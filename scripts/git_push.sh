#!/bin/bash
# Manual Git Push Script
# To be executed once a day or whenever you want to backup/commit to GitHub

MSG="${1:-Manual update $(date '+%Y-%m-%d %H:%M:%S')}"

echo "=========================================================="
echo "📦 [Git Push] Staging and committing local changes..."
echo "=========================================================="
git add .
if git diff-index --quiet HEAD --; then
  echo "No changes to commit."
else
  git commit -m "$MSG"
fi

echo "=========================================================="
echo "🚀 [Git Push] Pushing to GitHub (origin main)..."
echo "=========================================================="
git push origin main

echo "=========================================================="
echo "✅ [Git Push Complete] GitHub is up to date."
echo "=========================================================="
