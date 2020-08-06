<?php



try {

    

    if (isset($skip_start) && $skip_start === true) {
        
    } else {
        require_once '0start.php';
        $skip_start = false;
    }    
    
    
    \f\pa(\Nyos\nyos::$menu);
    
    
    \f\timer_start(233);
    // die('123');
    $list = [];
    foreach (\Nyos\nyos::$menu as $k => $v) {
        if (isset($v['type']) && $v['type'] == 'items') {

            if (isset($_REQUEST['only'])) {

                if ($_REQUEST['only'] == $k) {
                    $list[] = $k;
                    $e = \f\db\db_creat_local_table($db, $k, null, true);
                }
            } else {
                $list[] = $k;
                // echo '<br/>' . $k;
                // \f\pa($v);
                $e = \f\db\db_creat_local_table($db, $k, null, true);
                // \f\pa($e);
            }
        }
    }

    // $timer = \f\timer_stop(233);

    $skip_start = true;
    require_once DR . '/vendor/didrive_mod/jobdesc/1/didrive/micro-service/creat-db-summ-table.php';

    $timer = \f\timer_stop(233);

    if (isset($_REQUEST['show_res']) && $_REQUEST['show_res'] == 'no') {
        die();
    } else {
        \f\end2('ok', true, [$list, $timer]);
    }
} catch (\Exception $exc) {

    echo 'ошибка <pre>';
    print_r($exc);
    echo '</pre>';
    // echo $exc->getTraceAsString();
}