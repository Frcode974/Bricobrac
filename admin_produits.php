<?php
// =====================================================================
// FP3 — Liste des produits côté admin (CRUD)
// =====================================================================

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/config/db.php';

$stmt = $pdo->query("SELECT * FROM produits ORDER BY nom");
$produits = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brico'brac — Admin produits</title>
</head>
<body>
    <header>
        <h1>Brico'brac — Administration</h1>
        <nav>
            <a href="admin.php">Tableau de bord</a> |
            <a href="admin_produits.php">Gestion des produits</a> |
            <a href="deconnexion.php">Se déconnecter</a>
        </nav>
    </header>

    <main>
        <h2>Gestion des produits</h2>

        <p><a href="admin_ajout_produit.php">+ Ajouter un nouveau produit</a></p>

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
                    <th>Actions admin</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produits as $produit): ?>
                    <?php
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
                            <?= $aRemise ? number_format($produit['remise_pourcentage'], 0) . '%' : '-' ?>
                        </td>
                        <td><?= number_format($prixFinalTtc, 2, ',', ' ') ?> €</td>
                        <td><?= $estNouveaute ? 'Nouveauté' : '-' ?></td>
                        <td>
                            <a href="detail_produit.php?id=<?= $produit['id_produit'] ?>">Voir</a>
                        </td>
                        <td>
                            <a href="admin_modification_produit.php?id=<?= $produit['id_produit'] ?>">Modifier</a>
                            |
                            <a href="admin_suppression_produit.php?id=<?= $produit['id_produit'] ?>">Supprimer</a>
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