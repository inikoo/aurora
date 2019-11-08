<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  08 November 2019  11:14::10  +0100, Mijas Costa, Spain
 Copyright (c) 2019, Inikoo

 Version 3

*/

$tab     = 'packer.feedback';
$ar_file = 'ar_hr_tables.php';
$tipo    = 'packer_feedback';

$default = $user->get_tab_defaults($tab);


$table_views = array(


);

$table_filters = array(
    'reference' => array(
        'label' => _('Delivery note number'),
        'title' => _('Delivery note number'),
    ),

);

$parameters = array(
    'parent'     =>'packer',
    'parent_key' => $state['key'],

);

$table_buttons = array();

include 'utils/get_table_html.php';