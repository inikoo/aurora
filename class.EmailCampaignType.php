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
        $this->ignore_fields = array(
            'Email Campaign Type Key',
        );

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


        $this->get_data($arg1, $arg2);

    }

    function get_data($tipo, $tag) {


        $sql = sprintf(
            "SELECT * FROM `Email Campaign Type Dimension` WHERE  `Email Campaign Type Key`=%d", $tag
        );


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



            case 'Name':

                switch ($this->data['Email Campaign Type Code']){
                    case 'Newsletter':
                        $name=_('Newsletter');
                        break;
                    case 'Marketing':
                        $name=_('Mailshot');
                        break;
                    case 'AbandonedCart':
                        $name=_('Abandoned cart');
                        break;
                    case 'OOS Notification':
                        $name=_('Out of stock notification');
                        break;
                    case 'Registration':
                        $name=_('Welcome');
                        break;
                    case 'Password Reminder':
                        $name=_('Password reset');
                        break;
                    case 'Order Confirmation':
                        $name=_('order confirmation');
                        break;
                    case 'GR Reminder':
                        $name=_('Reorder reminder');
                        break;
                    default:
                        $name=$this->data['Email Campaign Type Code'];


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




}


?>
