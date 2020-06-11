<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Fri 11 Oct 2019 01:55:09 +0800 MYT, Kuala Lumpur, Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/


if($state['parent']=='website' and !empty($state['store']->id)  and  !$state['store']->get('Store Website Key') ){

    $html='<div style="padding:20px">'.sprintf(_('Website not set up, create one %s'),'<span class="marked_link" onClick="change_view(\'/store/'.$state['store']->id .'/settings\')" >'._('here').'</span>').'</div>';

    return;
}else{
    $html = '<div style="padding:20px;font-size:30px;opacity:.5;font-family: "Courier New", Courier, monospace"><i class="fal fa-bug"></i> 404</div>';
    return;
}









