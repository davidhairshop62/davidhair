const fs = require('fs');

let html = fs.readFileSync('index.html', 'utf8');

// 1. Update Desktop Navigation
const desktopNavStart = '<nav\r\n                class="hidden lg:flex items-center gap-5 xl:gap-7 text-xs xl:text-sm font-semibold text-slate-300 uppercase tracking-wider">';
const desktopNavEnd = '</nav>';

// Look for desktop nav with regex to handle LF or CRLF
const desktopNavRegex = /<nav\s+class="hidden lg:flex items-center gap-5 xl:gap-7 text-xs xl:text-sm font-semibold text-slate-300 uppercase tracking-wider">[\s\S]*?<\/nav>/;

const newDesktopNav = `<nav
                class="hidden lg:flex items-center gap-5 xl:gap-7 text-xs xl:text-sm font-semibold text-slate-300 uppercase tracking-wider">
                <!-- PRODUCTS Dropdown (ORDER & STOCK) -->
                <div class="relative group" x-data="{ productsOpen: false }" @mouseenter="productsOpen = true" @mouseleave="productsOpen = false">
                    <button type="button" @click="productsOpen = !productsOpen" class="hover:text-brand-tealLight transition-colors flex items-center gap-1.5 uppercase tracking-wider py-2 focus:outline-none cursor-pointer">
                        <span>Products</span>
                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200" :class="{ 'rotate-180': productsOpen }"></i>
                    </button>
                    <!-- Dropdown Menu -->
                    <div x-show="productsOpen" x-cloak
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                        class="absolute left-0 top-full pt-1 w-56 z-50 normal-case">
                        <div class="rounded-xl bg-brand-cardBg/95 backdrop-blur-md border border-brand-border shadow-2xl p-2 space-y-1">
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
                        </div>
                    </div>
                </div>

                <a href="#salon" class="hover:text-brand-tealLight transition-colors">Salon</a>
                <a href="#transformations" class="hover:text-brand-tealLight transition-colors flex items-center gap-1.5 font-bold text-white">
                    <i class="fa-solid fa-arrows-split-up-and-left text-teal-400"></i>
                    <span>Before & After</span>
                </a>
                <a href="#ai-simulator" class="hover:text-brand-tealLight transition-colors flex items-center gap-1 text-teal-300 font-bold">
                    <i class="fa-solid fa-wand-magic-sparkles text-xs"></i>
                    <span>AI Try-On</span>
                </a>
                <a href="#factory" class="hover:text-brand-tealLight transition-colors">Factory</a>
                <a href="#b2b" class="hover:text-brand-tealLight transition-colors">B2B</a>
            </nav>`;

if (!desktopNavRegex.test(html)) {
    console.error('Desktop nav not matched!');
    process.exit(1);
}

html = html.replace(desktopNavRegex, newDesktopNav);
console.log('Desktop nav replaced successfully!');

// 2. Update Mobile Navigation Drawer
const mobileOrderStockRegex = /<a href="#builder" @click="mobileMenuOpen = false"[\s\S]*?<a href="#salon"/;

const newMobileOrderStock = `<!-- Mobile Products Dropdown -->
            <div x-data="{ mobileProductsOpen: true }" class="border-b border-brand-border/40 pb-2">
                <button type="button" @click="mobileProductsOpen = !mobileProductsOpen"
                    class="w-full flex items-center justify-between text-slate-200 font-bold hover:text-brand-tealLight py-2 uppercase tracking-wider text-left">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-box-open text-brand-tealLight text-xs"></i>
                        <span>Products</span>
                    </span>
                    <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': mobileProductsOpen }"></i>
                </button>
                <div x-show="mobileProductsOpen" class="pl-3 space-y-1 mt-1">
                    <a href="#builder" @click="mobileMenuOpen = false"
                        class="block text-slate-300 hover:text-white font-medium py-1.5 text-xs flex items-center gap-2">
                        <i class="fa-solid fa-sliders text-teal-400 text-xs"></i>
                        <span class="font-bold uppercase tracking-wider">Order</span>
                        <span class="text-brand-textMuted text-[10px]">(Custom Toupee Builder)</span>
                    </a>
                    <a href="#stock" @click="mobileMenuOpen = false"
                        class="block text-slate-300 hover:text-white font-medium py-1.5 text-xs flex items-center gap-2">
                        <i class="fa-solid fa-boxes-stacked text-amber-400 text-xs"></i>
                        <span class="font-bold uppercase tracking-wider">Stock</span>
                        <span class="text-brand-textMuted text-[10px]">(In-Stock Inventory)</span>
                    </a>
                </div>
            </div>

            <a href="#salon"`;

if (!mobileOrderStockRegex.test(html)) {
    console.error('Mobile order/stock not matched!');
    process.exit(1);
}

html = html.replace(mobileOrderStockRegex, newMobileOrderStock);
console.log('Mobile nav replaced successfully!');

fs.writeFileSync('index.html', html, 'utf8');
console.log('index.html updated successfully with PRODUCTS dropdown menu!');
