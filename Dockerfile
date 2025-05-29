FROM php:8.2-cli

# Instaliraj potrebne ekstenzije i alate
RUN apt-get update && apt-get install -y \
    git unzip zip libpq-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Instaliraj Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Postavi radni direktorijum
WORKDIR /app

# Kopiraj fajlove
COPY . .

# Instaliraj PHP dependencije
RUN composer install --no-interaction --no-scripts --optimize-autoloader

# Pokretanje ugrađenog PHP servera
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
