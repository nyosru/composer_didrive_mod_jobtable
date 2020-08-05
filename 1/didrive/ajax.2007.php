<?php

if (isset($ajax2) && $ajax2 === true) {
    
} else {
    \f\end2('что то пошло не так #' . __LINE__, false);
}


//
if ( 1 == 2 && isset($_POST['action']) && $_POST['action'] == 'add_new_minus') {
// action=add_new_smena

    try {

//        require_once DR . '/all/ajax.start.php';
//
//        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'))
//            require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
//
//        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php'))
//            require ($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php');

        \Nyos\mod\items::addNew($db, $vv['folder'], \Nyos\nyos::$menu['072.vzuscaniya'], array(
// 'head' => rand(100, 100000),
            'date_now' => date('Y-m-d', strtotime($_REQUEST['date'])),
            'jobman' => $_REQUEST['jobman'],
            'sale_point' => $_REQUEST['salepoint'],
            'summa' => $_REQUEST['summa'],
            'text' => $_REQUEST['text']
        ));


//        if (date('Y-m-d', $start_time) == date('Y-m-d', $fin_time)) {
//            $dd = true;
//        } else {
//            $dd = false;
//        }
//        $r = ob_get_contents();
//        ob_end_clean();


        \f\end2('<div>'
                . '<nobr><b class="warn" >взыскание добавлено</b>'
                . '<br/>'
                . $_REQUEST['summa']
                . '<br/>'
                . '<small>' . $_REQUEST['text'] . '</small>'
//                . (
//                $dd === true ?
//                        '<br/>с ' . date('H:i', $start_time) . ' - ' . date('H:i', $fin_time) : '<br/>с ' . date('Y-m-d H:i:s', $start_time) . '<br/>по ' . date('Y-m-d H:i:s', $fin_time)
//                )
// .'окей '.$b
//                . '</br>'
//                . $b
//                . '</br>'
//                . $r
                . '</nobr>'
                . '</div>', true);
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
}
//
elseif ( 2 == 3 && isset($_POST['action']) && $_POST['action'] == 'add_new_plus') {
// action=add_new_smena

    try {

//require_once DR . '/all/ajax.start.php';
//        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'))
//            require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
//
//        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php'))
//            require ($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php');

        \Nyos\mod\items::addNew($db, $vv['folder'], \Nyos\nyos::$menu['072.plus'], array(
// 'head' => rand(100, 100000),
            'date_now' => date('Y-m-d', strtotime($_REQUEST['date'])),
            'jobman' => $_REQUEST['jobman'],
            'sale_point' => $_REQUEST['salepoint'],
            'summa' => $_REQUEST['summa'],
            'text' => $_REQUEST['text']
        ));


//        if (date('Y-m-d', $start_time) == date('Y-m-d', $fin_time)) {
//            $dd = true;
//        } else {
//            $dd = false;
//        }
//        $r = ob_get_contents();
//        ob_end_clean();


        \f\end2('<div>'
                . '<nobr><b class="warn" >премия добавлена'
                . '<br/>'
                . $_REQUEST['summa']
                . '<br/>'
                . '<small>' . $_REQUEST['text'] . '</small>'
                . '</b>'
//                . (
//                $dd === true ?
//                        '<br/>с ' . date('H:i', $start_time) . ' - ' . date('H:i', $fin_time) : '<br/>с ' . date('Y-m-d H:i:s', $start_time) . '<br/>по ' . date('Y-m-d H:i:s', $fin_time)
//                )
// .'окей '.$b
//                . '</br>'
//                . $b
//                . '</br>'
//                . $r
                . '</nobr>'
                . '</div>', true);
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
}


// добавляем минус к дню работника
if ($_POST['action'] == 'add_new_minus') {

//    \f\pa($_REQUEST);
//    die();
    
// удаляем запись кеша главного массива данных
    if (!empty($_REQUEST['delete_cash_start_date']))
        $e = \f\Cash::deleteKeyPoFilter(['all', 'jobdesc', 'date' . $_REQUEST['delete_cash_start_date']]);

    // \f\pa($e);
    // $e = \Nyos\mod\items::addNewSimple($db, '073.comments', $_REQUEST);

    $re = $_REQUEST;
    $re['date_now'] = $_REQUEST['date'];
    $re['sale_point'] = $_REQUEST['salepoint'];
    
    \Nyos\mod\items::$type_module = 2;
    $e = \Nyos\mod\items::add($db, \Nyos\mod\JobDesc::$mod_minus, $re);

    \f\end2('<div class="warn" style="padding:5px;" >'
        . '<div style="padding:5px; margin-bottom: 5px; background-color: rgba(0,0,0,0.1);" >добавили взыскание</div>'
        //. '<br/>'
        . ( $_REQUEST['comment'] ?? '' )
        . '</div>', true);

}
// добавляем комментарий к дню работника
if ($_POST['action'] == 'add_comment') {

// удаляем запись кеша главного массива данных
    if (!empty($_REQUEST['delete_cash_start_date']))
        $e = \f\Cash::deleteKeyPoFilter(['all', 'jobdesc', 'date' . $_REQUEST['delete_cash_start_date']]);
// \f\pa($e);
    // $e = \Nyos\mod\items::addNewSimple($db, '073.comments', $_REQUEST);
    \Nyos\mod\items::$type_module = 2;
    $e = \Nyos\mod\items::add($db, '073.comments', $_REQUEST);

    \f\end2('<div class="warn" style="padding:5px;" >'
            . '<div style="padding:5px; margin-bottom: 5px; background-color: rgba(0,0,0,0.1);" >добавили комментарий</div>'
//. '<br/>'
            . $_REQUEST['comment']
            . '</div>', true);
}
//
elseif (!empty($_REQUEST['action']) && $_REQUEST['action'] == 'delete_comment') {

    if (!empty($_REQUEST['remove'])) {
        $e = \f\Cash::deleteKeyPoFilter([\Nyos\mod\jobdesc::$mod_comments]);
        \Nyos\mod\items::deleteFromDops($db, \Nyos\mod\jobdesc::$mod_comments, $_REQUEST['remove']);
        \Nyos\mod\items::deleteItemForDops($db, \Nyos\mod\jobdesc::$mod_comments, $_REQUEST['remove']);
        \f\end2('коментарии дня удалены');
    } else {
        // \f\pa($_REQUEST);
        \f\end2('что то пошло не так, повторите и обратитесь к тех. поддержке');
    }
}
