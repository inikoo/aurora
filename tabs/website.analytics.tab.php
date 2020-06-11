<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Sun 07 April 2019 09:47:09 MYT, Cyberjaya, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
*/


if(!$state['_object']->id){

    $html='<div style="padding:20px">'.sprintf(_('Website not set up, create one %s'),'<span class="marked_link" onClick="change_view(\'/store/new\')" >'._('here').'</span>').'</div>';
    return;
}


$smarty->assign('website',$state['_object']);


$html = $smarty->fetch('dashboard/website.analytics.dbard.tpl');
