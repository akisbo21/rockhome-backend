FROM php:7.4.22-fpm

RUN pecl install psr

RUN apt update\
 && apt install -y git \
 && mkdir /var/phalcon \
 && cd /var/phalcon \
 && git clone https://github.com/phalcon/cphalcon \
 && cd ./cphalcon/build \
 && git fetch --all --tags \
 && git checkout tags/v4.1.0 -b v4 \
 && ./install

RUN apt update && apt install nginx -y

RUN apt-get install -y zlib1g-dev libmemcached-dev \
    && pecl install memcached \
    && docker-php-ext-enable memcached \
    && docker-php-ext-enable opcache

RUN pecl install apcu \
    && docker-php-ext-enable apcu \
    && pecl install apcu_bc \
    && echo "extension=apc.so" > /usr/local/etc/php/conf.d/docker-php-ext-xapcu.ini

RUN apt-get install -y libmagickwand-dev \
    && pecl install imagick \
    && docker-php-ext-enable imagick

RUN docker-php-ext-install intl \
    && docker-php-ext-enable opcache

RUN docker-php-ext-install pdo_mysql


# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer update


RUN chmod 777 -R /srv \
    && chmod 777 -R /mnt

RUN rm -R /etc/nginx/sites-enabled/*

RUN mkdir -p /var/cache/nginx && chmod 777 -R /var/cache/nginx

COPY ./conf/fpm/zzz-custom.conf /usr/local/etc/php-fpm.d/zzz-custom.conf

COPY ./conf/entrypoint.sh /etc/entrypoint.sh
COPY ./conf/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./conf/nginx/default.vhost /etc/nginx/sites-enabled/default.vhost

COPY ./conf/php/10-php.ini /usr/local/etc/php/conf.d/10-php.ini
COPY ./conf/php/30-apcu.ini /usr/local/etc/php/conf.d/30-custom-apcu.ini
COPY ./conf/php/40-opcache.ini /usr/local/etc/php/conf.d/40-custom-opcache.ini
COPY ./conf/php/50-session.ini /usr/local/etc/php/conf.d/50-custom-session.ini
COPY ./conf/php/70-php-dev.ini /usr/70-php-dev.ini
COPY ./conf/php/80-phalcon.ini /usr/local/etc/php/conf.d/80-phalcon.ini


RUN apt-get --allow-releaseinfo-change-suite update -y
RUN apt-get upgrade -y

WORKDIR /srv

RUN apt-get install -y autoconf pkg-config libssl-dev
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb


COPY ./conf/nginx.vhost /etc/nginx/sites-enabled/default.vhost

COPY conf/apc.ini /usr/local/etc/php/conf.d/zzzz-apc-cli.ini

RUN mkdir -p /var/cache/nginx && chmod 777 -R /var/cache/nginx

COPY ./src /srv

RUN chmod 777 -R /etc/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/etc/entrypoint.sh"]















