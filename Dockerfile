FROM php:8.2-cli

# Instalacija potrebnih ekstenzija
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_pgsql zip

# Instalacija Composer-a
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Radni direktorijum
WORKDIR /app
COPY . /app

# Instalacija PHP dependencija
RUN composer install --no-interaction

# Defaultni port i komanda
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
