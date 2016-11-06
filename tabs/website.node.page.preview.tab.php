<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:2 June 2016 at 11:52:03 CEST, Mijas Costa, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

$node = $state['_object'];
$page = get_object('webpage', $node->get_webpage_key());


$request = preg_replace('/\./', '/', strtolower($node->get('Code')));
$smarty->assign('request', $request);


$smarty->assign('node', $node);

$smarty->assign('page', $page);
$smarty->assign('key', $state['key']);

$smarty->assign('state', $state);


$html = $smarty->fetch('node_preview.tpl');


?>
