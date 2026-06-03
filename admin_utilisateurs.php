<?php
// =====================================================================
// FP3 — Liste des utilisateurs administrateurs
// =====================================================================

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/config/db.php';

$stmt = $pdo->query("SELECT id_utilisateur, email, role, date_creation FROM utilisateurs ORDER BY email");
$utilisateurs = $stmt->fetchAll();

$idCourant = (int) $_SESSION['id_utilisateur'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brico'brac — Admin utilisateurs</title>
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
        <h2>Gestion des utilisateurs</h2>

        <p><a href="admin_ajout_utilisateur.php">+ Ajouter un nouvel utilisateur</a></p>

        <table border="1" cellpadding="6" cellspacing="0">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Date de création</th>
                    <th>Actions admin</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= htmlspecialchars($u['role']) ?></td>
                        <td><?= htmlspecialchars($u['date_creation']) ?></td>
                        <td>
                            <a href="admin_modification_utilisateur.php?id=<?= $u['id_utilisateur'] ?>">Modifier</a>
                            <?php if ((int) $u['id_utilisateur'] !== $idCourant): ?>
                                |
                                <a href="admin_suppression_utilisateur.php?id=<?= $u['id_utilisateur'] ?>">Supprimer</a>
                            <?php else: ?>
                                <em>(vous-même)</em>
                            <?php endif; ?>
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