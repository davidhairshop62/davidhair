const fs = require('fs');
const path = require('path');
const { Client } = require('ssh2');

const PROJECT_ROOT = path.resolve(__dirname, '..');
const REMOTE_ROOT = '/var/www/davidhair';

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
    selectedKey = fs.readFileSync(k);
    break;
  }
}

if (selectedKey) {
  SSH_CONFIG.privateKey = selectedKey;
} else {
  SSH_CONFIG.password = 'Gudangrambut5535';
}

function shouldIgnore(relPath) {
  const norm = relPath.replace(/\\/g, '/');
  if (norm.startsWith('.git') || norm.includes('/.git/')) return true;
  if (norm.startsWith('node_modules') || norm.includes('/node_modules/')) return true;
  if (norm.startsWith('.agents') || norm.includes('/.agents/')) return true;
  if (norm.startsWith('.vscode') || norm.includes('/.vscode/')) return true;
  if (norm.startsWith('uploads') || norm.includes('/uploads/')) return true;
  if (norm.startsWith('scripts') && !norm.endsWith('.php')) return true;
  if (norm.startsWith('scratch') || norm.includes('/scratch/')) return true;
  if (norm.startsWith('videos') || norm.includes('/videos/')) return true; // Videos are uploaded once and large
  if (norm === 'images') return false; // Allow traversing images directory
  if (norm.startsWith('images/transformations')) return false; // Allow transformation images
  if (norm.startsWith('images')) return true; // Static images already on server
  if (norm === 'package.json' || norm === 'package-lock.json') return true;
  if (path.basename(norm).startsWith('.')) return true;
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
      results.push({ fullPath, relPath: relPath.replace(/\\/g, '/'), size: stat.size });
    }
  }
  return results;
}

async function deploy() {
  console.log('==========================================================');
  console.log('🚀 [David Hair Windows Deploy] Connecting to server...');
  console.log('   Target: root@187.127.96.101:' + REMOTE_ROOT);
  console.log('==========================================================');

  const files = getAllFiles(PROJECT_ROOT);
  console.log(`📦 Found ${files.length} project files to deploy.`);

  const conn = new Client();
  conn.on('ready', () => {
    conn.sftp(async (err, sftp) => {
      if (err) {
        console.error('❌ SFTP Error:', err.message);
        conn.end();
        process.exit(1);
      }

      const ensureDir = (remoteDirPath) => {
        return new Promise((res) => {
          conn.exec(`mkdir -p "${remoteDirPath}"`, () => res());
        });
      };

      const remoteDirsChecked = new Set();
      let successCount = 0;
      let failCount = 0;

      for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const remoteFilePath = `${REMOTE_ROOT}/${file.relPath}`;
        const remoteDir = path.dirname(remoteFilePath);

        if (!remoteDirsChecked.has(remoteDir)) {
          await ensureDir(remoteDir);
          remoteDirsChecked.add(remoteDir);
        }

        await new Promise((resolve) => {
          sftp.fastPut(file.fullPath, remoteFilePath, { concurrency: 2, chunkSize: 32768 }, (err) => {
            if (err) {
              console.error(`\n❌ Failed to upload ${file.relPath}:`, err.message);
              failCount++;
            } else {
              successCount++;
              process.stdout.write(`\r📤 Uploaded: [${successCount}/${files.length}] ${file.relPath.padEnd(30).substring(0, 30)}`);
            }
            resolve();
          });
        });
      }

      console.log('\n==========================================================');
      if (failCount === 0) {
        console.log(`✅ [Deploy Complete] Successfully uploaded ${successCount} files!`);
      } else {
        console.log(`⚠️ [Deploy Complete with Warnings] Success: ${successCount}, Failed: ${failCount}`);
      }
      console.log('👉 Verification URL: https://davidhair.atozhair.shop');
      console.log('==========================================================');

      conn.end();
      process.exit(0);
    });
  });

  conn.on('error', (err) => {
    console.error('❌ SSH Connection Error:', err.message);
    process.exit(1);
  });

  conn.connect(SSH_CONFIG);
}

deploy();
