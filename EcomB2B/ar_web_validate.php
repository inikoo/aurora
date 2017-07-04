<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 330 June 2017 at 18:24:32 GMT+8, Cyberjaya, Malaysia
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
    case 'validate_email_registered':
        $data = prepare_values(
            $_REQUEST, array(
                         'email'     => array('type' => 'string'),
                         'website_key' => array('type' => 'key')
                     )
        );
        validate_email_registered($db, $data);
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

function validate_email_registered($db, $data) {


    $sql = sprintf(
        "SELECT `Website User Key` from `Website User Dimension` WHERE  `Website User Handle`=%s AND `Website User Website Key`=%d",
        prepare_mysql($data['email']), $data['website_key']

    );




    if ($result=$db->query($sql)) {
        if ($row = $result->fetch()) {
            echo "false";
    	}else{
            echo "true";
        }
    }else {
    	print_r($error_info=$db->errorInfo());
    	print "$sql\n";
    	exit;
    }






}


?>
