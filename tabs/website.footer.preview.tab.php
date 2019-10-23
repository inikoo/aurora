<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 June 2016 at 13:45:01 CEST, Mijas Costa, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

$website = $state['_object'];

if ($user->can_supervisor('websites') and in_array($website->get('Website Store Key'), $user->stores)) {

    $theme = $state['_object']->get('Website Theme');

    $smarty->assign('website', $state['_object']);
    $smarty->assign('theme', $theme);

    $html = $smarty->fetch('footer_preview.tpl');


} else {
    $html = '<div style="padding:20px"><i class="fa error fa-octagon padding_right_5" ></i>  '._("Sorry you don't have permission to access this area").'</div>';
}

