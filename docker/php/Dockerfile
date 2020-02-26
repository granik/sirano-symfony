FROM alpine:3.9

LABEL maintainer="Janosch Kocsis <jk@coloso.de>"

RUN apk add --update \
    sudo \
    php7-xmlreader \
    php7-xmlwriter \
    php7-fpm \
    php7-apcu \
    php7-ctype \
    php7-curl \
    php7-dom \
    php7-gd \
    php7-iconv \
    php7-imagick \
    php7-json \
    php7-intl \
    php7-mcrypt \
    php7-fileinfo \
    php7-mbstring \
    php7-opcache \
    php7-openssl \
    php7-pdo \
    php7-pdo_mysql \
    php7-mysqli \
    php7-xml \
    php7-zlib \
    php7-phar \
    php7-tokenizer \
    php7-session \
    php7-simplexml \
#    php7-xdebug \
    php7-zip \
    make \
    curl \
    nodejs \
    yarn \
	mc \
	vim

RUN rm -rf /var/cache/apk/* && rm -rf /tmp/* && \
    curl --insecure https://getcomposer.org/download/1.9.2/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer

ADD ./docker/php/php.ini /etc/php7/conf.d/
ADD ./docker/php/php.ini /etc/php7/cli/conf.d/
ADD ./docker/php/php-fpm.conf /etc/php7/php-fpm.d/
ADD ./docker/php/wait-for-it.sh /tmp/
ADD ./docker/php/entrypoint.sh /
#ADD xdebug.ini  /etc/php7/conf.d/

WORKDIR /var/www/symfony

COPY ./backend ./

RUN adduser -D -g 'www' www && chown -R www:www /var/www

RUN sudo -u www composer install --no-ansi --no-interaction --no-progress --optimize-autoloader

RUN apk add bash supervisor

RUN chmod 755 /entrypoint.sh

#ENTRYPOINT ["/entrypoint.sh"]

ADD ./docker/php/crontab /crontab.txt
RUN /usr/bin/crontab /crontab.txt


RUN mkdir /etc/supervisor.d
COPY ./docker/php/supervisor.conf /etc/supervisor.conf

CMD ["supervisord", "-c", "/etc/supervisor.conf"]

EXPOSE 9001