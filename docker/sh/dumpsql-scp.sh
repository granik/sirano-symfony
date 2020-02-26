#!/bin/bash

# ПОДГОТОВКА:
# 1) Переходим в каталог ~/.ssh
# 2) ssh-keygen                                        Создаем ключи
# 3) ssh-copy-id -p 2233 webserver@77.50.114.196       Копируем ключи на удаленную машину (локалка)
# 4) sudo apt install mysql-client-5.7                 Устанавливаем mysqldump на хост-машину
# 5) 0 3 * * * ~/sirano-vol/sh/dumpmedia-scp.sh >> /var/log/sirano/crontab.log       Задание в crontab -e

# Данные для работы с БД
DBHOST=172.10.0.7               # Адрес БД
DBUSER=sirano                   # Имя пользователя базы данных
DBPASSWD=UrfT73Ac               # Пароль от базы данных
DBNAME=symfony                  # Имя базы данных для резервного копирования
DATETIME=`date +%d%m%Y-%H%M`    # Полная текущая дата и время 

# Файл дампа 
LOCALFILE=~/sirano-vol/dumps/sql/prod-$DBNAME-$DATETIME.sql.gz

#SSH и SCP
REMOTEUSER=webserver@77.50.114.196                       # Удаленный пользователь@сервер
REMOTEPORT=2244                                          # Порт
REMOTEDIR=/home/webserver/sirano-vol/dumps/sql/          # Удаленная папка

# Начало процесса
echo "[-----------------------------[`date +%F-%H-%M-%S`]-----------------------------]"
echo "[`date +%F-%H-%M-%S`] Starting backup"
echo "[`date +%F-%H-%M-%S`] Generate a database gzip dump: '$DBNAME'..."

# Делаем дамп базы
/usr/bin/mysqldump --user=$DBUSER --host=$DBHOST --password=$DBPASSWD $DBNAME | gzip > $LOCALFILE

if [[ $? -gt 0 ]]; then
  # если дамп сделать не удалось (код завершения предыдущей команды больше нуля) - прерываем весь скрипт
  echo "[`date +%F-%H-%M-%S`] Dumping failed! Script aborted."
  exit 1
fi

scp -P $REMOTEPORT $LOCALFILE $REMOTEUSER:$REMOTEDIR      # Копируем архив 
  
if [[ $? -gt 0 ]]; then                                   # Если не скопировался - просто сообщаем 
    echo "[`date +%F-%H-%M-%S`] Cloud: copy failed." 
 else                                                     # Если скопировался - сообщаем
    echo "[`date +%F-%H-%M-%S`] Cloud: file successfully uploaded!"
	echo ""
fi 
 