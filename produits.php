<?php
// =====================================================================
// FP2 — Liste publique des produits
// =====================================================================

require __DIR__ . '/config/db.php';

// Récupération de tous les produits, triés par nom
$stmt = $pdo->query("SELECT * FROM produits ORDER BY nom");
$produits = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brico'brac — Liste des produits</title>
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
        <h2>Liste des produits</h2>

        <table border="1" cellpadding="6" cellspacing="0">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prix HT</th>
                    <th>Prix TTC</th>
                    <th>Remise %</th>
                    <th>Prix final</th>
                    <th>Statut</th>
                    <th>Détails</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produits as $produit): ?>
                    <?php
                    // Calculs des prix
                    $prixTtc      = $produit['prix_ht'] * (1 + $produit['tva_pourcentage'] / 100);
                    $prixFinalTtc = $prixTtc * (1 - $produit['remise_pourcentage'] / 100);
                    $aRemise      = $produit['remise_pourcentage'] > 0;
                    $estNouveaute = $produit['est_nouveaute'] == 1;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($produit['nom']) ?></td>
                        <td><?= number_format($produit['prix_ht'], 2, ',', ' ') ?> €</td>
                        <td><?= number_format($prixTtc, 2, ',', ' ') ?> €</td>
                        <td>
                            <?php if ($aRemise): ?>
                                <?= number_format($produit['remise_pourcentage'], 0) ?>%
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><?= number_format($prixFinalTtc, 2, ',', ' ') ?> €</td>
                        <td>
                            <?php if ($estNouveaute): ?>
                                Nouveauté
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="produit.php?id=<?= $produit['id_produit'] ?>">Voir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Brico'brac</p>
    </footer>
</body>
</html>