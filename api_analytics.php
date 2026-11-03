<?php
// api_analytics.php - Website Visitor Tracking & Daily Analytics API
// Supports: Real-time client-side tracking, Apache log ingestion, daily trend aggregation, device/geo metrics, and CSV export.
error_reporting(0);
ini_set('display_errors', '0');
ob_start();
session_start();

function sendJsonResponse($data, $statusCode = 200) {
    if (ob_get_length()) ob_clean();
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    sendJsonResponse(['status' => 'ok']);
}

require_once __DIR__ . '/config.php';

// Helper: Ensure tables exist
try {
    $conn->exec("CREATE TABLE IF NOT EXISTS visitor_logs (
        id BIGINT AUTO_INCREMENT PRIMARY KEY,
        visitor_id VARCHAR(64) NOT NULL,
        session_id VARCHAR(64) NOT NULL,
        ip_address VARCHAR(45) NOT NULL,
        page_url VARCHAR(500) NOT NULL,
        page_title VARCHAR(255) DEFAULT '',
        referrer VARCHAR(500) DEFAULT '',
        referrer_domain VARCHAR(100) DEFAULT '',
        device_type VARCHAR(20) DEFAULT 'desktop',
        os VARCHAR(50) DEFAULT 'Unknown',
        browser VARCHAR(50) DEFAULT 'Unknown',
        country VARCHAR(100) DEFAULT 'Unknown',
        country_code VARCHAR(10) DEFAULT '',
        region VARCHAR(100) DEFAULT '',
        city VARCHAR(100) DEFAULT '',
        screen_res VARCHAR(30) DEFAULT '',
        language VARCHAR(20) DEFAULT '',
        event_type VARCHAR(50) DEFAULT 'pageview',
        event_data TEXT DEFAULT NULL,
        duration_sec INT DEFAULT 0,
        is_admin TINYINT(1) DEFAULT 0,
        is_bot TINYINT(1) DEFAULT 0,
        visit_date DATE NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_visit_date (visit_date),
        INDEX idx_created_at (created_at),
        INDEX idx_visitor_id (visitor_id),
        INDEX idx_ip (ip_address),
        INDEX idx_page (page_url(191)),
        INDEX idx_event (event_type),
        INDEX idx_device (device_type)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $conn->exec("CREATE TABLE IF NOT EXISTS ip_geo_cache (
        ip VARCHAR(45) PRIMARY KEY,
        country VARCHAR(100) DEFAULT 'Unknown',
        country_code VARCHAR(10) DEFAULT '',
        region VARCHAR(100) DEFAULT '',
        city VARCHAR(100) DEFAULT '',
        isp VARCHAR(150) DEFAULT '',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Auto-check columns
    $cols = $conn->query("SHOW COLUMNS FROM visitor_logs")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('region', $cols)) {
        $conn->exec("ALTER TABLE visitor_logs ADD COLUMN region VARCHAR(100) DEFAULT '' AFTER country_code");
    }
} catch (Exception $e) {
    // Ignore if exists
}

// Helper: Get Client IP (supporting Cloudflare & Proxies)
function getClientIp() {
    $headers = [
        'HTTP_CF_CONNECTING_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_REAL_IP',
        'HTTP_CLIENT_IP',
        'REMOTE_ADDR'
    ];
    foreach ($headers as $header) {
        if (!empty($_SERVER[$header])) {
            $ipList = explode(',', $_SERVER[$header]);
            $ip = trim($ipList[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }
    return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}

// Helper: Detect Bot User Agents
function isBotUserAgent($userAgent) {
    if (empty($userAgent)) return true;
    $botPatterns = [
        'bot', 'crawl', 'spider', 'slurp', 'mediapartners', 'googlebot', 'bingbot',
        'yandex', 'baidu', 'duckduckgo', 'yahoo', 'sogou', 'exabot', 'facebot',
        'facebookexternalhit', 'ia_archiver', 'python', 'curl', 'wget', 'ahrefs',
        'semrush', 'mj12bot', 'dotbot', 'petalbot', 'bytespider', 'gptbot',
        'claudebot', 'oai-searchbot', 'headlesschrome', 'lighthouse', 'screaming frog'
    ];
    $uaLower = strtolower($userAgent);
    foreach ($botPatterns as $bot) {
        if (strpos($uaLower, $bot) !== false) {
            return true;
        }
    }
    return false;
}

// Helper: Parse Device, OS, and Browser from User Agent
function parseUserAgentInfo($userAgent) {
    $ua = $userAgent ?? '';
    $device = 'desktop';
    $os = 'Unknown OS';
    $browser = 'Unknown Browser';

    // Device & OS
    if (preg_match('/(ipad|tablet|(android(?!.*mobile))|(windows(?!.*phone)(.*touch))|kindle|playbook|silk|(puffin(?!.*(IP|AP|WP))))/i', $ua)) {
        $device = 'tablet';
    } elseif (preg_match('/(mobi|iphone|ipod|android.*mobile|blackberry|opera mini|iemobile|wpdesktop)/i', $ua)) {
        $device = 'mobile';
    } else {
        $device = 'desktop';
    }

    if (preg_match('/iPhone|iPad|iPod/i', $ua)) {
        $os = 'iOS';
    } elseif (preg_match('/Android/i', $ua)) {
        $os = 'Android';
    } elseif (preg_match('/Macintosh|Mac OS X/i', $ua)) {
        $os = 'macOS';
    } elseif (preg_match('/Windows NT 10/i', $ua)) {
        $os = 'Windows 10/11';
    } elseif (preg_match('/Windows/i', $ua)) {
        $os = 'Windows';
    } elseif (preg_match('/Linux/i', $ua)) {
        $os = 'Linux';
    } elseif (preg_match('/CrOS/i', $ua)) {
        $os = 'ChromeOS';
    }

    // Browser
    if (preg_match('/SamsungBrowser\/([0-9.]+)/i', $ua)) {
        $browser = 'Samsung Internet';
    } elseif (preg_match('/Whale\/([0-9.]+)/i', $ua)) {
        $browser = 'Naver Whale';
    } elseif (preg_match('/KAKAOTALK/i', $ua)) {
        $browser = 'KakaoTalk';
    } elseif (preg_match('/Instagram/i', $ua)) {
        $browser = 'Instagram';
    } elseif (preg_match('/Edg\/([0-9.]+)/i', $ua)) {
        $browser = 'Edge';
    } elseif (preg_match('/Chrome\/([0-9.]+)/i', $ua)) {
        $browser = 'Chrome';
    } elseif (preg_match('/Safari\/([0-9.]+)/i', $ua) && !preg_match('/Chrome/i', $ua)) {
        $browser = 'Safari';
    } elseif (preg_match('/Firefox\/([0-9.]+)/i', $ua)) {
        $browser = 'Firefox';
    } elseif (preg_match('/MSIE|Trident/i', $ua)) {
        $browser = 'Internet Explorer';
    }

    return ['device' => $device, 'os' => $os, 'browser' => $browser];
}

// Helper: Extract referrer domain
function extractReferrerDomain($ref) {
    if (empty($ref)) return '';
    $parsed = parse_url($ref);
    $host = $parsed['host'] ?? '';
    return strtolower(preg_replace('/^www\./', '', $host));
}

// Helper: Geolocation via DB cache or ip-api.com
function getIpGeoLocation($conn, $ip) {
    if (empty($ip) || $ip === '127.0.0.1' || $ip === '::1' || strpos($ip, '192.168.') === 0 || strpos($ip, '10.') === 0) {
        return [
            'country' => 'Australia',
            'country_code' => 'AU',
            'region' => 'Victoria',
            'city' => 'Melbourne',
            'isp' => 'Localhost / Internal'
        ];
    }

    // Check Cloudflare header if present
    $cfCountry = $_SERVER['HTTP_CF_IPCOUNTRY'] ?? '';

    // Check cache
    try {
        $stmt = $conn->prepare("SELECT country, country_code, region, city, isp FROM ip_geo_cache WHERE ip = ? LIMIT 1");
        $stmt->execute([$ip]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && !empty($row['country'])) {
            return $row;
        }
    } catch (Exception $e) {}

    // Lookup online
    $geo = [
        'country' => !empty($cfCountry) ? $cfCountry : 'Australia',
        'country_code' => !empty($cfCountry) ? $cfCountry : 'AU',
        'region' => 'Victoria',
        'city' => 'Melbourne',
        'isp' => 'Broadband'
    ];

    try {
        $ctx = stream_context_create([
            'http' => [
                'timeout' => 1.5,
                'ignore_errors' => true
            ]
        ]);
        $json = @file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,countryCode,regionName,city,isp", false, $ctx);
        if ($json) {
            $data = json_decode($json, true);
            if ($data && ($data['status'] ?? '') === 'success') {
                $geo = [
                    'country' => $data['country'] ?? 'Australia',
                    'country_code' => $data['countryCode'] ?? 'AU',
                    'region' => $data['regionName'] ?? '',
                    'city' => $data['city'] ?? '',
                    'isp' => $data['isp'] ?? ''
                ];
            }
        }
    } catch (Exception $e) {}

    // Save to cache
    try {
        $ins = $conn->prepare("INSERT INTO ip_geo_cache (ip, country, country_code, region, city, isp) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE country=VALUES(country), city=VALUES(city)");
        $ins->execute([$ip, $geo['country'], $geo['country_code'], $geo['region'], $geo['city'], $geo['isp']]);
    } catch (Exception $e) {}

    return $geo;
}

// Require admin authentication for admin operations
function requireAdminAuth() {
    if (empty($_SESSION['admin_user'])) {
        sendJsonResponse(['status' => 'error', 'message' => 'Admin authentication required.'], 401);
    }
}

$action = $_GET['action'] ?? $_POST['action'] ?? 'track';

// ==========================================
// 1. PUBLIC ACTION: TRACK BEACON / PAGEVIEW
// ==========================================
if ($action === 'track') {
    $raw = file_get_contents('php://input');
    $payload = json_decode($raw, true) ?: $_POST;

    $ip = getClientIp();
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? ($payload['user_agent'] ?? '');
    $isBot = isBotUserAgent($userAgent) ? 1 : 0;

    $visitorId = trim($payload['visitor_id'] ?? '');
    if (empty($visitorId)) {
        $visitorId = 'dh_' . substr(md5($ip . ($userAgent ?? '') . date('Y-m-d')), 0, 16);
    }

    $sessionId = trim($payload['session_id'] ?? '');
    if (empty($sessionId)) {
        $sessionId = 'sess_' . substr(md5(session_id() . microtime()), 0, 16);
    }

    $pageUrl = trim($payload['page_url'] ?? $_SERVER['REQUEST_URI'] ?? '/');
    $pageTitle = trim($payload['page_title'] ?? '');
    $referrer = trim($payload['referrer'] ?? $_SERVER['HTTP_REFERER'] ?? '');
    $refDomain = extractReferrerDomain($referrer);
    $screenRes = trim($payload['screen_res'] ?? '');
    $language = trim($payload['language'] ?? ($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? ''));
    $eventType = trim($payload['event_type'] ?? 'pageview');
    $eventData = !empty($payload['event_data']) ? (is_string($payload['event_data']) ? $payload['event_data'] : json_encode($payload['event_data'], JSON_UNESCAPED_UNICODE)) : null;
    $durationSec = intval($payload['duration_sec'] ?? 0);
    $isAdmin = (!empty($_SESSION['admin_user']) || !empty($payload['is_admin'])) ? 1 : 0;

    $uaInfo = parseUserAgentInfo($userAgent);
    $deviceType = $payload['device_type'] ?? $uaInfo['device'];
    $os = $payload['os'] ?? $uaInfo['os'];
    $browser = $payload['browser'] ?? $uaInfo['browser'];

    $geo = getIpGeoLocation($conn, $ip);

    $visitDate = date('Y-m-d');

    try {
        $stmt = $conn->prepare("INSERT INTO visitor_logs 
            (visitor_id, session_id, ip_address, page_url, page_title, referrer, referrer_domain, device_type, os, browser, country, country_code, region, city, screen_res, language, event_type, event_data, duration_sec, is_admin, is_bot, visit_date, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([
            $visitorId,
            $sessionId,
            $ip,
            $pageUrl,
            $pageTitle,
            $referrer,
            $refDomain,
            $deviceType,
            $os,
            $browser,
            $geo['country'],
            $geo['country_code'],
            $geo['region'],
            $geo['city'],
            $screenRes,
            $language,
            $eventType,
            $eventData,
            $durationSec,
            $isAdmin,
            $isBot,
            $visitDate
        ]);
        $insertId = $conn->lastInsertId();

        sendJsonResponse([
            'status' => 'success',
            'log_id' => $insertId,
            'visitor_id' => $visitorId,
            'session_id' => $sessionId
        ]);
    } catch (Exception $e) {
        sendJsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}

// ==========================================
// 2. PUBLIC ACTION: UPDATE DURATION
// ==========================================
if ($action === 'update_duration') {
    $raw = file_get_contents('php://input');
    $payload = json_decode($raw, true) ?: $_POST;

    $logId = intval($payload['log_id'] ?? 0);
    $duration = intval($payload['duration_sec'] ?? 0);
    $visitorId = trim($payload['visitor_id'] ?? '');

    if ($duration > 0) {
        try {
            if ($logId > 0) {
                $stmt = $conn->prepare("UPDATE visitor_logs SET duration_sec = GREATEST(duration_sec, ?) WHERE id = ?");
                $stmt->execute([$duration, $logId]);
            } elseif (!empty($visitorId)) {
                $stmt = $conn->prepare("UPDATE visitor_logs SET duration_sec = GREATEST(duration_sec, ?) WHERE visitor_id = ? AND visit_date = CURDATE() ORDER BY id DESC LIMIT 1");
                $stmt->execute([$duration, $visitorId]);
            }
        } catch (Exception $e) {}
    }
    sendJsonResponse(['status' => 'success']);
}

// ==========================================
// 3. ADMIN ACTION: SUMMARY & DAILY ANALYTICS
// ==========================================
if ($action === 'summary') {
    requireAdminAuth();

    $period = $_GET['period'] ?? '7days';
    $customStart = $_GET['start_date'] ?? '';
    $customEnd = $_GET['end_date'] ?? '';
    $excludeAdmin = isset($_GET['exclude_admin']) ? intval($_GET['exclude_admin']) : 1;
    $excludeBots = isset($_GET['exclude_bots']) ? intval($_GET['exclude_bots']) : 1;

    $today = date('Y-m-d');
    $startDate = $today;
    $endDate = $today;

    switch ($period) {
        case 'today':
            $startDate = $today;
            $endDate = $today;
            break;
        case 'yesterday':
            $startDate = date('Y-m-d', strtotime('-1 day'));
            $endDate = $startDate;
            break;
        case '7days':
            $startDate = date('Y-m-d', strtotime('-6 days'));
            $endDate = $today;
            break;
        case '14days':
            $startDate = date('Y-m-d', strtotime('-13 days'));
            $endDate = $today;
            break;
        case '30days':
            $startDate = date('Y-m-d', strtotime('-29 days'));
            $endDate = $today;
            break;
        case 'this_month':
            $startDate = date('Y-m-01');
            $endDate = $today;
            break;
        case 'custom':
            if (!empty($customStart)) $startDate = $customStart;
            if (!empty($customEnd)) $endDate = $customEnd;
            break;
        case 'all':
            $startDate = '2020-01-01';
            $endDate = $today;
            break;
    }

    $where = ["visit_date BETWEEN :start_date AND :end_date"];
    $params = [':start_date' => $startDate, ':end_date' => $endDate];

    if ($excludeAdmin) {
        $where[] = "is_admin = 0";
    }
    if ($excludeBots) {
        $where[] = "is_bot = 0";
    }
    $whereSql = implode(" AND ", $where);

    // 1. Overall Key Metrics (KPIs)
    $kpiStmt = $conn->prepare("SELECT 
        COUNT(*) as total_pageviews,
        COUNT(DISTINCT visitor_id) as unique_visitors,
        COUNT(DISTINCT ip_address) as unique_ips,
        COALESCE(ROUND(AVG(NULLIF(duration_sec, 0)), 0), 0) as avg_duration_sec,
        COUNT(DISTINCT session_id) as total_sessions
        FROM visitor_logs WHERE $whereSql");
    $kpiStmt->execute($params);
    $kpis = $kpiStmt->fetch(PDO::FETCH_ASSOC) ?: [
        'total_pageviews' => 0,
        'unique_visitors' => 0,
        'unique_ips' => 0,
        'avg_duration_sec' => 0,
        'total_sessions' => 0
    ];

    // Single page sessions for bounce rate estimation
    $bounceStmt = $conn->prepare("SELECT COUNT(*) FROM (
        SELECT session_id, COUNT(*) as cnt FROM visitor_logs WHERE $whereSql GROUP BY session_id HAVING cnt = 1
    ) as single_pages");
    $bounceStmt->execute($params);
    $singlePageSessions = intval($bounceStmt->fetchColumn() ?: 0);
    $totalSessions = intval($kpis['total_sessions']) ?: 1;
    $bounceRate = round(($singlePageSessions / $totalSessions) * 100, 1);

    // New vs Returning Visitors
    $returningStmt = $conn->prepare("SELECT COUNT(DISTINCT visitor_id) FROM visitor_logs 
        WHERE visitor_id IN (
            SELECT visitor_id FROM visitor_logs GROUP BY visitor_id HAVING COUNT(*) > 1
        ) AND $whereSql");
    $returningStmt->execute($params);
    $returningVisitors = intval($returningStmt->fetchColumn() ?: 0);
    $uniqueVisitors = intval($kpis['unique_visitors']) ?: 0;
    $newVisitors = max(0, $uniqueVisitors - $returningVisitors);

    // 2. Daily Trends (Grouped by visit_date)
    $dailyStmt = $conn->prepare("SELECT 
        visit_date,
        COUNT(*) as pageviews,
        COUNT(DISTINCT visitor_id) as unique_visitors,
        COUNT(DISTINCT ip_address) as unique_ips
        FROM visitor_logs WHERE $whereSql
        GROUP BY visit_date ORDER BY visit_date ASC");
    $dailyStmt->execute($params);
    $dailyRows = $dailyStmt->fetchAll(PDO::FETCH_ASSOC);

    $dailyMap = [];
    foreach ($dailyRows as $r) {
        $dailyMap[$r['visit_date']] = $r;
    }

    // Fill all dates in range
    $dailyTrends = [];
    $curr = strtotime($startDate);
    $endTs = strtotime($endDate);
    while ($curr <= $endTs) {
        $dStr = date('Y-m-d', $curr);
        if (isset($dailyMap[$dStr])) {
            $dailyTrends[] = [
                'date' => $dStr,
                'display_date' => date('m/d (D)', $curr),
                'pageviews' => intval($dailyMap[$dStr]['pageviews']),
                'unique_visitors' => intval($dailyMap[$dStr]['unique_visitors']),
                'unique_ips' => intval($dailyMap[$dStr]['unique_ips'])
            ];
        } else {
            $dailyTrends[] = [
                'date' => $dStr,
                'display_date' => date('m/d (D)', $curr),
                'pageviews' => 0,
                'unique_visitors' => 0,
                'unique_ips' => 0
            ];
        }
        $curr = strtotime('+1 day', $curr);
    }

    // 3. Hourly Trends (24 hours)
    $hourlyStmt = $conn->prepare("SELECT 
        HOUR(created_at) as hour_num,
        COUNT(*) as pageviews,
        COUNT(DISTINCT visitor_id) as unique_visitors
        FROM visitor_logs WHERE $whereSql
        GROUP BY hour_num ORDER BY hour_num ASC");
    $hourlyStmt->execute($params);
    $hourlyRows = $hourlyStmt->fetchAll(PDO::FETCH_ASSOC);

    $hourlyMap = [];
    foreach ($hourlyRows as $hr) {
        $hourlyMap[intval($hr['hour_num'])] = $hr;
    }
    $hourlyTrends = [];
    for ($h = 0; $h < 24; $h++) {
        $hourlyTrends[] = [
            'hour' => sprintf('%02d:00', $h),
            'pageviews' => intval($hourlyMap[$h]['pageviews'] ?? 0),
            'unique_visitors' => intval($hourlyMap[$h]['unique_visitors'] ?? 0)
        ];
    }

    // 4. Top Visited Pages
    $pagesStmt = $conn->prepare("SELECT 
        page_url,
        COALESCE(NULLIF(MAX(page_title), ''), page_url) as page_title,
        COUNT(*) as views,
        COUNT(DISTINCT visitor_id) as unique_visitors,
        ROUND(AVG(NULLIF(duration_sec, 0)), 0) as avg_duration
        FROM visitor_logs WHERE $whereSql
        GROUP BY page_url ORDER BY views DESC LIMIT 15");
    $pagesStmt->execute($params);
    $topPages = $pagesStmt->fetchAll(PDO::FETCH_ASSOC);

    // 5. Top Traffic Sources / Referrers
    $refStmt = $conn->prepare("SELECT 
        CASE 
            WHEN referrer_domain = '' OR referrer_domain IS NULL THEN 'Direct / Bookmark'
            WHEN referrer_domain LIKE '%google%' THEN 'Google Search'
            WHEN referrer_domain LIKE '%naver%' THEN 'Naver'
            WHEN referrer_domain LIKE '%instagram%' THEN 'Instagram'
            WHEN referrer_domain LIKE '%facebook%' THEN 'Facebook'
            WHEN referrer_domain LIKE '%kakao%' THEN 'KakaoTalk'
            WHEN referrer_domain LIKE '%youtube%' THEN 'YouTube'
            ELSE referrer_domain
        END as source_name,
        COUNT(*) as count
        FROM visitor_logs WHERE $whereSql
        GROUP BY source_name ORDER BY count DESC LIMIT 10");
    $refStmt->execute($params);
    $topReferrers = $refStmt->fetchAll(PDO::FETCH_ASSOC);

    // 6. Device Breakdown
    $deviceStmt = $conn->prepare("SELECT 
        LOWER(device_type) as device,
        COUNT(*) as count
        FROM visitor_logs WHERE $whereSql
        GROUP BY device ORDER BY count DESC");
    $deviceStmt->execute($params);
    $devices = $deviceStmt->fetchAll(PDO::FETCH_ASSOC);

    // 7. Browser Breakdown
    $browserStmt = $conn->prepare("SELECT 
        browser,
        COUNT(*) as count
        FROM visitor_logs WHERE $whereSql
        GROUP BY browser ORDER BY count DESC LIMIT 6");
    $browserStmt->execute($params);
    $browsers = $browserStmt->fetchAll(PDO::FETCH_ASSOC);

    // 8. OS Breakdown
    $osStmt = $conn->prepare("SELECT 
        os,
        COUNT(*) as count
        FROM visitor_logs WHERE $whereSql
        GROUP BY os ORDER BY count DESC LIMIT 6");
    $osStmt->execute($params);
    $operatingSystems = $osStmt->fetchAll(PDO::FETCH_ASSOC);

    // 9. Geographic Countries & Cities
    $geoStmt = $conn->prepare("SELECT 
        country,
        country_code,
        city,
        COUNT(*) as count,
        COUNT(DISTINCT visitor_id) as unique_visitors
        FROM visitor_logs WHERE $whereSql
        GROUP BY country, country_code, city ORDER BY count DESC LIMIT 12");
    $geoStmt->execute($params);
    $locations = $geoStmt->fetchAll(PDO::FETCH_ASSOC);

    // 10. Key User Events & Conversions
    $eventStmt = $conn->prepare("SELECT 
        event_type,
        COUNT(*) as count
        FROM visitor_logs WHERE event_type != 'pageview' AND $whereSql
        GROUP BY event_type ORDER BY count DESC");
    $eventStmt->execute($params);
    $events = $eventStmt->fetchAll(PDO::FETCH_ASSOC);

    // Today's Real-time Quick Count
    $todayStmt = $conn->prepare("SELECT 
        COUNT(*) as today_views,
        COUNT(DISTINCT visitor_id) as today_visitors
        FROM visitor_logs WHERE visit_date = CURDATE()" . ($excludeAdmin ? " AND is_admin = 0" : "") . ($excludeBots ? " AND is_bot = 0" : ""));
    $todayStmt->execute();
    $todayQuick = $todayStmt->fetch(PDO::FETCH_ASSOC) ?: ['today_views' => 0, 'today_visitors' => 0];

    sendJsonResponse([
        'status' => 'success',
        'period' => $period,
        'start_date' => $startDate,
        'end_date' => $endDate,
        'kpis' => [
            'total_pageviews' => intval($kpis['total_pageviews']),
            'unique_visitors' => intval($kpis['unique_visitors']),
            'unique_ips' => intval($kpis['unique_ips']),
            'avg_duration_sec' => intval($kpis['avg_duration_sec']),
            'bounce_rate' => $bounceRate,
            'new_visitors' => $newVisitors,
            'returning_visitors' => $returningVisitors,
            'today_views' => intval($todayQuick['today_views']),
            'today_visitors' => intval($todayQuick['today_visitors'])
        ],
        'daily_trends' => $dailyTrends,
        'hourly_trends' => $hourlyTrends,
        'top_pages' => $topPages,
        'top_referrers' => $topReferrers,
        'devices' => $devices,
        'browsers' => $browsers,
        'operating_systems' => $operatingSystems,
        'locations' => $locations,
        'events' => $events
    ]);
}

// ==========================================
// 4. ADMIN ACTION: PAGINATED VISITOR LOGS
// ==========================================
if ($action === 'logs') {
    requireAdminAuth();

    $page = max(1, intval($_GET['page'] ?? 1));
    $limit = min(100, max(10, intval($_GET['limit'] ?? 30)));
    $offset = ($page - 1) * $limit;

    $date = trim($_GET['date'] ?? '');
    $search = trim($_GET['search'] ?? '');
    $device = trim($_GET['device'] ?? 'all');
    $event = trim($_GET['event'] ?? 'all');
    $excludeAdmin = isset($_GET['exclude_admin']) ? intval($_GET['exclude_admin']) : 1;
    $excludeBots = isset($_GET['exclude_bots']) ? intval($_GET['exclude_bots']) : 1;

    $where = ["1=1"];
    $params = [];

    if (!empty($date)) {
        $where[] = "visit_date = :date";
        $params[':date'] = $date;
    }
    if ($excludeAdmin) {
        $where[] = "is_admin = 0";
    }
    if ($excludeBots) {
        $where[] = "is_bot = 0";
    }
    if ($device !== 'all' && !empty($device)) {
        $where[] = "device_type = :device";
        $params[':device'] = $device;
    }
    if ($event !== 'all' && !empty($event)) {
        $where[] = "event_type = :event";
        $params[':event'] = $event;
    }
    if (!empty($search)) {
        $where[] = "(ip_address LIKE :search OR page_url LIKE :search OR page_title LIKE :search OR country LIKE :search OR city LIKE :search OR referrer LIKE :search OR browser LIKE :search)";
        $params[':search'] = "%$search%";
    }

    $whereSql = implode(" AND ", $where);

    $countStmt = $conn->prepare("SELECT COUNT(*) FROM visitor_logs WHERE $whereSql");
    $countStmt->execute($params);
    $totalLogs = intval($countStmt->fetchColumn() ?: 0);

    $logsStmt = $conn->prepare("SELECT * FROM visitor_logs WHERE $whereSql ORDER BY id DESC LIMIT $limit OFFSET $offset");
    $logsStmt->execute($params);
    $logs = $logsStmt->fetchAll(PDO::FETCH_ASSOC);

    sendJsonResponse([
        'status' => 'success',
        'total' => $totalLogs,
        'page' => $page,
        'pages' => ceil($totalLogs / $limit),
        'limit' => $limit,
        'logs' => $logs
    ]);
}

// ==========================================
// 5. ADMIN ACTION: SYNC / IMPORT APACHE LOGS
// ==========================================
if ($action === 'sync_apache_logs') {
    requireAdminAuth();

    $logFiles = [
        '/var/log/apache2/davidhairshop_access.log',
        '/var/log/apache2/davidhairshop_access.log.1'
    ];

    $imported = 0;
    $scanned = 0;
    $skipped = 0;

    // Pattern for Combined Log Format:
    // 124.170.32.125 - - [03/Nov/2026:00:13:44 +0000] "GET /videos/windy.mp4 HTTP/1.1" 206 329267 "https://davidhairshop.com/" "Mozilla/5.0..."
    $regex = '/^(\S+)\s+\S+\s+\S+\s+\[([^\]]+)\]\s+"([A-Z]+)\s+([^"\s]+)\s+HTTP\/[0-9.]+"\s+([0-9]{3})\s+(\S+)\s+"([^"]*)"\s+"([^"]*)"/';

    $staticExtensions = ['mp4', 'webm', 'jpg', 'jpeg', 'png', 'webp', 'svg', 'gif', 'css', 'js', 'woff', 'woff2', 'ttf', 'eot', 'ico', 'map', 'json', 'txt', 'xml'];
    $extPattern = '/\.(' . implode('|', $staticExtensions) . ')(\?.*)?$/i';

    $insertStmt = $conn->prepare("INSERT INTO visitor_logs 
        (visitor_id, session_id, ip_address, page_url, page_title, referrer, referrer_domain, device_type, os, browser, country, country_code, region, city, event_type, duration_sec, is_admin, is_bot, visit_date, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pageview', 15, 0, ?, ?, ?)");

    $checkStmt = $conn->prepare("SELECT id FROM visitor_logs WHERE ip_address = ? AND page_url = ? AND created_at = ? LIMIT 1");

    foreach ($logFiles as $logFile) {
        if (!file_exists($logFile) || !is_readable($logFile)) continue;

        // Read last 3000 lines from file
        $lines = [];
        $fp = @popen("tail -n 3500 " . escapeshellarg($logFile), "r");
        if ($fp) {
            while (!feof($fp)) {
                $line = fgets($fp);
                if ($line !== false && trim($line) !== '') {
                    $lines[] = trim($line);
                }
            }
            pclose($fp);
        }

        foreach ($lines as $line) {
            $scanned++;
            if (!preg_match($regex, $line, $matches)) {
                continue;
            }

            $ip = $matches[1];
            $timeRaw = $matches[2]; // e.g. 03/Nov/2026:00:13:44 +0000
            $method = $matches[3];
            $url = $matches[4];
            $statusCode = intval($matches[5]);
            $referrer = $matches[7];
            $userAgent = $matches[8];

            if ($method !== 'GET') continue;
            if ($statusCode >= 400 && $statusCode != 404) continue;

            // Skip static media files
            if (preg_match($extPattern, $url)) continue;

            // Skip stock_manager / admin / backend API internal hits from sync
            if (strpos($url, 'admin_stock_api.php') !== false || strpos($url, 'api_') !== false) continue;

            // Convert Apache time to MySQL datetime
            $timestamp = strtotime($timeRaw);
            if (!$timestamp) continue;
            $dt = date('Y-m-d H:i:s', $timestamp);
            $visitDate = date('Y-m-d', $timestamp);

            // Check if already logged
            $checkStmt->execute([$ip, $url, $dt]);
            if ($checkStmt->fetchColumn()) {
                $skipped++;
                continue;
            }

            $isBot = isBotUserAgent($userAgent) ? 1 : 0;
            $uaInfo = parseUserAgentInfo($userAgent);
            $refDomain = extractReferrerDomain($referrer);
            $geo = getIpGeoLocation($conn, $ip);

            // Synthesize friendly page title
            $title = 'David Hair';
            if (strpos($url, 'calculator') !== false) $title = 'Custom Hair Calculator | David Hair';
            elseif (strpos($url, 'gallery') !== false) $title = 'Before & After Gallery | David Hair';
            elseif (strpos($url, 'comparison') !== false) $title = 'Hair System Comparison | David Hair';
            elseif (strpos($url, 'diag') !== false) $title = 'Hairloss Diagnosis Quiz | David Hair';
            elseif ($url === '/' || strpos($url, 'index') !== false) $title = 'David Hair - Custom Toupee & Hair Systems';

            $visitorId = 'dh_' . substr(md5($ip . ($userAgent ?? '') . $visitDate), 0, 16);
            $sessionId = 'sess_' . substr(md5($ip . $visitDate . date('H', $timestamp)), 0, 16);

            $insertStmt->execute([
                $visitorId,
                $sessionId,
                $ip,
                $url,
                $title,
                $referrer === '-' ? '' : $referrer,
                $refDomain,
                $uaInfo['device'],
                $uaInfo['os'],
                $uaInfo['browser'],
                $geo['country'],
                $geo['country_code'],
                $geo['region'],
                $geo['city'],
                $isBot,
                $visitDate,
                $dt
            ]);
            $imported++;
        }
    }

    sendJsonResponse([
        'status' => 'success',
        'scanned' => $scanned,
        'imported' => $imported,
        'skipped' => $skipped,
        'message' => "Scanned {$scanned} log lines and successfully imported {$imported} visitor visits."
    ]);
}

// ==========================================
// 6. ADMIN ACTION: EXPORT CSV
// ==========================================
if ($action === 'export_csv') {
    requireAdminAuth();

    $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
    $endDate = $_GET['end_date'] ?? date('Y-m-d');
    $excludeAdmin = isset($_GET['exclude_admin']) ? intval($_GET['exclude_admin']) : 1;
    $excludeBots = isset($_GET['exclude_bots']) ? intval($_GET['exclude_bots']) : 1;

    $where = ["visit_date BETWEEN :start_date AND :end_date"];
    $params = [':start_date' => $startDate, ':end_date' => $endDate];

    if ($excludeAdmin) $where[] = "is_admin = 0";
    if ($excludeBots) $where[] = "is_bot = 0";
    $whereSql = implode(" AND ", $where);

    $stmt = $conn->prepare("SELECT id, created_at, visit_date, ip_address, country, city, device_type, os, browser, page_url, page_title, referrer, event_type, duration_sec 
        FROM visitor_logs WHERE $whereSql ORDER BY id DESC LIMIT 10000");
    $stmt->execute($params);

    if (ob_get_length()) ob_clean();

    $filename = "davidhair_visitors_{$startDate}_{$endDate}.csv";
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: no-cache, no-store, must-revalidate');

    $out = fopen('php://output', 'w');
    // UTF-8 BOM for Microsoft Excel
    fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

    fputcsv($out, ['ID', 'DateTime', 'Date', 'IP Address', 'Country', 'City', 'Device', 'OS', 'Browser', 'Page URL', 'Page Title', 'Referrer', 'Event', 'Duration (sec)']);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($out, [
            $row['id'],
            $row['created_at'],
            $row['visit_date'],
            $row['ip_address'],
            $row['country'],
            $row['city'],
            $row['device_type'],
            $row['os'],
            $row['browser'],
            $row['page_url'],
            $row['page_title'],
            $row['referrer'],
            $row['event_type'],
            $row['duration_sec']
        ]);
    }
    fclose($out);
    exit;
}

// ==========================================
// 7. ADMIN ACTION: CLEAR LOGS
// ==========================================
if ($action === 'clear_logs') {
    requireAdminAuth();

    $mode = $_POST['mode'] ?? 'older_than_90_days';
    $deleted = 0;

    if ($mode === 'older_than_30_days') {
        $stmt = $conn->prepare("DELETE FROM visitor_logs WHERE visit_date < DATE_SUB(CURDATE(), INTERVAL 30 DAY)");
        $stmt->execute();
        $deleted = $stmt->rowCount();
    } elseif ($mode === 'older_than_90_days') {
        $stmt = $conn->prepare("DELETE FROM visitor_logs WHERE visit_date < DATE_SUB(CURDATE(), INTERVAL 90 DAY)");
        $stmt->execute();
        $deleted = $stmt->rowCount();
    } elseif ($mode === 'only_bots') {
        $stmt = $conn->prepare("DELETE FROM visitor_logs WHERE is_bot = 1");
        $stmt->execute();
        $deleted = $stmt->rowCount();
    } elseif ($mode === 'all') {
        $stmt = $conn->prepare("TRUNCATE TABLE visitor_logs");
        $stmt->execute();
        $deleted = 'All';
    }

    sendJsonResponse(['status' => 'success', 'deleted' => $deleted, 'message' => "Visitor logs cleared successfully ({$deleted} rows affected)."]);
}

// ==========================================
// 8. ADMIN ACTION: SEED REALISTIC DEMO LOGS
// ==========================================
if ($action === 'seed_demo_logs') {
    requireAdminAuth();

    $pages = [
        ['url' => '/', 'title' => 'David Hair - Custom Toupee & Hair Systems Melbourne'],
        ['url' => '/calculator.html', 'title' => 'Instant Custom Hair Quote Calculator | David Hair'],
        ['url' => '/gallery.html', 'title' => 'Real Before & After Client Transformations | David Hair'],
        ['url' => '/comparison.html', 'title' => 'Custom Toupee vs Ready-to-Wear Comparison | David Hair'],
        ['url' => '/diag.html', 'title' => 'Interactive Hairloss Pattern Diagnosis | David Hair']
    ];

    $referrers = [
        'https://www.google.com.au/search?q=men+hair+system+melbourne',
        'https://www.google.com/search?q=custom+toupee+australia',
        'https://m.naver.com/search.naver?query=호주+멜버른+남성가발',
        'https://www.instagram.com/p/DavidHairMelbourne/',
        'https://www.facebook.com/',
        '', // Direct
        ''  // Direct
    ];

    $devices = [
        ['device' => 'mobile', 'os' => 'iOS', 'browser' => 'Safari', 'res' => '393x852'],
        ['device' => 'mobile', 'os' => 'Android', 'browser' => 'Samsung Internet', 'res' => '412x915'],
        ['device' => 'mobile', 'os' => 'iOS', 'browser' => 'Chrome', 'res' => '390x844'],
        ['device' => 'desktop', 'os' => 'Windows 10/11', 'browser' => 'Chrome', 'res' => '1920x1080'],
        ['device' => 'desktop', 'os' => 'macOS', 'browser' => 'Safari', 'res' => '1440x900'],
        ['device' => 'tablet', 'os' => 'iOS', 'browser' => 'Safari', 'res' => '820x1180']
    ];

    $locations = [
        ['country' => 'Australia', 'code' => 'AU', 'region' => 'Victoria', 'city' => 'Melbourne', 'ip' => '124.170.32.'],
        ['country' => 'Australia', 'code' => 'AU', 'region' => 'Victoria', 'city' => 'Springvale', 'ip' => '120.151.45.'],
        ['country' => 'Australia', 'code' => 'AU', 'region' => 'New South Wales', 'city' => 'Sydney', 'ip' => '110.174.12.'],
        ['country' => 'Australia', 'code' => 'AU', 'region' => 'Queensland', 'city' => 'Brisbane', 'ip' => '101.188.76.'],
        ['country' => 'South Korea', 'code' => 'KR', 'region' => 'Seoul', 'city' => 'Gangnam-gu', 'ip' => '211.234.118.'],
        ['country' => 'United States', 'code' => 'US', 'region' => 'California', 'city' => 'Los Angeles', 'ip' => '104.28.19.']
    ];

    $events = ['pageview', 'pageview', 'pageview', 'quote_calc', 'booking_click', 'whatsapp_click'];

    $insStmt = $conn->prepare("INSERT INTO visitor_logs 
        (visitor_id, session_id, ip_address, page_url, page_title, referrer, referrer_domain, device_type, os, browser, country, country_code, region, city, screen_res, language, event_type, duration_sec, is_admin, is_bot, visit_date, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 0, ?, ?)");

    $seededCount = 0;
    // Generate for past 14 days
    for ($i = 13; $i >= 0; $i--) {
        $dayDate = date('Y-m-d', strtotime("-{$i} days"));
        // Random 15-40 visits per day
        $dailyCount = rand(18, 38);
        for ($k = 0; $k < $dailyCount; $k++) {
            $hour = rand(8, 23);
            $min = rand(0, 59);
            $sec = rand(0, 59);
            $createdAt = sprintf('%s %02d:%02d:%02d', $dayDate, $hour, $min, $sec);

            $loc = $locations[array_rand($locations)];
            $ip = $loc['ip'] . rand(10, 250);
            $dev = $devices[array_rand($devices)];
            $pg = $pages[array_rand($pages)];
            $ref = $referrers[array_rand($referrers)];
            $refDomain = extractReferrerDomain($ref);
            $ev = $events[array_rand($events)];
            $dur = rand(15, 240);

            $vid = 'dh_' . substr(md5($ip . $dayDate . ($k % 4)), 0, 16);
            $sid = 'sess_' . substr(md5($ip . $createdAt), 0, 16);

            $insStmt->execute([
                $vid,
                $sid,
                $ip,
                $pg['url'],
                $pg['title'],
                $ref,
                $refDomain,
                $dev['device'],
                $dev['os'],
                $dev['browser'],
                $loc['country'],
                $loc['code'],
                $loc['region'],
                $loc['city'],
                $dev['res'],
                $loc['code'] === 'KR' ? 'ko-KR' : 'en-AU',
                $ev,
                $dur,
                $dayDate,
                $createdAt
            ]);
            $seededCount++;
        }
    }

    sendJsonResponse([
        'status' => 'success',
        'seeded' => $seededCount,
        'message' => "Successfully generated {$seededCount} realistic sample visits across the last 14 days."
    ]);
}

sendJsonResponse(['status' => 'error', 'message' => 'Invalid action.'], 400);
