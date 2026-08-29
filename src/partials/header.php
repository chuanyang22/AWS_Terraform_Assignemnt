<?php
$loggedIn = current_user_id() !== null;
$currentPage = basename($_SERVER['PHP_SELF']);
function nav_active($page, $current) {
    return $page === $current ? ' active' : '';
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<script>
(function () {
    var savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark' || savedTheme === 'light') {
        document.documentElement.setAttribute('data-theme', savedTheme);
    }
})();
</script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?= htmlspecialchars($pageDescription ?? 'Book campus sports facilities online - badminton, futsal and squash courts, available in real time.') ?>">
<title><?= htmlspecialchars($pageTitle ?? 'Sports Facility Booking') ?></title>
<link rel="icon" type="image/png" href="assets/favicon.png">
<link rel="stylesheet" href="style.css?v=<?= @filemtime(__DIR__ . '/../style.css') ?>">
</head>
<body class="dashboard-body">

<div class="layout-wrapper">
    <aside class="sidebar" id="sidebar">
        <a class="sidebar-brand" href="index.php">
            <img src="assets/tarumt-logo.png" alt="TAR UMT" class="brand-logo">
            <span class="sidebar-brand-text">Sports Booking</span>
        </a>
        <nav class="sidebar-nav">
            <a href="index.php" class="<?= trim(nav_active('index.php', $currentPage)) ?>">
                <span class="sidebar-icon">??</span><span class="sidebar-text">Home</span>
            </a>
            <a href="facilities.php" class="<?= trim(nav_active('facilities.php', $currentPage)) ?>">
                <span class="sidebar-icon">???</span><span class="sidebar-text">Facilities</span>
            </a>
            <a href="schedule.php" class="<?= trim(nav_active('schedule.php', $currentPage)) ?>">
                <span class="sidebar-icon">??</span><span class="sidebar-text">Schedule</span>
            </a>
            <a href="testimonials.php" class="<?= trim(nav_active('testimonials.php', $currentPage)) ?>">
                <span class="sidebar-icon">??</span><span class="sidebar-text">Testimonials</span>
            </a>
            <a href="about.php" class="<?= trim(nav_active('about.php', $currentPage)) ?>">
                <span class="sidebar-icon">??</span><span class="sidebar-text">About</span>
            </a>
            <a href="contact.php" class="<?= trim(nav_active('contact.php', $currentPage)) ?>">
                <span class="sidebar-icon">??</span><span class="sidebar-text">Contact</span>
            </a>
        </nav>
    </aside>

    <div class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <button id="sidebar-toggle" class="sidebar-toggle" aria-label="Toggle Sidebar">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
                </button>
                <div id="google_translate_element" style="display:inline-block; margin-left: 15px; vertical-align: middle;"></div>
            </div>
            <div class="topbar-right">
                <button id="theme-toggle" class="theme-toggle" type="button" aria-label="Toggle dark mode">&#9728;</button>
                <?php if ($loggedIn): ?>
                <div class="user-menu">
                    <button type="button" class="nav-user user-menu-trigger" aria-haspopup="true" aria-expanded="false">
                        <span class="user-avatar"><?= htmlspecialchars(mb_strtoupper(mb_substr(current_user_name(), 0, 1))) ?></span> 
                        <span class="user-name">Hi, <?= htmlspecialchars(current_user_name()) ?></span>
                    </button>
                    <div class="user-menu-dropdown">
                        <a href="account.php">My Account</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
                <?php else: ?>
                <a href="login.php" class="btn-login">Login</a>
                <a href="register.php" class="btn-register">Register</a>
                <?php endif; ?>
            </div>
        </header>
        <main class="container">
