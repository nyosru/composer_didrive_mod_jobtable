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

\f\timer_start(1);


// если нужно не обращать внимания на кеш
if (!empty($_GET['no_load_cash']))
    \Nyos\api\Iiko::$cash = false;

// getConfigDbIiko

\Nyos\api\Iiko::getConfigDbIiko();
$e = \Nyos\api\Iiko::loadIikoPeople();

// die('<br/>#'.__LINE__);
\f\pa($e, 2, '', ' результат загрузки');
// die('<br/>#'.__LINE__);
//                // если кеш есть
//                if (isset($e['file_cash_est']) && $e['file_cash_est'] == 'da') {
//                    echo '<br/>#' . __LINE__;
//                }
//                // если кеша нет, то записываем
//                else {
//                    echo '<br/>#' . __LINE__;
//                }

if (!empty($e['data']))
    $e2 = \Nyos\api\Iiko::saveIikoPeople($db, $e['data']);

// \f\pa($e2, 2, '', ' результат выполнения загрузки и проверки данных');

$msg2 = ( (!empty($e['error_txt'])) ? 'Обнаружена ошибка:' . $e['error_txt'] . PHP_EOL : '' ) . 'Добавлено пользователей: ' . ( $e2['new_items'] ?? 0 )
        . PHP_EOL . 'Обновлено параметров: ' . ( $e2['new_dops_kolvo'] ?? 0 );

//die('<br/>#' . __LINE__);
//
//
//
//$msg2 = '';
//
//if ((!empty($e2['kolvo_new']) && is_numeric($e2['kolvo_new']) && $e2['kolvo_new'] > 0 ) || (!empty($e2['kolvo_edit_dop']) && is_numeric($e2['kolvo_edit_dop']) && $e2['kolvo_edit_dop'] > 0 )) {
//
//    $msg2 .= 'удаляем кеш данных так как были изменения в локальной базе даных' . PHP_EOL;
//}

$msg2 .= PHP_EOL . 'комп время: ' . \f\timer_stop(1) . PHP_EOL;

try {

    if (class_exists('\\Nyos\\Msg')) {

        // echo 'Послали отчёт об операции в телеграм';
        // $msg = 'Загрузили данные с айки по пользователям ( ' . sizeof($re) . ' записей )';
        $msg = 'Загрузили данные с айки по пользователям'
//                . PHP_EOL . 'записей ' . ( $e2['kolvo_in'] ?? '-' )
//                . PHP_EOL . 'новых сотрудников  ' . ( $e2['kolvo_new'] ?? '-' )
//                . PHP_EOL . 'изменённых доп параметров ' . ( $e2['kolvo_edit_dop'] ?? '-' )
                . PHP_EOL . ( $msg2 ?? '' )
        ;

        \Nyos\Msg::sendTelegramm($msg, null, 2);

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
