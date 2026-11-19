    <!-- 1. GLOBAL HEADER / NAVIGATION -->
    <header
        class="fixed top-0 left-0 right-0 z-40 glass-nav border-b border-brand-border/80 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-3 sm:px-5 lg:px-6 h-16 sm:h-20 flex items-center justify-between gap-2 xl:gap-4 flex-nowrap">
            <!-- Logo / Brand & Mobile Quick Buttons (LOC, DIAG, QUOTE, BOOK) -->
            <div class="flex items-center gap-1.5 xs:gap-2 sm:gap-2.5 shrink-0 flex-nowrap">
                <!-- Logo (Desktop md+) -->
                <a href="/" class="hidden md:flex items-center gap-2 sm:gap-2.5 group shrink-0 whitespace-nowrap" title="David Hair - Home">
                    <div
                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl teal-gradient flex items-center justify-center font-heading font-extrabold text-white text-lg sm:text-xl shadow-md group-hover:scale-105 transition-transform shrink-0">
                        DH
                    </div>
                    <div class="hidden md:flex items-center shrink-0">
                        <span
                            class="font-heading text-lg sm:text-xl font-extrabold tracking-tight text-brand-textMain whitespace-nowrap inline-block">DAVID HAIR</span>
                    </div>
                </a>

                <!-- Mobile Quick Buttons: LOC · DIAG · QUOTE · BOOK -->
                <div class="flex md:hidden items-center gap-1 sm:gap-1.5 shrink-0">
                    <!-- Mobile Location Button -->
                    <button type="button" @click="openMapModal()"
                        class="flex items-center gap-1 px-1.5 py-1 sm:px-2.5 sm:py-2 rounded-lg bg-teal-500/15 hover:bg-teal-500/25 border border-teal-400/40 text-teal-300 text-[10px] sm:text-[11px] font-bold tracking-wide transition-all shadow-sm active:scale-95 min-h-[34px] sm:min-h-[38px] cursor-pointer shrink-0"
                        title="Melbourne Studio Location &amp; Directions">
                        <i class="fa-solid fa-location-dot text-teal-400 text-xs"></i>
                        <span>LOC</span>
                    </button>

                    <!-- Mobile Diagnostic Button -->
                    <button type="button" @click="openDiagModal()"
                        class="flex items-center gap-1 px-1.5 py-1 sm:px-2.5 sm:py-2 rounded-lg bg-emerald-500/15 hover:bg-emerald-500/25 border border-emerald-400/40 text-emerald-300 text-[10px] sm:text-[11px] font-bold tracking-wide transition-all shadow-sm active:scale-95 min-h-[34px] sm:min-h-[38px] cursor-pointer shrink-0"
                        title="Hair System &amp; Topper Diagnostic Assessment">
                        <i class="fa-solid fa-stethoscope text-emerald-400 text-xs"></i>
                        <span>DIAG</span>
                    </button>

                    <!-- Mobile Calculator / Quote Button -->
                    <button type="button" @click="isCalculatorModalOpen = true"
                        class="flex items-center gap-1 px-1.5 py-1 sm:px-2.5 sm:py-2 rounded-lg bg-sky-500/15 hover:bg-sky-500/25 border border-sky-400/40 text-sky-300 text-[10px] sm:text-[11px] font-bold tracking-wide transition-all shadow-sm active:scale-95 min-h-[34px] sm:min-h-[38px] cursor-pointer shrink-0"
                        title="Instant Custom Quote &amp; Cost Calculator">
                        <i class="fa-solid fa-calculator text-sky-400 text-xs"></i>
                        <span>QUOTE</span>
                    </button>

                    <!-- Mobile Booking Button -->
                    <button type="button" @click="openBookingModal()"
                        class="flex items-center gap-1 px-1.5 py-1 sm:px-2.5 sm:py-2 rounded-lg bg-amber-500/15 hover:bg-amber-500/25 border border-amber-400/40 text-amber-300 text-[10px] sm:text-[11px] font-bold tracking-wide transition-all shadow-sm active:scale-95 min-h-[34px] sm:min-h-[38px] cursor-pointer shrink-0"
                        title="Book Private 1-on-1 Consultation &amp; Fitting Suite">
                        <i class="fa-solid fa-calendar-check text-amber-400 text-xs"></i>
                        <span>BOOK</span>
                    </button>
                </div>
            </div>

            <!-- Navigation Links (Desktop) -->
            <nav
                class="hidden lg:flex items-center gap-3.5 xl:gap-5 text-xs xl:text-sm font-semibold text-slate-300 uppercase tracking-wider flex-nowrap whitespace-nowrap shrink-0">

                <!-- 1. All About Us Dropdown -->
                <div class="relative group whitespace-nowrap shrink-0" x-data="{ productsOpen: false }" @mouseenter="productsOpen = true" @mouseleave="productsOpen = false">
                    <button type="button" @click="productsOpen = !productsOpen" class="hover:text-brand-tealLight transition-colors flex items-center gap-1.5 uppercase tracking-wider py-2 focus:outline-none cursor-pointer whitespace-nowrap shrink-0 font-bold">
                        <span>ALL OF US</span>
                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200" :class="{ 'rotate-180': productsOpen }"></i>
                    </button>
                    <div x-show="productsOpen" x-cloak
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                        class="absolute left-0 top-full pt-1 w-64 z-50 normal-case">
                        <div class="rounded-xl bg-brand-cardBg/95 backdrop-blur-md border border-brand-border shadow-2xl p-2 space-y-1 flex flex-col">
                            <a href="/solutions" @click="productsOpen = false" class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-brand-cardInner text-slate-200 hover:text-white transition-colors group/item">
                                <div class="w-8 h-8 rounded-lg bg-sky-500/15 border border-sky-500/30 flex items-center justify-center text-sky-400 group-hover/item:scale-105 transition-transform">
                                    <i class="fa-solid fa-layer-group text-xs"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-xs uppercase tracking-wider">All Solutions (11)</div>
                                    <div class="text-[10px] text-brand-textMuted">Wigs, Systems &amp; Extensions</div>
                                </div>
                            </a>
                            <a href="/stock" @click="productsOpen = false" class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-brand-cardInner text-slate-200 hover:text-white transition-colors group/item">
                                <div class="w-8 h-8 rounded-lg bg-amber-500/15 border border-amber-500/30 flex items-center justify-center text-amber-400 group-hover/item:scale-105 transition-transform">
                                    <i class="fa-solid fa-boxes-stacked text-xs"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-xs uppercase tracking-wider">Stock</div>
                                    <div class="text-[10px] text-brand-textMuted">Ready-to-Ship Inventory</div>
                                </div>
                            </a>
                            <a href="/salon" @click="productsOpen = false" class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-brand-cardInner text-slate-200 hover:text-white transition-colors group/item">
                                <div class="w-8 h-8 rounded-lg bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-emerald-400 group-hover/item:scale-105 transition-transform">
                                    <i class="fa-solid fa-scissors text-xs"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-xs uppercase tracking-wider">Salon</div>
                                    <div class="text-[10px] text-brand-textMuted">Melbourne Private Studio</div>
                                </div>
                            </a>
                            <a href="/factory-b2b" @click="productsOpen = false" class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-brand-cardInner text-slate-200 hover:text-white transition-colors group/item">
                                <div class="w-8 h-8 rounded-lg bg-purple-500/15 border border-purple-500/30 flex items-center justify-center text-purple-400 group-hover/item:scale-105 transition-transform">
                                    <i class="fa-solid fa-industry text-xs"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-xs uppercase tracking-wider">Factory &amp; B2B</div>
                                    <div class="text-[10px] text-brand-textMuted">Direct Craft &amp; Wholesale Partner</div>
                                </div>
                            </a>
                            <a href="/solutions#wig-training" @click="productsOpen = false" class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-brand-cardInner text-amber-300 hover:text-amber-200 transition-colors group/item border-t border-brand-border/40 mt-1">
                                <div class="w-8 h-8 rounded-lg bg-amber-500/20 border border-amber-500/40 flex items-center justify-center text-amber-400 group-hover/item:scale-105 transition-transform">
                                    <i class="fa-solid fa-graduation-cap text-xs"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-xs uppercase tracking-wider">Academy Course</div>
                                    <div class="text-[10px] text-amber-400/80">Wig Maker &amp; Extensionist</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 2. MEDICAL WIGS Dropdown -->
                <div class="relative group whitespace-nowrap shrink-0" x-data="{ medicalOpen: false }" @mouseenter="medicalOpen = true" @mouseleave="medicalOpen = false">
                    <button type="button" @click="medicalOpen = !medicalOpen" class="hover:text-rose-300 transition-colors flex items-center gap-1.5 uppercase tracking-wider py-2 focus:outline-none cursor-pointer whitespace-nowrap shrink-0 font-bold text-rose-300">
                        <i class="fa-solid fa-hand-holding-heart text-rose-400 text-xs"></i>
                        <span>MEDICAL WIGS</span>
                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-rose-500/20 text-rose-300 border border-rose-500/30 font-mono tracking-tight shadow-sm">REBATE</span>
                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200" :class="{ 'rotate-180': medicalOpen }"></i>
                    </button>
                    <div x-show="medicalOpen" x-cloak
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                        class="absolute left-0 top-full pt-1 w-80 z-50 normal-case">
                        <div class="rounded-xl bg-brand-cardBg/95 backdrop-blur-md border border-brand-border shadow-2xl p-2 space-y-1.5 flex flex-col">
                            <a href="/medical-wigs" @click="medicalOpen = false;"
                                class="flex items-start gap-3 p-2.5 rounded-lg hover:bg-brand-cardInner text-slate-200 hover:text-white transition-all group/item cursor-pointer">
                                <div class="w-8 h-8 rounded-lg bg-rose-500/20 border border-rose-400/40 flex items-center justify-center text-rose-300 group-hover/item:scale-105 transition-transform flex-shrink-0 mt-0.5">
                                    <i class="fa-solid fa-hand-holding-heart text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1">
                                        <span class="font-bold text-xs uppercase tracking-wider text-rose-300">Medical Wigs &amp; Alopecia</span>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-rose-500/30 text-rose-200 border border-rose-400/40 font-mono tracking-tight flex-shrink-0">REBATE</span>
                                    </div>
                                    <div class="text-[10px] text-slate-300 mt-0.5">Health Fund Rebate ($250–$600+) &amp; NDIS &bull; 100% Private Suite</div>
                                </div>
                            </a>
                            <a href="/ffa" @click="medicalOpen = false;"
                                class="flex items-start gap-3 p-2.5 rounded-lg hover:bg-brand-cardInner text-slate-200 hover:text-white transition-all group/item cursor-pointer">
                                <div class="w-8 h-8 rounded-lg bg-teal-500/20 border border-teal-400/40 flex items-center justify-center text-teal-300 group-hover/item:scale-105 transition-transform flex-shrink-0 mt-0.5">
                                    <i class="fa-solid fa-head-side-mask text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1">
                                        <span class="font-bold text-xs uppercase tracking-wider text-teal-300">Frontal Fibrosing Alopecia (FFA)</span>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-teal-400 text-slate-900 font-mono tracking-tight flex-shrink-0">NEW</span>
                                    </div>
                                    <div class="text-[10px] text-slate-300 mt-0.5">Breathable Front Fringe Headband Hairpiece &bull; 1:1 Video Showcase</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 3. SELF CHECK Dropdown -->
                <div class="relative group whitespace-nowrap shrink-0" x-data="{ selfCheckOpen: false }" @mouseenter="selfCheckOpen = true" @mouseleave="selfCheckOpen = false">
                    <button type="button" @click="selfCheckOpen = !selfCheckOpen" class="hover:text-brand-tealLight transition-colors flex items-center gap-1.5 uppercase tracking-wider py-2 focus:outline-none cursor-pointer font-bold text-white whitespace-nowrap shrink-0">
                        <i class="fa-solid fa-clipboard-check text-teal-400"></i>
                        <span>SELF CHECK</span>
                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200" :class="{ 'rotate-180': selfCheckOpen }"></i>
                    </button>
                    <div x-show="selfCheckOpen" x-cloak
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                        class="absolute left-0 top-full pt-1 w-[360px] z-50 normal-case">
                        <div class="rounded-xl bg-brand-cardBg/95 backdrop-blur-md border border-brand-border shadow-2xl p-2 space-y-1.5 flex flex-col">
                            <a href="/diagnosis" @click="selfCheckOpen = false;"
                                class="w-full text-left flex items-start gap-3 p-2.5 rounded-lg hover:bg-brand-cardInner text-slate-200 hover:text-white transition-all group/item cursor-pointer">
                                <div class="w-8 h-8 rounded-lg bg-emerald-500/20 border border-emerald-400/40 flex items-center justify-center text-emerald-300 group-hover/item:scale-105 transition-transform flex-shrink-0 mt-0.5">
                                    <i class="fa-solid fa-stethoscope text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1">
                                        <span class="font-bold text-xs uppercase tracking-wider text-emerald-300">Self-Assessment Hub</span>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-emerald-500/25 text-emerald-200 border border-emerald-400/40 font-mono tracking-tight flex-shrink-0">5-STEP</span>
                                    </div>
                                    <div class="text-[10px] text-slate-300 mt-0.5">Wizard, Norwood Matrix &amp; Bespoke System Guide</div>
                                </div>
                            </a>
                            <button type="button" @click="openFfaModal(); selfCheckOpen = false;"
                                class="w-full text-left flex items-start gap-3 p-2.5 rounded-lg hover:bg-brand-cardInner text-slate-200 hover:text-white transition-all group/item cursor-pointer">
                                <div class="w-8 h-8 rounded-lg bg-teal-500/20 border border-teal-400/40 flex items-center justify-center text-teal-300 group-hover/item:scale-105 transition-transform flex-shrink-0 mt-0.5">
                                    <i class="fa-solid fa-head-side-mask text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1">
                                        <span class="font-bold text-xs uppercase tracking-wider text-teal-300">FFA Clinical Screening Tool</span>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-teal-400 text-slate-900 font-mono tracking-tight flex-shrink-0">FFA</span>
                                    </div>
                                    <div class="text-[10px] text-teal-200/80 mt-0.5">Interactive Questionnaire &bull; Stages I–V Guide</div>
                                </div>
                            </button>
                            <button type="button" @click="openComparisonModal(); selfCheckOpen = false;"
                                class="w-full text-left flex items-start gap-3 p-2.5 rounded-lg hover:bg-brand-cardInner text-slate-200 hover:text-white transition-all group/item cursor-pointer">
                                <div class="w-8 h-8 rounded-lg bg-indigo-500/20 border border-indigo-400/40 flex items-center justify-center text-indigo-300 group-hover/item:scale-105 transition-transform flex-shrink-0 mt-0.5">
                                    <i class="fa-solid fa-scale-balanced text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1">
                                        <span class="font-bold text-xs uppercase tracking-wider text-teal-300">Style Diagnostic &amp; Comparison</span>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-indigo-500/30 text-indigo-200 border border-indigo-400/40 font-mono tracking-tight flex-shrink-0">GUIDE</span>
                                    </div>
                                    <div class="text-[10px] text-slate-300 mt-0.5">Men's Toupees vs. Women's Toppers Architectural Guide</div>
                                </div>
                            </button>
                            <button type="button" @click="isCalculatorModalOpen = true; selfCheckOpen = false;"
                                class="w-full text-left flex items-start gap-3 p-2.5 rounded-lg hover:bg-brand-cardInner text-slate-200 hover:text-white transition-all group/item cursor-pointer">
                                <div class="w-8 h-8 rounded-lg bg-teal-500/15 border border-teal-500/30 flex items-center justify-center text-brand-tealLight group-hover/item:scale-105 transition-transform flex-shrink-0 mt-0.5">
                                    <i class="fa-solid fa-calculator text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1">
                                        <span class="font-bold text-xs uppercase tracking-wider text-teal-300">Hair System Cost Calculator</span>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-teal-500/20 text-teal-300 border border-teal-400/30 font-mono tracking-tight flex-shrink-0">CALC</span>
                                    </div>
                                    <div class="text-[10px] text-brand-textMuted mt-0.5">Live Custom Price Quote &bull; Base Size, Length &amp; Density</div>
                                </div>
                            </button>
                            <div class="border-t border-brand-border/40 my-1 pt-1.5 px-2.5 flex items-center justify-between">
                                <span class="text-[10px] font-mono uppercase tracking-wider text-teal-300 font-bold flex items-center gap-1.5">
                                    <i class="fa-solid fa-wand-magic-sparkles text-[10px]"></i>
                                    <span>Virtual Try-On &amp; Transformations</span>
                                </span>
                                <span class="text-[9px] px-1.5 py-0.5 rounded bg-teal-400/20 text-teal-300 font-mono font-bold">TRY ON</span>
                            </div>
                            <a href="/ai-tryon" @click="selfCheckOpen = false;"
                                class="flex items-start gap-3 p-2.5 rounded-lg hover:bg-brand-cardInner text-slate-200 hover:text-white transition-all group/item cursor-pointer">
                                <div class="w-8 h-8 rounded-lg bg-amber-500/15 border border-amber-500/30 flex items-center justify-center text-amber-400 group-hover/item:scale-105 transition-transform flex-shrink-0 mt-0.5">
                                    <i class="fa-solid fa-wand-magic-sparkles text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1">
                                        <span class="font-bold text-xs uppercase tracking-wider text-amber-300">AI Virtual Hairstyle Try-On</span>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-400/30 font-mono tracking-tight flex-shrink-0">AI TECH</span>
                                    </div>
                                    <div class="text-[10px] text-brand-textMuted mt-0.5">Live Camera &amp; Photo Instant Hairstyle Simulator</div>
                                </div>
                            </a>
                            <a href="/transformations" @click="selfCheckOpen = false;"
                                class="flex items-start gap-3 p-2.5 rounded-lg hover:bg-brand-cardInner text-slate-200 hover:text-white transition-all group/item cursor-pointer">
                                <div class="w-8 h-8 rounded-lg bg-amber-500/20 border border-amber-500/40 flex items-center justify-center text-amber-300 group-hover/item:scale-105 transition-transform flex-shrink-0 mt-0.5">
                                    <i class="fa-solid fa-arrows-split-up-and-left text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1">
                                        <span class="font-bold text-xs uppercase tracking-wider text-amber-300">10-Year Youth Transformations</span>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-400/30 font-mono tracking-tight flex-shrink-0">10-YR YOUTH</span>
                                    </div>
                                    <div class="text-[10px] text-brand-textMuted mt-0.5">Real Client Gallery &bull; Interactive Before/After Split Slider</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 4. FAQ Page Link -->
                <a href="/faq"
                    class="hover:text-brand-tealLight transition-colors flex items-center gap-1 font-bold text-white uppercase tracking-wider cursor-pointer whitespace-nowrap shrink-0">
                    <i class="fa-solid fa-circle-question text-teal-400 text-xs"></i>
                    <span>FAQ</span>
                </a>
            </nav>

            <!-- Right Utilities: LOC, DIAG, QUOTE, BOOK, LOGIN/SIGNUP, HAMBURGER -->
            <div class="flex items-center gap-1 sm:gap-1.5 xl:gap-2 shrink-0 flex-nowrap whitespace-nowrap">
                <button type="button" @click="openMapModal()"
                    class="hidden md:flex items-center gap-1 px-2 py-1.5 rounded-lg bg-teal-500/15 hover:bg-teal-500/25 border border-teal-400/40 text-teal-300 text-[11px] font-bold transition-all shadow-sm active:scale-95 cursor-pointer shrink-0 whitespace-nowrap"
                    title="Melbourne Studio Location &amp; Directions">
                    <i class="fa-solid fa-location-dot text-teal-400 text-xs"></i>
                    <span>LOC</span>
                </button>

                <button type="button" @click="openDiagModal()"
                    class="hidden md:flex items-center gap-1 px-2 py-1.5 rounded-lg bg-emerald-500/15 hover:bg-emerald-500/25 border border-emerald-400/40 text-emerald-300 text-[11px] font-bold transition-all shadow-sm active:scale-95 cursor-pointer shrink-0 whitespace-nowrap"
                    title="Hair System &amp; Topper Diagnostic Assessment">
                    <i class="fa-solid fa-stethoscope text-emerald-400 text-xs"></i>
                    <span>DIAG</span>
                </button>

                <button type="button" @click="isCalculatorModalOpen = true"
                    class="hidden md:flex items-center gap-1 px-2 py-1.5 rounded-lg bg-sky-500/15 hover:bg-sky-500/25 border border-sky-400/40 text-sky-300 text-[11px] font-bold transition-all shadow-sm active:scale-95 cursor-pointer shrink-0 whitespace-nowrap"
                    title="Instant Custom Quote &amp; Cost Calculator">
                    <i class="fa-solid fa-calculator text-sky-400 text-xs"></i>
                    <span>QUOTE</span>
                </button>

                <button type="button" @click="openBookingModal()"
                    class="hidden md:flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-amber-500/20 hover:bg-amber-500/30 border border-amber-400/50 text-amber-300 text-[11px] font-bold transition-all shadow-sm active:scale-95 cursor-pointer shrink-0 whitespace-nowrap"
                    title="Book Private 1-on-1 Consultation &amp; Fitting">
                    <i class="fa-solid fa-calendar-check text-amber-400 text-xs"></i>
                    <span>BOOK</span>
                </button>

                <!-- LOGIN / SIGNUP -->
                <div class="flex items-center shrink-0">
                    <div x-show="!currentMember" class="flex items-center">
                        <button type="button" @click="openSignInModal()"
                            class="w-8 h-8 rounded-lg teal-gradient hover:opacity-95 text-white transition-all flex items-center justify-center shadow-md active:scale-95 cursor-pointer shrink-0 border border-teal-400/40"
                            title="VIP Membership Sign In / Sign Up"
                            aria-label="Sign In or Sign Up">
                            <i class="fa-solid fa-circle-user text-base"></i>
                        </button>
                    </div>
                    <div x-show="currentMember" x-cloak class="flex items-center gap-1.5">
                        <button type="button" @click="openMemberProfileModal()"
                            class="px-2.5 sm:px-3 py-1.5 rounded-lg bg-brand-cardBg hover:bg-brand-cardInner border border-teal-500/50 text-xs font-semibold transition-all flex items-center gap-2 shadow-sm cursor-pointer"
                            title="Open My VIP Dashboard">
                            <div class="w-6 h-6 rounded-full teal-gradient flex items-center justify-center font-bold text-white text-[10px]" x-text="currentMember ? currentMember.full_name.charAt(0).toUpperCase() : 'M'"></div>
                            <div class="text-left leading-tight hidden xs:block">
                                <div class="text-white font-bold truncate max-w-[100px] flex items-center gap-1">
                                    <span x-text="currentMember ? currentMember.full_name : ''"></span>
                                    <span class="text-[9px] px-1 py-0.5 rounded font-mono" :class="{
                                        'bg-amber-500/20 text-amber-300': currentMember && currentMember.tier === 'Bronze',
                                        'bg-slate-700 text-slate-300': currentMember && currentMember.tier === 'Silver',
                                        'bg-yellow-500/20 text-yellow-300': currentMember && currentMember.tier === 'Gold',
                                        'bg-purple-500/20 text-purple-300': currentMember && currentMember.tier === 'VIP Partner'
                                    }" x-text="currentMember ? currentMember.tier : ''"></span>
                                </div>
                            </div>
                        </button>
                        <button type="button" @click="memberLogout()"
                            class="w-8 h-8 rounded-lg bg-rose-950/60 hover:bg-rose-900 border border-rose-800/80 text-rose-300 text-xs transition-colors flex items-center justify-center cursor-pointer"
                            title="Sign Out">
                            <i class="fa-solid fa-power-off"></i>
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu Hamburger Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="lg:hidden text-slate-200 text-xl p-2 rounded-lg border min-w-[40px] min-h-[40px] flex items-center justify-center transition-all duration-200 cursor-pointer"
                    :class="mobileMenuOpen ? 'bg-[#202E55] text-brand-tealLight border-teal-500/50 shadow-inner' : 'hover:bg-brand-cardBg border-brand-border/60'"
                    aria-label="Toggle navigation menu">
                    <i :class="mobileMenuOpen ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Drawer -->
        <div x-show="mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="lg:hidden bg-gradient-to-b from-[#202E55] via-[#1B274B] to-[#16203F] border-t border-slate-700/60 border-b-2 border-teal-500/40 px-5 py-5 space-y-3 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.85)] max-h-[85vh] overflow-y-auto backdrop-blur-xl">

            <!-- Quick Home Link -->
            <a href="/" @click="mobileMenuOpen = false"
                class="w-full flex items-center justify-between p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-slate-600/60 text-white text-xs font-bold transition-colors">
                <span class="flex items-center gap-2.5">
                    <span class="w-6 h-6 rounded-md teal-gradient flex items-center justify-center font-heading font-extrabold text-[11px] text-white">DH</span>
                    <span>Home &mdash; All Sections Hub</span>
                </span>
                <i class="fa-solid fa-house text-teal-400 text-xs"></i>
            </a>

            <!-- Mobile Quick Action Grid -->
            <div class="grid grid-cols-2 gap-2">
                <button type="button" @click="openBookingModal(); mobileMenuOpen = false;"
                    class="flex items-center gap-2 p-2.5 rounded-xl bg-amber-500/15 hover:bg-amber-500/25 border border-amber-400/40 text-amber-300 text-xs font-bold text-left cursor-pointer">
                    <i class="fa-solid fa-calendar-check text-amber-400"></i>
                    <span>Book Fitting</span>
                </button>
                <button type="button" @click="openMapModal(); mobileMenuOpen = false;"
                    class="flex items-center gap-2 p-2.5 rounded-xl bg-teal-500/15 hover:bg-teal-500/25 border border-teal-400/40 text-teal-300 text-xs font-bold text-left cursor-pointer">
                    <i class="fa-solid fa-location-dot text-teal-400"></i>
                    <span>Studio Map</span>
                </button>
                <button type="button" @click="openDiagModal(); mobileMenuOpen = false;"
                    class="flex items-center gap-2 p-2.5 rounded-xl bg-emerald-500/15 hover:bg-emerald-500/25 border border-emerald-400/40 text-emerald-300 text-xs font-bold text-left cursor-pointer">
                    <i class="fa-solid fa-stethoscope text-emerald-400"></i>
                    <span>Diagnostic</span>
                </button>
                <button type="button" @click="isCalculatorModalOpen = true; mobileMenuOpen = false;"
                    class="flex items-center gap-2 p-2.5 rounded-xl bg-sky-500/15 hover:bg-sky-500/25 border border-sky-400/40 text-sky-300 text-xs font-bold text-left cursor-pointer">
                    <i class="fa-solid fa-calculator text-sky-400"></i>
                    <span>Cost Quote</span>
                </button>
            </div>

            <!-- Mobile Membership Box -->
            <div x-show="!currentMember" class="p-3 rounded-xl bg-[#121933]/90 border border-slate-700/80 flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg teal-gradient flex items-center justify-center text-white text-xs">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white">VIP Membership</div>
                        <div class="text-[10px] text-teal-300 font-mono">+100 Pts Welcome Bonus</div>
                    </div>
                </div>
                <button type="button" @click="openSignInModal(); mobileMenuOpen = false;"
                    class="py-2 px-3 rounded-lg teal-gradient text-white text-xs font-bold shadow cursor-pointer">
                    Sign In
                </button>
            </div>

            <div x-show="currentMember" x-cloak class="p-3 rounded-xl bg-[#121933]/90 border border-teal-500/40 space-y-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full teal-gradient flex items-center justify-center text-white font-bold text-xs" x-text="currentMember ? currentMember.full_name.charAt(0).toUpperCase() : 'M'"></div>
                        <div>
                            <div class="text-xs font-bold text-white" x-text="currentMember ? currentMember.full_name : ''"></div>
                            <div class="text-[10px] text-brand-tealLight font-mono">
                                <span x-text="currentMember ? currentMember.tier : ''"></span> &bull; <span x-text="Number((currentMember && currentMember.points) || 0).toLocaleString() + ' Pts'"></span>
                            </div>
                        </div>
                    </div>
                    <button type="button" @click="memberLogout(); mobileMenuOpen = false;" class="text-[11px] text-rose-300 bg-rose-950/80 px-2.5 py-1 rounded border border-rose-800 cursor-pointer">
                        Sign Out
                    </button>
                </div>
                <button type="button" @click="openMemberProfileModal(); mobileMenuOpen = false;"
                    class="w-full py-2 rounded-lg bg-slate-800 border border-slate-600/70 text-xs font-bold text-teal-300 cursor-pointer">
                    Open VIP Dashboard &amp; Points
                </button>
            </div>

            <!-- Mobile Page Links -->
            <div class="flex flex-col space-y-1 pt-1 border-t border-slate-700/60">
                <a href="/medical-wigs" @click="mobileMenuOpen = false" class="flex items-center justify-between py-2.5 px-2.5 rounded-lg hover:bg-white/5 text-xs font-bold text-rose-300">
                    <span class="flex items-center gap-2.5"><i class="fa-solid fa-hand-holding-heart w-4 text-center"></i>Medical Wigs &amp; Alopecia</span>
                    <span class="text-[9px] px-1.5 py-0.5 rounded bg-rose-500/20 text-rose-300 font-mono">REBATE</span>
                </a>
                <a href="/ffa" @click="mobileMenuOpen = false" class="flex items-center justify-between py-2.5 px-2.5 rounded-lg hover:bg-white/5 text-xs font-bold text-teal-300">
                    <span class="flex items-center gap-2.5"><i class="fa-solid fa-head-side-mask w-4 text-center"></i>Frontal Fibrosing Alopecia (FFA)</span>
                    <span class="text-[9px] px-1.5 py-0.5 rounded bg-teal-400 text-slate-900 font-mono">NEW</span>
                </a>
                <a href="/solutions" @click="mobileMenuOpen = false" class="flex items-center justify-between py-2.5 px-2.5 rounded-lg hover:bg-white/5 text-xs font-bold text-slate-100">
                    <span class="flex items-center gap-2.5"><i class="fa-solid fa-layer-group w-4 text-center text-sky-400"></i>All Solutions (11)</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                </a>
                <a href="/diagnosis" @click="mobileMenuOpen = false" class="flex items-center justify-between py-2.5 px-2.5 rounded-lg hover:bg-white/5 text-xs font-bold text-slate-100">
                    <span class="flex items-center gap-2.5"><i class="fa-solid fa-clipboard-check w-4 text-center text-emerald-400"></i>Self-Assessment &amp; Style Guide</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                </a>
                <a href="/stock" @click="mobileMenuOpen = false" class="flex items-center justify-between py-2.5 px-2.5 rounded-lg hover:bg-white/5 text-xs font-bold text-slate-100">
                    <span class="flex items-center gap-2.5"><i class="fa-solid fa-boxes-stacked w-4 text-center text-amber-400"></i>In-Stock Precision Pieces</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                </a>
                <a href="/salon" @click="mobileMenuOpen = false" class="flex items-center justify-between py-2.5 px-2.5 rounded-lg hover:bg-white/5 text-xs font-bold text-slate-100">
                    <span class="flex items-center gap-2.5"><i class="fa-solid fa-scissors w-4 text-center text-emerald-400"></i>Springvale Salon &amp; Performance</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                </a>
                <a href="/transformations" @click="mobileMenuOpen = false" class="flex items-center justify-between py-2.5 px-2.5 rounded-lg hover:bg-white/5 text-xs font-bold text-amber-300">
                    <span class="flex items-center gap-2.5"><i class="fa-solid fa-arrows-split-up-and-left w-4 text-center"></i>10-Year Youth Transformations</span>
                    <span class="text-[9px] px-1.5 py-0.5 rounded bg-amber-500/20 text-amber-300 font-mono">B/A</span>
                </a>
                <a href="/ai-tryon" @click="mobileMenuOpen = false" class="flex items-center justify-between py-2.5 px-2.5 rounded-lg hover:bg-white/5 text-xs font-bold text-amber-300">
                    <span class="flex items-center gap-2.5"><i class="fa-solid fa-wand-magic-sparkles w-4 text-center"></i>AI Virtual Hairstyle Try-On</span>
                    <span class="text-[9px] px-1.5 py-0.5 rounded bg-amber-500/20 text-amber-300 font-mono">AI</span>
                </a>
                <a href="/factory-b2b" @click="mobileMenuOpen = false" class="flex items-center justify-between py-2.5 px-2.5 rounded-lg hover:bg-white/5 text-xs font-bold text-slate-100">
                    <span class="flex items-center gap-2.5"><i class="fa-solid fa-industry w-4 text-center text-purple-400"></i>Factory Direct &amp; B2B Wholesale</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                </a>
                <a href="/faq" @click="mobileMenuOpen = false" class="flex items-center justify-between py-2.5 px-2.5 rounded-lg hover:bg-white/5 text-xs font-bold text-slate-100">
                    <span class="flex items-center gap-2.5"><i class="fa-solid fa-circle-question w-4 text-center text-teal-400"></i>Frequently Asked Questions</span>
                    <span class="text-[9px] px-1.5 py-0.5 rounded bg-teal-500/20 text-teal-300 font-mono">Q&amp;A</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Mobile Menu Backdrop Overlay -->
    <div x-show="mobileMenuOpen" x-cloak @click="mobileMenuOpen = false"
        x-transition:enter="transition-opacity ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 lg:hidden"
        aria-hidden="true">
    </div>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/61433484882" target="_blank" rel="noopener"
        aria-label="Chat on WhatsApp with Melbourne Specialist"
        class="fixed bottom-5 right-5 z-50 w-12 h-12 sm:w-14 sm:h-14 bg-emerald-600 hover:bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-2xl transition-transform hover:scale-110 active:scale-95 group">
        <i class="fa-brands fa-whatsapp text-2xl sm:text-3xl"></i>
        <span
            class="hidden md:block absolute right-16 bg-brand-cardBg text-slate-200 text-xs font-semibold px-3 py-1.5 rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity border border-brand-border shadow-xl">
            Chat with Melbourne Specialist
        </span>
    </a>

