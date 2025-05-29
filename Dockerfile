FROM php:8.2-cli

# Instalacija potrebnih paketa
RUN apt-get update && apt-get install -y \
    git unzip zip libpq-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Dodavanje Composer-a
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Postavljanje radnog direktorijuma
WORKDIR /app
COPY . .

# Instalacija dependencija
RUN composer install --no-interaction --optimize-autoloader

# Start PHP built-in server
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
