<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 June 2018 at 14:24:01 GMT+8 Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

if (in_array($state['store']->id, $user->stores) and $user->can_view('customers')) {

    $tab     = 'prospects.email_templates';
    $ar_file = 'ar_customers_tables.php';
    $tipo    = 'prospects.email_templates';

    $default = $user->get_tab_defaults($tab);


    include_once 'class.EmailCampaignType.php';
    $email_campaign_type = new EmailCampaignType('code_store', 'Invite Mailshot', $state['parent_key']);


    $table_views = array(
        'overview' => array(
            'label' => _('Overview'),
            'title' => _('Overview')
        ),


    );

    $table_filters = array(
        'name' => array(
            'label' => _('Name'),
            'title' => _('Name')
        )

    );

    $parameters = array(
        'parent'     => 'EmailCampaignType',
        'parent_key' => $email_campaign_type->id,
        'store_key'  => $state['parent_key']

    );


    $table_buttons   = array();
    $table_buttons[] = array(
        'icon'      => 'plus',
        'title'     => _('New invitation template'),
        'reference' => "prospects/".$state['parent_key']."/template/new"
    );
    $smarty->assign('table_buttons', $table_buttons);


    include 'utils/get_table_html.php';


} else {
    $html = '<div style="padding: 20px"><i class="fa error fa-octagon " ></i>  '._('Access denied').'</div>';
}
