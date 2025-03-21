<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 May 2018 at 16:21:00 CEST, Mijas Costa Spain
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'utils/ar_common.php';
require_once 'utils/object_functions.php';
require_once 'utils/natural_language.php';
require_once 'utils/parse_natural_language.php';


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


$tipo = $_REQUEST['tipo'];

switch ($tipo) {

    case 'send_mailshot':
        $data = prepare_values(
            $_REQUEST, array(
                         'key' => array('type' => 'key')
                     )
        );
        send_mailshot($data, $account, $editor, $db);
        break;
    case 'set_schedule_mailshot':
        $data = prepare_values(
            $_REQUEST, array(
                         'key' => array('type' => 'key'),
                         'date' => array('type' => 'string'),
                         'time' => array('type' => 'string')
                     )
        );
        set_schedule_mailshot($data, $account, $editor, $db);
        break;
    case 'resume_mailshot':
        $data = prepare_values(
            $_REQUEST, array(
                         'key' => array('type' => 'key')
                     )
        );
        resume_mailshot($data, $account, $editor, $db);
        break;
    case 'stop_mailshot':
        $data = prepare_values(
            $_REQUEST, array(
                         'key' => array('type' => 'key')
                     )
        );
        stop_mailshot($data, $account, $editor, $db);
        break;
    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;

}


function set_schedule_mailshot($data, $account, $editor, $db) {


    $mailshot = get_object('Email_Campaign', $data['key']);

    $store=get_object('Store',$mailshot->get('Email Campaign Store Key'));


    if ($mailshot->get('Email Campaign State') == 'Ready') {

        $_scheduled_date=$data['date'].' '.$data['time'].':00';


        $given = new DateTime($_scheduled_date, new DateTimeZone($store->get('Store Timezone')));
        $given->setTimezone(new DateTimeZone("UTC"));
        $scheduled_date = $given->format("Y-m-d H:i:s");





        $mailshot->update_state('Scheduled',['Email Campaign Scheduled Date'=>$scheduled_date]);








        $response = array(
            'state'           => 200,
            'update_metadata' => $mailshot->get_update_metadata()


        );
    } else {

        $response = array(
            'state' => 400,
            'msg'   => 'Email Campaign State: '.$mailshot->get('Email Campaign State')
        );
    }
    echo json_encode($response);
    exit;
}

function send_mailshot($data, $account, $editor, $db) {


    $mailshot = get_object('Email_Campaign', $data['key']);


    if ($mailshot->get('Email Campaign State') == 'Ready') {


        $mailshot->update_state('Sending');


        include_once 'utils/new_fork.php';


        new_housekeeping_fork(
            'au_send_mailshots', array(
            'type'         => 'send_mailshot',
            'mailshot_key' => $mailshot->id,
            'editor'      => $editor

        ), $account->get('Account Code')
        );



        $response = array(
            'state'           => 200,
            'update_metadata' => $mailshot->get_update_metadata()


        );
        echo json_encode($response);
        exit;

    } else {

        $response = array(
            'state' => 400,
            'msg'   => 'Email Campaign State: '.$mailshot->get('Email Campaign State')
        );
        echo json_encode($response);
        exit;

    }
}


function resume_mailshot($data, $account, $editor, $db) {


    $mailshot = get_object('Email_Campaign', $data['key']);

    if ($mailshot->get('Email Campaign State') == 'Stopped') {


        $mailshot->update_state('Sending');


        include_once 'utils/new_fork.php';


        new_housekeeping_fork(
            'au_send_mailshots', array(
            'type'         => 'resume_mailshot',
            'mailshot_key' => $mailshot->id,

        ), $account->get('Account Code')
        );


        $response = array(
            'state'           => 200,
            // 'msg'=> $msg,
            'update_metadata' => $mailshot->get_update_metadata()


        );
        echo json_encode($response);
        exit;

    } else {

        $response = array(
            'state' => 400,
            'msg'   => 'Email Campaign state '.$mailshot->get('Email Campaign State')
        );
        echo json_encode($response);
        exit;

    }
}


function stop_mailshot($data, $account, $editor, $db) {


    $mailshot = get_object('Email_Campaign', $data['key']);


    if($mailshot->get('Email Campaign State')=='Scheduled'){
        $mailshot->update_state('Ready');

    }else{
        $mailshot->update_state('Stopped');

    }

    $response = array(
        'state'           => 200,
        // 'msg'=> $msg,
        'update_metadata' => $mailshot->get_update_metadata()


    );
    echo json_encode($response);
    exit;


}






