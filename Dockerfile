FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip zip libpq-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-interaction --optimize-autoloader

# Folderi za sesiju i keš da CSRF može da radi
RUN mkdir -p var/cache var/log var/sessions && chmod -R 777 var

CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
