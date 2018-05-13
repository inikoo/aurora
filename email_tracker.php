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



$message   = Message::fromRawPostData();
$validator = new MessageValidator();
if ($validator->isValid($message)) {

    if (in_array(
        $message['Type'], array(
                            'SubscriptionConfirmation',
                            'UnsubscribeConfirmation'
                        )
    )) {

        file_get_contents($message['SubscribeURL']);
    }
    else {
        require_once 'utils/general_functions.php';
        require_once 'keyring/dns.php';
        require 'external_libs/aws.phar';

        $db = new PDO(
            "mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
        );
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


        $sql = sprintf('select `Email Tracking Key`  from `Email Tracking Dimension`  where `Email Tracking SES ID`=%s  ', prepare_mysql($message['messageId']));

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $event_type = '';
                $event_data = '';

                $date = gmdate('Y-m-d H:i:s', strtotime($message['timestamp']));

                switch ($message['eventType']) {
                    case 'Send':
                        $event_type = 'Send';

                        break;
                    case 'Delivery':
                        $event_type = 'Delivery';
                        $date       = gmdate('Y-m-d H:i:s', strtotime($message['delivery']['timestamp']));

                        break;
                    case 'Open':
                        $event_type = 'Open';
                        $date       = gmdate('Y-m-d H:i:s', strtotime($message['open']['timestamp']));

                        unset($message['open']['timestamp']);
                        $event_data = $message['open'];


                        break;
                    default:

                        $sql = sprintf(
                            'insert into atest  (`date`,`headers`,`request`) values (NOW(),"%s","%s")  ', $row['Email Tracking Key'], addslashes(json_decode($message))

                        );

                        $db->exec($sql);

                        break;
                }

                $sql = spintf(
                    'insert into `Email Tracking Event Dimension`  (`Email Tracking Event Tracking Key`,`Email Tracking Event Type`,`Email Tracking Event Date`,`Email Tracking Event Data`) 
                  values (%d,%s,%s,%s)', $row['Email Tracking Key'], prepare_mysql($event_type), prepare_mysql($date), prepare_mysql(json_encode($event_data))

                );

            } else {
                $sql = sprintf(
                    'insert into atest  (`date`,`headers`,`request`) values (NOW(),"%s","%s")  ', '', addslashes(json_decode($message))

                );

                $db->exec($sql);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


    }
} else {

    http_response_code(404);
    exit;
}


?>
