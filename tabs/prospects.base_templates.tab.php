
<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 June 2018 at 16:29:26 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/




$email_template=$state['_object'];
include_once 'class.EmailCampaignType.php';

$email_campaign_type=new EmailCampaignType('code_store','Invite Mailshot',$state['store']->id);

$tab     = 'prospects.base_templates';
$ar_file = 'ar_email_template_tables.php';
$tipo    = 'email_templates_for_overwrite';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'name' => array('label' => _('Name')),
);


$smarty->assign('show_back_button', false);

$smarty->assign('email_template', $email_template);



include_once 'class.EmailCampaignType.php';
$email_campaign_type = new EmailCampaignType('code_store', 'Invite Mailshot', $state['store']->id);



$parameters = array(
    'parent'     => 'EmailCampaignType',
    'parent_key' => $email_campaign_type->id,
    'redirect' => base64_url_encode('&tab=prospects.template.workshop'),
    'email_template_key' => $email_template->id,
    'store_key' => $email_campaign_type->get('Store Key'),

);



$smarty->assign('table_top_template', 'prospects.base_blueprints.tpl');

include 'utils/get_table_html.php';

?>
