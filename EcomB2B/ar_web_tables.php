<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 July 2017 at 00:35:25 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';

require_once __DIR__.'/utils/get_addressing.php';


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


    case 'portfolio':
        $data = prepare_values(
            $_REQUEST, array(
                         'device_prefix' => array(
                             'type'     => 'string',
                             'optional' => true
                         )
                     )
        );

        get_portfolio_table_html($data, $customer);
        break;
    case 'clients':
        $data = prepare_values(
            $_REQUEST, array(
                         'device_prefix' => array(
                             'type'     => 'string',
                             'optional' => true
                         )
                     )
        );

        get_clients_table_html($data, $customer);
        break;
    case 'choose_client_for_order':
        $data = prepare_values(
            $_REQUEST, array(
                         'device_prefix' => array(
                             'type'     => 'string',
                             'optional' => true
                         )
                     )
        );

        get_choose_client_for_order_table_html($data, $customer);
        break;
    case 'client_orders':
        $data       = prepare_values(
            $_REQUEST, array(
                         'client_id'     => array('type' => 'keys'),
                         'device_prefix' => array(
                             'type'     => 'string',
                             'optional' => true
                         )
                     )
        );
        $parameters = array(
            'parent'     => 'client',
            'parent_key' => $data['client_id'],

        );
        get_orders_table_html($data, $parameters, $db);
        break;
    case 'clients_orders':
        $data = prepare_values(
            $_REQUEST, array(
                         'device_prefix' => array(
                             'type'     => 'string',
                             'optional' => true
                         )
                     )
        );

        $parameters = array(
            'parent'     => 'customer',
            'parent_key' => $customer->id,

        );
        get_orders_table_html($data, $parameters, $customer, $db);
        break;
    case 'order_items':
        $data = prepare_values(
            $_REQUEST, array(
                         'order_key'     => array('type' => 'keys'),
                         'device_prefix' => array(
                             'type'     => 'string',
                             'optional' => true
                         )
                     )
        );

        $order = get_object('Order', $data['order_key']);

        if ($order->get('Order Customer Key') != $customer->id) {
            $response = array(
                'state' => 400,
                'resp'  => 'Wrong order key'
            );
            echo json_encode($response);
            exit;
        }

        $parameters = array(
            'parent'     => 'order',
            'parent_key' => $order->id,

        );
        get_order_items_table_html($data, $parameters, $customer, $db);
        break;
    case 'catalogue':
        $data = prepare_values(
            $_REQUEST, array(
                         'parent'        => array('type' => 'string'),
                         'parent_key'    => array('type' => 'string'),
                         'scope'         => array('type' => 'string'),
                         'device_prefix' => array(
                             'type'     => 'string',
                             'optional' => true
                         )
                     )
        );
        get_catalogue_table_html($data, $customer,$website);
        break;
    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;

}

function get_order_items_table_html($data, $parameters, $customer, $db) {


    if (!isset($data['device_prefix'])) {
        $device_prefix = '';
    } else {
        $device_prefix = $data['device_prefix'];

    }

    include_once '../conf/export_fields.php';
    include_once '../conf/elements_options.php';


    $smarty = new Smarty();
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir('server_files/smarty/templates_c');
    $smarty->setCacheDir('server_files/smarty/cache');
    $smarty->setConfigDir('server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');


    $website  = get_object('Website', $_SESSION['website_key']);
    $store    = get_object('Store', $website->get('Website Store Key'));
    $web_user = get_object('website_user', $customer->get('Customer Website User Key'));


    switch ($data['scope']) {
        case 'departments':
            $tab     = 'departments';
            $ar_file = 'ar_web_catalogue.php';
            $tipo    = 'departments';


            $default     = array(
                'view'        => 'overview',
                'sort_key'    => 'label',
                'sort_order'  => 0,
                'rpp'         => 100,
                'rpp_options' => [
                    500,
                    100
                ],
                'f_field'     => 'name',

            );
            $table_views = array(
                'overview' => array('label' => _('Overview')),


            );

            $table_filters = array(

                'name' => array(
                    'label' => _('Name'),
                    'title' => _('Department name')
                ),

            );

            $parameters = array(
                'parent'     => 'store',
                'parent_key' => $website->get('Website Store Key')

            );
            break;
        case 'families':
            $tab     = 'families';
            $ar_file = 'ar_web_catalogue.php';
            $tipo    = 'families';


            $default     = array(
                'view'        => 'overview',
                'sort_key'    => 'code',
                'sort_order'  => 0,
                'rpp'         => 100,
                'rpp_options' => [
                    500,
                    100
                ],
                'f_field'     => 'code',


            );
            $table_views = array(
                'overview' => array('label' => _('Overview')),


            );

            $table_filters = array(
                'code' => array(
                    'label' => _('Code'),
                    'title' => _('Family code')
                ),
                'name' => array(
                    'label' => _('Name'),
                    'title' => _('Family name')
                ),

            );

            $parameters = array(
                'parent'     => $data['parent'],
                'parent_key' => $data['parent_key'],

            );
            break;
        case 'products':
            $tab     = 'products';
            $ar_file = 'ar_web_catalogue.php';
            $tipo    = 'products';


            $default     = array(
                'view'        => 'overview',
                'sort_key'    => 'code',
                'sort_order'  => 1,
                'rpp'         => 100,
                'rpp_options' => [
                    500,
                    100
                ],
                'f_field'     => 'code',

            );
            $table_views = array(
                'overview' => array('label' => _('Overview')),


            );

            $table_filters = array(
                'code' => array(
                    'label' => _('Code'),
                    'title' => _('Product code')
                ),
                'name' => array(
                    'label' => _('Name'),
                    'title' => _('Product name')
                ),

            );


            $parameters = array(
                'parent'     => $data['parent'],
                'parent_key' => $data['parent_key'],

            );
            break;
    }


    $table_buttons = array();


    $smarty->assign('table_buttons', $table_buttons);


    include 'utils/get_table_html.php';

    $state = [
        'tab' => ''
    ];


    $response = array(
        'state'     => 200,
        'app_state' => $state,
        'html'      => $html,


    );
    echo json_encode($response);


}

function get_portfolio_table_html($data, $customer) {


    if (!isset($data['device_prefix'])) {
        $device_prefix = '';
    } else {
        $device_prefix = $data['device_prefix'];

    }

    include_once '../conf/export_fields.php';
    include_once '../conf/elements_options.php';


    $smarty = new Smarty();
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir('server_files/smarty/templates_c');
    $smarty->setCacheDir('server_files/smarty/cache');
    $smarty->setConfigDir('server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');


    $website  = get_object('Website', $_SESSION['website_key']);
    $store    = get_object('Store', $website->get('Website Store Key'));
    $web_user = get_object('website_user', $customer->get('Customer Website User Key'));


    if ($device_prefix == 'mobile') {
        $tab = 'portfolio_mobile';
    } else {
        $tab = 'portfolio';
    }


    $ar_file = 'ar_web_portfolio.php';
    $tipo    = 'portfolio_items';


 //   print_r( get_elements_option('customer_portfolio'));
    $default = array(
        'view'          => 'overview',
        'sort_key'      => 'code',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => [
            500,
            100
        ],
        'f_field'       => 'code',
        //    'f_period'      => 'ytd',
        'elements_type' => array_keys(get_elements_option('customer_portfolio'))[0],
        'elements'      => get_elements_option('customer_portfolio'),

    );

    //if($device_prefix==''){
    //    $default['export_fields']=get_export_fields('portfolio_items');
    //}


    $table_views = array(
        'overview' => array('label' => _('Overview')),
        //    'performance' => array('label' => _('Performance')),
        //   'sales'       => array('label' => _('Sales')),
        //   'sales_y'     => array('label' => _('Invoiced amount (Yrs)')),
        //   'sales_q'     => array('label' => _('Invoiced amount (Qs)')),

    );

    $table_filters = array(
        'code' => array(
            'label' => _('Code'),
            'title' => _('Product code')
        ),
        'name' => array(
            'label' => _('Name'),
            'title' => _('Product name')
        ),

    );

    $parameters = array(
        'parent'     => 'customer',
        'parent_key' => $customer->id,

    );


    $table_buttons = array();
    if ($device_prefix == '') {
        $table_buttons[] = array(
            'icon'                  => 'plus',
            'title'                 => _("Add product to portfolio"),
            'id'                    => 'add_to_portfolio',
            'class'                 => 'items_operation',
            'add_item_to_portfolio' => array(

                'field_label' => _("Product").':',
                'ar_url'      => '/ar_web_portfolio.php',
                'metadata'    => base64_encode(
                    json_encode(
                        array(


                            'scope'      => 'product',
                            'parent'     => 'Store',
                            'parent_key' => $store->id,
                            'options'    => array('for_order'),
                        )
                    )
                )

            )

        );
    }

    $smarty->assign('web_user', $web_user);


    $smarty->assign('table_buttons', $table_buttons);


    include 'utils/get_table_html.php';

    $state = [
        'tab' => ''
    ];


    $response = array(
        'state'          => 200,
        'app_state'      => $state,
        'html'           => $html,
        'images_zip_url' => 'data_feed.php?uid='.$web_user->id.'&token='.$web_user->get('Website User Static API Hash').'&output=images&scope=portfolio_images',
        'csv_url'        => 'data_feed.php?uid='.$web_user->id.'&token='.$web_user->get('Website User Static API Hash').'&output=CSV&scope=portfolio_items',
        'xls_url'        => 'data_feed.php?uid='.$web_user->id.'&token='.$web_user->get('Website User Static API Hash').'&output=XLS&scope=portfolio_items',
        'json_url'       => 'data_feed.php?uid='.$web_user->id.'&token='.$web_user->get('Website User Static API Hash').'&output=Json&scope=portfolio_items',


    );
    echo json_encode($response);

}

function get_clients_table_html($data, $customer) {

    if (!isset($data['device_prefix'])) {
        $device_prefix = '';
    } else {
        $device_prefix = $data['device_prefix'];

    }

    include_once '../conf/export_fields.php';
    include_once '../conf/elements_options.php';


    $smarty = new Smarty();
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir('server_files/smarty/templates_c');
    $smarty->setCacheDir('server_files/smarty/cache');
    $smarty->setConfigDir('server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');


    $website = get_object('Website', $_SESSION['website_key']);
    $labels  = $website->get('Localised Labels');

    $store = get_object('Store', $website->get('Website Store Key'));


    $ar_file = 'ar_web_clients.php';
    $tipo    = 'customer_clients';


    if ($device_prefix == 'mobile') {
        $tab = 'customer_clients_mobile';
    } else {
        $tab = 'customer_clients';
    }


    $default = array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => [
            500,
            100
        ],
        'f_field'     => 'name',
        //    'f_period'      => 'ytd',
        //'elements_type' => array_keys(get_elements_option('customer_portfolio'))[0],
        //'elements'      => get_elements_option('customer_portfolio'),
        //'export_fields' => get_export_fields('products_public')

    );

    $table_views = array(
        'overview' => array('label' => _('Overview')),
        //    'performance' => array('label' => _('Performance')),
        //   'sales'       => array('label' => _('Sales')),
        //   'sales_y'     => array('label' => _('Invoiced amount (Yrs)')),
        //   'sales_q'     => array('label' => _('Invoiced amount (Qs)')),

    );

    $table_filters = array(
        'code'  => array(
            'label' => _('Reference'),
            'title' => _("Customer's client reference")
        ),
        'name'  => array(
            'label' => _('Name'),
            'title' => _("Customer's client name")
        ),
        'email' => array(
            'label' => _('Email'),
            'title' => _("Customer's client email")
        ),


    );

    $parameters = array(
        'parent'     => 'customer',
        'parent_key' => $customer->id,

    );


    $table_buttons = array();

    $table_buttons[] = array(
        'icon'  => 'plus',
        'title' => (empty($labels['_add_customer_client']) ? _('Add customer') : $labels['_add_customer_client']),
        'label' => (empty($labels['_add_customer_client']) ? _('Add customer') : $labels['_add_customer_client']),
        'id'    => 'new_customer',
        'class' => 'text width_auto',


    );

    $smarty->assign('table_buttons', $table_buttons);


    include 'utils/get_table_html.php';

    $state = [
        'tab' => ''
    ];

    $response = array(
        'state'     => 200,
        'app_state' => $state,
        'html'      => $html
    );
    echo json_encode($response);

}

function get_orders_table_html($data, $parameters, $db) {


    include_once '../conf/export_fields.php';
    include_once '../conf/elements_options.php';


    $smarty = new Smarty();
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir('server_files/smarty/templates_c');
    $smarty->setCacheDir('server_files/smarty/cache');
    $smarty->setConfigDir('server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');


    $website = get_object('Website', $_SESSION['website_key']);
    $labels  = $website->get('Localised Labels');

    $store = get_object('Store', $website->get('Website Store Key'));

    $tab     = 'clients_orders';
    $ar_file = 'ar_web_clients_orders.php';
    $tipo    = 'clients_orders';


    $default = array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => [
            500,
            100
        ],
        'f_field'     => 'public_id',
        //    'f_period'      => 'ytd',
        //'elements_type' => array_keys(get_elements_option('customer_portfolio'))[0],
        //'elements'      => get_elements_option('customer_portfolio'),
        //'export_fields' => get_export_fields('products_public')

    );

    $table_views = array(
        'overview' => array('label' => _('Overview')),
        //    'performance' => array('label' => _('Performance')),
        //   'sales'       => array('label' => _('Sales')),
        //   'sales_y'     => array('label' => _('Invoiced amount (Yrs)')),
        //   'sales_q'     => array('label' => _('Invoiced amount (Qs)')),

    );

    $table_filters = array(
        'public_id' => array(
            'label' => _('Number'),
            'title' => _('Order number')
        ),


    );

    $table_buttons = [];


    if ($parameters['parent'] == 'client') {


        $sql = "SELECT `Order Key` FROM `Order Dimension` WHERE `Order Customer Client Key`=? AND `Order State`='InBasket' ";

        $stmt = $db->prepare($sql);
        $stmt->execute(
            [
                $parameters['parent_key']
            ]
        );
        if ($row = $stmt->fetch()) {
            $order_key = $row['Order Key'];
        } else {
            $order_key = false;
        }

        if (!$order_key) {
            $table_buttons[] = array(
                'icon'  => 'plus',
                'title' => (empty($labels['_new_client_order']) ? _('New order') : $labels['_new_client_order']),
                'label' => (empty($labels['_new_client_order']) ? _('New order') : $labels['_new_client_order']),
                'id'    => 'new_order',
                'class' => 'text width_auto',


            );
        }

    } else {

        $table_buttons[] = array(
            'icon'  => 'plus',
            'title' => (empty($labels['_new_client_order']) ? _('New order') : $labels['_new_client_order']),
            'label' => (empty($labels['_new_client_order']) ? _('New order') : $labels['_new_client_order']),
            'id'    => 'client_order_new',
            'class' => 'text width_auto',


        );

    }

    $smarty->assign('table_buttons', $table_buttons);
    $smarty->assign('parameters', $parameters);


    include 'utils/get_table_html.php';

    $state = [
        'tab' => ''
    ];

    $response = array(
        'state'     => 200,
        'app_state' => $state,
        'html'      => $html
    );
    echo json_encode($response);

}

function get_choose_client_for_order_table_html($data, $customer) {


    include_once '../conf/export_fields.php';
    include_once '../conf/elements_options.php';


    $smarty = new Smarty();
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir('server_files/smarty/templates_c');
    $smarty->setCacheDir('server_files/smarty/cache');
    $smarty->setConfigDir('server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');


    $website = get_object('Website', $_SESSION['website_key']);
    $labels  = $website->get('Localised Labels');

    $store = get_object('Store', $website->get('Website Store Key'));


    $tab     = 'choose_client_for_order';
    $ar_file = 'ar_web_clients.php';
    $tipo    = 'customer_clients';


    $default = array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => [
            500,
            100
        ],
        'f_field'     => 'name',
        //    'f_period'      => 'ytd',
        //'elements_type' => array_keys(get_elements_option('customer_portfolio'))[0],
        //'elements'      => get_elements_option('customer_portfolio'),
        //'export_fields' => get_export_fields('products_public')

    );

    $table_views = array(
        'overview' => array('label' => _('Overview')),
        //    'performance' => array('label' => _('Performance')),
        //   'sales'       => array('label' => _('Sales')),
        //   'sales_y'     => array('label' => _('Invoiced amount (Yrs)')),
        //   'sales_q'     => array('label' => _('Invoiced amount (Qs)')),

    );

    $table_filters = array(
        'code'  => array(
            'label' => _('Reference'),
            'title' => _("Customer's client reference")
        ),
        'name'  => array(
            'label' => _('Name'),
            'title' => _("Customer's client name")
        ),
        'email' => array(
            'label' => _('Email'),
            'title' => _("Customer's client email")
        ),


    );

    $parameters = array(
        'parent'     => 'customer',
        'parent_key' => $customer->id,

    );


    $table_buttons = array();


    $smarty->assign('table_buttons', $table_buttons);


    include 'utils/get_table_html.php';

    $state = [
        'tab' => ''
    ];

    $response = array(
        'state'     => 200,
        'app_state' => $state,
        'html'      => $html
    );
    echo json_encode($response);

}

function get_catalogue_table_html($data, $customer,$website) {


    if (!isset($data['device_prefix'])) {
        $device_prefix = '';
    } else {
        $device_prefix = $data['device_prefix'];

    }

    include_once '../conf/export_fields.php';
    include_once '../conf/elements_options.php';


    $smarty = new Smarty();
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir('server_files/smarty/templates_c');
    $smarty->setCacheDir('server_files/smarty/cache');
    $smarty->setConfigDir('server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');

    $department_nav_label = '';
    $department_nav_title = '';
    $family_nav_label     = '';
    $family_nav_title     = '';
    $title                = '';

    $data_feeds=array(
        'title'=>_('All products data feed'),
        'urls'=>[
            'csv'=>'/catalog_data_feed.php?output=CSV&scope=website&scope_key='.$website->id,
            'xls'=>'/catalog_data_feed.php?output=XLS&scope=website&scope_key='.$website->id,
            'json'=>'/catalog_data_feed.php?output=Json&scope=website&scope_key='.$website->id
        ]
    );

    switch ($data['scope']) {
        case 'departments':

            $title   = _('Departments');
            $tab     = 'departments';
            $ar_file = 'ar_web_catalogue.php';
            $tipo    = 'departments';


            $default     = array(
                'view'        => 'overview',
                'sort_key'    => 'name',
                'sort_order'  => 1,
                'rpp'         => 100,
                'rpp_options' => [
                    500,
                    100
                ],
                'f_field'     => 'name',

            );
            $table_views = array(
                'overview' => array('label' => _('Overview')),


            );

            $table_filters = array(

                'name' => array(
                    'label' => _('Name'),
                    'title' => _('Department name')
                ),

            );

            $parameters = array(
                'parent'     => 'store',
                'parent_key' => $customer->get('Customer Store Key')

            );

            break;
        case 'families':
            if ($data['parent'] == 'department') {
                $department           = get_object('Category', $data['parent_key']);
                $department_nav_label = sprintf('<a href="catalogue.sys?scope=families&parent=department&parent_key=%d">%s</a>', $department->id, $department->get('Label'));


                if($device_prefix=='mobile'){
                    $title                = $department->get('Label');
                }else{
                    $title                = sprintf(_('Department: %s'), $department->get('Label'));
                }

                $data_feeds=array(
                    'title'=>_('Products in department data feed'),
                    'urls'=>[
                        'csv'=>'/catalog_data_feed.php?output=CSV&scope=department&scope_key='.$department->id,
                        'xls'=>'/catalog_data_feed.php?output=XLS&scope=department&scope_key='.$department->id,
                        'json'=>'/catalog_data_feed.php?output=Json&scope=department&scope_key='.$department->id
                    ]
                );


            } elseif ($data['parent'] == 'store') {

                $title = _('Families');
            }


            $tab     = 'families';
            $ar_file = 'ar_web_catalogue.php';
            $tipo    = 'families';


            $default     = array(
                'view'        => 'overview',
                'sort_key'    => 'code',
                'sort_order'  => 1,
                'rpp'         => 100,
                'rpp_options' => [
                    500,
                    100
                ],
                'f_field'     => 'code',

            );
            $table_views = array(
                'overview' => array('label' => _('Overview')),


            );

            $table_filters = array(
                'code' => array(
                    'label' => _('Code'),
                    'title' => _('Family code')
                ),
                'name' => array(
                    'label' => _('Name'),
                    'title' => _('Family name')
                ),

            );

            $parameters = array(
                'parent'     => $data['parent'],
                'parent_key' => $data['parent_key'],

            );



            break;
        case 'products':
            if ($data['parent'] == 'department') {
                $department           = get_object('Category', $data['parent_key']);
                $department_nav_label = sprintf('<a href="catalogue.sys?scope=families&parent=department&parent_key=%d">%s</a>', $department->id, $department->get('Label'));
                if($device_prefix=='mobile'){
                    $title                = $department->get('Label');
                }else{
                    $title                = sprintf(_('Department: %s'), $department->get('Label'));
                }

                $data_feeds=array(
                    'title'=>_('Products in department data feed'),
                    'urls'=>[
                        'csv'=>'/catalog_data_feed.php?output=CSV&scope=department&scope_key='.$department->id,
                        'xls'=>'/catalog_data_feed.php?output=XLS&scope=department&scope_key='.$department->id,
                        'json'=>'/catalog_data_feed.php?output=Json&scope=department&scope_key='.$department->id
                    ]
                );

            }
            elseif ($data['parent'] == 'family') {
                $family          = get_object('Category', $data['parent_key']);
                $family_nav_label = sprintf('<a href="catalogue.sys?scope=products&parent=family&parent_key=%d">%s</a>', $family->id, $family->get('Code'));
                $family_nav_title=htmlspecialchars($family->get('Label'));


                $department           = get_object('Category', $family->get('Product Category Department Category Key'));

                $department_nav_label = sprintf('<a href="catalogue.sys?scope=families&parent=department&parent_key=%d">%s</a>', $department->id, $department->get('Label'));

                $data_feeds=array(
                    'title'=>_('Products in family data feed'),
                    'urls'=>[
                        'csv'=>'/catalog_data_feed.php?output=CSV&scope=family&scope_key='.$family->id,
                        'xls'=>'/catalog_data_feed.php?output=XLS&scope=family&scope_key='.$family->id,
                        'json'=>'/catalog_data_feed.php?output=Json&scope=family&scope_key='.$family->id
                    ]
                );
                if($device_prefix=='mobile'){
                    $title                = $family->get('Label').' <span class="small ">('.$family->get('Code').')</span>';
                }else{
                    $title                = sprintf(_('Family: %s'), $family->get('Label')).' <span class="small margin_left_10">('.$family->get('Code').')</span>';
                }

            } elseif ($data['parent'] == 'store') {

                $title = _('Products');
            }


            $tab     = 'products';
            $ar_file = 'ar_web_catalogue.php';
            $tipo    = 'products';


            $default     = array(
                'view'        => 'overview',
                'sort_key'    => 'code',
                'sort_order'  => 1,
                'rpp'         => 100,
                'rpp_options' => [
                    500,
                    100
                ],
                'f_field'     => 'code',


            );
            $table_views = array(
                'overview' => array('label' => _('Overview')),


            );

            $table_filters = array(
                'code' => array(
                    'label' => _('Code'),
                    'title' => _('Product code')
                ),
                'name' => array(
                    'label' => _('Name'),
                    'title' => _('Product name')
                ),

            );

            $parameters = array(
                'parent'     => $data['parent'],
                'parent_key' => $data['parent_key'],

            );
            break;
    }


    $table_buttons = array();


    $smarty->assign('table_buttons', $table_buttons);


    include 'utils/get_table_html.php';

    $state = [
        'tab' => ''
    ];


    $response = array(
        'state'          => 200,
        'app_state'      => $state,
        'html'           => $html,
        'scope'          => $data['scope'],
        'department_nav' => array(
            'label' => $department_nav_label,
            'title' => $department_nav_title
        ),
        'family_nav'     => array(
            'label' => $family_nav_label,
            'title' => $family_nav_title
        ),

        'title' => $title,
        'data_feed' =>$data_feeds




    );
    echo json_encode($response);

}