<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 February 2018 at 17:52:40 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$email_template=get_object('EmailTemplate',$state['_object']->get('Email Campaign Email Template Key'));
$email_template_type=get_object('EmailTemplateType',$state['_object']->get('Email Campaign Email Template Type Key'));

//$control_blueprint_template = 'control.email_campaign_type.blueprints.tpl';


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
    'parent_key' => $email_template_type->id,
    'redirect' => base64_url_encode('email_campaign.workshop'),
);


$smarty->assign('scope', 'EmailTemplate');
$smarty->assign('scope_key', $email_template->id);

$smarty->assign('role', $email_template->get('Email Template Role'));

$smarty->assign('blueprints_redirect', 'email_campaign.workshop');



$smarty->assign('email_template_redirect', '&tab=email_campaign.workshop');
$smarty->assign('table_top_template', 'email_blueprints.showcase.tpl');

$smarty->assign(
    'js_code', 'js/injections/edit_blueprints.'.(_DEVEL ? '' : 'min.').'js'
);


include 'utils/get_table_html.php';


?>
