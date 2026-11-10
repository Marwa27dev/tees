<?php
require_once '../dbcon/dbcon.php';
require_once '../includes/helpers.php';
session_start();
require_login('../');

$profile = current_profile($conn);
if (!$profile) {
    redirect('../authentification/profil.php');
}

$idproduit = isset($_GET['idproduit']) ? (int) $_GET['idproduit'] : 0;
$stmt = $conn->prepare('DELETE FROM panier WHERE idprofil = ? AND idproduit = ?');
$stmt->execute([(int) $profile['idprofil'], $idproduit]);

redirect('panier.php');
?>
