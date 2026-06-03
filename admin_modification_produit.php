<?php
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/config/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) { die("Identifiant produit invalide."); }

$stmt = $pdo->prepare("SELECT * FROM produits WHERE id_produit = ?");
$stmt->execute([$id]);
$produit = $stmt->fetch();
if (!$produit) { die("Produit introuvable."); }

$erreur = '';
$succes = '';

$nom                = $produit['nom'];
$reference          = $produit['reference'];
$prix_ht            = $produit['prix_ht'];
$tva_pourcentage    = $produit['tva_pourcentage'];
$remise_pourcentage = $produit['remise_pourcentage'];
$est_nouveaute      = $produit['est_nouveaute'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom                = trim($_POST['nom'] ?? '');
    $reference          = trim($_POST['reference'] ?? '');
    $prix_ht            = trim($_POST['prix_ht'] ?? '');
    $tva_pourcentage    = trim($_POST['tva_pourcentage'] ?? '');
    $remise_pourcentage = trim($_POST['remise_pourcentage'] ?? '0');
    $est_nouveaute      = isset($_POST['est_nouveaute']) ? 1 : 0;

    if ($nom === '' || $reference === '' || $prix_ht === '' || $tva_pourcentage === '') {
        $erreur = 'Tous les champs obligatoires doivent être remplis.';
    } elseif (!is_numeric($prix_ht) || $prix_ht <= 0) {
        $erreur = 'Le prix HT doit être un nombre positif.';
    } elseif (!is_numeric($tva_pourcentage) || $tva_pourcentage < 0) {
        $erreur = 'La TVA doit être un nombre positif ou nul.';
    } elseif (!is_numeric($remise_pourcentage) || $remise_pourcentage < 0 || $remise_pourcentage > 100) {
        $erreur = 'La remise doit être un nombre entre 0 et 100.';
    } else {
        try {
            $stmt = $pdo->prepare("
                UPDATE produits
                SET nom = ?, reference = ?, prix_ht = ?, tva_pourcentage = ?,
                    remise_pourcentage = ?, est_nouveaute = ?
                WHERE id_produit = ?
            ");
            $stmt->execute([
                $nom, $reference, (float)$prix_ht, (float)$tva_pourcentage,
                (float)$remise_pourcentage, $est_nouveaute, $id,
            ]);
            $succes = "Le produit « $nom » a bien été modifié.";
        } catch (PDOException $e) {
            $erreur = $e->getCode() === '23000'
                ? 'Cette référence existe déjà pour un autre produit.'
                : 'Erreur lors de la modification : ' . $e->getMessage();
        }
    }
}

$titrePage = "Modifier le produit";
require __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Modifier le produit</h4>
            </div>
            <div class="card-body p-4">

                <?php if ($succes !== ''): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($succes) ?></div>
                    <p><a href="admin_produits.php" class="btn btn-outline-secondary">← Retour à la liste</a></p>
                <?php endif; ?>

                <?php if ($erreur !== ''): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
                <?php endif; ?>

                <form method="POST" action="admin_modification_produit.php?id=<?= $id ?>">
                    <div class="mb-3">
                        <label class="form-label">Nom du produit *</label>
                        <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($nom) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Référence *</label>
                        <input type="text" name="reference" class="form-control" value="<?= htmlspecialchars($reference) ?>" required>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Prix HT (€) *</label>
                            <input type="number" step="0.01" min="0" name="prix_ht" class="form-control" value="<?= htmlspecialchars($prix_ht) ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">TVA (%) *</label>
                            <input type="number" step="0.01" min="0" name="tva_pourcentage" class="form-control" value="<?= htmlspecialchars($tva_pourcentage) ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Remise (%)</label>
                            <input type="number" step="0.01" min="0" max="100" name="remise_pourcentage" class="form-control" value="<?= htmlspecialchars($remise_pourcentage) ?>">
                        </div>
                    </div>
                    <div class="form-check mb-4">
                        <input type="checkbox" name="est_nouveaute" value="1" class="form-check-input" id="nouv" <?= $est_nouveaute ? 'checked' : '' ?>>
                        <label class="form-check-label" for="nouv">Marquer comme nouveauté</label>
                    </div>
                    <button type="submit" class="btn btn-warning">Enregistrer les modifications</button>
                    <a href="admin_produits.php" class="btn btn-outline-secondary">Annuler</a>
                </form>

            </div>
        </div>

    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>