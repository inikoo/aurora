<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 09 Jun 2022 07:43:26 Central European Summer Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */


function send_basket_first_email($db)
{
    $smarty               = new Smarty();
    $smarty->caching_type = 'redis';
    $base                 = 'cron/';
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir($base.'server_files/smarty/templates_c');
    $smarty->setCacheDir($base.'server_files/smarty/cache');
    $smarty->setConfigDir($base.'server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');


    $account = get_object('Account', 1);

    $sql  = "select `Email Campaign Type Key` from `Email Campaign Type Dimension` where `Email Campaign Type Code`='Basket Reminder 1' and `Email Campaign Type Status`='Active' ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        [

        ]
    );
    while ($row = $stmt->fetch()) {
        $email_campaign_type      = get_object('email campaign type', $row['Email Campaign Type Key']);
        $email_template           = get_object('email_template', $email_campaign_type->get('Email Campaign Type Email Template Key'));
        $published_email_template = get_object('published_email_template', $email_template->get('Email Template Published Email Key'));

        $store   = get_object('Store', $email_campaign_type->get('Email Campaign Type Store Key'));
        $website = get_object('Website', $store->get('Store Website Key'));

        if ($website->id) {
            $unsubscribe_url = $website->get('Website URL').'/unsubscribe.php';
        } else {
            $unsubscribe_url = $account->get('Website URL').'/unsubscribe.php';
        }

        $send_data = array(
            'Email_Template_Type' => $email_campaign_type,
            'Email_Template'      => $email_template,
            'Unsubscribe URL'     => $unsubscribe_url

        );


        $metadata = $email_campaign_type->get('Metadata');

        $send_after = 1;
        if (isset($metadata['Send After Hours']) and is_numeric($metadata['Send After Hours']) and $metadata['Send After Hours'] > 0) {
            $send_after = $metadata['Send After Hours'];
        }


        $sql = "select `Order Key` from `Order Dimension` left join `Customer Dimension` on (`Order Customer Key`=`Customer Key`) where `Order Store Key`=? and `Order State`='InBasket' and `Customer Send Basket Emails`='Yes' and `Order First Basket Email` is null and `Order Last Updated Date` >= ? and  `Order Last Updated Date`<?  ;";

        $stmt2 = $db->prepare($sql);
        $stmt2->execute(
            [
                $email_campaign_type->get('Email Campaign Type Store Key'),
                gmdate('Y-m-d H:i:s', strtotime('now -7 day')),
                gmdate('Y-m-d H:i:s', strtotime("now -$send_after hours")),

            ]
        );


        while ($row2 = $stmt2->fetch()) {
            $order = get_object('Order', $row2['Order Key']);


            $customer = get_object('Customer', $order->get('Order Customer Key'));

            $published_email_template->send($customer, $send_data, $smarty);
            if ($published_email_template->sent) {
                print "send 1st basket email to order ".$order->get('Public ID')."  ".$order->id." \n";

                $sql = sprintf(
                    'insert into `Order Sent Email Bridge` (`Order Sent Email Order Key`,`Order Sent Email Email Tracking Key`,`Order Sent Email Type`) values (%d,%d,%s)',
                    $order->id,
                    $published_email_template->email_tracking->id,
                    prepare_mysql('Basket Reminder 1')
                );

                $db->exec($sql);
                $order->fast_update(
                    [
                        'Order First Basket Email' => gmdate('Y-m-d H:i:s')
                    ]
                );
            }else{
                print $published_email_template->msg."\n";
                $customer->fast_update(
                    [
                        'Customer Send Basket Emails'=>'No'
                    ]
                );


            }


        }
    }
}


function send_basket_second_email($db)
{
    $smarty               = new Smarty();
    $smarty->caching_type = 'redis';
    $base                 = 'cron/';
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir($base.'server_files/smarty/templates_c');
    $smarty->setCacheDir($base.'server_files/smarty/cache');
    $smarty->setConfigDir($base.'server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');


    $account = get_object('Account', 1);

    $sql  = "select `Email Campaign Type Key` from `Email Campaign Type Dimension` where `Email Campaign Type Code`='Basket Reminder 2' and `Email Campaign Type Status`='Active' ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        [

        ]
    );
    while ($row = $stmt->fetch()) {
        $email_campaign_type      = get_object('email campaign type', $row['Email Campaign Type Key']);
        $email_template           = get_object('email_template', $email_campaign_type->get('Email Campaign Type Email Template Key'));
        $published_email_template = get_object('published_email_template', $email_template->get('Email Template Published Email Key'));

        $store   = get_object('Store', $email_campaign_type->get('Email Campaign Type Store Key'));
        $website = get_object('Website', $store->get('Store Website Key'));

        if ($website->id) {
            $unsubscribe_url = $website->get('Website URL').'/unsubscribe.php';
        } else {
            $unsubscribe_url = $account->get('Website URL').'/unsubscribe.php';
        }

        $send_data = array(
            'Email_Template_Type' => $email_campaign_type,
            'Email_Template'      => $email_template,
            'Unsubscribe URL'     => $unsubscribe_url

        );


        $metadata = $email_campaign_type->get('Metadata');



        $send_after = 1;
        if (isset($metadata['Send After Hours']) and is_numeric($metadata['Send After Hours']) and $metadata['Send After Hours'] > 0) {
            $send_after = $metadata['Send After Hours'];
        }


        $sql = "select `Order Key` from `Order Dimension` left join `Customer Dimension` on (`Order Customer Key`=`Customer Key`) where `Order Store Key`=? and `Order State`='InBasket' and `Customer Send Basket Emails`='Yes' and `Order Second Basket Email` is null  and `Order First Basket Email` is not null  and  `Order First Basket Email`<?  ;";

        $stmt2 = $db->prepare($sql);
        $stmt2->execute(
            [
                $email_campaign_type->get('Email Campaign Type Store Key'),
                gmdate('Y-m-d H:i:s', strtotime("now -$send_after hours")),

            ]
        );


        while ($row2 = $stmt2->fetch()) {
            $order = get_object('Order', $row2['Order Key']);

            print "send 2nd basket email to order ".$order->get('Public ID')."  ".$order->id." \n";

            $customer = get_object('Customer', $order->get('Order Customer Key'));

            $published_email_template->send($customer, $send_data, $smarty);
            if ($published_email_template->sent) {
                $sql = sprintf(
                    'insert into `Order Sent Email Bridge` (`Order Sent Email Order Key`,`Order Sent Email Email Tracking Key`,`Order Sent Email Type`) values (%d,%d,%s)',
                    $order->id,
                    $published_email_template->email_tracking->id,
                    prepare_mysql('Basket Reminder 2')
                );

                $db->exec($sql);
                $order->fast_update(
                    [
                        'Order Second Basket Email' => gmdate('Y-m-d H:i:s')
                    ]
                );
            }


        }
    }
}


function send_basket_third_email($db)
{
    $smarty               = new Smarty();
    $smarty->caching_type = 'redis';
    $base                 = 'cron/';
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir($base.'server_files/smarty/templates_c');
    $smarty->setCacheDir($base.'server_files/smarty/cache');
    $smarty->setConfigDir($base.'server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');


    $account = get_object('Account', 1);

    $sql  = "select `Email Campaign Type Key` from `Email Campaign Type Dimension` where `Email Campaign Type Code`='Basket Reminder 3' and `Email Campaign Type Status`='Active' ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        [

        ]
    );
    while ($row = $stmt->fetch()) {
        $email_campaign_type      = get_object('email campaign type', $row['Email Campaign Type Key']);
        $email_template           = get_object('email_template', $email_campaign_type->get('Email Campaign Type Email Template Key'));
        $published_email_template = get_object('published_email_template', $email_template->get('Email Template Published Email Key'));

        $store   = get_object('Store', $email_campaign_type->get('Email Campaign Type Store Key'));
        $website = get_object('Website', $store->get('Store Website Key'));

        if ($website->id) {
            $unsubscribe_url = $website->get('Website URL').'/unsubscribe.php';
        } else {
            $unsubscribe_url = $account->get('Website URL').'/unsubscribe.php';
        }

        $send_data = array(
            'Email_Template_Type' => $email_campaign_type,
            'Email_Template'      => $email_template,
            'Unsubscribe URL'     => $unsubscribe_url

        );


        $metadata = $email_campaign_type->get('Metadata');




        $send_after = 1;
        if (isset($metadata['Send After Hours']) and is_numeric($metadata['Send After Hours']) and $metadata['Send After Hours'] > 0) {
            $send_after = $metadata['Send After Hours'];
        }


        $sql = "select `Order Key` from `Order Dimension` left join `Customer Dimension` on (`Order Customer Key`=`Customer Key`) where `Order Store Key`=? and `Order State`='InBasket' and `Customer Send Basket Emails`='Yes' and `Order Third Basket Email` is null  and `Order Second Basket Email` is not null  and  `Order Second Basket Email`<?  ;";

        $stmt2 = $db->prepare($sql);
        $stmt2->execute(
            [
                $email_campaign_type->get('Email Campaign Type Store Key'),
                gmdate('Y-m-d H:i:s', strtotime("now -$send_after hours")),

            ]
        );


        while ($row2 = $stmt2->fetch()) {
            $order = get_object('Order', $row2['Order Key']);

            print "send 3rd basket email to order ".$order->get('Public ID')."  ".$order->id." \n";

            $customer = get_object('Customer', $order->get('Order Customer Key'));

            $published_email_template->send($customer, $send_data, $smarty);
            if ($published_email_template->sent) {
                $sql = sprintf(
                    'insert into `Order Sent Email Bridge` (`Order Sent Email Order Key`,`Order Sent Email Email Tracking Key`,`Order Sent Email Type`) values (%d,%d,%s)',
                    $order->id,
                    $published_email_template->email_tracking->id,
                    prepare_mysql('Basket Reminder 3')
                );

                $db->exec($sql);
                $order->fast_update(
                    [
                        'Order Third Basket Email' => gmdate('Y-m-d H:i:s')
                    ]
                );
            }


        }
    }
}