<?php

ini_set('display_errors', 'On'); // сообщения с ошибками будут показываться
error_reporting(E_ALL); // E_ALL - отображаем ВСЕ ошибки

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'show_dolgn') {

    require_once './../../../didrive/base/start-for-microservice.php';

//    ob_start('ob_gzhandler');

$a = [
    'month_balans' => 'месячные балансы'
    ];

//    $r = ob_get_contents();
//    ob_end_clean();

    \f\end2($r, true, [ 'views' ]);
}

\f\end2('что то пошло не так', false);
