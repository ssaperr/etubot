<?php

$headers = [
    
	'cache-control: max-age=0',
	'upgrade-insecure-requests: 1',
	'user-agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.182 Safari/537.36 etuBot/1',
	'sec-fetch-user: ?1',
	'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
	'x-compress: null',
	'sec-fetch-site: none',
	'sec-fetch-mode: navigate',
	'accept-encoding: deflate, br',
	'accept-language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
	
];

if (!defined(CURLOPT_RESOLVE)) {
    define('CURLOPT_RESOLVE', 10203);
}

function fetchDataGet($url) {
    $ch = curl_init();
    global $headers, $check_ping_verbose, $cookie_file;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
    curl_setopt($ch, CURLOPT_STDERR, $check_ping_verbose);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function fetchDataPost($url, $opts) {
    $ch = curl_init();
    global $headers, $check_ping_verbose, $cookie_file;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($opts, '', '&'));
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
    curl_setopt($ch, CURLOPT_STDERR, $check_ping_verbose);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


function getTokenOnPage($url) {
    
    $result = fetchDataGet($url);
    
    return explode('"', explode('name="_token" value="', $result)[1])[0];
    
}

function loginLk($email, $passwd) {
    
    $url = 'https://lk.etu.ru/login';
    
    $_token = getTokenOnPage($url);
    
    $opts = [
        'password' => $passwd,
        'email'    => $email,
        '_token'   => $_token,
    ];
    
    $ping = fetchDataPost($url, $opts);
    
}

function getUserInfo($check_ping_verbose, $cookie_file) {
    
    $url = 'https://lk.etu.ru/api/profile/current';
    
    $ping = fetchDataGet($url);
    
    printGotJson($ping);

}
