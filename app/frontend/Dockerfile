# Utilisation de l'image de base Alpine
FROM alpine:3.17

# Installation des dépendances nécessaires pour Node.js
RUN apk update && \
    apk add --no-cache nodejs npm

# Création d'un répertoire de travail pour notre application
WORKDIR /front

# Copie des fichiers de l'application dans le répertoire de travail
COPY package.json .
COPY package-lock.json .
RUN npm install
COPY . /front

EXPOSE 3000

CMD ["npm", "start"]
