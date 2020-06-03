<?php

if (isset($_GET['newtype']{
                0})) {
    $_SESSION['newtype'] = $_GET['newtype'];
    \f\redirect('/', 'i.didrive.php');
}


//\f\pa($_POST);
//echo '<br/>';
//echo '<br/>';
//echo '<br/>';
//echo '<br/>';
//echo '<br/>';
//\f\pa($_SESSION);
// \f\Cash::allClear();
// $vv['tpl_body'] = '';



$vv['tpl_body'] = \f\like_tpl('body', dir_site_module_nowlev_tpldidr, dir_mods_mod_vers_didrive_tpl, DR);

/**
 * быстрый поиск в списке
 */
// $vv['in_body_end'][] = '<script src="/vendor/didrive_mod/jobdesc/1/didrive/timeo.js" />';
$vv['in_body_end'][] = '<script>
    
        //jQuery extension method:
        jQuery.fn.filterByText = function(textbox) {
          return this.each(function() {
            var select = this;
            var options = [];
            $(select).find(\'option\').each(function() {
              options.push({
                value: $(this).val(),
                text: $(this).text()
              });
            });
            $(select).data(\'options\', options);

            $(textbox).bind(\'change keyup\', function() {
              var options = $(select).empty().data(\'options\');
              var search = $.trim($(this).val());
              var regex = new RegExp(search, "gi");

              $.each(options, function(i) {
                var option = options[i];
                if (option.text.match(regex) !== null) {
                  $(select).append(
                    $("<option>").text(option.text).val(option.value)
                  );
                }
              });
            });
          });
        };

        // You could use it like this:

        $(function() {
          $("select.select_filtered").filterByText($("input#filtr_fio"));
          $("select.select_addspec").filterByText($("input#filtr_specfio"));
        });
    
    </script>';

// $vv['in_body_end'][] = '<script src="' . DS . 'vendor' . DS . 'didrive' . DS . 'base' . DS . 'js.lib' . DS . 'jquery.debounce-1.0.5.js"></script>';
$vv['in_body_end'][] = '<script defer="defer" src="' . DS . 'vendor' . DS . 'didrive' . DS . 'base' . DS . 'js.lib' . DS . 'jquery.ba-throttle-debounce.min.js"></script>';


// if (!empty($_REQUEST['sp'])) {

// if (1 == 1) {
if ( isset($_SESSION['newtype']) && $_SESSION['newtype'] == 1 ) {
    
    if (empty($vv['dihead']))
        $vv['dihead'] = '';

    echo '<div style="position: fixed; bottom: 100px; right: 50px; width: 350px;" >';

    $dirs_for_scan = [
        __DIR__ . DS . 'dist' . DS . 'assets' . DS . 'css' . DS => '/vendor/didrive_mod/jobdesc/1/didrive/dist/assets/css/',
        __DIR__ . DS . 'dist' . DS . 'assets' . DS . 'js' . DS => '/vendor/didrive_mod/jobdesc/1/didrive/dist/assets/js/',
        __DIR__ . DS . 'dist' . DS . 'css' . DS => '/vendor/didrive_mod/jobdesc/1/didrive/dist/css/',
        __DIR__ . DS . 'dist' . DS . 'js' . DS => '/vendor/didrive_mod/jobdesc/1/didrive/dist/js/',
    ];

    foreach ($dirs_for_scan as $d => $dir_local) {

// echo '<br/>#'.__LINE__.' '.__DIR__;
        if (is_dir($d)) {

            $list_f = scandir($d);
            foreach ($list_f as $v) {

                if( !isset($v{5}) )
                continue;
                
                // echo '<br/>#' . __LINE__ . ' ++1++ ' . $v;

                if (strpos($v, '.css') !== false && strpos($v, 'app.') !== false) {
                    echo '<br/>#' . __LINE__ . ' ' . $v;
                    $vv['dihead'] .= '<link href="' . $dir_local . $v . '" rel="stylesheet">';
                }

                if (strpos($v, '.js') !== false && ( strpos($v, 'app.') !== false || strpos($v, 'chunk') !== false ) ) {
                    echo '<br/>#' . __LINE__ . ' ' . $v;
                    $vv['in_body_end'][] = '<script type="text/javascript" defer="defer" src="' . $dir_local . $v . '"></script>';
                }
            }
        }
    }
//// $vv['dihead'] .= '<link href="/assets/css/app.640f582b232504c57832.css" rel="stylesheet">';
//
//    if (is_dir(__DIR__ . DS . 'dist' . DS . 'assets' . DS . 'js' . DS)) {
//        $list_f = scandir(__DIR__ . DS . 'dist' . DS . 'assets' . DS . 'js' . DS);
//        foreach ($list_f as $v) {
//            if (strpos($v, '.js') !== false && (strpos($v, 'app.') !== false || strpos($v, 'vendors.') !== false)) {
//                // echo '<br/>#' . __LINE__ . ' ' . $v;
//                $vv['in_body_end'][] = '<script type="text/javascript" defer="defer" src="/vendor/didrive_mod/jobdesc/1/didrive/dist/assets/js/' . $v . '"></script>';
//            }
//        }
//    }
// $vv['in_body_end'][] = '<script type="text/javascript" src="/assets/js/vendors.f2e79c865ce2172cb7cb.js"></script>';
// $vv['in_body_end'][] = '<script type="text/javascript" src="/assets/js/app.caac25aa43296c4b8c6d.js"></script>';

    echo '</div>';
}
