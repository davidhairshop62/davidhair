<?php
$pageTitle       = 'Springvale Private Hair System Salon & Maintenance | David Hair Melbourne';
$pageDescription = '100% private 1-on-1 fitting suite in Springvale Melbourne. Precision barber cut-in, 4-6 week re-bonding, scalp spa, waterproof swim & gym performance hold.';
$pageKeywords    = 'hair system salon springvale, toupee cut in melbourne, hair system rebonding maintenance melbourne, waterproof toupee swim gym';
$canonicalPath   = '/salon';
$activePage      = 'salon';
$extraHeadScripts = [];
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/subpage_bar.php';
?>
<main id="main-content" class="w-full min-h-screen">
<?php dh_render_subpage_top('Springvale Salon & Active Performance', 'PRIVATE SUITE'); ?>
    <section id="salon"
        class="relative py-10 sm:py-16 bg-[#0E1733]/60 border-y border-brand-border/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                <div class="lg:col-span-6 space-y-5 sm:space-y-6 text-center sm:text-left">
                    <span class="text-brand-tealLight text-xs font-bold uppercase tracking-widest block">Melbourne
                        Flagship Salon</span>
                    <h2 class="font-heading text-2xl sm:text-4xl font-extrabold text-white">Springvale Specialist
                        Fitting & Maintenance</h2>
                    <p class="text-brand-textMuted text-xs sm:text-base leading-relaxed font-normal">
                        Experience privacy, comfort, and master craftsmanship. Our Springvale salon provides personal
                        consultations, custom scalp template sculpting, seamless cut-in styling, and routine re-bonding
                        maintenance.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4 pt-2">
                        <!-- Box 1: Precision Cut-in & Blend -->
                        <div class="p-4 bg-brand-cardBg border border-brand-border rounded-xl shadow-sm text-left flex flex-col justify-between hover:border-teal-500/40 transition-all">
                            <div>
                                <i class="fa-solid fa-scissors text-brand-tealLight text-lg sm:text-xl mb-2"></i>
                                <div class="font-bold text-white text-xs sm:text-sm">Precision Cut-in & Blend ($145)</div>
                                <div class="text-[11px] text-brand-textMuted mt-1">Seamless blending with existing bio hair
                                    & barber fade.</div>
                            </div>
                            <div class="pt-2">
                                <button type="button" @click="isSalonCutInModalOpen = true"
                                    class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-400 hover:text-teal-300 transition-colors cursor-pointer group">
                                    <span>More Info</span>
                                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Box 2: 4-6 Wk Re-bond Service -->
                        <div class="p-4 bg-brand-cardBg border border-brand-border rounded-xl shadow-sm text-left flex flex-col justify-between hover:border-teal-500/40 transition-all">
                            <div>
                                <i class="fa-solid fa-sync text-brand-tealLight text-lg sm:text-xl mb-2"></i>
                                <div class="font-bold text-white text-xs sm:text-sm">4–6 Wk Re-bond Service ($99)</div>
                                <div class="text-[11px] text-brand-textMuted mt-1">Detachment, scalp exfoliation & medical
                                    re-bonding.</div>
                            </div>
                            <div class="pt-2">
                                <button type="button" @click="isSalonRebondModalOpen = true"
                                    class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-400 hover:text-teal-300 transition-colors cursor-pointer group">
                                    <span>More Info</span>
                                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Box 3: Template Sculpting -->
                        <div class="p-4 bg-brand-cardBg border border-brand-border rounded-xl shadow-sm text-left flex flex-col justify-between hover:border-teal-500/40 transition-all">
                            <div>
                                <i class="fa-solid fa-head-side-virus text-brand-tealLight text-lg sm:text-xl mb-2"></i>
                                <div class="font-bold text-white text-xs sm:text-sm">Template Sculpting ($0 Free)</div>
                                <div class="text-[11px] text-brand-textMuted mt-1">Exact scalp contour duplication & hair
                                    loss assessment.</div>
                            </div>
                            <div class="pt-2">
                                <button type="button" @click="isSalonTemplateModalOpen = true"
                                    class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-400 hover:text-teal-300 transition-colors cursor-pointer group">
                                    <span>More Info</span>
                                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Box 4: DIY Self-Care Coaching -->
                        <div class="p-4 bg-brand-cardBg border border-brand-border rounded-xl shadow-sm text-left flex flex-col justify-between hover:border-teal-500/40 transition-all">
                            <div>
                                <i class="fa-solid fa-graduation-cap text-brand-bronze text-lg sm:text-xl mb-2"></i>
                                <div class="font-bold text-white text-xs sm:text-sm">DIY Self-Care Coaching</div>
                                <div class="text-[11px] text-brand-textMuted mt-1">30–45 min hands-on home re-bonding
                                    training & supply kits.</div>
                            </div>
                            <div class="pt-2">
                                <button type="button" @click="isSalonDiyModalOpen = true"
                                    class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-400 hover:text-teal-300 transition-colors cursor-pointer group">
                                    <span>More Info</span>
                                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" @click="openMapModal()"
                        class="inline-flex items-center justify-center sm:justify-start gap-2.5 sm:gap-3 pt-2 text-xs sm:text-sm text-slate-300 hover:text-brand-tealLight transition-colors font-medium group text-left cursor-pointer">
                        <i class="fa-solid fa-location-dot text-brand-bronze group-hover:scale-125 group-hover:text-brand-tealLight transition-all text-base sm:text-lg flex-shrink-0"></i>
                        <span class="underline decoration-slate-600 underline-offset-4 group-hover:decoration-brand-tealLight">Shop F16 (Level 1), Buckingham Ave, Springvale VIC 3171, Melbourne Australia</span>
                        <span class="px-2.5 py-0.5 rounded-full bg-teal-500/15 border border-teal-500/30 text-[10px] text-teal-300 font-bold uppercase tracking-wider group-hover:bg-teal-500/25 transition-colors flex items-center gap-1 shrink-0">
                            <i class="fa-solid fa-map-location-dot"></i> View Map
                        </span>
                    </button>

                    <button @click="isBookingModalOpen = true"
                        class="w-full sm:w-auto teal-gradient text-white font-bold text-xs sm:text-sm uppercase tracking-wider px-8 py-4 rounded-xl shadow-lg hover:opacity-95 transition-all min-h-[48px]">
                        Book In-Person Appointment
                    </button>
                </div>

                <div class="lg:col-span-6 mt-4 lg:mt-0"
                     x-data="{
                        currentSlide: 0,
                        slides: [
                            { src: 'images/salon1.jpg', title: 'Springvale Flagship Salon', caption: 'Private Consultation & Styling Stations' },
                            { src: 'images/salon2.jpg', title: 'Private Fitting Studio', caption: 'Discrete 1-on-1 VIP Rooms' },
                            { src: 'images/salon3.jpg', title: 'Specialist Treatment Suite', caption: 'Scalp Health & Deep Cleanse Lounge' }
                        ],
                        timer: null,
                        init() {
                            this.startAutoPlay();
                        },
                        startAutoPlay() {
                            this.stopAutoPlay();
                            this.timer = setInterval(() => { this.nextSlide(); }, 4500);
                        },
                        stopAutoPlay() {
                            if (this.timer) clearInterval(this.timer);
                        },
                        nextSlide() {
                            this.currentSlide = (this.currentSlide + 1) % this.slides.length;
                        },
                        prevSlide() {
                            this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
                        },
                        goToSlide(idx) {
                            this.currentSlide = idx;
                            this.startAutoPlay();
                        }
                     }"
                     @mouseenter="stopAutoPlay()"
                     @mouseleave="startAutoPlay()">
                    
                    <div class="rounded-2xl border border-brand-border overflow-hidden bg-brand-cardBg shadow-2xl transition-all hover:border-brand-teal/50">
                        <!-- Main Carousel Viewport -->
                        <div class="relative overflow-hidden w-full h-72 sm:h-96 bg-brand-inner select-none group">
                            <!-- Slides Container -->
                            <template x-for="(slide, idx) in slides" :key="idx">
                                <div x-show="currentSlide === idx"
                                     x-transition:enter="transition ease-out duration-500"
                                     x-transition:enter-start="opacity-0 scale-98"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-300 absolute inset-0"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-98"
                                     class="absolute inset-0 w-full h-full">
                                    <img :src="slide.src" 
                                         :alt="slide.title" 
                                         class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                                </div>
                            </template>

                            <!-- Slide Number Counter (Top-Left) -->
                            <div class="absolute top-3.5 left-3.5 bg-black/70 backdrop-blur-md text-white text-[11px] font-mono font-bold px-3 py-1 rounded-xl border border-white/10 z-20 flex items-center gap-1.5 shadow-md">
                                <i class="fa-solid fa-camera text-brand-tealLight text-[10px]"></i>
                                <span x-text="(currentSlide + 1) + ' / ' + slides.length"></span>
                            </div>

                            <!-- Google Map Action Button (Top-Right) -->
                            <button type="button" 
                                    @click="openMapModal()" 
                                    class="absolute top-3.5 right-3.5 bg-brand-darkBg/90 hover:bg-teal-600 text-teal-300 hover:text-white backdrop-blur-md border border-teal-500/40 text-xs font-bold px-3 py-1.5 rounded-xl shadow-lg flex items-center gap-1.5 transition-all z-20 cursor-pointer">
                                <i class="fa-solid fa-map-location-dot"></i>
                                <span>View Google Map</span>
                            </button>

                            <!-- Carousel Navigation Arrows (Left & Right) -->
                            <button type="button" 
                                    @click.stop="prevSlide()" 
                                    aria-label="Previous Slide"
                                    class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/60 hover:bg-black/90 text-white flex items-center justify-center transition-all opacity-80 hover:opacity-100 hover:scale-110 z-20 shadow-lg border border-white/10 cursor-pointer">
                                <i class="fa-solid fa-chevron-left text-sm"></i>
                            </button>
                            <button type="button" 
                                    @click.stop="nextSlide()" 
                                    aria-label="Next Slide"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/60 hover:bg-black/90 text-white flex items-center justify-center transition-all opacity-80 hover:opacity-100 hover:scale-110 z-20 shadow-lg border border-white/10 cursor-pointer">
                                <i class="fa-solid fa-chevron-right text-sm"></i>
                            </button>

                            <!-- Carousel Slide Title & Caption (Bottom-Left) -->
                            <div class="absolute bottom-3 left-4 right-24 z-20">
                                <h3 class="text-white font-bold text-sm sm:text-base leading-snug drop-shadow-md" x-text="slides[currentSlide].title"></h3>
                                <p class="text-slate-300 text-[11px] sm:text-xs drop-shadow truncate" x-text="slides[currentSlide].caption"></p>
                            </div>

                            <!-- Carousel Indicator Dots (Bottom-Right) -->
                            <div class="absolute bottom-3 right-4 flex items-center gap-1.5 z-20 bg-black/60 backdrop-blur-sm px-2.5 py-1.5 rounded-full border border-white/10 shadow-md">
                                <template x-for="(s, idx) in slides" :key="idx">
                                    <button type="button" 
                                            @click.stop="goToSlide(idx)" 
                                            :aria-label="'Go to slide ' + (idx + 1)"
                                            class="transition-all rounded-full cursor-pointer"
                                            :class="currentSlide === idx ? 'w-5 h-1.5 bg-brand-teal' : 'w-1.5 h-1.5 bg-white/40 hover:bg-white/80'"></button>
                                </template>
                            </div>
                        </div>

                        <!-- 3-Thumbnail Preview Bar (한 줄에 3개씩 클릭하여 즉시 이동) -->
                        <div class="p-3 bg-brand-inner/70 border-b border-brand-border/60">
                            <div class="grid grid-cols-3 gap-2">
                                <template x-for="(slide, idx) in slides" :key="idx">
                                    <div @click="goToSlide(idx)"
                                         class="relative aspect-[16/10] sm:aspect-[16/9] rounded-xl overflow-hidden border cursor-pointer transition-all bg-brand-darkBg group/thumb"
                                         :class="currentSlide === idx ? 'border-2 border-brand-teal ring-2 ring-brand-teal/40 shadow-md' : 'border-brand-border/80 hover:border-slate-400 opacity-70 hover:opacity-100'">
                                        <img :src="slide.src" :alt="slide.title" class="w-full h-full object-cover group-hover/thumb:scale-105 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-black/30 group-hover/thumb:bg-black/10 transition-colors"></div>
                                        <span class="absolute bottom-1 left-1.5 text-[9px] font-bold text-white px-1.5 py-0.5 rounded bg-black/70 backdrop-blur-xs font-mono" x-text="'Salon ' + (idx + 1)"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Card Footer Info -->
                        <div class="p-4 sm:p-5">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-0">
                                <div>
                                    <div class="font-bold text-white text-sm sm:text-base flex items-center gap-2">
                                        <span>Springvale Flagship Studio</span>
                                        <i class="fa-solid fa-location-dot text-brand-bronze text-xs"></i>
                                    </div>
                                    <div class="text-[11px] sm:text-xs text-brand-textMuted mt-0.5">
                                        Private 1-on-1 VIP Fitting Rooms • Free Parking Available
                                    </div>
                                </div>
                                <span class="bg-emerald-950/80 text-emerald-300 text-[10px] sm:text-xs font-bold px-3 py-1 rounded-full border border-emerald-800/60 whitespace-nowrap">
                                    Mon - Sun (<strong class="text-amber-400 font-bold">Wed Closed</strong>) 10:30 AM – 5:00 PM
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="lifestyle-performance"
        class="bg-brand-darkBg/95 border-b border-brand-border/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-8">
                <span class="text-brand-tealLight text-xs font-bold uppercase tracking-widest block mb-1">Engineered for Freedom</span>
                <h3 class="font-heading text-xl sm:text-3xl font-extrabold text-white">Built
                    for the Australian Climate & Active Lifestyle</h3>
            </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
<div class="bg-brand-cardBg border border-brand-border/80 p-5 rounded-xl space-y-2 eye-shadow flex flex-col justify-between hover:border-teal-500/40 transition-all">
                    <div>
                        <div
                            class="w-10 h-10 rounded-lg teal-gradient flex items-center justify-center text-white text-lg font-bold mb-3 shadow-md">
                            <i class="fa-solid fa-person-swimming"></i>
                        </div>
                        <div class="font-bold text-white text-sm">100% Water-Resistant
                            Hold</div>
                        <p class="text-xs text-brand-textMuted leading-relaxed">
                            Confidently swim in open water, take hot showers, and maintain 4-6 weeks of waterproof hold.</p>
                    </div>
                    <div class="pt-2">
                        <button type="button" @click="isWaterproofModalOpen = true"
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-400 hover:text-teal-300 transition-colors cursor-pointer group">
                            <span>More Info</span>
                            <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </div>
<!-- Card 2: Gym & Workout Breathability -->
                <div class="bg-brand-cardBg border border-brand-border/80 p-5 rounded-xl space-y-2 eye-shadow flex flex-col justify-between hover:border-teal-500/40 transition-all">
                    <div>
                        <div
                            class="w-10 h-10 rounded-lg teal-gradient flex items-center justify-center text-white text-lg font-bold mb-3 shadow-md">
                            <i class="fa-solid fa-dumbbell"></i>
                        </div>
                        <div class="font-bold text-white text-sm">Gym & Workout
                            Breathability</div>
                        <p class="text-xs text-brand-textMuted leading-relaxed">
                            Micro-ventilation mesh base releases heat and scalp moisture during high-intensity workouts.</p>
                    </div>
                    <div class="pt-2">
                        <button type="button" @click="isGymModalOpen = true"
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-400 hover:text-teal-300 transition-colors cursor-pointer group">
                            <span>More Info</span>
                            <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </div>

                <!-- Card 3: UV & Climate Shield -->
                <div class="bg-brand-cardBg border border-brand-border/80 p-5 rounded-xl space-y-2 eye-shadow flex flex-col justify-between hover:border-teal-500/40 transition-all">
                    <div>
                        <div
                            class="w-10 h-10 rounded-lg teal-gradient flex items-center justify-center text-white text-lg font-bold mb-3 shadow-md">
                            <i class="fa-solid fa-sun"></i>
                        </div>
                        <div class="font-bold text-white text-sm">UV & Climate Shield
                        </div>
                        <p class="text-xs text-brand-textMuted leading-relaxed">Materials
                            tested against high Australian UV and humidity without adhesive breakdown.</p>
                    </div>
                    <div class="pt-2">
                        <button type="button" @click="isUvModalOpen = true"
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-400 hover:text-teal-300 transition-colors cursor-pointer group">
                            <span>More Info</span>
                            <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </div>

                <!-- Card 4: Seamless Barber Cut-In -->
                <div class="bg-brand-cardBg border border-brand-border/80 p-5 rounded-xl space-y-2 eye-shadow flex flex-col justify-between hover:border-teal-500/40 transition-all">
                    <div>
                        <div
                            class="w-10 h-10 rounded-lg teal-gradient flex items-center justify-center text-white text-lg font-bold mb-3 shadow-md">
                            <i class="fa-solid fa-scissors"></i>
                        </div>
                        <div class="font-bold text-white text-sm">Seamless Barber Cut-In
                        </div>
                        <p class="text-xs text-brand-textMuted leading-relaxed">Precision
                            fade work and texture matching eliminates any visible transition seam.</p>
                    </div>
                    <div class="pt-2">
                        <button type="button" @click="isBarberModalOpen = true"
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-400 hover:text-teal-300 transition-colors cursor-pointer group">
                            <span>More Info</span>
                            <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php dh_render_subpage_bottom('salon'); ?>
</main>
    <!-- ==================== CLIMATE & ACTIVE PERFORMANCE MODALS (4) ==================== -->
</div>
    </div>

    <!-- 100% WATERPROOF HOLD EXPLANATION MODAL -->
    <div x-show="isWaterproofModalOpen" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        <div @click.away="isWaterproofModalOpen = false"
            class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-2xl w-full p-5 sm:p-7 shadow-2xl relative max-h-[90vh] overflow-y-auto space-y-6 text-left my-auto">
            
            <!-- Close Button -->
            <button @click="isWaterproofModalOpen = false" 
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-brand-inner border border-brand-border text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>

            <!-- Header -->
            <div class="space-y-1.5 pr-8">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-300 text-[11px] font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-shield-halved text-xs"></i>
                    <span>Medical-Grade Waterproof Technology</span>
                </div>
                <h3 class="font-heading font-extrabold text-xl sm:text-2xl text-white">
                    100% Water-Resistant Hold &amp; Active Lifestyle Guide
                </h3>
                <p class="text-xs sm:text-sm text-brand-textMuted leading-relaxed">
                    Designed specifically for Melbourne's climate and active Australian lifestyles. Live freely with zero restrictions — swim, shower, exercise, and sleep without worrying about displacement.
                </p>
            </div>

            <!-- 2 Core Tech Pillars -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div class="bg-brand-inner border border-brand-border rounded-xl p-4 space-y-2">
                    <div class="w-8 h-8 rounded-lg teal-gradient flex items-center justify-center text-white text-xs font-bold">
                        <i class="fa-solid fa-droplet-slash"></i>
                    </div>
                    <div class="text-sm font-bold text-white">Medical-Grade Polymer Bond</div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Hypoallergenic acrylic &amp; silicone copolymers form a flexible waterproof barrier that resists scalp oils, perspiration, ocean saltwater, and hot steam.
                    </p>
                </div>
                <div class="bg-brand-inner border border-brand-border rounded-xl p-4 space-y-2">
                    <div class="w-8 h-8 rounded-lg teal-gradient flex items-center justify-center text-white text-xs font-bold">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div class="text-sm font-bold text-white">0.03mm Skin &amp; Mesh Base</div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Micro-thin skin seals flush against your scalp with zero gap for water to penetrate, while lace mesh allows heat and perspiration to escape naturally.
                    </p>
                </div>
            </div>

            <!-- Real-World Lifestyle Capabilities -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-teal-400"></i>
                    <span>Everyday Lifestyle &amp; Water Activities</span>
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-person-swimming"></i>
                            <span>Ocean, Beach &amp; Pool Swimming</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Surf, dive, and swim in chlorinated pools or open sea water. The bond holds firm against water currents.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-shower"></i>
                            <span>Daily Hot Showers &amp; Shampoo</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Shampoo and condition upright in the shower just like biological hair. Gently massage with your fingertips.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-dumbbell"></i>
                            <span>High-Intensity Gym &amp; Sports</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Heavy cardio and weightlifting. Perspiration evaporates through the base without degrading the bond matrix.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-bed"></i>
                            <span>24/7 Sleep &amp; Natural Living</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            No nightly removal required. Stay attached 24 hours a day and wake up with your hair ready to style.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Realistic 4-6 Weeks vs Salon Maintenance Cycle -->
            <div class="p-4 rounded-xl bg-gradient-to-r from-teal-950/40 via-brand-inner to-teal-950/40 border border-teal-500/30 space-y-2">
                <div class="flex items-center gap-2 text-xs font-bold text-teal-300">
                    <i class="fa-solid fa-calendar-days text-sm"></i>
                    <span>Hold Duration &amp; Recommended Salon Routine</span>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    While the chemical adhesive bond remains waterproof for <span class="font-bold text-white">4 to 6 weeks</span>, natural biological hair under the base grows ~1cm per month and the scalp secretes natural oils. For optimal hygiene, scalp health, and an undetectable hairline, we recommend a <span class="text-teal-300 font-semibold">3 to 4-week professional refit routine</span> at our Springvale flagship salon (detach, deep scalp cleanse, shave touch-up &amp; fresh re-bond).
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-2 border-t border-brand-border/60">
                <button type="button" @click="isWaterproofModalOpen = false" 
                    class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-brand-border text-slate-300 hover:text-white text-xs font-semibold transition-colors text-center">
                    Close
                </button>
                <button type="button" @click="isWaterproofModalOpen = false; isBookingModalOpen = true;"
                    class="w-full sm:w-auto px-6 py-2.5 rounded-xl teal-gradient text-white text-xs font-bold uppercase tracking-wider shadow-lg hover:opacity-95 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Book Private Consultation ($0)</span>
                </button>
            </div>
        </div>
    </div>

</div>
    </div>

    <!-- 2. GYM & WORKOUT BREATHABILITY MODAL -->
    <div x-show="isGymModalOpen" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        <div @click.away="isGymModalOpen = false"
            class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-2xl w-full p-5 sm:p-7 shadow-2xl relative max-h-[90vh] overflow-y-auto space-y-6 text-left my-auto">
            
            <!-- Close Button -->
            <button @click="isGymModalOpen = false" 
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-brand-inner border border-brand-border text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>

            <!-- Header -->
            <div class="space-y-1.5 pr-8">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-300 text-[11px] font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-wind text-xs"></i>
                    <span>Micro-Ventilation Aerocore System</span>
                </div>
                <h3 class="font-heading font-extrabold text-xl sm:text-2xl text-white">
                    Gym &amp; Workout Breathability: High-Intensity Comfort Guide
                </h3>
                <p class="text-xs sm:text-sm text-brand-textMuted leading-relaxed">
                    Tackle high-intensity cardio, CrossFit, and heavy lifting with zero overheating. Our breathable mesh architecture channels body heat away while allowing fresh airflow directly across your scalp.
                </p>
            </div>

            <!-- 2 Core Tech Pillars -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div class="bg-brand-inner border border-brand-border rounded-xl p-4 space-y-2">
                    <div class="w-8 h-8 rounded-lg teal-gradient flex items-center justify-center text-white text-xs font-bold">
                        <i class="fa-solid fa-lungs"></i>
                    </div>
                    <div class="text-sm font-bold text-white">Micro-Porous French Mesh</div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Precision hexagonal monofilament netting delivers instant heat dissipation, preventing sweat pooling beneath the system during intense strain.
                    </p>
                </div>
                <div class="bg-brand-inner border border-brand-border rounded-xl p-4 space-y-2">
                    <div class="w-8 h-8 rounded-lg teal-gradient flex items-center justify-center text-white text-xs font-bold">
                        <i class="fa-solid fa-fire-burner"></i>
                    </div>
                    <div class="text-sm font-bold text-white">Thermal-Shield Poly Perimeter</div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Reinforced polyurethane perimeter tape locks firmly around your hairline while the central lace breathes freely, guaranteeing zero slipping during rapid movement.
                    </p>
                </div>
            </div>

            <!-- Real-World Workout Scenarios -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-teal-400"></i>
                    <span>Athletic Performance &amp; Workout Scenarios</span>
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-dumbbell"></i>
                            <span>Heavy Weightlifting &amp; CrossFit</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Full scalp stability during max-effort lifts, squats, and inverted exercises without adhesive tension or sliding.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-person-running"></i>
                            <span>Cardio, Marathon &amp; Cycling</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Continuous headwind passes straight through the porous fibers for active convective cooling on long endurance runs.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-baseball-bat-ball"></i>
                            <span>Contact Sports &amp; Martial Arts</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Rugby, soccer, and BJJ compatible. Secure contour bonding withstands accidental bumps, headgear, and helmets.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-temperature-arrow-down"></i>
                            <span>Rapid Post-Workout Cool Down</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Sweat evaporates naturally in minutes post-workout, preventing the scalp irritation common with non-breathable hairpieces.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pro-Athlete Maintenance Tips -->
            <div class="p-4 rounded-xl bg-gradient-to-r from-teal-950/40 via-brand-inner to-teal-950/40 border border-teal-500/30 space-y-2">
                <div class="flex items-center gap-2 text-xs font-bold text-teal-300">
                    <i class="fa-solid fa-medal text-sm"></i>
                    <span>Athletic Care &amp; Scalp Protector Routine</span>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    For active gym-goers and athletes, we apply a specialized <span class="font-bold text-white">Scalp Protector Max barrier</span> before bonding at our Springvale salon. This blocks perspiration acids from breaking down adhesives early, ensuring a crisp, rock-solid <span class="text-teal-300 font-semibold">3 to 4-week hold even with 5+ weekly workout sessions</span>.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-2 border-t border-brand-border/60">
                <button type="button" @click="isGymModalOpen = false" 
                    class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-brand-border text-slate-300 hover:text-white text-xs font-semibold transition-colors text-center">
                    Close
                </button>
                <button type="button" @click="isGymModalOpen = false; isBookingModalOpen = true;"
                    class="w-full sm:w-auto px-6 py-2.5 rounded-xl teal-gradient text-white text-xs font-bold uppercase tracking-wider shadow-lg hover:opacity-95 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Book Private Consultation ($0)</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 3. UV & CLIMATE SHIELD MODAL -->
    <div x-show="isUvModalOpen" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        <div @click.away="isUvModalOpen = false"
            class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-2xl w-full p-5 sm:p-7 shadow-2xl relative max-h-[90vh] overflow-y-auto space-y-6 text-left my-auto">
            
            <!-- Close Button -->
            <button @click="isUvModalOpen = false" 
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-brand-inner border border-brand-border text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>

            <!-- Header -->
            <div class="space-y-1.5 pr-8">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-300 text-[11px] font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-sun text-xs"></i>
                    <span>Australian Solar &amp; Climate Defence</span>
                </div>
                <h3 class="font-heading font-extrabold text-xl sm:text-2xl text-white">
                    UV &amp; Climate Shield: Australian Weather Resilience Guide
                </h3>
                <p class="text-xs sm:text-sm text-brand-textMuted leading-relaxed">
                    Australia experiences some of the highest UV radiation and rapid temperature shifts in the world. Our hairpieces and bonding formulations are specifically fortified against solar bleaching and adhesive breakdown.
                </p>
            </div>

            <!-- 2 Core Tech Pillars -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div class="bg-brand-inner border border-brand-border rounded-xl p-4 space-y-2">
                    <div class="w-8 h-8 rounded-lg teal-gradient flex items-center justify-center text-white text-xs font-bold">
                        <i class="fa-solid fa-shield-virus"></i>
                    </div>
                    <div class="text-sm font-bold text-white">Anti-Oxidative Remy Hair</div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        100% cuticle-aligned virgin human hair infused with UV protectants to prevent reddish/orange brassiness and sun oxidation under Victoria's extreme UV index.
                    </p>
                </div>
                <div class="bg-brand-inner border border-brand-border rounded-xl p-4 space-y-2">
                    <div class="w-8 h-8 rounded-lg teal-gradient flex items-center justify-center text-white text-xs font-bold">
                        <i class="fa-solid fa-temperature-high"></i>
                    </div>
                    <div class="text-sm font-bold text-white">High-Thermal Poly Matrix</div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Advanced thermal-stable adhesives retain elastic tensile strength even in 40°C+ ambient heat, eliminating adhesive liquefaction and sticky residue.
                    </p>
                </div>
            </div>

            <!-- Australian Weather Challenges Addressed -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-teal-400"></i>
                    <span>Engineered for Victoria's Extreme Climatic Shifts</span>
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-fire"></i>
                            <span>40°C+ Summer Heatwaves</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Zero adhesive softening or hairline breakdown during blistering Australian summer heatwaves and dry northerly winds.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-cloud-sun-rain"></i>
                            <span>Melbourne "4 Seasons in 1 Day"</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Effortlessly handles sudden 20°C temperature plunges, tropical humidity, rain showers, and blazing sun without expanding or lifting.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-water"></i>
                            <span>Coastal Salinity &amp; Ocean Air</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Resists salt spray and atmospheric humidity along Port Phillip Bay without dehydrating hair cuticles.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-car-side"></i>
                            <span>Direct In-Car Greenhouse Heat</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Withstands intense solar radiation trapped inside parked cars during daily commutes with rock-solid structural integrity.
                        </p>
                    </div>
                </div>
            </div>

            <!-- UV Preservation & Salon Care Routine -->
            <div class="p-4 rounded-xl bg-gradient-to-r from-teal-950/40 via-brand-inner to-teal-950/40 border border-teal-500/30 space-y-2">
                <div class="flex items-center gap-2 text-xs font-bold text-teal-300">
                    <i class="fa-solid fa-bottle-droplet text-sm"></i>
                    <span>UV Protection &amp; Color Longevity Care</span>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    We supply client-exclusive <span class="font-bold text-white">Leave-In UV Filter Mist</span> that protects hair shafts against pigment fading. Additionally, during your regular 3–4 week salon visits, our master colorists provide <span class="text-teal-300 font-semibold">complimentary color-refresh toning</span> to ensure your hairpiece always blends 100% seamlessly with your natural temple hair.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-2 border-t border-brand-border/60">
                <button type="button" @click="isUvModalOpen = false" 
                    class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-brand-border text-slate-300 hover:text-white text-xs font-semibold transition-colors text-center">
                    Close
                </button>
                <button type="button" @click="isUvModalOpen = false; isBookingModalOpen = true;"
                    class="w-full sm:w-auto px-6 py-2.5 rounded-xl teal-gradient text-white text-xs font-bold uppercase tracking-wider shadow-lg hover:opacity-95 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Book Private Consultation ($0)</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 4. SEAMLESS BARBER CUT-IN MODAL -->
    <div x-show="isBarberModalOpen" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        <div @click.away="isBarberModalOpen = false"
            class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-2xl w-full p-5 sm:p-7 shadow-2xl relative max-h-[90vh] overflow-y-auto space-y-6 text-left my-auto">
            
            <!-- Close Button -->
            <button @click="isBarberModalOpen = false" 
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-brand-inner border border-brand-border text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>

            <!-- Header -->
            <div class="space-y-1.5 pr-8">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-300 text-[11px] font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-scissors text-xs"></i>
                    <span>Melbourne Master Barber Craftsmanship</span>
                </div>
                <h3 class="font-heading font-extrabold text-xl sm:text-2xl text-white">
                    Seamless Barber Cut-In: Invisible Blending &amp; Fade Guide
                </h3>
                <p class="text-xs sm:text-sm text-brand-textMuted leading-relaxed">
                    The difference between an obvious wig and a 100% undetectable hair system lies in the precision cut-in. Our specialized barbers hand-tailor each piece directly onto your head to harmonize perfectly with your facial contours.
                </p>
            </div>

            <!-- 2 Core Tech Pillars -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div class="bg-brand-inner border border-brand-border rounded-xl p-4 space-y-2">
                    <div class="w-8 h-8 rounded-lg teal-gradient flex items-center justify-center text-white text-xs font-bold">
                        <i class="fa-solid fa-bezier-curve"></i>
                    </div>
                    <div class="text-sm font-bold text-white">Graduated Natural Hairline</div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Hand-plucked single knot contouring mimics natural biological irregularity, eliminating artificial "wall-of-hair" density at the forehead.
                    </p>
                </div>
                <div class="bg-brand-inner border border-brand-border rounded-xl p-4 space-y-2">
                    <div class="w-8 h-8 rounded-lg teal-gradient flex items-center justify-center text-white text-xs font-bold">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>
                    <div class="text-sm font-bold text-white">Razor Feathering &amp; Thinning</div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Japanese feather razor sculpting intertwines system hair with your natural side hair at microscopic angles, leaving zero visible boundary seam.
                    </p>
                </div>
            </div>

            <!-- Hairstyle Versatility & Barber Techniques -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-teal-400"></i>
                    <span>Custom Styling Versatility for Modern Men</span>
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-scissors"></i>
                            <span>Skin Fades, Low &amp; Mid Tapers</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Seamless integration with trendy Melbourne barber fades (foil shaver to 1/2 guard) on your biological side and back hair.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-arrow-up-right-dots"></i>
                            <span>Exposed Hairline (Quiff &amp; Pompadour)</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Push your hair completely back with full confidence. Even under harsh halogen spotlights, only natural scalp is visible.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-fingerprint"></i>
                            <span>Natural Crown Swirl (Cowlick)</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Custom multi-directional ventilation recreates your original hair growth swirl for realistic 360-degree authenticity.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-sparkles"></i>
                            <span>Textured French Crop &amp; Messy Fringe</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Effortless matte paste styling with natural separation and movement that looks and feels like young, healthy hair.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Maintenance & Fade Refresh Routine -->
            <div class="p-4 rounded-xl bg-gradient-to-r from-teal-950/40 via-brand-inner to-teal-950/40 border border-teal-500/30 space-y-2">
                <div class="flex items-center gap-2 text-xs font-bold text-teal-300">
                    <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                    <span>Quick Side-Fade Refreshes Between System Retouches</span>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    While your bonded hair system lasts <span class="font-bold text-white">3 to 4 weeks</span> before needing a full re-fit, your natural side hair may grow out faster. David Hair Springvale offers <span class="text-teal-300 font-semibold">express 15-minute side-fade touch-ups</span> so your look stays razor-sharp and camera-ready every single week.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-2 border-t border-brand-border/60">
                <button type="button" @click="isBarberModalOpen = false" 
                    class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-brand-border text-slate-300 hover:text-white text-xs font-semibold transition-colors text-center">
                    Close
                </button>
                <button type="button" @click="isBarberModalOpen = false; isBookingModalOpen = true;"
                    class="w-full sm:w-auto px-6 py-2.5 rounded-xl teal-gradient text-white text-xs font-bold uppercase tracking-wider shadow-lg hover:opacity-95 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Book Private Consultation ($0)</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ==================== MELBOURNE FLAGSHIP SALON SERVICE MODALS (4) ==================== -->
<!-- SALON 1: PRECISION CUT-IN & BLEND MODAL -->
    <div x-show="isSalonCutInModalOpen" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        <div @click.away="isSalonCutInModalOpen = false"
            class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-2xl w-full p-5 sm:p-7 shadow-2xl relative max-h-[90vh] overflow-y-auto space-y-6 text-left my-auto">
            
            <!-- Close Button -->
            <button @click="isSalonCutInModalOpen = false" 
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-brand-inner border border-brand-border text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>

            <!-- Header -->
            <div class="space-y-1.5 pr-8">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-300 text-[11px] font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-scissors text-xs"></i>
                    <span>Melbourne Master Barber Craft • $145 AUD</span>
                </div>
                <h3 class="font-heading font-extrabold text-xl sm:text-2xl text-white">
                    Precision Cut-in &amp; Blend: Master Salon Styling Guide
                </h3>
                <p class="text-xs sm:text-sm text-brand-textMuted leading-relaxed">
                    The defining transformation session. Every raw factory system is hand-sculpted directly onto your head by our Melbourne master stylists to achieve an undetectable, lifelike hairline and natural texture gradation.
                </p>
            </div>

            <!-- 2 Core Tech Pillars -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div class="bg-brand-inner border border-brand-border rounded-xl p-4 space-y-2">
                    <div class="w-8 h-8 rounded-lg teal-gradient flex items-center justify-center text-white text-xs font-bold">
                        <i class="fa-solid fa-compass-drafting"></i>
                    </div>
                    <div class="text-sm font-bold text-white">Anatomical Hairline Placement</div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Caliper-calibrated hairline positioning tailored to your forehead curvature, facial bone symmetry, and age-appropriate recession angles.
                    </p>
                </div>
                <div class="bg-brand-inner border border-brand-border rounded-xl p-4 space-y-2">
                    <div class="w-8 h-8 rounded-lg teal-gradient flex items-center justify-center text-white text-xs font-bold">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>
                    <div class="text-sm font-bold text-white">Razor Feathering &amp; Thinning</div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Japanese feather-razor thinning interlocks the system hair with your natural side hair at micro-angles, eliminating any blunt wig borders.
                    </p>
                </div>
            </div>

            <!-- What's Included in the 90-Min Session -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-teal-400"></i>
                    <span>What’s Included in Your 90-Minute Master Session</span>
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-scissors"></i>
                            <span>Master Barber Fade &amp; Taper</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Skin fade, mid/low taper, or scissor-over-comb finish meticulously blended into your natural biological hair.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-sliders"></i>
                            <span>Density Calibration</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Custom point-cutting and texturizing shear work removes excess bulk so your hair moves naturally with authentic weight.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-shower"></i>
                            <span>Wash, Blow-Mold &amp; Clay Finish</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Salon sulfate-free shampoo, moisture conditioning, thermal styling, and premium matte paste finish tailored to your style.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-camera-retro"></i>
                            <span>360° Studio Lighting Inspection</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Multi-angle perimeter inspection under bright direct studio lights to ensure 100% undetectability from every angle.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Guarantee Box -->
            <div class="p-4 rounded-xl bg-gradient-to-r from-teal-950/40 via-brand-inner to-teal-950/40 border border-teal-500/30 space-y-2">
                <div class="flex items-center gap-2 text-xs font-bold text-teal-300">
                    <i class="fa-solid fa-shield-heart text-sm"></i>
                    <span>Complimentary 7-Day Styling Guarantee</span>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Live with your new hair for a week! If you want a slight perimeter adjustment or fringe tweak after washing it at home, return to our Springvale salon within <span class="font-bold text-white">7 days for a 100% free fine-tuning touch-up</span>.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-2 border-t border-brand-border/60">
                <button type="button" @click="isSalonCutInModalOpen = false" 
                    class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-brand-border text-slate-300 hover:text-white text-xs font-semibold transition-colors text-center">
                    Close
                </button>
                <button type="button" @click="isSalonCutInModalOpen = false; isBookingModalOpen = true;"
                    class="w-full sm:w-auto px-6 py-2.5 rounded-xl teal-gradient text-white text-xs font-bold uppercase tracking-wider shadow-lg hover:opacity-95 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Book Precision Cut-in ($145)</span>
                </button>
            </div>
        </div>
    </div>

    <!-- SALON 2: 4-6 WK RE-BOND SERVICE MODAL -->
    <div x-show="isSalonRebondModalOpen" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        <div @click.away="isSalonRebondModalOpen = false"
            class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-2xl w-full p-5 sm:p-7 shadow-2xl relative max-h-[90vh] overflow-y-auto space-y-6 text-left my-auto">
            
            <!-- Close Button -->
            <button @click="isSalonRebondModalOpen = false" 
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-brand-inner border border-brand-border text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>

            <!-- Header -->
            <div class="space-y-1.5 pr-8">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-300 text-[11px] font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-rotate text-xs"></i>
                    <span>Clinical Scalp &amp; System Maintenance • $99 AUD</span>
                </div>
                <h3 class="font-heading font-extrabold text-xl sm:text-2xl text-white">
                    4–6 Wk Re-bond Service: Deep Scalp Clean &amp; Re-bond Guide
                </h3>
                <p class="text-xs sm:text-sm text-brand-textMuted leading-relaxed">
                    Maintain pristine scalp health, invisible edges, and rock-solid adhesion. Our clinical re-bond protocol thoroughly refreshes both your biological scalp and your bespoke hairpiece in under 60 minutes.
                </p>
            </div>

            <!-- 2 Core Tech Pillars -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div class="bg-brand-inner border border-brand-border rounded-xl p-4 space-y-2">
                    <div class="w-8 h-8 rounded-lg teal-gradient flex items-center justify-center text-white text-xs font-bold">
                        <i class="fa-solid fa-spa"></i>
                    </div>
                    <div class="text-sm font-bold text-white">Clinical Scalp Exfoliation</div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Antiseptic scalp detox eliminates sebum build-up and dead skin cells, restoring a clean, healthy foundation for optimal skin breathability.
                    </p>
                </div>
                <div class="bg-brand-inner border border-brand-border rounded-xl p-4 space-y-2">
                    <div class="w-8 h-8 rounded-lg teal-gradient flex items-center justify-center text-white text-xs font-bold">
                        <i class="fa-solid fa-bath"></i>
                    </div>
                    <div class="text-sm font-bold text-white">Ultrasonic Base Purification</div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Residue-free citrus solvent wash removes old adhesive without pulling hand-tied hair knots or stretching ultra-thin 0.03mm polyurethane skin.
                    </p>
                </div>
            </div>

            <!-- Step-by-Step Re-bond Protocol -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-teal-400"></i>
                    <span>Comprehensive 4-Step Clinical Protocol</span>
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-hand-sparkles"></i>
                            <span>1. Painless System Detachment</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Zero-tension medical solvent soak allows the piece to release smoothly without tugging biological hair or delicate base materials.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-head-side-virus"></i>
                            <span>2. Scalp Scrub &amp; Bio-Hair Shave</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Deep pore-cleansing clarifying wash followed by a smooth foil shave of underlying regrowth (~1cm/month) for flush skin bonding.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-bottle-droplet"></i>
                            <span>3. Deep Conditioning Hair Spa</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Hydrating hair masque restores moisture and elasticity to the system, replenishing shine and silkiness.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>4. Fresh Polymer Re-bonding</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Fresh 3-layer medical waterproof bond (Ultra Hold / Ghost Bond) application followed by laser-aligned hairline re-attachment.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Recommended Routine Box -->
            <div class="p-4 rounded-xl bg-gradient-to-r from-teal-950/40 via-brand-inner to-teal-950/40 border border-teal-500/30 space-y-2">
                <div class="flex items-center gap-2 text-xs font-bold text-teal-300">
                    <i class="fa-solid fa-calendar-check text-sm"></i>
                    <span>Recommended 3–4 Week Routine for Maximum Lifespan</span>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    While the chemical adhesive bond lasts up to 6 weeks, regular salon re-bonding every <span class="font-bold text-white">3 to 4 weeks</span> prevents adhesive buildup, protects base integrity, and doubles the overall lifespan of your custom hair system.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-2 border-t border-brand-border/60">
                <button type="button" @click="isSalonRebondModalOpen = false" 
                    class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-brand-border text-slate-300 hover:text-white text-xs font-semibold transition-colors text-center">
                    Close
                </button>
                <button type="button" @click="isSalonRebondModalOpen = false; isBookingModalOpen = true;"
                    class="w-full sm:w-auto px-6 py-2.5 rounded-xl teal-gradient text-white text-xs font-bold uppercase tracking-wider shadow-lg hover:opacity-95 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Book Re-bond Service ($99)</span>
                </button>
            </div>
        </div>
    </div>

    <!-- SALON 3: TEMPLATE SCULPTING MODAL -->
    <div x-show="isSalonTemplateModalOpen" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        <div @click.away="isSalonTemplateModalOpen = false"
            class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-2xl w-full p-5 sm:p-7 shadow-2xl relative max-h-[90vh] overflow-y-auto space-y-6 text-left my-auto">
            
            <!-- Close Button -->
            <button @click="isSalonTemplateModalOpen = false" 
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-brand-inner border border-brand-border text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>

            <!-- Header -->
            <div class="space-y-1.5 pr-8">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-300 text-[11px] font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-head-side-virus text-xs"></i>
                    <span>100% Free Consultation • $0 AUD</span>
                </div>
                <h3 class="font-heading font-extrabold text-xl sm:text-2xl text-white">
                    Custom Scalp Template Sculpting: 3D Duplication Guide
                </h3>
                <p class="text-xs sm:text-sm text-brand-textMuted leading-relaxed">
                    Eliminate all guesswork. We capture an exact physical 3D replica of your unique head contour, curvature, and hair loss boundary in a private consultation suite for a bespoke factory fit.
                </p>
            </div>

            <!-- 2 Core Tech Pillars -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div class="bg-brand-inner border border-brand-border rounded-xl p-4 space-y-2">
                    <div class="w-8 h-8 rounded-lg teal-gradient flex items-center justify-center text-white text-xs font-bold">
                        <i class="fa-solid fa-ruler-combined"></i>
                    </div>
                    <div class="text-sm font-bold text-white">Exact 3D Contour Replication</div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Medical-grade contour casting creates a millimeter-precise mold matching your scalp curvature, preventing air pockets, wrinkling, or tension.
                    </p>
                </div>
                <div class="bg-brand-inner border border-brand-border rounded-xl p-4 space-y-2">
                    <div class="w-8 h-8 rounded-lg teal-gradient flex items-center justify-center text-white text-xs font-bold">
                        <i class="fa-solid fa-palette"></i>
                    </div>
                    <div class="text-sm font-bold text-white">True Swatch Color &amp; Gray Blend</div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Accurate natural light matching across 40+ human hair shade swatches, including custom gray percentage (5% to 40%) to harmonize with your temple hair.
                    </p>
                </div>
            </div>

            <!-- What Happens in Your Free 45-Min Session -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-teal-400"></i>
                    <span>What Happens During Your 45-Minute Session</span>
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-user-lock"></i>
                            <span>1. 100% Private 1-on-1 Suite</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Complete discretion in a private treatment room with zero visibility from other clients or storefront windows.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-pencil"></i>
                            <span>2. Hairline Design &amp; Mapping</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Hand-drawn hairline tailored to your natural forehead muscles, brow alignment, and preferred recession depth.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-hand"></i>
                            <span>3. Base Sample Touch &amp; Feel</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Physically touch and inspect actual demo pieces: 0.03mm Ultra-thin Skin, French Lace, Mono, and Silk Top bases.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                            <span>4. Transparent Factory Price Quote</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Receive a clear, fixed factory-direct specification quote with zero hidden salon fees or long-term lock-in contracts.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Zero Obligation Promise -->
            <div class="p-4 rounded-xl bg-gradient-to-r from-teal-950/40 via-brand-inner to-teal-950/40 border border-teal-500/30 space-y-2">
                <div class="flex items-center gap-2 text-xs font-bold text-teal-300">
                    <i class="fa-solid fa-handshake text-sm"></i>
                    <span>Zero Obligation &amp; No Pressure Promise</span>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Our template sculpting and hair loss assessment is <span class="font-bold text-white">100% Free with zero sales pressure</span>. Whether you order today, next month, or simply want honest expert advice, you will receive our full attention and care.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-2 border-t border-brand-border/60">
                <button type="button" @click="isSalonTemplateModalOpen = false" 
                    class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-brand-border text-slate-300 hover:text-white text-xs font-semibold transition-colors text-center">
                    Close
                </button>
                <button type="button" @click="isSalonTemplateModalOpen = false; isBookingModalOpen = true;"
                    class="w-full sm:w-auto px-6 py-2.5 rounded-xl teal-gradient text-white text-xs font-bold uppercase tracking-wider shadow-lg hover:opacity-95 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Book Free Template Session ($0)</span>
                </button>
            </div>
        </div>
    </div>

    <!-- SALON 4: DIY SELF-CARE COACHING MODAL -->
    <div x-show="isSalonDiyModalOpen" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        <div @click.away="isSalonDiyModalOpen = false"
            class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-2xl w-full p-5 sm:p-7 shadow-2xl relative max-h-[90vh] overflow-y-auto space-y-6 text-left my-auto">
            
            <!-- Close Button -->
            <button @click="isSalonDiyModalOpen = false" 
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-brand-inner border border-brand-border text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>

            <!-- Header -->
            <div class="space-y-1.5 pr-8">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-300 text-[11px] font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-graduation-cap text-xs"></i>
                    <span>Independent Home Re-Bonding Masterclass</span>
                </div>
                <h3 class="font-heading font-extrabold text-xl sm:text-2xl text-white">
                    DIY Self-Care Coaching: Home Re-Bonding &amp; Care Guide
                </h3>
                <p class="text-xs sm:text-sm text-brand-textMuted leading-relaxed">
                    Gain total independence over your schedule and budget. Learn how to remove, clean, re-tape, and re-bond your hair system in the privacy of your own home with 1-on-1 practical coaching and starter kits.
                </p>
            </div>

            <!-- 2 Core Tech Pillars -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div class="bg-brand-inner border border-brand-border rounded-xl p-4 space-y-2">
                    <div class="w-8 h-8 rounded-lg teal-gradient flex items-center justify-center text-white text-xs font-bold">
                        <i class="fa-solid fa-person-chalkboard"></i>
                    </div>
                    <div class="text-sm font-bold text-white">Hands-on Mirror Practice</div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Practice step-by-step alongside our specialist in a private salon suite until you can align your hairline in your bathroom mirror in minutes.
                    </p>
                </div>
                <div class="bg-brand-inner border border-brand-border rounded-xl p-4 space-y-2">
                    <div class="w-8 h-8 rounded-lg teal-gradient flex items-center justify-center text-white text-xs font-bold">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <div class="text-sm font-bold text-white">Pro Solvents &amp; Tape Training</div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Master the safe handling of citrus solvents (C-22), scalp anti-perspirants (Max Hold Sport), and contour tape shapes without skin irritation.
                    </p>
                </div>
            </div>

            <!-- DIY Curriculum -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-teal-400"></i>
                    <span>What You Learn in Your Coaching Session</span>
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-arrows-to-dot"></i>
                            <span>Pinpoint Hairline Alignment</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            3-finger forehead measurement technique and mirror alignment guides to ensure your hairpiece is never placed too low or crooked.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-broom"></i>
                            <span>Zero-Damage Adhesive Removal</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            How to roll adhesive residue off delicate lace and skin bases in minutes without pulling knots or shedding hair fibers.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-pump-soap"></i>
                            <span>Shampoo &amp; Detangling Routine</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Correct wide-tooth combing, sulfate-free washing, and leave-in hydration techniques that keep hair soft for 6–12 months.
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-brand-darkBg border border-brand-border/70 space-y-1">
                        <div class="font-bold text-teal-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-plane-departure"></i>
                            <span>Travel &amp; Emergency Fix Kit</span>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            How to pack a compact travel touch-up kit for domestic flights, beach trips, and quick edge touch-ups on the go.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Savings Box -->
            <div class="p-4 rounded-xl bg-gradient-to-r from-teal-950/40 via-brand-inner to-teal-950/40 border border-teal-500/30 space-y-2">
                <div class="flex items-center gap-2 text-xs font-bold text-teal-300">
                    <i class="fa-solid fa-piggy-bank text-sm"></i>
                    <span>Save Over $1,200 AUD Annually with Total Autonomy</span>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Once you master home self-care, you only need to visit the salon for seasonal haircut trims or new systems. You save over <span class="font-bold text-white">$1,200 AUD per year in salon maintenance fees</span> while maintaining 100% schedule flexibility and privacy.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-2 border-t border-brand-border/60">
                <button type="button" @click="isSalonDiyModalOpen = false" 
                    class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-brand-border text-slate-300 hover:text-white text-xs font-semibold transition-colors text-center">
                    Close
                </button>
                <button type="button" @click="isSalonDiyModalOpen = false; isBookingModalOpen = true;"
                    class="w-full sm:w-auto px-6 py-2.5 rounded-xl teal-gradient text-white text-xs font-bold uppercase tracking-wider shadow-lg hover:opacity-95 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Inquire About DIY Coaching &amp; Kits</span>
                </button>
            </div>
        </div>
    </div>


<?php
require __DIR__ . '/includes/global_modals.php';
require __DIR__ . '/includes/footer.php';
?>
