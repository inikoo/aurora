<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 November 2016 at 17:42:16 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/




$page=$state['_object']->get_webpage();


$request = preg_replace('/\./', '/', strtolower($page->get('Code')));
$smarty->assign('request', $request);



$smarty->assign('page', $page);
$smarty->assign('state', $state);

$html = $smarty->fetch('page_preview.tpl');


?>
