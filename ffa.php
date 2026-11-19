<?php
$pageTitle       = 'Frontal Fibrosing Alopecia (FFA) Hairpiece Melbourne | Breathable Fringe | David Hair';
$pageDescription = 'Dedicated breathable front fringe & elastic headband hairpiece engineered for Frontal Fibrosing Alopecia (FFA) in Melbourne. Gentle hairline coverage & 1-on-1 Springvale fitting.';
$pageKeywords    = 'frontal fibrosing alopecia hairpiece melbourne, FFA headband wig australia, front fringe hairpiece, receding hairline women hairpiece melbourne';
$canonicalPath   = '/ffa';
$activePage      = 'frontal-fibrosing-alopecia';
$extraHeadScripts = ['/assets/js/ffa.js'];
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/subpage_bar.php';
?>
<main id="main-content" class="w-full min-h-screen">
<?php dh_render_subpage_top('Frontal Fibrosing Alopecia (FFA)', 'NEW SPECIALIST'); ?>
    <section id="frontal-fibrosing-alopecia"
        class="scroll-mt-20 bg-gradient-to-b from-brand-darkBg via-[#0B152E] to-brand-darkBg border-y border-brand-border/80 relative overflow-hidden"
        x-data="ffaSectionApp()">

        <!-- Ambient Background Glow Effects -->
        <div class="absolute -top-36 -right-36 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-36 -left-36 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-14">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-teal-500/15 border border-teal-400/40 text-teal-300 text-xs font-bold uppercase tracking-wider mb-3.5 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                    <span>Frontal Fibrosing Alopecia (FFA) Specialist Solution</span>
                </div>
                <h2 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-300 via-sky-300 to-emerald-300">
                        FFA Frontal Piece
                    </span>
                    <span class="block text-xl sm:text-3xl lg:text-4xl text-slate-100 font-bold mt-2">
                        Breathable Front Fringe &amp; Headband Hairpiece
                    </span>
                    <span class="block text-xs sm:text-sm font-semibold text-teal-300 mt-2 font-mono uppercase tracking-wider">
                        Dedicated FFA Fringe Hairpiece &bull; 100% Glueless &bull; Elastic Headband Fit &bull; Maximum Breathability
                    </span>
                </h2>
                <p class="mt-3 text-brand-textMuted text-sm sm:text-base max-w-2xl mx-auto leading-relaxed font-normal">
                    Designed specifically for women experiencing Frontal Fibrosing Alopecia and progressive hairline recession.
                    Worn effortlessly like a comfortable elastic headband with 100% human hair bangs—protecting sensitive, scarred scalp while restoring a natural, youthful hairline in 30 seconds.
                </p>
            </div>

            <!-- Main Content Grid: Left 1:1 Video, Right Solution Features -->
            <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">

                <!-- 1:1 Video Player Column (Desktop: lg:col-span-6) -->
                <div class="lg:col-span-6 flex flex-col items-center">

                    <!-- Video Switcher Selector Tabs -->
                    <div class="flex items-center justify-center gap-2 mb-3 w-full max-w-md">
                        <button type="button" @click="switchVideo('highlight')"
                            class="flex-1 py-2 px-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer border"
                            :class="activeVideoTab === 'highlight' ? 'bg-teal-500 text-white border-teal-400 shadow-md shadow-teal-500/20' : 'bg-brand-cardBg text-slate-300 border-brand-border hover:bg-brand-inner'">
                            <i class="fa-solid fa-scissors text-xs"></i>
                            <span>Hairline Focus (15s)</span>
                        </button>
                        <button type="button" @click="switchVideo('full')"
                            class="flex-1 py-2 px-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer border"
                            :class="activeVideoTab === 'full' ? 'bg-teal-500 text-white border-teal-400 shadow-md shadow-teal-500/20' : 'bg-brand-cardBg text-slate-300 border-brand-border hover:bg-brand-inner'">
                            <i class="fa-solid fa-circle-play text-xs"></i>
                            <span>Full Application (51s)</span>
                        </button>
                    </div>

                    <!-- 1:1 Aspect Ratio Video Showcase Box (Strict 1:1 Ratio) -->
                    <div class="relative py-10 sm:py-16 w-full max-w-[460px] sm:max-w-[480px] aspect-square rounded-3xl overflow-hidden border-2 border-teal-500/40 shadow-2xl shadow-teal-950/50 bg-brand-cardInner group"
                         style="aspect-ratio: 1 / 1;"
                         @mouseenter="showControls = true"
                         @mouseleave="showControls = false"
                         @touchstart="showControls = true">

                        <!-- Ambient Glow -->
                        <div class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-teal-500/20 to-sky-500/20 blur-xl pointer-events-none"></div>

                        <!-- HTML5 Video (1:1 Object Cover) -->
                        <video x-ref="ffaVideo"
                               :src="currentVideo"
                               autoplay muted playsinline loop
                               class="w-full h-full object-cover rounded-2xl cursor-pointer"
                               @play="isPlaying = true"
                               @pause="isPlaying = false"
                               @timeupdate="onTimeUpdate()"
                               @loadedmetadata="onLoadedMetadata()"
                               @click="togglePlay()">
                            <source :src="currentVideo" type="video/mp4">
                            <source src="videos/Frontal-sum.mp4" type="video/mp4">
                            <source src="videos/frontal_highlight.mp4" type="video/mp4">
                            <source src="videos/frontal_full.mp4" type="video/mp4">
                        </video>

                        <!-- Top Floating Badges Overlay -->
                        <div class="absolute top-3 left-3 right-3 flex items-center justify-between pointer-events-none z-20">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-darkBg/85 backdrop-blur-md border border-teal-400/40 text-[11px] font-bold text-teal-300 shadow">
                                <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                                <span>1:1 Live Demonstration</span>
                            </div>
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-brand-darkBg/80 backdrop-blur-md border border-white/10 text-[10px] font-semibold text-slate-200">
                                <i class="fa-solid fa-wind text-teal-400"></i>
                                <span>Breathable Base</span>
                            </div>
                        </div>

                        <!-- Center Play/Pause Indicator (Shown when paused) -->
                        <div x-show="!isPlaying" x-cloak
                             class="absolute inset-0 flex items-center justify-center bg-black/40 backdrop-blur-xs transition-opacity z-20 cursor-pointer"
                             @click="togglePlay()">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-teal-500/90 text-white flex items-center justify-center text-2xl sm:text-3xl shadow-xl hover:scale-110 active:scale-95 transition-transform border border-teal-300">
                                <i class="fa-solid fa-play ml-1"></i>
                            </div>
                        </div>

                        <!-- Bottom Floating Video Controls Overlay -->
                        <div class="absolute bottom-0 inset-x-0 p-3 sm:p-4 bg-gradient-to-t from-black/90 via-black/60 to-transparent z-20 flex flex-col gap-2 transition-opacity duration-300"
                             :class="showControls || !isPlaying ? 'opacity-100' : 'opacity-80 group-hover:opacity-100'">
                            
                            <!-- Progress Bar (Scrubbable) -->
                            <div x-ref="progressBar" @click="seekVideo($event)"
                                 class="w-full h-1.5 bg-white/20 hover:h-2.5 rounded-full overflow-hidden cursor-pointer transition-all">
                                <div class="h-full bg-gradient-to-r from-teal-400 to-sky-400 transition-all duration-100"
                                     :style="'width: ' + progressPercent + '%;'"></div>
                            </div>

                            <!-- Controls Bar -->
                            <div class="flex items-center justify-between text-white text-xs pt-1">
                                <div class="flex items-center gap-3">
                                    <!-- Play/Pause Toggle -->
                                    <button type="button" @click="togglePlay()" class="hover:text-teal-300 transition-colors p-1" :title="isPlaying ? 'Pause' : 'Play'">
                                        <i class="fa-solid" :class="isPlaying ? 'fa-pause' : 'fa-play'"></i>
                                    </button>
                                    <!-- Mute/Unmute Toggle -->
                                    <button type="button" @click="toggleMute()" class="hover:text-teal-300 transition-colors flex items-center gap-1.5 p-1 bg-white/10 hover:bg-white/20 px-2 rounded-md" :title="isMuted ? 'Unmute Audio' : 'Mute Audio'">
                                        <i class="fa-solid text-xs" :class="isMuted ? 'fa-volume-xmark text-slate-300' : 'fa-volume-high text-teal-300'"></i>
                                        <span class="text-[10px] font-semibold" x-text="isMuted ? 'Muted' : 'Audio On'"></span>
                                    </button>
                                    <!-- Time Counter -->
                                    <span class="text-[10px] font-mono text-slate-300">
                                        <span x-text="formatTime(currentTime)"></span> / <span x-text="formatTime(duration)"></span>
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] text-teal-300 font-bold hidden sm:inline">Elastic Headband Hold</span>
                                    <!-- Fullscreen Toggle -->
                                    <button type="button" @click="toggleFullscreen()" class="hover:text-teal-300 transition-colors p-1" title="Fullscreen">
                                        <i class="fa-solid fa-expand text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- 3 Quick Pillars Below Video -->
                    <div class="grid grid-cols-3 gap-2 sm:gap-3 mt-4 w-full max-w-[480px]">
                        <div class="p-2.5 rounded-xl bg-brand-cardBg border border-brand-border text-center">
                            <i class="fa-solid fa-wind text-teal-400 text-sm mb-1"></i>
                            <div class="text-[11px] font-bold text-white">Ultra-Breathable</div>
                            <div class="text-[9px] text-brand-textMuted">Open Air-Mesh</div>
                        </div>
                        <div class="p-2.5 rounded-xl bg-brand-cardBg border border-brand-border text-center">
                            <i class="fa-solid fa-circle-notch text-sky-400 text-sm mb-1"></i>
                            <div class="text-[11px] font-bold text-white">Elastic Headband</div>
                            <div class="text-[9px] text-brand-textMuted">Zero Glue / Tape</div>
                        </div>
                        <div class="p-2.5 rounded-xl bg-brand-cardBg border border-brand-border text-center">
                            <i class="fa-solid fa-sparkles text-amber-400 text-sm mb-1"></i>
                            <div class="text-[11px] font-bold text-white">100% Remy Hair</div>
                            <div class="text-[9px] text-brand-textMuted">Natural Bangs</div>
                        </div>
                    </div>

                </div>

                <!-- Solution & Features Column (Desktop: lg:col-span-6) -->
                <div class="lg:col-span-6 space-y-6">

                    <!-- Why Traditional Solutions Fail for FFA Box -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-brand-cardBg/80 border border-brand-border shadow-md">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-rose-500/15 border border-rose-500/30 text-rose-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                            </div>
                            <div class="space-y-1">
                                <h3 class="text-xs sm:text-sm font-bold text-white uppercase tracking-wider">
                                    The Challenge of Frontal Fibrosing Alopecia (FFA)
                                </h3>
                                <p class="text-xs text-brand-textMuted leading-relaxed">
                                    FFA causes smooth scarring and persistent sensitivity along the front hairline and temples.
                                    <strong>Liquid glues and tape adhesives cause chemical burns</strong>, while <strong>traditional wig clips exert traction stress</strong> that can pull out fragile remaining hair.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- 4 Core Advantages Cards Grid -->
                    <div class="grid sm:grid-cols-2 gap-3.5">

                        <!-- Card 1: Elastic Headband -->
                        <div class="p-4 rounded-xl bg-brand-cardBg border border-teal-500/30 hover:border-teal-400/60 transition-all duration-300 space-y-2 group">
                            <div class="w-8 h-8 rounded-lg bg-teal-500/15 text-teal-400 flex items-center justify-center font-bold text-sm group-hover:scale-105 transition-transform">
                                <i class="fa-solid fa-circle-notch"></i>
                            </div>
                            <h4 class="text-xs sm:text-sm font-bold text-white flex items-center gap-1.5">
                                <span>Soft Elastic Headband</span>
                            </h4>
                            <p class="text-[11px] text-brand-textMuted leading-relaxed">
                                Worn comfortably just like an everyday hairband. <strong>No sticky glue, no tape, and no clips</strong>. Completely tension-free and gentle on scarring skin.
                            </p>
                        </div>

                        <!-- Card 2: Ultra Breathability -->
                        <div class="p-4 rounded-xl bg-brand-cardBg border border-teal-500/30 hover:border-teal-400/60 transition-all duration-300 space-y-2 group">
                            <div class="w-8 h-8 rounded-lg bg-sky-500/15 text-sky-400 flex items-center justify-center font-bold text-sm group-hover:scale-105 transition-transform">
                                <i class="fa-solid fa-wind"></i>
                            </div>
                            <h4 class="text-xs sm:text-sm font-bold text-white flex items-center gap-1.5">
                                <span>Maximum Air Breathability</span>
                            </h4>
                            <p class="text-[11px] text-brand-textMuted leading-relaxed">
                                Ultra-fine Swiss lace and open micro-mesh allow continuous airflow. Prevents sweat accumulation, itchiness, and folliculitis in warm weather.
                            </p>
                        </div>

                        <!-- Card 3: Natural Front Bangs -->
                        <div class="p-4 rounded-xl bg-brand-cardBg border border-teal-500/30 hover:border-teal-400/60 transition-all duration-300 space-y-2 group">
                            <div class="w-8 h-8 rounded-lg bg-amber-500/15 text-amber-400 flex items-center justify-center font-bold text-sm group-hover:scale-105 transition-transform">
                                <i class="fa-solid fa-scissors"></i>
                            </div>
                            <h4 class="text-xs sm:text-sm font-bold text-white flex items-center gap-1.5">
                                <span>100% Remy Human Fringe</span>
                            </h4>
                            <p class="text-[11px] text-brand-textMuted leading-relaxed">
                                Hand-tied individual human hair knots recreate natural growth direction. Cut into wispy bangs, curtain fringe, or side bangs to conceal recession.
                            </p>
                        </div>

                        <!-- Card 4: 15-Second Quick Fit -->
                        <div class="p-4 rounded-xl bg-brand-cardBg border border-teal-500/30 hover:border-teal-400/60 transition-all duration-300 space-y-2 group">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/15 text-emerald-400 flex items-center justify-center font-bold text-sm group-hover:scale-105 transition-transform">
                                <i class="fa-solid fa-stopwatch"></i>
                            </div>
                            <h4 class="text-xs sm:text-sm font-bold text-white flex items-center gap-1.5">
                                <span>30-Second Daily Wear</span>
                            </h4>
                            <p class="text-[11px] text-brand-textMuted leading-relaxed">
                                Slip over your head, position the hairline lace, and comb your biological crown hair over the band. Ready to go in seconds with zero maintenance hassle.
                            </p>
                        </div>

                    </div>

                    <!-- 3-Step Wear Guide Ribbon -->
                    <div class="p-4 rounded-xl bg-brand-inner/70 border border-brand-border flex flex-col sm:flex-row items-center gap-3">
                        <div class="text-[11px] font-bold text-teal-300 uppercase tracking-wider flex-shrink-0 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-check text-teal-400"></i>
                            <span>How To Wear:</span>
                        </div>
                        <div class="flex-1 grid grid-cols-3 gap-2 text-[10px] text-slate-300 text-center sm:text-left">
                            <div><strong class="text-white block">Step 1.</strong> Slip on elastic band</div>
                            <div><strong class="text-white block">Step 2.</strong> Align front fringe</div>
                            <div><strong class="text-white block">Step 3.</strong> Blend crown hair</div>
                        </div>
                    </div>

                    <!-- Melbourne Salon Service & CTAs -->
                    <div class="pt-2 flex flex-col sm:flex-row items-center gap-3">
                        <button type="button" @click="isBookingModalOpen = true"
                            class="w-full sm:w-auto teal-gradient text-white font-bold text-xs sm:text-sm uppercase tracking-wider px-6 sm:px-8 py-3.5 rounded-xl shadow-lg hover:opacity-95 transition-all text-center flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-calendar-check"></i>
                            <span>Book Free Springvale Consultation</span>
                        </button>
                        <button type="button" @click="$dispatch('open-ffa-modal'); openFfaModal();"
                            class="w-full sm:w-auto bg-brand-cardBg hover:bg-brand-border text-slate-200 border border-brand-border font-semibold text-xs sm:text-sm uppercase tracking-wider px-6 py-3.5 rounded-xl transition-all text-center flex items-center justify-center gap-2 cursor-pointer shadow-sm group">
                            <i class="fa-solid fa-clipboard-check text-brand-tealLight group-hover:scale-110 transition-transform"></i>
                            <span>Self-Check FFA Stage</span>
                        </button>
                    </div>

                    <div class="text-[11px] text-brand-textMuted flex items-center gap-2 justify-center sm:justify-start">
                        <i class="fa-solid fa-location-dot text-brand-bronze"></i>
                        <span>Private 1-on-1 consultation &amp; custom cut-in at Springvale Flagship Salon, Melbourne.</span>
                    </div>

                </div>

            </div>

        </div>
    </section>

<?php dh_render_subpage_bottom('frontal-fibrosing-alopecia'); ?>
</main>

<?php
require __DIR__ . '/includes/global_modals.php';
require __DIR__ . '/includes/footer.php';
?>
