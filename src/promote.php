<?php
require_once __DIR__ . '/config.php';
\ = \->prepare("UPDATE users SET is_admin = 1 WHERE email = 'tiesw-wm25@student.tarc.edu.my'");
\->execute();
echo "Promoted tiesw-wm25@student.tarc.edu.my to admin. Rows affected: " . \->affected_rows;
