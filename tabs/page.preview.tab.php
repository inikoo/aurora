<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 1 June 2016 at 09:58:41 CEST, Mijas Costa, Spain
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


$html = $smarty->fetch('page_preview.tpl');


?>
