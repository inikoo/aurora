<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 November 2016 at 13:58:38 GMT+8, Cyberjaya. Malaysia

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
    case 'menu':

        $data = array(
            'section' => '',
            'module'  => '',
            'object'  => ''

        );
        include_once 'navigation/menu.mobile.php';
        $html     = get_mobile_menu($data, $user, $smarty);
        $response = array(
            'state' => 200,
            'menu'  => $html
        );
        echo json_encode($response);

        break;
    case 'desktop_view':

        $_SESSION['device']='desktop';
        $response = array(
            'state' => 200
        );
        break;

    default:
        $response = array(
            'state' => 404,
            'resp'  => 'Operation not found 2'
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
            $_parent                  = get_object(
                'Website', $state['parent_key']
            );
            $website                  = $_parent;
            $state['current_website'] = $_parent->id;
            $website                  = $_parent;

            break;
        case 'node':
            $_parent                  = get_object(
                'WebsiteNode', $state['parent_key']
            );
            $website                  = get_object(
                'Website', $_parent->get('Website Node Website Key')
            );
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

        if (!isset($_object)) {
            $_object = get_object($state['object'], $state['key']);
        }


        if (is_numeric($_object->get('Store Key'))) {
            include_once 'class.Store.php';
            $store                  = new Store($_object->get('Store Key'));
            $state['current_store'] = $store->id;
        }
        if (is_numeric($_object->get('Warehouse Key'))) {
            include_once 'class.Warehouse.php';
            $warehouse                  = new Warehouse(
                $_object->get('Warehouse Key')
            );
            $state['current_warehouse'] = $warehouse->id;
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


    $state['metadata'] = (isset($data['metadata']) ? $data['metadata'] : array());


    $response['content'] = get_mobile_content($db, $smarty, $user, $account, $state, $data['metadata']);
    $response['title']   = get_title($state);


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


    // print_r($response);

    echo json_encode($response);

}


function get_title($state) {

    $title = '';


    switch ($state['module']) {
        case 'inventory':
            $title = '<i class="fa fa-th-large padding_right_5" aria-hidden="true"></i> '._('Inventory');
            break;
        default:
            break;
    }

    return $title;
}


function get_mobile_content($db, $smarty, $user, $account, $state = false, $metadata = false) {


    //  print_r($state);


    $actual_tab = $state['tab'];


    $smarty->assign('data', $state);

    $is_mobile = true;


    if ($state['section'] == 'inventory') {

        $actual_tab = 'inventory.mobile';
    }


    if (file_exists('tabs/'.$actual_tab.'.tab.php')) {
        include_once 'tabs/'.$actual_tab.'.tab.php';
    } else {
        $html = 'Tab Not found: >'.$actual_tab.'<';

    }


    return $html;

}


?>
