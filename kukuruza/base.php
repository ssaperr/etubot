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

