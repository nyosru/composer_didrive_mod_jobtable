<?php

/**
  класс модуля
 * */

namespace Nyos\mod;

if (!defined('IN_NYOS_PROJECT'))
    throw new \Exception('Сработала защита от розовых хакеров, обратитесь к администрратору');

class JobDesc {

    public static $dir_img_server = false;
    public static $cash = [];

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
     * получаем какие цены по датам у должностей на точке продаж (старая)
     * @param type $db
     * @param type $folder
     * @param type $module_sp
     * @param type $module_slary
     * @return type
     */
    public static function compileSalarysJobmans($db, $date, $module_sp = 'sale_point', $module_slary = '071.set_oplata') {




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

    public static function getSalaryJobman($db, $sp, $dolgn, $date, $module_sp = 'sale_point', $module_slary = '071.set_oplata') {

        //echo '<br/>' . $sp . ', ' . $dolgn . ', ' . $date;

        if (isset(self::$cash['salary_now'][$sp][$dolgn][$date]))
            return self::$cash['salary_now'][$sp][$dolgn][$date];

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

        // \f\pa(self::$cash['salarys']);

        /**
         * достаём зп этой должности этой тп и этой даты
         */
        $oborot1 = \Nyos\mod\IikoOborot::whatMonthOborot($db, $sp, substr($date, 5, 2), substr($date, 0, 4));
        //\f\pa($oborot1);
        $oborot = $oborot1['data']['oborot'];

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

        /**
         * тащим кто кем и где работал под дням в периоде
         */
        $jobinsp = \Nyos\mod\JobDesc::getSetupJobmanOnSp($db, $date);
        // \f\pa($jobinsp, 2, '', '$jobinsp');

        $checki = \Nyos\mod\items::getItemsSimple($db, '050.chekin_checkout');
        // \f\pa($checki,2,'','$checki'); // exit;

        $dt1 = date('Y-m-d 05:00:01', strtotime($date));
        $dt2 = date('Y-m-d 23:50:01', strtotime($date));

        $return = array('hours' => 0);

        foreach ($checki['data'] as $k => $v) {

            $now_d = substr($v['dop']['start'], 0, 10);

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
     * ищем где работают люди
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

        return $ret2;
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

    public static function getSetupJobmanOnSp($db, $date_start, $date_finish = null, $module_man_job_on_sp = 'jobman_send_on_sp', $mod_spec_jobday = '050.job_in_sp') {


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
     * получаем сотрудников кто работал в указанном промежутке
     * @param type $db
     * @param type $date_start
     * @param type $date_finish
     * @param type $module_man_job_on_sp
     * @param type $mod_spec_jobday
     * @return type
     */
    public static function getJobmansOnTime($db, $date_start, $date_finish = null, $module_man_job_on_sp = 'jobman_send_on_sp', $mod_spec_jobday = '050.job_in_sp') {

        echo '<br/>'.$date_start.' , '.$date_finish.'<br/>';
        
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
