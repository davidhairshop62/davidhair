const fs = require('fs');
const path = require('path');
const chokidar = require('chokidar');
const { Client } = require('ssh2');

const LOCAL_ROOT = path.resolve('c:/Users/david/OneDrive/Desktop/davidhair');
const REMOTE_ROOT = '/var/www/davidhair';

const SSH_CONFIG = {
  host: '187.127.96.101',
  port: 22,
  username: 'root',
  readyTimeout: 30000,
  keepaliveInterval: 10000
};

const keyPath = path.join(process.env.USERPROFILE || 'C:\\Users\\david', '.ssh', 'id_ed25519');
if (fs.existsSync(keyPath)) {
  SSH_CONFIG.privateKey = fs.readFileSync(keyPath);
} else {
  SSH_CONFIG.password = 'Gudangrambut5535';
}

let sftpClient = null;
let sshConn = null;
let uploadQueue = [];
let isUploading = false;
let isConnecting = false;

function log(msg) {
  const time = new Date().toISOString().substring(11, 19);
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
      log('SFTP Channel Ready!');
      
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
      const readStream = fs.createReadStream(localFile);
      const writeStream = sftpClient.createWriteStream(remotePath);

      writeStream.on('close', () => {
        log(`Synced: ${relPath} -> ${remotePath}`);
        resolve();
      });

      writeStream.on('error', (err) => {
        log(`Error uploading ${relPath}: ${err.message}`);
        resolve();
      });

      readStream.pipe(writeStream);
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
  if (norm.endsWith('/package-lock.json') || norm.endsWith('/package.json')) return true;
  if (norm.includes('/scripts/') || norm.endsWith('/scripts')) return true;
  if (path.basename(norm).startsWith('.')) return true;
  return false;
}

async function start() {
  connectSSH();
  log(`Watching for local user files in ${LOCAL_ROOT}...`);

  const watcher = chokidar.watch(LOCAL_ROOT, {
    ignored: isIgnored,
    persistent: true,
    ignoreInitial: false
  });

  watcher
    .on('add', (filePath) => {
      log(`Detected file: ${path.relative(LOCAL_ROOT, filePath)}`);
      queueUpload(filePath);
    })
    .on('change', (filePath) => {
      log(`Detected change: ${path.relative(LOCAL_ROOT, filePath)}`);
      queueUpload(filePath);
    });
}

start();
