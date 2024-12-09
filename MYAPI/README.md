# Groupe de charpe_o 985210

# myApi Quest

## Environnement et développement

### Prérequis

* docker 19.0.3 ``` docker -v```
* docker-compose 1.27.2  ``` docker-compose -v```

### Lancer l'environnement de développement
```bash 
sudo docker-compose build
sudo docker-compose up -d
```

### Initialiser la base de données
* Aller sur phpmyadmin:  ``` http://localhost:8080/```
* Créer une base de données qui s'apelle "mydb".
* Importer le script sql bdd.sql dans phpmyadmin.
* Une erreur rouge s'affichera et c'est normal, les tables se créeront quand même.

### Installer vendor -s'il n'existe pas-
* Entrer dans le container symfony: ``` docker exec -it www_docker_symfony bash```
* Installer vendor 
``` 
cd myApi
composer install
```

### Lien pour accéder aux différents services
* phpmyadmin:  ```http://localhost:8080/```
* symfony: ```http://localhost:8741/```
* api doc: ```http://127.0.0.1:8741/api```

### Entrer dans les containeurs
* mysql:  ``` docker exec -it db_docker_symfony bash```
* symfony: ``` docker exec -it www_docker_symfony bash```

### Erreur JWT bad configuration credantials:
* régénrer une pair de clé pour le configurer le JWT: ``` php bin/console lexik:jwt:generate-keypair```

### Erreur si vous avez lancer les containeurs dans deux dossiers différents:
* nettoyer les conteneurs: ``` docker system prune```

### Erreur si vos containeurs sont déjà allumé:
* stopper les conteneurs: ``` docker stop $(docker ps -a -q)```

### Erreur de synchronisation de docker vers le pc
* désactiver le module opache de php.ini dans le conteneur: 
``` 
environment:
            PHP_OPCACHE_ENABLE: 0
```

### Erreur PATCH method
* Pour utiliser la route PATCH avec un fichier il faut overrider la méthode HTTP POST par PATCH. Il faut uniquement
utiliser postman et non la documentation swagger de l'api /docs.
````
POST http://127.0.0.1:8741/videos/3?_method=patch
```

## Créer le projet symfony -s'il n'existe pas-
* créer le projet dans le container symfony ``` sudo docker exec www_docker_symfony composer create-project symfony/skeleton myApi```
* donner les droits d'éditer le projet ``` sudo chown -R $USER ./```

