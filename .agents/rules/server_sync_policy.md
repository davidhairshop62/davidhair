# Server Sync & Git Policy

1. **Auto Web Server Update**:
   - Whenever any project file is added, edited, or updated locally, immediately upload it to the production server (`/var/www/davidhair`) using the MCP tool `ssh_upload`.
   - Always verify that the file was successfully transferred.

2. **Git Commit/Push Policy**:
   - **Do NOT perform git commit or git push automatically.**
   - The user will manage all Git operations (commit, push, branch management) manually.

3. **Live Verification URL**:
   - Always provide the direct live URL `https://davidhair.atozhair.shop` (or specific page like `/stock_manager.php`) so the user can easily verify and test the changes in their browser.
