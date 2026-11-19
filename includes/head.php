<?php
/**
 * Shared <head> & <body> opener for David Hair Melbourne multi-page hub architecture.
 * Expected variables (all optional with sensible defaults):
 *   $pageTitle, $pageDescription, $pageKeywords, $canonicalPath, $ogImage,
 *   $extraHeadScripts (array of JS paths loaded BEFORE Alpine.js),
 *   $extraJsonLd (raw JSON-LD string or null),
 *   $activePage (string key e.g. 'home', 'medical-wigs', 'ffa', 'solutions', 'stock', 'salon', 'transformations', 'ai-tryon', 'factory-b2b', 'diagnosis', 'faq')
 */
$pageTitle       = $pageTitle       ?? "Men's Toupees & Women's Hair Toppers Melbourne | Factory Direct | David Hair";
$pageDescription = $pageDescription ?? "Factory-direct 100% human hair men's toupees, partial hair systems & women's silk top toppers in Melbourne. \$0 private consultation & custom fitting at Springvale studio. From \$420 AUD.";
$pageKeywords    = $pageKeywords    ?? "men's toupee melbourne, women's hair topper melbourne, men's hair system melbourne, toupee fitting springvale, non-surgical hair replacement melbourne, human hair toupee australia, silk top toppers, medical wigs melbourne";
$canonicalPath   = $canonicalPath   ?? '/';
$canonicalUrl    = 'https://davidhairshop.com' . ($canonicalPath === '/' ? '/' : '/' . ltrim($canonicalPath, '/'));
$ogImage         = $ogImage         ?? 'https://davidhairshop.com/images/TransMan.webp';
$extraHeadScripts = $extraHeadScripts ?? [];
$extraJsonLd     = $extraJsonLd     ?? null;
$activePage      = $activePage      ?? 'home';
$hasStaticCss    = file_exists(__DIR__ . '/../assets/css/tailwind.min.css');
$assetVer        = '20261119b';
?>
<!DOCTYPE html>
<html lang="en-AU" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($pageKeywords, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="author" content="David Hair">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8'); ?>">

    <!-- Local SEO Geo Meta Tags -->
    <meta name="geo.region" content="AU-VIC">
    <meta name="geo.placename" content="Springvale, Melbourne">
    <meta name="geo.position" content="-37.9493;145.1537">
    <meta name="ICBM" content="-37.9493, 145.1537">

    <!-- Open Graph / WhatsApp / Facebook Preview -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:site_name" content="David Hair Melbourne">
    <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="David Hair Melbourne Men's &amp; Women's Hair Systems">
    <meta property="og:locale" content="en_AU">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8'); ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome & Tailwind CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<?php if ($hasStaticCss): ?>
    <link rel="stylesheet" href="/assets/css/tailwind.min.css?v=<?php echo $assetVer; ?>">
<?php else: ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                screens: { 'xs': '480px', 'sm': '640px', 'md': '768px', 'lg': '1024px', 'xl': '1280px' },
                extend: {
                    colors: {
                        brand: {
                            darkBg: '#0B132B', cardBg: '#1C2541', cardInner: '#151E38', border: '#2E3A59',
                            textMain: '#F1F5F9', textMuted: '#94A3B8', teal: '#0D9488', tealLight: '#14B8A6',
                            bronze: '#D97706', bronzeHover: '#B45309'
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif']
                    }
                }
            }
        };
    </script>
<?php endif; ?>

    <!-- Shared & Page-Specific Application Logic (Preloaded before Alpine.js) -->
    <script src="/assets/js/global-app.js?v=<?php echo $assetVer; ?>"></script>
<?php foreach ($extraHeadScripts as $scriptPath): ?>
    <script src="<?php echo htmlspecialchars($scriptPath, ENT_QUOTES, 'UTF-8'); ?>?v=<?php echo $assetVer; ?>"></script>
<?php endforeach; ?>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<?php if ($activePage === 'home'): ?>
    <!-- Schema.org LocalBusiness JSON-LD -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "HairSalon",
        "@id": "https://davidhairshop.com/#organization",
        "name": "David Hair - Men's Toupees & Women's Hair Toppers Melbourne",
        "alternateName": "David Hair Systems Australia",
        "url": "https://davidhairshop.com/",
        "logo": "https://davidhairshop.com/images/TransMan.webp",
        "image": [
            "https://davidhairshop.com/images/TransMan.webp",
            "https://davidhairshop.com/images/transwoman.webp",
            "https://davidhairshop.com/images/salon1.jpg"
        ],
        "description": "Factory-direct 100% human hair men's toupees, partial hair systems, medical wigs & women's silk top toppers in Melbourne. Private 1-on-1 custom fitting & barber cut-in at Springvale studio.",
        "telephone": "+61433484882",
        "email": "contact@davidhairshop.com",
        "priceRange": "$$ ($420 - $950 AUD)",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Shop F16 (Level 1), Buckingham Ave",
            "addressLocality": "Springvale",
            "addressRegion": "VIC",
            "postalCode": "3171",
            "addressCountry": "AU"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": -37.951084,
            "longitude": 145.150951
        },
        "openingHoursSpecification": [
            {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": ["Monday", "Tuesday", "Thursday", "Friday", "Saturday", "Sunday"],
                "opens": "10:30",
                "closes": "17:00"
            }
        ]
    }
    </script>
<?php endif; ?>
<?php if ($extraJsonLd): ?>
    <script type="application/ld+json">
<?php echo $extraJsonLd; ?>
    </script>
<?php endif; ?>

    <style>
        [x-cloak] { display: none !important; }
        .glass-nav {
            background: rgba(11, 19, 43, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        .teal-gradient { background: linear-gradient(135deg, #0D9488 0%, #0284C7 100%); }
        .bronze-gradient { background: linear-gradient(135deg, #D97706 0%, #B45309 100%); }
        .eye-shadow { box-shadow: 0 12px 30px -10px rgba(0, 0, 0, 0.4); }
        button, a, input, select { touch-action: manipulation; }
    </style>

    <!-- David Hair Visitor Analytics Tracker -->
    <script src="/tracker.js" defer></script>
</head>

<body
    class="bg-brand-darkBg text-brand-textMain font-sans antialiased selection:bg-brand-teal selection:text-white overflow-x-hidden"
    x-data="davidHairApp()"
    @open-booking-modal.window="openBookingModal()"
    @close-all-submodals.window="isFfaModalOpen = false; isDiagModalOpen = false; isCalculatorModalOpen = false; isComparisonModalOpen = false;"
    @close-ffa-modal.window="isFfaModalOpen = false"
    @close-diag-modal.window="isDiagModalOpen = false"
    @close-calculator-modal.window="isCalculatorModalOpen = false"
    @close-comparison-modal.window="isComparisonModalOpen = false"
    @close-gallery-modal.window="isGalleryModalOpen = false"
    @open-gallery-modal.window="openGalleryModal()"
    @open-comparison-modal.window="openComparisonModal()">

