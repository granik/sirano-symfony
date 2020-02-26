#!/bin/sh

/tmp/wait-for-it.sh mysql:3306 && \
cd /var/www/symfony && \
sudo -u www composer install --no-ansi --no-interaction --no-progress --optimize-autoloader && \
sudo -u www composer skrub --perform && \
php bin/console doctrine:database:create --if-not-exists && \
php bin/console doctrine:migrations:migrate --no-interaction && \
php-fpm7 -F