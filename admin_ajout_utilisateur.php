<?php
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/config/db.php';

$erreur = '';
$succes = '';
$email  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email                  = trim($_POST['email'] ?? '');
    $motDePasse             = $_POST['mot_de_passe'] ?? '';
    $motDePasseConfirmation = $_POST['mot_de_passe_confirmation'] ?? '';

    if ($email === '' || $motDePasse === '' || $motDePasseConfirmation === '') {
        $erreur = 'Tous les champs sont obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = 'L\'email fourni n\'est pas valide.';
    } elseif (strlen($motDePasse) < 6) {
        $erreur = 'Le mot de passe doit contenir au moins 6 caractères.';
    } elseif ($motDePasse !== $motDePasseConfirmation) {
        $erreur = 'Les deux mots de passe ne correspondent pas.';
    } else {
        try {
            $hash = password_hash($motDePasse, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (email, mot_de_passe, role) VALUES (?, ?, 'admin')");
            $stmt->execute([$email, $hash]);
            $succes = "L'utilisateur « $email » a bien été ajouté.";
            $email = '';
        } catch (PDOException $e) {
            $erreur = $e->getCode() === '23000'
                ? 'Un utilisateur avec cet email existe déjà.'
                : 'Erreur lors de l\'ajout : ' . $e->getMessage();
        }
    }
}

$titrePage = "Ajouter un utilisateur";
require __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">

        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Ajouter un nouvel administrateur</h4>
            </div>
            <div class="card-body p-4">

                <?php if ($succes !== ''): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($succes) ?></div>
                    <a href="admin_utilisateurs.php" class="btn btn-outline-secondary">← Retour à la liste</a>
                <?php endif; ?>

                <?php if ($erreur !== ''): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
                <?php endif; ?>

                <form method="POST" action="admin_ajout_utilisateur.php">
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe * <small class="text-muted">(6 caractères min.)</small></label>
                        <input type="password" name="mot_de_passe" class="form-control" minlength="6" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirmer le mot de passe *</label>
                        <input type="password" name="mot_de_passe_confirmation" class="form-control" minlength="6" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Créer l'administrateur</button>
                    <a href="admin_utilisateurs.php" class="btn btn-outline-secondary">Annuler</a>
                </form>

            </div>
        </div>

    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>