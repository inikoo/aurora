
<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 June 2018 at 16:29:26 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/
include_once 'class.EmailCampaignType.php';

$email_campaign_type=new EmailCampaignType('code_store','Invite Mailshot',$state['store']->id);


$role               = 'Invite Mailshot';

$control_blueprint_template = 'control.email_campaign.blueprints.tpl';


$tab     = 'email_campaign.email_blueprints';
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
    'redirect' => base64_url_encode('&tab=prospects.email_template'),
);


$smarty->assign('role', $role);
$smarty->assign('scope', 'EmailCampaignType');
$smarty->assign('scope_key',$email_campaign_type->id);

$show_back_button = true;


$smarty->assign('show_back_button', $show_back_button);

$smarty->assign('email_template_redirect', '&tab=prospects.email_template');
$smarty->assign('table_top_template', 'email_blueprints.showcase.tpl');

$smarty->assign(
    'js_code', 'js/injections/edit_blueprints.'.(_DEVEL ? '' : 'min.').'js'
);


include 'utils/get_table_html.php';


?>
