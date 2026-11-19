<?php
$pageTitle       = 'Hair System, Toupee & Topper FAQ Melbourne | David Hair';
$pageDescription = "Answers to the most common questions about men's toupees, women's hair toppers, swimming, gym, lifespan, pricing ($420 AUD+), private health rebates & Springvale fitting.";
$pageKeywords    = 'hair system FAQ melbourne, how long does a toupee last, can you swim in a hair system, hair system cost melbourne';
$canonicalPath   = '/faq';
$activePage      = 'faq';
$extraHeadScripts = [];
$extraJsonLd = <<<'JSONLD'
{
                "@context": "https://schema.org",
        "@type": "FAQPage",
                "@id": "https://davidhairshop.com/#faq",
                "mainEntity": [
                    {
                        "@type": "Question",
                        "name": "What is a women's hair topper and how does it restore crown thinning?",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "A women's hair topper (or top piece) is a specialized non-surgical human hair enhancement designed specifically for female pattern hair loss, crown thinning, and widening parts. Hand-crafted on an ultra-breathable silk top or monofilament base, individual hair strands are ventilated to replicate natural scalp growth, integrating seamlessly with your bio hair using discreet pressure-sensitive micro-clips or medical bonding."
                        }
                    },
                    {
                        "@type": "Question",
                        "name": "How does a men's toupee compare to hair transplants or surgical procedures?",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "Unlike surgical hair transplants which require 6-12 months of recovery, carry scarring risks, and depend on limited donor hair density, a modern non-surgical men's toupee provides instantaneous, guaranteed full density with zero downtime or pain. With 0.03mm skin or Swiss lace bases, you can achieve an undetectable hairline styled to any look immediately."
                        }
                    },
                    {
                        "@type": "Question",
                        "name": "How natural and undetectable does a David Hair toupee look?",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "Our hair systems use 0.03mm ultra-thin skin membranes and Swiss lace bases with 100% Remy human hair and knotless V-loop ventilation. The hairline is completely invisible, looking as if the hair is growing directly out of your scalp even under direct sunlight or when slicked back."
                        }
                    },
                    {
                        "@type": "Question",
                        "name": "Can I shower, swim, work out, and live an active lifestyle with a hair system?",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "Absolutely! We use medical-grade, hypoallergenic, waterproof bonding adhesives and tapes specifically engineered for the Australian climate. You can shower, wash your hair, swim in open water, and do intense gym workouts with total security and confidence."
                        }
                    },
                    {
                        "@type": "Question",
                        "name": "How much does a hair system cost in Melbourne compared to regular salons?",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "While typical Australian hair clinics and salons charge between $1,500 and $2,500+ AUD, David Hair eliminates all middleman markups with our direct factory model. Our premium custom human hair systems start from just $420 to $460 AUD with full price transparency."
                        }
                    },
                    {
                        "@type": "Question",
                        "name": "Where is the David Hair Melbourne salon located and is the fitting private?",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "Our flagship studio is located at Shop F16 (Level 1), Buckingham Ave, Springvale, VIC 3171. Consultations and fittings are conducted 1-on-1 in 100% private suites with complete discretion and confidentiality."
                        }
                    },
                    {
                        "@type": "Question",
                        "name": "How long does a human hair system last and what maintenance is required?",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "Depending on the base material (ultra-thin skin vs durable lace/hybrid), a system lasts between 4 to 12 months with proper care. Professional rebonding and maintenance is typically recommended every 4 to 6 weeks, and we also provide DIY home care coaching and supplies."
                        }
                    }
                ]
            }
JSONLD;
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/subpage_bar.php';
?>
<main id="main-content" class="w-full min-h-screen">
<?php dh_render_subpage_top('Frequently Asked Questions', 'Q&A GUIDE'); ?>
    <section id="faq" x-data="{ activeFaq: 1 }" class="relative py-10 sm:py-16 bg-brand-darkBg">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Header -->
            <div class="text-center space-y-3 sm:space-y-4 max-w-3xl mx-auto mb-12 sm:mb-16">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-teal-500/15 border border-teal-400/30 text-teal-300 text-xs font-bold uppercase tracking-wider shadow-sm">
                    <i class="fa-solid fa-circle-question text-teal-400"></i>
                    <span>Got Questions? We Have Answers</span>
                </div>
                <h2 class="font-heading text-2xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight">
                    Frequently Asked <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-sky-400 to-emerald-400">Questions</span>
                </h2>
                <p class="text-xs sm:text-sm lg:text-base text-slate-300 leading-relaxed">
                    Everything you need to know about our factory-direct men's toupees, invisible hairlines, active lifestyle durability, and our private Springvale fitting studio.
                </p>
            </div>

            <!-- FAQ Accordion List -->
            <div class="space-y-3.5 sm:space-y-4">
                <!-- Item 1 -->
                <div class="bg-brand-cardBg/90 border border-brand-border rounded-2xl overflow-hidden transition-all duration-200"
                    :class="activeFaq === 1 ? 'border-teal-500/50 shadow-lg shadow-teal-950/30 ring-1 ring-teal-500/30' : 'hover:border-slate-600'">
                    <button type="button" @click="activeFaq = (activeFaq === 1 ? null : 1)"
                        class="w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between gap-4 text-left cursor-pointer">
                        <span class="font-heading font-bold text-sm sm:text-base text-white flex items-center gap-3">
                            <span class="w-7 h-7 rounded-lg bg-teal-500/20 text-teal-300 text-xs font-black flex items-center justify-center shrink-0">01</span>
                            <span>How natural and undetectable does a David Hair toupee look?</span>
                        </span>
                        <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-300"
                            :class="activeFaq === 1 ? 'rotate-180 text-teal-400' : ''"></i>
                    </button>
                    <div x-show="activeFaq === 1" x-cloak
                        class="px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-slate-300 leading-relaxed border-t border-brand-border/40">
                        Our hair systems are crafted with ultra-thin 0.03mm polyurethane skin membranes or breathable Swiss lace bases paired with 100% Remy human hair and knotless V-loop ventilation. The transition between your scalp and the hairpiece is completely undetectable, creating an invisible hairline where hair looks like it grows directly from your scalp, even when exposed to bright light or slicked back.
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="bg-brand-cardBg/90 border border-brand-border rounded-2xl overflow-hidden transition-all duration-200"
                    :class="activeFaq === 2 ? 'border-teal-500/50 shadow-lg shadow-teal-950/30 ring-1 ring-teal-500/30' : 'hover:border-slate-600'">
                    <button type="button" @click="activeFaq = (activeFaq === 2 ? null : 2)"
                        class="w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between gap-4 text-left cursor-pointer">
                        <span class="font-heading font-bold text-sm sm:text-base text-white flex items-center gap-3">
                            <span class="w-7 h-7 rounded-lg bg-teal-500/20 text-teal-300 text-xs font-black flex items-center justify-center shrink-0">02</span>
                            <span>Can I shower, swim, work out, and live an active lifestyle?</span>
                        </span>
                        <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-300"
                            :class="activeFaq === 2 ? 'rotate-180 text-teal-400' : ''"></i>
                    </button>
                    <div x-show="activeFaq === 2" x-cloak
                        class="px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-slate-300 leading-relaxed border-t border-brand-border/40">
                        Yes, 100%! We use medical-grade, hypoallergenic, waterproof bonding adhesives and tapes engineered specifically for the Australian climate. You can shampoo your hair in the morning, do high-intensity workouts at the gym, swim at the beach, and face strong windy days with zero anxiety or fear of lifting.
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="bg-brand-cardBg/90 border border-brand-border rounded-2xl overflow-hidden transition-all duration-200"
                    :class="activeFaq === 3 ? 'border-teal-500/50 shadow-lg shadow-teal-950/30 ring-1 ring-teal-500/30' : 'hover:border-slate-600'">
                    <button type="button" @click="activeFaq = (activeFaq === 3 ? null : 3)"
                        class="w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between gap-4 text-left cursor-pointer">
                        <span class="font-heading font-bold text-sm sm:text-base text-white flex items-center gap-3">
                            <span class="w-7 h-7 rounded-lg bg-teal-500/20 text-teal-300 text-xs font-black flex items-center justify-center shrink-0">03</span>
                            <span>How much does a hair system cost in Melbourne compared to regular salons?</span>
                        </span>
                        <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-300"
                            :class="activeFaq === 3 ? 'rotate-180 text-teal-400' : ''"></i>
                    </button>
                    <div x-show="activeFaq === 3" x-cloak
                        class="px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-slate-300 leading-relaxed border-t border-brand-border/40">
                        Typical Australian hair loss clinics and bespoke salons charge between $1,500 and $2,500+ AUD for an initial system. Because David Hair operates our own manufacturing facility with zero middleman markup, our bespoke 100% human hair systems start from only <strong class="text-teal-300">$420 to $460 AUD</strong>—saving you up to 70% with complete price transparency.
                    </div>
                </div>

                <!-- Item 4 -->
                <div class="bg-brand-cardBg/90 border border-brand-border rounded-2xl overflow-hidden transition-all duration-200"
                    :class="activeFaq === 4 ? 'border-teal-500/50 shadow-lg shadow-teal-950/30 ring-1 ring-teal-500/30' : 'hover:border-slate-600'">
                    <button type="button" @click="activeFaq = (activeFaq === 4 ? null : 4)"
                        class="w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between gap-4 text-left cursor-pointer">
                        <span class="font-heading font-bold text-sm sm:text-base text-white flex items-center gap-3">
                            <span class="w-7 h-7 rounded-lg bg-teal-500/20 text-teal-300 text-xs font-black flex items-center justify-center shrink-0">04</span>
                            <span>Where is the David Hair Melbourne studio located and is the fitting private?</span>
                        </span>
                        <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-300"
                            :class="activeFaq === 4 ? 'rotate-180 text-teal-400' : ''"></i>
                    </button>
                    <div x-show="activeFaq === 4" x-cloak
                        class="px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-slate-300 leading-relaxed border-t border-brand-border/40">
                        Our flagship studio is located at <button type="button" @click="openMapModal()" class="text-teal-300 hover:text-teal-200 font-bold underline inline-flex items-center gap-1 cursor-pointer">Shop F16 (Level 1), Buckingham Ave, Springvale, VIC 3171 <i class="fa-solid fa-map-location-dot text-xs"></i></button> (conveniently accessible from Clayton, Glen Waverley, Dandenong, Chadstone, and Melbourne CBD). All consultations, head measurements, and fitting sessions are carried out in 100% private 1-on-1 suites with complete discretion and confidentiality.
                    </div>
                </div>

                <!-- Item 5 -->
                <div class="bg-brand-cardBg/90 border border-brand-border rounded-2xl overflow-hidden transition-all duration-200"
                    :class="activeFaq === 5 ? 'border-teal-500/50 shadow-lg shadow-teal-950/30 ring-1 ring-teal-500/30' : 'hover:border-slate-600'">
                    <button type="button" @click="activeFaq = (activeFaq === 5 ? null : 5)"
                        class="w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between gap-4 text-left cursor-pointer">
                        <span class="font-heading font-bold text-sm sm:text-base text-white flex items-center gap-3">
                            <span class="w-7 h-7 rounded-lg bg-teal-500/20 text-teal-300 text-xs font-black flex items-center justify-center shrink-0">05</span>
                            <span>How long does a hairpiece last and how is it maintained?</span>
                        </span>
                        <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-300"
                            :class="activeFaq === 5 ? 'rotate-180 text-teal-400' : ''"></i>
                    </button>
                    <div x-show="activeFaq === 5" x-cloak
                        class="px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-slate-300 leading-relaxed border-t border-brand-border/40">
                        Depending on the base thickness (0.03mm ultra-thin skin lasts ~3–5 months, Swiss lace &amp; hybrid bases last 6–12 months), a unit provides lasting everyday performance. Maintenance (detaching, scalp cleanse, fresh tape/adhesive rebonding) is recommended every 4 to 6 weeks at our Springvale studio (from $99 AUD), or you can learn self-maintenance with our 1-on-1 coaching and home kits.
                    </div>
                </div>
            </div>

            <!-- Still Have Questions CTA Banner -->
            <div class="mt-12 sm:mt-16 bg-gradient-to-r from-teal-900/30 via-brand-cardBg to-sky-900/30 border border-teal-500/40 rounded-3xl p-6 sm:p-8 text-center space-y-4 shadow-xl">
                <div class="w-12 h-12 rounded-2xl bg-teal-500/20 text-teal-300 border border-teal-400/40 flex items-center justify-center mx-auto text-xl shadow-sm">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <h3 class="font-heading font-extrabold text-lg sm:text-2xl text-white">
                    Still Have Questions About Hair Systems?
                </h3>
                <p class="text-xs sm:text-sm text-slate-300 max-w-xl mx-auto leading-relaxed">
                    Speak directly with our Melbourne hair replacement specialist. Get confidential advice, base recommendations, or book your free $0 in-person consultation today.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                    <button type="button" @click="isBookingModalOpen = true"
                        class="w-full sm:w-auto teal-gradient hover:opacity-95 text-white font-bold text-xs sm:text-sm uppercase tracking-wider px-6 sm:px-8 py-3.5 rounded-xl shadow-lg transition-all min-h-[44px] flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-calendar-check text-xs"></i>
                        <span>Book $0 Private Fitting</span>
                    </button>
                    <a href="https://wa.me/61433484882?text=Hi%20David%20Hair%2C%20I%20have%20a%20question%20about%20your%20hair%20systems%20and%20private%20fitting."
                        target="_blank" rel="noopener"
                        class="w-full sm:w-auto bg-brand-inner hover:bg-slate-800 border border-brand-border text-slate-200 hover:text-white font-bold text-xs sm:text-sm uppercase tracking-wider px-6 sm:px-8 py-3.5 rounded-xl transition-all min-h-[44px] flex items-center justify-center gap-2">
                        <i class="fa-brands fa-whatsapp text-emerald-400 text-sm"></i>
                        <span>Chat on WhatsApp (+61 433 484 882)</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

<?php dh_render_subpage_bottom('faq'); ?>
</main>

<?php
require __DIR__ . '/includes/global_modals.php';
require __DIR__ . '/includes/footer.php';
?>
