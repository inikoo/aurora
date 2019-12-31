<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 February 2019 at 12:47:52 GMT+8
 Copyright (c) 2019, Inikoo

 Version 3

*/

/**
 * @var $mailshot \EmailCampaign
 */
$mailshot=$state['_object'];

$email_template=get_object('EmailTemplate',$mailshot->get('Email Campaign Email Template Key'));
$email_template_type=get_object('EmailTemplateType',$mailshot->get('Email Campaign Email Template Type Key'));



$tab     = 'mailshot.email_blueprints';
$ar_file = 'ar_email_template_tables.php';
$tipo    = 'email_blueprints';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'name' => array('label' => _('Name')),
);

$parameters = array(
    'parent'     => 'Mailshot',
    'parent_key' => $mailshot->id,
    'email_template_type_code'=>$email_template_type->get('Email Campaign Type Code'),
    'email_template_type_key'=>$mailshot->get('Email Campaign Email Template Type Key'),
    'redirect' => base64_url_encode('mailshot.workshop'),
);




$smarty->assign('scope', 'Mailshot');
$smarty->assign('scope_key', $mailshot->id);

$smarty->assign('role', $mailshot->get('Email Campaign Type'));
$smarty->assign('email_template_redirect', '&tab=mailshot.workshop');
$smarty->assign('table_top_template', 'email_blueprints.showcase.tpl');



include 'utils/get_table_html.php';


