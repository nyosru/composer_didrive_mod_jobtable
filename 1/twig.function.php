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
        // \f\pa($dolgn,2,'','$e');
        $dolgn['cash'] = 'da';
    }
    // если пусто то достаём по новой и записываем в мемкеш
    else {
        $dolgn['data'] = $dolgn['sort'] = \Nyos\mod\items::get($db, $mod);
        usort($dolgn['sort'], "\\f\\sort_ar_sort_desc");
        \f\Cash::setVar('dolgnosti', $dolgn, 0 );
        $dolgn['cash'] = 'no';
        // \f\pa($dolgn,2,'','$e2');
    }

    return $dolgn;
});
$twig->addFunction($function);
