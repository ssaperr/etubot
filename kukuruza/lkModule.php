<?php

$headers = [
    
	'cache-control: max-age=0',
	'upgrade-insecure-requests: 1',
	'user-agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36',
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
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
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
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
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

function loginDigit($params, $client_id) {
    
    global $lkLink, $headers, $check_ping_verbose, $cookie_file, $peer_id, $user_dir;
    
    //$params = '?client_id=29&redirect_uri=https%3A%2F%2Fdigital.etu.ru%2Fattendance%2Fapi%2Fauth%2Fredirect&response_type=code';
    
    $url = 'https://lk.etu.ru/oauth/authorize'.$params;
    
    $result = fetchDataGet($url);
    
    file_put_contents($user_dir.'debug.txt', $result);
    //echo $result;
    
    $_token = explode('"', explode('name="_token" value="', $result)[1])[0];
    $auth_token = explode('"', explode('name="auth_token" value="', $result)[1])[0];
    
    //sendMessage($peer_id, $result ? 1 : 0);
    
    //$client_id = 29;
    
    //echo $_token;
    $opts = [
        'state' => '',
        'auth_token'    => $auth_token,
        '_token'   => $_token,
        'client_id' => $client_id,
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($opts, '', '&'));
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
    curl_setopt($ch, CURLOPT_STDERR, $check_ping_verbose);
    $ping = curl_exec($ch);
    curl_close($ch);
    
    return $ping;
    
}

function loginToDigitSrvc($params, $cli_id, $peer_id) {
    
    global $digAttLink;
    
    $pingAtt = json_decode(fetchDataGet($digAttLink['check-in']), true);
                        
    if(isset($pingAtt['message']) && $pingAtt['message'] == 'Недостаточно прав') {

        loginDigit($params, $cli_id);

        $pingAtt = json_decode(fetchDataGet($digAttLink['check-in']), true);
                                
        if(isset($pingAtt['message']) && $pingAtt['message'] == 'Недостаточно прав') {
            sendWKeybAlias($peer_id, $l['auth_msg']['srve'], 'auth_btm', 72821);
            echo 'ok';
            die();
        }
    
    }
    
}