<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 October 2015 at 14:18:07 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

if($user->can_view('Staff')) {

    $default = $user->get_tab_defaults($tab);
    $ar_file = 'ar_hr_tables.php';
    if($user->can_edit('Staff')) {
        $tab  = 'employees';
        $tipo = 'employees';

        $table_views = array(
            'overview'      => array(
                'label' => _('Overview'),
                'title' => _('Overview')
            ),
            'personal_info' => array(
                'label' => _('Personal information'),
                'title' => _('Personal information')
            ),
            'employment'    => array(
                'label' => _('Employment'),
                'title' => _('Employment')
            ),
            'system_roles'  => array('label' => _('System roles')),
            'system_user'   => array('label' => _('System user'))

        );
    }else{
        $tab  = 'employees_restricted_view';
        $tipo = 'employees_restricted_view';

        $table_views = array(
            'overview'      => array(
                'label' => _('Overview'),
                'title' => _('Overview')
            ),


        );
    }




    $table_filters = array(
        'name' => array(
            'label' => _('Name'),
            'title' => _('Employee name')
        ),

    );

    $parameters = array(
        'parent'     => 'account',
        'parent_key' => 1,

    );


    $table_buttons   = array();



    $table_buttons[] = array(
        'icon'      => 'plus',
        'title'     => _('New employee'),
        'reference' => "employee/new"
    );

    if($user->can_edit('Staff')) {

        $smarty->assign('table_buttons', $table_buttons);
        $smarty->assign(
            'upload_file', array(
                             'tipo'       => 'upload_objects',
                             'parent'     => $state['parent'],
                             'parent_key' => $state['parent_key'],
                             'object'     => 'employee',
                             'label'      => _('Upload employees')

                         )
        );
    }

    $smarty->assign('tipo', $tipo);


    $smarty->assign('title', _('Employees'));

    include('utils/get_table_html.php');

}else{
    $html='<div style="padding: 20px"><i class="fa error fa-octagon " ></i>  '._('Access denied').'</div>';

}

