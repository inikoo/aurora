<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 May 2017 at 11:59:06 GMT+8,  Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


$tab     = 'website.in_process_webpages';
$ar_file = 'ar_websites_tables.php';
$tipo    = 'in_process_webpages';

$default = $user->get_tab_defaults($tab);



$table_views = array();

$table_filters = array(
    'code'  => array('label' => _('Code')),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
);


$table_buttons   = array();
$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New Webpage'),
    'reference' => "website/".$state['key']."/webpage/new"
);
$smarty->assign('table_buttons', $table_buttons);


include('utils/get_table_html.php');


?>
