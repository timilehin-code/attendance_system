FROM php:8.2-apache

# Install system dependencies + common PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql zip \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

# Install Composer inside the container
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/html

# Copy composer files first
COPY composer.json composer.lock ./

# Install your project dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy the rest of your code
COPY . .

# Allow .htaccess to work
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Make Apache listen on the port Render gives us
RUN echo '#!/bin/bash\n\
sed -i "s/Listen 80/Listen ${PORT:-80}/" /etc/apache2/ports.conf\n\
sed -i "s/:80/:${PORT:-80}/" /etc/apache2/sites-available/000-default.conf\n\
apache2-foreground' > /usr/local/bin/start-apache.sh \
    && chmod +x /usr/local/bin/start-apache.sh

EXPOSE 80
CMD ["/usr/local/bin/start-apache.sh"]