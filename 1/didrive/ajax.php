<?php

ini_set('display_errors', 'On'); // сообщения с ошибками будут показываться
error_reporting(E_ALL); // E_ALL - отображаем ВСЕ ошибки

date_default_timezone_set("Asia/Yekaterinburg");
define('IN_NYOS_PROJECT', true);

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require( $_SERVER['DOCUMENT_ROOT'] . '/all/ajax.start.php' );

//require_once( DR.'/vendor/didrive/base/class/Nyos.php' );
//require_once( dirname(__FILE__).'/../class.php' );
//if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'scan_new_datafile') {
//
//    scanNewData($db);
//    //cron_scan_new_datafile();
//}

// проверяем секрет
if (
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

    foreach ($_REQUEST as $k => $v) {
        $e .= '<Br/>' . $k . ' - ' . $v;
    }

    f\end2('Произошла неописуемая ситуация #' . __LINE__ . ' обратитесь к администратору ' . $e // . $_REQUEST['id'] . ' && ' . $_REQUEST['secret']
            , 'error');
}

//require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/sql.start.php');
//require( $_SERVER['DOCUMENT_ROOT'] . '/0.site/0.cfg.start.php');
//require( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'class' . DS . 'mysql.php' );
//require( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'db.connector.php' );


if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'calc_full_ocenka_day') {

    $error = '';

    $return = array(
        'txt' => '',
        // смен в дне
        'smen_in_day' => 0,
        // часов за день отработано
        'hours' => 0,
        // больше или меньше нормы сделано сегодня ( 1 - больше или равно // 0 - меньше // 2 не получилось достать )
        'oborot_bolee_norm' => 2,
        // сумма денег на руки от количества смен и процента на ФОТ
        'summa_na_ruki' => 0,
        // рекомендуемая оценка управляющего
        'ocenka_upr' => null
    );

    // id items для записи авто оценки
    $id_items_for_new_ocenka = [];

    // require_once DR . '/all/ajax.start.php';
    // $ff = $db->prepare('UPDATE `mitems` SET `status` = \'hide\' WHERE `id` = :id ');
    // $ff->execute(array(':id' => (int) $_POST['id2']));

    /**
     * достаём чеки за день
     */
    \Nyos\mod\items::$sql_itemsdop_add_where_array = array(
        ':dt1' => date('Y-m-d 05:00:01', strtotime($_REQUEST['date']))
        ,
        ':dt2' => date('Y-m-d 23:50:01', strtotime($_REQUEST['date']))
    );
    \Nyos\mod\items::$sql_itemsdop2_add_where = '
        INNER JOIN `mitems-dops` md1 
            ON 
                md1.id_item = mi.id 
                AND md1.name = \'start\'
                AND md1.value_datetime >= :dt1
                AND md1.value_datetime <= :dt2
        ';
    $checki = \Nyos\mod\items::getItemsSimple($db, '050.chekin_checkout', 'show');
    //\f\pa($checki,2,'','$checki');
    foreach ($checki['data'] as $k => $v) {

        $id_items_for_new_ocenka[$v['id']] = 1;

        if (isset($v['dop']['hour_on_job_hand'])) {
            $return['hours'] += $v['dop']['hour_on_job_hand'];
        } elseif (isset($v['dop']['hour_on_job_calc'])) {
            $return['hours'] += $v['dop']['hour_on_job_calc'];
        }
    }

//    $checki = \Nyos\mod\items::getItemsSimple($db, '050.chekin_checkout', 'show');
//    \f\pa($checki,2,'','$checki');

    if (!class_exists('Nyos\mod\JobDesc'))
        require_once DR . DS . 'vendor/didrive_mod/jobdesc/class.php';

    /**
     * достаём нормы на день
     */
    $now_norm = \Nyos\mod\JobDesc::whatNormToDay($db, $_REQUEST['sp'], $_REQUEST['date']);
    // \f\pa($now_norm,2,'','$now_norm');
    foreach ($now_norm as $k => $v) {
        //$return['txt'] .= '<br/><nobr>[norm_' . $k . '] - ' . $v . '</nobr>';
        $return['norm_' . $k] = $v;
    }

    if (empty($return['norm_date'])) {
        $error .= PHP_EOL . 'Нет плановых данных (дата)';
    } elseif (empty($return['norm_vuruchka']) || empty($return['norm_time_wait_norm_cold']) || empty($return['norm_procent_oplata_truda_on_oborota']) || empty($return['norm_kolvo_hour_in1smena'])) {
        $error .= PHP_EOL . 'Не все плановые данные по ТП указаны';
    }


//    $salary = \Nyos\mod\JobDesc::configGetJobmansSmenas($db);
//    \f\pa($salary,2,'','$salary');
//    $return['txt'] .= '<br/>salary';
//    foreach ($salary as $k => $v) {
//        $return['txt'] .= '<br/><nobr>[' . $k . '] - ' . $v . '</nobr>';
//        $return['salary_' . $k] = $v;
//    }

    /**
     * достаём оборот за сегодня
     */
    \Nyos\mod\items::$sql_itemsdop_add_where_array = array(
        ':date' => date('Y-m-d', strtotime($_REQUEST['date']))
        ,
        ':sp' => $_REQUEST['sp']
    );
    \Nyos\mod\items::$sql_itemsdop2_add_where = '
        INNER JOIN `mitems-dops` md1 
            ON 
                md1.id_item = mi.id 
                AND md1.name = \'sale_point\'
                AND md1.value = :sp
                '
            . '
        INNER JOIN `mitems-dops` md2
            ON 
                md2.id_item = mi.id 
                AND md2.name = \'date\'
                AND md2.value_date = :date
        '
    ;
    $oborot = \Nyos\mod\items::getItemsSimple($db, 'sale_point_oborot', 'show');
    // \f\pa($oborot, 2, '', '$oborot');
    foreach ($oborot['data'] as $k1 => $v1) {
        if (isset($v1['dop']))
            foreach ($v1['dop'] as $k => $v) {
                //$return['txt'] .= '<br/><nobr>[oborot_' . $k . '] - ' . $v . '</nobr>';
                $return['oborot_' . $k] = $v;
            }

        $return['oborot'] = $v1['dop']['oborot_server'] ?? $v1['dop']['oborot_hand'] ?? false;

        break;
    }

    if ($return['oborot'] === false) {
        $error .= PHP_EOL . 'Оборот точки продаж не указан';
    }




    /**
     * достаём время ожидания за сегодня
     */
    \Nyos\mod\items::$sql_itemsdop_add_where_array = array(
        ':date' => date('Y-m-d', strtotime($_REQUEST['date']))
        ,
        ':sp' => $_REQUEST['sp']
    );
    \Nyos\mod\items::$sql_itemsdop2_add_where = '
        INNER JOIN `mitems-dops` md1 
            ON 
                md1.id_item = mi.id 
                AND md1.name = \'sale_point\'
                AND md1.value = :sp
                '
            . '
        INNER JOIN `mitems-dops` md2
            ON 
                md2.id_item = mi.id 
                AND md2.name = \'date\'
                AND md2.value_date = :date
        '
    ;
    $timeo = \Nyos\mod\items::getItemsSimple($db, '074.time_expectations_list', 'show');
    // \f\pa($oborot, 2, '', '$oborot');
    foreach ($timeo['data'] as $k1 => $v1) {
        if (isset($v1['dop']))
            foreach ($v1['dop'] as $k => $v) {
                //$return['txt'] .= '<br/><nobr>[oborot_' . $k . '] - ' . $v . '</nobr>';
                $return['timeo_' . $k] = $v;
            }

        //$return['oborot'] = $v1['dop']['oborot_server'] ?? $v1['dop']['oborot_hand'] ?? false;

        break;
    }








    // если есть ошибки
    if (!empty($error)) {




        require_once DR . dir_site . 'config.php';

        $sp = \Nyos\mod\items::getItemsSimple($db, 'sale_point', 'show');
        // \f\pa($sp);

        $txt_to_tele = 'Обнаружены ошибки при расчёте оценки точки продаж (' . $sp['data'][$_REQUEST['sp']]['head'] . ') за день работы (' . $_REQUEST['date'] . ')' . PHP_EOL . PHP_EOL . $error;

        if (class_exists('\nyos\Msg'))
            \nyos\Msg::sendTelegramm($txt_to_tele, null, 1);

        if (isset($vv['admin_ajax_job'])) {
            foreach ($vv['admin_ajax_job'] as $k => $v) {
                \nyos\Msg::sendTelegramm($txt_to_tele, $v);
                //\Nyos\NyosMsg::sendTelegramm('Вход в управление ' . PHP_EOL . PHP_EOL . $e, $k );
            }
        }







        return \f\end2('Обнаружены ошибки при расчёте оценки точки продаж (' . $_REQUEST['sp'] . ') за день работы (' . $_REQUEST['date'] . ')' . $error, false);
    }
    // если нет ошибок считаем
    else {





        if (isset($return['norm_kolvo_hour_in1smena'])) {
            $return['smen_in_day'] = round($return['hours'] / $return['norm_kolvo_hour_in1smena'], 1);
            $return['txt'] .= '<br/>КОЛПОВ: ' . $return['smen_in_day'];

            $return['on_hand_fakt'] = ceil($return['oborot'] / $return['smen_in_day']);
            $return['txt'] .= '<br/>Y ФАКТ_НА_РУКИ: ' . $return['on_hand_fakt'];
        }




        if (isset($return['timeo_cold']) && isset($return['norm_time_wait_norm_cold']) &&
                $return['timeo_cold'] < $return['norm_time_wait_norm_cold']) {

            $return['ocenka_upr'] = 3;
            $return['txt'] .= '<br/><br/>сравнили время ожидания и норма времени ожидания, не норм, оценка 3';
        } else {
            $return['txt'] .= '<br/><br/>сравнили время ожидания и норма времени ожидания, норм, оценка 5';
        }

        if (isset($return['norm_vuruchka'])) {
            if (isset($return['oborot_oborot_server'])) {
                if ($return['oborot_oborot_server'] >= $return['norm_vuruchka']) {
                    $return['oborot_bolee_norm'] = 1;
                } else {
                    $return['oborot_bolee_norm'] = 0;
                }
            } elseif (isset($return['oborot_oborot_hand'])) {
                if ($return['oborot_oborot_hand'] >= $return['norm_vuruchka']) {
                    $return['oborot_bolee_norm'] = 1;
                } else {
                    $return['oborot_bolee_norm'] = 0;
                }
            }
        }

        $return['summa_na_ruki_norm'] = ceil($return['oborot'] / 100 * $return['norm_procent_oplata_truda_on_oborota']);
        $return['txt'] .= '<br/>Z ФОТ_НОРМА: ' . $return['summa_na_ruki_norm'];


        if ($return['on_hand_fakt'] < $return['summa_na_ruki_norm']) {
            $return['ocenka_upr'] = 3;
            $return['txt'] .= '<br/><br/>сравнили на руки по факту и норма на руки, не норм, оценка 3';
        } else {
            $return['txt'] .= '<br/><br/>сравнили на руки по факту и норма на руки, норм, оценка 5';
        }

        if (empty($return['ocenka_upr']))
            $return['ocenka_upr'] = 5;

        $return['txt'] .= '<br/><nobr>рекомендуемая оценка упр: ' . $return['ocenka_upr'] . '</nobr>';

        $sql_del = '';
        $sql_ar_new = [];
        foreach ($id_items_for_new_ocenka as $id_item => $v) {

            $sql_del .= (!empty($sql_del) ? ' OR ' : '' ) . ' id_item = \'' . (int) $id_item . '\' ';
            $sql_ar_new[] = array(
                'id_item' => $id_item,
                'name' => 'ocenka_auto',
                'value' => $return['ocenka_upr']
            );
        }


        $ff = $db->prepare('DELETE FROM `mitems-dops` WHERE name = \'ocenka_auto\' AND ( ' . $sql_del . ' ) ');
        $ff->execute();


        \f\db\sql_insert_mnogo($db, 'mitems-dops', $sql_ar_new);
        $return['txt'] .= '<br/>автоценку записали сотрудникам';

        require_once DR . dir_site . 'config.php';

        $sp = \Nyos\mod\items::getItemsSimple($db, 'sale_point', 'show');
        // \f\pa($sp);

        $txt_to_tele = 'Расчитали автооценку ( ' . $sp['data'][$_REQUEST['sp']]['head'] . ' ) за день работы (' . $_REQUEST['date'] . ')' . PHP_EOL . PHP_EOL . str_replace('<br/>', PHP_EOL, $return['txt']);

        if (class_exists('\nyos\Msg'))
            \nyos\Msg::sendTelegramm($txt_to_tele, null, 1);

        if (isset($vv['admin_ajax_job'])) {
            foreach ($vv['admin_ajax_job'] as $k => $v) {
                \nyos\Msg::sendTelegramm($txt_to_tele, $v);
                //\Nyos\NyosMsg::sendTelegramm('Вход в управление ' . PHP_EOL . PHP_EOL . $e, $k );
            }
        }

        return \f\end2(
                $return['txt']
                . '<br/>часов: ' . $return['hours']
                . '<br/>смен в дне: ' . $return['smen_in_day']
                , true, $return);
    }
}
//
elseif (isset($_POST['action']) && ( $_POST['action'] == 'delete_smena' || $_POST['action'] == 'delete_comment')) {

    // require_once DR . '/all/ajax.start.php';

    $ff = $db->prepare('UPDATE `mitems` SET `status` = \'hide\' WHERE `id` = :id ');
    $ff->execute(array(':id' => (int) $_POST['id2']));

    \f\end2('удалено');
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
        isset($_POST['action']) && (
        $_POST['action'] == 'add_new_smena' ||
        $_POST['action'] == 'add_comment' ||
        $_POST['action'] == 'confirm_smena' ||
        $_POST['action'] == 'goto_other_sp'
        )
) {
    // action=add_new_smena

    try {

        //require_once DR . '/all/ajax.start.php';
        // action=add_new_smena
        // \f\pa($_POST);
        // [date] => 2019-06-27
        // [toform_sp] => 2611
        // [action] => goto_other_sp
        // [id2] => 10    
        // [jobman] => 1886        
        /**
         * отправляем сотрудника на другую точку
         */
        if ($_POST['action'] == 'goto_other_sp') {

//            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'))
//                require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
//
//            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php'))
//                require ($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php');
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
        elseif ($_POST['action'] == 'add_new_smena') {

//            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'))
//                require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
//
//            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php'))
//                require ($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php');
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

            $indb = array(
                'head' => rand(100, 100000),
                'jobman' => $_REQUEST['jobman'],
                'sale_point' => $_REQUEST['salepoint'],
                'start' => date('Y-m-d H:i', $start_time),
                'fin' => date('Y-m-d H:i', $fin_time),
                'hour_on_job_calc' => \Nyos\mod\IikoChecks::calculateHoursInRange($start_time, $fin_time),
                'who_add_item' => 'admin',
                'who_add_item_id' => $_SESSION['now_user_di']['id'] ?? '',
                'ocenka' => $_REQUEST['ocenka']
            );



            \Nyos\mod\items::addNew($db, $vv['folder'], \Nyos\nyos::$menu['050.chekin_checkout'], $indb);

            \f\end2('<div>'
                    . '<nobr><b class="warn" >смена добавлена</b>'
                    . '<br/>'
                    . date('d.m.y H:i', $start_time) . ' - ' . date('d.m.y H:i', $fin_time)
                    . '</nobr>'
                    . '</div>', true);
        } elseif ($_POST['action'] == 'add_comment') {

            $indb = $_REQUEST;

//array(
//                // 'head' => rand(100, 100000),
//                'jobman' => $_REQUEST['jobman'],
//                'sale_point' => $_REQUEST['salepoint'],
//                'start' => date('Y-m-d H:i', $start_time),
//                'fin' => date('Y-m-d H:i', $fin_time)
//            )
            //\f\pa( $indb );
            \Nyos\mod\items::addNew($db, $vv['folder'], \Nyos\nyos::$menu['073.comments'], $indb);

            \f\end2('<div style="background-color: gray; padding:5px;" >'
                    . '<b class="warn" >добавили комментарий</b>'
                    . '<br/>'
                    . $_REQUEST['comment']
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

            \f\end2('<div>'
                    . '<nobr>'
                    . '<b class="warn" >отправлено на оплату</b>'
                    . '</nobr>'
                    . '</div>', true);
        }
        //
        elseif ($_POST['action'] == 'edit_items_dop') {

//        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'))
//            require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

            $ff = $db->prepare('DELETE FROM `mitems-dops` WHERE `id_item` = :id AND `name` = \'pay_check\' ;');
            $ff->execute(array(':id' => (int) $_POST['id2']));

            $ff = $db->prepare('INSERT INTO `mitems-dops` ( `id_item`, `name`, `value` ) values ( :id, \'pay_check\', \'yes\' ) ');
            $ff->execute(array(':id' => (int) $_POST['id2']));

            \f\end2('<div>'
                    . '<nobr>'
                    . '<b class="warn" >отправлено на оплату</b>'
                    . '</nobr>'
                    . '</div>', true);
        }
    }
    //
    catch (\Exception $ex) {

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

//        require_once DR . '/all/ajax.start.php';
//
//        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'))
//            require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
//
//        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php'))
//            require ($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php');

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
//
elseif (isset($_POST['action']) && $_POST['action'] == 'add_new_plus') {
    // action=add_new_smena

    try {

        //require_once DR . '/all/ajax.start.php';
//        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'))
//            require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
//
//        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php'))
//            require ($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php');

        \Nyos\mod\items::addNew($db, $vv['folder'], \Nyos\nyos::$menu['072.plus'], array(
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
                . '<nobr><b class="warn" >премия добавлена'
                . '<br/>'
                . $_REQUEST['summa']
                . '<br/>'
                . '<small>' . $_REQUEST['text'] . '</small>'
                . '</b>'
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

//    require_once DR . '/all/ajax.start.php';
//
//    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'))
//        require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
//
//    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/all/exception.nyosex'))
//        require $_SERVER['DOCUMENT_ROOT'] . '/all/exception.nyosex';
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
