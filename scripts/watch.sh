#!/bin/bash
# Real-time auto-sync watcher
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
node "$SCRIPT_DIR/watch_sync.js"
