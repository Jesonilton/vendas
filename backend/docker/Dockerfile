FROM php:8.3-cli

# Instala extensões
RUN apt-get update && apt-get install -y \
    unzip zip curl git libpq-dev supervisor \
    && docker-php-ext-install pdo pdo_mysql

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/html

COPY . .
RUN composer install

CMD ["/usr/bin/supervisord"]
