## Счета TigerWeb (powered by Laravel)


[Laravel 8.3](https://laravel.com/docs/8.x), PHP 7.4,  

Ссылка [на проект](https://gitlab.tigerweb.ru:9999/arif_it/scheta)

Нужен включенный extension = intl в php.ini

Session engine = DATABASE

Run command: `php /www/scheta.tiger/artisan queue:work`

CRON:  `* * */1 * * php /www/scheta.tiger/artisan schedule:run`

