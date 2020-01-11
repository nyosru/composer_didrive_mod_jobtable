<?php

//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);

ini_set('display_errors', 'On'); // сообщения с ошибками будут показываться
//error_reporting(E_ALL); // E_ALL - отображаем ВСЕ ошибки
error_reporting(-1); // E_ALL - отображаем ВСЕ ошибки

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


\Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
        . ' ON mid.id_item = mi.id '
        . ' AND mid.name = \'hour_on_job\' '
        . ' AND mid.status = \'show\' '
        . ' AND mid.value < 0 '
;

$checks = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_checks);

// \f\pa($checks);

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
