FROM php:8.2-apache

# Install PostgreSQL PDO driver
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Fix MPM conflict: disable event, enable prefork
RUN a2dismod mpm_event || true \
    && a2enmod mpm_prefork rewrite

# Copy all project files into Apache's web root
COPY . /var/www/html/

# Allow .htaccess overrides
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80
