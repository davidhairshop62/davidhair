const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const targets = [];

function scanDir(dir) {
  if (!fs.existsSync(dir)) return;
  fs.readdirSync(dir).forEach(file => {
    const fullPath = path.join(dir, file);
    const stat = fs.statSync(fullPath);
    if (stat.isDirectory()) {
      scanDir(fullPath);
    } else if (/\.(png|jpe?g)$/i.test(file)) {
      targets.push(fullPath);
    }
  });
}

scanDir(path.join(__dirname, '../images'));
scanDir(path.join(__dirname, '../videos'));

console.log(`Found ${targets.length} images to convert to WebP.`);

let totalBefore = 0;
let totalAfter = 0;

for (const imgPath of targets) {
  const statBefore = fs.statSync(imgPath);
  totalBefore += statBefore.size;

  const webpPath = imgPath.replace(/\.(png|jpe?g)$/i, '.webp');
  try {
    execSync(`cwebp -q 82 "${imgPath}" -o "${webpPath}" -quiet`);
    const statAfter = fs.statSync(webpPath);
    totalAfter += statAfter.size;
    const pct = (((statBefore.size - statAfter.size) / statBefore.size) * 100).toFixed(1);
    console.log(`✓ ${path.relative(path.join(__dirname, '..'), imgPath)}: ${(statBefore.size/1024).toFixed(0)}KB -> ${(statAfter.size/1024).toFixed(0)}KB (-${pct}%)`);
  } catch (err) {
    console.error(`Error converting ${imgPath}:`, err.message);
  }
}

console.log('========================================================');
console.log(`Total Before: ${(totalBefore / 1024 / 1024).toFixed(2)} MB`);
console.log(`Total After:  ${(totalAfter / 1024 / 1024).toFixed(2)} MB`);
console.log(`Saved:        ${(((totalBefore - totalAfter) / totalBefore) * 100).toFixed(1)}% reduction!`);
console.log('========================================================');
