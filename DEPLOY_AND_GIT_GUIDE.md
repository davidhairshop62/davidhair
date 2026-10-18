# 🚀 David Hair - 서버 배포 및 Git 작업 가이드

본 문서는 David Hair 프로젝트의 **초고속 서버 배포 방식**과 **수동 Git 운영 규칙**을 정리한 가이드입니다.

---

## 📌 1. 핵심 요약 (기존과의 차이점)

| 구분 | 기존 방식 (느림) | 개선된 방식 (초고속 ~1초) |
| :--- | :--- | :--- |
| **작업 흐름** | 로컬 Commit ➡️ GitHub Push ➡️ SSH 접속 ➡️ 서버에서 Pull | Mac ➡️ 운영 서버(`/var/www/davidhair`) **직접 rsync 전송** |
| **반영 속도** | 약 10초 ~ 20초 이상 | **약 0.5초 ~ 1초 (즉시 반영)** |
| **Git 관리** | 파일 수정마다 자동 커밋 (히스토리 오염) | **하루 1~2회 원할 때만 수동 푸시/풀** |
| **안전성** | 서버 변경 파일과 충돌 시 Pull 중단 위험 | 서버 업로드 파일(`uploads/`) 및 `.git` 완벽 보존 |

---

## 💼 2. 추천 일상 작업 흐름 (Daily Workflow)

### ① 아침 / 작업 시작 시 (코드 다운로드)
GitHub에 올라온 최신 소스코드를 로컬로 가져옵니다:
```bash
npm run git:pull
# 또는
./scripts/git_pull.sh
```

---

### ② 작업 진행 중 (서버 즉시 반영)

#### 방법 A: AI(Antigravity)와 함께 작업할 때
- AI에게 코드 수정을 요청하면 작업 완료 후 **자동으로 서버에 1초 만에 배포**됩니다.
- 응답 마지막에 나오는 사이트 링크를 클릭하여 바로 확인하시면 됩니다.

#### 방법 B: 직접 파일 수정 시 (실시간 자동 전송 모드 - 추천!)
터미널 창을 하나 열고 아래 명령어를 켜두면, **에디터에서 파일을 저장(Cmd + S)할 때마다 0.5초 만에 서버로 자동 전송**됩니다:
```bash
npm run watch
# 또는
./scripts/watch.sh
```
> *(작업을 끝내려면 터미널에서 `Ctrl + C` 누르면 종료됩니다.)*

#### 방법 C: 원할 때 한 번씩 수동으로 서버 올리기
```bash
npm run deploy
# 또는
./scripts/deploy.sh
```

---

### ③ 저녁 / 작업 완료 시 (GitHub 백업 업로드)
하루 작업을 마무리하거나 주요 기능 개발을 완료했을 때 GitHub에 백업합니다:
```bash
npm run git:push
# 또는 커밋 메시지를 직접 입력할 때:
./scripts/git_push.sh "오늘 수정 내용 요약"
```

---

## 🛠️ 3. 명령어 치트시트 (Cheat Sheet)

| 실행 명령어 | 단축 명령어 | 설명 |
| :--- | :--- | :--- |
| `./scripts/deploy.sh` | `npm run deploy` | 로컬 변경파일을 서버(`/var/www/davidhair`)로 1초 만에 즉시 배포 |
| `./scripts/watch.sh` | `npm run watch` | 파일 저장 시 실시간 자동 서버 동기화 데몬 실행 |
| `./scripts/git_pull.sh` | `npm run git:pull` | GitHub(origin main)에서 최신 코드 내려받기 |
| `./scripts/git_push.sh` | `npm run git:push` | 로컬 작업 내용을 GitHub(origin main)으로 백업 푸시 |
| `ssh davidhair` | - | 운영 서버 터미널로 즉시 SSH 접속 |

---

## 🌐 4. 웹사이트 바로가기 링크

* 🏠 **공식 메인 사이트**: [https://www.davidhairshop.com](https://www.davidhairshop.com) (미러: [https://davidhair.atozhair.shop](https://davidhair.atozhair.shop))
* 📦 **재고 관리 시스템**: [https://www.davidhairshop.com/stock_manager.php](https://www.davidhairshop.com/stock_manager.php)
* 🔌 **DB 연동 테스트**: [https://www.davidhairshop.com/db_test.php](https://www.davidhairshop.com/db_test.php)
