<?php

ini_set('display_errors', 'On'); // сообщения с ошибками будут показываться
error_reporting(E_ALL); // E_ALL - отображаем ВСЕ ошибки






if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'show_dolgn') {

    require_once './../../../didrive/base/start-for-microservice.php';

    ob_start('ob_gzhandler');

    function show_tr_oplats($v) {
        echo '<tr>'
        . '<td>'
        . ($v['dolgn'] ?? '')
        . ( !empty($v['dolgn']) ? '<br/>'
        . '<small>в&nbsp;автооценке '
        . (!isset($v['calc_auto']) ? '<span style="color:red;">не&nbsp;участвует</span>' : '<span style="color:green;">участвует</span>')
        . '</small>' : '' )
        . '</td>'
        . '<td>'
        . ($v['date'] ?? '')
        . '</td>'
        . '<td>'
        . ( !empty($v['oborot_sp_last_monht_menee']) ? 'Месячный&nbsp;ТО менее&nbsp;'.$v['oborot_sp_last_monht_menee'].'<Br/>' : '' )
        .( !empty($v['bonus_proc_from_oborot']) ? 'Бонус&nbsp;'.$v['bonus_proc_from_oborot'].'%&nbsp;ТО' : '' )
        .( !empty($v['pay_from_day_oborot_bolee']) ? 'ТО за день равно или более '.$v['pay_from_day_oborot_bolee'].'' : '' )
        . '</td>'
        . '<td>'
// \f\pa($v);
        . ($v['ocenka-hour-base'] ?? '')
        . '</td>'
        . '<td>'
// \f\pa($v);
        . ($v['ocenka-hour-3'] ?? '-')
        . '</td>'
        . '<td>'
        . ($v['premiya-3'] ?? '-')
// \f\pa($dolgn2);
        . '</td>'
        . '<td>'
        . ($v['ocenka-hour-5'] ?? '-')
        . '</td>'
        . '<td>'
        . ($v['premiya-5'] ?? '-')
        . '</td>'
        . '<td>'
        . ($v['if_kurit'] ?? '-')
        . '</td>'
        . '</tr>';
    }

    $sps = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_sale_point, 'show', 'id_id');

//    $dolgn = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_dolgn, 'show', 'id_id');
//    \f\pa($dolgn, 2, '', '$dolgn');
//    $pays = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_salary, 'show', 'id_id');
//    \f\pa($pays, 2, '', '$pays');

    $date_start = date('Y-m-01', strtotime($_REQUEST['date_start']));
    $date_fin = date('Y-m-d', strtotime($date_start . ' +1 month -1 day'));

    $oborot = \Nyos\mod\JobBuh::getOborotSpMonth($db, $_REQUEST['sp'], $date_start);
    echo '<h4>' . $sps[$_REQUEST['sp']]['head'] . ' оборот за месяц: ' . number_format($oborot / 1000, 1, '.', '`') . ' т.р.</h4>';

    //\f\pa($_REQUEST);
    
//    $sql = '( SELECT '
//            . ' s.* '
//            . ' , '
//            . ' d.head dolgn '
//            . ' , '
//            . ' CONCAT( d.id, YEAR(s.date), MONTH(s.date), DAY(s.date) ) sort '
//            . 'FROM `' . \f\db_table(\Nyos\mod\JobDesc::$mod_salary) . '` s '
//            . ' LEFT JOIN `' . \f\db_table(\Nyos\mod\JobDesc::$mod_dolgn) . '` d ON d.id = s.dolgnost '
//            . ' WHERE '
//            . ' s.date > :date1 '
//            . ' AND s.date <= :date2 '
//            . ' AND s.sale_point = :sp '
//            . ' ORDER BY s.date DESC ) '
//            . ' UNION '
//            . ' ( SELECT '
//            . ' s.* '
//            . ' , '
//            . ' d.head dolgn '
//            . ' , '
//            . ' CONCAT( d.id, YEAR(s.date), MONTH(s.date), DAY(s.date) ) sort '
//            . 'FROM `' . \f\db_table(\Nyos\mod\JobDesc::$mod_salary) . '` s '
//            . ' LEFT JOIN `' . \f\db_table(\Nyos\mod\JobDesc::$mod_dolgn) . '` d ON d.id = s.dolgnost '
//            . ' WHERE s.date <= :date1 '
//            . ' AND s.sale_point = :sp '
//            . ' ORDER BY s.date DESC ) ';
    
    $sql = 'SELECT '
            . ' s.* '
            . ' , '
            . ' d.head dolgn '
            . ' , '
            . ' d.calc_auto '
            . ' , '
            . ' CONCAT( d.sort, YEAR(s.date), MONTH(s.date), DAY(s.date), d.id ) sort '
            . 'FROM `' . \f\db_table(\Nyos\mod\JobDesc::$mod_salary) . '` s '
            . ' LEFT JOIN `' . \f\db_table(\Nyos\mod\JobDesc::$mod_dolgn) . '` d ON d.id = s.dolgnost '
            . ' WHERE s.date <= :date2 '
            . ' AND s.sale_point = :sp '
            . ' ORDER BY s.date DESC ';
    $ff = $db->prepare($sql);
    $in = [
        ':sp' => $_REQUEST['sp'],
        //':date1' => $date_start,
        ':date2' => $date_fin
    ];
    $ff->execute($in);
    $res = $ff->fetchAll();

    usort( $res, '\\f\\sort_ar_sort_desc' );
    
    //\f\pa($res,2);
    
    echo '<table class=table >'
    . '<thead>'
    . '<tr>'
    . '<th rowspan=2 >Должность</th>'
    . '<th rowspan=2 >Старт</th>'
    . '<th rowspan=2 >Условия, бонусы</th>'
    . '<th rowspan=2 >за час</th>'
    . '<th colspan=2 >оценка 3</th>'
    . '<th colspan=2 >оценка 5</th>'
    . '<th rowspan=2 >если курит</th>'
    . '</tr>'
    . '<tr>'
    . '<th>в час</th>'
    . '<th>бонус</th>'
    . '<th>в час</th>'
    . '<th>бонус</th>'
    . '</tr>'
    . '</thead>'
    . '<tbody>';

    $dolgn_date = '';
    
    foreach ($res as $k => $v) {
        if( $dolgn_date == $v['dolgn'].$v['date'] ){
            $v['dolgn'] = $v['date'] = '';
        }else{
        $dolgn_date = $v['dolgn'].$v['date'];
        }
        show_tr_oplats($v);
    }
    
    //\f\pa($dolgn);
/*
    foreach ($dolgn as $k => $v) {

// echo $v['head'];

        $dolgn2 = \Nyos\mod\JobDesc::getSalaryJobman($db, $_REQUEST['sp'], $v['id'], $date_start);
        \f\pa($dolgn2, 2, '', '$dolgn2');

        if (empty($dolgn2['date']))
            continue;

// \f\pa($dolgn2);
// $dolgn = \Nyos\mod\JobDesc::getSalarisNow($db, $_REQUEST['sp'], $v['id'], $date_fin);
// \f\pa($dolgn);

        show_tr_oplats($v, $dolgn2);

        if ($dolgn2['date'] >= $date_start) {

            $dolgn2 = \Nyos\mod\JobDesc::getSalaryJobman($db, $_REQUEST['sp'], $v['id'], date('Y-m-d', strtotime($dolgn2['date'] . ' -1 day')));

            if (empty($dolgn2['date']))
                continue;
            show_tr_oplats($v, $dolgn2);

            if ($dolgn2['date'] >= $date_start) {

                $dolgn2 = \Nyos\mod\JobDesc::getSalaryJobman($db, $_REQUEST['sp'], $v['id'], date('Y-m-d', strtotime($dolgn2['date'] . ' -1 day')));

                if (empty($dolgn2['date']))
                    continue;

                show_tr_oplats($v, $dolgn2);
            }
        }
    }
*/
    echo '</tbody></table>';

    $r = ob_get_contents();
    ob_end_clean();

    \f\end2($r, true);

// \f\pa($_REQUEST);

    $sps = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_sale_point);
// \f\pa($sps,2);

    $d = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_dolgn);
// \f\pa($d,2);

    \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
            . ' ON mid.id_item = mi.id '
            . ' AND mid.name = \'jobman\' '
            . ' AND mid.value = :user ';
    \Nyos\mod\items::$var_ar_for_1sql[':user'] = $_REQUEST['user'];
    $naznach = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_man_job_on_sp, '');
// \f\pa($e,2,'','nazn');

    \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
            . ' ON mid.id_item = mi.id '
            . ' AND mid.name = \'jobman\' '
            . ' AND mid.value = :user ';
    \Nyos\mod\items::$var_ar_for_1sql[':user'] = $_REQUEST['user'];
    $spec = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_spec_jobday, '');
// \f\pa($spec, 2, '', 'spec');

    foreach ($spec as $k => $v) {
        $v['type'] = 'spec';
        $naznach[] = $v;
    }

    usort($naznach, "\\f\\sort_ar_date");

//\f\pa($naznach, 2, '', '$naznach');

    \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
            . ' ON mid.id_item = mi.id '
            . ' AND mid.name = \'jobman\' '
            . ' AND mid.value = :user ';
    \Nyos\mod\items::$var_ar_for_1sql[':user'] = $_REQUEST['user'];

// echo $_REQUEST['date_start'];
    if (isset($_REQUEST['date_start'])) {

        \Nyos\mod\items::$join_where .= ' INNER JOIN `mitems-dops` mid2 '
                . ' ON mid2.id_item = mi.id '
                . ' AND mid2.name = \'start\' '
                . ' AND mid2.value_datetime >= :ds '
                . ' AND mid2.value_datetime <= :df ';
        \Nyos\mod\items::$var_ar_for_1sql[':ds'] = date('Y-m-01 05:00:00', strtotime($_REQUEST['date_start']));
        \Nyos\mod\items::$var_ar_for_1sql[':df'] = date('Y-m-d 03:00:00', strtotime(\Nyos\mod\items::$var_ar_for_1sql[':ds'] . ' +1 month'));
    }

    $checks = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_checks, '');
    usort($checks, "\\f\\sort_ar_date");

    echo
// '<link rel="stylesheet" href="/didrive/design/css/vendor/bootstrap.min.css" />'

    '<style> '
    . ' .d345 th, '
    . ' .d345 tbody td{ text-align: center; } '
    . ' .d345 tbody td.r{ text-align: right; } '
    . '</style>'
    . '<table class="table table-bordered d345" >'
    . '<thead>'
    . '<tr>'

//    . '<th>статус записи</th>'
//    . '<th>тип</th>'
//    . '<th>точка продаж</th>'
//    . '<th>должность</th>'
//    . '<th>принят</th>'
//    . '<th>уволен</th>'
    . '<th>старт</th>'
    . '<th>конец</th>'
    . '<th>длительность (авто/вручную)</th>'
    . '<th>оценка (авто/вручную)</th>'
    . '<th>тех. статус</th>'
    . '</tr>'
    . '</thead>'
    . '<tbody>';

    $date_start = date('Y-m-01', strtotime($_REQUEST['date_start']));
    $date_finish = date('Y-m-d', strtotime($date_start . ' +1 month -1 day'));


    $last_dolgn = [];

    foreach ($naznach as $k => $v) {

        if (isset($v['type']) && $v['type'] == 'spec')
            continue;

        if ($v['date'] < $date_start) {
            $last_dolgn = $v;
//\f\pa($v);
        }
    }


    if (!empty($last_dolgn)) {
        $v = $last_dolgn;
        echo '<tr>'

//                . '<td>' . ( isset($v['status']) && $v['status'] == 'show' ? 'вкл' : ( isset($v['status']) && $v['status'] == 'hide' ? 'выкл' : ( isset($v['status']) && $v['status'] == 'delete' ? 'удалено' : 'x' ) ) ) . '</td>'
        . '<td>' . $v['date'] . ' ' . ((isset($v['type']) && $v['type'] == 'spec') ? 'спец. назначение' : 'приём на должность') . '</td>'
//                . '<td>' . ( $sps[$v['sale_point']]['head'] ?? 'не определена' ) . '</td>'
//                . '<td class="r" >' . $v['date'] . '</td>'
        . '<td class="r" >' . ($v['date_finish'] ?? '-') . '</td>'
        . '<td colspan=2 >' . ($d[$v['dolgnost']]['head'] ?? '- - -') . '</td>'
//                . '<td>&nbsp;</td>'
        . '<td>' . $v['status'] . '</td>'
        . '</tr>';
    }

    for ($nn = 0; $nn <= 31; $nn++) {

        $date_now = date('Y-m-d', strtotime($date_start . ' +' . $nn . ' day'));
        /**
         * старт дня ( дата время )
         */
        $datetime_start = $date_now . ' 08:00:00';
        /**
         * конец дня ( дата время )
         */
        $datetime_finish = date('Y-m-d 03:00:00', strtotime($datetime_start . ' +1 day'));

        echo '<tr>'
        . '<td colspan=5 >' . $date_now . '</td>'
        . '</tr>';


        foreach ($naznach as $k => $v) {
            if ($v['date'] == $date_now) {
                echo '<tr>'

//                . '<td>' . ( isset($v['status']) && $v['status'] == 'show' ? 'вкл' : ( isset($v['status']) && $v['status'] == 'hide' ? 'выкл' : ( isset($v['status']) && $v['status'] == 'delete' ? 'удалено' : 'x' ) ) ) . '</td>'
                . '<td>' . $v['date'] . ' ' . ((isset($v['type']) && $v['type'] == 'spec') ? 'спец. назначение' : 'приём на должность') . '</td>'
//                . '<td>' . ( $sps[$v['sale_point']]['head'] ?? 'не определена' ) . '</td>'
//                . '<td class="r" >' . $v['date'] . '</td>'
                . '<td class="r" >' . ($v['date_finish'] ?? '-') . '</td>'
                . '<td colspan=2 >' . ($d[$v['dolgnost']]['head'] ?? '- - -') . '</td>'
//                . '<td>&nbsp;</td>'
//                . '<td>&nbsp;</td>'
                . '<td>' . $v['status'] . '</td>'
                . '</tr>';
            }
        }

        foreach ($checks as $k => $v) {
            if ($v['start'] >= $datetime_start && $v['start'] <= $datetime_finish) {

// \f\pa($v);
                echo '<tr>'
//        . '<td>' . ( isset($v['status']) && $v['status'] == 'show' ? 'вкл' : ( isset($v['status']) && $v['status'] == 'hide' ? 'выкл' : ( isset($v['status']) && $v['status'] == 'delete' ? 'удалено' : 'x' ) ) ) . '</td>'
//        . '<td>' . ( ( isset($v['type']) && $v['type'] == 'spec' ) ? 'спец. назначение' : 'приём' ) . '</td>'
//        . '<td>' . ( $sps[$v['sale_point']]['head'] ?? 'не определена' ) . '</td>'
//        . '<td>' . ( $d[$v['dolgnost']]['head'] ?? '- - -' ) . '</td>'
//        . '<td class="r" >' . $v['date'] . '</td>'
//                . '<td xclass="r" >';
//                \f\pa($v);
//                echo '</td>'
                . '<td class="c" >'
                . ($v['start'] ?? '-')
                . '</td>'
// . '<td class="r" >'
                . '<td class="c" >'
                . ($v['fin'] ?? 'x')
                . '</td>'
                . '<td class="r" >' . (!empty($v['hour_on_job_hand']) ? '<strike style="color:gray;" >' . ($v['hour_on_job'] ?? '-') . '</strike> <b>' . $v['hour_on_job_hand'] . '</b>' : ($v['hour_on_job'] ?? 'x')) . '</td>'
                . '<td class="r" >' .
                (!empty($v['ocenka']) ? '<strike style="color:gray;" >' . ($v['ocenka_auto'] ?? '-') . '</strike> <b>' . $v['ocenka'] . '</b>' : ($v['ocenka_auto'] ?? '-')) . '</td>'
//                . '<td>&nbsp;</td>'
                . '<td>' . ($v['status'] == 'show' ? 'норм' : $v['status']) . '</td>'
                . '</tr>';
            }
        }
    }


    echo '</tbody></table>'
    . '<center>'
    . '<p>Если у записи нет увольнения, датой уволнениния считается дата ( -1 день назад ) от следующего приёма на работу</p>'
    . '</center>';


    $r = ob_get_contents();
    ob_end_clean();

    \f\end2($r, true);
}

\f\end2('что то пошло не так', false);
