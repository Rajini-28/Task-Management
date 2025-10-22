1. Create the database using sql/create_db.sql (phpMyAdmin, MySQL CLI).
2. Update config.php with DB credentials.
3. Upload all files to your PHP-capable host (e.g., XAMPP, shared hosting).
4. Access index.php to sign up and try the app.
5. For GitHub Pages: GitHub Pages does not support PHP. If you want a shareable link via GitHub Pages, you'll need to host the backend elsewhere (free options: Render, Heroku (limited), Railway) or convert app to a static frontend + serverless backend.

Security & enhancements:
- Use HTTPS.
- Add CSRF tokens for state-changing endpoints.
- Add file uploads for attachments, tags, search, filters, recurring tasks.
