<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 December 2016 at 13:06:18 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'website.footer.versions';
$ar_file = 'ar_websites_tables.php';
$tipo    = 'footers';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'code' => array(
        'label' => _('Code')
    )

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
);


include('utils/get_table_html.php');



?>