$(document).ready(function () { // вся мaгия пoслe зaгрузки стрaницы

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

//alert('123');
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


    /**
     * кликаем по кнопам плюс минус час
     */
    $('body').on('click', '.ajax_hour_action', function (event) {

        clearTdSummAllGraph();

        $th = $(this);

        $znak = $th.attr('type_action'); // - || +
        // console.log($znak); // - || +

        $hour_id = $th.attr('hour_id'); // - || +
        // console.log($hour_id); // - || +

        $textblock_id = $th.attr('block');
        // console.log($textblock_id);

        $s = $th.attr('s');
        // console.log($textblock_id);

        $cifra = parseFloat($('span#' + $textblock_id).text());

        console.log($('span#' + $textblock_id).text());
        console.log($cifra);

        if ($znak == '-') {
            $new_val = $cifra - 0.5;
        }
        //
        else if ($znak == '+') {
            $new_val = $cifra + 0.5;
        }

        $('span#' + $textblock_id).text($new_val);

        $.ajax({

            url: "/vendor/didrive_mod/items/1/ajax.php",
            data: "action=edit_dop_pole&item_id=" + $hour_id + "&dop_name=hour_on_job_hand&new_val=" + $new_val + "&id=" + $textblock_id + "&s=" + $s,
            cache: false,
            dataType: "json",
            type: "post",
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

                    $('span#' + $textblock_id).css('border-bottom', '2px solid green');
                    // $('span#' + $textblock_id).css('color', 'darkgreen');

                    // console.log($new_val);
                    // console.log( 1, $('span#' + $textblock_id).closest('.www').find('.now_price_hour').attr('kolvo_hour'));
                    $('span#' + $textblock_id).closest('.smena1').find('.hours_kolvo').val($new_val);
                    // console.log( 2, $('span#' + $textblock_id).closest('.www').find('.now_price_hour').attr('kolvo_hour'));

                    setTimeout(function () {
                        calculateSummAllGraph();
                    }, 100);

                    //$(document).one( calculateSummAllGraph );

                }


            }

        });

        return false;
    });
    // else {
    // alert(i + ': ' + $(elem).text());
    // }



    $('body').on('change', '.select_edit_item_dop2', function () {

        console.log(2);
        setTimeout(function () {
            calculateSummAllGraph();
        }, 100);
        console.log(3);

    });



    /*
     * считаем все суммы всех точек
     * @returns {undefined}
     */
    function calculateSummAllGraph( ) {

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

    function clearTdSummAllGraph( ) {

        $('body .show_summ_hour_day').each(function (i, elem) {

            $(elem).html('...');

        });

    }

    calculateSummAllGraph();

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
    $('body').on('click', '.put_var_in_modal', function (event) {

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
    // else {
    // alert(i + ': ' + $(elem).text());
    // }

















// alert('123');

    $('body').on('click', '.jobdesc__calc_full_ocenka_day', function (event) {

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

        $.ajax({

            url: "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
            data: "t=1" + $uri_query,
            cache: false,
            dataType: "json",
            type: "post",

            beforeSend: function () {

                $(resto).html('<img src="/img/load.gif" alt="" border=0 />');

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


                if (showid != 0) {
                    $(showid).show('slow');
                }

                if (hidethis == 1) {
                    $th.hide();
                }

                $string = '';
//                $.each($j, function (name, value) {
//                $string += '<br/>'+name + ': ' + value;
//                });

                if( $j.status == 'ok' ){
                
                $html = '';
                    
                    if( $j.ocenka == 5 ){
                        $html += '<div style="background-color:rgba(0,255,0,0.2);xcolor:red;padding:5px;">общая оценка: 5</div>';
                    }else{
                        $html += '<div style="background-color:rgba(255,255,0,0.2);xcolor:red;padding:5px;">общая оценка: 3</div>';
                        
                    }

                    if( $j.ocenka_time == 5 ){
                        $html += '<div style="background-color:rgba(0,255,0,0.2);xcolor:red;padding:5px;">Оценка времени ожидания: 5</div>';
                    }else if( $j.ocenka_time == 3 ){
                        $html += '<div style="background-color:rgba(255,255,0,0.2);xcolor:red;padding:5px;">Оценка  времени ожидания: 3</div>';
                    }
                    
                    if( $j.ocenka_oborot == 5 ){
                        $html += '<div style="background-color:rgba(0,255,0,0.2);xcolor:red;padding:5px;">Оценка оборота по точке: 5</div>';
                    }else if( $j.ocenka_oborot == 3 ){
                        $html += '<div style="background-color:rgba(255,255,0,0.2);xcolor:red;padding:5px;">Оценка оборота по точке: 3</div>';
                    }
                    
                    
                    // $(resto).html( $html + $j.txt );
                    $(resto).html( $html + '<pre>' + $j.txt + '</pre>' + '<pre>' + $j.time + '</pre>' );
                    
            }else{
                $(resto).html( '<div style="background-color:yellow;color:red;padding:5px;">' + $j.html + '</div>' + $string);
            }

                //alert(resto);

                // $($res_to).html($j.data);
                // $($vars['resto']).html($j.data);
                //$(resto).html($j.html);

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






});
