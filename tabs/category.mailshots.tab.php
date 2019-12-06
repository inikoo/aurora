<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 06-08-2019 11:57:44 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$tab     = 'category.mailshots';
$ar_file = 'ar_mailshots_tables.php';
$tipo    = 'mailshots';


$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    )

);

$table_filters = array(
    'name' => array(
        'label' => _('Name'),
        'title' => _('name')
    )

);


$table_buttons = array();


include_once 'class.EmailCampaignType.php';
$email_campaign_type = new EmailCampaignType('code_store', 'Marketing', $state['_object']->get('Store Key'));


if ( $state['_object']->properties('spread_marketing_customers') == '' or $state['_object']->properties('spread_marketing_customers_last_updated') < (gmdate('U') - 604800)) {
    $_customers = '';

    include_once 'utils/new_fork.php';
    $account = get_object('Account', 1);
    new_housekeeping_fork(
        'au_housekeeping', array(
        'type'   => 'update_marketing_customers',
        'tipo'   => 'spread',
        'object' => 'category',
        'key'    => $state['_object']->id,
    ), $account->get('Account Code')
    );

} else {
    $_customers = ' ('.$state['_object']->properties('spread_marketing_customers').' '._('customers').')';
}


$table_buttons[] = array(
    'icon'  => 'bomb',
    'title' => _('Create mail bomb').$_customers,
    'id'    => 'new_spread_mailshot',
    'class' => 'new_marketing_mailshot new_spread_mailshot',
    'data_attr'  => array(
        'parent'     => 'EmailCampaignType',
        'parent_key' => $email_campaign_type->id,
        'scope'      => 'Product_Category',
        'list'       => '',
        'asset'      => 'C'.$state['_object']->id,
        'scope_type' => 'Wide',
        'name'       => date('Y.m.d').' Bmb '.$state['_object']->get('Code'),

    )

);


if ($state['_object']->properties('donut_marketing_customers') == '' or $state['_object']->properties('donut_marketing_customers_last_updated') < (gmdate('U') - 604800)) {
    $_customers = '';
    include_once 'utils/new_fork.php';
    $account = get_object('Account', 1);
    new_housekeeping_fork(
        'au_housekeeping', array(
        'type'   => 'update_marketing_customers',
        'tipo'   => 'donut',
        'object' => 'category',
        'key'    => $state['_object']->id,
    ), $account->get('Account Code')
    );

} else {
    $_customers = ' ('.$state['_object']->properties('donut_marketing_customers').' '._('customers').')';
}


$table_buttons[] = array(
    'icon'  => 'scrubber',
    'title' => _('Create donut mailshot').$_customers,
    'id'    => 'new_donut_mailshot',
    'class' => 'new_marketing_mailshot new_donut_mailshot',

    'data_attr' => array(
        'parent'     => 'EmailCampaignType',
        'parent_key' => $email_campaign_type->id,
        'scope'      => 'Product_Category',
        'list'       => '',
        'asset'      => 'C'.$state['_object']->id,
        'scope_type' => 'Donut',
        'name'       => date('Y.m.d').' Don '.$state['_object']->get('Code'),

    )

);


if ($state['_object']->properties('targeted_marketing_customers') == '' or $state['_object']->properties('targeted_marketing_customers_last_updated') < (gmdate('U') - 604800)) {
    $_customers = '';
    include_once 'utils/new_fork.php';
    $account = get_object('Account', 1);
    new_housekeeping_fork(
        'au_housekeeping', array(
        'type'   => 'update_marketing_customers',
        'tipo'   => 'targeted',
        'object' => 'category',
        'key'    => $state['_object']->id,
    ), $account->get('Account Code')
    );

} else {
    $_customers = ' ('.$state['_object']->properties('targeted_marketing_customers').' '._('customers').')';
}


$table_buttons[] = array(
    'icon'  => 'bullseye-arrow',
    'title' => _('Create precision mailshot').$_customers,
    'id'    => 'new_targeted_mailshot',
    'class' => 'new_marketing_mailshot new_targeted_mailshot',

    'data_attr' => array(
        'parent'     => 'EmailCampaignType',
        'parent_key' => $email_campaign_type->id,
        'scope'      => 'Product_Category',
        'list'       => '',
        'asset'      => 'C'.$state['_object']->id,
        'scope_type' => 'Targeted',
        'name'       => date('Y.m.d').' Pre '.$state['_object']->get('Code'),

    )

);




$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';



