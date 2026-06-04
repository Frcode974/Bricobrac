<?php
// =====================================================================
// FP3 — Tableau de bord administrateur
// =====================================================================

require __DIR__ . '/includes/auth.php';

$titrePage = "Administration";
require __DIR__ . '/includes/header.php';
?>

<h2 class="mb-1">Tableau de bord</h2>
<p class="text-muted mb-4">
    Bonjour <strong><?= htmlspecialchars($_SESSION['email']) ?></strong>,
    connecté en tant que <span class="badge bg-primary"><?= htmlspecialchars($_SESSION['role']) ?></span>.
</p>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Gestion des produits</h5>
                <p class="card-text text-muted">
                    Consulter, ajouter, modifier ou supprimer les produits du catalogue.
                </p>
                <a href="admin_produits.php" class="btn btn-primary">Accéder</a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Gestion des utilisateurs</h5>
                <p class="card-text text-muted">
                    Créer, modifier ou supprimer les comptes administrateurs.
                </p>
                <a href="admin_utilisateurs.php" class="btn btn-primary">Accéder</a>
            </div>
        </div>
    </div>
</div>

<div class="col-md-6">
    <div class="card shadow-sm h-100">
        <div class="card-body">
            <h5 class="card-title">Gestion des catégories</h5>
            <p class="card-text text-muted">Créer et consulter les catégories de produits.</p>
            <a href="admin_categories.php" class="btn btn-primary">Accéder</a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>