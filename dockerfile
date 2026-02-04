#base de tudo, linguagem e versão
FROM php:8.3-apache 

#apotando para diretorio que rodar a aplicação
WORKDIR /var/www/html

#A raiz do projeto
COPY . .

# prepara depencias e libs externas, extenções

RUN docker-php-ext-install pdo pdo_mysql mysqli
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

#A porta que vai rodar o container
EXPOSE 8080


#comandos que rodam na aplicação -> CMD ["php", "artisan", "queue:work, serve, schedule:work"]



