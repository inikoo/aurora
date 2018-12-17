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



$smarty->assign('order_flow',(empty($state['extra_tab'])?$state['extra']:$state['extra'].'_'.$state['extra_tab']));

$smarty->assign('account',$account);
$smarty->assign('currency',$account->get('Currency Code'));


$html = $smarty->fetch('dashboard/orders_server.dbard.tpl');




?>
