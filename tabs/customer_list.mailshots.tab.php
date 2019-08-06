<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29-07-2019 15:15:00 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/


$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$tab     = 'customer_list.mailshots';
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
$email_campaign_type = new EmailCampaignType('code_store', 'Marketing', $state['_object']->get('List Parent Key'));



$table_buttons[] = array(
    'icon'  => 'plus',
    'title' => _('New marketing mailshot'),
    'id'    => 'new_mailshot',
    'class'=>'new_marketing_mailshot',

    'attr'  => array(
        'parent'     => 'EmailCampaignType',
        'parent_key' => $email_campaign_type->id,
        'scope'=>'Customer_List',
        'list'=>$state['key'],
        'asset'=>'',
        'scope_type'=>'',
        'name'=>date('Y.m.d ').' List '.$state['_object']->id,
    )

);

$smarty->assign(
    'js_code', 'js/injections/new_marketing_mailshot.'.(_DEVEL ? '' : 'min.').'js'
);


$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';



