<?php
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/config/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) { die("Identifiant utilisateur invalide."); }

$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = ?");
$stmt->execute([$id]);
$utilisateur = $stmt->fetch();
if (!$utilisateur) { die("Utilisateur introuvable."); }

$erreur = '';
$succes = '';
$email  = $utilisateur['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email                  = trim($_POST['email'] ?? '');
    $motDePasse             = $_POST['mot_de_passe'] ?? '';
    $motDePasseConfirmation = $_POST['mot_de_passe_confirmation'] ?? '';

    if ($email === '') {
        $erreur = 'L\'email est obligatoire.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = 'L\'email fourni n\'est pas valide.';
    }
    if ($erreur === '' && $motDePasse !== '') {
        if (strlen($motDePasse) < 6) {
            $erreur = 'Le nouveau mot de passe doit contenir au moins 6 caractères.';
        } elseif ($motDePasse !== $motDePasseConfirmation) {
            $erreur = 'Les deux mots de passe ne correspondent pas.';
        }
    }

    if ($erreur === '') {
        try {
            if ($motDePasse !== '') {
                $hash = password_hash($motDePasse, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE utilisateurs SET email = ?, mot_de_passe = ? WHERE id_utilisateur = ?");
                $stmt->execute([$email, $hash, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE utilisateurs SET email = ? WHERE id_utilisateur = ?");
                $stmt->execute([$email, $id]);
            }
            if ($id === (int) $_SESSION['id_utilisateur']) {
                $_SESSION['email'] = $email;
            }
            $succes = "L'utilisateur « $email » a bien été modifié.";
        } catch (PDOException $e) {
            $erreur = $e->getCode() === '23000'
                ? 'Cet email est déjà utilisé par un autre compte.'
                : 'Erreur lors de la modification : ' . $e->getMessage();
        }
    }
}

$titrePage = "Modifier un utilisateur";
require __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">

        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Modifier l'utilisateur</h4>
            </div>
            <div class="card-body p-4">

                <?php if ($succes !== ''): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($succes) ?></div>
                    <a href="admin_utilisateurs.php" class="btn btn-outline-secondary">← Retour à la liste</a>
                <?php endif; ?>

                <?php if ($erreur !== ''): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
                <?php endif; ?>

                <form method="POST" action="admin_modification_utilisateur.php?id=<?= $id ?>">
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
                    </div>
                    <p class="text-muted"><em>Laissez les champs mot de passe vides pour conserver l'actuel.</em></p>
                    <div class="mb-3">
                        <label class="form-label">Nouveau mot de passe (optionnel)</label>
                        <input type="password" name="mot_de_passe" class="form-control" minlength="6">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirmer le nouveau mot de passe</label>
                        <input type="password" name="mot_de_passe_confirmation" class="form-control" minlength="6">
                    </div>
                    <button type="submit" class="btn btn-warning">Enregistrer les modifications</button>
                    <a href="admin_utilisateurs.php" class="btn btn-outline-secondary">Annuler</a>
                </form>

            </div>
        </div>

    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>