FROM php:7.1

LABEL maintainer="inhere <in.798@qq.com>" version="2.0"

# Version
ENV PHPREDIS_VERSION=4.3.0 \
    SWOOLE_VERSION=4.3.5

ADD . /var/www/swoft

# Timezone
RUN /bin/cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime \
    && echo 'Asia/Shanghai' > /etc/timezone \
# Libs
    && apt-get update \
    && apt-get install -y \
        curl \
        wget \
        git \
        zip \
        libz-dev \
        libssl-dev \
        libnghttp2-dev \
        libpcre3-dev \
    && apt-get clean \
    && apt-get autoremove \
# Install composer
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer self-update --clean-backups \
# Some php extension
    && docker-php-ext-install pdo_mysql \
       bcmath \
       sockets \
       zip \
       sysvmsg \
       sysvsem \
       sysvshm \
# Redis extension
    && wget http://pecl.php.net/get/redis-${PHPREDIS_VERSION}.tgz -O /tmp/redis.tar.tgz \
    && pecl install /tmp/redis.tar.tgz \
    && rm -rf /tmp/redis.tar.tgz \
    && docker-php-ext-enable redis \
# Swoole extension
    && wget https://github.com/swoole/swoole-src/archive/v${SWOOLE_VERSION}.tar.gz -O swoole.tar.gz \
    && mkdir -p swoole \
    && tar -xf swoole.tar.gz -C swoole --strip-components=1 \
    && rm swoole.tar.gz \
    && ( \
        cd swoole \
        && phpize \
        && ./configure --enable-mysqlnd --enable-sockets --enable-openssl --enable-http2 \
        && make -j$(nproc) \
        && make install \
    ) \
    && rm -r swoole \
    && docker-php-ext-enable swoole \
# Install composer deps
    && cd /var/www/swoft \
    && composer install \
    && composer clearcache

WORKDIR /var/www/swoft
EXPOSE 18306 18307 18308

ENTRYPOINT ["php", "/var/www/swoft/bin/swoft", "http:start"]
