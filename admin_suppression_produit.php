<?php
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/config/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : (int) ($_POST['id'] ?? 0);
if ($id <= 0) { die("Identifiant produit invalide."); }

$stmt = $pdo->prepare("SELECT * FROM produits WHERE id_produit = ?");
$stmt->execute([$id]);
$produit = $stmt->fetch();
if (!$produit) { die("Produit introuvable."); }

$succes = '';
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("DELETE FROM produits WHERE id_produit = ?");
        $stmt->execute([$id]);
        $succes = "Le produit « " . $produit['nom'] . " » a bien été supprimé.";
    } catch (PDOException $e) {
        $erreur = "Erreur lors de la suppression : " . $e->getMessage();
    }
}

$titrePage = "Supprimer un produit";
require __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-6">

        <?php if ($succes !== ''): ?>
            <div class="alert alert-success"><?= htmlspecialchars($succes) ?></div>
            <a href="admin_produits.php" class="btn btn-outline-secondary">← Retour à la liste</a>

        <?php elseif ($erreur !== ''): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
            <a href="admin_produits.php" class="btn btn-outline-secondary">← Retour à la liste</a>

        <?php else: ?>
            <div class="card shadow-sm border-danger">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">Confirmation de suppression</h4>
                </div>
                <div class="card-body">
                    <p>Êtes-vous sûr de vouloir supprimer le produit suivant ?</p>
                    <ul class="list-group mb-3">
                        <li class="list-group-item"><strong>Nom :</strong> <?= htmlspecialchars($produit['nom']) ?></li>
                        <li class="list-group-item"><strong>Référence :</strong> <?= htmlspecialchars($produit['reference']) ?></li>
                        <li class="list-group-item"><strong>Prix HT :</strong> <?= number_format($produit['prix_ht'], 2, ',', ' ') ?> €</li>
                    </ul>
                    <div class="alert alert-warning">Cette action est <strong>irréversible</strong>.</div>
                    <form method="POST" action="admin_suppression_produit.php">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <button type="submit" class="btn btn-danger">Oui, supprimer définitivement</button>
                        <a href="admin_produits.php" class="btn btn-outline-secondary">Annuler</a>
                    </form>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>