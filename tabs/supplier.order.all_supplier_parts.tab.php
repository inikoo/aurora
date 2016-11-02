<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 26 June 2016 at 11:54:43 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'supplier.order.all_supplier_parts';
$ar_file = 'ar_suppliers_tables.php';
$tipo    = 'order.supplier_parts';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
    'parts'    => array(
        'label' => _('Inventory Part'),
        'title' => _('Part details')
    ),
    'reorder'  => array('label' => _('Reorder')),

);

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


$table_buttons   = array();
$table_buttons[] = array(
    'icon'       => 'sign-out fa-flip-horizontal',
    'title'      => _("Purchase order items"),
    'change_tab' => 'supplier.order.items'
);
$smarty->assign('table_buttons', $table_buttons);

$smarty->assign(
    'js_code', 'js/injections/supplier.order.'.(_DEVEL ? '' : 'min.').'js'
);

$smarty->assign(
    'table_metadata', base64_encode(
        json_encode(
            array(
                'parent'     => $state['object'],
                'parent_key' => $state['key']
            )
        )
    )
);

include 'utils/get_table_html.php';


?>
