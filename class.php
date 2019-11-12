<?php

/**
  класс модуля
 * */

namespace Nyos\mod;

if (!defined('IN_NYOS_PROJECT'))
    throw new \Exception('Сработала защита от розовых хакеров, обратитесь к администрратору');

class JobDesc {

    public static $dir_img_server = false;
    public static $cash = [
        'salaris_all' => []
    ];

    /**
     * список модулей
     * назначения людей на точки продаж
     */
    public static $mod_man_job_on_sp = 'jobman_send_on_sp';

    /**
     * список модулей
     * спец. назначения людей на ТП
     */
    public static $mod_spec_jobday = '050.job_in_sp';

    /**
     * список модулей
     * должности
     */
    public static $mod_dolgn = '061.dolgnost';

    /**
     * список модулей
     * зарплаты
     */
    public static $mod_salary = '071.set_oplata';

    /**
     * список модулей
     * точки продаж
     */
    public static $mod_sale_point = 'sale_point';

    /**
     * список модулей 
     * оценки работы дня
     */
    public static $mod_ocenki_days = 'sp_ocenki_job_day';

    /**
     * список модулей //  
     * чеки
     */
    public static $mod_checks = '050.chekin_checkout';

    /**
     * список модулей //  
     * время ожидания по умолчанию
     */
    public static $mod_timeo_default = '074.time_expectations_default';

    /**
     * получаем какие цены по датам у должностей на точке продаж (старая)
     * @param type $db
     * @param type $folder
     * @param type $module_sp
     * @param type $module_slary
     * @return type
     */
    public static function configGetJobmansSmenas($db, $folder = null, $module_sp = 'sale_point', $module_slary = '071.set_oplata') {

        if ($folder === null)
            $folder = \Nyos\nyos::$folder_now;

// \f\pa( \Nyos\nyos::$folder_now );

        $re = [];

        /**
         * точки продаж
         */
        $sps = \Nyos\mod\items::getItems($db, $folder, $module_sp, 'show', null);
// \f\pa($sps, 2);

        /**
         * 
         */
// $salary = \Nyos\mod\items::getItems($db, $folder, $module_slary, 'show', null);
        $salary = \Nyos\mod\items::getItemsSimple($db, $module_slary, 'show');
// \f\pa($salary, 2);

        $re = [];

        foreach ($salary['data'] as $k => $v) {

            if (
                    $v['status'] == 'show' &&
                    isset($sps['data'][$v['dop']['sale_point']]) &&
                    $sps['data'][$v['dop']['sale_point']]['status'] == 'show') {
                
            } else {
                continue;
            }

            if (isset($v['dop']['sale_point']) && isset($sps['data'][$v['dop']['sale_point']])) {

                if (isset($sps['data'][$v['dop']['sale_point']]['head']) && $sps['data'][$v['dop']['sale_point']]['head'] == 'default') {

                    $re['default'][$v['dop']['dolgnost']][$v['dop']['date']] = $v['dop'];
                } else {

                    $re[$v['dop']['sale_point']][$v['dop']['dolgnost']][$v['dop']['date']] = $v['dop'];
                }
            }
        }

        $re2 = [];
        foreach ($re as $point => $v1) {
            foreach ($v1 as $dolg => $v2) {
                ksort($v2);
                $re2[$point][$dolg] = $v2;
            }
        }

        return $re2;
    }

    /**
     * получаем список сотрудников которые работают в указанный промежуток времени (новая версия)
     * @param type $db
     * @param type $dt_start
     * @param type $dt_fin
     * @param type $module_send_jobman_to_sp
     * @return int
     */
    public static function getJobmansOnTime1910($db, $dt_start, $dt_fin, $module_send_jobman_to_sp = 'jobman_send_on_sp') {

        /**
         * тащим список назначений на работу в точке продаж в период времени
         */
        $jobman_on = [];

        $send_jobm_to_sp = \Nyos\mod\items::getItemsSimple($db, $module_send_jobman_to_sp);
// \f\pa($send_jobm_to_sp, 2, '', '$send_jobm_to_sp');

        foreach ($send_jobm_to_sp['data'] as $k => $v) {

            if (isset($v['dop']['jobman']) && !isset($jobman_on[$v['dop']['jobman']])) {
                if (isset($v['dop']['date']) && $v['dop']['date'] <= $dt_fin) {

                    $jobman_on[$v['dop']['jobman']] = 1;

                    /*
                      if (isset($v['dop']['date']) && isset($v['dop']['date_finish'])) {
                      $jobman_on[$v['dop']['jobman']] = 1;
                      }
                     */
                }
            }
        }

// \f\pa($jobman_on, 2, '', '$return[jobman_on] допущенные сотрудники');


        return $jobman_on;
    }

    /**
     * получаем какие цены по датам у должностей на точке продаж (старая)
     * @param type $db
     * @param type $folder
     * @param type $module_sp
     * @param type $module_slary
     * @return type
     */
    public static function compileSalarysJobmans($db, $date, $module_sp = 'sale_point', $module_slary = '071.set_oplata') {

//if ($folder === null)
        $folder = \Nyos\nyos::$folder_now;

// \f\pa( \Nyos\nyos::$folder_now );

        $re = [];

        /**
         * точки продаж
         */
        $sps = \Nyos\mod\items::getItems($db, $folder, $module_sp, 'show', null);
// \f\pa($sps, 2);

        /**
         * 
         */
// $salary = \Nyos\mod\items::getItems($db, $folder, $module_slary, 'show', null);
        $salary = \Nyos\mod\items::getItemsSimple($db, $module_slary, 'show');
// \f\pa($salary, 2);

        $re = [];

        foreach ($salary['data'] as $k => $v) {

            if (
                    $v['status'] == 'show' &&
                    isset($sps['data'][$v['dop']['sale_point']]) &&
                    $sps['data'][$v['dop']['sale_point']]['status'] == 'show') {
                
            } else {
                continue;
            }

            if (isset($v['dop']['sale_point']) && isset($sps['data'][$v['dop']['sale_point']])) {

                if (isset($sps['data'][$v['dop']['sale_point']]['head']) && $sps['data'][$v['dop']['sale_point']]['head'] == 'default') {

                    $re['default'][$v['dop']['dolgnost']][$v['dop']['date']] = $v['dop'];
                } else {

                    $re[$v['dop']['sale_point']][$v['dop']['dolgnost']][$v['dop']['date']] = $v['dop'];
                }
            }
        }

        $re2 = [];
        foreach ($re as $point => $v1) {
            foreach ($v1 as $dolg => $v2) {
                ksort($v2);
                $re2[$point][$dolg] = $v2;
            }
        }

        return $re2;
    }

    /**
     * получаем id точки продаж по умолчанию
     * @param type $db
     * @param type $module_sp
     * @return type
     */
    public static function getDefaultSpId($db, $module_sp = 'sale_point') {

        if (!empty(self::$cash['sp_default']))
            return self::$cash['sp_default'];

        $sps = \Nyos\mod\items::getItemsSimple($db, $module_sp);
// \f\pa($sps,2,'','sps');

        $sp_default = null;

        foreach ($sps['data'] as $k => $v) {
            if ($v['head'] == 'default') {
                self::$cash['sp_default'] = $k;
                break;
            }
        }

        return self::$cash['sp_default'];
    }

    /**
     * считаем сумму заработанную за смену
     * @param массив $a
     * входящий массив с подборкой всех данных
     * @return цифра сумма или false
     */
    public static function calcSummaDay($a) {

// $summa = 0;
// \f\pa($a);

        if (isset($a['hour_on_job'])) {
            $hour = $a['hour_on_job'];
        }

        if (isset($a['ocenka'])) {
            $ocenka = $a['ocenka'];
        }

        $smoke = (isset($a['now_job']['smoke']) && $a['now_job']['smoke'] == 'da' ) ? true : false;

        if (
                !empty($hour) &&
                !empty($ocenka) &&
                !empty($a['salary-now']['ocenka-hour-' . $ocenka])
        ) {

            $summa = $hour * ( $a['salary-now']['ocenka-hour-' . $ocenka] + ( $smoke === true ? ( $a['salary-now']['if_kurit'] ?? 0 ) : 0 ) );
        }

        return $summa ?? false;
    }

    /**
     * 
     * @param type $db
     * @param type $sp
     * @param type $dolgn
     * @param type $date
     * @param type $oborot_sp_month
     * @param type $ocenka
     * @param type $module_sp
     * @param type $module_slary
     * @return type
     */
    public static function getSalaryJobman($db, $sp, $dolgn, $date, $module_sp = 'sale_point', $module_slary = '071.set_oplata') {

//        $sp = 2229;
//        $dolgn = 2;
//        $date = '2019-10-05';
//        echo '<hr>';
//        echo '<hr>';
//        echo '<hr>';
//        
//        echo '<br/>' . $sp . ' -- ' . $dolgn . ' -- ' . $date;

        if (isset(self::$cash['salary_now'][$sp][$dolgn][$date]))
            return self::$cash['salary_now'][$sp][$dolgn][$date];

//        echo '<br/>#'.__LINE__;

        $sps = \Nyos\mod\items::getItemsSimple($db, $module_sp);
// \f\pa($sps,2,'','sps');
// id sp по умолчанию
        $sp_default = self::getDefaultSpId($db);

// \f\pa($sp_default,2,'','$sp_default');
//$return = [ '11' => '22' ];
        $return = [];

        /**
         * достаём все зарплаты
         */
        if (empty(self::$cash['salarys'])) {

            $salary = \Nyos\mod\items::getItemsSimple($db, $module_slary, 'show');
//\f\pa($salary, 2);

            self::$cash['salarys'] = [];

            foreach ($salary['data'] as $k => $v) {
                self::$cash['salarys'][] = $v['dop'];
            }

            usort(self::$cash['salarys'], "\\f\\sort_ar_date");
        }

// \f\pa(self::$cash['salarys'], 2, '', 'salarises');
// \Nyos\mod\JobBuh::getOborotSpMonth($db, $v['dop']['now_job']['sale_point'], $v['dop']['date']);

        /**
         * достаём зп этой должности этой тп и этой даты
         */
//        $oborot1 = \Nyos\mod\IikoOborot::whatMonthOborot($db, $sp, substr($date, 5, 2), substr($date, 0, 4));
//        // \f\pa($oborot1);
//        $oborot = $oborot1['data']['oborot'];
// echo ' -' . $sp . ' =' . $dolgn . ' ';

        $no_def_sp = false;

        foreach (self::$cash['salarys'] as $k => $v) {

//echo ' --1 ';
//\f\pa($v,2);
// echo '<br/>дата ' . $v['date'];

            if (
                    $v['sale_point'] == $sp ||
                    (
                    $no_def_sp === false &&
                    $v['sale_point'] == $sp_default
                    )
            ) {

                if ($v['sale_point'] == $sp)
                    $no_def_sp = true;

//                echo '<br/>точка сходится '.$v['sale_point'];
//                echo '<br/>'.__FILE__.' #'.__LINE__;

                if ($v['dolgnost'] == $dolgn) {

// echo '<br/>точка сходится ' . $v['sale_point'];
// echo '<br/>' . __FILE__ . ' #' . __LINE__;
// echo '<br/>должность норм ' . $v['dolgnost'];
// echo '<br/>' . __FILE__ . ' #' . __LINE__;

                    if ($v['sale_point'] != $sp_default) {
// echo ' --' . __LINE__ . ' ';
                        $no_def_sp = true;
                    }

// \f\pa($v, 2);

                    if (isset($v['date']) && $date >= $v['date']) {

// \f\pa(self::$cash['salary_now'][$sp][$dolgn][$date], 2, '', 'salary dolgn sp');

                        if (isset($v['oborot_sp_last_monht_menee']) || isset($v['oborot_sp_last_monht_bolee'])) {

// достаём оборот этой точки продаж за текущий месяц
                            $oborot = \Nyos\mod\JobBuh::getOborotSpMonth($db, $sp, $date);
// $oborot = 1000000;
// \f\pa($oborot, 2, '', 'oborot');
// \f\pa($v, 2, '', 'v');
//echo '<br/>'.$oborot;
//echo '<br/>'.( $v['oborot_sp_last_monht_menee'] ?? '--' );

                            if (isset($v['oborot_sp_last_monht_menee']) && $v['oborot_sp_last_monht_menee'] >= $oborot) {

//echo '<br/>' . __FILE__ . ' #' . __LINE__;
                                self::$cash['salary_now'][$sp][$dolgn][$date] = $v;
                            } elseif (isset($v['oborot_sp_last_monht_bolee']) && $v['oborot_sp_last_monht_bolee'] <= $oborot) {

//echo '<br/>' . __FILE__ . ' #' . __LINE__;
                                self::$cash['salary_now'][$sp][$dolgn][$date] = $v;
                            }
//                        else {
//                            self::$cash['salary_now'][$sp][$dolgn][$date] = -266;
//                        }
                        } else {
                            self::$cash['salary_now'][$sp][$dolgn][$date] = $v;
                        }
                    } elseif (isset($v['date']) && $date < $v['date']) {
                        break;
                    }


// \f\pa(self::$cash['salary_now'][$sp][$dolgn][$date],2,'','salary dolgn sp');
                }
            }
        }

//        if (isset(self::$cash['salary_now'][$sp][$dolgn][$date])) {
//            \f\pa(self::$cash['salary_now'][$sp][$dolgn][$date], '', '', 'now salary');
//        }
//        if( $ocenka !== null && 
//            isset(self::$cash['salary_now'][$sp][$dolgn][$date]['ocenka-hour-'.$ocenka]) && 
//            isset(self::$cash['salary_now'][$sp][$dolgn][$date]['hour_on_job']) ){
//        
//        self::$cash['salary_now'][$sp][$dolgn][$date]['summa'] = self::$cash['salary_now'][$sp][$dolgn][$date]['hour_on_job'] * self::$cash['salary_now'][$sp][$dolgn][$date]['ocenka-hour-'.$ocenka];
//            
//        }
//self::$cash['salary_now'][$sp][$dolgn][$date]['summa'] = 0;

        if (isset(self::$cash['salary_now'][$sp][$dolgn][$date])) {
            return self::$cash['salary_now'][$sp][$dolgn][$date];
        } else {
            return;
        }

        if (1 == 1) {
            if (!isset(self::$cash['salary_now'][$sp][$dolgn][$date])) {

                foreach (self::$cash['salarys'] as $k => $v) {

//echo '1';
// \f\pa($v,2);

                    if ($v['sale_point'] == $sp && $v['dolgnost'] == $dolgn) {

//\f\pa($v,2);

                        if ($date >= $v['date']) {

                            if (isset($v['oborot_sp_last_monht_menee']) && $v['oborot_sp_last_monht_menee'] <= $oborot) {
                                self::$cash['salary_now'][$sp][$dolgn][$date] = $v;
                            } elseif (isset($v['oborot_sp_last_monht_bolee']) && $v['oborot_sp_last_monht_bolee'] >= $oborot) {
                                self::$cash['salary_now'][$sp][$dolgn][$date] = $v;
                            } else {
                                self::$cash['salary_now'][$sp][$dolgn][$date] = $v;
                            }
                        } elseif ($date < $v['date']) {
                            break;
                        }
                    }
                }
            }
        }

        foreach (self::$cash['salarys'] as $k => $v) {

//echo '1';
// \f\pa($v,2);

            if ($v['sale_point'] == $sp && $v['dolgnost'] == $dolgn) {

//\f\pa($v,2);

                if ($date >= $v['date']) {

                    if (isset($v['oborot_sp_last_monht_menee']) && $v['oborot_sp_last_monht_menee'] <= $oborot) {
                        self::$cash['salary_now'][$sp][$dolgn][$date] = $v;
                    } elseif (isset($v['oborot_sp_last_monht_bolee']) && $v['oborot_sp_last_monht_bolee'] >= $oborot) {
                        self::$cash['salary_now'][$sp][$dolgn][$date] = $v;
                    } else {
                        self::$cash['salary_now'][$sp][$dolgn][$date] = $v;
                    }
                } elseif ($date < $v['date']) {
                    break;
                }
            }
        }

        if (1 == 1) {

            if (!isset(self::$cash['salary_now'][$sp][$dolgn][$date])) {


//$sps = \Nyos\mod\items::getItems($db, \Nyos\Nyos::getFolder(), $module_sp, 'show', null);
//\Nyos\mod\items::$show_sql = true;
                $sps = \Nyos\mod\items::getItemsSimple($db, $module_sp);
//\f\pa($sps, 2);

                foreach ($sps['data'] as $k => $v) {
                    if ($v['head'] == 'default') {
                        $sp_id = $v['id'];
                    }
                }

                if (isset($sp_id)) {

                    $sp = $sp_id;

                    foreach (self::$cash['salarys'] as $k => $v) {

//echo '1';
// \f\pa($v,2);

                        if ($v['sale_point'] == $sp && $v['dolgnost'] == $dolgn) {

//\f\pa($v,2);

                            if ($date >= $v['date']) {

                                if (isset($v['oborot_sp_last_monht_menee']) && $v['oborot_sp_last_monht_menee'] <= $oborot) {
                                    self::$cash['salary_now'][$sp][$dolgn][$date] = $v;
                                } elseif (isset($v['oborot_sp_last_monht_bolee']) && $v['oborot_sp_last_monht_bolee'] >= $oborot) {
                                    self::$cash['salary_now'][$sp][$dolgn][$date] = $v;
                                } else {
                                    self::$cash['salary_now'][$sp][$dolgn][$date] = $v;
                                }
                            } elseif ($date < $v['date']) {
                                break;
                            }
                        }
                    }
                }
            }
        }

        return isset(self::$cash['salary_now'][$sp][$dolgn][$date]) ? self::$cash['salary_now'][$sp][$dolgn][$date] : false;
    }

    /**
     * ищем где работают люди
     * @param type $db
     * @param type $folder
     * @param type $date_start
     * @param type $date_fin
     * @param type $module_man_job_on_sp
     * @return type
     */
    public static function getTimeOgidanie($db, int $sp, string $date) {

// echo '<br/>'.$sp.' + '.$date;

        $timeo0 = \Nyos\mod\items::getItemsSimple($db, '074.time_expectations_list', 'show');

//\f\pa($timeo0);

        foreach ($timeo0['data'] as $k => $v) {

// echo '<br/>'.$v['dop']['date'];

            if (isset($v['dop']['sale_point']) && $v['dop']['sale_point'] == $sp && isset($v['dop']['date']) && $v['dop']['date'] == $date) {
                $timeo[] = $v['dop'];
            }
        }

        $return = [];

        if (isset($timeo)) {
            foreach ($timeo as $k1 => $v1) {
                foreach ($v1 as $k => $v) {
                    $return['timeo_' . $k] = $v;
                }
            }
        }

        return $return;
    }

    /**
     * получаем обороты по точке продаж за день
     * не использовать, старая версия
     * новая \Nyos\mod\IikoOborot::getDayOborot($db, $sp, $date);
     * @param type $db
     * @param int $sp
     * @param string $date
     * @return type
     */
    public static function getOborotSp($db, int $sp, string $date) {

        $oborot = \Nyos\mod\IikoOborot::getDayOborot($db, $sp, $date);

        if ($oborot === false)
            throw new \Exception('Оборот точки продаж не указан', 10);

        return $oborot;





        $date1 = date('Y-m-d', strtotime($date));
        $sp1 = $sp;
        /*
          \Nyos\mod\items::$sql_itemsdop_add_where_array = array(
          ':date' => $date1
          ,
          ':sp' => $sp1
          );
          \Nyos\mod\items::$sql_itemsdop2_add_where = '
          INNER JOIN `mitems-dops` md1
          ON
          md1.id_item = mi.id
          AND md1.name = \'sale_point\'
          AND md1.value = :sp
          '
          . '
          INNER JOIN `mitems-dops` md2
          ON
          md2.id_item = mi.id
          AND md2.name = \'date\'
          AND md2.value_date = :date
          '
          ;
         */
        $oborot_all = \Nyos\mod\items::getItemsSimple($db, 'sale_point_oborot', 'show');
// \f\pa($oborot, 2, '', '$oborot');

        foreach ($oborot_all['data'] as $k1 => $v1) {
            if (isset($v1['dop']['sale_point']) && $v1['dop']['sale_point'] == $sp1 && isset($v1['dop']['date']) && $v1['dop']['date'] == $date1) {

                foreach ($v1['dop'] as $k => $v) {
//$return['txt'] .= '<br/><nobr>[oborot_' . $k . '] - ' . $v . '</nobr>';
                    $return['oborot_' . $k] = $v;
                }

                $oborot = $v1['dop']['oborot_server'] ?? $v1['dop']['oborot_hand'] ?? false;

                break;
            }
        }

        if (empty($oborot))
            throw new \Exception('Оборот точки продаж не указан', 10);

        return $oborot;
    }

    /**
     * получаем количество часов отработанных в этот день
     * @param type $db
     * @param int $sp
     * @param string $date
     * @return type
     * @throws \Exception
     */
    public static function getTimesChecksDay($db, int $sp, string $date) {

//        echo '<hr>'
//        . __FILE__ . ' #' . __LINE__
//        . '<br/>'
//        . __FUNCTION__
//        . '<hr>';

        /**
         * тащим кто кем и где работал под дням в периоде
         */
        /*
          $jobinsp = \Nyos\mod\JobDesc::getSetupJobmanOnSp($db, $date);
          \f\pa($jobinsp, 2, '', '$jobinsp');
         */

        $worker_on_date = self::whereJobmans($db, $date);
        \f\pa($worker_on_date, 2, '', 'self::whereJobmans($db, $date);');



















// \f\pa($jobinsp2, 2, '', '$jobinsp2');

        $checki = \Nyos\mod\items::getItemsSimple($db, '050.chekin_checkout');
        //\f\pa($checki,2,'','$checki'); // exit;

        $dd = date('Y-m-d', strtotime($date));

        $dds = strtotime(date('Y-m-d 09:00:00', strtotime($date)));
        $ddf = strtotime(date('Y-m-d 02:00:00', ( strtotime($date) + 3600 * 24)));

        $checki2 = [];

        echo '<Br/>' . $date . '<br/>';

        foreach ($checki['data'] as $k2 => $v2) {

            if (isset($v2['dop']['jobman']) && isset($jobinsp['jobs'][$v2['dop']['jobman']])) {


                $ddn = strtotime($v2['dop']['start']);

                if ($dds <= $ddn && $ddn <= $ddf) {

                    // echo '<br/>#' . __LINE__ . ' ' . $v2['dop']['jobman'];
                    \f\pa($v2['dop']);
                    $checki2[$v2['dop']['jobman']][] = $v2['dop'];
                }
            }
        }

        \f\pa($checki2, 2, '', '$checki2'); // exit;
        // $dt1 = date('Y-m-d 05:00:01', strtotime($date));
        // $dt2 = date('Y-m-d 23:50:01', strtotime($date));

        $return = array('hours' => 0);

        foreach ($checki['data'] as $k => $v) {

            $now_d = substr($v['dop']['start'], 0, 10);

            if ($dd != $now_d)
                continue;

            $return[] = $v;

            if (isset($jobinsp['jobs'][$v['dop']['jobman']][$now_d]['sale_point'])) {

                $v['dop']['sale_point'] = $jobinsp['jobs'][$v['dop']['jobman']][$now_d]['sale_point'];
            } else {
                continue;
            }

            if (isset($v['dop']['sale_point']) && $v['dop']['sale_point'] == $sp && isset($v['dop']['start']) && ( $v['dop']['start'] >= $dt1 && $v['dop']['start'] <= $dt2 )) {
//echo '+2';
            } else {
                continue;
            }

            $return['id_check_for_new_ocenka'][$v['id']] = 1;

            if (!empty($v['dop']['hour_on_job_hand'])) {
                $return['hours'] += $v['dop']['hour_on_job_hand'];
            } elseif (!empty($v['dop']['hour_on_job'])) {
                $return['hours'] += $v['dop']['hour_on_job'];
            }
        }

        if ($return['hours'] == 0)
            throw new \Exception('Количество отработанных часов = 0', 11);

//\f\pa($return);

        return $return;
    }

    /**
     * ищем где работают люди (олд)
     * новая whereJobmansPeriod
     * @param type $db
     * @param type $folder
     * @param type $date_start
     * @param type $date_fin
     * @param type $module_man_job_on_sp
     * @return type
     */
    public static function whereJobmansOnSp($db, $folder = null, $date_start = null, $date_fin = null
    , $module_man_job_on_sp = 'jobman_send_on_sp'
    , $module_spec_naznach_on_sp = '050.job_in_sp'
    ) {

//whereJobmansOnSp( $db, $folder, $date_start, $date_finish );

        if ($folder === null)
            $folder = \Nyos\nyos::$folder_now;

// \f\pa( \Nyos\nyos::$folder_now );
// $re = [];







        /**
         * назначения сорудников на сп
         */
// $jobs = \Nyos\mod\items::getItems($db, $folder, $module_man_job_on_sp, 'show', null);
        $jobs = \Nyos\mod\items::getItemsSimple($db, $module_man_job_on_sp);
//\f\pa($jobs, 2);

        $d = array('jobs' => []);

        foreach ($jobs['data'] as $k => $v) {

// \f\pa($v,2,'','v');
// exit;

            if (
                    ( isset($v['dop']['date'])
// && $date_start >= $v['dop']['date'] 
                    && $v['dop']['date'] <= $date_fin
                    ) &&
                    (!isset($v['dop']['date_finish']) || ( isset($v['dop']['date_finish']) && $date_start <= $v['dop']['date_finish'] && $date_fin >= $v['dop']['date_finish'] ) )
            ) {
                $v['dop']['id'] = $v['id'];
                $v['dop']['d'] = $v;
                $d['jobs'][$v['dop']['date'] . '--' . $v['id']] = $v['dop'];
            }
        }

//\f\pa($d['jobs'], 2,'','jobs');

        $spec = \Nyos\mod\items::getItemsSimple($db, $module_spec_naznach_on_sp);
//\f\pa($spec, 2,'','$spec');

        foreach ($spec['data'] as $k => $v) {

//\f\pa($v);

            if (isset($v['dop']['date']) && $v['dop']['date'] >= $date_start && $v['dop']['date'] <= $date_fin) {
                $v['dop']['id'] = $v['id'];
                $v['dop']['d'] = $v;
                $v['dop']['type2'] = 'spec';
                $d['jobs'][$v['dop']['date'] . '--' . $v['id']] = $v['dop'];
            }
        }

        krsort($d['jobs']);

//\f\pa($d['jobs'], 2,'','jobs');

        $re2 = [];
        $ret = [];
        $ret2 = [];

        foreach ($d['jobs'] as $k => $v) {

            if (isset($last_date[$v['jobman']]))
                $v['date_end'] = date('Y-m-d', strtotime($last_date[$v['jobman']]) - 3600 * 24);

//            \f\pa($date_start);
//            \f\pa($date_fin);
//            \f\pa($v);

            $u_date_start = strtotime($v['date']);

//                if (strtotime($date_start) <= $u_date_start) {
            $ret2['jobs_on_sp'][$v['sale_point']][$v['jobman']] = 1;
//            } else {
//                $ret2['jobs_on_sp'][$v['sale_point']][$v['jobman']] = 'hide';
//            }

            $re2['jobs'][$v['sale_point']][$v['jobman']][$v['date']] = $v;

            $last_date[$v['jobman']] = $v['date'];
        }






        foreach ($re2['jobs'] as $k => $v) {
            foreach ($v as $k1 => $v1) {
                ksort($v1);
//\f\pa($v1);
                $ret2['jobs'][$k][$k1] = $v1;
            }
        }

/// \f\pa($ret2,2,'','$ret2');

        /**
         * выводим список точек по порядку сортировки
         */
        \Nyos\mod\items::$sql_order = ' ORDER BY mi.sort ASC ';
        $points = \Nyos\mod\items::getItemsSimple($db, self::$mod_sale_point);
        foreach ($points['data'] as $k => $v) {
            $ret2['sort'][] = $k;
        }

        return $ret2;
    }

    /**
     * + 191106
     * ищем где работают люди на указанную дату
     * старая версия -> whereJobmansOnSp
     * @param type $db
     * @param type $folder
     * @param type $date_start
     * @param type $date_fin
     * @param type $module_man_job_on_sp
     * @return type
     */
    public static function whereJobmansNowDate($db, $date = null) {

        $job = \Nyos\mod\items::getItemsSimple($db, self::$mod_man_job_on_sp);
        // \f\pa($job,2,'','$job');

        $ar = [];

        foreach ($job['data'] as $k => $v) {
            $ar[$v['dop']['jobman']][$v['dop']['date']] = $v['dop'];
        }

        foreach ($ar as $k => $v) {
            ksort($ar[$k]);
        }

//        \f\pa($ar, 2, '', 'массив пользователей и их перестановок');
//        $ar = array( 188 => $ar[188] );
//        \f\pa($ar, 2, '', 'массив пользователей и их перестановок');

        $now_job = [];

        foreach ($ar as $worker => $dates) {

//            echo '<br/>' . $worker;
//            \f\pa($dates);

            foreach ($dates as $date1 => $array) {

                // echo '<Br/>'.__LINE__.' ++ '.$date1.' <= '.$date;

                if ($date1 <= $date) {

                    // если есть дата конца и она меньше даты поиска то не пишем значение
                    if (isset($array['date_finish']) && $array['date_finish'] <= $date) {

                        if (isset($now_job[$worker]))
                            unset($now_job[$worker]);
                    }
                    // если конец не раньше и не равен дате, то пишем значение
                    else {
                        $now_job[$worker] = $array;
                    }
                }
            }
        }

        $spec = \Nyos\mod\items::getItemsSimple($db, self::$mod_spec_jobday);
        // \f\pa($spec,2,'','spec');

        foreach ($spec['data'] as $k1 => $v1) {
            if (isset($v1['dop']['date']) && $v1['dop']['date'] == $date) {
                $v1['dop']['type'] = 'spec';
                $now_job[$v1['dop']['jobman']] = $v1['dop'];
            }
        }

        // \f\pa($now_job, 2, '', 'массив работников на указанную дату');

        return $now_job;
    }

    /**
     * ищем где работают люди за период
     * старая версия whereJobmansOnSp
     * (текущая старая версия) новая > whereJobmans
     * @param type $db
     * @param type $folder
     * @param type $date_start
     * @param type $date_fin
     * @param type $module_man_job_on_sp
     * @return type
     */
    public static function whereJobmansPeriod($db, $date_start = null, $date_fin = null
    , $module_man_job_on_sp = 'jobman_send_on_sp'
    , $module_spec_naznach_on_sp = '050.job_in_sp'
    ) {














//whereJobmansOnSp( $db, $folder, $date_start, $date_finish );
//        if ($folder === null)
//            $folder = \Nyos\nyos::$folder_now;
// \f\pa( \Nyos\nyos::$folder_now );
// $re = [];
//\f\pa($d['jobs'], 2,'','jobs');




        /*
          $re2 = [];
          $ret = [];
          $ret2 = [];

          foreach ($d['jobs'] as $k => $v) {

          if (isset($last_date[$v['jobman']]))
          $v['date_end'] = date('Y-m-d', strtotime($last_date[$v['jobman']]) - 3600 * 24);

          //            \f\pa($date_start);
          //            \f\pa($date_fin);
          //            \f\pa($v);

          $u_date_start = strtotime($v['date']);

          //                if (strtotime($date_start) <= $u_date_start) {
          $ret2['jobs_on_sp'][$v['sale_point']][$v['jobman']] = 1;
          //            } else {
          //                $ret2['jobs_on_sp'][$v['sale_point']][$v['jobman']] = 'hide';
          //            }

          $re2['jobs'][$v['sale_point']][$v['jobman']][$v['date']] = $v;

          $last_date[$v['jobman']] = $v['date'];
          }

          foreach ($re2['jobs'] as $k => $v) {
          foreach ($v as $k1 => $v1) {
          ksort($v1);
          //\f\pa($v1);
          $ret2['jobs'][$k][$k1] = $v1;
          }
          }
         */

        $return = [];

        for ($i = 0; $i <= 300; $i++) {

            $dt = date('Y-m-d', strtotime($date_start . ' + ' . $i . ' day'));

            if ($date_fin <= $dt)
                break;

// echo '<br/>' . $date_fin . ' + ' . $date_start . ' + ' . $dt;
// echo '<br/>' . $dt;

            $return[$dt] = self::whereJobmansNowDate($db, $dt);
// \f\pa($ee);
        }

        return $return;
    }

    /**
     * где работают сотрудники
     * @param type $db
     * @param type $date_start
     * @param type $date_fin
     * пустой (значит 1 день сегодня) или не пустой, тогда промежуток
     * @return type
     */
    public static function whereJobmans($db, $date_start = null, $date_fin = null) {

        echo '<br/>' . __FUNCTION__ . ' ( [' . $date_start . '] , [' . $date_fin . '] ) #' . __LINE__;

        if (empty($date_start))
            $date_start = '2019-09-01';

        if (empty($date_fin))
            $date_fin = $date_start;

        $return = [];

        $i = 0;
        $dt = date('Y-m-d', strtotime($date_start . ' + ' . $i . ' day'));
        $return[$dt] = self::whereJobmansNowDate($db, $dt);

        return $return;
    }

    /**
     * получаем массив дат и должностей кто сколько получает за период (старт - стоп)
     * дата - точка - должность - сумма за час
     * @param type $db
     * @param type $date_start
     * @param type $date_finish
     * @param type $module_man_job_on_sp
     * @param type $mod_spec_jobday
     * @return type
     */
    public static function getSalarisPeriod($db, string $dt_start, string $dt_finish, $mod_salary = '071.set_oplata', $mod_sale_point = 'sale_point') {

        if (!empty(self::$cash['salaris_all']))
            return self::$cash['salaris_all'];

        $sps = \Nyos\mod\items::getItemsSimple($db, self::$mod_sale_point);
// \f\pa($sps, 2, '', '$sps');

        $salary = \Nyos\mod\items::getItemsSimple($db, self::$mod_salary);
// \f\pa($salary, 2, '', '$salary');

        $ss = [];

        foreach ($salary['data'] as $k1 => $v1) {

            $v1['dop']['id'] = $v1['id'];
// $ss[$v1['dop']['date']][$v1['dop']['sale_point']][$v1['dop']['dolgnost']][$v1['id']] = $v1['dop'];
            $ss[$v1['dop']['date']][( ( isset($sps['data'][$v1['dop']['sale_point']]['head']) && $sps['data'][$v1['dop']['sale_point']]['head'] == 'default' ) ? 'default' : $v1['dop']['sale_point'] )][$v1['dop']['dolgnost']][$v1['id']] = $v1['dop'];
        }

        ksort($ss);

// \f\pa($ss, '', '', '$ss');

        $now_price = [];


        foreach ($ss as $dt => $ar1) {

            if ($dt <= $dt_start) {
                
            } else {
                break;
            }

//            echo '<br/>1--' . $dt ;
//            echo '<br/>' . __LINE__;

            foreach ($ar1 as $sp => $ar2) {

//                echo '<br/>2--' . $sp;
//                echo '<br/>' . __LINE__;

                foreach ($ar2 as $dolgn => $ar3) {

// echo '<br/>' . __LINE__;
// $now_price[$sp][$dolgn] = $ar3['id'];
                    $now_price[$sp][$dolgn] = $ar3;
                }
            }
        }

// \f\pa($now_price);

        self::$cash['salaris_all'] = [];

        for ($i = 0; $i <= 370; $i++) {

            $nd = date('Y-m-d', strtotime($dt_start . ' +' . $i . ' day'));

            self::$cash['salaris_all'][$nd] = $now_price;
// echo '<br/>' . $nd;

            if ($nd == $dt_finish)
                break;
        }

//\f\pa($price_time);

        return self::$cash['salaris_all'];

//return $ret2;
    }

    /**
     * получаем массив по датам когда кто сколько получает
     * @param type $db
     * @param type $date_start
     * @param type $date_finish
     * @param type $module_man_job_on_sp
     * @param type $mod_spec_jobday
     * @return type
     */
    public static function getSalarisNow($db, int $sp, int $dolgn, string $date, $mod_dolgn = '061.dolgnost', $mod_salary = '071.set_oplata') {

        echo '[' . $sp . '|' . $date . '|' . $dolgn . ']';
        $d = date('Y-m-d', strtotime($date));

//        $dolgn = \Nyos\mod\items::getItemsSimple($db, $mod_dolgn);
//        \f\pa($dolgn,2,'','$dolgn');

        $salary = \Nyos\mod\items::getItemsSimple($db, $mod_salary);
// \f\pa($salary, 2, '', '$salary');

        $ar_salary = [];
        foreach ($salary['data'] as $k => $v) {

            $ar_salary[] = $v['dop'];
        }

        usort($ar_salary, "\\f\\sort_ar_date");
        \f\pa($ar_salary, 2, '', '$ar_salary');

        return $ret2;
    }

    /**
     * формируем массив данных для оценки
     * @param type $db
     * @param type $sp
     * @param type $date
     * @return type
     */
    public static function readVarsForOcenkaDays($db, $sp, $date) {

        $return = array(
            'txt' => '',
            // текст о времени исполнения
            'time' => '',
            // смен в дне
            'smen_in_day' => 0,
            // часов за день отработано
            'hours' => 0,
            // больше или меньше нормы сделано сегодня ( 1 - больше или равно // 0 - меньше // 2 не получилось достать )
            'oborot_bolee_norm' => 2,
            // сумма денег на руки от количества смен и процента на ФОТ
            'summa_na_ruki' => 0,
            // рекомендуемая оценка управляющего
// если 0 то нет оценки
            'ocenka' => 0,
            'ocenka_naruki' => 0
        );

        $return['date'] = date('Y-m-d', strtotime($date));
        $return['sp'] = $return['sale_point'] = $sp;

// id items для записи авто оценки

        /**
         * достаём чеки за день
         */
        if (1 == 2) {

            $id_items_for_new_ocenka = [];

            \f\timer::start();


// $return['hours'] = \Nyos\mod\JobDesc::getTimesChecksDay($db, $sp, $e) getOborotSp($db, $return['sp'], $return['date']);
            $times_day = \Nyos\mod\JobDesc::getTimesChecksDay($db, $return['sp'], $return['date']);

            \f\pa($times_day, 2, '', '$times_day');

            $return['hours'] = $times_day['hours'];
            $id_items_for_new_ocenka = $times_day['id_check_for_new_ocenka'];
// die($return['hours']);

            $return['time'] .= PHP_EOL . ' достали время работы по чекам за день : ' . \f\timer::stop()
                    . PHP_EOL . $return['hours'];
        }

//        if (!class_exists('Nyos\mod\JobDesc'))
//            require_once DR . DS . 'vendor/didrive_mod/jobdesc/class.php';
//        echo '<br/>' . __FILE__ . ' ' . __LINE__;
//        \f\pa($return);
//        die(__LINE__);

        /**
         * достаём нормы на день
         */
        if (1 == 1) {
            \f\timer::start();

            $now_norm = \Nyos\mod\JobDesc::whatNormToDay($db, $return['sp'], $return['date']);
//\f\pa($now_norm,2,'','$now_norm '.$return['sp'].' / '.$return['date'] );

            if ($now_norm === false)
                throw new \Exception('Нет плановых данных (дата)', 12);

            foreach ($now_norm as $k => $v) {
//$return['txt'] .= '<br/><nobr>[norm_' . $k . '] - ' . $v . '</nobr>';
                $return['norm_' . $k] = $v;
//echo '<br>'.PHP_EOL.'$return[\'norm_' . $k.'] = '. $v;
            }

            $return['time'] .= PHP_EOL . ' нормы за день время: ' . \f\timer::stop();
//\f\pa($return); die();

            if (empty($return['norm_date'])) {
// $error .= PHP_EOL . 'Нет плановых данных (дата)';
                throw new \Exception('Нет плановых данных (дата)', 12);
            } elseif (
// empty($return['norm_vuruchka']) 
                    empty($return['norm_vuruchka_on_1_hand']) || empty($return['norm_time_wait_norm_cold']) || empty($return['norm_procent_oplata_truda_on_oborota']) || empty($return['norm_kolvo_hour_in1smena'])
            ) {
                throw new \Exception('Не все плановые данные по ТП указаны', 13);
//$error .= PHP_EOL . 'Не все плановые данные по ТП указаны';
            }
        }

        /**
         * достаём оборот за сегодня
         */
        if (1 == 1) {
            \f\timer::start();
// $return['oborot'] = \Nyos\mod\JobDesc::getOborotSp($db, $_REQUEST['sp'], $_REQUEST['date']);
            $return['oborot'] = \Nyos\mod\IikoOborot::getDayOborot($db, $_REQUEST['sp'], $_REQUEST['date']);
// \f\pa($return);
// echo
            $return['time'] .= PHP_EOL . ' достали обороты за день: ' . \f\timer::stop()
                    . PHP_EOL . $return['oborot'];
        }

        /**
         * достаём время ожидания за сегодня
         */
        if (1 == 1) {

            \f\timer::start();
            $timeo = \Nyos\mod\JobDesc::getTimeOgidanie($db, $_REQUEST['sp'], $_REQUEST['date']);

            // \f\pa($timeo);
//\f\pa($timeo);
            $return['time'] .= PHP_EOL . ' достали время ожидания за день: ' . \f\timer::stop();
            foreach ($timeo as $k => $v) {
                $return['time'] .= PHP_EOL . $k . ' > ' . $v;
                $return[$k] = $v;
            }
        }

//        echo '<fieldset style="border: 1px solid gray; padding: 5px; margin: 5px;" ><legend>'
//        . 'достаём суммарное время работы сотрудников за сегодня</legend>';
        if (1 == 1) {

//            echo '<hr>';
//            echo __FILE__.' #'.__LINE__;
//            echo '<hr>';
//            echo '<hr>';
            // $sp

            $worker_on_date = self::whereJobmansNowDate($db, $return['date']);
            // \f\pa($worker_on_date, 2, '', '$worker_on_date');

            $ds = strtotime($return['date'] . ' 09:00:00');
            $df = strtotime($return['date'] . ' 03:00:00 +1 day');

            $ds1 = date('Y-m-d H:i:s', $ds);
            // echo '<Br/>'.date('Y-m-d H:i:s', $ds );
            $df1 = date('Y-m-d H:i:s', $df);
            // echo '<Br/>'.date('Y-m-d H:i:s', $df );

            $checks = \Nyos\mod\items::getItemsSimple($db, self::$mod_checks);
            $return['checks_for_new_ocenka'] = [];

            foreach ($checks['data'] as $k3 => $v3) {

                if (
                        isset($v3['dop']['jobman']) &&
                        isset($v3['dop']['start']) &&
                        $v3['dop']['start'] >= $ds1 &&
                        $v3['dop']['start'] <= $df1 &&
                        isset($worker_on_date[$v3['dop']['jobman']]['sale_point']) &&
                        $worker_on_date[$v3['dop']['jobman']]['sale_point'] == $sp
                ) {

                    //\f\pa($v3['dop']);
                    //break;
                    // часы отредактированные в ручную
                    if (!empty($v3['dop']['hour_on_job_hand'])) {
                        $return['checks_for_new_ocenka'][] = $v3['id'];
                        $return['hours'] += $v3['dop']['hour_on_job_hand'];
                    }
                    // авторасчёт количества часов
                    elseif (!empty($v3['dop']['hour_on_job'])) {
                        $return['checks_for_new_ocenka'][] = $v3['id'];
                        $return['hours'] += $v3['dop']['hour_on_job'];
                    }
                }
            }

//            $return['smen_in_day'] = round($return['hours'] / $return['norm_kolvo_hour_in1smena'], 1);
//            
//            if( !empty($return['oborot']) && !empty($return['smen_in_day']) )
//            $return['summa_na_ruki'] = ceil( $return['oborot'] / $return['smen_in_day'] );
//
//            // если на руки больше нормы то оценка 5
//            if ( $return['summa_na_ruki'] >= $return['norm_vuruchka_on_1_hand']) {
//                $return['ocenka_naruki'] = 5;
//            }
//            // если на руки меньше нормы то оценка 3
//            else {
//                $return['ocenka_naruki'] = 3;
//            }
            // $ee = self::getTimesChecksDay($db, $return['sp'], $return['date']);
            // \f\pa($ee, 2, '', '$ee = self::getTimesChecksDay($db, $ar[\'sp\'], $ar[\'date\']);');
            // $return['hours_job_days'] = $ee;
        }
        // echo '</fieldset>';

        return \f\end3('ok', true, $return);

        // return $return;
    }

    public static function getDaysOcenkaNo($db, $start_day = '2019-09-01') {

        // echo $start_day;
        $last_day = date('Y-m-d', $_SERVER['REQUEST_TIME'] - 3600 * 24 );

        $e = \Nyos\mod\items::getItemsSimple($db, self::$mod_ocenki_days);
        // \f\pa($e,2,'','$e');

        $r = [];

                echo ' -+ '.$start_day;
                echo ' -- '.$last_day;
                echo '<br/>';
        
        foreach ($e['data'] as $k => $v) {

            if (isset($v['dop'])) {
                
                if ( isset($v['dop']['date']) && ( $v['dop']['date'] >= $last_day || $v['dop']['date'] < $start_day ) )
                    continue;

                echo ' -- '.$v['dop']['date'];
                
                $r[$v['dop']['date']][$v['dop']['sale_point']] = $v['dop'];
                
            }
        }

        ksort($r);
        \f\pa($r, 2, '', '$r');

        // $start_day
        // echo '<br/>край день' . $last_day;

        $r2 = [];

        for ($nn = 0; $nn <= 100; $nn++) {

            $now_date = date('Y-m-d', strtotime($start_day . ' +' . $nn . ' day'));

            echo '<br/>$now_date ' . $now_date;

            foreach ($r as $sp => $dates) {

                if (!isset($dates[$now_date])) {
                    echo '<br/>- d sp ' . $sp;
                    $r2[$sp][$now_date] = 1;
                } else {
                    echo '<br/>+ d sp ' . $sp;
                }
            }

            if ($last_day == $now_date) {
                break;
            }
        }


        return \f\end3('ok', true, $r2);
        //return \f\end3('ok', true, $return);
        // return $return;
    }

    /**
     * записываем новые авто оценки для смен
     * @param type $db
     * @param type $array_checks
     * @param type $ocenka
     * @return type
     */
    public static function recordNewAutoOcenkiDay($db, $array_checks, $ocenka) {

        // echo '<br/>'.__FUNCTION__;
        // die();
        // строка для удаления
        $check_string = '';
        // массив для удаления
        $check_ar = // масив для вставки новых данных
                $rows_in = [];

        $nn = 1;
        foreach ($array_checks as $check) {
            $check_string .= (!empty($check_string) ? ' OR ' : '' ) . ' `id_item` = :check' . $nn . ' ';
            $check_ar[':check' . $nn] = $check;

            $rows_in[] = ['id_item' => $check];

            $nn++;
        }

        $ff = $db->prepare('DELETE FROM `mitems-dops` WHERE ( ' . $check_string . ' ) AND `name` = \'ocenka_auto\' ;');
        $ff->execute($check_ar);

        \f\db\sql_insert_mnogo($db, 'mitems-dops', $rows_in, ['name' => 'ocenka_auto', 'value' => $ocenka]);

//        \f\db\db2_insert( $db, 'mitems-dops', array(
//            'id_item' => (int) $_REQUEST['work_id'],
//            'name' => 'date_finish',
//            'value_date' => date('Y-m-d', strtotime($_REQUEST['date_end']))
//                )
//        );

        return;
    }

    /**
     * расчитываем какая оценка дня опираясь на массив данных из функции self::compileVarsForOcenkaDay($db, $sp, $date)
     * 1911
     * @param type $db
     * @param type $ar
     * @return type
     */
    public static function calcOcenkaDay($db, $return) {

        // \f\pa($return, 2, '', '$return входящие данные ');

        $text = '';
// текст промежуток
        $text_s = '<br/>';


//        echo '<hr>' . __FILE__ . ' #' . __LINE__
//        . '<br/>' . __FUNCTION__
//        . '<hr>';
//        if (empty($return['date']))
//            throw new Exception('нет даты расчёта оценки', 201);



        $vv1 = [
            'date' => '',
            'sp' => '',
            'norm_vuruchka_on_1_hand' => '',
            'norm_time_wait_norm_cold' => '',
            'norm_time_wait_norm_hot' => '',
            'norm_time_wait_norm_delivery' => '',
            'oborot' => '',
            'hours' => '',
            // // 'smen_in_day' => '',
            // // 'summa_na_ruki' => '',
            'timeo_cold' => 'время хол цех',
            'timeo_hot' => 'время гор цех',
            'timeo_delivery' => 'время доставка',
                // [oborot_bolee_norm] => 2
                // [ocenka] => 3
                // [ocenka_naruki] => 5
                // [sale_point] => 1
                // [norm_date] => 2019-09-30
                // [norm_sale_point] => 1
                // [norm_procent_oplata_truda_on_oborota] => 12
                // [norm_kolvo_hour_in1smena] => 16
                // 'timeo_date] => 2019-11-01
                // [timeo_sale_point] => 1
                // [ocenka_time] => 3
        ];



        foreach ($vv1 as $vv => $vv_text) {

//            \f\pa($vv);
//            \f\pa($vv_text);
//            \f\pa($return[$vv]);

            if (empty($return[$vv])) {

                if ($vv == 'timeo_hot') {

                    $norms_def = \Nyos\mod\JobDesc::whatNormToDayDefault($db);
                    $return[$vv] = $norms_def[2];
                } elseif ($vv == 'timeo_cold') {

                    $norms_def = \Nyos\mod\JobDesc::whatNormToDayDefault($db);
                    $return[$vv] = $norms_def[1];
                } elseif ($vv == 'timeo_delivery') {

                    $norms_def = \Nyos\mod\JobDesc::whatNormToDayDefault($db);
                    $return[$vv] = $norms_def[3];
                } else {

                    if (!empty($vv) && $vv == 'oborot') {
                        throw new \Exception('нет нет оборота по точке (' . $vv_text . ')', 201);
                    } else {
                        throw new \Exception('нет значения ' . $vv . ' (' . $vv_text . ')', __LINE__);
                    }
                }
            }
        }

//        \f\pa($return);

        $re = [
            'sp' => $return['sp'],
            'date' => $return['date'],
            'ocenka_time' => 0,
            //$return['ocenka_oborot'] => 5;
            'ocenka_naruki' => 0,
            'ocenka' => 0
        ];











        /**
         * вычисление оценки на руки
         */
        $return['smen_in_day'] = round($return['hours'] / $return['norm_kolvo_hour_in1smena'], 1);

        if (!empty($return['oborot']) && !empty($return['smen_in_day']))
            $return['summa_na_ruki'] = ceil($return['oborot'] / $return['smen_in_day']);

        $text .= $return['summa_na_ruki'] . ' (сейчас) >= (норма) ' . $return['norm_vuruchka_on_1_hand'] . $text_s;

        // если на руки больше нормы то оценка 5
        if ($return['summa_na_ruki'] >= $return['norm_vuruchka_on_1_hand']) {
            $re['ocenka_naruki'] = 5;
            $text .= 'сумма на руки больше нормы ' . $text_s;
        }
        // если на руки меньше нормы то оценка 3
        else {
            $re['ocenka_naruki'] = 3;
            $text .= 'сумма на руки НЕ больше нормы ' . $text_s;
        }

        // \f\pa($return);

        /*
          $ocenka = 5;
         */

        $ar = $return;

// время ожидания
        if (1 == 1) {

// время ожидания // холодный цех
            $time_type = 'cold';
            if (
                    !empty($ar['norm_time_wait_norm_' . $time_type]) &&
                    !empty($ar['timeo_' . $time_type])) {
                if (
                        $ar['timeo_' . $time_type] <= $ar['norm_time_wait_norm_' . $time_type]
                ) {
//            $ocenka = 5;
                    $text .= 'время ожидания ' . $time_type . ' норм ( 5 )' . $text_s;
                } else {
                    $re['ocenka_time'] = $re['ocenka'] = 3;
                    $text .= 'время ожидания ' . $time_type . ' не норм ( 3 )' . $text_s;
                }
                $text .= $ar['timeo_' . $time_type] . ' <= ' . $ar['norm_time_wait_norm_' . $time_type] . $text_s;
            } else {
                $text .= 'данных цеха ' . $time_type . ' не найдено (норма или текущее значение) ' . $text_s;
            }

// время ожидания // горячий цех
            $time_type = 'hot';
            if (
                    !empty($ar['norm_time_wait_norm_' . $time_type]) &&
                    !empty($ar['timeo_' . $time_type])) {
                if (
                        $ar['timeo_' . $time_type] <= $ar['norm_time_wait_norm_' . $time_type]
                ) {
//                $ocenka = 5;
                    $text .= 'время ожидания ' . $time_type . ' норм ( 5 )' . $text_s;
                } else {
                    $re['ocenka_time'] = $re['ocenka'] = 3;
                    $text .= 'время ожидания ' . $time_type . ' не норм ( 3 )' . $text_s;
                }
                $text .= $ar['timeo_' . $time_type] . ' <= ' . $ar['norm_time_wait_norm_' . $time_type] . $text_s;
            } else {
                $text .= 'данных цеха ' . $time_type . ' не найдено (норма или текущее значение) ' . $text_s;
            }

// время ожидания // доставка
            $time_type = 'delivery';
            if (
                    !empty($ar['norm_time_wait_norm_' . $time_type]) &&
                    !empty($ar['timeo_' . $time_type])) {
                if (
                        $ar['timeo_' . $time_type] <= $ar['norm_time_wait_norm_' . $time_type]
                ) {
//            $ocenka = 5;
                    $text .= 'время ожидания ' . $time_type . ' норм ( 5 )' . $text_s;
                } else {
                    $re['ocenka_time'] = $re['ocenka'] = 3;
                    $text .= 'время ожидания ' . $time_type . ' не норм ( 3 )' . $text_s;
                }
                $text .= $ar['timeo_' . $time_type] . ' <= ' . $ar['norm_time_wait_norm_' . $time_type] . $text_s;
            } else {
                $text .= 'данных цеха ' . $time_type . ' не найдено (норма или текущее значение) ' . $text_s;
            }
        }

// оцениваем количество денег на руки
// отработанное время
//        $jobs = self::getJobmansOnTime1910($db, $ar['date'], $ar['date']);
//        \f\pa($jobs);
//        $checks = \Nyos\mod\items::getItemsSimple($db, self::$mod_man_job_on_sp );
//        \f\pa($checks);
//        $ocenka = 3;

        return \f\end3($text, true, $re);
// return \f\end3('нет оценки', false, ['ocenka' => $ocenka]);
    }

    public static function getSetupJobmanOnSp($db, $date_start, $date_finish = null, $module_man_job_on_sp = 'jobman_send_on_sp', $mod_spec_jobday = '050.job_in_sp') {

        echo '<hr>' . __FILE__ . ' #' . __LINE__
        . '<br/>' . __FUNCTION__
        . '<hr>';

//        \f\pa($date_start);
//        \f\pa($date_finish);

        $ee = self::whereJobmans($db, $date_start, $date_start);
        \f\pa($ee, 2, '', '$ee');

        $plus_minus_checks = \Nyos\mod\JobBuh::getChecksMinusPlus($db, $date_start, $date_finish);
        // \f\pa($plus_minus_checks, 2, '', '$plus_minus_checks');
//        foreach ($plus_minus_checks['items'] as $jobman => $v1) {
//            foreach ($v1 as $k => $v) {
//                $checks[$jobman][$v['date']] = $v;
//            }
//        }
//        // $plus_minus_checks = '';
//\f\pa($checks, 2, '', '$checks');

        $ocenki = [];

        foreach ($plus_minus_checks['items'] as $jobman => $v1) {
            foreach ($v1 as $k => $item) {
                if (isset($item['ocenka'])) {
                    $ocenki[$jobman][$item['date']] = $item['ocenka'];
                } elseif (isset($item['ocenka_auto'])) {
                    $ocenki[$jobman][$item['date']] = $item['ocenka_auto'];
                }
            }
        }

// \f\pa($ocenki);

        $return = [];

        if (empty($date_finish)) {
            $ds = $df = date('Y-m-d', strtotime($date_start));
        } else {
            $ds = date('Y-m-d', strtotime($date_start) - 3600 * 24);
            $df = date('Y-m-d', strtotime($date_finish));
        }

        /**
         * тащим спец назначения
         */
        if (1 == 1) {
            $spec_day = \Nyos\mod\items::getItemsSimple($db, $mod_spec_jobday);
//\f\pa($spec_day, 2, '', '$spec_day');
            $spec = [];
            foreach ($spec_day['data'] as $k => $v) {
                if ($v['dop']['date'] >= $ds && $v['dop']['date'] <= $df) {
                    $spec[$v['dop']['jobman']][$v['dop']['date']] = $v['dop'];
                }
            }
//\f\pa($spec, 2, '', '$spec');
        }

        /**
         * назначения сорудников на сп
         */
        $jobs = \Nyos\mod\items::getItemsSimple($db, self::$mod_man_job_on_sp);
//        \f\pa($jobs, 2, '', '$jobs');


        $jobin = [];

        foreach ($jobs['data'] as $k => $v) {

// if ($v['dop']['date'] >= $ds && $v['dop']['date'] <= $df) {
            // $jobin[$v['dop']['jobman']][$v['dop']['date']][] = $v['dop'];
            $v['dop']['fl'] = __FILE__ . ' #' . __LINE__;
            $jobin[$v['dop']['date']][] = $v['dop'];

            /*
              (
              [jobman] => 187
              [sale_point] => 1
              [dolgnost] => 2
              [date] => 2019-05-01
              )
             */
//\f\pa($v['dop']);


            /*
              $nd = date('Y-m-d', strtotime($v['dop']['date']) + 3600 * 24 * 14);
              $v['dop']['date'] = $nd;
              $v['dop']['dolgnost'] = rand(10, 50);
              $jobin[$v['dop']['jobman']][$nd] = $v['dop'];

              $nd = date('Y-m-d', strtotime($v['dop']['date']) + 3600 * 24 * 7);
              $v['dop']['date'] = $nd;
              $v['dop']['dolgnost'] = rand(10, 50);
              $jobin[$v['dop']['jobman']][$nd] = $v['dop'];
             */
// }
        }

//\f\pa($jobin, 2, '', '$jobin');

        $j = [];

        foreach ($jobin as $k => $v) {

            if ($ds != $df)
                ksort($v);

            $j[$k] = $v;
        }

//      \f\pa($j, 2, '', '$j');

        $j2 = [];

        foreach ($j as $jobman => $v) {

// $start = false;
            $param_start = null;

            foreach ($v as $date => $v2) {

// if ($start === false && \strtotime($date) <= \strtotime($ds)) {
                if (\strtotime($date) <= \strtotime($ds)) {
                    $param_start = $v2;
                }

                if ($date >= $ds) {
// $start = true;
                    break;
                }
            }

            if (!empty($param_start)) {
                $j2[$jobman][$param_start['date']] = $param_start;
            }
        }

//\f\pa($j2, 2, '', '$j2');

        /**
         * если ищем несколько дат
         */
        if ($ds != $df) {

            $j3 = [];

            foreach ($j2 as $jobman => $date_ar) {
                foreach ($date_ar as $date => $ar) {
                    for ($i = 1; $i <= 35; $i++) {

                        $n = date('Y-m-d', strtotime($ds) + 3600 * 24 * $i);

                        if ($n > $df)
                            break;

//                    if (isset($j[$jobman][$n]))
//                        $ar = $j[$jobman][$n];
                        if (isset($j[$n]))
                            $ar = $j[$n];

                        $return['jobs_on_sp'][$ar['sale_point']][$ar['jobman']] = 1;

                        $a2 = $ar;

                        $a2['data_from_d'] = $a2['date'];
                        $a2['date'] = $n;

                        if (isset($spec[$jobman][$n])) {
                            $a2['sale_point'] = $spec[$jobman][$n]['sale_point'];
                            $a2['dolgnost'] = $spec[$jobman][$n]['dolgnost'];

                            $a2['type'] = 'spec';
                        }

                        $r[] = $a2;
                    }
                }
            }

            if ($ds != $df)
                usort($r, "\\f\\sort_ar_date");

            foreach ($r as $k => $v) {

                $salary = \Nyos\mod\JobDesc::getSalaryJobman($db, $v['sale_point'], $v['dolgnost'], $v['date']);
// $v['salary'] = $salary;
// $v['check'] = $checks[$v['jobman']][$v['date']] ?? 0 ;

                $v['hour'] = 0;
                if (isset($checks[$v['jobman']][$v['date']]['hour_on_job']) || isset($checks[$v['jobman']][$v['date']]['hour_on_job_hand'])) {
                    $v['hour'] = $checks[$v['jobman']][$v['date']]['hour_on_job_hand'] ?? $checks[$v['jobman']][$v['date']]['hour_on_job'];
                }

                $v['ocenka'] = $ocenki[$v['jobman']][$v['date']] ?? 0;

                $v['price_hour'] = 0;

// if (isset($salary['ocenka-hour-' . $v['ocenka']])) {
//\f\pa($v);
//\f\pa($salary,2,'','$salary');
// $v['prices'] = $salary;

                if (!empty($salary['ocenka-hour-base'])) {
                    $v['price_hour'] = $salary['ocenka-hour-base'] + ( $salary['if_kurit'] ?? 0 );
                    $v['ocenka_skip'] = 1;
                } elseif (isset($salary['ocenka-hour-' . $v['ocenka']])) {
                    $v['price_hour'] = $salary['ocenka-hour-' . $v['ocenka']] + ( $salary['if_kurit'] ?? 0 );
                }

                if ($v['price_hour'] != 0 && $v['hour'] != 0) {
                    $v['summa'] = ceil($v['price_hour'] * $v['hour']);
                }

                $return['jobs'][$v['jobman']][$v['date']] = $v;
            }

//\f\pa($return);
        }
        /**
         * если ищем одну дату
         */ else {
            foreach ($j2 as $jobman => $v2) {
                foreach ($v2 as $date => $v) {
                    $return['jobs'][$v['jobman']][$ds] = $v;
                }
            }
        }

//\f\pa($return);

        return $return;
    }

    /*
      public static function getChecksToday( $db, $date ) {

      $checks = \Nyos\mod\items::getItemsSimple($db, $module);

      return $return;
      }
     */

    /**
     * получаем сотрудников кто работал в указанном промежутке
     * @param type $db
     * @param type $date_start
     * @param type $date_finish
     * @param type $module_man_job_on_sp
     * @param type $mod_spec_jobday
     * @return type
     */
    public static function getJobmansOnTime($db, $date_start, $date_finish = null, $module_man_job_on_sp = 'jobman_send_on_sp', $mod_spec_jobday = '050.job_in_sp') {

        echo '<br/>' . $date_start . ' , ' . $date_finish . '<br/>';

        $checks_all = \Nyos\mod\JobBuh::getChecksMinusPlus($db, $date_start, $date_finish);

// \f\pa($plus_minus_checks, 2, '', '$plus_minus_checks');
        foreach ($checks_all['items'] as $jobman => $v1) {
            foreach ($v1 as $k => $v) {
                $checks[$jobman][$v['date']]['checks'][] = $v;
            }
        }

//        // $plus_minus_checks = '';
// echo '<br/>'.sizeof($checks); echo '<br/>';
//        \f\pa($checks, 2, '', '$checks');
// $ocenki = [];

        foreach ($checks_all['items'] as $jobman => $v1) {
            foreach ($v1 as $k => $item) {
                if (!empty($item['ocenka'])) {
                    $checks[$jobman][$item['date']]['ocenka'] = $item['ocenka'];
                } elseif (!empty($item['ocenka_auto'])) {
                    $checks[$jobman][$item['date']]['ocenka'] = $item['ocenka_auto'];
                }
            }
        }

// \f\pa($ocenki);
// \f\pa($checks, 2, '', '$checks');


        $return = [];

        if (empty($date_finish)) {
            $ds = $df = date('Y-m-d', strtotime($date_start));
        } else {
            $ds = date('Y-m-d', strtotime($date_start) - 3600 * 24);
            $df = date('Y-m-d', strtotime($date_finish));
        }

        /**
         * тащим спец назначения
         */
        if (1 == 1) {
            $spec_day = \Nyos\mod\items::getItemsSimple($db, $mod_spec_jobday);
//\f\pa($spec_day, 2, '', '$spec_day');
// $spec = [];
            foreach ($spec_day['data'] as $k => $v) {
                if ($v['dop']['date'] >= $ds && $v['dop']['date'] <= $df) {
// $spec[$v['dop']['jobman']][$v['dop']['date']] = $v['dop'];
                    $v['dop']['type'] = 'spec';
                    $checks[$v['dop']['jobman']][$v['dop']['date']]['checks'][] = $v['dop'];
                }
            }
//\f\pa($spec, 2, '', '$spec');
        }

//        \f\pa($checks, 2, '', '$checks');

        return $checks;










        /**
         * назначения сорудников на сп
         */
        $jobs = \Nyos\mod\items::getItemsSimple($db, $module_man_job_on_sp);
//        \f\pa($jobs, 2, '', '$jobs');


        $jobin = [];

        foreach ($jobs['data'] as $k => $v) {

// if ($v['dop']['date'] >= $ds && $v['dop']['date'] <= $df) {

            $jobin[$v['dop']['jobman']][$v['dop']['date']] = $v['dop'];

            /*
              (
              [jobman] => 187
              [sale_point] => 1
              [dolgnost] => 2
              [date] => 2019-05-01
              )
             */
//\f\pa($v['dop']);


            /*
              $nd = date('Y-m-d', strtotime($v['dop']['date']) + 3600 * 24 * 14);
              $v['dop']['date'] = $nd;
              $v['dop']['dolgnost'] = rand(10, 50);
              $jobin[$v['dop']['jobman']][$nd] = $v['dop'];

              $nd = date('Y-m-d', strtotime($v['dop']['date']) + 3600 * 24 * 7);
              $v['dop']['date'] = $nd;
              $v['dop']['dolgnost'] = rand(10, 50);
              $jobin[$v['dop']['jobman']][$nd] = $v['dop'];
             */
// }
        }

//\f\pa($jobin, 2, '', '$jobin');

        $j = [];

        foreach ($jobin as $k => $v) {

            if ($ds != $df)
                ksort($v);

            $j[$k] = $v;
        }

//      \f\pa($j, 2, '', '$j');

        $j2 = [];

        foreach ($j as $jobman => $v) {

// $start = false;
            $param_start = null;

            foreach ($v as $date => $v2) {

// if ($start === false && \strtotime($date) <= \strtotime($ds)) {
                if (\strtotime($date) <= \strtotime($ds)) {
                    $param_start = $v2;
                }

                if ($date >= $ds) {
// $start = true;
                    break;
                }
            }

            if (!empty($param_start)) {
                $j2[$jobman][$param_start['date']] = $param_start;
            }
        }

//\f\pa($j2, 2, '', '$j2');

        /**
         * если ищем несколько дат
         */
        if ($ds != $df) {

            $j3 = [];

            foreach ($j2 as $jobman => $date_ar) {
                foreach ($date_ar as $date => $ar) {
                    for ($i = 1; $i <= 35; $i++) {

                        $n = date('Y-m-d', strtotime($ds) + 3600 * 24 * $i);

                        if ($n > $df)
                            break;

//                    if (isset($j[$jobman][$n]))
//                        $ar = $j[$jobman][$n];
                        if (isset($j[$n]))
                            $ar = $j[$n];

                        $return['jobs_on_sp'][$ar['sale_point']][$ar['jobman']] = 1;

                        $a2 = $ar;

                        $a2['data_from_d'] = $a2['date'];
                        $a2['date'] = $n;

                        if (isset($spec[$jobman][$n])) {
                            $a2['sale_point'] = $spec[$jobman][$n]['sale_point'];
                            $a2['dolgnost'] = $spec[$jobman][$n]['dolgnost'];

                            $a2['type'] = 'spec';
                        }

                        $r[] = $a2;
                    }
                }
            }

            if ($ds != $df)
                usort($r, "\\f\\sort_ar_date");

            foreach ($r as $k => $v) {

                $salary = \Nyos\mod\JobDesc::getSalaryJobman($db, $v['sale_point'], $v['dolgnost'], $v['date']);
// $v['salary'] = $salary;
// $v['check'] = $checks[$v['jobman']][$v['date']] ?? 0 ;

                $v['hour'] = 0;
                if (isset($checks[$v['jobman']][$v['date']]['hour_on_job']) || isset($checks[$v['jobman']][$v['date']]['hour_on_job_hand'])) {
                    $v['hour'] = $checks[$v['jobman']][$v['date']]['hour_on_job_hand'] ?? $checks[$v['jobman']][$v['date']]['hour_on_job'];
                }

                $v['ocenka'] = $ocenki[$v['jobman']][$v['date']] ?? 0;

                $v['price_hour'] = 0;

// if (isset($salary['ocenka-hour-' . $v['ocenka']])) {
//\f\pa($v);
//\f\pa($salary,2,'','$salary');
// $v['prices'] = $salary;

                if (!empty($salary['ocenka-hour-base'])) {
                    $v['price_hour'] = $salary['ocenka-hour-base'] + ( $salary['if_kurit'] ?? 0 );
                    $v['ocenka_skip'] = 1;
                } elseif (isset($salary['ocenka-hour-' . $v['ocenka']])) {
                    $v['price_hour'] = $salary['ocenka-hour-' . $v['ocenka']] + ( $salary['if_kurit'] ?? 0 );
                }

                if ($v['price_hour'] != 0 && $v['hour'] != 0) {
                    $v['summa'] = ceil($v['price_hour'] * $v['hour']);
                }

                $return['jobs'][$v['jobman']][$v['date']] = $v;
            }

//\f\pa($return);
        }
        /**
         * если ищем одну дату
         */ else {
            foreach ($j2 as $jobman => $v2) {
                foreach ($v2 as $date => $v) {
                    $return['jobs'][$v['jobman']][$ds] = $v;
                }
            }
        }

//\f\pa($return);

        return $return;
    }

    /**
     * старая версия
     * @param type $db
     * @param type $date_start
     * @param type $date_finish
     * @param type $module_man_job_on_sp
     * @param type $mod_spec_jobday
     * @return type
     */
    public static function getJobmansOnTime_old1910101503($db, $date_start, $date_finish = null, $module_man_job_on_sp = 'jobman_send_on_sp', $mod_spec_jobday = '050.job_in_sp') {

        echo '<br/>' . $date_start . ' , ' . $date_finish . '<br/>';

        $plus_minus_checks = \Nyos\mod\JobBuh::getChecksMinusPlus($db, $date_start, $date_finish);
// \f\pa($plus_minus_checks, 2, '', '$plus_minus_checks');
        foreach ($plus_minus_checks['items'] as $jobman => $v1) {
            foreach ($v1 as $k => $v) {
                $checks[$jobman][$v['date']] = $v;
            }
        }
//        // $plus_minus_checks = '';
//\f\pa($checks, 2, '', '$checks');

        $ocenki = [];

        foreach ($plus_minus_checks['items'] as $jobman => $v1) {
            foreach ($v1 as $k => $item) {
                if (isset($item['ocenka'])) {
                    $ocenki[$jobman][$item['date']] = $item['ocenka'];
                } elseif (isset($item['ocenka_auto'])) {
                    $ocenki[$jobman][$item['date']] = $item['ocenka_auto'];
                }
            }
        }

// \f\pa($ocenki);

        $return = [];

        if (empty($date_finish)) {
            $ds = $df = date('Y-m-d', strtotime($date_start));
        } else {
            $ds = date('Y-m-d', strtotime($date_start) - 3600 * 24);
            $df = date('Y-m-d', strtotime($date_finish));
        }

        /**
         * тащим спец назначения
         */
        if (1 == 1) {
            $spec_day = \Nyos\mod\items::getItemsSimple($db, $mod_spec_jobday);
//\f\pa($spec_day, 2, '', '$spec_day');
            $spec = [];
            foreach ($spec_day['data'] as $k => $v) {
                if ($v['dop']['date'] >= $ds && $v['dop']['date'] <= $df) {
                    $spec[$v['dop']['jobman']][$v['dop']['date']] = $v['dop'];
                }
            }
//\f\pa($spec, 2, '', '$spec');
        }

        /**
         * назначения сорудников на сп
         */
        $jobs = \Nyos\mod\items::getItemsSimple($db, $module_man_job_on_sp);
//        \f\pa($jobs, 2, '', '$jobs');


        $jobin = [];

        foreach ($jobs['data'] as $k => $v) {

// if ($v['dop']['date'] >= $ds && $v['dop']['date'] <= $df) {

            $jobin[$v['dop']['jobman']][$v['dop']['date']] = $v['dop'];

            /*
              (
              [jobman] => 187
              [sale_point] => 1
              [dolgnost] => 2
              [date] => 2019-05-01
              )
             */
//\f\pa($v['dop']);


            /*
              $nd = date('Y-m-d', strtotime($v['dop']['date']) + 3600 * 24 * 14);
              $v['dop']['date'] = $nd;
              $v['dop']['dolgnost'] = rand(10, 50);
              $jobin[$v['dop']['jobman']][$nd] = $v['dop'];

              $nd = date('Y-m-d', strtotime($v['dop']['date']) + 3600 * 24 * 7);
              $v['dop']['date'] = $nd;
              $v['dop']['dolgnost'] = rand(10, 50);
              $jobin[$v['dop']['jobman']][$nd] = $v['dop'];
             */
// }
        }

//\f\pa($jobin, 2, '', '$jobin');

        $j = [];

        foreach ($jobin as $k => $v) {

            if ($ds != $df)
                ksort($v);

            $j[$k] = $v;
        }

//      \f\pa($j, 2, '', '$j');

        $j2 = [];

        foreach ($j as $jobman => $v) {

// $start = false;
            $param_start = null;

            foreach ($v as $date => $v2) {

// if ($start === false && \strtotime($date) <= \strtotime($ds)) {
                if (\strtotime($date) <= \strtotime($ds)) {
                    $param_start = $v2;
                }

                if ($date >= $ds) {
// $start = true;
                    break;
                }
            }

            if (!empty($param_start)) {
                $j2[$jobman][$param_start['date']] = $param_start;
            }
        }

//\f\pa($j2, 2, '', '$j2');

        /**
         * если ищем несколько дат
         */
        if ($ds != $df) {

            $j3 = [];

            foreach ($j2 as $jobman => $date_ar) {
                foreach ($date_ar as $date => $ar) {
                    for ($i = 1; $i <= 35; $i++) {

                        $n = date('Y-m-d', strtotime($ds) + 3600 * 24 * $i);

                        if ($n > $df)
                            break;

//                    if (isset($j[$jobman][$n]))
//                        $ar = $j[$jobman][$n];
                        if (isset($j[$n]))
                            $ar = $j[$n];

                        $return['jobs_on_sp'][$ar['sale_point']][$ar['jobman']] = 1;

                        $a2 = $ar;

                        $a2['data_from_d'] = $a2['date'];
                        $a2['date'] = $n;

                        if (isset($spec[$jobman][$n])) {
                            $a2['sale_point'] = $spec[$jobman][$n]['sale_point'];
                            $a2['dolgnost'] = $spec[$jobman][$n]['dolgnost'];

                            $a2['type'] = 'spec';
                        }

                        $r[] = $a2;
                    }
                }
            }

            if ($ds != $df)
                usort($r, "\\f\\sort_ar_date");

            foreach ($r as $k => $v) {

                $salary = \Nyos\mod\JobDesc::getSalaryJobman($db, $v['sale_point'], $v['dolgnost'], $v['date']);
// $v['salary'] = $salary;
// $v['check'] = $checks[$v['jobman']][$v['date']] ?? 0 ;

                $v['hour'] = 0;
                if (isset($checks[$v['jobman']][$v['date']]['hour_on_job']) || isset($checks[$v['jobman']][$v['date']]['hour_on_job_hand'])) {
                    $v['hour'] = $checks[$v['jobman']][$v['date']]['hour_on_job_hand'] ?? $checks[$v['jobman']][$v['date']]['hour_on_job'];
                }

                $v['ocenka'] = $ocenki[$v['jobman']][$v['date']] ?? 0;

                $v['price_hour'] = 0;

// if (isset($salary['ocenka-hour-' . $v['ocenka']])) {
//\f\pa($v);
//\f\pa($salary,2,'','$salary');
// $v['prices'] = $salary;

                if (!empty($salary['ocenka-hour-base'])) {
                    $v['price_hour'] = $salary['ocenka-hour-base'] + ( $salary['if_kurit'] ?? 0 );
                    $v['ocenka_skip'] = 1;
                } elseif (isset($salary['ocenka-hour-' . $v['ocenka']])) {
                    $v['price_hour'] = $salary['ocenka-hour-' . $v['ocenka']] + ( $salary['if_kurit'] ?? 0 );
                }

                if ($v['price_hour'] != 0 && $v['hour'] != 0) {
                    $v['summa'] = ceil($v['price_hour'] * $v['hour']);
                }

                $return['jobs'][$v['jobman']][$v['date']] = $v;
            }

//\f\pa($return);
        }
        /**
         * если ищем одну дату
         */ else {
            foreach ($j2 as $jobman => $v2) {
                foreach ($v2 as $date => $v) {
                    $return['jobs'][$v['jobman']][$ds] = $v;
                }
            }
        }

//\f\pa($return);

        return $return;
    }

    /**
     * какие нормы на день по умолчанию
     * @param type $array
     * @param type $sp
     * @param type $man
     * @param string $date
     * @return type
     */
    public static function whatNormToDayDefault($db) {

        if (!empty(self::$cash['timeo_default']))
            return self::$cash['timeo_default'];

        $norms_def0 = \Nyos\mod\items::getItemsSimple($db, '074.time_expectations_default');

        // \f\pa($norms_def);
        //die();

        $norms_def = [];

        foreach ($norms_def0['data'] as $k => $v) {

            if (!empty($v['dop']['otdel']) && !empty($v['dop']['default'])) {

                $otd = null;

                if ($v['dop']['otdel'] == 1) {
                    $otd = 'cold';
                } elseif ($v['dop']['otdel'] == 2) {
                    $otd = 'hot';
                } elseif ($v['dop']['otdel'] == 3) {
                    $otd = 'delivery';
                }

                if ($otd !== null) {
                    $norms_def[$v['dop']['otdel']] = $v['dop']['default'];
                }
            }
        }

        return self::$cash['timeo_default'] = $norms_def;
        // return $norms_def;
    }

    /**
     * какие нормы на день сегодня в сп
     * @param type $array
     * @param type $sp
     * @param type $man
     * @param string $date
     * @return type
     */
    public static function whatNormToDay($db, int $sp, string $date, $date_fin = null) {

// echo '<br/>' . $sp . ' - ' . $date . ' - ' . $date_fin;

        $norms = \Nyos\mod\items::getItemsSimple($db, 'sale_point_parametr', 'show');
// \f\pa($norms,2,'','$norms'); // die();

        $a = [];

        foreach ($norms['data'] as $k => $v) {

//if( $v['dop']['date'] == $d && $v['dop']['sale_point'] == $sp )
            if (isset($v['dop']['sale_point']) && $v['dop']['sale_point'] == $sp) {
//\f\pa($v);
                $a[] = $v['dop'];
// $dates[] = $v['dop']['date'];
            }
        }

        usort($a, "\\f\\sort_ar_date");

        $d_start = date('Y-m-d', strtotime($date));

        $last = false;

        foreach ($a as $k => $v) {
            if ($d_start >= $v['date']) {
                $last = $v;
            }
        }

        /**
         * если ищем нормы отрезка дат
         */
        if (!empty($date_fin)) {

            $d_fin = date('Y-m-d', strtotime($date_fin));
            $return = [];

// \f\pa($last);

            for ($i = 0; $i <= 32; $i++) {

                $now_date = date('Y-m-d', strtotime($d_start) + (3600 * 24 * $i));

                $copy = true;

                /**
                 * ищем новое значение параметров
                 */
                foreach ($a as $k1 => $v1) {
                    if ($v1['date'] == $now_date) {
                        $last = $v1;
                        $copy = false;
                        break;
                    }
                }

                if ($copy === true)
                    $last['type'] = 'copy';

                $return[$now_date] = $last;

                if ($now_date == $d_fin)
                    break;
            }

            return $return;
        }
// если ищем одну дату
        else {

            return $last;
        }
    }

    /**
     * сейчас работает человек на этой сп или нет
     * @param type $array
     * @param type $sp
     * @param type $man
     * @param string $date
     * @return type
     */
    public static function where_now_job_man($array, $sp, $man, string $date) {

// \f\pa($date);
//echo '<div style="width:150px;"></div>';
// \f\pa($array['jobs'][$sp][$man]);
//        \f\pa($sp);
//        \f\pa($man);
//        \f\pa($array['jobs'][$sp][$man]);

        $nowutime = strtotime($date . ' 00:00');

        foreach ($array['jobs'][$sp][$man] as $k => $v) {
// \f\pa($v['date']);

            if (isset($v['date']) && $nowutime == strtotime($v['date'])) {

                $v['d1'] = $date;
                $v['d2'] = $v['date'];

                return $v;
            }
        }

        $now = [];

        foreach ($array['jobs'][$sp][$man] as $k => $v) {

// \f\pa($v['date']);

            if (isset($v['date']) && $nowutime > strtotime($v['date'] . ' 00:00')) {

                $v['d1'] = $date;
                $v['d2'] = $v['date'];

                $now = $v;
            }
        }

        if (!empty($now)) {
            return $now;
        }

        return false;
    }

}
