<?php
// =====================================================================
// FP3 — Modification d'un utilisateur
// =====================================================================

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/config/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    die("Identifiant utilisateur invalide.");
}

$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = ?");
$stmt->execute([$id]);
$utilisateur = $stmt->fetch();

if (!$utilisateur) {
    die("Utilisateur introuvable.");
}

$erreur = '';
$succes = '';
$email  = $utilisateur['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email                  = trim($_POST['email'] ?? '');
    $motDePasse             = $_POST['mot_de_passe'] ?? '';
    $motDePasseConfirmation = $_POST['mot_de_passe_confirmation'] ?? '';

    // Validation de l'email
    if ($email === '') {
        $erreur = 'L\'email est obligatoire.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = 'L\'email fourni n\'est pas valide.';
    }

    // Validation du mot de passe — UNIQUEMENT si l'utilisateur en saisit un
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
                // Modification avec nouveau mot de passe
                $hash = password_hash($motDePasse, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("
                    UPDATE utilisateurs
                    SET email = ?, mot_de_passe = ?
                    WHERE id_utilisateur = ?
                ");
                $stmt->execute([$email, $hash, $id]);
            } else {
                // Modification de l'email uniquement
                $stmt = $pdo->prepare("
                    UPDATE utilisateurs
                    SET email = ?
                    WHERE id_utilisateur = ?
                ");
                $stmt->execute([$email, $id]);
            }

            // Si on modifie son propre compte, mettre à jour la session
            if ($id === (int) $_SESSION['id_utilisateur']) {
                $_SESSION['email'] = $email;
            }

            $succes = "L'utilisateur « $email » a bien été modifié.";

        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $erreur = 'Cet email est déjà utilisé par un autre compte.';
            } else {
                $erreur = 'Erreur lors de la modification : ' . $e->getMessage();
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
    <title>Brico'brac — Modifier un utilisateur</title>
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
        <h2>Modifier l'utilisateur</h2>

        <?php if ($succes !== ''): ?>
            <p style="color: green;"><?= htmlspecialchars($succes) ?></p>
            <p><a href="admin_utilisateurs.php">← Retour à la liste</a></p>
        <?php endif; ?>

        <?php if ($erreur !== ''): ?>
            <p style="color: red;"><?= htmlspecialchars($erreur) ?></p>
        <?php endif; ?>

        <form method="POST" action="admin_modification_utilisateur.php?id=<?= $id ?>">
            <p>
                <label>Email * :<br>
                    <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                </label>
            </p>
            <p>
                <em>Laissez les champs mot de passe vides pour conserver l'actuel.</em>
            </p>
            <p>
                <label>Nouveau mot de passe (optionnel) :<br>
                    <input type="password" name="mot_de_passe" minlength="6">
                </label>
            </p>
            <p>
                <label>Confirmer le nouveau mot de passe :<br>
                    <input type="password" name="mot_de_passe_confirmation" minlength="6">
                </label>
            </p>
            <p>
                <button type="submit">Enregistrer les modifications</button>
                <a href="admin_utilisateurs.php">Annuler</a>
            </p>
        </form>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Brico'brac</p>
    </footer>
</body>
</html>