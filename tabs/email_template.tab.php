<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 September 2016 at 13:52:10 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

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


if ($email_template_key) {

    $smarty->assign('control_template', $control_template);

    $smarty->assign('email_template_key', $email_template_key);


    if ($scope_metadata['welcome_email']['published_key']) {
        $smarty->assign('change_template_label', _('Reformat'));

    } else {
        $smarty->assign('change_template_label', _('Start again'));

    }


    include_once 'class.Email_Template.php';
    $email_template = new Email_Template($email_template_key);

    $smarty->assign('email_template', $email_template);



    $smarty->assign('send_email_to', $user->get_staff_email());


    $html = $smarty->fetch('email_template.tpl');
} else {

    $tab     = 'transactional.email_blueprints';
    $ar_file = 'ar_email_template_tables.php';
    $tipo    = 'email_blueprints';

    $default = $user->get_tab_defaults($tab);

    $table_views = array();

    $table_filters = array(
        'name' => array('label' => _('Name')),
    );

    if ($scope_metadata['welcome_email']['key']) {
        $show_back_button = true;

    } else {
        $show_back_button = false;

    }
    $smarty->assign('show_back_button', $show_back_button);


    $parameters = array(
        'parent'     => 'Welcome',
        'parent_key' => $state['key'],

    );

    $smarty->assign('role', $role);
    $smarty->assign('scope', 'Webpage');
    $smarty->assign('scope_key', $state['key']);

    $smarty->assign('table_top_template', 'email_blueprints.base.tpl');

    include 'utils/get_table_html.php';

}


?>
