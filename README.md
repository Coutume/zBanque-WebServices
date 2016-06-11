# zBanque
Gestion de banque communautaire Minecraft - partie webservices
## Installation

Avant de commencer, assurez-vous d'avoir un serveur HTTP permettant la réécriture d'URL.

Pour savoir comment configurer la réécriture : http://www.slimframework.com/docs/start/web-servers.html

Si vous avez Apache, il vous suffit de vérifier que le support des fichiers .htaccess ainsi que le module mod_rewrite sont activés.
Vous devez par ailleurs avoir une version de PHP >= 5.5.X

1. Cloner le dépôt zBanque-WebServices (ZB)
2. Installer Composer. Voir ici : https://getcomposer.org/doc/00-intro.md . L'installation locale (à la racine du répértoire de ZB) est une bonne option.
3. Exécuter la commande suivante depuis la racine du répertoire où est installé ZB afin d'installer les dépendances :
   * `php composer.phar install`
4. Éditer le fichier connexion.inc.php et renseignez les paramètres de votre base de données.
5. Lancer la génération des tables avec Doctrine : `vendor/bin/doctrine orm:schema-tool:create`
6. Profitez des webservices !
