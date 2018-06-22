<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 June 2018 at 14:21:30 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/

use Aws\Ses\SesClient;


trait Send_Email {
    function send($recipient, $data) {

        require_once 'external_libs/aws.phar';

        $this->error = false;
        $account     = get_object('Account', 1);

        if (empty($data['Email_Template'])) {
            $email_template = get_object('Email_Template', $this->data['Published Email Template Email Template Key']);
        } else {
            $email_template = $data['Email_Template'];
            unset($data['Email_Template']);
        }
        if (empty($data['Email_Template_Type'])) {
            $email_template_type = get_object('Email_Template_Type', $email_template->get('Email Template Email Campaign Type Key'));
        } else {
            $email_template_type = $data['Email_Template_Type'];
            unset($data['Email_Template_Type']);
        }


        if ($this->get('Published Email Template Subject') == '') {

            $this->error = true;
            $this->send  = false;
            $this->msg   = _('Empty email subject');

            return;

        }



        $sender = get_object('Store', $email_template_type->get('Store Key'));




        if ($sender->get('Send Email Address') == '') {

            $this->error = true;
            $this->send  = false;
            $this->msg   = 'Sender email address not configured';

            return;


        }



        if (empty($data['Email_Tracking'])) {

            include_once 'class.Email_Tracking.php';

            $email_tracking_data = array(
                'Email Tracking Email' => $recipient->get('Main Plain Email'),

                'Email Tracking Email Template Type Key'      => $email_template_type->id,
                'Email Tracking Email Template Key'           => $email_template->id,
                'Email Tracking Published Email Template Key' => $this->id,
                'Email Tracking Recipient'                    => $recipient->get_object_name(),
                'Email Tracking Recipient Key'                => $recipient->id,

            );


            $email_tracking = new Email_Tracking('new', $email_tracking_data);
        } else {
            $email_tracking = $data['Email_Tracking'];
        }


        $placeholders = array(
            '[Greetings]'     => $recipient->get_greetings(),
            '[Customer Name]' => $recipient->get('Name'),
            '[Name]'          => $recipient->get('Main Contact Name'),
            '[Name,Company]'  => preg_replace('/^, /', '', $recipient->get('Main Contact Name').($recipient->get('Company Name') == '' ? '' : ', '.$recipient->get('Company Name'))),
            '[Signature]'     => $sender->get('Signature'),
        );

        switch ($email_template_type->get('Email Campaign Type Code')) {

            case 'Invite':
            case 'Invite Mailshot':

                $placeholders['[Prospect Name]'] = $recipient->get('Name');

                break;

            case 'Password Reminder':


                $placeholders['[Reset_Password_URL]'] = $data['Reset_Password_URL'];

                break;
            case 'GR Reminder':

                $order = get_object('Order', $data['Order Key']);


                $placeholders['[Order Number]'] = $order->get('Public ID');
                $placeholders['[Order Amount]'] = $order->get('Total');
                $placeholders['[Order Date]']   = $order->get('Dispatched Date');

                break;

            case 'Order Confirmation':

                $order=$data['Order'];

                $placeholders['[Order Number]'] = $order->get('Public ID');
                $placeholders['[Order Amount]'] = $order->get('Total');
                $placeholders['[Order Date]']   = $order->get('Dispatched Date');
                $placeholders['[Pay Info]']   =$data['Pay Info'];
                $placeholders['[Order]']   =$data['Order Info'];


            default:


        }




        $from_name = base64_encode($sender->get('Name'));
        $sender_email_address=$sender->get('Send Email Address');
        $_source   = "=?utf-8?B?$from_name?= <$sender_email_address>";

        $request                                    = array();
        $request['Source']                          = $_source;
        $request['Destination']['ToAddresses']      = array($recipient->get('Main Plain Email'));
        $request['Message']['Subject']['Data']      = $this->get('Published Email Template Subject');
        $request['Message']['Body']['Text']['Data'] = strtr($this->get('Published Email Template Text'), $placeholders);
        $request['ConfigurationSetName']            = $account->get('Account Code');



        if ($this->get('Published Email Template HTML') != '') {

            $request['Message']['Body']['Html']['Data'] = strtr($this->get('Published Email Template HTML'), $placeholders);

        }

        if ($email_template_type->get('Email Campaign Type Code') == 'GR Reminder') {


            $request['Message']['Body']['Text']['Data'] = preg_replace_callback(
                '/\[Order Date \+\s*(\d+)\s*days\]/', function ($match_data) use ($_date) {
                return strftime("%a, %e %b %Y", strtotime($_date.' +'.$match_data[1].' days'));
            }, $request['Message']['Body']['Text']['Data']
            );
            $request['Message']['Body']['Html']['Data'] = preg_replace_callback(
                '/\[Order Date \+\s*(\d+)\s*days\]/', function ($match_data) use ($_date) {
                return strftime("%a, %e %b %Y", strtotime($_date.' +'.$match_data[1].' days'));
            }, $request['Message']['Body']['Html']['Data']
            );

            $request['Message']['Body']['Text']['Data'] = preg_replace_callback(
                '/\[Order Date \+\s*(\d+)\s*weeks\]/', function ($match_data) use ($_date) {
                return strftime("%a, %e %b %Y", strtotime($_date.' +'.$match_data[1].' weeks'));
            }, $request['Message']['Body']['Text']['Data']
            );
            $request['Message']['Body']['Html']['Data'] = preg_replace_callback(
                '/\[Order Date \+\s*(\d+)\s*months\]/', function ($match_data) use ($_date) {
                return strftime("%a, %e %b %Y", strtotime($_date.' +'.$match_data[1].' months'));
            }, $request['Message']['Body']['Html']['Data']
            );


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


        try {
            $result = $client->sendEmail($request);


            $email_tracking->fast_update(
                array(
                    'Email Tracking State'  => "Sent to SES",
                    "Email Tracking SES Id" => $result->get('MessageId'),


                )
            );

            if (in_array(
                $email_template_type->get('Email Campaign Type Code'), array(
                                                                         'Order Confirmation',
                                                                         'Delivery Confirmation',
                                                                         'OOS Notification',
                                                                         'Password Reminder',
                                                                         'Invite',
                                                                         'Invite Mailshot',
                                                                         'GR Reminder',
                                                                         'Registration'
                                                                     )
            )) {

                $sql = sprintf(
                    'insert into `Email Tracking Email Copy` (`Email Tracking Email Copy Key`,`Email Tracking Email Copy Subject`,`Email Tracking Email Copy Body`) values (%d,%s,%s)  ',
                    $email_tracking->id,
                    prepare_mysql($request['Message']['Subject']['Data']),
                    (isset($request['Message']['Body']['Html']['Data'])?prepare_mysql($request['Message']['Body']['Html']['Data']):prepare_mysql($request['Message']['Body']['Text']['Data'])


                    )


                );
                $this->db->exec($sql);

            }


            $this->send = true;
            $this->email_tracking=$email_tracking;

        } catch (Exception $e) {


            $email_tracking->fast_update(
                array(
                    'Email Tracking State' => "Rejected by SES",


                )
            );

            $sql = sprintf(
                'insert into `Email Tracking Event Dimension` (
              `Email Tracking Event Tracking Key`,`Email Tracking Event Type`,
              `Email Tracking Event Date`,`Email Tracking Event Data`) values (
                    %d,%s,%s,%s)', $email_tracking->id, prepare_mysql('Send to SES Error'), prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql(json_encode(array('error' => $e->getMessage())))


            );
            $this->db->exec($sql);


            $this->error = true;
            $this->msg   = _('Error, email not send').' '.$e->getMessage();

        }

        include_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'                    => 'update_email_template_data',
            'email_template_key'      => $email_template->id,
            'email_template_type_key' => $email_template_type->id,
        ), $account->get('Account Code')
        );


    }


}


?>
