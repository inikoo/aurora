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
        get_view($db, $smarty, $user, $account, $modules);
        break;
    case 'widget_details':
        get_widget_details($db, $smarty, $user, $account);
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

function get_widget_details($db, $smarty, $user, $account) {

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
        'widget_details' => $html
    );
    echo json_encode($response);

}


function get_view($db, $smarty, $user, $account, $modules) {

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

    if (isset($data['metadata']['help']) and $data['metadata']['help']) {
        get_help($data, $modules, $db);

        return;
    }


    if (isset($data['metadata']['reload']) and $data['metadata']['reload']) {
        $reload = true;
    } else {
        $reload = false;
    }


    $state = parse_request($data, $db, $modules, $account, $user);


    $state['current_store']     = $_SESSION['current_store'];
    $state['current_website']   = $_SESSION['current_website'];
    $state['current_warehouse'] = $_SESSION['current_warehouse'];


    $store     = '';
    $website   = '';
    $warehouse = '';


    switch ($state['parent']) {

        case 'store':
            include_once 'class.Store.php';

            if ($state['parent_key'] != '') {
                $_parent = new Store($state['parent_key']);
            } else {
                if ($state['object'] == 'product') {
                    $_object             = get_object(
                        $state['object'], $state['key']
                    );
                    $_parent             = new Store(
                        $_object->get('Product Store Key')
                    );
                    $state['parent_key'] = $_parent->id;
                } elseif ($state['object'] == 'customer') {
                    $_object             = get_object(
                        $state['object'], $state['key']
                    );
                    $_parent             = new Store(
                        $_object->get('Customer Store Key')
                    );
                    $state['parent_key'] = $_parent->id;

                } else {
                    print $state['object'];

                }

            }

            $state['current_store'] = $_parent->id;
            $store                  = $_parent;
            break;

        case 'part':
            include_once 'class.Part.php';
            include_once 'class.Warehouse.php';

            $_parent   = new Part($state['parent_key']);
            $warehouse = new Warehouse($state['current_warehouse']);

            break;
        case 'website':
            $_parent                  = get_object('Website', $state['parent_key']);
            $website                  = $_parent;
            $state['current_website'] = $_parent->id;
            $website                  = $_parent;

            break;
        case 'page':
            $_parent                  = get_object('Webpage', $state['parent_key']);
            $website                  = get_object('Website', $_parent->get('Webpage Website Key'));
            $state['current_website'] = $website->id;
            $website                  = $website;

            break;
        case 'node':
            $_parent                  = get_object(
                'WebsiteNode', $state['parent_key']
            );
            $website                  = get_object('Website', $_parent->get('Website Node Website Key'));
            $state['current_website'] = $website->id;


            break;


        case 'warehouse':
            include_once 'class.Warehouse.php';
            $_parent                    = new Warehouse($state['parent_key']);
            $warehouse                  = $_parent;
            $state['current_warehouse'] = $_parent->id;

            break;
        case 'category':
            include_once 'class.Category.php';
            include_once 'class.Store.php';
            $_parent = new Category($state['parent_key']);

            if ($_parent->get('Category Scope') == 'Product' or $_parent->get(
                    'Category Scope'
                ) == 'Customer'
            ) {
                $store = new Store($_parent->get('Store Key'));

            }

            break;
        case 'day':
        case 'month':
        case 'week':
            $_parent = '';
            break;
        default:
            $_parent = get_object($state['parent'], $state['parent_key']);

    }
    $state['_parent'] = $_parent;


    if ($state['object'] != '') {


        print_r($state['object']);


        if (!isset($_object)) {
            $_object = get_object($state['object'], $state['key']);
        }

        print_r($_object);
        if (is_numeric($_object->get('Store Key'))) {
            include_once 'class.Store.php';
            $store                  = new Store($_object->get('Store Key'));
            $state['current_store'] = $store->id;
        }
        if (is_numeric($_object->get('Warehouse Key'))) {
            include_once 'class.Warehouse.php';
            $warehouse                  = new Warehouse($_object->get('Warehouse Key'));
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


        if (!$_object->id and $modules[$state['module']]['sections'][$state['section']]['type'] == 'object') {


            if ($state['object'] == 'barcode') {
                $_object          = new Barcode('deleted', $state['key']);
                $state['_object'] = $_object;
                if ($_object->id) {
                    $state['section'] = 'deleted_barcode';
                    $state['tab']     = 'barcode.history';

                }
            } elseif ($state['object'] == 'supplier') {
                $_object          = new Supplier('deleted', $state['key']);
                $state['_object'] = $_object;
                if ($_object->id) {
                    $state['section'] = 'deleted_supplier';
                    $state['tab']     = 'supplier.history';

                }
            } elseif ($state['object'] == 'purchase_order') {
                $_object          = new PurchaseOrder('deleted', $state['key']);
                $state['_object'] = $_object;


                if ($_object->id) {
                    $state['section'] = 'deleted_order';

                    if (!array_key_exists(
                        $state['tab'], $modules[$state['module']]['sections'][$state['section']]['tabs']
                    )
                    ) {
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
                    )
                    ) {
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
                    )
                    ) {
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
                    )
                    ) {
                        $state['tab'] = 'deleted.contractor.history';
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

    $state['store']     = $store;
    $state['website']   = $website;
    $state['warehouse'] = $warehouse;


    $sql = sprintf(
        'INSERT INTO `User System View Fact`  (`User Key`,`Date`,`Module`,`Section`,`Tab`,`Parent`,`Parent Key`,`Object`,`Object Key`)  VALUES (%d,%s,%s,%s,%s,%s,%s,%s,%s)', $user->id,
        prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql($state['module']), prepare_mysql($state['section']), prepare_mysql(
            ($state['subtab'] != '' ? $state['subtab'] : $state['tab'])
        ), prepare_mysql($state['parent']), prepare_mysql($state['parent_key']), prepare_mysql($state['object']), prepare_mysql($state['key'])

    );
    $db->exec($sql);

    $_SESSION['request'] = $state['request'];


    if (isset($state['current_store'])) {
        $_SESSION['current_store'] = $state['current_store'];
    }
    if (isset($state['current_website'])) {
        $_SESSION['current_website'] = $state['current_website'];
    }
    if (isset($state['current_warehouse'])) {
        $_SESSION['current_warehouse'] = $state['current_warehouse'];
    }

    $response = array('state' => array());

    list($state, $response['view_position']) = get_view_position(
        $db, $state, $user, $smarty, $account
    );


    if ($data['old_state']['module'] != $state['module'] or $reload) {
        $response['menu'] = get_menu($state, $user, $smarty);

    }


    if ($data['old_state']['module'] != $state['module'] or $data['old_state']['section'] != $state['section'] or $data['old_state']['parent_key'] != $state['parent_key'] or $data['old_state']['key']
        != $state['key'] or $reload or isset($data['metadata']['reload_showcase'])

    ) {


        $response['navigation'] = get_navigation($user, $smarty, $state, $db, $account);
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
    }


    list($state, $response['tabs']) = get_tabs(
        $state, $db, $account, $modules, $user, $smarty
    );// todo only calculate when is subtabs in the section


    if ($state['object'] != '' and ($modules[$state['module']]['sections'][$state['section']]['type'] == 'object' or isset($modules[$state['module']]['sections'][$state['section']]['showcase']))) {

        if (isset($data['metadata']['reload_showcase']) or !($data['old_state']['module'] == $state['module'] and $data['old_state']['section'] == $state['section'] and $data['old_state']['object']
                == $state['object'] and $data['old_state']['key'] == $state['key'])
        ) {


            $response['object_showcase'] = get_object_showcase(
                (isset($modules[$state['module']]['sections'][$state['section']]['showcase']) ? $modules[$state['module']]['sections'][$state['section']]['showcase'] : $state['object']), $state,
                                                                                                                                                                                           $smarty,
                                                                                                                                                                                           $user, $db,
                                                                                                                                                                                           $account
            );

        }


    } else {


        $response['object_showcase'] = '_';
    }

    $state['metadata'] = (isset($data['metadata']) ? $data['metadata'] : array());


    $response['tab'] = get_tab($db, $smarty, $user, $account, $state['tab'], $state['subtab'], $state, $data['metadata']);


    unset($state['_object']);
    unset($state['_parent']);

    unset($state['old_state']['_parent']);
    unset($state['old_state']['_object']);
    unset($state['old_state']['store']);
    unset($state['old_state']['website']);
    unset($state['old_state']['warehouse']);

    unset($state['store']);
    unset($state['website']);
    unset($state['warehouse']);
    $response['state'] = $state;


    echo json_encode($response);

}

function get_tab($db, $smarty, $user, $account, $tab, $subtab, $state = false, $metadata = false) {


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
    //print $tab;
    if (is_array($state) and !(preg_match('/\_edit$/', $tab) or preg_match(
                '/\.wget$/', $tab
            ) or $tab == 'part_family.product_family.new')
    ) {


        $_SESSION['state'][$state['module']][$state['section']]['tab'] = $_tab;
        if ($_subtab != '') {
            $_SESSION['tab_state'][$_tab] = $_subtab;
        }

    }

    return $html;

}


function get_object_showcase($showcase, $data, $smarty, $user, $db, $account) {

    if (preg_match('/\_edit$/', $data['tab'])) {
        return '';
    }
    switch ($showcase) {
        case 'material':
            include_once 'showcase/material.show.php';
            $html = get_showcase($data, $smarty, $user, $db);
            break;
        case 'page':
            include_once 'showcase/webpage.show.php';
            $html = get_webpage_showcase($data, $smarty, $user, $db);
            break;
        case 'page_version':
            include_once 'showcase/webpage_version.show.php';
            $html = get_webpage_version_showcase($data, $smarty, $user, $db);
            break;
        case 'website':
        case 'dashboard':
            $html = '';
            break;
        case 'node':
            include_once 'showcase/website_node.show.php';
            $html = get_website_node_showcase($data, $smarty, $user, $db);
            break;
        case 'upload':
            include_once 'showcase/upload.show.php';
            $html = get_upload_showcase($data, $smarty, $user, $db);
            break;
        case 'purchase_order':
            include_once 'showcase/supplier.order.show.php';
            $html = get_supplier_order_showcase($data, $smarty, $user, $db);
            break;
        case 'campaign':
            include_once 'showcase/campaign.show.php';
            $html = get_campaign_showcase($data, $smarty, $user, $db);
            break;
        case 'deal':
            include_once 'showcase/deal.show.php';
            $html = get_deal_showcase($data, $smarty, $user, $db);
            break;
        case 'store':

            include_once 'showcase/store.show.php';
            $html = get_store_showcase($data, $smarty, $user, $db);
            break;
        case 'products_special_categories':
            include_once 'showcase/products_special_categories.show.php';
            $html = get_products_special_categories_showcase(
                $data, $smarty, $user, $db
            );
            break;
        case 'account':


            if ($data['module'] == 'products_server') {
                include_once 'showcase/stores.show.php';
                $html = get_stores_showcase($data, $smarty, $user, $db);

            } else {

                include_once 'showcase/account.show.php';
                $html = get_account_showcase($data, $smarty, $user, $db);
            }
            break;
        case 'product':
            include_once 'showcase/product.show.php';
            $html = get_product_showcase($data, $smarty, $user, $db);
            break;
        case 'part':
            include_once 'showcase/part.show.php';
            $html = get_part_showcase($data, $smarty, $user, $db);
            break;
        case 'supplier_part':
            include_once 'showcase/supplier_part.show.php';
            $html = get_supplier_part_showcase($data, $smarty, $user, $db);
            break;
        case 'employee':
            include_once 'showcase/employee.show.php';
            $html = get_employee_showcase($data, $smarty, $user, $db);
            break;
        case 'contractor':
            include_once 'showcase/contractor.show.php';
            $html = get_contractor_showcase($data, $smarty, $user, $db);
            break;
        case 'customer':
            include_once 'showcase/customer.show.php';
            $html = get_customer_showcase($data, $smarty, $user, $db);
            break;
        case 'supplier':
            include_once 'showcase/supplier.show.php';
            $html = get_supplier_showcase($data, $smarty, $user, $db);
            break;
        case 'agent':
            include_once 'showcase/agent.show.php';
            $html = get_agent_showcase($data, $smarty, $user, $db);
            break;
        case 'order':
            include_once 'showcase/order.show.php';
            $html = get_order_showcase($data, $smarty, $user, $db);
            break;
        case 'invoice':
            include_once 'showcase/invoice.show.php';
            $html = get_invoice_showcase($data, $smarty, $user, $db);
            break;
        case 'delivery_note':
            include_once 'showcase/delivery_note.show.php';
            $html = get_delivery_note_showcase($data, $smarty, $user, $db);
            break;
        case 'user':
            include_once 'showcase/user.show.php';
            $html = get_user_showcase($data, $smarty, $user, $db);
            break;
        case 'warehouse':
            include_once 'showcase/warehouse.show.php';

            if (!$user->can_view('locations') or !in_array(
                    $data['key'], $user->warehouses
                )
            ) {
                $html = get_locked_warehouse_showcase(
                    $data, $smarty, $user, $db
                );

            } else {
                $html = get_warehouse_showcase($data, $smarty, $user, $db);
            }
            break;
        case 'location':
            include_once 'showcase/location.show.php';

            if (!$user->can_view('locations') or !in_array(
                    $data['warehouse']->id, $user->warehouses
                )
            ) {
                $html = get_locked_location_showcase(
                    $data, $smarty, $user, $db
                );

            } else {
                $html = get_location_showcase($data, $smarty, $user, $db);
            }
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

                if ($data['_object']->id == $data['store']->get(
                        'Store Family Category Key'
                    ) or $data['_object']->id == $data['store']->get(
                        'Store Department Category Key'
                    )
                ) {
                    $html = '';
                } elseif ($data['_object']->get('Root Key') == $data['store']->get('Store Family Category Key')) {
                    include_once 'showcase/family.show.php';
                    $html = get_family_showcase($data, $smarty, $user, $db);
                } elseif ($data['_object']->get('Root Key') == $data['store']->get('Store Department Category Key')) {
                    include_once 'showcase/department.show.php';
                    $html = get_department_showcase($data, $smarty, $user, $db);
                } else {
                    $html = '';
                }

            } elseif ($data['_object']->get('Category Scope') == 'Part') {

                if ($data['_object']->id == $account->get(
                        'Account Part Family Category Key'
                    )
                ) {
                    include_once 'showcase/part_families.show.php';
                    $html = get_part_familes_showcase(
                        $data, $smarty, $user, $db
                    );
                } elseif ($data['_object']->get('Root Key') == $account->get(
                        'Account Part Family Category Key'
                    )
                ) {
                    include_once 'showcase/part_family.show.php';
                    $html = get_part_family_showcase(
                        $data, $smarty, $user, $db
                    );
                } else {
                    return '_';
                }

            } elseif ($data['_object']->get('Category Scope') == 'Supplier') {
                include_once 'showcase/supplier_category_showcase.show.php';
                $html = get_supplier_category_showcase(
                    $data, $smarty, $user, $db
                );

            } else {
                return '_';
            }


            break;
        case 'PurchaseOrderItem':
            include_once 'showcase/supplier.order.item.show.php';
            $html = get_showcase($data, $smarty, $user, $db);
            break;
        case 'supplierdelivery':
            include_once 'showcase/supplier.delivery.show.php';
            $html = get_showcase($data, $smarty, $user, $db);
            break;
        case 'position':
            include_once 'showcase/job_position.show.php';
            $html = get_showcase($data, $smarty, $user, $db);
            break;
        case 'webpage_type':
            include_once 'showcase/webpage_type.show.php';
            $html = get_showcase($data, $smarty, $user, $db);
            break;
        default:
            $html = $data['object'].' -> '.$data['key'];
            break;
    }


    return $html;

}


function get_menu($data, $user, $smarty) {

    include_once 'navigation/menu.php';

    return $html;


}


function get_navigation($user, $smarty, $data, $db, $account) {


    switch ($data['module']) {

        case ('dashboard'):
            require_once 'navigation/dashboard.nav.php';

            return get_dashboard_navigation(
                $data, $smarty, $user, $db, $account
            );
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

            }
        case ('products'):
            require_once 'navigation/products.nav.php';
            switch ($data['section']) {

                case 'store':
                    return get_store_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case 'store.new':
                    return get_new_store_navigation(
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
                    break;
            }
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
                case ('dashboard'):
                    return get_customers_dashboard_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('statistics'):

                    return get_customers_statistics_navigation(
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
            }

            break;
        case ('orders_server'):
            require_once 'navigation/orders.nav.php';
            switch ($data['section']) {
                case ('orders'):
                case ('payments'):
                    return get_orders_server_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
            }

            break;
        case ('invoices_server'):
            require_once 'navigation/orders.nav.php';
            switch ($data['section']) {

                case ('invoices'):
                case ('payments'):
                    return get_invoices_server_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

                case ('categories'):
                    return get_invoices_categories_server_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('category'):
                    return get_invoices_category_server_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
            }


            break;
        case ('delivery_notes_server'):
            require_once 'navigation/orders.nav.php';
            switch ($data['section']) {
                case ('delivery_notes'):

                    return get_delivery_notes_server_navigation(
                        $data, $smarty, $user, $db, $account
                    );
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
                case ('payments'):
                    return get_orders_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('order'):
                    return get_order_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('delivery_note'):
                    return get_delivery_note_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('invoice'):
                    return get_invoice_navigation($data, $smarty, $user, $db, $account);
                    break;
                default:
                    return 'View not found';

            }
            break;
        case ('invoices'):
            require_once 'navigation/orders.nav.php';
            switch ($data['section']) {

                case ('invoices'):
                case ('payments'):
                    return get_invoices_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;

                case ('invoice'):
                    return get_invoice_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('delivery_note'):
                    return get_delivery_note_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('order'):
                    return get_order_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                default:
                    return 'View not found';

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
                    return 'View not found';

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
                case ('websites'):

                    return get_websites_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('webpages'):

                    return get_webpages_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('webpage_type'):
                    return get_webpage_type_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('website'):
                    return get_website_navigation($data, $smarty, $user, $db, $account);
                    break;
                case ('website.node'):
                    return get_node_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('page'):
                    return get_page_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('page_version'):
                    return get_page_version_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('website.user'):
                    return get_user_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                default:
                    return 'View not found';

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

        case ('marketing'):
            require_once 'navigation/marketing.nav.php';
            switch ($data['section']) {

                case ('campaigns'):
                    return get_campaigns_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('campaign'):
                    return get_campaign_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('deal'):
                    return get_deal_navigation(
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
                case ('dashboard'):
                    return get_customers_dashboard_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('statistics'):

                    return get_customers_statistics_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('pending_orders'):
                    return get_customers_pending_orders_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
            }


        case ('reports'):

            require_once 'navigation/reports.nav.php';
            switch ($data['section']) {
                case ('reports'):
                    return get_reports_navigation($user, $smarty, $data);
                    break;
                case ('performance'):
                    return get_performance_navigation($user, $smarty, $data);
                    break;
                case ('sales'):
                    return get_sales_navigation($user, $smarty, $data);
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
                case ('supplier_parts'):
                    return get_supplier_parts_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('supplier_part'):
                    return get_supplier_part_navigation(
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

            }
            break;
        case ('suppliers'):
            require_once 'navigation/suppliers.nav.php';
            switch ($data['section']) {
                case ('settings'):
                    return get_settings_navigation(
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

                    return get_product_navigation(
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
            require_once 'navigation/account.nav.php';

            return get_profile_navigation($data, $smarty, $user, $db, $account);
            break;

        case ('payments'):
            require_once 'navigation/payments.nav.php';
            switch ($data['section']) {
                case ('payment_service_provider'):
                    return get_payment_service_provider_navigation(
                        $data, $user, $smarty
                    );
                    break;
                case ('payment_service_providers'):
                    return get_payment_service_providers_navigation(
                        $data, $user, $smarty
                    );
                    break;
                case ('payment_account'):
                    return get_payment_account_navigation(
                        $data, $user, $smarty
                    );
                    break;
                case ('payment_accounts'):
                    return get_payment_accounts_navigation(
                        $data, $user, $smarty
                    );
                    break;
                case ('payment'):
                    return get_payment_navigation($data, $user, $smarty);
                    break;
                case ('payments'):
                    return get_payments_navigation($data, $user, $smarty);
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
                case ('users'):
                    return get_users_navigation(
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
                case ('settings'):
                    return get_settings_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('user.api_key') :
                    return get_api_key_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('user.api_key.new') :
                    return get_new_api_key_navigation(
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


            }
        case 'agent_client_deliveries':
            require_once 'navigation/agent.nav.php';
            switch ($data['section']) {
                case ('agent_deliveries'):
                    return get_agent_client_deliveries_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
                case ('agent_delivery'):
                    return get_agent_client_delivery_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;


            }
            break;
        default:
            return 'Module not found';
    }

}


function get_tabs($data, $db, $account, $modules, $user, $smarty) {


    if (preg_match('/\_edit$/', $data['tab'])) {
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

    $_content = array(
        'tabs'    => $tabs,
        'subtabs' => $subtabs


    );


    if ($data['section'] == 'category') {

        if ($data['_object']->get('Category Scope') == 'Product') {

            // print_r($data['_object']);

            if ($data['_object']->get('Category Branch Type') == 'Root') {

                unset($_content['tabs']['category.sales']);
            }

            if ($data['_object']->get('Category Subject') == 'Product') {
                $_content['tabs']['category.subjects']['label'] = _('Products');


                if ($data['_object']->get('Root Key') == $data['store']->get('Store Family Category Key')) {
                    $_content['tabs']['category.categories']['label'] = _('Families');


                    if ($data['store']->get('Store Family Category Key') == $data['_object']->id) {

                        $_content['tabs']['category.webpage']['class']    = 'hide';
                        $_content['tabs']['category.details']['class']    = 'hide';
                        $_content['tabs']['category.categories']['class'] = 'hide';

                        $_content['tabs']['category.categories']['selected'] = true;
                        $data['tab']                                         = 'category.categories';
                        $data['subtab']                                         = '';
                        $_content['subtabs']=array();

                    }


                }

            } else {
                if ($data['_object']->get('Root Key') == $data['store']->get('Store Department Category Key')) {
                    $_content['tabs']['category.subjects']['label']   = _('Families');
                    $_content['tabs']['category.categories']['label'] = _('Departments');


                    if ($data['store']->get('Store Department Category Key') == $data['_object']->id) {

                        $_content['tabs']['category.webpage']['class']    = 'hide';
                        $_content['tabs']['category.details']['class']    = 'hide';
                        $_content['tabs']['category.categories']['class'] = 'hide';

                        $_content['tabs']['category.categories']['selected'] = true;
                        $data['tab']                                         = 'category.categories';
                        $data['subtab']                                         = '';
                        $_content['subtabs']=array();

                    }


                } else {

                    $_content['tabs']['category.subjects']['label'] = _('Categories');
                }

            }
        } elseif ($data['_object']->get('Category Scope') == 'Part') {


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


    } elseif ($data['module'] == 'suppliers' and $data['section'] == 'order') {
        if ($data['_object']->get('Purchase Order State') == 'InProcess') {

            //$data['tab']='supplier.order.items';

            //print_r($data);

            //$_content['tabs']['supplier.order.delivery_notes']['class']='hide';

            //if (isset($_content['tabs']['supplier.order.delivery_notes']['selected']) and  $_content['tabs']['supplier.order.delivery_notes']['selected']) {
            // $_content['tabs']['supplier.order.delivery_notes']['selected']=false;
            // $_content['tabs']['supplier.order.details']['selected']=true;

            // $data['tab']='supplier.order.details';

            // }


        }
    }
   // print_r($_content['tabs']);
   // print_r($_content['subtabs']);

    $smarty->assign('_content', $_content);


    if ($data['section'] == 'warehouse') {
        if (!$user->can_view('locations') or !in_array(
                $data['key'], $user->warehouses
            )
        ) {
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
    //$branch=array(array('label'=>'<span >'._('Home').'</span>', 'icon'=>'home', 'reference'=>'/dashboard'));
    //print_r($state);
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
                    'icon'      => 'shopping-bag',
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

            if ($user->get_number_stores() > 1) {
                $branch[] = array(
                    'label'     => _('Stores'),
                    'icon'      => '',
                    'reference' => 'stores'
                );

            }
            if ($state['section'] == 'store') {

                $branch[]               = array(
                    'label'     => _('Store').' <span class="Store_Code id">'.$state['_object']->get(
                            'Store Code'
                        ).'</span>',
                    'icon'      => 'shopping-bag',
                    'reference' => 'store/'.$state['_object']->id
                );
                $state['current_store'] = $state['_object']->id;

            } elseif ($state['section'] == 'store.new') {
                $branch[] = array(
                    'label'     => _('New store'),
                    'icon'      => 'shopping-bag',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'dashboard') {
                $branch[] = array(
                    'label'     => _('Stores'),
                    'icon'      => '',
                    'reference' => 'stores'
                );

                $branch[]               = array(
                    'label'     => _(
                            "Store's dashboard"
                        ).' <span class="id">'.$state['_object']->get('Store Code').'</span>',
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
                            'label'     => _('Orders').' ('._(
                                    'All stores'
                                ).')',
                            'icon'      => 'bars',
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
                    'label'     => _(
                            'Products'
                        ).' <span class="id">'.$state['store']->get('Code').'</span>',
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
                            'label'     => _('Orders').' ('._(
                                    'All stores'
                                ).')',
                            'icon'      => 'bars',
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


            } elseif ($state['section'] == 'order') {


                $branch[] = array(
                    'label'     => _(
                            'Products'
                        ).' <span class="id">'.$state['_parent']->get('Store Code').'</span>',
                    'icon'      => '',
                    'reference' => 'products/'.$state['_parent']->get('Store Key')
                );


                $branch[] = array(
                    'label'     => '<span class=" Product_Code">'.$state['_parent']->get('Code').'</span>',
                    'icon'      => 'cube',
                    'reference' => 'product/'.$state['_parent']->id
                );
                $branch[] = array(
                    'label'     => '<span class="id ">'.$state['_object']->get('Order Public ID').'</span>',
                    'icon'      => 'shopping-cart',
                    'reference' => ''
                );

            }
            break;
        case 'customers_server':
            if ($state['section'] == 'customers') {
                $branch[] = array(
                    'label'     => _('Customers (All stores)'),
                    'icon'      => '',
                    'reference' => ''
                );
            }

            break;


        case 'customers':


            switch ($state['parent']) {
                case 'store':
                    $store                  = new Store($state['parent_key']);
                    $state['current_store'] = $store->id;

                    break;


            }

            if ($user->get_number_stores() > 1) {


                $branch[] = array(
                    'label'     => _('(All stores)'),
                    'icon'      => '',
                    'reference' => 'customers/all'
                );

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
                        'label'     => $list->get('List Name'),
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
                            'label'     => _(
                                    "Customer's lists"
                                ).' '.$store->data['Store Code'],
                            'icon'      => 'list',
                            'reference' => 'customers/'.$store->id.'/lists'
                        );
                        $branch[] = array(
                            'label'     => $list->get(
                                'List Name'
                            ),
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
                case 'dashboard':
                    $branch[] = array(
                        'label'     => _(
                                "Customer's dashboard"
                            ).' '.$store->data['Store Code'],
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
                case 'statistics':
                    $branch[] = array(
                        'label'     => _(
                                "Customer's stats"
                            ).' '.$store->data['Store Code'],
                        'icon'      => 'line-chart',
                        'reference' => 'customers/statistics/'.$store->id
                    );
                    break;
                case 'pending_orders':
                    $branch[] = array(
                        'label'     => _(
                                "Pending orders"
                            ).' '.$store->data['Store Code'],
                        'icon'      => 'clock-o',
                        'reference' => 'customers/pending_orders/'.$store->id
                    );
                    break;
            }
            break;
        case 'suppliers':
            if ($state['section'] == 'suppliers') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => 'ship',
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
                    'icon'      => 'ship',
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
                    'icon'      => 'ship',
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
                    'icon'      => 'ship',
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
                    'icon'      => 'ship',
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
                        'icon'      => 'ship',
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
                        'icon'      => 'ship',
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
                        'icon'      => 'ship',
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
                    'icon'      => 'ship',
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
                    'icon'      => 'ship',
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
                    'icon'      => 'ship',
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
                    'icon'      => 'ship',
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

            }


            break;
        case 'orders_server':
            $branch[] = array(
                'label'     => '',
                'icon'      => 'bars',
                'reference' => 'receipts'
            );

            if ($user->get_number_stores() > 1) {
                $branch[] = array(
                    'label'     => _('Orders').' ('._('All stores').')',
                    'icon'      => '',
                    'reference' => 'orders/all'
                );
            }
            break;
        case 'invoices_server':

            if ($state['section'] == 'categories') {
                $branch[] = array(
                    'label'     => _("Invoice's categories").' ('._(
                            'All stores'
                        ).')',
                    'icon'      => '',
                    'reference' => ''
                );

            }
            if ($state['section'] == 'category') {
                $branch[] = array(
                    'label'     => _("Invoice's categories").' ('._(
                            'All stores'
                        ).')',
                    'icon'      => '',
                    'reference' => ''
                );
                $branch[] = array(
                    'label'     => '<span class="Category_Code">'.$state['_object']->get('Code').'</span>',
                    'icon'      => 'sitemap',
                    'reference' => ''
                );

            } else {


                $branch[] = array(
                    'label'     => '',
                    'icon'      => 'bars',
                    'reference' => 'receipts'
                );

                if ($user->get_number_stores() > 1) {
                    $branch[] = array(
                        'label'     => _('Invoices').' ('._(
                                'All stores'
                            ).')',
                        'icon'      => '',
                        'reference' => ''
                    );
                }
            }
            break;
        case 'delivery_notes_server':
            $branch[] = array(
                'label'     => '',
                'icon'      => 'bars',
                'reference' => 'receipts'
            );

            if ($user->get_number_stores() > 1) {
                $branch[] = array(
                    'label'     => _('Delivery Notes').' ('._(
                            'All stores'
                        ).')',
                    'icon'      => '',
                    'reference' => 'delivery_notes/all'
                );
            }
            break;
        case 'orders':
            $branch[] = array(
                'label'     => '',
                'icon'      => 'bars',
                'reference' => 'receipts'
            );
            switch ($state['section']) {

                case 'dashboard':

                    $branch[] = array(
                        'label'     => _("Orders").' '.$state['store']->data['Store Code'],
                        'icon'      => 'tachometer',
                        'reference' => ''
                    );

                    break;


                case 'basket_orders':

                    $branch[] = array(
                        'label'     => _('Orders in website').' '.$state['store']->data['Store Code'],
                        'icon'      => 'globe',
                        'reference' => ''
                    );


                    break;

                case 'pending_orders':

                    $branch[] = array(
                        'label'     => _('Pending orders').' '.$state['store']->data['Store Code'],
                        'icon'      => 'shopping-cart',
                        'reference' => ''
                    );


                    break;

                case 'orders':


                    $branch[] = array(
                        'label'     => _('Orders (Archive)').' '.$state['store']->data['Store Code'],
                        'icon'      => 'archive',
                        'reference' => ''
                    );


                    break;

                case 'payments':
                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label' => _('Payments').' ('._(
                                    'All stores'
                                ).')',
                            'icon'  => 'bars',
                            'url'   => 'payments/all'
                        );
                    }
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
                                    'icon'      => 'bars',
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


                        switch ($state['_object']->get('Order Class')) {
                            case 'Archived':
                                $branch[] = array(
                                    'label'     => _('Orders (Archive)').' '.$state['store']->data['Store Code'],
                                    'icon'      => 'archive',
                                    'reference' => 'orders/'.$state['store']->id
                                );
                                break;
                            case 'InProcess':
                                $branch[] = array(
                                    'label'     => _('Pending orders').' '.$state['store']->data['Store Code'],
                                    'icon'      => 'shopping-cart',
                                    'reference' => 'orders/'.$state['store']->id.'/flow'
                                );
                                break;
                            case 'InWebsite':
                                $branch[] = array(
                                    'label'     => _('Orders in website').' '.$state['store']->data['Store Code'],
                                    'icon'      => 'globe',
                                    'reference' => 'orders/'.$state['store']->id.'/website'
                                );
                                break;
                            default:
                                exit("Error order don't have class");
                                break;
                        }


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

                case 'invoice':

                    $store = new Store(
                        $state['_object']->data['Invoice Store Key']
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
                            'Invoice Public ID'
                        ),
                        'icon'      => 'file-text-o',
                        'reference' => ''
                    );
                    break;

            }

            break;
        case 'delivery_notes':
            $branch[] = array(
                'label'     => '',
                'icon'      => 'bars',
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
                        'icon'      => 'file-text-o',
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
        case 'invoices':
            $branch[] = array(
                'label'     => '',
                'icon'      => 'bars',
                'reference' => 'receipts'
            );

            switch ($state['section']) {

                case 'invoices':
                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label' => '('._('All stores').')',
                            'icon'  => '',
                            'url'   => 'invoices/all'
                        );
                    }
                    $store = new Store($state['parent_key']);

                    $branch[] = array(
                        'label'     => _('Invoices').' '.$store->data['Store Code'],
                        'icon'      => '',
                        'reference' => 'invoices/'.$store->id
                    );


                    break;

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
                        'icon'      => 'file-text-o',
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
                        'icon'      => 'file-text-o',
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
                        'icon'      => 'file-text-o',
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
                        'icon'      => 'hand-spock-o',
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
                        'icon'      => 'hand-rock-o',
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
                        'label'     => '<span class="id Staff_Alias">'.$state['_object']->get('Staff Alias').'</span> <i class="fa fa-trash-o padding_left_5" aria-hidden="true"></i> ',
                        'icon'      => 'hand-rock-o',
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
                        'icon'      => 'hand-rock-o',
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
                        'icon'      => 'hand-spock-o',
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
                        'icon'      => 'hand-spock-o',
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
                        'icon'      => 'hand-rock-o',
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
                        'label'     => '<span class="id Staff_Alias">'.$state['_object']->get('Staff Alias').'</span> <i class="fa fa-trash-o padding_left_5" aria-hidden="true"></i> ',
                        'icon'      => 'hand-spock-o',
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
                        'icon'  => 'hand-spock-o'
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
                        'icon'      => 'hand-rock-o',
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
                                "Parts's categories"
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
                        'icon'      => 'square',
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
                        'icon'      => 'square',
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
                        'icon'      => 'square',
                        'reference' => ''
                    );

                    break;


                case 'supplier_part.new':


                    if ($state['parent'] == 'category') {
                        $category = $state['_parent'];
                        $branch[] = array(
                            'label'     => _(
                                "Parts's categories"
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
                        'icon'      => 'square',
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
                        'icon'      => 'square',
                        'reference' => 'part/'.$state['parent_key']
                    );


                    $branch[] = array(
                        'label'     => '<span class="id Product_Code">'.$state['_object']->get('Code').'</span>',
                        'icon'      => 'cube',
                        'reference' => 'products/'.$state['_object']->get('Product Store Key').'/'.$state['_object']->id
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
                        'label'     => _("Parts's categories"),
                        'icon'      => 'sitemap',
                        'reference' => ''
                    );
                    break;
                case 'category':
                    $category = $state['_object'];
                    $branch[] = array(
                        'label'     => _("Parts's categories"),
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
                        'label'     => _("Parts's categories"),
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
                                "Parts's categories"
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
                        'icon'      => 'square',
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
                        'icon'      => 'square',
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
                'icon'      => 'map',
                'reference' => ''
            );


            break;

        case 'warehouses':


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
                        'label'     => '('._('All warehouses').')',
                        'icon'      => '',
                        'reference' => 'warehouses'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'map',
                        'reference' => ''
                    );
                    break;
                case 'locations':
                    $branch[] = array(
                        'label'     => '('._('All warehouses').')',
                        'icon'      => '',
                        'reference' => 'warehouses'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'map',
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
                        'label'     => '('._('All warehouses').')',
                        'icon'      => '',
                        'reference' => 'warehouses'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'map',
                        'reference' => 'warehouse/'.$state['parent_key']
                    );
                    $branch[] = array(
                        'label'     => _('Pending delivery notes'),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;

                case 'location':

                    $branch[] = array(
                        'label'     => '('._('All warehouses').')',
                        'icon'      => '',
                        'reference' => 'warehouses'
                    );
                    $branch[] = array(
                        'label'     => '<span class=" Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'map',
                        'reference' => 'warehouse/'.$state['parent_key']
                    );
                    $branch[] = array(
                        'label'     => _('Locations'),
                        'icon'      => '',
                        'reference' => 'warehouse/'.$state['parent_key'].'/locations'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Location_Code">'.$state['_object']->get('Code').'</span>',
                        'icon'      => 'map-marker',
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
                        'label'     => _(
                            "Locations's categories"
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

                    break;
                case 'warehouse.new':

                    $branch[] = array(
                        'label'     => '('._('All warehouses').')',
                        'icon'      => '',
                        'reference' => 'warehouses'
                    );

                    $branch[] = array(
                        'label'     => _('New warehouse'),
                        'icon'      => 'map',
                        'reference' => ''
                    );

                    break;
                case 'part':
                    if ($user->get_number_warehouses() > 1 or $user->can_create(
                            'warehouses'
                        )
                    ) {

                        $branch[] = array(
                            'label'     => '('._('All warehouses').')',
                            'icon'      => '',
                            'reference' => 'inventory/all'
                        );

                    }
                    $branch[] = array(
                        'label'     => _('Inventory').' <span class="id">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'th-large',
                        'reference' => 'inventory/'.$state['warehouse']->id
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Part_Reference">'.$state['_object']->get('Reference').'</span> (<span class="id">'.$state['_object']->get(
                                'SKU'
                            ).'</span>)',
                        'icon'      => 'square',
                        'reference' => ''
                    );

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


            if ($user->get_number_websites() > 1) {

                $branch[] = array(
                    'label'     => '('._('All websites').')',
                    'icon'      => '',
                    'reference' => 'websites/all'
                );

            }
            switch ($state['section']) {
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
                        'label'     => '<span class="id Website_Code">'.$state['website']->get('Code').'</span> <i class="fa fa-files-o" aria-hidden="true"></i>',
                        'icon'      => 'globe',
                        'reference' => 'website/'.$state['website']->id
                    );


                    break;
                case 'website.node':


                    if ($state['parent'] == 'node') {
                        $branch[] = array(
                            'label'     => $state['website']->get(
                                'Code'
                            ),
                            'icon'      => 'sitemap',
                            'reference' => 'website/'.$state['website']->id
                        );

                        if ($state['_object']->get('Website Node Parent Key') != $state['_object']->id) {
                            $node_branches = array();
                            $node_branches = create_node_breadcrumbs(
                                $db, $state['_object']->get(
                                'Website Node Parent Key'
                            ), $node_branches
                            );
                            $branch        = array_merge(
                                $branch, $node_branches
                            );
                        }

                    } else {
                        $branch[] = array(
                            'label'     => $state['website']->get(
                                'Code'
                            ),
                            'icon'      => 'globe',
                            'reference' => 'website/'.$state['website']->id
                        );

                    }

                    $branch[] = array(
                        'label'     => '<span class="id Webpage_Name">'.$state['_object']->webpage->get('Name').'</span>',
                        'icon'      => ($state['_object']->get(
                            'Website Node Icon'
                        ) == ''
                            ? 'file-o'
                            : $state['_object']->get(
                                'Website Node Icon'
                            )),
                        'reference' => ''
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
                        $website = new Site($state['parent_key']);
                    } elseif ($state['parent'] == 'page') {
                        $page = new Page($state['parent_key']);

                        $website = new Site($page->get('Page Site Key'));

                    }

                    $branch[] = array(
                        'label'     => _('Website').' '.$website->data['Site Code'],
                        'icon'      => 'globe',
                        'reference' => 'website/'.$website->id
                    );

                    if ($state['parent'] == 'page') {

                        $branch[] = array(
                            'label'     => _('Page').' '.$page->data['Page Code'],
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
        case 'payments':

            if ($state['section'] == 'payment_account') {


                /*

			include_once 'class.Payment_Service_Provider.php';

			$psp=new Payment_Service_Provider($state['_object']->get('Payment Service Provider Key'));

			$branch[]=array('label'=>_('Payment service provider').'  <span id="id">'.$psp->get('Payment Service Provider Code').'</span>', 'icon'=>'', 'reference'=>'account/payment_service_provider/'.$psp->id);

			$branch[]=array('label'=>_('Payment account').'  <span id="id">'.$state['_object']->get('Payment Account Code').'</span>', 'icon'=>'', 'reference'=>'account/payment_service_provider/'.$state['_object']->id);
*/


                if ($state['parent'] == 'account') {
                    $branch[] = array(
                        'label'     => '('._('All stores').')',
                        'icon'      => 'cc',
                        'reference' => 'payment_accounts/all'
                    );

                }

                $branch[] = array(
                    'label'     => _('Payment account').'  <span id="id">'.$state['_object']->get(
                            'Payment Account Code'
                        ).'</span>',
                    'icon'      => '',
                    'reference' => 'account/payment_service_provider/'.$state['_object']->id
                );


            } elseif ($state['section'] == 'payment_accounts') {
                if ($state['parent'] == 'store') {
                    $store = new Store($state['parent_key']);
                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'payment_accounts/all'
                        );
                    }

                    $branch[] = array(
                        'label'     => _('Payment accounts').'  <span id="id">('.$store->get('Code').')</span>',
                        'icon'      => '',
                        'reference' => 'payment_accounts/'.$store->id
                    );
                } elseif ($state['parent'] == 'account') {

                    $branch[] = array(
                        'label'     => _('Payment accounts').' ('._(
                                'All stores'
                            ).')',
                        'icon'      => '',
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


            } elseif ($state['section'] == 'payment') {

                if ($state['parent'] == 'account') {

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


                    $branch[] = array(
                        'label'     => _('Payment').'  <span id="id">'.$state['_object']->get('Payment Key').'</span>',
                        'icon'      => '',
                        'reference' => ''
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

                    $branch[] = array(
                        'label'     => _('Payment').'  <span id="id">'.$state['_object']->get(
                                'Payment Key'
                            ).'</span>',
                        'icon'      => '',
                        'reference' => 'payment_account/'.$payment_account->id.'/payment/'.$state['_object']->id
                    );

                }


            }


            break;
        case 'account':


            if ($state['section'] == 'orders_index') {
                $branch[] = array(
                    'label'     => _("Order's index"),
                    'icon'      => 'bars',
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
                    'reference' => 'account/users'
                );

            } elseif ($state['section'] == 'staff') {
                $branch[] = array(
                    'label'     => _('Users'),
                    'icon'      => '',
                    'reference' => 'account/users'
                );
                $branch[] = array(
                    'label'     => _('Employees'),
                    'icon'      => 'terminal',
                    'reference' => 'account/users/staff'
                );
            } elseif ($state['section'] == 'contractors') {
                $branch[] = array(
                    'label'     => _('Users'),
                    'icon'      => '',
                    'reference' => 'account/users'
                );
                $branch[] = array(
                    'label'     => _('Contractors'),
                    'icon'      => 'terminal',
                    'reference' => 'account/users/contractors'
                );
            } elseif ($state['section'] == 'suppliers') {
                $branch[] = array(
                    'label'     => _('Users'),
                    'icon'      => '',
                    'reference' => 'account/users'
                );
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => 'terminal',
                    'reference' => 'account/users/suppliers'
                );
            } elseif ($state['section'] == 'agents') {
                $branch[] = array(
                    'label'     => _('Users'),
                    'icon'      => '',
                    'reference' => 'account/users'
                );
                $branch[] = array(
                    'label'     => _('Agents'),
                    'icon'      => 'terminal',
                    'reference' => 'account/users/agents'
                );
            } elseif ($state['section'] == 'user') {
                $branch[] = array(
                    'label'     => _('Users'),
                    'icon'      => '',
                    'reference' => 'account/users'
                );


                switch ($state['_object']->get('User Type')) {
                    case 'Staff':
                        $branch[] = array(
                            'label'     => _('Employees'),
                            'icon'      => '',
                            'reference' => 'account/users/staff'
                        );
                        break;
                    case 'Contractor':
                        $branch[] = array(
                            'label'     => _('Contractors'),
                            'icon'      => '',
                            'reference' => 'account/users/contractors'
                        );
                        break;
                    case 'Agent':
                        $branch[] = array(
                            'label'     => _('Agents'),
                            'icon'      => '',
                            'reference' => 'account/users/agents'
                        );
                        break;
                    case 'Suppliers':
                        $branch[] = array(
                            'label'     => _('Suppliers'),
                            'icon'      => '',
                            'reference' => 'account/users/suppliers'
                        );
                        break;
                    default:

                        break;
                }


                $branch[] = array(
                    'label'     => '<span id="id">'.$state['_object']->get('User Handle').'</span>',
                    'icon'      => 'terminal',
                    'reference' => 'account/user/'.$state['_object']->id
                );

            } elseif ($state['section'] == 'deleted.user') {
                $branch[] = array(
                    'label'     => _('Deteted users'),
                    'icon'      => '',
                    'reference' => 'account/deleted_users'
                );


                $branch[] = array(
                    'label'     => '<span id="id">'.$state['_object']->get('User Handle').'</span>  <i class="fa fa-trash-o padding_left_5" aria-hidden="true"></i> ',
                    'icon'      => 'terminal',
                    'reference' => 'account/user/'.$state['_object']->id
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
                        'icon'      => 'ship',
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
                    'icon'      => 'ship',
                    'reference' => ''
                );

            }

            break;

        case 'production':

            $branch[] = array(
                'label'     => _("(All manufactures)"),
                'icon'      => '',
                'reference' => 'production/all'
            );

            $branch[] = array(
                'label'     => _("Production").' <span class="id Supplier_Code">'.$state['_object']->get(
                        'Code'
                    ).'</span>',
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
            $branch[] = array(
                'label'     => _('(All stores)'),
                'icon'      => 'bullhorn',
                'reference' => 'marketing/all'
            );

            if ($state['section'] == 'deals') {

                $branch[] = array(
                    'label'     => _('Deals').' '.$state['store']->get('Code'),
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
            }
            break;


        case 'agent_suppliers':
            if ($state['section'] == 'suppliers') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => 'ship',
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
                    'icon'      => 'ship',
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
                    'icon'      => 'ship',
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
                    'icon'      => 'ship',
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
                    'icon'      => 'ship',
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
                        'icon'      => 'ship',
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
                        'icon'      => 'ship',
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
                    'icon'      => 'ship',
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
                    'icon'      => 'ship',
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
                    'icon'      => 'ship',
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


function create_node_breadcrumbs($db, $node_key, $branch) {

    include_once 'utils/text_functions.php';

    $sql = sprintf(
        'SELECT `Website Node Parent Key`,`Website Node Key`,`Website Node Website Key`,`Webpage Code`,`Webpage Name`,`Website Node Icon` FROM `Website Node Dimension` LEFT JOIN `Webpage Dimension` ON (`Website Node Webpage Key`=`Webpage Key`)  WHERE `Website Node Key`=%d',
        $node_key
    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {


            array_unshift(
                $branch, array(
                           'label'     => trimStringToFullWord(
                               16, $row['Webpage Name']
                           ),
                           'icon'      => ($row['Website Node Icon'] == '' ? 'file-o' : $row['Website Node Icon']),
                           'reference' => 'website/'.$row['Website Node Website Key'].'/node/'.$row['Website Node Key']
                       )
            );
            if ($row['Website Node Parent Key'] == $node_key) {
                return $branch;
            } else {

                $branch = create_node_breadcrumbs(
                    $db, $row['Website Node Parent Key'], $branch
                );

                return $branch;
            }
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print $sql;
        exit;
    }

}


?>
