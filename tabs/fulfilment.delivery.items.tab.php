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
                'icon'  => 'edit_add',
                'title' => _("Edit items"),
                'id'    => 'edit_dialog'
            );

            $table_buttons[] = array(
                'icon'                 => 'plus',
                'title'                => _('New item'),
                'id'                   => 'add_fulfilment_asset',
                'class'                => 'items_operation'.(($fulfilment_delivery->get('Fulfilment Delivery State') == 'InProcess' or $fulfilment_delivery->get('Fulfilment Delivery State') == 'Received') ? ' ' : 'hide'),
                'add_fulfilment_asset' => array(
                    'ar_url'      => '/ar_edit_fulfilment.php',
                    'metadata' => json_encode(
                        array(
                            'parent'     => 'Fulfilment_Delivery',
                            'parent_key' => $fulfilment_delivery->id,
                            'ar_url'=>''
                        )
                    )


                )

            );

            $edit_table_dialog = array(
                'labels' => array(
                    'add_items'  => _("Upload items").":",
                    'edit_items' => _("Edit items").":"
                ),

                'upload_items' => array(
                    'icon'         => 'plus',
                    'label'        => _("Upload items"),
                    'template_url' => '/upload_arrangement.php?object=fulfilment_asset&parent=fulfilment_delivery&parent_key='.$fulfilment_delivery->id,

                    'tipo'       => 'edit_objects',
                    'parent'     => 'fulfilment_delivery',
                    'parent_key' => $fulfilment_delivery->id,

                    'object' => 'fulfilment_asset',
                ),

                'spreadsheet_edit' => array(
                    'tipo' => 'edit_objects',

                    'parent'      => 'fulfilment_delivery',
                    'parent_key'  => $fulfilment_delivery->id,
                    'object'      => 'fulfilment_asset',
                    'parent_code' => $fulfilment_delivery->id,
                ),

            );
            $smarty->assign('edit_table_dialog', $edit_table_dialog);


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
            $tipo        = 'fulfilment.delivery.assets';
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

