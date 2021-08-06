<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  02 February 2020  23:42::55  +0800 Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/

require_once 'utils/ar_common.php';


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


$_data = prepare_values(
    $_REQUEST, array(
                 'download_key' => array('type' => 'key'),
             )
);

$sql = "update `Download Dimension` set `Download State`='Cancelled' where `Download Key`=? and `Download State` in ('Created','Error','In Process')   ";

$db->prepare($sql)->execute(
    array(
        $_data['download_key']
    )
);

$response = array(
    'state' => 200,
);
echo json_encode($response);


