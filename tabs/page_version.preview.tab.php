<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 November 2016 at 16:15:23 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$request = preg_replace(
    '/\./', '/', strtolower($state['_object']->get('Code'))
);
$smarty->assign('request', $request);


//$smarty->assign('node', $node);

$smarty->assign('page', $state['_object']);
$smarty->assign('key', $state['key']);

$smarty->assign('state', $state);


$html = $smarty->fetch('page_version_preview.tpl');


?>
