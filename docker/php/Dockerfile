FROM php:8.3-fpm-alpine

# Install packages including git FIRST
RUN apk add --no-cache \
    bash \
    git \
    unzip \
    icu-dev \
    libpq-dev \
 && docker-php-ext-install pdo pdo_pgsql intl opcache

# After git is installed, we can set safe directory
RUN git config --global --add safe.directory /var/www/html

# Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files and do first install
COPY composer.json composer.lock* ./
RUN composer install --no-interaction || true

# Copy everything else
COPY . /var/www/html

# Final install (will install if anything missing)
RUN composer install --no-interaction

CMD ["php-fpm"]
