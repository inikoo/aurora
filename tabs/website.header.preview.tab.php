<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 2 June 2016 at 15:13:50 CEST, Mijas Costa, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once('class.WebsiteNode.php');

$node=new WebsiteNode('website_code',$state['key'],'p.home');
$page=get_object('webpage',$node->get_webpage_key());



$request=preg_replace('/\./','/',strtolower($node->get('Code')));
$smarty->assign('request', $request);


$smarty->assign('node', $node);

$smarty->assign('page', $page);
$smarty->assign('key', $state['key']);

$smarty->assign('state', $state);


$html=$smarty->fetch('header_preview.tpl');







?>
