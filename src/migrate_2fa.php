<?php
require_once __DIR__ . '/config.php';

try {
    $conn->query("ALTER TABLE users ADD COLUMN two_factor_secret VARCHAR(255) NULL AFTER date_of_birth");
    $conn->query("ALTER TABLE users ADD COLUMN two_factor_enabled TINYINT(1) NOT NULL DEFAULT 0 AFTER two_factor_secret");
    echo "Migration successful!";
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage();
}
