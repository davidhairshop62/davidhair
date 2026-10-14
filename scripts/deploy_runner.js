const { spawn } = require('child_process');
const path = require('path');

const isWin = process.platform === 'win32';
const args = process.argv.slice(2);

if (isWin) {
  const child = spawn(process.execPath, [path.join(__dirname, 'deploy_win.js'), ...args], { stdio: 'inherit' });
  child.on('exit', (code) => process.exit(code || 0));
} else {
  const child = spawn('bash', [path.join(__dirname, 'deploy.sh'), ...args], { stdio: 'inherit' });
  child.on('exit', (code) => process.exit(code || 0));
}
