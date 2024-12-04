FROM php:8.2-fpm-alpine

ARG user
ARG uid

# Install packages
RUN apk --no-cache update && apk add --no-cache \
    zip \
    libzip-dev \
    mysql-client \
    libpng-dev \
    oniguruma-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libmcrypt-dev \
    libxml2-dev \
    autoconf \
    g++ \
    make


RUN docker-php-ext-configure zip
RUN docker-php-ext-install zip pdo_mysql

RUN pecl install redis
RUN docker-php-ext-enable redis

# Settings user
RUN addgroup -g $uid -S $user && \
    adduser -u $uid -S -G $user $user

# Install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Workdir
WORKDIR /var/www/html

# Copy backend to container
COPY --chown=$user:$user ./backend /var/www/html
#COPY ./docker/php/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

# Make docker-entrypoint.sh as executable
RUN chmod u+x ./docker-entrypoint.sh

# Run entrypoints
ENTRYPOINT ["sh", "./docker-entrypoint.sh"]
CMD ["php-fpm"]

# User
USER $user
