<?php
/**
 * Shared subpage navigation bar (top back-to-home breadcrumb + bottom explore-more strip).
 */

function dh_get_section_directory(): array {
    return [
        [
            'key' => 'medical-wigs',
            'url' => '/medical-wigs',
            'title' => 'Medical Wigs & Alopecia Solutions',
            'short' => 'Medical Wigs',
            'subtitle' => '100% human hair cranial wigs • $250–$600+ Private Health Fund Rebate & NDIS invoice',
            'icon' => 'fa-solid fa-hand-holding-heart',
            'badge' => 'REBATE',
            'accent' => 'rose',
            'order' => 2,
        ],
        [
            'key' => 'frontal-fibrosing-alopecia',
            'url' => '/ffa',
            'title' => 'Frontal Fibrosing Alopecia (FFA)',
            'short' => 'FFA Fringe',
            'subtitle' => 'Breathable front fringe headband hairpiece designed for delicate frontal hairlines',
            'icon' => 'fa-solid fa-head-side-mask',
            'badge' => 'NEW',
            'accent' => 'teal',
            'order' => 3,
        ],
        [
            'key' => 'products-range',
            'url' => '/solutions',
            'title' => 'All Hair Solutions (11 Systems)',
            'short' => 'All Solutions',
            'subtitle' => "Men's toupees, women's silk toppers, full wigs, hair extensions & academy training",
            'icon' => 'fa-solid fa-layer-group',
            'badge' => '11 TYPES',
            'accent' => 'sky',
            'order' => 4,
        ],
        [
            'key' => 'hair-diagnosis-hub',
            'url' => '/diagnosis',
            'title' => 'Self-Assessment & Style Guide',
            'short' => 'Self Check',
            'subtitle' => 'Step-by-step interactive wizard, Norwood & women hair loss matrix & tailored specs',
            'icon' => 'fa-solid fa-clipboard-check',
            'badge' => '5-STEP',
            'accent' => 'emerald',
            'order' => 5,
        ],
        [
            'key' => 'stock',
            'url' => '/stock',
            'title' => 'In-Stock Precision Pieces',
            'short' => 'In-Stock',
            'subtitle' => 'Ready-to-fit Melbourne studio inventory available for same-day fitting & cut-in',
            'icon' => 'fa-solid fa-boxes-stacked',
            'badge' => 'READY',
            'accent' => 'amber',
            'order' => 6,
        ],
        [
            'key' => 'salon',
            'url' => '/salon',
            'title' => 'Springvale Salon & Active Performance',
            'short' => 'Salon & Care',
            'subtitle' => '1-on-1 private fitting suite, barber cut-in, 4–6 wk re-bonding, swim & gym hold',
            'icon' => 'fa-solid fa-scissors',
            'badge' => 'PRIVATE',
            'accent' => 'emerald',
            'order' => 7,
        ],
        [
            'key' => 'transformations',
            'url' => '/transformations',
            'title' => '10-Year Youth Transformations',
            'short' => 'Before & After',
            'subtitle' => 'Real Melbourne client before & after showcase with interactive split-view gallery',
            'icon' => 'fa-solid fa-arrows-split-up-and-left',
            'badge' => '10-YR YOUTH',
            'accent' => 'amber',
            'order' => 8,
        ],
        [
            'key' => 'ai-simulator',
            'url' => '/ai-tryon',
            'title' => 'AI Virtual Hairstyle Try-On',
            'short' => 'AI Try-On',
            'subtitle' => 'Preview men & women hair systems on your own photo or live camera in seconds',
            'icon' => 'fa-solid fa-wand-magic-sparkles',
            'badge' => 'AI TECH',
            'accent' => 'purple',
            'order' => 9,
        ],
        [
            'key' => 'factory',
            'url' => '/factory-b2b',
            'title' => 'Factory Direct & B2B Wholesale',
            'short' => 'Factory & B2B',
            'subtitle' => '20+ years direct manufacturing craftsmanship, salon wholesale & trade partnership',
            'icon' => 'fa-solid fa-industry',
            'badge' => 'DIRECT',
            'accent' => 'indigo',
            'order' => 10,
        ],
        [
            'key' => 'faq',
            'url' => '/faq',
            'title' => 'Frequently Asked Questions',
            'short' => 'FAQ',
            'subtitle' => 'Lifespan, swimming, adhesives, private health rebates, maintenance & ordering answers',
            'icon' => 'fa-solid fa-circle-question',
            'badge' => 'Q&A',
            'accent' => 'teal',
            'order' => 11,
        ],
    ];
}

function dh_render_subpage_top(string $title, string $badge = ''): void {
?>
    <div class="pt-20 sm:pt-24 pb-3 bg-[#091025] border-b border-brand-border/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2.5 min-w-0">
                <a href="/"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-brand-cardBg hover:bg-brand-border border border-brand-border text-slate-200 hover:text-white text-xs font-bold transition-colors shrink-0">
                    <i class="fa-solid fa-arrow-left text-brand-tealLight text-[11px]"></i>
                    <span>Home</span>
                </a>
                <span class="text-slate-600 text-xs">/</span>
                <span class="text-xs sm:text-sm font-heading font-bold text-white truncate"><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></span>
                <?php if ($badge !== ''): ?>
                    <span class="hidden sm:inline-block text-[10px] font-mono font-bold px-2 py-0.5 rounded-full bg-teal-500/20 text-teal-300 border border-teal-400/30 shrink-0"><?php echo htmlspecialchars($badge, ENT_QUOTES, 'UTF-8'); ?></span>
                <?php endif; ?>
            </div>
            <button type="button" @click="openBookingModal()"
                class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-500/15 hover:bg-amber-500/25 border border-amber-400/40 text-amber-300 text-xs font-bold transition-colors cursor-pointer shrink-0">
                <i class="fa-solid fa-calendar-check text-[11px]"></i>
                <span>Book Free Consult</span>
            </button>
        </div>
    </div>
<?php
}

function dh_render_subpage_bottom(string $currentKey): void {
    $items = dh_get_section_directory();
?>
    <section class="py-12 sm:py-16 bg-[#080E21] border-t border-brand-border/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Quick Consultation CTA -->
            <div class="rounded-2xl bg-gradient-to-r from-brand-cardBg via-[#162447] to-brand-cardBg border border-teal-500/30 p-5 sm:p-7 flex flex-col md:flex-row items-center justify-between gap-4 shadow-xl">
                <div class="text-center md:text-left">
                    <div class="text-[11px] font-mono uppercase tracking-wider text-brand-tealLight font-bold">Springvale 1-on-1 Private Suite</div>
                    <h3 class="font-heading text-lg sm:text-xl font-extrabold text-white mt-1">Need Personal Advice or a Custom Quote?</h3>
                    <p class="text-xs sm:text-sm text-brand-textMuted mt-1">Book a $0 private fitting consultation or check your instant estimate online.</p>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-2.5 w-full md:w-auto">
                    <button type="button" @click="openBookingModal()"
                        class="flex-1 sm:flex-initial teal-gradient text-white font-bold text-xs uppercase tracking-wider px-5 py-3 rounded-xl shadow-lg hover:opacity-95 transition-all cursor-pointer flex items-center justify-center gap-2">
                        <i class="fa-solid fa-calendar-check"></i>
                        <span>Book Free Consult</span>
                    </button>
                    <button type="button" @click="isCalculatorModalOpen = true"
                        class="flex-1 sm:flex-initial bg-brand-darkBg hover:bg-slate-800 text-sky-300 border border-sky-400/40 font-bold text-xs uppercase tracking-wider px-5 py-3 rounded-xl transition-all cursor-pointer flex items-center justify-center gap-2">
                        <i class="fa-solid fa-calculator"></i>
                        <span>Instant Quote</span>
                    </button>
                </div>
            </div>

            <!-- Explore Other Sections -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-heading text-sm sm:text-base font-bold text-white uppercase tracking-wider">Explore Other Sections</h4>
                    <a href="/" class="text-xs font-bold text-brand-tealLight hover:underline flex items-center gap-1">
                        <i class="fa-solid fa-house text-[11px]"></i>
                        <span>All Sections Hub</span>
                    </a>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5 sm:gap-3">
                    <?php foreach ($items as $item):
                        if ($item['key'] === $currentKey) continue;
                    ?>
                        <a href="<?php echo htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8'); ?>"
                            class="group flex items-center justify-between gap-2 p-3 rounded-xl bg-brand-cardBg/80 hover:bg-brand-cardBg border border-brand-border/70 hover:border-teal-400/50 transition-all">
                            <span class="flex items-center gap-2 min-w-0">
                                <i class="<?php echo htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8'); ?> text-brand-tealLight text-xs shrink-0"></i>
                                <span class="text-xs font-bold text-slate-200 group-hover:text-white truncate"><?php echo htmlspecialchars($item['short'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-500 group-hover:text-teal-300 group-hover:translate-x-0.5 transition-all shrink-0"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
<?php
}

