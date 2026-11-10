<?php
require_once 'dbcon/dbcon.php';
require_once 'includes/helpers.php';
session_start();
require_login('');

$profile = current_profile($conn);
if (!$profile) {
    redirect('authentification/profil.php');
}

$idproduit = (int) ($_GET['idproduit'] ?? 0);
if ($idproduit) {
    $stmt = $conn->prepare('INSERT IGNORE INTO favoris (idprofil, idproduit) VALUES (?, ?)');
    $stmt->execute([(int) $profile['idprofil'], $idproduit]);
}

redirect('produit.php?id=' . $idproduit);
?>
