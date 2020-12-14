<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo '<a href="?creat=month_oborot" >создать представление month_oborot</a>';


if (isset($_REQUEST['creat']) && $_REQUEST['creat'] == 'month_oborot') {

    // ob_start('ob_gzhandler');

    require_once './../../../didrive/base/start-for-microservice.php';

    echo '<br/>запущено ' . __LINE__;

    $sql = 'CREATE OR REPLACE VIEW v__month_oborot
            AS SELECT 
                MONTH(o.date) date_m,
                YEAR(o.date) date_y,
                SUM(o.oborot_server) oborot,
                o.sale_point
            FROM `mod_' . \Nyos\mod\JobDesc::$mod_oborots . '` o
                GROUP BY date_y, date_m, o.sale_point
            ';
    $ff = $db->prepare($sql);
    $ff->execute();

    echo '<br/>выполнено ' . __LINE__;

//    $r = ob_get_contents();
//    ob_end_clean();

//    die($r);
    die();

    // \f\end2($r, true);
}

die('<br/><br/>и тишина ..');
