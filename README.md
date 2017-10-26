# API Symfony 3

# Installation
## 1. Récupérer le code
Vous avez deux solutions pour le faire :

1. Via Git, en clonant ce dépôt.
2. Via le téléchargement du code source en une archive ZIP.

## 2. Télécharger les vendors
Avec Composer bien évidemment :

    php composer.phar install

## 3. Créez la base de données
Si la base de données que vous avez renseignée n'existe pas déjà, créez-la :

    php bin/console doctrine:database:create

Puis créez les tables correspondantes au schéma Doctrine :

    php bin/console doctrine:schema:update --dump-sql
    php bin/console doctrine:schema:update --force

Enfin, éventuellement, ajoutez les fixtures :

    php bin/console doctrine:fixtures:load

## 5. Publiez les assets
Publiez les assets dans le répertoire web :

    php bin/console assets:install web

## Et profitez !
