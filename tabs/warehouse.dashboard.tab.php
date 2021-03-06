<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 February 2017 at 18:38:11 GMT+8, Cyberjaya , Malaysia
 Copyright (c) 2017, Inikoo

 Version 3.0
*/


$account->load_acc_data();

/**
 * @var $warehouse \Warehouse
 */
$warehouse=$state['_object'];

// todo update real time when qty change
$warehouse->update_stock_amount();
$warehouse->update_children();
$warehouse->update_warehouse_paid_ordered_parts();
$warehouse->update_warehouse_part_locations_to_replenish();
$warehouse->update_children();

include_once 'widgets/warehouse_alerts.wget.php';

//$state['_object']->get_kpi('Month To Day');

$smarty->assign('warehouse',$state['_object']);
$html = $smarty->fetch('warehouse.kpi.tpl');
$html .= '<div class="widget_container">'.get_warehouse_alerts( $db,$warehouse, $account, $user, $smarty).'</div>';
$html .= '<div id="widget_details" class="hide" style="clear:both;font-size:90%;border-top:1px solid #ccc"><div>';



