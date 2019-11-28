<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 July 2016 at 15:51:13 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'location.parts';
$ar_file = 'ar_warehouse_tables.php';
$tipo    = 'parts';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'reference' => array(
        'label' => _('Reference'),
        'title' => _('Part reference')
    ),

);


$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$table_buttons = array();


$table_buttons[] = array(
    'icon'                 => 'plus',
    'title'                => _('New part'),
    'id'                   => 'new_part',
    'class'                => 'part',
    'add_part_to_location' => array(

        'field_label' => _("Part").':',
        'metadata'    => base64_encode(
            json_encode(
                array(
                    'scope'      => 'part',
                    'parent'     => 'account',
                    'parent_key' => 1,
                    'options'    => array('in_use')
                )
            )
        )

    )

);


$table_buttons[] = array(
    'icon'                         => 'person-dolly',
    'title'                        => _('Move all parts'),
    'class'                        => 'move_all_parts_from_location',
    'id'                           => 'move_all_parts_from_location',
    'move_all_parts_from_location' => array(
        'field_id' => 'Location Code',
        'field_label'=>_('Move to location'),
        'placeholder'=>_('code'),
        'location_key'=>$state['key']

    )
);


$smarty->assign(
    'table_metadata', json_encode(
                        array(
                            'parent'     => $state['object'],
                            'parent_key' => $state['key'],
                            'field'      => 'part'
                        )
                    )

);


$smarty->assign('table_buttons', $table_buttons);
$smarty->assign('table_operation_msg', 'move_all_parts_from_location_inline_msg');





$smarty->assign('table_top_template', 'location.parts.edit.tpl');

include 'utils/get_table_html.php';

