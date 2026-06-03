<?php
// =====================================================================
// FP2 — Page détail d'un produit
// =====================================================================

require __DIR__ . '/config/db.php';

// Récupération et validation de l'id depuis l'URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("Identifiant produit invalide.");
}

// Requête préparée — on ne concatène jamais une variable dans une requête
$stmt = $pdo->prepare("SELECT * FROM produits WHERE id_produit = ?");
$stmt->execute([$id]);
$produit = $stmt->fetch();

// Cas où le produit n'existe pas
if (!$produit) {
    die("Produit introuvable.");
}

// Calculs des prix
$prixTtc      = $produit['prix_ht'] * (1 + $produit['tva_pourcentage'] / 100);
$prixFinalTtc = $prixTtc * (1 - $produit['remise_pourcentage'] / 100);
$aRemise      = $produit['remise_pourcentage'] > 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brico'brac — <?= htmlspecialchars($produit['nom']) ?></title>
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
        <h2><?= htmlspecialchars($produit['nom']) ?></h2>

        <h3>Informations sur le produit</h3>
        <ul>
            <li>Référence produit : <?= htmlspecialchars($produit['reference']) ?></li>
            <li>Nouveauté : <?= $produit['est_nouveaute'] ? 'oui' : 'non' ?></li>
            <li>Prix hors taxes : <?= number_format($produit['prix_ht'], 2, ',', ' ') ?> €</li>
            <li>TVA appliquée : <?= number_format($produit['tva_pourcentage'], 0) ?>%</li>
            <li>Prix TTC initial : <?= number_format($prixTtc, 2, ',', ' ') ?> €</li>
            <?php if ($aRemise): ?>
                <li>Remise appliquée : <?= number_format($produit['remise_pourcentage'], 0) ?>%</li>
            <?php endif; ?>
        </ul>

        <p><strong>Prix final TTC : <?= number_format($prixFinalTtc, 2, ',', ' ') ?> €</strong></p>

        <p><a href="produits.php">← Retour à la liste</a></p>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Brico'brac</p>
    </footer>
</body>
</html>