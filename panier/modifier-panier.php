<?php
require_once '../dbcon/dbcon.php';
require_once '../includes/helpers.php';
session_start();
require_login('../');

$profile = current_profile($conn);
if (!$profile) {
    redirect('../authentification/profil.php');
}

$idproduit = isset($_POST['idproduit']) ? (int) $_POST['idproduit'] : 0;
$quantite = isset($_POST['quantite']) ? (int) $_POST['quantite'] : 1;

if ($quantite <= 0) {
    $stmt = $conn->prepare('DELETE FROM panier WHERE idprofil = ? AND idproduit = ?');
    $stmt->execute([(int) $profile['idprofil'], $idproduit]);
} else {
    $stmt = $conn->prepare('UPDATE panier SET quantite = ? WHERE idprofil = ? AND idproduit = ?');
    $stmt->execute([$quantite, (int) $profile['idprofil'], $idproduit]);
}

redirect('panier.php');
?>
