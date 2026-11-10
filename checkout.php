<?php
require_once 'dbcon/dbcon.php';
require_once 'includes/helpers.php';
session_start();
require_login('');

$profile = current_profile($conn);
if (!$profile) {
    redirect('authentification/profil.php');
}

$stmt = $conn->prepare('
    SELECT p.*, pa.quantite AS quantite_panier
    FROM panier pa
    JOIN produit p ON p.idproduit = pa.idproduit
    WHERE pa.idprofil = ?
');
$stmt->execute([(int) $profile['idprofil']]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (!$items) {
    redirect('panier/panier.php');
}

$subtotal = 0;
foreach ($items as $item) {
    $subtotal += (float) $item['prix'] * (int) $item['quantite_panier'];
}
$shipping = 50;
$total = $subtotal + $shipping;

if (isset($_POST['commander'])) {
    $adresse = trim($_POST['adresse'] ?? '');
    $paiement = trim($_POST['paiement'] ?? 'Paiement a la livraison');
    if ($adresse === '') {
        $adresse = $profile['adresse'] . ', ' . $profile['ville'];
    }

    $conn->beginTransaction();
    try {
        $stmt = $conn->prepare('INSERT INTO commande (idprofil, statut, total, livraison, adresse_livraison, mode_paiement) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([(int) $profile['idprofil'], 'En attente', $total, $shipping, $adresse, $paiement]);
        $idcommande = (int) $conn->lastInsertId();

        $line = $conn->prepare('INSERT INTO ligne_commande (idcommande, idproduit, nomproduit, prix, quantite) VALUES (?, ?, ?, ?, ?)');
        $stock = $conn->prepare('UPDATE produit SET quantite = GREATEST(quantite - ?, 0) WHERE idproduit = ?');
        foreach ($items as $item) {
            $line->execute([$idcommande, (int) $item['idproduit'], $item['nomproduit'], (float) $item['prix'], (int) $item['quantite_panier']]);
            $stock->execute([(int) $item['quantite_panier'], (int) $item['idproduit']]);
        }

        $clear = $conn->prepare('DELETE FROM panier WHERE idprofil = ?');
        $clear->execute([(int) $profile['idprofil']]);
        $conn->commit();
        redirect('mes-commandes.php?success=1');
    } catch (Exception $e) {
        $conn->rollBack();
        $error = "Impossible de valider la commande.";
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Checkout</title>
  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/shop-pro.css">
</head>
<body class="shop-page">
<nav class="navbar navbar-expand-lg shop-nav sticky-top"><div class="container"><a class="navbar-brand" href="index.php">Tees Maroc</a><div class="d-flex gap-2"><a class="btn btn-outline-light" href="boutique.php">Boutique</a><a class="btn btn-light" href="panier/panier.php">Panier</a></div></div></nav>
<header class="page-hero"><div class="container"><span class="eyebrow">Checkout</span><h1>Finaliser la commande</h1><p>Confirme l’adresse, le paiement et le recapitulatif avant validation.</p></div></header>
<main class="container py-5">
  <?php if (isset($error)) { ?><div class="alert alert-danger"><?php echo h($error); ?></div><?php } ?>
  <div class="row g-4">
    <div class="col-lg-7">
      <form method="post" class="soft-panel p-4">
        <h2 class="h4 mb-3">Livraison</h2>
        <div class="mb-3">
          <label class="form-label">Adresse de livraison</label>
          <textarea name="adresse" class="form-control" rows="4" required><?php echo h($profile['adresse'] . ', ' . $profile['ville'] . ', ' . $profile['pays']); ?></textarea>
        </div>
        <div class="mb-4">
          <label class="form-label">Mode de paiement</label>
          <select name="paiement" class="form-select">
            <option>Paiement a la livraison</option>
            <option>Carte bancaire - simulation</option>
            <option>Virement bancaire</option>
          </select>
        </div>
        <button name="commander" class="btn btn-dark btn-lg w-100">Confirmer la commande</button>
      </form>
    </div>
    <div class="col-lg-5">
      <div class="summary-card">
        <h2 class="h4 mb-3">Recapitulatif</h2>
        <?php foreach ($items as $item) { ?>
          <div class="d-flex justify-content-between border-bottom py-2">
            <span><?php echo h($item['nomproduit']); ?> x <?php echo (int) $item['quantite_panier']; ?></span>
            <strong><?php echo money((float) $item['prix'] * (int) $item['quantite_panier']); ?></strong>
          </div>
        <?php } ?>
        <div class="d-flex justify-content-between mt-3"><span>Sous-total</span><strong><?php echo money($subtotal); ?></strong></div>
        <div class="d-flex justify-content-between"><span>Livraison</span><strong><?php echo money($shipping); ?></strong></div>
        <hr>
        <div class="d-flex justify-content-between fs-5"><span>Total</span><strong><?php echo money($total); ?></strong></div>
      </div>
    </div>
  </div>
</main>
</body>
</html>
