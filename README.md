# test-etl

### PHP
При разработке использовалась версия 8.3.1

### Composer
При разработке использовалась версия 2.6.6

### Docker
Проект был создан в docker окружении.
Находясь в одной директории с docker-compose.yml,
выполните в консоли:

docker-compose up -d 

docker-compose exec php83 composer require phpoffice/phpspreadsheet --working-dir=/data

docker-compose exec php83 composer require vlucas/phpdotenv --working-dir=/data

Корень проекта в docker-контейнире находиться в каталоге: /data;

После запуска докер-контейнера, необходимо настроить своё подключение в файле: /data/.env;

Далее, после настройки подключения к БД, необходимо выполнить на сервере dump.sql-файл, который находиться в: /data/handbook/dump.sql;


___


СТРУКТУРА ПАПОК
-------------------
      app/                само приложение, где находиться вся кодовая база
        common/           общие элементы проекта
        controllers/      http-контроллеры
        db/               подключение к базе данных
        models/           модели
      docker/             необходимые файлы для docker (php.ini, nginx.conf)
      files/              место хранение временных файлов, пока парситься xlsx-файл


