<?php
// api_invoices.php - Invoicing, Receipts, Printing & Multi-channel Dispatch API
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
require_once __DIR__ . '/notification_helper.php';

// Ensure tables exist
try {
    $conn->exec("CREATE TABLE IF NOT EXISTS invoices (
        id INT AUTO_INCREMENT PRIMARY KEY,
        invoice_number VARCHAR(50) UNIQUE NOT NULL,
        invoice_type VARCHAR(30) DEFAULT 'Tax Invoice',
        invoice_date DATE NOT NULL,
        due_date DATE NULL,
        customer_name VARCHAR(150) NOT NULL,
        customer_phone VARCHAR(50) DEFAULT '',
        customer_email VARCHAR(150) DEFAULT '',
        customer_address VARCHAR(255) DEFAULT '',
        subtotal DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        tax_rate DECIMAL(5,2) DEFAULT 10.00,
        tax_amount DECIMAL(10,2) DEFAULT 0.00,
        discount DECIMAL(10,2) DEFAULT 0.00,
        total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        payment_method VARCHAR(50) DEFAULT 'Card / EFTPOS',
        status VARCHAR(30) DEFAULT 'Paid',
        notes TEXT,
        created_by VARCHAR(100) DEFAULT 'Admin',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_invoice_date (invoice_date),
        INDEX idx_customer_name (customer_name)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    $conn->exec("CREATE TABLE IF NOT EXISTS invoice_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        invoice_id INT NOT NULL,
        item_name VARCHAR(255) NOT NULL,
        description TEXT,
        quantity DECIMAL(10,2) NOT NULL DEFAULT 1.00,
        unit_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        sort_order INT DEFAULT 0,
        INDEX idx_invoice_id (invoice_id),
        CONSTRAINT fk_invoice_items_invoice FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
} catch (Exception $e) {
    sendJsonResponse(['status' => 'error', 'message' => 'DB init error: ' . $e->getMessage()], 500);
}

$action = $_GET['action'] ?? $_POST['action'] ?? 'list';

// Helper: Get next invoice number
function generate_next_invoice_number($conn) {
    $prefix = 'INV-' . date('ym') . '-';
    $stmt = $conn->prepare("SELECT invoice_number FROM invoices WHERE invoice_number LIKE :pattern ORDER BY id DESC LIMIT 1");
    $stmt->execute([':pattern' => $prefix . '%']);
    $last = $stmt->fetchColumn();

    if ($last) {
        $numPart = intval(substr($last, strlen($prefix)));
        $nextNum = str_pad($numPart + 1, 3, '0', STR_PAD_LEFT);
    } else {
        $nextNum = '001';
    }
    return $prefix . $nextNum;
}

// -------------------------------------------------------------
// 1. GET: List invoices with stats
// -------------------------------------------------------------
if ($action === 'list') {
    try {
        $search = trim($_GET['search'] ?? '');
        $status = trim($_GET['status'] ?? 'All');
        $fromDate = trim($_GET['from_date'] ?? '');
        $toDate = trim($_GET['to_date'] ?? '');

        $where = [];
        $params = [];

        if (!empty($search)) {
            $where[] = "(invoice_number LIKE :q OR customer_name LIKE :q OR customer_phone LIKE :q OR customer_email LIKE :q)";
            $params[':q'] = "%$search%";
        }

        if (!empty($status) && $status !== 'All') {
            $where[] = "status = :status";
            $params[':status'] = $status;
        }

        if (!empty($fromDate)) {
            $where[] = "invoice_date >= :from_date";
            $params[':from_date'] = $fromDate;
        }

        if (!empty($toDate)) {
            $where[] = "invoice_date <= :to_date";
            $params[':to_date'] = $toDate;
        }

        $whereSql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

        // Fetch list
        $sql = "SELECT i.*, 
                (SELECT COUNT(*) FROM invoice_items WHERE invoice_id = i.id) as item_count
                FROM invoices i
                $whereSql
                ORDER BY i.invoice_date DESC, i.id DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch stats
        $statsStmt = $conn->query("
            SELECT 
                COUNT(*) as total_count,
                COALESCE(SUM(total_amount), 0) as total_amount_all,
                COALESCE(SUM(CASE WHEN status = 'Paid' THEN total_amount ELSE 0 END), 0) as total_paid,
                COALESCE(SUM(CASE WHEN status != 'Paid' THEN total_amount ELSE 0 END), 0) as total_unpaid,
                COUNT(CASE WHEN status != 'Paid' THEN 1 ELSE NULL END) as unpaid_count,
                COALESCE(SUM(CASE WHEN DATE_FORMAT(invoice_date, '%Y-%m') = DATE_FORMAT(CURRENT_DATE, '%Y-%m') AND status = 'Paid' THEN total_amount ELSE 0 END), 0) as this_month_paid
            FROM invoices
        ");
        $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);

        sendJsonResponse([
            'status' => 'success',
            'invoices' => $invoices,
            'stats' => $stats
        ]);
    } catch (Exception $e) {
        sendJsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}

// -------------------------------------------------------------
// 2. GET: Single invoice with items
// -------------------------------------------------------------
if ($action === 'get') {
    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) {
        sendJsonResponse(['status' => 'error', 'message' => 'Invalid invoice ID'], 400);
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM invoices WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$invoice) {
            sendJsonResponse(['status' => 'error', 'message' => 'Invoice not found'], 404);
        }

        $itemStmt = $conn->prepare("SELECT * FROM invoice_items WHERE invoice_id = :id ORDER BY sort_order ASC, id ASC");
        $itemStmt->execute([':id' => $id]);
        $invoice['items'] = $itemStmt->fetchAll(PDO::FETCH_ASSOC);

        sendJsonResponse([
            'status' => 'success',
            'invoice' => $invoice
        ]);
    } catch (Exception $e) {
        sendJsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}

// -------------------------------------------------------------
// 3. GET: Next invoice number
// -------------------------------------------------------------
if ($action === 'get_next_number') {
    try {
        $nextNumber = generate_next_invoice_number($conn);
        sendJsonResponse([
            'status' => 'success',
            'next_number' => $nextNumber
        ]);
    } catch (Exception $e) {
        sendJsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}

// -------------------------------------------------------------
// Helper: read POST json payload
// -------------------------------------------------------------
$rawInput = file_get_contents('php://input');
$postData = json_decode($rawInput, true) ?: $_POST;

// -------------------------------------------------------------
// 4. POST: Create invoice
// -------------------------------------------------------------
if ($action === 'create') {
    $customerName = trim($postData['customer_name'] ?? '');
    if (empty($customerName)) {
        sendJsonResponse(['status' => 'error', 'message' => 'Customer name is required'], 400);
    }

    $invoiceNumber = trim($postData['invoice_number'] ?? '');
    if (empty($invoiceNumber)) {
        $invoiceNumber = generate_next_invoice_number($conn);
    }

    $invoiceType = trim($postData['invoice_type'] ?? 'Tax Invoice');
    $invoiceDate = !empty($postData['invoice_date']) ? $postData['invoice_date'] : date('Y-m-d');
    $dueDate = !empty($postData['due_date']) ? $postData['due_date'] : null;
    $customerPhone = trim($postData['customer_phone'] ?? '');
    $customerEmail = trim($postData['customer_email'] ?? '');
    $customerAddress = trim($postData['customer_address'] ?? '');
    $paymentMethod = trim($postData['payment_method'] ?? 'Card / EFTPOS');
    $status = trim($postData['status'] ?? 'Paid');
    $notes = trim($postData['notes'] ?? '');
    $createdBy = trim($postData['created_by'] ?? 'Admin');

    $items = is_array($postData['items'] ?? null) ? $postData['items'] : [];
    if (empty($items)) {
        sendJsonResponse(['status' => 'error', 'message' => 'At least one line item is required'], 400);
    }

    // Calculate totals
    $subtotal = 0;
    $cleanItems = [];
    foreach ($items as $idx => $it) {
        $name = trim($it['item_name'] ?? '');
        if (empty($name)) continue;
        $qty = max(0.01, floatval($it['quantity'] ?? 1));
        $price = max(0, floatval($it['unit_price'] ?? 0));
        $amount = round($qty * $price, 2);
        $subtotal += $amount;

        $cleanItems[] = [
            'item_name' => $name,
            'description' => trim($it['description'] ?? ''),
            'quantity' => $qty,
            'unit_price' => $price,
            'amount' => $amount,
            'sort_order' => $idx
        ];
    }

    if (empty($cleanItems)) {
        sendJsonResponse(['status' => 'error', 'message' => 'Valid items with names are required'], 400);
    }

    $taxRate = isset($postData['tax_rate']) ? floatval($postData['tax_rate']) : 10.0;
    $taxIncluded = !empty($postData['tax_included']); // whether tax is inclusive or exclusive
    $discount = max(0, floatval($postData['discount'] ?? 0));

    // If inclusive, subtotal already includes GST. If exclusive, add GST.
    // Default in Australia retail: prices are GST inclusive or GST is 10% of subtotal.
    if ($taxIncluded) {
        // GST is 10/110 of subtotal
        $taxAmount = round(($subtotal - $discount) / 11, 2);
        $totalAmount = round($subtotal - $discount, 2);
    } else {
        $taxAmount = round((($subtotal - $discount) * ($taxRate / 100)), 2);
        $totalAmount = round($subtotal - $discount + $taxAmount, 2);
    }
    if ($totalAmount < 0) $totalAmount = 0;

    try {
        $conn->beginTransaction();

        $sql = "INSERT INTO invoices (
            invoice_number, invoice_type, invoice_date, due_date,
            customer_name, customer_phone, customer_email, customer_address,
            subtotal, tax_rate, tax_amount, discount, total_amount,
            payment_method, status, notes, created_by
        ) VALUES (
            :invoice_number, :invoice_type, :invoice_date, :due_date,
            :customer_name, :customer_phone, :customer_email, :customer_address,
            :subtotal, :tax_rate, :tax_amount, :discount, :total_amount,
            :payment_method, :status, :notes, :created_by
        )";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':invoice_number' => $invoiceNumber,
            ':invoice_type' => $invoiceType,
            ':invoice_date' => $invoiceDate,
            ':due_date' => $dueDate,
            ':customer_name' => $customerName,
            ':customer_phone' => $customerPhone,
            ':customer_email' => $customerEmail,
            ':customer_address' => $customerAddress,
            ':subtotal' => $subtotal,
            ':tax_rate' => $taxRate,
            ':tax_amount' => $taxAmount,
            ':discount' => $discount,
            ':total_amount' => $totalAmount,
            ':payment_method' => $paymentMethod,
            ':status' => $status,
            ':notes' => $notes,
            ':created_by' => $createdBy
        ]);

        $invoiceId = $conn->lastInsertId();

        // Insert line items
        $itemSql = "INSERT INTO invoice_items (
            invoice_id, item_name, description, quantity, unit_price, amount, sort_order
        ) VALUES (
            :invoice_id, :item_name, :description, :quantity, :unit_price, :amount, :sort_order
        )";
        $itemStmt = $conn->prepare($itemSql);

        foreach ($cleanItems as $ci) {
            $itemStmt->execute([
                ':invoice_id' => $invoiceId,
                ':item_name' => $ci['item_name'],
                ':description' => $ci['description'],
                ':quantity' => $ci['quantity'],
                ':unit_price' => $ci['unit_price'],
                ':amount' => $ci['amount'],
                ':sort_order' => $ci['sort_order']
            ]);
        }

        $conn->commit();

        // Fetch back complete saved record
        $getStmt = $conn->prepare("SELECT * FROM invoices WHERE id = :id");
        $getStmt->execute([':id' => $invoiceId]);
        $savedInvoice = $getStmt->fetch(PDO::FETCH_ASSOC);

        $savedItemStmt = $conn->prepare("SELECT * FROM invoice_items WHERE invoice_id = :id ORDER BY sort_order ASC, id ASC");
        $savedItemStmt->execute([':id' => $invoiceId]);
        $savedInvoice['items'] = $savedItemStmt->fetchAll(PDO::FETCH_ASSOC);

        // Optionally send Telegram alert if requested in postData
        if (!empty($postData['send_telegram_on_create'])) {
            @send_telegram_invoice_alert($conn, $savedInvoice);
        }

        sendJsonResponse([
            'status' => 'success',
            'message' => 'Invoice created successfully',
            'invoice' => $savedInvoice
        ]);
    } catch (Exception $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        sendJsonResponse(['status' => 'error', 'message' => 'Save failed: ' . $e->getMessage()], 500);
    }
}

// -------------------------------------------------------------
// 5. POST: Update invoice
// -------------------------------------------------------------
if ($action === 'update') {
    $id = intval($postData['id'] ?? 0);
    if ($id <= 0) {
        sendJsonResponse(['status' => 'error', 'message' => 'Invalid invoice ID'], 400);
    }

    $customerName = trim($postData['customer_name'] ?? '');
    if (empty($customerName)) {
        sendJsonResponse(['status' => 'error', 'message' => 'Customer name is required'], 400);
    }

    $invoiceNumber = trim($postData['invoice_number'] ?? '');
    $invoiceType = trim($postData['invoice_type'] ?? 'Tax Invoice');
    $invoiceDate = !empty($postData['invoice_date']) ? $postData['invoice_date'] : date('Y-m-d');
    $dueDate = !empty($postData['due_date']) ? $postData['due_date'] : null;
    $customerPhone = trim($postData['customer_phone'] ?? '');
    $customerEmail = trim($postData['customer_email'] ?? '');
    $customerAddress = trim($postData['customer_address'] ?? '');
    $paymentMethod = trim($postData['payment_method'] ?? 'Card / EFTPOS');
    $status = trim($postData['status'] ?? 'Paid');
    $notes = trim($postData['notes'] ?? '');

    $items = is_array($postData['items'] ?? null) ? $postData['items'] : [];
    if (empty($items)) {
        sendJsonResponse(['status' => 'error', 'message' => 'At least one line item is required'], 400);
    }

    $subtotal = 0;
    $cleanItems = [];
    foreach ($items as $idx => $it) {
        $name = trim($it['item_name'] ?? '');
        if (empty($name)) continue;
        $qty = max(0.01, floatval($it['quantity'] ?? 1));
        $price = max(0, floatval($it['unit_price'] ?? 0));
        $amount = round($qty * $price, 2);
        $subtotal += $amount;

        $cleanItems[] = [
            'item_name' => $name,
            'description' => trim($it['description'] ?? ''),
            'quantity' => $qty,
            'unit_price' => $price,
            'amount' => $amount,
            'sort_order' => $idx
        ];
    }

    $taxRate = isset($postData['tax_rate']) ? floatval($postData['tax_rate']) : 10.0;
    $taxIncluded = !empty($postData['tax_included']);
    $discount = max(0, floatval($postData['discount'] ?? 0));

    if ($taxIncluded) {
        $taxAmount = round(($subtotal - $discount) / 11, 2);
        $totalAmount = round($subtotal - $discount, 2);
    } else {
        $taxAmount = round((($subtotal - $discount) * ($taxRate / 100)), 2);
        $totalAmount = round($subtotal - $discount + $taxAmount, 2);
    }
    if ($totalAmount < 0) $totalAmount = 0;

    try {
        $conn->beginTransaction();

        $sql = "UPDATE invoices SET
            invoice_number = :invoice_number,
            invoice_type = :invoice_type,
            invoice_date = :invoice_date,
            due_date = :due_date,
            customer_name = :customer_name,
            customer_phone = :customer_phone,
            customer_email = :customer_email,
            customer_address = :customer_address,
            subtotal = :subtotal,
            tax_rate = :tax_rate,
            tax_amount = :tax_amount,
            discount = :discount,
            total_amount = :total_amount,
            payment_method = :payment_method,
            status = :status,
            notes = :notes
            WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':invoice_number' => $invoiceNumber,
            ':invoice_type' => $invoiceType,
            ':invoice_date' => $invoiceDate,
            ':due_date' => $dueDate,
            ':customer_name' => $customerName,
            ':customer_phone' => $customerPhone,
            ':customer_email' => $customerEmail,
            ':customer_address' => $customerAddress,
            ':subtotal' => $subtotal,
            ':tax_rate' => $taxRate,
            ':tax_amount' => $taxAmount,
            ':discount' => $discount,
            ':total_amount' => $totalAmount,
            ':payment_method' => $paymentMethod,
            ':status' => $status,
            ':notes' => $notes
        ]);

        // Replace line items
        $conn->prepare("DELETE FROM invoice_items WHERE invoice_id = :id")->execute([':id' => $id]);

        $itemSql = "INSERT INTO invoice_items (
            invoice_id, item_name, description, quantity, unit_price, amount, sort_order
        ) VALUES (
            :invoice_id, :item_name, :description, :quantity, :unit_price, :amount, :sort_order
        )";
        $itemStmt = $conn->prepare($itemSql);

        foreach ($cleanItems as $ci) {
            $itemStmt->execute([
                ':invoice_id' => $id,
                ':item_name' => $ci['item_name'],
                ':description' => $ci['description'],
                ':quantity' => $ci['quantity'],
                ':unit_price' => $ci['unit_price'],
                ':amount' => $ci['amount'],
                ':sort_order' => $ci['sort_order']
            ]);
        }

        $conn->commit();

        $getStmt = $conn->prepare("SELECT * FROM invoices WHERE id = :id");
        $getStmt->execute([':id' => $id]);
        $savedInvoice = $getStmt->fetch(PDO::FETCH_ASSOC);

        $savedItemStmt = $conn->prepare("SELECT * FROM invoice_items WHERE invoice_id = :id ORDER BY sort_order ASC, id ASC");
        $savedItemStmt->execute([':id' => $id]);
        $savedInvoice['items'] = $savedItemStmt->fetchAll(PDO::FETCH_ASSOC);

        sendJsonResponse([
            'status' => 'success',
            'message' => 'Invoice updated successfully',
            'invoice' => $savedInvoice
        ]);
    } catch (Exception $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        sendJsonResponse(['status' => 'error', 'message' => 'Update failed: ' . $e->getMessage()], 500);
    }
}

// -------------------------------------------------------------
// 6. POST: Quick status toggle
// -------------------------------------------------------------
if ($action === 'update_status') {
    $id = intval($postData['id'] ?? 0);
    $status = trim($postData['status'] ?? 'Paid');

    if ($id <= 0) {
        sendJsonResponse(['status' => 'error', 'message' => 'Invalid ID'], 400);
    }

    try {
        $stmt = $conn->prepare("UPDATE invoices SET status = :status WHERE id = :id");
        $stmt->execute([':status' => $status, ':id' => $id]);
        sendJsonResponse(['status' => 'success', 'message' => 'Status updated']);
    } catch (Exception $e) {
        sendJsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}

// -------------------------------------------------------------
// 7. POST: Delete invoice
// -------------------------------------------------------------
if ($action === 'delete') {
    $id = intval($postData['id'] ?? 0);
    if ($id <= 0) {
        sendJsonResponse(['status' => 'error', 'message' => 'Invalid ID'], 400);
    }

    try {
        $stmt = $conn->prepare("DELETE FROM invoices WHERE id = :id");
        $stmt->execute([':id' => $id]);
        sendJsonResponse(['status' => 'success', 'message' => 'Invoice deleted successfully']);
    } catch (Exception $e) {
        sendJsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}

// -------------------------------------------------------------
// 8. POST: Send Telegram Alert
// -------------------------------------------------------------
if ($action === 'send_telegram') {
    $id = intval($postData['id'] ?? 0);
    $customChatId = trim($postData['chat_id'] ?? '');

    try {
        // Fetch invoice with items
        $stmt = $conn->prepare("SELECT * FROM invoices WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$invoice) {
            sendJsonResponse(['status' => 'error', 'message' => 'Invoice not found'], 404);
        }

        $itemStmt = $conn->prepare("SELECT * FROM invoice_items WHERE invoice_id = :id ORDER BY sort_order ASC");
        $itemStmt->execute([':id' => $id]);
        $invoice['items'] = $itemStmt->fetchAll(PDO::FETCH_ASSOC);

        $res = send_telegram_invoice_alert($conn, $invoice, !empty($customChatId) ? $customChatId : null);

        if ($res['status'] === 'success') {
            sendJsonResponse([
                'status' => 'success',
                'message' => 'Telegram message sent successfully!',
                'data' => $res
            ]);
        } else {
            sendJsonResponse([
                'status' => 'error',
                'message' => $res['message'] ?? 'Failed to send Telegram message'
            ], 400);
        }
    } catch (Exception $e) {
        sendJsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}

sendJsonResponse(['status' => 'error', 'message' => 'Unknown action'], 400);
