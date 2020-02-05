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


    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}


function get_portfolio_table_html($data, $customer) {


    include_once '../conf/export_fields.php';
    include_once '../conf/elements_options.php';


    $smarty = new Smarty();
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir('server_files/smarty/templates_c');
    $smarty->setCacheDir('server_files/smarty/cache');
    $smarty->setConfigDir('server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');


    $website = get_object('Website', $_SESSION['website_key']);
    $store   = get_object('Store', $website->get('Website Store Key'));


    //'html'       => $smarty->fetch('theme_1/blk.client.theme_1.EcomB2B'.($data['device_prefix'] != '' ? '.'.$data['device_prefix'] : '').'.tpl'),


    $tab     = 'portfolio';
    $ar_file = 'ar_web_portfolio.php';
    $tipo    = 'portfolio_items';




    $default=array(
        'view'          => 'overview',
        'sort_key'      => 'code',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => [500,100],
        'f_field'       => 'code',
    //    'f_period'      => 'ytd',
        'elements_type' => array_keys(get_elements_option('customer_portfolio'))[0],
        'elements'      => get_elements_option('customer_portfolio'),
        'export_fields' => get_export_fields('products_public')

    );

    $table_views = array(
        'overview'    => array('label' => _('Overview')),
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

    $table_buttons[] = array(
        'icon'     => 'plus',
        'title'    => _("Add product to portfolio"),
        'id'       => 'add_to_portfolio',
        'class'    => 'items_operation',
        'add_item_to_portfolio' => array(

            'field_label' => _("Product").':',
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

    $smarty->assign('table_buttons', $table_buttons);


    include 'utils/get_table_html.php';

    $state=[
        'tab'=>''
    ];

    $response = array(
        'state' => 200,
        'app_state'=>$state,
        'html'  => $html
    );
    echo json_encode($response);

}
