<?php
// api_booking.php - David Hair Springvale Fitting Booking API
require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');

// Ensure fitting_bookings table exists
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
    ");
} catch (Exception $e) {
    // Table might already exist or permission issue
}

$action = $_REQUEST['action'] ?? 'list';

// Default daily operating hours (11:00 AM - 5:00 PM, 1-hour interval, total 7 slots)
$standardSlots = ['11:00 AM', '12:00 PM', '01:00 PM', '02:00 PM', '03:00 PM', '04:00 PM', '05:00 PM'];
$totalSlotsPerDay = count($standardSlots); // 7 slots

// 1. GET MONTH AVAILABILITY (returns green, yellow, red dots for each day in a month)
if ($action === 'get_month_status') {
    $yearMonth = $_GET['month'] ?? date('Y-m'); // e.g. '2026-10'
    $startDate = $yearMonth . '-01';
    $endDate = date('Y-m-t', strtotime($startDate));

    try {
        $stmt = $conn->prepare("
            SELECT booking_date, COUNT(*) as booked_count 
            FROM fitting_bookings 
            WHERE booking_date BETWEEN :start AND :end 
              AND status != 'Cancelled'
            GROUP BY booking_date
        ");
        $stmt->execute([':start' => $startDate, ':end' => $endDate]);
        $counts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // ['2026-10-15' => 3, ...]

        // Calculate dot color for days with bookings
        $dayStatus = [];
        foreach ($counts as $date => $cnt) {
            $cnt = intval($cnt);
            $ratio = $cnt / $totalSlotsPerDay;
            if ($ratio >= 1.0) {
                $dot = 'red'; // 100% full
            } elseif ($ratio >= 0.7) {
                $dot = 'yellow'; // 70% or more booked
            } else {
                $dot = 'green'; // plenty of slots
            }
            $dayStatus[$date] = [
                'booked_count' => $cnt,
                'total_slots' => $totalSlotsPerDay,
                'dot' => $dot
            ];
        }

        echo json_encode([
            'status' => 'success',
            'month' => $yearMonth,
            'total_slots_per_day' => $totalSlotsPerDay,
            'standard_slots' => $standardSlots,
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

        echo json_encode([
            'status' => 'success',
            'message' => 'Your Springvale fitting appointment has been submitted successfully!',
            'booking_id' => $bookingId,
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

echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
