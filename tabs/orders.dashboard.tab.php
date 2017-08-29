<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:13 February 2017 at 09:25:34 GMT+8, Cyberjaya , Malaysia
 Copyright (c) 2017, Inikoo

 Version 3.0
*/



$store=$state['store'];

$store->load_acc_data();

$store->update_orders();


//include_once 'widgets/warehouse_alerts.wget.php';

//$state['_object']->get_kpi('Month To Day');

$smarty->assign('store',$store);
$smarty->assign('currency','store');

$html = $smarty->fetch('dashboard/orders.dbard.tpl');

$html .= '<div id="widget_details" class="hide" style="clear:both;margin-top:20px;border-top:1px solid #ccc"><div>';



?>
