<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  28 January 2019 at 13:35:32 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3.1

*/


$tab     = 'production_part.tasks';
$ar_file = 'ar_production_tables.php';
$tipo    = 'production_part.tasks';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview'    => array('label' => _('Overview')),


);

$table_filters = array(
    'code' => array(
        'label' => _('Reference'),
        'title' => _('Reference')
    ),


);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

$table_buttons   = array();

$table_buttons[] = array(
    'icon'     => 'task',
    'title'    => _('Link task'),
    'id'       => 'new_item',
    'class'    => 'items_operation',
    'add_item' => array(

        'field_label' => _("Component").':',
        'metadata'    => base64_encode(
            json_encode(
                array(
                    'scope'      => 'task',
                    'parent'     => 'Warehouse',
                    'parent_key' => $state['current_warehouse'],
                    'options'    => array('for_bill_of_materials')
                )
            )
        )

    )

);


$smarty->assign('table_buttons', $table_buttons);

include 'utils/get_table_html.php';


