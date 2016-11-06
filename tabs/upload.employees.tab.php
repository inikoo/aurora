<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 March 2016 at 21:50:54 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'upload.employees';
$ar_file = 'ar_upload_tables.php';
$tipo    = 'records';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'object_name' => array(
        'label' => _('Name'),
        'title' => _('Employee code')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$smarty->assign('tipo', $tipo);


$smarty->assign('title', _('Upload records'));

include('utils/get_table_html.php');


?>
