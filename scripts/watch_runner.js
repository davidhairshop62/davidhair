const { spawn } = require('child_process');
const path = require('path');

const isWin = process.platform === 'win32';
const script = isWin
  ? path.join(__dirname, 'auto_sync_daemon.js')
  : path.join(__dirname, 'watch_sync.js');

const child = spawn(process.execPath, [script], { stdio: 'inherit' });
child.on('exit', (code) => process.exit(code || 0));
