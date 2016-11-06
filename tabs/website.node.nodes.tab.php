<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 June 2016 at 10:18:07 CEST, Mijas Costa, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'website.node.nodes';
$ar_file = 'ar_websites_tables.php';
$tipo    = 'nodes';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'code'  => array('label' => _('Code')),
    'title' => array('label' => _('Name')),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
);

$smarty->assign('parent_node_key', $state['key']);

include('utils/get_table_html.php');


?>
