<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 10 Jul 2021 02:21:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

/** @var array $state */
/** @var \User $user */
/** @var \Smarty $smarty */

/**
 * @var $fulfilment_delivery \Fulfilment_Delivery
 */
$fulfilment_delivery = $state['_object'];


$ar_file       = 'ar_fulfilment_tables.php';
$table_buttons = [];

if ($state['_object']->get('Fulfilment Delivery Type') == 'Part') {
    switch ($state['_object']->get('Fulfilment Delivery State')) {
        case 'InProcess':
            $tab         = 'customer.delivery.parts_in_process';
            $table_views = array('overview' => array('label' => _('Overview')));
            break;
        default:
            $tab = 'customer.delivery.parts';

            $table_views = array('overview' => array('label' => _('Overview')),);
            break;
    }
    $table_filters = array(
        'reference'   => array('label' => _('Reference')),
        'description' => array('label' => _('Description')),
    );
} else {
    switch ($state['_object']->get('Fulfilment Delivery State')) {
        case 'InProcess':
            $tab         = 'customer.delivery.assets_in_process';
            $table_views = array('overview' => array('label' => _('Overview')));
            $tipo        = 'customer.delivery.assets_in_process';


            $table_buttons[] = array(
                'icon'     => 'plus',
                'title'    => _('New item'),
                'id'       => 'new_item_unit',
                'class'    => 'items_operation'.($fulfilment_delivery->get('Fulfilment Delivery State') != 'InProcess' ? ' hide' : ''),
                'add_item' => array(
                    'field'           => 'Fulfilment Delivery Units',
                    'field_label'     => _("Supplier's product").':',
                    'placeholder_qty' => _("Units"),
                    'metadata'        => base64_encode(
                        json_encode(
                            array(
                                'scope'      => 'supplier_part',
                                'parent'     => $fulfilment_delivery->get('Fulfilment Delivery Parent'),
                                'parent_key' => $fulfilment_delivery->get('Fulfilment Delivery Parent Key'),
                                'options'    => array('for_purchase_order')
                            )
                        )
                    )

                )

            );


            $table_buttons[] = array(
                'icon'         => 'upload',
                'title'        => _('Upload assets'),
                'id'           => 'upload_order_items',
                'class'        => 'hide',
                'upload_items' => array(
                    'tipo'       => 'add_item',
                    'parent'     => 'Fulfilment_Delivery',
                    'parent_key' => $fulfilment_delivery->id,
                    'field'      => 'Fulfilment Delivery Units'
                )

            );


            $smarty->assign(
                'table_metadata', json_encode(
                                    array(
                                        'parent'     => $state['object'],
                                        'parent_key' => $state['key'],

                                    )
                                )

            );


            break;
        default:
            $tab = 'customer.delivery.assets';

            $table_views = array('overview' => array('label' => _('Overview')),);
            break;
    }
    $table_filters = array(
        'id'        => array('label' => _('Id')),
        'reference' => array('label' => _('Reference')),
    );
}


$smarty->assign('table_buttons', $table_buttons);

$default = $user->get_tab_defaults($tab);


$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

$table_buttons = array();
$smarty->assign('table_buttons', $table_buttons);
include 'utils/get_table_html.php';

