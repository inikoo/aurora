<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  07 March 2020  11:43::23  +0800, Kuala Lumpur Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_out.php';



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


    case 'catalogue':
        $data = prepare_values(
            $_REQUEST, array(
                         'parent'         => array('type' => 'string'),
                         'parent_key'     => array('type' => 'string'),
                         'scope'     => array('type' => 'string'),
                         'device_prefix' => array(
                             'type'     => 'string',
                             'optional' => true
                         )
                     )
        );

        get_catalogue_table_logged_out_html($data,$website);
        break;
    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;

}



function get_catalogue_table_logged_out_html($data,$website) {


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




    switch ($data['scope']){
        case 'departments':
            $tab     = 'departments_logged_out';
            $ar_file = 'ar_web_catalogue_logged_out.php';
            $tipo    = 'departments';


            $default = array(
                'view'          => 'overview',
                'sort_key'      => 'label',
                'sort_order'    => 0,
                'rpp'           => 100,
                'rpp_options'   => [
                    500,
                    100
                ],
                'f_field'       => 'name',

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
            $tab     = 'families_logged_out';
            $ar_file = 'ar_web_catalogue_logged_out.php';
            $tipo    = 'families';


            $default = array(
                'view'          => 'overview',
                'sort_key'      => 'code',
                'sort_order'    => 0,
                'rpp'           => 100,
                'rpp_options'   => [
                    500,
                    100
                ],
                'f_field'       => 'code',


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
                'parent_key' =>$data['parent_key'],

            );
            break;
        case 'products':
            $tab     = 'products_logged_out';
            $ar_file = 'ar_web_catalogue_logged_out.php';
            $tipo    = 'products';


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
                'parent_key' =>$data['parent_key'],

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


    );
    echo json_encode($response);

}