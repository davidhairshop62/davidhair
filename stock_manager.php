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

    <!-- html2pdf.js for Client-Side PDF Generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .teal-gradient { background: linear-gradient(135deg, #0D9488 0%, #0284C7 100%); }
        .bronze-gradient { background: linear-gradient(135deg, #D97706 0%, #B45309 100%); }

        /* PRINT STYLES FOR INVOICE / RECEIPT */
        @media print {
            body {
                background: #ffffff !important;
                color: #111827 !important;
            }
            body > *:not(#printable-invoice-modal) {
                display: none !important;
            }
            #printable-invoice-modal {
                position: static !important;
                background: transparent !important;
                padding: 0 !important;
                margin: 0 !important;
                display: block !important;
                overflow: visible !important;
            }
            #printable-invoice-modal .no-print {
                display: none !important;
            }
            #printable-invoice {
                position: static !important;
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 20px !important;
                background: #ffffff !important;
                color: #111827 !important;
                box-shadow: none !important;
                border: none !important;
            }
        }
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
                        <div class="flex items-center gap-2">
                            <button @click="openNewBookingModal()" class="teal-gradient hover:opacity-95 text-white font-bold text-xs uppercase tracking-wider px-3.5 py-2.5 rounded-lg shadow-md transition-all flex items-center gap-2">
                                <i class="fa-solid fa-calendar-plus"></i> + New Booking
                            </button>
                            <button @click="loadBookings()" class="bg-brand-inner hover:bg-slate-800 text-slate-300 border border-brand-border text-xs font-semibold px-3 py-2.5 rounded-lg transition-colors flex items-center gap-1.5" title="Refresh Bookings">
                                <i class="fa-solid fa-rotate" :class="{ 'fa-spin': isLoadingBookings }"></i>
                            </button>
                        </div>
                    </template>
                    <template x-if="activeTab === 'members'">
                        <div class="flex items-center gap-2">
                            <button @click="openMemberModal('add')" class="teal-gradient hover:opacity-95 text-white font-bold text-xs uppercase tracking-wider px-3.5 py-2.5 rounded-lg shadow-md transition-all flex items-center gap-2">
                                <i class="fa-solid fa-user-plus"></i> + Add Member
                            </button>
                            <button @click="loadMembers()" class="bg-brand-inner hover:bg-slate-800 text-slate-300 border border-brand-border text-xs font-semibold px-3 py-2.5 rounded-lg transition-colors flex items-center gap-1.5" title="Refresh Members">
                                <i class="fa-solid fa-rotate" :class="{ 'fa-spin': isLoadingMembers }"></i>
                            </button>
                        </div>
                    </template>
                    <template x-if="activeTab === 'invoices'">
                        <div class="flex items-center gap-2">
                            <button @click="openNewInvoiceModal()" class="teal-gradient hover:opacity-95 text-white font-bold text-xs uppercase tracking-wider px-3.5 py-2.5 rounded-lg shadow-md transition-all flex items-center gap-2">
                                <i class="fa-solid fa-plus"></i> + Create Invoice
                            </button>
                            <button @click="loadInvoices()" class="bg-brand-inner hover:bg-slate-800 text-slate-300 border border-brand-border text-xs font-semibold px-3 py-2.5 rounded-lg transition-colors flex items-center gap-1.5" title="Refresh Invoices">
                                <i class="fa-solid fa-rotate" :class="{ 'fa-spin': isLoadingInvoices }"></i>
                            </button>
                        </div>
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
            <div class="flex items-center gap-3 border-b border-brand-border pb-3 overflow-x-auto">
                <button @click="activeTab = 'stock'" 
                        :class="activeTab === 'stock' ? 'bg-brand-teal text-white font-bold shadow-md' : 'bg-brand-cardBg text-slate-300 hover:text-white border border-brand-border'" 
                        class="px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all flex items-center gap-2 whitespace-nowrap">
                    <i class="fa-solid fa-boxes-stacked"></i> Stock Inventory Manager
                </button>
                <button @click="activeTab = 'ai_styles'; loadAiStyles()" 
                        :class="activeTab === 'ai_styles' ? 'bg-brand-teal text-white font-bold shadow-md' : 'bg-brand-cardBg text-slate-300 hover:text-white border border-brand-border'" 
                        class="px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all flex items-center gap-2 whitespace-nowrap">
                    <i class="fa-solid fa-scissors text-amber-400"></i> AI Simulator Hairstyles
                </button>
                <button @click="activeTab = 'bookings'; loadBookings()" 
                        :class="activeTab === 'bookings' ? 'bg-brand-teal text-white font-bold shadow-md' : 'bg-brand-cardBg text-slate-300 hover:text-white border border-brand-border'" 
                        class="px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all flex items-center gap-2 relative whitespace-nowrap">
                    <i class="fa-solid fa-calendar-check text-emerald-400"></i> Springvale Fitting Bookings
                    <template x-if="pendingBookingsCount > 0">
                        <span class="ml-1.5 px-2 py-0.5 text-[10px] font-black rounded-full bg-rose-500 text-white animate-pulse" x-text="pendingBookingsCount"></span>
                    </template>
                </button>
                <button @click="activeTab = 'members'; loadMembers()" 
                        :class="activeTab === 'members' ? 'bg-brand-teal text-white font-bold shadow-md' : 'bg-brand-cardBg text-slate-300 hover:text-white border border-brand-border'" 
                        class="px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all flex items-center gap-2 relative whitespace-nowrap">
                    <i class="fa-solid fa-users-gear text-purple-400"></i> Members &amp; VIP Performance
                    <template x-if="members.length > 0">
                        <span class="ml-1.5 px-2 py-0.5 text-[10px] font-black rounded-full bg-purple-500/30 text-purple-300 border border-purple-500/40" x-text="members.length"></span>
                    </template>
                </button>
                <button @click="activeTab = 'sections'; loadSections()" 
                        :class="activeTab === 'sections' ? 'bg-brand-teal text-white font-bold shadow-md' : 'bg-brand-cardBg text-slate-300 hover:text-white border border-brand-border'" 
                        class="px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all flex items-center gap-2 relative whitespace-nowrap">
                    <i class="fa-solid fa-layer-group text-sky-400"></i> Section &amp; Menu Manager
                </button>
                <button @click="activeTab = 'invoices'; loadInvoices()" 
                        :class="activeTab === 'invoices' ? 'bg-brand-teal text-white font-bold shadow-md' : 'bg-brand-cardBg text-slate-300 hover:text-white border border-brand-border'" 
                        class="px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all flex items-center gap-2 relative whitespace-nowrap">
                    <i class="fa-solid fa-file-invoice-dollar text-amber-300"></i> Invoices &amp; Receipts
                    <template x-if="invoices.length > 0">
                        <span class="ml-1.5 px-2 py-0.5 text-[10px] font-black rounded-full bg-amber-500/30 text-amber-300 border border-amber-500/40" x-text="invoices.length"></span>
                    </template>
                </button>
            </div>

            <!-- TAB 1: STOCK INVENTORY MANAGER -->
            <div x-show="activeTab === 'stock'" space-y-6>
                <!-- Stats Bar -->
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 sm:gap-4 mb-6">
                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm">
                        <div class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">Total Stock Items</div>
                        <div class="text-2xl font-extrabold text-white mt-1" x-text="items.length"></div>
                    </div>
                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm">
                        <div class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">Total Inventory Qty</div>
                        <div class="text-2xl font-extrabold text-brand-tealLight mt-1" x-text="totalQty"></div>
                    </div>
                    <!-- Visible on Site Stat Card (Clickable Filter) -->
                    <div class="bg-brand-cardBg border border-brand-border hover:border-emerald-500/60 p-4 rounded-xl shadow-sm cursor-pointer transition-colors group"
                         @click="selectedVisibility = selectedVisibility === 'visible' ? 'All' : 'visible'"
                         :class="{ 'ring-2 ring-emerald-500/50 bg-brand-inner': selectedVisibility === 'visible' }"
                         title="클릭하여 메인페이지 노출 제품만 필터링">
                        <div class="text-xs font-bold text-emerald-400 uppercase tracking-wider flex items-center justify-between">
                            <span>Visible on Site</span>
                            <i class="fa-solid fa-eye text-[11px] group-hover:scale-110 transition-transform"></i>
                        </div>
                        <div class="text-2xl font-extrabold text-emerald-400 mt-1 flex items-baseline gap-1.5">
                            <span x-text="visibleItemsCount"></span>
                            <span class="text-xs font-normal text-emerald-400/70">items</span>
                        </div>
                    </div>
                    <!-- Hidden Stat Card (Clickable Filter) -->
                    <div class="bg-brand-cardBg border border-brand-border hover:border-amber-500/60 p-4 rounded-xl shadow-sm cursor-pointer transition-colors group"
                         @click="selectedVisibility = selectedVisibility === 'hidden' ? 'All' : 'hidden'"
                         :class="{ 'ring-2 ring-amber-500/50 bg-brand-inner': selectedVisibility === 'hidden' }"
                         title="클릭하여 숨김 제품만 필터링">
                        <div class="text-xs font-bold text-amber-400 uppercase tracking-wider flex items-center justify-between">
                            <span>Hidden</span>
                            <i class="fa-solid fa-eye-slash text-[11px] group-hover:scale-110 transition-transform"></i>
                        </div>
                        <div class="text-2xl font-extrabold text-amber-400 mt-1 flex items-baseline gap-1.5">
                            <span x-text="hiddenItemsCount"></span>
                            <span class="text-xs font-normal text-amber-400/70">items</span>
                        </div>
                    </div>
                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm col-span-2 sm:col-span-1">
                        <div class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">Database Status</div>
                        <div class="text-xs font-bold text-emerald-400 mt-2 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> MySQL Connected
                        </div>
                    </div>
                </div>

                <!-- Filter & Search Controls -->
                <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl flex flex-col md:flex-row items-center justify-between gap-4 mb-6">
                    <div class="relative w-full md:w-72">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" x-model="searchQuery" placeholder="Search by SKU, Name, Base..." class="w-full bg-brand-inner border border-brand-border rounded-lg pl-9 pr-4 py-2 text-xs text-white outline-none focus:border-brand-teal">
                    </div>

                    <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                        <!-- Category Filter -->
                        <div class="flex items-center gap-2">
                            <label class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">Category:</label>
                            <select x-model="selectedCategory" class="bg-brand-inner border border-brand-border text-xs text-white rounded-lg px-3 py-2 outline-none focus:border-brand-teal">
                                <option value="All">All Categories</option>
                                <option value="Men's Toupee">Men's Toupee</option>
                                <option value="Women's topper">Women's topper</option>
                                <option value="Wig">Wig</option>
                                <option value="Hair Extension">Hair Extension</option>
                            </select>
                        </div>

                        <!-- Visibility Filter -->
                        <div class="flex items-center gap-2">
                            <label class="text-xs font-bold text-brand-textMuted uppercase tracking-wider flex items-center gap-1">
                                <i class="fa-solid fa-eye text-brand-tealLight"></i> Display:
                            </label>
                            <select x-model="selectedVisibility" class="bg-brand-inner border border-brand-border text-xs text-white rounded-lg px-3 py-2 outline-none focus:border-brand-teal font-medium">
                                <option value="All">All Status (전체)</option>
                                <option value="visible">Visible Only (노출 중)</option>
                                <option value="hidden">Hidden Only (숨김)</option>
                            </select>
                        </div>

                        <!-- Sort By Selector -->
                        <div class="flex items-center gap-2">
                            <label class="text-xs font-bold text-brand-textMuted uppercase tracking-wider flex items-center gap-1">
                                <i class="fa-solid fa-arrow-down-short-wide text-brand-tealLight"></i> Sort:
                            </label>
                            <select @change="applySelectSort($event.target.value)" :value="sortColumn ? (sortColumn + '-' + sortDirection) : 'id-desc'" class="bg-brand-inner border border-brand-border text-xs text-white rounded-lg px-3 py-2 outline-none focus:border-brand-teal font-medium">
                                <option value="id-desc">Newest First</option>
                                <option value="name-asc">Item Name (A–Z)</option>
                                <option value="name-desc">Item Name (Z–A)</option>
                                <option value="category-asc">Category</option>
                                <option value="size-asc">Size</option>
                                <option value="price-asc">Price (Lowest First ↑)</option>
                                <option value="price-desc">Price (Highest First ↓)</option>
                                <option value="stockQty-desc">Stock Qty (Highest First ↓)</option>
                                <option value="stockQty-asc">Stock Qty (Lowest First ↑)</option>
                                <option value="is_visible-desc">Display: Visible First (노출 우선)</option>
                                <option value="is_visible-asc">Display: Hidden First (숨김 우선)</option>
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

                                    <!-- 6. Sort by Display / Visibility -->
                                    <th class="py-3.5 px-4 cursor-pointer hover:text-white transition-colors group whitespace-nowrap" @click="sortBy('is_visible')" title="클릭하여 메인 노출 여부 정렬">
                                        <div class="flex items-center gap-1.5">
                                            <span>Display (노출)</span>
                                            <i class="fa-solid" :class="getSortIcon('is_visible')"></i>
                                        </div>
                                    </th>

                                    <th class="py-3.5 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-brand-border/60">
                                <template x-for="item in filteredItems" :key="item.id">
                                    <tbody class="contents">
                                        <!-- 1. VIEW MODE ROW -->
                                        <tr x-show="editingRowId !== item.id" class="hover:bg-brand-inner/50 transition-colors group" :class="{ 'opacity-70': !(item.is_visible === undefined || item.is_visible == 1 || item.is_visible === true || item.is_visible === '1') }">
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
                                                    <template x-if="!(item.is_visible === undefined || item.is_visible == 1 || item.is_visible === true || item.is_visible === '1')">
                                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase border bg-amber-950/80 border-amber-700 text-amber-300 flex items-center gap-1">
                                                            <i class="fa-solid fa-eye-slash text-[9px]"></i> Hidden
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
                                            <!-- 노출 / 숨김 토글 스위치 버튼 -->
                                            <td class="py-3 px-4 whitespace-nowrap align-top">
                                                <button type="button" 
                                                        @click="toggleItemVisibility(item)" 
                                                        :disabled="togglingItemIds.includes(item.id)"
                                                        :class="(item.is_visible === undefined || item.is_visible == 1 || item.is_visible === true || item.is_visible === '1') 
                                                            ? 'bg-emerald-950/80 hover:bg-emerald-900 border-emerald-500/70 text-emerald-300' 
                                                            : 'bg-slate-800/90 hover:bg-slate-700 border-slate-600 text-slate-400'"
                                                        class="px-2.5 py-1.5 rounded-lg border text-xs font-bold inline-flex items-center gap-1.5 transition-all shadow-sm cursor-pointer select-none active:scale-95"
                                                        :title="(item.is_visible === undefined || item.is_visible == 1 || item.is_visible === true || item.is_visible === '1') ? '현재 메인페이지 노출 중 (클릭 시 숨김)' : '현재 메인페이지 숨김 상태 (클릭 시 노출)'">
                                                    <span class="w-2 h-2 rounded-full" 
                                                          :class="(item.is_visible === undefined || item.is_visible == 1 || item.is_visible === true || item.is_visible === '1') ? 'bg-emerald-400 animate-pulse' : 'bg-slate-500'"></span>
                                                    <i class="fa-solid text-[11px]" 
                                                       :class="(item.is_visible === undefined || item.is_visible == 1 || item.is_visible === true || item.is_visible === '1') ? 'fa-eye text-emerald-400' : 'fa-eye-slash text-slate-400'"></i>
                                                    <span x-text="(item.is_visible === undefined || item.is_visible == 1 || item.is_visible === true || item.is_visible === '1') ? '노출 (Visible)' : '숨김 (Hidden)'"></span>
                                                    <i x-show="togglingItemIds.includes(item.id)" class="fa-solid fa-spinner fa-spin text-[10px] ml-1 text-brand-tealLight"></i>
                                                </button>
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
                                                    <option value="Men's Toupee">Men's Toupee</option>
                                                    <option value="Women's topper">Women's topper</option>
                                                    <option value="Wig">Wig</option>
                                                    <option value="Hair Extension">Hair Extension</option>
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
                                            <!-- 인라인 수정: 노출 / 숨김 토글 -->
                                            <td class="py-3 px-3 align-top whitespace-nowrap">
                                                <button type="button" 
                                                        @click="inlineForm.is_visible = !inlineForm.is_visible" 
                                                        :class="inlineForm.is_visible ? 'bg-emerald-950/90 border-emerald-500/70 text-emerald-300' : 'bg-slate-800 border-slate-600 text-slate-400'"
                                                        class="px-2.5 py-1.5 rounded-lg border text-xs font-bold inline-flex items-center gap-1.5 transition-all shadow-sm">
                                                    <span class="w-2 h-2 rounded-full" :class="inlineForm.is_visible ? 'bg-emerald-400' : 'bg-slate-500'"></span>
                                                    <i class="fa-solid text-[11px]" :class="inlineForm.is_visible ? 'fa-eye text-emerald-400' : 'fa-eye-slash text-slate-400'"></i>
                                                    <span x-text="inlineForm.is_visible ? '노출' : '숨김'"></span>
                                                </button>
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
                                        <div class="w-[200px] h-[200px] min-w-[200px] min-h-[200px] rounded-lg border border-brand-border bg-white p-2 flex items-center justify-center flex-shrink-0 shadow-sm">
                                            <img :src="st.overlay" :alt="st.name" class="w-full h-full object-contain">
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
                        <div class="text-[11px] text-brand-textMuted mt-0.5">Total fitting requests received</div>
                    </div>

                    <div class="bg-brand-cardBg border border-amber-500/40 p-4 rounded-xl shadow-sm">
                        <div class="text-xs font-bold text-amber-400 uppercase tracking-wider flex items-center justify-between">
                            <span>Pending Confirmation</span>
                            <i class="fa-solid fa-clock text-amber-400"></i>
                        </div>
                        <div class="text-2xl font-extrabold text-amber-300 mt-1" x-text="pendingBookingsCount"></div>
                        <div class="text-[11px] text-amber-400/80 mt-0.5">Awaiting WhatsApp confirmation</div>
                    </div>

                    <div class="bg-brand-cardBg border border-emerald-500/40 p-4 rounded-xl shadow-sm">
                        <div class="text-xs font-bold text-emerald-400 uppercase tracking-wider flex items-center justify-between">
                            <span>Confirmed</span>
                            <i class="fa-solid fa-circle-check text-emerald-400"></i>
                        </div>
                        <div class="text-2xl font-extrabold text-emerald-300 mt-1" x-text="confirmedBookingsCount"></div>
                        <div class="text-[11px] text-emerald-400/80 mt-0.5">Confirmed studio visits</div>
                    </div>

                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm">
                        <div class="text-xs font-bold text-brand-textMuted uppercase tracking-wider flex items-center justify-between">
                            <span>Today's Fitting</span>
                            <i class="fa-solid fa-location-dot text-brand-tealLight"></i>
                        </div>
                        <div class="text-2xl font-extrabold text-brand-tealLight mt-1" x-text="todayBookingsCount"></div>
                        <div class="text-[11px] text-brand-textMuted mt-0.5">Scheduled for today</div>
                    </div>
                </div>

                <!-- Controls & Filter Toolbar -->
                <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl flex flex-col md:flex-row gap-4 justify-between items-stretch md:items-center">
                    <!-- Search Input -->
                    <div class="relative flex-1 max-w-md">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" 
                               x-model="bookingSearchQuery" 
                               placeholder="Search customer, phone, service, #FIT ID..." 
                               class="w-full bg-brand-inner border border-brand-border rounded-lg pl-9 pr-4 py-2 text-white text-xs outline-none focus:border-brand-teal placeholder:text-slate-500">
                    </div>

                    <!-- View Switcher & Filter Buttons -->
                    <div class="flex flex-wrap items-center gap-2">
                        <!-- View Mode Switcher: Weekly Timetable vs List View -->
                        <div class="flex items-center gap-1 bg-brand-inner border border-brand-border p-1 rounded-lg text-xs">
                            <button type="button" 
                                    @click="bookingViewMode = 'week'" 
                                    :class="bookingViewMode === 'week' ? 'bg-brand-teal text-white font-bold shadow-md' : 'text-slate-300 hover:text-white'" 
                                    class="px-3 py-1.5 rounded-md flex items-center gap-1.5 transition-all">
                                <i class="fa-solid fa-calendar-week"></i>
                                <span>Weekly Timetable (08:00–20:00)</span>
                            </button>
                            <button type="button" 
                                    @click="bookingViewMode = 'list'" 
                                    :class="bookingViewMode === 'list' ? 'bg-brand-teal text-white font-bold shadow-md' : 'text-slate-300 hover:text-white'" 
                                    class="px-3 py-1.5 rounded-md flex items-center gap-1.5 transition-all">
                                <i class="fa-solid fa-list-ul"></i>
                                <span>List View</span>
                            </button>
                        </div>

                        <!-- Status Filter -->
                        <div class="flex items-center gap-1 bg-brand-inner border border-brand-border p-1 rounded-lg text-xs">
                            <span class="px-2 text-[11px] text-slate-400 font-bold uppercase">Status:</span>
                            <button type="button" @click="bookingStatusFilter = 'All'" :class="bookingStatusFilter === 'All' ? 'bg-brand-teal text-white font-bold' : 'text-slate-300 hover:text-white'" class="px-2 py-1 rounded transition-colors">All</button>
                            <button type="button" @click="bookingStatusFilter = 'Pending'" :class="bookingStatusFilter === 'Pending' ? 'bg-amber-600 text-white font-bold' : 'text-slate-300 hover:text-white'" class="px-2 py-1 rounded transition-colors">Pending</button>
                            <button type="button" @click="bookingStatusFilter = 'Confirmed'" :class="bookingStatusFilter === 'Confirmed' ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:text-white'" class="px-2 py-1 rounded transition-colors">Confirmed</button>
                            <button type="button" @click="bookingStatusFilter = 'Completed'" :class="bookingStatusFilter === 'Completed' ? 'bg-sky-600 text-white font-bold' : 'text-slate-300 hover:text-white'" class="px-2 py-1 rounded transition-colors">Completed</button>
                            <button type="button" @click="bookingStatusFilter = 'Cancelled'" :class="bookingStatusFilter === 'Cancelled' ? 'bg-rose-700 text-white font-bold' : 'text-slate-300 hover:text-white'" class="px-2 py-1 rounded transition-colors">Cancelled</button>
                        </div>

                        <!-- Date Filter (for List View) -->
                        <div x-show="bookingViewMode === 'list'" class="flex items-center gap-1 bg-brand-inner border border-brand-border p-1 rounded-lg text-xs">
                            <span class="px-2 text-[11px] text-slate-400 font-bold uppercase">Date:</span>
                            <button type="button" @click="bookingDateFilter = 'All'" :class="bookingDateFilter === 'All' ? 'bg-brand-teal text-white font-bold' : 'text-slate-300 hover:text-white'" class="px-2 py-1 rounded transition-colors">All</button>
                            <button type="button" @click="bookingDateFilter = 'Today'" :class="bookingDateFilter === 'Today' ? 'bg-brand-teal text-white font-bold' : 'text-slate-300 hover:text-white'" class="px-2 py-1 rounded transition-colors">Today</button>
                            <button type="button" @click="bookingDateFilter = 'Upcoming'" :class="bookingDateFilter === 'Upcoming' ? 'bg-brand-teal text-white font-bold' : 'text-slate-300 hover:text-white'" class="px-2 py-1 rounded transition-colors">Upcoming</button>
                        </div>

                        <!-- Holiday & Off-Days Settings -->
                        <button type="button" @click="openHolidayModal()" class="px-3 py-1.5 bg-brand-inner hover:bg-slate-700 border border-amber-500/40 text-amber-300 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all shadow-sm" title="Manage Regular Off-Days & Holiday Periods">
                            <i class="fa-solid fa-calendar-xmark text-amber-400"></i>
                            <span>Holiday & Off-Days (휴무일 설정)</span>
                        </button>

                        <!-- Notification Settings (Telegram & Email) -->
                        <button type="button" @click="openNotificationModal()" class="px-3 py-1.5 bg-brand-inner hover:bg-slate-700 border border-sky-500/40 text-sky-300 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all shadow-sm" title="Configure Telegram & Email Booking Alerts">
                            <i class="fa-solid fa-bell text-sky-400"></i>
                            <span>알림 설정 (Telegram/Email)</span>
                        </button>

                        <!-- Refresh -->
                        <button type="button" @click="loadBookings()" class="px-3 py-1.5 bg-brand-inner hover:bg-slate-700 border border-brand-border text-slate-200 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all" title="Refresh Bookings">
                            <i class="fa-solid fa-rotate" :class="{ 'fa-spin': isLoadingBookings }"></i>
                        </button>
                    </div>
                </div>

                <!-- VIEW 1: WEEKLY TIMETABLE (08:00 - 20:00) -->
                <div x-show="bookingViewMode === 'week'" class="space-y-4">
                    <!-- Visual Drag & Drop Guide Banner -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-brand-inner/70 border border-teal-500/30 px-4 py-2.5 rounded-xl text-xs text-brand-tealLight shadow-md">
                        <div class="flex items-center gap-2.5">
                            <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-teal-500/20 text-teal-300">
                                <i class="fa-solid fa-arrows-up-down-left-right text-xs"></i>
                            </span>
                            <span>
                                <strong>Drag & Drop Rescheduling:</strong> Click and drag any appointment card to a new date and time slot (08:00–20:00) to reschedule instantly.
                            </span>
                        </div>
                        <div class="flex items-center gap-2 text-[11px] text-slate-400 self-end sm:self-auto font-mono">
                            <i class="fa-regular fa-calendar-check text-teal-400"></i>
                            <span>7-Day Weekly View</span>
                        </div>
                    </div>

                    <!-- Weekly Navigation Bar -->
                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl flex flex-col md:flex-row gap-4 justify-between items-center shadow-lg">
                        <!-- Navigation Buttons -->
                        <div class="flex flex-wrap items-center gap-2">
                            <button type="button" @click="prevWeek()" class="px-3 py-2 bg-brand-inner hover:bg-slate-800 border border-brand-border text-slate-200 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all shadow-sm active:scale-95" title="Go to previous 7 days">
                                <i class="fa-solid fa-chevron-left"></i>
                                <span>Prev 7 Days</span>
                            </button>
                            <button type="button" @click="goToTodayWeek()" class="px-3 py-2 bg-brand-inner hover:bg-brand-teal/20 border border-brand-teal/60 text-brand-tealLight rounded-lg text-xs font-bold transition-all shadow-sm active:scale-95" title="Show 7 days starting from today">
                                <i class="fa-solid fa-calendar-day mr-1"></i>Today + 7 Days
                            </button>
                            <button type="button" @click="goToMondayWeek()" class="px-3 py-2 bg-brand-inner hover:bg-slate-800 border border-brand-border text-slate-300 rounded-lg text-xs font-bold transition-all shadow-sm active:scale-95" title="Show Monday through Sunday of this week">
                                This Week (Mon–Sun)
                            </button>
                            <button type="button" @click="nextWeek()" class="px-3 py-2 bg-brand-inner hover:bg-slate-800 border border-brand-border text-slate-200 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all shadow-sm active:scale-95" title="Go to next 7 days">
                                <span>Next 7 Days</span>
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>

                            <!-- Date Picker: Pick ANY start date -> Shows 7 days -->
                            <div class="flex items-center gap-2 bg-brand-inner border border-brand-border px-3 py-1.5 rounded-lg text-xs text-slate-300">
                                <i class="fa-regular fa-calendar-days text-brand-tealLight"></i>
                                <span class="text-[11px] text-slate-300 font-semibold">Start Date:</span>
                                <input type="date" 
                                       :value="weekStartDate" 
                                       @change="setStartDate($event.target.value)" 
                                       class="bg-brand-darkBg border border-brand-border text-white text-xs rounded px-2.5 py-1 outline-none focus:border-brand-teal cursor-pointer font-mono font-bold"
                                       title="Pick an initial date to display a 7-day schedule">
                                <span class="text-[10px] text-brand-textMuted font-mono">(+7 Days)</span>
                            </div>
                        </div>

                        <!-- Week Range Label & Stats -->
                        <div class="flex flex-wrap items-center gap-3">
                            <div class="text-xs font-bold text-white bg-brand-inner border border-brand-border px-3.5 py-2 rounded-lg flex items-center gap-2 shadow-sm font-mono">
                                <i class="fa-regular fa-calendar-check text-brand-tealLight text-sm"></i>
                                <span x-text="weekRangeLabel"></span>
                            </div>
                            <div class="flex items-center gap-1.5 text-xs font-mono">
                                <span class="px-2.5 py-1.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-300 font-semibold">
                                    Total: <strong class="text-white font-extrabold" x-text="weekTotalBookingsCount"></strong>
                                </span>
                                <span class="px-2.5 py-1.5 rounded-lg bg-emerald-950/80 border border-emerald-700 text-emerald-300 font-semibold">
                                    Confirmed: <strong class="text-white font-extrabold" x-text="weekConfirmedBookingsCount"></strong>
                                </span>
                                <span class="px-2.5 py-1.5 rounded-lg bg-amber-950/80 border border-amber-700 text-amber-300 font-semibold">
                                    Pending: <strong class="text-white font-extrabold" x-text="weekPendingBookingsCount"></strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Timetable Grid -->
                    <div class="bg-brand-cardBg border border-brand-border rounded-2xl overflow-hidden shadow-2xl">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs border-collapse min-w-[1050px]">
                                <!-- Header: Time + 7 Days -->
                                <thead class="bg-brand-inner text-brand-textMuted uppercase font-bold text-[11px] border-b border-brand-border sticky top-0 z-20">
                                    <tr>
                                        <th class="py-3.5 px-3 w-32 text-center border-r border-brand-border/60 bg-brand-inner">
                                            <div class="flex items-center justify-center gap-1.5 text-slate-300 font-heading">
                                                <i class="fa-regular fa-clock text-brand-tealLight"></i>
                                                <span>Time Slot</span>
                                            </div>
                                        </th>
                                        <template x-for="day in weekDays" :key="day.dateStr">
                                            <th class="py-3 px-3 text-center border-r border-brand-border/60 last:border-r-0 transition-colors"
                                                :class="day.isToday ? 'bg-teal-950/60 text-brand-tealLight border-b-2 border-b-brand-teal' : 'bg-brand-inner text-slate-300'">
                                                <div class="flex flex-col items-center gap-1">
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="text-sm font-extrabold" x-text="day.nameEn"></span>
                                                        <template x-if="day.isToday">
                                                            <span class="px-1.5 py-0.5 text-[9px] rounded font-black uppercase bg-teal-400 text-slate-950 shadow animate-pulse">TODAY</span>
                                                        </template>
                                                        <template x-if="day.isClosed">
                                                            <span class="px-1.5 py-0.5 text-[8.5px] rounded font-bold uppercase bg-slate-800 text-amber-400 border border-amber-500/40" :title="day.closureReason" x-text="day.closureReason ? (day.closureReason.includes('Off-Day') ? 'OFF' : 'HOLIDAY') : 'CLOSED'"></span>
                                                        </template>
                                                    </div>
                                                    <div class="text-[11px] font-mono font-medium text-slate-400" x-text="day.monthDay"></div>
                                                    <template x-if="day.bookingsCount > 0">
                                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-brand-teal/20 text-brand-tealLight border border-brand-teal/40" x-text="day.bookingsCount + (day.bookingsCount === 1 ? ' booking' : ' bookings')"></span>
                                                    </template>
                                                </div>
                                            </th>
                                        </template>
                                    </tr>
                                </thead>

                                <!-- Body: 08:00 to 20:00 (13 hourly slots) -->
                                <tbody class="divide-y divide-brand-border/50">
                                    <template x-for="slot in timeSlots" :key="slot">
                                        <tr class="hover:bg-slate-900/40 transition-colors">
                                            <!-- Time Column -->
                                            <td class="py-3 px-2 text-center border-r border-brand-border/60 bg-brand-inner/40 font-mono align-top w-32 select-none">
                                                <div class="font-extrabold text-white text-sm" x-text="slot"></div>
                                                <div class="text-[10px] text-slate-400 mt-0.5" x-text="formatSlotLabel(slot)"></div>
                                            </td>

                                            <!-- 7 Day Columns -->
                                            <template x-for="day in weekDays" :key="day.dateStr">
                                                <td class="p-2 border-r border-brand-border/40 last:border-r-0 align-top min-w-[135px] min-h-[96px] relative group transition-all"
                                                    :class="{
                                                        'bg-teal-950/15': day.isToday && dragOverCell !== (day.dateStr + '_' + slot),
                                                        'bg-slate-950/40 opacity-75': day.isClosed && !day.isToday && dragOverCell !== (day.dateStr + '_' + slot),
                                                        'ring-2 ring-brand-teal ring-inset bg-teal-900/50 shadow-inner': dragOverCell === (day.dateStr + '_' + slot)
                                                    }"
                                                    @dragover.prevent="handleDragOver($event, day.dateStr, slot)"
                                                    @dragleave="handleDragLeave($event, day.dateStr, slot)"
                                                    @drop.prevent="handleDrop($event, day.dateStr, slot)">
                                                    
                                                    <!-- Drop Target Visual Indicator -->
                                                    <template x-if="dragOverCell === (day.dateStr + '_' + slot)">
                                                        <div class="mb-1.5 py-1 px-2 bg-teal-400 text-slate-950 font-black text-[10px] rounded text-center shadow-lg pointer-events-none animate-pulse flex items-center justify-center gap-1">
                                                            <i class="fa-solid fa-arrow-down"></i>
                                                            <span>Drop Here</span>
                                                        </div>
                                                    </template>

                                                    <!-- Bookings Container in this cell -->
                                                    <div class="space-y-2 min-h-[76px] flex flex-col justify-start">
                                                        <template x-for="b in getBookingsForSlot(day.dateStr, slot)" :key="b.id">
                                                            <div draggable="true"
                                                                 @dragstart="handleDragStart($event, b)"
                                                                 @dragend="handleDragEnd($event)"
                                                                 @click="openBookingDetail(b)"
                                                                 class="p-2.5 rounded-xl border transition-all cursor-grab active:cursor-grabbing shadow-md hover:scale-[1.02] flex flex-col justify-between group/card select-none"
                                                                 :class="{
                                                                     'opacity-40 border-dashed border-teal-400 ring-2 ring-brand-teal': draggedBooking && draggedBooking.id === b.id,
                                                                     'bg-amber-950/80 border-amber-500/80 hover:border-amber-400 hover:shadow-amber-950/70': b.status === 'Pending' && (!draggedBooking || draggedBooking.id !== b.id),
                                                                     'bg-emerald-950/80 border-emerald-500/80 hover:border-emerald-400 hover:shadow-emerald-950/70': b.status === 'Confirmed' && (!draggedBooking || draggedBooking.id !== b.id),
                                                                     'bg-sky-950/80 border-sky-500/80 hover:border-sky-400 hover:shadow-sky-950/70': b.status === 'Completed' && (!draggedBooking || draggedBooking.id !== b.id),
                                                                     'bg-rose-950/80 border-rose-500/80 hover:border-rose-400 opacity-60': b.status === 'Cancelled' && (!draggedBooking || draggedBooking.id !== b.id)
                                                                 }">
                                                                <!-- Card Top: Drag Handle + Time & Status -->
                                                                <div class="flex items-center justify-between gap-1 mb-1">
                                                                    <div class="font-mono text-[10px] font-bold text-white flex items-center gap-1">
                                                                        <span class="text-slate-400 group-hover/card:text-teal-300 transition-colors" title="Drag to reschedule date &amp; time">
                                                                            <i class="fa-solid fa-grip-vertical"></i>
                                                                        </span>
                                                                        <span x-text="b.booking_time"></span>
                                                                    </div>
                                                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold uppercase"
                                                                          :class="{
                                                                              'bg-amber-500/30 text-amber-300': b.status === 'Pending',
                                                                              'bg-emerald-500/30 text-emerald-300': b.status === 'Confirmed',
                                                                              'bg-sky-500/30 text-sky-300': b.status === 'Completed',
                                                                              'bg-rose-500/30 text-rose-300': b.status === 'Cancelled'
                                                                          }"
                                                                          x-text="b.status"></span>
                                                                </div>

                                                                <!-- Card Middle: Customer Name & Phone -->
                                                                <div class="font-bold text-white text-xs truncate" :title="b.customer_name" x-text="b.customer_name"></div>
                                                                <div class="text-[10px] font-mono text-slate-300 truncate mt-0.5" x-text="b.phone"></div>

                                                                <!-- Card Bottom: Service & WhatsApp -->
                                                                <div class="mt-1.5 pt-1 border-t border-white/10 flex items-center justify-between gap-1">
                                                                    <span class="text-[9px] text-slate-400 truncate max-w-[85px]" :title="b.service_type" x-text="b.service_type"></span>
                                                                    <a :href="'https://wa.me/' + formatWhatsAppPhone(b.phone)" 
                                                                       @click.stop
                                                                       target="_blank" 
                                                                       class="text-emerald-400 hover:text-emerald-300 text-xs p-0.5 transition-colors" 
                                                                       title="Chat on WhatsApp">
                                                                        <i class="fa-brands fa-whatsapp text-sm"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </template>

                                                        <!-- If no bookings: Hover "+ New Booking" slot button -->
                                                        <template x-if="getBookingsForSlot(day.dateStr, slot).length === 0">
                                                            <div @click="openNewBookingModal(day.dateStr, slot)"
                                                                 class="w-full h-full min-h-[70px] rounded-lg border border-dashed border-transparent hover:border-brand-border/80 hover:bg-brand-inner/40 flex items-center justify-center transition-all cursor-pointer group/slot">
                                                                <span class="opacity-0 group-hover/slot:opacity-100 transition-opacity text-[11px] font-bold text-slate-400 flex items-center gap-1">
                                                                    <i class="fa-solid fa-plus text-brand-tealLight"></i>
                                                                    <span>+ New Booking</span>
                                                                </span>
                                                            </div>
                                                        </template>
                                                    </div>

                                                </td>
                                            </template>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- VIEW 2: LIST TABLE VIEW -->
                <div x-show="bookingViewMode === 'list'" class="bg-brand-cardBg border border-brand-border rounded-2xl overflow-hidden shadow-xl">
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
                                            No fitting bookings found matching your search or filters.
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
                                            <a :href="'https://wa.me/' + formatWhatsAppPhone(b.phone)" target="_blank" class="font-mono text-emerald-400 hover:text-emerald-300 font-semibold flex items-center gap-1.5 hover:underline" title="Open WhatsApp chat">
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
                                                <option value="Pending">🟡 Pending</option>
                                                <option value="Confirmed">🟢 Confirmed</option>
                                                <option value="Completed">🔵 Completed</option>
                                                <option value="Cancelled">🔴 Cancelled</option>
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
                                                    title="Change status to Confirmed and open WhatsApp confirmation message">
                                                <i class="fa-brands fa-whatsapp text-sm"></i>
                                                <span x-text="b.status === 'Confirmed' ? 'Re-send WhatsApp' : 'Confirm & WhatsApp'"></span>
                                            </button>
                                        </td>

                                        <!-- Google Calendar Button -->
                                        <td class="py-3.5 px-4 text-center">
                                            <button type="button" 
                                                    @click="addToGoogleCalendar(b)" 
                                                    class="bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs px-3 py-2 rounded-lg shadow transition-all inline-flex items-center gap-1.5 whitespace-nowrap"
                                                    title="Add this booking to Google Calendar with 1 click">
                                                <i class="fa-regular fa-calendar-plus text-sm"></i>
                                                <span>Add to Google Cal</span>
                                            </button>
                                        </td>

                                        <!-- Manage (Delete) -->
                                        <td class="py-3.5 px-4 text-right">
                                            <button type="button" 
                                                    @click="deleteBooking(b)" 
                                                    class="bg-rose-950/80 hover:bg-rose-900 text-rose-300 border border-rose-800 text-xs px-2.5 py-1.5 rounded transition-colors" 
                                                    title="Delete Booking">
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

            <!-- TAB 4: MEMBERS & VIP PERFORMANCE -->
            <div x-show="activeTab === 'members'" class="space-y-6">
                <!-- KPI Performance Bar -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm relative overflow-hidden">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">Total Members</span>
                            <div class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-400 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-users"></i>
                            </div>
                        </div>
                        <div class="text-2xl font-extrabold text-white mt-2" x-text="members.length"></div>
                        <div class="text-[11px] text-slate-400 mt-1 flex items-center gap-1">
                            <span class="text-emerald-400 font-bold" x-text="members.filter(m => m.status === 'Active').length + ' Active'"></span>
                            <span>•</span>
                            <span x-text="members.filter(m => m.status !== 'Active').length + ' Inactive'"></span>
                        </div>
                    </div>

                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm relative overflow-hidden">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">VIP &amp; Gold Tier</span>
                            <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-crown"></i>
                            </div>
                        </div>
                        <div class="text-2xl font-extrabold text-amber-300 mt-2" x-text="vipAndGoldCount"></div>
                        <div class="text-[11px] text-slate-400 mt-1">
                            High-loyalty returning clients
                        </div>
                    </div>

                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm relative overflow-hidden">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">Total Performance Spent</span>
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-sack-dollar"></i>
                            </div>
                        </div>
                        <div class="text-2xl font-extrabold text-brand-tealLight mt-2 font-mono">
                            $<span x-text="Number(totalMembersSpent).toLocaleString('en-AU', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span>
                        </div>
                        <div class="text-[11px] text-slate-400 mt-1">
                            Cumulative revenue tracked
                        </div>
                    </div>

                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm relative overflow-hidden">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">Total Loyalty Points</span>
                            <div class="w-8 h-8 rounded-lg bg-sky-500/10 text-sky-400 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-coins"></i>
                            </div>
                        </div>
                        <div class="text-2xl font-extrabold text-sky-300 mt-2 font-mono">
                            <span x-text="Number(totalMembersPoints).toLocaleString()"></span> <span class="text-xs font-normal text-slate-400">Pts</span>
                        </div>
                        <div class="text-[11px] text-slate-400 mt-1">
                            Active reward balance
                        </div>
                    </div>
                </div>

                <!-- Controls & Filters Bar -->
                <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl flex flex-col lg:flex-row items-center justify-between gap-4">
                    <div class="relative w-full lg:w-96">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" x-model="memberSearchQuery" placeholder="Search by name, email, phone, code (#DH-M...)" class="w-full bg-brand-inner border border-brand-border rounded-lg pl-9 pr-4 py-2 text-xs text-white outline-none focus:border-brand-teal">
                    </div>

                    <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                        <!-- Tier Filter -->
                        <div class="flex items-center gap-2">
                            <label class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">Tier:</label>
                            <select x-model="memberTierFilter" class="bg-brand-inner border border-brand-border text-xs text-white rounded-lg px-3 py-2 outline-none focus:border-brand-teal">
                                <option value="All">All Tiers</option>
                                <option value="Bronze">Bronze ($0-$499)</option>
                                <option value="Silver">Silver ($500-$1,499)</option>
                                <option value="Gold">Gold ($1,500-$2,999)</option>
                                <option value="VIP Partner">VIP Partner ($3,000+)</option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="flex items-center gap-2">
                            <label class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">Status:</label>
                            <select x-model="memberStatusFilter" class="bg-brand-inner border border-brand-border text-xs text-white rounded-lg px-3 py-2 outline-none focus:border-brand-teal">
                                <option value="All">All Statuses</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Suspended">Suspended</option>
                            </select>
                        </div>

                        <button type="button" @click="openMemberModal('add')" class="teal-gradient hover:opacity-95 text-white font-bold text-xs uppercase tracking-wider px-3.5 py-2 rounded-lg shadow-md transition-all flex items-center gap-1.5 ml-auto lg:ml-0">
                            <i class="fa-solid fa-user-plus"></i> + Add Member
                        </button>
                    </div>
                </div>

                <!-- Members Table -->
                <div class="bg-brand-cardBg border border-brand-border rounded-2xl overflow-hidden shadow-xl">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-brand-inner/80 text-brand-textMuted uppercase tracking-wider text-[11px] border-b border-brand-border">
                                <tr>
                                    <th class="py-3 px-4 font-bold">Member Code &amp; Name</th>
                                    <th class="py-3 px-4 font-bold">Contact &amp; Hair System</th>
                                    <th class="py-3 px-4 font-bold">Membership Tier</th>
                                    <th class="py-3 px-4 font-bold">Total Spent &amp; Visits</th>
                                    <th class="py-3 px-4 font-bold">Loyalty Points</th>
                                    <th class="py-3 px-4 font-bold">Referral Stats</th>
                                    <th class="py-3 px-4 font-bold">Status</th>
                                    <th class="py-3 px-4 font-bold text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-brand-border/60">
                                <template x-if="filteredMembers.length === 0">
                                    <tr>
                                        <td colspan="8" class="text-center py-12 text-slate-400">
                                            <i class="fa-solid fa-user-slash text-3xl mb-2 text-slate-600 block"></i>
                                            <span>No members found matching your search criteria.</span>
                                        </td>
                                    </tr>
                                </template>
                                <template x-for="m in filteredMembers" :key="m.id">
                                    <tr class="hover:bg-brand-inner/40 transition-colors">
                                        <!-- Code & Name -->
                                        <td class="py-3.5 px-4">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-8 h-8 rounded-full teal-gradient flex items-center justify-center font-bold text-white text-xs shadow-sm flex-shrink-0" x-text="m.full_name ? m.full_name.charAt(0).toUpperCase() : 'M'"></div>
                                                <div>
                                                    <div class="font-bold text-white flex items-center gap-1.5">
                                                        <span x-text="m.full_name"></span>
                                                    </div>
                                                    <div class="text-[10px] font-mono text-brand-tealLight" x-text="m.member_code"></div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Contact & Hair System -->
                                        <td class="py-3.5 px-4">
                                            <div class="text-slate-200 font-mono text-[11px]" x-text="m.email"></div>
                                            <div class="text-slate-400 text-[11px] flex items-center gap-1 mt-0.5">
                                                <i class="fa-solid fa-phone text-[9px]"></i>
                                                <span x-text="m.phone || 'No phone'"></span>
                                            </div>
                                            <div class="text-[10px] text-teal-300 font-medium mt-0.5 flex items-center gap-1" x-show="m.hair_system_type">
                                                <i class="fa-solid fa-scissors text-[9px]"></i>
                                                <span x-text="m.hair_system_type"></span>
                                            </div>
                                        </td>

                                        <!-- Membership Tier -->
                                        <td class="py-3.5 px-4">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold border"
                                                  :class="{
                                                      'bg-amber-950/80 text-amber-300 border-amber-700': m.tier === 'Bronze',
                                                      'bg-slate-800 text-slate-200 border-slate-600': m.tier === 'Silver',
                                                      'bg-yellow-950/80 text-yellow-300 border-yellow-600': m.tier === 'Gold',
                                                      'bg-purple-950/90 text-purple-200 border-purple-500 shadow-sm shadow-purple-500/20': m.tier === 'VIP Partner'
                                                  }">
                                                <i class="fa-solid" :class="{
                                                    'fa-shield-halved': m.tier === 'Bronze',
                                                    'fa-star': m.tier === 'Silver',
                                                    'fa-medal': m.tier === 'Gold',
                                                    'fa-crown': m.tier === 'VIP Partner'
                                                }"></i>
                                                <span x-text="m.tier"></span>
                                            </span>
                                        </td>

                                        <!-- Total Spent & Visits -->
                                        <td class="py-3.5 px-4 font-mono">
                                            <div class="text-white font-bold text-sm">
                                                $<span x-text="Number(m.total_spent || 0).toLocaleString('en-AU', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span>
                                            </div>
                                            <div class="text-[10px] text-slate-400 flex items-center gap-1 mt-0.5">
                                                <i class="fa-solid fa-calendar-check text-[9px] text-brand-tealLight"></i>
                                                <span x-text="(m.visit_count || 0) + ' visits'"></span>
                                            </div>
                                        </td>

                                        <!-- Loyalty Points -->
                                        <td class="py-3.5 px-4">
                                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-sky-950/60 border border-sky-800/80 text-sky-200 font-mono font-bold text-xs">
                                                <i class="fa-solid fa-coins text-sky-400 text-[10px]"></i>
                                                <span x-text="Number(m.points || 0).toLocaleString()"></span>
                                                <span class="text-[10px] font-normal text-sky-300">Pts</span>
                                            </div>
                                        </td>

                                        <!-- Referral Stats -->
                                        <td class="py-3.5 px-4">
                                            <div class="text-[10px] font-mono text-slate-300">
                                                Code: <span class="text-amber-300 font-bold" x-text="m.referral_code || '-'"></span>
                                            </div>
                                            <div class="text-[10px] text-slate-400 mt-0.5">
                                                <span class="text-emerald-400 font-bold" x-text="m.referral_count || 0"></span> referred
                                            </div>
                                            <div class="text-[9px] text-slate-500 mt-0.5" x-show="m.referred_by">
                                                By: <span x-text="m.referred_by"></span>
                                            </div>
                                        </td>

                                        <!-- Status -->
                                        <td class="py-3.5 px-4">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold"
                                                  :class="{
                                                      'bg-emerald-950/80 text-emerald-300 border border-emerald-800': m.status === 'Active',
                                                      'bg-slate-800 text-slate-400 border border-slate-700': m.status === 'Inactive',
                                                      'bg-rose-950/80 text-rose-300 border border-rose-800': m.status === 'Suspended'
                                                  }">
                                                <span class="w-1.5 h-1.5 rounded-full" :class="{
                                                    'bg-emerald-400': m.status === 'Active',
                                                    'bg-slate-400': m.status === 'Inactive',
                                                    'bg-rose-400': m.status === 'Suspended'
                                                }"></span>
                                                <span x-text="m.status"></span>
                                            </span>
                                        </td>

                                        <!-- Actions -->
                                        <td class="py-3.5 px-4 text-right">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <button type="button" @click="openRecordModal(m)" class="bg-brand-inner hover:bg-slate-800 text-brand-tealLight border border-brand-border text-xs px-2.5 py-1.5 rounded transition-colors font-bold" title="Add Purchase, Service or Points Record">
                                                    <i class="fa-solid fa-plus-minus mr-1"></i>Record
                                                </button>
                                                <button type="button" @click="openHistoryModal(m)" class="bg-brand-inner hover:bg-slate-800 text-sky-300 border border-brand-border text-xs px-2.5 py-1.5 rounded transition-colors" title="View Transaction &amp; Performance History">
                                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                                </button>
                                                <button type="button" @click="openMemberModal('edit', m)" class="bg-brand-inner hover:bg-slate-800 text-amber-300 border border-brand-border text-xs px-2 py-1.5 rounded transition-colors" title="Edit Member Profile">
                                                    <i class="fa-solid fa-pen"></i>
                                                </button>
                                                <button type="button" @click="deleteMember(m)" class="bg-rose-950/80 hover:bg-rose-900 text-rose-300 border border-rose-800 text-xs px-2 py-1.5 rounded transition-colors" title="Delete Member">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 5: SECTIONS & MENU MANAGER -->
            <div x-show="activeTab === 'sections'" x-cloak class="space-y-6">
                <!-- Summary Stats Bar -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm">
                        <div class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">Total Sections</div>
                        <div class="text-2xl font-extrabold text-white mt-1" x-text="sections.length"></div>
                    </div>
                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm">
                        <div class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">Visible on Site</div>
                        <div class="text-2xl font-extrabold text-emerald-400 mt-1 flex items-center gap-2">
                            <span x-text="visibleSectionsCount"></span>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Active</span>
                        </div>
                    </div>
                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm">
                        <div class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">Hidden Sections</div>
                        <div class="text-2xl font-extrabold text-slate-400 mt-1" x-text="hiddenSectionsCount"></div>
                    </div>
                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm">
                        <div class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">In Navigation Menu</div>
                        <div class="text-2xl font-extrabold text-brand-tealLight mt-1" x-text="inMenuSectionsCount"></div>
                    </div>
                </div>

                <!-- Toolbar: Filter & Actions -->
                <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl flex flex-col md:flex-row items-center justify-between gap-4 shadow-sm">
                    <div class="flex flex-wrap items-center gap-1.5 w-full md:w-auto">
                        <template x-for="cat in ['All', 'Interactive & AI', 'Products & Ordering', 'Salon & Services', 'Brand & Trust']" :key="cat">
                            <button type="button" @click="activeSectionFilter = cat"
                                :class="activeSectionFilter === cat ? 'bg-brand-teal text-white font-bold shadow' : 'bg-brand-inner text-slate-300 hover:text-white border border-brand-border'"
                                class="px-3 py-1.5 rounded-lg text-xs transition-all cursor-pointer"
                                x-text="cat">
                            </button>
                        </template>
                    </div>

                    <div class="flex items-center gap-2.5 w-full md:w-auto justify-end">
                        <a href="section_manager.php" target="_blank"
                            class="bg-brand-inner hover:bg-slate-800 text-slate-300 border border-brand-border px-3 py-2 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5" title="Open Dedicated Page">
                            <i class="fa-solid fa-arrow-up-right-from-square text-sky-400"></i>
                            <span class="hidden sm:inline">Standalone Page</span>
                        </a>

                        <button type="button" @click="resetAllSectionsToVisible()" :disabled="isSavingSections"
                            class="bg-brand-inner hover:bg-slate-800 text-slate-200 border border-brand-border px-3.5 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-eye text-emerald-400"></i>
                            <span>Show All</span>
                        </button>

                        <button type="button" @click="saveAllSections()" :disabled="isSavingSections"
                            class="teal-gradient hover:opacity-95 text-white px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all flex items-center gap-2 shadow-md cursor-pointer disabled:opacity-50">
                            <i class="fa-solid fa-floppy-disk" :class="{ 'fa-spin': isSavingSections }"></i>
                            <span x-text="isSavingSections ? 'Saving...' : 'Save All Changes'"></span>
                        </button>
                    </div>
                </div>

                <!-- Drag & Drop Help / Instruction Hint -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between text-xs px-3.5 py-2 text-brand-textMuted bg-brand-cardBg/80 border border-brand-border rounded-xl gap-2 shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-teal-500/15 border border-teal-500/30 text-teal-400 flex items-center justify-center text-xs shrink-0">
                            <i class="fa-solid fa-arrows-up-down"></i>
                        </span>
                        <span><strong>Drag &amp; Drop:</strong> Click &amp; drag any card or grab handle <i class="fa-solid fa-grip-vertical text-teal-400 mx-1"></i> to reorder. Changes auto-save instantly.</span>
                    </div>
                    <div class="flex items-center gap-2 font-mono text-[11px] text-slate-400 shrink-0">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Instant Live Sync</span>
                    </div>
                </div>

                <!-- Sections Cards List -->
                <div class="space-y-3.5">
                    <template x-for="sec in filteredSectionsList" :key="sec.id">
                        <div class="bg-brand-cardBg border rounded-xl p-4 sm:p-5 transition-all shadow-sm duration-150 relative select-none"
                             :class="{
                                 'border-brand-border hover:border-teal-500/50': sec.is_visible && (!draggedSec || draggedSec.id !== sec.id) && dragOverId !== sec.id,
                                 'border-rose-900/40 bg-[#161B2E]/60 opacity-80': !sec.is_visible && (!draggedSec || draggedSec.id !== sec.id) && dragOverId !== sec.id,
                                 'opacity-40 border-2 border-dashed border-teal-400 bg-teal-950/30 scale-[0.98]': draggedSec && draggedSec.id === sec.id,
                                 'border-t-4 border-t-teal-400 bg-[#152347] shadow-2xl scale-[1.01] -translate-y-1': dragOverId === sec.id && draggedSec && draggedSec.id !== sec.id
                             }"
                             draggable="true"
                             @dragstart="onDragStart($event, sec)"
                             @dragover.prevent="onDragOver($event, sec)"
                             @dragenter.prevent="onDragEnter($event, sec)"
                             @dragleave="onDragLeave($event, sec)"
                             @drop.prevent="onDrop($event, sec)"
                             @dragend="onDragEnd($event)">
                            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                                <div class="flex items-start gap-3 sm:gap-3.5 flex-1 min-w-0">
                                    <!-- Drag Handle -->
                                    <div class="cursor-grab active:cursor-grabbing text-slate-500 hover:text-teal-400 p-1.5 flex items-center justify-center shrink-0 transition-colors mt-0.5 rounded hover:bg-slate-800"
                                         title="Drag to reorder section">
                                        <i class="fa-solid fa-grip-vertical text-base sm:text-lg"></i>
                                    </div>

                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-white shrink-0 mt-0.5 shadow-sm"
                                         :class="sec.is_visible ? 'teal-gradient' : 'bg-slate-800 text-slate-400 border border-slate-700'">
                                        <i :class="sec.menu_icon || 'fa-solid fa-layer-group'"></i>
                                    </div>
                                    <div class="space-y-1 min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="font-heading font-bold text-sm sm:text-base text-white tracking-tight" x-text="sec.name"></h3>
                                            <span class="text-[10px] px-2 py-0.5 rounded-full font-mono font-semibold"
                                                  :class="{
                                                      'bg-sky-500/20 text-sky-300 border border-sky-500/30': sec.category === 'Interactive & AI',
                                                      'bg-amber-500/20 text-amber-300 border border-amber-500/30': sec.category === 'Products & Ordering',
                                                      'bg-purple-500/20 text-purple-300 border border-purple-500/30': sec.category === 'Salon & Services',
                                                      'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30': sec.category === 'Brand & Trust'
                                                  }" x-text="sec.category"></span>
                                            <span class="text-[10px] px-2 py-0.5 rounded-md bg-slate-800 border border-slate-700 text-slate-400 font-mono" x-text="'#' + sec.section_key"></span>
                                        </div>
                                        <p class="text-xs text-brand-textMuted line-clamp-2" x-text="sec.description"></p>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-3 sm:gap-5 w-full lg:w-auto justify-between lg:justify-end pt-3 lg:pt-0 border-t lg:border-t-0 border-brand-border/60" draggable="false" @dragstart.stop.prevent="">
                                    <!-- Display Order Controls (Move Up/Down & Sequence Number) -->
                                    <div class="flex items-center gap-1.5 bg-brand-inner/90 border border-brand-border/80 rounded-xl px-2.5 py-1.5 shadow-inner">
                                        <div class="text-left leading-tight mr-1">
                                            <span class="text-[10px] text-brand-textMuted font-bold uppercase tracking-wider block">Order</span>
                                            <span class="text-xs font-mono font-extrabold text-teal-300" x-text="'#' + sec.sort_order"></span>
                                        </div>
                                        <input type="number" min="1" max="99" 
                                               x-model.number="sec.sort_order" 
                                               @change="normalizeAndSaveSectionOrders()"
                                               class="w-12 text-center bg-brand-darkBg border border-brand-border rounded-lg py-1 text-xs font-mono font-bold text-white outline-none focus:border-brand-teal"
                                               title="Type custom display sequence number">
                                        <div class="flex flex-col gap-0.5">
                                            <button type="button" @click="moveSection(sec, -1)" :disabled="isFirstSection(sec)"
                                                class="w-5 h-4.5 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white flex items-center justify-center text-[9px] disabled:opacity-30 disabled:cursor-not-allowed transition-colors cursor-pointer" title="Move Up (Appear Earlier)">
                                                <i class="fa-solid fa-chevron-up"></i>
                                            </button>
                                            <button type="button" @click="moveSection(sec, 1)" :disabled="isLastSection(sec)"
                                                class="w-5 h-4.5 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white flex items-center justify-center text-[9px] disabled:opacity-30 disabled:cursor-not-allowed transition-colors cursor-pointer" title="Move Down (Appear Later)">
                                                <i class="fa-solid fa-chevron-down"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Toggle 1: Show on Homepage -->
                                    <div class="flex items-center gap-2.5">
                                        <button type="button" @click="toggleSectionVisibility(sec)"
                                            :class="sec.is_visible ? 'bg-emerald-500' : 'bg-slate-700'"
                                            class="w-13 h-7 rounded-full p-1 transition-colors duration-200 relative flex-shrink-0 cursor-pointer focus:outline-none">
                                            <div :class="sec.is_visible ? 'translate-x-6' : 'translate-x-0'"
                                                 class="w-5 h-5 rounded-full bg-white transition-transform duration-200 shadow-md"></div>
                                        </button>
                                        <div class="text-left leading-tight">
                                            <span class="text-xs font-bold block" :class="sec.is_visible ? 'text-emerald-400' : 'text-slate-400'"
                                                  x-text="sec.is_visible ? 'Visible' : 'Hidden'"></span>
                                            <span class="text-[10px] text-brand-textMuted">On Homepage</span>
                                        </div>
                                    </div>

                                    <!-- Toggle 2: Show in Navigation Menu -->
                                    <div class="flex items-center gap-2.5">
                                        <button type="button" @click="toggleSectionMenu(sec)"
                                            :class="sec.in_menu ? 'bg-brand-teal' : 'bg-slate-700'"
                                            class="w-13 h-7 rounded-full p-1 transition-colors duration-200 relative flex-shrink-0 cursor-pointer focus:outline-none">
                                            <div :class="sec.in_menu ? 'translate-x-6' : 'translate-x-0'"
                                                 class="w-5 h-5 rounded-full bg-white transition-transform duration-200 shadow-md"></div>
                                        </button>
                                        <div class="text-left leading-tight">
                                            <span class="text-xs font-bold block" :class="sec.in_menu ? 'text-brand-tealLight' : 'text-slate-400'"
                                                  x-text="sec.in_menu ? 'In Nav' : 'No Nav'"></span>
                                            <span class="text-[10px] text-brand-textMuted">Top &amp; Mobile</span>
                                        </div>
                                    </div>

                                    <!-- Menu Title Input -->
                                    <div class="w-32 hidden sm:block">
                                        <label class="text-[10px] text-slate-400 block mb-0.5">Nav Label</label>
                                        <input type="text" x-model="sec.menu_title" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-2.5 py-1.5 text-xs text-white outline-none focus:border-brand-teal">
                                    </div>

                                    <!-- Quick Jump -->
                                    <a :href="'/#' + sec.section_key" target="_blank"
                                       class="w-8 h-8 rounded-lg bg-brand-inner hover:bg-slate-800 border border-brand-border flex items-center justify-center text-slate-400 hover:text-white transition-colors"
                                       title="Preview Section on Live Site">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- TAB 6: INVOICE & RECEIPT MANAGER -->
            <div x-show="activeTab === 'invoices'" x-cloak class="space-y-6">
                <!-- Summary Stats Bar -->
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 sm:gap-4">
                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm">
                        <div class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">Total Invoices</div>
                        <div class="text-2xl font-extrabold text-white mt-1" x-text="invoiceStats.total_count || 0"></div>
                    </div>
                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm">
                        <div class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Total Revenue</div>
                        <div class="text-2xl font-extrabold text-emerald-400 mt-1 font-mono">$<span x-text="Number(invoiceStats.total_amount_all || 0).toLocaleString('en-AU', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span></div>
                    </div>
                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm">
                        <div class="text-xs font-bold text-brand-tealLight uppercase tracking-wider">Paid Amount</div>
                        <div class="text-2xl font-extrabold text-brand-tealLight mt-1 font-mono">$<span x-text="Number(invoiceStats.total_paid || 0).toLocaleString('en-AU', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span></div>
                    </div>
                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm">
                        <div class="text-xs font-bold text-rose-400 uppercase tracking-wider flex items-center justify-between">
                            <span>Unpaid / Due</span>
                            <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-rose-500/20 text-rose-300 border border-rose-500/30" x-text="invoiceStats.unpaid_count || 0"></span>
                        </div>
                        <div class="text-2xl font-extrabold text-rose-400 mt-1 font-mono">$<span x-text="Number(invoiceStats.total_unpaid || 0).toLocaleString('en-AU', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span></div>
                    </div>
                    <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm">
                        <div class="text-xs font-bold text-amber-300 uppercase tracking-wider">This Month</div>
                        <div class="text-2xl font-extrabold text-amber-300 mt-1 font-mono">$<span x-text="Number(invoiceStats.this_month_paid || 0).toLocaleString('en-AU', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span></div>
                    </div>
                </div>

                <!-- Toolbar: Filter, Search & Create Action -->
                <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl flex flex-col md:flex-row items-center justify-between gap-4 shadow-sm">
                    <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                        <!-- Search Bar -->
                        <div class="relative w-full sm:w-64">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" x-model="invoiceSearchQuery" placeholder="Search customer, invoice #, phone..."
                                class="w-full bg-brand-darkBg border border-brand-border rounded-lg pl-9 pr-3 py-2 text-xs text-white placeholder-slate-500 outline-none focus:border-brand-teal">
                        </div>

                        <!-- Status Filter -->
                        <div class="flex items-center gap-1 bg-brand-inner border border-brand-border rounded-lg p-1 text-xs">
                            <template x-for="st in ['All', 'Paid', 'Unpaid', 'Quote']" :key="st">
                                <button type="button" @click="invoiceStatusFilter = st"
                                    :class="invoiceStatusFilter === st ? 'bg-brand-teal text-white font-bold shadow' : 'text-slate-400 hover:text-white'"
                                    class="px-3 py-1 rounded transition-all cursor-pointer" x-text="st">
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 w-full md:w-auto justify-end">
                        <button type="button" @click="loadInvoices()"
                            class="bg-brand-inner hover:bg-slate-800 text-slate-300 border border-brand-border px-3 py-2 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5" title="Refresh Invoices">
                            <i class="fa-solid fa-rotate" :class="{ 'fa-spin': isLoadingInvoices }"></i>
                            <span class="hidden sm:inline">Refresh</span>
                        </button>

                        <button type="button" @click="openNewInvoiceModal()"
                            class="teal-gradient hover:opacity-95 text-white px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all flex items-center gap-2 shadow-md cursor-pointer">
                            <i class="fa-solid fa-plus"></i>
                            <span>+ Create Invoice / Receipt</span>
                        </button>
                    </div>
                </div>

                <!-- Invoices List Table -->
                <div class="bg-brand-cardBg border border-brand-border rounded-xl overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-brand-inner text-brand-textMuted uppercase tracking-wider text-[11px] border-b border-brand-border">
                                <tr>
                                    <th class="py-3 px-4 font-semibold">Invoice #</th>
                                    <th class="py-3 px-4 font-semibold">Date</th>
                                    <th class="py-3 px-4 font-semibold">Customer</th>
                                    <th class="py-3 px-4 font-semibold">Items</th>
                                    <th class="py-3 px-4 font-semibold">Total Amount</th>
                                    <th class="py-3 px-4 font-semibold">Payment</th>
                                    <th class="py-3 px-4 font-semibold">Status</th>
                                    <th class="py-3 px-4 font-semibold text-right">Quick Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-brand-border/60">
                                <template x-if="filteredInvoices.length === 0">
                                    <tr>
                                        <td colspan="8" class="text-center py-12 text-slate-400">
                                            <div class="max-w-sm mx-auto space-y-3">
                                                <i class="fa-solid fa-file-invoice-dollar text-4xl text-slate-600"></i>
                                                <p class="font-medium text-slate-300">No invoices or receipts found</p>
                                                <p class="text-xs text-slate-500">Create your first invoice with customer details, multiple items, and export via PDF, Print, WhatsApp or Telegram.</p>
                                                <button type="button" @click="openNewInvoiceModal()" class="teal-gradient text-white text-xs font-bold px-4 py-2 rounded-lg shadow mt-2">
                                                    + Create First Invoice
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <template x-for="inv in filteredInvoices" :key="inv.id">
                                    <tr class="hover:bg-brand-inner/40 transition-colors">
                                        <!-- Invoice # & Type -->
                                        <td class="py-3.5 px-4">
                                            <div class="font-mono font-bold text-white tracking-wide" x-text="inv.invoice_number"></div>
                                            <span class="text-[10px] uppercase font-semibold px-1.5 py-0.5 rounded mt-0.5 inline-block"
                                                :class="{
                                                    'bg-amber-500/20 text-amber-300 border border-amber-500/30': inv.invoice_type === 'Tax Invoice',
                                                    'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30': inv.invoice_type === 'Receipt',
                                                    'bg-sky-500/20 text-sky-300 border border-sky-500/30': inv.invoice_type === 'Quote'
                                                }" x-text="inv.invoice_type"></span>
                                        </td>
                                        <!-- Date -->
                                        <td class="py-3.5 px-4 font-mono text-slate-300">
                                            <div x-text="inv.invoice_date"></div>
                                            <div x-show="inv.due_date" class="text-[10px] text-slate-400">Due: <span x-text="inv.due_date"></span></div>
                                        </td>
                                        <!-- Customer -->
                                        <td class="py-3.5 px-4">
                                            <div class="font-bold text-white flex items-center gap-1.5">
                                                <i class="fa-solid fa-user text-slate-500 text-[10px]"></i>
                                                <span x-text="inv.customer_name"></span>
                                            </div>
                                            <div x-show="inv.customer_phone" class="text-[11px] text-slate-400 font-mono flex items-center gap-1 mt-0.5">
                                                <i class="fa-solid fa-phone text-slate-500 text-[9px]"></i>
                                                <span x-text="inv.customer_phone"></span>
                                            </div>
                                        </td>
                                        <!-- Items count -->
                                        <td class="py-3.5 px-4">
                                            <span class="bg-brand-inner border border-brand-border px-2 py-1 rounded text-slate-300 text-[11px] font-medium">
                                                <i class="fa-solid fa-bag-shopping text-brand-tealLight mr-1"></i>
                                                <span x-text="(inv.item_count || 1) + ' Item' + ((inv.item_count || 1) > 1 ? 's' : '')"></span>
                                            </span>
                                        </td>
                                        <!-- Total Amount -->
                                        <td class="py-3.5 px-4">
                                            <div class="font-mono font-bold text-sm text-emerald-400">
                                                $<span x-text="Number(inv.total_amount || 0).toFixed(2)"></span>
                                            </div>
                                            <div x-show="Number(inv.tax_amount || 0) > 0" class="text-[10px] text-slate-400">
                                                Inc. GST $<span x-text="Number(inv.tax_amount || 0).toFixed(2)"></span>
                                            </div>
                                        </td>
                                        <!-- Payment Method -->
                                        <td class="py-3.5 px-4">
                                            <span class="text-slate-300 font-medium" x-text="inv.payment_method || 'Card'"></span>
                                        </td>
                                        <!-- Status Badge -->
                                        <td class="py-3.5 px-4">
                                            <button type="button" @click="quickToggleInvoiceStatus(inv)"
                                                class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full border transition-all cursor-pointer"
                                                :class="{
                                                    'bg-emerald-950/80 text-emerald-300 border-emerald-600 hover:bg-emerald-900': inv.status === 'Paid',
                                                    'bg-rose-950/80 text-rose-300 border-rose-600 hover:bg-rose-900 animate-pulse': inv.status !== 'Paid' && inv.status !== 'Quote',
                                                    'bg-sky-950/80 text-sky-300 border-sky-600 hover:bg-sky-900': inv.status === 'Quote'
                                                }"
                                                :title="'Click to toggle: currently ' + inv.status">
                                                <i :class="inv.status === 'Paid' ? 'fa-solid fa-check-circle mr-1' : 'fa-solid fa-clock mr-1'"></i>
                                                <span x-text="inv.status"></span>
                                            </button>
                                        </td>
                                        <!-- Quick Actions -->
                                        <td class="py-3.5 px-4 text-right">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <!-- View / Print / PDF -->
                                                <button type="button" @click="openInvoicePreview(inv)"
                                                    class="p-2 rounded-lg bg-teal-500/15 hover:bg-teal-500/30 text-teal-300 border border-teal-500/30 transition-colors"
                                                    title="View, Print & PDF">
                                                    <i class="fa-solid fa-print"></i>
                                                </button>
                                                <!-- WhatsApp -->
                                                <button type="button" @click="sendWhatsAppInvoice(inv)"
                                                    class="p-2 rounded-lg bg-emerald-500/15 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/30 transition-colors"
                                                    title="Send WhatsApp Receipt">
                                                    <i class="fa-brands fa-whatsapp text-sm"></i>
                                                </button>
                                                <!-- Telegram -->
                                                <button type="button" @click="sendTelegramInvoice(inv)"
                                                    class="p-2 rounded-lg bg-sky-500/15 hover:bg-sky-500/30 text-sky-300 border border-sky-500/30 transition-colors"
                                                    title="Send Telegram Push to Phone">
                                                    <i class="fa-brands fa-telegram text-sm"></i>
                                                </button>
                                                <!-- Edit -->
                                                <button type="button" @click="editInvoice(inv)"
                                                    class="p-2 rounded-lg bg-brand-inner hover:bg-slate-800 text-slate-300 border border-brand-border transition-colors"
                                                    title="Edit Invoice">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <!-- Delete -->
                                                <button type="button" @click="deleteInvoice(inv)"
                                                    class="p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/25 text-rose-300 border border-rose-500/20 transition-colors"
                                                    title="Delete Invoice">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </div>
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

                                    <!-- 노출 / 숨김 상태 배지 (프리뷰) -->
                                    <template x-if="form.is_visible == 0 || form.is_visible === false || form.is_visible === '0'">
                                        <div class="absolute top-3 right-3 bg-amber-950/90 text-amber-300 border border-amber-600 text-[10px] font-bold px-2 py-0.5 rounded shadow flex items-center gap-1 z-10">
                                            <i class="fa-solid fa-eye-slash text-[9px]"></i>
                                            <span>Hidden (미노출)</span>
                                        </div>
                                    </template>

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
                        <!-- 노출 여부 (Website Visibility) 설정 토글 바 -->
                        <div class="bg-brand-inner/70 p-3 rounded-xl border border-brand-border flex items-center justify-between">
                            <div>
                                <label class="block font-bold text-slate-200 text-xs flex items-center gap-1.5">
                                    <i class="fa-solid" :class="form.is_visible ? 'fa-eye text-emerald-400' : 'fa-eye-slash text-slate-400'"></i>
                                    <span>Website Visibility (메인페이지 노출 여부)</span>
                                </label>
                                <p class="text-[11px] text-brand-textMuted mt-0.5">
                                    <span x-show="form.is_visible" class="text-emerald-300">✅ 메인페이지(index.html) 방문자에게 제품이 표시됩니다.</span>
                                    <span x-show="!form.is_visible" class="text-amber-300">⚠️ 메인페이지에서 숨겨지며 관리자 콘솔에서만 확인 가능합니다.</span>
                                </p>
                            </div>
                            <button type="button" 
                                    @click="form.is_visible = form.is_visible ? 0 : 1"
                                    :class="form.is_visible ? 'bg-emerald-600 hover:bg-emerald-500 text-white' : 'bg-slate-700 hover:bg-slate-600 text-slate-300'"
                                    class="px-3.5 py-2 rounded-lg font-bold text-xs flex items-center gap-1.5 shadow transition-all whitespace-nowrap cursor-pointer">
                                <span class="w-2 h-2 rounded-full" :class="form.is_visible ? 'bg-white animate-pulse' : 'bg-slate-400'"></span>
                                <span x-text="form.is_visible ? 'Visible (노출)' : 'Hidden (숨김)'"></span>
                            </button>
                        </div>
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
                                    <option value="Men's Toupee">Men's Toupee</option>
                                    <option value="Women's topper">Women's topper</option>
                                    <option value="Wig">Wig</option>
                                    <option value="Hair Extension">Hair Extension</option>
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
                    <div class="w-16 h-16 rounded bg-white p-1 flex items-center justify-center border border-brand-border">
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

    <!-- BOOKING DETAIL MODAL -->
    <div x-show="bookingDetailModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-brand-darkBg/90 backdrop-blur-sm overflow-y-auto">
        <div class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-lg w-full p-6 space-y-5 shadow-2xl my-6" @click.away="closeBookingDetail()">
            <!-- Header -->
            <div class="flex justify-between items-center border-b border-brand-border pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg teal-gradient flex items-center justify-center text-white text-sm shadow">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-white text-base">Fitting Appointment Details</h3>
                        <span class="text-xs font-mono font-bold text-brand-tealLight" x-text="selectedBooking ? '#FIT-' + selectedBooking.id : ''"></span>
                    </div>
                </div>
                <button type="button" @click="closeBookingDetail()" class="text-slate-400 hover:text-white p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Body Content -->
            <template x-if="selectedBooking">
                <div class="space-y-4 text-xs">
                    <!-- Status & Time Banner -->
                    <div class="p-3 rounded-xl border flex items-center justify-between"
                         :class="{
                             'bg-amber-950/60 border-amber-600/60 text-amber-200': selectedBooking.status === 'Pending',
                             'bg-emerald-950/60 border-emerald-600/60 text-emerald-200': selectedBooking.status === 'Confirmed',
                             'bg-sky-950/60 border-sky-600/60 text-sky-200': selectedBooking.status === 'Completed',
                             'bg-rose-950/60 border-rose-600/60 text-rose-200': selectedBooking.status === 'Cancelled'
                         }">
                        <div>
                            <span class="text-[10px] uppercase font-bold tracking-wider opacity-80 block">Appointment Date &amp; Time</span>
                            <span class="text-sm font-bold text-white font-mono flex items-center gap-1.5 mt-0.5">
                                <i class="fa-regular fa-calendar-days text-brand-tealLight"></i>
                                <span x-text="selectedBooking.booking_date"></span>
                                <span class="text-slate-400">|</span>
                                <i class="fa-regular fa-clock text-brand-tealLight"></i>
                                <span x-text="selectedBooking.booking_time"></span>
                            </span>
                        </div>
                        <div>
                            <select x-model="selectedBooking.status" 
                                    @change="updateBookingStatus(selectedBooking.id, $event.target.value)"
                                    class="text-xs font-bold rounded-lg px-2.5 py-1.5 outline-none cursor-pointer border bg-brand-darkBg text-white">
                                <option value="Pending">🟡 Pending</option>
                                <option value="Confirmed">🟢 Confirmed</option>
                                <option value="Completed">🔵 Completed</option>
                                <option value="Cancelled">🔴 Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <!-- Customer Info Grid -->
                    <div class="grid grid-cols-2 gap-3 bg-brand-inner/70 p-3.5 rounded-xl border border-brand-border">
                        <div>
                            <span class="text-slate-400 text-[11px] block">Customer Name</span>
                            <span class="text-white font-bold text-sm" x-text="selectedBooking.customer_name"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 text-[11px] block">Phone / WhatsApp</span>
                            <a :href="'https://wa.me/' + formatWhatsAppPhone(selectedBooking.phone)" target="_blank" class="text-emerald-400 hover:text-emerald-300 font-mono font-bold flex items-center gap-1 mt-0.5">
                                <i class="fa-brands fa-whatsapp"></i>
                                <span x-text="selectedBooking.phone"></span>
                            </a>
                        </div>
                        <div class="col-span-2" x-show="selectedBooking.email">
                            <span class="text-slate-400 text-[11px] block">Email</span>
                            <span class="text-slate-200 font-mono" x-text="selectedBooking.email"></span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-slate-400 text-[11px] block">Selected Service</span>
                            <span class="text-brand-tealLight font-bold" x-text="selectedBooking.service_type"></span>
                        </div>
                    </div>

                    <!-- Notes / Memo -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="font-bold text-slate-300">Admin Notes / Remarks</label>
                            <button type="button" @click="saveSelectedBookingNotes()" class="text-brand-tealLight hover:underline text-[11px] font-bold">
                                <i class="fa-solid fa-floppy-disk mr-1"></i>Save Notes
                            </button>
                        </div>
                        <textarea x-model="selectedBooking.notes" 
                                  rows="3" 
                                  placeholder="Customer requirements, toupee base preferences, etc..."
                                  class="w-full bg-brand-darkBg border border-brand-border rounded-lg p-2.5 text-white outline-none focus:border-brand-teal leading-relaxed"></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-2 pt-2 border-t border-brand-border">
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" 
                                    @click="confirmAndWhatsApp(selectedBooking)" 
                                    class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-2.5 px-3 rounded-xl transition-all flex items-center justify-center gap-2 shadow-md">
                                <i class="fa-brands fa-whatsapp text-sm"></i>
                                <span>Confirm & WhatsApp</span>
                            </button>
                            <button type="button" 
                                    @click="addToGoogleCalendar(selectedBooking)" 
                                    class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2.5 px-3 rounded-xl transition-all flex items-center justify-center gap-2 shadow-md">
                                <i class="fa-regular fa-calendar-plus text-sm"></i>
                                <span>Add to Google Cal</span>
                            </button>
                        </div>
                        <div class="flex justify-between items-center pt-1">
                            <button type="button" 
                                    @click="deleteBooking(selectedBooking); closeBookingDetail()" 
                                    class="text-rose-400 hover:text-rose-300 text-xs font-bold flex items-center gap-1.5 px-2 py-1">
                                <i class="fa-solid fa-trash-can"></i> Delete Booking
                            </button>
                            <button type="button" 
                                    @click="closeBookingDetail()" 
                                    class="px-4 py-2 bg-brand-inner hover:bg-slate-700 border border-brand-border text-slate-200 rounded-lg font-bold">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- SALON HOLIDAY & REGULAR OFF-DAYS MODAL -->
    <div x-show="isHolidayModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-brand-darkBg/90 backdrop-blur-sm overflow-y-auto">
        <div class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-2xl w-full p-6 space-y-5 shadow-2xl my-6" @click.away="isHolidayModalOpen = false">
            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b border-brand-border pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center text-base shadow">
                        <i class="fa-solid fa-calendar-xmark"></i>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-white text-base sm:text-lg">Salon Holiday & Regular Off-Day Settings (휴무일 설정)</h3>
                        <p class="text-[11px] text-slate-400">Manage weekly recurring closed days and holiday date ranges to block customer bookings.</p>
                    </div>
                </div>
                <button type="button" @click="isHolidayModalOpen = false" class="text-slate-400 hover:text-white p-1.5 transition-colors">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- SECTION 1: WEEKLY REGULAR OFF-DAYS -->
            <div class="bg-brand-inner/70 border border-brand-border/80 rounded-xl p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-white text-xs flex items-center gap-1.5">
                            <i class="fa-solid fa-repeat text-brand-tealLight"></i>
                            <span>Weekly Regular Off-Days (정기 휴일 설정)</span>
                        </h4>
                        <p class="text-[11px] text-slate-400 mt-0.5">Select days of the week when David Hair Salon is regularly closed:</p>
                    </div>
                    <button type="button" @click="saveRegularOffDays()" :disabled="isSavingClosureRules" class="px-3.5 py-1.5 teal-gradient text-white rounded-lg text-xs font-bold shadow hover:opacity-95 flex items-center gap-1.5">
                        <i class="fa-solid fa-spinner fa-spin" x-show="isSavingClosureRules"></i>
                        <i class="fa-solid fa-check" x-show="!isSavingClosureRules"></i>
                        <span>Save Regular Days</span>
                    </button>
                </div>

                <!-- 7 Days Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-7 gap-2 pt-1">
                    <template x-for="day in [
                        { num: 0, en: 'Sun', ko: '일요일' },
                        { num: 1, en: 'Mon', ko: '월요일' },
                        { num: 2, en: 'Tue', ko: '화요일' },
                        { num: 3, en: 'Wed', ko: '수요일' },
                        { num: 4, en: 'Thu', ko: '목요일' },
                        { num: 5, en: 'Fri', ko: '금요일' },
                        { num: 6, en: 'Sat', ko: '토요일' }
                    ]" :key="day.num">
                        <button type="button" 
                                @click="toggleRegularOffDay(day.num)"
                                class="p-2.5 rounded-xl border text-center transition-all flex flex-col items-center justify-center gap-0.5"
                                :class="isDayRegularOff(day.num) ? 'bg-amber-500/20 border-amber-500/60 text-amber-300 font-bold shadow' : 'bg-brand-darkBg border-brand-border/60 text-slate-400 hover:border-slate-500'">
                            <span class="text-xs uppercase" x-text="day.en"></span>
                            <span class="text-[10px]" x-text="day.ko"></span>
                            <span class="text-[9px] px-1.5 py-0.5 rounded mt-1 font-semibold"
                                  :class="isDayRegularOff(day.num) ? 'bg-amber-500 text-slate-950 font-black' : 'bg-slate-800 text-slate-400'"
                                  x-text="isDayRegularOff(day.num) ? 'OFF' : 'OPEN'"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- SECTION 2: SPECIFIC CLOSURE PERIODS -->
            <div class="bg-brand-inner/70 border border-brand-border/80 rounded-xl p-4 space-y-3">
                <div>
                    <h4 class="font-bold text-white text-xs flex items-center gap-1.5">
                        <i class="fa-solid fa-calendar-days text-amber-400"></i>
                        <span>Special Closure Periods (특정 휴무 기간: 언제부터 언제까지)</span>
                    </h4>
                    <p class="text-[11px] text-slate-400 mt-0.5">Block booking for temporary holidays, vacations, or studio renovations:</p>
                </div>

                <!-- Add Form -->
                <form @submit.prevent="addClosurePeriod()" class="grid grid-cols-1 sm:grid-cols-12 gap-2 bg-brand-darkBg/80 p-3 rounded-lg border border-brand-border/60 text-xs">
                    <div class="sm:col-span-3">
                        <label class="block font-bold text-slate-300 text-[10px] mb-1">Start Date (시작일) *</label>
                        <input type="date" x-model="newClosurePeriod.start_date" required class="w-full bg-brand-inner border border-brand-border rounded px-2.5 py-1.5 text-white outline-none focus:border-brand-teal text-xs">
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block font-bold text-slate-300 text-[10px] mb-1">End Date (종료일) *</label>
                        <input type="date" x-model="newClosurePeriod.end_date" required class="w-full bg-brand-inner border border-brand-border rounded px-2.5 py-1.5 text-white outline-none focus:border-brand-teal text-xs">
                    </div>
                    <div class="sm:col-span-4">
                        <label class="block font-bold text-slate-300 text-[10px] mb-1">Reason / Note (사유) *</label>
                        <input type="text" x-model="newClosurePeriod.reason" required placeholder="e.g. Christmas Break, Vacation..." class="w-full bg-brand-inner border border-brand-border rounded px-2.5 py-1.5 text-white outline-none focus:border-brand-teal text-xs">
                    </div>
                    <div class="sm:col-span-2 flex items-end">
                        <button type="submit" :disabled="isSavingClosureRules" class="w-full py-2 bg-brand-teal hover:bg-teal-600 text-white rounded font-bold transition-colors flex items-center justify-center gap-1 shadow">
                            <i class="fa-solid fa-plus text-xs"></i>
                            <span>Add</span>
                        </button>
                    </div>
                </form>

                <!-- List of Active Closure Periods -->
                <div class="space-y-1.5 max-h-48 overflow-y-auto">
                    <div class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Registered Holiday Periods:</div>
                    
                    <template x-if="!closureRules.closure_periods || closureRules.closure_periods.length === 0">
                        <div class="text-center py-4 text-slate-500 text-xs bg-brand-darkBg/50 rounded border border-dashed border-brand-border">
                            No special closure periods registered. The salon will follow regular weekly off-days.
                        </div>
                    </template>

                    <template x-for="p in closureRules.closure_periods" :key="p.id">
                        <div class="flex items-center justify-between p-2.5 bg-brand-darkBg rounded-lg border border-brand-border text-xs">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                <div>
                                    <span class="font-bold text-white font-mono" x-text="p.start_date + ' ~ ' + p.end_date"></span>
                                    <span class="text-amber-300 ml-2 font-medium" x-text="p.reason"></span>
                                </div>
                            </div>
                            <button type="button" @click="deleteClosurePeriod(p)" class="px-2 py-1 bg-rose-950/60 hover:bg-rose-900 border border-rose-500/40 text-rose-300 rounded text-[11px] font-semibold transition-colors flex items-center gap-1">
                                <i class="fa-solid fa-trash-can text-[10px]"></i>
                                <span>Delete</span>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Footer Close -->
            <div class="flex justify-end pt-2 border-t border-brand-border">
                <button type="button" @click="isHolidayModalOpen = false" class="px-4 py-2 bg-brand-inner border border-brand-border rounded-lg text-slate-300 hover:text-white font-semibold text-xs">
                    Close (닫기)
                </button>
            </div>
        </div>
    </div>

    <!-- BOOKING NOTIFICATION SETTINGS MODAL (Telegram & Email) -->
    <div x-show="isNotificationModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-brand-darkBg/90 backdrop-blur-sm overflow-y-auto">
        <div class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-xl w-full p-6 space-y-5 shadow-2xl my-6" @click.away="isNotificationModalOpen = false">
            <div class="flex justify-between items-center border-b border-brand-border pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-sky-500/20 border border-sky-400/40 flex items-center justify-center text-sky-400 text-base shadow">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-white text-base">예약 실시간 알림 설정 (Telegram &amp; Email)</h3>
                        <p class="text-[11px] text-brand-textMuted">고객 예약 신청 시 스마트폰 텔레그램 및 이메일로 즉시 자동 전송</p>
                    </div>
                </div>
                <button type="button" @click="isNotificationModalOpen = false" class="text-slate-400 hover:text-white p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="space-y-4 text-xs">
                <!-- 1. Telegram Notification Section -->
                <div class="bg-brand-inner/70 border border-brand-border rounded-xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fa-brands fa-telegram text-sky-400 text-base"></i>
                            <span class="font-bold text-white text-sm">텔레그램(Telegram) 푸시 알림</span>
                        </div>
                        <label class="flex items-center gap-1.5 cursor-pointer text-slate-300">
                            <input type="checkbox" x-model="notificationConfig.telegram_enabled" true-value="1" false-value="0" class="rounded bg-brand-cardBg border-brand-border text-teal-500 focus:ring-0">
                            <span class="font-semibold text-xs">알림 켜기</span>
                        </label>
                    </div>

                    <div class="space-y-2.5">
                        <div>
                            <label class="block text-slate-400 text-[11px] mb-1 font-semibold">1) Telegram Bot Token (봇 토큰)</label>
                            <input type="text" x-model="notificationConfig.telegram_bot_token" placeholder="예: 7123456789:AAHxxxxxxxxxxxxxxxxxxxx" class="w-full bg-brand-cardBg border border-brand-border rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-teal-400 font-mono">
                            <div class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                                <span>💡 텔레그램 앱에서 <b>@BotFather</b> 검색 ➡️ <b>/newbot</b> 입력 후 발급받은 토큰을 붙여넣으세요.</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-slate-400 text-[11px] mb-1 font-semibold">2) Telegram Chat ID (내 채팅 ID)</label>
                            <input type="text" x-model="notificationConfig.telegram_chat_id" placeholder="예: 123456789" class="w-full bg-brand-cardBg border border-brand-border rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-teal-400 font-mono">
                            <div class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                                <span>💡 텔레그램에서 <b>@userinfobot</b> 검색 ➡️ <b>/start</b> 누르면 나오는 Id 숫자를 입력하세요.</span>
                            </div>
                        </div>

                        <div class="pt-1 flex items-center justify-between">
                            <span class="text-[10px] text-amber-300">⚠️ 봇을 만든 후 본인 봇 채팅방에 들어가서 <b>[시작]</b>을 먼저 눌러주셔야 메시지가 도착합니다.</span>
                            <button type="button" @click="testTelegramAlert()" :disabled="isTestingTelegram" class="px-3 py-1.5 rounded-lg bg-sky-500/20 hover:bg-sky-500/30 border border-sky-400/50 text-sky-300 font-bold text-xs flex items-center gap-1.5 cursor-pointer shrink-0">
                                <i class="fa-solid" :class="isTestingTelegram ? 'fa-spinner fa-spin' : 'fa-paper-plane'"></i>
                                <span x-text="isTestingTelegram ? '전송 중...' : '텔레그램 테스트 발송'"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 2. Email Notification Section -->
                <div class="bg-brand-inner/70 border border-brand-border rounded-xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-envelope text-teal-400 text-sm"></i>
                            <span class="font-bold text-white text-sm">이메일(Email) 알림</span>
                        </div>
                        <label class="flex items-center gap-1.5 cursor-pointer text-slate-300">
                            <input type="checkbox" x-model="notificationConfig.email_enabled" true-value="1" false-value="0" class="rounded bg-brand-cardBg border-brand-border text-teal-500 focus:ring-0">
                            <span class="font-semibold text-xs">알림 켜기</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-slate-400 text-[11px] mb-1 font-semibold">수신 이메일 주소</label>
                        <div class="flex items-center gap-2">
                            <input type="email" x-model="notificationConfig.notification_email" placeholder="shc9607@gmail.com" class="flex-1 bg-brand-cardBg border border-brand-border rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-teal-400 font-mono">
                            <button type="button" @click="testEmailAlert()" :disabled="isTestingEmail" class="px-3 py-2 rounded-lg bg-teal-500/20 hover:bg-teal-500/30 border border-teal-400/50 text-teal-300 font-bold text-xs flex items-center gap-1.5 cursor-pointer shrink-0">
                                <i class="fa-solid" :class="isTestingEmail ? 'fa-spinner fa-spin' : 'fa-paper-plane'"></i>
                                <span x-text="isTestingEmail ? '전송 중...' : '이메일 테스트 발송'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Save -->
            <div class="flex justify-between items-center pt-3 border-t border-brand-border">
                <span class="text-[11px] text-slate-400">설정을 저장하면 새 예약 시 즉시 발송됩니다.</span>
                <div class="flex items-center gap-2">
                    <button type="button" @click="isNotificationModalOpen = false" class="px-4 py-2 bg-brand-inner border border-brand-border rounded-lg text-slate-300 hover:text-white font-semibold text-xs">
                        취소
                    </button>
                    <button type="button" @click="saveNotificationConfig()" :disabled="isSavingNotificationConfig" class="px-4 py-2 teal-gradient rounded-lg text-white font-bold text-xs shadow hover:opacity-95 flex items-center gap-1.5">
                        <i class="fa-solid" :class="isSavingNotificationConfig ? 'fa-spinner fa-spin' : 'fa-check'"></i>
                        <span x-text="isSavingNotificationConfig ? '저장 중...' : '설정 저장 (Save)'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- NEW BOOKING MANUAL MODAL -->
    <div x-show="newBookingModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-brand-darkBg/90 backdrop-blur-sm overflow-y-auto">
        <div class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl my-6" @click.away="newBookingModalOpen = false">
            <div class="flex justify-between items-center border-b border-brand-border pb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg teal-gradient flex items-center justify-center text-white text-sm shadow">
                        <i class="fa-solid fa-calendar-plus"></i>
                    </div>
                    <h3 class="font-heading font-bold text-white text-base">Add New Fitting Appointment</h3>
                </div>
                <button type="button" @click="newBookingModalOpen = false" class="text-slate-400 hover:text-white p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form @submit.prevent="submitNewBooking()" class="space-y-3.5 text-xs">
                <div>
                    <label class="block font-bold text-slate-300 mb-1">Customer Full Name *</label>
                    <input type="text" x-model="newBookingForm.customer_name" required placeholder="e.g. David Kim" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">Phone / WhatsApp *</label>
                        <input type="text" x-model="newBookingForm.phone" required placeholder="+61 412 345 678" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal font-mono">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">Email (Optional)</label>
                        <input type="email" x-model="newBookingForm.email" placeholder="customer@example.com" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal font-mono">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-300 mb-1">Fitting Service *</label>
                    <select x-model="newBookingForm.service_type" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal">
                        <option value="Free Consultation & Hairline Assessment ($0 Free)">Free Consultation & Hairline Assessment ($0 Free)</option>
                        <option value="New Hairpiece Fitting & Cut-in Styling - Member Only ($145 AUD)">New Hairpiece Fitting & Cut-in Styling - Member Only ($145 AUD)</option>
                        <option value="Re-bond Maintenance & Scalp Clean - Member Only ($80 AUD)">Re-bond Maintenance & Scalp Clean - Member Only ($80 AUD)</option>
                        <option value="Double Side Tape Bonding & Scalp Clean - Member Only ($80 AUD)">Double Side Tape Bonding & Scalp Clean - Member Only ($80 AUD)</option>
                        <option value="Hairpiece Blending Haircut - Member Only ($20 AUD)">Hairpiece Blending Haircut - Member Only ($20 AUD)</option>
                        <option value="Topper Treatment Shampoo & Treatment - Member Only ($30 AUD)">Topper Treatment Shampoo & Treatment - Member Only ($30 AUD)</option>
                        <option value="Colour - Member Only ($50 AUD)">Colour - Member Only ($50 AUD)</option>
                        <option value="Hi-Lite / Low Ble - Member Only ($50 AUD)">Hi-Lite / Low Ble - Member Only ($50 AUD)</option>
                        <option value="Perm Man - Member Only ($50 AUD)">Perm Man - Member Only ($50 AUD)</option>
                        <option value="Perm Woman - Member Only ($80 AUD)">Perm Woman - Member Only ($80 AUD)</option>
                        <option value="Minor Adjustment & Perimeter Touch-up - Member Only ($50 AUD)">Minor Adjustment & Perimeter Touch-up - Member Only ($50 AUD)</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">Booking Date *</label>
                        <input type="date" x-model="newBookingForm.booking_date" required class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal font-mono">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">Booking Time *</label>
                        <select x-model="newBookingForm.booking_time" required class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal font-mono">
                            <option value="08:00 AM">08:00 AM</option>
                            <option value="09:00 AM">09:00 AM</option>
                            <option value="10:00 AM">10:00 AM</option>
                            <option value="11:00 AM">11:00 AM</option>
                            <option value="12:00 PM">12:00 PM</option>
                            <option value="01:00 PM">01:00 PM</option>
                            <option value="02:00 PM">02:00 PM</option>
                            <option value="03:00 PM">03:00 PM</option>
                            <option value="04:00 PM">04:00 PM</option>
                            <option value="05:00 PM">05:00 PM</option>
                            <option value="06:00 PM">06:00 PM</option>
                            <option value="07:00 PM">07:00 PM</option>
                            <option value="08:00 PM">08:00 PM</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-300 mb-1">Consultation Notes</label>
                    <textarea x-model="newBookingForm.notes" rows="2" placeholder="Customer requests or specifications..." class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t border-brand-border">
                    <button type="button" @click="newBookingModalOpen = false" class="px-4 py-2 bg-brand-inner border border-brand-border rounded-lg text-slate-300 hover:text-white font-semibold">Cancel</button>
                    <button type="submit" class="px-5 py-2 teal-gradient rounded-lg text-white font-bold shadow-md">Create Booking</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MEMBER PROFILE MODAL (ADD / EDIT) -->
    <div x-show="isMemberModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-brand-darkBg/90 backdrop-blur-sm overflow-y-auto">
        <div class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-2xl w-full p-6 space-y-4 shadow-2xl my-6" @click.away="isMemberModalOpen = false">
            <div class="flex justify-between items-center border-b border-brand-border pb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg teal-gradient flex items-center justify-center text-white text-sm shadow">
                        <i class="fa-solid fa-user-gear"></i>
                    </div>
                    <h3 class="font-heading font-bold text-white text-lg" x-text="memberModalMode === 'edit' ? 'Edit Member Profile' : 'Add New Member'"></h3>
                    <span x-show="memberModalMode === 'edit'" class="text-[11px] bg-brand-inner border border-brand-border px-2 py-0.5 rounded text-brand-tealLight font-mono" x-text="memberForm.member_code"></span>
                </div>
                <button type="button" @click="isMemberModalOpen = false" class="text-slate-400 hover:text-white p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form @submit.prevent="saveMember()" class="space-y-4 text-xs">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">Full Name *</label>
                        <input type="text" x-model="memberForm.full_name" required placeholder="John Smith" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">Email Address *</label>
                        <input type="email" x-model="memberForm.email" required placeholder="client@example.com" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white font-mono outline-none focus:border-brand-teal">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">Phone / WhatsApp</label>
                        <input type="text" x-model="memberForm.phone" placeholder="+61 412 345 678" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white font-mono outline-none focus:border-brand-teal">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">
                            <span x-text="memberModalMode === 'edit' ? 'Change Password (Leave blank to keep)' : 'Password *'"></span>
                        </label>
                        <input type="password" x-model="memberForm.password" :required="memberModalMode === 'add'" placeholder="••••••••" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white font-mono outline-none focus:border-brand-teal">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">Hair System Specification</label>
                        <select x-model="memberForm.hair_system_type" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal">
                            <option value="Full Skin System (0.03mm)">Full Skin System (0.03mm)</option>
                            <option value="Full Swiss Lace">Full Swiss Lace</option>
                            <option value="Lace Front + PU Perimeter">Lace Front + PU Perimeter</option>
                            <option value="Silk Top System">Silk Top System</option>
                            <option value="CNC Premier Virgin Hair">CNC Premier Virgin Hair</option>
                            <option value="Women Silk Topper">Women Silk Topper</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">Membership Tier</label>
                        <select x-model="memberForm.tier" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal font-semibold">
                            <option value="Bronze">Bronze Tier ($0 - $499)</option>
                            <option value="Silver">Silver Tier ($500 - $1,499)</option>
                            <option value="Gold">Gold Tier ($1,500 - $2,999)</option>
                            <option value="VIP Partner">VIP Partner Tier ($3,000+)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">Total Spent ($ AUD)</label>
                        <input type="number" step="0.01" min="0" x-model="memberForm.total_spent" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white font-mono outline-none focus:border-brand-teal">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">Salon Visits</label>
                        <input type="number" min="0" x-model="memberForm.visit_count" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white font-mono outline-none focus:border-brand-teal">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">Loyalty Points</label>
                        <input type="number" min="0" x-model="memberForm.points" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white font-mono outline-none focus:border-brand-teal">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">Account Status</label>
                        <select x-model="memberForm.status" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal">
                            <option value="Active">🟢 Active</option>
                            <option value="Inactive">⚪ Inactive</option>
                            <option value="Suspended">🔴 Suspended</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">Referred By (Referral Code)</label>
                        <input type="text" x-model="memberForm.referred_by" placeholder="e.g. DH-REF-ABC1" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white font-mono outline-none focus:border-brand-teal">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-300 mb-1">Consultation / Fitting Notes</label>
                    <textarea x-model="memberForm.notes" rows="2" placeholder="Custom base measurements, haircut preferences, skin sensitivity notes..." class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal leading-relaxed"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t border-brand-border">
                    <button type="button" @click="isMemberModalOpen = false" class="px-4 py-2 bg-brand-inner border border-brand-border rounded-lg text-slate-300 hover:text-white font-semibold">Cancel</button>
                    <button type="submit" class="px-5 py-2 teal-gradient rounded-lg text-white font-bold shadow-md">
                        <span x-text="memberModalMode === 'edit' ? 'Save Changes' : 'Create Member'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- RECORD CLIENT ACTIVITY MODAL (PURCHASE / SERVICE / POINTS) -->
    <div x-show="isRecordModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-brand-darkBg/90 backdrop-blur-sm overflow-y-auto">
        <div class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl my-6" @click.away="isRecordModalOpen = false">
            <div class="flex justify-between items-center border-b border-brand-border pb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg teal-gradient flex items-center justify-center text-white text-sm shadow">
                        <i class="fa-solid fa-plus-minus"></i>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-white text-base">Record Activity &amp; Performance</h3>
                        <p class="text-[11px] text-slate-400" x-text="selectedMemberForRecord ? selectedMemberForRecord.full_name + ' (' + selectedMemberForRecord.member_code + ')' : ''"></p>
                    </div>
                </div>
                <button type="button" @click="isRecordModalOpen = false" class="text-slate-400 hover:text-white p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form @submit.prevent="submitMemberRecord()" class="space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-300 mb-1">Activity / Transaction Type *</label>
                    <select x-model="recordForm.type" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal">
                        <option value="purchase">🛍️ Toupee / Product Purchase (Increases Spent &amp; Tiers)</option>
                        <option value="service">✂️ Salon Fitting &amp; Maintenance Service (Increases Visits)</option>
                        <option value="points_add">🎁 Loyalty Bonus Points Award</option>
                        <option value="points_redeem">🏷️ Points Redemption (Discount applied)</option>
                        <option value="referral">🤝 Referral Performance Reward (+Points)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-300 mb-1">Description / Service Title *</label>
                    <input type="text" x-model="recordForm.title" required placeholder="e.g. Full Skin System #0.03mm or Re-bond Service" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">Amount ($ AUD)</label>
                        <input type="number" step="0.01" min="0" x-model="recordForm.amount" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white font-mono outline-none focus:border-brand-teal">
                        <p class="text-[10px] text-slate-400 mt-1">Added to member's total spending</p>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">Points Delta (+ or -)</label>
                        <input type="number" step="1" x-model="recordForm.points_delta" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white font-mono outline-none focus:border-brand-teal">
                        <p class="text-[10px] text-slate-400 mt-1">e.g. +100 for earn, -200 for redeem</p>
                    </div>
                </div>

                <div class="p-3 rounded-lg bg-brand-inner/80 border border-brand-border text-[11px] text-slate-300 space-y-1">
                    <div class="font-bold text-brand-tealLight flex items-center gap-1.5">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span>Automatic Tier Progression</span>
                    </div>
                    <p class="text-slate-400">
                        When total spending reaches thresholds, the membership tier automatically promotes:
                        Silver ($500+), Gold ($1,500+), VIP Partner ($3,000+).
                    </p>
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t border-brand-border">
                    <button type="button" @click="isRecordModalOpen = false" class="px-4 py-2 bg-brand-inner border border-brand-border rounded-lg text-slate-300 hover:text-white font-semibold">Cancel</button>
                    <button type="submit" class="px-5 py-2 teal-gradient rounded-lg text-white font-bold shadow-md">Record Activity</button>
                </div>
            </form>
        </div>
    </div>

    <!-- PERFORMANCE HISTORY MODAL -->
    <div x-show="isHistoryModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-brand-darkBg/90 backdrop-blur-sm overflow-y-auto">
        <div class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-2xl w-full p-6 space-y-4 shadow-2xl my-6" @click.away="closeHistoryModal()">
            <div class="flex justify-between items-center border-b border-brand-border pb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-sky-600 flex items-center justify-center text-white text-sm shadow">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-white text-base">Client Performance &amp; Activity Log</h3>
                        <p class="text-[11px] text-slate-400" x-text="selectedMemberHistory ? selectedMemberHistory.full_name + ' (' + selectedMemberHistory.member_code + ')' : ''"></p>
                    </div>
                </div>
                <button type="button" @click="closeHistoryModal()" class="text-slate-400 hover:text-white p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <template x-if="selectedMemberHistory">
                <div class="space-y-4">
                    <div class="grid grid-cols-3 gap-3 bg-brand-inner p-3 rounded-xl border border-brand-border text-center text-xs">
                        <div>
                            <span class="text-[10px] text-slate-400 uppercase tracking-wider block">Current Tier</span>
                            <span class="font-bold text-amber-300 mt-0.5 block" x-text="selectedMemberHistory.tier"></span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 uppercase tracking-wider block">Total Spent</span>
                            <span class="font-bold text-brand-tealLight font-mono mt-0.5 block">$<span x-text="Number(selectedMemberHistory.total_spent || 0).toLocaleString('en-AU', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span></span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 uppercase tracking-wider block">Points Balance</span>
                            <span class="font-bold text-sky-300 font-mono mt-0.5 block" x-text="Number(selectedMemberHistory.points || 0).toLocaleString() + ' Pts'"></span>
                        </div>
                    </div>

                    <div class="border border-brand-border rounded-xl overflow-hidden max-h-72 overflow-y-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-brand-inner/90 text-brand-textMuted uppercase tracking-wider text-[10px] border-b border-brand-border sticky top-0">
                                <tr>
                                    <th class="py-2.5 px-3">Date</th>
                                    <th class="py-2.5 px-3">Type</th>
                                    <th class="py-2.5 px-3">Title / Description</th>
                                    <th class="py-2.5 px-3 text-right">Amount</th>
                                    <th class="py-2.5 px-3 text-right">Points</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-brand-border/60">
                                <template x-if="memberRecordsList.length === 0">
                                    <tr>
                                        <td colspan="5" class="text-center py-8 text-slate-400">
                                            <span>No activity records recorded yet.</span>
                                        </td>
                                    </tr>
                                </template>
                                <template x-for="r in memberRecordsList" :key="r.id">
                                    <tr class="hover:bg-brand-inner/30">
                                        <td class="py-2.5 px-3 font-mono text-[10px] text-slate-400" x-text="r.created_at ? r.created_at.substring(0, 16) : '-'"></td>
                                        <td class="py-2.5 px-3">
                                            <span class="text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded"
                                                  :class="{
                                                      'bg-emerald-950 text-emerald-300 border border-emerald-800': r.type === 'purchase',
                                                      'bg-purple-950 text-purple-300 border border-purple-800': r.type === 'service',
                                                      'bg-sky-950 text-sky-300 border border-sky-800': r.type === 'points_add',
                                                      'bg-rose-950 text-rose-300 border border-rose-800': r.type === 'points_redeem',
                                                      'bg-amber-950 text-amber-300 border border-amber-800': r.type === 'referral'
                                                  }" x-text="r.type"></span>
                                        </td>
                                        <td class="py-2.5 px-3 text-slate-200" x-text="r.title"></td>
                                        <td class="py-2.5 px-3 text-right font-mono font-bold" :class="Number(r.amount) > 0 ? 'text-brand-tealLight' : 'text-slate-400'" x-text="Number(r.amount) > 0 ? '$' + Number(r.amount).toFixed(2) : '-'"></td>
                                        <td class="py-2.5 px-3 text-right font-mono font-bold" :class="Number(r.points_delta) >= 0 ? 'text-emerald-400' : 'text-rose-400'" x-text="(Number(r.points_delta) >= 0 ? '+' : '') + Number(r.points_delta)"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>

            <div class="flex justify-end pt-2 border-t border-brand-border">
                <button type="button" @click="closeHistoryModal()" class="px-4 py-2 bg-brand-inner border border-brand-border rounded-lg text-slate-300 hover:text-white font-semibold text-xs">Close</button>
            </div>
        </div>
    </div>

    <!-- INVOICE CREATE / EDIT MODAL -->
    <div x-show="isInvoiceModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-brand-darkBg/90 backdrop-blur-sm overflow-y-auto">
        <div class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-4xl w-full p-5 sm:p-7 space-y-5 shadow-2xl my-6 max-h-[92vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b border-brand-border pb-3.5">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl teal-gradient flex items-center justify-center text-white text-base shadow">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-white text-lg tracking-tight" x-text="invoiceModalMode === 'edit' ? 'Edit Tax Invoice / Receipt' : 'Create Tax Invoice / Receipt'"></h3>
                        <span class="text-[11px] text-brand-textMuted">Issue professional tax invoice &amp; receipt with live calculations</span>
                    </div>
                </div>
                <button type="button" @click="isInvoiceModalOpen = false" class="text-slate-400 hover:text-white p-1.5 transition-colors">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Modal Body Form -->
            <form @submit.prevent="saveInvoice()" class="space-y-5 text-xs">
                <!-- Row 1: Invoice Meta -->
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 bg-brand-inner/70 p-3.5 rounded-xl border border-brand-border/80">
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">Invoice / Receipt #</label>
                        <div class="relative">
                            <input type="text" x-model="invoiceForm.invoice_number" required
                                class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white font-mono text-xs outline-none focus:border-brand-teal">
                        </div>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">Document Type</label>
                        <select x-model="invoiceForm.invoice_type" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white text-xs outline-none focus:border-brand-teal">
                            <option value="Tax Invoice">Tax Invoice</option>
                            <option value="Receipt">Receipt (Payment Received)</option>
                            <option value="Quote">Quote / Estimate</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">Issue Date</label>
                        <input type="date" x-model="invoiceForm.invoice_date" required
                            class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white font-mono text-xs outline-none focus:border-brand-teal">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">Due Date (Optional)</label>
                        <input type="date" x-model="invoiceForm.due_date"
                            class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white font-mono text-xs outline-none focus:border-brand-teal">
                    </div>
                </div>

                <!-- Row 2: Customer Information -->
                <div class="bg-brand-inner/70 p-3.5 rounded-xl border border-brand-border/80 space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-brand-border/60 pb-2">
                        <span class="font-bold text-brand-tealLight uppercase tracking-wider text-[11px] flex items-center gap-1.5">
                            <i class="fa-solid fa-user-tag"></i> Customer Information
                        </span>
                        <!-- Quick Member Select Dropdown -->
                        <div class="flex items-center gap-2">
                            <span class="text-slate-400 text-[11px]">Quick Member:</span>
                            <select @change="onSelectMemberDropdown($event.target.value)" class="bg-brand-darkBg border border-brand-border rounded-lg px-2.5 py-1 text-slate-200 text-xs outline-none focus:border-brand-teal">
                                <option value="">-- Select Member / VIP --</option>
                                <template x-for="m in members" :key="m.id">
                                    <option :value="m.id" x-text="m.full_name + ' (' + (m.phone || m.email) + ')'"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Customer Name <span class="text-rose-400">*</span></label>
                            <input type="text" x-model="invoiceForm.customer_name" required placeholder="e.g. John Smith"
                                class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white text-xs outline-none focus:border-brand-teal">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Mobile / Phone</label>
                            <input type="text" x-model="invoiceForm.customer_phone" placeholder="e.g. 0433 484 882"
                                class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white font-mono text-xs outline-none focus:border-brand-teal">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Email (Optional)</label>
                            <input type="email" x-model="invoiceForm.customer_email" placeholder="customer@example.com"
                                class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white text-xs outline-none focus:border-brand-teal">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Payment Method</label>
                            <select x-model="invoiceForm.payment_method" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white text-xs outline-none focus:border-brand-teal">
                                <option value="Card / EFTPOS">Card / EFTPOS</option>
                                <option value="Cash">Cash</option>
                                <option value="Bank Transfer">Bank Transfer (EFT)</option>
                                <option value="Afterpay">Afterpay / Zip</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Payment Status</label>
                            <select x-model="invoiceForm.status" class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white text-xs outline-none focus:border-brand-teal font-semibold">
                                <option value="Paid">Paid (Full)</option>
                                <option value="Unpaid / Due">Unpaid / Due</option>
                                <option value="Partial">Partial Payment</option>
                                <option value="Quote">Quote / Draft</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Address / Note (Optional)</label>
                            <input type="text" x-model="invoiceForm.customer_address" placeholder="e.g. Springvale VIC"
                                class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white text-xs outline-none focus:border-brand-teal">
                        </div>
                    </div>
                </div>

                <!-- Row 3: Multi-Item Entry Section -->
                <div class="bg-brand-inner/70 p-3.5 rounded-xl border border-brand-border/80 space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-brand-border/60 pb-2.5">
                        <span class="font-bold text-brand-tealLight uppercase tracking-wider text-[11px] flex items-center gap-1.5">
                            <i class="fa-solid fa-list-check"></i> Products &amp; Services Line Items
                        </span>

                        <!-- Quick Presets -->
                        <div class="flex flex-wrap items-center gap-2">
                            <select @change="onSelectServicePreset($event.target.value); $event.target.value='';" class="bg-brand-darkBg border border-teal-500/50 text-teal-300 rounded-lg px-2.5 py-1 text-xs outline-none cursor-pointer">
                                <option value="">+ Add Salon Service...</option>
                                <template x-for="(s, idx) in salonServicePresets" :key="idx">
                                    <option :value="idx" x-text="s.name + ' ($' + s.price.toFixed(2) + ')'"></option>
                                </template>
                            </select>

                            <select @change="onSelectStockPreset($event.target.value); $event.target.value='';" class="bg-brand-darkBg border border-amber-500/50 text-amber-300 rounded-lg px-2.5 py-1 text-xs outline-none cursor-pointer">
                                <option value="">+ Add From Stock Inventory...</option>
                                <template x-for="item in items" :key="item.id">
                                    <option :value="item.id" x-text="item.name + ' ($' + Number(item.price).toFixed(2) + ')'"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="text-slate-400 uppercase text-[10px] border-b border-brand-border">
                                <tr>
                                    <th class="py-2 px-2 w-7/12 font-semibold">Description / Product Name</th>
                                    <th class="py-2 px-2 w-2/12 font-semibold">Qty</th>
                                    <th class="py-2 px-2 w-2/12 font-semibold">Unit Price ($)</th>
                                    <th class="py-2 px-2 w-2/12 text-right font-semibold">Amount ($)</th>
                                    <th class="py-2 px-1 w-1/12 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-brand-border/50">
                                <template x-for="(it, idx) in invoiceForm.items" :key="idx">
                                    <tr class="align-top">
                                        <!-- Item Description -->
                                        <td class="py-2 px-2">
                                            <input type="text" x-model="it.item_name" required placeholder="e.g. Men Custom Toupee / Hair Fitting"
                                                class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-2.5 py-1.5 text-white text-xs outline-none focus:border-brand-teal font-medium">
                                            <input type="text" x-model="it.description" placeholder="Optional notes / specifications"
                                                class="w-full bg-transparent border-0 text-[11px] text-slate-400 px-2 py-0.5 mt-0.5 outline-none placeholder-slate-600 focus:text-white">
                                        </td>
                                        <!-- Quantity -->
                                        <td class="py-2 px-2">
                                            <input type="number" step="any" min="0.1" x-model.number="it.quantity" required
                                                class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-2.5 py-1.5 text-white font-mono text-xs outline-none focus:border-brand-teal text-center">
                                        </td>
                                        <!-- Unit Price -->
                                        <td class="py-2 px-2">
                                            <div class="relative">
                                                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 font-mono text-xs">$</span>
                                                <input type="number" step="0.01" min="0" x-model.number="it.unit_price" required
                                                    class="w-full bg-brand-darkBg border border-brand-border rounded-lg pl-6 pr-2.5 py-1.5 text-white font-mono text-xs outline-none focus:border-brand-teal">
                                            </div>
                                        </td>
                                        <!-- Line Total Amount -->
                                        <td class="py-2 px-2 text-right font-mono font-bold text-white text-xs pt-3">
                                            $<span x-text="((Number(it.quantity) || 0) * (Number(it.unit_price) || 0)).toFixed(2)"></span>
                                        </td>
                                        <!-- Delete row -->
                                        <td class="py-2 px-1 text-center pt-2.5">
                                            <button type="button" @click="removeInvoiceItem(idx)" :disabled="invoiceForm.items.length <= 1"
                                                class="text-slate-500 hover:text-rose-400 disabled:opacity-30 disabled:hover:text-slate-500 p-1 transition-colors">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Add Row Button -->
                    <button type="button" @click="addInvoiceItem()"
                        class="px-3 py-1.5 bg-brand-darkBg hover:bg-slate-800 border border-brand-border rounded-lg text-brand-tealLight hover:text-white font-bold text-xs flex items-center gap-1.5 transition-all">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Add Line Item</span>
                    </button>
                </div>

                <!-- Row 4: Calculation Summary & Notes -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-brand-inner/70 p-4 rounded-xl border border-brand-border/80">
                    <!-- Left: Notes & Telegram Notification Checkbox -->
                    <div class="space-y-3">
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Invoice Notes / Bank Details / Warranty Terms</label>
                            <textarea x-model="invoiceForm.notes" rows="3" placeholder="Notes printed at the bottom of the receipt..."
                                class="w-full bg-brand-darkBg border border-brand-border rounded-lg p-2.5 text-white text-xs outline-none focus:border-brand-teal leading-relaxed"></textarea>
                        </div>
                        <div class="flex items-center gap-2 pt-1">
                            <input type="checkbox" id="send_tg_check" x-model="invoiceForm.send_telegram_on_create"
                                class="w-4 h-4 rounded border-brand-border text-brand-teal focus:ring-0 cursor-pointer">
                            <label for="send_tg_check" class="text-slate-300 text-xs font-medium cursor-pointer flex items-center gap-1.5">
                                <i class="fa-brands fa-telegram text-sky-400"></i>
                                <span>Send instant Telegram push notification to my phone on save</span>
                            </label>
                        </div>
                    </div>

                    <!-- Right: Live Calculations -->
                    <div class="space-y-2 bg-brand-darkBg/60 p-3.5 rounded-xl border border-brand-border/60">
                        <div class="flex justify-between items-center text-slate-300">
                            <span>Subtotal:</span>
                            <span class="font-mono font-bold">$<span x-text="computedInvoiceSubtotal.toFixed(2)"></span></span>
                        </div>

                        <!-- Discount -->
                        <div class="flex justify-between items-center text-slate-300">
                            <span>Discount AUD ($):</span>
                            <input type="number" step="0.01" min="0" x-model.number="invoiceForm.discount"
                                class="w-24 bg-brand-darkBg border border-brand-border rounded px-2 py-1 text-right text-white font-mono text-xs outline-none focus:border-brand-teal">
                        </div>

                        <!-- GST Toggle -->
                        <div class="flex justify-between items-center text-slate-300 pt-1 border-t border-brand-border/40">
                            <label class="flex items-center gap-1.5 cursor-pointer">
                                <input type="checkbox" x-model="invoiceForm.tax_included" class="w-3.5 h-3.5 rounded text-brand-teal">
                                <span class="text-[11px]">Include 10% GST (AU Standard)</span>
                            </label>
                            <span class="font-mono text-slate-400 text-xs">GST: $<span x-text="computedInvoiceTax.toFixed(2)"></span></span>
                        </div>

                        <!-- Grand Total -->
                        <div class="flex justify-between items-center pt-2 border-t border-brand-border text-white">
                            <span class="font-heading font-bold text-sm sm:text-base">Grand Total:</span>
                            <div class="text-right">
                                <div class="font-mono font-black text-lg sm:text-xl text-emerald-400">
                                    AUD $<span x-text="computedInvoiceTotal.toFixed(2)"></span>
                                </div>
                                <span class="text-[10px] text-slate-400" x-text="invoiceForm.tax_included ? '(Inclusive of 10% GST)' : '(Plus 10% GST)'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="flex items-center justify-between pt-3 border-t border-brand-border">
                    <button type="button" @click="isInvoiceModalOpen = false"
                        class="px-4 py-2 bg-brand-inner border border-brand-border rounded-lg text-slate-300 hover:text-white font-semibold">
                        Cancel
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="submit" :disabled="isSavingInvoice"
                            class="teal-gradient hover:opacity-95 text-white px-5 py-2.5 rounded-xl font-bold uppercase tracking-wider text-xs shadow-lg transition-all flex items-center gap-2 disabled:opacity-50">
                            <i class="fa-solid fa-floppy-disk" :class="{ 'fa-spin': isSavingInvoice }"></i>
                            <span x-text="isSavingInvoice ? 'Saving to MySQL...' : (invoiceModalMode === 'edit' ? 'Update Invoice' : 'Save Invoice & Generate')"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- INVOICE & RECEIPT PRINT / PREVIEW MODAL -->
    <div id="printable-invoice-modal" x-show="isInvoicePrintModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-brand-darkBg/95 backdrop-blur-md overflow-y-auto">
        <div class="max-w-4xl w-full my-6 space-y-4">
            <!-- Modal Action Bar (Hidden during print) -->
            <div class="no-print bg-brand-cardBg border border-brand-border rounded-2xl p-4 flex flex-wrap items-center justify-between gap-3 shadow-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl teal-gradient flex items-center justify-center text-white text-lg shadow">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div>
                        <div class="font-heading font-bold text-white text-base flex items-center gap-2">
                            <span>Receipt / Invoice Preview</span>
                            <span class="text-xs font-mono text-brand-tealLight" x-text="selectedInvoice ? '#' + selectedInvoice.invoice_number : ''"></span>
                        </div>
                        <span class="text-[11px] text-brand-textMuted">Ready to print, download PDF, or dispatch via WhatsApp / Telegram</span>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <!-- Print Button -->
                    <button type="button" @click="printInvoice()"
                        class="px-3.5 py-2 bg-brand-teal hover:bg-teal-600 text-white rounded-lg font-bold text-xs flex items-center gap-1.5 shadow transition-all cursor-pointer">
                        <i class="fa-solid fa-print"></i>
                        <span>Print</span>
                    </button>

                    <!-- PDF Download Button -->
                    <button type="button" @click="downloadInvoicePdf()"
                        class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-xs flex items-center gap-1.5 shadow transition-all cursor-pointer">
                        <i class="fa-solid fa-file-pdf"></i>
                        <span>PDF</span>
                    </button>

                    <!-- WhatsApp Button -->
                    <button type="button" @click="sendWhatsAppInvoice(selectedInvoice)"
                        class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-xs flex items-center gap-1.5 shadow transition-all cursor-pointer">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                        <span>WhatsApp</span>
                    </button>

                    <!-- Telegram Button -->
                    <button type="button" @click="sendTelegramInvoice(selectedInvoice)" :disabled="isSendingTelegramInvoice"
                        class="px-3.5 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-lg font-bold text-xs flex items-center gap-1.5 shadow transition-all cursor-pointer disabled:opacity-50">
                        <i class="fa-brands fa-telegram text-sm" :class="{ 'fa-spin': isSendingTelegramInvoice }"></i>
                        <span>Telegram</span>
                    </button>

                    <!-- Copy Text Button -->
                    <button type="button" @click="copyInvoiceText(selectedInvoice)"
                        class="px-3 py-2 bg-brand-inner hover:bg-slate-800 text-slate-300 border border-brand-border rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-colors cursor-pointer" title="Copy receipt text to clipboard">
                        <i class="fa-solid fa-copy"></i>
                        <span class="hidden sm:inline">Copy Text</span>
                    </button>

                    <!-- Close Button -->
                    <button type="button" @click="isInvoicePrintModalOpen = false"
                        class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-semibold transition-colors">
                        ✕ Close
                    </button>
                </div>
            </div>

            <!-- ACTUAL PRINTABLE INVOICE DOCUMENT (#printable-invoice) -->
            <div id="printable-invoice" class="bg-white text-slate-800 p-8 sm:p-12 rounded-2xl shadow-2xl max-w-3xl mx-auto font-sans leading-relaxed border border-slate-200">
                <template x-if="selectedInvoice">
                    <div class="space-y-8">
                        <!-- Invoice Header -->
                        <div class="flex flex-col sm:flex-row justify-between items-start gap-6 border-b-2 border-slate-900 pb-6">
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center font-heading font-extrabold text-2xl tracking-tight shadow">
                                        DH
                                    </div>
                                    <div>
                                        <h1 class="font-heading text-2xl font-black text-slate-900 tracking-tight leading-none">DAVID HAIR SYSTEMS</h1>
                                        <span class="text-xs font-semibold text-slate-600 uppercase tracking-wider block mt-1">Premium Men's Hair Systems &amp; Salon</span>
                                    </div>
                                </div>
                                <div class="text-xs text-slate-600 space-y-0.5 pt-2">
                                    <div>Shop 62, 46-58 Buckingham Ave, Springvale VIC 3171</div>
                                    <div>Phone: <strong>+61 433 484 882</strong> &bull; Email: <strong>shc9607@gmail.com</strong></div>
                                    <div>Web: <strong>www.davidhairshop.com</strong> &bull; ABN: <strong>82 612 948 231</strong></div>
                                </div>
                            </div>

                            <!-- Document Title & Status Stamp -->
                            <div class="text-left sm:text-right space-y-2">
                                <div class="font-heading font-black text-2xl uppercase tracking-wider text-slate-900" x-text="selectedInvoice.invoice_type || 'TAX INVOICE'"></div>
                                <div class="inline-block px-3 py-1 rounded-md text-xs font-black uppercase tracking-wider border-2"
                                    :class="{
                                        'border-emerald-600 text-emerald-700 bg-emerald-50': selectedInvoice.status === 'Paid',
                                        'border-rose-600 text-rose-700 bg-rose-50': selectedInvoice.status !== 'Paid' && selectedInvoice.status !== 'Quote',
                                        'border-sky-600 text-sky-700 bg-sky-50': selectedInvoice.status === 'Quote'
                                    }" x-text="selectedInvoice.status === 'Paid' ? 'PAID IN FULL' : selectedInvoice.status">
                                </div>
                            </div>
                        </div>

                        <!-- Meta Info Grid -->
                        <div class="grid grid-cols-2 gap-6 text-xs border-b border-slate-200 pb-6">
                            <!-- Billed To -->
                            <div class="space-y-1">
                                <span class="font-bold text-slate-400 uppercase tracking-wider text-[10px] block">BILLED TO:</span>
                                <div class="text-base font-bold text-slate-900" x-text="selectedInvoice.customer_name"></div>
                                <div x-show="selectedInvoice.customer_phone" class="text-slate-700">Tel: <strong x-text="selectedInvoice.customer_phone"></strong></div>
                                <div x-show="selectedInvoice.customer_email" class="text-slate-700" x-text="selectedInvoice.customer_email"></div>
                                <div x-show="selectedInvoice.customer_address" class="text-slate-700" x-text="selectedInvoice.customer_address"></div>
                            </div>

                            <!-- Invoice Details -->
                            <div class="space-y-1 text-right">
                                <span class="font-bold text-slate-400 uppercase tracking-wider text-[10px] block">INVOICE DETAILS:</span>
                                <div>Invoice #: <strong class="font-mono text-slate-900" x-text="selectedInvoice.invoice_number"></strong></div>
                                <div>Date: <strong x-text="selectedInvoice.invoice_date"></strong></div>
                                <div x-show="selectedInvoice.due_date">Due Date: <strong x-text="selectedInvoice.due_date"></strong></div>
                                <div>Payment Method: <strong x-text="selectedInvoice.payment_method || 'Card / EFTPOS'"></strong></div>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div>
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="border-b-2 border-slate-800 text-slate-900 font-bold uppercase text-[11px]">
                                        <th class="py-2.5 px-2 w-12">#</th>
                                        <th class="py-2.5 px-2">Item &amp; Description</th>
                                        <th class="py-2.5 px-2 text-center w-20">Qty</th>
                                        <th class="py-2.5 px-2 text-right w-28">Unit Price</th>
                                        <th class="py-2.5 px-2 text-right w-28">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    <template x-for="(it, i) in (selectedInvoice.items || [])" :key="i">
                                        <tr>
                                            <td class="py-3 px-2 font-mono text-slate-400" x-text="i + 1"></td>
                                            <td class="py-3 px-2">
                                                <div class="font-bold text-slate-900" x-text="it.item_name"></div>
                                                <div x-show="it.description" class="text-[11px] text-slate-500 italic mt-0.5" x-text="it.description"></div>
                                            </td>
                                            <td class="py-3 px-2 text-center font-mono font-medium" x-text="it.quantity"></td>
                                            <td class="py-3 px-2 text-right font-mono" x-text="'$' + Number(it.unit_price).toFixed(2)"></td>
                                            <td class="py-3 px-2 text-right font-mono font-bold text-slate-900" x-text="'$' + Number(it.amount).toFixed(2)"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Calculations Box -->
                        <div class="flex justify-end pt-2">
                            <div class="w-72 space-y-2 text-xs border-t-2 border-slate-900 pt-4">
                                <div class="flex justify-between text-slate-600">
                                    <span>Subtotal:</span>
                                    <span class="font-mono font-bold text-slate-900">$<span x-text="Number(selectedInvoice.subtotal || 0).toFixed(2)"></span></span>
                                </div>
                                <template x-if="Number(selectedInvoice.discount || 0) > 0">
                                    <div class="flex justify-between text-rose-600 font-medium">
                                        <span>Discount:</span>
                                        <span class="font-mono">-$<span x-text="Number(selectedInvoice.discount).toFixed(2)"></span></span>
                                    </div>
                                </template>
                                <template x-if="Number(selectedInvoice.tax_amount || 0) > 0">
                                    <div class="flex justify-between text-slate-600">
                                        <span>Includes 10% GST:</span>
                                        <span class="font-mono">$<span x-text="Number(selectedInvoice.tax_amount).toFixed(2)"></span></span>
                                    </div>
                                </template>
                                <div class="flex justify-between text-slate-900 font-bold text-base border-t-2 border-slate-900 pt-2.5">
                                    <span>TOTAL AUD:</span>
                                    <span class="font-mono text-xl text-slate-900">$<span x-text="Number(selectedInvoice.total_amount || 0).toFixed(2)"></span></span>
                                </div>
                            </div>
                        </div>

                        <!-- Notes & Footer Terms -->
                        <div class="border-t border-slate-200 pt-6 space-y-3 text-[11px] text-slate-500 leading-relaxed">
                            <div x-show="selectedInvoice.notes" class="p-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-700">
                                <strong class="block text-slate-900 mb-1">Notes &amp; Instructions:</strong>
                                <span x-text="selectedInvoice.notes"></span>
                            </div>

                            <div class="space-y-1">
                                <div><strong>Warranty &amp; Complimentary Service:</strong> All custom hair systems fitted at David Hair Systems include 1 complimentary fitting check &amp; styling touch-up within 14 days of appointment.</div>
                                <div><strong>Direct Bank Deposit (if required):</strong> Bank: ANZ &bull; BSB: 013-006 &bull; Account: 1234-5678 &bull; Name: David Hair Systems</div>
                            </div>

                            <div class="text-center pt-4 text-slate-400 border-t border-slate-100">
                                Thank you for choosing David Hair Systems Springvale &bull; www.davidhairshop.com
                            </div>
                        </div>
                    </div>
                </template>
            </div>
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
                selectedVisibility: 'All', // 'All', 'visible', 'hidden'
                togglingItemIds: [],

                // Sorting state (name, category, size, price, stockQty, is_visible)
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
                form: { id: null, sku: '', name: '', category: '', type: '', base: '', size: '', density: '', color: '', price: 0, stockQty: 0, image_url: '', description: '', badge: '', is_visible: 1 },
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
                bookingViewMode: 'week', // 'week' or 'list'
                weekStartDate: '', // Starting date for 7-day display
                draggedBooking: null,
                dragOverCell: '',
                get currentWeekMonday() { return this.weekStartDate; },
                set currentWeekMonday(val) { this.weekStartDate = val; },
                bookingSearchQuery: '',
                bookingStatusFilter: 'All',
                bookingDateFilter: 'All',
                isLoadingBookings: false,
                bookingDetailModalOpen: false,
                selectedBooking: null,
                newBookingModalOpen: false,
                newBookingForm: {
                    customer_name: '',
                    phone: '',
                    email: '',
                    service_type: 'Free Consultation & Hairline Assessment ($0 Free)',
                    booking_date: '',
                    booking_time: '11:00 AM',
                    notes: ''
                },

                // Salon Holiday & Closure Rules state
                isHolidayModalOpen: false,
                isLoadingClosureRules: false,
                isSavingClosureRules: false,
                closureRules: { regular_off_days: [0], closure_periods: [] },
                newClosurePeriod: { start_date: '', end_date: '', reason: '' },

                // Booking Notification Settings state
                isNotificationModalOpen: false,
                isLoadingNotificationConfig: false,
                isSavingNotificationConfig: false,
                isTestingTelegram: false,
                isTestingEmail: false,
                notificationConfig: {
                    telegram_bot_token: '',
                    telegram_chat_id: '',
                    telegram_enabled: '1',
                    notification_email: 'shc9607@gmail.com',
                    email_enabled: '1'
                },

                async init() {
                    await this.checkAuth();
                    this.initWeek();
                    await this.loadClosureRules();
                },

                get totalQty() {
                    return this.items.reduce((sum, item) => sum + parseInt(item.stockQty || 0), 0);
                },

                get visibleItemsCount() {
                    return this.items.filter(i => i.is_visible === undefined || i.is_visible === null || i.is_visible == 1 || i.is_visible === true || i.is_visible === '1').length;
                },

                get hiddenItemsCount() {
                    return this.items.filter(i => i.is_visible !== undefined && i.is_visible !== null && (i.is_visible == 0 || i.is_visible === false || i.is_visible === '0')).length;
                },

                get pendingBookingsCount() {
                    return this.bookings.filter(b => b.status === 'Pending').length;
                },

                get confirmedBookingsCount() {
                    return this.bookings.filter(b => b.status === 'Confirmed').length;
                },

                get todayBookingsCount() {
                    const today = this.formatDate(new Date());
                    return this.bookings.filter(b => b.booking_date === today).length;
                },

                get weekDays() {
                    if (!this.weekStartDate) this.initWeek();
                    const parts = (this.weekStartDate || this.formatDate(new Date())).split('-');
                    const base = new Date(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10));
                    const days = [];
                    const namesKo = ['일', '월', '화', '수', '목', '금', '토'];
                    const namesEn = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                    const today = this.formatDate(new Date());

                    for (let i = 0; i < 7; i++) {
                        const d = new Date(base);
                        d.setDate(base.getDate() + i);
                        const dateStr = this.formatDate(d);
                        const dayOfWeek = d.getDay();
                        const dayBookings = this.bookings.filter(b => b.booking_date === dateStr);
                        const closure = this.checkDateClosed(dateStr);
                        days.push({
                            dateStr: dateStr,
                            nameKo: namesKo[dayOfWeek],
                            nameEn: namesEn[dayOfWeek],
                            monthDay: String(d.getMonth() + 1).padStart(2, '0') + '.' + String(d.getDate()).padStart(2, '0'),
                            isToday: dateStr === today,
                            isClosed: closure.isClosed,
                            closureReason: closure.reason,
                            bookingsCount: dayBookings.length,
                            confirmedCount: dayBookings.filter(b => b.status === 'Confirmed').length,
                            pendingCount: dayBookings.filter(b => b.status === 'Pending').length
                        });
                    }
                    return days;
                },

                get weekRangeLabel() {
                    const days = this.weekDays;
                    if (!days || days.length === 0) return '';
                    return `${days[0].dateStr} (${days[0].nameEn}) ~ ${days[6].dateStr} (${days[6].nameEn})`;
                },

                get weekTotalBookingsCount() {
                    const days = this.weekDays;
                    const dates = new Set(days.map(d => d.dateStr));
                    return this.bookings.filter(b => dates.has(b.booking_date)).length;
                },

                get weekConfirmedBookingsCount() {
                    const days = this.weekDays;
                    const dates = new Set(days.map(d => d.dateStr));
                    return this.bookings.filter(b => dates.has(b.booking_date) && b.status === 'Confirmed').length;
                },

                get weekPendingBookingsCount() {
                    const days = this.weekDays;
                    const dates = new Set(days.map(d => d.dateStr));
                    return this.bookings.filter(b => dates.has(b.booking_date) && b.status === 'Pending').length;
                },

                timeSlots: [
                    '08:00', '09:00', '10:00', '11:00', '12:00', 
                    '13:00', '14:00', '15:00', '16:00', '17:00', 
                    '18:00', '19:00', '20:00'
                ],

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
                        const isVis = (item.is_visible === undefined || item.is_visible === null || item.is_visible == 1 || item.is_visible === true || item.is_visible === '1');
                        const matchesVisibility = this.selectedVisibility === 'All' || 
                            (this.selectedVisibility === 'visible' && isVis) || 
                            (this.selectedVisibility === 'hidden' && !isVis);
                        const query = this.searchQuery.toLowerCase();
                        const matchesQuery = !query || 
                            item.name.toLowerCase().includes(query) || 
                            item.sku.toLowerCase().includes(query) || 
                            item.base.toLowerCase().includes(query);
                        return matchesCategory && matchesVisibility && matchesQuery;
                    });

                    if (this.sortColumn) {
                        const col = this.sortColumn;
                        const dir = this.sortDirection === 'asc' ? 1 : -1;

                        result = [...result].sort((a, b) => {
                            let valA = a[col];
                            let valB = b[col];

                            // 노출 상태 정렬: is_visible
                            if (col === 'is_visible') {
                                const visA = (a.is_visible === undefined || a.is_visible === null || a.is_visible == 1 || a.is_visible === true || a.is_visible === '1') ? 1 : 0;
                                const visB = (b.is_visible === undefined || b.is_visible === null || b.is_visible == 1 || b.is_visible === true || b.is_visible === '1') ? 1 : 0;
                                return (visA - visB) * dir;
                            }

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
                        this.sortDirection = (col === 'price' || col === 'stockQty' || col === 'is_visible') ? 'desc' : 'asc';
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
                            await this.loadMembers();
                            await this.loadSections();
                            await this.loadInvoices();
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
                            await this.loadMembers();
                            await this.loadSections();
                            await this.loadInvoices();
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
                        const isVis = (item.is_visible === undefined || item.is_visible === null || item.is_visible == 1 || item.is_visible === true || item.is_visible === '1') ? 1 : 0;
                        this.form = { ...item, image_url: item.image, description: item.description || '', badge: item.badge || '', is_visible: isVis };
                        this.imagePreview = item.image;
                    } else {
                        this.form = {
                            id: null,
                            sku: 'SKU-DH-' + (100 + this.items.length + 1),
                            name: '', category: "Men's Toupee", type: "Skin Systems",
                            base: "0.03mm Skin", size: '8x10"', density: '90%',
                            color: '#1B Off Black', price: 450, stockQty: 10, image_url: '',
                            description: '', badge: '', is_visible: 1
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
                    formData.append('is_visible', (this.form.is_visible === undefined || this.form.is_visible === null || this.form.is_visible == 1 || this.form.is_visible === true || this.form.is_visible === '1') ? 1 : 0);
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
                    const isVis = (item.is_visible === undefined || item.is_visible === null || item.is_visible == 1 || item.is_visible === true || item.is_visible === '1');
                    this.inlineForm = { 
                        ...item, 
                        price: parseFloat(item.price) || 0,
                        stockQty: parseInt(item.stockQty) || 0,
                        description: item.description || '',
                        badge: item.badge || '',
                        image_url: item.image || '',
                        is_visible: isVis
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
                        formData.append('is_visible', this.inlineForm.is_visible ? 1 : 0);
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
                    formData.append('is_visible', (item.is_visible === undefined || item.is_visible === null || item.is_visible == 1 || item.is_visible === true || item.is_visible === '1') ? 1 : 0);
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
                        formData.append('is_visible', (item.is_visible === undefined || item.is_visible === null || item.is_visible == 1 || item.is_visible === true || item.is_visible === '1') ? 1 : 0);
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

                async toggleItemVisibility(item) {
                    if (this.togglingItemIds.includes(item.id)) return;
                    this.togglingItemIds.push(item.id);

                    const currentVis = (item.is_visible === undefined || item.is_visible === null || item.is_visible == 1 || item.is_visible === true || item.is_visible === '1');
                    const nextVis = currentVis ? 0 : 1;
                    const previousValue = item.is_visible;

                    // Optimistic update
                    item.is_visible = nextVis;

                    try {
                        const formData = new FormData();
                        formData.append('action', 'toggle_visibility');
                        formData.append('id', item.id);
                        formData.append('is_visible', nextVis);

                        const res = await fetch('/admin_stock_api.php', { method: 'POST', body: formData });
                        const data = await res.json();

                        if (data.status === 'success') {
                            item.is_visible = data.is_visible;
                            const statusText = item.is_visible ? '노출 (Visible)' : '숨김 (Hidden)';
                            const icon = item.is_visible ? '👁️' : '🙈';
                            this.showToast(`${icon} [${item.sku}] "${item.name}" 제품이 메인페이지에서 "${statusText}"(으)로 변경되었습니다.`);
                        } else {
                            item.is_visible = previousValue; // Revert
                            alert('노출 설정 변경 실패: ' + (data.message || 'Error'));
                        }
                    } catch (err) {
                        item.is_visible = previousValue; // Revert
                        alert('네트워크 오류가 발생했습니다.');
                    } finally {
                        this.togglingItemIds = this.togglingItemIds.filter(id => id !== item.id);
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
                        await this.loadClosureRules();
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
                },

                initWeek() {
                    this.goToMondayWeek();
                },

                formatDate(d) {
                    if (!d) return '';
                    const dateObj = (d instanceof Date) ? d : new Date(d);
                    const year = dateObj.getFullYear();
                    const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                    const day = String(dateObj.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                },

                getMonday(d) {
                    const date = new Date(d);
                    const day = date.getDay(); // 0 is Sun, 1 is Mon...
                    const diff = date.getDate() - day + (day === 0 ? -6 : 1);
                    return new Date(date.setDate(diff));
                },

                setStartDate(val) {
                    if (!val) return;
                    this.weekStartDate = val;
                },

                goToTodayWeek() {
                    this.weekStartDate = this.formatDate(new Date());
                },

                goToMondayWeek() {
                    const now = new Date();
                    this.weekStartDate = this.formatDate(this.getMonday(now));
                },

                prevWeek() {
                    if (!this.weekStartDate) this.initWeek();
                    const parts = this.weekStartDate.split('-');
                    const current = new Date(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10));
                    current.setDate(current.getDate() - 7);
                    this.weekStartDate = this.formatDate(current);
                },

                nextWeek() {
                    if (!this.weekStartDate) this.initWeek();
                    const parts = this.weekStartDate.split('-');
                    const current = new Date(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10));
                    current.setDate(current.getDate() + 7);
                    this.weekStartDate = this.formatDate(current);
                },

                jumpToDate(val) {
                    this.setStartDate(val);
                },

                handleDragStart(e, b) {
                    this.draggedBooking = b;
                    if (e.dataTransfer) {
                        e.dataTransfer.effectAllowed = 'move';
                        try {
                            e.dataTransfer.setData('text/plain', String(b.id));
                        } catch (err) {}
                    }
                },

                handleDragEnd(e) {
                    this.draggedBooking = null;
                    this.dragOverCell = '';
                },

                handleDragOver(e, dateStr, slot) {
                    e.preventDefault();
                    if (e.dataTransfer) {
                        e.dataTransfer.dropEffect = 'move';
                    }
                    const key = dateStr + '_' + slot;
                    if (this.dragOverCell !== key) {
                        this.dragOverCell = key;
                    }
                },

                handleDragLeave(e, dateStr, slot) {
                    if (e.currentTarget && e.relatedTarget && e.currentTarget.contains(e.relatedTarget)) {
                        return;
                    }
                    const key = dateStr + '_' + slot;
                    if (this.dragOverCell === key) {
                        this.dragOverCell = '';
                    }
                },

                async handleDrop(e, targetDate, targetSlot) {
                    e.preventDefault();
                    this.dragOverCell = '';
                    if (!this.draggedBooking) return;

                    const b = this.draggedBooking;
                    const oldDate = b.booking_date;
                    const oldTime = b.booking_time;
                    const newTime = this.convertSlotToAmPm(targetSlot);

                    // If dropped on the exact same slot, reset
                    if (oldDate === targetDate && oldTime === newTime) {
                        this.draggedBooking = null;
                        return;
                    }

                    // Check if target date is closed
                    const closureCheck = this.checkDateClosed(targetDate);
                    if (closureCheck.isClosed) {
                        alert(`Cannot move appointment: Selected date (${targetDate}) is closed (${closureCheck.reason}).`);
                        this.draggedBooking = null;
                        return;
                    }

                    const confirmMsg = `[Reschedule Appointment]\n\nCustomer: ${b.customer_name} (#FIT-${b.id})\n\nFrom: ${oldDate} ${oldTime}\nTo: ${targetDate} ${newTime}\n\nDo you want to move this appointment to the new date and time?`;
                    if (!confirm(confirmMsg)) {
                        this.draggedBooking = null;
                        return;
                    }

                    // Optimistic UI update
                    b.booking_date = targetDate;
                    b.booking_time = newTime;
                    const bookingId = b.id;
                    this.draggedBooking = null;

                    try {
                        const formData = new FormData();
                        formData.append('id', bookingId);
                        formData.append('booking_date', targetDate);
                        formData.append('booking_time', newTime);

                        const res = await fetch('/api_booking.php?action=reschedule_booking', {
                            method: 'POST',
                            body: formData
                        });
                        const data = await res.json();
                        if (data.status === 'success') {
                            this.showToast(`✅ Booking #${bookingId} rescheduled to ${targetDate} ${newTime}`);
                        } else {
                            // Revert on error
                            b.booking_date = oldDate;
                            b.booking_time = oldTime;
                            alert(data.message || 'Failed to reschedule appointment');
                        }
                    } catch (err) {
                        // Revert on network error
                        b.booking_date = oldDate;
                        b.booking_time = oldTime;
                        alert('Network error rescheduling appointment: ' + err.message);
                    }
                },

                formatSlotLabel(slot) {
                    const h = parseInt(slot.split(':')[0], 10);
                    const ampm = h >= 12 ? 'PM' : 'AM';
                    const displayH = h > 12 ? h - 12 : (h === 0 ? 12 : h);
                    return `${displayH}:00 ${ampm}`;
                },

                parseBookingHour(timeStr) {
                    if (!timeStr) return null;
                    const match = timeStr.match(/(\d{1,2}):(\d{2})\s*(AM|PM)?/i);
                    if (!match) return null;
                    let h = parseInt(match[1], 10);
                    const ampm = match[3] ? match[3].toUpperCase() : '';
                    if (ampm === 'PM' && h < 12) h += 12;
                    if (ampm === 'AM' && h === 12) h = 0;
                    return String(h).padStart(2, '0') + ':00';
                },

                getBookingsForSlot(dateStr, hourSlot) {
                    let list = this.bookings.filter(b => {
                        if (b.booking_date !== dateStr) return false;
                        const bHour = this.parseBookingHour(b.booking_time);
                        return bHour === hourSlot;
                    });

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
                    return list;
                },

                openBookingDetail(b) {
                    if (this.draggedBooking) return;
                    this.selectedBooking = { ...b };
                    this.bookingDetailModalOpen = true;
                },

                closeBookingDetail() {
                    this.bookingDetailModalOpen = false;
                    this.selectedBooking = null;
                },

                async saveSelectedBookingNotes() {
                    if (!this.selectedBooking) return;
                    try {
                        const formData = new FormData();
                        formData.append('id', this.selectedBooking.id);
                        formData.append('status', this.selectedBooking.status);
                        formData.append('notes', this.selectedBooking.notes || '');
                        const res = await fetch('/api_booking.php?action=update_status', {
                            method: 'POST',
                            body: formData
                        });
                        const data = await res.json();
                        if (data.status === 'success') {
                            const b = this.bookings.find(x => x.id == this.selectedBooking.id);
                            if (b) {
                                b.status = this.selectedBooking.status;
                                b.notes = this.selectedBooking.notes;
                            }
                            this.showToast(`Booking #${this.selectedBooking.id} notes saved!`);
                        } else {
                            alert(data.message || 'Update failed');
                        }
                    } catch (e) {
                        alert('Server error saving booking notes.');
                    }
                },

                openNewBookingModal(dateStr = '', timeSlot = '') {
                    this.newBookingForm = {
                        customer_name: '',
                        phone: '',
                        email: '',
                        service_type: 'Free Consultation & Hairline Assessment ($0 Free)',
                        booking_date: dateStr || this.formatDate(new Date()),
                        booking_time: timeSlot ? this.convertSlotToAmPm(timeSlot) : '11:00 AM',
                        notes: ''
                    };
                    this.newBookingModalOpen = true;
                },

                convertSlotToAmPm(slot) {
                    const h = parseInt(slot.split(':')[0], 10);
                    const ampm = h >= 12 ? 'PM' : 'AM';
                    const displayH = h > 12 ? h - 12 : (h === 0 ? 12 : h);
                    return `${String(displayH).padStart(2, '0')}:00 ${ampm}`;
                },

                async submitNewBooking() {
                    if (!this.newBookingForm.customer_name || !this.newBookingForm.phone || !this.newBookingForm.booking_date || !this.newBookingForm.booking_time) {
                        alert('Please enter customer name, phone number, booking date, and time.');
                        return;
                    }
                    try {
                        const formData = new FormData();
                        formData.append('customer_name', this.newBookingForm.customer_name);
                        formData.append('phone', this.newBookingForm.phone);
                        formData.append('email', this.newBookingForm.email || '');
                        formData.append('service_type', this.newBookingForm.service_type);
                        formData.append('booking_date', this.newBookingForm.booking_date);
                        formData.append('booking_time', this.newBookingForm.booking_time);
                        formData.append('notes', this.newBookingForm.notes || '');

                        const res = await fetch('/api_booking.php?action=create_booking', {
                            method: 'POST',
                            body: formData
                        });
                        const data = await res.json();
                        if (data.status === 'success') {
                            this.showToast(`✅ New fitting appointment (#FIT-${data.booking_id}) registered!`);
                            this.newBookingModalOpen = false;
                            await this.loadBookings();
                        } else {
                            alert(data.message || 'Failed to register appointment');
                        }
                    } catch (e) {
                        alert('Network error registering appointment');
                    }
                },

                // Closure & Holiday Rules Management Methods
                async loadClosureRules() {
                    this.isLoadingClosureRules = true;
                    try {
                        const res = await fetch('/api_booking.php?action=get_closure_rules');
                        const data = await res.json();
                        if (data.status === 'success') {
                            this.closureRules = {
                                regular_off_days: data.regular_off_days || [0],
                                closure_periods: data.closure_periods || []
                            };
                        }
                    } catch (e) {
                        console.error('Failed to load closure rules:', e);
                    } finally {
                        this.isLoadingClosureRules = false;
                    }
                },

                openHolidayModal() {
                    this.isHolidayModalOpen = true;
                    this.loadClosureRules();
                },

                async openNotificationModal() {
                    this.isNotificationModalOpen = true;
                    this.isLoadingNotificationConfig = true;
                    try {
                        const res = await fetch('/api_booking.php?action=get_notification_config');
                        const data = await res.json();
                        if (data.status === 'success' && data.data) {
                            this.notificationConfig = {
                                telegram_bot_token: data.data.telegram_bot_token_masked || data.data.telegram_bot_token || '',
                                telegram_chat_id: data.data.telegram_chat_id || '',
                                telegram_enabled: data.data.telegram_enabled ?? '1',
                                notification_email: data.data.notification_email || 'shc9607@gmail.com',
                                email_enabled: data.data.email_enabled ?? '1'
                            };
                        }
                    } catch (e) {
                        console.error('Failed to load notification config:', e);
                    } finally {
                        this.isLoadingNotificationConfig = false;
                    }
                },

                async saveNotificationConfig() {
                    this.isSavingNotificationConfig = true;
                    try {
                        const fd = new FormData();
                        fd.append('telegram_bot_token', this.notificationConfig.telegram_bot_token);
                        fd.append('telegram_chat_id', this.notificationConfig.telegram_chat_id);
                        fd.append('telegram_enabled', this.notificationConfig.telegram_enabled);
                        fd.append('notification_email', this.notificationConfig.notification_email);
                        fd.append('email_enabled', this.notificationConfig.email_enabled);

                        const res = await fetch('/api_booking.php?action=save_notification_config', {
                            method: 'POST',
                            body: fd
                        });
                        const data = await res.json();
                        if (data.status === 'success') {
                            this.showToast('알림 설정이 성공적으로 저장되었습니다!');
                            this.isNotificationModalOpen = false;
                        } else {
                            alert(data.message || '저장 실패');
                        }
                    } catch (e) {
                        alert('서버 저장 에러');
                    } finally {
                        this.isSavingNotificationConfig = false;
                    }
                },

                async testTelegramAlert() {
                    if (!this.notificationConfig.telegram_bot_token || !this.notificationConfig.telegram_chat_id) {
                        alert('텔레그램 봇 토큰(Bot Token)과 챗 ID(Chat ID)를 먼저 입력해주세요.');
                        return;
                    }
                    this.isTestingTelegram = true;
                    try {
                        const fd = new FormData();
                        fd.append('type', 'telegram');
                        fd.append('telegram_bot_token', this.notificationConfig.telegram_bot_token);
                        fd.append('telegram_chat_id', this.notificationConfig.telegram_chat_id);

                        const res = await fetch('/api_booking.php?action=test_notification', {
                            method: 'POST',
                            body: fd
                        });
                        const data = await res.json();
                        if (data.status === 'success') {
                            alert('✅ 텔레그램 테스트 알림이 성공적으로 전송되었습니다! 텔레그램 앱을 확인해보세요.');
                        } else {
                            alert('❌ 전송 실패: ' + (data.message || '오류 발생'));
                        }
                    } catch (e) {
                        alert('테스트 발송 서버 오류');
                    } finally {
                        this.isTestingTelegram = false;
                    }
                },

                async testEmailAlert() {
                    if (!this.notificationConfig.notification_email) {
                        alert('수신할 이메일 주소를 입력해주세요.');
                        return;
                    }
                    this.isTestingEmail = true;
                    try {
                        const fd = new FormData();
                        fd.append('type', 'email');
                        fd.append('notification_email', this.notificationConfig.notification_email);

                        const res = await fetch('/api_booking.php?action=test_notification', {
                            method: 'POST',
                            body: fd
                        });
                        const data = await res.json();
                        if (data.status === 'success') {
                            alert('✅ 이메일 테스트 발송 완료: ' + this.notificationConfig.notification_email);
                        } else {
                            alert('⚠️ 알림: ' + (data.message || '이메일 발송에 실패했습니다.'));
                        }
                    } catch (e) {
                        alert('테스트 발송 서버 오류');
                    } finally {
                        this.isTestingEmail = false;
                    }
                },

                isDayRegularOff(dayNum) {
                    return (this.closureRules.regular_off_days || []).includes(dayNum);
                },

                toggleRegularOffDay(dayNum) {
                    let list = [...(this.closureRules.regular_off_days || [])];
                    const idx = list.indexOf(dayNum);
                    if (idx > -1) {
                        list.splice(idx, 1);
                    } else {
                        list.push(dayNum);
                    }
                    list.sort((a, b) => a - b);
                    this.closureRules.regular_off_days = list;
                },

                async saveRegularOffDays() {
                    this.isSavingClosureRules = true;
                    try {
                        const formData = new FormData();
                        formData.append('days', JSON.stringify(this.closureRules.regular_off_days || []));
                        const res = await fetch('/api_booking.php?action=save_regular_off_days', {
                            method: 'POST',
                            body: formData
                        });
                        const data = await res.json();
                        if (data.status === 'success') {
                            this.closureRules.regular_off_days = data.regular_off_days || [];
                            this.showToast('✅ Regular off-days (정기휴일) updated successfully.');
                        } else {
                            alert(data.message || 'Failed to save regular off-days.');
                        }
                    } catch (e) {
                        alert('Server error saving regular off-days.');
                    } finally {
                        this.isSavingClosureRules = false;
                    }
                },

                async addClosurePeriod() {
                    if (!this.newClosurePeriod.start_date || !this.newClosurePeriod.end_date) {
                        alert('Please select both Start Date and End Date.');
                        return;
                    }
                    this.isSavingClosureRules = true;
                    try {
                        const formData = new FormData();
                        formData.append('start_date', this.newClosurePeriod.start_date);
                        formData.append('end_date', this.newClosurePeriod.end_date);
                        formData.append('reason', this.newClosurePeriod.reason || 'Salon Holiday');

                        const res = await fetch('/api_booking.php?action=add_closure_period', {
                            method: 'POST',
                            body: formData
                        });
                        const data = await res.json();
                        if (data.status === 'success') {
                            this.closureRules.closure_periods = data.closure_periods || [];
                            this.newClosurePeriod = { start_date: '', end_date: '', reason: '' };
                            this.showToast('✅ Holiday closure period (휴무 기간) registered.');
                        } else {
                            alert(data.message || 'Failed to add closure period.');
                        }
                    } catch (e) {
                        alert('Server error adding closure period.');
                    } finally {
                        this.isSavingClosureRules = false;
                    }
                },

                async deleteClosurePeriod(period) {
                    if (!confirm(`Delete holiday closure period "${period.reason || 'Holiday'}" (${period.start_date} ~ ${period.end_date})?`)) return;
                    try {
                        const res = await fetch(`/api_booking.php?action=delete_closure_period&id=${period.id}`);
                        const data = await res.json();
                        if (data.status === 'success') {
                            this.closureRules.closure_periods = data.closure_periods || [];
                            this.showToast('✅ Closure period removed.');
                        } else {
                            alert(data.message || 'Failed to delete closure period.');
                        }
                    } catch (e) {
                        alert('Error deleting closure period.');
                    }
                },

                checkDateClosed(dateStr) {
                    if (!dateStr) return { isClosed: false, reason: '' };
                    const parts = dateStr.split('-');
                    if (parts.length !== 3) return { isClosed: false, reason: '' };
                    const d = new Date(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10));
                    const dayOfWeek = d.getDay();
                    const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

                    if ((this.closureRules.regular_off_days || []).includes(dayOfWeek)) {
                        return { isClosed: true, reason: `Regular Off-Day (${dayNames[dayOfWeek]})` };
                    }

                    const periods = this.closureRules.closure_periods || [];
                    for (const p of periods) {
                        if (dateStr >= p.start_date && dateStr <= p.end_date) {
                            return { isClosed: true, reason: p.reason || 'Salon Holiday' };
                        }
                    }

                    return { isClosed: false, reason: '' };
                },

                // ==========================================
                // SECTION & MENU MANAGER METHODS
                // ==========================================
                sections: [],
                activeSectionFilter: 'All',
                isSavingSections: false,
                draggedSec: null,
                dragOverId: null,

                onDragStart(evt, sec) {
                    this.draggedSec = sec;
                    evt.dataTransfer.effectAllowed = 'move';
                    evt.dataTransfer.setData('text/plain', sec.id);
                },

                onDragOver(evt, sec) {
                    evt.preventDefault();
                    evt.dataTransfer.dropEffect = 'move';
                    if (this.draggedSec && this.draggedSec.id !== sec.id) {
                        this.dragOverId = sec.id;
                    }
                },

                onDragEnter(evt, sec) {
                    if (this.draggedSec && this.draggedSec.id !== sec.id) {
                        this.dragOverId = sec.id;
                    }
                },

                onDragLeave(evt, sec) {
                    if (this.dragOverId === sec.id) {
                        this.dragOverId = null;
                    }
                },

                onDrop(evt, targetSec) {
                    evt.preventDefault();
                    if (!this.draggedSec || this.draggedSec.id === targetSec.id) {
                        this.draggedSec = null;
                        this.dragOverId = null;
                        return;
                    }

                    this.sections.sort((a, b) => (Number(a.sort_order) || 0) - (Number(b.sort_order) || 0));

                    const fromIdx = this.sections.findIndex(s => s.id === this.draggedSec.id);
                    const toIdx = this.sections.findIndex(s => s.id === targetSec.id);

                    if (fromIdx !== -1 && toIdx !== -1) {
                        const [moved] = this.sections.splice(fromIdx, 1);
                        this.sections.splice(toIdx, 0, moved);

                        this.normalizeAndSaveSectionOrders();
                        this.showToast(`"${moved.name}" moved to position #${toIdx + 1}`);
                    }

                    this.draggedSec = null;
                    this.dragOverId = null;
                },

                onDragEnd(evt) {
                    this.draggedSec = null;
                    this.dragOverId = null;
                },

                get filteredSectionsList() {
                    if (this.activeSectionFilter === 'All') return this.sections;
                    return this.sections.filter(s => s.category === this.activeSectionFilter);
                },

                get visibleSectionsCount() {
                    return this.sections.filter(s => s.is_visible == 1).length;
                },

                get hiddenSectionsCount() {
                    return this.sections.filter(s => s.is_visible == 0).length;
                },

                get inMenuSectionsCount() {
                    return this.sections.filter(s => s.in_menu == 1).length;
                },

                async loadSections() {
                    try {
                        const res = await fetch('api_sections.php?action=get_public_sections&t=' + Date.now());
                        const data = await res.json();
                        if (data.status === 'success' && Array.isArray(data.sections)) {
                            this.sections = data.sections.sort((a, b) => (Number(a.sort_order) || 0) - (Number(b.sort_order) || 0));
                        }
                    } catch (e) {
                        console.error('Failed to load sections:', e);
                    }
                },

                moveSection(sec, direction) {
                    this.sections.sort((a, b) => (Number(a.sort_order) || 0) - (Number(b.sort_order) || 0));
                    const currIdx = this.sections.findIndex(s => s.id === sec.id);
                    if (currIdx === -1) return;
                    const targetIdx = currIdx + direction;
                    if (targetIdx < 0 || targetIdx >= this.sections.length) return;

                    const temp = this.sections[currIdx];
                    this.sections[currIdx] = this.sections[targetIdx];
                    this.sections[targetIdx] = temp;

                    this.normalizeAndSaveSectionOrders();
                },

                isFirstSection(sec) {
                    if (!this.sections.length) return true;
                    return this.sections[0].id === sec.id;
                },

                isLastSection(sec) {
                    if (!this.sections.length) return true;
                    return this.sections[this.sections.length - 1].id === sec.id;
                },

                normalizeAndSaveSectionOrders() {
                    this.sections.sort((a, b) => (Number(a.sort_order) || 0) - (Number(b.sort_order) || 0));
                    this.sections.forEach((s, idx) => {
                        s.sort_order = idx + 1;
                    });
                    this.saveAllSections();
                },

                async toggleSectionVisibility(sec) {
                    const nextVal = sec.is_visible ? 0 : 1;
                    sec.is_visible = nextVal;
                    try {
                        const formData = new FormData();
                        formData.append('id', sec.id);
                        formData.append('section_key', sec.section_key);
                        formData.append('is_visible', nextVal);

                        const res = await fetch('api_sections.php?action=toggle_visibility', {
                            method: 'POST',
                            body: formData
                        });
                        const data = await res.json();
                        if (data.status === 'success') {
                            this.showToast(`"${sec.name}" is now ${nextVal ? 'Visible' : 'Hidden'} on homepage.`);
                        }
                    } catch (e) {
                        this.showToast('Error saving visibility: ' + e.message);
                    }
                },

                async toggleSectionMenu(sec) {
                    const nextVal = sec.in_menu ? 0 : 1;
                    sec.in_menu = nextVal;
                    try {
                        const formData = new FormData();
                        formData.append('id', sec.id);
                        formData.append('section_key', sec.section_key);
                        formData.append('in_menu', nextVal);

                        const res = await fetch('api_sections.php?action=toggle_menu', {
                            method: 'POST',
                            body: formData
                        });
                        const data = await res.json();
                        if (data.status === 'success') {
                            this.showToast(`"${sec.menu_title}" ${nextVal ? 'added to' : 'removed from'} navigation menu.`);
                        }
                    } catch (e) {
                        this.showToast('Error saving menu setting: ' + e.message);
                    }
                },

                async saveAllSections() {
                    this.isSavingSections = true;
                    try {
                        const res = await fetch('api_sections.php?action=bulk_update', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ sections: this.sections })
                        });
                        const data = await res.json();
                        if (data.status === 'success') {
                            this.showToast('All section & menu configurations saved to database!');
                        } else {
                            this.showToast(data.message || 'Save failed.');
                        }
                    } catch (e) {
                        this.showToast('Failed to save changes: ' + e.message);
                    } finally {
                        this.isSavingSections = false;
                    }
                },

                async resetAllSectionsToVisible() {
                    if (!confirm('Do you want to reset and show all sections on the homepage?')) return;
                    try {
                        const res = await fetch('api_sections.php?action=reset_defaults', { method: 'POST' });
                        const data = await res.json();
                        if (data.status === 'success') {
                            this.sections.forEach(s => s.is_visible = 1);
                            this.showToast('All sections are now set to visible.');
                        }
                    } catch (e) {
                        this.showToast('Reset failed: ' + e.message);
                    }
                },

                // ==========================================
                // INVOICES & RECEIPTS MANAGEMENT METHODS
                // ==========================================
                invoices: [],
                invoiceStats: {
                    total_count: 0,
                    total_amount_all: 0,
                    total_paid: 0,
                    total_unpaid: 0,
                    unpaid_count: 0,
                    this_month_paid: 0
                },
                isLoadingInvoices: false,
                invoiceSearchQuery: '',
                invoiceStatusFilter: 'All',
                isInvoiceModalOpen: false,
                invoiceModalMode: 'create', // 'create' or 'edit'
                isSavingInvoice: false,
                isInvoicePrintModalOpen: false,
                selectedInvoice: null,
                isSendingTelegramInvoice: false,

                // Invoice Form State
                invoiceForm: {
                    id: null,
                    invoice_number: '',
                    invoice_type: 'Tax Invoice',
                    invoice_date: new Date().toISOString().split('T')[0],
                    due_date: '',
                    customer_name: '',
                    customer_phone: '',
                    customer_email: '',
                    customer_address: '',
                    payment_method: 'Card / EFTPOS',
                    status: 'Paid',
                    tax_rate: 10,
                    tax_included: true,
                    discount: 0,
                    notes: 'Thank you for choosing David Hair Systems. All custom hair systems include 1 complimentary fitting check & styling touch-up within 14 days.',
                    send_telegram_on_create: true,
                    items: [
                        { item_name: 'Men Custom Toupee (Standard Skin)', description: 'Ultra-thin 0.06mm skin base European hair', quantity: 1, unit_price: 650.00, amount: 650.00 },
                        { item_name: 'VIP 1-on-1 Fitting Consultation', description: 'Cut, blend & perimeter attachment', quantity: 1, unit_price: 150.00, amount: 150.00 }
                    ]
                },

                // Preset Services for Quick Selection
                salonServicePresets: [
                    { name: 'VIP 1-on-1 Fitting Consultation', price: 150.00, desc: 'Complete custom fitting, sizing & attachment' },
                    { name: 'Men Custom Toupee (Standard Skin)', price: 650.00, desc: 'Ultra-thin 0.06mm skin base European hair' },
                    { name: 'Men Custom Toupee (French/Swiss Lace)', price: 680.00, desc: 'Breathable Swiss lace base custom contour' },
                    { name: 'Toupee Maintenance & Scalp Clean', price: 120.00, desc: 'Deep scalp wash, adhesive removal & re-taping' },
                    { name: 'Haircut & Natural Hair Blend', price: 60.00, desc: 'Precision blend cut with natural bio-hair' },
                    { name: 'Base Perimeter Re-bonding Service', price: 80.00, desc: 'Front hairline adhesive touch-up & bond' },
                    { name: 'Ultra-Hold Contour Tape Roll (36pcs)', price: 35.00, desc: 'Medical-grade 4-week double-sided adhesive' },
                    { name: 'Ghost Bond XL Adhesive (1.3oz)', price: 45.00, desc: 'Water-based invisible lace scalp bond' },
                    { name: 'Scalp Protector Dab-On Primer', price: 28.00, desc: 'Sweat & oil barrier skin primer' },
                    { name: 'Custom Order Booking Deposit', price: 200.00, desc: 'Non-refundable fitting template deposit' }
                ],

                get filteredInvoices() {
                    return this.invoices.filter(inv => {
                        const q = this.invoiceSearchQuery.toLowerCase().trim();
                        const matchesQuery = !q ||
                            (inv.invoice_number && inv.invoice_number.toLowerCase().includes(q)) ||
                            (inv.customer_name && inv.customer_name.toLowerCase().includes(q)) ||
                            (inv.customer_phone && inv.customer_phone.toLowerCase().includes(q)) ||
                            (inv.customer_email && inv.customer_email.toLowerCase().includes(q));

                        const matchesStatus = this.invoiceStatusFilter === 'All' || inv.status === this.invoiceStatusFilter;
                        return matchesQuery && matchesStatus;
                    });
                },

                get computedInvoiceSubtotal() {
                    if (!this.invoiceForm.items || !this.invoiceForm.items.length) return 0;
                    return this.invoiceForm.items.reduce((sum, it) => {
                        const q = Number(it.quantity) || 0;
                        const p = Number(it.unit_price) || 0;
                        return sum + (q * p);
                    }, 0);
                },

                get computedInvoiceTax() {
                    const net = Math.max(0, this.computedInvoiceSubtotal - (Number(this.invoiceForm.discount) || 0));
                    if (this.invoiceForm.tax_included) {
                        return net / 11;
                    } else {
                        const rate = Number(this.invoiceForm.tax_rate) || 10;
                        return net * (rate / 100);
                    }
                },

                get computedInvoiceTotal() {
                    const net = Math.max(0, this.computedInvoiceSubtotal - (Number(this.invoiceForm.discount) || 0));
                    if (this.invoiceForm.tax_included) {
                        return net;
                    } else {
                        return net + this.computedInvoiceTax;
                    }
                },

                async loadInvoices() {
                    this.isLoadingInvoices = true;
                    try {
                        const res = await fetch('api_invoices.php?action=list');
                        const data = await res.json();
                        if (data.status === 'success') {
                            this.invoices = data.invoices || [];
                            this.invoiceStats = data.stats || this.invoiceStats;
                        }
                    } catch (e) {
                        console.error('Failed to load invoices:', e);
                    } finally {
                        this.isLoadingInvoices = false;
                    }
                },

                async openNewInvoiceModal() {
                    this.invoiceModalMode = 'create';
                    let nextNum = 'INV-' + new Date().toISOString().substring(2, 4) + (new Date().getMonth() + 1).toString().padStart(2, '0') + '-001';
                    try {
                        const res = await fetch('api_invoices.php?action=get_next_number');
                        const data = await res.json();
                        if (data.status === 'success' && data.next_number) {
                            nextNum = data.next_number;
                        }
                    } catch (e) {}

                    this.invoiceForm = {
                        id: null,
                        invoice_number: nextNum,
                        invoice_type: 'Tax Invoice',
                        invoice_date: new Date().toISOString().split('T')[0],
                        due_date: '',
                        customer_name: '',
                        customer_phone: '',
                        customer_email: '',
                        customer_address: '',
                        payment_method: 'Card / EFTPOS',
                        status: 'Paid',
                        tax_rate: 10,
                        tax_included: true,
                        discount: 0,
                        notes: 'Thank you for choosing David Hair Systems. All custom hair systems include 1 complimentary fitting check & styling touch-up within 14 days.',
                        send_telegram_on_create: true,
                        items: [
                            { item_name: 'Men Custom Toupee (Standard Skin)', description: 'Ultra-thin 0.06mm skin base European hair', quantity: 1, unit_price: 650.00, amount: 650.00 },
                            { item_name: 'VIP 1-on-1 Fitting Consultation', description: 'Cut, blend & perimeter attachment', quantity: 1, unit_price: 150.00, amount: 150.00 }
                        ]
                    };
                    this.isInvoiceModalOpen = true;
                },

                onSelectMemberDropdown(memberId) {
                    if (!memberId) return;
                    const m = this.members.find(mem => String(mem.id) === String(memberId));
                    if (m) {
                        this.invoiceForm.customer_name = m.full_name || '';
                        this.invoiceForm.customer_phone = m.phone || '';
                        this.invoiceForm.customer_email = m.email || '';
                        this.showToast(`Selected member: ${m.full_name}`);
                    }
                },

                onSelectServicePreset(idx) {
                    if (idx === '' || idx === undefined) return;
                    const preset = this.salonServicePresets[parseInt(idx, 10)];
                    if (preset) {
                        this.invoiceForm.items.push({
                            item_name: preset.name,
                            description: preset.desc,
                            quantity: 1,
                            unit_price: preset.price,
                            amount: preset.price
                        });
                        this.showToast(`Added "${preset.name}" ($${preset.price})`);
                    }
                },

                onSelectStockPreset(itemId) {
                    if (!itemId) return;
                    const item = this.items.find(i => String(i.id) === String(itemId));
                    if (item) {
                        const price = Number(item.price) || 0;
                        const desc = [item.category, item.base, item.size].filter(Boolean).join(' • ');
                        this.invoiceForm.items.push({
                            item_name: item.name,
                            description: desc,
                            quantity: 1,
                            unit_price: price,
                            amount: price
                        });
                        this.showToast(`Added stock item: "${item.name}" ($${price})`);
                    }
                },

                addInvoiceItem() {
                    this.invoiceForm.items.push({
                        item_name: '',
                        description: '',
                        quantity: 1,
                        unit_price: 0,
                        amount: 0
                    });
                },

                removeInvoiceItem(idx) {
                    if (this.invoiceForm.items.length > 1) {
                        this.invoiceForm.items.splice(idx, 1);
                    }
                },

                async saveInvoice() {
                    this.isSavingInvoice = true;
                    try {
                        const payload = {
                            ...this.invoiceForm,
                            subtotal: this.computedInvoiceSubtotal,
                            tax_amount: this.computedInvoiceTax,
                            total_amount: this.computedInvoiceTotal
                        };

                        const action = this.invoiceModalMode === 'edit' ? 'update' : 'create';
                        const res = await fetch(`api_invoices.php?action=${action}`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(payload)
                        });
                        const data = await res.json();

                        if (data.status === 'success') {
                            this.showToast(data.message || 'Invoice saved successfully!');
                            this.isInvoiceModalOpen = false;
                            await this.loadInvoices();

                            // Automatically open preview modal for the saved invoice
                            if (data.invoice) {
                                this.selectedInvoice = data.invoice;
                                this.isInvoicePrintModalOpen = true;
                            }
                        } else {
                            this.showToast('Save error: ' + (data.message || 'Unknown error'));
                        }
                    } catch (e) {
                        this.showToast('Network error while saving invoice: ' + e.message);
                    } finally {
                        this.isSavingInvoice = false;
                    }
                },

                async editInvoice(inv) {
                    try {
                        const res = await fetch(`api_invoices.php?action=get&id=${inv.id}`);
                        const data = await res.json();
                        if (data.status === 'success' && data.invoice) {
                            const i = data.invoice;
                            this.invoiceModalMode = 'edit';
                            this.invoiceForm = {
                                id: i.id,
                                invoice_number: i.invoice_number,
                                invoice_type: i.invoice_type,
                                invoice_date: i.invoice_date,
                                due_date: i.due_date || '',
                                customer_name: i.customer_name,
                                customer_phone: i.customer_phone || '',
                                customer_email: i.customer_email || '',
                                customer_address: i.customer_address || '',
                                payment_method: i.payment_method || 'Card / EFTPOS',
                                status: i.status || 'Paid',
                                tax_rate: Number(i.tax_rate) || 10,
                                tax_included: true,
                                discount: Number(i.discount) || 0,
                                notes: i.notes || '',
                                send_telegram_on_create: false,
                                items: (i.items && i.items.length) ? i.items.map(it => ({
                                    item_name: it.item_name,
                                    description: it.description || '',
                                    quantity: Number(it.quantity) || 1,
                                    unit_price: Number(it.unit_price) || 0,
                                    amount: Number(it.amount) || 0
                                })) : [{ item_name: 'Salon Service', description: '', quantity: 1, unit_price: 100, amount: 100 }]
                            };
                            this.isInvoicePrintModalOpen = false;
                            this.isInvoiceModalOpen = true;
                        }
                    } catch (e) {
                        this.showToast('Failed to load invoice details: ' + e.message);
                    }
                },

                async deleteInvoice(inv) {
                    if (!confirm(`Are you sure you want to delete invoice "${inv.invoice_number}" for ${inv.customer_name}?`)) return;
                    try {
                        const res = await fetch('api_invoices.php?action=delete', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ id: inv.id })
                        });
                        const data = await res.json();
                        if (data.status === 'success') {
                            this.showToast('Invoice deleted successfully');
                            await this.loadInvoices();
                        } else {
                            this.showToast(data.message || 'Delete failed');
                        }
                    } catch (e) {
                        this.showToast('Error deleting invoice: ' + e.message);
                    }
                },

                async quickToggleInvoiceStatus(inv) {
                    const nextStatus = inv.status === 'Paid' ? 'Unpaid / Due' : 'Paid';
                    try {
                        const res = await fetch('api_invoices.php?action=update_status', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ id: inv.id, status: nextStatus })
                        });
                        const data = await res.json();
                        if (data.status === 'success') {
                            inv.status = nextStatus;
                            this.showToast(`Invoice #${inv.invoice_number} marked as ${nextStatus}`);
                            await this.loadInvoices();
                        }
                    } catch (e) {
                        this.showToast('Failed to update status');
                    }
                },

                async openInvoicePreview(inv) {
                    try {
                        const res = await fetch(`api_invoices.php?action=get&id=${inv.id}`);
                        const data = await res.json();
                        if (data.status === 'success' && data.invoice) {
                            this.selectedInvoice = data.invoice;
                            this.isInvoicePrintModalOpen = true;
                        } else {
                            this.showToast('Failed to load invoice details');
                        }
                    } catch (e) {
                        this.showToast('Network error loading invoice');
                    }
                },

                printInvoice() {
                    window.print();
                },

                downloadInvoicePdf() {
                    if (!this.selectedInvoice) return;
                    const element = document.getElementById('printable-invoice');
                    if (!element) return;

                    const invNum = this.selectedInvoice.invoice_number || 'INV';
                    const custName = (this.selectedInvoice.customer_name || 'Customer').replace(/[^a-zA-Z0-9]/g, '_');
                    const filename = `DavidHair_${invNum}_${custName}.pdf`;

                    this.showToast('Generating high-resolution PDF...');

                    const opt = {
                        margin: [10, 10, 10, 10],
                        filename: filename,
                        image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: { scale: 2, useCORS: true, logging: false },
                        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                    };

                    if (typeof html2pdf !== 'undefined') {
                        html2pdf().set(opt).from(element).save().then(() => {
                            this.showToast(`PDF downloaded: ${filename}`);
                        }).catch(err => {
                            console.error(err);
                            window.print();
                        });
                    } else {
                        window.print();
                    }
                },

                sendWhatsAppInvoice(inv) {
                    if (!inv) return;
                    const invNum = inv.invoice_number || 'N/A';
                    const date = inv.invoice_date || '';
                    const name = inv.customer_name || 'Customer';
                    const total = Number(inv.total_amount || 0).toFixed(2);
                    const payment = inv.payment_method || 'Card';
                    const status = inv.status || 'Paid';

                    let itemsText = '';
                    if (inv.items && inv.items.length) {
                        inv.items.forEach(it => {
                            itemsText += `• ${it.item_name} x ${it.quantity}: $${Number(it.amount).toFixed(2)}\n`;
                        });
                    }

                    const message = 
`🧾 *DAVID HAIR SYSTEMS*
*${inv.invoice_type || 'Tax Invoice'} / Receipt*
━━━━━━━━━━━━━━━━━━━━
🔖 *Invoice #:* ${invNum}
📅 *Date:* ${date}
👤 *Customer:* ${name}
━━━━━━━━━━━━━━━━━━━━
🛍️ *Purchased Items & Services:*
${itemsText}--------------------
💰 *Total Amount:* AUD $${total} (${status.toUpperCase()})
💳 *Payment:* ${payment}
━━━━━━━━━━━━━━━━━━━━
📍 Shop 62, 46-58 Buckingham Ave, Springvale VIC
📞 +61 433 484 882 | www.davidhairshop.com
Thank you for choosing David Hair Systems!`;

                    let rawPhone = (inv.customer_phone || '').replace(/[^0-9]/g, '');
                    if (rawPhone.startsWith('0')) {
                        rawPhone = '61' + rawPhone.substring(1);
                    } else if (!rawPhone.startsWith('61') && rawPhone.length === 9) {
                        rawPhone = '61' + rawPhone;
                    }

                    const url = rawPhone ? 
                        `https://wa.me/${rawPhone}?text=${encodeURIComponent(message)}` :
                        `https://wa.me/?text=${encodeURIComponent(message)}`;

                    if (navigator.clipboard) {
                        navigator.clipboard.writeText(message);
                    }

                    window.open(url, '_blank');
                    this.showToast('WhatsApp opened! Receipt text also copied to clipboard.');
                },

                async sendTelegramInvoice(inv) {
                    if (!inv) return;
                    this.isSendingTelegramInvoice = true;
                    try {
                        const res = await fetch('api_invoices.php?action=send_telegram', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ id: inv.id })
                        });
                        const data = await res.json();
                        if (data.status === 'success') {
                            this.showToast('✅ Invoice notification sent to your Telegram!');
                        } else {
                            this.showToast('Telegram Error: ' + (data.message || 'Check bot settings'));
                        }
                    } catch (e) {
                        this.showToast('Failed to send Telegram message: ' + e.message);
                    } finally {
                        this.isSendingTelegramInvoice = false;
                    }
                },

                copyInvoiceText(inv) {
                    if (!inv) return;
                    const invNum = inv.invoice_number || 'N/A';
                    const date = inv.invoice_date || '';
                    const name = inv.customer_name || 'Customer';
                    const total = Number(inv.total_amount || 0).toFixed(2);
                    const payment = inv.payment_method || 'Card';
                    const status = inv.status || 'Paid';

                    let itemsText = '';
                    if (inv.items && inv.items.length) {
                        inv.items.forEach(it => {
                            itemsText += `• ${it.item_name} x ${it.quantity}: $${Number(it.amount).toFixed(2)}\n`;
                        });
                    }

                    const text = 
`[David Hair Systems - ${inv.invoice_type || 'Tax Invoice'}]
Invoice #: ${invNum}
Date: ${date}
Customer: ${name}
Phone: ${inv.customer_phone || '-'}
--------------------------------
${itemsText}--------------------------------
Subtotal: $${Number(inv.subtotal || 0).toFixed(2)}
Total AUD: $${total} (${status})
Payment: ${payment}
--------------------------------
David Hair Systems Springvale
Shop 62, 46-58 Buckingham Ave, Springvale VIC 3171
Tel: +61 433 484 882 | www.davidhairshop.com`;

                    if (navigator.clipboard) {
                        navigator.clipboard.writeText(text);
                        this.showToast('Receipt text copied to clipboard!');
                    }
                }
            }
        }
    </script>
</body>
</html>
