<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 08-05-2019 10:22:26 CEST, Tranava Slovakia
 Copyright (c) 2019, Inikoo

 Version 3.1

*/


function create_email_templates($db, $store) {

    include_once 'class.Email_Template.php';


    $email_campaign_types_data = array(
        array(
            'Email Campaign Type Status' => 'Active',
            'Email Campaign Type Scope'  => 'Marketing',
            'Email Campaign Type Code'   => 'Newsletter',
        ),
        array(
            'Email Campaign Type Status' => 'Active',
            'Email Campaign Type Scope'  => 'Marketing',
            'Email Campaign Type Code'   => 'Marketing',
        ),
        array(
            'Email Campaign Type Status' => 'Active',
            'Email Campaign Type Scope'  => 'Marketing',
            'Email Campaign Type Code'   => 'GR Reminder',
        ),
        array(
            'Email Campaign Type Status' => 'Active',
            'Email Campaign Type Scope'  => 'Marketing',
            'Email Campaign Type Code'   => 'AbandonedCart',
        ),

        array(
            'Email Campaign Type Status' => 'Active',
            'Email Campaign Type Scope'  => 'Marketing',
            'Email Campaign Type Code'   => 'Invite',
        ),
        array(
            'Email Campaign Type Status' => 'Active',
            'Email Campaign Type Scope'  => 'Marketing',
            'Email Campaign Type Code'   => 'Invite Mailshot',
        ),

        array(
            'Email Campaign Type Status' => 'Active',
            'Email Campaign Type Scope'  => 'User Notification',
            'Email Campaign Type Code'   => 'New Order',
        ),
        array(
            'Email Campaign Type Status' => 'Active',
            'Email Campaign Type Scope'  => 'User Notification',
            'Email Campaign Type Code'   => 'New Customer',
        ),
        array(
            'Email Campaign Type Status' => 'Active',
            'Email Campaign Type Scope'  => 'User Notification',
            'Email Campaign Type Code'   => 'Invoice Deleted',
        ),
        array(
            'Email Campaign Type Status' => 'Active',
            'Email Campaign Type Scope'  => 'User Notification',
            'Email Campaign Type Code'   => 'Delivery Note Undispatched',
        ),
        array(
            'Email Campaign Type Status' => 'Active',
            'Email Campaign Type Scope'  => 'Customer Notification',
            'Email Campaign Type Code'   => 'OOS Notification',
        ),
        array(
            'Email Campaign Type Status' => 'Active',
            'Email Campaign Type Scope'  => 'Customer Notification',
            'Email Campaign Type Code'   => 'Registration',
        ),
        array(
            'Email Campaign Type Status' => 'Active',
            'Email Campaign Type Scope'  => 'Customer Notification',
            'Email Campaign Type Code'   => 'Password Reminder',
        ),
        array(
            'Email Campaign Type Status' => 'Active',
            'Email Campaign Type Scope'  => 'Customer Notification',
            'Email Campaign Type Code'   => 'Order Confirmation',
        ),
        array(
            'Email Campaign Type Status' => 'Active',
            'Email Campaign Type Scope'  => 'Customer Notification',
            'Email Campaign Type Code'   => 'Delivery Confirmation',
        ),

    );

    foreach ($email_campaign_types_data as $email_campaign_type_data) {


        $email_campaign_type_data['Email Campaign Type Store Key'] = $store->id;


        $sql = sprintf(
            "INSERT INTO `Email Campaign Type Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($email_campaign_type_data)).'`', join(',', array_fill(0, count($email_campaign_type_data), '?'))
        );


        $stmt = $db->prepare($sql);

        $i = 1;
        foreach ($email_campaign_type_data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {
            $email_campaign_type_key = $db->lastInsertId();
            $email_campaign_type     = get_object('email_campaign_type', $email_campaign_type_key);

            switch ($email_campaign_type->get('Email Campaign Type Code')) {
                case 'New Order':
                case 'New Customer':
                case 'Delivery Note Undispatched':
                case 'Invoice Deleted':

                    $html = '';
                    $text = '';

                    switch ($email_campaign_type->get('Email Campaign Type Code')) {
                        case 'New Order':
                            $subject = _('New order').' '.$store->get('Name');
                        // $html    = file_get_contents('templates/notification_emails/new_order.ntfy.tpl');

                        case 'New Customer':
                            $subject = _('New customer registration').' '.$store->get('Name');
                            // $html    = file_get_contents('templates/notification_emails/alert.ntfy.tpl');
                            break;
                        case 'Delivery Note Undispatched':
                            $subject = _('Delivery note undispatched').' '.$store->get('Name');
                            //   $html    = file_get_contents('templates/notification_emails/alert.ntfy.tpl');

                            break;
                        case 'Invoice Deleted':
                            $subject = _('Invoice deleted').' '.$store->get('Name');
                            //    $html    = file_get_contents('templates/notification_emails/alert.ntfy.tpl');

                            break;
                    }


                    $email_template_data = array(
                        'Email Template Name'                    => $email_campaign_type->get('Email Campaign Type Code'),
                        'Email Template Email Campaign Type Key' => $email_campaign_type->id,
                        'Email Template Role Type'               => 'Transactional',
                        'Email Template Role'                    => $email_campaign_type->get('Email Campaign Type Code'),
                        'Email Template Scope'                   => 'EmailCampaignType',
                        'Email Template Scope Key'               => $email_campaign_type->id,
                        'Email Template Subject'                 => $subject,
                        'Email Template HTML'                    => $html,
                        'Email Template Text'                    => $text,


                        'Email Template Created'      => gmdate('Y-m-d H:i:s'),
                        'Email Template Editing JSON' => ''
                    );

                    // print_r($email_template_data);

                    $email_template = new Email_Template('find', $email_template_data, 'create');

                    $email_campaign_type->fast_update(
                        array(
                            'Email Campaign Type Email Template Key' => $email_template->id
                        )
                    );


                    $email_template->publish();


                    break;


                case 'Registration':

                    $website = get_object('Website', $store->get('Website Key'));

                    $registration_page = $website->get_webpage('register.sys');
                    $scope_metadata    = $registration_page->get('Scope Metadata');

                    $email_campaign_type->fast_update(array('Email Campaign Type Email Template Key' => $scope_metadata['emails']['welcome']['key']));


                    $sql = sprintf(
                        'update `Email Template Dimension` set `Email Template Email Campaign Type Key`=%d where `Email Template Key`=%d  ', $email_campaign_type->id, $scope_metadata['emails']['welcome']['key']

                    );
                    $db->exec($sql);

                    break;
                case 'Password Reminder':


                    $website = get_object('Website', $store->get('Website Key'));

                    $registration_page = $website->get_webpage('login.sys');

                    $scope_metadata = $registration_page->get('Scope Metadata');


                    $email_campaign_type->fast_update(array('Email Campaign Type Email Template Key' => $scope_metadata['emails']['reset_password']['key']));

                    $sql = sprintf(
                        'update `Email Template Dimension` set `Email Template Email Campaign Type Key`=%d where `Email Template Key`=%d  ', $email_campaign_type->id, $scope_metadata['emails']['reset_password']['key']

                    );
                    $db->exec($sql);

                    break;
                case 'Order Confirmation':
                    $website = get_object('Website', $store->get('Website Key'));

                    $registration_page = $website->get_webpage('checkout.sys');
                    $scope_metadata    = $registration_page->get('Scope Metadata');

                    $email_campaign_type->fast_update(array('Email Campaign Type Email Template Key' => $scope_metadata['emails']['order_confirmation']['key']));

                    $sql = sprintf(
                        'update `Email Template Dimension` set `Email Template Email Campaign Type Key`=%d where `Email Template Key`=%d  ', $email_campaign_type->id, $scope_metadata['emails']['order_confirmation']['key']

                    );
                    $db->exec($sql);
                    break;
                case 'OOS Notification':

                    $_metadata = array(

                        'Schedule' => array(

                            'Days'     => array(
                                'Monday'    => 'Yes',
                                'Tuesday'   => 'Yes',
                                'Wednesday' => 'Yes',
                                'Thursday'  => 'Yes',
                                'Friday'    => 'Yes',
                                'Saturday'  => 'Yes',
                                'Sunday'    => 'Yes'
                            ),
                            'Time'     => '16:00:00',
                            'Timezone' => $store->get('Store Timezone')
                        )

                    );

                    // print_r($_metadata);


                    $email_campaign_type->fast_update(array('Email Campaign Type Metadata' => json_encode($_metadata)));


                    break;
                case 'GR Reminder':
                    $_metadata = array(
                        'Send After' => 20,
                        'Schedule'   => array(

                            'Days'     => array(
                                'Monday'    => 'Yes',
                                'Tuesday'   => 'Yes',
                                'Wednesday' => 'Yes',
                                'Thursday'  => 'Yes',
                                'Friday'    => 'Yes',
                                'Saturday'  => 'Yes',
                                'Sunday'    => 'Yes'
                            ),
                            'Time'     => '16:00:00',
                            'Timezone' => $store->get('Store Timezone')
                        )

                    );

                    // print_r($_metadata);


                    $email_campaign_type->fast_update(array('Email Campaign Type Metadata' => json_encode($_metadata)));

                    break;


            }


        } else {

        }


    }

}



/*

exit;
add_notifications($db);


function add_notifications($db) {


    $sql = sprintf("SELECT `Store Key` FROM `Store Dimension`");
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $store = new Store('id', $row['Store Key']);


            $email_campaign_type = 'Store Notifications';


            $sql = sprintf(
                'insert into `Email Campaign Type Dimension`  (`Email Campaign Type Store Key`,`Email Campaign Type Code`) values (%d,%s) ', $store->id, prepare_mysql($email_campaign_type)

            );
            //print "$sql\n";
            $db->exec($sql);
            $email_campaign_type = new EmailCampaignType('code_store', $email_campaign_type, $store->id);


        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

}


function add_base_email_templates($db) {

    $email_campaign_types = array(
        'Newsletter',
        'Marketing',
        'GR Reminder',
        'AbandonedCart',
        'OOS Notification',
        'Registration',
        'Password Reminder',
        'Order Confirmation',
        'Delivery Confirmation',
        'Invite',
        'Invite Mailshot',
        'Store Notifications'
    );

    $sql = sprintf("SELECT `Store Key` FROM `Store Dimension`");
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $store = get_object('Store', $row['Store Key']);
            foreach ($email_campaign_types as $email_campaign_type) {
                $sql = sprintf(
                    'insert into `Email Campaign Type Dimension`  (`Email Campaign Type Store Key`,`Email Campaign Type Code`) values (%d,%s) ', $store->id, prepare_mysql($email_campaign_type)

                );
                //print "$sql\n";
                $db->exec($sql);


                $email_campaign_type = new EmailCampaignType('code_store', $email_campaign_type, $store->id);


                if ($email_campaign_type->get('Email Campaign Type Code') == 'Registration') {


                    $website = get_object('Website', $store->get('Website Key'));

                    $registration_page = $website->get_webpage('register.sys');
                    $scope_metadata    = $registration_page->get('Scope Metadata');

                    $email_campaign_type->fast_update(array('Email Campaign Type Email Template Key' => $scope_metadata['emails']['welcome']['key']));


                    $sql = sprintf(
                        'update `Email Template Dimension` set `Email Template Email Campaign Type Key`=%d where `Email Template Key`=%d  ', $email_campaign_type->id, $scope_metadata['emails']['welcome']['key']

                    );
                    $db->exec($sql);

                } elseif ($email_campaign_type->get('Email Campaign Type Code') == 'Password Reminder') {


                    $website = get_object('Website', $store->get('Website Key'));

                    $registration_page = $website->get_webpage('login.sys');

                    $scope_metadata = $registration_page->get('Scope Metadata');


                    $email_campaign_type->fast_update(array('Email Campaign Type Email Template Key' => $scope_metadata['emails']['reset_password']['key']));

                    $sql = sprintf(
                        'update `Email Template Dimension` set `Email Template Email Campaign Type Key`=%d where `Email Template Key`=%d  ', $email_campaign_type->id, $scope_metadata['emails']['reset_password']['key']

                    );
                    $db->exec($sql);

                } elseif ($email_campaign_type->get('Email Campaign Type Code') == 'Order Confirmation') {


                    $website = get_object('Website', $store->get('Website Key'));

                    $registration_page = $website->get_webpage('checkout.sys');
                    $scope_metadata    = $registration_page->get('Scope Metadata');

                    $email_campaign_type->fast_update(array('Email Campaign Type Email Template Key' => $scope_metadata['emails']['order_confirmation']['key']));

                    $sql = sprintf(
                        'update `Email Template Dimension` set `Email Template Email Campaign Type Key`=%d where `Email Template Key`=%d  ', $email_campaign_type->id, $scope_metadata['emails']['order_confirmation']['key']

                    );
                    $db->exec($sql);
                } elseif ($email_campaign_type->get('Email Campaign Type Code') == 'OOS Notification') {


                    $_metadata = array(

                        'Schedule' => array(

                            'Days'     => array(
                                'Monday'    => 'Yes',
                                'Tuesday'   => 'Yes',
                                'Wednesday' => 'Yes',
                                'Thursday'  => 'Yes',
                                'Friday'    => 'Yes',
                                'Saturday'  => 'Yes',
                                'Sunday'    => 'Yes'
                            ),
                            'Time'     => '16:00:00',
                            'Timezone' => $store->get('Store Timezone')
                        )

                    );

                    // print_r($_metadata);


                    $email_campaign_type->fast_update(array('Email Campaign Type Metadata' => json_encode($_metadata)));


                } elseif ($email_campaign_type->get('Email Campaign Type Code') == 'GR Reminder') {


                    $_metadata = array(
                        'Send After' => 20,
                        'Schedule'   => array(

                            'Days'     => array(
                                'Monday'    => 'Yes',
                                'Tuesday'   => 'Yes',
                                'Wednesday' => 'Yes',
                                'Thursday'  => 'Yes',
                                'Friday'    => 'Yes',
                                'Saturday'  => 'Yes',
                                'Sunday'    => 'Yes'
                            ),
                            'Time'     => '16:00:00',
                            'Timezone' => $store->get('Store Timezone')
                        )

                    );

                    // print_r($_metadata);


                    $email_campaign_type->fast_update(array('Email Campaign Type Metadata' => json_encode($_metadata)));


                }


            }
        }
    }


    $sql = sprintf('select * from `Email Template Dimension`');


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $email_template = get_object('EmailTemplate', $row['Email Template Key']);

            if ($email_template->get('Email Template Scope') == 'EmailCampaignType') {
                $email_template->fast_update(
                    array(
                        'Email Template Email Campaign Type Key' => $email_template->get('Email Template Scope Key')
                    )
                );

            }

            // official roles
            //'AbandonedCart','Delivery Confirmation','GR Reminder','Invite','Invite Mailshot','Marketing','Newsletter','OOS Notification','Order Confirmation','Password Reminder','Registration'

            // old roles
            //'Delivery Confirmation','GR Reminder','Invite Mailshot','OOS Notification','Order Confirmation','Order_Confirmation','Password Reminder','Registration','Reset_Password','Welcome'

            if ($email_template->get('Email Template Role') == 'Invitation Mailshot') {
                $email_template->fast_update(
                    array(
                        'Email Template Role' => 'Invite Mailshot'
                    )
                );
            }


            if ($email_template->get('Email Template Role') == 'Reset_Password') {
                $email_template->fast_update(
                    array(
                        'Email Template Role' => 'Password Reminder'
                    )
                );
            }
            if ($email_template->get('Email Template Role') == 'Order_Confirmation') {
                $email_template->fast_update(
                    array(
                        'Email Template Role' => 'Order Confirmation'
                    )
                );
            }
            if ($email_template->get('Email Template Role') == 'Welcome') {
                $email_template->fast_update(
                    array(
                        'Email Template Role' => 'Registration'
                    )
                );
            }

            if ($email_template->get('Email Template Scope') == 'Webpage') {

                $webpage             = get_object('Webpage', $email_template->get('Email Template Scope Key'));
                $website             = get_object('Website', $webpage->get('Webpage Website Key'));
                $email_template_type = get_object('Email_Template_Type', $email_template->get('Email Template Role').'|'.$website->get('Website Store Key'), 'code_store');

                if ($email_template_type->id) {
                    $email_template->fast_update(
                        array(
                            'Email Template Scope Key' => $email_template_type->id,
                            'Email Template Scope'     => 'EmailCampaignType'
                        )
                    );
                } else {

                    // print_r($website);

                    exit('email_template_type not found from role'.$email_template->get('Email Template Role'));
                }


            }


        }
    } else {
        print_r($error_info = $this->db->errorInfo());
        print "$sql\n";
        exit;
    }


    $sql = sprintf('select * from `Email Blueprint Dimension`');


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $email_blueprint = get_object('EmailBlueprint', $row['Email Blueprint Key']);
            $parent          = get_object($email_blueprint->get('Email Blueprint Scope'), $email_blueprint->get('Email Blueprint Scope Key'));


            switch ($email_blueprint->get('Email Blueprint Scope')) {
                case 'Webpage':

                    switch ($parent->get('Webpage Code')) {
                        case 'register.sys':
                            $scope_metadata = $parent->get('Scope Metadata');
                            $email_template = get_object('EmailTemplate', $scope_metadata['emails']['welcome']['key']);
                            break;
                        case 'login.sys':
                            $scope_metadata = $parent->get('Scope Metadata');
                            $email_template = get_object('EmailTemplate', $scope_metadata['emails']['reset_password']['key']);
                            break;
                        case 'checkout.sys':
                            $scope_metadata = $parent->get('Scope Metadata');
                            $email_template = get_object('EmailTemplate', $scope_metadata['emails']['order_confirmation']['key']);
                            break;


                    }

                    $email_blueprint->fast_update(
                        array(
                            'Email Blueprint Email Campaign Type Key' => $email_template->get('Email Template Email Campaign Type Key'),
                            'Email Blueprint Email Template Key'      => $email_template->id

                        )
                    );

                    break;

                case 'EmailCampaignType':
                    $email_blueprint->fast_update(
                        array(
                            'Email Blueprint Email Campaign Type Key' => $parent->id,
                            'Email Blueprint Email Template Key'      => $parent->get('Email Campaign Type Email Template Key')

                        )
                    );
                    break;


            }


        }
    } else {
        print_r($error_info = $this->db->errorInfo());
        print "$sql\n";
        exit;
    }


}

*/