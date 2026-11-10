<?php
require_once 'dbcon/dbcon.php';
require_once 'includes/helpers.php';
session_start();

$profile = current_profile($conn);
$cartCount = $profile ? cart_count($conn, $profile['idprofil']) : 0;

$products = $conn->query('
  SELECT p.*, c.nomcategorie
  FROM produit p
  JOIN categorie c ON c.idcategorie = p.idcategorie
  ORDER BY p.date_ajout DESC, p.idproduit DESC
  LIMIT 8
')->fetchAll(PDO::FETCH_ASSOC);

$categories = $conn->query('
  SELECT c.*, COUNT(p.idproduit) AS total
  FROM categorie c
  LEFT JOIN produit p ON p.idcategorie = c.idcategorie
  GROUP BY c.idcategorie, c.nomcategorie
  ORDER BY c.nomcategorie
  LIMIT 6
')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tees Maroc - Boutique streetwear premium</title>
  <meta name="description" content="Boutique marocaine de vetements streetwear: t-shirts, hoodies, vestes et collections selectionnees.">
  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/home-pro.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top site-nav">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
      <img src="images/main-logo.png" alt="Tees Maroc">
      <!-- <span>Tees Maroc</span> -->
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="index.php">Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="boutique.php">Boutique</a></li>
        <li class="nav-item"><a class="nav-link" href="#nouveautes">Nouveautes</a></li>
        <li class="nav-item"><a class="nav-link" href="#categories">Categories</a></li>
        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
      </ul>
      <form action="boutique.php" method="get" class="nav-search me-lg-3 mb-3 mb-lg-0">
        <i class="fa fa-search"></i>
        <input name="q" placeholder="Rechercher">
      </form>
      <div class="d-flex gap-2 nav-actions">
        <?php if (isset($_SESSION['iduser'])) { ?>
          <a class="btn btn-outline-light" href="mes-commandes.php" title="Commandes"><i class="fa fa-receipt"></i></a>
          <a class="btn btn-light position-relative" href="panier/panier.php" title="Panier">
            <i class="fa fa-cart-shopping"></i>
            <span class="cart-badge"><?php echo $cartCount; ?></span>
          </a>
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>
            <a class="btn btn-outline-light" href="admin/admin.php">Admin</a>
          <?php } ?>
          <a class="btn btn-outline-light" href="authentification/logout.php">Sortir</a>
        <?php } else { ?>
          <a class="btn btn-outline-light" href="authentification/login.php">Connexion</a>
          <a class="btn btn-light" href="authentification/register.php">Compte</a>
        <?php } ?>
      </div>
    </div>
  </div>
</nav>

<header class="hero">
  <div class="hero-overlay"></div>
  <div class="container hero-content">
    <div class="row align-items-center hero-row">
      <div class="col-lg-8 col-xl-7 reveal">
        <span class="hero-kicker">Collection 2026</span>
        <h1>Nouvelle collection</h1>
        <p>Vetements simples, modernes et faciles a porter.</p>
        <div class="d-flex flex-wrap gap-3">
          <a href="boutique.php" class="btn btn-light btn-lg">Explorer la boutique</a>
          <a href="#nouveautes" class="btn btn-outline-light btn-lg">Nouveautes</a>
        </div>
      </div>
    </div>
  </div>
</header>

<main>
  <section class="ticker" aria-label="Promesses boutique">
    <div class="ticker-track">
      <span>COLLECTION 2026</span><span>LIVRAISON MAROC</span><span>STOCK EN TEMPS REEL</span><span>PAIEMENT FLEXIBLE</span>
      <span>COLLECTION 2026</span><span>LIVRAISON MAROC</span><span>STOCK EN TEMPS REEL</span><span>PAIEMENT FLEXIBLE</span>
    </div>
  </section>

  <section class="services-section">
    <div class="container">
      <div class="row g-3">
        <div class="col-md-4">
          <div class="service-box reveal"><i class="fa fa-truck-fast"></i><h2>Livraison rapide</h2><p>Preparation soignee et livraison suivie partout au Maroc.</p></div>
        </div>
        <div class="col-md-4">
          <div class="service-box reveal delay-1"><i class="fa fa-credit-card"></i><h2>Paiement flexible</h2><p>Paiement a la livraison ou simulation carte bancaire.</p></div>
        </div>
        <div class="col-md-4">
          <div class="service-box reveal delay-2"><i class="fa fa-shield-heart"></i><h2>Qualite verifiee</h2><p>Fiches produits detaillees, stock visible et commande simple.</p></div>
        </div>
      </div>
    </div>
  </section>

  <section id="categories" class="section-pad">
    <div class="container">
      <div class="section-title reveal">
        <span>Categories</span>
        <h2>Choisis ton univers</h2>
      </div>
      <div class="row g-3">
        <?php foreach ($categories as $category) { ?>
          <div class="col-sm-6 col-lg-4">
            <a class="category-tile reveal" href="boutique.php?categorie=<?php echo (int) $category['idcategorie']; ?>">
              <span><?php echo h($category['nomcategorie']); ?></span>
              <small><?php echo (int) $category['total']; ?> produit(s)</small>
              <i class="fa fa-arrow-right"></i>
            </a>
          </div>
        <?php } ?>
      </div>
    </div>
  </section>

  <section id="nouveautes" class="section-pad products-band">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div class="section-title mb-0 reveal">
          <span>Nouveautes</span>
          <h2>Selection tendance</h2>
        </div>
        <a href="boutique.php" class="btn btn-outline-dark">Voir tout</a>
      </div>
      <div class="row g-4">
        <?php foreach ($products as $product) { ?>
          <div class="col-sm-6 col-lg-3">
            <article class="product-card reveal">
              <a href="produit.php?id=<?php echo (int) $product['idproduit']; ?>" class="product-image-wrap">
                <img src="images/<?php echo h($product['imageproduit']); ?>" alt="<?php echo h($product['nomproduit']); ?>">
                <span><?php echo h($product['nomcategorie']); ?></span>
              </a>
              <div class="product-body">
                <h3><a href="produit.php?id=<?php echo (int) $product['idproduit']; ?>"><?php echo h($product['nomproduit']); ?></a></h3>
                <p><?php echo h($product['marque']); ?> - <?php echo h($product['couleur']); ?> - <?php echo h($product['taille']); ?></p>
                <div class="d-flex justify-content-between align-items-center">
                  <strong><?php echo money($product['prix']); ?></strong>
                  <a class="btn btn-dark btn-sm" href="panier/ajouter-panier.php?idproduit=<?php echo (int) $product['idproduit']; ?>"><i class="fa fa-cart-plus"></i></a>
                </div>
              </div>
            </article>
          </div>
        <?php } ?>
      </div>
    </div>
  </section>

  <section class="section-pad story-section">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-lg-6 reveal">
          <img src="images/hero-image.jpg" class="story-image" alt="Collection Tees Maroc">
        </div>
        <div class="col-lg-6 reveal delay-1">
          <div class="section-title">
            <span>Pourquoi Tees</span>
            <h2>Une boutique simple, claire et faite pour commander vite.</h2>
          </div>
          <p class="lead">Le site regroupe les essentiels d’un e-commerce moderne: catalogue filtrable, pages produits, panier avec quantites, checkout, avis et suivi commande.</p>
          <a href="boutique.php" class="btn btn-dark btn-lg">Commencer</a>
        </div>
      </div>
    </div>
  </section>
</main>

<footer id="contact" class="site-footer">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        <img src="images/dark-logo.png" class="footer-logo" alt="Tees Maroc">
        <p>Tees Maroc selectionne des pieces streetwear simples, confortables et faciles a porter au quotidien.</p>
      </div>
      <div class="col-sm-6 col-lg-2">
        <h2>Navigation</h2>
        <a href="index.php">Accueil</a>
        <a href="boutique.php">Boutique</a>
        <a href="panier/panier.php">Panier</a>
        <a href="mes-commandes.php">Commandes</a>
      </div>
      <div class="col-sm-6 col-lg-3">
        <h2>Compte</h2>
        <a href="authentification/login.php">Connexion</a>
        <a href="authentification/register.php">Creer un compte</a>
        <a href="authentification/profil.php">Profil</a>
        <a href="admin/admin.php">Admin</a>
      </div>
      <div class="col-lg-3">
        <h2>Contact</h2>
        <p>Email: contact@tees.ma</p>
        <p>Telephone: +212 222 333 44</p>
        <div class="socials">
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-youtube"></i></a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <span>Tees Maroc © 2026 - Tous droits reserves.</span>
      <span>Paiement a la livraison disponible.</span>
    </div>
  </div>
</footer>

<script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/home-pro.js"></script>
</body>
</html>
