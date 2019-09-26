<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 May 2018 at 21:31:18 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$website = $state['_object'];

if ($user->can_supervisor('websites') and in_array($website->get('Website Store Key'), $user->stores)) {

    $theme = $website->get('Website Theme');


    $smarty->assign('website', $website);
    $smarty->assign('theme', $theme);


    $smarty->assign('settings', $website->settings);


    $mobile_style_values = array();
    foreach ($website->mobile_style as $value) {
        if ($value[0] == '.header-logo') {

            if ($value[1] == 'padding-left') {
                $mobile_style_values['header_text_padding'] = floatval($value[2]);
            } elseif ($value[1] == 'background-image') {
                $mobile_style_values['header_background_image'] = preg_replace('/\"?\)$/', '', preg_replace('/^url\(\"?/', '', $value[2]));
            }

        }
    }
    $smarty->assign('mobile_style_values', $mobile_style_values);


    $html = $smarty->fetch('control.website.header.tpl');

} else {
    $html = '<div style="padding:20px"><i class="fa error fa-octagon padding_right_5" ></i>  '.("Sorry you dont have permission to access this area").'</div>';
}