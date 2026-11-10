<?php
require_once 'dbcon/dbcon.php';
require_once 'includes/helpers.php';
session_start();
require_login('');

$profile = current_profile($conn);
if (!$profile) {
    redirect('authentification/profil.php');
}

$stmt = $conn->prepare('SELECT * FROM commande WHERE idprofil = ? ORDER BY created_at DESC');
$stmt->execute([(int) $profile['idprofil']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mes commandes</title>
  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/shop-pro.css">
</head>
<body class="shop-page">
<nav class="navbar navbar-expand-lg shop-nav sticky-top"><div class="container"><a class="navbar-brand" href="index.php">Tees Maroc</a><div class="d-flex gap-2"><a class="btn btn-outline-light" href="boutique.php">Boutique</a><a class="btn btn-light" href="panier/panier.php">Panier</a></div></div></nav>
<header class="page-hero"><div class="container"><span class="eyebrow">Compte client</span><h1>Mes commandes</h1><p>Consulte l’historique et les details de chaque commande.</p></div></header>
<main class="container py-5">
  <?php if (isset($_GET['success'])) { ?><div class="alert alert-success">Commande creee avec succes.</div><?php } ?>
  <?php if (!$orders) { ?>
    <div class="soft-panel empty-box">Aucune commande pour le moment.</div>
  <?php } ?>
  <div class="accordion" id="orders">
    <?php foreach ($orders as $order) {
      $lineStmt = $conn->prepare('SELECT * FROM ligne_commande WHERE idcommande = ?');
      $lineStmt->execute([(int) $order['idcommande']]);
      $lines = $lineStmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
      <div class="accordion-item order-card">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#order<?php echo (int) $order['idcommande']; ?>">
            Commande #<?php echo (int) $order['idcommande']; ?> - <?php echo h($order['statut']); ?> - <?php echo money($order['total']); ?>
          </button>
        </h2>
        <div id="order<?php echo (int) $order['idcommande']; ?>" class="accordion-collapse collapse" data-bs-parent="#orders">
          <div class="accordion-body">
            <p class="text-muted">Livraison: <?php echo h($order['adresse_livraison']); ?> | Paiement: <?php echo h($order['mode_paiement']); ?></p>
            <table class="table">
              <thead><tr><th>Produit</th><th>Quantite</th><th>Prix</th></tr></thead>
              <tbody>
                <?php foreach ($lines as $line) { ?>
                  <tr><td><?php echo h($line['nomproduit']); ?></td><td><?php echo (int) $line['quantite']; ?></td><td><?php echo money($line['prix']); ?></td></tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</main>
<script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
