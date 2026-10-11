const path = require('path');
const { exec } = require('child_process');
const chokidar = require('chokidar');

const PROJECT_ROOT = path.resolve(__dirname, '..');
const DEPLOY_SCRIPT = path.join(PROJECT_ROOT, 'scripts', 'deploy.sh');

let syncTimeout = null;
let isDeploying = false;
let pendingDeploy = false;

function triggerDeploy(changedFile) {
  if (syncTimeout) clearTimeout(syncTimeout);

  syncTimeout = setTimeout(() => {
    if (isDeploying) {
      pendingDeploy = true;
      return;
    }

    isDeploying = true;
    const time = new Date().toLocaleTimeString();
    console.log(`\n[${time}] ⚡ Detected change: ${changedFile || 'files'}`);
    console.log(`[${time}] 🚀 Fast syncing to /var/www/davidhair via rsync...`);

    exec(`bash "${DEPLOY_SCRIPT}"`, (err, stdout, stderr) => {
      isDeploying = false;
      if (err) {
        console.error(`❌ Sync error:`, stderr || err.message);
      } else {
        console.log(`✅ Synced! Live: https://davidhair.atozhair.shop`);
      }

      if (pendingDeploy) {
        pendingDeploy = false;
        triggerDeploy('queued changes');
      }
    });
  }, 300); // 300ms debounce
}

console.log('=====================================================');
console.log('👀 David Hair Auto-Watch Sync started!');
console.log('   Any saved file will be uploaded to /var/www/davidhair instantly (~1s).');
console.log('   URL: https://davidhair.atozhair.shop');
console.log('   (Press Ctrl+C to stop)');
console.log('=====================================================');

const watcher = chokidar.watch(PROJECT_ROOT, {
  ignored: [
    /(^|[\/\\])\../, // dotfiles/folders like .git, .DS_Store
    '**/node_modules/**',
    '**/uploads/**',
    '**/scripts/**'
  ],
  persistent: true,
  ignoreInitial: true
});

watcher
  .on('add', (p) => triggerDeploy(path.relative(PROJECT_ROOT, p)))
  .on('change', (p) => triggerDeploy(path.relative(PROJECT_ROOT, p)))
  .on('unlink', (p) => triggerDeploy(path.relative(PROJECT_ROOT, p)));
