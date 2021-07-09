<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 July 2017 at 23:45:03 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';




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
    case 'logout':
        $data = prepare_values(
            $_REQUEST, array(
                         'webpage_key' => array('type' => 'key')
                     )
        );
        logout($db);
        break;


    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;

}

function logout($db) {


    $sql="update `Website User Log Dimension` set `Website User Log Status`='Close',`Website User Log Logout Date`=? where `Website User Log Key`=? ";

    $db->prepare($sql)->execute(
        array(
            gmdate('Y-m-d H:i:s'),
            $_SESSION['UTK']['WUL']
        )
    );

    setcookie("UTK", "", time() - 10000);
    session_regenerate_id();
    session_destroy();
    unset($_SESSION);


    $response = array(
        'state' => 200

    );

    echo json_encode($response);



}


