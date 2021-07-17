<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 May 2018 at 14:20:42 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

if (in_array($state['store']->id, $user->stores) and $user->can_view('customers')) {

    $tab     = 'prospects';
    $ar_file = 'ar_customers_tables.php';
    $tipo    = 'prospects';

    $default = $user->get_tab_defaults($tab);


    $table_views = array(
        'overview' => array(
            'label' => _('Overview'),
            'title' => _('Overview')
        ),
        'contact'  => array(
            'label' => _('Contact'),
            'title' => _('Contact details')
        ),


    );

    $table_filters = array(

        'email'        => array(
            'label' => _('Email'),
            'title' => _('Prospect email')
        ),
        'name'         => array(
            'label' => _('Name'),
            'title' => _('Prospect name')
        )

    );

    $parameters = array(
        'parent'     => 'store',
        'parent_key' => $state['parent_key'],

    );


    include_once 'conf/export_edit_template_fields.php';
    $edit_table_dialog = array(
        'new_item'         => array(
            'icon'      => 'plus',
            'title'     => _("New prospect"),
            'reference' => "prospects/".$state['store']->id."/new"
        ),
        'upload_items'     => array(
            'icon'         => 'plus',
            'label'        => _("Upload prospects"),
            'template_url' => '/upload_arrangement.php?object=prospect&parent=store&parent_key='.$state['store']->id,

            'tipo'        => 'edit_objects',
            'parent'      => 'store',
            'parent_key'  => $state['store']->id,

            'object'      => 'prospect',
        ),


    );
    $smarty->assign('edit_table_dialog', $edit_table_dialog);

    $table_buttons   = array();


    $table_buttons[] = array(
        'icon'  => 'edit_add',
        'title' => _("Edit prospects"),
        'id'    => 'edit_dialog'
    );
    $smarty->assign('table_buttons', $table_buttons);


    include 'utils/get_table_html.php';


} else {
    try {
        $html = $smarty->fetch('access_denied');
    } catch (Exception $e) {
    }
}