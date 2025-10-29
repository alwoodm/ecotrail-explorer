FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html

CMD ["bash", "-c", "if [ ! -f /var/www/html/database.db ]; then php -f /var/www/html/app/init.php; fi; exec apache2-foreground"]
