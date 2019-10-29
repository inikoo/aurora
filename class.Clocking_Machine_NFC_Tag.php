<?php
/*

 About: 
 Author: Raul Perusquia <rulovico@gmail.com>

 Refurbished: 12 November 2018 at 15:04:45 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/
include_once('class.DB_Table.php');


class Clocking_Machine_NFC_Tag extends DB_Table {

    /**
     * @var \PDO
     */
    public $db;


    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Clocking Machine NFC Tag';
        $this->ignore_fields = array('Clocking Machine NFC Tag Key');

        if (preg_match('/^(new|create)$/i', $arg1) and is_array($arg2)) {
            $this->create($arg2);

            return;
        }

        if (preg_match('/find/i', $arg1)) {
            $this->find($arg2, $arg3);

            return;
        }
        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }
        $this->get_data($arg1, $arg2, $arg3);
    }


    function create($data) {

        $this->data = $this->base_data();
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->data)) {
                $this->data[$key] = _trim($value);
            }
        }

        if ($this->data['Clocking Machine NFC Tag ID'] == '') {
            $this->msg   = _('Field required');
            $this->new   = false;
            $this->error = true;

            return;
        }

        $this->data['Clocking Machine NFC Tag Hash']          = base_convert($this->data['Clocking Machine NFC Tag ID'], 10, 36);
        $this->data['Clocking Machine NFC Tag Creation Date'] = gmdate('Y-m-d H:i:s');


        if ($this->data['Clocking Machine NFC Tag Last Scan Box Key'] == '') {
            $this->msg   = _('Field required');
            $this->new   = false;
            $this->error = true;

            return;
        }


        $sql = sprintf(
            "INSERT INTO `Clocking Machine NFC Tag Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($this->data)).'`', join(',', array_fill(0, count($this->data), '?'))
        );


        $stmt = $this->db->prepare($sql);

        $i = 1;
        foreach ($this->data as $key => $value) {
            if (in_array($key ,array( 'Clocking Machine NFC Tag Assigned Date','Clocking Machine NFC Tag Assigner User Key','Clocking Machine NFC Tag Staff Key')) and $value == '') {
                $value = null;
            }
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {
            $this->id  = $this->db->lastInsertId();
            $this->new = true;
            $this->get_data('id', $this->id);


            $history_data = array(
                'History Abstract' => _('New NFC tag scanned').' <span>'.$this->data['Clocking Machine NFC Tag Hash'].'</span>',
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );


        } else {
            //print_r($error_info = $this->db->errorInfo());
            //exit;

            $this->error = true;
            $this->msg   = 'Error inserting NFC tag record';
        }

    }

    function get_data($key, $tag, $tag2 = false) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Clocking Machine NFC Tag Dimension` WHERE `Clocking Machine NFC Tag Key`=%d", $tag
            );
        } elseif ($key == 'tag_id') {
            $sql = sprintf(
                "SELECT  *  FROM `Clocking Machine NFC Tag Dimension` WHERE `Clocking Machine NFC Tag ID`=%s ", prepare_mysql($tag)
            );
        } else {
            return false;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Clocking Machine NFC Tag Key'];

        }


    }

    function find($raw_data, $options = '') {

        if (isset($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {

                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }

            }
        }


        $this->found = false;
        $create      = '';

        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        if ($raw_data['Clocking Machine NFC Tag ID'] == '') {
            $this->error_code = 'missing_field';
            $this->msg        = 'Missing tag id';

            return;
        }


        $data = $this->base_data();
        foreach ($raw_data as $key => $val) {
            $_key        = $key;
            $data[$_key] = $val;
        }

        $sql = "SELECT `Clocking Machine NFC Tag Key` FROM `Clocking Machine NFC Tag Dimension` WHERE `Clocking Machine NFC Tag ID`=?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $data['Clocking Machine NFC Tag ID']
            )
        );
        if ($row = $stmt->fetch()) {
            $this->found            = true;
            $this->error_code       = 'duplicated_field';
            $this->found_key        = $row['Clocking Machine NFC Tag Key'];
            $this->duplicated_field = 'Clocking Machine NFC Tag ID';



        }


        if ($this->found) {

            $this->get_data('id', $this->found_key);
            return;

        }


        if ($create) {
            $this->create($data, $options);
        }
    }

    function get($key) {


        if (!$this->id) {
            return;
        }


        switch ($key) {


            default:


                if (array_key_exists('Clocking Machine NFC Tag '.$key, $this->data)) {
                    return $this->data[$this->table_name.' '.$key];
                }

                if (isset($this->data[$key])) {
                    return $this->data[$key];
                } else {
                    return '';
                }
        }

        return '';
    }

    function get_field_label($field) {

        switch ($field) {


            default:


                $label = $field;

        }

        return $label;

    }


    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        if (!$this->deleted and $this->id) {

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
    }

    /**
     * @param $box_key integer
     * @param $date string
     *
     * @return array
     */
    function scanned($box_key, $api_key_key,$date) {

        $this->fast_update(
            array(
                'Clocking Machine NFC Tag Last Scan Box Key' => $box_key,
                'Clocking Machine NFC Tag Last Scan'         => $date
            )
        );


        if($this->data['Clocking Machine NFC Tag Status']=='Assigned'){
            $this->fast_update(
                array(
                    'Clocking Machine NFC Tag Scans' => $this->data['Clocking Machine NFC Tag Scans']+1,
                )
            );

            $staff=get_object('Staff',$this->data['Clocking Machine NFC Tag Staff Key']);
            $staff->editor = $this->editor;

            if(!$staff->id){
                $scan_data=array(
                    'state'=>'Fail',
                    'msg'=>'staff not set up'
                );
            }else{
                include_once 'class.Timesheet.php';

                include_once 'class.Timesheet_Record.php';

                $data = array(
                    'Timesheet Record Date'   => $date,
                    'Timesheet Record Source' => 'ClockingMachine',
                    'Timesheet Record Type'   => 'ClockingRecord',
                    'editor'                  => $this->editor
                );



                $staff->create_timesheet_record($data);



                if ($staff->create_timesheet_record_error) {

                    if ($staff->create_timesheet_record_duplicated) {
                        $scan_data = log_api_key_access_failure(
                            $this->db, $api_key_key, 'Fail_Operation', "Record already exists"
                        );

                    } else {
                        $scan_data = log_api_key_access_failure(
                            $this->db, $api_key_key, 'Fail_Operation', "Error creating record"
                        );

                    }


                } else {
                    $scan_data = log_api_key_access_success(
                        $this->db, $api_key_key, 'Record created'
                    );
                    $scan_data['staff_name']=$staff->get('Name');

                }




            }


        }else{

            $this->fast_update(
                array(
                    'Clocking Machine NFC Tag Scans While Pending' => $this->data['Clocking Machine NFC Tag Scans While Pending']+1,
                )
            );



            $scan_data=array(
                'state'=>'Pending_Tag'
            );
        }


        return $scan_data;


    }


}

