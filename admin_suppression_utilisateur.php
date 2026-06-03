<?php
// =====================================================================
// FP3 — Suppression d'un utilisateur (avec confirmation)
// =====================================================================

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/config/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : (int) ($_POST['id'] ?? 0);

if ($id <= 0) {
    die("Identifiant utilisateur invalide.");
}

// SÉCURITÉ — on ne peut pas se supprimer soi-même
if ($id === (int) $_SESSION['id_utilisateur']) {
    die("Vous ne pouvez pas supprimer votre propre compte.");
}

// Récupérer l'utilisateur pour confirmation
$stmt = $pdo->prepare("SELECT id_utilisateur, email, role FROM utilisateurs WHERE id_utilisateur = ?");
$stmt->execute([$id]);
$utilisateur = $stmt->fetch();

if (!$utilisateur) {
    die("Utilisateur introuvable.");
}

$succes = '';
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
        $stmt->execute([$id]);
        $succes = "L'utilisateur « " . $utilisateur['email'] . " » a bien été supprimé.";
    } catch (PDOException $e) {
        $erreur = "Erreur lors de la suppression : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brico'brac — Suppression d'un utilisateur</title>
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
        <?php if ($succes !== ''): ?>
            <p style="color: green;"><?= htmlspecialchars($succes) ?></p>
            <p><a href="admin_utilisateurs.php">← Retour à la liste</a></p>

        <?php elseif ($erreur !== ''): ?>
            <p style="color: red;"><?= htmlspecialchars($erreur) ?></p>
            <p><a href="admin_utilisateurs.php">← Retour à la liste</a></p>

        <?php else: ?>
            <h2>Confirmation de suppression</h2>

            <p>Êtes-vous sûr de vouloir supprimer l'utilisateur suivant ?</p>

            <ul>
                <li><strong>Email :</strong> <?= htmlspecialchars($utilisateur['email']) ?></li>
                <li><strong>Rôle :</strong> <?= htmlspecialchars($utilisateur['role']) ?></li>
            </ul>

            <p style="color: red;"><strong>Cette action est irréversible.</strong></p>

            <form method="POST" action="admin_suppression_utilisateur.php">
                <input type="hidden" name="id" value="<?= $id ?>">
                <button type="submit">Oui, supprimer définitivement</button>
                <a href="admin_utilisateurs.php">Annuler</a>
            </form>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Brico'brac</p>
    </footer>
</body>
</html>