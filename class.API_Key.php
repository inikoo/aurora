<?php

/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 19 November 2015 at 14:10:58 GMT Sheffield UK

 Version 2.0
*/


class API_Key extends DB_Table {


    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {
        global $db;

        $this->db            = $db;
        $this->table_name    = 'API Key';
        $this->ignore_fields = array('API Key Key');

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }
        if ($arg1 == 'create') {
            $this->create($arg2, $arg3);

            return;
        }

        $this->get_data($arg1, $arg2);

        return;

    }


    function get_data($tipo, $tag) {

        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `API Key Dimension` WHERE `API Key Key`=%d", $tag
            );
        } elseif ($tipo == 'deleted') {
            $this->get_deleted_data($tag);

            return;
        } else {
            return;
        }
        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id   = $this->data['API Key Key'];
            $this->user = get_object('User', $this->data['API Key User Key']);
        }

    }


    function get_deleted_data($tag) {


        if ($tag > 0) {


            $this->deleted = true;
            $sql           = sprintf(
                "SELECT * FROM `API Key Deleted Dimension` WHERE `API Key Deleted Key`=%d", $tag
            );


            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->id   = $this->data['API Key Deleted Key'];
                $this->user = get_object('User', $this->data['API Key Deleted User Key']);

            }
        }

    }


    function create($data,$cost) {


        if (!$cost or !is_numeric($cost) or !is_integer($cost)) {
            $cost = 10;
        }

        if ($cost < 4) {
            $cost = 4;
        }


        include_once 'utils/password_functions.php';



        $this->secret_key = 'P'.generatePassword(39, 3);



        $data['API Key Code'] = hash('crc32', generatePassword(32, 10), false);


        $data['API Key Hash'] = password_hash($this->secret_key, PASSWORD_DEFAULT, ['cost' => $cost]);

        $data['API Key Valid From'] = gmdate('Y-m-d H:i:s');


        $this->secret_key = base64_encode($this->secret_key);
        $this->data       = $data;

        $keys   = '';
        $values = '';


        foreach ($this->data as $key => $value) {
            $keys   .= ",`".$key."`";
            $values .= ','.prepare_mysql($value, false);
        }
        $values = preg_replace('/^,/', '', $values);
        $keys   = preg_replace('/^,/', '', $keys);

        $sql = "insert into `API Key Dimension` ($keys) values ($values)";

        //print  $sql;
        if ($this->db->exec($sql)) {

            $this->id  = $this->db->lastInsertId();
            $this->new = true;

            $this->get_data('id', $this->id);


            $history_data = array(
                'History Abstract' => sprintf(_('%s API key created (%s)'), $this->get('Scope'), $this->get('Code')),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );

        } else {
            $this->error;
            $this->msg = 'Can not create API key';


        }
    }

    function get($key = '') {

        if (!$this->id) {
            return '';
        }


        switch ($key) {

            case 'Scope':
                switch ($this->data['API Key Scope']) {


                    case 'Timesheet':
                        return _('Timesheet machine');
                        break;
                    case 'Stock':
                        return _('Stock control app');
                        break;
                    case 'Picking':
                        return _('Picking app');
                        break;
                    default:
                        return $this->data['API Key Scope'];
                }

                break;

            case 'Allowed Requests per Hour':
            case 'Successful Requests':
            case 'Failed Attempt Requests':
            case 'Failed Access Request':
            case 'Failed Time Limit Requests':
            case 'Failed Operation Requests':
            case 'Failed IP Requests':
                return number($this->data['API Key '.$key]);
                break;


            case ('Valid From'):
            case ('Valid To'):
            case ('Last Request Date'):
                return ($this->data['API Key '.$key] == '' or $this->data['API Key '.$key] == '0000-00-00 00:00:00')
                    ? ''
                    : strftime(
                        "%a %e %b %Y %H:%M %Z", strtotime($this->data['API Key '.$key])
                    );

                break;


            case 'Active':
                switch ($this->data['API Key Active']) {
                    case 'Yes':
                        return _('Yes');
                        break;
                    case 'No':
                        return _('No');
                        break;
                    default:
                        return $this->data['API Key Active'];
                }
                break;
            case 'Address':

                global $account;


                switch ($this->data['API Key Scope']) {
                    case 'Timesheet':
                        $request = '/api/timesheet_record';
                        break;
                    case 'Stock':
                        $request = '/api/stock';
                        break;
                    case 'Picking':
                        $request = '/api/picking';
                        break;
                    default:
                        $request = '/api/';
                        break;
                }

                return $account->get('Account System Public URL').$request;

                break;
            case 'Scope':
                switch ($this->data['API Key Scope']) {
                    case 'Timesheet':
                        $scope = _('Timesheet');
                        break;
                    default:
                        $scope = $this->data['API Key Scope'];
                        break;
                }

                return $scope;
                break;
            default:
                if (isset($this->data[$key])) {
                    return $this->data[$key];
                }

                if (array_key_exists('API Key '.$key, $this->data)) {
                    return $this->data['API Key '.$key];
                }

                return false;

        }


    }

    function refresh_key($cost) {


        if (!$cost or !is_numeric($cost) or !is_integer($cost)) {
            $cost = 10;
        }

        if ($cost < 4) {
            $cost = 4;
        }


        include_once 'utils/password_functions.php';
        $this->secret_key = 'P'.generatePassword(39, 3);



        $this->fast_update(
            array(
                'API Key Hash' => password_hash($this->secret_key, PASSWORD_DEFAULT, ['cost' => $cost])
            )
        );

        $this->secret_key = base64_encode($this->secret_key);
    }

    function update_requests_data() {


        $request_elements = array(
            'OK'             => 0,
            'Fail_IP'        => 0,
            'Fail_TimeLimit' => 0
        );

        $sql = sprintf(
            "SELECT count(*) AS num, `Response` AS state FROM `API Request Dimension` WHERE `API Key Key`=%d GROUP BY `API Request State`", $this->id
        );
        foreach ($this->db->query($sql) as $row) {
            $request_elements[$row['state']] = $row['num'];

        }


        $this->fast_update(
            array(
                'API Key Successful Requests'       => $request_elements['OK'],
                'API Key Failed Attempt Requests'   => $request_elements['Fail_Attempt'],
                'API Key Failed Access Requests'    => $request_elements['Fail_Access'],
                'API Key Failed Operation Requests' => $request_elements['Fail_Operation'],

                'API Key Failed IP Requests'         => $request_elements['Fail_IP'],
                'API Key Failed Time Limit Requests' => $request_elements['Fail_TimeLimit'],


            )
        );


        $sql = sprintf("SELECT max(`Date`)  AS last_request FROM `API Request Dimension` WHERE `API Key Key`=%d GROUP BY `API Request State`", $this->id);
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->fast_update(
                    array(
                        'API Key Last Request Date' => $row['last_request']


                    )
                );
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

    }


    function delete() {


        $data = $this->data;
        unset($data['API Key Hash']);
        $metadata = json_encode($data);


        $sql = sprintf(
            "INSERT INTO `API Key Deleted Dimension`  (`API Key Deleted Key`,`API Key Deleted User Key`,`API Key Deleted Code`,`API Key Deleted Date`,`API Key Deleted Metadata`) VALUES (%d,%d,%s,%s,%s) ", $this->id, $this->get('API Key User Key'),
            prepare_mysql($this->get('API Key Code')), prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql(
                gzcompress(
                    $metadata, 9
                )
            )

        );


        $stmt = $this->db->prepare($sql);
        $stmt->execute();


        $history_data = array(
            'History Abstract' => _('API key deleted'),
            'History Details'  => '',
            'Action'           => 'deleted'
        );
        $this->add_subject_history(
            $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
        );


        $sql = sprintf(
            "DELETE FROM `API Key Dimension` WHERE `API Key Key`=%d", $this->id
        );
        $this->db->exec($sql);


    }


    function get_field_label($field) {

        switch ($field) {

            case 'API Key Scope':
                $label = _('Scope');
                break;
            case 'API Key Allowed IP':
                $label = _('Allowed IPs');
                break;

            case 'API Key Allowed Requests per Hour':
                $label = _('Max request/hr');
                break;
            case 'API Key Active':
                $label = _('API active');
                break;


            default:
                $label = $field;

        }

        return $label;

    }


    function regenerate_private_key() {

        include_once 'utils/password_functions.php';
        $this->secret_key = generatePassword(40, 3);


        $api_key_hash = password_hash($this->secret_key, PASSWORD_DEFAULT);
        $secret_key   = base64_encode($this->secret_key);

        $this->fast_update(
            array(
                'API Key Hash' => $api_key_hash
            )
        );

        $history_data = array(
            'History Abstract' => _('API private key regenerated'),
            'History Details'  => '',
            'Action'           => 'edited'
        );
        $this->add_subject_history(
            $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
        );


        return $secret_key;


    }


}
