# Dockerfile
FROM php:8.2-apache

# 1) Installer les dépendances système + driver MongoDB
RUN apt-get update \
 && apt-get install -y libzip-dev zip unzip git \
 && pecl install mongodb \
 && docker-php-ext-enable mongodb \
 && docker-php-ext-install mysqli pdo pdo_mysql \
 && a2enmod rewrite \
 && rm -rf /var/lib/apt/lists/*

# 2) Récupérer Composer depuis l’image officielle
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 3) Installer les dépendances PHP via Composer
#    (copie d'abord pour bénéficier du cache si seuls composer.json/lock changent)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# 4) Copier le reste de votre application
COPY . .

# 5) Permissions Apache
RUN chown -R www-data:www-data /var/www/html \
 && find /var/www/html -type d -exec chmod 755 {} \; \
 && find /var/www/html -type f -exec chmod 644 {} \;

EXPOSE 80
