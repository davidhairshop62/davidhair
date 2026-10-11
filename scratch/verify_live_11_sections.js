const https = require('https');

https.get('https://davidhair.atozhair.shop?nocache=' + Date.now(), res => {
  let data = '';
  res.on('data', chunk => data += chunk);
  res.on('end', () => {
    console.log('HTTP Status:', res.statusCode);
    const checks = [
      'Human Hair Wig',
      'Synthetic Hair Wig',
      'Pin Point Hair Link',
      'Mini Hair Filler',
      'Hair Topper',
      'Hair Contacts',
      'Men\'s Hair System',
      'Tape-In Hair Extension',
      'Fusion Hair Extensions',
      'Weaving Hair Extension',
      'Wig Making & Hair Extensionist Training Course'
    ];
    checks.forEach(c => console.log(c + ':', data.includes(c)));
  });
});
