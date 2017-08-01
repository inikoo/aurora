<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 July 2017 at 22:31:45 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


$webpage        = $state['_object'];
$scope_metadata = $webpage->get('Scope Metadata');

if ($webpage->get('Webpage Code') == 'register.sys') {
    //$content        = $webpage->get('Content Data');
    $email_template_key = $scope_metadata['emails']['welcome']['key'];
    $role               = 'Welcome';
    // $control_template = 'control.email_template.welcome.tpl';
    // $smarty->assign('control_template', $control_template);
} elseif ($webpage->get('Webpage Code') == 'login.sys') {

    $email_template_key = $scope_metadata['emails']['reset_password']['key'];
    $role               = 'Reset_Password';

} elseif ($webpage->get('Webpage Code') == 'checkout.sys') {

    $email_template_key = $scope_metadata['emails']['order_confirmation']['key'];
    $role               = 'Order_Confirmation';

} else {
    return;
}

$control_blueprint_template = 'control.webpage.blueprints.tpl';


$tab     = 'webpage.email_blueprints';
$ar_file = 'ar_email_template_tables.php';
$tipo    = 'email_blueprints';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'name' => array('label' => _('Name')),
);

$parameters = array(
    'parent'     => 'Welcome',
    'parent_key' => $state['key'],

);

$smarty->assign('role', $role);
$smarty->assign('scope', 'Webpage');
$smarty->assign('scope_key', $state['key']);

if ($email_template_key) {
    $show_back_button = true;

} else {
    $show_back_button = false;

}
$smarty->assign('show_back_button', $show_back_button);

$smarty->assign('email_template_redirect', '&subtab=webpage.email_template');
$smarty->assign('table_top_template', 'email_blueprints.showcase.tpl');

$smarty->assign(
    'js_code', 'js/injections/edit_blueprints.'.(_DEVEL ? '' : 'min.').'js'
);


include 'utils/get_table_html.php';


?>
