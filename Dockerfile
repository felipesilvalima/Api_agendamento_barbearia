# Dockerfile Dev Laravel com PHP-FPM
FROM php:8.3-fpm

# Diretório de trabalho
WORKDIR /var/www/html

# Dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libonig-dev \
    libxml2-dev \
    curl \
    && docker-php-ext-install pdo pdo_mysql mbstring xml \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copia o código do projeto
COPY . .

# CORREÇÃO DAS PERMISSÕES
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/storage \
    && chmod -R 777 /var/www/html/bootstrap/cache \
    && touch /var/www/html/storage/logs/laravel.log \
    && chmod 666 /var/www/html/storage/logs/laravel.log

# Expondo a porta do PHP-FPM
EXPOSE 9000

# Rodando como root mesmo (ou mude para www-data se preferir)
USER root

# Comando padrão
CMD ["php-fpm"]