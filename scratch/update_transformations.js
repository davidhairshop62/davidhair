const fs = require('fs');

let html = fs.readFileSync('index.html', 'utf8');

const targetStart = '<!-- 6. TRANSFORMATIONS (BEFORE & AFTER SHOWCASE) -->';
const targetEnd = '<!-- 6.5 AI VIRTUAL HAIRSTYLE FITTING SIMULATOR -->';

const startIndex = html.indexOf(targetStart);
const endIndex = html.indexOf(targetEnd);

if (startIndex === -1 || endIndex === -1) {
    console.error('Target boundary not found! startIndex:', startIndex, 'endIndex:', endIndex);
    process.exit(1);
}

const newSection = `<!-- 6. TRANSFORMATIONS (BEFORE & AFTER SHOWCASE) -->
    <section id="transformations" class="py-16 sm:py-24 bg-brand-darkBg border-t border-brand-border/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-16 space-y-3">
                <span class="text-brand-tealLight text-xs font-bold uppercase tracking-widest block">Proven Transformations • 10-Year Youthful Restoration</span>
                <h2 class="font-heading text-2xl sm:text-4xl font-extrabold text-white">
                    Experience The 10-Year Youthful <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-sky-300 to-emerald-400">Before &amp; After</span>
                </h2>
                <p class="text-brand-textMuted text-xs sm:text-sm max-w-xl mx-auto leading-relaxed">
                    Worried about an unnatural look? Drag the slider across each portrait to see for yourself.<br class="hidden sm:inline">
                    Precision 0.03mm skin, undetectable lace, and 100% human hair silk top toppers styled by Melbourne specialists.
                </p>
            </div>

            <!-- ROW 1: MEN'S TRANSFORMATIONS (3 Cases • 3:4 Aspect Ratio) -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-brand-border/60 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-sky-500/20 border border-sky-400/40 flex items-center justify-center text-sky-400">
                        <i class="fa-solid fa-mars text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-heading text-base sm:text-lg font-bold text-white">Men's Precision Toupee Transformations</h3>
                        <p class="text-[11px] sm:text-xs text-brand-textMuted">0.03mm Ultra-Thin Skin &amp; French Lace Systems (Aspect 3:4)</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs text-sky-300 font-mono bg-sky-950/60 border border-sky-800/80 px-2.5 py-1 rounded-md self-start sm:self-auto">
                    <i class="fa-solid fa-arrows-left-right text-[10px]"></i>
                    <span>Drag Slider to Compare</span>
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 mb-14">
                <!-- Men Case 1 -->
                <div class="bg-brand-cardBg border border-brand-border rounded-2xl overflow-hidden p-4 sm:p-5 space-y-3.5 shadow-xl hover:border-teal-500/40 transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between pb-1 border-b border-brand-border/40">
                        <div class="font-bold text-white text-sm flex items-center gap-2">
                            <i class="fa-solid fa-user-tie text-sky-400"></i>
                            <span>Case #M01 • Frontal &amp; Crown</span>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-500/20 text-teal-300 border border-teal-500/30">
                            0.03mm Knotless
                        </span>
                    </div>

                    <!-- Interactive Drag Slider (3:4 Ratio) -->
                    <div class="relative aspect-[3/4] w-full rounded-xl overflow-hidden select-none cursor-ew-resize bg-slate-900 shadow-inner group"
                        x-data="{ sliderPos: 50, containerW: 0 }"
                        x-ref="slider_m1"
                        x-init="$nextTick(() => containerW = $refs.slider_m1 ? $refs.slider_m1.clientWidth : 0)"
                        @resize.window="containerW = $refs.slider_m1 ? $refs.slider_m1.clientWidth : 0"
                        @mousemove="if ($event.buttons === 1) { containerW = $el.clientWidth; sliderPos = Math.max(0, Math.min(100, ($event.offsetX / $el.clientWidth) * 100)); }"
                        @touchmove="containerW = $el.clientWidth; sliderPos = Math.max(0, Math.min(100, (($event.touches[0].clientX - $el.getBoundingClientRect().left) / $el.clientWidth) * 100))"
                        @click="containerW = $el.clientWidth; sliderPos = Math.max(0, Math.min(100, ($event.offsetX / $el.clientWidth) * 100))">
                        <!-- After Image -->
                        <img src="images/transformations/man1_after.jpg"
                            alt="Men Hair Transformation After"
                            class="absolute inset-0 w-full h-full object-cover pointer-events-none select-none">
                        <div class="absolute bottom-3 right-3 bg-black/75 backdrop-blur text-white text-[10px] font-extrabold px-2.5 py-1 rounded-md uppercase tracking-wider border border-white/20 select-none shadow">
                            After (Fitted &amp; Styled)
                        </div>

                        <!-- Before Image Overlay -->
                        <div class="absolute inset-0 overflow-hidden pointer-events-none select-none" :style="\`width: \${sliderPos}%\`">
                            <img src="images/transformations/man1_before.jpg"
                                alt="Men Hair Loss Before"
                                class="absolute inset-0 h-full object-cover max-w-none pointer-events-none select-none"
                                :style="\`width: \${containerW || ($refs.slider_m1 ? $refs.slider_m1.clientWidth : 380)}px\`">
                            <div class="absolute bottom-3 left-3 bg-black/75 backdrop-blur text-white text-[10px] font-extrabold px-2.5 py-1 rounded-md uppercase tracking-wider border border-white/20 select-none shadow">
                                Before (Hair Loss)
                            </div>
                        </div>

                        <!-- Slider Handle Divider -->
                        <div class="absolute top-0 bottom-0 w-0.5 bg-white cursor-ew-resize shadow-2xl pointer-events-none" :style="\`left: \${sliderPos}%\`">
                            <div class="w-7 h-7 rounded-full teal-gradient absolute top-1/2 -translate-y-1/2 -translate-x-1/2 flex items-center justify-center text-white text-xs shadow-xl ring-2 ring-white/90">
                                <i class="fa-solid fa-arrows-left-right text-[10px]"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5 pt-1 text-xs">
                        <div class="text-teal-300 font-semibold flex items-center gap-1.5 text-[11px] sm:text-xs">
                            <i class="fa-solid fa-check-circle text-teal-400"></i>
                            <span>Springvale Flagship Master Cut-In</span>
                        </div>
                        <div class="flex gap-1.5 text-[10px] font-mono font-semibold">
                            <span class="bg-brand-inner border border-brand-border px-2 py-0.5 rounded text-slate-300">Base: 0.03mm Skin</span>
                            <span class="bg-brand-inner border border-brand-border px-2 py-0.5 rounded text-slate-300">Density: 90%</span>
                        </div>
                    </div>
                </div>

                <!-- Men Case 2 -->
                <div class="bg-brand-cardBg border border-brand-border rounded-2xl overflow-hidden p-4 sm:p-5 space-y-3.5 shadow-xl hover:border-teal-500/40 transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between pb-1 border-b border-brand-border/40">
                        <div class="font-bold text-white text-sm flex items-center gap-2">
                            <i class="fa-solid fa-briefcase text-sky-400"></i>
                            <span>Case #M02 • Executive Blend</span>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-sky-500/20 text-sky-300 border border-sky-500/30">
                            HD Lace + PU
                        </span>
                    </div>

                    <!-- Interactive Drag Slider (3:4 Ratio) -->
                    <div class="relative aspect-[3/4] w-full rounded-xl overflow-hidden select-none cursor-ew-resize bg-slate-900 shadow-inner group"
                        x-data="{ sliderPos: 50, containerW: 0 }"
                        x-ref="slider_m2"
                        x-init="$nextTick(() => containerW = $refs.slider_m2 ? $refs.slider_m2.clientWidth : 0)"
                        @resize.window="containerW = $refs.slider_m2 ? $refs.slider_m2.clientWidth : 0"
                        @mousemove="if ($event.buttons === 1) { containerW = $el.clientWidth; sliderPos = Math.max(0, Math.min(100, ($event.offsetX / $el.clientWidth) * 100)); }"
                        @touchmove="containerW = $el.clientWidth; sliderPos = Math.max(0, Math.min(100, (($event.touches[0].clientX - $el.getBoundingClientRect().left) / $el.clientWidth) * 100))"
                        @click="containerW = $el.clientWidth; sliderPos = Math.max(0, Math.min(100, ($event.offsetX / $el.clientWidth) * 100))">
                        <!-- After Image -->
                        <img src="images/transformations/man2_after.jpg"
                            alt="Men Hair Transformation After"
                            class="absolute inset-0 w-full h-full object-cover pointer-events-none select-none">
                        <div class="absolute bottom-3 right-3 bg-black/75 backdrop-blur text-white text-[10px] font-extrabold px-2.5 py-1 rounded-md uppercase tracking-wider border border-white/20 select-none shadow">
                            After (Fitted &amp; Styled)
                        </div>

                        <!-- Before Image Overlay -->
                        <div class="absolute inset-0 overflow-hidden pointer-events-none select-none" :style="\`width: \${sliderPos}%\`">
                            <img src="images/transformations/man2_before.jpg"
                                alt="Men Hair Loss Before"
                                class="absolute inset-0 h-full object-cover max-w-none pointer-events-none select-none"
                                :style="\`width: \${containerW || ($refs.slider_m2 ? $refs.slider_m2.clientWidth : 380)}px\`">
                            <div class="absolute bottom-3 left-3 bg-black/75 backdrop-blur text-white text-[10px] font-extrabold px-2.5 py-1 rounded-md uppercase tracking-wider border border-white/20 select-none shadow">
                                Before (Hair Loss)
                            </div>
                        </div>

                        <!-- Slider Handle Divider -->
                        <div class="absolute top-0 bottom-0 w-0.5 bg-white cursor-ew-resize shadow-2xl pointer-events-none" :style="\`left: \${sliderPos}%\`">
                            <div class="w-7 h-7 rounded-full teal-gradient absolute top-1/2 -translate-y-1/2 -translate-x-1/2 flex items-center justify-center text-white text-xs shadow-xl ring-2 ring-white/90">
                                <i class="fa-solid fa-arrows-left-right text-[10px]"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5 pt-1 text-xs">
                        <div class="text-teal-300 font-semibold flex items-center gap-1.5 text-[11px] sm:text-xs">
                            <i class="fa-solid fa-check-circle text-teal-400"></i>
                            <span>Natural Salt-&-Pepper Hair Match</span>
                        </div>
                        <div class="flex gap-1.5 text-[10px] font-mono font-semibold">
                            <span class="bg-brand-inner border border-brand-border px-2 py-0.5 rounded text-slate-300">Base: Lace + PU</span>
                            <span class="bg-brand-inner border border-brand-border px-2 py-0.5 rounded text-slate-300">Density: 95%</span>
                        </div>
                    </div>
                </div>

                <!-- Men Case 3 -->
                <div class="bg-brand-cardBg border border-brand-border rounded-2xl overflow-hidden p-4 sm:p-5 space-y-3.5 shadow-xl hover:border-teal-500/40 transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between pb-1 border-b border-brand-border/40">
                        <div class="font-bold text-white text-sm flex items-center gap-2">
                            <i class="fa-solid fa-dumbbell text-sky-400"></i>
                            <span>Case #M03 • Active Fade &amp; Crop</span>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                            Sweat-Resistant
                        </span>
                    </div>

                    <!-- Interactive Drag Slider (3:4 Ratio) -->
                    <div class="relative aspect-[3/4] w-full rounded-xl overflow-hidden select-none cursor-ew-resize bg-slate-900 shadow-inner group"
                        x-data="{ sliderPos: 50, containerW: 0 }"
                        x-ref="slider_m3"
                        x-init="$nextTick(() => containerW = $refs.slider_m3 ? $refs.slider_m3.clientWidth : 0)"
                        @resize.window="containerW = $refs.slider_m3 ? $refs.slider_m3.clientWidth : 0"
                        @mousemove="if ($event.buttons === 1) { containerW = $el.clientWidth; sliderPos = Math.max(0, Math.min(100, ($event.offsetX / $el.clientWidth) * 100)); }"
                        @touchmove="containerW = $el.clientWidth; sliderPos = Math.max(0, Math.min(100, (($event.touches[0].clientX - $el.getBoundingClientRect().left) / $el.clientWidth) * 100))"
                        @click="containerW = $el.clientWidth; sliderPos = Math.max(0, Math.min(100, ($event.offsetX / $el.clientWidth) * 100))">
                        <!-- After Image -->
                        <img src="images/transformations/man3_after.jpg"
                            alt="Men Hair Transformation After"
                            class="absolute inset-0 w-full h-full object-cover pointer-events-none select-none">
                        <div class="absolute bottom-3 right-3 bg-black/75 backdrop-blur text-white text-[10px] font-extrabold px-2.5 py-1 rounded-md uppercase tracking-wider border border-white/20 select-none shadow">
                            After (Fitted &amp; Styled)
                        </div>

                        <!-- Before Image Overlay -->
                        <div class="absolute inset-0 overflow-hidden pointer-events-none select-none" :style="\`width: \${sliderPos}%\`">
                            <img src="images/transformations/man3_before.jpg"
                                alt="Men Hair Loss Before"
                                class="absolute inset-0 h-full object-cover max-w-none pointer-events-none select-none"
                                :style="\`width: \${containerW || ($refs.slider_m3 ? $refs.slider_m3.clientWidth : 380)}px\`">
                            <div class="absolute bottom-3 left-3 bg-black/75 backdrop-blur text-white text-[10px] font-extrabold px-2.5 py-1 rounded-md uppercase tracking-wider border border-white/20 select-none shadow">
                                Before (Hair Loss)
                            </div>
                        </div>

                        <!-- Slider Handle Divider -->
                        <div class="absolute top-0 bottom-0 w-0.5 bg-white cursor-ew-resize shadow-2xl pointer-events-none" :style="\`left: \${sliderPos}%\`">
                            <div class="w-7 h-7 rounded-full teal-gradient absolute top-1/2 -translate-y-1/2 -translate-x-1/2 flex items-center justify-center text-white text-xs shadow-xl ring-2 ring-white/90">
                                <i class="fa-solid fa-arrows-left-right text-[10px]"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5 pt-1 text-xs">
                        <div class="text-teal-300 font-semibold flex items-center gap-1.5 text-[11px] sm:text-xs">
                            <i class="fa-solid fa-check-circle text-teal-400"></i>
                            <span>Gym &amp; High Heat Waterproof Hold</span>
                        </div>
                        <div class="flex gap-1.5 text-[10px] font-mono font-semibold">
                            <span class="bg-brand-inner border border-brand-border px-2 py-0.5 rounded text-slate-300">Base: Bio-Skin</span>
                            <span class="bg-brand-inner border border-brand-border px-2 py-0.5 rounded text-slate-300">Density: 100%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ROW 2: WOMEN'S TRANSFORMATIONS (3 Cases • 3:4 Aspect Ratio) -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-brand-border/60 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-rose-500/20 border border-rose-400/40 flex items-center justify-center text-rose-400">
                        <i class="fa-solid fa-venus text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-heading text-base sm:text-lg font-bold text-white">Women's Silk Top &amp; Crown Volume Transformations</h3>
                        <p class="text-[11px] sm:text-xs text-brand-textMuted">100% Remy Hair Toppers for Diffuse Thinning &amp; Part Line Restoration (Aspect 3:4)</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs text-rose-300 font-mono bg-rose-950/60 border border-rose-800/80 px-2.5 py-1 rounded-md self-start sm:self-auto">
                    <i class="fa-solid fa-arrows-left-right text-[10px]"></i>
                    <span>Drag Slider to Compare</span>
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Women Case 1 -->
                <div class="bg-brand-cardBg border border-brand-border rounded-2xl overflow-hidden p-4 sm:p-5 space-y-3.5 shadow-xl hover:border-rose-500/40 transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between pb-1 border-b border-brand-border/40">
                        <div class="font-bold text-white text-sm flex items-center gap-2">
                            <i class="fa-solid fa-wand-magic text-rose-400"></i>
                            <span>Case #W01 • Diffuse Crown Thinning</span>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                            Silk Parting
                        </span>
                    </div>

                    <!-- Interactive Drag Slider (3:4 Ratio) -->
                    <div class="relative aspect-[3/4] w-full rounded-xl overflow-hidden select-none cursor-ew-resize bg-slate-900 shadow-inner group"
                        x-data="{ sliderPos: 50, containerW: 0 }"
                        x-ref="slider_w1"
                        x-init="$nextTick(() => containerW = $refs.slider_w1 ? $refs.slider_w1.clientWidth : 0)"
                        @resize.window="containerW = $refs.slider_w1 ? $refs.slider_w1.clientWidth : 0"
                        @mousemove="if ($event.buttons === 1) { containerW = $el.clientWidth; sliderPos = Math.max(0, Math.min(100, ($event.offsetX / $el.clientWidth) * 100)); }"
                        @touchmove="containerW = $el.clientWidth; sliderPos = Math.max(0, Math.min(100, (($event.touches[0].clientX - $el.getBoundingClientRect().left) / $el.clientWidth) * 100))"
                        @click="containerW = $el.clientWidth; sliderPos = Math.max(0, Math.min(100, ($event.offsetX / $el.clientWidth) * 100))">
                        <!-- After Image -->
                        <img src="images/transformations/woman1_after.jpg"
                            alt="Women Hair Transformation After"
                            class="absolute inset-0 w-full h-full object-cover pointer-events-none select-none">
                        <div class="absolute bottom-3 right-3 bg-black/75 backdrop-blur text-white text-[10px] font-extrabold px-2.5 py-1 rounded-md uppercase tracking-wider border border-white/20 select-none shadow">
                            After (Full Volume Styled)
                        </div>

                        <!-- Before Image Overlay -->
                        <div class="absolute inset-0 overflow-hidden pointer-events-none select-none" :style="\`width: \${sliderPos}%\`">
                            <img src="images/transformations/woman1_before.jpg"
                                alt="Women Thinning Before"
                                class="absolute inset-0 h-full object-cover max-w-none pointer-events-none select-none"
                                :style="\`width: \${containerW || ($refs.slider_w1 ? $refs.slider_w1.clientWidth : 380)}px\`">
                            <div class="absolute bottom-3 left-3 bg-black/75 backdrop-blur text-white text-[10px] font-extrabold px-2.5 py-1 rounded-md uppercase tracking-wider border border-white/20 select-none shadow">
                                Before (Sparse Crown)
                            </div>
                        </div>

                        <!-- Slider Handle Divider -->
                        <div class="absolute top-0 bottom-0 w-0.5 bg-white cursor-ew-resize shadow-2xl pointer-events-none" :style="\`left: \${sliderPos}%\`">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-r from-rose-500 to-amber-500 absolute top-1/2 -translate-y-1/2 -translate-x-1/2 flex items-center justify-center text-white text-xs shadow-xl ring-2 ring-white/90">
                                <i class="fa-solid fa-arrows-left-right text-[10px]"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5 pt-1 text-xs">
                        <div class="text-rose-300 font-semibold flex items-center gap-1.5 text-[11px] sm:text-xs">
                            <i class="fa-solid fa-check-circle text-rose-400"></i>
                            <span>Seamless Clip-In or Micro-Bond Integration</span>
                        </div>
                        <div class="flex gap-1.5 text-[10px] font-mono font-semibold">
                            <span class="bg-brand-inner border border-brand-border px-2 py-0.5 rounded text-slate-300">Base: 6x6 Silk Top</span>
                            <span class="bg-brand-inner border border-brand-border px-2 py-0.5 rounded text-slate-300">Density: 120%</span>
                        </div>
                    </div>
                </div>

                <!-- Women Case 2 -->
                <div class="bg-brand-cardBg border border-brand-border rounded-2xl overflow-hidden p-4 sm:p-5 space-y-3.5 shadow-xl hover:border-rose-500/40 transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between pb-1 border-b border-brand-border/40">
                        <div class="font-bold text-white text-sm flex items-center gap-2">
                            <i class="fa-solid fa-sparkles text-rose-400"></i>
                            <span>Case #W02 • Volume Bob &amp; Parting</span>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                            Featherweight
                        </span>
                    </div>

                    <!-- Interactive Drag Slider (3:4 Ratio) -->
                    <div class="relative aspect-[3/4] w-full rounded-xl overflow-hidden select-none cursor-ew-resize bg-slate-900 shadow-inner group"
                        x-data="{ sliderPos: 50, containerW: 0 }"
                        x-ref="slider_w2"
                        x-init="$nextTick(() => containerW = $refs.slider_w2 ? $refs.slider_w2.clientWidth : 0)"
                        @resize.window="containerW = $refs.slider_w2 ? $refs.slider_w2.clientWidth : 0"
                        @mousemove="if ($event.buttons === 1) { containerW = $el.clientWidth; sliderPos = Math.max(0, Math.min(100, ($event.offsetX / $el.clientWidth) * 100)); }"
                        @touchmove="containerW = $el.clientWidth; sliderPos = Math.max(0, Math.min(100, (($event.touches[0].clientX - $el.getBoundingClientRect().left) / $el.clientWidth) * 100))"
                        @click="containerW = $el.clientWidth; sliderPos = Math.max(0, Math.min(100, ($event.offsetX / $el.clientWidth) * 100))">
                        <!-- After Image -->
                        <img src="images/transformations/woman2_after.jpg"
                            alt="Women Hair Transformation After"
                            class="absolute inset-0 w-full h-full object-cover pointer-events-none select-none">
                        <div class="absolute bottom-3 right-3 bg-black/75 backdrop-blur text-white text-[10px] font-extrabold px-2.5 py-1 rounded-md uppercase tracking-wider border border-white/20 select-none shadow">
                            After (Full Volume Styled)
                        </div>

                        <!-- Before Image Overlay -->
                        <div class="absolute inset-0 overflow-hidden pointer-events-none select-none" :style="\`width: \${sliderPos}%\`">
                            <img src="images/transformations/woman2_before.jpg"
                                alt="Women Thinning Before"
                                class="absolute inset-0 h-full object-cover max-w-none pointer-events-none select-none"
                                :style="\`width: \${containerW || ($refs.slider_w2 ? $refs.slider_w2.clientWidth : 380)}px\`">
                            <div class="absolute bottom-3 left-3 bg-black/75 backdrop-blur text-white text-[10px] font-extrabold px-2.5 py-1 rounded-md uppercase tracking-wider border border-white/20 select-none shadow">
                                Before (Thinning Part)
                            </div>
                        </div>

                        <!-- Slider Handle Divider -->
                        <div class="absolute top-0 bottom-0 w-0.5 bg-white cursor-ew-resize shadow-2xl pointer-events-none" :style="\`left: \${sliderPos}%\`">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-r from-rose-500 to-amber-500 absolute top-1/2 -translate-y-1/2 -translate-x-1/2 flex items-center justify-center text-white text-xs shadow-xl ring-2 ring-white/90">
                                <i class="fa-solid fa-arrows-left-right text-[10px]"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5 pt-1 text-xs">
                        <div class="text-rose-300 font-semibold flex items-center gap-1.5 text-[11px] sm:text-xs">
                            <i class="fa-solid fa-check-circle text-rose-400"></i>
                            <span>Color-Matched Balayage &amp; Salon Blend</span>
                        </div>
                        <div class="flex gap-1.5 text-[10px] font-mono font-semibold">
                            <span class="bg-brand-inner border border-brand-border px-2 py-0.5 rounded text-slate-300">Base: Monofilament</span>
                            <span class="bg-brand-inner border border-brand-border px-2 py-0.5 rounded text-slate-300">Density: 130%</span>
                        </div>
                    </div>
                </div>

                <!-- Women Case 3 -->
                <div class="bg-brand-cardBg border border-brand-border rounded-2xl overflow-hidden p-4 sm:p-5 space-y-3.5 shadow-xl hover:border-rose-500/40 transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between pb-1 border-b border-brand-border/40">
                        <div class="font-bold text-white text-sm flex items-center gap-2">
                            <i class="fa-solid fa-crown text-rose-400"></i>
                            <span>Case #W03 • Full Crown &amp; Fringe</span>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-500/20 text-teal-300 border border-teal-500/30">
                            Zero Detection
                        </span>
                    </div>

                    <!-- Interactive Drag Slider (3:4 Ratio) -->
                    <div class="relative aspect-[3/4] w-full rounded-xl overflow-hidden select-none cursor-ew-resize bg-slate-900 shadow-inner group"
                        x-data="{ sliderPos: 50, containerW: 0 }"
                        x-ref="slider_w3"
                        x-init="$nextTick(() => containerW = $refs.slider_w3 ? $refs.slider_w3.clientWidth : 0)"
                        @resize.window="containerW = $refs.slider_w3 ? $refs.slider_w3.clientWidth : 0"
                        @mousemove="if ($event.buttons === 1) { containerW = $el.clientWidth; sliderPos = Math.max(0, Math.min(100, ($event.offsetX / $el.clientWidth) * 100)); }"
                        @touchmove="containerW = $el.clientWidth; sliderPos = Math.max(0, Math.min(100, (($event.touches[0].clientX - $el.getBoundingClientRect().left) / $el.clientWidth) * 100))"
                        @click="containerW = $el.clientWidth; sliderPos = Math.max(0, Math.min(100, ($event.offsetX / $el.clientWidth) * 100))">
                        <!-- After Image -->
                        <img src="images/transformations/woman3_after.jpg"
                            alt="Women Hair Transformation After"
                            class="absolute inset-0 w-full h-full object-cover pointer-events-none select-none">
                        <div class="absolute bottom-3 right-3 bg-black/75 backdrop-blur text-white text-[10px] font-extrabold px-2.5 py-1 rounded-md uppercase tracking-wider border border-white/20 select-none shadow">
                            After (Full Volume Styled)
                        </div>

                        <!-- Before Image Overlay -->
                        <div class="absolute inset-0 overflow-hidden pointer-events-none select-none" :style="\`width: \${sliderPos}%\`">
                            <img src="images/transformations/woman3_before.jpg"
                                alt="Women Thinning Before"
                                class="absolute inset-0 h-full object-cover max-w-none pointer-events-none select-none"
                                :style="\`width: \${containerW || ($refs.slider_w3 ? $refs.slider_w3.clientWidth : 380)}px\`">
                            <div class="absolute bottom-3 left-3 bg-black/75 backdrop-blur text-white text-[10px] font-extrabold px-2.5 py-1 rounded-md uppercase tracking-wider border border-white/20 select-none shadow">
                                Before (Crown Thinning)
                            </div>
                        </div>

                        <!-- Slider Handle Divider -->
                        <div class="absolute top-0 bottom-0 w-0.5 bg-white cursor-ew-resize shadow-2xl pointer-events-none" :style="\`left: \${sliderPos}%\`">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-r from-rose-500 to-amber-500 absolute top-1/2 -translate-y-1/2 -translate-x-1/2 flex items-center justify-center text-white text-xs shadow-xl ring-2 ring-white/90">
                                <i class="fa-solid fa-arrows-left-right text-[10px]"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5 pt-1 text-xs">
                        <div class="text-rose-300 font-semibold flex items-center gap-1.5 text-[11px] sm:text-xs">
                            <i class="fa-solid fa-check-circle text-rose-400"></i>
                            <span>Soft Bangs &amp; Wavy Length Custom Blending</span>
                        </div>
                        <div class="flex gap-1.5 text-[10px] font-mono font-semibold">
                            <span class="bg-brand-inner border border-brand-border px-2 py-0.5 rounded text-slate-300">Base: Swiss Lace</span>
                            <span class="bg-brand-inner border border-brand-border px-2 py-0.5 rounded text-slate-300">Density: 125%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Interlinked CTA: AI Try-On & Private Fitting -->
            <div class="mt-12 p-6 rounded-2xl bg-gradient-to-r from-teal-950/60 via-[#111A3A] to-teal-950/60 border border-teal-500/40 flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left shadow-2xl">
                <div>
                    <h4 class="text-base sm:text-lg font-bold text-white flex items-center justify-center md:justify-start gap-2">
                        <i class="fa-solid fa-wand-magic-sparkles text-teal-400"></i>
                        <span>Curious How You Would Look With A Hairpiece?</span>
                    </h4>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1">
                        Try on styles instantly with our AI Virtual Simulator, or book a 100% confidential fitting in our Springvale private suites.
                    </p>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-3 flex-shrink-0">
                    <a href="#ai-simulator" class="px-5 py-2.5 rounded-xl bg-brand-inner hover:bg-slate-800 border border-teal-500/50 text-teal-300 font-bold text-xs uppercase tracking-wider transition-all flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-camera"></i>
                        <span>Try AI Simulator</span>
                    </a>
                    <button @click="claimWelcomeOffer()" class="px-5 py-2.5 rounded-xl teal-gradient hover:opacity-95 text-white font-bold text-xs uppercase tracking-wider transition-all shadow-md flex items-center gap-2">
                        <i class="fa-solid fa-calendar-check"></i>
                        <span>Book Free Private Fitting ($0)</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    `;

const updatedHtml = html.slice(0, startIndex) + newSection + html.slice(endIndex);
fs.writeFileSync('index.html', updatedHtml, 'utf8');
console.log('Successfully updated transformations section in index.html!');
