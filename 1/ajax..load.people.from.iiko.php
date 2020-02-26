<?php

ini_set('display_errors', 'On'); // сообщения с ошибками будут показываться
error_reporting(E_ALL); // E_ALL - отображаем ВСЕ ошибки


//if ($_SERVER['HTTP_HOST'] == 'photo.uralweb.info' || $_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info' || $_SERVER['HTTP_HOST'] == 'a2.uralweb.info' || $_SERVER['HTTP_HOST'] == 'adomik.uralweb.info' || $_SERVER['HTTP_HOST'] == 'adomik.dev.uralweb.info'
//) {
//    date_default_timezone_set("Asia/Omsk");
//} else {
//    date_default_timezone_set("Asia/Yekaterinburg");
//}

define('IN_NYOS_PROJECT', true);

// die('<br/>#'.__LINE__.' '.__FILE__);

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require $_SERVER['DOCUMENT_ROOT'] . '/all/ajax.start.php';

\f\timer_start(1);

// если нужно не обращать внимания на кеш
if (!empty($_GET['no_load_cash']))
    \Nyos\api\Iiko::$cash = false;

$e = \Nyos\api\Iiko::loadIikoPeople();
// \f\pa($e, 2, '', ' результат загрузки');
// \f\pa($e['data'], 2, '', ' результат загрузки');

//die('<br/>#' . __LINE__ . ' ' . __FILE__);

//die('<br/>#' . __LINE__ . ' ' . __FILE__);

//                // если кеш есть
//                if (isset($e['file_cash_est']) && $e['file_cash_est'] == 'da') {
//                    echo '<br/>#' . __LINE__;
//                }
//                // если кеша нет, то записываем
//                else {
//                    echo '<br/>#' . __LINE__;
//                }

$e2 = \Nyos\api\Iiko::saveIikoPeople($db, $e['data']);
// \f\pa($e2, 2, '', ' результат выполнения загрузки и проверки данных');

// die('<br/>#' . __LINE__ . ' ' . __FILE__);

$msg2 = '';

if ((!empty($e2['kolvo_new']) && is_numeric($e2['kolvo_new']) && $e2['kolvo_new'] > 0 ) || (!empty($e2['kolvo_edit_dop']) && is_numeric($e2['kolvo_edit_dop']) && $e2['kolvo_edit_dop'] > 0 )) {

    $msg2 .= 'удаляем кеш данных так как были изменения в локальной базе даных' . PHP_EOL;
}

$msg2 .= \f\timer_stop(1) . PHP_EOL;


try {

    if (class_exists('\\Nyos\\Msg')) {

        // echo 'Послали отчёт об операции в телеграм';
        // $msg = 'Загрузили данные с айки по пользователям ( ' . sizeof($re) . ' записей )';
        $msg = 'Загрузили данные с айки по пользователям'
                . PHP_EOL . 'записей ' . ( $e2['kolvo_in'] ?? '-' )
                . PHP_EOL . 'новых сотрудников  ' . ( $e2['kolvo_new'] ?? '-' )
                . PHP_EOL . 'изменённых доп параметров ' . ( $e2['kolvo_edit_dop'] ?? '-' )
                . PHP_EOL . ( $msg2 ?? '' )
        ;

        \Nyos\Msg::sendTelegramm($msg, null, 1);

        if (file_exists(DR . DS . 'sites' . DS . \Nyos\Nyos::$folder_now . DS . 'config.php'))
            require_once DR . DS . 'sites' . DS . \Nyos\Nyos::$folder_now . DS . 'config.php';

        if (!empty($vv['info_send_telegram']['admin_ajax_job'])) {
            foreach ($vv['admin_auerific'] as $k => $v) {
                \Nyos\Msg::sendTelegramm($msg, $v);
            }
        }

        echo '<br/>Послали отчёт об операции в телеграм';
    } else {

        echo '<br/>НЕ Послали отчёт об операции в телеграм';
    }
} catch (\Exception $ex) {

    $text = '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
            . PHP_EOL . $ex->getMessage() . ' #' . $ex->getCode()
            . PHP_EOL . $ex->getFile() . ' #' . $ex->getLine()
            . PHP_EOL . $ex->getTraceAsString()
            . '</pre>';
}

die('<br/>' . __FILE__ . ' ' . __LINE__);
