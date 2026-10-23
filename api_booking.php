<?php
// api_booking.php - David Hair Springvale Fitting Booking API
error_reporting(0);
ini_set('display_errors', '0');
ob_start();
session_start();

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/notification_helper.php';

header('Content-Type: application/json; charset=utf-8');

// Ensure tables exist
try {
    $conn->exec("
        CREATE TABLE IF NOT EXISTS fitting_bookings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            customer_name VARCHAR(100) NOT NULL,
            phone VARCHAR(50) NOT NULL,
            email VARCHAR(150) NULL,
            service_type VARCHAR(100) NOT NULL,
            booking_date DATE NOT NULL,
            booking_time VARCHAR(20) NOT NULL,
            status VARCHAR(20) DEFAULT 'Pending',
            notes TEXT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        CREATE TABLE IF NOT EXISTS salon_closure_periods (
            id INT AUTO_INCREMENT PRIMARY KEY,
            start_date DATE NOT NULL,
            end_date DATE NOT NULL,
            reason VARCHAR(255) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        CREATE TABLE IF NOT EXISTS salon_schedule_settings (
            setting_key VARCHAR(50) PRIMARY KEY,
            setting_value TEXT NOT NULL,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // Insert default regular_off_days ([0] = Sunday) if not present
    $stmtInit = $conn->prepare("SELECT COUNT(*) FROM salon_schedule_settings WHERE setting_key = 'regular_off_days'");
    $stmtInit->execute();
    if ($stmtInit->fetchColumn() == 0) {
        $conn->exec("INSERT INTO salon_schedule_settings (setting_key, setting_value) VALUES ('regular_off_days', '[0]')");
    }
} catch (Exception $e) {
    // Table might already exist or permission issue
}

// Helper to retrieve salon schedule & closure rules
function get_salon_closure_rules($conn) {
    $regularOffDays = [0]; // Default: Sunday (0)
    try {
        $stmt = $conn->prepare("SELECT setting_value FROM salon_schedule_settings WHERE setting_key = 'regular_off_days'");
        $stmt->execute();
        $val = $stmt->fetchColumn();
        if ($val !== false && $val !== null) {
            $decoded = json_decode($val, true);
            if (is_array($decoded)) {
                $regularOffDays = array_values(array_unique(array_map('intval', $decoded)));
            }
        }
    } catch (Exception $e) {}

    $closurePeriods = [];
    try {
        $stmt = $conn->query("SELECT * FROM salon_closure_periods ORDER BY start_date ASC");
        $closurePeriods = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {}

    return [
        'regular_off_days' => $regularOffDays,
        'closure_periods' => $closurePeriods
    ];
}

// Helper to check if a specific date is closed
function check_date_is_closed($dateStr, $rules) {
    if (empty($dateStr)) {
        return ['is_closed' => false, 'reason' => ''];
    }

    $ts = strtotime($dateStr);
    $dayOfWeek = intval(date('w', $ts)); // 0=Sun, 1=Mon, ..., 6=Sat
    $dayName = date('l', $ts);

    // 1. Regular weekly off-day check
    if (in_array($dayOfWeek, $rules['regular_off_days'], true)) {
        return [
            'is_closed' => true,
            'reason' => "Regular Weekly Off-Day ({$dayName})"
        ];
    }

    // 2. Specific period closure check (start_date <= dateStr <= end_date)
    foreach ($rules['closure_periods'] as $period) {
        if ($dateStr >= $period['start_date'] && $dateStr <= $period['end_date']) {
            $reason = !empty($period['reason']) ? $period['reason'] : 'Salon Holiday';
            return [
                'is_closed' => true,
                'reason' => $reason
            ];
        }
    }

    return ['is_closed' => false, 'reason' => ''];
}

$action = $_REQUEST['action'] ?? 'list';

// AUTHENTICATION GUARD FOR ADMIN ACTIONS
$adminBookingActions = [
    'list_bookings',
    'update_status',
    'reschedule_booking',
    'delete_booking',
    'save_regular_off_days',
    'add_closure_period',
    'delete_closure_period',
    'get_notification_config',
    'save_notification_config',
    'test_notification'
];

if (in_array($action, $adminBookingActions)) {
    if (empty($_SESSION['admin_user'])) {
        if (ob_get_length()) ob_clean();
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized. Admin login required.'], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// Default daily operating hours (11:00 AM - 5:00 PM, 1-hour interval, total 7 slots)
$standardSlots = ['11:00 AM', '12:00 PM', '01:00 PM', '02:00 PM', '03:00 PM', '04:00 PM', '05:00 PM'];
$totalSlotsPerDay = count($standardSlots); // 7 slots

// 1. GET MONTH AVAILABILITY (returns green, yellow, red, or gray/closed for each day in a month)
if ($action === 'get_month_status') {
    $yearMonth = $_GET['month'] ?? date('Y-m'); // e.g. '2026-10'
    $startDate = $yearMonth . '-01';
    $endDate = date('Y-m-t', strtotime($startDate));

    try {
        $rules = get_salon_closure_rules($conn);

        $stmt = $conn->prepare("
            SELECT booking_date, COUNT(*) as booked_count 
            FROM fitting_bookings 
            WHERE booking_date BETWEEN :start AND :end 
              AND status != 'Cancelled'
            GROUP BY booking_date
        ");
        $stmt->execute([':start' => $startDate, ':end' => $endDate]);
        $counts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // ['2026-10-15' => 3, ...]

        $daysInMonth = intval(date('t', strtotime($startDate)));
        $dayStatus = [];

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $currDate = sprintf('%s-%02d', $yearMonth, $d);
            $closureCheck = check_date_is_closed($currDate, $rules);

            if ($closureCheck['is_closed']) {
                $dayStatus[$currDate] = [
                    'is_closed' => true,
                    'closed_reason' => $closureCheck['reason'],
                    'booked_count' => intval($counts[$currDate] ?? 0),
                    'total_slots' => 0,
                    'dot' => 'gray'
                ];
            } else {
                $cnt = intval($counts[$currDate] ?? 0);
                $ratio = $cnt / $totalSlotsPerDay;
                if ($ratio >= 1.0) {
                    $dot = 'red'; // 100% full
                } elseif ($ratio >= 0.7) {
                    $dot = 'yellow'; // 70% or more booked
                } else {
                    $dot = 'green'; // plenty of slots
                }
                $dayStatus[$currDate] = [
                    'is_closed' => false,
                    'closed_reason' => '',
                    'booked_count' => $cnt,
                    'total_slots' => $totalSlotsPerDay,
                    'dot' => $dot
                ];
            }
        }

        echo json_encode([
            'status' => 'success',
            'month' => $yearMonth,
            'total_slots_per_day' => $totalSlotsPerDay,
            'standard_slots' => $standardSlots,
            'regular_off_days' => $rules['regular_off_days'],
            'closure_periods' => $rules['closure_periods'],
            'data' => $dayStatus
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// 2. GET DAY SLOTS (available vs booked for a specific date)
if ($action === 'get_day_slots') {
    $date = $_GET['date'] ?? date('Y-m-d');
    try {
        $rules = get_salon_closure_rules($conn);
        $closureCheck = check_date_is_closed($date, $rules);

        if ($closureCheck['is_closed']) {
            echo json_encode([
                'status' => 'success',
                'date' => $date,
                'is_closed' => true,
                'closed_reason' => $closureCheck['reason'],
                'dot' => 'gray',
                'booked_count' => 0,
                'total_slots' => 0,
                'slots' => []
            ]);
            exit;
        }

        $stmt = $conn->prepare("
            SELECT booking_time 
            FROM fitting_bookings 
            WHERE booking_date = :date AND status != 'Cancelled'
        ");
        $stmt->execute([':date' => $date]);
        $bookedTimes = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $slots = [];
        foreach ($standardSlots as $slot) {
            $isBooked = in_array($slot, $bookedTimes);
            $slots[] = [
                'time' => $slot,
                'available' => !$isBooked
            ];
        }

        $bookedCount = count($bookedTimes);
        $ratio = $bookedCount / $totalSlotsPerDay;
        $dot = ($ratio >= 1.0) ? 'red' : (($ratio >= 0.7) ? 'yellow' : 'green');

        echo json_encode([
            'status' => 'success',
            'date' => $date,
            'is_closed' => false,
            'closed_reason' => '',
            'dot' => $dot,
            'booked_count' => $bookedCount,
            'total_slots' => $totalSlotsPerDay,
            'slots' => $slots
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// 3. CREATE BOOKING (Customer submission)
if ($action === 'create_booking') {
    $name = trim($_POST['customer_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $service = trim($_POST['service_type'] ?? 'Free Consultation & Hairline Assessment ($0)');
    $date = trim($_POST['booking_date'] ?? '');
    $time = trim($_POST['booking_time'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if (empty($name) || empty($phone) || empty($date) || empty($time)) {
        echo json_encode(['status' => 'error', 'message' => 'Please provide Name, Phone, Date, and Time.']);
        exit;
    }

    try {
        // Enforce salon closure check
        $rules = get_salon_closure_rules($conn);
        $closureCheck = check_date_is_closed($date, $rules);
        if ($closureCheck['is_closed']) {
            echo json_encode([
                'status' => 'error',
                'message' => "David Hair Salon is closed on {$date} ({$closureCheck['reason']}). Please choose an alternate date."
            ]);
            exit;
        }

        // Check if slot is already taken
        $check = $conn->prepare("
            SELECT id FROM fitting_bookings 
            WHERE booking_date = :date AND booking_time = :time AND status != 'Cancelled'
        ");
        $check->execute([':date' => $date, ':time' => $time]);
        if ($check->fetch()) {
            echo json_encode(['status' => 'error', 'message' => 'This time slot is already booked. Please choose another slot.']);
            exit;
        }

        $stmt = $conn->prepare("
            INSERT INTO fitting_bookings (customer_name, phone, email, service_type, booking_date, booking_time, status, notes)
            VALUES (:name, :phone, :email, :service, :date, :time, 'Pending', :notes)
        ");
        $stmt->execute([
            ':name' => $name,
            ':phone' => $phone,
            ':email' => $email,
            ':service' => $service,
            ':date' => $date,
            ':time' => $time,
            ':notes' => $notes
        ]);

        $bookingId = $conn->lastInsertId();

        // Dispatch real-time Telegram and Email alerts to Admin
        $notifyResults = dispatch_booking_notifications($conn, [
            'booking_id' => $bookingId,
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'service' => $service,
            'date' => $date,
            'time' => $time,
            'notes' => $notes
        ]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Your Springvale fitting appointment has been submitted successfully!',
            'booking_id' => $bookingId,
            'notifications' => $notifyResults,
            'details' => [
                'name' => $name,
                'phone' => $phone,
                'date' => $date,
                'time' => $time,
                'service' => $service
            ]
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// 4. LIST BOOKINGS (Admin)
if ($action === 'list_bookings') {
    try {
        $stmt = $conn->query("
            SELECT * FROM fitting_bookings 
            ORDER BY booking_date DESC, booking_time ASC, id DESC
        ");
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success',
            'data' => $bookings
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// 5. UPDATE BOOKING STATUS (Admin)
if ($action === 'update_status') {
    $id = intval($_POST['id'] ?? 0);
    $newStatus = trim($_POST['status'] ?? 'Confirmed'); // 'Pending', 'Confirmed', 'Completed', 'Cancelled'
    $notes = isset($_POST['notes']) ? trim($_POST['notes']) : null;

    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid booking ID']);
        exit;
    }

    try {
        if ($notes !== null) {
            $stmt = $conn->prepare("UPDATE fitting_bookings SET status = :status, notes = :notes WHERE id = :id");
            $stmt->execute([':status' => $newStatus, ':notes' => $notes, ':id' => $id]);
        } else {
            $stmt = $conn->prepare("UPDATE fitting_bookings SET status = :status WHERE id = :id");
            $stmt->execute([':status' => $newStatus, ':id' => $id]);
        }

        echo json_encode([
            'status' => 'success',
            'message' => "Booking #{$id} status updated to {$newStatus}."
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// 5.5 RESCHEDULE BOOKING (Drag & Drop Date & Time)
if ($action === 'reschedule_booking') {
    $id = intval($_POST['id'] ?? 0);
    $newDate = trim($_POST['booking_date'] ?? '');
    $newTime = trim($_POST['booking_time'] ?? '');

    if ($id <= 0 || empty($newDate) || empty($newTime)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid ID, Date, or Time for rescheduling']);
        exit;
    }

    try {
        // Enforce salon closure check
        $rules = get_salon_closure_rules($conn);
        $closureCheck = check_date_is_closed($newDate, $rules);
        if ($closureCheck['is_closed']) {
            echo json_encode([
                'status' => 'error',
                'message' => "Cannot reschedule: David Hair Salon is closed on {$newDate} ({$closureCheck['reason']})."
            ]);
            exit;
        }

        $stmt = $conn->prepare("UPDATE fitting_bookings SET booking_date = :bdate, booking_time = :btime WHERE id = :id");
        $stmt->execute([':bdate' => $newDate, ':btime' => $newTime, ':id' => $id]);

        echo json_encode([
            'status' => 'success',
            'message' => "Booking #{$id} rescheduled to {$newDate} at {$newTime}.",
            'booking_id' => $id,
            'booking_date' => $newDate,
            'booking_time' => $newTime
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// 6. DELETE BOOKING (Admin)
if ($action === 'delete_booking') {
    $id = intval($_REQUEST['id'] ?? 0);
    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid booking ID']);
        exit;
    }
    try {
        $stmt = $conn->prepare("DELETE FROM fitting_bookings WHERE id = :id");
        $stmt->execute([':id' => $id]);
        echo json_encode(['status' => 'success', 'message' => 'Booking deleted successfully.']);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// 7. GET CLOSURE RULES (Admin & Frontend)
if ($action === 'get_closure_rules') {
    try {
        $rules = get_salon_closure_rules($conn);
        echo json_encode([
            'status' => 'success',
            'regular_off_days' => $rules['regular_off_days'],
            'closure_periods' => $rules['closure_periods']
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// 8. SAVE REGULAR OFF DAYS (Admin)
if ($action === 'save_regular_off_days') {
    try {
        $raw = $_POST['days'] ?? '[]';
        $days = is_array($raw) ? $raw : json_decode($raw, true);
        if (!is_array($days)) {
            $days = array_filter(array_map('trim', explode(',', (string)$raw)), 'strlen');
        }

        $cleanDays = [];
        foreach ($days as $d) {
            $val = intval($d);
            if ($val >= 0 && $val <= 6 && !in_array($val, $cleanDays, true)) {
                $cleanDays[] = $val;
            }
        }
        sort($cleanDays);

        $jsonVal = json_encode($cleanDays);
        $stmt = $conn->prepare("
            INSERT INTO salon_schedule_settings (setting_key, setting_value) 
            VALUES ('regular_off_days', :val)
            ON DUPLICATE KEY UPDATE setting_value = :val2
        ");
        $stmt->execute([':val' => $jsonVal, ':val2' => $jsonVal]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Regular off-days updated successfully.',
            'regular_off_days' => $cleanDays
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// 9. ADD CLOSURE PERIOD (Admin)
if ($action === 'add_closure_period') {
    $startDate = trim($_POST['start_date'] ?? '');
    $endDate = trim($_POST['end_date'] ?? '');
    $reason = trim($_POST['reason'] ?? 'Holiday');

    if (empty($startDate) || empty($endDate)) {
        echo json_encode(['status' => 'error', 'message' => 'Please provide both Start Date and End Date.']);
        exit;
    }

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid date format (must be YYYY-MM-DD).']);
        exit;
    }

    // If start > end, swap them
    if ($startDate > $endDate) {
        $tmp = $startDate;
        $startDate = $endDate;
        $endDate = $tmp;
    }

    try {
        $stmt = $conn->prepare("
            INSERT INTO salon_closure_periods (start_date, end_date, reason) 
            VALUES (:start_date, :end_date, :reason)
        ");
        $stmt->execute([
            ':start_date' => $startDate,
            ':end_date' => $endDate,
            ':reason' => $reason
        ]);

        $rules = get_salon_closure_rules($conn);

        echo json_encode([
            'status' => 'success',
            'message' => "Closure period ({$startDate} ~ {$endDate}) added successfully.",
            'id' => $conn->lastInsertId(),
            'closure_periods' => $rules['closure_periods']
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// 10. DELETE CLOSURE PERIOD (Admin)
if ($action === 'delete_closure_period') {
    $id = intval($_REQUEST['id'] ?? 0);
    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid closure period ID']);
        exit;
    }

    try {
        $stmt = $conn->prepare("DELETE FROM salon_closure_periods WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $rules = get_salon_closure_rules($conn);

        echo json_encode([
            'status' => 'success',
            'message' => 'Closure period deleted successfully.',
            'closure_periods' => $rules['closure_periods']
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// 11. GET NOTIFICATION CONFIG (Admin)
if ($action === 'get_notification_config') {
    try {
        $settings = get_notification_settings($conn);
        // Mask token for display
        $maskedToken = '';
        if (!empty($settings['telegram_bot_token'])) {
            $t = $settings['telegram_bot_token'];
            $len = strlen($t);
            $maskedToken = ($len > 10) ? substr($t, 0, 6) . '...' . substr($t, -4) : '******';
        }
        $settings['telegram_bot_token_masked'] = $maskedToken;
        $settings['has_telegram_token'] = !empty($settings['telegram_bot_token']);

        echo json_encode([
            'status' => 'success',
            'data' => $settings
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// 12. SAVE NOTIFICATION CONFIG (Admin)
if ($action === 'save_notification_config') {
    try {
        $allowedKeys = [
            'telegram_bot_token',
            'telegram_chat_id',
            'telegram_enabled',
            'notification_email',
            'email_enabled',
            'smtp_host',
            'smtp_port',
            'smtp_user',
            'smtp_pass'
        ];

        foreach ($allowedKeys as $k) {
            if (isset($_POST[$k])) {
                $v = trim($_POST[$k]);
                // If token is masked string, do not overwrite
                if ($k === 'telegram_bot_token' && strpos($v, '...') !== false) {
                    continue;
                }
                $stmt = $conn->prepare("
                    INSERT INTO salon_schedule_settings (setting_key, setting_value)
                    VALUES (:key, :val)
                    ON DUPLICATE KEY UPDATE setting_value = :val2
                ");
                $stmt->execute([':key' => $k, ':val' => $v, ':val2' => $v]);
            }
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Notification settings saved successfully.'
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// 13. TEST NOTIFICATION (Admin)
if ($action === 'test_notification') {
    $type = $_POST['type'] ?? 'telegram'; // 'telegram' or 'email'
    $settings = get_notification_settings($conn);

    $testBooking = [
        'booking_id' => 999,
        'customer_name' => 'David Hair Test',
        'phone' => '+61 433 484 882',
        'email' => 'shc9607@gmail.com',
        'service_type' => 'Test Fitting Appointment ($0)',
        'booking_date' => date('Y-m-d'),
        'booking_time' => '02:00 PM',
        'notes' => 'This is a test notification to verify Telegram/Email alerts.'
    ];

    if ($type === 'telegram') {
        $botToken = trim($_POST['telegram_bot_token'] ?? $settings['telegram_bot_token']);
        $chatId = trim($_POST['telegram_chat_id'] ?? $settings['telegram_chat_id']);

        if (empty($botToken) || empty($chatId)) {
            echo json_encode(['status' => 'error', 'message' => 'Please provide both Telegram Bot Token and Chat ID.']);
            exit;
        }

        $res = send_telegram_booking_alert($testBooking, $botToken, $chatId);
        echo json_encode($res);
        exit;
    }

    if ($type === 'email') {
        $email = trim($_POST['notification_email'] ?? $settings['notification_email']);
        $res = send_email_booking_alert($testBooking, $email);
        echo json_encode($res);
        exit;
    }

    echo json_encode(['status' => 'error', 'message' => 'Unknown notification test type.']);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
