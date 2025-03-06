<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 December 2016 at 13:26:41 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'class.DBW_Table.php';
include_once 'trait.WebsiteUserAiku.php';

class Public_Website_User extends DBW_Table {
    use WebsiteUserAiku;

    function __construct($a1 = 'id', $a2 = false, $a3 = false) {
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

        $this->get_data($key, $_data);

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
            "SELECT count(*) AS num  FROM `Website User Dimension` WHERE `Website User Handle`=%s AND `Website User Website Key`=%d ", prepare_mysql($base_data['Website User Handle']), $base_data['Website User Website Key']
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                if ($row['num'] > 0) {
                    $this->error = true;
                    $this->msg   = 'Duplicate user login';

                    return;
                }
            }
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
            $this->fast_update(
                [
                    'Website User Static API Hash' => md5(DNS_ACCOUNT_CODE.'.'.$this->id.'.'.SKEY.microtime())
                ]
            );

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
            $this->model_updated('new',$this->id);

            return $this;
        } else {
            $this->error = true;
            $this->msg   = _('Unknown error').' (2)';

            return false;
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
            return false;
        }


        switch ($key) {
            case 'Website User Customer Key':
            case 'Website User Handle':
            case 'Website User Static API Hash':
                return $this->data[$key];
                break;

            default:


        }

        return false;
    }


    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        if (is_string($value)) {
            $value = _trim($value);
        }

        switch ($field) {
            case 'Website User Password':
            case 'Website User Password Hash':

                $this->update_field($field, $value, 'no_history');
                break;


                break;

            case 'Website User Handle':

                $this->update_field($field, $value, $options);
                break;


            default:


        }

    }


    function get_field_label($field) {


        switch ($field) {

            case 'Website User Password':
                $label = _('password');
                break;

            default:
                $label = $field;

        }

        return $label;

    }

}



