<?php
// =====================================================================
// FP3 — Déconnexion
// =====================================================================

session_start();
$_SESSION = [];        // Vide les données de session
session_destroy();     // Détruit la session côté serveur
header('Location: index.php');
exit;