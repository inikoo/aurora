<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12:30 am Wednesday, 1 July 2020 (MYT) Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/


if($account->get('Account Manufacturers')==0){
    $html='<div style="padding:20px">'._('No production').'</div>';
    return;
}

$tab     = 'production.in_process_parts';
$ar_file = 'ar_production_tables.php';
$tipo    = 'in_process_parts';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'reference' => array(
        'label' => _('Reference'),
        'title' => _('Part reference')
    ),
);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);

include 'utils/get_table_html.php';

