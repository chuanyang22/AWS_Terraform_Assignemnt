<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use Google\Authenticator\GoogleAuthenticator;

if (!isset($_SESSION['pending_2fa_user_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code'] ?? '');
    if (empty($code)) {
        $error = 'Please enter the 6-digit code.';
    } else {
        $stmt = $conn->prepare('SELECT id, two_factor_secret FROM users WHERE id = ?');
        $stmt->bind_param('i', $_SESSION['pending_2fa_user_id']);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user) {
            $g = new GoogleAuthenticator();
            if ($g->checkCode($user['two_factor_secret'], $code)) {
                $_SESSION['user_id'] = $user['id'];
                unset($_SESSION['pending_2fa_user_id']);
                header('Location: index.php');
                exit;
            } else {
                $error = 'Invalid authentication code.';
            }
        } else {
            $error = 'User not found.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang=""en"">
<head>
    <meta charset=""UTF-8"">
    <title>Two-Factor Authentication - Sport Facility Bookings</title>
    <link rel=""stylesheet"" href=""style.css"">
</head>
<body>
<?php include __DIR__ . '/partials/header.php'; ?>

<main class=""main-content"">
    <div class=""container"">
        <h2>Two-Factor Authentication</h2>
        <?php if ($error): ?>
            <div class=""notification error""><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <p>Please open your Google Authenticator app and enter the 6-digit code for your account.</p>

        <form method=""post"" class=""form-container"">
            <label>6-Digit Code
                <input type=""text"" name=""code"" inputmode=""numeric"" pattern=""[0-9]{6}"" required autofocus>
            </label>
            <button type=""submit"" class=""btn btn-primary"">Verify</button>
        </form>
    </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
