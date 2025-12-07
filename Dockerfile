# 1. Base image: PHP 8.2 + Apache
FROM php:8.2-apache

# 2. Install required system packages and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip

# 3. Enable Apache mod_rewrite (needed by Laravel)
RUN a2enmod rewrite

# 4. Workdir inside container
WORKDIR /var/www/html

# 5. Copy project files into container
COPY . .

# 6. Install Composer (copied from official composer image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 7. Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# 8. Set permissions for storage & cache
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# 9. Set Apache DocumentRoot to public/
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 10. Expose HTTP port
EXPOSE 80

# 11. Start Apache
CMD ["apache2-foreground"]