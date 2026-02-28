FROM php:8.2-apache

WORKDIR /app

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"
    
COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader

COPY ./ /app

RUN apt-get update && apt-get install -y \
    apt-get clean && rm -rf /var/lib/apt/lists/* \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

EXPOSE 8000

RUN chown -R www-data:www-data /app

RUN a2enmod rewrite