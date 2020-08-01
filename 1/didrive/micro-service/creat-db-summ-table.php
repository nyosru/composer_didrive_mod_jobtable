<?php

/**
 * создание обобщённых табллиц данных
 * + суммы оборота за месяц
 */
if (isset($skip_start) && $skip_start === true) {
    
} else {
    require_once '0start.php';
    $skip_start = false;
}

try {

    $sql = 'SELECT * FROM `temp_oborot` LIMIT 1 ';
    $ff10 = $db->prepare($sql);
    $ff10->execute();
    $r = $ff10->fetch();
    // if ($ff10->rowCount() != 1) {
    // $r = $ff1->rowCount();
    // \f\pa($r);
    //    if (empty($r))
} catch (\PDOException $ex) {

    // \f\pa($ex);
    // echo '<br/>ошибка в первом запросе ';

    if (strpos($ex->getMessage(), 'not found') !== false) {

        // echo '<br/>создаём таблицу ';

        $ff = $db->prepare('DROP TABLE IF EXISTS `temp_oborot`;');
        $ff->execute();

        $ff = $db->prepare('CREATE TABLE `temp_oborot` (
            `date_y` int(4) NOT NULL,
            `date_m` int(2) NOT NULL,
            `sale_point` int(3) NOT NULL,
            `oborot` int(9) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=\'суммы оборота за месяц в точках за месяц\';');
        $ff->execute();
        $ff = $db->prepare('ALTER TABLE `temp_oborot`
            ADD KEY `sale_point` (`sale_point`),
            ADD KEY `date_m` (`date_m`),
            ADD KEY `date_y` (`date_y`);');
        $ff->execute();
        $ff = $db->prepare('COMMIT;');
        $ff->execute();
    }
}

try {


    $sql = 'SELECT '
            . ' sale_point '
            . ' , date '
            . ' , DATE_FORMAT(`date`, \'%Y\') as date_y '
            . ' , DATE_FORMAT(`date`, \'%m\') as date_m '
            // . ' , DATE_FORMAT(`date`, \'%Y-%m\') as period '
            . ' , CASE
                WHEN oborot_hand IS NOT NULL 
                   THEN oborot_hand
                   ELSE oborot_server
                END as oborot '
            . ' , oborot_hand '
            . ' , oborot_server '
//            . ' , SUM( oborot ) '
            . ' FROM `mod_sale_point_oborot` '
            . ' WHERE '
            //. ' ( '
            . ' `date` LIKE \'' . date('Y-m', strtotime(date('Y-m-d', $_SERVER['REQUEST_TIME']) . ' -1 month')) . '-%\' '
            . ' OR '
            . ' `date` LIKE \'' . date('Y-m') . '-%\' '
            . ' ORDER BY date ASC '
    //. ' ) '
    ;
    // echo $sql;
    $ff1 = $db->prepare($sql);
    $ff1->execute();
    // \f\pa($ff1->fetchAll(),2);

    $res = [];
    $delete = [];
    while ($r = $ff1->fetch()) {

//        if ($r['sale_point'] == 3125)
//            echo '<br/>' . $r['sale_point'] . ' | ' . $r['date'] . ' | ' . round($r['oborot'], 1) . ' / ' . $r['oborot'];

        if (!isset($res[$r['sale_point']][$r['date_y']][$r['date_m']])) {
            $res[$r['sale_point']][$r['date_y']][$r['date_m']] = round($r['oborot'], 1);
            $delete[$r['date_y']][$r['date_m']] = 1;
        } else {
            // \f\pa( [ $res[$r['sale_point']][$r['date_y']][$r['date_m']] , $r['oborot'] ] );
            $res[$r['sale_point']][$r['date_y']][$r['date_m']] = $res[$r['sale_point']][$r['date_y']][$r['date_m']] + round($r['oborot'], 1);
        }

//        if ($r['sale_point'] == 3125)
//            echo $res[$r['sale_point']][$r['date_y']][$r['date_m']];
    }

    // \f\pa($res);
    // \f\pa($delete);

    $sql = '';

    foreach ($delete as $y => $a) {
        foreach ($a as $m => $a1) {
            $sql .= (!empty($sql) ? ' OR ' : '' ) . ' ( `date_y` = \'' . $y . '\' AND `date_m` = \'' . $m . '\' ) ';
        }
    }

    // echo 'DELETE FROM `temp_oborot` WHERE '.$sql;
    // \f\pa( 'DELETE FROM `temp_oborot` WHERE ' . $sql );
    $ff12 = $db->prepare('DELETE FROM `temp_oborot` WHERE ' . $sql);
    $ff12->execute();

    $in = [];

//    echo '<table>';

    foreach ($res as $sp => $a1) {
        foreach ($a1 as $y => $a2) {
            foreach ($a2 as $m => $oborot) {

//                if ($sp == 3125)
//                    echo '<tr><td>' . $sp . '</td><td>' . $y . '</td><td>' . $m . '</td><td>' . $oborot . '</td></tr>';

                $in[] = [
                    'sale_point' => $sp,
                    'date_y' => $y,
                    'date_m' => $m,
                    'oborot' => ceil($oborot)
                ];
            }
        }
    }

    // \f\pa($in);
    \f\db\sql_insert_mnogo($db, 'temp_oborot', $in);

    \f\end2('обновлённых записей ' . sizeof($in));
} catch (Exception $exc) {

    echo '<pre>';
    print_r($exc);
    echo '</pre>';
    // echo $exc->getTraceAsString();
}


die(__FILE__ . ' #' . __LINE__);

//    
//    
//    // \f\pa($_REQUEST);
//    // echo '<h3>удаляем все автобонусы</h3>';
//
//    $date_start = date('Y-m-01', strtotime($_REQUEST['date']. ' -1 month') );
//    $date_finish = date('Y-m-d', strtotime($date_start . ' +1 month -1 day'));
//   
//    $sql = 'SELECT * '
//            . ' SET `mi`.`status` = \'delete\' '
//            . ' WHERE mi.`module` = :module AND mi.`id` IN (' . $ids . ') '
//            . ' ;';
//
//    $sql = '
//        SELECT 
//            SUM(`summ`),
//            DATE_FORMAT(`data`, \'%Y-%m\') as period
//        FROM `tablename`
//        WHERE 
//            `data` >= DATE_FORMAT(CURRENT_DATE - INTERVAL 11 MONTH, \'%Y-%m-01\')
//        GROUP BY period
//        ;';
//    \f\pa($sql);
//    $ff = $db->prepare($sql);
//    
//    // \f\pa($var_in_sql);
//    $ff->execute([':module' => \Nyos\mod\JobDesc::$mod_bonus]);
//    
//die();
//    
//    \f\Cash::deleteKeyPoFilter([\Nyos\mod\JobDesc::$mod_bonus]);
//
//    \Nyos\mod\items::$search['auto_bonus_zp'] = 'da';
//    \Nyos\mod\items::$between_date['date_now'] = [$date_start, $date_finish];
//// \Nyos\mod\items::$return_items_header = true;
//    \Nyos\mod\items::$show_sql = true;
//    $items = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_bonus);
//    \f\pa($items, 2);
//
//    if( !empty($items) ){
//    
//    $ids = implode(', ', array_keys($items));
//    \f\pa($ids);
//
//    $sql = 'UPDATE `mitems` mi '
//            . ' SET `mi`.`status` = \'delete\' '
//            . ' WHERE mi.`module` = :module AND mi.`id` IN (' . $ids . ') '
//            . ' ;';
//    \f\pa($sql);
//    $ff = $db->prepare($sql);
//    
//    // \f\pa($var_in_sql);
//    $ff->execute([':module' => \Nyos\mod\JobDesc::$mod_bonus]);
//    }else{
//        echo '<br/>нечего удалять';
//    }
//    echo '<br/>' . __FILE__ . ' #' . __LINE__;
//
//    die('удалено ' . sizeof($items));
//    