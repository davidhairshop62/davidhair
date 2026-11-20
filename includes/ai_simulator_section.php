    <section id="ai-simulator"
        class="py-10 sm:py-16 bg-gradient-to-b from-brand-darkBg via-[#0E1838] to-brand-darkBg border-t border-brand-border/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="aiTryOnSimulator()">
            <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-12">
                <div
                    class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-teal-950/80 border border-teal-500/40 text-brand-tealLight text-xs font-bold uppercase tracking-wider mb-3">
                    <i class="fa-solid fa-wand-magic-sparkles text-amber-400"></i> AI Powered Hair Transformation
                </div>
                <h2 class="font-heading text-2xl sm:text-4xl font-extrabold text-white">AI Virtual Hairstyle Try-On
                    Simulator</h2>
                <p class="text-brand-textMuted text-xs sm:text-base mt-2">Upload your face photo and instantly swap your
                    hairstyle to Crew Cut, Gentleman, or custom hairpiece styles before booking your Melbourne fitting.
                </p>
            </div>

            <!-- Simulator Interactive Container -->
            <div class="bg-brand-cardBg border border-brand-border rounded-2xl p-4 sm:p-7 eye-shadow space-y-5">

                <!-- TOP STEP 1 SPOTLIGHT BAR: Makes "Take Photo First" Unmistakable on Mobile & Desktop -->
                <div x-show="!isCameraActive"
                    class="rounded-2xl p-3.5 sm:p-4 border transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-3"
                    :class="!hasOwnPhoto ? 'bg-gradient-to-r from-amber-500/15 via-teal-500/15 to-sky-500/15 border-amber-400/50 shadow-lg' : 'bg-emerald-950/35 border-emerald-500/35'">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                            :class="!hasOwnPhoto ? 'bg-amber-400/20 border border-amber-300/60 text-amber-300' : 'bg-emerald-500/20 border border-emerald-400/50 text-emerald-300'">
                            <i class="fa-solid text-base" :class="!hasOwnPhoto ? 'fa-camera-retro animate-bounce' : 'fa-circle-check'"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-[10px] font-mono font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-full"
                                    :class="!hasOwnPhoto ? 'bg-amber-400 text-slate-950 animate-pulse' : 'bg-emerald-500/25 text-emerald-300 border border-emerald-400/40'"
                                    x-text="!hasOwnPhoto ? 'STEP 1 • START HERE' : 'PHOTO READY'"></span>
                                <span class="text-xs sm:text-sm font-heading font-extrabold text-white"
                                    x-text="!hasOwnPhoto ? 'Take or Upload Your Face Photo First!' : 'Your Photo Is Ready — Pick Any Style Below'"></span>
                            </div>
                            <p class="text-[11px] text-slate-300 mt-0.5"
                                x-text="!hasOwnPhoto ? 'Tap the glowing Take Photo button to preview hairstyles directly on your own face.' : 'Drag or pinch to adjust fit, or retake your photo anytime.'"></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0 w-full sm:w-auto">
                        <button @click="startCamera()" type="button"
                            :class="!hasOwnPhoto ? 'ai-take-photo-pulse text-white' : 'teal-gradient text-white border border-teal-400/40'"
                            class="relative flex-1 sm:flex-initial font-extrabold text-xs sm:text-sm px-4 sm:px-5 py-3 rounded-xl shadow-xl transition-all flex items-center justify-center gap-2 min-h-[46px] cursor-pointer active:scale-95 whitespace-nowrap">
                            <span x-show="!hasOwnPhoto" class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-200 opacity-90"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-white"></span>
                            </span>
                            <i class="fa-solid fa-camera text-base"></i>
                            <span x-text="!hasOwnPhoto ? 'Take Photo Now' : 'Retake Photo'"></span>
                        </button>
                        <div class="relative border border-brand-border hover:border-teal-400 bg-brand-darkBg/90 hover:bg-slate-800 rounded-xl px-3.5 py-3 text-xs font-bold text-slate-200 hover:text-white flex items-center justify-center gap-1.5 min-h-[46px] cursor-pointer transition-colors shrink-0">
                            <input type="file" accept="image/*" @change="handleFaceUpload($event)"
                                class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10">
                            <i class="fa-solid fa-upload text-teal-400"></i>
                            <span>Upload</span>
                        </div>
                    </div>
                </div>

                <div class="grid lg:grid-cols-12 gap-6 sm:gap-8 items-start">

                    <!-- LEFT COLUMN: 3:4 Photo Area & Before/After Comparison Result -->
                    <div class="lg:col-span-6 relative space-y-4 flex flex-col justify-center">

                        <!-- AI Processing Loader -->
                        <div x-show="isProcessing" x-cloak
                            class="absolute inset-0 z-30 bg-brand-cardBg/90 backdrop-blur-sm rounded-2xl flex flex-col items-center justify-center space-y-4">
                            <div
                                class="w-12 h-12 rounded-full border-4 border-brand-teal border-t-transparent animate-spin">
                            </div>
                            <div class="text-sm font-bold text-white animate-pulse">AI Scalp Mapping & Fitting <span
                                    class="text-brand-tealLight" x-text="selectedStyle"></span>...</div>
                            <div class="text-xs text-brand-textMuted">Superimposing hairpiece texture onto face photo
                            </div>
                        </div>

                        <!-- LIVE CAMERA CAPTURE MODE (1:1 Square Ratio with Facial Alignment Guide Overlay) -->
                        <div x-show="isCameraActive" x-cloak class="w-full flex items-center justify-center py-1">
                            <div
                                class="relative py-10 sm:py-16 w-full max-w-[420px] aspect-square mx-auto bg-black rounded-xl overflow-hidden shadow-2xl border-2 border-brand-teal flex items-center justify-center">
                                <!-- Live Video Stream -->
                                <video preload="metadata" x-ref="cameraVideo" autoplay playsinline
                                    class="w-full h-full object-cover transition-transform duration-200"
                                    :class="cameraFacingMode === 'user' ? '-scale-x-100' : ''"></video>

                                <!-- Facial Contour Alignment Mesh Guide (greenmesh.png for Men, womanMeshGreen.png for Women) -->
                                <div x-show="cameraGuideOpacity > 0" 
                                     class="absolute inset-0 pointer-events-none flex items-center justify-center overflow-hidden p-2 select-none z-10 transition-opacity">
                                    <img :src="getCameraGuideImage()"
                                         class="max-w-full max-h-full object-contain pointer-events-none select-none transition-opacity duration-200"
                                         :style="`opacity: ${cameraGuideOpacity}; filter: drop-shadow(0 0 2px rgba(0, 0, 0, 0.9));`"
                                         alt="Facial contour alignment guide">
                                </div>

                                <!-- Subtle Rule of Thirds & Symmetry Grid -->
                                <div class="absolute inset-0 pointer-events-none z-12 flex items-center justify-center">
                                    <div class="w-full h-full border border-teal-500/15 grid grid-cols-3 grid-rows-3 pointer-events-none">
                                        <div class="border-r border-b border-teal-500/15"></div>
                                        <div class="border-r border-b border-teal-500/15"></div>
                                        <div class="border-b border-teal-500/15"></div>
                                        <div class="border-r border-b border-teal-500/15"></div>
                                        <div class="border-r border-b border-teal-500/15 relative">
                                            <div class="w-3.5 h-3.5 border-t-2 border-l-2 border-amber-400 absolute top-0 left-0"></div>
                                            <div class="w-3.5 h-3.5 border-t-2 border-r-2 border-amber-400 absolute top-0 right-0"></div>
                                            <div class="w-3.5 h-3.5 border-b-2 border-l-2 border-amber-400 absolute bottom-0 left-0"></div>
                                            <div class="w-3.5 h-3.5 border-b-2 border-r-2 border-amber-400 absolute bottom-0 right-0"></div>
                                        </div>
                                        <div class="border-b border-teal-500/15"></div>
                                        <div class="border-r border-teal-500/15"></div>
                                        <div class="border-r border-teal-500/15"></div>
                                        <div></div>
                                    </div>
                                </div>

                                <!-- Top Toolbar: Switch Guide Gender & Switch Camera -->
                                <div class="absolute top-3 inset-x-3 flex items-center justify-between z-20 pointer-events-auto">
                                    <button type="button" 
                                            @click="toggleCameraGuideGender()" 
                                            class="bg-slate-900/85 backdrop-blur hover:bg-slate-800 text-teal-300 text-[11px] font-bold px-3 py-1.5 rounded-xl border border-teal-500/50 shadow flex items-center gap-1.5 active:scale-95 transition-all">
                                        <i class="fa-solid fa-venus-mars text-xs"></i>
                                        <span x-text="cameraGuideGender === 'Woman' ? 'Women Guide' : 'Men Guide'"></span>
                                    </button>

                                    <button type="button" 
                                            @click="switchCamera()" 
                                            class="bg-slate-900/85 backdrop-blur hover:bg-slate-800 text-amber-300 text-[11px] font-bold px-3 py-1.5 rounded-xl border border-amber-500/50 shadow flex items-center gap-1.5 active:scale-95 transition-all">
                                        <i class="fa-solid fa-camera-rotate text-xs"></i>
                                        <span x-text="cameraFacingMode === 'user' ? 'Front Camera' : 'Rear Camera'"></span>
                                    </button>
                                </div>

                                <!-- Center Alignment Notice Badge -->
                                <div class="absolute top-12 inset-x-3 flex justify-center z-20 pointer-events-none">
                                    <span class="bg-black/80 backdrop-blur text-teal-300 text-[10px] sm:text-[11px] font-bold px-3 py-1 rounded-full border border-teal-500/50 shadow text-center">
                                        <i class="fa-solid fa-crosshairs text-amber-400 mr-1 animate-pulse"></i>
                                        Align your face within the guide overlay (1:1 Ratio)
                                    </span>
                                </div>

                                <!-- Bottom Action Bar: Cancel, Opacity Toggle, Capture -->
                                <div class="absolute bottom-3 inset-x-3 flex items-center justify-between gap-2 z-20 pointer-events-auto">
                                    <button @click="stopCamera()" type="button"
                                        class="bg-slate-900/90 hover:bg-slate-800 text-white text-xs font-bold px-3.5 py-2.5 rounded-xl border border-slate-600 shadow-md">
                                        <i class="fa-solid fa-xmark mr-1"></i> Cancel
                                    </button>

                                    <button type="button" 
                                            @click="cycleGuideOpacity()" 
                                            class="bg-slate-900/90 hover:bg-slate-800 text-slate-200 text-xs font-bold px-3 py-2.5 rounded-xl border border-slate-600 shadow-md flex items-center gap-1.5">
                                        <i class="fa-solid fa-eye text-teal-400 text-xs"></i>
                                        <span x-text="cameraGuideOpacity === 0 ? 'Guide Off' : (cameraGuideOpacity === 1 ? 'Guide 100%' : 'Guide 50%')"></span>
                                    </button>

                                    <button @click="captureCameraPhoto()" type="button"
                                        class="ai-take-photo-pulse text-white text-xs font-extrabold px-4 py-2.5 rounded-xl shadow-2xl flex items-center gap-2 hover:scale-105 active:scale-95 transition-all cursor-pointer">
                                        <i class="fa-solid fa-camera text-sm"></i> Take Photo (1:1)
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- RESULT CARD WITH 3:4 ASPECT RATIO PHOTO BOX -->
                        <div x-show="!isCameraActive"
                            class="bg-brand-darkBg border border-brand-border rounded-2xl p-4 space-y-3 eye-shadow flex flex-col justify-between">
                            <div class="flex justify-between items-center border-b border-brand-border pb-3">
                                <div>
                                    <span class="text-xs font-bold text-white"
                                        x-text="'Result: ' + selectedStyle"></span>
                                    <div class="text-[10px] text-brand-textMuted"
                                        x-text="'Color: ' + selectedColor + ' | Ratio 1:1'"></div>
                                </div>
                                <span
                                    class="bg-teal-950 text-brand-tealLight text-[10px] font-extrabold px-2.5 py-1 rounded-full border border-teal-800 uppercase tracking-wider">1:1
                                    Ratio Fitted</span>
                            </div>

                            <!-- CONTROL TOOLBAR (OUTSIDE PHOTO CANVAS BOX) -->
                            <div class="flex flex-wrap items-center justify-between gap-2 p-2 bg-brand-inner border border-brand-border rounded-xl">
                                <div class="flex items-center gap-1.5">
                                    <button @click="activeControlTab = 'hair'" 
                                            :class="activeControlTab === 'hair' ? 'bg-brand-teal text-white border-brand-tealLight font-bold shadow-md scale-105' : 'bg-slate-800 text-slate-300 border-slate-700 opacity-80 hover:opacity-100'"
                                            class="px-3 py-1.5 rounded-lg text-xs border transition-all flex items-center gap-1.5 cursor-pointer">
                                        <i class="fa-solid fa-scissors"></i> Hairpiece Controls
                                    </button>
                                    <button @click="activeControlTab = 'photo'" 
                                            :class="activeControlTab === 'photo' ? 'bg-sky-600 text-white border-sky-400 font-bold shadow-md scale-105' : 'bg-slate-800 text-slate-300 border-slate-700 opacity-80 hover:opacity-100'"
                                            class="px-3 py-1.5 rounded-lg text-xs border transition-all flex items-center gap-1.5 cursor-pointer">
                                        <i class="fa-solid fa-image"></i> Photo Controls
                                    </button>
                                    <button @click="resetTransform()" 
                                            title="Reset Position & Scale"
                                            class="px-2.5 py-1.5 rounded-lg text-xs bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 transition-all flex items-center gap-1 cursor-pointer">
                                        <i class="fa-solid fa-rotate-left"></i> <span class="hidden sm:inline">Reset</span>
                                    </button>
                                </div>
                                
                                <div class="bg-brand-darkBg border border-brand-border text-brand-tealLight text-[11px] font-bold px-2.5 py-1 rounded-lg shadow-sm flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full" :class="activeControlTab === 'hair' ? 'bg-teal-400 animate-pulse' : 'bg-sky-400 animate-pulse'"></span>
                                    <span x-text="activeControlTab === 'hair' ? `Hair: ${hairScale}% (W:${hairWidth}% H:${hairHeight}%)` : `Photo: ${faceScale}%`"></span>
                                </div>
                            </div>

                            <!-- Photo Box (Clean Dynamic Canvas for Direct Manipulation & Before/After Slider) -->
                            <div class="w-full flex items-center justify-center my-auto py-1">
                                <div x-ref="photoCanvas"
                                     class="photo-canvas-container relative w-full max-w-[420px] max-h-[560px] mx-auto rounded-xl overflow-hidden select-none bg-slate-950 flex items-center justify-center border-2 transition-all duration-200 shadow-2xl"
                                     :class="isSliderDragging ? 'cursor-ew-resize border-teal-400 ring-2 ring-teal-400/30' : (activeControlTab === 'hair' ? 'border-brand-teal cursor-grab active:cursor-grabbing' : 'border-sky-500 cursor-grab active:cursor-grabbing')"
                                     :style="`aspect-ratio: ${photoAspect};`"
                                     @mousedown="startDrag($event, 'move')"
                                     @mousemove="onDrag($event)"
                                     @mouseup="endDrag()"
                                     @mouseleave="endDrag()"
                                     @touchstart="startDrag($event, 'move')"
                                     @touchmove="onDrag($event)"
                                     @touchend="endDrag()"
                                     @wheel.prevent="onWheel($event)"
                                     @mousemove.window="if (isSliderDragging) onSliderMove($event)"
                                     @mouseup.window="if (isSliderDragging) endSliderDrag()"
                                     @touchmove.window="if (isSliderDragging) onSliderMove($event)"
                                     @touchend.window="if (isSliderDragging) endSliderDrag()"
                                     @touchcancel.window="if (isSliderDragging) endSliderDrag()">

                                    <!-- Subtle Corner Badges (Before / After) -->
                                    <div class="absolute top-2.5 left-2.5 z-20 pointer-events-none select-none">
                                        <span class="bg-black/70 backdrop-blur-sm text-slate-300 text-[10px] font-bold px-2 py-0.5 rounded border border-white/10 uppercase tracking-wider shadow">Before</span>
                                    </div>
                                    <div class="absolute top-2.5 right-2.5 z-20 pointer-events-none select-none">
                                        <span class="bg-teal-950/80 backdrop-blur-sm text-teal-300 text-[10px] font-bold px-2 py-0.5 rounded border border-teal-500/40 uppercase tracking-wider shadow">After</span>
                                    </div>

                                    <!-- Base Layer: Original Face Photo -->
                                    <div class="absolute inset-0 w-full h-full flex items-center justify-center overflow-hidden p-2 pointer-events-none">
                                        <img x-ref="faceImg" loading="eager" decoding="sync" :src="userFacePhoto"
                                            class="max-w-full max-h-full object-contain rounded-lg select-none"
                                            :style="`aspect-ratio: ${photoAspect}; ${getFaceTransform()}`">
                                    </div>

                                    <!-- Top Layer: Transformed Photo with Hairpiece Overlay (Clipped to Right Side) -->
                                    <div class="absolute inset-0 w-full h-full flex items-center justify-center overflow-hidden p-2 pointer-events-none select-none"
                                        :style="`clip-path: inset(0 0 0 ${sliderPos}%)`">
                                        <!-- Base Face Photo -->
                                        <img loading="eager" decoding="sync" :src="userFacePhoto"
                                            class="max-w-full max-h-full object-contain rounded-lg select-none"
                                            :style="`aspect-ratio: ${photoAspect}; ${getFaceTransform()}`">
                                        <!-- Hairpiece Overlay (Matches Photo Width & Vertical Aspect Ratio) -->
                                        <div class="absolute inset-0 w-full h-full flex items-center justify-center overflow-hidden p-2 pointer-events-none">
                                            <div class="w-full h-full flex items-center justify-center transition-transform duration-75"
                                                :style="getHairTransform()">
                                                <img x-ref="hairImg" loading="eager" decoding="sync" :src="getHairOverlayUrl()"
                                                    class="max-w-full max-h-full object-contain drop-shadow-2xl select-none transition-opacity duration-150"
                                                    :style="`aspect-ratio: ${photoAspect};`">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Direct Manipulation Interactive Bounding Box Overlay for Hairpiece -->
                                    <div x-show="activeControlTab === 'hair' && !isSliderDragging" 
                                         class="absolute inset-0 w-full h-full flex items-center justify-center overflow-hidden p-2 pointer-events-none z-15">
                                        <div class="w-full h-full flex items-center justify-center transition-transform duration-75"
                                             :style="getHairTransform()">
                                             <div class="relative pointer-events-auto border-2 border-dashed border-amber-300 ring-2 ring-teal-400/50 rounded-2xl group shadow-2xl bg-teal-400/10 transition-all flex items-center justify-center"
                                                  :style="`width: 100%; aspect-ratio: ${photoAspect}; max-width: 100%; max-height: 100%;`">
                                                 <!-- Corner Resize Handles (Scale) -->
                                                 <div @mousedown.stop="startDrag($event, 'se')" @touchstart.stop="startDrag($event, 'se')" 
                                                      class="absolute -bottom-3 -right-3 w-7 h-7 bg-teal-300 border-2 border-slate-900 rounded-full cursor-se-resize shadow-xl hover:scale-125 transition-transform flex items-center justify-center text-[9px] text-slate-950 font-black z-20">↘</div>
                                                 <div @mousedown.stop="startDrag($event, 'sw')" @touchstart.stop="startDrag($event, 'sw')" 
                                                      class="absolute -bottom-3 -left-3 w-7 h-7 bg-teal-300 border-2 border-slate-900 rounded-full cursor-sw-resize shadow-xl hover:scale-125 transition-transform flex items-center justify-center text-[9px] text-slate-950 font-black z-20">↙</div>
                                                 <div @mousedown.stop="startDrag($event, 'ne')" @touchstart.stop="startDrag($event, 'ne')" 
                                                      class="absolute -top-3 -right-3 w-7 h-7 bg-teal-300 border-2 border-slate-900 rounded-full cursor-ne-resize shadow-xl hover:scale-125 transition-transform flex items-center justify-center text-[9px] text-slate-950 font-black z-20">↗</div>
                                                 <div @mousedown.stop="startDrag($event, 'nw')" @touchstart.stop="startDrag($event, 'nw')" 
                                                      class="absolute -top-3 -left-3 w-7 h-7 bg-teal-300 border-2 border-slate-900 rounded-full cursor-nw-resize shadow-xl hover:scale-125 transition-transform flex items-center justify-center text-[9px] text-slate-950 font-black z-20">↖</div>

                                                 <!-- Side Handles (Width & Height) -->
                                                 <div @mousedown.stop="startDrag($event, 'e')" @touchstart.stop="startDrag($event, 'e')" 
                                                      class="absolute top-1/2 -right-3.5 -translate-y-1/2 w-6 h-8 bg-amber-400 border-2 border-slate-900 rounded-md cursor-e-resize shadow-xl hover:scale-110 transition-transform flex items-center justify-center text-[11px] text-slate-950 font-black z-20">↔</div>
                                                 <div @mousedown.stop="startDrag($event, 'w')" @touchstart.stop="startDrag($event, 'w')" 
                                                      class="absolute top-1/2 -left-3.5 -translate-y-1/2 w-6 h-8 bg-amber-400 border-2 border-slate-900 rounded-md cursor-w-resize shadow-xl hover:scale-110 transition-transform flex items-center justify-center text-[11px] text-slate-950 font-black z-20">↔</div>
                                                 <div @mousedown.stop="startDrag($event, 's')" @touchstart.stop="startDrag($event, 's')" 
                                                      class="absolute -bottom-3.5 left-1/2 -translate-x-1/2 w-8 h-6 bg-amber-400 border-2 border-slate-900 rounded-md cursor-s-resize shadow-xl hover:scale-110 transition-transform flex items-center justify-center text-[11px] text-slate-950 font-black z-20">↕</div>
                                                 <div @mousedown.stop="startDrag($event, 'n')" @touchstart.stop="startDrag($event, 'n')" 
                                                      class="absolute -top-3.5 left-1/2 -translate-x-1/2 w-8 h-6 bg-amber-400 border-2 border-slate-900 rounded-md cursor-n-resize shadow-xl hover:scale-110 transition-transform flex items-center justify-center text-[11px] text-slate-950 font-black z-20">↕</div>
                                             </div>
                                        </div>
                                    </div>

                                    <!-- Comparison Divider Slider Line & Center Draggable Arrow Button -->
                                    <div class="slider-divider absolute top-0 bottom-0 pointer-events-auto z-25 select-none cursor-ew-resize touch-none flex items-center justify-center -translate-x-1/2"
                                        :style="`left: ${sliderPos}%; width: 44px;`"
                                        @mousedown.stop.prevent="startSliderDrag($event)"
                                        @touchstart.stop.prevent="startSliderDrag($event)">

                                        <!-- Vertical Line -->
                                        <div class="w-0.5 h-full bg-white shadow-[0_0_8px_rgba(0,0,0,0.85)] pointer-events-none transition-colors"
                                            :class="isSliderDragging ? 'bg-teal-300 w-1 shadow-[0_0_12px_rgba(45,212,191,0.9)]' : ''"></div>

                                        <!-- Center Draggable Arrow Button -->
                                        <div class="slider-handle w-9 h-9 sm:w-10 sm:h-10 rounded-full teal-gradient absolute top-1/2 -translate-y-1/2 flex items-center justify-center text-white text-xs sm:text-sm shadow-2xl border-2 border-white pointer-events-auto cursor-grab active:cursor-grabbing hover:scale-110 active:scale-95 transition-transform ring-4 ring-teal-400/40"
                                            :class="isSliderDragging ? 'scale-115 ring-teal-300 ring-8 shadow-teal-500/50' : ''"
                                            @mousedown.stop.prevent="startSliderDrag($event)"
                                            @touchstart.stop.prevent="startSliderDrag($event)"
                                            title="마우스 또는 손가락으로 좌우로 드래그하여 Before / After를 비교하세요">
                                            <i class="fa-solid fa-arrows-left-right drop-shadow-md"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- OPERATING INSTRUCTIONS BANNER (OUTSIDE PHOTO CANVAS BOX) -->
                            <div class="bg-brand-inner/90 text-slate-200 text-xs font-semibold py-2 px-3 rounded-xl border border-brand-border text-center flex items-center justify-center gap-2 shadow-sm">
                                <i class="fa-solid fa-hand-pointer text-brand-tealLight animate-bounce"></i>
                                <span x-text="activeControlTab === 'hair' ? 'Drag canvas to reposition hair | Use handles to adjust width & length | Scroll to zoom' : 'Drag canvas to reposition photo | Scroll wheel to zoom in/out'"></span>
                            </div>


                            <!-- PHOTO EXPORT & PRINT ACTIONS -->
                            <div class="grid grid-cols-2 gap-2.5 pt-1">
                                <button type="button" @click="openExportModal('after')"
                                    class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs py-2.5 px-3 rounded-xl border border-emerald-400/40 shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95 group">
                                    <i class="fa-solid fa-download text-sm group-hover:scale-110 transition-transform"></i>
                                    <span>Save Image (사진 저장)</span>
                                </button>
                                <button type="button" @click="printSynthesizedPhoto('after')"
                                    class="bg-slate-800 hover:bg-slate-700 text-teal-300 font-bold text-xs py-2.5 px-3 rounded-xl border border-teal-500/40 shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95 group">
                                    <i class="fa-solid fa-print text-sm group-hover:scale-110 transition-transform"></i>
                                    <span>Print Card (사진 출력)</span>
                                </button>
                            </div>

                            <!-- Action CTAs -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                                <button type="button" @click="$dispatch('open-booking-modal'); openBookingModal();"
                                    class="teal-gradient text-white text-xs font-bold uppercase tracking-wider py-3.5 px-4 rounded-xl shadow-md hover:opacity-95 min-h-[46px] flex items-center justify-center gap-2 cursor-pointer transition-all active:scale-95">
                                    <i class="fa-solid fa-location-dot text-amber-300 text-sm"></i>
                                    <span>Book Springvale Fitting</span>
                                </button>
                                <button type="button" @click="isCalculatorModalOpen = true"
                                    class="bg-brand-inner hover:bg-slate-800 text-slate-200 border border-brand-border text-xs font-bold uppercase tracking-wider py-3.5 px-4 rounded-xl text-center flex items-center justify-center min-h-[46px] cursor-pointer transition-all">
                                    <i class="fa-solid fa-calculator mr-1.5 text-brand-tealLight"></i> Custom Build This Look
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: Control Panel & Customizer -->
                    <div class="lg:col-span-6 space-y-5">

                        <!-- STEP 1: Photo Options (Camera vs File Upload) -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-brand-tealLight">Step 1:
                                Photo Input Method</label>
                            <div class="grid grid-cols-2 gap-2.5">
                                <!-- Camera Capture Trigger Button -->
                                <button @click="startCamera()" type="button"
                                    :class="!hasOwnPhoto ? 'ai-take-photo-pulse text-white' : 'teal-gradient text-white'"
                                    class="relative font-extrabold text-xs p-3 rounded-xl shadow-lg hover:opacity-95 transition-all flex items-center justify-center gap-2 min-h-[52px] cursor-pointer">
                                    <span x-show="!hasOwnPhoto"
                                        class="absolute -top-2.5 right-2 bg-amber-400 text-slate-950 text-[9px] font-black uppercase px-2 py-0.5 rounded-full shadow animate-bounce">
                                        Step 1
                                    </span>
                                    <span x-show="!hasOwnPhoto" class="relative flex h-2.5 w-2.5">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-200 opacity-90"></span>
                                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-white"></span>
                                    </span>
                                    <i class="fa-solid fa-camera text-base"></i>
                                    <span>Take Photo (Camera)</span>
                                </button>
                                <!-- File Upload Trigger -->
                                <div
                                    class="border-2 border-dashed border-brand-border hover:border-brand-teal rounded-xl p-2.5 text-center bg-brand-inner transition-colors relative cursor-pointer group flex items-center justify-center gap-2 min-h-[50px]">
                                    <input type="file" accept="image/*" @change="handleFaceUpload($event)"
                                        class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10">
                                    <i class="fa-solid fa-upload text-sm text-brand-tealLight"></i>
                                    <span class="text-xs font-bold text-white">Upload Photo</span>
                                </div>
                            </div>
                            <!-- Sample Face Options -->
                            <div class="flex items-center gap-2 pt-1">
                                <span class="text-[10px] text-brand-textMuted font-medium">Or try sample:</span>
                                <button
                                    @click="setSampleFace('images/manhairstyle/manO.jpeg')"
                                    class="text-[10px] text-brand-tealLight underline font-bold">Sample 1 (Man)</button>
                                <button
                                    @click="setSampleFace('images/womanhairstyle/womanO.jpeg')"
                                    class="text-[10px] text-brand-tealLight underline font-bold">Sample 2 (Woman)</button>
                            </div>
                        </div>

                        <!-- STEP 2: Hairstyle Category Filters (Man, Woman, All) -->
                        <div class="space-y-2.5">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold uppercase tracking-wider text-brand-tealLight">Step 2:
                                    Select Category & Style</label>
                                <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-brand-inner border border-brand-border text-slate-200" x-text="selectedStyle"></span>
                            </div>

                            <!-- Category Filter Tabs (Man, Woman, All) -->
                            <div
                                class="flex items-center gap-1.5 bg-brand-inner p-1 rounded-xl border border-brand-border text-xs font-bold">
                                <button type="button" @click="selectCategory('Man')"
                                    :class="selectedCategory === 'Man' ? 'bg-brand-teal text-white shadow' : 'text-slate-400 hover:text-white'"
                                    class="flex-1 py-1.5 rounded-lg transition-all text-center cursor-pointer">
                                    👨 Men
                                </button>
                                <button type="button" @click="selectCategory('Woman')"
                                    :class="selectedCategory === 'Woman' ? 'bg-pink-600 text-white shadow' : 'text-slate-400 hover:text-white'"
                                    class="flex-1 py-1.5 rounded-lg transition-all text-center cursor-pointer">
                                    👩 Women
                                </button>
                                <button type="button" @click="selectCategory('All')"
                                    :class="selectedCategory === 'All' ? 'bg-brand-teal text-white shadow' : 'text-slate-400 hover:text-white'"
                                    class="py-1.5 px-3 rounded-lg transition-all text-center cursor-pointer">
                                    ✨ All
                                </button>
                            </div>

                            <!-- Style Cards List (150*150 Hairstyle Photos on White Background) -->
                            <div class="flex flex-wrap items-center gap-3 max-h-[340px] overflow-y-auto pr-1 justify-center sm:justify-start">
                                <template x-for="st in filteredStyles" :key="st.name">
                                    <button type="button" @click="selectedStyle = st.name"
                                        :title="st.name + (st.tag ? ' - ' + st.tag : '')"
                                        :class="[
                                            isWomanStyle(st)
                                                ? (selectedStyle === st.name
                                                    ? 'border-pink-500 ring-4 ring-pink-400/50 shadow-lg shadow-pink-500/25 scale-[1.02]'
                                                    : 'border-pink-400 hover:border-pink-500 hover:scale-[1.01]')
                                                : (selectedStyle === st.name
                                                    ? 'border-brand-teal ring-4 ring-teal-400/50 shadow-lg shadow-teal-500/25 scale-[1.02]'
                                                    : 'border-brand-border hover:border-slate-400 hover:scale-[1.01]')
                                        ]"
                                        class="w-[150px] h-[150px] min-w-[150px] min-h-[150px] max-w-[150px] max-h-[150px] bg-white border-2 rounded-2xl p-1.5 flex items-center justify-center transition-all cursor-pointer relative overflow-hidden group shadow-sm">
                                        <img :src="st.overlay" :alt="st.name"
                                            class="w-full h-full object-contain rounded-xl transition-transform duration-200 group-hover:scale-105 pointer-events-none"
                                            loading="lazy">
                                    </button>
                                </template>
                                <div x-show="filteredStyles.length === 0" class="w-full py-8 text-center text-slate-400 text-xs bg-brand-inner/40 rounded-xl border border-dashed border-brand-border">
                                    <i class="fa-solid fa-sparkles text-brand-tealLight text-sm mb-1.5 block"></i>
                                    <span class="font-medium">No styles registered in this category</span>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 3: Fine-Tune Adjustments (Hair Color & Blend) -->
                        <div class="pt-2 border-t border-brand-border space-y-2.5">
                            <label class="block text-xs font-bold uppercase tracking-wider text-brand-tealLight">Step 3:
                                Fine-Tune Adjustments</label>

                            <!-- Hair Color & Blend Selection -->
                            <div class="bg-brand-inner/60 border border-brand-border/80 p-3.5 rounded-xl space-y-2.5">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-300 flex items-center gap-1.5">
                                        <i class="fa-solid fa-palette text-brand-tealLight"></i> Hair Color & Blend
                                    </span>
                                    <span class="text-[11px] font-mono text-brand-tealLight font-bold" x-text="selectedColor"></span>
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <template
                                        x-for="c in ['#1B Off Black', '#1 Jet Black', '#2 Dark Brown', '#4 Med Brown', '#613 Blonde']"
                                        :key="c">
                                        <button type="button" @click="selectedColor = c"
                                            :class="selectedColor === c ? 'ring-2 ring-brand-teal scale-105 bg-teal-950 text-white font-bold border-brand-teal shadow-md' : 'opacity-70 hover:opacity-100 bg-brand-inner text-slate-300 border-brand-border hover:border-slate-500'"
                                            class="px-2.5 py-1.5 rounded-lg border text-xs transition-all flex items-center gap-2 cursor-pointer">
                                            <span class="w-3 h-3 rounded-full border border-white/20 inline-block shadow-inner shrink-0"
                                                :style="{
                                                    '#1B Off Black': 'background-color: #242424',
                                                    '#1 Jet Black': 'background-color: #0d0d0d',
                                                    '#2 Dark Brown': 'background-color: #3d2314',
                                                    '#4 Med Brown': 'background-color: #633e24',
                                                    '#613 Blonde': 'background-color: #e8ca8c'
                                                }[c]"></span>
                                            <span x-text="c"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Apply & Save Specifications Button -->
                        <button @click="runTransformation()" :disabled="isProcessing"
                            class="w-full teal-gradient text-white font-bold text-xs uppercase tracking-wider py-3.5 rounded-xl shadow-lg hover:opacity-95 transition-all min-h-[48px] flex items-center justify-center gap-2 disabled:opacity-50">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            <span x-text="isProcessing ? 'AI Mapping Scalp & Fitting...' : 'Save Hairstyle Specs'"></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- SYNTHESIZED PHOTO EXPORT & PRINT PREVIEW MODAL -->
            <div x-show="isExportModalOpen" x-cloak
                @keydown.escape.window="isExportModalOpen = false"
                class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-5 bg-black/80 backdrop-blur-md">
                
                <div @click.away="isExportModalOpen = false"
                    class="bg-brand-cardBg border border-teal-500/40 rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl flex flex-col max-h-[92vh]">
                    
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-4 border-b border-brand-border bg-brand-inner">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg teal-gradient flex items-center justify-center text-white text-sm shadow">
                                <i class="fa-solid fa-camera-retro"></i>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-bold text-white">Synthesized Hairstyle Photo</h3>
                                <p class="text-[11px] text-brand-textMuted">Save high-res image or print client consultation card</p>
                            </div>
                        </div>
                        <button type="button" @click="isExportModalOpen = false"
                            class="w-8 h-8 rounded-lg bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition-colors cursor-pointer">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <!-- Mode Toggle (Full Look vs Before/After) -->
                    <div class="p-3 bg-brand-darkBg border-b border-brand-border flex items-center justify-center gap-2">
                        <button type="button" @click="switchExportMode('after')"
                            :class="exportMode === 'after' ? 'teal-gradient text-white font-bold shadow' : 'bg-slate-800 text-slate-300 hover:text-white border border-slate-700'"
                            class="flex-1 py-1.5 px-3 rounded-lg text-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-wand-magic-sparkles text-[11px]"></i>
                            <span>Full Transformation (완성본)</span>
                        </button>
                        <button type="button" @click="switchExportMode('compare')"
                            :class="exportMode === 'compare' ? 'teal-gradient text-white font-bold shadow' : 'bg-slate-800 text-slate-300 hover:text-white border border-slate-700'"
                            class="flex-1 py-1.5 px-3 rounded-lg text-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-arrows-left-right text-[11px]"></i>
                            <span>Before & After (비교본)</span>
                        </button>
                    </div>

                    <!-- Modal Body: Image Preview -->
                    <div class="p-4 flex-1 overflow-y-auto flex flex-col items-center justify-center bg-slate-950/70">
                        <div class="relative w-full max-w-[360px] aspect-square rounded-xl overflow-hidden shadow-2xl border-2 border-teal-500/40 bg-black flex items-center justify-center">
                            <template x-if="exportPreviewUrl">
                                <img :src="exportPreviewUrl" alt="Synthesized Result Preview"
                                    class="w-full h-full object-contain">
                            </template>
                            <div x-show="isExporting"
                                class="absolute inset-0 bg-black/75 backdrop-blur-sm flex flex-col items-center justify-center gap-2">
                                <div class="w-8 h-8 rounded-full border-3 border-teal-400 border-t-transparent animate-spin"></div>
                                <span class="text-xs text-teal-300 font-bold">Rendering high-res photo...</span>
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-400 text-center mt-2.5">
                            <i class="fa-solid fa-mobile-screen-button mr-1 text-teal-400"></i>
                            모바일에서는 위 사진을 길게 터치하여 앨범에 직접 저장할 수도 있습니다.
                        </p>
                    </div>

                    <!-- Modal Footer Actions -->
                    <div class="p-4 border-t border-brand-border bg-brand-inner space-y-2.5">
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" @click="downloadSynthesizedPhoto(exportMode)"
                                class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs py-2.5 px-3 rounded-xl shadow transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                                <i class="fa-solid fa-download text-sm"></i>
                                <span>Download PNG (저장)</span>
                            </button>
                            <button type="button" @click="printSynthesizedPhoto(exportMode)"
                                class="bg-slate-800 hover:bg-slate-700 text-teal-300 border border-teal-500/40 font-bold text-xs py-2.5 px-3 rounded-xl shadow transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                                <i class="fa-solid fa-print text-sm"></i>
                                <span>Print Card (인쇄/출력)</span>
                            </button>
                        </div>
                        <button type="button" @click="isExportModalOpen = false; $dispatch('open-booking-modal'); openBookingModal();"
                            class="w-full teal-gradient hover:opacity-95 text-white font-bold text-xs uppercase tracking-wider py-3 px-4 rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                            <i class="fa-solid fa-location-dot text-amber-300"></i>
                            <span>Book Springvale Fitting</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
