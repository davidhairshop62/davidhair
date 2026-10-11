const fs = require('fs');
const path = require('path');
const chokidar = require('chokidar');
const { Client } = require('ssh2');

const LOCAL_ROOT = path.resolve(__dirname, '..');
const REMOTE_ROOT = '/var/www/davidhair';

const SSH_CONFIG = {
  host: '187.127.96.101',
  port: 22,
  username: 'root',
  readyTimeout: 30000,
  keepaliveInterval: 10000
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

let sftpClient = null;
let sshConn = null;
let uploadQueue = [];
let isUploading = false;
let isConnecting = false;

function log(msg) {
  const time = new Date().toLocaleTimeString();
  console.log(`[${time}] [AUTO-SYNC] ${msg}`);
}

function connectSSH() {
  if (isConnecting) return;
  isConnecting = true;
  log('Connecting to SSH server 187.127.96.101...');

  const conn = new Client();
  conn.on('ready', () => {
    isConnecting = false;
    log('SSH Connected! Opening SFTP channel...');
    conn.sftp((err, sftp) => {
      if (err) {
        log(`SFTP Error: ${err.message}`);
        conn.end();
        return;
      }
      sshConn = conn;
      sftpClient = sftp;
      log('SFTP Ready! Real-time sync active (Save file -> instant upload).');
      log('Live URL: https://davidhair.atozhair.shop');
      
      conn.exec(`mkdir -p "${REMOTE_ROOT}"`, (mErr) => {
        if (mErr) log(`Warning creating remote root: ${mErr.message}`);
        processQueue();
      });
    });
  });

  conn.on('error', (err) => {
    log(`SSH Connection Error: ${err.message}`);
    isConnecting = false;
    sshConn = null;
    sftpClient = null;
    setTimeout(connectSSH, 5000);
  });

  conn.on('end', () => {
    log('SSH Session Ended. Reconnecting in 5s...');
    isConnecting = false;
    sshConn = null;
    sftpClient = null;
    setTimeout(connectSSH, 5000);
  });

  conn.connect(SSH_CONFIG);
}

function ensureRemoteDir(remoteDirPath) {
  return new Promise((resolve) => {
    if (!sshConn) return resolve(false);
    const posixPath = remoteDirPath.replace(/\\/g, '/');
    sshConn.exec(`mkdir -p "${posixPath}"`, (err) => {
      if (err) log(`Failed to mkdir ${posixPath}: ${err.message}`);
      resolve(!err);
    });
  });
}

async function processQueue() {
  if (isUploading || uploadQueue.length === 0) return;

  if (!sftpClient || !sshConn) {
    connectSSH();
    return;
  }

  isUploading = true;
  const localFile = uploadQueue.shift();

  try {
    if (!fs.existsSync(localFile)) {
      isUploading = false;
      return processQueue();
    }

    const relPath = path.relative(LOCAL_ROOT, localFile).replace(/\\/g, '/');
    const remotePath = `${REMOTE_ROOT}/${relPath}`;
    const remoteDir = path.dirname(remotePath);

    await ensureRemoteDir(remoteDir);

    await new Promise((resolve) => {
      if (!sftpClient) {
        log(`SFTP client disconnected, re-queueing ${relPath}`);
        uploadQueue.unshift(localFile);
        return resolve();
      }

      sftpClient.fastPut(localFile, remotePath, (err) => {
        if (err) {
          log(`Error uploading ${relPath}: ${err.message}`);
        } else {
          log(`⚡ Synced: ${relPath} -> ${remotePath}`);
          console.log(`   👉 Live: https://davidhair.atozhair.shop/${relPath === 'index.html' ? '' : relPath}`);
        }
        resolve();
      });
    });
  } catch (err) {
    log(`Sync error: ${err.message}`);
  }

  isUploading = false;
  processQueue();
}

function queueUpload(filePath) {
  if (!uploadQueue.includes(filePath)) {
    uploadQueue.push(filePath);
    processQueue();
  }
}

function isIgnored(testPath) {
  const norm = testPath.replace(/\\/g, '/');
  if (norm.includes('/node_modules/') || norm.endsWith('/node_modules')) return true;
  if (norm.includes('/.git/') || norm.endsWith('/.git')) return true;
  if (norm.includes('/.agents/') || norm.endsWith('/.agents')) return true;
  if (norm.includes('/.vscode/') || norm.endsWith('/.vscode')) return true;
  if (norm.includes('/uploads/') || norm.endsWith('/uploads')) return true;
  if (norm.endsWith('/package-lock.json') || norm.endsWith('/package.json')) return true;
  if (norm.includes('/scripts/') || norm.endsWith('/scripts')) return true;
  if (path.basename(norm).startsWith('.')) return true;
  return false;
}

console.log('=====================================================');
console.log('👀 David Hair Auto-Watch Sync (Windows / Node.js)');
console.log('   Server: root@187.127.96.101:/var/www/davidhair');
console.log('   Live:   https://davidhair.atozhair.shop');
console.log('   (Press Ctrl+C to stop)');
console.log('=====================================================');

connectSSH();
log(`Watching for file changes in ${LOCAL_ROOT}...`);

const watcher = chokidar.watch(LOCAL_ROOT, {
  ignored: isIgnored,
  persistent: true,
  ignoreInitial: true // Only sync on file changes/saves
});

watcher
  .on('add', (filePath) => {
    log(`Detected new file: ${path.relative(LOCAL_ROOT, filePath)}`);
    queueUpload(filePath);
  })
  .on('change', (filePath) => {
    log(`Detected save: ${path.relative(LOCAL_ROOT, filePath)}`);
    queueUpload(filePath);
  });
