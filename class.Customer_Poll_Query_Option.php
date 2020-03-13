<?php
/*
 
 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 13 February 2018 at 19:51:28 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 2.0
*/

include_once 'class.DB_Table.php';


class Customer_Poll_Query_Option extends DB_Table {

    var $deleted = false;

    function __construct($a1, $a2 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Customer Poll Query Option';
        $this->ignore_fields = array('Customer Poll Query Option Key');


        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } elseif ($a1 == 'new') {
            $this->create($a2);
        } else {
            $this->get_data($a1, $a2);
        }
    }


    function get_data($key, $tag) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Customer Poll Query Option Dimension` WHERE `Customer Poll Query Option Key`=%d", $tag
            );
        } elseif ($key == 'deleted') {
            $this->get_deleted_data($tag);

            return false;
        }else{
            return false;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Customer Poll Query Option Key'];
        }

        return true;
    }


    function get_deleted_data($tag) {

        $this->deleted = true;
        $sql           = sprintf(
            "SELECT * FROM `Customer Poll Query Option Deleted Dimension` WHERE `Customer Poll Query Option Deleted Key`=%d", $tag
        );

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id     = $this->data['Customer Poll Query Option Deleted Key'];
            $deleted_data = json_decode(
                gzuncompress($this->data['Customer Poll Query Option Deleted Metadata']), true
            );

            unset($this->data['Customer Poll Query Option Deleted Metadata']);

            foreach ($deleted_data as $key => $value) {
                $this->data[$key] = $value;
            }
        }
    }


    function create($data) {


        $this->new    = false;
        $this->editor = $data['editor'];

        $base_data = $this->base_data();

        foreach ($data as $key => $value) {
            if (array_key_exists($key, $base_data)) {
                $base_data[$key] = _trim($value);
            }
        }



        $sql = sprintf(
            "INSERT INTO `Customer Poll Query Option Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($base_data)).'`', join(',', array_fill(0, count($base_data), '?'))
        );

        $stmt = $this->db->prepare($sql);


        $i = 1;
        foreach ($base_data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {

            $this->id  = $this->db->lastInsertId();
            $this->msg = _("Option for customer poll created");
            $this->get_data('id', $this->id);
            $this->new = true;


            $history_data = array(
                'History Abstract' => _("Option for customer poll created"),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );

            $this->update_website();


            return;
        } else {
            $this->msg = "Error can not create poll query option";
            print $sql;
            exit;
        }
    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        if ($this->deleted) {
            return;
        }

        switch ($field) {
            default:
                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {
                    if ($value != $this->data[$field]) {
                        $this->update_field($field, $value, $options);
                    }
                }


        }


     $this->update_website();



    }



    function update_website(){
        $store=get_object('Public_Store',$this->data['Customer Poll Query Option Store Key']);
        $website=get_object('Website',$store->get('Store Website Key'));

        /**
         * @var $registration_webpage \Page
         */
        $registration_webpage=$website->get_webpage('register.sys');

        $registration_webpage->reindex_items();
    }

    function get_field_label($field) {

        switch ($field) {


            case 'Customer Poll Query Option Name':
                $label = _('answer code');
                break;
            case 'Customer Poll Query Option Label':
                $label = _('answer');
                break;


            default:

                $label = $field;

        }

        return $label;

    }

    function update_poll_query_option_customers() {

        $number_customers = 0;
        $last_answered    = '';


        $sql = "SELECT count(DISTINCT `Customer Poll Customer Key`) AS number , max(`Customer Poll Date`)  last_answered FROM `Customer Poll Fact` WHERE `Customer Poll Query Option Key`=?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        if ($row = $stmt->fetch()) {

            $number_customers = $row['number'];
            $last_answered    = $row['last_answered'];
            }

        $this->fast_update(
            array(
                'Customer Poll Query Option Customers'     => $number_customers,
                'Customer Poll Query Option Last Answered' => $last_answered

            )
        );


    }

    function delete() {

        $replies = array();


        $sql = sprintf('SELECT `Customer Poll Customer Key` FROM `Customer Poll Fact` WHERE `Customer Poll Query Option Key`=%d ', $this->id);

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $replies[] = $row['Customer Poll Customer Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $data = $this->data;
        unset($data['Customer Poll Query Option Key']);
        unset($data['Customer Poll Query Option Name']);
        unset($data['Customer Poll Query Option Label']);
        $data['Replies'] = $replies;


        $sql = sprintf(
            'INSERT INTO `Customer Poll Query Option Deleted Dimension`  (`Customer Poll Query Option Deleted Key`,`Customer Poll Query Option Deleted Date`,`Customer Poll Query Option Deleted Name`,`Customer Poll Query Option Deleted Label`,`Customer Poll Query Option Deleted Metadata`) VALUES (%d,%s,%s,%s,%s) ',
            $this->id, prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql($this->get('Customer Poll Query Option Name')), prepare_mysql($this->get('Customer Poll Query Option Label')), prepare_mysql(gzcompress(json_encode($data), 9))


        );

        $this->db->exec($sql);


        $sql = sprintf('DELETE FROM `Customer Poll Fact` WHERE `Customer Poll Query Option Key`=%d ', $this->id);
        $this->db->exec($sql);


        $sql = sprintf(
            'DELETE FROM `Customer Poll Query Option Dimension`  WHERE `Customer Poll Query Option Key`=%d ', $this->id
        );
        $this->db->exec($sql);

        //  print $sql;


        $history_data = array(
            'History Abstract' => sprintf(
                _('Customer poll %s deleted'), '<b>'.$this->data['Customer Poll Query Option Name'].'</b>'
            ),
            'History Details'  => '',
            'Action'           => 'deleted'
        );

        $this->add_subject_history(
            $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
        );


        $this->deleted = true;


        $poll_query = get_object('Customer_Poll_Query', $this->data['Customer Poll Query Option Query Key']);
        $poll_query->update_answers();

        $this->update_website();


    }

    function get($key, $data = false) {

        if (!$this->id ) {
            return '';
        }


        switch ($key) {


            default:


                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Customer Poll Query Option '.$key, $this->data)) {
                    return $this->data['Customer Poll Query Option '.$key];
                }


        }

        return '';
    }

}


