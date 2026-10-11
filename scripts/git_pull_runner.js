const { spawn } = require('child_process');

console.log('==========================================================');
console.log('📥 [Git Pull] Pulling latest changes from GitHub origin/main...');
console.log('==========================================================');

const child = spawn('git', ['pull', 'origin', 'main'], { stdio: 'inherit', shell: true });
child.on('exit', (code) => {
  if (code === 0) {
    console.log('==========================================================');
    console.log('✅ [Git Pull Complete]');
    console.log('==========================================================');
  }
  process.exit(code || 0);
});
