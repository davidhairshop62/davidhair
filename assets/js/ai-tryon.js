function aiTryOnSimulator() {
    return {
        userFacePhoto: 'images/manhairstyle/manO.jpeg',
        photoAspect: '1 / 1',
        selectedStyle: 'Crew cut',
        selectedColor: '#1B Off Black',
        selectedCategory: 'Man', // 'All', 'Man', 'Woman', 'Unisex'
        activeControlTab: 'hair', // 'hair' | 'photo'
        isProcessing: false,
        sliderPos: 50,

        // Booking trigger helper
        openBookingModal() {
            window.dispatchEvent(new CustomEvent('open-booking-modal'));
            try {
                const root = document.querySelector('[x-data*="davidHairApp"]');
                if (root && root._x_dataStack && root._x_dataStack[0]) {
                    root._x_dataStack[0].isBookingModalOpen = true;
                }
            } catch(e) {}
        },

        // Synthesized Photo Export & Print State
        exportPreviewUrl: null,
        isExportModalOpen: false,
        exportMode: 'after',
        isExporting: false,

        // Camera capture state
        isCameraActive: false,
        mediaStream: null,
        cameraFacingMode: 'user', // 'user' (front) or 'environment' (rear)
        cameraGuideOpacity: 0.5, // 50% opacity (transparency)
        cameraGuideGender: 'Man', // 'Man' or 'Woman'

        // Original Face Photo transform controls
        faceScale: 100,
        faceX: 0,
        faceY: 0,

        // Hairpiece fitting & stretch controls
        hairX: 0,
        hairY: 0,
        hairScale: 100,
        hairWidth: 100,
        hairHeight: 100,

        // Direct Drag & Touch Manipulation State
        isDragging: false,
        dragHandle: null,
        startX: 0,
        startY: 0,
        initialX: 0,
        initialY: 0,
        initialWidth: 100,
        initialHeight: 100,
        initialScale: 100,

        // Before/After Comparison Slider Drag State & Handlers
        isSliderDragging: false,

        startSliderDrag(e) {
            if (e && e.stopPropagation) e.stopPropagation();
            this.isDragging = false;
            this.dragHandle = null;
            this.isSliderDragging = true;
            this.updateSliderPos(e);
        },

        updateSliderPos(e) {
            const canvas = this.$refs.photoCanvas;
            if (!canvas) return;
            const rect = canvas.getBoundingClientRect();
            const touch = (e.touches && e.touches.length > 0)
                ? e.touches[0]
                : ((e.changedTouches && e.changedTouches.length > 0) ? e.changedTouches[0] : e);
            if (!touch || typeof touch.clientX !== 'number') return;
            const relativeX = touch.clientX - rect.left;
            let pct = (relativeX / rect.width) * 100;
            pct = Math.max(0, Math.min(100, Math.round(pct)));
            this.sliderPos = pct;
        },

        onSliderMove(e) {
            if (!this.isSliderDragging) return;
            if (e.cancelable && e.preventDefault) e.preventDefault();
            this.updateSliderPos(e);
        },

        endSliderDrag() {
            this.isSliderDragging = false;
        },

        startDrag(e, handle = 'move') {
            if (this.isSliderDragging) return;
            if (e.target && e.target.closest && (e.target.closest('.slider-divider') || e.target.closest('.slider-handle'))) return;
            this.isDragging = true;
            this.dragHandle = handle;
            const touch = e.touches ? e.touches[0] : e;
            this.startX = touch.clientX;
            this.startY = touch.clientY;

            if (this.activeControlTab === 'hair') {
                this.initialX = this.hairX;
                this.initialY = this.hairY;
                this.initialScale = this.hairScale;
                this.initialWidth = this.hairWidth;
                this.initialHeight = this.hairHeight;
            } else {
                this.initialX = this.faceX;
                this.initialY = this.faceY;
                this.initialScale = this.faceScale;
            }
        },

        onDrag(e) {
            if (this.isSliderDragging) return;
            if (!this.isDragging) return;
            const touch = e.touches ? e.touches[0] : e;
            const deltaX = touch.clientX - this.startX;
            const deltaY = touch.clientY - this.startY;

            if (this.dragHandle === 'move') {
                if (this.activeControlTab === 'hair') {
                    this.hairX = Math.round(this.initialX + deltaX);
                    this.hairY = Math.round(this.initialY + deltaY);
                } else {
                    this.faceX = Math.round(this.initialX + deltaX);
                    this.faceY = Math.round(this.initialY + deltaY);
                }
            } else if (this.activeControlTab === 'hair') {
                if (this.dragHandle === 'e' || this.dragHandle === 'w') {
                    const factor = this.dragHandle === 'e' ? deltaX : -deltaX;
                    this.hairWidth = Math.max(40, Math.min(250, Math.round(this.initialWidth + factor * 0.8)));
                } else if (this.dragHandle === 's' || this.dragHandle === 'n') {
                    const factor = this.dragHandle === 's' ? deltaY : -deltaY;
                    this.hairHeight = Math.max(40, Math.min(250, Math.round(this.initialHeight + factor * 0.8)));
                } else if (['nw', 'ne', 'sw', 'se'].includes(this.dragHandle)) {
                    const factor = ['se', 'ne'].includes(this.dragHandle) ? deltaX : -deltaX;
                    this.hairScale = Math.max(40, Math.min(250, Math.round(this.initialScale + factor * 0.5)));
                }
            } else if (this.activeControlTab === 'photo') {
                if (['nw', 'ne', 'sw', 'se'].includes(this.dragHandle)) {
                    this.faceScale = Math.max(40, Math.min(250, Math.round(this.initialScale + deltaX * 0.5)));
                }
            }
        },

        endDrag() {
            this.isDragging = false;
            this.dragHandle = null;
        },

        onWheel(e) {
            const delta = e.deltaY > 0 ? -5 : 5;
            if (this.activeControlTab === 'hair') {
                this.hairScale = Math.max(40, Math.min(250, this.hairScale + delta));
            } else {
                this.faceScale = Math.max(40, Math.min(250, this.faceScale + delta));
            }
        },

        resetTransform() {
            if (this.activeControlTab === 'hair') {
                this.hairX = 0;
                this.hairY = 0;
                this.hairScale = 100;
                this.hairWidth = 100;
                this.hairHeight = 100;
            } else {
                this.faceX = 0;
                this.faceY = 0;
                this.faceScale = 100;
            }
        },

        // High-Resolution Photo Synthesizer & Canvas Generator
        async generateSynthesizedCanvas(mode = 'after') {
            const canvasBox = this.$refs.photoCanvas;
            if (!canvasBox) return null;
            const rect = canvasBox.getBoundingClientRect();
            const W = rect.width || 420;
            const H = rect.height || 420;

            const size = 1080;
            const canvas = document.createElement('canvas');
            canvas.width = size;
            canvas.height = size;
            const ctx = canvas.getContext('2d');
            if (!ctx) return null;

            const scale = size / W;
            const cx = size / 2;
            const cy = size / 2;
            const pad = 16 * scale;
            const baseSize = size - pad;

            const loadImg = (src) => {
                return new Promise((resolve, reject) => {
                    if (!src) return reject(new Error('Empty src'));
                    const img = new Image();
                    img.crossOrigin = 'anonymous';
                    img.onload = () => resolve(img);
                    img.onerror = () => reject(new Error('Failed to load image: ' + src));
                    if (src.startsWith('data:image/svg+xml;utf8,')) {
                        const rawSvg = src.replace('data:image/svg+xml;utf8,', '');
                        img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(rawSvg)));
                    } else {
                        img.src = src;
                    }
                });
            };

            let faceImg, hairImg;
            try {
                faceImg = await loadImg(this.userFacePhoto);
            } catch (e) {
                faceImg = this.$refs.faceImg;
            }

            try {
                hairImg = await loadImg(this.getHairOverlayUrl());
            } catch (e) {
                hairImg = this.$refs.hairImg;
            }

            if (!faceImg) return null;

            // Fill sleek luxury background
            ctx.fillStyle = '#0a0f1d';
            ctx.fillRect(0, 0, size, size);

            const drawFace = () => {
                ctx.save();
                ctx.translate(cx + this.faceX * scale, cy + this.faceY * scale);
                const fScale = this.faceScale / 100;
                ctx.scale(fScale, fScale);
                ctx.drawImage(faceImg, -baseSize / 2, -baseSize / 2, baseSize, baseSize);
                ctx.restore();
            };

            const drawHair = () => {
                if (!hairImg) return;
                ctx.save();
                ctx.translate(cx + this.hairX * scale, cy + this.hairY * scale);
                const sX = (this.hairScale / 100) * (this.hairWidth / 100);
                const sY = (this.hairScale / 100) * (this.hairHeight / 100);
                ctx.scale(sX, sY);
                if (ctx.filter) {
                    ctx.filter = this.getHairFilter();
                }
                ctx.drawImage(hairImg, -baseSize / 2, -baseSize / 2, baseSize, baseSize);
                ctx.restore();
            };

            if (mode === 'after') {
                drawFace();
                drawHair();
            } else {
                // Compare split view
                const splitX = Math.round((this.sliderPos / 100) * size);

                // Left: Original Face (Before)
                ctx.save();
                ctx.beginPath();
                ctx.rect(0, 0, splitX, size);
                ctx.clip();
                drawFace();
                ctx.restore();

                // Right: Transformed Face with Hair (After)
                ctx.save();
                ctx.beginPath();
                ctx.rect(splitX, 0, size - splitX, size);
                ctx.clip();
                drawFace();
                drawHair();
                ctx.restore();

                // Vertical Divider Line
                ctx.strokeStyle = '#ffffff';
                ctx.lineWidth = 4;
                ctx.beginPath();
                ctx.moveTo(splitX, 0);
                ctx.lineTo(splitX, size);
                ctx.stroke();

                // BEFORE badge
                ctx.fillStyle = 'rgba(0, 0, 0, 0.75)';
                ctx.fillRect(24, 24, 110, 36);
                ctx.strokeStyle = 'rgba(255, 255, 255, 0.2)';
                ctx.lineWidth = 1;
                ctx.strokeRect(24, 24, 110, 36);
                ctx.fillStyle = '#cbd5e1';
                ctx.font = 'bold 18px system-ui, -apple-system, sans-serif';
                ctx.fillText('BEFORE', 42, 48);

                // AFTER badge
                ctx.fillStyle = 'rgba(19, 78, 74, 0.9)';
                ctx.fillRect(size - 134, 24, 110, 36);
                ctx.strokeStyle = 'rgba(45, 212, 191, 0.5)';
                ctx.lineWidth = 1;
                ctx.strokeRect(size - 134, 24, 110, 36);
                ctx.fillStyle = '#5eead4';
                ctx.font = 'bold 18px system-ui, -apple-system, sans-serif';
                ctx.fillText('AFTER', size - 104, 48);
            }

            // Bottom Studio Branding Bar
            const footerH = 80;
            ctx.fillStyle = 'rgba(11, 17, 32, 0.94)';
            ctx.fillRect(0, size - footerH, size, footerH);
            ctx.strokeStyle = 'rgba(45, 212, 191, 0.4)';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(0, size - footerH);
            ctx.lineTo(size, size - footerH);
            ctx.stroke();

            ctx.fillStyle = '#2dd4bf';
            ctx.font = 'bold 22px system-ui, -apple-system, sans-serif';
            ctx.fillText('DAVID HAIR STUDIO', 28, size - 35);

            ctx.fillStyle = '#cbd5e1';
            ctx.font = '16px system-ui, -apple-system, sans-serif';
            ctx.fillText(`Style: ${this.selectedStyle} | Color: ${this.selectedColor}`, 290, size - 35);

            ctx.fillStyle = '#94a3b8';
            ctx.font = '14px system-ui, -apple-system, sans-serif';
            ctx.fillText('Springvale Melbourne • +61 433 484 882', size - 310, size - 35);

            return canvas;
        },

        async openExportModal(mode = 'after') {
            this.exportMode = mode;
            this.isExporting = true;
            this.isExportModalOpen = true;
            try {
                const canvas = await this.generateSynthesizedCanvas(this.exportMode);
                if (canvas) {
                    this.exportPreviewUrl = canvas.toDataURL('image/png');
                }
            } catch (e) {
                console.error('Export preview error:', e);
            } finally {
                this.isExporting = false;
            }
        },

        async switchExportMode(mode) {
            this.exportMode = mode;
            this.isExporting = true;
            try {
                const canvas = await this.generateSynthesizedCanvas(mode);
                if (canvas) {
                    this.exportPreviewUrl = canvas.toDataURL('image/png');
                }
            } catch (e) {
                console.error('Switch mode error:', e);
            } finally {
                this.isExporting = false;
            }
        },

        async downloadSynthesizedPhoto(mode = this.exportMode || 'after') {
            this.isExporting = true;
            try {
                const canvas = await this.generateSynthesizedCanvas(mode);
                if (!canvas) throw new Error('Canvas generation failed');
                const dataUrl = canvas.toDataURL('image/png');
                this.exportPreviewUrl = dataUrl;

                const a = document.createElement('a');
                const safeName = (this.selectedStyle || 'hairstyle').toLowerCase().replace(/[^a-z0-9]/g, '-');
                a.download = `davidhair-${safeName}-${Date.now()}.png`;
                a.href = dataUrl;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);

                this.isExportModalOpen = true;
            } catch (e) {
                console.error('Download error:', e);
                alert('Failed to save image. Please try again.');
            } finally {
                this.isExporting = false;
            }
        },

        async printSynthesizedPhoto(mode = this.exportMode || 'after') {
            this.isExporting = true;
            try {
                const canvas = await this.generateSynthesizedCanvas(mode);
                if (!canvas) throw new Error('Canvas generation failed');
                const dataUrl = canvas.toDataURL('image/png');
                this.exportPreviewUrl = dataUrl;

                const printWindow = window.open('', '_blank');
                if (printWindow) {
                    printWindow.document.write(`
                        <!DOCTYPE html>
                        <html lang="en">
                        <head>
                            <meta charset="utf-8">
                            <title>David Hair Studio - Hair Fitting Consultation Card</title>
                            <style>
                                @page { margin: 12mm; size: A4 portrait; }
                                * { box-sizing: border-box; }
                                body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; color: #0f172a; margin: 0; padding: 24px; background: #fff; }
                                .card { max-width: 680px; margin: 0 auto; border: 2px solid #0d9488; border-radius: 16px; padding: 28px; background: #ffffff; }
                                .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #e2e8f0; padding-bottom: 16px; margin-bottom: 20px; }
                                .logo { font-size: 26px; font-weight: 900; color: #0f766e; letter-spacing: -0.5px; }
                                .tagline { font-size: 13px; color: #64748b; font-weight: 600; margin-top: 2px; }
                                .meta { text-align: right; font-size: 12px; color: #64748b; line-height: 1.5; }
                                .photo-frame { width: 100%; max-width: 460px; aspect-ratio: 1/1; margin: 0 auto 24px auto; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.12); border: 2px solid #e2e8f0; }
                                .photo-frame img { width: 100%; height: 100%; object-fit: contain; background: #0b1120; display: block; }
                                .specs-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; margin-bottom: 22px; }
                                .spec-item { display: flex; flex-direction: column; gap: 3px; }
                                .spec-label { font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
                                .spec-val { font-size: 15px; font-weight: 700; color: #0f172a; }
                                .footer { border-top: 1px dashed #cbd5e1; padding-top: 16px; font-size: 12px; color: #475569; text-align: center; line-height: 1.6; }
                                .footer strong { color: #0f766e; }
                                @media print {
                                    body { padding: 0; }
                                    .card { border: none; padding: 0; }
                                }
                            </style>
                        </head>
                        <body>
                            <div class="card">
                                <div class="header">
                                    <div>
                                        <div class="logo">DAVID HAIR STUDIO</div>
                                        <div class="tagline">Melbourne Bespoke Non-Surgical Hair Replacement</div>
                                    </div>
                                    <div class="meta">
                                        <strong>Client Consultation Sheet</strong><br>
                                        Date: ${new Date().toLocaleDateString('en-AU', { year: 'numeric', month: 'short', day: 'numeric' })}<br>
                                        Ref: #${Math.floor(100000 + Math.random() * 900000)}
                                    </div>
                                </div>

                                <div class="photo-frame">
                                    <img src="${dataUrl}" alt="Synthesized Hairpiece Fitting Consultation Photo">
                                </div>

                                <div class="specs-grid">
                                    <div class="spec-item">
                                        <span class="spec-label">Selected Hairstyle</span>
                                        <span class="spec-val">${this.selectedStyle}</span>
                                    </div>
                                    <div class="spec-item">
                                        <span class="spec-label">Color Tone Code</span>
                                        <span class="spec-val">${this.selectedColor}</span>
                                    </div>
                                    <div class="spec-item">
                                        <span class="spec-label">Target Category</span>
                                        <span class="spec-val">${this.selectedCategory} Hair System</span>
                                    </div>
                                    <div class="spec-item">
                                        <span class="spec-label">Fitting Studio Location</span>
                                        <span class="spec-val">Shop F16 (Level 1), Buckingham Ave, Springvale VIC</span>
                                    </div>
                                </div>

                                <div class="footer">
                                    <strong>David Hair Studio Melbourne</strong> • Contact: <strong>+61 433 484 882</strong> (WhatsApp / Call)<br>
                                    Please present this consultation card during your free private fitting appointment for priority custom system matching.
                                </div>
                            </div>
                            <script>
                                window.onload = function() {
                                    setTimeout(function() {
                                        window.print();
                                    }, 350);
                                };
                            <\/script>
                        </body>
                        </html>
                    `);
                    printWindow.document.close();
                } else {
                    this.isExportModalOpen = true;
                }
            } catch (e) {
                console.error('Print error:', e);
                alert('Failed to prepare print preview. Please try again.');
            } finally {
                this.isExporting = false;
            }
        },

        styles: [
            {
                name: 'Crew cut',
                category: 'Man',
                tag: 'Clean Short Fade',
                overlay: 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 300" fill="none"><path d="M120,180 C110,110 160,35 250,30 C340,35 390,110 380,180 C360,140 330,85 250,80 C170,85 140,140 120,180 Z" fill="%231a1a1a" opacity="0.95"/><path d="M140,145 C170,75 330,75 360,145 C330,95 170,95 140,145 Z" fill="%232b2b2b" opacity="0.88"/><path d="M160,110 C200,55 300,55 340,110 C300,70 200,70 160,110 Z" fill="%230f0f0f" opacity="0.95"/></svg>'
            },
            {
                name: 'Gentleman',
                category: 'Man',
                tag: 'Executive Side Part',
                overlay: 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 320" fill="none"><path d="M100,200 C90,120 150,25 260,20 C360,20 410,100 400,200 C380,140 350,60 270,60 C200,60 170,120 100,200 Z" fill="%23151515" opacity="0.95"/><path d="M110,180 C140,80 240,40 340,70 C380,120 370,180 340,130 C270,75 200,80 110,180 Z" fill="%232a2a2a" opacity="0.88"/><path d="M140,130 C190,50 300,50 360,110 C310,65 220,65 140,130 Z" fill="%230a0a0a" opacity="0.95"/></svg>'
            },
            {
                name: 'Textured Crop',
                category: 'Man',
                tag: 'Modern Layered Fringe',
                overlay: 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 320" fill="none"><path d="M110,210 C100,120 160,30 250,25 C340,30 400,120 390,210 C370,170 360,120 330,125 C300,130 280,115 250,120 C220,125 190,115 170,125 C140,130 130,170 110,210 Z" fill="%231a1a1a" opacity="0.95"/><path d="M130,160 C160,70 340,70 370,160 C340,105 300,110 250,105 C200,110 160,105 130,160 Z" fill="%23303030" opacity="0.85"/><path d="M150,120 C190,45 310,45 350,120 C310,65 260,65 150,120 Z" fill="%230d0d0d" opacity="0.95"/></svg>'
            },
            {
                name: 'Slick Back',
                category: 'Man',
                tag: 'Back Swept Undercut',
                overlay: 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 320" fill="none"><path d="M105,210 C95,110 150,20 250,15 C350,20 405,110 395,210 C380,150 340,50 250,55 C160,50 120,150 105,210 Z" fill="%23181818" opacity="0.95"/><path d="M125,170 C160,60 340,60 375,170 C340,90 250,85 125,170 Z" fill="%232c2c2c" opacity="0.88"/><path d="M150,120 C190,40 310,40 350,120 C310,55 190,55 150,120 Z" fill="%230a0a0a" opacity="0.95"/></svg>'
            },
            {
                name: 'Silk Topper',
                category: 'Woman',
                tag: 'Women\'s Crown Volume',
                overlay: 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 360" fill="none"><path d="M90,260 C80,130 150,15 250,10 C350,15 420,130 410,260 C390,180 370,80 250,75 C130,80 110,180 90,260 Z" fill="%23181818" opacity="0.95"/><path d="M110,210 C140,80 360,80 390,210 C360,110 250,105 110,210 Z" fill="%232e2e2e" opacity="0.88"/><path d="M140,140 C180,45 320,45 360,140 C320,65 180,65 140,140 Z" fill="%230c0c0c" opacity="0.95"/></svg>'
            }
        ],

        get filteredStyles() {
            if (this.selectedCategory === 'All') return this.styles;
            if (this.selectedCategory === 'Man') {
                return this.styles.filter(s => {
                    const c = (s.category || s.gender || '').trim().toLowerCase();
                    return c === 'man' || c === 'men';
                });
            }
            if (this.selectedCategory === 'Woman') {
                return this.styles.filter(s => {
                    const c = (s.category || s.gender || '').trim().toLowerCase();
                    return c === 'woman' || c === 'women' || c.includes('wom');
                });
            }
            return this.styles;
        },

        isWomanStyle(st) {
            if (!st) return false;
            if (this.selectedCategory === 'Woman') return true;
            const g = (st.gender || st.category || '').toLowerCase();
            return g.includes('wom') || g.includes('female');
        },

        getHairOverlayUrl() {
            const matched = this.styles.find(s => s.name === this.selectedStyle);
            return matched ? matched.overlay : (this.styles[0] ? this.styles[0].overlay : '');
        },

        getHairFilter() {
            if (this.selectedColor === '#1 Jet Black') return 'contrast(130%) brightness(80%)';
            if (this.selectedColor === '#2 Dark Brown') return 'sepia(40%) contrast(110%) brightness(95%)';
            if (this.selectedColor === '#4 Med Brown') return 'sepia(60%) hue-rotate(-10deg) brightness(105%)';
            if (this.selectedColor === '#613 Blonde') return 'sepia(90%) hue-rotate(20deg) brightness(130%)';
            return 'contrast(105%) brightness(95%)';
        },

        getFaceTransform() {
            return `transform: translate(${this.faceX}px, ${this.faceY}px) scale(${this.faceScale / 100}); transform-origin: center;`;
        },

        getHairTransform() {
            const finalScaleX = (this.hairScale / 100) * (this.hairWidth / 100);
            const finalScaleY = (this.hairScale / 100) * (this.hairHeight / 100);
            return `transform: translate(${this.hairX}px, ${this.hairY}px) scale(${finalScaleX}, ${finalScaleY}); filter: ${this.getHairFilter()};`;
        },

        async init() {
            this.preloadAiAssets();
            await this.fetchAiStyles();
            this.preloadAiAssets();
        },

        preloadAiAssets() {
            try {
                const faces = ['images/manhairstyle/manO.jpeg', 'images/womanhairstyle/womanO.jpeg'];
                faces.forEach(src => {
                    const img = new Image();
                    img.src = src;
                });
                if (this.styles && this.styles.length > 0) {
                    this.styles.forEach(s => {
                        if (s.overlay && !s.overlay.startsWith('data:')) {
                            const img = new Image();
                            img.src = s.overlay;
                        }
                    });
                }
            } catch (e) {
                // ignore preloading errors
            }
        },

        async fetchAiStyles() {
            try {
                const res = await fetch('/admin_stock_api.php?action=list_ai_styles');
                const data = await res.json();
                if (data.status === 'success' && data.data && data.data.length > 0) {
                    this.styles = data.data.map(item => {
                        const g = (item.gender || item.category || '').trim().toLowerCase();
                        let cat = 'Man';
                        if (g === 'women' || g === 'woman' || g.includes('wom') || item.name.toLowerCase().includes('woman') || item.name.toLowerCase().includes('topper') || item.name.toLowerCase().includes('female')) {
                            cat = 'Woman';
                        } else {
                            cat = 'Man';
                        }
                        return {
                            ...item,
                            category: cat,
                            gender: cat === 'Woman' ? 'Women' : 'Men'
                        };
                    });
                    const available = this.filteredStyles;
                    if (available.length > 0) {
                        this.selectedStyle = available[0].name;
                    }
                }
            } catch (e) {
                console.log('Using default static AI styles');
            }
        },

        getCameraGuideImage() {
            const g = this.cameraGuideGender || this.selectedCategory || 'Man';
            return (g === 'Woman') ? 'images/womanMeshGreen.png' : 'images/greenmesh.png';
        },

        toggleCameraGuideGender() {
            this.cameraGuideGender = (this.cameraGuideGender === 'Woman') ? 'Man' : 'Woman';
        },

        cycleGuideOpacity() {
            if (this.cameraGuideOpacity === 0.5) {
                this.cameraGuideOpacity = 1.0;
            } else if (this.cameraGuideOpacity === 1.0) {
                this.cameraGuideOpacity = 0;
            } else {
                this.cameraGuideOpacity = 0.5;
            }
        },

        async switchCamera() {
            this.cameraFacingMode = (this.cameraFacingMode === 'user') ? 'environment' : 'user';
            this.stopCamera();
            await this.startCamera();
        },

        async startCamera() {
            this.cameraGuideGender = (this.selectedCategory === 'Woman') ? 'Woman' : 'Man';
            this.cameraGuideOpacity = 0.5;
            try {
                if (this.mediaStream) {
                    this.mediaStream.getTracks().forEach(track => track.stop());
                    this.mediaStream = null;
                }
                this.mediaStream = await navigator.mediaDevices.getUserMedia({
                    video: { 
                        width: { ideal: 1080 }, 
                        height: { ideal: 1080 }, 
                        facingMode: { ideal: this.cameraFacingMode } 
                    },
                    audio: false
                });
                this.isCameraActive = true;
                this.$nextTick(() => {
                    if (this.$refs.cameraVideo) {
                        this.$refs.cameraVideo.srcObject = this.mediaStream;
                    }
                });
            } catch (e) {
                alert('Camera access denied or device not supported. Please allow camera permissions in your browser settings.');
                console.error('Camera access error:', e);
            }
        },

        stopCamera() {
            if (this.mediaStream) {
                this.mediaStream.getTracks().forEach(track => track.stop());
                this.mediaStream = null;
            }
            this.isCameraActive = false;
        },

        captureCameraPhoto() {
            if (!this.$refs.cameraVideo) return;
            const video = this.$refs.cameraVideo;
            const vw = video.videoWidth || 720;
            const vh = video.videoHeight || 720;

            // 1:1 Center Square Crop
            const size = Math.min(vw, vh);
            const sx = (vw - size) / 2;
            const sy = (vh - size) / 2;

            const canvas = document.createElement('canvas');
            canvas.width = 1024;
            canvas.height = 1024;
            const ctx = canvas.getContext('2d');

            // If front camera, mirror horizontally
            if (this.cameraFacingMode === 'user') {
                ctx.translate(canvas.width, 0);
                ctx.scale(-1, 1);
            }

            // Draw centered square crop from video to 1024x1024 canvas
            ctx.drawImage(video, sx, sy, size, size, 0, 0, canvas.width, canvas.height);
            this.userFacePhoto = canvas.toDataURL('image/jpeg', 0.90);
            this.photoAspect = '1 / 1';
            this.stopCamera();
            this.resetAllFit();
        },

        resetHairFit() {
            this.hairX = 0;
            this.hairY = 0;
            this.hairScale = 100;
            this.hairWidth = 100;
            this.hairHeight = 100;
        },

        resetFaceFit() {
            this.faceScale = 100;
            this.faceX = 0;
            this.faceY = 0;
        },

        resetAllFit() {
            this.resetHairFit();
            this.resetFaceFit();
        },

        selectCategory(cat) {
            this.selectedCategory = cat;
            if (cat === 'Man') {
                this.userFacePhoto = 'images/manhairstyle/manO.jpeg';
            } else if (cat === 'Woman') {
                this.userFacePhoto = 'images/womanhairstyle/womanO.jpeg';
            } else if (cat === 'All') {
                this.userFacePhoto = 'images/manhairstyle/manO.jpeg';
            }
            this.photoAspect = '1 / 1';
            const available = this.filteredStyles;
            if (available.length > 0 && !available.some(s => s.name === this.selectedStyle)) {
                this.selectedStyle = available[0].name;
            }
            this.resetAllFit();
        },

        setSampleFace(url) {
            this.userFacePhoto = url;
            this.photoAspect = '1 / 1';
            this.resetAllFit();
        },

        handleFaceUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    // Client-side auto-downscale to max 1200px for instant rendering & 60fps performance
                    const maxDim = 1200;
                    let w = img.width;
                    let h = img.height;
                    if (w > maxDim || h > maxDim) {
                        if (w > h) {
                            h = Math.round((h * maxDim) / w);
                            w = maxDim;
                        } else {
                            w = Math.round((w * maxDim) / h);
                            h = maxDim;
                        }
                    }
                    const canvas = document.createElement('canvas');
                    canvas.width = w;
                    canvas.height = h;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, w, h);
                    this.userFacePhoto = canvas.toDataURL('image/jpeg', 0.88);
                    this.photoAspect = `${w} / ${h}`;
                    this.resetAllFit();
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        runTransformation() {
            this.isProcessing = true;
            setTimeout(() => {
                this.isProcessing = false;
            }, 250);
        }
    }
}

