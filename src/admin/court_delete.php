<?php
require '../config.php';
require '../auth.php';
require_admin();

$redirect = 'facilities.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];

    $stmt = $conn->prepare('SELECT facility_id FROM courts WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($row) {
        $redirect = 'facility_edit.php?id=' . $row['facility_id'];
    }

    $stmt = $conn->prepare('DELETE FROM courts WHERE id = ?');
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        $_SESSION['flash_error'] = 'Cannot delete this court: it still has bookings or closures referencing it.';
    }
    $stmt->close();
}

if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
    header('Content-Type: application/json');
    if (isset($_SESSION['flash_error'])) {
        $err = $_SESSION['flash_error'];
        unset($_SESSION['flash_error']);
        echo json_encode(['success' => false, 'error' => $err]);
    } else {
        echo json_encode(['success' => true]);
    }
    exit;
}

header('Location: ' . $redirect);
exit;
