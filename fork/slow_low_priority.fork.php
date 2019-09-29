<?php
/*
 Author: Raul Perusquia <rulovico@gmail.com>
 Created:  23-09-2019 22:07:31 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 2.0
*/

include 'utils/object_functions.php';
include_once 'utils/new_fork.php';

function fork_take_webpage_screenshot($job) {


    if (!$_data = get_fork_metadata($job)) {
        return true;
    }

    list($account, $db, $data, $editor) = $_data;


    $webpage = get_object('Webpage', $data['webpage_key']);
    $webpage->fork=true;

    $url = $webpage->get('Webpage URL').'?snapshot='.md5(VKEY.'||'.gmdate('Ymd'));


    if (!($webpage->get('Website Code') == 'home_logout.sys' or $webpage->get('Website Code') == 'register.sys')) {
        $url .= '&logged_in=1';
    }

    try {
        $webpage->update_screenshots();
    } catch (Exception $e) {

        echo $e->getMessage();
        print "error $url\n";
    }


    $context = new ZMQContext();
    $socket  = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
    $socket->connect("tcp://localhost:5555");


    /*
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
    */


}