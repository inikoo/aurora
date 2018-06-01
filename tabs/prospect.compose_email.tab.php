<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 May 2018 at 13:07:19 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


include_once 'class.EmailCampaignType.php';
$email_campaign_type = new EmailCampaignType('code_store', 'Invite Mailshot', $state['store']->id);

$smarty->assign('direct_email', true);
$smarty->assign('recipient', $state['_parent']->get_object_name());
$smarty->assign('recipient_key',  $state['_parent']->id);


$control_template           = 'control.email_template.tpl';
$control_blueprint_template = 'control.email_campaign.blueprints.tpl';


$content = $email_campaign_type->get('Content Data');

$smarty->assign('control_template', $control_template);
$smarty->assign('control_blueprint_template', $control_blueprint_template);


$email_template = get_object('Email_Template', $email_campaign_type->get('Email Campaign Type Email Template Key'));

$smarty->assign('email_template_redirect', '&tab=email_campaign_type.email_template');


if ($email_template->id and !($email_template->get('Email Template Type') == 'HTML' and $email_template->get('Email Template Editing JSON') == '')) {

    $smarty->assign('control_template', $control_template);

    $smarty->assign('email_template_key', $email_template->id);


    $smarty->assign('change_template_label', _('Start again'));


    $smarty->assign('email_template', $email_template);


    $send_email_to = $user->get_staff_email();


    $merge_tags     = '';
    $merge_contents = '';



    $smarty->assign('merge_tags', $merge_tags);
    $smarty->assign('merge_contents', $merge_contents);

    $smarty->assign('send_email_to', $send_email_to);


    $html = $smarty->fetch('email_template.tpl');
} else {


    $html= '<div style="margin:30px;">
            <h1>'._('Invitation email template not configured').'</h1>
            <div style="margin-top:20px">
            <span class="button" style="border:1px solid #ccc;padding:10px;" onclick="change_view(\'prospects/'.$state['_parent']->get('Store Key').'\',{ tab:\'prospects.email_template\'})">'._('Configure it here').'</span>
            </div>
            </div>';





}


?>
