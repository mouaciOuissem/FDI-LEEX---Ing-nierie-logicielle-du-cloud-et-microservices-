# Utiliser une image alpine comme point de départ pour réduire la taille de l'image
FROM alpine:3.17

# Installer les dépendances nécessaires pour Python et PostgreSQL
RUN apk add --no-cache python3 python3-dev py3-pip postgresql-dev gcc musl-dev

# Définir le répertoire de travail
WORKDIR /code

# Copier les fichiers Pipfile et Pipfile.lock dans le conteneur
COPY Pipfile Pipfile.lock /code/

# Installer les dépendances du projet avec pipenv
RUN pip3 install pipenv && pipenv install --system --deploy

# Copier le reste des fichiers du projet dans le conteneur
COPY . /code

ADD start.sh /code
RUN chmod +x /code/start.sh
# Exposer le port 8082 pour accéder à l'application
EXPOSE 8082

# Définir la commande à exécuter lorsque le conteneur démarre
# CMD ["python3", "manage.py", "runserver", "0.0.0.0:8082" ]
CMD ["/code/start.sh"]

