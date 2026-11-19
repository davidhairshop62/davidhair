<?php
$pageTitle       = 'AI Virtual Hairstyle Try-On Simulator | Preview Hair Systems | David Hair';
$pageDescription = "Try on realistic men's toupee & women's hair topper styles instantly using your photo or live camera before visiting our Melbourne salon.";
$pageKeywords    = 'virtual hair system try on, AI hairstyle simulator toupee, preview wig hair topper online melbourne';
$canonicalPath   = '/ai-tryon';
$activePage      = 'ai-simulator';
$extraHeadScripts = ['/assets/js/ai-tryon.js'];
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/subpage_bar.php';
?>
<main id="main-content" class="w-full min-h-screen">
<?php dh_render_subpage_top('AI Virtual Hairstyle Try-On', 'AI TECH'); ?>
<?php require __DIR__ . '/includes/ai_simulator_section.php'; ?>

<?php dh_render_subpage_bottom('ai-simulator'); ?>
</main>

<?php
require __DIR__ . '/includes/global_modals.php';
require __DIR__ . '/includes/footer.php';
?>
