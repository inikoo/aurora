<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 July 2018 at 21:50:49 GMT+8, Kuala Lumpur Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0


*/
include_once 'class.DB_Table.php';

class Sales_Representative extends DB_Table {

    var $new = false;
    var $updated_data = array();

    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {


        global $db;
        $this->db = $db;

        $this->table_name    = 'Sales Representative';
        $this->ignore_fields = array('Sales Representative Key');

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
                "SELECT * FROM `Sales Representative Dimension` WHERE  `Sales Representative Key`=%d", $tag
            );
        }  else {
            return;
        }


        // print "$sql\n";

        if ($this->data = $this->db->query($sql)->fetch()) {

            $this->id    = $this->data['Sales Representative Key'];
            $this->user = get_object('User', $this->data['Sales Representative User Key']);

        }


    }


    function find($raw_data, $options) {

        if (isset($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {
                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }
            }
            unset($raw_data['editor']);
        }


        $this->found     = false;
        $this->found_key = false;


        $sql = sprintf(
            "SELECT `Sales Representative Key` FROM `Sales Representative Dimension` WHERE  `Sales Representative User Key`=%d", $raw_data['Sales Representative User Key']
        );



        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->found_key = $row['Sales Representative Key'];
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

    function create($data) {


        $data['Sales Representative Created Date'] = gmdate('Y-m-d H:i:s');


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


        $sql = "insert into `Sales Representative Dimension` $keys  $values";


        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();

            $this->get_data('id', $this->id);


            $this->new = true;


            $history_abstract = sprintf(_('%s promoted as sales representative'), $this->user->get('Alias'));


            $history_data = array(
                'History Abstract' => $history_abstract,
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );

            $history_data = array(
                'History Abstract' => $history_abstract,
                'History Details'  => '',
                'Action'           => 'edited'
            );

            $this->user->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->user->get_object_name(), $this->user->id
            );

            if($this->user->get_staff_key()>0){
                $staff=get_object('Staff',$this->user->get_staff_key());

                $staff->add_subject_history(
                    $history_data, true, 'No', 'Changes', $this->user->get_object_name(), $this->user->id
                );
            }


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


            default:
                if (isset($this->data[$key])) {
                    return $this->data[$key];
                }

                if (array_key_exists('Sales Representative '.$key, $this->data)) {
                    return $this->data[$this->table_name.' '.$key];
                }
        }

        return false;
    }

    function get_field_label($field) {

        switch ($field) {

            case 'Sales Representative Name':
                $label = _('name');
                break;

            default:
                $label = $field;

        }

        return $label;

    }

    function activate() {

        $this->update_field_switcher('Sales Representative Status', 'Active');

    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        switch ($field) {


            default:
                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {
                    if ($value != $this->data[$field]) {

                        $this->update_field($field, $value, $options);
                    }
                }

        }
    }

    function update_customers_data() {

        return;
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
                'Sales Representative Scheduled' => $scheduled,
                'Sales Representative Sent'      => $sent,
                'Sales Representative Delivered' => $delivered,

                'Sales Representative Open'    => $open,
                'Sales Representative Clicked' => $clicked,

                'Sales Representative Errors'       => $errors,
                'Sales Representative Hard Bounces' => $hard_bounces,
                'Sales Representative Soft Bounces' => $soft_bounces,
                'Sales Representative Spams'        => $spam,
                'Sales Representative Unsubscribed' => $unsubscribed,
                'Sales Representative Mailshots'    => $mailshots


            )

        );


    }


}


?>
