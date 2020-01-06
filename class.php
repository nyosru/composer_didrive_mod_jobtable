<?php

/**
  класс модуля
 * */

namespace Nyos\mod;

if (!defined('IN_NYOS_PROJECT'))
    throw new \Exception('Сработала защита от розовых хакеров, обратитесь к администрратору');

class JobDesc {

    public static $dir_img_server = false;

    /**
     * используем одну дату или нет
     * @var булев
     */
    public static $one_date = false;
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
     * / работники
     */
    public static $mod_jobman = '070.jobman';

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
     * нормы на день
     */
    public static $mod_norms_day = 'sale_point_parametr';

    /**
     * список модулей 
     * оценки работы дня
     */
    public static $mod_ocenki_days = 'sp_ocenki_job_day';

    /**
     * список модулей //  
     * чеки
     * @var строка
     */
    public static $mod_checks = '050.chekin_checkout';

    /**
     * список модулей //  
     * оборот ыточек по дням
     * @var строка
     */
    public static $mod_oborots = 'sale_point_oborot';

    /**
     * список модулей //  
     * время ожидания по умолчанию
     * @var строка
     */
    public static $mod_timeo_default = '074.time_expectations_default';

    /**
     * список модулей //  
     * время ожидания
     * @var строка
     */
    public static $mod_timeo = '074.time_expectations_list';

    /**
     * модуль бонусов (день точка сотрудник сумма )
     * @var строка
     */
    public static $mod_bonus = '072.plus';

    /**
     * модуль минусов (день точка сотрудник сумма )
     * @var строка
     */
    public static $mod_minus = '072.vzuscaniya';

    /**
     * смотрим кто работает в указанную дату в указанной точке
     * / если точку не указываем то вывалится массив точек в которых ввсе те люди что там работают
     * / 1912
     * @param type $db
     * @param type $date
     * @param type $sp
     */
    public static function getJobmansDate($db, string $date, $sp = null) {

        if (empty($sp) || ( isset($sp) && is_numeric($sp) )) {
            
        } else {
            return \f\end3('SP не та', false);
        }


        \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
                . ' ON mid.id_item = mi.id '
                . ' AND mid.name = \'date\' '
                . ' AND mid.value_date <= \'' . $date . '\' '
                . ' INNER JOIN `mitems-dops` mid2 '
                . 'ON mid2.id_item = mi.id '
                . 'AND mid2.name = \'jobman\' ';

        if (!empty($sp))
            \Nyos\mod\items::$join_where .= ' INNER JOIN `mitems-dops` mid3 '
                    . 'ON mid3.id_item = mi.id '
                    . 'AND mid3.name = \'sale_point\' '
                    . 'AND mid3.value = \'' . $sp . '\' '

//                . ' LEFT JOIN `mitems-dops` mid3 '
//                . 'ON mid3.id_item = mi.id '
//                . 'AND mid3.name = \'date_finish\' '
//                . ' INNER JOIN `mitems-dops` mid2 
//                ON mid2.id_item = mi.id 
//                AND mid2.name = \'dolgnost\' '
//                . ' LEFT JOIN `mitems-dops` mid3 
//                ON mid3.id_item = mid2.value
//                AND mid3.name = \'calc_auto\' '

            ;

        \Nyos\mod\items::$select_var1 = ' , mid.value_date date_start ';
        \Nyos\mod\items::$sql_order = //                ' GROUP BY mid2.value ' . 
                'ORDER BY date_start DESC '
//                'ORDER BY mid2.value DESC, date_start DESC '
        ;

        \Nyos\mod\items::$where2dop = ' AND ( '
                . ' `name` = \'sale_point\' '
                . ' OR '
                . ' `name` = \'jobman\' '
                . ' OR '
                . ' `name` = \'date\' '
                . ' OR '
                . ' `name` = \'date_finish\' '
                . ' OR '
                . ' `name` = \'dolgnost\' '
                . ' ) '
        ;

        // $job_all0 = \Nyos\mod\items::getItemsSimple3($db, 'jobman_send_on_sp');
        $job_all0 = \Nyos\mod\items::get($db, self::$mod_man_job_on_sp);
        // echo '11: '.sizeof($job_all0);
        // \f\pa($job_all0, '','','$job_all0');

        $return = [];

        // echo '<table>';

        foreach ($job_all0 as $k => $v) {

            if (!empty($v['date_finish']) && $v['date_finish'] < $date) {
                // echo '<br/>-- '.$v['jobman'].' '.$v['sale_point'].' '.$v['date'].' '.( $v['date_finish'] ?? 'x' ).' '.$v['status'];
                //echo '<tr><td>--'.$v['jobman'].'</td><td>'.$v['sale_point'].'</td><td>'.$v['date'].'</td><td>'.( $v['date_finish'] ?? 'x' ).'</td><td>'.$v['status'].'</td></tr>';
                // unset($job_all0[$k]);
                continue;
            }

            // echo '<br/>'.$v['jobman'].' '.$v['sale_point'].' '.$v['date'].' '.( $v['date_finish'] ?? 'x' ).' '.$v['status'];
            // echo '<tr><td>'.$v['id'].'</td><td>'.$v['jobman'].'</td><td>'.$v['sale_point'].'</td><td>'.$v['date'].'</td><td>'.( $v['date_finish'] ?? 'x' ).'</td><td>'.$v['status'].'</td></tr>';

            if (!empty($sp)) {
                if (!isset($return[$v['jobman']]))
                    $return[$v['jobman']] = $v;
            } else {
                if (!isset($return[$v['sale_point']][$v['jobman']]))
                    $return[$v['sale_point']][$v['jobman']] = $v;
            }
        }

        // echo '</table>';

        \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
                . ' ON mid.id_item = mi.id '
                . ' AND mid.name = \'date\' '
                . ' AND mid.value_date = \'' . $date . '\' '
        ;

        $all_spec = \Nyos\mod\items::get($db, self::$mod_spec_jobday);
        // \f\pa($all_spec);

        foreach ($all_spec as $k => $v) {
            if (empty($sp)) {
                $return[$v['sale_point']][$v['jobman']] = $v;
            } else {
                $return[$v['jobman']] = $v;
            }
        }

        return $return;
    }

    /**
     * считаем сколько суммарно часов отработано за сегодня
     * @param type $db
     * @param type $date
     * @param int $sp
     * @return array
     */
    public static function calcJobHoursDay($db, $date, int $sp) {

        $jobs_mesta = self::getJobmansDate($db, $date, $sp);
        //\f\pa($jobs_mesta,2,'','$jobs_mesta');

        $sql2 = '';
        foreach ($jobs_mesta as $k => $v) {
            $sql2 .= (!empty($sql2) ? ' OR ' : '' ) . ' mid2.value = \'' . $k . '\' ';
        }

        if (empty($sql2))
            return \f\end3('нет часов для суммирования', false);

        //\Nyos\mod\items::$show_sql = true;
        \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
                . ' ON mid.id_item = mi.id '
                . ' AND mid.name = \'start\' '
                . ' AND mid.value_datetime >= :date1 '
                . ' AND mid.value_datetime <= :date2 '
                . ' INNER JOIN `mitems-dops` mid2 '
                . ' ON mid2.id_item = mi.id '
                . ' AND mid2.name = \'jobman\' '
                . ' AND (' . $sql2 . ') '
        ;

        \Nyos\mod\items::$var_ar_for_1sql[':date1'] = $date . ' 08:00:00';
        \Nyos\mod\items::$var_ar_for_1sql[':date2'] = date('Y-m-d 03:00:00', strtotime($date . ' +1day'));

        \Nyos\mod\items::$where2dop = ' AND ( '
//                . ' `name` = \'sale_point\' '
//                . ' OR '
//                . ' `name` = \'jobman\' '
//                . ' OR '
//                . ' `name` = \'date\' '
//                . ' OR '
                . ' `name` = \'hour_on_job_hand\' '
                . ' OR '
                . ' `name` = \'hour_on_job\' '
                . ' ) '
        ;

        $checks = \Nyos\mod\items::get($db, self::$mod_checks);
        // \f\pa($checks,2,'','checks');

        $ret['hours'] = 0;
        $ret['smen_in_day'] = 0;
        $ret['checks_for_new_ocenka'] = [];

        foreach ($checks as $k => $v) {

            $ret['hours'] += $v['hour_on_job_hand'] ?? $v['hour_on_job'] ?? 0;
            $ret['smen_in_day'] ++;
            $ret['checks_for_new_ocenka'][] = $v['id'];
        }

        return \f\end3('ok', true, $ret);














        $return = [];

        \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid 
                ON mid.id_item = mi.id 
                AND mid.name = \'date\' 
                AND mid.value_date <= \'' . $date . '\'  '
                . ' INNER JOIN `mitems-dops` mid2 
                ON mid2.id_item = mi.id 
                AND mid2.name = \'dolgnost\' '
                . ' LEFT JOIN `mitems-dops` mid3 
                ON mid3.id_item = mid2.value
                AND mid3.name = \'calc_auto\' '
        ;

        \Nyos\mod\items::$select_var1 = ' , mid3.value calc_auto ';

        // $job_all0 = \Nyos\mod\items::getItemsSimple3($db, 'jobman_send_on_sp');
        $job_all0 = \Nyos\mod\items::getItemsSimple3($db, self::$mod_man_job_on_sp);
        //\f\pa($job_all0,2);

        usort($job_all0, "\\f\\sort_ar_date");

        // \f\pa($job_all0,2);

        $job_now = [];

        foreach ($job_all0 as $k => $v) {
            // echo '<br/>'.$v['jobman'].' - '.$v['sale_point'].' + '.$v['date'];
            $job_now[$v['jobman']] = $v;
        }

        /**
         * кто работает сегдня на нашей точке продаж
         */
        $job_now_on_sp = [];
        foreach ($job_now as $k => $v) {
            if ($v['sale_point'] == $sp) {

                //\f\pa($v);

                $job_now_on_sp[$k] = $v;
            }
        }

        // \f\pa($job_now_on_sp,2);

        \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid 
        ON mid.id_item = mi.id 
        AND mid.name = \'date\' 
        AND mid.value_date = \'' . $date . '\'  ';
        $spec = \Nyos\mod\items::getItemsSimple3($db, '050.job_in_sp');
        // \f\pa($spec,2);

        \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
                . ' ON mid.id_item = mi.id '
                . ' AND mid.name = \'start\' '
                . ' AND mid.value_datetime >= \'' . $date . ' 08:00:00\' '
                . ' AND mid.value_datetime <= \'' . date('Y-m-d 03:00:00', strtotime($date . ' +1day')) . '\' ';
        $checks = \Nyos\mod\items::getItemsSimple3($db, '050.chekin_checkout');

        //\f\pa($checks, 2,'','checks');

        $return['hours'] = 0;

        foreach ($checks as $k => $v) {

            if (isset($job_now_on_sp[$v['jobman']])) {

                // \f\pa($job_now_on_sp[$v['jobman']]);

                /**
                 * если на должност ине стоит галочка считаем автоматически .. то пропускаем
                 */
                if (empty($job_now_on_sp[$v['jobman']]['calc_auto'])) {
                    \f\pa($v);
                    continue;
                }

//                if( empty($v['hour_on_job_hand']) && empty($v['hour_on_job']) ){
//                }
                // \f\pa($job_now_on_sp[$v['jobman']]);
                // \f\pa($v);

                $return['checks_for_new_ocenka'][] = $v['id'];

                // $return['hours'] += ( $v['hour_on_job_hand'] ?? $v['hour_on_job'] );
                $return['hours'] += ( $v['hour_on_job_hand'] ?? ( $v['hour_on_job'] ?? 0 ) );
            }
        }

        // echo '<br/>часов: '.$return['hours'];
        return $return;
    }

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

    public static function getSalaryJobman($db, $sp, $dolgn, $date, $module_sp = 'sale_point', $module_salary = '071.set_oplata') {

//        echo '<br/>sp: ' . $sp;
//        echo '<br/>d: ' . $dolgn;

        if (isset(self::$cash['salary_now'][$sp][$dolgn][$date]))
            return self::$cash['salary_now'][$sp][$dolgn][$date];

        $sp_default = self::getDefaultSpId($db);

        if (empty(self::$cash['salarys'])) {
            self::$cash['salarys'] = \Nyos\mod\items::getItemsSimple3($db, $module_salary);
            usort(self::$cash['salarys'], "\\f\\sort_ar_date");
        }

        // если тру то используем точку по умолчанию
        $sp_def = true;

        foreach (self::$cash['salarys'] as $k => $v) {

            if ($v['date'] <= $date) {

//                echo '<div style="margin:5px; padding:5px; border:1px solid green; max-height:50px; overflow: auto;" >';
//                \f\pa($v);
//                echo '</div>';

                if (isset($v['dolgnost']) && $v['dolgnost'] == $dolgn) {
                    if (isset($v['sale_point']) && ( $v['sale_point'] == $sp || ( $sp_def === true && $v['sale_point'] == $sp_default ) )) {

                        if (isset($v['oborot_sp_last_monht_menee']) || isset($v['oborot_sp_last_monht_bolee'])) {

//                            echo '<br/>проверяем оборот:';
                            // достаём оборот этой точки продаж за текущий месяц
                            $oborot = \Nyos\mod\JobBuh::getOborotSpMonth($db, $sp, $date);
//                            \f\pa($oborot, '', '', 'oborot');

                            if (isset($v['oborot_sp_last_monht_menee']) || isset($v['oborot_sp_last_monht_bolee'])) {
                                if (isset($v['oborot_sp_last_monht_menee'])) {
                                    if (isset($v['oborot_sp_last_monht_menee']) && $v['oborot_sp_last_monht_menee'] <= $oborot) {
//                                        echo '<br/>++ ' . __LINE__ . ' ++';
                                        continue;
                                    }
//                                    else {
//                                        echo '<br/>-- menee ' . __LINE__ . ' ++';
//                                    }
                                }
                                if (isset($v['oborot_sp_last_monht_bolee'])) {
                                    if (isset($v['oborot_sp_last_monht_bolee']) && $v['oborot_sp_last_monht_bolee'] >= $oborot) {
                                        // echo '<br/>++ ' . __LINE__ . ' ++';
                                        continue;
                                    }
//                                    else {
//                                        echo '<br/>-- bolee ' . __LINE__ . ' ++';
//                                    }
                                }
                            }
                        }

                        if ($v['sale_point'] != $sp_default)
                            $sp_def = false;

//                        \f\pa($v);
                        self::$cash['salary_now'][$sp][$dolgn][$date] = $v;
                    }
                }
            }
        }

        return self::$cash['salary_now'][$sp][$dolgn][$date];
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
    public static function getSalaryJobman_old1912111155($db, $sp, $dolgn, $date, $module_sp = 'sale_point', $module_slary = '071.set_oplata') {

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

//            $salary = \Nyos\mod\items::getItemsSimple($db, $module_slary, 'show');
////\f\pa($salary, 2);
//
//            self::$cash['salarys'] = [];
//
//            foreach ($salary['data'] as $k => $v) {
//                self::$cash['salarys'][] = $v['dop'];
//            }

            self::$cash['salarys'] = \Nyos\mod\items::getItemsSimple3($db, $module_slary);

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
        // $timeo0 = \Nyos\mod\items::getItemsSimple($db, '074.time_expectations_list', 'show');
        $timeo0 = \Nyos\mod\items::getItemsSimple3($db, '074.time_expectations_list');

//\f\pa($timeo0);

        foreach ($timeo0 as $k => $v) {
// echo '<br/>'.$v['dop']['date'];

            if (isset($v['sale_point']) && $v['sale_point'] == $sp && isset($v['date']) && $v['date'] == $date) {
                $timeo[] = $v;
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
        $jobinsp = \Nyos\mod\JobDesc::getSetupJobmanOnSp($db, $date);
        \f\pa($jobinsp, 2, '', '$jobinsp');


        // echo '<br/>'.__FILE__.' #'.__LINE__;

        $worker_on_date = self::whereJobmans($db, $date);
        \f\pa($worker_on_date, 2, '', 'self::whereJobmans($db, $date);');

        // echo '<br/>'.__FILE__.' #'.__LINE__;
// \f\pa($jobinsp2, 2, '', '$jobinsp2');



        $dd = date('Y-m-d', strtotime($date));

        $dds = strtotime(date('Y-m-d 09:00:00', strtotime($date)));
        $ddf = strtotime(date('Y-m-d 02:00:00', ( strtotime($date) . ' + 1 day ')));


        $checki = \Nyos\mod\items::getItemsSimple($db, '050.chekin_checkout');
        // \f\pa($checki,2,'','$checki'); // exit;

        $checki2 = [];

        echo '<Br/>' . $date . '<br/>';

        foreach ($checki['data'] as $k2 => $v2) {

            if (isset($v2['dop']['jobman']) && isset($jobinsp['jobs'][$v2['dop']['jobman']])) {

                $ddn = strtotime($v2['dop']['start']);

                if ($dds <= $ddn && $ddn <= $ddf) {

// echo '<br/>#' . __LINE__ . ' ' . $v2['dop']['jobman'];
                    // \f\pa($v2['dop']);
                    $checki2[$v2['dop']['jobman']][] = $v2['dop'];
                }
            }
        }

        // \f\pa($checki2, 2, '', '$checki2'); // exit;
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

        \f\pa($return, 2, '', '$return');

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

        // $job = \Nyos\mod\items::getItemsSimple($db, self::$mod_man_job_on_sp);
        $job = \Nyos\mod\items::getItemsSimple3($db, self::$mod_man_job_on_sp);
// \f\pa($job,2,'','$job');

        $ar = [];

        foreach ($job as $k => $v) {
            $ar[$v['jobman']][$v['date']] = $v;
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

        //$spec = \Nyos\mod\items::getItemsSimple($db, self::$mod_spec_jobday);


        $spec = \Nyos\mod\items::getItemsSimple3($db, self::$mod_spec_jobday);
// \f\pa($spec,2,'','spec');

        foreach ($spec as $k1 => $v1) {
            if (isset($v1['date']) && $v1['date'] == $date) {
                $v1['type'] = 'spec';
                $now_job[$v1['jobman']] = $v1;
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

        // echo '<br/>' . __FUNCTION__ . ' ( [' . $date_start . '] , [' . $date_fin . '] ) #' . __LINE__;

        if (empty($date_start))
            $date_start = '2019-09-01';

        if (empty($date_fin))
            $date_fin = $date_start;

        $return = [];

        $i = 0;
        // $dt = date('Y-m-d', strtotime($date_start . ' + ' . $i . ' day'));
        $dt = date('Y-m-d', strtotime($date_start));

        $return[$dt] = self::whereJobmansNowDate($db, $dt);

        // \f\pa($return[$dt],2,'','$return[$dt]');

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
        if (1 == 1) {

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
        if (5 == 5) {
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
                throw new \Exception('Не все плановые данные по ТП указаны', 204);
//$error .= PHP_EOL . 'Не все плановые данные по ТП указаны';
            }
        }

        /**
         * достаём оборот за сегодня
         */
        if (5 == 5) {

            \f\timer::start();

// $return['oborot'] = \Nyos\mod\JobDesc::getOborotSp($db, $_REQUEST['sp'], $_REQUEST['date']);
// die('<br/>'.__FILE__.' == '.__LINE__);

            $return['oborot'] = \Nyos\mod\IikoOborot::getDayOborot($db, $return['sp'], $return['date']);

// \f\pa($return);
// echo

            $return['time'] .= PHP_EOL . ' достали обороты за день: ' . \f\timer::stop()
                    . PHP_EOL . $return['oborot'];
        }

        /**
         * достаём время ожидания за сегодня
         */
        if (5 == 5) {

            \f\timer::start();

//            echo '<hr>'.__FILE__.' #'.__LINE__;
//            echo '<br/>'.$return['sp'].' , '.$return['date'];
//            echo '<hr>';

            $timeo = \Nyos\mod\JobDesc::getTimeOgidanie($db, $return['sp'], $return['date']);

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
        if (1 == 3) {

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
//die();
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

    /**
     * запись автобонусов день + тп 
     * @param type $db
     * @param type $sp
     * @param type $date
     * @return type
     */
    public static function creatAutoBonus($db, $_sp, $dt0) {

        $show_comment = true;
        $show_comment = false;

        $dt = strtotime($dt0);
        $_d = date('Y-m-d', $dt);
        $ds = date('Y-m-d 05:00:00', $dt);
        $df = date('Y-m-d 04:00:00', $dt + 3600 * 24);

        if ($show_comment === true)
            \f\timer::start(123);

// удаление имеющихся бонусов в этот день
        $ee = self::deleteAutoBonus($db, $_sp, $_d);
        //\f\pa($ee,'','','$ee удаление автобонусов');

        if ($show_comment === true)
            echo '<br/>tt(' . __LINE__ . '): ' . \f\timer::stop('str', 123);

        /**
         * список должность и сколько бонуса накинуть
         * должность - оценка - бонус
         */
        $list_dolg_bonus = [];




        /**
         * массив работник > должность
         */
        $where_job = \Nyos\mod\JobDesc::whereJobmansNowDate($db, $_d);
        // \f\pa($where_job);

        foreach ($where_job as $k => $v) {
            if (isset($v['sale_point']) && $v['sale_point'] == $_sp && isset($v['jobman']) && isset($v['dolgnost'])) {
                $job_in[$v['jobman']] = $v['dolgnost'];
                $list_dolg_bonus[$v['dolgnost']] = 0;
            }
        }

        // \f\pa($job_in, 2, '', '$job_in работает в указанную дату на точке скана ' . $_sp . ' ( работник > должность )');

        if ($show_comment === true)
            echo '<br/>tt(' . __LINE__ . '): ' . \f\timer::stop('str', 123);


        /**
         * дневной оборот точки
         */
        $oborot_month = \Nyos\mod\IikoOborot::getOborotMonth($db, $_sp, $_d);
        // \f\pa($oborot_month, 2, '', '$oborot_month');
        // \Nyos\mod\items::$get_data_simple = true;
        // $checks0 = \Nyos\mod\items::getItemsSimple($db, \Nyos\mod\JobDesc::$mod_checks);

        if ($show_comment === true)
            echo '<br/>tt(' . __LINE__ . '): ' . \f\timer::stop('str', 123);

        \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` md ON '
                . ' `md`.`id_item` = mi.id '
                . 'AND `md`.`name` = \'start\' '
                . 'AND `md`.`value_datetime` >= \'' . $ds . '\'  '
                . 'AND `md`.`value_datetime` <= \'' . $df . '\'  ';

        $checks0 = \Nyos\mod\items::getItemsSimple3($db, \Nyos\mod\JobDesc::$mod_checks);
        $checks = [];

        foreach ($checks0 as $k => $v) {
            if (isset($v['jobman']) && isset($job_in[$v['jobman']]) && isset($v['start']) && $v['start'] >= $ds && $v['start'] <= $df) {
                $v['item_id'] = $v['id'];
                $checks[] = $v;
            }
        }

        if ($show_comment === true)
            echo '<br/>tt(' . __LINE__ . '): ' . \f\timer::stop('str', 123);


        // usort($checks, "\\f\\sort_ar_start");
        // \f\pa($checks, 2, '', '$checks');


        /*
          $cheki_da = [];
          $cheki_jobmans = [];

          // usort($dd, "\\f\\sort_ar_date");

          foreach ($checks as $k => $v) {
          if (
          !empty($v['start']) && $ds < $v['start'] && !empty($v['fin']) && $v['fin'] < $df && isset($v['jobman']) && isset($job_in[$v['jobman']])
          ) {
          $cheki_da[$v['id']] = $v;

          // если ещё не было записи
          if (!isset($cheki_jobmans[$v['jobman']])) {
          $ocenka = $v['ocenka'] ?? $v['ocenka_auto'];
          $cheki_jobmans[$v['jobman']] = $ocenka;
          }
          // если запись была, то смотрим где ниже оценка
          else {
          $ocenka = $v['ocenka'] ?? $v['ocenka_auto'];
          if ($ocenka < $cheki_jobmans['jobman']) {
          $cheki_jobmans[$v['jobman']] = $ocenka;
          }
          }
          }
          }

          // \f\pa($cheki_da, 2, '', '$cheki_da');
          \f\pa($cheki_jobmans, 2, '', '$cheki_jobmans список работников > оценка');
         */


//        \f\timer::start(11);
//        
//        $dolgns = \Nyos\mod\items::getItemsSimple($db, \Nyos\mod\JobDesc::$mod_salary);
//// \f\pa($dolgns, 5, '', '$dolgns');
//
//        $dd = [];
//
//        foreach ($dolgns['data'] as $k => $v) {
//            $dd[] = $v['dop'];
//        }
//
//        usort($dd, "\\f\\sort_ar_date");
//        // \f\pa($dd, 5, '', '$dolgns2');
//
//        echo '<br/>tt:'.\f\timer::stop('str',11);
//        
//        \f\timer::start(11);
//        
        $dd = \Nyos\mod\items::getItemsSimple3($db, \Nyos\mod\JobDesc::$mod_salary, 'show', 'date_asc');
        // \f\pa($dolgns, 5, '', '$dolgns2');
//        echo '<br/>tt:'.\f\timer::stop('str',11);
//        die();

        $d = [];

        foreach ($dd as $k => $v) {

            if ($v['date'] > $_d)
                continue;

            if (isset($v['oborot_sp_last_monht_bolee'])) {

                if ($oborot_month >= $v['oborot_sp_last_monht_bolee']) {

//                echo '<br/>[' . $v['dolgnost'] . '] ' . __LINE__ . ' ' . $v['oborot_sp_last_monht_bolee'] . ' ++ ' . $oborot_month;
//                \f\pa($v);

                    $d[$v['dolgnost']] = $v;
                }
            }

//
            elseif (isset($v['oborot_sp_last_monht_menee'])) {
                if ($oborot_month <= $v['oborot_sp_last_monht_menee']) {

//                echo '<br/>[' . $v['dolgnost'] . '] ' . __LINE__ . ' ' . $v['oborot_sp_last_monht_menee'] . ' ++ ' . $oborot_month;
//                \f\pa($v);
                    $d[$v['dolgnost']] = $v;
                }
            }

//
            else {

//            echo '<br/>[' . $v['dolgnost'] . '] ' . __LINE__ . ' ';
//            \f\pa($v);
                $d[$v['dolgnost']] = $v;
            }
        }

        if ($show_comment === true)
            echo '<br/>tt(' . __LINE__ . '): ' . \f\timer::stop('str', 123);


        $adds = [];


        // \f\pa($d, 5, '', '$d должности на ' . $_d);
        // перебираем чеки ищем кто работает и номер чека берём для комментария
        foreach ($checks as $k => $v) {

//        echo '<hr>';

            if (isset($job_in[$v['jobman']])) {

//            echo '<br/>jm:' . $v['jobman'];
//            echo '<br/>должность:' . $job_in[$v['jobman']];

                $ocenka = $v['ocenka'] ?? $v['ocenka_auto'] ?? null;
//            echo '<br/>оценка:' . $ocenka;

                if (empty($ocenka))
                    continue;

                $premiya = $d[$job_in[$v['jobman']]]['premiya-' . $ocenka] ?? null;

                if (!empty($premiya)) {
//                echo '<Br/>pr ' . $premiya;

                    $adds[] = [
                        'auto_bonus_zp' => 'da',
                        'jobman' => $v['jobman'],
                        'sale_point' => $_sp,
                        'date_now' => $_d,
                        'summa' => $premiya,
                        'text' => 'бонус к зп',
                    ];

//                $add = [
//                    'auto_bonus_zp' => 'da',
//                    'jobman' => $v['jobman'],
//                    'sale_point' => $_sp,
//                    'date_now' => $_d,
//                    'summa' => $premiya,
//                    'text' => 'бонус к зп',
//                ];
//
//                \Nyos\mod\items::addNewSimple($db, \Nyos\mod\JobDesc::$mod_bonus, $add);
                }
            }
        }

        if ($show_comment === true)
            echo '<br/>tt(' . __LINE__ . '): ' . \f\timer::stop('str', 123);

        if (!empty($adds)) {
            // \f\pa($adds);

            \Nyos\mod\items::addNewSimples($db, \Nyos\mod\JobDesc::$mod_bonus, $adds);
            return \f\end3('bonus exists', true, ['adds' => $adds]);
        } else {

            return \f\end3('no bonus', false);
        }

        // return \f\end3('ok', true, $return);
// return $return;
    }

    /**
     * удаление автобонусов по зп ( день + тп )
     * @param type $db
     * @param type $sp
     * @param type $date
     * @return type
     */
    public static function deleteAutoBonus($db, $sp, $date0) {

        // echo '<br/>'.$date0;
        $date = date('Y-m-d', strtotime($date0));

        // \Nyos\mod\items::$get_data_simple = true;
        // $bonuses = \Nyos\mod\items::getItemsSimple($db, self::$mod_bonus);

        if (empty(self::$cash['bonuses'])) {

            //self::$cash['bonuses'] = \Nyos\mod\items::getItemsSimple3($db, self::$mod_bonus);
            self::$cash['bonuses'] = \Nyos\mod\items::get($db, self::$mod_bonus);
            // echo '<br/>' . __LINE__;
        } else {
            // echo '<br/>' . __LINE__;
        }
        // exit;

        $sql1 = '';
        $sql2 = [];

        $nn = 0;

        foreach (self::$cash['bonuses'] as $k => $v) {
            if (isset($v['auto_bonus_zp']) && $v['auto_bonus_zp'] == 'da' && isset($v['date_now']) && $v['date_now'] == $date) {

                //\f\pa($v);
                //$sql2[':id' . $nn] = $v['_id'];
                $sql2[':id' . $nn] = $v['id'];
                $sql1 .= (!empty($sql1) ? ' OR ' : '' ) . ' `id` = :id' . $nn;

                $nn++;
            }
        }
        // \f\pa($bonus_del);

        if (!empty($sql1)) {

            // echo 'UPDATE mitems SET `status` = \'delete\' WHERE ' . $sql1;
            $ff = $db->prepare('UPDATE mitems SET `status` = \'delete\' WHERE ' . $sql1);
            $ff->execute($sql2);

            return \f\end3('ok, удалено ' . sizeof($sql2) . ' шт.', true, $sql2);
        } else {
            return \f\end3('нечего удалять', false);
        }


// return $return;
    }

    /**
     * считаем оценку 1 дня (при автоматическом выставлении оценок
     * @param type $db
     * @param type $sp
     * @param type $data
     */
    public static function calculateAutoOcenkaDays($db, $sp, $date) {

        $return = \Nyos\mod\JobDesc::readVarsForOcenkaDays($db, $sp, $date);
        \f\pa($return, 2, '', '$return readVarsForOcenkaDays');

        die('<br/>end ' . __FILE__ . ' #' . __LINE__);







        // echo '<br/>' . __FILE__ . ' #' . __LINE__;
        // return \f\end3( 'wef', true );
        // ob_start('ob_gzhandler');

        try {

            if (1 == 1) {

                //                die();
                // массив чеков для новых оценок
                // $return['checks_for_new_ocenka']
            }

            return \f\end3('ok ' . __LINE__, true);

            if (1 == 1) {
                // \f\pa($return['data'], 2, '', '$return данные для оценки дня');
                $return['data']['ocenka-data'] = $ocenka = \Nyos\mod\JobDesc::calcOcenkaDay($db, $return['data']);
                //\f\pa($ocenka, 2, '', '$ocenka');
                //die();
                // $return = array_merge($return, $ocenka);

                $ocenki_error = '';

                if (empty($ocenka['data']['ocenka_time']))
                    $ocenki_error .= 'нет оценки по времени ожидания';

                if (empty($ocenka['data']['ocenka_naruki']))
                    $ocenki_error .= (!empty($ocenki_error) ? ', ' : '' ) . 'нет оценки по сумме на руки';

                if (!empty($ocenki_error))
                    throw new \Exception($ocenki_error);

                $return['data']['ocenka_naruki'] = $ocenka['data']['ocenka_naruki'];
                $return['data']['ocenka_time'] = $ocenka['data']['ocenka_time'];
                $return['data']['ocenka'] = $ocenka['data']['ocenka'];
            }

            if (1 == 1) {

                //        if ( class_exists('\Nyos\mod\items') )
                //            echo '<br/>' . __FILE__ . ' ' . __LINE__;
                // if (!empty($return['data']['checks_for_new_ocenka'])) {
                // \f\pa( $return['checks_for_new_ocenka'], 2 , '' , 'checks_for_new_ocenka' );

                $return['data']['ocenka-save'] = \Nyos\mod\JobDesc::recordNewAutoOcenkiDay($db,
                                $return['data']['checks_for_new_ocenka'],
                                $ocenka['data']['ocenka']);

                // }
                $return['data']['ocenka-save2'] = \Nyos\mod\items::addNewSimple($db, \Nyos\mod\jobdesc::$mod_ocenki_days, [
                            'sale_point' => $ocenka['data']['sp'],
                            'date' => $ocenka['data']['date'],
                            'ocenka_time' => $ocenka['data']['ocenka_time'],
                            'ocenka_naruki' => $ocenka['data']['ocenka_naruki'],
                            'ocenka' => $ocenka['data']['ocenka'],
                ]);
            }
            // $r = ob_get_contents();
            // ob_end_clean();

            return \f\end3('ok ' . ( $r ?? '--' ), true, $return['data']);

            if (1 == 2) {

// require_once DR . '/all/ajax.start.php';
// $ff = $db->prepare('UPDATE `mitems` SET `status` = \'hide\' WHERE `id` = :id ');
// $ff->execute(array(':id' => (int) $_POST['id2']));
//die('123');
//
//echo '<br/>'.__FILE__.' '.__LINE__;
//    $checki = \Nyos\mod\items::getItemsSimple($db, '050.chekin_checkout', 'show');
//    \f\pa($checki,2,'','$checki');
//echo '<br/>'.__FILE__.' '.__LINE__;
//    $salary = \Nyos\mod\JobDesc::configGetJobmansSmenas($db);
//    \f\pa($salary,2,'','$salary');
//    $return['txt'] .= '<br/>salary';
//    foreach ($salary as $k => $v) {
//        $return['txt'] .= '<br/><nobr>[' . $k . '] - ' . $v . '</nobr>';
//        $return['salary_' . $k] = $v;
//    }
//echo '<br/>'.__FILE__.' '.__LINE__;
//echo '<br/>'.__FILE__.' '.__LINE__;
//echo '<br/>'.__FILE__.' '.__LINE__;
// \f\pa($return);
// exit;
//\f\pa($return);
// если есть ошибки
                if (!empty($error)) {

                    require_once DR . dir_site . 'config.php';

                    $sp = \Nyos\mod\items::getItemsSimple($db, 'sale_point', 'show');
// \f\pa($sp);

                    if (1 == 2 && !isset($_REQUEST['no_send_msg'])) {
                        $txt_to_tele = 'Обнаружены ошибки при расчёте оценки точки продаж (' . $sp['data'][$_REQUEST['sp']]['head'] . ') за день работы (' . $_REQUEST['date'] . ')' . PHP_EOL . PHP_EOL . $error;

                        if (class_exists('\nyos\Msg'))
                            \nyos\Msg::sendTelegramm($txt_to_tele, null, 1);

                        if (isset($vv['admin_ajax_job'])) {
                            foreach ($vv['admin_ajax_job'] as $k => $v) {
                                \nyos\Msg::sendTelegramm($txt_to_tele, $v);
//\Nyos\NyosMsg::sendTelegramm('Вход в управление ' . PHP_EOL . PHP_EOL . $e, $k );
                            }
                        }
                    }
//echo '<br/>'.__FILE__.' '.__LINE__;

                    return \f\end2('Обнаружены ошибки при расчёте оценки точки продаж (' . $_REQUEST['sp'] . ') за день работы (' . $_REQUEST['date'] . ')' . $error, false);
                }
// если нет ошибок считаем
                else {

                    \f\timer::start();

                    /**
                     * сравниваем время ожидания холодный цех
                     */
                    if (isset($return['timeo_cold']) && isset($return['norm_time_wait_norm_cold'])) {

                        $return['txt'] .= '<br/><br/>-------------------';
                        $return['txt'] .= '<br/>время ожидания (хол.цех)';
                        $return['txt'] .= '<br/>по плану: ' . $return['norm_time_wait_norm_cold'] . ' и значение в ТП ' . $return['timeo_cold'];

                        if (isset($return['timeo_cold']) && isset($return['norm_time_wait_norm_cold']) &&
                                $return['timeo_cold'] > $return['norm_time_wait_norm_cold']) {

                            $return['txt'] .= '<br/>не норм, оценка 3';
                            $return['ocenka_time'] = 3;
                            $return['ocenka'] = 3;
                        } else {
                            $return['txt'] .= '<br/>норм, оценка 5';
                            $return['ocenka_time'] = 5;
                        }
                    } else {
                        throw new \Exception('Вычисляем оценку дня, прервано, не хватает данных по времени ожидания', 14);
                    }

                    /**
                     * сравниваем объём выручки
                     */
                    if (1 == 2) {
                        if (!empty($return['norm_vuruchka']) && !empty($return['oborot'])) {

                            $return['txt'] .= '<br/><br/>-------------------';
                            $return['txt'] .= '<br/>норма выручки';
                            $return['txt'] .= '<br/>по плану: ' . $return['norm_vuruchka'] . ' и значение в ТП ' . $return['oborot'];

                            if ($return['oborot'] >= $return['norm_vuruchka']) {
                                $return['oborot_bolee_norm'] = 1;
                                $return['ocenka_oborot'] = 5;
                                $return['txt'] .= '<br/>норм, оценка 5';
                            } else {
                                $return['oborot_bolee_norm'] = 0;
                                $return['ocenka_oborot'] = 3;
                                $return['ocenka'] = 3;
                                $return['txt'] .= '<br/>не норм, оценка 3';
                            }
                        }
//
                        else {
                            throw new \Exception('Вычисляем оценку дня, прервано, не хватает данных по обороту за сутки', 18);
                        }
                    }

                    /**
                     * считаем норму выручки на руки
                     */
// if (!empty($return['norm_kolvo_hour_in1smena'])) {
                    if (!empty($return['norm_kolvo_hour_in1smena']) && !empty($return['norm_vuruchka_on_1_hand'])) {

                        $return['txt'] .= '<br/><br/>-------------------';
                        $return['txt'] .= '<br/>норма выручки (на руки)';

                        $return['smen_in_day'] = round($return['hours'] / $return['norm_kolvo_hour_in1smena'], 1);
                        $return['txt'] .= '<br/>Кол-во поваров: ' . $return['smen_in_day'];

                        $return['on_hand_fakt'] = ceil($return['oborot'] / $return['smen_in_day']);
// $return['summa_na_ruki_norm'] = ceil($return['oborot'] / 100 * $return['norm_procent_oplata_truda_on_oborota']);
//$return['txt'] .= '<br/>по плану: ' . $return['summa_na_ruki_norm'] . ' и значение в ТП ' . $return['on_hand_fakt'];
                        $return['txt'] .= '<br/>по плану: ' . $return['norm_vuruchka_on_1_hand'] . ' и значение в ТП ' . $return['on_hand_fakt'];

                        if ($return['on_hand_fakt'] < $return['norm_vuruchka_on_1_hand']) {
                            $return['ocenka'] = 3;
                            $return['ocenka_naruki'] = 3;
                            $return['ocenka'] = 3;
                            $return['txt'] .= '<br/>не норм, оценка 3';
                        } else {
                            $return['ocenka_naruki'] = 5;
                            $return['txt'] .= '<br/>норм, оценка 5';
                        }
                    } else {
                        throw new \Exception('Вычисляем оценку дня, прервано, не хватает значения по плану (норма на руки)', 19);
                    }


                    $return['txt'] .= '<br/>';
                    $return['txt'] .= '<br/>';
                    $return['txt'] .= '-----------';
                    $return['txt'] .= '<br/>';
                    $return['txt'] .= 'оценка дня : ' . $return['ocenka'];
                    $return['txt'] .= '<br/>';
                    $return['txt'] .= '<br/>';
                    $return['txt'] .= '<br/>';

// $return['ocenka_upr'] = $return['ocenka'];
//            $return['time'] .= PHP_EOL . ' считаем ходится не сходится : ' . \f\timer::stop();
//            $return['txt'] .= '<br/><nobr>рекомендуемая оценка упр: ' . $return['ocenka_upr'] . '</nobr>';


                    /**
                     * запись результатов в бд
                     */
                    if (1 == 1) {
                        $sql_del = '';
                        $sql_ar_new = [];

                        foreach ($id_items_for_new_ocenka as $id_item => $v) {

                            $sql_del .= (!empty($sql_del) ? ' OR ' : '' ) . ' id_item = \'' . (int) $id_item . '\' ';
                            $sql_ar_new[] = array(
                                'id_item' => $id_item,
                                'name' => 'ocenka_auto',
                                'value' => $return['ocenka']
                            );
                        }

                        if (!empty($sql_del)) {
                            $ff = $db->prepare('DELETE FROM `mitems-dops` WHERE name = \'ocenka_auto\' AND ( ' . $sql_del . ' ) ');
                            $ff->execute();
                        }

                        \f\db\sql_insert_mnogo($db, 'mitems-dops', $sql_ar_new);
                        $return['txt'] .= '<br/>записали автоценки сотрудникам';
                    }

                    require_once DR . dir_site . 'config.php';

                    $sp = \Nyos\mod\items::getItemsSimple($db, 'sale_point', 'show');
// \f\pa($sp);

                    \Nyos\mod\items::addNewSimple($db, 'sp_ocenki_job_day', $return);

                    if (1 == 2 && (!isset($_REQUEST['no_send_msg']) && !isset($_REQUEST['telega_no_send']) )) {

                        $txt_to_tele = 'Расчитали автооценку ( ' . $sp['data'][$_REQUEST['sp']]['head'] . ' ) за день работы (' . $_REQUEST['date'] . ')'
                                . PHP_EOL
                                . PHP_EOL
                                . str_replace('<br/>', PHP_EOL, $return['txt'])
//                        . PHP_EOL
//                        . '-----------------'
//                        . PHP_EOL
//                        . 'время выполнения вычислений'
//                        . PHP_EOL
//                        . $return['time']
                        ;

                        if (class_exists('\nyos\Msg'))
                            \nyos\Msg::sendTelegramm($txt_to_tele, null, 1);

                        if (isset($vv['admin_ajax_job'])) {
                            foreach ($vv['admin_ajax_job'] as $k => $v) {
                                \nyos\Msg::sendTelegramm($txt_to_tele, $v);
//\Nyos\NyosMsg::sendTelegramm('Вход в управление ' . PHP_EOL . PHP_EOL . $e, $k );
                            }
                        }
                    }

                    \f\end2(
                            $return['txt']
                            . '<br/>часов: ' . $return['hours']
                            . '<br/>смен в дне: ' . $return['smen_in_day']
                            , true, $return);
                }

//return \f\end2('Обнаружены ошибки: ' . $ex->getMessage() . ' <Br/>' . $text, false, array( 'error' => $ex->getMessage() ) );        
            }
        }
//
        catch (\Exception $ex) {

// if ( isset($_REQUEST['no_send_msg']) ) {}else{}

            echo '<br/>' . __FILE__ . ' #' . __LINE__;

            $text = $ex->getMessage()
                    . ' авторасчёт оценки дня'
                    . PHP_EOL
                    . PHP_EOL
                    . ' sp:' . ( $return['data']['sp'] ?? '--' )
                    . ' date:' . ( $return['data']['date'] ?? '--' )
                    . PHP_EOL
                    . PHP_EOL
                    . '--- ' . __FILE__ . ' ' . __LINE__ . '-------'
                    . PHP_EOL
                    . $ex->getMessage() . ' #' . $ex->getCode()
                    . PHP_EOL
                    . $ex->getFile() . ' #' . $ex->getLine()
                    . PHP_EOL
                    . $ex->getTraceAsString()
// . '</pre>'
            ;

            if (1 == 2 && class_exists('\Nyos\Msg')) {
                \Nyos\Msg::sendTelegramm($text, null, 1);
            }
            /*
              echo '<pre>'
              . PHP_EOL
              . PHP_EOL
              . '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
              . PHP_EOL
              . $ex->getMessage() . ' #' . $ex->getCode()
              . PHP_EOL
              . $ex->getFile() . ' #' . $ex->getLine()
              . PHP_EOL
              . $ex->getTraceAsString()
              . '</pre>';
             */

            /*

              require_once DR . dir_site . 'config.php';

              $sp = \Nyos\mod\items::getItemsSimple($db, 'sale_point', 'show');
              // \f\pa($sp);

              $txt_to_tele = 'Обнаружены ошибки при расчёте оценки точки продаж (' . $sp['data'][$_REQUEST['sp']]['head'] . ') за день работы (' . $_REQUEST['date'] . ')' . PHP_EOL . PHP_EOL . $error;

              if (class_exists('\nyos\Msg'))
              \nyos\Msg::sendTelegramm($txt_to_tele, null, 1);

              if (isset($vv['admin_ajax_job'])) {
              foreach ($vv['admin_ajax_job'] as $k => $v) {
              \nyos\Msg::sendTelegramm($txt_to_tele, $v);
              //\Nyos\NyosMsg::sendTelegramm('Вход в управление ' . PHP_EOL . PHP_EOL . $e, $k );
              }
              }
             */

//            $r = ob_get_contents();
//            ob_end_clean();

            return \f\end3('Обнаружены ошибки: ' . $ex->getMessage(), false, [
                'error' => $ex->getMessage(),
                'code' => $ex->getCode(),
                'sp' => ( $return['data']['sp'] ?? null ),
                'date' => ( $return['data']['date'] ?? null ),
                // 'text' => $text . '<br/>' . str_replace('<br/>', PHP_EOL, $r),
                // 'msg' => $ex->getMessage(),
                'file_line' => $ex->getFile() . ' #' . $ex->getLine(),
                'trace' => explode(PHP_EOL, $ex->getTraceAsString()),
                'datas' => ( $return ?? 'x' ),
            ]);
        }
    }

    public static function getDaysOcenkaNo($db, $start_day = '2019-09-01') {

        // список точек продаж для скана
        $lisp_sp = [];
        // сколько дней сканим от сегодня назад
        $scan_days = 70;
// echo $start_day;
        $last_day = date('Y-m-d', $_SERVER['REQUEST_TIME'] - 3600 * 24);

        /**
         * собираем список точек продаж
         */
        $sps = \Nyos\mod\items::getItemsSimple($db, self::$mod_sale_point);
        // \f\pa($sps, 2, '', '$sps');
        // выбираем тех у кого id для получения оборота
        foreach ($sps['data'] as $k => $v) {
            if (!empty($v['dop']['id_tech_for_oborot']))
                $lisp_sp[$v['id']] = 1;
        }
        //\f\pa($lisp_sp, 2, '', '$lisp_sp');


        $e = \Nyos\mod\items::getItemsSimple($db, self::$mod_ocenki_days);
// \f\pa($e,2,'','$e оценки self::$mod_ocenki_days');

        $r = [];


//        echo ' -+ ' . $start_day;
//        echo ' -- ' . $last_day;
//        echo '<br/>';

        foreach ($e['data'] as $k => $v) {

            if (isset($v['dop'])) {

                if (isset($v['dop']['date']) && ( $v['dop']['date'] > $last_day || $v['dop']['date'] < $start_day )) {

// echo '<br/>дата в центре, не сходится = ' . $start_day .' = '. $last_day .' | '.$v['dop']['date'];
                    continue;
                } else {
// echo '<br/>дата в центре, сходится = ' . $start_day .' = '. $last_day .' | '.$v['dop']['date'];
                }

//                if (!isset($lisp_sp[$v['dop']['sale_point']]))
//                    $lisp_sp[$v['dop']['sale_point']] = 1;
//                echo ' -- ' . $v['dop']['date'];
//                echo ' || ' . $v['dop']['sale_point'];
//                echo '<br/>';

                $r[$v['dop']['date']][$v['dop']['sale_point']] = $v['dop'];
            }
        }


        foreach ($r as $date => $v1) {

            foreach ($lisp_sp as $sp => $v2) {

                if (empty($v1[$sp])) {
                    $r[$date][$sp] = false;
                }
            }
        }


        krsort($r);
        // \f\pa($r, 2, '', '$r');

        return \f\end3('ok', true, $r);
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

        if (!empty($check_string)) {
            $ff = $db->prepare('DELETE FROM `mitems-dops` WHERE ( ' . $check_string . ' ) AND `name` = \'ocenka_auto\' ;');
            $ff->execute($check_ar);
        }

        \f\db\sql_insert_mnogo($db, 'mitems-dops', $rows_in, ['name' => 'ocenka_auto', 'value' => $ocenka]);

//        \f\db\db2_insert( $db, 'mitems-dops', array(
//            'id_item' => (int) $_REQUEST['work_id'],
//            'name' => 'date_finish',
//            'value_date' => date('Y-m-d', strtotime($_REQUEST['date_end']))
//                )
//        );

        return \f\end3('ок', true, ['check_string' => ( $check_string ?? 'x')]);
    }

    /**
     * расчитываем какая оценка дня опираясь на массив данных из функции self::compileVarsForOcenkaDay($db, $sp, $date)
     * 1911
     * @param type $db
     * @param type $ar
     * @return type
     */
    public static function calcOcenkaDay($db, $return) {

        $text = '';
        $text_s = '<br/>';

        $vv1 = [
            'date' => '',
            'sp' => '',
            'norm_vuruchka_on_1_hand' => '',
            'norm_time_wait_norm_cold' => '',
            'norm_time_wait_norm_hot' => '',
            'norm_time_wait_norm_delivery' => '',
            'oborot' => '',
            'hours' => '',
            'timeo_cold' => 'время хол цех',
            'timeo_hot' => 'время гор цех',
            'timeo_delivery' => 'время доставка',
        ];

        foreach ($vv1 as $vv => $vv_text) {

            if ($vv == 'norm_time_wait_norm_hot' || $vv == 'norm_time_wait_norm_delivery')
                continue;

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

                    //
                    if (!empty($vv) && $vv == 'oborot') {
                        throw new \Exception('нет оборота по точке (' . $vv_text . ')', 201);
                    }
                    //
                    elseif (!empty($vv) && $vv == 'hours') {
                        throw new \Exception('нет количества часов работы за день для расчёта (' . $vv_text . ')', 203);
                    }
                    //
                    else {
                        throw new \Exception('нет значения ' . $vv . ' (' . $vv_text . ')', __LINE__);
                    }
                }
            }
        }

        $re = [
            'sp' => $return['sp'],
            'date' => $return['date'],
            'ocenka_time' => 0,
            //$return['ocenka_oborot'] => 5;
            'ocenka_naruki' => 0,
            'ocenka' => 0,
                // 'txt' => ''
        ];

        $return['txt'] .= '<br/>оборот точки: ' . $return['oborot'];

        /**
         * вычисление оценки на руки
         */
        $return['smen_in_day'] = round($return['hours'] / $return['norm_kolvo_hour_in1smena'], 1);

        $return['txt'] .= '<br/>суммарно отработано часов:' . $return['hours'];
        $return['txt'] .= '<br/>часов в 1 смене:' . $return['norm_kolvo_hour_in1smena'];
        $return['txt'] .= '<br/>отработано смен: ' . $return['hours'] . ' / ' . $return['norm_kolvo_hour_in1smena'] . ' = ' . $return['smen_in_day'];

        if (!empty($return['oborot']) && !empty($return['smen_in_day'])) {
            $return['summa_na_ruki'] = ceil($return['oborot'] / $return['smen_in_day']);
            $return['txt'] .= '<br/>на 1 руки денег: ' . $return['oborot'] . ' / ' . $return['smen_in_day'] . ' = ' . $return['summa_na_ruki'];
        }


        $text .= $return['summa_na_ruki'] . ' (сейчас) >= (норма) ' . $return['norm_vuruchka_on_1_hand'] . $text_s;

// если на руки больше нормы то оценка 5
        if ($return['summa_na_ruki'] >= $return['norm_vuruchka_on_1_hand']) {

            $re['ocenka_naruki'] = 5;
            $text .= 'сумма на руки больше плана/нормыы ' . $text_s;
            $return['txt'] .= '<br/>сумма на руки больше или равно норм ( ' . $return['summa_na_ruki'] . ' >= ' . $return['norm_vuruchka_on_1_hand'] . ' ) : оценка 5';
        }
// если на руки меньше нормы то оценка 3
        else {

            $re['ocenka_naruki'] = 3;
            $text .= 'сумма на руки меньше плана/нормы ' . $text_s;
            $return['txt'] .= '<br/>сумма Р на руки меньше НОРМы (' . $return['summa_na_ruki'] . ' < ' . $return['norm_vuruchka_on_1_hand'] . ') : оценка 3';
        }

        $ar = $return;

// время ожидания
        if (1 == 1) {

            $return['txt'] .= '<br/><br/><b>смотрим на время ожидания</b>';

            $re['ocenka_time'] = 5;

            $tyty = 'cold';
            $text .= PHP_EOL . '<br/>время ожидания ' . $tyty;

            if (!empty($ar['timeo_' . $tyty]) && !empty($ar['norm_time_wait_norm_' . $tyty]) && $ar['timeo_' . $tyty] <= $ar['norm_time_wait_norm_' . $tyty]) {

                $text .= ' норм ( 5 )';
                $return['txt'] .= '<br/>время цеха ' . $tyty . ' ( ' . $ar['timeo_' . $tyty] . ' < ' . $ar['norm_time_wait_norm_' . $tyty] . ' ) меньше максимума (нормы) : оценка 5';
            } else {
                $text .= ' не норм ( 3 )';
                $re['ocenka_time'] = 3;
                $return['txt'] .= '<br/>время цеха ' . $tyty . ' ( ' . $ar['timeo_' . $tyty] . ' > ' . $ar['norm_time_wait_norm_' . $tyty] . ' ) больше максимума (нормы) : оценка 3';
            }

            $tyty = 'hot';

            $text .= PHP_EOL . '<br/>время ожидания ' . $tyty;
            if (empty($ar['norm_time_wait_norm_' . $tyty])) {
                $text .= ' параметра не указано, оценка максимум ( 5 )';
                $return['txt'] .= '<br/>время (нормы) цеха ' . $tyty . ' не указано, не считаем';
            } else {
                if (!empty($ar['timeo_' . $tyty]) && !empty($ar['norm_time_wait_norm_' . $tyty]) && $ar['timeo_' . $tyty] <= $ar['norm_time_wait_norm_' . $tyty]) {
                    $text .= ' норм ( 5 )';
                    $return['txt'] .= '<br/>время цеха ' . $tyty . ' ( ' . $ar['timeo_' . $tyty] . ' < ' . $ar['norm_time_wait_norm_' . $tyty] . ' ) меньше максимума (нормы) : оценка 5';
                } else {
                    $text .= ' не норм ( 3 )';
                    $re['ocenka_time'] = 3;
                    $return['txt'] .= '<br/>время цеха ' . $tyty . ' ( ' . $ar['timeo_' . $tyty] . ' > ' . $ar['norm_time_wait_norm_' . $tyty] . ' ) больше максимума (нормы) : оценка 3';
                }
            }

            $tyty = 'delivery';
            $text .= PHP_EOL . '<br/>время ожидания ' . $tyty;
            if (empty($ar['norm_time_wait_norm_' . $tyty])) {
                $text .= ' параметра не указано, оценка максимум ( 5 )';
                $return['txt'] .= '<br/>время (нормы) цеха ' . $tyty . ' не указано, не считаем';
            } else {
                if (!empty($ar['timeo_' . $tyty]) && !empty($ar['norm_time_wait_norm_' . $tyty]) && $ar['timeo_' . $tyty] <= $ar['norm_time_wait_norm_' . $tyty]) {
                    $text .= ' норм ( 5 )';
                    $return['txt'] .= '<br/>время цеха ' . $tyty . ' ( ' . $ar['timeo_' . $tyty] . ' < ' . $ar['norm_time_wait_norm_' . $tyty] . ' ) меньше максимума (нормы) : оценка 5';
                } else {
                    $text .= ' не норм ( 3 )';
                    $re['ocenka_time'] = 3;
                    $return['txt'] .= '<br/>время цеха ' . $tyty . ' ( ' . $ar['timeo_' . $tyty] . ' > ' . $ar['norm_time_wait_norm_' . $tyty] . ' ) больше максимума (нормы) : оценка 3';
                }
            }
        }

        $re['ocenka'] = 5;

        if ($re['ocenka_time'] == 3) {
            $re['ocenka'] = 3;
        } elseif ($re['ocenka_time'] == 3) {
            $re['ocenka'] = 3;
        } elseif ($re['ocenka_naruki'] == 3) {
            $re['ocenka'] = 3;
        }

        $return['txt'] .= '<br/>итоговая общая оценка: ' . $re['ocenka'];
        $re['txt'] = $return['txt'] . '<hr><b>время обработки</b>' . $return['time'];

        return \f\end3($text, true, $re);
    }

    public static function getSetupJobmanOnSp($db, $date_start, $date_finish = null, $module_man_job_on_sp = 'jobman_send_on_sp', $mod_spec_jobday = '050.job_in_sp') {

//        echo '<hr>' . __FILE__ . ' #' . __LINE__
//        . '<br/>' . __FUNCTION__
//        . '<hr>';
//        \f\pa($date_start);
//        \f\pa($date_finish);
//        $ee = self::whereJobmans($db, $date_start, $date_start);
//        \f\pa($ee, 2, '', 'self::whereJobmans ' . __FILE__ . ' ' . __LINE__);

        $plus_minus_checks = \Nyos\mod\JobBuh::getChecksMinusPlus($db, $date_start, $date_finish);
        //\f\pa($plus_minus_checks, 2, '', '$plus_minus_checks');
//        $plus_minus_checks = \Nyos\mod\JobDesc:: Buh::getChecksMinusPlus($db, $date_start, $date_finish);
//        \f\pa($plus_minus_checks, 2, '', '$plus_minus_checks');
//        foreach ($plus_minus_checks['items'] as $jobman => $v1) {
//            foreach ($v1 as $k => $v) {
//                $checks[$jobman][$v['date']] = $v;
//            }
//        }
//        // $plus_minus_checks = '';
//\f\pa($checks, 2, '', '$checks');

        $ocenki = [];

        if (!empty($plus_minus_checks['items']))
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
            // $spec_day = \Nyos\mod\items::getItemsSimple($db, $mod_spec_jobday);
            $spec_day = \Nyos\mod\items::get($db, $mod_spec_jobday);
//\f\pa($spec_day, 2, '', '$spec_day');
            $spec = [];
//            foreach ($spec_day['data'] as $k => $v) {
//                if ($v['dop']['date'] >= $ds && $v['dop']['date'] <= $df) {
//                    $spec[$v['dop']['jobman']][$v['dop']['date']] = $v['dop'];
//                }
//            }
            foreach ($spec_day as $k => $v) {
                if ($v['date'] >= $ds && $v['date'] <= $df) {
                    $spec[$v['jobman']][$v['date']] = $v;
                }
            }
//\f\pa($spec, 2, '', '$spec');
        }

        // назначения сорудников на сп
        // $jobs = \Nyos\mod\items::getItemsSimple($db, self::$mod_man_job_on_sp);
        $jobs = \Nyos\mod\items::getItemsSimple3($db, self::$mod_man_job_on_sp);
//        \f\pa($jobs, 2, '', '$jobs');

        $jobin = [];

        foreach ($jobs as $k => $v) {
            // $v['fl'] = __FILE__ . ' #' . __LINE__;
            $jobin[$v['date']][] = $v;
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
        // если ищем несколько дат
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

                if (self::$one_date === true) {
                    $return[$v['jobman']] = $v;
                } else {
                    $return['jobs'][$v['jobman']][$v['date']] = $v;
                }
            }

//\f\pa($return);
        }
        // если ищем одну дату
        else {
            foreach ($j2 as $jobman => $v2) {
                foreach ($v2 as $date => $v) {
                    if (self::$one_date === true) {
                        $return[$v['jobman']] = $v;
                    } else {
                        $return['jobs'][$v['jobman']][$ds] = $v;
                    }
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

        \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
                . ' ON mid.id_item = mi.id '
                . ' AND mid.name = \'sale_point\' '
                . ' AND mid.value = :sp ';
        \Nyos\mod\items::$var_ar_for_1sql[':sp'] = $sp;

        if (!empty($date_fin)) {
            \Nyos\mod\items::$join_where .= ' INNER JOIN `mitems-dops` mid2 '
                    . ' ON mid2.id_item = mi.id '
                    . ' AND mid2.name = \'date\' '
                    . ' AND mid2.value_date >= :ds '
                    . ' AND mid2.value_date <= :df '
            ;
            \Nyos\mod\items::$var_ar_for_1sql[':ds'] = $date;
            \Nyos\mod\items::$var_ar_for_1sql[':df'] = $date_fin;
        } else {
            \Nyos\mod\items::$join_where .= ' INNER JOIN `mitems-dops` mid2 '
                    . ' ON mid2.id_item = mi.id '
                    . ' AND mid2.name = \'date\' '
                    . ' AND mid2.value_date = :ds '
            ;
            \Nyos\mod\items::$var_ar_for_1sql[':ds'] = $date;
        }

        // $norms = \Nyos\mod\items::get($db, 'sale_point_parametr');
        $norms = \Nyos\mod\items::get($db, self::$mod_norms_day);

        $rr = [];

        foreach ($norms as $k => $v) {

            // если ищем одну даты то отправляем этот 1 день
            if ($date_fin === null) {
                return $v;
            }

            // если ищем несколько дат, то собираем по датам в массив и возвращаем
            $rr[$v['date']] = $v;
        }

        return $rr;
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
