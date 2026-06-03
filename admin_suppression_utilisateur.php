<?php
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/config/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : (int) ($_POST['id'] ?? 0);
if ($id <= 0) { die("Identifiant utilisateur invalide."); }

if ($id === (int) $_SESSION['id_utilisateur']) {
    die("Vous ne pouvez pas supprimer votre propre compte.");
}

$stmt = $pdo->prepare("SELECT id_utilisateur, email, role FROM utilisateurs WHERE id_utilisateur = ?");
$stmt->execute([$id]);
$utilisateur = $stmt->fetch();
if (!$utilisateur) { die("Utilisateur introuvable."); }

$succes = '';
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
        $stmt->execute([$id]);
        $succes = "L'utilisateur « " . $utilisateur['email'] . " » a bien été supprimé.";
    } catch (PDOException $e) {
        $erreur = "Erreur lors de la suppression : " . $e->getMessage();
    }
}

$titrePage = "Supprimer un utilisateur";
require __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-6">

        <?php if ($succes !== ''): ?>
            <div class="alert alert-success"><?= htmlspecialchars($succes) ?></div>
            <a href="admin_utilisateurs.php" class="btn btn-outline-secondary">← Retour à la liste</a>

        <?php elseif ($erreur !== ''): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
            <a href="admin_utilisateurs.php" class="btn btn-outline-secondary">← Retour à la liste</a>

        <?php else: ?>
            <div class="card shadow-sm border-danger">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">Confirmation de suppression</h4>
                </div>
                <div class="card-body">
                    <p>Êtes-vous sûr de vouloir supprimer l'utilisateur suivant ?</p>
                    <ul class="list-group mb-3">
                        <li class="list-group-item"><strong>Email :</strong> <?= htmlspecialchars($utilisateur['email']) ?></li>
                        <li class="list-group-item"><strong>Rôle :</strong> <?= htmlspecialchars($utilisateur['role']) ?></li>
                    </ul>
                    <div class="alert alert-warning">Cette action est <strong>irréversible</strong>.</div>
                    <form method="POST" action="admin_suppression_utilisateur.php">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <button type="submit" class="btn btn-danger">Oui, supprimer définitivement</button>
                        <a href="admin_utilisateurs.php" class="btn btn-outline-secondary">Annuler</a>
                    </form>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>