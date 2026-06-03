<?php
require __DIR__ . '/config/db.php';

$stmt = $pdo->query("SELECT * FROM produits ORDER BY nom");
$produits = $stmt->fetchAll();

$titrePage = "Liste des produits";
require __DIR__ . '/includes/header.php';
?>

<h2 class="mb-4">Liste des produits</h2>

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
                <th class="text-center">Panier</th>
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
                    <td class="text-center" style="min-width: 180px;">
    <form method="POST" action="panier.php" class="d-flex gap-2 justify-content-center">
        <input type="hidden" name="action" value="ajouter">
        <input type="hidden" name="id_produit" value="<?= $produit['id_produit'] ?>">
        <input type="number" name="quantite" value="1" min="1" class="form-control form-control-sm" style="width: 70px;">
        <button type="submit" class="btn btn-success btn-sm">Ajouter</button>
    </form>
</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>