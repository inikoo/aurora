<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 July 2017 at 00:35:25 GMT+8, Cyberjaya, Malaysia
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
    case 'update_password':
        $data = prepare_values(
            $_REQUEST, array(
                         'pwd' => array('type' => 'string'),

                     )
        );
        update_password($db, $data, $editor);
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

function update_password($db, $data, $editor) {



    $website_user         = get_object('User', $_SESSION['website_user_key']);
    $website_user->editor = $editor;


    if ($website_user->id) {

        $website_user->update(
            array(
                'Website User Password'      => $data['pwd'],
                'Website User Password Hash' => password_hash($data['pwd'], PASSWORD_DEFAULT, array('cost' => 12)),


            )
        );


        echo json_encode(
            array(
                'state' => 200
            )
        );
        exit;

    } else {
        echo json_encode(
            array(
                'state'      => 400,
                'error_type' => 'User not found'
            )
        );
        exit;
    }


}


?>
