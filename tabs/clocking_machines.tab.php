<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Thu 3 Oct 2019 16:10:43 +0800 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

if ($user->can_view('staff')) {

    $tab     = 'clocking_machines';
    $ar_file = 'ar_hr_tables.php';
    $tipo    = 'clocking_machines';

    $default = $user->get_tab_defaults($tab);


    $table_views = array(
        'overview' => array(
            'label' => _('Overview'),
            'title' => _('Overview')
        ),


    );

    $table_filters = array(
        'code' => array(
            'label' => _('Code'),
            'title' => _('Time machine code')
        ),


    );

    $parameters = array(
        'parent'     => 'account',
        'parent_key' => 1

    );


    $table_buttons = array();

    if ($user->can_supervisor('staff')) {
        $table_buttons[] = array(
            'icon'      => 'plus',
            'title'     => _('New clocking-in machine'),
            'reference' => "clocking_machines/new"
        );
    }

    $smarty->assign('table_buttons', $table_buttons);


    include 'utils/get_table_html.php';

} else {
    $html = '<div style="padding: 20px"><i class="fa error fa-octagon " ></i>  '._('Access denied').'</div>';
}