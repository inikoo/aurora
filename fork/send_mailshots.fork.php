<?php
/*
 Author: Raul Perusquia <rulovico@gmail.com>
 Created:  11 May 2020  20:52::56  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 2.0
*/

include_once 'utils/send_zqm_message.class.php';

function fork_send_mailshots($job) {

    global $account, $db;// remove the global $db and $account is removed

    if (!$_data = get_fork_metadata($job)) {
        return true;
    }

    list($account, $db, $data, $editor, $ES_hosts) = $_data;


    print $data['type']."\n";
    //return true;
    switch ($data['type']) {

        case 'send_mailshot':

            $email_campaign         = get_object('email_campaign', $data['mailshot_key']);
            $email_campaign->editor = $data['editor'];
            print "Email campaign: ".$email_campaign->id."\n";

            if ($email_campaign->id) {

                $email_campaign->update_estimated_recipients();
                $email_campaign->send_mailshot();
            }
            break;

        case 'resume_mailshot':


            $mailshot = get_object('mailshot', $data['mailshot_key']);


            if ($mailshot->id) {
                print "Email campaign: ".$mailshot->id."\n";

                $max_thread = 1;

                $sql = "select `Email Tracking Thread` from `Email Tracking Dimension` where `Email Tracking Email Mailshot Key`=?  and `Email Tracking State`='Ready' group by `Email Tracking Thread`";

                $stmt = $db->prepare($sql);
                $stmt->execute(
                    array(
                        $mailshot->id
                    )
                );
                while ($row = $stmt->fetch()) {
                    $client        = new GearmanClient();
                    $fork_metadata = json_encode(
                        array(
                            'code' => addslashes($account->get('Code')),
                            'data' => array(
                                'mailshot' => $mailshot->id,
                                'thread'   => $row['Email Tracking Thread'],
                            )
                        )
                    );
                    include_once 'keyring/au_deploy_conf.php';
                    $servers = explode(",", GEARMAN_SERVERS);
                    shuffle($servers);
                    $servers = implode(",", $servers);
                    $client->addServers($servers);
                    $client->doBackground('au_send_mailshot', $fork_metadata);

                    if ($row['Email Tracking Thread'] >= $max_thread) {
                        $max_thread = $row['Email Tracking Thread'] + 1;
                    }
                }


                $mailshot->send_mailshot($max_thread);
            }
            break;


        case 'create_and_send_mailshot':


            $email_template_type = get_object('email_template_type', $data['email_template_type_key']);
            $email_campaign      = $email_template_type->create_mailshot();

            if (is_object($email_campaign) and $email_campaign->id) {
                $email_campaign->update_state('ComposingEmail');
                $email_campaign->update_state('Ready');
                $email_campaign->update_estimated_recipients();

                $email_campaign->update_state('Sending');

                $email_campaign->send_mailshot();
            }


            break;
        case 'create_and_send_second_wave_mailshot':
            $mailshot = get_object('mailshot', $data['mailshot_key']);
            // This is restricted as well in EmailCampaign class can_create_second_wave
            if($mailshot->get('Email Campaign Type')=='Newsletter') {
                $mailshot->editor = $editor;
                $mailshot->create_second_wave();
            }
            break;

    }
}