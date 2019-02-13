<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 February 2019 at 12:39:27 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/


$mailshot = $state['_object'];


$email_campaign_type = get_object('email_campaign_type', $mailshot->get('Email Campaign Email Template Type Key'));


$control_blueprint_template = 'control.email_campaign.blueprints.tpl';


$content = $email_campaign_type->get('Content Data');

$smarty->assign('control_blueprint_template', $control_blueprint_template);


$email_template = get_object('Email_Template', $mailshot->get('Email Campaign Email Template Key'));


$smarty->assign('email_template_redirect', '&tab=mailshot.workshop');


$smarty->assign('control_template', 'control.email_template_text.tpl');

$smarty->assign('email_template_key', $email_template->id);


$smarty->assign('blueprints_redirect', 'mailshot.email_blueprints');


$smarty->assign('email_template', $email_template);




$html = $smarty->fetch('email_template.workshop_text.tpl');
