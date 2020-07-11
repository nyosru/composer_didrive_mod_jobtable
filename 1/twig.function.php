<?php

/**
  определение функций для TWIG
 */
//creatSecret
// $function = new Twig_SimpleFunction('creatSecret', function ( string $text ) {
//    return \Nyos\Nyos::creatSecret($text);
// });
// $twig->addFunction($function);



$function = new Twig_SimpleFunction('jobdesc__get_dolgnosti', function ( $db, $mod = '061.dolgnost' ) {

    // \f\Cash::deleteKeyPoFilter( ['dolgnosti'] );
    $dolgn = \f\Cash::getVar('dolgnosti');
    
    // если не пусто то вернули из мемкеша
    if (!empty($dolgn)) {
        $dolgn['cash'] = 'da';
    }
    // если пусто то достаём по новой и записываем в мемкеш
    else {
        $dolgn['data'] = $dolgn['sort'] = \Nyos\mod\items::get($db, $mod);
        usort($dolgn['sort'], "\\f\\sort_ar_sort_desc");
        \f\Cash::setVar('dolgnosti', $dolgn, 0);
        $dolgn['cash'] = 'no';
        // \f\pa($dolgn,2,'','$e2');
    }

    return $dolgn;
});
$twig->addFunction($function);



/**
 * достаём отметки за день
 */
$function = new Twig_SimpleFunction('jobdesc__get_metki', function ( $db, int $jm, int $sp, string $date, $mod = '072.metki' ) {

    // если нет переменной то не пишем кеш
    $cash_var = 'jobdesc__mod' . $mod . '_jm'.$jm.'_d' . $date;
    //\f\pa($cash_var);

    // если не установлено то запоминаем на всегда
    // $cash_time_sec = 0;

    // если есть то показываем и считает время и память
    // $show_timer = rand(0, 9999);

    if (!empty($show_timer)) {
        \f\timer_start($show_timer);
    }

    if (!empty($show_timer)) {
        echo '<br/>#' . __LINE__ . ' var ' . $cash_var;
    }

    $keys = \f\Cash::getVar('keys');
    // \f\pa($keys);

    if (isset($keys[$cash_var])) {

        $return = \f\Cash::getVar($cash_var);

        if (!empty($show_timer))
            echo '<br/>#' . __LINE__ . ' данные из кеша';
    }

    // если вывод пустой
    if (!isset($keys[$cash_var])) {

        $return = [];

        if (!empty($show_timer))
            echo '<br/>#' . __LINE__ . ' считаем данные и пишем в кеш';

        \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
                . ' ON mid.id_item = mi.id '
                . ' AND mid.name = \'date\' '
                . ' AND mid.value_date = :d '
//            . ' AND mid.value_date <= :df '
                . ' INNER JOIN `mitems-dops` mid2 '
                . ' ON mid2.id_item = mi.id '
                . ' AND mid2.name = \'sale_point\' '
                . ' AND mid2.value = :sp '
                . ' INNER JOIN `mitems-dops` mid3 '
                . ' ON mid3.id_item = mi.id '
                . ' AND mid3.name = \'jobman\' '
                . ' AND mid3.value = :jm '
        ;
        \Nyos\mod\items::$var_ar_for_1sql[':sp'] = $sp;
        \Nyos\mod\items::$var_ar_for_1sql[':d'] = $date;
        \Nyos\mod\items::$var_ar_for_1sql[':jm'] = $jm;
        $return = \Nyos\mod\items::get($db, $mod);
        // \Nyos\mod\items::$var_ar_for_1sql = [];
        // usort($dolgn['sort'], "\\f\\sort_ar_sort_desc");
        // \f\Cash::setVar( , $d, 0);
        // $d['cash'] = 'no';
        // \f\pa($d, 2, '', '$e2');
        // тут супер код делающий $return конец

        $return['save_dt'] = date('Y-m-d H:i:s');
        \f\Cash::setVar($cash_var, $return, ( $cash_time_sec ?? 0));

        $return['cash'] = 'no';
    }

    if (!empty($show_timer))
        echo '<br/>#' . __LINE__ . ' ' . \f\timer_stop($show_timer);

    return $return;
});
$twig->addFunction($function);
