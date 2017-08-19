<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 August 2017 at 08:36:51 CEST, Tranava, Slovakia
 Copyright (c) 2017, Inikoo

 Version 3.0
*/



$account=get_object('Account',1);

$account->load_acc_data();

$account->update_orders();


//include_once 'widgets/warehouse_alerts.wget.php';

//$state['_object']->get_kpi('Month To Day');

$smarty->assign('account',$account);
$smarty->assign('currency',$account->get('Currency Code'));

$html = $smarty->fetch('dashboard/orders_server.dbard.tpl');

$html .= '<div id="widget_details" class="hide" style="clear:both;margin-top:20px;border-top:1px solid #ccc"><div>';



?>
