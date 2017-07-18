<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 July 2017 at 16:00:57 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'class.DB_Table.php';

class Website_User extends DB_Table {


    function Website_User($a1 = 'id', $a2 = false, $a3 = false) {
        global $db;
        $this->db = $db;

        $this->table_name = 'Website User';


        if (($a1 == 'new') and is_array($a2)) {
            $this->create($a2);

            return;
        }


        if (is_numeric($a1) and !$a2) {
            $_data = $a1;
            $key   = 'id';
        } else {
            $_data = $a2;
            $key   = $a1;
        }

        $this->get_data($key, $_data, $a3);

        return;
    }

    function create($data) {


        $this->new = false;
        $this->msg = _('Unknown Error').' (0)';
        $base_data = $this->base_data();

        foreach ($data as $key => $value) {
            if (array_key_exists($key, $base_data)) {
                $base_data[$key] = _trim($value);
            }
        }

        $this->editor = $data['editor'];


        if ($base_data['Website User Handle'] == '') {
            $this->msg = "Login can't be empty";

            return;
        }


        $sql = sprintf(
            "SELECT count(*) AS num  FROM `Website User Dimension` WHERE `Website User Handle`=%s AND `Website User Website Key`=%d ", prepare_mysql($base_data['Website User Handle']),
            $base_data['Website User Website Key']
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                if ($row['num'] > 0) {
                    $this->error = true;
                    $this->msg   = 'Duplicate user login';

                    return;
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        $base_data['Website User Created'] = gmdate("Y-m-d H:i:s");

        $base_data['Website User Password Hash'] = password_hash($base_data['Website User Password'], PASSWORD_DEFAULT, array('cost' => 12));


        $keys   = '(';
        $values = 'values(';
        foreach ($base_data as $key => $value) {
            $keys   .= "`$key`,";
            $values .= prepare_mysql($value).",";

        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);
        $sql    = sprintf("INSERT INTO `Website User Dimension` %s %s", $keys, $values);


        if ($this->db->exec($sql)) {

            $user_key = $this->db->lastInsertId();
            $this->get_data('id', $user_key);

            $sql = sprintf("INSERT INTO `Website User Data` (`Website User Key`) VALUES (%d)", $user_key);
            $this->db->exec($sql);

            $this->new = true;


            $history_data = array(
                'History Abstract' => sprintf(_('Website user %s created'), $this->get('Handle')),
                'History Details'  => '',
                'Action'           => 'created',
                'Subject'          => 'Customer',
                'Subject Key'      => $this->data['Website User Customer Key'],
                'Author Name'      => _('Customer')

            );


            $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id);

            $this->msg = 'User added successfully';


            return $this;
        } else {

            print $sql;

            $this->error = true;
            $this->msg   = 'Unknown error 2';

            return;
        }


    }

    function get_data($type, $key) {


        if ($type == 'handle') {
            $sql = sprintf(
                "SELECT * FROM  `Website User Dimension` WHERE `Website User Handle`=%s ", prepare_mysql($key)
            );
        } else {
            $sql = sprintf(
                "SELECT * FROM `Website User Dimension` WHERE `Website User Key`=%d", $key
            );
        }


        if ($this->data = $this->db->query($sql)->fetch()) {


            $this->id                            = $this->data['Website User Key'];
            $this->data['Website User Password'] = '';


        }


    }

    function get($key) {


        if (!$this->id) {
            return;
        }


        switch ($key) {


            default:

                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Website User '.$key, $this->data)) {
                    return $this->data['Website User '.$key];
                }


        }

    }


    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        if (is_string($value)) {
            $value = _trim($value);
        }

        switch ($field) {


            default:


        }

    }


    function delete() {

        $sql = sprintf('DELETE FROM `Website User Dimension` WHERE `Website User Dimension`=%d ', $this->id);
        $this->db->exec($sql);

        $sql = sprintf(
            "INSERT INTO `Website User Deleted Dimension` (`Website User Deleted Key`,`Website User Deleted Handle`,`Website User Deleted Customer Key`,`Website User Deleted Website Key`,`Website User Deleted Date`) VALUE (%d,%s,%d,%d,%s) ",
            $this->id, prepare_mysql($this->data['Website User Handle']), $this->data['Website User Customer Key'], $this->data['Website User Website Key'], prepare_mysql(gmdate('Y-m-d H:i:s'))

        );


        $this->db->exec($sql);
        $website = get_object('Website', $this->data['Website User Website Key']);

        $website->update_customers_data();


    }


}


?>
