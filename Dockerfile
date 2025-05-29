FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip zip libpq-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-interaction --optimize-autoloader

# ✅ DODATO: Folder za sesije
RUN mkdir -p /app/var/sessions && chmod -R 777 /app/var

CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
