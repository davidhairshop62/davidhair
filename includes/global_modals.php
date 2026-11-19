<!-- GLOBAL SHARED MODALS (Location, Welcome, Booking, Auth, Lazy-Loaded Tools) -->
    <!-- GOOGLE MAPS LOCATION MODAL -->
    <div x-show="isMapModalOpen" x-cloak
        @keydown.escape.window="isMapModalOpen = false"
        class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-5 bg-black/85 backdrop-blur-md overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        
        <div @click.away="isMapModalOpen = false"
            class="bg-gradient-to-b from-[#111A3A] to-[#0A0F24] border border-teal-500/40 rounded-3xl max-w-4xl w-full shadow-2xl relative my-6 text-white overflow-hidden flex flex-col max-h-[92vh]">
            
            <!-- Modal Header (Sticky) -->
            <div class="px-5 sm:px-7 py-4 sm:py-5 border-b border-brand-border/80 flex items-center justify-between bg-brand-darkBg/95 backdrop-blur-md sticky top-0 z-20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl teal-gradient flex items-center justify-center text-white text-base shadow-sm shrink-0">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-heading font-extrabold text-base sm:text-xl text-white tracking-tight">
                                David Hair Shop • Melbourne Studio
                            </h3>
                            <span class="hidden xs:inline-block text-[10px] bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 px-2 py-0.5 rounded-full font-mono font-bold">Springvale VIC</span>
                        </div>
                        <p class="text-[11px] sm:text-xs text-brand-textMuted flex items-center gap-1.5 mt-0.5">
                            <i class="fa-regular fa-clock text-teal-400"></i>
                            <span>Mon-Sun (<strong class="font-extrabold text-amber-400 tracking-wide">Wed Closed</strong>) 10:30AM – 5:00PM (100% Private 1-on-1 Consultation &amp; Fitting Suite) <span class="font-bold text-teal-300">Need Make an Appointment</span></span>
                        </p>
                    </div>
                </div>

                <!-- Close Button -->
                <button type="button" @click="isMapModalOpen = false"
                    class="w-9 h-9 rounded-xl bg-brand-inner hover:bg-white/10 border border-brand-border text-slate-400 hover:text-white flex items-center justify-center transition-colors cursor-pointer shrink-0"
                    title="Close Map (Esc)">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <!-- Modal Body (Map & Quick Info) -->
            <div class="p-4 sm:p-6 overflow-y-auto space-y-4 flex-1">
                <!-- Google Maps Interactive Iframe -->
                <div class="relative w-full h-[320px] sm:h-[420px] rounded-2xl overflow-hidden border border-brand-border/80 shadow-inner bg-slate-900">
                    <template x-if="isMapModalOpen">
                    <iframe
                        :src="isMapModalOpen ? googleMapsEmbedUrl : ''"
                        class="w-full h-full border-0 block"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="David Hair Shop Google Map">
                    </iframe>
                    </template>
                </div>

                <!-- Address & Contact Info Card -->
                <div class="bg-brand-inner/70 border border-brand-border rounded-2xl p-4 sm:p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="space-y-1.5">
                        <div class="flex items-start gap-2.5">
                            <i class="fa-solid fa-building-user text-brand-tealLight mt-1 text-sm shrink-0"></i>
                            <div>
                                <span class="font-bold text-white text-sm block">Shop F16 (Level 1), Buckingham Ave</span>
                                <span class="text-xs text-slate-300">Springvale, VIC 3171, Melbourne Australia</span>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-4 text-xs text-slate-400 pt-1">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-phone text-teal-400"></i>
                                <a href="tel:+61433484882" class="hover:text-white font-mono">+61 433 484 882</a>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <i class="fa-brands fa-whatsapp text-emerald-400"></i>
                                <a href="https://wa.me/61433484882" target="_blank" rel="noopener" class="hover:text-white">WhatsApp Available</a>
                            </span>
                        </div>
                    </div>

                    <!-- Action Buttons in Card -->
                    <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                        <!-- Copy Address Button -->
                        <button type="button" @click="copyStudioAddress()"
                            class="flex-1 md:flex-initial px-4 py-2.5 rounded-xl bg-brand-darkBg hover:bg-slate-800 text-slate-200 border border-brand-border text-xs font-semibold flex items-center justify-center gap-2 transition-all cursor-pointer">
                            <i class="fa-solid" :class="addressCopied ? 'fa-check text-emerald-400' : 'fa-copy text-teal-400'"></i>
                            <span x-text="addressCopied ? 'Address Copied!' : 'Copy Address'"></span>
                        </button>

                        <!-- Open in Google Maps External -->
                        <a :href="googleMapsPlaceUrl" target="_blank" rel="noopener"
                            class="flex-1 md:flex-initial teal-gradient text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-lg hover:opacity-95 transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            <span>Open in Google Maps</span>
                        </a>
                    </div>
                </div>

                <!-- Directions & Parking Tips -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    <div class="bg-brand-cardBg/60 border border-brand-border/60 rounded-xl p-3 flex items-start gap-2.5">
                        <i class="fa-solid fa-car text-amber-400 mt-0.5 text-sm shrink-0"></i>
                        <div>
                            <span class="font-bold text-white block mb-0.5">Parking Info</span>
                            <span class="text-slate-400 text-[11px] leading-tight block">Ample multi-level &amp; street parking nearby Buckingham Ave.</span>
                        </div>
                    </div>
                    <div class="bg-brand-cardBg/60 border border-brand-border/60 rounded-xl p-3 flex items-start gap-2.5">
                        <i class="fa-solid fa-train text-teal-400 mt-0.5 text-sm shrink-0"></i>
                        <div>
                            <span class="font-bold text-white block mb-0.5">Train &amp; Bus</span>
                            <span class="text-slate-400 text-[11px] leading-tight block">3 min walk from Springvale Railway Station (Pakenham line).</span>
                        </div>
                    </div>
                    <div class="bg-brand-cardBg/60 border border-brand-border/60 rounded-xl p-3 flex items-start gap-2.5">
                        <i class="fa-solid fa-shield-halved text-emerald-400 mt-0.5 text-sm shrink-0"></i>
                        <div>
                            <span class="font-bold text-white block mb-0.5">100% Private</span>
                            <span class="text-slate-400 text-[11px] leading-tight block">Private 1-on-1 consultation suite with complete confidentiality.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer CTA -->
            <div class="px-5 sm:px-7 py-3.5 border-t border-brand-border/80 flex flex-col sm:flex-row items-center justify-between gap-3 bg-brand-darkBg/95">
                <span class="text-xs text-slate-400 text-center sm:text-left">
                    Need an in-person consultation or custom head measurement?
                </span>
                <div class="flex items-center gap-2.5 w-full sm:w-auto">
                    <button type="button" @click="isMapModalOpen = false; isBookingModalOpen = true;"
                        class="w-full sm:w-auto bronze-gradient text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow hover:opacity-95 transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-calendar-check"></i>
                        <span>Book Private Fitting</span>
                    </button>
                    <button type="button" @click="isMapModalOpen = false"
                        class="w-full sm:w-auto bg-brand-inner hover:bg-slate-700 text-slate-300 font-semibold text-xs px-4 py-2.5 rounded-xl border border-brand-border transition-all">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- SMART WELCOME EVENT MODAL (1ST VISIT PRIVATE FITTING + $50 VOUCHER) -->
    <div x-show="isWelcomeModalOpen" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-black/85 backdrop-blur-md overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95">
        
        <div @click.away="closeWelcomeModal()"
            class="bg-gradient-to-b from-[#111A3A] to-[#0A0F24] border border-teal-500/40 rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl relative my-6 text-white overflow-hidden ring-1 ring-teal-400/30">
            
            <!-- Glow background ambient light -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-teal-500/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Close button -->
            <button @click="closeWelcomeModal()"
                class="absolute top-4 right-4 text-slate-400 hover:text-white p-2 rounded-full hover:bg-white/10 transition-colors z-10"
                title="Close">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <!-- Modal Header -->
            <div class="text-center space-y-2 pt-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/20 border border-teal-400/40 text-teal-300 text-xs font-bold uppercase tracking-wider shadow-sm">
                    <i class="fa-solid fa-gift text-amber-300 animate-bounce"></i>
                    <span>First-Time Visitor Special Welcome</span>
                </div>
                <h3 class="font-heading font-extrabold text-2xl sm:text-3xl text-white tracking-tight">
                    100% Confidential <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-sky-300 to-emerald-400">Free Fitting</span> &amp; <span class="text-amber-300">$50 Voucher</span>
                </h3>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed max-w-md mx-auto">
                    Struggling with hair loss? You don't have to face it alone.<br class="hidden sm:inline">
                    Experience custom non-surgical hair replacement in our <strong>100% private 1-on-1 suites</strong> in Springvale, Melbourne.
                </p>
            </div>

            <!-- Benefits List -->
            <div class="space-y-3 bg-brand-inner/70 border border-brand-border/80 rounded-2xl p-4 sm:p-5">
                <!-- Benefit 1 -->
                <div class="flex items-start gap-3.5">
                    <div class="w-9 h-9 rounded-xl bg-teal-500/20 border border-teal-400/40 text-teal-300 flex items-center justify-center font-bold text-sm flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-white flex items-center gap-1.5">
                            <span>1:1 Private Fitting &amp; Scalp Assessment</span>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-black bg-emerald-500/30 text-emerald-300 border border-emerald-500/40">$0 FREE</span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-0.5">Valued at $80 • Discreet private suite, precise head contouring &amp; live try-on</p>
                    </div>
                </div>

                <!-- Benefit 2 -->
                <div class="flex items-start gap-3.5 pt-2 border-t border-brand-border/50">
                    <div class="w-9 h-9 rounded-xl bg-amber-500/20 border border-amber-400/40 text-amber-300 flex items-center justify-center font-bold text-sm flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-ticket"></i>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-white flex items-center gap-1.5">
                            <span>$50 Master Cut-in Styling Voucher</span>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-mono font-bold bg-amber-500/30 text-amber-300 border border-amber-500/40">CODE: FIRST50</span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-0.5">Instant $50 credit applied toward master salon cut-in &amp; bespoke blending in Melbourne</p>
                    </div>
                </div>

                <!-- Benefit 3 -->
                <div class="flex items-start gap-3.5 pt-2 border-t border-brand-border/50">
                    <div class="w-9 h-9 rounded-xl bg-sky-500/20 border border-sky-400/40 text-sky-300 flex items-center justify-center font-bold text-sm flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-tag"></i>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-white">Factory-Direct Fair Pricing (From $650)</div>
                        <p class="text-[11px] text-slate-400 mt-0.5">No retail middleman markup. Direct from our manufacturing facility vs. typical Australian clinics ($1,800+)</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-2.5 pt-1">
                <button @click="claimWelcomeOffer()"
                    class="w-full teal-gradient hover:opacity-95 text-white font-extrabold text-sm uppercase tracking-wider py-3.5 rounded-xl shadow-lg shadow-teal-900/40 transition-all flex items-center justify-center gap-2 group active:scale-98">
                    <i class="fa-solid fa-calendar-check text-base group-hover:scale-110 transition-transform"></i>
                    <span>Book Free Private Fitting ($0)</span>
                </button>
                <a :href="'https://wa.me/61433484882?text=' + encodeURIComponent('Hi David Hair Studio, I saw your First-Visit Welcome Event ($0 Free Private Fitting & $50 Voucher). I would like to make an inquiry.')"
                    target="_blank"
                    @click="closeWelcomeModal(true)"
                    class="w-full bg-brand-inner hover:bg-slate-800 border border-brand-border text-slate-200 font-bold text-xs py-3 rounded-xl transition-all flex items-center justify-center gap-2">
                    <i class="fa-brands fa-whatsapp text-emerald-400 text-sm"></i>
                    <span>Discreet 1-on-1 WhatsApp Inquiry</span>
                </a>
            </div>

            <!-- Footer: Don't show again today & close -->
            <div class="flex items-center justify-between text-[11px] text-slate-400 pt-2 border-t border-brand-border/40">
                <label class="flex items-center gap-2 cursor-pointer select-none hover:text-slate-200">
                    <input type="checkbox" x-model="welcomeDontShowToday" class="rounded border-slate-600 bg-brand-inner text-teal-500 focus:ring-0 cursor-pointer">
                    <span>Don't show again today</span>
                </label>
                <button @click="closeWelcomeModal()" class="text-slate-400 hover:text-white underline transition-colors">
                    Maybe later
                </button>
            </div>
        </div>
    </div>


    <!-- BOOKING SPRINGVALE FITTING MODAL -->
    <div id="booking" x-show="isBookingModalOpen" x-cloak
        class="fixed inset-0 z-[70] flex items-center justify-center p-3 sm:p-4 bg-black/85 backdrop-blur-md overflow-y-auto" x-transition>
        <div @click.away="isBookingModalOpen = false"
            class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-2xl w-full p-5 sm:p-7 space-y-5 shadow-2xl relative my-6 max-h-[92vh] overflow-y-auto">
            <button @click="isBookingModalOpen = false"
                class="absolute top-4 right-4 text-slate-400 hover:text-white p-2">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <!-- SUCCESS STATE -->
            <template x-if="bookingSuccessData">
                <div class="text-center py-6 space-y-4">
                    <div class="w-16 h-16 rounded-full bg-emerald-500/20 border-2 border-emerald-400 text-emerald-400 flex items-center justify-center mx-auto text-3xl shadow-lg">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <h3 class="font-heading font-extrabold text-xl sm:text-2xl text-white">Fitting Appointment Requested!</h3>
                        <p class="text-xs text-brand-textMuted mt-1">Your appointment request has been submitted. Our Springvale specialist will confirm your time via WhatsApp shortly.</p>
                    </div>

                    <div class="bg-brand-inner border border-brand-border rounded-xl p-4 text-left max-w-md mx-auto space-y-2 text-xs">
                        <div class="flex justify-between border-b border-brand-border/60 pb-1.5">
                            <span class="text-brand-textMuted">Booking ID</span>
                            <span class="font-mono text-brand-tealLight font-bold" x-text="'#FIT-' + bookingSuccessData.id"></span>
                        </div>
                        <div class="flex justify-between border-b border-brand-border/60 pb-1.5">
                            <span class="text-brand-textMuted">Date & Time</span>
                            <span class="text-white font-bold" x-text="bookingSuccessData.date + ' @ ' + bookingSuccessData.time"></span>
                        </div>
                        <div class="flex justify-between border-b border-brand-border/60 pb-1.5">
                            <span class="text-brand-textMuted">Service</span>
                            <span class="text-slate-200 font-medium truncate max-w-[200px]" x-text="bookingSuccessData.service"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-brand-textMuted">Studio Location</span>
                            <button type="button" @click="openMapModal()" class="text-slate-200 hover:text-brand-tealLight text-xs flex items-center gap-1.5 group cursor-pointer transition-colors text-right">
                                <i class="fa-solid fa-location-dot text-brand-bronze group-hover:scale-125 transition-transform"></i>
                                <span class="underline decoration-dotted">Shop F16 (Level 1), Buckingham Ave, Springvale</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2.5 justify-center pt-2">
                        <a :href="'https://wa.me/61433484882?text=' + encodeURIComponent('Hi David Hair, I just requested a Springvale fitting appointment #' + bookingSuccessData.id + ' on ' + bookingSuccessData.date + ' at ' + bookingSuccessData.time)" target="_blank"
                           class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs py-3 px-5 rounded-xl shadow flex items-center justify-center gap-2 transition-all">
                            <i class="fa-brands fa-whatsapp text-base"></i> Quick Chat on WhatsApp
                        </a>
                        <button type="button" @click="resetBookingForm(); isBookingModalOpen = false;"
                                class="bg-brand-inner hover:bg-slate-700 text-slate-200 font-semibold text-xs py-3 px-5 rounded-xl transition-all border border-brand-border">
                            Close
                        </button>
                    </div>
                </div>
            </template>

            <!-- FORM STATE -->
            <template x-if="!bookingSuccessData">
                <div>
                    <div class="text-center mb-5">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-brand-tealLight bg-brand-teal/20 px-2.5 py-0.5 rounded-full border border-brand-teal/30">Melbourne In-Person Studio</span>
                        <h3 class="font-heading font-extrabold text-xl sm:text-2xl text-white mt-1">Book Springvale Fitting</h3>
                        <p class="text-xs text-brand-textMuted mt-1">Select your preferred date from the calendar and choose an available time slot (11:00 AM – 5:00 PM).</p>
                    </div>

                    <form @submit.prevent="submitBooking()" class="space-y-4 text-xs">
                        <!-- Service Selection (Option Buttons - Single Choice) -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="block font-bold text-slate-200 text-sm sm:text-base flex items-center gap-2">
                                    <span>Select Service</span>
                                    <span class="text-[10px] font-semibold text-brand-tealLight bg-teal-950/70 border border-brand-teal/30 px-2 py-0.5 rounded-full uppercase tracking-wider">Choose 1</span>
                                </label>
                                <span class="text-[11px] text-slate-400 hidden sm:inline-flex items-center gap-1 font-mono">
                                    <i class="fa-regular fa-clock text-brand-tealLight"></i>
                                    <span x-text="(fittingServices.find(s => s.value === booking.service) || {}).duration || ''"></span>
                                </span>
                            </div>

                            <div role="radiogroup" aria-label="Select Service" class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-2.5">
                                <template x-for="(svc, idx) in fittingServices" :key="svc.value">
                                    <button type="button" 
                                            role="radio"
                                            :aria-checked="booking.service === svc.value"
                                            @click="booking.service = svc.value"
                                            :class="[
                                                booking.service === svc.value 
                                                    ? 'border-brand-teal bg-gradient-to-r from-teal-950/80 to-slate-900/90 text-white shadow-md ring-1 ring-brand-teal/60 scale-[1.01]' 
                                                    : 'border-brand-border/70 bg-brand-darkBg/90 hover:bg-slate-800/80 hover:border-slate-500 text-slate-300',
                                                idx === 4 ? 'sm:col-span-2' : ''
                                            ]"
                                            class="p-2.5 sm:p-3 rounded-xl border text-left transition-all duration-200 flex items-center justify-between gap-3 cursor-pointer group">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors"
                                                 :class="booking.service === svc.value ? 'bg-brand-teal/20 text-brand-tealLight border border-brand-teal/40' : 'bg-slate-800 text-slate-400 group-hover:text-slate-200 border border-slate-700/60'">
                                                <i :class="svc.icon" class="text-xs"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-xs sm:text-sm font-bold truncate leading-tight transition-colors" 
                                                     :class="booking.service === svc.value ? 'text-white' : 'text-slate-200 group-hover:text-white'"
                                                     x-text="svc.label"></div>
                                                <div class="text-[10px] leading-tight mt-1 flex items-center gap-1 font-mono"
                                                     :class="booking.service === svc.value ? 'text-teal-300' : 'text-slate-400'">
                                                    <i class="fa-regular fa-clock text-[9px]"></i>
                                                    <span x-text="svc.duration"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="shrink-0 flex items-center">
                                            <template x-if="booking.service === svc.value">
                                                <span class="w-5 h-5 rounded-full bg-brand-teal/20 text-brand-tealLight border border-brand-teal/60 flex items-center justify-center text-[10px] shadow-sm">
                                                    <i class="fa-solid fa-check"></i>
                                                </span>
                                            </template>
                                            <template x-if="booking.service !== svc.value">
                                                <span class="w-5 h-5 rounded-full border border-slate-700 bg-slate-900/60 group-hover:border-slate-500 transition-colors"></span>
                                            </template>
                                        </div>
                                    </button>
                                </template>
                            </div>
                            <input type="hidden" name="service" :value="booking.service">
                        </div>

                        <!-- 2-COLUMN: CALENDAR ON LEFT, TIME SLOTS ON RIGHT -->
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-5 bg-brand-inner/60 p-3 sm:p-4 rounded-xl border border-brand-border">
                            <!-- CALENDAR (md:col-span-7) -->
                            <div class="md:col-span-7 space-y-2.5">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <button type="button" @click="changeCalendarMonth(-1)" class="w-7 h-7 rounded bg-brand-darkBg border border-brand-border text-slate-300 hover:text-white flex items-center justify-center transition-colors">
                                            <i class="fa-solid fa-chevron-left text-xs"></i>
                                        </button>
                                        <span class="font-bold text-white text-xs sm:text-sm font-heading" x-text="calendarMonthLabel"></span>
                                        <button type="button" @click="changeCalendarMonth(1)" class="w-7 h-7 rounded bg-brand-darkBg border border-brand-border text-slate-300 hover:text-white flex items-center justify-center transition-colors">
                                            <i class="fa-solid fa-chevron-right text-xs"></i>
                                        </button>
                                    </div>
                                    <!-- DOT LEGEND -->
                                    <div class="flex items-center gap-2 text-[10px] text-slate-400">
                                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-400"></span>Available</span>
                                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-400"></span>Filling Fast</span>
                                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-rose-500"></span>Fully Booked</span>
                                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-slate-500"></span>Closed</span>
                                    </div>
                                </div>

                                <!-- Weekdays -->
                                <div class="grid grid-cols-7 gap-1 text-center font-bold text-[10px] text-brand-textMuted py-1 border-b border-brand-border/60">
                                    <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
                                </div>

                                <!-- Calendar Day Grid -->
                                <div class="grid grid-cols-7 gap-1">
                                    <template x-for="(d, idx) in calendarDays" :key="idx">
                                        <div class="h-10 sm:h-11">
                                             <template x-if="d.day">
                                                <button type="button" 
                                                        @click="selectCalendarDate(d.dateStr, d.isPast, d.isClosed)"
                                                        :disabled="d.isPast || d.isClosed"
                                                        :title="d.isClosed ? (d.closedReason || 'Closed') : ''"
                                                        class="w-full h-full rounded-lg border transition-all flex flex-col items-center justify-center relative p-0.5"
                                                        :class="{
                                                            'opacity-25 cursor-not-allowed border-transparent bg-transparent text-slate-600': d.isPast,
                                                            'opacity-40 cursor-not-allowed bg-slate-900/80 border-dashed border-slate-800 text-slate-500': !d.isPast && d.isClosed,
                                                            'border-brand-teal bg-teal-950/80 text-white font-bold shadow-md scale-105': !d.isClosed && booking.date === d.dateStr,
                                                            'border-brand-border/60 bg-brand-darkBg hover:border-slate-500 text-slate-200': !d.isPast && !d.isClosed && booking.date !== d.dateStr
                                                        }">
                                                    <span class="text-xs leading-none" :class="{ 'line-through text-slate-600': d.isClosed }" x-text="d.day"></span>
                                                    
                                                    <!-- Status Dots (green, yellow, red, gray) -->
                                                    <template x-if="d.dot === 'gray'">
                                                        <span class="text-[7.5px] font-mono uppercase text-slate-500 tracking-tighter mt-0.5 leading-none">Off</span>
                                                    </template>
                                                    <template x-if="d.dot === 'green'">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mt-1 shadow-sm" title="Available"></span>
                                                    </template>
                                                    <template x-if="d.dot === 'yellow'">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 mt-1 shadow-sm" title="Filling fast"></span>
                                                    </template>
                                                    <template x-if="d.dot === 'red'">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mt-1 shadow-sm" title="Fully booked"></span>
                                                    </template>
                                                </button>
                                             </template>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- TIME SLOTS (md:col-span-5) -->
                            <div class="md:col-span-5 border-t md:border-t-0 md:border-l border-brand-border pt-3 md:pt-0 md:pl-4 space-y-2.5">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-white text-xs flex items-center gap-1.5">
                                        <i class="fa-regular fa-clock text-brand-tealLight"></i> Available Time
                                    </span>
                                    <span class="text-[10px] text-brand-tealLight font-mono font-bold" x-text="booking.date || 'Select Date'"></span>
                                </div>

                                <template x-if="!booking.date">
                                    <div class="p-6 text-center text-slate-400 text-[11px] bg-brand-darkBg/60 rounded-lg border border-dashed border-brand-border">
                                        <i class="fa-regular fa-calendar-days text-2xl mb-1 text-slate-500 block"></i>
                                        Please select a date on the calendar first.
                                    </div>
                                </template>

                                <template x-if="booking.date && selectedDateIsClosed">
                                    <div class="p-5 text-center bg-slate-900/80 border border-amber-500/30 rounded-xl space-y-2">
                                        <i class="fa-solid fa-calendar-xmark text-2xl text-amber-400 block"></i>
                                        <div class="font-bold text-white text-xs">Salon Closed on This Date</div>
                                        <div class="text-[11px] text-amber-300 font-medium" x-text="selectedDateClosedReason || 'Scheduled regular off-day or holiday.'"></div>
                                        <div class="text-[10px] text-slate-400">Please choose an alternate date from the calendar.</div>
                                    </div>
                                </template>

                                <template x-if="booking.date && !selectedDateIsClosed">
                                    <div class="space-y-1.5">
                                        <div class="text-[10px] text-slate-400">11:00 AM - 5:00 PM (1-hour slots)</div>
                                        <div class="grid grid-cols-2 gap-1.5" x-show="selectedDateSlots && selectedDateSlots.length > 0">
                                            <template x-for="slot in selectedDateSlots" :key="slot.time">
                                                <button type="button" 
                                                        @click="if(slot.available) booking.time = slot.time"
                                                        :disabled="!slot.available"
                                                        class="py-2 px-1 rounded-lg text-xs font-semibold border transition-all text-center"
                                                        :class="{
                                                            'opacity-35 cursor-not-allowed bg-slate-900 border-slate-800 text-slate-600 line-through': !slot.available,
                                                            'teal-gradient text-white font-bold border-transparent shadow': booking.time === slot.time,
                                                            'bg-brand-darkBg text-slate-200 border-brand-border hover:border-brand-teal': slot.available && booking.time !== slot.time
                                                        }">
                                                    <span x-text="slot.time"></span>
                                                    <span x-show="!slot.available" class="block text-[8px] font-normal uppercase text-rose-400">Booked</span>
                                                </button>
                                            </template>
                                        </div>
                                        <div x-show="!selectedDateSlots || selectedDateSlots.length === 0" class="p-4 text-center bg-slate-900/60 border border-slate-800 rounded-lg text-slate-400 text-xs">
                                            <i class="fa-regular fa-clock text-amber-400 text-base mb-1 block"></i>
                                            No time slots available for today. Please select another date.
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Customer Info Form -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                            <div>
                                <label class="block font-bold text-slate-300 mb-1">Your Full Name *</label>
                                <input type="text" x-model="booking.name" required placeholder="e.g. David Chang" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-300 mb-1">WhatsApp / Phone *</label>
                                <input type="tel" x-model="booking.phone" required placeholder="e.g. +61 433 484 882" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal">
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-300 mb-1">Special Requests / Hair Concerns (Optional)</label>
                            <input type="text" x-model="booking.notes" placeholder="e.g. Thin skin 0.03mm consultation, first-time toupee wearer..." class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal text-xs">
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                :disabled="!booking.date || !booking.time || selectedDateIsClosed || isBookingSubmitting"
                                :class="(!booking.date || !booking.time || selectedDateIsClosed || isBookingSubmitting) ? 'opacity-50 cursor-not-allowed bg-slate-700' : 'teal-gradient hover:opacity-95 shadow-lg'"
                                class="w-full py-3.5 rounded-xl text-white font-bold text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-spinner fa-spin" x-show="isBookingSubmitting"></i>
                            <i class="fa-solid fa-calendar-check" x-show="!isBookingSubmitting"></i>
                            <span x-text="isBookingSubmitting ? 'Submitting Appointment...' : (selectedDateIsClosed ? 'Date is Closed for Booking' : (booking.date && booking.time ? `Confirm ${booking.date} @ ${booking.time} Fitting` : 'Please Select Date & Time'))"></span>
                        </button>
                    </form>
                </div>
            </template>
        </div>
    </div>


    <!-- SIGN IN MODAL -->
    <div x-show="isSignInModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-brand-darkBg/95 backdrop-blur-md overflow-y-auto">
        <div class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-md w-full p-6 sm:p-7 space-y-5 shadow-2xl relative" @click.away="isSignInModalOpen = false">
            <button type="button" @click="isSignInModalOpen = false" class="absolute top-4 right-4 text-slate-400 hover:text-white p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <div class="text-center space-y-1.5">
                <div class="w-12 h-12 rounded-xl teal-gradient flex items-center justify-center font-heading font-extrabold text-white text-xl mx-auto shadow-md">
                    DH
                </div>
                <h3 class="font-heading text-xl font-bold text-white tracking-tight">VIP Member Sign In</h3>
                <p class="text-xs text-brand-textMuted">Access your personal membership tier, performance points, and fitting history.</p>
            </div>

            <!-- Not a Member Callout Box (Click to switch to Sign Up) -->
            <button type="button" @click="isSignInModalOpen = false; openSignUpModal();"
                class="w-full p-3.5 rounded-xl bg-gradient-to-r from-teal-500/15 via-emerald-500/15 to-teal-500/15 hover:from-teal-500/25 hover:via-emerald-500/25 hover:to-teal-500/25 border border-teal-400/40 hover:border-teal-300 text-left transition-all shadow-sm group cursor-pointer flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-teal-500/25 border border-teal-400/50 text-teal-300 flex items-center justify-center text-sm flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-user-plus text-base"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white group-hover:text-teal-200 flex items-center gap-1.5">
                            <span>Not a member yet? Create VIP Account</span>
                            <span class="text-[10px] bg-amber-400/20 text-amber-300 border border-amber-400/40 px-1.5 py-0.2 rounded font-bold font-mono">+100 Pts</span>
                        </div>
                        <p class="text-[11px] text-teal-300/90 group-hover:text-teal-200 mt-0.5 font-medium">Click here to register &amp; unlock exclusive VIP benefits &rarr;</p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-xs text-teal-400 group-hover:translate-x-1 transition-transform flex-shrink-0"></i>
            </button>

            <!-- Error Banner -->
            <div x-show="signInForm.error" x-cloak class="p-3 rounded-lg bg-rose-950/80 border border-rose-800 text-rose-300 text-xs font-semibold flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation flex-shrink-0"></i>
                    <span x-text="signInForm.error"></span>
                </div>
                <button type="button" @click="isSignInModalOpen = false; openSignUpModal();" class="text-teal-300 hover:text-teal-200 underline font-bold text-[11px] whitespace-nowrap cursor-pointer">
                    Sign Up &rarr;
                </button>
            </div>

            <form @submit.prevent="memberLogin()" class="space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-300 mb-1">Email Address *</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="email" x-model="signInForm.email" required placeholder="client@example.com" class="w-full bg-brand-darkBg border border-brand-border rounded-lg pl-10 pr-4 py-2.5 text-white font-medium outline-none focus:border-brand-teal min-h-[44px]">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-300 mb-1">Password *</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="password" x-model="signInForm.password" required placeholder="••••••••" class="w-full bg-brand-darkBg border border-brand-border rounded-lg pl-10 pr-4 py-2.5 text-white font-mono outline-none focus:border-brand-teal min-h-[44px]">
                    </div>
                </div>

                <button type="submit" :disabled="signInForm.loading" class="w-full teal-gradient py-3.5 rounded-xl text-white font-bold text-xs uppercase tracking-wider shadow-lg hover:opacity-95 transition-all min-h-[48px] flex items-center justify-center gap-2 disabled:opacity-50">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span x-text="signInForm.loading ? 'Signing In...' : 'Sign In to VIP Account'"></span>
                </button>
            </form>

            <div class="text-center text-xs text-slate-400 border-t border-brand-border/60 pt-4 space-y-2">
                <p class="flex items-center justify-center gap-1 flex-wrap">
                    <span>Don't have an account yet?</span>
                    <button type="button" @click="isSignInModalOpen = false; openSignUpModal();" class="text-teal-400 hover:text-teal-300 font-bold underline cursor-pointer inline-flex items-center gap-1 ml-1">
                        <span>Sign up here (+100 Pts Bonus)</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </p>
                <p class="text-[11px] text-slate-500 font-mono">Demo account: john.smith@example.com / password123</p>
            </div>
        </div>
    </div>

    <!-- SIGN UP MODAL -->
    <div x-show="isSignUpModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-brand-darkBg/95 backdrop-blur-md overflow-y-auto">
        <div class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-lg w-full p-6 sm:p-7 space-y-5 shadow-2xl relative my-6 max-h-[92vh] overflow-y-auto" @click.away="isSignUpModalOpen = false">
            <button type="button" @click="isSignUpModalOpen = false" class="absolute top-4 right-4 text-slate-400 hover:text-white p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <div class="text-center space-y-1.5">
                <div class="w-12 h-12 rounded-xl teal-gradient flex items-center justify-center font-heading font-extrabold text-white text-xl mx-auto shadow-md">
                    DH
                </div>
                <h3 class="font-heading text-xl font-bold text-white tracking-tight">Create VIP Membership</h3>
                <p class="text-xs text-brand-textMuted">Register for bespoke hairpiece care, priority bookings, and loyalty rewards.</p>
            </div>

            <!-- Welcome Gift Banner -->
            <div class="p-3 rounded-xl bg-gradient-to-r from-amber-500/20 via-teal-500/20 to-amber-500/20 border border-amber-400/40 text-amber-200 text-xs flex items-center gap-2.5 shadow-sm">
                <i class="fa-solid fa-gift text-amber-400 text-lg flex-shrink-0"></i>
                <div>
                    <span class="font-bold text-white block">Welcome Gift: 100 Loyalty Points</span>
                    <span class="text-[11px] text-amber-300">Instantly credited upon registration. Redeem for discounts on salon fittings &amp; supplies!</span>
                </div>
            </div>

            <!-- Error Banner -->
            <div x-show="signUpForm.error" x-cloak class="p-3 rounded-lg bg-rose-950/80 border border-rose-800 text-rose-300 text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation flex-shrink-0"></i>
                <span x-text="signUpForm.error"></span>
            </div>

            <form @submit.prevent="memberRegister()" class="space-y-4 text-xs">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">Full Name *</label>
                        <input type="text" x-model="signUpForm.full_name" required placeholder="e.g. John Smith" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2.5 text-white font-medium outline-none focus:border-brand-teal min-h-[44px]">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">Phone / WhatsApp *</label>
                        <input type="text" x-model="signUpForm.phone" required placeholder="+61 412 345 678" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2.5 text-white font-mono outline-none focus:border-brand-teal min-h-[44px]">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-300 mb-1">Email Address (Login ID) *</label>
                    <input type="email" x-model="signUpForm.email" required placeholder="your.name@example.com" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2.5 text-white font-mono outline-none focus:border-brand-teal min-h-[44px]">
                </div>

                <div>
                    <label class="block font-bold text-slate-300 mb-1">Password * (Min 6 characters)</label>
                    <input type="password" x-model="signUpForm.password" required minlength="6" placeholder="••••••••" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2.5 text-white font-mono outline-none focus:border-brand-teal min-h-[44px]">
                </div>

                <div>
                    <label class="block font-bold text-slate-300 mb-1">Hair System Preference</label>
                    <select x-model="signUpForm.hair_system_type" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2.5 text-white outline-none focus:border-brand-teal min-h-[44px]">
                        <option value="Full Skin System (0.03mm)">Full Skin System (0.03mm) - Seamless &amp; Knotless</option>
                        <option value="Full Swiss Lace">Full Swiss Lace - Maximum Breathability</option>
                        <option value="Lace Front + PU Perimeter">Lace Front + PU Perimeter - Ideal Durability</option>
                        <option value="Silk Top System">Silk Top System - Zero Visible Knots Scalp</option>
                        <option value="CNC Premier Virgin Hair">CNC Premier Virgin Hair - Medical 3D Cranial</option>
                        <option value="Women Silk Topper">Women Silk Topper - Crown Thinning Coverage</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-300 mb-1">Friend's Referral Code (Optional)</label>
                    <input type="text" x-model="signUpForm.referral_code" placeholder="e.g. DH-REF-JS42 (Bonus +50 Pts for your friend)" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2.5 text-white font-mono uppercase outline-none focus:border-brand-teal min-h-[44px]">
                </div>

                <button type="submit" :disabled="signUpForm.loading" class="w-full teal-gradient py-3.5 rounded-xl text-white font-bold text-xs uppercase tracking-wider shadow-lg hover:opacity-95 transition-all min-h-[48px] flex items-center justify-center gap-2 disabled:opacity-50">
                    <i class="fa-solid fa-user-plus"></i>
                    <span x-text="signUpForm.loading ? 'Creating Membership...' : 'Join David Hair VIP (+100 Pts)'"></span>
                </button>
            </form>

            <div class="text-center text-xs text-slate-400 border-t border-brand-border/60 pt-4">
                Already registered? 
                <button type="button" @click="isSignUpModalOpen = false; openSignInModal();" class="text-teal-400 hover:text-teal-300 font-bold underline ml-1">
                    Sign In to Existing Account
                </button>
            </div>
        </div>
    </div>

    <!-- MEMBER PROFILE & VIP DASHBOARD MODAL -->
    <div x-show="isMemberProfileModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-brand-darkBg/95 backdrop-blur-md overflow-y-auto">
        <div class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-2xl w-full p-6 sm:p-7 space-y-6 shadow-2xl relative my-6 max-h-[92vh] overflow-y-auto" @click.away="isMemberProfileModalOpen = false">
            <button type="button" @click="isMemberProfileModalOpen = false" class="absolute top-4 right-4 text-slate-400 hover:text-white p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <!-- Member Header & Tier Banner -->
            <template x-if="currentMember">
                <div class="space-y-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-brand-border/80 pb-5">
                        <div class="flex items-center gap-3.5">
                            <div class="w-14 h-14 rounded-2xl teal-gradient flex items-center justify-center font-heading font-extrabold text-white text-2xl shadow-lg" x-text="currentMember.full_name ? currentMember.full_name.charAt(0).toUpperCase() : 'M'"></div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-heading text-xl font-bold text-white tracking-tight" x-text="currentMember.full_name"></h3>
                                    <span class="text-xs px-2.5 py-0.5 rounded-full font-bold border" :class="{
                                        'bg-amber-950/80 text-amber-300 border-amber-700': currentMember.tier === 'Bronze',
                                        'bg-slate-800 text-slate-200 border-slate-600': currentMember.tier === 'Silver',
                                        'bg-yellow-950/80 text-yellow-300 border-yellow-600': currentMember.tier === 'Gold',
                                        'bg-purple-950/90 text-purple-200 border-purple-500 shadow-purple-500/30': currentMember.tier === 'VIP Partner'
                                    }">
                                        <i class="fa-solid mr-1" :class="{
                                            'fa-shield-halved': currentMember.tier === 'Bronze',
                                            'fa-star': currentMember.tier === 'Silver',
                                            'fa-medal': currentMember.tier === 'Gold',
                                            'fa-crown': currentMember.tier === 'VIP Partner'
                                        }"></i>
                                        <span x-text="currentMember.tier"></span>
                                    </span>
                                </div>
                                <div class="text-xs text-slate-400 font-mono mt-0.5 flex items-center gap-2">
                                    <span class="text-brand-tealLight font-bold" x-text="currentMember.member_code"></span>
                                    <span>&bull;</span>
                                    <span x-text="currentMember.email"></span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <button type="button" @click="isMemberProfileModalOpen = false; isBookingModalOpen = true;" class="flex-1 sm:flex-initial px-4 py-2 rounded-xl teal-gradient text-white text-xs font-bold shadow-md hover:opacity-95 transition-all flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-calendar-check"></i>
                                <span>Book VIP Fitting</span>
                            </button>
                            <button type="button" @click="memberLogout()" class="px-3 py-2 rounded-xl bg-rose-950/70 hover:bg-rose-900 border border-rose-800 text-rose-300 text-xs font-semibold transition-colors" title="Sign Out">
                                <i class="fa-solid fa-power-off"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Performance Stats Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <div class="bg-brand-darkBg/90 border border-brand-border rounded-xl p-4 text-center">
                            <div class="text-[11px] uppercase tracking-wider text-slate-400 font-bold">Loyalty Points Balance</div>
                            <div class="text-2xl font-extrabold text-sky-300 font-mono mt-1">
                                <span x-text="Number(currentMember.points || 0).toLocaleString()"></span>
                                <span class="text-xs font-semibold text-slate-400">Pts</span>
                            </div>
                            <p class="text-[10px] text-sky-400/80 mt-1">100 pts = $10 discount voucher</p>
                        </div>

                        <div class="bg-brand-darkBg/90 border border-brand-border rounded-xl p-4 text-center">
                            <div class="text-[11px] uppercase tracking-wider text-slate-400 font-bold">Total Performance Spent</div>
                            <div class="text-2xl font-extrabold text-brand-tealLight font-mono mt-1">
                                $<span x-text="Number(currentMember.total_spent || 0).toLocaleString('en-AU', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1">Factory orders &amp; salon fittings</p>
                        </div>

                        <div class="bg-brand-darkBg/90 border border-brand-border rounded-xl p-4 text-center">
                            <div class="text-[11px] uppercase tracking-wider text-slate-400 font-bold">Salon Visits Tracked</div>
                            <div class="text-2xl font-extrabold text-white font-mono mt-1">
                                <span x-text="currentMember.visit_count || 0"></span>
                                <span class="text-xs font-semibold text-slate-400">Visits</span>
                            </div>
                            <p class="text-[10px] text-emerald-400 mt-1">Springvale private suite</p>
                        </div>
                    </div>

                    <!-- Tier Threshold Progress -->
                    <div class="bg-brand-inner/70 border border-brand-border rounded-xl p-4 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-white flex items-center gap-1.5">
                                <i class="fa-solid fa-stairs text-brand-tealLight"></i>
                                <span>Membership Status Progression</span>
                            </span>
                            <span class="text-brand-tealLight font-mono font-bold" x-text="getTierNextThresholdText()"></span>
                        </div>
                        <div class="w-full bg-brand-darkBg rounded-full h-2.5 overflow-hidden border border-brand-border">
                            <div class="teal-gradient h-full transition-all duration-500 rounded-full" :style="'width: ' + getTierProgressPercent() + '%'"></div>
                        </div>
                        <div class="flex justify-between text-[10px] text-slate-400 font-mono pt-1">
                            <span>Bronze ($0)</span>
                            <span>Silver ($500)</span>
                            <span>Gold ($1,500)</span>
                            <span>VIP Partner ($3,000+)</span>
                        </div>
                    </div>

                    <!-- Personal Referral Box -->
                    <div class="p-4 rounded-xl bg-gradient-to-r from-purple-950/40 via-brand-darkBg to-purple-950/40 border border-purple-500/40 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-user-group text-purple-400"></i>
                                <span class="text-xs font-bold text-white">Your Personal Referral Reward Code</span>
                            </div>
                            <span class="text-[10px] text-purple-300 font-bold font-mono" x-text="(currentMember.referral_count || 0) + ' Friends Referred'"></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-brand-inner border border-purple-500/50 rounded-lg px-3 py-2 text-purple-200 font-mono font-bold text-sm tracking-wider" x-text="currentMember.referral_code || 'DH-REF-VIP'"></div>
                            <button type="button" @click="copyReferralCode()" class="px-3.5 py-2 rounded-lg bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow transition-all flex items-center gap-1.5">
                                <i class="fa-solid" :class="copiedReferral ? 'fa-check' : 'fa-copy'"></i>
                                <span x-text="copiedReferral ? 'Copied!' : 'Copy Code'"></span>
                            </button>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">
                            Share this code with friends. When they register, they receive <span class="text-white font-bold">100 Welcome Points</span>, and you earn <span class="text-amber-300 font-bold">+50 Bonus Points</span> automatically!
                        </p>
                    </div>

                    <!-- Activity History -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center gap-1.5">
                                <i class="fa-solid fa-clock-rotate-left text-brand-tealLight"></i>
                                <span>Recent Performance &amp; Activity History</span>
                            </h4>
                            <button type="button" @click="loadMemberRecords()" class="text-[11px] text-brand-tealLight hover:underline font-bold flex items-center gap-1">
                                <i class="fa-solid fa-rotate text-[10px]" :class="{ 'fa-spin': isLoadingMemberRecords }"></i>
                                <span>Refresh</span>
                            </button>
                        </div>

                        <div class="border border-brand-border rounded-xl overflow-hidden max-h-56 overflow-y-auto">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-brand-inner/90 text-brand-textMuted uppercase tracking-wider text-[10px] border-b border-brand-border sticky top-0">
                                    <tr>
                                        <th class="py-2.5 px-3">Date</th>
                                        <th class="py-2.5 px-3">Type</th>
                                        <th class="py-2.5 px-3">Description</th>
                                        <th class="py-2.5 px-3 text-right">Amount</th>
                                        <th class="py-2.5 px-3 text-right">Points</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-brand-border/60">
                                    <template x-if="memberRecords.length === 0">
                                        <tr>
                                            <td colspan="5" class="text-center py-6 text-slate-400">
                                                <span>No past transactions recorded yet.</span>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-for="r in memberRecords" :key="r.id">
                                        <tr class="hover:bg-brand-inner/30">
                                            <td class="py-2 px-3 font-mono text-[10px] text-slate-400" x-text="r.created_at ? r.created_at.substring(0, 10) : '-'"></td>
                                            <td class="py-2 px-3">
                                                <span class="text-[9px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded"
                                                      :class="{
                                                          'bg-emerald-950 text-emerald-300 border border-emerald-800': r.type === 'purchase',
                                                          'bg-purple-950 text-purple-300 border border-purple-800': r.type === 'service',
                                                          'bg-sky-950 text-sky-300 border border-sky-800': r.type === 'points_add',
                                                          'bg-rose-950 text-rose-300 border border-rose-800': r.type === 'points_redeem',
                                                          'bg-amber-950 text-amber-300 border border-amber-800': r.type === 'referral'
                                                      }" x-text="r.type"></span>
                                            </td>
                                            <td class="py-2 px-3 text-slate-200" x-text="r.title"></td>
                                            <td class="py-2 px-3 text-right font-mono font-bold" :class="Number(r.amount) > 0 ? 'text-brand-tealLight' : 'text-slate-400'" x-text="Number(r.amount) > 0 ? '$' + Number(r.amount).toFixed(2) : '-'"></td>
                                            <td class="py-2 px-3 text-right font-mono font-bold" :class="Number(r.points_delta) >= 0 ? 'text-emerald-400' : 'text-rose-400'" x-text="(Number(r.points_delta) >= 0 ? '+' : '') + Number(r.points_delta || 0).toLocaleString() + ' Pts'"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>


    <!-- 8. INTERACTIVE HAIR SYSTEM PRICE CALCULATOR MODAL -->
    <div x-show="isCalculatorModalOpen" x-cloak
        @keydown.escape.window="isCalculatorModalOpen = false"
        class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4 md:p-6 bg-black/85 backdrop-blur-md overflow-hidden"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        <div @click.away="isCalculatorModalOpen = false"
            class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-6xl w-full h-[94vh] flex flex-col shadow-2xl relative overflow-hidden text-left my-auto">
            
            <!-- Modal Top Header Bar -->
            <div class="px-4 sm:px-6 py-3.5 bg-brand-darkBg/95 border-b border-brand-border flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl teal-gradient flex items-center justify-center text-white text-base shadow-sm flex-shrink-0">
                        <i class="fa-solid fa-calculator"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-heading font-extrabold text-sm sm:text-base text-white tracking-tight">
                                Hair System Quote &amp; Cost Calculator
                            </h3>
                            <span class="hidden sm:inline-block text-[10px] bg-teal-500/20 text-teal-300 border border-teal-500/40 px-2 py-0.5 rounded-full font-mono font-bold">Live Estimate (AUD)</span>
                        </div>
                        <p class="text-[11px] text-brand-textMuted hidden xs:block">
                            Direct interactive price estimator for Men's Toupee &amp; Women's Hair Topper
                        </p>
                    </div>
                </div>

                <!-- Header Actions -->
                <div class="flex items-center gap-2">
                    <a :href="calculatorUrl" href="calculator.html" target="_blank"
                        class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-brand-inner hover:bg-slate-700 border border-brand-border text-slate-300 hover:text-white text-xs font-semibold transition-colors"
                        title="Open in Full Tab">
                        <i class="fa-solid fa-arrow-up-right-from-square text-[11px] text-brand-tealLight"></i>
                        <span>Full Page</span>
                    </a>
                    <button type="button" @click="isCalculatorModalOpen = false"
                        class="w-9 h-9 rounded-lg bg-brand-inner hover:bg-rose-950/80 border border-brand-border hover:border-rose-800 text-slate-400 hover:text-rose-200 flex items-center justify-center transition-colors cursor-pointer"
                        title="Close Calculator">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Content Iframe -->
            <div class="flex-1 w-full h-full bg-[#f8fafc] relative overflow-hidden">
                <template x-if="isCalculatorModalOpen">
                <iframe :src="isCalculatorModalOpen ? calculatorUrl : ''"
                    class="w-full h-full border-0 block"
                    title="David Hair System Quote &amp; Cost Calculator"
                    loading="lazy">
                </iframe>
                </template>
            </div>
        </div>
    </div>

    <!-- 8.5. INTERACTIVE HAIR SYSTEM & TOPPER DIAGNOSTIC MODAL (diag.html) -->
    <div x-show="isDiagModalOpen" x-cloak
        @keydown.escape.window="isDiagModalOpen = false"
        @close-diag-modal.window="isDiagModalOpen = false"
        class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4 md:p-6 bg-black/85 backdrop-blur-md overflow-hidden"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        <div @click.away="isDiagModalOpen = false"
            class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-5xl w-full h-[94vh] flex flex-col shadow-2xl relative overflow-hidden text-left my-auto">
            
            <!-- Modal Top Header Bar -->
            <div class="px-4 sm:px-6 py-3.5 bg-brand-darkBg/95 border-b border-brand-border flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-600 flex items-center justify-center text-white text-base shadow-sm flex-shrink-0">
                        <i class="fa-solid fa-stethoscope"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-heading font-extrabold text-sm sm:text-base text-white tracking-tight">
                                Hair System &amp; Topper Diagnostic Assessment
                            </h3>
                            <span class="hidden sm:inline-block text-[10px] bg-teal-500/20 text-teal-300 border border-teal-500/40 px-2 py-0.5 rounded-full font-mono font-bold">5-Step Clinical Tool</span>
                        </div>
                        <p class="text-[11px] text-brand-textMuted hidden xs:block">
                            Male Norwood Stage drawings &amp; Women's specialized clinical hair loss conditions
                        </p>
                    </div>
                </div>

                <!-- Header Actions -->
                <div class="flex items-center gap-2">
                    <a :href="diagUrl" href="diag.html" target="_blank"
                        class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-brand-inner hover:bg-slate-700 border border-brand-border text-slate-300 hover:text-white text-xs font-semibold transition-colors"
                        title="Open in Full Tab">
                        <i class="fa-solid fa-arrow-up-right-from-square text-[11px] text-brand-tealLight"></i>
                        <span>Full Page</span>
                    </a>
                    <button type="button" @click="isDiagModalOpen = false"
                        class="w-9 h-9 rounded-lg bg-brand-inner hover:bg-rose-950/80 border border-brand-border hover:border-rose-800 text-slate-400 hover:text-rose-200 flex items-center justify-center transition-colors cursor-pointer"
                        title="Close Diagnostic Assessment">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Content Iframe -->
            <div class="flex-1 w-full h-full bg-[#f8fafc] relative overflow-hidden">
                <template x-if="isDiagModalOpen">
                <iframe :src="isDiagModalOpen ? diagUrl : ''"
                    class="w-full h-full border-0 block"
                    title="Hair System &amp; Topper Diagnostic Assessment"
                    loading="lazy">
                </iframe>
                </template>
            </div>
        </div>
    </div>

    <!-- 8.6. FRONTAL FIBROSING ALOPECIA (FFA) STAGE ASSESSMENT MODAL (ffa_interactive_questionnaire.html) -->
    <div x-show="isFfaModalOpen" x-cloak
        @keydown.escape.window="isFfaModalOpen = false"
        @open-ffa-modal.window="openFfaModal()"
        @close-ffa-modal.window="isFfaModalOpen = false"
        class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4 md:p-6 bg-black/85 backdrop-blur-md overflow-hidden"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        <div @click.away="isFfaModalOpen = false"
            class="bg-brand-cardBg border border-teal-500/40 rounded-2xl max-w-5xl w-full h-[94vh] flex flex-col shadow-2xl relative overflow-hidden text-left my-auto">
            
            <!-- Modal Top Header Bar -->
            <div class="px-4 sm:px-6 py-3.5 bg-brand-darkBg/95 border-b border-brand-border flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-teal-500 to-sky-600 flex items-center justify-center text-white text-base shadow-sm flex-shrink-0">
                        <i class="fa-solid fa-head-side-mask"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-heading font-extrabold text-sm sm:text-base text-white tracking-tight">
                                FFA Clinical Screening &amp; Self-Assessment Tool
                            </h3>
                            <span class="hidden sm:inline-block text-[10px] bg-teal-500/20 text-teal-300 border border-teal-400/40 px-2 py-0.5 rounded-full font-mono font-bold">FFA Stage Evaluation</span>
                        </div>
                        <p class="text-[11px] text-amber-300/80 hidden xs:block">
                            Informational Self-Reference Only &bull; Please consult a medical physician/dermatologist for diagnosis &amp; care
                        </p>
                    </div>
                </div>

                <!-- Header Actions -->
                <div class="flex items-center gap-2">
                    <a :href="ffaModalUrl" href="ffa_interactive_questionnaire.html" target="_blank"
                        class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-brand-inner hover:bg-slate-700 border border-brand-border text-slate-300 hover:text-white text-xs font-semibold transition-colors"
                        title="Open in Full Tab">
                        <i class="fa-solid fa-arrow-up-right-from-square text-[11px] text-brand-tealLight"></i>
                        <span>Full Page</span>
                    </a>
                    <button type="button" @click="isFfaModalOpen = false"
                        class="w-9 h-9 rounded-lg bg-brand-inner hover:bg-rose-950/80 border border-brand-border hover:border-rose-800 text-slate-400 hover:text-rose-200 flex items-center justify-center transition-colors cursor-pointer"
                        title="Close FFA Assessment">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Content Iframe -->
            <div class="flex-1 w-full h-full bg-[#f8fafc] relative overflow-hidden">
                <template x-if="isFfaModalOpen">
                <iframe :src="isFfaModalOpen ? ffaModalUrl : ''"
                    class="w-full h-full border-0 block"
                    title="FFA Clinical Screening &amp; Self-Assessment Tool"
                    loading="lazy">
                </iframe>
                </template>
            </div>
        </div>
    </div>

    <!-- 8.7. SELF-ASSESSMENT & STYLE DIAGNOSTIC TOOL MODAL (comparison.html) -->
    <div x-show="isComparisonModalOpen" x-cloak
        @keydown.escape.window="isComparisonModalOpen = false"
        @open-comparison-modal.window="openComparisonModal()"
        @close-comparison-modal.window="isComparisonModalOpen = false"
        class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4 md:p-6 bg-black/85 backdrop-blur-md overflow-hidden"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        <div @click.away="isComparisonModalOpen = false"
            class="bg-brand-cardBg border border-teal-500/40 rounded-2xl max-w-6xl w-full h-[94vh] flex flex-col shadow-2xl relative overflow-hidden text-left my-auto">
            
            <!-- Modal Top Header Bar -->
            <div class="px-4 sm:px-6 py-3.5 bg-brand-darkBg/95 border-b border-brand-border flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 via-sky-500 to-teal-500 flex items-center justify-center text-white text-base shadow-sm flex-shrink-0">
                        <i class="fa-solid fa-scale-balanced"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-heading font-extrabold text-sm sm:text-base text-white tracking-tight">
                                Self-Assessment &amp; Style Diagnostic Tool
                            </h3>
                            <span class="hidden sm:inline-block text-[10px] bg-teal-500/20 text-teal-300 border border-teal-400/40 px-2 py-0.5 rounded-full font-mono font-bold">Men's Toupees vs Women's Toppers</span>
                        </div>
                        <p class="text-[11px] text-slate-300 hidden xs:block">
                            Architectural Comparison Guide &bull; Cap Designs, Breathability, Durability &amp; Ownership Costs
                        </p>
                    </div>
                </div>

                <!-- Header Actions -->
                <div class="flex items-center gap-2">
                    <a :href="comparisonUrl" href="comparison.html" target="_blank"
                        class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-brand-inner hover:bg-slate-700 border border-brand-border text-slate-300 hover:text-white text-xs font-semibold transition-colors"
                        title="Open in Full Tab">
                        <i class="fa-solid fa-arrow-up-right-from-square text-[11px] text-brand-tealLight"></i>
                        <span>Full Page</span>
                    </a>
                    <button type="button" @click="isComparisonModalOpen = false"
                        class="w-9 h-9 rounded-lg bg-brand-inner hover:bg-rose-950/80 border border-brand-border hover:border-rose-800 text-slate-400 hover:text-rose-200 flex items-center justify-center transition-colors cursor-pointer"
                        title="Close Diagnostic Tool">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Content Iframe -->
            <div class="flex-1 w-full h-full bg-[#f8fafc] relative overflow-hidden">
                <template x-if="isComparisonModalOpen">
                <iframe :src="isComparisonModalOpen ? comparisonUrl : ''"
                    class="w-full h-full border-0 block"
                    title="Self-Assessment &amp; Style Diagnostic Tool"
                    loading="lazy">
                </iframe>
                </template>
            </div>
        </div>
    </div>

    <!-- 8.8. INTERACTIVE BEFORE & AFTER GALLERY MODAL (gallery.html) -->
    <div x-show="isGalleryModalOpen" x-cloak
        @keydown.escape.window="isGalleryModalOpen = false"
        @close-gallery-modal.window="isGalleryModalOpen = false"
        class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4 md:p-6 bg-black/85 backdrop-blur-md overflow-hidden"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        <div @click.away="isGalleryModalOpen = false"
            class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-6xl w-full h-[94vh] flex flex-col shadow-2xl relative overflow-hidden text-left my-auto">
            
            <!-- Modal Top Header Bar -->
            <div class="px-4 sm:px-6 py-3.5 bg-brand-darkBg/95 border-b border-brand-border flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-teal-500 to-sky-400 flex items-center justify-center text-slate-950 font-black shadow-sm flex-shrink-0">
                        <i class="fa-solid fa-arrows-split-up-and-left text-sm"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-heading font-extrabold text-sm sm:text-base text-white tracking-tight">
                                David Hair Before &amp; After Transformation Gallery
                            </h3>
                            <span class="hidden sm:inline-block text-[10px] bg-amber-500/20 text-amber-300 border border-amber-500/40 px-2 py-0.5 rounded-full font-mono font-bold">Interactive Modal</span>
                        </div>
                        <p class="text-[11px] text-brand-textMuted hidden xs:block">
                            남성 &amp; 여성 맞춤 투페, 가발, 톱퍼 시공 전후 비교 및 4컷 각도별 상세 뷰어
                        </p>
                    </div>
                </div>

                <!-- Header Actions -->
                <div class="flex items-center gap-2">
                    <a :href="galleryUrl" href="gallery.html" target="_blank"
                        class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-brand-inner hover:bg-slate-700 border border-brand-border text-slate-300 hover:text-white text-xs font-semibold transition-colors"
                        title="새 탭으로 전체화면 열기">
                        <i class="fa-solid fa-arrow-up-right-from-square text-[11px] text-brand-tealLight"></i>
                        <span>Full Page</span>
                    </a>
                    <button type="button" @click="isGalleryModalOpen = false"
                        class="w-9 h-9 rounded-lg bg-brand-inner hover:bg-rose-950/80 border border-brand-border hover:border-rose-800 text-slate-400 hover:text-rose-200 flex items-center justify-center transition-colors cursor-pointer"
                        title="Close Gallery">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Content Iframe -->
            <div class="flex-1 w-full h-full bg-brand-darkBg relative overflow-hidden">
                <template x-if="isGalleryModalOpen">
                <iframe :src="isGalleryModalOpen ? galleryUrl : ''"
                    class="w-full h-full border-0 block"
                    title="David Hair Before &amp; After Transformation Gallery"
                    loading="lazy">
                </iframe>
                </template>
            </div>
        </div>
    </div>
