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

            url: "/sites/yadom_admin/module/000.index/ajax.php",
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
                $modal_id = $(this.value);
            }
            
        });

        // alert('123');
        // return false;

        $.ajax({

            type: 'POST',
            url: "/sites/yadom_admin/module/000.index/ajax.php",
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

                $($modal_id).modal('hide');

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

            url: "/sites/yadom_admin/module/000.index/ajax.php",
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

});
