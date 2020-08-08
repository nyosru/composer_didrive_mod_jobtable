<?php

ob_start('ob_gzhandler');

try {

//    $date = $in['date'] ?? $_REQUEST['date'] ?? null;
//
//    if (empty($date))
//        throw new \Exception('нет даты');

    if (isset($skip_start) && $skip_start === true) {
        
    } else {
        require_once '0start.php';
        $skip_start = false;
    }


    // \f\pa($_REQUEST);

    $in_sql = [':jm' => $_REQUEST['user']];

    $sql = ' SELECT 
                o.id 
                , o.sale_point
                , o.dolgnost
                , o.date
                , o.smoke
                , o.date_finish
                , o.status
                , d.head position_name
                , \'norm\' type 

            FROM mod_jobman_send_on_sp o '
            . ' LEFT JOIN mod_061_dolgnost d ON d.id = o.dolgnost AND d.status = \'show\' '
            . ' WHERE '
            . ' o.jobman = :jm '
            // . ' ORDER BY o.date DESC '
            . ' UNION ALL '
            . ' SELECT 
                s.id 
                , s.sale_point
                , s.dolgnost
                , s.date
                , s.smoke
                , \'\' date_finish
                , s.status
                , d.head position_name
                , \'spec\' type 

            FROM mod_050_job_in_sp s '
            . ' LEFT JOIN mod_061_dolgnost d ON d.id = s.dolgnost AND d.status = \'show\' '
            . ' WHERE '
            . ' s.jobman = :jm '

            // . ' ORDER BY s.date DESC '
            . ' ; '
    ;
    // \f\pa($sql);
    $ff = $db->prepare($sql);
    // \f\pa($in_sql);
    $ff->execute($in_sql);
    $return = $ff->fetchAll();

    usort($return, "\\f\\sort_ar_date_desc");

//        return $return;
    // $re__sp_position_date_d = [];
//        while ($r = $ff->fetch()) {
//            // $re__sp_position_date_d[$r['sale_point']][$r['dolgnost']][$r['date']][] = $r;
//            self::$ar_pays__sp_position_d[$r['sale_point']][$r['dolgnost']][] = $r;
//        }
//
    // \f\pa($return);


    if (isset($_REQUEST['answer']) && $_REQUEST['answer'] == 'json') {

        $r = ob_get_contents();
        ob_end_clean();

        \f\end2('ок', true, ['req' => $_REQUEST, 'data' => $return]);
    } else {

        echo '<table class="table" >
            <thead>
            <tr>
            <th>Точка продаж</th>
            <th>Должность</th>
            <th>Назначение</th>
            <th>Дата назначение ( и увольнения )</th>
            <th>Статус</th>
            </tr>
            </thead>
            ';

        foreach ($return as $k => $v) {

            echo '<tr style="'. ( $v['type'] == 'spec' ? ' background-color: rgba( 255,255,0,0.2); ' : '' ) .' " >'
            . '<td>' . ( $v['type'] == 'norm' ? 'Прием на работу' : ( $v['type'] == 'spec' ? 'Спец. назначение' : '' ) ) . '</td>'
            . '<td>' . $v['position_name'] . '</td>'
            . '<td>' . $v['date'] . ( !empty( $v['date_finish'] ) ? ' ('.$v['date_finish'].')' : '' ) . '</td>'
            . '<td>' . $v['status'] . '</td>'
            . '</tr>';
        }

        echo '</table>';

        $r = ob_get_contents();
        ob_end_clean();

        \f\end2( $r , true);
    }
} catch (\Exception $exc) {

    echo '<pre>';
    print_r($exc);
    echo '</pre>';
    // echo $exc->getTraceAsString();
    $r = ob_get_contents();
    ob_end_clean();

    \f\end2('<h3>Что-то пошло не так</h3> ' . $r, false, $_REQUEST);
}