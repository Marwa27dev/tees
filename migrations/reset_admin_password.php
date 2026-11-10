<?php
require_once __DIR__ . '/../dbcon/dbcon.php';

$username = 'safaasafaa';
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ? AND role = 'admin'");
$stmt->execute([$hash, $username]);

echo "Admin password reset.\n";
?>
