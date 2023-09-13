<?php

if(!defined('KUKURUZA')) die('Hacking attempt!');

define('LK_DOMAIN', 'https://lk.etu.ru');

$lkLink = [
    'dropSess'  => LK_DOMAIN.'/logout',
    'login'     => LK_DOMAIN.'/login',
    'myInfo'    => LK_DOMAIN.'/api/profile/current',
];

?>