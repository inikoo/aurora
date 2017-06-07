<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 June 2016 at 15:13:50 CEST, Mijas Costa, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

$theme='theme_1';

$smarty->assign('website',$state['_object']);
$smarty->assign('theme',$theme);

$html = $smarty->fetch('header_preview.tpl');

?>
