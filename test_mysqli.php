<?php
mysqli_report(MYSQLI_REPORT_OFF);

$conn = @new mysqli("fake-host.rds.amazonaws.com", "admin", "password", "db");
if ($conn->connect_error) {
    echo "FAKE HOST ERROR: " . $conn->connect_error . "\n";
}

$conn2 = @new mysqli("localhost", "admin", "password", "db");
if ($conn2->connect_error) {
    echo "LOCALHOST ERROR: " . $conn2->connect_error . "\n";
}

$conn3 = @new mysqli("", "admin", "password", "db");
if ($conn3->connect_error) {
    echo "EMPTY ERROR: " . $conn3->connect_error . "\n";
}

