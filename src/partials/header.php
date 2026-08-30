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
        <div class="sidebar-top">
            <button id="sidebar-toggle-inside" class="sidebar-toggle-btn" aria-label="Toggle Sidebar">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
            </button>
        </div>
        <nav class="sidebar-nav">
            <a href="index.php" class="<?= trim(nav_active('index.php', $currentPage)) ?>">
                <span class="sidebar-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></span><span class="sidebar-text">Home</span>
            </a>
            <a href="facilities.php" class="<?= trim(nav_active('facilities.php', $currentPage)) ?>">
                <span class="sidebar-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg></span><span class="sidebar-text">Facilities</span>
            </a>
            <a href="schedule.php" class="<?= trim(nav_active('schedule.php', $currentPage)) ?>">
                <span class="sidebar-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span><span class="sidebar-text">Schedule</span>
            </a>
            <a href="testimonials.php" class="<?= trim(nav_active('testimonials.php', $currentPage)) ?>">
                <span class="sidebar-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></span><span class="sidebar-text">Testimonials</span>
            </a>
            <a href="about.php" class="<?= trim(nav_active('about.php', $currentPage)) ?>">
                <span class="sidebar-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg></span><span class="sidebar-text">About</span>
            </a>
            <a href="contact.php" class="<?= trim(nav_active('contact.php', $currentPage)) ?>">
                <span class="sidebar-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span><span class="sidebar-text">Contact</span>
            </a>
        </nav>
    </aside>

    <div class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <button id="sidebar-toggle-outside" class="sidebar-toggle-btn mobile-only" aria-label="Toggle Sidebar">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
                </button>
                <a class="topbar-brand" href="index.php">
                    <img src="assets/tarumt-logo.png" alt="TAR UMT" class="brand-logo">
                    <span class="topbar-brand-text">Sports Booking</span>
                </a>
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


