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
     * получаем какие цены по датам у должностей на точке продаж
     * @param type $db
     * @param type $folder
     * @param type $module_sp
     * @param type $module_slary
     * @return type
     */
    public static function configGetJobmansSmenas($db, $folder = null, $module_sp, $module_slary) {

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
        $salary = \Nyos\mod\items::getItems($db, $folder, $module_slary, 'show', null);
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
                sort($v2);
                $re2[$point][$dolg] = $v2;
            }
        }

        return $re2;
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
    public static function whereJobmansOnSp($db, $folder = null, $date_start = null, $date_fin = null, $module_man_job_on_sp = 'jobman_send_on_sp') {

//whereJobmansOnSp( $db, $folder, $date_start, $date_finish );

        if ($folder === null)
            $folder = \Nyos\nyos::$folder_now;

        // \f\pa( \Nyos\nyos::$folder_now );
        // $re = [];

        /**
         * назначения сорудников на сп
         */
        $jobs = \Nyos\mod\items::getItems($db, $folder, $module_man_job_on_sp, 'show', null);
        //\f\pa($jobs, 2);

        $d = array('jobs' => []);

        foreach ($jobs['data'] as $k => $v) {
            $v['dop']['d'] = $v;
            $d['jobs'][$v['dop']['date'] . '--' . $v['id']] = $v['dop'];
        }

        krsort($d['jobs']);

        // \f\pa($d['jobs'], 2);

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

            if (strtotime($date_start) >= $u_date_start) {
                $ret2['jobs_on_sp'][$v['sale_point']][$v['jobman']] = 1;
            } else {
                $ret2['jobs_on_sp'][$v['sale_point']][$v['jobman']] = 'hide';
            }

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
 * какие нормы на день сегодня в сп
 * @param type $array
 * @param type $sp
 * @param type $man
 * @param string $date
 * @return type
 */
    public static function whatNormToDay($db, int $sp, string $date) {

   \Nyos\mod\items::$sql_itemsdop_add_where_array = array(
        ':dt1' => date('Y-m-d 05:00:01', strtotime($_REQUEST['date']) )
        ,
        ':dt2' => date('Y-m-d 23:50:01', strtotime($_REQUEST['date']) )
    );
    \Nyos\mod\items::$sql_itemsdop2_add_where = '
        INNER JOIN `mitems-dops` md1 
            ON 
                md1.id_item = mi.id 
                AND md1.name = \'start\'
                AND md1.value_datetime >= :dt1
                AND md1.value_datetime <= :dt2
        ';
    $checki = \Nyos\mod\items::getItemsSimple($db, '050.chekin_checkout', 'show' );
    \f\pa($checki);
         
        
        
        return $ret2;
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

            if (isset($v['date']) && $nowutime >= strtotime($v['date'] . ' 00:00')) {

                $v['d1'] = $date;
                $v['d2'] = $v['date'];

                return $v;
            }
        }

        return false;
    }

}
