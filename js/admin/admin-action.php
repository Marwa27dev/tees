<?php
require_once '../dbcon/dbcon.php';
require_once '../includes/helpers.php';
session_start();
require_admin('../');

if (isset($_POST['ajouter'])) {
    $nom = trim($_POST['nom'] ?? '');
    $quantite = max(0, (int) ($_POST['quantite'] ?? 0));
    $description = trim($_POST['description'] ?? '');
    $prix = max(0, (float) ($_POST['prix'] ?? 0));
    $categorie = (int) ($_POST['categorie'] ?? 0);
    $marque = trim($_POST['marque'] ?? 'Collection');
    $couleur = trim($_POST['couleur'] ?? 'Noir');
    $taille = trim($_POST['taille'] ?? 'Standard');
    $image = trim($_POST['image'] ?? 'product-item1.jpg');

    $stmt = $conn->prepare('
        INSERT INTO produit (nomproduit, imageproduit, quantite, description, prix, idcategorie, marque, couleur, taille)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ');
    $stmt->execute([$nom, $image, $quantite, $description, $prix, $categorie, $marque, $couleur, $taille]);
}

redirect('admin.php');
?>
