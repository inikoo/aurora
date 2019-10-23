<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 February 2018 at 17:29:38 GMT+8, Cyberjaya , Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$website = $state['_object'];

if ($user->can_supervisor('websites') and in_array($website->get('Website Store Key'), $user->stores)) {


    $theme   = $website->get('Website Theme');


    $smarty->assign('settings', $website->settings);


    $smarty->assign('website', $website);
    $smarty->assign('theme', $theme);

    //print_r($website->settings);


    $html = $smarty->fetch('control.website.colors.tpl');


}else{
    $html = '<div style="padding:20px"><i class="fa error fa-octagon padding_right_5" ></i>  '._("Sorry you don't have permission to access this area").'</div>';
}
