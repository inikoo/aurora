<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 May 2018 at 09:47:42 CEST, Mijas COsta, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'category.deals';
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
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$table_buttons   = array();
$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New offer'),
    'reference' => "products/".$state['parent_key']."/category/".$state['key']."/deal/new"
);
$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';


?>
