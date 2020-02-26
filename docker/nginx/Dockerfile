FROM alpine:3.9

RUN apk add --update nginx
RUN rm -rf /var/cache/apk/* && rm -rf /tmp/*

ADD ./docker/nginx/nginx.conf /etc/nginx/
ADD ./docker/nginx/sirano-dev.conf /etc/nginx/conf.d/default.conf

RUN adduser -D -g 'www' www && chown -R www:www /var/www

CMD ["nginx"]

EXPOSE 80