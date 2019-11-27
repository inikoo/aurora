<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  28 January 2019 at 13:18:07 MYT+0800, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3.1

*/


$tab     = 'bill_of_materials';
$ar_file = 'ar_production_tables.php';
$tipo    = 'bill_of_materials';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array('label' => _('Overview')),


);

$table_filters = array(
    'reference' => array(
        'label' => _('Reference'),
        'title' => _('Reference')
    ),


);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

$table_buttons = array();

$table_buttons[] = array(
    'icon'  => 'plus',
    'title' => _('Add component'),
    'id'    => 'new_item',
    'class' => 'items_operation',

    'add_item' => array(
        'field'      => 'Units',
        'placeholder_qty' => _('qty (Units)'),
        'placeholder'     => _('Part reference'),
        'field_label'     => _("Material").':',
        'metadata'        => base64_encode(
            json_encode(
                array(
                    'scope'      => 'part',
                    'parent'     => 'Warehouse',
                    'parent_key' => $state['current_warehouse'],
                    'for_bill_of_materials'=>true,
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

$smarty->assign('table_top_template', 'bill_of_materials.edit.tpl');

include 'utils/get_table_html.php';


