<?php
define('KUKURUZA', true); //Мини-защита от прямого доступа
define('ROOT_DIR', dirname(dirname(__FILE__))); //Линк на корень сайта
define('SYST_DIR', ROOT_DIR.'/kukuruza'); //Основная директория бота

//Sensitive-данные
include(SYST_DIR.'/setup.php');

//lang-строчки
include(SYST_DIR.'/lang.php');

//Клавиатуры
include(SYST_DIR.'/keyboards.php');

//Ссылки на методы ЛК ЕТУ
include(SYST_DIR.'/lkLinks.php');

//База данных
include(SYST_DIR.'/db.php');
$db = new SafeMySql;

$m = new Memcached();
$m->addServer('localhost', 11211);

//Базовый модуль
include(SYST_DIR.'/base.php');

//Подключение модулей
include(SYST_DIR.'/lkModule.php');
include(SYST_DIR.'/parseMessage.php');
include(SYST_DIR.'/parseEvent.php');

date_default_timezone_set( 'Europe/Moscow' );

$server_time = intval($_SERVER['REQUEST_TIME']);

echo "Скрипт сброса кэша начал работу \n";

$m->flush(0);

echo "Скрипт сброса кэша завершил работу \n";
echo "----------------------------------------------------------------------------\n";

?>