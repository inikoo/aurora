<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 July 2017 at 17:00:26 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/date_functions.php';


if (!$user->can_view('stores')) {
    echo json_encode(
        array(
            'state' => 405,
            'resp'  => 'Forbidden'
        )
    );
    exit;
}


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
    case 'webpage_block':

        $data = prepare_values(
            $_REQUEST, array(
                         'code' => array('type' => 'string'),
                         'theme' => array('type' => 'string')
                     )
        );

        webpage_block($data, $db, $user,$smarty);
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


function webpage_block($data, $db, $user,$smarty) {

    include_once('conf/webpage_blocks.php');

    $blocks = get_webpage_blocks();

    $block = $blocks[$data['code']];


    $block_id=preg_replace('/\./','_',uniqid('web_block',true));

    $smarty->assign('key', $block_id);

    $smarty->assign('data', $block);
    $smarty->assign('block', $block);




    $response = array(
        'state' => 200,
        'button'=>$smarty->fetch($data['theme'].'/blk.control_label.'.$data['theme'].'.tpl'),
        'controls'=>$smarty->fetch($data['theme'].'/blk.control.'.$data['code'].'.'.$data['theme'].'.tpl'),
        'block'=>$smarty->fetch($data['theme'].'/blk.'.$data['code'].'.'.$data['theme'].'.tpl'),
        'type'=>$data['code'],
        'block_key'=>$block_id
    );
    echo json_encode($response);

}





?>
