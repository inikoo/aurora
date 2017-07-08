<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 September 2016 at 13:52:10 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$webpage = $state['_object'];

$scope_metadata = $webpage->get('Scope Metadata');


if ($webpage->get('Webpage Code') == 'register.sys') {


    $email_template_key           = $scope_metadata['emails']['welcome']['key'];
    $published_email_template_key = $scope_metadata['emails']['welcome']['published_key'];

    $role = 'Welcome';



} elseif ($webpage->get('Webpage Code') == 'login.sys') {


    $email_template_key           = $scope_metadata['emails']['reset_password']['key'];
    $published_email_template_key =  $scope_metadata['emails']['reset_password']['published_key'];

    $role = 'Reset_Password';




} elseif ($webpage->get('Webpage Code') == 'checkout.sys') {


    $email_template_key           = $scope_metadata['emails']['order_confirmation']['key'];
    $published_email_template_key =  $scope_metadata['emails']['order_confirmation']['published_key'];

    $role = 'Order_Confirmation';




} else {
    return;
}

$control_template = 'control.email_template.tpl';


$control_blueprint_template = 'control.webpage.blueprints.tpl';


$content = $webpage->get('Content Data');

$smarty->assign('control_template', $control_template);
$smarty->assign('control_blueprint_template', $control_blueprint_template);


include_once 'class.Email_Template.php';
$email_template = new Email_Template($email_template_key);

$smarty->assign('email_template_redirect', '&subtab=webpage.email_template');



if ($email_template->id and ! ($email_template->get('Email Template Type')=='HTML' and $email_template->get('Email Template Editing JSON')=='' )  ) {

    $smarty->assign('control_template', $control_template);

    $smarty->assign('email_template_key', $email_template_key);


    if ($published_email_template_key) {
        $smarty->assign('change_template_label', _('Reformat'));

    } else {
        $smarty->assign('change_template_label', _('Start again'));

    }




    $smarty->assign('email_template', $email_template);


    $smarty->assign('send_email_to', $user->get_staff_email());


    $html = $smarty->fetch('email_template.tpl');
} else {

    $tab     = 'webpage.email_blueprints';
    $ar_file = 'ar_email_template_tables.php';
    $tipo    = 'email_blueprints';

    $default = $user->get_tab_defaults($tab);

    $table_views = array();

    $table_filters = array(
        'name' => array('label' => _('Name')),
    );


    $smarty->assign('show_back_button',false);


    $parameters = array(
        'parent'     => 'Welcome',
        'parent_key' => $state['key'],

    );

    $smarty->assign('role', $role);
    $smarty->assign('scope', 'Webpage');
    $smarty->assign('scope_key', $state['key']);




    $smarty->assign('table_top_template', 'email_blueprints.showcase.tpl');

    include 'utils/get_table_html.php';

}


?>
