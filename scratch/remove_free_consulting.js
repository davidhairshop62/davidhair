const fs = require('fs');

let html = fs.readFileSync('index.html', 'utf8');
const originalLength = html.length;

// Find and remove desktop header Free Consulting button
const btn1Match = html.match(/[ \t]*<!-- Free Consulting Primary CTA -->\r?\n[ \t]*<button @click="isBookingModalOpen = true"[\s\S]*?<span>Free Consulting<\/span>\r?\n[ \t]*<\/button>\r?\n?/);
if (btn1Match) {
    console.log('Found desktop Free Consulting button, removing...');
    html = html.replace(btn1Match[0], '');
} else {
    console.warn('Desktop Free Consulting button NOT found!');
}

// Find and remove mobile drawer Free Consulting button
const btn2Match = html.match(/[ \t]*<button @click="isBookingModalOpen = true; mobileMenuOpen = false;"[\s\S]*?<span>Free Consulting<\/span>\r?\n[ \t]*<\/button>\r?\n?/);
if (btn2Match) {
    console.log('Found mobile drawer Free Consulting button, removing...');
    html = html.replace(btn2Match[0], '');
} else {
    console.warn('Mobile drawer Free Consulting button NOT found!');
}

if (html.length < originalLength) {
    fs.writeFileSync('index.html', html, 'utf8');
    console.log('Successfully updated index.html! New length:', html.length);
} else {
    console.error('No changes were made to index.html.');
}
