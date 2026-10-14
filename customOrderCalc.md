# David Hair - Interactive Custom Order Builder Architecture & Calculation Guide

> **문서 버전**: 2.0.0 (Men's Toupee 최신 사양 반영)  
> **최종 수정일**: 2026-10-15  
> **대상 파일**: [`index.html`](file:///c:/Users/david/OneDrive/Desktop/davidhair/index.html) (`<section id="builder">`, `function davidHairApp()`)  
> **라이브 서비스 URL**: [https://davidhair.atozhair.shop/#builder](https://davidhair.atozhair.shop/#builder)

---

## 1. 개요 (Overview)

David Hair의 **Interactive Custom Order Builder**는 고객이 원하는 맞춤 가발(Hairpiece)을 6단계 위저드 방식으로 구성하고, 실시간으로 공장 직거래 가격(Live Estimate)을 산출하여 문의/주문할 수 있는 맞춤 제작 시스템입니다.

사용자가 선택하는 3가지 핵심 프로필에 따라 화면과 옵션이 완전히 차별화된 전용 플로우(Dedicated Flow)로 분기됩니다.

1. **♂ Men's Toupee (남성 맞춤 가발)**: From **$650 AUD** (정수리/앞이마 남성형 탈모 커버, 스위스넷/PU/스킨/레이스 베이스, 새치 비율(Grey Blend), 바버 페이드 컷인)
2. **♀ Women's Top Piece (여성 탑피스 / 헤어 토퍼)**: From **$600 AUD** (정수리/가르마 확산형 탈모 커버, 트리플 실크탑(히든 매듭), 무삭발 압력 스냅 클립, 발레아쥬 컬러, 페이스 프레이밍 셋팅)
3. **👑 Full Cap System (전두 가발 / 메디컬 캡)**: From **$800 AUD** (전체 탈모/전두 탈모증(Alopecia Totalis), 3D 두상 맞춤 몰드, 무접착 실리콘 흡착, 360° 전방향 가르마 & 올림머리)

---

## 2. 시스템별 기본 가격 및 단계별 옵션 비교표

| 단계 (Step) | ♂ Men's Toupee (남성 맞춤 가발) | ♀ Women's Top Piece (여성 탑피스) | 👑 Full Cap System (전두 가발) |
| :--- | :--- | :--- | :--- |
| **기본 가격 (Base Price)** | **From $650 AUD** | **From $600 AUD** | **From $800 AUD** |
| **Step 1: 시스템 선택** | `id: 'men'` (Frontal & Crown Replacement) | `id: 'women'` (Silk Top Hair Topper) | `id: 'full'` (Medical 360° Full Cap) |
| **Step 2: 베이스 재질** | • Swiss Net + PU (Clip & Bonding) : **+$0**<br>• Lace Front + Swiss Net + PU (Clip & Bonding) : **+$50**<br>• Ultra-thin Skin (Bonding) : **+$50**<br>• Full Swiss Lace (Bonding) : **+$0** | • Triple-Layer Injected Silk Top (+ $90)<br>• Fine Mono with PU Perimeter (+ $70)<br>• French Lace with Soft Stretch (+ $60)<br>• Integration Micro-Mesh Net (+ $80) | • Premier Medical CNC Vacuum Cap (+ $250)<br>• Full Swiss Lace + Stretch Center (+ $120)<br>• Medical Anti-Slip Silicone Cap (+ $180)<br>• Full Thin Skin 0.05~0.08mm (+ $150)<br>• Silk Top Crown + Lace Perimeter (+ $190) |
| **Step 2: 규격 프리셋 & 사이즈** | • 6" x 8" (Small / Frontal : **+$0**)<br>• 7" x 9" (Medium : **+$20**)<br>• 8" x 10" (Standard : **+$40**)<br>• Custom W x L (Inches / CM) | • 5" x 5" (Included)<br>• 6" x 6" (+ $25)<br>• 7" x 7" (+ $45)<br>• 8" x 8" (+ $70)<br>• 커스텀 가로 x 세로 입력 (Inches) | • Petite / Small: 21.0" ~ 21.5" (Included)<br>• Average / Medium: 22.0" ~ 22.5" (Included)<br>• Large Cap: 23.0" ~ 23.5" (+ $30)<br>• 3D 해부학적 맞춤 두상 몰드 (+ $50) |
| **Step 3: 헤어 기장 (Length)** | • 5" / 6" (기본 : **+$0**)<br>• 8" (**+$50**)<br>• 10" (**+$90**)<br>• 12" (**+$100**) | • 10" (+ $90)<br>• 12" (+ $100)<br>• 14" (+ $200)<br>• 16" (+ $300)<br>• 18" (+ $400)<br>• 20" (+ $500)<br>• 22" (+ $600) | • 8" (+ $50)<br>• 12" (+ $100)<br>• 16" (+ $300)<br>• 20" (+ $500)<br>• 24" (+ $700) |
| **Step 3: 헤어 밀도 (Density)** | • 60% ~ 110% 슬라이더 (남성 자연스러운 80% 기본 : **+$0**)<br>• 90% : **+$10**<br>• 100% : **+$20**<br>• 110% : **+$30** | 80% ~ 140% 슬라이더 (기본 110% Medium Full) | 90% ~ 150% 슬라이더 (기본 120% Full Natural) |
| **Step 3: 웨이브 / 텍스처** | • Straight (기본 : **+$0**)<br>• Slight Wave : **+$10**<br>• Body Wave : **+$10**<br>• Tight Curl : **+$30** | Silky Straight, Natural Body Wave, Loose Wave, Deep Curl | Natural Straight, Soft Wave, Body Wave, Kinky Curly |
| **Step 3: 부착 방식 (Attachment)** | 클립 & 본딩 겸용 구조 기본 제공 | • 압력 스냅 클립 (Included)<br>• 클립 + 전면 테이프 탭 (+ $25)<br>• 헤일로 와이어 + 클립 (+ $30)<br>• 둘레 PU 본딩 (+ $35) | • 조절식 엘라스틱 스트랩 (Included)<br>• 메디컬 실리콘 흡착 패드 (+ $40)<br>• 콤 클립 + 스트랩 (+ $25) |
| **Step 4: 베이스 컬러** | **8종 스와치**: `#1`, `#1B`, `#Natural`, `#2`, `#3`, `#4`, `#6`, `#8` | #1B, #2, #4, #4/27, #8/60, #613 | #1, #1B, #2, #4, #4/27, #613 |
| **Step 4: 새치 블렌딩 (Grey Blend)** | **0% ~ 80% 슬라이더 & 원터치 프리셋 버튼**:<br>• 0% : **+$0**<br>• 5% : **+$10**<br>• 10% : **+$15**<br>• 20% : **+$30**<br>• 30% : **+$30**<br>• 50% : **+$60**<br>• 80% : **+$100** | 해당 없음 (자연 갈색/블론드 발레아쥬 옵션) | 해당 없음 |
| **Step 4: 모류 방향 & 가르마** | • Freestyle (자유 모류)<br>• Left Part (좌측 가르마)<br>• Right Part (우측 가르마)<br>• Center Part (중앙 가르마)<br>• Crown Pompadour (포마드)<br>• Slicked Back (올백 스타일) | Free Part (Multi-Directional), Center Part, Left Part, Right Part | 360° Free Part, Center Part, Deep Side Part |
| **Step 5: 살롱 서비스 & 크래프트** | • 앞이마 블리치드 매듭 : **+$20**<br>• 스캘럽트 프론트 헤어라인 : **+$25**<br>• 언더 벤틸레이션 : **+$20**<br>• 멜버른 바버 컷인 & 페이드 블렌딩 : **+$145**<br>• 4-6주 메디컬 리퀴드 본딩 : **+$80**<br>• 일일 클립 & 테이프 스타터 팩 : **+$30** | • 플래그십 토퍼 페이스 프레이밍 & 드라이 (+ $160)<br>• 실크 콘실드 매듭 & 베이비 헤어 (+ $30)<br>• 소프트 벨벳 이마 밴드 (+ $25)<br>• 추가 교체용 실리콘 클립 4개 (+ $20) | • 맞춤 두상 피팅 & 마스터 컷인 (+ $190)<br>• 메디컬 민감 두피 진정 프로토콜 (+ $95)<br>• 360° 둘레 블리치드 매듭 (+ $40)<br>• 디럭스 가발 보관 & 관리 키트 (+ $55) |
| **Step 6: 검토 & 제출** | 선택된 남성 투피 맞춤 사양 요약, 두피/템플릿 사진 첨부, 견적 합계 확인 및 문의 제출 | 토퍼 사양 요약, 사진 업로드, 고객 정보 입력, 상담 제출 | 풀캡 사양 요약, 두상 사진 업로드, 고객 정보 입력, 상담 제출 |

---

## 3. 남성 맞춤 가발 (`men`) 가격 계산 알고리즘

### 3.1 총 견적 산출 수식

$$\text{Men Total} = \text{Base Price (\$650)} + \text{Base Material} + \text{Size Surcharge} + \text{Length Surcharge} + \text{Density Surcharge} + \text{Wave Surcharge} + \text{Grey Surcharge} + \sum \text{Salon Services}$$

### 3.2 세부 가산금 산정 규칙

#### (1) 베이스 재질 (Base Material)
- `men_swiss_pu` (Swiss Net + PU): **+$0**
- `men_lace_front_swiss_pu` (Lace Front + Swiss Net + PU): **+$50 AUD**
- `men_skin` (Ultra-thin Skin): **+$50 AUD**
- `men_full_lace` (Full Swiss Lace): **+$0**

#### (2) 규격 프리셋 & 커스텀 사이즈 (Size Surcharge)
함수: `getDimensionSurcharge()`
- `6" x 8"`: **+$0**
- `7" x 9"`: **+$20 AUD**
- `8" x 10"`: **+$40 AUD**
- 커스텀 W x L (Inches / CM 변환):
  - $W + L \le 14"$: **+$0**
  - $W + L \le 16"$: **+$20 AUD**
  - $W + L \le 18"$: **+$40 AUD**
  - 초과: **+$70 AUD**

#### (3) 기장 (Hair Length Surcharge)
함수: `getHairLengthSurchargeFor(lengthInches)`
- $5" / 6"$: **+$0** (Included)
- $8"$: **+$50 AUD**
- $10"$: **+$90 AUD**
- $12"$: **+$100 AUD**

#### (4) 헤어 밀도 (Density Surcharge)
함수: `getMenDensitySurcharge()`
- $60\% \sim 80\%$: **+$0** (남성 자연스러운 80% 기본)
- $90\%$: **+$10 AUD**
- $100\%$: **+$20 AUD**
- $110\%$: **+$30 AUD**

#### (5) 웨이브 / 텍스처 (Wave Surcharge)
함수: `getMenWaveSurcharge()`
- `Straight`: **+$0** (Included)
- `Slight Wave`: **+$10 AUD**
- `Body Wave`: **+$10 AUD**
- `Tight Curl`: **+$30 AUD**

#### (6) 새치 블렌딩 (Grey Blend Surcharge)
함수: `getMenGreySurcharge()`
- $0\%$: **+$0** (Included)
- $5\%$: **+$10 AUD**
- $10\%$: **+$15 AUD**
- $20\%$: **+$30 AUD**
- $30\%$: **+$30 AUD**
- $50\%$: **+$60 AUD**
- $80\%$: **+$100 AUD**

#### (7) 살롱 서비스 (Salon Services)
- 앞이마 블리치드 매듭 (`menBleachedKnots`): **+$20 AUD**
- 스캘럽트 프론트 헤어라인 (`menScallopedFront`): **+$25 AUD**
- 언더 벤틸레이션 (`menUnderVentilation`): **+$20 AUD**
- 멜버른 바버 컷인 & 페이드 블렌딩 (`menPrecisionCutIn`): **+$145 AUD**
- 4-6주 메디컬 리퀴드 본딩 (`menBondService`): **+$80 AUD**
- 일일 클립 & 테이프 스타터 팩 (`menClipAndTape`): **+$30 AUD**

---

## 4. 프론트엔드 핵심 구현 코드 (`index.html`)

### 4.1 상태 데이터 구조 (`davidHairApp`)

```javascript
builder: {
    profile: 'men', // 'men' | 'women' | 'full'
    
    // ♂ Men's Toupee Specs (남성 맞춤 가발)
    menBase: 'men_swiss_pu',      // 'men_swiss_pu' (+$0), 'men_lace_front_swiss_pu' (+$50), 'men_skin' (+$50), 'men_full_lace' (+$0)
    menUnit: 'inch',              // 'inch' | 'cm'
    menSizePreset: '6x8',         // '6x8' (+$0), '7x9' (+$20), '8x10' (+$40), 'custom'
    menWidth: 6.0,
    menLength: 8.0,
    menHairLength: 6,             // 5, 6 (+$0), 8 (+$50), 10 (+$90), 12 (+$100)
    menDensity: 80,               // 60~80 (+$0), 90 (+$10), 100 (+$20), 110 (+$30)
    menWave: 'Straight',          // 'Straight' (+$0), 'Slight Wave' (+$10), 'Body Wave' (+$10), 'Tight Curl' (+$30)
    menColor: '#1B',              // #1, #1B, #Natural, #2, #3, #4, #6, #8
    menGreyPercent: 0,            // 0 (+$0), 5 (+$10), 10 (+$15), 20 (+$30), 30 (+$30), 50 (+$60), 80 (+$100)
    menDirection: 'Freestyle',    // 'Freestyle', 'Left Part', 'Right Part', 'Center Part', 'Crown Pompadour', 'Slicked Back'
    menBleachedKnots: false,      // +$20 AUD
    menScallopedFront: true,      // +$25 AUD (기본 선택)
    menUnderVentilation: false,   // +$20 AUD
    menPrecisionCutIn: true,      // +$145 AUD (기본 선택)
    menBondService: false,        // +$80 AUD
    menClipAndTape: true,         // +$30 AUD (기본 선택)

    // 고객 정보
    customerName: '',
    customerPhone: '',
    customerEmail: '',
    notes: ''
}
```

### 4.2 남성 맞춤 가발 계산 및 헬퍼 메서드

```javascript
getMenDensitySurcharge() {
    const d = Number(this.builder.menDensity) || 80;
    if (d <= 80) return 0;
    if (d <= 90) return 10;
    if (d <= 100) return 20;
    return 30; // 110%
},

getMenWaveSurcharge() {
    const w = this.builder.menWave;
    if (w === 'Slight Wave' || w === 'Body Wave') return 10;
    if (w === 'Tight Curl') return 30;
    return 0; // Straight
},

getMenGreySurcharge() {
    const g = Number(this.builder.menGreyPercent) || 0;
    if (g <= 0) return 0;
    if (g <= 5) return 10;
    if (g <= 10) return 15;
    if (g <= 30) return 30;
    if (g <= 50) return 60;
    return 100; // 80%
},

calculateTotal() {
    let total = 0;
    const p = this.profiles.find(x => x.id === this.builder.profile);
    total += p ? p.basePrice : 650;

    if (this.builder.profile === 'men') {
        const b = this.basesMen.find(x => x.id === this.builder.menBase);
        total += b && b.price !== undefined ? b.price : 0;
        total += this.getDimensionSurcharge();
        total += this.getHairLengthSurchargeFor(this.builder.menHairLength);
        total += this.getMenDensitySurcharge();
        total += this.getMenWaveSurcharge();
        total += this.getMenGreySurcharge();
        if (this.builder.menBleachedKnots) total += 20;
        if (this.builder.menScallopedFront) total += 25;
        if (this.builder.menUnderVentilation) total += 20;
        if (this.builder.menPrecisionCutIn) total += 145;
        if (this.builder.menBondService) total += 80;
        if (this.builder.menClipAndTape) total += 30;
    } else if (this.builder.profile === 'women') {
        // 여성 탑피스 계산
        ...
    } else if (this.builder.profile === 'full') {
        // 풀캡 전두 가발 계산
        ...
    }
    return total;
}
```

---

## 5. 배포 및 서버 동기화

수정 사항은 작업 공간의 자동 배포 명령어를 통해 원격 서버로 즉시 반영됩니다:

```bash
# 원격 프로덕션 서버 자동 동기화 배포
npm run deploy
```

- **원격 서버**: `root@187.127.96.101:/var/www/davidhair`
- **프로덕션 라이브 URL**: [https://davidhair.atozhair.shop/#builder](https://davidhair.atozhair.shop/#builder)
