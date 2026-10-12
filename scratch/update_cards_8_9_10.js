const fs = require('fs');
const path = require('path');

const cardConfigs = [
  {
    name: '8. Tape-in hair Extension',
    commentMatch: '<!-- 8. Tape-in hair Extension -->',
    img1: 'videos/tape1.jpg',
    alt1: 'Tape-In Hair Extension - Seamless Invisiblend',
    img2: 'videos/tape2.jpeg',
    alt2: 'Tape-In Hair Extension - Flat Polymer Skin Tabs'
  },
  {
    name: '9. Fusion hair extensions',
    commentMatch: '<!-- 9. Fusion hair extensions -->',
    img1: 'videos/fusion1.jpeg',
    alt1: 'Fusion Hair Extensions - Keratin Bond Tips',
    img2: 'videos/fusion2.jpeg',
    alt2: 'Fusion Hair Extensions - 360 Degree Styling'
  },
  {
    name: '10. Weaving hair Extension',
    commentMatch: '<!-- 10. Weaving hair Extension -->',
    img1: 'videos/weaving.jpeg',
    alt1: 'Weaving Hair Extension - Traditional Weft Method',
    img2: 'videos/weaving1.jpeg',
    alt2: 'Weaving Hair Extension - Safe Fast Installation'
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
