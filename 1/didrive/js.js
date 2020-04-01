$(document).ready(function () { // вся мaгия пoслe зaгрузки стрaницы


    function ocenka_clear($sp, $date, $clear_to_now = '') {


// если не пусто то трём все даты начиная с указанной
        if ($clear_to_now != '') {

            $('#a_price_' + $sp + '_' + $date).html('<div class=\'bg-warning\' style=\'padding:5px;\' >Значение изменено</div>');
            console.log('стираем все даты, начиная с указанной', $sp, $date);

        }
// если пусто то трём дату указанную
        else {

            $('#a_price_' + $sp + '_' + $date).html('<div class=\'bg-warning\' style=\'padding:5px;\' >Значение изменено</div>');
            console.log('стираем 1 дату', $sp, $date);

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

            url: "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
            data: "action=put_workman_on_sp&" + data + '&sp_from=' + sp_from + '&dolgnost_from=' + dolgn_from,
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

        console.log('delete_workman_from_sp( ' + $sp + ', ' + $workman + ', ' + $wm_s + ' )');

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

        console.log('set_end_now_jobs( ' + $work_id + ', ' + $wm_s + ', ' + $date_end + ' )');

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

        console.log('cancel_end_now_jobs( ' + $work_id + ', ' + $wm_s + ', ' + $date_end + ' )');

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
            console.log(i + ': ' + $(elem).text() + ': ' + $(elem).value());
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
                    console.log($id + ' / ' + $hours + ' / ' + $price);

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
                    console.log('пропускаем');
                }
            }

        });
    }

// считаем сумму каждой смены
//    setTimeout(function () {
//        calcSummMoneySmena();
//    }, 2000);










    // кликаем по кнопам плюс минус час

    // $('body').on('click', '.ajax_hour_action', $.debounce(300, jobdesc__plus_minus_hour) );

    $('body').on('click', '.ajax_hour_action', function () {

        var in_date = '';
        var in_sp = '';
//        clearTdSummAllGraph();
        var uri_query = '';

        $.each(this.attributes, function () {
            if (this.specified) {
                //console.log(1, this.name, this.value);
                uri_query = uri_query + '&ajax_' + this.name + '=' + this.value;

                if (this.name == 'date') {
                    in_date = this.value;
                } else if (this.name == 'sp') {
                    in_sp = this.value;
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

        console.log($('span#' + $textblock_id).text());
        console.log($cifra);

        if ($znak == '-') {
            var $new_val = +$cifra - +0.5;
        }
//
        else if ($znak == '+') {
            var $new_val = +$cifra + +0.5;
        }

        $('span#' + $textblock_id).text($new_val);

        $.ajax({

            url: "/vendor/didrive_mod/items/1/ajax.php",
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

                    ocenka_clear(in_sp, in_date);

                    $('span#' + $textblock_id).css('border-bottom', '2px solid green');
                    // $('span#' + $textblock_id).css('color', 'darkgreen');

                    // console.log($new_val);
                    // console.log( 1, $('span#' + $textblock_id).closest('.www').find('.now_price_hour').attr('kolvo_hour'));
                    $('span#' + $textblock_id).closest('.smena1').find('.hours_kolvo').val($new_val);
                    // console.log( 2, $('span#' + $textblock_id).closest('.www').find('.now_price_hour').attr('kolvo_hour'));

                    // $.debounce( 1000, calcSummMoneySmena2 );
                    calcSummMoneySmena2($textblock_id);

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

        return false;
    });




    $('body').on('change', '.select_edit_item_dop2', function () {

        console.log(2);
        setTimeout(function () {
            calculateSummAllGraph();
        }, 100);
        console.log(3);
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
                    console.log($vars['resto']);
                    // alert($res_to);
                }

                if (this.name == 'show_on_click') {
                    $('#' + this.value).show('slow');
                }

            }

        });
        console.log($vars['resto']);
        console.log($uri_query);
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
        console.log('delete_workman_from_sp');
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
        console.log('set_end_now_jobs');
        $need_answer = '';
        $wm_s = '';
        $date_end = '';
// set_end_now_jobs( $now_job_id, $s, $res_to ) {

        $.each(this.attributes, function () {
            console.log(this.name, this.value);

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
        console.log('cancel_end_now_jobs');
        $need_answer = '';
        $wm_s = '';
        $date_end = '';
// set_end_now_jobs( $now_job_id, $s, $res_to ) {

        $.each(this.attributes, function () {
            console.log(this.name, this.value);

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


    $('body').on('click', '.act_smena', function (event) {

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



    $('body').on('submit', '#add_new_smena', function (event) {

        event.preventDefault();
        // создание массива объектов из данных формы
        var data1 = $(this).serializeArray();
        // переберём каждое значение массива и выведем его в формате имяЭлемента=значение в консоль
        console.log('Входящие данные');
        $.each(data1, function () {

            console.log(this.name + '=' + this.value);
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


    $('body').on('submit', '#goto_other_sp', function (event) {

        event.preventDefault();
        // создание массива объектов из данных формы
        var data1 = $(this).serializeArray();
        // переберём каждое значение массива и выведем его в формате имяЭлемента=значение в консоль
        console.log('Входящие данные');
        $.each(data1, function () {

            console.log(this.name + '=' + this.value);
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
    $('body').on('submit', '#add_minus', function (event) {

        event.preventDefault();
        // создание массива объектов из данных формы
        var data1 = $(this).serializeArray();
        // переберём каждое значение массива и выведем его в формате имяЭлемента=значение в консоль
        console.log('Входящие данные');
        $.each(data1, function () {

            console.log(this.name + '=' + this.value);
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
    $('body').on('click', '.put_var_in_modal2', function (event) {

        $.each(this.attributes, function () {

            if (this.specified) {

                console.log(this.name, this.value);
                // $uri_query = $uri_query + '&' + this.name + '=' + this.value.replace(' ', '..')

                if (this.name == 'data-target2') {
                    var $id_modal = this.value;
                    console.log(this.value);
                    $(this.value).modal('toggle');
                    // $id_modal.modal('toggle');
                } else {
                    console.log(2, this.value);
                    if ($("input").is("#" + this.name)) {
                        $("input#" + this.name).val(this.value);
                    }
                }
            }
        });

        return false;

        if ($(this).prop('data-target2').length()) {
            console.log($(this).prop('data-target2'));
        }

        $.each(this.attributes, function () {

            if (this.specified) {

                console.log(this.name, this.value);
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
    $('body').on('click', '.delete_smena', function (event) {

        $.each(this.attributes, function () {

            if (this.specified) {

                console.log(this.name, this.value);
                // $uri_query = $uri_query + '&' + this.name + '=' + this.value.replace(' ', '..')

                if (this.name == 'data-target2') {
                    var $id_modal = this.value;
                    console.log(this.value);
                    $(this.value).modal('toggle');
                    // $id_modal.modal('toggle');
                } else {
                    console.log(2, this.value);
                    if ($("input").is("#" + this.name)) {
                        $("input#" + this.name).val(this.value);
                    }
                }
            }
        });
        return false;
        if ($(this).prop('data-target2').length()) {
            console.log($(this).prop('data-target2'));
        }

        $.each(this.attributes, function () {

            if (this.specified) {

                console.log(this.name, this.value);
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
                    console.log($vars['resto']);
                    // alert($res_to);
                }

                if (this.name == 'show_on_click') {
                    $('#' + this.value).show('slow');
                }

            }

        });
        console.log($vars['resto']);
        console.log($uri_query);
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
                    console.log($vars['resto']);
                    // alert($res_to);
                }

                if (this.name == 'show_on_click') {
                    $('#' + this.value).show('slow');
                }

            }

        });
        console.log($vars['resto']);
        console.log($uri_query);
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
        $.each(this.attributes, function () {

            if (this.specified) {

                if (this.name.indexOf("forajax_") != -1) {
                    $uri_query = $uri_query + '&' + this.name.replace('forajax_', '') + '=' + this.value;
                    console.log(this.name, this.value);
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

        });
        if (answer != 0) {

            if (!confirm(answer)) {
                return false;
            }

        }

        var $th = $(this);
        $.ajax({

            url: "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
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

        console.log($uri_query);

        if (answer != 0) {

            if (!confirm(answer)) {
                return false;
            }

        }

        $.ajax({

            url: "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
            data: "action=bonus_record_month&date=" + date + "&sp=" + sp,
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
                    console.log(this.name, this.value);
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



});
