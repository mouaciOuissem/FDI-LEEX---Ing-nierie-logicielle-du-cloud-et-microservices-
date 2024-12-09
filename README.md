# Groupe de trabel_w 999029

### Pour app :

cd app/backend

sudo docker-compose up --build


# Pour App /django todo 

cd app/backend

python3 manage.py run server 0.0.0.0:8080

python3 manage.py migrate 

python3 manage.py makemigrations todo

python3 manage.py migrate todo


cd app/front


npm start

# Pour la base de donnée

creer base de donnée postgres, 

crédential all "postgres"

# Pour myApi sans docker
(voir le readme avec docker dans le repo MYAPI)

## Prérequis 
- installation de symfony
- installation de php
- installation de mysql

Il faudra sûrement modifier le .env dans le dossier myApi dans MYAPI
