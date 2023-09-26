<?php

if(!defined('KUKURUZA')) die('Hacking attempt!');

//Собираем эвенты
function parseEvemt($peer_id, $message) {
    
    global $l, $db, $user_dir, $lkLink, $digAttLink, $k, $k_tpl, $m;
    
    $row = $db->GetRow("SELECT id, peer_id, user_group, auto_att FROM ?n WHERE peer_id = ?i", "users", $peer_id);
    
    if(!$row) {
        
        sendMessage($peer_id, $l['welcome']['error']);
        
    } else {

        $result = fetchDataGet($lkLink['myInfo']);
            
        if($result != '[]') $logged = true;
        else $logged = false;        
        
        $eve_id = $message['payload'];
        
        switch($eve_id['event']) {
            
            case 'attLesson':
                
                        
                    if($logged) {
                        
                        loginToDigitSrvc('?client_id=29&redirect_uri=https%3A%2F%2Fdigital.etu.ru%2Fattendance%2Fapi%2Fauth%2Fredirect&response_type=code', 29, $peer_id);
                        
                        
                        $url = 'https://digital.etu.ru/attendance/api/schedule/check-in/'.$eve_id['attLessonId'];
                        $opts = [
                            'ok' => true,
                        ];
                        
                        $ping = json_decode(fetchDataPost($url, $opts), true);
                        
                        $event_data = '{
                            "type": "show_snackbar",
                            "text": "'.$ping['message'].'"
                        }';
                        
                        sendCallBackResp($peer_id, $message['event_id']);
                        
                        $keyboard = json_encode([
                            "one_time" => false,
                            "buttons" => [
                            [ //В начало
                                [
                                    "color" => "secondary",
                                    "action" => [
                                        "type" => "callback",
                                        "payload" => "{\"event\": \"backKeybAtt\"}",
                                        "label" => "Назад"
                                ],
                                ],
                            ],
                            ],
                        ]);
                    
                        if($ping['ok'] == true) {
                            
                            sendSticker($peer_id, 72798);
                            sendMessage($peer_id, 'Вы были успешно отмечены на '.$eve_id['attLessonLabel'], $k['att_n_start']);
                            
                            $check_in = json_decode(fetchDataGet('https://digital.etu.ru/attendance/api/schedule/check-in'), true);
                            $m->set('att_check_in_'.$peer_id, $check_in, 324000);
                        
                        } else {
                            sendSticker($peer_id, 72817);
                            sendMessage($peer_id, $ping['message'], $keyboard);
                        }
                        
                    } else {
                        sendNoAuthErr($peer_id);
                    }
                        
           
                
            break;
            
            case 'backKeybAtt':
                
                sendCallBackResp($peer_id, $message['event_id']);
                
                        
                    if($logged) {
                            
                        //loginToDigitSrvc('?client_id=29&redirect_uri=https%3A%2F%2Fdigital.etu.ru%2Fattendance%2Fapi%2Fauth%2Fredirect&response_type=code', 29, $peer_id);
                            
                        sendWKeybAlias($peer_id, $l['attendance']['start'], $row['auto_att'] == 1 ? 'att_aa_on' : 'att_aa_off', 0);
                            
                    } else {
                            
                        sendNoAuthErr($peer_id);
                            
                    }
                        
             
                
            break;
            
            default:
                sendWKeybAlias($peer_id, false, 'start', 72797);
            break;
        
        }
    }
}
