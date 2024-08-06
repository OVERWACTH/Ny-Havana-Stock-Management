

# Ny Havana Stock Management

## Description

Ny Havana Stock Management est une application de gestion de stock développée avec le framework Symfony. Cette application permet de gérer les stocks de matériel, de suivre les demandes de matériel (CR), et de gérer les réparations.

## Prérequis

Avant de commencer, assurez-vous d'avoir installé les outils suivants :

- [PHP 8.0 ou supérieur](https://www.php.net/)
- [Composer](https://getcomposer.org/)
- [Symfony CLI](https://symfony.com/download)
- [Node.js et npm](https://nodejs.org/)

### Modifier le fichier php.ini
Trover cette ligne et enlever les points virgules : 
extension=pdo_pgsql
extension=pdo_sqlite
extension=pgsql

### Cloner le dépôt

```sh
git clone https://github.com/OVERWACTH/Ny-Havana-Stock-Management.git
cd Ny-Havana-Stock-Management
```


### Installer les dépendances PHP

```sh
composer install
```
## Commande

```sh
composer require symfony/orm-pack

composer require symfony/maker-bundle --dev

composer require symfony/security-bundle

php bin/console doctrine:database:create

php bin/console doctrine:migrations:migrate

composer require twig

composer require symfony/ux-stimulus

composer require symfony/ux-turbo

composer require twig/extra-bundle

composer require symfony/monolog-bundle

composer require symfonycasts/verify-email-bundle
```

###  Lancer le serveur de développement

```sh
symfony server:start -d
```
