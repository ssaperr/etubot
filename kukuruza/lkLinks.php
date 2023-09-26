<?php

if(!defined('KUKURUZA')) die('Hacking attempt!');

define('LK_DOMAIN', 'https://lk.etu.ru');
define('DT_DOMAIN', 'https://digital.etu.ru');

$lkLink = [
    'dropSess'  => LK_DOMAIN.'/logout',
    'login'     => LK_DOMAIN.'/login',
    'myInfo'    => LK_DOMAIN.'/api/profile/current',
    'oauth'     => LK_DOMAIN.'/oauth/authorize',
];

$digAttLink = [
    'check_in' => DT_DOMAIN.'/attendance/api/schedule/check-in',
    
];
?>