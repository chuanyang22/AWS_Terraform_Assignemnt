<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$facilityPages = ['facilities.php', 'facility_create.php', 'facility_edit.php', 'court_create.php', 'court_edit.php'];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<script>
(function () {
    var saved = localStorage.getItem('theme');
    if (saved === 'dark' || saved === 'light') {
        document.documentElement.setAttribute('data-theme', saved);
    }
})();
</script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle ?? 'Admin - Sports Facility Booking') ?></title>
<link rel="icon" type="image/png" href="../assets/favicon.png">
<link rel="stylesheet" href="../style.css?v=<?= @filemtime(__DIR__ . '/../../style.css') ?>">
</head>
<body class="dashboard-body">

<div class="layout-wrapper">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-top">
            <button id="sidebar-toggle-inside" class="sidebar-toggle-btn" aria-label="Toggle Sidebar">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </button>
        </div>
        <nav class="sidebar-nav">
            <a href="facilities.php" class="<?= in_array($currentPage, $facilityPages) ? 'active' : '' ?>">
                <span class="sidebar-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg></span><span class="sidebar-text">Facilities</span>
            </a>
            <a href="schedule.php" class="<?= $currentPage === 'schedule.php' ? 'active' : '' ?>">
                <span class="sidebar-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span><span class="sidebar-text">Schedule</span>
            </a>
            <a href="bookings.php" class="<?= $currentPage === 'bookings.php' ? 'active' : '' ?>">
                <span class="sidebar-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/></svg></span><span class="sidebar-text">Bookings</span>
            </a>
            <a href="closures.php" class="<?= $currentPage === 'closures.php' ? 'active' : '' ?>">
                <span class="sidebar-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span><span class="sidebar-text">Closures</span>
            </a>
            <a href="testimonials.php" class="<?= $currentPage === 'testimonials.php' ? 'active' : '' ?>">
                <span class="sidebar-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></span><span class="sidebar-text">Testimonials</span>
            </a>
            <a href="messages.php" class="<?= $currentPage === 'messages.php' ? 'active' : '' ?>">
                <span class="sidebar-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></span><span class="sidebar-text">Messages</span>
            </a>
            <a href="users.php" class="<?= $currentPage === 'users.php' ? 'active' : '' ?>">
                <span class="sidebar-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></span><span class="sidebar-text">Users</span>
            </a>
        </nav>
    </aside>

    <div class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <button id="sidebar-toggle-outside" class="sidebar-toggle-btn mobile-only" aria-label="Toggle Sidebar">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
                </button>
                <a class="topbar-brand" href="facilities.php">
                    <img src="../assets/tarumt-logo.png" alt="TAR UMT" class="brand-logo">
                    <span class="topbar-brand-text">Admin Dashboard</span>
                </a>
            </div>
            <div class="topbar-right">
                <div id="google_translate_element" style="display:inline-block; margin-right: 15px; vertical-align: middle;"></div>
                <button id="theme-toggle" class="theme-toggle" type="button" aria-label="Toggle dark mode">&#9728;</button>
                <div class="user-menu">
                    <button type="button" class="nav-user user-menu-trigger" aria-haspopup="true" aria-expanded="false">
                        <span class="user-avatar"><?= htmlspecialchars(mb_strtoupper(mb_substr(current_user_name(), 0, 1))) ?></span> 
                        <span class="user-name">Hi, <?= htmlspecialchars(current_user_name()) ?></span>
                    </button>
                    <div class="user-menu-dropdown">
                        <a href="../index.php">Back to Site</a>
                        <a href="../account.php">My Account</a>
                        <a href="../logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </header>
        <main class="container">
