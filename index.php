<?php
// Page d'accueil de Brico'brac (FP1)
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brico'brac — Accueil</title>
</head>
<body>
  <header>
    <h1>Brico'brac</h1>
    <nav>
        <a href="index.php">Accueil</a> |
        <a href="produits.php">Liste des produits</a> |
        <a href="connexion.php">Connexion admin</a>
    </nav>
  </header>

    <main>
        <p>Bienvenue sur Brico'brac ! La référence du magasin de bricolage près de chez vous !</p>
        <p><a href="produits.php">Voir tous nos produits</a></p>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Brico'brac</p>
    </footer>
</body>
</html>