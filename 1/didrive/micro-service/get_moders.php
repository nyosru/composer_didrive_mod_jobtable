<?php

// получаем список модераторов с точками доступа

try {

    if (isset($skip_start) && $skip_start === true) {
        
    } else {
        require_once '0start.php';
        $skip_start = false;
    }

    $sql = 'SELECT '
            . ' mm.sale_point sp, '
            . ' mm.user_id '
            . ' , '
            . ' u.name '
            . ' , '
            . ' u.family '
            . ' , '
            . ' u.soc_web '
            . ' , '
            . ' u.soc_web_link '
            . ' , '
            . ' u.soc_web_id '
            . ' , '
            . ' u.avatar '
            // . ' , '
            . ' FROM gm_user u '
            . ' LEFT JOIN mod_sale_point_access_moder mm ON u.id = mm.user_id AND mm.status = \'show\' '
            // . ' WHERE mm.status = \'show\' '
            . ' ORDER BY mm.id DESC ; ';

    // \f\pa($sql);
    $ff = $db->prepare($sql);
    // \f\pa($var_in_sql);
    $ff->execute();

    // $r = $ff->fetchAll();
    // \f\pa($r);
    // \f\end2('ok', true, $ff->fetchAll());
    $re = [];
    while ($r = $ff->fetch()) {

        if (!isset($re[$r['user_id']]['data']))
            $re[$r['user_id']]['data'] = $r;

        $re[$r['user_id']]['sps'][] = $r['sp'];

    }

     // \f\pa($re);
    \f\end2('ok', true, [ 'list' => $re ] );
    
} catch (\Exception $exc) {
    echo '<pre>';
    print_r($exc);
    echo '</pre>';
    // echo $exc->getTraceAsString();
}