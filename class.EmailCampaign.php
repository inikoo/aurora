<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 25 September 2017 at 14:19:21 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2010-2015, Inikoo

 Version 3.0


*/
include_once 'class.DB_Table.php';

class EmailCampaign extends DB_Table
{


    function __construct($arg1 = false, $arg2 = false)
    {
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

    function get_data($tipo, $tag)
    {
        $sql = sprintf(
            "SELECT * FROM `Email Campaign Dimension` WHERE  `Email Campaign Key`=%d",
            $tag
        );


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id       = $this->data['Email Campaign Key'];
            $this->metadata = ($this->data['Email Campaign Metadata'] == '' ? array() : json_decode($this->data['Email Campaign Metadata'], true));
            $this->store=get_object('Store',$this->data['Email Campaign Store Key']);
        }
    }

    function find($raw_data, $options)
    {
        if (isset($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {
                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }
            }
        }

        $this->found     = false;
        $this->found_key = false;

        $create = '';
        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }

        if ($create and !$this->found) {
            $this->create($raw_data);
        }
    }

    function create($raw_data)
    {
        $data = $this->base_data();
        $this->editor = $raw_data['editor'];
        unset($raw_data['editor']);


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


        if (empty($data['Email Campaign Metadata'])) {
            $data['Email Campaign Metadata'] = '{}';
        }

        $data['Email Campaign Creation Date']     = gmdate('Y-m-d H:i:s');
        $data['Email Campaign Last Updated Date'] = gmdate('Y-m-d H:i:s');

        $sql = sprintf(
            "INSERT INTO `Email Campaign Dimension` (%s) values (%s)",
            '`'.join('`,`', array_keys($data)).'`',
            join(',', array_fill(0, count($data), '?'))
        );

        $stmt = $this->db->prepare($sql);


        $i = 1;
        foreach ($data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {
            $this->id = $this->db->lastInsertId();
            $this->get_data('id', $this->id);
            $this->new = true;
            /** @var $store \Store */
            $store = get_object('Store', $this->data['Email Campaign Store Key']);
            $store->update_email_campaign_data();


            switch ($this->get('Email Campaign Type')) {
                case 'AbandonedCart':
                    $history_abstract = sprintf(_('Mailshot for orders in basket created (%s)'), '<b>'.$this->data['Email Campaign Name'].'</b>');

                    break;
                case 'Newsletter':
                    $history_abstract = _('Newsletter created');
                    break;
                case 'Invite Full Mailshot':
                    $history_abstract = _('Invitation mailshot');
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

            $this->add_subject_history(
                $history_data,
                true,
                'No',
                'Changes',
                $this->get_object_name(),
                $this->id
            );
        } else {
            $this->error = true;
            $this->msg   = "Can not insert Email Campaign Dimension";

        }
    }

    function get($key)
    {
        if (!$this->id) {
            return false;
        }

        switch ($key) {
            case 'Subject':

                if ($this->data['Email Campaign Wave Type'] == 'Wave' and $this->metadata('subject') != '') {
                    $subject = $this->metadata('subject');
                } else {
                    $email_template = get_object('Email Template', $this->data['Email Campaign Email Template Key']);
                    $published_email_template = get_object('published_email_template', $email_template->get('Email Template Published Email Key'));
                    if ($published_email_template->id) {
                        $subject = $published_email_template->get('Subject');
                    } else {
                        $subject = $email_template->get('Subject');
                    }
                }
                if ($subject == '') {
                    $subject = $this->get('Name');
                }

                return $subject;


            case 'Email Campaign Second Wave Subject':
            case 'Second Wave Subject':

                return $this->metadata('second_wave_subject');

            case 'Second Wave Formatted Date':
                if ($this->data['Email Campaign Second Wave Date'] == '') {
                    return '';
                }
                return '<i class="fal fa-stopwatch"></i> '.strftime("%a %e %b %H:%M %Z", strtotime($this->data['Email Campaign Second Wave Date'].' +0:00'));


            case 'Metadata':

                if ($this->data['Email Campaign '.$key] == '') {
                    $content_data = false;
                } else {
                    $content_data = json_decode($this->data['Email Campaign '.$key], true);
                }
                return $content_data;


            case 'State Index':

                switch ($this->data['Email Campaign State']) {
                    case 'InProcess':
                        return 10;

                    case 'ComposingEmail':
                        return 20;

                    case 'Ready':
                        return 30;

                    case 'Scheduled':
                        return 40;
                    case 'Sending':
                        return 50;
                    case 'Stopped':
                        return 60;
                    case 'Cancelled':
                        return 70;
                    case 'Sent':
                        return 100;
                    default:
                        return 0;

                }


            case ('State Icon'):

                switch ($this->data['Email Campaign State']) {
                    case 'InProcess':
                        return 'fal fa-drafting-compass';

                    case 'ComposingEmail':
                        return 'fal fa-pen-nib';

                    case 'Ready':
                        return 'fal fa-clock';

                    case 'Scheduled':
                        return 'far fa-clock';

                    case 'Sending':
                        return 'fal fa-paper-plane very_discreet';

                    case 'Stopped':
                        return 'fa-pause';

                    case 'Cancelled':
                        return 'fa red fa-octagon';

                    case 'Sent':
                        return 'fal fa-paper-plane';



                    default:
                        return '';

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
                    case 'ComposingEmail':
                        if ($this->get('Email Campaign Selecting Blueprints') == 'No') {
                            return _('Composing email');
                        } else {
                            return _('Changing template');
                        }

                    case 'Ready':
                        return _('Ready to send');

                    case 'Scheduled':
                        return _('Scheduled to be send');


                    case 'Sending':
                        return _('Sending');


                    case 'Cancelled':
                        return _('Cancelled');

                    case 'Sent':
                        return _('Sent');



                    default:
                        return $this->data['Email Campaign State'];

                }


                break;


            case 'Email Campaign Abandoned Cart Type':
                $metadata = $this->get('Metadata');

                return $metadata['Type'];

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

            case 'Email Campaign Cool Down Days':
                return ($this->metadata('Cool_Down_Days') == '' ? 180 : $this->metadata('Cool_Down_Days'));
            case 'Cool Down Days':
                return (is_numeric($this->metadata('Cool_Down_Days')) ? number($this->metadata('Cool_Down_Days')) : 180);

            case 'Email Campaign Max Number Emails':
                return $this->metadata('Max_Number_Emails');
            case 'Max Number Emails':
                return (is_numeric($this->metadata('Max_Number_Emails')) ? number($this->metadata('Max_Number_Emails')) : '');
            case 'Abandoned Cart Days Inactive in Basket':
                $metadata = $this->get('Metadata');

                return number((isset($metadata['Days Inactive in Basket']) ? $metadata['Days Inactive in Basket'] : 0));

            case 'Email Campaign Abandoned Cart Days Inactive in Basket':
                $metadata = $this->get('Metadata');

                return (isset($metadata['Days Inactive in Basket']) ? $metadata['Days Inactive in Basket'] : '');

            case 'Email Campaign Abandoned Cart Days Last Updated':
                $metadata = $this->get('Metadata');

                return (isset($metadata['Days Last Updated']) ? $metadata['Days Last Updated'] : '');

            case 'Abandoned Cart Days Last Updated':
                $metadata = $this->get('Metadata');

                return number(isset($metadata['Days Last Updated']) ? $metadata['Days Last Updated'] : 0);

            case 'Number Estimated Emails':
                return number($this->data['Email Campaign '.$key]);


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



            case 'Creation Date':
            case 'Setup Date':
            case 'Scheduled Date':
            case 'Composed Date':
            case 'Start Send Date':
            case 'End Send Date':
                if ($this->data['Email Campaign '.$key] != '') {

                    $date = new DateTime($this->data['Email Campaign '.$key]);


                    $date->setTimezone(new DateTimeZone($this->store->get('Store Timezone')));
                    return strftime('%e %b %y %k:%M', strtotime($date->format('Y-m-d H:i:s')));

                   // return strftime('%e %b %y %k:%M', strtotime($this->data['Email Campaign '.$key]));
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

            case 'Open Percentage':
            case 'Clicked Percentage':
            case 'Spams Percentage':
                if ($this->data['Email Campaign Sent'] == 0) {
                    return percentage(0, 1);
                }

                return percentage($this->data['Email Campaign '.preg_replace('/ Percentage/', '', $key)], $this->data['Email Campaign Sent']);


            case 'Unsubscribed Percentage':
                if ($this->data['Email Campaign Open'] == 0) {
                    return percentage(0, 1);
                }

                return percentage($this->data['Email Campaign Unsubscribed'], $this->data['Email Campaign Open']);


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

    function metadata($key)
    {
        return (isset($this->metadata[$key]) ? $this->metadata[$key] : '');
    }

    function update_estimated_recipients()
    {
        if ($this->get('State Index') < 40) {
            $estimated_recipients = 0;

            switch ($this->get('Email Campaign Type')) {
                case 'AbandonedCart':

                    $metadata = $this->get('Metadata');


                    if ($metadata['Type'] == 'Inactive') {
                        $sql = sprintf(
                            'SELECT count(DISTINCT O.`Order Key`) AS num FROM `Order Dimension` O LEFT JOIN `Customer Dimension` ON (`Order Customer Key`=`Customer Key`) WHERE `Order State`="InBasket" AND `Customer Main Plain Email`!="" AND `Customer Send Email Marketing`="Yes" AND `Order Store Key`=%d AND `Order Last Updated by Customer`<= CURRENT_DATE - INTERVAL %d DAY',
                            $this->data['Email Campaign Store Key'],
                            (empty($metadata['Days Inactive in Basket']) ? 0 : $metadata['Days Inactive in Basket'])
                        );
                    } else {
                        $sql = sprintf(
                            'SELECT count(DISTINCT O.`Order Key`) AS num FROM `Order Dimension` O LEFT JOIN `Customer Dimension` ON (`Order Customer Key`=`Customer Key`) WHERE `Order State`="InBasket" AND `Customer Main Plain Email`!="" AND `Customer Send Email Marketing`="Yes" AND `Order Store Key`=%d AND `Order Last Updated by Customer`>= CURRENT_DATE - INTERVAL %d DAY',
                            $this->data['Email Campaign Store Key'],
                            (empty($metadata['Days Last Updated']) ? 0 : $metadata['Days Last Updated'])
                        );
                    }


                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $estimated_recipients = $row['num'];
                        }
                    }

                    break;

                case 'Newsletter':


                    if ($this->data['Email Campaign Wave Type'] == 'Wave') {
                        $sql = "select count(*)  as num from `Email Tracking Dimension` where `Email Tracking Email Mailshot Key`=? and `Email Tracking State`='Sent'     ";

                        $stmt = $this->db->prepare($sql);
                        $stmt->execute(
                            array(
                                $this->get('Email Campaign First Wave Key')
                            )
                        );
                        if ($row = $stmt->fetch()) {
                            $estimated_recipients = $row['num'];
                        }
                    } else {
                        $sql = "select count(*)  as num from `Customer Dimension` where `Customer Store Key`=? and `Customer Main Plain Email`!='' and `Customer Send Newsletter`='Yes' and  `Customer Type by Activity` not in ('Rejected', 'ToApprove')";


                        $stmt = $this->db->prepare($sql);
                        $stmt->execute(
                            array(
                                $this->get('Store Key')
                            )
                        );
                        if ($row = $stmt->fetch()) {
                            $estimated_recipients = $row['num'];
                        }
                    }


                    break;
                case 'OOS Notification':
                    $sql = sprintf(
                        'select count(*)  as num from `Back in Stock Reminder Fact` where `Back in Stock Reminder Store Key`=%d and `Back in Stock Reminder State`="Ready" ',
                        $this->get('Store Key')
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
                        $this->data['Email Campaign Store Key'],
                        prepare_mysql($date)
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


                    if ($this->data['Email Campaign Wave Type'] == 'Wave') {
                        $sql = "select count(*)  as num from `Email Tracking Dimension` where `Email Tracking Email Mailshot Key`=? and `Email Tracking State`='Sent'  ";

                        //print $sql;

                        $stmt = $this->db->prepare($sql);
                        $stmt->execute(
                            array(
                                $this->get('Email Campaign First Wave Key')
                            )
                        );
                        if ($row = $stmt->fetch()) {
                            $estimated_recipients = $row['num'];
                        }
                    } else {
                        include_once 'utils/asset_marketing_customers.php';


                        switch ($this->data['Email Campaign Scope']) {
                            case 'Customer_List':

                                $list = get_object('List', $this->data['Email Campaign Scope Key']);

                                $estimated_recipients = $list->get('Number Items');

                                break;
                            case 'Product Targeted':

                                $product = get_object('Product', $this->data['Email Campaign Scope Key']);
                                if ($product->properties('targeted_marketing_customers') == '' or $product->properties('targeted_marketing_customers_last_updated') < (gmdate('U') - 3600)) {
                                    $product->update_product_targeted_marketing_customers();
                                }
                                $estimated_recipients = $product->properties('targeted_marketing_customers');

                                break;
                            case 'Product Wide':

                                $product = get_object('Product', $this->data['Email Campaign Scope Key']);
                                if ($product->properties('spread_marketing_customers') == '' or $product->properties('spread_marketing_customers_last_updated') < (gmdate('U') - 3600)) {
                                    $product->update_product_spread_marketing_customers();
                                }
                                $estimated_recipients = $product->properties('spread_marketing_customers');

                                break;
                            case 'Product Donut':
                                /** @var Product $product */
                                $product = get_object('Product', $this->data['Email Campaign Scope Key']);
                                if ($product->properties('donut_marketing_customers') == '' or $product->properties('donut_marketing_customers_last_updated') < (gmdate('U') - 3600)) {
                                    $product->update_product_donut_marketing_customers();
                                }
                                $estimated_recipients = $product->properties('donut_marketing_customers');


                                break;
                            case 'Category Targeted':

                                $category = get_object('Category', $this->data['Email Campaign Scope Key']);
                                if ($category->properties('targeted_marketing_customers') == '' or $category->properties('targeted_marketing_customers_last_updated') < (gmdate('U') - 3600)) {
                                    $category->update_product_category_targeted_marketing_customers();
                                }
                                $estimated_recipients = $category->properties('targeted_marketing_customers');
                                break;

                            case 'Category Wide':

                                $category = get_object('Category', $this->data['Email Campaign Scope Key']);
                                if ($category->properties('spread_marketing_customers') == '' or $category->properties('spread_marketing_customers_last_updated') < (gmdate('U') - 3600)) {
                                    $category->update_product_category_spread_marketing_customers();
                                }
                                $estimated_recipients = $category->properties('spread_marketing_customers');

                                break;
                            case 'Category Donut':

                                $category = get_object('Category', $this->data['Email Campaign Scope Key']);
                                if ($category->properties('donut_marketing_customers') == '' or $category->properties('donut_marketing_customers_last_updated') < (gmdate('U') - 3600)) {
                                    $category->update_product_category_donut_marketing_customers();
                                }
                                $estimated_recipients = $category->properties('donut_marketing_customers');

                                break;
                        }
                    }
                    break;

                case 'Invite Full Mailshot':

                    $metadata = $this->get('Metadata');

                    $sql =
                        "select count(*)  as num from `Prospect Dimension` where `Prospect Store Key`=? and `Prospect Main Plain Email`!='' and  ( `Prospect Status`='NoContacted'  or  ( `Prospect Status`='Contacted' and  `Prospect Last Contacted Date`<= CURRENT_DATE - INTERVAL ? DAY   )  )  ";


                    $stmt = $this->db->prepare($sql);
                    $stmt->execute(
                        array(
                            $this->get('Store Key'),
                            (empty($metadata['Cool_Down_Days']) ? 180 : $metadata['Cool_Down_Days'])
                        )
                    );
                    if ($row = $stmt->fetch()) {
                        $estimated_recipients = $row['num'];

                        if (is_numeric($this->metadata('Max_Number_Emails')) and $this->metadata('Max_Number_Emails') >= 0) {
                            $estimated_recipients = min($estimated_recipients, $this->metadata('Max_Number_Emails'));
                        }
                    }


                    break;

                default:
            }

            $this->fast_update(array('Email Campaign Number Estimated Emails' => $estimated_recipients));
        }
    }

    function delete()
    {
        if (in_array(
            $this->data['Email Campaign State'], array(
                                                   'InProcess',
                                                   'ComposingEmail',
                                                   'Ready'
                                               )
        )) {
            /** @var $store \Store */
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
                "DELETE FROM `Email Campaign Dimension` WHERE `Email Campaign Key`=%d",
                $this->id
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
                case 'Marketing':


                    return sprintf('marketing/%d/emails/%d', $store->id, $email_template_type->id);

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

    function update_field_switcher($field, $value, $options = '', $metadata = '')
    {
        switch ($field) {
            case 'Email Campaign Second Wave Subject':

                $this->fast_update_json_field('Email Campaign Metadata', 'second_wave_subject', $value);
                break;

            case 'Email Campaign State':

                $this->update_state($value);
                break;


            case 'Metadata':

                $this->update_field('Email Campaign '.$field, $value, $options);
                break;
            case 'Email Campaign Max Number Emails':
                $metadata = $this->get('Metadata');
                if ($metadata == '') {
                    $metadata = array();
                }

                $metadata['Max_Number_Emails'] = $value;
                $this->fast_update(array('Email Campaign Metadata' => json_encode($metadata)));
                $this->metadata = ($this->data['Email Campaign Metadata'] == '' ? array() : json_decode($this->data['Email Campaign Metadata'], true));
                $this->update_estimated_recipients();
                $this->update_metadata = array(
                    'class_html' => array(
                        'Email_Campaign_Number_Estimated_Emails' => $this->get('Email Campaign Number Estimated Emails'),
                        'Number_Estimated_Emails'                => $this->get('Number Estimated Emails'),
                    ),

                );
                break;
            case 'Email Campaign Cool Down Days':
                $metadata = $this->get('Metadata');
                if ($metadata == '') {
                    $metadata = array();
                }

                $metadata['Cool_Down_Days'] = $value;
                $this->fast_update(array('Email Campaign Metadata' => json_encode($metadata)));
                $this->metadata = ($this->data['Email Campaign Metadata'] == '' ? array() : json_decode($this->data['Email Campaign Metadata'], true));
                $this->update_estimated_recipients();
                $this->update_metadata = array(
                    'class_html' => array(
                        'Email_Campaign_Number_Estimated_Emails' => $this->get('Email Campaign Number Estimated Emails'),
                        'Number_Estimated_Emails'                => $this->get('Number Estimated Emails'),
                    ),

                );
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

    function update_state($value, $extra_data = [])
    {
        $operations = array();

        $old_state = $this->data['Email Campaign State'];

        switch ($value) {
            case 'Scheduled':

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
                        'Email Campaign Scheduled Date'    => $extra_data['Email Campaign Scheduled Date']
                    )
                );

                $operations = array(
                    'delete_operations',
                    'stop_schedule_operations',

                );
                break;

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


                if ($this->data['Email Campaign State'] == 'Cancelled') {
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
                            'Email Campaign Composed Date'     => gmdate('Y-m-d H:i:s'),
                            'Email Campaign Scheduled Date'    => ''

                        )
                    );
                }

                $operations = array(
                    'delete_operations',
                    'schedule_mailshot_operations',
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
                        'Email Campaign Scheduled Date'    => ''
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
                                'update `Email Tracking Dimension` set `Email Tracking Thread`=`Email Tracking Thread`+%d where=`Email Tracking Email Mailshot Key`=%d  where `Email Tracking State`="Ready"   ',
                                $row['max_thread'] + 1,
                                $this->id
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

                if ($old_state == 'Sending' and $this->data['Email Campaign Second Wave Date'] != '') {
                    $this->fast_update(
                        [
                            'Email Campaign Second Wave Date' => $this->get_second_wave_date()
                        ]
                    );
                }

                $operations = array();

                break;
        }


        $this->update_metadata = array(
            'class_html'  => array(
                'Email_Campaign_Name'            => $this->get('Name'),
                'Email_Campaign_State'           => $this->get('State'),
                'Email_Campaign_Setup_Date'      => '&nbsp;'.$this->get('Setup Date'),
                'Email_Campaign_Scheduled_Date'      => '&nbsp;'.$this->get('Scheduled Date'),
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


    function get_second_wave_date()
    {
        $store = get_object('Store', $this->data['Email Campaign Store Key']);

        if (in_array(
            date('D'), [
                         'Fri',
                         'Thu',
                         'Sat',
                         'Sun'
                     ]
        )) {
            $second_wave_date = strtotime('next monday 10am '.$store->get('Store Timezone'));
        } else {
            $second_wave_date = strtotime('+2 days 10am '.$store->get('Store Timezone'));
        }


        if ($this->data['Email Campaign State'] == 'Sent' and ($second_wave_date < strtotime('now'))) {
            $second_wave_date = strtotime('+1 hour');
        }


        return gmdate('Y-m-d H:i:s', $second_wave_date);
    }

    function get_field_label($field)
    {
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
            case 'Email Campaign Max Number Emails':
                $label = _('Max number of emails');
                break;
            case 'Email Campaign Cool Down Days':
                $label = _('Days since last contact');
                break;
            default:
                $label = $field;
        }

        return $label;
    }

    function send_mailshot($first_thread = 1)
    {
        include_once 'class.Email_Tracking.php';
        $account = get_object('Account', 1);
        $store   = get_object('Store', $this->get('Store Key'));

        $email_template_type = get_object('email_template_type', $this->data['Email Campaign Email Template Type Key']);
        $email_template      = get_object('email_template', $this->data['Email Campaign Email Template Key']);


        if ($this->get('Email Campaign Wave Type') == 'Yes') {
            $this->fast_update(['Email Campaign Second Wave Date' => $this->get_second_wave_date()]);
        };

        if ($this->data['Email Campaign Type'] == 'Invite Full Mailshot') {
            $recipient_type = 'Prospect';
        } else {
            $recipient_type = 'Customer';
        }

        $thread      = $first_thread;
        $thread_size = 50;
        $contador    = 0;


        if ($this->data['Email Campaign Type'] == 'Marketing') {
            include_once 'utils/asset_marketing_customers.php';


            $targeted_threshold = min($store->properties('email_marketing_customers') * .05, 500);
            $wide_threshold     = $targeted_threshold * 5;

            $customers = array();

            switch ($this->data['Email Campaign Scope']) {
                case 'Product Targeted':
                    $customers = get_targeted_product_customers($customers, $this->db, $this->data['Email Campaign Scope Key'], $targeted_threshold);
                    break;
                case 'Product Wide':
                    $customers = get_spread_product_customers($customers, $this->db, $this->data['Email Campaign Scope Key'], $wide_threshold);
                    break;
                case 'Product Donut':

                    $targeted_customers = get_targeted_product_customers($customers, $this->db, $this->data['Email Campaign Scope Key'], $targeted_threshold);
                    $spread_customers   = get_spread_product_customers($customers, $this->db, $this->data['Email Campaign Scope Key'], $wide_threshold);
                    $customers          = array_diff($spread_customers, $targeted_customers);
                    break;
                case 'Category Targeted':
                    $customers = get_targeted_categories_customers($customers, $this->db, $this->data['Email Campaign Scope Key'], $targeted_threshold);
                    break;
                case 'Category Wide':

                    $customers = get_spread_categories_customers($customers, $this->db, $this->data['Email Campaign Scope Key'], $wide_threshold);
                    break;
                case 'Category Donut':
                    $targeted_customers = get_targeted_categories_customers($customers, $this->db, $this->data['Email Campaign Scope Key'], $targeted_threshold);
                    $spread_customers   = get_spread_categories_customers($customers, $this->db, $this->data['Email Campaign Scope Key'], $wide_threshold);
                    $customers          = array_diff($spread_customers, $targeted_customers);
                    break;
                case 'Customer_List':

                    $list = get_object('List', $this->data['Email Campaign Scope Key']);

                    if ($list->get('List Type') == 'Static') {
                        $table = '`List Customer Bridge` CB left join `Customer Dimension` C  on (CB.`Customer Key`=C.`Customer Key`)';
                        $where = sprintf(
                            ' where `List Key`=%d ',
                            $list->id
                        );
                    } else {
                        include_once 'utils/parse_customer_list.php';

                        $_data              = json_decode($list->get('List Metadata'), true);
                        $_data['store_key'] = $list->get('List Parent Key');

                        list($table, $where, $group_by) = parse_customer_list($_data, $this->db);
                        $where = sprintf(' where `Customer Store Key`=%d ', $list->get('List Parent Key')).$where;
                    }

                    $sql       = sprintf(
                        'select C.`Customer Key` ,C.`Customer Main Plain Email` from  %s %s  and `Customer Send Email Marketing`="Yes" and `Customer Main Plain Email`!=""  ',
                        $table,
                        $where
                    );
                    $customers = array();
                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {
                            $customers[$row['Customer Key']] = $row['Customer Main Plain Email'];
                        }
                    }


                    break;
            }


            foreach ($customers as $customer_key => $email) {
                list($contador, $thread_size, $thread) = $this->create_email_tracking($customer_key, $email, $recipient_type, $account, $email_template_type, $email_template, $contador, $thread_size, $thread);
            }
        }
        else {
            $sql = $this->get_recipients_sql();

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    list($contador, $thread_size, $thread) = $this->create_email_tracking($row[$recipient_type.' Key'], $row[$recipient_type.' Main Plain Email'], $recipient_type, $account, $email_template_type, $email_template, $contador, $thread_size, $thread);
                }
            }
        }


        if ($contador > 0) {
            $client        = new GearmanClient();
            $fork_metadata = json_encode(
                array(
                    'code' => addslashes($account->get('Code')),
                    'data' => array(
                        'mailshot' => $this->id,
                        'thread'   => $thread,
                        'editor'   => $this->editor
                    )
                )
            );

            include_once 'keyring/au_deploy_conf.php';
            $servers = explode(",", GEARMAN_SERVERS);
            shuffle($servers);
            $servers = implode(",", $servers);
            $client->addServers($servers);
            $client->doBackground('au_send_mailshot', $fork_metadata);
        }


        $sql = sprintf('select count(*) as num from `Email Tracking Dimension` where `Email Tracking Email Mailshot Key`=%d  ', $this->id);

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->fast_update(array('Email Campaign Number Estimated Emails' => $row['num']));
            }
        }
    }

    function create_email_tracking($customer_key, $email, $recipient_type, $account, $email_template_type, $email_template, $contador, $thread_size, $thread)
    {
        $sql_find = sprintf(
            'select `Email Tracking Key` from `Email Tracking Dimension` where `Email Tracking Email Mailshot Key`=%s and `Email Tracking Email`=%s ',
            $this->id,
            prepare_mysql($email)
        );

        if ($result_find = $this->db->query($sql_find)) {
            if ($row_find = $result_find->fetch()) {
            } else {
                $email_tracking_data = array(
                    'Email Tracking Email' => $email,

                    'Email Tracking Email Template Type Key'      => $email_template_type->id,
                    'Email Tracking Email Template Key'           => $email_template->id,
                    'Email Tracking Email Mailshot Key'           => $this->id,
                    'Email Tracking Published Email Template Key' => $email_template->get('Email Template Published Email Key'),
                    'Email Tracking Recipient'                    => $recipient_type,
                    'Email Tracking Recipient Key'                => $customer_key,
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
                    include_once 'keyring/au_deploy_conf.php';
                    $servers = explode(",", GEARMAN_SERVERS);
                    shuffle($servers);
                    $servers = implode(",", $servers);
                    $client->addServers($servers);
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
        }

        return array(
            $contador,
            $thread_size,
            $thread
        );
    }

    function get_recipients_sql()
    {
        switch ($this->data['Email Campaign Type']) {
            case 'Newsletter':

                if ($this->data['Email Campaign Wave Type'] == 'Wave') {
                    $sql = sprintf(
                        "select `Customer Key` ,`Customer Main Plain Email`  from `Email Tracking Dimension` left join   `Customer Dimension` on (`Customer Key`=`Email Tracking Recipient Key`)  where `Customer Main Plain Email`!='' and `Customer Send Newsletter`='Yes' and   `Email Tracking Email Mailshot Key`=%d and `Email Tracking State`='Sent' and  `Customer Type by Activity` not in ('Rejected', 'ToApprove') ",

                        $this->get('Email Campaign First Wave Key')
                    );
                } else {
                    $sql = sprintf(
                        'select `Customer Key` ,`Customer Main Plain Email` from `Customer Dimension` where `Customer Store Key`=%d and `Customer Main Plain Email`!="" and `Customer Send Newsletter`="Yes" and  `Customer Type by Activity` not in ("Rejected", "ToApprove") order by `Customer Key` desc ',
                        $this->data['Email Campaign Store Key']
                    );
                }

                return $sql;


            case 'AbandonedCart':

                $metadata = $this->get('Metadata');


                if ($metadata['Type'] == 'Inactive') {
                    $sql = sprintf(
                        'select `Customer Key` ,`Customer Main Plain Email` from `Order Dimension` O  left join `Customer Dimension` on (`Order Customer Key`=`Customer Key`) where `Order State`="InBasket" and `Customer Send Email Marketing`="Yes" and `Customer Main Plain Email`!="" and `Order Store Key`=%d  and `Order Last Updated by Customer`<= CURRENT_DATE - INTERVAL %d DAY ',
                        $this->data['Email Campaign Store Key'],
                        $metadata['Days Inactive in Basket']
                    );
                } else {
                    $sql = sprintf(
                        'select `Customer Key` ,`Customer Main Plain Email` from `Order Dimension` O  left join `Customer Dimension` on (`Order Customer Key`=`Customer Key`) where `Order State`="InBasket"  and `Customer Send Email Marketing`="Yes" and `Customer Main Plain Email`!="" and `Order Store Key`=%d  and `Order Last Updated by Customer`>= CURRENT_DATE - INTERVAL %d DAY ',
                        $this->data['Email Campaign Store Key'],
                        $metadata['Days Last Updated']
                    );
                }


                return $sql;


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
                    $this->data['Email Campaign Store Key'],
                    prepare_mysql($date)
                );

                return $sql;


            case 'Invite Full Mailshot':

                $metadata = $this->get('Metadata');

                $sql = sprintf(
                    "select `Prospect Key`,`Prospect Main Plain Email` from `Prospect Dimension` where `Prospect Store Key`=%d and `Prospect Main Plain Email`!='' and  ( `Prospect Status`='NoContacted'  or  ( `Prospect Status`='Contacted' and  `Prospect Last Contacted Date`<= CURRENT_DATE - INTERVAL %d DAY   )  )  ",
                    $this->data['Email Campaign Store Key'],
                    (empty($metadata['Cool_Down_Days']) ? 180 : $metadata['Cool_Down_Days'])
                );

                if (is_numeric($this->metadata('Max_Number_Emails')) and $this->metadata('Max_Number_Emails') >= 0) {
                    $sql .= sprintf(' limit %d', $this->metadata('Max_Number_Emails'));
                }

                return $sql;

            case 'Marketing':

                if ($this->data['Email Campaign Wave Type'] == 'Wave') {
                    $sql = sprintf(
                        "select `Customer Key` ,`Customer Main Plain Email`  from `Email Tracking Dimension` left join   `Customer Dimension` on (`Customer Key`=`Email Tracking Recipient Key`)  where `Customer Main Plain Email`!='' and `Customer Send Newsletter`='Yes' and   `Email Tracking Email Mailshot Key`=%d and `Email Tracking State`='Sent' and  `Customer Type by Activity` not in ('Rejected', 'ToApprove') ",

                        $this->get('Email Campaign First Wave Key')
                    );

                    return $sql;
                } else {
                    $metadata = $this->get('Metadata');


                    if (isset($metadata['type'])) {
                        switch ($metadata['type']) {
                            case 'awhere':
                                include_once 'utils/parse_customer_list.php';

                                list($table, $where, $group_by) = parse_customer_list($metadata['fields'], $this->db);

                                $where = sprintf(' where `Customer Store Key`=%d ', $this->get('Store Key')).$where.' and `Customer Send Email Marketing`="Yes" and `Customer Main Plain Email`!="" ';

                                $sql = "select C.`Customer Key` ,`Customer Main Plain Email` from $table  $where ";


                                return $sql;


                            default:
                                break;
                        }
                    }
                }
                break;
        }
    }

    function update_sent_emails_totals()
    {
        return;
        $unsubscribed = 0;
        $sent         = 0;
        $open         = 0;
        $clicked      = 0;
        $errors       = 0;
        $delivered    = 0;
        $hard_bounces = 0;
        $soft_bounces = 0;
        $spam         = 0;


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
        }

        $delivered = $sent - $hard_bounces - $errors - $soft_bounces;


        $sql = "select count(*) as num  from `Email Tracking Dimension` where `Email Tracking Email Mailshot Key`=? and `Email Tracking Spam`='Yes'";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        if ($row = $stmt->fetch()) {
            $spam = $row['num'];
        }


        $sql = sprintf('select count(*) as num  from `Email Tracking Dimension` where `Email Tracking Email Mailshot Key`=%d and `Email Tracking Unsubscribed`="Yes" ', $this->id);
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $unsubscribed = $row['num'];
            }
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

    function create_second_wave()
    {
        if ($this->data['Email Campaign Wave Type'] == 'Wave' or $this->data['Email Campaign Wave Type'] == 'Sent') {
            return;
        }

        $email_template_type = get_object('EmailCampaignType', $this->get('Email Campaign Email Template Type Key'));

        //todo Create 2nd waves for Marketing emails (Needs to pass scope and scope key somehow)
        $second_wave_mailshot = $email_template_type->create_mailshot(
            array(
                'Email Campaign Name' => $this->get('Email Campaign Name').' ('._('2nd wave').')',


            )
        );

        $second_wave_mailshot->fast_update(
            array(
                'Email Campaign First Wave Key'     => $this->id,
                'Email Campaign Wave Type'          => 'Wave',
                'Email Campaign Email Template Key' => $this->data['Email Campaign Email Template Key'],
                'Email Campaign State'              => 'Ready',
                'Email Campaign Setup Date'         => gmdate('Y-m-d H:i:s'),
                'Email Campaign Composed Date'      => gmdate('Y-m-d H:i:s'),
            )
        );


        $this->fast_update_json_field('Email Campaign Metadata', 'subject', $this->metadata('second_wave_subject'));


        $this->fast_update(
            [
                'Email Campaign Second Wave Key' => $second_wave_mailshot->id,
                'Email Campaign Wave Type'       => 'Sent'
            ]
        );

        $second_wave_mailshot->update_estimated_recipients();

        $second_wave_mailshot->update_state('Sending');


        include_once 'utils/new_fork.php';


        new_housekeeping_fork(
            'au_send_mailshots',
            array(
                'type'         => 'send_mailshot',
                'mailshot_key' => $second_wave_mailshot->id,
                'editor'       => $this->editor

            ),
            DNS_ACCOUNT_CODE
        );
    }

    function can_create_second_wave()
    {
        $can_create_second_wave = false;

        // This is restricted as well in send_mailshots.fork.php line 86
        if (in_array(
                $this->get('Email Campaign Type'), [
                                                     'Newsletter',
                                                     // 'Marketing'
                                                 ]
            ) and $this->data['Email Campaign Wave Type'] != 'Wave') {
            $can_create_second_wave = true;

            if ($this->data['Email Campaign State'] == 'Sent' and (strtotime($this->data['Email Campaign End Send Date']) < strtotime('now -4 days'))) {
                $can_create_second_wave = false;
            }
        }


        return $can_create_second_wave;
    }


}



