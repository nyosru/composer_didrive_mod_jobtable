<?php

//echo '<br/>';
//echo '<br/>';
//echo '<br/>';
//echo '<br/>';
//echo '<br/>';
//\f\pa($_SESSION);

$vv['tpl_body'] = \f\like_tpl('body', dir_site_module_nowlev_tpldidr, dir_mods_mod_vers_didrive_tpl, DR);

/**
 * быстрый поиск в списке
 */
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
        });
    
    </script>';

