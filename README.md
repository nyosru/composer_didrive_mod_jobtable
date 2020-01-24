# composer_didrive_mod_job_load_time_expectation
работа с загрузкой и обработкой времени ожидания

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
