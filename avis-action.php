<?php
require_once 'dbcon/dbcon.php';
require_once 'includes/helpers.php';
session_start();
require_login('');

$profile = current_profile($conn);
if (!$profile) {
    redirect('authentification/profil.php');
}

$idproduit = (int) ($_POST['idproduit'] ?? 0);
$note = min(5, max(1, (int) ($_POST['note'] ?? 5)));
$message = trim($_POST['message'] ?? '');

if ($idproduit && $message !== '') {
    $stmt = $conn->prepare('INSERT INTO avis_produit (idproduit, idprofil, note, message) VALUES (?, ?, ?, ?)');
    $stmt->execute([$idproduit, (int) $profile['idprofil'], $note, $message]);
}

redirect('produit.php?id=' . $idproduit);
?>
