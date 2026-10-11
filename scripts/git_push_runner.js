const { spawnSync } = require('child_process');

const customMsg = process.argv.slice(2).join(' ').trim();
const nowStr = new Date().toISOString().replace('T', ' ').substring(0, 19);
const commitMsg = customMsg || `Manual update ${nowStr}`;

console.log('==========================================================');
console.log('📦 [Git Push] Staging local changes (git add .)...');
console.log('==========================================================');
spawnSync('git', ['add', '.'], { stdio: 'inherit', shell: true });

const statusResult = spawnSync('git', ['status', '--porcelain'], { encoding: 'utf-8', shell: true });
const hasChanges = statusResult.stdout && statusResult.stdout.trim().length > 0;

if (!hasChanges) {
  console.log('ℹ️  No changes to commit.');
} else {
  console.log(`📝 Committing: "${commitMsg}"`);
  spawnSync('git', ['commit', '-m', commitMsg], { stdio: 'inherit', shell: true });
}

console.log('==========================================================');
console.log('🚀 [Git Push] Pushing to GitHub origin/main...');
console.log('==========================================================');
const pushResult = spawnSync('git', ['push', 'origin', 'main'], { stdio: 'inherit', shell: true });

if (pushResult.status === 0) {
  console.log('==========================================================');
  console.log('✅ [Git Push Complete] GitHub is up to date.');
  console.log('==========================================================');
} else {
  console.error('❌ [Git Push Failed] Please check git output above.');
}
process.exit(pushResult.status || 0);
