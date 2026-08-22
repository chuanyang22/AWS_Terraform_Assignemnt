<?php
// ALB / target-group health check endpoint.
// Returns 200 if the web server + PHP stack is up.
// Returns 503 if the database is unreachable (lets the ALB drain this
// instance without marking a brand-new instance as permanently dead just
// because user_data hasn't finished writing the DB credentials yet).
header('Content-Type: text/plain');

// Load environment variables from .env (same logic as config.php but
// without the fatal die() on connection failure).
$envFile = __DIR__ . '/.env';
if (is_readable($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key   = trim($key);
        $value = trim($value);
        if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
            (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
            $value = substr($value, 1, -1);
        }
        if (getenv($key) === false) {
            putenv("$key=$value");
        }
    }
}

$host   = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? $_SERVER['DB_HOST'] ?? '');
$user   = getenv('DB_USER') ?: ($_ENV['DB_USER'] ?? $_SERVER['DB_USER'] ?? '');
$pass   = getenv('DB_PASS') ?: ($_ENV['DB_PASS'] ?? $_SERVER['DB_PASS'] ?? '');
$dbname = getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? $_SERVER['DB_NAME'] ?? '');

if (empty($host)) {
    // Credentials not yet available (user_data still running) — report
    // degraded but don't crash so the instance isn't immediately replaced.
    http_response_code(503);
    echo "degraded: DB_HOST not set\n";
    exit;
}

mysqli_report(MYSQLI_REPORT_OFF);
$conn = @new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    http_response_code(503);
    echo "degraded: " . $conn->connect_error . "\n";
    exit;
}
$conn->close();

http_response_code(200);
echo "OK\n";
