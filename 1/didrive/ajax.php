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
    
    foreach( $_REQUEST as $k => $v ){
        $e .= '<Br/>'.$k .' - '.$v;
    }
    
    f\end2('Произошла неописуемая ситуация #' . __LINE__ . ' обратитесь к администратору '.$e // . $_REQUEST['id'] . ' && ' . $_REQUEST['secret']
            , 'error');
    
    
}

//require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/sql.start.php');
//require( $_SERVER['DOCUMENT_ROOT'] . '/0.site/0.cfg.start.php');
//require( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'class' . DS . 'mysql.php' );
//require( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'db.connector.php' );
// добавляем смену сотруднику
if (isset($_POST['action']) && $_POST['action'] == 'add_new_smena') {
    // action=add_new_smena

    try {

        require_once DR . '/all/ajax.start.php';

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'))
            require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php'))
            require ($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php');

//$start_date = $_REQUEST['']        
//$start_date = $_REQUEST['']        
        
        //$e = \Nyos\mod\items::addNew($db, $e, $cfg_mod, $data);
        
        $b = '';
        
        foreach( $_REQUEST as $k => $v ){
        $b .= '<br/>'.$k.' - '.$v;    
        }
        
//        strtotime( $_REQUEST['start_time'] )
//        strtotime( $_REQUEST['fin_time'] )
        
        if( strtotime( $_REQUEST['start_time'] ) > strtotime( $_REQUEST['fin_time'] ) ){
            //$b .= '<br/>'.__LINE__;
            $start_time = strtotime( $_REQUEST['date'].' '.$_REQUEST['start_time'] );
            $fin_time = strtotime( $_REQUEST['date'].' '.$_REQUEST['fin_time'] )+3600*24;
        }else{
            //$b .= '<br/>'.__LINE__;
            $start_time = strtotime( $_REQUEST['date'].' '.$_REQUEST['start_time'] );
            $fin_time = strtotime( $_REQUEST['date'].' '.$_REQUEST['fin_time'] );
        }

        // $mnu = \Nyos\Nyos::getMenu($vv['folder']);
        // $mnu = \Nyos\Nyos::getMenu();
//        $mnu = \Nyos\nyos::$menu;
//        print_r($mnu);
        
        \Nyos\mod\items::addNew($db, $vv['folder'], \Nyos\nyos::$menu['050.chekin_checkout'], array(
            'jobman' => $_REQUEST['jobman'],
            'sale_point' => $_REQUEST['salepoint'],
            'start' => $start_time,
            'fin' => $fin_time
            ));

        
        if( date( 'Y-m-d', $start_time) == date( 'Y-m-d', $fin_time) ){
            $dd = true;
        }else{
            $dd = false;
        }
        
        \f\end2('<div>'
                . '<nobr>смена'
                .( 
                    $dd === true ? 
                        '<br/>с '.date( 'H:i', $start_time) .' - '.date( 'H:i', $fin_time) 
                        : '<br/>с '.date( 'Y-m-d H:i:s', $start_time) .'<br/>по '.date( 'Y-m-d H:i:s', $fin_time) 
                )
                // .'окей '.$b
                .'</br>'
                .$b
                .'</nobr>'
                .'</div>', true);
        
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
} elseif (isset($_POST['action']) && $_POST['action'] == 'show_info_strings') {

    require_once DR . '/all/ajax.start.php';

    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'))
        require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/all/exception.nyosex'))
        require $_SERVER['DOCUMENT_ROOT'] . '/all/exception.nyosex';

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
