<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  Wed 16 Oct 2019 11:19:41 +0800 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

$tab     = 'customer.active_portfolio';
$ar_file = 'ar_customers_tables.php';
$tipo    = 'customer_portfolio';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview')
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
    'type'       => 'Active'

);

$table_buttons = array();

$table_buttons[] = array(
    'icon'                  => 'plus',
    'title'                 => _("Add product to customer's portfolio"),
    'id'                    => 'add_to_portfolio',
    'class'                 => 'items_operation',
    'add_item_to_portfolio' => array(

        'field_label' => _("Product").':',
        'ar_url'     => '/ar_edit_customers.php',
        'metadata'    => base64_encode(
            json_encode(
                array(

                    'scope'      => 'product',
                    'parent'     => 'Store',
                    'parent_key' => $state['_object']->get('Store Key'),
                    'options'    => array('for_order'),
                )
            )
        )

    )

);
$smarty->assign('table_buttons', $table_buttons);

$smarty->assign(
    'table_metadata', json_encode(
                        array(
                            'parent'     => $state['object'],
                            'parent_key' => $state['key'],
                        )
                    )

);


include('utils/get_table_html.php');


