FROM php:8-alpine

RUN apk add --no-cache \
        php8 \
        php8-fpm \
        libressl-dev  \
        postgresql-dev  \
        mc \
        nano \
        php8-ctype \
        php8-xmlreader \
        php8-xmlwriter \
        php8-fileinfo  \
        php8-gd  \
        php8-simplexml  \
        php8-bcmath \
        php8-redis \
        php8-curl \
        php8-xml \
        php8-zip \
        php8-dom \
        php8-mbstring \
        php8-sockets \
        php8-openssl \
        php8-phar \
        php8-iconv \
        php8-tokenizer \
        php8-pdo_pgsql \
        php8-pgsql \
        php8-session \
        curl
RUN docker-php-ext-install pdo pdo_pgsql

CMD ["php-fpm8", "-F"]

COPY . ./symfony

WORKDIR "/symfony"

RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install --ignore-platform-reqs

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions amqp \
