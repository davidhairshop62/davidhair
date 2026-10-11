const https = require('https');

https.get('https://davidhair.atozhair.shop?nocache=' + Date.now(), res => {
  let data = '';
  res.on('data', chunk => data += chunk);
  res.on('end', () => {
    console.log('HTTP Status:', res.statusCode);
    console.log('Products Dropdown exists:', data.includes('Products'));
    console.log('Order link exists:', data.includes('href="#builder"'));
    console.log('Stock link exists:', data.includes('href="#stock"'));
  });
});
