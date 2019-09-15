<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16-09-2019 00:11:53 MYT, Kuala Lumpur, Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

$account->load_acc_data();
$smarty->assign('account',$account);
$html=$smarty->fetch('dashboard/suppliers_parts_stock_status.dbard.tpl');
