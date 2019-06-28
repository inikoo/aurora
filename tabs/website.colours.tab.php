<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 February 2018 at 17:29:38 GMT+8, Cyberjaya , Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$website=$state['_object'];
$theme=$website->get('Website Theme');


$smarty->assign('settings',$website->settings);


$smarty->assign('website',$website);
$smarty->assign('theme',$theme);

//print_r($website->settings);


$html = $smarty->fetch('control.website.colors.tpl');








?>
