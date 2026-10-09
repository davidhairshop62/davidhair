const fs = require('fs');
const path = require('path');
const { Client } = require('ssh2');
const { Server } = require('@modelcontextprotocol/sdk/server/index.js');
const { StdioServerTransport } = require('@modelcontextprotocol/sdk/server/stdio.js');
const { CallToolRequestSchema, ListToolsRequestSchema } = require('@modelcontextprotocol/sdk/types.js');

const SSH_CONFIG = {
  host: '187.127.96.101',
  port: 22,
  username: 'root',
  readyTimeout: 20000
};

function getSSHClient() {
  return new Promise((resolve, reject) => {
    const conn = new Client();
    conn.on('ready', () => resolve(conn));
    conn.on('error', (err) => reject(err));

    const keyPath = path.join(process.env.USERPROFILE || 'C:\\Users\\david', '.ssh', 'id_ed25519');
    let opts = { ...SSH_CONFIG };
    if (fs.existsSync(keyPath)) {
      opts.privateKey = fs.readFileSync(keyPath);
    } else {
      opts.password = 'Gudangrambut5535';
    }
    conn.connect(opts);
  });
}

const server = new Server(
  { name: 'ssh-mcp-server', version: '1.0.0' },
  { capabilities: { tools: {} } }
);

server.setRequestHandler(ListToolsRequestSchema, async () => {
  return {
    tools: [
      {
        name: 'ssh_exec',
        description: 'Execute shell commands on remote root@187.127.96.101',
        inputSchema: {
          type: 'object',
          properties: {
            command: { type: 'string', description: 'Command to run on remote server' }
          },
          required: ['command']
        }
      },
      {
        name: 'ssh_upload',
        description: 'Upload a local file to remote server /var/www/davidhair',
        inputSchema: {
          type: 'object',
          properties: {
            localPath: { type: 'string', description: 'Local relative or absolute file path' },
            remotePath: { type: 'string', description: 'Remote target path (defaults to /var/www/davidhair/<filename>)' }
          },
          required: ['localPath']
        }
      },
      {
        name: 'ssh_list_dir',
        description: 'List contents of a directory on remote server',
        inputSchema: {
          type: 'object',
          properties: {
            path: { type: 'string', description: 'Remote directory path (default: /var/www/davidhair)' }
          }
        }
      }
    ]
  };
});

server.setRequestHandler(CallToolRequestSchema, async (request) => {
  const { name, arguments: args } = request.params;
  const conn = await getSSHClient();

  try {
    if (name === 'ssh_exec') {
      return await new Promise((resolve) => {
        conn.exec(args.command, (err, stream) => {
          if (err) {
            conn.end();
            return resolve({ content: [{ type: 'text', text: `Error: ${err.message}` }], isError: true });
          }
          let out = '', errOut = '';
          stream.on('data', (data) => { out += data.toString(); });
          stream.stderr.on('data', (data) => { errOut += data.toString(); });
          stream.on('close', (code) => {
            conn.end();
            resolve({
              content: [{ type: 'text', text: out + (errOut ? `\nSTDERR:\n${errOut}` : '') }]
            });
          });
        });
      });
    }

    if (name === 'ssh_upload') {
      const localFilePath = path.isAbsolute(args.localPath)
        ? args.localPath
        : path.join(process.cwd(), args.localPath);

      const targetFilename = path.basename(localFilePath);
      const remoteFilePath = args.remotePath || `/var/www/davidhair/${targetFilename}`;

      return await new Promise((resolve) => {
        conn.sftp((err, sftp) => {
          if (err) {
            conn.end();
            return resolve({ content: [{ type: 'text', text: `SFTP Error: ${err.message}` }], isError: true });
          }
          const readStream = fs.createReadStream(localFilePath);
          const writeStream = sftp.createWriteStream(remoteFilePath);

          writeStream.on('close', () => {
            conn.end();
            resolve({ content: [{ type: 'text', text: `Successfully uploaded ${localFilePath} to ${remoteFilePath}` }] });
          });
          writeStream.on('error', (wErr) => {
            conn.end();
            resolve({ content: [{ type: 'text', text: `Upload Error: ${wErr.message}` }], isError: true });
          });

          readStream.pipe(writeStream);
        });
      });
    }

    if (name === 'ssh_list_dir') {
      const targetPath = args.path || '/var/www/davidhair';
      return await new Promise((resolve) => {
        conn.exec(`ls -la "${targetPath}"`, (err, stream) => {
          if (err) {
            conn.end();
            return resolve({ content: [{ type: 'text', text: `Error: ${err.message}` }], isError: true });
          }
          let out = '';
          stream.on('data', (data) => { out += data.toString(); });
          stream.on('close', () => {
            conn.end();
            resolve({ content: [{ type: 'text', text: out }] });
          });
        });
      });
    }

    conn.end();
    throw new Error(`Unknown tool: ${name}`);
  } catch (error) {
    conn.end();
    return { content: [{ type: 'text', text: `Execution failed: ${error.message}` }], isError: true };
  }
});

async function run() {
  const transport = new StdioServerTransport();
  await server.connect(transport);
}

run().catch((err) => {
  console.error("Fatal MCP Server Error:", err);
  process.exit(1);
});
