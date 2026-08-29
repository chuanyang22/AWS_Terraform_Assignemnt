<?php
require 'config.php';
require 'auth.php';
require 'helpers.php';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $id_number = trim($_POST['id_number'] ?? '');
    $date_of_birth = trim($_POST['date_of_birth'] ?? '');
    $new_password = $_POST['new_password'] ?? '';

    if ($email === '' || $id_number === '' || $date_of_birth === '' || $new_password === '') {
        $error = 'All fields are required.';
    } elseif (strlen($new_password) < 6) {
        $error = 'New password must be at least 6 characters.';
    } else {
        $stmt = $conn->prepare('SELECT id FROM users WHERE email = ? AND id_number = ? AND date_of_birth = ?');
        $stmt->bind_param('sss', $email, $id_number, $date_of_birth);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user) {
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $update = $conn->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
            $update->bind_param('si', $password_hash, $user['id']);
            $update->execute();
            $update->close();
            
            $success = 'Password successfully reset! You can now <a href="login.php">login</a>.';
        } else {
            $error = 'Account not found or verification details are incorrect.';
        }
    }
}

$pageTitle = 'Forgot Password';
require 'partials/header.php';
?>
<div class="auth-card">
<h1>Reset Password</h1>
<?php if ($error): ?><p class="alert alert-error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<?php if ($success): ?><p class="alert alert-success"><?= $success ?></p><?php else: ?>
<p style="margin-bottom: 20px;">Enter your email, student ID, and date of birth to verify your identity.</p>
<form method="post" action="forgot_password.php">
<label>Email <span class="required-mark">*</span> <input type="email" name="email" required></label>
<label>Student/Staff ID Number <span class="required-mark">*</span> <input type="text" name="id_number" required></label>
<label>Date of Birth <span class="required-mark">*</span> <input type="date" name="date_of_birth" required></label>
<label>New Password <span class="required-mark">*</span>
<div class="password-field">
<input type="password" name="new_password" required minlength="6">
<button type="button" class="password-toggle" tabindex="-1" aria-label="Show password"></button>
</div>
</label>
<button type="submit">Reset Password</button>
</form>
<?php endif; ?>
<p>Remembered your password? <a href="login.php">Login here</a></p>
</div>
<?php require 'partials/footer.php'; ?>

