FROM php:8.2-apache

# Nous avons besoin de libssl-dev et pkg-config pour que l’extension mongodb compile avec SSL
# ainsi que libzip-dev, zip, unzip et git pour d’autres dépendances.
RUN apt-get update && \
    apt-get install -y \
      libssl-dev \
      pkg-config \
      libzip-dev \
      zip \
      unzip \
      git && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb && \
    docker-php-ext-install mysqli pdo pdo_mysql && \
    a2enmod rewrite && \
    rm -rf /var/lib/apt/lists/*

# Récupération de Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


WORKDIR /var/www/html

# Installation des dépendances PHP
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copie du code et réglages des permissions
COPY apache.conf /etc/apache2/sites-available/000-default.conf
COPY . .
RUN chown -R www-data:www-data /var/www/html


 #EXPOSE 80
ENV PORT=8080
EXPOSE 8080

