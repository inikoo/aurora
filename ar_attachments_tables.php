<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Crated: 4 December 2015 at 21:26:14 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';

/** @var \User $user */
/** @var PDO $db */
/** @var \Account $account */

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
    case 'attachments':
        attachments(get_table_parameters(), $db, $user,$account);
        break;

    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);

}


function attachments($_data, $db, $user,$account) {
    include_once 'prepare_table/attachments.ptc.php';
    $table=new prepare_table_attachments($db,$account,$user);
    echo $table->fetch($_data);
}


