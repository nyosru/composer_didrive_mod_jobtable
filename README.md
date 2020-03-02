# composer_didrive_mod_job_load_time_expectation
работа с загрузкой и обработкой времени ожидания




-------- блокировочный экран JS ----------
    $("body").append("<div id='body_block' class='body_block' >пару секунд вычисляем<br/><span id='body_block_465'></span></div>");






храним ключи в мемкеш
dolgnosti - [ 'data' => [ dolgnosti ], 'sort' => [ dolg отсортированные по полю сортировки ] ]
в twig функции > jobdesc__get_dolgnosti



после любого действия с графиком .. трём кеш 
-------- php ---------
    // удаляем запись кеша главного массива данных
    if (!empty($_REQUEST['delete_cash_start_date'])) {
        $e = \f\Cash::deleteKeyPoFilter(['all', 'jobdesc', 'date' . date('Y-m-01',strtotime($_REQUEST['delete_cash_start_date'])) ]);
        // \f\pa($e);
    }



работа с кешем

    // если нет переменной то не пишем кеш            
    $cash_var = 'jobdesc__money_mp_buh__' . \Nyos\mod\JobDesc::$mod_buh_pm . '_jm' . $user . '_date' . $date;
    // если не указали время жизни то оно бесконечно    
    // $cash_time_sec = 3600*24;
    // если есть таймер то показываем таймер выполнения
    $show_timer = rand(0, 9999);

        if (!empty($show_timer)) {
            \f\timer_start($timer_rand);
            $cash_var = 'jobdesc__money_minus_mod' . self::$mod_minus . '_datestart' . $date_start . '_datefinish' . $date_finish;
            $cash_time_sec = 0;
        }

        $return = [];

        if (!empty($cash_var)) {

            if (!empty($show_timer)) {
                echo '<br/>#' . __LINE__ . ' var ' . $cash_var;
                \f\timer_start($show_timer);
            }

            $return = \f\Cash::getVar($cash_var);
        }

        if (!empty($return)) {

            if (!empty($show_timer))
                echo '<br/>#' . __LINE__ . ' данные из кеша';

            if (isset($return[0]) && $return[0] == 'mc_skip') {
                if (!empty($show_timer))
                    echo '<br/>#' . __LINE__ . ' данных нет, возвращаем null';
                unset($return);
            }

        } else {

            $return = [];

            if (!empty($show_timer))
                echo '<br/>#' . __LINE__ . ' считаем данные и пишем в кеш';

            // тут супер код делающий $return старт

            // тут супер код делающий $return конец

            if (!empty($return)) {
                \f\Cash::setVar($cash_var, $return, ( $cash_time_sec ?? 0));
            } else {
                \f\Cash::setVar($cash_var, [0 => 'mc_skip'], ( $cash_time_sec ?? 0));
                if (!empty($show_timer))
                    echo '<br/>#' . __LINE__ . ' нет данных ничего не пишем в кеш';
            }
    
        }

        if (!empty($show_timer))
            echo '<br/>#'.__LINE__.' '.\f\timer_stop ($show_timer);

        return $return ?? [];