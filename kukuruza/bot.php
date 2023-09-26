<?php
if(!defined('KUKURUZA')) die('Hacking attempt!');

//Sensitive-данные
include(SYST_DIR.'/setup.php');

$event = json_decode(file_get_contents('php://input'), true);

if($event['secret'] != VK_API_SECRET) {
    die("Hacking attempt!");
}

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

switch ($event['type']) {
    
    // Подтверждение сервера
    case CALLBACK_API_EVENT_CONFIRMATION:
        
        echo(CALLBACK_API_CONFIRMATION_TOKEN);
        
    break;
    
    // Получение нового сообщения
    case CALLBACK_API_EVENT_MESSAGE_NEW:
        
        $message = $event['object'];
        $peer_id = $message['peer_id'] ?: $message['user_id'];
    
        $user_dir = ROOT_DIR.'/db/'.$peer_id.'/';
        if(is_dir($user_dir)){
            $check_ping_verbose = fopen($user_dir .'ping.txt', 'w');
            $cookie_file        = $user_dir . 'cookie.txt';
        }
    
        parseMessage($peer_id, $message);
    
    break;
    
    case CALLBACK_API_EVENT_MESSAGE_EVENT:
        
        $message = $event['object'];
        $peer_id = $message['peer_id'] ?: $message['user_id'];
        
        $user_dir = ROOT_DIR.'/db/'.$peer_id.'/';
        if(is_dir($user_dir)){
            $check_ping_verbose = fopen($user_dir .'ping.txt', 'w');
            $cookie_file        = $user_dir . 'cookie.txt';
        }
        
        parseEvemt($peer_id, $message);
        
    break;
    
    //Незнакомец хочет поесть кукурузку
    default:
        
        echo('version '.VK_API_VERSION);
        
    break;
    
}

echo('ok');