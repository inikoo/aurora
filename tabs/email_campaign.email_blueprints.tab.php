<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 February 2018 at 17:52:40 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$email_template_key=false;

$email_campaign        = $state['_object'];
//$scope_metadata = $email_campaign->get('Scope Metadata');

switch ($email_campaign->get('Email Campaign Type')){

    case 'AbandonedCart':
   //     $email_template_key = $scope_metadata['emails']['welcome']['key'];
        $role               = 'AbandonedCart';
        break;
    default:
        return;
}


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
    'parent'     => 'EmailCampaign',
    'parent_key' => $state['key'],
    'redirect' => base64_url_encode('&tab=email_campaign.email_template'),
);


$smarty->assign('role', $role);
$smarty->assign('scope', 'EmailCampaign');
$smarty->assign('scope_key', $state['key']);

if ($email_template_key) {
    $show_back_button = true;

} else {
    $show_back_button = false;

}
$smarty->assign('show_back_button', $show_back_button);

$smarty->assign('email_template_redirect', '&tab=email_campaign.email_template');
$smarty->assign('table_top_template', 'email_blueprints.showcase.tpl');

$smarty->assign(
    'js_code', 'js/injections/edit_blueprints.'.(_DEVEL ? '' : 'min.').'js'
);


include 'utils/get_table_html.php';


?>
