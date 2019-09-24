<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 May 2018 at 14:20:42 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

if(in_array($state['store']->id,$user->stores) and $user->can_view('customers') ){

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
    'name'         => array(
        'label' => _('Name'),
        'title' => _('Prospect name')
    ),
    'email'        => array(
        'label' => _('Email'),
        'title' => _('Prospect email')
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
    'parent'     => 'store',
    'parent_key' => $state['parent_key'],

);


$table_buttons   = array();
$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New prospect'),
    'reference' => "prospects/".$state['parent_key']."/new"
);
$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';



}else{
    $html='<div style="padding: 20px"><i class="fa error fa-octagon " ></i>  '._('Access denied').'</div>';
}