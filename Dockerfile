FROM php:8.2-apache

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev libonig-dev libxml2-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configurar Apache
RUN a2enmod rewrite
COPY ./public /var/www/html/public
COPY . /var/www/html

WORKDIR /var/www/html

# Instalar dependencias de Symfony
RUN composer install --no-dev --optimize-autoloader

# Permisos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
