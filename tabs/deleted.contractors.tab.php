<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 July 2016 at 15:25:05 GMT+8, Kuala Lumpur, Malaysa
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'deleted.contractors';
$ar_file = 'ar_hr_tables.php';
$tipo    = 'deleted.contractors';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'name' => array(
        'label' => _('Name'),
        'title' => _('Contractors name')
    ),

);

$parameters = array(
    'parent'     => 'account',
    'parent_key' => 1,

);


$smarty->assign('title', _('Deleted contractors'));
$smarty->assign('view_position', _('Deleted contractors'));


$smarty->assign('tipo', $tipo);


include 'utils/get_table_html.php';


?>
