FROM php:7.4-apache

# Instala la extensión mysqli
RUN docker-php-ext-install mysqli

# Copia los archivos de la aplicación a la carpeta htdocs
COPY app/ /var/www/html/