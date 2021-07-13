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
            $tab         = 'fulfilment.delivery.parts_in_process';
            $table_views = array('overview' => array('label' => _('Overview')));
            break;
        default:
            $tab = 'fulfilment.delivery.parts';

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
        case 'Received':
            $tab         = 'fulfilment.delivery.assets';
            $table_views = array('overview' => array('label' => _('Overview')));
            $tipo        = 'fulfilment.delivery.assets';

            $table_buttons[] = array(
                'icon_classes' => 'fa fa-trash-alt very_discreet_on_hover',
                'icon'         => 'trash-alt',
                'title'        => _('Delete assets'),
                'id'           => 'show_delete_asset_column',


            );

            $table_buttons[] = array(
                'icon'         => 'upload',
                'title'        => _('Upload assets'),
                'id'           => 'upload_order_items',
                'class'        => (($fulfilment_delivery->get('Fulfilment Delivery State') == 'InProcess' or $fulfilment_delivery->get('Fulfilment Delivery State') == 'Received') ? ' ' : 'hide'),
                'upload_items' => array(
                    'tipo'       => 'add_item',
                    'parent'     => 'Fulfilment_Delivery',
                    'parent_key' => $fulfilment_delivery->id,
                    'field'      => 'Fulfilment Delivery Units'
                )

            );

            $table_buttons[] = array(
                'icon'                 => 'plus',
                'title'                => _('New item'),
                'id'                   => 'add_fulfilment_asset',
                'class'                => 'items_operation'.(($fulfilment_delivery->get('Fulfilment Delivery State') == 'InProcess' or $fulfilment_delivery->get('Fulfilment Delivery State') == 'Received') ? ' ' : 'hide'),
                'add_fulfilment_asset' => array(
                    'metadata' => json_encode(
                        array(
                            'parent'     => 'Fulfilment_Delivery',
                            'parent_key' => $fulfilment_delivery->id,
                        )
                    )


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
            $tab = 'fulfilment.delivery.assets';

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


include 'utils/get_table_html.php';

