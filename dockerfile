FROM php:8.2-apache

# Installation des dépendances et extensions PHP nécessaires
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

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de configuration
COPY composer.json composer.lock ./

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Copier le reste du code source
COPY . .

# Définir les permissions (optionnel, peut ralentir le build)
RUN chown -R www-data:www-data /var/www/html

# Définir le port d'écoute
ENV PORT=8080
EXPOSE 8080

# Démarrer le serveur Apache
CMD ["apache2-foreground"]
