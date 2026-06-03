<?php
// =====================================================================
// FP3 — Ajout d'un nouveau produit
// =====================================================================

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/config/db.php';

$erreur = '';
$succes = '';

// Valeurs par défaut (préservées en cas d'erreur, réinitialisées en cas de succès)
$nom                = '';
$reference          = '';
$prix_ht            = '';
$tva_pourcentage    = '20';
$remise_pourcentage = '0';
$est_nouveaute      = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des valeurs saisies
    $nom                = trim($_POST['nom'] ?? '');
    $reference          = trim($_POST['reference'] ?? '');
    $prix_ht            = trim($_POST['prix_ht'] ?? '');
    $tva_pourcentage    = trim($_POST['tva_pourcentage'] ?? '');
    $remise_pourcentage = trim($_POST['remise_pourcentage'] ?? '0');
    $est_nouveaute      = isset($_POST['est_nouveaute']) ? 1 : 0;

    // Validation
    if ($nom === '' || $reference === '' || $prix_ht === '' || $tva_pourcentage === '') {
        $erreur = 'Tous les champs obligatoires doivent être remplis.';
    } elseif (!is_numeric($prix_ht) || $prix_ht <= 0) {
        $erreur = 'Le prix HT doit être un nombre positif.';
    } elseif (!is_numeric($tva_pourcentage) || $tva_pourcentage < 0) {
        $erreur = 'La TVA doit être un nombre positif ou nul.';
    } elseif (!is_numeric($remise_pourcentage) || $remise_pourcentage < 0 || $remise_pourcentage > 100) {
        $erreur = 'La remise doit être un nombre entre 0 et 100.';
    } else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO produits (nom, reference, prix_ht, tva_pourcentage, remise_pourcentage, est_nouveaute)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $nom,
                $reference,
                (float) $prix_ht,
                (float) $tva_pourcentage,
                (float) $remise_pourcentage,
                $est_nouveaute,
            ]);

            $succes = "Le produit « $nom » a bien été ajouté.";

            // Réinitialiser les champs pour permettre un nouvel ajout
            $nom                = '';
            $reference          = '';
            $prix_ht            = '';
            $tva_pourcentage    = '20';
            $remise_pourcentage = '0';
            $est_nouveaute      = 0;

        } catch (PDOException $e) {
            // Code 23000 = violation de contrainte (probablement la référence UNIQUE)
            if ($e->getCode() === '23000') {
                $erreur = 'Cette référence existe déjà dans la base.';
            } else {
                $erreur = 'Erreur lors de l\'ajout : ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brico'brac — Ajouter un produit</title>
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
        <h2>Ajouter un nouveau produit</h2>

        <?php if ($succes !== ''): ?>
            <p style="color: green;"><?= htmlspecialchars($succes) ?></p>
            <p><a href="admin_produits.php">← Retour à la liste</a></p>
        <?php endif; ?>

        <?php if ($erreur !== ''): ?>
            <p style="color: red;"><?= htmlspecialchars($erreur) ?></p>
        <?php endif; ?>

        <form method="POST" action="admin_ajout_produit.php">
            <p>
                <label>Nom du produit * :<br>
                    <input type="text" name="nom" value="<?= htmlspecialchars($nom) ?>" required>
                </label>
            </p>
            <p>
                <label>Référence * :<br>
                    <input type="text" name="reference" value="<?= htmlspecialchars($reference) ?>" required>
                </label>
            </p>
            <p>
                <label>Prix HT (€) * :<br>
                    <input type="number" step="0.01" min="0" name="prix_ht" value="<?= htmlspecialchars($prix_ht) ?>" required>
                </label>
            </p>
            <p>
                <label>TVA (%) * :<br>
                    <input type="number" step="0.01" min="0" name="tva_pourcentage" value="<?= htmlspecialchars($tva_pourcentage) ?>" required>
                </label>
            </p>
            <p>
                <label>Remise (%) :<br>
                    <input type="number" step="0.01" min="0" max="100" name="remise_pourcentage" value="<?= htmlspecialchars($remise_pourcentage) ?>">
                </label>
            </p>
            <p>
                <label>
                    <input type="checkbox" name="est_nouveaute" value="1" <?= $est_nouveaute ? 'checked' : '' ?>>
                    Marquer comme nouveauté
                </label>
            </p>
            <p>
                <button type="submit">Ajouter le produit</button>
                <a href="admin_produits.php">Annuler</a>
            </p>
        </form>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Brico'brac</p>
    </footer>
</body>
</html>