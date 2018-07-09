<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 February 2018 at 22:46:50 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$email_campaign = $state['_object'];


$email_campaign_type = get_object('email_campaign_type', $email_campaign->get('Email Campaign Email Template Type Key'));


$control_blueprint_template = 'control.email_campaign.blueprints.tpl';


$content = $email_campaign_type->get('Content Data');

$smarty->assign('control_blueprint_template', $control_blueprint_template);


$email_template = get_object('Email_Template', $email_campaign->get('Email Campaign Email Template Key'));


$smarty->assign('email_template_redirect', '&tab=email_campaign.workshop');


if ($email_template->id and !($email_template->get('Email Template Type') == 'HTML' and $email_template->get('Email Template Editing JSON') == '')) {

    $smarty->assign('control_template', 'control.email_template.tpl');

    $smarty->assign('email_template_key', $email_template->id);


    $smarty->assign('blueprints_redirect', 'email_campaign.email_blueprints');


    $smarty->assign('email_template', $email_template);


    $send_email_to = $user->get_staff_email();


    $merge_tags     = '';
    $merge_contents = '';


    if ($email_template->get('Email Template Role') == 'Password Reminder') {
        $merge_tags = ",{ name: '"._('Reset password URL')."',value: '[Reset_Password_URL]'}";

    } elseif ($email_template->get('Email Template Role') == 'Order Confirmation') {
        $merge_tags     = ",{ name: '"._('Order number')."',value: '[Order Number]'},{ name: '"._('Order Amount')."',value: '[Order Amount]'}";
        $merge_contents = "{ name: '"._('Payment information')."',value: '[Pay Info]'},{ name: '"._('Order')."',value: '[Order]'}";

    } elseif ($email_template->get('Email Template Role') == 'OOS Notification') {
        $merge_tags = ",{ name: '"._('Back in stock products')."',value: '[Products]'}";

    } elseif ($email_template->get('Email Template Role') == 'GR Reminder') {
        $merge_tags = ",{ name: '"._('Last order number')."',value: '[Order Number]'},{ name: '"._('Last order date')."',value: '[Order Date]'},
                { name: '"._('Last order date + n days (Replace n for a number, default 30)')."',value: '[Order Date + n days]'},
                 { name: '"._('Last order date + n weeks (Replace n for a number, default 1)')."',value: '[Order Date + n weeks]'},
                  { name: '"._('Last order date + n months (Replace n for a number, default 1)')."',value: '[Order Date + n months]'}
                ";

        $merge_contents = "{ name: '"._('Unsubscribe')."',value: '[Unsubscribe]'}";


    }
    //http://aw.inikoo.com/order.php?id=2287930

    $smarty->assign('merge_tags', $merge_tags);
    $smarty->assign('merge_contents', $merge_contents);

    $smarty->assign('send_email_to', $send_email_to);


    $html = $smarty->fetch('email_template.workshop.tpl');
} else {


    $smarty->assign('show_back_button', false);
    $smarty->assign('role', $email_campaign_type->get('Email Campaign Type Code'));
    $smarty->assign('scope', 'EmailCampaign');
    $smarty->assign('scope_key', $email_campaign->id);

    $html_email_blueprints = $smarty->fetch('email_blueprints.showcase.tpl');


    $tab     = 'email_campaign_type.email_blueprints';
    $ar_file = 'ar_email_template_tables.php';
    $tipo    = 'email_blueprints';

    $default = $user->get_tab_defaults($tab);

    $table_views = array();

    $table_filters = array(
        'name' => array('label' => _('Name')),
    );


    $parameters = array(
        'parent'     => 'EmailCampaignType',
        'parent_key' => $email_campaign_type->id,
        'redirect'   => base64_url_encode('email_campaign.workshop'),


    );


    $smarty->assign(
        'js_code', 'js/injections/edit_blueprints.'.(_DEVEL ? '' : 'min.').'js'
    );

    include 'utils/get_table_html.php';
    $html = $html_email_blueprints.$html;


}
