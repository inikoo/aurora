<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 May 2018 at 16:21:00 CEST, 
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
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
                         'key'   => array('type' => 'key')
                     )
        );
        send_mailshot($data,$account,$editor,$db);
        break;


    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}


function send_mailshot($data,$account,$editor,$db) {


    $mailshot =  get_object('Email_Campaign',$data['key']);

    if($mailshot->get('Email Campaign State')=='Ready'){


       $mailshot->update_state('Sending');




        include_once 'utils/new_fork.php';




        list($fork_key, $msg) = new_fork(
            'au_send_mailshot', array('key'=>$mailshot->id), $account->get('Account Code'), $db
        );



        $response = array(
            'state' => 200,
            'msg'=> $msg,
            'fork_key'=> $fork_key,
            'update_metadata'=>$mailshot->get_update_metadata()


        );
        echo json_encode($response);
        exit;

    }else{

        $response = array(
            'state' => 400,
            'msg'=> 'Email Campaign state '.$mailshot->get('Email Campaign State')
        );
        echo json_encode($response);
        exit;

    }





}

?>
