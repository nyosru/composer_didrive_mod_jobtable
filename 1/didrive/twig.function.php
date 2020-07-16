<?php

/**
 * тащим список людей кто в указанный период был на работе в этой точке
 * new version 200624
 */
$function = new Twig_SimpleFunction('jobdesc__getJobsJobMans', function ( $db, string $date_start, $sp_id = null ) {

    return \Nyos\mod\JobDesc::getJobsJobMans($db, $date_start, '', $sp_id);

//    \f\pa('\Nyos\mod\JobDesc::getJobsJobMans');   
//    \f\timer_start(11);
//    $e = \Nyos\mod\JobDesc::getJobsJobMans($db, $date_start, '', $sp_id);
//    echo \f\timer_stop(11);
//    return $e;
});
$twig->addFunction($function);

$function = new Twig_SimpleFunction('jobdesc__getJobsSmens', function ( $db, $jobMans, string $date ) {

    return \Nyos\mod\JobDesc::getChecks($db, $jobMans, $date);
});
$twig->addFunction($function);

$function = new Twig_SimpleFunction('jobdesc__getJobsDops', function ( $db, $jobMans, string $date ) {

//    \f\pa($jobMans);
//    \f\pa($date);


    if (!empty($date)) {
        $ds = date('Y-m-01', strtotime($date));
        \Nyos\mod\items::$between_date['date_now'] = [$ds, date('Y-m-d', strtotime($ds . ' +1 month -1 day'))];
    }
    if (!empty($jobMans)) {
        \Nyos\mod\items::$search['jobman'] = array_keys($jobMans);
    }
    $plus0 = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_bonus);
    // \f\pa($plus0,2,'','$plus0');
    $plus_ar_jm_date_ar = [];
    foreach ($plus0 as $k => $v) {
        $plus_ar_jm_date_ar[$v['jobman']][$v['date_now']][] = $v;
    }

    if (!empty($date)) {
        $ds = date('Y-m-01', strtotime($date));
        \Nyos\mod\items::$between_date['date_now'] = [$ds, date('Y-m-d', strtotime($ds . ' +1 month -1 day'))];
    }
    if (!empty($jobMans)) {
        \Nyos\mod\items::$search['jobman'] = array_keys($jobMans);
    }
    $minus0 = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_minus);
    $minus_ar_jm_date_ar = [];
    foreach ($plus0 as $k => $v) {
        $minus_ar_jm_date_ar[$v['jobman']][$v['date_now']][] = $v;
    }

    return [
        'plus' => $plus_ar_jm_date_ar,
        'minus' => $minus_ar_jm_date_ar
    ];
});
$twig->addFunction($function);




/**
 * тащим список людей кто в указанный период был на работе в этой точке
 */
$function = new Twig_SimpleFunction('jobdesc__getMansOnJobsPeriod', function ( $db, string $date_start, string $date_finish, $sp_id = null ) {

    return \Nyos\mod\JobDesc::getPeriodWhereJobMans($db, $date_start, $date_finish, $sp_id);
});
$twig->addFunction($function);


/**
  определение функций для TWIG
 */
$function = new Twig_SimpleFunction('jobdesc__getDayNaznachJobman', function ( $db, int $jobman_id, string $date ) {

    // название переменной где храним кеш
    $cash_var = 'jobdesc__getDayNaznachJobman_date' . $date . '_jobman' . $jobman_id;
    // время в сек на которое храним кеш
    $cash_time = 60 * 10;

    if (1 == 2 && !empty($cash_var)) {

        $list = \f\Cash::getVar($cash_var);

        if (!empty($list))
            return \f\end3('ок', true, $list);
    }

    \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
            . ' ON mid.id_item = mi.id '
            . ' AND mid.name = \'jobman\' '
            . ' AND mid.value = :j '
            . ' INNER JOIN `mitems-dops` mid2 '
            . ' ON mid2.id_item = mi.id '
            . ' AND mid2.name = \'date\' '
            . ' AND mid2.value_date <= :date '
    ;
    \Nyos\mod\items::$sql_order = ' ORDER BY mid2.value_date DESC ';

    // \Nyos\mod\items::$show_sql = true;

    \Nyos\mod\items::$var_ar_for_1sql[':j'] = $jobman_id;
    \Nyos\mod\items::$var_ar_for_1sql[':date'] = $date;
    \Nyos\mod\items::$limit1 = true;

    $list = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_man_job_on_sp);

//    \Nyos\mod\items::$join_where = '';
//    \Nyos\mod\items::$limit1 = false;
//    \Nyos\mod\items::$var_ar_for_1sql = [];
//    \Nyos\mod\items::$sql_order = '';
    // \f\pa($list['data'], 2, '', '$list');
    // \f\pa($list, 2, '', '$list');
    // \f\sort_ar_date();
//    usort( $list, "\\f\\sort_ar_date" );
//    
//    \f\pa($list, 2, '', '$list');

    if (!empty($cash_var) && !empty($cash_time))
        \f\Cash::setVar($cash_var, $list, $cash_time);

    return \f\end3('ок', true, $list);
});
$twig->addFunction($function);







/**
 * кто работает сейчас на точке продаж
 * 2020-01-10
 */
$function = new Twig_SimpleFunction('jobdesc__whereJobmansNowDate', function ( $db, int $sp, string $date ) {

    $workmans = \Nyos\mod\JobDesc::whereJobmansNowDate($db, $date, $sp);
    // \f\pa($workmans,2,'','$workmans');

    return \f\end3('ок', true, $workmans);
});
$twig->addFunction($function);


$function = new Twig_SimpleFunction('jobdesc__calculateHoursOnJob', function ( $db, int $sp, string $date ) {

//    if ($date == '2020-01-05')
//        \Nyos\mod\JobDesc::$return_dop_info = true;
    // echo $date.'<Br/>';

    $workmans = \Nyos\mod\JobDesc::calculateHoursOnJob($db, $date, $sp);
    //\f\pa($workmans,2,'','$workmans');

    return \f\end3('ок', true, $workmans['data']);
});
$twig->addFunction($function);


$function = new Twig_SimpleFunction('jobdesc__get_smena_jobs', function ( string $date_start, string $date_finish, array $get_points = [] ) {

    global $db;




// \f\pa( \Nyos\nyos::$folder_now );

    /**
     * точки продаж
     */
//$points = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, 'sale_point', 'show', null);
    $points = \Nyos\mod\items::getItemsSimple($db, 'sale_point');
// \f\pa($sp0,2);

    /**
     * работники
     */
//    \Nyos\mod\items::$sql_itemsdop_add_where_array = array();
    if (1 == 1) {
        \Nyos\mod\items::$sql_itemsdop_add_where = ' ( 
            ( 
                midop.name != \'stagirovka_start\' 
                OR
                ( midop.name = \'stagirovka_start\' AND midop.value <= date(\'' . $date_finish . '\') )
            )
            OR
            ( 
                midop.name != \'fulljob_start\' 
                OR
                ( midop.name = \'fulljob_start\' AND midop.value <= date(\'' . $date_finish . '\') )
            )
            OR
            ( 
                midop.name != \'job_end\' 
                OR
                ( midop.name = \'job_end\' AND midop.value >= date(\'' . $date_start . '\') )
            )
        )
        ';
        $jobmans = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, '070.jobman', 'show', null);

//\f\pa($jobmans,2,'','$jobmans');
    }


    /*
      $jobmans0 = \Nyos\mod\items::getItemsSimple($db, '070.jobman');
      // \f\pa($jobmans,2,'','$jobmans');

      $ds = strtotime($date_start);
      $df = strtotime($date_finish);

      $jobmans = [];

      foreach ($jobmans0['data'] as $k => $v) {

      if (
      !isset($v['dop']['job_end'])
      || ( isset($v['dop']['job_end']) && strtotime($v['dop']['job_end']) <= $df )
      ) {
      //
      if (isset($v['dop']['stagirovka_start'])) {
      if (strtotime($v['dop']['stagirovka_start']) >= $ds) {
      $jobmans['data'][$k] = $v;
      }
      }
      //
      elseif (isset($v['dop']['fulljob_start'])) {
      if (strtotime($v['dop']['fulljob_start']) >= $ds) {
      $jobmans['data'][$k] = $v;
      }
      }
      }
      }

      \f\pa($jobmans,2,'','$jobmans');
     */
















    /**
     * спец назначения
     */
    $job_in = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, '050.job_in_sp', 'show', null);
// \f\pa($job_in, 2,'','$job_in');

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
        if (isset($job['dop']['date'])) {
            $now_st = strtotime($job['dop']['date']);

            if ($ud_start <= $now_st && ( $ud_fin + 3600 * 24 ) >= $now_st) {
                $a_job_in[$job['dop']['sale_point']][$job['dop']['date']][$job['dop']['jobman']] = 1;
            }
        }
    }

// \f\pa($jobmans,2,null,'$jobmans');
    foreach ($jobmans['data'] as $m => $man) {

// \f\pa($man);

        if (isset($man['dop']['sale_point']) && isset($man['id'])) {
            $a_job_in[$man['dop']['sale_point']]['def'][$man['id']] = 1;
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
// \f\pa($a_job_in);

    return $a_job_in;
});
$twig->addFunction($function);


$function = new Twig_SimpleFunction('jobdesc__get_norms', function ( $db, string $sp, string $date_start, string $date_finish ) {

    return \Nyos\mod\JobDesc::whatNormToDay($db, $sp, $date_start, $date_finish);
});
$twig->addFunction($function);


$function = new Twig_SimpleFunction('jobdesc__calcJobHoursDay', function ( $db, string $date, int $sp ) {
    // $hours = \Nyos\mod\JobDesc::calcJobHoursDay($db, $date, $sp);
    return \Nyos\mod\JobDesc::calcJobHoursDay($db, $date, $sp);
});
$twig->addFunction($function);


$function = new Twig_SimpleFunction('get_timers_on_sp', function ( $db, string $sp, string $date_start, string $date_finish ) {

// \f\pa( \Nyos\nyos::$folder_now );

    /**
     * точки продаж
     */
//    \Nyos\mod\items::$sql_itemsdop2_add_where = '
//            INNER JOIN `mitems-dops` midop2 ON midop2.id_item = mi.id AND midop2.name = \'sale_point\' AND midop2.value = ' . $sp . ' 
//            INNER JOIN `mitems-dops` midop3 ON midop3.id_item = mi.id AND midop3.name = \'date\' 
//                AND midop3.value_date >= \'' . $date_start . '\' 
//                AND midop3.value_date <= \'' . $date_finish . '\' 
//        ';
    $points = \Nyos\mod\items::getItemsSimple($db, '074.time_expectations_list', 'show');
// \f\pa($points,2);

    $ee = [];

    foreach ($points['data'] as $k => $v) {
        if (isset($v['dop']['sale_point']) && $v['dop']['sale_point'] == $sp) {

            $ee[$v['dop']['date']] = $v['dop'];
        }
    }

    return $ee;
});
$twig->addFunction($function);




$function = new Twig_SimpleFunction('get_timers_on_sp_default', function ( $db, string $mod_default = '074.time_expectations_default' ) {

    $cash_var = 'twig--get_timers_on_sp_default--074.time_expectations_default';
    $e = \f\Cash::getVar($cash_var);
    //\f\pa($e);
    if (!empty($e)) {

//                echo '<br/>201: ' . \f\timer::stop('str', 121);
//                echo '<br/>211: ' . \f\CalcMemory::stop(121);

        return $e;
    }

    //$def = \Nyos\mod\items::getItemsSimple($db, $mod_default, 'show');
    $def = \Nyos\mod\items::getItemsSimple3($db, $mod_default);
// \f\pa($def,2);
    $return = [];

    foreach ($def as $k => $v) {
        if (isset($v['otdel'])) {
            if ($v['otdel'] == 1) {
                $return['cold'] = $v['default'];
            } elseif ($v['otdel'] == 2) {
                $return['hot'] = $v['default'];
            } elseif ($v['otdel'] == 3) {
                $return['delivery'] = $v['default'];
            }
        }
    }

    \f\Cash::setVar($cash_var, $return);

    return $return;
});
$twig->addFunction($function);


/**
 * получаем список доступов доступных точек продаж
 */
$function = new Twig_SimpleFunction('jobdesc__get__admin_access_to_sp', function ( $db ) {

    if (empty($_SESSION['now_user_di']['id']) or empty($_SESSION['now_user_di']['access']))
        return \f\end3('не хватает переменных #' . __LINE__, false);

    $sps = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_sale_point, 'show', 'sort_asc');

    // \f\pa( $_SESSION['now_user_di']['access'] );
    // echo '11-' . $_SESSION['now_user_di']['access'] . '-22';
    // \f\pa( $_SESSION['now_user_di'] );
//    if (isset($_SESSION['now_user_di']['access']))
//        \f\pa(123);
//    if ($_SESSION['now_user_di']['access'] == 'admin')
//        \f\pa(123);


    if (!empty($_SESSION['now_user_di']['access']) && $_SESSION['now_user_di']['access'] == 'admin') {

        return \f\end3('ок админ', true, $sps);
    } elseif (isset($_SESSION['now_user_di']['access']) && $_SESSION['now_user_di']['access'] == 'moder') {

        \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
                . ' ON mid.id_item = mi.id '
                . ' AND mid.name = \'user_id\' '
                . ' AND mid.value = :user_id '
        ;
        \Nyos\mod\items::$var_ar_for_1sql[':user_id'] = $_SESSION['now_user_di']['id'];
        //\Nyos\mod\items::$show_sql = true;

        $ac = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_sale_point_access_moder);
        $return = [];

        foreach ($sps as $k1 => $v1) {

            // \f\pa($v1);
            foreach ($ac as $k => $v) {
                if (isset($v['sale_point']) && $v['sale_point'] == $v1['id'])
                    $return[] = $v1;
            }
        }

        return \f\end3('ок модер', true, $return);
    } else {

        return \f\end3('не достаточно прав доступа #' . __LINE__, false);
    }

    return \f\end3('что то пошло не так #' . __LINE__, false, $_SESSION);
});
$twig->addFunction($function);



/**
 * получаем список доступов к точкам продаж если модератор
 */
$function = new Twig_SimpleFunction('jobdesc__get__access_moders', function ( $db ) {

    if (isset($_SESSION['now_user_di']['access']) && $_SESSION['now_user_di']['access'] == 'moder') {

        $ac = \Nyos\mod\items::getItemsSimple($db, 'sale_point_access_moder');
        $return = [];


        foreach ($ac['data'] as $k => $v) {

            if (isset($v['dop']['sale_point']) && isset($v['dop']['user_id']) && $v['dop']['user_id'] == $_SESSION['now_user_di']['id']) {
                $return[$v['dop']['sale_point']] = 1;
            }
        }

        return $return;
    } else {
        return false;
    }
});
$twig->addFunction($function);



$function = new Twig_SimpleFunction('get_list_jobmans', function ( $db, string $date_start, string $date_finish ) {

// \f\pa( \Nyos\nyos::$folder_now );

    /**
     * точки продаж
     */
    $points = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, 'sale_point', 'show', null);
// \f\pa($sp0,2);

    /**
     * работники
     */
//    \Nyos\mod\items::$sql_itemsdop_add_where_array = array();
//    \Nyos\mod\items::$sql_itemsdop_add_where = ' ( 
//            ( 
//                midop.name != \'stagirovka_start\' 
//                OR
//                ( midop.name = \'stagirovka_start\' AND midop.value <= date(\'' . $date_finish . '\') )
//            )
//            OR
//            ( 
//                midop.name != \'fulljob_start\' 
//                OR
//                ( midop.name = \'fulljob_start\' AND midop.value <= date(\'' . $date_finish . '\') )
//            )
//            OR
//            ( 
//                midop.name != \'job_end\' 
//                OR
//                ( midop.name = \'job_end\' AND midop.value >= date(\'' . $date_start . '\') )
//            )
//        )
//        ';
// $jobmans = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, '070.jobman', 'show', null);

    $jobmans = \Nyos\mod\items::getItemsSimple($db, '070.jobman');
// \f\pa($jobmans,2,'','$jobmans');

    /**
     * спец назначения
     */
    $job_in = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, '050.job_in_sp', 'show', null);
// \f\pa($job_in, 2,'','$job_in');

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

// \f\pa($jobmans,2,null,'$jobmans');
    foreach ($jobmans['data'] as $m => $man) {

// \f\pa($man);

        if (isset($man['dop']['sale_point']) && isset($man['id'])) {
            $a_job_in[$man['dop']['sale_point']]['def'][$man['id']] = 1;
        }

//      \f\pa($job);
//        $now_st = strtotime($job['dop']['date']);
//
//        if ($ud_start <= $now_st && ( $ud_fin + 3600 * 24 ) >= $now_st) {
//            $a_job_in[$job['dop']['sale_point']][$job['dop']['jobman']][$job['dop']['date']] = 1;
//        }
    }


    foreach ($job_in['data'] as $j => $job) {

// \f\pa($job);
        $now_st = strtotime($job['dop']['date']);

        if ($ud_start <= $now_st && ( $ud_fin + 3600 * 24 ) >= $now_st) {

            if (!isset($a_job_in[$job['dop']['sale_point']]['def']))
                $a_job_in[$job['dop']['sale_point']]['def'] = [];

            $a_job_in[$job['dop']['sale_point']]['spec'][$job['dop']['jobman']] = 1;
        }
    }


// \f\pa($a_job_in, 2, null, '$a_job_in');
// \f\pa($a_job_in, 2, null, '$a_job_in');
// \f\pa($a_job_in);

    return $a_job_in;
});
$twig->addFunction($function);


/**
 * список сотрудников для добавления в точки продаж (показываем тех у кого нет назначения на другие точки)
 * все возможные сотрудники, новая версия от 19-11-20
 */
$function = new Twig_SimpleFunction('jobdesc__get_list_jobmans', function ( $db ) {


    $jobmans = \Nyos\mod\items::getItemsSimple($db, '070.jobman');
// \f\pa($jobmans, 2);
// $job_on_sp = \Nyos\mod\items::getItemsSimple($db, 'jobman_send_on_sp');
// \f\pa($jobmans);
//    $jobs = [];
//    foreach ($job_on_sp['data'] as $k => $v) {
//        $jobs[$v['dop']['jobman']] = 1;
//    }
//    $free_jobmans = [];
//    foreach ($jobmans['data'] as $k => $v) {
//        if (!isset($jobs[$k])) {
//            $free_jobmans[] = ['id' => $k, 'head' => $v['head'], 'bd' => $v['dop']['birthday'] ?? ''];
//        }
//    }
//    usort($free_jobmans, "\\f\\sort_ar_head");
    usort($jobmans['data'], "\\f\\sort_ar_head");

// return $free_jobmans;
    return $jobmans['data'];
});
$twig->addFunction($function);




/**
 * список сотрудников для добавления в точки продаж (показываем тех у кого нет назначения на другие точки)
 */
$function = new Twig_SimpleFunction('jobdesc__getJobmans', function ( $db ) {

    try {
    $sql = 'SELECT id, head, birthday , iiko_name FROM mod_070_jobman ORDER BY head ASC;';
    $ff = $db->prepare($sql);
    $ff->execute();
    $ss = $ff->fetchAll();
    
    } catch (\PDOException $exc) {
        // echo $exc->getTraceAsString();
        \f\pa($exc);
    }
    
    return $ss;
});
$twig->addFunction($function);

$function = new Twig_SimpleFunction('jobdesc__get_addlist_jobmans', function ( $db ) {

    $jobmans = \Nyos\mod\items::getItemsSimple($db, '070.jobman');
// \f\pa($jobmans, 2);

    $job_on_sp = \Nyos\mod\items::getItemsSimple($db, 'jobman_send_on_sp');
// \f\pa($jobmans);

    $jobs = [];
    foreach ($job_on_sp['data'] as $k => $v) {
        $jobs[$v['dop']['jobman']] = 1;
    }

    $free_jobmans = [];
    foreach ($jobmans['data'] as $k => $v) {
        if (!isset($jobs[$k])) {
            $free_jobmans[] = ['id' => $k, 'head' => $v['head'], 'bd' => $v['dop']['birthday'] ?? ''];
        }
    }

    usort($free_jobmans, "\\f\\sort_ar_head");

    return $free_jobmans;
});
$twig->addFunction($function);


/**
 * список сотрудников для добавления в точки продаж (показываем тех у кого нет назначения на другие точки)
 */
$function = new Twig_SimpleFunction('jobdesc__get_movelist_jobmans', function ( $db ) {


    $jobmans = \Nyos\mod\items::getItemsSimple($db, '070.jobman');
// \f\pa($jobmans, 2);

    $job_on_sp = \Nyos\mod\items::getItemsSimple($db, 'jobman_send_on_sp');
// \f\pa($jobmans);

    $jobs = [];
    foreach ($job_on_sp['data'] as $k => $v) {
        $jobs[$v['dop']['jobman']] = 1;
    }

    $free_jobmans = [];
    foreach ($jobmans['data'] as $k => $v) {
        if (isset($jobs[$k])) {
            $free_jobmans[] = ['id' => $k, 'head' => $v['head'], 'bd' => $v['dop']['birthday'] ?? ''];
        }
    }

    usort($free_jobmans, "\\f\\sort_ar_head");

    return $free_jobmans;
});
$twig->addFunction($function);

/**
 * достаём список сотрудников кто уже работает на точках
 */
$function = new Twig_SimpleFunction('jobdesc__get_all_jobmans', function ( $db ) {

//    $show_timer = true;
//
//    if (isset($show_timer) && $show_timer === true)
//        \f\timer_start(12);

    return \Nyos\mod\JobDesc::getListJobmans($db);

    $return = \Nyos\mod\JobDesc::getListJobmans($db);
    /*
      //foreach ($ee as $k => $v) {
      //        while ($v = $ff->fetch()) {
      //
      //        }
      usort($return, "\\f\\sort_ar_head");
     */

    if (isset($show_timer) && $show_timer === true)
        echo '<br/>ss ' . \f\timer_stop(12);

    return $return;
});
$twig->addFunction($function);


/**
 * достаём список сотрудников кто уже работает на точках, кроме текущей точки
 */
$function = new Twig_SimpleFunction('jobdesc__get_list_for_specnaznach_jobmans', function ( $db ) {


    $sps = \Nyos\mod\items::getItemsSimple($db, 'sale_point');
// \f\pa($job_on_sp);

    $d = \Nyos\mod\items::getItemsSimple($db, '061.dolgnost');
// \f\pa($job_on_sp);

    $jobmans = \Nyos\mod\items::getItemsSimple($db, '070.jobman');
// \f\pa($jobmans, 2);

    $job_on_sp = \Nyos\mod\items::getItemsSimple($db, 'jobman_send_on_sp');
// \f\pa($job_on_sp);

    $jon = [];
    foreach ($job_on_sp['data'] as $k => $v) {
        $jon[] = $v['dop'];
    }

    usort($jon, "\\f\\sort_ar_date_desc");
//\f\pa($jon);

    $jobs = [];
    foreach ($jon as $k => $v) {
        if (isset($v['sale_point']) && isset($v['dolgnost']) && isset($v['jobman']) && !isset($jobs[$v['jobman']])) {
            $jobs[$v['jobman']] = array(
                'sp' => $v['sale_point']
                , 'dolgnost' => $v['dolgnost']
            );
        }
    }
//\f\pa($jobs);


    $nofree_jobmans = [];
    foreach ($jobmans['data'] as $k => $v) {
        if (isset($jobs[$k])) {
            $nofree_jobmans[] = [
                'id' => $k
                , 'head' => $v['head']
                , 'sp_id' => ( $jobs[$k]['sp'] ?? '' )
                , 'sp' => ( $sps['data'][$jobs[$k]['sp']]['head'] ?? '' )
                , 'dolgnost' => $d['data'][$jobs[$k]['dolgnost']]['head']
                , 'bd' => ( $v['dop']['birthday'] ?? '' )
            ];
        }
    }

    usort($nofree_jobmans, "\\f\\sort_ar_head");
// \f\pa($nofree_jobmans);

    return $nofree_jobmans;
});
$twig->addFunction($function);


$function = new Twig_SimpleFunction('jobdesc__where_now_dolgn', function ( $array, $sp, $man, string $date ) {

// echo $date_start.' , '.$date_finish ;
    return \Nyos\mod\JobDesc::where_now_job_man($array, $sp, $man, $date);

// \f\pa( \Nyos\nyos::$folder_now );
});
$twig->addFunction($function);


/**
 * получаем список спец назначнеий в периоде
 */
$function = new Twig_SimpleFunction('jobdesc__get_spec_job_on_sp', function ( $db, string $date_start, string $date_finish, $moduleSpec = '050.job_in_sp' ) {

    $d = \Nyos\mod\items::getItemsSimple($db, $moduleSpec);
    $return = [];

    foreach ($d['data'] as $k => $v) {
        if (isset($v['dop']['jobman']) && isset($v['dop']['date']) && $v['dop']['date'] >= $date_start && $v['dop']['date'] <= $date_finish) {
            $return[$v['dop']['jobman']][$v['dop']['date']] = $v['dop'];
        }
    }

    return $return;

// \f\pa( \Nyos\nyos::$folder_now );
});
$twig->addFunction($function);


/**
 * получаем точки к которым открыт доступ для модера
 */
$function = new Twig_SimpleFunction('jobdesc__get_access_for_moder', function ( $db, $moduleAccess = 'sale_point_access_moder' ) {

    $d = \Nyos\mod\items::getItemsSimple($db, $moduleAccess);

    $return = [];

    foreach ($d['data'] as $k => $v) {
        if (isset($v['dop']['sale_point']) && isset($v['dop']['user_id']) && isset($_SESSION['now_user_di']['id']) && $_SESSION['now_user_di']['id'] == $v['dop']['user_id']
        ) {
            $return[$v['dop']['sale_point']] = true;
        }
    }

    return $return;

// \f\pa( \Nyos\nyos::$folder_now );
});
$twig->addFunction($function);











$function = new Twig_SimpleFunction('jobdesc__getListJobsPeriodAll', function ( $db, string $date_start, string $date_finish ) {

    if (!empty($_GET['sp'])) {
        //$jobs_all = \Nyos\mod\JobDesc::getListJobsPeriodSp($db, $_GET['sp'], $date_start, $date_finish);
        // \f\pa($jobs_all, 2,'','jobs_all sp');
        \Nyos\mod\JobDesc::$sp = $_GET['sp'];
    }

    return \Nyos\mod\JobDesc::getListJobsPeriodAll($db, $date_start, $date_finish);
//    $return = \Nyos\mod\JobDesc::getListJobsPeriodAll($db, $date_start, $date_finish);
//    \f\pa($return);
//    return $return;

//    $jobs_all = \Nyos\mod\JobDesc::getListJobsPeriodAll($db, $date_start, $date_finish);
//    //\f\pa($jobs_all, 2,'','jobs_all');
//
//    return $jobs_all;
});
$twig->addFunction($function);






$function = new Twig_SimpleFunction('jobdesc__jobmans_job_on_sp', function ( $db, $folder = null, string $date_start, string $date_finish ) {

// echo $date_start.' , '.$date_finish ;
//    $ee12 = \Nyos\mod\JobDesc::getListJobsPeriod( $db, $date_start, $date_finish );
//    \f\pa($ee12,'','','$ee12');
//
//    $ee12 = \Nyos\mod\JobDesc::whereJobmansNowDate($db, $date_start );
//    \f\pa($ee12,'','','$ee12');
//    $ee1 = \Nyos\mod\JobDesc::getJobmansDate($db, $date_start );
//    \f\pa($ee1,'','','$ee1');
//    $ee = \Nyos\mod\JobDesc::whereJobmansPeriod($db, $date_start, $date_finish);
//    \f\pa($ee,'','','$ee');

    return \Nyos\mod\JobDesc::whereJobmansOnSp($db, $folder, $date_start, $date_finish);

// \f\pa( \Nyos\nyos::$folder_now );

    /**
     * точки продаж
     */
// $points = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, 'sale_point', 'show', null);

    \Nyos\mod\items::$sql_order = ' ORDER BY mi.sort DESC ';
    $points = \Nyos\mod\items::getItemsSimple($db, 'sale_point');
// \f\pa($points,2,'','$points');

    /**
     * работники
     */
//    \Nyos\mod\items::$sql_itemsdop_add_where_array = array();
    \Nyos\mod\items::$sql_itemsdop_add_where = ' ( 
            ( 
                midop.name != \'stagirovka_start\' 
                OR
                ( midop.name = \'stagirovka_start\' AND midop.value <= date(\'' . $date_finish . '\') )
            )
            OR
            ( 
                midop.name != \'fulljob_start\' 
                OR
                ( midop.name = \'fulljob_start\' AND midop.value <= date(\'' . $date_finish . '\') )
            )
            OR
            ( 
                midop.name != \'job_end\' 
                OR
                ( midop.name = \'job_end\' AND midop.value >= date(\'' . $date_start . '\') )
            )
        )
        ';
    $jobmans = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, '070.jobman', 'show', null);
// \f\pa($jobman,2,'','$jobman');

    /**
     * спец назначения
     */
    $job_in = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, '050.job_in_sp', 'show', null);
// \f\pa($job_in, 2,'','$job_in');

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

// \f\pa($jobmans,2,null,'$jobmans');
    foreach ($jobmans['data'] as $m => $man) {

// \f\pa($man);

        if (isset($man['dop']['sale_point']) && isset($man['id'])) {
            $a_job_in[$man['dop']['sale_point']]['def'][$man['id']] = 1;
        }

//      \f\pa($job);
//        $now_st = strtotime($job['dop']['date']);
//
//        if ($ud_start <= $now_st && ( $ud_fin + 3600 * 24 ) >= $now_st) {
//            $a_job_in[$job['dop']['sale_point']][$job['dop']['jobman']][$job['dop']['date']] = 1;
//        }
    }


    foreach ($job_in['data'] as $j => $job) {

// \f\pa($job);
        $now_st = strtotime($job['dop']['date']);

        if ($ud_start <= $now_st && ( $ud_fin + 3600 * 24 ) >= $now_st) {

            if (!isset($a_job_in[$job['dop']['sale_point']]['def']))
                $a_job_in[$job['dop']['sale_point']]['def'] = [];

            $a_job_in[$job['dop']['sale_point']]['spec'][$job['dop']['jobman']] = 1;
        }
    }


// \f\pa($a_job_in, 2, null, '$a_job_in');
// \f\pa($a_job_in, 2, null, '$a_job_in');
// \f\pa($a_job_in);

    return $a_job_in;
});
$twig->addFunction($function);

/**
 * показываем результат по дню
 */
$function = new Twig_SimpleFunction('show_date_total', function ( $db, string $date ) {


    echo '<br/>' . $date;
// \f\pa( \Nyos\nyos::$folder_now );

    $re = ['aa' => 123];

    /**
     * точки продаж
     */
    $points = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, 'sale_point', 'show', null);
// \f\pa($sp0,2);

    /**
     * работники
     */
//    \Nyos\mod\items::$sql_itemsdop_add_where_array = array();
    \Nyos\mod\items::$sql_itemsdop_add_where = ' ( 
            ( 
                midop.name != \'stagirovka_start\' 
                OR
                ( midop.name = \'stagirovka_start\' AND midop.value <= date(\'' . $date . '\') )
            )
            OR
            ( 
                midop.name != \'fulljob_start\' 
                OR
                ( midop.name = \'fulljob_start\' AND midop.value <= date(\'' . $date . '\') )
            )
            OR
            ( 
                midop.name != \'job_end\' 
                OR
                ( midop.name = \'job_end\' AND midop.value >= date(\'' . $date . '\') )
            )
        )
        ';
    $jobmans = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, '070.jobman', 'show', null);
// \f\pa($jobman,2,'','$jobman');

    /**
     * спец назначения
     */
    $job_in = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, '050.job_in_sp', 'show', null);
// \f\pa($job_in, 2,'','$job_in');

    /**
     * Вход выход на смену
     */
    $checks = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, '050.chekin_checkout', 'show', null);
//\f\pa($checks, 2, null, '$checks');

    return $re;
});
$twig->addFunction($function);

/**
 * получение зарплат дефаулт точка и точки в модуле
 */
$function = new Twig_SimpleFunction('get_salarys', function ( $db, string $module_slary = '071.set_oplata', string $module_sp = 'sale_point' ) {

    return \Nyos\mod\JobDesc::configGetJobmansSmenas($db, null, $module_sp, $module_slary);
});
$twig->addFunction($function);

$function = new Twig_SimpleFunction('get_jobman_on_sp', function ( $db,
        $folder = null,
        $date_start = null,
        $date_fin = null,
        string $module_jobman_go_to_job = 'jobman_send_on_sp',
        string $module_jobman = '070.jobman',
        string $module_salary = '071.set_oplata',
        string $module_sp = 'sale_point'
        ) {

    return \Nyos\mod\JobDesc::whereJobmansOnSp($db, null, $module_sp, $module_slary);
});
$twig->addFunction($function);



/**
 * ищем в массиве оплат, нужные цифры по должности в сп
 */
$function = new Twig_SimpleFunction('get_1salary', function ( $salarys, $sp, $dolgn, $date ) {

//echo '<Br/>' . $sp . ' + ' . $dolgn . ' + ' . $date;

    $now_date = null;
    $re = null;

    if (isset($salarys[$sp][$dolgn])) {

//\f\pa($salarys['default'][$dolgn]);

        foreach ($salarys[$sp][$dolgn] as $k => $v) {

            if (
                    ( $now_date === null || ( $now_date < strtotime($v['date']) ) ) &&
                    strtotime($v['date']) <= strtotime($date)
            ) {

                $lastd = strtotime($v['date']);
                $re = $v;
            }
        }
    }

    if (isset($salarys['default'][$dolgn]) && $re === null) {

//\f\pa($salarys['default'][$dolgn]);

        foreach ($salarys['default'][$dolgn] as $k => $v) {

            if (
                    ( $now_date === null || ( $now_date < strtotime($v['date']) ) ) &&
                    strtotime($v['date']) <= strtotime($date)
            ) {

                $lastd = strtotime($v['date']);
                $re = $v;
            }
        }
    }

    return $re;
});
$twig->addFunction($function);






/**
 * ищем в массиве оплат, нужные цифры по должности в сп
 */
$function = new Twig_SimpleFunction('jobdesc__getSalaryJobman', function ( $db, $sp, $dolgn, $date ) {

//    echo '<<div style="border:1px solid green; padding: 5px; margin: 5px;" >';
//    echo '<br/>\Nyos\mod\JobDesc::getSalaryJobman($db, '.$sp .' , '.$dolgn.' , '.$date.' );';
//    $e = \Nyos\mod\JobDesc::getSalaryJobman($db, $sp, $dolgn, $date);
//    \f\pa($e);
//    echo '</div>';

    return \Nyos\mod\JobDesc::getSalaryJobman($db, $sp, $dolgn, $date);
});
$twig->addFunction($function);









$function = new Twig_SimpleFunction('jobdesc__get_checki', function ( $db, string $date_start, string $date_finish, array $get_points = [] ) {

    //global $db;

    /**
     * Вход выход на смену
     */
    $ud_start = strtotime($date_start);
    $ud_start = date('Y-m-d 08:00:00', strtotime($date_start));
    $ud_fin = strtotime($date_finish);
    $ud_fin = date('Y-m-d 03:00:00', strtotime($date_finish . ' +1 day'));


//    \Nyos\mod\items::$sql_itemsdop_add_where = ' ( 
//                ( midop.name != \'start\' OR ( midop.name = \'start\' AND midop.value_DATE >= date(\'' . $date_start . '\') ) )
//                    OR
//                ( midop.name != \'fin\' OR ( midop.name = \'fin\' AND midop.value_date <= date(\'' . date('Y-m-d', $ud_fin + 3600 * 24) . '\') ) )
//        )
//        ';
//$checks = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, '050.chekin_checkout', 'show', null);
//    $checks = \Nyos\mod\items::getItemsSimple($db, '050.chekin_checkout');
//    \f\pa($checks, 2, null, '$checks');
// \f\pa($points);
// \Nyos\mod\items::$style_old = true;
//    $checks0 = \Nyos\mod\items::getItemsSimple3($db, '050.chekin_checkout');

    \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
            . ' ON mid.id_item = mi.id '
            . ' AND mid.name = \'start\' '
            . ' AND mid.value_datetime >= :ds '
//            . ' AND mid.value_date <= :df '
//            . ' INNER JOIN `mitems-dops` mid2 '
//            . ' ON mid2.id_item = mi.id '
//            . ' AND mid2.name = \'sale_point\' '
//            . ' AND mid2.value = :sp '

    ;
    \Nyos\mod\items::$var_ar_for_1sql[':ds'] = $ud_start;
//    \Nyos\mod\items::$var_ar_for_1sql[':sp'] = $sp;
//    \Nyos\mod\items::$var_ar_for_1sql[':df'] = $date_finish;
    $checks = \Nyos\mod\items::get($db, '050.chekin_checkout');


//    $checks = [];
//// \f\pa($checks);
//    foreach ($checks2 as $k => $v) {
////        if (isset($v['start']) && $v['start'] > $ud_start) {
//            //$v['dop'] = $v;
//            $checks[$v['id']] = $v;
////        }
////        else{
////            echo '<br/>'.$v['start'];
////        }
//    }

    unset($checks0);

// \f\pa($checks);

    if (1 == 1) {


// $payeds0 = \Nyos\mod\items::getItemsSimple($db, '075.buh_oplats');
        // $payeds0 = \Nyos\mod\items::getItemsSimple3($db, '075.buh_oplats');
        $payeds0 = \Nyos\mod\items::get($db, '075.buh_oplats');
// \f\pa($payeds0,'','','payeds');

        foreach ($payeds0 as $k => $v) {
            if (!empty($v['checkin'])) {
                $payeds[$v['checkin']][] = $v;
            }
        }
// \f\pa($payeds,'','','payeds');

        unset($payeds0);
    }

    if (1 == 1) {


        /**
         * работник - дата - время вх и вых
         */
        $vv['checks'] = [];

// \f\pa($checks);

        if (isset($checks) && sizeof($checks) > 0) {

            foreach ($checks as $c => $check) {

// $now_st = strtotime($check['dop']['start']);
// if ($ud_start <= $now_st && ( $ud_fin + 3600 * 24 ) >= $now_st) {
                if (isset($check['start'])) {

                    $da = date('Y-m-d', strtotime($check['start']));

                    if (isset($payeds[$check['id']])) {

                        $check['payed'] = $payeds[$check['id']];
                    }
                    if (isset($check['fin'])) {

                        $check['time_on_job'] = ( ceil(strtotime($check['fin']) / 1800) * 1800 ) - ( ceil(strtotime($check['start']) / 1800) * 1800 );
                        $check['hour_on_job'] = ceil($check['time_on_job'] / 1800) / 2;
// $check['dop']['polhour'] = ceil($check['dop']['time_on_job'] / 1800)*1800;
// $check['dop']['colvo_hour'] = $check['dop']['polhour'] * 2;
                    }

                    if (isset($check['jobman']) && isset($check['id']))
                        $vv['checks'][$da][$check['jobman']][$check['id']] = $check;
                }

// }
            }
        }

// \f\pa($vv['checks'], 2, null, '$vv[\'checks\']');
    }

    return $vv['checks'];
});
$twig->addFunction($function);



$function = new Twig_SimpleFunction('get_minusa', function ( $db, string $date_start, string $date_finish ) {

    \Nyos\mod\items::$sql_itemsdop_add_where = '
        ( midop.name != \'date\' OR
            ( 
                midop.name = \'date\' AND 
                midop.value >= date(\'' . $date_start . '\') AND 
                midop.value <= date(\'' . $date_finish . '\') 
            )
        )
        ';
    $vv['checks'] = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, '072.vzuscaniya', '', null);
// \f\pa($vv['checks']);

    return $vv['checks'];
});
$twig->addFunction($function);

$function = new Twig_SimpleFunction('jobdesc__get_ocenka_day', function ( $db, string $date_start, string $date_finish ) {


    $dt1 = date('Y-m-d', strtotime($date_start));
    $dt2 = date('Y-m-d', strtotime($date_finish));

//    \Nyos\mod\items::$sql_itemsdop_add_where_array = array(
//        ':dt1' => date('Y-m-d', strtotime($date_start) )
//        ,
//        ':dt2' => date('Y-m-d', strtotime($date_finish) )
//    );
//    \Nyos\mod\items::$sql_itemsdop2_add_where = '
//        INNER JOIN `mitems-dops` md1 
//            ON 
//                md1.id_item = mi.id 
//                AND md1.name = \'date\'
//                AND md1.value_date >= :dt1
//                AND md1.value_date <= :dt2
//        ';

    $oc = \Nyos\mod\items::getItemsSimple($db, 'sp_ocenki_job_day');
//\f\pa($oc);

    $r = [];

    foreach ($oc['data'] as $k => $v) {
        if (isset($v['dop']['date']) && $v['dop']['date'] <= $dt1 && $v['dop']['date'] >= $dt2) {
            $r[$v['dop']['sale_point']][$v['dop']['date']] = $v['dop'];
        }
    }

    return $r;
});
$twig->addFunction($function);


$function = new Twig_SimpleFunction('getComments', function ( $db, string $date_start, string $date_finish, $sp = null, $jobman = null ) {

    \Nyos\mod\items::$sql_itemsdop_add_where = '
        ( midop.name != \'date\' OR
            ( 
                midop.name = \'date\' AND 
                midop.value >= date(\'' . $date_start . '\') AND 
                midop.value <= date(\'' . $date_finish . '\') 
            )
        )
        ';

    if (!empty($sp)) {
        \Nyos\mod\items::$sql_itemsdop_add_where .= '
        AND ( midop.name != \'sale_point\' OR
            ( 
                midop.name = \'sale_point\' AND 
                midop.value = \'' . addslashes($sp) . '\'
            )
        )
        ';
    }

    if (!empty($jobman)) {
        \Nyos\mod\items::$sql_itemsdop_add_where .= '
        AND ( midop.name != \'jobman\' OR
            ( 
                midop.name = \'jobman\' AND 
                midop.value = \'' . addslashes($jobman) . '\'
            )
        )
        ';
    }

    $vv['comments'] = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, '073.comments', '', null);
// \f\pa($vv['checks']);

    return $vv['comments'];
});
$twig->addFunction($function);



$function = new Twig_SimpleFunction('get_plusa', function ( $db, string $date_start, string $date_finish ) {

// \f\timer::start(22);
//    \Nyos\mod\items::$sql_itemsdop_add_where = '
//        ( midop.name != \'date\' OR
//            ( 
//                midop.name = \'date\' AND 
//                midop.value >= date(\'' . $date_start . '\') AND 
//                midop.value <= date(\'' . $date_finish . '\') 
//            )
//        )
//        ';

    \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` md ON md.id_item = mi.id AND md.name = \'date_now\' AND md.value_date >= \'' . $date_start . '\' AND md.value_date <= \'' . $date_finish . '\' ';
    $e = \Nyos\mod\items::getItemsSimple3($db, '072.plus');

// \f\pa($e);
// \f\pa($vv['checks']);
// echo \f\timer::stop('str',22);

    $re = [];
    foreach ($e as $k => $v) {
        $re[$v['sale_point']][$v['jobman']][$v['date_now']][] = $v;
    }

    return $re;
    return $e;
    /*
      \Nyos\mod\items::$get_data_simple = true;
      $e = \Nyos\mod\items::getItemsSimple($db, '072.plus','show');

      $return = [];

      foreach( $e as $k => $v ){
      if( !empty($v['date_now'])
      && $v['date_now'] >= $date_start
      && $v['date_now'] <= $date_finish ){
      $return[] = $v;
      }
      }

      // echo \f\timer::stop('str',22);
      //die();

      //
      return $return;
     */
});
$twig->addFunction($function);


/**
 * получаем плюса и минуса периода
 */
$function = new Twig_SimpleFunction('jobdesc__get_money_dops', function ( $db, string $date_start, string $date_finish, $sp = null, $type_ar = 'sp-jobman-date' ) {

    $re = [];

    \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` md ON md.id_item = mi.id AND md.name = \'date_now\' AND md.value_date >= \'' . $date_start . '\' AND md.value_date <= \'' . $date_finish . '\' ';

    if (!empty($sp) && is_numeric($sp)) {
        \Nyos\mod\items::$join_where .= ' INNER JOIN `mitems-dops` md1 ON md1.id_item = mi.id AND md1.name = \'sale_point\' AND md1.value = \'' . $sp . '\' ';
    }

    $e = \Nyos\mod\items::getItemsSimple3($db, '072.plus');
// \f\pa($e);

    foreach ($e as $k => $v) {
        $v['type2'] = 'plus';

        if ($type_ar == 'jobman-date') {
            $re[$v['jobman']][$v['date_now']][] = $v;
        }
//elseif( $type_ar = 'sp-jobman-date' ) {
        else {
            $re[$v['sale_point']][$v['jobman']][$v['date_now']][] = $v;
        }
    }



    \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` md ON md.id_item = mi.id AND md.name = \'date_now\' AND md.value_date >= \'' . $date_start . '\' AND md.value_date <= \'' . $date_finish . '\' ';

    if (!empty($sp) && is_numeric($sp)) {
        \Nyos\mod\items::$join_where .= ' INNER JOIN `mitems-dops` md1 ON md1.id_item = mi.id AND md1.name = \'sale_point\' AND md1.value = \'' . $sp . '\' ';
    }

    $e = \Nyos\mod\items::getItemsSimple3($db, '072.vzuscaniya');
// \f\pa($e);

    foreach ($e as $k => $v) {
        $v['type2'] = 'minus';

        if ($type_ar == 'jobman-date') {
            $re[$v['jobman']][$v['date_now']][] = $v;
        }
//elseif( $type_ar = 'sp-jobman-date' ) {
        else {
            $re[$v['sale_point']][$v['jobman']][$v['date_now']][] = $v;
        }
    }

    return $re;
});
$twig->addFunction($function);




$function = new Twig_SimpleFunction('jobdesc__get_ocenki_days', function ( $db, string $sp, string $date_start, string $date_finish ) {

//        \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
//                . ' ON mid.id_item = mi.id '
//                . ' AND mid.name = \'date\' '
//                . ' AND mid.value_date >= \'' . $date_start . '\' '
//                . ' AND mid.value_date <= \'' . $date_finish . '\' '
//                . ' INNER JOIN `mitems-dops` mid2 '
//                . ' ON mid2.id_item = mi.id '
//                . ' AND mid2.name = \'sale_point\' '
//                . ' AND mid2.value = \'' . $sp . '\' '
    ;

    // \Nyos\mod\items::$show_sql = true;
    \Nyos\mod\items::$between_date['date'] = [$date_start, $date_finish];
    \Nyos\mod\items::$search['sale_point'] = $sp;
    $ocenki = \Nyos\mod\items::get($db, 'sp_ocenki_job_day');
    // \f\pa($ocenki,'','','$ocenki');

    $re = [];

    foreach ($ocenki as $k => $v) {

        //if (!empty($v['dop']['sale_point']) && $v['dop']['sale_point'] == $sp && !empty($v['dop']['date']) && $v['dop']['date'] >= $date_start && $v['dop']['date'] <= $date_finish) {
        $re[$v['date']] = $v;
        //$re[$v['date']]['id'] = $v['id'];
        //}
    }


    return $re;
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















