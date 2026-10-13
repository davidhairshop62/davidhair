# Hair System Calculation & Ordering Module Specification (`hairCalc.md`)

This document outlines the system requirements, customer journey parameters, base specifications, and pricing formulas for custom men's toupees and women's hair toppers. This module is designed for integration into `davidhair.atoz.shop` on the Antigravity platform.

---

## 1. Initial Flow & Customer Gender Selection
When a customer enters the shop, the initial split determines the product category:
*   **Male (Toupee)**
*   **Female (Hair Topper / Topis)**

---

## 2. Men's Toupee Module Specification

### 2.1 Attachment Type & Size Design (Hairloss Phase)
*   **Bonding (부착식):** Measured precisely to match the exact hairless area.
*   **Clip (클립식):** Designed approximately **$1\text{ cm}$ larger** than the actual hairless area to ensure stable clip placement.

### 2.2 Base Material & Style Recommendation
*   **Natural Hairline Focus:** Full Lace, Lace Front + PU, or Thin Skin + PU.
*   **Bangs / Fringe Down Style (MPU Type):** Features a ~4mm lace front edge with polyurethane (PU) surrounding the perimeter.

### 2.3 Base Standard Dimensions & Stock Sizes
*   **Core Stock Sizes:** $6\times8$, $7\times9$, $8\times10$
*   **Intermediate Sizes:** $6.5\times8$, $7.5\times9$, $8.5\times10$
*   **Extended Sizes:** $6\times8.5$, $7\times9.5$, $8\times10.5$

### 2.4 Parting, Part Styles, & Knotting Methods
*   **Part / Style Options:** Free Style, Spike, Pompadour, Right Part, Left Part, Center Part.
*   **Clear Part Options:** Available with completely clear/transparent parting lines (Right, Left, Center).
*   **Knotting Methods:** 
    *   Standard Semi-Double Knotting (applied directly to Swiss Net [primary], Fine Mono Net, French Net/Bull Net, or Skin Cap).
    *   **Advanced Scalp Simulation (Silk Series):** Silk Top, Silk Part, or Silk Front. Replicates human scalp appearance, requiring **double the production time and higher labor costs**.

### 2.5 Hair Specifications (Male)
*   **Texture:** Straight, Wave, Curl, Afro Curl.
*   **Color:** Black, Dark Brown, Brown, Light Brown.
*   **Grey Hair (White Hair) Ratio:** Overall ratio from $5\%$ to $90\%$ (or $100\%$ Grey). 
    *   *Granular Control:* Individual percentages can be assigned separately for **Sides, Back, Front, and Crown**.
*   **Hair Length:** Standard range $4$, $6$, $8$, $10$, $12$ inches (**$6$ inches is the most popular/primary choice**).
*   **Density:** Options include $60\%$, $70\%$, **$80\%$ (Primary standard)**, $100\%$, $120\%$.

### 2.6 Head Measurement
*   **Wrapping:** Physical head shaping/wrapping process performed prior to factory order transmission.

---

## 3. Women's Hair Topper (Topis) Module Specification

### 3.1 Base Size & Shape
*   **Comprehensive Range:** Supports all cap shapes and dimensions, scaling from tiny part caps (e.g., **$2\times4$ inches** for micro-parting/crown thinning) up to large full-coverage crown bases.

### 3.2 Attachment Type & Conditions
*   **Clip Type (Standard):** Requires verification that the customer has sufficient residual/healthy hair to anchor the clips securely.
*   **Headband Type (Specialized):** Used for conditions like *Frontal Fibrosing Alopecia*, combining elastic bands and clips for secure non-damaging retention.

### 3.3 Scalp Simulation, Parting, & Knotting
*   Shares identical knotting methods (Semi-Double Knotting on Swiss net, etc.) and advanced **Silk Top** options with the men's system.

### 3.4 Hair Specifications (Female)
*   **Texture & Grey Ratio:** Same options as male specifications (including zone-specific grey distribution).
*   **Expanded Color Spectrum:** Broader variety of multi-tone, highlighted, dyed, and lighter shades compared to men's lines.
*   **Expanded Lengths:** Accommodates medium to long lengths tailored to female styling demands.
*   **Density:** Configurable from $60\%$ to $120\%$.

---

## 4. Automated Pricing Calculation Logic & Formula

### 4.1 Base Pricing Reference
*   **Male Base Price (6-inch standard):** ~ $\text{AUD } 1,000$
*   **Female Base Price (Mini/Standard baseline):** ~ $\text{AUD } 1,200$

### 4.2 Formula Representation
$$\text{Total Price} = (\text{Base Price} + \text{Size Fee} + \text{Length Fee}) \times \text{Density Multiplier} \times \text{Silk Multiplier} + \text{Grey Hair Fee} + \text{Accessory Fee}$$

### 4.3 Variable Multipliers & Add-ons
*   **Size Fee:** Additional cost applied for oversized bases or specialized micro-caps ($2\times4$).
*   **Length Fee:** Incremental charges per inch exceeding the baseline ($6$ inches for men).
*   **Density Multiplier:** High-density options ($>100\%$) apply a $\times 1.15$ factor.
*   **Silk Multiplier:** Selecting Silk Top / Silk Part applies a $\times 1.8 \sim 2.0$ labor multiplier due to doubled production hours.
*   **Grey Hair Fee:** Surcharge for complex multi-zone grey placement.
*   **Accessory Fee:** Additional costs for specialized headband components.