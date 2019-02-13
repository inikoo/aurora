<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 May 2018 at 13:41:52 CEST, Trnava, Slovakia

 Copyright (c) 2018, Inikoo

 Version 3.0


*/


include_once 'class.DB_Table.php';

class EmailCampaignType extends DB_Table {

    var $new = false;
    var $updated_data = array();

    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {


        global $db;
        $this->db = $db;

        $this->table_name    = 'Email Campaign Type';
        $this->ignore_fields = array('Email Campaign Type Key',);

        if (!$arg1 and !$arg2) {
            $this->error = true;
            $this->msg   = 'No arguments';
        }
        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }


        if (is_array($arg2) and preg_match('/find|new/i', $arg1)) {
            $this->find($arg2, 'create');

            return;
        }


        $this->get_data($arg1, $arg2, $arg3);

    }

    function get_data($tipo, $tag, $tag2 = '') {


        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Email Campaign Type Dimension` WHERE  `Email Campaign Type Key`=%d", $tag
            );
        } elseif ($tipo == 'code_store') {
            $sql = sprintf(
                "SELECT * FROM `Email Campaign Type Dimension` WHERE  `Email Campaign Type Code`=%s and `Email Campaign Type Store Key`=%d ", prepare_mysql($tag), $tag2
            );
        } else {
            return;
        }


        // print "$sql\n";

        if ($this->data = $this->db->query($sql)->fetch()) {

            $this->id = $this->data['Email Campaign Type Key'];
        }


        switch ($this->get('Email Campaign Type Type')) {
            case 'AbandonedCart':

                $sql = sprintf(
                    "SELECT * FROM `Email Campaign Type Abandoned Cart Dimension` WHERE  `Email Campaign Type Abandoned Cart Email Campaign Type Key`=%d", $tag
                );


                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        foreach ($row as $key => $value) {
                            $this->data[$key] = $value;
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


                break;
            default:

        }


    }

    function get($key) {

        if (!$this->id) {
            return false;
        }

        switch ($key) {
            case 'Metadata':
                if ($this->data['Email Campaign Type '.$key] == '') {
                    $content_data = false;
                } else {
                    $content_data = json_decode($this->data['Email Campaign Type '.$key], true);
                }

                return $content_data;
                break;


            case 'Send After':
                $_metadata = $this->get('Metadata');

                return sprintf(ngettext('%s day', '%s days', $_metadata['Send After']), number($_metadata['Send After']));

            case 'Schedule Time':
                $_metadata = $this->get('Metadata');


                date_default_timezone_set($_metadata['Schedule']['Timezone']);

                return strftime('%R %Z', strtotime('2018-01-01 '.$_metadata['Schedule']['Time'].' '.$_metadata['Schedule']['Timezone']));

                break;

            case 'Send Email Address':
                $store = get_object('store', $this->get('Store Key'));

                return $store->get('Store Email');
            case 'Icon':
                switch ($this->data['Email Campaign Type Code']) {
                    case 'Newsletter':
                        $icon = 'newspaper';
                        break;
                    case 'Marketing':
                        $icon = 'bullhorn';
                        break;
                    case 'AbandonedCart':
                        $icon = 'basket';
                        break;
                    case 'OOS Notification':
                        $icon = 'dolly';
                        break;
                    case 'Registration':
                        $icon = 'door-open';
                        break;
                    case 'Password Reminder':
                        $icon = 'lock-open';
                        break;
                    case 'Order Confirmation':
                        $icon = 'shopping-cart';
                        break;
                    case 'Delivery Confirmation':
                        $icon = 'truck';
                        break;
                    case 'GR Reminder':
                        $icon = 'bell';
                        break;
                    default:
                        $icon = '';


                }

                return $icon;
                break;

            case 'Label':

                switch ($this->data['Email Campaign Type Code']) {
                    case 'Newsletter':
                        $name = _('Newsletters');
                        break;
                    case 'Marketing':
                        $name = _('Mailshots');
                        break;
                    case 'AbandonedCart':
                        $name = _('Orders in basket');
                        break;
                    case 'OOS Notification':
                        $name = _('Back in stock emails');
                        break;
                    case 'Registration':
                        $name = _('Welcome emails');
                        break;
                    case 'Password Reminder':
                        $name = _('Password reset emails');
                        break;
                    case 'Order Confirmation':
                        $name = _('Order confirmations');
                        break;
                    case 'GR Reminder':
                        $name = _('Reorder reminders');
                        break;
                    case 'Invite Mailshot':
                        $name = _("Prospect's invitations");
                        break;
                    case 'Invite':
                        $name = _("Personalised prospect invitations");
                        break;
                    default:
                        $name = $this->data['Email Campaign Type Code'];


                }

                return $name;
                break;

            case 'Status Label':


                if ($this->get('Email Campaign Type Scope') == 'Marketing') {
                    return '';
                }

                switch ($this->get('Email Campaign Type Status')) {
                    case 'InProcess':

                        if ($this->get('Email Campaign Type Email Template Key') == '') {
                            $label = ' <span class="discreet "> <i class="far small fa-seedling"></i> '._('Email template not set').'</span>';

                        } else {
                            $label = ' <span class="discreet "> <i class="far small fa-seedling"></i> '._('Email template not saved').'</span>';

                        }

                        break;
                    case 'Active':
                        $label = ' <span class="success"> <i class="fa small fa-broadcast-tower"></i> '._('Live').'</span>';

                        break;
                    case 'Suspended':
                        $label = ' <span class="error"> <i class="fa small fa-stop"></i> '._('Suspended').'</span>';

                        break;

                }

                return $label;

                break;
            case 'Signature':


                $store = get_object('Store', $this->data['Email Campaign Type Store Key']);

                return $store->get('Email Template Signature');

                break;

            default:
                if (isset($this->data[$key])) {
                    return $this->data[$key];
                }

                if (array_key_exists('Email Campaign Type '.$key, $this->data)) {
                    return $this->data[$this->table_name.' '.$key];
                }
        }

        return false;
    }

    function get_field_label($field) {

        switch ($field) {

            case 'Email Campaign Type Name':
                $label = _('name');
                break;

            default:
                $label = $field;

        }

        return $label;

    }

    function suspend() {

        $this->update_field_switcher('Email Campaign Type Status', 'Suspended');

    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        switch ($field) {
            case 'Email Campaign Type Send After':

                $metadata               = $this->get('Metadata');
                $metadata['Send After'] = $value;


                $this->update_field('Email Campaign Type Metadata', json_encode($metadata), 'no_history');

                break;
            case 'Email Campaign Type Schedule Days Monday':
            case 'Email Campaign Type Schedule Days Tuesday':
            case 'Email Campaign Type Schedule Days Wednesday':
            case 'Email Campaign Type Schedule Days Thursday':
            case 'Email Campaign Type Schedule Days Friday':
            case 'Email Campaign Type Schedule Days Saturday':
            case 'Email Campaign Type Schedule Days Sunday':

                $day                                = preg_replace('/^Email Campaign Type Schedule Days /', '', $field);
                $metadata                           = $this->get('Metadata');
                $metadata['Schedule']['Days'][$day] = $value;
                $this->update_field('Email Campaign Type Metadata', json_encode($metadata), 'no_history');

                break;
            case 'Email Campaign Type Schedule Time':

                $metadata                     = $this->get('Metadata');
                $metadata['Schedule']['Time'] = $value;
                $this->update_field('Email Campaign Type Metadata', json_encode($metadata), 'no_history');

                break;
            case 'Email Campaign Type Status':

                $this->update_status($value);
                break;

            case 'Scope Metadata':

                $this->update_field('Email Campaign Type '.$field, $value, $options);
                break;

            case 'Email Campaign Type Abandoned Cart Days Inactive in Basket':
                $this->fast_update(array('Email Campaign Type Abandoned Cart Days Inactive in Basket' => $value), 'Email Campaign Type Abandoned Cart Dimension');
                $this->update_estimated_recipients();


                $this->update_metadata = array(
                    'class_html' => array(
                        'Email_Campaign_Number_Estimated_Emails' => $this->get('Email Campaign Type Number Estimated Emails'),
                        'Number_Estimated_Emails'                => $this->get('Number Estimated Emails'),

                    ),

                );


                break;


            default:
                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {
                    if ($value != $this->data[$field]) {

                        $this->update_field($field, $value, $options);
                    }
                }

        }
    }

    function update_status($value = '') {


        if ($this->get('Email Campaign Type Email Template Key') == '') {

            $value = 'InProcess';
        } else {
            $email_template = get_object('Email Template', $this->data['Email Campaign Type Email Template Key']);

            if (!$email_template->get('Email Template Published Email Key') > 0) {
                $value = 'InProcess';
            }

        }

        if ($value == '') {
            $value = 'Active';
        }


        $this->update_field('Email Campaign Type Status', $value);


        $this->update_metadata = array(
            'class_html' => array(
                'Status_Label' => $this->get('Status Label'),

            ),

        );


    }

    function activate() {

        $this->update_field_switcher('Email Campaign Type Status', 'Active');

    }

    function update_sent_emails_totals() {

        $unsubscribed = 0;
        $open         = 0;
        $scheduled    = 0;
        $sent         = 0;
        $clicked      = 0;
        $mailshots    = 0;
        $errors       = 0;
        $delivered    = 0;
        $hard_bounces = 0;
        $soft_bounces = 0;
        $spam         = 0;


        //'Send to SES',Send',Read
        //'Sent','Sent to SES','Ready',,'Rejected by SES',','','Hard Bounce','Soft Bounce','Spam','Delivered','Opened','Clicked','Error'


        //     'Ready','Sent to SES','Rejected by SES','Sent','Soft Bounce','Hard Bounce','Delivered','Spam','Opened','Clicked','Error'


        $sql = sprintf('select count(*) as num ,`Email Tracking State` from `Email Tracking Dimension` where `Email Tracking Email Template Type Key`=%d group by `Email Tracking State` ', $this->id);
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Email Tracking State'] == 'Ready') {
                    $scheduled = $row['num'];
                }

                if (in_array(
                    $row['Email Tracking State'], array(
                                                    'Sent',
                                                    'Sent to SES',
                                                    'Soft Bounce',
                                                    'Hard Bounce',
                                                    'Delivered',
                                                    'Spam',
                                                    'Opened',
                                                    'Clicked'
                                                )
                )) {
                    $sent += $row['num'];

                }

                if (in_array(
                    $row['Email Tracking State'], array(
                                                    'Delivered',
                                                    'Spam',
                                                    'Opened',
                                                    'Clicked'
                                                )
                )) {
                    $delivered += $row['num'];
                }
                if (in_array(
                    $row['Email Tracking State'], array(
                                                    'Opened',
                                                    'Clicked'
                                                )
                )) {
                    $open += $row['num'];
                }
                if ($row['Email Tracking State'] == 'Clicked') {
                    $clicked = $row['num'];
                }
                if ($row['Email Tracking State'] == 'Rejected by SES') {
                    $errors = $row['num'];
                }
                if ($row['Email Tracking State'] == 'Hard Bounce') {
                    $hard_bounces = $row['num'];
                }
                if ($row['Email Tracking State'] == 'Soft Bounce') {
                    $soft_bounces = $row['num'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf('select count(*) as num  from `Email Tracking Dimension` where `Email Tracking Email Template Type Key`=%d and `Email Tracking Spam`="Yes" ', $this->id);
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $spam = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf('select count(*) as num  from `Email Tracking Dimension` where `Email Tracking Email Template Type Key`=%d and `Email Tracking Unsubscribed`="Yes" ', $this->id);
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $unsubscribed = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf('select count(*) as num  from `Email Campaign Dimension` where `Email Campaign Email Template Type Key`=%d  ', $this->id);
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $mailshots = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->fast_update(
            array(
                'Email Campaign Type Scheduled' => $scheduled,
                'Email Campaign Type Sent'      => $sent,
                'Email Campaign Type Delivered' => $delivered,

                'Email Campaign Type Open'    => $open,
                'Email Campaign Type Clicked' => $clicked,

                'Email Campaign Type Errors'       => $errors,
                'Email Campaign Type Hard Bounces' => $hard_bounces,
                'Email Campaign Type Soft Bounces' => $soft_bounces,
                'Email Campaign Type Spams'        => $spam,
                'Email Campaign Type Unsubscribed' => $unsubscribed,
                'Email Campaign Type Mailshots'    => $mailshots


            )

        );


    }


    function create_mailshot() {


        if ($this->get_estimated_recipients() > 0 or $this->data['Email Campaign Type Code'] == 'create_mailshot' or $this->data['Email Campaign Type Code'] == 'Marketing') {
            include_once 'class.EmailCampaign.php';
            $email_campaign_data = array(
                'Email Campaign Store Key'               => $this->data['Email Campaign Type Store Key'],
                'Email Campaign Name'                    => date('Y.m.d'),
                'Email Campaign Type'                    => $this->data['Email Campaign Type Code'],
                'Email Campaign Email Template Type Key' => $this->id,
                'editor'                                 => $this->editor
            );

            if ($this->data['Email Campaign Type Code'] == 'GR Reminder') {

                $metadata                                                 = $this->get('Metadata');
                $email_campaign_data['Email Campaign Metadata']           = json_encode(array('Send After' => $metadata['Send After']));
                $email_campaign_data['Email Campaign Email Template Key'] = $this->data['Email Campaign Type Email Template Key'];

            } elseif ($this->data['Email Campaign Type Code'] == 'OOS Notification') {

                $email_campaign_data['Email Campaign Email Template Key'] = $this->data['Email Campaign Type Email Template Key'];

            } elseif ($this->data['Email Campaign Type Code'] == 'Marketing') {


            } elseif ($this->data['Email Campaign Type Code'] == 'AbandonedCart') {

                $metadata                                       = $this->get('Metadata');
                $email_campaign_data['Email Campaign Metadata'] = json_encode(
                    array(
                        'Type'                    => (isset($metadata['Type']) ? $metadata['Type'] : 'Inactive'),
                        'Days Inactive in Basket' => (isset($metadata['Days Inactive in Basket']) ? $metadata['Days Inactive in Basket'] : 30),
                        'Days Last Updated'       => (isset($metadata['Days Last Updated']) ? $metadata['Days Last Updated'] : 7)

                    )
                );

            } elseif ($this->data['Email Campaign Type Code'] == 'Marketing') {


            }
            $email_campaign = new EmailCampaign('create', $email_campaign_data);

            return $email_campaign;
        } else {
            $this->error = true;

            return false;
        }
    }

    function get_estimated_recipients() {


        $estimated_recipients = 0;

        // print $this->get('Email Campaign Type Code');

        switch ($this->get('Email Campaign Type Code')) {
            case 'AbandonedCart':

                $metadata = $this->get('Metadata');


                $sql = sprintf(
                    'SELECT count(DISTINCT O.`Order Key`) AS num FROM `Order Dimension` O LEFT JOIN `Customer Dimension` ON (`Order Customer Key`=`Customer Key`) WHERE `Order State`="InBasket" AND `Customer Main Plain Email`!="" AND `Customer Send Email Marketing`="Yes" AND `Order Store Key`=%d AND `Order Last Updated Date`<= CURRENT_DATE - INTERVAL %d DAY',
                    $this->data['Email Campaign Type Store Key'], (empty($metadata['Days Inactive in Basket']) ? 0 : $metadata['Days Inactive in Basket'])
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $estimated_recipients = $row['num'];
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                break;

            case 'Newsletter':
                $sql = sprintf(
                    'select count(*)  as num from `Customer Dimension` where `Customer Store Key`=%d and `Customer Main Plain Email`!="" and `Customer Send Newsletter`="Yes" and  `Customer Type by Activity` not in ("Rejected", "ToApprove") ', $this->get('Store Key')
                );
                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $estimated_recipients = $row['num'];
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                break;
            case 'OOS Notification':
                $sql = sprintf(
                    'select count( distinct `Back in Stock Reminder Customer Key`)  as num from `Back in Stock Reminder Fact` where `Back in Stock Reminder Store Key`=%d and `Back in Stock Reminder State`="Ready"  ', $this->get('Store Key')
                );


                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $estimated_recipients = $row['num'];
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                break;
            case 'GR Reminder':

                $metadata = $this->get('Metadata');


                $date = gmdate('Y-m-d', strtotime('today - '.$metadata['Send After'].' days'));


                $sql = sprintf(
                    'select count( distinct `Customer Key`)  as num from `Customer Dimension`    left join `Order Dimension` on (`Customer Last Dispatched Order Key`=`Order Key`) where `Customer Store Key`=%d and  `Customer Send Email Marketing`=\'Yes\' and  `Customer Last Dispatched Order Key` is NOT NULL and Date(`Order Dispatched Date`)=%s  ',
                    $this->get('Store Key'), prepare_mysql($date)
                );


                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $estimated_recipients = $row['num'];
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                break;

            default:

        }


        return $estimated_recipients;

    }

    function update_number_subscribers() {

        if ($this->data['Email Campaign Type Scope'] == 'User Notification') {
            $store      = get_object('Store', $this->data['Email Campaign Type Store Key']);
            $recipients = $store->get_notification_recipients($this->data['Email Campaign Type Code']);

            $this->fast_update(
                array(
                    'Email Campaign Type Subscribers' => count($recipients)
                )
            );
        }


    }


}


?>
