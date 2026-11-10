<?php
function h($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect($path) {
    header("Location: $path");
    exit;
}

function require_login($base = '') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['iduser'])) {
        redirect($base . 'authentification/login.php');
    }
}

function require_admin($base = '') {
    require_login($base);
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        redirect($base . 'index.php');
    }
}

function current_profile(PDO $conn) {
    if (!isset($_SESSION['iduser'])) {
        return null;
    }
    $stmt = $conn->prepare('SELECT * FROM profil WHERE iduser = ?');
    $stmt->execute([(int) $_SESSION['iduser']]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function cart_count(PDO $conn, $idprofil) {
    $stmt = $conn->prepare('SELECT COALESCE(SUM(quantite), 0) FROM panier WHERE idprofil = ?');
    $stmt->execute([(int) $idprofil]);
    return (int) $stmt->fetchColumn();
}

function money($amount) {
    return number_format((float) $amount, 2, ',', ' ') . ' MAD';
}
?>
