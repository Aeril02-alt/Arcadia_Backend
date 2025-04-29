FROM php:8.2-apache

# Installe les extensions nécessaires (MySQL + MongoDB)
RUN apt-get update && apt-get install -y \
        libzip-dev zip unzip \
        && docker-php-ext-install mysqli pdo pdo_mysql \
        && pecl install mongodb \
        && docker-php-ext-enable mongodb \
        && a2enmod rewrite \
        && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copie la config Apache personnalisée (si tu en as besoin)
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Copie tous les fichiers du projet
COPY . /var/www/html/

# Droits corrects pour Apache
RUN chown -R www-data:www-data /var/www/html/

# Assure que doc/photo est accessible par Apache (lecture/écriture)
RUN mkdir -p /var/www/html/doc/photo && \
    chmod -R 755 /var/www/html/doc/photo && \
    chown -R www-data:www-data /var/www/html/doc/photo

EXPOSE 80
