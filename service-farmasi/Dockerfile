FROM php:8.2-cli

# Install dependency
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip

# Install extension
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Workdir
WORKDIR /app

# Copy composer
COPY composer.json composer.lock ./

RUN composer install --ignore-platform-reqs --no-scripts

# Copy project
COPY . .

# Buat folder cache dan beri permission
RUN mkdir -p bootstrap/cache \
    && chmod -R 777 bootstrap/cache storage

# Generate autoload
RUN composer dump-autoload --optimize --ignore-platform-reqs

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000