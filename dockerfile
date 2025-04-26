FROM php:8.2-apache

# Installe les extensions nécessaires (MySQL + MongoDB)
RUN apt-get update && apt-get install -y \
        libzip-dev zip unzip \
        && docker-php-ext-install mysqli pdo pdo_mysql \
        && pecl install mongodb \
        && docker-php-ext-enable mongodb \
        && a2enmod rewrite

# Copie la config Apache personnalisée
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Copie les fichiers du projet
COPY . /var/www/html/

# Droits pour Apache
RUN chown -R www-data:www-data /var/www/html/
