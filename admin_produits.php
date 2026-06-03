<?php
// =====================================================================
// FP3 — Liste des produits côté admin
// =====================================================================

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/config/db.php';

$stmt = $pdo->query("SELECT * FROM produits ORDER BY nom");
$produits = $stmt->fetchAll();

$titrePage = "Gestion des produits";
require __DIR__ . '/includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Gestion des produits</h2>
    <a href="admin_ajout_produit.php" class="btn btn-success">
        + Ajouter un nouveau produit
    </a>
</div>

<div class="table-responsive bg-white p-3 rounded shadow-sm">
    <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>Nom</th>
                <th>Prix HT</th>
                <th>Prix TTC</th>
                <th>Remise</th>
                <th>Prix final</th>
                <th>Statut</th>
                <th class="text-center">Détails</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produits as $produit): ?>
                <?php
                $prixTtc      = $produit['prix_ht'] * (1 + $produit['tva_pourcentage'] / 100);
                $prixFinalTtc = $prixTtc * (1 - $produit['remise_pourcentage'] / 100);
                $aRemise      = $produit['remise_pourcentage'] > 0;
                $estNouveaute = $produit['est_nouveaute'] == 1;
                ?>
                <tr>
                    <td><?= htmlspecialchars($produit['nom']) ?></td>
                    <td><?= number_format($produit['prix_ht'], 2, ',', ' ') ?> €</td>
                    <td><?= number_format($prixTtc, 2, ',', ' ') ?> €</td>
                    <td>
                        <?php if ($aRemise): ?>
                            <span class="badge bg-warning text-dark">
                                -<?= number_format($produit['remise_pourcentage'], 0) ?>%
                            </span>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= number_format($prixFinalTtc, 2, ',', ' ') ?> €</strong></td>
                    <td>
                        <?php if ($estNouveaute): ?>
                            <span class="badge bg-success">Nouveauté</span>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <a href="detail_produit.php?id=<?= $produit['id_produit'] ?>" class="btn btn-outline-primary btn-sm">
                            Voir
                        </a>
                    </td>
                    <td class="text-center">
                        <a href="admin_modification_produit.php?id=<?= $produit['id_produit'] ?>" class="btn btn-outline-warning btn-sm">
                            Modifier
                        </a>
                        <a href="admin_suppression_produit.php?id=<?= $produit['id_produit'] ?>" class="btn btn-outline-danger btn-sm">
                            Supprimer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>