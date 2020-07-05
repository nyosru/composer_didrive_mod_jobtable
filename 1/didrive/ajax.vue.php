<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define('IN_NYOS_PROJECT', true);

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

//\f\timer::start();

require($_SERVER['DOCUMENT_ROOT'] . '/all/ajax.start.php');

//            'spec' => \Nyos\mod\JobDesc::getListJobsPeriodSpec($db, $date_start, $date_finish),
//            'norm' => \Nyos\mod\JobDesc::getListJobsPeriod($db, $date_start, $date_finish),

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'get_jobs_norm') {


    if (empty($_REQUEST['date_start']))
        \f\end2('нет даты старта', false, [ 'req' => $_REQUEST ] );

    if (!empty($_REQUEST['date_finish'])) {
        $date_finish = date('Y-m-d', strtotime($_REQUEST['date_finish']));
    } elseif (!empty($_REQUEST['date_start']) && empty($_REQUEST['date_finish'])) {
        $date_finish = date('Y-m-d', strtotime(date('Y-m-01', strtotime($_REQUEST['date_start'])) . ' +1 month -1 day'));
    }

    if (!empty($_REQUEST['date_start']) && !empty($date_finish)) {
        $res =  \Nyos\mod\JobDesc::getListJobsPeriod($db, date('Y-m-d', strtotime($_REQUEST['date_start'])), $date_finish);

        \f\end2('ok', true, $res);
    }
}

\f\end2('что то пошло не так #' . __LINE__, false);
