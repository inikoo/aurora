<?php
/*
 File: User.php

 This file contains the User Class

 About:
 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';


class User extends DB_Table {


    private $groups_read = false;
    private $rights_read = false;


    function __construct($a1 = 'id', $a2 = false, $a3 = false) {
        global $db;
        $this->db = $db;

        $this->table_name    = 'User';
        $this->ignore_fields = array(
            'User Key',
            'User Last Login'
        );

        if (($a1 == 'new') and is_array($a2)) {
            $this->find($a2, 'create');

            return;
        }

        if (($a1 == 'find') and is_array($a2)) {
            $this->find($a2, $a3);

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


    function find($raw_data, $options = '') {
        if (isset($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {

                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }

            }
        }

        if (preg_match('/create/i', $options)) {
            $create = true;
        } else {
            $create = false;
        }


        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = _trim($value);
            }
        }


        $sql = sprintf(
            "SELECT `User Key` FROM `User Dimension` WHERE `User Type`=%s AND `User Handle`=%s ", prepare_mysql($data['User Type']), prepare_mysql($data['User Handle'])
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->found     = true;
                $this->found_key = $row['User Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if ($this->found) {
            $this->get_data('id', $this->found_key);

        }


        if (!$this->found and $create) {
            $this->create($raw_data);
        }


    }


    function get_data($key, $data, $data2 = 'Staff') {

        if ($key == 'handle') {
            $sql = sprintf(
                "SELECT * FROM  `User Dimension` WHERE `User Handle`=%s AND `User Type`=%s", prepare_mysql($data), prepare_mysql($data2)
            );
        } elseif ($key == 'Administrator') {
            $sql = sprintf(
                "SELECT * FROM  `User Dimension` WHERE  `User Type`='Administrator'"

            );
        } elseif ($key == 'Warehouse') {
            $sql = sprintf(
                "SELECT * FROM  `User Dimension` WHERE  `User Type`='Warehouse'"

            );
        } elseif ($key == 'deleted') {
            $this->get_deleted_data($data);

            return;
        } else {
            $sql = sprintf(
                "SELECT * FROM `User Dimension` WHERE `User Key`=%d", $data
            );
        }

        if ($this->data = $this->db->query($sql)->fetch()) {


            $this->id                    = $this->data['User Key'];
            $this->data['User Password'] = '';

            //print $this->data['User Type'];

            if ($this->data['User Type'] == 'Staff' or $this->data['User Type'] == 'Contractor' or $this->data['User Type'] == 'Administrator' or $this->data['User Type'] == 'Warehouse') {

                $sql = sprintf(
                    "SELECT * FROM `User Staff Settings Dimension` WHERE `User Key`=%d", $this->id
                );

                //print $sql;


                if ($row = $this->db->query($sql)->fetch()) {


                    $this->data = array_merge($this->data, $row);
                }
            }
        }


    }


    function get_deleted_data($tag) {

        $this->deleted = true;
        $sql           = sprintf(
            "SELECT * FROM `User Deleted Dimension` WHERE `User Deleted Key`=%d", $tag
        );

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id     = $this->data['User Deleted Key'];
            $deleted_data = json_decode(
                gzuncompress($this->data['User Deleted Metadata']), true
            );
            foreach ($deleted_data['data'] as $key => $value) {
                $this->data[$key] = $value;
            }
        }
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
        unset($data['editor']);

        if ($base_data['User Created'] == '') {
            $base_data['User Created'] = gmdate("Y-m-d H:i:s");
        }

        if ($base_data['User Handle'] == '') {
            $this->msg = _("Login can't be empty");

            return;
        }
        if (strlen($base_data['User Handle']) < 4) {
            $this->msg   = _('Login too short');
            $this->error = true;

            return;
        }


        $sql = sprintf(
            "SELECT count(*) AS numh  FROM `User Dimension` WHERE `User Type`=%s AND `User Handle`=%s ", prepare_mysql($base_data['User Type']), prepare_mysql($base_data['User Handle'])
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                if ($row['numh'] > 0) {
                    $this->error = true;
                    $this->msg   = _('Duplicate user login');

                    return;
                }
            } else {
                $this->error = true;
                $this->msg   = _('Unknown error');

                return;

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if ($base_data['User Type'] == 'Staff') {

            $sql = sprintf(
                "SELECT `User Handle`  FROM `User Dimension` WHERE `User Type`='Staff' AND `User Parent Key`=%d", $data['User Parent Key']
            );

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $this->msg = _('Employee has already a user set up');

                    return;
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


        }

        if ($base_data['User Type'] == 'Administrator') {
            $base_data['User Alias'] = _('Administrator');
        }


        $keys   = '(';
        $values = 'values(';
        foreach ($base_data as $key => $value) {
            $keys .= "`$key`,";
            if ($key == 'User Inactive Note') {
                $values .= prepare_mysql($value, false).",";
            } else {
                $values .= prepare_mysql($value).",";
            }
        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);
        $sql    = sprintf("INSERT INTO `User Dimension` %s %s", $keys, $values);


        if ($this->db->exec($sql)) {

            $user_id = $this->db->lastInsertId();
            $this->get_data('id', $user_id);

            $this->fast_update(array('User Settings' => '{}'));

            $this->new = true;

            if ($this->get('User Type') == 'Administrator') {

                $this->update(array('User Parent Key' => $this->id), 'no_history');

                $history_data = array(
                    'History Abstract' => sprintf(_('%s user record created'), $this->get('Handle')),
                    'History Details'  => '',
                    'Action'           => 'created',
                    'Subject'          => 'Administrator',
                    'Subject Key'      => $this->id,
                    'Author Name'      => 'Aurora'
                );

            } else {
                $history_data = array(
                    'History Abstract' => sprintf(_('%s user record created'), $this->get('Handle')),
                    'History Details'  => '',
                    'Action'           => 'created',

                );

            }


            $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id);

            $this->msg = _('User added successfully');


            $this->update_staff_type();

            if ($this->data['User Type'] == 'Staff' or $this->data['User Type'] == 'Contractor' or $this->data['User Type'] == 'Administrator' or $this->data['User Type'] == 'Warehouse') {


                $sql = sprintf(
                    "INSERT INTO `User Staff Settings Dimension` (`User Key`) VALUES (%d)  ", $this->id
                );
                $this->db->exec($sql);
                $this->get_data('id', $this->id);

                if (isset($data['User Permissions'])) {
                    $this->update_permissions($data['User Permissions'], 'no_history');
                }

            }


            return $this;
        } else {
            $this->error = true;
            $this->msg   = _('Unknown error').' (2)';

            return;
        }


        $this->get_data('id', $user_id);


    }

    /**
     * @param $key
     *
     * @return mixed
     */
    function get($key) {

        global $account;

        if (!$this->id) {
            return;
        }


        switch ($key) {
            case('theme_raw'):
                if (empty(json_decode($this->data['User Settings'], true))) {
                    return 'app_theme_default';
                } else {
                    return json_decode($this->data['User Settings'])->theme;
                }
                break;

            case 'User Groups':
                return $this->get_groups();
                break;

            case 'Groups':
                return $this->get_groups_formatted();
                break;
            case 'User Stores':

                $stores = array();
                $sql    = sprintf(
                    "SELECT GROUP_CONCAT(`Scope Key`) AS stores  FROM `User Right Scope Bridge` WHERE `User Key`=%d AND `Scope`='Store'", $this->id
                );
                if ($row = $this->db->query($sql)->fetch()) {
                    $stores = $row['stores'];
                }

                return $stores;

                break;
            case 'Stores':
                $number_stores = $this->get_number_stores();

                if ($number_stores == 0) {
                    $stores = '<span class="none very_discreet" >'._('none').'</span>';
                } elseif ($number_stores == $account->get('Stores') and $number_stores > 20) {
                    $stores = '<span class="all" > '._('all').'</span>';
                } else {

                    $stores = array();
                    $sql    = sprintf(
                        "SELECT `Scope Key`,`Store Code`,`Store Name` AS `key` FROM `User Right Scope Bridge`  LEFT JOIN `Store Dimension` ON (`Store Key`=`Scope Key`) WHERE `User Key`=%d AND `Scope`='Store'", $this->id
                    );
                    foreach ($this->db->query($sql) as $row) {

                        $stores[] = $row['Store Code'];
                    }

                    $stores = join($stores, ', ');
                }


                return $stores.' <span class="very_discreet italic">('.$number_stores.'/'.$account->get('Stores').')</span>';

                break;


            case 'User Productions':

                $productions = array();
                $sql         = sprintf(
                    "SELECT GROUP_CONCAT(`Scope Key`) AS productions  FROM `User Right Scope Bridge` WHERE `User Key`=%d AND `Scope`='Production'", $this->id
                );
                if ($row = $this->db->query($sql)->fetch()) {
                    $productions = $row['productions'];
                }

                return $productions;

                break;
            case 'Productions':
                $number_productions = $this->get_number_productions();

                if ($number_productions == 0) {
                    $productions = '<span class="none very_discreet italic" > '._('none').'</span>';
                } elseif ($number_productions == $account->get('Productions') and $number_productions > 20) {
                    $productions = '<span class="all" > '._('all').'</span>';
                } else {

                    $productions = array();
                    $sql         = sprintf(
                        "SELECT `Scope Key`,`Supplier Code`,`Supplier Name` AS `key` FROM `User Right Scope Bridge`  LEFT JOIN `Supplier Dimension` ON (`Supplier Key`=`Scope Key`) WHERE `User Key`=%d AND `Scope`='Production'", $this->id
                    );
                    foreach ($this->db->query($sql) as $row) {

                        $productions[] = $row['Supplier Code'];
                    }
                    $productions = join($productions, ', ');
                }

                return $productions.' <span class="very_discreet italic">('.$number_productions.'/'.$account->get('Productions').')</span>';

                break;

            case 'User Websites':
                $websites = array();
                $sql      = sprintf(
                    "SELECT GROUP_CONCAT(`Scope Key`) AS websites  FROM `User Right Scope Bridge` WHERE `User Key`=%d AND `Scope`='Website'", $this->id
                );
                if ($row = $this->db->query($sql)->fetch()) {
                    $websites = $row['websites'];
                }

                return $websites;
                break;
            case 'Websites':
                $number_websites = $this->get_number_websites();

                if ($number_websites == 0) {
                    $websites = '<span class="none very_discreet" > '._('none').'</span>';
                } elseif ($number_websites == $account->get('Websites')) {
                    $websites = '<span class="all" > '._('all').'</span>';
                } else {

                    $websites = array();
                    $sql      = sprintf(
                        "SELECT `Scope Key`,`Website Code`,`Website Name` AS `key` FROM `User Right Scope Bridge`  LEFT JOIN `Website Dimension` ON (`Website Key`=`Scope Key`) WHERE `User Key`=%d AND `Scope`='Website'", $this->id
                    );

                    foreach ($this->db->query($sql) as $row) {
                        if ($row['Website Code'] != '') {
                            $websites[] = $row['Website Code'];
                        }
                    }
                    $websites = join($websites, ', ');
                }

                return $websites.' <span class="very_discreet italic">('.$number_websites.'/'.$account->get('Websites').')</span>';
                break;
            case 'User Warehouses':
                $warehouses = array();
                $sql        = sprintf(
                    "SELECT GROUP_CONCAT(`Scope Key`) AS warehouses  FROM `User Right Scope Bridge` WHERE `User Key`=%d AND `Scope`='Warehouse'", $this->id
                );
                if ($row = $this->db->query($sql)->fetch()) {
                    $warehouses = $row['warehouses'];
                }

                return $warehouses;
                break;
            case 'Warehouses':
                $number_warehouses = $this->get_number_warehouses();
                if ($number_warehouses == 0) {
                    $warehouses = '<span class="none very_discreet" > '._('none').'</span>';
                } elseif ($number_warehouses == $account->get('Warehouses') and $number_warehouses > 20) {
                    $warehouses = '<span class="all" ><i class="fa fa-toggle-on"></i> '._('all').'</span>';
                } else {

                    $warehouses = array();
                    $sql        = sprintf(
                        "SELECT `Scope Key`,`Warehouse Code`,`Warehouse Name` AS `key` FROM `User Right Scope Bridge`  LEFT JOIN `Warehouse Dimension` ON (`Warehouse Key`=`Scope Key`) WHERE `User Key`=%d AND `Scope`='Warehouse'", $this->id
                    );

                    foreach ($this->db->query($sql) as $row) {
                        if ($row['Warehouse Code'] != '') {
                            $warehouses[] = $row['Warehouse Code'];
                        }
                    }
                    $warehouses = join($warehouses, ', ');
                }

                return $warehouses.' <span class="very_discreet italic">('.$number_warehouses.'/'.$account->get('Warehouses').')</span>';

                break;


            case('User Password'):
            case('User PIN'):
                return '';
                break;
            case('Password'):
                return '******';
                break;
            case('PIN'):
                return '****';
                break;

            case('Preferred Locale'):


                include 'utils/available_locales.php';

                if (array_key_exists(
                    $this->data['User Preferred Locale'], $available_locales
                )) {
                    $locale = $available_locales[$this->data['User Preferred Locale']];

                    return $locale['Language Name'].($locale['Language Name'] != $locale['Language Original Name'] ? ' ('.$locale['Language Original Name'].')' : '');
                } else {

                    return $this->data['User Preferred Locale'];
                }
                break;

            case('Active'):

                switch ($this->data['User Active']) {
                    case('Yes'):
                        $formatted_value = _('Yes');
                        break;
                    case('No'):
                        $formatted_value = _('No');
                        break;

                    default:
                        $formatted_value = $this->data['User Active'];
                }

                return $formatted_value;

                break;


            case('Login Count'):
            case('Failed Login Count'):
                return number($this->data['User '.$key]);
                break;

            case('Created '):
            case('Last Failed Login'):
            case('Last Login'):
                if ($this->data ['User '.$key] == '' or $this->data ['User '.$key] == '0000-00-00 00:00:00') {
                    return '';
                } else {
                    return strftime(
                        "%a %e %b %Y %H:%M %Z", strtotime($this->data ['User '.$key]." +00:00")
                    );
                }
                break;

            case('isactive'):
                return $this->data['Is Active'];
                break;
            case('groups'):
                return $this->data['groups'];
                break;

            case('Staff Position'):
            case('Position'):
                include_once 'class.Staff.php';
                $employee = get_object('Staff', $this->get_staff_key());

                return $employee->get($key);
                break;
            default:
                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('User '.$key, $this->data)) {
                    return $this->data['User '.$key];
                }

        }

    }

    function get_groups() {
        $this->groups = array();
        $sql          = sprintf(
            "SELECT GROUP_CONCAT(`User Group Key`) AS groups FROM `User Group User Bridge` UGUB  WHERE UGUB.`User Key`=%d", $this->id
        );
        if ($row = $this->db->query($sql)->fetch()) {
            $groups       = $row['groups'];
            $this->groups = preg_split('/,/', $groups);
        }

        return $this->groups;
    }


    function get_groups_formatted() {

        $number_groups = $this->get_number_groups();

        if ($number_groups == 0) {
            return '<span class="none" ><i class="fa fa-toggle-off"></i> '._(
                    'none'
                ).'</span>';
        }
        if ($number_groups == 12) {
            return '<span class="all" ><i class="fa fa-toggle-on"></i> '._(
                    'all'
                ).'</span>';
        } else {

            include 'conf/user_groups.php';
            $groups = array();
            $sql    = sprintf(
                "SELECT `User Group Key` AS `key` FROM `User Group User Bridge` UGUB  WHERE UGUB.`User Key`=%d", $this->id
            );
            foreach ($this->db->query($sql) as $row) {
                if (isset($user_groups[$row['key']])) {
                    $groups[] = $user_groups[$row['key']]['Name'];
                }
            }

            return join($groups, ', ');
        }
    }

    function get_number_groups() {
        $number_groups = 0;
        $sql           = sprintf(
            "SELECT count(*) AS groups FROM `User Group User Bridge` UGUB  WHERE UGUB.`User Key`=%d", $this->id
        );
        if ($row = $this->db->query($sql)->fetch()) {
            $number_groups = $row['groups'];
        }

        return $number_groups;
    }

    function get_number_stores() {
        $number_stores = 0;
        $sql           = sprintf(
            "SELECT count(*) AS num FROM `User Right Scope Bridge` WHERE `User Key`=%d AND `Scope`='Store'", $this->id
        );
        if ($row = $this->db->query($sql)->fetch()) {
            $number_stores = $row['num'];
        }

        return $number_stores;
    }

    function get_number_productions() {
        $number_productions = 0;
        $sql                = sprintf(
            "SELECT count(*) AS num FROM `User Right Scope Bridge` WHERE `User Key`=%d AND `Scope`='Production'", $this->id
        );
        if ($row = $this->db->query($sql)->fetch()) {
            $number_productions = $row['num'];
        }

        return $number_productions;
    }

    function get_number_websites() {
        $number_websites = 0;
        $sql             = sprintf(
            "SELECT count(*) AS websites FROM `User Right Scope Bridge` WHERE `User Key`=%d AND `Scope`='Website'", $this->id
        );
        if ($row = $this->db->query($sql)->fetch()) {
            $number_websites = $row['websites'];
        }

        return $number_websites;
    }

    function get_number_warehouses() {
        $number_warehouses = 0;
        $sql               = sprintf(
            "SELECT count(*) AS warehouses FROM `User Right Scope Bridge` WHERE `User Key`=%d AND `Scope`='Warehouse'", $this->id
        );
        if ($row = $this->db->query($sql)->fetch()) {
            $number_warehouses = $row['warehouses'];
        }

        return $number_warehouses;
    }

    function get_staff_key() {

        if (!$this->id) {
            return 0;
        }

        if ($this->data['User Type'] == 'Staff' or $this->data['User Type'] == 'Contractor') {
            $staff_key = $this->data['User Parent Key'];
        } else {
            $staff_key = 0;
        }

        return $staff_key;
    }

    function update_staff_type() {

        if ($this->data['User Type'] != 'Staff') {
            $this->data['User Staff Type'] = '';

        } else {

            $staff = get_object('Staff', $this->data['User Parent Key']);
            if ($staff->data['Staff Currently Working'] == 'Yes') {
                $this->data['User Staff Type'] = 'Working';


            } else {
                $this->data['User Staff Type'] = 'NotWorking';

            }


        }
        $sql = sprintf(
            "UPDATE `User Dimension` SET `User Staff Type`=%s WHERE `User Key`=%d", prepare_mysql($this->data['User Staff Type']), $this->id
        );
        $this->db->exec($sql);
    }


    function update_permissions($value) {

        $value = json_decode($value, true);


        $groups = $value['user_groups'];
        foreach ($groups as $key => $_value) {
            if (!is_numeric($_value)) {
                unset($groups[$key]);
            }
        }


        $this->read_groups();
        $old_groups = $this->groups_key_array;

        $to_delete = array_diff($old_groups, $groups);
        $to_add    = array_diff($groups, $old_groups);


        $changed = 0;
        if (count($to_delete) > 0) {
            $changed += $this->delete_group($to_delete);

        }
        if (count($to_add) > 0) {
            $changed += $this->add_group($to_add);

        }
        $this->read_groups();

        if ($changed > 0) {
            $this->updated   = true;
            $this->new_value = array(
                'groups' => $this->groups_key_array
            );
        }


        //todo fix it if you offer multi warehouse or multi productions
        $this->update_stores($value['stores']);

        // todo here you can put the supplier production if you want
        if (in_array(7, $groups) or in_array(4, $groups)) {

        }
        $warehouses = array();


        if (in_array(3, $groups) or in_array(22, $groups) or in_array(27, $groups) or in_array(18, $groups) or in_array(9, $groups)) {

            $sql = sprintf('select `Warehouse Key` from `Warehouse Dimension`');

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $warehouses[] = $row['Warehouse Key'];
            }

        }

        $this->update_warehouses($warehouses, false);
        $this->update_rights();
    }

    function update_rights() {

        $rights = array();
        include 'conf/user_groups.php';


        foreach ($this->get_groups() as $group_key) {


            if (isset($user_groups[$group_key])) {
                $rights = array_merge($rights, $user_groups[$group_key]['Rights']);

            } else {


                $sql  = 'delete  FROM `User Group User Bridge`   WHERE `User Group Key`=:key';
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':key', $group_key, PDO::PARAM_INT);
                $stmt->execute();

            }

        }
        $sql  = 'delete FROM `User Rights Bridge`  WHERE `User Key`=:user_key';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_key', $this->id, PDO::PARAM_INT);
        $stmt->execute();

        if ($this->data['User Active'] == 'Yes') {
            foreach ($rights as $right_code) {
                $sql  = 'insert into `User Rights Bridge` (`User Key`,`Right Code`) values (:user_key,:right_code) ';
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':user_key', $this->id, PDO::PARAM_INT);
                $stmt->bindParam(':right_code', $right_code, PDO::PARAM_STR);

                $stmt->execute();
            }
        }


    }


    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        if (is_string($value)) {
            $value = _trim($value);
        }

        switch ($field) {
            case('Permissions'):
                $this->update_permissions($value);

                break;
            case('theme'):
                $this->fast_update_json_field('User Settings', $field, $value);

                break;

            case('theme'):
                $this->fast_update_json_field('User Settings', $field, $value);

                break;


            case('User Active'):
                $this->update_active($value);
                break;
            case('User Password'):
                $this->update_password($value, $options);
                break;
            case('User PIN'):
                $this->update_pin($value, $options);
                break;


            case('User Handle'):
                $old_value = $this->get('Handle');
                $this->update_field($field, $value, $options);
                switch ($this->data['User Type']) {
                    case 'Staff':

                        $staff         = get_object('Staff', $this->data['User Parent Key']);
                        $staff->editor = $this->editor;
                        $staff->get_user_data();
                        $new_value = $this->get('Handle');
                        $staff->add_changelog_record(
                            'Staff User Handle', $old_value, $new_value, '', $staff->table_name, $staff->id
                        );

                        break;
                    default:
                        return;
                        break;
                }


                break;
            default:
                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {

                    $this->update_field($field, $value, $options);
                } elseif (array_key_exists(
                    $field, $this->base_data('User Staff Settings Dimension')
                )) {
                    $this->update_table_field(
                        $field, $value, $options, 'User', 'User Staff Settings Dimension', $this->id
                    );
                }


        }

    }


    function read_groups() {

        include 'conf/user_groups.php';
        $this->groups           = array();
        $this->groups_key_list  = '';
        $this->groups_key_array = array();


        $sql = sprintf(
            "SELECT `User Group Key` FROM `User Group User Bridge`  WHERE  `User Key`=%d", $this->id
        );
        //print $sql;
        //exit;
        if ($result = $this->db->query($sql)) {

            foreach ($result as $row) {

                if (isset($user_groups[$row['User Group Key']])) {

                    $this->groups[$row['User Group Key']] = array('User Group Name' => $user_groups[$row['User Group Key']]['Name']);
                    $this->groups_key_list                .= ','.$row['User Group Key'];
                    $this->groups_key_array[]             = $row['User Group Key'];
                }
            }
        }


        $this->groups_key_list = preg_replace(
            '/^,/', '', $this->groups_key_list
        );


        $this->groups_read = true;
    }

    function delete_group($to_delete, $history = true) {

        include 'conf/user_groups.php';

        $changed = 0;
        foreach ($to_delete as $group_key) {

            $sql      = sprintf(
                "DELETE FROM `User Group User Bridge` WHERE `User Key`=%d AND `User Group Key`=%d ", $this->id, $group_key
            );
            $_changed = $this->db->exec($sql);

            if ($_changed > 0) {
                $changed++;


                $history_data = array(
                    'History Abstract'    => sprintf(
                        _("User removed from group %s"), $user_groups[$group_key]['Name']
                    ),
                    'History Details'     => '',
                    'Action'              => 'disassociate',
                    'Indirect Object'     => 'User Group',
                    'Indirect Object Key' => $group_key
                );
                $history_key  = $this->add_history($history_data);
                $sql          = sprintf(
                    "INSERT INTO `%s History Bridge` VALUES (%d,%d,'No','No','Changes')", $this->table_name, $this->id, $history_key
                );
                $this->db->exec($sql);


            }
        }


        return $changed;
    }

    function add_group($to_add, $history = true) {

        include 'conf/user_groups.php';

        $changed = 0;
        foreach ($to_add as $group_key) {

            if (array_key_exists($group_key, $user_groups)) {
                $group_name = $user_groups[$group_key]['Name'];


                $sql      = sprintf(
                    "INSERT INTO `User Group User Bridge`VALUES (%d,%d) ", $this->id, $group_key
                );
                $_changed = $this->db->exec($sql);
                if ($_changed > 0) {
                    $changed++;

                    $history_data = array(
                        'History Abstract'    => sprintf(
                            _("User added to group %s"), $user_groups[$group_key]['Name']
                        ),
                        'History Details'     => '',
                        'Action'              => 'associate',
                        'Indirect Object'     => 'User Group',
                        'Indirect Object Key' => $group_key
                    );
                    $history_key  = $this->add_history($history_data);
                    $sql          = sprintf(
                        "INSERT INTO `%s History Bridge` VALUES (%d,%d,'No','No','Changes')", $this->table_name, $this->id, $history_key
                    );
                    $this->db->exec($sql);

                }

            }

        }

        return $changed;
    }


    function has_scope($scope) {


        $groups = $this->get_groups();
        if (count($groups) > 0) {
            include 'conf/user_groups.php';

            foreach ($groups as $group_key) {
                if (isset($user_groups[$group_key][$scope.'_Scope']) and $user_groups[$group_key][$scope.'_Scope']) {
                    return true;
                }
            }


        }

        return false;


    }

    function update_stores($stores) {

        $this->updated = false;

        if (!($this->data['User Type'] == 'Staff' or $this->data['User Type'] == 'Contractor')) {
            $this->error = true;

            return;
        }
        foreach ($stores as $key => $_value) {
            if (!is_numeric($_value)) {
                unset($stores[$key]);
            }
        }
        $old_stores = preg_split('/,/', $this->get('User Stores'));

        $to_delete = array_diff($old_stores, $stores);
        $to_add    = array_diff($stores, $old_stores);
        $changed   = 0;


        if (count($to_delete) > 0) {
            $changed += $this->delete_store($to_delete);
        }
        if (count($to_add) > 0) {
            $changed += $this->add_store($to_add);
        }

        if ($changed > 0) {
            $this->updated = true;
        }

    }

    function delete_store($to_delete, $history = true) {

        $changed = 0;
        foreach ($to_delete as $store_key) {

            $sql      = sprintf(
                "DELETE FROM `User Right Scope Bridge` WHERE `User Key`=%d AND `Scope Key`=%d AND `Scope`='Store' ", $this->id, $store_key
            );
            $_changed = $this->db->exec($sql);
            $changed  += $_changed;

            $store = get_object('Store', $store_key);
            if ($store->id and $_changed) {
                $history_data = array(
                    'History Abstract'    => sprintf(
                        _("User's rights for store %s were removed"), $store->data['Store Code']
                    ),
                    'History Details'     => '',
                    'Action'              => 'disassociate',
                    'Indirect Object'     => 'Store',
                    'Indirect Object Key' => $store->id
                );
                $history_key  = $this->add_history($history_data);
                $sql          = sprintf(
                    "INSERT INTO `%s History Bridge` VALUES (%d,%d,'No','No','Changes')", $this->table_name, $this->id, $history_key
                );
                $this->db->exec($sql);
            }


        }


        return $changed;
    }

    function add_store($to_add, $history = true) {

        $changed = 0;
        foreach ($to_add as $scope_id) {

            $store = get_object('Store', $scope_id);


            if (!$store->id) {
                continue;
            }
            $sql       = sprintf(
                "INSERT INTO `User Right Scope Bridge`VALUES (%d,'Store',%d) ", $this->id, $scope_id
            );
            $update_op = $this->db->prepare($sql);
            $update_op->execute();
            $affected = $update_op->rowCount();

            if ($affected > 0) {
                $changed++;


                $history_data = array(
                    'History Abstract'    => sprintf(
                        _("User's rights for store %s were granted"), $store->data['Store Code']
                    ),
                    'History Details'     => '',
                    'Action'              => 'disassociate',
                    'Indirect Object'     => 'Store',
                    'Indirect Object Key' => $store->id
                );

                $history_key = $this->add_history($history_data);
                $sql         = sprintf(
                    "INSERT INTO `%s History Bridge` VALUES (%d,%d,'No','No','Changes')", $this->table_name, $this->id, $history_key
                );
                $this->db->exec($sql);
            }
        }

        return $changed;

    }


    function update_warehouses($warehouses, $history = true) {

        global $account;
        $this->updated = false;

        if (!($this->data['User Type'] == 'Staff' or $this->data['User Type'] == 'Contractor')) {
            $this->error = true;

            return;
        }

        foreach ($warehouses as $key => $value) {
            if (!is_numeric($value)) {
                unset($warehouses[$key]);
            }
        }
        $old_warehouses = preg_split('/,/', $this->get('User Warehouses'));

        $to_delete = array_diff($old_warehouses, $warehouses);
        $to_add    = array_diff($warehouses, $old_warehouses);
        $changed   = 0;


        if (count($to_delete) > 0) {
            $changed += $this->delete_warehouse($to_delete, $history);
        }
        if (count($to_add) > 0) {
            $changed += $this->add_warehouse($to_add, $history);
        }

        if ($changed > 0) {
            $this->updated = true;


            if ($account->get('Warehouses') == 0 or $this->get_number_warehouses() == 0) {
                $this->update(
                    array('User Hooked Warehouse Key' => ''), 'no_history'
                );
            } else {

                if ($account->get('Warehouses') == 1 and $this->get_number_warehouses() == 1) {

                    $this->update(
                        array(
                            'User Hooked Warehouse Key' => $this->get(
                                'User Warehouses'
                            )
                        ), 'no_history'
                    );
                }
            }

        }

    }

    function delete_warehouse($to_delete, $history = true) {

        include_once 'class.Warehouse.php';
        $changed = 0;
        foreach ($to_delete as $warehouse_key) {

            $sql      = sprintf(
                "DELETE FROM `User Right Scope Bridge` WHERE `User Key`=%d AND `Scope Key`=%d AND `Scope`='Warehouse' ", $this->id, $warehouse_key
            );
            $_changed = $this->db->exec($sql);
            $changed  += $_changed;

            $warehouse = get_object('Warehouse', $warehouse_key);

            if ($warehouse->id and $_changed and $history) {
                $history_data = array(
                    'History Abstract'    => sprintf(
                        _("User's rights for warehouse %s were removed"), $warehouse->data['Warehouse Code']
                    ),
                    'History Details'     => '',
                    'Action'              => 'disassociate',
                    'Indirect Object'     => 'Warehouse',
                    'Indirect Object Key' => $warehouse->id
                );
                $history_key  = $this->add_history($history_data);
                $sql          = sprintf(
                    "INSERT INTO `%s History Bridge` VALUES (%d,%d,'No','No','Changes')", $this->table_name, $this->id, $history_key
                );
                $this->db->exec($sql);
            }


        }


        return $changed;
    }

    function add_warehouse($to_add, $history = true) {
        $changed = 0;
        foreach ($to_add as $scope_id) {

            $warehouse = get_object('Warehouse', $scope_id);
            if (!$warehouse->id) {
                continue;
            }
            $sql       = sprintf(
                "INSERT INTO `User Right Scope Bridge`VALUES (%d,'Warehouse',%d) ", $this->id, $scope_id
            );
            $update_op = $this->db->prepare($sql);
            $update_op->execute();
            $affected = $update_op->rowCount();

            if ($affected > 0 and $history) {
                $changed++;


                $history_data = array(
                    'History Abstract'    => sprintf(
                        _("User's rights for warehouse %s were granted"), $warehouse->data['Warehouse Code']
                    ),
                    'History Details'     => '',
                    'Action'              => 'associate',
                    'Indirect Object'     => 'Warehouse',
                    'Indirect Object Key' => $warehouse->id
                );

                $history_key = $this->add_history($history_data);
                $sql         = sprintf(
                    "INSERT INTO `%s History Bridge` VALUES (%d,%d,'No','No','Changes')", $this->table_name, $this->id, $history_key
                );
                $this->db->exec($sql);
            }
        }

        return $changed;

    }


    /*
        function update_productions($value) {

            global $account;
            include_once 'class.Supplier_Production.php';

            $this->updated = false;

            if ( !( $this->data['User Type'] == 'Staff' or  $this->data['User Type'] == 'Contractor' ) ) {
                $this->error = true;

                return;
            }
            $productions = preg_split('/,/', $value);
            foreach ($productions as $key => $value) {
                if (!is_numeric($value)) {
                    unset($productions[$key]);
                }
            }
            $old_productions = preg_split('/,/', $this->get('User Productions'));

            $old_formatted_productions = $this->get('Productions');
            $to_delete                 = array_diff($old_productions, $productions);
            $to_add                    = array_diff($productions, $old_productions);
            $changed                   = 0;


            if (count($to_delete) > 0) {
                $changed += $this->delete_production_supplier($to_delete);
            }
            if (count($to_add) > 0) {
                $changed += $this->add_production_supplier($to_add);
            }

            if ($changed > 0) {
                $this->updated = true;


                if ($account->get('Productions') == 0 or $this->get_number_productions() == 0) {
                    $this->update(
                        array('User Hooked Production Key' => ''), 'no_history'
                    );
                } else {

                    if ($account->get('Productions') == 1 and $this->get_number_productions() == 1) {

                        $this->update(
                            array(
                                'User Hooked Production Key' => $this->get(
                                    'User Productions'
                                )
                            ), 'no_history'
                        );
                    }
                }

            }

        }

        function delete_production_supplier($to_delete, $history = true) {

            $changed = 0;
            foreach ($to_delete as $production_key) {

                $sql      = sprintf(
                    "DELETE FROM `User Right Scope Bridge` WHERE `User Key`=%d AND `Scope Key`=%d AND `Scope`='Production' ", $this->id, $production_key
                );
                $_changed = $this->db->exec($sql);
                $changed  += $_changed;

                $production = new Supplier_Production($production_key);
                if ($production->id and $_changed) {
                    $history_data = array(
                        'History Abstract'    => sprintf(
                            _(
                                "User's rights for production supplier %s were removed"
                            ), $production->get('Code')
                        ),
                        'History Details'     => '',
                        'Action'              => 'disassociate',
                        'Indirect Object'     => 'Supplier',
                        'Indirect Object Key' => $production->id
                    );
                    $history_key  = $this->add_history($history_data);
                    $sql          = sprintf(
                        "INSERT INTO `%s History Bridge` VALUES (%d,%d,'No','No','Changes')", $this->table_name, $this->id, $history_key
                    );
                    $this->db->exec($sql);
                }


            }


            return $changed;
        }

        function add_production_supplier($to_add, $history = true) {
            $changed = 0;
            foreach ($to_add as $scope_id) {

                $production = new Supplier_Production($scope_id);
                if (!$production->id) {
                    continue;
                }
                $sql       = sprintf(
                    "INSERT INTO `User Right Scope Bridge`VALUES (%d,'Production',%d) ", $this->id, $scope_id
                );
                $update_op = $this->db->prepare($sql);
                $update_op->execute();
                $affected = $update_op->rowCount();

                if ($affected > 0) {
                    $changed++;


                    $history_data = array(
                        'History Abstract'    => sprintf(
                            _(
                                "User's rights for production supplier %s were granted"
                            ), $production->get('Code')
                        ),
                        'History Details'     => '',
                        'Action'              => 'disassociate',
                        'Indirect Object'     => 'Supplier',
                        'Indirect Object Key' => $production->id
                    );

                    $history_key = $this->add_history($history_data);
                    $sql         = sprintf(
                        "INSERT INTO `%s History Bridge` VALUES (%d,%d,'No','No','Changes')", $this->table_name, $this->id, $history_key
                    );
                    $this->db->exec($sql);
                }
            }

            return $changed;

        }

        */


    function update_active($value) {
        $this->updated = false;

        $old_value = $this->get('Active');
        if (!preg_match('/^(Yes|No)$/', $value)) {
            $this->error = true;
            $this->msg   = sprintf(_('Wrong value %s'), $value);

            return;
        }

        $this->update_field('User Active', $value);


        switch ($this->data['User Type']) {
            case 'Staff':
                $staff = get_object('Staff', $this->data['User Parent Key']);

                $staff->editor = $this->editor;
                $staff->get_user_data();
                $new_value = $this->get('Active');
                $staff->add_changelog_record(
                    'Staff User Active', $old_value, $new_value, '', $staff->table_name, $staff->id
                );

                break;
            default:
                return;
                break;
        }

        $this->update_rights();

        $this->other_fields_updated = array(
            'User_Password' => array(
                'field'           => 'User_Password',
                'render'          => ($this->get('User Active') == 'Yes' ? true : false),
                'value'           => $this->get('User Password'),
                'formatted_value' => $this->get('Password'),


            ),
            'User_PIN'      => array(
                'field'           => 'User_PIN',
                'render'          => ($this->get('User Active') == 'Yes' ? true : false),
                'value'           => $this->get('User PIN'),
                'formatted_value' => $this->get('PIN'),


            )
        );

    }

    function update_password($value, $options = '') {

        $this->update_field('User Password', $value, $options);

        switch ($this->data['User Type']) {
            case 'Staff':
                $staff = get_object('Staff', $this->data['User Parent Key']);

                $staff->editor = $this->editor;
                $staff->get_user_data();
                $staff->add_changelog_record(
                    'Staff User Password', '******', '******', '', $staff->table_name, $staff->id
                );

                break;
            default:
                return;
                break;
        }


    }

    function update_pin($value, $options = '') {


        switch ($this->data['User Type']) {
            case 'Staff':
                $staff = get_object('Staff', $this->data['User Parent Key']);

                $staff->editor = $this->editor;
                $staff->get_user_data();
                $staff->update(array('Staff PIN' => $value));
                break;
            default:
                return;
                break;
        }


    }

    function get_field_label($field) {

        switch ($field) {


            case 'User Active':
                $label = _('active');
                break;
            case 'User Handle':
                $label = _('login');
                break;

            case 'User Alias':
                $label = _('name');
                break;

            case 'User Password':
                $label = _('password');
                break;

            case 'User PIN':
                $label = _('PIN');
                break;

            case 'Preferred Locale':
                $label = _('language');
                break;
            case 'User Password Recovery Email':
                $label = _("notifications/recovery email");
                break;
            case 'User Password Recovery Mobile':
                $label = _("recovery mobile");
                break;
            case 'User Stores':
                $label = _('stores');
                break;
            case 'User Websites':
                $label = _('websites');
                break;
            case 'User Warehouses':
                $label = _('warehouses');
                break;
            case 'User Productions':
                $label = _('production suppliers');
                break;
            default:
                $label = $field;

        }

        return $label;

    }

    function get_staff_alias() {

        include_once 'class.Staff.php';


        $staff_alias = '';
        $staff_key   = $this->get_staff_key();
        if ($staff_key) {
            $staff = get_object('Staff', $staff_key);

            $staff_alias = $staff->get('Staff Alias');
        }

        return $staff_alias;
    }

    function get_staff_email() {


        $staff_email = '';
        $staff_key   = $this->get_staff_key();


        if ($staff_key) {
            $staff = get_object('Staff', $staff_key);


            $staff_email = $staff->get('Staff Email');
        }

        return $staff_email;
    }

    function get_staff_name() {

        include_once 'class.Staff.php';


        $staff_name = '';
        $staff_key  = $this->get_staff_key();
        if ($staff_key) {
            $staff      = get_object('Staff', $staff_key);
            $staff_name = $staff->get('Staff Name');
        }

        return $staff_name;
    }

    function get_number_suppliers() {
        return count($this->suppliers);
    }

    function is($tag = '') {
        if (strtolower($this->data['User Type']) == strtolower($tag)) {
            return true;
        } else {
            return false;
        }

    }

    function can_view($tag, $tag_key = false) {

        return $this->can_do('View', $tag, $tag_key);

    }

    function can_supervisor($tag, $tag_key = false) {

        return $this->can_do('Supervisor', $tag, $tag_key);



    }

    function can_do($right_type, $tag, $tag_key = false) {


        if (!is_string($tag)) {
            return false;
        }
        $tag = strtolower(_trim($tag));



        if ($tag_key == false) {
            if (isset($this->rights_allow[$right_type][$tag])) {

                return true;
            } else {
                return false;
            }
        }


        //    return $this->can_do_any($right_type,$tag);
        //  if(!is_numeric($tag_key) or $tag_key<=0 or !preg_match('/^\d+$/',$tag_key) )
        //     return false;
        //  return $this->can_do_this_key($right_type,$tag,$tag_key);

    }

    function can_create($tag, $tag_key = false) {
        return $this->can_do('Create', $tag, $tag_key);
    }

    function can_edit($tag, $tag_key = false) {
        return $this->can_do('Edit', $tag, $tag_key);
    }

    function can_delete($tag, $tag_key = false) {
        return $this->can_do('Delete', $tag, $tag_key);
    }

    function can_do_anyx($right_type, $tag) {

        if (array_key_exists($tag, $this->rights_allow[$right_type])) {
            return true;
        } else {
            return false;
        }
    }

    function read_warehouses() {

        $this->warehouses = array();
        $sql              = sprintf(
            "SELECT * FROM `User Right Scope Bridge` WHERE `User Key`=%d AND `Scope`='Warehouse'", $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $this->warehouses[] = $row['Scope Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }

    function read_stores() {

        $this->stores = array();
        $sql          = sprintf(
            "SELECT * FROM `User Right Scope Bridge` WHERE `User Key`=%d AND `Scope`='Store' ", $this->id
        );
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $this->stores[] = $row['Scope Key'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }

    function read_suppliers() {

        $this->suppliers = array();
        $sql             = sprintf(
            "SELECT * FROM `User Right Scope Bridge` WHERE `User Key`=%d AND `Scope`='Supplier' ", $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $this->suppliers[] = $row['Scope Key'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $this->supplier_righs = 'none';
        if ($this->data['User Type'] == 'Supplier') {
            $sql = "SELECT count(*) AS num FROM `Supplier Dimension`";

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $total_number_suppliers = $row['num'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


            $total_number_allowed_suppliers = count($this->suppliers);
            if ($total_number_suppliers == $total_number_allowed_suppliers) {
                $this->supplier_rights = 'all';
            } else {
                $this->supplier_righs = 'some';
            }
        }
    }

    function read_rights() {

        include 'conf/user_groups.php';
        include 'conf/user_rights.php';

        $this->rights_allow['View']       = array();
        $this->rights_allow['Delete']     = array();
        $this->rights_allow['Edit']       = array();
        $this->rights_allow['Create']     = array();
        $this->rights_allow['Supervisor'] = array();

        $this->rights = array();

        if (!$this->groups_read) {
            $this->read_groups();
        }


        $rights = array();
        foreach ($this->groups_key_array as $group_key) {
            //print "* $group_key *  ";
            //print_r($user_groups[$group_key]['Rights']);
            $rights = array_merge($rights, $user_groups[$group_key]['Rights']);
            //print_r($rights);
        }

        //print "****";

        $sql = sprintf(
            "SELECT group_concat(`Right Code`) AS rights FROM `User Rights Bridge` WHERE `User Key`=%d", $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                if ($row['rights'] != '') {
                    $rights = array_merge(
                        $rights, preg_split('/,/', $row['rights'])
                    );
                }
            }
        }


        foreach ($rights as $right) {
            $right_data = $user_rights[$right];


            if ($right_data['Right Type'] == 'View') {
                $this->rights_allow['View'][$right_data['Right Name']] = 1;

                $this->rights[$right_data['Right Name']]['View'] = 'View';
            }
            if ($right_data['Right Type'] == 'Delete') {
                $this->rights_allow['Delete'][$right_data['Right Name']] = 1;
                $this->rights[$right_data['Right Name']]['Delete']       = 'Delete';
            }
            if ($right_data['Right Type'] == 'Edit') {
                $this->rights_allow['Edit'][$right_data['Right Name']] = 1;
                $this->rights[$right_data['Right Name']]['Edit']       = 'Edit';
            }
            if ($right_data['Right Type'] == 'Create') {
                $this->rights_allow['Create'][$right_data['Right Name']] = 1;
                $this->rights[$right_data['Right Name']]['Create']       = 'Create';
            }
            if ($right_data['Right Type'] == 'Supervisor') {
                $this->rights_allow['Supervisor'][$right_data['Right Name']] = 1;
                $this->rights[$right_data['Right Name']]['Supervisor']       = 'Supervisor';
            }


        }
        //print_r($this->groups_key_array);
        //print_r($this->rights_allow);
        //exit;
    }

    function can_view_list($right_name) {
        $list = array();

        if (isset($this->rights_allow['View'][$right_name])) {
            $rights_data = $this->rights_allow['View'][$right_name];
            if ($rights_data['Right Access'] == 'All') {

                switch ($right_name) {
                    case('stores'):
                        $sql = sprintf(
                            'SELECT `Store Key`  FROM `Store Dimension`'
                        );

                        if ($result = $this->db->query($sql)) {
                            foreach ($result as $row) {
                                $list[] = $row['Store Key'];
                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            exit;
                        }


                        break;
                }

            }

        }

        return $list;
    }


    function get_tab_defaults($tab) {


        include 'conf/tabs.defaults.php';


        if (isset($tab_defaults[$tab])) {

            return $tab_defaults[$tab];
        }
        if (isset($tab_defaults_alias[$tab])) {
            return $tab_defaults[$tab_defaults_alias[$tab]];
        }

        exit("User class: error get_tab_defaults not configured: $tab");
    }

    function create_api_key($data) {

        $data['API Key User Key'] = $this->id;


        $api_key = new API_Key('create', $data);

        $this->create_user_error = $api_key->error;
        $this->create_user_msg   = $api_key->msg;
        $this->api_key           = $api_key;

        return $this->api_key;

    }

    function delete() {

        $data     = array('data' => $this->data);
        $metadata = json_encode($data);

        $sql = sprintf(
            "INSERT INTO `User Deleted Dimension`  (`User Deleted Key`,`User Deleted Handle`,`User Deleted Alias`,`User Deleted Type`,`User Deleted Date`,`User Deleted Metadata`) VALUES (%d,%s,%s,%s,%s,%s) ", $this->id, prepare_mysql($this->get('User Handle'), true),
            prepare_mysql($this->get('User Alias'), true), prepare_mysql($this->get('User Type'), true), prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql(gzcompress($metadata, 9))

        );

        //print $sql;


        $stmt = $this->db->prepare($sql);
        $stmt->execute();


        $history_data = array(
            'History Abstract' => _('User deleted'),
            'History Details'  => '',
            'Action'           => 'deleted'
        );
        $this->add_subject_history(
            $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
        );


        $sql = sprintf(
            "DELETE FROM `User Dimension` WHERE `User Key`=%d  ", $this->id
        );
        $this->db->exec($sql);


        $this->deleted = true;


    }

    function get_dashboard_items() {

        $dashboard_items = array();


        if ($this->can_view('customers_reports')) {
            $dashboard_items[] = 'pending_orders_and_customers';

        }

        if ($this->can_view('inventory_reports')) {
            $dashboard_items[] = 'inventory_warehouse';

        }
        if ($this->can_view('sales_reports')) {
            $dashboard_items[] = 'sales_overview';

        }


        /*

                if ($this->data['User Type'] == 'Staff') {
                    //$dashboard_items[] = 'kpis';





                    $dashboard_items[] = 'pending_orders_and_customers';
                    $dashboard_items[] = 'inventory_warehouse';


                } else {
                    if ($this->data['User Type'] == 'Contractor') {

                       // $dashboard_items[] = 'kpis';
                        $dashboard_items[] = 'pending_orders_and_customers';
                        $dashboard_items[] = 'inventory_warehouse';

                        $dashboard_items[] = 'sales_overview';
                        //$dashboard_items[] = 'customers';



                    } else {
                        if ($this->data['User Type'] == 'Administrator') {


                        }
                    }
                }
        */

        return $dashboard_items;


    }


}


?>
