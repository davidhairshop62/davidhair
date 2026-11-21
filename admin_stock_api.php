<?php
// admin_stock_api.php - Authentication & Stock Management & AI Hairstyles CRUD API
error_reporting(0);
ini_set('display_errors', '0');
ob_start();
session_start();

function sendJsonResponse($data, $statusCode = 200) {
    if (ob_get_length()) ob_clean();
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

require_once __DIR__ . '/config.php';

$uploadDir = __DIR__ . '/uploads/';
if (!file_exists($uploadDir)) {
    @mkdir($uploadDir, 0777, true);
}

// Auto-create ai_hairstyles table
try {
    $conn->exec("CREATE TABLE IF NOT EXISTS ai_hairstyles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        tag VARCHAR(150) DEFAULT '',
        gender VARCHAR(50) DEFAULT 'Men',
        overlay LONGTEXT NOT NULL,
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Seed initial hairstyles if table is empty
    $checkStmt = $conn->query("SELECT COUNT(*) FROM ai_hairstyles");
    if ($checkStmt->fetchColumn() == 0) {
        $initialStyles = [
            ['name' => 'Crew cut', 'tag' => 'Clean Short Fade', 'gender' => 'Men', 'overlay' => 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 300" fill="none"><path d="M120,180 C110,110 160,35 250,30 C340,35 390,110 380,180 C360,140 330,85 250,80 C170,85 140,140 120,180 Z" fill="%231a1a1a" opacity="0.95"/><path d="M140,145 C170,75 330,75 360,145 C330,95 170,95 140,145 Z" fill="%232b2b2b" opacity="0.88"/><path d="M160,110 C200,55 300,55 340,110 C300,70 200,70 160,110 Z" fill="%230f0f0f" opacity="0.95"/></svg>', 'sort_order' => 1],
            ['name' => 'Gentleman', 'tag' => 'Executive Side Part', 'gender' => 'Men', 'overlay' => 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 320" fill="none"><path d="M100,200 C90,120 150,25 260,20 C360,20 410,100 400,200 C380,140 350,60 270,60 C200,60 170,120 100,200 Z" fill="%23151515" opacity="0.95"/><path d="M110,180 C140,80 240,40 340,70 C380,120 370,180 340,130 C270,75 200,80 110,180 Z" fill="%232a2a2a" opacity="0.88"/><path d="M140,130 C190,50 300,50 360,110 C310,65 220,65 140,130 Z" fill="%230a0a0a" opacity="0.95"/></svg>', 'sort_order' => 2],
            ['name' => 'Textured Crop', 'tag' => 'Modern Layered Fringe', 'gender' => 'Men', 'overlay' => 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 320" fill="none"><path d="M110,210 C100,120 160,30 250,25 C340,30 400,120 390,210 C370,170 360,120 330,125 C300,130 280,115 250,120 C220,125 190,115 170,125 C140,130 130,170 110,210 Z" fill="%231a1a1a" opacity="0.95"/><path d="M130,160 C160,70 340,70 370,160 C340,105 300,110 250,105 C200,110 160,105 130,160 Z" fill="%23303030" opacity="0.85"/><path d="M150,120 C190,45 310,45 350,120 C310,65 260,65 150,120 Z" fill="%230d0d0d" opacity="0.95"/></svg>', 'sort_order' => 3],
            ['name' => 'Slick Back', 'tag' => 'Back Swept Undercut', 'gender' => 'Men', 'overlay' => 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 320" fill="none"><path d="M105,210 C95,110 150,20 250,15 C350,20 405,110 395,210 C380,150 340,50 250,55 C160,50 120,150 105,210 Z" fill="%23181818" opacity="0.95"/><path d="M125,170 C160,60 340,60 375,170 C340,90 250,85 125,170 Z" fill="%232c2c2c" opacity="0.88"/><path d="M150,120 C190,40 310,40 350,120 C310,55 190,55 150,120 Z" fill="%230a0a0a" opacity="0.95"/></svg>', 'sort_order' => 4],
            ['name' => 'Silk Topper', 'tag' => "Women's Crown Volume", 'gender' => 'Women', 'overlay' => 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 360" fill="none"><path d="M90,260 C80,130 150,15 250,10 C350,15 420,130 410,260 C390,180 370,80 250,75 C130,80 110,180 90,260 Z" fill="%23181818" opacity="0.95"/><path d="M110,210 C140,80 360,80 390,210 C360,110 250,105 110,210 Z" fill="%232e2e2e" opacity="0.88"/><path d="M140,140 C180,45 320,45 360,140 C320,65 180,65 140,140 Z" fill="%230c0c0c" opacity="0.95"/></svg>', 'sort_order' => 5]
        ];
        $ins = $conn->prepare("INSERT INTO ai_hairstyles (name, tag, gender, overlay, sort_order) VALUES (:name, :tag, :gender, :overlay, :sort_order)");
        foreach ($initialStyles as $st) {
            $ins->execute($st);
        }
    }
} catch (Exception $e) {
    // Table exists or error ignored
}

// Auto-create cap_guide_items table (Buyer's & Stylist's Guide: Men's Toupees & Women's Toppers)
try {
    $conn->exec("CREATE TABLE IF NOT EXISTS cap_guide_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category VARCHAR(20) NOT NULL DEFAULT 'men',
        title VARCHAR(150) NOT NULL,
        subtitle VARCHAR(150) DEFAULT '',
        image_url VARCHAR(500) DEFAULT '',
        modal_subtitle VARCHAR(200) DEFAULT '',
        modal_desc TEXT NULL,
        realism TINYINT NOT NULL DEFAULT 4,
        breathability TINYINT NOT NULL DEFAULT 4,
        lifespan VARCHAR(100) DEFAULT '',
        lifespan_tone VARCHAR(20) DEFAULT 'slate',
        annual_cost VARCHAR(120) DEFAULT '',
        annual_cost_sub VARCHAR(120) DEFAULT '',
        annual_cost_tone VARCHAR(20) DEFAULT 'slate',
        advantages TEXT NULL,
        drawbacks TEXT NULL,
        best_suited TEXT NULL,
        sort_order INT NOT NULL DEFAULT 0,
        is_visible TINYINT(1) NOT NULL DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $cgCount = $conn->query("SELECT COUNT(*) FROM cap_guide_items")->fetchColumn();
    if ($cgCount == 0) {
        $initialCapGuides = [
            // Men's Toupees (6)
            [
                'category' => 'men',
                'title' => 'Ultra-Thin Skin',
                'subtitle' => '0.02mm - 0.04mm PU',
                'image_url' => 'images/caps/men_ultra_thin_skin.jpg',
                'modal_subtitle' => "Men's Toupee Base • Micro-Polyurethane Membrane",
                'modal_desc' => 'Seamless flush hairline with undetectable skin surface, ideal for swept-back hairlines.',
                'realism' => 5,
                'breathability' => 1,
                'lifespan' => '1 - 2 Mos.',
                'lifespan_tone' => 'rose',
                'annual_cost' => 'Very High',
                'annual_cost_sub' => '(6–10 units/yr)',
                'annual_cost_tone' => 'rose',
                'advantages' => 'Flawless flush hairline; brush-back styles; easy wipe-clean adhesive removal.',
                'drawbacks' => 'Traps heat/perspiration; highly prone to tearing during wash routines.',
                'best_suited' => 'Events, exposed slicked-back hairlines, and high maintenance budgets.',
                'sort_order' => 1,
            ],
            [
                'category' => 'men',
                'title' => 'Standard Thin Skin',
                'subtitle' => '0.06mm - 0.12mm PU',
                'image_url' => 'images/caps/men_standard_thin_skin.jpg',
                'modal_subtitle' => "Men's Toupee Base • Durable Polyurethane Edge",
                'modal_desc' => 'Durable polyurethane skin perimeter offering tear resistance and easy tape cleanup.',
                'realism' => 3,
                'breathability' => 2,
                'lifespan' => '3 - 6 Mos.',
                'lifespan_tone' => 'slate',
                'annual_cost' => 'Moderate',
                'annual_cost_sub' => '(2–4 units/yr)',
                'annual_cost_tone' => 'slate',
                'advantages' => 'Significantly stronger than ultra-thin; simple tape application and cleanup.',
                'drawbacks' => 'Perimeter line can show under inspection; slight shine under bright spotlighting.',
                'best_suited' => 'Beginners wanting manageable home application without tear anxiety.',
                'sort_order' => 2,
            ],
            [
                'category' => 'men',
                'title' => 'Full Swiss Lace',
                'subtitle' => 'Ultra-Fine Soft Mesh',
                'image_url' => 'images/caps/men_swiss_lace.jpg',
                'modal_subtitle' => "Men's Toupee Base • 100% Breathable Swiss Mesh",
                'modal_desc' => 'Ultra-fine soft mesh with maximum ventilation, providing supreme scalp breathability.',
                'realism' => 5,
                'breathability' => 5,
                'lifespan' => '1 - 3 Mos.',
                'lifespan_tone' => 'amber',
                'annual_cost' => 'High',
                'annual_cost_sub' => '(4–6 units/yr)',
                'annual_cost_tone' => 'rose',
                'advantages' => 'Maximum ventilation; softest edge; realistic undetectable front transition.',
                'drawbacks' => 'Adhesive seeps through holes; laborious clean-up; delicate mesh strands.',
                'best_suited' => 'Athletes, hot climates, and users demanding maximum cooling.',
                'sort_order' => 3,
            ],
            [
                'category' => 'men',
                'title' => 'Full French Lace',
                'subtitle' => 'Durable Lace Mesh',
                'image_url' => 'images/caps/men_french_lace.jpg',
                'modal_subtitle' => "Men's Toupee Base • Reinforced Honeycomb Mesh",
                'modal_desc' => 'Resilient honeycomb mesh weave with hand-tied knots for long-wearing durability.',
                'realism' => 4,
                'breathability' => 5,
                'lifespan' => '3 - 4 Mos.',
                'lifespan_tone' => 'slate',
                'annual_cost' => 'Moderate–High',
                'annual_cost_sub' => '',
                'annual_cost_tone' => 'amber',
                'advantages' => 'Sturdier than Swiss lace; excellent breathability; long-wearing integrity.',
                'drawbacks' => 'Under close scrutiny, the mesh lattice is slightly more detectable than Swiss lace.',
                'best_suited' => 'Wearers seeking high breathability without replacing units every 6 weeks.',
                'sort_order' => 4,
            ],
            [
                'category' => 'men',
                'title' => 'Fine Monofilament',
                'subtitle' => 'Woven Mono Base',
                'image_url' => 'images/caps/men_fine_monofilament.jpg',
                'modal_subtitle' => "Men's Toupee Base • High-Density Woven Mono",
                'modal_desc' => 'Fine woven monofilament center base with PU perimeter for heavy density support.',
                'realism' => 2,
                'breathability' => 3,
                'lifespan' => '6 - 12+ Mos.',
                'lifespan_tone' => 'emerald',
                'annual_cost' => 'Lowest',
                'annual_cost_sub' => '(1–2 units/yr)',
                'annual_cost_tone' => 'emerald',
                'advantages' => 'Exceptional durability; handles heavy hair density without tearing.',
                'drawbacks' => 'Thick border; cannot expose the front hairline cleanly; stiffer feel.',
                'best_suited' => 'Fringe/bangs forward hairstyles where cost-per-day is the top metric.',
                'sort_order' => 5,
            ],
            [
                'category' => 'men',
                'title' => 'Hybrid Cap',
                'subtitle' => 'Lace Front + Mono + PU Edge',
                'image_url' => 'images/caps/men_hybrid_cap.jpg',
                'modal_subtitle' => "Men's Toupee Base • Multi-Zone Precision Construction",
                'modal_desc' => 'Undetectable front lace transition combined with sturdy mono crown and clean PU border.',
                'realism' => 4,
                'breathability' => 4,
                'lifespan' => '4 - 8 Mos.',
                'lifespan_tone' => 'emerald',
                'annual_cost' => 'Balanced',
                'annual_cost_sub' => '',
                'annual_cost_tone' => 'slate',
                'advantages' => 'Natural front, easy tape cleaning around the perimeter, sturdy center crown.',
                'drawbacks' => 'Front lace wears out earlier than the rear mono; requires care along joints.',
                'best_suited' => 'The most versatile choice. Ideal balance of realism, lifespan, and daily maintenance.',
                'sort_order' => 6,
            ],
            // Women's Hair Toppers (5)
            [
                'category' => 'women',
                'title' => 'Silk Top',
                'subtitle' => '3-Layer Hidden Knot',
                'image_url' => 'images/caps/women_silk_top.jpg',
                'modal_subtitle' => "Women's Topper Base • Zero Knot Scalp Simulation",
                'modal_desc' => 'Three-layer construction with hidden knots, indistinguishable from a biological human scalp.',
                'realism' => 5,
                'breathability' => 2,
                'lifespan' => '1 - 2+ Years',
                'lifespan_tone' => 'emerald',
                'annual_cost' => 'High Upfront',
                'annual_cost_sub' => '(Low maintenance)',
                'annual_cost_tone' => 'slate',
                'advantages' => 'Zero visible black knots; indistinguishable from a biological human scalp.',
                'drawbacks' => 'Heavier 3-layer build; can sit slightly elevated on the crown before break-in.',
                'best_suited' => 'Frequent top-down visibility, defined part lines, and maximum scalp realism.',
                'sort_order' => 1,
            ],
            [
                'category' => 'women',
                'title' => 'Monofilament (Mono Top)',
                'subtitle' => 'Single Mesh Layer',
                'image_url' => 'images/caps/women_mono_top.jpg',
                'modal_subtitle' => "Women's Topper Base • Free Parting Transparent Mesh",
                'modal_desc' => 'Soft contouring single mesh base with perimeter clips, allowing multi-directional parting.',
                'realism' => 4,
                'breathability' => 4,
                'lifespan' => '1 - 1.5 Years',
                'lifespan_tone' => 'emerald',
                'annual_cost' => 'Moderate Upfront',
                'annual_cost_sub' => '',
                'annual_cost_tone' => 'slate',
                'advantages' => 'Hugs the head contour smoothly; allows multi-directional part relocation.',
                'drawbacks' => 'Knots are subtly visible upon micro-inspection unless pre-bleached.',
                'best_suited' => 'Sensitive scalps, daily all-day wearers who prioritize flat, discreet volume.',
                'sort_order' => 2,
            ],
            [
                'category' => 'women',
                'title' => 'Lace Front / Lace Top',
                'subtitle' => 'Sheer Extended Lace',
                'image_url' => 'images/caps/women_lace_front.jpg',
                'modal_subtitle' => "Women's Topper Base • Undetectable Front Hairline",
                'modal_desc' => 'Sheer extended French lace edge for hairline recession, featuring micro clips.',
                'realism' => 5,
                'breathability' => 5,
                'lifespan' => '6 - 12 Mos.',
                'lifespan_tone' => 'amber',
                'annual_cost' => 'Moderate–High',
                'annual_cost_sub' => '',
                'annual_cost_tone' => 'amber',
                'advantages' => 'Flattest transition available; creates a new hairline if biological front is receded.',
                'drawbacks' => 'Delicate edge requires careful brushing; front lace may require daily tape down.',
                'best_suited' => "Frontal fibrosing alopecia, receded widow's peaks, and hot humid environments.",
                'sort_order' => 3,
            ],
            [
                'category' => 'women',
                'title' => 'Injected Poly / Skin',
                'subtitle' => 'Polyurethane Crown',
                'image_url' => 'images/caps/women_injected_skin.jpg',
                'modal_subtitle' => "Women's Topper Base • Direct Root Injection",
                'modal_desc' => 'Hair strands individually injected into polyurethane membrane with zero visible knots.',
                'realism' => 4,
                'breathability' => 1,
                'lifespan' => '6 - 12 Mos.',
                'lifespan_tone' => 'slate',
                'annual_cost' => 'Moderate',
                'annual_cost_sub' => '',
                'annual_cost_tone' => 'slate',
                'advantages' => 'No knots visible; hair looks like it grows straight from the scalp membrane.',
                'drawbacks' => 'Zero breathability; potential for base yellowing over time; heat buildup.',
                'best_suited' => 'Winter/cool weather wear, short daily outings, or adhesive-attached toppers.',
                'sort_order' => 4,
            ],
            [
                'category' => 'women',
                'title' => 'Wefted / Honeycomb Base',
                'subtitle' => 'Pull-Through Integration',
                'image_url' => 'images/caps/women_honeycomb_base.jpg',
                'modal_subtitle' => "Women's Topper Base • Pull-Through Fishnet Grid",
                'modal_desc' => 'Open honeycomb grid allowing biological hair pull-through for maximum crown volume.',
                'realism' => 2,
                'breathability' => 5,
                'lifespan' => '1 - 2+ Years',
                'lifespan_tone' => 'emerald',
                'annual_cost' => 'Lowest',
                'annual_cost_sub' => '',
                'annual_cost_tone' => 'emerald',
                'advantages' => 'Maximum crown volume; biological hair blends directly through the open slots.',
                'drawbacks' => 'Cannot conceal completely bald spots without a mono/silk crown insert.',
                'best_suited' => 'Diffuse thinning where substantial hair remains and overall volume is the goal.',
                'sort_order' => 5,
            ],
        ];

        $insCg = $conn->prepare("INSERT INTO cap_guide_items
            (category, title, subtitle, image_url, modal_subtitle, modal_desc, realism, breathability, lifespan, lifespan_tone, annual_cost, annual_cost_sub, annual_cost_tone, advantages, drawbacks, best_suited, sort_order, is_visible)
            VALUES (:category, :title, :subtitle, :image_url, :modal_subtitle, :modal_desc, :realism, :breathability, :lifespan, :lifespan_tone, :annual_cost, :annual_cost_sub, :annual_cost_tone, :advantages, :drawbacks, :best_suited, :sort_order, 1)");
        foreach ($initialCapGuides as $cg) {
            $insCg->execute($cg);
        }
    }
} catch (Exception $e) {
    // Table exists or error ignored
}

// Auto-check stock_items table columns for description, badge, is_visible, and gallery_images
try {
    $cols = $conn->query("SHOW COLUMNS FROM stock_items")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('description', $cols)) {
        $conn->exec("ALTER TABLE stock_items ADD COLUMN description TEXT NULL");
    }
    if (!in_array('badge', $cols)) {
        $conn->exec("ALTER TABLE stock_items ADD COLUMN badge VARCHAR(50) DEFAULT ''");
    }
    if (!in_array('is_visible', $cols)) {
        $conn->exec("ALTER TABLE stock_items ADD COLUMN is_visible TINYINT(1) NOT NULL DEFAULT 1");
    }
    if (!in_array('gallery_images', $cols)) {
        $conn->exec("ALTER TABLE stock_items ADD COLUMN gallery_images TEXT NULL");
    }
} catch (Exception $e) {
    // Columns already exist or error ignored
}

// Normalize base_size_prices entries (supports show_men, show_women, special_note, promo_price, badge & legacy men_strike migration)
function normalizeBaseSizePrices($rawSizes) {
    if (!is_array($rawSizes)) {
        return [];
    }
    $defaultVisibility = [
        '2x3'   => ['show_men' => false, 'show_women' => true],
        '2x4'   => ['show_men' => false, 'show_women' => true],
        '3x4'   => ['show_men' => false, 'show_women' => true],
        '4x4'   => ['show_men' => false, 'show_women' => true],
        '5x5'   => ['show_men' => false, 'show_women' => true],
        '6x6'   => ['show_men' => false, 'show_women' => true],
        '6x7'   => ['show_men' => true,  'show_women' => false],
        '6x8'   => ['show_men' => true,  'show_women' => false],
        '7x9'   => ['show_men' => true,  'show_women' => false],
        '8x10'  => ['show_men' => true,  'show_women' => false],
        '10x12' => ['show_men' => true,  'show_women' => true],
    ];

    $normalized = [];
    foreach ($rawSizes as $key => $entry) {
        $cleanKey = strtolower(trim((string)$key));
        if (!preg_match('/^\d+(\.\d+)?x\d+(\.\d+)?$/', $cleanKey) || !is_array($entry)) {
            continue;
        }
        $men = isset($entry['men']) && $entry['men'] !== '' ? (float)$entry['men'] : 0;
        $women = isset($entry['women']) && $entry['women'] !== '' ? (float)$entry['women'] : 0;

        $defVis = $defaultVisibility[$cleanKey] ?? ['show_men' => true, 'show_women' => true];
        $showMen = array_key_exists('show_men', $entry) ? filter_var($entry['show_men'], FILTER_VALIDATE_BOOLEAN) : $defVis['show_men'];
        $showWomen = array_key_exists('show_women', $entry) ? filter_var($entry['show_women'], FILTER_VALIDATE_BOOLEAN) : $defVis['show_women'];

        $hasPromoField = array_key_exists('promo_price', $entry);
        $promoPrice = ($hasPromoField && $entry['promo_price'] !== '' && $entry['promo_price'] !== null && (float)$entry['promo_price'] > 0)
            ? (float)$entry['promo_price']
            : null;
        $badge = isset($entry['badge']) ? trim((string)$entry['badge']) : '';
        $specialNote = isset($entry['special_note']) ? trim((string)$entry['special_note']) : '';

        // One-time graceful migration from legacy 7x9 { men: 680, men_strike: 700 }
        if (!$hasPromoField && !empty($entry['men_strike']) && (float)$entry['men_strike'] > $men && $men > 0) {
            $promoPrice = $men;
            $men = (float)$entry['men_strike'];
            if ($badge === '') {
                $badge = 'Limited Special';
            }
            if ($specialNote === '' && $cleanKey === '7x9') {
                $specialNote = "Men's Standard Special Promo";
            }
        }

        $item = [
            'men' => $men,
            'women' => $women,
            'show_men' => (bool)$showMen,
            'show_women' => (bool)$showWomen,
            'promo_price' => $promoPrice,
            'badge' => $badge,
            'special_note' => $specialNote,
        ];
        // Keep legacy men_strike field synced when promo_price is lower than regular men's price
        if ($promoPrice !== null && $promoPrice > 0 && $men > $promoPrice) {
            $item['men_strike'] = $men;
        }
        $normalized[$cleanKey] = $item;
    }

    // Sort cleanly by combined size sum (W+L), then W
    uksort($normalized, function ($a, $b) {
        $pa = array_map('floatval', explode('x', $a));
        $pb = array_map('floatval', explode('x', $b));
        $sumA = ($pa[0] ?? 0) + ($pa[1] ?? 0);
        $sumB = ($pb[0] ?? 0) + ($pb[1] ?? 0);
        if ($sumA != $sumB) return $sumA <=> $sumB;
        return ($pa[0] ?? 0) <=> ($pb[0] ?? 0);
    });

    return $normalized;
}

// Normalize indexed option lists (Cap Construction & Hair Density factors)
function normalizeFactorOptions($rawList, $defaultList) {
    if (!is_array($rawList) || count($rawList) === 0) {
        return $defaultList;
    }
    $clean = [];
    foreach ($rawList as $idx => $item) {
        if (!is_array($item)) continue;
        $label = trim((string)($item['label'] ?? ''));
        if ($label === '') continue;
        $factor = isset($item['factor']) && is_numeric($item['factor']) ? round((float)$item['factor'], 2) : 1.0;
        if ($factor <= 0) $factor = 1.0;
        $id = trim((string)($item['id'] ?? ''));
        if ($id === '') {
            $id = 'opt_' . ($idx + 1) . '_' . substr(md5($label), 0, 6);
        }
        $clean[] = [
            'id' => $id,
            'label' => $label,
            'factor' => $factor,
            'is_default' => !empty($item['is_default'])
        ];
    }
    return count($clean) > 0 ? $clean : $defaultList;
}

// Auto-create calculator_pricing table & seed default pricing
function getDefaultCalculatorPricing() {
    return [
        'retail_multiplier' => 3.0,
        'custom_fee_aud' => 200,
        'base_size_prices' => [
            '2x3'   => ['men' => 250, 'women' => 250, 'show_men' => false, 'show_women' => true,  'promo_price' => null, 'badge' => '', 'special_note' => ''],
            '2x4'   => ['men' => 280, 'women' => 300, 'show_men' => false, 'show_women' => true,  'promo_price' => null, 'badge' => '', 'special_note' => ''],
            '3x4'   => ['men' => 350, 'women' => 350, 'show_men' => false, 'show_women' => true,  'promo_price' => null, 'badge' => '', 'special_note' => ''],
            '4x4'   => ['men' => 400, 'women' => 500, 'show_men' => false, 'show_women' => true,  'promo_price' => null, 'badge' => '', 'special_note' => ''],
            '5x5'   => ['men' => 450, 'women' => 550, 'show_men' => false, 'show_women' => true,  'promo_price' => null, 'badge' => '', 'special_note' => ''],
            '6x6'   => ['men' => 500, 'women' => 600, 'show_men' => false, 'show_women' => true,  'promo_price' => null, 'badge' => '', 'special_note' => ''],
            '6x7'   => ['men' => 600, 'women' => 600, 'show_men' => true,  'show_women' => false, 'promo_price' => null, 'badge' => '', 'special_note' => ''],
            '6x8'   => ['men' => 600, 'women' => 600, 'show_men' => true,  'show_women' => false, 'promo_price' => null, 'badge' => '', 'special_note' => ''],
            '7x9'   => ['men' => 700, 'women' => 650, 'show_men' => true,  'show_women' => false, 'promo_price' => 680, 'badge' => 'Limited Special', 'special_note' => "Men's Standard Special Promo", 'men_strike' => 700],
            '8x10'  => ['men' => 700, 'women' => 700, 'show_men' => true,  'show_women' => false, 'promo_price' => null, 'badge' => '', 'special_note' => ''],
            '10x12' => ['men' => 800, 'women' => 1000, 'show_men' => true, 'show_women' => true,  'promo_price' => null, 'badge' => '', 'special_note' => '']
        ],
        'cap_constructions' => [
            ['id' => 'pu_swiss_net',        'label' => 'PU+Swiss Net (Standard)',            'factor' => 1.0, 'is_default' => true],
            ['id' => 'mpu_pu_swiss',        'label' => 'MPU+PU+Swiss Net',                   'factor' => 1.1, 'is_default' => false],
            ['id' => 'mpu_pu_part_swiss',   'label' => 'MPU+PU+Part+Swiss Net',              'factor' => 1.3, 'is_default' => false],
            ['id' => 'mpu_pu_bpt_swiss',    'label' => 'MPU+PU+BPT Part + Swiss Net',        'factor' => 1.5, 'is_default' => false],
            ['id' => 'lacefront_pu_swiss',  'label' => 'LaceFront+PU+Swiss Net',             'factor' => 1.2, 'is_default' => false],
            ['id' => 'lacefront_pu_part',   'label' => 'LaceFront+PU+Part+Swiss Net',        'factor' => 1.4, 'is_default' => false],
            ['id' => 'lacefront_pu_bpt',    'label' => 'LaceFront+PU+BPT Part + Swiss Net',  'factor' => 1.6, 'is_default' => false],
            ['id' => 'full_hd_lace',        'label' => 'Full HD Lace Net',                   'factor' => 1.3, 'is_default' => false],
            ['id' => 'full_injection_skin', 'label' => 'Full Injection Skin',                'factor' => 2.0, 'is_default' => false],
            ['id' => 'full_bpt_top',        'label' => 'Full BPT Top',                       'factor' => 2.0, 'is_default' => false]
        ],
        'hair_densities' => [
            ['id' => 'density_80',  'label' => '80% Light Natural (Standard)', 'factor' => 1.0, 'is_default' => true],
            ['id' => 'density_90',  'label' => '90% Medium-Light',             'factor' => 1.1, 'is_default' => false],
            ['id' => 'density_100', 'label' => '100% Medium',                  'factor' => 1.2, 'is_default' => false],
            ['id' => 'density_110', 'label' => '110% Medium-Heavy',            'factor' => 1.3, 'is_default' => false],
            ['id' => 'density_120', 'label' => '120% Heavy Full',              'factor' => 1.4, 'is_default' => false],
            ['id' => 'density_130', 'label' => '130% Extra Heavy',             'factor' => 1.5, 'is_default' => false]
        ],
        'length_weights' => [
            '6' => 1.0,
            '8' => 1.5,
            '10' => 1.8,
            '12' => 2.0,
            '14' => 4.0,
            '16' => 6.0,
            '18' => 8.0,
            '20' => 10.0
        ],
        'hair_type_surcharges_usd' => [
            'standard' => 0,
            'remy' => 40,
            'virgin' => 90
        ],
        'texture_prices_aud' => [
            'straight' => 0,
            'natural_wave' => 10,
            'body_wave' => 30,
            'deep_wave' => 50,
            'curl' => 50,
            'tight_curl' => 100
        ],
        'grey_hair_step_aud' => 10,
        'salon_services_aud' => [
            'install_cut_in' => 150,
            'bonding_daily_clip' => 30,
            'bonding_tape_2_3w' => 50,
            'bonding_tape_lace_3_4w' => 70,
            'bonding_full_glue_4_5w' => 90,
            'maint_hair_cut' => 20,
            'maint_shampoo' => 20,
            'maint_unit_treatment' => 30,
            'maint_colour' => 50,
            'maint_perm' => 50
        ],
        'craftsmanship_aud' => [
            'bleached_knots' => 35,
            'root_shadowing' => 40,
            'rush_production' => 80
        ]
    ];
}

try {
    $conn->exec("CREATE TABLE IF NOT EXISTS calculator_pricing (
        id INT AUTO_INCREMENT PRIMARY KEY,
        config_key VARCHAR(64) NOT NULL UNIQUE,
        config_json LONGTEXT NOT NULL,
        updated_by VARCHAR(100) DEFAULT 'admin',
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $checkPricing = $conn->query("SELECT COUNT(*) FROM calculator_pricing WHERE config_key = 'main_pricing'");
    if ($checkPricing->fetchColumn() == 0) {
        $defaultJson = json_encode(getDefaultCalculatorPricing(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $insPricing = $conn->prepare("INSERT INTO calculator_pricing (config_key, config_json, updated_by) VALUES ('main_pricing', :json, 'system')");
        $insPricing->execute([':json' => $defaultJson]);
    }
} catch (Exception $e) {
    // Ignore table exists or initialization errors
}

$action = $_REQUEST['action'] ?? 'list';

try {
    // 1. LOGIN ACTION (with IP brute-force rate limiting: max 5 failed attempts / 15 mins)
    if ($action === 'login') {
        $clientIp = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        try {
            $conn->exec("CREATE TABLE IF NOT EXISTS admin_login_attempts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                ip_address VARCHAR(45) NOT NULL,
                attempted_email VARCHAR(190) DEFAULT '',
                attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_ip_time (ip_address, attempted_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            // Prune attempts older than 24h
            $conn->exec("DELETE FROM admin_login_attempts WHERE attempted_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)");

            // Check failed count in last 15 minutes
            $chkRate = $conn->prepare("SELECT COUNT(*) FROM admin_login_attempts WHERE ip_address = :ip AND attempted_at >= DATE_SUB(NOW(), INTERVAL 15 MINUTE)");
            $chkRate->execute([':ip' => $clientIp]);
            if ((int)$chkRate->fetchColumn() >= 5) {
                sendJsonResponse(['status' => 'error', 'message' => 'Too many failed login attempts. Please wait 15 minutes before trying again.'], 429);
            }
        } catch (Exception $rateEx) {
            // Never block login if rate-limit table init fails
        }

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            sendJsonResponse(['status' => 'error', 'message' => 'Please enter both Admin ID and Password.']);
        }

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            try {
                $clr = $conn->prepare("DELETE FROM admin_login_attempts WHERE ip_address = :ip");
                $clr->execute([':ip' => $clientIp]);
            } catch (Exception $e) {}
            session_regenerate_id(true);
            $_SESSION['admin_user'] = $user['email'];
            $_SESSION['admin_role'] = $user['role'];
            sendJsonResponse([
                'status' => 'success',
                'message' => 'Login successful.',
                'user' => $user['email']
            ]);
        } else {
            try {
                $insFail = $conn->prepare("INSERT INTO admin_login_attempts (ip_address, attempted_email) VALUES (:ip, :email)");
                $insFail->execute([':ip' => $clientIp, ':email' => substr($email, 0, 190)]);
            } catch (Exception $e) {}
            sendJsonResponse(['status' => 'error', 'message' => 'Invalid Admin ID or Password.']);
        }
    }

    // 2. LOGOUT ACTION
    if ($action === 'logout') {
        unset($_SESSION['admin_user']);
        unset($_SESSION['admin_role']);
        session_destroy();
        sendJsonResponse(['status' => 'success', 'message' => 'Logged out successfully.']);
    }

    // 3. CHECK AUTH ACTION
    if ($action === 'check_auth') {
        if (!empty($_SESSION['admin_user'])) {
            sendJsonResponse(['status' => 'authenticated', 'user' => $_SESSION['admin_user']]);
        } else {
            sendJsonResponse(['status' => 'unauthenticated']);
        }
    }

    // 4. PUBLIC LIST ACTION (STOCK ITEMS)
    if ($action === 'list') {
        $stmt = $conn->query("SELECT * FROM stock_items ORDER BY id DESC");
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($items as &$item) {
            if (!empty($item['gallery_images'])) {
                $decoded = json_decode($item['gallery_images'], true);
                $item['gallery_images'] = is_array($decoded) ? $decoded : (!empty($item['image']) ? [$item['image']] : []);
            } else {
                $item['gallery_images'] = !empty($item['image']) ? [$item['image']] : [];
            }
        }
        unset($item);
        sendJsonResponse(['status' => 'success', 'data' => $items]);
    }

    // 5. PUBLIC LIST AI HAIRSTYLES ACTION
    if ($action === 'list_ai_styles') {
        $stmt = $conn->query("SELECT * FROM ai_hairstyles ORDER BY sort_order ASC, id ASC");
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($items as &$it) {
            $g = strtolower(trim($it['gender'] ?? ''));
            if ($g === 'women' || $g === 'woman' || str_contains($g, 'wom')) {
                $it['category'] = 'Woman';
                $it['gender'] = 'Women';
            } else {
                $it['category'] = 'Man';
                $it['gender'] = 'Men';
            }
        }
        unset($it);
        sendJsonResponse(['status' => 'success', 'data' => $items]);
    }

    // 6. PUBLIC GET CALCULATOR PRICING
    if ($action === 'get_calculator_pricing') {
        $stmt = $conn->prepare("SELECT config_json, updated_at FROM calculator_pricing WHERE config_key = 'main_pricing' LIMIT 1");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && !empty($row['config_json'])) {
            $data = json_decode($row['config_json'], true);
            if ($data) {
                $defaults = getDefaultCalculatorPricing();
                if (isset($data['base_size_prices']) && is_array($data['base_size_prices'])) {
                    $data['base_size_prices'] = normalizeBaseSizePrices($data['base_size_prices']);
                }
                $data['cap_constructions'] = normalizeFactorOptions($data['cap_constructions'] ?? null, $defaults['cap_constructions']);
                $data['hair_densities'] = normalizeFactorOptions($data['hair_densities'] ?? null, $defaults['hair_densities']);
                sendJsonResponse([
                    'status' => 'success',
                    'pricing' => $data,
                    'updated_at' => $row['updated_at']
                ]);
            }
        }

        // Fallback to default pricing
        $defaults = getDefaultCalculatorPricing();
        sendJsonResponse([
            'status' => 'success',
            'pricing' => $defaults,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    // 6B. PUBLIC LIST CAP GUIDE ITEMS (Buyer's & Stylist's Guide: Men's Toupees & Women's Toppers)
    if ($action === 'list_cap_guide') {
        $includeHidden = !empty($_REQUEST['include_hidden']) && !empty($_SESSION['admin_user']);
        $sql = "SELECT * FROM cap_guide_items";
        if (!$includeHidden) {
            $sql .= " WHERE is_visible = 1";
        }
        $sql .= " ORDER BY FIELD(category, 'men', 'women'), sort_order ASC, id ASC";
        $stmt = $conn->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $men = [];
        $women = [];
        foreach ($rows as &$r) {
            $r['id'] = (int)$r['id'];
            $r['realism'] = max(1, min(5, (int)$r['realism']));
            $r['breathability'] = max(1, min(5, (int)$r['breathability']));
            $r['sort_order'] = (int)$r['sort_order'];
            $r['is_visible'] = (int)$r['is_visible'];
            if (strtolower($r['category']) === 'women') {
                $r['category'] = 'women';
                $women[] = $r;
            } else {
                $r['category'] = 'men';
                $men[] = $r;
            }
        }
        unset($r);

        sendJsonResponse([
            'status' => 'success',
            'data' => $rows,
            'men' => $men,
            'women' => $women
        ]);
    }

    // AUTHENTICATION GUARD FOR WRITE OPERATIONS (save, delete)
    if (empty($_SESSION['admin_user'])) {
        sendJsonResponse(['status' => 'error', 'message' => 'Admin authentication required. Please login.'], 401);
    }

    // 6. TOGGLE STOCK ITEM VISIBILITY (SHOW / HIDE ON MAIN PAGE)
    if ($action === 'toggle_visibility') {
        $id = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($id <= 0) {
            sendJsonResponse(['status' => 'error', 'message' => 'Invalid product ID.'], 400);
        }

        if (isset($_REQUEST['is_visible'])) {
            $newVal = intval($_REQUEST['is_visible']) ? 1 : 0;
            $stmt = $conn->prepare("UPDATE stock_items SET is_visible = :vis WHERE id = :id");
            $stmt->execute([':vis' => $newVal, ':id' => $id]);
        } else {
            $stmt = $conn->prepare("UPDATE stock_items SET is_visible = IF(is_visible = 1, 0, 1) WHERE id = :id");
            $stmt->execute([':id' => $id]);
        }

        $stmt = $conn->prepare("SELECT is_visible FROM stock_items WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $currentVis = (int)$stmt->fetchColumn();

        sendJsonResponse([
            'status' => 'success',
            'message' => $currentVis ? 'Product is now visible on main page.' : 'Product is now hidden from main page.',
            'id' => $id,
            'is_visible' => $currentVis
        ]);
    }

    // 6.5. UPLOAD STOCK IMAGES (MULTIPLE FILES)
    if ($action === 'upload_stock_images') {
        $uploadedUrls = [];
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (!empty($_FILES['images'])) {
            $files = $_FILES['images'];
            $names = is_array($files['name']) ? $files['name'] : [$files['name']];
            $tmps = is_array($files['tmp_name']) ? $files['tmp_name'] : [$files['tmp_name']];
            $errors = is_array($files['error']) ? $files['error'] : [$files['error']];

            for ($i = 0; $i < count($names); $i++) {
                if ($errors[$i] === UPLOAD_ERR_OK && is_uploaded_file($tmps[$i])) {
                    $ext = strtolower(pathinfo($names[$i], PATHINFO_EXTENSION));
                    if (in_array($ext, $allowedExts)) {
                        $newFileName = 'stock_' . time() . '_' . rand(1000, 9999) . '_' . $i . '.' . $ext;
                        $targetPath = $uploadDir . $newFileName;
                        if (move_uploaded_file($tmps[$i], $targetPath)) {
                            $uploadedUrls[] = '/uploads/' . $newFileName;
                        }
                    }
                }
            }
        }

        if (!empty($uploadedUrls)) {
            sendJsonResponse([
                'status' => 'success',
                'message' => count($uploadedUrls) . ' image(s) uploaded successfully.',
                'urls' => $uploadedUrls
            ]);
        } else {
            sendJsonResponse(['status' => 'error', 'message' => 'No valid images were uploaded.'], 400);
        }
    }

    // 7. SAVE STOCK ITEM
    if ($action === 'save') {
        $id = !empty($_POST['id']) ? intval($_POST['id']) : null;
        $sku = trim($_POST['sku'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? "Men's Toupee");
        $type = trim($_POST['type'] ?? "Skin Systems");
        $base = trim($_POST['base'] ?? '');
        $size = trim($_POST['size'] ?? '8x10"');
        $density = trim($_POST['density'] ?? '90%');
        $color = trim($_POST['color'] ?? '#1B Off Black');
        $price = floatval($_POST['price'] ?? 0);
        $stockQty = intval($_POST['stockQty'] ?? 1);
        $imageUrl = trim($_POST['image_url'] ?? '');
        $rawGallery = $_POST['gallery_images'] ?? '';

        $galleryList = [];
        if (!empty($rawGallery)) {
            if (is_array($rawGallery)) {
                $galleryList = $rawGallery;
            } else {
                $decoded = json_decode($rawGallery, true);
                if (is_array($decoded)) {
                    $galleryList = $decoded;
                }
            }
        }

        // Handle Image File Upload (single legacy)
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $_FILES['image_file']['tmp_name'];
            $fileName = $_FILES['image_file']['name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

            if (in_array($fileExt, $allowedExts)) {
                $newFileName = 'stock_' . time() . '_' . rand(1000, 9999) . '.' . $fileExt;
                $targetPath = $uploadDir . $newFileName;
                if (move_uploaded_file($fileTmp, $targetPath)) {
                    $newUrl = '/uploads/' . $newFileName;
                    $imageUrl = $newUrl;
                    if (!in_array($newUrl, $galleryList)) {
                        array_unshift($galleryList, $newUrl);
                    }
                }
            }
        }

        // Handle multiple gallery files upload during save if attached
        if (!empty($_FILES['gallery_files'])) {
            $files = $_FILES['gallery_files'];
            $names = is_array($files['name']) ? $files['name'] : [$files['name']];
            $tmps = is_array($files['tmp_name']) ? $files['tmp_name'] : [$files['tmp_name']];
            $errors = is_array($files['error']) ? $files['error'] : [$files['error']];
            for ($i = 0; $i < count($names); $i++) {
                if ($errors[$i] === UPLOAD_ERR_OK && is_uploaded_file($tmps[$i])) {
                    $ext = strtolower(pathinfo($names[$i], PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                        $newFileName = 'stock_' . time() . '_' . rand(1000, 9999) . '_' . $i . '.' . $ext;
                        if (move_uploaded_file($tmps[$i], $uploadDir . $newFileName)) {
                            $galleryList[] = '/uploads/' . $newFileName;
                        }
                    }
                }
            }
        }

        // Sanitize gallery list (remove empty/duplicates)
        $cleanGallery = [];
        foreach ($galleryList as $g) {
            $g = trim($g);
            if (!empty($g) && !in_array($g, $cleanGallery)) {
                $cleanGallery[] = $g;
            }
        }

        // Ensure primary image exists and is in gallery
        if (!empty($imageUrl)) {
            if (!in_array($imageUrl, $cleanGallery)) {
                array_unshift($cleanGallery, $imageUrl);
            }
        } elseif (!empty($cleanGallery)) {
            $imageUrl = $cleanGallery[0];
        }

        $galleryJson = !empty($cleanGallery) ? json_encode($cleanGallery, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : null;

        $description = trim($_POST['description'] ?? '');
        $badge = trim($_POST['badge'] ?? '');
        $is_visible = isset($_POST['is_visible']) ? (intval($_POST['is_visible']) ? 1 : 0) : 1;

        if (empty($sku) || empty($name)) {
            sendJsonResponse(['status' => 'error', 'message' => 'SKU and Name are required fields.']);
        }

        if ($id) {
            $sql = "UPDATE stock_items SET sku = :sku, name = :name, category = :category, type = :type, base = :base, size = :size, density = :density, color = :color, price = :price, stockQty = :stockQty, description = :description, badge = :badge, is_visible = :is_visible, gallery_images = :gallery_images";
            if (!empty($imageUrl)) {
                $sql .= ", image = :image";
            }
            $sql .= " WHERE id = :id";

            $stmt = $conn->prepare($sql);
            $params = [
                ':sku' => $sku, ':name' => $name, ':category' => $category, ':type' => $type,
                ':base' => $base, ':size' => $size, ':density' => $density, ':color' => $color,
                ':price' => $price, ':stockQty' => $stockQty, ':description' => $description, ':badge' => $badge,
                ':is_visible' => $is_visible,
                ':gallery_images' => $galleryJson,
                ':id' => $id
            ];
            if (!empty($imageUrl)) {
                $params[':image'] = $imageUrl;
            }
            $stmt->execute($params);
            sendJsonResponse(['status' => 'success', 'message' => 'Stock item updated successfully.']);
        } else {
            if (empty($imageUrl)) {
                $imageUrl = 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=600&q=80';
            }
            if (empty($galleryJson)) {
                $galleryJson = json_encode([$imageUrl], JSON_UNESCAPED_SLASHES);
            }
            $stmt = $conn->prepare("INSERT INTO stock_items (sku, name, category, type, base, size, density, color, price, stockQty, image, description, badge, is_visible, gallery_images) VALUES (:sku, :name, :category, :type, :base, :size, :density, :color, :price, :stockQty, :image, :description, :badge, :is_visible, :gallery_images)");
            $stmt->execute([
                ':sku' => $sku, ':name' => $name, ':category' => $category, ':type' => $type,
                ':base' => $base, ':size' => $size, ':density' => $density, ':color' => $color,
                ':price' => $price, ':stockQty' => $stockQty, ':image' => $imageUrl,
                ':description' => $description, ':badge' => $badge,
                ':is_visible' => $is_visible,
                ':gallery_images' => $galleryJson
            ]);
            sendJsonResponse(['status' => 'success', 'message' => 'New stock item created successfully.']);
        }
    }

    // 7. DELETE STOCK ITEM
    if ($action === 'delete') {
        $id = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM stock_items WHERE id = :id");
            $stmt->execute([':id' => $id]);
            sendJsonResponse(['status' => 'success', 'message' => 'Stock item deleted successfully.']);
        } else {
            sendJsonResponse(['status' => 'error', 'message' => 'Invalid ID.']);
        }
    }

    // 8. SAVE AI SIMULATOR HAIRSTYLE
    if ($action === 'save_ai_style') {
        $id = !empty($_POST['id']) ? intval($_POST['id']) : null;
        $name = trim($_POST['name'] ?? '');
        $tag = trim($_POST['tag'] ?? '');
        $gender = trim($_POST['gender'] ?? 'Men');
        $overlayUrl = trim($_POST['overlay_url'] ?? '');

        // Check for File Upload
        if (isset($_FILES['overlay_file']) && $_FILES['overlay_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['overlay_file']['error'] !== UPLOAD_ERR_OK) {
                $errCodes = [
                    1 => 'File size exceeds PHP upload_max_filesize limit.',
                    2 => 'File size exceeds MAX_FILE_SIZE directive.',
                    3 => 'File was only partially uploaded.',
                    6 => 'Missing temporary folder on server.',
                    7 => 'Failed to write uploaded file to disk.',
                    8 => 'PHP extension stopped the file upload.'
                ];
                $errDetail = $errCodes[$_FILES['overlay_file']['error']] ?? ('Code ' . $_FILES['overlay_file']['error']);
                sendJsonResponse(['status' => 'error', 'message' => 'Upload Error: ' . $errDetail]);
            }

            $fileTmp = $_FILES['overlay_file']['tmp_name'];
            $fileName = $_FILES['overlay_file']['name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'svg'];

            if (!in_array($fileExt, $allowedExts)) {
                sendJsonResponse(['status' => 'error', 'message' => 'Invalid file extension (.' . $fileExt . '). Allowed: JPG, PNG, WEBP, SVG.']);
            }

            $newFileName = 'aistyle_' . time() . '_' . rand(1000, 9999) . '.' . $fileExt;
            $targetPath = $uploadDir . $newFileName;
            
            if (move_uploaded_file($fileTmp, $targetPath)) {
                $overlayUrl = '/uploads/' . $newFileName;
            } else {
                sendJsonResponse(['status' => 'error', 'message' => 'Failed to save file to /uploads/ folder. Check folder permissions.']);
            }
        }

        if (empty($name)) {
            sendJsonResponse(['status' => 'error', 'message' => 'Hairstyle Name is required.']);
        }

        if ($id) {
            $sql = "UPDATE ai_hairstyles SET name = :name, tag = :tag, gender = :gender";
            $params = [':name' => $name, ':tag' => $tag, ':gender' => $gender];
            if (!empty($overlayUrl)) {
                $sql .= ", overlay = :overlay";
                $params[':overlay'] = $overlayUrl;
            }
            $sql .= " WHERE id = :id";
            $params[':id'] = $id;

            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            sendJsonResponse(['status' => 'success', 'message' => 'AI Hairstyle updated successfully.', 'overlay' => $overlayUrl]);
        } else {
            if (empty($overlayUrl)) {
                sendJsonResponse(['status' => 'error', 'message' => 'Transparent PNG/SVG overlay file or URL is required.']);
            }
            $stmt = $conn->prepare("INSERT INTO ai_hairstyles (name, tag, gender, overlay) VALUES (:name, :tag, :gender, :overlay)");
            $stmt->execute([':name' => $name, ':tag' => $tag, ':gender' => $gender, ':overlay' => $overlayUrl]);
            sendJsonResponse(['status' => 'success', 'message' => 'New AI Hairstyle registered successfully.', 'overlay' => $overlayUrl]);
        }
    }

    // 9. DELETE AI SIMULATOR HAIRSTYLE
    if ($action === 'delete_ai_style') {
        $id = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM ai_hairstyles WHERE id = :id");
            $stmt->execute([':id' => $id]);
            sendJsonResponse(['status' => 'success', 'message' => 'AI Hairstyle deleted successfully.']);
        } else {
            sendJsonResponse(['status' => 'error', 'message' => 'Invalid ID.']);
        }
    }



    // 11. SAVE CALCULATOR PRICING (Admin only)
    if ($action === 'save_calculator_pricing') {
        if (empty($_SESSION['admin_user'])) {
            sendJsonResponse(['status' => 'error', 'message' => 'Unauthorized: Admin login required.'], 401);
        }

        $rawInput = file_get_contents('php://input');
        $pricingData = null;

        if (!empty($rawInput)) {
            $jsonDecoded = json_decode($rawInput, true);
            if (isset($jsonDecoded['pricing'])) {
                $pricingData = $jsonDecoded['pricing'];
            } else if (is_array($jsonDecoded)) {
                $pricingData = $jsonDecoded;
            }
        }

        if (!$pricingData && !empty($_POST['pricing'])) {
            $pricingData = is_array($_POST['pricing']) ? $_POST['pricing'] : json_decode($_POST['pricing'], true);
        }

        if (empty($pricingData) || !is_array($pricingData)) {
            sendJsonResponse(['status' => 'error', 'message' => 'Invalid pricing payload provided.']);
        }

        // Merge with defaults for other config keys, but preserve exact base_size_prices, cap_constructions, and hair_densities
        $defaults = getDefaultCalculatorPricing();
        $incomingBaseSizes = (isset($pricingData['base_size_prices']) && is_array($pricingData['base_size_prices']))
            ? normalizeBaseSizePrices($pricingData['base_size_prices'])
            : null;
        $incomingCaps = (isset($pricingData['cap_constructions']) && is_array($pricingData['cap_constructions']))
            ? normalizeFactorOptions($pricingData['cap_constructions'], $defaults['cap_constructions'])
            : null;
        $incomingDensities = (isset($pricingData['hair_densities']) && is_array($pricingData['hair_densities']))
            ? normalizeFactorOptions($pricingData['hair_densities'], $defaults['hair_densities'])
            : null;

        $mergedPricing = array_replace_recursive($defaults, $pricingData);
        if ($incomingBaseSizes !== null) {
            $mergedPricing['base_size_prices'] = $incomingBaseSizes;
        } else {
            $mergedPricing['base_size_prices'] = normalizeBaseSizePrices($mergedPricing['base_size_prices']);
        }
        $mergedPricing['cap_constructions'] = $incomingCaps !== null ? $incomingCaps : $defaults['cap_constructions'];
        $mergedPricing['hair_densities'] = $incomingDensities !== null ? $incomingDensities : $defaults['hair_densities'];

        $jsonStr = json_encode($mergedPricing, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $user = $_SESSION['admin_user'] ?? 'admin';

        $stmt = $conn->prepare("INSERT INTO calculator_pricing (config_key, config_json, updated_by)
                                VALUES ('main_pricing', :json, :user)
                                ON DUPLICATE KEY UPDATE config_json = VALUES(config_json), updated_by = VALUES(updated_by)");
        $stmt->execute([':json' => $jsonStr, ':user' => $user]);

        sendJsonResponse([
            'status' => 'success',
            'message' => 'Calculator pricing updated successfully.',
            'pricing' => $mergedPricing,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    // 12. RESET CALCULATOR PRICING TO DEFAULT (Admin only)
    if ($action === 'reset_calculator_pricing') {
        if (empty($_SESSION['admin_user'])) {
            sendJsonResponse(['status' => 'error', 'message' => 'Unauthorized: Admin login required.'], 401);
        }

        $defaults = getDefaultCalculatorPricing();
        $jsonStr = json_encode($defaults, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $user = $_SESSION['admin_user'] ?? 'admin';

        $stmt = $conn->prepare("INSERT INTO calculator_pricing (config_key, config_json, updated_by)
                                VALUES ('main_pricing', :json, :user)
                                ON DUPLICATE KEY UPDATE config_json = VALUES(config_json), updated_by = VALUES(updated_by)");
        $stmt->execute([':json' => $jsonStr, ':user' => $user]);

        sendJsonResponse([
            'status' => 'success',
            'message' => 'Calculator pricing reset to factory defaults.',
            'pricing' => $defaults,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    // 13. SAVE CAP GUIDE ITEM (Add or Update — Admin only)
    if ($action === 'save_cap_guide') {
        $id = !empty($_POST['id']) ? intval($_POST['id']) : 0;
        $category = strtolower(trim($_POST['category'] ?? 'men')) === 'women' ? 'women' : 'men';
        $title = trim($_POST['title'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $imageUrl = trim($_POST['image_url'] ?? '');
        $modalSubtitle = trim($_POST['modal_subtitle'] ?? '');
        $modalDesc = trim($_POST['modal_desc'] ?? '');
        $realism = max(1, min(5, intval($_POST['realism'] ?? 4)));
        $breathability = max(1, min(5, intval($_POST['breathability'] ?? 4)));
        $lifespan = trim($_POST['lifespan'] ?? '');
        $lifespanTone = in_array($_POST['lifespan_tone'] ?? '', ['rose', 'amber', 'emerald', 'slate']) ? $_POST['lifespan_tone'] : 'slate';
        $annualCost = trim($_POST['annual_cost'] ?? '');
        $annualCostSub = trim($_POST['annual_cost_sub'] ?? '');
        $annualCostTone = in_array($_POST['annual_cost_tone'] ?? '', ['rose', 'amber', 'emerald', 'slate']) ? $_POST['annual_cost_tone'] : 'slate';
        $advantages = trim($_POST['advantages'] ?? '');
        $drawbacks = trim($_POST['drawbacks'] ?? '');
        $bestSuited = trim($_POST['best_suited'] ?? '');
        $sortOrder = isset($_POST['sort_order']) && $_POST['sort_order'] !== '' ? intval($_POST['sort_order']) : 0;
        $isVisible = isset($_POST['is_visible']) ? (filter_var($_POST['is_visible'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0) : 1;

        if (empty($title)) {
            sendJsonResponse(['status' => 'error', 'message' => 'Cap Design Title is required.'], 400);
        }

        // Optional Photo Upload
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['image_file']['error'] !== UPLOAD_ERR_OK) {
                sendJsonResponse(['status' => 'error', 'message' => 'Image upload error (code ' . $_FILES['image_file']['error'] . ').'], 400);
            }
            $fileTmp = $_FILES['image_file']['tmp_name'];
            $fileName = $_FILES['image_file']['name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($fileExt, $allowedExts)) {
                sendJsonResponse(['status' => 'error', 'message' => 'Invalid file format. Allowed: JPG, PNG, WEBP.'], 400);
            }
            $newFileName = 'capguide_' . time() . '_' . rand(1000, 9999) . '.' . $fileExt;
            $targetPath = $uploadDir . $newFileName;
            if (move_uploaded_file($fileTmp, $targetPath)) {
                $imageUrl = '/uploads/' . $newFileName;
            } else {
                sendJsonResponse(['status' => 'error', 'message' => 'Failed to save uploaded image to /uploads/.'], 500);
            }
        }

        if (empty($modalSubtitle)) {
            $modalSubtitle = ($category === 'women' ? "Women's Topper Base" : "Men's Toupee Base") . (!empty($subtitle) ? " • " . $subtitle : '');
        }
        if (empty($modalDesc) && !empty($advantages)) {
            $modalDesc = $advantages;
        }

        if ($id > 0) {
            // Keep existing image_url if none provided on update
            if (empty($imageUrl)) {
                $oldStmt = $conn->prepare("SELECT image_url FROM cap_guide_items WHERE id = :id");
                $oldStmt->execute([':id' => $id]);
                $imageUrl = (string)$oldStmt->fetchColumn();
            }
            $stmt = $conn->prepare("UPDATE cap_guide_items SET
                category = :category,
                title = :title,
                subtitle = :subtitle,
                image_url = :image_url,
                modal_subtitle = :modal_subtitle,
                modal_desc = :modal_desc,
                realism = :realism,
                breathability = :breathability,
                lifespan = :lifespan,
                lifespan_tone = :lifespan_tone,
                annual_cost = :annual_cost,
                annual_cost_sub = :annual_cost_sub,
                annual_cost_tone = :annual_cost_tone,
                advantages = :advantages,
                drawbacks = :drawbacks,
                best_suited = :best_suited,
                sort_order = :sort_order,
                is_visible = :is_visible
                WHERE id = :id");
            $stmt->execute([
                ':category' => $category,
                ':title' => $title,
                ':subtitle' => $subtitle,
                ':image_url' => $imageUrl,
                ':modal_subtitle' => $modalSubtitle,
                ':modal_desc' => $modalDesc,
                ':realism' => $realism,
                ':breathability' => $breathability,
                ':lifespan' => $lifespan,
                ':lifespan_tone' => $lifespanTone,
                ':annual_cost' => $annualCost,
                ':annual_cost_sub' => $annualCostSub,
                ':annual_cost_tone' => $annualCostTone,
                ':advantages' => $advantages,
                ':drawbacks' => $drawbacks,
                ':best_suited' => $bestSuited,
                ':sort_order' => $sortOrder,
                ':is_visible' => $isVisible,
                ':id' => $id,
            ]);
            sendJsonResponse(['status' => 'success', 'message' => 'Cap Guide item updated successfully.', 'id' => $id, 'image_url' => $imageUrl]);
        } else {
            if ($sortOrder === 0) {
                $maxOrder = (int)$conn->query("SELECT COALESCE(MAX(sort_order), 0) FROM cap_guide_items WHERE category = " . $conn->quote($category))->fetchColumn();
                $sortOrder = $maxOrder + 1;
            }
            $stmt = $conn->prepare("INSERT INTO cap_guide_items
                (category, title, subtitle, image_url, modal_subtitle, modal_desc, realism, breathability, lifespan, lifespan_tone, annual_cost, annual_cost_sub, annual_cost_tone, advantages, drawbacks, best_suited, sort_order, is_visible)
                VALUES (:category, :title, :subtitle, :image_url, :modal_subtitle, :modal_desc, :realism, :breathability, :lifespan, :lifespan_tone, :annual_cost, :annual_cost_sub, :annual_cost_tone, :advantages, :drawbacks, :best_suited, :sort_order, :is_visible)");
            $stmt->execute([
                ':category' => $category,
                ':title' => $title,
                ':subtitle' => $subtitle,
                ':image_url' => $imageUrl,
                ':modal_subtitle' => $modalSubtitle,
                ':modal_desc' => $modalDesc,
                ':realism' => $realism,
                ':breathability' => $breathability,
                ':lifespan' => $lifespan,
                ':lifespan_tone' => $lifespanTone,
                ':annual_cost' => $annualCost,
                ':annual_cost_sub' => $annualCostSub,
                ':annual_cost_tone' => $annualCostTone,
                ':advantages' => $advantages,
                ':drawbacks' => $drawbacks,
                ':best_suited' => $bestSuited,
                ':sort_order' => $sortOrder,
                ':is_visible' => $isVisible,
            ]);
            sendJsonResponse(['status' => 'success', 'message' => 'New Cap Guide item added successfully.', 'id' => (int)$conn->lastInsertId(), 'image_url' => $imageUrl]);
        }
    }

    // 14. TOGGLE CAP GUIDE VISIBILITY (Admin only)
    if ($action === 'toggle_cap_guide_visibility') {
        $id = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($id <= 0) {
            sendJsonResponse(['status' => 'error', 'message' => 'Invalid ID.'], 400);
        }
        $stmt = $conn->prepare("UPDATE cap_guide_items SET is_visible = IF(is_visible = 1, 0, 1) WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $visStmt = $conn->prepare("SELECT is_visible FROM cap_guide_items WHERE id = :id");
        $visStmt->execute([':id' => $id]);
        $newVis = (int)$visStmt->fetchColumn();
        sendJsonResponse([
            'status' => 'success',
            'is_visible' => $newVis,
            'message' => $newVis ? 'Guide item is now visible.' : 'Guide item is now hidden.'
        ]);
    }

    // 15. DELETE CAP GUIDE ITEM (Admin only)
    if ($action === 'delete_cap_guide') {
        $id = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($id <= 0) {
            sendJsonResponse(['status' => 'error', 'message' => 'Invalid ID.'], 400);
        }
        $stmt = $conn->prepare("DELETE FROM cap_guide_items WHERE id = :id");
        $stmt->execute([':id' => $id]);
        sendJsonResponse(['status' => 'success', 'message' => 'Cap Guide item deleted successfully.']);
    }

} catch (PDOException $e) {
    sendJsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
}
?>
