<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 March 2017 at 10:43:28 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/


  if ($user->get('User Type') != 'Agent') {
       $html= 'Forbidden';
       return;
    }

$tab     = 'agent_parts';
$ar_file = 'ar_suppliers_tables.php';
$tipo    = 'agent_parts';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview')
    )


);

$table_filters = array(
    'reference' => array(
        'label' => _('Reference'),
        'title' => _('Part reference')
    ),

);

$parameters = array(
    'parent'     => 'agent',
    'parent_key' => $user->get('User Parent Key'),

);


include 'utils/get_table_html.php';


?>
