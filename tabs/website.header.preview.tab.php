<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 May 2018 at 21:31:18 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


$website=$state['_object'];
$theme=$website->get('Website Theme');

$smarty->assign('website',$website);
$smarty->assign('theme',$theme);

//print_r($website->settings);


$html = $smarty->fetch('control.website.header.tpl');



?>
