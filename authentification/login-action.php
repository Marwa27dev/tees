<?php
require_once '../dbcon/dbcon.php';

if (isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($password, $row['password'])) {
        session_start();
        $_SESSION['username'] = $row['username'];
        $_SESSION['iduser'] = $row['iduser'];
        $_SESSION['role'] = $row['role'];

        if ($row['role'] === 'client') {
            $stmt = $conn->prepare('SELECT * FROM profil WHERE iduser = ?');
            $stmt->execute([(int) $row['iduser']]);
            redirect_if_needed($stmt->fetch(PDO::FETCH_ASSOC) ? '../index.php' : 'profil.php');
        }

        if ($row['role'] === 'admin') {
            redirect_if_needed('../admin/admin.php');
        }
    }

    $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Email ou mot de passe incorrect</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    include 'login.php';
}

function redirect_if_needed($path) {
    header("Location: $path");
    exit;
}
?>
