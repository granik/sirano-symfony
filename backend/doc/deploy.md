# Развёртывание проекта

После клонирование проекта:

## Загружаем все php-зависимости:

```bash
composer install
```

## Настраиваем окружение.

1. Копируем файл `.env.example` как `.env`.
2. В `.env` прописываем правильный dns базы данных в `DATABASE_URL`.

## Развёртывание базы данных

1. Создаём базу данных:

```bash
php bin/console doctrine:database:create
```

2. Запускаем миграции:

```bash
php bin/console --no-interaction doctrine:migrations:migrate
```

## Настройка веб-сервера

Для работы сайта надо чтобы веб-сервер был настроен на директорию `/public`
проекта.

Пример конфигурационного файла для nginx:

```
server {
    listen 192.168.1.32:80;
    server_name доменное_имя_проекта;

    client_max_body_size 0;

    access_log  /путь_к_лог_файлам/sirano.access_log;
    error_log   /путь_к_лог_файлам/sirano.error_log warn;

    root    /путь_к_проекту/public;
    index   index.php;

    location / {
        # Redirect everything that isn't a real file to index.php
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ /\.(ht|svn|git) {
        deny all;
    }

    location ~ \.php$ {
        try_files   $uri @php;

        include fastcgi_params;

        fastcgi_pass    unix:/путь_к_сокету_php/php-fpm-php.sock;
        fastcgi_param   HOST            $host;
        fastcgi_param   SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location @php {
        include fastcgi_params;

        fastcgi_pass    unix:/путь_к_сокету_php/php-fpm-php.sock;
        fastcgi_param   HOST            $host;
        fastcgi_param   SCRIPT_FILENAME $document_root/index.php;

        fastcgi_index   index.php;
    }
}
```