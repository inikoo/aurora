<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 April 2018 at 10:50:33 BST, Sheffield. UK
 Copyright (c) 2018, Inikoo

 Version 3

*/

$ar_file = 'ar_suppliers_tables.php';


if ($state['_object']->get('State Index') >=100) {
    $tab  = 'supplier.delivery.costing';
    $tipo = 'delivery.costing';

    $table_views = array(
        'overview' => array('label' => _("Item's descriptions")),

    );
  //  $smarty->assign('aux_templates', array('supplier.delivery.costing.tpl'));
/*
    $smarty->assign(
        'js_code', array(
            'js/injections/supplier.delivery.costing.'.(_DEVEL ? '' : 'min.').'js',
        )
    );
*/

} else {

    $html='delivery not placed yet';
    return;

    $tab  = 'supplier.delivery.items';
    $tipo = 'delivery.items';

    $table_views = array(
        'overview' => array('label' => _('Description')),

    );


}


$default = $user->get_tab_defaults($tab);


$table_filters = array(
    'code' => array('label' => _('Code')),
    'name' => array('label' => _('Name')),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$table_buttons   = array();

$smarty->assign('table_buttons', $table_buttons);

$smarty->assign(
    'table_metadata',
        json_encode(
            array('parent'     => $state['object'],
                  'parent_key' => $state['key']
            )

    )
);

$smarty->assign('delivery', $state['_object']);

$smarty->assign('currency', $state['_object']->get('Supplier Delivery Currency Code'));
$smarty->assign('currency_account', $account->get('Currency Code'));

$smarty->assign('currency_symbol', currency_symbol($account->get('Currency Code')));

$number_zero_placed_items=0;
$sql=sprintf('select count(*) as num from `Purchase Order Transaction Fact` where `Supplier Delivery Key`=%d and (`Supplier Delivery Placed Units`=0  or `Supplier Delivery Placed Units` is null) ',
             $state['_object']->id

    );
if ($result=$db->query($sql)) {
    if ($row = $result->fetch()) {
        $number_zero_placed_items=$row['num'];
	}
}else {
	print_r($error_info=$db->errorInfo());
	print "$sql\n";
	exit;
}

//print $sql;

$smarty->assign('number_zero_placed_items',$number_zero_placed_items);


$smarty->assign('table_top_template', 'supplier.delivery.costing.tpl');

$smarty->assign('table_identification', 'supplier_delivery_costing_table');



include 'utils/get_table_html.php';



