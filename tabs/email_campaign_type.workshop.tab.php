<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 May 2018 at 15:18:13 CEST, Trnava, Slovakia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$email_campaign_type = $state['_object'];


$control_template           = 'control.email_template.tpl';
$control_blueprint_template = 'control.email_campaign.blueprints.tpl';


$content = $email_campaign_type->get('Content Data');

$smarty->assign('control_template', $control_template);
$smarty->assign('control_blueprint_template', $control_blueprint_template);


$email_template = get_object('Email_Template', $email_campaign_type->get('Email Campaign Type Email Template Key'));

//print_r($email_template);

$smarty->assign('email_template_redirect', '&tab=email_campaign_type.workshop');


if ($email_template->id and !($email_template->get('Email Template Type') == 'HTML' and $email_template->get('Email Template Editing JSON') == '')) {

    $smarty->assign('control_template', $control_template);

    $smarty->assign('email_template_key', $email_template->id);


    $smarty->assign('blueprints_redirect', 'email_campaign_type.email_blueprints');


    //print_r($email_template);
    //exit;
    $smarty->assign('email_template', $email_template);


    $send_email_to = $user->get_staff_email();


    $merge_tags     = '';
    $merge_contents = '';
    $special_links  = '';


    if ($email_template->get('Email Template Role') == 'Password Reminder') {
        $merge_tags = ",{ name: '"._('Reset password URL')."',value: '[Reset_Password_URL]'}";

    } elseif ($email_template->get('Email Template Role') == 'Order Confirmation') {
        $merge_tags     = ",{ name: '"._('Order number')."',value: '[Order Number]'},{ name: '"._('Order Amount')."',value: '[Order Amount]'},{ name: '"._('Invoice address')."',value: '[Invoice Address]'},{ name: '"._('Delivery address')."',value: '[Delivery Address]'}";
        $merge_contents = "{ name: '"._('Payment information')."',value: '[Pay Info]'},{ name: '"._('Order items')."',value: '[Order]'},{ name: '"._("Customer note")."',value: '[Customer Note]'}";

    } elseif ($email_template->get('Email Template Role') == 'OOS Notification') {
        $merge_tags = ",{ name: '"._('Back in stock products')."',value: '[Products]'}";

    } elseif ($email_template->get('Email Template Role') == 'Delivery Confirmation') {
        $merge_tags = ",{ name: '"._('Tracking code block')
            ."',value: '[Tracking START]Your tracking number is [Tracking Number].[END] [Not Tracking START]You should shortly receive email from one of our courier companies with tracking number and estimated delivery times.[END]'}";


        $special_links="{ type: '"._('Tracking link')."',link: '[Tracking URL]', label: '"._('here')."'}";

    } elseif ($email_template->get('Email Template Role') == 'GR Reminder') {
        $merge_tags = ",{ name: '"._('Last order number')."',value: '[Order Number]'},{ name: '"._('Last order date')."',value: '[Order Date]'},
                { name: '"._('Last order date + n days (Replace n for a number, default 30)')."',value: '[Order Date + n days]'},
                 { name: '"._('Last order date + n weeks (Replace n for a number, default 1)')."',value: '[Order Date + n weeks]'},
                  { name: '"._('Last order date + n months (Replace n for a number, default 1)')."',value: '[Order Date + n months]'}
                ";

        $merge_contents = "{ name: '"._('Unsubscribe')."',value: '[Unsubscribe]'}";

    }elseif ( in_array($email_template->get('Email Template Role'),['Basket Reminder 1','Basket Reminder 2','Basket Reminder 3'])     ) {


        $merge_contents = "{ name: '"._('Unsubscribe')."',value: '[Unsubscribe basket emails]'}";

    } elseif ( $email_template->get('Email Template Role')=='Basket Low Stock'     ) {


        $merge_contents = "{ name: '"._('Unsubscribe')."',value: '[Unsubscribe basket emails]'},{ name: '"._('Low/out of stock stock items')."',value: '[Low Stock Items in Basket]'}";

    }

    $smarty->assign('merge_tags', $merge_tags);
    $smarty->assign('merge_contents', $merge_contents);
    $smarty->assign('special_links', $special_links);


    $smarty->assign('send_email_to', $send_email_to);


    $html = $smarty->fetch('email_template.workshop.tpl');
} else {

    $smarty->assign('show_back_button', false);
    $smarty->assign('role', $email_campaign_type->get('Email Campaign Type Code'));
    $smarty->assign('scope', 'EmailCampaignType');
    $smarty->assign('scope_key', $state['key']);

    $html = $smarty->fetch('email_blueprints.showcase.tpl');


    /*

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
        'parent_key' => $state['key'],
        'redirect'   => base64_url_encode('&tab=email_campaign_type.workshop'),


    );


    include 'utils/get_table_html.php';

    */

}


?>
