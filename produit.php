<?php
require_once 'dbcon/dbcon.php';
require_once 'includes/helpers.php';
session_start();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$stmt = $conn->prepare('SELECT p.*, c.nomcategorie FROM produit p JOIN categorie c ON c.idcategorie = p.idcategorie WHERE p.idproduit = ?');
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) { redirect('boutique.php'); }

$avisStmt = $conn->prepare('SELECT a.*, pr.nom, pr.prenom FROM avis_produit a JOIN profil pr ON pr.idprofil = a.idprofil WHERE a.idproduit = ? ORDER BY a.created_at DESC');
$avisStmt->execute([$id]);
$avis = $avisStmt->fetchAll(PDO::FETCH_ASSOC);
$avg = $avis ? array_sum(array_column($avis, 'note')) / count($avis) : 0;
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo h($product['nomproduit']); ?> - Tees Maroc</title>
  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/shop-pro.css">
</head>
<body class="shop-page">
<nav class="navbar navbar-expand-lg shop-nav sticky-top"><div class="container"><a class="navbar-brand" href="index.php">Tees Maroc</a><div class="d-flex gap-2"><a class="btn btn-outline-light" href="boutique.php">Boutique</a><a class="btn btn-light" href="panier/panier.php"><i class="fa fa-cart-shopping"></i></a></div></div></nav>
<header class="page-hero"><div class="container"><span class="eyebrow">Detail produit</span><h1><?php echo h($product['nomproduit']); ?></h1><p><?php echo h($product['nomcategorie']); ?> - <?php echo money($product['prix']); ?></p></div></header>

<main class="container py-5">
  <div class="row g-4 product-detail-panel soft-panel">
    <div class="col-lg-6"><img src="images/<?php echo h($product['imageproduit']); ?>" alt="<?php echo h($product['nomproduit']); ?>"></div>
    <div class="col-lg-6">
      <span class="badge-soft mb-3 d-inline-flex"><?php echo h($product['nomcategorie']); ?></span>
      <h2 class="display-6 fw-bold"><?php echo h($product['nomproduit']); ?></h2>
      <p class="fs-3 fw-bold"><?php echo money($product['prix']); ?></p>
      <p class="text-muted"><?php echo h($product['description']); ?></p>
      <div class="row g-3 my-4">
        <div class="col-6"><div class="info-tile"><small>Marque</small><br><strong><?php echo h($product['marque']); ?></strong></div></div>
        <div class="col-6"><div class="info-tile"><small>Stock</small><br><strong><?php echo (int) $product['quantite']; ?> pieces</strong></div></div>
        <div class="col-6"><div class="info-tile"><small>Couleur</small><br><strong><?php echo h($product['couleur']); ?></strong></div></div>
        <div class="col-6"><div class="info-tile"><small>Taille</small><br><strong><?php echo h($product['taille']); ?></strong></div></div>
      </div>
      <form action="panier/ajouter-panier.php?idproduit=<?php echo (int) $product['idproduit']; ?>" method="post" class="d-flex gap-2 mb-3">
        <input type="number" name="quantite" min="1" max="<?php echo max(1, (int) $product['quantite']); ?>" value="1" class="form-control" style="max-width:120px">
        <button class="btn btn-dark btn-lg flex-grow-1"><i class="fa fa-cart-plus me-2"></i>Ajouter au panier</button>
      </form>
      <a href="favoris.php?idproduit=<?php echo (int) $product['idproduit']; ?>" class="btn btn-outline-dark w-100"><i class="fa fa-heart me-2"></i>Ajouter aux favoris</a>
    </div>
  </div>

  <section class="mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h3 fw-bold mb-0">Avis clients</h2><span class="badge-soft"><?php echo $avg ? number_format($avg, 1) . '/5' : 'Aucun avis'; ?></span></div>
    <?php if (isset($_SESSION['iduser'])) { ?>
      <form action="avis-action.php" method="post" class="soft-panel p-4 mb-4">
        <input type="hidden" name="idproduit" value="<?php echo (int) $product['idproduit']; ?>">
        <div class="row g-3"><div class="col-md-2"><select name="note" class="form-select"><option value="5">5/5</option><option value="4">4/5</option><option value="3">3/5</option><option value="2">2/5</option><option value="1">1/5</option></select></div><div class="col-md-8"><input name="message" class="form-control" maxlength="500" placeholder="Votre avis sur le produit" required></div><div class="col-md-2 d-grid"><button class="btn btn-dark">Publier</button></div></div>
      </form>
    <?php } ?>
    <div class="row g-3">
      <?php foreach ($avis as $item) { ?><div class="col-md-6"><div class="soft-panel p-4 h-100"><div class="d-flex justify-content-between"><strong><?php echo h($item['prenom'] . ' ' . $item['nom']); ?></strong><span class="text-warning"><?php echo (int) $item['note']; ?>/5</span></div><p class="mb-0 mt-2 text-muted"><?php echo h($item['message']); ?></p></div></div><?php } ?>
    </div>
  </section>
</main>
</body>
</html>
