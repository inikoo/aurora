<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:27 13 September 2015 18:30:16 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$smarty->left_delimiter = '{{';
$smarty->right_delimiter = '}}';
$smarty->assign('data',$state);


$smarty->assign('results_per_page_options',$results_per_page_options);
$smarty->assign('results_per_page',$results_per_page);


$smarty->assign('sortKey','id');
$smarty->assign('request',"/ar_contacts.php?tipo=customers&parent=store&parent_key=".$state['parent_key']);


$smarty->assign('columns_file','columns_customers');


$html=$smarty->fetch('table.tpl');

?>
