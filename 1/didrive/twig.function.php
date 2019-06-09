<?php

/**
  определение функций для TWIG
 */
$function = new Twig_SimpleFunction('get_smena_jobs', function ( string $date_start, string $date_finish, array $get_points = [] ) {

    global $db;

    // \f\pa( \Nyos\nyos::$folder_now );

    /**
     * точки продаж
     */
    $points = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, 'sale_point', 'show', null);
    // \f\pa($sp0,2);

    /**
     * работники
     */
    $jobmans = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, '070.jobman', 'show', null);
    // \f\pa($jobman,2);

    /**
     * спец назначения
     */
    $job_in = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, '050.job_in_sp', 'show', null);
    //\f\pa($job_in, 2);

    /**
     * Вход выход на смену
     */
    $checks = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, '050.chekin_checkout', 'show', null);
    //\f\pa($checks, 2, null, '$checks');

    /**
     * оплата
     */
    //$oplats = \Nyos\mod\items::getItems( $db, \Nyos\nyos::$folder_now, '071.set_oplata', 'show', null );
    //\f\pa($oplata,2);
//    // $e = \Nyos\mod\items::getItems( $db, \Nyos\nyos::$folder_now, '072.vzuscaniya', 'show', null );
//    
//    $vz = \Nyos\mod\items::getItems( $db, \Nyos\nyos::$folder_now, '061.dolgnost', 'show', null );
//    // \f\pa($vz,2);
//    
    // $e = [$jobman,$oplata,$vz];
    // \f\pa($e);

    $ud_start = strtotime($date_start);
    $ud_fin = strtotime($date_finish);

// \f\pa($points);





    /**
     * точка - работник - дата
     */
    $a_job_in = [];
    foreach ($job_in['data'] as $j => $job) {

        // \f\pa($job);
        $now_st = strtotime($job['dop']['date']);

        if ($ud_start <= $now_st && ( $ud_fin + 3600 * 24 ) >= $now_st) {
            $a_job_in[$job['dop']['sale_point']][$job['dop']['jobman']]['naznach'][$job['dop']['date']] = 1;
        }
    }

    //\f\pa($jobmans,2,null,'$jobmans');
    foreach ($jobmans['data'] as $m => $man) {

        if( isset($man['dop']['sale_point']) && isset($man['id']) ){
        $a_job_in[$man['dop']['sale_point']][$man['id']]['default'] = $man['dop'];
        }

//      \f\pa($job);
//        $now_st = strtotime($job['dop']['date']);
//
//        if ($ud_start <= $now_st && ( $ud_fin + 3600 * 24 ) >= $now_st) {
//            $a_job_in[$job['dop']['sale_point']][$job['dop']['jobman']][$job['dop']['date']] = 1;
//        }
    }
    // \f\pa($a_job_in, 2, null, '$a_job_in');
    // \f\pa($a_job_in, 2, null, '$a_job_in');



    return $a_job_in;
});
$twig->addFunction($function);



$function = new Twig_SimpleFunction('get_checki', function ( string $date_start, string $date_finish, array $get_points = [] ) {

    global $db;

    /**
     * Вход выход на смену
     */
    $checks = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, '050.chekin_checkout', 'show', null);
    //\f\pa($checks, 2, null, '$checks');

    $ud_start = strtotime($date_start);
    $ud_fin = strtotime($date_finish);

// \f\pa($points);

    /**
     * работник - дата - время вх и вых
     */
    $vv['checks'] = [];
    foreach ($checks['data'] as $c => $check) {

        $now_st = strtotime($check['dop']['start']);

        if ($ud_start <= $now_st && ( $ud_fin + 3600 * 24 ) >= $now_st) {
            
            $da = date('Y-m-d', strtotime($check['dop']['start']));
            
            $vv['checks'][$da][$check['dop']['jobman']][$check['id']] = array(
                'start' => $check['dop']['start'],
                'fin' => $check['dop']['fin']
            );
            
        }
    }

    // \f\pa($vv['checks'], 2, null, '$vv[\'checks\']');

    return $vv['checks'];
});
$twig->addFunction($function);




if (1 == 2) {
    $function = new Twig_SimpleFunction('get_jobman', function ( $date_start, $date_finish, $point = null ) {

        global $db;

        // \f\pa( \Nyos\nyos::$folder_now );

        $sp0 = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, 'sale_point', 'show', null);
        // \f\pa($sp0,2);
//    echo '<br/>';
//    echo '<br/>';
//    echo '111111';
//    echo '222222';

        $jobman = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, '070.jobman', 'show', null);
        // \f\pa($jobman,2);

        $job_in = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, '050.job_in_sp', 'show', null);
        //\f\pa($job_in, 2);

        $cheki = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, '050.chekin_checkout', 'show', null);
        //\f\pa($cheki,2,null,'$cheki');
//    $oplata = \Nyos\mod\items::getItems( $db, \Nyos\nyos::$folder_now, '071.set_oplata', 'show', null );
//    // \f\pa($oplata,2);
//    
//    // $e = \Nyos\mod\items::getItems( $db, \Nyos\nyos::$folder_now, '072.vzuscaniya', 'show', null );
//    
//    $vz = \Nyos\mod\items::getItems( $db, \Nyos\nyos::$folder_now, '061.dolgnost', 'show', null );
//    // \f\pa($vz,2);
//    
        // $e = [$jobman,$oplata,$vz];
        // \f\pa($e);

        $re = [];

        $ud_start = strtotime($date_start);
        $ud_fin = strtotime($date_finish);

        foreach ($sp0['data'] as $k => $sp) {

            // \f\pa($sp0);

            if ($sp['head'] == 'default')
                continue;

            if (isset($sp['status']) && $sp['status'] !== 'show')
                continue;

//        echo '<hr>';
//        echo $point .' == '. $sp_id.' -- '.$date_start.' ++ '.$date_finish;
//        \f\pa($sp);
            //$re[$sp['id']] = $sp;
            $re[$sp['id']] = [];

            $plan_job = [];

            foreach ($job_in['data'] as $k => $v) {

                /**
                 * проверяем точку продаж
                 */
                if ($v['dop']['sale_point'] != $sp['id'])
                    continue;

                /**
                 *  проверяем дату чтобы была в диапазоне
                 */
                $dd = strtotime($v['dop']['date']);
                if ($dd < $ud_start || $dd > $ud_fin)
                    continue;

//            if (!isset($re[$sp['id']][$v['dop']['date']]))
//                $re[$sp['id']][$v['dop']['date']] = [];
//
//            if (!isset($re[$sp['id']][$v['dop']['date']][$v['dop']['jobman']]))
//                $re[$sp['id']][$v['dop']['date']][$v['dop']['jobman']] = [];
//            

                $re[$sp['id']][$v['dop']['date']][$v['dop']['jobman']] = [];

                foreach ($cheki['data'] as $k2 => $v2) {

                    if ($v2['dop']['jobman'] == $v['dop']['jobman'] && strtotime(date('Y-m-d', strtotime($v2['dop']['start']))) == strtotime($v['dop']['date'])
                    ) {
                        $v2['dop']['hours'] = ( strtotime($v2['dop']['fin']) - strtotime($v2['dop']['start']) ) / 3600;
                        $re[$sp['id']][$v['dop']['date']][$v['dop']['jobman']][$v2['id']] = $v2['dop'];
                    }
                }
            }
        }

        // \f\pa($re, 2);

        return $re;
        //return \Nyos\Nyos::creatSecret($text);
    });
    $twig->addFunction($function);
}