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

// require 'external_libs/aws.phar';

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

                    $email_tracking->fast_update(array('Email Tracking Spam'=>'Yes'));


                }

                $email_template_type = get_object('email_template_type', $email_tracking->get('Email Tracking Email Template Type Key'));


                $email_template_type->update_sent_emails_totals();



                //$_sql = sprintf('insert into atest  (`date`,`headers`,`request`) values (NOW(),"%s","%s")  ', $sql, 'xx');
                //$db->exec($_sql);

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
