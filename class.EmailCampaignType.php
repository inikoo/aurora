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

    function EmailCampaignType($arg1 = false, $arg2 = false, $arg3 = false) {


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
                        $name = _('Abandoned carts');
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
                    default:
                        $name = $this->data['Email Campaign Type Code'];


                }

                return $name;
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


    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        switch ($field) {

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

                $metadata                           = $this->get('Metadata');
                $metadata['Schedule']['Time'] = $value;
                $this->update_field('Email Campaign Type Metadata', json_encode($metadata), 'no_history');

                break;
            case 'Email Campaign Type State':

                $this->update_state($value);
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

    function suspend(){

        $this->update_field_switcher('Email Campaign Type Status','Suspended');

    }

    function activate(){

        $this->update_field_switcher('Email Campaign Type Status','Active');

    }


}


?>
