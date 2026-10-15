#!/bin/bash
# High-speed direct server sync using rsync over SSH (takes ~1 sec)
# Excludes .git, uploads (server user uploads), node_modules, .DS_Store

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"

SSH_KEY="$HOME/.ssh/id_ed25519_davidhair"
SERVER_TARGET="root@187.127.96.101:/var/www/davidhair/"

echo "🚀 [Deploy] Syncing local files directly to /var/www/davidhair..."

rsync -avz \
  --exclude='.git/' \
  --exclude='.agents/' \
  --exclude='scratch/' \
  --exclude='.deploy_cache.json' \
  --exclude='.DS_Store' \
  --exclude='node_modules/' \
  --exclude='uploads/' \
  -e "ssh -i $SSH_KEY -o ConnectTimeout=10" \
  "$PROJECT_ROOT/" "$SERVER_TARGET"

if [ $? -eq 0 ]; then
  echo "=========================================================="
  echo "✅ [Deploy Complete] Server updated successfully!"
  echo "👉 Verification URL: https://davidhair.atozhair.shop"
  echo "=========================================================="
else
  echo "❌ [Deploy Failed] Please check SSH connection."
  exit 1
fi
