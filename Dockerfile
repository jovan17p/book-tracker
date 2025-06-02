# Koristi zvanični PHP image kao osnovu
FROM php:8.2-cli

# Instaliraj potrebne sisteme za rad sa PHP-om
RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_pgsql zip

# Instaliraj Composer (PHP dependency manager)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Postavi radni direktorijum u kontejneru
WORKDIR /app

# Kopiraj sve fajlove iz lokalnog direktorijuma u radni direktorijum u Dockeru
COPY . .

# Instaliraj PHP dependencije (Composer)
RUN composer install --no-dev --optimize-autoloader

# Pokreni PHP server na portu 10000
CMD php -S 0.0.0.0:10000 -t public
