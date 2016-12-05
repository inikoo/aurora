<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 May 2016 at 13:02:47 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'pcommon.php';

$smarty->assign('_request', $_SERVER['REQUEST_URI']);
$smarty->assign('_PREVIEW', _PREVIEW);

$smarty->display('ecom/papp.tpl');

?>
