FROM php:8.1-cli

RUN apt-get update && apt-get install -y \
    ffmpeg \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip

COPY . /var/www/html
WORKDIR /var/www/html

CMD ["php", "-S", "0.0.0.0:10000"]