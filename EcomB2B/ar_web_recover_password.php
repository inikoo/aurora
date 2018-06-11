<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 July 2017 at 12:19:22 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

use Aws\Ses\SesClient;


require_once 'common.php';
require_once 'utils/ar_web_common.php';
require_once 'utils/public_object_functions.php';

require_once 'utils/get_addressing.php';


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 407,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}
$tipo = $_REQUEST['tipo'];

switch ($tipo) {
    case 'recover_password':
        $data = prepare_values(
            $_REQUEST, array(
                         'website_key'    => array('type' => 'key'),
                         'webpage_key'    => array('type' => 'key'),
                         'recovery_email' => array('type' => 'string')


                     )
        );
        recover_password($db, $data, $editor, $website, $account);
        break;


    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}

function recover_password($db, $data, $editor, $website, $account) {

    require 'external_libs/aws.phar';


    $sql = sprintf(
        "SELECT  `Customer Type by Activity`,`Website User Key`,`Website User Customer Key` FROM `Website User Dimension` left join `Customer Dimension` on (`Customer Key`=`Website User Customer Key`)   WHERE  `Website User Handle`=%s AND `Website User Website Key`=%d",
        prepare_mysql($data['recovery_email']), $data['website_key']

    );


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {


            if ($row['Customer Type by Activity'] == 'ToApprove') {
                $response = array(
                    'state'      => 400,
                    'msg'        => _('Account waiting for approval'),
                    'error_code' => 'waiting_approval'
                );
                echo json_encode($response);
                exit;
            }

            $customer = get_object('Customer', $row['Website User Customer Key']);

            require_once "external_libs/random/lib/random.php";
            $selector      = base64_url_encode(random_bytes(9));
            $authenticator = base64_url_encode(random_bytes(33));


            $hash = hash('sha256', $authenticator);

            $sql = sprintf(
                'INSERT INTO `Website Recover Token Dimension` (`Website Recover Token Website Key`,`Website Recover Token Selector`,`Website Recover Token Hash`,`Website Recover Token Website User Key`,`Website Recover Token Customer Key`,`Website Recover Token Expire`) 
            VALUES (%d,%s,%s,%d,%d,%s)', $data['website_key'], prepare_mysql($selector), prepare_mysql($hash), $row['Website User Key'], $row['Website User Customer Key'], prepare_mysql(date('Y-m-d H:i:s', time() + 1200))

            );


            $db->exec($sql);


            $webpage = get_object('webpage', $data['webpage_key']);

            $scope_metadata = $webpage->get('Scope Metadata');


            $email_template = get_object('email_template', $scope_metadata['emails']['reset_password']['key']);


            $published_email_template = get_object('published_email_template', $email_template->get('Email Template Published Email Key'));


            if ($email_template->get('Email Template Subject') == '') {
                $response = array(
                    'state'      => 400,
                    'msg'        => _('Empty email subject'),
                    'error_code' => 'unknown'
                );
                echo json_encode($response);
                exit;
            }


            $sender_email_address = $webpage->get('Send Email Address');

            if ($sender_email_address == '') {
                $response = array(
                    'state'      => 400,
                    'msg'        => 'Sender email address not configured',
                    'error_code' => 'unknown'
                );
                echo json_encode($response);
                exit;
            }


            $client = SesClient::factory(
                array(
                    'version'     => 'latest',
                    'region'      => 'eu-west-1',
                    'credentials' => [
                        'key'    => AWS_ACCESS_KEY_ID,
                        'secret' => AWS_SECRET_ACCESS_KEY,
                    ],
                )
            );


            $placeholders = array(
                '[Greetings]'          => $customer->get_greetings(),
                '[Name]'               => $customer->get('Name'),
                '[Name,Company]'       => preg_replace(
                    '/^, /', '', $customer->get('Customer Main Contact Name').(($customer->get('Customer Company Name') == '' or $customer->get('Customer Company Name') == $customer->get('Customer Main Contact Name')) ? '' : ', '.$customer->get('Customer Company Name'))
                ),
                '[Reset_Password_URL]' => 'https://'.$website->get('Website URL').'/reset.php?s='.$selector.'&a='.$authenticator,
                '[Signature]'          => $webpage->get('Signature'),
            );


            $request                                    = array();
            $request['Source']                          = $sender_email_address;
            $request['Destination']['ToAddresses']      = array($data['recovery_email']);
            $request['Message']['Subject']['Data']      = $published_email_template->get('Published Email Template Subject');
            $request['Message']['Body']['Text']['Data'] = strtr($published_email_template->get('Published Email Template Text'), $placeholders);
            $request['ConfigurationSetName']            = $account->get('Account Code');


            if ($email_template->get('Email Template Type') == 'HTML') {

                $request['Message']['Body']['Html']['Data'] = strtr($published_email_template->get('Published Email Template HTML'), $placeholders);

            }


            $sql = sprintf(
                'insert into `Email Tracking Dimension` (
                `Email Tracking Email`,`Email Tracking Email Template Type Key`,
              `Email Tracking Scope`,`Email Tracking Scope Key`,
              `Email Tracking Email Template Key`,`Email Tracking Published Email Template Key`,
              `Email Tracking Recipient`,`Email Tracking Recipient Key`,`Email Tracking Created Date`) values (
                    %s,%d,
                      %s,%d,
                    %d,%d,
                    %s,%s,%s)',
                prepare_mysql($data['recovery_email']),$email_template->get('Email Template Email Campaign Type Key'),
                prepare_mysql('Password Reminder'), $website->id, $email_template->id, $published_email_template->id, prepare_mysql('Customer'), $customer->id, prepare_mysql(gmdate('Y-m-d H:i:s'))


            );
            $db->exec($sql);
            $email_tracking_key = $db->lastInsertId();


            try {
                $result    = $client->sendEmail($request);
                $messageId = $result->get('MessageId');


                $sql = sprintf(
                    'update `Email Tracking Dimension` set `Email Tracking State`="Sent to SES" , `Email Tracking SES Id`=%s   where `Email Tracking Key`=%d ', prepare_mysql($messageId), $email_tracking_key
                );
                $db->exec($sql);


                $response = array(
                    'state' => 200


                );


            } catch (Exception $e) {
                // echo("The email was not sent. Error message: ");
                // echo($e->getMessage()."\n");

                $sql = sprintf(
                    'update `Email Tracking Dimension` set `Email Tracking State`="Error"   where `Email Tracking Key`=%d ', $email_tracking_key
                );
                $db->exec($sql);


                $sql = sprintf(
                    'insert into `Email Tracking Event Dimension` (
              `Email Tracking Event Tracking Key`,`Email Tracking Event Type`,
              `Email Tracking Event Date`,`Email Tracking Event Data`
     ) values (
                    %d,%s,%s,%s)', $email_tracking_key, prepare_mysql('Send to SES Error'), prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql(json_encode(array('error'=>$e->getMessage())))


                );
                $db->exec($sql);



              //  print $sql;
                $response = array(
                    'state'      => 400,
                    'msg'        => "Error, email not send",
                    'code'       => $e->getMessage(),
                    'error_code' => 'unknown'


                );
            }


            echo json_encode($response);
            exit;


        } else {
            $response = array(
                'state'      => 400,
                'error_code' => 'email_not_register'
            );
            echo json_encode($response);
            exit;
        }
    } else {
        $response = array(
            'state'      => 400,
            'error_code' => 'unknown'
        );
        echo json_encode($response);
        exit;
    }


}


?>
