<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 June 2016 at 15:13:50 CEST, Mijas Costa, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/


$website=$state['_object'];
$theme=$website->get('Website Theme');

$smarty->assign('website',$website);
$smarty->assign('theme',$theme);

$header_data = $website->get('Header Data');
$header_key=$website->get('Website Header Key');


$smarty->assign('header_data', $header_data);
$smarty->assign('header_key', $header_key);


$html = $smarty->fetch('header_preview.tpl');

?>
