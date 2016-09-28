<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 22 September 2016 at 17:23:22 GMT+8, Cyberjaya , Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';


$smarty->assign('_request', $_SERVER['REQUEST_URI']);

$smarty->display('aurora.tpl');

?>
