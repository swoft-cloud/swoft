FROM swoft/swoft:latest

MAINTAINER huangzhhui <huangzhwork@gmail.com>

WORKDIR /var/www/swoft
RUN composer install \
    && composer dump-autoload -o \
    && composer clearcache
