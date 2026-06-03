<?php
// =====================================================================
// FP3 — Suppression d'un produit (avec confirmation)
// =====================================================================

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/config/db.php';

// L'id peut venir de l'URL (GET, lors de la confirmation) ou du formulaire (POST, lors de la suppression)
$id = isset($_GET['id']) ? (int) $_GET['id'] : (int) ($_POST['id'] ?? 0);

if ($id <= 0) {
    die("Identifiant produit invalide.");
}

// Récupérer le produit pour vérifier qu'il existe et afficher son nom
$stmt = $pdo->prepare("SELECT * FROM produits WHERE id_produit = ?");
$stmt->execute([$id]);
$produit = $stmt->fetch();

if (!$produit) {
    die("Produit introuvable.");
}

$succes = '';
$erreur = '';

// Si POST : on exécute la suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("DELETE FROM produits WHERE id_produit = ?");
        $stmt->execute([$id]);
        $succes = "Le produit « " . $produit['nom'] . " » a bien été supprimé.";
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
    <title>Brico'brac — Suppression d'un produit</title>
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
        <?php if ($succes !== ''): ?>
            <p style="color: green;"><?= htmlspecialchars($succes) ?></p>
            <p><a href="admin_produits.php">← Retour à la liste</a></p>

        <?php elseif ($erreur !== ''): ?>
            <p style="color: red;"><?= htmlspecialchars($erreur) ?></p>
            <p><a href="admin_produits.php">← Retour à la liste</a></p>

        <?php else: ?>
            <h2>Confirmation de suppression</h2>

            <p>Êtes-vous sûr de vouloir supprimer le produit suivant ?</p>

            <ul>
                <li><strong>Nom :</strong> <?= htmlspecialchars($produit['nom']) ?></li>
                <li><strong>Référence :</strong> <?= htmlspecialchars($produit['reference']) ?></li>
                <li><strong>Prix HT :</strong> <?= number_format($produit['prix_ht'], 2, ',', ' ') ?> €</li>
            </ul>

            <p style="color: red;"><strong>Cette action est irréversible.</strong></p>

            <form method="POST" action="admin_suppression_produit.php">
                <input type="hidden" name="id" value="<?= $id ?>">
                <button type="submit">Oui, supprimer définitivement</button>
                <a href="admin_produits.php">Annuler</a>
            </form>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Brico'brac</p>
    </footer>
</body>
</html>