function medicalWigsSectionApp() {
    return {
        activePhotoIdx: 0,
        photos: [
            {
                src: 'images/salon2.jpg',
                title: '100% Private 1-on-1 Suite',
                badge: 'Springvale Flagship',
                desc: 'Completely discrete, door-closed suite with zero outside visibility for absolute dignity and tranquility.'
            },
            {
                src: 'images/salon1.jpg',
                title: 'Comfortable Consultation Lounge',
                badge: 'Carers Welcome',
                desc: 'Warm sanctuary where partners, family members, or carers can sit together with complimentary tea and zero pressure.'
            },
            {
                src: 'images/transformations/woman3_after.webp',
                title: 'Medical Grade Full Wig Transformation',
                badge: '100% Virgin Hair',
                desc: 'Ultra-soft organic cotton & bamboo interior lining specially engineered for sensitive or chemo-treated scalps.'
            }
        ],
        selectPhoto(idx) {
            this.activePhotoIdx = idx;
        }
    };
}

