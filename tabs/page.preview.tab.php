<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 1 June 2016 at 09:58:41 CEST, Mijas Costa, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/



$smarty->assign('object', $state['_object']);
$smarty->assign('key', $state['key']);

$smarty->assign('state', $state);


$html=$smarty->fetch('page_preview.tpl');







?>
