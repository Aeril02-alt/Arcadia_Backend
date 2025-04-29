FROM php:8.2-apache

# Installe les extensions nécessaires (MySQL + MongoDB)
RUN apt-get update && apt-get install -y \
        libzip-dev zip unzip \
        && docker-php-ext-install mysqli pdo pdo_mysql \
        && pecl install mongodb \
        && docker-php-ext-enable mongodb \
        && a2enmod rewrite \
        && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copie la config Apache personnalisée
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Copie les fichiers du projet
COPY . /var/www/html/

# Assurer les bonnes permissions pour les fichiers copiés
RUN chown -R www-data:www-data /var/www/html/  # Apache doit être propriétaire des fichiers

# Donne les bonnes permissions au dossier doc/photo
RUN chmod -R 755 /var/www/html/doc/photo && \
    chown -R www-data:www-data /var/www/html/doc/photo  
    # Apache a les droits d'écriture et lecture