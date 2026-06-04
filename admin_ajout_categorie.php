<?php
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/config/db.php';

$erreur = '';
$succes = '';
$nom = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');

    if ($nom === '') {
        $erreur = 'Le nom de la catégorie est obligatoire.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO categories (nom) VALUES (?)");
            $stmt->execute([$nom]);
            $succes = "La catégorie « $nom » a bien été ajoutée.";
            $nom = '';
        } catch (PDOException $e) {
            $erreur = $e->getCode() === '23000'
                ? 'Cette catégorie existe déjà.'
                : 'Erreur : ' . $e->getMessage();
        }
    }
}

$titrePage = "Ajouter une catégorie";
require __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Ajouter une catégorie</h4>
            </div>
            <div class="card-body p-4">

                <?php if ($succes !== ''): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($succes) ?></div>
                    <a href="admin_categories.php" class="btn btn-outline-secondary">← Retour à la liste</a>
                <?php endif; ?>

                <?php if ($erreur !== ''): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
                <?php endif; ?>

                <form method="POST" action="admin_ajout_categorie.php">
                    <div class="mb-3">
                        <label class="form-label">Nom de la catégorie *</label>
                        <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($nom) ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Créer la catégorie</button>
                    <a href="admin_categories.php" class="btn btn-outline-secondary">Annuler</a>
                </form>

            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>