<?php
// =====================================================================
// FP3 — Page de connexion administrateur
// =====================================================================

session_start();
require __DIR__ . '/config/db.php';

// Si déjà connecté, rediriger directement vers l'admin
if (isset($_SESSION['id_utilisateur'])) {
    header('Location: admin.php');
    exit;
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email      = trim($_POST['email'] ?? '');
    $motDePasse = $_POST['mot_de_passe'] ?? '';

    if ($email === '' || $motDePasse === '') {
        $erreur = 'Email et mot de passe requis.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $utilisateur = $stmt->fetch();

        if ($utilisateur && password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
            // Connexion réussie : on enregistre l'utilisateur en session
            $_SESSION['id_utilisateur'] = $utilisateur['id_utilisateur'];
            $_SESSION['email']          = $utilisateur['email'];
            $_SESSION['role']           = $utilisateur['role'];

            header('Location: admin.php');
            exit;
        } else {
            $erreur = 'Email ou mot de passe incorrect.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brico'brac — Connexion</title>
</head>
<body>
    <header>
        <h1>Brico'brac</h1>
        <nav>
            <a href="index.php">Accueil</a> |
            <a href="produits.php">Liste des produits</a>
        </nav>
    </header>

    <main>
        <h2>Connexion administrateur</h2>

        <?php if ($erreur !== ''): ?>
            <p style="color: red;"><?= htmlspecialchars($erreur) ?></p>
        <?php endif; ?>

        <form method="POST" action="connexion.php">
            <p>
                <label>Email :<br>
                    <input type="email" name="email" required>
                </label>
            </p>
            <p>
                <label>Mot de passe :<br>
                    <input type="password" name="mot_de_passe" required>
                </label>
            </p>
            <p>
                <button type="submit">Se connecter</button>
            </p>
        </form>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Brico'brac</p>
    </footer>
</body>
</html>