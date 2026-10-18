<?php
// db_test.php - 데이터베이스 연결 확인 페이지 (관리자 인증 필요)
session_start();
if (empty($_SESSION['admin_user'])) {
    http_response_code(403);
    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>403 Forbidden</title></head><body style="background:#0f172a;color:#f8fafc;font-family:sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;"><div style="text-align:center;padding:24px;background:#1e293b;border-radius:12px;border:1px solid #334155;"><h2 style="margin-bottom:12px;color:#f87171;">403 Forbidden</h2><p style="color:#94a3b8;margin-bottom:20px;">Access to database diagnostic tools is restricted to authenticated administrators.</p><a href="/stock_manager.php" style="color:#14b8a6;text-decoration:none;font-weight:bold;">Go to Admin Login &rarr;</a></div></body></html>';
    exit;
}

require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>David Hair - Database Connection Test</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pretendard:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --text-main: #f8fafc;
            --text-sub: #94a3b8;
            --success-color: #10b981;
            --success-bg: rgba(16, 185, 129, 0.1);
            --border-color: #334155;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Pretendard', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 36px;
            max-width: 540px;
            width: 100%;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
        }

        .header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .status-badge {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background-color: var(--success-color);
            box-shadow: 0 0 12px var(--success-color);
        }

        h1 {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .card {
            background-color: var(--success-bg);
            border: 1px solid var(--success-color);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .card-title {
            color: var(--success-color);
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 8px;
        }

        .info-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 14px;
            background-color: rgba(255, 255, 255, 0.03);
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .info-label {
            color: var(--text-sub);
            font-size: 0.9rem;
        }

        .info-value {
            font-weight: 600;
            font-size: 0.95rem;
            font-family: monospace;
        }

        .footer {
            margin-top: 24px;
            text-align: center;
            color: var(--text-sub);
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="status-badge"></div>
            <h1>데이터베이스 연결 성공</h1>
        </div>

        <div class="card">
            <div class="card-title">PDO Connection Established</div>
            <p style="color: #cbd5e1; font-size: 0.95rem;">MySQL 데이터베이스와 성공적으로 연결되었습니다.</p>
        </div>

        <div class="info-group">
            <div class="info-row">
                <span class="info-label">데이터베이스명</span>
                <span class="info-value"><?php echo htmlspecialchars($dbname); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">서버 주소</span>
                <span class="info-value"><?php echo htmlspecialchars($servername); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">접속 계정</span>
                <span class="info-value"><?php echo htmlspecialchars($username); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">MySQL 버전</span>
                <span class="info-value">
                    <?php 
                        try {
                            echo $conn->getAttribute(PDO::ATTR_SERVER_VERSION);
                        } catch(Exception $e) {
                            echo "Unknown";
                        }
                    ?>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">인코딩</span>
                <span class="info-value">UTF-8</span>
            </div>
        </div>

        <div class="footer">
            David Hair System &bull; Remote Server Sync Active
        </div>
    </div>
</body>
</html>
