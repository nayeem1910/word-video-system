FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    ffmpeg \
    curl \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    zip unzip

RUN a2enmod rewrite
RUN mkdir -p /var/www/html/output && chmod -R 777 /var/www/html/output
WORKDIR /var/www/html
COPY . /var/www/html
RUN chmod -R 755 /var/www/html