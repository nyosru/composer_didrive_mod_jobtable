<?php

try {

//    $date = $in['date'] ?? $_REQUEST['date'] ?? null;
//
//    if (empty($date))
//        throw new \Exception('нет даты');
    // $sp = $_REQUEST['sp'] ?? $in['sp'] ?? null;
//    $sp1 = ceil( $_REQUEST['sp'] );
//
//    if (empty($sp1))
//        throw new \Exception('нет точки продаж');

    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive/base/start-for-microservice.php';

    if (empty($_REQUEST['date']))
        \f\end2('нет даты', false);

    \Nyos\mod\items::$sql_select_vars = ['jobman', 'uniqorderid', 'dishdiscountsumint', 'opendate_typed d'];
    \Nyos\mod\items::$between_date['opendate_typed'] = [date('Y-m-01', strtotime($_REQUEST['date'])), date('Y-m-y', strtotime(date('Y-m-01', strtotime($_REQUEST['date'])) . ' +1 month -1 day'))];
    \Nyos\mod\items::$search['jobman'] = explode(',',$_REQUEST['jobmans']);
    $in_db = \Nyos\mod\items::get($db, 'delivery');

    \f\end2('ok', true, [ 'infos' => $in_db ] );
    
} catch (Exception $exc) {

    if (isset($_REQUEST['show_info'])) {
        echo '<pre>';
        print_r($exc);
        echo '</pre>';
    }

    \f\end2('error', false, $exc);
}