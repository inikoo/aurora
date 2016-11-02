<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 9 May 2016 at 16:13:09 GMT+8, Puchong Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'deals';
$ar_file = 'ar_marketing_tables.php';
$tipo    = 'deals';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),

);

$table_filters = array(

    'name' => array('label' => _('Name')),

);

$parameters = array(
    'parent'     => 'store',
    'parent_key' => $state['parent_key'],

);


$table_buttons   = array();
$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New offer'),
    'reference' => "deals/".$state['parent_key']."/new"
);
$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';


?>
