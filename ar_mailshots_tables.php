<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 May 2018 at 11:30:25 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/object_functions.php';


if (!$user->can_view('stores')) {
    echo json_encode(
        array(
            'state' => 405,
            'resp'  => 'Forbidden'
        )
    );
    exit;
}


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


$tipo = $_REQUEST['tipo'];

switch ($tipo) {
    case 'mailshots':
        mailshots(get_table_parameters(), $db, $user);
        break;
    case 'account_mailshots':
        account_mailshots(get_table_parameters(), $db, $user);
        break;
    case 'subject_sent_emails':
        subject_sent_emails(get_table_parameters(), $db, $user);
        break;
    case 'sent_emails':
        sent_emails(get_table_parameters(), $db, $user);
        break;
    case 'user_notification_sent_emails':
        user_notification_sent_emails(get_table_parameters(), $db, $user);
        break;
    case 'email_tracking_events':
        email_tracking_events(get_table_parameters(), $db, $user);
        break;
    case 'oss_notification_next_recipients':
        oss_notification_next_recipients(get_table_parameters(), $db, $user);
        break;
    case 'gr_reminder_next_recipients':
        gr_reminder_next_recipients(get_table_parameters(), $db, $user);
        break;
    case 'previous_mailshots':
        previous_mailshots(get_table_parameters(), $db, $user);
        break;
    case 'other_stores_mailshots':
        other_stores_mailshots(get_table_parameters(), $db, $user);
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


function subject_sent_emails($_data, $db, $user) {

    $rtext_label = 'email';
    include_once 'prepare_table/init.php';


    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    $parent = get_object($_data['parameters']['parent'], $_data['parameters']['parent_key']);

    // print $sql;
    //'Ready','Send to SES','Rejected by SES','Send','Read','Hard Bounce','Soft Bounce','Spam','Delivered','Opened','Clicked','Error'


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Email Tracking State']) {
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
                    $state = $data['Email Tracking State'];
            }


            $subject = sprintf('<span class="link" onclick="change_view(\'%s/%d/%d/email/%d\')"  >%s</span>', strtolower($parent->get_object_name()).'s', $parent->get('Store Key'), $parent->id, $data['Email Tracking Key'], $data['Published Email Template Subject']);

            if ($_data['parameters']['parent'] == 'prospect_agent') {
                $recipient = sprintf('<span class="link" onclick="change_view(\'prospects/%d/%d\')"  >%s</span>', $data['store_key'], $data['recipient_key'], $data['recipient_name']);
                $email     = sprintf('<span class="link" onclick="change_view(\'report/prospect_agents/%d/email/%d\')"  >%s</span>', $_data['parameters']['parent_key'], $data['Email Tracking Key'], $data['Email Tracking Email']);

            } else {
                $recipient = '';
                $email     = '';
            }


            $adata[] = array(
                'id'        => (integer)$data['Email Tracking Key'],
                'state'     => $state,
                'subject'   => $subject,
                'recipient' => $recipient,
                'email'     => $email,
                'date'      => strftime("%a, %e %b %Y %R", strtotime($data['Email Tracking Created Date'].' +0:00')),


            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function email_tracking_events($_data, $db, $user) {

    $rtext_label = 'event';

    include_once 'prepare_table/init.php';
    include_once 'utils/parse_email_status_codes.php';


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;


    $adata = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            $status_code = parse_email_status_code($data['Email Tracking Event Type'], $data['Email Tracking Event Status Code']);

            switch ($data['Email Tracking Event Type']) {
                case 'Clicked':
                    $event = _('Clicked');
                    // $_data = json_decode($data['data'], true);

                    // $note = $_data['userAgent'].' '.$_data['ipAddress'];
                    $note = $data['Email Tracking Event Note'];
                    break;
                case 'Opened':
                    $event = _('Opened');
                    // $_data = json_decode($data['data'], true);

                    // $note = $_data['userAgent'].' '.$_data['ipAddress'];
                    $note = $data['Email Tracking Event Note'];
                    break;
                case 'Hard Bounce':
                    $event = _('Hard bounce');

                    $note = sprintf('<span>%s</span>', $status_code);

                    if ($data['Email Tracking Event Note'] != '') {
                        $note .= ' <span class="discreet italic">('.$data['Email Tracking Event Note'].')</span>';
                    }

                    //$_data = json_decode($data['Email Tracking Event Data'], true);
                    //print_r($_data);
                    break;
                case 'Soft Bounce':
                    $event = _('Soft bounce');

                    $note = sprintf('<span>%s</span>', $status_code);
                    if ($data['Email Tracking Event Note'] != '') {
                        $note .= ' <span class="discreet italic">('.$data['Email Tracking Event Note'].')</span>';
                    }
                    //$_data = json_decode($data['Email Tracking Event Data'], true);
                    //print_r($_data);

                    break;
                default:

                    $event = $data['Email Tracking Event Type'];
                    $note  = '';
            }

            $adata[] = array(
                'id'   => (integer)$data['Email Tracking Event Key'],
                'date' => strftime("%a %e %b %Y %H:%M %Z ", strtotime($data['Email Tracking Event Date'].' +0:00')),

                'note'  => $note,
                'event' => $event,


            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function sent_emails($_data, $db, $user) {

    $rtext_label = 'email';



    $parent = get_object($_data['parameters']['parent'], $_data['parameters']['parent_key']);



    if ($_data['parameters']['parent'] == 'mailshot' or $_data['parameters']['parent'] == 'email_campaign') {
        $email_campaign_type = get_object('email_campaign_type', $parent->get('Email Campaign Email Template Type Key'));
    } elseif ($_data['parameters']['parent'] == 'customer') {
        $_parent = get_object('customer', $_data['parameters']['parent_key']);
    }


    include_once 'prepare_table/init.php';


    include_once 'utils/parse_email_status_codes.php';


    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();



    if ($_data['parameters']['parent'] == 'mailshot') {

        if ($email_campaign_type->get('Email Campaign Type Scope') == 'Marketing') {
            $link = 'marketing/%d/emails/%d/mailshot/%d/tracking/%d';
        } else {
            $link = 'customers/%d/notifications/%d/mailshot/%d/tracking/%d';
        }


    }


    //'Ready','Send to SES','Rejected by SES','Send','Read','Hard Bounce','Soft Bounce','Spam','Delivered','Opened','Clicked','Error'


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            switch ($data['Email Campaign Type Code']) {
                case 'Newsletter':
                    $_type = _('Newsletter');
                    break;
                case 'Marketing':
                    $_type = _('Marketing mailshot');
                    break;
                case 'AbandonedCart':
                    $_type = _('Orders in basket');
                    break;
                case 'OOS Notification':
                    $_type = _('Back in stock email');
                    break;
                case 'Registration':
                    $_type = _('Welcome email');
                    break;
                case 'Password Reminder':
                    $_type = _('Password reset email');
                    break;
                case 'Order Confirmation':
                    $_type = _('Order confirmation');
                    break;
                case 'GR Reminder':
                    $_type = _('Reorder reminder');
                    break;
                case 'Invite Mailshot':
                    $_type = _('Invitation');
                    break;
                case 'Invite Full Mailshot':
                    $_type = _('Invitation mailshot');
                    break;
                case 'Invite':
                    $_type = _('Personalized invitation');
                    break;
                default:
                    $_type = $data['Email Campaign Type Code'];


            }

            switch ($data['Email Tracking State']) {
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

                    $status_code = parse_email_status_code($data['Email Tracking State'], $data['Email Tracking Delivery Status Code']);

                    $state = '<span class="error"><i class="fa fa-exclamation-circle"></i>  '.$status_code.'</span>';
                    break;
                case 'Soft Bounce':
                    $status_code = parse_email_status_code($data['Email Tracking State'], $data['Email Tracking Delivery Status Code']);
                    $state       = '<span class="warning"><i class="fa fa-exclamation-circle error"></i>  '.$status_code.'</span>';


                    break;
                case 'Spam':
                    $state = '<span class="error"><i class="fa fa-exclamation-circle"></i>  '._('Mark as spam').'</span>';
                    break;
                default:
                    $state = $data['Email Tracking State'];
            }

            $state = sprintf('<span class="email_tracking_state_%d">%s</span>', $data['Email Tracking Key'], $state);

            $customer  = sprintf('<span class="link" onclick="change_view(\'customers/%d/%d\')"  >%s (%05d)</span>', $data['store_key'], $data['recipient_key'], $data['recipient_name'], $data['recipient_key']);
            $prospect = sprintf('<span class="link" onclick="change_view(\'prospects/%d/%d\')"  >%s (%05d)</span>', $data['store_key'], $data['recipient_key'], $data['recipient_name'], $data['recipient_key']);

            $subject = '';
            if ($_data['parameters']['parent'] == 'email_campaign_type') {




                switch ($parent->get('Email Campaign Type Scope')) {
                    case 'Customer Notification':
                        $email = sprintf('<span class="link" onclick="change_view(\'customers/%d/notifications/%d/tracking/%d\')"  >%s</span>', $parent->get('Store Key'), $parent->id, $data['Email Tracking Key'], $data['Email Tracking Email']);
                        break;
                    case 'Marketing':
                        $email = sprintf('<span class="link" onclick="change_view(\'marketing/%d/emails/%d/tracking/%d\')"  >%s</span>', $parent->get('Store Key'), $parent->id, $data['Email Tracking Key'], $data['Email Tracking Email']);
                        break;
                    default:
                        $email = sprintf('<span class="link" onclick="change_view(\'email_campaign_type/%d/%d/tracking/%d\')"  >%s</span>', $parent->get('Store Key'), $parent->id, $data['Email Tracking Key'], $data['Email Tracking Email']);

                }


            } elseif ($_data['parameters']['parent'] == 'mailshot') {

                $email = sprintf(
                    '<span class="link" onclick="change_view(\''.$link.'\')"  >%s</span>', $email_campaign_type->get('Store Key'), $email_campaign_type->id, $parent->id, $data['Email Tracking Key'], $data['Email Tracking Email']
                );

            } elseif ($_data['parameters']['parent'] == 'customer') {
                $email   = '';
                $subject = sprintf(
                    '<span class="link" onclick="change_view(\'customers/%d/%d/email/%d\')"  >%s</span>', $_parent->get('Store Key'), $_parent->id, $data['Email Tracking Key'], $data['Published Email Template Subject']
                );

            } elseif ($_data['parameters']['parent'] == 'email_campaign') {


                $subject = '';

                if ($parent->get('Email Campaign Type') == 'AbandonedCart') {
                    $email = sprintf(
                        '<span class="link" onclick="change_view(\'orders/%d/dashboard/website/mailshots/%d/tracking/%d\')"  >%s</span>', $data['store_key'], $parent->id, $data['Email Tracking Key'], $data['Email Tracking Email']
                    );
                } else {
                    $email = sprintf(
                        '<span class="link" onclick="change_view(\'email_campaign_type/%d/%d/mailshot/%d/tracking/%d\')"  >%s</span>', $email_campaign_type->get('Store Key'), $email_campaign_type->id, $parent->id, $data['Email Tracking Key'],
                        $data['Email Tracking Email']
                    );
                }


            }




            $adata[] = array(
                'id'       => (integer)$data['Email Tracking Key'],
                'state'    => $state,
                'email'    => $email,
                'type'     => $_type,
                'subject'  => $subject,
                'prospect' => $prospect,
                'customer' => $customer,
                'date'     => strftime("%a, %e %b %Y %R:%S %Z", strtotime($data['Email Tracking Created Date'].' +0:00')),


            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function user_notification_sent_emails($_data, $db, $user) {

    $rtext_label = 'email';
    include_once 'prepare_table/init.php';
    include_once 'utils/parse_email_status_codes.php';


    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    $parent = get_object($_data['parameters']['parent'], $_data['parameters']['parent_key']);
    if ($_data['parameters']['parent'] == 'mailshot' or $_data['parameters']['parent'] == 'email_campaign') {
        $email_campaign_type = get_object('email_campaign_type', $parent->get('Email Campaign Email Template Type Key'));
    } elseif ($_data['parameters']['parent'] == 'customer') {
        $_parent = get_object('customer', $_data['parameters']['parent_key']);
    }

    if ($_data['parameters']['parent'] == 'mailshot') {

        if ($email_campaign_type->get('Email Campaign Type Scope') == 'Marketing') {
            $link = 'marketing/%d/emails/%d/mailshot/%d/tracking/%d';


        } else {
            $link = 'customers/%d/notifications/%d/mailshot/%d/tracking/%d';

        }


    }


    //'Ready','Send to SES','Rejected by SES','Send','Read','Hard Bounce','Soft Bounce','Spam','Delivered','Opened','Clicked','Error'


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Email Tracking State']) {
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

                    $status_code = parse_email_status_code($data['Email Tracking State'], $data['Email Tracking Delivery Status Code']);

                    $state = '<span class="error"><i class="fa fa-exclamation-circle"></i>  '.$status_code.'</span>';
                    break;
                case 'Soft Bounce':
                    $status_code = parse_email_status_code($data['Email Tracking State'], $data['Email Tracking Delivery Status Code']);
                    $state       = '<span class="warning"><i class="fa fa-exclamation-circle error"></i>  '.$status_code.'</span>';


                    break;
                case 'Spam':
                    $state = '<span class="error"><i class="fa fa-exclamation-circle"></i>  '._('Mark as spam').'</span>';
                    break;
                default:
                    $state = $data['Email Tracking State'];
            }

            $state = sprintf('<span class="email_tracking_state_%d">%s</span>', $data['Email Tracking Key'], $state);


            if ($data['recipient_key']) {
                $recipient = sprintf('<span class="link" onclick="change_view(\'users/%d\')"  >%s</span>', $data['recipient_key'], $data['recipient_name']);

            } else {
                $recipient = sprintf('<span class="italic discreet"   >%s</span>', _('External email'));

            }

            $subject = '';

            $email = sprintf('<span class="link" onclick="change_view(\'store/%d/notifications/%d/tracking/%d\')"  >%s</span>', $parent->get('Store Key'), $parent->id, $data['Email Tracking Key'], $data['Email Tracking Email']);


            $adata[] = array(
                'id'        => (integer)$data['Email Tracking Key'],
                'state'     => $state,
                'email'     => $email,
                'subject'   => $subject,
                'recipient' => $recipient,
                'date'      => strftime("%a, %e %b %Y %R:%S", strtotime($data['Email Tracking Created Date'].' +0:00')),


            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function oss_notification_next_recipients($_data, $db, $user) {

    $rtext_label = 'customer';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction  limit $start_from,$number_results";
    $adata = array();


    //print $sql;
    //'Ready','Send to SES','Rejected by SES','Send','Read','Hard Bounce','Soft Bounce','Spam','Delivered','Opened','Clicked','Error'


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            $customer = sprintf('<span class="link" onclick="change_view(\'customers/%d/%d\')"  >%s (%05d)</span>', $data['Customer Store Key'], $data['Customer Key'], $data['Customer Name'], $data['Customer Key']);

            $products = '';
            //print_r($data);
            $products_data = preg_split('/\,/', $data['products']);
            // print_r($products_data);
            foreach ($products_data as $product_data) {
                $_product_data = preg_split('/\|/', $product_data);

                $products .= sprintf('<span class="link" onclick="change_view(\'products/%d/%d\')" >%s</span>, ', $data['Customer Store Key'], $_product_data[0], $_product_data[1]);
            }
            $products = preg_replace('/, $/', '', $products);

            $adata[] = array(
                'id' => (integer)$data['Back in Stock Reminder Customer Key'],

                'customer' => $customer,
                'products' => $products,


            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function gr_reminder_next_recipients($_data, $db, $user) {

    $rtext_label = 'customer';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group order by $order $order_direction  limit $start_from,$number_results";
    $adata = array();


    //print $sql;
    //exit;
    //'Ready','Send to SES','Rejected by SES','Send','Read','Hard Bounce','Soft Bounce','Spam','Delivered','Opened','Clicked','Error'


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            //print_r($data);


            $customer   = sprintf('<span class="link" onclick="change_view(\'customers/%d/%d\')"  >%s (%05d)</span>', $data['Customer Store Key'], $data['Customer Key'], $data['Customer Name'], $data['Customer Key']);
            $last_order = sprintf('<span class="link" onclick="change_view(\'orders/%d/%d\')"  >%s</span>', $data['Customer Store Key'], $data['Order Key'], $data['Order Public ID']);


            $adata[] = array(
                'id' => (integer)$data['Customer Key'],

                'customer'   => $customer,
                'last_order' => $last_order,


            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function mailshots($_data, $db, $user) {


    $email_template_type = get_object('email_template_type', $_data['parameters']['parent_key']);

    if ($email_template_type->get('Code') == 'Newsletter') {
        $rtext_label = 'newsletter';

    } else {
        $rtext_label = 'mailshot';

    }


    if ($email_template_type->get('Email Campaign Type Scope') == 'Marketing') {
        $link = 'marketing/%d/emails/%d/mailshot/%d';


    } else {
        $link = 'customers/%d/notifications/%d/mailshot/%d';

    }


    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group order by $order $order_direction  limit $start_from,$number_results";
    $adata = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Email Campaign State']) {

                case 'ComposingEmail':
                    $state = _('Composing email');
                    break;
                case 'Sent':
                    $state = _('Sent');
                    break;
                default:
                    $state = $data['Email Campaign State'];
            }

            $subject = $data['Email Template Subject'];
            if ($subject == '') {
                $subject = '<span class="discreet italic">'.$data['Email Campaign Name'].'</span>';

            }


            $name = sprintf(
                '<span class="link" onclick="change_view(\''.$link.'\')"  title="%s" >%s</span>', $data['Email Campaign Store Key'], $_data['parameters']['parent_key'], $data['Email Campaign Key'], $data['Email Campaign Name'], $subject
            );


            $adata[] = array(
                'id' => (integer)$data['Email Campaign Key'],

                'name'  => sprintf('<span class="name_%d">%s</span>', $data['Email Campaign Key'], $name),
                'state' => sprintf('<span class="state_%d">%s</span>', $data['Email Campaign Key'], $state),

                'date' => sprintf('<span class="date_%d">%s</span>', $data['Email Campaign Key'], strftime("%a, %e %b %Y %R", strtotime($data['Email Campaign Last Updated Date'].' +0:00'))),
                'sent' => sprintf('<span class="sent_%d">%s</span>', $data['Email Campaign Key'], number($data['Email Campaign Sent'])),


                'bounces' => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Delivered'] == 0 ? 'super_discreet' : ($data['Email Campaign Bounces'] == 0 ? 'success super_discreet' : '')), number($data['Email Campaign Bounces']),
                    percentage($data['Email Campaign Bounces'], $data['Email Campaign Sent'])
                ),

                'hard_bounces' => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Delivered'] == 0 ? 'super_discreet' : ($data['Email Campaign Hard Bounces'] == 0 ? 'success super_discreet' : '')), number($data['Email Campaign Hard Bounces']),
                    percentage($data['Email Campaign Hard Bounces'], $data['Email Campaign Sent'])
                ),
                'soft_bounces' => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Delivered'] == 0 ? 'super_discreet' : ($data['Email Campaign Soft Bounces'] == 0 ? 'success super_discreet' : '')), number($data['Email Campaign Soft Bounces']),
                    percentage($data['Email Campaign Soft Bounces'], $data['Email Campaign Sent'])
                ),


                'delivered' => ($data['Email Campaign Sent'] == 0 ? '<span class="super_discreet">'._('NA').'</span>' : number($data['Email Campaign Delivered'])),

                'open'    => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Delivered'] == 0 ? 'super_discreet' : ''), number($data['Email Campaign Open']), percentage($data['Email Campaign Open'], $data['Email Campaign Delivered'])
                ),
                'clicked' => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Delivered'] == 0 ? 'super_discreet' : ''), number($data['Email Campaign Clicked']), percentage($data['Email Campaign Clicked'], $data['Email Campaign Delivered'])
                ),
                'spam'    => sprintf(
                    '<span class="%s " title="%s">%s</span>', ($data['Email Campaign Delivered'] == 0 ? 'super_discreet' : ($data['Email Campaign Spams'] == 0 ? 'success super_discreet' : '')), number($data['Email Campaign Spams']),
                    percentage($data['Email Campaign Spams'], $data['Email Campaign Delivered'])
                ),


            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function account_mailshots($_data, $db, $user) {


    $rtext_label = 'mailshot';


    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group order by $order $order_direction  limit $start_from,$number_results";
    $adata = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Email Campaign State']) {

                case 'ComposingEmail':
                    $state = _('Composing email');
                    break;
                case 'Sent':
                    $state = _('Sent');
                    break;
                default:
                    $state = $data['Email Campaign State'];
            }

            switch ($data['Email Campaign Type']) {

                case 'Newsletter':
                    $type = _('Newsletter');
                    break;
                default:
                    $type = $data['Email Campaign Type'];
            }

            $type = sprintf('<span class="link" onclick="change_view(\'email_campaign_type/%d/%d\')">%s</span>', $data['Store Key'], $data['Email Campaign Email Template Type Key'], $type);


            $name = sprintf(
                '<span class="link" onclick="change_view(\'email_campaign_type/%d/%d/mailshot/%d\')"  >%s</span>', $data['Store Key'], $data['Email Campaign Email Template Type Key'], $data['Email Campaign Key'], $data['Email Campaign Name']
            );


            $adata[] = array(
                'id'    => (integer)$data['Email Campaign Key'],
                'type'  => $type,
                'store' => sprintf('<span class="link" onclick="change_view(\'customers/%d/email_campaigns\')" title="%s">%s</span>', $data['Store Key'], $data['Store Name'], $data['Store Code']),


                'name'  => sprintf('<span class="name_%d">%s</span>', $data['Email Campaign Key'], $name),
                'state' => sprintf('<span class="state_%d">%s</span>', $data['Email Campaign Key'], $state),

                'date' => sprintf('<span class="date_%d">%s</span>', $data['Email Campaign Key'], strftime("%a, %e %b %Y %R", strtotime($data['Email Campaign Last Updated Date'].' +0:00'))),
                'sent' => sprintf('<span class="sent_%d">%s</span>', $data['Email Campaign Key'], number($data['Email Campaign Sent'])),


                'bounces' => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Delivered'] == 0 ? 'super_discreet' : ($data['Email Campaign Bounces'] == 0 ? 'success super_discreet' : '')), number($data['Email Campaign Bounces']),
                    percentage($data['Email Campaign Bounces'], $data['Email Campaign Sent'])
                ),

                'hard_bounces' => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Delivered'] == 0 ? 'super_discreet' : ($data['Email Campaign Hard Bounces'] == 0 ? 'success super_discreet' : '')), number($data['Email Campaign Hard Bounces']),
                    percentage($data['Email Campaign Hard Bounces'], $data['Email Campaign Sent'])
                ),
                'soft_bounces' => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Delivered'] == 0 ? 'super_discreet' : ($data['Email Campaign Soft Bounces'] == 0 ? 'success super_discreet' : '')), number($data['Email Campaign Soft Bounces']),
                    percentage($data['Email Campaign Soft Bounces'], $data['Email Campaign Sent'])
                ),


                'delivered' => ($data['Email Campaign Sent'] == 0 ? '<span class="super_discreet">'._('NA').'</span>' : number($data['Email Campaign Delivered'])),

                'open'    => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Delivered'] == 0 ? 'super_discreet' : ''), number($data['Email Campaign Open']), percentage($data['Email Campaign Open'], $data['Email Campaign Delivered'])
                ),
                'clicked' => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Delivered'] == 0 ? 'super_discreet' : ''), number($data['Email Campaign Clicked']), percentage($data['Email Campaign Clicked'], $data['Email Campaign Delivered'])
                ),
                'spam'    => sprintf(
                    '<span class="%s " title="%s">%s</span>', ($data['Email Campaign Delivered'] == 0 ? 'super_discreet' : ($data['Email Campaign Spams'] == 0 ? 'success super_discreet' : '')), number($data['Email Campaign Spams']),
                    percentage($data['Email Campaign Spams'], $data['Email Campaign Delivered'])
                ),


            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function previous_mailshots($_data, $db, $user) {


    $mailshot     = get_object('Mailshot', $_data['parameters']['parent_key']);
    $mailshot_key = $mailshot->id;
    if ($mailshot->get('Email Campaign Type') == 'Newsletter') {
        $rtext_label = 'newsletter';

    } else {
        $rtext_label = 'mailshot';

    }
    $_data['parameters']['email_campaign_type_key'] = $mailshot->get('Email Campaign Email Template Type Key');


    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group order by $order $order_direction  limit $start_from,$number_results";
    $adata = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            $name = sprintf(
                '<span class="link" onclick="change_view(\'marketing/%d/emails/%d/mailshot/%d\')"  >%s</span>', $data['Email Campaign Store Key'], $_data['parameters']['parent_key'], $data['Email Campaign Key'], $data['Email Campaign Name']
            );


            $operations = '';


            $operations .= sprintf(
                '<span class="button" onClick="clone_sent_mailshot_from_table(this,\'Mailshot\',%d,%d,\'%s\')"><i class="fa fa-clone fa-fw"></i> %s</span>', $mailshot_key, $data['Email Campaign Key'], base64_url_decode($_data['parameters']['redirect']), _('Clone me')
            );


            $adata[] = array(
                'id' => (integer)$data['Email Campaign Key'],

                'name' => sprintf('<span class="name_%d">%s</span>', $data['Email Campaign Key'], $name),

                'date'    => sprintf('<span class="date_%d">%s</span>', $data['Email Campaign Key'], strftime("%a, %e %b %Y", strtotime($data['Email Campaign Last Updated Date'].' +0:00'))),
                'sent'    => sprintf('<span class="sent_%d">%s</span>', $data['Email Campaign Key'], number($data['Email Campaign Sent'])),
                'subject' => $data['Email Template Subject'],

                'operations' => $operations,

                'hard_bounces' => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Delivered'] == 0 ? 'super_discreet' : ($data['Email Campaign Hard Bounces'] == 0 ? 'success super_discreet' : '')), number($data['Email Campaign Hard Bounces']),
                    percentage($data['Email Campaign Hard Bounces'], $data['Email Campaign Sent'])
                ),
                'soft_bounces' => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Delivered'] == 0 ? 'super_discreet' : ($data['Email Campaign Soft Bounces'] == 0 ? 'success super_discreet' : '')), number($data['Email Campaign Soft Bounces']),
                    percentage($data['Email Campaign Soft Bounces'], $data['Email Campaign Sent'])
                ),


                'delivered' => ($data['Email Campaign Sent'] == 0 ? '<span class="super_discreet">'._('NA').'</span>' : number($data['Email Campaign Delivered'])),

                'open'    => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Delivered'] == 0 ? 'super_discreet' : ''), number($data['Email Campaign Open']), percentage($data['Email Campaign Open'], $data['Email Campaign Delivered'])
                ),
                'clicked' => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Delivered'] == 0 ? 'super_discreet' : ''), number($data['Email Campaign Clicked']), percentage($data['Email Campaign Clicked'], $data['Email Campaign Delivered'])
                ),
                'spam'    => sprintf(
                    '<span class="%s " title="%s">%s</span>', ($data['Email Campaign Delivered'] == 0 ? 'super_discreet' : ($data['Email Campaign Spams'] == 0 ? 'success super_discreet' : '')), number($data['Email Campaign Spams']),
                    percentage($data['Email Campaign Spams'], $data['Email Campaign Delivered'])
                ),


            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function other_stores_mailshots($_data, $db, $user) {


    $mailshot     = get_object('Mailshot', $_data['parameters']['parent_key']);
    $mailshot_key = $mailshot->id;
    if ($mailshot->get('Email Campaign Type') == 'Newsletter') {
        $rtext_label = 'newsletter';

    } else {
        $rtext_label = 'mailshot';

    }


    $_data['parameters']['email_campaign_type_key'] = $mailshot->get('Email Campaign Email Template Type Key');

    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group order by $order $order_direction  limit $start_from,$number_results";
    $adata = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            $name = sprintf(
                '<span class="link" onclick="change_view(\'marketing/%d/emails/%d/mailshot/%d\')"  >%s</span>', $data['Email Campaign Store Key'], $_data['parameters']['parent_key'], $data['Email Campaign Key'], $data['Email Campaign Name']
            );


            $operations = '';


            $operations .= sprintf(
                '<span class="button" onClick="clone_sent_mailshot_from_table(this,\'Mailshot\',%d,%d,\'%s\')"><i class="fa fa-clone fa-fw"></i> %s</span>', $mailshot_key, $data['Email Campaign Key'], base64_url_decode($_data['parameters']['redirect']), _('Clone me')
            );


            $adata[] = array(
                'id' => (integer)$data['Email Campaign Key'],

                'name'  => sprintf('<span class="name_%d">%s</span>', $data['Email Campaign Key'], $name),
                'store' => sprintf('<span title="%s">%s</span>', $data['Store Name'], $data['Store Code']),

                'date'    => sprintf('<span class="date_%d">%s</span>', $data['Email Campaign Key'], strftime("%a, %e %b %Y", strtotime($data['Email Campaign Last Updated Date'].' +0:00'))),
                'sent'    => sprintf('<span class="sent_%d">%s</span>', $data['Email Campaign Key'], number($data['Email Campaign Sent'])),
                'subject' => $data['Email Template Subject'],

                'operations' => $operations,

                'hard_bounces' => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Delivered'] == 0 ? 'super_discreet' : ($data['Email Campaign Hard Bounces'] == 0 ? 'success super_discreet' : '')), number($data['Email Campaign Hard Bounces']),
                    percentage($data['Email Campaign Hard Bounces'], $data['Email Campaign Sent'])
                ),
                'soft_bounces' => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Delivered'] == 0 ? 'super_discreet' : ($data['Email Campaign Soft Bounces'] == 0 ? 'success super_discreet' : '')), number($data['Email Campaign Soft Bounces']),
                    percentage($data['Email Campaign Soft Bounces'], $data['Email Campaign Sent'])
                ),


                'delivered' => ($data['Email Campaign Sent'] == 0 ? '<span class="super_discreet">'._('NA').'</span>' : number($data['Email Campaign Delivered'])),

                'open'    => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Delivered'] == 0 ? 'super_discreet' : ''), number($data['Email Campaign Open']), percentage($data['Email Campaign Open'], $data['Email Campaign Delivered'])
                ),
                'clicked' => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Delivered'] == 0 ? 'super_discreet' : ''), number($data['Email Campaign Clicked']), percentage($data['Email Campaign Clicked'], $data['Email Campaign Delivered'])
                ),
                'spam'    => sprintf(
                    '<span class="%s " title="%s">%s</span>', ($data['Email Campaign Delivered'] == 0 ? 'super_discreet' : ($data['Email Campaign Spams'] == 0 ? 'success super_discreet' : '')), number($data['Email Campaign Spams']),
                    percentage($data['Email Campaign Spams'], $data['Email Campaign Delivered'])
                ),


            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}

?>
