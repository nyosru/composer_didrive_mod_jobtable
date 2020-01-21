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

//---------------------------//

// защита от повторного срабатывания в секундах
$time_expire = 60 * 60 * 11;

\f\timer_start(5);
$txt = 'обновляем бонусы к ЗП: ';
$txt2 = '';

// ------------------------- //


$sps = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_sale_point);
// \f\pa($sps);

$nn = 1;

foreach ($sps as $k => $v) {

    if ($v['head'] == 'default')
        continue;

    //echo '<Br/>точка ' . $v['head'];

    $temp_var = 'setup24-bonus-sp-' . $v['id'];
    $ee = \f\Cash::getVar($temp_var);
    // \f\pa($ee,'','','var');

    if (!empty($ee)) {
        $txt2 .= $v['head'] .' ';
        continue;
    }

    $timer = \f\timer_stop(5, 'ar');
    // \f\pa($timer);

    if (isset($timer['sec']) && $timer['sec'] > 20)
        break;

//    if ($nn > 2)
//        break;

    $nn++;

    $txt .= $v['head'] . ' ';

    $u = [
        'action' => 'bonus_record_month',
        'date' => date('Y-m-d', $_SERVER['REQUEST_TIME']),
        'sp' => $v['id']
    ];
    $link = 'http://' . $_SERVER['HTTP_HOST'] . '/vendor/didrive_mod/jobdesc/1/didrive/ajax.php?' . http_build_query($u);

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

        \f\Cash::setVar($temp_var, 1, ( $time_expire ?? 60 * 60 * 5 ) );
    }
}


if( !empty($txt2) )
$txt .= PHP_EOL.'загружено ранее: '.$txt2;

$txt .= PHP_EOL . 'таймер: '. \f\timer_stop(5, 'str');

\nyos\Msg::sendTelegramm($txt, null, 2 );


//
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
