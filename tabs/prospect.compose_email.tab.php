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


$content = $email_campaign_type->get('Content Data');

$smarty->assign('control_template', 'control.prospect.compose_email.tpl');


$email_template = get_object('Email_Template',  $state['_object']->id);



if ($email_template->id ) {


    $smarty->assign('email_template_key', $email_template->id);




    $smarty->assign('email_template', $email_template);




    $merge_tags     = '';
    $merge_contents = '';



    $smarty->assign('merge_tags', $merge_tags);
    $smarty->assign('merge_contents', $merge_contents);

    $smarty->assign('send_email_to', $user->get_staff_email());


    $html = $smarty->fetch('prospect.compose_email.tpl');
}


?>
