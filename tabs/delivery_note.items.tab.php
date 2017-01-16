<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 October 2015 at 15:10:19 CETT, Pisa-Milan (train), Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'delivery_note.items';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'delivery_note.items';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview'     => array(
        'label' => _('Overview'),
    ),
    'picking_aid' => array(
        'label' => _('Picking Aid'),
    ),
    'packing_aid' => array(
        'label' => _('Packing Aid'),
    ),

);

$table_filters = array(
    'code' => array(
        'label' => _('Code'),
        'title' => _('Product code')
    ),
    'name' => array(
        'label' => _('Name'),
        'title' => _('Product name')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

$smarty->assign(
    'table_metadata', base64_encode(
                        json_encode(
                            array('parent'     => $state['object'],
                                  'parent_key' => $state['key']
                            )
                        )
                    )
);
$smarty->assign('dn', $state['_object']);
$state['_object']->update_totals();

$smarty->assign('table_top_template', 'delivery_note.options.tpl');

include('utils/get_table_html.php');


?>
