<?php

if (empty($_REQUEST['date']))
    \f\end2('нет даты', false);

if (!empty($_REQUEST['sp']) && is_numeric($_REQUEST['sp'])) {
    
} else {
    \f\end2('не определена точка продаж', false);
}

\f\timer_start(3);


if (isset($skip_start) && $skip_start === true) {
    
} else {
    require_once '0start.php';
}


// пишем бонусы по зарплате за месяц по 1 точке
// добавляем вычисление процентов от оборота в день
// elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'bonus_record_month') {
// показываем кнопочки быстрого запуска расчёта автооценок по точкам
if (isset($_REQUEST['list_tp']) && $_REQUEST['list_tp'] == 'da') {

    if (isset($_REQUEST['list_tp']) && $_REQUEST['clear_all'] == 'da') {

        echo '<h3>удаляем все автобонусы</h3>';

        $date_start = date('Y-m-01', strtotime($_REQUEST['date']));
        $date_finish = date('Y-m-d', strtotime($date_start . ' +1 month -1 day'));

        \f\Cash::deleteKeyPoFilter([\Nyos\mod\JobDesc::$mod_bonus]);

        \Nyos\mod\items::$search['auto_bonus_zp'] = 'da';
        \Nyos\mod\items::$between_date['date_now'] = [$date_start, $date_finish];
        // \Nyos\mod\items::$return_items_header = true;
        $items = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_bonus);
        \f\pa($items, 2);
        $ids = implode(', ', array_keys($items));
        \f\pa($ids);

        $sql = 'UPDATE `mitems` mi '
                . ' SET `mi`.`status` = \'delete\' '
                . ' WHERE mi.`module` = :module AND mi.`id` IN (' . $ids . ') '
                . ' ;';
        // \f\pa($sql);
        $ff = $db->prepare($sql);
        // \f\pa($var_in_sql);
        $ff->execute([':module' => \Nyos\mod\JobDesc::$mod_bonus]);

        echo '<br/>' . __FILE__ . ' #' . __LINE__;

        die('удалено ' . sizeof($items));


//                    \Nyos\mod\items::$between_date['date_now'] = 'da';
//                    \Nyos\mod\items::$search['auto_bonus_zp'] = 'da';
//                    \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_bonus);


        for ($n = 0; $n <= 32; $n++) {

            $date_now = date('Y-m-d', strtotime($date_start . ' +' . $n . ' day'));

            if (substr($date_now, 5, 2) == substr($date_start, 5, 2)) {
//                // if ( $date_now <= $date_finish ) {
//                    
////                break;
////                }
//                echo '<br/>+';                    
                echo '<br/>' . $date_now . ' ' . $date_finish . ' - ' . substr($date_now, 5, 2) . ' ' . substr($date_start, 5, 2);
//                }
//                
//                
                // UPDATE `mitems` SET `status` = 'delete' WHERE `mitems`.`id` = 9;
                \Nyos\mod\items::deleteFromDops($db, \Nyos\mod\JobDesc::$mod_bonus, ['date_now' => $date_now, 'auto_bonus_zp' => 'da']);
            }
        }

        // \Nyos\mod\items::deleteFromDops($db, \Nyos\mod\JobDesc::$mod_bonus, [ 'date_now' => date('Y-m-d',strtotime($_REQUEST['date']) ), 'auto_bonus_zp' => 'da' ] );
    }

    $sps = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_sale_point);

    echo '<a target="iframe_a" style="display:inline-block; border: 1px solid gray;padding:10px; float:left;" '
    . 'href="/vendor/didrive_mod/jobdesc/1/didrive/ajax.php?action=234">refresh</a>';

    foreach ($sps as $k => $v) {
        echo '<a target="iframe_a" style="display:inline-block; border: 1px solid gray;padding:10px; float:left;" '
        . 'href="/vendor/didrive_mod/jobdesc/1/didrive/ajax.php?action=bonus_record_month&date=2020-06-01&sp=' . $v['id'] . '">' . $v['id'] . '</a>';
    }
    echo '<br clear="all" /><iframe src="demo_iframe.htm" name="iframe_a"></iframe>';
    die();
}




$date_start = date('Y-m-01', strtotime($_REQUEST['date']));
$date_finish = date('Y-m-d', strtotime($date_start . ' +1 month -1 day'));

// ставим переменную чтобы дальше не удалять по дням
//    \Nyos\mod\JobDesc::$no_delete_autobonus_1day = true;

try {

//echo '<br/>'.__FILE__.' '.__LINE__;
//        $ww = \Nyos\mod\JobDesc::creatAutoBonusMonth($db, $_REQUEST['sp'], $date_start);
// die($ww);

    $ee = \Nyos\mod\JobDesc::deleteAutoBonusMonth($db, $_REQUEST['sp'], $date_start );
    // \f\pa($ee,2,'','delete bonus month');
    
    \f\Cash::deleteKeyPoFilter([\Nyos\mod\JobDesc::$mod_bonus]);
    
    $smens7 = \Nyos\mod\JobDesc::newGetSmensFullMonth($db, 'all', $date_start);
    // \f\pa($smens7, 2, '', '$smens7 '.sizeof($smens7) );

    \Nyos\mod\items::$between_date['date'] = [$date_start, $date_finish];
    $metki = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_metki);

//            $ff = $db->prepare($sql);
//            $vars = [];
//            $vars[':user'] = $user;
//            $vars[':date_start'] = $date_start;
//            $vars[':date_finish'] = $date_finish;
//            $ff->execute($vars);
//            $res = $ff->fetchAll();

    $add_bonuses = [];

    foreach ($smens7['data'] as $k => $v) {

        if (!empty($v['spec1_sp'])) {
            $now_sp = $v['spec1_sp'];
        } elseif (!empty($v['job_sp'])) {
            $now_sp = $v['job_sp'];
        } else {
            continue;
        }

        if (!empty($_REQUEST['sp']) && $_REQUEST['sp'] != $now_sp)
            continue;

        // echo '. '; flush();

        $e = \Nyos\mod\JobDesc::setupAutoBonus($db, $now_sp, $v['jobman'], $v['date'], $v['money'], $v);
        //\f\pa($e);

        if ($e['status'] == 'ok')
            $add_bonuses[] = $e['data'];

//            $ocenka = $v['ocenka'] ?? $v['ocenka_auto'] ?? null;
//
//            if (!empty($v['money']['premiya-' . $ocenka])) {
//                $add_bonuses[] = [
//                    'auto_bonus_zp' => 'da',
//                    'jobman' => $v['jobman'],
//                    'sale_point' => $now_sp,
//                    'date_now' => $v['date'],
//                    'summa' => $v['money']['premiya-' . $ocenka],
//                    'text' => 'бонус к зп'
//                ];
//            } elseif (!empty($v['money']['bonus_proc_from_oborot'])) {
//
//                $add_bonuses[] = [
//                    'auto_bonus_zp' => 'da',
//                    'jobman' => $v['jobman'],
//                    'sale_point' => $now_sp,
//                    'date_now' => $v['date'],
//                    'summa' => $v['money']['premiya-' . $ocenka],
//                    'text' => 'бонус к зп'
//                ];
//            }
    }

    // \f\pa($add_bonuses,2,'','$add_bonuses');
    \Nyos\mod\items::addNewSimples($db, \Nyos\mod\JobDesc::$mod_bonus, $add_bonuses);

    // \f\pa(sizeof($add_bonuses));
} catch (Exception $ex) {

    echo $text = '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
    . PHP_EOL . $ex->getMessage() . ' #' . $ex->getCode()
    . PHP_EOL . $ex->getFile() . ' #' . $ex->getLine()
    . PHP_EOL . $ex->getTraceAsString()
    . '</pre>';

    if (class_exists('\nyos\Msg'))
        \nyos\Msg::sendTelegramm($text, null, 1);
}

// \f\end2('end in ajax', true, $ww);

$e = [
    'datas' => $ww['data']['adds'] ?? [],
    'timer' => \f\timer_stop(3),
    'kolvo' => sizeof($add_bonuses) ?? 0,
        // 'w' => $ww
];

//\f\pa($e,2);
//    exit;

\f\end2('ok', true, $e);
