<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 September 2018 at 09:28:42 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$tab     = 'prospect_agent.prospects';
$ar_file = 'ar_customers_tables.php';
$tipo    = 'prospects';

$default = $user->get_tab_defaults($tab);


//print_r($default);

$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
    'contact'  => array(
        'label' => _('Contact'),
        'title' => _('Contact details')
    ),



);

$table_filters = array(
    'name'         => array(
        'label' => _('Name'),
        'title' => _('Prospect name')
    ),
    'email'        => array(
        'label' => _('Email'),
        'title' => _('Prospect email')
    ),
    'company_name' => array(
        'label' => _('Company name'),
        'title' => _('Company name')
    ),
    'contact_name' => array(
        'label' => _('Contact name'),
        'title' => _('Contact name')
    )

);


$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


include 'utils/get_table_html.php';


?>
