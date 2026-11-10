<?php
require_once '../dbcon/dbcon.php';
require_once '../includes/helpers.php';
session_start();
require_admin('../');

$idcommande = (int) ($_POST['idcommande'] ?? 0);
$statut = $_POST['statut'] ?? 'En attente';
$allowed = ['En attente', 'Validee', 'Expediee', 'Livree', 'Annulee'];
if (!in_array($statut, $allowed, true)) {
    $statut = 'En attente';
}

$stmt = $conn->prepare('UPDATE commande SET statut = ? WHERE idcommande = ?');
$stmt->execute([$statut, $idcommande]);

redirect('admin.php');
?>
