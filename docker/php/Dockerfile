FROM php:8.3-fpm

# Instalar dependencias del sistema y extensiones de PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    libzip-dev \
    unzip \
    libicu-dev \
    curl \
    gnupg \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql zip bcmath intl pcntl posix \
    && pecl install redis \
    && docker-php-ext-enable redis

# Instalar Node.js 22 (LTS) y npm para Filament v5 / Tailwind v4.1+
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Optimización para Laravel (opcional pero recomendado)
RUN chown -R www-data:www-data /var/www/html
