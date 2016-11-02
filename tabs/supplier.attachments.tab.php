<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 22 August 2016 at 17:54:26 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'supplier.attachments';
$ar_file = 'ar_attachments_tables.php';
$tipo    = 'attachments';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'caption' => array('label' => _('Caption')),
);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

$table_buttons   = array();
$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New attachment'),
    'reference' => $state['object']."/".$state['key']."/attachment/new"
);
$smarty->assign('table_buttons', $table_buttons);

include('utils/get_table_html.php');

?>
