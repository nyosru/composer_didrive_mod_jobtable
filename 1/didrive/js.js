$(document).ready(function () { // вся мaгия пoслe зaгрузки стрaницы









    function run_load__aj_get_minus_plus_coment() {

        var ert = [];
        ert = get_blocks_attr('div.load_job_man_1sp_smens');
        // console.log('результат 2', ert);
        $.ajax({

            url: "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
            data: "action=aj_get_minus_plus_coment&" + ert['string'],
            cache: false,
            dataType: "json",
            type: "post",
            beforeSend: function () {

                // $('div.graph_cell_1sp_man_day .smens').html('&nbsp;');
                // $('div.graph_cell_1sp_man_day .smens').addClass('ajax_loaded');

            },
            success: function ($j) {

                // console.log('123', $j);
                nn1 = [];
                $b = 0;
                $b1 = $('div.graph_cell_1sp_man_day div.in_bonus');
                $b1.empty().hide();

                $.each($j['bonus']['data'], function (n, ar) {

                    // console.log('1', ar);
                    $b = $('#sp_man_day_d' + ar['date_now'] + '_sp' + ar['sale_point'] + '_u' + ar['jobman'] + ' div.in_bonus');
                    //$b.hide();
//                        console.log('nn1', nn1);

                    // $b.html('x');
//                            $b.empty();
                    // nn1 = 2;
//                            console.log('nn1', nn1);

                    $hh = creat_html_bonus(ar);
                    $b.append($hh);

                    //$b.show('slow');

                });

                $b1.show('slow');



                $b1 = $('div.graph_cell_1sp_man_day div.in_minus');
                $b1.hide().empty();

                $.each($j['minus']['data'], function (n, ar) {

                    $hh = creat_html_minus(ar);
                    $b = $('#sp_man_day_d' + ar['date_now'] + '_sp' + ar['sale_point'] + '_u' + ar['jobman'] + ' div.in_minus');
                    //$b.hide();
                    // $b.html($hh);
                    $b.append($hh);
                    // $b.show('slow');

                });

                $b1.show('slow');

                $b1 = $('div.graph_cell_1sp_man_day div.in_comments');
                $b1.hide().empty();

                $.each($j['comment']['data'], function (n, ar) {

                    $hh = creat_html_comment(ar, $j['comment']['cfg']);
                    $b = $('#sp_man_day_d' + ar['date_to'] + '_sp' + ar['sale_point'] + '_u' + ar['jobman'] + ' div.in_comments');

//                        ret = '<div style="text-align:left;" ><small>';
//                        $.each(ar, function (k2, a2) {
//                            ret += '<br/>' + k2 + ': ' + a2;
//                        });
//                        ret += '</small></div>';
                    // $b.html(ret + $hh);

                    // $b.html($hh);
                    $b.append($hh);
                });

                $b1.show('slow');


//                add_new_dolg_in_graph($j);
//
//                $.each($j['checks'], function (date, value) {
//                    // console.log('1', date, value);
//                    $.each(value, function (k1, ar) {
//
//                        $hh = creat_html_1smena(ar);
//                        $('#sp_man_day_d' + date + '_sp' + ar['sp'] + '_u' + ar['jobman'] + ' div.smens').html($hh);
//
//                        // sp_man_day_d{{ date }}_sp{{sp_now}}_u{{ user_id }}
//                        // console.log('1', date, value1);
//
//                    });
//                });
//
//                $('div.graph_cell_1sp_man_day .smens').removeClass('ajax_loaded');

//                    $('div.oborot_data_td').html('-');
//                    ii = 1;
//                    // переберём массив arr
//                    $.each($j['res'], function (sp, value) {
//                        $.each(value, function (date, value2) {
//
//                            console.log(sp, date, value2);
//
//
//                            if (!!value2['oborot_hand']) {
//                                val_hand = value2['oborot_hand'];
//                            } else {
//                                val_hand = '';
//                            }
//
//                            input_oborot_hand = '<input type="number" max="1000000" min="0" '
//                                    + ' placeholder="уточнить"'
//                                    + ' title="укажите точную сумму оборота"'
//
//                                    + ' value="' + val_hand + '" '
//
//                                    + ' style="width:120px; padding: 3px; margin: 0 auto; text-align:center;" '
//                                    + ' class="form-control didrive__edit_items_dop_pole2" '
//
//                                    + ' edit_item_id="' + value2['id'] + '"'
//                                    + ' edit_dop_name="oborot_hand"'
//                                    + ' edit_s="' + value2['s_hand'] + '"' // {{ creatSecret(oborots[now_date2][\'id\']~"oborot_hand") }}"'
//
//                                    + ' pole_price_id="a_price_{{ sp_now }}_{{ now_date2 }}"'
//                                    + ' text_in_pole_price_id="<br/><center>оборот изменился, текущая автооценка удалена</center>"'
//
//                                    + ' delete_ocenka_sp="{{ sp_now }}"'
//                                    + ' delete_ocenka_date="{{ now_date2 }}"'
//                                    + ' delete_ocenka_s="{{ creatSecret(sp_now~now_date2) }}"'
//
//                                    + ' />';
//
//
//
//                            // if (!!value2['oborot_hand'] && value2['oborot_hand'] > 0) {
//                            if (!!value2['oborot_server']) {
//
//                                $('#data_obr_' + date + '__' + sp).html(
//                                        (value2['oborot_server'] ?
//                                                // ( value2['oborot_server'] || '-' ) + '<sup><abbr title="Авто значение с сервера">A</abbr></sup>'
//                                                number_format(value2['oborot_server'], 0, '.', '`') + ' <sup><abbr title="Авто значение с сервера">A</abbr></sup>'
//                                                : 'x')
//                                        + '<br/>'
//
//                                        + input_oborot_hand
//
////                                        + '<input type="number" min="0" max="900000" step="0.01" '
////                                        + ' class="number_oborot_tochnee" '
////                                        + ' value="' + val_hand + '" '
////                                        + ' />'
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//                                        );
//                            } else {
//
//                                $('#data_obr_' + date + '__' + sp).html(
//                                        '<sup><a href="/vendor/didrive_mod/iiko_oborot/1/didrive/ajax.php" '
//                                        + ' vars="date=' + date + '&hide_form=da&action=get_oborot_for_sps&get_sp_load=' + sp + '" '
//                                        + ' res_to="' + sp + '_' + ii + '_res_ob2"'
//                                        + ' xtarget="_blank"'
//                                        + ' class="load_ajaxjson_to_id"'
//                                        + ' >загрузить с ИИКО</a></sup>'
//
//                                        + ' <div id="' + sp + '_' + ii + '_res_ob2" ></div>'
//
////                                        + (value2['oborot_server'] ?
////                                                // ( value2['oborot_server'] || '-' ) + '<sup><abbr title="Авто значение с сервера">A</abbr></sup>'
////                                                number_format(value2['oborot_server'] / 1000, 1, '.', '`') + ' <sup><abbr title="Авто значение с сервера">A</abbr></sup>'
////                                                : 'x')
////
////                                        + '<br/>'
//
//                                        // + (value2['oborot_hand'] ?? '-1')
//
////                                    + '<br/>'
////                                    + (value2['delivery'] || '-')
//                                        );
//                                ii++;
//                            }
//
//
//                        }
//                        );
//                    });
            }

        });
    }




// console.log('123')

// window.nyos = [ 'dolgn' , 123 ];

//    $.cache[98]['wer'] = 123;
//    alert($.cache[98]['wer']);



    function ocenka_clear($sp, $date, $clear_to_now = '') {


// если не пусто то трём все даты начиная с указанной
        if ($clear_to_now != '') {

            $('#a_price_' + $sp + '_' + $date).html('<div class=\'bg-warning\' style=\'padding:5px;\' >Значение изменено</div>');
            // console.log('стираем все даты, начиная с указанной', $sp, $date);
        }
// если пусто то трём дату указанную
        else {

            $('#a_price_' + $sp + '_' + $date).html('<div class=\'bg-warning\' style=\'padding:5px;\' >Значение изменено</div>');
            // console.log('стираем 1 дату', $sp, $date);
        }


        $.ajax({

            url: "/vendor/didrive_mod/jobdesc/1/ajax.php",
            data: "action=ocenka_clear&sp=" + $sp + "&date=" + $date + "&clear_to_now=" + $clear_to_now,
            cache: false,
            dataType: "json",
            type: "post",
            async: false,
//            beforeSend: function () {
//
//                $('span#' + $textblock_id).css('border-bottom', '2px solid orange');
//                $('span#' + $textblock_id).css('font-weight', 'bold');
//                //if (typeof $div_hide !== 'undefined') {
//                //$('#' + $div_hide).hide();
//                //}
//
//                // $("#ok_but_stat").html('<img src="/img/load.gif" alt="" border=0 />');
//                //                $("#ok_but_stat").show('slow');
//                //                $("#ok_but").hide();
//
//                ocenka_clear($in_sp, $in_date);
//
//            }
//            ,
            success: function ($j) {

                console.log('стираем оценку дня', $j);
//
//                // alert($j.status);
//
//                if ($j.status == 'error') {
//
//                    // $('span#' + $textblock_id).css('border-bottom', '2px solid red');
//                    // $('span#' + $textblock_id).css('color', 'darkred');
//
//                } else {
//
//                    $('span#' + $textblock_id).css('border-bottom', '2px solid green');
//                    // $('span#' + $textblock_id).css('color', 'darkgreen');
//
//                    // console.log($new_val);
//                    // console.log( 1, $('span#' + $textblock_id).closest('.www').find('.now_price_hour').attr('kolvo_hour'));
//                    // $('span#' + $textblock_id).closest('.smena1').find('.hours_kolvo').val($new_val);
//                    // console.log( 2, $('span#' + $textblock_id).closest('.www').find('.now_price_hour').attr('kolvo_hour'));
//
//
//                    // $.debounce( 1000, calcSummMoneySmena2 );
//                    // calcSummMoneySmena2($textblock_id);
//
////                    setTimeout( function () {
////                        //calculateSummAllGraph();
////
////                        console.log('$textblock_id', $textblock_id);
////                        // alert($textblock_id);
////
////                        calcSummMoneySmena($textblock_id);
////
////                    }, 100);
////                    //$(document).one( calculateSummAllGraph );
//
//                }


            }

        });
    }

    /**
     * обработка назначения сотрудника на точку продаж
     * @param {type} $sp
     * @param {type} $workman
     * @param {type} $dolgnost
     * @param {type} $date_start
     * @returns {undefined}
     */
    function put_workman_on_sp($th) {

// console.log('function put_workman_on_sp( ' + $sp + ', ' + $workman + ', ' + $dolgnost + ', ' + $date_start + ' )');
        var data = $($th).serialize();
//        console.log($th);
//        console.log('111 ',data);

        dolgn_from = $('#add_person1day__user option:selected').attr('dolgn');
        sp_from = $('#add_person1day__user option:selected').attr('sp');

        $.ajax({

            xurl: "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
            url: "/vendor/didrive_mod/jobdesc/1/didrive/micro-service/add-new-smena.php",
            xdata: "action=put_workman_on_sp&" + data + '&sp_from=' + sp_from + '&dolgnost_from=' + dolgn_from,
            data: data + '&sp_from=' + sp_from + '&dolgnost_from=' + dolgn_from,
            cache: false,
            dataType: "json",
            type: "post",
            beforeSend: function () {

                /*
                 if (typeof $div_hide !== 'undefined') {
                 $('#' + $div_hide).hide();
                 }
                 */
                // $("#ok_but_stat").html('<img src="/img/load.gif" alt="" border=0 />');
//                $("#ok_but_stat").show('slow');
//                $("#ok_but").hide();

            }
            ,
            success: function ($j) {

                if ($j['status'] == 'ok') {
                    // alert('назначение проведено, обновляю страницу, пару секунд пожалуйста');
                    $('.put_workman_on_sp').html('<div style="padding:20px;" >обновляю страницу, секунду</div>');
                    //$('.put_workman_on_sp').hide('slow');
                    location.reload();
                } else {
                    alert('произошла неописуемая ситуация #109');
                }

                //$($vars['resto']).append($j.data);

                // $($res_to).html($j.data);
                // $($vars['resto']).html($j.data);
                // $th("#main").prepend("<div id='box1'>1 блок</div>");                    
                // $th("#main").prepend("<div id='box1'>1 блок</div>");                    
                // $th.html( $j.html + '<br/><A href="">Сделать ещё заявку</a>');
                // $($res_to_id).html( $j.html + '<br/><A href="">Сделать ещё заявку</a>');

                // return true;

                /*
                 // alert($j.html);
                 if (typeof $div_show !== 'undefined') {
                 $('#' + $div_show).show();
                 }
                 */
//                $('#form_ok').hide();
//                $('#form_ok').html($j.html + '<br/><A href="">Сделать ещё заявку</a>');
//                $('#form_ok').show('slow');
//                $('#form_new').hide();
//
//                $('.list_mag').hide();
//                $('.list_mag_ok').show('slow');

            }

        });
    }

    function delete_workman_from_sp($sp, $workman, $wm_s, $res_to) {

        // console.log('delete_workman_from_sp( ' + $sp + ', ' + $workman + ', ' + $wm_s + ' )');
        // var data = $($th).serialize();
        // console.log('111 '+data);

        $.ajax({

            url: "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
            data: "action=delete_workman_from_sp&sp=" + $sp + "&workman=" + $workman + "&id=" + $workman + "&s=" + $wm_s,
            cache: false,
            dataType: "json",
            type: "post",
            beforeSend: function () {

                /*
                 if (typeof $div_hide !== 'undefined') {
                 $('#' + $div_hide).hide();
                 }
                 */
                // $("#ok_but_stat").html('<img src="/img/load.gif" alt="" border=0 />');
//                $("#ok_but_stat").show('slow');
//                $("#ok_but").hide();

            }
            ,
            success: function ($j) {

                if ($j['status'] == 'ok') {
                    alert('окей, удалили назначение');
                    $('#user_tr_' + $sp + '_' + $workman).hide('slow');
                }
                //
                else {
                    alert($j['html']);
                    //alert('2');
                }

            }

        });
    }

    /**
     * определяем конец рабочего периода
     * @param {type} $sp
     * @param {type} $work_id
     * @param {type} $wm_s
     * @param {type} $date_end
     * @returns {undefined}
     */
    function set_end_now_jobs($work_id, $wm_s, $date_end) {

        // console.log('set_end_now_jobs( ' + $work_id + ', ' + $wm_s + ', ' + $date_end + ' )');
        // return false;

        // var data = $($th).serialize();
        // console.log('111 '+data);

        $.ajax({

            url: "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
            data: "action=set_end_now_jobs&id=" + $work_id + "&s=" + $wm_s + "&work_id=" + $work_id + "&wm_s=" + $wm_s + "&date_end=" + $date_end,
            cache: false,
            dataType: "json",
            type: "post",
            beforeSend: function () {

                $("body").append("<div id='body_block' class='body_block' >пару секунд вычисляем<br/><span id='body_block_465'></span></div>");
                /*
                 if (typeof $div_hide !== 'undefined') {
                 $('#' + $div_hide).hide();
                 }
                 */
                // $("#ok_but_stat").html('<img src="/img/load.gif" alt="" border=0 />');
//                $("#ok_but_stat").show('slow');
//                $("#ok_but").hide();

            }
            ,
            success: function ($j) {

                if ($j['status'] == 'ok') {
                    // alert('окей, рабочий период закрыт, со следующего дня сотрудник уволен, после "ок" перезагрузка страницы, пару секунд пожалуйста');
                    location.reload();
                    // $('#user_tr_' + $sp + '_' + $workman ).hide('slow');
                }
                //
                else {
                    alert($j['html']);
                    //alert('2');
                }

            }

        });
    }

    /**
     * отмена конца смены (если поставили по ошибке)
     * @param {type} $work_id
     * @param {type} $wm_s
     * @param {type} $date_end
     * @returns {undefined}
     */
    function cancel_end_now_jobs($work_id, $wm_s, $date_end) {

        // console.log('cancel_end_now_jobs( ' + $work_id + ', ' + $wm_s + ', ' + $date_end + ' )');
        // return false;

        // var data = $($th).serialize();
        // console.log('111 '+data);

        $.ajax({

            url: "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
            data: "action=cancel_end_now_jobs&id=" + $work_id + "&s=" + $wm_s + "&work_id=" + $work_id + "&wm_s=" + $wm_s + "&date_end=" + $date_end,
            cache: false,
            dataType: "json",
            type: "post",
            beforeSend: function () {

                /*
                 if (typeof $div_hide !== 'undefined') {
                 $('#' + $div_hide).hide();
                 }
                 */
                // $("#ok_but_stat").html('<img src="/img/load.gif" alt="" border=0 />');
//                $("#ok_but_stat").show('slow');
//                $("#ok_but").hide();

            }
            ,
            success: function ($j) {

                if ($j['status'] == 'ok') {
                    // alert('окей, отменено, после "ок" перезагрузка страницы, пару секунд пожалуйста');
                    location.reload();
                    $("body").append("<div id=\'body_block\' class=\'body_block\' >пару секунд вычисляем<br/><span id=\'body_block_465\'></span></div>");
                    // $('#user_tr_' + $sp + '_' + $workman ).hide('slow');
                }
                //
                else {
                    alert($j['html']);
                    //alert('2');
                }

            }

        });
    }

// перебор div
//function hidePosts(){ 
//  var hideText = "текст";
//  var posts = document.querySelectorAll("._post.post");
//  for (var i = 0; i<posts.length; i++) {
//    var post = posts[i].querySelector(".wall_post_text");
//    if (post.innerText.indexOf(hideText) != -1 )
//    {
//      posts[i].style.display = "none";
//    }
//  }
//}

// alert('123');


    function calculate_summ_day($sp, $date) {

        $('.price_hour_' + $date + '_' + $sp).each(function (i, elem) {

//            if ($(this).hasClass("stop")) {
//                alert("Остановлено на " + i + "-м пункте списка.");
//                return false;
//            } else {
            // console.log(i + ': ' + $(elem).text() + ': ' + $(elem).value());
//            }

        });
    }

// onload="calculate_summ_day( {{ sp_now }}, {{ date }} );" 

    /*
     * считаем все суммы всех точек
     * @returns {undefined}
     */
    function calculateSummAllGraph() {

        $('body .show_summ_hour_day').each(function (i, elem) {

            var $date = $(elem).attr('data');
            var $sp = $(elem).attr('sp');
            //console.log('блок для расчёта дня ', $date, $sp);

            //$('body .price_hour_' + $date + '_' + $sp).each(function (i2, elem2) {

            //console.log('body .price_hour_' + $date + '_' + $sp);

            var $summa = 0;
            var $summa_hours = 0;
            var $error = '';
//            $('body .price_hour_' + $date + '_' + $sp).each(function (i2, elem2) {
//
//                var $e1 = $(elem2).text();
//                var $e2 = $(elem2).val();
//
//                //$kolvo_hour = Number($(elem2).attr('kolvo_hour'));
//                $kolvo_hour = Number($(elem2).closest('.smena1').find('.hours_kolvo').val());
//                //console.log('второго уровня блок ', i2, $e1, $e2, $kolvo_hour);
//
//                $summa += $e2 * $kolvo_hour;
//                $summa_hours += $kolvo_hour;
//
//            });

            // console.log('summa_m ', $summa);
            // console.log('summa_h ', $summa_hours);

            $price = 0;
            $('body .price_hour_' + $date + '_' + $sp + '_select').each(function (i3, elem3) {

                $th = $(elem3).find('option:selected');
                //var $e1 = $(elem2).text();

                $price = Number($th.attr('price'));
                $error = 'Не все оценки выставлены';
                // $kolvo_hour = Number($th.attr('kolvo_hour'));
                $kolvo_hour = Number($(elem3).closest('.smena1').find('.hours_kolvo').prop('value'));
                //console.log('select ', $kolvo_hour);
                //console.log('второго уровня 2 блок ', i3, $price, $kolvo_hour);
                $summa_hours += $kolvo_hour;
                //console.log('$summa_hours', $summa_hours);

                $summa += $price * $kolvo_hour;
                //console.log('summa ', $summa);

            });
            if ($price == 0) {
                $error = $summa_hours + ' ч.'
            }

            if ($error == '') {
                $(elem).html('<nobr>' + number_format($summa_hours, 1, '.', '`') + ' ч<br/>' + number_format($summa, 0, '.', '`') + ' р</nobr>');
            } else {
                $(elem).html($error);
            }

        });
    }

//    setTimeout(function () {
//        alert(123);
//        calculateSummAllGraph();
//    }, 3000);

    /* затираем данные в строчках с результатом работы */
    function clearTdSummAllGraph() {
        $('body .show_summ_hour_day').each(function (i, elem) {
            $(elem).html('...');
        });
    }




// calculateSummAllGraph();

    /**
     * вычисляем сумму денег за день 1911
     * @param {type} id
     * если указали то считаем только 1 смену (чекин чекаут)
     * @returns {undefined}
     */
    function calcSummMoneySmena(id = null) {
        $.debounce(1000, calcSummMoneySmena2);
    }

    function calcSummMoneySmena2(id = null) {

//alert( id );
//console.log('calcSummMoneySmena', id);

        $('body .smena_summa').html('..');
        $('body .job_hours').each(function (i, elem) {

            $id = $(elem).attr('id_smena');
            // console.log($id);

            $hours0 = $(elem).html();
            //console.log($hours);
            $hours = $hours0 * 1;
            // console.log($hours2);

            if (1 == 1 || id == $id || id == null) {

                if ($('.smena_price_' + $id).length && $hours > 0 && $id > 0) {

                    $price = $('.smena_price_' + $id + ' option:selected').attr('price');
                    // console.log('цена - '+$price);
                    // console.log($id + ' / ' + $hours + ' / ' + $price);
                    $sum = $hours * $price;
                    if ($sum > 0) {
                        $('body .smena_summa_' + $id).html($sum + 'р');
                    } else {
                        $('body .smena_summa_' + $id).html('');
                    }

                    if ($('.smena_oplacheno_' + $id).length) {

                        $summa_oplat = $('.smena_oplacheno_' + $id).attr('summ');
                        // если выплачено и начислено сходится, убираем начислено
                        if ($sum == $summa_oplat) {
// console.log($sum + ' бб ' + $summa_oplat);
                            $('body .smena_summa_' + $id).hide();
                        }
                    }

                }
//
                else {
                    // console.log('пропускаем');
                }
            }

        });
    }



// кликаем по кнопам плюс минус час

// $('body').on('click', '.ajax_hour_action', $.debounce(300, jobdesc__plus_minus_hour) );


    $('body').on('change', '.select_edit_item_dop2', function () {

        // console.log(2);
        setTimeout(function () {
            calculateSummAllGraph();
        }, 100);
        // console.log(3);
    });

    /* если изменили стоимость часа у человека, затираем данные и высчитываем суммы */
    $('body').on('change', 'select.select_edit_item_dop', function () {

        clearTdSummAllGraph();
        // alert('123');
        setTimeout(function () {
            calculateSummAllGraph();
        }, 2000);
    })


    $('body').on('click', '.show_job_tab2', function (event) {

        $.each(this.attributes, function () {

            if (this.specified) {

                if (this.name == 'show_on_click') {
                    $('#' + this.value).toggle('slow');
                }

            }

        });
    });
    $('body').on('click', '.show_job_tab', function (event) {

// alert('2323');
        $(this).removeClass("show_job_tab");
        $(this).addClass("show_job_tab2");
        var $uri_query = '';
        var $vars = [];
        $.each(this.attributes, function () {

            if (this.specified) {
                // console.log(this.name, this.value);
                $uri_query = $uri_query + '&' + this.name + '=' + this.value.replace(' ', '..')

                if (this.name == 'res_to') {
                    $vars['resto'] = '#' + this.value + ' tbody';
                    // console.log($vars['resto']);
                    // alert($res_to);
                }

                if (this.name == 'show_on_click') {
                    $('#' + this.value).show('slow');
                }

            }

        });
        // console.log($vars['resto']);
        // console.log($uri_query);
        //$(this).html("тут список");
        var $th = $(this);
        $.ajax({

            xurl: "/sites/yadom_admin/module/000.index/ajax.php",
            url: "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
            data: "action=show_info_strings" + $uri_query,
            cache: false,
            dataType: "json",
            type: "post",
            beforeSend: function () {
                /*
                 if (typeof $div_hide !== 'undefined') {
                 $('#' + $div_hide).hide();
                 }
                 */
                // $("#ok_but_stat").html('<img src="/img/load.gif" alt="" border=0 />');
//                $("#ok_but_stat").show('slow');
//                $("#ok_but").hide();
            }
            ,
            success: function ($j) {

                // $($res_to).html($j.data);
                // $($vars['resto']).html($j.data);
                $($vars['resto']).append($j.data);
                // $th("#main").prepend("<div id='box1'>1 блок</div>");                    
                // $th("#main").prepend("<div id='box1'>1 блок</div>");                    
                // $th.html( $j.html + '<br/><A href="">Сделать ещё заявку</a>');
                // $($res_to_id).html( $j.html + '<br/><A href="">Сделать ещё заявку</a>');

                // return true;

                /*
                 // alert($j.html);
                 if (typeof $div_show !== 'undefined') {
                 $('#' + $div_show).show();
                 }
                 */
//                $('#form_ok').hide();
//                $('#form_ok').html($j.html + '<br/><A href="">Сделать ещё заявку</a>');
//                $('#form_ok').show('slow');
//                $('#form_new').hide();
//
//                $('.list_mag').hide();
//                $('.list_mag_ok').show('slow');

            }

        });
        //return false;

    });
    // else {
    // alert(i + ': ' + $(elem).text());
    // }

    /**
     * назначение сотрудника на точку продаж
     */
    $('body').on('submit', '.put_workman_on_sp', function (event) {

        event.preventDefault();
        // put_workman_on_sp($sp, $workman, $dolgnost, $date_start);
        put_workman_on_sp(this);
        return false;
    });
    // else {
    // alert(i + ': ' + $(elem).text());
    // }

    /**
     * удаление сотрудника с точки продаж (старая версия)
     */
    $('body').on('click', '.delete_workman_from_sp', function (event) {

// event.preventDefault();
// put_workman_on_sp($sp, $workman, $dolgnost, $date_start);
// put_workman_on_sp( this );
        // console.log('delete_workman_from_sp');
        $answer = '';
        $wm_s = '';
        $.each(this.attributes, function () {
            //console.log(this.name, this.value);

            if (this.name == 'sp') {
                $sp = this.value;
            } else if (this.name == 'work_id') {
                $work_id = this.value;
            } else if (this.name == 'wm_s') {
                $wm_s = this.value;
            } else if (this.name == 'date_end') {
                $date_end = this.value;
            } else if (this.name == 'answer') {
                $answer = this.value;
            }
        });
        if ($answer != '') {
            if (!confirm($answer)) {
                return false;
            }
        }

        $res = delete_workman_from_sp($sp, $work_id, $wm_s, $date_end);
    });
    // else {
    // alert(i + ': ' + $(elem).text());
    // }


    /**
     * кликнули (уволен с завтрашнего дня)
     * обозначаем конец текущего периода работы
     */
    $('body').on('click', '.set_end_now_jobs', function (event) {

// event.preventDefault();
// put_workman_on_sp($sp, $workman, $dolgnost, $date_start);
// put_workman_on_sp( this );
        // console.log('set_end_now_jobs');
        $need_answer = '';
        $wm_s = '';
        $date_end = '';
// set_end_now_jobs( $now_job_id, $s, $res_to ) {

        $.each(this.attributes, function () {
            // console.log(this.name, this.value);
            if (this.name == 'work_id') {
                $work_id = this.value;
            } else if (this.name == 'sp') {
                $sp = this.value;
            } else if (this.name == 'wm_s') {
                $wm_s = this.value;
            } else if (this.name == 'date_finish') {
                $date_end = this.value;
            } else if (this.name == 'res_to') {
                $res_to = this.value;
            } else if (this.name == 'need_answer') {
                $need_answer = this.value;
            }

        });
        if ($need_answer != '') {
            if (!confirm($need_answer)) {
                return false;
            }
        }

        $res = set_end_now_jobs($work_id, $wm_s, $date_end);
        return false;
    });
    // else {
    // alert(i + ': ' + $(elem).text());
    // }

    /**
     * кликнули (уволен с завтрашнего дня)
     * обозначаем конец текущего периода работы
     */
    $('body').on('click', '.cancel_end_now_jobs', function (event) {

// event.preventDefault();
// put_workman_on_sp($sp, $workman, $dolgnost, $date_start);
// put_workman_on_sp( this );
        // console.log('cancel_end_now_jobs');
        $need_answer = '';
        $wm_s = '';
        $date_end = '';
// set_end_now_jobs( $now_job_id, $s, $res_to ) {

        $.each(this.attributes, function () {
            // console.log(this.name, this.value);
            if (this.name == 'work_id') {
                $work_id = this.value;
            } else if (this.name == 'sp') {
                $sp = this.value;
            } else if (this.name == 'wm_s') {
                $wm_s = this.value;
            } else if (this.name == 'date_finish') {
                $date_end = this.value;
            } else if (this.name == 'res_to') {
                $res_to = this.value;
            } else if (this.name == 'need_answer') {
                $need_answer = this.value;
            }

        });
        if ($need_answer != '') {
            if (!confirm($need_answer)) {
                return false;
            }
        }

        $res = cancel_end_now_jobs($work_id, $wm_s, $date_end);
        return false;
    });
    // else {
    // alert(i + ': ' + $(elem).text());
    // }


    //$('body').on('click', '.act_smena', function (event) {
    $(document).on('click', '.act_smena', function (event) {

// alert('2323');
//        $(this).removeClass("show_job_tab");
//        $(this).addClass("show_job_tab2");
//        var $uri_query = '';
//        var $vars = [];
// var $vars = serialize(this.attributes);
// var $vars =  JSON.stringify(this.attributes);
        var resto = '';
        var $vars = new Array();
        var $uri_query = '';
        var hidethis = 0;
        var showid = 0;
        var answer = 0;
        $.each(this.attributes, function () {

            if (this.specified) {

                // console.log(this.name, this.value);
                // $uri_query = $uri_query + '&' + this.name + '=' + this.value.replace(' ', '..')
                $uri_query = $uri_query + '&' + this.name + '=' + this.value;
//
                if (this.name == 'hidethis' && this.value == 'da') {
                    hidethis = 1;
                }
                if (this.name == 'show_id') {
                    showid = '#' + this.value;
                }
                if (this.name == 'go_answer') {
                    answer = this.value;
                }
                if (this.name == 'resto') {
                    resto = '#' + this.value;
                    //console.log($vars['resto']);
                    // alert($res_to);
                }
//
//                if (this.name == 'show_on_click') {
//                    $('#' + this.value).show('slow');
//                }

            }

        });
        if (answer != 0) {

            if (!confirm(answer)) {
                return false;
            }

        }

//        alert($uri_query);
//        return false;

// console.log($vars['resto']);

// console.log($uri_query);
//$(this).html("тут список");
        var $th = $(this);
        $.ajax({

            xurl: "/sites/yadom_admin/module/000.index/ajax.php",
            url: "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
            data: "t=1" + $uri_query,
            cache: false,
            dataType: "json",
            type: "post",
            beforeSend: function () {
                /*
                 if (typeof $div_hide !== 'undefined') {
                 $('#' + $div_hide).hide();
                 }
                 */
                // $("#ok_but_stat").html('<img src="/img/load.gif" alt="" border=0 />');
//                $("#ok_but_stat").show('slow');
//                $("#ok_but").hide();
            }
            ,
            success: function ($j) {

                //alert(resto);

                // $($res_to).html($j.data);
                // $($vars['resto']).html($j.data);
                $(resto).html($j.html);
                if (showid != 0) {
                    $(showid).show('slow');
                }

                if (hidethis == 1) {
                    $th.hide();
                }

                // $th("#main").prepend("<div id='box1'>1 блок</div>");                    
                // $th("#main").prepend("<div id='box1'>1 блок</div>");                    
                // $th.html( $j.html + '<br/><A href="">Сделать ещё заявку</a>');
                // $($res_to_id).html( $j.html + '<br/><A href="">Сделать ещё заявку</a>');

                // return true;

                /*
                 // alert($j.html);
                 if (typeof $div_show !== 'undefined') {
                 $('#' + $div_show).show();
                 }
                 */
//                $('#form_ok').hide();
//                $('#form_ok').html($j.html + '<br/><A href="">Сделать ещё заявку</a>');
//                $('#form_ok').show('slow');
//                $('#form_new').hide();
//
//                $('.list_mag').hide();
//                $('.list_mag_ok').show('slow');

            }

        });
        return false;
    });
    // else {
    // alert(i + ': ' + $(elem).text());
    // }


    $('body').on('click', '.send_ajax_values', function (event) {

// alert('2323');
//        $(this).removeClass("show_job_tab");
//        $(this).addClass("show_job_tab2");
//        var $uri_query = '';
//        var $vars = [];
// var $vars = serialize(this.attributes);
// var $vars =  JSON.stringify(this.attributes);
        var resto = '';
        var $vars = new Array();
        var $uri_query = '';
        var hidethis = 0;
        var showid = 0;
        var answer = 0;
        $.each(this.attributes, function () {

            if (this.specified) {

                // console.log(this.name, this.value);
                // $uri_query = $uri_query + '&' + this.name + '=' + this.value.replace(' ', '..')
                $uri_query = $uri_query + '&' + this.name + '=' + this.value;
//
                if (this.name == 'hidethis' && this.value == 'da') {
                    hidethis = 1;
                }
                if (this.name == 'show_id') {
                    showid = '#' + this.value;
                }
                if (this.name == 'go_answer') {
                    answer = this.value;
                }
                if (this.name == 'resto') {
                    resto = '#' + this.value;
                    //console.log($vars['resto']);
                    // alert($res_to);
                }
//
//                if (this.name == 'show_on_click') {
//                    $('#' + this.value).show('slow');
//                }

            }

        });
        if (answer != 0) {

            if (!confirm(answer)) {
                return false;
            }

        }

//        alert($uri_query);
//        return false;

// console.log($vars['resto']);

// console.log($uri_query);
//$(this).html("тут список");
        var $th = $(this);
        $.ajax({

            xurl: "/sites/yadom_admin/module/000.index/ajax.php",
            url: "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
            data: "t=1" + $uri_query,
            cache: false,
            dataType: "json",
            type: "post",
            beforeSend: function () {
                /*
                 if (typeof $div_hide !== 'undefined') {
                 $('#' + $div_hide).hide();
                 }
                 */
                // $("#ok_but_stat").html('<img src="/img/load.gif" alt="" border=0 />');
//                $("#ok_but_stat").show('slow');
//                $("#ok_but").hide();
            }
            ,
            success: function ($j) {

                //alert(resto);

                // $($res_to).html($j.data);
                // $($vars['resto']).html($j.data);
                $(resto).html($j.html);
                if (showid != 0) {
                    $(showid).show('slow');
                }

                if (hidethis == 1) {
                    $th.hide();
                }

                // $th("#main").prepend("<div id='box1'>1 блок</div>");                    
                // $th("#main").prepend("<div id='box1'>1 блок</div>");                    
                // $th.html( $j.html + '<br/><A href="">Сделать ещё заявку</a>');
                // $($res_to_id).html( $j.html + '<br/><A href="">Сделать ещё заявку</a>');

                // return true;

                /*
                 // alert($j.html);
                 if (typeof $div_show !== 'undefined') {
                 $('#' + $div_show).show();
                 }
                 */
//                $('#form_ok').hide();
//                $('#form_ok').html($j.html + '<br/><A href="">Сделать ещё заявку</a>');
//                $('#form_ok').show('slow');
//                $('#form_new').hide();
//
//                $('.list_mag').hide();
//                $('.list_mag_ok').show('slow');

            }

        });
        return false;
    });
    // else {
    // alert(i + ': ' + $(elem).text());
    // }


    $('body').on('submit', '#goto_other_sp', function (event) {

        event.preventDefault();
        // создание массива объектов из данных формы
        var data1 = $(this).serializeArray();
        // переберём каждое значение массива и выведем его в формате имяЭлемента=значение в консоль
        // console.log('Входящие данные');
        $.each(data1, function () {

            // console.log(this.name + '=' + this.value);
            if (this.name == 'print_res_to_id') {
                $print_res_to = $('#' + this.value);
            }

            if (this.name == 'data-target2') {
                $modal_id = this.value;
            }

        });
        // alert('123');
        // return false;

        $.ajax({

            type: 'POST',
            xurl: "/sites/yadom_admin/module/000.index/ajax.php",
            url: "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
            dataType: 'json',
            data: data1,
            // сoбытиe дo oтпрaвки
            beforeSend: function ($data) {
                // $div_res.html('<img src="/img/load.gif" alt="" border="" />');
                // $this.css({"border": "2px solid orange"});
            },
            // сoбытиe пoслe удaчнoгo oбрaщeния к сeрвeру и пoлучeния oтвeтa
            success: function ($data) {

                //alert('123');

                // eсли oбрaбoтчик вeрнул oшибку
                if ($data['status'] == 'error')
                {
                    // alert($data['error']); // пoкaжeм eё тeкст
                    // $div_res.html('<div class="warn warn">' + $data['html'] + '</div>');
                    // $this.css({"border": "2px solid red"});

                    $($print_res_to).append('<div>произошла ошибка: ' + $data['html'] + '</div>');
                }
                // eсли всe прoшлo oк
                else
                {
                    // $div_res.html('<div class="warn good">' + $data['html'] + '</div>');
                    // $this.css({"border": "2px solid green"});

                    $($print_res_to).append($data['html']);
                }

                //$($modal_id).modal('hide');
                $('.modal').modal('hide');
            }
            ,
            // в случae нeудaчнoгo зaвeршeния зaпрoсa к сeрвeру
            error: function (xhr, ajaxOptions, thrownError) {
                // пoкaжeм oтвeт сeрвeрa
                alert(xhr.status + ' ' + thrownError); // и тeкст oшибки
            }

            // сoбытиe пoслe любoгo исхoдa
            // ,complete: function ($data) {
            // в любoм случae включим кнoпку oбрaтнo
            // $form.find('input[type="submit"]').prop('disabled', false);
            // }

        }); // ajax-


        return false;
    });

    /* версия 2007 */
    $('body').on('click', '.put_var_in_modal3', function (event) {
        $.each(this.attributes, function () {
            if (this.specified) {
                if (this.name == 'data-target2') {
                    var $id_modal = this.value;
                    $(this.value).modal('toggle');
                } else {
                    if ($("input").is("." + this.name)) {
                        $("input." + this.name).val(this.value);
                    }
                }
            }
        });
    });


    $('body').on('click', '.put_var_in_modal2', function (event) {

        $.each(this.attributes, function () {

            if (this.specified) {

                // console.log(this.name, this.value);
                // $uri_query = $uri_query + '&' + this.name + '=' + this.value.replace(' ', '..')

                if (this.name == 'data-target2') {
                    var $id_modal = this.value;
                    // console.log(this.value);
                    $(this.value).modal('toggle');
                    // $id_modal.modal('toggle');
                } else {
                    // console.log(2, this.value);
                    if ($("input").is("#" + this.name)) {
                        $("input#" + this.name).val(this.value);
                    }
                }
            }
        });
        return false;
        if ($(this).prop('data-target2').length()) {
            // console.log($(this).prop('data-target2'));
        }

        $.each(this.attributes, function () {

            if (this.specified) {

                // console.log(this.name, this.value);
//                $uri_query = $uri_query + '&' + this.name + '=' + this.value.replace(' ', '..')
//
//                if (this.name == 'res_to') {
//                    $vars['resto'] = '#' + this.value + ' tbody';
//                    console.log($vars['resto']);
//                    // alert($res_to);
//                }
//
//                if (this.name == 'show_on_click') {
//                    $('#' + this.value).show('slow');
//                }

            }

        });
        return false;
    });

    $('body').on('click', '.22put_var_in_modal', function (event) {

// alert('2323');
        $(this).removeClass("show_job_tab");
        $(this).addClass("show_job_tab2");
        var $uri_query = '';
        var $vars = [];
        $.each(this.attributes, function () {

            if (this.specified) {
                // console.log(this.name, this.value);
                $uri_query = $uri_query + '&' + this.name + '=' + this.value.replace(' ', '..')

                if (this.name == 'res_to') {
                    $vars['resto'] = '#' + this.value + ' tbody';
                    // console.log($vars['resto']);
                    // alert($res_to);
                }

                if (this.name == 'show_on_click') {
                    $('#' + this.value).show('slow');
                }

            }

        });
        // console.log($vars['resto']);
        // console.log($uri_query);
        //$(this).html("тут список");
        var $th = $(this);
        $.ajax({

            xurl: "/sites/yadom_admin/module/000.index/ajax.php",
            url: "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
            data: "action=show_info_strings" + $uri_query,
            cache: false,
            dataType: "json",
            type: "post",
            beforeSend: function () {
                /*
                 if (typeof $div_hide !== 'undefined') {
                 $('#' + $div_hide).hide();
                 }
                 */
                // $("#ok_but_stat").html('<img src="/img/load.gif" alt="" border=0 />');
//                $("#ok_but_stat").show('slow');
//                $("#ok_but").hide();
            }
            ,
            success: function ($j) {

                // $($res_to).html($j.data);
                // $($vars['resto']).html($j.data);
                $($vars['resto']).append($j.data);
                // $th("#main").prepend("<div id='box1'>1 блок</div>");                    
                // $th("#main").prepend("<div id='box1'>1 блок</div>");                    
                // $th.html( $j.html + '<br/><A href="">Сделать ещё заявку</a>');
                // $($res_to_id).html( $j.html + '<br/><A href="">Сделать ещё заявку</a>');

                // return true;

                /*
                 // alert($j.html);
                 if (typeof $div_show !== 'undefined') {
                 $('#' + $div_show).show();
                 }
                 */
//                $('#form_ok').hide();
//                $('#form_ok').html($j.html + '<br/><A href="">Сделать ещё заявку</a>');
//                $('#form_ok').show('slow');
//                $('#form_new').hide();
//
//                $('.list_mag').hide();
//                $('.list_mag_ok').show('slow');

            }

        });
        //return false;

    });
    $('body').on('click', '.set_end_jobs_uvolen', function (event) {

// alert('2323');
        $(this).removeClass("show_job_tab");
        $(this).addClass("show_job_tab2");
        var $uri_query = '';
        var $vars = [];
        $.each(this.attributes, function () {

            if (this.specified) {
                // console.log(this.name, this.value);
                $uri_query = $uri_query + '&' + this.name + '=' + this.value.replace(' ', '..')

                if (this.name == 'res_to') {
                    $vars['resto'] = '#' + this.value + ' tbody';
                    // console.log($vars['resto']);
                    // alert($res_to);
                }

                if (this.name == 'show_on_click') {
                    $('#' + this.value).show('slow');
                }

            }

        });
        // console.log($vars['resto']);
        // console.log($uri_query);
        //$(this).html("тут список");
        var $th = $(this);
        $.ajax({

            xurl: "/sites/yadom_admin/module/000.index/ajax.php",
            url: "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
            data: "action=show_info_strings" + $uri_query,
            cache: false,
            dataType: "json",
            type: "post",
            beforeSend: function () {
                /*
                 if (typeof $div_hide !== 'undefined') {
                 $('#' + $div_hide).hide();
                 }
                 */
                // $("#ok_but_stat").html('<img src="/img/load.gif" alt="" border=0 />');
//                $("#ok_but_stat").show('slow');
//                $("#ok_but").hide();
            }
            ,
            success: function ($j) {

                // $($res_to).html($j.data);
                // $($vars['resto']).html($j.data);
                $($vars['resto']).append($j.data);
                // $th("#main").prepend("<div id='box1'>1 блок</div>");                    
                // $th("#main").prepend("<div id='box1'>1 блок</div>");                    
                // $th.html( $j.html + '<br/><A href="">Сделать ещё заявку</a>');
                // $($res_to_id).html( $j.html + '<br/><A href="">Сделать ещё заявку</a>');

                // return true;

                /*
                 // alert($j.html);
                 if (typeof $div_show !== 'undefined') {
                 $('#' + $div_show).show();
                 }
                 */
//                $('#form_ok').hide();
//                $('#form_ok').html($j.html + '<br/><A href="">Сделать ещё заявку</a>');
//                $('#form_ok').show('slow');
//                $('#form_new').hide();
//
//                $('.list_mag').hide();
//                $('.list_mag_ok').show('slow');

            }

        });
        //return false;

    });
    // else {
    // alert(i + ': ' + $(elem).text());
    // }

// alert('123');

    $('body').on('click', '.jobdesc__calc_full_ocenka_day', function (event) {

        var resto = '';
        var $vars = new Array();
        var $uri_query = '';
        var showid = 0;
        var hidethis = 0;
        var answer = 0;
        var resto = 0;
        var resto1 = 0;
        var showid = 0;

        var microservice = 0;

        $.each(this.attributes, function () {

            if (this.specified) {

                if (this.name == 'forajax_microservice') {

                    microservice = '/vendor/didrive_mod/jobdesc/1/didrive/micro-service/' + this.value + '.php';

                } else {

                    if (this.name.indexOf("forajax_") != -1) {
                        $uri_query = $uri_query + '&' + this.name.replace('forajax_', '') + '=' + this.value;
                        // console.log(this.name, this.value);
                    }

                    if (this.name == 'hidethis') {
                        hidethis = 1;
                    }

                    if (this.name == 'show_id') {
                        showid = '#' + this.value;
                    } else if (this.name == 'res_to_id') {
                        resto = '#' + this.value;
                        resto1 = this.value + '111';
                    } else if (this.name == 'answer') {
                        answer = this.value;
                    }

                }
            }

        });
        if (answer != 0) {

            if (!confirm(answer)) {
                return false;
            }

        }

        var $th = $(this);
        $.ajax({

            url: (microservice != 0 ? microservice : '/vendor/didrive_mod/jobdesc/1/didrive/ajax.php'),

            data: "t=1" + $uri_query,
            cache: false,
            dataType: "json",
            type: "post",
            beforeSend: function () {
                $(resto).html('<img src="/img/load.gif" alt="" border=0 />');
            }
            ,
            success: function ($j) {

                if (showid != 0) {
                    $(showid).show('slow');
                }

                if (hidethis == 1) {
                    $th.hide();
                }

                $string = '';
                $html = '';
                if ($j['status'] == 'ok') {

                    if ($j['data']['ocenka'] == 5) {
                        $html += '<div style="background-color:rgba(0,255,0,0.2);xcolor:red;padding:5px;">общая оценка: 5</div>';
                    } else {
                        $html += '<div style="background-color:rgba(255,255,0,0.2);xcolor:red;padding:5px;">общая оценка: 3</div>';
                    }

                    if ($j['data']['ocenka_time'] == 5) {
                        $html += '<div style="background-color:rgba(0,255,0,0.2);xcolor:red;padding:5px;">Оценка времени ожидания: 5</div>';
                    } else {
                        $html += '<div style="background-color:rgba(255,255,0,0.2);xcolor:red;padding:5px;">Оценка  времени ожидания: 3</div>';
                    }

                    if ($j['data']['ocenka_naruki'] == 5) {
                        $html += '<div style="background-color:rgba(0,255,0,0.2);xcolor:red;padding:5px;">Оценка суммы на руки: 5</div>';
                    } else {
                        $html += '<div style="background-color:rgba(255,255,0,0.2);xcolor:red;padding:5px;">Оценка суммы на руки: 3</div>';
                    }

                    if ($j['data']['ocenka_naruki_ot_oborota'] == 5) {
                        $html += '<div style="background-color:rgba(0,255,0,0.2);xcolor:red;padding:5px;">% от оборота на руки: 5</div>';
                    } else {
                        $html += '<div style="background-color:rgba(255,255,0,0.2);xcolor:red;padding:5px;">% от оборота на руки: 3</div>';
                    }

                    $(resto).html($html + '<br/><center><button class="btn btn-xs btn-info" onclick="$(\'#' + resto1 + '\').toggle(\'slow\');" >показать/скрыть расчёты</button></center><br/><div id="' + resto1 + '" style="display: none;background-color: rgba(0,0,255,0.2);padding:10px;" ><nobr><b>расчёт оценки</b>' + $j['data']['txt'] + '</nobr></div>');
                } else {

                    // alert('11');
                    if (resto != 0) {
                        $(resto).html('<div style="background-color:yellow;color:red;padding:5px;">' + $j['html'] + '</div>');
                    } else {
                        alert('#1731 resto = 0');
                    }

                }

            }

        });
        return false;
    });
    $('body').on('click', '.jobdesc__record__auto_bonus_zp__m', function (event) {

        var $th = $(this);
//        var sp = $th.attr('sp');
//        var date = $th.attr('date');

//         alert( sp + ' ' + date );

        var answer = 0;
//        $uri_query = '';

        $.each(this.attributes, function () {

            if (this.specified) {

//                if (this.name.indexOf("forajax_") != -1) {
//                    $uri_query = $uri_query + '&' + this.name.replace('forajax_', '') + '=' + this.value;
//                    console.log(this.name, this.value);
//                }
//                $uri_query = $uri_query + '&' + this.name.replace('forajax_', '') + '=' + this.value;

//                if (this.name == 'hidethis') {
//                    hidethis = 1;
//                }

                if (this.name == 'sp') {
                    sp = this.value;
                } else if (this.name == 'date') {
                    date = this.value;
                } else if (this.name == 'res_to_id') {
                    resto = '#' + this.value;
                } else if (this.name == 'answer') {
                    answer = this.value;
                }
            }

        });
        // console.log($uri_query);
        if (answer != 0) {

            if (!confirm(answer)) {
                return false;
            }

        }

        $.ajax({

            xurl: "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
            url: "/vendor/didrive_mod/jobdesc/1/didrive/micro-service/bonus_record_month.php",
            xdata: "action=bonus_record_month&date=" + date + "&sp=" + sp,
            data: "date=" + date + "&sp=" + sp,
            cache: false,
            dataType: "json",
            type: "post",
            beforeSend: function () {


                $("body").append("<div id='body_block' class='body_block' >пару секунд вычисляем<br/><span id='body_block_465'></span></div>");
                $(resto).html('<img src="/img/load.gif" alt="" border=0 />');
//                if (hidethis == 1) {
//                    $th.hide();
//                }

                /*
                 if (typeof $div_hide !== 'undefined') {
                 $('#' + $div_hide).hide();
                 }
                 */
//                $("#ok_but_stat").show('slow');
//                $("#ok_but").hide();
            }
            ,
            success: function ($j) {

                if ($j['status'] == 'ok') {

                    $(resto).html('<div style="background-color:rgba(0,250,0,0.3);color:black;padding:5px;">( бонусов ' + $j['kolvo'] + ')' + $j['html'] + '</div>');
                    $('#body_block_465').html('<div style="background-color:rgba(0,250,0,0.3);color:black;padding:5px;">( бонусов ' + $j['kolvo'] + ')' + $j['html'] + '</div>');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                } else {

                    $(resto).html('<div style="background-color:rgba(250,0,0,0.3);color:black;padding:5px;">ошибка: ' + $j['html'] + '</div>');
                    $('#body_block').remove();
                }

            }

        });
        return false;
        // alert('2323');
//        $(this).removeClass("show_job_tab");
//        $(this).addClass("show_job_tab2");
//        var $uri_query = '';
//        var $vars = [];
        // var $vars = serialize(this.attributes);
        // var $vars =  JSON.stringify(this.attributes);
        var resto = '';
        var $vars = new Array();
        var $uri_query = '';
        var showid = 0;
        var hidethis = 0;
        var answer = 0;
        var resto = 0;
        var showid = 0;
        $.each(this.attributes, function () {

            if (this.specified) {

                if (this.name.indexOf("forajax_") != -1) {
                    $uri_query = $uri_query + '&' + this.name.replace('forajax_', '') + '=' + this.value;
                    // console.log(this.name, this.value);
                }


                // $uri_query = $uri_query + '&' + this.name + '=' + this.value.replace(' ', '..')

//                forajax_sp="{{ sp_now }}" 
//                forajax_jobman="{{ man.id }}" 
//                forajax_datestart="{{ date_start }}"  
//                forajax_datefin="{{ date_finish }}" 

//
                if (this.name == 'hidethis') {
                    hidethis = 1;
                }

                if (this.name == 'show_id') {
                    showid = '#' + this.value;
                } else if (this.name == 'res_to_id') {
                    resto = '#' + this.value;
                } else if (this.name == 'answer') {
                    answer = this.value;
                }
//                if (this.name == 'resto') {
//                    resto = '#' + this.value;
//                    //console.log($vars['resto']);
//                    // alert($res_to);
//                }
//
//                if (this.name == 'show_on_click') {
//                    $('#' + this.value).show('slow');
//                }

            }

        });
        if (answer != 0) {

            if (!confirm(answer)) {
                return false;
            }

        }

//        alert($uri_query);
//        return false;

// console.log($vars['resto']);

// console.log($uri_query);
//$(this).html("тут список");
        var $th = $(this);
    });
// вставляем перменные времени ожидания
    if (1 == 1) {
        /**
         * получаем массив доступных ячеек для времени ожидания (для аякс вставки)
         * @returns {Array}
         */
        function get_timeo_td_from_html() {

// timeo.ajax + аякс загрузка данных

            var n = 1;
            var vars = [];
            $('div.timeo_data_td').each(function (i, elem) {

// var date = '';
                var date = $(this).attr('timeo_date');
                // var sp = '';
                var sp = $(this).attr('timeo_sp');
                if (!!sp) {
// console.log('timeo 1 ajax', date, sp);
                    if (!!date) {
                        // console.log('все переменные есть', date, sp, n);
                        vars = vars + '&d[' + n + '][date]=' + date + '&d[' + n + '][sp]=' + sp;
                        // vars.push([date, sp]);

                        n++;
                    }
                }

            });
//        console.log('результат', vars);

            return vars;
        }


// вставляем перменные времени ожидания
        function creat__timeo() {

            var vars = get_timeo_td_from_html();
            $.ajax({

                url: "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
                data: "action=timeo_show_vars&" + vars,
                cache: false,
                dataType: "json",
                type: "post",
                beforeSend: function () {

                    $('div.timeo_data_td').html('.. загружаем ..');
                },
                success: function ($j) {

                    $('div.timeo_data_td').html('-');
// переберём массив arr
                    $.each($j['res'], function (sp, value) {
                        $.each(value, function (date, value2) {
//                        console.log('sp', sp
//                                , 'date', date
//                                , 'c', (value2['cold'] || '')
//                                , 'h', (value2['hot'] || '')
//                                , 'd', (value2['delivery'] || '')
//                                );

                            $('#timeo_data_td__' + date + '__' + sp).html(
                                    (value2['cold'] || '-')
                                    // + ' / '
                                    // + (value2['hot'] || '-')
                                    + ' / '
                                    + (value2['delivery'] || '-')
                                    );
                            $('#timeo_data_td__str__' + date + '__' + sp).html(
                                    (value2['cold'] || '-') + '<br/>'
                                    + (value2['hot'] || '-') + '<br/>'
                                    + (value2['delivery'] || '-')
                                    );
                        });
                    });
                }

            });
        }
    }

// вставляем значения оборотов
    if (1 == 1) {
        /**
         * получаем массив доступных ячеек для времени ожидания (для аякс вставки)
         * @returns {Array}
         */
        function get_oborot_td_from_html() {

// timeo.ajax + аякс загрузка данных

            var n = 1;
            var vars = [];
            $('div.loaded_ajax_oborot_vars').each(function (i, elem) {

                var sp = $(this).attr('sp');
                var date_start = $(this).attr('date_start');
                var date_stop = $(this).attr('date_stop');
                if (!!sp && !!date_start && !!date_stop) {
// console.log('все переменные есть', date, sp, n);
                    vars = vars + '&d[' + n + '][date_start]=' + date_start + '&d[' + n + '][date_stop]=' + date_stop + '&d[' + n + '][sp]=' + sp;
                    // vars.push([date, sp]);

                    n++;
                }

            });
//        console.log('результат', vars);

            return vars;
        }

        function creat__oborot_show_vars() {
            var vars = get_oborot_td_from_html();
            $.ajax({

                url: "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
                data: "action=oborot_show_vars&" + vars,
                cache: false,
                dataType: "json",
                type: "post",
                beforeSend: function () {

                    $('div.oborot_data_td').html('.. загружаем ..');
                },
                success: function ($j) {

                    $('div.oborot_data_td').html('-');
                    ii = 1;
                    // переберём массив arr
                    $.each($j['res'], function (sp, value) {
                        $.each(value, function (date, value2) {

                            // console.log(sp, date, value2);
                            if (!!value2['oborot_hand']) {
                                val_hand = value2['oborot_hand'];
                            } else {
                                val_hand = '';
                            }

                            input_oborot_hand = '<input type="number" max="1000000" min="0" '
                                    + ' placeholder="уточнить"'
                                    + ' title="укажите точную сумму оборота"'

                                    + ' value="' + val_hand + '" '

                                    + ' style="width:120px; padding: 3px; margin: 0 auto; text-align:center;" '
                                    + ' class="form-control didrive__edit_items_dop_pole2" '

                                    + ' edit_item_id="' + value2['id'] + '"'
                                    + ' edit_dop_name="oborot_hand"'
                                    + ' edit_s="' + value2['s_hand'] + '"' // {{ creatSecret(oborots[now_date2][\'id\']~"oborot_hand") }}"'

                                    + ' pole_price_id="a_price_{{ sp_now }}_{{ now_date2 }}"'
                                    + ' text_in_pole_price_id="<br/><center>оборот изменился, текущая автооценка удалена</center>"'

                                    + ' delete_ocenka_sp="{{ sp_now }}"'
                                    + ' delete_ocenka_date="{{ now_date2 }}"'
                                    + ' delete_ocenka_s="{{ creatSecret(sp_now~now_date2) }}"'

                                    + ' />';
                            // if (!!value2['oborot_hand'] && value2['oborot_hand'] > 0) {
                            if (!!value2['oborot_server']) {

                                $('#data_obr_' + date + '__' + sp).html(
                                        (value2['oborot_server'] ?
                                                // ( value2['oborot_server'] || '-' ) + '<sup><abbr title="Авто значение с сервера">A</abbr></sup>'
                                                number_format(value2['oborot_server'], 0, '.', '`') + ' <sup><abbr title="Авто значение с сервера">A</abbr></sup>'
                                                : 'x')
                                        + '<br/>'

                                        + input_oborot_hand

//                                        + '<input type="number" min="0" max="900000" step="0.01" '
//                                        + ' class="number_oborot_tochnee" '
//                                        + ' value="' + val_hand + '" '
//                                        + ' />'



















                                        );
                            } else {

                                $('#data_obr_' + date + '__' + sp).html(
                                        '<sup><a href="/vendor/didrive_mod/iiko_oborot/1/didrive/ajax.php" '
                                        + ' vars="date=' + date + '&hide_form=da&action=get_oborot_for_sps&get_sp_load=' + sp + '" '
                                        + ' res_to="' + sp + '_' + ii + '_res_ob2"'
                                        + ' xtarget="_blank"'
                                        + ' class="load_ajaxjson_to_id"'
                                        + ' >загрузить с ИИКО</a></sup>'

                                        + ' <div id="' + sp + '_' + ii + '_res_ob2" ></div>'

//                                        + (value2['oborot_server'] ?
//                                                // ( value2['oborot_server'] || '-' ) + '<sup><abbr title="Авто значение с сервера">A</abbr></sup>'
//                                                number_format(value2['oborot_server'] / 1000, 1, '.', '`') + ' <sup><abbr title="Авто значение с сервера">A</abbr></sup>'
//                                                : 'x')
//
//                                        + '<br/>'

                                        // + (value2['oborot_hand'] ?? '-1')

//                                    + '<br/>'
//                                    + (value2['delivery'] || '-')
                                        );
                                ii++;
                            }


                        }
                        );
                    });
                }

            });
        }
    }

// получаем атрибуты со всех блоков что содержат класс
    function get_blocks_attr(classes) {

// timeo.ajax + аякс загрузка данных

        var n = 1;
        var arr = [];
        var string = '';
        $(classes).each(function () {

// var arr[n] = [];

            $.each(this.attributes, function () {
// this.attributes is not a plain object, but an array
// of attribute nodes, which contain both the name and value
                if (this.specified) {
                    // console.log(this.name, this.value);
                    // пропускаем аттр
                    if (this.name == 'class' || this.name == 'href') {
                    }
// обрабатываем аттр
                    else {

                        string = string + '&d[' + n + '][' + this.name + ']=' + this.value;
                        // arr.push({ n : { this.name : this.value } });
                    }
                }
            });
            n++;
        });
        return {'string': string, 'ar': arr};
        /*
         
         $(classes).each(function (i, elem) {
         
         var sp = $(this).attr('sp');
         var date_start = $(this).attr('date_start');
         var date_stop = $(this).attr('date_stop');
         if (!!sp && !!date_start && !!date_stop) {
         // console.log('все переменные есть', date, sp, n);
         vars = vars + '&d[' + n + '][date_start]=' + date_start + '&d[' + n + '][date_stop]=' + date_stop + '&d[' + n + '][sp]=' + sp;
         // vars.push([date, sp]);
         
         n++;
         }
         
         });
         //        console.log('результат', vars);
         */
        // return vars;
    }

// + назначения в график
// добавляем смены в график
    if (2 == 2) {

        function new_dolg_html(ar1, dolgn) {


//'<div class="alert-warning text-center" style="padding: 5px 2px;">'
// '               основная работа: ТКРСК1 Су-шеф (командировка 190/220)'
//  '              <br>'
//   '             спец. назначение в ТТ-2 Су-шеф'
//    '        </div>'


            var in_html = '<div class="';
            if (ar1['type'] == 'spec') {
                in_html += 'alert-warning';
            } else
            {
                in_html += 'alert-info info_new_dolgn';
            }
// in_html .= '<div class="'.(ar1['type'] == 'spec' ? 'alert-warning' : 'alert-info info_new_dolgn') + ' " '
            in_html += ' " style="padding: 5px 2px;" '
                    // +'id="block_new_dolgn98448" '
                    + '>'
//                    + '<a class="send_ajax" href="/vendor/didrive_mod/jobdesc/1/didrive/ajax.php" '
//                    + ' action="cancel_smena" '
//                    + ' ajax_id="98448" '
//                    + ' ajax_s="37b2ce4af0d1680cc2f875cd43a7a2ef" '
//                    + ' answer="Хотите отменить это назначение ?" res_to="delete_naznach_res98448" '
//                    + ' result_ok_show_to="delete_naznach_ok_res98448" '
//                    + ' xresult_ok_to="delete_naznach_ok_res98448" '
//                    + ' result_error_show_to="delete_naznach_error_res98448" '
//                    + ' xresult_error_to="delete_naznach_error_res98448" '
//                    + ' hide_block_id_when_ok="block_new_dolgn98448" '
//                    + ' xhide_block_class_when_ok=""'
//                    + '><span class="fa fa-times"></span></a>'

            if (ar1['type'] == 'spec') {
                in_html += 'спец. назначение<br/>';
            }

            in_html += '<abbr title="сотруднику назначена новая должность">' + dolgn[ar1['dolgnost']]['head'] + '</abbr>'
//                    +'<small>'
//                    + 'не&nbsp;курит'
//                    +'</small>'
                    + '</div>';
            return in_html;
        }

        /**
         * + назначения в график
         * @param {type} ar
         * @returns {undefined}
         */
        function add_new_dolg_in_graph(ar) {

            $.each(ar['job_on'], function (sp, v0) {
                $.each(v0, function (jobman, v1) {
                    $.each(v1, function (k2, v2) {
// console.log('jon', sp, jobman, v2);
                        $('#sp_man_day_d' + v2['date'] + '_sp' + sp + '_u' + jobman + ' .dolgn').html(new_dolg_html(v2, ar['dolgn']));
                        // console.log('#sp_man_day_d' + v2['date'] + '_sp' + sp + '_u' + jobman + ' .dolgn');
                    });
                });
            });
        }

        /**
         * добавляем смены в график
         * @param {type} ar
         * @returns {ret|String}
         */
        function creat_html_1smena(ar) {

            ret = '';
            // показывать или нет сумму часов и выбор цены
            var show_hours = false;
            var now_date = false;
//            ret += '<div style="text-align:left;" ><small>';
//            $.each(ar, function (k2, a2) {
//                ret += '<br/>' + k2 + ': ' + a2;
//            });
//            ret += '</small></div>';

            ret += ' <div class="text-center show_down_hiden smena1 '

                    /*
                     { % if check.status == 'hide' % }
                     smena_cancel
                     
                     { % elseif check.status == 'show' % }
                     
                     {# чел начал смену и не закончил #}
                     { % if check.who_add_item is defined and check.who_add_item == 'user' % }
                     
                     { % if check.error_added is defined and check.error_added == 'yes' % }
                     { % set user_smena_status = 'add_error' % }
                     smena_started_error                                 
                     { % elseif check.start is defined and check.fin is not defined % }
                     { % set user_smena_status = 'add' % }
                     smena_started
                     { % elseif check.start is defined and check.fin is defined % }
                     { % set user_smena_status = 'add_ok' % }
                     */
//                     + ' smena_started_ok '
                    + ' smena_ok '
                    /*
                     { % endif % }
                     */
                    + ' "> ';
            /*
             
             {#{ pa(check) }#}
             
             */

            if (typeof ar['who_add_item'] !== 'undefined' && ar['who_add_item'] == 'iiko') {
                /* <nobr>
                 { % if check.who_add_item is defined and check.who_add_item == 'iiko' % }
                 */
                ret += ' <abbr title="загружено с сервера ИИКО #{{  check.id }}" ><small style="float:left;">iiko</small></abbr> '
                        + ' <br clear="all" /> ';
            } else {
// { % elseif check.who_add_item is defined and check.who_add_item == 'admin' % }

                ret += ' <abbr title="добавлено вручную #{{  check.id }}" ><small style="float:left;">вручную</small></abbr> '
                        + ' <br clear="all" /> ';
            }
            /*
             { % endif % }
             
             {#{ pa(check) }#}
             */
            ret += ' <div '
//    xstyle="display:inline-block;" 
                    + ' class="point_div text-center" > ';
// если есть такая переменная
            if (typeof ar['start'] !== 'undefined') {

// если есть такая переменная
                if (typeof ar['fin'] !== 'undefined') {

// показывать или нет сумму часов и выбор цены
                    var show_hours = true;
                    var now_date = ar['start'].slice(11, -3);
                    ret += '<abbr class="job_timer" >'
                            + ar['start'].slice(11, -3) + ' - ' + ar['fin'].slice(11, -3) + '<br/>'
                            + '</abbr>';
                }
// если старт есть и финиша нет
                else {

                    ret += ar['start'].slice(11, -3) + ' - ...'; // {{ check.start | date("H:i") }} - ...
                }
            }



            if (show_hours = true) {

                ret += '<nobr>';
//             { % if check.hour_on_job_hand is defined and check.hour_on_job_hand > 0 % }
//             { % set hours_on_job = check.hour_on_job_hand % }
//             { % elseif check.hour_on_job is defined and check.hour_on_job > 0 % }
//             { % set hours_on_job = check.hour_on_job % }
//             { % else % }
//             { % set hours_on_job = 0 % }
//             { % endif % }

                ret += '<i class="fa fa-minus ajax_hour_action"  '

                        + ' type_action="-" '
                        + ' hour_id="' + ar['id'] + '" '
                        + ' block="hour_' + ar['id'] + '" '
                        + ' s="' + ar['s'] + '" '

//                        + ' cash_delete1_1="hoursonjob" '
//                        + ' cash_delete1_2name="date" '
//                        + ' cash_delete1_2="' + now_date + '" '
//                        + ' cash_delete1_3name="sp" '
//                        + ' cash_delete1_3="' + ar['sp'] + '" '

                        + ' sp="' + ar['sp'] + '" '
                        + ' date="' + now_date + '" '

                        //+ ' onclick="$(\'#hoursday_' + now_date + '\').html(\'<div class="bg-warning" style="padding:5px;" >Значение изменено</div>\');" '

                        + '></i>';
                // { % endif % }

                ret += '<span id="hour_' + ar['id'] + '" '

                        // {# для динамичного показа суммы за смену + #}
                        + ' class="job_hours" id_smena="' + ar['id'] + '" '
                        // {# для динамичного показа суммы за смену - #}
                        // xstyle="display: inline-block; width:40px; border:1px solid green;"

                        + ' title="{{ check.start | date("d.m H:i") }} - {{ check.fin | date("d.m H:i") }} отработано часов (авторасчет):' + ar['hour_on_job'] + '" '

                        // { % if check.hour_on_job_hand is defined % } style="font-weight: bold;" { % endif % } 

                        + (typeof ar['hour_on_job_hand'] !== 'undefined' ? ' style="font-weight: bold;" ' : '')

                        + ' >' + (typeof ar['hour_on_job_hand'] !== 'undefined' ? ar['hour_on_job_hand'] : ar['hour_on_job']) + '</span>';
                ret += '<i class="fa fa-plus ajax_hour_action" '
                        + ' type_action="+" '
                        + ' hour_id="' + ar['id'] + '" '
                        + ' block="hour_' + ar['id'] + '" '
                        + ' s="' + ar['s'] + '" '

//                        + ' cash_delete1_1="hoursonjob" '
//                        + ' cash_delete1_2name="date" '
//                        + ' cash_delete1_2="' + now_date + '" '
//                        + ' cash_delete1_3name="sp" '
//                        + ' cash_delete1_3="' + ar['sp'] + '" '

                        + ' sp="' + ar['sp'] + '" '
                        + ' date="' + now_date + '" '

                        // + ' onclick="$(\'#hoursday_{{ date }}').html('<div class=\'bg-warning\' style=\'padding:5px;\' >Значение изменено</div>');" '

                        + ' ></i> ';
//                ret += '<a href="#"  class="but_show_option" '
//                        // + ' onclick="$('#drop2_{{ rand_d }},.drop2_{{ rand_d }}').toggle('slow'); '
//                        + ' return false;" '
//                        + ' >'
//                        + ' <span class="fa fa-caret-down" ></span>'
//                        + ' </a>';

                ret += '</nobr><br/>';
            }


            /*       
             {# если нет финиша #}
             { % if check.start is defined and check.fin is not defined % }
             
             {# если есть финиш #}
             { % else % }
             
             <abbr class="job_hour" >
             <nobr>
             
             { % if check.hour_on_job_hand is defined and check.hour_on_job_hand > 0 % }
             { % set hours_on_job = check.hour_on_job_hand % }
             { % elseif check.hour_on_job is defined and check.hour_on_job > 0 % }
             { % set hours_on_job = check.hour_on_job % }
             { % else % }
             { % set hours_on_job = 0 % }
             { % endif % }
             
             { % if check.status == 'show' and check.payed is not defined % }
             <i class="fa fa-minus ajax_hour_action" 
             
             type_action="-" 
             hour_id="{{ check.id }}" 
             block="hour_{{ check.id }}" 
             s="{{ creatSecret('hour_'~check.id) }}"
             
             cash_delete1_1="hoursonjob"
             cash_delete1_2name="date"
             cash_delete1_2="{{ date }}"
             cash_delete1_3name="sp"
             cash_delete1_3="{{ sp_now }}"
             
             sp="{{ sp_now }}"
             date="{{ date }}"
             
             onclick="$('#hoursday_{{ date }}').html('<div class=\'bg-warning\' style=\'padding:5px;\' >Значение изменено</div>');"
             
             ></i>
             { % endif % }
             
             typeof variable !== 'undefined'
             
             +++          
             <span id="hour_{{ check.id }}" 
             
             {# для динамичного показа суммы за смену + #}
             class="job_hours" id_smena="{{ check.id }}" 
             {# для динамичного показа суммы за смену - #}
             
             xstyle="display: inline-block; width:40px; border:1px solid green;"
             
             title="{{ check.start | date("d.m H:i") }} - {{ check.fin | date("d.m H:i") }} отработано часов (авторасчет):{{ check.hour_on_job }}"
             { % if check.hour_on_job_hand is defined % } style="font-weight: bold;" { % endif % } 
             >{{ hours_on_job }}</span>
             
             +++            
             
             { % if check.status == 'show' and check.payed is not defined % }
             
             <i class="fa fa-plus ajax_hour_action" 
             type_action="+" 
             hour_id="{{ check.id }}" 
             block="hour_{{ check.id }}" 
             s="{{ creatSecret('hour_'~check.id) }}" 
             
             cash_delete1_1="hoursonjob"
             cash_delete1_2name="date"
             cash_delete1_2="{{ date }}"
             cash_delete1_3name="sp"
             cash_delete1_3="{{ sp_now }}"
             
             sp="{{ sp_now }}"
             date="{{ date }}"
             
             onclick="$('#hoursday_{{ date }}').html('<div class=\'bg-warning\' style=\'padding:5px;\' >Значение изменено</div>');"
             
             ></i> 
             
             <a href="#"  class="but_show_option" 
             onclick="$('#drop2_{{ rand_d }},.drop2_{{ rand_d }}').toggle('slow');
             return false;" 
             >
             <span class="fa fa-caret-down" ></span>
             </a>
             { % endif % }
             
             </nobr>
             
             <br/>
             </abbr>
             
             { % endif % }
             
             
             
             { % if check.status == 'show' % }
             
             { % set summa_day = 0 % }
             
             <input 
             xtype="text" 
             type="hidden" 
             class="hours_kolvo" 
             value="{ % if check.hour_on_job_hand is defined % }{{ check.hour_on_job_hand }}{ % else % }{{ check.hour_on_job_calc }}{ % endif % }" >
             
             <center>
             
             { % if data_on_workman_day['salary']['ocenka-hour-base'] is defined % }
             
             { % if (now_dolgn.smoke is defined and now_dolgn.smoke == 'da') or (now_job.smoke is defined and now_job.smoke == 'da') % }
             { % set price_hour = data_on_workman_day['salary']['ocenka-hour-base'] + data_on_workman_day['salary']['if_kurit'] % }
             { % else % }
             { % set price_hour = data_on_workman_day['salary']['ocenka-hour-base'] % }
             { % endif % }
             
             <input 
             type="hidden" 
             class="price_hour_{{ date }}_{{ sp_now }}" 
             value="{{ price_hour }}" 
             >
             
             {{ price_hour }}р/ч
             
             { % set summa_day = hours_on_job * price_hour % }
             
             { % else % }
             
             
             {#
             <div class="text-left" >
             {{ pa(now_job) }}
             {{ pa(salar) }}
             </div>
             #}
             
             {# % if date == '2020-03-01' % }
             <div style="text-align:left;" >
             check
             {{  pa(check) }}
             now_dolgn
             {{  pa(now_dolgn) }}
             </div>
             { % endif % #}
             
             
             
             
             <select name="ocenka" 
             class="select_price_hour_now select_edit_item_dop price_hour_{{ date }}_{{ sp_now }}_select
             
             {# для динамичного показа суммы за смену + #}
             smena_price_{{ check.id }}
             {# для динамичного показа суммы за смену - #}
             " 
             
             action="edit_dop_pole"
             folder="{{ folder }}"
             module="050.chekin_checkout"
             dop_name="ocenka"
             item_id="{{ check.id }}"
             s="{{ creatSecret('050.chekin_checkout'~'ocenka'~check.id) }}" 
             
             { % if check.payed is defined % } disabled="disabled" { % endif % }
             >
             <option price="0" value="">Оценка</option>
             
             {#{ pa(salar) }#}
             
             { % set price_hour1_now = '' % }
             
             { % for i in range(low = 5, high = 2, step = - 1) % }    {#{ i }#}
             
             { % set price_hour = 0 % }
             
             { % if salar['ocenka-hour-'~i] is defined % }> 
             { % if (now_dolgn.smoke is defined and now_dolgn.smoke == 'da') or (now_job.smoke is defined and now_job.smoke == 'da') % }
             { % set price_hour = salar['ocenka-hour-'~i] + salar['if_kurit'] % }
             { % else % }
             { % set price_hour = salar['ocenka-hour-'~i] % }
             { % endif % }
             { % endif % }
             
             { % if price_hour > 0 % }
             <option value="{{ i }}" 
             
             {# для динамичного показа суммы за смену + #}
             price="{{price_hour}}" 
             {# для динамичного показа суммы за смену - #}
             
             { % if check.ocenka is defined and check.ocenka == i % }
             selected="selected"
             
             { % if price_hour1_now == '' % }
             { % set price_hour1_now = price_hour % }
             { % endif % }
             
             {# % elseif check.ocenka_auto is defined and check.ocenka_auto == i and check.ocenka is not defined % #}
             { % elseif check.ocenka is not defined and check.ocenka_auto is defined and check.ocenka_auto == i % }
             
             { % set price_hour_now = price_hour % }
             
             { % if price_hour1_now == '' % }
             { % set price_hour1_now = price_hour % }
             { % endif % }
             
             selected="selected"
             { % endif % } 
             
             xprice="{{price_hour}}" 
             >
             
             {{ i }} 
             
             { % if check.ocenka_auto is defined and check.ocenka_auto == i % }
             (А)
             { % endif % } 
             
             {# % if check.ocenka_auto is defined and check.ocenka_auto == i % }
             (Р)
             { % endif % #} 
             
             { % if price_hour > 0 % }
             > {{price_hour}} р/ч
             { % endif % }
             
             {# pr1 {{ price_hour1_now }} #}
             
             </option>
             
             { % endif % }
             
             { % endfor % }
             
             {#
             <option value="4" { % if check.ocenka is defined and check.ocenka == 4 % }selected="selected"{ % endif % } >4 { % if salar['ocenka-hour-4'] is defined % }> {{ salar['ocenka-hour-4'] }}р/ч{ % endif % }</option>
             <option value="3" { % if check.ocenka is defined and check.ocenka == 3 % }selected="selected"{ % endif % } >3 { % if salar['ocenka-hour-3'] is defined % }> {{ salar['ocenka-hour-3'] }}р/ч{ % endif % }</option>
             <option value="2" { % if check.ocenka is defined and check.ocenka == 2 % }selected="selected"{ % endif % } >2 { % if salar['ocenka-hour-2'] is defined % }> {{ salar['ocenka-hour-2'] }}р/ч{ % endif % }</option>
             #}
             
             </select>
             
             { % if price_hour1_now != '' % }
             { % set summa_day = hours_on_job * price_hour1_now % }
             { % endif % }
             
             { % endif % }
             
             
             </center>
             
             { % endif % }
             
             
             {# для динамичного показа суммы за смену 1911 + #}
             */
            +'<div class="smena_summa smena_summa_{{ check.id }}" title="сумма за смену">'

                    /*
                     { % if summa_day is defined and summa_day != 0 % }
                     {{ summa_day }}
                     { % else % }
                     ...
                     { % endif % }
                     */
                    + '</div>'

                    /*
                     {# < div class = "smena_summa smena_summa_{{ check.id }}" title = "сумма за смену" > {{ price_hour * price_hour_now }} < /div>#}
                     {# для динамичного показа суммы за смену 1911 - #}
                     
                     {#
                     <a href=""><span class="ocenka_text">Оценка:</span> <span class="ocenka_num" >...</span></a> < br / >
                     #}
                     */
                    + '</div>'
                    /*
                     
                     { % if user_smena_status == 'add' % }
                     {# % if user_smena_status == '' or user_smena_status == 'add_error' or user_smena_status == 'add' % #}
                     { % else % }
                     
                     { % if check.status == 'hide' % }
                     <span class="hide_down" >
                     удалённая смена
                     </span>
                     
                     { % elseif check.status == 'show' % }
                     
                     { % if check.pay_check == 'yes' % }
                     
                     <span class="hide_down" >
                     отправлено в бух. ожидает оплаты
                     </span>
                     
                     <a href="#" class="btn3 edit_items_dop_values drop2_{{ rand_d }}" 
                     style='display:none;'
                     {# действие после вопроса #}
                     comit_answer="Отменить разрешение на оплату смены ?"
                     
                     {# замена доп параметра #}
                     action="edit_dop_item"
                     
                     {# модуль итемов #}
                     itemsmod="050.chekin_checkout"
                     {# id итема #}
                     item_id="{{ rand_d }}"
                     {# название доп параметра #}
                     dop_name="pay_check"
                     {# новое значение параметра #}
                     dop_new_value="no"
                     
                     {# секрет #}
                     s3="{{ creatSecret('050.chekin_checkout-'~rand_d~'-pay_check-no') }}" 
                     
                     {# скрыть ссылку после клика #}
                     hidethis="da" 
                     {# сделать видимым блок по id #}
                     show_id="ares{{ rand_d }}" 
                     {# id куда печатаем результат #}
                     res_to_id="ares{{ rand_d }}" 
                     {# сообщение печатаем если всё ок #}
                     msg_to_success="Отменено"
                     
                     {# print_res_to_id = "ares{{ rand_d }}" #}
                     
                     >Отозвать разрешение на оплату</a>
                     
                     
                     { % else % }
                     
                     { % if 1 == 2 % }
                     <a href="#" class="btn3 edit_items_dop_values" 
                     
                     {# действие после вопроса #}
                     comit_answer="Отправляем на оплату ?"
                     
                     {# замена доп параметра #}
                     action="edit_dop_item"
                     
                     {# модуль итемов #}
                     itemsmod="050.chekin_checkout"
                     {# id итема #}
                     item_id="{{ rand_d }}"
                     {# название доп параметра #}
                     dop_name="pay_check"
                     {# новое значение параметра #}
                     dop_new_value="yes"
                     
                     {# секрет #}
                     s3="{{ creatSecret('050.chekin_checkout-'~rand_d~'-pay_check-yes') }}" 
                     
                     {# скрыть ссылку после клика #}
                     hidethis="da" 
                     {# сделать видимым блок по id #}
                     show_id="ares{{ rand_d }}" 
                     {# id куда печатаем результат #}
                     res_to_id="ares{{ rand_d }}" 
                     {# сообщение печатаем если всё ок #}
                     msg_to_success="Отправили на оплату, спасибо"
                     
                     {# print_res_to_id = "ares{{ rand_d }}" #}
                     
                     >Отправить на оплату 2</a>
                     { % endif % }
                     { % endif % }
                     
                     {# { pa(check) } #}
                     
                     { % endif % }
                     { % endif % }
                     
                     <span class="xhide_down" style="display:none;" id="drop2_{{ rand_d }}">
                     
                     { % if check.status == 'hide' % }
                     
                     {#
                     <a href="#" class="actx act_smenax btn3 edit_items_dop_values" 
                     
                     action="recover_smena" 
                     go_answer="Хотите восстановить смену ?"
                     id2="{{ rand_d }}" 
                     s2="{{ creatSecret(rand_d) }}" 
                     resto="ares{{ rand_d }}" 
                     show_id="ares{{ rand_d }}" 
                     hidethis="da" 
                     
                     >восстановить</a>
                     #}
                     
                     { % elseif check.status == 'show' % }
                     
                     
                     
                     <a href="#" class="act act_smena btn3" 
                     go_answer="Хотите удалить смену ?"
                     action="delete_smena" id2="{{ check.id }}" s2="{{ creatSecret(check.id) }}" resto="ares{{ check.id }}" show_id="ares{{ check.id }}" hidethis="da" >удалить смену</a>
                     
                     { % if 1 == 2 % }
                     <a href="#" class="act act_smena btn3" 
                     
                     
                     {# действие после вопроса #}
                     comit_answer="Отправляем на оплату ?"
                     
                     {# замена доп параметра #}
                     action="edit_dop_item"
                     
                     {# модуль итемов #}
                     itemsmod="050.chekin_checkout"
                     {# id итема #}
                     item_id="{{ rand_d }}"
                     {# название доп параметра #}
                     dop_name="pay_check"
                     {# новое значение параметра #}
                     dop_new_value="yes"
                     
                     {# секрет #}
                     s3="{{ creatSecret('050.chekin_checkout-'~rand_d~'-pay_check-yes') }}" 
                     
                     {# скрыть ссылку после клика #}
                     hidethis="da" 
                     {# сделать видимым блок по id #}
                     show_id="ares{{ rand_d }}" 
                     {# id куда печатаем результат #}
                     res_to_id="ares{{ rand_d }}" 
                     {# сообщение печатаем если всё ок #}
                     msg_to_success="Отправили на оплату, спасибо"
                     
                     >редактировать смену</a>
                     { % endif % }
                     { % endif % }
                     
                     </span>
                     
                     <div id="ares{{ rand_d }}" style="display:none;"></div>
                     
                     
                     { % if check.payed is defined % }
                     
                     { % set pay_all = 0 % }
                     { % set pay_string = '' % }
                     
                     { % for k2, v2 in check.payed % }
                     
                     { % set pay_all = pay_all + v2.summa % }
                     { % set pay_string = pay_string~' / '~v2.summa % }
                     
                     { % endfor % }
                     
                     { % if pay_all != 0 % }
                     
                     <abbr class="pole_oplacheno 
                     
                     {# для динамичного показа суммы за смену + #}
                     smena_oplacheno_{{ check.id }}
                     {# для динамичного показа суммы за смену - #}
                     
                     " 
                     
                     {# для динамичного показа суммы за смену + #}
                     summ="{{ pay_all }}" 
                     {# для динамичного показа суммы за смену - #}
                     
                     title="оплачено {{ pay_string }} /" ><i class="fa fa-money" ></i> {{ pay_all | number_format(0, '.', '`') }}&nbsp;₽</abbr>
                     
                     { % endif % }
                     
                     { % endif % }
                     
                     {# { pa(check) } #}
                     
                     </nobr>
                     */
                    + ' </div> ';
            return ret;
        }


        function creat__ajax_in_smens() {

            $('div.graph_cell_1sp_man_day .smens').addClass('ajax_loaded');
            var ert = [];
            ert = get_blocks_attr('div.load_job_man_1sp_smens');
            // console.log('результат', ert);
            $.ajax({

                url: "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
                data: "action=ajax_in_smens&" + ert['string'],
                cache: false,
                dataType: "json",
                type: "post",
                beforeSend: function () {

                    // $('div.graph_cell_1sp_man_day .smens').html('&nbsp;');
                    $('div.graph_cell_1sp_man_day .smens').addClass('ajax_loaded');
                },
                success: function ($j) {

                    if (typeof document.nyos !== 'undefined') {
                    } else {
                        document['nyos'] = [];
                    }

                    if (typeof document['nyos']['dolgn'] !== 'undefined') {
                    } else {
                        document['nyos']['dolgn'] = $j['dolgn']
                    }

                    if (typeof document.nyos.dolgn_money !== 'undefined') {
                    } else {
                        document.nyos.dolgn_money = $j.dolgn_money
                    }

                    if (typeof document.nyos.job_on !== 'undefined') {
                    } else {
                        document.nyos.job_on = $j.job_on
                    }

                    // console.log('doc', document);


                    add_new_dolg_in_graph($j);

                    $.each($j['checks'], function (date, value) {
                        // console.log('1', date, value);
                        $.each(value, function (k1, ar) {

                            // console.log('1day', date, ar);

                            $hh = creat_html_1smena(ar);

                            $('#sp_man_day_d' + date + '_sp' + ar['sp'] + '_u' + ar['jobman'] + ' div.smens').html($hh);

//                            var $b = $('#sp_man_day_d' + date + '_sp' + ar['sp'] + '_u' + ar['jobman'] + ' div.smens');
//                            $b.hide();
//                            $b.html($hh);
//                            $b.show('slow');
//                            
                            // sp_man_day_d{{ date }}_sp{{sp_now}}_u{{ user_id }}
                            // console.log('1', date, value1);

                        });
                    });
                    $('div.graph_cell_1sp_man_day .smens').removeClass('ajax_loaded');
                }

            });
        }
    }

    if (1 == 2) {
// добавляем минусы плюсы бонусы
        if (2 == 1) {

            function creat_html_bonus(ar) {

                ret = '<div class="'
                        // +'xtext-center '
                        + 'show_down_hiden plus show">'

                        + '<a '
                        + ' href="#" '

                        + ' class="base__send_to_ajax hide_down" '
                        + ' style="float:right;" '

                        + ' href_to_ajax="/vendor/didrive_mod/items/1/ajax.php" '

                        + ' hidethis="da" '
                        + ' answer="удалить ?" '

                        + ' action="remove_item" '
                        + ' aj_id="' + ar['id'] + '" '
                        + ' aj_s="' + ar['s'] + '" '

                        + ' res_to_id="plus' + ar['id'] + '" '
                        + ' after_click_showid="plus' + ar['id'] + '" '
                        + ' msg_to_success="бонус удалён" '

                        + '><i class="fa fa-times"></i></a>'

                        + '<b>+'
                        + ar['summa']
                        + '</b> &nbsp;'
                        + '<small>'
                        + ar['text']
                        + '</small>'
//                    + '<span class="hide_down">'
//                    + '<a href="#" class="btn3 edit_items_dop_values drop2_ btn btn-xs btn-light" '
//                    + 'xstyle="display:none;" '
//                    + 'comit_answer="Отменить премию ?" action="edit_dop_item" '
//                    + 'itemsmod="072.plus" item_id="97789" '
//                    + 'new_status="hide" s3="dea83abff19ec1d0cc3fd72253cf5809" hidethis="da" '
//                    + 'show_id="ares97789" res_to_id="ares97789" '
//                    + 'msg_to_success="Отменено">Отменить премию</a></span>'
//                    + '<div id="ares97789" style="display:none;"></div>'
                        + '<div id="plus' + ar['id'] + '" style="display:none;"></div>';
                +'</div>';
                return ret;
            }

            function creat_html_minus(ar) {

                var ret = '<div class="xtext-center show_down_hiden minus show">'


                        + '<a '
                        + ' href="#" '

                        + ' class="base__send_to_ajax hide_down" '
                        + ' style="float:right;" '

                        + ' href_to_ajax="/vendor/didrive_mod/items/1/ajax.php" '

                        + ' hidethis="da" '
                        + ' answer="удалить ?" '

                        + ' action="remove_item" '
                        + ' aj_id="' + ar['id'] + '" '
                        + ' aj_s="' + ar['s'] + '" '

                        + ' res_to_id="minus' + ar['id'] + '" '
                        + ' after_click_showid="minus' + ar['id'] + '" '
                        // + ' msg_to_success="Комментарий удалён" '

                        + '><i class="fa fa-times"></i></a>'

                        + '<b>'
                        + '-' + ar['summa']
                        + '</b> &nbsp; '
                        + '<small>'
                        + ar['text']
                        + '</small>'
//                    + '<span class="hide_down">'
//                    + '<a href="#" '
//                    + ' class="btn3 edit_items_dop_values drop2_ btn btn-xs btn-light" '
//                    // + 'xstyle="display:none;" comit_answer="Отменить взыскание ?" '
//                    + ' action="edit_dop_item" itemsmod="072.vzuscaniya" item_id="99244" '
//                    + ' new_status="hide" s3="f50e87feccec1fb4f19d886948fb979e" '
//                    + ' hidethis="da" show_id="ares99244" res_to_id="ares99244" '
//                    + ' msg_to_success="Отменено">Отменить взыскание</a>'
//                    + '</span>'
//                    + '<div id="ares99244" style="display:none;"></div>'

                        + '<div id="minus' + ar['id'] + '" style="display:none;"></div>';

                +'</div>';
                return ret;
            }

            function creat_html_comment(ar, cfg) {

                var ret = '<div class="show_1comment">'

                        + '<a '
                        + ' href="#" '

                        + ' class="base__send_to_ajax" '
                        + ' style="float:right;" '

                        + ' href_to_ajax="/vendor/didrive_mod/items/1/ajax.php" '

                        + ' hidethis="da" '
                        + ' answer="удалить комментарий ?" '

                        + ' action="remove_item" '
                        + ' aj_id="' + ar['id'] + '" '
                        + ' aj_s="' + ar['s'] + '" '

                        + ' res_to_id="com' + ar['id'] + '" '
                        + ' after_click_showid="com' + ar['id'] + '" '
                        + ' msg_to_success="Комментарий удалён" '

                        + '><i class="fa fa-times"></i></a>'

                        + ar['comment']

                        + '<div id="com' + ar['id'] + '" style="display:none;"></div>';
                +'</div>'
                return ret;
            }

        }

        if (1 == 2) {

// если есть этот блок то грузим всю аякс
            var $e = $('#run_load_data').attr('start');
            if ($e == 'da') {
                // alert('123');
                creat__oborot_show_vars();
                creat__ajax_in_smens();
                run_load__aj_get_minus_plus_coment();
            }


//// запуск загрузки времени ожидания
//    var $e1 = $('#run_load_timeo').attr('start');
//    if ($e1 == 'da') {
////        alert('123');
////        creat__oborot_show_vars();
//        creat__timeo();
////        run_load__aj_get_minus_plus_coment();
//    }

        }
    }


// запуск загрузки времени ожидания
    var $e1 = $('#run_load_timeo').attr('start');
    if ($e1 == 'da') {
//        alert('123');
//        creat__oborot_show_vars();
        creat__timeo();
//        run_load__aj_get_minus_plus_coment();
    }



// $(document).ready(function () { // вся мaгия пoслe зaгрузки стрaницы
//    nd = didrive__get_cash();
//    console.log('9999999999', nd );
//    
//    nd = didrive__get_cash();
//    console.log('9999999999', nd );
// });







    /**
     * добавляем взыскание / комментарий / бонус
     * 2007 версия
     */

    $('body').on('submit', '.add_form', function (event) {

        // console.log('добавляем минус');

        event.preventDefault();
        // создание массива объектов из данных формы        
        var data1 = $(this).serializeArray();

        // console.log( 'добавляем минус', data1 );

        // переберём каждое значение массива и выведем его в формате имяЭлемента=значение в консоль
        // console.log('Входящие данные');
        $.each(data1, function () {

            // console.log(this.name + '=' + this.value);

            if (this.name == 'print_res_to_id') {
                $print_res_to = $('#' + this.value);
            }

            if (this.name == 'data-target2') {
                $modal_id = this.value;
            }

        });
        // alert('123');
        // return false;

        $.ajax({

            type: 'POST',
            // xurl: "/sites/yadom_admin/module/000.index/ajax.php",
            url: "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",

            dataType: 'json',
            data: data1,

            // сoбытиe дo oтпрaвки
            beforeSend: function ($data) {
                // $div_res.html('<img src="/img/load.gif" alt="" border="" />');
                // $this.css({"border": "2px solid orange"});
            },

            // сoбытиe пoслe удaчнoгo oбрaщeния к сeрвeру и пoлучeния oтвeтa
            success: function ($data) {

                //alert('123');

                // eсли oбрaбoтчик вeрнул oшибку
                if ($data['status'] == 'error')
                {
                    // alert($data['error']); // пoкaжeм eё тeкст
                    // $div_res.html('<div class="warn warn">' + $data['html'] + '</div>');
                    // $this.css({"border": "2px solid red"});

                    $print_res_to.append('<div>произошла ошибка: ' + $data['html'] + '</div>');
                }
                // eсли всe прoшлo oк
                else
                {
                    // $div_res.html('<div class="warn good">' + $data['html'] + '</div>');
                    // $this.css({"border": "2px solid green"});

                    $print_res_to.append($data['html']);
                    // run_load__aj_get_minus_plus_coment();
                }

                //$($modal_id).modal('hide');
                $('.modal').modal('hide');
            }
            ,
            // в случae нeудaчнoгo зaвeршeния зaпрoсa к сeрвeру
            error: function (xhr, ajaxOptions, thrownError) {
                // пoкaжeм oтвeт сeрвeрa
                alert(xhr.status + ' ' + thrownError); // и тeкст oшибки
            }

            // сoбытиe пoслe любoгo исхoдa
            // ,complete: function ($data) {
            // в любoм случae включим кнoпку oбрaтнo
            // $form.find('input[type="submit"]').prop('disabled', false);
            // }

        }); // ajax-


        return false;
    });


// новая версия 2007
    $('body').on('click', '.ajax_hour_action', function () {

        var in_date = '';
        var in_sp = '';
//        clearTdSummAllGraph();
        var uri_query = '';
        $.each(this.attributes, function () {
            if (this.specified) {

                if ( this.name == 'class' 
                        || this.name == 'onclick'
                        || this.name == 'style'
                        ) {
                } else {
                    //console.log(1, this.name, this.value);
                    uri_query = uri_query + '&ajax_' + this.name + '=' + this.value;
                    if (this.name == 'date') {
                        in_date = this.value;
                    } else if (this.name == 'sp') {
                        in_sp = this.value;
                    }
                }
            }
        });
        $th = $(this);
        $znak = $th.attr('type_action'); // - || +
        // console.log($znak); // - || +

        $hour_id = $th.attr('hour_id'); // - || +
        // console.log($hour_id); // - || +

        $textblock_id = $th.attr('block');
        // console.log($textblock_id);

        $s = $th.attr('s');
        // console.log($textblock_id);

        $cifra = Number(parseFloat($('span#' + $textblock_id).text())).toFixed(1);
        if ($cifra > 20)
            $cifra = 20;
        // console.log($('span#' + $textblock_id).text());
        // console.log($cifra);
        if ($znak == '-') {
            var $new_val = +$cifra - +0.5;
        }
//
        else if ($znak == '+') {
            var $new_val = +$cifra + +0.5;
        }

        $('span#' + $textblock_id).text($new_val);
        $.ajax({

            // xxurl: "/vendor/didrive_mod/items/1/ajax.php",
            // xurl: "/vendor/didrive_mod/items/1/micro-service/edit-dop-pole.php",
            url: "/vendor/didrive_mod/items/2/micro-service/edit-dop-pole.php",
            data: uri_query + "&action=edit_dop_pole&item_id=" + $hour_id + "&dop_name=hour_on_job_hand&new_val=" + $new_val + "&id=" + $textblock_id + "&s=" + $s,
            cache: false,
            dataType: "json",
            type: "post",
            async: false,
            beforeSend: function () {

                $('span#' + $textblock_id).css('border-bottom', '2px solid orange');
                $('span#' + $textblock_id).css('font-weight', 'bold');
                //if (typeof $div_hide !== 'undefined') {
                //$('#' + $div_hide).hide();
                //}

                // $("#ok_but_stat").html('<img src="/img/load.gif" alt="" border=0 />');
                //                $("#ok_but_stat").show('slow');
                //                $("#ok_but").hide();


            }
            ,
            success: function ($j) {

                // alert($j.status);

                if ($j.status == 'error') {

                    $('span#' + $textblock_id).css('border-bottom', '2px solid red');
                    // $('span#' + $textblock_id).css('color', 'darkred');

                } else {

                    // ocenka_clear(in_sp, in_date);
                    // $.debounce(1000, ocenka_clear(in_sp, in_date) );

                    $('span#' + $textblock_id).css('border-bottom', '2px solid green');
                    $('span#' + $textblock_id).closest('.smena1').find('.hours_kolvo').val($new_val);
                    $('.smena_summa_' + $hour_id).html($('#price_' + $hour_id + ' option:selected').attr('price') * $new_val);

                    // $.debounce( 1000, calcSummMoneySmena2 );
//                    setTimeout( function () {
//                        //calculateSummAllGraph();
//
//                        console.log('$textblock_id', $textblock_id);
//                        // alert($textblock_id);
//
//                        calcSummMoneySmena($textblock_id);
//
//                    }, 100);
//                    //$(document).one( calculateSummAllGraph );

                }


            }

        });

//        setTimeout( function () {
//            calcSummMoneySmena2($textblock_id);
//            }, 100);

        return false;
    });

//        setTimeout( function () {
//            calcSummMoneySmena2();
//            }, 100);


// новая версия 2007
    $('body').on('submit', '#add_new_smena', function (event) {

        event.preventDefault();
        // создание массива объектов из данных формы
        var data1 = $(this).serializeArray();
        // переберём каждое значение массива и выведем его в формате имяЭлемента=значение в консоль
        // console.log('Входящие данные');
        $.each(data1, function () {

            // console.log(this.name + '=' + this.value);
            if (this.name == 'print_res_to_id') {
                $print_res_to = $('#' + this.value);
            }

            if (this.name == 'data-target2') {
                $modal_id = this.value;
            }

        });
        // alert('123');
        // return false;

        $.ajax({

            type: 'POST',
            xurl: "/sites/yadom_admin/module/000.index/ajax.php",
            xurl: "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
            url: "/vendor/didrive_mod/jobdesc/1/didrive/micro-service/add-new-smena.php",
            dataType: 'json',
            data: data1,
            // сoбытиe дo oтпрaвки
            beforeSend: function ($data) {
                // $div_res.html('<img src="/img/load.gif" alt="" border="" />');
                // $this.css({"border": "2px solid orange"});
            },
            // сoбытиe пoслe удaчнoгo oбрaщeния к сeрвeру и пoлучeния oтвeтa
            success: function ($data) {

                //alert('123');

                // eсли oбрaбoтчик вeрнул oшибку
                if ($data['status'] == 'error')
                {
                    // alert($data['error']); // пoкaжeм eё тeкст
                    // $div_res.html('<div class="warn warn">' + $data['html'] + '</div>');
                    // $this.css({"border": "2px solid red"});

                    $($print_res_to).append('<div>произошла ошибка: ' + $data['html'] + '</div>');
                }
                // eсли всe прoшлo oк
                else
                {
                    // $div_res.html('<div class="warn good">' + $data['html'] + '</div>');
                    // $this.css({"border": "2px solid green"});

                    $($print_res_to).append($data['html']);
                }

                //$($modal_id).modal('hide');
                $('.modal').modal('hide');
            }
            ,
            // в случae нeудaчнoгo зaвeршeния зaпрoсa к сeрвeру
            error: function (xhr, ajaxOptions, thrownError) {
                // пoкaжeм oтвeт сeрвeрa
                alert(xhr.status + ' ' + thrownError); // и тeкст oшибки
            }

            // сoбытиe пoслe любoгo исхoдa
            // ,complete: function ($data) {
            // в любoм случae включим кнoпку oбрaтнo
            // $form.find('input[type="submit"]').prop('disabled', false);
            // }

        }); // ajax-


        return false;
    });

//
//    // новая версия 2007
//    $('body').on('click', '.delete_smena', function (event) {
//
//        $.each(this.attributes, function () {
//
//            if (this.specified) {
//
//                // console.log(this.name, this.value);
//                // $uri_query = $uri_query + '&' + this.name + '=' + this.value.replace(' ', '..')
//
//                if (this.name == 'data-target2') {
//                    var $id_modal = this.value;
//                    // console.log(this.value);
//                    $(this.value).modal('toggle');
//                    // $id_modal.modal('toggle');
//                } else {
//                    // console.log(2, this.value);
//                    if ($("input").is("#" + this.name)) {
//                        $("input#" + this.name).val(this.value);
//                    }
//                }
//            }
//        });
//        return false;
//        if ($(this).prop('data-target2').length()) {
//            // console.log($(this).prop('data-target2'));
//        }
//
//        $.each(this.attributes, function () {
//
//            if (this.specified) {
//
//                // console.log(this.name, this.value);
////                $uri_query = $uri_query + '&' + this.name + '=' + this.value.replace(' ', '..')
////
////                if (this.name == 'res_to') {
////                    $vars['resto'] = '#' + this.value + ' tbody';
////                    console.log($vars['resto']);
////                    // alert($res_to);
////                }
////
////                if (this.name == 'show_on_click') {
////                    $('#' + this.value).show('slow');
////                }
//
//            }
//
//        });
//        return false;
//    });



});
