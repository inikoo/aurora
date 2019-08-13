<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 December 2018 at 12:35:34 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2018, Inikoo

 Version 3.0
*/




$account=get_object('Account',1);
$account->load_acc_data();
$warehouse=get_object('Warehouse',$session->get('current_warehouse'));

$warehouse->update_delivery_notes();
$smarty->assign('delivery_note_flow',(empty($state['extra_tab'])?$state['extra']:$state['extra'].'_'.$state['extra_tab']));


$smarty->assign('account',$account);
$smarty->assign('warehouse',$warehouse);

$smarty->assign('currency',$account->get('Currency Code'));


$html = $smarty->fetch('dashboard/pending_delivery_notes.dbard.tpl');





