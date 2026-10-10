#!/bin/bash
# Auto Sync Script for David Hair
# Syncs local changes to GitHub and updates the production server (/var/www/davidhair)

COMMIT_MSG="${1:-Auto sync update $(date '+%Y-%m-%d %H:%M:%S')}"

echo "========================================="
echo "1. Staging and committing local changes..."
echo "========================================="
git add .
if git diff-index --quiet HEAD --; then
    echo "No local changes to commit."
else
    git commit -m "$COMMIT_MSG"
fi

echo "========================================="
echo "2. Pushing to GitHub (origin main)..."
echo "========================================="
git push origin main

echo "========================================="
echo "3. Updating production server (/var/www/davidhair)..."
echo "========================================="
ssh -i ~/.ssh/id_ed25519_davidhair root@187.127.96.101 "cd /var/www/davidhair && git pull origin main"

echo "========================================="
echo "Sync Complete!"
echo "========================================="
