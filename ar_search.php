<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 December 2015 at 18:35:53 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/




require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/text_functions.php';
include_once 'search_functions.php';


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
    case 'search':



        $data = prepare_values(
            $_REQUEST, array(
                         'query' => array('type' => 'string'),
                         'state' => array('type' => 'json array')
                     )
        );





        if ($user->get('User Type') == 'Agent') {
            agent_search($db, $account, $user, $data);
        } else {
            if ($data['state']['module'] == 'customers' or $data['state']['module'] == 'customers_server') {
                if ($data['state']['current_store']) {
                    $data['scope']     = 'store';
                    $data['scope_key'] = $data['state']['current_store'];
                } else {
                    $data['scope'] = 'stores';
                }
                search_customers( $data);
            } elseif ($data['state']['module'] == 'orders') {
                if ($data['state']['current_store']) {
                    $data['scope']     = 'store';
                    $data['scope_key'] = $data['state']['current_store'];
                } else {
                    $data['scope'] = 'stores';
                }
                search_orders($db, $account,$user, $data);
            } elseif ($data['state']['module'] == 'products') {
                if ($data['state']['current_store']) {
                    $data['scope']     = 'store';
                    $data['scope_key'] = $data['state']['current_store'];
                } else {
                    $data['scope'] = 'stores';
                }

                search_products($db, $account, $user,$data);



            } elseif ($data['state']['module'] == 'websites') {



                if ($data['state']['current_website']) {
                    $data['scope']     = 'website';
                    $data['scope_key'] = $data['state']['current_website'];
                } else {
                    $data['scope'] = 'websites';
                }

                search_webpages($db, $account, $user, $data);

            } elseif ($data['state']['module'] == 'products_server') {

                $data['scope'] = 'stores';

                search_products($db, $account, $user,$data);
            } elseif ($data['state']['module'] == 'inventory') {
                if ($data['state']['current_warehouse']) {
                    $data['scope']     = 'warehouse';
                    $data['scope_key'] = $data['state']['current_warehouse'];
                } else {
                    $data['scope'] = 'warehouses';
                }
                search_inventory($db, $account, $user,$data);
            } elseif ($data['state']['module'] == 'hr') {
                search_hr($db, $account, $user, $data);

            } elseif ($data['state']['module'] == 'suppliers') {
                search_suppliers($db, $account, $user, $data);

            }elseif ($data['state']['module'] == 'production') {
                search_production($db, $account, $user, $data);

            } elseif ($data['state']['module'] == 'delivery_notes') {
                if ($data['state']['current_store']) {
                    $data['scope']     = 'store';
                    $data['scope_key'] = $data['state']['current_store'];
                } else {
                    $data['scope'] = 'stores';
                }
                search_delivery_notes($db, $account, $user, $data);
            } elseif ($data['state']['module'] == 'delivery_notes_server') {

                $data['scope'] = 'stores';

                search_delivery_notes($db, $account, $user, $data);
            } elseif ($data['state']['module'] == 'orders_server') {
                $data['scope'] = 'stores';
                search_orders($db, $account, $user,$data);
            } elseif ($data['state']['module'] == 'accounting_server') {
                if (in_array($data['state']['section'], array('deleted_invoice','deleted_invoices_server','invoices','invoice','category'))) {


                    $data['scope'] = 'stores';
                    search_invoices($db, $account, $user, $data);
                } else {
                    $data['scope'] = 'stores';
                    search_payments($db, $account,$user, $data);
                }
            } elseif ($data['state']['module'] == 'accounting') {

                $data['scope']     = 'store';
                $data['scope_key'] = $data['state']['current_store'];
                if (in_array($data['state']['section'], array('deleted_invoice','deleted_invoices','invoices','invoice','category'))) {
                    search_invoices($db, $account, $user, $data);
                } else {
                    search_payments($db, $account,$user, $data);
                }



            } elseif ($data['state']['module'] == 'warehouses') {
                if ($data['state']['current_warehouse']) {
                    $data['scope']     = 'warehouse';
                    $data['scope_key'] = $data['state']['current_warehouse'];
                } else {
                    $data['scope'] = 'warehouses';
                }
                search_locations($db, $account, $user,$data);
            }
        }
        break;


    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tab not found '.$tab
        );
        echo json_encode($response);
        exit;
        break;
}




