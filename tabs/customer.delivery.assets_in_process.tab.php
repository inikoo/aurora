<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 10 Jul 2021 18:19:35 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

/** @var array $state */
/** @var \User $user */


$ar_file = 'ar_fulfilment_tables.php';
$tipo    = 'customer.order.assets_in_process';
$tab     = 'customer.order.assets_in_process';


/**
 * @var $fulfilment_delivery \Fulfilment_Delivery
 */
$fulfilment_delivery = $state['_object'];

$table_views = array(
    'overview' => array('label' => _('Overview'),),

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
    'class'    => 'items_operation'.($fulfulment_delivery->get('Purchase Order State') != 'InProcess' ? ' hide' : ''),
    'add_item' => array(
        'field'           => 'Purchase Order Cartons',
        'field_label'     => _("Supplier's product").':',
        'placeholder_qty' => _("Cartons"),
        'metadata'        => base64_encode(
            json_encode(
                array(
                    'scope'      => 'supplier_part',
                    'parent'     => $fulfulment_delivery->get('Purchase Order Parent'),
                    'parent_key' => $fulfulment_delivery->get('Purchase Order Parent Key'),
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
    'class'    => 'items_operation'.($fulfulment_delivery->get('Purchase Order State') != 'InProcess' ? ' hide' : ''),
    'add_item' => array(
        'field'           => 'Purchase Order SKOs',
        'field_label'     => _("Supplier's product").':',
        'placeholder_qty' => _("SKOs"),
        'metadata'        => base64_encode(
            json_encode(
                array(
                    'scope'      => 'supplier_part',
                    'parent'     => $fulfulment_delivery->get('Purchase Order Parent'),
                    'parent_key' => $fulfulment_delivery->get('Purchase Order Parent Key'),
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
    'class'    => 'items_operation'.($fulfulment_delivery->get('Purchase Order State') != 'InProcess' ? ' hide' : ''),
    'add_item' => array(
        'field'           => 'Purchase Order Units',
        'field_label'     => _("Supplier's product").':',
        'placeholder_qty' => _("Units"),
        'metadata'        => base64_encode(
            json_encode(
                array(
                    'scope'      => 'supplier_part',
                    'parent'     => $fulfulment_delivery->get('Purchase Order Parent'),
                    'parent_key' => $fulfulment_delivery->get('Purchase Order Parent Key'),
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
        'parent_key' => $fulfulment_delivery->id,
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

