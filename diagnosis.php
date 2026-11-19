<?php
$pageTitle       = 'Hair System Self-Assessment & Style Diagnostic Guide | David Hair Melbourne';
$pageDescription = "Find your ideal hair system in 60 seconds. Interactive men's Norwood stage & women's hair loss diagnostic wizard, hairline matrix, and tailored base recommendations.";
$pageKeywords    = 'hair system diagnostic tool, which toupee is best for me, women hair topper size guide, norwood stage hair system melbourne';
$canonicalPath   = '/diagnosis';
$activePage      = 'hair-diagnosis-hub';
$extraHeadScripts = [];
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/subpage_bar.php';
?>
<main id="main-content" class="w-full min-h-screen">
<?php dh_render_subpage_top('Self-Assessment & Style Guide', '5-STEP TOOL'); ?>
    <section id="hair-diagnosis-hub"
        class="bg-gradient-to-b from-brand-darkBg via-[#0D152F] to-brand-darkBg border-y border-brand-border/80 relative overflow-hidden">
        
        <!-- Ambient Background Glow Effects -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Section Header & Option Toggle Controller -->
            <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-12">
                <button type="button" @click="openComparisonModal()"
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-teal-500/15 hover:bg-teal-500/25 border border-teal-400/40 hover:border-teal-300 text-teal-300 hover:text-white text-xs font-bold uppercase tracking-wider mb-3 shadow-sm hover:scale-105 active:scale-95 transition-all cursor-pointer group"
                    title="Open Self-Assessment & Style Diagnostic Tool (comparison.html)">
                    <i class="fa-solid fa-clipboard-question text-teal-400 group-hover:scale-110 transition-transform"></i>
                    <span>Self-Assessment &amp; Style Diagnostic Tool</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px] opacity-70 group-hover:opacity-100"></i>
                </button>
                <h2 class="font-heading text-2xl sm:text-4xl font-extrabold text-white tracking-tight">
                    Which Hair System Is Best for Me? <br class="hidden sm:inline">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-sky-400 to-amber-300">
                        Self-Assessment &amp; Bespoke System Guide
                    </span>
                </h2>
                <p class="text-slate-300 text-xs sm:text-base mt-3 leading-relaxed">
                    Instantly identify your optimal base construction and tailored hair specifications based on your hair loss stage (Phase), preferred hairstyle, and critically, <span class="text-amber-300 font-bold underline decoration-amber-400/50 underline-offset-4">"Forehead Hairline Exposure"</span>.
                </p>

                <!-- Self-Assessment & Diagnostic Tool Modal Trigger Buttons -->
                <div class="mt-7 mb-2 flex flex-wrap items-center justify-center gap-3">
                    <button type="button" @click="openComparisonModal()"
                        class="px-6 sm:px-8 py-3.5 sm:py-4 rounded-2xl bg-gradient-to-r from-sky-600 via-indigo-600 to-teal-600 hover:from-sky-500 hover:to-teal-500 text-white font-extrabold text-sm sm:text-base shadow-xl shadow-sky-500/25 ring-2 ring-sky-400/60 hover:ring-sky-300 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center gap-3.5 cursor-pointer group">
                        <span class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white text-lg shadow-inner group-hover:rotate-12 transition-transform shrink-0">
                            <i class="fa-solid fa-scale-balanced"></i>
                        </span>
                        <div class="text-left">
                            <span class="block leading-tight font-extrabold tracking-tight">Self-Assessment &amp; Style Diagnostic Tool</span>
                            <span class="text-[11px] text-sky-100 font-normal">Men's Toupees vs. Women's Toppers Architectural Guide</span>
                        </div>
                        <span class="ml-1 px-2.5 py-1 rounded-lg bg-black/35 border border-white/20 text-[10px] font-mono font-bold tracking-wider uppercase flex items-center gap-1 text-white">
                            <span>Open Modal</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                        </span>
                    </button>

                    <button type="button" @click="openDiagModal()"
                        class="px-6 sm:px-8 py-3.5 sm:py-4 rounded-2xl bg-gradient-to-r from-teal-500 via-emerald-500 to-teal-600 hover:from-teal-400 hover:to-emerald-400 text-white font-extrabold text-sm sm:text-base shadow-xl shadow-teal-500/25 ring-2 ring-teal-400/60 hover:ring-teal-300 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center gap-3.5 cursor-pointer group">
                        <span class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white text-lg shadow-inner group-hover:rotate-12 transition-transform shrink-0">
                            <i class="fa-solid fa-stethoscope"></i>
                        </span>
                        <div class="text-left">
                            <span class="block leading-tight font-extrabold tracking-tight">Open 5-Step Diagnostic Assessment</span>
                            <span class="text-[11px] text-teal-100 font-normal">Norwood Stage Scalp Drawings &amp; Women's 7 Hair Loss Conditions</span>
                        </div>
                        <span class="ml-1 px-2.5 py-1 rounded-lg bg-black/35 border border-white/20 text-[10px] font-mono font-bold tracking-wider uppercase flex items-center gap-1 text-white">
                            <span>Open Modal</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                        </span>
                    </button>
                </div>

                <!-- Comparison Toggle Pills -->
                <div class="mt-5 inline-flex flex-wrap items-center justify-center p-1.5 rounded-2xl bg-slate-900/90 border border-slate-700/80 shadow-2xl max-w-full"
                     x-show="isSectionVisible('diagnosis-option1') || isSectionVisible('diagnosis-option2')">
                    <button type="button" @click="activeDiagnosisView = 'wizard'"
                        :class="activeDiagnosisView === 'wizard' ? 'bg-gradient-to-r from-teal-600 to-teal-500 text-white font-bold shadow-lg ring-1 ring-teal-400/50' : 'text-slate-300 hover:text-white hover:bg-slate-800/60'"
                        class="px-4 sm:px-5 py-2.5 rounded-xl text-xs sm:text-sm transition-all flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-list-check text-xs"></i>
                        <span>Option 1: Step-by-Step Interactive Quiz</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-black/40 font-mono font-bold">Wizard</span>
                    </button>
                    <button type="button" @click="activeDiagnosisView = 'matrix'"
                        :class="activeDiagnosisView === 'matrix' ? 'bg-gradient-to-r from-sky-600 to-indigo-600 text-white font-bold shadow-lg ring-1 ring-sky-400/50' : 'text-slate-300 hover:text-white hover:bg-slate-800/60'"
                        class="px-4 sm:px-5 py-2.5 rounded-xl text-xs sm:text-sm transition-all flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-diagram-project text-xs"></i>
                        <span>Option 2: Visual Diagram &amp; Hairline Matrix</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-black/40 font-mono font-bold">Matrix</span>
                    </button>
                    <button type="button" @click="activeDiagnosisView = 'both'"
                        x-show="isSectionVisible('diagnosis-option1') && isSectionVisible('diagnosis-option2')"
                        :class="activeDiagnosisView === 'both' ? 'bg-gradient-to-r from-amber-600 to-orange-500 text-white font-bold shadow-lg ring-1 ring-amber-400/50' : 'text-slate-300 hover:text-white hover:bg-slate-800/60'"
                        class="px-3.5 sm:px-4 py-2.5 rounded-xl text-xs sm:text-sm transition-all flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-columns text-xs"></i>
                        <span>Compare Option 1 &amp; 2 Side-by-Side</span>
                    </button>
                </div>
            </div>

            <!-- ============================================================= -->
            <!-- [OPTION 1] STEP-BY-STEP INTERACTIVE WIZARD                   -->
            <!-- ============================================================= -->
            <div x-show="isSectionVisible('diagnosis-option1') && (activeDiagnosisView === 'wizard' || activeDiagnosisView === 'both')"
                class="mb-16 rounded-3xl bg-gradient-to-b from-[#151F3D] via-[#101732] to-[#0D132A] border-2 border-teal-500/30 p-5 sm:p-8 lg:p-10 shadow-2xl relative transition-all"
                :class="{ 'ring-4 ring-teal-500/20': activeDiagnosisView === 'wizard' }">
                
                <!-- Option 1 Badge Tag -->
                <div class="flex flex-wrap items-center justify-between gap-3 mb-6 pb-5 border-b border-slate-700/60">
                    <div class="flex items-center gap-2.5">
                        <span class="px-3 py-1 rounded-lg bg-teal-500 text-white text-xs font-extrabold uppercase tracking-wider shadow">OPTION 1</span>
                        <h3 class="font-heading text-lg sm:text-xl font-bold text-white">4-Step Interactive Self-Assessment Quiz</h3>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" @click="openDiagModal()"
                            class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-teal-500/20 hover:bg-teal-500/30 border border-teal-400/40 text-teal-300 hover:text-white text-xs font-bold transition-all cursor-pointer">
                            <i class="fa-solid fa-stethoscope text-teal-400"></i>
                            <span>Clinical 5-Step Diagnostic (diag.html)</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-teal-300"></i>
                        </button>
                        <div class="hidden md:flex items-center gap-2 text-xs font-medium text-slate-400">
                            <span class="w-2 h-2 rounded-full bg-teal-400 animate-ping"></span>
                            <span>Instant Calculation</span>
                        </div>
                    </div>
                </div>

                <!-- Wizard Progress Stepper Bar -->
                <div class="mb-8">
                    <div class="grid grid-cols-4 gap-2 sm:gap-4 text-center">
                        <button type="button" @click="quizStep = 1"
                            :class="quizStep >= 1 ? 'text-teal-300 border-teal-400 bg-teal-950/40' : 'text-slate-400 border-slate-700 bg-slate-900/40'"
                            class="p-2 sm:p-3 rounded-xl border text-xs sm:text-sm font-bold transition-all flex flex-col sm:flex-row items-center justify-center gap-1.5 cursor-pointer">
                            <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-mono"
                                :class="quizStep === 1 ? 'bg-teal-400 text-slate-950 font-black' : (quizStep > 1 ? 'bg-teal-500/30 text-teal-300' : 'bg-slate-800 text-slate-400')">1</span>
                            <span>Gender</span>
                        </button>
                        <button type="button" @click="quizStep = 2"
                            :class="quizStep >= 2 ? 'text-teal-300 border-teal-400 bg-teal-950/40' : 'text-slate-400 border-slate-700 bg-slate-900/40'"
                            class="p-2 sm:p-3 rounded-xl border text-xs sm:text-sm font-bold transition-all flex flex-col sm:flex-row items-center justify-center gap-1.5 cursor-pointer">
                            <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-mono"
                                :class="quizStep === 2 ? 'bg-teal-400 text-slate-950 font-black' : (quizStep > 2 ? 'bg-teal-500/30 text-teal-300' : 'bg-slate-800 text-slate-400')">2</span>
                            <span>Hair Loss Phase</span>
                        </button>
                        <button type="button" @click="quizStep = 3"
                            :class="quizStep >= 3 ? 'text-teal-300 border-teal-400 bg-teal-950/40' : 'text-slate-400 border-slate-700 bg-slate-900/40'"
                            class="p-2 sm:p-3 rounded-xl border text-xs sm:text-sm font-bold transition-all flex flex-col sm:flex-row items-center justify-center gap-1.5 cursor-pointer">
                            <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-mono"
                                :class="quizStep === 3 ? 'bg-teal-400 text-slate-950 font-black' : (quizStep > 3 ? 'bg-teal-500/30 text-teal-300' : 'bg-slate-800 text-slate-400')">3</span>
                            <span>Style &amp; Hairline</span>
                        </button>
                        <button type="button" @click="quizStep = 4"
                            :class="quizStep === 4 ? 'text-amber-300 border-amber-400 bg-amber-950/40' : 'text-slate-400 border-slate-700 bg-slate-900/40'"
                            class="p-2 sm:p-3 rounded-xl border text-xs sm:text-sm font-bold transition-all flex flex-col sm:flex-row items-center justify-center gap-1.5 cursor-pointer">
                            <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-mono"
                                :class="quizStep === 4 ? 'bg-amber-400 text-slate-950 font-black' : 'bg-slate-800 text-slate-400'">4</span>
                            <span>Prescription Specs</span>
                        </button>
                    </div>
                </div>

                <!-- STEP 1: GENDER SELECTION -->
                <div x-show="quizStep === 1" x-cloak class="space-y-6">
                    <div class="text-center max-w-xl mx-auto space-y-1">
                        <span class="text-teal-400 text-xs font-bold uppercase tracking-wider">Step 1 / 4</span>
                        <h4 class="font-heading text-xl sm:text-2xl font-bold text-white">Select Your Profile</h4>
                        <p class="text-xs sm:text-sm text-slate-400">Men and women experience distinct hair loss progression patterns requiring entirely different base engineering.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 max-w-2xl mx-auto pt-2">
                        <!-- Men's Card -->
                        <div @click="setQuizGender('men'); quizStep = 2"
                            :class="quizGender === 'men' ? 'border-teal-400 bg-gradient-to-b from-teal-950/60 to-[#121E3D] ring-2 ring-teal-400/50 shadow-xl' : 'border-slate-700 bg-slate-900/60 hover:border-slate-500 hover:bg-slate-900/90'"
                            class="p-6 rounded-2xl border text-center space-y-4 cursor-pointer transition-all group">
                            <div class="w-16 h-16 rounded-2xl bg-teal-500/20 border border-teal-400/40 flex items-center justify-center mx-auto text-teal-400 text-3xl group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-mars"></i>
                            </div>
                            <div>
                                <div class="font-heading text-lg font-extrabold text-white">Men (Custom Toupee)</div>
                                <div class="text-xs text-teal-300 font-semibold mt-0.5">Receding Hairline &amp; Crown Vertex Solution</div>
                            </div>
                            <ul class="text-left text-xs text-slate-300 space-y-2 pt-2 border-t border-slate-700/50">
                                <li class="flex items-center gap-2"><i class="fa-solid fa-check text-teal-400 text-xs"></i> <span>Custom bespoke partial hair system (Toupee)</span></li>
                                <li class="flex items-center gap-2"><i class="fa-solid fa-check text-teal-400 text-xs"></i> <span>Undetectable hairline for slick-back, pompadour or dandy cuts</span></li>
                                <li class="flex items-center gap-2"><i class="fa-solid fa-check text-teal-400 text-xs"></i> <span>4-week waterproof bond for swimming, gym &amp; active Aussie lifestyle</span></li>
                            </ul>
                            <button type="button" class="w-full py-2.5 rounded-xl bg-teal-600 hover:bg-teal-500 text-white font-bold text-xs uppercase tracking-wider transition-colors shadow">
                                Start Men's Assessment &rarr;
                            </button>
                        </div>

                        <!-- Women's Card -->
                        <div @click="setQuizGender('women'); quizStep = 2"
                            :class="quizGender === 'women' ? 'border-pink-400 bg-gradient-to-b from-pink-950/60 to-[#121E3D] ring-2 ring-pink-400/50 shadow-xl' : 'border-slate-700 bg-slate-900/60 hover:border-slate-500 hover:bg-slate-900/90'"
                            class="p-6 rounded-2xl border text-center space-y-4 cursor-pointer transition-all group">
                            <div class="w-16 h-16 rounded-2xl bg-pink-500/20 border border-pink-400/40 flex items-center justify-center mx-auto text-pink-400 text-3xl group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-venus"></i>
                            </div>
                            <div>
                                <div class="font-heading text-lg font-extrabold text-white">Women (Silk Top Topper)</div>
                                <div class="text-xs text-pink-300 font-semibold mt-0.5">Parting Widening &amp; Crown Volume Solution</div>
                            </div>
                            <ul class="text-left text-xs text-slate-300 space-y-2 pt-2 border-t border-slate-700/50">
                                <li class="flex items-center gap-2"><i class="fa-solid fa-check text-pink-400 text-xs"></i> <span>Targeted parting &amp; crown volume topper piece</span></li>
                                <li class="flex items-center gap-2"><i class="fa-solid fa-check text-pink-400 text-xs"></i> <span>Ultra-realistic triple injected silk top (knotless scalp look)</span></li>
                                <li class="flex items-center gap-2"><i class="fa-solid fa-check text-pink-400 text-xs"></i> <span>10-second zero-damage soft silicone clip-on attachment</span></li>
                            </ul>
                            <button type="button" class="w-full py-2.5 rounded-xl bg-pink-600 hover:bg-pink-500 text-white font-bold text-xs uppercase tracking-wider transition-colors shadow">
                                Start Women's Assessment &rarr;
                            </button>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: HAIR LOSS DEGREE & PHASE (SVG DIAGRAMS) -->
                <div x-show="quizStep === 2" x-cloak class="space-y-6">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 max-w-3xl mx-auto pb-3 border-b border-slate-700/60">
                        <div>
                            <span class="text-teal-400 text-xs font-bold uppercase tracking-wider">Step 2 / 4</span>
                            <h4 class="font-heading text-xl sm:text-2xl font-bold text-white">Select Your Current Hair Loss Stage (Phase)</h4>
                            <p class="text-xs text-slate-400 mt-1">Select the scalp diagram below that most closely reflects your current pattern.</p>
                        </div>
                        <!-- Quick Gender Switcher -->
                        <div class="inline-flex rounded-lg bg-slate-900 p-1 border border-slate-700 text-xs flex-shrink-0">
                            <button type="button" @click="setQuizGender('men')" :class="quizGender === 'men' ? 'bg-teal-600 text-white font-bold' : 'text-slate-400'" class="px-3 py-1 rounded-md transition-colors">Men (Norwood)</button>
                            <button type="button" @click="setQuizGender('women')" :class="quizGender === 'women' ? 'bg-pink-600 text-white font-bold' : 'text-slate-400'" class="px-3 py-1 rounded-md transition-colors">Women (Ludwig)</button>
                        </div>
                    </div>

                    <!-- Men's Norwood Phases Grid -->
                    <template x-if="quizGender === 'men'">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 max-w-5xl mx-auto">
                            <!-- Phase 1: M-Shape Early -->
                            <div @click="quizPhase = 'phase1'"
                                :class="quizPhase === 'phase1' ? 'border-teal-400 bg-teal-950/40 ring-2 ring-teal-400/50 shadow-lg' : 'border-slate-700 bg-slate-900/60 hover:border-slate-500'"
                                class="p-4 rounded-2xl border text-center space-y-3 cursor-pointer transition-all flex flex-col justify-between">
                                <div>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-teal-500/20 text-teal-300 font-mono font-bold">Phase 1 (Norwood II~III)</span>
                                    <div class="font-bold text-sm text-white mt-1.5">Receding Temples (M-Shape)</div>
                                    <div class="text-[11px] text-slate-400">Deep temple recession with crown and mid-scalp intact</div>
                                </div>
                                <!-- Scalp SVG -->
                                <div class="py-2 flex justify-center">
                                    <svg viewBox="0 0 100 110" class="w-24 h-24">
                                        <ellipse cx="50" cy="58" rx="34" ry="42" fill="#141E38" stroke="#334155" stroke-width="2"/>
                                        <path d="M 47 16 L 50 11 L 53 16" stroke="#64748b" stroke-width="2" fill="none" stroke-linecap="round"/>
                                        <path d="M 16 53 Q 12 58 16 64" stroke="#475569" stroke-width="2" fill="none"/>
                                        <path d="M 84 53 Q 88 58 84 64" stroke="#475569" stroke-width="2" fill="none"/>
                                        <!-- Normal hair background -->
                                        <path d="M 22 45 Q 50 30 78 45 Q 84 75 75 92 Q 50 100 25 92 Z" fill="#24314E"/>
                                        <!-- Receding M-Shape highlighted in glowing teal -->
                                        <path d="M 23 42 Q 33 55 42 42 Q 32 30 23 42 Z" fill="#0D9488" stroke="#14B8A6" stroke-width="1.5" class="animate-pulse"/>
                                        <path d="M 77 42 Q 67 55 58 42 Q 68 30 77 42 Z" fill="#0D9488" stroke="#14B8A6" stroke-width="1.5" class="animate-pulse"/>
                                    </svg>
                                </div>
                                <div class="text-[11px] text-teal-300 bg-slate-900/80 py-1.5 px-2 rounded-lg border border-slate-700/60 font-medium">
                                    Rec. Base: 5x7" ~ 6x8" Frontal
                                </div>
                            </div>

                            <!-- Phase 2: M + Crown Combined -->
                            <div @click="quizPhase = 'phase2'"
                                :class="quizPhase === 'phase2' ? 'border-teal-400 bg-teal-950/40 ring-2 ring-teal-400/50 shadow-lg' : 'border-slate-700 bg-slate-900/60 hover:border-slate-500'"
                                class="p-4 rounded-2xl border text-center space-y-3 cursor-pointer transition-all flex flex-col justify-between">
                                <div>
                                    <div class="inline-flex items-center gap-1 text-[10px] px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-300 font-mono font-bold">
                                        <span>Phase 2 (IV~V)</span>
                                        <span class="text-[9px] bg-amber-400 text-slate-950 px-1 rounded">Most Popular</span>
                                    </div>
                                    <div class="font-bold text-sm text-white mt-1.5">M-Shape &amp; Crown Vertex</div>
                                    <div class="text-[11px] text-slate-400">Simultaneous frontal hairline recession and crown thinning</div>
                                </div>
                                <!-- Scalp SVG -->
                                <div class="py-2 flex justify-center">
                                    <svg viewBox="0 0 100 110" class="w-24 h-24">
                                        <ellipse cx="50" cy="58" rx="34" ry="42" fill="#141E38" stroke="#334155" stroke-width="2"/>
                                        <path d="M 47 16 L 50 11 L 53 16" stroke="#64748b" stroke-width="2" fill="none" stroke-linecap="round"/>
                                        <path d="M 16 53 Q 12 58 16 64" stroke="#475569" stroke-width="2" fill="none"/>
                                        <path d="M 84 53 Q 88 58 84 64" stroke="#475569" stroke-width="2" fill="none"/>
                                        <!-- Normal hair background -->
                                        <path d="M 22 45 Q 50 30 78 45 Q 84 75 75 92 Q 50 100 25 92 Z" fill="#24314E"/>
                                        <!-- Receding M-Shape -->
                                        <path d="M 22 42 Q 33 58 45 44 Q 32 30 22 42 Z" fill="#0D9488" stroke="#14B8A6" stroke-width="1.5"/>
                                        <path d="M 78 42 Q 67 58 55 44 Q 68 30 78 42 Z" fill="#0D9488" stroke="#14B8A6" stroke-width="1.5"/>
                                        <!-- Crown Bald Circle -->
                                        <circle cx="50" cy="70" r="11" fill="#0D9488" stroke="#14B8A6" stroke-width="1.5" class="animate-pulse"/>
                                    </svg>
                                </div>
                                <div class="text-[11px] text-teal-300 bg-slate-900/80 py-1.5 px-2 rounded-lg border border-slate-700/60 font-medium">
                                    Rec. Base: 7x9" (Golden Standard)
                                </div>
                            </div>

                            <!-- Phase 3: Advanced Top U-Shape -->
                            <div @click="quizPhase = 'phase3'"
                                :class="quizPhase === 'phase3' ? 'border-teal-400 bg-teal-950/40 ring-2 ring-teal-400/50 shadow-lg' : 'border-slate-700 bg-slate-900/60 hover:border-slate-500'"
                                class="p-4 rounded-2xl border text-center space-y-3 cursor-pointer transition-all flex flex-col justify-between">
                                <div>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-teal-500/20 text-teal-300 font-mono font-bold">Phase 3 (Norwood VI~VII)</span>
                                    <div class="font-bold text-sm text-white mt-1.5">Advanced Top Thinning (U-Shape)</div>
                                    <div class="text-[11px] text-slate-400">Frontal hairline and crown have merged, exposing the entire top scalp</div>
                                </div>
                                <!-- Scalp SVG -->
                                <div class="py-2 flex justify-center">
                                    <svg viewBox="0 0 100 110" class="w-24 h-24">
                                        <ellipse cx="50" cy="58" rx="34" ry="42" fill="#141E38" stroke="#334155" stroke-width="2"/>
                                        <path d="M 47 16 L 50 11 L 53 16" stroke="#64748b" stroke-width="2" fill="none" stroke-linecap="round"/>
                                        <path d="M 16 53 Q 12 58 16 64" stroke="#475569" stroke-width="2" fill="none"/>
                                        <path d="M 84 53 Q 88 58 84 64" stroke="#475569" stroke-width="2" fill="none"/>
                                        <!-- Normal hair background (only sides and nape) -->
                                        <path d="M 18 55 Q 50 30 82 55 Q 84 80 75 94 Q 50 100 25 94 Z" fill="#24314E"/>
                                        <!-- Large Connected Bald Area -->
                                        <ellipse cx="50" cy="54" rx="23" ry="27" fill="#0D9488" stroke="#14B8A6" stroke-width="1.5" class="animate-pulse"/>
                                    </svg>
                                </div>
                                <div class="text-[11px] text-teal-300 bg-slate-900/80 py-1.5 px-2 rounded-lg border border-slate-700/60 font-medium">
                                    Rec. Base: 8x10" Wide Toupee
                                </div>
                            </div>

                            <!-- Phase 4: Full Head / Alopecia -->
                            <div @click="quizPhase = 'phase4'"
                                :class="quizPhase === 'phase4' ? 'border-teal-400 bg-teal-950/40 ring-2 ring-teal-400/50 shadow-lg' : 'border-slate-700 bg-slate-900/60 hover:border-slate-500'"
                                class="p-4 rounded-2xl border text-center space-y-3 cursor-pointer transition-all flex flex-col justify-between">
                                <div>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-purple-500/20 text-purple-300 font-mono font-bold">Phase 4 (Severe)</span>
                                    <div class="font-bold text-sm text-white mt-1.5">Severe Alopecia / Full Coverage</div>
                                    <div class="text-[11px] text-slate-400">Minimal donor hair on sides/nape requiring complete cranial cap</div>
                                </div>
                                <!-- Scalp SVG -->
                                <div class="py-2 flex justify-center">
                                    <svg viewBox="0 0 100 110" class="w-24 h-24">
                                        <ellipse cx="50" cy="58" rx="34" ry="42" fill="#141E38" stroke="#334155" stroke-width="2"/>
                                        <path d="M 47 16 L 50 11 L 53 16" stroke="#64748b" stroke-width="2" fill="none" stroke-linecap="round"/>
                                        <path d="M 16 53 Q 12 58 16 64" stroke="#475569" stroke-width="2" fill="none"/>
                                        <path d="M 84 53 Q 88 58 84 64" stroke="#475569" stroke-width="2" fill="none"/>
                                        <!-- Minimal thin rim -->
                                        <path d="M 18 70 Q 50 50 82 70 Q 82 85 75 95 Q 50 101 25 95 Z" fill="#24314E"/>
                                        <!-- Full Head Loss -->
                                        <ellipse cx="50" cy="56" rx="30" ry="36" fill="#0D9488" stroke="#14B8A6" stroke-width="1.5" class="animate-pulse"/>
                                    </svg>
                                </div>
                                <div class="text-[11px] text-teal-300 bg-slate-900/80 py-1.5 px-2 rounded-lg border border-slate-700/60 font-medium">
                                    Rec. System: Full Cranial Cap Wig
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Women's Ludwig Phases Grid -->
                    <template x-if="quizGender === 'women'">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 max-w-5xl mx-auto">
                            <!-- W Phase 1 -->
                            <div @click="quizPhase = 'w_phase1'"
                                :class="quizPhase === 'w_phase1' ? 'border-pink-400 bg-pink-950/40 ring-2 ring-pink-400/50 shadow-lg' : 'border-slate-700 bg-slate-900/60 hover:border-slate-500'"
                                class="p-4 rounded-2xl border text-center space-y-3 cursor-pointer transition-all flex flex-col justify-between">
                                <div>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-pink-500/20 text-pink-300 font-mono font-bold">Phase 1 (Ludwig I)</span>
                                    <div class="font-bold text-sm text-white mt-1.5">Early Parting Widening</div>
                                    <div class="text-[11px] text-slate-400">Hair thinning along the central parting with subtle scalp visibility</div>
                                </div>
                                <div class="py-2 flex justify-center">
                                    <svg viewBox="0 0 100 110" class="w-24 h-24">
                                        <ellipse cx="50" cy="58" rx="34" ry="42" fill="#141E38" stroke="#334155" stroke-width="2"/>
                                        <path d="M 47 16 L 50 11 L 53 16" stroke="#64748b" stroke-width="2" fill="none" stroke-linecap="round"/>
                                        <!-- Women hair base -->
                                        <path d="M 18 40 Q 50 25 82 40 Q 86 85 76 96 Q 50 102 24 96 Z" fill="#2E243A"/>
                                        <!-- Parting thin line in pink/teal -->
                                        <path d="M 50 26 L 50 78" stroke="#F43F5E" stroke-width="3" stroke-linecap="round" class="animate-pulse"/>
                                    </svg>
                                </div>
                                <div class="text-[11px] text-pink-300 bg-slate-900/80 py-1.5 px-2 rounded-lg border border-slate-700/60 font-medium">
                                    Rec. Topper: 4x5" Mini Parting Piece
                                </div>
                            </div>

                            <!-- W Phase 2 -->
                            <div @click="quizPhase = 'w_phase2'"
                                :class="quizPhase === 'w_phase2' ? 'border-pink-400 bg-pink-950/40 ring-2 ring-pink-400/50 shadow-lg' : 'border-slate-700 bg-slate-900/60 hover:border-slate-500'"
                                class="p-4 rounded-2xl border text-center space-y-3 cursor-pointer transition-all flex flex-col justify-between">
                                <div>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-300 font-mono font-bold">Phase 2 (Ludwig II) Most Popular</span>
                                    <div class="font-bold text-sm text-white mt-1.5">Diffuse Crown &amp; Parting Exposure</div>
                                    <div class="text-[11px] text-slate-400">Noticeable scalp exposure at crown and widened parting line under lighting</div>
                                </div>
                                <div class="py-2 flex justify-center">
                                    <svg viewBox="0 0 100 110" class="w-24 h-24">
                                        <ellipse cx="50" cy="58" rx="34" ry="42" fill="#141E38" stroke="#334155" stroke-width="2"/>
                                        <path d="M 47 16 L 50 11 L 53 16" stroke="#64748b" stroke-width="2" fill="none" stroke-linecap="round"/>
                                        <path d="M 18 40 Q 50 25 82 40 Q 86 85 76 96 Q 50 102 24 96 Z" fill="#2E243A"/>
                                        <!-- Center Parting + Crown Oval -->
                                        <path d="M 50 26 L 50 78" stroke="#F43F5E" stroke-width="4" stroke-linecap="round"/>
                                        <ellipse cx="50" cy="55" rx="14" ry="17" fill="#F43F5E" fill-opacity="0.35" stroke="#F43F5E" stroke-width="1.5" class="animate-pulse"/>
                                    </svg>
                                </div>
                                <div class="text-[11px] text-pink-300 bg-slate-900/80 py-1.5 px-2 rounded-lg border border-slate-700/60 font-medium">
                                    Rec. Topper: 6x6" Silk Top Volume Piece
                                </div>
                            </div>

                            <!-- W Phase 3 -->
                            <div @click="quizPhase = 'w_phase3'"
                                :class="quizPhase === 'w_phase3' ? 'border-pink-400 bg-pink-950/40 ring-2 ring-pink-400/50 shadow-lg' : 'border-slate-700 bg-slate-900/60 hover:border-slate-500'"
                                class="p-4 rounded-2xl border text-center space-y-3 cursor-pointer transition-all flex flex-col justify-between">
                                <div>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-pink-500/20 text-pink-300 font-mono font-bold">Phase 3 (Ludwig III)</span>
                                    <div class="font-bold text-sm text-white mt-1.5">Advanced Top Diffuse Thinning</div>
                                    <div class="text-[11px] text-slate-400">Significant density reduction across the entire top requiring wide coverage</div>
                                </div>
                                <div class="py-2 flex justify-center">
                                    <svg viewBox="0 0 100 110" class="w-24 h-24">
                                        <ellipse cx="50" cy="58" rx="34" ry="42" fill="#141E38" stroke="#334155" stroke-width="2"/>
                                        <path d="M 47 16 L 50 11 L 53 16" stroke="#64748b" stroke-width="2" fill="none" stroke-linecap="round"/>
                                        <path d="M 18 40 Q 50 25 82 40 Q 86 85 76 96 Q 50 102 24 96 Z" fill="#2E243A"/>
                                        <ellipse cx="50" cy="55" rx="22" ry="25" fill="#F43F5E" fill-opacity="0.4" stroke="#F43F5E" stroke-width="1.5" class="animate-pulse"/>
                                    </svg>
                                </div>
                                <div class="text-[11px] text-pink-300 bg-slate-900/80 py-1.5 px-2 rounded-lg border border-slate-700/60 font-medium">
                                    Rec. Topper: 7x8" ~ 8x8" Wide Top Topper
                                </div>
                            </div>

                            <!-- W Phase 4 -->
                            <div @click="quizPhase = 'w_phase4'"
                                :class="quizPhase === 'w_phase4' ? 'border-pink-400 bg-pink-950/40 ring-2 ring-pink-400/50 shadow-lg' : 'border-slate-700 bg-slate-900/60 hover:border-slate-500'"
                                class="p-4 rounded-2xl border text-center space-y-3 cursor-pointer transition-all flex flex-col justify-between">
                                <div>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-purple-500/20 text-purple-300 font-mono font-bold">Phase 4 (Full Cover)</span>
                                    <div class="font-bold text-sm text-white mt-1.5">Full Cranial Coverage Wig</div>
                                    <div class="text-[11px] text-slate-400">Medical alopecia, universal hair loss, or full cranial coverage requirement</div>
                                </div>
                                <div class="py-2 flex justify-center">
                                    <svg viewBox="0 0 100 110" class="w-24 h-24">
                                        <ellipse cx="50" cy="58" rx="34" ry="42" fill="#141E38" stroke="#334155" stroke-width="2"/>
                                        <path d="M 47 16 L 50 11 L 53 16" stroke="#64748b" stroke-width="2" fill="none" stroke-linecap="round"/>
                                        <ellipse cx="50" cy="56" rx="29" ry="36" fill="#F43F5E" fill-opacity="0.45" stroke="#F43F5E" stroke-width="1.5" class="animate-pulse"/>
                                    </svg>
                                </div>
                                <div class="text-[11px] text-pink-300 bg-slate-900/80 py-1.5 px-2 rounded-lg border border-slate-700/60 font-medium">
                                    Rec. System: Full Cap Medical Wig
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Wizard Navigation Buttons -->
                    <div class="flex items-center justify-between max-w-xl mx-auto pt-4">
                        <button type="button" @click="quizStep = 1" class="px-5 py-2.5 rounded-xl border border-slate-700 hover:bg-slate-800 text-slate-300 font-semibold text-xs flex items-center gap-1.5 transition-colors cursor-pointer">
                            <i class="fa-solid fa-chevron-left text-[10px]"></i> Previous
                        </button>
                        <button type="button" @click="quizStep = 3" class="px-6 py-2.5 rounded-xl teal-gradient text-white font-bold text-xs flex items-center gap-1.5 shadow-lg transition-transform active:scale-95 cursor-pointer">
                            Next: Style &amp; Hairline Choice <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </button>
                    </div>
                </div>

                <!-- STEP 3: DESIRED HAIRSTYLE & FOREHEAD HAIRLINE EXPOSURE (THE CRITICAL FACTOR) -->
                <div x-show="quizStep === 3" x-cloak class="space-y-6">
                    <div class="text-center max-w-2xl mx-auto space-y-1">
                        <span class="text-teal-400 text-xs font-bold uppercase tracking-wider">Step 3 / 4 (Critical Tech Factor)</span>
                        <h4 class="font-heading text-xl sm:text-2xl font-bold text-white">Select Your Desired Hairstyle &amp; Hairline Exposure</h4>
                        <p class="text-xs sm:text-sm text-slate-300">
                            ⭐ <strong class="text-amber-300">The Decisive Factor:</strong> Whether you <span class="underline decoration-teal-400 underline-offset-4">"fully expose your forehead hairline"</span> or <span class="underline decoration-teal-400 underline-offset-4">"cover it with bangs"</span> determines the exact engineering base required!
                        </p>
                    </div>

                    <!-- Key Education Callout Banner -->
                    <div class="max-w-3xl mx-auto p-4 rounded-2xl bg-amber-500/10 border border-amber-400/40 text-amber-200 text-xs leading-relaxed flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-500/20 border border-amber-400/50 flex items-center justify-center text-amber-400 flex-shrink-0 mt-0.5">
                            <i class="fa-solid fa-lightbulb"></i>
                        </div>
                        <div>
                            <div class="font-bold text-amber-300 mb-0.5">Why is Forehead Hairline Exposure the Critical Tech Factor?</div>
                            <div>Exposing the forehead with a slick-back or pompadour requires an invisible transition where knots and base borders cannot be detected even from 1cm away—necessitating <strong class="text-white">0.03mm Ultra-thin Skin</strong> or <strong class="text-white">HD Swiss Lace</strong>. Conversely, styles with covered fringes eliminate hairline exposure, allowing maximum use of <strong class="text-white">breathable, high-durability Fine Swiss Net / Mono bases (up to 12 months lifespan)</strong> for optimal comfort and longevity in the Australian climate.</div>
                        </div>
                    </div>

                    <!-- Men's Hairline Exposure Styles -->
                    <template x-if="quizGender === 'men'">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 max-w-4xl mx-auto">
                            <!-- Style A: 100% Fully Exposed Forehead -->
                            <div @click="quizStyle = 'exposed'"
                                :class="quizStyle === 'exposed' ? 'border-amber-400 bg-gradient-to-b from-amber-950/40 to-[#121E3D] ring-2 ring-amber-400/50 shadow-xl' : 'border-slate-700 bg-slate-900/60 hover:border-slate-500'"
                                class="p-5 rounded-2xl border space-y-3 cursor-pointer transition-all flex flex-col justify-between">
                                <div class="space-y-2">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-500/20 border border-amber-400/40 text-amber-300 text-[10px] font-bold">
                                        <i class="fa-solid fa-fire text-amber-400"></i>
                                        <span>100% Fully Exposed Hairline</span>
                                    </div>
                                    <div class="font-heading text-base font-bold text-white">Slick-Back / Pompadour / Quiff</div>
                                    <div class="text-xs text-slate-300 leading-relaxed">
                                        Hair combed entirely off the face to showcase a confident, clean, high-definition male hairline.
                                    </div>
                                </div>
                                <div class="p-3 rounded-xl bg-slate-950/70 border border-slate-700/60 space-y-1.5 text-[11px]">
                                    <div class="font-bold text-teal-300 flex items-center gap-1">
                                        <i class="fa-solid fa-microchip"></i> Essential Base Engineering:
                                    </div>
                                    <div class="text-slate-200 font-semibold">• 0.03mm Ultra-thin Skin</div>
                                    <div class="text-slate-400 text-[10px]">Knotless Direct V-Loop injection appearing seamlessly like natural follicular roots even at 1cm close-up</div>
                                </div>
                                <div class="text-[10px] text-amber-300 font-semibold text-center">
                                    #Pompadour #SlickBack #SidePart #UndetectableHairline
                                </div>
                            </div>

                            <!-- Style B: Semi-Exposed 30~50% -->
                            <div @click="quizStyle = 'semi'"
                                :class="quizStyle === 'semi' ? 'border-sky-400 bg-gradient-to-b from-sky-950/40 to-[#121E3D] ring-2 ring-sky-400/50 shadow-xl' : 'border-slate-700 bg-slate-900/60 hover:border-slate-500'"
                                class="p-5 rounded-2xl border space-y-3 cursor-pointer transition-all flex flex-col justify-between">
                                <div class="space-y-2">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-sky-500/20 border border-sky-400/40 text-sky-300 text-[10px] font-bold">
                                        <i class="fa-solid fa-star text-sky-400"></i>
                                        <span>30~50% Semi-Exposed (Curtain Part)</span>
                                    </div>
                                    <div class="font-heading text-base font-bold text-white">Korean As-Perm / Curtain Fringe / Side-Part</div>
                                    <div class="text-xs text-slate-300 leading-relaxed">
                                        Subtle forehead reveal through soft parted bangs—the most trending, versatile modern Asian style.
                                    </div>
                                </div>
                                <div class="p-3 rounded-xl bg-slate-950/70 border border-slate-700/60 space-y-1.5 text-[11px]">
                                    <div class="font-bold text-teal-300 flex items-center gap-1">
                                        <i class="fa-solid fa-microchip"></i> Essential Base Engineering:
                                    </div>
                                    <div class="text-slate-200 font-semibold">• Lace Front + Swiss PU Hybrid</div>
                                    <div class="text-slate-400 text-[10px]">Invisible Swiss Lace front edge combined with durable PU perimeter for rock-solid tape bonding</div>
                                </div>
                                <div class="text-[10px] text-sky-300 font-semibold text-center">
                                    #AsPerm #CurtainBangs #NaturalPart #MostPopular
                                </div>
                            </div>

                            <!-- Style C: 100% Covered Fringe -->
                            <div @click="quizStyle = 'covered'"
                                :class="quizStyle === 'covered' ? 'border-emerald-400 bg-gradient-to-b from-emerald-950/40 to-[#121E3D] ring-2 ring-emerald-400/50 shadow-xl' : 'border-slate-700 bg-slate-900/60 hover:border-slate-500'"
                                class="p-5 rounded-2xl border space-y-3 cursor-pointer transition-all flex flex-col justify-between">
                                <div class="space-y-2">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/40 text-emerald-300 text-[10px] font-bold">
                                        <i class="fa-solid fa-shield-halved text-emerald-400"></i>
                                        <span>100% Covered Fringe (Forward Bangs)</span>
                                    </div>
                                    <div class="font-heading text-base font-bold text-white">Dandy Cut / Forward Fringe / Textured Bangs</div>
                                    <div class="text-xs text-slate-300 leading-relaxed">
                                        Full forward fringe completely covers the forehead, providing a youthful look with zero hairline exposure anxiety.
                                    </div>
                                </div>
                                <div class="p-3 rounded-xl bg-slate-950/70 border border-slate-700/60 space-y-1.5 text-[11px]">
                                    <div class="font-bold text-teal-300 flex items-center gap-1">
                                        <i class="fa-solid fa-microchip"></i> Essential Base Engineering:
                                    </div>
                                    <div class="text-slate-200 font-semibold">• Swiss Net + PU (Maximum Longevity)</div>
                                    <div class="text-slate-400 text-[10px]">Optimal thermal breathability and up to 1-year lifespan with zero delicate edge maintenance</div>
                                </div>
                                <div class="text-[10px] text-emerald-300 font-semibold text-center">
                                    #DandyCut #TexturedFringe #LongestLifespan #MaximumBreathability
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Women's Styles -->
                    <template x-if="quizGender === 'women'">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 max-w-4xl mx-auto">
                            <!-- W Style 1 -->
                            <div @click="quizStyle = 'w_part'"
                                :class="quizStyle === 'w_part' ? 'border-pink-400 bg-gradient-to-b from-pink-950/40 to-[#121E3D] ring-2 ring-pink-400/50 shadow-xl' : 'border-slate-700 bg-slate-900/60 hover:border-slate-500'"
                                class="p-5 rounded-2xl border space-y-3 cursor-pointer transition-all flex flex-col justify-between">
                                <div class="space-y-2">
                                    <span class="text-[10px] px-2.5 py-1 rounded-full bg-pink-500/20 text-pink-300 font-bold">Exposed Parting Line</span>
                                    <div class="font-heading text-base font-bold text-white">Natural Parting &amp; Crown Volume</div>
                                    <div class="text-xs text-slate-300 leading-relaxed">
                                        Effortless parting with multi-directional styling where hyper-realistic scalp skin is paramount.
                                    </div>
                                </div>
                                <div class="p-3 rounded-xl bg-slate-950/70 border border-slate-700/60 space-y-1 text-[11px]">
                                    <div class="font-bold text-pink-300">• Triple Injected Silk Top Base</div>
                                    <div class="text-slate-400 text-[10px]">Hidden knot injection delivering a flawless human scalp appearance from every angle</div>
                                </div>
                            </div>

                            <!-- W Style 2 -->
                            <div @click="quizStyle = 'w_fringe'"
                                :class="quizStyle === 'w_fringe' ? 'border-pink-400 bg-gradient-to-b from-pink-950/40 to-[#121E3D] ring-2 ring-pink-400/50 shadow-xl' : 'border-slate-700 bg-slate-900/60 hover:border-slate-500'"
                                class="p-5 rounded-2xl border space-y-3 cursor-pointer transition-all flex flex-col justify-between">
                                <div class="space-y-2">
                                    <span class="text-[10px] px-2.5 py-1 rounded-full bg-pink-500/20 text-pink-300 font-bold">Covered Forehead (See-Through Bangs)</span>
                                    <div class="font-heading text-base font-bold text-white">See-Through Fringe &amp; Crown Volume</div>
                                    <div class="text-xs text-slate-300 leading-relaxed">
                                        Light airy fringe softens facial contours while delivering instant crown lift for a youthful aesthetic.
                                    </div>
                                </div>
                                <div class="p-3 rounded-xl bg-slate-950/70 border border-slate-700/60 space-y-1 text-[11px]">
                                    <div class="font-bold text-pink-300">• French Lace Front + Silk Parting</div>
                                    <div class="text-slate-400 text-[10px]">Featherlight lace front for delicate fringe fall with a silk parting core for crown elevation</div>
                                </div>
                            </div>

                            <!-- W Style 3 -->
                            <div @click="quizStyle = 'w_wave'"
                                :class="quizStyle === 'w_wave' ? 'border-pink-400 bg-gradient-to-b from-pink-950/40 to-[#121E3D] ring-2 ring-pink-400/50 shadow-xl' : 'border-slate-700 bg-slate-900/60 hover:border-slate-500'"
                                class="p-5 rounded-2xl border space-y-3 cursor-pointer transition-all flex flex-col justify-between">
                                <div class="space-y-2">
                                    <span class="text-[10px] px-2.5 py-1 rounded-full bg-pink-500/20 text-pink-300 font-bold">Full Volume &amp; Long Lengths</span>
                                    <div class="font-heading text-base font-bold text-white">Long Layered Wave &amp; Updo Versatility</div>
                                    <div class="text-xs text-slate-300 leading-relaxed">
                                        14" to 18" lengths accommodating high ponytails, half-updos, and heat-styled glamour waves.
                                    </div>
                                </div>
                                <div class="p-3 rounded-xl bg-slate-950/70 border border-slate-700/60 space-y-1 text-[11px]">
                                    <div class="font-bold text-pink-300">• Double Drawn Virgin Human Hair</div>
                                    <div class="text-slate-400 text-[10px]">100% premium virgin cuticle hair maintaining consistent thickness root-to-tip</div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Wizard Navigation Buttons -->
                    <div class="flex items-center justify-between max-w-xl mx-auto pt-4">
                        <button type="button" @click="quizStep = 2" class="px-5 py-2.5 rounded-xl border border-slate-700 hover:bg-slate-800 text-slate-300 font-semibold text-xs flex items-center gap-1.5 transition-colors cursor-pointer">
                            <i class="fa-solid fa-chevron-left text-[10px]"></i> Previous
                        </button>
                        <button type="button" @click="quizStep = 4" class="px-7 py-3 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs uppercase tracking-wider flex items-center gap-2 shadow-xl transition-transform active:scale-95 cursor-pointer">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            <span>View Tailored Hair System Specs &rarr;</span>
                        </button>
                    </div>
                </div>

                <!-- STEP 4: DIAGNOSTIC PRESCRIPTION & RECOMMENDED SPECIFICATIONS -->
                <div x-show="quizStep === 4" x-cloak class="space-y-6">
                    <div class="text-center max-w-2xl mx-auto space-y-1">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold border border-emerald-400/40">
                            <i class="fa-solid fa-circle-check text-emerald-400"></i>
                            <span>Self-Assessment Complete (Match Score: 98%)</span>
                        </div>
                        <h4 class="font-heading text-2xl sm:text-3xl font-extrabold text-white">Your Tailored Bespoke Hair System Prescription</h4>
                        <p class="text-xs sm:text-sm text-slate-300">Custom master-craft specifications generated from your hair loss phase and hairline exposure choice.</p>
                    </div>

                    <!-- Recommended Spec Card -->
                    <div class="max-w-3xl mx-auto rounded-3xl bg-slate-900/95 border-2 border-teal-500/50 p-6 sm:p-8 shadow-2xl relative overflow-hidden space-y-6">
                        <div class="flex flex-wrap items-center justify-between gap-3 pb-5 border-b border-slate-800">
                            <div>
                                <span class="text-[10px] text-teal-400 uppercase font-mono font-bold tracking-widest block">Recommended Bespoke Specification</span>
                                <h5 class="text-lg sm:text-xl font-bold text-white flex items-center gap-2">
                                    <span x-text="quizGender === 'men' ? 'David Hair Bespoke Precision Toupee (Men)' : 'David Hair Silk Top Custom Topper (Women)'"></span>
                                </h5>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-slate-400 block">Direct Factory Price (AUD)</span>
                                <span class="text-xl sm:text-2xl font-black text-amber-300 font-heading" x-text="quizGender === 'men' ? (quizPhase === 'phase4' ? '$900 AUD' : '$650 AUD~') : '$600 AUD~'"></span>
                            </div>
                        </div>

                        <!-- 4 Pillars of Spec -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- 1. Base Material -->
                            <div class="p-4 rounded-xl bg-slate-950/80 border border-slate-800 space-y-1.5">
                                <span class="text-[10px] text-slate-400 font-semibold block uppercase">1. Recommended Base Construction</span>
                                <div class="font-bold text-sm text-white flex items-center gap-1.5">
                                    <i class="fa-solid fa-layer-group text-teal-400"></i>
                                    <span x-text="quizGender === 'men' ? (quizStyle === 'exposed' ? '0.03mm Ultra-Thin Skin (Knotless V-Loop)' : (quizStyle === 'semi' ? 'Lace Front + Swiss Net + PU Hybrid' : 'Fine Swiss Net + PU (Maximum Longevity)')) : 'Triple-Layer Injected Silk Top'"></span>
                                </div>
                                <p class="text-[11px] text-slate-400 leading-tight" x-text="quizGender === 'men' ? (quizStyle === 'exposed' ? 'For fully exposed hairlines. Direct V-loop knotless membrane that looks like natural scalp skin even at 1cm close-up.' : (quizStyle === 'semi' ? 'Best-seller combining an undetectable Swiss lace front hairline with a durable PU tape perimeter.' : 'For forward bangs. Zero hairline exposure concern with superior heat dissipation and up to 1-year longevity.')) : 'Triple-injected silk base creating an undetectable parting with individual hairs emerging straight from the scalp.'"></p>
                            </div>

                            <!-- 2. Recommended Size -->
                            <div class="p-4 rounded-xl bg-slate-950/80 border border-slate-800 space-y-1.5">
                                <span class="text-[10px] text-slate-400 font-semibold block uppercase">2. Recommended Base Dimensions</span>
                                <div class="font-bold text-sm text-white flex items-center gap-1.5">
                                    <i class="fa-solid fa-ruler-combined text-amber-400"></i>
                                    <span x-text="quizGender === 'men' ? (quizPhase === 'phase1' ? '6 x 8 inches (Frontal Focus)' : (quizPhase === 'phase2' ? '7 x 9 inches (Standard Golden)' : (quizPhase === 'phase3' ? '8 x 10 inches (Large Top Coverage)' : 'Full Cranial Cap (Complete)'))) : (quizPhase === 'w_phase1' ? '4 x 5 inches (Mini Parting)' : (quizPhase === 'w_phase2' ? '6 x 6 inches (Crown Volume)' : '7 x 8 inches (Wide Top)'))"></span>
                                </div>
                                <p class="text-[11px] text-slate-400 leading-tight">Tailored to fully enclose your hair loss area while seamlessly feathering into your remaining natural biological hair.</p>
                            </div>

                            <!-- 3. Attachment Method -->
                            <div class="p-4 rounded-xl bg-slate-950/80 border border-slate-800 space-y-1.5">
                                <span class="text-[10px] text-slate-400 font-semibold block uppercase">3. Recommended Attachment System</span>
                                <div class="font-bold text-sm text-white flex items-center gap-1.5">
                                    <i class="fa-solid fa-link text-sky-400"></i>
                                    <span x-text="quizGender === 'men' ? (quizStyle === 'exposed' ? 'Medical 4-Week Waterproof Bond (Continuous Wear)' : 'Dual System: Daily Medical Tape &amp; Clips') : 'Zero-Damage Soft Silicone Easy-Clip System'"></span>
                                </div>
                                <p class="text-[11px] text-slate-400 leading-tight" x-text="quizGender === 'men' ? (quizStyle === 'exposed' ? 'Engineered for surfing, gym, and active Australian summers—bonded continuously for up to 4 weeks.' : 'Versatile daily wear allowing effortless removal before bed or flexible re-application.') : 'Fast 10-second self-application with zero pulling or strain on existing biological hair.'"></p>
                            </div>

                            <!-- 4. Hair Specs & Color -->
                            <div class="p-4 rounded-xl bg-slate-950/80 border border-slate-800 space-y-1.5">
                                <span class="text-[10px] text-slate-400 font-semibold block uppercase">4. Hair Quality &amp; Density</span>
                                <div class="font-bold text-sm text-white flex items-center gap-1.5">
                                    <i class="fa-solid fa-crown text-teal-400"></i>
                                    <span>100% Remy Virgin Human Hair (80~90% Natural Density)</span>
                                </div>
                                <p class="text-[11px] text-slate-400 leading-tight">Graduated hairline density calibrated to prevent any artificial wig look, mirroring authentic scalp hair behavior.</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-4 flex flex-col sm:flex-row items-center gap-3">
                            <button type="button" @click="applyDiagnosisToBuilder(quizGender, quizPhase, quizStyle)"
                                class="w-full sm:w-2/3 py-4 rounded-2xl teal-gradient hover:opacity-95 text-white font-extrabold text-xs sm:text-sm uppercase tracking-wider flex items-center justify-center gap-2 shadow-xl cursor-pointer transition-transform active:scale-95">
                                <i class="fa-solid fa-sliders"></i>
                                <span>Order This Bespoke Spec in Custom Builder &rarr;</span>
                            </button>
                            <button type="button" @click="isBookingModalOpen = true"
                                class="w-full sm:w-1/3 py-4 rounded-2xl bg-slate-800 hover:bg-slate-700 border border-slate-600 text-slate-200 font-bold text-xs sm:text-sm flex items-center justify-center gap-2 transition-colors cursor-pointer">
                                <i class="fa-solid fa-calendar-check text-amber-400"></i>
                                <span>Book Private Springvale Fitting</span>
                            </button>
                        </div>

                        <div class="text-center pt-2">
                            <button type="button" @click="quizStep = 1" class="text-xs text-slate-400 hover:text-white underline decoration-slate-600 underline-offset-4 cursor-pointer">
                                ↺ Retake Self-Assessment
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ============================================================= -->
            <!-- [OPTION 2] VISUAL DIAGRAM & HAIRLINE MATRIX                  -->
            <!-- ============================================================= -->
            <div x-show="isSectionVisible('diagnosis-option2') && (activeDiagnosisView === 'matrix' || activeDiagnosisView === 'both')"
                class="rounded-3xl bg-gradient-to-b from-[#131E3D] via-[#0E1630] to-[#0B1226] border-2 border-sky-500/30 p-5 sm:p-8 lg:p-10 shadow-2xl relative transition-all"
                :class="{ 'ring-4 ring-sky-500/20': activeDiagnosisView === 'matrix' }">
                
                <!-- Option 2 Badge Tag -->
                <div class="flex flex-wrap items-center justify-between gap-3 mb-6 pb-5 border-b border-slate-700/60">
                    <div class="flex items-center gap-2.5">
                        <span class="px-3 py-1 rounded-lg bg-sky-500 text-white text-xs font-extrabold uppercase tracking-wider shadow">OPTION 2</span>
                        <h3 class="font-heading text-lg sm:text-xl font-bold text-white">Visual Diagram &amp; Hairline Matrix</h3>
                    </div>
                    <!-- Matrix Gender Switcher -->
                    <div class="inline-flex rounded-xl bg-slate-900/90 p-1 border border-slate-700 text-xs shadow-inner">
                        <button type="button" @click="setMatrixGender('men')"
                            :class="matrixGender === 'men' ? 'bg-teal-600 text-white font-bold shadow' : 'text-slate-400 hover:text-white'"
                            class="px-4 py-1.5 rounded-lg transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-mars"></i> Men's Matrix
                        </button>
                        <button type="button" @click="setMatrixGender('women')"
                            :class="matrixGender === 'women' ? 'bg-pink-600 text-white font-bold shadow' : 'text-slate-400 hover:text-white'"
                            class="px-4 py-1.5 rounded-lg transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-venus"></i> Women's Matrix
                        </button>
                    </div>
                </div>

                <div class="space-y-8">
                    <!-- MATRIX ROW 1: HAIR LOSS PHASES -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-sky-500/20 border border-sky-400/40 text-sky-400 text-xs font-mono font-bold flex items-center justify-center">1</span>
                            <h4 class="font-heading text-base sm:text-lg font-bold text-white">
                                <span x-text="matrixGender === 'men' ? 'Men\'s Hair Loss Stages (Norwood Scale)' : 'Women\'s Diffuse Thinning Stages (Ludwig Scale)'"></span>
                                <span class="text-xs text-slate-400 font-normal ml-2">Click to select your scalp pattern</span>
                            </h4>
                        </div>

                        <!-- Men's Phase Cards in Matrix -->
                        <template x-if="matrixGender === 'men'">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                                <div @click="matrixPhase = 'phase1'"
                                    :class="matrixPhase === 'phase1' ? 'border-sky-400 bg-sky-950/40 ring-2 ring-sky-400/40' : 'border-slate-800 bg-slate-950/60 hover:border-slate-600'"
                                    class="p-3.5 rounded-2xl border text-center space-y-2 cursor-pointer transition-all">
                                    <div class="text-[10px] font-mono text-sky-300 font-bold">Phase 1 (Norwood II~III)</div>
                                    <div class="font-bold text-xs sm:text-sm text-white">Receding Temples</div>
                                    <div class="w-16 h-16 mx-auto">
                                        <svg viewBox="0 0 100 110" class="w-full h-full">
                                            <ellipse cx="50" cy="58" rx="34" ry="42" fill="#141E38" stroke="#334155" stroke-width="2"/>
                                            <path d="M 47 16 L 50 11 L 53 16" stroke="#64748b" stroke-width="2" fill="none"/>
                                            <path d="M 22 45 Q 50 30 78 45 Q 84 75 75 92 Q 50 100 25 92 Z" fill="#24314E"/>
                                            <path d="M 23 42 Q 33 55 42 42 Q 32 30 23 42 Z" fill="#0284C7" stroke="#38BDF8" stroke-width="1.5"/>
                                            <path d="M 77 42 Q 67 55 58 42 Q 68 30 77 42 Z" fill="#0284C7" stroke="#38BDF8" stroke-width="1.5"/>
                                        </svg>
                                    </div>
                                    <span class="text-[10px] text-slate-400 block">Rec. 5x7" ~ 6x8"</span>
                                </div>

                                <div @click="matrixPhase = 'phase2'"
                                    :class="matrixPhase === 'phase2' ? 'border-sky-400 bg-sky-950/40 ring-2 ring-sky-400/40' : 'border-slate-800 bg-slate-950/60 hover:border-slate-600'"
                                    class="p-3.5 rounded-2xl border text-center space-y-2 cursor-pointer transition-all">
                                    <div class="text-[10px] font-mono text-sky-300 font-bold">Phase 2 (IV~V) Top Pick</div>
                                    <div class="font-bold text-xs sm:text-sm text-white">M-Shape &amp; Crown</div>
                                    <div class="w-16 h-16 mx-auto">
                                        <svg viewBox="0 0 100 110" class="w-full h-full">
                                            <ellipse cx="50" cy="58" rx="34" ry="42" fill="#141E38" stroke="#334155" stroke-width="2"/>
                                            <path d="M 47 16 L 50 11 L 53 16" stroke="#64748b" stroke-width="2" fill="none"/>
                                            <path d="M 22 45 Q 50 30 78 45 Q 84 75 75 92 Q 50 100 25 92 Z" fill="#24314E"/>
                                            <path d="M 22 42 Q 33 58 45 44 Q 32 30 22 42 Z" fill="#0284C7" stroke="#38BDF8" stroke-width="1.5"/>
                                            <path d="M 78 42 Q 67 58 55 44 Q 68 30 78 42 Z" fill="#0284C7" stroke="#38BDF8" stroke-width="1.5"/>
                                            <circle cx="50" cy="70" r="11" fill="#0284C7" stroke="#38BDF8" stroke-width="1.5"/>
                                        </svg>
                                    </div>
                                    <span class="text-[10px] text-sky-300 font-bold block">Rec. 7x9" (Golden Standard)</span>
                                </div>

                                <div @click="matrixPhase = 'phase3'"
                                    :class="matrixPhase === 'phase3' ? 'border-sky-400 bg-sky-950/40 ring-2 ring-sky-400/40' : 'border-slate-800 bg-slate-950/60 hover:border-slate-600'"
                                    class="p-3.5 rounded-2xl border text-center space-y-2 cursor-pointer transition-all">
                                    <div class="text-[10px] font-mono text-sky-300 font-bold">Phase 3 (VI~VII)</div>
                                    <div class="font-bold text-xs sm:text-sm text-white">Advanced Top Thinning</div>
                                    <div class="w-16 h-16 mx-auto">
                                        <svg viewBox="0 0 100 110" class="w-full h-full">
                                            <ellipse cx="50" cy="58" rx="34" ry="42" fill="#141E38" stroke="#334155" stroke-width="2"/>
                                            <path d="M 47 16 L 50 11 L 53 16" stroke="#64748b" stroke-width="2" fill="none"/>
                                            <path d="M 18 55 Q 50 30 82 55 Q 84 80 75 94 Q 50 100 25 94 Z" fill="#24314E"/>
                                            <ellipse cx="50" cy="54" rx="23" ry="27" fill="#0284C7" stroke="#38BDF8" stroke-width="1.5"/>
                                        </svg>
                                    </div>
                                    <span class="text-[10px] text-slate-400 block">Rec. 8x10" Large Top</span>
                                </div>

                                <div @click="matrixPhase = 'phase4'"
                                    :class="matrixPhase === 'phase4' ? 'border-sky-400 bg-sky-950/40 ring-2 ring-sky-400/40' : 'border-slate-800 bg-slate-950/60 hover:border-slate-600'"
                                    class="p-3.5 rounded-2xl border text-center space-y-2 cursor-pointer transition-all">
                                    <div class="text-[10px] font-mono text-sky-300 font-bold">Phase 4 (Severe)</div>
                                    <div class="font-bold text-xs sm:text-sm text-white">Severe / Full Head Loss</div>
                                    <div class="w-16 h-16 mx-auto">
                                        <svg viewBox="0 0 100 110" class="w-full h-full">
                                            <ellipse cx="50" cy="58" rx="34" ry="42" fill="#141E38" stroke="#334155" stroke-width="2"/>
                                            <path d="M 47 16 L 50 11 L 53 16" stroke="#64748b" stroke-width="2" fill="none"/>
                                            <path d="M 18 70 Q 50 50 82 70 Q 82 85 75 95 Q 50 101 25 95 Z" fill="#24314E"/>
                                            <ellipse cx="50" cy="56" rx="30" ry="36" fill="#0284C7" stroke="#38BDF8" stroke-width="1.5"/>
                                        </svg>
                                    </div>
                                    <span class="text-[10px] text-purple-300 block">Full Cap System</span>
                                </div>
                            </div>
                        </template>

                        <!-- Women's Phase Cards in Matrix -->
                        <template x-if="matrixGender === 'women'">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                                <div @click="matrixPhase = 'w_phase1'"
                                    :class="matrixPhase === 'w_phase1' ? 'border-pink-400 bg-pink-950/40 ring-2 ring-pink-400/40' : 'border-slate-800 bg-slate-950/60 hover:border-slate-600'"
                                    class="p-3.5 rounded-2xl border text-center space-y-2 cursor-pointer transition-all">
                                    <div class="text-[10px] font-mono text-pink-300 font-bold">Phase 1 (Ludwig I)</div>
                                    <div class="font-bold text-xs sm:text-sm text-white">Parting Widening</div>
                                    <div class="text-[10px] text-slate-400">Rec. 4x5" Mini Piece</div>
                                </div>
                                <div @click="matrixPhase = 'w_phase2'"
                                    :class="matrixPhase === 'w_phase2' ? 'border-pink-400 bg-pink-950/40 ring-2 ring-pink-400/40' : 'border-slate-800 bg-slate-950/60 hover:border-slate-600'"
                                    class="p-3.5 rounded-2xl border text-center space-y-2 cursor-pointer transition-all">
                                    <div class="text-[10px] font-mono text-pink-300 font-bold">Phase 2 (Ludwig II) Top Pick</div>
                                    <div class="font-bold text-xs sm:text-sm text-white">Crown Scalp Exposure</div>
                                    <div class="text-[10px] text-pink-300 font-bold">Rec. 6x6" Silk Top Piece</div>
                                </div>
                                <div @click="matrixPhase = 'w_phase3'"
                                    :class="matrixPhase === 'w_phase3' ? 'border-pink-400 bg-pink-950/40 ring-2 ring-pink-400/40' : 'border-slate-800 bg-slate-950/60 hover:border-slate-600'"
                                    class="p-3.5 rounded-2xl border text-center space-y-2 cursor-pointer transition-all">
                                    <div class="text-[10px] font-mono text-pink-300 font-bold">Phase 3 (Ludwig III)</div>
                                    <div class="font-bold text-xs sm:text-sm text-white">Advanced Diffuse Loss</div>
                                    <div class="text-[10px] text-slate-400">Rec. 7x8" Wide Top</div>
                                </div>
                                <div @click="matrixPhase = 'w_phase4'"
                                    :class="matrixPhase === 'w_phase4' ? 'border-pink-400 bg-pink-950/40 ring-2 ring-pink-400/40' : 'border-slate-800 bg-slate-950/60 hover:border-slate-600'"
                                    class="p-3.5 rounded-2xl border text-center space-y-2 cursor-pointer transition-all">
                                    <div class="text-[10px] font-mono text-pink-300 font-bold">Phase 4 (Full Cover)</div>
                                    <div class="font-bold text-xs sm:text-sm text-white">Full Cranial Coverage</div>
                                    <div class="text-[10px] text-purple-300">Full Cap Medical Wig</div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- MATRIX ROW 2: FOREHEAD HAIRLINE EXPOSURE (CRITICAL FACTOR COMPARISON) -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-amber-500/20 border border-amber-400/40 text-amber-400 text-xs font-mono font-bold flex items-center justify-center">2</span>
                            <h4 class="font-heading text-base sm:text-lg font-bold text-white">
                                <span>Forehead Hairline Exposure Comparison (Core Tech Differences)</span>
                                <span class="text-xs text-amber-300 font-semibold ml-2">Click each style to compare base engineering</span>
                            </h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Matrix Style 1: Fully Exposed -->
                            <div @click="matrixHairline = 'exposed'"
                                :class="matrixHairline === 'exposed' ? 'border-amber-400 bg-gradient-to-b from-amber-950/40 to-slate-950 ring-2 ring-amber-400/50 shadow-xl' : 'border-slate-800 bg-slate-950/60 hover:border-slate-700'"
                                class="p-4 sm:p-5 rounded-2xl border space-y-3 cursor-pointer transition-all">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-amber-500/20 text-amber-300 border border-amber-400/30">100% Fully Exposed</span>
                                    <span class="text-[10px] text-slate-400">Hairline Precision: <strong class="text-amber-400">★★★★★</strong></span>
                                </div>
                                <div class="font-bold text-sm sm:text-base text-white">Slick-Back · Pompadour · Quiff</div>
                                <p class="text-xs text-slate-300 leading-relaxed">
                                    Hairline is swept fully back. Knots and edge must remain completely undetectable from 1cm away—mandates <strong>0.03mm Ultra-thin Skin</strong> or <strong>HD Swiss Lace</strong>.
                                </p>
                                <div class="text-[11px] bg-slate-900 p-2.5 rounded-lg border border-slate-800 text-slate-300">
                                    <span class="text-amber-300 font-bold block mb-0.5">• Core Base Technology:</span>
                                    <span>Ultra-thin Skin 0.03mm Knotless V-Loop Injection</span>
                                </div>
                            </div>

                            <!-- Matrix Style 2: Semi-Exposed -->
                            <div @click="matrixHairline = 'semi'"
                                :class="matrixHairline === 'semi' ? 'border-sky-400 bg-gradient-to-b from-sky-950/40 to-slate-950 ring-2 ring-sky-400/50 shadow-xl' : 'border-slate-800 bg-slate-950/60 hover:border-slate-700'"
                                class="p-4 sm:p-5 rounded-2xl border space-y-3 cursor-pointer transition-all">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-sky-500/20 text-sky-300 border border-sky-400/30">30~50% Semi-Exposed</span>
                                    <span class="text-[10px] text-slate-400">Hairline Precision: <strong class="text-sky-400">★★★★☆</strong></span>
                                </div>
                                <div class="font-bold text-sm sm:text-base text-white">Curtain Fringe · As-Perm · Side-Part</div>
                                <p class="text-xs text-slate-300 leading-relaxed">
                                    Soft parted bangs subtly revealing the forehead. Invisible Swiss/French lace front edge paired with a durable PU perimeter.
                                </p>
                                <div class="text-[11px] bg-slate-900 p-2.5 rounded-lg border border-slate-800 text-slate-300">
                                    <span class="text-sky-300 font-bold block mb-0.5">• Core Base Technology:</span>
                                    <span>Lace Front + Swiss Net + PU Perimeter</span>
                                </div>
                            </div>

                            <!-- Matrix Style 3: Covered Fringe -->
                            <div @click="matrixHairline = 'covered'"
                                :class="matrixHairline === 'covered' ? 'border-emerald-400 bg-gradient-to-b from-emerald-950/40 to-slate-950 ring-2 ring-emerald-400/50 shadow-xl' : 'border-slate-800 bg-slate-950/60 hover:border-slate-700'"
                                class="p-4 sm:p-5 rounded-2xl border space-y-3 cursor-pointer transition-all">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">100% Covered Fringe</span>
                                    <span class="text-[10px] text-slate-400">Hairline Precision: <strong class="text-emerald-400">★★★☆☆</strong></span>
                                </div>
                                <div class="font-bold text-sm sm:text-base text-white">Dandy Cut · Forward Bangs</div>
                                <p class="text-xs text-slate-300 leading-relaxed">
                                    Forward fringe completely masks the hairline. Maximizes longevity and breathability with durable Fine Swiss Net.
                                </p>
                                <div class="text-[11px] bg-slate-900 p-2.5 rounded-lg border border-slate-800 text-slate-300">
                                    <span class="text-emerald-300 font-bold block mb-0.5">• Core Base Technology:</span>
                                    <span>Fine Swiss Net + Durable PU (Up to 12-Month Lifespan)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MATRIX ROW 3: DYNAMIC PRESCRIPTION CARD -->
                    <div class="p-5 sm:p-7 rounded-2xl bg-slate-950 border-2 border-sky-500/40 space-y-5 shadow-2xl">
                        <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-800">
                            <div>
                                <span class="text-[10px] text-sky-400 uppercase font-mono font-bold">Live Matrix Prescription Output</span>
                                <h5 class="text-lg font-bold text-white flex items-center gap-2">
                                    <span>Live Matrix Prescription:</span>
                                    <span class="text-teal-300" x-text="matrixGender === 'men' ? (matrixHairline === 'exposed' ? '0.03mm Ultra-Thin Skin Slick-Back System' : (matrixHairline === 'semi' ? 'Lace Front Hybrid Curtain/Side-Part System' : 'Swiss Net High-Durability Dandy Fringe System')) : 'Triple-Injected Silk Top Premium Women\'s Topper'"></span>
                                </h5>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-slate-400 block">Factory Direct Estimate</span>
                                <span class="text-xl font-black text-amber-300 font-heading" x-text="matrixGender === 'men' ? (matrixPhase === 'phase4' ? '$900 AUD' : '$650 AUD~') : '$600 AUD~'"></span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                            <div class="p-3 rounded-xl bg-slate-900/90 border border-slate-800">
                                <span class="text-slate-400 block text-[10px]">Selected Hair Loss Stage</span>
                                <span class="font-bold text-white" x-text="matrixGender === 'men' ? (matrixPhase === 'phase1' ? 'Receding Temples (5x7~6x8\')' : (matrixPhase === 'phase2' ? 'M-Shape + Crown (7x9\')' : (matrixPhase === 'phase3' ? 'Advanced Top (8x10\')' : 'Severe Alopecia (Full Cap)'))) : (matrixPhase === 'w_phase1' ? 'Parting Widening (4x5\')' : (matrixPhase === 'w_phase2' ? 'Crown Exposure (6x6\')' : 'Advanced Diffuse (7x8\')'))"></span>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-900/90 border border-slate-800">
                                <span class="text-slate-400 block text-[10px]">Hairline Exposure Level</span>
                                <span class="font-bold text-amber-300" x-text="matrixHairline === 'exposed' ? '100% Fully Exposed (0.03mm Skin)' : (matrixHairline === 'semi' ? '30~50% Semi-Exposed (Lace Front)' : '100% Covered Fringe (Swiss Net PU)')"></span>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-900/90 border border-slate-800">
                                <span class="text-slate-400 block text-[10px]">Attachment Solution</span>
                                <span class="font-bold text-sky-300" x-text="matrixHairline === 'exposed' ? '4-Week Waterproof Bond' : 'Dual Clip &amp; Medical Tape System'"></span>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center gap-3 pt-2">
                            <button type="button" @click="applyDiagnosisToBuilder(matrixGender, matrixPhase, matrixHairline)"
                                class="w-full sm:w-2/3 py-3.5 rounded-xl teal-gradient hover:opacity-95 text-white font-extrabold text-xs sm:text-sm uppercase tracking-wider flex items-center justify-center gap-2 shadow-lg cursor-pointer transition-transform active:scale-95">
                                <i class="fa-solid fa-sliders"></i>
                                <span>Configure This Matrix Spec in Builder &rarr;</span>
                            </button>
                            <button type="button" @click="isBookingModalOpen = true"
                                class="w-full sm:w-1/3 py-3.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-600 text-slate-200 font-bold text-xs sm:text-sm flex items-center justify-center gap-2 transition-colors cursor-pointer">
                                <i class="fa-solid fa-calendar-check text-amber-400"></i>
                                <span>Book Private Salon Consultation</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

<?php dh_render_subpage_bottom('hair-diagnosis-hub'); ?>
</main>

<?php
require __DIR__ . '/includes/global_modals.php';
require __DIR__ . '/includes/footer.php';
?>
