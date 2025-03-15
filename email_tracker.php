<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 May 2018 at 14:57:32 CEST, Mijas Costa, Spain
 Copyright (c) 2018, Inikoo

 Version 3

*/

if (extension_loaded('newrelic')) {
newrelic_background_job();
}
require_once 'keyring/dns.php';
require_once 'keyring/au_deploy_conf.php';

require 'vendor/autoload.php';
require_once 'utils/sentry.php';

require_once 'utils/parse_user_agent.php';
require_once 'utils/natural_language.php';
require_once 'utils/parse_email_status_codes.php';
require_once 'utils/i18n.php';


use Aws\Sns\Message;
use Aws\Sns\MessageValidator;


if ('POST' !== $_SERVER['REQUEST_METHOD']) {
    http_response_code(405);
    die;
}

require_once 'utils/general_functions.php';


require_once 'utils/object_functions.php';

$editor = array(
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Email tracker'
);


$db = new PDO(
    "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


$account = get_object('Account', 1);

$_locale = $account->get('Account Locale').'.UTF-8';


set_locale($_locale);

$locale = $_locale;


$sns       = Message::fromRawPostData();
$validator = new MessageValidator();
if ($validator->isValid($sns)) {


    if (in_array(
        $sns['Type'], array(
                        'SubscriptionConfirmation',
                        'UnsubscribeConfirmation'
                    )
    )) {

        file_get_contents($sns['SubscribeURL']);
    } else {

        $sns_id = $sns['MessageId'];


        $sql = sprintf('select `Email Tracking Event Key` from `Email Tracking Event Dimension` where `Email Tracking Event Message ID`=%s ', prepare_mysql($sns_id));
        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                http_response_code(200);
                exit;
            }
        } else {
            http_response_code(200);
            exit;
        }

        $message = json_decode($sns['Message'], true);

        $sql = sprintf('select `Email Tracking Key`  from `Email Tracking Dimension`  where `Email Tracking SES ID`=%s  ', prepare_mysql($message['mail']['messageId']));

        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {

                $event_type = '';
                $event_data = '';

                $date = gmdate('Y-m-d H:i:s', strtotime($message['mail']['timestamp']));


                //'Sent','Rejected by SES','Send','Read','Hard Bounce','Soft Bounce','Spam','Delivered','Opened','Clicked','Send to SES Error'
                $note        = '';
                $status_code = '';
                switch ($message['eventType']) {
                    case 'Send':
                    case 'Delivery':
                        exit();
                        break;


                    case 'Open':

                        require_once 'utils/ip_geolocation.php';
                        require_once 'utils/parse_user_agent.php';


                        $event_type = 'Opened';
                        $date       = gmdate('Y-m-d H:i:s', strtotime($message['open']['timestamp']));

                        unset($message['open']['timestamp']);
                        $event_data = $message['open'];

                        if (isset($event_data['ipAddress'])) {
                            $ips = preg_split('/,/', $event_data['ipAddress']);
                            //print_r($ips);

                            foreach ($ips as $ip) {
                                $geolocation_data = get_ip_geolocation(trim($ip), $db);
                                $note             = $geolocation_data['Location'];
                            }
                        }


                        $user_agent_note = '';

                        if (isset($event_data['userAgent'])) {
                            $user_agent_data = parse_user_agent(trim($event_data['userAgent']), $db);

                            if (is_array($user_agent_data) and $user_agent_data['Status'] == 'OK') {


                                if ($user_agent_data['Icon'] != '') {
                                    $user_agent_note = ' <i title="'.$user_agent_data['Device'].'" class="far '.$user_agent_data['Icon'].'"></i> ';
                                } else {
                                    $user_agent_note = $user_agent_data['Device'].' ';
                                }


                                $user_agent_note .= $user_agent_data['Software'];

                                if ($user_agent_data['Software Details'] != '') {
                                    $user_agent_note .= ' <span class="discreet italic">('.$user_agent_data['Software Details'].')</span>';
                                }


                            }


                        }

                        if ($user_agent_note != '') {
                            $note .= ', '.$user_agent_note;
                        }
                        $note = preg_replace('/^, /', '', $note);


                        break;

                    case 'Click':

                        if (isset($message['linkTags']['type']['unsubscribe'])) {
                            // ignore Unsubscribe link clicks
                            http_response_code(200);
                            exit;
                        }

                        $event_type = 'Clicked';
                        $date       = gmdate('Y-m-d H:i:s', strtotime($message['click']['timestamp']));

                        unset($message['click']['timestamp']);
                        $event_data = $message['click'];


                        if (isset($event_data['link'])) {
                            $note = $event_data['link'];
                        }


                        break;
                    case 'Bounce':
                        $date       = gmdate('Y-m-d H:i:s', strtotime($message['bounce']['timestamp']));
                        $event_data = $message['bounce'];

                        unset($message['bounce']['timestamp']);


                        switch ($message['bounce']['bounceType']) {
                            case 'Undetermined':
                            case 'Transient':
                                $event_type = 'Soft Bounce';


                                break;

                            case 'Permanent':
                                $event_type = 'Hard Bounce';


                                break;

                        }

                        if (isset($event_data['bouncedRecipients'][0]['status'])) {
                            $status_code = $event_data['bouncedRecipients'][0]['status'];
                        } else {


                            $sql = "insert into atest  (`date`,`headers`,`request`) values (NOW(),?,?)  ";

                            $stmt = $db->prepare($sql);
                            $stmt->execute(
                                [
                                    $row['Email Tracking Key'],
                                    json_encode($message)
                                ]
                            );


                        }

                        if (isset($event_data['bouncedRecipients'][0]['diagnosticCode'])) {
                            $note = $event_data['bouncedRecipients'][0]['diagnosticCode'];
                        }


                        break;
                    case 'Complaint':
                        $date       = gmdate('Y-m-d H:i:s', strtotime($message['complaint']['timestamp']));
                        $event_data = $message['complaint'];

                        unset($message['complaint']['timestamp']);

                        $event_type = 'Spam';


                        break;

                    default:


                        $sql = "insert into atest  (`date`,`headers`,`request`) values (NOW(),?,?)  ";

                        $stmt = $db->prepare($sql);
                        $stmt->execute(
                            [
                                $row['Email Tracking Key'],
                                json_encode($message)
                            ]
                        );


                        break;
                }


                $sql = "insert into `Email Tracking Event Dimension`  (`Email Tracking Event Tracking Key`,`Email Tracking Event Type`,`Email Tracking Event Date`,`Email Tracking Event Data`,`Email Tracking Event Message ID`,`Email Tracking Event Note`,`Email Tracking Event Status Code`) 
                  values (?,?,?, ?,?,?,?)";


                $db->prepare($sql)->execute(
                    array(
                        $row['Email Tracking Key'],
                        $event_type,
                        $date,
                        json_encode($event_data),
                        $sns_id,
                        $note,
                        $status_code
                    )
                );


                $event_key = $db->lastInsertId();


                $email_tracking = get_object('email_tracking', $row['Email Tracking Key']);
                $email_tracking->update_state($event_type);


                if ($event_type == 'Hard Bounce' or $event_type == 'Soft Bounce') {


                    $bounce_type        = $event_type;
                    $bounce_status_code = $status_code;
                    $bounce_note        = $note;

                    $sql = "update `Email Tracking Dimension` set `Email Tracking Delivery Status Code`=? where `Email Tracking Key`=?";
                    $db->prepare($sql)->execute(
                        array(

                            $bounce_status_code,
                            $row['Email Tracking Key']
                        )
                    );

                    if($bounce_status_code=='5.7.1'){
                        $sql = "update `Email Tracking Dimension` set `Email Tracking Spam`='Yes' where `Email Tracking Key`=?";
                        $db->prepare($sql)->execute(
                            array(
                                $row['Email Tracking Key']
                            )
                        );

                    }


                    $sql = sprintf('select `Bounced Email Key`,`Bounced Email Bounce Type`,`Bounced Email Count` from `Bounced Email Dimension` where `Bounced Email`=%s  ', prepare_mysql($email_tracking->get('Email Tracking Email')));
                    if ($result3 = $db->query($sql)) {
                        if ($row3 = $result3->fetch()) {

                            $bounce_count = $row3['Bounced Email Count'] + 1;

                            $sql = sprintf(
                                "update  `Bounced Email Dimension` set `Bounced Email Bounce Type`=%s,`Bounced Email Status Code`=%s,`Bounced Email Count`=%d   ,`Bounced Email Source`='AU' where `Bounced Email Key`=%d  ", prepare_mysql($bounce_type),
                                prepare_mysql($bounce_status_code), $bounce_count, $row3['Bounced Email Key']
                            );
                            $db->exec($sql);

                        } else {
                            $sql = sprintf(
                                'insert into `Bounced Email Dimension` (`Bounced Email`,`Bounced Email Bounce Type`,`Bounced Email Status Code`,`Bounced Email Date`) values (%s,%s,%s,%s) ', prepare_mysql($email_tracking->get('Email Tracking Email')),
                                prepare_mysql($bounce_type), prepare_mysql($bounce_status_code), prepare_mysql(gmdate('Y-m-d H:i:s'))


                            );
                            $db->exec($sql);
                            $bounce_count = 1;

                        }


                        $unsubscribe_note = sprintf('<span>%s</span>', parse_email_status_code($bounce_type.' Bounce', $bounce_status_code));

                        if ($bounce_note != '') {
                            $unsubscribe_note .= ' <span class="discreet italic">('.$bounce_note.')</span>';
                        }


                        $sql   = "select `Customer Key` from `Customer Dimension` where `Customer Main Plain Email`=?  ";
                        $stmt2 = $db->prepare($sql);
                        $stmt2->execute(
                            array(
                                $email_tracking->get('Email Tracking Email')
                            )
                        );
                        while ($row2 = $stmt2->fetch()) {
                            $customer         = get_object('Customer', $row2['Customer Key']);
                            $customer->editor = $editor;

                            if ($bounce_type == 'Hard Bounce' or ($bounce_type == 'Soft Bounce' and $bounce_count > 100)) {

                                if ($customer->get('Customer Send Newsletter') == 'Yes' or $customer->get('Customer Send Email Marketing') == 'Yes' or $customer->get('Customer Send Basket Emails') == 'Yes') {
                                    $customer->unsubscribe(_('Unsubscribed to newsletter and marketing emails because email soft bounced several times').', '.$unsubscribe_note);
                                }
                                $customer->fast_update(array('Customer Email State' => 'Error'));
                            } else {
                                $customer->fast_update(array('Customer Email State' => 'Warning'));
                                $history_data = array(
                                    'History Abstract' => _('Email soft bounced').', '.$unsubscribe_note,
                                    'History Details'  => '',
                                    'Action'           => 'edited'
                                );
                                $customer->add_subject_history(
                                    $history_data, true, 'No', 'Changes', $customer->get_object_name(), $customer->id
                                );
                            }

                        }


                        $sql   = "select `Prospect Key` from `Prospect Dimension` where `Prospect Main Plain Email`=?  ";
                        $stmt2 = $db->prepare($sql);
                        $stmt2->execute(
                            array(
                                $email_tracking->get('Email Tracking Email')
                            )
                        );
                        while ($row2 = $stmt2->fetch()) {


                            if ($bounce_type == 'Hard Bounce' or ($bounce_type == 'Soft Bounce' and $bounce_count >= 3)) {
                                $prospect         = get_object('Prospect', $row2['Prospect Key']);
                                $prospect->editor = $editor;

                                $prospect->update_status('Bounced');


                            }

                        }


                    }


                }


                if ($event_type == 'Spam') {


                    foreach ($event_data['complainedRecipients'] as $_spam_recipients) {

                        $sql = "insert into `Email Spam Dimension` (`Email Spam Sender`,`Email Spam Recipient`,`Email Spam Tracking Event Key`,`Email Spam Date`) values (?,?,?,?) ";


                        $stmt = $db->prepare($sql);
                        $stmt->execute(
                            array(
                                $_spam_recipients['emailAddress'],
                                $message['mail']['source'],
                                $event_key,
                                gmdate('Y-m-d H:i:s')

                            )
                        );


                    }

                    $email_tracking->fast_update(array('Email Tracking Spam' => 'Yes'));


                }


                if ($email_tracking->get('Email Tracking Email Template Type Key') > 0) {


                    $sql = "insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ";


                    $_date = gmdate('Y-m-d H:i:s');
                    $stmt  = $db->prepare($sql);
                    $stmt->execute(
                        array(
                            $_date,
                            $_date,
                            'email_template_type_update_sent_emails_totals',
                            $email_tracking->get('Email Tracking Email Template Type Key'),
                            $_date
                        )
                    );


                }
                if ($email_tracking->get('Email Tracking Email Mailshot Key') > 0) {
                    /**
                     * @var $email_campaign \EmailCampaign
                     */
                    $email_campaign = get_object('email_campaign', $email_tracking->get('Email Tracking Email Mailshot Key'));
                    $email_campaign->update_sent_emails_totals();
                }
                if ($email_tracking->get('Email Tracking Email Template Key') > 0) {
                    /**
                     * @var $email_template \Email_Template
                     */
                    $email_template = get_object('email_template', $email_tracking->get('Email Tracking Email Template Key'));
                    $email_template->update_sent_emails_totals();
                }


                switch ($email_tracking->get('Email Tracking Recipient')) {
                    case 'Prospect':
                        /**
                         * @var $prospect \Prospect
                         */
                        $prospect = get_object('Prospect', $email_tracking->get('Email Tracking Recipient Key'));
                        $prospect->update_prospect_data();
                        break;
                    default:
                        break;
                }





                switch ($email_tracking->get('Email Tracking State')) {
                    case 'Ready':
                        $state = _('Ready to send');
                        break;
                    case 'Sent to SES':
                        $state = _('Sending');
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

                    $email_template_type = get_object('email_template_type', $email_campaign->get('Email Campaign Email Template Type Key'));
                    include_once 'utils/send_zqm_message.class.php';
                    send_zqm_message(json_encode(
                            array(
                                'channel' => 'real_time.'.strtolower($account->get('Account Code')),


                                'objects' => array(
                                    array(
                                        'object' => 'mailshot',
                                        'key'    => $email_campaign->id,

                                        'update_metadata' => array(
                                            'class_html' => array(
                                                '_Sent_Emails_Info'                      => $email_campaign->get('Sent Emails Info'),
                                                '_Email_Campaign_Sent'                   => $email_campaign->get('Sent'),
                                                'Email_Campaign_Bounces_Percentage'      => $email_campaign->get('Bounces Percentage'),
                                                'Email_Campaign_Hard_Bounces_Percentage' => $email_campaign->get('Hard Bounces Percentage'),
                                                'Email_Campaign_Soft_Bounces_Percentage' => $email_campaign->get('Soft Bounces Percentage'),
                                                //'Email_Campaign_Delivered'              => $email_campaign->get('Delivered'),
                                                //'Email_Campaign_Delivered_Percentage'              => $email_campaign->get('Delivered Percentage'),
                                                'Email_Campaign_Open'                    => $email_campaign->get('Open'),
                                                'Email_Campaign_Spams'                   => $email_campaign->get('Spams'),
                                                'Email_Campaign_Clicked'                 => $email_campaign->get('Clicked'),
                                                'Email_Campaign_Open_Percentage'         => $email_campaign->get('Open Percentage'),
                                                'Email_Campaign_Spams_Percentage'        => $email_campaign->get('Spams Percentage'),
                                                'Email_Campaign_Clicked_Percentage'      => $email_campaign->get('Clicked Percentage'),

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
                        ));


                }


                // maybe this is crashing server
//                include_once 'utils/new_fork.php';
//                new_housekeeping_fork(
//                    'au_aiku',
//                    array(
//                        'model'    => 'EmailTrackingEvent',
//                        'model_id' => $event_key,
//                        'field'    => ''
//                    ),
//                    DNS_ACCOUNT_CODE
//                );

            } else {

                $sql = "insert into atest  (`date`,`headers`,`request`) values (NOW(),?,?)  ";

                $stmt = $db->prepare($sql);
                $stmt->execute(
                    [
                        'xx',
                        json_encode($message)
                    ]
                );


            }
            http_response_code(200);
            exit;

        } else {
            //print_r($error_info = $db->errorInfo());
            //print "$sql\n";
            http_response_code(200);
            exit;
        }


    }
} else {

    http_response_code(404);
    exit;
}



