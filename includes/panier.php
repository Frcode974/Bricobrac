<?php
// =====================================================================
// FP4 — Fonctions utilitaires pour le panier (cookies)
// =====================================================================

function getPanier(): array
{
    $json = $_COOKIE['panier'] ?? '{}';
    $panier = json_decode($json, true);
    return is_array($panier) ? $panier : [];
}

function savePanier(array $panier): void
{
    $valeur = json_encode($panier);
    $expiration = time() + 30 * 24 * 60 * 60; // 30 jours
    setcookie('panier', $valeur, $expiration, '/');
    $_COOKIE['panier'] = $valeur; // pour que la même requête en ait connaissance
}

function ajouterAuPanier(int $idProduit, int $quantite = 1): void
{
    if ($idProduit <= 0 || $quantite <= 0) return;
    $panier = getPanier();
    $panier[$idProduit] = ($panier[$idProduit] ?? 0) + $quantite;
    savePanier($panier);
}

function modifierQuantitePanier(int $idProduit, int $quantite): void
{
    $panier = getPanier();
    if ($quantite <= 0) {
        unset($panier[$idProduit]);
    } else {
        $panier[$idProduit] = $quantite;
    }
    savePanier($panier);
}

function retirerDuPanier(int $idProduit): void
{
    $panier = getPanier();
    unset($panier[$idProduit]);
    savePanier($panier);
}

function viderPanier(): void
{
    setcookie('panier', '', time() - 3600, '/');
    $_COOKIE['panier'] = '{}';
}

function compterArticlesPanier(): int
{
    return array_sum(getPanier());
}