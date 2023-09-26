<?php

if(!defined('KUKURUZA')) die('Hacking attempt!');

//Собираем эвенты
function parseMessage($peer_id, $message) {
    
    global $l, $db, $user_dir, $lkLink, $digAttLink, $k, $m, $server_time;
    
    $chk_auth_try = explode('-', $message['text']);
    
    $row = $db->GetRow("SELECT id, peer_id, user_group, auto_att FROM ?n WHERE peer_id = ?i", "users", $peer_id);
    
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
			    
                        sendWKeybAlias($peer_id, $l['welcome']['main'], 'auth_btm', 72789);
                        
		    	    } else {
			            
			            sendWKeybAlias($peer_id, $l['welcome']['already'], 'start', 0);
			    
		    	    }
            
                break;
                
                case $l['trigger']['auth']: //"Авторизация"
                    sendWKeybAlias($peer_id, $l['welcome']['auth'], 'clear', 72830);
                break;
 
                case $l['trigger']['clear']: //"Сбросить сессию"
                    
                    if($result != '[]') {
                        
                        $result = fetchDataGet($lkLink['dropSess']);
                        
                        file_put_contents($user_dir . 'ping.txt', "");
                        file_put_contents($user_dir . 'cookie.txt', "");
                        
                        $m->delete('att_check_in_'.$peer_id);
                        
                        $result = fetchDataGet($lkLink['myInfo']);
                        
                        if($result == '[]') {
                     
                            sendWKeybAlias($peer_id, $l['welcome']['cleared'], 'auth_btm', 72800);
                            
                        } else {
                            
                            sendWKeybAlias($peer_id, $l['auth_msg']['srve'], 'auth_btm', 72821);
                            
                        }
                        
                    } else {
                        sendNoAuthErr($peer_id);
                    }
                    
                break;
                
                case $l['trigger']['cmds']: //"Команды"
                    sendWKeybAlias($peer_id, $l['main_txt']['help'], 'start', 72793);
                break;
                
                case $l['trigger']['head']: //"Начало"
                
                    if($logged) sendWKeybAlias($peer_id, $l['main_txt']['back'], 'main', 72802);
                    else sendNoAuthErr($peer_id);
                    
                break;
                
                case $l['trigger']['abme']: //"Обо мне"
                
                    if($logged) {
                        
                        $myInfo = json_decode($result, true);
                        
                        if($m->get('dict_citizenships')) {
                            $dict_arr = json_decode($m->get('dict_citizenships'), true);
                        } else {
                            $dict_p_val = ['data' => '["dict_citizenships"]',];
                            $dict_arr = fetchDataPost("https://lk.etu.ru/dashboard/api/dictionaries", $dict_p_val);
                            $m->set('dict_citizenships', $dict_arr);
                            $dict_arr = json_decode($dict_arr,true);
                        }
                        
                        $found_country = array_search($myInfo['dict_citizenship_id'], array_column($dict_arr['dict_citizenships'], 'id'));
                        
                        $toSend = '';
                        $toSend .= "{$myInfo['fio']}";
                        $toSend .= "\n";
                        $toSend .= "{$myInfo['position']}";
                        $toSend .= "\n";
                        $toSend .= "{$myInfo['email']}";
                        $toSend .= "\n";
                        $toSend .= "\n";
                        $toSend .= $l['abme']['osn'];
                        $toSend .= "\n";
                        $toSend .= $l['abme']['uid'].": {$myInfo['id']}";
                        $toSend .= "\n";
                        $toSend .= $l['abme']['birth'].": {$myInfo['birth_date']}";
                        $toSend .= "\n";
                        $toSend .= $l['abme']['grazhd'].": {$dict_arr['dict_citizenships'][$found_country]['label']}";
                        
                        sendWKeybAlias($peer_id, $toSend, 'start', 0);
                        
                    } else {
                        
                        sendNoAuthErr($peer_id);
                        
                    }
                    
                break;
                
                case $l['trigger']['attd']: //"Посещаемость"
                    
                        
                        if($logged) {
                            
                            $ping = fetchDataGet('https://digital.etu.ru/attendance/api/auth/current-user');
                            if($ping == '{}') {
                                $ping = loginDigit('?client_id=29&redirect_uri=https%3A%2F%2Fdigital.etu.ru%2Fattendance%2Fapi%2Fauth%2Fredirect&response_type=code', 29);
                            
                            $ping = fetchDataGet('https://digital.etu.ru/attendance/api/auth/current-user');
                            if($ping == '{}') {
                                sendWKeybAlias($peer_id, $l['attendance']['att_err'], 'att_n_start', 72821);
                                echo 'ok';
                                die();
                                }
                            }
                            
                            sendWKeybAlias($peer_id, $l['attendance']['start'], $row['auto_att'] == 1 ? 'att_aa_on' : 'att_aa_off', 72797);
                            
                            
                            
                        } else {
                            
                            sendNoAuthErr($peer_id);
                            
                        }
                   
                    
                break;
                
                case $l['trigger']['auto_att_toon']: //Автоматически отмечать на всех предметах
                case $l['trigger']['auto_att_tooff']: //'Не отмечать автоматически на парах'
                    
                        
                        if($logged) {
                            
                            $ping = fetchDataGet('https://digital.etu.ru/attendance/api/auth/current-user');
                            if($ping == '{}') {
                                $ping = loginDigit('?client_id=29&redirect_uri=https%3A%2F%2Fdigital.etu.ru%2Fattendance%2Fapi%2Fauth%2Fredirect&response_type=code', 29);
                            
                            $ping = fetchDataGet('https://digital.etu.ru/attendance/api/auth/current-user');
                            if($ping == '{}') {
                                sendWKeybAlias($peer_id, $l['attendance']['att_err'], 'att_n_start', 72821);
                                echo 'ok';
                                die();
                            }
                            }
                            
                            $db->query("UPDATE ?n SET auto_att = ?i WHERE peer_id = ?i", 'users', $message['text'] == $l['trigger']['auto_att_toon'] ? 1 : 0, $peer_id);
                            
                            sendWKeybAlias($peer_id, $message['text'] == $l['trigger']['auto_att_toon'] ? $l['attendance']['auto_att_toon'] : $l['attendance']['auto_att_tooff'], $message['text'] == $l['trigger']['auto_att_toon'] ? 'att_aa_on' : 'att_aa_off', $message['text'] == $l['trigger']['auto_att_toon'] ? 72829 : 72810);
                            
                        } else {
                            
                            sendNoAuthErr($peer_id);
                            
                        }
                    
                    
                break;
                
                case $l['trigger']['ava_att_refresh']:
                   
                        
                        if($logged) {
                            
                            $ping = fetchDataGet('https://digital.etu.ru/attendance/api/auth/current-user');
                            if($ping == '{}') {
                                $ping = loginDigit('?client_id=29&redirect_uri=https%3A%2F%2Fdigital.etu.ru%2Fattendance%2Fapi%2Fauth%2Fredirect&response_type=code', 29);
                            
                            $ping = fetchDataGet('https://digital.etu.ru/attendance/api/auth/current-user');
                            if($ping == '{}') {
                                sendWKeybAlias($peer_id, $l['attendance']['att_err'], 'att_n_start', 72821);
                                echo 'ok';
                                die();
                            }
                            }
                            
                            $check_in = json_decode(fetchDataGet('https://digital.etu.ru/attendance/api/schedule/check-in'), true);
                            $m->set('att_check_in_'.$peer_id, $check_in, 324000);
                            
                            sendWKeybAlias($peer_id, $l['attendance']['att_refresh'], 'att_n_start', 72806);
                            
                        } else {
                            
                            sendNoAuthErr($peer_id);
                            
                        }
                      
                    
                break;
                
                case $l['trigger']['ava_att_le']:
                    
                        
                        if($logged) {
                            
                            $ping = fetchDataGet('https://digital.etu.ru/attendance/api/auth/current-user');
                            if($ping == '{}') {
                                $ping = loginDigit('?client_id=29&redirect_uri=https%3A%2F%2Fdigital.etu.ru%2Fattendance%2Fapi%2Fauth%2Fredirect&response_type=code', 29);
                            
                            $ping = fetchDataGet('https://digital.etu.ru/attendance/api/auth/current-user');
                            if($ping == '{}') {
                                sendWKeybAlias($peer_id, $l['attendance']['att_err'], 'att_n_start', 72821);
                                echo 'ok';
                                die();
                            }
                            }
                            
                    if($m->get('att_check_in_'.$peer_id) && !$m->get('att_check_in_'.$peer_id)['message']) {
                        $check_in = $m->get('att_check_in_'.$peer_id);
                    } else {
                        $check_in = json_decode(fetchDataGet('https://digital.etu.ru/attendance/api/schedule/check-in'), true);
                        $m->set('att_check_in_'.$peer_id, $check_in, 324000);
                    }
                    
                    $leTodayMsg = $l['attendance']['today']."\n\n";
                    
                    $att_l_tpl = [];
                    
                    foreach($check_in as $lesson) {
                        
                        
                        if(date('Y-m-d', strtotime($lesson['start'])) == date('Y-m-d', $server_time)) {
                            
                            foreach($lesson['teachers'] as $teacher) {
                                $lesson_tchrs .= $teacher['surname'].' '.$teacher['name'].' '.$teacher['midname']."\n";
                            }
                            
                            $leTodayMsg .= $lesson['lesson']['title']." ".$lesson['lesson']['subjectType'];
                            $leTodayMsg .= "\n";
                            $leTodayMsg .= getTime('H:i', $lesson['start'])."-".getTime('H:i', $lesson['end'])." === ".$l['main_txt']['room']." ".$lesson['room'];
                            $leTodayMsg .= "\n";
                            $leTodayMsg .= $lesson_tchrs;
                            $leTodayMsg .= $lesson['selfReported'] == true ? 'Вы посетили пару' : ($server_time <= strtotime($lesson['start']) ? 'Пара ещё не началась (отм. с '.getTime('H:i', $lesson['checkInStart']).')' : ($server_time <= strtotime($lesson['checkInDeadline']) ? 'Вы можете отметиться на паре до '.getTime('H:i', $lesson['checkInDeadline']) : 'Вы пропустили пару'));
                            $leTodayMsg .= "\n";
                            $leTodayMsg .= "\n";
                            
                            $lesson_tchrs = '';
                            
                            if($server_time >= strtotime($lesson['checkInStart']) && $server_time <= strtotime($lesson['checkInDeadline']) && $lesson['selfReported'] != true) {
                            
                            $cur_lesson = [
                                'id' =>  $lesson['id'],  
                                'label' => $lesson['lesson']['shortTitle'].' '.$lesson['lesson']['subjectType'],
                            ];
                    
                            $cur_lesson['payload'] = [
                                'event' => 'attLesson',
                                'attLessonId'    => $cur_lesson['id'],
                                'attLessonLabel' => $cur_lesson['label'],
                            ];
                            
                            $att_l_tpl[] = [
                                [ //В начало
                                "color" => "primary",
                                "action" => [
                                    "type" => "callback",
                                    "payload" => json_encode($cur_lesson['payload'], true),
                                    "label" => 'Отметиться на: '.$cur_lesson['label'],
                                ],
                                ]
                            ];
                            

                            }
                            
                        }
                        
                    }

                    $att_l_tpl[] = [
                                [ //Принудительно обновить предметы
                                "color" => "primary",
                                "action" => [
                                    "type" => "text",
                                    "payload" => "{\"button\": \"1\"}",
                                    "label" => 'Принудительно обновить предметы',
                                ],
                                ]
                            ];
                        $att_l_tpl[] = [ //Назад
                                [
                                    "color" => "secondary",
                                    "action" => [
                                        "type" => "callback",
                                        "payload" => "{\"event\": \"backKeybAtt\"}",
                                        "label" => "Назад"
                                    ],
                                ],
                        ];
                        
                        $keyboard = json_encode([
                        "one_time" => false,
                        "buttons" => 
                            $att_l_tpl,
                        
                        ]);
                    
                    
                    sendMessage($peer_id, $leTodayMsg, $keyboard); 
                    
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
}
