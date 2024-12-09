FROM alpine:3.17

# Installer les dépendances nécessaires pour PostgreSQL

RUN apk add --no-cache postgresql postgresql-contrib

# Définir le répertoire de travail

WORKDIR /db

# Copier les fichiers de configuration dans le conteneur

COPY init.sql /docker-entrypoint-initdb.d/

# Exposer le port 5432 pour accéder à la base de données

EXPOSE 5432

# Définir la commande à exécuter lorsque le conteneur démarre

CMD ["postgres"]