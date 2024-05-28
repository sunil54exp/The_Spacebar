FROM php:7.2-apache

RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip libssl-dev pkg-config libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && docker-php-ext-install bcmath \
    && docker-php-ext-configure gd && docker-php-ext-install gd \
    && docker-php-ext-install mbstring \
    && apt-get install -y libmcrypt-dev \
    && pecl install mcrypt-1.0.2 && docker-php-ext-enable mcrypt \
    && apt-get install -y libgeoip-dev \
    && pecl install geoip-1.1.1 \
    && docker-php-ext-enable geoip \
    && pecl install xdebug-2.9.3 \
    && apt-get install libsodium-dev -y && docker-php-ext-install sodium \
    && pecl install mongodb-1.12.0 && docker-php-ext-enable mongodb \
    && a2enmod rewrite \
    && a2enmod headers \
    && pecl install redis && docker-php-ext-enable redis \
    && curl -1sLf https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh | bash \
    && apt install symfony-cli \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=1.10.24 \
    && cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini \
    && sed -E -i -e 's,;error_log = syslog,error_log = /dev/stderr,' /usr/local/etc/php/php.ini \
    && apt-get install -y mecab mecab-ipadic-utf8 libgpgme11-dev wget && apt-get update \
    && apt-get install -y apt-transport-https ca-certificates zip unzip libmecab-dev \
    && mkdir /tmp/mecab && cd /tmp/mecab && wget https://github.com/rsky/php-mecab/archive/master.zip && unzip master.zip \
    && cd php-mecab-master/mecab \
    && phpize \
    && ./configure \
    && make \
    && make install \
    && rm -rf /tmp/mecab \
    && cd /usr/local/etc/php/conf.d/ \
    && touch mecab.ini && echo "extension=mecab.so" | tee -a mecab.ini \
    && docker-php-ext-enable mecab \
    && pecl install apcu \
    && pecl install apcu_bc-1.0.3 \
    && docker-php-ext-enable apcu --ini-name 10-docker-php-ext-apcu.ini \
    && docker-php-ext-enable apc --ini-name 20-docker-php-ext-apc.ini \
    && mkdir -p /var/www/project1

ENV PHP_MEMORY_LIMIT=128M
ENV REQUEST_TIMEOUT 60
WORKDIR /var/www/project1

ARG USER_ID
RUN usermod -u $USER_ID www-data
RUN chown -R www-data:www-data /var/www/project1
