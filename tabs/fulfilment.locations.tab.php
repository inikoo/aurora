<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 Jun 2021 21:22 MYT , Malaysia , Kuala Lumpur
 Copyright (c) 2015, Inikoo

 Version 3

*/

if (!$user->can_view('fulfilment')) {
    $html = '';
} else {


    $warehouse = $state['warehouse'];


    $tab     = 'fulfilment.locations';
    $ar_file = 'ar_fulfilment_tables.php';
    $tipo    = 'locations';

    $default = $user->get_tab_defaults($tab);


    $table_views = array();

    $table_filters = array(
        'code' => array(
            'label' => _('Code'),
            'title' => _('Location code')
        ),

    );

    $parameters = array(
        'parent'     => $state['parent'],
        'parent_key' => $state['parent_key'],

    );





  //  $smarty->assign('title', _('Locations'));


//    $smarty->assign('view_position','<span onclick=\"change_view(\'warehouse/'.$warehouse->id.'\')\"><i class=\"fal  fa-warehouse-alt\"></i> <span class=\"id Warehouse_Code\">'.$warehouse->get('Code').'</span></span><i class=\"fa fa-angle-double-right separator\"></i>  '._('Locations').' </span>');


    include 'utils/get_table_html.php';
}

