<?php

// ставим новые нормы дня на всякие дни

try {

//    if (empty($_REQUEST['date']))
//        throw new \Exception('нет даты');
//
//    if (empty($_REQUEST['user']))
//        throw new \Exception('нет пользователя');
//
//    if (empty($_REQUEST['dolgn']))
//        throw new \Exception('нет должности');

    if (isset($skip_start) && $skip_start === true) {
        
    } else {
        require_once '0start.php';
        $skip_start = false;
    }

    if (isset($_REQUEST['s']) && isset($_REQUEST['id']) && \Nyos\nyos::checkSecret($_REQUEST['s'], $_REQUEST['id']) !== false) {
        
    } else {
        
        // \f\pa($_REQUEST);
        throw new \Exception('не', __LINE__ );
        
    }

    // \f\pa($_REQUEST);

    $date_start = date('Y-m-01', strtotime($_REQUEST['date']));
    $date_finish = date('Y-m-d', strtotime($date_start . ' +1 month -1 day'));

    $delete = ['sale_point' => (int) $_REQUEST['in']['sale_point']];

    $in_db = $_REQUEST['in'];
    // $in_db = ['sale_point' => (int) $_REQUEST['in']['sale_point'] ];



    $dates = [];
    $in_data = [];

    for ($i = 0; $i <= 35; $i++) {

        $now = date('Y-m-d', strtotime($date_start . ' +' . $i . ' day'));

        if ($now <= $date_finish) {

            $nowdn = date('w', strtotime($now));
            // echo '<br/>' . $nowdn;
            
            if ( $now == $_REQUEST['date'] ) {

                $delete['date'][] = $now;
                $in_data[] = [ 'date' => $now ];

            } else if( !empty($_REQUEST['copyto']) && in_array($nowdn, $_REQUEST['copyto'])) {

                $delete['date'][] = $now;
                $in_data[] = [ 'date' => $now ];
                // echo '+'.$now;
            }
//            else{
//                echo '-'.$now;
//            }
        }
    }

    // \f\pa($delete);

    \Nyos\mod\items::deleteItemForDops($db, \Nyos\mod\JobDesc::$mod_norms_day, $delete);

    // foreach( )

    $in_db = $_REQUEST['in'];

    // \f\pa( \Nyos\mod\JobDesc::$mod_norms_day );
    
    $res = \Nyos\mod\items::adds($db, \Nyos\mod\JobDesc::$mod_norms_day, $in_data, $in_db);

// return \f\end3('ok', true , $res );

    \f\end2('<div class="warn" style="padding:5px;" >'
            . '<b>параметры установлены</b>'
            . '<br/>с ' . implode(', ', $delete['date'] )
            . '</div>', true);
    
} catch ( \Exception $exc) {

    // \f\end2( [ $_REQUEST, $exc ]  );
    
    echo '<pre>'; 
    print_r($_REQUEST);
    print_r($exc);
    echo '</pre>';
    // echo $exc->getTraceAsString();
}