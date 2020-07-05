<?php

ini_set('display_errors', 'On'); // сообщения с ошибками будут показываться
error_reporting(E_ALL); // E_ALL - отображаем ВСЕ ошибки


if ($_SERVER['HTTP_HOST'] == 'photo.uralweb.info' || $_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info' || $_SERVER['HTTP_HOST'] == 'a2.uralweb.info' || $_SERVER['HTTP_HOST'] == 'adomik.uralweb.info'
) {
    date_default_timezone_set("Asia/Omsk");
} else {
    date_default_timezone_set("Asia/Yekaterinburg");
}

define('IN_NYOS_PROJECT', true);

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require $_SERVER['DOCUMENT_ROOT'] . '/all/ajax.start.php';

//
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'calc_hand_checks') {

    \Nyos\mod\items::$nocash = true;
    // \Nyos\mod\items::$need_polya_vars = ' /* */ ';

    $e = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_checks);
    // \f\pa($e);

    $aa = [];

    echo sizeof($e);

    $w = 0;
    $w1 = 0;

    foreach ($e as $k => $v) {
        if (!empty($v['start']) && !empty($v['fin']) && !isset($v['hour_on_job'])) {

            $hour = \Nyos\mod\IikoChecks::calculateHoursInRange($v['fin'], $v['start']);

            if ($hour == 0)
                continue;

            if ($hour > 0) {
                $aa[$v['id']]['hour_on_job'] = $hour;
            }
        }
    }

    // \f\pa($aa);
    echo sizeof($aa);

    \Nyos\mod\items::saveNewDop($db, $aa);

    die(__FILE__);
}

//
elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'calc_mont_sp') {

    $date_start = date('Y-m-01', (!empty($_REQUEST['date']) ? strtotime($_REQUEST['date']) : $_SERVER['REQUEST_TIME']));
    $date_fin = date('Y-m-d', strtotime($date_start . ' +1 month -1 day'));

    // echo $date_start . ' ' . $date_fin;
//    \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
//                . ' ON mid.id_item = mi.id '
//                . ' AND mid.name = \'start\' '
//                . ' AND mid.value_datetime >= \'' . $date_start . ' 08:00:00\' '
//                . ' AND mid.value_datetime <= \'' . $date_fin.' 03:00:00\' ';
//    $checks = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_checks );
//    \f\pa($checks);

    \Nyos\mod\items::$where2 = ' AND `head` != \'default\' ';
    $norms = \Nyos\mod\items::get($db, 'sale_point');
    // \f\pa($norms);

    $now_date = date('Y-m-d', $_SERVER['REQUEST_TIME']);

    $return = [];

    for ($i = 0; $i <= 32; $i++) {

        $i_date = date('Y-m-d', strtotime($date_start . ' +' . $i . ' day'));

        if ($i_date >= $now_date || $i_date > $date_fin)
            break;

        // echo '<br/>' . $i_date;

        $sp = $_REQUEST['sp'] ?? 2153;

        $g = [
            't' => 1,
            'action' => 'calc_full_ocenka_day',
            'id' => $sp . '_1',
            'id2' => $sp,
            's' => md5($sp . '_1'),
            's2' => md5($sp),
            'show_timer' => 'da',
            'sp' => $sp,
            'date' => $i_date,
        ];

        $ee = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/vendor/didrive_mod/jobdesc/1/didrive/ajax.php?' . http_build_query($g));
        $return[$i_date] = ( (substr($ee, 0, 1) == '{') ? json_decode($ee, true) : ['status' => 'error', 'html' => $ee] );
        // \f\pa($ee);
    }

    if (!empty($_REQUEST['goto'])) {
        \f\redirect('https://' . $_SERVER['HTTP_HOST'], $_REQUEST['goto']);
    } elseif (!empty($_REQUEST['return']) && $_REQUEST['return'] == 'html') {
        \f\pa($return);
    } elseif (isset($_REQUEST['return']) && $_REQUEST['return'] == 'html-small') {
        die('автооценка обработали даты ' . $date_start . ' - ' . $date_fin);
    } else {
        \f\end2('автооценка обработали даты ' . $date_start . ' - ' . $date_fin, true, $return);
    }

    die();
}

// стираем оценку дня
elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'ocenka_clear') {

    if (empty($_REQUEST['sp']) || empty($_REQUEST['date']))
        \f\end2('не хватает данных', false);

    // $date = ;
    \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
            . ' ON mid.id_item = mi.id '
            . ' AND mid.name = \'date\' '
            . ' AND mid.value_date ' . (!empty($_REQUEST['clear_to_now']) ? ' >= ' : ' = ' ) . ' :d '
            . ' INNER JOIN `mitems-dops` mid2 '
            . ' ON mid2.id_item = mi.id '
            . ' AND mid2.name = \'sale_point\' '
            . ' AND mid2.value = :sp '
    ;
    \Nyos\mod\items::$var_ar_for_1sql[':sp'] = $_REQUEST['sp'];
    \Nyos\mod\items::$var_ar_for_1sql[':d'] = date('Y-m-d', strtotime($_REQUEST['date']));

    \Nyos\mod\items::$return_items_header = true;
    $ocenka = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_ocenki_days);

    // \f\pa($ocenka);
    if (!empty($ocenka))
        foreach ($ocenka as $k => $v) {

            if (!empty($v['id'])) {
                $res = \Nyos\mod\items::deleteId($db, $v['id']);
                // \f\pa($res);
            }
        }

    \f\end2('автооценку удалили', true, $ocenka);

    die();
}

//
elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'calculate_ocenka_auto') {

    $sec_on_job_ajax = 15;

    \f\timer::start(77);
    $timer = 0;
    $get_data = [];

    $telega_send = '';

    /**
     * список рабочих точек продаж
     */
    $list = \Nyos\mod\items::getItemsSimple($db, 'jobman_send_on_sp');
    //\f\pa($list, 2);
    $sps = [];
    foreach ($list['data'] as $k => $v) {
        if (isset($v['dop']['sale_point']) && !isset($sps[$v['dop']['sale_point']])) {
            $sps[$v['dop']['sale_point']] = true;
        }
    }
    //\f\pa($sps, 2, '', '$sps');


    if (isset($_REQUEST['all_delete']) && $_REQUEST['all_delete'] == 'da') {

        $ff = $db->prepare('DELETE FROM mitems WHERE module = :id ');
        $ff->execute(array(':id' => 'sp_ocenki_job_day'));
        // echo '<br/>' . __FILE__ . ' ' . __LINE__;
        exit;
    }


    /**
     * достаём оценки и сортируем по дате убывание
     */
    $ocenki0 = \Nyos\mod\items::getItemsSimple($db, 'sp_ocenki_job_day');
    //\f\pa($ocenki0, 2);
    $ocenki = [];
    foreach ($ocenki0['data'] as $k => $v) {
        $ocenki[] = $v['dop'];
    }
    if (!empty($ocenki))
        usort($ocenki, "\\f\\sort_ar_date_desc");
    //\f\pa($ocenki, 2, '', '$ocenki');

    $sp_ar = \Nyos\mod\items::getItemsSimple($db, 'sale_point', 'show');
    // \f\pa($sp_ar);

    $timer = ceil(\f\timer::stop(1));

    $telega_send = 'Выставляем оценки дню (авто)' . PHP_EOL;

    $r = 0;

    for ($i = 1; $i <= 20; $i++) {

        $date = date('Y-m-d', $_SERVER['REQUEST_TIME'] - 3600 * 24 * $i);
        //echo '<fieldset><legend>' . $date . '</legend>';

        $sps_job = [];
        $sps_error = [];

        foreach ($ocenki as $k => $v) {

            //echo '<br/>' . $v['date'] . '=' . $date;

            if (isset($v['date']) && $v['date'] == $date) {

                //echo '<br/>' . $v['date'] . '=' . $date;
                //echo '<br/>#' . __LINE__;

                foreach ($sps as $sp => $v0) {

                    //echo '<br/>' . $sp . '+' . $v0;
                    $sps_job[$sp] = 1;

                    //if ($nn <= 10)
                    //  \f\pa($v);

                    if ($nn <= 20)
                        $telega_send .= PHP_EOL . '+ ' . $date . ' ' . $sp_ar['data'][$sp]['head'] . PHP_EOL . 'Время ' . $v['ocenka_time'] . ' Оборот ' . $v['ocenka_oborot']
                                . ' НаРуки ' . $v['ocenka_naruki'] . ' Общая ' . $v['ocenka'];

                    $nn++;
                }
            }
        }

        //\f\pa($sps_job, 2, '', '$sps_job');
        //echo '<br/>';
        //echo '<br/>' . __LINE__ . ' |' . $date . '|';

        foreach ($sps as $sp => $a) {

            if (isset($sps_job[$sp])) {

                //echo ' / есть оценка ' . __LINE__;
            } else {

                \f\timer::start(1);

                //echo ' / нет оценка ' . __LINE__;
                //echo ' ( ' . $sp . ' - ' . $date . ' )';
                $req = array(
                    'action' => 'calc_full_ocenka_day',
                    'id' => 1,
                    's' => \Nyos\Nyos::creatSecret(1),
// show_timer	da
                    'sp' => $sp,
                    'date' => $date,
                    'telega_no_send' => 'da'
                );
                //echo 'zapros';

                $telega_send .= PHP_EOL . $date . ' тп ' . $sp_ar['data'][$sp]['head'];

                $ff = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/vendor/didrive_mod/jobdesc/1/didrive/ajax.php?' . http_build_query($req));
                \f\pa($ff, 2, '', '$ff');
                $f1 = json_decode($ff, true);
                \f\pa($f1, 2, '', '$f1');


                /**
                 * если ошибка
                 */
                if (isset($f1['error'])) {
                    $telega_send .= PHP_EOL . 'ошибка: #' . $f1['code'] . ' ' . $f1['error'];

                    $uri = '';
                    $gets = [];

                    // оборот по дате
                    if ($f1['code'] == 10) {

                        //$uri = '/vendor/didrive_api/yadom_time_expectation/ajax.php?';
                        $uri = '/vendor/didrive_mod/iiko_oborot/1/didrive/ajax.php?';
                        $gets = array(
                            'action' => 'get_oborot_for_sps',
                            'get_sp_load' => 1,
                            'hide_form' => 'da',
                            'date' => $date
                        );
                    }

                    if (!empty($uri) && !empty($gets)) {

                        $telega_send .= PHP_EOL . 'ошибка отправлена на доработку';
                        echo '<fieldset><legend>ошибка отправлена на доработку</legend>';


                        echo 'http://' . $_SERVER['HTTP_HOST'] . $uri . http_build_query($gets);
                        $ff = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . $uri . http_build_query($gets));
                        \f\pa($ff, 2, '', '$ff');
                        $f1 = json_decode($ff, true);
                        \f\pa($f1, 2, '', '$f1');


                        echo '</fieldset>';
                    }


                    $ti = ceil(\f\timer::stop(1));
                    // echo '<br/>time:' . $ti;
                    $timer += $ti;

                    continue;
                }
                /**
                 * если обработали норм
                 */ elseif (!empty($f1['ocenka'])
                ) {
                    $telega_send .= PHP_EOL . 'ок: часов ' . ( $f1['hours'] ?? '-' )
                            . ' оборот ' . ( $f1['ocenka_oborot'] ?? '-' )
                            . ' время ' . ( $f1['ocenka_time'] ?? '-' )
                            . ' на руки ' . ( $f1['ocenka_naruki'] ?? '-' )
                            . PHP_EOL
                            . ' общая оценка ' . ( $f1['ocenka'] ?? '-' )

                    ;
                    //continue;
                }

                $get_data[$date][$sp] = json_decode($ff, true);

                $ti = ceil(\f\timer::stop(1));
                // echo '<br/>time:' . $ti;
                $timer += $ti;

                if (!empty($ff['error'])) {
                    $telega_send .= PHP_EOL . 'ошибка: ' . ' #' . $ff['code'] . ' ' . $ff['error'];
                }
            }

            if ($timer > $sec_on_job_ajax)
                break;
        }
        //echo '</fieldset><br/>';

        if ($timer > $sec_on_job_ajax)
            break;
    }


//    echo '<br/>-- timer -- ' . $timer;
//    echo '<br/>';
    //\f\pa($get_data, 2, '', '$get_data');
    //$telega_send = '';

    foreach ($get_data as $date => $v1) {
        foreach ($v1 as $sp => $d) {
            if (!empty($telega_send)) {
                $telega_send .= $d['$telega_send'];
            }
        }
    }



    if (1 == 1 && !empty($telega_send)) {

        if (class_exists('\nyos\Msg'))
            \nyos\Msg::sendTelegramm($telega_send, null, 1);

        if (isset($vv['admin_ajax_job'])) {
            foreach ($vv['admin_ajax_job'] as $k => $v) {
                \nyos\Msg::sendTelegramm($telega_send, $v);
                //\Nyos\NyosMsg::sendTelegramm('Вход в управление ' . PHP_EOL . PHP_EOL . $e, $k );
            }
        }
    }

    \f\end2('закончили обход', true, array(
        'timer' => $timer,
        'sms' => $telega_send
    ));
}
//
elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'calculate_ocenka') {

    $nd = date('Y-m-d', $_SERVER['REQUEST_TIME']);

    $ocenki0 = \Nyos\mod\items::getItemsSimple($db, 'sp_ocenki_job_day');
    //\f\pa($ocenki0, 2);
    foreach ($ocenki0['data'] as $k => $v) {
        $ocenki[$v['dop']['sale_point']][$v['dop']['date']] = 1;
    }
    //\f\pa($ocenki, 2);

    $list = \Nyos\mod\items::getItemsSimple($db, 'jobman_send_on_sp');
    //\f\pa($list, 2);
    $sps = [];
    foreach ($list['data'] as $k => $v) {
        if (isset($v['dop']['sale_point']) && !isset($sps[$v['dop']['sale_point']])) {
            $sps[$v['dop']['sale_point']] = true;
        }
    }
    //\f\pa($sps, 2);

    $sp_ar = \Nyos\mod\items::getItemsSimple($db, 'sale_point', 'show');
    // \f\pa($sp);


    $tt = 0;

    for ($i = 0; $i <= 20; $i++) {

        $date_now = date('Y-m-d', strtotime($nd) - 3600 * 24 * $i);

        foreach ($sps as $sp => $v) {

            if (1 == 1 || !isset($ocenki[$sp][$date_now])) {

                \f\pa($sp);

                $t = \f\timer::start(1);
                $zapros = array(
                    'action' => 'calc_full_ocenka_day',
                    'date' => $date_now,
                    'sp' => $sp,
                    'id' => 1,
                    's' => \Nyos\nyos::creatSecret(1),
                    'no_send_msg' => 'da'
                );
                //$e = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/vendor/didrive_mod/jobdesc/1/didrive/ajax.php?' . http_build_query($zapros));

                try {

                    require DR . '/vendor/didrive_mod/jobdesc/1/didrive/ajax.php?' . http_build_query($zapros);
                } catch (Exception $ex) {
                    $text .= PHP_EOL . 'Ошибка: ' . $ex->getMessage();
                }

                echo '<hr>';
                echo '<pre>';
                print_r($e);
                print_r(json_decode($e, true));
                echo '</pre>';

                $tt += ceil(\f\timer::stop(1));
            }

            echo '<br/>' . $tt;

            if ($tt > 25)
                break;
        }

        if ($tt > 25)
            break;
    }


    $txt_to_tele = 'Обнаружены ошибки при расчёте оценки точки продаж (' . $sp['data'][$_REQUEST['sp']]['head'] . ') за день работы (' . $_REQUEST['date'] . ')' . PHP_EOL . PHP_EOL . $error;

    if (class_exists('\nyos\Msg'))
        \nyos\Msg::sendTelegramm($txt_to_tele, null, 1);

    if (isset($vv['admin_ajax_job'])) {
        foreach ($vv['admin_ajax_job'] as $k => $v) {
            \nyos\Msg::sendTelegramm($txt_to_tele, $v);
            //\Nyos\NyosMsg::sendTelegramm('Вход в управление ' . PHP_EOL . PHP_EOL . $e, $k );
        }
    }


    \f\end2('ok', true);
}


if (isset($_REQUEST['date']{0}) && isset($_REQUEST['s']{5}) && \Nyos\nyos::checkSecret($_REQUEST['s'], $_REQUEST['date']) === true) {

    if (isset($_REQUEST['date']) && isset($_REQUEST['action']) && $_REQUEST['action'] == 'clear_copy_checks_today') {

        \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` md ON '
                . ' `md`.`id_item` = mi.id '
                . 'AND `md`.`name` = \'start\' '
                . 'AND `md`.`value_datetime` >= \'' . date('Y-m-d 05:00:00', strtotime($_REQUEST['date'])) . '\'  '
                . 'AND `md`.`value_datetime` <= \'' . date('Y-m-d 05:00:00', strtotime($_REQUEST['date'] . ' +1day')) . '\'  ';
        $checks2 = $checks = \Nyos\mod\items::getItemsSimple3($db, '050.chekin_checkout');

        // \f\pa($checks);

        $sql2 = '';
        $noscan = [];
        $kolvo = 0;

        foreach ($checks as $k => $v) {

            if ($v['status'] != 'show')
                continue;

            // echo '<br/>' . $v['id'] . ' ' . $v['start'];

            foreach ($checks2 as $k1 => $v1) {

                if (isset($noscan[$v1['id']]))
                    continue;

                if ($v1['status'] != 'show')
                    continue;

                if ($v['id'] != $v1['id'] && $v['jobman'] == $v1['jobman'] && $v1['start'] == $v['start']) {

                    if (isset($noscan[$v['id']]))
                        continue;

//                    echo '<table><tr><td>';
//                    \f\pa($v);
//                    echo '</td><td>';
//                    \f\pa($v1);
//                    echo '</td></tr></table>';

                    if (!isset($v1['ocenka'])) {
                        $sql2 .= (!empty($sql2) ? ' OR ' : '' ) . ' `id` = \'' . $v1['id'] . '\' ';
                        $noscan[$v1['id']] = 1;
                        $kolvo++;
                    } elseif (!isset($v['ocenka'])) {
                        $sql2 .= (!empty($sql2) ? ' OR ' : '' ) . ' `id` = \'' . $v['id'] . '\' ';
                        $noscan[$v['id']] = 1;
                        $kolvo++;
                    }
                }
            }
        }

        if (!empty($sql2)) {
            $ff = $db->prepare('UPDATE `mitems` SET `status` = \'delete\' WHERE ' . $sql2 . ' ;');
            $ff->execute();
            //$ff->execute(array(':id' => (int) $_POST['id2']));
        }

        die('Найдено и удалено копий: ' . $kolvo);

        die($sql2);

        die('<br/>' . __FILE__ . ' #' . __LINE__);
    }

    if (isset($_REQUEST['date']) && isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete_checks_today') {

        \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` md ON '
                . ' `md`.`id_item` = mi.id '
                . 'AND `md`.`name` = \'start\' '
                . 'AND `md`.`value_datetime` >= \'' . date('Y-m-d 05:00:00', strtotime($_REQUEST['date'])) . '\'  '
                . 'AND `md`.`value_datetime` <= \'' . date('Y-m-d 05:00:00', strtotime($_REQUEST['date'] . ' +1day')) . '\'  ';
        // $checks = \Nyos\mod\items::getItemsSimple3($db, '050.chekin_checkout');
        $checks = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_checks);
        // \f\pa($checks);

        $sl = '';
        $sl2 = [];
        $n = 1;

        foreach ($checks as $k => $v) {
            $sl .= (!empty($sl) ? ' OR ' : '' ) . ' `id_item` = :item' . $n . ' ';
            $sl2[':item' . $n] = $v['id'];
            $n++;
        }

        $sql = ' DELETE FROM `mitems-dops` WHERE name = \'fin\' AND ( ' . $sl . ' ); ';
        \f\pa($sql);
        $ff = $db->prepare($sql);
        $ff->execute($sl2);


        die();

        $sql2 = '';
        $noscan = [];
        $kolvo = 0;

        foreach ($checks as $k => $v) {

            if ($v['status'] != 'show')
                continue;

            // echo '<br/>' . $v['id'] . ' ' . $v['start'];

            foreach ($checks2 as $k1 => $v1) {

                if (isset($noscan[$v1['id']]))
                    continue;

                if ($v1['status'] != 'show')
                    continue;

                if ($v['id'] != $v1['id'] && $v['jobman'] == $v1['jobman'] && $v1['start'] == $v['start']) {

                    if (isset($noscan[$v['id']]))
                        continue;

//                    echo '<table><tr><td>';
//                    \f\pa($v);
//                    echo '</td><td>';
//                    \f\pa($v1);
//                    echo '</td></tr></table>';

                    if (!isset($v1['ocenka'])) {
                        $sql2 .= (!empty($sql2) ? ' OR ' : '' ) . ' `id` = \'' . $v1['id'] . '\' ';
                        $noscan[$v1['id']] = 1;
                        $kolvo++;
                    } elseif (!isset($v['ocenka'])) {
                        $sql2 .= (!empty($sql2) ? ' OR ' : '' ) . ' `id` = \'' . $v['id'] . '\' ';
                        $noscan[$v['id']] = 1;
                        $kolvo++;
                    }
                }
            }
        }

        if (!empty($sql2)) {
            $ff = $db->prepare('UPDATE `mitems` SET `status` = \'delete\' WHERE ' . $sql2 . ' ;');
            $ff->execute();
            //$ff->execute(array(':id' => (int) $_POST['id2']));
        }

        die('Найдено и удалено копий: ' . $kolvo);

        die($sql2);

        die('<br/>' . __FILE__ . ' #' . __LINE__);
    }
}



die('The end');

//require_once( DR.'/vendor/didrive/base/class/Nyos.php' );
//require_once( dirname(__FILE__).'/../class.php' );
//if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'scan_new_datafile') {
//
//    scanNewData($db);
//    //cron_scan_new_datafile();
//}
// проверяем секрет
if (
        ( isset($_REQUEST['id_item_check']{0}) && isset($_REQUEST['user']{0}) && isset($_REQUEST['action']{0}) && isset($_REQUEST['s']{5}) &&
        \Nyos\nyos::checkSecret($_REQUEST['s'], $_REQUEST['action'] . $_REQUEST['user'] . '-' . $_REQUEST['id_item_check']) === true
        )
        //
        ||
        //
        ( isset($_REQUEST['user']{0}) && isset($_REQUEST['action']{0}) && isset($_REQUEST['s']{5}) &&
        \Nyos\nyos::checkSecret($_REQUEST['s'], $_REQUEST['action'] . $_REQUEST['user']) === true
        )
        //
        ||
        //
        (
        isset($_REQUEST['id']{0}) && isset($_REQUEST['s']{5}) &&
        \Nyos\nyos::checkSecret($_REQUEST['s'], $_REQUEST['id']) === true
        ) || (
        isset($_REQUEST['id2']{0}) && isset($_REQUEST['s2']{5}) &&
        \Nyos\nyos::checkSecret($_REQUEST['s2'], $_REQUEST['id2']) === true
        )
) {
    
}
//
else {

    $e = '';
//    foreach ($_REQUEST as $k => $v) {
//        $e .= '<Br/>' . $k . ' - ' . $v;
//    }

    f\end2('Произошла неописуемая ситуация #' . __LINE__ . ' обратитесь к администратору ' . $e // . $_REQUEST['id'] . ' && ' . $_REQUEST['secret']
            , 'error');
}

//require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/sql.start.php');
//require( $_SERVER['DOCUMENT_ROOT'] . '/0.site/0.cfg.start.php');
//require( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'class' . DS . 'mysql.php' );
//require( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'db.connector.php' );
// добавляем смену сотруднику
if (isset($_POST['action']) && $_POST['action'] == 'enter_in_sp') {


    require $_SERVER['DOCUMENT_ROOT'] . '/all/ajax.start.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php';


    $e = \Nyos\mod\items::addNew($db, $vv['folder'], \Nyos\Nyos::$menu['050.chekin_checkout'], array(
                'jobman' => $_REQUEST['user'],
                'sale_point' => $_REQUEST['sale_point'],
                'start' => date('Y-m-d H:i', $_SERVER['REQUEST_TIME']),
                'who_add_item' => 'user',
                'who_add_item_id' => $_REQUEST['user']
    ));

//    $s = $db->prepare('SELECT * FROM `gm_user` WHERE `folder` = :folder ');
//    $s->execute(array(':folder' => $folder));
//    $r = $s->fetchAll();
    // \f\pa($r);    
    //\f\pa($e);
    \f\end2('окей');
}
// заканчиваем смену
elseif (isset($_POST['action']) && $_POST['action'] == 'end_job_in_sp') {

    require $_SERVER['DOCUMENT_ROOT'] . '/all/ajax.start.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php';
    // echo \Nyos\Nyos::$folder_now;

    require $_SERVER['DOCUMENT_ROOT'] . '/sites/' . \Nyos\Nyos::$folder_now . '/config.func.php';
    $r = get_no_fin_sp($db, \Nyos\Nyos::$folder_now, $_REQUEST['user']);


    // \f\pa($r);
    // echo $_REQUEST['sp'];


    if ($r['sale_point'] == $_REQUEST['sp']) {

        \f\db\db2_insert($db, 'mitems-dops', array(
            'id_item' => $r['id'],
            'name' => 'fin',
            'value' => date('Y-m-d H:i', $_SERVER['REQUEST_TIME'])
        ));

        \Nyos\mod\items::clearCash(\Nyos\Nyos::$folder_now);

        \f\end2('Смена закрыта, спасибо');
    } else {
        \f\end2('не окей', false);
    }
}

$e = '';
foreach ($_REQUEST as $k => $v) {
    $e .= '<Br/>' . $k . ' - ' . $v;
}

\f\end2('Произошла неописуемая ситуация #' . __LINE__ . ' обратитесь к администратору' . $e, 'error');
exit;


/*
elseif (isset($_POST['action']) && $_POST['action'] == 'delete_smena') {

    require_once DR . '/all/ajax.start.php';

    $ff = $db->prepare('UPDATE `mitems` SET `status` = \'hide\' WHERE `id` = :id ');
    $ff->execute(array(':id' => (int) $_POST['id2']));

    \f\end2('смена удалена');
}
//
elseif (isset($_POST['action']) && $_POST['action'] == 'recover_smena') {

    require_once DR . '/all/ajax.start.php';

    $ff = $db->prepare('UPDATE `mitems` SET `status` = \'show\' WHERE `id` = :id ');
    $ff->execute(array(':id' => (int) $_POST['id2']));

    \f\end2('смена восстановлена');
}
//
elseif (
        isset($_POST['action']) && ( $_POST['action'] == 'add_new_smena' || $_POST['action'] == 'confirm_smena')
) {
    // action=add_new_smena

    try {

        require_once DR . '/all/ajax.start.php';

        if ($_POST['action'] == 'add_new_smena') {

            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'))
                require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php'))
                require ($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php');

            // если старт часов меньше часов сдачи
            if (strtotime($_REQUEST['start_time']) > strtotime($_REQUEST['fin_time'])) {
                //$b .= '<br/>'.__LINE__;
                $start_time = strtotime($_REQUEST['date'] . ' ' . $_REQUEST['start_time']);
                $fin_time = strtotime($_REQUEST['date'] . ' ' . $_REQUEST['fin_time']) + 3600 * 24;
            }
            // если старт часов больше часов сдачи
            else {
                //$b .= '<br/>'.__LINE__;
                $start_time = strtotime($_REQUEST['date'] . ' ' . $_REQUEST['start_time']);
                $fin_time = strtotime($_REQUEST['date'] . ' ' . $_REQUEST['fin_time']);
            }

            \Nyos\mod\items::addNew($db, $vv['folder'], \Nyos\nyos::$menu['050.chekin_checkout'], array(
                'head' => rand(100, 100000),
                'jobman' => $_REQUEST['jobman'],
                'sale_point' => $_REQUEST['salepoint'],
                'start' => date('Y-m-d H:i', $start_time),
                'fin' => date('Y-m-d H:i', $fin_time)
            ));

            \f\end2('<div>'
                    . '<nobr><b class="warn" >смена добавлена</b>'
                    . '<br/>'
                    . date('d.m.y H:i', $start_time) . ' - ' . date('d.m.y H:i', $fin_time)
                    . '</nobr>'
                    . '</div>', true);
        }
        //
        elseif ($_POST['action'] == 'confirm_smena') {

//        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'))
//            require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

            $ff = $db->prepare('DELETE FROM `mitems-dops` WHERE `id_item` = :id AND `name` = \'pay_check\' ;');
            $ff->execute(array(':id' => (int) $_POST['id2']));

            $ff = $db->prepare('INSERT INTO `mitems-dops` ( `id_item`, `name`, `value` ) values ( :id, \'pay_check\', \'yes\' ) ');
            $ff->execute(array(':id' => (int) $_POST['id2']));

            \f\end2( '<div>'
                        . '<nobr>'
                            . '<b class="warn" >отправлено на оплату</b>'
                        . '</nobr>'
                    . '</div>', true );
        }
        //
        elseif ($_POST['action'] == 'edit_items_dop') {

//        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'))
//            require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

            $ff = $db->prepare('DELETE FROM `mitems-dops` WHERE `id_item` = :id AND `name` = \'pay_check\' ;');
            $ff->execute(array(':id' => (int) $_POST['id2']));

            $ff = $db->prepare('INSERT INTO `mitems-dops` ( `id_item`, `name`, `value` ) values ( :id, \'pay_check\', \'yes\' ) ');
            $ff->execute(array(':id' => (int) $_POST['id2']));

            \f\end2( '<div>'
                        . '<nobr>'
                            . '<b class="warn" >отправлено на оплату</b>'
                        . '</nobr>'
                    . '</div>', true );
        }
        
    } catch (\Exception $ex) {

        $e = '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
                . PHP_EOL . $ex->getMessage() . ' #' . $ex->getCode()
                . PHP_EOL . $ex->getFile() . ' #' . $ex->getLine()
                . PHP_EOL . $ex->getTraceAsString()
                . '</pre>';

        \f\end2($e, true);
    } catch (\PDOException $ex) {

        $e = '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
                . PHP_EOL . $ex->getMessage() . ' #' . $ex->getCode()
                . PHP_EOL . $ex->getFile() . ' #' . $ex->getLine()
                . PHP_EOL . $ex->getTraceAsString()
                . '</pre>';

        \f\end2($e, true);
    }
}
//
elseif (isset($_POST['action']) && $_POST['action'] == 'add_new_minus') {
    // action=add_new_smena

    try {

        require_once DR . '/all/ajax.start.php';

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'))
            require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php'))
            require ($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php');

        \Nyos\mod\items::addNew($db, $vv['folder'], \Nyos\nyos::$menu['072.vzuscaniya'], array(
            // 'head' => rand(100, 100000),
            'date_now' => date('Y-m-d', strtotime($_REQUEST['date'])),
            'jobman' => $_REQUEST['jobman'],
            'sale_point' => $_REQUEST['salepoint'],
            'summa' => $_REQUEST['summa'],
            'text' => $_REQUEST['text']
        ));


//        if (date('Y-m-d', $start_time) == date('Y-m-d', $fin_time)) {
//            $dd = true;
//        } else {
//            $dd = false;
//        }
//        $r = ob_get_contents();
//        ob_end_clean();


        \f\end2('<div>'
                . '<nobr><b class="warn" >взыскание добавлено</b>'
                . '<br/>'
                . $_REQUEST['summa']
                . '<br/>'
                . '<small>' . $_REQUEST['text'] . '</small>'
//                . (
//                $dd === true ?
//                        '<br/>с ' . date('H:i', $start_time) . ' - ' . date('H:i', $fin_time) : '<br/>с ' . date('Y-m-d H:i:s', $start_time) . '<br/>по ' . date('Y-m-d H:i:s', $fin_time)
//                )
                // .'окей '.$b
//                . '</br>'
//                . $b
//                . '</br>'
//                . $r
                . '</nobr>'
                . '</div>', true);
    } catch (\Exception $ex) {

        $e = '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
                . PHP_EOL . $ex->getMessage() . ' #' . $ex->getCode()
                . PHP_EOL . $ex->getFile() . ' #' . $ex->getLine()
                . PHP_EOL . $ex->getTraceAsString()
                . '</pre>';

        \f\end2($e, true);
    } catch (\PDOException $ex) {

        $e = '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
                . PHP_EOL . $ex->getMessage() . ' #' . $ex->getCode()
                . PHP_EOL . $ex->getFile() . ' #' . $ex->getLine()
                . PHP_EOL . $ex->getTraceAsString()
                . '</pre>';

        \f\end2($e, true);
    }
}
///
elseif (isset($_POST['action']) && $_POST['action'] == 'show_info_strings') {

    require_once DR . '/all/ajax.start.php';

    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'))
        require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/all/exception.nyosex'))
        require $_SERVER['DOCUMENT_ROOT'] . '/all/exception.nyosex';

    // require_once DR.'/vendor/didrive_mod/items/class.php';
    // \Nyos\mod\items::getItems( $db, $folder )
    // echo DR ;
    $loader = new Twig_Loader_Filesystem(dirname(__FILE__) . '/tpl.ajax/');

// инициализируем Twig
    $twig = new Twig_Environment($loader, array(
        'cache' => $_SERVER['DOCUMENT_ROOT'] . '/templates_c',
        'auto_reload' => true
            //'cache' => false,
            // 'debug' => true
    ));

    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/all/twig.function.php'))
        require ($_SERVER['DOCUMENT_ROOT'] . '/all/twig.function.php');

    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php'))
        require ($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php');

    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/1/twig.function.php'))
        require ($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/1/twig.function.php');

//    \Nyos\Mod\Items::getItems($db, $folder, $module, $stat, $limit);

    $vv['get'] = $_GET;

    $ttwig = $twig->loadTemplate('show_table.htm');
    echo $ttwig->render($vv);

    $r = ob_get_contents();
    ob_end_clean();

    // die($r);


    \f\end2('окей', true, array('data' => $r));
}

f\end2('Произошла неописуемая ситуация #' . __LINE__ . ' обратитесь к администратору', 'error');

exit;
*/