<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support Ticketing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
<header class="top-bar">
    <div class="logo">Helpdesk<span>Lite</span></div>
    <nav class="nav-links" aria-label="Main navigation">
        <a href="index.php">Tickets</a>
        <a href="ticket_form.php">Submit Ticket</a>
    </nav>
</header>
<main class="container">
<?php if (!empty($_SESSION['flash'])): ?>
    <p class="flash"><?php echo e($_SESSION['flash']); unset($_SESSION['flash']); ?></p>
<?php endif; ?>
