FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    curl \
    libzip-dev \
    libpng-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Enable Apache rewrite
RUN a2enmod rewrite

# Salin project ke container
COPY . /var/www/html

# Set permission
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Ubah DocumentRoot Apache ke folder public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Ubah permission public untuk jaga-jaga
RUN chown -R www-data:www-data /var/www/html/public

# Set working directory
WORKDIR /var/www/html/public

# Expose port 80
EXPOSE 80
