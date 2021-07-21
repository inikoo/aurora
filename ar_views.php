<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 August 2015 23:49:27 GMT+8 Singapore

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/object_functions.php';
require_once 'helpers/view/get_showcases.php';
require_once 'helpers/view/get_navigation.php';
require_once 'helpers/view/get_breadcrumbs.php';


$tipo = $_REQUEST['tipo'];


switch ($tipo) {
    case 'views':
        get_view($db, $smarty, $user, $account, $modules, $redis);
        break;
    case 'widget_details':
        get_widget_details($redis, $db, $smarty, $user, $account, $modules);
        break;
    case 'tab':
        $data     = prepare_values(
            $_REQUEST, array(
                         'tab'    => array('type' => 'string'),
                         'subtab' => array('type' => 'string'),
                         'state'  => array('type' => 'json array'),
                     )
        );
        $response = array(
            'tab' => get_tab(
                $redis, $db, $smarty, $user, $account, $data['tab'], $data['subtab'], $data['state']
            )
        );

        echo json_encode($response);
        break;


    default:
        $response = array(
            'state' => 404,
            'resp'  => 'Operation not found 2'
        );
        echo json_encode($response);

}

function get_widget_details($redis, $db, $smarty, $user, $account, $modules) {

    $data = prepare_values(
        $_REQUEST, array(
                     'widget'   => array('type' => 'string'),
                     'metadata' => array(
                         'type'     => 'json array',
                         'optional' => true
                     ),

                 )
    );


    $state = $data['metadata'];


    $html     = get_tab(
        $redis, $db, $smarty, $user, $account, $data['widget'], '', $state, $metadata = false
    );
    $response = array(
        'state'          => 200,
        'widget_details' => $html,
    );
    echo json_encode($response);

}

/**
 * @param $db      \PDO
 * @param $smarty  \Smarty
 * @param $user    \User
 * @param $account \Account
 * @param $modules
 * @param $redis   \Redis
 *
 */
function get_view($db, $smarty, $user, $account, $modules, $redis) {


    require_once 'utils/parse_request.php';

    $data = prepare_values(
        $_REQUEST, array(
                     'request'   => array('type' => 'string'),
                     'old_state' => array('type' => 'json array'),
                     'tab'       => array(
                         'type'     => 'string',
                         'optional' => true
                     ),
                     'subtab'    => array(
                         'type'     => 'string',
                         'optional' => true
                     ),
                     'otf'       => array(
                         'type'     => 'string',
                         'optional' => true
                     ),
                     'metadata'  => array(
                         'type'     => 'json array',
                         'optional' => true
                     ),

                 )
    );


    $old_weblocation = (isset($data['old_state']['module']) ? $data['old_state']['module'] : '').'|'.(isset($data['old_state']['section']) ? $data['old_state']['section'] : '');
    $redis->zadd('_IU'.$account->get('Code'), gmdate('U'), $user->id);

    //if (isset($data['metadata']['help']) and $data['metadata']['help']) {
    //    get_help($data, $modules, $db);
    //    return;
    //}


    if (isset($data['metadata']['reload']) and $data['metadata']['reload']) {
        $reload = true;
    } else {
        $reload = false;
    }


    if (!empty($data['tab'])) {
        $requested_tab = $data['tab'];
    } else {
        $requested_tab = '';
    }

    $state = parse_request($data, $db, $modules, $user);


    $state['current_website']    = (!empty($_SESSION['current_website']) ? $_SESSION['current_website'] : '');
    $state['current_store']      = (!empty($_SESSION['current_store']) ? $_SESSION['current_store'] : '');
    $state['current_warehouse']  = (!empty($_SESSION['current_warehouse']) ? $_SESSION['current_warehouse'] : '');
    $state['current_production'] = (!empty($_SESSION['current_production']) ? $_SESSION['current_production'] : '');


    $store      = '';
    $website    = '';
    $warehouse  = '';
    $production = '';


    if (!empty($state['store_key'])) {
        $store = get_object('Store', $state['store_key']);
    }

    switch ($state['parent']) {

        case 'store':


            if ($state['parent_key'] != '') {
                $_parent = get_object('Store', $state['parent_key']);
            } else {
                if ($state['object'] == 'product') {
                    $_object             = get_object($state['object'], $state['key']);
                    $_parent             = get_object('Store', $_object->get('Product Store Key'));
                    $state['parent_key'] = $_parent->id;
                } elseif ($state['object'] == 'customer') {
                    $_object             = get_object($state['object'], $state['key']);
                    $_parent             = get_object('Store', $_object->get('Customer Store Key'));
                    $state['parent_key'] = $_parent->id;

                } else {
                    print $state['object'];

                }

            }

            $state['current_store'] = $_parent->id;
            $store                  = $_parent;

            if ($state['object'] == 'website' and $state['key'] == '') {
                $state['key'] = $store->get('Store Website Key');
            }


            break;
        case 'customer':
            $_parent = get_object($state['parent'], $state['parent_key']);
            $store   = get_object('Store', $_parent->get('Customer Store Key'));

            break;
        case 'part':
            include_once 'class.Part.php';
            include_once 'class.Warehouse.php';

            $_parent   = new Part($state['parent_key']);
            $warehouse = new Warehouse($state['current_warehouse']);

            break;
        case 'website':


            $_parent = get_object('Website', $state['parent_key']);
            $website = $_parent;


            $store                    = get_object('Store', $_parent->get('Website Store Key'));
            $state['current_store']   = $store->id;
            $state['current_website'] = $_parent->id;

            break;
        case 'page':
            $_parent                  = get_object('Webpage', $state['parent_key']);
            $website                  = get_object('Website', $_parent->get('Webpage Website Key'));
            $state['current_website'] = $website->id;
            $state['current_store']   = $website->get('Website Store Key');

            break;
        case 'webpage_type':
            $_parent                  = get_object('Webpage_Type', $state['parent_key']);
            $website                  = get_object('Website', $_parent->get('Webpage Type Website Key'));
            $state['current_website'] = $website->id;
            $state['current_store']   = $website->get('Website Store Key');

            if (!$_parent->id) {
                $state = array(
                    'old_state'  => $state,
                    'module'     => 'utils',
                    'section'    => 'not_found',
                    'tab'        => 'not_found',
                    'subtab'     => '',
                    'parent'     => $state['object'],
                    'parent_key' => '',
                    'object'     => '',
                    'store'      => '',
                    'website'    => '',
                    'warehouse'  => '',
                    'key'        => '',
                    'request'    => $state['request']
                );
            }


            break;
        case 'warehouse':
            $_parent                    = get_object('Warehouse', $state['parent_key']);
            $warehouse                  = $_parent;
            $state['current_warehouse'] = $_parent->id;

            break;
        case 'warehouse_area':
            $_parent = get_object($state['parent'], $state['parent_key']);

            $warehouse                  = get_object('Warehouse', $_parent->get('Warehouse Key'));
            $state['current_warehouse'] = $warehouse->id;

            break;
        case 'category':

            $_parent = get_object('Category', $state['parent_key']);

            if ($_parent->get('Category Scope') == 'Product' or $_parent->get('Category Scope') == 'Customer') {
                $store = get_object('Store', $_parent->get('Store Key'));

            }

            break;
        case 'day':
        case 'month':
        case 'week':
            $_parent = '';
            break;
        case 'order':
            $_parent = get_object($state['parent'], $state['parent_key']);
            $store   = get_object('store', $_parent->get('Store Key'));

            break;
        case 'timeseries':
            $_parent = get_object($state['parent'], $state['parent_key']);

            if ($_parent->get('Timeseries Parent') == 'Warehouse') {
                $warehouse = get_object('Warehouse', $_parent->get('Timeseries Parent Key'));

            }
            break;
        case 'account':
            //print_r($state);
            $_parent = get_object($state['parent'], $state['parent_key']);


            if (in_array(
                $state['module'], [
                                    'customers_server',
                                    'mailroom_server',
                                    'products_server',
                                    'offers_server',
                                    'websites_server',
                                    'orders_server'

                                ]

            )) {
                $state['current_store'] = '';
            }


            if (in_array($state['module'], array('warehouses_server'))) {
                $state['current_warehouse'] = '';
            }
            if (in_array($state['module'], array('production_server'))) {
                $state['current_production'] = '';

            }
            break;
        case 'campaign':

            if (is_numeric($state['parent_key'])) {
                $_parent = get_object($state['parent'], $state['parent_key']);

            } else {


                $_parent = get_object('campaign_code-store_key', strtoupper($state['parent_key']).'|'.$state['extra']);

                $state['parent_key'] = $_parent->id;
            }


            break;


        case 'supplier':

            $_parent = get_object($state['parent'], $state['parent_key']);


            if ($_parent->get('Supplier Production') == 'Yes') {
                if ($state['module'] == 'suppliers') {


                    if ($state['section'] != 'delivery' and $state['section'] != 'supplier_part' and $state['section'] != 'order') {
                        $state['request'] = preg_replace('/supplier\//', 'production/', $state['request']);
                        $state['module']  = 'production';
                        $state['section'] = 'dashboard';
                        $state['tab']     = 'production.dashboard';
                        $state['subtab']  = 'production.dashboard';
                        $state['object']  = 'supplier_production';
                        $_object          = get_object($state['object'], $state['key']);
                    }


                }


            }

            break;


        default:
            $_parent = get_object($state['parent'], $state['parent_key']);


    }


    $state['_parent'] = $_parent;


    if ($state['object'] != '') {


        if ($state['object'] == 'campaign' and !is_numeric($state['key'])) {

            $_object = get_object('campaign_code-store_key', $state['key'].'|'.$state['parent_key']);

            $state['key'] = $_object->id;
        }


        if (!isset($_object)) {

            $_object = get_object($state['object'], $state['key']);
        }


        if (is_numeric($_object->get('Store Key'))) {


            $store                  = get_object('Store', $_object->get('Store Key'));
            $state['current_store'] = $store->id;
        }


        if (is_numeric($_object->get('Warehouse Key'))) {
            $warehouse                  = get_object('Warehouse', $_object->get('Warehouse Key'));
            $state['current_warehouse'] = $warehouse->id;
        } elseif ($state['object'] == 'location') {
            $warehouse                  = get_object('Warehouse', $_parent->get('Warehouse Key'));
            $state['current_warehouse'] = $warehouse->id;
        }

        if (is_numeric($_object->get('Website Key'))) {


            $website                  = get_object('Website', $_object->get('Website Key'));
            $state['current_website'] = $website->id;
            $state['current_store']   = $website->get('Website Store Key');
        }


        if ($state['object'] == 'product' and $state['tab'] != 'product.new') {


            if ($state['parent'] == 'store' and $_object->get('Store Key') != $state['parent_key']) {


                $state = array(
                    'old_state'  => $state,
                    'module'     => 'utils',
                    'section'    => 'not_found',
                    'tab'        => 'not_found',
                    'subtab'     => '',
                    'parent'     => $state['object'],
                    'parent_key' => '',
                    'object'     => '',
                    'store'      => '',
                    'website'    => '',
                    'warehouse'  => '',
                    'key'        => '',
                    'request'    => $state['request']
                );

            }
        }

        if ($state['object'] == 'customer' and $state['tab'] != 'customer.new' and $_object->id) {


            if ($state['parent'] == 'store' and $state['parent_key'] == '') {

                include_once 'class.Store.php';
                $_parent                = new Store($_object->get('Store Key'));
                $state['parent_key']    = $_object->get('Store Key');
                $state['current_store'] = $_parent->id;
                $store                  = $_parent;
                $state['_parent']       = $_parent;
            }

            if ($state['parent'] == 'store' and $_object->get('Store Key') != $state['parent_key']) {

                $state = array(
                    'old_state'  => $state,
                    'module'     => 'utils',
                    'section'    => 'not_found',
                    'tab'        => 'not_found',
                    'subtab'     => '',
                    'parent'     => $state['object'],
                    'parent_key' => '',
                    'object'     => '',
                    'store'      => '',
                    'website'    => '',
                    'warehouse'  => '',
                    'key'        => '',
                    'request'    => $state['request']
                );

            }
        }


        if ($state['object'] == 'website' and $state['tab'] != 'website.new') {


            $store = get_object('Store', $_object->get('Website Store Key'));


            $state['current_website'] = $state['key'];
            $state['current_store']   = $store->id;
            if ($state['parent'] == 'store' and !$state['_parent']->get('Store Website Key')) {


                $state = array(
                    'old_state'  => $state,
                    'module'     => 'products',
                    'section'    => 'no_website',
                    'tab'        => 'no_website',
                    'subtab'     => '',
                    'parent'     => $state['parent'],
                    'parent_key' => $state['parent_key'],
                    'object'     => '',
                    'store'      => $state['_parent'],
                    'website'    => $website,
                    'warehouse'  => $warehouse,
                    'key'        => '',
                    'request'    => $state['request']
                );


            }


        }

        /*

                if ($state['module'] != 'production') {

                    if ($state['object'] == 'supplier' and $_object->get('Supplier Production') == 'Yes') {


                        $state['request'] = 'production/'.$_object->id;
                        $state['module']  = 'production';
                        $state['section'] = 'dashboard';
                        $state['tab']     = 'production.dashboard';
                        $state['subtab']  = '';
                        $state['object']  = 'supplier_production';
                        $_object          = get_object($state['object'], $state['key']);


                    }


                    //print_r($state);

                    if ($state['object'] == 'purchase_order' and $_object->get('Purchase Order Type') == 'Production') {


                        $state['request'] = 'production/'.$_object->get('Purchase Order Parent Key').'/order/'.$_object->id;

                        $state['module']     = 'production';
                        $state['section']    = 'order';
                        $state['tab']        = 'supplier.order.items';
                        $state['subtab']     = '';
                        $state['parent']     = 'supplier_production';
                        $state['parent_key'] = $_object->get('Purchase Order Parent Key');
                        $_parent             = get_object($state['parent'], $state['parent_key']);
                        $state['_parent']    = $_parent;
                        $state['section']    = 'order';


                    }


                    if ($state['object'] == 'supplierdelivery' and $_object->get('Supplier Delivery Type') == 'Production') {


                        $state['request']    = 'production/'.$_object->get('Supplier Delivery Parent Key').'/delivery/'.$_object->id;
                        $state['module']     = 'production';
                        $state['parent']     = 'supplier_production';
                        $state['parent_key'] = $_object->get('Supplier Delivery Parent Key');
                        $_parent             = get_object($state['parent'], $state['parent_key']);
                        $state['_parent']    = $_parent;
                        $state['section']    = 'delivery';


                    }


                    if ($state['object'] == 'supplier_part' and $_object->get('Supplier Part Production') == 'Yes') {

                        $state['request'] = 'production/'.$_object->get('Supplier Part Supplier Key').'/part/'.$_object->id;
                        $state['module']  = 'production';
                        $state['parent']  = 'supplier_production';

                        $state['parent_key'] = $_object->get('Supplier Part Supplier Key');
                        $_parent             = get_object($state['parent'], $state['parent_key']);
                        $state['_parent']    = $_parent;
                        $state['section']    = 'production_part';


                    }
                }

                */


        //  print_r($state);

        // $state['current_store'];

        if (empty($store) and !empty($state['_parent']) and is_numeric($state['_parent']->get('Store Key'))) {
            $store = get_object('Store', $state['_parent']->get('Store Key'));
        }


        if (!$_object->id and $modules[$state['module']]['sections'][$state['section']]['type'] == 'object') {


            if ($state['object'] == 'api_key') {
                $_object          = new API_Key('deleted', $state['key']);
                $state['_object'] = $_object;
                if ($_object->id) {
                    $state['section'] = 'deleted_api_key';
                    $state['tab']     = 'api_key.history';

                }
            } elseif ($state['object'] == 'barcode') {
                $_object          = new Barcode('deleted', $state['key']);
                $state['_object'] = $_object;
                if ($_object->id) {
                    $state['section'] = 'deleted_barcode';
                    $state['tab']     = 'barcode.history';

                }
            } elseif ($state['object'] == 'customer') {


                $_object          = new Customer('deleted', $state['key']);
                $state['_object'] = $_object;


                if ($_object->id) {
                    $state['section'] = 'deleted_customer';
                    $state['tab']     = 'customer.history';

                }
            } elseif ($state['object'] == 'Customer_Poll_Query_Option') {
                $_object          = new Customer_Poll_Query_Option('deleted', $state['key']);
                $state['_object'] = $_object;
                if ($_object->id) {
                    $state['section'] = 'deleted_customer_poll_query_option';
                    $state['tab']     = 'poll_query_option.history';

                }
            } elseif ($state['object'] == 'location') {
                $_object          = new Location('deleted', $state['key']);
                $state['_object'] = $_object;
                if ($_object->id) {
                    $state['section'] = 'deleted_location';
                    $state['tab']     = 'location.history';

                }
            } elseif ($state['object'] == 'supplier') {
                $_object          = new Supplier('deleted', $state['key']);
                $state['_object'] = $_object;
                if ($_object->id) {
                    $state['section'] = 'deleted_supplier';
                    $state['tab']     = 'supplier.history';

                }


            } elseif ($state['object'] == 'invoice') {
                $_object          = get_object('invoice_deleted', $state['key']);
                $state['_object'] = $_object;
                if ($_object->id) {
                    $state['section'] = 'deleted_invoice';
                }
            } elseif ($state['object'] == 'refund') {
                $_object          = get_object('invoice_deleted', $state['key']);
                $state['_object'] = $_object;
                if ($_object->id) {
                    $state['section'] = 'deleted_invoice';
                }
            } elseif ($state['object'] == 'purchase_order') {
                $_object          = new PurchaseOrder('deleted', $state['key']);
                $state['_object'] = $_object;


                if ($_object->id) {
                    $state['section'] = 'deleted_order';

                    if (!array_key_exists(
                        $state['tab'], $modules[$state['module']]['sections'][$state['section']]['tabs']
                    )) {
                        $state['tab'] = 'deleted.user.history';
                    }


                }
            } elseif ($state['object'] == 'user') {
                $_object          = new User('deleted', $state['key']);
                $state['_object'] = $_object;
                if ($_object->id) {
                    $state['section'] = 'deleted.user';
                    if (!array_key_exists(
                        $state['tab'], $modules[$state['module']]['sections'][$state['section']]['tabs']
                    )) {
                        $state['tab'] = 'deleted.user.history';
                    }

                }
            } elseif ($state['object'] == 'employee') {
                $_object          = new Staff('deleted', $state['key']);
                $state['_object'] = $_object;
                if ($_object->id) {
                    $state['section'] = 'deleted.employee';
                    if (!array_key_exists(
                        $state['tab'], $modules[$state['module']]['sections'][$state['section']]['tabs']
                    )) {
                        $state['tab'] = 'deleted.employee.history';
                    }

                }
            } elseif ($state['object'] == 'contractor') {
                $_object          = new Staff('deleted', $state['key']);
                $state['_object'] = $_object;
                if ($_object->id) {
                    $state['section'] = 'deleted.contractor';
                    if (!array_key_exists(
                        $state['tab'], $modules[$state['module']]['sections'][$state['section']]['tabs']
                    )) {
                        $state['tab'] = 'deleted.contractor.history';
                    }

                }
            } elseif ($state['object'] == 'webpage') {
                $_object = get_object('PageDeleted', $state['key']);


                $state['_object'] = $_object;
                if ($_object->id) {
                    $state['section'] = 'deleted.webpage';
                    if (!array_key_exists(
                        $state['tab'], $modules[$state['module']]['sections'][$state['section']]['tabs']
                    )) {
                        $state['tab'] = 'deleted.webpage.history';
                    }

                }
            }


            if (!$_object->id and !($state['tab'] == 'no_website')) {

                $state = array(
                    'old_state'  => $state,
                    'module'     => 'utils',
                    'section'    => 'not_found',
                    'tab'        => 'not_found',
                    'subtab'     => '',
                    'parent'     => $state['object'],
                    'parent_key' => '',
                    'object'     => '',
                    'store'      => $store,
                    'website'    => $website,
                    'warehouse'  => $warehouse,
                    'key'        => '',
                    'request'    => $state['request']
                );
            }

        } else {

            $state['_object'] = $_object;

        }

    }

    if ($state['section'] == 'setup') {

        $state = array(
            'old_state'  => $state,
            'module'     => 'utils',
            'section'    => 'not_found',
            'tab'        => 'not_found',
            'subtab'     => '',
            'parent'     => $state['parent'],
            'parent_key' => '',
            'object'     => '',
            'key'        => '',
            'store'      => $store,
            'website'    => $website,
            'warehouse'  => $warehouse,
            'request'    => $data['request']
        );

    }


    if (is_object($_parent) and !$_parent->id) {
        $state = array(
            'old_state'  => $state,
            'module'     => 'utils',
            'section'    => 'not_found',
            'tab'        => 'not_found',
            'subtab'     => '',
            'parent'     => $state['parent'],
            'parent_key' => '',
            'object'     => '',
            'key'        => '',
            'store'      => $store,
            'website'    => $website,
            'warehouse'  => $warehouse,
            'request'    => $data['request']
        );
    }

    if ($state['module'] == 'hr') {
        if (!$user->can_view('staff')) {
            $state = array(
                'old_state'  => $state,
                'module'     => 'utils',
                'section'    => 'forbidden',
                'tab'        => 'forbidden',
                'subtab'     => '',
                'parent'     => $state['parent'],
                'parent_key' => $state['parent_key'],
                '_object'    => '',
                'object'     => '',
                'key'        => '',
                'store'      => $store,
                'website'    => $website,
                'warehouse'  => $warehouse
            );
        }
    }


    $state['store']      = $store;
    $state['website']    = $website;
    $state['warehouse']  = $warehouse;
    $state['production'] = $production;


    if (is_object($store) and $store->id) {
        $state['current_store'] = $store->id;
    }
    if (is_object($warehouse) and $warehouse->id) {
        $state['current_warehouse'] = $warehouse->id;
    }

    $sql = sprintf(
        'INSERT INTO `User System View Fact`  (`User Key`,`Date`,`Module`,`Section`,`Tab`,`Parent`,`Parent Key`,`Object`,`Object Key`)  VALUES (%d,%s,%s,%s,%s,%s,%s,%s,%s)', $user->id, prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql($state['module']),
        prepare_mysql($state['section']), prepare_mysql(
            ($state['subtab'] != '' ? $state['subtab'] : $state['tab'])
        ), prepare_mysql($state['parent']), prepare_mysql($state['parent_key']), prepare_mysql($state['object']), prepare_mysql($state['key'])

    );
    $db->exec($sql);

    $response = array('app_state' => array());


    if (isset($state['current_store'])) {
        $_SESSION['current_store'] = $state['current_store'];

        if ($state['current_store']) {
            $store_data                     = get_cached_object_data($redis, DNS_ACCOUNT_CODE, 'Store', $state['current_store']);
            $response['current_store_code'] = $store_data['code'];
        }

        $user->fast_update_json_field('User Settings', 'current_store', $state['current_store']);

    }


    list($state, $response['view_position']) = get_breadcrumbs(
        $db, $state, $user, $smarty, $account
    );


    if ($data['old_state']['module'] != $state['module'] or $reload) {
        $response['menu'] = get_menu($state, $user, $smarty, $db, $account);

    }


    if ($data['old_state']['module'] != $state['module'] or $data['old_state']['section'] != $state['section'] or $data['old_state']['parent_key'] != $state['parent_key'] or $data['old_state']['key'] != $state['key'] or $reload or isset(
            $data['metadata']['reload_showcase']

        )

    ) {


        $response['nav'] = get_navigation($user, $smarty, $state, $db, $account);


    }
    if ($reload) {
        $response['logout_label'] = _('Logout');
    }


    //special dynamic tabs
    if ($state['section'] == 'timesheets') {

        if ($state['parent'] == 'day') {

            unset($modules[$state['module']]['sections'][$state['section']]['tabs']['timesheets.days']);
            unset($modules[$state['module']]['sections'][$state['section']]['tabs']['timesheets.weeks']);
            unset($modules[$state['module']]['sections'][$state['section']]['tabs']['timesheets.months']);

            if ($state['tab'] == 'timesheets.days' or $state['tab'] == 'timesheets.weeks' or $state['tab'] == 'timesheets.months') {
                $state['tab'] = 'timesheets.employees';
            }

        } elseif ($state['parent'] == 'week') {

            unset($modules[$state['module']]['sections'][$state['section']]['tabs']['timesheets.weeks']);
            unset($modules[$state['module']]['sections'][$state['section']]['tabs']['timesheets.months']);

            if ($state['tab'] == 'timesheets.weeks' or $state['tab'] == 'timesheets.months') {
                $state['tab'] = 'timesheets.days';
            }

        } elseif ($state['parent'] == 'month') {

            unset($modules[$state['module']]['sections'][$state['section']]['tabs']['timesheets.months']);

            if ($state['tab'] == 'timesheets.months') {
                $state['tab'] = 'timesheets.weeks';
            }

        }
    } elseif ($state['module'] == 'orders') {

        if ($state['section'] == 'mailshot') {

            switch ($state['_object']->get('Email Campaign State')) {

                case 'InProcess':

                    if (!in_array(
                        $state['tab'], array(
                                         'email_campaign.details',
                                         'email_campaign.mail_list'
                                     )
                    )) {
                        $state['tab'] = 'email_campaign.details';
                    }


                    break;

            }
        }


    }


    list($state, $response['tabs']) = get_tabs($state, $db, $account, $modules, $user, $smarty, $requested_tab);// todo only calculate when is subtabs in the section


    $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'request', $state['request']);


    if ($state['object'] != '' and ($modules[$state['module']]['sections'][$state['section']]['type'] == 'object' or isset($modules[$state['module']]['sections'][$state['section']]['showcase']))) {


        if (isset($data['metadata']['reload_showcase']) or !($data['old_state']['module'] == $state['module'] and $data['old_state']['section'] == $state['section'] and $data['old_state']['object'] == $state['object'] and $data['old_state']['key'] == $state['key'])) {


            list($response['object_showcase'], $title, $web_location) = get_object_showcase(
                (isset($modules[$state['module']]['sections'][$state['section']]['showcase']) ? $modules[$state['module']]['sections'][$state['section']]['showcase'] : $state['object']), $state, $smarty, $user, $db, $account, $redis
            );

            if ($title != '') {
                $state['title'] = $title;
            }

            if ($web_location != '') {
                $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', $web_location);
            } else {

                switch ($state['module']) {
                    case 'products':
                        switch ($state['section']) {
                            case 'email_campaign_type':


                                switch ($state['_object']->get('Email Campaign Type Code')) {
                                    case 'Newsletter':
                                        $web_location = '<i class="fal fa-fw fa-newspaper"></i> '._('Newsletters');
                                        break;
                                    case 'Marketing':
                                        $web_location = '<i class="fal fa-fw fa-bullhorn"></i> '._('Mailshots');
                                        break;
                                }

                                break;
                        }
                        break;
                }


                $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', $web_location);
            }


        }


    } else {

        $response['object_showcase'] = '_';
        switch ($state['module']) {

            case 'inventory':
                switch ($state['section']) {
                    case 'barcodes':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-barcode"></i> '._("Retail Barcodes"));

                        break;
                    case 'dashboard':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-tachometer"></i> '._("Dashboard"));
                        break;
                    case 'categories':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-sitemap"></i> '._("Part's categories"));
                        break;
                    case 'stock_history':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-scanner"></i> '._("Stock history"));
                        break;
                    case 'part.attachment.new':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-paperclip"></i> '._("Upload attachment for part"));
                        break;
                    default:
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-boxes"></i> '._("Inventory (Parts)"));

                }

                break;

            case 'suppliers':
                switch ($state['section']) {
                    case 'agents':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-user-secret"></i> '._('Agents'));
                        break;
                    case 'categories':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-sitemap"></i> '._("Supplier's categories"));
                        break;
                    default:
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-hand-holding-box"></i> '._('Suppliers'));

                }

                break;
            case 'dashboard':
                switch ($state['section']) {

                    default:
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-tachometer-alt"></i> '._('Dashboard'));

                }

                break;
            case 'orders':
                switch ($state['section']) {
                    case 'dashboard':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-stream"></i> '._("Pending orders").' '.$store->get('Code'));
                        break;
                    default:
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-shopping-cart"></i> '._('Orders').' '.$store->get('Code'));

                }
                break;
            case 'products':

                switch ($state['section']) {
                    case 'marketing':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-bullhorn"></i> '._("Marketing").' '.$store->get('Code'));
                        break;
                    case 'offers':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-tags"></i> '._("Offers").' '.$store->get('Code'));
                        break;
                    case 'categories':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-sitemap"></i> '._("Product's categories").' '.$store->get('Code'));
                        break;

                    default:
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-cubes"></i> '._('Products').' '.$store->get('Code'));
                }
                break;
            case 'customers':


                switch ($state['section']) {
                    case 'customer_notifications':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-paper-plane"></i> '._("Notifications").' '.$store->get('Code'));
                        break;
                    case 'insights':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-graduation-cap"></i> '._("Customer's insights").' '.$store->get('Code'));
                        break;
                    case 'lists':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-list"></i> '._("Customer's lists").' '.$store->get('Code'));
                        break;

                    case 'dashboard':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-tachometer"></i> '._("Customer's dashboard").' '.$store->get('Code'));
                        break;
                    case 'prospects':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-user-friends"></i> '._('Prospects').' '.$store->get('Code'));
                        break;
                    case 'categories':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-sitemap"></i> '._("Customer's categories").' '.$store->get('Code'));
                        break;
                    case 'sub_category':
                        exit;
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-users"></i> <i class="fal fa-fw fa-sitemap"></i> '._("Customer's category"));
                        break;
                    default:
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-users"></i> '._('Customers').' '.$store->get('Code'));

                }

                break;
            case 'customers_server':

                switch ($state['section']) {
                    case 'insights':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-graduation-cap"></i> '._('Customers insights (All stores)'));
                        break;
                    default:
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-users"></i> '._('Customers (All stores)'));

                }

                break;
            case 'orders_server':
                switch ($state['section']) {
                    case 'group_by_store':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-compress"></i> '._("Orders by store"));
                        break;
                    case 'dashboard':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-stream"></i> '._("Pending orders"));
                        break;
                    default:
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-shopping-cart"></i> '._('Orders (All stores)'));

                }

                break;
            case 'customers_server':
                switch ($state['section']) {
                    case 'email_communications':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-paper-plane"></i> '._('Notifications.').' ('._('All stores').')');
                        break;

                    default:
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-users"></i> '._('Customers (All stores)'));

                }

                break;
            case 'accounting':
                switch ($state['section']) {

                    case 'invoices':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-file-invoice-dollar"></i> '._('Invoices').' '.$store->get('Code'));
                        break;
                    case 'payments':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-credit-cart"></i> '._('Payments').' '.$store->get('Code'));
                        break;
                    case 'credits':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-piggy-bank"></i> '._('Vault').' '.$store->get('Code'));
                        break;
                    case 'deleted_invoices':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-file-invoice-dollar"></i> '._('Deleted invoices').' '.$store->get('Code'));
                        break;

                }
            case 'accounting_server':
                switch ($state['section']) {

                    case 'invoices':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-file-invoice-dollar"></i> '._('Invoices').' ('._('All').')');
                        break;
                    case 'deleted_invoices':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-file-invoice-dollar"></i> '._('Deleted invoices').' ('._('All').')');
                        break;
                    case 'payments':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-credit-cart"></i> '._('Payments').' ('._('All').')');
                        break;
                    case 'credits':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-piggy-bank"></i> '._('Vault').' ('._('All').')');
                        break;


                }

                break;
            case 'warehouses':
                switch ($state['section']) {

                    case 'dashboard':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-warehouse"></i> '._("Dashboard"));
                        break;
                    case 'warehouse':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-warehouse"></i> '.$warehouse->get('Code'));
                        break;
                    case 'locations':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-pallet"></i> '._("Locations"));
                        break;
                    case 'returns':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-backspace"></i> '._("Returns"));
                        break;


                }

                break;
            case 'delivery_notes_server':
                switch ($state['section']) {
                    case 'group_by_store':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-compress"></i> '._("Delivery notes by store"));
                        break;
                    case 'delivery_notes':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-truxk"></i> '._('Delivery notes').' ('._('All').')');
                        break;
                    case 'pending_delivery_notes':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-stream"></i> '._("Pending delivery notes'"));
                        break;


                }
            case 'hr':
                switch ($state['section']) {
                    case 'employees':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-hand-rock"></i> '._('Employees'));
                        break;
                    case 'contractors':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-hand-spock"></i> '._('Contractors'));
                        break;
                    case 'overtimes':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-clock"></i> '._('Overtimes'));
                        break;
                    case 'organization':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-sitemap"></i> '._('Organization'));
                        break;

                }

                break;
            case 'users':
                switch ($state['section']) {
                    case 'users':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-users-class"></i> '._('Users').' ('._('All').')');
                        break;
                    case 'staff':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-users-class"></i> '._('Users').' ('._('Employees').')');
                        break;
                    case 'contractors':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-users-class"></i> '._('Users').' ('._('Contractors').')');
                        break;
                    case 'suppliers':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-users-class"></i> '._('Users').' ('._('Suppliers').')');
                        break;
                    case 'agents':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-users-class"></i> '._('Users').' ('._('Agents').')');
                        break;
                    default:
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-users-class"></i> '._('Users'));

                }
                break;
            case 'reports':
                switch ($state['section']) {
                    case 'intrastat':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-chart-line"></i> '._('Intrastat'));
                        break;
                    case 'pickers':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-chart-line"></i> '._('Pickers'));
                        break;
                    case 'packers':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-chart-line"></i> '._('Packers'));
                        break;

                    default:
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-chart-line"></i> '._('Reports'));

                }

                break;
            case 'account':
                switch ($state['section']) {


                    default:
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-toolbox"></i> '._('Account'));

                }

                break;
            case 'production':
                switch ($state['section']) {
                    case 'dashboard':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-tachometer-alt"></i> '._('Production'));
                        break;
                    case 'materials':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-industry"></i> '._('Materials'));
                        break;
                    case 'production_parts':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-industry"></i> '._('Parts'));
                        break;
                    case 'manufacture_tasks':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-industry"></i> '._('Manufacture tasks'));
                        break;
                    case 'production_supplier_orders':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-industry"></i> '._('Job orders'));
                        break;
                    case 'production_supplier_deliveries':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-industry"></i> '._('Deliveries'));
                        break;
                    case 'settings':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-industry"></i> '._('Settings'));
                        break;
                    default:

                }
            case 'fulfilment':
                switch ($state['section']) {
                    case 'dashboard':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-tachometer-alt"></i> '._('Fulfilment'));
                        break;
                    case 'customers':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-user"></i> '._('Fulfilment customers'));
                        break;
                    case 'locations':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-pallet"></i> '._('Fulfilment locations'));
                        break;

                }
                break;
            case 'utils':
                switch ($state['section']) {
                    case 'fire':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-chess-clock"></i> '._('Attendance'));
                        break;

                    default:

                }

                break;
            default:
                $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '');
            // $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', $state['module'].' '.$state['section']);

        }


    }

    $state['metadata'] = (isset($data['metadata']) ? $data['metadata'] : array());

    $response['tab'] = get_tab($redis, $db, $smarty, $user, $account, $state['tab'], $state['subtab'], $state, $data['metadata']);

    if ($old_weblocation != (isset($state['module']) ? $state['module'] : '').'|'.(isset($state['section']) ? $state['section'] : '')) {


        require_once 'utils/real_time_functions.php';
        $real_time_users = get_users_read_time_data($redis, $account);

        include_once 'utils/send_zqm_message.class.php';
        send_zqm_message(
            json_encode(
                array(
                    'channel' => 'real_time.'.strtolower($account->get('Account Code')),
                    'iu'      => $real_time_users,
                )
            )
        );


    }


    unset($state['_object']);
    unset($state['_parent']);

    unset($state['old_state']['_parent']);
    unset($state['old_state']['_object']);
    unset($state['old_state']['store']);
    unset($state['old_state']['website']);
    unset($state['old_state']['warehouse']);
    unset($state['old_state']['old_state']);

    unset($state['store']);
    unset($state['website']);
    unset($state['warehouse']);
    unset($state['production']);

    $state['stores']   = $user->stores;
    $response['state'] = 200;

    $response['app_state'] = $state;


    $encoded = json_encode($response);

    if ($encoded == '') {
        $encoded = json_encode(utf8ize($response));
    }

    echo $encoded;


}

function utf8ize($mixed) {
    if (is_array($mixed)) {
        foreach ($mixed as $key => $value) {
            $mixed[$key] = utf8ize($value);
        }
    } elseif (is_string($mixed)) {
        return mb_convert_encoding($mixed, "UTF-8", "UTF-8");
    }

    return $mixed;
}


function get_tab($redis, $db, $smarty, $user, $account, $tab, $subtab, $state = false, $metadata = false) {


    //cleaning mess can removed later
    if (!is_string($tab)) {
        $tab = '';
    }
    if (!is_string($subtab)) {
        $subtab = '';
    }
    //=================


    $html = '';

    if (isset($state['section']) and $state['section'] == 'customer' and $state['store']->get('Store Type') == 'Dropshipping' and $state['_object']->get('Customer Type by Activity') == 'ToApprove') {
        return '';
    }


    $_tab    = $tab;
    $_subtab = $subtab;


    $actual_tab   = ($subtab != '' ? $subtab : $tab);
    $state['tab'] = $actual_tab;


    $smarty->assign('data', $state);


    if (file_exists('tabs/'.$actual_tab.'.tab.php')) {

        include_once 'tabs/'.$actual_tab.'.tab.php';
    } else {
        $html = 'Tab Not found: >'.$actual_tab.'.tab.php<';

    }

    if (is_array($state) and !(preg_match('/_edit$/', $tab) or preg_match('/\.wget$/', $_tab))) {


        $tmp = $_SESSION['state'];

        $tmp[$state['module']][$state['section']]['tab'] = $_tab;


        $_SESSION['state'] = $tmp;

        if (!empty($_subtab)) {


            if (isset($_SESSION['tab_state'])) {
                $tmp                   = $_SESSION['tab_state'];
                $tmp[$_tab]            = $_subtab;
                $_SESSION['tab_state'] = $tmp;
            } else {
                $_SESSION['tab_state'][$_tab] = $_subtab;
            }

        }

    }

    return $html;

}


function get_menu($data, $user, $smarty, $db, $account) {

    include_once 'navigation/menu.php';
    $account->load_acc_data();

    return get_menu_html($data, $user, $smarty, $db, $account);


}


function get_tabs($data, $db, $account, $modules, $user, $smarty, $requested_tab = '') {


    //cleaning mess can removed later
    if (empty($data['subtab']) or !is_string($data['subtab'])) {
        $data['subtab'] = '';
    }
    //=================


    if (preg_match('/\_edit$/', $data['tab']) or $data['section'] == 'refund.new') {
        return array(
            $data,
            ''
        );
    }


    if (isset($modules[$data['module']]['sections'][$data['section']]['tabs'])) {


        $tabs = $modules[$data['module']]['sections'][$data['section']]['tabs'];
    } else {
        $tabs = array();
    }


    if (isset($modules[$data['module']]['sections'][$data['section']]['tabs'][$data['tab']] ['subtabs'])) {

        $subtabs = $modules[$data['module']]['sections'][$data['section']]['tabs'][$data['tab']]['subtabs'];
    } else {
        $subtabs = array();
    }


    if (isset($tabs[$data['tab']])) {
        $tabs[$data['tab']]['selected'] = true;
    }


    if (isset($subtabs[$data['subtab']])) {
        $subtabs[$data['subtab']]['selected'] = true;
    }


    foreach ($tabs as $key => $tab) {
        if (isset($tab['quantity_data'])) {
            $tabs[$key]['label'] .= sprintf(
                ' <span class=\'discreet %s\'>(%s)</span>', preg_replace('/\s/', '_', $tab['quantity_data']['field']), $data[$tab['quantity_data']['object']]->get($tab['quantity_data']['field'])
            );
        }

        if (isset($tab['dynamic_reference'])) {
            $tabs[$key]['reference'] = sprintf($tab['dynamic_reference'], $data['parent_key']);
        }

    }

    foreach ($subtabs as $key => $subtab) {
        if (isset($subtab['quantity_data'])) {
            $subtabs[$key]['label'] .= sprintf(
                ' <span class=\'discreet %s\'>(%s)</span>', preg_replace('/\s/', '_', $subtab['quantity_data']['field']), $data[$subtab['quantity_data']['object']]->get($subtab['quantity_data']['field'])
            );
        }
    }


    $_content = array(
        'tabs'    => $tabs,
        'subtabs' => $subtabs


    );


    if ($data['section'] == 'customer') {


        if ($data['store']->get('Store Type') == 'Dropshipping') {

            if ($data['_object']->get('Customer Type by Activity') == 'ToApprove') {
                $_content['tabs'] = [];
            } else {
                $_content['tabs']['customer.clients']['class'] = '';

            }


        } else {
            $_content['tabs']['customer.clients']['class'] = 'hide';
            if ($data['tab'] == 'customer.clients') {
                $data['tab'] = 'customer.history';
            }

        }


    } elseif ($data['section'] == 'employees') {

        if (!$user->can_edit('Staff')) {
            $_content['tabs']['exemployees']['class']       = 'hide';
            $_content['tabs']['deleted.employees']['class'] = 'hide';

            if ($data['tab'] == 'exemployees' or $data['tab'] == 'deleted.employees') {
                $data['tab'] = 'employees';
            }
        }


    } elseif ($data['section'] == 'employee') {

        if (!$user->can_edit('Staff')) {
            $_content['tabs']['employee.details']['class']     = 'hide';
            $_content['tabs']['employee.attachments']['class'] = 'hide';
            $_content['tabs']['employee.history']['class']     = 'hide';

            if ($data['tab'] == 'employee.details' or $data['tab'] == 'employee.attachment' or $data['tab'] == 'employee.history') {
                $data['tab'] = 'employee.today_timesheet.record';
            }
        }


    } elseif ($data['section'] == 'mailshot') {


        $_content['tabs']['mailshot.set_mail_list']['class'] = 'hide';
        $_content['tabs']['mailshot.mail_list']['class']     = 'hide';


        switch ($data['_object']->get('Email Campaign State')) {

            case 'InProcess':
                $_content['tabs']['mailshot.workshop']['class'] = 'hide';
                $_content['subtabs']                            = array();

                $_content['tabs']['mailshot.sent_emails']['class']     = 'hide';
                $_content['tabs']['mailshot.published_email']['class'] = 'hide';

                $data['tab'] = 'mailshot.details';


                $_content['tabs']['mailshot.details']['selected'] = true;


                break;
            case 'SetRecipients':
                $_content['tabs']['mailshot.workshop']['class']        = 'hide';
                $_content['tabs']['mailshot.sent_emails']['class']     = 'hide';
                $_content['tabs']['mailshot.published_email']['class'] = 'hide';
                $_content['tabs']['mailshot.set_mail_list']['class']   = '';
                $_content['tabs']['mailshot.mail_list']['class']       = 'hide';

                //$_content['tabs']['mailshot.details']['selected'] = true;
                //$_content['tabs']['mailshot.details']['selected'] = true;

                if ($data['tab'] == 'mailshot.mail_list') {
                    $data['tab'] = 'mailshot.set_mail_list';
                }


                break;

            case 'ComposingEmail':
            case 'Stopped':
                $email_template = get_object('Email_Template', $data['_object']->get('Email Campaign Email Template Key'));


                if (($email_template->id and !($email_template->get('Email Template Type') == 'HTML' and $email_template->get('Email Template Editing JSON') == '')) and $email_template->get('Email Template Selecting Blueprints') == 'No') {


                    if ($data['tab'] == 'mailshot.details') {
                        $_content['subtabs'] = array();


                    } else {


                        $data['tab'] = 'mailshot.workshop';


                        $_content['subtabs'] = $modules[$data['module']]['sections'][$data['section']] ['tabs']['mailshot.workshop']['subtabs'];

                        $_content['subtabs']['mailshot.workshop.composer']['class']      = 'first';
                        $_content['subtabs']['mailshot.workshop.composer_text']['class'] = '';

                        $_content['subtabs']['mailshot.workshop.templates']['class']              = 'hide';
                        $_content['subtabs']['mailshot.workshop.other_stores_mailshots']['class'] = 'hide';
                        $_content['subtabs']['mailshot.workshop.previous_mailshots']['class']     = 'hide';


                        if (!($data['subtab'] == 'mailshot.workshop.composer' or $data['subtab'] == 'mailshot.workshop.composer_text')) {

                            $data['subtab'] = 'mailshot.workshop.composer';
                        }
                    }

                    $_content['subtabs'][$data['subtab']]['selected'] = true;

                } else {


                    if ($data['tab'] == 'mailshot.details') {
                        $_content['subtabs'] = array();


                    } else {
                        $data['tab'] = 'mailshot.workshop';


                        $_content['subtabs'] = $modules[$data['module']]['sections'][$data['section']] ['tabs']['mailshot.workshop']['subtabs'];


                        $_content['subtabs']['mailshot.workshop.composer']['class']      = 'hide';
                        $_content['subtabs']['mailshot.workshop.composer_text']['class'] = 'hide';


                        $_content['subtabs']['mailshot.workshop.templates']['class']              = 'first';
                        $_content['subtabs']['mailshot.workshop.other_stores_mailshots']['class'] = '';
                        $_content['subtabs']['mailshot.workshop.previous_mailshots']['class']     = '';


                        if ($data['subtab'] == 'mailshot.workshop.composer' or $data['subtab'] == 'mailshot.workshop.composer_text' or $data['subtab'] == '') {
                            $data['subtab'] = 'mailshot.workshop.templates';
                        }
                    }

                    $_content['subtabs'][$data['subtab']]['selected'] = true;


                }

                if ($data['_object']->get('Email Campaign State') == 'Stopped') {
                    $_content['tabs']['mailshot.published_email']['class'] = 'hide';
                    $_content['tabs']['mailshot.sent_emails']['class']     = 'hide';
                }


                /*

                $_content['tabs']['mailshot.email_blueprints']['class'] = 'hide';
                $_content['tabs']['mailshot.published_email']['class']  = 'hide';


                if ($data['tab'] == 'mailshot.published_email') {
                    $data['tab'] = 'mailshot.workshop';
                }

                */
                break;

            case 'Ready':
                $_content['tabs']['mailshot.email_blueprints']['class'] = 'hide';
                $_content['tabs']['mailshot.workshop']['class']         = 'hide';
                $_content['tabs']['mailshot.sent_emails']['class']      = 'hide';


                if ($data['tab'] == 'mailshot.workshop') {
                    $data['tab'] = 'mailshot.published_email';
                }
                break;
            case 'Sent':
            case 'Sending':


                $_content['tabs']['mailshot.email_blueprints']['class'] = 'hide';
                $_content['tabs']['mailshot.workshop']['class']         = 'hide';

                $_content['subtabs'] = array();


                $_content['tabs']['mailshot.mail_list']['class'] = 'hide';

                $_content['tabs']['mailshot.sent_emails']['class'] = '';


                if ($data['tab'] == 'mailshot.workshop') {
                    $data['tab'] = 'mailshot.sent_emails';
                }

                break;

        }


        /*

        switch ($data['_object']->get('Email Campaign Type')) {
            case 'Newsletter':



                break;

            case 'AbandonedCart':
                $_content['tabs']['mailshot.email_blueprints']['class'] = 'hide';

                $_content['tabs']['mailshot.set_mail_list']['class'] = 'hide';
                $_content['tabs']['mailshot.mail_list']['class']     = 'hide';


                switch ($data['_object']->get('Email Campaign State')) {

                    case 'InProcess':
                        $_content['tabs']['mailshot.workshop']['class']        = 'hide';
                        $_content['tabs']['mailshot.sent_emails']['class']     = 'hide';
                        $_content['tabs']['mailshot.published_email']['class'] = 'hide';

                        //$_content['tabs']['mailshot.details']['selected'] = true;
                        //$_content['tabs']['mailshot.details']['selected'] = true;


                        break;
                    case 'SetRecipients':
                        $_content['tabs']['mailshot.workshop']['class']        = 'hide';
                        $_content['tabs']['mailshot.sent_emails']['class']     = 'hide';
                        $_content['tabs']['mailshot.published_email']['class'] = 'hide';
                        $_content['tabs']['mailshot.set_mail_list']['class']   = '';
                        $_content['tabs']['mailshot.mail_list']['class']       = 'hide';

                        //$_content['tabs']['mailshot.details']['selected'] = true;
                        //$_content['tabs']['mailshot.details']['selected'] = true;

                        if ($data['tab'] == 'mailshot.mail_list') {
                            $data['tab'] = 'mailshot.set_mail_list';
                        }


                        break;

                    case 'ComposingEmail':
                        $_content['tabs']['mailshot.email_blueprints']['class'] = 'hide';
                        $_content['tabs']['mailshot.published_email']['class']  = 'hide';
                        $_content['tabs']['mailshot.sent_emails']['class']      = 'hide';

                        if ($data['tab'] == 'mailshot.published_email') {
                            $data['tab'] = 'mailshot.workshop';
                        }
                        break;

                    case 'Ready':
                        $_content['tabs']['mailshot.email_blueprints']['class'] = 'hide';
                        $_content['tabs']['mailshot.workshop']['class']         = 'hide';
                        $_content['tabs']['mailshot.sent_emails']['class']      = 'hide';


                        if ($data['tab'] == 'mailshot.workshop') {
                            $data['tab'] = 'mailshot.published_email';
                        }
                        break;
                    case 'Sent':
                    case 'Sending':
                    case 'Stopped':
                        $_content['tabs']['mailshot.email_blueprints']['class'] = 'hide';
                        $_content['tabs']['mailshot.workshop']['class']         = 'hide';
                        $_content['tabs']['mailshot.mail_list']['class']        = 'hide';

                        $_content['tabs']['mailshot.sent_emails']['class'] = '';


                        if ($data['tab'] == 'mailshot.workshop') {
                            $data['tab'] = 'mailshot.sent_emails';
                        }

                        break;

                }

                break;

            case 'Marketing':

                $_content['tabs']['mailshot.email_blueprints']['class'] = 'hide';

                $_content['tabs']['mailshot.set_mail_list']['class'] = 'hide';
                $_content['tabs']['mailshot.mail_list']['class']     = 'hide';


                switch ($data['_object']->get('Email Campaign State')) {

                    case 'InProcess':
                        $_content['tabs']['mailshot.workshop']['class']        = 'hide';
                        $_content['tabs']['mailshot.sent_emails']['class']     = 'hide';
                        $_content['tabs']['mailshot.published_email']['class'] = 'hide';
                        $_content['tabs']['mailshot.set_mail_list']['class']   = '';

                        //$_content['tabs']['mailshot.details']['selected'] = true;
                        if ($data['tab'] != 'mailshot.details') {
                            $_content['tabs']['mailshot.set_mail_list']['selected'] = true;

                            $data['tab'] = 'mailshot.set_mail_list';
                        }

                        break;
                    case 'SetRecipients':
                        $_content['tabs']['mailshot.workshop']['class']        = 'hide';
                        $_content['tabs']['mailshot.sent_emails']['class']     = 'hide';
                        $_content['tabs']['mailshot.published_email']['class'] = 'hide';
                        $_content['tabs']['mailshot.set_mail_list']['class']   = '';
                        $_content['tabs']['mailshot.mail_list']['class']       = 'hide';

                        //$_content['tabs']['mailshot.details']['selected'] = true;
                        //$_content['tabs']['mailshot.details']['selected'] = true;

                        if ($data['tab'] == 'mailshot.mail_list') {
                            $data['tab'] = 'mailshot.set_mail_list';
                        }


                        break;

                    case 'ComposingEmail':
                        $_content['tabs']['mailshot.email_blueprints']['class'] = 'hide';
                        $_content['tabs']['mailshot.published_email']['class']  = 'hide';
                        $_content['tabs']['mailshot.sent_emails']['class']      = 'hide';

                        if ($data['tab'] == 'mailshot.published_email') {
                            $data['tab'] = 'mailshot.workshop';
                        }
                        break;

                    case 'Ready':
                        $_content['tabs']['mailshot.email_blueprints']['class'] = 'hide';
                        $_content['tabs']['mailshot.workshop']['class']         = 'hide';
                        $_content['tabs']['mailshot.sent_emails']['class']      = 'hide';


                        if ($data['tab'] == 'mailshot.workshop') {
                            $data['tab'] = 'mailshot.published_email';
                        }
                        break;
                    case 'Sent':
                    case 'Sending':
                    case 'Stopped':
                        $_content['tabs']['mailshot.email_blueprints']['class'] = 'hide';
                        $_content['tabs']['mailshot.workshop']['class']         = 'hide';
                        $_content['tabs']['mailshot.mail_list']['class']        = 'hide';

                        $_content['tabs']['mailshot.sent_emails']['class'] = '';


                        if ($data['tab'] == 'mailshot.workshop') {
                            $data['tab'] = 'mailshot.sent_emails';
                        }

                        break;

                }

                break;

        }
*/

    } elseif ($data['section'] == 'category') {

        if ($data['_object']->get('Category Scope') == 'Product') {

            //  print_r($_content['tabs']);


            if ($data['_object']->get('Category Subject') == 'Product') {


                $_content['tabs']['category.subjects']['label'] = _('Products');
                $_content['tabs']['category.subjects']['icon']  = 'cube';


                if ($data['_object']->get('Root Key') == $data['store']->get('Store Family Category Key')) {
                    $_content['tabs']['category.categories']['label'] = _('Families');


                    if ($data['store']->get('Store Family Category Key') == $data['_object']->id) {

                        $_content['tabs']['category.webpage']['class']         = 'hide';
                        $_content['tabs']['category.details']['class']         = 'hide';
                        $_content['tabs']['category.customers']['class']       = 'hide';
                        $_content['tabs']['category.sales']['class']           = 'hide';
                        $_content['tabs']['category.mailshots']['class']       = 'hide';
                        $_content['tabs']['category.deal_components']['class'] = 'hide';
                        $_content['tabs']['category._correlations']['class']   = 'hide';
                        $_content['tabs']['category.images']['class']          = 'hide';
                        $_content['tabs']['category.history']['class']         = 'hide';
                        $_content['tabs']['category.categories']['class']      = 'hide';


                        $_content['tabs']['category.categories']['selected'] = true;
                        $data['tab']                                         = 'category.categories';
                        $data['subtab']                                      = '';
                        $_content['subtabs']                                 = array();

                    }


                }

            } else {


                if ($data['_object']->get('Root Key') == $data['store']->get('Store Department Category Key')) {
                    $_content['tabs']['category.subjects']['label']   = _('Families');
                    $_content['tabs']['category.categories']['label'] = _('Departments');


                    if ($data['store']->get('Store Department Category Key') == $data['_object']->id) {

                        $_content['tabs']['category.webpage']['class']         = 'hide';
                        $_content['tabs']['category.details']['class']         = 'hide';
                        $_content['tabs']['category.customers']['class']       = 'hide';
                        $_content['tabs']['category.sales']['class']           = 'hide';
                        $_content['tabs']['category.mailshots']['class']       = 'hide';
                        $_content['tabs']['category.deal_components']['class'] = 'hide';
                        $_content['tabs']['category._correlations']['class']   = 'hide';
                        $_content['tabs']['category.images']['class']          = 'hide';
                        $_content['tabs']['category.history']['class']         = 'hide';
                        $_content['tabs']['category.categories']['class']      = 'hide';


                        $_content['tabs']['category.categories']['selected'] = true;
                        $data['tab']                                         = 'category.categories';
                        $data['subtab']                                      = '';
                        $_content['subtabs']                                 = array();

                    }


                } else {

                    $_content['tabs']['category.subjects']['label'] = _('Categories');
                }

            }


        } elseif ($data['_object']->get('Category Scope') == 'Part') {

            $_content['tabs']['category.customers']['class'] = 'hide';


            if ($data['_object']->get('Root Key') == $account->get('Account Part Family Category Key')) {

                $_content['tabs']['category.categories']['label'] = _('Families');

                if ($data['_object']->get('Category Branch Type') != 'Head') {
                    $_content['tabs']['part_family.product_families']['class']        = 'hide';
                    $_content['tabs']['category.images']['class']                     = 'hide';
                    $_content['tabs']['category.part.discontinued_subjects']['class'] = 'hide';
                    $_content['tabs']['category.part.sales']['class']                 = 'hide';

                }

            } else {

                $_content['tabs']['category.subjects']['label']            = _('Parts');
                $_content['tabs']['part_family.product_families']['class'] = 'hide';
                $_content['tabs']['category.images']['class']              = 'hide';

            }


        } elseif ($data['_object']->get('Category Scope') == 'Invoice') {

            if ($data['_object']->get('Category Branch Type') == 'Root') {
                $_content['tabs']['category.details']['class'] = 'hide';

                if ($data['tab'] == 'category.details') {
                    $_content['tabs']['category.categories']['selected'] = true;

                    $data['tab'] = 'category.categories';

                }

            }


        }


        if ($data['_object']->get('Category Branch Type') == 'Head') {
            unset($_content['tabs']['category.categories']);
            if ($data['tab'] == 'category.categories') {
                $_content['tabs']['category.subjects']['selected'] = true;
                $data['tab']                                       = 'category.subjects';
            }

        } else {
            unset($_content['tabs']['category.subjects']);
            if ($data['tab'] == 'category.subjects') {
                $_content['tabs']['category.categories']['selected'] = true;
                $data['tab']                                         = 'category.categories';
            }
        }

        //print_r($data['_object']);
        //print_r($_content);


    } elseif ($data['section'] == 'prospects.email_template') {

        if ($requested_tab != '') {
            $data['tab'] = $requested_tab;

        } else {
            $data['tab'] = 'prospects.template.workshop';

        }


    } elseif ($data['section'] == 'email_campaign_type') {


        if ($data['_object']->get('Email Campaign Type Scope') == 'User Notification') {
            $_content['tabs']['email_campaign_type.next_recipients']['class'] = 'hide';
            $_content['tabs']['email_campaign_type.details']['class']         = 'hide';
            $_content['tabs']['email_campaign_type.workshop']['class']        = 'hide';
            $_content['tabs']['email_campaign_type.mailshots']['class']       = 'hide';

            //  $_content['tabs']['email_campaign_type.mailshots']['label'] = _('Newsletters');
            //$_content['tabs']['email_campaign_type.mailshots']['icon']  = 'newspaper';


            if ($data['tab'] != 'email_campaign_type.sent_emails') {
                $_content['tabs']['email_campaign_type.sent_emails']['selected'] = true;

                $data['tab'] = 'email_campaign_type.sent_emails';

            }


        } else {
            if ($data['_object']->get('Email Campaign Type Code') == 'Newsletter') {
                $_content['tabs']['email_campaign_type.next_recipients']['class'] = 'hide';
                $_content['tabs']['email_campaign_type.details']['class']         = 'hide';
                $_content['tabs']['email_campaign_type.workshop']['class']        = 'hide';
                $_content['tabs']['email_campaign_type.sent_emails']['class']     = 'hide';

                $_content['tabs']['email_campaign_type.mailshots']['label'] = _('Newsletters');
                $_content['tabs']['email_campaign_type.mailshots']['icon']  = 'newspaper';


                if ($data['tab'] == 'email_campaign_type.next_recipients' or $data['tab'] == 'email_campaign_type.details' or $data['tab'] == 'email_campaign_type.next_recipients' or $data['tab'] == 'email_campaign_type.sent_emails') {
                    $_content['tabs']['email_campaign_type.sent_emails']['selected'] = true;

                    $data['tab'] = 'email_campaign_type.mailshots';

                }


            } elseif ($data['_object']->get('Email Campaign Type Code') == 'AbandonedCart' or $data['_object']->get('Email Campaign Type Code') == 'Marketing') {
                $_content['tabs']['email_campaign_type.next_recipients']['class'] = 'hide';
                $_content['tabs']['email_campaign_type.details']['class']         = 'hide';
                $_content['tabs']['email_campaign_type.workshop']['class']        = 'hide';
                $_content['tabs']['email_campaign_type.sent_emails']['class']     = 'hide';

                //$_content['tabs']['email_campaign_type.mailshots']['label'] = _('Newsletters');
                //$_content['tabs']['email_campaign_type.mailshots']['icon']  = 'newspaper';


                if ($data['tab'] == 'email_campaign_type.next_recipients' or $data['tab'] == 'email_campaign_type.details' or $data['tab'] == 'email_campaign_type.next_recipients' or $data['tab'] == 'email_campaign_type.sent_emails') {
                    $_content['tabs']['email_campaign_type.mailshots']['selected'] = true;

                    $data['tab'] = 'email_campaign_type.mailshots';

                }


            } elseif ($data['_object']->get('Email Campaign Type Code') == 'Invite Mailshot' or $data['_object']->get('Email Campaign Type Code') == 'Invite' or $data['_object']->get('Email Campaign Type Code') == 'Invite Full Mailshot') {
                $_content['tabs']['email_campaign_type.next_recipients']['class'] = 'hide';
                $_content['tabs']['email_campaign_type.details']['class']         = 'hide';
                $_content['tabs']['email_campaign_type.workshop']['class']        = 'hide';
                $_content['tabs']['email_campaign_type.mailshots']['class']       = 'hide';

                //$_content['tabs']['email_campaign_type.mailshots']['label'] = _('Newsletters');
                //$_content['tabs']['email_campaign_type.mailshots']['icon']  = 'newspaper';


                if ($data['tab'] != 'email_campaign_type.sent_emails') {
                    $_content['tabs']['email_campaign_type.sent_emails']['selected'] = true;

                    $data['tab'] = 'email_campaign_type.sent_emails';

                }


            } else {

                $_content['tabs']['email_campaign_type.next_recipients']['class'] = '';
                $_content['tabs']['email_campaign_type.details']['class']         = '';
                $_content['tabs']['email_campaign_type.workshop']['class']        = '';
                $_content['tabs']['email_campaign_type.sent_emails']['class']     = '';

                if (in_array(
                    $data['_object']->get('Email Campaign Type Code'), array(
                                                                         'Registration',
                                                                         'Registration Approved',
                                                                         'Registration Rejected',
                                                                         'Password Reminder',
                                                                         'Invite',
                                                                         'Delivery Confirmation',
                                                                         'Order Confirmation'
                                                                     )
                )) {
                    $_content['tabs']['email_campaign_type.mailshots']['class']       = 'hide';
                    $_content['tabs']['email_campaign_type.next_recipients']['class'] = 'hide';

                    if ($data['tab'] == 'email_campaign_type.mailshots' or $data['tab'] == 'email_campaign_type.next_recipients') {
                        $_content['tabs']['email_campaign_type.sent_emails']['selected'] = true;

                        $data['tab'] = 'email_campaign_type.sent_emails';

                    }

                    if ($data['_object']->get('Email Campaign Type Status') == 'InProcess') {

                        $_content['tabs']['email_campaign_type.details']['class']     = 'hide';
                        $_content['tabs']['email_campaign_type.sent_emails']['class'] = 'hide';
                        if ($data['tab'] == 'email_campaign_type.details' or $data['tab'] == 'email_campaign_type.sent_emails') {
                            $_content['tabs']['email_campaign_type.workshop']['selected'] = true;

                            $data['tab'] = 'email_campaign_type.workshop';

                        }


                    }


                } else {
                    $_content['tabs']['email_campaign_type.mailshots']['class']       = '';
                    $_content['tabs']['email_campaign_type.next_recipients']['class'] = '';

                }


            }
        }


    } elseif ($data['section'] == 'order') {
        if ($data['module'] == 'orders') {


            if ($requested_tab == 'order.input_picking_sheet') {
                $_content['tabs']['order.input_picking_sheet']['class'] = '';

            } else {
                if ($data['tab'] == 'order.input_picking_sheet') {
                    $data['tab']                                 = 'order.items';
                    $_content['tabs']['order.items']['selected'] = true;
                }

            }


            $order_state_index = $data['_object']->get('State Index');
            if ($order_state_index >= 40 or $order_state_index < 0) {

                $_content['tabs']['order.all_products']['class'] = 'hide';


                if ($data['tab'] == 'order.all_products') {
                    $data['tab'] = 'order.items';
                }

            } elseif ($order_state_index == 10) {


                $_content['tabs']['order.details']['class']        = 'hide';
                $_content['tabs']['order.delivery_notes']['class'] = 'hide';
                $_content['tabs']['order.invoices']['class']       = 'hide';
                $_content['tabs']['order.sent_emails']['class']    = 'hide';


                if ($data['tab'] == 'order.details' or $data['tab'] == 'order.delivery_notes' or $data['tab'] == 'order.invoices' or $data['tab'] == 'order.sent_emails') {
                    $_content['tabs']['order.items']['selected'] = true;

                    $data['tab'] = 'order.items';
                }
            } else {
                $_content['tabs']['order.all_products']['class'] = '';
            }


        } elseif ($data['module'] == 'production') {
            if ($data['_object']->get('Purchase Order State') == 'InProcess') {

                $_content['tabs']['job_order.all_production_parts']['class'] = '';
                $_content['tabs']['job_order.items_in_process']['class']     = '';
                $_content['tabs']['job_order.items']['class']                = 'hide';

                if ($data['_object']->get('Purchase Order Number Items') == 0) {


                    if ($data['tab'] == 'job_order.items_in_process') {
                        $data['tab']                                                    = 'job_order.all_production_parts';
                        $_content['tabs']['job_order.all_production_parts']['selected'] = true;
                        $_content['tabs']['job_order.items_in_process']['selected']     = false;

                    }


                    $_content['tabs']['job_order.items_in_process']['class'] = 'hide';

                } else {
                    if ($data['tab'] == 'job_order.items') {
                        $data['tab']                                                = 'job_order.items_in_process';
                        $_content['tabs']['job_order.items_in_process']['selected'] = true;
                        $_content['tabs']['job_order.items']['selected']            = false;

                    }
                    $_content['tabs']['job_order.items_in_process']['class'] = '';

                }


            } else {


                $_content['tabs']['job_order.all_production_parts']['class'] = 'hide';
                $_content['tabs']['job_order.items_in_process']['class']     = 'hide';

                if ($data['tab'] == 'job_order.items_in_process' || $data['tab'] == 'job_order.all_production_parts') {
                    $data['tab'] = 'job_order.items';
                }

            }
        } else {
            if ($data['_object']->get('Purchase Order State') == 'InProcess') {

                $_content['tabs']['supplier.order.all_supplier_parts']['class'] = '';
                $_content['tabs']['supplier.order.items_in_process']['class']   = '';
                $_content['tabs']['supplier.order.items']['class']              = 'hide';


                if ($data['tab'] == 'supplier.order.items') {
                    $data['tab'] = 'supplier.order.items_in_process';
                }

            } else {


                $_content['tabs']['supplier.order.all_supplier_parts']['class'] = 'hide';
                $_content['tabs']['supplier.order.items_in_process']['class']   = 'hide';

                if ($data['tab'] == 'supplier.order.items_in_process' || $data['tab'] == 'supplier.order.all_supplier_parts') {
                    $data['tab'] = 'supplier.order.items';
                }

            }
        }
    } elseif ($data['section'] == 'delivery') {
        if ($data['module'] == 'suppliers') {
            $_content['tabs']['supplier.delivery.items_done']['class'] = 'hide';
            $_content['tabs']['supplier.delivery.costing']['class']    = 'hide';

            $_content['tabs']['supplier.delivery.items_mismatch']['class'] = 'hide';
            $_content['tabs']['supplier.delivery.items']['class']          = '';

            switch ($data['_object']->get('Supplier Delivery State')) {


                //'InProcess','Consolidated','Dispatched','Received','Checked','Placed','Costing','Cancelled','InvoiceChecked'

                case 'Costing':


                    $_content['tabs']['supplier.delivery.costing']['class'] = '';
                    $_content['tabs']['supplier.delivery.items']['class']   = 'hide';
                    if ($data['tab'] == 'supplier.delivery.items' || $data['tab'] == 'supplier.delivery.items_done') {
                        $data['tab'] = 'supplier.delivery.costing';

                        $_content['tabs']['supplier.delivery.costing']['selected'] = true;
                    }

                    $_content['tabs']['supplier.delivery.items_mismatch']['class'] = '';


                    break;
                case 'InvoiceChecked':

                    $_content['tabs']['supplier.delivery.items_done']['class'] = '';

                    $_content['tabs']['supplier.delivery.costing']['class'] = 'hide';
                    $_content['tabs']['supplier.delivery.items']['class']   = 'hide';
                    if ($data['tab'] == 'supplier.delivery.costing' || $data['tab'] == 'supplier.delivery.items') {
                        $data['tab'] = 'supplier.delivery.items_done';

                        $_content['tabs']['supplier.delivery.items_done']['selected'] = true;
                    }
                    $_content['tabs']['supplier.delivery.items_mismatch']['class'] = '';

                    break;
                case 'Received':

                    if ($data['tab'] == 'supplier.delivery.costing' || $data['tab'] == 'supplier.delivery.items_done') {
                        $data['tab'] = 'supplier.delivery.items';

                        $_content['tabs']['supplier.delivery.items']['selected'] = true;

                    }

                    if ($data['_object']->get('Supplier Delivery Number Received and Checked Items') > 0) {
                        $_content['tabs']['supplier.delivery.items_mismatch']['class'] = '';

                    }

                    break;

                case 'Placed':
                case 'Checked':

                    if ($data['tab'] == 'supplier.delivery.costing' || $data['tab'] == 'supplier.delivery.items_done') {
                        $data['tab'] = 'supplier.delivery.items';

                        $_content['tabs']['supplier.delivery.items']['selected'] = true;

                    }
                    $_content['tabs']['supplier.delivery.items_mismatch']['class'] = '';

                    break;
                default:
                    if ($data['tab'] == 'supplier.delivery.costing' || $data['tab'] == 'supplier.delivery.items_done' || $data['tab'] == 'supplier.delivery.items_mismatch') {
                        $data['tab'] = 'supplier.delivery.items';

                        $_content['tabs']['supplier.delivery.items']['selected'] = true;

                    }


                    break;

            }
        } elseif ($data['module'] == 'production') {
            $_content['tabs']['supplier.delivery.items_done']['class'] = 'hide';
            $_content['tabs']['supplier.delivery.costing']['class']    = 'hide';

            $_content['tabs']['supplier.delivery.items_mismatch']['class'] = 'hide';
            $_content['tabs']['supplier.delivery.items']['class']          = '';

            switch ($data['_object']->get('Supplier Delivery State')) {


                //'InProcess','Consolidated','Dispatched','Received','Checked','Placed','Costing','Cancelled','InvoiceChecked'

                case 'Costing':


                    $_content['tabs']['supplier.delivery.costing']['class'] = '';
                    $_content['tabs']['supplier.delivery.items']['class']   = 'hide';
                    if ($data['tab'] == 'supplier.delivery.items' || $data['tab'] == 'supplier.delivery.items_done') {
                        $data['tab'] = 'supplier.delivery.costing';

                        $_content['tabs']['supplier.delivery.costing']['selected'] = true;
                    }

                    $_content['tabs']['supplier.delivery.items_mismatch']['class'] = '';


                    break;
                case 'InvoiceChecked':

                    $_content['tabs']['supplier.delivery.items_done']['class'] = '';

                    $_content['tabs']['supplier.delivery.costing']['class'] = 'hide';
                    $_content['tabs']['supplier.delivery.items']['class']   = 'hide';
                    if ($data['tab'] == 'supplier.delivery.costing' || $data['tab'] == 'supplier.delivery.items') {
                        $data['tab'] = 'supplier.delivery.items_done';

                        $_content['tabs']['supplier.delivery.items_done']['selected'] = true;
                    }
                    $_content['tabs']['supplier.delivery.items_mismatch']['class'] = '';

                    break;
                case 'Received':

                    if ($data['tab'] == 'supplier.delivery.costing' || $data['tab'] == 'supplier.delivery.items_done') {
                        $data['tab'] = 'supplier.delivery.items';

                        $_content['tabs']['supplier.delivery.items']['selected'] = true;

                    }

                    if ($data['_object']->get('Supplier Delivery Number Received and Checked Items') > 0) {
                        $_content['tabs']['supplier.delivery.items_mismatch']['class'] = '';

                    }

                    break;

                case 'Placed':
                case 'Checked':

                    if ($data['tab'] == 'supplier.delivery.costing' || $data['tab'] == 'supplier.delivery.items_done') {
                        $data['tab'] = 'supplier.delivery.items';

                        $_content['tabs']['supplier.delivery.items']['selected'] = true;

                    }
                    $_content['tabs']['supplier.delivery.items_mismatch']['class'] = '';

                    break;
                default:
                    if ($data['tab'] == 'supplier.delivery.costing' || $data['tab'] == 'supplier.delivery.items_done' || $data['tab'] == 'supplier.delivery.items_mismatch') {
                        $data['tab'] = 'supplier.delivery.items';

                        $_content['tabs']['supplier.delivery.items']['selected'] = true;

                    }


                    break;

            }
        }
    } elseif ($data['section'] == 'webpage') {

        if ($data['module'] == 'products') {
            if (!in_array(
                    $data['_object']->get('Webpage Code'), array(
                                                             'register.sys',
                                                             'login.sys',
                                                             'checkout.sys'
                                                         )
                )

                or ($data['_object']->get('Webpage Code') == 'register.sys' and $data['_object']->get('Website Registration Type') == 'Closed')

            ) {
                $_content['subtabs'] = '';

            } else {

            }

        }


    } elseif ($data['section'] == 'website') {


        if ($data['website']->get('Website Status') == 'Active') {
            $_content['subtabs']['website.ready_webpages']['class'] = 'hide';
        } else {
            $_content['subtabs']['website.offline_webpages']['class'] = 'hide';
            $_content['subtabs']['website.online_webpages']['class']  = 'hide';

        }


    } elseif ($data['section'] == 'delivery_note') {


        if ($data['tab'] == 'set_delivery_note.fast_track_packing') {
            $data['tab']                                                   = 'delivery_note.fast_track_packing';
            $_content['tabs']['delivery_note.fast_track_packing']['class'] = '';

        } elseif ($data['tab'] == 'delivery_note.fast_track_packing') {
            $data['tab']                                                   = 'delivery_note.items';
            $_content['tabs']['delivery_note.fast_track_packing']['class'] = 'hide';

        } else {
            $_content['tabs']['delivery_note.fast_track_packing']['class'] = 'hide';

        }


        if ($data['_object']->get('State Index') <= 10) {
            $_content['tabs']['delivery_note.picking_aid']['class'] = 'hide';


        }

        //  exit;


    } elseif ($data['section'] == 'poll_query') {


        if ($data['_object']->get('Customer Poll Query Type') == 'Options') {

            if ($data['tab'] == 'poll_query.answers') {
                $data['tab'] = 'poll_query.options';
            }

            $_content['tabs']['poll_query.answers']['class'] = 'hide';
            $_content['tabs']['poll_query.options']['class'] = '';

            $_content['tabs']['poll_query.options']['selected'] = true;

        } else {
            if ($data['tab'] == 'poll_query.options') {
                $data['tab'] = 'poll_query.answers';
            }
            $_content['tabs']['poll_query.options']['class'] = 'hide';
            $_content['tabs']['poll_query.answers']['class'] = '';

            $_content['tabs']['poll_query.answers']['selected'] = true;

        }


    } elseif ($data['section'] == 'return') {


        $_content['tabs']['return.items_done']['class'] = 'hide';

        $_content['tabs']['return.items']['class'] = '';

        switch ($data['_object']->get('Supplier Delivery State')) {


            case 'InvoiceChecked':

                $_content['tabs']['return.items_done']['class'] = '';

                $_content['tabs']['return.items']['class'] = 'hide';
                if ($data['tab'] == 'return.items') {
                    $data['tab'] = 'return.items_done';

                    $_content['tabs']['return.items_done']['selected'] = true;
                }

                break;
            case 'Dispatched':
            case 'Received':
                if ($data['tab'] == 'return.items_done') {
                    $data['tab'] = 'return.items';

                    $_content['tabs']['return.items']['selected'] = true;

                }

                if ($data['_object']->get('Supplier Delivery Number Received and Checked Items') > 0) {
                    $_content['tabs']['return.items_mismatch']['class'] = '';

                }

                break;

            case 'Placed':
            case 'Checked':

                if ($data['tab'] == 'return.costing' || $data['tab'] == 'return.items_done') {
                    $data['tab'] = 'return.items';

                    $_content['tabs']['return.items']['selected'] = true;

                }
                $_content['tabs']['return.items_mismatch']['class'] = '';

                break;
            default:


                break;

        }

    } elseif ($data['section'] == 'deleted_invoice') {


        if ($data['tab'] != 'invoice.history') {
            $data['tab']                                           = 'deleted_invoice.items';
            $_content['tabs']['deleted_invoice.items']['selected'] = true;
        }


    } elseif ($data['section'] == 'invoice') {


        if ($data['tab'] == 'deleted_invoice.items') {
            $data['tab']                                   = 'invoice.items';
            $_content['tabs']['invoice.items']['selected'] = true;
        }


    }


    // print_r($_content['subtabs']);

    if (empty($_content['subtabs'])) {
        $_content['subtabs'] = array();
    }

    $smarty->assign('_content', $_content);


    if ($data['section'] == 'warehouse') {
        if (!$user->can_view('locations') or !in_array(
                $data['key'], $user->warehouses
            )) {
            return array(
                $data,
                ''
            );
        }

    }


    $html = $smarty->fetch('tabs.tpl');

    return array(
        $data,
        $html
    );
}




