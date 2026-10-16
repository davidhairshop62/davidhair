<?php
// api_sections.php - Database Table & API for Homepage Sections & Navigation Menu Visibility
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

// Auto-create site_sections table if it does not exist
try {
    $conn->exec("CREATE TABLE IF NOT EXISTS site_sections (
        id INT AUTO_INCREMENT PRIMARY KEY,
        section_key VARCHAR(64) UNIQUE NOT NULL,
        name VARCHAR(100) NOT NULL,
        category VARCHAR(50) NOT NULL,
        description VARCHAR(255) DEFAULT '',
        is_visible TINYINT(1) NOT NULL DEFAULT 1,
        in_menu TINYINT(1) NOT NULL DEFAULT 1,
        menu_title VARCHAR(100) NOT NULL,
        menu_icon VARCHAR(50) DEFAULT '',
        menu_badge VARCHAR(50) DEFAULT '',
        sort_order INT NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Check count and seed if empty
    $countStmt = $conn->query("SELECT COUNT(*) FROM site_sections");
    if ($countStmt->fetchColumn() == 0) {
        $defaultSections = [
            [
                'section_key' => 'hero',
                'name' => 'Hero Section (Video & Main Value)',
                'category' => 'Brand & Trust',
                'description' => 'Top visual hero banner with background video switchers, trust stats, and main CTA buttons.',
                'is_visible' => 1,
                'in_menu' => 0,
                'menu_title' => 'Home',
                'menu_icon' => 'fa-house',
                'menu_badge' => '',
                'sort_order' => 1
            ],
            [
                'section_key' => 'hair-diagnosis-hub',
                'name' => 'Do I Need a Wig? (Self-Diagnosis Hub)',
                'category' => 'Interactive & AI',
                'description' => 'Interactive hairloss phase self-diagnosis, style recommendations, and wig specs calculator.',
                'is_visible' => 1,
                'in_menu' => 1,
                'menu_title' => 'Wig Quiz',
                'menu_icon' => 'fa-clipboard-question',
                'menu_badge' => 'Self-Check',
                'sort_order' => 2
            ],
            [
                'section_key' => 'diagnosis-option1',
                'name' => 'Diagnosis Quiz: Option 1 (4-Step Wizard)',
                'category' => 'Interactive & AI',
                'description' => '4-step wizard: Gender -> Hairloss Phase -> Hairstyle Selection -> Full Wig Specs recommendation.',
                'is_visible' => 1,
                'in_menu' => 0,
                'menu_title' => 'Quiz Wizard',
                'menu_icon' => 'fa-wand-magic-sparkles',
                'menu_badge' => '',
                'sort_order' => 3
            ],
            [
                'section_key' => 'diagnosis-option2',
                'name' => 'Diagnosis Quiz: Option 2 (Visual Matrix)',
                'category' => 'Interactive & AI',
                'description' => 'Visual Norwood / Ludwig scale diagram & exposed hairline comparison matrix.',
                'is_visible' => 1,
                'in_menu' => 0,
                'menu_title' => 'Visual Matrix',
                'menu_icon' => 'fa-table-cells',
                'menu_badge' => '',
                'sort_order' => 4
            ],
            [
                'section_key' => 'lifestyle-performance',
                'name' => 'Active Lifestyle & Climate Performance',
                'category' => 'Brand & Trust',
                'description' => 'Australian climate durability: 100% waterproof hold, gym, swimming & UV protection highlights.',
                'is_visible' => 1,
                'in_menu' => 0,
                'menu_title' => 'Active Life',
                'menu_icon' => 'fa-person-swimming',
                'menu_badge' => '',
                'sort_order' => 5
            ],
            [
                'section_key' => 'builder',
                'name' => 'Custom Toupee Order Builder',
                'category' => 'Products & Ordering',
                'description' => 'Interactive 6-step custom order configuration wizard with dynamic pricing.',
                'is_visible' => 1,
                'in_menu' => 1,
                'menu_title' => 'Order',
                'menu_icon' => 'fa-sliders',
                'menu_badge' => 'Bespoke',
                'sort_order' => 6
            ],
            [
                'section_key' => 'stock',
                'name' => 'In-Stock Ready-to-Ship Inventory',
                'category' => 'Products & Ordering',
                'description' => 'Real-time database inventory of pre-made toupees and toppers ready for immediate dispatch.',
                'is_visible' => 1,
                'in_menu' => 1,
                'menu_title' => 'Stock',
                'menu_icon' => 'fa-boxes-stacked',
                'menu_badge' => 'Ready',
                'sort_order' => 7
            ],
            [
                'section_key' => 'salon',
                'name' => 'Melbourne Flagship Salon Services',
                'category' => 'Salon & Services',
                'description' => 'Springvale salon services: cut-in, fade blend, 4-6 week rebonding maintenance.',
                'is_visible' => 1,
                'in_menu' => 1,
                'menu_title' => 'Salon',
                'menu_icon' => 'fa-scissors',
                'menu_badge' => '',
                'sort_order' => 8
            ],
            [
                'section_key' => 'transformations',
                'name' => 'Before & After Transformations',
                'category' => 'Salon & Services',
                'description' => 'Real client transformation gallery, before and after slider, 10-year youth look.',
                'is_visible' => 1,
                'in_menu' => 1,
                'menu_title' => 'Before & After',
                'menu_icon' => 'fa-arrows-split-up-and-left',
                'menu_badge' => '10-Yr Youth',
                'sort_order' => 9
            ],
            [
                'section_key' => 'ai-simulator',
                'name' => 'AI Virtual Hairstyle Try-On Simulator',
                'category' => 'Interactive & AI',
                'description' => 'Live camera and photo upload virtual hair fitting simulator with touch drag/scale.',
                'is_visible' => 1,
                'in_menu' => 1,
                'menu_title' => 'AI Try-On',
                'menu_icon' => 'fa-wand-magic-sparkles',
                'menu_badge' => 'AI Tech',
                'sort_order' => 10
            ],
            [
                'section_key' => 'products-range',
                'name' => 'Product Lineup & Academy Course',
                'category' => 'Products & Ordering',
                'description' => 'Complete range of 11 hair solutions plus professional wig maker academy.',
                'is_visible' => 1,
                'in_menu' => 1,
                'menu_title' => 'All Products',
                'menu_icon' => 'fa-layer-group',
                'menu_badge' => '',
                'sort_order' => 11
            ],
            [
                'section_key' => 'factory',
                'name' => 'Factory Direct Advantage',
                'category' => 'Brand & Trust',
                'description' => 'Qingdao manufacturing craftsmanship, 100% Remy human hair, zero middleman markup.',
                'is_visible' => 1,
                'in_menu' => 1,
                'menu_title' => 'Factory',
                'menu_icon' => 'fa-industry',
                'menu_badge' => '',
                'sort_order' => 12
            ],
            [
                'section_key' => 'b2b',
                'name' => 'B2B Wholesale & Partner Program',
                'category' => 'Brand & Trust',
                'description' => 'Wholesale salon partnerships, OEM manufacturing, salon distribution inquiries.',
                'is_visible' => 1,
                'in_menu' => 1,
                'menu_title' => 'B2B',
                'menu_icon' => 'fa-handshake',
                'menu_badge' => '',
                'sort_order' => 13
            ]
        ];

        $ins = $conn->prepare("INSERT INTO site_sections (section_key, name, category, description, is_visible, in_menu, menu_title, menu_icon, menu_badge, sort_order)
            VALUES (:section_key, :name, :category, :description, :is_visible, :in_menu, :menu_title, :menu_icon, :menu_badge, :sort_order)");

        foreach ($defaultSections as $sec) {
            $ins->execute($sec);
        }
    }
} catch (Exception $e) {
    // If creation fails, proceed to try queries
}

$action = $_REQUEST['action'] ?? 'get_public_sections';

// Raw JSON input helper
$rawInput = file_get_contents('php://input');
$jsonBody = json_decode($rawInput, true) ?: [];

try {
    // 1. PUBLIC GET SECTIONS (Used by index.html to show/hide sections & navigation items)
    if ($action === 'get_public_sections' || $action === 'list') {
        $stmt = $conn->query("SELECT id, section_key, name, category, description, is_visible, in_menu, menu_title, menu_icon, menu_badge, sort_order FROM site_sections ORDER BY sort_order ASC, id ASC");
        $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $visibilityMap = [];
        $menuMap = [];
        $orderMap = [];

        foreach ($sections as &$sec) {
            $sec['is_visible'] = (int)$sec['is_visible'];
            $sec['in_menu'] = (int)$sec['in_menu'];
            $sec['sort_order'] = (int)$sec['sort_order'];
            $visibilityMap[$sec['section_key']] = ($sec['is_visible'] === 1);
            $menuMap[$sec['section_key']] = ($sec['in_menu'] === 1);
            $orderMap[$sec['section_key']] = (int)$sec['sort_order'];
        }
        unset($sec);

        sendJsonResponse([
            'status' => 'success',
            'sections' => $sections,
            'visibility' => $visibilityMap,
            'in_menu' => $menuMap,
            'orders' => $orderMap,
            'timestamp' => time()
        ]);
    }

    // 1.1 UPDATE ORDER ACTION
    if ($action === 'update_order') {
        $key = trim($_POST['section_key'] ?? $jsonBody['section_key'] ?? '');
        $id = (int)($_POST['id'] ?? $jsonBody['id'] ?? 0);
        $sortOrder = (int)($_POST['sort_order'] ?? $jsonBody['sort_order'] ?? 0);

        if (!$key && !$id) {
            sendJsonResponse(['status' => 'error', 'message' => 'Section key or ID is required.']);
        }

        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE site_sections SET sort_order = :ord, updated_at = NOW() WHERE id = :id");
            $stmt->execute([':ord' => $sortOrder, ':id' => $id]);
        } else {
            $stmt = $conn->prepare("UPDATE site_sections SET sort_order = :ord, updated_at = NOW() WHERE section_key = :key");
            $stmt->execute([':ord' => $sortOrder, ':key' => $key]);
        }

        sendJsonResponse(['status' => 'success', 'message' => 'Section display order updated successfully.', 'sort_order' => $sortOrder]);
    }

    // 2. TOGGLE VISIBILITY (Show / Hide on Homepage)
    if ($action === 'toggle_visibility') {
        $key = trim($_POST['section_key'] ?? $jsonBody['section_key'] ?? '');
        $id = (int)($_POST['id'] ?? $jsonBody['id'] ?? 0);
        $val = isset($_POST['is_visible']) ? (int)$_POST['is_visible'] : (isset($jsonBody['is_visible']) ? (int)$jsonBody['is_visible'] : null);

        if (!$key && !$id) {
            sendJsonResponse(['status' => 'error', 'message' => 'Section key or ID is required.']);
        }

        if ($val === null) {
            // Toggle current value
            if ($id > 0) {
                $cur = $conn->prepare("SELECT is_visible FROM site_sections WHERE id = :id");
                $cur->execute([':id' => $id]);
            } else {
                $cur = $conn->prepare("SELECT is_visible FROM site_sections WHERE section_key = :key");
                $cur->execute([':key' => $key]);
            }
            $currentVal = (int)$cur->fetchColumn();
            $val = $currentVal ? 0 : 1;
        }

        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE site_sections SET is_visible = :val, updated_at = NOW() WHERE id = :id");
            $stmt->execute([':val' => $val, ':id' => $id]);
        } else {
            $stmt = $conn->prepare("UPDATE site_sections SET is_visible = :val, updated_at = NOW() WHERE section_key = :key");
            $stmt->execute([':val' => $val, ':key' => $key]);
        }

        sendJsonResponse([
            'status' => 'success',
            'message' => 'Section visibility updated successfully.',
            'section_key' => $key,
            'is_visible' => $val
        ]);
    }

    // 3. TOGGLE IN_MENU (Show / Hide in Navigation Bar & Mobile Menu)
    if ($action === 'toggle_menu') {
        $key = trim($_POST['section_key'] ?? $jsonBody['section_key'] ?? '');
        $id = (int)($_POST['id'] ?? $jsonBody['id'] ?? 0);
        $val = isset($_POST['in_menu']) ? (int)$_POST['in_menu'] : (isset($jsonBody['in_menu']) ? (int)$jsonBody['in_menu'] : null);

        if (!$key && !$id) {
            sendJsonResponse(['status' => 'error', 'message' => 'Section key or ID is required.']);
        }

        if ($val === null) {
            if ($id > 0) {
                $cur = $conn->prepare("SELECT in_menu FROM site_sections WHERE id = :id");
                $cur->execute([':id' => $id]);
            } else {
                $cur = $conn->prepare("SELECT in_menu FROM site_sections WHERE section_key = :key");
                $cur->execute([':key' => $key]);
            }
            $currentVal = (int)$cur->fetchColumn();
            $val = $currentVal ? 0 : 1;
        }

        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE site_sections SET in_menu = :val, updated_at = NOW() WHERE id = :id");
            $stmt->execute([':val' => $val, ':id' => $id]);
        } else {
            $stmt = $conn->prepare("UPDATE site_sections SET in_menu = :val, updated_at = NOW() WHERE section_key = :key");
            $stmt->execute([':val' => $val, ':key' => $key]);
        }

        sendJsonResponse([
            'status' => 'success',
            'message' => 'Menu visibility updated successfully.',
            'section_key' => $key,
            'in_menu' => $val
        ]);
    }

    // 4. UPDATE SINGLE SECTION DETAILS (Title, badge, sort_order, etc.)
    if ($action === 'update_section') {
        $id = (int)($_POST['id'] ?? $jsonBody['id'] ?? 0);
        if (!$id) {
            sendJsonResponse(['status' => 'error', 'message' => 'Section ID is required.']);
        }

        $name = trim($_POST['name'] ?? $jsonBody['name'] ?? '');
        $menuTitle = trim($_POST['menu_title'] ?? $jsonBody['menu_title'] ?? '');
        $menuIcon = trim($_POST['menu_icon'] ?? $jsonBody['menu_icon'] ?? '');
        $menuBadge = trim($_POST['menu_badge'] ?? $jsonBody['menu_badge'] ?? '');
        $isVisible = (int)($_POST['is_visible'] ?? $jsonBody['is_visible'] ?? 1);
        $inMenu = (int)($_POST['in_menu'] ?? $jsonBody['in_menu'] ?? 1);
        $sortOrder = (int)($_POST['sort_order'] ?? $jsonBody['sort_order'] ?? 0);

        $stmt = $conn->prepare("UPDATE site_sections SET 
            name = COALESCE(NULLIF(:name, ''), name),
            menu_title = COALESCE(NULLIF(:menu_title, ''), menu_title),
            menu_icon = :menu_icon,
            menu_badge = :menu_badge,
            is_visible = :is_visible,
            in_menu = :in_menu,
            sort_order = :sort_order,
            updated_at = NOW()
            WHERE id = :id");

        $stmt->execute([
            ':name' => $name,
            ':menu_title' => $menuTitle,
            ':menu_icon' => $menuIcon,
            ':menu_badge' => $menuBadge,
            ':is_visible' => $isVisible,
            ':in_menu' => $inMenu,
            ':sort_order' => $sortOrder,
            ':id' => $id
        ]);

        sendJsonResponse(['status' => 'success', 'message' => 'Section updated successfully.']);
    }

    // 5. BULK UPDATE (Save all sections visibility & menu configuration at once)
    if ($action === 'bulk_update') {
        $sections = $jsonBody['sections'] ?? (isset($_POST['sections']) ? json_decode($_POST['sections'], true) : []);

        if (!is_array($sections) || empty($sections)) {
            sendJsonResponse(['status' => 'error', 'message' => 'No sections data provided for bulk update.']);
        }

        $conn->beginTransaction();
        $stmt = $conn->prepare("UPDATE site_sections SET 
            is_visible = :is_visible,
            in_menu = :in_menu,
            menu_title = :menu_title,
            sort_order = :sort_order,
            updated_at = NOW()
            WHERE id = :id");

        foreach ($sections as $sec) {
            $stmt->execute([
                ':is_visible' => (int)($sec['is_visible'] ?? 1),
                ':in_menu' => (int)($sec['in_menu'] ?? 0),
                ':menu_title' => $sec['menu_title'] ?? '',
                ':sort_order' => (int)($sec['sort_order'] ?? 0),
                ':id' => (int)$sec['id']
            ]);
        }
        $conn->commit();

        sendJsonResponse(['status' => 'success', 'message' => 'All section configurations saved successfully.']);
    }

    // 6. RESET ALL TO VISIBLE (Default state)
    if ($action === 'reset_defaults') {
        $conn->exec("UPDATE site_sections SET is_visible = 1, updated_at = NOW()");
        sendJsonResponse(['status' => 'success', 'message' => 'All sections have been enabled and set to visible.']);
    }

    sendJsonResponse(['status' => 'error', 'message' => 'Invalid action: ' . $action], 400);

} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    sendJsonResponse(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()], 500);
}
