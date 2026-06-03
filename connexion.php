<?php
// =====================================================================
// FP3 — Page de connexion administrateur
// =====================================================================

session_start();
require __DIR__ . '/config/db.php';

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

$titrePage = "Connexion";
require __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">

        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white text-center">
                <h3 class="mb-0">Connexion administrateur</h3>
            </div>
            <div class="card-body p-4">

                <?php if ($erreur !== ''): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
                <?php endif; ?>

                <form method="POST" action="connexion.php">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="mot_de_passe" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                </form>

            </div>
        </div>

    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>