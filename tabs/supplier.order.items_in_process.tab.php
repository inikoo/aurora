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
$tab = 'supplier.order.items_in_process';


/**
 * @var $supplier \Supplier
 */
$supplier=$state['_object'];

$table_views = array(
    'cartons' => array('label' => _('Ordering cartons'),),
    'skos'    => array('label' => _('Ordering SKOs'),),
    'units'   => array('label' => _('Ordering units'),),
);

$default = $user->get_tab_defaults($tab);


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
    'class'    => 'items_operation'.($supplier->get('Purchase Order State') != 'InProcess' ? ' hide' : ''),
    'add_item' => array(
        'field'      => 'Purchase Order Cartons',
        'field_label' => _("Supplier's product").':',
        'placeholder_qty' => _("Cartons"),
        'metadata'    => base64_encode(
            json_encode(
                array(
                    'scope'      => 'supplier_part',
                    'parent'     => $supplier->get('Purchase Order Parent'),
                    'parent_key' => $supplier->get('Purchase Order Parent Key'),
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
    'class'    => 'items_operation'.($supplier->get('Purchase Order State') != 'InProcess' ? ' hide' : ''),
    'add_item' => array(
        'field'      => 'Purchase Order SKOs',
        'field_label' => _("Supplier's product").':',
        'placeholder_qty' => _("SKOs"),
        'metadata'    => base64_encode(
            json_encode(
                array(
                    'scope'      => 'supplier_part',
                    'parent'     => $supplier->get('Purchase Order Parent'),
                    'parent_key' => $supplier->get('Purchase Order Parent Key'),
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
    'class'    => 'items_operation'.($supplier->get('Purchase Order State') != 'InProcess' ? ' hide' : ''),
    'add_item' => array(
        'field'      => 'Purchase Order Units',
        'field_label' => _("Supplier's product").':',
        'placeholder_qty' => _("Units"),
        'metadata'    => base64_encode(
            json_encode(
                array(
                    'scope'      => 'supplier_part',
                    'parent'     => $supplier->get('Purchase Order Parent'),
                    'parent_key' => $supplier->get('Purchase Order Parent Key'),
                    'options'    => array('for_purchase_order')
                )
            )
        )

    )

);


$smarty->assign('table_buttons', $table_buttons);

$smarty->assign(
    'table_metadata',
                        json_encode(
                            array(
                                'parent'     => $state['object'],
                                'parent_key' => $state['key'],

                            )
                        )

);


include 'utils/get_table_html.php';

