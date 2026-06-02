# Utilise une image officielle PHP avec Apache
FROM php:8.2-apache

# Active le module de réécriture d'Apache (très utile pour les routes)
RUN a2enmod rewrite

# Installe les extensions PHP nécessaires pour MySQL (PDO MySQL)
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copie tout le contenu de ton projet dans le dossier web d'Apache
COPY . /var/www/html/

# Donne les bonnes permissions aux fichiers
RUN chown -W www-data:www-data /var/www/html

# Expose le port 80 pour le serveur web
EXPOSE 80