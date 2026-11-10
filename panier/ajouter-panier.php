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
$quantite = isset($_POST['quantite']) ? max(1, (int) $_POST['quantite']) : 1;

$stmt = $conn->prepare('SELECT idproduit FROM produit WHERE idproduit = ?');
$stmt->execute([$idproduit]);
if (!$stmt->fetch()) {
    redirect('../boutique.php');
}

$stmt = $conn->prepare('
    INSERT INTO panier (idprofil, idproduit, quantite)
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE quantite = quantite + VALUES(quantite)
');
$stmt->execute([(int) $profile['idprofil'], $idproduit, $quantite]);

redirect('panier.php');
?>
