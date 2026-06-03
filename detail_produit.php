<?php
require __DIR__ . '/config/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    die("Identifiant produit invalide.");
}

$stmt = $pdo->prepare("SELECT * FROM produits WHERE id_produit = ?");
$stmt->execute([$id]);
$produit = $stmt->fetch();

if (!$produit) {
    die("Produit introuvable.");
}

$prixTtc      = $produit['prix_ht'] * (1 + $produit['tva_pourcentage'] / 100);
$prixFinalTtc = $prixTtc * (1 - $produit['remise_pourcentage'] / 100);
$aRemise      = $produit['remise_pourcentage'] > 0;
$estNouveaute = $produit['est_nouveaute'] == 1;

$titrePage = $produit['nom'];
require __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">

        <h2 class="mb-4 text-center">
            <?= htmlspecialchars($produit['nom']) ?>
            <?php if ($estNouveaute): ?>
                <span class="badge bg-success align-middle">Nouveauté</span>
            <?php endif; ?>
        </h2>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                Informations sur le produit
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                    <span>Référence produit</span>
                    <strong><?= htmlspecialchars($produit['reference']) ?></strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Nouveauté</span>
                    <strong><?= $estNouveaute ? 'oui' : 'non' ?></strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Prix hors taxes</span>
                    <strong><?= number_format($produit['prix_ht'], 2, ',', ' ') ?> €</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>TVA appliquée</span>
                    <strong><?= number_format($produit['tva_pourcentage'], 0) ?>%</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Prix TTC initial</span>
                    <strong><?= number_format($prixTtc, 2, ',', ' ') ?> €</strong>
                </li>
                <?php if ($aRemise): ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Remise appliquée</span>
                        <span class="badge bg-warning text-dark">
                            -<?= number_format($produit['remise_pourcentage'], 0) ?>%
                        </span>
                    </li>
                <?php endif; ?>
            </ul>
            <div class="card-footer d-flex justify-content-between align-items-center">
                <strong>Prix final TTC :</strong>
                <span class="fs-3 text-primary">
                    <?= number_format($prixFinalTtc, 2, ',', ' ') ?> €
                </span>
            </div>
        </div>
<form method="POST" action="panier.php" class="d-flex gap-2 mb-3">
    <input type="hidden" name="action" value="ajouter">
    <input type="hidden" name="id_produit" value="<?= $produit['id_produit'] ?>">
    <label class="form-label me-2 align-self-center">Quantité :</label>
    <input type="number" name="quantite" value="1" min="1" class="form-control" style="width: 100px;">
    <button type="submit" class="btn btn-success">Ajouter au panier</button>
</form>
        <a href="produits.php" class="btn btn-outline-secondary">← Retour à la liste</a>

    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>