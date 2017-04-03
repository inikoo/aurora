<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 March 2016 at 09:45:42 GMT+8, Yiwu, China

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

$account    = new Account();

$current_step = $data['section'];

if($account->id) {

    $setup_data = $account->get('Setup Metadata');


    //$smarty->assign('setup_data', $setup_data);


    $nav_menu = array();


    foreach ($setup_data['steps'] as $step_code => $step_data) {

        if ($step_code == 'root_user') {
            $nav_menu[] = array(
                '<i class="fa fa-check fa-fw '.($step_data['setup'] ? 'highlight' : 'discreet').'"></i>',
                _('Root account'),
                '/account/setup/root_user',
                'setup_root_user',
                'module',
                ''
            );
        }elseif ($step_code == 'setup_account') {
            $nav_menu[] = array(
                '<i class="fa fa-check fa-fw '.($step_data['setup'] ? 'highlight' : 'discreet').'"></i>',
                _('Setup account'),
                '/account/setup/setup_account',
                'setup_account',
                'module',
                ''
            );
        } elseif ($step_code == 'add_employees') {
            $nav_menu[] = array(
                '<i class="fa fa-check fa-fw '.($step_data['setup'] ? 'highlight' : 'discreet').'"></i>',
                _('Add employees'),
                '/account/setup/add_employees',
                'setup_add_employees',
                'module',
                ''
            );
        } elseif ($step_code == 'add_warehouse') {
            $nav_menu[] = array(
                '<i class="fa fa-check fa-fw '.($step_data['setup'] ? 'highlight' : 'discreet').'"></i>',
                _('Set warehouse'),
                '/account/setup/add_warehouse',
                'setup.add_warehouse',
                'module',
                ''
            );
        } elseif ($step_code == 'add_store') {
            $nav_menu[] = array(
                '<i class="fa fa-check fa-fw '.($step_data['setup'] ? 'highlight' : 'discreet').'"></i>',
                _('Set store'),
                '/account/setup/add_store',
                'setup.add_store',
                'module',
                ''
            );
        }

    }

}else{
    $nav_menu=array();
}


$smarty->assign('current_step', $current_step);

$smarty->assign('nav_menu', $nav_menu);

$html = $smarty->fetch('setup_menu.tpl');

?>
