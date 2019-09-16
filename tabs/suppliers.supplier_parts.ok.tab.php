<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 316-09-2019 17:24:57 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/


$tab = 'suppliers.supplier_parts.ok';


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
    'barcodes' => array('label' => _("Barcode/Weight/CMB")),
    'parts'    => array(
        'label' => _('Part sales'),
        'title' => _('Sales of associated part (include other suppliers)')
    ),
    'reorder'  => array('label' => _('Part (re)stock')),

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



