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

        $order=get_object('Order',$data['order_key']);

        if($order->get('Order Customer Key')!=$customer->id){
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
    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;

}

function get_order_items_table_html($data,$parameters ,$customer,$db) {


    if(!isset($data['device_prefix'])){
        $device_prefix='';
    }else{
        $device_prefix=$data['device_prefix'];

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




    $tab     = 'order_items';
    $ar_file = 'ar_web_order.php';
    $tipo    = 'order_items';


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


    );





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


    );




    $table_buttons = array();

    $smarty->assign('web_user', $web_user);


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


    if(!isset($data['device_prefix'])){
        $device_prefix='';
    }else{
        $device_prefix=$data['device_prefix'];

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




    $tab     = 'portfolio';
    $ar_file = 'ar_web_portfolio.php';
    $tipo    = 'portfolio_items';


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


    if($device_prefix==''){
        $default['export_fields']=get_export_fields('portfolio_items');
    }


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
    if($device_prefix=='') {
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
        'state'     => 200,
        'app_state' => $state,
        'html'      => $html,
        'images_zip_url'=>'data_feed.php?uid='.$web_user->id.'&token='.$web_user->get('Website User Static API Hash').'&output=images&scope=portfolio_images',
        'data_feed_url'=>'data_feed.php?uid='.$web_user->id.'&token='.$web_user->get('Website User Static API Hash').'&output=CSV&scope=portfolio_items',
    );
    echo json_encode($response);

}

function get_clients_table_html($data, $customer) {


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


    $tab     = 'customer_clients';
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