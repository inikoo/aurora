<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 May 2018 at 12:00:28 CEST, Mijas Costa, Spain
 Copyright (c) 2018, Inikoo

 Version 3

*/

use Aws\Ses\SesClient;

require 'external_libs/aws.phar';


function fork_send_mailshot($job) {




    if (!$_data = get_fork_data($job)) {
        return;
    }

    //return 1;

    $db                  = $_data['db'];
    $fork_data           = $_data['fork_data'];
    $fork_key            = $_data['fork_key'];
    $inikoo_account_code = $_data['inikoo_account_code'];


    $email_campaign = get_object('Email_Campaign', $fork_data['key']);

    $store = get_object('Store', $email_campaign->get('Email Campaign Store Key'));

    $number_rows = 0;

    $sql_count = $email_campaign->get_sql_recipients_count();
    $sql_data  = $email_campaign->get_sql_recipients();





    if ($sql_count != '') {

        if ($result = $db->query($sql_count)) {
            if ($row = $result->fetch()) {
                $number_rows = $row['num'];
            }
        } else {
            print_r($error_info = $db->errorInfo());
            //  exit;
        }

    } else {
        $stmt = $db->prepare($sql_data);


        $stmt->execute();

        $number_rows = $stmt->rowCount();

    }


    $sql = sprintf(
        "UPDATE `Fork Dimension` SET `Fork State`='In Process' ,`Fork Operations Total Operations`=%d,`Fork Start Date`=NOW() WHERE `Fork Key`=%d ", $number_rows, $fork_key
    );


    // print "$sql\n";

    $db->exec($sql);


    $email_template = get_object('email_template', $email_campaign->get('Email Campaign Email Template Key'));

    $published_email_template = get_object('published_email_template', $email_template->get('Email Template Published Email Key'));


  //  print_r($published_email_template);

    $SES_client = SesClient::factory(
        array(
            'version'     => 'latest',
            'region'      => 'eu-west-1',
            'credentials' => [
                'key'    => AWS_ACCESS_KEY_ID,
                'secret' => AWS_SECRET_ACCESS_KEY,
            ],
        )
    );

    $account=get_object('Account',1);
    $row_index = 1;
    // print "$sql_data\n";

    if ($result = $db->query($sql_data)) {
        foreach ($result as $row) {


            send_email($row['Customer Key'], $email_campaign, $store, $published_email_template, $SES_client,$db,$account);


            $row_index++;


            if ($row_index % 100 == 0) {
                $sql = sprintf(
                    "UPDATE `Fork Dimension` SET `Fork Operations Done`=%d  WHERE `Fork Key`=%d ", $row_index, $fork_key
                );
                $db->exec($sql);
            }

        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql_data\n";
        // exit;
    }







    $sql = sprintf(
        "UPDATE `Fork Dimension` SET `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=%d WHERE `Fork Key`=%d ", $row_index, $fork_key
    );

    $db->exec($sql);


    $email_campaign->update_state('Send');


    return false;
}


function send_email($recipient_key, $email_campaign, $store, $published_email_template, $SES_client,$db,$account) {











    //'Test Email','Marketing','Registration','Password Reminder','Newsletter','Order Confirmation','Delivery Confirmation','Issue Reporting'
   // print_r($published_email_template);

    switch ($email_campaign->get('Email Campaign Type')) {
        case 'AbandonedCart':
            $scope     = 'Abandoned Cart';
            $scope_key = $email_campaign->get('Email Campaign Abandoned Cart Email Campaign Key');

            $recipient_type = 'Customer';
            $customer       = get_object($recipient_type, $recipient_key);

            $placeholders = array(
                '[Greetings]'    => $customer->get_greetings(),
                '[Name]'         => $customer->get('Name'),
                '[Name,Company]' => preg_replace(
                    '/^, /', '', $customer->get('Customer Main Contact Name').($customer->get('Customer Company Name') == '' ? '' : ', '.$customer->get('Customer Company Name'))
                ),
                '[Signature]'    => $store->get('Signature'),
            );

            $recipient_type = 'Customer';

            $email_address = $customer->get('Customer Main Plain Email');
            $email_address = 'raul@inikoo.com';

            break;




    }

    $sql = sprintf(
        'insert into `Email Send Dimension` (`Email Send Type`,`Email Send Type Key`,
          `Email Send Email Template Key`,`Email Send Published Email Template Key`,`Email Send Recipient Type`,`Email Send Recipient Key`,`Email Send Creation Date`) values (%s,%d,%d,%d,%s,%s,%s)',
        prepare_mysql($scope),
        $scope_key,
        $email_campaign->id,

        $published_email_template->id,
        prepare_mysql($recipient_type), $recipient_key,
        prepare_mysql(gmdate('Y-m-d H:i:s'))


    );


   // print "$sql\n";




    $db->exec($sql);
    $email_send_key = $db->lastInsertId();


    $request           = array();
    $request['Source'] = $store->get('Store Email');;
    $request['Destination']['ToAddresses']      = array($email_address);
    $request['Message']['Subject']['Data']      = $published_email_template->get('Published Email Template Subject');
    $request['Message']['Body']['Text']['Data'] = strtr($published_email_template->get('Published Email Template Text'), $placeholders);
    if ($published_email_template->get('Published Email Template HTML') != '') {

        $request['Message']['Body']['Html']['Data'] = strtr($published_email_template->get('Published Email Template HTML'), $placeholders);


        $url=$account->get('Account System Public URL');
        $url='http:/au.bali';


        $request['Message']['Body']['Html']['Data']= preg_replace('/\<\/body\>\<\/html\>\s*$/',' <img src="'.$url.'/email_tracker.php?id='.$email_send_key.' alt="" width="1px" height="1px"></body></html> ',$request['Message']['Body']['Html']['Data']);




    }



    $sql=sprintf('update `Email Send Dimension` set `Email Send State`="Send" , `Email Send SES Id`=%s ,`Email Send Date`=%s   where `Email Send Key`=%d ',
                 prepare_mysql('xxxx'),
                 prepare_mysql(gmdate('Y-m-d H:i:s')),
                 $email_send_key
    );
    $db->exec($sql);

    print "$sql\n";
    /*

    try {
        $result   = $SES_client->sendEmail($request);
        $response = array(
            'state' => 200,
            'scope' => 'send_email',
            'msg'   => $result->get('MessageId')


        );




        $sql=sprintf('update `Email Send Dimension` set `Email Send State`="Send" , `Email Send SES Id`=%s ,`Email Send Date`=%s   where `Email Send Key`=%d ',
                     prepare_mysql($result->get('MessageId')),
                     prepare_mysql(gmdate('Y-m-d H:i:s')),
                     $email_send_key
            );

        $db->exec($sql);
    } catch (Exception $e) {
        // echo("The email was not sent. Error message: ");
        // echo($e->getMessage()."\n");
        $response = array(
            'state'      => 400,
            'msg'        => "Error, email not send",
            'code'       => $e->getMessage(),
            'error_code' => 'unknown'


        );

        $sql=sprintf('update `Email Send Dimension` set `Email Send State`="Error" ,`Email Send Note`=%s where `Email Send Key`=%d',

                     prepare_mysql($e->getMessage()),
                     $email_send_key
        );

        $db->exec($sql);


    }

*/
   // exit('caca');


}


?>
