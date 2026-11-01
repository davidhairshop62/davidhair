<?php
// api_gallery.php - Database Table & API for Before/After Gallery
error_reporting(0);
ini_set('display_errors', '0');
ob_start();
session_start();

function sendJsonResponse($data, $statusCode = 200) {
    if (ob_get_length()) ob_clean();
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    sendJsonResponse(['status' => 'ok']);
}

require_once __DIR__ . '/config.php';

// 1. Auto-create gallery_items table
try {
    $conn->exec("CREATE TABLE IF NOT EXISTS gallery_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        gender ENUM('male', 'female', 'unisex') NOT NULL DEFAULT 'male',
        category VARCHAR(50) NOT NULL COMMENT '가발, 투페, 톱퍼 등',
        title VARCHAR(255) NOT NULL,
        description TEXT DEFAULT NULL,
        hair_type VARCHAR(100) DEFAULT NULL,
        age_group VARCHAR(50) DEFAULT NULL,
        before_img VARCHAR(500) NOT NULL,
        after_img1 VARCHAR(500) NOT NULL,
        after_img2 VARCHAR(500) DEFAULT NULL,
        after_img3 VARCHAR(500) DEFAULT NULL,
        after_img4 VARCHAR(500) DEFAULT NULL,
        views INT DEFAULT 0,
        sort_order INT NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Auto seed if empty
    $count = $conn->query("SELECT COUNT(*) FROM gallery_items")->fetchColumn();
    if ($count == 0) {
        $seedStmt = $conn->prepare("INSERT INTO gallery_items 
            (gender, category, title, description, hair_type, age_group, before_img, after_img1, after_img2, after_img3, after_img4, sort_order) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $seedData = [
            [
                'male', '투페', 'M자 및 정수리 탈모 극복 0.03mm 초극박 스킨 투페',
                '이마 헤어라인과 정수리 밀도 저하로 고민하시던 고객님께 초극박 스킨 베이스 투페를 시공하여 10년 이상 젊어진 자연스러운 올백 헤어스타일을 완성했습니다.',
                '100% 인모 / 유럽풍 내추럴 블랙', '40대',
                'images/transformations/man1_before.webp', 'images/transformations/man1_after.webp',
                'images/transformations/man1_after.jpg', null, null, 1
            ],
            [
                'male', '투페', '스위스 레이스 통기성 프리미엄 남성 부분가발',
                '땀이 많고 활동적인 라이프스타일을 가진 고객님을 위한 100% 스위스 레이스 부분가발. 경계선이 전혀 티나지 않는 내추럴 댄디 컷 스타일입니다.',
                '천연 인모 100% / 소프트 볼륨', '30대',
                'images/transformations/man2_before.webp', 'images/transformations/man2_after.webp',
                'images/transformations/man2_after.jpg', null, null, 2
            ],
            [
                'male', '가발', '정수리 광범위 탈모 전두형 모노필라멘트 풀 커버 가발',
                '두피 전체의 자연스러운 밀도 회복과 두상에 맞춘 3D 입체 성형 프레임으로 장시간 착용에도 가볍고 편안한 클래식 포마드 스타일을 연출했습니다.',
                '프리미엄 레미모 / 다크 브라운', '50대',
                'images/transformations/man3_before.webp', 'images/transformations/man3_after.webp',
                'images/transformations/man3_after.jpg', null, null, 3
            ],
            [
                'female', '톱퍼', '정수리 가르마 탈모 보완 100% 실크탑 여성 톱퍼',
                '가르마 라인의 빈모를 완벽히 가려주는 숨쉬는 리얼스칼프 실크탑 톱퍼. 본 머리와 자연스럽게 믹스되어 풍성한 뿌리 볼륨을 되찾아 드렸습니다.',
                '버진 레미모 / 초코 브라운', '40대',
                'images/transformations/woman1_before.webp', 'images/transformations/woman1_after.webp',
                'images/transformations/woman1_after.jpg', null, null, 4
            ],
            [
                'female', '톱퍼', '앞머리 라인 & 크라운 동시 커버 하이브리드 헤어피스',
                '앞이마 축소 및 정수리 풍성함을 동시에 잡는 맞춤형 하이브리드 톱퍼. 클립형으로 3초 만에 간편 탈착이 가능합니다.',
                '프리미엄 실크 인모 / 내추럴 웨이브', '50대',
                'images/transformations/woman2_before.webp', 'images/transformations/woman2_after.webp',
                'images/transformations/woman2_after.jpg', null, null, 5
            ],
            [
                'female', '가발', '항암 및 전두 탈모 케어 메디컬 프리미엄 여성 전두가발',
                '민감해진 두피를 감싸는 오가닉 순면 안감과 인체공학적 탄력 밴드가 결합된 여성 전용 풀 커버리지 가발. 우아한 미디움 C컬 펌 스타일링.',
                '100% 최고급 버진헤어 / 애쉬 모카', '30대',
                'images/transformations/woman3_before.webp', 'images/transformations/woman3_after.webp',
                'images/transformations/woman3_after.jpg', null, null, 6
            ]
        ];

        foreach ($seedData as $row) {
            $seedStmt->execute($row);
        }
    }
} catch (Exception $e) {
    sendJsonResponse(['success' => false, 'error' => 'DB 초기화 실패: ' . $e->getMessage()], 500);
}

$method = $_SERVER['REQUEST_METHOD'];

// GET: 목록 조회
if ($method === 'GET') {
    $action = $_GET['action'] ?? 'list';

    if ($action === 'detail') {
        $id = intval($_GET['id'] ?? 0);
        $stmt = $conn->prepare("SELECT * FROM gallery_items WHERE id = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($item) {
            $conn->prepare("UPDATE gallery_items SET views = views + 1 WHERE id = ?")->execute([$id]);
            sendJsonResponse(['success' => true, 'data' => $item]);
        } else {
            sendJsonResponse(['success' => false, 'message' => '항목을 찾을 수 없습니다.'], 404);
        }
    }

    $gender = trim($_GET['gender'] ?? 'all');
    $category = trim($_GET['category'] ?? 'all');
    $keyword = trim($_GET['keyword'] ?? '');

    $sql = "SELECT * FROM gallery_items WHERE 1=1";
    $params = [];

    if ($gender !== 'all' && in_array($gender, ['male', 'female', 'unisex'])) {
        $sql .= " AND gender = ?";
        $params[] = $gender;
    }

    if ($category !== 'all' && $category !== '') {
        $sql .= " AND category = ?";
        $params[] = $category;
    }

    if ($keyword !== '') {
        $sql .= " AND (title LIKE ? OR description LIKE ? OR category LIKE ? OR hair_type LIKE ?)";
        $search = "%$keyword%";
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
    }

    $sql .= " ORDER BY sort_order ASC, id DESC";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        sendJsonResponse(['success' => true, 'data' => $list, 'count' => count($list)]);
    } catch (Exception $e) {
        sendJsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

// POST: 등록 / 수정 / 삭제
if ($method === 'POST') {
    $action = $_POST['action'] ?? ($_GET['action'] ?? 'create');

    // 삭제 처리
    if ($action === 'delete') {
        $id = intval($_POST['id'] ?? ($_GET['id'] ?? 0));
        if ($id <= 0) {
            sendJsonResponse(['success' => false, 'message' => '잘못된 ID입니다.'], 400);
        }
        $stmt = $conn->prepare("DELETE FROM gallery_items WHERE id = ?");
        $stmt->execute([$id]);
        sendJsonResponse(['success' => true, 'message' => '성공적으로 삭제되었습니다.']);
    }

    // 파일 업로드 디렉토리 확인 및 생성
    $uploadDir = __DIR__ . '/uploads/gallery/';
    if (!is_dir($uploadDir)) {
        @mkdir($uploadDir, 0777, true);
    }

    function processUpload($fileKey, $urlKey, $dir) {
        if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            if (in_array($ext, $allowed)) {
                $filename = 'bf_' . uniqid() . '.' . $ext;
                $dest = $dir . $filename;
                if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $dest)) {
                    return 'uploads/gallery/' . $filename;
                }
            }
        }
        $url = trim($_POST[$urlKey] ?? '');
        return !empty($url) ? $url : null;
    }

    $gender = in_array($_POST['gender'] ?? '', ['male', 'female', 'unisex']) ? $_POST['gender'] : 'male';
    $category = trim($_POST['category'] ?? '투페');
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $hair_type = trim($_POST['hair_type'] ?? '');
    $age_group = trim($_POST['age_group'] ?? '');
    $sort_order = intval($_POST['sort_order'] ?? 0);

    $before_img = processUpload('before_file', 'before_img', $uploadDir);
    $after_img1 = processUpload('after1_file', 'after_img1', $uploadDir);
    $after_img2 = processUpload('after2_file', 'after_img2', $uploadDir);
    $after_img3 = processUpload('after3_file', 'after_img3', $uploadDir);
    $after_img4 = processUpload('after4_file', 'after_img4', $uploadDir);

    if (empty($title)) {
        sendJsonResponse(['success' => false, 'message' => '제목을 입력해주세요.'], 400);
    }
    if (empty($before_img) || empty($after_img1)) {
        sendJsonResponse(['success' => false, 'message' => 'Before 사진과 1개 이상의 After 사진은 필수입니다.'], 400);
    }

    if ($action === 'create') {
        $stmt = $conn->prepare("INSERT INTO gallery_items 
            (gender, category, title, description, hair_type, age_group, before_img, after_img1, after_img2, after_img3, after_img4, sort_order)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$gender, $category, $title, $description, $hair_type, $age_group, $before_img, $after_img1, $after_img2, $after_img3, $after_img4, $sort_order]);
        
        sendJsonResponse(['success' => true, 'message' => '갤러리 항목이 등록되었습니다.', 'id' => $conn->lastInsertId()]);
    } else if ($action === 'update') {
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            sendJsonResponse(['success' => false, 'message' => '유효하지 않은 ID입니다.'], 400);
        }

        $stmt = $conn->prepare("UPDATE gallery_items SET 
            gender = ?, category = ?, title = ?, description = ?, hair_type = ?, age_group = ?,
            before_img = COALESCE(?, before_img),
            after_img1 = COALESCE(?, after_img1),
            after_img2 = ?,
            after_img3 = ?,
            after_img4 = ?,
            sort_order = ?
            WHERE id = ?");
        $stmt->execute([$gender, $category, $title, $description, $hair_type, $age_group, $before_img, $after_img1, $after_img2, $after_img3, $after_img4, $sort_order, $id]);

        sendJsonResponse(['success' => true, 'message' => '성공적으로 수정되었습니다.']);
    }
}

