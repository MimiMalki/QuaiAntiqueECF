
# Projet ECF: Quai Antique restaurant 
## Table des matières
1. [La démarche à suivre pour l’exécution en local](#Démarche)
2. [L’explication de la création d’un administrateur 
pour le back-office](#Explication)

### Informations générales
L'application Quai Antique restaurant est une application développer en symfony6.

Vous pouver trouver tous livrables demandés dans le dossier ```livrables``` fournie avec le dépôt git de l'application :
- ***Les fichiers de création et d’alimentation de la base de données.***
- ***Une documentation technique.***
- ***Une charte graphique.***
- ***les diagrammes de la conception.***

## Démarche
***
Voici les étapes à suivre pour exécuter le projet Quai Antique restaurant Symfony localement :
1. Téléchargez et installez un serveur web [ Apache ](https://www.apachefriends.org/fr/index.html) et un serveur de base de données [ MySQL ](https://www.phpmyadmin.net/) sur votre ordinateur. 
2. Installez [ Composer ](https://getcomposer.org/doc/00-intro.md), le gestionnaire de dépendances de Symfony, en suivant les instructions sur le site officiel de Composer.
3. Clonez le projet Symfony depuis le référentiel Git en utilisant la commande ``` git clone https://github.com/nom_du_projet.git```.
4. Naviguez dans le répertoire du projet et installez les dépendances en exécutant ```la commande composer install```.
5.  créer la base de données en utilisant la commande ```php bin/console doctrine:database:create``` .
6.  Configurez les paramètres de connexion dans le fichier .env en renseignant les informations de connexion à votre serveur de base de données.
7. Créez les tables de la base de données en exécutant la commande ```php bin/console doctrine:schema:create```.
8. Exécutez le serveur Symfony en exécutant la commande ```symfony server:start```. Si tout se passe bien, vous devriez voir une URL d'accès au serveur dans le terminal.
9. Accédez à la page d'accueil dédier aux client et visiteurs de l'application en ouvrant votre navigateur Web et en accédant à l'URL http://localhost:8000/home
10. Accédez à la page d'accueil dédier utilisateur administrateur de l'application en ouvrant votre navigateur Web et en accédant à l'URL http://localhost:8000/admin
 
Vous pouvez maintenant explorer l'application Symfony localement et commencer à tester le projet.

 
## Explication
***
Voici les étapes pour créer un administrateur :
J'ai crée mon administrateur pour le back-office avec les datafixtures appliquer sur mon entity User.
on suivant les étapes suivantes:

1. Installer composer dataFxtures ```composer require --dev orm-fixtures```.
2. Créer une classe de fixtures(UserFixtures) ```symfony console make:fixtures UserFixtures```. pour charger les données de l'utilisateur administrateur telles que son nom et prenom d'admin et son mot de passe avec système de cryptage de Symfony pour stocker le mots de passe de l'administrateur en toute sécurité dans la base de données.
3. Exécuter la commande de chargement de fixtures:  ```php bin/console doctrine:fixtures:load ```. Cela créera un nouvel utilisateur administrateur avec les informations fournies et l'ajoutera à la base de données.
4. Sans oublier pas de définir les autorisations d'accès de l'administrateur dans le fichier **security.yaml**  Dans la section access_control de notre fichier security.yaml, nous définissons les règles d'accès pour le back-office. Nous autorisons uniquement les utilisateurs ayant le rôle ```"ROLE_ADMIN"```à accéder aux pages du back-office.
  ```  
 access_control:
        # Define access control rules for the back-office
        - { path: ^/admin, roles: ROLE_ADMIN }
 ```
 
Une fois ces étapes terminées, notre administrateur pour le back-office de notre application restaurat en Symfony 6.

