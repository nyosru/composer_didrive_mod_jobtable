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

    require_once '0start.php';

    if (isset($_REQUEST['show_info'])) 
    \f\pa($_REQUEST,'','','request');
    // echo '<h3>тащим всех работников в этом месяце</h3>';

    $ee = \Nyos\mod\JobDesc::getActionsJobmansOnMonth( $db, $_REQUEST['jobmans'] , $_REQUEST['date'] );
    // $ee = [];

    if (isset($_REQUEST['show_info'])){
//    \f\pa($ee );
    exit;
    }

    \f\end2( 'ok', true, $ee );
    
} catch (Exception $exc) {

    if (isset($_REQUEST['show_info'])){
    echo '<pre>'; print_r($exc); echo '</pre>';
    }
    
    \f\end2( 'error', false, $exc );
    
}