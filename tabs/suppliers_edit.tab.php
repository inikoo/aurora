<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 May 2016 at 10:47:31 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'suppliers_edit';
$ar_file = 'ar_suppliers_tables.php';
$tipo    = 'suppliers_edit';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array('label' => _('Code, name')),
    'contact'  => array('label' => _('Email, telephones')),


);

$table_filters = array(
    'name'         => array(
        'label' => _('Name'),
        'title' => _('Supplier name')
    ),
    'email'        => array(
        'label' => _('Email'),
        'title' => _('Supplier email')
    ),
    'company_name' => array(
        'label' => _('Company name'),
        'title' => _('Company name')
    ),
    'contact_name' => array(
        'label' => _('Contact name'),
        'title' => _('Contact name')
    )

);

$parameters = array(
    'parent'     => '',
    'parent_key' => '',

);

$table_buttons = array();

$table_buttons[] = array(
    'icon'  => 'sign-out fa-flip-horizontal',
    'title' => _('Exit edit'),
    'id'    => 'exit_edit_table'
);

//$table_buttons[]=array('icon'=>'plus', 'title'=>_('New supplier'), 'reference'=>"suppliers/new");

$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';


?>
