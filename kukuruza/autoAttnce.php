<?php
define('KUKURUZA', true); //Мини-защита от прямого доступа
define('ROOT_DIR', dirname(dirname(__FILE__))); //Линк на корень сайта
define('SYST_DIR', ROOT_DIR.'/kukuruza'); //Основная директория бота

//Sensitive-данные
include(SYST_DIR.'/setup.php');

//lang-строчки
include(SYST_DIR.'/lang.php');

//Клавиатуры
include(SYST_DIR.'/keyboards.php');

//Ссылки на методы ЛК ЕТУ
include(SYST_DIR.'/lkLinks.php');

//База данных
include(SYST_DIR.'/db.php');
$db = new SafeMySql;

$m = new Memcached();
$m->addServer('localhost', 11211);

//Базовый модуль
include(SYST_DIR.'/base.php');

//Подключение модулей
include(SYST_DIR.'/lkModule.php');
include(SYST_DIR.'/parseMessage.php');
include(SYST_DIR.'/parseEvent.php');

date_default_timezone_set( 'Europe/Moscow' );

$server_time = intval($_SERVER['REQUEST_TIME']);

$admUsers = $db->GetAll("SELECT peer_id FROM ?n WHERE head_admin = 1", "users");
foreach($admUsers as $adm_peer_id) {
    sendWKeybAlias($adm_peer_id['peer_id'], $l['attendance']['admin_autoatt_start'], 'att_n_start', 72821);
}

echo "Скрипт начал работу \n";
echo "----------------------------------------------------------------------------\n";

//Пагинация для уменьшения нагрузок
$page = 0;
$gcount = 10;

//Для статистики
$stat_cnt_users = 0;

//Парсим всех, у кого включена опция авто-посещаемости
do {
    
    $page++;
    $limit_page = ($page-1)*$gcount;
    
    $getUsers = $db->GetAll("SELECT id, peer_id, user_group, auto_att FROM ?n WHERE auto_att = 1 LIMIT ?i, ?i", "users", $limit_page, $gcount);
    
    foreach($getUsers as $row) {
        
        $peer_id = $row['peer_id'];
        $user_dir = ROOT_DIR.'/db/'.$peer_id.'/';
        
        if(is_dir($user_dir)) {
            
            echo "Начало лога пользователя ".$peer_id."\n";
            
            $check_ping_verbose = fopen($user_dir .'ping.txt', 'w');
            $cookie_file        = $user_dir . 'cookie.txt';
            
            $result = fetchDataGet($lkLink['myInfo']);
            
            if($result != '[]') $logged = true;
            else $logged = false;   
            
            if($logged) {
                
                $ping = fetchDataGet('https://digital.etu.ru/attendance/api/auth/current-user');
                if($ping == '{}') {
                    
                    //Попытка авторизации в дигит
                    $ping = loginDigit('?client_id=29&redirect_uri=https%3A%2F%2Fdigital.etu.ru%2Fattendance%2Fapi%2Fauth%2Fredirect&response_type=code', 29);

                    $ping = fetchDataGet('https://digital.etu.ru/attendance/api/auth/current-user');
                    
                }    
                
                if($ping != '{}') {
                        
                        if($m->get('att_check_in_'.$peer_id) && !$m->get('att_check_in_'.$peer_id)['message']) {
                            $check_in = $m->get('att_check_in_'.$peer_id);
                        } else {
                            $check_in = json_decode(fetchDataGet('https://digital.etu.ru/attendance/api/schedule/check-in'), true);
                            $m->set('att_check_in_'.$peer_id, $check_in, 324000);
                        }
                        
                        foreach($check_in as $lesson) {
                            
                            if($server_time >= strtotime($lesson['checkInStart']) && $server_time <= strtotime($lesson['checkInDeadline']) && $lesson['selfReported'] != true) {
                                
                                $url = 'https://digital.etu.ru/attendance/api/schedule/check-in/'.$lesson['id'];
                                $opts = [
                                    'ok' => true,
                                ];
                        
                                $ping = json_decode(fetchDataPost($url, $opts), true);
                                
                                if($ping['ok'] == true) {

                                    if($peer_id != $prev_peer) $stat_cnt_users++;
                                    $prev_peer = $peer_id;
                                    
                                    sendSticker($peer_id, 72798);
                                    sendMessage($peer_id, "Авто-посещаемость:\nВы были успешно отмечены на ".$lesson['lesson']['shortTitle'].' '.$lesson['lesson']['subjectType'], $k['att_n_start']);
                            
                                    $check_in = json_decode(fetchDataGet('https://digital.etu.ru/attendance/api/schedule/check-in'), true);
                                    $m->set('att_check_in_'.$peer_id, $check_in, 324000);
                                    
                                    echo $peer_id." отмечен на ".$lesson['lesson']['shortTitle'].' '.$lesson['lesson']['subjectType']."\n";
                                    
                                } else {
                                    
                                    sendSticker($peer_id, 72817);
                                    sendMessage($peer_id, $l['attendance']['auto_att_err_bef_reson'].$ping['message'], $keyboard);
                                    
                                    echo $peer_id." не был отмечен на ".$lesson['lesson']['shortTitle'].' '.$lesson['lesson']['subjectType'].", ошибка ".$ping['message']."\n";
                                    
                                }
                                
                            }
                            
                        }
                        
                        echo "Конец лога пользователя ".$peer_id."\n";
                        
                    } else {
                        
                        echo $peer_id.": фатальная ошибка, не авторизован в дигит после попытки авторизации\n";
                        sendWKeybAlias($peer_id, $l['attendance']['auto_att_err_bef_reson'].$l['attendance']['att_err'], 'att_n_start', 72821);
                        
                    }
                    
                
                
            } else {
                
                echo $peer_id." не авторизован в лк \n";
                sendWKeybAlias($peer_id, $l['attendance']['auto_att_err_bef_reson'].$l['attendance']['auto_att_res_nolog'], 'att_n_start', 72821);
                
                $db->query("UPDATE ?n SET auto_att = ?i WHERE peer_id = ?i", 'users', 0, $peer_id);
                            
                sendWKeybAlias($peer_id, $l['attendance']['auto_att_tooff'], 'auth_btm', 72810);
                            
            }
        }
        
        echo "----------------------------------------------------------------------------\n";
        
    }
    
} while($getUsers);

foreach($admUsers as $adm_peer_id) {
    sendWKeybAlias($adm_peer_id['peer_id'], $l['attendance']['admin_autoatt_end']."\n".$l['attendance']['admin_autoatt_marked'].$stat_cnt_users, 'att_n_start', 0);
}

echo('ok');