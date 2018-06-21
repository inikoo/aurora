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


require_once 'class.EmailCampaignType.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);


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

if ($result=$db->query($sql)) {
		foreach ($result as $row) {

		    $email_campaign_type=get_object('email_campaign_type',$row['Email Campaign Type Key']);

		    if($email_campaign_type->get('Email Campaign Type Email Template Key')==''){
                $email_campaign_type->fast_update(array('Email Campaign Type Status'=>'InProcess'));
            }else{

		        $email_template=get_object('EmailTemplate',$email_campaign_type->get('Email Campaign Type Email Template Key'));

		        if($email_template->get('Email Template Published Email Key')==''){
                    $email_campaign_type->fast_update(array('Email Campaign Type Status'=>'InProcess'));

                }


            }


		}
}else {
		print_r($error_info=$db->errorInfo());
		print "$sql\n";
		exit;
}


