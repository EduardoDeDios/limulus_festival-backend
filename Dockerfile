FROM php:8.4-apache

# Instalar dependencias del sistema y librerías necesarias
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    curl \
    ca-certificates \
    libicu-dev \
    libzip-dev \
    zlib1g-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# Configurar e instalar extensiones PHP necesarias
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd intl pdo pdo_mysql zip opcache

# Habilitar mod_rewrite para Symfony
RUN a2enmod rewrite

# Usar Composer oficial (copiado desde la imagen oficial de Composer)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar solo los archivos de dependencias y aprovechar la cache de Docker
COPY composer.json composer.lock ./

# Instalar dependencias PHP sin scripts (se ejecutarán tras copiar todo)
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --no-scripts --optimize-autoloader

# Copiar el resto del código
COPY . .

# Ejecutar scripts que requiera el proyecto y optimizar autoload
RUN composer run-script post-install-cmd --no-interaction || true \
    && composer dump-autoload --optimize --no-dev --classmap-authoritative

# Ajustar DocumentRoot de Apache a /public (Symfony)
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!DocumentRoot /var/www/html!DocumentRoot /var/www/html/public!g' /etc/apache2/apache2.conf

# Permisos mínimos necesarios para ejecución en contenedor
RUN chown -R www-data:www-data var public || true \
    && chmod -R 0755 var public || true

# Variables de entorno recomendadas (Render las puede sobrescribir)
ENV APP_ENV=prod
ENV APP_DEBUG=0

EXPOSE 80

CMD ["apache2-foreground"]
