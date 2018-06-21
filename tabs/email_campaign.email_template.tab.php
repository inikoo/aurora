<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 February 2018 at 22:46:50 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$email_campaign = $state['_object'];

$scope_metadata = $email_campaign->get('Scope Metadata');



$control_template = 'control.email_template.tpl';


$control_blueprint_template = 'control.email_campaign.blueprints.tpl';


$content = $email_campaign->get('Content Data');

$smarty->assign('control_template', $control_template);
$smarty->assign('control_blueprint_template', $control_blueprint_template);



$email_template = get_object('Email_Template',$email_campaign->get('Email Campaign Email Template Key'));

$smarty->assign('email_template_redirect', '&tab=email_campaign.email_template');



if ($email_template->id and !($email_template->get('Email Template Type') == 'HTML' and $email_template->get('Email Template Editing JSON') == '')) {

    $smarty->assign('control_template', $control_template);

    $smarty->assign('email_template_key', $email_template->id);


    if ($email_campaign->get('Email Campaign Published Email Template Key')) {
        $smarty->assign('change_template_label', _('Reformat'));

    } else {
        $smarty->assign('change_template_label', _('Start again'));

    }


    $smarty->assign('email_template', $email_template);


    $send_email_to = $user->get_staff_email();


    $merge_tags     = '';
    $merge_contents = '';


    if ($email_template->get('Email Template Role') == 'Password Reminder') {
        $merge_tags = ",{ name: '"._('Reset password URL')."',value: '[Reset_Password_URL]'}";

    } elseif ($email_template->get('Email Template Role') == 'Order Confirmation') {
        $merge_tags     = ",{ name: '"._('Order number')."',value: '[Order Number]'},{ name: '"._('Order Amount')."',value: '[Order Amount]'}";
        $merge_contents = "{ name: '"._('Payment information')."',value: '[Pay Info]'},{ name: '"._('Order')."',value: '[Order]'}";

    }


    $smarty->assign('merge_tags', $merge_tags);
    $smarty->assign('merge_contents', $merge_contents);

    $smarty->assign('send_email_to', $send_email_to);


    $html = $smarty->fetch('email_template.tpl');
} else {

    $tab     = 'email_campaign.email_blueprints';
    $ar_file = 'ar_email_template_tables.php';
    $tipo    = 'email_blueprints';

    $default = $user->get_tab_defaults($tab);

    $table_views = array();

    $table_filters = array(
        'name' => array('label' => _('Name')),
    );


    $smarty->assign('show_back_button', false);


    $parameters = array(
        'parent'     => 'EmailCampaign',
        'parent_key' => $state['key'],
        'redirect' => base64_url_encode('&tab=email_campaign.email_template'),


    );

    $smarty->assign('role', $email_campaign->get('Email Campaign Type'));
    $smarty->assign('scope', 'EmailCampaign');
    $smarty->assign('scope_key', $state['key']);


    $smarty->assign('table_top_template', 'email_blueprints.showcase.tpl');

    include 'utils/get_table_html.php';

}


?>
