# Based on the offical image with latest PHP 8.4 version
FROM php:8.4-fpm

## First add lines who don't change, or rarely change

# startup script
CMD ["/entry.sh"]

# Fixed environment settings for Symfony
ENV APP_ENV dev
ENV APP_DEBUG 1
ENV APP_SECRET 7246da9658ef30dc881555b5ebc26b5e
ENV COMPOSER_ALLOW_SUPERUSER=1

# Application root dir
WORKDIR /var/www/html

# Make sure log folder is writable
RUN mkdir /var/www/html/var
RUN mkdir /var/www/html/var/cache
RUN mkdir /var/www/html/var/log
RUN chown -R www-data:www-data /var/www/html/var

# Configure PHP-FPM
COPY docker/fpm/www.conf ${PHP_INI_DIR}/php-fpm.d/www.conf
COPY docker/fpm/10-opcache.ini ${PHP_INI_DIR}/conf.d/10-opcache.ini
COPY docker/fpm/11-opcache.ini ${PHP_INI_DIR}/conf.d/11-opcache.ini
COPY docker/fpm/20-timezone.ini ${PHP_INI_DIR}/conf.d/20-timezone.ini
COPY docker/fpm/30-security.ini ${PHP_INI_DIR}/conf.d/30-security.ini
COPY docker/fpm/40-errors.ini ${PHP_INI_DIR}/conf.d/40-errors.ini
COPY docker/fpm/50-performance.ini ${PHP_INI_DIR}/conf.d/50-performance.ini
COPY docker/fpm/60-memory.ini ${PHP_INI_DIR}/conf.d/60-memory.ini

# entrypoint
COPY docker/fpm/entry.sh /entry.sh
RUN chmod 755 /entry.sh

## Continue with lines who often change and/or can not be cached by docker build

# Install supervisor and dependencies for extensions
RUN apt-get update && \
    apt-get install -y autoconf g++ make zip unzip git sudo && \
    rm -rf /var/lib/apt/lists/*

# Give www-data sudo rights
RUN echo "www-data ALL=(ALL) NOPASSWD:ALL" | sudo tee /etc/sudoers.d/www-data

# Install php extensions
RUN docker-php-ext-configure opcache && \
    docker-php-ext-install opcache pdo_mysql mysqli

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# composer build
COPY composer.* /var/www/html/
RUN composer install --no-autoloader --no-scripts --no-interaction --no-cache --no-dev

# load new source
ARG HASH_RELEASE
ENV HASH_RELEASE $HASH_RELEASE
COPY . /var/www/html/
RUN composer dump-autoload --no-dev --optimize --classmap-authoritative --no-interaction --no-cache && \
    composer run-script post-install-cmd --no-dev --no-interaction --no-cache

### DEV PART

# Overwrite validate timestamp
COPY docker/fpm/11-opcache-dev.ini ${PHP_INI_DIR}/conf.d/11-opcache.ini

# XDebug config
COPY docker/fpm/70-xdebug.ini ${PHP_INI_DIR}/conf.d/70-xdebug.ini

# Install XDebug
RUN sudo -E pecl install xdebug-3.4.1
RUN sudo -E docker-php-ext-enable xdebug
