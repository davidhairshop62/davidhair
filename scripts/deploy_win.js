const fs = require('fs');
const path = require('path');
const { Client } = require('ssh2');

const PROJECT_ROOT = path.resolve(__dirname, '..');
const REMOTE_ROOT = '/var/www/davidhair';
const CACHE_FILE = path.join(PROJECT_ROOT, '.deploy_cache.json');

const SSH_CONFIG = {
  host: '187.127.96.101',
  port: 22,
  username: 'root',
  readyTimeout: 30000,
  keepaliveInterval: 10000,
  keepaliveCountMax: 10
};

// Check for default SSH keys in Windows/User home
const possibleKeys = [
  path.join(process.env.USERPROFILE || '', '.ssh', 'id_ed25519'),
  path.join(process.env.USERPROFILE || '', '.ssh', 'id_rsa'),
  path.join(process.env.USERPROFILE || '', '.ssh', 'id_ed25519_davidhair'),
  path.join(process.env.HOME || '', '.ssh', 'id_ed25519_davidhair')
];

let selectedKey = null;
for (const k of possibleKeys) {
  if (k && fs.existsSync(k)) {
    try {
      selectedKey = fs.readFileSync(k);
      break;
    } catch (_) {}
  }
}

if (selectedKey) {
  SSH_CONFIG.privateKey = selectedKey;
} else {
  SSH_CONFIG.password = 'Gudangrambut5535';
}

function shouldIgnore(relPath) {
  const norm = relPath.replace(/\\/g, '/');
  if (norm === '.deploy_cache.json' || norm.endsWith('/.deploy_cache.json')) return true;
  if (norm.startsWith('.git') || norm.includes('/.git/')) return true;
  if (norm.startsWith('node_modules') || norm.includes('/node_modules/')) return true;
  if (norm.startsWith('.agents') || norm.includes('/.agents/')) return true;
  if (norm.startsWith('.vscode') || norm.includes('/.vscode/')) return true;
  if (norm.startsWith('uploads') || norm.includes('/uploads/')) return true;
  if (norm.startsWith('videos') || norm.includes('/videos/')) return true;
  if (norm === 'package.json' || norm === 'package-lock.json') return true;
  if (norm === '.htaccess') return false;
  if (path.basename(norm).startsWith('.')) return true;
  if (norm === 'images' || norm.startsWith('images/')) return false;
  return false;
}

function getAllFiles(dir, baseDir = dir) {
  let results = [];
  const list = fs.readdirSync(dir);
  for (const item of list) {
    const fullPath = path.join(dir, item);
    const relPath = path.relative(baseDir, fullPath);
    if (shouldIgnore(relPath)) continue;

    const stat = fs.statSync(fullPath);
    if (stat.isDirectory()) {
      results = results.concat(getAllFiles(fullPath, baseDir));
    } else {
      results.push({
        fullPath,
        relPath: relPath.replace(/\\/g, '/'),
        size: stat.size,
        mtimeMs: Math.round(stat.mtimeMs)
      });
    }
  }
  return results;
}

async function deploy() {
  const startTime = Date.now();
  const isForce = process.argv.includes('--force') || process.argv.includes('-f') || process.argv.includes('--all');

  let cache = {};
  if (!isForce && fs.existsSync(CACHE_FILE)) {
    try {
      cache = JSON.parse(fs.readFileSync(CACHE_FILE, 'utf8'));
    } catch (_) {
      cache = {};
    }
  }

  const allFiles = getAllFiles(PROJECT_ROOT);
  
  // Filter changed or new files
  const filesToUpload = allFiles.filter(file => {
    if (isForce) return true;
    const cached = cache[file.relPath];
    if (!cached) return true;
    return cached.size !== file.size || Math.abs(cached.mtimeMs - file.mtimeMs) > 1000;
  });

  console.log('==========================================================');
  console.log('🚀 [David Hair Fast Deploy] ' + (isForce ? '(FORCE FULL SYNC)' : '(SMART INCREMENTAL SYNC)'));
  console.log(`   Target: root@187.127.96.101:${REMOTE_ROOT}`);
  console.log('==========================================================');

  if (filesToUpload.length === 0) {
    console.log(`⚡ All ${allFiles.length} files are up to date! Nothing to upload.`);
    console.log('👉 Verification URL: https://davidhair.atozhair.shop');
    console.log('==========================================================');
    process.exit(0);
  }

  console.log(`📦 Changes detected: ${filesToUpload.length} file(s) to upload (${allFiles.length - filesToUpload.length} up-to-date skipped).`);

  const conn = new Client();
  conn.on('ready', () => {
    conn.sftp(async (err, sftp) => {
      if (err) {
        console.error('❌ SFTP Error:', err.message);
        conn.end();
        process.exit(1);
      }

      // Collect unique remote directories needed
      const uniqueDirs = new Set();
      for (const f of filesToUpload) {
        const dir = path.dirname(`${REMOTE_ROOT}/${f.relPath}`).replace(/\\/g, '/');
        if (dir !== REMOTE_ROOT) uniqueDirs.add(dir);
      }

      if (uniqueDirs.size > 0) {
        const dirCmd = Array.from(uniqueDirs).map(d => `"${d}"`).join(' ');
        await new Promise((res) => {
          conn.exec(`mkdir -p ${dirCmd}`, () => res());
        });
      }

      const CONCURRENCY = 5;
      let currentIndex = 0;
      let successCount = 0;
      let failCount = 0;

      async function worker() {
        while (currentIndex < filesToUpload.length) {
          const index = currentIndex++;
          const file = filesToUpload[index];
          const remoteFilePath = `${REMOTE_ROOT}/${file.relPath}`;

          await new Promise((resolve) => {
            sftp.fastPut(file.fullPath, remoteFilePath, { concurrency: 2, chunkSize: 32768 }, (err) => {
              if (err) {
                console.error(`\n❌ Failed to upload ${file.relPath}:`, err.message);
                failCount++;
              } else {
                successCount++;
                cache[file.relPath] = { size: file.size, mtimeMs: file.mtimeMs };
                process.stdout.write(`\r📤 Uploaded: [${successCount}/${filesToUpload.length}] ${file.relPath.padEnd(30).substring(0, 30)}`);
              }
              resolve();
            });
          });
        }
      }

      const workers = Array.from({ length: Math.min(CONCURRENCY, filesToUpload.length) }, () => worker());
      await Promise.all(workers);

      // Clean up deleted files from cache
      const activeRelPaths = new Set(allFiles.map(f => f.relPath));
      for (const k of Object.keys(cache)) {
        if (!activeRelPaths.has(k)) delete cache[k];
      }

      // Save cache file
      try {
        fs.writeFileSync(CACHE_FILE, JSON.stringify(cache, null, 2), 'utf8');
      } catch (_) {}

      const duration = ((Date.now() - startTime) / 1000).toFixed(1);
      console.log('\n==========================================================');
      if (failCount === 0) {
        console.log(`✅ [Deploy Complete] Successfully uploaded ${successCount} file(s) in ${duration}s!`);
      } else {
        console.log(`⚠️ [Deploy Complete with Warnings] Success: ${successCount}, Failed: ${failCount} (${duration}s)`);
      }
      console.log('👉 Verification URL: https://davidhair.atozhair.shop');
      console.log('==========================================================');

      conn.end();
      process.exit(failCount === 0 ? 0 : 1);
    });
  });

  conn.on('error', (err) => {
    console.error('❌ SSH Connection Error:', err.message);
    process.exit(1);
  });

  conn.connect(SSH_CONFIG);
}

deploy();
