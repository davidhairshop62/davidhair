<?php
$pageTitle       = "Men's Toupees, Women's Hair Toppers & Medical Wigs Melbourne | Factory Direct | David Hair";
$pageDescription = "Factory-direct 100% human hair men's toupees, women's silk top toppers, medical wigs & FFA solutions in Melbourne. \$0 private consultation & fitting at Springvale studio. From \$420 AUD.";
$pageKeywords    = "men's toupee melbourne, women's hair topper melbourne, men's hair system melbourne, medical wigs springvale, FFA hairpiece melbourne, toupee fitting springvale, human hair toupee australia";
$canonicalPath   = '/';
$activePage      = 'home';

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/subpage_bar.php';

$directory = dh_get_section_directory();

$accentStyles = [
    'rose'    => ['icon' => 'bg-rose-500/15 border-rose-400/35 text-rose-300',       'badge' => 'bg-rose-500/20 text-rose-200 border-rose-400/40',       'hover' => 'hover:border-rose-400/60'],
    'teal'    => ['icon' => 'bg-teal-500/15 border-teal-400/35 text-teal-300',       'badge' => 'bg-teal-400/20 text-teal-200 border-teal-400/40',       'hover' => 'hover:border-teal-400/60'],
    'sky'     => ['icon' => 'bg-sky-500/15 border-sky-400/35 text-sky-300',          'badge' => 'bg-sky-500/20 text-sky-200 border-sky-400/40',          'hover' => 'hover:border-sky-400/60'],
    'emerald' => ['icon' => 'bg-emerald-500/15 border-emerald-400/35 text-emerald-300', 'badge' => 'bg-emerald-500/20 text-emerald-200 border-emerald-400/40', 'hover' => 'hover:border-emerald-400/60'],
    'amber'   => ['icon' => 'bg-amber-500/15 border-amber-400/35 text-amber-300',    'badge' => 'bg-amber-500/20 text-amber-200 border-amber-400/40',    'hover' => 'hover:border-amber-400/60'],
    'purple'  => ['icon' => 'bg-purple-500/15 border-purple-400/35 text-purple-300', 'badge' => 'bg-purple-500/20 text-purple-200 border-purple-400/40', 'hover' => 'hover:border-purple-400/60'],
    'indigo'  => ['icon' => 'bg-indigo-500/15 border-indigo-400/35 text-indigo-300', 'badge' => 'bg-indigo-500/20 text-indigo-200 border-indigo-400/40', 'hover' => 'hover:border-indigo-400/60'],
];
?>

<main id="main-content" class="w-full">

    <!-- 1. ULTRA-FAST MOBILE-FIRST HERO -->
    <section id="hero" class="relative pt-24 sm:pt-28 pb-10 sm:pb-14 overflow-hidden bg-gradient-to-b from-[#0F1C3F] via-brand-darkBg to-brand-darkBg">
        <!-- Subtle ambient glow (pure CSS, zero network cost) -->
        <div class="pointer-events-none absolute -top-24 left-1/2 -translate-x-1/2 w-[560px] h-[320px] rounded-full bg-teal-500/10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-12 gap-6 lg:gap-10 items-center">

                <!-- Hero Content Column (First on Mobile for instant LCP & readability) -->
                <div class="lg:col-span-7 space-y-4 sm:space-y-6 text-center lg:text-left order-1 lg:order-2">
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-brand-cardBg border border-teal-500/30 text-brand-tealLight text-[11px] font-semibold uppercase tracking-wider shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse flex-shrink-0"></span>
                            <span>Factory Direct &bull; Springvale Flagship</span>
                        </div>
                        <button type="button" @click="isWelcomeModalOpen = true"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-500/15 hover:bg-amber-500/25 border border-amber-400/40 text-amber-300 text-[11px] font-bold transition-all shadow-sm active:scale-95 cursor-pointer">
                            <i class="fa-solid fa-gift text-amber-400"></i>
                            <span>$0 Free Fitting &amp; $50 Voucher</span>
                            <i class="fa-solid fa-chevron-right text-[9px] opacity-70"></i>
                        </button>
                    </div>

                    <h1 class="font-heading text-2xl xs:text-3xl sm:text-5xl font-extrabold text-brand-textMain leading-tight tracking-tight">
                        Factory-Direct Men's &amp; Women's Hair Systems,
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-sky-400 to-emerald-400 block sm:inline">
                            Finished by Melbourne Specialists
                        </span>
                    </h1>

                    <p class="text-brand-textMuted text-sm sm:text-base max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                        100% human hair toupees, women's silk toppers, medical wigs &amp; FFA solutions from our own factory &mdash; custom fitted in our 100% private Springvale studio from <strong class="text-white">$420 AUD</strong>.
                    </p>

                    <!-- Primary Hero CTAs -->
                    <div class="grid grid-cols-2 sm:flex sm:flex-row items-center justify-center lg:justify-start gap-2.5 sm:gap-3.5 pt-1">
                        <button type="button" @click="isCalculatorModalOpen = true"
                            class="teal-gradient text-white font-bold text-xs sm:text-sm uppercase tracking-wider px-4 sm:px-7 py-3.5 rounded-xl shadow-lg hover:opacity-95 active:scale-[0.98] transition-all text-center min-h-[46px] flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-calculator"></i>
                            <span>Instant Quote</span>
                        </button>
                        <button type="button" @click="openBookingModal()"
                            class="bg-brand-cardBg hover:bg-brand-border text-amber-300 border border-amber-400/40 font-bold text-xs sm:text-sm uppercase tracking-wider px-4 sm:px-7 py-3.5 rounded-xl shadow-sm active:scale-[0.98] transition-all text-center min-h-[46px] flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-calendar-check text-amber-400"></i>
                            <span>Book Fitting</span>
                        </button>
                    </div>

                    <!-- Trust Strip -->
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-x-5 gap-y-1.5 pt-2 text-[11px] sm:text-xs text-slate-300">
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-shield-halved text-teal-400"></i> 100% Human Hair</span>
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-user-lock text-rose-400"></i> 1-on-1 Private Suite</span>
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-award text-amber-400"></i> Melbourne Since 2006</span>
                    </div>
                </div>

                <!-- Hero Video Card (Auto-plays immediately with WebP poster for instant paint) -->
                <div class="lg:col-span-5 order-2 lg:order-1"
                    x-data="{
                        videos: [
                            '/videos/Runing.mp4',
                            '/videos/Frontal-sum.mp4',
                            '/videos/shampoo.mp4',
                            '/videos/swim.mp4',
                            '/videos/windy.mp4'
                        ],
                        labels: ['Active Running', 'FFA Frontal Fringe', 'Daily Shampoo', 'Pool & Ocean Swim', 'Strong Wind Hold'],
                        currentIdx: 0,
                        init() {
                            this.$nextTick(() => {
                                const v = this.$refs.heroVideo;
                                if (v) { v.play().catch(() => {}); }
                            });
                            setInterval(() => { this.playNext(); }, 6500);
                        },
                        playNext() {
                            this.currentIdx = (this.currentIdx + 1) % this.videos.length;
                            this.$nextTick(() => {
                                const v = this.$refs.heroVideo;
                                if (v) { v.load(); v.play().catch(() => {}); }
                            });
                        }
                    }">
                    <div class="relative mx-auto max-w-md rounded-2xl overflow-hidden border border-brand-border shadow-2xl bg-brand-cardBg">
                        <!-- Immediate Auto-Playing Lifestyle Video with Instant WebP Poster -->
                        <div class="relative">
                            <video x-ref="heroVideo"
                                :src="videos[currentIdx]"
                                src="/videos/Runing.mp4"
                                poster="/images/TransMan.webp"
                                preload="metadata"
                                autoplay muted playsinline
                                @ended="playNext()"
                                class="w-full h-64 sm:h-80 lg:h-[400px] object-cover bg-brand-darkBg">
                            </video>
                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-brand-darkBg/80 via-transparent to-black/20"></div>
                            <div class="absolute top-3 left-3 right-3 flex items-center justify-between pointer-events-none">
                                <span class="px-2.5 py-1 rounded-full bg-black/65 border border-teal-400/40 text-[10px] font-mono font-bold text-teal-300 backdrop-blur"
                                    x-text="(currentIdx + 1) + '/' + videos.length + ' • ' + labels[currentIdx]">1/5 &bull; Active Running</span>
                                <button type="button" @click="playNext()"
                                    class="pointer-events-auto px-2.5 py-1 rounded-full bg-black/70 hover:bg-teal-600 border border-white/20 text-[10px] font-bold text-white cursor-pointer transition-colors">
                                    Next <i class="fa-solid fa-forward-step ml-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Bottom Studio Caption -->
                        <div class="px-4 py-3 bg-brand-cardBg border-t border-brand-border/80 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-full teal-gradient flex items-center justify-center font-bold text-white text-xs flex-shrink-0">
                                    <i class="fa-solid fa-scissors"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-xs font-bold text-white truncate">Melbourne Master Cut-in</div>
                                    <div class="text-[10px] text-brand-tealLight font-semibold truncate">Springvale Studio &bull; Dandenong Market</div>
                                </div>
                            </div>
                            <button type="button" @click="openMapModal()"
                                class="px-2.5 py-1.5 rounded-lg bg-teal-500/15 hover:bg-teal-500/25 border border-teal-400/35 text-teal-300 text-[10px] font-bold shrink-0 cursor-pointer">
                                <i class="fa-solid fa-location-dot mr-1"></i>Map
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. SECTION DIRECTORY HUB (Fast Tap-to-Open Dedicated Pages) -->
    <section id="sections-hub" class="py-8 sm:py-14 bg-[#080F24] border-t border-brand-border/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Directory Header -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2 mb-6 sm:mb-8">
                <div>
                    <div class="text-[11px] font-mono uppercase tracking-wider text-brand-tealLight font-bold flex items-center gap-1.5">
                        <i class="fa-solid fa-bolt text-amber-400"></i>
                        <span>Instant Section Directory</span>
                    </div>
                    <h2 class="font-heading text-xl sm:text-3xl font-extrabold text-white tracking-tight mt-0.5">
                        Explore Solutions &amp; Services
                    </h2>
                </div>
                <p class="text-xs sm:text-sm text-brand-textMuted">
                    Tap any topic below to open its dedicated fast-loading page.
                </p>
            </div>

            <!-- Dynamic Order-Aware Card Grid (Syncs with section_manager.php) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                <?php foreach ($directory as $item):
                    $st = $accentStyles[$item['accent']] ?? $accentStyles['teal'];
                    $keyJs = htmlspecialchars($item['key'], ENT_QUOTES, 'UTF-8');
                    $orderDefault = (int) $item['order'];
                ?>
                <a href="<?php echo htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8'); ?>"
                    x-show="isSectionVisible('<?php echo $keyJs; ?>')"
                    :style="'order: ' + getSectionOrder('<?php echo $keyJs; ?>', <?php echo $orderDefault; ?>) + ';'"
                    class="group relative flex items-center gap-3.5 sm:gap-4 p-4 sm:p-5 rounded-2xl bg-brand-cardBg/90 hover:bg-brand-cardBg border border-brand-border/80 <?php echo $st['hover']; ?> shadow-md hover:shadow-xl transition-all duration-200 active:scale-[0.99]">

                    <!-- Left Icon -->
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl border <?php echo $st['icon']; ?> flex items-center justify-center text-base sm:text-lg shrink-0 group-hover:scale-105 transition-transform">
                        <i class="<?php echo htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                    </div>

                    <!-- Center Title + 1-Line Summary -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-heading font-extrabold text-sm sm:text-base text-white group-hover:text-brand-tealLight transition-colors leading-snug">
                                <?php echo htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?>
                            </h3>
                            <span class="text-[9px] font-mono font-bold px-1.5 py-0.5 rounded-full border <?php echo $st['badge']; ?> shrink-0">
                                <?php echo htmlspecialchars($item['badge'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </div>
                        <p class="text-[11px] sm:text-xs text-brand-textMuted mt-1 line-clamp-2 leading-relaxed">
                            <?php echo htmlspecialchars($item['subtitle'], ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                    </div>

                    <!-- Right Chevron -->
                    <div class="w-7 h-7 rounded-lg bg-brand-darkBg/70 group-hover:bg-teal-500/20 border border-brand-border/70 flex items-center justify-center text-slate-400 group-hover:text-teal-300 shrink-0 transition-all">
                        <i class="fa-solid fa-chevron-right text-[11px] group-hover:translate-x-0.5 transition-transform"></i>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>

            <!-- Direct Interactive Tools Shortcut Bar -->
            <div class="mt-8 sm:mt-10 pt-6 sm:pt-8 border-t border-brand-border/60">
                <div class="text-[11px] font-mono uppercase tracking-wider text-slate-400 font-bold mb-3">
                    Quick Interactive Tools
                </div>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-3">
                    <button type="button" @click="openDiagModal()"
                        class="flex items-center justify-between gap-2 p-3.5 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-400/30 text-left transition-all cursor-pointer">
                        <span class="flex items-center gap-2.5 min-w-0">
                            <i class="fa-solid fa-stethoscope text-emerald-400 text-sm shrink-0"></i>
                            <span class="min-w-0">
                                <span class="block text-xs font-bold text-white truncate">5-Step Diagnostic</span>
                                <span class="block text-[10px] text-emerald-300/80 truncate">Match your hair system</span>
                            </span>
                        </span>
                        <i class="fa-solid fa-arrow-right text-[10px] text-emerald-300 shrink-0"></i>
                    </button>

                    <button type="button" @click="isCalculatorModalOpen = true"
                        class="flex items-center justify-between gap-2 p-3.5 rounded-xl bg-sky-500/10 hover:bg-sky-500/20 border border-sky-400/30 text-left transition-all cursor-pointer">
                        <span class="flex items-center gap-2.5 min-w-0">
                            <i class="fa-solid fa-calculator text-sky-400 text-sm shrink-0"></i>
                            <span class="min-w-0">
                                <span class="block text-xs font-bold text-white truncate">Cost Calculator</span>
                                <span class="block text-[10px] text-sky-300/80 truncate">Live AUD price quote</span>
                            </span>
                        </span>
                        <i class="fa-solid fa-arrow-right text-[10px] text-sky-300 shrink-0"></i>
                    </button>

                    <button type="button" @click="openComparisonModal()"
                        class="flex items-center justify-between gap-2 p-3.5 rounded-xl bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-400/30 text-left transition-all cursor-pointer">
                        <span class="flex items-center gap-2.5 min-w-0">
                            <i class="fa-solid fa-scale-balanced text-indigo-400 text-sm shrink-0"></i>
                            <span class="min-w-0">
                                <span class="block text-xs font-bold text-white truncate">Style Comparison</span>
                                <span class="block text-[10px] text-indigo-300/80 truncate">Toupee vs Topper guide</span>
                            </span>
                        </span>
                        <i class="fa-solid fa-arrow-right text-[10px] text-indigo-300 shrink-0"></i>
                    </button>

                    <button type="button" @click="openFfaModal()"
                        class="flex items-center justify-between gap-2 p-3.5 rounded-xl bg-teal-500/10 hover:bg-teal-500/20 border border-teal-400/30 text-left transition-all cursor-pointer">
                        <span class="flex items-center gap-2.5 min-w-0">
                            <i class="fa-solid fa-head-side-mask text-teal-400 text-sm shrink-0"></i>
                            <span class="min-w-0">
                                <span class="block text-xs font-bold text-white truncate">FFA Screening</span>
                                <span class="block text-[10px] text-teal-300/80 truncate">Frontal stage checklist</span>
                            </span>
                        </span>
                        <i class="fa-solid fa-arrow-right text-[10px] text-teal-300 shrink-0"></i>
                    </button>
                </div>
            </div>

        </div>
    </section>

</main>

<?php
require __DIR__ . '/includes/global_modals.php';
require __DIR__ . '/includes/footer.php';
?>

