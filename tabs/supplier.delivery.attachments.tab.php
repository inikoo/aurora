<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 327 August 2018 at 16:54:49 GMT+8, Kuala Lumpur, Malysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'supplier.delivery.attachments';
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
    'reference' => strtolower($state['_object']->get('Supplier Delivery Parent'))."/".$state['_object']->get('Supplier Delivery Parent Key')."/delivery/".$state['key']."/attachment/new"
);
$smarty->assign('table_buttons', $table_buttons);

include('utils/get_table_html.php');


