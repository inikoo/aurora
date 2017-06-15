<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 June 2016 at 13:45:01 CEST, Mijas Costa, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

$theme=$state['_object']->get('Website Theme');

$smarty->assign('website',$state['_object']);
$smarty->assign('theme',$theme);

$html = $smarty->fetch('footer_preview.tpl');

?>
