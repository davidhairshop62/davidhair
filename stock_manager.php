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

                    <button @click="logout()" class="bg-rose-950/80 hover:bg-rose-900 text-rose-300 border border-rose-800 text-xs font-bold px-3 py-2.5 rounded-lg transition-colors flex items-center gap-1.5" title="Sign Out">
                        <i class="fa-solid fa-power-off"></i> Logout
                    </button>
                </div>
            </div>
        </header>

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
                <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
                    <div class="relative w-full sm:w-80">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" x-model="searchQuery" placeholder="Search by SKU, Name, Base..." class="w-full bg-brand-inner border border-brand-border rounded-lg pl-9 pr-4 py-2 text-xs text-white outline-none focus:border-brand-teal">
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
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
                </div>

                <!-- Stock Items Table -->
                <div class="bg-brand-cardBg border border-brand-border rounded-2xl overflow-hidden shadow-xl">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-brand-inner text-brand-textMuted uppercase font-bold text-[11px] border-b border-brand-border">
                                <tr>
                                    <th class="py-3.5 px-4">Product Image</th>
                                    <th class="py-3.5 px-4">SKU & Item Name</th>
                                    <th class="py-3.5 px-4">Category / Base</th>
                                    <th class="py-3.5 px-4">Specs (Size/Density/Color)</th>
                                    <th class="py-3.5 px-4">Price (AUD)</th>
                                    <th class="py-3.5 px-4">Stock Qty</th>
                                    <th class="py-3.5 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-brand-border/60">
                                <template x-for="item in filteredItems" :key="item.id">
                                    <tr class="hover:bg-brand-inner/50 transition-colors">
                                        <td class="py-3 px-4">
                                            <div class="w-14 h-14 rounded-lg overflow-hidden border border-brand-border bg-brand-inner flex-shrink-0">
                                                <img :src="item.image" :alt="item.name" class="w-full h-full object-cover">
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="font-mono text-brand-tealLight font-bold" x-text="item.sku"></div>
                                            <div class="font-bold text-white text-sm mt-0.5" x-text="item.name"></div>
                                        </td>
                                        <td class="py-3 px-4 space-y-1">
                                            <span class="inline-block bg-brand-inner px-2 py-0.5 rounded border border-brand-border font-semibold text-slate-300" x-text="item.category"></span>
                                            <div class="text-[11px] text-brand-textMuted" x-text="item.base"></div>
                                        </td>
                                        <td class="py-3 px-4 text-slate-300">
                                            <div><span class="text-brand-textMuted">Size:</span> <span class="font-semibold" x-text="item.size"></span></div>
                                            <div><span class="text-brand-textMuted">Density:</span> <span class="font-semibold" x-text="item.density"></span></div>
                                            <div><span class="text-brand-textMuted">Color:</span> <span class="font-semibold" x-text="item.color"></span></div>
                                        </td>
                                        <td class="py-3 px-4 font-mono font-bold text-white text-sm">
                                            $<span x-text="Number(item.price).toFixed(2)"></span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span :class="item.stockQty > 5 ? 'bg-emerald-950 text-emerald-400 border-emerald-800' : 'bg-rose-950 text-rose-400 border-rose-800'" class="px-2.5 py-1 rounded-full text-xs font-bold border inline-block" x-text="item.stockQty + ' in stock'"></span>
                                        </td>
                                        <td class="py-3 px-4 text-right space-x-1">
                                            <button @click="openModal('edit', item)" class="bg-brand-inner hover:bg-slate-700 text-slate-200 border border-brand-border text-xs px-2.5 py-1.5 rounded transition-colors font-semibold">Edit</button>
                                            <button @click="deleteItem(item)" class="bg-rose-950/80 hover:bg-rose-900 text-rose-300 border border-rose-800 text-xs px-2.5 py-1.5 rounded transition-colors font-semibold">Delete</button>
                                        </td>
                                    </tr>
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

        </main>
    </div>

    <!-- STOCK ITEM EDIT MODAL -->
    <div x-show="isModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-brand-darkBg/90 backdrop-blur-sm">
        <div class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl">
            <div class="flex justify-between items-center border-b border-brand-border pb-3">
                <h3 class="font-heading font-bold text-white text-lg" x-text="modalMode === 'edit' ? 'Edit Stock Item' : 'Add New Stock Item'"></h3>
                <button @click="isModalOpen = false" class="text-slate-400 hover:text-white p-1"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form @submit.prevent="saveStockItem()" class="space-y-4 text-xs">
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

                <div>
                    <label class="block font-bold text-slate-300 mb-1">Upload Product Image File</label>
                    <input type="file" accept="image/*" @change="handleFileSelect($event)" class="w-full text-slate-300 text-xs">
                </div>

                <div>
                    <label class="block font-bold text-slate-300 mb-1">Or Image URL</label>
                    <input type="text" x-model="form.image_url" placeholder="https://..." class="w-full bg-brand-darkBg border border-brand-border rounded-lg px-3 py-2 text-white outline-none focus:border-brand-teal">
                </div>

                <div x-show="imagePreview" class="p-2 bg-brand-inner border border-brand-border rounded-lg flex items-center gap-3">
                    <img :src="imagePreview" class="w-12 h-12 object-cover rounded border">
                    <span class="text-[11px] text-slate-300">Selected Image Preview</span>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="isModalOpen = false" class="px-4 py-2 bg-brand-inner border border-brand-border rounded-lg text-slate-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 teal-gradient rounded-lg text-white font-bold">Save Stock</button>
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

                isModalOpen: false,
                modalMode: 'add',
                form: { id: null, sku: '', name: '', category: '', type: '', base: '', size: '', density: '', color: '', price: 0, stockQty: 0, image_url: '' },
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

                async init() {
                    await this.checkAuth();
                },

                get totalQty() {
                    return this.items.reduce((sum, item) => sum + parseInt(item.stockQty || 0), 0);
                },

                get filteredItems() {
                    return this.items.filter(item => {
                        const matchesCategory = this.selectedCategory === 'All' || item.category === this.selectedCategory;
                        const query = this.searchQuery.toLowerCase();
                        const matchesQuery = !query || 
                            item.name.toLowerCase().includes(query) || 
                            item.sku.toLowerCase().includes(query) || 
                            item.base.toLowerCase().includes(query);
                        return matchesCategory && matchesQuery;
                    });
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
                    if (mode === 'edit' && item) {
                        this.form = { ...item, image_url: item.image };
                        this.imagePreview = item.image;
                    } else {
                        this.form = {
                            id: null,
                            sku: 'SKU-DH-' + (100 + this.items.length + 1),
                            name: '', category: "Men's Toupees", type: "Skin Systems",
                            base: "0.03mm Skin", size: '8x10"', density: '90%',
                            color: '#1B Off Black', price: 450, stockQty: 10, image_url: ''
                        };
                        this.imagePreview = '';
                    }
                    this.isModalOpen = true;
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
                    formData.append('image_url', this.form.image_url);
                    if (this.selectedFile) formData.append('image_file', this.selectedFile);

                    try {
                        const res = await fetch('/admin_stock_api.php', { method: 'POST', body: formData });
                        const result = await res.json();
                        if (result.status === 'success') {
                            alert(result.message);
                            this.isModalOpen = false;
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
                }
            }
        }
    </script>
</body>
</html>
