<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Inventory Manager | David Hair Admin</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            darkBg: '#0B132B',
                            cardBg: '#1C2541',
                            cardInner: '#151E38',
                            border: '#2E3A59',
                            textMain: '#F1F5F9',
                            textMuted: '#94A3B8',
                            teal: '#0D9488',
                            tealLight: '#14B8A6',
                            bronze: '#D97706'
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif']
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .teal-gradient { background: linear-gradient(135deg, #0D9488 0%, #0284C7 100%); }
        .bronze-gradient { background: linear-gradient(135deg, #D97706 0%, #B45309 100%); }
    </style>
</head>
<body class="bg-brand-darkBg text-brand-textMain font-sans antialiased min-h-screen" x-data="stockManagerApp()">

    <!-- 1. ADMIN LOGIN MODAL SCREEN (Shown if not authenticated) -->
    <div x-show="!isAuthenticated" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-brand-darkBg/98 backdrop-blur-md">
        <div class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-md w-full p-6 sm:p-8 space-y-6 shadow-2xl">
            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-xl teal-gradient flex items-center justify-center font-heading font-extrabold text-white text-2xl mx-auto shadow-md">
                    DH
                </div>
                <h2 class="font-heading text-xl sm:text-2xl font-bold text-white tracking-tight">David Hair Admin Authentication</h2>
                <p class="text-xs text-brand-textMuted">Enter Admin ID and Password to manage stock inventory & AI simulator.</p>
            </div>

            <!-- Login Form -->
            <form @submit.prevent="login()" class="space-y-4 text-xs">
                <div x-show="loginError" x-cloak class="p-3 rounded-lg bg-rose-950/80 border border-rose-800 text-rose-300 text-xs font-semibold flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation flex-shrink-0"></i>
                    <span x-text="loginError"></span>
                </div>

                <div>
                    <label class="block font-bold text-slate-300 mb-1">Admin ID (Email)</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="email" x-model="loginForm.email" required placeholder="shc9607@gmail.com" class="w-full bg-brand-darkBg border border-brand-border rounded-lg pl-10 pr-4 py-2.5 text-white font-medium outline-none focus:border-brand-teal min-h-[44px]">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-300 mb-1">Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input :type="showPassword ? 'text' : 'password'" x-model="loginForm.password" required placeholder="••••••••" class="w-full bg-brand-darkBg border border-brand-border rounded-lg pl-10 pr-10 py-2.5 text-white font-mono text-sm outline-none focus:border-brand-teal min-h-[44px]">
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white p-1">
                            <i :class="showPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" :disabled="isLoggingIn" class="w-full teal-gradient py-3.5 rounded-xl text-white font-bold text-xs uppercase tracking-wider shadow-lg hover:opacity-95 transition-all min-h-[48px] flex items-center justify-center gap-2 disabled:opacity-50">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span x-text="isLoggingIn ? 'Authenticating...' : 'Sign In to Stock Manager'"></span>
                </button>
            </form>

            <div class="pt-2 text-center text-[11px] text-brand-textMuted border-t border-brand-border/60">
                Authorized Staff Only &bull; David Hair Systems Pty Ltd
            </div>
        </div>
    </div>

    <!-- 2. MAIN DASHBOARD CONTENT (Shown if authenticated) -->
    <div x-show="isAuthenticated" x-cloak>
        <!-- Header / Navbar -->
        <header class="bg-brand-cardBg border-b border-brand-border sticky top-0 z-30 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl teal-gradient flex items-center justify-center font-heading font-extrabold text-white text-xl shadow-md">
                        DH
                    </div>
                    <div>
                        <h1 class="font-heading text-lg sm:text-xl font-bold text-white tracking-tight leading-tight">Stock & AI Manager</h1>
                        <span class="text-[11px] text-brand-tealLight font-bold uppercase tracking-wider block">David Hair MySQL Admin Console</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Language Switcher Buttons (Flag icons only) -->
                    <div class="flex items-center gap-1 bg-brand-inner border border-brand-border rounded-lg p-1 text-sm font-bold">
                        <button type="button" @click="setLang('en')" :class="lang === 'en' ? 'bg-brand-teal text-white shadow scale-105' : 'text-slate-400 hover:text-white opacity-70 hover:opacity-100'" class="px-2 py-1 rounded transition-all" title="English (Australia)">🇦🇺</button>
                        <button type="button" @click="setLang('ko')" :class="lang === 'ko' ? 'bg-brand-teal text-white shadow scale-105' : 'text-slate-400 hover:text-white opacity-70 hover:opacity-100'" class="px-2 py-1 rounded transition-all" title="한국어 (Korean)">🇰🇷</button>
                        <button type="button" @click="setLang('id')" :class="lang === 'id' ? 'bg-brand-teal text-white shadow scale-105' : 'text-slate-400 hover:text-white opacity-70 hover:opacity-100'" class="px-2 py-1 rounded transition-all" title="Bahasa Indonesia">🇮🇩</button>
                    </div>

                    <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg bg-brand-inner border border-brand-border text-xs text-slate-300 font-mono">
                        <i class="fa-solid fa-user-shield text-brand-tealLight"></i>
                        <span x-text="currentUser"></span>
                    </div>

                    <a href="/" target="_blank" class="bg-brand-inner hover:bg-slate-800 text-slate-300 border border-brand-border text-xs font-semibold px-3.5 py-2.5 rounded-lg transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-globe"></i> View Site
                    </a>

                    <template x-if="activeTab === 'stock'">
                        <button @click="openModal('add')" class="teal-gradient hover:opacity-95 text-white font-bold text-xs uppercase tracking-wider px-3.5 py-2.5 rounded-lg shadow-md transition-all flex items-center gap-2">
                            <i class="fa-solid fa-plus"></i> Add New Stock
                        </button>
                    </template>
                    <template x-if="activeTab === 'ai_styles'">
                        <button @click="openAiModal('add')" class="teal-gradient hover:opacity-95 text-white font-bold text-xs uppercase tracking-wider px-3.5 py-2.5 rounded-lg shadow-md transition-all flex items-center gap-2">
                            <i class="fa-solid fa-plus"></i> Add AI Hairstyle
                        </button>
                    </template>
                    <template x-if="activeTab === 'bookings'">
                        <button @click="loadBookings()" class="teal-gradient hover:opacity-95 text-white font-bold text-xs uppercase tracking-wider px-3.5 py-2.5 rounded-lg shadow-md transition-all flex items-center gap-2">
                            <i class="fa-solid fa-rotate" :class="{ 'fa-spin': isLoadingBookings }"></i> Refresh Bookings
                        </button>
                    </template>

                    <button @click="logout()" class="bg-rose-950/80 hover:bg-rose-900 text-rose-300 border border-rose-800 text-xs font-bold px-3 py-2.5 rounded-lg transition-colors flex items-center gap-1.5" title="Sign Out">
                        <i class="fa-solid fa-power-off"></i> Logout
                    </button>
                </div>
            </div>
        </header>

        <!-- FLOATING TOAST NOTIFICATION -->
        <div x-show="toastMessage" 
             x-cloak 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 transform -translate-y-4 scale-95"
             class="fixed top-6 right-6 z-50 bg-emerald-950/95 border-2 border-emerald-500 text-emerald-200 px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-3 font-bold text-xs backdrop-blur-md">
            <i class="fa-solid fa-circle-check text-emerald-400 text-lg"></i>
            <span x-text="toastMessage"></span>
        </div>

        <!-- Main Content Container -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            <!-- Top Navigation Tab Switcher -->
            <div class="flex items-center gap-3 border-b border-brand-border pb-3">
                <button @click="activeTab = 'stock'" 
                        :class="activeTab === 'stock' ? 'bg-brand-teal text-white font-bold shadow-md' : 'bg-brand-cardBg text-slate-300 hover:text-white border border-brand-border'" 
                        class="px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all flex items-center gap-2">
                    <i class="fa-solid fa-boxes-stacked"></i> Stock Inventory Manager (재고 관리)
                </button>
                <button @click="activeTab = 'ai_styles'; loadAiStyles()" 
                        :class="activeTab === 'ai_styles' ? 'bg-brand-teal text-white font-bold shadow-md' : 'bg-brand-cardBg text-slate-300 hover:text-white border border-brand-border'" 
                        class="px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all flex items-center gap-2">
                    <i class="fa-solid fa-scissors text-amber-400"></i> AI Simulator Hairstyles (가상 헤어스타일 등록/관리)
                </button>
                <button @click="activeTab = 'bookings'; loadBookings()" 
                        :class="activeTab === 'bookings' ? 'bg-brand-teal text-white font-bold shadow-md' : 'bg-brand-cardBg text-slate-300 hover:text-white border border-brand-border'" 
                        class="px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all flex items-center gap-2 relative">
                    <i class="fa-solid fa-calendar-check text-emerald-400"></i> Springvale Fitting Bookings (예약 관리)
                    <template x-if="pendingBookingsCount > 0">
                        <span class="ml-1.5 px-2 py-0.5 text-[10px] font-black rounded-full bg-rose-500 text-white animate-pulse" x-text="pendingBookingsCount"></span>
                    </template>
                </button>
            </div>

            <!-- TAB 1: STOCK INVENTORY MANAGER -->
            <div x-show="activeTab === 'stock'" space-y-6>
                <!-- Stats Bar -->
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm">
                        <div class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">Total Stock Items</div>
                        <div class="text-2xl font-extrabold text-white mt-1" x-text="items.length"></div>
                    </div>
                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm">
                        <div class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">Total Inventory Qty</div>
                        <div class="text-2xl font-extrabold text-brand-tealLight mt-1" x-text="totalQty"></div>
                    </div>
                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm">
                        <div class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">Categories</div>
                        <div class="text-2xl font-extrabold text-brand-bronze mt-1">5</div>
                    </div>
                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm">
                        <div class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">Database Status</div>
                        <div class="text-xs font-bold text-emerald-400 mt-2 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> MySQL Connected
                        </div>
                    </div>
                </div>

                <!-- Filter & Search Controls -->
                <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl flex flex-col md:flex-row items-center justify-between gap-4 mb-6">
                    <div class="relative w-full md:w-80">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" x-model="searchQuery" placeholder="Search by SKU, Name, Base..." class="w-full bg-brand-inner border border-brand-border rounded-lg pl-9 pr-4 py-2 text-xs text-white outline-none focus:border-brand-teal">
                    </div>

                    <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                        <!-- Category Filter -->
                        <div class="flex items-center gap-2">
                            <label class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">Category:</label>
                            <select x-model="selectedCategory" class="bg-brand-inner border border-brand-border text-xs text-white rounded-lg px-3 py-2 outline-none focus:border-brand-teal">
                                <option value="All">All Categories</option>
                                <option value="Men's Toupees">Men's Toupees</option>
                                <option value="Women's Toppers">Women's Toppers</option>
                                <option value="Skin Systems">Skin Systems</option>
                                <option value="Swiss Lace">Swiss Lace</option>
                                <option value="PU Perimeter">PU Perimeter</option>
                            </select>
                        </div>

                        <!-- Sort By Selector -->
                        <div class="flex items-center gap-2">
                            <label class="text-xs font-bold text-brand-textMuted uppercase tracking-wider flex items-center gap-1">
                                <i class="fa-solid fa-arrow-down-short-wide text-brand-tealLight"></i> Sort:
                            </label>
                            <select @change="applySelectSort($event.target.value)" :value="sortColumn ? (sortColumn + '-' + sortDirection) : 'id-desc'" class="bg-brand-inner border border-brand-border text-xs text-white rounded-lg px-3 py-2 outline-none focus:border-brand-teal font-medium">
                                <option value="id-desc">최신 등록순</option>
                                <option value="name-asc">Item Name (제품명 A-Z)</option>
                                <option value="name-desc">Item Name (제품명 Z-A)</option>
                                <option value="category-asc">Category (카테고리순)</option>
                                <option value="size-asc">Size (사이즈순)</option>
                                <option value="price-asc">Price (가격 낮은순 ↑)</option>
                                <option value="price-desc">Price (가격 높은순 ↓)</option>
                                <option value="stockQty-desc">Stock Qty (재고 많은순 ↓)</option>
                                <option value="stockQty-asc">Stock Qty (재고 적은순 ↑)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Stock Items Table -->
                <div class="bg-brand-cardBg border border-brand-border rounded-2xl overflow-hidden shadow-xl">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-brand-inner text-brand-textMuted uppercase font-bold text-[11px] border-b border-brand-border select-none">
                                <tr>
                                    <th class="py-3.5 px-4 w-36">Product Image</th>
                                    
                                    <!-- 1. Sort by Item Name -->
                                    <th class="py-3.5 px-4 cursor-pointer hover:text-white transition-colors group" @click="sortBy('name')" title="클릭하여 제품명 정렬">
                                        <div class="flex items-center gap-1.5">
                                            <span>SKU & Item Name</span>
                                            <i class="fa-solid" :class="getSortIcon('name')"></i>
                                        </div>
                                    </th>

                                    <!-- 2. Sort by Category -->
                                    <th class="py-3.5 px-4 cursor-pointer hover:text-white transition-colors group" @click="sortBy('category')" title="클릭하여 카테고리 정렬">
                                        <div class="flex items-center gap-1.5">
                                            <span>Category / Base</span>
                                            <i class="fa-solid" :class="getSortIcon('category')"></i>
                                        </div>
                                    </th>

                                    <!-- 3. Sort by Size -->
                                    <th class="py-3.5 px-4 cursor-pointer hover:text-white transition-colors group" @click="sortBy('size')" title="클릭하여 사이즈 정렬">
                                        <div class="flex items-center gap-1.5">
                                            <span>Specs (Size/Density/Color)</span>
                                            <i class="fa-solid" :class="getSortIcon('size')"></i>
                                        </div>
                                    </th>

                                    <!-- 4. Sort by Price -->
                                    <th class="py-3.5 px-4 cursor-pointer hover:text-white transition-colors group whitespace-nowrap" @click="sortBy('price')" title="클릭하여 가격 정렬">
                                        <div class="flex items-center gap-1.5">
                                            <span>Price (AUD)</span>
                                            <i class="fa-solid" :class="getSortIcon('price')"></i>
                                        </div>
                                    </th>

                                    <!-- 5. Sort by Stock Qty -->
                                    <th class="py-3.5 px-4 cursor-pointer hover:text-white transition-colors group whitespace-nowrap" @click="sortBy('stockQty')" title="클릭하여 재고수량 정렬">
                                        <div class="flex items-center gap-1.5">
                                            <span>Stock Qty</span>
                                            <i class="fa-solid" :class="getSortIcon('stockQty')"></i>
                                        </div>
                                    </th>

                                    <th class="py-3.5 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-brand-border/60">
                                <template x-for="item in filteredItems" :key="item.id">
                                    <tbody class="contents">
                                        <!-- 1. VIEW MODE ROW -->
                                        <tr x-show="editingRowId !== item.id" class="hover:bg-brand-inner/50 transition-colors group">
                                            <td class="py-3 px-4 align-top">
                                                <!-- 큼직한 사진 박스 (112px ~ 128px) -->
                                                <div class="relative w-28 h-28 sm:w-32 sm:h-32 rounded-xl overflow-hidden border-2 border-brand-border bg-brand-inner flex-shrink-0 group/img shadow-md">
                                                    <img :src="item.image" :alt="item.name" class="w-full h-full object-cover group-hover/img:scale-105 transition-transform duration-300">
                                                    
                                                    <!-- 마우스 호버 시 즉시 사진 변경 레이블/버튼 -->
                                                    <label class="absolute inset-0 bg-black/75 opacity-0 group-hover/img:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer text-white p-2 text-center" title="이 제품의 사진을 즉시 변경">
                                                        <i class="fa-solid fa-camera text-xl mb-1 text-brand-tealLight"></i>
                                                        <span class="text-[11px] font-bold">사진 변경</span>
                                                        <input type="file" accept="image/*" @change="quickUploadImage(item, $event)" class="hidden">
                                                    </label>
                                                    
                                                    <!-- 사진 원본 보기 링크 (새 탭) -->
                                                    <a :href="item.image" target="_blank" @click.stop class="absolute bottom-1 right-1 w-6 h-6 rounded-md bg-black/60 hover:bg-black text-slate-300 hover:text-white flex items-center justify-center text-[10px] opacity-0 group-hover/img:opacity-100 transition-opacity shadow" title="사진 원본 보기">
                                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4 align-top">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-mono text-brand-tealLight font-bold" x-text="item.sku"></span>
                                                    <template x-if="item.badge">
                                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase border shadow-sm flex items-center gap-1"
                                                              :class="getBadgeClass(item.badge)">
                                                            <i :class="getBadgeIcon(item.badge)"></i>
                                                            <span x-text="item.badge"></span>
                                                        </span>
                                                    </template>
                                                </div>
                                                <div class="font-bold text-white text-sm mt-0.5" x-text="item.name"></div>
                                                <div x-show="item.description" class="text-[11px] text-brand-textMuted line-clamp-2 mt-1 bg-brand-inner/40 p-1.5 rounded border border-brand-border/40" x-text="item.description"></div>
                                            </td>
                                            <td class="py-3 px-4 space-y-1 align-top">
                                                <span class="inline-block bg-brand-inner px-2 py-0.5 rounded border border-brand-border font-semibold text-slate-300" x-text="item.category"></span>
                                                <div class="text-[11px] text-brand-textMuted" x-text="item.base"></div>
                                            </td>
                                            <td class="py-3 px-4 text-slate-300 align-top">
                                                <div><span class="text-brand-textMuted">Size:</span> <span class="font-semibold" x-text="item.size"></span></div>
                                                <div><span class="text-brand-textMuted">Density:</span> <span class="font-semibold" x-text="item.density"></span></div>
                                                <div><span class="text-brand-textMuted">Color:</span> <span class="font-semibold" x-text="item.color"></span></div>
                                            </td>
                                            <td class="py-3 px-4 font-mono font-bold text-white text-sm whitespace-nowrap align-top">
                                                $<span x-text="Number(item.price).toFixed(2)"></span>
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap align-top">
                                                <div class="flex items-center gap-1.5">
                                                    <button type="button" @click="quickUpdateStockQty(item, -1)" class="w-6 h-6 rounded bg-brand-inner hover:bg-slate-700 text-slate-300 hover:text-white flex items-center justify-center font-bold text-xs border border-brand-border transition-colors shadow-sm" title="재고 1개 감소">-</button>
                                                    <span :class="item.stockQty > 5 ? 'bg-emerald-950 text-emerald-400 border-emerald-800' : 'bg-rose-950 text-rose-400 border-rose-800'" class="px-2.5 py-1 rounded-full text-xs font-bold border inline-block text-center min-w-[58px]" x-text="item.stockQty + ' pcs'"></span>
                                                    <button type="button" @click="quickUpdateStockQty(item, 1)" class="w-6 h-6 rounded bg-brand-inner hover:bg-slate-700 text-slate-300 hover:text-white flex items-center justify-center font-bold text-xs border border-brand-border transition-colors shadow-sm" title="재고 1개 증가">+</button>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4 text-right space-x-1 whitespace-nowrap align-top">
                                                <button type="button" @click="startInlineEdit(item)" class="bg-teal-950 hover:bg-teal-900 text-teal-300 border border-teal-700 text-xs px-2.5 py-1.5 rounded transition-colors font-bold shadow-sm" title="라인 상에서 바로 수정">
                                                    <i class="fa-solid fa-pen mr-1"></i>라인수정
                                                </button>
                                                <button type="button" @click="openModal('edit', item)" class="bg-brand-inner hover:bg-slate-700 text-slate-200 border border-brand-border text-xs px-2 py-1.5 rounded transition-colors font-semibold" title="상세 모달 열기">상세</button>
                                                <button type="button" @click="copyItemFromTable(item)" class="bg-indigo-950/80 hover:bg-indigo-900 text-indigo-300 border border-indigo-800 text-xs px-2 py-1.5 rounded transition-colors font-semibold" title="Copy to New Product"><i class="fa-solid fa-copy"></i></button>
                                                <button type="button" @click="deleteItem(item)" class="bg-rose-950/80 hover:bg-rose-900 text-rose-300 border border-rose-800 text-xs px-2 py-1.5 rounded transition-colors font-semibold"><i class="fa-solid fa-trash-can"></i></button>
                                            </td>
                                        </tr>

                                        <!-- 2. INLINE EDITING ROW -->
                                        <tr x-show="editingRowId === item.id" x-cloak class="bg-brand-inner/90 border-2 border-brand-teal shadow-2xl">
                                            <td class="py-3 px-3 align-top min-w-[140px]">
                                                <!-- 큼직한 편집용 사진 박스 -->
                                                <div class="relative w-28 h-28 sm:w-32 sm:h-32 rounded-xl overflow-hidden border-2 border-brand-teal bg-black flex-shrink-0 shadow-lg mb-2">
                                                    <img :src="inlineImagePreview || inlineForm.image_url" class="w-full h-full object-cover">
                                                </div>
                                                
                                                <!-- 사진 변경: 파일 업로드 및 URL 입력 -->
                                                <div class="space-y-1.5 w-28 sm:w-32">
                                                    <label class="block cursor-pointer bg-teal-600 hover:bg-teal-500 text-white text-[10px] font-bold py-1.5 px-2 rounded-lg text-center transition-all shadow">
                                                        <i class="fa-solid fa-cloud-arrow-up mr-1"></i> 사진 파일 선택
                                                        <input type="file" accept="image/*" @change="handleInlineFileSelect($event)" class="hidden">
                                                    </label>
                                                    <template x-if="inlineSelectedFile">
                                                        <div class="text-[9px] text-emerald-300 font-bold bg-emerald-950 px-1.5 py-0.5 rounded border border-emerald-600 truncate flex items-center gap-1">
                                                            <i class="fa-solid fa-circle-check text-emerald-400"></i>
                                                            <span x-text="inlineSelectedFile.name"></span>
                                                        </div>
                                                    </template>
                                                    <input type="text" x-model="inlineForm.image_url" @input="if(!inlineSelectedFile) inlineImagePreview = inlineForm.image_url" placeholder="또는 URL 입력..." class="w-full bg-brand-darkBg border border-brand-border rounded px-1.5 py-1 text-[10px] text-white outline-none focus:border-brand-teal" title="이미지 URL 직접 입력">
                                                </div>
                                            </td>
                                            <td class="py-3 px-3 align-top space-y-1.5 min-w-[220px]">
                                                <div class="flex items-center gap-1.5">
                                                    <input type="text" x-model="inlineForm.sku" placeholder="SKU" class="w-28 bg-brand-darkBg border border-brand-border rounded px-2 py-1 text-white font-mono font-bold text-xs outline-none focus:border-brand-teal" title="SKU Code">
                                                    <select x-model="inlineForm.badge" class="bg-brand-darkBg border border-brand-border rounded px-1.5 py-1 text-white text-[10px] outline-none focus:border-brand-teal font-semibold">
                                                        <option value="">배지 없음</option>
                                                        <option value="New">⭐ New</option>
                                                        <option value="Best Seller">🔥 Best Seller</option>
                                                        <option value="MD's Pick">👑 MD's Pick</option>
                                                        <option value="Limited">⚡ Limited</option>
                                                        <option value="Promotion">🎉 Promotion</option>
                                                    </select>
                                                </div>
                                                <input type="text" x-model="inlineForm.name" placeholder="제품명" class="w-full bg-brand-darkBg border border-brand-border rounded px-2 py-1 text-white font-bold text-xs outline-none focus:border-brand-teal">
                                                <div>
                                                    <input type="text" x-model="inlineForm.description" placeholder="제품 설명 (방문자에게 표시)..." class="w-full bg-brand-darkBg border border-brand-border rounded px-2 py-1 text-slate-200 text-[11px] outline-none focus:border-brand-teal">
                                                </div>
                                            </td>
                                            <td class="py-3 px-3 align-top space-y-1.5 min-w-[130px]">
                                                <select x-model="inlineForm.category" class="w-full bg-brand-darkBg border border-brand-border rounded px-2 py-1 text-white text-xs outline-none focus:border-brand-teal">
                                                    <option value="Men's Toupees">Men's Toupees</option>
                                                    <option value="Women's Toppers">Women's Toppers</option>
                                                    <option value="Skin Systems">Skin Systems</option>
                                                    <option value="Swiss Lace">Swiss Lace</option>
                                                    <option value="PU Perimeter">PU Perimeter</option>
                                                </select>
                                                <input type="text" x-model="inlineForm.base" placeholder="Base Construction" class="w-full bg-brand-darkBg border border-brand-border rounded px-2 py-1 text-white text-[11px] outline-none focus:border-brand-teal">
                                            </td>
                                            <td class="py-3 px-3 align-top space-y-1 min-w-[140px]">
                                                <div class="flex items-center gap-1">
                                                    <span class="text-brand-textMuted text-[10px] w-9">Size:</span>
                                                    <input type="text" x-model="inlineForm.size" class="w-full bg-brand-darkBg border border-brand-border rounded px-1.5 py-0.5 text-white text-[11px] outline-none focus:border-brand-teal">
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <span class="text-brand-textMuted text-[10px] w-9">Density:</span>
                                                    <input type="text" x-model="inlineForm.density" class="w-full bg-brand-darkBg border border-brand-border rounded px-1.5 py-0.5 text-white text-[11px] outline-none focus:border-brand-teal">
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <span class="text-brand-textMuted text-[10px] w-9">Color:</span>
                                                    <input type="text" x-model="inlineForm.color" class="w-full bg-brand-darkBg border border-brand-border rounded px-1.5 py-0.5 text-white text-[11px] outline-none focus:border-brand-teal">
                                                </div>
                                            </td>
                                            <td class="py-3 px-3 align-top">
                                                <div class="flex items-center gap-1">
                                                    <span class="text-slate-400 font-bold">$</span>
                                                    <input type="number" step="0.01" x-model="inlineForm.price" class="w-20 bg-brand-darkBg border border-brand-border rounded px-2 py-1 text-white font-mono font-bold text-xs outline-none focus:border-brand-teal">
                                                </div>
                                            </td>
                                            <td class="py-3 px-3 align-top">
                                                <div class="flex items-center gap-1">
                                                    <input type="number" x-model="inlineForm.stockQty" class="w-16 bg-brand-darkBg border border-brand-border rounded px-2 py-1 text-white font-mono font-bold text-xs outline-none focus:border-brand-teal">
                                                    <span class="text-slate-400 text-[10px]">pcs</span>
                                                </div>
                                            </td>
                                            <td class="py-3 px-3 align-top text-right space-x-1 whitespace-nowrap">
                                                <button type="button" @click="saveInlineEdit()" :disabled="inlineSaving" class="bg-teal-600 hover:bg-teal-500 text-white font-bold text-xs px-3 py-1.5 rounded shadow transition-all inline-flex items-center gap-1">
                                                    <i class="fa-solid fa-check" x-show="!inlineSaving"></i>
                                                    <i class="fa-solid fa-spinner fa-spin" x-show="inlineSaving"></i>
                                                    <span x-text="inlineSaving ? '저장...' : '저장'"></span>
                                                </button>
                                                <button type="button" @click="cancelInlineEdit()" class="bg-slate-700 hover:bg-slate-600 text-slate-200 text-xs px-2.5 py-1.5 rounded transition-all">
                                                    취소
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 2: AI SIMULATOR HAIRSTYLES MANAGER -->
            <div x-show="activeTab === 'ai_styles'" x-cloak class="space-y-4">
                <div class="flex justify-between items-center bg-brand-cardBg border border-brand-border p-4 rounded-xl">
                    <div>
                        <h3 class="text-sm font-bold text-white flex items-center gap-2">
                            <i class="fa-solid fa-wand-magic-sparkles text-brand-tealLight"></i> AI Try-On Simulator Registered Hairstyles
                        </h3>
                        <p class="text-xs text-brand-textMuted mt-0.5">Upload transparent PNG or SVG hairpiece overlays for live customer virtual try-on.</p>
                    </div>
                    <button @click="openAiModal('add')" class="teal-gradient hover:opacity-95 text-white font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded-lg shadow-md transition-all flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Add New AI Hairstyle
                    </button>
                </div>

                <div class="bg-brand-cardBg border border-brand-border rounded-2xl overflow-hidden shadow-xl">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-brand-inner text-brand-textMuted uppercase font-bold text-[11px] border-b border-brand-border">
                            <tr>
                                <th class="py-3.5 px-4">Preview</th>
                                <th class="py-3.5 px-4">Hairstyle Name</th>
                                <th class="py-3.5 px-4">Tagline / Subtitle</th>
                                <th class="py-3.5 px-4">Target Category</th>
                                <th class="py-3.5 px-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-brand-border/60">
                            <template x-for="st in aiStyles" :key="st.id">
                                <tr class="hover:bg-brand-inner/50 transition-colors">
                                    <td class="py-3 px-4">
                                        <div class="w-14 h-14 rounded-lg border border-brand-border bg-slate-950 p-1 flex items-center justify-center">
                                            <img :src="st.overlay" :alt="st.name" class="max-w-full max-h-full object-contain">
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 font-bold text-white text-sm" x-text="st.name"></td>
                                    <td class="py-3 px-4 text-brand-textMuted font-medium" x-text="st.tag"></td>
                                    <td class="py-3 px-4">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase border"
                                              :class="st.gender === 'Women' ? 'bg-rose-950 text-rose-300 border-rose-800' : 'bg-teal-950 text-teal-300 border-teal-800'"
                                              x-text="st.gender || 'Men'"></span>
                                    </td>
                                    <td class="py-3 px-4 text-right space-x-1">
                                        <button @click="openAiModal('edit', st)" class="bg-brand-inner hover:bg-slate-700 text-slate-200 border border-brand-border text-xs px-2.5 py-1.5 rounded transition-colors">Edit</button>
                                        <button @click="deleteAiStyle(st)" class="bg-rose-950/80 hover:bg-rose-900 text-rose-300 border border-rose-800 text-xs px-2.5 py-1.5 rounded transition-colors">Delete</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 3: SPRINGVALE FITTING BOOKINGS MANAGER -->
            <div x-show="activeTab === 'bookings'" x-cloak class="space-y-6">
                <!-- Stats Summary Row -->
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm">
                        <div class="text-xs font-bold text-brand-textMuted uppercase tracking-wider flex items-center justify-between">
                            <span>Total Bookings</span>
                            <i class="fa-solid fa-calendar-days text-brand-tealLight"></i>
                        </div>
                        <div class="text-2xl font-extrabold text-white mt-1" x-text="bookings.length"></div>
                        <div class="text-[11px] text-brand-textMuted mt-0.5">전체 피팅 예약 접수</div>
                    </div>

                    <div class="bg-brand-cardBg border border-amber-500/40 p-4 rounded-xl shadow-sm">
                        <div class="text-xs font-bold text-amber-400 uppercase tracking-wider flex items-center justify-between">
                            <span>Pending Confirmation</span>
                            <i class="fa-solid fa-clock text-amber-400"></i>
                        </div>
                        <div class="text-2xl font-extrabold text-amber-300 mt-1" x-text="pendingBookingsCount"></div>
                        <div class="text-[11px] text-amber-400/80 mt-0.5">컨펌 대기 (WhatsApp 발송 필요)</div>
                    </div>

                    <div class="bg-brand-cardBg border border-emerald-500/40 p-4 rounded-xl shadow-sm">
                        <div class="text-xs font-bold text-emerald-400 uppercase tracking-wider flex items-center justify-between">
                            <span>Confirmed</span>
                            <i class="fa-solid fa-circle-check text-emerald-400"></i>
                        </div>
                        <div class="text-2xl font-extrabold text-emerald-300 mt-1" x-text="confirmedBookingsCount"></div>
                        <div class="text-[11px] text-emerald-400/80 mt-0.5">확정 완료된 피팅 예약</div>
                    </div>

                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm">
                        <div class="text-xs font-bold text-brand-textMuted uppercase tracking-wider flex items-center justify-between">
                            <span>Today's Fitting</span>
                            <i class="fa-solid fa-location-dot text-brand-tealLight"></i>
                        </div>
                        <div class="text-2xl font-extrabold text-brand-tealLight mt-1" x-text="todayBookingsCount"></div>
                        <div class="text-[11px] text-brand-textMuted mt-0.5">오늘 스튜디오 방문 예정</div>
                    </div>
                </div>

                <!-- Controls & Filter Toolbar -->
                <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl flex flex-col md:flex-row gap-4 justify-between items-stretch md:items-center">
                    <!-- Search Input -->
                    <div class="relative flex-1 max-w-md">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" 
                               x-model="bookingSearchQuery" 
                               placeholder="고객명, 연락처, 서비스명 검색..." 
                               class="w-full bg-brand-inner border border-brand-border rounded-lg pl-9 pr-4 py-2 text-white text-xs outline-none focus:border-brand-teal placeholder:text-slate-500">
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Status Filter -->
                        <div class="flex items-center gap-1 bg-brand-inner border border-brand-border p-1 rounded-lg text-xs">
                            <span class="px-2 text-[11px] text-slate-400 font-bold uppercase">Status:</span>
                            <button type="button" @click="bookingStatusFilter = 'All'" :class="bookingStatusFilter === 'All' ? 'bg-brand-teal text-white font-bold' : 'text-slate-300 hover:text-white'" class="px-2.5 py-1 rounded transition-colors">All</button>
                            <button type="button" @click="bookingStatusFilter = 'Pending'" :class="bookingStatusFilter === 'Pending' ? 'bg-amber-600 text-white font-bold' : 'text-slate-300 hover:text-white'" class="px-2.5 py-1 rounded transition-colors">Pending</button>
                            <button type="button" @click="bookingStatusFilter = 'Confirmed'" :class="bookingStatusFilter === 'Confirmed' ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:text-white'" class="px-2.5 py-1 rounded transition-colors">Confirmed</button>
                            <button type="button" @click="bookingStatusFilter = 'Completed'" :class="bookingStatusFilter === 'Completed' ? 'bg-sky-600 text-white font-bold' : 'text-slate-300 hover:text-white'" class="px-2.5 py-1 rounded transition-colors">Completed</button>
                            <button type="button" @click="bookingStatusFilter = 'Cancelled'" :class="bookingStatusFilter === 'Cancelled' ? 'bg-rose-700 text-white font-bold' : 'text-slate-300 hover:text-white'" class="px-2.5 py-1 rounded transition-colors">Cancelled</button>
                        </div>

                        <!-- Date Filter -->
                        <div class="flex items-center gap-1 bg-brand-inner border border-brand-border p-1 rounded-lg text-xs">
                            <span class="px-2 text-[11px] text-slate-400 font-bold uppercase">Date:</span>
                            <button type="button" @click="bookingDateFilter = 'All'" :class="bookingDateFilter === 'All' ? 'bg-brand-teal text-white font-bold' : 'text-slate-300 hover:text-white'" class="px-2.5 py-1 rounded transition-colors">All</button>
                            <button type="button" @click="bookingDateFilter = 'Today'" :class="bookingDateFilter === 'Today' ? 'bg-brand-teal text-white font-bold' : 'text-slate-300 hover:text-white'" class="px-2.5 py-1 rounded transition-colors">Today</button>
                            <button type="button" @click="bookingDateFilter = 'Upcoming'" :class="bookingDateFilter === 'Upcoming' ? 'bg-brand-teal text-white font-bold' : 'text-slate-300 hover:text-white'" class="px-2.5 py-1 rounded transition-colors">Upcoming</button>
                        </div>

                        <!-- Refresh -->
                        <button type="button" @click="loadBookings()" class="px-3 py-2 bg-brand-inner hover:bg-slate-700 border border-brand-border text-slate-200 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all">
                            <i class="fa-solid fa-rotate" :class="{ 'fa-spin': isLoadingBookings }"></i>
                            <span>새로고침</span>
                        </button>
                    </div>
                </div>

                <!-- Bookings Table -->
                <div class="bg-brand-cardBg border border-brand-border rounded-2xl overflow-hidden shadow-xl">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs whitespace-nowrap">
                            <thead class="bg-brand-inner text-brand-textMuted uppercase font-bold text-[11px] border-b border-brand-border">
                                <tr>
                                    <th class="py-3.5 px-4">Booking ID</th>
                                    <th class="py-3.5 px-4">Date & Time</th>
                                    <th class="py-3.5 px-4">Customer</th>
                                    <th class="py-3.5 px-4">WhatsApp / Phone</th>
                                    <th class="py-3.5 px-4">Service</th>
                                    <th class="py-3.5 px-4">Status</th>
                                    <th class="py-3.5 px-4">Notes / Memo</th>
                                    <th class="py-3.5 px-4 text-center">Confirm & WhatsApp</th>
                                    <th class="py-3.5 px-4 text-center">Google Calendar</th>
                                    <th class="py-3.5 px-4 text-right">Manage</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-brand-border/60">
                                <template x-if="filteredBookings.length === 0">
                                    <tr>
                                        <td colspan="10" class="py-12 text-center text-slate-400">
                                            <i class="fa-regular fa-calendar-xmark text-3xl mb-2 text-slate-500 block"></i>
                                            등록된 피팅 예약이 없거나 필터 조건과 일치하는 내역이 없습니다.
                                        </td>
                                    </tr>
                                </template>

                                <template x-for="b in filteredBookings" :key="b.id">
                                    <tr class="hover:bg-brand-inner/40 transition-colors">
                                        <!-- ID -->
                                        <td class="py-3.5 px-4 font-mono font-bold text-brand-tealLight" x-text="'#FIT-' + b.id"></td>

                                        <!-- Date & Time -->
                                        <td class="py-3.5 px-4">
                                            <div class="font-bold text-white flex items-center gap-1.5">
                                                <i class="fa-regular fa-calendar text-brand-tealLight text-xs"></i>
                                                <span x-text="b.booking_date"></span>
                                                <template x-if="isDateToday(b.booking_date)">
                                                    <span class="text-[9px] bg-cyan-950 text-cyan-300 border border-cyan-800 px-1.5 py-0.2 rounded font-extrabold uppercase">Today</span>
                                                </template>
                                            </div>
                                            <div class="text-[11px] text-slate-300 font-mono flex items-center gap-1 mt-0.5">
                                                <i class="fa-regular fa-clock text-slate-400 text-[10px]"></i>
                                                <span x-text="b.booking_time"></span>
                                            </div>
                                        </td>

                                        <!-- Customer -->
                                        <td class="py-3.5 px-4">
                                            <div class="font-bold text-white text-sm" x-text="b.customer_name"></div>
                                            <template x-if="b.email">
                                                <div class="text-[11px] text-slate-400" x-text="b.email"></div>
                                            </template>
                                        </td>

                                        <!-- WhatsApp / Phone -->
                                        <td class="py-3.5 px-4">
                                            <a :href="'https://wa.me/' + formatWhatsAppPhone(b.phone)" target="_blank" class="font-mono text-emerald-400 hover:text-emerald-300 font-semibold flex items-center gap-1.5 hover:underline" title="WhatsApp으로 대화 열기">
                                                <i class="fa-brands fa-whatsapp text-sm"></i>
                                                <span x-text="b.phone"></span>
                                            </a>
                                        </td>

                                        <!-- Service -->
                                        <td class="py-3.5 px-4">
                                            <span class="max-w-[220px] truncate block text-slate-200 font-medium" :title="b.service_type" x-text="b.service_type"></span>
                                        </td>

                                        <!-- Status Badge & Dropdown -->
                                        <td class="py-3.5 px-4">
                                            <select :value="b.status" 
                                                    @change="updateBookingStatus(b.id, $event.target.value)" 
                                                    class="text-xs font-bold rounded-lg px-2.5 py-1.5 outline-none cursor-pointer border"
                                                    :class="{
                                                        'bg-amber-950/90 text-amber-300 border-amber-700': b.status === 'Pending',
                                                        'bg-emerald-950/90 text-emerald-300 border-emerald-700': b.status === 'Confirmed',
                                                        'bg-sky-950/90 text-sky-300 border-sky-700': b.status === 'Completed',
                                                        'bg-rose-950/90 text-rose-300 border-rose-700': b.status === 'Cancelled'
                                                    }">
                                                <option value="Pending">🟡 Pending (대기)</option>
                                                <option value="Confirmed">🟢 Confirmed (확정)</option>
                                                <option value="Completed">🔵 Completed (완료)</option>
                                                <option value="Cancelled">🔴 Cancelled (취소)</option>
                                            </select>
                                        </td>

                                        <!-- Notes -->
                                        <td class="py-3.5 px-4 max-w-[200px]">
                                            <div class="text-[11px] text-slate-300 truncate" :title="b.notes || 'None'" x-text="b.notes || '-'"></div>
                                        </td>

                                        <!-- Confirm & WhatsApp Button -->
                                        <td class="py-3.5 px-4 text-center">
                                            <button type="button" 
                                                    @click="confirmAndWhatsApp(b)" 
                                                    class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs px-3 py-2 rounded-lg shadow transition-all inline-flex items-center gap-1.5 whitespace-nowrap"
                                                    title="예약 상태를 Confirmed로 변경하고 고객에게 WhatsApp 확정 메세지를 보냅니다.">
                                                <i class="fa-brands fa-whatsapp text-sm"></i>
                                                <span x-text="b.status === 'Confirmed' ? 'Re-send WhatsApp' : 'Confirm & WhatsApp'"></span>
                                            </button>
                                        </td>

                                        <!-- Google Calendar Button -->
                                        <td class="py-3.5 px-4 text-center">
                                            <button type="button" 
                                                    @click="addToGoogleCalendar(b)" 
                                                    class="bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs px-3 py-2 rounded-lg shadow transition-all inline-flex items-center gap-1.5 whitespace-nowrap"
                                                    title="이 예약을 Google Calendar에 1클릭으로 저장합니다.">
                                                <i class="fa-regular fa-calendar-plus text-sm"></i>
                                                <span>Google Cal 저장</span>
                                            </button>
                                        </td>

                                        <!-- Manage (Delete) -->
                                        <td class="py-3.5 px-4 text-right">
                                            <button type="button" 
                                                    @click="deleteBooking(b)" 
                                                    class="bg-rose-950/80 hover:bg-rose-900 text-rose-300 border border-rose-800 text-xs px-2.5 py-1.5 rounded transition-colors" 
                                                    title="예약 삭제">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- STOCK ITEM EDIT MODAL -->
    <div x-show="isModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-brand-darkBg/90 backdrop-blur-sm overflow-y-auto">
        <div class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-4xl w-full p-6 space-y-4 shadow-2xl my-6 max-h-[92vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b border-brand-border pb-3">
                <div class="flex items-center gap-2">
                    <h3 class="font-heading font-bold text-white text-lg flex items-center gap-2" x-text="modalMode === 'edit' ? 'Edit Stock Item' : (copiedNotice ? 'Add New Stock Item (Copied Draft)' : 'Add New Stock Item')"></h3>
                    <span x-show="modalMode === 'edit'" class="text-[11px] bg-brand-inner border border-brand-border px-2 py-0.5 rounded text-brand-tealLight font-mono">ID: <span x-text="form.id"></span></span>
                    <span x-show="copiedNotice" class="text-[10px] bg-indigo-500/20 text-indigo-300 border border-indigo-500/40 px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">Copy Mode</span>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" x-show="modalMode === 'edit'" @click="copyAsNewProduct()" class="px-3 py-1.5 bg-indigo-950 hover:bg-indigo-900 border border-indigo-600 text-indigo-200 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all shadow-sm hover:border-indigo-400" title="Copy this product to create a new one">
                        <i class="fa-solid fa-copy text-indigo-400"></i>
                        <span>Copy as New</span>
                    </button>
                    <button @click="isModalOpen = false" class="text-slate-400 hover:text-white p-1"><i class="fa-solid fa-xmark text-lg"></i></button>
                </div>
            </div>

            <!-- Copied Notice Banner -->
            <div x-show="copiedNotice" x-cloak class="p-3 bg-indigo-950/90 border border-indigo-500/70 rounded-xl text-indigo-200 text-xs flex items-center justify-between gap-3 shadow-lg">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-check text-indigo-400 text-base flex-shrink-0"></i>
                    <div>
                        <div class="font-bold text-white flex items-center gap-1.5">
                            <span>Product Copied (제품 복사 완료)</span>
                            <span class="text-[10px] bg-indigo-800/80 text-indigo-200 px-1.5 py-0.5 rounded font-mono">New Product Draft</span>
                        </div>
                        <p class="text-[11px] text-indigo-300 mt-0.5" x-text="copiedNotice"></p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <button type="button" @click="saveStockItem()" class="px-2.5 py-1 bg-teal-600 hover:bg-teal-500 text-white rounded text-xs font-bold flex items-center gap-1 shadow transition-all whitespace-nowrap" title="Save this copied product immediately">
                        <i class="fa-solid fa-bolt"></i> Instant Save
                    </button>
                    <button type="button" @click="copiedNotice = ''" class="text-indigo-400 hover:text-white p-1 text-sm"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </div>

            <form @submit.prevent="saveStockItem()" class="space-y-4 text-xs">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- LEFT COLUMN: index.html 실물 크기 카드 미리보기 -->
                    <div class="lg:col-span-5 flex flex-col items-center">
                        <div class="w-full flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-200 flex items-center gap-1.5">
                                <i class="fa-solid fa-eye text-brand-tealLight"></i> Live Card Preview
                            </span>
                            <span class="text-[10px] text-brand-tealLight bg-brand-teal/20 px-2 py-0.5 rounded font-mono border border-brand-teal/30">index.html 실제 크기</span>
                        </div>

                        <!-- index.html 실물 카드 (동일한 1:1 aspect-square 및 카드 레이아웃) -->
                        <div class="w-full max-w-[280px] bg-brand-cardBg border border-brand-border rounded-2xl overflow-hidden shadow-2xl flex flex-col justify-between group hover:border-brand-teal transition-all">
                            <div>
                                <!-- 이미지 영역: index.html과 100% 동일한 1:1 aspect-square 비율 -->
                                <div class="relative aspect-square w-full overflow-hidden bg-brand-inner flex items-center justify-center">
                                    <template x-if="imagePreview || form.image_url">
                                        <img :src="imagePreview || form.image_url" :alt="form.name || 'Preview'" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!imagePreview && !form.image_url">
                                        <div class="flex flex-col items-center justify-center text-slate-500 p-4 text-center">
                                            <i class="fa-regular fa-image text-4xl mb-2 text-slate-600"></i>
                                            <span class="text-[11px] text-slate-400 font-semibold">이미지가 없습니다</span>
                                        </div>
                                    </template>

                                    <!-- 재고 수량 배지 -->
                                    <div class="absolute top-3 left-3 bg-brand-darkBg/90 text-brand-tealLight text-[10px] font-bold px-2.5 py-1 rounded-md border border-brand-border shadow-md"
                                         x-text="'Stock: ' + (form.stockQty || 0) + ' pcs'"></div>


                                    <!-- Badge Tag Overlay (New, Best Seller, MD's Pick, Limited, Promotion) -->
                                    <template x-if="form.badge">
                                        <div class="absolute bottom-3 left-3 z-10">
                                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider shadow-xl border flex items-center gap-1.5"
                                                  :class="getBadgeClass(form.badge)">
                                                <i :class="getBadgeIcon(form.badge)"></i>
                                                <span x-text="form.badge"></span>
                                            </span>
                                        </div>
                                    </template>
                                </div>

                                <!-- 카드 텍스트 정보 (index.html 카드와 동일) -->
                                <div class="p-4 space-y-2.5 bg-brand-cardBg">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-mono text-brand-tealLight font-bold" x-text="form.sku || 'SKU-DH-...' "></span>
                                        <span class="text-[10px] text-brand-textMuted font-medium" x-text="form.category || 'Category'"></span>
                                    </div>
                                    <h4 class="font-bold text-white text-sm leading-snug truncate" x-text="form.name || '제품명을 입력하세요'"></h4>
                                    
                                    <!-- 제품 설명 실시간 미리보기 -->
                                    <div class="space-y-1">
                                        <span class="text-[9px] text-slate-400 uppercase tracking-wider font-bold block">Description Preview</span>
                                        <p class="text-xs text-brand-textMuted line-clamp-2 leading-relaxed bg-brand-inner/70 p-2 rounded-lg border border-brand-border/40 min-h-[44px]"
                                           x-text="form.description || '제품 설명이 여기에 표시됩니다...'"></p>
                                    </div>

                                    <div class="grid grid-cols-2 gap-1.5 text-[10px] text-brand-textMuted font-mono pt-1">
                                        <div class="bg-brand-inner px-2 py-1 rounded border border-brand-border/60 truncate">
                                            Base: <span class="text-slate-200" x-text="form.base || '-'"></span>
                                        </div>
                                        <div class="bg-brand-inner px-2 py-1 rounded border border-brand-border/60 truncate">
                                            Size: <span class="text-slate-200" x-text="form.size || '-'"></span>
                                        </div>
                                        <div class="bg-brand-inner px-2 py-1 rounded border border-brand-border/60 truncate">
                                            Density: <span class="text-slate-200" x-text="form.density || '-'"></span>
                                        </div>
                                        <div class="bg-brand-inner px-2 py-1 rounded border border-brand-border/60 truncate">
                                            Color: <span class="text-slate-200" x-text="form.color || '-'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 하단 가격 표시 바 -->
                            <div class="p-3 bg-brand-inner/80 border-t border-brand-border flex items-center justify-between">
                                <span class="text-[11px] text-slate-400 font-medium">Price (AUD)</span>
                                <span class="text-base font-extrabold text-brand-tealLight font-mono">$<span x-text="Number(form.price || 0).toFixed(2)"></span></span>
                            </div>
                        </div>
                        
                        <p class="text-[11px] text-slate-400 mt-3 text-center flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-circle-info text-brand-tealLight"></i>
                            <span>index.html에 방문자에게 보이는 실제 1:1 크기입니다.</span>
                        </p>
                    </div>

                    <!-- RIGHT COLUMN: 입력 및 수정 폼 -->
                    <div class="lg:col-span-7 space-y-3.5">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-300 mb-1">SKU Code</label>
                                <input type="text" x-model="form.sku" required class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white font-mono outline-none focus:border-brand-teal">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-300 mb-1">Item Name</label>
                                <input type="text" x-model="form.name" required class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-300 mb-1">Category</label>
                                <select x-model="form.category" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal">
                                    <option value="Men's Toupees">Men's Toupees</option>
                                    <option value="Women's Toppers">Women's Toppers</option>
                                    <option value="Skin Systems">Skin Systems</option>
                                    <option value="Swiss Lace">Swiss Lace</option>
                                    <option value="PU Perimeter">PU Perimeter</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-300 mb-1">Base Construction</label>
                                <input type="text" x-model="form.base" required class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal">
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-300 mb-1">Badge Tag / 제품 구분 (사진 위 표시)</label>
                            <select x-model="form.badge" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal font-semibold">
                                <option value="">None (선택 안함)</option>
                                <option value="New">⭐ New (신상품)</option>
                                <option value="Best Seller">🔥 Best Seller (베스트셀러)</option>
                                <option value="MD's Pick">👑 MD's Pick (MD 추천)</option>
                                <option value="Limited">⚡ Limited (한정판)</option>
                                <option value="Promotion">🎉 Promotion (특가/프로모션)</option>
                            </select>
                        </div>

                        <!-- 제품 설명 텍스트 필드 (Textarea로 넉넉하게 편집 가능) -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block font-bold text-slate-200">
                                    <i class="fa-solid fa-align-left text-brand-tealLight mr-1"></i> Product Description / 제품 설명
                                </label>
                                <span class="text-[11px] text-brand-tealLight font-medium">수정 시 좌측 프리뷰에 실시간 반영</span>
                            </div>
                            <textarea x-model="form.description" 
                                      rows="3" 
                                      placeholder="e.g. 100% human hair, ultra-soft natural look, invisible hairline..." 
                                      class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal text-xs leading-relaxed resize-y"></textarea>
                        </div>

                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label class="block font-bold text-slate-300 mb-1">Size</label>
                                <input type="text" x-model="form.size" required class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-2.5 py-2 text-white outline-none focus:border-brand-teal">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-300 mb-1">Density</label>
                                <input type="text" x-model="form.density" required class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-2.5 py-2 text-white outline-none focus:border-brand-teal">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-300 mb-1">Color</label>
                                <input type="text" x-model="form.color" required class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-2.5 py-2 text-white outline-none focus:border-brand-teal">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-300 mb-1">Price (AUD)</label>
                                <input type="number" step="0.01" x-model="form.price" required class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white font-mono outline-none focus:border-brand-teal">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-300 mb-1">Stock Quantity</label>
                                <input type="number" x-model="form.stockQty" required class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white font-mono outline-none focus:border-brand-teal">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-300 mb-1">Upload Product Image File</label>
                                <input type="file" accept="image/*" @change="handleFileSelect($event)" class="w-full text-slate-300 text-xs file:mr-2 file:py-1.5 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:bg-brand-inner file:text-brand-tealLight file:cursor-pointer hover:file:bg-slate-700">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-300 mb-1">Or Image URL</label>
                                <input type="text" x-model="form.image_url" @input="if(!selectedFile) imagePreview = form.image_url" placeholder="https://..." class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MODAL FOOTER BUTTONS -->
                <div class="flex justify-between items-center pt-3 border-t border-brand-border">
                    <div>
                        <button type="button" x-show="modalMode === 'edit'" @click="copyAsNewProduct()" class="px-3.5 py-2 bg-indigo-950 hover:bg-indigo-900 border border-indigo-600 text-indigo-200 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all shadow-sm hover:border-indigo-400">
                            <i class="fa-solid fa-copy text-indigo-400"></i>
                            <span>Copy as New Product (새 제품으로 복사)</span>
                        </button>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" @click="isModalOpen = false" class="px-4 py-2 bg-brand-inner border border-brand-border rounded-lg text-slate-300 hover:text-white font-semibold">Cancel</button>
                        <button type="submit" class="px-4 py-2 teal-gradient rounded-lg text-white font-bold" x-text="modalMode === 'edit' ? 'Save Stock' : (copiedNotice ? 'Create Copied Product' : 'Save Stock')"></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- AI HAIRSTYLE MODAL -->
    <div x-show="aiModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-brand-darkBg/90 backdrop-blur-sm">
        <div class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl">
            <div class="flex justify-between items-center border-b border-brand-border pb-3">
                <h3 class="font-heading font-bold text-white text-lg" x-text="aiModalMode === 'edit' ? 'Edit AI Hairstyle' : 'Add New AI Hairstyle'"></h3>
                <button @click="aiModalOpen = false" class="text-slate-400 hover:text-white p-1"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form @submit.prevent="saveAiStyle()" class="space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-300 mb-1">Hairstyle Name (e.g. Crew Cut, Gentleman)</label>
                    <input type="text" x-model="aiForm.name" required placeholder="e.g. Textured Crop" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal">
                </div>
                <div>
                    <label class="block font-bold text-slate-300 mb-1">Tagline / Subtitle (e.g. Clean Short Fade)</label>
                    <input type="text" x-model="aiForm.tag" placeholder="e.g. Modern Layered Fringe" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal">
                </div>
                <div>
                    <label class="block font-bold text-slate-300 mb-1">Category / Gender</label>
                    <select x-model="aiForm.gender" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal">
                        <option value="Men">Men's Style</option>
                        <option value="Women">Women's Style</option>
                        <option value="Unisex">Unisex</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-300 mb-1">Option A: Upload New Transparent Overlay Image (PNG or SVG)</label>
                    <input type="file" id="aiFileInput" accept="image/png,image/svg+xml,image/webp,image/jpeg" @change="handleAiFileSelect($event)" class="w-full text-slate-300 text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-teal file:text-white hover:file:bg-teal-600 cursor-pointer">
                    <template x-if="aiSelectedFileName">
                        <div class="mt-2 p-2 rounded-lg bg-emerald-950/90 border border-emerald-700 text-emerald-300 text-[11px] font-bold flex items-center justify-between">
                            <span><i class="fa-solid fa-circle-check mr-1.5 text-emerald-400"></i> New File Attached: <span x-text="aiSelectedFileName" class="underline"></span></span>
                            <button type="button" @click="clearAiSelectedFile()" class="text-rose-400 hover:text-white text-[10px] underline ml-2">Clear File</button>
                        </div>
                    </template>
                    <div class="text-[10px] text-brand-textMuted mt-1">Uploading a new file will automatically replace the existing image URL upon saving.</div>
                </div>
                <div>
                    <label class="block font-bold text-slate-300 mb-1">Option B: Image URL / SVG Data URI</label>
                    <input type="text" x-model="aiForm.overlay_url" :disabled="!!aiSelectedFileName" :class="aiSelectedFileName ? 'opacity-50 bg-slate-900 cursor-not-allowed text-slate-400' : 'bg-brand-darkBg text-white'" :placeholder="aiSelectedFileName ? 'File attached above (URL text box cleared)...' : 'https://example.com/hair.png or data:image/svg+xml;...'" class="w-full border border-brand-border rounded-lg px-3 py-2 outline-none focus:border-brand-teal font-mono text-[11px]">
                    <div x-show="aiSelectedFileName" class="text-[10px] text-amber-400 font-bold mt-1"><i class="fa-solid fa-circle-info mr-1"></i> URL text box cleared & disabled because file "<span x-text="aiSelectedFileName"></span>" is selected above.</div>
                </div>

                <div x-show="aiImagePreview || aiForm.overlay_url" class="p-3 bg-slate-950 border border-brand-border rounded-xl flex items-center gap-3">
                    <div class="w-16 h-16 rounded bg-black p-1 flex items-center justify-center border border-brand-border">
                        <img :src="aiImagePreview || aiForm.overlay_url" class="max-w-full max-h-full object-contain">
                    </div>
                    <div class="text-[11px] text-slate-300 font-bold">Transparent Hair Overlay Preview</div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="aiModalOpen = false" class="px-4 py-2 bg-brand-inner border border-brand-border rounded-lg text-slate-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 teal-gradient rounded-lg text-white font-bold">Save Hairstyle</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ALPINE LOGIC -->
    <script>
        function stockManagerApp() {
            return {
                activeTab: 'stock',
                isAuthenticated: false,
                isLoggingIn: false,
                loginError: '',
                lang: localStorage.getItem('dh_lang') || 'en',
                setLang(l) {
                    this.lang = l;
                    localStorage.setItem('dh_lang', l);
                },
                currentUser: '',
                showPassword: false,
                loginForm: { email: 'shc9607@gmail.com', password: '' },

                items: [],
                searchQuery: '',
                selectedCategory: 'All',

                // Sorting state (name, category, size, price, stockQty)
                sortColumn: '',
                sortDirection: 'asc',

                // Inline row edit state
                editingRowId: null,
                inlineForm: {},
                inlineSaving: false,
                inlineSelectedFile: null,
                inlineImagePreview: '',
                toastMessage: '',
                toastTimeout: null,

                isModalOpen: false,
                modalMode: 'add',
                copiedNotice: '',
                form: { id: null, sku: '', name: '', category: '', type: '', base: '', size: '', density: '', color: '', price: 0, stockQty: 0, image_url: '', description: '', badge: '' },
                selectedFile: null,
                imagePreview: '',

                // AI Hairstyles state
                aiStyles: [],
                aiModalOpen: false,
                aiModalMode: 'add',
                aiForm: { id: null, name: '', tag: '', gender: 'Men', overlay_url: '' },
                aiSelectedFile: null,
                aiSelectedFileName: '',
                previousOverlayUrl: '',
                aiImagePreview: '',

                // Fitting Bookings state
                bookings: [],
                bookingSearchQuery: '',
                bookingStatusFilter: 'All',
                bookingDateFilter: 'All',
                isLoadingBookings: false,

                async init() {
                    await this.checkAuth();
                },

                get totalQty() {
                    return this.items.reduce((sum, item) => sum + parseInt(item.stockQty || 0), 0);
                },

                get pendingBookingsCount() {
                    return this.bookings.filter(b => b.status === 'Pending').length;
                },

                get confirmedBookingsCount() {
                    return this.bookings.filter(b => b.status === 'Confirmed').length;
                },

                get todayBookingsCount() {
                    const today = new Date().toISOString().slice(0, 10);
                    return this.bookings.filter(b => b.booking_date === today).length;
                },

                get filteredBookings() {
                    let list = this.bookings;
                    const query = (this.bookingSearchQuery || '').toLowerCase().trim();
                    if (query) {
                        list = list.filter(b => 
                            (b.customer_name && b.customer_name.toLowerCase().includes(query)) ||
                            (b.phone && b.phone.toLowerCase().includes(query)) ||
                            (b.service_type && b.service_type.toLowerCase().includes(query)) ||
                            (b.notes && b.notes.toLowerCase().includes(query)) ||
                            ('fit-' + b.id).includes(query)
                        );
                    }
                    if (this.bookingStatusFilter !== 'All') {
                        list = list.filter(b => b.status === this.bookingStatusFilter);
                    }
                    if (this.bookingDateFilter === 'Today') {
                        const today = new Date().toISOString().slice(0, 10);
                        list = list.filter(b => b.booking_date === today);
                    } else if (this.bookingDateFilter === 'Upcoming') {
                        const today = new Date().toISOString().slice(0, 10);
                        list = list.filter(b => b.booking_date >= today);
                    }
                    return list;
                },

                get filteredItems() {
                    let result = this.items.filter(item => {
                        const matchesCategory = this.selectedCategory === 'All' || item.category === this.selectedCategory;
                        const query = this.searchQuery.toLowerCase();
                        const matchesQuery = !query || 
                            item.name.toLowerCase().includes(query) || 
                            item.sku.toLowerCase().includes(query) || 
                            item.base.toLowerCase().includes(query);
                        return matchesCategory && matchesQuery;
                    });

                    if (this.sortColumn) {
                        const col = this.sortColumn;
                        const dir = this.sortDirection === 'asc' ? 1 : -1;

                        result = [...result].sort((a, b) => {
                            let valA = a[col];
                            let valB = b[col];

                            // 숫자형 정렬: price, stockQty
                            if (col === 'price' || col === 'stockQty') {
                                valA = parseFloat(valA) || 0;
                                valB = parseFloat(valB) || 0;
                                return (valA - valB) * dir;
                            }

                            // 문자열 정렬: name, category, size
                            valA = (valA || '').toString().toLowerCase();
                            valB = (valB || '').toString().toLowerCase();
                            return valA.localeCompare(valB) * dir;
                        });
                    }

                    return result;
                },

                sortBy(col) {
                    if (this.sortColumn === col) {
                        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.sortColumn = col;
                        this.sortDirection = (col === 'price' || col === 'stockQty') ? 'desc' : 'asc';
                    }
                },

                getSortIcon(col) {
                    if (this.sortColumn !== col) {
                        return 'fa-sort text-slate-500 opacity-40 group-hover:opacity-100';
                    }
                    return this.sortDirection === 'asc' 
                        ? 'fa-sort-up text-brand-tealLight scale-125' 
                        : 'fa-sort-down text-brand-tealLight scale-125';
                },

                applySelectSort(val) {
                    if (val === 'id-desc') {
                        this.sortColumn = '';
                        this.sortDirection = 'asc';
                        return;
                    }
                    const [col, dir] = val.split('-');
                    this.sortColumn = col;
                    this.sortDirection = dir;
                },

                async checkAuth() {
                    try {
                        const res = await fetch('/admin_stock_api.php?action=check_auth');
                        const data = await res.json();
                        if (data.status === 'authenticated') {
                            this.isAuthenticated = true;
                            this.currentUser = data.user;
                            await this.loadItems();
                            await this.loadAiStyles();
                            await this.loadBookings();
                        } else {
                            this.isAuthenticated = false;
                        }
                    } catch (e) {
                        this.isAuthenticated = false;
                    }
                },

                async login() {
                    this.isLoggingIn = true;
                    this.loginError = '';

                    const formData = new FormData();
                    formData.append('action', 'login');
                    formData.append('email', this.loginForm.email);
                    formData.append('password', this.loginForm.password);

                    try {
                        const res = await fetch('/admin_stock_api.php', { method: 'POST', body: formData });
                        const data = await res.json();
                        if (data.status === 'success') {
                            this.isAuthenticated = true;
                            this.currentUser = data.user;
                            this.loginForm.password = '';
                            await this.loadItems();
                            await this.loadAiStyles();
                            await this.loadBookings();
                        } else {
                            this.loginError = data.message || 'Login failed.';
                        }
                    } catch (e) {
                        this.loginError = 'Network error during login.';
                    } finally {
                        this.isLoggingIn = false;
                    }
                },

                async logout() {
                    try {
                        await fetch('/admin_stock_api.php?action=logout');
                        this.isAuthenticated = false;
                        this.currentUser = '';
                        this.items = [];
                    } catch (e) {
                        this.isAuthenticated = false;
                    }
                },

                async loadItems() {
                    try {
                        const res = await fetch('/admin_stock_api.php?action=list');
                        const data = await res.json();
                        if (data.status === 'success') {
                            this.items = data.data;
                        }
                    } catch (e) {
                        console.log('Error loading items:', e);
                    }
                },

                async loadAiStyles() {
                    try {
                        const res = await fetch('/admin_stock_api.php?action=list_ai_styles&t=' + Date.now());
                        const data = await res.json();
                        if (data.status === 'success') {
                            this.aiStyles = data.data;
                        }
                    } catch (e) {
                        console.log('Error loading AI styles:', e);
                    }
                },

                openModal(mode, item = null) {
                    this.modalMode = mode;
                    this.selectedFile = null;
                    this.copiedNotice = '';
                    if (mode === 'edit' && item) {
                        this.form = { ...item, image_url: item.image, description: item.description || '', badge: item.badge || '' };
                        this.imagePreview = item.image;
                    } else {
                        this.form = {
                            id: null,
                            sku: 'SKU-DH-' + (100 + this.items.length + 1),
                            name: '', category: "Men's Toupees", type: "Skin Systems",
                            base: "0.03mm Skin", size: '8x10"', density: '90%',
                            color: '#1B Off Black', price: 450, stockQty: 10, image_url: '',
                            description: '', badge: ''
                        };
                        this.imagePreview = '';
                    }
                    this.isModalOpen = true;
                },

                copyAsNewProduct() {
                    if (!this.form.name && !this.form.sku) {
                        alert('복사할 제품 정보가 없습니다.');
                        return;
                    }

                    const originalSku = this.form.sku || 'SKU';
                    const originalName = this.form.name || 'Product';

                    // Generate unique new SKU
                    let newSku = `${originalSku}-COPY`;
                    let counter = 1;
                    while (this.items.some(item => item.sku && item.sku.toLowerCase() === newSku.toLowerCase())) {
                        counter++;
                        newSku = `${originalSku}-COPY-${counter}`;
                    }

                    // New name
                    let newName = originalName;
                    if (!newName.includes('(Copy)')) {
                        newName = `${newName} (Copy)`;
                    }

                    // Convert modal to Add New mode with copied specs
                    this.modalMode = 'add';
                    this.form.id = null; // Important: Clear ID so backend performs INSERT, not UPDATE
                    this.form.sku = newSku;
                    this.form.name = newName;
                    if (!this.form.image_url && this.imagePreview) {
                        this.form.image_url = this.imagePreview;
                    }
                    this.selectedFile = null;

                    this.copiedNotice = `"${originalSku}" 제품 정보가 복사되었습니다. SKU 및 세부 사양을 검토/수정한 후 저장하세요.`;
                },

                copyItemFromTable(item) {
                    this.openModal('edit', item);
                    this.copyAsNewProduct();
                },

                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.selectedFile = file;
                        const reader = new FileReader();
                        reader.onload = (e) => { this.imagePreview = e.target.result; };
                        reader.readAsDataURL(file);
                    }
                },

                async saveStockItem() {
                    const formData = new FormData();
                    formData.append('action', 'save');
                    if (this.form.id) formData.append('id', this.form.id);
                    formData.append('sku', this.form.sku);
                    formData.append('name', this.form.name);
                    formData.append('category', this.form.category);
                    formData.append('type', this.form.type);
                    formData.append('base', this.form.base);
                    formData.append('size', this.form.size);
                    formData.append('density', this.form.density);
                    formData.append('color', this.form.color);
                    formData.append('price', this.form.price);
                    formData.append('stockQty', this.form.stockQty);
                    formData.append('description', this.form.description || '');
                    formData.append('badge', this.form.badge || '');
                    formData.append('image_url', this.form.image_url);
                    if (this.selectedFile) formData.append('image_file', this.selectedFile);

                    try {
                        const res = await fetch('/admin_stock_api.php', { method: 'POST', body: formData });
                        const result = await res.json();
                        if (result.status === 'success') {
                            alert(result.message);
                            this.isModalOpen = false;
                            this.copiedNotice = '';
                            await this.loadItems();
                        } else {
                            alert('Error: ' + result.message);
                        }
                    } catch (e) {
                        alert('Network error saving stock item.');
                    }
                },

                async deleteItem(item) {
                    if (confirm(`Delete "${item.name}" (${item.sku})?`)) {
                        try {
                            const res = await fetch(`/admin_stock_api.php?action=delete&id=${item.id}`);
                            const result = await res.json();
                            if (result.status === 'success') {
                                alert(result.message);
                                await this.loadItems();
                            }
                        } catch (e) { alert('Network error deleting item.'); }
                    }
                },

                showToast(msg) {
                    this.toastMessage = msg;
                    if (this.toastTimeout) clearTimeout(this.toastTimeout);
                    this.toastTimeout = setTimeout(() => { this.toastMessage = ''; }, 3500);
                },

                startInlineEdit(item) {
                    this.editingRowId = item.id;
                    this.inlineSelectedFile = null;
                    this.inlineImagePreview = item.image || '';
                    this.inlineForm = { 
                        ...item, 
                        price: parseFloat(item.price) || 0,
                        stockQty: parseInt(item.stockQty) || 0,
                        description: item.description || '',
                        badge: item.badge || '',
                        image_url: item.image || ''
                    };
                },

                cancelInlineEdit() {
                    this.editingRowId = null;
                    this.inlineSelectedFile = null;
                    this.inlineImagePreview = '';
                    this.inlineForm = {};
                },

                handleInlineFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.inlineSelectedFile = file;
                        const reader = new FileReader();
                        reader.onload = (e) => { this.inlineImagePreview = e.target.result; };
                        reader.readAsDataURL(file);
                    }
                },

                async saveInlineEdit() {
                    if (!this.inlineForm.sku || !this.inlineForm.name) {
                        alert('SKU와 제품명을 입력해주세요.');
                        return;
                    }
                    this.inlineSaving = true;
                    try {
                        const formData = new FormData();
                        formData.append('action', 'save');
                        formData.append('id', this.inlineForm.id);
                        formData.append('sku', this.inlineForm.sku);
                        formData.append('name', this.inlineForm.name);
                        formData.append('category', this.inlineForm.category);
                        formData.append('type', this.inlineForm.type || '');
                        formData.append('base', this.inlineForm.base);
                        formData.append('size', this.inlineForm.size);
                        formData.append('density', this.inlineForm.density);
                        formData.append('color', this.inlineForm.color);
                        formData.append('price', this.inlineForm.price);
                        formData.append('stockQty', this.inlineForm.stockQty);
                        formData.append('description', this.inlineForm.description || '');
                        formData.append('badge', this.inlineForm.badge || '');
                        formData.append('image_url', this.inlineForm.image_url || this.inlineForm.image || '');
                        if (this.inlineSelectedFile) {
                            formData.append('image_file', this.inlineSelectedFile);
                        }

                        const res = await fetch('/admin_stock_api.php', { method: 'POST', body: formData });
                        const result = await res.json();
                        if (result.status === 'success') {
                            this.showToast(`✅ [${this.inlineForm.sku}] "${this.inlineForm.name}" 정보 및 사진이 저장되었습니다.`);
                            this.editingRowId = null;
                            this.inlineSelectedFile = null;
                            this.inlineImagePreview = '';
                            await this.loadItems();
                        } else {
                            alert('저장 실패: ' + result.message);
                        }
                    } catch (e) {
                        alert('네트워크 오류가 발생했습니다.');
                    } finally {
                        this.inlineSaving = false;
                    }
                },

                async quickUploadImage(item, event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    const formData = new FormData();
                    formData.append('action', 'save');
                    formData.append('id', item.id);
                    formData.append('sku', item.sku);
                    formData.append('name', item.name);
                    formData.append('category', item.category);
                    formData.append('type', item.type || '');
                    formData.append('base', item.base);
                    formData.append('size', item.size);
                    formData.append('density', item.density);
                    formData.append('color', item.color);
                    formData.append('price', item.price);
                    formData.append('stockQty', item.stockQty);
                    formData.append('description', item.description || '');
                    formData.append('badge', item.badge || '');
                    formData.append('image_file', file);

                    this.showToast(`📸 [${item.sku}] 새 사진을 업로드하는 중...`);

                    try {
                        const res = await fetch('/admin_stock_api.php', { method: 'POST', body: formData });
                        const result = await res.json();
                        if (result.status === 'success') {
                            this.showToast(`✅ [${item.sku}] 사진이 즉시 변경되었습니다!`);
                            await this.loadItems();
                        } else {
                            alert('사진 업로드 실패: ' + result.message);
                        }
                    } catch (e) {
                        alert('네트워크 오류로 사진 업로드 실패');
                    }
                },

                async quickUpdateStockQty(item, delta) {
                    const newQty = Math.max(0, parseInt(item.stockQty || 0) + delta);
                    if (newQty === parseInt(item.stockQty)) return;
                    
                    const oldQty = item.stockQty;
                    item.stockQty = newQty; // UI 즉시 반영

                    try {
                        const formData = new FormData();
                        formData.append('action', 'save');
                        formData.append('id', item.id);
                        formData.append('sku', item.sku);
                        formData.append('name', item.name);
                        formData.append('category', item.category);
                        formData.append('type', item.type || '');
                        formData.append('base', item.base);
                        formData.append('size', item.size);
                        formData.append('density', item.density);
                        formData.append('color', item.color);
                        formData.append('price', item.price);
                        formData.append('stockQty', newQty);
                        formData.append('description', item.description || '');
                        formData.append('badge', item.badge || '');
                        formData.append('image_url', item.image || '');

                        const res = await fetch('/admin_stock_api.php', { method: 'POST', body: formData });
                        const result = await res.json();
                        if (result.status === 'success') {
                            this.showToast(`📦 [${item.sku}] 재고가 ${newQty}개로 변경되었습니다.`);
                        } else {
                            item.stockQty = oldQty;
                            alert('재고 변경 실패: ' + result.message);
                        }
                    } catch (e) {
                        item.stockQty = oldQty;
                        alert('네트워크 오류');
                    }
                },

                getBadgeClass(badge) {
                    switch (badge) {
                        case 'New':
                            return 'bg-cyan-500/90 text-slate-950 border-cyan-400 font-black';
                        case 'Best Seller':
                            return 'bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 border-amber-300 font-black';
                        case "MD's Pick":
                            return 'bg-purple-600 text-white border-purple-400 font-black';
                        case 'Limited':
                            return 'bg-rose-600 text-white border-rose-400 font-black';
                        case 'Promotion':
                            return 'bg-emerald-500 text-slate-950 border-emerald-300 font-black';
                        default:
                            return 'bg-slate-800 text-slate-200 border-slate-700';
                    }
                },

                getBadgeIcon(badge) {
                    switch (badge) {
                        case 'New': return 'fa-solid fa-star';
                        case 'Best Seller': return 'fa-solid fa-fire';
                        case "MD's Pick": return 'fa-solid fa-crown';
                        case 'Limited': return 'fa-solid fa-bolt';
                        case 'Promotion': return 'fa-solid fa-tag';
                        default: return 'fa-solid fa-tag';
                    }
                },

                openAiModal(mode, style = null) {
                    this.aiModalMode = mode;
                    this.aiSelectedFile = null;
                    this.aiSelectedFileName = '';
                    this.previousOverlayUrl = '';
                    const fileInput = document.getElementById('aiFileInput');
                    if (fileInput) fileInput.value = '';

                    if (mode === 'edit' && style) {
                        this.aiForm = { ...style, overlay_url: style.overlay };
                        this.previousOverlayUrl = style.overlay || '';
                        this.aiImagePreview = style.overlay;
                    } else {
                        this.aiForm = { id: null, name: '', tag: '', gender: 'Men', overlay_url: '' };
                        this.aiImagePreview = '';
                    }
                    this.aiModalOpen = true;
                },

                handleAiFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.aiSelectedFile = file;
                        this.aiSelectedFileName = file.name;
                        if (this.aiForm.overlay_url) {
                            this.previousOverlayUrl = this.aiForm.overlay_url;
                        }
                        this.aiForm.overlay_url = '';

                        const reader = new FileReader();
                        reader.onload = (e) => { 
                            this.aiImagePreview = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        this.clearAiSelectedFile();
                    }
                },

                clearAiSelectedFile() {
                    this.aiSelectedFile = null;
                    this.aiSelectedFileName = '';
                    const fileInput = document.getElementById('aiFileInput');
                    if (fileInput) fileInput.value = '';

                    if (this.previousOverlayUrl) {
                        this.aiForm.overlay_url = this.previousOverlayUrl;
                        this.aiImagePreview = this.previousOverlayUrl;
                    } else {
                        this.aiForm.overlay_url = '';
                        this.aiImagePreview = '';
                    }
                },

                async saveAiStyle() {
                    const formData = new FormData();
                    formData.append('action', 'save_ai_style');
                    if (this.aiForm.id) formData.append('id', this.aiForm.id);
                    formData.append('name', this.aiForm.name || '');
                    formData.append('tag', this.aiForm.tag || '');
                    formData.append('gender', this.aiForm.gender || 'Men');
                    
                    if (this.aiSelectedFile) {
                        formData.append('overlay_file', this.aiSelectedFile);
                    } else {
                        formData.append('overlay_url', this.aiForm.overlay_url || this.previousOverlayUrl || '');
                    }

                    try {
                        const res = await fetch('/admin_stock_api.php', { method: 'POST', body: formData });
                        const text = await res.text();
                        let result;
                        try {
                            result = JSON.parse(text);
                        } catch(parseErr) {
                            alert('Server response error: ' + text.substring(0, 150));
                            return;
                        }

                        if (result && result.status === 'success') {
                            alert(result.message || 'AI Hairstyle saved successfully.');
                            this.aiModalOpen = false;
                            this.aiSelectedFile = null;
                            this.aiSelectedFileName = '';
                            this.previousOverlayUrl = '';
                            await this.loadAiStyles();
                        } else {
                            alert('Error: ' + (result?.message || 'Failed to save AI Hairstyle.'));
                        }
                    } catch (e) {
                        alert('Network error saving AI style: ' + e.message);
                    }
                },

                async deleteAiStyle(style) {
                    if (confirm(`Delete AI Hairstyle "${style.name}"?`)) {
                        try {
                            const res = await fetch(`/admin_stock_api.php?action=delete_ai_style&id=${style.id}`);
                            const text = await res.text();
                            let result = JSON.parse(text);
                            if (result && result.status === 'success') {
                                alert(result.message || 'AI Hairstyle deleted.');
                                await this.loadAiStyles();
                            } else {
                                alert('Error: ' + (result?.message || 'Could not delete style.'));
                            }
                        } catch (e) { alert('Error deleting AI hairstyle.'); }
                    }
                },

                isDateToday(dateStr) {
                    if (!dateStr) return false;
                    const today = new Date().toISOString().slice(0, 10);
                    return dateStr === today;
                },

                async loadBookings() {
                    this.isLoadingBookings = true;
                    try {
                        const res = await fetch('/api_booking.php?action=list_bookings');
                        const data = await res.json();
                        if (data.status === 'success') {
                            this.bookings = data.data || [];
                        }
                    } catch (e) {
                        console.error('Failed to load bookings:', e);
                    } finally {
                        this.isLoadingBookings = false;
                    }
                },

                async updateBookingStatus(id, newStatus) {
                    try {
                        const formData = new FormData();
                        formData.append('id', id);
                        formData.append('status', newStatus);
                        const res = await fetch('/api_booking.php?action=update_status', {
                            method: 'POST',
                            body: formData
                        });
                        const data = await res.json();
                        if (data.status === 'success') {
                            const b = this.bookings.find(x => x.id == id);
                            if (b) b.status = newStatus;
                            this.showToast(`Booking #${id} status changed to ${newStatus}`);
                        } else {
                            alert(data.message || 'Status update failed.');
                        }
                    } catch (e) {
                        alert('Server error updating status.');
                    }
                },

                async deleteBooking(b) {
                    if (!confirm(`Delete booking #${b.id} for ${b.customer_name}?`)) return;
                    try {
                        const res = await fetch(`/api_booking.php?action=delete_booking&id=${b.id}`);
                        const data = await res.json();
                        if (data.status === 'success') {
                            this.bookings = this.bookings.filter(x => x.id !== b.id);
                            this.showToast(`Booking #${b.id} deleted.`);
                        } else {
                            alert(data.message || 'Failed to delete booking.');
                        }
                    } catch (e) {
                        alert('Error deleting booking.');
                    }
                },

                formatWhatsAppPhone(phone) {
                    if (!phone) return '';
                    let clean = phone.replace(/[^0-9]/g, '');
                    if (clean.startsWith('0') && clean.length === 10) {
                        clean = '61' + clean.substring(1);
                    } else if (clean.startsWith('61')) {
                        // already has 61
                    } else if (clean.length === 9 && clean.startsWith('4')) {
                        clean = '61' + clean;
                    }
                    return clean;
                },

                async confirmAndWhatsApp(b) {
                    // 1. If status is not Confirmed, update it in DB
                    if (b.status !== 'Confirmed') {
                        await this.updateBookingStatus(b.id, 'Confirmed');
                    }

                    // 2. Prepare WhatsApp message
                    const cleanPhone = this.formatWhatsAppPhone(b.phone);
                    const msg = `Hi ${b.customer_name}, this is David Hair Studio Springvale.\n\nYour fitting appointment has been CONFIRMED:\n📅 Date: ${b.booking_date}\n⏰ Time: ${b.booking_time}\n✂️ Service: ${b.service_type}\n📍 Studio: David Hair Studio, Springvale VIC, Melbourne\n\nPlease let us know if you have any questions or need to reschedule. See you soon!`;

                    const url = `https://wa.me/${cleanPhone}?text=${encodeURIComponent(msg)}`;
                    window.open(url, '_blank');
                    this.showToast(`Booking #${b.id} Confirmed & WhatsApp launched!`);
                },

                addToGoogleCalendar(b) {
                    // Parse time to hours (e.g. 11:00 AM, 01:00 PM)
                    let match = (b.booking_time || '').match(/(\d+):(\d+)\s*(AM|PM)?/i);
                    let startH = 11;
                    let endH = 12;
                    if (match) {
                        let h = parseInt(match[1]);
                        let ampm = match[3] ? match[3].toUpperCase() : '';
                        if (ampm === 'PM' && h < 12) h += 12;
                        if (ampm === 'AM' && h === 12) h = 0;
                        startH = h;
                        endH = h + 1;
                    }

                    const dateClean = (b.booking_date || '').replace(/[^0-9]/g, '');
                    const startStr = dateClean + 'T' + String(startH).padStart(2, '0') + '0000';
                    const endStr = dateClean + 'T' + String(endH).padStart(2, '0') + '0000';
                    const datesParam = `${startStr}/${endStr}`;

                    const title = `[David Hair Fitting] ${b.customer_name} (${b.booking_time})`;
                    const details = `Customer: ${b.customer_name}\nPhone: ${b.phone}\nService: ${b.service_type}\nBooking ID: #FIT-${b.id}\nStatus: ${b.status}\nNotes: ${b.notes || 'None'}`;
                    const location = `David Hair Fitting Studio, Springvale VIC, Melbourne, Australia`;

                    const gcalUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(title)}&dates=${datesParam}&details=${encodeURIComponent(details)}&location=${encodeURIComponent(location)}`;
                    window.open(gcalUrl, '_blank');
                    this.showToast(`Google Calendar link opened for #${b.id}!`);
                }
            }
        }
    </script>
</body>
</html>
