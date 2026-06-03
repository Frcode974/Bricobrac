<?php
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/config/db.php';

$stmt = $pdo->query("SELECT id_utilisateur, email, role, date_creation FROM utilisateurs ORDER BY email");
$utilisateurs = $stmt->fetchAll();
$idCourant = (int) $_SESSION['id_utilisateur'];

$titrePage = "Gestion des utilisateurs";
require __DIR__ . '/includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Gestion des utilisateurs</h2>
    <a href="admin_ajout_utilisateur.php" class="btn btn-success">
        + Ajouter un nouvel utilisateur
    </a>
</div>

<div class="table-responsive bg-white p-3 rounded shadow-sm">
    <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>Email</th>
                <th>Rôle</th>
                <th>Date de création</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utilisateurs as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><span class="badge bg-primary"><?= htmlspecialchars($u['role']) ?></span></td>
                    <td><?= htmlspecialchars($u['date_creation']) ?></td>
                    <td class="text-center">
                        <a href="admin_modification_utilisateur.php?id=<?= $u['id_utilisateur'] ?>" class="btn btn-outline-warning btn-sm">
                            Modifier
                        </a>
                        <?php if ((int) $u['id_utilisateur'] !== $idCourant): ?>
                            <a href="admin_suppression_utilisateur.php?id=<?= $u['id_utilisateur'] ?>" class="btn btn-outline-danger btn-sm">
                                Supprimer
                            </a>
                        <?php else: ?>
                            <span class="badge bg-secondary">vous-même</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>