const fs = require('fs');
const path = require('path');

function optimizeHtml(filePath) {
  let content = fs.readFileSync(filePath, 'utf-8');

  // 1. Replace image extensions with .webp where webp exists
  const webpReplacements = [
    ['images/TransMan.png', 'images/TransMan.webp'],
    ['images/transwoman.png', 'images/transwoman.webp'],
    ['videos/humanwig1.png', 'videos/humanwig1.webp'],
    ['videos/humanwig2.jpeg', 'videos/humanwig2.webp'],
    ['videos/synwig1.png', 'videos/synwig1.webp'],
    ['videos/synwig2.png', 'videos/synwig2.webp'],
    ['videos/pin1.jpeg', 'videos/pin1.webp'],
    ['videos/pin2.png', 'videos/pin2.webp'],
    ['videos/mini1.jpeg', 'videos/mini1.webp'],
    ['videos/mini2.jpg', 'videos/mini2.webp'],
    ['videos/top1.jpeg', 'videos/top1.webp'],
    ['videos/top2.jpeg', 'videos/top2.webp'],
    ['videos/skin1.jpeg', 'videos/skin1.webp'],
    ['videos/skin2.jpeg', 'videos/skin2.webp'],
    ['videos/toupee1.jpeg', 'videos/toupee1.webp'],
    ['videos/toupee2.jpeg', 'videos/toupee2.webp'],
    ['videos/tape1.jpg', 'videos/tape1.webp'],
    ['videos/tape2.jpeg', 'videos/tape2.webp'],
    ['videos/fusion1.jpeg', 'videos/fusion1.webp'],
    ['videos/fusion2.jpeg', 'videos/fusion2.webp'],
    ['videos/weaving.jpeg', 'videos/weaving.webp'],
    ['videos/weaving1.jpeg', 'videos/weaving1.webp'],
    ['videos/train1.jpeg', 'videos/train1.webp'],
    ['videos/train2.jpg', 'videos/train2.webp']
  ];

  // Also replace all images/transformations/*.jpg with *.webp
  content = content.replace(/images\/transformations\/(man\d|woman\d)_(after|before)\.jpg/g, 'images/transformations/$1_$2.webp');

  for (const [oldSrc, newSrc] of webpReplacements) {
    content = content.split(oldSrc).join(newSrc);
  }

  // 2. Add loading="lazy" decoding="async" to all <img> tags that don't already have loading=
  content = content.replace(/<img\s+([^>]*?)>/gi, (match, attrs) => {
    let newAttrs = attrs;
    if (!/loading=/i.test(newAttrs)) {
      newAttrs = `loading="lazy" ${newAttrs}`;
    }
    if (!/decoding=/i.test(newAttrs)) {
      newAttrs = `decoding="async" ${newAttrs}`;
    }
    return `<img ${newAttrs}>`;
  });

  // 3. Add preload="none" or preload="metadata" to hero videos if not present
  content = content.replace(/<video\s+([^>]*?)>/gi, (match, attrs) => {
    let newAttrs = attrs;
    if (!/preload=/i.test(newAttrs)) {
      newAttrs = `preload="metadata" ${newAttrs}`;
    }
    return `<video ${newAttrs}>`;
  });

  fs.writeFileSync(filePath, content, 'utf-8');
  console.log(`Optimized ${filePath}`);
}

optimizeHtml(path.join(__dirname, '../index.html'));
optimizeHtml(path.join(__dirname, '../scratch/add_all_products_sections.js'));
