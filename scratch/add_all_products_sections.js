const fs = require('fs');

let html = fs.readFileSync('index.html', 'utf8');

// Find the insertion point: right after </section> of #ai-simulator
const targetSplit = '<!-- 7. FACTORY DIRECT ADVANTAGE -->';
const splitIndex = html.indexOf(targetSplit);

if (splitIndex === -1) {
    console.error('Target <!-- 7. FACTORY DIRECT ADVANTAGE --> not found!');
    process.exit(1);
}

const productsSection = `<!-- 6.8 COMPREHENSIVE PRODUCT LINEUP & ACADEMY -->
    <section id="products-range" class="py-16 sm:py-24 bg-brand-darkBg border-t border-brand-border/60" x-data="{ activeCatalogTab: 'all' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-14 space-y-3">
                <span class="text-brand-tealLight text-xs font-bold uppercase tracking-widest block">Complete Professional Portfolio &amp; Academy</span>
                <h2 class="font-heading text-2xl sm:text-4xl font-extrabold text-white">
                    Bespoke Hair Systems, <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-sky-300 to-amber-400">Medical Wigs &amp; Extensions</span>
                </h2>
                <p class="text-brand-textMuted text-xs sm:text-sm max-w-2xl mx-auto leading-relaxed">
                    Explore our 11 specialized product lines: from 100% hand-tied medical wigs and non-surgical micro-implantation to luxury keratin fusion extensions and certified wig-maker masterclasses.
                </p>

                <!-- Filter Tabs -->
                <div class="flex flex-wrap items-center justify-center gap-2 pt-3">
                    <button type="button" @click="activeCatalogTab = 'all'"
                        :class="activeCatalogTab === 'all' ? 'bg-brand-teal text-white shadow-lg' : 'bg-brand-cardBg text-slate-300 hover:text-white border border-brand-border'"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer">
                        All Solutions (11)
                    </button>
                    <button type="button" @click="activeCatalogTab = 'wigs'"
                        :class="activeCatalogTab === 'wigs' ? 'bg-brand-teal text-white shadow-lg' : 'bg-brand-cardBg text-slate-300 hover:text-white border border-brand-border'"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer">
                        Wigs (Human &amp; Synthetic)
                    </button>
                    <button type="button" @click="activeCatalogTab = 'systems'"
                        :class="activeCatalogTab === 'systems' ? 'bg-brand-teal text-white shadow-lg' : 'bg-brand-cardBg text-slate-300 hover:text-white border border-brand-border'"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer">
                        Toupees, Toppers &amp; Fillers
                    </button>
                    <button type="button" @click="activeCatalogTab = 'micro'"
                        :class="activeCatalogTab === 'micro' ? 'bg-brand-teal text-white shadow-lg' : 'bg-brand-cardBg text-slate-300 hover:text-white border border-brand-border'"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer">
                        Hair Link &amp; Contacts
                    </button>
                    <button type="button" @click="activeCatalogTab = 'extensions'"
                        :class="activeCatalogTab === 'extensions' ? 'bg-brand-teal text-white shadow-lg' : 'bg-brand-cardBg text-slate-300 hover:text-white border border-brand-border'"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer">
                        Hair Extensions (3 Methods)
                    </button>
                    <button type="button" @click="activeCatalogTab = 'academy'"
                        :class="activeCatalogTab === 'academy' ? 'bg-amber-600 text-white shadow-lg' : 'bg-brand-cardBg text-amber-300 hover:text-white border border-amber-500/40'"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer">
                        Academy &amp; Training
                    </button>
                </div>
            </div>

            <!-- Product Grid (Cards 1 to 10) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 mb-12">

                <!-- 1. Human Hair Wig -->
                <div x-show="activeCatalogTab === 'all' || activeCatalogTab === 'wigs'"
                    class="bg-brand-cardBg border border-brand-border rounded-2xl p-6 sm:p-7 shadow-xl hover:border-teal-500/50 transition-all flex flex-col justify-between space-y-5 group">
                    <div class="space-y-3.5">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-teal-500/20 text-teal-300 border border-teal-500/30 uppercase tracking-wider">
                                Medical &amp; Alopecia
                            </span>
                            <span class="text-xs font-bold text-amber-400 flex items-center gap-1 font-mono">
                                <i class="fa-solid fa-crown text-[10px]"></i> Super REMY
                            </span>
                        </div>
                        <div>
                            <h3 class="font-heading text-xl font-bold text-white group-hover:text-brand-tealLight transition-colors">
                                Human Hair Wig
                            </h3>
                            <div class="text-xs font-semibold text-teal-300 mt-0.5">Custom-Make Available</div>
                        </div>
                        <p class="text-xs text-brand-textMuted leading-relaxed">
                            Natural-looking and comfortable wig for women who have experienced hair loss or have a sensitive scalp. Hand-tied monofilament top that allows your own skin tone to show through for an undetectable, realistic look.
                        </p>
                        <div class="space-y-2 pt-2 border-t border-brand-border/40 text-xs text-slate-300">
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-brand-tealLight text-xs mt-0.5"></i>
                                <span>Super REMY human hair with monofilament top &amp; all hand-tied</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-brand-tealLight text-xs mt-0.5"></i>
                                <span>Hand selected 100% human hair for natural movement &amp; luster</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-brand-tealLight text-xs mt-0.5"></i>
                                <span>Lacefront | Full hand-made craftsmanship | Long lasting durability</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-brand-tealLight text-xs mt-0.5"></i>
                                <span>Silktop (Silk Top) cap available by custom order</span>
                            </div>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-brand-border/40 space-y-3">
                        <div class="flex flex-wrap gap-1.5 text-[10px] font-mono">
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">Monofilament</span>
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">Silktop Option</span>
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">100% Hand-Tied</span>
                        </div>
                        <button type="button" @click="booking.service = 'Human Hair Wig Consultation ($0 Free)'; isBookingModalOpen = true"
                            class="w-full teal-gradient hover:opacity-95 text-white font-bold text-xs uppercase tracking-wider py-2.5 rounded-xl shadow transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-regular fa-calendar-check"></i>
                            <span>Inquire &amp; Book Consultation</span>
                        </button>
                    </div>
                </div>

                <!-- 2. Synthetic Hair Wig -->
                <div x-show="activeCatalogTab === 'all' || activeCatalogTab === 'wigs'"
                    class="bg-brand-cardBg border border-brand-border rounded-2xl p-6 sm:p-7 shadow-xl hover:border-teal-500/50 transition-all flex flex-col justify-between space-y-5 group">
                    <div class="space-y-3.5">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-sky-500/20 text-sky-300 border border-sky-500/30 uppercase tracking-wider">
                                Ready-to-Wear
                            </span>
                            <span class="text-xs font-bold text-emerald-400 flex items-center gap-1 font-mono">
                                <i class="fa-solid fa-bolt text-[10px]"></i> Shake &amp; Wear
                            </span>
                        </div>
                        <div>
                            <h3 class="font-heading text-xl font-bold text-white group-hover:text-brand-tealLight transition-colors">
                                Synthetic Hair Wig
                            </h3>
                            <div class="text-xs font-semibold text-sky-300 mt-0.5">No styling again — just shake and put it on</div>
                        </div>
                        <p class="text-xs text-brand-textMuted leading-relaxed">
                            Perfected synthetic hair fiber designed to look and behave just like human hair. Most synthetic wigs can be worn right out of the box with zero daily styling requirements, holding its style permanently regardless of climate or humidity.
                        </p>
                        <div class="space-y-2 pt-2 border-t border-brand-border/40 text-xs text-slate-300">
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-sky-400 text-xs mt-0.5"></i>
                                <span>Instantly wearable right out of the box with zero effort</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-sky-400 text-xs mt-0.5"></i>
                                <span>Heat-friendly synthetic options can be styled with heat tools</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-sky-400 text-xs mt-0.5"></i>
                                <span>Suitable for short-term wearing under 6 months (recovery or look changes)</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-sky-400 text-xs mt-0.5"></i>
                                <span>Price significantly lower than human hair wigs</span>
                            </div>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-brand-border/40 space-y-3">
                        <div class="flex flex-wrap gap-1.5 text-[10px] font-mono">
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">Zero Styling</span>
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">Climate-Proof</span>
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">Heat-Friendly Option</span>
                        </div>
                        <button type="button" @click="booking.service = 'Synthetic Hair Wig Assessment ($0 Free)'; isBookingModalOpen = true"
                            class="w-full bg-brand-inner hover:bg-slate-800 border border-brand-border hover:border-teal-500/50 text-white font-bold text-xs uppercase tracking-wider py-2.5 rounded-xl transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-regular fa-calendar-check text-sky-400"></i>
                            <span>View Styles &amp; Fit</span>
                        </button>
                    </div>
                </div>

                <!-- 3. Pin point Hair Link -->
                <div x-show="activeCatalogTab === 'all' || activeCatalogTab === 'micro'"
                    class="bg-brand-cardBg border border-brand-border rounded-2xl p-6 sm:p-7 shadow-xl hover:border-teal-500/50 transition-all flex flex-col justify-between space-y-5 group">
                    <div class="space-y-3.5">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 uppercase tracking-wider">
                                Non-Surgical Implantation
                            </span>
                            <span class="text-xs font-bold text-brand-tealLight flex items-center gap-1 font-mono">
                                <i class="fa-solid fa-link text-[10px]"></i> Micro-Link
                            </span>
                        </div>
                        <div>
                            <h3 class="font-heading text-xl font-bold text-white group-hover:text-brand-tealLight transition-colors">
                                Pin Point Hair Link
                            </h3>
                            <div class="text-xs font-semibold text-amber-300 mt-0.5">Strand-by-Strand Hair Thickening Technique</div>
                        </div>
                        <p class="text-xs text-brand-textMuted leading-relaxed">
                            No chemicals, adhesives, weaves or hair systems involved. An advanced solution that is completely natural and safe: 4~6 strands of hair are linked directly to a single strand of your growing hair to boost density instantly.
                        </p>
                        <div class="space-y-2 pt-2 border-t border-brand-border/40 text-xs text-slate-300">
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-amber-400 text-xs mt-0.5"></i>
                                <span>Instantly increase the density of hair wherever desired</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-amber-400 text-xs mt-0.5"></i>
                                <span>Zero damage, zero shaving, zero stress to growing natural hair</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-amber-400 text-xs mt-0.5"></i>
                                <span>Free Training available for Contracted HFLS Salons</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-amber-400 text-xs mt-0.5"></i>
                                <span>Supply to certified technicians &amp; salons only</span>
                            </div>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-brand-border/40 space-y-3">
                        <div class="flex flex-wrap gap-1.5 text-[10px] font-mono">
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">4~6 Strands Link</span>
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">No Glue / Chemicals</span>
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-amber-300 border border-amber-500/30">HFLS Salons Only</span>
                        </div>
                        <button type="button" @click="booking.service = 'Pin Point Hair Link Assessment ($0 Free)'; isBookingModalOpen = true"
                            class="w-full teal-gradient hover:opacity-95 text-white font-bold text-xs uppercase tracking-wider py-2.5 rounded-xl shadow transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-user-doctor"></i>
                            <span>Book Technique Consultation</span>
                        </button>
                    </div>
                </div>

                <!-- 4. Mini Hair Filler -->
                <div x-show="activeCatalogTab === 'all' || activeCatalogTab === 'systems'"
                    class="bg-brand-cardBg border border-brand-border rounded-2xl p-6 sm:p-7 shadow-xl hover:border-teal-500/50 transition-all flex flex-col justify-between space-y-5 group">
                    <div class="space-y-3.5">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30 uppercase tracking-wider">
                                Featherweight Fill
                            </span>
                            <span class="text-xs font-bold text-teal-300 flex items-center gap-1 font-mono">
                                <i class="fa-solid fa-feather text-[10px]"></i> Ultra Light
                            </span>
                        </div>
                        <div>
                            <h3 class="font-heading text-xl font-bold text-white group-hover:text-brand-tealLight transition-colors">
                                Mini Hair Filler
                            </h3>
                            <div class="text-xs font-semibold text-rose-300 mt-0.5">Perfect for Fine &amp; Thinning Hair</div>
                        </div>
                        <p class="text-xs text-brand-textMuted leading-relaxed">
                            Mini hair filler blends seamlessly with your own hair. Small specialized micro-clips grip even the finest of hair for a secure fit. Achieve instant scalp cover and natural fullness without any bulkiness.
                        </p>
                        <div class="space-y-2 pt-2 border-t border-brand-border/40 text-xs text-slate-300">
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-rose-400 text-xs mt-0.5"></i>
                                <span>100% Hand-Selected Human Hair</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-rose-400 text-xs mt-0.5"></i>
                                <span>Lightest hair piece available — feels just like a feather</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-rose-400 text-xs mt-0.5"></i>
                                <span>Micro-clips hold securely onto delicate, sparse baby hair</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-rose-400 text-xs mt-0.5"></i>
                                <span>Targeted coverage for crown, hairline, and part line</span>
                            </div>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-brand-border/40 space-y-3">
                        <div class="flex flex-wrap gap-1.5 text-[10px] font-mono">
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">100% Human Hair</span>
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">Micro-Clip</span>
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">Zero Bulk</span>
                        </div>
                        <button type="button" @click="booking.service = 'Mini Hair Filler Trial ($0 Free)'; isBookingModalOpen = true"
                            class="w-full teal-gradient hover:opacity-95 text-white font-bold text-xs uppercase tracking-wider py-2.5 rounded-xl shadow transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-regular fa-calendar-check"></i>
                            <span>Try Mini Filler in Salon</span>
                        </button>
                    </div>
                </div>

                <!-- 5. Topper -->
                <div x-show="activeCatalogTab === 'all' || activeCatalogTab === 'systems'"
                    class="bg-brand-cardBg border border-brand-border rounded-2xl p-6 sm:p-7 shadow-xl hover:border-teal-500/50 transition-all flex flex-col justify-between space-y-5 group">
                    <div class="space-y-3.5">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-purple-500/20 text-purple-300 border border-purple-500/30 uppercase tracking-wider">
                                Women's Volume
                            </span>
                            <span class="text-xs font-bold text-amber-300 flex items-center gap-1 font-mono">
                                <i class="fa-solid fa-arrows-to-eye text-[10px]"></i> Crown Focus
                            </span>
                        </div>
                        <div>
                            <h3 class="font-heading text-xl font-bold text-white group-hover:text-brand-tealLight transition-colors">
                                Hair Topper (Top Piece)
                            </h3>
                            <div class="text-xs font-semibold text-purple-300 mt-0.5">The Best Alternative to a Full Wig</div>
                        </div>
                        <p class="text-xs text-brand-textMuted leading-relaxed">
                            Hair toppers instantly transform your look by adding voluminous body to fine, thinning hair. Our clip-in hair toppers offer women unique solutions at each stage of hair loss without the weight of a full cap.
                        </p>
                        <div class="space-y-2 pt-2 border-t border-brand-border/40 text-xs text-slate-300">
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-purple-400 text-xs mt-0.5"></i>
                                <span>Hairpieces for women: Toppers, Toupees, Hair pieces &amp; Top pieces</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-purple-400 text-xs mt-0.5"></i>
                                <span>Clips securely into your biological hair in under 30 seconds</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-purple-400 text-xs mt-0.5"></i>
                                <span>Silk &amp; monofilament base matches your real scalp tone seamlessly</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-purple-400 text-xs mt-0.5"></i>
                                <span>Multi-directional parting allows flexible, creative styling</span>
                            </div>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-brand-border/40 space-y-3">
                        <div class="flex flex-wrap gap-1.5 text-[10px] font-mono">
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">Full Wig Alt</span>
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">Clip-In Ease</span>
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">Silk Parting</span>
                        </div>
                        <button type="button" @click="booking.service = 'Hair Topper Fitting ($0 Free)'; isBookingModalOpen = true"
                            class="w-full teal-gradient hover:opacity-95 text-white font-bold text-xs uppercase tracking-wider py-2.5 rounded-xl shadow transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-regular fa-calendar-check"></i>
                            <span>Schedule Private Fitting</span>
                        </button>
                    </div>
                </div>

                <!-- 6. Hair Contacts -->
                <div x-show="activeCatalogTab === 'all' || activeCatalogTab === 'micro'"
                    class="bg-brand-cardBg border border-brand-border rounded-2xl p-6 sm:p-7 shadow-xl hover:border-teal-500/50 transition-all flex flex-col justify-between space-y-5 group">
                    <div class="space-y-3.5">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-teal-500/20 text-teal-300 border border-teal-500/30 uppercase tracking-wider">
                                Silicon Skin Tape
                            </span>
                            <span class="text-xs font-bold text-rose-400 flex items-center gap-1 font-mono">
                                <i class="fa-solid fa-microchip text-[10px]"></i> 0.02mm Base
                            </span>
                        </div>
                        <div>
                            <h3 class="font-heading text-xl font-bold text-white group-hover:text-brand-tealLight transition-colors">
                                Hair Contacts
                            </h3>
                            <div class="text-xs font-semibold text-teal-300 mt-0.5">Skin Hair Thickening Tape</div>
                        </div>
                        <p class="text-xs text-brand-textMuted leading-relaxed">
                            100% Human hair is knotted onto ultra-thin silicon skin. You may use it anywhere whether on bare skin or over sparse hair. Provides perfect, undetectable blending for receding hair-lines, bald patches, and localized thinning.
                        </p>
                        <div class="space-y-2 pt-2 border-t border-brand-border/40 text-xs text-slate-300">
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-brand-tealLight text-xs mt-0.5"></i>
                                <span>Perfect for receding hair-lines, alopecia bald patches &amp; thinning spots</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-brand-tealLight text-xs mt-0.5"></i>
                                <span>Compatible with waterproof double-sided tape and special medical adhesive</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-brand-tealLight text-xs mt-0.5"></i>
                                <span>Flawless blending of hair so natural it mimics pores</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-brand-tealLight text-xs mt-0.5"></i>
                                <span>Free Training available for Contracted HFLS Salons (Supply to technicians only)</span>
                            </div>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-brand-border/40 space-y-3">
                        <div class="flex flex-wrap gap-1.5 text-[10px] font-mono">
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">Silicon Skin</span>
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">Bald Patch Solution</span>
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-teal-300 border border-teal-500/30">HFLS Salons Only</span>
                        </div>
                        <button type="button" @click="booking.service = 'Hair Contacts Assessment ($0 Free)'; isBookingModalOpen = true"
                            class="w-full teal-gradient hover:opacity-95 text-white font-bold text-xs uppercase tracking-wider py-2.5 rounded-xl shadow transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>Inquire Hair Contacts</span>
                        </button>
                    </div>
                </div>

                <!-- 7. Men's hair system -->
                <div x-show="activeCatalogTab === 'all' || activeCatalogTab === 'systems'"
                    class="bg-brand-cardBg border border-brand-border rounded-2xl p-6 sm:p-7 shadow-xl hover:border-teal-500/50 transition-all flex flex-col justify-between space-y-5 group">
                    <div class="space-y-3.5">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-sky-500/20 text-sky-300 border border-sky-500/30 uppercase tracking-wider">
                                Non-Surgical Toupee
                            </span>
                            <span class="text-xs font-bold text-teal-300 flex items-center gap-1 font-mono">
                                <i class="fa-solid fa-clock text-[10px]"></i> &lt; 2-Hour Fit
                            </span>
                        </div>
                        <div>
                            <h3 class="font-heading text-xl font-bold text-white group-hover:text-brand-tealLight transition-colors">
                                Men's Hair System (Toupee)
                            </h3>
                            <div class="text-xs font-semibold text-sky-300 mt-0.5">Men's &amp; Ladies Hair Replacement System</div>
                        </div>
                        <p class="text-xs text-brand-textMuted leading-relaxed">
                            Undetectable hairpiece for men's hair loss. Human hair is hand-implanted into artificial skin and finest mesh net. In less than 2 hours, we install the hair system onto your scalp—zero pain and non-surgical. It becomes a part of you.
                        </p>
                        <div class="space-y-2 pt-2 border-t border-brand-border/40 text-xs text-slate-300">
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-sky-400 text-xs mt-0.5"></i>
                                <span>Poly Around Full Perimeter Base option</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-sky-400 text-xs mt-0.5"></i>
                                <span>Thin Front Skin + Poly Side and Back option</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-sky-400 text-xs mt-0.5"></i>
                                <span>Thin Front Skin + Realistic Part + Poly Side &amp; Back</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-sky-400 text-xs mt-0.5"></i>
                                <span>Ready-made &amp; custom-made available, styled in various cuts</span>
                            </div>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-brand-border/40 space-y-3">
                        <div class="flex flex-wrap gap-1.5 text-[10px] font-mono">
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">Full Poly</span>
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">Thin Skin</span>
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">Ready &amp; Bespoke</span>
                        </div>
                        <a href="#builder"
                            class="w-full teal-gradient hover:opacity-95 text-white font-bold text-xs uppercase tracking-wider py-2.5 rounded-xl shadow transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-sliders"></i>
                            <span>Configure in 3D Builder</span>
                        </a>
                    </div>
                </div>

                <!-- 8. Tape-in hair Extension -->
                <div x-show="activeCatalogTab === 'all' || activeCatalogTab === 'extensions'"
                    class="bg-brand-cardBg border border-brand-border rounded-2xl p-6 sm:p-7 shadow-xl hover:border-teal-500/50 transition-all flex flex-col justify-between space-y-5 group">
                    <div class="space-y-3.5">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 uppercase tracking-wider">
                                Extension Method #1
                            </span>
                            <span class="text-xs font-bold text-amber-300 flex items-center gap-1 font-mono">
                                <i class="fa-solid fa-ruler-vertical text-[10px]"></i> 18"-24"
                            </span>
                        </div>
                        <div>
                            <h3 class="font-heading text-xl font-bold text-white group-hover:text-brand-tealLight transition-colors">
                                Tape-In Hair Extension
                            </h3>
                            <div class="text-xs font-semibold text-emerald-300 mt-0.5">Seamless / Invisiblend Tape-In Extension</div>
                        </div>
                        <p class="text-xs text-brand-textMuted leading-relaxed">
                            100% Human hair is knotted onto polymer skin. Perfectly blends with your own hair and growing hair in complete safety and comfort. Flat medical-grade tabs remain completely invisible even in fine hair.
                        </p>
                        <div class="space-y-2 pt-2 border-t border-brand-border/40 text-xs text-slate-300">
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-emerald-400 text-xs mt-0.5"></i>
                                <span>Available lengths: 18 / 20 / 22 / 24 inches by order</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-emerald-400 text-xs mt-0.5"></i>
                                <span>12 Basic Colors, Highlighted Colors, &amp; Balayage multi-tone</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-emerald-400 text-xs mt-0.5"></i>
                                <span>Grading: Single drawn, Double drawn &amp; Super double drawn</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-emerald-400 text-xs mt-0.5"></i>
                                <span>Quick 45-minute re-application with zero residue</span>
                            </div>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-brand-border/40 space-y-3">
                        <div class="flex flex-wrap gap-1.5 text-[10px] font-mono">
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">Polymer Skin</span>
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">12 Colors + Balayage</span>
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">Super Double Drawn</span>
                        </div>
                        <button type="button" @click="booking.service = 'Tape-In Hair Extension Consultation ($0 Free)'; isBookingModalOpen = true"
                            class="w-full teal-gradient hover:opacity-95 text-white font-bold text-xs uppercase tracking-wider py-2.5 rounded-xl shadow transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-regular fa-calendar-check"></i>
                            <span>Inquire Tape-In Extensions</span>
                        </button>
                    </div>
                </div>

                <!-- 9. Fusion hair extensions -->
                <div x-show="activeCatalogTab === 'all' || activeCatalogTab === 'extensions'"
                    class="bg-brand-cardBg border border-brand-border rounded-2xl p-6 sm:p-7 shadow-xl hover:border-teal-500/50 transition-all flex flex-col justify-between space-y-5 group">
                    <div class="space-y-3.5">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 uppercase tracking-wider">
                                Extension Method #2
                            </span>
                            <span class="text-xs font-bold text-teal-300 flex items-center gap-1 font-mono">
                                <i class="fa-solid fa-fire-flame-curved text-[10px]"></i> Keratin Bond
                            </span>
                        </div>
                        <div>
                            <h3 class="font-heading text-xl font-bold text-white group-hover:text-brand-tealLight transition-colors">
                                Fusion Hair Extensions
                            </h3>
                            <div class="text-xs font-semibold text-amber-300 mt-0.5">I-Tip, U-Tip, V-Tip, Flat Tip Bonds</div>
                        </div>
                        <p class="text-xs text-brand-textMuted leading-relaxed">
                            Fusion tip hair extensions are tipped with high-grade Italian keratin adhesive gentle on your natural hair. A fast method to apply extensions that lie ultra-flat and undetectable, offering 360-degree free styling.
                        </p>
                        <div class="space-y-2 pt-2 border-t border-brand-border/40 text-xs text-slate-300">
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-amber-400 text-xs mt-0.5"></i>
                                <span>I-Tip, U-Tip (nail tip), V-Tip &amp; Flat Tip configurations</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-amber-400 text-xs mt-0.5"></i>
                                <span>High quality Italian keratin adhesive protects natural hair strands</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-amber-400 text-xs mt-0.5"></i>
                                <span>Grading: Single drawn, Double drawn &amp; Super double drawn</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-amber-400 text-xs mt-0.5"></i>
                                <span>Removes smoothly with gentle release solution without damage</span>
                            </div>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-brand-border/40 space-y-3">
                        <div class="flex flex-wrap gap-1.5 text-[10px] font-mono">
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">I/U/V/Flat Tips</span>
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">Italian Keratin</span>
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">Super Double Drawn</span>
                        </div>
                        <button type="button" @click="booking.service = 'Fusion Hair Extension Consultation ($0 Free)'; isBookingModalOpen = true"
                            class="w-full teal-gradient hover:opacity-95 text-white font-bold text-xs uppercase tracking-wider py-2.5 rounded-xl shadow transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-regular fa-calendar-check"></i>
                            <span>Inquire Keratin Fusion</span>
                        </button>
                    </div>
                </div>

                <!-- 10. Weaving hair Extension -->
                <div x-show="activeCatalogTab === 'all' || activeCatalogTab === 'extensions'"
                    class="bg-brand-cardBg border border-brand-border rounded-2xl p-6 sm:p-7 shadow-xl hover:border-teal-500/50 transition-all flex flex-col justify-between space-y-5 group">
                    <div class="space-y-3.5">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 uppercase tracking-wider">
                                Extension Method #3
                            </span>
                            <span class="text-xs font-bold text-amber-300 flex items-center gap-1 font-mono">
                                <i class="fa-solid fa-scissors text-[10px]"></i> Weft Weave
                            </span>
                        </div>
                        <div>
                            <h3 class="font-heading text-xl font-bold text-white group-hover:text-brand-tealLight transition-colors">
                                Weaving Hair Extension (Wefts)
                            </h3>
                            <div class="text-xs font-semibold text-indigo-300 mt-0.5">Traditional Hair Extension Method</div>
                        </div>
                        <p class="text-xs text-brand-textMuted leading-relaxed">
                            Easy, quick and safest way of applying a hair extension weave. The installation process takes less than 1 hour and can be removed in less than 15 minutes, with zero chemical exposure to your natural hair.
                        </p>
                        <div class="space-y-2 pt-2 border-t border-brand-border/40 text-xs text-slate-300">
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-indigo-400 text-xs mt-0.5"></i>
                                <span>Fast install under 1 hour; removed in less than 15 minutes</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-indigo-400 text-xs mt-0.5"></i>
                                <span>18 / 20 / 22 / 24 inches available by request</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-indigo-400 text-xs mt-0.5"></i>
                                <span>Straight | Wavy | Curl Style (custom wholesale available)</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-indigo-400 text-xs mt-0.5"></i>
                                <span>European | East Asian | Indian hair origins available</span>
                            </div>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-brand-border/40 space-y-3">
                        <div class="flex flex-wrap gap-1.5 text-[10px] font-mono">
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">&lt; 1hr Install</span>
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">Euro / Asian / Indian</span>
                            <span class="bg-brand-inner px-2 py-0.5 rounded text-slate-300 border border-brand-border">Straight &amp; Curl</span>
                        </div>
                        <button type="button" @click="booking.service = 'Weaving Hair Extension Consultation ($0 Free)'; isBookingModalOpen = true"
                            class="w-full teal-gradient hover:opacity-95 text-white font-bold text-xs uppercase tracking-wider py-2.5 rounded-xl shadow transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-regular fa-calendar-check"></i>
                            <span>Inquire Weave Extensions</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- 11. Wig making Training course (Grand Featured Academy Banner) -->
            <div id="wig-training" x-show="activeCatalogTab === 'all' || activeCatalogTab === 'academy'"
                class="rounded-3xl bg-gradient-to-br from-[#121E40] via-[#1A2650] to-[#0D152C] border-2 border-amber-500/40 p-6 sm:p-10 shadow-2xl space-y-8 relative overflow-hidden">
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 pb-6 border-b border-brand-border/80">
                    <div class="space-y-2 max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 border border-amber-400/40 text-xs font-bold font-mono">
                            <i class="fa-solid fa-graduation-cap text-amber-400"></i>
                            <span>David Hair Professional Academy</span>
                        </div>
                        <h3 class="font-heading text-2xl sm:text-3xl font-extrabold text-white">
                            Wig Making &amp; Hair Extensionist Training Course
                        </h3>
                        <p class="text-sm text-slate-300 leading-relaxed">
                            Do you want to be a professional Wig maker and Hair extensionist? Our training is very special with fully experienced master wig makers and clinical hair extensionists. Complete practical, client measurement, cutting-in, and salon business monetization.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row lg:flex-col gap-3 w-full lg:w-auto shrink-0">
                        <button type="button" @click="booking.service = 'Wig Making & Hair Extensionist Training Course ($0 Free Consultation)'; isBookingModalOpen = true"
                            class="bronze-gradient hover:opacity-95 text-white font-extrabold text-xs uppercase tracking-wider px-6 py-3.5 rounded-xl shadow-xl transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-file-signature"></i>
                            <span>Enroll / Course Inquiry ($0)</span>
                        </button>
                        <a href="https://wa.me/614334894882?text=Hello%20David%20Hair%20Academy%2C%20I%20am%20interested%20in%20the%20Wig%20Making%20and%20Hair%20Extensionist%20Training%20Course."
                            target="_blank" rel="noopener"
                            class="bg-brand-inner hover:bg-slate-800 border border-amber-500/40 text-amber-300 hover:text-white font-bold text-xs uppercase tracking-wider px-6 py-3.5 rounded-xl transition-all flex items-center justify-center gap-2">
                            <i class="fa-brands fa-whatsapp text-emerald-400 text-sm"></i>
                            <span>Chat with Master Trainer</span>
                        </a>
                    </div>
                </div>

                <!-- 7 Core Curriculum Modules -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-amber-300 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-book-open"></i>
                        <span>What You Will Master in This Course</span>
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-brand-darkBg/80 border border-brand-border/80 p-4 rounded-xl space-y-1.5">
                            <div class="w-7 h-7 rounded-lg bg-amber-500/20 text-amber-300 flex items-center justify-center font-bold text-xs">1</div>
                            <div class="text-xs font-bold text-white">How to Choose Wig for Customer</div>
                            <p class="text-[11px] text-brand-textMuted">Matching face structure, age, lifestyle, density &amp; skin tone.</p>
                        </div>
                        <div class="bg-brand-darkBg/80 border border-brand-border/80 p-4 rounded-xl space-y-1.5">
                            <div class="w-7 h-7 rounded-lg bg-amber-500/20 text-amber-300 flex items-center justify-center font-bold text-xs">2</div>
                            <div class="text-xs font-bold text-white">How to Measure Head for Customer</div>
                            <p class="text-[11px] text-brand-textMuted">Precision tape drafting, plaster molds, &amp; 3D scalp contour template sculpting.</p>
                        </div>
                        <div class="bg-brand-darkBg/80 border border-brand-border/80 p-4 rounded-xl space-y-1.5">
                            <div class="w-7 h-7 rounded-lg bg-amber-500/20 text-amber-300 flex items-center justify-center font-bold text-xs">3</div>
                            <div class="text-xs font-bold text-white">How to Fit &amp; Style</div>
                            <p class="text-[11px] text-brand-textMuted">Seamless barber cut-in, feather blending, perimeter gradient styling.</p>
                        </div>
                        <div class="bg-brand-darkBg/80 border border-brand-border/80 p-4 rounded-xl space-y-1.5">
                            <div class="w-7 h-7 rounded-lg bg-amber-500/20 text-amber-300 flex items-center justify-center font-bold text-xs">4</div>
                            <div class="text-xs font-bold text-white">Maintenance &amp; Base Care</div>
                            <p class="text-[11px] text-brand-textMuted">Ventilation repairs, knotting fixes, lace cleansing, solvent protocols.</p>
                        </div>
                        <div class="bg-brand-darkBg/80 border border-brand-border/80 p-4 rounded-xl space-y-1.5">
                            <div class="w-7 h-7 rounded-lg bg-teal-500/20 text-teal-300 flex items-center justify-center font-bold text-xs">5</div>
                            <div class="text-xs font-bold text-white">9 Methods of Hair Extension</div>
                            <p class="text-[11px] text-brand-textMuted">Tape-in, I-tip, U-tip, Flat tip, Weave, Link, Micro-ring, Nano &amp; Clip-in.</p>
                        </div>
                        <div class="bg-brand-darkBg/80 border border-brand-border/80 p-4 rounded-xl space-y-1.5">
                            <div class="w-7 h-7 rounded-lg bg-teal-500/20 text-teal-300 flex items-center justify-center font-bold text-xs">6</div>
                            <div class="text-xs font-bold text-white">Medical Hair Prosthetics</div>
                            <p class="text-[11px] text-brand-textMuted">Medical cranial prostheses for alopecia, chemotherapy, &amp; sensitive scalps.</p>
                        </div>
                        <div class="bg-brand-darkBg/80 border border-brand-border/80 p-4 rounded-xl space-y-1.5">
                            <div class="w-7 h-7 rounded-lg bg-teal-500/20 text-teal-300 flex items-center justify-center font-bold text-xs">7</div>
                            <div class="text-xs font-bold text-white">Salon Business Monetization</div>
                            <p class="text-[11px] text-brand-textMuted">Factory-direct wholesale pricing access, client consultation scripts &amp; pricing.</p>
                        </div>
                        <div class="bg-brand-darkBg/80 border border-amber-500/40 p-4 rounded-xl space-y-1.5 flex flex-col justify-center text-center">
                            <i class="fa-solid fa-award text-2xl text-amber-400 mb-1"></i>
                            <div class="text-xs font-bold text-white">Official Certificate</div>
                            <p class="text-[10px] text-amber-300 font-mono">Issued upon completion</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    `;

const updatedHtml = html.slice(0, splitIndex) + productsSection + html.slice(splitIndex);

// Also add "All Products & Wigs" and "Training Course" into the PRODUCTS dropdown menu
const oldProductsDropdown = `<div class="rounded-xl bg-brand-cardBg/95 backdrop-blur-md border border-brand-border shadow-2xl p-2 space-y-1">
                            <a href="#builder" @click="productsOpen = false" class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-brand-inner text-slate-200 hover:text-white transition-colors group/item">
                                <div class="w-8 h-8 rounded-lg bg-teal-500/15 border border-teal-500/30 flex items-center justify-center text-brand-tealLight group-hover/item:scale-105 transition-transform">
                                    <i class="fa-solid fa-sliders text-xs"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-xs uppercase tracking-wider">Order</div>
                                    <div class="text-[10px] text-brand-textMuted">Custom Toupee Builder</div>
                                </div>
                            </a>
                            <a href="#stock" @click="productsOpen = false" class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-brand-inner text-slate-200 hover:text-white transition-colors group/item">
                                <div class="w-8 h-8 rounded-lg bg-amber-500/15 border border-amber-500/30 flex items-center justify-center text-amber-400 group-hover/item:scale-105 transition-transform">
                                    <i class="fa-solid fa-boxes-stacked text-xs"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-xs uppercase tracking-wider">Stock</div>
                                    <div class="text-[10px] text-brand-textMuted">Ready-to-Ship Inventory</div>
                                </div>
                            </a>
                        </div>`;

const newProductsDropdown = `<div class="rounded-xl bg-brand-cardBg/95 backdrop-blur-md border border-brand-border shadow-2xl p-2 space-y-1">
                            <a href="#builder" @click="productsOpen = false" class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-brand-inner text-slate-200 hover:text-white transition-colors group/item">
                                <div class="w-8 h-8 rounded-lg bg-teal-500/15 border border-teal-500/30 flex items-center justify-center text-brand-tealLight group-hover/item:scale-105 transition-transform">
                                    <i class="fa-solid fa-sliders text-xs"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-xs uppercase tracking-wider">Order</div>
                                    <div class="text-[10px] text-brand-textMuted">Custom Toupee Builder</div>
                                </div>
                            </a>
                            <a href="#stock" @click="productsOpen = false" class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-brand-inner text-slate-200 hover:text-white transition-colors group/item">
                                <div class="w-8 h-8 rounded-lg bg-amber-500/15 border border-amber-500/30 flex items-center justify-center text-amber-400 group-hover/item:scale-105 transition-transform">
                                    <i class="fa-solid fa-boxes-stacked text-xs"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-xs uppercase tracking-wider">Stock</div>
                                    <div class="text-[10px] text-brand-textMuted">Ready-to-Ship Inventory</div>
                                </div>
                            </a>
                            <a href="#products-range" @click="productsOpen = false" class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-brand-inner text-slate-200 hover:text-white transition-colors group/item border-t border-brand-border/40 mt-1">
                                <div class="w-8 h-8 rounded-lg bg-sky-500/15 border border-sky-500/30 flex items-center justify-center text-sky-400 group-hover/item:scale-105 transition-transform">
                                    <i class="fa-solid fa-layer-group text-xs"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-xs uppercase tracking-wider">All Solutions (11)</div>
                                    <div class="text-[10px] text-brand-textMuted">Wigs, Systems &amp; Extensions</div>
                                </div>
                            </a>
                            <a href="#wig-training" @click="productsOpen = false" class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-brand-inner text-amber-300 hover:text-amber-200 transition-colors group/item">
                                <div class="w-8 h-8 rounded-lg bg-amber-500/20 border border-amber-500/40 flex items-center justify-center text-amber-400 group-hover/item:scale-105 transition-transform">
                                    <i class="fa-solid fa-graduation-cap text-xs"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-xs uppercase tracking-wider">Academy Course</div>
                                    <div class="text-[10px] text-amber-400/80">Wig Maker &amp; Extensionist</div>
                                </div>
                            </a>
                        </div>`;

let finalHtml = updatedHtml;
if (finalHtml.includes(oldProductsDropdown)) {
    finalHtml = finalHtml.replace(oldProductsDropdown, newProductsDropdown);
    console.log('PRODUCTS dropdown enriched with links to new catalog and academy!');
}

fs.writeFileSync('index.html', finalHtml, 'utf8');
console.log('Successfully injected all 11 products & academy sections into index.html!');
