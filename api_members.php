<?php
// api_members.php - Complete Membership Management & Performance Tracking API
error_reporting(0);
ini_set('display_errors', '0');
ob_start();
session_start();

function sendJsonResponse($data, $statusCode = 200) {
    if (ob_get_length()) ob_clean();
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    sendJsonResponse(['status' => 'ok']);
}

require_once __DIR__ . '/config.php';

// Auto-create 'members' and 'member_records' tables
try {
    $conn->exec("CREATE TABLE IF NOT EXISTS members (
        id INT AUTO_INCREMENT PRIMARY KEY,
        member_code VARCHAR(50) UNIQUE NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(150) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        phone VARCHAR(50) DEFAULT '',
        hair_system_type VARCHAR(100) DEFAULT 'Men Custom Toupee',
        tier VARCHAR(50) DEFAULT 'Bronze',
        points INT DEFAULT 100,
        total_spent DECIMAL(10,2) DEFAULT 0.00,
        visit_count INT DEFAULT 0,
        referral_code VARCHAR(50) UNIQUE,
        referred_by VARCHAR(50) DEFAULT NULL,
        referral_count INT DEFAULT 0,
        status VARCHAR(30) DEFAULT 'Active',
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $conn->exec("CREATE TABLE IF NOT EXISTS member_records (
        id INT AUTO_INCREMENT PRIMARY KEY,
        member_id INT NOT NULL,
        type VARCHAR(50) NOT NULL,
        title VARCHAR(200) NOT NULL,
        amount DECIMAL(10,2) DEFAULT 0.00,
        points_delta INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_member (member_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Seed initial members if empty
    $countStmt = $conn->query("SELECT COUNT(*) FROM members");
    if ($countStmt->fetchColumn() == 0) {
        $seedMembers = [
            [
                'member_code' => 'DHM-1001',
                'full_name' => 'John Smith',
                'email' => 'john.smith@example.com',
                'password_hash' => password_hash('password123', PASSWORD_BCRYPT),
                'phone' => '+61 412 345 678',
                'hair_system_type' => '0.03mm Ultra-Thin Skin (90% Density)',
                'tier' => 'Gold',
                'points' => 350,
                'total_spent' => 1450.00,
                'visit_count' => 4,
                'referral_code' => 'JOHNSMITH',
                'referral_count' => 2,
                'notes' => 'Prefers Springvale Master Cut-In. Low-taper fade style.'
            ],
            [
                'member_code' => 'DHM-1002',
                'full_name' => 'Michael Brown',
                'email' => 'michael.b@example.com',
                'password_hash' => password_hash('password123', PASSWORD_BCRYPT),
                'phone' => '+61 423 456 789',
                'hair_system_type' => 'French Lace Front + PU Perimeter',
                'tier' => 'Silver',
                'points' => 150,
                'total_spent' => 620.00,
                'visit_count' => 2,
                'referral_code' => 'MIKEBROWN',
                'referral_count' => 1,
                'notes' => 'Active gym member, uses Scalp Protector Max.'
            ],
            [
                'member_code' => 'DHM-1003',
                'full_name' => 'David Lee',
                'email' => 'david.lee@example.com',
                'password_hash' => password_hash('password123', PASSWORD_BCRYPT),
                'phone' => '+61 434 567 890',
                'hair_system_type' => 'Custom Silk Top Toupee (1B Off Black)',
                'tier' => 'VIP Partner',
                'points' => 850,
                'total_spent' => 3200.00,
                'visit_count' => 9,
                'referral_code' => 'DAVIDLEE',
                'referral_count' => 5,
                'notes' => 'Salon affiliate partner in South Yarra.'
            ]
        ];

        $ins = $conn->prepare("INSERT INTO members (member_code, full_name, email, password_hash, phone, hair_system_type, tier, points, total_spent, visit_count, referral_code, referral_count, notes) 
                               VALUES (:member_code, :full_name, :email, :password_hash, :phone, :hair_system_type, :tier, :points, :total_spent, :visit_count, :referral_code, :referral_count, :notes)");
        
        $recIns = $conn->prepare("INSERT INTO member_records (member_id, type, title, amount, points_delta) VALUES (:member_id, :type, :title, :amount, :points_delta)");

        foreach ($seedMembers as $m) {
            $ins->execute($m);
            $newId = $conn->lastInsertId();
            // Seed a welcome point record and initial purchase
            $recIns->execute([
                'member_id' => $newId,
                'type' => 'points_bonus',
                'title' => 'Welcome Member Bonus Points',
                'amount' => 0.00,
                'points_delta' => 100
            ]);
            $recIns->execute([
                'member_id' => $newId,
                'type' => 'order',
                'title' => 'Hairpiece Order & Master Salon Cut-In',
                'amount' => $m['total_spent'],
                'points_delta' => intval($m['total_spent'] / 10)
            ]);
        }
    }
} catch (Exception $e) {
    // Keep running
}

$action = isset($_GET['action']) ? trim($_GET['action']) : (isset($_POST['action']) ? trim($_POST['action']) : '');

// Read JSON input if sent as body
$rawInput = file_get_contents('php://input');
$jsonBody = json_decode($rawInput, true);
if (is_array($jsonBody)) {
    if (empty($action) && isset($jsonBody['action'])) {
        $action = trim($jsonBody['action']);
    }
}

// AUTHENTICATION GUARD FOR ADMIN MEMBER ACTIONS
$adminMemberActions = ['list', 'create', 'update', 'delete', 'add_record'];
if (in_array($action, $adminMemberActions)) {
    if (empty($_SESSION['admin_user'])) {
        sendJsonResponse(['status' => 'error', 'message' => 'Unauthorized. Admin login required.'], 401);
    }
}

switch ($action) {
    // -------------------------------------------------------------
    // 1. PUBLIC: MEMBER REGISTRATION
    // -------------------------------------------------------------
    case 'register':
        $fullName = trim($jsonBody['full_name'] ?? $_POST['full_name'] ?? '');
        $email = strtolower(trim($jsonBody['email'] ?? $_POST['email'] ?? ''));
        $password = trim($jsonBody['password'] ?? $_POST['password'] ?? '');
        $phone = trim($jsonBody['phone'] ?? $_POST['phone'] ?? '');
        $hairSystemType = trim($jsonBody['hair_system_type'] ?? $_POST['hair_system_type'] ?? 'Men 0.03mm Skin Toupee');
        $referralCodeInput = strtoupper(trim($jsonBody['referred_by'] ?? $_POST['referred_by'] ?? ''));

        if (empty($fullName) || empty($email) || empty($password)) {
            sendJsonResponse(['status' => 'error', 'message' => 'Full Name, Email, and Password are required.'], 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            sendJsonResponse(['status' => 'error', 'message' => 'Please provide a valid email address.'], 400);
        }

        if (strlen($password) < 6) {
            sendJsonResponse(['status' => 'error', 'message' => 'Password must be at least 6 characters long.'], 400);
        }

        // Check if email already registered
        $checkStmt = $conn->prepare("SELECT id FROM members WHERE email = :email");
        $checkStmt->execute(['email' => $email]);
        if ($checkStmt->fetch()) {
            sendJsonResponse(['status' => 'error', 'message' => 'This email address is already registered. Please sign in.'], 409);
        }

        // Generate Unique Member Code and Referral Code
        $memberSeq = rand(1000, 9999);
        $memberCode = 'DHM-' . $memberSeq;
        $myReferralCode = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', substr($fullName, 0, 6))) . rand(10, 99);

        // Check referrer
        $referredBy = null;
        if (!empty($referralCodeInput)) {
            $refStmt = $conn->prepare("SELECT id, referral_code FROM members WHERE referral_code = :ref");
            $refStmt->execute(['ref' => $referralCodeInput]);
            $referrer = $refStmt->fetch(PDO::FETCH_ASSOC);
            if ($referrer) {
                $referredBy = $referrer['referral_code'];
                // Give referrer +50 referral bonus points and increment referral_count
                $conn->prepare("UPDATE members SET referral_count = referral_count + 1, points = points + 50 WHERE id = :id")
                     ->execute(['id' => $referrer['id']]);
                
                $conn->prepare("INSERT INTO member_records (member_id, type, title, amount, points_delta) VALUES (:mid, 'referral_bonus', :title, 0.00, 50)")
                     ->execute(['mid' => $referrer['id'], 'title' => "Referral Bonus (Referred {$fullName})"]);
            }
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $welcomePoints = 100; // Welcome reward points

        $stmt = $conn->prepare("INSERT INTO members (member_code, full_name, email, password_hash, phone, hair_system_type, tier, points, total_spent, visit_count, referral_code, referred_by, status) 
                               VALUES (:code, :name, :email, :pwd, :phone, :htype, 'Bronze', :pts, 0.00, 0, :ref, :refby, 'Active')");
        $stmt->execute([
            'code' => $memberCode,
            'name' => $fullName,
            'email' => $email,
            'pwd' => $passwordHash,
            'phone' => $phone,
            'htype' => $hairSystemType,
            'pts' => $welcomePoints,
            'ref' => $myReferralCode,
            'refby' => $referredBy
        ]);

        $newMemberId = $conn->lastInsertId();

        // Add welcome bonus record
        $conn->prepare("INSERT INTO member_records (member_id, type, title, amount, points_delta) VALUES (:mid, 'points_bonus', 'Welcome Membership Bonus (+100 pts)', 0.00, 100)")
             ->execute(['mid' => $newMemberId]);

        // Auto login session
        $_SESSION['member_id'] = $newMemberId;
        $_SESSION['member_email'] = $email;
        $_SESSION['member_name'] = $fullName;

        sendJsonResponse([
            'status' => 'success',
            'message' => 'Welcome to David Hair Membership! You have received 100 Welcome Points.',
            'member' => [
                'id' => $newMemberId,
                'member_code' => $memberCode,
                'full_name' => $fullName,
                'email' => $email,
                'phone' => $phone,
                'tier' => 'Bronze',
                'points' => $welcomePoints,
                'total_spent' => '0.00',
                'referral_code' => $myReferralCode,
                'hair_system_type' => $hairSystemType
            ]
        ]);
        break;

    // -------------------------------------------------------------
    // 2. PUBLIC: MEMBER LOGIN
    // -------------------------------------------------------------
    case 'login':
        $email = strtolower(trim($jsonBody['email'] ?? $_POST['email'] ?? ''));
        $password = trim($jsonBody['password'] ?? $_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            sendJsonResponse(['status' => 'error', 'message' => 'Email and Password are required.'], 400);
        }

        $stmt = $conn->prepare("SELECT * FROM members WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $member = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$member || !password_verify($password, $member['password_hash'])) {
            sendJsonResponse(['status' => 'error', 'message' => 'Invalid email or password. Please check your credentials.'], 401);
        }

        if ($member['status'] !== 'Active') {
            sendJsonResponse(['status' => 'error', 'message' => 'Your account is currently inactive. Please contact Springvale support.'], 403);
        }

        $_SESSION['member_id'] = $member['id'];
        $_SESSION['member_email'] = $member['email'];
        $_SESSION['member_name'] = $member['full_name'];

        unset($member['password_hash']);

        sendJsonResponse([
            'status' => 'success',
            'message' => 'Signed in successfully. Welcome back, ' . $member['full_name'] . '!',
            'member' => $member
        ]);
        break;

    // -------------------------------------------------------------
    // 3. PUBLIC: CURRENT MEMBER SESSION PROFILE
    // -------------------------------------------------------------
    case 'me':
        $memberId = $_SESSION['member_id'] ?? null;
        // Only allow explicit member_id lookup if authenticated as administrator
        if (!empty($_SESSION['admin_user']) && isset($_GET['member_id'])) {
            $memberId = intval($_GET['member_id']);
        }

        if (!$memberId) {
            sendJsonResponse(['status' => 'error', 'logged_in' => false, 'message' => 'No active member session.'], 200);
        }

        $stmt = $conn->prepare("SELECT id, member_code, full_name, email, phone, hair_system_type, tier, points, total_spent, visit_count, referral_code, referral_count, status, created_at FROM members WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $memberId]);
        $member = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$member) {
            sendJsonResponse(['status' => 'error', 'logged_in' => false, 'message' => 'Member not found.'], 200);
        }

        // Fetch recent records
        $recStmt = $conn->prepare("SELECT * FROM member_records WHERE member_id = :mid ORDER BY created_at DESC LIMIT 10");
        $recStmt->execute(['mid' => $memberId]);
        $records = $recStmt->fetchAll(PDO::FETCH_ASSOC);

        sendJsonResponse([
            'status' => 'success',
            'logged_in' => true,
            'member' => $member,
            'records' => $records
        ]);
        break;

    // -------------------------------------------------------------
    // 4. PUBLIC: MEMBER LOGOUT
    // -------------------------------------------------------------
    case 'logout':
        unset($_SESSION['member_id'], $_SESSION['member_email'], $_SESSION['member_name']);
        sendJsonResponse(['status' => 'success', 'message' => 'Signed out successfully.']);
        break;

    // -------------------------------------------------------------
    // 5. ADMIN: LIST ALL MEMBERS & PERFORMANCE
    // -------------------------------------------------------------
    case 'list':
        $search = trim($_GET['search'] ?? '');
        $tierFilter = trim($_GET['tier'] ?? '');
        $statusFilter = trim($_GET['status'] ?? '');

        $sql = "SELECT id, member_code, full_name, email, phone, hair_system_type, tier, points, total_spent, visit_count, referral_code, referral_count, status, notes, created_at FROM members WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (full_name LIKE :s1 OR email LIKE :s2 OR phone LIKE :s3 OR member_code LIKE :s4)";
            $params['s1'] = "%$search%";
            $params['s2'] = "%$search%";
            $params['s3'] = "%$search%";
            $params['s4'] = "%$search%";
        }

        if (!empty($tierFilter) && $tierFilter !== 'All') {
            $sql .= " AND tier = :tier";
            $params['tier'] = $tierFilter;
        }

        if (!empty($statusFilter) && $statusFilter !== 'All') {
            $sql .= " AND status = :st";
            $params['st'] = $statusFilter;
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calculate KPI summary
        $statsStmt = $conn->query("SELECT 
            COUNT(*) as total_members,
            COALESCE(SUM(total_spent), 0) as total_revenue,
            COALESCE(SUM(points), 0) as total_points,
            SUM(CASE WHEN tier IN ('Gold', 'VIP Partner') THEN 1 ELSE 0 END) as vip_count,
            COALESCE(SUM(referral_count), 0) as total_referrals
        FROM members");
        $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);

        sendJsonResponse([
            'status' => 'success',
            'data' => $members,
            'stats' => $stats
        ]);
        break;

    // -------------------------------------------------------------
    // 6. ADMIN: CREATE MEMBER
    // -------------------------------------------------------------
    case 'create':
        $fullName = trim($jsonBody['full_name'] ?? '');
        $email = strtolower(trim($jsonBody['email'] ?? ''));
        $password = trim($jsonBody['password'] ?? 'password123');
        $phone = trim($jsonBody['phone'] ?? '');
        $hairSystemType = trim($jsonBody['hair_system_type'] ?? 'Men 0.03mm Skin Toupee');
        $tier = trim($jsonBody['tier'] ?? 'Bronze');
        $points = intval($jsonBody['points'] ?? 100);
        $totalSpent = floatval($jsonBody['total_spent'] ?? 0.00);
        $visitCount = intval($jsonBody['visit_count'] ?? 0);
        $notes = trim($jsonBody['notes'] ?? '');

        if (empty($fullName) || empty($email)) {
            sendJsonResponse(['status' => 'error', 'message' => 'Full Name and Email are required.'], 400);
        }

        // Check duplicate email
        $chk = $conn->prepare("SELECT id FROM members WHERE email = :email");
        $chk->execute(['email' => $email]);
        if ($chk->fetch()) {
            sendJsonResponse(['status' => 'error', 'message' => 'Email already registered.'], 409);
        }

        $memberCode = 'DHM-' . rand(1000, 9999);
        $referralCode = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', substr($fullName, 0, 6))) . rand(10, 99);
        $pwdHash = password_hash($password, PASSWORD_BCRYPT);

        $ins = $conn->prepare("INSERT INTO members (member_code, full_name, email, password_hash, phone, hair_system_type, tier, points, total_spent, visit_count, referral_code, notes, status) 
                               VALUES (:code, :name, :email, :pwd, :phone, :htype, :tier, :pts, :spent, :vis, :ref, :notes, 'Active')");
        $ins->execute([
            'code' => $memberCode,
            'name' => $fullName,
            'email' => $email,
            'pwd' => $pwdHash,
            'phone' => $phone,
            'htype' => $hairSystemType,
            'tier' => $tier,
            'pts' => $points,
            'spent' => $totalSpent,
            'vis' => $visitCount,
            'ref' => $referralCode,
            'notes' => $notes
        ]);

        sendJsonResponse(['status' => 'success', 'message' => 'Member created successfully. Code: ' . $memberCode]);
        break;

    // -------------------------------------------------------------
    // 7. ADMIN: UPDATE MEMBER & PERFORMANCE
    // -------------------------------------------------------------
    case 'update':
        $id = intval($jsonBody['id'] ?? 0);
        if ($id <= 0) {
            sendJsonResponse(['status' => 'error', 'message' => 'Invalid member ID.'], 400);
        }

        $fullName = trim($jsonBody['full_name'] ?? '');
        $email = strtolower(trim($jsonBody['email'] ?? ''));
        $phone = trim($jsonBody['phone'] ?? '');
        $hairSystemType = trim($jsonBody['hair_system_type'] ?? '');
        $tier = trim($jsonBody['tier'] ?? 'Bronze');
        $points = intval($jsonBody['points'] ?? 0);
        $totalSpent = floatval($jsonBody['total_spent'] ?? 0.00);
        $visitCount = intval($jsonBody['visit_count'] ?? 0);
        $status = trim($jsonBody['status'] ?? 'Active');
        $notes = trim($jsonBody['notes'] ?? '');

        $sql = "UPDATE members SET full_name = :name, email = :email, phone = :phone, hair_system_type = :htype, tier = :tier, points = :pts, total_spent = :spent, visit_count = :vis, status = :status, notes = :notes";
        $params = [
            'id' => $id,
            'name' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'htype' => $hairSystemType,
            'tier' => $tier,
            'pts' => $points,
            'spent' => $totalSpent,
            'vis' => $visitCount,
            'status' => $status,
            'notes' => $notes
        ];

        // Optional password change
        if (!empty($jsonBody['password'])) {
            $sql .= ", password_hash = :pwd";
            $params['pwd'] = password_hash(trim($jsonBody['password']), PASSWORD_BCRYPT);
        }

        $sql .= " WHERE id = :id";
        $upd = $conn->prepare($sql);
        $upd->execute($params);

        sendJsonResponse(['status' => 'success', 'message' => 'Member updated successfully.']);
        break;

    // -------------------------------------------------------------
    // 8. ADMIN: DELETE MEMBER
    // -------------------------------------------------------------
    case 'delete':
        $id = intval($jsonBody['id'] ?? $_POST['id'] ?? 0);
        if ($id <= 0) {
            sendJsonResponse(['status' => 'error', 'message' => 'Invalid member ID.'], 400);
        }

        $del = $conn->prepare("DELETE FROM members WHERE id = :id");
        $del->execute(['id' => $id]);

        $delRec = $conn->prepare("DELETE FROM member_records WHERE member_id = :id");
        $delRec->execute(['id' => $id]);

        sendJsonResponse(['status' => 'success', 'message' => 'Member deleted successfully.']);
        break;

    // -------------------------------------------------------------
    // 9. ADMIN: ADD PERFORMANCE RECORD (PURCHASE / SERVICE / POINTS)
    // -------------------------------------------------------------
    case 'add_record':
        $memberId = intval($jsonBody['member_id'] ?? 0);
        $type = trim($jsonBody['type'] ?? 'service'); // 'order', 'service', 'points_bonus', 'adjustment'
        $title = trim($jsonBody['title'] ?? '');
        $amount = floatval($jsonBody['amount'] ?? 0.00);
        $pointsDelta = intval($jsonBody['points_delta'] ?? 0);

        if ($memberId <= 0 || empty($title)) {
            sendJsonResponse(['status' => 'error', 'message' => 'Member ID and Title are required.'], 400);
        }

        // Insert record
        $recStmt = $conn->prepare("INSERT INTO member_records (member_id, type, title, amount, points_delta) VALUES (:mid, :type, :title, :amount, :pdelta)");
        $recStmt->execute([
            'mid' => $memberId,
            'type' => $type,
            'title' => $title,
            'amount' => $amount,
            'pdelta' => $pointsDelta
        ]);

        // Update member totals
        $updSql = "UPDATE members SET points = GREATEST(0, points + :pdelta)";
        $updParams = ['mid' => $memberId, 'pdelta' => $pointsDelta];

        if ($amount > 0) {
            $updSql .= ", total_spent = total_spent + :amt, visit_count = visit_count + 1";
            $updParams['amt'] = $amount;
        }

        // Automatic Tier Upgrade Logic based on total_spent
        // Bronze ($0 - $499) -> Silver ($500 - $1499) -> Gold ($1500 - $2999) -> VIP Partner ($3000+)
        $updSql .= ", tier = CASE 
            WHEN (total_spent + :amt_check) >= 3000 THEN 'VIP Partner'
            WHEN (total_spent + :amt_check) >= 1500 THEN 'Gold'
            WHEN (total_spent + :amt_check) >= 500 THEN 'Silver'
            ELSE tier
        END";
        $updParams['amt_check'] = ($amount > 0) ? $amount : 0;

        $updSql .= " WHERE id = :mid";
        $conn->prepare($updSql)->execute($updParams);

        sendJsonResponse(['status' => 'success', 'message' => 'Performance record added and member stats updated.']);
        break;

    // -------------------------------------------------------------
    // 10. GET MEMBER RECORDS
    // -------------------------------------------------------------
    case 'records':
        $memberId = intval($_GET['member_id'] ?? 0);
        $currentLoggedInMemberId = $_SESSION['member_id'] ?? 0;
        $isAdmin = !empty($_SESSION['admin_user']);

        // Must be logged in as admin or requesting own records
        if (!$isAdmin && ($currentLoggedInMemberId === 0 || $currentLoggedInMemberId !== $memberId)) {
            sendJsonResponse(['status' => 'error', 'message' => 'Unauthorized to view member records.'], 403);
        }

        if ($memberId <= 0) {
            sendJsonResponse(['status' => 'error', 'message' => 'Invalid member ID.'], 400);
        }

        $stmt = $conn->prepare("SELECT * FROM member_records WHERE member_id = :mid ORDER BY created_at DESC");
        $stmt->execute(['mid' => $memberId]);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

        sendJsonResponse(['status' => 'success', 'data' => $records]);
        break;

    default:
        sendJsonResponse(['status' => 'error', 'message' => 'Invalid or missing API action.'], 400);
        break;
}
