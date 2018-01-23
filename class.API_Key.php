<?php

/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 19 November 2015 at 14:10:58 GMT Sheffield UK

 Version 2.0
*/


class API_Key extends DB_Table {


    function API_Key($arg1 = false, $arg2 = false) {
        global $db;

        $this->db            = $db;
        $this->table_name    = 'API Key';
        $this->ignore_fields = array('API Key Key');

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }
        if (preg_match('/^(create|new)/i', $arg1)) {
            $this->create($arg2);

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
        } else {
            return;
        }
        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['API Key Key'];
        }

    }

    function create($data) {


        include_once 'utils/password_functions.php';
        $this->secret_key = generatePassword(40, 10);

        $data['API Key Code'] = hash('crc32', generatePassword(32, 10), false);


        $data['API Key Hash'] = password_hash(
            $this->secret_key, PASSWORD_DEFAULT
        );
        $this->secret_key     = base64_encode($this->secret_key);
        $this->data           = $data;

        $keys   = '';
        $values = '';


        foreach ($this->data as $key => $value) {
            $keys .= ",`".$key."`";
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
        } else {
            $this->error;
            $this->msg = 'Can not create API key';


        }
    }

    function get($key = '') {

        if(!$this->id){
            return '';
        }


        switch ($key) {


            case 'Scope':
                switch ($this->data['API Key Scope']){


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

    function update_requests_data() {


        $request_elements = array(
            'OK'             => 0,
            'Fail_IP'        => 0,
            'Fail_TimeLimit' => 0
        );

        $sql = sprintf(
            "SELECT count(*) AS num, `Response` AS state FROM `API Request Dimension` WHERE `API Key Key`=%d group by `API Request State`", $this->id
        );
        foreach ($this->db->query($sql) as $row) {
            $request_elements[$row['state']] = $row['num'];

        }



        $this->fast_update(
            array(
                'API Key Successful Requests'        => $request_elements['OK'],
                'API Key Failed Attempt Requests'        => $request_elements['Fail_Attempt'],
                'API Key Failed Access Requests'        => $request_elements['Fail_Access'],
                'API Key Failed Operation Requests'        => $request_elements['Fail_Operation'],

                'API Key Failed IP Requests'         => $request_elements['Fail_IP'],
                'API Key Failed Time Limit Requests' => $request_elements['Fail_TimeLimit'],


            )
        );


        $sql=sprintf("select max(`Date`)  as last_request FROM `API Request Dimension` WHERE `API Key Key`=%d group by `API Request State`", $this->id);
        if ($result=$this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->fast_update(
                    array(
                        'API Key Last Request Date'        => $row['last_request']



                    )
                );
        	}
        }else {
        	print_r($error_info=$this->db->errorInfo());
        	print "$sql\n";
        	exit;
        }

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


}
