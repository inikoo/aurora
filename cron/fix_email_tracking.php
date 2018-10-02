<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 June 2018 at 13:38:53 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/natural_language.php';
require_once 'utils/date_functions.php';
require_once 'utils/object_functions.php';
require_once 'utils/ip_geolocation.php';
require_once 'utils/parse_user_agent.php';



require_once 'class.EmailCampaignType.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);


//
$sql = sprintf('select * from `Email Tracking Event Dimension` where `Email Tracking Event Tracking Key`=3093860 ');
$sql = sprintf('select * from `Email Tracking Event Dimension` ');


if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $type = $row['Email Tracking Event Type'];


        switch ($row['Email Tracking Event Type']) {
            case 'Clicked':

                $_data = json_decode($row['Email Tracking Event Data'], true);
               // print_r($_data);

                $note=$_data['link'];
                $sql = sprintf(
                    'update `Email Tracking Event Dimension` set `Email Tracking Event Note`=%s where `Email Tracking Event Key`=%d ', prepare_mysql($note), $row['Email Tracking Event Key']
                );

                //print "$sql\n";

                $db->exec($sql);

                break;
            case 'Opened':

                $_data = json_decode($row['Email Tracking Event Data'], true);


                $ips=preg_split('/\,/',$_data['ipAddress']);
                //print_r($ips);

                foreach ($ips as $ip){
                    $geolocation_data = get_ip_geolocation(trim($ip), $db);
                  // print_r($geolocation_data);


                    $note = $geolocation_data['Location'];


                }
                $user_agent_note='';
                if(isset($_data['userAgent'])){
                    $user_agent_data = parse_user_agent(trim($_data['userAgent']), $db);

                    if( is_array($user_agent_data) and $user_agent_data['Status']=='OK'){

                        // exit;
                       // print_r($user_agent_data);




                        if($user_agent_data['Icon']!=''){
                            $user_agent_note=' <i title="'.$user_agent_data['Device'].'" class="far '.$user_agent_data['Icon'].'"></i> ';
                        }else{
                            $user_agent_note=$user_agent_data['Device'].' ';
                        }


                        $user_agent_note.=$user_agent_data['Software'];

                        if($user_agent_data['Software Details']!=''){
                            $user_agent_note.=' <span class="discreet italic">('.$user_agent_data['Software Details'].')</span>';
                        }




                    }


                }

                if($user_agent_note!=''){
                    $note.=', '.$user_agent_note;
                }
                $note=preg_replace('/^\, /','',$note);

                $sql = sprintf(
                    'update `Email Tracking Event Dimension` set `Email Tracking Event Note`=%s where `Email Tracking Event Key`=%d ', prepare_mysql($note), $row['Email Tracking Event Key']
                );

                //print "$sql\n";

                $db->exec($sql);
//exit;

               // exit;
                break;
            case 'Soft Bounce':
            case 'Hard Bounce':


                $_data = json_decode($row['Email Tracking Event Data'], true);
                if (isset($_data['bouncedRecipients'][0]['status'])) {
                    $status_code = $_data['bouncedRecipients'][0]['status'];
                }
                if (isset($_data['bouncedRecipients'][0]['diagnosticCode'])) {
                    $note = $_data['bouncedRecipients'][0]['diagnosticCode'];
                }

                $sql = sprintf(
                    'update `Email Tracking Event Dimension` set `Email Tracking Event Type`=%s ,`Email Tracking Event Status Code`=%s ,`Email Tracking Event Note`=%s where `Email Tracking Event Key`=%d ', prepare_mysql($type), prepare_mysql($status_code),
                    prepare_mysql($note), $row['Email Tracking Event Key']
                );
                $db->exec($sql);


                $sql = sprintf(
                    'update `Email Tracking Dimension` set `Email Tracking Delivery Status Code`=%s  where `Email Tracking Key`=%d  and `Email Tracking State` in ("Hard Bounce","Soft Bounce")', prepare_mysql($status_code), $row['Email Tracking Event Tracking Key']
                );
                $db->exec($sql);
                break;
            default:

                $event = $row['Email Tracking Event Type'];
                $note  = '';
        }


    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


exit;
// remove duplicated events


$sql = sprintf('select `Email Tracking Key` from `Email Tracking Dimension` ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $sql = sprintf('select * from `Email Tracking Event Dimension` where   `Email Tracking Event Tracking Key`=%d and `Email Tracking Event Message ID` is null order by 	`Email Tracking Event Key`', $row['Email Tracking Key']);


        if ($result = $db->query($sql)) {
            foreach ($result as $row2) {
                //print_r($row2);


                $sql = sprintf('select `Email Tracking Event Type` from `Email Tracking Event Dimension` where `Email Tracking Event Key`=%s', $row2['Email Tracking Event Key']);

                if ($result3 = $db->query($sql)) {
                    if ($row3 = $result3->fetch()) {
                        $sql = sprintf(
                            'delete from `Email Tracking Event Dimension` where `Email Tracking Event Tracking Key`=%d  and `Email Tracking Event Date`=%s and `Email Tracking Event Data`=%s and `Email Tracking Event Type`=%s 
                            and `Email Tracking Event Key`!=%d ', $row['Email Tracking Key'], prepare_mysql($row2['Email Tracking Event Date']), prepare_mysql($row2['Email Tracking Event Data']), prepare_mysql($row2['Email Tracking Event Type']),
                            $row2['Email Tracking Event Key']

                        );
                        //print "$sql\n";

                        $db->exec($sql);
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    //  print "$sql\n";
                    exit;
                }


            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }

        $email_tracking = get_object('Email_Tracking', $row['Email Tracking Key']);


        $sql = sprintf('select count(*) as num from  `Email Tracking Event Dimension` where `Email Tracking Event Tracking Key`=%d and `Email Tracking Event Type`="Clicked" ', $row['Email Tracking Key']);

        if ($result = $db->query($sql)) {
            if ($rowx = $result->fetch()) {
                $email_tracking->fast_update(
                    array('Email Tracking Number Clicks' => $rowx['num'])
                );

            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }

        $sql = sprintf('select count(*) as num from  `Email Tracking Event Dimension` where `Email Tracking Event Tracking Key`=%d and `Email Tracking Event Type`="Opened" ', $row['Email Tracking Key']);

        if ($result = $db->query($sql)) {
            if ($rowx = $result->fetch()) {
                $email_tracking->fast_update(
                    array('Email Tracking Number Reads' => $rowx['num'])
                );

            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }

    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


exit;

$sql = sprintf('select `Email Tracking Key` from `Email Tracking Dimension` ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $sql = sprintf('select count(*) as num, min(`Email Tracking Event Date`) as min_date from `Email Tracking Event Dimension` where `Email Tracking Event Type`="Clicked" and `Email Tracking Event Tracking Key`=%d ', $row['Email Tracking Key']);


        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {

                if ($row2['num'] > 0) {
                    $email_tracking = get_object('Email_Tracking', $row['Email Tracking Key']);
                    $data_to_update = array(
                        'Email Tracking Number Clicks'      => $row2['num'],
                        'Email Tracking First Clicked Date' => $row2['min_date'],
                    );
                    $email_tracking->fast_update($data_to_update);


                }


            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


exit;
// old fixes

$email_campaign_types = array(
    'Newsletter',
    'Marketing',
    'GR Reminder',
    'AbandonedCart',
    'OOS Notification',
    'Registration',
    'Password Reminder',
    'Order Confirmation',
    'Delivery Confirmation',
    'Invite',
    'Invite Mailshot',
);

$sql = sprintf("SELECT `Store Key` FROM `Store Dimension`");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $store = get_object('Store', $row['Store Key']);
        foreach ($email_campaign_types as $email_campaign_type) {
            $sql = sprintf(
                'insert into `Email Campaign Type Dimension`  (`Email Campaign Type Store Key`,`Email Campaign Type Code`) values (%d,%s) ', $store->id, prepare_mysql($email_campaign_type)

            );
            //print "$sql\n";
            $db->exec($sql);


            $email_campaign_type = new EmailCampaignType('code_store', $email_campaign_type, $store->id);


            if ($email_campaign_type->get('Email Campaign Type Code') == 'Registration') {


                $website = get_object('Website', $store->get('Website Key'));

                $registration_page = $website->get_webpage('register.sys');
                $scope_metadata    = $registration_page->get('Scope Metadata');

                $email_campaign_type->fast_update(array('Email Campaign Type Email Template Key' => $scope_metadata['emails']['welcome']['key']));


                $sql = sprintf(
                    'update `Email Template Dimension` set `Email Template Email Campaign Type Key`=%d where `Email Template Key`=%d  ', $email_campaign_type->id, $scope_metadata['emails']['welcome']['key']

                );
                $db->exec($sql);

            } elseif ($email_campaign_type->get('Email Campaign Type Code') == 'Password Reminder') {


                $website = get_object('Website', $store->get('Website Key'));

                $registration_page = $website->get_webpage('login.sys');

                $scope_metadata = $registration_page->get('Scope Metadata');


                $email_campaign_type->fast_update(array('Email Campaign Type Email Template Key' => $scope_metadata['emails']['reset_password']['key']));

                $sql = sprintf(
                    'update `Email Template Dimension` set `Email Template Email Campaign Type Key`=%d where `Email Template Key`=%d  ', $email_campaign_type->id, $scope_metadata['emails']['reset_password']['key']

                );
                $db->exec($sql);

            } elseif ($email_campaign_type->get('Email Campaign Type Code') == 'Order Confirmation') {


                $website = get_object('Website', $store->get('Website Key'));

                $registration_page = $website->get_webpage('checkout.sys');
                $scope_metadata    = $registration_page->get('Scope Metadata');

                $email_campaign_type->fast_update(array('Email Campaign Type Email Template Key' => $scope_metadata['emails']['order_confirmation']['key']));

                $sql = sprintf(
                    'update `Email Template Dimension` set `Email Template Email Campaign Type Key`=%d where `Email Template Key`=%d  ', $email_campaign_type->id, $scope_metadata['emails']['order_confirmation']['key']

                );
                $db->exec($sql);
            } elseif ($email_campaign_type->get('Email Campaign Type Code') == 'OOS Notification') {


                $_metadata = array(

                    'Schedule' => array(

                        'Days'     => array(
                            'Monday'    => 'Yes',
                            'Tuesday'   => 'Yes',
                            'Wednesday' => 'Yes',
                            'Thursday'  => 'Yes',
                            'Friday'    => 'Yes',
                            'Saturday'  => 'Yes',
                            'Sunday'    => 'Yes'
                        ),
                        'Time'     => '16:00:00',
                        'Timezone' => $store->get('Store Timezone')
                    )

                );

                // print_r($_metadata);


                $email_campaign_type->fast_update(array('Email Campaign Type Metadata' => json_encode($_metadata)));


            } elseif ($email_campaign_type->get('Email Campaign Type Code') == 'GR Reminder') {


                $_metadata = array(
                    'Send After' => 20,
                    'Schedule'   => array(

                        'Days'     => array(
                            'Monday'    => 'Yes',
                            'Tuesday'   => 'Yes',
                            'Wednesday' => 'Yes',
                            'Thursday'  => 'Yes',
                            'Friday'    => 'Yes',
                            'Saturday'  => 'Yes',
                            'Sunday'    => 'Yes'
                        ),
                        'Time'     => '16:00:00',
                        'Timezone' => $store->get('Store Timezone')
                    )

                );

                // print_r($_metadata);


                $email_campaign_type->fast_update(array('Email Campaign Type Metadata' => json_encode($_metadata)));


            }


        }
    }
}


$sql = sprintf('select * from `Email Template Dimension`');


if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $email_template = get_object('EmailTemplate', $row['Email Template Key']);

        if ($email_template->get('Email Template Scope') == 'EmailCampaignType') {
            $email_template->fast_update(
                array(
                    'Email Template Email Campaign Type Key' => $email_template->get('Email Template Scope Key')
                )
            );

        }

        // official roles
        //'AbandonedCart','Delivery Confirmation','GR Reminder','Invite','Invite Mailshot','Marketing','Newsletter','OOS Notification','Order Confirmation','Password Reminder','Registration'

        // old roles
        //'Delivery Confirmation','GR Reminder','Invite Mailshot','OOS Notification','Order Confirmation','Order_Confirmation','Password Reminder','Registration','Reset_Password','Welcome'

        if ($email_template->get('Email Template Role') == 'Invitation Mailshot') {
            $email_template->fast_update(
                array(
                    'Email Template Role' => 'Invite Mailshot'
                )
            );
        }


        if ($email_template->get('Email Template Role') == 'Reset_Password') {
            $email_template->fast_update(
                array(
                    'Email Template Role' => 'Password Reminder'
                )
            );
        }
        if ($email_template->get('Email Template Role') == 'Order_Confirmation') {
            $email_template->fast_update(
                array(
                    'Email Template Role' => 'Order Confirmation'
                )
            );
        }
        if ($email_template->get('Email Template Role') == 'Welcome') {
            $email_template->fast_update(
                array(
                    'Email Template Role' => 'Registration'
                )
            );
        }

        if ($email_template->get('Email Template Scope') == 'Webpage') {

            $webpage             = get_object('Webpage', $email_template->get('Email Template Scope Key'));
            $website             = get_object('Website', $webpage->get('Webpage Website Key'));
            $email_template_type = get_object('Email_Template_Type', $email_template->get('Email Template Role').'|'.$website->get('Website Store Key'), 'code_store');

            if ($email_template_type->id) {
                $email_template->fast_update(
                    array(
                        'Email Template Scope Key' => $email_template_type->id,
                        'Email Template Scope'     => 'EmailCampaignType'
                    )
                );
            } else {

                // print_r($website);

                exit('email_template_type not found from role'.$email_template->get('Email Template Role'));
            }


        }


    }
} else {
    print_r($error_info = $this->db->errorInfo());
    print "$sql\n";
    exit;
}


$sql = sprintf('select * from `Email Tracking Dimension`');
if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $recipient = get_object($row['Email Tracking Recipient'], $row['Email Tracking Recipient Key']);

        $sql = sprintf(
            'update `Email Tracking Dimension` set `Email Tracking Email`=%s   where `Email Tracking Key`=%d ', prepare_mysql($recipient->get('Main Plain Email')), $row['Email Tracking Key']
        );


        $db->exec($sql);


        /*

        if ($row['Email Tracking Scope'] == 'Registration') {


            $sql = sprintf('select `Email Campaign Type Key`  from `Email Campaign Type Dimension` where `Email Campaign Type Email Template Key`=%d ', $row['Email Tracking Email Template Key']);
            if ($result2 = $db->query($sql)) {
                if ($row2 = $result2->fetch()) {
                    $sql = sprintf(
                        'update `Email Tracking Dimension` set `Email Tracking Email Template Type Key`=%d   where `Email Tracking Key`=%d ', $row2['Email Campaign Type Key'], $row['Email Tracking Key']
                    );

                    $db->exec($sql);

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }

        }
        if ($row['Email Tracking Scope'] == 'Order Confirmation') {


            $sql = sprintf('select `Email Campaign Type Key`  from `Email Campaign Type Dimension` where `Email Campaign Type Email Template Key`=%d ', $row['Email Tracking Email Template Key']);
            if ($result2 = $db->query($sql)) {
                if ($row2 = $result2->fetch()) {
                    $sql = sprintf(
                        'update `Email Tracking Dimension` set `Email Tracking Email Template Type Key`=%d   where `Email Tracking Key`=%d ', $row2['Email Campaign Type Key'], $row['Email Tracking Key']
                    );

                    $db->exec($sql);

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }

        }
        if ($row['Email Tracking Scope'] == 'Password Reminder') {


            $sql = sprintf('select `Email Campaign Type Key`  from `Email Campaign Type Dimension` where `Email Campaign Type Email Template Key`=%d ', $row['Email Tracking Email Template Key']);
            if ($result2 = $db->query($sql)) {
                if ($row2 = $result2->fetch()) {
                    $sql = sprintf(
                        'update `Email Tracking Dimension` set `Email Tracking Email Template Type Key`=%d   where `Email Tracking Key`=%d ', $row2['Email Campaign Type Key'], $row['Email Tracking Key']
                    );

                    $db->exec($sql);

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }

        }

        */

    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


$sql = sprintf('select * from `Email Blueprint Dimension`');


if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $email_blueprint = get_object('EmailBlueprint', $row['Email Blueprint Key']);
        $parent          = get_object($email_blueprint->get('Email Blueprint Scope'), $email_blueprint->get('Email Blueprint Scope Key'));


        switch ($email_blueprint->get('Email Blueprint Scope')) {
            case 'Webpage':

                switch ($parent->get('Webpage Code')) {
                    case 'register.sys':
                        $scope_metadata = $parent->get('Scope Metadata');
                        $email_template = get_object('EmailTemplate', $scope_metadata['emails']['welcome']['key']);
                        break;
                    case 'login.sys':
                        $scope_metadata = $parent->get('Scope Metadata');
                        $email_template = get_object('EmailTemplate', $scope_metadata['emails']['reset_password']['key']);
                        break;
                    case 'checkout.sys':
                        $scope_metadata = $parent->get('Scope Metadata');
                        $email_template = get_object('EmailTemplate', $scope_metadata['emails']['order_confirmation']['key']);
                        break;


                }

                $email_blueprint->fast_update(
                    array(
                        'Email Blueprint Email Campaign Type Key' => $email_template->get('Email Template Email Campaign Type Key'),
                        'Email Blueprint Email Template Key'      => $email_template->id

                    )
                );

                break;

            case 'EmailCampaignType':
                $email_blueprint->fast_update(
                    array(
                        'Email Blueprint Email Campaign Type Key' => $parent->id,
                        'Email Blueprint Email Template Key'      => $parent->get('Email Campaign Type Email Template Key')

                    )
                );
                break;


        }


    }
} else {
    print_r($error_info = $this->db->errorInfo());
    print "$sql\n";
    exit;
}

$sql = sprintf('select * from `Email Tracking Dimension` where `Email Tracking State`="Sent to SES"  and `Email Tracking Created Date`<"2018-06-09 00:00:00" ');
if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $sql = sprintf('delete from `Email Tracking Event Dimension` where `Email Tracking Event Tracking Key`=%d ', $row['Email Tracking Key']);
        $db->exec($sql);
        $sql = sprintf('delete from `Email Tracking Dimension` where `Email Tracking Key`=%d ', $row['Email Tracking Key']);
        $db->exec($sql);

    }
} else {
    print_r($error_info = $this->db->errorInfo());
    print "$sql\n";
    exit;
}

$sql = sprintf('select * from `Email Campaign Type Dimension`');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $email_campaign_type = get_object('email_campaign_type', $row['Email Campaign Type Key']);

        if ($email_campaign_type->get('Email Campaign Type Email Template Key') == '') {
            $email_campaign_type->fast_update(array('Email Campaign Type Status' => 'InProcess'));
        } else {

            $email_template = get_object('EmailTemplate', $email_campaign_type->get('Email Campaign Type Email Template Key'));

            if ($email_template->get('Email Template Published Email Key') == '') {
                $email_campaign_type->fast_update(array('Email Campaign Type Status' => 'InProcess'));

            }


        }


    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


