    <!-- GLOBAL FOOTER -->
    <footer class="bg-[#060B18] border-t border-brand-border/80 pt-12 sm:pt-16 pb-8 text-xs text-brand-textMuted">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10 pb-10 sm:pb-12 border-b border-brand-border/60">

                <!-- Col 1: Brand & Socials -->
                <div class="space-y-4">
                    <a href="/" class="flex items-center gap-3 group w-fit">
                        <div class="w-8 h-8 rounded-lg teal-gradient flex items-center justify-center font-heading font-extrabold text-white text-base shadow-sm">
                            DH
                        </div>
                        <span class="font-heading font-extrabold text-lg text-white tracking-tight">DAVID HAIR</span>
                    </a>
                    <p class="text-xs leading-relaxed text-slate-400">
                        Bespoke hand-tied human hairpieces, medical wigs &amp; silk top toppers direct from our factory, custom fitted
                        and maintained at our Springvale, Melbourne flagship salon.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <a href="/" aria-label="Instagram"
                            class="w-9 h-9 rounded-lg bg-brand-cardBg border border-brand-border flex items-center justify-center text-slate-300 hover:text-brand-tealLight hover:border-brand-teal transition-colors">
                            <i class="fa-brands fa-instagram text-base"></i>
                        </a>
                        <a href="/" aria-label="Facebook"
                            class="w-9 h-9 rounded-lg bg-brand-cardBg border border-brand-border flex items-center justify-center text-slate-300 hover:text-brand-tealLight hover:border-brand-teal transition-colors">
                            <i class="fa-brands fa-facebook-f text-base"></i>
                        </a>
                        <a href="/" aria-label="YouTube"
                            class="w-9 h-9 rounded-lg bg-brand-cardBg border border-brand-border flex items-center justify-center text-slate-300 hover:text-brand-tealLight hover:border-brand-teal transition-colors">
                            <i class="fa-brands fa-youtube text-base"></i>
                        </a>
                        <a href="https://wa.me/61433484882" target="_blank" rel="noopener" aria-label="WhatsApp"
                            class="w-9 h-9 rounded-lg bg-brand-cardBg border border-brand-border flex items-center justify-center text-slate-300 hover:text-emerald-400 hover:border-emerald-500 transition-colors">
                            <i class="fa-brands fa-whatsapp text-base"></i>
                        </a>
                    </div>
                </div>

                <!-- Col 2: Quick Navigation -->
                <div class="space-y-3">
                    <h4 class="font-heading font-bold text-xs sm:text-sm text-white uppercase tracking-wider">Navigation</h4>
                    <ul class="space-y-2 text-slate-300 font-medium">
                        <li><button type="button" @click="isCalculatorModalOpen = true"
                                class="hover:text-brand-tealLight transition-colors inline-block py-0.5 cursor-pointer text-left">Custom Quote Calculator</button></li>
                        <li><a href="/solutions" class="hover:text-brand-tealLight transition-colors inline-block py-0.5">All Solutions (11)</a></li>
                        <li><a href="/medical-wigs" class="hover:text-brand-tealLight transition-colors inline-block py-0.5">Medical Wigs (Rebate)</a></li>
                        <li><a href="/ffa" class="hover:text-brand-tealLight transition-colors inline-block py-0.5">FFA Frontal Fringe</a></li>
                        <li><a href="/salon" class="hover:text-brand-tealLight transition-colors inline-block py-0.5">Springvale Salon</a></li>
                        <li><a href="/stock" class="hover:text-brand-tealLight transition-colors inline-block py-0.5">In-Stock Inventory</a></li>
                        <li><a href="/transformations" class="hover:text-brand-tealLight transition-colors inline-block py-0.5">Before &amp; After Gallery</a></li>
                        <li><a href="/factory-b2b" class="hover:text-brand-tealLight transition-colors inline-block py-0.5">Factory &amp; B2B Wholesale</a></li>
                        <li><a href="/faq" class="hover:text-brand-tealLight transition-colors inline-block py-0.5">FAQ</a></li>
                    </ul>
                </div>

                <!-- Col 3: Melbourne Salon Info -->
                <div class="space-y-3">
                    <h4 class="font-heading font-bold text-xs sm:text-sm text-white uppercase tracking-wider">Springvale Salon</h4>
                    <ul class="space-y-2 text-slate-300">
                        <li class="flex items-start gap-2">
                            <button type="button" @click="openMapModal()" class="flex items-start gap-2 text-left hover:text-brand-tealLight transition-colors group cursor-pointer">
                                <i class="fa-solid fa-location-dot text-brand-bronze mt-1 flex-shrink-0 group-hover:scale-125 group-hover:text-brand-tealLight transition-all"></i>
                                <span class="hover:underline">Shop F16 (Level 1), Buckingham Ave, Springvale, VIC 3171, Melbourne Australia</span>
                            </button>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-phone text-brand-tealLight flex-shrink-0"></i>
                            <a href="tel:+61433484882" class="hover:text-brand-tealLight transition-colors">+61 433 484 882</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-envelope text-brand-tealLight flex-shrink-0"></i>
                            <span>contact@davidhairshop.com</span>
                        </li>
                        <li class="flex items-start gap-2 pt-1 text-slate-400">
                            <i class="fa-solid fa-clock text-slate-400 mt-0.5 flex-shrink-0"></i>
                            <span>Mon – Sun: 10:30 AM – 5:00 PM<br>(<strong class="text-amber-400 font-bold">Wed Closed</strong> &bull; Appointment Required)</span>
                        </li>
                        <li class="pt-2 border-t border-brand-border/60">
                            <div class="flex items-center gap-2 font-bold text-white text-xs">
                                <i class="fa-solid fa-store text-amber-400 flex-shrink-0"></i>
                                <span>Dandenong Market</span>
                            </div>
                            <div class="flex items-center gap-2 pt-1 text-slate-300 text-xs">
                                <i class="fa-solid fa-clock text-teal-400 flex-shrink-0"></i>
                                <span>Tue, Fri, Sat, Sun (9AM – 3PM)</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Col 4: Guarantees & Support -->
                <div class="space-y-3">
                    <h4 class="font-heading font-bold text-xs sm:text-sm text-white uppercase tracking-wider">Quality &amp; Support</h4>
                    <ul class="space-y-2 text-slate-300">
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-shield-halved text-emerald-400 flex-shrink-0"></i>
                            <span>100% Virgin Hair Quality Guarantee</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-plane text-brand-tealLight flex-shrink-0"></i>
                            <span>Discreet Express Worldwide Freight</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-screwdriver-wrench text-brand-bronze flex-shrink-0"></i>
                            <span>Re-bonding &amp; Maintenance Guide</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-boxes-stacked text-brand-tealLight flex-shrink-0"></i>
                            <a href="/stock_manager.php" target="_blank"
                                class="hover:text-brand-tealLight transition-colors underline font-bold">Stock Admin Manager (CRUD)</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-database text-sky-400 flex-shrink-0"></i>
                            <a href="/db_test.php" class="hover:text-brand-tealLight transition-colors underline">System Status (PDO/MySQL)</a>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Bottom Sub-Footer -->
            <div class="pt-6 sm:pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-[11px] text-slate-400 text-center md:text-left">
                <div>
                    &copy; 2026 David Hair Systems Pty Ltd. All rights reserved. &bull; Melbourne, Victoria Australia
                </div>
                <div class="flex items-center gap-3 sm:gap-4 text-base sm:text-lg text-slate-300">
                    <i class="fa-brands fa-cc-visa hover:text-white transition-colors" title="Visa"></i>
                    <i class="fa-brands fa-cc-mastercard hover:text-white transition-colors" title="Mastercard"></i>
                    <i class="fa-brands fa-cc-paypal hover:text-white transition-colors" title="PayPal"></i>
                    <i class="fa-brands fa-apple-pay hover:text-white transition-colors" title="Apple Pay"></i>
                    <span class="text-[10px] font-bold font-mono bg-brand-cardBg px-2 py-0.5 rounded border border-brand-border">AFTERPAY</span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>

