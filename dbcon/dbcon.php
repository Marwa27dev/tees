<?php
    $host = 'localhost';
    $dbname = 'ecommerce';
    $user = 'root';
    $password = '';

    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

    try {
        $conn = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        die("Impossible de se connecter a la base de donnees.");
    }
?>

