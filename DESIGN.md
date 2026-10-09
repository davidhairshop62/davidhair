# DESIGN.md - David Hair Architecture & Responsive Design System

## 1. Visual Theme & Atmosphere
- **Brand**: David Hair (davidhairshop.com / davidhair.shop)
- **Role**: Senior Full-Stack Developer & UI/UX Architect
- **Atmosphere**: Low-glare, eye-comfortable matte slate ambiance with full cross-device responsiveness (Desktop, Tablet 768px-1024px, Mobile <768px). Combines deep soothing slate tones with muted emerald-teal and warm bronze highlights.
- **Density**: 5 (Daily App Balanced)
- **Variance**: 8 (Offset Asymmetric & Dynamic)
- **Motion Intensity**: 6 (Fluid Spring Physics & Micro-interactions)

---

## 2. Color Calibration & Roles (Eye-Comfortable Palette)
- **Matte Midnight Canvas** (`#0B132B`, `#0F172A`) — Deep, low-glare background surface
- **Soft Ambient Card Fill** (`#1C2541`, `#1E293B`) — Soothing surface fill with subtle border definition
- **Warm Eye-Friendly Text** (`#F1F5F9`) — Soft off-white main text
- **Muted Steel Text** (`#94A3B8`) — Secondary text and helper labels
- **Comfort Emerald Teal** (`#0D9488`, `#14B8A6`) — Soft primary action accent
- **Warm Bronze Gold** (`#D97706`, `#B45309`) — Muted luxury highlight badges
- **Soft Slate Border** (`#2E3A59`, `#334155`) — Low-contrast structural borders

---

## 3. Responsive Breakpoints & Viewport Architecture

### 1. Mobile First (< 768px)
- **Navigation**: Concise menu labels (`Order`, `Salon`, `Stock`, `Trans`, `Factory`, `B2B`). Collapses into full-width animated mobile drawer (`x-show="mobileMenuOpen"`).
- **Hero & Headers**: Fluid responsive typography using `text-3xl sm:text-5xl lg:text-6xl`. Hero buttons stack vertically (`flex-col sm:flex-row`).
- **Custom Order Builder**: Single-column step cards; sticky order summary card moves below wizard. Touch-friendly selection buttons (min 44px height).
- **Stock Pieces**: Single column product grid (`grid-cols-1 sm:grid-cols-2 lg:grid-cols-4`). Filter buttons wrap cleanly (`flex-wrap`).
- **Before & After Slider**: Dual touch listener (`@touchmove`) & mouse listener (`@mousemove`) for smooth touch dragging on iPhone/Android screens.

---

## 4. Core Features & Navigation Structure
- **Header Menu Labels**:
  1. `Order` (`#builder`)
  2. `Salon` (`#salon`)
  3. `Stock` (`#stock`)
  4. `Trans` (`#transformations`)
  5. `Factory` (`#factory`)
  6. `B2B` (`#b2b`)
- **Utilities**: Currency Switcher (`AUD` / `USD` / `KRW`), "Free Consulting" primary CTA button, WhatsApp floating trigger.
- **Multi-Column Footer**: Brand & Socials, Quick Navigation, Springvale Salon Hours & Contact, Quality Guarantees & System Status (`/db_test.php`), Payment Method Icons.
