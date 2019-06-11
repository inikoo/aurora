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
            $this->metadata   = ($this->data['Email Campaign Metadata']==''?array():json_decode($this->data['Email Campaign Metadata'], true));

        }


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

        /*
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
        */

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


        $this->editor = $raw_data['editor'];

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


            $this->new = true;

            $store = get_object('Store', $this->data['Email Campaign Store Key']);
            $store->update_email_campaign_data();


            switch ($this->get('Email Campaign Type')) {
                case 'AbandonedCart':
                    $history_abstract = sprintf(_('Mailshot for orders in basket created (%s)'), '<b>'.$this->data['Email Campaign Name'].'</b>');

                    break;
                case 'Newsletter':
                    $history_abstract = _('Newsletter created');

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
                    case 'Stopped':
                        return 60;
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
                        if ($this->data['Email Campaign Type'] == 'Newsletter') {
                            return _('Setting up newsletter');

                        } else {
                            return _('Setting up mailing list');

                        }
                        break;
                    case 'ComposingEmail':
                        if ($this->get('Email Campaign Selecting Blueprints') == 'No') {
                            return _('Composing email');

                        } else {
                            return _('Changing template');

                        }


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


            case 'Email Campaign Abandoned Cart Type':
                $metadata = $this->get('Metadata');

                return $metadata['Type'];
                break;
            case 'Abandoned Cart Type':
                $metadata = $this->get('Metadata');

                switch ($metadata['Type']) {
                    case 'Inactive':
                        $formatted_value = _('Inactive day');
                        break;
                    case 'Last_Updated':
                        $formatted_value = _('Last updated');
                        break;
                    default:
                        $formatted_value = $metadata['Type'];
                }

                return $formatted_value;
                break;

            case 'Abandoned Cart Days Inactive in Basket':
                $metadata = $this->get('Metadata');

                return number((isset($metadata['Days Inactive in Basket']) ? $metadata['Days Inactive in Basket'] : 0));
                break;
            case 'Email Campaign Abandoned Cart Days Inactive in Basket':
                $metadata = $this->get('Metadata');

                return (isset($metadata['Days Inactive in Basket']) ? $metadata['Days Inactive in Basket'] : '');
                break;
            case 'Email Campaign Abandoned Cart Days Last Updated':
                $metadata = $this->get('Metadata');

                return (isset($metadata['Days Last Updated']) ? $metadata['Days Last Updated'] : '');
                break;
            case 'Abandoned Cart Days Last Updated':
                $metadata = $this->get('Metadata');

                return number(isset($metadata['Days Last Updated']) ? $metadata['Days Last Updated'] : 0);
                break;
            case 'Number Estimated Emails':
                return number($this->data['Email Campaign '.$key]);
                break;

            case  'Sent Emails Info':


                if (!$this->data['Email Campaign Number Estimated Emails'] > 0) {


                    if ($this->data['Email Campaign State'] == 'Sending') {
                        return '<span class="very_discreet"><i class="fa fa-spin fa-spinner"></i> '._('Initializing launch').'</span>';
                    } else {
                        return '';
                    }

                }


                $sent_emails_info = sprintf(_('Sent %s of %s'), '<b>'.number($this->data['Email Campaign Sent']).'</b>', '<b>'.number($this->data['Email Campaign Number Estimated Emails'])).'</b> ';


                if ($this->data['Email Campaign Number Estimated Emails'] > 0) {
                    $sent_emails_info .= ' <span class="discreet">('.percentage($this->data['Email Campaign Sent'], $this->data['Email Campaign Number Estimated Emails']).')</span>';


                    if ($this->data['Email Campaign State'] == 'Sending') {

                        if (isset($this->start_send)) {
                            $start_datetime = $this->start_send;
                        } else {
                            $start_datetime = $this->data['Email Campaign Start Send Date'];
                        }


                        $offset = (isset($this->sent) ? $this->sent : 0);


                        if ($this->data['Email Campaign Sent'] - $offset > 5) {
                            $sent_emails_info .= ' <span class="discreet padding_left_5">'.eta($this->data['Email Campaign Sent'] - $offset, $this->data['Email Campaign Number Estimated Emails'] - $offset, $start_datetime).'</span>';

                        }

                    }
                }


                return $sent_emails_info;
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
            case 'Delivered Percentage':

                if ($this->data['Email Campaign Sent'] == 0) {
                    return percentage(0, 1);
                }

                return percentage($this->data['Email Campaign Delivered'], $this->data['Email Campaign Sent']);

                break;
            case 'Bounces Percentage':

                if ($this->data['Email Campaign Sent'] == 0) {
                    return percentage(0, 1);
                }

                return percentage($this->data['Email Campaign Hard Bounces'] + $this->data['Email Campaign Soft Bounces'], $this->data['Email Campaign Sent']);

                break;
            case 'Hard Bounces Percentage':

                if ($this->data['Email Campaign Sent'] == 0) {
                    return percentage(0, 1);
                }

                return percentage($this->data['Email Campaign Hard Bounces'], $this->data['Email Campaign Sent']);

                break;
            case 'Soft Bounces Percentage':

                if ($this->data['Email Campaign Sent'] == 0) {
                    return percentage(0, 1);
                }

                return percentage($this->data['Email Campaign Soft Bounces'], $this->data['Email Campaign Sent']);

                break;


            case 'Delivered':
            case 'Open':
            case 'Clicked':
            case 'Sent':
            case 'Spams':
            case 'Unsubscribed':
                return number($this->data[$this->table_name.' '.$key]);
                break;
            case 'Open Percentage':
            case 'Clicked Percentage':
            case 'Spams Percentage':
                if ($this->data['Email Campaign Sent'] == 0) {
                    return percentage(0, 1);
                }

                return percentage($this->data['Email Campaign '.preg_replace('/ Percentage/', '', $key)], $this->data['Email Campaign Sent']);

                break;

            case 'Unsubscribed Percentage':
                if ($this->data['Email Campaign Open'] == 0) {
                    return percentage(0, 1);
                }

                return percentage($this->data['Email Campaign Unsubscribed'], $this->data['Email Campaign Open']);

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

    function update_estimated_recipients() {



        if ($this->get('State Index') < 40) {


            $estimated_recipients = 0;

            switch ($this->get('Email Campaign Type')) {
                case 'AbandonedCart':

                    $metadata = $this->get('Metadata');


                    if ($metadata['Type'] == 'Inactive') {
                        $sql = sprintf(
                            'SELECT count(DISTINCT O.`Order Key`) AS num FROM `Order Dimension` O LEFT JOIN `Customer Dimension` ON (`Order Customer Key`=`Customer Key`) WHERE `Order State`="InBasket" AND `Customer Main Plain Email`!="" AND `Customer Send Email Marketing`="Yes" AND `Order Store Key`=%d AND `Order Last Updated by Customer`<= CURRENT_DATE - INTERVAL %d DAY',
                            $this->data['Email Campaign Store Key'], (empty($metadata['Days Inactive in Basket']) ? 0 : $metadata['Days Inactive in Basket'])
                        );
                    } else {
                        $sql = sprintf(
                            'SELECT count(DISTINCT O.`Order Key`) AS num FROM `Order Dimension` O LEFT JOIN `Customer Dimension` ON (`Order Customer Key`=`Customer Key`) WHERE `Order State`="InBasket" AND `Customer Main Plain Email`!="" AND `Customer Send Email Marketing`="Yes" AND `Order Store Key`=%d AND `Order Last Updated by Customer`>= CURRENT_DATE - INTERVAL %d DAY',
                            $this->data['Email Campaign Store Key'], (empty($metadata['Days Last Updated']) ? 0 : $metadata['Days Last Updated'])
                        );
                    }






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
                        'select count(*)  as num from `Customer Dimension` where `Customer Store Key`=%d and `Customer Main Plain Email`!="" and `Customer Send Newsletter`="Yes" and  `Customer Type by Activity` not in ("Rejected", "ToApprove")', $this->get('Store Key')
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
                        'select count(*)  as num  from `Customer Dimension`    left join `Order Dimension` on (`Customer Last Dispatched Order Key`=`Order Key`) where `Customer Store Key`=%d and  `Customer Send Email Marketing`=\'Yes\' and  `Customer Last Dispatched Order Date`=%s ',
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
                case 'Marketing':
                    $metadata = $this->get('Metadata');


                    if (isset($metadata['type'])) {
                        switch ($metadata['type']) {
                            case 'awhere':
                                include_once 'utils/parse_customer_list.php';

                                list($table, $where, $group_by) = parse_customer_list($metadata['fields'], $this->db);

                                $where = sprintf(' where `Customer Store Key`=%d ', $this->get('Store Key')).$where.' and `Customer Send Email Marketing`="Yes" and `Customer Main Plain Email`!="" ';

                                $sql = "select count(Distinct C.`Customer Key`) as num from $table  $where ";


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
                                break;
                        }
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


            $store               = get_object('Store', $this->data['Email Campaign Store Key']);
            $email_template_type = get_object('Email_Template_Type', $this->data['Email Campaign Email Template Type Key']);

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


            $sql = sprintf(
                "DELETE FROM `Email Campaign Dimension` WHERE `Email Campaign Key`=%d", $this->id
            );

            $this->db->exec($sql);

            $store->update_email_campaign_data();
            $email_template_type->update_sent_emails_totals();

            $this->updated = true;
            $this->deleted = true;

            switch ($this->get('Email Campaign Type')) {
                case 'AbandonedCart':

                    if ($this->web_state['module'] == 'customers') {
                        return sprintf('email_campaign_type/%d/%d', $store->id, $email_template_type->id);

                    } else {
                        return sprintf('orders/%d/dashboard/website/mailshots', $store->id);

                    }


                    break;
                case 'Newsletter':


                    return sprintf('email_campaign_type/%d/%d', $store->id, $email_template_type->id);

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

            case 'second wave':

                $this->fast_update_json_field('Store Metadata', preg_replace('/\s/', '_', $field), $value);

                break;

            case 'Metadata':

                $this->update_field('Email Campaign '.$field, $value, $options);
                break;

            case 'Email Campaign Abandoned Cart Days Inactive in Basket':


                $metadata = $this->get('Metadata');

                if ($metadata == '') {
                    $metadata = array();
                }

                $metadata['Days Inactive in Basket'] = $value;


                $this->fast_update(array('Email Campaign Metadata' => json_encode($metadata)));
                $this->update_estimated_recipients();


                $this->update_metadata = array(
                    'class_html' => array(
                        'Email_Campaign_Number_Estimated_Emails' => $this->get('Email Campaign Number Estimated Emails'),
                        'Number_Estimated_Emails'                => $this->get('Number Estimated Emails'),

                    ),

                );


                break;

            case 'Email Campaign Abandoned Cart Days Last Updated':


                $metadata = $this->get('Metadata');

                if ($metadata == '') {
                    $metadata = array();
                }

                $metadata['Days Last Updated'] = $value;


                $this->fast_update(array('Email Campaign Metadata' => json_encode($metadata)));
                $this->update_estimated_recipients();


                $this->update_metadata = array(
                    'class_html' => array(
                        'Email_Campaign_Number_Estimated_Emails' => $this->get('Email Campaign Number Estimated Emails'),
                        'Number_Estimated_Emails'                => $this->get('Number Estimated Emails'),

                    ),

                );


                break;

            case 'Email Campaign Abandoned Cart Type':


                $metadata = $this->get('Metadata');

                if ($metadata == '') {
                    $metadata = array();
                }

                $metadata['Type'] = $value;


                $this->fast_update(array('Email Campaign Metadata' => json_encode($metadata)));
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

            case 'InProcess':


                if ($this->data['Email Campaign State'] != 'ComposingEmail') {
                    $this->error = true;
                    $this->msg   = 'forbidden';

                    return;
                }


                if ($this->data['Email Campaign State'] == 'Ready') {
                    $this->fast_update(
                        array(
                            'Email Campaign Composed Date' => ''
                        )
                    );
                }


                $this->fast_update(
                    array(
                        'Email Campaign State'             => $value,
                        'Email Campaign Last Updated Date' => gmdate('Y-m-d H:i:s'),
                        'Email Campaign Setup Date'        => ''
                    )
                );

                $operations = array(
                    'delete_operations'

                );

                break;


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
                if ($this->get('Email Campaign Type') == 'Marketing' and $this->get('Email Campaign Scope') == '') {
                    $operations[] = 'set_mail_list_operations';
                }

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
                    )
                );

                if ($this->data['Email Campaign State'] != 'Stopped') {
                    $this->fast_update(
                        array(

                            'Email Campaign Start Send Date' => gmdate('Y-m-d H:i:s'),
                        )
                    );
                }

                $operations = array(
                    'stop_operations',

                );

                break;
            case 'Stopped':


                if ($this->data['Email Campaign State'] == 'Sent') {
                    $this->error = true;
                    $this->msg   = _('Mailshot already sent');

                    return;
                }
                if ($this->data['Email Campaign State'] == 'Cancelled') {
                    $this->error = true;
                    $this->msg   = _('Mailshot cancelled');

                    return;
                }


                $this->fast_update(
                    array(
                        'Email Campaign State'             => $value,
                        'Email Campaign Last Updated Date' => gmdate('Y-m-d H:i:s'),
                        'Email Campaign Stopped Date'      => gmdate('Y-m-d H:i:s'),
                    )
                );


                $sql = sprintf(
                    'select max(`Email Tracking Thread`) as max_thread,count(*) as num from `Email Tracking Dimension` where `Email Tracking Email Mailshot Key`=%d  and `Email Tracking State`="Ready" group by `Email Tracking Thread`  ',

                    $this->id
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        if ($row['num'] > 0 and $row['max_thread'] > 0) {
                            $sql = sprintf(
                                'update `Email Tracking Dimension` set `Email Tracking Thread`=`Email Tracking Thread`+%d where=`Email Tracking Email Mailshot Key`=%d  where `Email Tracking State`="Ready"   ', $row['max_thread'] + 1, $this->id
                            );
                            $this->db->exec($sql);
                        }


                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                $operations = array(
                    'resume_operations',

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
                'Email_Campaign_Name'            => $this->get('Name'),
                'Email_Campaign_State'           => $this->get('State'),
                'Email_Campaign_Setup_Date'      => '&nbsp;'.$this->get('Setup Date'),
                'Email_Campaign_Composed_Date'   => '&nbsp;'.$this->get('Composed Date'),
                'Email_Campaign_Start_Send_Date' => '&nbsp;'.$this->get('Start Send Date'),
                'Email_Campaign_End_Send_Date'   => '&nbsp;'.$this->get('End Send Date'),
                'Number_Estimated_Emails'        => $this->get('Number Estimated Emails')

            ),
            'operations'  => $operations,
            'state_index' => $this->get('State Index'),
            'state'       => $this->data['Email Campaign State']
        );


        switch ($this->data['Email Campaign State']) {

            case 'InProcess':

                if ($this->get('Email Campaign Type') == 'Marketing' and $this->get('Email Campaign Scope') == '') {
                    $this->update_metadata['tab'] = 'set_mail_list';
                } else {
                    $this->update_metadata['tab'] = 'details';
                }


                break;
            case 'ComposingEmail':
                $this->update_metadata['tab'] = 'workshop';
                break;
            case 'Sending':
                $this->update_metadata['hide'] = array('estimated_recipients_pre_sent');
                $this->update_metadata['show'] = array('estimated_recipients_post_sent');

                $this->update_metadata['class_html']['_Sent_Emails_Info'] = $this->get('Sent Emails Info');


                break;
            case 'Sent':
                $this->update_metadata['add_class'] = array('sent_node' => 'complete');
                break;

        }


    }


    function get_field_label($field) {

        switch ($field) {

            case 'Email Campaign Name':
                $label = _('name');
                break;
            case 'Email Campaign Abandoned Cart Days Inactive in Basket':
                $label = _('Inactive days in basket');
                break;
            case 'Email Campaign Abandoned Cart Days Last Updated':
                $label = sprintf(_('Last updated %s days ago'), '<em>n</em>');
                break;
            default:
                $label = $field;

        }

        return $label;

    }


    function send_mailshot($first_thread = 1) {

        include_once 'class.Email_Tracking.php';
        $account = get_object('Account', 1);

        $email_template_type = get_object('email_template_type', $this->data['Email Campaign Email Template Type Key']);
        $email_template      = get_object('email_template', $this->data['Email Campaign Email Template Key']);


        $recipient_type = 'Customer';


        $sql = $this->get_recipients_sql();


        $contador    = 0;
        $thread      = $first_thread;
        $thread_size = 50;

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $sql_find = sprintf(
                    'select `Email Tracking Key` from `Email Tracking Dimension` where `Email Tracking Email Mailshot Key`=%s and `Email Tracking Email`=%s ', $this->id, prepare_mysql($row[$recipient_type.' Main Plain Email'])
                );

                if ($result_find = $this->db->query($sql_find)) {
                    if ($row_find = $result_find->fetch()) {

                    } else {
                        $email_tracking_data = array(
                            'Email Tracking Email' => $row[$recipient_type.' Main Plain Email'],

                            'Email Tracking Email Template Type Key'      => $email_template_type->id,
                            'Email Tracking Email Template Key'           => $email_template->id,
                            'Email Tracking Email Mailshot Key'           => $this->id,
                            'Email Tracking Published Email Template Key' => $email_template->get('Email Template Published Email Key'),
                            'Email Tracking Recipient'                    => $recipient_type,
                            'Email Tracking Recipient Key'                => $row[$recipient_type.' Key'],
                            'Email Tracking Thread'                       => $thread

                        );


                        new Email_Tracking('new', $email_tracking_data);

                        $contador++;
                        if ($contador > $thread_size) {


                            $client        = new GearmanClient();
                            $fork_metadata = json_encode(
                                array(
                                    'code' => addslashes($account->get('Code')),
                                    'data' => array(
                                        'mailshot' => $this->id,
                                        'thread'   => $thread,
                                    )
                                )
                            );
                            $client->addServer('127.0.0.1');
                            $client->doBackground('au_send_mailshot', $fork_metadata);
                            $thread++;

                            if ($thread > 1) {
                                $thread_size = 250;
                            } elseif ($thread > 10) {
                                $thread_size = 500;
                            }

                            $contador = 0;


                        }


                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "Error:  $sql_find\n";
                    exit;
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "Error: $sql\n";
            exit;
        }


        if ($contador > 0) {
            $client        = new GearmanClient();
            $fork_metadata = json_encode(
                array(
                    'code' => addslashes($account->get('Code')),
                    'data' => array(
                        'mailshot' => $this->id,
                        'thread'   => $thread,
                    )
                )
            );
            $client->addServer('127.0.0.1');
            $client->doBackground('au_send_mailshot', $fork_metadata);
        }


        $sql = sprintf('select count(*) as num from `Email Tracking Dimension` where `Email Tracking Email Mailshot Key`=%d  ', $this->id);

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->fast_update(array('Email Campaign Number Estimated Emails' => $row['num']));

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        /*

        $metadata = $this->get('Metadata');
        if (!isset($metadata['sending'])) {
            $metadata['sending']   = array();
            $metadata['sending'][] = array(
                'start'       => gmdate('Y-m-d H:i:s'),
                'end'         => '',
                'sent_before' => $this->data['Email Campaign Sent']

            );
        }

        $this->fast_update(array('Email Campaign Metadata' => json_encode($metadata)));


        */


        /*

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


                // print_r($send_data);

                $sql = sprintf('select `Email Campaign State` from `Email Campaign Dimension` where `Email Campaign Key`=%d ', $this->id);
                if ($result2 = $this->db->query($sql)) {
                    if ($row2 = $result2->fetch()) {
                        if ($row2['Email Campaign State'] == 'Stopped') {
                            return;
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                $published_email_template->send(get_object($row['Email Tracking Recipient'], $row['Email Tracking Recipient Key']), $send_data, $smarty);


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        // exit('xxx');
        $this->update_state('Sent');

        if (isset($this->socket)) {



            $this->update_metadata['hide'] = array(
                'estimated_recipients',
                'email_campaign_operations'
            );


            $this->socket->send(
                json_encode(
                    array(
                        'channel' => 'real_time.'.strtolower($account->get('Account Code')),
                        'objects' => array(
                            array(
                                'object' => 'email_campaign',
                                'key'    => $this->id,

                                'update_metadata' => $this->get_update_metadata()

                            )

                        ),


                    )
                )
            );
        }


        */


    }

    function get_recipients_sql() {

        switch ($this->data['Email Campaign Type']) {

            case 'Newsletter':

                $sql = sprintf(
                    'select `Customer Key` ,`Customer Main Plain Email` from `Customer Dimension` where `Customer Store Key`=%d and `Customer Main Plain Email`!="" and `Customer Send Newsletter`="Yes" and  `Customer Type by Activity` not in ("Rejected", "ToApprove") ',
                    $this->data['Email Campaign Store Key']
                );


                return $sql;


                break;
            case 'AbandonedCart':

                $metadata = $this->get('Metadata');


                if ($metadata['Type'] == 'Inactive') {
                    $sql = sprintf(
                        'select `Customer Key` ,`Customer Main Plain Email` from `Order Dimension` O  left join `Customer Dimension` on (`Order Customer Key`=`Customer Key`) where `Order State`="InBasket" and `Customer Main Plain Email`!="" and `Customer Send Email Marketing`="Yes" and `Customer Main Plain Email`!="" and `Order Store Key`=%d  and `Order Last Updated by Customer`<= CURRENT_DATE - INTERVAL %d DAY ',
                        $this->data['Email Campaign Store Key'], $metadata['Days Inactive in Basket']
                    );
                } else {
                    $sql = sprintf(
                        'select `Customer Key` ,`Customer Main Plain Email` from `Order Dimension` O  left join `Customer Dimension` on (`Order Customer Key`=`Customer Key`) where `Order State`="InBasket" and `Customer Main Plain Email`!="" and `Customer Send Email Marketing`="Yes" and `Customer Main Plain Email`!="" and `Order Store Key`=%d  and `Order Last Updated by Customer`>= CURRENT_DATE - INTERVAL %d DAY ',
                        $this->data['Email Campaign Store Key'], $metadata['Days Last Updated']
                    );
                }




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
                    'select `Customer Key` ,`Customer Main Plain Email` from `Customer Dimension`    left join `Order Dimension` on (`Customer Last Dispatched Order Key`=`Order Key`) where `Customer Store Key`=%d and  `Customer Send Email Marketing`=\'Yes\' and   `Customer Last Dispatched Order Date`=%s ',
                    $this->data['Email Campaign Store Key'], prepare_mysql($date)
                );

                return $sql;


                break;

            case 'Marketing':
                $metadata = $this->get('Metadata');


                if (isset($metadata['type'])) {
                    switch ($metadata['type']) {
                        case 'awhere':
                            include_once 'utils/parse_customer_list.php';

                            list($table, $where, $group_by) = parse_customer_list($metadata['fields'], $this->db);

                            $where = sprintf(' where `Customer Store Key`=%d ', $this->get('Store Key')).$where.' and `Customer Send Email Marketing`="Yes" and `Customer Main Plain Email`!="" ';

                            $sql = "select C.`Customer Key` ,`Customer Main Plain Email` from $table  $where ";


                            return $sql;


                            break;
                        default:
                            break;
                    }
                }


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
/*
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

*/

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

        $delivered=$sent-$hard_bounces-$errors-$soft_bounces;


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


    function metadata($key) {
        return (isset($this->metadata[$key]) ? $this->metadata[$key] : '');
    }


}



