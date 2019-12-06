<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  06 December 2019  09:35::59  +0100, Mijas Costa, Spain
 Copyright (c) 2019, Inikoo

 Version 3

*/


$email_template_type = get_object('email_template_type-code_store', 'Invite Full Mailshot|'.$state['store']->id);

$parameters = array(
    'parent'     => 'email_campaign_type',
    'parent_key' => $email_template_type->id

);

$tab     = 'email_campaign_type.mailshots';
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
        'label' => _('Subject'),
        'title' => _('Subject')
    )
);

$table_buttons = array();

$table_buttons[] = array(
    'icon'  => 'plus',
    'title' => _('New mailshot'),
    'id'    => 'new_prospect_mailshot',
    'class' => 'new_marketing_mailshot',
    'data_attr'  => array(
        'parent'     => 'EmailCampaignType',
        'parent_key' => $email_template_type->id,
        'scope'      => 'Prospects',
        'list'       => '',
        'asset'      => '',
        'scope_type' => '',
        'name'       => date('Y.m.d').' '.$state['store']->get('Code'),
    )

);

$smarty->assign('table_buttons', $table_buttons);
include 'utils/get_table_html.php';
