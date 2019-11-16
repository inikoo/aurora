<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 November 2018 at 13:21:58 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$ar_file = 'ar_warehouse_tables.php';


$tab  = 'return.check_items';
$tipo = 'return.checking_items';

$table_views = array(
    'overview' => array('label' => _("Item's descriptions")),
    // 'placement_notes'=>array('label'=>_('Placement notes')),

);
$smarty->assign('aux_templates', array('supplier.delivery.checking.tpl'));

$smarty->assign(
    'js_code', array(
                 'js/injections/supplier.delivery.checking.'.(_DEVEL ? '' : 'min.').'js',
             )
);


$default = $user->get_tab_defaults($tab);


$table_filters = array(
    'reference' => array('label' => _('Reference')),
    'name'      => array('label' => _('Name')),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$table_buttons = array();


$table_buttons[] = array(
    'icon'  => 'barcode',
    'id'    => 'book_in_with_barcode',
    'class' => 'items_operation '.($state['_object']->get('Supplier Delivery State') != 'Received' ? ' hide' : ''),
    'title' => _("Book in using barcode scanner"),
);


$table_buttons[] = array(
    'icon'       => 'stop',
    'id'         => 'all_available_items',
    'class'      => 'items_operation'.($state['_object']->get('Supplier Delivery State') != 'In Process' ? ' hide' : ''),
    'title'      => _("All supplier's products"),
    'change_tab' => 'supplier.order.all_supplier_parts'
);


$smarty->assign('table_buttons', $table_buttons);

$smarty->assign(
    'table_metadata',
                        json_encode(
                            array(
                                'parent'     => $state['object'],
                                'parent_key' => $state['key'],
                                'type'  => 'return'
                            )

                    )
);

$smarty->assign('dn', $state['_object']);


$smarty->assign('table_top_template', 'supplier.delivery.options.tpl');


include 'utils/get_table_html.php';


?>
