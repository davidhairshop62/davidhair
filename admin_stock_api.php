<?php
// admin_stock_api.php - Authentication & Stock Management & AI Hairstyles CRUD API
error_reporting(0);
ini_set('display_errors', '0');
ob_start();
session_start();

function sendJsonResponse($data, $statusCode = 200) {
    if (ob_get_length()) ob_clean();
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

require_once __DIR__ . '/config.php';

$uploadDir = __DIR__ . '/uploads/';
if (!file_exists($uploadDir)) {
    @mkdir($uploadDir, 0777, true);
}

// Auto-create ai_hairstyles table
try {
    $conn->exec("CREATE TABLE IF NOT EXISTS ai_hairstyles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        tag VARCHAR(150) DEFAULT '',
        gender VARCHAR(50) DEFAULT 'Men',
        overlay LONGTEXT NOT NULL,
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Seed initial hairstyles if table is empty
    $checkStmt = $conn->query("SELECT COUNT(*) FROM ai_hairstyles");
    if ($checkStmt->fetchColumn() == 0) {
        $initialStyles = [
            ['name' => 'Crew cut', 'tag' => 'Clean Short Fade', 'gender' => 'Men', 'overlay' => 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 300" fill="none"><path d="M120,180 C110,110 160,35 250,30 C340,35 390,110 380,180 C360,140 330,85 250,80 C170,85 140,140 120,180 Z" fill="%231a1a1a" opacity="0.95"/><path d="M140,145 C170,75 330,75 360,145 C330,95 170,95 140,145 Z" fill="%232b2b2b" opacity="0.88"/><path d="M160,110 C200,55 300,55 340,110 C300,70 200,70 160,110 Z" fill="%230f0f0f" opacity="0.95"/></svg>', 'sort_order' => 1],
            ['name' => 'Gentleman', 'tag' => 'Executive Side Part', 'gender' => 'Men', 'overlay' => 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 320" fill="none"><path d="M100,200 C90,120 150,25 260,20 C360,20 410,100 400,200 C380,140 350,60 270,60 C200,60 170,120 100,200 Z" fill="%23151515" opacity="0.95"/><path d="M110,180 C140,80 240,40 340,70 C380,120 370,180 340,130 C270,75 200,80 110,180 Z" fill="%232a2a2a" opacity="0.88"/><path d="M140,130 C190,50 300,50 360,110 C310,65 220,65 140,130 Z" fill="%230a0a0a" opacity="0.95"/></svg>', 'sort_order' => 2],
            ['name' => 'Textured Crop', 'tag' => 'Modern Layered Fringe', 'gender' => 'Men', 'overlay' => 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 320" fill="none"><path d="M110,210 C100,120 160,30 250,25 C340,30 400,120 390,210 C370,170 360,120 330,125 C300,130 280,115 250,120 C220,125 190,115 170,125 C140,130 130,170 110,210 Z" fill="%231a1a1a" opacity="0.95"/><path d="M130,160 C160,70 340,70 370,160 C340,105 300,110 250,105 C200,110 160,105 130,160 Z" fill="%23303030" opacity="0.85"/><path d="M150,120 C190,45 310,45 350,120 C310,65 260,65 150,120 Z" fill="%230d0d0d" opacity="0.95"/></svg>', 'sort_order' => 3],
            ['name' => 'Slick Back', 'tag' => 'Back Swept Undercut', 'gender' => 'Men', 'overlay' => 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 320" fill="none"><path d="M105,210 C95,110 150,20 250,15 C350,20 405,110 395,210 C380,150 340,50 250,55 C160,50 120,150 105,210 Z" fill="%23181818" opacity="0.95"/><path d="M125,170 C160,60 340,60 375,170 C340,90 250,85 125,170 Z" fill="%232c2c2c" opacity="0.88"/><path d="M150,120 C190,40 310,40 350,120 C310,55 190,55 150,120 Z" fill="%230a0a0a" opacity="0.95"/></svg>', 'sort_order' => 4],
            ['name' => 'Silk Topper', 'tag' => "Women's Crown Volume", 'gender' => 'Women', 'overlay' => 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 360" fill="none"><path d="M90,260 C80,130 150,15 250,10 C350,15 420,130 410,260 C390,180 370,80 250,75 C130,80 110,180 90,260 Z" fill="%23181818" opacity="0.95"/><path d="M110,210 C140,80 360,80 390,210 C360,110 250,105 110,210 Z" fill="%232e2e2e" opacity="0.88"/><path d="M140,140 C180,45 320,45 360,140 C320,65 180,65 140,140 Z" fill="%230c0c0c" opacity="0.95"/></svg>', 'sort_order' => 5]
        ];
        $ins = $conn->prepare("INSERT INTO ai_hairstyles (name, tag, gender, overlay, sort_order) VALUES (:name, :tag, :gender, :overlay, :sort_order)");
        foreach ($initialStyles as $st) {
            $ins->execute($st);
        }
    }
} catch (Exception $e) {
    // Table exists or error ignored
}

// Auto-check stock_items table columns for description, badge, is_visible, and gallery_images
try {
    $cols = $conn->query("SHOW COLUMNS FROM stock_items")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('description', $cols)) {
        $conn->exec("ALTER TABLE stock_items ADD COLUMN description TEXT NULL");
    }
    if (!in_array('badge', $cols)) {
        $conn->exec("ALTER TABLE stock_items ADD COLUMN badge VARCHAR(50) DEFAULT ''");
    }
    if (!in_array('is_visible', $cols)) {
        $conn->exec("ALTER TABLE stock_items ADD COLUMN is_visible TINYINT(1) NOT NULL DEFAULT 1");
    }
    if (!in_array('gallery_images', $cols)) {
        $conn->exec("ALTER TABLE stock_items ADD COLUMN gallery_images TEXT NULL");
    }
} catch (Exception $e) {
    // Columns already exist or error ignored
}

$action = $_REQUEST['action'] ?? 'list';

try {
    // 1. LOGIN ACTION
    if ($action === 'login') {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            sendJsonResponse(['status' => 'error', 'message' => 'Please enter both Admin ID and Password.']);
        }

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_user'] = $user['email'];
            $_SESSION['admin_role'] = $user['role'];
            sendJsonResponse([
                'status' => 'success',
                'message' => 'Login successful.',
                'user' => $user['email']
            ]);
        } else {
            sendJsonResponse(['status' => 'error', 'message' => 'Invalid Admin ID or Password.']);
        }
    }

    // 2. LOGOUT ACTION
    if ($action === 'logout') {
        unset($_SESSION['admin_user']);
        unset($_SESSION['admin_role']);
        session_destroy();
        sendJsonResponse(['status' => 'success', 'message' => 'Logged out successfully.']);
    }

    // 3. CHECK AUTH ACTION
    if ($action === 'check_auth') {
        if (!empty($_SESSION['admin_user'])) {
            sendJsonResponse(['status' => 'authenticated', 'user' => $_SESSION['admin_user']]);
        } else {
            sendJsonResponse(['status' => 'unauthenticated']);
        }
    }

    // 4. PUBLIC LIST ACTION (STOCK ITEMS)
    if ($action === 'list') {
        $stmt = $conn->query("SELECT * FROM stock_items ORDER BY id DESC");
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($items as &$item) {
            if (!empty($item['gallery_images'])) {
                $decoded = json_decode($item['gallery_images'], true);
                $item['gallery_images'] = is_array($decoded) ? $decoded : (!empty($item['image']) ? [$item['image']] : []);
            } else {
                $item['gallery_images'] = !empty($item['image']) ? [$item['image']] : [];
            }
        }
        unset($item);
        sendJsonResponse(['status' => 'success', 'data' => $items]);
    }

    // 5. PUBLIC LIST AI HAIRSTYLES ACTION
    if ($action === 'list_ai_styles') {
        $stmt = $conn->query("SELECT * FROM ai_hairstyles ORDER BY sort_order ASC, id ASC");
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($items as &$it) {
            $g = strtolower(trim($it['gender'] ?? ''));
            if ($g === 'women' || $g === 'woman' || str_contains($g, 'wom')) {
                $it['category'] = 'Woman';
                $it['gender'] = 'Women';
            } else {
                $it['category'] = 'Man';
                $it['gender'] = 'Men';
            }
        }
        unset($it);
        sendJsonResponse(['status' => 'success', 'data' => $items]);
    }

    // AUTHENTICATION GUARD FOR WRITE OPERATIONS (save, delete)
    if (empty($_SESSION['admin_user'])) {
        sendJsonResponse(['status' => 'error', 'message' => 'Admin authentication required. Please login.'], 401);
    }

    // 6. TOGGLE STOCK ITEM VISIBILITY (SHOW / HIDE ON MAIN PAGE)
    if ($action === 'toggle_visibility') {
        $id = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($id <= 0) {
            sendJsonResponse(['status' => 'error', 'message' => 'Invalid product ID.'], 400);
        }

        if (isset($_REQUEST['is_visible'])) {
            $newVal = intval($_REQUEST['is_visible']) ? 1 : 0;
            $stmt = $conn->prepare("UPDATE stock_items SET is_visible = :vis WHERE id = :id");
            $stmt->execute([':vis' => $newVal, ':id' => $id]);
        } else {
            $stmt = $conn->prepare("UPDATE stock_items SET is_visible = IF(is_visible = 1, 0, 1) WHERE id = :id");
            $stmt->execute([':id' => $id]);
        }

        $stmt = $conn->prepare("SELECT is_visible FROM stock_items WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $currentVis = (int)$stmt->fetchColumn();

        sendJsonResponse([
            'status' => 'success',
            'message' => $currentVis ? 'Product is now visible on main page.' : 'Product is now hidden from main page.',
            'id' => $id,
            'is_visible' => $currentVis
        ]);
    }

    // 6.5. UPLOAD STOCK IMAGES (MULTIPLE FILES)
    if ($action === 'upload_stock_images') {
        $uploadedUrls = [];
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (!empty($_FILES['images'])) {
            $files = $_FILES['images'];
            $names = is_array($files['name']) ? $files['name'] : [$files['name']];
            $tmps = is_array($files['tmp_name']) ? $files['tmp_name'] : [$files['tmp_name']];
            $errors = is_array($files['error']) ? $files['error'] : [$files['error']];

            for ($i = 0; $i < count($names); $i++) {
                if ($errors[$i] === UPLOAD_ERR_OK && is_uploaded_file($tmps[$i])) {
                    $ext = strtolower(pathinfo($names[$i], PATHINFO_EXTENSION));
                    if (in_array($ext, $allowedExts)) {
                        $newFileName = 'stock_' . time() . '_' . rand(1000, 9999) . '_' . $i . '.' . $ext;
                        $targetPath = $uploadDir . $newFileName;
                        if (move_uploaded_file($tmps[$i], $targetPath)) {
                            $uploadedUrls[] = '/uploads/' . $newFileName;
                        }
                    }
                }
            }
        }

        if (!empty($uploadedUrls)) {
            sendJsonResponse([
                'status' => 'success',
                'message' => count($uploadedUrls) . ' image(s) uploaded successfully.',
                'urls' => $uploadedUrls
            ]);
        } else {
            sendJsonResponse(['status' => 'error', 'message' => 'No valid images were uploaded.'], 400);
        }
    }

    // 7. SAVE STOCK ITEM
    if ($action === 'save') {
        $id = !empty($_POST['id']) ? intval($_POST['id']) : null;
        $sku = trim($_POST['sku'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? "Men's Toupee");
        $type = trim($_POST['type'] ?? "Skin Systems");
        $base = trim($_POST['base'] ?? '');
        $size = trim($_POST['size'] ?? '8x10"');
        $density = trim($_POST['density'] ?? '90%');
        $color = trim($_POST['color'] ?? '#1B Off Black');
        $price = floatval($_POST['price'] ?? 0);
        $stockQty = intval($_POST['stockQty'] ?? 1);
        $imageUrl = trim($_POST['image_url'] ?? '');
        $rawGallery = $_POST['gallery_images'] ?? '';

        $galleryList = [];
        if (!empty($rawGallery)) {
            if (is_array($rawGallery)) {
                $galleryList = $rawGallery;
            } else {
                $decoded = json_decode($rawGallery, true);
                if (is_array($decoded)) {
                    $galleryList = $decoded;
                }
            }
        }

        // Handle Image File Upload (single legacy)
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $_FILES['image_file']['tmp_name'];
            $fileName = $_FILES['image_file']['name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

            if (in_array($fileExt, $allowedExts)) {
                $newFileName = 'stock_' . time() . '_' . rand(1000, 9999) . '.' . $fileExt;
                $targetPath = $uploadDir . $newFileName;
                if (move_uploaded_file($fileTmp, $targetPath)) {
                    $newUrl = '/uploads/' . $newFileName;
                    $imageUrl = $newUrl;
                    if (!in_array($newUrl, $galleryList)) {
                        array_unshift($galleryList, $newUrl);
                    }
                }
            }
        }

        // Handle multiple gallery files upload during save if attached
        if (!empty($_FILES['gallery_files'])) {
            $files = $_FILES['gallery_files'];
            $names = is_array($files['name']) ? $files['name'] : [$files['name']];
            $tmps = is_array($files['tmp_name']) ? $files['tmp_name'] : [$files['tmp_name']];
            $errors = is_array($files['error']) ? $files['error'] : [$files['error']];
            for ($i = 0; $i < count($names); $i++) {
                if ($errors[$i] === UPLOAD_ERR_OK && is_uploaded_file($tmps[$i])) {
                    $ext = strtolower(pathinfo($names[$i], PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                        $newFileName = 'stock_' . time() . '_' . rand(1000, 9999) . '_' . $i . '.' . $ext;
                        if (move_uploaded_file($tmps[$i], $uploadDir . $newFileName)) {
                            $galleryList[] = '/uploads/' . $newFileName;
                        }
                    }
                }
            }
        }

        // Sanitize gallery list (remove empty/duplicates)
        $cleanGallery = [];
        foreach ($galleryList as $g) {
            $g = trim($g);
            if (!empty($g) && !in_array($g, $cleanGallery)) {
                $cleanGallery[] = $g;
            }
        }

        // Ensure primary image exists and is in gallery
        if (!empty($imageUrl)) {
            if (!in_array($imageUrl, $cleanGallery)) {
                array_unshift($cleanGallery, $imageUrl);
            }
        } elseif (!empty($cleanGallery)) {
            $imageUrl = $cleanGallery[0];
        }

        $galleryJson = !empty($cleanGallery) ? json_encode($cleanGallery, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : null;

        $description = trim($_POST['description'] ?? '');
        $badge = trim($_POST['badge'] ?? '');
        $is_visible = isset($_POST['is_visible']) ? (intval($_POST['is_visible']) ? 1 : 0) : 1;

        if (empty($sku) || empty($name)) {
            sendJsonResponse(['status' => 'error', 'message' => 'SKU and Name are required fields.']);
        }

        if ($id) {
            $sql = "UPDATE stock_items SET sku = :sku, name = :name, category = :category, type = :type, base = :base, size = :size, density = :density, color = :color, price = :price, stockQty = :stockQty, description = :description, badge = :badge, is_visible = :is_visible, gallery_images = :gallery_images";
            if (!empty($imageUrl)) {
                $sql .= ", image = :image";
            }
            $sql .= " WHERE id = :id";

            $stmt = $conn->prepare($sql);
            $params = [
                ':sku' => $sku, ':name' => $name, ':category' => $category, ':type' => $type,
                ':base' => $base, ':size' => $size, ':density' => $density, ':color' => $color,
                ':price' => $price, ':stockQty' => $stockQty, ':description' => $description, ':badge' => $badge,
                ':is_visible' => $is_visible,
                ':gallery_images' => $galleryJson,
                ':id' => $id
            ];
            if (!empty($imageUrl)) {
                $params[':image'] = $imageUrl;
            }
            $stmt->execute($params);
            sendJsonResponse(['status' => 'success', 'message' => 'Stock item updated successfully.']);
        } else {
            if (empty($imageUrl)) {
                $imageUrl = 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=600&q=80';
            }
            if (empty($galleryJson)) {
                $galleryJson = json_encode([$imageUrl], JSON_UNESCAPED_SLASHES);
            }
            $stmt = $conn->prepare("INSERT INTO stock_items (sku, name, category, type, base, size, density, color, price, stockQty, image, description, badge, is_visible, gallery_images) VALUES (:sku, :name, :category, :type, :base, :size, :density, :color, :price, :stockQty, :image, :description, :badge, :is_visible, :gallery_images)");
            $stmt->execute([
                ':sku' => $sku, ':name' => $name, ':category' => $category, ':type' => $type,
                ':base' => $base, ':size' => $size, ':density' => $density, ':color' => $color,
                ':price' => $price, ':stockQty' => $stockQty, ':image' => $imageUrl,
                ':description' => $description, ':badge' => $badge,
                ':is_visible' => $is_visible,
                ':gallery_images' => $galleryJson
            ]);
            sendJsonResponse(['status' => 'success', 'message' => 'New stock item created successfully.']);
        }
    }

    // 7. DELETE STOCK ITEM
    if ($action === 'delete') {
        $id = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM stock_items WHERE id = :id");
            $stmt->execute([':id' => $id]);
            sendJsonResponse(['status' => 'success', 'message' => 'Stock item deleted successfully.']);
        } else {
            sendJsonResponse(['status' => 'error', 'message' => 'Invalid ID.']);
        }
    }

    // 8. SAVE AI SIMULATOR HAIRSTYLE
    if ($action === 'save_ai_style') {
        $id = !empty($_POST['id']) ? intval($_POST['id']) : null;
        $name = trim($_POST['name'] ?? '');
        $tag = trim($_POST['tag'] ?? '');
        $gender = trim($_POST['gender'] ?? 'Men');
        $overlayUrl = trim($_POST['overlay_url'] ?? '');

        // Check for File Upload
        if (isset($_FILES['overlay_file']) && $_FILES['overlay_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['overlay_file']['error'] !== UPLOAD_ERR_OK) {
                $errCodes = [
                    1 => 'File size exceeds PHP upload_max_filesize limit.',
                    2 => 'File size exceeds MAX_FILE_SIZE directive.',
                    3 => 'File was only partially uploaded.',
                    6 => 'Missing temporary folder on server.',
                    7 => 'Failed to write uploaded file to disk.',
                    8 => 'PHP extension stopped the file upload.'
                ];
                $errDetail = $errCodes[$_FILES['overlay_file']['error']] ?? ('Code ' . $_FILES['overlay_file']['error']);
                sendJsonResponse(['status' => 'error', 'message' => 'Upload Error: ' . $errDetail]);
            }

            $fileTmp = $_FILES['overlay_file']['tmp_name'];
            $fileName = $_FILES['overlay_file']['name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'svg'];

            if (!in_array($fileExt, $allowedExts)) {
                sendJsonResponse(['status' => 'error', 'message' => 'Invalid file extension (.' . $fileExt . '). Allowed: JPG, PNG, WEBP, SVG.']);
            }

            $newFileName = 'aistyle_' . time() . '_' . rand(1000, 9999) . '.' . $fileExt;
            $targetPath = $uploadDir . $newFileName;
            
            if (move_uploaded_file($fileTmp, $targetPath)) {
                $overlayUrl = '/uploads/' . $newFileName;
            } else {
                sendJsonResponse(['status' => 'error', 'message' => 'Failed to save file to /uploads/ folder. Check folder permissions.']);
            }
        }

        if (empty($name)) {
            sendJsonResponse(['status' => 'error', 'message' => 'Hairstyle Name is required.']);
        }

        if ($id) {
            $sql = "UPDATE ai_hairstyles SET name = :name, tag = :tag, gender = :gender";
            $params = [':name' => $name, ':tag' => $tag, ':gender' => $gender];
            if (!empty($overlayUrl)) {
                $sql .= ", overlay = :overlay";
                $params[':overlay'] = $overlayUrl;
            }
            $sql .= " WHERE id = :id";
            $params[':id'] = $id;

            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            sendJsonResponse(['status' => 'success', 'message' => 'AI Hairstyle updated successfully.', 'overlay' => $overlayUrl]);
        } else {
            if (empty($overlayUrl)) {
                sendJsonResponse(['status' => 'error', 'message' => 'Transparent PNG/SVG overlay file or URL is required.']);
            }
            $stmt = $conn->prepare("INSERT INTO ai_hairstyles (name, tag, gender, overlay) VALUES (:name, :tag, :gender, :overlay)");
            $stmt->execute([':name' => $name, ':tag' => $tag, ':gender' => $gender, ':overlay' => $overlayUrl]);
            sendJsonResponse(['status' => 'success', 'message' => 'New AI Hairstyle registered successfully.', 'overlay' => $overlayUrl]);
        }
    }

    // 9. DELETE AI SIMULATOR HAIRSTYLE
    if ($action === 'delete_ai_style') {
        $id = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM ai_hairstyles WHERE id = :id");
            $stmt->execute([':id' => $id]);
            sendJsonResponse(['status' => 'success', 'message' => 'AI Hairstyle deleted successfully.']);
        } else {
            sendJsonResponse(['status' => 'error', 'message' => 'Invalid ID.']);
        }
    }

} catch (PDOException $e) {
    sendJsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
}
?>
