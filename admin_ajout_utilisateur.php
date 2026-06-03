<?php
// =====================================================================
// FP3 — Ajout d'un nouvel utilisateur (admin)
// =====================================================================

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/config/db.php';

$erreur = '';
$succes = '';
$email  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email                    = trim($_POST['email'] ?? '');
    $motDePasse               = $_POST['mot_de_passe'] ?? '';
    $motDePasseConfirmation   = $_POST['mot_de_passe_confirmation'] ?? '';

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
            $motDePasseHash = password_hash($motDePasse, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO utilisateurs (email, mot_de_passe, role)
                VALUES (?, ?, 'admin')
            ");
            $stmt->execute([$email, $motDePasseHash]);

            $succes = "L'utilisateur « $email » a bien été ajouté.";
            $email  = ''; // Réinitialiser pour permettre un nouvel ajout

        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $erreur = 'Un utilisateur avec cet email existe déjà.';
            } else {
                $erreur = 'Erreur lors de l\'ajout : ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brico'brac — Ajouter un utilisateur</title>
</head>
<body>
    <header>
        <h1>Brico'brac — Administration</h1>
        <nav>
            <a href="admin.php">Tableau de bord</a> |
            <a href="admin_produits.php">Gestion des produits</a> |
            <a href="admin_utilisateurs.php">Gestion des utilisateurs</a> |
            <a href="deconnexion.php">Se déconnecter</a>
        </nav>
    </header>

    <main>
        <h2>Ajouter un nouvel administrateur</h2>

        <?php if ($succes !== ''): ?>
            <p style="color: green;"><?= htmlspecialchars($succes) ?></p>
            <p><a href="admin_utilisateurs.php">← Retour à la liste</a></p>
        <?php endif; ?>

        <?php if ($erreur !== ''): ?>
            <p style="color: red;"><?= htmlspecialchars($erreur) ?></p>
        <?php endif; ?>

        <form method="POST" action="admin_ajout_utilisateur.php">
            <p>
                <label>Email * :<br>
                    <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                </label>
            </p>
            <p>
                <label>Mot de passe * (6 caractères minimum) :<br>
                    <input type="password" name="mot_de_passe" minlength="6" required>
                </label>
            </p>
            <p>
                <label>Confirmer le mot de passe * :<br>
                    <input type="password" name="mot_de_passe_confirmation" minlength="6" required>
                </label>
            </p>
            <p>
                <button type="submit">Créer l'administrateur</button>
                <a href="admin_utilisateurs.php">Annuler</a>
            </p>
        </form>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Brico'brac</p>
    </footer>
</body>
</html>