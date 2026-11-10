<?php
require_once 'dbcon/dbcon.php';
require_once 'includes/helpers.php';
session_start();

$q = trim($_GET['q'] ?? '');
$category = isset($_GET['categorie']) ? (int) $_GET['categorie'] : 0;
$sort = $_GET['tri'] ?? 'recent';
$categories = $conn->query('SELECT * FROM categorie ORDER BY nomcategorie')->fetchAll(PDO::FETCH_ASSOC);
$featuredCategory = null;
foreach ($categories as $cat) {
    if ((int) $cat['idcategorie'] === $category) {
        $featuredCategory = $cat['nomcategorie'];
        break;
    }
}

$where = [];
$params = [];
if ($q !== '') {
    $where[] = '(p.nomproduit LIKE ? OR p.description LIKE ? OR p.marque LIKE ?)';
    array_push($params, "%$q%", "%$q%", "%$q%");
}
if ($category > 0) {
    $where[] = 'p.idcategorie = ?';
    $params[] = $category;
}

$order = ['prix_asc' => 'p.prix ASC', 'prix_desc' => 'p.prix DESC', 'nom' => 'p.nomproduit ASC'][$sort] ?? 'p.date_ajout DESC';
$sql = 'SELECT p.*, c.nomcategorie FROM produit p JOIN categorie c ON c.idcategorie = p.idcategorie';
if ($where) { $sql .= ' WHERE ' . implode(' AND ', $where); }
$sql .= " ORDER BY $order";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
$profile = current_profile($conn);
$cartCount = $profile ? cart_count($conn, $profile['idprofil']) : 0;
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Boutique - Tees Maroc</title>
  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/shop-pro.css">
</head>
<body class="shop-page">
<nav class="navbar navbar-expand-lg shop-nav sticky-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">Tees Maroc</a>
    <div class="d-flex align-items-center gap-2">
      <a class="nav-link d-none d-md-inline" href="index.php">Accueil</a>
      <a class="nav-link d-none d-md-inline" href="mes-commandes.php">Commandes</a>
      <a class="btn btn-light" href="panier/panier.php"><i class="fa fa-cart-shopping me-1"></i><?php echo $cartCount; ?></a>
    </div>
  </div>
</nav>

<header class="page-hero">
  <div class="container">
    <div class="shop-hero-card">
      <div>
        <span class="eyebrow">Catalogue</span>
        <h1><?php echo $featuredCategory ? h($featuredCategory) : 'Boutique'; ?></h1>
        <p>Parcours la collection, filtre rapidement et commande tes pieces favorites sans perdre de temps.</p>
      </div>
      <div class="shop-hero-stats">
        <strong><?php echo count($products); ?></strong>
        <span>produit(s)</span>
      </div>
    </div>
  </div>
</header>

<main class="container py-5">
  <div class="shop-layout">
    <aside class="shop-sidebar">
      <form class="soft-panel filter-panel" method="get">
        <div class="filter-block">
          <label class="filter-label">Recherche</label>
          <div class="search-field">
            <i class="fa fa-search"></i>
            <input class="form-control" name="q" value="<?php echo h($q); ?>" placeholder="Nom, marque, couleur...">
          </div>
        </div>
        <div class="filter-block">
          <label class="filter-label">Categorie</label>
          <select name="categorie" class="form-select">
            <option value="0">Toutes les categories</option>
            <?php foreach ($categories as $cat) { ?>
              <option value="<?php echo (int) $cat['idcategorie']; ?>" <?php echo $category === (int) $cat['idcategorie'] ? 'selected' : ''; ?>><?php echo h($cat['nomcategorie']); ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="filter-block">
          <label class="filter-label">Trier par</label>
          <select name="tri" class="form-select">
            <option value="recent" <?php echo $sort === 'recent' ? 'selected' : ''; ?>>Nouveautes</option>
            <option value="prix_asc" <?php echo $sort === 'prix_asc' ? 'selected' : ''; ?>>Prix croissant</option>
            <option value="prix_desc" <?php echo $sort === 'prix_desc' ? 'selected' : ''; ?>>Prix decroissant</option>
            <option value="nom" <?php echo $sort === 'nom' ? 'selected' : ''; ?>>Nom A-Z</option>
          </select>
        </div>
        <button class="btn btn-dark w-100"><i class="fa fa-sliders me-2"></i>Appliquer</button>
        <a class="btn btn-outline-dark w-100 mt-2" href="boutique.php">Reinitialiser</a>
      </form>

      <div class="category-menu soft-panel">
        <span>Categories</span>
        <a class="<?php echo $category === 0 ? 'active' : ''; ?>" href="boutique.php">Toutes les pieces</a>
        <?php foreach ($categories as $cat) { ?>
          <a class="<?php echo $category === (int) $cat['idcategorie'] ? 'active' : ''; ?>" href="boutique.php?categorie=<?php echo (int) $cat['idcategorie']; ?>"><?php echo h($cat['nomcategorie']); ?></a>
        <?php } ?>
      </div>
    </aside>

    <section class="shop-results">
      <div class="shop-grid-head">
        <div>
          <span class="eyebrow-small">Collection</span>
          <h2>Produits disponibles</h2>
        </div>
        <p><?php echo count($products); ?> piece(s) affichee(s)</p>
      </div>

      <?php if (!$products) { ?>
        <div class="soft-panel empty-box"><h2 class="h4">Aucun produit trouve</h2><p class="text-muted">Essaie une autre recherche ou retire les filtres.</p></div>
      <?php } ?>

      <div class="product-grid">
        <?php foreach ($products as $product) { ?>
          <article class="product-card">
            <a class="product-media" href="produit.php?id=<?php echo (int) $product['idproduit']; ?>">
              <img src="images/<?php echo h($product['imageproduit']); ?>" alt="<?php echo h($product['nomproduit']); ?>">
              <span class="quick-view">Voir la fiche</span>
            </a>
            <div class="card-body">
              <div class="d-flex justify-content-between gap-2 mb-3">
                <span class="badge-soft"><?php echo h($product['nomcategorie']); ?></span>
                <span class="badge-soft <?php echo (int) $product['quantite'] > 0 ? 'stock-ok' : 'stock-out'; ?>"><?php echo (int) $product['quantite'] > 0 ? 'En stock' : 'Rupture'; ?></span>
              </div>
              <h2><a href="produit.php?id=<?php echo (int) $product['idproduit']; ?>"><?php echo h($product['nomproduit']); ?></a></h2>
              <p><?php echo h($product['marque']); ?> - <?php echo h($product['couleur']); ?> - <?php echo h($product['taille']); ?></p>
              <div class="product-meta">
                <strong><?php echo money($product['prix']); ?></strong>
                <small><?php echo (int) $product['quantite']; ?> en stock</small>
              </div>
              <div class="product-actions">
                <a class="btn btn-outline-dark" href="produit.php?id=<?php echo (int) $product['idproduit']; ?>">Details</a>
                <a class="btn btn-dark" href="panier/ajouter-panier.php?idproduit=<?php echo (int) $product['idproduit']; ?>"><i class="fa fa-cart-plus me-1"></i>Ajouter</a>
              </div>
            </div>
          </article>
        <?php } ?>
      </div>
    </section>
  </div>
</main>
</body>
</html>
