FROM php:7.1.8-apache

COPY . /src

COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN docker-php-ext-install bcmath

RUN chown -R www-data:www-data /src
RUN a2enmod rewrite
RUN service apache2 restart
