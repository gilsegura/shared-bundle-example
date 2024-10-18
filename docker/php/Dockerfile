FROM php:8.3-fpm-alpine

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions  \
    gd  \
    bcmath  \
    exif  \
    intl  \
    opcache  \
    pcntl  \
    xsl  \
    zip  \
    apcu \
    pdo_mysql \
    amqp \
    xdebug

RUN install-php-extensions @composer

WORKDIR /var/www

EXPOSE 9000

CMD ["php-fpm", "-F", "-R"]

