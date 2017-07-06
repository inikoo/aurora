<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 July 2017 at 22:31:45 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


$webpage = $state['_object'];

if ($webpage->get('Webpage Code') == 'register.sys') {

    $content        = $webpage->get('Content Data');
    $scope_metadata = $webpage->get('Scope Metadata');

    $email_template_key = $scope_metadata['welcome_email']['key'];
    $role               = 'Welcome';

    $control_template = 'control.welcome_mail.tpl';

    $control_blueprint_template = 'control.blueprints.welcome_mail.tpl';

    $smarty->assign('control_template', $control_template);
    $smarty->assign('control_blueprint_template', $control_blueprint_template);


} else {
    return;
}


$tab     = 'transactional.email_blueprints';
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

if ($scope_metadata['welcome_email']['key']) {
    $show_back_button = true;

} else {
    $show_back_button = false;

}
$smarty->assign('show_back_button', $show_back_button);


$smarty->assign('table_top_template', 'email_blueprints.base.tpl');

$smarty->assign(
    'js_code', 'js/injections/edit_blueprints.'.(_DEVEL ? '' : 'min.').'js'
);


include 'utils/get_table_html.php';


?>
