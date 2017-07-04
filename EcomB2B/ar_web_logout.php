<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 July 2017 at 23:45:03 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/get_addressing.php';



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
        logout($db, $data);
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

function logout($db, $data) {


    $sql=sprintf('update `Website User Log Dimension` set `Website User Log Status`="Close",`Website User Log Logout Date`=%s where `Website User Log Key`=%d  ',
        prepare_mysql(gmdate('Y-m-d H:i:s')),
        $_SESSION['website_user_log_key']
        );

    $db->exec($sql);

    $sql=sprintf('delete from  `Website Auth Token Dimension` where `Website Auth Token Website User Log Key`=%d  ',
                 $_SESSION['website_user_log_key']
    );

    $db->exec($sql);

    setcookie('rmb', 'x:x', time() - 864000, '/'
    //,'',
    //true, // TLS-only
    //true  // http-only
    );


    session_regenerate_id();
    session_destroy();
    unset($_SESSION);


    $response = array(
        'state' => 200

    );

    echo json_encode($response);



}


?>
