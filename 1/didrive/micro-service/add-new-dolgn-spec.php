<?php

if (empty($_REQUEST['date']))
    throw new \Exception('нет даты');

if (empty($_REQUEST['user']))
    throw new \Exception('нет пользователя');

if (!isset($_REQUEST['dolgn']))
    throw new \Exception('нет должности');

if (isset($skip_start) && $skip_start === true) {
    
} else {
    require_once '0start.php';
    $skip_start = false;
}

try {

    $indb = array(
        'head' => 1,
        'jobman' => $_REQUEST['user'],
        'sale_point' => $_POST['sp'] ?? $_REQUEST['sp'],
        'dolgnost' => $_REQUEST['dolgn'],
        'date' => $_REQUEST['date'],
    );

    \f\db\db_edit2($db, 'mod_' . \f\translit(\Nyos\mod\JobDesc::$mod_spec_jobday, 'uri2'), [
        'jobman' => $indb['jobman'],
        'sale_point' => $indb['sale_point'],
        'date' => $indb['date']
            ], ['status' => 'delete']);

    \Nyos\mod\items::$type_module = 3;
    \Nyos\mod\items::add($db, \Nyos\mod\JobDesc::$mod_spec_jobday, $indb);

    \f\end2('добавлено');
    die();
} catch (Exception $exc) {

//    echo '<pre>';
//    print_r($exc);
//    echo '</pre>';

    \nyos\Msg::sendTelegramm(__FILE__ . ' #' . __LINE__ . PHP_EOL . $exc->getMessage(), null, 2);
}