FROM php:8.2-cli

# Instalacija sistemskih paketa
RUN apt-get update && apt-get install -y \
    git unzip zip libpq-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Instalacija Composer-a
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Postavi radni direktorijum
WORKDIR /app

# Kopiraj sve fajlove iz projekta
COPY . .

# Instaliraj dependencije
RUN composer install --no-interaction --optimize-autoloader --no-dev
