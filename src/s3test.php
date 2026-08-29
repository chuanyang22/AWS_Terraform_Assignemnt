<?php
require 'config.php';
require 'helpers.php';
header('Content-Type: text/plain');

echo "Bucket: " . AWS_S3_BUCKET . "\n";
echo "Region: " . AWS_S3_REGION . "\n";

list($url, $error) = s3_put_object('test-upload.txt', 'hello world', 'text/plain');
if ($error) {
    echo "ERROR: " . $error . "\n";
} else {
    echo "SUCCESS: " . $url . "\n";
}
