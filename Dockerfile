# Use the official PHP image with Apache
FROM php:8.0-apache

# Install MySQL extensions for PHP
RUN apt-get update && apt-get install -y \
    mariadb-client \
    && docker-php-ext-install pdo pdo_mysql mysqli

# Copy custom php.ini to the appropriate location
COPY ./config/php.ini /usr/local/etc/php/

# Copy application files to the container
COPY . /var/www/html

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 for the Apache server
EXPOSE 80

# Start the Apache server
CMD ["apache2-foreground"]
