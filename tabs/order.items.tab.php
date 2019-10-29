<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 October 2015 at 13:03:57 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'order.items';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'order.items';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview'     => array(
        'label' => _('Description'),
        'title' => _('Description')
    ),
    'properties' => array(
        'label' => _('Tariff Codes / Weight'),
        'title' => _('Tariff Codes / Weight')
    ),

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
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$table_buttons   = array();


if($state['_object']->get('State Index')>0 and  $state['_object']->get('State Index')<=40){

    $table_buttons[] = array(
        'icon'     => 'plus',
        'title'    => _('New item'),
        'id'       => 'new_item',
        'class'    => 'items_operation '.(($state['_object']->get('State Index')>0 and  $state['_object']->get('State Index')<40)?'':'hide'),
        'add_item' => array(

            'field_label' => _("Product").':',
            'metadata'    => base64_encode(
                json_encode(
                    array(
                        'scope'      => 'product',
                        'parent'     => 'Store',
                        'parent_key' => $state['_object']->get('Store Key'),
                        'options'    => array('for_order')
                    )
                )
            )

        )

    );
}

if($state['_object']->get('State Index')==40){

    $table_buttons[] = array(
        'icon'     => 'pencil-alt',
        'title'    => _('Edit items'),
        'id'       => 'edit_order_in_warehouse_items',
    );

}


$smarty->assign('object', $state['_object']);


$smarty->assign('table_buttons', $table_buttons);



$smarty->assign(
    'table_metadata',
                        json_encode(
                            array(
                                'parent'     => $state['object'],
                                'parent_key' => $state['key'],
                                'field'      => 'Order Quantity'
                            )
                        )

);





include('utils/get_table_html.php');



