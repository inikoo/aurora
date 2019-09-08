<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Sun 07 April 2019 09:47:09 MYT, Cyberjaya, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
*/


$smarty->assign('website',$state['_object']);
$html = $smarty->fetch('dashboard/website.analytics.dbard.tpl');
