<?php
// api_get_stock.php - Fetch In-Stock Precision Pieces from MySQL Database
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

require_once __DIR__ . '/config.php';

try {
    // Ensure is_visible column exists
    try {
        $cols = $conn->query("SHOW COLUMNS FROM stock_items")->fetchAll(PDO::FETCH_COLUMN);
        if (!in_array('is_visible', $cols)) {
            $conn->exec("ALTER TABLE stock_items ADD COLUMN is_visible TINYINT(1) NOT NULL DEFAULT 1");
        }
    } catch (Exception $e) {}

    // 필터링 파라미터 수신 (카테고리 또는 타입 조건)
    $category = isset($_GET['category']) ? trim($_GET['category']) : 'All';
    $sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'default';

    $whereConditions = ["is_visible = 1"];
    $params = [];

    // 조건별 SQL 쿼리 나열
    if ($category !== 'All' && !empty($category)) {
        if ($category === "Men's Toupee" || $category === "Men's Toupees") {
            $whereConditions[] = "(category = 'Men\'s Toupee' OR category = 'Men\'s Toupees')";
        } elseif (strcasecmp($category, "Women's topper") === 0 || strcasecmp($category, "Women's Toppers") === 0) {
            $whereConditions[] = "(category = 'Women\'s topper' OR category = 'Women\'s Topper' OR category = 'Women\'s Toppers')";
        } elseif (strcasecmp($category, "Human Hair Wig") === 0) {
            $whereConditions[] = "category = 'Human Hair Wig'";
        } elseif (strcasecmp($category, "Synthetic Wig") === 0) {
            $whereConditions[] = "category = 'Synthetic Wig'";
        } elseif (strcasecmp($category, "Wig") === 0) {
            $whereConditions[] = "(category = 'Wig' OR category = 'Human Hair Wig' OR category = 'Synthetic Wig')";
        } elseif (strcasecmp($category, "Hair Extension") === 0) {
            $whereConditions[] = "(category = 'Hair Extension' OR category LIKE '%Extension%')";
        } elseif ($category === 'Skin Systems') {
            $whereConditions[] = "(type = 'Skin Systems' OR base LIKE '%Skin%')";
        } elseif ($category === 'Swiss Lace') {
            $whereConditions[] = "(type = 'Swiss Lace' OR base LIKE '%Lace%')";
        } elseif ($category === 'PU Perimeter') {
            $whereConditions[] = "(type = 'PU Perimeter' OR base LIKE '%PU%')";
        } elseif ($category === 'CNC Virgin') {
            $whereConditions[] = "(type = 'CNC Virgin' OR base LIKE '%CNC%')";
        } else {
            $whereConditions[] = "(category = :category OR type = :category)";
            $params[':category'] = $category;
        }
    }

    $sql = "SELECT id, sku, name, category, type, base, size, density, color, CAST(price AS FLOAT) as price, stockQty, image, description, badge, is_visible FROM stock_items";
    if (!empty($whereConditions)) {
        $sql .= " WHERE " . implode(" AND ", $whereConditions);
    }

    // 정렬 조건
    if ($sort === 'price_asc') {
        $sql .= " ORDER BY price ASC";
    } elseif ($sort === 'price_desc') {
        $sql .= " ORDER BY price DESC";
    } elseif ($sort === 'qty_desc') {
        $sql .= " ORDER BY stockQty DESC";
    } else {
        $sql .= " ORDER BY category ASC, name ASC";
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'count' => count($items),
        'filter' => $category,
        'data' => $items
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database query error: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>
