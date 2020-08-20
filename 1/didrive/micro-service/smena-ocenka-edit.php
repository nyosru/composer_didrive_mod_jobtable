<?php

try {

//    $date = $in['date'] ?? $_REQUEST['date'] ?? null;
//
//    if (empty($date))
//        throw new \Exception('нет даты');

    require_once '0start.php';

    //\f\pa($_REQUEST);
//
//  [ee] => 1
//    [ocenka] => 5
//    [name] => ocenka
//    [ajax_module] => 050.chekin_checkout
//    [dop_name] => ocenka
//    [item_id] => 156131
//    [s] => befd1d17e57b86f3e941ffe90f28ab61    
//    
    
    // серкет норм
    if (!empty($_REQUEST['s']) && !empty($_REQUEST['ajax_module']) 
            && !empty($_REQUEST['dop_name']) && !empty($_REQUEST['item_id']) 
            && \Nyos\Nyos::checkSecret($_REQUEST['s'], $_REQUEST['ajax_module'] . $_REQUEST['dop_name'] . $_REQUEST['item_id']) !== false) {

        $sql = 'UPDATE `mod_'. \f\translit( $_REQUEST['ajax_module'] , 'uri2' ).'` d '
                . ' SET `d`.`'. addslashes($_REQUEST['dop_name']).'` = :new_val '
                . ' WHERE d.id = :id '
                . ' LIMIT 1 ;';
        //\f\pa($sql);
        $ff = $db->prepare($sql);

        // \f\pa($var_in_sql);
        $ff->execute([
                // ':module' => \Nyos\mod\JobDesc::$mod_bonus
                ':new_val' => $_REQUEST['ocenka'],
                ':id' => $_REQUEST['item_id']
                ]);
        \f\end2( 'сохранили' );
    }
    
    \f\end2( 'не сохранили' , false  );

} catch (Exception $exc) {

    // echo '<pre>';    print_r($exc);     echo '</pre>';
    // echo $exc->getTraceAsString();
    \f\end2( 'сохранили' , false , $exc );
}