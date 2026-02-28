FROM php:8.3-apache

WORKDIR /app

# Dependências do sistema
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip unzip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Copiar código
COPY ./ /app

# Composer (root) para instalar pacotes
RUN composer install --no-dev --optimize-autoloader

# Permissões finais de todo o projeto
RUN chown -R root:www-data /app

# Habilitar mod_rewrite (root)
RUN a2enmod rewrite

# Agora sim muda para usuário www-data
USER root

# Expor porta
EXPOSE 80