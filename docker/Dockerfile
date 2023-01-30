FROM php:8.1-fpm-alpine
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions zip \
    pgsql \
    pdo_pgsql \
    amqp \
    sockets \
    bcmath

RUN apk add --no-cache \
    supervisor \
    nginx

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && mv composer.phar /usr/bin/composer

ADD ./php/php.ini /usr/local/etc/php/php.ini

RUN mkdir -p /run/nginx/ && touch /run/nginx/nginx.pid
RUN mkdir -p /var/lib/nginx/tmp /var/log/nginx /var/cache/nginx \
    && chown -R 1000:1000 /var/lib/nginx /var/log/nginx /var/cache/nginx \
        /run/nginx/nginx.pid \
    && chmod -R 755 /var/lib/nginx /var/log/nginx /var/cache/nginx \
        /run/nginx/nginx.pid

WORKDIR /var/www
# USER 1000:1000

COPY ./nginx/config/project.conf /etc/nginx/nginx.conf
COPY ./supervisor/supervisor.conf /etc/supervisor.conf


ENTRYPOINT [ "supervisord", "-c", "/etc/supervisor.conf" ]
