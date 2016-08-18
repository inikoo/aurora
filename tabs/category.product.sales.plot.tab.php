<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 17 August 2016 at 21:51:13 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$category=$state['_object'];



$data=base64_encode(json_encode(array(
'valid_from'=>$category->get('Product Category Valid From'),
'valid_to'=>($category->get('Product Category Status')=='Discontinued'?$category->get('Product Category Valid To'):gmdate("Y-m-d H:i:s")  ) ,
'parent'=>'product_category',
'parent_key'=>$state['key'],
'title_value'=>_('Sales'),
'title_volume'=>_('Invoices')

)));

$smarty->assign('data',$data);
$html=$smarty->fetch('asset_sales.chart.tpl');


?>
