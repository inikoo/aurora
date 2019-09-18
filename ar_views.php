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


$tipo = $_REQUEST['tipo'];


switch ($tipo) {
    case 'views':
        get_view($db, $smarty, $user, $account, $modules, $redis);
        break;
    case 'widget_details':
        get_widget_details($db, $smarty, $user, $account, $modules);
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
                $db, $smarty, $user, $account, $data['tab'], $data['subtab'], $data['state']
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

function get_widget_details($db, $smarty, $user, $account, $modules) {

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
        $db, $smarty, $user, $account, $data['widget'], '', $state, $metadata = false
    );
    $response = array(
        'state'          => 200,
        'widget_details' => $html,
    );
    echo json_encode($response);

}


function get_view($db, $smarty, $user, $account, $modules, $redis) {


    global $session;

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

    $state = parse_request($data, $db, $modules, $account, $user);


    $state['current_website']    = $session->get('current_website');
    $state['current_store']      = $session->get('current_website');
    $state['current_warehouse']  = $session->get('current_warehouse');
    $state['current_production'] = (!empty($session->get('current_production')) ? $session->get('current_production') : '');


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
            //$state['current_website'] = $_parent->id;


            $store                    = get_object('Store', $_parent->get('Website Store Key'));
            $state['current_store']   = $store->id;
            $state['current_website'] = $_parent->id;

            break;
        case 'page':
            $_parent = get_object('Webpage', $state['parent_key']);
            $website = get_object('Website', $_parent->get('Webpage Website Key'));
            //$state['current_website'] = $website->id;
            $website                  = $website;
            $state['current_website'] = $website->id;

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
                $state['module'], array(
                                    'customers_server',
                                    'orders_server'
                                )
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


            include_once 'class.Website.php';
            $website                  = new Website($_object->get('Website Key'));
            $state['current_website'] = $website->id;
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

        if ($state['object'] == 'customer' and $state['tab'] != 'customer.new') {

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
            if ($state['_parent'] == 'store' and !$state['_parent']->get('Store Website Key')) {
                $state = array(
                    'old_state'  => $state,
                    'module'     => 'products',
                    'section'    => 'no_website',
                    'tab'        => 'no_website',
                    'subtab'     => '',
                    'parent'     => $state['parent'],
                    'parent_key' => $state['parent_key'],
                    'object'     => '',
                    'store'      => $store,
                    'website'    => $website,
                    'warehouse'  => $warehouse,
                    'key'        => '',
                    'request'    => $state['request']
                );
            }


        }

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

            if ($state['object'] == 'purchase_order' and $_object->get('Purchase Order Production') == 'Yes') {


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


            if ($state['object'] == 'supplierdelivery' and $_object->get('Supplier Delivery Production') == 'Yes') {


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
        //  print_r($state);

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
            }
            if ($state['object'] == 'barcode') {
                $_object          = new Barcode('deleted', $state['key']);
                $state['_object'] = $_object;
                if ($_object->id) {
                    $state['section'] = 'deleted_barcode';
                    $state['tab']     = 'barcode.history';

                }
            }
            if ($state['object'] == 'Customer_Poll_Query_Option') {
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

            if (!$_object->id) {

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

    //if ($state['module'] == 'production') {
    //    $production = $state['_object'];
    // }
    //print microtime_float()-$timer."<br>\n";$timer=microtime_float();


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
    //if (is_object($production) and $production->id) {
    //    $state['current_production'] = $production->id;
    //}


    $sql = sprintf(
        'INSERT INTO `User System View Fact`  (`User Key`,`Date`,`Module`,`Section`,`Tab`,`Parent`,`Parent Key`,`Object`,`Object Key`)  VALUES (%d,%s,%s,%s,%s,%s,%s,%s,%s)', $user->id, prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql($state['module']),
        prepare_mysql($state['section']), prepare_mysql(
            ($state['subtab'] != '' ? $state['subtab'] : $state['tab'])
        ), prepare_mysql($state['parent']), prepare_mysql($state['parent_key']), prepare_mysql($state['object']), prepare_mysql($state['key'])

    );
    $db->exec($sql);

    //$_SESSION['request'] = $state['request'];


    if (isset($state['current_store'])) {
        $session->set('current_store', $state['current_store']);

    }

    // todo implement correctly if multiwarehouses ever done, special care has to be done when visiting warehouses servers becuse current_warehouse will be set to null, and if then jump to a invengtory/part it may be still null causing avok
    //if (isset($state['current_warehouse'])) {$session->set('current_warehouse', $state['current_warehouse']);}

    // todo implement correctly if multiprouction ever done,

    //if (isset($state['current_production'])) {$session->set('current_production', $state['current_production']);}

    $response = array('app_state' => array());

    list($state, $response['view_position']) = get_view_position(
        $db, $state, $user, $smarty, $account
    );


    if ($data['old_state']['module'] != $state['module'] or $reload) {
        $response['menu'] = get_menu($state, $user, $smarty, $db, $account);

    }


    if ($data['old_state']['module'] != $state['module'] or $data['old_state']['section'] != $state['section'] or $data['old_state']['parent_key'] != $state['parent_key'] or $data['old_state']['key'] != $state['key'] or $reload or isset(
            $data['metadata']['reload_showcase']

        )

    ) {


        $_navigation = get_navigation($user, $smarty, $state, $db, $account);


        if (is_array($_navigation)) {
            $response['navigation']     = $_navigation[0];
            $response['web_navigation'] = $_navigation[1];
        } else {
            $response['navigation']     = $_navigation;
            $response['web_navigation'] = '';

        }


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
                (isset($modules[$state['module']]['sections'][$state['section']]['showcase']) ? $modules[$state['module']]['sections'][$state['section']]['showcase'] : $state['object']), $state, $smarty, $user, $db, $account
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
                                        $web_location= '<i class="fal fa-fw fa-newspaper"></i> '._('Newsletters');
                                        break;
                                    case 'Marketing':
                                        $web_location= '<i class="fal fa-fw fa-bullhorn"></i> '._('Mailshots');
                                        break;
                                }

                                break;
                        }
                        break;

                }





                $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', $web_location);
                // $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', $state['module'].' * '.$state['section']);
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
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-sitemap"></i> '._("Products's categories").' '.$store->get('Code'));
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
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-graduation-cap"></i> '._("Customer's insigths").' '.$store->get('Code'));
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
                    default:
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-users"></i> '._('Customers').' '.$store->get('Code'));

                }

                break;
            case 'customers_server ':
                switch ($state['section']) {

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
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-file-alt"></i> '._('Invoices').' '.$store->get('Code'));
                        break;
                    case 'payments':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-credit-cart"></i> '._('Payments').' '.$store->get('Code'));
                        break;
                    case 'credits':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-piggy-bank"></i> '._('Credit vault').' '.$store->get('Code'));
                        break;
                    case 'deleted_invoices':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-file-alt"></i> '._('Deleted invoices').' '.$store->get('Code'));
                        break;

                }
            case 'accounting_server':
                switch ($state['section']) {

                    case 'invoices':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-file-alt"></i> '._('Invoices').' ('._('All').')');
                        break;
                    case 'deleted_invoices':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-file-alt"></i> '._('Deleted invoices').' ('._('All').')');
                        break;
                    case 'payments':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-credit-cart"></i> '._('Payments').' ('._('All').')');
                        break;
                    case 'credits':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-piggy-bank"></i> '._('Credit vault').' ('._('All').')');
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
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-industry"></i> '._('Production sheets'));
                        break;
                    case 'settings':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-industry"></i> '._('Settings'));
                        break;
                    default:

                }
            case 'utils':
                switch ($state['section']) {
                    case 'fire':
                        $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'web_location', '<i class="fal fa-fw fa-fire"></i> '._('Fire'));
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

    $response['tab'] = get_tab($db, $smarty, $user, $account, $state['tab'], $state['subtab'], $state, $data['metadata']);

    if ($old_weblocation != (isset($state['module']) ? $state['module'] : '').'|'.(isset($state['section']) ? $state['section'] : '')) {

        $context = new ZMQContext();
        $socket  = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://localhost:5555");

        require_once 'utils/real_time_functions.php';
        $real_time_users = get_users_read_time_data($redis, $account);


        $socket->send(
            json_encode(
                array(
                    'channel' => 'real_time.'.strtolower($account->get('Account Code')),

                    'iu' => $real_time_users,

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


    $response['state'] = 200;

    $response['app_state'] = $state;


    // print_r($response);
    // exit;

    echo json_encode($response);

}

function get_tab($db, $smarty, $user, $account, $tab, $subtab, $state = false, $metadata = false) {

    global $session;

    $html = '';


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

    if (is_array($state) and !(preg_match('/\_edit$/', $tab) or preg_match('/\.wget$/', $_tab))) {


        // $_SESSION['state'][ $state['module']  ][ $state['section']  ]  ['tab'] = $_tab;

        $tmp = $session->get('state');

        $tmp[$state['module']][$state['section']]['tab'] = $_tab;

        // print $_subtab;

        $session->set('state', $tmp);


        if (!empty($_subtab)) {

            $tmp        = $session->get('tab_state');
            $tmp[$_tab] = $_subtab;
            $session->set('tab_state', $tmp);


        }

    }

    return $html;

}


/**
 * @param $showcase
 * @param $data
 * @param $smarty  \Smarty
 * @param $user    \User
 * @param $db      \PDO
 * @param $account \Account
 *
 * @return mixed|string
 */
function get_object_showcase($showcase, $data, $smarty, $user, $db, $account) {


    $title        = '';
    $web_location = '';
    if (preg_match('/\_edit$/', $data['tab'])) {
        return array(
            '',
            ''
        );
    }


    switch ($showcase) {
        case 'material':
            include_once 'showcase/material.show.php';
            $html = get_showcase($data, $smarty, $user, $db);
            break;
        case 'webpage':
            include_once 'showcase/webpage.show.php';
            $html         = get_webpage_showcase($data, $smarty, $user, $db);
            $title        = $data['_object']->get('Code');
            $web_location = '<i class="fal fa-fw fa-browser"></i> '.$title;

            break;

        case 'website':
            include_once 'showcase/website.show.php';
            $html         = get_website_showcase($data, $smarty, $user, $db);
            $title        = $data['_object']->get('Code');
            $web_location = '<i class="fal fa-fw fa-globe"></i> '.$title;

            break;
        case 'dashboard':
            $html         = '';
            $web_location = '<i class="fal fa-fw fa-tachometer-alt"></i> '._('Dashboard');
            break;

        case 'upload':
            include_once 'showcase/upload.show.php';
            $html = get_upload_showcase($data, $smarty, $user, $db);
            break;
        case 'purchase_order':
            include_once 'showcase/supplier.order.show.php';
            $html         = get_supplier_order_showcase($data, $smarty, $user, $db);
            $title        = $data['_object']->get('Public ID');
            $web_location = '<i class="fal fa-fw fa-clipboard"></i> '.$data['_object']->get('Public ID');
            if ($data['module'] == 'production') {
                $web_location .= ' <i class="fal fa-fw fa-industry"></i>';
            }

            break;
        case 'campaign':
            include_once 'showcase/campaign.show.php';
            $html = get_campaign_showcase($data, $smarty, $user, $db);
            break;
        case 'deal':
            include_once 'showcase/deal.show.php';
            $html = get_deal_showcase($data, $smarty, $user, $db);
            break;
        case 'deal_component':
            include_once 'showcase/deal_component.show.php';
            $html = get_deal_component_showcase($data, $smarty, $user, $db);
            break;
        case 'store':

            include_once 'showcase/store.show.php';
            $html         = get_store_showcase($data, $smarty, $user, $db);
            $title        = $data['_object']->get('Code');
            $web_location = '<i class="fal fa-fw fa-store"></i> '.$title;
            break;
        case 'products_special_categories':
            include_once 'showcase/products_special_categories.show.php';
            $html         = get_products_special_categories_showcase(
                $data, $smarty, $user, $db
            );
            $title        = _("Product's categories").' '.$data['store']->get('Code');
            $web_location = '<i class="fal fa-fw fa-sitemap"></i> '.$title;
            break;
        case 'account':
            if ($data['module'] == 'products_server') {
                include_once 'showcase/stores.show.php';
                $html         = get_stores_showcase($data, $smarty, $user, $db);
                $title        = _('Stores');
                $web_location = '<i class="fal fa-fw fa-store"></i> '.$title;
            } else {

                include_once 'showcase/account.show.php';
                $html         = get_account_showcase($data, $smarty, $user, $db);
                $title        = _('Account');
                $web_location = '<i class="fal fa-fw fa-toolbox"></i> '.$title;
            }
            break;
        case 'product':

            if ($data['module'] == 'customers') {
                include_once 'showcase/customer.product.show.php';
                $html = get_customer_product_showcase($data, $smarty, $user, $db);
            } else {
                include_once 'showcase/product.show.php';
                $html = get_product_showcase($data, $smarty, $user, $db);
            }

            $title        = $data['_object']->get('Code');
            $web_location = '<i class="fal fa-fw fa-cube"></i> '.$title;

            break;
        case 'part':
            include_once 'showcase/part.show.php';
            $html         = get_part_showcase($data, $smarty, $user, $db);
            $title        = $data['_object']->get('Reference');
            $web_location = '<i class="fal fa-fw fa-box"></i> '.$title;

            break;
        case 'supplier_part':
            include_once 'showcase/supplier_part.show.php';
            $html         = get_supplier_part_showcase($data, $smarty, $user, $db);
            $title        = $data['_object']->get('Reference');
            $web_location = '<i class="fal fa-fw fa-hand-receiving"></i> '.$title;

            break;
        case 'employee':
            include_once 'showcase/employee.show.php';
            $html         = get_employee_showcase($data, $smarty, $user, $db);
            $title        = $data['_object']->get('Name');
            $web_location = '<i class="fal fa-fw fa-hand-rock"></i> '.$data['_object']->get('ID');

            break;
        case 'contractor':
            include_once 'showcase/contractor.show.php';
            $html         = get_contractor_showcase($data, $smarty, $user, $db);
            $title        = $data['_object']->get('Name');
            $web_location = '<i class="fal fa-fw fa-hand-spock"></i> '.$data['_object']->get('ID');

            break;
        case 'customer':
            include_once 'showcase/customer.show.php';
            $html         = get_customer_showcase($data, $smarty, $user, $db);
            $title        = 'C'.$data['_object']->get('Formatted ID');
            $web_location = '<i class="fal fa-fw fa-user"></i> '.$title;

            break;
        case 'supplier':
            include_once 'showcase/supplier.show.php';
            $html         = get_supplier_showcase($data, $smarty, $user, $db);
            $title        = $data['_object']->get('Code');
            $web_location = '<i class="fal fa-fw fa-hand-holding-box"></i> '.$title;
            break;
        case 'agent':
            include_once 'showcase/agent.show.php';
            $html = get_agent_showcase($data, $smarty, $user, $db);
            break;
        case 'order':
            include_once 'showcase/order.show.php';
            $html         = get_order_showcase($data, $smarty, $user, $db);
            $title        = $data['_object']->get('Public ID');
            $web_location = '<i class="fal fa-fw fa-shopping-cart"></i> '.$title;


            break;
        case 'invoice':
        case 'refund':
            include_once 'showcase/invoice.show.php';
            $html         = get_invoice_showcase($data, $smarty, $user, $db);
            $title        = $data['_object']->get('Public ID');
            $web_location = '<i class="fal fa-fw fa-file-alt"></i> '.$title;

            break;
        case 'delivery_note':
            include_once 'showcase/delivery_note.show.php';
            $html         = get_delivery_note_showcase($data, $smarty, $user, $db);
            $title        = $data['_object']->get('ID');
            $web_location = '<i class="fal fa-fw fa-truck"></i> '.$title;

            break;
        case 'user':
            include_once 'showcase/user.show.php';
            $html  = get_user_showcase($data, $smarty, $user, $db);
            $title = $data['_object']->get('Handle');


            if ($user->id == $data['_object']->id and preg_match('/profile/', $data['request'])) {
                $web_location = '<i class="fal fa-fw fa-user-circle"></i> '._('Profile');

            } else {
                $web_location = '<i class="fal fa-fw fa-terminal"></i> '.$title;

            }


            break;
        case 'warehouse':
            include_once 'showcase/warehouse.show.php';

            if (!$user->can_view('locations') or !in_array($data['key'], $user->warehouses)) {
                $html = get_locked_warehouse_showcase($data, $smarty, $user, $db);

            } else {
                $html = get_warehouse_showcase($data, $smarty, $user, $db);
            }

            $title        = 'W '.$data['_object']->get('Code');
            $web_location = '<i class="fal fa-fw fa-warehouse-alt"></i> '.$title;


            break;
        case 'warehouse_area':
            include_once 'showcase/warehouse_area.show.php';

            if (!$user->can_view('locations') or !in_array($data['warehouse']->id, $user->warehouses)) {
                $html = get_locked_warehouse_area_showcase($data, $smarty, $user, $db);

            } else {
                $html = get_warehouse_area_showcase($data, $smarty, $user, $db);
            }
            break;
        case 'location':
            include_once 'showcase/location.show.php';

            if (!$user->can_view('locations') or !in_array($data['warehouse']->id, $user->warehouses)) {
                $html = get_locked_location_showcase($data, $smarty, $user, $db);

            } else {
                $html = get_location_showcase($data, $smarty, $user, $db);
            }
            $title        = $data['_object']->get('Code');
            $web_location = '<i class="fal fa-fw fa-pallet"></i> '.$title;

            break;


        case 'timesheet':
            include_once 'showcase/timesheet.show.php';
            $html = get_timesheet_showcase($data, $smarty, $user, $db);
            break;
        case 'attachment':
            include_once 'showcase/attachment.show.php';
            $html = get_attachment_showcase($data, $smarty, $user, $db);
            break;
        case 'manufacture_task':
            include_once 'showcase/manufacture_task.show.php';
            $html = get_manufacture_task_showcase($data, $smarty, $user, $db);
            break;
        case 'upload':
            include_once 'showcase/upload.show.php';
            $html = get_upload_showcase($data, $smarty, $user, $db);
            break;
        case 'barcode':
            include_once 'showcase/barcode.show.php';
            $html = get_barcode_showcase($data, $smarty, $user, $db);
            break;
        case 'category':

            if ($data['_object']->get('Category Scope') == 'Product') {


                if ($data['_object']->id == $data['store']->get('Store Family Category Key')) {
                    $html         = '';
                    $title        = _('Families').' '.$data['store']->get('Code');
                    $web_location = '<i class="fal fa-fw fa-sitemap"></i> '.$title;
                } else {
                    if ($data['_object']->id == $data['store']->get('Store Department Category Key')) {
                        $html         = '';
                        $title        = _('Departments').' '.$data['store']->get('Code');
                        $web_location = '<i class="fal fa-fw fa-sitemap"></i> '.$title;
                    } elseif ($data['_object']->get('Root Key') == $data['store']->get('Store Family Category Key')) {
                        include_once 'showcase/family.show.php';
                        $html         = get_family_showcase($data, $smarty, $user, $db);
                        $title        = $data['_object']->get('Code').' '.$data['store']->get('Code');
                        $web_location = '<i class="fal fa-fw fa-sitemap"></i> '.$title;
                    } elseif ($data['_object']->get('Root Key') == $data['store']->get('Store Department Category Key')) {
                        include_once 'showcase/department.show.php';
                        $html         = get_department_showcase($data, $smarty, $user, $db);
                        $title        = $data['_object']->get('Code').' '.$data['store']->get('Code');
                        $web_location = '<i class="fal fa-fw fa-sitemap"></i> '.$title;
                    } else {

                        $html         = '';
                        $title        = $data['_object']->get('Code').' '.$data['store']->get('Code');
                        $web_location = '<i class="fal fa-fw fa-sitemap"></i> '.$title;
                    }
                }


            } elseif ($data['_object']->get('Category Scope') == 'Part') {

                if ($data['_object']->id == $account->get('Account Part Family Category Key')) {
                    include_once 'showcase/part_families.show.php';
                    $html         = get_part_familes_showcase($data, $smarty, $user, $db);
                    $title        = _("Families").' <i class="fal fa-fw fa-box"></i>';
                    $web_location = '<i class="fal fa-fw fa-sitemap"></i> '.$title;


                } elseif ($data['_object']->get('Root Key') == $account->get('Account Part Family Category Key')) {
                    include_once 'showcase/part_family.show.php';
                    $html         = get_part_family_showcase($data, $smarty, $user, $db);
                    $title        = $data['_object']->get('Code').' <i class="fal fa-fw fa-box"></i>';
                    $web_location = '<i class="fal fa-fw fa-sitemap"></i> '.$title;

                } else {
                    return '_';
                }

            } elseif ($data['_object']->get('Category Scope') == 'Supplier') {
                include_once 'showcase/supplier_category_showcase.show.php';
                $html = get_supplier_category_showcase(
                    $data, $smarty, $user, $db
                );

            } elseif ($data['_object']->get('Category Scope') == 'Invoice') {
                include_once 'showcase/invoice_category_showcase.show.php';
                $html = get_invoice_category_showcase(
                    $data, $smarty, $user, $db
                );

            } else {
                return '_';
            }

            $title = $data['_object']->get('Code');

            break;
        case 'PurchaseOrderItem':
            include_once 'showcase/supplier.order.item.show.php';
            $html = get_showcase($data, $smarty, $user, $db);
            break;
        case 'supplierdelivery':
        case 'supplier_delivery':


            if ($user->get('User Type') == 'Agent') {
                include_once 'showcase/agent_delivery.show.php';
                $html = get_showcase($data, $smarty, $user, $db);
            } else {
                include_once 'showcase/supplier.delivery.show.php';
                $html = get_showcase($data, $smarty, $user, $db);
            }
            $title = $data['_object']->get('Public ID');

            if ($data['module'] == 'production') {
                $web_location = '<i class="fal fa-fw fa-clipboard-check"></i> '.$data['_object']->get('Public ID');

                $web_location .= ' <i class="fal fa-fw fa-industry"></i>';
            } else {
                $web_location = '<i class="fal fa-fw fa-truck"></i> '.$data['_object']->get('Public ID');

            }


            break;
        case 'position':
            include_once 'showcase/job_position.show.php';
            $html = get_showcase($data, $smarty, $user, $db);
            break;
        case 'webpage_type':
            include_once 'showcase/webpage_type.show.php';
            $html = get_showcase($data, $smarty, $user, $db);
            break;

        case 'payment_account':
            include_once 'showcase/payment_account.show.php';
            $html = get_payment_account_showcase($data, $smarty, $user, $db);
            break;
        case 'payment_service_provider':
            include_once 'showcase/payment_service_provider.show.php';
            $html = get_payment_service_provider_showcase($data, $smarty, $user, $db);
            break;
        case 'charge':
            include_once 'showcase/charge.show.php';
            $html = get_charge_showcase($data, $smarty, $user, $db);
            break;
        case 'timeseries_record':
            include_once 'showcase/timeseries_record.show.php';
            $html = get_timeseries_record_showcase($data, $smarty, $user, $db, $account);
            break;
        case 'mailshot':
            include_once 'showcase/email_campaign.show.php';
            $html = get_email_campaign_showcase($data, $smarty, $user, $db, $account);
            $web_location = '<i class="fal fa-fw fa-mail-bulk"></i> '.(strlen($data['_object']->get('Name'))>17?substr($data['_object']->get('Name'),0,20).'&hellip;':$data['_object']->get('Name'));

            break;

        case 'newsletter':
            include_once 'showcase/email_campaign.show.php';
            $html = get_email_campaign_showcase($data, $smarty, $user, $db, $account);

            break;

        case 'api_key':
        case 'deleted_api_key':
            include_once 'showcase/api_key.show.php';
            $html = get_api_key_showcase($data, $smarty, $user, $db, $account);
            break;
        case 'Customer_Poll_Query':
            include_once 'showcase/customer_poll_query.show.php';
            $html = get_customer_poll_query_showcase($data, $smarty, $user, $db, $account);
            break;
        case 'Customer_Poll_Query_Option':
            include_once 'showcase/customer_poll_query_option.show.php';
            $html = get_customer_poll_query_option_showcase($data, $smarty, $user, $db, $account);
            break;
        case 'list':
            include_once 'showcase/list.show.php';
            $html = get_list_showcase($data, $smarty, $user, $db);
            break;
        case 'email_campaign_type':
            include_once 'showcase/email_campaign_type.show.php';
            $html = get_email_campaign_type_showcase($data, $smarty, $user, $db);
            break;
        case 'prospect':
            include_once 'showcase/prospect.show.php';
            $html = get_prospect_showcase($data, $smarty, $user, $db);
            break;
        case 'email_tracking':
            include_once 'showcase/email_tracking.show.php';
            $html = get_prospect_email_tracking($data, $smarty, $user, $db);
            break;
        case 'email_template':
            include_once 'showcase/email_template.show.php';
            $html = get_email_template_showcase($data, $smarty, $user, $db);
            break;
        case 'sales_representative':
            include_once 'showcase/sales_representative.show.php';
            $html = get_sales_representative_showcase($data, $smarty, $user, $db);
            break;
        case 'agent_supplier_order':
            include_once 'showcase/agent_supplier_order.show.php';
            $html = get_agent_supplier_order_showcase($data, $smarty, $user, $db);
            break;
        case 'payment':
            include_once 'showcase/payment.show.php';
            $html = get_payment_showcase($data, $smarty, $user, $db);
            break;
        case 'purge':
            include_once 'showcase/purge.show.php';
            $html = get_purge_showcase($data, $smarty, $user, $db);
            break;
        case 'shipper':
            include_once 'showcase/shipper.show.php';
            $html = get_shipper_showcase($data, $smarty, $user, $db);
            break;
        case 'production_part':
            include_once 'showcase/production_part.show.php';
            $html = get_production_part_showcase($data, $smarty);
            break;
        case 'shipping_zone_schema':
            $html         = '';
            $title        = $data['_object']->get('Label');
            $web_location = '<i class="fal fa-fw fa-bring-front"></i> '._('Shipping schema');
            break;
        default:
            $html = $data['object'].' -> '.$data['key'];
            break;
    }


    return array(
        $html,
        $title,
        $web_location
    );

}


function get_menu($data, $user, $smarty, $db, $account) {

    include_once 'navigation/menu.php';

    return $html;


}


function get_navigation($user, $smarty, $data, $db, $account) {


    switch ($data['module']) {

        case ('dashboard'):
            require_once 'navigation/dashboard.nav.php';

            return get_dashboard_navigation($data, $smarty, $user, $db, $account);
            break;
        case ('products_server'):
            require_once 'navigation/products.nav.php';
            switch ($data['section']) {
                case 'stores':
                    return get_stores_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

                case 'products':
                    return get_products_all_stores_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case 'store.new':
                    return get_new_store_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

            }
            break;
        case ('products'):
            require_once 'navigation/products.nav.php';
            require_once 'navigation/websites.nav.php';
            require_once 'navigation/marketing.nav.php';


            switch ($data['section']) {

                case 'store':
                    return get_store_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

                case 'products':
                    return get_products_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case 'product':
                    return get_product_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case 'product.new':
                    return get_new_product_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

                case 'services':
                    return get_services_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case 'service':
                    return get_service_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case 'service.new':
                    return get_new_service_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

                case 'dashboard':
                    return get_store_dashboard_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

                case ('categories'):
                    return get_products_categories_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('category'):
                    return get_products_category_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('main_category.new'):
                    return get_products_new_main_category_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('order'):
                    return get_order_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('offers'):
                    return get_marketing_navigation($data, $smarty, $user, $db, $account);
                    break;

                case ('marketing'):
                    return get_marketing_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;


                case ('campaign'):
                case ('campaign_order_recursion'):
                case ('vouchers'):
                    return get_campaign_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('campaign.new'):
                    return get_new_campaign_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('deal.new'):
                    return get_new_deal_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('deal_component.new'):
                    return get_new_deal_component_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('deal'):
                    return get_deal_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('deal_component'):
                    return get_deal_component_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('enewsletters'):
                    return get_enewsletters_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('mailshots'):
                    return get_mailshots_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('marketing_post'):

                    return get_marketing_post_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('charge'):
                    return get_charge_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('shipping_zone'):
                    return get_shipping_zone_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('shipping_option'):
                    return get_shipping_option_navigation($data, $smarty, $user, $db, $account);
                    break;

                case ('charge.new'):
                    return get_charge_new_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('shipping_zone.new'):
                    return get_shipping_zone_new_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('shipping_option.new'):
                    return get_shipping_option_new_navigation($data, $smarty, $user, $db, $account);
                    break;
                case 'email_campaign_type':


                    if ($data['_object']->get('Email Campaign Type Scope') == 'User Notification') {
                        return get_user_notification_email_campaign_type_navigation($data, $smarty, $user, $db, $account);

                    } else {
                        return get_marketing_email_campaign_type_navigation($data, $smarty, $user, $db, $account);

                    }
                    break;
                case ('mailshot'):
                    return get_mailshot_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('email_tracking'):
                    return get_email_tracking_navigation($data, $smarty, $user, $db, $account, $account);
                    break;
                case 'shipping_zone_schema':
                    return get_shipping_zone_schema_navigation($data, $smarty, $user, $db, $account, $account);
                    break;

                case ('mailshot.new'):
                    return get_mailshot_new_navigation($data, $smarty, $user, $db, $account);
                    break;


            }
            break;
        case ('customers'):
            require_once 'navigation/customers.nav.php';


            switch ($data['section']) {

                case ('customer'):
                    return get_customer_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

                case ('customers'):
                    return get_customers_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('categories'):

                    return get_customers_categories_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('category'):

                    return get_customers_category_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('lists'):
                    return get_customers_lists_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('list'):
                    return get_customers_list_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('list.new'):
                    return get_new_list_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('dashboard'):
                    return get_customers_dashboard_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('customer_notifications'):

                    return get_customers_notifications_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('insights'):

                    return get_customers_insights_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('poll_query.new'):
                    return get_customers_new_poll_query_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('poll_query'):
                    return get_customers_poll_query_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('poll_query_option.new'):
                    return get_customers_new_poll_query_option_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('poll_query_option'):
                    return get_customers_poll_query_option_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('deleted_customer_poll_query_option'):
                    return get_customers_deleted_poll_query_option_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('pending_orders'):
                    return get_customers_pending_orders_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('customer.new'):
                    return get_new_customer_navigation(
                        $data, $smarty, $user, $db, $account, $account
                    );

                case ('prospect'):
                    return get_prospect_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;


                case 'email_campaign_type':
                    return get_email_campaign_type_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case 'mailshot':
                    return get_mailshot_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('prospects'):
                    return get_prospects_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('prospect.new'):
                    return get_new_prospect_navigation(
                        $data, $smarty, $user, $db, $account, $account
                    );
                    break;
                case ('prospect.compose_email'):
                    return get_new_prospect_compose_email_navigation(
                        $data, $smarty, $user, $db, $account, $account
                    );

                    break;
                case ('email_tracking'):
                    return get_email_tracking_navigation(
                        $data, $smarty, $user, $db, $account, $account
                    );
                    break;
                case ('prospects.template.new'):
                    return get_prospects_new_template_navigation(
                        $data, $smarty, $user, $db, $account, $account
                    );
                    break;
                case ('prospects.email_template'):
                    return get_prospects_email_template_navigation(
                        $data, $smarty, $user, $db, $account, $account
                    );
                    break;
                case ('product'):
                    return get_customer_product_navigation(
                        $data, $smarty, $user, $db, $account, $account
                    );
                    break;


            }

            break;
        case ('customers_server'):
            require_once 'navigation/customers.nav.php';
            switch ($data['section']) {
                case ('customers'):
                case('pending_orders'):
                    return get_customers_server_navigation(
                        $data, $smarty, $user, $db
                    );
                    break;
                case('email_communications'):
                    return get_email_communications_server_navigation(
                        $data, $smarty, $user, $db
                    );
                    break;
            }

            break;
        case ('orders_server'):
            require_once 'navigation/orders.nav.php';
            switch ($data['section']) {


                case ('dashboard'):
                    return get_orders_server_dashboard_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('group_by_store'):
                    return get_orders_server_group_by_store_navigation($data, $smarty, $user, $db, $account);
                    break;


                case ('orders'):

                    return get_orders_server_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('order'):
                    return get_order_navigation($data, $smarty, $user, $db, $account);
                    break;

                case ('mailshot'):
                    return get_abandoned_card_email_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

            }

            break;

        case ('delivery_notes_server'):
            require_once 'navigation/orders.nav.php';

            switch ($data['section']) {
                case ('pending_delivery_notes'):

                    return get_pending_delivery_notes_server_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('delivery_notes'):

                    return get_delivery_notes_server_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('group_by_store'):
                    return get_delivery_notes_server_group_by_store_navigation($data, $smarty, $user, $db, $account);
                    break;


            }

            break;

        case ('orders'):
            require_once 'navigation/orders.nav.php';
            switch ($data['section']) {


                case ('dashboard'):
                    return get_dashboard_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('basket_orders'):
                    return get_basket_orders_navigation($data, $smarty, $user, $db, $account);
                    break;

                case ('pending_orders'):
                    return get_pending_orders_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('orders'):

                    return get_orders_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('order'):
                    return get_order_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('invoice'):
                    include_once 'navigation/accounting.nav.php';

                    return get_invoice_navigation($data, $smarty, $user, $db, $account);
                    break;

                case ('refund'):
                    include_once 'navigation/accounting.nav.php';

                    return get_invoice_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('delivery_note'):
                    return get_delivery_note_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('invoices'):
                    return get_invoices_navigation($data, $smarty, $user, $db, $account);
                    break;

                case ('payment'):
                    return get_payment_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('mailshot'):
                    return get_abandoned_card_email_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('refund.new'):
                    return get_refund_new_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('replacement.new'):
                    return get_replacement_new_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('return.new'):
                    return get_return_new_navigation($data, $smarty, $user, $db, $account);
                    break;

                case ('replacement'):
                    return get_replacement_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('return'):
                    return get_return_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('email_tracking'):
                    return get_email_tracking_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('purge'):
                    return get_purge_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('deleted_invoice'):
                    include_once 'navigation/accounting.nav.php';

                    return get_deleted_invoice_navigation($data, $smarty, $user, $db, $account);
                    break;
                default:
                    return 'View not found x2'.$data['section'];

            }
            break;

        case ('delivery_notes'):
            require_once 'navigation/orders.nav.php';

            switch ($data['section']) {
                case ('delivery_notes'):
                    return get_delivery_notes_navigation($data, $smarty, $user, $db, $account);
                    break;

                case ('delivery_note'):
                    return get_delivery_note_navigation($data, $smarty, $user, $db, $account);
                    break;

                case ('invoice'):
                    include_once 'navigation/invoice.nav.php';

                    return get_invoice_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('order'):
                    return get_order_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('pick_aid'):
                    return get_pick_aid_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('pack_aid'):
                    return get_pack_aid_navigation($data, $smarty, $user, $db, $account);
                    break;
                default:
                    return 'View not found x1'.$data['section'];

            }
            break;
        case ('websites_server'):
            require_once 'navigation/websites.nav.php';
            switch ($data['section']) {
                case ('websites'):

                    return get_websites_navigation($data, $smarty, $user, $db, $account);
                    break;
            }

            break;
        case ('websites'):

            require_once 'navigation/websites.nav.php';

            switch ($data['section']) {


                case 'analytics':
                case 'settings':
                case 'workshop':
                case 'web_users':
                    return get_website_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('webpage'):
                    return get_webpage_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('webpage_type'):
                    return get_webpage_type_navigation($data, $smarty, $user, $db, $account);
                    break;
                case 'webpages':
                    return get_webpages_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('webpage.new'):
                    return get_new_webpage_navigation($data, $smarty, $user, $db, $account);
                    break;
                default:
                    return 'View not found '.$data['section'];

            }
            break;
        case ('marketing_server'):
            require_once 'navigation/marketing.nav.php';
            switch ($data['section']) {
                case ('marketing'):

                    return get_marketing_server_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
            }

            break;


        case ('reports'):

            require_once 'navigation/reports.nav.php';
            switch ($data['section']) {
                case ('reports'):
                    return get_reports_navigation($user, $smarty, $data);
                    break;
                case ('performance'):
                    return get_performance_navigation($user, $smarty, $data);
                    break;
                case ('pickers'):
                    return get_pickers_navigation($user, $smarty, $data);
                    break;
                case ('packers'):
                    return get_packers_navigation($user, $smarty, $data);
                    break;
                case ('sales_representatives'):
                    return get_sales_representatives_navigation($user, $smarty, $data);
                    break;
                case ('prospect_agents'):
                    return get_prospect_agents_navigation($user, $smarty, $data);
                    break;
                case ('sales_representative'):
                    return get_sales_representative_navigation($user, $smarty, $data);
                    break;
                case ('prospect_agent'):
                    return get_prospect_agent_navigation($user, $smarty, $data);
                    break;
                case ('prospect_agent_email_tracking'):
                    return get_prospect_agent_email_tracking_navigation($data, $smarty, $user, $db);
                    break;

                case ('lost_stock'):
                    return get_lost_stock_navigation($user, $smarty, $data);
                    break;
                case ('stock_given_free'):
                    return get_stock_given_free_navigation($user, $smarty, $data);
                    break;
                case ('sales'):
                    return get_sales_navigation($user, $smarty, $data);
                    break;
                case ('report_orders'):
                    return get_report_orders_navigation($user, $smarty, $data);
                    break;
                case ('report_orders_components'):
                    return get_report_orders_components_navigation($user, $smarty, $data);
                    break;
                case ('report_delivery_notes'):
                    return get_report_delivery_notes_navigation($user, $smarty, $data);
                    break;
                case ('intrastat'):
                    return get_intrastat_navigation($user, $smarty, $data);
                    break;
                case ('intrastat_orders'):
                    return get_intrastat_orders_navigation($user, $smarty, $data);
                    break;
                case ('intrastat_products'):
                    return get_intrastat_products_navigation($user, $smarty, $data);
                    break;
                case ('intrastat_imports'):
                    return get_intrastat_imports_navigation($user, $smarty, $data);
                    break;
                case ('intrastat_parts'):
                    return get_intrastat_parts_navigation($user, $smarty, $data);
                    break;
                case ('intrastat_deliveries'):
                    return get_intrastat_deliveries_navigation($user, $smarty, $data);
                    break;
                case ('tax'):
                    return get_tax_navigation($user, $smarty, $data);
                    break;
                case ('billingregion_taxcategory'):
                    return get_georegion_taxcategory_navigation(
                        $user, $smarty, $data
                    );
                    break;
                case ('billingregion_taxcategory.invoices'):
                    return get_invoices_georegion_taxcategory_navigation(
                        $user, $smarty, $data, 'invoices'
                    );
                    break;
                case ('billingregion_taxcategory.refunds'):
                    return get_invoices_georegion_taxcategory_navigation(
                        $user, $smarty, $data, 'refunds'
                    );
                    break;
                case ('ec_sales_list'):
                    return get_ec_sales_list_navigation($user, $smarty, $data);
                    break;
            }
        case ('production_server'):
            require_once 'navigation/production.nav.php';
            switch ($data['section']) {
                case ('production.suppliers'):
                    return get_suppliers_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

                case ('settings'):
                    return get_server_settings_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;


            }
            break;
        case ('production'):
            require_once 'navigation/production.nav.php';

            switch ($data['section']) {
                case ('delivery'):
                    return get_delivery_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('order'):
                    return get_purchase_order_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('production_supplier_orders'):
                    return get_production_supplier_purchase_orders_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('production_supplier_deliveries'):
                    return get_production_supplier_deliveries_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('dashboard'):
                    return get_dashboard_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('manufacture_tasks'):
                    return get_manufacture_tasks_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('manufacture_task.new'):
                    return get_new_manufacture_task_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('operatives'):
                    return get_operatives_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('batches'):
                    return get_batches_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('manufacture_task'):
                    return get_manufacture_task_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('operative'):
                    return get_operative_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('batch'):
                    return get_batch_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('settings'):
                    return get_settings_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('production_parts'):
                    return get_production_parts_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('production_part'):
                    return get_production_part_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('materials'):
                    return get_materials_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('material'):
                    return get_material_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                case ('production_part.new'):
                    return get_new_production_part_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('upload'):
                    return get_upload_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

            }
            break;
        case ('suppliers'):
            require_once 'navigation/suppliers.nav.php';

            switch ($data['section']) {
                case ('dashboard'):
                    return get_dashboard_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('supplier_parts'):
                    return get_supplier_parts_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('supplier'):
                    return get_supplier_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('agent'):
                    return get_agent_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

                case ('suppliers'):
                    return get_suppliers_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('orders'):
                    return get_purchase_orders_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('deliveries'):
                    return get_deliveries_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('order'):
                    return get_purchase_order_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('deleted_order'):
                    return get_deleted_purchase_order_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

                case ('delivery'):
                    return get_delivery_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('agents'):
                    return get_agents_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('categories'):

                    return get_suppliers_categories_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('category'):

                    return get_suppliers_category_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('main_category.new'):
                    return get_suppliers_new_main_category_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('dashboard'):
                    return get_suppliers_dashboard_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('supplier.new'):
                    return get_new_supplier_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('agent.new'):
                    return get_new_agent_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('supplier_part'):
                    return get_supplier_part_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('supplier_part.new'):
                    return get_new_supplier_part_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('deleted_supplier'):
                    return get_deleted_supplier_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('supplier.user.new'):
                    return get_new_supplier_user_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('agent.user.new'):
                    return get_new_agent_user_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('supplier.order.item'):
                case ('agent.order.item'):
                    return get_order_item_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('supplier.attachment'):
                    return get_supplier_attachment_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('supplier.attachment.new'):
                    return get_new_supplier_attachment_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('timeseries_record'):
                    return get_timeseries_record_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('supplier_delivery.attachment.new'):
                    return get_supplier_delivery_attachment_new_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('supplier_delivery.attachment'):
                    return get_supplier_delivery_attachment_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('settings'):
                    return get_settings_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
            }

            break;

        case ('inventory'):
            require_once 'navigation/inventory.nav.php';

            //print $data['section'];
            switch ($data['section']) {
                case ('dashboard'):
                    return get_dashboard_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

                case ('inventory'):
                    return get_inventory_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

                case ('part'):
                    return get_part_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('part.new'):
                    return get_new_part_navigation(
                        $data, $smarty, $user, $db, $account, $account
                    );
                    break;
                case ('supplier_part.new'):
                    return get_new_supplier_part_navigation(
                        $data, $smarty, $user, $db, $account, $account
                    );
                    break;
                case ('product'):

                    return get_part_product_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('part.image'):
                    return get_part_image_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

                case ('transactions'):
                    return get_transactions_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('stock_history'):
                    return get_stock_history_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('stock_history.day'):
                    return get_stock_history_day_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('categories'):
                    return get_categories_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('category'):
                    return get_parts_category_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('main_category.new'):
                    return get_parts_new_main_category_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

                case ('upload'):
                    return get_upload_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

                case ('barcodes'):
                    return get_barcodes_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('barcode'):
                    return get_barcode_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('deleted_barcode'):
                    return get_deleted_barcode_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('part.attachment'):
                    return get_part_attachment_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('part.attachment.new'):
                    return get_new_part_attachment_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('feedback'):
                    return get_feedback_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('feedback_item'):
                    return get_feedback_item_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
            }

            break;
        case ('warehouses'):
        case ('warehouses_server'):
            require_once 'navigation/warehouses.nav.php';


            switch ($data['section']) {
                case ('dashboard'):
                    return get_dashboard_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('warehouses'):
                    return get_warehouses_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('warehouse'):
                    return get_warehouse_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('warehouse.new'):
                    return get_new_warehouse_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('warehouse_area.new'):
                    return get_new_warehouse_area_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('warehouse_areas'):
                    return get_warehouse_areas_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('warehouse_area'):
                    return get_warehouse_area_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('locations'):
                    return get_locations_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;


                case ('location'):
                    return get_location_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                    break;
                case ('location.new'):
                    return get_new_location_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('deleted_location'):
                    return get_deleted_location_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                case ('categories'):
                    return get_categories_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('category'):
                    return get_locations_category_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('main_category.new'):
                    return get_locations_new_main_category_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

                case ('delivery_notes'):
                    return get_delivery_notes_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('leakages'):
                    return get_leakages_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('timeseries_record'):
                    return get_timeseries_record_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('returns'):
                    return get_returns_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('return'):
                    return get_return_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('shipper'):
                    return get_shipper_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('shipper.new'):
                    return get_shipper_new_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('upload'):
                    return get_upload_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('feedback'):
                    return get_feedback_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;


            }

            break;

        case ('hr'):
            require_once 'navigation/hr.nav.php';

            switch ($data['section']) {

                case ('employees'):
                case ('new_timesheet_record'):

                    return get_employees_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('contractors'):
                    return get_contractors_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('organization'):
                    return get_organization_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('employee'):
                    return get_employee_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('deleted.employee'):
                    return get_deleted_employee_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('employee.new'):
                    return get_new_employee_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('contractor'):
                    return get_contractor_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('deleted.contractor'):
                    return get_deleted_contractor_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('contractor.new'):
                    return get_new_contractor_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('timesheet'):
                    return get_timesheet_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('timesheets'):
                    return get_timesheets_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('employee.attachment'):
                    return get_employee_attachment_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('employee.attachment.new'):
                    return get_new_employee_attachment_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('employee.user.new'):
                    return get_new_employee_user_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('contractor.user.new'):
                    return get_new_contractor_user_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

                case ('upload'):
                    return get_upload_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('overtimes'):
                    return get_overtimes_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('hr.history'):
                    return get_history_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('position'):
                    return get_position_navigation($data, $smarty, $user, $db, $account);
                    break;
            }

            break;


        case ('utils'):
            require_once 'navigation/utils.nav.php';
            switch ($data['section']) {
                case ('forbidden'):
                case ('not_found'):
                    return get_utils_navigation($data);
                    break;
                case ('fire'):
                    return get_fire_navigation($data);
                    break;
            }

            break;
        case ('profile'):
            require_once 'navigation/users.nav.php';

            switch ($data['section']) {
                case ('profile.api_key.new'):
                    return get_profile_new_api_key_navigation($data, $smarty, $user, $db, $account);
                    break;
                default:
                    return get_profile_navigation($data, $smarty, $user, $db, $account);
                    break;
            }


            break;
        case ('accounting_server'):


            require_once 'navigation/accounting.nav.php';

            switch ($data['section']) {


                case ('payment_service_providers'):
                    return get_payment_service_providers_navigation($data, $user, $smarty, $db);
                    break;
                case ('payment_service_provider'):
                    return get_payment_service_provider_navigation($data, $user, $smarty, $db);
                    break;
                case ('payment_account'):
                    return get_payment_account_server_navigation($data, $user, $smarty, $db);
                    break;
                case ('payment_accounts'):
                    return get_payment_accounts_navigation(
                        $data, $user, $smarty, $db
                    );
                    break;
                case ('payment'):
                    return get_payment_navigation($data, $user, $smarty, $db);
                    break;
                case ('payments'):
                    return get_payments_navigation($data, $user, $smarty, $db);
                    break;
                case ('credits'):
                    return get_credits_navigation($data, $user, $smarty, $db);
                    break;
                case ('payments_by_store'):
                    return get_payments_by_store_navigation($data, $user, $smarty, $db);
                    break;
                case ('invoice'):
                    include_once 'navigation/invoice.nav.php';

                    return get_invoice_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('invoices'):
                    return get_invoices_server_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('deleted_invoices_server'):
                    return get_deleted_invoices_server_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('categories'):
                    return get_invoices_categories_server_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('category'):
                    return get_invoices_category_server_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('deleted_invoice'):
                    return get_deleted_invoice_navigation($data, $smarty, $user, $db, $account);
                    break;
            }
            break;
        case ('accounting'):
            require_once 'navigation/accounting.nav.php';


            switch ($data['section']) {


                case ('invoices'):
                    return get_invoices_navigation($data, $smarty, $user, $db, $account);
                    break;

                case ('invoice'):

                    return get_invoice_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('deleted_invoice'):
                    return get_deleted_invoice_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('payment_service_provider'):
                    return get_payment_service_provider_navigation(
                        $data, $user, $smarty, $db
                    );
                    break;
                case ('payment_service_providers'):
                    return get_payment_service_providers_navigation(
                        $data, $user, $smarty, $db
                    );
                    break;
                case ('payment_account'):
                    return get_payment_account_navigation($data, $user, $smarty, $db);
                    break;
                case ('payment_accounts'):
                    return get_payment_accounts_navigation(
                        $data, $user, $smarty, $db
                    );
                    break;
                case ('payment'):
                    return get_payment_navigation($data, $user, $smarty, $db);
                    break;
                case ('payments'):
                    return get_payments_navigation($data, $user, $smarty, $db);
                    break;
                case ('credits'):
                    return get_credits_navigation($data, $user, $smarty, $db);
                    break;
                case ('deleted_invoices'):
                    return get_deleted_invoices_navigation($data, $smarty, $user, $db, $account);
                    break;
            }
            break;
        case ('account'):

            require_once 'navigation/account.nav.php';

            switch ($data['section']) {
                case ('account'):
                    return get_account_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

                case ('data_sets'):
                    return get_data_sets_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('timeseries'):
                    return get_timeseries_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('timeserie'):
                    return get_timeserie_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('images'):
                    return get_images_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('attachments'):
                    return get_attachments_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('uploads'):
                    return get_uploads_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('materials'):
                    return get_materials_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('material'):
                    return get_material_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('upload'):
                    return get_upload_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('osf'):
                    return get_osf_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('isf'):
                    return get_isf_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('orders_index'):
                    return get_orders_index_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('settings'):
                    return get_settings_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;


            }


            break;
        case ('settings'):
            require_once 'navigation/account.nav.php';

            return get_settings_navigation($data);
            break;
        case 'agent_profile':
            require_once 'navigation/agent.nav.php';
            switch ($data['section']) {
                case ('profile'):
                    return get_agent_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;


            }
            break;
        case 'agent_suppliers':
            require_once 'navigation/agent.nav.php';
            switch ($data['section']) {
                case ('suppliers'):
                    return get_suppliers_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('supplier'):
                    return get_supplier_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('supplier_part'):
                    return get_supplier_part_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('supplier.attachment'):
                    return get_supplier_attachment_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('supplier.attachment.new'):
                    return get_new_supplier_attachment_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
            }
        case 'agent_client_orders':
            require_once 'navigation/agent.nav.php';
            switch ($data['section']) {
                case ('orders'):
                    return get_agent_client_orders_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('client_order'):
                    return get_agent_client_order_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('agent_supplier_order'):
                    return get_agent_supplier_order_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;


            }
        case 'agent_client_deliveries':

            require_once 'navigation/agent.nav.php';
            switch ($data['section']) {
                case ('deliveries'):
                    return get_deliveries_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('agent_delivery'):
                    return get_agent_delivery_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;


            }
            break;
        case 'agent_parts':
            require_once 'navigation/agent.nav.php';
            switch ($data['section']) {
                case ('parts'):
                    return get_parts_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;


            }
            break;
        case 'users':
            require_once 'navigation/users.nav.php';
            switch ($data['section']) {
                case ('users'):
                    return get_users_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;


                case ('staff'):
                    return get_staff_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('contractors'):
                    return get_contractors_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('suppliers'):
                    return get_suppliers_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('agents'):
                    return get_agents_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('warehouse'):
                    return get_warehouse_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('root'):
                    return get_root_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('user'):
                    return get_user_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('deleted.user'):
                    return get_deleted_user_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('suppliers.user'):
                    return get_supplierss_user_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

                case ('warehouse.user'):
                    return get_warehouse_user_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('root.user'):
                    return get_root_user_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('user.api_key') :
                    return get_api_key_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('user.api_key.new') :
                    return get_new_api_key_navigation($data, $smarty, $user, $db, $account);
                case ('deleted_api_key') :
                    return get_deleted_api_key_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

            }
            break;
        default:
            return 'Module not found';
    }

}


function get_tabs($data, $db, $account, $modules, $user, $smarty, $requested_tab = '') {


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


    if ($data['section'] == 'mailshot') {


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
                        $data['tab']         = 'mailshot.workshop';
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

                        $_content['tabs']['category.webpage']['class']           = 'hide';
                        $_content['tabs']['category.details']['class']           = 'hide';
                        $_content['tabs']['category.customers']['class']         = 'hide';
                        $_content['tabs']['category.sales']['class']             = 'hide';
                        $_content['tabs']['category.mailshots']['class']         = 'hide';
                        $_content['tabs']['category.deal_components']['class']   = 'hide';
                        $_content['tabs']['category.sales_correlation']['class'] = 'hide';
                        $_content['tabs']['category.images']['class']            = 'hide';
                        $_content['tabs']['category.history']['class']           = 'hide';
                        $_content['tabs']['category.categories']['class']        = 'hide';


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

                        $_content['tabs']['category.webpage']['class']           = 'hide';
                        $_content['tabs']['category.details']['class']           = 'hide';
                        $_content['tabs']['category.customers']['class']         = 'hide';
                        $_content['tabs']['category.sales']['class']             = 'hide';
                        $_content['tabs']['category.mailshots']['class']         = 'hide';
                        $_content['tabs']['category.deal_components']['class']   = 'hide';
                        $_content['tabs']['category.sales_correlation']['class'] = 'hide';
                        $_content['tabs']['category.images']['class']            = 'hide';
                        $_content['tabs']['category.history']['class']           = 'hide';
                        $_content['tabs']['category.categories']['class']        = 'hide';


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


        } else {
            $_content['tabs']['category.customers']['class'] = 'hide';
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


            } elseif ($data['_object']->get('Email Campaign Type Code') == 'Invite Mailshot' or $data['_object']->get('Email Campaign Type Code') == 'Invite') {
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
            if ($order_state_index > 40 or $order_state_index < 0) {

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


function get_view_position($db, $state, $user, $smarty, $account) {

    $branch = array();

    switch ($state['module']) {
        case 'dashboard':


            $branch = array(
                array(
                    'label'     => '<span >'._('Dashboard').'</span>',
                    'icon'      => 'dashboard',
                    'reference' => '/dashboard'
                )
            );

            break;

        case 'products_server':
            if ($state['section'] == 'stores') {
                $branch[] = array(
                    'label'     => _('Stores'),
                    'icon'      => 'shopping-basket',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'products') {

                $branch[] = array(
                    'label'     => _('Products (All stores)'),
                    'icon'      => 'cube',
                    'reference' => ''
                );
            }


            break;

        case 'products':
            $state['current_store'] = $state['store']->id;

            if ($user->get_number_stores() > 1) {
                $branch[] = array(
                    'label'     => _('Stores'),
                    'icon'      => '',
                    'reference' => 'stores'
                );

            }


            if ($state['section'] == 'store') {

                $branch[] = array(
                    'label'     => _('Store').' <span class="Store_Code id">'.$state['_object']->get('Store Code').'</span>',
                    'icon'      => 'shopping-basket',
                    'reference' => 'store/'.$state['_object']->id
                );


            }
            if ($state['section'] == 'settings') {
                $branch[] = array(
                    'label'     => _('Settings store').' <span class="Store_Code id">'.$state['_object']->get('Store Code').'</span>',
                    'icon'      => 'sliders-h',
                    'reference' => 'store/'.$state['_object']->id.'/settings'
                );


            } elseif ($state['section'] == 'store.new') {
                $branch[] = array(
                    'label'     => _('New store'),
                    'icon'      => 'shopping-basket',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'dashboard') {
                $branch[] = array(
                    'label'     => _('Stores'),
                    'icon'      => '',
                    'reference' => 'stores'
                );

                $branch[]               = array(
                    'label'     => _("Store's dashboard").' <span class="id">'.$state['_object']->get('Store Code').'</span>',
                    'icon'      => '',
                    'reference' => 'store/'.$state['_object']->id
                );
                $state['current_store'] = $state['_object']->id;

            } elseif ($state['section'] == 'product') {

                if ($state['parent'] == 'store') {
                    $branch[] = array(
                        'label'     => _('Products').' <span class="id">'.$state['store']->get('Code').'</span>',
                        'icon'      => '',
                        'reference' => 'products/'.$state['_parent']->id
                    );

                } elseif ($state['parent'] == 'category') {

                    $category = $state['_parent'];
                    $branch[] = array(
                        'label'     => _("Products's categories").' <span class="id">'.$state['store']->get('Code').'</span>',
                        'icon'      => 'sitemap',
                        'reference' => 'products/'.$category->get(
                                'Store Key'
                            ).'/categories'
                    );


                    if (isset($state['metadata'])) {
                        $parent_category_keys = $state['metadata'];
                    } else {

                        $parent_category_keys = preg_split(
                            '/\>/', $category->get('Category Position')
                        );
                    }


                    foreach ($parent_category_keys as $category_key) {
                        if (!is_numeric($category_key)) {
                            continue;
                        }
                        // if ($category_key==$state['parent_key']) {
                        // $branch[]=array('label'=>'<span class="Category_Code">'.$category->get('Code').'</span> <span class="italic hide Category_Label">'.$category->get('Label').'</span>', 'icon'=>'', 'reference'=>'');
                        // break;
                        //}else {

                        $parent_category = new Category($category_key);
                        if ($parent_category->id) {

                            $branch[] = array(
                                'label'     => $parent_category->get(
                                    'Code'
                                ),
                                'icon'      => '',
                                'reference' => 'products/'.$category->get('Store Key').'/category/'.$parent_category->id
                            );

                        }
                        //}
                    }


                } elseif ($state['parent'] == 'order') {
                    $order  = new Order($state['parent_key']);
                    $store  = new Store($order->get('Order Store Key'));
                    $branch = array(
                        array(
                            'label'     => _('Home'),
                            'icon'      => 'home',
                            'reference' => ''
                        )
                    );

                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => _('Orders').' ('._('All stores').')',
                            'icon'      => 'indent',
                            'reference' => 'orders/all'
                        );
                    }

                    $branch[] = array(
                        'label'     => _('Orders').' '.$store->data['Store Code'],
                        'icon'      => '',
                        'reference' => 'orders/'.$store->id
                    );

                    $branch[] = array(
                        'label'     => _('Order').' '.$order->get(
                                'Order Public ID'
                            ),
                        'icon'      => 'shopping-cart',
                        'reference' => 'orders/'.$store->id.'/'.$state['parent_key']
                    );


                }
                $state['current_store'] = $state['store']->id;
                $_ref                   = $state['parent'].'/'.$state['parent_key'].'/product/'.$state['_object']->id;
                if (isset($state['otf'])) {
                    $_ref = $state['parent'].'/'.$state['parent_key'].'/item/'.$state['otf'];
                }

                $branch[] = array(
                    'label'     => '<span class="id Product_Code">'.$state['_object']->get('Code').'</span>',
                    'icon'      => 'cube',
                    'reference' => $_ref
                );

            } elseif ($state['section'] == 'products') {


                $branch[] = array(
                    'label'     => _('Products').' <span class="id">'.$state['store']->get('Code').'</span>',
                    'icon'      => 'cube',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'service') {

                if ($state['parent'] == 'store') {
                    $branch[] = array(
                        'label'     => _('Services').' <span class="id">'.$state['store']->get('Code').'</span>',
                        'icon'      => '',
                        'reference' => 'services/'.$state['_parent']->id
                    );

                } elseif ($state['parent'] == 'category') {

                    $category = $state['_parent'];
                    $branch[] = array(
                        'label'     => _("Services's categories").' <span class="id">'.$state['store']->get('Code').'</span>',
                        'icon'      => 'sitemap',
                        'reference' => 'services/'.$category->get(
                                'Store Key'
                            ).'/categories'
                    );


                    if (isset($state['metadata'])) {
                        $parent_category_keys = $state['metadata'];
                    } else {

                        $parent_category_keys = preg_split(
                            '/\>/', $category->get('Category Position')
                        );
                    }


                    foreach ($parent_category_keys as $category_key) {
                        if (!is_numeric($category_key)) {
                            continue;
                        }
                        // if ($category_key==$state['parent_key']) {
                        // $branch[]=array('label'=>'<span class="Category_Code">'.$category->get('Code').'</span> <span class="italic hide Category_Label">'.$category->get('Label').'</span>', 'icon'=>'', 'reference'=>'');
                        // break;
                        //}else {

                        $parent_category = new Category($category_key);
                        if ($parent_category->id) {

                            $branch[] = array(
                                'label'     => $parent_category->get(
                                    'Code'
                                ),
                                'icon'      => '',
                                'reference' => 'services/'.$category->get('Store Key').'/category/'.$parent_category->id
                            );

                        }
                        //}
                    }


                } elseif ($state['parent'] == 'order') {
                    $order  = new Order($state['parent_key']);
                    $store  = new Store($order->get('Order Store Key'));
                    $branch = array(
                        array(
                            'label'     => _('Home'),
                            'icon'      => 'home',
                            'reference' => ''
                        )
                    );

                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => _('Orders').' ('._('All stores').')',
                            'icon'      => 'indent',
                            'reference' => 'orders/all'
                        );
                    }

                    $branch[] = array(
                        'label'     => _('Orders').' '.$store->data['Store Code'],
                        'icon'      => '',
                        'reference' => 'orders/'.$store->id
                    );

                    $branch[] = array(
                        'label'     => _('Order').' '.$order->get(
                                'Order Public ID'
                            ),
                        'icon'      => 'shopping-cart',
                        'reference' => 'orders/'.$store->id.'/'.$state['parent_key']
                    );


                }


                $state['current_store'] = $state['store']->id;
                $_ref                   = $state['parent'].'/'.$state['parent_key'].'/service/'.$state['_object']->id;
                if (isset($state['otf'])) {
                    $_ref = $state['parent'].'/'.$state['parent_key'].'/item/'.$state['otf'];
                }

                $branch[] = array(
                    'label'     => '<span class="id Service_Code">'.$state['_object']->get('Code').'</span>',
                    'icon'      => 'cube',
                    'reference' => $_ref
                );

            } elseif ($state['section'] == 'services') {


                $branch[] = array(
                    'label'     => _(
                            'Services'
                        ).' <span class="id">'.$state['store']->get('Code').'</span>',
                    'icon'      => 'cube',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'categories') {
                $branch[] = array(
                    'label'     => _("Products's categories").' <span class="id">'.$state['store']->get('Code').'</span>',
                    'icon'      => 'sitemap',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'category') {
                $category = $state['_object'];
                $branch[] = array(
                    'label'     => _("Products's categories").' <span class="id">'.$state['store']->get('Code').'</span>',
                    'icon'      => 'sitemap',
                    'reference' => 'products/'.$category->get(
                            'Store Key'
                        ).'/categories'
                );


                if (isset($state['metadata'])) {
                    $parent_category_keys = $state['metadata'];
                } else {

                    $parent_category_keys = preg_split(
                        '/\>/', $category->get('Category Position')
                    );
                }


                foreach ($parent_category_keys as $category_key) {
                    if (!is_numeric($category_key)) {
                        continue;
                    }
                    if ($category_key == $state['key']) {
                        $branch[] = array(
                            'label'     => '<span class="Category_Code">'.$category->get('Code').'</span> <span class="italic hide Category_Label">'.$category->get('Label').'</span>',
                            'icon'      => '',
                            'reference' => ''
                        );
                        break;
                    } else {

                        $parent_category = new Category($category_key);
                        if ($parent_category->id) {

                            $branch[] = array(
                                'label'     => $parent_category->get(
                                    'Code'
                                ),
                                'icon'      => '',
                                'reference' => 'products/'.$category->get('Store Key').'/category/'.$parent_category->id
                            );

                        }
                    }
                }
            } elseif ($state['section'] == 'main_category.new') {
                $branch[] = array(
                    'label'     => _("Products's categories"),
                    'icon'      => 'sitemap',
                    'reference' => 'products/'.$state['parent_key'].'/categories'
                );
                $branch[] = array(
                    'label'     => _('New main category'),
                    'icon'      => '',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'refund.new') {


                $branch[] = array(
                    'label'     => '<span class="id ">'.$state['_object']->get('Order Public ID').'</span>',
                    'icon'      => 'shopping-cart',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'customer') {


                if ($state['parent'] == 'campaign') {
                    $branch[] = array(
                        'label'     => _('Marketing').' <span class="Store_Code">'.$state['store']->get('Code').'</span>',
                        'icon'      => 'bullhorn',
                        'reference' => 'marketing/'.$state['store']->id
                    );

                    $branch[] = array(
                        'label'     => '<span class="Deal_Campaign_Name">'.$state['_parent']->get('Name').'</span>',
                        'icon'      => 'tags',
                        'reference' => 'campaigns/'.$state['store']->id.'/'.$state['_parent']->id
                    );

                    $branch[] = array(
                        'label'     => '<span class="id ">'.$state['_object']->get_formatted_id().'</span>',
                        'icon'      => 'user',
                        'reference' => ''
                    );
                } elseif ($state['parent'] == 'deal') {

                    $branch[] = array(
                        'label'     => _('Marketing').' <span class="Store_Code">'.$state['store']->get('Code').'</span>',
                        'icon'      => 'bullhorn',
                        'reference' => 'marketing/'.$state['store']->id
                    );

                    $campaign = get_object('Campaign', $state['_parent']->get('Deal Campaign Key'));

                    $branch[] = array(
                        'label'     => '<span class="Deal_Campaign_Name">'.$campaign->get('Name').'</span>',
                        'icon'      => 'tags',
                        'reference' => 'campaigns/'.$state['store']->id.'/'.$state['_parent']->id
                    );

                    $branch[] = array(
                        'label'     => '<span class="Deal_Name">'.$state['_parent']->get('Name').'</span>',
                        'icon'      => 'tags',
                        'reference' => 'campaigns/'.$state['store']->id.'/'.$state['_parent']->get('Deal Campaign Key').'/deal/'.$state['_parent']->id
                    );

                    $branch[] = array(
                        'label'     => '<span class="id ">'.$state['_object']->get_formatted_id().'</span>',
                        'icon'      => 'user',
                        'reference' => ''
                    );
                }

            } elseif ($state['section'] == 'website') {

                $branch[]               = array(
                    'label'     => _('Store').' <span class="Store_Code id">'.$state['store']->get('Code').'</span>',
                    'icon'      => 'shopping-basket',
                    'reference' => 'store/'.$state['store']->id
                );
                $state['current_store'] = $state['store']->id;

                $branch[] = array(
                    'label'     => '<span class="id Website_Code">'.$state['website']->get('Code').'</span>',
                    'icon'      => 'globe',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'webpage') {

                $branch[]               = array(
                    'label'     => _('Store').' <span class="Store_Code id">'.$state['store']->get('Code').'</span>',
                    'icon'      => 'shopping-basket',
                    'reference' => 'store/'.$state['store']->id
                );
                $state['current_store'] = $state['store']->id;

                $branch[] = array(
                    'label'     => '<span class=" Website_Code">'.$state['website']->get('Code').'</span>',
                    'icon'      => 'globe',
                    'reference' => 'store/'.$state['store']->id.'/website'
                );


                $branch[] = array(
                    'label'     => '<span class="id Webpage_Code">'.$state['_object']->get('Code').'</span>',
                    'icon'      => 'file-alt',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'deleted.webpage') {

                $branch[]               = array(
                    'label'     => _('Store').' <span class="Store_Code id">'.$state['store']->get('Code').'</span>',
                    'icon'      => 'shopping-basket',
                    'reference' => 'store/'.$state['store']->id
                );
                $state['current_store'] = $state['store']->id;

                $branch[] = array(
                    'label'     => '<span class=" Website_Code">'.$state['website']->get('Code').'</span>',
                    'icon'      => 'globe',
                    'reference' => 'store/'.$state['store']->id.'/website'
                );


                $branch[] = array(
                    'label'     => '<span class=" Webpage_Code error"> ('._('Deleted').')  '.$state['_object']->get('Page Title').'</span>',
                    'icon'      => 'file-alt error',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'webpage.new') {


                $branch[]               = array(
                    'label'     => _('Store').' <span class="Store_Code id">'.$state['store']->get('Code').'</span>',
                    'icon'      => 'shopping-basket',
                    'reference' => 'store/'.$state['store']->id
                );
                $state['current_store'] = $state['store']->id;


                $branch[] = array(
                    'label'     => '<span class=" Website_Code">'.$state['website']->get('Code').'</span>',
                    'icon'      => 'globe',
                    'reference' => 'store/'.$state['store']->id.'/website'
                );

                $branch[] = array(
                    'label'     => _('New webpage'),
                    'icon'      => '',
                    'reference' => '',
                );
            } elseif ($state['section'] == 'website.new') {


                $branch[]               = array(
                    'label'     => _('Store').' <span class="Store_Code id">'.$state['store']->get('Code').'</span>',
                    'icon'      => 'shopping-basket',
                    'reference' => 'store/'.$state['store']->id
                );
                $state['current_store'] = $state['store']->id;


                $branch[] = array(
                    'label'     => _('New website'),
                    'icon'      => '',
                    'reference' => '',
                );
            } elseif ($state['section'] == 'marketing') {

                $branch[] = array(
                    'label'     => _('Marketing emails').' '.$state['store']->get('Code'),
                    'icon'      => 'bullhorn',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'campaign_order_recursion') {
                $branch[] = array(
                    'label'     => _('Offers').' <span class="Store_Code">'.$state['store']->get('Code').'</span>',
                    'icon'      => 'tags',
                    'reference' => 'offers/'.$state['store']->id
                );

                $branch[] = array(
                    'label'     => '<span class="Deal_Campaign_Name">'.$state['_object']->get('Name').'</span>',
                    'icon'      => 'tags',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'vouchers') {
                $branch[] = array(
                    'label'     => _('Offers').' <span class="Store_Code">'.$state['store']->get('Code').'</span>',
                    'icon'      => 'tags',
                    'reference' => 'offers/'.$state['store']->id
                );

                $branch[] = array(
                    'label'     => '<span class="Deal_Campaign_Name">'.$state['_object']->get('Name').'</span>',
                    'html_icon' => $state['_object']->get('Icon'),
                    'reference' => ''
                );


            } elseif ($state['section'] == 'campaign') {
                $branch[] = array(
                    'label'     => _('Offers').' <span class="Store_Code">'.$state['store']->get('Code').'</span>',
                    'icon'      => 'tags',
                    'reference' => 'offers/'.$state['store']->id
                );

                $branch[] = array(
                    'label'     => '<span class="Deal_Campaign_Name">'.$state['_object']->get('Name').'</span>',
                    'html_icon' => $state['_object']->get('Icon'),
                    'reference' => ''
                );


            } elseif ($state['section'] == 'campaign.new') {
                $branch[] = array(
                    'label'     => _('Marketing').' <span class="Store_Code">'.$state['store']->get('Code').'</span>',
                    'icon'      => 'bullhorn',
                    'reference' => 'marketing/'.$state['store']->id
                );

                $branch[] = array(
                    'label'     => _('New campaign'),
                    'icon'      => '',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'deal.new') {


                if ($state['parent'] == 'campaign') {

                    include_once 'class.Store.php';
                    $state['store'] = new Store($state['_parent']->get('Store Key'));

                    $branch[] = array(
                        'label'     => _('Offers').' <span class="Store_Code">'.$state['store']->get('Code').'</span>',
                        'icon'      => 'tags',
                        'reference' => 'offers/'.$state['store']->id
                    );

                    $branch[] = array(
                        'label'     => '<span class="Deal_Campaign_Name">'.$state['_parent']->get('Name').'</span>',
                        'html_icon' => $state['_parent']->get('Icon'),
                        'reference' => 'offers/'.$state['store']->id.'/'.strtolower($state['_parent']->get('Code'))
                    );


                    switch ($state['_parent']->get('Code')) {
                        case 'VO':
                            $branch[] = array(
                                'label'     => _('New voucher'),
                                'icon'      => '',
                                'reference' => ''
                            );
                            break;
                        default:
                            $branch[] = array(
                                'label'     => _('New offer'),
                                'icon'      => '',
                                'reference' => ''
                            );

                    }


                } else {

                    $branch[] = array(
                        'label'     => _('New offer'),
                        'icon'      => '',
                        'reference' => ''
                    );

                }


            } elseif ($state['section'] == 'deal') {

                if ($state['parent'] == 'campaign') {
                    $branch[] = array(
                        'label'     => _('Offers').' <span class="Store_Code">'.$state['store']->get('Code').'</span>',
                        'icon'      => 'tags',
                        'reference' => 'marketing/'.$state['store']->id
                    );

                    $branch[] = array(
                        'label'     => '<span class="Deal_Campaign_Name">'.$state['_parent']->get('Name').'</span>',
                        'icon'      => 'tags',
                        'reference' => 'campaigns/'.$state['store']->id.'/'.$state['_parent']->id
                    );

                } elseif ($state['parent'] == 'category') {


                    $category = $state['_parent'];
                    $branch[] = array(
                        'label'     => _("Products's categories").' <span class="id">'.$state['store']->get('Code').'</span>',
                        'icon'      => 'sitemap',
                        'reference' => 'products/'.$category->get(
                                'Store Key'
                            ).'/categories'
                    );


                    if (isset($state['metadata'])) {
                        $parent_category_keys = $state['metadata'];
                    } else {

                        $parent_category_keys = preg_split(
                            '/\>/', $category->get('Category Position')
                        );
                    }


                    foreach ($parent_category_keys as $category_key) {
                        if (!is_numeric($category_key)) {
                            continue;
                        }
                        if ($category_key == $state['key']) {
                            $branch[] = array(
                                'label'     => '<span class="Category_Code">'.$category->get('Code').'</span> <span class="italic hide Category_Label">'.$category->get('Label').'</span>',
                                'icon'      => '',
                                'reference' => ''
                            );
                            break;
                        } else {

                            $parent_category = new Category($category_key);
                            if ($parent_category->id) {

                                $branch[] = array(
                                    'label'     => $parent_category->get(
                                        'Code'
                                    ),
                                    'icon'      => '',
                                    'reference' => 'products/'.$category->get('Store Key').'/category/'.$parent_category->id
                                );

                            }
                        }
                    }

                }

                $branch[] = array(
                    'label'     => '<span class="Deal_Name">'.$state['_object']->get('Name').'</span>',
                    'icon'      => 'tag',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'charge') {

                $branch[] = array(
                    'label'     => _('Store').' <span class="Store_Code id">'.$state['store']->get('Store Code').'</span>',
                    'icon'      => 'shopping-basket',
                    'reference' => 'store/'.$state['store']->id
                );
                $branch[] = array(
                    'label'     => _('Charge').': <span class="Charge_Name id">'.$state['_object']->get('Charge Name').'</span>',
                    'icon'      => 'money',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'email_campaign_type') {


                if ($state['_object']->get('Email Campaign Type Scope') == 'User Notification') {
                    $branch[] = array(
                        'label'     => _('Store').' <span class="Store_Code id">'.$state['store']->get('Store Code').'</span> <span class="italic">(<span style="padding:0 1px 0 1px">'._('Notifications')
                            .' <i style="padding: 0px" class="fal fa-rectangle-wide"></i></span>)</span>',
                        'icon'      => 'shopping-basket',
                        'reference' => 'store/'.$state['store']->id,
                        'metadata'  => '{tab:\'store.notifications\'}'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id">'.$state['_object']->get('Label').'</span>',
                        'icon'      => 'bell',
                        'reference' => ''
                    );
                } else {
                    $branch[] = array(
                        'label'     => _('Marketing emails').' '.$state['store']->get('Code'),
                        'icon'      => 'bullhorn',
                        'reference' => 'marketing/'.$state['store']->id.'/emails',
                    );
                    $branch[] = array(
                        'label'     => '<span class="id">'.$state['_object']->get('Label').'</span>',
                        'icon'      => 'container-storage',
                        'reference' => ''
                    );
                }


            } elseif ($state['section'] == 'mailshot') {


                $branch[] = array(
                    'label'     => _('Marketing emails').' '.$state['store']->get('Code'),
                    'icon'      => 'bullhorn',
                    'reference' => 'marketing/'.$state['store']->id.'/emails',
                );
                $branch[] = array(
                    'label'     => '<span class="id">'.$state['_parent']->get('Label').'</span>',
                    'icon'      => $state['_parent']->get('Icon'),
                    'reference' => 'marketing/'.$state['store']->id.'/emails/'.$state['_parent']->id,
                );
                $branch[] = array(
                    'label'     => '<span class="Email_Campaign_Name id">'.$state['_object']->get('Name').'</span>',
                    'icon'      => 'container-storage',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'email_tracking') {


                $email_template_type = get_object('EmailTemplateType', $state['_parent']->get('Email Campaign Email Template Type Key'));


                $branch[] = array(
                    'label'     => _('Marketing emails').' '.$state['store']->get('Code'),
                    'icon'      => 'bullhorn',
                    'reference' => 'marketing/'.$state['store']->id.'/emails',
                );
                $branch[] = array(
                    'label'     => '<span class="id">'.$email_template_type->get('Label').'</span>',
                    'icon'      => $email_template_type->get('Icon'),
                    'reference' => 'marketing/'.$state['store']->id.'/emails/'.$email_template_type->id,
                );
                $branch[] = array(
                    'label'     => '<span class="Email_Campaign_Name id">'.$state['_parent']->get('Name').'</span>',
                    'icon'      => 'container-storage',
                    'reference' => 'marketing/'.$state['store']->id.'/emails/'.$email_template_type->id.'/mailshot/'.$state['_parent']->id,
                );
                $branch[] = array(
                    'label'     => _('Tracking').': <span class="id">'.$state['_object']->get('Email Tracking Email').'</span>',
                    'icon'      => '',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'deal_component') {


                if ($state['parent'] == 'campaign') {
                    $branch[] = array(
                        'label'     => _('Offers').' <span class="Store_Code">'.$state['store']->get('Code').'</span>',
                        'icon'      => 'tags',
                        'reference' => 'marketing/'.$state['store']->id
                    );

                    $branch[] = array(
                        'label'     => '<span class="Deal_Campaign_Name">'.$state['_parent']->get('Name').'</span>',
                        'icon'      => 'tags',
                        'reference' => 'campaigns/'.$state['store']->id.'/'.$state['_parent']->id
                    );

                } elseif ($state['parent'] == 'category') {


                    $category = $state['_parent'];
                    $branch[] = array(
                        'label'     => _("Products's categories").' <span class="id">'.$state['store']->get('Code').'</span>',
                        'icon'      => 'sitemap',
                        'reference' => 'products/'.$category->get(
                                'Store Key'
                            ).'/categories'
                    );


                    if (isset($state['metadata'])) {
                        $parent_category_keys = $state['metadata'];
                    } else {

                        $parent_category_keys = preg_split(
                            '/\>/', $category->get('Category Position')
                        );
                    }


                    foreach ($parent_category_keys as $category_key) {
                        if (!is_numeric($category_key)) {
                            continue;
                        }
                        if ($category_key == $state['key']) {
                            $branch[] = array(
                                'label'     => '<span class="Category_Code">'.$category->get('Code').'</span> <span class="italic hide Category_Label">'.$category->get('Label').'</span>',
                                'icon'      => '',
                                'reference' => ''
                            );
                            break;
                        } else {

                            $parent_category = new Category($category_key);
                            if ($parent_category->id) {

                                $branch[] = array(
                                    'label'     => $parent_category->get(
                                        'Code'
                                    ),
                                    'icon'      => '',
                                    'reference' => 'products/'.$category->get('Store Key').'/category/'.$parent_category->id
                                );

                            }
                        }
                    }

                }

                $branch[] = array(
                    'label'     => '<span class="Deal_Component_Name_Label">'.$state['_object']->get('Name Label').'</span>',
                    'icon'      => 'tag',
                    'reference' => ''
                );
            } else {
                //print 'section not found: '.$state['section'];
            }


            break;
        case 'customers_server':

            if ($state['section'] == 'customers') {
                $branch[] = array(
                    'label'     => _('Customers (All stores)'),
                    'icon'      => '',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'email_communications') {
                $branch[] = array(
                    'label'     => _('Customer notifications (All stores)'),
                    'icon'      => '',
                    'reference' => ''
                );
            }

            break;


        case 'customers':


            $state['current_store'] = $state['store']->id;


            switch ($state['parent']) {
                case 'store':
                    $store                  = new Store($state['parent_key']);
                    $state['current_store'] = $store->id;

                    break;


            }

            if ($user->get_number_stores() > 1) {


                if (in_array(
                    $state['section'], array(
                                         'email_campaigns',
                                         'email_campaign_type',
                                         'email_tracking',
                                         'mailshot'
                                     )
                )) {
                    $branch[] = array(
                        'label'     => _('(All stores)'),
                        'icon'      => 'mail-bulk',
                        'reference' => 'customers/all/email_communications'
                    );
                } else {
                    $branch[] = array(
                        'label'     => _('(All stores)'),
                        'icon'      => 'window-restore',
                        'reference' => 'customers/all'
                    );
                }


            }


            switch ($state['section']) {
                case 'list':
                    $list  = new SubjectList($state['key']);
                    $store = new Store($list->data['List Parent Key']);


                    $branch[] = array(
                        'label'     => _(
                                "Customer's lists"
                            ).' '.$store->data['Store Code'],
                        'icon'      => 'list',
                        'reference' => 'customers/'.$store->id.'/lists'
                    );
                    $branch[] = array(
                        'label'     => '<span class="List_Name">'.$list->get('List Name').'</span>',
                        'icon'      => '',
                        'reference' => 'customers/list/'.$list->id
                    );

                    break;

                case 'customer':


                    if ($state['parent'] == 'store') {
                        $customer = new Customer($state['key']);
                        if ($customer->id) {


                            $store = new Store(
                                $customer->data['Customer Store Key']
                            );


                            $branch[] = array(
                                'label'     => _(
                                        'Customers'
                                    ).' '.$store->data['Store Code'],
                                'icon'      => 'users',
                                'reference' => 'customers/'.$store->id
                            );
                            $branch[] = array(
                                'label'     => $customer->get_formatted_id(),
                                'icon'      => 'user',
                                'reference' => 'customer/'.$customer->id
                            );
                        }
                    } elseif ($state['parent'] == 'list') {
                        $customer = new Customer($state['key']);
                        $store    = new Store(
                            $customer->data['Customer Store Key']
                        );

                        $list = new SubjectList($state['parent_key']);

                        $branch[] = array(
                            'label'     => _("Customer's lists").' '.$store->data['Store Code'],
                            'icon'      => 'list',
                            'reference' => 'customers/'.$store->id.'/lists'
                        );
                        $branch[] = array(
                            'label'     => '<span class="List_Name">'.$list->get('List Name').'</span>',
                            'icon'      => '',
                            'reference' => 'customers/list/'.$list->id
                        );


                        $branch[] = array(
                            'label'     => _(
                                    'Customer'
                                ).' '.$customer->get_formatted_id(),
                            'icon'      => 'user',
                            'reference' => 'customer/'.$customer->id
                        );
                    }
                    break;
                case 'prospect':
                    $branch[] = array(
                        'label'     => _('Prospects').' '.$store->data['Store Code'],
                        'icon'      => 'user-friends',
                        'reference' => 'prospects/'.$store->id
                    );

                    $branch[] = array(
                        'label'     => '<span class="Prospect_Name id">'.$state['_object']->get('Name').'</span>',
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;
                case 'prospects.template.new':
                    $branch[] = array(
                        'label'     => _('Prospects').' '.$store->data['Store Code'],
                        'icon'      => 'user-friends',
                        'reference' => 'prospects/'.$store->id
                    );

                    $branch[] = array(
                        'label'     => _('New prospect invitation template'),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;

                case 'email_tracking':


                    $store = get_object('Store', $state['_parent']->get('Store Key'));


                    if ($state['_parent']->get_object_name() == 'Prospect') {
                        $branch[] = array(
                            'label'     => _('Prospects').' '.$store->data['Store Code'],
                            'icon'      => 'user-friends',
                            'reference' => 'prospects/'.$store->id
                        );

                        $branch[] = array(
                            'label'     => '<span class="Prospect_Name id">'.$state['_parent']->get('Name').'</span>',
                            'icon'      => '',
                            'reference' => 'prospects/'.$store->id.'/'.$state['_parent']->id
                        );

                        $branch[] = array(
                            'label'     => _('Invitaion email'),
                            'icon'      => 'paper-plane',
                            'reference' => ''
                        );

                    } elseif ($state['_parent']->get_object_name() == 'Customer') {
                        $branch[] = array(
                            'label'     => _('Customers').' '.$store->data['Store Code'],
                            'icon'      => 'users',
                            'reference' => 'customers/'.$store->id
                        );

                        $branch[] = array(
                            'label'     => '<span class="Customer_Name id">'.$state['_parent']->get('Name').'</span>',
                            'icon'      => 'user',
                            'reference' => 'customers/'.$store->id.'/'.$state['_parent']->id
                        );

                        $branch[] = array(
                            'label'     => _('Sent email'),
                            'icon'      => 'paper-plane',
                            'reference' => ''
                        );

                    } elseif ($state['_parent']->get_object_name() == 'Email Campaign Type') {

                        $branch[] = array(
                            'label'     => _("Customer notifications").' '.$store->data['Store Code'],
                            'icon'      => 'paper-plane',
                            'reference' => 'customers/'.$store->id.'/notifications'
                        );

                        $branch[] = array(
                            'label'     => $state['_parent']->get('Label'),
                            'icon'      => $state['_parent']->get('Icon'),
                            'reference' => 'email_campaign_type/'.$state['_parent']->get('Store Key').'/'.$state['_parent']->id
                        );

                        $branch[] = array(
                            'label'     => _('Tracking').': <span class="id">'.$state['_object']->get('Email Tracking Email').'</span>',
                            'icon'      => '',
                            'reference' => ''
                        );

                    } elseif ($state['_parent']->get_object_name() == 'Email Campaign') {


                        $email_campaign_type = get_object('email_campaign_type', $state['_parent']->get('Email Campaign Email Template Type Key'));


                        $branch[] = array(
                            'label'     => _("Customer notifications").' '.$store->data['Store Code'],
                            'icon'      => 'paper-plane',
                            'reference' => 'customers/'.$store->id.'/notifications'
                        );

                        $branch[] = array(
                            'label'     => $email_campaign_type->get('Label'),
                            'icon'      => $email_campaign_type->get('Icon'),
                            'reference' => 'customers/'.$state['_parent']->get('Store Key').'/notifications/'.$email_campaign_type->id
                        );

                        $branch[] = array(
                            'label'     => $state['_parent']->get('Name'),
                            'icon'      => 'container-storage',
                            'reference' => 'customers/'.$state['_parent']->get('Store Key').'/notifications/'.$email_campaign_type->id.'/mailshot/'.$state['_parent']->id
                        );

                        $branch[] = array(
                            'label'     => _('Tracking').': <span class="id">'.$state['_object']->get('Email Tracking Email').'</span>',
                            'icon'      => '',
                            'reference' => ''
                        );

                    }


                    break;
                case 'mailshot':


                    $store = get_object('Store', $state['_parent']->get('Store Key'));


                    if ($state['_parent']->get_object_name() == 'Email Campaign Type') {

                        $branch[] = array(
                            'label'     => _("Customer notifications").' '.$store->data['Store Code'],
                            'icon'      => 'paper-plane',
                            'reference' => 'customers/'.$store->id.'/notifications'
                        );

                        $branch[] = array(
                            'label'     => $state['_parent']->get('Label'),
                            'icon'      => $state['_parent']->get('Icon'),
                            'reference' => 'customers/'.$state['_parent']->get('Store Key').'/notifications/'.$state['_parent']->id
                        );

                        if ($state['_object']->get('Email Campaign Type') == 'Newsletter') {
                            $branch[] = array(
                                'label'     => '<span class="id Email_Campaign_Name">'.$state['_object']->get('Name').'</span>',
                                'icon'      => '',
                                'reference' => ''
                            );
                        } else {
                            $branch[] = array(
                                'label'     => _('Mailshot').': <span class="id Email_Campaign_Name">'.$state['_object']->get('Name').'</span>',
                                'icon'      => 'container-storage',
                                'reference' => ''
                            );
                        }


                    }


                    break;

                case 'prospect.compose_email':


                    $store = get_object('Store', $state['_parent']->get('Store Key'));

                    $branch[] = array(
                        'label'     => _('Prospects').' '.$store->data['Store Code'],
                        'icon'      => 'user-friends',
                        'reference' => 'prospects/'.$store->id
                    );

                    $branch[] = array(
                        'label'     => '<span class="Prospect_Name id">'.$state['_parent']->get('Name').'</span>',
                        'icon'      => '',
                        'reference' => 'prospects/'.$store->id.'/'.$state['_parent']->id
                    );

                    $branch[] = array(
                        'label'     => _('Composing personalized invitation'),
                        'icon'      => 'envelope',
                        'reference' => ''
                    );
                    break;

                    break;
                case 'dashboard':
                    $branch[] = array(
                        'label'     => _("Customer's dashboard").' '.$store->data['Store Code'],
                        'icon'      => 'dashboard',
                        'reference' => 'customers/dashboard/'.$store->id
                    );
                    break;
                case 'customers':
                    $branch[] = array(
                        'label'     => _('Customers').' '.$store->data['Store Code'],
                        'icon'      => 'users',
                        'reference' => 'customers/'.$store->id
                    );
                    break;

                case 'prospects':
                    $branch[] = array(
                        'label'     => _('Prospects').' '.$store->data['Store Code'],
                        'icon'      => 'user-friends',
                        'reference' => 'prospects/'.$store->id
                    );
                    break;

                case 'prospect.new':
                    $branch[] = array(
                        'label'     => _('Prospects').' '.$store->data['Store Code'],
                        'icon'      => 'user-friends',
                        'reference' => 'prospects/'.$store->id
                    );
                    $branch[] = array(
                        'label'     => _('New prospect'),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;

                case 'prospects.email_template':
                    $branch[] = array(
                        'label'     => _('Prospects').' '.$store->data['Store Code'],
                        'icon'      => 'user-friends',
                        'reference' => 'prospects/'.$store->id.'&tab=prospects'
                    );

                    $branch[] = array(
                        'label'     => _('Invitation templates'),
                        'icon'      => 'chalkboard',
                        'reference' => 'prospects/'.$store->id.'&tab=prospects.email_templates'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Email_Template_Name">'.$state['_object']->get('Name').'</span>',
                        'icon'      => 'envelope',
                        'reference' => 'prospects/'.$store->id.'/template/'
                    );

                    break;


                case 'categories':
                    $branch[] = array(
                        'label'     => _(
                                "Customer's categories"
                            ).' '.$store->data['Store Code'],
                        'icon'      => 'sitemap',
                        'reference' => 'customers/categories/'.$store->id
                    );
                    break;
                case 'lists':
                    $branch[] = array(
                        'label'     => _(
                                "Customer's lists"
                            ).' '.$store->data['Store Code'],
                        'icon'      => 'list',
                        'reference' => 'customers/'.$store->id.'/lists'
                    );
                    break;
                case 'insights':
                    $branch[] = array(
                        'label'     => _("Customer's insights").' '.$store->data['Store Code'],
                        'icon'      => 'graduation-cap',
                        'reference' => 'customers/'.$store->id.'/insights'
                    );
                    break;
                case 'customer_notifications':
                    $branch[] = array(
                        'label'     => _("Customer notifications").' <span class="id">'.$store->data['Store Code'].'</span>',
                        'icon'      => 'paper-plane',
                        'reference' => 'customers/'.$store->id.'/notifications'
                    );
                    break;
                case 'email_campaign_type':
                    $branch[] = array(
                        'label'     => _("Customer notifications").' '.$store->data['Store Code'],
                        'icon'      => 'paper-plane',
                        'reference' => 'customers/'.$store->id.'/notifications'
                    );

                    $branch[] = array(
                        'label'     => $state['_object']->get('Label'),
                        'icon'      => $state['_object']->get('Icon'),
                        'reference' => ''
                    );

                    break;

                case 'newsletter':
                    $branch[] = array(
                        'label'     => _("Customer notifications").' '.$store->data['Store Code'],
                        'icon'      => 'envelope',
                        'reference' => 'customers/'.$store->id.'/notifications'
                    );

                    $branch[] = array(
                        'label'     => _("Newsletter").' <span class="id Email_Campaign_Name">'.$state['_object']->get('Name').'</span>',
                        'icon'      => '',
                        'reference' => 'customers/'.$store->id.'/notifications/'
                    );

                    break;
                case 'poll_query.new':
                    $branch[] = array(
                        'label'     => _("Customer's insights").' '.$store->data['Store Code'],
                        'icon'      => 'graduation-cap',
                        'reference' => 'customers/'.$store->id.'/insights'
                    );
                    $branch[] = array(
                        'label'     => _('New poll query'),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;
                case 'poll_query':
                    $branch[] = array(
                        'label'     => _("Customer's insights").' '.$store->data['Store Code'],
                        'icon'      => 'graduation-cap',
                        'reference' => 'customers/'.$store->id.'/insights'
                    );
                    $branch[] = array(
                        'label'     => sprintf(_('Poll query %s'), $state['_object']->get('Name')),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;

                case 'poll_query_option.new':


                    $store = get_object('Store', $state['_parent']->get('Store Key'));

                    $branch[] = array(
                        'label'     => _("Customer's insights").' '.$store->data['Store Code'],
                        'icon'      => 'graduation-cap',
                        'reference' => 'customers/'.$store->id.'/insights'
                    );
                    $branch[] = array(
                        'label'     => sprintf(_('Poll query %s'), $state['_parent']->get('Name')),
                        'icon'      => '',
                        'reference' => 'customers/'.$store->id.'/poll_query/'.$state['_parent']->id,
                    );
                    $branch[] = array(
                        'label'     => _('New option'),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;
                case 'poll_query_option':
                    $store    = get_object('Store', $state['_object']->get('Store Key'));
                    $branch[] = array(
                        'label'     => _("Customer's insights").' '.$store->data['Store Code'],
                        'icon'      => 'graduation-cap',
                        'reference' => 'customers/'.$store->id.'/insights'
                    );
                    $branch[] = array(
                        'label'     => sprintf(_('Poll query %s'), $state['_parent']->get('Name')),
                        'icon'      => '',
                        'reference' => 'customers/'.$store->id.'/poll_query/'.$state['_parent']->id
                    );
                    $branch[] = array(
                        'label'     => sprintf(_('Option %s'), $state['_object']->get('Name')),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;
                case 'deleted_customer_poll_query_option':

                    //print_r($state['_object']->data);

                    $store    = get_object('Store', $state['_parent']->get('Store Key'));
                    $branch[] = array(
                        'label'     => _("Customer's insights").' '.$store->data['Store Code'],
                        'icon'      => 'graduation-cap',
                        'reference' => 'customers/'.$store->id.'/insights'
                    );
                    $branch[] = array(
                        'label'     => sprintf(_('Poll query %s'), $state['_parent']->get('Name')),
                        'icon'      => '',
                        'reference' => 'customers/'.$store->id.'/poll_query/'.$state['_parent']->id
                    );
                    $branch[] = array(
                        'label'     => sprintf(_('Deleted option %s'), $state['_object']->get('Customer Poll Query Option Deleted Name')),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;
                case 'pending_orders':
                    $branch[] = array(
                        'label'     => _("Pending orders").' '.$store->data['Store Code'],
                        'icon'      => 'clock',
                        'reference' => 'customers/pending_orders/'.$store->id
                    );
                    break;


                case 'product':


                    $branch[] = array(
                        'label'     => _('Customers').' '.$state['store']->get('Store Code'),
                        'icon'      => 'users',
                        'reference' => 'customers/'.$state['store']->id
                    );
                    $branch[] = array(
                        'label'     => $state['_parent']->get_formatted_id(),
                        'icon'      => 'user',
                        'reference' => 'customer/'.$state['_parent']->id
                    );
                    $branch[] = array(
                        'label'     => $state['_object']->get('Code'),
                        'icon'      => 'cube',
                        'reference' => 'customer/'.$state['_parent']->id.'/product/'.$state['_object']->id
                    );


                    break;

            }
            break;
        case 'suppliers':
            if ($state['section'] == 'dashboard') {
                $branch[] = array(
                    'label'     => _("Supplier's dashboard"),
                    'icon'      => 'tachometer-alt',
                    'reference' => 'dashboard'
                );
            } elseif ($state['section'] == 'suppliers') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => 'hand-holding-box',
                    'reference' => 'suppliers'
                );
            } elseif ($state['section'] == 'settings') {
                $branch[] = array(
                    'label'     => _("Suppliers' settings"),
                    'icon'      => 'sliders',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'orders') {
                $branch[] = array(
                    'label'     => _('Purchase orders'),
                    'icon'      => 'clipboard',
                    'reference' => 'suppliers.orders'
                );
            } elseif ($state['section'] == 'deliveries') {
                $branch[] = array(
                    'label'     => _('Deliveries'),
                    'icon'      => 'truck',
                    'reference' => 'suppliers.deliveries'
                );
            } elseif ($state['section'] == 'agents') {
                $branch[] = array(
                    'label'     => _('Agents'),
                    'icon'      => 'user-secret',
                    'reference' => 'agents'
                );
            } elseif ($state['section'] == 'agent.new') {
                $branch[] = array(
                    'label'     => _('Agents'),
                    'icon'      => '',
                    'reference' => 'agents'
                );
                $branch[] = array(
                    'label'     => _('New agent'),
                    'icon'      => 'user-secret',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'supplier') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$state['_object']->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['key']
                );

            } elseif ($state['section'] == 'supplier.new') {

                if ($state['parent'] == 'agent') {
                    $branch[] = array(
                        'label'     => _('Agents'),
                        'icon'      => '',
                        'reference' => 'agents'
                    );
                    $branch[] = array(
                        'label'     => '<span class="Agent_Code">'.$state['_parent']->get('Code').'</span>',
                        'icon'      => 'user-secret',
                        'reference' => 'agent/'.$state['parent_key']
                    );

                } else {
                    $branch[] = array(
                        'label'     => _('Suppliers'),
                        'icon'      => '',
                        'reference' => 'suppliers'
                    );

                }

                $branch[] = array(
                    'label'     => _('New supplier'),
                    'icon'      => 'hand-holding-box',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'supplier.attachment.new') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['parent_key']
                );
                $branch[] = array(
                    'label'     => _('Upload attachment'),
                    'icon'      => 'paperclip',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'supplier.attachment') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['parent_key']
                );
                $branch[] = array(
                    'label'     => '<span class="id Attachment_Caption">'.$state['_object']->get('Caption').'</span>',
                    'icon'      => 'paperclip',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'order' or $state['section'] == 'deleted_order') {

                if ($state['parent'] == 'account') {
                    $branch[] = array(
                        'label'     => _('Purchase orders'),
                        'icon'      => '',
                        'reference' => 'suppliers/orders'
                    );
                } elseif ($state['parent'] == 'supplier') {
                    $branch[] = array(
                        'label'     => _('Suppliers'),
                        'icon'      => '',
                        'reference' => 'suppliers'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Code'),
                        'icon'      => 'hand-holding-box',
                        'reference' => 'supplier/'.$state['parent_key']
                    );
                } elseif ($state['parent'] == 'agent') {
                    $branch[] = array(
                        'label'     => _('Agents'),
                        'icon'      => '',
                        'reference' => 'agents'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Agent_Code">'.$state['_parent']->get('Code'),
                        'icon'      => 'user-secret',
                        'reference' => 'agent/'.$state['parent_key']
                    );
                } elseif ($state['parent'] == 'supplier_part') {

                    $supplier = new Supplier(
                        $state['_parent']->get('Supplier Part Supplier Key')
                    );

                    $branch[] = array(
                        'label'     => _('Suppliers'),
                        'icon'      => '',
                        'reference' => 'suppliers'
                    );
                    $branch[] = array(
                        'label'     => '<span class="Supplier_Code">'.$supplier->get('Code').'</span>',
                        'icon'      => 'hand-holding-box',
                        'reference' => 'supplier/'.$supplier->id
                    );
                    $branch[] = array(
                        'label'     => '<span class="Supplier_Part_Reference">'.$state['_parent']->get('Reference').'</span>',
                        'icon'      => 'stop',
                        'reference' => 'supplier/'.$supplier->id.'/part/'.$state['_parent']->id
                    );

                }
                $branch[] = array(
                    'label'     => '<span class="Purchase_Order_Public_ID">'.$state['_object']->get('Public ID').'</span>',
                    'icon'      => 'clipboard',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'delivery') {

                if ($state['parent'] == 'account') {
                    $branch[] = array(
                        'label'     => _('Deliveries'),
                        'icon'      => '',
                        'reference' => 'suppliers/deliveries'
                    );
                } elseif ($state['parent'] == 'supplier') {
                    $branch[] = array(
                        'label'     => _('Suppliers'),
                        'icon'      => '',
                        'reference' => 'suppliers'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Code'),
                        'icon'      => 'hand-holding-box',
                        'reference' => 'supplier/'.$state['parent_key']
                    );
                } elseif ($state['parent'] == 'agent') {
                    $branch[] = array(
                        'label'     => _('Agents'),
                        'icon'      => '',
                        'reference' => 'agents'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Agent_Code">'.$state['_parent']->get('Code'),
                        'icon'      => 'user-secret',
                        'reference' => 'agent/'.$state['parent_key']
                    );
                }
                $branch[] = array(
                    'label'     => '<span class="Supplier_Delivery_Public_ID">'.$state['_object']->get('Public ID').'</span>',
                    'icon'      => 'truck',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'supplier.order.item') {

                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Parent Code'),
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['_parent']->get(
                            'Purchase Order Parent Key'
                        )
                );
                $branch[] = array(
                    'label'     => '<span class="Purchase_Order_Public_ID">'.$state['_parent']->get('Public ID').'</span>',
                    'icon'      => 'clipboard',
                    'reference' => 'supplier/'.$state['_parent']->get(
                            'Purchase Order Parent Key'
                        ).'/order/'.$state['parent_key']
                );


                $branch[] = array(
                    'label'     => '<span class="Supplier_Part_Reference">'.$state['_object']->get('Reference').'</span>',
                    'icon'      => 'bars',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'agent.order.item') {


                $branch[] = array(
                    'label'     => _('Agents'),
                    'icon'      => '',
                    'reference' => 'agents'
                );
                $branch[] = array(
                    'label'     => '<span class="id Agent_Code">'.$state['_parent']->get('Parent Code'),
                    'icon'      => 'user-secret',
                    'reference' => 'agent/'.$state['_parent']->get(
                            'Purchase Order Parent Key'
                        )
                );
                $branch[] = array(
                    'label'     => '<span class="Purchase_Order_Public_ID">'.$state['_parent']->get('Public ID').'</span>',
                    'icon'      => 'clipboard',
                    'reference' => 'supplier/'.$state['_parent']->get(
                            'Purchase Order Parent Key'
                        ).'/order/'.$state['parent_key']
                );


                $branch[] = array(
                    'label'     => '<span class="Supplier_Part_Reference">'.$state['_object']->get('Reference').'</span>',
                    'icon'      => 'bars',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'agent') {
                $branch[] = array(
                    'label'     => _('Agents'),
                    'icon'      => '',
                    'reference' => 'agents'
                );
                $branch[] = array(
                    'label'     => '<span class="Agent_Code">'.$state['_object']->get('Code').'</span> <span class="Agent_Name italic">'.$state['_object']->get('Name').'</span>',
                    'icon'      => 'user-secret',
                    'reference' => 'agent/'.$state['key']
                );

            } elseif ($state['section'] == 'agent.new') {
                $branch[] = array(
                    'label'     => _('Agents'),
                    'icon'      => '',
                    'reference' => 'agents'
                );
                $branch[] = array(
                    'label'     => _('New agent'),
                    'icon'      => 'user-secret',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'supplier_part') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="Supplier_Code">'.$state['_parent']->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['_parent']->id
                );
                $branch[] = array(
                    'label'     => '<span class="Supplier_Part_Reference">'.$state['_object']->get('Reference').'</span>',
                    'icon'      => 'stop',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'supplier_part.new') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="Supplier_Code">'.$state['_parent']->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['_parent']->id
                );
                $branch[] = array(
                    'label'     => _("New supplier's part"),
                    'icon'      => 'stop',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'categories') {
                $branch[] = array(
                    'label'     => _("Suppliers's categories"),
                    'icon'      => 'sitemap',
                    'reference' => 'suppliers/categories/'
                );

            } elseif ($state['section'] == 'category') {


                $category = $state['_object'];
                $branch[] = array(
                    'label'     => _("Suppliers's categories"),
                    'icon'      => 'sitemap',
                    'reference' => 'suppliers/categories/'
                );


                if (isset($state['metadata'])) {
                    $parent_category_keys = $state['metadata'];
                } else {

                    $parent_category_keys = preg_split(
                        '/\>/', $category->get('Category Position')
                    );
                }


                foreach ($parent_category_keys as $category_key) {
                    if (!is_numeric($category_key)) {
                        continue;
                    }
                    if ($category_key == $state['key']) {
                        $branch[] = array(
                            'label'     => '<span class="Category_Label">'.$category->get('Label').'</span>',
                            'icon'      => '',
                            'reference' => ''
                        );
                        break;
                    } else {

                        $parent_category = new Category($category_key);
                        if ($parent_category->id) {

                            $branch[] = array(
                                'label'     => $parent_category->get(
                                    'Label'
                                ),
                                'icon'      => '',
                                'reference' => 'suppliers/category/'.$parent_category->id
                            );

                        }
                    }
                }

                break;


            } elseif ($state['section'] == 'main_category.new') {
                $branch[] = array(
                    'label'     => _("Suppliers's categories"),
                    'icon'      => 'sitemap',
                    'reference' => 'suppliers/categories/'
                );
                $branch[] = array(
                    'label'     => _("New main category"),
                    'icon'      => '',
                    'reference' => '/'
                );

            } elseif ($state['section'] == 'supplier.user.new') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Code').'</span> <span class="Supplier_Name italic">'.$state['_parent']->get('Name').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['parent_key']
                );
                $branch[] = array(
                    'label'     => _('New system user'),
                    'icon'      => 'terminal',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'agent.user.new') {
                $branch[] = array(
                    'label'     => _('Agents'),
                    'icon'      => '',
                    'reference' => 'agents'
                );
                $branch[] = array(
                    'label'     => '<span class="Agent_Code">'.$state['_parent']->get('Code').'</span> <span class="Agent_Name italic">'.$state['_parent']->get('Name').'</span>',
                    'icon'      => 'user-secret',
                    'reference' => 'agent/'.$state['parent_key']
                );
                $branch[] = array(
                    'label'     => _('New system user'),
                    'icon'      => 'terminal',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'timeseries_record') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Parent')->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['_parent']->get('Timeseries Parent Key')
                );
                $branch[] = array(
                    'label'     => '<span class="id">'.$state['_object']->get('Code').'</span>',
                    'icon'      => 'table',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'supplier_delivery.attachment.new') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );


                $supplier = get_object('Supplier', $state['_parent']->get('Parent Key'));

                //  print_r($state['_parent']);

                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$supplier->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$supplier->id
                );

                $branch[] = array(
                    'label'     => '<span class="Supplier_Delivery_Public_ID">'.$state['_parent']->get('Public ID').'</span>',
                    'icon'      => 'truck',
                    'reference' => ''
                );


                $branch[] = array(
                    'label'     => _('Upload attachment'),
                    'icon'      => 'paperclip',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'supplier_delivery.attachment') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['parent_key']
                );
                $branch[] = array(
                    'label'     => '<span class="id Attachment_Caption">'.$state['_object']->get('Caption').'</span>',
                    'icon'      => 'paperclip',
                    'reference' => ''
                );

            }


            break;
        case 'orders_server':

            $state['current_store'] = 0;
            $branch[]               = array(
                'label'     => '',
                'icon'      => 'indent',
                'reference' => 'receipts'
            );


            if ($state['section'] == 'dashboard') {

                $branch[] = array(
                    'label'     => _("Orders control panel").' ('._('All stores').')',
                    'icon'      => '',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'orders') {

                $branch[] = array(
                    'label'     => _('Orders').' ('._('All stores').')',
                    'icon'      => '',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'mailshot') {


                $branch[] = array(
                    'label'     => _("Orders control panel").' ('._('All stores').')',
                    'icon'      => '',
                    'reference' => 'orders/all/dashboard/website/mailshots'
                );


                $branch[] = array(
                    'label'     => '<span class="Email_Campaign_Name">'.$state['_object']->get('Name').'</span>',
                    'icon'      => 'at',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'invoices') {

                $branch[] = array(
                    'label'     => _('Invoices').' ('._('All stores').')',
                    'icon'      => '',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'group_by_store') {

                $branch[] = array(
                    'label'     => _('Orders grouped by store'),
                    'icon'      => 'compress',
                    'reference' => ''
                );


            }


            break;

        case 'delivery_notes_server':
            $state['current_store'] = 0;
            $branch[]               = array(
                'label'     => '',
                'icon'      => 'indent',
                'reference' => 'receipts'
            );

            if ($state['section'] == 'delivery_notes') {


                if ($user->get_number_stores() > 1) {
                    $branch[] = array(
                        'label'     => _('Delivery Notes').' ('._('All stores').')',
                        'icon'      => '',
                        'reference' => 'delivery_notes/all'
                    );
                }


            } elseif ($state['section'] == 'group_by_store') {

                $branch[] = array(
                    'label'     => _('Delivery notes grouped by store'),
                    'icon'      => 'compress',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'pending_delivery_notes') {

                $branch[] = array(
                    'label'     => _('Pending delivery notes'),
                    'icon'      => 'stream',
                    'reference' => ''
                );


            }


            break;

        case 'orders':
            $state['current_store'] = $state['store']->id;
            $branch[]               = array(
                'label'     => '',
                'icon'      => 'indent',
                'reference' => 'receipts'
            );
            switch ($state['section']) {

                case 'dashboard':

                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => 'stream',
                            'reference' => 'orders/all/dashboard'
                        );
                    }

                    $branch[] = array(
                        'label'     => $state['store']->data['Store Code'],
                        'icon'      => 'stream',
                        'reference' => ''
                    );

                    break;


                case 'orders':

                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'orders/all'
                        );
                    }


                    $branch[] = array(
                        'label'     => _('Orders').' '.$state['store']->data['Store Code'],
                        'icon'      => 'shopping-cart',
                        'reference' => ''
                    );


                    break;

                case 'payments':
                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label' => _('Payments').' ('._('All stores').')',
                            'icon'  => 'indent',
                            'url'   => 'payments/all'
                        );
                    }
                    break;

                case 'payment':

                    if ($state['parent'] == 'order') {


                        $branch[] = array(
                            'label'     => _('Orders').' '.$state['store']->data['Store Code'],
                            'icon'      => '',
                            'reference' => 'orders/'.$state['store']->id
                        );

                        $branch[] = array(
                            'label'     => $state['_parent']->get('Order Public ID'),
                            'icon'      => 'shopping-cart',
                            'reference' => ''
                        );


                    }

                    $branch[] = array(
                        'label'     => $state['_object']->get('Payment Transaction ID'),
                        'icon'      => 'dollar-sign',
                        'reference' => ''
                    );

                    break;

                case 'order':

                    if ($state['parent'] == 'customer') {

                        $customer = new Customer($state['parent_key']);
                        if ($customer->id) {
                            if ($user->get_number_stores() > 1) {


                                $branch[] = array(
                                    'label'     => _(
                                        'Customers (All stores)'
                                    ),
                                    'icon'      => 'indent',
                                    'reference' => 'customers/all'
                                );

                            }

                            $store = new Store(
                                $customer->data['Customer Store Key']
                            );


                            $branch[] = array(
                                'label'     => _(
                                        'Customers'
                                    ).' '.$store->data['Store Code'],
                                'icon'      => 'users',
                                'reference' => 'customers/'.$store->id
                            );
                            $branch[] = array(
                                'label'     => _(
                                        'Customer'
                                    ).' '.$customer->get_formatted_id(),
                                'icon'      => 'user',
                                'reference' => 'customer/'.$customer->id
                            );
                        }


                    } else {


                        if ($user->get_number_stores() > 1) {
                            $branch[] = array(
                                'label'     => '('._('All stores').')',
                                'icon'      => 'stream',
                                'reference' => 'orders/all/dashboard'
                            );
                        }


                        if (!empty($state['extra'])) {

                            switch ($state['extra']) {
                                case 'submitted_not_paid':
                                    $label = _('Submitted (not paid)');
                                    break;
                                case 'submitted':
                                    $label = _('Submitted (paid)');
                                    break;
                                case 'website':
                                    $label = _('In basket');
                                    break;
                                case 'in_warehouse':
                                    $label = _('In warehouse');

                                    break;
                                case 'in_warehouse_with_alerts':
                                    $label = _('In warehouse').' ('._('with alerts').')';

                                    break;

                                case 'packed_done':
                                    $label = _('Packed & closed');

                                    break;
                                case 'approved':
                                    $label = _('Invoiced');

                                    break;
                                case 'dispatched_today':
                                    $label = _('Dispatched today');

                                    break;
                                default:
                                    $label = '';
                            }

                            $branch[] = array(
                                'label'     => $label.' <span class="id">'.$state['store']->data['Store Code'].'</span>',
                                'icon'      => 'stream',
                                'reference' => 'orders/'.$state['store']->id.'/dashboard/'.$state['extra']
                            );

                        } else {
                            $branch[] = array(
                                'label'     => _('Orders').' '.$state['store']->data['Store Code'],
                                'icon'      => '',
                                'reference' => 'orders/'.$state['store']->id
                            );
                        }


                    }
                    $branch[] = array(
                        'label'     => $state['_object']->get('Order Public ID'),
                        'icon'      => 'shopping-cart',
                        'reference' => ''
                    );

                    break;

                case 'return.new':


                    $branch[] = array(
                        'label'     => _('Orders').' '.$state['store']->data['Store Code'],
                        'icon'      => '',
                        'reference' => 'orders/'.$state['store']->id
                    );


                    $branch[] = array(
                        'label'     => $state['_object']->get('Order Public ID'),
                        'icon'      => 'shopping-cart',
                        'reference' => sprintf('orders/%d/%d', $state['_object']->get('Order Store Key'), $state['_object']->id)
                    );


                    $branch[] = array(
                        'label'     => _('Creating return'),
                        'icon'      => 'backspace',
                        'reference' => ''
                    );
                    break;


                case 'refund.new':

                    if ($state['parent'] == 'customer') {

                        $customer = new Customer($state['parent_key']);
                        if ($customer->id) {
                            if ($user->get_number_stores() > 1) {


                                $branch[] = array(
                                    'label'     => _('Customers (All stores)'),
                                    'icon'      => 'indent',
                                    'reference' => 'customers/all'
                                );

                            }

                            $store = new Store(
                                $customer->data['Customer Store Key']
                            );


                            $branch[] = array(
                                'label'     => _(
                                        'Customers'
                                    ).' '.$store->data['Store Code'],
                                'icon'      => 'users',
                                'reference' => 'customers/'.$store->id
                            );
                            $branch[] = array(
                                'label'     => _(
                                        'Customer'
                                    ).' '.$customer->get_formatted_id(),
                                'icon'      => 'user',
                                'reference' => 'customer/'.$customer->id
                            );
                        }


                    } else {


                        $branch[] = array(
                            'label'     => _('Orders').' '.$state['store']->data['Store Code'],
                            'icon'      => '',
                            'reference' => 'orders/'.$state['store']->id
                        );


                    }
                    $branch[] = array(
                        'label'     => $state['_object']->get('Order Public ID'),
                        'icon'      => 'shopping-cart',
                        'reference' => ''
                    );

                    break;

                case 'delivery_note':

                    $store = new Store(
                        $state['_object']->data['Delivery Note Store Key']
                    );

                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'orders/all'
                        );
                    }
                    $branch[] = array(
                        'label'     => _('Orders').' '.$store->data['Store Code'],
                        'icon'      => '',
                        'reference' => 'orders/'.$store->id
                    );

                    $parent   = new Order($state['parent_key']);
                    $branch[] = array(
                        'label'     => $parent->get(
                            'Order Public ID'
                        ),
                        'icon'      => 'shopping-cart',
                        'reference' => 'orders/'.$store->id.'/'.$state['parent_key']
                    );


                    $branch[] = array(
                        'label'     => $state['_object']->get(
                            'Delivery Note ID'
                        ),
                        'icon'      => 'truck fa-flip-horizontal',
                        'reference' => ''
                    );
                    break;

                case 'invoices':
                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'invoices/all'
                        );
                    }
                    $store = new Store($state['parent_key']);

                    $branch[] = array(
                        'label'     => _('Invoices').' '.$store->data['Store Code'],
                        'icon'      => '',
                        'reference' => ''
                    );


                    break;

                case 'invoice':
                case 'refund':
                case 'deleted_invoice':
                case 'deleted_refund':
                    $store = new Store($state['_object']->data['Invoice Store Key']);

                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'orders/all'
                        );
                    }
                    $branch[] = array(
                        'label'     => _('Orders').' '.$store->data['Store Code'],
                        'icon'      => '',
                        'reference' => 'orders/'.$store->id
                    );

                    $parent   = new Order($state['parent_key']);
                    $branch[] = array(
                        'label'     => $parent->get('Order Public ID'),
                        'icon'      => 'shopping-cart',
                        'reference' => 'orders/'.$store->id.'/'.$state['parent_key']
                    );


                    if ($state['_object']->deleted) {
                        $branch[] = array(
                            'label'     => '<span class="strikethrough">'.$state['_object']->get('Invoice Public ID').'</span> ('._('Deleted').')',
                            'icon'      => 'file-alt',
                            'reference' => ''
                        );
                    } else {
                        $branch[] = array(
                            'label'     => $state['_object']->get('Invoice Public ID'),
                            'icon'      => 'file-alt',
                            'reference' => ''
                        );
                    }


                    break;
                case 'mailshot':


                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'orders/all'
                        );
                    }


                    $branch[] = array(
                        'label'     => _('Orders control panel').' '.$state['store']->get('Code'),
                        'icon'      => '',
                        'reference' => 'orders/'.$state['store']->id.'/dashboard/website/mailshots'
                    );


                    $branch[] = array(
                        'label'     => '<span class="Email_Campaign_Name">'.$state['_object']->get('Name').'</span>',
                        'icon'      => 'at',
                        'reference' => ''
                    );
                    break;

                case 'email_tracking':
                    //$store = get_object('Store', $state['_parent']->get('Store Key'));

                    $branch[] = array(
                        'label'     => _('Orders').' '.$state['store']->data['Store Code'],
                        'icon'      => '',
                        'reference' => 'orders/'.$state['store']->id
                    );


                    $branch[] = array(
                        'label'     => $state['_parent']->get('Order Public ID'),
                        'icon'      => 'shopping-cart',
                        'reference' => 'orders/'.$state['store']->id.'/'.$state['_parent']->id
                    );


                    $branch[] = array(
                        'label'     => _('Sent email').' <span class="id">'.$state['_object']->get('Sent Date').'</span>',
                        'icon'      => '',
                        'reference' => ''
                    );


                    break;
                case 'purge':
                    //$store = get_object('Store', $state['_parent']->get('Store Key'));

                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'orders/all/dashboard'
                        );
                    }

                    $branch[] = array(
                        'label'     => _('Orders control panel').' '.$state['store']->data['Store Code'],
                        'icon'      => '',
                        'reference' => ''
                    );


                    $branch[] = array(
                        'label'     => _('Purge').' <span class="id">'.$state['_object']->get('Date').'</span>',
                        'icon'      => '',
                        'reference' => ''
                    );


                    break;
            }

            break;
        case 'delivery_notes':
            $state['current_store'] = $state['store']->id;
            $branch[]               = array(
                'label'     => '',
                'icon'      => 'indent',
                'reference' => 'receipts'
            );

            switch ($state['section']) {
                case 'delivery_notes':

                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'delivery_notes/all'
                        );
                    }
                    $store = new Store($state['parent_key']);

                    $branch[] = array(
                        'label'     => _(
                                'Delivery Notes'
                            ).' '.$store->data['Store Code'],
                        'icon'      => '',
                        'reference' => 'delivery_notes/'.$store->id
                    );


                    break;


                case 'delivery_note':

                    if ($state['parent'] == 'customer') {

                        $customer = new Customer($state['parent_key']);
                        if ($customer->id) {
                            if ($user->get_number_stores() > 1) {


                                $branch[] = array(
                                    'label'     => _(
                                        'Customers (All stores)'
                                    ),
                                    'icon'      => '',
                                    'reference' => 'customers/all'
                                );

                            }

                            $store = new Store(
                                $customer->data['Customer Store Key']
                            );


                            $branch[] = array(
                                'label'     => _(
                                        'Customers'
                                    ).' '.$store->data['Store Code'],
                                'icon'      => 'users',
                                'reference' => 'customers/'.$store->id
                            );
                            $branch[] = array(
                                'label'     => _(
                                        'Customer'
                                    ).' '.$customer->get_formatted_id(),
                                'icon'      => 'user',
                                'reference' => 'customer/'.$customer->id
                            );
                        }


                    } else {
                        $store = new Store(
                            $state['_object']->data['Delivery Note Store Key']
                        );

                        if ($user->get_number_stores() > 1) {
                            $branch[] = array(
                                'label'     => '('._('All stores').')',
                                'icon'      => '',
                                'reference' => 'delivery_notes/all'
                            );
                        }
                        $branch[] = array(
                            'label'     => _(
                                    'Delivery notes'
                                ).' '.$store->data['Store Code'],
                            'icon'      => '',
                            'reference' => 'delivery_notes/'.$store->id
                        );


                    }
                    $branch[] = array(
                        'label'     => $state['_object']->get(
                            'Delivery Note ID'
                        ),
                        'icon'      => 'truck fa-flip-horizontal',
                        'reference' => ''
                    );

                    break;

                case 'order':

                    $store = new Store(
                        $state['_object']->data['Order Store Key']
                    );

                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'delivery_notes/all'
                        );
                    }
                    $branch[] = array(
                        'label'     => _(
                                'Delivery notes'
                            ).' '.$store->data['Store Code'],
                        'icon'      => '',
                        'reference' => 'delivery_notes/'.$store->id
                    );

                    $parent   = new DeliveryNote($state['parent_key']);
                    $branch[] = array(
                        'label'     => $parent->get(
                            'Delivery Note ID'
                        ),
                        'icon'      => 'truck fa-flip-horizontal',
                        'reference' => 'delivery_notes/'.$store->id.'/'.$state['parent_key']
                    );


                    $branch[] = array(
                        'label'     => $state['_object']->get(
                            'Order Public ID'
                        ),
                        'icon'      => 'shopping-cart',
                        'reference' => ''
                    );
                    $branch[] = array(
                        'label'     => $state['_object']->get(
                            'Delivery Note ID'
                        ),
                        'icon'      => 'truck fa-flip-horizontal',
                        'reference' => ''
                    );

                    break;

                case 'invoice':

                    $store = new Store(
                        $state['_object']->data['Invoice Store Key']
                    );

                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'delivery_notes/all'
                        );
                    }
                    $branch[] = array(
                        'label'     => _(
                                'Delivery notes'
                            ).' '.$store->data['Store Code'],
                        'icon'      => '',
                        'reference' => 'delivery_notes/'.$store->id
                    );

                    $parent   = new DeliveryNote($state['parent_key']);
                    $branch[] = array(
                        'label'     => $parent->get(
                            'Delivery Note ID'
                        ),
                        'icon'      => 'truck fa-flip-horizontal',
                        'reference' => 'delivery_notes/'.$store->id.'/'.$state['parent_key']
                    );


                    $branch[] = array(
                        'label'     => $state['_object']->get(
                            'Invoice Public ID'
                        ),
                        'icon'      => 'file-alt',
                        'reference' => ''
                    );
                    $branch[] = array(
                        'label'     => $state['_object']->get(
                            'Delivery Note ID'
                        ),
                        'icon'      => 'truck fa-flip-horizontal',
                        'reference' => ''
                    );

                    break;


            }

            break;


        /*
        case 'invoices':
            $branch[] = array(
                'label'     => '',
                'icon'      => 'bars',
                'reference' => 'receipts'
            );

            switch ($state['section']) {



                case 'payments':
                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label' => _('Payments').' ('._(
                                    'All stores'
                                ).')',
                            'icon'  => '',
                            'url'   => 'invoices/payments/all'
                        );
                    }
                    break;

                case 'invoice':

                    if ($state['parent'] == 'customer') {

                        $customer = new Customer($state['parent_key']);
                        if ($customer->id) {
                            if ($user->get_number_stores() > 1) {


                                $branch[] = array(
                                    'label'     => _(
                                        'Customers (All stores)'
                                    ),
                                    'icon'      => '',
                                    'reference' => 'customers/all'
                                );

                            }

                            $store = new Store(
                                $customer->data['Customer Store Key']
                            );


                            $branch[] = array(
                                'label'     => _(
                                        'Customers'
                                    ).' '.$store->data['Store Code'],
                                'icon'      => 'users',
                                'reference' => 'customers/'.$store->id
                            );
                            $branch[] = array(
                                'label'     => _(
                                        'Customer'
                                    ).' '.$customer->get_formatted_id(),
                                'icon'      => 'user',
                                'reference' => 'customer/'.$customer->id
                            );
                        }


                    } else {
                        $store = new Store(
                            $state['_object']->data['Invoice Store Key']
                        );

                        if ($user->get_number_stores() > 1) {
                            $branch[] = array(
                                'label'     => '('._('All stores').')',
                                'icon'      => '',
                                'reference' => 'invoices/all'
                            );
                        }
                        $branch[] = array(
                            'label'     => _('Invoices').' '.$store->data['Store Code'],
                            'icon'      => '',
                            'reference' => 'invoices/'.$store->id
                        );


                    }
                    $branch[] = array(
                        'label'     => $state['_object']->get(
                            'Invoice Public ID'
                        ),
                        'icon'      => 'file-alt',
                        'reference' => ''
                    );

                    break;

                case 'delivery_note':

                    $store = new Store(
                        $state['_object']->data['Delivery Note Store Key']
                    );


                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'invoices/all'
                        );
                    }
                    $branch[] = array(
                        'label'     => _('Invoices').' '.$store->data['Store Code'],
                        'icon'      => '',
                        'reference' => 'invoices/'.$store->id
                    );

                    $parent   = new Invoice($state['parent_key']);
                    $branch[] = array(
                        'label'     => $parent->get(
                            'Invoice Public ID'
                        ),
                        'icon'      => 'file-alt',
                        'reference' => 'invoices/'.$store->id.'/'.$state['parent_key']
                    );


                    $branch[] = array(
                        'label'     => $state['_object']->get(
                            'Delivery Note ID'
                        ),
                        'icon'      => 'truck fa-flip-horizontal',
                        'reference' => ''
                    );
                    break;


                case 'order':

                    $store = new Store(
                        $state['_object']->data['Order Store Key']
                    );


                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'invoices/all'
                        );
                    }
                    $branch[] = array(
                        'label'     => _('Invoices').' '.$store->data['Store Code'],
                        'icon'      => '',
                        'reference' => 'invoices/'.$store->id
                    );

                    $parent   = new Invoice($state['parent_key']);
                    $branch[] = array(
                        'label'     => $parent->get(
                            'Invoice Public ID'
                        ),
                        'icon'      => 'file-alt',
                        'reference' => 'invoices/'.$store->id.'/'.$state['parent_key']
                    );


                    $branch[] = array(
                        'label'     => $state['_object']->get(
                            'Order Public ID'
                        ),
                        'icon'      => 'shopping-cart',
                        'reference' => ''
                    );
                    break;


            }


            break;

        */

        case 'help':
            switch ($state['section']) {
                case 'help':
                    $branch[] = array(
                        'label'     => _('Help'),
                        'icon'      => '',
                        'reference' => 'help'
                    );
                    break;


            }
            break;
        case 'hr':
            switch ($state['section']) {
                case 'employees':
                    $branch[] = array(
                        'label'     => _('Employees'),
                        'icon'      => '',
                        'reference' => 'hr'
                    );
                    break;
                case 'contractors':
                    $branch[] = array(
                        'label'     => _('Contractors'),
                        'icon'      => 'hand-spock',
                        'reference' => 'hr/contractors'
                    );
                    break;
                case 'hr.history':
                    $branch[] = array(
                        'label'     => _('Manpower history'),
                        'icon'      => '',
                        'reference' => 'hr/history'
                    );
                    break;

                case 'employee':
                    $branch[] = array(
                        'label'     => _('Employees'),
                        'icon'      => '',
                        'reference' => 'hr'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Staff_Alias">'.$state['_object']->get('Staff Alias').'</span>',
                        'icon'      => 'hand-rock',
                        'reference' => 'employee/'.$state['_object']->id
                    );
                    break;
                case 'deleted.employee':
                    $branch[] = array(
                        'label'     => _('Deleted employees'),
                        'icon'      => '',
                        'reference' => 'hr/deleted_employees'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Staff_Alias">'.$state['_object']->get('Staff Alias').'</span> <i class="far fa-trash-alt padding_left_5" aria-hidden="true"></i> ',
                        'icon'      => 'hand-rock',
                        'reference' => 'employee/'.$state['_object']->id
                    );
                    break;

                case 'employee.attachment.new':
                    $branch[] = array(
                        'label'     => _('Employees'),
                        'icon'      => '',
                        'reference' => 'hr'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Staff_Alias">'.$state['_parent']->get('Staff Alias').'</span>',
                        'icon'      => 'hand-rock',
                        'reference' => 'employee/'.$state['_parent']->id
                    );
                    $branch[] = array(
                        'label' => _('New attachment'),
                        'icon'  => 'paperclip'
                    );

                    break;


                case 'employee.new':
                    $branch[] = array(
                        'label'     => _('Employees'),
                        'icon'      => '',
                        'reference' => 'hr'
                    );
                    $branch[] = array(
                        'label' => _('New employee'),
                        'icon'  => ''
                    );
                    break;
                case 'contractor':
                    $branch[] = array(
                        'label'     => _('Contractors'),
                        'icon'      => '',
                        'reference' => 'hr/contractors'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Staff_Alias">'.$state['_object']->get('Staff Alias').'</span>',
                        'icon'      => 'hand-spock',
                        'reference' => 'employee/'.$state['_object']->id
                    );
                    break;
                case 'contractor.user.new':
                    $branch[] = array(
                        'label'     => _('Contractors'),
                        'icon'      => '',
                        'reference' => 'hr/contractors'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Staff_Alias">'.$state['_parent']->get('Staff Alias').'</span>',
                        'icon'      => 'hand-spock',
                        'reference' => 'contractor/'.$state['_parent']->id
                    );
                    $branch[] = array(
                        'label' => _('New system user'),
                        'icon'  => ''
                    );

                    break;
                case 'employee.user.new':
                    $branch[] = array(
                        'label'     => _('Employees'),
                        'icon'      => '',
                        'reference' => 'hr'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Staff_Alias">'.$state['_parent']->get('Staff Alias').'</span>',
                        'icon'      => 'hand-rock',
                        'reference' => 'employee/'.$state['_parent']->id
                    );
                    $branch[] = array(
                        'label' => _('New system user'),
                        'icon'  => ''
                    );

                    break;
                case 'deleted.contractor':
                    $branch[] = array(
                        'label'     => _('Deleted contractors'),
                        'icon'      => '',
                        'reference' => 'hr/deleted_contractors'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Staff_Alias">'.$state['_object']->get('Staff Alias').'</span> <i class="far fa-trash-alt padding_left_5" aria-hidden="true"></i> ',
                        'icon'      => 'hand-spock',
                        'reference' => 'employee/'.$state['_object']->id
                    );
                    break;
                case 'contractor.new':
                    $branch[] = array(
                        'label'     => _('Contractors'),
                        'icon'      => '',
                        'reference' => 'hr/contractors'
                    );
                    $branch[] = array(
                        'label' => _('New contractor'),
                        'icon'  => 'hand-spock'
                    );
                    break;

                case 'employee.attachment':
                    include_once 'class.Staff.php';
                    $employee = new Staff($state['parent_key']);
                    $branch[] = array(
                        'label'     => _('Employees'),
                        'icon'      => '',
                        'reference' => 'hr'
                    );

                    $branch[] = array(
                        'label'     => '<span class="id Staff_Alias">'.$employee->get('Staff Alias').'</span>',
                        'icon'      => 'hand-rock',
                        'reference' => 'employee/'.$employee->id
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Attachment_Caption">'.$state['_object']->get('Caption').'</span>',
                        'icon'      => 'paperclip',
                        'reference' => 'employee/'.$employee->id.'/attachment/'.$state['_object']->id
                    );
                    break;

                case 'organization':
                    $branch[] = array(
                        'label'     => _('Organization'),
                        'icon'      => '',
                        'reference' => 'hr/organization'
                    );
                    break;
                case 'position':
                    $branch[] = array(
                        'label'     => _('Job positions'),
                        'icon'      => '',
                        'reference' => 'hr/organization'
                    );
                    $branch[] = array(
                        'label'     => $state['_object']->get('title'),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;

                case 'timesheets':
                    $branch[] = array(
                        'label'     => _("Employees' calendar"),
                        'icon'      => '',
                        'reference' => 'timesheets/day/'.date(
                                'Ymd'
                            )
                    );
                    if ($state['parent'] == 'year') {
                        $branch[] = array(
                            'label'     => $state['parent_key'],
                            'icon'      => '',
                            'reference' => 'timesheets/year/'.$state['parent_key']
                        );

                    } elseif ($state['parent'] == 'month') {
                        $year     = substr($state['parent_key'], 0, 4);
                        $month    = substr($state['parent_key'], 4, 2);
                        $branch[] = array(
                            'label'     => $year,
                            'icon'      => '',
                            'reference' => 'timesheets/year/'.$year
                        );

                        $date     = strtotime("$year-$month-01");
                        $branch[] = array(
                            'label'     => strftime('%B', $date),
                            'icon'      => '',
                            'reference' => 'timesheets/month/'.$state['parent_key']
                        );

                    } elseif ($state['parent'] == 'week') {
                        $year     = substr($state['parent_key'], 0, 4);
                        $week     = substr($state['parent_key'], 4, 2);
                        $branch[] = array(
                            'label'     => $year,
                            'icon'      => '',
                            'reference' => 'timesheets/year/'.$year
                        );

                        $date     = strtotime("$year".'W'.$week);
                        $branch[] = array(
                            'label'     => sprintf(
                                _('%s week (starting %s %s)'), get_ordinal_suffix($week), strftime('%a', $date), get_ordinal_suffix(strftime('%d', $date))
                            ),
                            'icon'      => '',
                            'reference' => 'timesheets/week/'.$year.$week
                        );

                    } elseif ($state['parent'] == 'day') {

                        $year  = substr($state['parent_key'], 0, 4);
                        $month = substr($state['parent_key'], 4, 2);
                        $day   = substr($state['parent_key'], 6, 2);

                        $date = strtotime("$year-$month-$day");

                        $branch[] = array(
                            'label'     => $year,
                            'icon'      => '',
                            'reference' => 'timesheets/year/'.$year
                        );

                        $branch[] = array(
                            'label'     => strftime('%B', $date),
                            'icon'      => '',
                            'reference' => 'timesheets/month/'.$year.$month
                        );
                        $branch[] = array(
                            'label'     => strftime('%a', $date).' '.get_ordinal_suffix(strftime('%d', $date)),
                            'icon'      => '',
                            'reference' => 'timesheets/month/'.$year.$month.$day
                        );

                    }

            }
            break;


        case 'inventory':


            switch ($state['section']) {
                case 'inventory':
                    $branch[] = array(
                        'label'     => _('Inventory'),
                        'icon'      => 'th-large',
                        'reference' => ''
                    );
                    break;
                case 'part':


                    if ($state['parent'] == 'category') {
                        $category = $state['_parent'];
                        $branch[] = array(
                            'label'     => _(
                                "Parts's families"
                            ),
                            'icon'      => 'sitemap',
                            'reference' => 'inventory/categories'
                        );


                        if (isset($state['metadata'])) {
                            $parent_category_keys = $state['metadata'];
                        } else {

                            $parent_category_keys = preg_split(
                                '/\>/', $category->get('Category Position')
                            );
                        }


                        foreach ($parent_category_keys as $category_key) {
                            if (!is_numeric($category_key)) {
                                continue;
                            }
                            if ($category_key == $state['parent_key']) {
                                $branch[] = array(
                                    'label'     => '<span class="Category_Label">'.$category->get('Label').'</span>',
                                    'icon'      => '',
                                    'reference' => 'inventory/category/'.$category_key
                                );
                                break;
                            } else {

                                $parent_category = new Category($category_key);
                                if ($parent_category->id) {

                                    $branch[] = array(
                                        'label'     => $parent_category->get(
                                            'Label'
                                        ),
                                        'icon'      => '',
                                        'reference' => 'inventory/category/'.$parent_category->id
                                    );

                                }
                            }
                        }

                    } else {
                        $branch[] = array(
                            'label'     => _('Inventory'),
                            'icon'      => 'th-large',
                            'reference' => 'inventory'
                        );

                    }

                    $branch[] = array(
                        'label'     => '<span class="id Part_Reference">'.$state['_object']->get('Reference').'</span>',
                        'icon'      => 'box',
                        'reference' => ''
                    );

                    break;

                case 'part.image':

                    $branch[] = array(
                        'label'     => _('Inventory'),
                        'icon'      => 'th-large',
                        'reference' => 'inventory'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Part_Reference">'.$state['_parent']->get('Reference').'</span>',
                        'icon'      => 'box',
                        'reference' => 'part/'.$state['_parent']->sku
                    );
                    $branch[] = array(
                        'label'     => _('Image'),
                        'icon'      => 'camera-retro',
                        'reference' => ''
                    );

                    break;

                case 'part.new':

                    $branch[] = array(
                        'label'     => _('Inventory'),
                        'icon'      => 'th-large',
                        'reference' => 'inventory'
                    );
                    $branch[] = array(
                        'label'     => _('New part'),
                        'icon'      => 'box',
                        'reference' => ''
                    );

                    break;


                case 'supplier_part.new':


                    if ($state['parent'] == 'category') {
                        $category = $state['_parent'];
                        $branch[] = array(
                            'label'     => _(
                                "Parts's families"
                            ),
                            'icon'      => 'sitemap',
                            'reference' => 'inventory/categories'
                        );


                        if (isset($state['metadata'])) {
                            $parent_category_keys = $state['metadata'];
                        } else {

                            $parent_category_keys = preg_split(
                                '/\>/', $category->get('Category Position')
                            );
                        }


                        foreach ($parent_category_keys as $category_key) {
                            if (!is_numeric($category_key)) {
                                continue;
                            }
                            if ($category_key == $state['parent_key']) {
                                $branch[] = array(
                                    'label'     => '<span class="Category_Label">'.$category->get('Label').'</span>',
                                    'icon'      => '',
                                    'reference' => ''
                                );
                                break;
                            } else {

                                $parent_category = new Category($category_key);
                                if ($parent_category->id) {

                                    $branch[] = array(
                                        'label'     => $parent_category->get(
                                            'Label'
                                        ),
                                        'icon'      => '',
                                        'reference' => 'inventory/category/'.$parent_category->id
                                    );

                                }
                            }
                        }

                    } else {
                        $branch[] = array(
                            'label'     => _('Inventory'),
                            'icon'      => 'th-large',
                            'reference' => 'inventory'
                        );

                    }

                    $branch[] = array(
                        'label'     => '<span class="id Part_Reference">'.$state['_object']->get('Reference').'</span>',
                        'icon'      => 'box',
                        'reference' => 'part/'.$state['_object']->id
                    );
                    $branch[] = array(
                        'label'     => _('New supplier part'),
                        'icon'      => '',
                        'reference' => ''
                    );

                    break;

                case 'product':
                    $branch[] = array(
                        'label'     => _('Inventory'),
                        'icon'      => 'th-large',
                        'reference' => 'inventory'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Part_Reference">'.$state['_parent']->get('Reference').'</span>',
                        'icon'      => 'box',
                        'reference' => 'part/'.$state['parent_key']
                    );


                    $branch[] = array(
                        'label'     => '<span class="id Product_Code">'.$state['_object']->get('Code').'</span>',
                        'icon'      => 'cube',
                        'reference' => 'products/'.$state['_object']->get('Product Store Key').'/'.$state['_object']->id
                    );

                    break;
                case 'feedback':
                    $branch[] = array(
                        'label'     => _('Issues'),
                        'icon'      => 'poop',
                        'reference' => ''
                    );
                    break;
                case 'barcodes':
                    $branch[] = array(
                        'label'     => _('Barcodes'),
                        'icon'      => 'barcode',
                        'reference' => ''
                    );
                    break;
                case 'barcode':
                    $branch[] = array(
                        'label'     => _('Barcodes'),
                        'icon'      => '',
                        'reference' => 'inventory/barcodes'
                    );
                    $branch[] = array(
                        'label'     => $state['_object']->get(
                            'Number'
                        ),
                        'icon'      => 'barcode',
                        'reference' => ''
                    );

                    break;
                case 'deleted_barcode':
                    $branch[] = array(
                        'label'     => _('Barcodes'),
                        'icon'      => '',
                        'reference' => 'inventory/barcodes'
                    );
                    $branch[] = array(
                        'label'     => $state['_object']->get(
                                'Deleted Number'
                            ).' <i class="fa fa-trash" aria-hidden="true"></i>',
                        'icon'      => 'barcode',
                        'reference' => ''
                    );

                    break;
                case 'categories':
                    $branch[] = array(
                        'label'     => _("Parts's families"),
                        'icon'      => 'sitemap',
                        'reference' => ''
                    );
                    break;
                case 'category':
                    $category = $state['_object'];
                    $branch[] = array(
                        'label'     => _("Parts's families"),
                        'icon'      => 'sitemap',
                        'reference' => 'inventory/categories'
                    );


                    if (isset($state['metadata'])) {
                        $parent_category_keys = $state['metadata'];
                    } else {

                        $parent_category_keys = preg_split(
                            '/\>/', $category->get('Category Position')
                        );
                    }


                    foreach ($parent_category_keys as $category_key) {
                        if (!is_numeric($category_key)) {
                            continue;
                        }
                        if ($category_key == $state['key']) {
                            $branch[] = array(
                                'label'     => '<span class="Category_Label">'.$category->get('Label').'</span>',
                                'icon'      => '',
                                'reference' => ''
                            );
                            break;
                        } else {

                            $parent_category = new Category($category_key);
                            if ($parent_category->id) {

                                $branch[] = array(
                                    'label'     => $parent_category->get(
                                        'Label'
                                    ),
                                    'icon'      => '',
                                    'reference' => 'inventory/category/'.$parent_category->id
                                );

                            }
                        }
                    }

                    break;
                case 'main_category.new':

                    $branch[] = array(
                        'label'     => _("Parts's families"),
                        'icon'      => 'sitemap',
                        'reference' => 'inventory/categories'
                    );
                    $branch[] = array(
                        'label'     => _('New main category'),
                        'icon'      => '',
                        'reference' => ''
                    );


                    break;
                case 'upload':

                    if ($state['parent'] == 'category') {
                        $category = $state['_parent'];
                        $branch[] = array(
                            'label'     => _(
                                "Parts's families"
                            ),
                            'icon'      => 'sitemap',
                            'reference' => 'inventory/categories'
                        );


                        if (isset($state['metadata'])) {
                            $parent_category_keys = $state['metadata'];
                        } else {

                            $parent_category_keys = preg_split(
                                '/\>/', $category->get('Category Position')
                            );
                        }


                        foreach ($parent_category_keys as $category_key) {
                            if (!is_numeric($category_key)) {
                                continue;
                            }
                            if ($category_key == $state['key']) {
                                $branch[] = array(
                                    'label'     => '<span class="Category_Label">'.$category->get('Label').'</span>',
                                    'icon'      => '',
                                    'reference' => ''
                                );
                                break;
                            } else {

                                $parent_category = new Category($category_key);
                                if ($parent_category->id) {

                                    $branch[] = array(
                                        'label'     => $parent_category->get(
                                            'Label'
                                        ),
                                        'icon'      => '',
                                        'reference' => 'inventory/category/'.$parent_category->id
                                    );

                                }
                            }
                        }

                    }

                    $branch[] = array(
                        'label'     => _('Upload').' '.sprintf(
                                '%04d', $state['_object']->get('Key')
                            ),
                        'icon'      => 'upload',
                        'reference' => ''
                    );

                    break;
                case 'stock_history':
                    $branch[] = array(
                        'label'     => _('Stock History'),
                        'icon'      => 'area-chart',
                        'reference' => ''
                    );
                    break;
                case 'stock_history.day':
                    $branch[] = array(
                        'label'     => _('Stock History'),
                        'icon'      => 'area-chart',
                        'reference' => 'inventory/stock_history'
                    );
                    $branch[] = array(
                        'label'     => strftime(
                            "%a %e %b %Y", strtotime($state['key'].' +0:00')
                        ),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;
                case 'part.attachment.new':
                    $branch[] = array(
                        'label'     => _('Inventory'),
                        'icon'      => 'th-large',
                        'reference' => 'inventory'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Part_Reference">'.$state['_parent']->get('Reference').'</span>',
                        'icon'      => 'box',
                        'reference' => 'part/'.$state['_parent']->sku
                    );
                    $branch[] = array(
                        'label'     => _('Upload attachment'),
                        'icon'      => 'paperclip',
                        'reference' => ''
                    );
                    break;
                case 'part.attachment':
                    $branch[] = array(
                        'label'     => _('Inventory'),
                        'icon'      => 'th-large',
                        'reference' => 'inventory'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Part_Reference">'.$state['_parent']->get('Reference').'</span>',
                        'icon'      => 'box',
                        'reference' => 'part/'.$state['_parent']->sku
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Attachment_Caption">'.$state['_object']->get('Caption').'</span>',
                        'icon'      => 'paperclip',
                        'reference' => ''
                    );

                    break;
            }

            break;
        case 'warehouses_server':

            $branch[] = array(
                'label'     => '('._('All warehouses').')',
                'icon'      => 'warehouse-alt',
                'reference' => ''
            );


            break;

        case 'warehouses':


            if ($user->get_number_warehouses() > 1 or $user->can_create('warehouses')) {


                $branch[] = array(
                    'label'     => '('._('All warehouses').')',
                    'icon'      => '',
                    'reference' => 'warehouses'
                );
            }

            switch ($state['section']) {


                case 'dashboard':
                    $branch[] = array(
                        'label'     => _('Warehouse dashboard'),
                        'icon'      => 'tachometer',
                        'reference' => ''
                    );

                    break;


                case 'warehouse':


                    $branch[] = array(
                        'label'     => '<span class="id Warehouse_Code">'._('Warehouse').' '.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => ''
                    );
                    break;
                case 'feedback':

                    $branch[] = array(
                        'label'     => '<span class="id Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => 'warehouse/'.$state['parent_key']
                    );
                    $branch[] = array(
                        'label'     => _('Issues'),
                        'icon'      => 'poop',
                        'reference' => ''
                    );
                    break;
                case 'warehouse_areas':


                    $branch[] = array(
                        'label'     => '<span class="id Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => 'warehouse/'.$state['parent_key']
                    );
                    $branch[] = array(
                        'label'     => _('Warehouse areas'),
                        'icon'      => 'inventory',
                        'reference' => ''
                    );
                    break;
                case 'warehouse_area':


                    $branch[] = array(
                        'label'     => '<span class="id Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => 'warehouse/'.$state['warehouse']->id
                    );
                    $branch[] = array(
                        'label'     => _('Warehouse areas'),
                        'icon'      => 'inventory',
                        'reference' => 'warehouse/'.$state['warehouse']->id.'/areas'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Warehouse_Area_Code">'.$state['_object']->get('Code').'</span>',
                        'icon'      => 'inventory',
                        'reference' => ''
                    );
                    break;
                case 'locations':


                    $branch[] = array(
                        'label'     => '<span class="id Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => 'warehouse/'.$state['parent_key']
                    );
                    $branch[] = array(
                        'label'     => _('Locations'),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;
                case 'delivery_notes':

                    $branch[] = array(
                        'label'     => '<span class="id Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => 'warehouse/'.$state['parent_key']
                    );
                    $branch[] = array(
                        'label'     => _('Pending delivery notes'),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;

                case 'location':


                    switch ($state['_parent']->get_object_name()) {
                        case 'Warehouse Area':
                            $branch[] = array(
                                'label'     => '<span class="id Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                                'icon'      => 'warehouse-alt',
                                'reference' => 'warehouse/'.$state['warehouse']->id
                            );
                            $branch[] = array(
                                'label'     => _('Warehouse areas'),
                                'icon'      => 'inventory',
                                'reference' => 'warehouse/'.$state['warehouse']->id.'/areas'
                            );
                            $branch[] = array(
                                'label'     => '<span class="id Warehouse_Area_Code">'.$state['_parent']->get('Code').'</span>',
                                'icon'      => 'inventory',
                                'reference' => 'warehouse/'.$state['warehouse']->id.'/areas/'.$state['_parent']->id
                            );
                            break;
                        case 'Warehouse':

                            $branch[] = array(
                                'label'     => '<span class=" Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                                'icon'      => 'warehouse-alt',
                                'reference' => 'warehouse/'.$state['parent_key']
                            );
                            $branch[] = array(
                                'label'     => _('Locations'),
                                'icon'      => '',
                                'reference' => 'warehouse/'.$state['parent_key'].'/locations'
                            );

                            break;
                    }


                    $branch[] = array(
                        'label'     => '<span class="id Location_Code">'.$state['_object']->get('Code').'</span>',
                        'icon'      => 'inventory',
                        'reference' => ''
                    );

                    break;

                case 'location.new':


                    switch ($state['_parent']->get_object_name()) {
                        case 'Warehouse Area':
                            $branch[] = array(
                                'label'     => '<span class="id Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                                'icon'      => 'warehouse-alt',
                                'reference' => 'warehouse/'.$state['warehouse']->id
                            );
                            $branch[] = array(
                                'label'     => _('Warehouse areas'),
                                'icon'      => 'inventory',
                                'reference' => 'warehouse/'.$state['warehouse']->id.'/areas'
                            );
                            $branch[] = array(
                                'label'     => '<span class="id Warehouse_Area_Code">'.$state['_parent']->get('Code').'</span>',
                                'icon'      => 'inventory',
                                'reference' => 'warehouse/'.$state['warehouse']->id.'/areas/'.$state['_parent']->id
                            );
                            break;
                        case 'Warehouse':

                            break;
                    }


                    $branch[] = array(
                        'label'     => _('New location'),
                        'icon'      => '',
                        'reference' => ''
                    );


                    break;

                case 'categories':
                    $branch[] = array(
                        'label'     => _("Locations's categories"),
                        'icon'      => 'sitemap',
                        'reference' => ''
                    );
                    break;
                case 'category':
                    $category = $state['_object'];
                    $branch[] = array(
                        'label'     => _("Locations's categories"),
                        'icon'      => 'sitemap',
                        'reference' => 'inventory/categories'
                    );


                    if (isset($state['metadata'])) {
                        $parent_category_keys = $state['metadata'];
                    } else {

                        $parent_category_keys = preg_split(
                            '/\>/', $category->get('Category Position')
                        );
                    }


                    foreach ($parent_category_keys as $category_key) {
                        if (!is_numeric($category_key)) {
                            continue;
                        }
                        if ($category_key == $state['key']) {
                            $branch[] = array(
                                'label'     => '<span class="Category_Label">'.$category->get('Label').'</span>',
                                'icon'      => '',
                                'reference' => ''
                            );
                            break;
                        } else {

                            $parent_category = new Category($category_key);
                            if ($parent_category->id) {

                                $branch[] = array(
                                    'label'     => $parent_category->get('Label'),
                                    'icon'      => '',
                                    'reference' => 'inventory/category/'.$parent_category->id
                                );

                            }
                        }
                    }

                    break;
                case 'warehouse.new':

                    $branch[] = array(
                        'label'     => _('New warehouse'),
                        'icon'      => 'warehouse-alt',
                        'reference' => ''
                    );

                    break;
                case 'warehouse_area.new':

                    $branch[] = array(
                        'label'     => _('New warehouse area'),
                        'icon'      => 'inventory',
                        'reference' => ''
                    );

                    break;
                case 'part':

                    $branch[] = array(
                        'label'     => _('Inventory').' <span class="id">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'th-large',
                        'reference' => 'inventory/'.$state['warehouse']->id
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Part_Reference">'.$state['_object']->get('Reference').'</span> (<span class="id">'.$state['_object']->get(
                                'SKU'
                            ).'</span>)',
                        'icon'      => 'box',
                        'reference' => ''
                    );

                    break;

                case 'leakages':


                    $branch[] = array(
                        'label'     => _('Warehouse dashboard'),
                        'icon'      => 'tachometer',
                        'reference' => 'warehouse/'.$state['warehouse']->id.'/dashboard'
                    );
                    $branch[] = array(
                        'label'     => _('Stock leakages'),
                        'icon'      => 'inbox',
                        'reference' => ''
                    );
                    break;
                case 'timeseries_record':


                    $branch[] = array(
                        'label'     => _('Warehouse dashboard'),
                        'icon'      => 'tachometer',
                        'reference' => 'warehouse/'.$state['warehouse']->id.'/dashboard'
                    );
                    $branch[] = array(
                        'label'     => _('Stock leakages'),
                        'icon'      => 'inbox',
                        'reference' => 'warehouse/'.$state['warehouse']->id.'/leakages'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id">'.$state['_object']->get('Code').'</span>',
                        'icon'      => 'table',
                        'reference' => ''
                    );
                    break;
                case 'returns':


                    $branch[] = array(
                        'label'     => '<span class=" Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => 'warehouse/'.$state['parent_key']
                    );

                    $branch[] = array(
                        'label'     => _('Returns'),
                        'icon'      => 'backspace',
                        'reference' => ''
                    );
                    break;
                case 'return':


                    $branch[] = array(
                        'label'     => '<span class=" Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => 'warehouse/'.$state['parent_key']
                    );

                    $branch[] = array(
                        'label'     => _('Returns'),
                        'icon'      => 'backspace',
                        'reference' => 'warehouse/'.$state['parent_key'].'/returns'
                    );

                    $branch[] = array(
                        'label'     => '<span class="id">'.$state['_object']->get('Public ID').'</span>',
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;
                case 'shippers':


                    $branch[] = array(
                        'label'     => '<span class=" Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => 'warehouse/'.$state['parent_key']
                    );

                    $branch[] = array(
                        'label'     => _('Shipping companies'),
                        'icon'      => 'truck-loading',
                        'reference' => ''
                    );
                    break;
                case 'shipper.new':


                    $branch[] = array(
                        'label'     => '<span class=" Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => 'warehouse/'.$state['parent_key']
                    );

                    $branch[] = array(
                        'label'     => _('Shipping companies'),
                        'icon'      => 'truck-loading',
                        'reference' => 'warehouse/'.$state['parent_key'].'/shippers'
                    );

                    $branch[] = array(
                        'label'     => _('Add shipping company'),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;
                case 'shipper.new':


                    $branch[] = array(
                        'label'     => '<span class=" Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => 'warehouse/'.$state['parent_key']
                    );

                    $branch[] = array(
                        'label'     => _('Shipping companies'),
                        'icon'      => 'truck-loading',
                        'reference' => 'warehouse/'.$state['parent_key'].'/shippers'
                    );

                    $branch[] = array(
                        'label'     => '<span class="Shipper_Code">'.$state['_object']->get('Code').'</span>',
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;

                case 'upload':

                    $branch[] = array(
                        'label'     => '<span class=" Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => 'warehouse/'.$state['parent_key']
                    );


                    if ($state['_object']->get('Upload Type') == 'EditObjects') {


                        switch ($state['_object']->get('Upload Object')) {
                            case 'warehouse_area':

                                $branch[] = array(
                                    'label'     => _('Warehouse areas'),
                                    'icon'      => '',
                                    'reference' => 'warehouse/'.$state['parent_key'].'/areas'
                                );


                                $branch[] = array(
                                    'label'     => _('Editing warehouse areas').' ('.$state['_object']->get('Filename').')',
                                    'icon'      => 'upload',
                                    'reference' => ''
                                );

                                break;
                            case 'location':


                                switch ($state['parent']) {
                                    case 'warehouse':


                                        $branch[] = array(
                                            'label'     => _('Locations'),
                                            'icon'      => '',
                                            'reference' => 'warehouse/'.$state['parent_key'].'/locations'
                                        );

                                        break;
                                    case 'warehouse_area':

                                        $branch[] = array(
                                            'label'     => _('Warehouse areas'),
                                            'icon'      => '',
                                            'reference' => 'warehouse/'.$state['parent_key'].'/areas'
                                        );

                                        $branch[] = array(
                                            'label'     => '<span class="id Warehouse_Area_Code">'.$state['_parent']->get('Code').'</span>',
                                            'icon'      => 'inventory',
                                            'reference' => 'warehouse/'.$state['warehouse']->id.'/areas/'.$state['_parent']->id
                                        );

                                        break;
                                }


                                $branch[] = array(
                                    'label'     => _('Editing locations').' ('.$state['_object']->get('Filename').')',
                                    'icon'      => 'upload',
                                    'reference' => ''
                                );


                                break;
                        }


                    } else {

                        switch ($state['_object']->get('Upload Object')) {
                            case 'warehouse_area':

                                $branch[] = array(
                                    'label'     => _('Warehouse areas'),
                                    'icon'      => '',
                                    'reference' => 'warehouse/'.$state['parent_key'].'/areas'
                                );


                                $branch[] = array(
                                    'label'     => _('Adding warehouse areas').' ('.$state['_object']->get('Filename').')',
                                    'icon'      => 'upload',
                                    'reference' => ''
                                );

                                break;
                            case 'location':


                                switch ($state['parent']) {
                                    case 'warehouse':


                                        $branch[] = array(
                                            'label'     => _('Locations'),
                                            'icon'      => '',
                                            'reference' => 'warehouse/'.$state['parent_key'].'/locations'
                                        );

                                        break;
                                    case 'warehouse_area':

                                        $branch[] = array(
                                            'label'     => _('Warehouse areas'),
                                            'icon'      => '',
                                            'reference' => 'warehouse/'.$state['parent_key'].'/areas'
                                        );

                                        $branch[] = array(
                                            'label'     => '<span class="id Warehouse_Area_Code">'.$state['_parent']->get('Code').'</span>',
                                            'icon'      => 'inventory',
                                            'reference' => 'warehouse/'.$state['warehouse']->id.'/areas/'.$state['_parent']->id
                                        );

                                        break;
                                }


                                $branch[] = array(
                                    'label'     => _('Adding locations').' ('.$state['_object']->get('Filename').')',
                                    'icon'      => 'upload',
                                    'reference' => ''
                                );


                                break;
                        }


                    }


                    /*

                    switch ($data['parent']) {


                    }

                    switch ($state['_object']->get('Upload Object')) {
                        case 'location':

                            $branch[] = array(
                                'label'     => _('Locations'),
                                'icon'      => '',
                                'reference' => 'warehouse/'.$state['parent_key'].'/locations'
                            );

                            $branch[] = array(
                                'label'     => _('Uploading new locations').' ('.$state['_object']->get('Filename').')',
                                'icon'      => 'upload',
                                'reference' => ''
                            );
                            break;
                        case 'warehouse_area':

                            $branch[] = array(
                                'label'     => _('Warehouse areas'),
                                'icon'      => '',
                                'reference' => 'warehouse/'.$state['parent_key'].'/areas'
                            );


                            $branch[] = array(
                                'label'     => '<span title="'.$state['_object']->get('Filename').'">'._('Uploading new warehouse areas').'</span>',
                                'icon'      => 'upload',
                                'reference' => ''
                            );


                            break;

                    }

*/
                    break;

            }


            break;
        case 'websites_server':


            $branch[] = array(
                'label'     => _('Websites'),
                'icon'      => '',
                'reference' => ''
            );


            break;
        case 'websites':
            $state['current_store'] = $state['store']->id;

            if ($user->get_number_websites() > 1) {

                $branch[] = array(
                    'label'     => '('._('All websites').')',
                    'icon'      => '',
                    'reference' => 'websites/all'
                );

            }
            switch ($state['section']) {
                case 'analytics':
                    $branch[] = array(
                        'label'     => '<span class="id Website_Code">'.$state['website']->get('Code'),
                        'icon'      => 'analytics',
                        'reference' => '',
                    );
                    break;
                case 'workshop':
                    $branch[] = array(
                        'label'     => '<span class="id Website_Code">'.$state['website']->get('Code').'</span> '._('workshop'),
                        'icon'      => 'drafting-compass',
                        'reference' => ''
                    );
                    break;
                case 'web_users':
                    $branch[] = array(
                        'label'     => '<span class="id Website_Code">'.$state['website']->get('Code').'</span> '._('eegistered users'),
                        'icon'      => 'users-class',
                        'reference' => ''
                    );
                    break;
                case 'settings':
                    $branch[] = array(
                        'label'     => '<span class="id Website_Code">'.$state['website']->get('Code').'</span> '._('settings'),
                        'icon'      => 'sliders-h',
                        'reference' => ''
                    );
                    break;
                case 'website':


                    $branch[] = array(
                        'label'     => '<span class="id Website_Code">'.$state['website']->get('Code').'</span>',
                        'icon'      => '',
                        'reference' => 'website/'.$state['website']->id
                    );
                    break;
                case 'webpage_type':
                    $branch[] = array(
                        'label'     => '<span class="id Website_Code">'.$state['website']->get('Code').'</span>',
                        'icon'      => 'globe',
                        'reference' => 'webpages/'.$state['website']->id
                    );

                    $branch[] = array(
                        'label'     => _('Web page type').': <span class="id">'.$state['_object']->get('Label').'</span>',
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;

                case 'webpages':


                    $branch[] = array(
                        'label'     => '<span class="id Website_Code">'.$state['website']->get('Code').'</span> <i class="fa fa-files" aria-hidden="true"></i>',
                        'icon'      => 'browser',
                        'reference' => 'website/'.$state['website']->id
                    );


                    break;

                case 'page':

                    $branch[] = array(
                        'label'     => $state['website']->get(
                            'Code'
                        ),
                        'icon'      => 'globe',
                        'reference' => 'website/'.$state['website']->id
                    );
                    $branch[] = array(
                        'label'     => $state['_object']->get('Code'),
                        'icon'      => 'file',
                        'reference' => ''
                    );


                    break;
                case 'page_version':

                    $branch[] = array(
                        'label'     => $state['website']->get(
                            'Code'
                        ),
                        'icon'      => 'globe',
                        'reference' => 'website/'.$state['website']->id
                    );
                    $branch[] = array(
                        'label'     => $state['_parent']->get('Code'),
                        'icon'      => 'file',
                        'reference' => 'website/'.$state['website']->id.'/page/'.$state['_parent']->id
                    );


                    switch ($state['_object']->get('Webpage Version Device')) {
                        case 'Desktop':
                            $device_icon = 'desktop';
                            break;
                        case 'Mobile':
                            $device_icon = 'mobile';
                            break;
                        case 'Tablet':
                            $device_icon = 'tablet';
                            break;
                        default:
                            $device_icon = '';
                    }

                    $branch[] = array(
                        'label'     => ' <i class="fa fa-code-fork" aria-hidden="true"></i>'.$state['_object']->get('Code'),
                        'icon'      => $device_icon,
                        'reference' => ''
                    );

                    break;
                case 'website.user':

                    if ($state['parent'] == 'website') {
                        $website = new Website($state['parent_key']);
                    } elseif ($state['parent'] == 'page') {
                        $page = new Page($state['parent_key']);

                        $website = new Website($page->get('Webpage Website Key'));

                    }

                    $branch[] = array(
                        'label'     => _('Website').' '.$website->get('Code'),
                        'icon'      => 'globe',
                        'reference' => 'website/'.$website->id
                    );

                    if ($state['parent'] == 'page') {

                        $branch[] = array(
                            'label'     => _('Page').' '.$page->get('Code'),
                            'icon'      => 'file',
                            'reference' => 'website/'.$website->id.'/page/'.$page->id
                        );

                    }

                    $branch[] = array(
                        'label'     => _(
                                'User'
                            ).' '.$state['_object']->data['User Handle'],
                        'icon'      => 'user',
                        'reference' => 'website/'.$website->id.'/user/'.$state['_object']->id
                    );

                    break;
            }

            break;

        case 'profile':
            $branch[] = array(
                'label'     => _('My profile').' <span class="id">'.$user->get('User Alias').'</span>',
                'icon'      => '',
                'reference' => 'profile'
            );


            break;
        case 'accounting_server':


            if ($state['section'] == 'dashboard') {

                $branch[] = array(
                    'label'     => _('Accounting'),
                    'icon'      => 'abacus',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'payment_account') {


                if ($state['parent'] == 'account') {
                    $branch[] = array(
                        'label'     => _('Payment accounts').' ('._('All stores').')',
                        'icon'      => '',
                        'reference' => 'payment_accounts/all'
                    );

                }

                $branch[] = array(
                    'label'     => '<span title="'._('Payment account').'" id="id">'.$state['_object']->get('Payment Account Code').'</span>',
                    'icon'      => 'money-check-alt',
                    'reference' => 'account/payment_service_provider/'.$state['_object']->id
                );


            } elseif ($state['section'] == 'payment_accounts') {
                if ($state['parent'] == 'account') {

                    $branch[] = array(
                        'label'     => _('Payment accounts').' ('._('All stores').')',
                        'icon'      => 'money-check-alt',
                        'reference' => 'payment_accounts/all'
                    );

                } elseif ($state['parent'] == 'payment_service_provider') {

                    include_once 'class.Payment_Service_Provider.php';

                    $psp = new Payment_Service_Provider($state['parent_key']);

                    $branch[] = array(
                        'label'     => _(
                                'Payment service provider'
                            ).'  <span id="id">'.$psp->get(
                                'Payment Service Provider Code'
                            ).'</span>',
                        'icon'      => '',
                        'reference' => 'payment_service_provider/'.$psp->id
                    );
                    $branch[] = array(
                        'label'     => _('Payment accounts'),
                        'icon'      => '',
                        'reference' => ''
                    );

                }


            } elseif ($state['section'] == 'payment_service_providers') {


                $branch[] = array(
                    'label'     => _('Payment service providers'),
                    'icon'      => 'cash-register',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'payment_service_provider') {

                $branch[] = array(
                    'label'     => _('Payment service providers'),
                    'icon'      => 'cash-register',
                    'reference' => 'payment_service_providers/all'
                );


                $branch[] = array(
                    'label'     => '<span id="id">'.$state['_object']->get('Code').'</span>',
                    'icon'      => '',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'payments') {

                if ($state['parent'] == 'account') {
                    $branch[] = array(
                        'label'     => _('Payments').' ('._(
                                'All stores'
                            ).')',
                        'icon'      => '',
                        'reference' => 'payments/all'
                    );

                } elseif ($state['parent'] == 'store') {
                    $store = new Store($state['parent_key']);


                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'payments/all'
                        );
                    }

                    $branch[] = array(
                        'label'     => _('Payments').'  <span id="id">('.$store->get('Code').')</span>',
                        'icon'      => '',
                        'reference' => 'payments/'.$store->id
                    );


                } elseif ($state['parent'] == 'payment_service_provider') {
                    include_once 'class.Payment_Service_Provider.php';
                    $branch[] = array(
                        'label'     => _(
                                'Payment service provider'
                            ).'  <span id="id">'.$psp->get(
                                'Payment Service Provider Code'
                            ).'</span>',
                        'icon'      => '',
                        'reference' => 'account/payment_service_provider/'.$psp->id
                    );


                } elseif ($state['parent'] == 'payment_account') {
                    include_once 'class.Payment_Account.php';
                    $payment_account = new Payment_Account($state['_object']->get('Payment Account Key'));


                    $branch[] = array(
                        'label'     => _('Payment account').'  <span id="id">'.$payment_account->get(
                                'Payment Account Code'
                            ).'</span>',
                        'icon'      => '',
                        'reference' => 'payment_service_provider/'.$psp->id.'/payment_account/'.$payment_account->id
                    );


                }


            } elseif ($state['section'] == 'credits') {


                $branch[] = array(
                    'label'     => _('Credits').' ('._('All stores').')',
                    'icon'      => 'piggy-bank',
                    'reference' => 'credits/all'
                );


            } elseif ($state['section'] == 'invoices') {


                $branch[] = array(
                    'label'     => _('Invoices').' ('._('All stores').')',
                    'icon'      => 'file-alt',
                    'reference' => 'invoices/all'
                );


            } elseif ($state['section'] == 'deleted_invoices_server') {


                $branch[] = array(
                    'label'     => _('Deleted invoices').' ('._('All stores').')',
                    'icon'      => 'ban',
                    'reference' => 'invoices/deleted/all'
                );


            } elseif ($state['section'] == 'invoice') {


                $branch[] = array(
                    'label'     => _('Invoices').' ('._('All stores').')',
                    'icon'      => '',
                    'reference' => 'invoices/all'
                );


                $branch[] = array(
                    'label'     => $state['_object']->get('Invoice Public ID'),
                    'icon'      => 'file-alt',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'payments_by_store') {


                $branch[] = array(
                    'label'     => _('Payments by store'),
                    'icon'      => '',
                    'reference' => 'payments/by_store'
                );


            } elseif ($state['section'] == 'payment') {

                if ($state['parent'] == 'account') {


                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => _('Payments').' ('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'payments/all'
                        );
                    }


                    $branch[] = array(
                        'label'     => '<span id="id">'.$state['_object']->get('Transaction ID').'</span>',
                        'icon'      => 'credit-card',
                        'reference' => ''
                    );

                } elseif ($state['parent'] == 'payment_service_provider') {


                    $branch[] = array(
                        'label'     => _('Payment service providers'),
                        'icon'      => 'cash-register',
                        'reference' => 'payment_service_providers/all'
                    );
                    $branch[] = array(
                        'label'     => '<span id="id">'.$state['_parent']->get('Payment Service Provider Code').'</span>',
                        'icon'      => '',
                        'reference' => 'payment_service_provider/'.$state['_parent']->id
                    );

                    $branch[] = array(
                        'label'     => '<span id="id" title="'._('Payment').'">'.$state['_object']->get('Payment Transaction ID').'</span>',
                        'icon'      => 'credit-card',
                        'reference' => '',
                    );


                } elseif ($state['parent'] == 'payment_account') {

                    $branch[] = array(
                        'label'     => _('Payment accounts').' ('._('All stores').')',
                        'icon'      => '',
                        'reference' => 'payment_accounts/all'
                    );

                    $branch[] = array(
                        'label'     => '<span id="id" title="'._('Payment account').'">'.$state['_parent']->get('Payment Account Code').'</span>',
                        'icon'      => 'fa fa-money-check',
                        'reference' => 'payment_account/'.$state['_parent']->id
                    );

                    $branch[] = array(
                        'label'     => '<span id="id" title="'._('Payment').'">'.$state['_object']->get('Payment Transaction ID').'</span>',
                        'icon'      => 'credit-card',
                        'reference' => '',
                    );

                }


            }


            break;
        case 'accounting':

            if ($state['section'] == 'payment_account') {


                $branch[] = array(
                    'label'     => _('Payment accounts').' ('._('All stores').')',
                    'html_icon' => '',
                    'reference' => 'payment_accounts/all'
                );
                $branch[] = array(
                    'label'     => _('Payment accounts').' ('.$state['_parent']->get('Code').')',
                    'html_icon' => '',
                    'reference' => 'payment_accounts/'.$state['parent_key']
                );


                $branch[] = array(
                    'label'     => '<span id="id">'.$state['_object']->get('Payment Account Code').'</span>',
                    'html_icon' => '<i class="fal fa-money-check-alt"></i>',
                    'reference' => 'account/payment_service_provider/'.$state['_object']->id
                );


            } elseif ($state['section'] == 'payment_accounts') {


                // done in store.payment_accounts.tab.php


            } elseif ($state['section'] == 'payment_service_providers') {


                $branch[] = array(
                    'label'     => _('Payment service providers'),
                    'icon'      => 'bank',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'payment_service_provider') {

                $branch[] = array(
                    'label'     => _('Payment service providers'),
                    'icon'      => 'bank',
                    'reference' => ''
                );
                $psp      = new Payment_Service_Provider(
                    $state['_object']->get('Payment Service Provider Key')
                );

                $branch[] = array(
                    'label'     => _(
                            'Payment service provider'
                        ).'  <span id="id">'.$psp->get('Code').'</span>',
                    'icon'      => '',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'payments') {


                if ($state['tab'] == 'store.payment_accounts') {


                    $branch[] = array(
                        'label'     => '('._('All stores').')',
                        'icon'      => '',
                        'reference' => 'payments_accounts/all'
                    );

                    $branch[] = array(
                        'label'     => _('Payments accounts').'  <span id="id">('.$state['_parent']->get('Code').')</span>',
                        'icon'      => '',
                        'reference' => 'payments_accounts/'.$state['_parent']->id
                    );

                } else {
                    $branch[] = array(
                        'label'     => _('Payments per store'),
                        'html_icon' => '<i class="fal fa-layer-group"></i>',
                        'reference' => 'payments/per_store'
                    );

                    $branch[] = array(
                        'label'     => _('Payments').'  <span id="id">('.$state['_parent']->get('Code').')</span>',
                        'icon'      => '',
                        'reference' => 'payments/'.$state['_parent']->id
                    );
                }


                /*

                if ($state['parent'] == 'account') {
                    $branch[] = array(
                        'label'     => _('Payments').' ('._('All stores').')',
                        'icon'      => '',
                        'reference' => 'payments/all'
                    );

                } elseif ($state['parent'] == 'store') {
                    $store = new Store($state['parent_key']);


                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'payments/all'
                        );
                    }

                    $branch[] = array(
                        'label'     => _('Payments').'  <span id="id">('.$store->get('Code').')</span>',
                        'icon'      => '',
                        'reference' => 'payments/'.$store->id
                    );


                } elseif ($state['parent'] == 'payment_service_provider') {
                    include_once 'class.Payment_Service_Provider.php';
                    $branch[] = array(
                        'label'     => _(
                                'Payment service provider'
                            ).'  <span id="id">'.$psp->get(
                                'Payment Service Provider Code'
                            ).'</span>',
                        'icon'      => '',
                        'reference' => 'account/payment_service_provider/'.$psp->id
                    );


                } elseif ($state['parent'] == 'payment_account') {
                    include_once 'class.Payment_Account.php';
                    $payment_account = new Payment_Account(
                        $state['_object']->get('Payment Account Key')
                    );
                    $branch[]        = array(
                        'label'     => _('Payment account').'  <span id="id">'.$payment_account->get(
                                'Payment Account Code'
                            ).'</span>',
                        'icon'      => '',
                        'reference' => 'payment_service_provider/'.$psp->id.'/payment_account/'.$payment_account->id
                    );


                }

                */


            } elseif ($state['section'] == 'payment') {


                if ($state['parent'] == 'store') {


                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => _('Payments per store'),
                            'html_icon' => '<i class="fal fa-layer-group"></i>',
                            'reference' => 'payments/per_store'
                        );
                    }

                    $branch[] = array(
                        'label'     => _('Payments').'  <span id="id">('.$state['_parent']->get('Code').')</span>',
                        'icon'      => '',
                        'reference' => 'payments/'.$state['_parent']->id
                    );


                    $branch[] = array(
                        'label'     => '<span id="id" title="'._('Payment').'">'.$state['_object']->get('Payment Transaction ID').'</span>',
                        'icon'      => 'credit-card',
                        'reference' => ''
                    );

                } elseif ($state['parent'] == 'store_payment_account') {


                    $tmp = preg_split('/\_/', $state['parent_key']);

                    $branch[] = array(
                        'label'     => _('Payment accounts').' ('._('All stores').')',
                        'html_icon' => '',
                        'reference' => 'payment_accounts/all'
                    );
                    $branch[] = array(
                        'label'     => _('Payment accounts').' ('.$state['store']->get('Code').')',
                        'html_icon' => '',
                        'reference' => 'payment_accounts/'.$tmp[0]
                    );


                    $branch[] = array(
                        'label'     => '<span id="id">'.$state['_object']->get('Payment Account Code').'</span>',
                        'html_icon' => '<i class="fal fa-money-check-alt"></i>',
                        'reference' => 'payment_accounts/'.$tmp[0].'/'.$tmp[1]
                    );

                    $branch[] = array(
                        'label'     => '<span id="id" title="'._('Payment').'">'.$state['_object']->get('Payment Transaction ID').'</span>',
                        'icon'      => 'credit-card',
                        'reference' => ''
                    );


                }


            } elseif ($state['section'] == 'invoice' or $state['section'] == 'refund') {


                if ($user->get_number_stores() > 1) {
                    $branch[] = array(
                        'label'     => _('Invoices per store'),
                        'html_icon' => '<i class="fal fa-layer-group"></i>',
                        'reference' => 'invoices/per_store'
                    );
                }


                $branch[] = array(
                    'label'     => _('Invoices').' '.$state['_parent']->get('Store Code'),
                    'icon'      => '',
                    'reference' => 'invoices/'.$state['_parent']->id
                );


                $branch[] = array(
                    'label'     => $state['_object']->get('Invoice Public ID'),
                    'icon'      => 'file-alt',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'invoices') {

                if ($user->get_number_stores() > 1) {
                    $branch[] = array(
                        'label'     => _('Invoices per store'),
                        'html_icon' => '<i class="fal fa-layer-group"></i>',
                        'reference' => 'invoices/per_store'
                    );
                }
                $branch[] = array(
                    'label'     => _('Invoices').' '.$state['_parent']->get('Store Code'),
                    'icon'      => '',
                    'reference' => 'invoices/'.$state['_parent']->id
                );
            } elseif ($state['section'] == 'credits') {


                $branch[] = array(
                    'label'     => _('Credits').' ('._('All stores').')',
                    'icon'      => 'piggy-bank',
                    'reference' => 'credits/all'
                );


                $branch[] = array(
                    'label'     => _('Credits').' ('.$state['_parent']->get('Store Code').')',
                    'icon'      => '',
                    'reference' => ''
                );
            }


            break;
        case 'account':


            if ($state['section'] == 'orders_index') {
                $branch[] = array(
                    'label'     => _("Order's index"),
                    'icon'      => 'indent',
                    'reference' => ''
                );
                break;
            }

            $branch[] = array(
                'label'     => _('Account').' <span class="id">'.$account->get('Account Code').'</span>',
                'icon'      => '',
                'reference' => 'account'
            );


            if ($state['section'] == 'users') {
                $branch[] = array(
                    'label'     => _('Users'),
                    'icon'      => 'terminal',
                    'reference' => 'users'
                );

            } elseif ($state['section'] == 'staff') {
                $branch[] = array(
                    'label'     => _('Users'),
                    'icon'      => '',
                    'reference' => 'users'
                );
                $branch[] = array(
                    'label'     => _('Employees'),
                    'icon'      => 'terminal',
                    'reference' => 'users/staff'
                );
            } elseif ($state['section'] == 'contractors') {
                $branch[] = array(
                    'label'     => _('Users'),
                    'icon'      => '',
                    'reference' => 'users'
                );
                $branch[] = array(
                    'label'     => _('Contractors'),
                    'icon'      => 'terminal',
                    'reference' => 'users/contractors'
                );
            } elseif ($state['section'] == 'suppliers') {
                $branch[] = array(
                    'label'     => _('Users'),
                    'icon'      => '',
                    'reference' => 'users'
                );
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => 'terminal',
                    'reference' => 'users/suppliers'
                );
            } elseif ($state['section'] == 'agents') {
                $branch[] = array(
                    'label'     => _('Users'),
                    'icon'      => '',
                    'reference' => 'users'
                );
                $branch[] = array(
                    'label'     => _('Agents'),
                    'icon'      => 'terminal',
                    'reference' => 'users/agents'
                );
            } elseif ($state['section'] == 'user') {
                $branch[] = array(
                    'label'     => _('Users'),
                    'icon'      => '',
                    'reference' => 'users'
                );


                switch ($state['_object']->get('User Type')) {
                    case 'Staff':
                        $branch[] = array(
                            'label'     => _('Employees'),
                            'icon'      => '',
                            'reference' => 'users/staff'
                        );
                        break;
                    case 'Contractor':
                        $branch[] = array(
                            'label'     => _('Contractors'),
                            'icon'      => '',
                            'reference' => 'users/contractors'
                        );
                        break;
                    case 'Agent':
                        $branch[] = array(
                            'label'     => _('Agents'),
                            'icon'      => '',
                            'reference' => 'users/agents'
                        );
                        break;
                    case 'Suppliers':
                        $branch[] = array(
                            'label'     => _('Suppliers'),
                            'icon'      => '',
                            'reference' => 'users/suppliers'
                        );
                        break;
                    default:

                        break;
                }


                $branch[] = array(
                    'label'     => '<span id="id">'.$state['_object']->get('User Handle').'</span>',
                    'icon'      => 'terminal',
                    'reference' => 'users/'.$state['_object']->id
                );

            } elseif ($state['section'] == 'deleted.user') {
                $branch[] = array(
                    'label'     => _('Deteted users'),
                    'icon'      => '',
                    'reference' => 'account/deleted_users'
                );


                $branch[] = array(
                    'label'     => '<span id="id">'.$state['_object']->get('User Handle').'</span>  <i class="far fa-trash-alt padding_left_5" aria-hidden="true"></i> ',
                    'icon'      => 'terminal',
                    'reference' => 'users/'.$state['_object']->id
                );

            } elseif ($state['section'] == 'user.api_key') {

                $branch[] = array(
                    'label'     => _('Users'),
                    'icon'      => '',
                    'reference' => 'users'
                );

                switch ($state['_parent']->get('User Type')) {
                    case 'Staff':
                        $branch[] = array(
                            'label'     => _('Employees'),
                            'icon'      => '',
                            'reference' => 'users/staff'
                        );
                        break;
                    case 'Contractor':
                        $branch[] = array(
                            'label'     => _('Contractors'),
                            'icon'      => '',
                            'reference' => 'users/contractors'
                        );
                        break;
                    case 'Agent':
                        $branch[] = array(
                            'label'     => _('Agents'),
                            'icon'      => '',
                            'reference' => 'users/agents'
                        );
                        break;
                    case 'Suppliers':
                        $branch[] = array(
                            'label'     => _('Suppliers'),
                            'icon'      => '',
                            'reference' => 'users/suppliers'
                        );
                        break;
                    default:

                        break;
                }
                $branch[] = array(
                    'label'     => '<span >'.$state['_parent']->get('User Handle').'</span>',
                    'icon'      => 'terminal',
                    'reference' => 'users/'.$state['_parent']->id
                );

                $branch[] = array(
                    'label'     => _('API key').': <span class="id">'.$state['_object']->get('Scope').'</span> ('.$state['_object']->get('Code').')',
                    'icon'      => '',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'user.api_key.new') {

                $branch[] = array(
                    'label'     => _('Users'),
                    'icon'      => '',
                    'reference' => 'users'
                );

                switch ($state['_parent']->get('User Type')) {
                    case 'Staff':
                        $branch[] = array(
                            'label'     => _('Employees'),
                            'icon'      => '',
                            'reference' => 'users/staff'
                        );
                        break;
                    case 'Contractor':
                        $branch[] = array(
                            'label'     => _('Contractors'),
                            'icon'      => '',
                            'reference' => 'users/contractors'
                        );
                        break;
                    case 'Agent':
                        $branch[] = array(
                            'label'     => _('Agents'),
                            'icon'      => '',
                            'reference' => 'users/agents'
                        );
                        break;
                    case 'Suppliers':
                        $branch[] = array(
                            'label'     => _('Suppliers'),
                            'icon'      => '',
                            'reference' => 'users/suppliers'
                        );
                        break;
                    default:

                        break;
                }
                $branch[] = array(
                    'label'     => '<span >'.$state['_parent']->get('User Handle').'</span>',
                    'icon'      => 'terminal',
                    'reference' => 'users/'.$state['_parent']->id
                );

                $branch[] = array(
                    'label'     => _('New API key'),
                    'icon'      => '',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'deleted_api_key') {

                $branch[] = array(
                    'label'     => _('Users'),
                    'icon'      => '',
                    'reference' => 'users'
                );

                switch ($state['_parent']->get('User Type')) {
                    case 'Staff':
                        $branch[] = array(
                            'label'     => _('Employees'),
                            'icon'      => '',
                            'reference' => 'users/staff'
                        );
                        break;
                    case 'Contractor':
                        $branch[] = array(
                            'label'     => _('Contractors'),
                            'icon'      => '',
                            'reference' => 'users/contractors'
                        );
                        break;
                    case 'Agent':
                        $branch[] = array(
                            'label'     => _('Agents'),
                            'icon'      => '',
                            'reference' => 'users/agents'
                        );
                        break;
                    case 'Suppliers':
                        $branch[] = array(
                            'label'     => _('Suppliers'),
                            'icon'      => '',
                            'reference' => 'users/suppliers'
                        );
                        break;
                    default:

                        break;
                }
                $branch[] = array(
                    'label'     => '<span >'.$state['_parent']->get('User Handle').'</span>',
                    'icon'      => 'terminal',
                    'reference' => 'users/'.$state['_parent']->id
                );

                $branch[] = array(
                    'label'     => _('Deleted API key').': <span class="id">'.$state['_object']->get('Deleted Scope').'</span> ('.$state['_object']->get('Deleted Code').')',
                    'icon'      => '',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'settings') {
                $branch[] = array(
                    'label'     => _('Settings'),
                    'icon'      => 'cog',
                    'reference' => 'account/settings'
                );

            } elseif ($state['section'] == 'payment_service_provider') {
                $branch[] = array(
                    'label'     => _('Payment service provider').'  <span id="id">'.$state['_object']->get(
                            'Payment Service Provider Code'
                        ).'</span>',
                    'icon'      => '',
                    'reference' => 'account/payment_service_provider/'.$state['_object']->id
                );

            } elseif ($state['section'] == 'data_sets') {
                $branch[] = array(
                    'label'     => _('Data sets'),
                    'icon'      => 'align-left',
                    'reference' => 'account/data_sets'
                );

            } elseif ($state['section'] == 'timeseries') {
                $branch[] = array(
                    'label'     => _('Data sets'),
                    'icon'      => 'align-left',
                    'reference' => 'account/data_sets'
                );
                $branch[] = array(
                    'label'     => _('Time series'),
                    'icon'      => 'line-chart',
                    'reference' => 'account/data_sets/timeseries'
                );

            } elseif ($state['section'] == 'timeserie') {
                $branch[] = array(
                    'label'     => _('Data sets'),
                    'icon'      => 'align-left',
                    'reference' => 'account/data_sets'
                );
                $branch[] = array(
                    'label'     => _('Time series'),
                    'icon'      => 'line-chart',
                    'reference' => 'account/data_sets/timeseries'
                );
                $branch[] = array(
                    'label'     => $state['_object']->get('Name'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'images') {
                $branch[] = array(
                    'label'     => _('Data sets'),
                    'icon'      => 'align-left',
                    'reference' => 'account/data_sets'
                );
                $branch[] = array(
                    'label'     => _('Images'),
                    'icon'      => 'image',
                    'reference' => 'account/data_sets/images'
                );

            } elseif ($state['section'] == 'attachments') {
                $branch[] = array(
                    'label'     => _('Data sets'),
                    'icon'      => 'align-left',
                    'reference' => 'account/data_sets'
                );
                $branch[] = array(
                    'label'     => _('Attachments'),
                    'icon'      => 'paperclip',
                    'reference' => 'account/data_sets/attachments'
                );

            } elseif ($state['section'] == 'uploads') {
                $branch[] = array(
                    'label'     => _('Data sets'),
                    'icon'      => 'align-left',
                    'reference' => 'account/data_sets'
                );
                $branch[] = array(
                    'label'     => _('Records uploads'),
                    'icon'      => 'upload',
                    'reference' => 'account/data_sets/uploads'
                );

            } elseif ($state['section'] == 'materials') {
                $branch[] = array(
                    'label'     => _('Data sets'),
                    'icon'      => 'align-left',
                    'reference' => 'account/data_sets'
                );
                $branch[] = array(
                    'label'     => _('Materials'),
                    'icon'      => 'puzzle-piece',
                    'reference' => 'account/data_sets/materials'
                );

            } elseif ($state['section'] == 'material') {
                $branch[] = array(
                    'label'     => _('Data sets'),
                    'icon'      => 'align-left',
                    'reference' => 'account/data_sets'
                );
                $branch[] = array(
                    'label'     => _('Materials'),
                    'icon'      => '',
                    'reference' => 'account/data_sets/materials'
                );
                $branch[] = array(
                    'label'     => '<span class="Material_Name">'.$state['_object']->get('Name').'</span>',
                    'icon'      => 'puzzle-piece',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'osf') {
                $branch[] = array(
                    'label'     => _('Data sets'),
                    'icon'      => 'align-left',
                    'reference' => 'account/data_sets'
                );
                $branch[] = array(
                    'label'     => _('Transactions timeseries'),
                    'icon'      => '',
                    'reference' => 'account/data_sets/osf'
                );

            } elseif ($state['section'] == 'isf') {
                $branch[] = array(
                    'label'     => _('Data sets'),
                    'icon'      => 'align-left',
                    'reference' => 'account/data_sets'
                );
                $branch[] = array(
                    'label'     => _('Inventory timeseries'),
                    'icon'      => '',
                    'reference' => 'account/data_sets/isf'
                );

            } elseif ($state['section'] == 'upload') {

                if ($state['parent'] == 'supplier') {
                    $branch   = array();
                    $branch[] = array(
                        'label'     => _('Suppliers'),
                        'icon'      => '',
                        'reference' => 'suppliers'
                    );
                    $branch[] = array(
                        'label'     => '<span class="Supplier_Code">'.$state['_parent']->get('Code').'</span>',
                        'icon'      => 'hand-holding-box',
                        'reference' => 'supplier/'.$state['parent_key']
                    );
                    $branch[] = array(
                        'label'     => _('Upload').' '.sprintf(
                                '%04d', $state['_object']->get('Key')
                            ),
                        'icon'      => 'upload',
                        'reference' => ''
                    );

                } elseif ($state['parent'] == 'inventory') {
                    $branch   = array();
                    $branch[] = array(
                        'label'     => _('Inventory'),
                        'icon'      => 'th-large',
                        'reference' => 'inventory'
                    );
                    $branch[] = array(
                        'label'     => _('Upload').' '.sprintf(
                                '%04d', $state['_object']->get('Key')
                            ),
                        'icon'      => 'upload',
                        'reference' => ''
                    );

                } else {

                    $branch[] = array(
                        'label'     => _('Data sets'),
                        'icon'      => 'align-left',
                        'reference' => 'account/data_sets'
                    );
                    $branch[] = array(
                        'label'     => _('Records uploads'),
                        'icon'      => '',
                        'reference' => 'account/data_sets/uploads'
                    );
                    $branch[] = array(
                        'label'     => _('Upload').' '.sprintf(
                                '%04d', $state['_object']->get('Key')
                            ),
                        'icon'      => 'upload',
                        'reference' => ''
                    );
                }
            }

            /*
		case ('data_sets'):
			return get_data_sets_navigation($data, $smarty, $user, $db,$account);
			break;
		case ('timeseries'):
			return get_timeseries_navigation($data, $smarty, $user, $db,$account);
			break;
		case ('images'):
			return get_images_navigation($data, $smarty, $user, $db,$account);
			break;
		case ('attachments'):
			return get_attachments_navigation($data, $smarty, $user, $db,$account);
			break;
		case ('osf'):
			return get_osf_navigation($data, $smarty, $user, $db,$account);
			break;
		case ('isf'):
			return get_isf_navigation($data, $smarty, $user, $db,$account);
			break;
*/


            break;

        case 'production_server':
            $branch[] = array(
                'label'     => _("Production (All manufactures)"),
                'icon'      => 'industry',
                'reference' => 'production/all'
            );


            if ($state['section'] == 'production.suppliers') {

                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => 'hand-holding-box',
                    'reference' => ''
                );

            }

            break;

        case 'production':


            /*
            $branch[] = array(
                'label'     => _("(All manufactures)"),
                'icon'      => '',
                'reference' => 'production/all'
            );
            */ $branch[] = array(
            'label'     => _("Production").' <span class="id Supplier_Code">'.$state['_object']->get('Code').'</span>',
            'icon'      => 'industry',
            'reference' => 'production'
        );


            if ($state['section'] == 'manufacture_tasks') {

                $branch[] = array(
                    'label'     => _("Manufacture Tasks"),
                    'icon'      => 'tasks',
                    'reference' => 'production/manufacture_tasks'
                );
            } elseif ($state['section'] == 'manufacture_task') {

                $branch[] = array(
                    'label'     => _("Manufacture Tasks"),
                    'icon'      => 'tasks',
                    'reference' => 'production/manufacture_tasks'
                );
                $branch[] = array(
                    'label'     => '<span class="Manufacture_Task_Code">'.$state['_object']->get('Code').'</span>',
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'settings') {

                $branch[] = array(
                    'label'     => _('Settings'),
                    'icon'      => 'sliders',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'dashboard') {

                $branch[] = array(
                    'label'     => _('Dashboard'),
                    'icon'      => 'tachometer',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'materials') {

                $branch[] = array(
                    'label'     => _('Materials'),
                    'icon'      => 'puzzle-piece',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'production_parts') {

                $branch[] = array(
                    'label'     => _('Parts'),
                    'icon'      => 'hand-receiving',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'production_part') {

                $branch[] = array(
                    'label'     => _('Parts'),
                    'icon'      => 'hand-receiving',
                    'reference' => 'production/'.$state['current_production'].'/parts'
                );

                $branch[] = array(
                    'label'     => $state['_object']->get('Reference'),
                    'icon'      => 'box',
                    'reference' => ''
                );

            }

            break;
        case 'reports':
            $branch[] = array(
                'label'     => _('Reports'),
                'icon'      => '',
                'reference' => 'reports'
            );

            if ($state['section'] == 'billingregion_taxcategory') {
                $branch[] = array(
                    'label'     => _(
                        'Billing region & Tax code report'
                    ),
                    'icon'      => '',
                    'reference' => 'report/billingregion_taxcategory'
                );

            } elseif ($state['section'] == 'sales') {
                $branch[] = array(
                    'label'     => _('Sales report'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'report_orders') {
                $branch[] = array(
                    'label'     => _("Dispatched order's sales"),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'report_orders_components') {
                $branch[] = array(
                    'label'     => _("Dispatched order's x-rays"),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'report_delivery_notes') {
                $branch[] = array(
                    'label'     => _('Delivery notes'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'pickers') {
                $branch[] = array(
                    'label'     => _('Pickers productivity'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'packers') {
                $branch[] = array(
                    'label'     => _('Packers productivity'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'sales_representatives') {
                $branch[] = array(
                    'label'     => _('Sales representatives productivity'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'sales_representative') {
                $branch[] = array(
                    'label'     => _('Sales representatives productivity'),
                    'icon'      => '',
                    'reference' => 'report/sales_representatives'
                );
                $branch[] = array(
                    'label'     => $state['_object']->user->get('Alias'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'prospect_agents') {
                $branch[] = array(
                    'label'     => _("Prospect's agents"),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'prospect_agent') {
                $branch[] = array(
                    'label'     => _("Prospect's agents"),
                    'icon'      => '',
                    'reference' => 'report/prospect_agents'
                );
                $branch[] = array(
                    'label'     => $state['_object']->user->get('Alias'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'prospect_agent_email_tracking') {
                $branch[] = array(
                    'label'     => _("Prospect's agents"),
                    'icon'      => '',
                    'reference' => 'report/prospect_agents'
                );
                $branch[] = array(
                    'label'     => $state['_parent']->user->get('Alias'),
                    'icon'      => '',
                    'reference' => 'report/prospect_agents/'.$state['parent_key']
                );
                $branch[] = array(
                    'label'     => $state['_object']->get('Email Tracking Email'),
                    'icon'      => 'paper-plane',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'lost_stock') {
                $branch[] = array(
                    'label'     => _('Lost/Damaged stock'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'stock_given_free') {
                $branch[] = array(
                    'label'     => _('Stock given for free'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'intrastat') {
                $branch[] = array(
                    'label'     => _('Intrastat exports'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'intrastat_imports') {
                $branch[] = array(
                    'label'     => _('Intrastat imports'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'intrastat_products') {
                $branch[] = array(
                    'label'     => _('Intrastat'),
                    'icon'      => '',
                    'reference' => 'report/intrastat'
                );


                $_data  = preg_split('/\|/', $state['extra']);
                $__data = preg_split('/\_/', $_data[1]);

                $_parameters = array(

                    'period' => (!empty($_SESSION['table_state']['intrastat']['period']) ? $_SESSION['table_state']['intrastat']['period'] : 'last_m'),
                    'from'   => (!empty($_SESSION['table_state']['intrastat']['from']) ? $_SESSION['table_state']['intrastat']['from'] : ''),
                    'to'     => (!empty($_SESSION['table_state']['intrastat']['to']) ? $_SESSION['table_state']['intrastat']['to'] : '')


                );


                include_once 'utils/date_functions.php';

                list(
                    $db_interval, $from, $to, $from_date_1yb, $to_1yb
                    ) = calculate_interval_dates(
                    $db, $_parameters['period'], $_parameters['from'], $_parameters['to']
                );

                $_from   = strftime('%d %b %Y', strtotime($from));
                $_to     = strftime('%d %b %Y', strtotime($to));
                $_period = '';
                if ($_from != $_to) {
                    $_period .= " ($_from-$_to)";
                } else {
                    $_period .= " ($_from)";
                }

                $_tariff_code = ($__data[1] == 'missing' ? _('comodity code missing') : $__data[1]);

                $branch[] = array(
                    'label'     => _('Products')."; $__data[0], $_tariff_code  $_period",
                    'icon'      => '',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'intrastat_orders') {
                $branch[] = array(
                    'label'     => _('Intrastat'),
                    'icon'      => '',
                    'reference' => 'report/intrastat'
                );


                $_data  = preg_split('/\|/', $state['extra']);
                $__data = preg_split('/\_/', $_data[1]);

                $_parameters = array(

                    'period' => (!empty($_SESSION['table_state']['intrastat']['period']) ? $_SESSION['table_state']['intrastat']['period'] : 'last_m'),
                    'from'   => (!empty($_SESSION['table_state']['intrastat']['from']) ? $_SESSION['table_state']['intrastat']['from'] : ''),
                    'to'     => (!empty($_SESSION['table_state']['intrastat']['to']) ? $_SESSION['table_state']['intrastat']['to'] : '')


                );


                include_once 'utils/date_functions.php';

                list(
                    $db_interval, $from, $to, $from_date_1yb, $to_1yb
                    ) = calculate_interval_dates(
                    $db, $_parameters['period'], $_parameters['from'], $_parameters['to']
                );

                $_from   = strftime('%d %b %Y', strtotime($from));
                $_to     = strftime('%d %b %Y', strtotime($to));
                $_period = '';
                if ($_from != $_to) {
                    $_period .= " ($_from-$_to)";
                } else {
                    $_period .= " ($_from)";
                }

                $_tariff_code = ($__data[1] == 'missing' ? _('comodity code missing') : $__data[1]);

                $branch[] = array(
                    'label'     => _('Orders')."; $__data[0], $_tariff_code  $_period",
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'intrastat_parts') {
                $branch[] = array(
                    'label'     => _('Intrastat imports'),
                    'icon'      => '',
                    'reference' => 'report/intrastat_imports'
                );


                $_data  = preg_split('/\|/', $state['extra']);
                $__data = preg_split('/\_/', $_data[1]);

                $_parameters = array(

                    'period' => (!empty($_SESSION['table_state']['intrastat_imports']['period']) ? $_SESSION['table_state']['intrastat_imports']['period'] : 'last_m'),
                    'from'   => (!empty($_SESSION['table_state']['intrastat_imports']['from']) ? $_SESSION['table_state']['intrastat_imports']['from'] : ''),
                    'to'     => (!empty($_SESSION['table_state']['intrastat_imports']['to']) ? $_SESSION['table_state']['intrastat_imports']['to'] : '')


                );


                include_once 'utils/date_functions.php';

                list(
                    $db_interval, $from, $to, $from_date_1yb, $to_1yb
                    ) = calculate_interval_dates(
                    $db, $_parameters['period'], $_parameters['from'], $_parameters['to']
                );

                $_from   = strftime('%d %b %Y', strtotime($from));
                $_to     = strftime('%d %b %Y', strtotime($to));
                $_period = '';
                if ($_from != $_to) {
                    $_period .= " ($_from-$_to)";
                } else {
                    $_period .= " ($_from)";
                }

                $_tariff_code = ($__data[1] == 'missing' ? _('commodity code missing') : $__data[1]);

                $branch[] = array(
                    'label'     => _('Parts')."; $__data[0], $_tariff_code  $_period",
                    'icon'      => '',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'intrastat_deliveries') {
                $branch[] = array(
                    'label'     => _('Intrastat imports'),
                    'icon'      => '',
                    'reference' => 'report/intrastat_imports'
                );


                $_data  = preg_split('/\|/', $state['extra']);
                $__data = preg_split('/\_/', $_data[1]);

                $_parameters = array(

                    'period' => (!empty($_SESSION['table_state']['intrastat_imports']['period']) ? $_SESSION['table_state']['intrastat_imports']['period'] : 'last_m'),
                    'from'   => (!empty($_SESSION['table_state']['intrastat_imports']['from']) ? $_SESSION['table_state']['intrastat_imports']['from'] : ''),
                    'to'     => (!empty($_SESSION['table_state']['intrastat_imports']['to']) ? $_SESSION['table_state']['intrastat_imports']['to'] : '')


                );


                include_once 'utils/date_functions.php';

                list(
                    $db_interval, $from, $to, $from_date_1yb, $to_1yb
                    ) = calculate_interval_dates(
                    $db, $_parameters['period'], $_parameters['from'], $_parameters['to']
                );

                $_from   = strftime('%d %b %Y', strtotime($from));
                $_to     = strftime('%d %b %Y', strtotime($to));
                $_period = '';
                if ($_from != $_to) {
                    $_period .= " ($_from-$_to)";
                } else {
                    $_period .= " ($_from)";
                }

                $_tariff_code = ($__data[1] == 'missing' ? _('commodity code missing') : $__data[1]);

                $branch[] = array(
                    'label'     => _('Deliveries')."; $__data[0], $_tariff_code  $_period",
                    'icon'      => '',
                    'reference' => ''
                );

            } else {
                if ($state['section'] == 'billingregion_taxcategory.invoices') {
                    $branch[] = array(
                        'label'     => _(
                            'Billing region & Tax code report'
                        ),
                        'icon'      => '',
                        'reference' => 'report/billingregion_taxcategory'
                    );


                    $parents = preg_split('/_/', $state['parent_key']);

                    switch ($parents[0]) {
                        case 'EU':
                            $billing_region = _('European Union');
                            break;
                        case 'NOEU':
                            $billing_region = _('Outside European Union');
                            break;
                        case 'GBIM':
                            $billing_region = 'GB+IM';
                            break;
                        case 'Unknown':
                            $billing_region = _('Unknown');
                            break;
                        default:
                            $billing_region = $parents[0];
                            break;
                    }

                    $label    = _('Invoices')." $billing_region & ".$parents[1];
                    $branch[] = array(
                        'label'     => $label,
                        'icon'      => '',
                        'reference' => ''
                    );

                } else {
                    if ($state['section'] == 'billingregion_taxcategory.refunds') {
                        $branch[] = array(
                            'label'     => _(
                                'Billing region & Tax code report'
                            ),
                            'icon'      => '',
                            'reference' => 'report/billingregion_taxcategory'
                        );
                        $parents  = preg_split('/_/', $state['parent_key']);

                        switch ($parents[0]) {
                            case 'EU':
                                $billing_region = _('European Union');
                                break;
                            case 'Unknown':
                                $billing_region = _('Unknown');
                                break;
                            case 'NOEU':
                                $billing_region = _('Outside European Union');
                                break;
                            case 'GBIM':
                                $billing_region = 'GB+IM';
                                break;
                            default:
                                $billing_region = $state[0];
                                break;
                        }

                        $label    = _('Refunds')." $billing_region & ".$parents[1];
                        $branch[] = array(
                            'label'     => $label,
                            'icon'      => '',
                            'reference' => ''
                        );
                    } elseif ($state['section'] == 'ec_sales_list') {
                        $branch[] = array(
                            'label'     => _('EC sales list'),
                            'icon'      => '',
                            'reference' => 'report/ec_sales_list'
                        );

                    }
                }
            }

            break;


        case 'marketing_server':
            $branch[] = array(
                'label'     => _('Marketing (All stores)'),
                'icon'      => 'bullhorn',
                'reference' => ''
            );

            break;

            break;
        case 'marketing':
            $state['current_store'] = $state['store']->id;
            $branch[]               = array(
                'label'     => _('(All stores)'),
                'icon'      => 'bullhorn',
                'reference' => 'marketing/all'
            );

            if ($state['section'] == 'deals') {

                $branch[] = array(
                    'label'     => _('Offers').' '.$state['store']->get('Code'),
                    'icon'      => 'tag',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'campaigns') {
                $branch[] = array(
                    'label'     => _('Campaigns').' <span class="Store_Code">'.$state['store']->get('Code').'</span>',
                    'icon'      => 'tags',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'campaign') {
                $branch[] = array(
                    'label'     => _('Campaigns').' <span class="Store_Code">'.$state['store']->get('Code').'</span>',
                    'icon'      => 'tags',
                    'reference' => 'campaigns/'.$state['store']->id
                );

                $branch[] = array(
                    'label'     => '<span class="Deal_Campaign_Name">'.$state['_object']->get('Name').'</span>',
                    'icon'      => 'tags',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'campaign.new') {
                $branch[] = array(
                    'label'     => _('Campaigns').' <span class="Store_Code">'.$state['store']->get('Code').'</span>',
                    'icon'      => 'tags',
                    'reference' => 'campaigns/'.$state['store']->id
                );

                $branch[] = array(
                    'label'     => _('New campaign'),
                    'icon'      => '',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'deal.new') {


                if ($state['parent'] == 'campaign') {

                    include_once 'class.Store.php';
                    $state['store'] = new Store($state['_parent']->get('Store Key'));

                    $branch[] = array(
                        'label'     => _('Campaigns').' <span class="Store_Code">'.$state['store']->get('Code').'</span>',
                        'icon'      => 'tags',
                        'reference' => 'campaigns/'.$state['store']->id
                    );

                    $branch[] = array(
                        'label'     => '<span class="Deal_Campaign_Name">'.$state['_parent']->get('Name').'</span>',
                        'icon'      => 'tags',
                        'reference' => ''
                    );

                }


                $branch[] = array(
                    'label'     => _('New offer'),
                    'icon'      => '',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'deal') {

                if ($state['parent'] == 'campaign') {
                    $branch[] = array(
                        'label'     => _('Campaigns').' <span class="Store_Code">'.$state['store']->get(
                                'Code'
                            ).'</span>',
                        'icon'      => 'tags',
                        'reference' => 'campaigns/'.$state['store']->id
                    );

                    $branch[] = array(
                        'label'     => '<span class="Deal_Campaign_Name">'.$state['_parent']->get('Name').'</span>',
                        'icon'      => 'tags',
                        'reference' => ''
                    );

                }

                $branch[] = array(
                    'label'     => '<span class="Deal_Name">'.$state['_object']->get('Name').'</span>',
                    'icon'      => 'tag',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'dashboard') {
                $branch[] = array(
                    'label'     => _('Marketing dashboard').' <span class="Store_Code">'.$state['store']->get('Code').'</span>',
                    'icon'      => 'tachometer',
                    'reference' => ''
                );
            }
            break;


        case 'agent_suppliers':


            if ($state['section'] == 'suppliers') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => 'hand-holding-box',
                    'reference' => 'suppliers'
                );
            } elseif ($state['section'] == 'settings') {
                $branch[] = array(
                    'label'     => _("Suppliers' settings"),
                    'icon'      => 'sliders',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'orders') {
                $branch[] = array(
                    'label'     => _('Purchase orders'),
                    'icon'      => 'clipboard',
                    'reference' => 'suppliers.orders'
                );
            } elseif ($state['section'] == 'deliveries') {
                $branch[] = array(
                    'label'     => _('Deliveries'),
                    'icon'      => 'truck',
                    'reference' => 'suppliers.deliveries'
                );
            } elseif ($state['section'] == 'agents') {
                $branch[] = array(
                    'label'     => _('Agents'),
                    'icon'      => 'user-secret',
                    'reference' => 'agents'
                );
            } elseif ($state['section'] == 'supplier') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$state['_object']->get('Code').'</span> <span class="Supplier_Name italic">'.$state['_object']->get('Name').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['key']
                );

            } elseif ($state['section'] == 'supplier.attachment.new') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['parent_key']
                );
                $branch[] = array(
                    'label'     => _('Upload attachment'),
                    'icon'      => 'paperclip',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'supplier.attachment') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['parent_key']
                );
                $branch[] = array(
                    'label'     => '<span class="id Attachment_Caption">'.$state['_object']->get('Caption').'</span>',
                    'icon'      => 'paperclip',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'supplier.new') {

                if ($state['parent'] == 'agent') {
                    $branch[] = array(
                        'label'     => _('Agents'),
                        'icon'      => '',
                        'reference' => 'agents'
                    );
                    $branch[] = array(
                        'label'     => '<span class="Agent_Code">'.$state['_parent']->get('Code').'</span>',
                        'icon'      => 'user-secret',
                        'reference' => 'agent/'.$state['parent_key']
                    );

                } else {
                    $branch[] = array(
                        'label'     => _('Suppliers'),
                        'icon'      => '',
                        'reference' => 'suppliers'
                    );

                }

                $branch[] = array(
                    'label'     => _('New supplier'),
                    'icon'      => 'hand-holding-box',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'order' or $state['section'] == 'deleted_order') {

                if ($state['parent'] == 'account') {
                    $branch[] = array(
                        'label'     => _('Purchase orders'),
                        'icon'      => '',
                        'reference' => 'suppliers/orders'
                    );
                } elseif ($state['parent'] == 'supplier') {
                    $branch[] = array(
                        'label'     => _('Suppliers'),
                        'icon'      => '',
                        'reference' => 'suppliers'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Code'),
                        'icon'      => 'hand-holding-box',
                        'reference' => 'supplier/'.$state['parent_key']
                    );
                } elseif ($state['parent'] == 'agent') {
                    $branch[] = array(
                        'label'     => _('Agents'),
                        'icon'      => '',
                        'reference' => 'agents'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Agent_Code">'.$state['_parent']->get('Code'),
                        'icon'      => 'user-secret',
                        'reference' => 'agent/'.$state['parent_key']
                    );
                }
                $branch[] = array(
                    'label'     => '<span class="Purchase_Order_Public_ID">'.$state['_object']->get('Public ID').'</span>',
                    'icon'      => 'clipboard',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'delivery') {

                if ($state['parent'] == 'account') {
                    $branch[] = array(
                        'label'     => _('Deliveries'),
                        'icon'      => '',
                        'reference' => 'suppliers/deliveries'
                    );
                } elseif ($state['parent'] == 'supplier') {
                    $branch[] = array(
                        'label'     => _('Suppliers'),
                        'icon'      => '',
                        'reference' => 'suppliers'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Code'),
                        'icon'      => 'hand-holding-box',
                        'reference' => 'supplier/'.$state['parent_key']
                    );
                } elseif ($state['parent'] == 'agent') {
                    $branch[] = array(
                        'label'     => _('Agents'),
                        'icon'      => '',
                        'reference' => 'agents'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Agent_Code">'.$state['_parent']->get('Code'),
                        'icon'      => 'user-secret',
                        'reference' => 'agent/'.$state['parent_key']
                    );
                }
                $branch[] = array(
                    'label'     => '<span class="Supplier_Delivery_Public_ID">'.$state['_object']->get('Public ID').'</span>',
                    'icon'      => 'truck',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'supplier.order.item') {

                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Parent Code'),
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['_parent']->get(
                            'Purchase Order Parent Key'
                        )
                );
                $branch[] = array(
                    'label'     => '<span class="Purchase_Order_Public_ID">'.$state['_parent']->get('Public ID').'</span>',
                    'icon'      => 'clipboard',
                    'reference' => 'supplier/'.$state['_parent']->get(
                            'Purchase Order Parent Key'
                        ).'/order/'.$state['parent_key']
                );


                $branch[] = array(
                    'label'     => '<span class="Supplier_Part_Reference">'.$state['_object']->get('Reference').'</span>',
                    'icon'      => 'bars',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'agent.order.item') {


                $branch[] = array(
                    'label'     => _('Agents'),
                    'icon'      => '',
                    'reference' => 'agents'
                );
                $branch[] = array(
                    'label'     => '<span class="id Agent_Code">'.$state['_parent']->get('Parent Code'),
                    'icon'      => 'user-secret',
                    'reference' => 'agent/'.$state['_parent']->get(
                            'Purchase Order Parent Key'
                        )
                );
                $branch[] = array(
                    'label'     => '<span class="Purchase_Order_Public_ID">'.$state['_parent']->get('Public ID').'</span>',
                    'icon'      => 'clipboard',
                    'reference' => 'supplier/'.$state['_parent']->get(
                            'Purchase Order Parent Key'
                        ).'/order/'.$state['parent_key']
                );


                $branch[] = array(
                    'label'     => '<span class="Supplier_Part_Reference">'.$state['_object']->get('Reference').'</span>',
                    'icon'      => 'bars',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'supplier_part') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="Supplier_Code">'.$state['_parent']->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['_parent']->id
                );
                $branch[] = array(
                    'label'     => '<span class="Supplier_Part_Reference">'.$state['_object']->get('Reference').'</span>',
                    'icon'      => 'stop',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'supplier_part.new') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="Supplier_Code">'.$state['_parent']->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['_parent']->id
                );
                $branch[] = array(
                    'label'     => _("New supplier's part"),
                    'icon'      => 'stop',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'agent.user.new') {
                $branch[] = array(
                    'label'     => _('Agents'),
                    'icon'      => '',
                    'reference' => 'agents'
                );
                $branch[] = array(
                    'label'     => '<span class="Agent_Code">'.$state['_parent']->get('Code').'</span> <span class="Agent_Name italic">'.$state['_parent']->get('Name').'</span>',
                    'icon'      => 'user-secret',
                    'reference' => 'agent/'.$state['parent_key']
                );
                $branch[] = array(
                    'label'     => _('New system user'),
                    'icon'      => 'terminal',
                    'reference' => ''
                );

            }


            break;

        case 'agent_client_orders':

            switch ($state['section']) {
                case 'orders':
                    $branch[] = array(
                        'label'     => _("Client's orders"),
                        'icon'      => 'clipboard',
                        'reference' => 'agents'
                    );
                    break;
                case 'client_order':
                    $branch[] = array(
                        'label'     => _("Client's orders"),
                        'icon'      => '',
                        'reference' => 'orders'
                    );
                    $branch[] = array(
                        'label'     => '<class ="Purchase_Order_Public_ID">'.$state['_object']->get('Public ID').'</span>',
                        'icon'      => 'clipboard',
                        'reference' => 'orders'
                    );
                    break;
                case 'agent_supplier_order':
                    $branch[] = array(
                        'label'     => _("Client's orders"),
                        'icon'      => '',
                        'reference' => 'orders'
                    );
                    $branch[] = array(
                        'label'     => '<class ="Purchase_Order_Public_ID">'.$state['_parent']->get('Public ID').'</span>',
                        'icon'      => 'clipboard',
                        'reference' => 'client_order/'.$state['_parent']->id
                    );
                    $branch[] = array(
                        'label'     => '<class ="Agent_Supplier_Purchase_Order_Public_ID">'.$state['_object']->get('Public ID').'</span>',
                        'icon'      => 'paste',
                        'reference' => ''
                    );

            }

            break;

        case 'agent_client_deliveries':


            switch ($state['section']) {
                case 'deliveries':
                    $branch[] = array(
                        'label'     => _("Deliveries"),
                        'icon'      => 'truck-container',
                        'reference' => 'agent_deliveries'
                    );
                    break;
                case 'agent_delivery':
                    $branch[] = array(
                        'label'     => _("Deliveries"),
                        'icon'      => '',
                        'reference' => 'agent_deliveries'
                    );
                    $branch[] = array(
                        'label'     => '<class ="Supplier_Delivery_Public_ID">'.$state['_object']->get('Public ID').'</span>',
                        'icon'      => 'truck-container',
                        'reference' => ''
                    );
                    break;


            }

            break;

        case 'agent_parts':

            switch ($state['section']) {
                case 'parts':
                    $branch[] = array(
                        'label'     => _("Products"),
                        'icon'      => 'stop',
                        'reference' => ''
                    );
                    break;


            }

            break;

        default:


    }

    $_content = array(
        'branch' => $branch,

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('view_position.tpl');

    return array(
        $state,
        $html
    );


}

