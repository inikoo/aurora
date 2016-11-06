<?php

/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 22 November 2015 at 17:46:16 GMT Sheffield UK

 Version 2.0
*/


class Timesheet extends DB_Table {


    function Timesheet($arg1 = false, $arg2 = false, $arg3 = false) {
        global $db;

        $this->db            = $db;
        $this->table_name    = 'Timesheet';
        $this->ignore_fields = array('Timesheet Key');

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }

        if (preg_match('/^find/i', $arg1)) {

            $this->find($arg2, $arg3);

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
                "SELECT * FROM `Timesheet Dimension` WHERE `Timesheet Key`=%d", $tag
            );
        } else {
            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Timesheet Key'];
        }


    }

    function find($raw_data, $options) {


        if (isset($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {

                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }

            }
        }


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
                $data[$key] = _trim($value);
            }
        }


        $sql = sprintf(
            "SELECT `Timesheet Key` FROM `Timesheet Dimension` WHERE `Timesheet Date`=%s AND `Timesheet Staff Key`=%d ", prepare_mysql($data['Timesheet Date']), $data['Timesheet Staff Key']
        );


        if ($row = $this->db->query($sql)->fetch()) {


            $this->found     = true;
            $this->found_key = $row['Timesheet Key'];
            $this->get_data('id', $this->found_key);
        }


        if ($create and !$this->found) {


            $this->create($raw_data);

        }


    }

    function create($data) {

        $this->duplicated = false;
        $this->new        = false;

        $this->editor = $data['editor'];
        unset($data['editor']);
        $this->data = $data;

        $keys   = '';
        $values = '';

        foreach ($this->data as $key => $value) {
            $keys .= ",`".$key."`";
            $values .= ','.prepare_mysql($value, false);
        }
        $values = preg_replace('/^,/', '', $values);
        $keys   = preg_replace('/^,/', '', $keys);

        $sql = "insert into `Timesheet Dimension` ($keys) values ($values)";

        //print  $sql;
        if ($this->db->exec($sql)) {

            $this->id  = $this->db->lastInsertId();
            $this->new = true;

            $this->get_data('id', $this->id);
        } else {
            $this->error = true;


            $error_info = $this->db->errorInfo();
            if ($error_info[0] == 23000) {
                $this->duplicated = true;
                $this->msg        = _('Record already exists');
            } else {
                $this->msg = 'Can not create Timesheet. '.$error_info[2];
            }

        }

    }

    function create_timesheet_record($data) {


        $data['Timesheet Record Timesheet Key'] = $this->id;
        $data['Timesheet Record Staff Key']
                                                = $this->data['Timesheet Staff Key'];
        $data['editor']                         = $this->editor;
        $timesheet_record                       = new Timesheet_Record(
            'new', $data
        );


        if ($timesheet_record->new) {


            $this->update_number_clocking_records();
            $this->process_clocking_records_action_type();
            $this->update_clocked_time();
            $this->update_working_time();
            $this->update_unpaid_overtime();


        } else {
            $this->error = true;
            $this->msg   = $timesheet_record->msg;


        }

        return $timesheet_record;

    }

    function update_number_clocking_records() {

        $clocking_records         = 0;
        $ignored_clocking_records = 0;

        $sql = sprintf(
            'SELECT count(*) num,`Timesheet Record Ignored` FROM `Timesheet Record Dimension` WHERE `Timesheet Record Timesheet Key`=%d AND `Timesheet Record Type`="ClockingRecord" GROUP BY `Timesheet Record Ignored`  ',
            $this->id
        );

        if ($result = $this->db->query($sql)) {

            while ($row = $result->fetch()) {
                if ($row['Timesheet Record Ignored'] == 'Yes') {
                    $ignored_clocking_records = $row['num'];
                } else {
                    $clocking_records = $row['num'];
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;

        }

        $this->update(
            array('Timesheet Clocking Records' => $clocking_records), 'no_history'
        );
        $this->update(
            array('Timesheet Ignored Clocking Records' => $ignored_clocking_records), 'no_history'
        );


    }

    function process_clocking_records_action_type() {


        $sql = sprintf(
            'UPDATE `Timesheet Record Dimension` SET `Timesheet Record Ignored Due Missing End`= `Timesheet Record Ignored`  WHERE `Timesheet Record Timesheet Key`=%d   ', $this->id

        );
        $this->db->exec($sql);
        $action_type = 'Start';

        $sql = sprintf(
            'SELECT `Timesheet Record Date`,`Timesheet Record Key` FROM `Timesheet Record Dimension` WHERE `Timesheet Record Timesheet Key`=%d AND `Timesheet Record Ignored`="No"  AND `Timesheet Record Type`="ClockingRecord" ORDER BY `Timesheet Record Date`',
            $this->id

        );

        if ($result = $this->db->query($sql)) {

            foreach ($result as $row) {


                $sql = sprintf(
                    "UPDATE `Timesheet Record Dimension` SET `Timesheet Record Action Type`=%s WHERE `Timesheet Record Key`=%d  ", prepare_mysql($action_type), $row['Timesheet Record Key']
                );


                $this->db->exec($sql);

                if ($action_type == 'Start') {
                    $last_start_key = $row['Timesheet Record Key'];
                    $action_type    = 'End';
                } else {
                    $action_type = 'Start';

                }

            }

            if ($action_type == 'End' and $last_start_key) {

                $sql = sprintf(
                    "UPDATE `Timesheet Record Dimension` SET `Timesheet Record Ignored Due Missing End`='Yes' WHERE `Timesheet Record Key`=%d  ", $last_start_key
                );

                $this->db->exec($sql);
            }

        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $missing_records = 0;

        $sql = sprintf(
            'SELECT count(*) AS num  FROM `Timesheet Record Dimension` WHERE `Timesheet Record Timesheet Key`=%d AND `Timesheet Record Ignored`="No"  AND  `Timesheet Record Ignored Due Missing End`="Yes" ',
            $this->id

        );

        if ($result = $this->db->query($sql)) {

            if ($row = $result->fetch()) {
                $missing_records = $row['num'];

            }

        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        $this->update(
            array('Timesheet Missing Clocking Records' => $missing_records), 'no_history'
        );

    }

    function update_clocked_time() {
        $action_type = 'Start';

        $clocked_seconds = 0;
        $sql             = sprintf(
            'SELECT `Timesheet Record Date`,`Timesheet Record Key`, UNIX_TIMESTAMP(`Timesheet Record Date`) date FROM `Timesheet Record Dimension` WHERE `Timesheet Record Timesheet Key`=%d AND `Timesheet Record Ignored Due Missing End`="No"  AND `Timesheet Record Type`="ClockingRecord" ORDER BY `Timesheet Record Date`',
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($action_type == 'Start') {
                    $start_date  = $row['date'];
                    $action_type = 'End';
                } else {
                    $end_date        = $row['date'];
                    $clocked_seconds = $clocked_seconds + ($end_date - $start_date);
                    $action_type     = 'Start';
                }

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }
        $this->update(
            array('Timesheet Clocked Time' => ($clocked_seconds)), 'no_history'
        );


        switch (date('N', strtotime($this->get('Timesheet Date')))) {
            case 1:
                $this->update(
                    array('Timesheet Monday Clocked Time' => $clocked_seconds), 'no_history'
                );
                break;
            case 2:
                $this->update(
                    array('Timesheet Tuesday Clocked Time' => $clocked_seconds), 'no_history'
                );
                break;
            case 3:
                $this->update(
                    array('Timesheet Wednesday Clocked Time' => $clocked_seconds), 'no_history'
                );
                break;
            case 4:
                $this->update(
                    array('Timesheet Thursday Clocked Time' => $clocked_seconds), 'no_history'
                );
                break;
            case 5:
                $this->update(
                    array('Timesheet Friday Clocked Time' => $clocked_seconds), 'no_history'
                );
                break;
            case 6:
                $this->update(
                    array('Timesheet Saturday Clocked Time' => $clocked_seconds), 'no_history'
                );
                break;
            case 7:
                $this->update(
                    array('Timesheet Sunday Clocked Time' => $clocked_seconds), 'no_history'
                );
                break;
            default:

                break;
        }

    }

    function get($key = '') {


        switch ($key) {

            case 'Clocked Hours':
            case 'Working Hours':
            case 'Breaks Hours':
                $hours = $this->data['Timesheet '.preg_replace(
                        '/Hours/', 'Time', $key
                    )] / 3600;

                return sprintf(
                    "%s %s", number($hours, 3), ngettext("h", "hrs", $hours)
                );

                break;
            case 'Clocked Time':
            case 'Working Time':
            case 'Breaks Time':
            case 'Unpaid Overtime':
                include_once 'utils/natural_language.php';

                return seconds_to_string(
                    $this->data['Timesheet '.$key], 'minutes', true
                );


                break;
            case 'IsoDate':
                return $this->data['Timesheet Date'] != '' ? date(
                    "Y-m-d", strtotime($this->data['Timesheet Date'])
                ) : '';


                break;

            case 'Date':
                return $this->data['Timesheet Date'] != '' ? strftime(
                    "%a %e %b %Y", strtotime($this->data['Timesheet Date'])
                ) : '';

                break;


            default:
                if (isset($this->data[$key])) {
                    return $this->data[$key];
                }
                $_key = ucfirst($key);
                if (isset($this->data[$_key])) {
                    return $this->data[$_key];
                }

                return false;

        }


    }

    function update_working_time() {

        $this->update_breaks_time();

        $clocked_in          = false;
        $valid_working_hours = false;
        $working_seconds     = 0;

        $start_date = false;

        $end_working_seconds = 0;

        //'WorkingHoursMark','OvertimeMark','ClockingRecord','BreakMark'
        $sql = sprintf(
            "SELECT `Timesheet Record Action Type`,`Timesheet Record Date`,`Timesheet Record Key`, UNIX_TIMESTAMP(`Timesheet Record Date`) date FROM `Timesheet Record Dimension` WHERE `Timesheet Record Timesheet Key`=%d AND `Timesheet Record Ignored Due Missing End`='No' AND `Timesheet Record Type` IN ('WorkingHoursMark','ClockingRecord','BreakMark') ORDER BY `Timesheet Record Date`,`Timesheet Record Action Type`",
            $this->id
        );
        // print "$sql\n";


        $recording = false;

        $clocked_in      = false;
        $start           = '';
        $working_seconds = 0;

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                //print "-- $start  ||  $clocked_in -- \n";
                //print_r($row);

                if (!$recording and $row['Timesheet Record Action Type'] != 'MarkStart') {

                    if ($row['Timesheet Record Action Type'] == 'Start') {

                        $clocked_in = true;
                    }
                    if ($row['Timesheet Record Action Type'] == 'End') {
                        $clocked_in = false;
                    }
                    continue;
                }

                if ($row['Timesheet Record Action Type'] == 'MarkStart') {


                    $recording = true;


                    $start = $row['date'];


                    continue;
                }


                if ($recording and $row['Timesheet Record Action Type'] == 'MarkEnd') {
                    if ($clocked_in) {

                        $working_seconds += $row['date'] - $start;
                    }
                    $recording = false;
                    continue;

                }

                if ($recording and $row['Timesheet Record Action Type'] == 'Start') {


                    $start      = $row['date'];
                    $clocked_in = true;
                    continue;

                }
                if ($recording and $row['Timesheet Record Action Type'] == 'End') {


                    $working_seconds += $row['date'] - $start;
                    $clocked_in = false;
                    continue;


                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        //print $working_seconds/3600;

        //$working_seconds=$working_seconds-$this->data['Timesheet Breaks Time'];

        $this->update(
            array('Timesheet Working Time' => $working_seconds), 'no_history'
        );


        switch (date('N', strtotime($this->get('Timesheet Date')))) {
            case 1:
                $this->update(
                    array('Timesheet Monday Working Time' => $working_seconds), 'no_history'
                );
                break;
            case 2:
                $this->update(
                    array('Timesheet Tuesday Working Time' => $working_seconds), 'no_history'
                );
                break;
            case 3:
                $this->update(
                    array('Timesheet Wednesday Working Time' => $working_seconds), 'no_history'
                );
                break;
            case 4:
                $this->update(
                    array('Timesheet Thursday Working Time' => $working_seconds), 'no_history'
                );
                break;
            case 5:
                $this->update(
                    array('Timesheet Friday Working Time' => $working_seconds), 'no_history'
                );
                break;
            case 6:
                $this->update(
                    array('Timesheet Saturday Working Time' => $working_seconds), 'no_history'
                );
                break;
            case 7:
                $this->update(
                    array('Timesheet Sunday Working Time' => $working_seconds), 'no_history'
                );
                break;
            default:

                break;
        }


    }

    function update_breaks_time() {
        $clocked_in          = false;
        $valid_working_hours = false;
        $working_seconds     = 0;

        $start_date = false;

        //'WorkingHoursMark','OvertimeMark','ClockingRecord','BreakMark'
        $sql = sprintf(
            "SELECT `Timesheet Record Action Type`,`Timesheet Record Date`,`Timesheet Record Key`, UNIX_TIMESTAMP(`Timesheet Record Date`) date FROM `Timesheet Record Dimension` WHERE `Timesheet Record Timesheet Key`=%d AND `Timesheet Record Ignored Due Missing End`='No' AND `Timesheet Record Type` IN ('ClockingRecord','BreakMark') ORDER BY `Timesheet Record Date`,`Timesheet Record Action Type`",
            $this->id
        );
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Timesheet Record Action Type'] == 'MarkStart') {
                    $valid_working_hours = false;
                } elseif ($row['Timesheet Record Action Type'] == 'MarkEnd') {
                    $valid_working_hours = true;
                } elseif ($row['Timesheet Record Action Type'] == 'Start') {
                    $clocked_in = true;
                } elseif ($row['Timesheet Record Action Type'] == 'End') {
                    $clocked_in = false;
                }

                if ($valid_working_hours and $clocked_in) {

                    if (!$start_date) {
                        $start_date = $row['date'];
                    }

                } elseif (!$valid_working_hours and $clocked_in) {

                    if ($start_date) {

                        $working_seconds = $working_seconds + ($row['date'] - $start_date);
                        $start_date      = false;
                    }

                } elseif ($valid_working_hours and !$clocked_in) {

                    if ($start_date) {

                        $working_seconds = $working_seconds + ($row['date'] - $start_date);
                        $start_date      = false;
                    }

                }

                //print_r($row);
                //print "v wh $valid_working_hours, in: $clocked_in ; $start_date -> ".($working_seconds/3600)." \n";


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }
        $this->update(
            array('Timesheet Breaks Time' => $working_seconds), 'no_history'
        );


        switch (date('N', strtotime($this->get('Timesheet Date')))) {
            case 1:
                $this->update(
                    array('Timesheet Monday Breaks Time' => $working_seconds), 'no_history'
                );
                break;
            case 2:
                $this->update(
                    array('Timesheet Tuesday Breaks Time' => $working_seconds), 'no_history'
                );
                break;
            case 3:
                $this->update(
                    array('Timesheet Wednesday Breaks Time' => $working_seconds), 'no_history'
                );
                break;
            case 4:
                $this->update(
                    array('Timesheet Thursday Breaks Time' => $working_seconds), 'no_history'
                );
                break;
            case 5:
                $this->update(
                    array('Timesheet Friday Breaks Time' => $working_seconds), 'no_history'
                );
                break;
            case 6:
                $this->update(
                    array('Timesheet Saturday Breaks Time' => $working_seconds), 'no_history'
                );
                break;
            case 7:
                $this->update(
                    array('Timesheet Sunday Breaks Time' => $working_seconds), 'no_history'
                );
                break;
            default:

                break;
        }


    }

    function update_unpaid_overtime() {

        $clocked_seconds = 0;
        $action_type     = '';

        $maarked_start = false;

        //'WorkingHoursMark','OvertimeMark','ClockingRecord','BreakMark'
        $sql = sprintf(
            "SELECT `Timesheet Record Ignored`,`Timesheet Record Ignored Due Missing End`,`Timesheet Record Action Type`,`Timesheet Record Date`,`Timesheet Record Key`, UNIX_TIMESTAMP(`Timesheet Record Date`) date FROM `Timesheet Record Dimension` WHERE `Timesheet Record Timesheet Key`=%d AND `Timesheet Record Ignored Due Missing End`='No' AND `Timesheet Record Type` IN ('ClockingRecord','WorkingHoursMark') ORDER BY `Timesheet Record Date`,`Timesheet Record Action Type`",
            $this->id
        );
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                //print_r($row);
                if ($row['Timesheet Record Action Type'] == 'Start') {

                    $action_type = 'Start';
                    $start_date  = $row['date'];
                } elseif ($row['Timesheet Record Action Type'] == 'End') {

                    if ($start_date) {
                        $end_date        = $row['date'];
                        $clocked_seconds = $clocked_seconds + ($end_date - $start_date);
                    }
                    $action_type = 'End';

                } elseif ($row['Timesheet Record Action Type'] == 'MarkStart') {
                    $maarked_start = true;;


                    if ($action_type == 'Start') {
                        $end_date = $row['date'];


                        $clocked_seconds = $clocked_seconds + ($end_date - $start_date);

                    }

                    break;

                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }
        //print "start ".($clocked_seconds/60)." \n";
        //print "++++++++++++++\n";

        if ($maarked_start) {
            $action_type = '';
            $sql         = sprintf(
                "SELECT  `Timesheet Record Ignored`,`Timesheet Record Ignored Due Missing End`,`Timesheet Record Action Type`,`Timesheet Record Date`,`Timesheet Record Key`, UNIX_TIMESTAMP(`Timesheet Record Date`) date FROM `Timesheet Record Dimension` WHERE `Timesheet Record Timesheet Key`=%d AND `Timesheet Record Ignored Due Missing End`='No'  AND `Timesheet Record Type` IN ('ClockingRecord','WorkingHoursMark') ORDER BY `Timesheet Record Date` DESC,`Timesheet Record Action Type` DESC",
                $this->id
            );
            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {

                    //print_r($row);
                    if ($row['Timesheet Record Action Type'] == 'End') {

                        $action_type = 'Start';
                        $start_date  = $row['date'];
                    } elseif ($row['Timesheet Record Action Type'] == 'Start') {

                        if ($start_date) {
                            $end_date        = $row['date'];
                            $clocked_seconds = $clocked_seconds - ($end_date - $start_date);
                        }
                        $action_type = 'End';

                    } elseif ($row['Timesheet Record Action Type'] == 'MarkEnd') {


                        if ($action_type == 'Start') {
                            $end_date = $row['date'];


                            $clocked_seconds = $clocked_seconds - ($end_date - $start_date);

                        }

                        break;

                    }


                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }

        }

        //print "v ".($clocked_seconds/60)." \n";
        $this->update(
            array('Timesheet Unpaid Overtime' => $clocked_seconds), 'no_history'
        );


        switch (date('N', strtotime($this->get('Timesheet Date')))) {
            case 1:
                $this->update(
                    array('Timesheet Monday Unpaid Overtime' => $clocked_seconds), 'no_history'
                );
                break;
            case 2:
                $this->update(
                    array('Timesheet Tuesday Unpaid Overtime' => $clocked_seconds), 'no_history'
                );
                break;
            case 3:
                $this->update(
                    array('Timesheet Wednesday Unpaid Overtime' => $clocked_seconds), 'no_history'
                );
                break;
            case 4:
                $this->update(
                    array('Timesheet Thursday Unpaid Overtime' => $clocked_seconds), 'no_history'
                );
                break;
            case 5:
                $this->update(
                    array('Timesheet Friday Unpaid Overtime' => $clocked_seconds), 'no_history'
                );
                break;
            case 6:
                $this->update(
                    array('Timesheet Saturday Unpaid Overtime' => $clocked_seconds), 'no_history'
                );
                break;
            case 7:
                $this->update(
                    array('Timesheet Sunday Unpaid Overtime' => $clocked_seconds), 'no_history'
                );
                break;
            default:

                break;
        }

    }

    function get_field_label($field) {

        switch ($field) {

            case 'Timesheet Clocked Time':
                $label = _('Clocked');
                break;
            case 'Timesheet Working Time':
                $label = _('Clocked (working hours)');
                break;
            case 'Timesheet Breaks Time':
                $label = _('Breaks');
                break;
            case 'Timesheet Unpaid Overtime':
                $label = _('Overtime (unpaid)');
                break;
            case 'Timesheet Paid Overtime':
                $label = _('Overtime (paid)');
                break;
            case 'Timesheet Date':
                $label = _('date');
                break;
            default:
                $label = $field;

        }

        return $label;

    }

    function get_staff_data() {

        $sql = sprintf(
            'SELECT * FROM `Staff Dimension` WHERE  `Staff Key`=%d ', $this->data['Timesheet Staff Key']
        );
        if ($row = $this->db->query($sql)->fetch()) {

            foreach ($row as $key => $value) {
                $this->data['Timesheet '.$key] = $value;
            }
        }


    }

    function update_type() {
        $number_records = 0;

        $sql = sprintf(
            'SELECT count(*) num  FROM `Timesheet Record Dimension` WHERE `Timesheet Record Timesheet Key`=%d AND `Timesheet Record Type`="WorkingHoursMark"   ', $this->id
        );

        if ($result = $this->db->query($sql)) {

            if ($row = $result->fetch()) {

                $number_records = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;

        }

        if ($number_records >= 2) {
            $type = 'WorkingDay';
        } else {
            $type = 'DayOff';
        }

        $this->update(array('Timesheet Type' => $type), 'no_history');


    }

    function update_number_records($type) {

        $number_records = 0;

        $sql = sprintf(
            'SELECT count(*) num  FROM `Timesheet Record Dimension` WHERE `Timesheet Record Timesheet Key`=%d AND `Timesheet Record Type`=%s   ', $this->id, prepare_mysql($type)
        );

        if ($result = $this->db->query($sql)) {

            if ($row = $result->fetch()) {

                $number_records = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;

        }

        if ($type == 'WorkingHoursMark') {
            $this->update(
                array('Timesheet Working Hours Records' => $number_records), 'no_history'
            );

        } elseif ($type == 'OvertimeMark') {
            $this->update(
                array('Timesheet Overtime Records' => $number_records), 'no_history'
            );

        } elseif ($type == 'BreakMark') {
            $this->update(
                array('Timesheet Break Records' => $number_records), 'no_history'
            );

        }


    }

    function process_mark_records_action_type() {

        $action_type = 'MarkStart';

        $sql = sprintf(
            'SELECT `Timesheet Record Date`,`Timesheet Record Key` FROM `Timesheet Record Dimension` WHERE `Timesheet Record Timesheet Key`=%d AND `Timesheet Record Ignored`="No"  AND `Timesheet Record Type` IN ("WorkingHoursMark","BreakMark") ORDER BY `Timesheet Record Date`',
            $this->id

        );

        if ($result = $this->db->query($sql)) {

            foreach ($result as $row) {


                $sql = sprintf(
                    "UPDATE `Timesheet Record Dimension` SET `Timesheet Record Action Type`=%s WHERE `Timesheet Record Key`=%d  ", prepare_mysql($action_type), $row['Timesheet Record Key']
                );
                $this->db->exec($sql);

                if ($action_type == 'MarkStart') {
                    $action_type = 'MarkEnd';
                } else {
                    $action_type = 'MarkStart';

                }

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }


    function remove_records($type) {

        $sql = sprintf(
            'DELETE  FROM `Timesheet Record Dimension` WHERE `Timesheet Record Timesheet Key`=%d AND `Timesheet Record Type`=%s ', $this->id, prepare_mysql($type)
        );

        $this->db->exec($sql);

    }


}
