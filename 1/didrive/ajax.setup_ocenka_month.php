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


$sps = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_sale_point);
// \f\pa($sps);

$nn = 1;
\f\timer_start(5);
$txt = 'обновляем автооценки дней ТП за текущий месяц';
$txt0 = '';
$txt2 = '';

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

        \f\Cash::setVar($temp_var, 1, 60 * 5);
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

\nyos\Msg::sendTelegramm($txt, null, 1);

die('<pre>' . $txt);

exit;



$in = [];

$w = 0;

foreach ($checks as $k => $v) {

    $w++;

    if ($w >= 10)
        break;

    \f\pa($v);

    $v['new_hour'] = \Nyos\mod\IikoChecks::calcHoursInSmena(date('Y-m-d H:i', strtotime($v['start'])), date('Y-m-d H:i', strtotime($v['fin'])));
    \f\pa($v);

    $in[$v['id']] = ['hour_on_job' => $v['new_hour']];
}

$res = \Nyos\mod\items::saveNewDop($db, $in);
\f\pa($res);

exit;
