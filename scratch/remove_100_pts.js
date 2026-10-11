const fs = require('fs');
let html = fs.readFileSync('index.html', 'utf8');

const target = '<span class="hidden md:inline-block text-[10px] bg-white/20 text-white px-1.5 py-0.5 rounded font-mono font-bold">+100 Pts</span>';

if (html.includes(target)) {
    // Remove the newline and indentation along with the target span
    const regex = new RegExp('\\r?\\n[ \\t]*' + target.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&'));
    if (regex.test(html)) {
        html = html.replace(regex, '');
    } else {
        html = html.replace(target, '');
    }
    html = html.replace('title="Join VIP Membership &amp; Receive 100 Welcome Points"', 'title="Join VIP Membership"');
    fs.writeFileSync('index.html', html, 'utf8');
    console.log('Successfully removed +100 Pts from SignUp button!');
} else {
    console.error('Target not found!');
}
