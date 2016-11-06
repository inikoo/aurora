<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 January 2016 at 13:45:40 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

$store = $state['_object'];
$smarty->assign('store', $store);

$html = $smarty->fetch('dashboard/store_sales_plot.tpl');


?>
