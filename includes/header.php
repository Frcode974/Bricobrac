<?php
// Si auth.php a été appelé avant, on a déjà session_start().
// Sinon on démarre la session pour pouvoir lire $_SESSION dans la nav.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$estConnecte = isset($_SESSION['id_utilisateur']);
$titrePage   = $titrePage ?? 'Brico\'brac';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brico'brac — <?= htmlspecialchars($titrePage) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">Brico'brac</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="produits.php">Produits</a></li>
                </ul>
                <ul class="navbar-nav">
                    <?php if ($estConnecte): ?>
                        <li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>
                        <li class="nav-item">
                            <a class="nav-link" href="deconnexion.php">
                                Déconnexion (<?= htmlspecialchars($_SESSION['email']) ?>)
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="connexion.php">Connexion admin</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container">