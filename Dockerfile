FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    curl git unzip zip libzip-dev libpng-dev libonig-dev libxml2-dev \
    && apt-get clean

RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean

RUN docker-php-ext-install pdo pdo_mysql mbstring zip gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer install --no-dev --optimize-autoloader
RUN npm ci
RUN npm run build
RUN php artisan storage:link

CMD php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
