<?php
require_once __DIR__ . '/../dbcon/dbcon.php';

function column_exists(PDO $conn, $table, $column) {
    $stmt = $conn->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
    $stmt->execute([$column]);
    return (bool) $stmt->fetch();
}

function table_exists(PDO $conn, $table) {
    $stmt = $conn->prepare('SHOW TABLES LIKE ?');
    $stmt->execute([$table]);
    return (bool) $stmt->fetch();
}

function run_sql(PDO $conn, $sql) {
    $conn->exec($sql);
    echo "OK: $sql\n";
}

if (!column_exists($conn, 'panier', 'quantite')) {
    run_sql($conn, 'ALTER TABLE panier ADD quantite INT NOT NULL DEFAULT 1');
}

try {
    run_sql($conn, 'ALTER TABLE panier ADD UNIQUE KEY unique_panier_produit (idprofil, idproduit)');
} catch (Exception $e) {
    echo "INFO: index panier deja present ou non applicable.\n";
}

$productColumns = [
    'taille' => "ALTER TABLE produit ADD taille VARCHAR(30) NOT NULL DEFAULT 'Standard'",
    'couleur' => "ALTER TABLE produit ADD couleur VARCHAR(50) NOT NULL DEFAULT 'Noir'",
    'marque' => "ALTER TABLE produit ADD marque VARCHAR(100) NOT NULL DEFAULT 'Collection'",
    'date_ajout' => 'ALTER TABLE produit ADD date_ajout DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP'
];

foreach ($productColumns as $column => $sql) {
    if (!column_exists($conn, 'produit', $column)) {
        run_sql($conn, $sql);
    }
}

if (!table_exists($conn, 'commande')) {
    run_sql($conn, "CREATE TABLE commande (
        idcommande INT NOT NULL AUTO_INCREMENT,
        idprofil INT NOT NULL,
        statut VARCHAR(50) NOT NULL DEFAULT 'En attente',
        total DECIMAL(10,2) NOT NULL DEFAULT 0,
        livraison DECIMAL(10,2) NOT NULL DEFAULT 0,
        adresse_livraison VARCHAR(255) NOT NULL,
        mode_paiement VARCHAR(80) NOT NULL DEFAULT 'Paiement a la livraison',
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (idcommande),
        KEY idprofil (idprofil)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
}

if (!table_exists($conn, 'ligne_commande')) {
    run_sql($conn, "CREATE TABLE ligne_commande (
        idligne INT NOT NULL AUTO_INCREMENT,
        idcommande INT NOT NULL,
        idproduit INT NOT NULL,
        nomproduit VARCHAR(255) NOT NULL,
        prix DECIMAL(10,2) NOT NULL,
        quantite INT NOT NULL,
        PRIMARY KEY (idligne),
        KEY idcommande (idcommande),
        KEY idproduit (idproduit)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
}

if (!table_exists($conn, 'favoris')) {
    run_sql($conn, "CREATE TABLE favoris (
        idfavori INT NOT NULL AUTO_INCREMENT,
        idprofil INT NOT NULL,
        idproduit INT NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (idfavori),
        UNIQUE KEY unique_favori (idprofil, idproduit)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
}

if (!table_exists($conn, 'avis_produit')) {
    run_sql($conn, "CREATE TABLE avis_produit (
        idavis INT NOT NULL AUTO_INCREMENT,
        idproduit INT NOT NULL,
        idprofil INT NOT NULL,
        note INT NOT NULL DEFAULT 5,
        message VARCHAR(500) NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (idavis),
        KEY idproduit (idproduit),
        KEY idprofil (idprofil)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
}

echo "Migration terminee.\n";
?>
