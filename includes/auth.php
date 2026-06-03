<?php
// =====================================================================
// Vérification de session — à inclure en haut de toute page admin
// =====================================================================

session_start();

if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: connexion.php');
    exit;
}