<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 June 2021 22:19 MYR , Kuala Lumpur Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/natural_language.php';
require_once 'utils/object_functions.php';
require_once 'utils/prepare_table.php';

/** @var User $user */
/** @var PDO $db */
/** @var Account $account */


if (!$user->can_view('fulfilment')) {
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
    case 'locations':
        locations(get_table_parameters(), $db, $user, $account);
        break;
    case 'dropshipping_customers':
        dropshipping_customers(get_table_parameters(), $db, $user, $account);
        break;
    case 'asset_keeping_customers':
        asset_keeping_customers(get_table_parameters(), $db, $user, $account);
        break;
    case 'fulfilment_parts':
        fulfilment_parts(get_table_parameters(), $db, $user, $account);
        break;
    case 'deliveries':
        deliveries(get_table_parameters(), $db, $user, $account);
        break;
    case 'fulfilment.delivery.assets':
        customer_delivery_assets(get_table_parameters(), $db, $user, $account);
        break;
    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
}

function customer_delivery_assets($_data, $db, $user, $account) {
    include_once 'prepare_table/fulfilment.assets.ptc.php';
    $table=new prepare_table_fulfilment_assets($db,$account,$user);
    echo $table->fetch($_data);
}

function locations($_data, $db, $user, $account) {
    include_once 'prepare_table/fulfilment.locations.ptc.php';
    $table=new prepare_table_fulfilment_locations($db,$account,$user);
    echo $table->fetch($_data);
}

function asset_keeping_customers($_data, $db, $user, $account) {
    include_once 'prepare_table/fulfilment.asset_keeping_customers.ptc.php';
    $table=new prepare_table_fulfilment_asset_keeping_customers($db,$account,$user);
    echo $table->fetch($_data);
}
function dropshipping_customers($_data, $db, $user,$account) {
    include_once 'prepare_table/fulfilment.dropshipping_customers.ptc.php';
    $table=new prepare_table_fulfilment_dropshipping_customers($db,$account,$user);
    echo $table->fetch($_data);
}

function fulfilment_parts($_data, $db, $user, $account) {
    include_once 'prepare_table/fulfilment.parts.ptc.php';
    $table=new prepare_table_fulfilment_parts($db,$account,$user);
    echo $table->fetch($_data);
}

function deliveries($_data, $db, $user,$account) {
    include_once 'prepare_table/fulfilment.deliveries.ptc.php';
    $table=new prepare_table_fulfilment_deliveries($db,$account,$user);
    echo $table->fetch($_data);
}
