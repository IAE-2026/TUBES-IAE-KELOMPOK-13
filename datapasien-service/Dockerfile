FROM php:8.2-cli
WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-scripts --ignore-platform-reqs

COPY . .

RUN composer dump-autoload --optimize --ignore-platform-reqs
RUN mkdir -p bootstrap/cache \
    && chmod -R 777 bootstrap/cache storage

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]