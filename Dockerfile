# Use official PHP with Apache
FROM php:8.2-apache

# Copy all project files to Apache root
COPY . /var/www/html/

# Enable Apache mod_rewrite (optional but useful)
RUN a2enmod rewrite

# Set permissions (important for PHP)
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80