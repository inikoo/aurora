<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 May 2018 at 14:57:32 CEST, Mijas Costa, Spain
 Copyright (c) 2018, Inikoo

 Version 3

*/

require 'vendor/autoload.php';

use Aws\Sns\Message;
use Aws\Sns\MessageValidator;


if ('POST' !== $_SERVER['REQUEST_METHOD']) {
    http_response_code(405);
    die;
}

require_once 'utils/general_functions.php';
require_once 'keyring/dns.php';

require_once 'utils/object_functions.php';


$db = new PDO(
    "mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


$sns       = Message::fromRawPostData();
$validator = new MessageValidator();
if ($validator->isValid($sns)) {


    //$sql = sprintf('insert into atest  (`date`,`headers`,`request`) values (NOW(),"%s","%s")  ', 'xx', addslashes( print_r($sns,true)  ));
    //$db->exec($sql);


    if (in_array(
        $sns['Type'], array(
                        'SubscriptionConfirmation',
                        'UnsubscribeConfirmation'
                    )
    )) {

        file_get_contents($sns['SubscribeURL']);
    } else {


        $sql = sprintf('insert into atest2  (`date`,`data`) values (NOW(),"%s")  ', addslashes(serialize($sns)));

        $db->exec($sql);


        $message = json_decode($sns['Message'], true);


        // $sql = sprintf('insert into atest  (`date`,`headers`,`request`) values (NOW(),"%s","%s")  ', 'xx', addslashes(json_encode($message)));

        // $db->exec($sql);


        $sql = sprintf('select `Email Tracking Key`  from `Email Tracking Dimension`  where `Email Tracking SES ID`=%s  ', prepare_mysql($message['mail']['messageId']));


        //  $db->exec($_sql);


        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {

                $event_type = '';
                $event_data = '';

                $date = gmdate('Y-m-d H:i:s', strtotime($message['mail']['timestamp']));


                //'Sent','Rejected by SES','Send','Read','Hard Bounce','Soft Bounce','Spam','Delivered','Opened','Clicked','Send to SES Error'



                $message_id=$message['mail']['messageId'];

                switch ($message['eventType']) {
                    case 'Send':
                        $event_type = 'Sent';

                        break;
                    case 'Delivery':
                        $event_type = 'Delivered';
                        $date       = gmdate('Y-m-d H:i:s', strtotime($message['delivery']['timestamp']));

                        break;
                    case 'Open':
                        $event_type = 'Opened';
                        $date       = gmdate('Y-m-d H:i:s', strtotime($message['open']['timestamp']));

                        unset($message['open']['timestamp']);
                        $event_data = $message['open'];


                        break;

                    case 'Click':

                        if (isset($message['linkTags']['type']['unsubscribe'])) {
                            // ignore Unsubscribe link clicks
                            exit;
                        }

                        $event_type = 'Clicked';
                        $date       = gmdate('Y-m-d H:i:s', strtotime($message['click']['timestamp']));

                        unset($message['click']['timestamp']);
                        $event_data = $message['click'];


                        break;
                    case 'Bounce':
                        $date       = gmdate('Y-m-d H:i:s', strtotime($message['bounce']['timestamp']));
                        $event_data = $message['bounce'];

                        unset($message['bounce']['timestamp']);

                        switch ($message['bounce']['bounceType']) {
                            case 'Undetermined':
                                $event_type = 'Soft Bounce';


                                break;

                            case 'Transient':
                                $event_type = 'Soft Bounce';


                                break;

                            case 'Permanent':
                                $event_type = 'Hard Bounce';


                                break;

                        }


                        break;
                    case 'Complaint':
                        $date       = gmdate('Y-m-d H:i:s', strtotime($message['complaint']['timestamp']));
                        $event_data = $message['complaint'];

                        unset($message['complaint']['timestamp']);

                        $event_type = 'Spam';


                        break;

                    default:

                        $sql = sprintf(
                            'insert into atest  (`date`,`headers`,`request`) values (NOW(),"%s","%s")  ', $row['Email Tracking Key'], addslashes(json_encode($message))

                        );

                        $db->exec($sql);

                        break;
                }


                $sql = sprintf(
                    'insert into `Email Tracking Event Dimension`  (`Email Tracking Event Tracking Key`,`Email Tracking Event Type`,`Email Tracking Event Date`,`Email Tracking Event Data`) 
                  values (%d,%s,%s,%s)', $row['Email Tracking Key'], prepare_mysql($event_type), prepare_mysql($date), prepare_mysql(json_encode($event_data))

                );
                $db->exec($sql);
                $event_key = $db->lastInsertId();


                $email_tracking = get_object('email_tracking', $row['Email Tracking Key']);
                $email_tracking->update_state($event_type);


                if ($event_type == 'Hard Bounce') {


                    foreach ($event_data['bouncedRecipients'] as $_bounced_recipients) {

                        $sql = sprintf(
                            'insert into `Email Hard Bounce Dimension` (`Email Hard Bounce Email`,`Email Hard Bounce Tracking Event Key`) values (%s,%d) ',

                            prepare_mysql($_bounced_recipients['emailAddress']), $event_key

                        );
                        $db->exec($sql);

                    }


                }

                if ($event_type == 'Spam') {


                    foreach ($event_data['complainedRecipients'] as $_spam_recipients) {

                        $sql = sprintf(
                            'insert into `Email Spam Dimension` (`Email Spam Sender`,`Email Spam Recipient`,`Email Spam Tracking Event Key`) values (%s,%d) ',

                            prepare_mysql($_spam_recipients['emailAddress']), prepare_mysql($message['mail']['source']),

                            $event_key

                        );
                        $db->exec($sql);
                    }

                    $email_tracking->fast_update(array('Email Tracking Spam' => 'Yes'));


                }


                if ($email_tracking->get('Email Tracking Email Template Type Key') > 0) {
                    $email_template_type = get_object('email_template_type', $email_tracking->get('Email Tracking Email Template Type Key'));
                    $email_template_type->update_sent_emails_totals();
                }
                if ($email_tracking->get('Email Tracking Email Mailshot Key') > 0) {
                    $email_campaign = get_object('email_campaign', $email_tracking->get('Email Tracking Email Mailshot Key'));
                    $email_campaign->update_sent_emails_totals();
                }
                if ($email_tracking->get('Email Tracking Email Template Key') > 0) {
                    $email_template = get_object('email_template', $email_tracking->get('Email Tracking Email Template Key'));
                    $email_template->update_sent_emails_totals();
                }


                switch ($email_tracking->get('Email Tracking Recipient')) {
                    case 'Prospect':
                        $prospect = get_object('Prospect', $email_tracking->get('Email Tracking Recipient Key'));
                        $prospect->update_prospect_data();
                        break;
                    default:
                        break;
                }


                //$_sql = sprintf('insert into atest  (`date`,`headers`,`request`) values (NOW(),"%s","%s")  ', $sql, 'xx');
                //$db->exec($_sql);

                $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
                $socket->connect("tcp://localhost:5555");
                $account = get_object('Account', 1);


                switch ($email_tracking->get('Email Tracking State')) {
                    case 'Ready':
                        $state = _('Ready to send');
                        break;
                    case 'Sent to SES':
                        $state = _('Sending');
                        break;
                        break;
                    case 'Delivered':
                        $state = _('Delivered');
                        break;
                    case 'Opened':
                        $state = _('Opened');
                        break;
                    case 'Clicked':
                        $state = _('Clicked');
                        break;
                    case 'Error':
                        $state = '<span class="warning">'._('Error').'</span>';
                        break;
                    case 'Hard Bounce':
                        $state = '<span class="error"><i class="fa fa-exclamation-circle"></i>  '._('Bounced').'</span>';
                        break;
                    case 'Soft Bounce':
                        $state = '<span class="warning"><i class="fa fa-exclamation-triangle"></i>  '._('Probable bounce').'</span>';
                        break;
                    case 'Spam':
                        $state = '<span class="error"><i class="fa fa-exclamation-circle"></i>  '._('Mark as spam').'</span>';
                        break;
                    default:
                        $state = $email_tracking->get('Email Tracking State');
                }


                if (isset($email_campaign)) {
                    $this->socket->send(
                        json_encode(
                            array(
                                'channel' => 'real_time.'.strtolower($account->get('Account Code')),
                                'objects' => array(
                                    array(
                                        'object' => 'email_campaign',
                                        'key'    => $email_campaign->id,

                                        'update_metadata' => array(
                                            'class_html' => array(
                                                'Sent_Emails_Info'    => $email_campaign->get('Sent Emails Info'),
                                                'Email_Campaign_Sent' => $email_campaign->get('Sent'),
                                            )
                                        )

                                    )

                                ),

                                'tabs' => array(
                                    array(
                                        'tab'        => 'email_campaign.sent_emails',
                                        'parent'     => 'email_campaign_type',
                                        'parent_key' => $email_template_type->id,
                                        'cell'       => array(
                                            'email_tracking_state_'.$email_tracking->id => $state
                                        )


                                    ),
                                    array(
                                        'tab'        => 'email_campaign_type.mailshots',
                                        'parent'     => 'store',
                                        'parent_key' => $email_template_type->get('Store Key'),
                                        'cell'       => array(
                                            'date_'.$email_campaign->id  => strftime("%a, %e %b %Y %R", strtotime($email_campaign->get('Email Campaign Last Updated Date')." +00:00")),
                                            'state_'.$email_campaign->id => $email_campaign->get('State'),
                                            'sent_'.$email_campaign->id  => $email_campaign->get('Sent')
                                        )


                                    ),

                                ),


                            )
                        )
                    );

                }


            } else {
                $sql = sprintf(
                    'insert into atest  (`date`,`headers`,`request`) values (NOW(),"%s","%s")  ', 'xx', addslashes(json_encode($message))

                );

                $db->exec($sql);
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


    }
} else {

    http_response_code(404);
    exit;
}


?>
