<?php
/*
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 05-05-2019 09:55:54 CEST , Tranava, Slovakia
 Copyright (c) 2014, Inikoo

 Version 2.0
*/

include_once 'utils/object_functions.php';
include_once 'utils/new_fork.php';

function fork_send_mailshot($job) {

    global $account,$db;

    $time_start_tier_1 = microtime_float();
    $time_start_tier_2 = microtime_float();

    if (!$_data = get_fork_metadata($job)) {
        return true;
    }

    list($account, $db, $data, $editor,$ES_hosts) = $_data;

    $context = new ZMQContext();
    $socket  = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
    $socket->connect("tcp://localhost:5555");

    $mailshot = get_object('email_campaign', $data['mailshot']);

    $smarty = new Smarty();
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir('server_files/smarty/templates_c');
    $smarty->setCacheDir('server_files/smarty/cache');
    $smarty->setConfigDir('server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');

    $store           = get_object('Store', $mailshot->data['Email Campaign Store Key']);
    $website         = get_object('Website', $store->get('Store Website Key'));

    if($website->id){
        $unsubscribe_url = $website->get('Website URL').'/unsubscribe.php';

    }else{
        $unsubscribe_url = $account->get('Website URL').'/unsubscribe.php';

    }

    $email_template_type = get_object('email_template_type', $mailshot->data['Email Campaign Email Template Type Key']);
    $email_template      = get_object('email_template', $mailshot->data['Email Campaign Email Template Key']);

    $published_email_template = get_object('published_email_template', $email_template->get('Email Template Published Email Key'));

    if(isset($data['editor'])){
        $published_email_template->editor=$data['editor'];
    }


    if (isset($socket)) {
        $published_email_template->socket = $socket;
    }


    $sql = sprintf(
        'select `Email Tracking Key`,`Email Tracking Recipient`,`Email Tracking Recipient Key` ,`Email Tracking Recipient Key` from `Email Tracking Dimension` where `Email Tracking Email Mailshot Key`=%d  and `Email Tracking Thread`=%d and `Email Tracking State`="Ready" ',
        $mailshot->id, $data['thread']
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            $send_data = array(
                'Email_Template_Type' => $email_template_type,
                'Email_Template'      => $email_template,
                'Email_Tracking'      => get_object('Email_Tracking', $row['Email Tracking Key']),
                'Unsubscribe URL'     => $unsubscribe_url
            );

            if ($mailshot->data['Email Campaign Type'] == 'GR Reminder') {
                $customer               = get_object('Customer', $row['Email Tracking Recipient Key']);
                $send_data['Order Key'] = $customer->get('Customer Last Dispatched Order Key');
            }


            // print_r($send_data);

            $sql = sprintf('select `Email Campaign State` from `Email Campaign Dimension` where `Email Campaign Key`=%d ', $mailshot->id);
            if ($result2 = $db->query($sql)) {
                if ($row2 = $result2->fetch()) {
                    if ($row2['Email Campaign State'] == 'Stopped') {
                        return true;

                    }
                }
            }

            $recipient=get_object($row['Email Tracking Recipient'], $row['Email Tracking Recipient Key']);
            if(isset($data['editor'])){
                $recipient->editor=$data['editor'];
            }




            $published_email_template->send($recipient, $send_data, $smarty);


            //print $published_email_template->msg;



            $time_end = microtime_float();

            if (($time_end - $time_start_tier_1) > 15000) {


                new_housekeeping_fork(
                    'au_housekeeping', array(
                    'type'                    => 'update_sent_emails_data',
                    'email_template_key'      => $email_template->id,
                    'email_template_type_key' => $email_template_type->id,

                ), $account->get('Account Code')
                );


                $time_start_tier_1 = microtime_float();
            }


            if (($time_end - $time_start_tier_2) > 5000 or  mt_rand(1, 1000)==1000  ) {

                new_housekeeping_fork(
                    'au_housekeeping', array(
                    'type'               => 'update_sent_emails_data',
                    'email_mailshot_key' => $mailshot->id,

                ), $account->get('Account Code')
                );

                $time_start_tier_2 = microtime_float();
            }


        }
    }


    new_housekeeping_fork(
        'au_housekeeping', array(
        'type'                    => 'update_sent_emails_data',
        'email_template_key'      => $email_template->id,
        'email_template_type_key' => $email_template_type->id,

    ), $account->get('Account Code')
    );


    $mailshot->update_sent_emails_totals();


    $socket->send(
        json_encode(
            array(
                'channel' => 'real_time.'.strtolower($account->get('Account Code')),
                'objects' => array(
                    array(
                        'object' => 'mailshot',
                        'key'    => $mailshot->id,

                        'update_metadata' => array(
                            'class_html' => array(
                                '_Sent_Emails_Info'    => $mailshot->get('Sent Emails Info'),
                                '_Email_Campaign_Sent' => $mailshot->get('Sent'),
                            )
                        )

                    )

                ),

                'tabs' => array(

                    array(
                        'tab'        => 'email_campaign_type.mailshots',
                        'parent'     => 'store',
                        'parent_key' => $mailshot->get('Email Campaign Store Key'),
                        'cell'       => array(
                            'date_'.$mailshot->id  => strftime("%a, %e %b %Y %R", strtotime($mailshot->get('Email Campaign Last Updated Date')." +00:00")),
                            'state_'.$mailshot->id => $mailshot->get('State'),
                            'sent_'.$mailshot->id  => $mailshot->get('Sent')
                        )


                    ),

                ),


            )
        )
    );


    $total         = 0;
    $state_numbers = array(
        'Ready'   => 0,
        'Stopped' => 0
    );
    $sql           = sprintf(
        'select count(*) as num , `Email Tracking State`  from `Email Tracking Dimension`  where `Email Tracking Email Mailshot Key`=%d  group by `Email Tracking State` ', $mailshot->id
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $state_numbers[$row['Email Tracking State']] = $row['num'];
            $total                                       += $row['num'];

        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    switch ($mailshot->get('Email Campaign State')) {
        case 'Sending':
            //'InProcess','SetRecipients','ComposingEmail','Ready','Scheduled','Sending','Sent','Cancelled','Stopped'
            if ($state_numbers['Ready'] == 0 and $state_numbers['Stopped'] == 0) {
                $mailshot->update_state('Sent');


                $mailshot->update_metadata['hide'] = array(
                    'control_panel'

                );
                $mailshot->update_metadata['show'] = array(
                    'sent_email_data'
                );


                $socket->send(
                    json_encode(
                        array(
                            'channel' => 'real_time.'.strtolower($account->get('Account Code')),
                            'objects' => array(
                                array(
                                    'object' => 'mailshot',
                                    'key'    => $mailshot->id,

                                    'update_metadata' => $mailshot->get_update_metadata(),
                                    'v'               => 1


                                )

                            ),


                        )
                    )
                );

            }


            break;

    }


    $sql = sprintf(
        'select count(*) as num   from `Email Tracking Dimension`  where `Email Tracking Email Mailshot Key`=%d  and `Email Tracking Thread`=%d  and `Email Tracking State`="Ready" ', $mailshot->id, $data['thread']
    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            if ($row['num'] > 0) {
                $client        = new GearmanClient();
                $fork_metadata = json_encode(
                    array(
                        'code' => addslashes($account->get('Code')),
                        'data' => array(
                            'mailshot' => $mailshot->id,
                            'thread'   => $data['thread'],
                        )
                    )
                );
                $client->addServer('127.0.0.1');
                $client->doBackground('au_send_mailshot', $fork_metadata);

            }

        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    /*

            $mailshot->update_metadata['hide'] = array(
                'estimated_recipients',
                'email_campaign_operations'
            );


            $socket->send(
                json_encode(
                    array(
                        'channel' => 'real_time.'.strtolower($account->get('Account Code')),
                        'objects' => array(
                            array(
                                'object' => 'email_campaign',
                                'key'    => $mailshot->id,

                                'update_metadata' => $mailshot->get_update_metadata()

                            )

                        ),


                    )
                )
            );


    */


}