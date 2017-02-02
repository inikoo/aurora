<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 February 2017 at 00:47:49 GMT+8, CyberJaya, Malaysia
 Copyright (c) 2015, Inikoo

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



    case 'recreate_timesheets':
        $data = prepare_values(
            $_REQUEST, array(
                         'key' => array('type' => 'key'),
                         'scope'      => array('type' => 'string'),

                     )
        );
        calculate_sales($account, $db, $data, $editor);
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








function recreate_timesheets($account, $db, $data, $editor) {

print_r($data);
exit;

    require_once 'utils/new_fork.php';

    $data['editor'] = $editor;

    list($fork_key, $msg) = new_fork(
        'au_recreate_timesheets', $data, $account->get('Account Code'), $db
    );


    $response = array(
        'state'    => 200,
        'fork_key' => $fork_key,
        'msg'      => $msg

    );
    echo json_encode($response);


}



?>
