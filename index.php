<?php
define("GIT_LNK", 'https://github.com/ssaperr/etubot');

define('KUKURUZA', true); //Мини-защита от прямого доступа
define('ROOT_DIR', dirname(__FILE__)); //Линк на корень сайта
define('SYST_DIR', ROOT_DIR.'/kukuruza'); //Основная директория бота

include(SYST_DIR.'/bot.php'); //Основной модуль бота