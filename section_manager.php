<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage Section & Navigation Manager | David Hair Admin</title>
    
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
        .glass-card { background: rgba(28, 37, 65, 0.95); backdrop-filter: blur(12px); }
    </style>
</head>
<body class="bg-brand-darkBg text-brand-textMain font-sans antialiased min-h-screen" x-data="sectionManagerApp()" x-init="init()">

    <!-- 1. ADMIN LOGIN MODAL SCREEN (Shown if not authenticated) -->
    <div x-show="!isAuthenticated" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-brand-darkBg/98 backdrop-blur-md">
        <div class="bg-brand-cardBg border border-brand-border rounded-2xl max-w-md w-full p-6 sm:p-8 space-y-6 shadow-2xl">
            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-xl teal-gradient flex items-center justify-center font-heading font-extrabold text-white text-2xl mx-auto shadow-md">
                    DH
                </div>
                <h2 class="font-heading text-xl sm:text-2xl font-bold text-white tracking-tight">David Hair Section Manager</h2>
                <p class="text-xs text-brand-textMuted">Sign in with Admin credentials to manage homepage sections &amp; navigation menus.</p>
            </div>

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
                    <span x-text="isLoggingIn ? 'Authenticating...' : 'Sign In to Section Manager'"></span>
                </button>
            </form>

            <div class="pt-2 text-center text-[11px] text-brand-textMuted border-t border-brand-border/60">
                Authorized Staff Only &bull; David Hair Systems Pty Ltd
            </div>
        </div>
    </div>

    <!-- 2. MAIN APPLICATION CONTENT (Authenticated) -->
    <div x-show="isAuthenticated" x-cloak>
        <!-- Header / Navbar -->
        <header class="bg-brand-cardBg border-b border-brand-border sticky top-0 z-30 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl teal-gradient flex items-center justify-center font-heading font-extrabold text-white text-xl shadow-md">
                        DH
                    </div>
                    <div>
                        <h1 class="font-heading text-lg sm:text-xl font-bold text-white tracking-tight leading-tight">Sections &amp; Menu Manager</h1>
                        <span class="text-[11px] text-brand-tealLight font-bold uppercase tracking-wider block">Homepage Visibility Control Console</span>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 sm:gap-3">
                    <a href="stock_manager.php" class="bg-brand-inner hover:bg-slate-800 text-slate-200 border border-brand-border text-xs font-bold px-3.5 py-2.5 rounded-lg transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-boxes-stacked text-amber-400"></i>
                        <span class="hidden sm:inline">Stock &amp; Bookings Admin</span>
                    </a>

                    <a href="/" target="_blank" class="bg-brand-inner hover:bg-slate-800 text-slate-200 border border-brand-border text-xs font-semibold px-3.5 py-2.5 rounded-lg transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-globe text-brand-tealLight"></i>
                        <span class="hidden sm:inline">View Live Site</span>
                    </a>

                    <button @click="logout()" class="bg-rose-950/80 hover:bg-rose-900 text-rose-300 border border-rose-800 text-xs font-bold px-3 py-2.5 rounded-lg transition-colors flex items-center gap-1.5" title="Sign Out">
                        <i class="fa-solid fa-power-off"></i>
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

        <!-- MAIN CONTAINER -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            <!-- Summary Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm">
                    <div class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">Total Sections</div>
                    <div class="text-2xl font-extrabold text-white mt-1" x-text="sections.length"></div>
                </div>
                <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm">
                    <div class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">Visible on Site</div>
                    <div class="text-2xl font-extrabold text-emerald-400 mt-1 flex items-center gap-2">
                        <span x-text="visibleCount"></span>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Active</span>
                    </div>
                </div>
                <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm">
                    <div class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">Hidden Sections</div>
                    <div class="text-2xl font-extrabold text-slate-400 mt-1" x-text="hiddenCount"></div>
                </div>
                <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl shadow-sm">
                    <div class="text-xs font-bold text-brand-textMuted uppercase tracking-wider">In Navigation Menu</div>
                    <div class="text-2xl font-extrabold text-brand-tealLight mt-1" x-text="inMenuCount"></div>
                </div>
            </div>

            <!-- Toolbar: Category Filter & Action Buttons -->
            <div class="bg-brand-cardBg border border-brand-border p-4 rounded-xl flex flex-col md:flex-row items-center justify-between gap-4 shadow-sm">
                <!-- Category Filter Tabs -->
                <div class="flex flex-wrap items-center gap-1.5 w-full md:w-auto">
                    <template x-for="cat in ['All', 'Interactive & AI', 'Products & Ordering', 'Salon & Services', 'Brand & Trust']" :key="cat">
                        <button type="button" @click="activeFilter = cat"
                            :class="activeFilter === cat ? 'bg-brand-teal text-white font-bold shadow' : 'bg-brand-inner text-slate-300 hover:text-white border border-brand-border'"
                            class="px-3 py-1.5 rounded-lg text-xs transition-all cursor-pointer"
                            x-text="cat">
                        </button>
                    </template>
                </div>

                <!-- Bulk Actions -->
                <div class="flex items-center gap-2.5 w-full md:w-auto justify-end">
                    <button type="button" @click="resetAllToVisible()" :disabled="isSaving"
                        class="bg-brand-inner hover:bg-slate-800 text-slate-200 border border-brand-border px-3.5 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-eye text-emerald-400"></i>
                        <span>Show All Sections</span>
                    </button>

                    <button type="button" @click="saveAllChanges()" :disabled="isSaving"
                        class="teal-gradient hover:opacity-95 text-white px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all flex items-center gap-2 shadow-md cursor-pointer disabled:opacity-50">
                        <i class="fa-solid fa-floppy-disk" :class="{ 'fa-spin': isSaving }"></i>
                        <span x-text="isSaving ? 'Saving Changes...' : 'Save All Changes'"></span>
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

            <!-- Sections List Cards / Table -->
            <div class="space-y-3.5">
                <template x-for="(sec, idx) in filteredSections" :key="sec.id">
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
                            
                            <!-- Left Info & Badges -->
                            <div class="flex items-start gap-3 sm:gap-3.5 flex-1 min-w-0">
                                <!-- Drag Handle -->
                                <div class="cursor-grab active:cursor-grabbing text-slate-500 hover:text-teal-400 p-1.5 flex items-center justify-center shrink-0 transition-colors mt-0.5 rounded hover:bg-slate-800"
                                     title="Drag to reorder section">
                                    <i class="fa-solid fa-grip-vertical text-base sm:text-lg"></i>
                                </div>

                                <!-- Sort Order / Icon Box -->
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-white shrink-0 mt-0.5 shadow-sm"
                                     :class="sec.is_visible ? 'teal-gradient' : 'bg-slate-800 text-slate-400 border border-slate-700'">
                                    <i :class="sec.menu_icon || 'fa-solid fa-layer-group'"></i>
                                </div>

                                <div class="space-y-1 min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="font-heading font-bold text-sm sm:text-base text-white tracking-tight" x-text="sec.name"></h3>
                                        
                                        <!-- Category Badge -->
                                        <span class="text-[10px] px-2 py-0.5 rounded-full font-mono font-semibold"
                                              :class="{
                                                  'bg-sky-500/20 text-sky-300 border border-sky-500/30': sec.category === 'Interactive & AI',
                                                  'bg-amber-500/20 text-amber-300 border border-amber-500/30': sec.category === 'Products & Ordering',
                                                  'bg-purple-500/20 text-purple-300 border border-purple-500/30': sec.category === 'Salon & Services',
                                                  'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30': sec.category === 'Brand & Trust'
                                              }" x-text="sec.category"></span>

                                        <!-- Anchor ID Tag -->
                                        <span class="text-[10px] px-2 py-0.5 rounded-md bg-slate-800 border border-slate-700 text-slate-400 font-mono" x-text="'#' + sec.section_key"></span>
                                    </div>
                                    <p class="text-xs text-brand-textMuted line-clamp-2" x-text="sec.description"></p>
                                </div>
                            </div>

                            <!-- Right Controls: Order, Toggle Switches & Inputs -->
                            <div class="flex flex-wrap items-center gap-3 sm:gap-5 w-full lg:w-auto justify-between lg:justify-end pt-3 lg:pt-0 border-t lg:border-t-0 border-brand-border/60" draggable="false" @dragstart.stop.prevent="">
                                
                                <!-- Display Order Controls (Move Up/Down & Sequence Number) -->
                                <div class="flex items-center gap-1.5 bg-brand-inner/90 border border-brand-border/80 rounded-xl px-2.5 py-1.5 shadow-inner">
                                    <div class="text-left leading-tight mr-1">
                                        <span class="text-[10px] text-brand-textMuted font-bold uppercase tracking-wider block">Order</span>
                                        <span class="text-xs font-mono font-extrabold text-teal-300" x-text="'#' + sec.sort_order"></span>
                                    </div>
                                    <input type="number" min="1" max="99" 
                                           x-model.number="sec.sort_order" 
                                           @change="normalizeAndSaveOrders()"
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

                                <!-- Toggle 1: Show on Homepage (Visible) -->
                                <div class="flex items-center gap-2.5">
                                    <button type="button" @click="toggleVisibility(sec)"
                                        :class="sec.is_visible ? 'bg-emerald-500' : 'bg-slate-700'"
                                        class="w-13 h-7 rounded-full p-1 transition-colors duration-200 relative flex-shrink-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-400">
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
                                    <button type="button" @click="toggleMenu(sec)"
                                        :class="sec.in_menu ? 'bg-brand-teal' : 'bg-slate-700'"
                                        class="w-13 h-7 rounded-full p-1 transition-colors duration-200 relative flex-shrink-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-teal-400">
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

                                <!-- Quick Jump to Section -->
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

            <!-- Helpful Information Panel -->
            <div class="bg-brand-cardBg/90 border border-brand-border rounded-xl p-5 space-y-2 text-xs text-brand-textMuted">
                <div class="font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-brand-tealLight"></i>
                    <span>How Section Visibility &amp; Menu Synchronization Works</span>
                </div>
                <p>1. <strong>Visible on Homepage:</strong> Controls whether the section is rendered and visible on <a href="/" target="_blank" class="text-brand-tealLight underline">https://davidhair.atozhair.shop</a>. If toggled off, the entire section collapses smoothly with zero layout shift.</p>
                <p>2. <strong>In Nav Menu:</strong> Automatically synchronizes with both the top desktop navigation bar and the mobile hamburger drawer menu. If a section is hidden, its corresponding menu item is immediately hidden as well.</p>
                <p>3. <strong>Instant Save:</strong> Clicking the switches instantly updates the MySQL database. You can also edit navigation labels and click "Save All Changes".</p>
            </div>

        </main>
    </div>

    <!-- Application Script -->
    <script>
        function sectionManagerApp() {
            return {
                isAuthenticated: false,
                isLoggingIn: false,
                loginError: '',
                showPassword: false,
                loginForm: {
                    email: '',
                    password: ''
                },
                sections: [],
                activeFilter: 'All',
                isSaving: false,
                toastMessage: '',
                draggedSec: null,
                dragOverId: null,

                init() {
                    this.checkAuth();
                    this.loadSections();
                },

                showToast(msg) {
                    this.toastMessage = msg;
                    setTimeout(() => {
                        this.toastMessage = '';
                    }, 3500);
                },

                async checkAuth() {
                    try {
                        const res = await fetch('admin_stock_api.php?action=check_auth');
                        const data = await res.json();
                        if (data.status === 'authenticated') {
                            this.isAuthenticated = true;
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
                    try {
                        const formData = new FormData();
                        formData.append('email', this.loginForm.email);
                        formData.append('password', this.loginForm.password);

                        const res = await fetch('admin_stock_api.php?action=login', {
                            method: 'POST',
                            body: formData
                        });
                        const data = await res.json();
                        if (data.status === 'success') {
                            this.isAuthenticated = true;
                            this.showToast('Login successful!');
                            this.loadSections();
                        } else {
                            this.loginError = data.message || 'Login failed. Please check credentials.';
                        }
                    } catch (e) {
                        this.loginError = 'Server connection error: ' + e.message;
                    } finally {
                        this.isLoggingIn = false;
                    }
                },

                async logout() {
                    try {
                        await fetch('admin_stock_api.php?action=logout');
                    } catch (e) {}
                    this.isAuthenticated = false;
                    this.showToast('Signed out.');
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

                get filteredSections() {
                    if (this.activeFilter === 'All') {
                        return this.sections;
                    }
                    return this.sections.filter(s => s.category === this.activeFilter);
                },

                get visibleCount() {
                    return this.sections.filter(s => s.is_visible == 1).length;
                },

                get hiddenCount() {
                    return this.sections.filter(s => s.is_visible == 0).length;
                },

                get inMenuCount() {
                    return this.sections.filter(s => s.in_menu == 1).length;
                },

                async toggleVisibility(sec) {
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

                async toggleMenu(sec) {
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

                moveSection(sec, direction) {
                    this.sections.sort((a, b) => (Number(a.sort_order) || 0) - (Number(b.sort_order) || 0));
                    const currIdx = this.sections.findIndex(s => s.id === sec.id);
                    if (currIdx === -1) return;
                    const targetIdx = currIdx + direction;
                    if (targetIdx < 0 || targetIdx >= this.sections.length) return;

                    const temp = this.sections[currIdx];
                    this.sections[currIdx] = this.sections[targetIdx];
                    this.sections[targetIdx] = temp;

                    this.normalizeAndSaveOrders();
                },

                isFirstSection(sec) {
                    if (!this.sections.length) return true;
                    return this.sections[0].id === sec.id;
                },

                isLastSection(sec) {
                    if (!this.sections.length) return true;
                    return this.sections[this.sections.length - 1].id === sec.id;
                },

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

                        this.normalizeAndSaveOrders();
                        this.showToast(`"${moved.name}" moved to position #${toIdx + 1}`);
                    }

                    this.draggedSec = null;
                    this.dragOverId = null;
                },

                onDragEnd(evt) {
                    this.draggedSec = null;
                    this.dragOverId = null;
                },

                normalizeAndSaveOrders() {
                    this.sections.sort((a, b) => (Number(a.sort_order) || 0) - (Number(b.sort_order) || 0));
                    this.sections.forEach((s, idx) => {
                        s.sort_order = idx + 1;
                    });
                    this.saveAllChanges();
                },

                async saveAllChanges() {
                    this.isSaving = true;
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
                        this.isSaving = false;
                    }
                },

                async resetAllToVisible() {
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
                }
            }
        }
    </script>
</body>
</html>
