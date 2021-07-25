<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 25 Jul 2021 14:06:40 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

include_once 'utils/get_export_edit_template_fields.php';
/** @var User $user */
/** @var \Smarty $smarty */
/** @var array $state */

if (!$user->can_view('locations') or !in_array(
        $state['key'], $user->warehouses
    )
) {
    $html = '';
} else {

    $warehouse =  $state['_parent'];

    $tab     = 'warehouse.picking_pipelines';
    $ar_file = 'ar_warehouse_tables.php';
    $tipo    = 'picking_pipelines';

    $default = $user->get_tab_defaults($tab);


    $table_views = array();

    $table_filters = array(
        'name' => array('label' => _('Name')),

    );

    $parameters = array(
        'parent'     => $state['parent'],
        'parent_key' => $state['parent_key'],

    );



    $smarty->assign('table_buttons', []);

    $smarty->assign('title', _('Picking pipelines'));
    $smarty->assign('view_position','<span onclick=\"change_view(\'warehouse/'.$warehouse->id.'\')\"><i class=\"fal  fa-warehouse-alt\"></i> <span class=\"id Warehouse_Code\">'.$warehouse->get('Code').
                                   '</span></span><i class=\"fa fa-angle-double-right separator\"></i>  '._('Picking pipelines').' </span>');


    include 'utils/get_table_html.php';
}


