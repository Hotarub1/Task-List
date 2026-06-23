FROM ubuntu:26.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    php8.5-fpm \
    php8.5-mysql \
    php8.5-xml \
    php8.5-mbstring \
    php8.5-curl \
    php8.5-zip \
    php8.5-gd \
    php8.5-intl \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Listen on TCP instead of a Unix socket so Nginx can reach PHP-FPM across containers
# and allow environment variables to pass through to PHP worker processes
RUN sed -i \
    's|listen = /run/php/php8.5-fpm.sock|listen = 0.0.0.0:9000|' \
    /etc/php/8.5/fpm/pool.d/www.conf \
  && sed -i \
    's|;clear_env = no|clear_env = no|' \
    /etc/php/8.5/fpm/pool.d/www.conf \
  && echo 'clear_env = no' >> /etc/php/8.5/fpm/pool.d/www.conf

# PHP-FPM requires the run directory to exist
RUN mkdir -p /run/php

WORKDIR /var/www/html

EXPOSE 9000

CMD ["/usr/sbin/php-fpm8.5", "--nodaemonize"]
