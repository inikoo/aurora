<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 August 2018 at 15:45:45 GMT+8 Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$ar_file = 'ar_suppliers_tables.php';
$tipo    = 'supplier.order.items_in_process';
$tab     = 'job_order.items_in_process';


/**
 * @var $purchase_order \PurchaseOrder
 */
$purchase_order = $state['_object'];

$table_views = array(
    'batches' => array('label' => _('Planing batch'),),

    'cartons' => array('label' => _('Cartons view'),),
    'skos'    => array('label' => _('SKOs view'),),
    'units'   => array('label' => _('Units view'),),
);

$default = $user->get_tab_defaults($tab);


//print_r($default);

$table_filters = array(
    'code' => array('label' => _('Code')),
    'name' => array('label' => _('Name')),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$table_buttons = array();


$table_buttons[] = array(
    'icon'     => 'plus',
    'title'    => _('New item'),
    'id'       => 'new_item_carton',
    'class'    => 'items_operation'.($purchase_order->get('Purchase Order State') != 'InProcess' ? ' hide' : ''),
    'add_item' => array(
        'field'           => 'Purchase Order Cartons',
        'field_label'     => _("Supplier's product").':',
        'placeholder_qty' => _("Cartons"),
        'metadata'        => base64_encode(
            json_encode(
                array(
                    'scope'      => 'supplier_part',
                    'parent'     => $purchase_order->get('Purchase Order Parent'),
                    'parent_key' => $purchase_order->get('Purchase Order Parent Key'),
                    'options'    => array('for_purchase_order')
                )
            )
        )

    )

);


$table_buttons[] = array(
    'icon'     => 'plus',
    'title'    => _('New item'),
    'id'       => 'new_item_sko',
    'class'    => 'items_operation'.($purchase_order->get('Purchase Order State') != 'InProcess' ? ' hide' : ''),
    'add_item' => array(
        'field'           => 'Purchase Order SKOs',
        'field_label'     => _("Supplier's product").':',
        'placeholder_qty' => _("SKOs"),
        'metadata'        => base64_encode(
            json_encode(
                array(
                    'scope'      => 'supplier_part',
                    'parent'     => $purchase_order->get('Purchase Order Parent'),
                    'parent_key' => $purchase_order->get('Purchase Order Parent Key'),
                    'options'    => array('for_purchase_order')
                )
            )
        )

    )

);

$table_buttons[] = array(
    'icon'     => 'plus',
    'title'    => _('New item'),
    'id'       => 'new_item_unit',
    'class'    => 'items_operation'.($purchase_order->get('Purchase Order State') != 'InProcess' ? ' hide' : ''),
    'add_item' => array(
        'field'           => 'Purchase Order Units',
        'field_label'     => _("Supplier's product").':',
        'placeholder_qty' => _("Units"),
        'metadata'        => base64_encode(
            json_encode(
                array(
                    'scope'      => 'supplier_part',
                    'parent'     => $purchase_order->get('Purchase Order Parent'),
                    'parent_key' => $purchase_order->get('Purchase Order Parent Key'),
                    'options'    => array('for_purchase_order')
                )
            )
        )

    )

);



$table_buttons[] = array(
    'icon'         => 'upload',
    'title'        => _('Upload items'),
    'id'           => 'upload_order_items',
    'class'=>'hide',
    'upload_items' => array(
        'tipo'       => 'add_item',
        'parent'     => 'PurchaseOrder',
        'parent_key' => $purchase_order->id,
        'field'     => 'Purchase Order Units'
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

