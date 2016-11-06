<?php

/*
 File: Ship_To.php

 This file contains the Ship To Class

 About:
 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/


class Ship_To extends DB_Table {


    function Ship_To($arg1 = false, $arg2 = false) {

        $this->table_name    = 'Ship To';
        $this->ignore_fields = array('Ship To Key');

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }
        if (preg_match('/^(create|new)/i', $arg1)) {
            $this->find($arg2, 'create');

            return;
        }
        if (preg_match('/find/i', $arg1)) {
            $this->find($arg2, $arg1);

            return;
        }
        $this->get_data($arg1, $arg2);

        return;

    }


    function get_data($tipo, $tag) {

        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Ship To Dimension` WHERE `Ship To Key`=%d", $tag
            );
        } else {
            return;
        }

        // print $sql;
        $result = mysql_query($sql);
        if ($this->data = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->id = $this->data['Ship To Key'];
        }


    }


    function find($raw_data, $options) {

        $create = '';
        $update = '';
        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }
        if (preg_match('/update/i', $options)) {
            $update = 'update';
        }

        $data = $this->base_data();


        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = $value;
            }

        }

        // print_r($raw_data);
        //  print_r($data);
        //  exit("s");


        $fields = array(
            'Ship To Email',
            'Ship To Telephone',
            'Ship To Company Name',
            'Ship To Contact Name',
            'Ship To Country Code',
            'Ship To Postal Code',
            'Ship To Town',
            'Ship To Line 1',
            'Ship To Line 2',
            'Ship To Line 3',
            'Ship To Line 4'
        );

        $sql = sprintf("SELECT * FROM `Ship To Dimension` WHERE TRUE  ");
        foreach ($fields as $field) {
            $sql .= sprintf(
                ' and `%s`=%s COLLATE utf8_bin', $field, prepare_mysql($data[$field], false)
            );
        }
        //print $sql;

        $result      = mysql_query($sql);
        $num_results = mysql_num_rows($result);
        if ($num_results == 0) {
            // address not found
            $this->found = false;


        } else {
            if ($num_results == 1) {
                $row = mysql_fetch_array($result, MYSQL_ASSOC);

                $this->get_data('id', $row['Ship To Key']);
                $this->found     = true;
                $this->found_key = $row['Ship To Key'];

            } else {// Found in mora than one
                print("Warning to shipping addresses $sql\n");
                $row = mysql_fetch_array($result, MYSQL_ASSOC);

                $this->get_data('id', $row['Ship To Key']);
                $this->found     = true;
                $this->found_key = $row['Ship To Key'];


            }
        }

        if (!$this->found and $create) {
            $this->create($data);

        }


    }

    function create($data) {

        $this->data = $data;

        $keys   = '';
        $values = '';

        foreach ($this->data as $key => $value) {
            if ($key == 'Ship To XHTML Address') {
                continue;
            }
            //  if(preg_match('/Address Data Creation/i',$key) ){
            // $keys.=",`".$key."`";
            // $values.=', Now()';
            //}else{
            $keys .= ",`".$key."`";
            $values .= ','.prepare_mysql($value, false);
            // }

        }


        $values = preg_replace('/^,/', '', $values);
        $keys   = preg_replace('/^,/', '', $keys);

        $sql = "insert into `Ship To Dimension` ($keys) values ($values)";
        //print $sql;
        if (mysql_query($sql)) {
            $this->id                  = mysql_insert_id();
            $this->data['Address Key'] = $this->id;
            $this->new                 = true;
            $this->get_data('id', $this->id);
            $this->data['Ship To XHTML Address'] = $this->get_xhtml_address();
            //print $this->data['Ship To XHTML Address'];
            $sql = sprintf(
                "UPDATE `Ship To Dimension` SET `Ship To XHTML Address`=%s WHERE `Ship To Key`=%d", prepare_mysql($this->data['Ship To XHTML Address']), $this->id
            );
            mysql_query($sql);
        } else {
            print "Error can not create address\n";
            exit;

        }
    }

    function get_xhtml_address() {
        return $this->display('xhtml');

    }

    function display($tipo) {

        include_once('utils/geography_functions.php');

        $separator = '\n';

        if ($tipo == 'xhtml') {
            $separator = '<br/>';
        }


        $address = '';

        if ($this->data['Ship To Contact Name'] != '') {
            if ($tipo == 'xhtml') {
                $address .= '<span style="text-decoration:underline">'._trim(
                        $this->data['Ship To Contact Name']
                    ).'</span>'.$separator;
            } else {
                $address .= _trim($this->data['Ship To Contact Name']).$separator;
            }
        }

        if ($this->data['Ship To Company Name'] != '') {
            $address .= _trim($this->data['Ship To Company Name']).$separator;
        }

        if ($address != '') {
            $address
                = '<div style="font-style:italic;color:#444;margin-bottom:5px">'.$address.'</div>';
        }


        if ($this->data['Ship To Line 1'] != '') {
            $address .= htmlspecialchars(_trim($this->data['Ship To Line 1'])).$separator;
        }
        if ($this->data['Ship To Line 2'] != '') {
            $address .= htmlspecialchars(_trim($this->data['Ship To Line 2'])).$separator;
        }

        if ($this->data['Ship To Line 3'] != '') {
            $address .= htmlspecialchars(_trim($this->data['Ship To Line 3'])).$separator;
        }
        $town_address = _trim($this->data['Ship To Town']);
        if ($town_address != '') {
            $address .= htmlspecialchars($town_address).$separator;
        }

        if ($this->data['Ship To Line 4'] != '') {
            $address .= _trim($this->data['Ship To Line 3']).$separator;
        }
        $ps_address = _trim($this->data['Ship To Postal Code']);
        if ($ps_address != '') {
            $address .= $ps_address.$separator;
        }

        if ($tipo == 'xhtml') {
            $address .= '<b>'.translate_country_name(
                    $this->data['Ship To Country Name']
                ).'</b>';
        } else {
            $address .= translate_country_name(
                $this->data['Ship To Country Name']
            );
        }

        if ($tipo == 'xhtml') {
            if ($this->data['Ship To Telephone'] != '') {
                $address .= '<div style="font-style:italic;color:#444;margin-top:5px">'._('Tel').': '._trim($this->data['Ship To Telephone']).'</div>';
            }

        } else {

            if ($this->data['Ship To Telephone'] != '') {
                $address .= $Ship.$separator;
            }

            if ($this->data['Ship To Telephone'] != '') {
                $address .= _('Tel').': '._trim(
                        $this->data['Ship To Telephone']
                    );
            }
        }


        return _trim($address);

    }

    function get($key = '') {


        if ($key == 'World Region Code') {


            if ($this->data['Ship To Country Code'] == '') {
                return 'UNKN';
            }

            $sql    = sprintf(
                "SELECT `World Region Code` FROM kbase.`Country Dimension` WHERE `Country Code`=%s", prepare_mysql($this->data['Ship To Country Code'])
            );
            $result = mysql_query($sql);
            if ($row = mysql_fetch_array($result)) {
                return $row['World Region Code'] == '' ? 'UNKN' : $row['World Region Code'];
            } else {
                return 'UNKN';
            }

        }


        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        switch ($key) {
        }
        $_key = ucfirst($key);
        if (isset($this->data[$_key])) {
            return $this->data[$_key];
        }
        print "Error $key not found in get from Ship TO\n";

        return false;

    }


}
