<?php
require_once '../dbcon/dbcon.php';
require_once '../includes/helpers.php';
session_start();
require_login('../');

$profile = current_profile($conn);
if (!$profile) {
    redirect('../authentification/profil.php');
}

$stmt = $conn->prepare('
    SELECT p.*, pa.quantite AS quantite_panier
    FROM panier pa
    JOIN produit p ON p.idproduit = pa.idproduit
    WHERE pa.idprofil = ?
    ORDER BY p.nomproduit
');
$stmt->execute([(int) $profile['idprofil']]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$subtotal = 0;
foreach ($items as $item) {
    $subtotal += (float) $item['prix'] * (int) $item['quantite_panier'];
}
$shipping = $subtotal > 0 ? 50 : 0;
$total = $subtotal + $shipping;
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Panier</title>
  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/shop-pro.css">
</head>
<body class="shop-page">
<nav class="navbar navbar-expand-lg shop-nav sticky-top"><div class="container"><a class="navbar-brand" href="../index.php">Tees Maroc</a><div class="d-flex gap-2"><a class="btn btn-outline-light" href="../boutique.php">Boutique</a><a class="btn btn-light" href="../mes-commandes.php">Commandes</a></div></div></nav>
<header class="page-hero"><div class="container"><span class="eyebrow">Panier</span><h1>Votre panier</h1><p><?php echo count($items); ?> produit(s) selectionne(s)</p></div></header>
<main class="container py-5">
  <?php if (!$items) { ?>
    <div class="soft-panel empty-box">
      <h2 class="h4">Votre panier est vide</h2>
      <p class="text-muted">Ajoutez des produits pour preparer votre commande.</p>
      <a href="../boutique.php" class="btn btn-dark">Voir les produits</a>
    </div>
  <?php } else { ?>
    <div class="row g-4">
      <div class="col-lg-8">
        <div>
          <?php foreach ($items as $item) { ?>
            <div class="cart-line">
            <div class="row g-3 align-items-center">
              <div class="col-4 col-md-auto">
                <img src="../images/<?php echo h($item['imageproduit']); ?>" alt="<?php echo h($item['nomproduit']); ?>">
              </div>
              <div class="col-8 col-md-4">
                <h2 class="h6 mb-1"><a class="text-dark text-decoration-none" href="../produit.php?id=<?php echo (int) $item['idproduit']; ?>"><?php echo h($item['nomproduit']); ?></a></h2>
                <p class="small text-muted mb-0"><?php echo h($item['marque']); ?> - <?php echo h($item['couleur']); ?> - <?php echo h($item['taille']); ?></p>
              </div>
              <div class="col-md-3 mt-3 mt-md-0">
                <form method="post" action="modifier-panier.php" class="d-flex gap-2">
                  <input type="hidden" name="idproduit" value="<?php echo (int) $item['idproduit']; ?>">
                  <input type="number" class="form-control" name="quantite" min="0" max="<?php echo (int) $item['quantite']; ?>" value="<?php echo (int) $item['quantite_panier']; ?>">
                  <button class="btn btn-outline-dark" title="Mettre a jour"><i class="fa fa-rotate"></i></button>
                </form>
              </div>
              <div class="col-md-2 mt-3 mt-md-0 text-md-end fw-semibold"><?php echo money((float) $item['prix'] * (int) $item['quantite_panier']); ?></div>
              <div class="col-md-1 mt-3 mt-md-0 text-md-end">
                <a href="supprimer-du-panier.php?idproduit=<?php echo (int) $item['idproduit']; ?>" class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="fa fa-trash"></i></a>
              </div>
            </div>
            </div>
          <?php } ?>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="summary-card">
          <h2 class="h4 mb-4">Resume</h2>
          <div class="d-flex justify-content-between mb-2"><span>Sous-total</span><strong><?php echo money($subtotal); ?></strong></div>
          <div class="d-flex justify-content-between mb-2"><span>Livraison</span><strong><?php echo money($shipping); ?></strong></div>
          <hr>
          <div class="d-flex justify-content-between fs-5 mb-4"><span>Total</span><strong><?php echo money($total); ?></strong></div>
          <a href="../checkout.php" class="btn btn-dark w-100 btn-lg">Valider la commande</a>
        </div>
      </div>
    </div>
  <?php } ?>
</main>
</body>
</html>
