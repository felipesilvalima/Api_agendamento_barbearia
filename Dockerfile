FROM php:8.3-fpm

WORKDIR /app

# Dependências do sistema
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring xml \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

COPY ./ /app

RUN composer install --no-dev --optimize-autoloader
# Ajusta permissões das pastas que precisam ser graváveis
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Usuário padrão: vamos usar www-data para produção
USER www-data

# Porta
EXPOSE 8000

CMD ["php-fpm"]