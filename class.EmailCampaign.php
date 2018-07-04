<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 25 September 2017 at 14:19:21 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2010-2015, Inikoo

 Version 3.0


*/
include_once 'class.DB_Table.php';

class EmailCampaign extends DB_Table {

    var $new = false;
    var $updated_data = array();

    function EmailCampaign($arg1 = false, $arg2 = false) {


        global $db;
        $this->db = $db;

        $this->table_name    = 'Email Campaign';
        $this->ignore_fields = array(
            'Email Campaign Key',
        );

        if (!$arg1 and !$arg2) {
            $this->error = true;
            $this->msg   = 'No arguments';
        }
        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }


        if (is_array($arg2) and $arg1 = 'create') {
            $this->find($arg2, 'create');

            return;
        }

        $this->get_data($arg1, $arg2);

    }

    function get_data($tipo, $tag) {


        $sql = sprintf(
            "SELECT * FROM `Email Campaign Dimension` WHERE  `Email Campaign Key`=%d", $tag
        );


        if ($this->data = $this->db->query($sql)->fetch()) {

            $this->id = $this->data['Email Campaign Key'];
        }


        switch ($this->get('Email Campaign Type')) {
            case 'AbandonedCart':

                $sql = sprintf(
                    "SELECT * FROM `Email Campaign Abandoned Cart Dimension` WHERE  `Email Campaign Abandoned Cart Email Campaign Key`=%d", $tag
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

                if ($this->data['Email Campaign '.$key] == '') {
                    $content_data = false;
                } else {
                    $content_data = json_decode($this->data['Email Campaign '.$key], true);
                }

                return $content_data;
                break;


            case ('State Index'):

                switch ($this->data['Email Campaign State']) {
                    case 'InProcess':
                        return 10;
                        break;
                    case 'ComposingEmail':
                        return 20;
                        break;
                    case 'Ready':
                        return 30;
                        break;
                    case 'Scheduled':
                        return 40;
                        break;
                    case 'Sending':
                        return 50;
                        break;
                    case 'Cancelled':
                        return 70;
                        break;
                    case 'Sent':
                        return 100;
                        break;


                    default:
                        return 0;
                        break;
                }

                break;
            case 'State':
                //'InProcess','ComposingEmail','Ready','Sending','Complete'
                switch ($this->data['Email Campaign State']) {
                    case 'InProcess':
                        return _('Setting up mailing list');
                        break;
                    case 'ComposingEmail':
                        return _('Composing email');
                        break;
                    case 'Ready':
                        return _('Ready to send');
                        break;
                    case 'Scheduled':
                        return _('Scheduled to be send');
                        break;

                    case 'Sending':
                        return _('Sending');

                        break;
                    case 'Cancelled':
                        return _('Cancelled');
                        break;
                    case 'Sent':
                        return _('Sent');
                        break;


                    default:
                        return $this->data['Email Campaign State'];
                        break;
                }


                break;

            case 'Abandoned Cart Days Inactive in Basket':
            case 'Number Estimated Emails':
                return number($this->data['Email Campaign '.$key]);
                break;


            case 'Creation Date':
            case 'Setup Date':
            case 'Composed Date':
            case 'Start Send Date':
            case 'End Send Date':
                if ($this->data['Email Campaign '.$key] != '') {
                    return strftime('%e %b %y %k:%M', strtotime($this->data['Email Campaign '.$key]));
                } else {
                    return '';
                }


                break;


            default:
                if (isset($this->data[$key])) {
                    return $this->data[$key];
                }

                if (array_key_exists('Email Campaign '.$key, $this->data)) {
                    return $this->data[$this->table_name.' '.$key];
                }
        }

        return false;
    }

    function find($raw_data, $options) {


        if (isset($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {
                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }
            }
        }

        $this->found     = false;
        $this->found_key = false;


        $sql = sprintf(
            "SELECT `Email Campaign Key` FROM `Email Campaign Dimension` WHERE `Email Campaign Store Key`=%d AND `Email Campaign Name`=%s", $raw_data['Email Campaign Store Key'], prepare_mysql($raw_data['Email Campaign Name'])
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->found_key = $row['Email Campaign Key'];
                $this->found     = true;
                $this->get_data('id', $this->found_key);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $create = '';
        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        if ($create and !$this->found) {
            $this->create($raw_data);
        }

    }

    function create($raw_data) {

        $data = $this->base_data();


        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {

                $data[$key] = _trim($value);

            }
        }


        if ($data['Email Campaign Name'] == '') {
            $this->error;
            $this->msg = 'no name';

            return;
        }


        $data['Email Campaign Creation Date']     = gmdate('Y-m-d H:i:s');
        $data['Email Campaign Last Updated Date'] = gmdate('Y-m-d H:i:s');


        $keys   = '(';
        $values = 'values(';
        foreach ($data as $key => $value) {
            $keys .= "`$key`,";
            if ($key = '') {
                $values .= prepare_mysql($value, false).",";
            } else {
                $values .= prepare_mysql($value).",";
            }
        }


        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);


        $sql = "insert into `Email Campaign Dimension` $keys  $values";

        // print $sql;

        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();

            $this->get_data('id', $this->id);

            switch ($this->get('Email Campaign Type')) {
                case 'AbandonedCart':

                    $sql = sprintf('INSERT INTO `Email Campaign Abandoned Cart Dimension`  (`Email Campaign Abandoned Cart Email Campaign Key`) VALUES (%d) ', $this->id);

                    $this->db->exec($sql);
                    $this->get_data('id', $this->id);
                    break;
                default:

            }


            $this->new = true;

            $store = get_object('Store', $this->data['Email Campaign Store Key']);
            $store->update_email_campaign_data();


            switch ($this->get('Email Campaign Type')) {
                case 'AbandonedCart':
                    $history_abstract = sprintf(_('Abandoned cart mailshot %s created'), '<b>'.$this->data['Email Campaign Name'].'</b>');

                    break;
                default:

                    $history_abstract = sprintf(_('Email campaign %s created'), $this->data['Email Campaign Name']);
                    break;
            }

            $this->update_estimated_recipients();

            $history_data = array(
                'History Abstract' => $history_abstract,
                'History Details'  => '',
                'Action'           => 'created'
            );

            $history_key = $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );


        } else {
            $this->error = true;
            $this->msg   = "Can not insert Email Campaign Dimension";
            // exit("$sql\n");
        }


    }

    function update_estimated_recipients() {


        if ($this->get('State Index') < 40) {


            $estimated_recipients = 0;

            switch ($this->get('Email Campaign Type')) {
                case 'AbandonedCart':

                    $sql = sprintf(
                        'SELECT count(DISTINCT O.`Order Key`) AS num FROM `Order Dimension` O LEFT JOIN `Customer Dimension` ON (`Order Customer Key`=`Customer Key`) WHERE `Order State`="InBasket" AND `Customer Main Plain Email`!="" AND `Customer Send Email Marketing`="Yes" AND `Order Store Key`=%d AND `Order Last Updated Date`<= CURRENT_DATE - INTERVAL %d DAY',
                        $this->data['Email Campaign Store Key'], $this->data['Email Campaign Abandoned Cart Days Inactive in Basket']
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
                    $sql = sprintf('select count(*)  as num from `Customer Dimension` where `Customer Store Key`=%d and `Customer Main Plain Email`!="" and `Customer Send Newsletter`="Yes" ', $this->get('Store Key'));
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
                        'select count(*)  as num from `Back in Stock Reminder Fact` where `Back in Stock Reminder Store Key`=%d and `Back in Stock Reminder State`="Ready" ', $this->get('Store Key')
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
                    //print_r($metadata);


                    $date = gmdate('Y-m-d', strtotime('today - '.$metadata['Send After'].' days'));


                    $sql = sprintf(
                        'select count(*)  as num  from `Customer Dimension`    left join `Order Dimension` on (`Customer Last Dispatched Order Key`=`Order Key`) where `Customer Store Key`=%d and  `Customer Send Email Marketing`=\'Yes\' and  `Customer Last Dispatched Order Key` is NOT NULL and Date(`Order Dispatched Date`)=%s ',
                        $this->data['Email Campaign Store Key'], prepare_mysql($date)
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

            $this->fast_update(array('Email Campaign Number Estimated Emails' => $estimated_recipients));

        }

    }


    function delete() {


        if (in_array(
            $this->data['Email Campaign State'], array(
                                                   'InProcess',
                                                   'ComposingEmail',
                                                   'Ready'
                                               )
        )) {


            $store = get_object('Store', $this->data['Email Campaign Store Key']);

            $sql = sprintf('SELECT `History Key` FROM `Email Campaign History Bridge` WHERE `Email Campaign Key`=%d ', $this->id);

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $sql = sprintf("DELETE FROM `History Dimension` WHERE  `History Key`=%d", $row['History Key']);
                    $this->db->exec($sql);
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }

            $sql = sprintf("DELETE FROM `Email Campaign History Bridge` WHERE `Email Campaign Key`=%d ", $this->id);
            $this->db->exec($sql);


            switch ($this->get('Email Campaign Type')) {
                case 'AbandonedCart':


                    $sql = sprintf("DELETE FROM `Email Campaign Abandoned Cart Dimension` WHERE  `Email Campaign Abandoned Cart Email Campaign Key`=%d", $this->id);
                    $this->db->exec($sql);


                    break;
                default:

            }


            $sql = sprintf(
                "DELETE FROM `Email Campaign Dimension` WHERE `Email Campaign Key`=%d", $this->id
            );

            $this->db->exec($sql);

            $store->update_email_campaign_data();


            $this->updated = true;
            $this->deleted = true;

            switch ($this->get('Email Campaign Type')) {
                case 'AbandonedCart':
                    return sprintf('orders/%d/dashboard/website/mailshots', $store->id);

                    break;
                case 'Newsletter':
                    return sprintf('customers/%d/email_campaigns', $store->id);

                    break;
                default:

            }


        } else {
            $this->error = true;
            $this->msg   = 'Email Campaign can not be deleted';
        }

    }


    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        switch ($field) {

            case 'Email Campaign State':

                $this->update_state($value);
                break;

            case 'Metadata':

                $this->update_field('Email Campaign '.$field, $value, $options);
                break;

            case 'Email Campaign Abandoned Cart Days Inactive in Basket':
                $this->fast_update(array('Email Campaign Abandoned Cart Days Inactive in Basket' => $value), 'Email Campaign Abandoned Cart Dimension');
                $this->update_estimated_recipients();


                $this->update_metadata = array(
                    'class_html' => array(
                        'Email_Campaign_Number_Estimated_Emails' => $this->get('Email Campaign Number Estimated Emails'),
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


    function update_state($value) {

        $operations = array();


        switch ($value) {

            case 'ComposingEmail':


                if ($this->data['Email Campaign State'] == 'Sending') {
                    $this->error = true;
                    $this->msg   = _('Campaign already sending emails');

                    return;
                }
                if ($this->data['Email Campaign State'] == 'Sent') {
                    $this->error = true;
                    $this->msg   = _('Campaign already send');

                    return;
                }

                if ($this->data['Email Campaign State'] == 'Ready') {
                    $this->fast_update(
                        array(
                            'Email Campaign Composed Date' => ''
                        )
                    );
                }
                /*
                                if ($this->data['Email Campaign State'] == 'Scheduled' or $this->data['Email Campaign State'] == 'Ready' or $this->data['Email Campaign State'] == 'Cancelled' or $this->data['Email Campaign State'] == 'ComposingEmail') {

                                    $this->fast_update(
                                        array(
                                            'Email Campaign Last Updated Date' => gmdate('Y-m-d H:i:s')
                                        )
                                    );
                                }else{

                                    $this->fast_update(
                                        array(
                                            'Email Campaign State'             => $value,
                                            'Email Campaign Last Updated Date' => gmdate('Y-m-d H:i:s'),
                                            'Email Campaign Setup Date' => gmdate('Y-m-d H:i:s')
                                        )
                                    );
                                }
                */

                $this->fast_update(
                    array(
                        'Email Campaign State'             => $value,
                        'Email Campaign Last Updated Date' => gmdate('Y-m-d H:i:s'),
                        'Email Campaign Setup Date'        => gmdate('Y-m-d H:i:s')
                    )
                );

                $operations = array(
                    'delete_operations'

                );

                break;

            case 'Ready':


                if ($this->data['Email Campaign State'] == 'Sending') {
                    $this->error = true;
                    $this->msg   = _('Campaign already sending emails');

                    return;
                }
                if ($this->data['Email Campaign State'] == 'Sent') {
                    $this->error = true;
                    $this->msg   = _('Campaign already send');

                    return;
                }


                if ($this->data['Email Campaign State'] == 'Scheduled' or $this->data['Email Campaign State'] == 'Cancelled') {

                    $this->fast_update(
                        array(
                            'Email Campaign Last Updated Date' => gmdate('Y-m-d H:i:s')
                        )
                    );
                } else {
                    $this->fast_update(
                        array(
                            'Email Campaign State'             => $value,
                            'Email Campaign Last Updated Date' => gmdate('Y-m-d H:i:s'),
                            'Email Campaign Composed Date'     => gmdate('Y-m-d H:i:s')

                        )
                    );
                }

                $operations = array(
                    'delete_operations',
                    //  'schedule_mailshot_operations',
                    'send_mailshot_operations',
                    'undo_set_as_ready_operations'
                );

                break;
            case 'Sending':


                if ($this->data['Email Campaign State'] == 'Sending') {
                    $this->error = true;
                    $this->msg   = _('Campaign already sending emails');

                    return;
                }
                if ($this->data['Email Campaign State'] == 'Sent') {
                    $this->error = true;
                    $this->msg   = _('Campaign already send');

                    return;
                }
                if ($this->data['Email Campaign State'] == 'Cancelled') {
                    $this->error = true;
                    $this->msg   = _('Campaign cancelled');

                    return;
                }


                $this->fast_update(
                    array(
                        'Email Campaign State'             => $value,
                        'Email Campaign Last Updated Date' => gmdate('Y-m-d H:i:s'),
                        'Email Campaign Start Send Date'   => gmdate('Y-m-d H:i:s'),
                    )
                );

                $operations = array(
                    'stop_operations',

                );

                break;
            case 'Stopped':


                if ($this->data['Email Campaign State'] == 'Sending') {
                    $this->error = true;
                    $this->msg   = _('Campaign already sending emails');

                    return;
                }
                if ($this->data['Email Campaign State'] == 'Sent') {
                    $this->error = true;
                    $this->msg   = _('Campaign already send');

                    return;
                }
                if ($this->data['Email Campaign State'] == 'Cancelled') {
                    $this->error = true;
                    $this->msg   = _('Campaign cancelled');

                    return;
                }


                $this->fast_update(
                    array(
                        'Email Campaign State'             => $value,
                        'Email Campaign Last Updated Date' => gmdate('Y-m-d H:i:s'),
                        'Email Campaign Start Send Date'   => gmdate('Y-m-d H:i:s'),
                    )
                );


                break;

            case 'Sent':



                $this->fast_update(
                    array(
                        'Email Campaign State'             => $value,
                        'Email Campaign Last Updated Date' => gmdate('Y-m-d H:i:s'),
                        'Email Campaign End Send Date'     => gmdate('Y-m-d H:i:s'),
                    )
                );

                $operations = array();

                break;


        }


        $this->update_metadata = array(
            'class_html'  => array(
                'Email_Campaign_State'           => $this->get('State'),
                'Email_Campaign_Setup_Date'      => '&nbsp;'.$this->get('Setup Date'),
                'Email_Campaign_Composed_Date'   => '&nbsp;'.$this->get('Composed Date'),
                'Email_Campaign_Start_Send_Date' => '&nbsp;'.$this->get('Start Send Date'),
                'Email_Campaign_End_Send_Date'   => '&nbsp;'.$this->get('End Send Date'),


            ),
            'operations'  => $operations,
            'state_index' => $this->get('State Index'),
            'state'       => $this->data['Email Campaign State']
        );


    }


    function get_field_label($field) {

        switch ($field) {

            case 'Email Campaign Name':
                $label = _('name');
                break;
            case 'Email Campaign Abandoned Cart Days Inactive in Basket':
                $label = _('Inactive days in basket');
                break;

            default:
                $label = $field;

        }

        return $label;

    }


    function send_mailshot() {

        include_once 'class.Email_Tracking.php';


        require_once 'external_libs/Smarty/Smarty.class.php';
        $smarty               = new Smarty();
        $smarty->template_dir = 'templates';
        $smarty->compile_dir  = 'server_files/smarty/templates_c';
        $smarty->cache_dir    = 'server_files/smarty/cache';
        $smarty->config_dir   = 'server_files/smarty/configs';


        $store=get_object('Store',$this->data['Email Campaign Store Key']);
        $website=get_object('Website',$store->get('Store Website Key'));


        $email_template_type = get_object('email_template_type', $this->data['Email Campaign Email Template Type Key']);
        $email_template      = get_object('email_template', $email_template_type->data['Email Campaign Type Email Template Key']);

        $published_email_template = get_object('published_email_template', $email_template->get('Email Template Published Email Key'));


        $recipient_type = 'Customer';


        $this->update_state('Sending');
        $sql = $this->get_recipients_sql();

        //print $sql;

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $email_tracking_data = array(
                    'Email Tracking Email' => $row[$recipient_type.' Main Plain Email'],

                    'Email Tracking Email Template Type Key' => $email_template_type->id,
                    'Email Tracking Email Template Key'      => $email_template->id,
                    'Email Tracking Email Mailshot Key'      => $this->id,

                    'Email Tracking Published Email Template Key' => $published_email_template->id,
                    'Email Tracking Recipient'                    => $recipient_type,
                    'Email Tracking Recipient Key'                => $row[$recipient_type.' Key']

                );


                new Email_Tracking('new', $email_tracking_data);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf('select count(*) as num from `Email Tracking Dimension` where `Email Tracking Email Mailshot Key`=%d  ', $this->id);

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->fast_update(array('Email Campaign Number of Emails' => $row['num']));

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf('select `Email Tracking Key`,`Email Tracking Recipient`,`Email Tracking Recipient Key` ,`Email Tracking Recipient Key` from `Email Tracking Dimension` where `Email Tracking Email Mailshot Key`=%d and `Email Tracking State`="Ready" ', $this->id);

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $send_data = array(
                    'Email_Template_Type' => $email_template_type,
                    'Email_Template'      => $email_template,
                    'Email_Tracking'      => get_object('Email_Tracking', $row['Email Tracking Key']),
                    'Unsubscribe URL'     => $website->get('Website URL').'/unsubscribe.php'
                );

                if ($this->data['Email Campaign Type'] == 'GR Reminder') {
                    $customer               = get_object('Customer', $row['Email Tracking Recipient Key']);
                    $send_data['Order Key'] = $customer->get('Customer Last Dispatched Order Key');
                }


                // print_r($row);


                $published_email_template->send(get_object($row['Email Tracking Recipient'], $row['Email Tracking Recipient Key']), $send_data, $smarty);


              //  print_r($published_email_template);


                // print_r($published_email_template);

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->update_state('Sent');


    }

    function get_recipients_sql() {

        switch ($this->data['Email Campaign Type']) {

            case 'AbandonedCart':


                $sql = sprintf(
                    'select `Customer Key` ,`Customer Main Plain Email` from `Order Dimension` O  left join `Customer Dimension` on (`Order Customer Key`=`Customer Key`) where `Order State`="InBasket" and `Customer Main Plain Email`!="" and `Customer Send Email Marketing`="Yes" and `Customer Main Plain Email`!="" and `Order Store Key`=%d  and `Order Last Updated Date`<= CURRENT_DATE - INTERVAL %d DAY ',
                    $this->data['Email Campaign Store Key'], $this->data['Email Campaign Abandoned Cart Days Inactive in Basket']
                );

                return $sql;


                break;
            case 'OOS Notification':


                $sql = sprintf(
                    'select `Customer Key` ,`Customer Main Plain Email`  from `Back in Stock Reminder Fact`  left join `Customer Dimension` on (`Back in Stock Reminder Customer Key`=`Customer Key`)   where `Back in Stock Reminder Store Key`=%d and `Back in Stock Reminder State`="Ready" and `Customer Main Plain Email`!="" group by `Back in Stock Reminder Customer Key`',
                    $this->data['Email Campaign Store Key']
                );

                return $sql;

            case 'GR Reminder':


                $metadata = $this->get('Metadata');
                //print_r($metadata);


                $date = gmdate('Y-m-d', strtotime('today - '.$metadata['Send After'].' days'));


                $sql = sprintf(
                    'select `Customer Key` ,`Customer Main Plain Email` from `Customer Dimension`    left join `Order Dimension` on (`Customer Last Dispatched Order Key`=`Order Key`) where `Customer Store Key`=%d and  `Customer Send Email Marketing`=\'Yes\' and  `Customer Last Dispatched Order Key` is NOT NULL and Date(`Order Dispatched Date`)=%s ',
                    $this->data['Email Campaign Store Key'], prepare_mysql($date)
                );

                return $sql;


                break;

        }
    }


    function update_sent_emails_totals() {


        $unsubscribed = 0;
        $sent         = 0;
        $open         = 0;
        $clicked      = 0;
        $errors       = 0;
        $delivered    = 0;
        $hard_bounces = 0;
        $soft_bounces = 0;
        $spam         = 0;


        //'Send to SES',Send',Read
        //'Sent','Sent to SES','Ready',,'Rejected by SES',','','Hard Bounce','Soft Bounce','Spam','Delivered','Opened','Clicked','Error'


        //     'Ready','Sent to SES','Rejected by SES','Sent','Soft Bounce','Hard Bounce','Delivered','Spam','Opened','Clicked','Error'


        $sql = sprintf('select count(*) as num ,`Email Tracking State` from `Email Tracking Dimension` where `Email Tracking Email Mailshot Key`=%d group by `Email Tracking State` ', $this->id);
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


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


        $sql = sprintf('select count(*) as num  from `Email Tracking Dimension` where `Email Tracking Email Mailshot Key`=%d and `Email Tracking Spam`="Yes" ', $this->id);
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $spam = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $sql = sprintf('select count(*) as num  from `Email Tracking Dimension` where `Email Tracking Email Mailshot Key`=%d and `Email Tracking Unsubscribed`="Yes" ', $this->id);
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $unsubscribed = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->fast_update(
            array(
                'Email Campaign Sent'      => $sent,
                'Email Campaign Delivered' => $delivered,

                'Email Campaign Open'    => $open,
                'Email Campaign Clicked' => $clicked,

                'Email Campaign Errors'       => $errors,
                'Email Campaign Hard Bounces' => $hard_bounces,
                'Email Campaign Soft Bounces' => $soft_bounces,
                'Email Campaign Spams'        => $spam,
                'Email Campaign Unsubscribed' => $unsubscribed,

            )

        );


    }


}


?>
