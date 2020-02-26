#!/bin/sh

chmod u+x /tmp/wait-for-it.sh && \
/tmp/wait-for-it.sh mysql:3306 && \
php-fpm7 -F