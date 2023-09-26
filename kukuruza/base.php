<?php
function api($method, $params){
    $params['access_token'] = VK_API_ACCESS_TOKEN;
    $params['v']            = VK_API_VERSION;
	$query = http_build_query($params);
	$response = json_decode(file_get_contents('https://api.vk.com/method/'.$method.'?'. $query), true);
	if (!$response || !isset($response['response'])) {
        throw new Exception("Invalid response for {$method} request ".print_r($response));
    }
}

function sendMessage($peer_id, $message, $keyboard = '') {
    global $k;
    $keyboard = $keyboard != '' ? $keyboard : $k['clear'];
    api('messages.send', array(
        'peer_id'  => $peer_id,
        'message'  => $message,
        'keyboard' => $keyboard,
    ));
}

function sendSticker($peer_id, $sticker_id) {
    api('messages.send', array(
    'peer_id'    => $peer_id,
    'sticker_id' => $sticker_id,
  ));
}

function sendNoAuthErr($peer_id) {
    global $l, $k;
    sendSticker($peer_id, 72799);
    sendMessage($peer_id, $l['welcome']['no_auth'], $k['auth_inl']);
}

function sendWKeybAlias($peer_id, $message, $k_alias = '', $sticker = 0) {
    global $l, $k;
    if($sticker > 0) {
        api('messages.send', array(
            'peer_id'  => $peer_id,
            'keyboard' => $k_alias != '' ? $k[$k_alias] : $k['clear'],
            'sticker_id' => $sticker,
        ));
    }
    if($message) {
        api('messages.send', array(
            'peer_id'  => $peer_id,
            'message'  => $message,
            'keyboard' => $k_alias != '' ? $k[$k_alias] : $k['clear'],
        ));
    }
    
}

function sendCallBackResp($peer_id, $event_id, $event_data = '') {
    api('messages.sendMessageEventAnswer', array(
        'peer_id'    => $peer_id,
        'user_id'    => $peer_id,
        'event_id'   => $event_id,
        'event_data' => $event_data != '' ? $event_data : false,
    ));
}

function getTime($stamp, $date) {

    $datetime = new DateTime($date, new DateTimeZone( "Europe/Moscow" ) );
    return $datetime->format($stamp);
}