FROM wordpress:php8.2-apache

WORKDIR /var/www/html

RUN a2enmod rewrite headers expires

COPY --chown=www-data:www-data . /var/www/html/

EXPOSE 80