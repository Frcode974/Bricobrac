-- =====================================================================
-- Script de création de la base Bricobrac
-- Auteur : Jérémy MICHAUX
-- Version : 1.0 
-- =====================================================================

DROP DATABASE IF EXISTS bricobrac;
CREATE DATABASE bricobrac CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bricobrac;

CREATE TABLE utilisateurs (
    id_utilisateur  INT UNSIGNED NOT NULL AUTO_INCREMENT,
    email           VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe    VARCHAR(255) NOT NULL,
    role            VARCHAR(50)  NOT NULL,
    date_creation   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_utilisateur)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE clients (
    id_client        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom              VARCHAR(255) NOT NULL,
    adresse          VARCHAR(255) NOT NULL,
    email            VARCHAR(255) NOT NULL UNIQUE,
    date_inscription DATE         NOT NULL DEFAULT (CURRENT_DATE),
    PRIMARY KEY (id_client)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE produits (
    id_produit         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom                VARCHAR(255) NOT NULL,
    reference          VARCHAR(50) NOT NULL UNIQUE,
    prix_ht            DECIMAL(10, 2) NOT NULL,
    tva_pourcentage    DECIMAL(5, 2) NOT NULL,
    remise_pourcentage DECIMAL(5, 2) NOT NULL DEFAULT 0,
    est_nouveaute      TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (id_produit)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;