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
            'Email Campaign Type Scope'  => 'Marketing',
            'Email Campaign Type Code'   => 'Invite Full Mailshot',
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
        array(
            'Email Campaign Type Status' => 'InProcess',
            'Email Campaign Type Scope'  => 'Customer Notification',
            'Email Campaign Type Code'   => 'Registration Approved',
        ),
        array(
            'Email Campaign Type Status' => 'InProcess',
            'Email Campaign Type Scope'  => 'Customer Notification',
            'Email Campaign Type Code'   => 'Registration Rejected',
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
                            break;
                        case 'New Customer':
                            $subject = _('New customer registration').' '.$store->get('Name');
                            break;
                        case 'Delivery Note Undispatched':
                            $subject = _('Delivery note undispatched').' '.$store->get('Name');
                            break;
                        case 'Invoice Deleted':
                            $subject = _('Invoice deleted').' '.$store->get('Name');
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

