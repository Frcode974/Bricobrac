<?php
// =====================================================================
// Script de création du premier administrateur
// À lancer UNE SEULE FOIS, puis supprimer ou désactiver
// =====================================================================

require __DIR__ . '/../config/db.php';

// ----- Identifiants à personnaliser AVANT exécution -----
$email      = 'admin@bricobrac.fr';
$motDePasse = 'admin123';   // ← change ce mot de passe !
// --------------------------------------------------------

try {
    // Vérifier qu'aucun utilisateur n'utilise déjà cet email
    $stmt = $pdo->prepare("SELECT id_utilisateur FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        die("Un utilisateur avec l'email $email existe déjà.");
    }

    // Hashage du mot de passe (NE JAMAIS stocker en clair)
    $motDePasseHash = password_hash($motDePasse, PASSWORD_DEFAULT);

    // Insertion de l'administrateur
    $stmt = $pdo->prepare("
        INSERT INTO utilisateurs (email, mot_de_passe, role)
        VALUES (?, ?, 'admin')
    ");
    $stmt->execute([$email, $motDePasseHash]);

    echo "<pre>";
    echo "Administrateur créé avec succès.\n";
    echo "Email      : $email\n";
    echo "Mot de passe : $motDePasse\n\n";
    echo "Pense à supprimer ou bloquer ce script après usage !";
    echo "</pre>";

} catch (PDOException $e) {
    die("Erreur lors de la création de l'admin : " . $e->getMessage());
}