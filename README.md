# Application de Gestion de Véhicules

Cette application Symfony permet la gestion de véhicules ainsi que de leurs disponibilités.

## Prérequis

Avant de commencer, assurez-vous d'avoir installé les éléments suivants :

- PHP 7.4 ou version ultérieure
- Composer
- MySQL ou tout autre SGBD compatible avec Symfony

Je me suis permis de garder toutes les dépendances liées à Symfony car le projet m'a énormément plu. Ainsi, je compte aller plus loin dans les fonctionnalités afin de monter en compétence.

## Installation


1. Clonez ce dépôt sur votre machine locale.
2. Naviguez jusqu'au répertoire du projet dans votre terminal.
3. Exécutez `composer install` pour installer les dépendances.
4. Créez votre fichier `.env` en vous basant sur le fichier `.env.example` et configurez votre base de données.
5. Exécutez `php bin/console doctrine:database:create` pour créer la base de données.
6. Exécutez `php bin/console doctrine:migrations:migrate` pour exécuter les migrations et créer le schéma de base de données.
7. Lancez le serveur Symfony avec `php -S localhost:8000 -t public`.
8. Accédez à l'application dans votre navigateur en vous rendant à l'adresse indiquée.

## Fonctionnalités

1. **Création de Véhicule** : Permet d'ajouter de nouveaux véhicules à la base de données avec leurs marques, modèles, etc.
2. **Gestion des Disponibilités** : CRUD pour gérer les disponibilités de chaque véhicule, y compris la définition des dates de début et de fin de disponibilité ainsi que les prix par jour.
3. **Recherche de Véhicule par Disponibilité** : Permet de rechercher des véhicules disponibles pour une certaine période, avec la possibilité de filtrer par prix et d'étendre la recherche d'un jour si aucun résultat n'est trouvé.

## Technologies Utilisées

- Symfony version 7
- Bootstrap 

## Contributeurs

- [Fierens James](https://github.com/Jeymeus)

## Licence

Ce projet est distribué sans licence spécifique. Il est destiné à un usage personnel ou éducatif uniquement.
