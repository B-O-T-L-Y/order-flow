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
    make \
    supervisor


RUN docker-php-ext-configure zip
RUN docker-php-ext-install zip pdo_mysql
RUN docker-php-ext-configure pcntl --enable-pcntl && docker-php-ext-install pcntl;

#RUN pecl install redis
#RUN docker-php-ext-enable redis

# Settings user
RUN addgroup -g $uid -S $user && adduser -u $uid -S -G $user $user

# Install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Workdir
WORKDIR /var/www/html

# Copy backend to container
COPY --chown=$user:$user ./backend /var/www/html
#COPY ./docker/php/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

RUN cp .env.example .env

# Make docker-entrypoint.sh as executable
RUN chmod u+x ./docker-entrypoint.sh

# Create necessary directories and set ownership
RUN mkdir -p /var/log/supervisor /var/run/supervisor /var/run && \
    chown -R $user:$user /var/log/supervisor /var/run/supervisor /var/run

# Ensure supervisor can write to logs
RUN touch /var/log/supervisor/supervisord.log && \
    chown $user:$user /var/log/supervisor/supervisord.log

# Copy Supervisor config
COPY --chown=$user:$user ./docker/php/supervisord.conf /etc/supervisord.conf

# Run entrypoints
ENTRYPOINT ["sh", "./docker-entrypoint.sh"]

# User
USER $user
