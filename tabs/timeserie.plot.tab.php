<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 11 January 2016 at 14:41:19 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$smarty->assign('timeseries', $state['_object']);


$html=$smarty->fetch('plot.tpl');



?>
