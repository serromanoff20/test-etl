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

СТРУКТУРА ПАПОК
-------------------
      app/                само приложение, где находиться вся кодовая база
        common/           общие элементы проекта
        controllers/      http-контроллеры
        db/               подключение к базе данных
        models/           модели
      docker/             необходимые файлы для docker (php.ini, nginx.conf)
      files/              место хранение временных файлов, пока парситься xlsx-файл

host: localhost; <br />
port: 8222;

После предварительных настроек, можно приступать к работе с самим сервисом:

1) Для того что бы загрузить данные в БД, нужно сделать POST-запрос на эндпоинт - <br />http://localhost:8222/; с праметром "file";
<br />ответ при успешной загрузке:
<br />`<?xml version="1.0" encoding="UTF-8"?>
<feed version="1.0">
    <code>200</code>
    <response>
        <message>Данные загружены</message>
    </response>
</feed>`
<br />ответ при возникновениии ошибки:
<br />`<?xml version="1.0" encoding="UTF-8"?>
<feed version="1.0">
    <code>500</code>
    <response>
        <error>
            <place>app\controllers\MainController</place>
            <message>Неверно составлен запрос</message>
        </error>
    </response>
</feed>`   

2) Весь писок агенств(agency), по GET-запросу на эндпоинт - <br />http://localhost:8222/agency/all; без параметров;
<br />ответ при успешной обработке запроса:
<br />`<?xml version="1.0" encoding="UTF-8"?>
<feed version="1.0">
    <code>200</code>
    <response>
        <item>
            <id>1</id>
            <local_id>УХ-1</local_id>
            <name>Горизонт</name>
        </item>
        <item>
            <id>2</id>
            <local_id>Янв-1</local_id>
            <name>Лидер</name>
        </item>
...
    </response>
</feed>`


3) Фильтр конкретному агенства(agency), по GET-запросу на эндпоинт - <br />http://localhost:8222/agency/by-local-id?local_id=янв-1;
   <br />
   ответ при успешной обработке запроса:
   <br />
   `<?xml version="1.0" encoding="UTF-8"?>
    <feed version="1.0">
        <code>200</code>
        <response>
            <id>2</id>
            <local_id>Янв-1</local_id>
            <name>Лидер</name>
        </response>
    </feed>`
   <br />ответ при возникновениии ошибки:
   <br />`<?xml version="1.0" encoding="UTF-8"?>
    <feed version="1.0">
        <code>500</code>
        <response>
            <error>
                <place>app\models\Agency</place>
                <message>По local_id - янв агенство не найдено</message>
            </error>
        </response>
    </feed>`

4) Фильтр конкретного агенства(agency) по уникальному id, по GET-запросу на эндпоинт - <br />http://localhost:8222/agency/by-id?id=2
<br />ответ при успешной обработке запроса:
<br />`<?xml version="1.0" encoding="UTF-8"?>
<feed version="1.0">
    <code>200</code>
    <response>
        <id>2</id>
        <local_id>Янв-1</local_id>
        <name>Лидер</name>
    </response>
</feed>`
<br />ответ при возникновениии ошибки:
<br />`<?xml version="1.0" encoding="UTF-8"?>
<feed version="1.0">
    <code>500</code>
    <response>
        <error>
            <place>app\models\Agency</place>
            <message>По id - 100 агенство не найдено</message>
        </error>
    </response>
</feed>`

5) Весь список контактов с продавцами(contacts), по GET-запросу на эндпоинт - <br />http://localhost:8222/contacts/all; без параметров;
<br />ответ при успешной обработке запроса:
<br />`<?xml version="1.0" encoding="UTF-8"?>
   <feed version="1.0">
   <code>200</code>
   <response>
   <item>
   <id>1</id>
   <name>Петр Ильич</name>
   <phones>8-800 2000 6000</phones>
   </item>
   <item>
   <id>2</id>
   <name>Сергей Семёныч</name>
   <phones>8923123456, +7123 600 4000</phones>
   </item>
   ...
   </response>
   </feed>`

6) Контакт продавца(contacts) с фильтрацией по агенству, по GET-запросу на эндпоинт - <br />http://localhost:8222/contacts/by-local-id-agency?local_id=янв-1;
<br />ответ при успешной обработке запроса:
<br />`<?xml version="1.0" encoding="UTF-8"?>
   <feed version="1.0">
   <code>200</code>
   <response>
   <id>2</id>
   <name>Сергей Семёныч</name>
   <phones>8923123456, +7123 600 4000</phones>
   </response>
   </feed>`
<br />ответ при возникновениии ошибки:
<br />`<?xml version="1.0" encoding="UTF-8"?>
   <feed version="1.0">
   <code>500</code>
   <response>
   <error>
   <place>app\models\AllView</place>
   <message>По local_id - янв объявление не найдено</message>
   </error>
   </response>
   </feed>`

7) Тот же контакт продовца(contacts), только в качестве входного параметра указываем id агенста, по GET-запросу на эндпоинт - <br />http://localhost:8222/contacts/by-id?id=2;
<br />ответ при успешной обработке запроса:
<br />`<?xml version="1.0" encoding="UTF-8"?>
   <feed version="1.0">
   <code>200</code>
   <response>
   <id>2</id>
   <name>Сергей Семёныч</name>
   <phones>8923123456, +7123 600 4000</phones>
   </response>
   </feed>`
<br />ответ при возникновениии ошибки:
<br />`<?xml version="1.0" encoding="UTF-8"?>
   <feed version="1.0">
   <code>500</code>
   <response>
   <error>
   <place>app\models\Contact</place>
   <message>По id - 1000 контакт не найден</message>
   </error>
   </response>
   </feed>`


Все остальные(manager, estate) GET-запросы, соответственно, аналогичные.
...


1) Посмотреть список всех объявений по недвижемости(all_view), по GET-запросу на эндпоинт - <br />http://localhost:8222/estate/all-view; без параметров;
<br />ответ при успешной обработке запроса:
<br />
`<?xml version="1.0" encoding="UTF-8"?>
<feed version="1.0">
    <code>200</code>
    <response>
        <item>
            <id_agency>1</id_agency>
            <local_id>УХ-1</local_id>
            <name_agency>Горизонт</name_agency>
            <id_estate>1</id_estate>
            <address>ул Подгорная, 6</address>
            <price>4201438</price>
            <rooms>3</rooms>
            <description>Уютная двухкомнатная квартира в центре города.</description>
            <floor>2</floor>
            <house_floors>5</house_floors>
            <id_manager>1</id_manager>
            <name_manager>Иван Грозный</name_manager>
            <id_contacts>1</id_contacts>
            <name_seller>Петр Ильич</name_seller>
            <phones_seller>8-800 2000 6000</phones_seller>
        </item>
        <item>
            <id_agency>2</id_agency>
            <local_id>Янв-1</local_id>
            <name_agency>Лидер</name_agency>
            <id_estate>2</id_estate>
            <address>ул Надгорная, 17</address>
            <price>1897643</price>
            <rooms>1</rooms>
            <description>Светлый лофт с панорамными окнами.</description>
            <floor>3</floor>
            <house_floors>10</house_floors>
            <id_manager>2</id_manager>
            <name_manager>Генадий</name_manager>
            <id_contacts>2</id_contacts>
            <name_seller>Сергей Семёныч</name_seller>
            <phones_seller>8923123456, +7123 600 4000</phones_seller>
        </item>
        ...
    </response>
</feed>`

2) Отфильтровать список объявлений по агенству(estate), по GET-запросу на эндпоинт - <br />http://localhost:8222/estate/by-local-id-agency?local_id=ух-1;
<br />ответ при успешной обработке запроса:
<br />
`<?xml version="1.0" encoding="UTF-8"?>
<feed version="1.0">
    <code>200</code>
    <response>
        <id>1</id>
        <address>ул Подгорная, 6</address>
        <rooms>3</rooms>
        <floor>2</floor>
        <house_floors>5</house_floors>
        <description>Уютная двухкомнатная квартира в центре города.</description>
        <contact_id>1</contact_id>
        <manager_id>1</manager_id>
    </response>
</feed>`
<br />ответ при возникновениии ошибки:
<br />`<?xml version="1.0" encoding="UTF-8"?>
<feed version="1.0">
<code>500</code>
<response>
<error>
<place>app\models\AllView</place>
<message>По local_id - ух объявление не найдено</message>
</error>
</response>
</feed>`

3) Отфильтровать список объявлений по контактам продавца(estate), по GET-запросу на эндпоинт - <br />http://localhost:8222/estate/by-contacts-id?contact_id=1;
<br />ответ при успешной обработке запроса:
<br />
`<?xml version="1.0" encoding="UTF-8"?>
<feed version="1.0">
    <code>200</code>
    <response>
        <item>
            <id>1</id>
            <address>ул Подгорная, 6</address>
            <price>4201438</price>
            <rooms>3</rooms>
            <floor>2</floor>
            <house_floors>5</house_floors>
            <description>Уютная двухкомнатная квартира в центре города.</description>
            <contact_id>1</contact_id>
            <manager_id>1</manager_id>
        </item>
    </response>
</feed>`   
<br />ответ при возникновении ошибки:
<br />`<?xml version="1.0" encoding="UTF-8"?>
   <feed version="1.0">
   <code>500</code>
   <response>
   <error>
   <place>app\models\Estate</place>
   <message>По contact_id - 100 имущество не найдено</message>
   </error>
   </response>
   </feed>`

4) Отфильтровать список объявлений по менеджеру(all_view), по GET-запросу на эндпоинт - <br />http://localhost:8222/estate/by-manager-name?manager_name=Евгений;
<br />ответ при успешной обработке запроса:
<br />
`<?xml version="1.0" encoding="UTF-8"?>
<feed version="1.0">
    <code>200</code>
    <response>
        <item>
            <id_agency>51</id_agency>
            <local_id>ВИП-1</local_id>
            <name_agency>Простор</name_agency>
            <id_estate>51</id_estate>
            <address>пр. Богатый, д 51</address>
            <price>9000000</price>
            <rooms>7</rooms>
            <description>Изысканное предложение для изысканных господ</description>
            <floor>10</floor>
            <house_floors>10</house_floors>
            <id_manager>51</id_manager>
            <name_manager>Евгений</name_manager>
            <id_contacts>38</id_contacts>
            <name_seller>Генадий Романович</name_seller>
            <phones_seller>8 800 2000 600</phones_seller>
        </item>
    </response>
</feed>`   
<br />ответ при возникновениии ошибки:
<br />
`<?xml version="1.0" encoding="UTF-8"?>
<feed version="1.0">
    <code>500</code>
    <response>
        <error>
            <place>app\models\AllView</place>
            <message>По manager_id - Евген имущество не найдено</message>
        </error>
    </response>
</feed>`   
