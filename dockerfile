FROM php:8.2-apache

# Installation des dépendances système et extensions PHP
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

# Définir le répertoire de travail
WORKDIR /var/www/html

# Récupérer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier uniquement les fichiers nécessaires pour Composer
COPY ./composer.json ./composer.lock ./

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Copier le reste du code source (en respectant le .dockerignore)
COPY . .

# Définir les permissions (éviter si possible)
RUN chown -R www-data:www-data /var/www/html

# Définir le port d'écoute
ENV PORT=8080
EXPOSE 8080

# Démarrer le serveur Apache
CMD ["apache2-foreground"]
