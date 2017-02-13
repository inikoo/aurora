<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:13 February 2017 at 09:25:34 GMT+8, Cyberjaya , Malydia
 Copyright (c) 2017, Inikoo

 Version 3.0
*/



$store=$state['store'];

$store->load_acc_data();

//include_once 'widgets/warehouse_alerts.wget.php';

//$state['_object']->get_kpi('Month To Day');

$smarty->assign('store',$store);
$smarty->assign('currency',$store->get('Store Currency Code'));

$html = $smarty->fetch('dashboard/orders.dbard.tpl');


//$html .= '<div class="widget_container">'.get_warehouse_alerts( $db,$warehouse, $account, $user, $smarty).'</div>';
//$html .= '<div id="widget_details" class="hide" style="clear:both;font-size:90%;border-top:1px solid #ccc"><div>';


?>
