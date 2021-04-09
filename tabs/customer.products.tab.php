<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 09 april 2021 at 14:19:33 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$tab     = 'customer.products';
$ar_file = 'ar_products_tables.php';
$tipo    = 'products';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview'    => array('label' => _('Overview')),
    'price'    => array('label' => _('Price')),

    'performance' => array('label' => _('Performance')),
    'sales'       => array('label' => _('Sales')),
    'sales_y'     => array('label' => _('Invoiced amount (Yrs)')),
    'sales_q'     => array('label' => _('Invoiced amount (Qs)')),

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
    'parent'     => 'customer_product',
    'parent_key' => $state['key'],

);

$table_buttons = array();

$table_buttons[] = array(
    'icon'                  => 'plus',
    'title'                 => _("Assign product to customer"),
    'id'                    => 'add_product_to_customer',
    'class'                 => 'items_operation',
    'add_product_to_customer' => array(

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


include 'utils/get_table_html.php';


