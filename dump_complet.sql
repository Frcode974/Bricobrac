-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 04 juin 2026 à 03:44
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bricobrac`
--

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id_client` int(10) UNSIGNED NOT NULL,
  `numero_client` varchar(20) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date_inscription` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id_client`, `numero_client`, `nom`, `adresse`, `email`, `date_inscription`) VALUES
(1, '8749052136', 'Marie DUPONT', '12 Rue Victor Hugo, 75001 Paris', 'marie.dupont@gmail.com', '2023-05-08'),
(2, '2369541780', 'Ahmed Khaled', '28 Rue de la Liberté, 13001 Marseille', 'ahmed.khaled@orange.fr', '2023-09-15'),
(3, '5093142876', 'Sophie MARTIN', '5 Rue du Commerce, 69002 Lyon', 'sophie.martin@outlook.com', '2023-06-23'),
(4, '1436978520', 'Mehdi Ben Salah', '18 Avenue de la République, 31000 Toulouse', 'mehdi.bensalah@orange.fr', '2023-11-19'),
(5, '7602398514', 'Emma ROUX', '8 Rue des Fleurs, 44000 Nantes', 'emma.roux@gmail.com', '2023-03-04'),
(6, '6523789140', 'Lucas Lefebvre', '16 Rue de la Paix, 59000 Lille', 'lucas.lefebvre@wanadoo.fr', '2023-07-29'),
(7, '8926103475', 'Inès Bouchard', '3 Boulevard Saint-Germain, 75006 Paris', 'ines.bouchard@gmail.com', '2023-01-18'),
(8, '4872159360', 'Omar Belkacem', '7 Avenue Jean Jaurès, 13002 Marseille', 'omar.belkacem@wanadoo.fr', '2023-10-12'),
(9, '1369420875', 'Julie Leclerc', '22 Rue des Roses, 69003 Lyon', 'julie.leclerc@orange.fr', '2023-06-27'),
(10, '5682937401', 'Fatima Abdi', '14 Place de la Victoire, 31000 Toulouse', 'fatima.abdi@outlook.com', '2023-04-21'),
(11, '9753021864', 'Thomas Moreau', '10 Rue du Château, 44000 Nantes', 'thomas.moreau@gmail.com', '2023-08-10'),
(12, '3146895072', 'Chloé Lambert', '5 Avenue des Ducs, 59000 Lille', 'chloe.lambert@orange.fr', '2023-12-03'),
(13, '6501248739', 'Youssef El Hadi', '15 Rue de la Liberté, 75010 Paris', 'youssef.elhadi@gmail.com', '2023-09-07'),
(14, '1893270456', 'Manon Dubois', '31 Boulevard Voltaire, 13003 Marseille', 'manon.dubois@wanadoo.fr', '2023-02-14'),
(15, '4259876130', 'Camille Renault', '17 Rue de la République, 69004 Lyon', 'camille.renault@gmail.com', '2023-05-25'),
(16, '9365104872', 'Leila Ben Mansour', '9 Rue des Arts, 31000 Toulouse', 'leila.benmansour@outlook.com', '2023-11-01'),
(17, '2189740536', 'Antoine Girard', '24 Rue des Lys, 44000 Nantes', 'antoine.girard@orange.fr', '2023-06-08'),
(18, '5039176824', 'Sofia Toumi', '11 Avenue Foch, 59000 Lille', 'sofia.toumi@gmail.com', '2023-09-19'),
(19, '3476921805', 'Ethan Martin', '6 Rue du Faubourg, 75011 Paris', 'ethan.martin@wanadoo.fr', '2023-03-30'),
(20, '6902431875', 'Amira El Kassimi', '8 Boulevard des Cèdres, 13004 Marseille', 'amira.elkassimi@gmail.com', '2023-07-24'),
(21, '8219403567', 'Nathan Dupuis', '20 Rue de la Croix, 69005 Lyon', 'nathan.dupuis@outlook.com', '2023-05-11'),
(22, '5704628139', 'Aya El Mahdi', '12 Avenue Victor Hugo, 31000 Toulouse', 'aya.elmahdi@gmail.com', '2023-10-05'),
(23, '3698021547', 'Emma Fournier', '33 Rue des Iris, 44000 Nantes', 'emma.fournier@wanadoo.fr', '2023-02-17'),
(24, '4786319052', 'Rayan Khelifi', '14 Avenue de la Gare, 59000 Lille', 'rayan.khelifi@gmail.com', '2023-12-26');

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id_produit` int(10) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `reference` varchar(50) NOT NULL,
  `prix_ht` decimal(10,2) NOT NULL,
  `tva_pourcentage` decimal(5,2) NOT NULL,
  `remise_pourcentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `est_nouveaute` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id_produit`, `nom`, `reference`, `prix_ht`, `tva_pourcentage`, `remise_pourcentage`, `est_nouveaute`) VALUES
(1, 'marteau de menuisier bois verni', '81968453', 8.90, 20.00, 0.00, 0),
(2, 'marteau massette fibre de verre', '80166978', 21.90, 20.00, 0.00, 0),
(3, 'maillet de menuisier bois', '82039106', 14.90, 20.00, 0.00, 0),
(4, 'marteau arrache-clou', '81968500', 12.90, 20.00, 0.00, 0),
(5, 'tournevis électricien plat', '74936295', 1.95, 20.00, 0.00, 1),
(6, 'tournevis électricien isolé plat', '67337361', 5.10, 20.00, 0.00, 0),
(7, 'tournevis testeur de tension plat', '76292503', 2.90, 20.00, 0.00, 0),
(8, 'tournevis sans fil', '81900760', 40.00, 20.00, 0.00, 0),
(9, 'jeu de tournevis', '73923500', 34.90, 20.00, 0.00, 1),
(10, 'tournevis cruciforme', '74936246', 3.20, 20.00, 0.00, 0),
(11, 'jeu de tournevis torx', '74936372', 17.90, 20.00, 15.00, 0),
(12, 'tournevis boule cruciforme', '73708264', 3.95, 20.00, 0.00, 0),
(13, 'scie de carreleur', '18850476', 9.95, 20.00, 0.00, 0),
(14, 'lot de 2 lames pour scie à métaux', '70709401', 2.50, 20.00, 10.00, 0),
(15, 'scie à métaux', '70907452', 8.90, 20.00, 0.00, 0),
(16, 'scie égoïne de charpentier', '70907354', 10.90, 20.00, 0.00, 0),
(17, 'boîte à onglet manuelle', '70709653', 9.90, 20.00, 0.00, 1),
(18, 'scie japonaise', '67998931', 18.90, 20.00, 0.00, 0),
(19, 'scie à bûche', '63732655', 15.60, 20.00, 0.00, 0),
(20, 'scie universelle', '70720265', 2.05, 20.00, 0.00, 0),
(21, 'fourreau pour scie', '70709345', 3.90, 20.00, 0.00, 0),
(22, 'scie à chantourner de plaquiste', '73550442', 5.99, 20.00, 0.00, 0),
(23, 'pince coupante', '69241060', 39.00, 20.00, 0.00, 0),
(24, 'pince à sertir les rails', '80150490', 24.90, 20.00, 0.00, 0),
(25, 'pince à agraphage des profiles', '80124107', 55.00, 20.00, 0.00, 0),
(26, 'pince à dénuder', '80125159', 8.90, 20.00, 0.00, 0),
(27, 'pince-clé multiprise', '69587994', 49.90, 20.00, 0.00, 1),
(28, 'pince coupe-mosaïque', '18699366', 19.90, 20.00, 0.00, 0),
(29, 'pince perroquet', '18699345', 14.90, 20.00, 0.00, 0),
(30, 'pince à cosse isolée', '70059913', 25.90, 20.00, 0.00, 0),
(31, 'pince coupe-carrelage', '18699310', 9.90, 20.00, 0.00, 0),
(32, 'pince à cintrer', '74669791', 20.40, 20.00, 0.00, 0),
(33, 'pince coupe-boulons coupante', '80125144', 20.90, 20.00, 0.00, 0),
(34, 'cisaille à tôle à ardoise coupe devant', '80125135', 10.90, 20.00, 0.00, 0),
(35, 'pince pour collier de fixation', '66502576', 17.35, 20.00, 0.00, 0),
(36, 'pince à bec', '80125154', 10.90, 20.00, 0.00, 1);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_utilisateur` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `email`, `mot_de_passe`, `role`, `date_creation`) VALUES
(1, 'admin@bricobrac.fr', '$2y$10$EWYUDuR3UNGCGznZCc54Jev2zwZzoL08LEEgLJVWzAJwp1ppU.SmS', 'admin', '2026-06-03 18:43:28');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id_client`),
  ADD UNIQUE KEY `numero_client` (`numero_client`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id_produit`),
  ADD UNIQUE KEY `reference` (`reference`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id_client` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id_produit` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_utilisateur` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
