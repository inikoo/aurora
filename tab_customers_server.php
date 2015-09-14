<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 14 September 2015 19:11:44 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$smarty->left_delimiter = '{{';
$smarty->right_delimiter = '}}';
$smarty->assign('data',$state);


$smarty->assign('results_per_page_options',$results_per_page_options);
$smarty->assign('results_per_page',$results_per_page);


$smarty->assign('sortKey','id');
$smarty->assign('request',"/ar_assets.php?tipo=customers_per_store");


$smarty->assign('columns_file','columns_customers_server');



$html=$smarty->fetch('table.tpl');

?>
