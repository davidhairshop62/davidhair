<?php
$pageTitle       = "In-Stock Men's Toupees & Women's Toppers Melbourne | Ready Today | David Hair";
$pageDescription = "Browse live ready-to-fit men's hair systems, skin/lace toupees & women's toppers currently in stock at our Springvale, Melbourne flagship studio.";
$pageKeywords    = 'in stock toupee melbourne, ready to wear hair system springvale, same day toupee fitting melbourne, human hair topper stock';
$canonicalPath   = '/stock';
$activePage      = 'stock';
$extraHeadScripts = [];
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/subpage_bar.php';
?>
<main id="main-content" class="w-full min-h-screen">
<?php dh_render_subpage_top('In-Stock Precision Pieces', 'READY TO FIT'); ?>
    <section id="stock"
        class="bg-brand-darkBg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-12">
                <span class="text-brand-tealLight text-xs font-bold uppercase tracking-widest block mb-2">Ready-To-Ship
                    Inventory</span>
                <h2 class="font-heading text-2xl sm:text-4xl font-extrabold text-white">
                    In-Stock Precision Pieces</h2>
                <p class="text-brand-textMuted text-xs sm:text-sm mt-2 sm:mt-3">Pre-made master
                    stock systems ready for
                    immediate express dispatch or same-day Melbourne salon fitting.</p>
            </div>

            <!-- Filter Tabs -->
            <div class="flex flex-wrap items-center justify-center gap-2 mb-8 sm:mb-12">
                <template
                    x-for="cat in ['All', 'Men\'s Toupee', 'Women\'s topper', 'Human Hair Wig', 'Synthetic Wig', 'Hair Extension']"
                    :key="cat">
                    <button @click="stockFilter = cat"
                        :class="stockFilter === cat ? 'bg-brand-teal text-white font-bold shadow-md' : 'bg-brand-cardBg text-slate-300 border border-brand-border hover:border-slate-500'"
                        class="px-4 sm:px-5 py-2 rounded-xl text-xs uppercase tracking-wider transition-all min-h-[38px]"
                        x-text="cat"></button>
                </template>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <template x-for="item in filteredStockItems" :key="item.sku">
                    <div
                        class="bg-brand-cardBg border border-brand-border rounded-2xl overflow-hidden eye-shadow flex flex-col justify-between group hover:border-brand-teal transition-all">
                        <div>
                            <div class="relative py-10 sm:py-16 aspect-square w-full overflow-hidden bg-brand-inner">
                                <img decoding="async" loading="lazy" :src="getStockImageUrl(item.active_image || item.image)" :alt="item.name"
                                    @click="openStockDetailModal(item)"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 cursor-pointer">
                                <div class="absolute top-3 left-3 bg-brand-darkBg/90 text-brand-tealLight text-[10px] font-bold px-2.5 py-1 rounded-md border border-brand-border"
                                    x-text="'Stock: ' + item.stockQty + ' pcs'"></div>

                                <!-- Badge Tag Overlay (New, Best Seller, MD's Pick, Limited, Promotion) -->
                                <template x-if="item.badge">
                                    <div class="absolute bottom-3 left-3 z-10">
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider shadow-xl border flex items-center gap-1.5"
                                              :class="getStockBadgeClass(item.badge)">
                                            <i :class="getStockBadgeIcon(item.badge)"></i>
                                            <span x-text="item.badge"></span>
                                        </span>
                                    </div>
                                </template>
                            </div>

                            <!-- 다중 사진 등록 시: 메인사진 아래 3개씩 표시 -->
                            <template x-if="item.gallery_images && item.gallery_images.length > 1">
                                <div class="p-2 bg-brand-inner/70 border-b border-brand-border/60">
                                    <div class="grid grid-cols-3 gap-1.5">
                                        <template x-for="(gImg, gIdx) in item.gallery_images" :key="gIdx">
                                            <div @click.stop="item.active_image = gImg"
                                                 class="relative aspect-square rounded-lg overflow-hidden border cursor-pointer transition-all bg-brand-darkBg"
                                                 :class="(item.active_image ? item.active_image === gImg : item.image === gImg) ? 'border-brand-teal ring-2 ring-brand-teal/40' : 'border-brand-border/70 hover:border-slate-400 opacity-80 hover:opacity-100'">
                                                <img :src="getStockImageUrl(gImg)" :alt="item.name + ' - Photo ' + (gIdx + 1)" class="w-full h-full object-cover">
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <div class="p-4 sm:p-5 space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="text-[10px] font-mono text-brand-tealLight" x-text="item.sku"></div>
                                    <span class="text-[10px] text-brand-textMuted font-medium" x-text="item.category"></span>
                                </div>
                                <h3 class="font-bold text-white text-sm sm:text-base leading-snug cursor-pointer hover:text-brand-tealLight transition-colors" @click="openStockDetailModal(item)" x-text="item.name"></h3>
                                <template x-if="item.description">
                                    <p class="text-xs text-brand-textMuted line-clamp-2 leading-relaxed" x-text="item.description"></p>
                                </template>

                                <div
                                    class="grid grid-cols-2 gap-2 text-[10px] sm:text-[11px] text-brand-textMuted font-mono">
                                    <div
                                        class="bg-brand-inner px-2 py-1 rounded border border-brand-border/60 truncate">
                                        Base: <span class="text-slate-200" x-text="item.base"></span></div>
                                    <div
                                        class="bg-brand-inner px-2 py-1 rounded border border-brand-border/60 truncate">
                                        Size: <span class="text-slate-200" x-text="item.size"></span></div>
                                    <div
                                        class="bg-brand-inner px-2 py-1 rounded border border-brand-border/60 truncate">
                                        Density: <span class="text-slate-200" x-text="item.density"></span></div>
                                    <div
                                        class="bg-brand-inner px-2 py-1 rounded border border-brand-border/60 truncate">
                                        Color: <span class="text-slate-200" x-text="item.color"></span></div>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 sm:p-5 pt-0 space-y-3 border-t border-brand-border/60 mt-3 sm:mt-4">
                            <div class="flex items-center justify-between pt-3">
                                <span class="text-xs text-brand-textMuted">Price</span>
                                <span class="font-heading text-base sm:text-lg font-bold text-brand-tealLight"
                                    x-text="formatCurrency(item.price)"></span>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <button @click="openStockDetailModal(item)"
                                    class="teal-gradient text-white text-[11px] font-bold py-2.5 rounded-lg shadow-sm hover:opacity-95 min-h-[40px] flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                                    <i class="fa-solid fa-circle-info text-[11px]"></i>
                                    <span>Detail Item</span>
                                </button>
                                <button @click="bookStockFit(item)"
                                    class="bg-brand-inner hover:bg-slate-800 text-slate-200 border border-brand-border text-[11px] font-semibold py-2.5 rounded-lg hover:border-brand-teal transition-all min-h-[40px] flex items-center justify-center gap-1.5 cursor-pointer">
                                    <i class="fa-solid fa-calendar-check text-[11px] text-brand-tealLight"></i>
                                    <span>Book Salon Fit</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Empty State Message if category has 0 in-stock pieces -->
                <template x-if="filteredStockItems.length === 0">
                    <div class="col-span-full text-center py-12 px-6 bg-brand-cardBg/60 border border-brand-border/60 rounded-2xl space-y-3">
                        <div class="w-12 h-12 rounded-full bg-teal-500/10 border border-teal-500/30 flex items-center justify-center mx-auto text-brand-tealLight text-xl">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                        <h3 class="text-white font-bold text-base">No In-Stock Pieces in This Category</h3>
                        <p class="text-brand-textMuted text-xs max-w-md mx-auto leading-relaxed">
                            All pre-made stock pieces in <span class="text-white font-semibold" x-text="stockFilter"></span> are currently allocated or reserved for Melbourne salon fittings. You can craft a custom bespoke order directly with our factory.
                        </p>
                        <div class="pt-2">
                            <button type="button" @click="isCalculatorModalOpen = true" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl teal-gradient text-white text-xs font-bold shadow-md hover:opacity-95 transition-all cursor-pointer">
                                <i class="fa-solid fa-calculator"></i>
                                <span>Build Custom Hairpiece</span>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>

<?php dh_render_subpage_bottom('stock'); ?>
</main>
    <!-- STOCK ITEM DETAIL MODAL -->
    <div x-show="isStockDetailModalOpen" x-cloak
        class="fixed inset-0 z-[75] flex items-center justify-center p-3 sm:p-5 bg-black/85 backdrop-blur-md overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        
        <div @click.away="closeStockDetailModal()"
            class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-4xl w-full p-5 sm:p-7 shadow-2xl relative my-6 max-h-[92vh] overflow-y-auto space-y-6"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="scale-95 opacity-0"
            x-transition:enter-end="scale-100 opacity-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="scale-100 opacity-100"
            x-transition:leave-end="scale-95 opacity-0">
            
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-brand-border pb-4">
                <div class="flex items-center gap-2.5 flex-wrap">
                    <span class="w-2.5 h-2.5 rounded-full bg-brand-teal animate-pulse"></span>
                    <span class="font-heading font-extrabold text-white text-base sm:text-lg">Detail Item Information</span>
                    <template x-if="selectedStockDetail">
                        <span class="text-xs bg-brand-inner border border-brand-border px-2.5 py-0.5 rounded font-mono text-brand-tealLight font-bold" x-text="selectedStockDetail.sku"></span>
                    </template>
                    <template x-if="selectedStockDetail && selectedStockDetail.badge">
                        <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider shadow border flex items-center gap-1"
                              :class="getStockBadgeClass(selectedStockDetail.badge)">
                            <i :class="getStockBadgeIcon(selectedStockDetail.badge)"></i>
                            <span x-text="selectedStockDetail.badge"></span>
                        </span>
                    </template>
                </div>
                <button type="button" @click="closeStockDetailModal()" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition-colors">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Modal Content (Two Columns) -->
            <template x-if="selectedStockDetail">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <!-- Left: High-Res Photo & 3-Column Thumbnails -->
                    <div class="md:col-span-5 space-y-3">
                        <div class="relative aspect-square w-full rounded-2xl overflow-hidden bg-brand-inner border border-brand-border shadow-lg flex items-center justify-center group">
                            <img :src="getStockImageUrl(stockDetailActiveImage || selectedStockDetail.image)" 
                                 :alt="selectedStockDetail.name" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            
                            <!-- Stock Qty Badge -->
                            <div class="absolute top-3 left-3 bg-brand-darkBg/90 text-brand-tealLight text-[11px] font-bold px-3 py-1 rounded-md border border-brand-border shadow-md flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                <span x-text="'Melbourne Stock: ' + (selectedStockDetail.stockQty || 0) + ' pcs'"></span>
                            </div>
                        </div>

                        <!-- Under Main Photo: If Multiple Photos, Show 3 Per Row -->
                        <template x-if="selectedStockDetail.gallery_images && selectedStockDetail.gallery_images.length > 1">
                            <div class="p-3 bg-brand-inner/70 rounded-xl border border-brand-border/60">
                                <div class="flex items-center justify-between mb-2 px-0.5">
                                    <span class="text-[11px] font-bold text-slate-300 flex items-center gap-1.5">
                                        <i class="fa-solid fa-images text-brand-tealLight"></i>
                                        <span>Gallery Photos (<span x-text="selectedStockDetail.gallery_images.length"></span>)</span>
                                    </span>
                                    <span class="text-[10px] text-brand-tealLight font-mono">3 Photos per Row</span>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <template x-for="(gImg, gIdx) in selectedStockDetail.gallery_images" :key="gIdx">
                                        <div @click="stockDetailActiveImage = gImg"
                                             class="relative aspect-square rounded-xl overflow-hidden border cursor-pointer transition-all bg-brand-darkBg group/thumb"
                                             :class="(stockDetailActiveImage === gImg || (!stockDetailActiveImage && selectedStockDetail.image === gImg)) ? 'border-2 border-brand-teal ring-2 ring-brand-teal/40 shadow-md' : 'border-brand-border/80 hover:border-slate-400 opacity-80 hover:opacity-100'">
                                            <img :src="getStockImageUrl(gImg)" :alt="selectedStockDetail.name + ' - Photo ' + (gIdx + 1)" class="w-full h-full object-cover">
                                            <template x-if="selectedStockDetail.image === gImg">
                                                <span class="absolute top-1 left-1 bg-brand-teal text-white text-[8px] font-black px-1 rounded shadow">Main</span>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Right: Detailed Specs & Description -->
                    <div class="md:col-span-7 flex flex-col justify-between space-y-4">
                        <div class="space-y-4">
                            <!-- Category & Title -->
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs uppercase tracking-wider text-brand-tealLight font-semibold" x-text="selectedStockDetail.category"></span>
                                    <span class="text-slate-500">•</span>
                                    <span class="text-xs text-slate-400" x-text="selectedStockDetail.type || 'Custom Piece'"></span>
                                </div>
                                <h2 class="font-heading font-extrabold text-white text-lg sm:text-2xl leading-snug" x-text="selectedStockDetail.name"></h2>
                            </div>

                            <!-- Price Banner -->
                            <div class="p-3.5 bg-brand-inner/90 border border-brand-teal/30 rounded-xl flex items-center justify-between shadow-md">
                                <div>
                                    <span class="text-[11px] text-slate-400 uppercase tracking-wider font-semibold block">Factory Direct Price</span>
                                    <span class="font-heading text-xl sm:text-2xl font-black text-brand-tealLight" x-text="formatCurrency(selectedStockDetail.price)"></span>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 text-xs font-bold">
                                        <i class="fa-solid fa-truck-fast"></i>
                                        <span>Same-Day Dispatch</span>
                                    </span>
                                    <p class="text-[10px] text-slate-400 mt-1">Free Delivery across Australia</p>
                                </div>
                            </div>

                            <!-- Specification Table / Grid -->
                            <div class="space-y-2">
                                <span class="text-xs font-bold text-slate-200 uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="fa-solid fa-sliders text-brand-tealLight"></i>
                                    <span>Technical Specifications (상세 규격 정보)</span>
                                </span>
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div class="bg-brand-inner/70 p-2.5 rounded-lg border border-brand-border/70 flex flex-col">
                                        <span class="text-[10px] text-brand-textMuted uppercase font-semibold">Base Construction</span>
                                        <span class="font-bold text-slate-100 mt-0.5 truncate" x-text="selectedStockDetail.base || 'Custom Hand-Tied'"></span>
                                    </div>
                                    <div class="bg-brand-inner/70 p-2.5 rounded-lg border border-brand-border/70 flex flex-col">
                                        <span class="text-[10px] text-brand-textMuted uppercase font-semibold">Base Size</span>
                                        <span class="font-bold text-slate-100 mt-0.5 truncate" x-text="selectedStockDetail.size || 'Standard Fit'"></span>
                                    </div>
                                    <div class="bg-brand-inner/70 p-2.5 rounded-lg border border-brand-border/70 flex flex-col">
                                        <span class="text-[10px] text-brand-textMuted uppercase font-semibold">Hair Density</span>
                                        <span class="font-bold text-slate-100 mt-0.5 truncate" x-text="selectedStockDetail.density || 'Natural (100%)'"></span>
                                    </div>
                                    <div class="bg-brand-inner/70 p-2.5 rounded-lg border border-brand-border/70 flex flex-col">
                                        <span class="text-[10px] text-brand-textMuted uppercase font-semibold">Hair Color</span>
                                        <span class="font-bold text-slate-100 mt-0.5 truncate" x-text="selectedStockDetail.color || '#Natural'"></span>
                                    </div>
                                    <div class="bg-brand-inner/70 p-2.5 rounded-lg border border-brand-border/70 flex flex-col">
                                        <span class="text-[10px] text-brand-textMuted uppercase font-semibold">Hair Type / Material</span>
                                        <span class="font-bold text-slate-100 mt-0.5 truncate" x-text="(selectedStockDetail.category && selectedStockDetail.category.toLowerCase().includes('synthetic')) ? 'Premium Heat-Resistant Synthetic' : '100% Virgin Remy Human Hair'"></span>
                                    </div>
                                    <div class="bg-brand-inner/70 p-2.5 rounded-lg border border-brand-border/70 flex flex-col">
                                        <span class="text-[10px] text-brand-textMuted uppercase font-semibold">Melbourne Availability</span>
                                        <span class="font-bold text-emerald-400 mt-0.5 flex items-center gap-1 truncate">
                                            <i class="fa-solid fa-circle-check text-[10px]"></i>
                                            <span x-text="(selectedStockDetail.stockQty || 0) + ' pcs In Stock'"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Description -->
                            <div class="space-y-1.5">
                                <span class="text-xs font-bold text-slate-200 uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="fa-solid fa-file-lines text-brand-tealLight"></i>
                                    <span>Detailed Description (제품 설명)</span>
                                </span>
                                <div class="p-3.5 bg-brand-inner/50 rounded-xl border border-brand-border/70 text-xs text-slate-300 leading-relaxed max-h-36 overflow-y-auto">
                                    <template x-if="selectedStockDetail.description">
                                        <p class="whitespace-pre-line" x-text="selectedStockDetail.description"></p>
                                    </template>
                                    <template x-if="!selectedStockDetail.description">
                                        <p class="text-slate-400">
                                            This precision piece is engineered with master artisan craftsmanship using 100% premium human hair. Designed for ultra-natural hairlines, superior breathability, and reliable day-to-day wear. Compatible with medical-grade tapes and liquid lace adhesives for 2 to 4 weeks secure bonding.
                                        </p>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons in Modal -->
                        <div class="pt-4 border-t border-brand-border flex flex-col sm:flex-row gap-2.5">
                            <button type="button" 
                                    @click="bookStockFit(selectedStockDetail); isStockDetailModalOpen = false;"
                                    class="flex-1 px-5 py-3 rounded-xl teal-gradient hover:opacity-95 text-white font-bold text-xs uppercase tracking-wider transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer">
                                <i class="fa-solid fa-calendar-check text-sm"></i>
                                <span>Book Springvale Salon Fitting</span>
                            </button>
                            <a :href="'https://wa.me/61433484882?text=' + encodeURIComponent('Hi David Hair Studio, I am interested in Precision Piece: ' + selectedStockDetail.name + ' (SKU: ' + selectedStockDetail.sku + ', Price: $' + selectedStockDetail.price + ' AUD). Could you provide express order details?')"
                               target="_blank"
                               @click="isStockDetailModalOpen = false"
                               class="flex-1 px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs uppercase tracking-wider transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer">
                                <i class="fa-brands fa-whatsapp text-sm"></i>
                                <span>Express WhatsApp Order</span>
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>


<?php
require __DIR__ . '/includes/global_modals.php';
require __DIR__ . '/includes/footer.php';
?>
