<?php
if(!defined('KUKURUZA')) die('Hacking attempt!');

$l = [
    'trigger' => [
        'start' => 'Начать',
        'autht' => 'Авторизоваться',
        'auth'  => 'Авторизация',
        'clear' => 'Сбросить сессию',
        'abme'  => 'Обо мне',
        'head'  => 'Начало',
    ],
    'welcome' => [
        'main'    => "Приветик!\nЭто бот, позволяющий получить доступ к ЛК ЛЭТИ прямо из ВКонтакте.\nЧтобы продолжить, нажмите \"Авторизоваться\"",
        'already' => "Вы уже внесены в базу пользователей, действия не требуются.",
        'error'   => "Мы пока не знаем, кто Вы 👀.\nПожалуйста, напишите \"Начать\", чтобы мы могли продолжить.",
        'auth'    => "Чтобы авторизоваться, отправьте сообщение по форме:\nАвторизоваться-ПОЧТА-ПАРОЛЬ\n\nМы не храним Ваши логины и пароли, они используются только 1 раз во время процесса авторизации и не сохраняются в памяти бота.\n\nИсходный код проекта можно просмотреть тут: https://github.com/ssaperr/etubot",
        'cleared' => "Сессия была отключена.\nЧтобы вновь авторизоваться, можете воспользоваться памяткой - \"Авторизация\"",
        'no_auth' => "Вы не авторизованы.",
    ],
    'main_txt' => [
        'back' => "Вернулись в начало.",
    ],
    'auth_msg' => [
        'tryg' => "Попытка авторизации.",
        'done' => "Вы были авторизованы.",
        'nope' => "Вы пропустили логин или пароль.\nЧтобы авторизоваться, отправьте сообщение по форме:\Авторизоваться-ПОЧТА-ПАРОЛЬ",
        'alry' => "Ваша сессия до сих пор активна.",
        'errr' => "Произошла ошибка.\nВозможно, Вы ввели неверный логин или пароль.\nПопробуйте ещё раз!",
        'wrpd' => "Неверный логин или пароль.\nВозможно, Вы где-то очепятались. Попробуйте ещё раз.",
        'srve' => "Непредвиденная ошибка бота 0_0",
    ],

];