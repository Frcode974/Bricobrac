<?php
// =====================================================================
// FP4 — Page panier : affichage + gestion (ajout, modif, retrait, validation)
// =====================================================================

require __DIR__ . '/includes/panier.php';
require __DIR__ . '/config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Traitement des actions POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action     = $_POST['action'] ?? '';
    $idProduit  = (int) ($_POST['id_produit'] ?? 0);
    $quantite   = (int) ($_POST['quantite'] ?? 0);

    if ($action === 'ajouter') {
        ajouterAuPanier($idProduit, $quantite > 0 ? $quantite : 1);
        $_SESSION['message_panier'] = 'Produit ajouté au panier.';
    } elseif ($action === 'modifier') {
        modifierQuantitePanier($idProduit, $quantite);
        $_SESSION['message_panier'] = 'Quantité mise à jour.';
    } elseif ($action === 'retirer') {
        retirerDuPanier($idProduit);
        $_SESSION['message_panier'] = 'Produit retiré du panier.';
    } elseif ($action === 'valider') {
        viderPanier();
        $_SESSION['message_validation'] = 'Votre commande a été enregistrée.';
    }

    header('Location: panier.php');
    exit;
}

// Récupération des messages de session, puis nettoyage
$message            = $_SESSION['message_panier']     ?? '';
$messageValidation  = $_SESSION['message_validation'] ?? '';
unset($_SESSION['message_panier'], $_SESSION['message_validation']);

// Lecture du panier
$panier = getPanier();

// Récupération des détails des produits du panier
$produitsPanier = [];
$total = 0;
if (!empty($panier)) {
    $ids = array_keys($panier);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE id_produit IN ($placeholders)");
    $stmt->execute($ids);
    foreach ($stmt->fetchAll() as $produit) {
        $quantite     = (int) $panier[$produit['id_produit']];
        $prixTtc      = $produit['prix_ht'] * (1 + $produit['tva_pourcentage'] / 100);
        $prixFinalTtc = $prixTtc * (1 - $produit['remise_pourcentage'] / 100);
        $sousTotal    = $prixFinalTtc * $quantite;
        $total       += $sousTotal;

        $produit['quantite']      = $quantite;
        $produit['prix_unitaire'] = $prixFinalTtc;
        $produit['sous_total']    = $sousTotal;
        $produitsPanier[] = $produit;
    }
}

$titrePage = "Mon panier";
require __DIR__ . '/includes/header.php';
?>

<h2 class="mb-4">Mon panier</h2>

<?php if ($messageValidation !== ''): ?>
    <div class="alert alert-success">
        <h4 class="alert-heading">Commande enregistrée !</h4>
        <p class="mb-0"><?= htmlspecialchars($messageValidation) ?></p>
    </div>
    <a href="produits.php" class="btn btn-primary">← Retour au catalogue</a>

<?php elseif (empty($produitsPanier)): ?>
    <div class="alert alert-info">Votre panier est vide.</div>
    <a href="produits.php" class="btn btn-primary">← Voir les produits</a>

<?php else: ?>

    <?php if ($message !== ''): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="table-responsive bg-white p-3 rounded shadow-sm">
        <table class="table align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Produit</th>
                    <th>Prix unitaire TTC</th>
                    <th class="text-center" style="width: 180px;">Quantité</th>
                    <th class="text-end">Sous-total</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produitsPanier as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nom']) ?></td>
                        <td><?= number_format($p['prix_unitaire'], 2, ',', ' ') ?> €</td>
                        <td>
                            <form method="POST" action="panier.php" class="d-flex gap-2 justify-content-center">
                                <input type="hidden" name="action" value="modifier">
                                <input type="hidden" name="id_produit" value="<?= $p['id_produit'] ?>">
                                <input type="number" name="quantite" value="<?= $p['quantite'] ?>" min="1" class="form-control form-control-sm" style="width: 80px;">
                                <button type="submit" class="btn btn-outline-warning btn-sm">Mettre à jour</button>
                            </form>
                        </td>
                        <td class="text-end"><strong><?= number_format($p['sous_total'], 2, ',', ' ') ?> €</strong></td>
                        <td class="text-center">
                            <form method="POST" action="panier.php">
                                <input type="hidden" name="action" value="retirer">
                                <input type="hidden" name="id_produit" value="<?= $p['id_produit'] ?>">
                                <button type="submit" class="btn btn-outline-danger btn-sm">Retirer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="table-light">
                    <td colspan="3" class="text-end"><strong>Total TTC :</strong></td>
                    <td class="text-end"><strong class="fs-5 text-primary"><?= number_format($total, 2, ',', ' ') ?> €</strong></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="d-flex justify-content-between mt-3">
        <a href="produits.php" class="btn btn-outline-secondary">← Continuer mes achats</a>
        <form method="POST" action="panier.php">
            <input type="hidden" name="action" value="valider">
            <button type="submit" class="btn btn-success btn-lg">Valider ma commande</button>
        </form>
    </div>

<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>