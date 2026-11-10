<?php
require_once '../dbcon/dbcon.php';
require_once '../includes/helpers.php';
session_start();
require_admin('../');

$stats = [
    'produits' => (int) $conn->query('SELECT COUNT(*) FROM produit')->fetchColumn(),
    'clients' => (int) $conn->query("SELECT COUNT(*) FROM users WHERE role = 'client'")->fetchColumn(),
    'commandes' => (int) $conn->query('SELECT COUNT(*) FROM commande')->fetchColumn(),
    'ventes' => (float) $conn->query('SELECT COALESCE(SUM(total), 0) FROM commande')->fetchColumn(),
];

$categories = $conn->query('SELECT * FROM categorie ORDER BY nomcategorie')->fetchAll(PDO::FETCH_ASSOC);
$products = $conn->query('SELECT p.*, c.nomcategorie FROM produit p JOIN categorie c ON c.idcategorie = p.idcategorie ORDER BY p.idproduit DESC')->fetchAll(PDO::FETCH_ASSOC);
$clients = $conn->query("SELECT iduser, email, username, birthday FROM users WHERE role = 'client' ORDER BY iduser DESC")->fetchAll(PDO::FETCH_ASSOC);
$orders = $conn->query('
    SELECT co.*, pr.nom, pr.prenom, u.email
    FROM commande co
    JOIN profil pr ON pr.idprofil = co.idprofil
    JOIN users u ON u.iduser = pr.iduser
    ORDER BY co.created_at DESC
')->fetchAll(PDO::FETCH_ASSOC);

function status_class($status) {
    return [
        'En attente' => 'status-wait',
        'Validee' => 'status-ok',
        'Expediee' => 'status-ship',
        'Livree' => 'status-done',
        'Annulee' => 'status-cancel',
    ][$status] ?? 'status-wait';
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Administration - Tees Maroc</title>
  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/admin-pro.css">
</head>
<body>
<div class="admin-shell">
  <aside class="sidebar">
    <a class="brand" href="admin.php">
      <span class="brand-mark">T</span>
      <span><strong>Tees</strong><small>Back-office</small></span>
    </a>
    <nav class="side-nav" role="tablist">
      <button class="active" data-bs-toggle="tab" data-bs-target="#overview" type="button"><i class="fa fa-chart-line"></i>Vue globale</button>
      <button data-bs-toggle="tab" data-bs-target="#produits" type="button"><i class="fa fa-shirt"></i>Produits</button>
      <button data-bs-toggle="tab" data-bs-target="#ajouter" type="button"><i class="fa fa-plus"></i>Ajouter</button>
      <button data-bs-toggle="tab" data-bs-target="#commandes" type="button"><i class="fa fa-box"></i>Commandes</button>
      <button data-bs-toggle="tab" data-bs-target="#clients" type="button"><i class="fa fa-users"></i>Clients</button>
    </nav>
    <div class="side-footer">
      <a href="../index.php"><i class="fa fa-globe"></i> Voir le site</a>
      <a href="../authentification/logout.php"><i class="fa fa-right-from-bracket"></i> Deconnexion</a>
    </div>
  </aside>

  <main class="admin-main">
    <header class="admin-topbar">
      <div>
        <p class="eyebrow">Administration</p>
        <h1>Tableau de bord</h1>
      </div>
      <div class="top-actions">
        <label class="admin-search">
          <i class="fa fa-search"></i>
          <input id="adminSearch" type="search" placeholder="Rechercher produit, client, commande...">
        </label>
        <a class="btn btn-dark" href="../boutique.php"><i class="fa fa-store me-2"></i>Boutique</a>
      </div>
    </header>

    <div class="tab-content">
      <section class="tab-pane fade show active" id="overview">
        <div class="stats-grid">
          <article class="stat-card"><span>Produits</span><strong><?php echo $stats['produits']; ?></strong><i class="fa fa-shirt"></i></article>
          <article class="stat-card"><span>Clients</span><strong><?php echo $stats['clients']; ?></strong><i class="fa fa-users"></i></article>
          <article class="stat-card"><span>Commandes</span><strong><?php echo $stats['commandes']; ?></strong><i class="fa fa-box"></i></article>
          <article class="stat-card"><span>Ventes</span><strong><?php echo money($stats['ventes']); ?></strong><i class="fa fa-coins"></i></article>
        </div>

        <div class="row g-4 mt-1">
          <div class="col-xl-7">
            <section class="panel">
              <div class="panel-head"><div><h2>Commandes recentes</h2><p>Change le statut depuis la liste commandes.</p></div></div>
              <?php foreach (array_slice($orders, 0, 5) as $order) { ?>
                <div class="mini-row searchable">
                  <div><strong>#<?php echo (int) $order['idcommande']; ?> - <?php echo h($order['prenom'] . ' ' . $order['nom']); ?></strong><small><?php echo h($order['email']); ?></small></div>
                  <span class="status <?php echo status_class($order['statut']); ?>"><?php echo h($order['statut']); ?></span>
                  <strong><?php echo money($order['total']); ?></strong>
                </div>
              <?php } ?>
              <?php if (!$orders) { ?><p class="empty-state">Aucune commande pour le moment.</p><?php } ?>
            </section>
          </div>
          <div class="col-xl-5">
            <section class="panel">
              <div class="panel-head"><div><h2>Stock faible</h2><p>Produits a surveiller.</p></div></div>
              <?php
                $lowProducts = array_filter($products, fn($p) => (int) $p['quantite'] <= 10);
                foreach (array_slice($lowProducts, 0, 5) as $product) {
              ?>
                <div class="mini-row searchable">
                  <div><strong><?php echo h($product['nomproduit']); ?></strong><small><?php echo h($product['nomcategorie']); ?></small></div>
                  <span class="status <?php echo (int) $product['quantite'] > 0 ? 'status-wait' : 'status-cancel'; ?>"><?php echo (int) $product['quantite']; ?> en stock</span>
                </div>
              <?php } ?>
              <?php if (!$lowProducts) { ?><p class="empty-state">Stock confortable sur tous les produits.</p><?php } ?>
            </section>
          </div>
        </div>
      </section>

      <section class="tab-pane fade" id="produits">
        <section class="panel">
          <div class="panel-head">
            <div><h2>Catalogue produits</h2><p>Modifie rapidement les informations importantes.</p></div>
            <button class="btn btn-outline-dark" data-bs-toggle="tab" data-bs-target="#ajouter" type="button"><i class="fa fa-plus me-2"></i>Nouveau produit</button>
          </div>
          <div class="table-responsive">
            <table class="table app-table align-middle">
              <thead><tr><th>Produit</th><th>Stock</th><th>Prix</th><th>Categorie</th><th>Details</th><th class="text-end">Actions</th></tr></thead>
              <tbody>
                <?php foreach ($products as $product) { ?>
                  <tr class="searchable">
                    <td>
                      <div class="product-cell">
                        <img src="../images/<?php echo h($product['imageproduit']); ?>" alt="">
                        <div><strong><?php echo h($product['nomproduit']); ?></strong><small>ID #<?php echo (int) $product['idproduit']; ?></small></div>
                      </div>
                    </td>
                    <td><span class="stock-pill <?php echo (int) $product['quantite'] > 10 ? 'stock-ok' : 'stock-low'; ?>"><?php echo (int) $product['quantite']; ?></span></td>
                    <td><strong><?php echo money($product['prix']); ?></strong></td>
                    <td><?php echo h($product['nomcategorie']); ?></td>
                    <td><small><?php echo h($product['marque']); ?> / <?php echo h($product['couleur']); ?> / <?php echo h($product['taille']); ?></small></td>
                    <td class="text-end action-buttons">
                      <a class="btn btn-sm btn-outline-dark" href="produit-modifier.php?idproduit=<?php echo (int) $product['idproduit']; ?>"><i class="fa fa-pen"></i></a>
                      <a class="btn btn-sm btn-outline-danger" href="produit-supprimer.php?idproduit=<?php echo (int) $product['idproduit']; ?>" onclick="return confirm('Supprimer ce produit ?')"><i class="fa fa-trash"></i></a>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </section>
      </section>

      <section class="tab-pane fade" id="ajouter">
        <section class="panel form-panel">
          <div class="panel-head"><div><h2>Ajouter un produit</h2><p>Renseigne les informations visibles dans la boutique.</p></div></div>
          <form action="admin-action.php" method="post">
            <div class="row g-3">
              <div class="col-lg-6"><label class="form-label">Nom du produit</label><input name="nom" class="form-control" placeholder="Ex: Hoodie premium noir" required></div>
              <div class="col-md-3"><label class="form-label">Prix</label><input name="prix" type="number" step="0.01" class="form-control" placeholder="350" required></div>
              <div class="col-md-3"><label class="form-label">Stock</label><input name="quantite" type="number" min="0" class="form-control" placeholder="25" required></div>
              <div class="col-md-4"><label class="form-label">Marque</label><input name="marque" class="form-control" value="Collection"></div>
              <div class="col-md-4"><label class="form-label">Couleur</label><input name="couleur" class="form-control" value="Noir"></div>
              <div class="col-md-4"><label class="form-label">Taille</label><input name="taille" class="form-control" value="Standard"></div>
              <div class="col-lg-6"><label class="form-label">Categorie</label><select name="categorie" class="form-select"><?php foreach ($categories as $cat) { ?><option value="<?php echo (int) $cat['idcategorie']; ?>"><?php echo h($cat['nomcategorie']); ?></option><?php } ?></select></div>
              <div class="col-lg-6"><label class="form-label">Image dans le dossier images</label><input name="image" class="form-control" value="product-item1.jpg"></div>
              <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4" placeholder="Description courte du produit..." required></textarea></div>
              <div class="col-12"><button name="ajouter" class="btn btn-dark btn-lg"><i class="fa fa-plus me-2"></i>Ajouter le produit</button></div>
            </div>
          </form>
        </section>
      </section>

      <section class="tab-pane fade" id="commandes">
        <section class="panel">
          <div class="panel-head"><div><h2>Gestion des commandes</h2><p>Suis les commandes et mets a jour leur statut.</p></div></div>
          <div class="table-responsive">
            <table class="table app-table align-middle">
              <thead><tr><th>Commande</th><th>Client</th><th>Total</th><th>Statut</th><th>Date</th><th>Modifier</th></tr></thead>
              <tbody>
                <?php foreach ($orders as $order) { ?>
                  <tr class="searchable">
                    <td><strong>#<?php echo (int) $order['idcommande']; ?></strong></td>
                    <td><?php echo h($order['prenom'] . ' ' . $order['nom']); ?><br><small><?php echo h($order['email']); ?></small></td>
                    <td><strong><?php echo money($order['total']); ?></strong></td>
                    <td><span class="status <?php echo status_class($order['statut']); ?>"><?php echo h($order['statut']); ?></span></td>
                    <td><small><?php echo h($order['created_at']); ?></small></td>
                    <td>
                      <form action="commande-statut.php" method="post" class="status-form">
                        <input type="hidden" name="idcommande" value="<?php echo (int) $order['idcommande']; ?>">
                        <select name="statut" class="form-select form-select-sm">
                          <?php foreach (['En attente','Validee','Expediee','Livree','Annulee'] as $status) { ?>
                            <option <?php echo $order['statut'] === $status ? 'selected' : ''; ?>><?php echo $status; ?></option>
                          <?php } ?>
                        </select>
                        <button class="btn btn-sm btn-dark">Enregistrer</button>
                      </form>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </section>
      </section>

      <section class="tab-pane fade" id="clients">
        <section class="panel">
          <div class="panel-head"><div><h2>Clients</h2><p>Liste des comptes clients inscrits.</p></div></div>
          <div class="table-responsive">
            <table class="table app-table align-middle">
              <thead><tr><th>ID</th><th>Email</th><th>Username</th><th>Naissance</th><th class="text-end">Action</th></tr></thead>
              <tbody>
                <?php foreach ($clients as $client) { ?>
                  <tr class="searchable">
                    <td>#<?php echo (int) $client['iduser']; ?></td>
                    <td><?php echo h($client['email']); ?></td>
                    <td><strong><?php echo h($client['username']); ?></strong></td>
                    <td><?php echo h($client['birthday']); ?></td>
                    <td class="text-end"><a class="btn btn-sm btn-outline-dark" href="client-modifier.php?iduser=<?php echo (int) $client['iduser']; ?>"><i class="fa fa-pen me-1"></i>Modifier</a></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </section>
      </section>
    </div>
  </main>
</div>
<script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/admin-pro.js"></script>
</body>
</html>
