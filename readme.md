# Bricobrac
## Infromations générales 
Créé par Jérémy Michaux 
# Brico'brac — Projet BTS SIO SLAM

**Auteur** : Jérémy MICHAUX
**N° candidat** : 1210030479W
**Session** : 2026
**Centre** : IRIS Mediaschool

## Description

Maquette web e-commerce développée pour la chaîne de magasins de bricolage
Brico'brac, dans le cadre d'une mission pour l'agence Sciendi Web.

Stack : PHP 8 + MySQL + Bootstrap 5 + Git/GitHub.

## Fonctionnalités implémentées

- **FP1** — Page d'accueil avec nom et slogan
- **FP2** — Liste publique des produits + page détail
- **FP3** — Authentification admin + CRUD produits + CRUD utilisateurs
- **FC1** — Responsive (Bootstrap 5) + messages de confirmation
- **FP4** — Panier en cookies + validation simulée

## Installation locale

### Prérequis

- XAMPP (Apache + MySQL + PHP 8)
- Navigateur web

### Étapes

1. Cloner le projet dans `C:\xampp\htdocs\` : cd C:\xampp\htdocs
git clone https://github.com/Frcode974/Bricobrac.git bricobrac
2. Démarrer Apache et MySQL via le XAMPP Control Panel.

3. **Créer la base de données** :
   - Ouvrir http://localhost/phpmyadmin
   - Onglet « Importer » → sélectionner `bricobrac.sql` → Exécuter

4. **Importer les données initiales** :
   - Aller sur http://localhost/bricobrac/scripts/import_donnees.php
   - Vérifier le message de succès (36 produits + 24 clients importés)

5. **Créer le premier administrateur** :
   - Aller sur http://localhost/bricobrac/scripts/creer_admin.php
   - Identifiants par défaut : admin@bricobrac.fr / admin123

6. **Tester le site** : http://localhost/bricobrac/

### Plan B (restauration rapide)

Si l'installation pas à pas pose problème, utiliser le dump complet :
- phpMyAdmin → Importer → `dump_complet.sql` → Exécuter
- Le dump contient la structure + toutes les données + l'admin.

## Structure du projet

- `config/db.php` — connexion PDO réutilisable
- `includes/auth.php` — vérification de session
- `includes/header.php` / `footer.php` — gabarit Bootstrap
- `includes/panier.php` — fonctions panier
- `scripts/` — scripts d'installation (import + création admin)
- `data/` — fichiers CSV sources
- Pages publiques : index, produits, detail_produit, panier
- Pages admin : connexion, deconnexion, admin_*

## Identifiants admin par défaut

- Email : `admin@bricobrac.fr`
- Mot de passe : `admin123`