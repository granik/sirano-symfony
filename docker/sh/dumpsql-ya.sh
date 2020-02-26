#!/bin/bash

# ПОДГОТОВКА:
# 1) sudo apt-get install davfs2 -y   Ставим davfs2 систему (монтировать может из-под юзера)
# 2) sudo  mkdir /mnt/yadisk          Создаем папку под яндекс-диск в систему
# 3) sudo vim /etc/davfs2/secrets     Пишем: /mnt/yadisk полныйадресemail@yandex.ru пароль
# 4) sudo vim /etc/fstab              Пишем: https://webdav.yandex.ru/ /mnt/yadisk davfs user,rw,_netdev,file_mode=600,dir_mode=700 0 1 (пустая строка в конце)
# 5) sudo usermod -aG davfs2 %user%   Добавляем юзера в группу davfs2 (для запуска из-под юзера)
# 6) mount /mnt/yadisk и umount /mnt/yadisk (df -h /mnt/yadisk)

# Данные для работы с БД
DBHOST=172.18.0.4               # Адрес БД
DBUSER=root                     # Имя пользователя базы данных
DBPASSWD=root                   # Пароль от базы данных
DBNAME=dev_pediatrics           # Имя базы данных для резервного копирования
DATETIME=`date +%d%m%Y-%H%M`    # Полная текущая дата и время 

# Файл дампа 
LOCALFILE=/home/webserver/pediatrics-vol/sql/$DBNAME-$DATETIME.sql.gz

# Облачное хранилище
CLOUDUSE=1                      # Копировать ли в облако? Закомментировать строку, если не надо
CLOUDMNT=/mnt/yadisk            # Точка монтирования облака относительно корня
CLOUDDIR=SQL-Dumps/pediatrics   # Папка в облаке, куда будут лететь файлы

# Путь к файлу в облаке
CLOUDFILE=$CLOUDMNT/$CLOUDDIR/$DBNAME-$DATETIME.sql.gz


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

# Отправка в облако
if [[ $CLOUDUSE -eq 1 ]]; then                     # Если задано копирование в облако - делаем
    mount | grep "$CLOUDMNT" > /dev/null           # Проверяем примонтировано ли уже у нас облако (вывод не важен) 
 
    if [[ $? -ne 0 ]]; then                        # Если нет 
        mount $CLOUDMNT                            # значит монтируем 
    fi 
  
    if [[ $? -eq 0 ]]; then                        # если монтирование успешно - копируем туда файл 
        echo "[`date +%F-%H-%M-%S`] Cloud: successfully mounted at $CLOUDMNT" 
        echo "[`date +%F-%H-%M-%S`] Cloud: copying started => $CLOUDFILEGZ" 
    
        cp $LOCALFILE $CLOUDFILE                   # Копируем архив 
  
        if [[ $? -gt 0 ]]; then                    # Если не скопировался - просто сообщаем 
            echo "[`date +%F-%H-%M-%S`] Cloud: copy failed." 
         else                                       # Если скопировался - сообщаем и размонтируем 
            echo "[`date +%F-%H-%M-%S`] Cloud: file successfully uploaded!" 
            umount $CLOUDMNT                       # Размонтирование облака 
		
            if [[ $? -gt 0 ]]; then                # Сообщаем результат размонтирования (если необходимо)
              echo "[`date +%F-%H-%M-%S`] Cloud: umount - failed!"
              rm /var/run/mount.davfs/mnt-yadisk.pid					
            fi
        fi 
     else
        echo "[`date +%F-%H-%M-%S`] Cloud: failed to mount cloud at $CLOUDMNT" 
    fi 
fi
