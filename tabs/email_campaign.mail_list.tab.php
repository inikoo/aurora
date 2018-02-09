<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 February 2018 at 14:31:29 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/



switch ($state['_object']->get('Email Campaign Type')) {
    case 'AbandonedCart':

        $tab     = 'abandoned_cart.mail_list';
        $ar_file = 'ar_customers_tables.php';
        $tipo    = 'abandoned_cart';
        break;
    default:

        $tab     = 'email_campaign.mail_list';
        $ar_file = 'ar_customers_tables.php';
        $tipo    = 'mail_list';
        break;
}



$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    )

);

$table_filters = array(
    'name'         => array(
        'label' => _('Name'),
        'title' => _('Customer name')
    ),
    'email'        => array(
        'label' => _('Email'),
        'title' => _('Customer email')
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
    'parent'     =>$state['object'],
    'parent_key' => $state['key'],

);


$table_buttons   = array();

include 'utils/get_table_html.php';


?>
