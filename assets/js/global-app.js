// Global Bridge for cross-frame iframe communication (FFA, Diag, Calculator) & QR booking deep-links
window.dhOpenBookingModal = function() {
    var attempts = 0;
    function tryOpen() {
        attempts++;
        try {
            // 1. Dispatch custom events for Alpine
            window.dispatchEvent(new CustomEvent('open-booking-modal'));
            window.dispatchEvent(new CustomEvent('close-all-submodals'));
            window.dispatchEvent(new CustomEvent('close-ffa-modal'));
            window.dispatchEvent(new CustomEvent('close-diag-modal'));
            window.dispatchEvent(new CustomEvent('close-calculator-modal'));

            // 2. Direct Alpine proxy mutation on root app
            var appEl = document.querySelector('[x-data*="davidHairApp"]');
            if (appEl && appEl._x_dataStack && appEl._x_dataStack[0]) {
                var data = appEl._x_dataStack[0];
                data.isFfaModalOpen = false;
                data.isDiagModalOpen = false;
                data.isCalculatorModalOpen = false;
                data.isWelcomeModalOpen = false;
                data.isBookingModalOpen = true;
                if (typeof data.fetchMonthStatus === 'function') {
                    data.fetchMonthStatus();
                }
                return true;
            }
        } catch(err) {
            console.warn('dhOpenBookingModal error:', err);
        }
        // Retry if Alpine is still loading from CDN (up to 2.5s)
        if (attempts < 25) {
            setTimeout(tryOpen, 100);
        }
        return false;
    }
    tryOpen();
};

// Global cross-frame postMessage listener
window.addEventListener('message', function(event) {
    if (!event.data) return;
    var act = (event.data && event.data.action) || event.data;
    if (act === 'openBookingModal' || act === 'open-booking-modal') {
        window.dhOpenBookingModal();
    }
    if (act === 'closeGalleryModal' || act === 'close-gallery-modal') {
        window.dispatchEvent(new CustomEvent('close-gallery-modal'));
    }
    if (act === 'openGalleryModal' || act === 'open-gallery-modal') {
        window.dispatchEvent(new CustomEvent('open-gallery-modal'));
    }
});

// URL Hash & Param listener on page load (Triggered by QR Code: ?book=true or #booking)
function dhCheckUrlAndOpenBooking() {
    try {
        var s = (window.location.search || '').toLowerCase();
        var h = (window.location.hash || '').toLowerCase();
        if (s.includes('book') || s.includes('reserve') || h === '#booking' || h === '#book') {
            window.dhOpenBookingModal();
        }
    } catch (e) {}
}

window.addEventListener('DOMContentLoaded', dhCheckUrlAndOpenBooking);
window.addEventListener('load', dhCheckUrlAndOpenBooking);
window.addEventListener('hashchange', dhCheckUrlAndOpenBooking);
document.addEventListener('alpine:initialized', dhCheckUrlAndOpenBooking);
document.addEventListener('alpine:init', dhCheckUrlAndOpenBooking);

function davidHairApp() {
    return {
        lang: 'en-AU',
        setLang(l) {
            this.lang = 'en-AU';
        },
        currency: 'AUD',
        mobileMenuOpen: false,
        isBookingModalOpen: false,

        isStockDetailModalOpen: false,
        selectedStockDetail: null,
        stockDetailActiveImage: '',
        openStockDetailModal(item) {
            this.selectedStockDetail = item;
            this.stockDetailActiveImage = item.active_image || item.image || (item.gallery_images && item.gallery_images[0]) || '';
            this.isStockDetailModalOpen = true;
        },
        closeStockDetailModal() {
            this.isStockDetailModalOpen = false;
            this.selectedStockDetail = null;
            this.stockDetailActiveImage = '';
        },
        isB2bModalOpen: false,
        isWelcomeModalOpen: false,
        welcomeDontShowToday: false,
        isCalculatorModalOpen: false,
        calculatorUrl: '/calculator.html?t=' + Date.now(),
        isDiagModalOpen: false,
        diagUrl: '/diag.html?t=' + Date.now(),
        openDiagModal() {
            this.diagUrl = '/diag.html?t=' + Date.now();
            this.isDiagModalOpen = true;
        },
        isFfaModalOpen: false,
        ffaModalUrl: '/ffa_interactive_questionnaire.html?t=' + Date.now(),
        openFfaModal() {
            this.ffaModalUrl = '/ffa_interactive_questionnaire.html?t=' + Date.now();
            this.isFfaModalOpen = true;
        },
        isComparisonModalOpen: false,
        comparisonUrl: '/comparison.html?t=' + Date.now(),
        openComparisonModal() {
            this.comparisonUrl = '/comparison.html?t=' + Date.now();
            this.isComparisonModalOpen = true;
        },
        isGalleryModalOpen: false,
        galleryUrl: '/gallery.html?t=' + Date.now(),
        openGalleryModal() {
            this.galleryUrl = '/gallery.html?t=' + Date.now();
            this.isGalleryModalOpen = true;
        },
        isFaqModalOpen: false,
        isMapModalOpen: false,
        googleMapsPlaceUrl: 'https://www.google.com/maps/place/David+Hair+Shop/@-37.951084,145.1483707,17z/data=!3m2!4b1!5s0x6ad614c7832bd26b:0x4e8247ab315ada8f!4m6!3m5!1s0x6ad61408aa7f0829:0xe8f1dc7860f28ea4!8m2!3d-37.951084!4d145.150951!16s%2Fg%2F1tgc9n5p?entry=ttu&g_ep=EgoyMDI2MTAxNC4wIKXMDSoASAFQAw%3D%3D',
        googleMapsEmbedUrl: 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3147.288279822692!2d145.1483707!3d-37.951084!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad61408aa7f0829%3A0xe8f1dc7860f28ea4!2sDavid%20Hair%20Shop!5e0!3m2!1sen!2sau!4v1713400000000!5m2!1sen!2sau',
        addressCopied: false,
        openMapModal() {
            this.isMapModalOpen = true;
        },
        copyStudioAddress() {
            const text = 'Shop F16 (Level 1), Buckingham Ave, Springvale VIC 3171, Australia';
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(() => {
                    this.addressCopied = true;
                    setTimeout(() => { this.addressCopied = false; }, 2500);
                }).catch(() => {});
            } else {
                const ta = document.createElement('textarea');
                ta.value = text;
                document.body.appendChild(ta);
                ta.select();
                try {
                    document.execCommand('copy');
                    this.addressCopied = true;
                    setTimeout(() => { this.addressCopied = false; }, 2500);
                } catch(e) {}
                document.body.removeChild(ta);
            }
        },

        // Dynamic Homepage Sections & Navigation Menu Visibility
        sectionsMap: (function() {
            try {
                const c = localStorage.getItem('dh_sections_cache');
                if (c) {
                    const p = JSON.parse(c).visibility || {};
                    p.builder = false;
                    return p;
                }
            } catch(e) {}
            return { builder: false };
        })(),
        sectionsMenuMap: (function() {
            try {
                const c = localStorage.getItem('dh_sections_cache');
                if (c) {
                    const p = JSON.parse(c).in_menu || {};
                    p.builder = false;
                    return p;
                }
            } catch(e) {}
            return { builder: false };
        })(),
        sectionsOrderMap: (function() {
            try {
                const c = localStorage.getItem('dh_sections_cache');
                if (c) return JSON.parse(c).orders || {};
            } catch(e) {}
            return {};
        })(),
        sectionsList: (function() {
            try {
                const c = localStorage.getItem('dh_sections_cache');
                if (c) return JSON.parse(c).sections || [];
            } catch(e) {}
            return [];
        })(),

        isSectionVisible(key) {
            if (key === 'builder') return false;
            if (key === 'medical-wigs' || key === 'frontal-fibrosing-alopecia') return true;
            if (this.sectionsMap && key in this.sectionsMap) {
                return this.sectionsMap[key] === true;
            }
            return true;
        },

        isSectionInMenu(key) {
            if (key === 'builder') return false;
            if (key === 'medical-wigs' || key === 'frontal-fibrosing-alopecia') return true;
            if (this.sectionsMenuMap && key in this.sectionsMenuMap) {
                return this.sectionsMenuMap[key] === true;
            }
            return true;
        },

        getSectionOrder(key, defaultOrder = 50) {
            if (this.sectionsOrderMap && key in this.sectionsOrderMap && this.sectionsOrderMap[key] !== undefined && this.sectionsOrderMap[key] !== null) {
                return Number(this.sectionsOrderMap[key]);
            }
            if (this.sectionsList && this.sectionsList.length > 0) {
                const s = this.sectionsList.find(x => x.section_key === key);
                if (s && s.sort_order !== undefined && s.sort_order !== null) return Number(s.sort_order);
            }
            return defaultOrder;
        },

        applySectionsOrder(sections) {
            // Multi-page hub architecture:
            // - On index.php, #hero stays on top and #sections-hub cards order via CSS grid `order`.
            // - On dedicated subpages, DOM order is fixed: top bar -> main section -> bottom CTA/explore section.
            // Never call main.appendChild() here, otherwise the main section would jump below the bottom CTA section.
            return;
        },

        getProductsMenuOrder() {
            return Math.min(
                this.getSectionOrder('stock', 7),
                this.getSectionOrder('salon', 8),
                this.getSectionOrder('products-range', 11),
                this.getSectionOrder('factory', 12),
                this.getSectionOrder('b2b', 13)
            );
        },

        isTopSection(key) {
            let minOrder = 9999;
            let topKey = 'hero';
            const allKeys = ['hero', 'medical-wigs', 'frontal-fibrosing-alopecia', 'hair-diagnosis-hub', 'lifestyle-performance', 'stock', 'salon', 'transformations', 'ai-simulator', 'products-range', 'factory', 'b2b'];
            for (let i = 0; i < allKeys.length; i++) {
                const k = allKeys[i];
                if (this.isSectionVisible(k)) {
                    const ord = this.getSectionOrder(k, i + 1);
                    if (ord < minOrder) {
                        minOrder = ord;
                        topKey = k;
                    }
                }
            }
            return topKey === key;
        },

        getMenuTitle(key, defaultTitle) {
            if (this.sectionsList && this.sectionsList.length > 0) {
                const s = this.sectionsList.find(x => x.section_key === key);
                if (s && s.menu_title) return s.menu_title;
            }
            return defaultTitle;
        },

        getMenuBadge(key, defaultBadge = '') {
            if (this.sectionsList && this.sectionsList.length > 0) {
                const s = this.sectionsList.find(x => x.section_key === key);
                if (s && s.menu_badge !== undefined && s.menu_badge !== null && s.menu_badge !== '') return s.menu_badge;
            }
            return defaultBadge;
        },

        getMenuIcon(key, defaultIcon = 'fa-solid fa-layer-group') {
            if (this.sectionsList && this.sectionsList.length > 0) {
                const s = this.sectionsList.find(x => x.section_key === key);
                if (s && s.menu_icon) {
                    return s.menu_icon.startsWith('fa-') ? s.menu_icon : 'fa-solid ' + s.menu_icon;
                }
            }
            return defaultIcon;
        },

        async fetchSectionSettings() {
            try {
                const res = await fetch('/api_sections.php?action=get_public_sections&t=' + Date.now());
                const data = await res.json();
                if (data.status === 'success') {
                    this.sectionsMap = data.visibility || {};
                    this.sectionsMap.builder = false;
                    this.sectionsMenuMap = data.in_menu || {};
                    this.sectionsMenuMap.builder = false;
                    this.sectionsOrderMap = data.orders || {};
                    this.sectionsList = data.sections || [];
                    try {
                        localStorage.setItem('dh_sections_cache', JSON.stringify({
                            visibility: this.sectionsMap,
                            in_menu: this.sectionsMenuMap,
                            orders: this.sectionsOrderMap,
                            sections: this.sectionsList,
                            timestamp: Date.now()
                        }));
                    } catch(e) {}
                    this.applySectionsOrder(data.sections);
                }
            } catch(e) {
                console.warn('Could not load dynamic sections:', e);
            }
        },

        // Climate & Active Lifestyle Modals
        isWaterproofModalOpen: false,
        isGymModalOpen: false,
        isUvModalOpen: false,
        isBarberModalOpen: false,

        // Melbourne Flagship Salon Modals
        isSalonCutInModalOpen: false,
        isSalonRebondModalOpen: false,
        isSalonTemplateModalOpen: false,
        isSalonDiyModalOpen: false,

        // VIP Membership State
        currentMember: null,
        isSignInModalOpen: false,
        isSignUpModalOpen: false,
        isMemberProfileModalOpen: false,
        copiedReferral: false,
        memberRecords: [],
        isLoadingMemberRecords: false,
        signInForm: {
            email: '',
            password: '',
            error: '',
            loading: false
        },
        signUpForm: {
            full_name: '',
            email: '',
            password: '',
            phone: '',
            hair_system_type: 'Full Skin System (0.03mm)',
            referral_code: '',
            error: '',
            loading: false
        },
        currentStep: 1,
        stockFilter: 'All',

        // Self-Diagnosis & Tailored Solution State (Option 1 & Option 2)
        activeDiagnosisView: 'both', // 'wizard', 'matrix', 'both'

        // Option 1: Step-by-Step Interactive Wizard
        quizGender: 'men',
        quizStep: 1,
        quizPhase: 'phase2',
        quizStyle: 'exposed',

        // Option 2: Visual Diagram & Hairline Matrix
        matrixGender: 'men',
        matrixPhase: 'phase2',
        matrixHairline: 'exposed',

        setQuizGender(g) {
            this.quizGender = g;
            if (g === 'men') {
                this.quizPhase = 'phase2';
                this.quizStyle = 'exposed';
            } else {
                this.quizPhase = 'w_phase2';
                this.quizStyle = 'w_part';
            }
        },

        setMatrixGender(g) {
            this.matrixGender = g;
            if (g === 'men') {
                this.matrixPhase = 'phase2';
                this.matrixHairline = 'exposed';
            } else {
                this.matrixPhase = 'w_phase2';
                this.matrixHairline = 'w_part';
            }
        },

        applyDiagnosisToBuilder(gender, phase, style) {
            this.builder.profile = gender;
            if (gender === 'men') {
                if (style === 'exposed') {
                    this.builder.menBase = 'men_skin'; // 0.03mm Ultra-thin Skin
                    this.builder.menDirection = 'Back (Pompadour)';
                } else if (style === 'semi') {
                    this.builder.menBase = 'men_lace_front_swiss_pu'; // Lace Front + Swiss PU
                    this.builder.menDirection = 'Left Part';
                } else {
                    this.builder.menBase = 'men_swiss_pu'; // Swiss Net + PU
                    this.builder.menDirection = 'Forward (Bangs)';
                }
                if (phase === 'phase1') this.builder.menSizePreset = '6x8';
                else if (phase === 'phase2') this.builder.menSizePreset = '7x9';
                else if (phase === 'phase3') this.builder.menSizePreset = '8x10';
                else if (phase === 'phase4') {
                    this.builder.profile = 'full';
                    this.builder.fullBase = 'full_cnc';
                }
            } else {
                this.builder.profile = 'women';
                this.builder.womenBase = 'women_silk';
                if (phase === 'w_phase1') this.builder.womenSizePreset = '4x5';
                else if (phase === 'w_phase2') this.builder.womenSizePreset = '6x6';
                else if (phase === 'w_phase3') this.builder.womenSizePreset = '7x8';
                else if (phase === 'w_phase4') {
                    this.builder.profile = 'full';
                    this.builder.fullBase = 'full_silk_lace';
                }
            }
            this.currentStep = 2; // Jump to Step 2 in customizer
            const el = document.getElementById('builder');
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        },

        t(key) {
            if (this.dict && this.dict['en'] && this.dict['en'][key]) {
                return this.dict['en'][key];
            }
            return key;
        },

        getStepTitle(step) {
            const prof = (this.builder && this.builder.profile) || 'men';
            if (this.stepTitles && this.stepTitles[prof]) {
                return this.stepTitles[prof][step - 1] || '';
            }
            const list = ['Select Profile', 'Base & Size', 'Hair Specs', 'Color & Blend', 'Craft Details', 'Review & Submit'];
            return list[step - 1] || '';
        },

        dict: {
            en: {
                nav_order: 'Order',
                nav_salon: 'Salon',
                nav_stock: 'Stock',
                nav_trans: 'Before & After',
                nav_ai: 'AI Try-On',
                nav_factory: 'Factory',
                nav_b2b: 'B2B',
                nav_free_consulting: 'Free Consulting',

                hero_badge: 'Factory Direct • Springvale Flagship',
                hero_title_1: 'Factory-Direct Precision Hairpieces,',
                hero_title_2: 'Finished by Melbourne Specialists',
                hero_subtitle: 'Bespoke hand-tied human hair toupees and silk top pieces from our factory, tailored and maintained at our Springvale flagship salon.',
                hero_btn_builder: 'Build Custom Order',
                hero_btn_stock: 'Book Springvale Fitting',
                hero_stat_1_val: '100%',
                hero_stat_1_lbl: 'Virgin Human Hair',
                hero_stat_2_val: '1-Piece',
                hero_stat_2_lbl: 'Custom MOQ',
                hero_stat_3_val: 'Since 2006',
                hero_stat_3_lbl: 'Business in Australia',
                hero_card_title: 'Melbourne Master Cut-in',
                hero_card_sub: 'Springvale Salon, VIC Australia',

                climate_badge: 'Engineered for Freedom',
                climate_title: 'Built for the Australian Climate & Active Lifestyle',
                climate_card_1_title: '100% Water-Resistant Hold',
                climate_card_1_desc: 'Confidently swim in open water, take hot showers, and maintain 4-6 weeks of waterproof hold.',
                climate_card_2_title: 'Gym & Workout Breathability',
                climate_card_2_desc: 'Micro-ventilation mesh base releases heat and scalp moisture during high-intensity workouts.',
                climate_card_3_title: 'UV & Climate Shield',
                climate_card_3_desc: 'Materials tested against high Australian UV and humidity without adhesive breakdown.',
                climate_card_4_title: 'Seamless Barber Cut-In',
                climate_card_4_desc: 'Precision fade work and texture matching eliminates any visible transition seam.',

                ai_badge: 'Instant Style Fitting',
                ai_title: 'AI Hairstyle Fitting & Simulator',
                ai_desc: 'Try on different hairpieces & styles directly on your photo. Adjust position and scale for a perfect fit before ordering.',
                ai_upload_btn: 'Upload Your Photo',
                ai_select_style: 'Select Style:',
                ai_select_color: 'Select Color:',
                ai_fit_controls: 'Hairpiece Fit Controls',
                ai_move_x: 'Move Left / Right',
                ai_move_y: 'Move Up / Down',
                ai_scale_size: 'Scale Size',
                ai_width_stretch: 'Width Stretch',
                ai_height_stretch: 'Height Stretch',
                ai_reset_hair: 'Reset Hair Fit',
                ai_reset_face: 'Reset Photo',
                ai_reset_all: 'Reset All',
                ai_save_btn: 'Save Hairstyle Specs',

                builder_badge: 'Bespoke Customizer',
                builder_title: 'Interactive Custom Order Builder',
                builder_sub: 'Design your custom hand-tied hairpiece step-by-step. Get instant price estimations and direct factory precision.',
                builder_step: 'Step',
                builder_of: 'of',
                builder_next: 'Next Step',
                builder_prev: 'Previous',
                builder_submit: 'Submit Custom Order',

                stock_badge: 'Express Delivery',
                stock_title: 'Melbourne In-Stock Toupees & Toppers',
                stock_sub: 'Same-day dispatch or immediate salon fitting available in Springvale.',
                stock_btn_express: 'Detail Item',
                stock_btn_fit: 'Book Salon Fit',

                factory_badge: 'Direct Manufacturing',
                factory_title: 'Direct Factory Craftsmanship',
                factory_sub: 'Every hairpiece is 100% hand-tied by master artisans using premium human hair.',

                b2b_badge: 'Wholesale & Salon Supply',
                b2b_title: 'B2B Wholesale & Salon Partnership',
                b2b_sub: 'Join our salon network in Australia & global distribution. Direct factory pricing with low MOQ.',

            }
        },

        async init() {
            // Check URL for booking trigger (QR Code / Deep Link: ?book=true, #booking, etc.)
            try {
                var s = (window.location.search || '').toLowerCase();
                var h = (window.location.hash || '').toLowerCase();
                if (s.includes('book') || s.includes('reserve') || h === '#booking' || h === '#book') {
                    this.openBookingModal();
                }
            } catch (e) {}

            this.applySectionsOrder();
            this.fetchSectionSettings();

            // Listen for storage events (e.g. admin changed order in stock manager in another tab)
            window.addEventListener('storage', (e) => {
                if (e.key === 'dh_sections_cache' && e.newValue) {
                    try {
                        const p = JSON.parse(e.newValue);
                        if (p.orders) this.sectionsOrderMap = p.orders;
                        if (p.sections) this.sectionsList = p.sections;
                        if (p.visibility) this.sectionsMap = p.visibility;
                        if (p.in_menu) this.sectionsMenuMap = p.in_menu;
                        this.applySectionsOrder(p.sections);
                    } catch (err) {}
                }
            });

            // Always ensure fresh calculator.html on modal open
            this.$watch('isCalculatorModalOpen', (isOpen) => {
                if (isOpen) {
                    this.calculatorUrl = '/calculator.html?t=' + Date.now();
                }
            });

            // Always ensure fresh diag.html on modal open
            this.$watch('isDiagModalOpen', (isOpen) => {
                if (isOpen) {
                    this.diagUrl = '/diag.html?t=' + Date.now();
                }
            });

            // Always ensure fresh ffa_interactive_questionnaire.html on modal open
            this.$watch('isFfaModalOpen', (isOpen) => {
                if (isOpen) {
                    this.ffaModalUrl = '/ffa_interactive_questionnaire.html?t=' + Date.now();
                }
            });

            window.addEventListener('open-ffa-modal', () => {
                this.openFfaModal();
            });

            // Always ensure fresh comparison.html on modal open
            this.$watch('isComparisonModalOpen', (isOpen) => {
                if (isOpen) {
                    this.comparisonUrl = '/comparison.html?t=' + Date.now();
                }
            });

            window.addEventListener('open-comparison-modal', () => {
                this.openComparisonModal();
            });


            const formatStockData = (data) => {
                return data.map(item => {
                    let gallery = [];
                    if (Array.isArray(item.gallery_images)) {
                        gallery = item.gallery_images;
                    } else if (typeof item.gallery_images === 'string' && item.gallery_images.startsWith('[')) {
                        try { gallery = JSON.parse(item.gallery_images); } catch(e) { gallery = []; }
                    }
                    if (gallery.length === 0 && item.image) {
                        gallery = [item.image];
                    }
                    return {
                        ...item,
                        active_image: item.image,
                        gallery_images: gallery
                    };
                });
            };

            if (document.getElementById('stock')) {
            try {
                let res = await fetch('/api_get_stock.php?t=' + Date.now(), { cache: 'no-store' });
                if (!res.ok) {
                    res = await fetch('https://davidhair.atozhair.shop/api_get_stock.php?t=' + Date.now());
                }
                const result = await res.json();
                if (result.status === 'success' && Array.isArray(result.data) && result.data.length > 0) {
                    this.stockItems = formatStockData(result.data);
                }
            } catch (e) {
                try {
                    const res = await fetch('https://davidhair.atozhair.shop/api_get_stock.php?t=' + Date.now());
                    const result = await res.json();
                    if (result.status === 'success' && Array.isArray(result.data) && result.data.length > 0) {
                        this.stockItems = formatStockData(result.data);
                    }
                } catch (err2) {
                    console.log('Loaded default stock fallback:', err2);
                }
            }
            }
            this.$watch('isBookingModalOpen', (val) => {
                if (val) {
                    this.fetchMonthStatus();
                }
            });
            await this.checkMemberSession();
        },

        closeWelcomeModal(dontShowAgain = false) {
            this.isWelcomeModalOpen = false;
            if (dontShowAgain || this.welcomeDontShowToday) {
                const expireTime = new Date().getTime() + (24 * 60 * 60 * 1000);
                try {
                    localStorage.setItem('davidhair_welcome_dismissed', String(expireTime));
                } catch (e) {}
            }
        },

        claimWelcomeOffer() {
            this.closeWelcomeModal(true);
            if (this.booking) {
                this.booking.service = 'Free Consultation (15 mins)';
                this.booking.notes = 'Welcome Event Voucher Applied: $50 First-order styling voucher (FIRST50)';
            }
            this.isBookingModalOpen = true;
        },

        stepTitles: {
            men: [
                'Select Profile',
                'Base & Dimensions',
                'Hair Length & Density',
                'Color & Grey Blend',
                'Direction & Barber Craft',
                'Review & Submit'
            ],
            women: [
                'Select Profile',
                'Topper Base & Crown Size',
                'Length & Attachment',
                'Color & Parting Style',
                'Salon Finishing & Styling',
                'Review & Submit'
            ],
            full: [
                'Select Profile',
                'Medical Cap & Head Sizing',
                'Hair Length & Cap Security',
                'Color & Density Specs',
                'Salon Fitting & Scalp Protocol',
                'Review & Submit'
            ]
        },

        profiles: [
            { id: 'men', name: "Men's Toupee", subtitle: 'Frontal & Crown Replacement System', desc: 'Bespoke hand-tied toupees tailored for receding hairlines and crown hair loss with undetectable front hairlines.', basePrice: 650, icon: 'fa-solid fa-mars', badge: 'Most Popular' },
            { id: 'women', name: "Women's Top Piece", subtitle: 'Silk Top Hair Topper for Crown Thinning', desc: 'Triple-layer realistic silk top hairpieces designed for diffuse thinning, parting line volume, and instant crown fullness without shaving.', basePrice: 600, icon: 'fa-solid fa-venus', badge: 'Silk Scalp' },
            { id: 'full', name: 'Full Cap System', subtitle: 'Medical & Total Alopecia Full Head System', desc: '360° total head coverage with custom cranial contouring, anti-slip silicone, and medical-grade hypoallergenic materials.', basePrice: 800, icon: 'fa-solid fa-user-tie', badge: '360° Full Cap' }
        ],

        // Bases for Men's Toupee
        basesMen: [
            { id: 'men_swiss_pu', name: 'Swiss Net + PU (Clip & Bonding)', price: 0, desc: 'Breathable Swiss net center with durable PU perimeter for versatile clip & tape/liquid bonding.' },
            { id: 'men_lace_front_swiss_pu', name: 'Lace Front + Swiss Net + PU (Clip & Bonding)', price: 50, desc: 'Ultra-natural lace front hairline combined with Swiss net center and durable PU perimeter.' },
            { id: 'men_skin', name: 'Ultra-thin Skin (Bonding)', price: 50, desc: 'Seamless knotless micro-skin base with direct V-loop injection for realistic scalp liquid bonding.' },
            { id: 'men_full_lace', name: 'Full Swiss Lace (Bonding)', price: 0, desc: 'Maximum airflow breathability and fully undetectable hairline for active lifestyle liquid bonding.' }
        ],

        // Bases for Women's Top Piece
        basesWomen: [
            { id: 'women_silk', name: 'Triple-Layer Injected Silk Top', price: 90, desc: 'Hidden knots embedded beneath Swiss silk. Looks 100% like hair growing directly from scalp.' },
            { id: 'women_mono', name: 'Fine Mono with PU Perimeter', price: 70, desc: 'Durable, lightweight honeycomb monofilament with smooth PU rim for clips or tape.' },
            { id: 'women_lace', name: 'French Lace with Soft Stretch', price: 60, desc: 'Featherlight and ultra-breathable lace with flexible contour to hug the head curvature.' },
            { id: 'women_integ', name: 'Integration Micro-Mesh Net', price: 80, desc: 'Specially engineered open-mesh allowing you to pull your natural bio hair through for seamless blending.' }
        ],

        // Bases for Full Cap System
        basesFull: [
            { id: 'full_cnc', name: 'Premier Medical CNC Vacuum Cap', price: 250, desc: '3D cranial scan mold with European virgin hair and biometric vacuum suction fit.' },
            { id: 'full_lace', name: 'Full Swiss Lace + Stretch Center', price: 120, desc: '100% hand-tied all around. Permits high ponytails, updos, and parted styles in any direction.' },
            { id: 'full_silicone', name: 'Medical Anti-Slip Silicone Cap', price: 180, desc: 'Hypoallergenic soft silicone grip strips. Stays securely anchored without tape or adhesive.' },
            { id: 'full_skin', name: 'Full Thin Skin (0.05mm - 0.08mm)', price: 150, desc: 'Full-head skin membrane that creates immediate suction against bare scalps.' },
            { id: 'full_silk_lace', name: 'Silk Top Crown + Lace Perimeter', price: 190, desc: 'Triple-layer silk parting zone combined with invisible Swiss lace perimeter and ear tabs.' }
        ],

        // Combined legacy bases fallback
        bases: [
            { id: 'men_skin', name: 'Ultra-thin Skin (0.03mm)', price: 40, desc: 'Seamless skin illusion with direct knotless injection.' },
            { id: 'men_lace', name: 'Full Swiss Lace', price: 60, desc: 'Maximum breathability and natural hairline.' },
            { id: 'men_hybrid', name: 'Lace Front + PU Perimeter', price: 50, desc: 'Durable perimeter bonding with realistic lace front.' },
            { id: 'women_silk', name: 'Silk Top', price: 90, desc: 'Triple-layer realistic scalp appearance.' },
            { id: 'full_cnc', name: 'Premier CNC Virgin Hair System', price: 250, desc: 'Medical-grade custom head mold paired with European Virgin Hair.' }
        ],

        colorsMen: [
            { code: '#1', name: 'Jet Black', hex: '#0a0a0a' },
            { code: '#1B', name: 'Off Black', hex: '#1c1917' },
            { code: '#Natural', name: 'Natural Black', hex: '#181513' },
            { code: '#2', name: 'Darkest Brown', hex: '#2b1d14' },
            { code: '#3', name: 'Dark Brown', hex: '#3f2212' },
            { code: '#4', name: 'Medium Brown', hex: '#5c3317' },
            { code: '#6', name: 'Light Chestnut Brown', hex: '#6e3b1f' },
            { code: '#8', name: 'Light Ash Brown', hex: '#855b38' }
        ],

        colorsWomen: [
            { code: '#1B', name: 'Natural Off Black', hex: '#1c1917' },
            { code: '#2', name: 'Dark Espresso Brown', hex: '#27170e' },
            { code: '#4', name: 'Warm Chestnut', hex: '#5c3317' },
            { code: '#4/27', name: 'Honey Caramel Balayage', hex: '#a6723e' },
            { code: '#8/60', name: 'Ash Blonde Dimension', hex: '#c5b597' },
            { code: '#613', name: 'Platinum Blonde', hex: '#fef08a' }
        ],

        colorsFull: [
            { code: '#1', name: 'Jet Black', hex: '#0a0a0a' },
            { code: '#1B', name: 'Natural Off Black', hex: '#1c1917' },
            { code: '#2', name: 'Dark Brown', hex: '#2b1d14' },
            { code: '#4', name: 'Medium Brown', hex: '#5c3317' },
            { code: '#4/27', name: 'Honey Balayage', hex: '#a6723e' },
            { code: '#613', name: 'Blonde', hex: '#fef08a' }
        ],

        colors: [
            { code: '#1', name: 'Jet Black', hex: '#0a0a0a' },
            { code: '#1B', name: 'Off Black', hex: '#1c1917' },
            { code: '#2', name: 'Dark Brown', hex: '#3f2212' },
            { code: '#4', name: 'Medium Brown', hex: '#5c3317' },
            { code: '#613', name: 'Blonde', hex: '#fef08a' }
        ],

        stockItems: [
            { id: 10, sku: 'SKU-DH-102-Small-Xtra', name: "Swiss Net +  PU Perimeter  Small  Xtra", category: "Men's Toupee", type: "Swiss Lace", base: "Lace Front + PU", size: '6x7"', density: '80%', color: '#Natural', price: 450, stockQty: 3, image: '/uploads/stock_1791609994_1983.jpg', badge: 'Limited', description: "A Swiss Net + PU Perimeter man's toupee combines the ultra-natural appearance of fine lace with the durability and secure bonding of polyurethane (PU). It is widely regarded as one of the best hybrid base designs for a realistic yet manageable hair replacement system." },
            { id: 9, sku: 'SKU-DH-102-Small', name: "Swiss Net +  PU Perimeter  Small", category: "Men's Toupee", type: "Swiss Lace", base: "Lace Front + PU", size: '6x8"', density: '80%', color: '#Natural', price: 500, stockQty: 2, image: '/uploads/stock_1791609994_1983.jpg', badge: 'Limited', description: '' },
            { id: 8, sku: 'SKU-DH-102-Medium', name: "Swiss Net +  PU Perimeter  Medium", category: "Men's Toupee", type: "Swiss Lace", base: "Lace Front + PU", size: '7x9"', density: '80%', color: '#Natural', price: 550, stockQty: 3, image: '/uploads/stock_1791609994_1983.jpg', badge: '', description: '' },
            { id: 7, sku: 'SKU-DH-102-Large', name: "Swiss Net +  PU Perimeter  Large", category: "Men's Toupee", type: "Swiss Lace", base: "Lace Front + PU", size: '8x10"', density: '80%', color: '#Natural', price: 600, stockQty: 2, image: '/uploads/stock_1791609994_1983.jpg', badge: '', description: '' },
            { id: 1, sku: 'SKU-DH-101', name: "Ultra-Thin Skin 0.03mm Toupee", category: "Men's Toupee", type: "Skin Systems", base: "0.03mm Skin", size: '8x10"', density: '100%', color: '#1B Off Black', price: 650, stockQty: 3, image: '/uploads/stock_1791608690_1661.jpg', badge: 'Best Seller', description: 'Ultra-thin 0.03mm invisible skin base with direct knotless V-loop injection.' }
        ],

        builder: {
            profile: 'men',
            
            // Men's Toupee Specs
            menBase: 'men_swiss_pu',
            menUnit: 'inch',
            menSizePreset: '6x8',
            menWidth: 6.0,
            menLength: 8.0,
            menHairLength: 6,
            menDensity: 80,
            menWave: 'Straight',
            menColor: '#1B',
            menGreyPercent: 0,
            menDirection: 'Freestyle',
            menBleachedKnots: false,
            menScallopedFront: true,
            menUnderVentilation: false,
            menPrecisionCutIn: true,
            menBondService: false,
            menClipAndTape: true,

            // Women's Top Piece Specs
            womenBase: 'women_silk',
            womenSizePreset: '6x6',
            womenWidth: 6.0,
            womenLength: 6.0,
            womenHairLength: 14,
            womenDensity: 110,
            womenWave: 'Natural Body Wave',
            womenColor: '#1B',
            womenParting: 'Free Part (Multi-Directional)',
            womenAttachment: 'clips', // 'clips', 'clips_tape', 'halo', 'poly_bond'
            womenStyling: false,
            womenConcealedKnots: false,
            womenVelvetBand: false,
            womenExtraClips: false,

            // Full Cap System Specs
            fullBase: 'full_cnc',
            fullCapSize: 'medium', // 'small', 'medium', 'large', 'custom'
            fullCircumference: 22.5,
            fullFrontToNape: 14.0,
            fullEarToEar: 12.5,
            fullCustomMold: false,
            fullHairLength: 16,
            fullDensity: 120,
            fullWave: 'Natural Straight',
            fullColor: '#1B',
            fullParting: 'Free Part (360° Styling)',
            fullSecurity: 'straps', // 'straps', 'silicone_pads', 'clips_straps'
            fullBleached360: false,
            fullSalonFit: false,
            fullMedicalScalp: false,
            fullCareKit: false,

            // Customer info
            customerName: '',
            customerPhone: '',
            customerEmail: '',
            notes: '',

            // Fallback / Generic accessors
            get base() {
                if (this.profile === 'women') return this.womenBase;
                if (this.profile === 'full') return this.fullBase;
                return this.menBase;
            },
            set base(v) {
                if (this.profile === 'women') this.womenBase = v;
                else if (this.profile === 'full') this.fullBase = v;
                else this.menBase = v;
            },
            get unit() { return this.menUnit; },
            set unit(v) { this.menUnit = v; },
            get width() { return this.profile === 'women' ? this.womenWidth : this.menWidth; },
            set width(v) { if (this.profile === 'women') this.womenWidth = v; else this.menWidth = v; },
            get length() { return this.profile === 'women' ? this.womenLength : this.menLength; },
            set length(v) { if (this.profile === 'women') this.womenLength = v; else this.menLength = v; },
            get hairLength() {
                if (this.profile === 'women') return this.womenHairLength;
                if (this.profile === 'full') return this.fullHairLength;
                return this.menHairLength;
            },
            set hairLength(v) {
                if (this.profile === 'women') this.womenHairLength = v;
                else if (this.profile === 'full') this.fullHairLength = v;
                else this.menHairLength = v;
            },
            get density() {
                if (this.profile === 'women') return this.womenDensity;
                if (this.profile === 'full') return this.fullDensity;
                return this.menDensity;
            },
            set density(v) {
                if (this.profile === 'women') this.womenDensity = v;
                else if (this.profile === 'full') this.fullDensity = v;
                else this.menDensity = v;
            },
            get wave() {
                if (this.profile === 'women') return this.womenWave;
                if (this.profile === 'full') return this.fullWave;
                return this.menWave;
            },
            set wave(v) {
                if (this.profile === 'women') this.womenWave = v;
                else if (this.profile === 'full') this.fullWave = v;
                else this.menWave = v;
            },
            get color() {
                if (this.profile === 'women') return this.womenColor;
                if (this.profile === 'full') return this.fullColor;
                return this.menColor;
            },
            set color(v) {
                if (this.profile === 'women') this.womenColor = v;
                else if (this.profile === 'full') this.fullColor = v;
                else this.menColor = v;
            },
            get greyPercent() { return this.menGreyPercent; },
            set greyPercent(v) { this.menGreyPercent = v; },
            get direction() {
                if (this.profile === 'women') return this.womenParting;
                if (this.profile === 'full') return this.fullParting;
                return this.menDirection;
            },
            set direction(v) {
                if (this.profile === 'women') this.womenParting = v;
                else if (this.profile === 'full') this.fullParting = v;
                else this.menDirection = v;
            },
            get bleachedKnots() {
                if (this.profile === 'women') return this.womenConcealedKnots;
                if (this.profile === 'full') return this.fullBleached360;
                return this.menBleachedKnots;
            },
            get underVentilation() { return this.profile === 'men' ? this.menUnderVentilation : false; },
            get precisionCutIn() {
                if (this.profile === 'women') return this.womenStyling;
                if (this.profile === 'full') return this.fullSalonFit;
                return this.menPrecisionCutIn;
            },
            get bondService() {
                if (this.profile === 'women') return this.womenAttachment === 'poly_bond';
                if (this.profile === 'full') return this.fullMedicalScalp;
                return this.menBondService;
            },
            get clipAndTape() {
                if (this.profile === 'women') return this.womenAttachment === 'clips';
                if (this.profile === 'full') return this.fullSecurity === 'clips_straps';
                return this.menClipAndTape;
            }
        },

        calendarYear: new Date().getFullYear(),
        calendarMonth: new Date().getMonth() + 1,
        calendarMonthStatus: {},
        calendarRegularOffDays: [0],
        calendarClosurePeriods: [],
        selectedDateSlots: [],
        selectedDateIsClosed: false,
        selectedDateClosedReason: '',
        isBookingSubmitting: false,
        bookingSuccessData: null,
        fittingServices: [
            { label: 'Free Consultation', duration: '15 mins', value: 'Free Consultation (15 mins)', icon: 'fa-regular fa-comments' },
            { label: 'Medical Wig & Rebate Fitting', duration: '45 mins', value: 'Medical Wig & Rebate Fitting (45 mins)', icon: 'fa-solid fa-hand-holding-heart' },
            { label: 'Collecting Order and Fitting', duration: '45 mins', value: 'Collecting Order and Fitting (45 mins)', icon: 'fa-solid fa-box-open' },
            { label: 'Maintenance', duration: '60 min', value: 'Maintenance (60 min)', icon: 'fa-solid fa-screwdriver-wrench' },
            { label: 'Cleaning Hair Piece', duration: '30 min', value: 'Cleaning Hair Piece (30 min)', icon: 'fa-solid fa-soap' },
            { label: 'Rebonding & Cut', duration: '90 mins', value: 'Rebonding & Cut (90 mins)', icon: 'fa-solid fa-scissors' }
        ],
        booking: {
            service: 'Free Consultation (15 mins)',
            date: '',
            time: '',
            name: '',
            phone: '',
            notes: ''
        },

        b2bForm: {
            selectedTier: 'Master Salon Partner',
            company: '',
            type: 'Hair Salon',
            location: '',
            volume: '15-40 pcs',
            name: '',
            phone: ''
        },

        get filteredStockItems() {
            let items = (this.stockItems || []).filter(i => i.is_visible === undefined || i.is_visible === null || i.is_visible == 1 || i.is_visible === true || i.is_visible === '1');
            if (this.stockFilter === "Men's Toupee" || this.stockFilter === "Men's Toupees") {
                items = items.filter(i => i.category === "Men's Toupee" || i.category === "Men's Toupees");
            } else if (this.stockFilter === "Women's topper" || this.stockFilter === "Women's Toppers" || this.stockFilter === "Women's Topper") {
                items = items.filter(i => (i.category || '').toLowerCase().includes('topper'));
            } else if (this.stockFilter === "Human Hair Wig") {
                items = items.filter(i => (i.category || '').toLowerCase() === 'human hair wig');
            } else if (this.stockFilter === "Synthetic Wig") {
                items = items.filter(i => (i.category || '').toLowerCase() === 'synthetic wig');
            } else if (this.stockFilter === "Wig") {
                items = items.filter(i => (i.category || '').toLowerCase().includes('wig'));
            } else if (this.stockFilter === "Hair Extension") {
                items = items.filter(i => (i.category || '').toLowerCase().includes('extension'));
            }

            // Sort by Category, then Product Name (A-Z)
            const catOrder = {
                "Men's Toupee": 1,
                "Men's Toupees": 1,
                "Women's topper": 2,
                "Women's Topper": 2,
                "Women's Toppers": 2,
                "Human Hair Wig": 3,
                "Synthetic Wig": 4,
                "Wig": 5,
                "Hair Extension": 6
            };

            return [...items].sort((a, b) => {
                const orderA = catOrder[a.category] !== undefined ? catOrder[a.category] : 99;
                const orderB = catOrder[b.category] !== undefined ? catOrder[b.category] : 99;
                if (orderA !== orderB) {
                    return orderA - orderB;
                }
                const catCmp = (a.category || '').localeCompare(b.category || '', undefined, { sensitivity: 'base' });
                if (catCmp !== 0) {
                    return catCmp;
                }
                return (a.name || '').localeCompare(b.name || '', undefined, { numeric: true, sensitivity: 'base' });
            });
        },

        selectProfile(id) {
            this.builder.profile = id;
            this.currentStep = 2;
            if (typeof document !== 'undefined') {
                const el = document.getElementById('builder');
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        },

        getProfileName(id) {
            const p = this.profiles.find(x => x.id === id);
            return p ? p.name : id;
        },

        getProfilePrice(id) {
            const p = this.profiles.find(x => x.id === id);
            return p ? p.basePrice : 650;
        },

        getProfileDesc(id) {
            const p = this.profiles.find(x => x.id === id);
            return p ? p.desc : '';
        },

        getProfileTagline(id) {
            const p = this.profiles.find(x => x.id === id);
            return p ? p.subtitle : '';
        },

        getProfileIcon(id) {
            const p = this.profiles.find(x => x.id === id);
            return p ? p.icon : 'fa-solid fa-crown';
        },

        getActiveBases() {
            if (this.builder.profile === 'women') return this.basesWomen;
            if (this.builder.profile === 'full') return this.basesFull;
            return this.basesMen;
        },

        getActiveColors() {
            if (this.builder.profile === 'women') return this.colorsWomen;
            if (this.builder.profile === 'full') return this.colorsFull;
            return this.colorsMen;
        },

        getBaseName(id) {
            const all = [...(this.basesMen || []), ...(this.basesWomen || []), ...(this.basesFull || []), ...(this.bases || [])];
            const b = all.find(x => x.id === id);
            return b ? b.name : id;
        },

        getBasePrice(id) {
            const all = [...(this.basesMen || []), ...(this.basesWomen || []), ...(this.basesFull || []), ...(this.bases || [])];
            const b = all.find(x => x.id === id);
            return b && b.price !== undefined ? b.price : 0;
        },

        getDensityLabel(val) {
            if (val <= 70) return 'Light';
            if (val <= 90) return 'Medium Light';
            if (val <= 110) return 'Medium';
            if (val <= 125) return 'Medium Full';
            return 'Heavy';
        },

        getMenDensitySurcharge() {
            const d = Number(this.builder.menDensity) || 80;
            if (d <= 80) return 0;
            if (d <= 90) return 10;
            if (d <= 100) return 20;
            return 30; // 110%
        },

        getMenWaveSurcharge() {
            const w = this.builder.menWave;
            if (w === 'Slight Wave' || w === 'Body Wave') return 10;
            if (w === 'Tight Curl') return 30;
            return 0; // Straight
        },

        getMenGreySurcharge() {
            const g = Number(this.builder.menGreyPercent) || 0;
            if (g <= 0) return 0;
            if (g <= 5) return 10;
            if (g <= 10) return 15;
            if (g <= 30) return 30;
            if (g <= 50) return 60;
            return 100; // 80%
        },

        getDimensionSurcharge() {
            if (this.builder.profile === 'men') {
                if (this.builder.menSizePreset === '6x8') return 0;
                if (this.builder.menSizePreset === '7x9') return 20;
                if (this.builder.menSizePreset === '8x10') return 40;
                // Custom W x L
                let w = Number(this.builder.menWidth) || 8;
                let l = Number(this.builder.menLength) || 10;
                if (this.builder.menUnit === 'cm') {
                    w = w / 2.54;
                    l = l / 2.54;
                }
                const sum = w + l;
                if (sum <= 14) return 0;
                if (sum <= 16) return 20;
                if (sum <= 18) return 40;
                return 70;
            }
            return 0;
        },

        getWomenSizeSurcharge() {
            const sz = this.builder.womenSizePreset;
            if (sz === '5x5') return 0;
            if (sz === '6x6') return 25;
            if (sz === '7x7') return 45;
            if (sz === '8x8') return 70;
            const w = Number(this.builder.womenWidth) || 6;
            const l = Number(this.builder.womenLength) || 6;
            const area = w * l;
            if (area <= 25) return 0;
            if (area <= 36) return 25;
            if (area <= 49) return 45;
            return 70;
        },

        getHairLengthSurchargeFor(len) {
            const l = Number(len) || 6;
            if (l <= 6) return 0;
            if (l === 8) return 50;
            if (l === 10) return 90;
            if (l === 12) return 100;
            if (l === 14) return 200;
            if (l === 16) return 300;
            if (l === 18) return 400;
            if (l === 20) return 500;
            if (l > 20) return 500 + (l - 20) * 50;
            return 0;
        },

        getHairLengthSurcharge() {
            if (this.builder.profile === 'men') return this.getHairLengthSurchargeFor(this.builder.menHairLength);
            if (this.builder.profile === 'women') return this.getHairLengthSurchargeFor(this.builder.womenHairLength);
            return this.getHairLengthSurchargeFor(this.builder.fullHairLength);
        },

        getHairLengthPriceTag(len) {
            const cost = this.getHairLengthSurchargeFor(len);
            if (cost === 0) return 'Included';
            return '+' + this.formatCurrency(cost);
        },

        calculateTotal() {
            let total = 0;
            const p = this.profiles.find(x => x.id === this.builder.profile);
            total += p ? p.basePrice : 650;

            if (this.builder.profile === 'men') {
                const b = this.basesMen.find(x => x.id === this.builder.menBase);
                total += b && b.price !== undefined ? b.price : 0;
                total += this.getDimensionSurcharge();
                total += this.getHairLengthSurchargeFor(this.builder.menHairLength);
                total += this.getMenDensitySurcharge();
                total += this.getMenWaveSurcharge();
                total += this.getMenGreySurcharge();
                if (this.builder.menBleachedKnots) total += 20;
                if (this.builder.menScallopedFront) total += 25;
                if (this.builder.menUnderVentilation) total += 20;
                if (this.builder.menPrecisionCutIn) total += 145;
                if (this.builder.menBondService) total += 80;
                if (this.builder.menClipAndTape) total += 30;
            } else if (this.builder.profile === 'women') {
                const b = this.basesWomen.find(x => x.id === this.builder.womenBase);
                total += b ? b.price : 90;
                total += this.getWomenSizeSurcharge();
                total += this.getHairLengthSurchargeFor(this.builder.womenHairLength);
                if (this.builder.womenAttachment === 'clips_tape') total += 25;
                else if (this.builder.womenAttachment === 'halo') total += 30;
                else if (this.builder.womenAttachment === 'poly_bond') total += 35;
                if (this.builder.womenStyling) total += 160;
                if (this.builder.womenConcealedKnots) total += 30;
                if (this.builder.womenVelvetBand) total += 25;
                if (this.builder.womenExtraClips) total += 20;
            } else if (this.builder.profile === 'full') {
                const b = this.basesFull.find(x => x.id === this.builder.fullBase);
                total += b ? b.price : 120;
                if (this.builder.fullCapSize === 'large') total += 30;
                if (this.builder.fullCustomMold) total += 50;
                total += this.getHairLengthSurchargeFor(this.builder.fullHairLength);
                if (this.builder.fullSecurity === 'silicone_pads') total += 40;
                else if (this.builder.fullSecurity === 'clips_straps') total += 25;
                if (this.builder.fullBleached360) total += 40;
                if (this.builder.fullSalonFit) total += 190;
                if (this.builder.fullMedicalScalp) total += 95;
                if (this.builder.fullCareKit) total += 55;
            }
            return total;
        },

        formatCurrency(amount) {
            if (this.currency === 'USD') return '$' + Math.round(amount * 0.66) + ' USD';
            if (this.currency === 'KRW') return '₩' + (Math.round(amount * 890)).toLocaleString() + ' KRW';
            return '$' + amount + ' AUD';
        },

        nextStep() {
            if (this.currentStep < 6) this.currentStep++;
        },

        prevStep() {
            if (this.currentStep > 1) this.currentStep--;
        },

        orderStockItem(item) {
            this.openStockDetailModal(item);
        },

        bookStockFit(item) {
            this.booking.service = 'Collecting Order and Fitting (45 mins)';
            this.booking.notes = `Stock item fitting request: ${item.name} (${item.sku})`;
            this.isBookingModalOpen = true;
            this.fetchMonthStatus();
        },

        get calendarMonthLabel() {
            const d = new Date(this.calendarYear, this.calendarMonth - 1, 1);
            return d.toLocaleString('en-US', { month: 'long', year: 'numeric' });
        },

        get calendarDays() {
            const year = this.calendarYear;
            const month = this.calendarMonth;
            const firstDayIndex = new Date(year, month - 1, 1).getDay(); // 0 for Sun
            const totalDays = new Date(year, month, 0).getDate();
            const days = [];

            for (let i = 0; i < firstDayIndex; i++) {
                days.push({ day: null, dateStr: '', isPast: true, isClosed: false, closedReason: '', dot: null });
            }

            const todayStr = this.getMelbourneDateStr();

            for (let day = 1; day <= totalDays; day++) {
                const mStr = String(month).padStart(2, '0');
                const dStr = String(day).padStart(2, '0');
                const dateStr = `${year}-${mStr}-${dStr}`;
                const isPast = dateStr < todayStr;
                const statusInfo = this.calendarMonthStatus[dateStr];
                const isClosed = !isPast && !!(statusInfo && statusInfo.is_closed);
                const closedReason = (statusInfo && statusInfo.closed_reason) || '';

                let dot = null;
                if (!isPast) {
                    if (isClosed) {
                        dot = 'gray';
                    } else if (statusInfo && statusInfo.dot) {
                        dot = statusInfo.dot; // 'green', 'yellow', 'red'
                    } else {
                        dot = 'green'; // Available
                    }
                }
                days.push({ day, dateStr, isPast, isClosed, closedReason, dot });
            }
            return days;
        },

        async changeCalendarMonth(delta) {
            let newM = this.calendarMonth + delta;
            let newY = this.calendarYear;
            if (newM < 1) {
                newM = 12;
                newY -= 1;
            } else if (newM > 12) {
                newM = 1;
                newY += 1;
            }
            this.calendarMonth = newM;
            this.calendarYear = newY;
            await this.fetchMonthStatus();
        },

        async fetchMonthStatus() {
            try {
                const monthStr = `${this.calendarYear}-${String(this.calendarMonth).padStart(2, '0')}`;
                const res = await fetch(`/api_booking.php?action=get_month_status&month=${monthStr}`);
                const data = await res.json();
                if (data.status === 'success') {
                    this.calendarMonthStatus = data.data || {};
                    this.calendarRegularOffDays = data.regular_off_days || [0];
                    this.calendarClosurePeriods = data.closure_periods || [];
                }
            } catch (e) {
                console.error('Failed to load month status:', e);
            }
        },

        async selectCalendarDate(dateStr, isPast, isClosed) {
            if (isPast || isClosed) return;
            this.booking.date = dateStr;
            this.booking.time = '';
            await this.fetchDaySlots(dateStr);
        },

        async fetchDaySlots(dateStr) {
            try {
                const res = await fetch(`/api_booking.php?action=get_day_slots&date=${dateStr}`);
                const data = await res.json();
                let rawSlots = [];
                if (data.status === 'success') {
                    this.selectedDateIsClosed = !!data.is_closed;
                    this.selectedDateClosedReason = data.closed_reason || '';
                    rawSlots = data.slots || [];
                } else {
                    this.selectedDateIsClosed = false;
                    this.selectedDateClosedReason = '';
                    rawSlots = [
                        { time: '11:00 AM', available: true },
                        { time: '12:00 PM', available: true },
                        { time: '01:00 PM', available: true },
                        { time: '02:00 PM', available: true },
                        { time: '03:00 PM', available: true },
                        { time: '04:00 PM', available: true },
                        { time: '05:00 PM', available: true }
                    ];
                }
                this.selectedDateSlots = this.filterTodaySlots(dateStr, rawSlots);
            } catch (e) {
                console.error('Failed to load day slots:', e);
            }
        },

        filterTodaySlots(dateStr, slots) {
            if (!slots || !Array.isArray(slots)) return [];
            const todayStr = this.getMelbourneDateStr();
            if (dateStr !== todayStr) {
                return slots;
            }
            const nowMinutes = this.getMelbourneCurrentMinutes();
            return slots.filter(slot => {
                const slotMinutes = this.parseSlotTimeToMinutes(slot.time);
                // Do not show available times that are earlier than or equal to current time today
                return slotMinutes > nowMinutes;
            });
        },

        getMelbourneDateStr() {
            try {
                const nf = new Intl.DateTimeFormat('en-CA', {
                    timeZone: 'Australia/Melbourne',
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit'
                });
                return nf.format(new Date());
            } catch (e) {
                const d = new Date();
                return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
            }
        },

        getMelbourneCurrentMinutes() {
            try {
                const parts = new Intl.DateTimeFormat('en-US', {
                    timeZone: 'Australia/Melbourne',
                    hour: 'numeric',
                    minute: 'numeric',
                    hourCycle: 'h23'
                }).formatToParts(new Date());
                let h = 0, m = 0;
                for (const p of parts) {
                    if (p.type === 'hour') h = parseInt(p.value, 10);
                    if (p.type === 'minute') m = parseInt(p.value, 10);
                }
                return h * 60 + m;
            } catch (e) {
                const d = new Date();
                return d.getHours() * 60 + d.getMinutes();
            }
        },

        parseSlotTimeToMinutes(timeStr) {
            if (!timeStr) return 0;
            const match = String(timeStr).match(/^(\d{1,2}):(\d{2})\s*(AM|PM)$/i);
            if (!match) return 0;
            let h = parseInt(match[1], 10);
            const m = parseInt(match[2], 10);
            const mer = match[3].toUpperCase();
            if (mer === 'PM' && h < 12) h += 12;
            if (mer === 'AM' && h === 12) h = 0;
            return h * 60 + m;
        },

        async submitBooking() {
            if (!this.booking.name || !this.booking.phone || !this.booking.date || !this.booking.time) {
                alert('Please fill in your name, contact phone, date, and preferred time.');
                return;
            }
            if (this.selectedDateIsClosed) {
                alert('Selected date is closed for bookings. Please choose an alternate date.');
                return;
            }
            this.isBookingSubmitting = true;
            try {
                const formData = new FormData();
                formData.append('customer_name', this.booking.name);
                formData.append('phone', this.booking.phone);
                formData.append('service_type', this.booking.service);
                formData.append('booking_date', this.booking.date);
                formData.append('booking_time', this.booking.time);
                formData.append('notes', this.booking.notes || '');

                const res = await fetch('/api_booking.php?action=create_booking', {
                    method: 'POST',
                    body: formData
                });
                const result = await res.json();
                if (result.status === 'success') {
                    this.bookingSuccessData = {
                        id: result.booking_id,
                        name: this.booking.name,
                        phone: this.booking.phone,
                        date: this.booking.date,
                        time: this.booking.time,
                        service: this.booking.service
                    };
                    await this.fetchMonthStatus();
                    await this.fetchDaySlots(this.booking.date);
                } else {
                    alert(result.message || 'Booking submission failed. Please try again.');
                }
            } catch (e) {
                alert('Server error occurred during booking. Please try again.');
            } finally {
                this.isBookingSubmitting = false;
            }
        },

        resetBookingForm() {
            this.bookingSuccessData = null;
            this.booking = {
                service: 'Free Consultation (15 mins)',
                date: '',
                time: '',
                name: '',
                phone: '',
                notes: ''
            };
            this.selectedDateSlots = [];
            this.selectedDateIsClosed = false;
            this.selectedDateClosedReason = '';
            this.fetchMonthStatus();
        },

        openBookingModal(serviceName) {
            this.isFfaModalOpen = false;
            this.isDiagModalOpen = false;
            this.isCalculatorModalOpen = false;
            this.isComparisonModalOpen = false;
            if (serviceName && this.fittingServices && this.fittingServices.some(s => s.value === serviceName)) {
                this.booking.service = serviceName;
            } else if (!this.booking.service || (this.fittingServices && !this.fittingServices.some(s => s.value === this.booking.service))) {
                this.booking.service = 'Free Consultation (15 mins)';
            }
            this.isBookingModalOpen = true;
            this.fetchMonthStatus();
        },

        getStockBadgeClass(badge) {
            switch (badge) {
                case 'New':
                    return 'bg-cyan-500 text-slate-950 border-cyan-300 shadow-cyan-500/30';
                case 'Best Seller':
                    return 'bg-gradient-to-r from-amber-400 to-amber-500 text-slate-950 border-amber-300 shadow-amber-500/30';
                case "MD's Pick":
                    return 'bg-purple-600 text-white border-purple-400 shadow-purple-600/30';
                case 'Limited':
                    return 'bg-rose-600 text-white border-rose-400 shadow-rose-600/30 animate-pulse';
                case 'Promotion':
                    return 'bg-emerald-500 text-slate-950 border-emerald-300 shadow-emerald-500/30';
                default:
                    return 'bg-slate-800 text-slate-200 border-slate-700';
            }
        },

        getStockBadgeIcon(badge) {
            switch (badge) {
                case 'New':
                    return 'fa-solid fa-sparkles text-slate-950';
                case 'Best Seller':
                    return 'fa-solid fa-fire text-amber-950';
                case "MD's Pick":
                    return 'fa-solid fa-crown text-amber-300';
                case 'Limited':
                    return 'fa-solid fa-bolt text-yellow-300';
                case 'Promotion':
                    return 'fa-solid fa-tag text-emerald-950';
                default:
                    return 'fa-solid fa-star';
            }
        },

        getStockImageUrl(img) {
            if (!img) return 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=600&q=80';
            if (img.startsWith('http://') || img.startsWith('https://')) return img;
            if (window.location.protocol === 'file:' || window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                return 'https://davidhair.atozhair.shop' + (img.startsWith('/') ? img : '/' + img);
            }
            return img;
        },

        openB2BModal(tierName) {
            this.b2bForm.selectedTier = tierName;
            this.isB2bModalOpen = true;
        },

        submitOrder() {
            const b = this.builder;
            if (!b.customerName || !b.customerPhone) {
                alert('Please enter your Name and Phone / WhatsApp number so our specialist can reach you.');
                return;
            }

            const profName = this.getProfileName(b.profile);
            const totalEst = this.formatCurrency(this.calculateTotal());
            let specDetails = `Custom Hairpiece Specification:\n`;
            specDetails += `• System Type: ${profName}\n`;

            if (b.profile === 'men') {
                specDetails += `• Base: ${this.getBaseName(b.menBase)}\n`;
                specDetails += `• Size: ${b.menSizePreset === 'custom' ? (b.menWidth + 'x' + b.menLength + ' ' + b.menUnit) : (b.menSizePreset + ' Preset')}\n`;
                specDetails += `• Hair Length: ${b.menHairLength}"\n`;
                specDetails += `• Density: ${b.menDensity}% (${this.getDensityLabel(b.menDensity)})\n`;
                specDetails += `• Texture: ${b.menWave}\n`;
                specDetails += `• Color: ${b.menColor} (${b.menGreyPercent}% Grey)\n`;
                specDetails += `• Direction: ${b.menDirection}\n`;
                const services = [];
                if (b.menBleachedKnots) services.push('Bleached Knots Front (+$20)');
                if (b.menScallopedFront) services.push('Scalloped Front (+$25)');
                if (b.menUnderVentilation) services.push('Under-ventilation (+$20)');
                if (b.menPrecisionCutIn) services.push('Melbourne Barber Cut-in (+$145)');
                if (b.menBondService) services.push('4-6 Wk Medical Bond (+$80)');
                if (b.menClipAndTape) services.push('Clip & Tape Kit (+$30)');
                if (services.length) specDetails += `• Services: ${services.join(', ')}\n`;
            } else if (b.profile === 'women') {
                specDetails += `• Base: ${this.getBaseName(b.womenBase)}\n`;
                specDetails += `• Coverage Size: ${b.womenSizePreset === 'custom' ? (b.womenWidth + 'x' + b.womenLength + ' inch') : (b.womenSizePreset + ' inch Preset')}\n`;
                specDetails += `• Hair Length: ${b.womenHairLength}"\n`;
                specDetails += `• Density: ${b.womenDensity}% (${this.getDensityLabel(b.womenDensity)})\n`;
                specDetails += `• Texture: ${b.womenWave}\n`;
                specDetails += `• Color: ${b.womenColor}\n`;
                specDetails += `• Parting: ${b.womenParting}\n`;
                const attachMap = {
                    clips: 'Silicone Pressure Clips (Included)',
                    clips_tape: 'Hybrid Clips + Tape Tab (+$25)',
                    halo: 'Invisible Halo Wire + Clips (+$30)',
                    poly_bond: 'Perimeter PU Medical Bond (+$35)'
                };
                specDetails += `• Attachment: ${attachMap[b.womenAttachment] || b.womenAttachment}\n`;
                const services = [];
                if (b.womenStyling) services.push('Melbourne Salon Face Framing & Blowout (+$160)');
                if (b.womenConcealedKnots) services.push('Silk Concealed Knots & Baby Hairs (+$30)');
                if (b.womenVelvetBand) services.push('Soft Velvet Comfort Band (+$25)');
                if (b.womenExtraClips) services.push('Extra Clip Set (+$20)');
                if (services.length) specDetails += `• Services: ${services.join(', ')}\n`;
            } else if (b.profile === 'full') {
                specDetails += `• Base: ${this.getBaseName(b.fullBase)}\n`;
                specDetails += `• Cap Size: ${b.fullCapSize.toUpperCase()} (Circumference ~${b.fullCircumference}")\n`;
                if (b.fullCustomMold) specDetails += `• Mold: Custom 3D Anatomical Cranial Mold (+ $50 AUD)\n`;
                specDetails += `• Hair Length: ${b.fullHairLength}"\n`;
                specDetails += `• Density: ${b.fullDensity}% (${this.getDensityLabel(b.fullDensity)})\n`;
                specDetails += `• Texture: ${b.fullWave}\n`;
                specDetails += `• Color: ${b.fullColor}\n`;
                specDetails += `• Parting: ${b.fullParting}\n`;
                const secMap = {
                    straps: 'Adjustable Elastic Straps (Included)',
                    silicone_pads: 'Medical Anti-Slip Silicone Pads (+$40)',
                    clips_straps: 'Comb Clips + Elastic Straps (+$25)'
                };
                specDetails += `• Security: ${secMap[b.fullSecurity] || b.fullSecurity}\n`;
                const services = [];
                if (b.fullBleached360) services.push('360° Bleached Knots (+$40)');
                if (b.fullSalonFit) services.push('Custom Head Fit & Master Cut-in (+$190)');
                if (b.fullMedicalScalp) services.push('Sensitive Scalp Treatment & Fit (+$95)');
                if (b.fullCareKit) services.push('Deluxe Care & Maintenance Kit (+$55)');
                if (services.length) specDetails += `• Services: ${services.join(', ')}\n`;
            }

            specDetails += `• Live Estimate: ${totalEst}\n`;
            specDetails += `• Customer: ${b.customerName} (${b.customerPhone})`;

            const message = `✨ Custom Order Inquiry Submitted! ✨\n\nThank you, ${b.customerName}!\nYour bespoke specification for [${profName}] has been recorded.\n\nEstimated Total: ${totalEst}\n\nOur Melbourne Springvale Specialist will contact you at ${b.customerPhone} shortly to confirm template fitting and factory production.`;
            
            alert(message);
        },

        submitB2BForm() {
            alert(`B2B Wholesale Application received for ${this.b2bForm.company} (${this.b2bForm.selectedTier}). Our Factory Export Manager will send trial catalog terms to ${this.b2bForm.phone} shortly.`);
            this.isB2bModalOpen = false;
        },

        // VIP Membership Methods
        async checkMemberSession() {
            try {
                const res = await fetch('/api_members.php?action=me&t=' + Date.now());
                const data = await res.json();
                if (data.status === 'authenticated' && data.member) {
                    this.currentMember = data.member;
                } else {
                    this.currentMember = null;
                }
            } catch (e) {
                this.currentMember = null;
            }
        },

        openSignInModal() {
            this.signInForm.error = '';
            this.isSignInModalOpen = true;
        },

        openSignUpModal() {
            this.signUpForm.error = '';
            this.isSignUpModalOpen = true;
        },

        async openMemberProfileModal() {
            this.isMemberProfileModalOpen = true;
            await this.loadMemberRecords();
        },

        async memberLogin() {
            this.signInForm.loading = true;
            this.signInForm.error = '';
            try {
                const formData = new FormData();
                formData.append('email', this.signInForm.email);
                formData.append('password', this.signInForm.password);

                const res = await fetch('/api_members.php?action=login', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (data.status === 'success') {
                    this.currentMember = data.member;
                    this.isSignInModalOpen = false;
                    this.signInForm.password = '';
                    alert(`Welcome back, ${data.member.full_name}! (Tier: ${data.member.tier})`);
                } else {
                    this.signInForm.error = data.message || 'Invalid email or password.';
                }
            } catch (err) {
                this.signInForm.error = 'Network error during sign in.';
            } finally {
                this.signInForm.loading = false;
            }
        },

        async memberRegister() {
            this.signUpForm.loading = true;
            this.signUpForm.error = '';
            try {
                const formData = new FormData();
                formData.append('full_name', this.signUpForm.full_name);
                formData.append('email', this.signUpForm.email);
                formData.append('password', this.signUpForm.password);
                formData.append('phone', this.signUpForm.phone);
                formData.append('hair_system_type', this.signUpForm.hair_system_type);
                formData.append('referral_code', this.signUpForm.referral_code || '');

                const res = await fetch('/api_members.php?action=register', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (data.status === 'success') {
                    this.currentMember = data.member;
                    this.isSignUpModalOpen = false;
                    this.signUpForm.password = '';
                    alert(`🎉 Welcome to David Hair VIP, ${data.member.full_name}! 100 Loyalty Points have been added to your balance.`);
                } else {
                    this.signUpForm.error = data.message || 'Registration failed.';
                }
            } catch (err) {
                this.signUpForm.error = 'Network error during registration.';
            } finally {
                this.signUpForm.loading = false;
            }
        },

        async memberLogout() {
            try {
                await fetch('/api_members.php?action=logout');
            } catch (e) {}
            this.currentMember = null;
            this.isMemberProfileModalOpen = false;
            this.memberRecords = [];
        },

        copyReferralCode() {
            if (!this.currentMember || !this.currentMember.referral_code) return;
            navigator.clipboard.writeText(this.currentMember.referral_code).then(() => {
                this.copiedReferral = true;
                setTimeout(() => { this.copiedReferral = false; }, 2500);
            }).catch(() => {
                prompt('Copy your referral code:', this.currentMember.referral_code);
            });
        },

        async loadMemberRecords() {
            if (!this.currentMember) return;
            this.isLoadingMemberRecords = true;
            try {
                const res = await fetch('/api_members.php?action=records&member_id=' + this.currentMember.id + '&t=' + Date.now());
                const data = await res.json();
                if (data.status === 'success') {
                    this.memberRecords = data.data || [];
                }
            } catch (e) {
                console.error('Error loading member records:', e);
            } finally {
                this.isLoadingMemberRecords = false;
            }
        },

        getTierNextThresholdText() {
            if (!this.currentMember) return '';
            const spent = parseFloat(this.currentMember.total_spent || 0);
            if (spent < 500) {
                const diff = (500 - spent).toFixed(2);
                return `${diff} AUD to Silver Tier`;
            } else if (spent < 1500) {
                const diff = (1500 - spent).toFixed(2);
                return `${diff} AUD to Gold Tier`;
            } else if (spent < 3000) {
                const diff = (3000 - spent).toFixed(2);
                return `${diff} AUD to VIP Partner`;
            } else {
                return 'Top VIP Partner Achieved';
            }
        },

        getTierProgressPercent() {
            if (!this.currentMember) return 0;
            const spent = parseFloat(this.currentMember.total_spent || 0);
            if (spent >= 3000) return 100;
            if (spent >= 1500) return 66 + ((spent - 1500) / 1500) * 34;
            if (spent >= 500) return 33 + ((spent - 500) / 1000) * 33;
            return (spent / 500) * 33;
        }
    }
}

