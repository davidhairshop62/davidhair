<?php
// notification_helper.php - David Hair Booking Notifications (Telegram & Email)

require_once __DIR__ . '/config.php';

/**
 * Get notification settings from salon_schedule_settings table
 */
function get_notification_settings($conn) {
    $defaults = [
        'telegram_bot_token' => '',
        'telegram_chat_id' => '',
        'telegram_enabled' => '1',
        'notification_email' => 'shc9607@gmail.com',
        'email_enabled' => '1',
        'smtp_host' => 'smtp.gmail.com',
        'smtp_port' => '587',
        'smtp_user' => 'shc9607@gmail.com',
        'smtp_pass' => '',
    ];

    try {
        $keys = array_keys($defaults);
        $in = "'" . implode("','", $keys) . "'";
        $stmt = $conn->query("SELECT setting_key, setting_value FROM salon_schedule_settings WHERE setting_key IN ($in)");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $defaults[$row['setting_key']] = $row['setting_value'];
        }
    } catch (Exception $e) {
        // Table fallback
    }

    return $defaults;
}

/**
 * Send Telegram Notification
 */
function send_telegram_booking_alert($booking, $botToken, $chatId) {
    if (empty($botToken) || empty($chatId)) {
        return [
            'status' => 'skipped',
            'message' => 'Telegram Bot Token or Chat ID not configured.'
        ];
    }

    $name = htmlspecialchars($booking['name'] ?? $booking['customer_name'] ?? 'Guest', ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($booking['phone'] ?? '', ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($booking['email'] ?? 'Not provided', ENT_QUOTES, 'UTF-8');
    $service = htmlspecialchars($booking['service'] ?? $booking['service_type'] ?? 'Hair Fitting', ENT_QUOTES, 'UTF-8');
    $date = htmlspecialchars($booking['date'] ?? $booking['booking_date'] ?? '', ENT_QUOTES, 'UTF-8');
    $time = htmlspecialchars($booking['time'] ?? $booking['booking_time'] ?? '', ENT_QUOTES, 'UTF-8');
    $notes = htmlspecialchars($booking['notes'] ?? 'None', ENT_QUOTES, 'UTF-8');
    $bookingId = intval($booking['booking_id'] ?? $booking['id'] ?? 0);

    // Format clean phone for tel link
    $cleanPhone = preg_replace('/[^0-9+]/', '', $phone);

    $msg  = "🔔 <b>[David Hair] 새로운 피팅 예약 접수!</b>\n";
    $msg .= "━━━━━━━━━━━━━━━━━━━━━━\n";
    if ($bookingId > 0) {
        $msg .= "🔖 <b>예약 번호:</b> #{$bookingId}\n";
    }
    $msg .= "👤 <b>고객명:</b> {$name}\n";
    $msg .= "📞 <b>연락처:</b> <a href=\"tel:{$cleanPhone}\">{$phone}</a>\n";
    $msg .= "📧 <b>이메일:</b> {$email}\n";
    $msg .= "💇 <b>서비스:</b> {$service}\n";
    $msg .= "📅 <b>예약일자:</b> <b>{$date}</b>\n";
    $msg .= "⏰ <b>예약시간:</b> <b>{$time}</b>\n";
    if (!empty($notes) && $notes !== 'None') {
        $msg .= "📝 <b>요청사항:</b> <i>{$notes}</i>\n";
    }
    $msg .= "━━━━━━━━━━━━━━━━━━━━━━\n";
    $msg .= "👉 <a href=\"https://www.davidhairshop.com/stock_manager.php\">관리자 페이지에서 확인하기</a>";

    $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
    $postFields = [
        'chat_id' => $chatId,
        'text' => $msg,
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => false
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

    $response = curl_exec($ch);
    $err = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($err) {
        return ['status' => 'error', 'message' => "cURL Error: " . $err];
    }

    $json = json_decode($response, true);
    if ($httpCode === 200 && !empty($json['ok'])) {
        return ['status' => 'success', 'data' => $json];
    }

    $errMsg = $json['description'] ?? "HTTP Code {$httpCode}";
    return ['status' => 'error', 'message' => "Telegram Error: " . $errMsg];
}

/**
 * Send Email Notification (HTML template)
 */
function send_email_booking_alert($booking, $recipientEmail, $smtpSettings = []) {
    if (empty($recipientEmail)) {
        return ['status' => 'skipped', 'message' => 'Recipient email not configured.'];
    }

    $name = htmlspecialchars($booking['name'] ?? $booking['customer_name'] ?? 'Guest', ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($booking['phone'] ?? '', ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($booking['email'] ?? 'Not provided', ENT_QUOTES, 'UTF-8');
    $service = htmlspecialchars($booking['service'] ?? $booking['service_type'] ?? 'Hair Fitting', ENT_QUOTES, 'UTF-8');
    $date = htmlspecialchars($booking['date'] ?? $booking['booking_date'] ?? '', ENT_QUOTES, 'UTF-8');
    $time = htmlspecialchars($booking['time'] ?? $booking['booking_time'] ?? '', ENT_QUOTES, 'UTF-8');
    $notes = htmlspecialchars($booking['notes'] ?? 'None', ENT_QUOTES, 'UTF-8');
    $bookingId = intval($booking['booking_id'] ?? $booking['id'] ?? 0);

    $subject = "=?UTF-8?B?" . base64_encode("[David Hair] New Booking Alert: {$name} ({$date} {$time})") . "?=";

    $htmlBody = <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #0b1120; color: #f1f5f9; padding: 20px; }
.card { max-width: 580px; margin: 0 auto; background: #1e293b; border-radius: 16px; border: 1px solid #334155; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
.header { background: linear-gradient(135deg, #0f766e, #0e7490); padding: 24px; text-align: center; color: #fff; }
.header h2 { margin: 0; font-size: 20px; font-weight: 800; }
.header p { margin: 6px 0 0; font-size: 13px; opacity: 0.9; }
.content { padding: 24px; }
.item { margin-bottom: 14px; padding-bottom: 14px; border-bottom: 1px solid #334155; }
.item:last-child { border-bottom: none; }
.label { font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 700; letter-spacing: 0.5px; }
.value { font-size: 15px; color: #f8fafc; font-weight: 600; margin-top: 4px; }
.highlight { color: #38bdf8; font-size: 16px; }
.btn { display: inline-block; background: #0f766e; color: #fff !important; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 700; font-size: 13px; text-align: center; margin-top: 10px; }
.footer { text-align: center; font-size: 12px; color: #64748b; padding: 16px; background: #0f172a; }
</style>
</head>
<body>
<div class="card">
    <div class="header">
        <h2>🔔 New Fitting Appointment</h2>
        <p>David Hair Melbourne • Springvale Studio</p>
    </div>
    <div class="content">
        <div class="item">
            <div class="label">Booking ID</div>
            <div class="value">#{$bookingId}</div>
        </div>
        <div class="item">
            <div class="label">Customer Name</div>
            <div class="value highlight">{$name}</div>
        </div>
        <div class="item">
            <div class="label">Contact Phone</div>
            <div class="value"><a href="tel:{$phone}" style="color: #2dd4bf; text-decoration: none;">{$phone}</a></div>
        </div>
        <div class="item">
            <div class="label">Customer Email</div>
            <div class="value">{$email}</div>
        </div>
        <div class="item">
            <div class="label">Requested Date &amp; Time</div>
            <div class="value" style="color: #fbbf24;">{$date} at {$time}</div>
        </div>
        <div class="item">
            <div class="label">Service Type</div>
            <div class="value">{$service}</div>
        </div>
        <div class="item">
            <div class="label">Notes / Requests</div>
            <div class="value" style="font-weight: normal; color: #cbd5e1;">{$notes}</div>
        </div>
        <div style="text-align: center; padding-top: 10px;">
            <a href="https://www.davidhairshop.com/stock_manager.php" class="btn">Open Admin Dashboard</a>
        </div>
    </div>
    <div class="footer">
        David Hair Melbourne Flagship Studio &bull; Shop F16 Buckingham Ave, Springvale VIC 3171
    </div>
</div>
</body>
</html>
HTML;

    // Check if SMTP credentials provided
    if (!empty($smtpSettings['smtp_user']) && !empty($smtpSettings['smtp_pass'])) {
        return send_smtp_email(
            $smtpSettings['smtp_host'] ?? 'smtp.gmail.com',
            intval($smtpSettings['smtp_port'] ?? 587),
            $smtpSettings['smtp_user'],
            $smtpSettings['smtp_pass'],
            $recipientEmail,
            $subject,
            $htmlBody
        );
    }

    // Default: Native mail()
    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: David Hair Booking <booking@davidhairshop.com>',
        'Reply-To: ' . (!empty($booking['email']) ? $booking['email'] : 'booking@davidhairshop.com'),
        'X-Mailer: PHP/' . phpversion()
    ];

    $sent = @mail($recipientEmail, $subject, $htmlBody, implode("\r\n", $headers));
    if ($sent) {
        return ['status' => 'success', 'message' => 'Email sent via mail()'];
    }
    return ['status' => 'error', 'message' => 'Failed to send email via mail()'];
}

/**
 * Socket-based lightweight SMTP client (TLS/STARTTLS)
 */
function send_smtp_email($host, $port, $user, $pass, $to, $subject, $htmlBody) {
    $timeout = 10;
    $socket = fsockopen($host, $port, $errno, $errstr, $timeout);
    if (!$socket) {
        return ['status' => 'error', 'message' => "SMTP Connection failed: $errstr ($errno)"];
    }

    $read = function() use ($socket) {
        $res = '';
        while ($line = fgets($socket, 515)) {
            $res .= $line;
            if (substr($line, 3, 1) === ' ') break;
        }
        return $res;
    };

    $send = function($cmd) use ($socket) {
        fputs($socket, $cmd . "\r\n");
    };

    $res = $read();

    $send("EHLO " . gethostname());
    $res = $read();

    if ($port == 587) {
        $send("STARTTLS");
        $res = $read();
        if (substr($res, 0, 3) !== '220') {
            fclose($socket);
            return ['status' => 'error', 'message' => "STARTTLS failed: $res"];
        }
        stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
        $send("EHLO " . gethostname());
        $res = $read();
    }

    $send("AUTH LOGIN");
    $res = $read();
    $send(base64_encode($user));
    $res = $read();
    $send(base64_encode($pass));
    $res = $read();
    if (substr($res, 0, 3) !== '235') {
        fclose($socket);
        return ['status' => 'error', 'message' => "SMTP Auth failed: $res"];
    }

    $send("MAIL FROM: <$user>");
    $res = $read();
    $send("RCPT TO: <$to>");
    $res = $read();
    $send("DATA");
    $res = $read();

    $headers = [
        "From: David Hair Booking <$user>",
        "To: <$to>",
        "Subject: $subject",
        "MIME-Version: 1.0",
        "Content-Type: text/html; charset=UTF-8",
        "X-Mailer: DavidHairSMTP"
    ];

    $emailData = implode("\r\n", $headers) . "\r\n\r\n" . $htmlBody . "\r\n.\r\n";
    $send($emailData);
    $res = $read();
    $send("QUIT");
    fclose($socket);

    if (substr($res, 0, 3) === '250') {
        return ['status' => 'success', 'message' => 'Email sent via authenticated SMTP'];
    }
    return ['status' => 'error', 'message' => "SMTP Delivery Error: $res"];
}

/**
 * Main dispatcher to send both Telegram and Email notifications
 */
function dispatch_booking_notifications($conn, $bookingData) {
    $settings = get_notification_settings($conn);
    $results = [];

    // 1. Telegram
    if (!empty($settings['telegram_enabled']) && $settings['telegram_enabled'] === '1') {
        $results['telegram'] = send_telegram_booking_alert(
            $bookingData,
            $settings['telegram_bot_token'],
            $settings['telegram_chat_id']
        );
    } else {
        $results['telegram'] = ['status' => 'disabled'];
    }

    // 2. Email
    if (!empty($settings['email_enabled']) && $settings['email_enabled'] === '1') {
        $results['email'] = send_email_booking_alert(
            $bookingData,
            $settings['notification_email'],
            [
                'smtp_host' => $settings['smtp_host'],
                'smtp_port' => $settings['smtp_port'],
                'smtp_user' => $settings['smtp_user'],
                'smtp_pass' => $settings['smtp_pass'],
            ]
        );
    } else {
        $results['email'] = ['status' => 'disabled'];
    }

    // Log to file for tracking
    try {
        $logFile = __DIR__ . '/booking_notifications.log';
        $logEntry = date('Y-m-d H:i:s') . ' | Booking #' . ($bookingData['booking_id'] ?? 0)
                  . ' | Telegram: ' . ($results['telegram']['status'] ?? 'unknown')
                  . ' | Email: ' . ($results['email']['status'] ?? 'unknown')
                  . "\n";
        @file_put_contents($logFile, $logEntry, FILE_APPEND);
    } catch (Exception $e) {}

    return $results;
}

/**
 * Send Telegram Invoice / Receipt Alert
 */
function send_telegram_invoice_alert($conn, $invoice, $customChatId = null) {
    $settings = get_notification_settings($conn);
    $botToken = $settings['telegram_bot_token'] ?? '';
    $chatId = $customChatId ?: ($settings['telegram_chat_id'] ?? '');

    if (empty($botToken) || empty($chatId)) {
        return ['status' => 'skipped', 'message' => 'Telegram Bot Token or Chat ID not configured.'];
    }

    $invNum = htmlspecialchars($invoice['invoice_number'] ?? 'N/A', ENT_QUOTES, 'UTF-8');
    $invType = htmlspecialchars($invoice['invoice_type'] ?? 'Tax Invoice', ENT_QUOTES, 'UTF-8');
    $invDate = htmlspecialchars($invoice['invoice_date'] ?? date('Y-m-d'), ENT_QUOTES, 'UTF-8');
    $custName = htmlspecialchars($invoice['customer_name'] ?? 'Valued Customer', ENT_QUOTES, 'UTF-8');
    $custPhone = htmlspecialchars($invoice['customer_phone'] ?? '', ENT_QUOTES, 'UTF-8');
    $totalAmount = number_format(floatval($invoice['total_amount'] ?? 0), 2);
    $paymentMethod = htmlspecialchars($invoice['payment_method'] ?? 'Card', ENT_QUOTES, 'UTF-8');
    $status = htmlspecialchars($invoice['status'] ?? 'Paid', ENT_QUOTES, 'UTF-8');
    $notes = htmlspecialchars($invoice['notes'] ?? '', ENT_QUOTES, 'UTF-8');

    $msg  = "🧾 <b>[David Hair] {$invType} (#{$invNum})</b>\n";
    $msg .= "━━━━━━━━━━━━━━━━━━━━━━\n";
    $msg .= "📅 <b>일자:</b> {$invDate}\n";
    $msg .= "👤 <b>고객명:</b> {$custName}\n";
    if (!empty($custPhone)) {
        $msg .= "📞 <b>연락처:</b> {$custPhone}\n";
    }
    $msg .= "💳 <b>결제:</b> {$paymentMethod} (<b>{$status}</b>)\n";
    $msg .= "━━━━━━━━━━━━━━━━━━━━━━\n";
    $msg .= "🛍️ <b>항목 내역:</b>\n";

    if (!empty($invoice['items']) && is_array($invoice['items'])) {
        foreach ($invoice['items'] as $it) {
            $iName = htmlspecialchars($it['item_name'] ?? 'Item', ENT_QUOTES, 'UTF-8');
            $iQty = floatval($it['quantity'] ?? 1);
            $iPrice = number_format(floatval($it['unit_price'] ?? 0), 2);
            $iAmount = number_format(floatval($it['amount'] ?? 0), 2);
            $msg .= "• {$iName} x {$iQty} (\${$iPrice}) = <b>\${$iAmount}</b>\n";
        }
    }

    $subtotal = number_format(floatval($invoice['subtotal'] ?? 0), 2);
    $tax = number_format(floatval($invoice['tax_amount'] ?? 0), 2);
    $discount = floatval($invoice['discount'] ?? 0);

    $msg .= "──────────────────────\n";
    if ($discount > 0) {
        $msg .= "할인(Discount): -\$" . number_format($discount, 2) . "\n";
    }
    if (floatval($invoice['tax_amount'] ?? 0) > 0) {
        $msg .= "GST (10%): \${$tax} 포함\n";
    }
    $msg .= "💰 <b>총 결제금액: AUD \${$totalAmount}</b>\n";
    $msg .= "━━━━━━━━━━━━━━━━━━━━━━\n";
    if (!empty($notes)) {
        $msg .= "📝 <i>{$notes}</i>\n";
    }
    $msg .= "📍 David Hair Systems (Springvale Shop 62)\n";
    $msg .= "👉 <a href=\"https://www.davidhairshop.com/stock_manager.php\">재고/인보이스 관리 페이지 열기</a>";

    $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
    $postFields = [
        'chat_id' => $chatId,
        'text' => $msg,
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => true
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

    $response = curl_exec($ch);
    $err = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($err) {
        return ['status' => 'error', 'message' => "cURL Error: " . $err];
    }

    $resData = json_decode($response, true);
    if ($httpCode === 200 && !empty($resData['ok'])) {
        return ['status' => 'success', 'data' => $resData];
    } else {
        return ['status' => 'error', 'message' => $resData['description'] ?? 'Telegram API error', 'raw' => $response];
    }
}

