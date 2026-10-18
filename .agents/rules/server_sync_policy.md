# Server Sync & Git Policy

1. **Auto Web Server Update (Fast Direct Deploy) - MANDATORY**:
   - 코드나 파일 추가, 수정 또는 작업 완료 시 **반드시 매번 자동으로 `npm run deploy`** 를 실행하여 운영 서버(`/var/www/davidhair`)로 즉시 동기화합니다.
   - Windows 및 Mac 환경 모두 지원하는 `npm run deploy` (또는 `node scripts/deploy_runner.js`)를 실행합니다.
   - GitHub를 거치지 않고 직접 동기화하여 속도를 극대화합니다.
   - 서버의 `uploads/` 디렉토리와 `.git`은 절대 훼손되지 않도록 보호됩니다.

2. **Git Commit/Push Policy (Manual Only)**:
   - **절대로 `git commit`이나 `git push`를 자동으로 실행하지 마십시오.**
   - Git은 일일 백업(하루 1~2회) 또는 사용자가 명시적으로 원할 때만 수동으로 실행됩니다. (`./scripts/git_push.sh`, `./scripts/git_pull.sh`)

3. **Live Verification URL (MANDATORY)**:
   - 사용자와 대화하는 모든 응답의 마지막에는 사용자가 결과를 브라우저에서 바로 클릭하여 확인할 수 있도록 라이브 URL을 항상 안내해야 합니다.
   - 기본 URL: `https://davidhair.atozhair.shop`
   - 작업한 특정 페이지가 있다면 해당 URL도 함께 제공 (예: `https://davidhair.atozhair.shop/stock_manager.php`, `https://davidhair.atozhair.shop/index.html`)
