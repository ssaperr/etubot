<?php

if(!defined('KUKURUZA')) die('Hacking attempt!');
	
define('CALLBACK_API_CONFIRMATION_TOKEN', '');  // Строка, которую должен вернуть сервер

define('VK_API_ACCESS_TOKEN', '');   // Ключ доступа сообщества
define('VK_API_SECRET', ''); //csrf-токен

define('CALLBACK_API_EVENT_CONFIRMATION', 'confirmation'); // Тип события о подтверждении сервера
define('CALLBACK_API_EVENT_MESSAGE_NEW', 'message_new'); // Тип события о новом сообщении

define('VK_API_VERSION', '5.89'); // Используемая версия API

?>