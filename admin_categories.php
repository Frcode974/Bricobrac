<?php
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/config/db.php';

// Récupérer toutes les catégories + le nombre de produits de chacune
$stmt = $pdo->query("
    SELECT c.id_categorie, c.nom,
           COUNT(p.id_produit) AS nb_produits
    FROM categories c
    LEFT JOIN produits p ON p.id_categorie = c.id_categorie
    GROUP BY c.id_categorie, c.nom
    ORDER BY c.nom
");
$categories = $stmt->fetchAll();

// Si une catégorie est sélectionnée via ?id=X, on liste ses produits
$idCategorie = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$produitsCategorie = [];
$categorieSelectionnee = null;

if ($idCategorie > 0) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id_categorie = ?");
    $stmt->execute([$idCategorie]);
    $categorieSelectionnee = $stmt->fetch();

    if ($categorieSelectionnee) {
        $stmt = $pdo->prepare("SELECT * FROM produits WHERE id_categorie = ? ORDER BY nom");
        $stmt->execute([$idCategorie]);
        $produitsCategorie = $stmt->fetchAll();
    }
}

$titrePage = "Gestion des catégories";
require __DIR__ . '/includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Gestion des catégories</h2>
    <a href="admin_ajout_categorie.php" class="btn btn-success">+ Ajouter une catégorie</a>
</div>

<div class="row">
    <div class="col-md-5">
        <h4>Liste des catégories</h4>
        <div class="table-responsive bg-white p-3 rounded shadow-sm">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nom</th>
                        <th class="text-center">Nb produits</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $c): ?>
                        <tr>
                            <td><?= htmlspecialchars($c['nom']) ?></td>
                            <td class="text-center"><span class="badge bg-primary"><?= $c['nb_produits'] ?></span></td>
                            <td class="text-center">
                                <a href="admin_categories.php?id=<?= $c['id_categorie'] ?>" class="btn btn-outline-primary btn-sm">
                                    Voir produits
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-7">
        <?php if ($categorieSelectionnee): ?>
            <h4>Produits dans « <?= htmlspecialchars($categorieSelectionnee['nom']) ?> »</h4>
            <div class="table-responsive bg-white p-3 rounded shadow-sm">
                <?php if (empty($produitsCategorie)): ?>
                    <p class="text-muted">Aucun produit dans cette catégorie.</p>
                <?php else: ?>
                    <table class="table table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Nom</th>
                                <th>Référence</th>
                                <th>Prix HT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produitsCategorie as $p): ?>
                                <tr>
                                    <td><?= htmlspecialchars($p['nom']) ?></td>
                                    <td><?= htmlspecialchars($p['reference']) ?></td>
                                    <td><?= number_format($p['prix_ht'], 2, ',', ' ') ?> €</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Cliquez sur « Voir produits » pour afficher les produits d'une catégorie.</div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>