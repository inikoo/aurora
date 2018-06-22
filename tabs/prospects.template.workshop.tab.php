<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 June 2018 at 03:59:52 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/




$email_template = $state['_object'];


$smarty->assign('email_template', $email_template);



//$smarty->assign('email_template_redirect', '&tab=email_campaign_type.email_template');

$smarty->assign('send_email_to', $user->get_staff_email());


if ($email_template->id and !($email_template->get('Email Template Type') == 'HTML' and $email_template->get('Email Template Editing JSON') == '')) {

    $smarty->assign('control_template', 'control.prospect.template.workshop.tpl');









    $merge_tags     = '';
    $merge_contents = '';




    $smarty->assign('merge_tags', $merge_tags);
    $smarty->assign('merge_contents', $merge_contents);


    $html = $smarty->fetch('prospect.template.workshop.tpl');



} else {



    $tab     = 'prospects.base_templates';
    $ar_file = 'ar_email_template_tables.php';
    $tipo    = 'base_templates';

    $default = $user->get_tab_defaults($tab);

    $table_views = array();

    $table_filters = array(
        'name' => array('label' => _('Name')),
    );


    $smarty->assign('show_back_button', false);

    include_once 'class.EmailCampaignType.php';
    $email_campaign_type = new EmailCampaignType('code_store', 'Invite Mailshot', $state['store']->id);



    $parameters = array(
        'parent'     => 'EmailCampaignType',
        'parent_key' => $email_campaign_type->id,
        'store_key' => $email_campaign_type->get('Store Key'),
        'redirect' => base64_url_encode('&tab=prospects.template.workshop'),
        'email_template_key' => $email_template->id,

    );



    $smarty->assign('table_top_template', 'prospects.base_blueprints.tpl');

    include 'utils/get_table_html.php';

}


?>
