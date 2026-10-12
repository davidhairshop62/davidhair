const fs = require('fs');
const path = require('path');

const cardConfigs = [
  {
    name: '2. Synthetic Hair Wig',
    commentMatch: '<!-- 2. Synthetic Hair Wig -->',
    img1: 'videos/synwig1.png',
    alt1: 'Synthetic Hair Wig - Ready to Wear',
    img2: 'videos/synwig2.png',
    alt2: 'Synthetic Hair Wig - Shake & Wear Style'
  },
  {
    name: '3. Pin point Hair Link',
    commentMatch: '<!-- 3. Pin point Hair Link -->',
    img1: 'videos/pin1.jpeg',
    alt1: 'Pin Point Hair Link - Strand-by-Strand Technique',
    img2: 'videos/pin2.png',
    alt2: 'Pin Point Hair Link - Micro-Link Non-Surgical Method'
  },
  {
    name: '4. Mini Hair Filler',
    commentMatch: '<!-- 4. Mini Hair Filler -->',
    img1: 'videos/mini1.jpeg',
    alt1: 'Mini Hair Filler - Featherweight Natural Volume',
    img2: 'videos/mini2.jpg',
    alt2: 'Mini Hair Filler - Micro-Clip Crown Coverage'
  },
  {
    name: '5. Topper',
    commentMatch: '<!-- 5. Topper -->',
    img1: 'videos/top1.jpeg',
    alt1: 'Hair Topper - Crown Volume Enhancement',
    img2: 'videos/top2.jpeg',
    alt2: 'Hair Topper - Silk Parting Natural Blending'
  },
  {
    name: '6. Hair Contacts',
    commentMatch: '<!-- 6. Hair Contacts -->',
    img1: 'videos/skin1.jpeg',
    alt1: 'Hair Contacts - 0.02mm Silicon Skin Base',
    img2: 'videos/skin2.jpeg',
    alt2: 'Hair Contacts - Bald Patch Seamless Blending'
  },
  {
    name: "7. Men's hair system",
    commentMatch: "<!-- 7. Men's hair system -->",
    img1: 'videos/toupee1.jpeg',
    alt1: "Men's Hair System - Bespoke Toupee Transformation",
    img2: 'videos/toupee2.jpeg',
    alt2: "Men's Hair System - Undetectable Hairline Fit"
  }
];

function buildCarouselHtml(cfg) {
  return `                    <div class="space-y-4">
                        <!-- 1:1 Aspect Ratio Image Carousel (Auto-playing) -->
                        <div x-data="{
                                currentSlide: 0,
                                totalSlides: 2,
                                timer: null,
                                touchStartX: 0,
                                startAutoPlay() {
                                    this.stopAutoPlay();
                                    this.timer = setInterval(() => { this.next() }, 3500);
                                },
                                stopAutoPlay() {
                                    if (this.timer) { clearInterval(this.timer); this.timer = null; }
                                },
                                next() { this.currentSlide = (this.currentSlide + 1) % this.totalSlides },
                                prev() { this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides },
                                goTo(idx) { this.currentSlide = idx; this.startAutoPlay(); }
                            }"
                            x-init="startAutoPlay()"
                            @mouseenter="stopAutoPlay()"
                            @mouseleave="startAutoPlay()"
                            @touchstart="stopAutoPlay(); touchStartX = $event.changedTouches[0].screenX"
                            @touchend="if ($event.changedTouches[0].screenX - touchStartX > 40) prev(); else if (touchStartX - $event.changedTouches[0].screenX > 40) next(); startAutoPlay();"
                            class="relative w-full aspect-square rounded-xl overflow-hidden bg-slate-950 border border-brand-border/70 group/carousel select-none shadow-md">
                            
                            <!-- Slide Track (1:1 Ratio) -->
                            <div class="flex h-full w-full transition-transform duration-500 ease-out"
                                :style="'transform: translateX(-' + (currentSlide * 100) + '%)'">
                                <div class="w-full h-full flex-shrink-0 relative">
                                    <img src="${cfg.img1}" alt="${cfg.alt1}"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="w-full h-full flex-shrink-0 relative">
                                    <img src="${cfg.img2}" alt="${cfg.alt2}"
                                        class="w-full h-full object-cover">
                                </div>
                            </div>

                            <!-- Slide Counter Badge -->
                            <div class="absolute top-2.5 right-2.5 px-2 py-0.5 rounded-md bg-slate-950/70 backdrop-blur-md text-[10px] font-mono text-white/90 border border-white/10 z-10 pointer-events-none">
                                <span x-text="currentSlide + 1">1</span> / 2
                            </div>

                            <!-- Prev / Next Navigation Arrows -->
                            <button type="button" @click.stop="prev(); startAutoPlay()"
                                class="absolute left-2.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-slate-950/70 hover:bg-teal-500 text-white/90 hover:text-white flex items-center justify-center backdrop-blur-sm border border-white/15 shadow-lg transition-all opacity-80 sm:opacity-0 sm:group-hover/carousel:opacity-100 cursor-pointer z-10"
                                aria-label="Previous Slide">
                                <i class="fa-solid fa-chevron-left text-xs"></i>
                            </button>
                            <button type="button" @click.stop="next(); startAutoPlay()"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-slate-950/70 hover:bg-teal-500 text-white/90 hover:text-white flex items-center justify-center backdrop-blur-sm border border-white/15 shadow-lg transition-all opacity-80 sm:opacity-0 sm:group-hover/carousel:opacity-100 cursor-pointer z-10"
                                aria-label="Next Slide">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </button>

                            <!-- Navigation Dots Indicator -->
                            <div class="absolute bottom-2.5 left-1/2 -translate-x-1/2 flex items-center gap-1.5 z-10 bg-slate-950/60 backdrop-blur-md px-2.5 py-1 rounded-full border border-white/10">
                                <button type="button" @click.stop="goTo(0)"
                                    :class="currentSlide === 0 ? 'w-4 bg-teal-400' : 'w-1.5 bg-white/40 hover:bg-white/70'"
                                    class="h-1.5 rounded-full transition-all duration-300 cursor-pointer"
                                    aria-label="Slide 1"></button>
                                <button type="button" @click.stop="goTo(1)"
                                    :class="currentSlide === 1 ? 'w-4 bg-teal-400' : 'w-1.5 bg-white/40 hover:bg-white/70'"
                                    class="h-1.5 rounded-full transition-all duration-300 cursor-pointer"
                                    aria-label="Slide 2"></button>
                            </div>
                        </div>`;
}

function processFile(filePath) {
  let content = fs.readFileSync(filePath, 'utf-8');
  let updatedCount = 0;

  for (const cfg of cardConfigs) {
    const cardIdx = content.indexOf(cfg.commentMatch);
    if (cardIdx === -1) {
      console.warn(`Card not found: ${cfg.commentMatch} in ${filePath}`);
      continue;
    }

    // Look for <div class="space-y-3.5"> after this comment
    const spaceDivTarget = '<div class="space-y-3.5">';
    const targetIdx = content.indexOf(spaceDivTarget, cardIdx);
    if (targetIdx === -1 || targetIdx > cardIdx + 400) {
      console.warn(`space-y-3.5 not found near ${cfg.commentMatch}`);
      continue;
    }

    const replacement = buildCarouselHtml(cfg);
    content = content.substring(0, targetIdx) + replacement + content.substring(targetIdx + spaceDivTarget.length);
    updatedCount++;
    console.log(`Updated ${cfg.name} in ${filePath}`);
  }

  fs.writeFileSync(filePath, content, 'utf-8');
  console.log(`Finished ${filePath}: ${updatedCount} cards updated.`);
}

processFile(path.join(__dirname, '../index.html'));
processFile(path.join(__dirname, '../scratch/add_all_products_sections.js'));
