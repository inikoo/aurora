<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 February 2018 at 22:46:50 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$mailshot = $state['_object'];


$email_campaign_type = get_object('email_campaign_type', $mailshot->get('Email Campaign Email Template Type Key'));


$control_blueprint_template = 'control.email_campaign.blueprints.tpl';


$content = $email_campaign_type->get('Content Data');

$smarty->assign('control_blueprint_template', $control_blueprint_template);


$email_template = get_object('Email_Template', $mailshot->get('Email Campaign Email Template Key'));


$smarty->assign('email_template_redirect', '&tab=mailshot.workshop');


$smarty->assign('control_template', 'control.mailshot.email_template.tpl');

$smarty->assign('email_template_key', $email_template->id);


$smarty->assign('blueprints_redirect', 'mailshot.email_blueprints');


$smarty->assign('email_template', $email_template);


$send_email_to = $user->get_staff_email();


$merge_tags     = '';
$merge_contents = '';



if ($email_template->get('Email Template Role') == 'OOS Notification') {
    $merge_tags = ",{ name: '"._('Back in stock products')."',value: '[Products]'}";

} elseif ($email_template->get('Email Template Role') == 'Newsletter') {
    $merge_contents = "{ name: '"._('Unsubscribe')."',value: '[Unsubscribe]'}";

} elseif ($email_template->get('Email Template Role') == 'Marketing') {
    $merge_contents = "{ name: '"._('Unsubscribe')."',value: '[Unsubscribe]'}";

} elseif ($email_template->get('Email Template Role') == 'AbandonedCart') {
    $merge_contents = "{ name: '"._('Unsubscribe')."',value: '[Unsubscribe]'}";

}elseif ($email_template->get('Email Template Role') == 'Invite Full Mailshot') {
    $merge_contents = "{ name: '"._('Stop junk mail')."',value: '[Stop_Junk_Mail]'}";
    $merge_tags = "{ name: '"._('Greetings')."',value: '[Greetings]'},
    { name: '"._('Prospect name')."',value: '[Prospect Name]'},
     { name: '"._('Contact name, Company')."',value: '[Name,Company]'},
      { name: '"._('Contact name')."',value: '[Name]'},
       { name: '"._('Signature')."',value: '[Signature Name]'},
    
    ";





    $smarty->assign('overwrite_merge_tags', true);


} elseif ($email_template->get('Email Template Role') == 'GR Reminder') {
    $merge_tags = ",{ name: '"._('Last order number')."',value: '[Order Number]'},{ name: '"._('Last order date')."',value: '[Order Date]'},
                { name: '"._('Last order date + n days (Replace n for a number, default 30)')."',value: '[Order Date + n days]'},
                 { name: '"._('Last order date + n weeks (Replace n for a number, default 1)')."',value: '[Order Date + n weeks]'},
                  { name: '"._('Last order date + n months (Replace n for a number, default 1)')."',value: '[Order Date + n months]'}
                ";

    $merge_contents = "{ name: '"._('Unsubscribe')."',value: '[Unsubscribe]'}";


}

$smarty->assign('merge_tags', $merge_tags);
$smarty->assign('merge_contents', $merge_contents);

$smarty->assign('send_email_to', $send_email_to);


$html = $smarty->fetch('email_template.workshop.tpl');
