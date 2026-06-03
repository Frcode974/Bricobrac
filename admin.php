<?php
// =====================================================================
// FP3 — Tableau de bord administrateur
// =====================================================================

session_start();

// Vérification : seuls les utilisateurs connectés ont accès
if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: connexion.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Brico'brac — Administration</title>
</head>
<body>
    <header>
        <h1>Brico'brac — Administration</h1>
        <nav>
            <a href="index.php">Accueil</a> |
            <a href="produits.php">Liste publique</a> |
            <a href="deconnexion.php">Se déconnecter</a>
        </nav>
    </header>

    <main>
        <h2>Tableau de bord</h2>
        <p>Bonjour <?= htmlspecialchars($_SESSION['email']) ?>, vous êtes connecté en tant que <strong><?= htmlspecialchars($_SESSION['role']) ?></strong>.</p>

        <h3>Actions disponibles</h3>
        <ul>
            <li>Gestion des produits (à venir)</li>
            <li>Gestion des utilisateurs (à venir)</li>
        </ul>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Brico'brac</p>
    </footer>
</body>
</html>