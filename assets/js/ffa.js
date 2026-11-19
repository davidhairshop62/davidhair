function ffaSectionApp() {
    return {
        activeVideoTab: 'highlight',
        currentVideo: 'videos/Frontal-sum.mp4',
        isPlaying: true,
        isMuted: true,
        currentTime: 0,
        duration: 15,
        progressPercent: 0,
        showControls: false,

        init() {
            this.$nextTick(() => {
                const v = this.$refs.ffaVideo;
                if (v) {
                    v.play().then(() => {
                        this.isPlaying = true;
                    }).catch(() => {
                        this.isPlaying = false;
                    });
                }
            });
        },

        switchVideo(type) {
            this.activeVideoTab = type;
            this.currentVideo = (type === 'highlight') ? 'videos/Frontal-sum.mp4' : 'videos/frontal_full.mp4';
            this.$nextTick(() => {
                const v = this.$refs.ffaVideo;
                if (v) {
                    v.currentTime = 0;
                    v.load();
                    v.play().then(() => {
                        this.isPlaying = true;
                    }).catch(() => {
                        this.isPlaying = false;
                    });
                }
            });
        },

        togglePlay() {
            const v = this.$refs.ffaVideo;
            if (!v) return;
            if (v.paused) {
                v.play().then(() => {
                    this.isPlaying = true;
                }).catch(() => {});
            } else {
                v.pause();
                this.isPlaying = false;
            }
        },

        toggleMute() {
            const v = this.$refs.ffaVideo;
            if (!v) return;
            v.muted = !v.muted;
            this.isMuted = v.muted;
        },

        onTimeUpdate() {
            const v = this.$refs.ffaVideo;
            if (!v || !v.duration) return;
            this.currentTime = v.currentTime;
            this.duration = v.duration;
            this.progressPercent = (v.currentTime / v.duration) * 100;
        },

        onLoadedMetadata() {
            const v = this.$refs.ffaVideo;
            if (!v) return;
            this.duration = v.duration;
        },

        seekVideo(e) {
            const v = this.$refs.ffaVideo;
            const bar = this.$refs.progressBar;
            if (!v || !bar || !v.duration) return;
            const rect = bar.getBoundingClientRect();
            const clickX = e.clientX - rect.left;
            const pct = Math.max(0, Math.min(1, clickX / rect.width));
            v.currentTime = pct * v.duration;
        },

        toggleFullscreen() {
            const v = this.$refs.ffaVideo;
            if (!v) return;
            if (v.requestFullscreen) {
                v.requestFullscreen();
            } else if (v.webkitRequestFullscreen) {
                v.webkitRequestFullscreen();
            }
        },

        formatTime(seconds) {
            if (!seconds || isNaN(seconds)) return '0:00';
            const m = Math.floor(seconds / 60);
            const s = Math.floor(seconds % 60);
            return `${m}:${s < 10 ? '0' : ''}${s}`;
        },

        openFfaModal() {
            window.dispatchEvent(new CustomEvent('open-ffa-modal'));
        }
    };
}
