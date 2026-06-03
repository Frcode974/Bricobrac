<?php
// =====================================================================
// FP3 — Tableau de bord administrateur
// =====================================================================

require __DIR__ . '/includes/auth.php';
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
    <li><a href="admin_produits.php">Gestion des produits</a></li>
    <li><a href="admin_utilisateurs.php">Gestion des utilisateurs</a></li>
</ul>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Brico'brac</p>
    </footer>
</body>
</html>