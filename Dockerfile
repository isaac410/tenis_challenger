FROM php:8.2.21-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    wget \
    && docker-php-ext-install pdo pdo_mysql zip \
    && wget https://get.symfony.com/cli/installer -O - | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-scripts --no-interaction

EXPOSE 8000

CMD ["symfony", "server:start", "--no-tls", "--port=8000", "--allow-http"]