<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1:55 pm Monday, 22 June 2020 (MYT) Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/


$tab = 'suppliers.supplier_parts.all';


$table_views = array(
    'reorder'  => array('label' => _('Reorder')),

    'overview' => array(
        'label' => _('Overview'),
    ),
    'barcodes' => array('label' => _("Barcode/Weight/CMB")),
    'parts'    => array(
        'label' => _('Part sales'),
        'title' => _('Sales of associated part (include other suppliers)')
    ),

);

$ar_file = 'ar_suppliers_tables.php';
$tipo    = 'supplier_parts';

$default = $user->get_tab_defaults($tab);


$table_filters = array(
    'reference' => array(
        'label' => _('Reference'),
        'label' => _('Reference'),
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


include 'utils/get_table_html.php';



