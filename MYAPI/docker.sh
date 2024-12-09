#lancer le script=> ./docker.sh    <= s'assurer detre a la racine du dossier docker-symfony

#stopper tous les conteneurs en cours
sudo docker stop $(docker ps -a -q)

#lancer le conteneur symfony + apache
sudo docker-compose build

#lancer tous les autres conteneurs (mysql,phpmyadmin)
sudo docker-compose up -d

#lancer le conteneur symfony v5.4 (a lancer 1 seule fois sinon erreur, pas dérangeante)
#sudo docker exec www_docker_symfony composer create-project symfony/skeleton myApi

#donner des droits pour modifiers les fichiers symfony
#sudo chown -R $USER ./

#----------initialiser la bdd:------------------------------------------------------

# SOIT A LA MAIN SUR PHPMYADMIN => IMPORTER LE SCRIPT BDD.SQL DANS PHPMYADMIN  http://localhost:8080/

# SOIT entrer dans le container a la main

#-----1. Entrer dans le container mysql ---------
#docker exec -it db_docker_symfony bash

#-----2. entrer dans mysql -----------------------
#mysql -uroot -p
#password: rien saisir

#-----3. créationdes tables en bdd -----------------------
#copier coller le script bdd.sql dans le terminal et faire entrée.

#----------executer une commande avec composer require namePackage:-----------------

#-----1. Entrer dans le container symfony ---------
#docker exec -it www_docker_symfony bash

#-----2. lancer la comande composer require -------
#cd myApi 
#composer require namePackage



#----------executer une commande avec doctrine:database:create ----------------------------

#-----1. Entrer dans le container symfony ---------
#docker exec -it www_docker_symfony bash

#-----2. lancer la comande composer require -------
#cd myApi 
#php bin/console doctrine:database:create



#accéder a phpmyamdin => http://localhost:8080/
#accéder a symfony => http://localhost:8741/

#connexion base de données
#login root, password vide

#docker exec www_docker_symfony    php myApi/bin/console make:entity --api-resource
#composer require symfony/maker-bundle --dev