FROM php:latest

MAINTAINER huangzhhui <h@swoft.org>

RUN /bin/cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime \
    && echo 'Asia/Shanghai' > /etc/timezone

RUN apt-get update \
    && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        curl \
        zip \
        libz-dev \
        openssl \
        libssl-dev \
        libnghttp2-dev \
        libhiredis-dev \
        && docker-php-ext-install iconv \
        && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
        && docker-php-ext-install gd \
        && apt-get clean \
        && apt-get autoremove

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer self-update --clean-backups

RUN pecl install redis && docker-php-ext-enable redis && pecl clear-cache

RUN docker-php-ext-install pdo_mysql

    
# install swoole
RUN cd /root && \
    curl -o /tmp/swoole.tar.gz https://github.com/swoole/swoole-src/archive/master.tar.gz -L && \
    tar zxvf /tmp/swoole.tar.gz && cd swoole-src* && \
    phpize && \
    ./configure \
    --enable-coroutine \
    --enable-openssl  \
    --enable-http2  \
    --enable-async-redis \
    --enable-mysqlnd && \
    make && make install && \
    docker-php-ext-enable swoole && \
    echo "swoole.fast_serialize=On" >> /usr/local/etc/php/conf.d/docker-php-ext-swoole-serialize.ini && \
    apt-get install -y gdb && \
    rm -rf /tmp/*  \
    && docker-php-ext-enable swoole

ADD . /var/www/swoft

WORKDIR /var/www/swoft
RUN composer install --no-dev \
    && composer dump-autoload -o \
    && composer clearcache

EXPOSE 80

CMD ["php", "/var/www/swoft/bin/swoft", "start"]
