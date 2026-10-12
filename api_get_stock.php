<?php
// api_get_stock.php - Fetch In-Stock Precision Pieces from MySQL Database
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

require_once __DIR__ . '/config.php';

try {
    // 필터링 파라미터 수신 (카테고리 또는 타입 조건)
    $category = isset($_GET['category']) ? trim($_GET['category']) : 'All';
    $sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'default';

    $sql = "SELECT id, sku, name, category, type, base, size, density, color, CAST(price AS FLOAT) as price, stockQty, image, description, badge FROM stock_items";
    $params = [];

    // 조건별 SQL 쿼리 나열
    if ($category !== 'All' && !empty($category)) {
        if ($category === "Men's Toupees" || $category === "Women's Toppers") {
            $sql .= " WHERE category = :category";
            $params[':category'] = $category;
        } elseif ($category === 'Skin Systems') {
            $sql .= " WHERE type = 'Skin Systems' OR base LIKE '%Skin%'";
        } elseif ($category === 'Swiss Lace') {
            $sql .= " WHERE type = 'Swiss Lace' OR base LIKE '%Lace%'";
        } elseif ($category === 'PU Perimeter') {
            $sql .= " WHERE type = 'PU Perimeter' OR base LIKE '%PU%'";
        } elseif ($category === 'CNC Virgin') {
            $sql .= " WHERE type = 'CNC Virgin' OR base LIKE '%CNC%'";
        } else {
            $sql .= " WHERE category = :category OR type = :category";
            $params[':category'] = $category;
        }
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
