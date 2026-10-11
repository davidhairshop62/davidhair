# 🏠 David Hair - 집(Windows) 환경 서버 배포 및 Git 작업 가이드

본 문서는 **집(Windows PC / PowerShell / CMD)** 환경에서 David Hair 프로젝트의 실시간 서버 반영과 Git 관리를 원활하게 진행할 수 있도록 정리한 가이드입니다.

> 💡 **참고**: 회사의 Mac 환경에서는 `DEPLOY_AND_GIT_GUIDE.md`를 참고하시고, **집의 윈도우 PC에서는 본 문서(`HOME_DEPLOY_AND_GIT_GUIDE.md`)**를 사용하시면 됩니다.

---

## 📌 1. 윈도우 환경 핵심 요약 및 에러 해결

* **발생했던 문제**: 윈도우 터미널(CMD/PowerShell)에서 `./scripts/watch.sh` 실행 시 `'.'은(는) 내부 또는 외부 명령이 아닙니다` 오류 발생 (리눅스/맥 전용 쉘스크립트 경로 문제).
* **해결 및 개선**:
  1. **크로스 플랫폼 Node 러너 적용**: `npm run watch` 및 `npm run deploy`가 윈도우와 맥 모두에서 100% 정상 작동하도록 설정 완료.
  2. **윈도우 전용 배치(.bat) 파일 제공**: `scripts\watch.bat`, `scripts\deploy.bat` 지원.
  3. **초고속 SFTP 동기화**: 윈도우에 rsync가 없어도 Node.js SSH2(fastPut)를 통해 **저장(Ctrl + S) 즉시 0.5초 만에 서버로 자동 전송**.

---

## 💼 2. 집(Windows) 일상 작업 흐름 (Daily Workflow)

### ① 작업 시작 시 (회사에서 작업한 최신 코드 다운로드)
퇴근 전 회사(Mac)에서 올린 최신 코드를 집 윈도우 PC로 가져옵니다:
```powershell
npm run git:pull
```
*(또는 `scripts\git_pull.bat` 실행 / `git pull origin main`)*

---

### ② 작업 진행 중 (서버 즉시 반영)

#### 🌟 [가장 추천] 실시간 자동 동기화 모드 (저장 즉시 서버 반영)
VS Code 터미널 또는 PowerShell 창 하나를 열고 아래 명령어를 입력해 둡니다:
```powershell
npm run watch
```
*(또는 `scripts\watch.bat`)*

* **작동 방식**:
  - `stock_manager.php`, `index.html` 등 코드를 수정하고 **Ctrl + S (저장)**를 누르면 **0.5초 안에 운영 서버로 자동 업로드**됩니다.
  - 브라우저를 새로고침(F5)하기만 하면 즉시 반영 결과를 확인할 수 있습니다.
* **종료 방법**: 터미널에서 `Ctrl + C` 누르면 종료됩니다.

#### 📦 원할 때 한 번씩 전체 배포하기
파일들을 한 번에 서버에 전체 동기화하고 싶을 때:
```powershell
npm run deploy
```
*(또는 `scripts\deploy.bat`)*

---

### ③ 작업 마무리 시 (회사 출근 전 GitHub 백업 업로드)
오늘 작업한 내용을 GitHub에 올려두면 다음 날 회사 Mac에서 그대로 이어서 작업할 수 있습니다:
```powershell
npm run git:push
```
*(또는 커밋 메시지와 함께 실행 시: `scripts\git_push.bat "오늘 수정한 내용"`)*

---

## 🛠️ 3. 윈도우(Windows) 명령어 치트시트

| 실행 명령어 | 배치 파일 단축키 | 설명 |
| :--- | :--- | :--- |
| **`npm run watch`** | `scripts\watch.bat` | **[강추]** 저장(Ctrl+S) 시 실시간으로 서버에 0.5초 즉시 자동 반영 |
| **`npm run deploy`** | `scripts\deploy.bat` | 로컬 프로젝트 파일들을 운영 서버로 일괄 배포 |
| **`npm run git:pull`** | `scripts\git_pull.bat` | GitHub(origin main)에서 최신 코드 내려받기 |
| **`npm run git:push`** | `scripts\git_push.bat` | 오늘 작업한 내용을 GitHub(origin main)에 백업 업로드 |
| **`ssh root@187.127.96.101`** | - | 윈도우 터미널에서 운영 서버로 직접 SSH 원격 접속 |

---

## 🌐 4. 결과 확인 및 테스트 링크

수정한 코드가 실시간으로 반영되는 서버 주소입니다:

* 🏠 **메인 사이트 (예약 & 스타일)**: [https://davidhair.atozhair.shop](https://davidhair.atozhair.shop)
* 📦 **재고 관리 시스템 (대규모 업데이트)**: [https://davidhair.atozhair.shop/stock_manager.php](https://davidhair.atozhair.shop/stock_manager.php)
* 🔌 **DB 연동 테스트**: [https://davidhair.atozhair.shop/db_test.php](https://davidhair.atozhair.shop/db_test.php)
* 📅 **예약 API 엔드포인트**: [https://davidhair.atozhair.shop/api_booking.php](https://davidhair.atozhair.shop/api_booking.php)
* 💈 **재고 API 엔드포인트**: [https://davidhair.atozhair.shop/admin_stock_api.php](https://davidhair.atozhair.shop/admin_stock_api.php)
