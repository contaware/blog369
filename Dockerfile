FROM php:8.5-apache

# Set the php extensions you need for your project
RUN apt update && \
    apt install -y libfreetype-dev libjpeg62-turbo-dev libwebp-dev libpng-dev libzip-dev zip && \
    pecl install xdebug && \
    a2enmod rewrite && \
    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install mysqli pdo pdo_mysql gd exif zip && \
    docker-php-ext-enable xdebug

# To make sur that the bind-mounted files inside the container 
# are owned by www-data, run the container with the www-data 
# user instead of root.
USER www-data:www-data
