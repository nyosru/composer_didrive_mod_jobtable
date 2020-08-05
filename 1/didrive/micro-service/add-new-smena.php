<?php

try {

    $date = $in['date'] ?? $_REQUEST['date'] ?? null;

    if (empty($date))
        throw new \Exception('нет даты');

    if (isset($skip_start) && $skip_start === true) {
        
    } else {
        require_once '0start.php';
        $skip_start = false;
    }

    // если нет старта и финиша значит назначение сотрудника новое
    if (empty($_REQUEST['start_time']) && empty($_REQUEST['fin_time'])) {

        if (isset($_REQUEST['type2']) && $_REQUEST['type2'] == 'spec_naznach') {

            $skip_start = true;
            require_once './add-new-dolgn-spec.php';
            die();
            
        } else {

            $skip_start = true;
            require_once './add-new-dolgn.php';
            die();
        }
    }





// если старт часов меньше часов сдачи
    if (strtotime($_REQUEST['start_time']) > strtotime($_REQUEST['fin_time'])) {
        $start_time = strtotime($_REQUEST['date'] . ' ' . $_REQUEST['start_time']);
        $fin_time = strtotime($_REQUEST['date'] . ' ' . $_REQUEST['fin_time']) + 3600 * 24;
    }
// если старт часов больше часов сдачи
    else {
        $start_time = strtotime($_REQUEST['date'] . ' ' . $_REQUEST['start_time']);
        $fin_time = strtotime($_REQUEST['date'] . ' ' . $_REQUEST['fin_time']);
    }

    $indb = array(
        'head' => 1,
        'jobman' => $_REQUEST['jobman'],
        'sale_point' => $_REQUEST['salepoint'],
        'start' => date('Y-m-d H:i', $start_time),
        'fin' => date('Y-m-d H:i', $fin_time),
        // 'hour_on_job' => \Nyos\mod\IikoChecks::calculateHoursInRange( date('Y-m-d H:i', $start_time), date('Y-m-d H:i', $fin_time)),
        'hour_on_job' => \Nyos\mod\IikoChecks::calcHoursInSmena(date('Y-m-d H:i', $start_time), date('Y-m-d H:i', $fin_time)),
        // 'hour_on_job' => \Nyos\mod\IikoChecks::calculateHoursInRangeUnix($start_time, $fin_time),
        'who_add_item' => 'admin',
        'who_add_item_id' => $_SESSION['now_user_di']['id'] ?? '',
        'ocenka' => $_REQUEST['ocenka']
    );

    //\f\pa($indb);
    \Nyos\mod\items::$type_module = 2;
    \Nyos\mod\items::add($db, '050.chekin_checkout', $indb);

    $ee = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/i.didrive.php?level=000.job&refresh_db=sd&only=050.chekin_checkout&show_res=no');

    \f\end2('<div class="warn" style="padding:5px;" >'
            . '<nobr><b>смена добавлена</b>'
            . '<br/>с ' . date('d.m.y H:i', $start_time)
            . '<br/>до ' . date('d.m.y H:i', $fin_time)
            . '<br/>часов на работе ' . $indb['hour_on_job']
            . '<hr>' . $ee . '<hr>'
            . '</nobr>'
            . '</div>', true);
} catch (Exception $exc) {

    echo '<pre>';
    print_r($exc);
    echo '</pre>';
    // echo $exc->getTraceAsString();
}