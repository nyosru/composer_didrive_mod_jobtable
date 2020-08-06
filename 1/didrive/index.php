<?php

if (isset($_GET['newtype']{0})) {
    $_SESSION['newtype'] = $_GET['newtype'];
    \f\redirect('/', 'i.didrive.php');
}

$vv['in_body_end'][] = '<link rel="stylesheet" href="/vendor/didrive_mod/jobdesc/1/didrive/css.2007.css">';

// \f\pa($_SESSION);

if ( 1 == 2 && isset($_SESSION['newtype']) && $_SESSION['newtype'] == 2007 ) {
// if (isset($_SESSION['newtype']) && ( $_SESSION['newtype'] == 1 || $_SESSION['newtype'] == 2007 ) ) {
    
$vv['in_body_end'][] = '<div style="background-color: rgba(255,255,255,0.5); position: fixed; bottom: 100px; right: 50px; width: 350px;" >vue</div>';
$vv['in_body_end'][] = '<script src="https://cdn.jsdelivr.net/npm/vue@2.6.11"></script>';
$vv['in_body_end'][] = 
    '<script>
        
    import ShowBonus from \'/vendor/didrive_mod/jobdesc/1/didrive/vue/src/show-bonus\';

    var app = new Vue({ 
        el: \'#body2\'

	components: {
            ShowBonus,
        }

    });
    </script>';
    
    
}elseif (isset($_SESSION['newtype']) && ( $_SESSION['newtype'] == 1 ) ) {

    if (empty($vv['dihead']))
        $vv['dihead'] = '';

    echo '<div style="background-color: rgba(255,255,255,0.5); position: fixed; bottom: 100px; right: 50px; width: 350px;" >';

    $dirs_for_scan = [
        __DIR__ . DS . 'dist' . DS . 'assets' . DS . 'css' . DS => '/vendor/didrive_mod/jobdesc/1/didrive/dist/assets/css/',
        __DIR__ . DS . 'dist' . DS . 'assets' . DS . 'js' . DS => '/vendor/didrive_mod/jobdesc/1/didrive/dist/assets/js/',
        __DIR__ . DS . 'dist' . DS . 'css' . DS => '/vendor/didrive_mod/jobdesc/1/didrive/dist/css/',
        __DIR__ . DS . 'dist' . DS . 'js' . DS => '/vendor/didrive_mod/jobdesc/1/didrive/dist/js/',
        __DIR__ . DS . 'vue' . DS . 'dist' . DS . 'css' . DS => '/vendor/didrive_mod/jobdesc/1/didrive/vue/dist/css/',
        __DIR__ . DS . 'vue' . DS . 'dist' . DS . 'js' . DS => '/vendor/didrive_mod/jobdesc/1/didrive/vue/dist/js/',
    ];

    foreach ($dirs_for_scan as $d => $dir_local) {

        echo '<br/>#'.__LINE__.' '.__DIR__;
        echo $d;
        
        if (is_dir($d)) {

            $list_f = scandir($d);
            foreach ($list_f as $v) {

                if (!isset($v{5}))
                    continue;

                echo '<br/>#' . __LINE__ . ' ++1++ ' . $v;

                if (strpos($v, '.css') !== false && strpos($v, 'app.') !== false) {
                    echo '<br/>#' . __LINE__ . ' ' . $v;
                    $vv['dihead'] .= '<link href="' . $dir_local . $v . '" rel="stylesheet">';
                }

                if (strpos($v, '.js') !== false && ( strpos($v, 'app.') !== false || strpos($v, 'chunk') !== false )) {
                    echo '<br/>#' . __LINE__ . ' ' . $v;
                    $vv['in_body_end'][] = '<script type="text/javascript" defer="defer" src="' . $dir_local . $v . '"></script>';
                }
            }
        }
    }

    echo '</div>';
}







if( 1 == 2 ){
//\f\pa($_POST);
echo '<br/>';
echo '<br/>';
echo '<br/>';
echo '<br/>';
echo '<br/>';
echo '<div style="padding: 0px 150px ;" >';
////
////$e = \Nyos\mod\JobBuh::calcDayBudget($db, 1, '2020-07-02');
////
//
//$ee = \Nyos\mod\JobDesc::getListJobsPeriodAll($db, $_REQUEST['date'] ?? date('Y-m-d'));
//\f\pa($ee['data'],2);
////\f\pa(array_keys($ee['data']['where_job__workman_date']));
//\f\pa(array_keys($ee['data']['job_on_sp'][$_REQUEST['sp']]));
//// \f\pa(array_keys($ee['data']['where_job__workman_date']));
//

//$e = \Nyos\mod\JobDesc::calcDayBudget($db, $_REQUEST['sp'], ( $_REQUEST['date'] ?? date('Y-m-d') ) , [] );
//$e = \Nyos\mod\JobDesc::whoWhereCoocking($db, $_REQUEST['sp'], ( $_REQUEST['date'] ?? date('Y-m-d') ) );
//\f\pa($e);

echo '</div>';
}

//\f\pa($_SESSION);
// \f\Cash::allClear();
// $vv['tpl_body'] = '';

$vv['user_id'] = $_SESSION['now_user_di']['id'];
$vv['user_id_secret'] = \Nyos\Nyos::creatSecret($_SESSION['now_user_di']['id']);

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
