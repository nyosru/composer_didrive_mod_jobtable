<?php

//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);

ini_set('display_errors', 'On'); // сообщения с ошибками будут показываться
error_reporting(E_ALL); // E_ALL - отображаем ВСЕ ошибки
// error_reporting(-1); // E_ALL - отображаем ВСЕ ошибки

if ($_SERVER['HTTP_HOST'] == 'photo.uralweb.info' || $_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info' || $_SERVER['HTTP_HOST'] == 'a2.uralweb.info' || $_SERVER['HTTP_HOST'] == 'adomik.uralweb.info'
) {
    date_default_timezone_set("Asia/Omsk");
} else {
    date_default_timezone_set("Asia/Yekaterinburg");
}

define('IN_NYOS_PROJECT', true);

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

//\f\timer::start();
require( $_SERVER['DOCUMENT_ROOT'] . '/all/ajax.start.php' );



/**
 * сколько сек на работу скрипта
 */
$stop_job_sec = 25;









$msg = 'Запущен расчёт оценок за 45 прошедших дней';

// --------------------------------- //
// защита от повторного срабатывания в секундах
$time_expire = 60 * 60 * 11;
$time_expire = 60 * 60;


\f\timer_start(5);
$txt = 'обновляем автооценки дней ТП за текущий месяц';
$txt0 = '';
$txt2 = '';

// --------------------------------- // 


\f\timer_start(123);



$sps = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_sale_point);
// \f\pa($sps, 2,'','$sps');




$ocenki_sp_date = [];
if (1 == 1) {

    \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
            . ' ON mid.id_item = mi.id '
            . ' AND mid.name = \'date\' '
            . ' AND mid.value_date >= :ds '
    ;
    \Nyos\mod\items::$var_ar_for_1sql[':ds'] = date('Y-m-d', $_SERVER['REQUEST_TIME'] - 3600 * 24 * 40);
    \Nyos\mod\items::$where2dop = ' AND (  midop.name = \'date\' OR midop.name = \'ocenka\' OR midop.name = \'sale_point\' ) ';

    $sps_ocenki = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_ocenki_days);
// \f\pa( $sps_ocenki, 2, '', '$sps_ocenki' );

    foreach ($sps_ocenki as $k => $v) {
        $ocenki_sp_date[$v['sale_point']][$v['date']] = 1;
    }

    unset($sps_ocenki);
}







$timer = \f\timer_stop(123, 'ar');
echo '<br/>timer ' . __LINE__ . ' sec: ' . round($timer['sec'], 2);






// удаляем оценки у дней в которые есть смены без оценок
if (1 == 1) {

    $jobs_all = \Nyos\mod\JobDesc::getListJobsPeriodAll($db, date('Y-m-d', $_SERVER['REQUEST_TIME'] - 3600 * 24 * 45));
// \f\pa($jobs_all, 2, '', 'jobs_all');

    \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
            . ' ON mid.id_item = mi.id '
            . ' AND mid.name = \'start\' '
            . ' AND mid.value_datetime >= :ds '
    ;
    \Nyos\mod\items::$var_ar_for_1sql[':ds'] = date('Y-m-d 05:00:00', $_SERVER['REQUEST_TIME'] - 3600 * 24 * 45);
    // \Nyos\mod\items::$where2dop = ' AND ( midop.name = \'ocenka_auto\' ) ';

    $checks = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_checks);
// \f\pa($checks, 2, '', '$checks');
    // $e2 = [];
    $refresh_estim__sp_date = [];

//$nn = 1;

    foreach ($checks as $k => $v) {

        // echo '<Br/>123';

        if (!empty($v['ocenka_auto']))
            continue;

//        \f\pa($v);
// $nn++;
// 
// if( $nn >= 20 )
//     break;

        $d = date('Y-m-d', strtotime($v['start']));

        if (isset($jobs_all['data']['where_job__workman_date'][$v['jobman']][$d])) {

// \f\pa($jobs_all['data']['where_job__workman_date'][$v['jobman']][$d]);
// \Nyos\mod\JobDesc::

            try {

//                $sql = 'update `mitems` mi
//                inner join `mitems-dops` mid on mi.id = mid.id_item 
//                    and mid.name = \'sale_point\'
//                    and mid.value = :sp
//                inner join `mitems-dops` mid2 on mi.id = mid2.id_item 
//                    and mid2.name = \'date\'
//                    and mid2.value_date = :d
//                set 
//                    mi.status = \'delete\'
//                where 
//                    mi.module = :mod
//                ';
// \f\pa($sql);
//
                $sp_now = $jobs_all['data']['where_job__workman_date'][$v['jobman']][$d]['sale_point'];
//                 \f\pa($sp_now);
                // $e = [':mod' => 'sp_ocenki_job_day', ':sp' => $sp_now, ':d' => $d];
                // $e2[] = ['sp' => $sp_now, 'd' => $d];
                $refresh_estim__sp_date[$sp_now][$d] = 1;
                //\f\pa($e);
                // if ( !isset($ocenki_sp_date[$sp_now][$d]) ) {
//                    \f\pa($e);
//                    $ff = $db->prepare($sql);
//                    $ff->execute($e);
//                    $tt = $ff->fetch();
//                    \f\pa($tt);
                // \f\pa(123);
                // }
            } catch (\Exception $ex) {

//                \f\pa($ex);

                echo '<pre>'
                . PHP_EOL
                . PHP_EOL
                . '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
                . PHP_EOL
                . $ex->getMessage() . ' #' . $ex->getCode()
                . PHP_EOL
                . $ex->getFile() . ' #' . $ex->getLine()
                . PHP_EOL
                . $ex->getTraceAsString()
                . '</pre>';
            }
        }
    }
}

// \f\pa($e2, '', '', 'e2');
// \f\pa($refresh_estim__sp_date, '', '', '$no_estim__sp_date');

$timer = \f\timer_stop(123, 'ar');
echo '<br/>timer ' . __LINE__ . ' sec: ' . round($timer['sec'], 2);

// exit;









$ocenki_sp_date = [];
if (1 == 1) {

    \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
            . ' ON mid.id_item = mi.id '
            . ' AND mid.name = \'date\' '
            . ' AND mid.value_date >= :ds '
    ;
    \Nyos\mod\items::$var_ar_for_1sql[':ds'] = date('Y-m-d', $_SERVER['REQUEST_TIME'] - 3600 * 24 * 40);
    \Nyos\mod\items::$where2dop = ' AND (  midop.name = \'date\' OR midop.name = \'ocenka\' OR midop.name = \'sale_point\' ) ';

    $sps_ocenki = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_ocenki_days);
// \f\pa( $sps_ocenki, 2, '', '$sps_ocenki' );

    foreach ($sps_ocenki as $k => $v) {
        $ocenki_sp_date[$v['sale_point']][$v['date']] = 1;
    }

    unset($sps_ocenki);
}



// exit;


$norms_day__sp_date = [];
if (1 == 1) {

    \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
            . ' ON mid.id_item = mi.id '
            . ' AND mid.name = \'date\' '
            . ' AND mid.value_date >= :ds '
    ;
    \Nyos\mod\items::$var_ar_for_1sql[':ds'] = date('Y-m-d', $_SERVER['REQUEST_TIME'] - 3600 * 24 * 40);

    \Nyos\mod\items::$where2dop = ' AND ( midop.name = \'date\' OR midop.name = \'sale_point\' ) ';

    $ar = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_norms_day);
// \f\pa($sps_norms, 2, '', '$sps_norms' );
    
    foreach ($ar as $k => $v) {
        $norms_day__sp_date[$v['sale_point']][$v['date']] = 1;
    }

    unset($ar);
// \f\pa($norms_day__sp_date, 2, '', '$norms_day__sp_date');

}


// $shifts_without_estim = [];
// $shifts_estim = [];
//$shifts = [];
//if (1 == 1) {
//    \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
//            . ' ON mid.id_item = mi.id '
//            . ' AND mid.name = \'date\' '
//            . ' AND mid.value_date >= :ds '
//    ;
//    \Nyos\mod\items::$var_ar_for_1sql[':ds'] = date('Y-m-d', $_SERVER['REQUEST_TIME'] - 3600 * 24 * 40);
//    \Nyos\mod\items::$where2dop = ' AND ( midop.name = \'date\' OR midop.name = \'sale_point\' ) ';
//    $ar = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_checks );
//    // \f\pa($sps_norms, 2, '', '$sps_norms' );
//    foreach ($ar as $k => $v) {
//        $shifts[$v['sale_point']][$v['date']] = 1;
//    }
//    unset($ar);
//    \f\pa($shifts, 2, '', '$shifts');
//}
//$jobs_all = \Nyos\mod\JobDesc::getListJobsPeriodAll($db, date('Y-m-d', $_SERVER['REQUEST_TIME'] - 3600 * 24 * 45));
//// $jobs_all['data']['where_job__workman_date'] = [];
//\f\pa($jobs_all, 2,'','jobs_all');
//\f\pa($jobs_all['data']['where_job__workman_date'], 12,'','jobs_all');
//    $jobs_all = \Nyos\mod\JobDesc::getListJobsPeriodAll($db, date('Y-m-d', $_SERVER['REQUEST_TIME'] - 3600 * 24 * 40) );
//    \f\pa($jobs_all, 12,'','jobs_all');








foreach ($sps as $k => $v) {

    if ($v['head'] == 'default')
        continue;

    for ($i = 1; $i <= 39; $i++) {

        $date = date('Y-m-d', $_SERVER['REQUEST_TIME'] - 3600 * 24 * $i);

        echo '<br/>' . $v['head'] . ' ' . $date . ' / оц ' . ( isset($ocenki_sp_date[$v['id']][$date]) ? 'есть' : 'нет' )
        . ' / нормы '
        . ( isset($norms_day__sp_date[$v['id']][$date]) ? 'есть' : 'нет' )
        ;

        $need_estimation = false;

// если есть норма дня то изучаем остальные параметры и если нужно считаем оценку
        if (isset($norms_day__sp_date[$v['id']][$date])) {

            if (!empty($refresh_estim__sp_date[$v['id']][$date]))
                $need_estimation = true;

            if (!isset($ocenki_sp_date[$v['id']][$date])) {
                $need_estimation = true;
            }
        }

        echo '<br/>' . $v['head'] . ' ' . $date . ' оценка ' . ( $need_estimation === true ? 'нужна++' : 'не нужна --' )
        . ( (!empty($refresh_estim__sp_date[$v['id']][$date])) ? ' обновить' : '' )
        ;

        if ($need_estimation === true) {

//            https://adomik.dev.uralweb.info/vendor/didrive_mod/jobdesc/1/didrive/ajax.php
//            t=1&action=calc_full_ocenka_day&id=1_0&id2=1&s=a82539c2c6b3da5997cda9ad9665b70b&s2=e4fd78c868945c9c4fedcd13d67d3703&show_timer=da&sp=1&date=2020-02-01



            $u = [
//                'action' => 'bonus_record_month',
//                'date' => date('Y-m-d', $_SERVER['REQUEST_TIME']),
//                'sp' => $v['id']
//                'id' => '1_0',
//                'id2' => 1,
//                's' => md5(),
//                's2' => md5(),
//                'show_timer' => 'da',

                'action' => 'calc_full_ocenka_day',
                'sp' => $v['id'],
                'date' => $date,
                'sp_s' => \Nyos\Nyos::creatSecret($v['id'] . $date),
            ];
            
            // \f\pa($u,'','','$u');
            
            $link = 'http://' . $_SERVER['HTTP_HOST'] . '/vendor/didrive_mod/jobdesc/1/didrive/ajax.php?' . http_build_query($u);

//инициализация сеанса
            if ($curl = curl_init()) {
// $curl
// curl_setopt($curl, CURLOPT_URL, 'http://webcodius.ru/'); //указываем адрес страницы
//указываем адрес страницы
                curl_setopt($curl, CURLOPT_URL, $link);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt ($curl, CURLOPT_POST, true);
// curl_setopt ($curl, CURLOPT_POSTFIELDS, "i=1");
                curl_setopt($curl, CURLOPT_HEADER, 0);
                $result = curl_exec($curl); //выполнение запроса
//                \f\pa($result, '', '', 'result');
                
//                \f\pa( json_decode($result,true) );
                
                curl_close($curl); //закрытие сеанса
            }

//                \f\pa($result, '', '', 'result');
//                \f\pa( json_decode($result,true),'','','$result2' );
            $ar = json_decode($result, true);

            if (isset($ar['data']['ocenka']))
                $msg .= PHP_EOL . $v['head'] . ' ' . $date . ' > ' . ( $ar['data']['ocenka'] ?? '-' );

            if (isset($ar['html']) && isset($ar['status']) && $ar['status'] == 'error' )
                $msg .= PHP_EOL . ( $ar['html'] ?? '-' );

//            $timer = \f\timer_stop(123, 'ar');
//            \f\pa($timer,'','','timer');
//            echo '<br/>timer' . \f\timer_stop(123);
//            die( '#'.__LINE__ );
        }

// echo '<br/>timer' . \f\timer_stop(123);

        $timer = \f\timer_stop(123, 'ar');
        echo '<br/>timer sec: ' . round($timer['sec'], 2);

        if ($timer['sec'] > $stop_job_sec)
            break;
    }

    $timer = \f\timer_stop(123, 'ar');
    echo '<br/>timer sec: ' . round($timer['sec'], 2);
    if ($timer['sec'] > $stop_job_sec)
        break;
}









if (1 == 1 && !empty($msg) && class_exists('\Nyos\Msg')) {
    \Nyos\Msg::sendTelegramm($msg, null, 2);
}



exit;












$nn = 1;

foreach ($sps as $k => $v) {

    if ($v['head'] == 'default')
        continue;

//echo '<Br/>точка ' . $v['head'];

    $temp_var = 'setup24-ocenka-sp-' . $v['id'];
    $ee = \f\Cash::getVar($temp_var);
// \f\pa($ee,'','','var');

    if (!empty($ee)) {
        $txt2 .= $v['head'] . ' ';
        continue;
    }

    $timer = \f\timer_stop(5, 'ar');
// \f\pa($timer);

    if (isset($timer['sec']) && $timer['sec'] > 20)
        break;

//    if ($nn > 2)
//        break;

    $nn++;

    $txt0 .= $v['head'] . ' ';

    $u = [
        'action' => 'calc_mont_sp',
        'date' => date('Y-m-d', $_SERVER['REQUEST_TIME'] - 3600 * 24),
        'sp' => $v['id'],
        'return' => 'html-small'
    ];
//    $link = 'http://' . $_SERVER['HTTP_HOST'] . '/vendor/didrive_mod/jobdesc/1/didrive/ajax.php?' . http_build_query($u);
    $link = 'http://' . $_SERVER['HTTP_HOST'] . '/vendor/didrive_mod/jobdesc/1/ajax.php?' . http_build_query($u);
// action=calc_mont_sp&sp=3051&2return=html-small';

    if ($curl = curl_init()) { //инициализация сеанса
// $curl
// curl_setopt($curl, CURLOPT_URL, 'http://webcodius.ru/'); //указываем адрес страницы
//указываем адрес страницы
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt ($curl, CURLOPT_POST, true);
// curl_setopt ($curl, CURLOPT_POSTFIELDS, "i=1");
        curl_setopt($curl, CURLOPT_HEADER, 0);
        $result = curl_exec($curl); //выполнение запроса
// \f\pa($result, '', '', 'result');
        curl_close($curl); //закрытие сеанса

        \f\Cash::setVar($temp_var, 1, ( $time_expire ?? 60 * 60 * 5));
    }
}


if (!empty($txt0)) {
    $txt .= PHP_EOL . 'обработано сейчас: ' . $txt0;
} else {
    $txt .= PHP_EOL . 'обработано сейчас ничего, видимо всё уже обработано';
}

if (!empty($txt2))
    $txt .= PHP_EOL . 'загружено ранее: ' . $txt2;

$txt .= PHP_EOL . 'таймер: ' . \f\timer_stop(5, 'str');

\nyos\Msg::sendTelegramm($txt, null, 2);

die('<pre>' . $txt);

//exit;
//
//
//
//$in = [];
//
//$w = 0;
//
//foreach ($checks as $k => $v) {
//
//    $w++;
//
//    if ($w >= 10)
//        break;
//
//    \f\pa($v);
//
//    $v['new_hour'] = \Nyos\mod\IikoChecks::calcHoursInSmena(date('Y-m-d H:i', strtotime($v['start'])), date('Y-m-d H:i', strtotime($v['fin'])));
//    \f\pa($v);
//
//    $in[$v['id']] = ['hour_on_job' => $v['new_hour']];
//}
//
//$res = \Nyos\mod\items::saveNewDop($db, $in);
//\f\pa($res);
//
//exit;
