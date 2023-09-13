<?php

if(!defined('KUKURUZA')) die('Hacking attempt!');

//Собираем эвенты
function parseMessage($peer_id, $message) {
    
    global $l, $db, $user_dir, $lkLink, $k;
    
    $chk_auth_try = explode('-', $message['text']);
    
    $row = $db->GetRow("SELECT id, peer_id FROM ?n WHERE peer_id = ?i", "users", $peer_id);
    
    if(!$row && $message['text'] != $l['trigger']['start']) {
        
        sendMessage($peer_id, $l['welcome']['error']);
        
    } else {
    
        if($chk_auth_try[0] == $l['trigger']['autht']) {
            
            sendMessage($peer_id, $l['auth_msg']['tryg']);
            
            if(!isset($chk_auth_try[1]) || !isset($chk_auth_try[2])) {
                
                sendMessage($peer_id, $l['auth_msg']['nope']);
                
            } else {
                
                file_put_contents($user_dir . 'ping.txt', "");
                file_put_contents($user_dir . 'cookie.txt', "");

                $email  = $chk_auth_try[1];
                $passwd = $chk_auth_try[2];
                
                loginLk($email, $passwd);
                
                $result = fetchDataGet($lkLink['myInfo']);
                
                if($result == '[]') {
                    sendSticker($peer_id, 72821);
                    sendMessage($peer_id, $l['auth_msg']['wrpd']);
                } else {
                    sendMessage($peer_id, $l['auth_msg']['done']);
                    sendWKeybAlias($peer_id, $l['main_txt']['back'], 'main', 0);
                }
                
                
            }

        } else {
            
            $result = fetchDataGet($lkLink['myInfo']);
            if($result != '[]') $logged = true;
            else $logged = false;        
            
            switch($message['text']) {
        
                case $l['trigger']['start']: //"Начать"
            
			        $row = $db->GetRow("SELECT id, peer_id FROM ?n WHERE peer_id = ?i", "users", $peer_id);
			
			        if(!$row) {
			        
			            $user_dir = ROOT_DIR.'/db/'.$peer_id.'/';
            
			            if(!is_dir($user_dir)){
			                @mkdir($user_dir, 0755);
			                @chmod($user_dir, 0755);
			            }
			    
			            $data = [
			                'peer_id' => $peer_id,
			            ];
			    
			            $db->query("INSERT INTO ?n SET ?u", "users", $data);
			    
			            sendSticker($peer_id, 72789);
                        sendMessage($peer_id, $l['welcome']['main']);
                
		    	    } else {
			    
		    	        sendMessage($peer_id, $l['welcome']['already']);
			    
		    	    }
            
                break;
                
                case $l['trigger']['auth']: //"Авторизация"
                    sendSticker($peer_id, 72830);
                    sendMessage($peer_id, $l['welcome']['auth']);
                break;
                
                                
                case $l['trigger']['clear']: //"Сбросить сессию"
                    
                    if($result != '[]') {
                        
                        $result = fetchDataGet($lkLink['dropSess']);
                        
                        file_put_contents($user_dir . 'ping.txt', "");
                        file_put_contents($user_dir . 'cookie.txt', "");
                        
                        $result = fetchDataGet($lkLink['myInfo']);
                        
                        if($result == '[]') {
                            sendSticker($peer_id, 72800);
                            sendMessage($peer_id, $l['welcome']['cleared'], $k['auth_btm']);
                        } else {
                            sendSticker($peer_id, 72821);
                            sendMessage($peer_id, $l['auth_msg']['srve']);
                        }
                        
                    } else {
                        sendNoAuthErr($peer_id);
                    }
                    
                break;
                
                case $l['trigger']['head']: //"Начало"
                
                    if($logged) sendWKeybAlias($peer_id, $l['main_txt']['back'], 'main', 72802);
                    else sendNoAuthErr($peer_id);
                    
                break;
                
                case $l['trigger']['abme']: //"Обо мне"
                
                    if($logged) {
                        
                        $myInfo = json_decode($result, true);
                        
                        $toSend = '';
                        $toSend .= "Основная информация:";
                        $toSend .= "\n";
                        $toSend .= "{$myInfo['second_name']} {$myInfo['first_name']} {$myInfo['middle_name']}";
                        $toSend .= "\n";
                        
                        sendMessage($peer_id, $toSend);
                        
                    } else {
                        
                        sendNoAuthErr($peer_id);
                        
                    }
                    
                break;

                default:
                    sendSticker($peer_id, 72797);
                break;
        
            }
        }
    }
}

function sendNoAuthErr($peer_id) {
    global $l, $k;
    sendSticker($peer_id, 72799);
    sendMessage($peer_id, $l['welcome']['no_auth'], $k['auth_inl']);
}

function sendWKeybAlias($peer_id, $message, $k_alias = '', $sticker = 0) {
    global $l, $k;
    if($sticker > 0) sendSticker($peer_id, $sticker);
    sendMessage($peer_id, $message, $k_alias != '' ? $k[$k_alias] : $k['clear']);
}