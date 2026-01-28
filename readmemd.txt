Customer Support Ticketing System

Tech:
- PHP + MySQL (PDO, prepared statements, XSS escaping with htmlspecialchars)
- HTML5 semantic tags (header, main, section, nav, footer, table)
- CSS3 UI: assets/css/styles.css
- JavaScript Ajax: assets/js/main.js

Features:
- Users submit tickets (ticket_form.php)
- Admins view ticket and change status (ticket_view.php)
- Search by issue type, priority, date range, and keyword (index.php)
- Ajax: live status updates without reloading the page (ajax_update_status.php)

Setup:
1. Create `support_ticketing` database and run the SQL script to create `tickets` table. [file:1]
2. Configure DB credentials in config/db.php.
3. Place project in web root and open public/index.php in a browser.
