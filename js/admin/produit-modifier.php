<?php
require_once '../dbcon/dbcon.php';
require_once '../includes/helpers.php';
session_start();
require_admin('../');

$idproduit = isset($_GET['idproduit']) ? (int) $_GET['idproduit'] : 0;
$stmt = $conn->prepare('SELECT * FROM produit WHERE idproduit = ?');
$stmt->execute([$idproduit]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
    redirect('admin.php');
}

if (isset($_POST['modifier'])) {
    $stmt = $conn->prepare('
        UPDATE produit
        SET nomproduit = ?, quantite = ?, description = ?, prix = ?, marque = ?, couleur = ?, taille = ?, imageproduit = ?
        WHERE idproduit = ?
    ');
    $stmt->execute([
        trim($_POST['nom'] ?? ''),
        max(0, (int) ($_POST['quantite'] ?? 0)),
        trim($_POST['description'] ?? ''),
        max(0, (float) ($_POST['prix'] ?? 0)),
        trim($_POST['marque'] ?? ''),
        trim($_POST['couleur'] ?? ''),
        trim($_POST['taille'] ?? ''),
        trim($_POST['image'] ?? ''),
        $idproduit
    ]);
    redirect('admin.php');
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modifier produit</title>
  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/admin-pro.css">
</head>
<body>
<main class="admin-main" style="max-width: 980px; margin: 0 auto;">
  <div class="admin-topbar">
    <div><p class="eyebrow">Catalogue</p><h1>Modifier le produit</h1></div>
    <a href="admin.php" class="btn btn-outline-dark"><i class="fa fa-arrow-left me-2"></i>Retour admin</a>
  </div>
  <form method="post" class="panel form-panel">
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">Nom</label><input type="text" class="form-control" name="nom" value="<?php echo h($row['nomproduit']); ?>" required></div>
      <div class="col-md-3"><label class="form-label">Prix</label><input type="number" step="0.01" class="form-control" name="prix" value="<?php echo h($row['prix']); ?>" required></div>
      <div class="col-md-3"><label class="form-label">Stock</label><input type="number" class="form-control" name="quantite" value="<?php echo h($row['quantite']); ?>" required></div>
      <div class="col-md-4"><label class="form-label">Marque</label><input type="text" class="form-control" name="marque" value="<?php echo h($row['marque']); ?>"></div>
      <div class="col-md-4"><label class="form-label">Couleur</label><input type="text" class="form-control" name="couleur" value="<?php echo h($row['couleur']); ?>"></div>
      <div class="col-md-4"><label class="form-label">Taille</label><input type="text" class="form-control" name="taille" value="<?php echo h($row['taille']); ?>"></div>
      <div class="col-12"><label class="form-label">Image dans /images</label><input type="text" class="form-control" name="image" value="<?php echo h($row['imageproduit']); ?>"></div>
      <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="4" required><?php echo h($row['description']); ?></textarea></div>
      <div class="col-12"><button name="modifier" class="btn btn-dark btn-lg">Enregistrer</button></div>
    </div>
  </form>
</main>
</body>
</html>
