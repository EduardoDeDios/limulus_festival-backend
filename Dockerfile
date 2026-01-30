FROM php:8.3-apache

# Dependencias del sistema necesarias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    zlib1g-dev \
    libxml2-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl pdo pdo_mysql

# Instalar extensión ZIP sin compilarla manualmente
RUN docker-php-ext-install zip || true

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar proyecto
COPY . .

# Instalar dependencias Symfony
RUN composer install --no-dev --optimize-autoloader

# Permisos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
