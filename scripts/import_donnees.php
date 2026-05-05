<?php
// =====================================================================
// Script d'import des données initiales depuis les fichiers CSV
// =====================================================================

require __DIR__ . '/../config/db.php';

echo "<pre>";

try {
    // ----- IMPORT DES PRODUITS -----
    echo "Import des produits...\n";

    $pdo->exec("TRUNCATE TABLE produits");

    $cheminArticles = __DIR__ . '/../data/articles.csv';
    $file = fopen($cheminArticles, 'r');
    if ($file === false) {
        throw new Exception("Impossible d'ouvrir $cheminArticles");
    }

    fgetcsv($file); // sauter la ligne d'en-tête

    $stmt = $pdo->prepare("
        INSERT INTO produits (nom, reference, prix_ht, tva_pourcentage, remise_pourcentage, est_nouveaute)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $nbProduits = 0;
    while (($ligne = fgetcsv($file)) !== false) {
        $nom       = $ligne[0];
        $reference = $ligne[1];
        $prix_ht   = (float) $ligne[2];
        $tva       = (float) $ligne[3];
        $remise    = ($ligne[4] === '') ? 0 : (float) $ligne[4];
        $nouveaute = (strtolower(trim($ligne[5])) === 'oui') ? 1 : 0;

        $stmt->execute([$nom, $reference, $prix_ht, $tva, $remise, $nouveaute]);
        $nbProduits++;
    }
    fclose($file);
    echo "✓ $nbProduits produits importés.\n\n";

    // ----- IMPORT DES CLIENTS -----
    echo "Import des clients...\n";

    $pdo->exec("TRUNCATE TABLE clients");

    $cheminClients = __DIR__ . '/../data/clients.csv';
    $file = fopen($cheminClients, 'r');
    if ($file === false) {
        throw new Exception("Impossible d'ouvrir $cheminClients");
    }

    fgetcsv($file); // sauter la ligne d'en-tête

    $stmt = $pdo->prepare("
        INSERT INTO clients (nom, numero_client, adresse, email, date_inscription)
        VALUES (?, ?, ?, ?, ?)
    ");

    $nbClients = 0;
    while (($ligne = fgetcsv($file)) !== false) {
        $nom     = $ligne[0];
        $numero  = $ligne[1];
        $adresse = $ligne[2];
        $email   = $ligne[3];
        $date    = $ligne[4];

        $stmt->execute([$nom, $numero, $adresse, $email, $date]);
        $nbClients++;
    }
    fclose($file);
    echo "✓ $nbClients clients importés.\n\n";

    echo "Import terminé avec succès.";

} catch (Exception $e) {
    echo "ERREUR : " . $e->getMessage();
}

echo "</pre>";