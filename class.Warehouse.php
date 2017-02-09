<?php
/*
 File: Warehouse.php

 This file contains the Warehouse Class

 About:
 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';
include_once 'class.WarehouseArea.php';
include_once 'class.Location.php';

class Warehouse extends DB_Table {

    var $areas = false;
    var $locations = false;

    function Warehouse($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Warehouse';
        $this->ignore_fields = array('Warehouse Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } elseif ($a1 == 'find') {
            $this->find($a2, $a3);

        } else {
            $this->get_data($a1, $a2);
        }
    }


    function get_data($key, $tag) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Warehouse Dimension` WHERE `Warehouse Key`=%d", $tag
            );
        } else {
            if ($key == 'code') {
                $sql = sprintf(
                    "SELECT  * FROM `Warehouse Dimension` WHERE `Warehouse Code`=%s ", prepare_mysql($tag)
                );
            } else {
                if ($key == 'name') {
                    $sql = sprintf(
                        "SELECT  *  FROM `Warehouse Dimension` WHERE `Warehouse Name`=%s ", prepare_mysql($tag)
                    );
                } else {
                    return;
                }
            }
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id   = $this->data['Warehouse Key'];
            $this->code = $this->data['Warehouse Code'];
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

        $this->found     = false;
        $this->found_key = false;

        $create = '';
        $update = '';
        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = _trim($value);
            }
        }


        if ($data['Warehouse Code'] == '') {
            $this->error = true;
            $this->msg   = 'Warehouse code empty';

            return;
        }

        if ($data['Warehouse Name'] == '') {
            $data['Warehouse Name'] = $data['Warehouse Code'];
        }


        $sql = sprintf(
            "SELECT `Warehouse Key` FROM `Warehouse Dimension` WHERE `Warehouse Code`=%s  ", prepare_mysql($data['Warehouse Code'])
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $this->found     = true;
                $this->found_key = $row['Warehouse Key'];
                $this->get_data('id', $this->found_key);
                $this->duplicated_field = 'Warehouse Code';

                return;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }
        $sql = sprintf(
            "SELECT `Warehouse Key` FROM `Warehouse Dimension` WHERE `Warehouse Name`=%s  ", prepare_mysql($data['Warehouse Name'])
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $this->found     = true;
                $this->found_key = $row['Warehouse Key'];
                $this->get_data('id', $this->found_key);
                $this->duplicated_field = 'Warehouse Name';

                return;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if ($create and !$this->found) {
            $this->create($data);

            return;
        }


    }

    function create($data) {
        $this->new = false;
        $base_data = $this->base_data();

        foreach ($data as $key => $value) {
            if (array_key_exists($key, $base_data)) {
                $base_data[$key] = _trim($value);
            }
        }

        $keys   = '(';
        $values = 'values(';
        foreach ($base_data as $key => $value) {
            $keys .= "`$key`,";
            if (preg_match(
                '/^(Warehouse Address|Warehouse Company Name|Warehouse Company Number|Warehouse VAT Number|Warehouse Telephone|Warehouse Email)$/i', $key
            )) {
                $values .= prepare_mysql($value, false).",";
            } else {
                $values .= prepare_mysql($value).",";
            }
        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);
        $sql    = sprintf(
            "INSERT INTO `Warehouse Dimension` %s %s", $keys, $values
        );

        if ($this->db->exec($sql)) {
            $this->id  = $this->db->lastInsertId();
            $this->msg = _("Warehouse Added");
            $this->get_data('id', $this->id);
            $this->new = true;


            if (is_numeric($this->editor['User Key']) and $this->editor['User Key'] > 1) {

                $sql = sprintf(
                    "INSERT INTO `User Right Scope Bridge` VALUES(%d,'Warehouse',%d)", $this->editor['User Key'], $this->id
                );
                $this->db->exec($sql);

            }


            $flags = array(
                'Blue'   => _('Blue'),
                'Green'  => _('Green'),
                'Orange' => _('Orange'),
                'Pink'   => _('Pink'),
                'Purple' => _('Purple'),
                'Red'    => _('Red'),
                'Yellow' => _('Yellow')
            );
            foreach ($flags as $flag => $flag_label) {
                $sql = sprintf(
                    "INSERT INTO `Warehouse Flag Dimension` (`Warehouse Flag Key`, `Warehouse Key`, `Warehouse Flag Color`, `Warehouse Flag Label`, `Warehouse Flag Number Locations`, `Warehouse Flag Active`) VALUES (NULL, %d, %s,%s, '0', 'Yes')",
                    $this->id, prepare_mysql($flag), prepare_mysql($flag_label)
                );

                $this->db->exec($sql);

            }


            return;
        } else {
            $this->msg = _(" Error can not create warehouse");
            print $sql;
            exit;
        }
    }

    function create_category($raw_data) {

        if (!isset($raw_data['Category Label']) or $raw_data['Category Label'] == '') {
            $raw_data['Category Label'] = $raw_data['Category Code'];
        }

        if (!isset($raw_data['Category Locked']) or $raw_data['Category Locked'] == '') {
            $raw_data['Category Locked'] = 'No';
        }

        $data = array(
            'Category Code'           => $raw_data['Category Code'],
            'Category Label'          => $raw_data['Category Label'],
            'Category Scope'          => 'Location',
            'Category Subject'        => $raw_data['Category Subject'],
            'Category Warehouse Key'  => $this->id,
            'Category Can Have Other' => $raw_data['Category Locked'],
            'Category Locked'         => 'No',
            'Category Branch Type'    => 'Root',
            'editor'                  => $this->editor

        );

        $category = new Category('find create', $data);


        if ($category->id) {
            $this->new_category_msg = $category->msg;

            if ($category->new) {
                $this->new_category = true;

            } else {
                $this->error = true;
                $this->msg   = $category->msg;

            }

            return $category;
        } else {
            $this->error = true;
            $this->msg   = $category->msg;
        }

    }

    function create_location($data) {

        $this->new_product = false;

        $data['editor'] = $this->editor;


        //print_r($data);

        if (!isset($data['Location Code']) or $data['Location Code'] == '') {
            $this->error      = true;
            $this->msg        = _("Location missing");
            $this->error_code = 'location_code_missing';
            $this->metadata   = '';

            return;
        }

        $sql = sprintf(
            'SELECT count(*) AS num FROM `Location Dimension` WHERE `Location Code`=%s AND `Location Warehouse Key`=%d  ', prepare_mysql($data['Location Code']), $this->id

        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                if ($row['num'] > 0) {
                    $this->error      = true;
                    $this->msg        = sprintf(
                        _('Duplicated code (%s)'), $data['Location Code']
                    );
                    $this->error_code = 'duplicate_location_code_reference';
                    $this->metadata   = $data['Location Code'];

                    return;
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if (!isset($data['Location Mainly Used For']) or $data['Location Mainly Used For'] == '') {


            $this->error      = true;
            $this->msg        = _('Location used for missing');
            $this->error_code = 'location_mainly_used_for_missing';

            return;
        }

        if (!in_array(
            $data['Location Mainly Used For'], array(
                                                 'Picking',
                                                 'Storing',
                                                 'Loading',
                                                 'Displaying',
                                                 'Other'
                                             )
        )
        ) {


            $this->error      = true;
            $this->msg        = _('Location used for not valid');
            $this->error_code = 'location_mainly_used_for_missing_not_valid';

            return;
        }


        $data['Location Warehouse Key'] = $this->id;


        $location = new Location('find', $data, 'create');


        if ($location->id) {
            $this->new_object_msg = $location->msg;

            if ($location->new) {
                $this->new_object    = true;
                $this->new_locationt = true;
                $this->update_children();


            } else {

                $this->error = true;
                if ($location->found) {

                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(
                        array($location->duplicated_field)
                    );

                    if ($location->duplicated_field == 'Location Code') {
                        $this->msg = _("Duplicated location code");
                    } else {
                        $this->msg = 'Duplicated '.$location->duplicated_field;
                    }


                } else {
                    $this->msg = $location->msg;
                }
            }

            return $location;
        } else {

            $this->error = true;
            $this->msg   = $location->msg;

        }

    }

    function update_children() {


        $number_locations                  = 0;
        $number_part_locations             = 0;
        $number_part_locations_with_errors = 0;

        $pending_orders                         = 0;
        $pending_orders_with_missing_pick_stock = 0;

        $sql = sprintf('SELECT count(*) AS number FROM `Location Dimension` WHERE `Location Warehouse Key`=%d', $this->id);


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_locations = $row['number'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        $sql = sprintf('SELECT count(*) AS number  , sum(if(`Quantity On Hand`<0,1,0) ) AS errors FROM `Part Location Dimension` WHERE `Part Location Warehouse Key`=%d', $this->id);


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_part_locations             = $row['number'];
                $number_part_locations_with_errors = $row['errors'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        /*
        $sql=sprintf('select count(*) as number from `Shelf Dimension` where `Shelf Warehouse Key`=%d', $this->id);
        $number_shelfs=0;


        if ($result=$this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_shelfs=$row['number'];

            }
        }else {
            print_r($error_info=$this->db->errorInfo());
            exit;
        }

*/
        /*

        $sql=sprintf('select count(*) as number from `Warehouse Area Dimension` where `Warehouse Key`=%d', $this->id);
        $number_areas=0;

        if ($result=$this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_areas=$row['number'];

            }
        }else {
            print_r($error_info=$this->db->errorInfo());
            exit;
        }

*/


        $this->update(
            array(
                'Warehouse Number Locations'      => $number_locations,
                'Warehouse Part Locations'        => $number_part_locations,
                'Warehouse Part Locations Errors' => $number_part_locations_with_errors
            ), 'no_history'
        );


    }

    function get($key, $data = false) {

        if (!$this->id) {
            return '';
        }


        switch ($key) {
            case('num_areas'):
            case('number_areas'):
                if (!$this->areas) {
                    $this->load('areas');
                }

                return count($this->areas);
                break;
            case('areas'):
                if (!$this->areas) {
                    $this->load('areas');
                }

                return $this->areas;
                break;
            case('area'):
                if (!$this->areas) {
                    $this->load('areas');
                }
                if (isset($this->areas[$data['id']])) {
                    return $this->areas[$data['id']];
                }
                break;
            default:


                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Warehouse '.$key, $this->data)) {
                    return $this->data['Warehouse '.$key];
                }


                if (preg_match(
                    '/(Warehouse )?Flag Label (.+)$/', $key, $match
                )) {

                    if (isset($this->flags[$match[2]])) {
                        return $this->flags[$match[2]]['Warehouse Flag Label'];
                    }
                }


        }

        return '';
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

                if (preg_match(
                    '/(Warehouse Flag Label) (.+)$/', $field, $match
                )) {


                    $this->update_flag($match[2], $match[1], $value);


                }


        }
    }

    function update_flag($flag_key, $field, $value) {

        if (in_array(
            $field, array(
                      'Warehouse Flag Label',
                      'Warehouse Flag Active'
                  )
        )) {


            $sql = sprintf(
                "SELECT * FROM  `Warehouse Flag Dimension` WHERE  `Warehouse Flag Key`=%d AND `Warehouse Key`=%d", $flag_key, $this->id
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {


                    $default_flag_key = $this->get_default_flag_key();
                    if ($default_flag_key == $value and $field == 'Warehouse Flag Active' and $value == 'No') {
                        $this->error = true;
                        $this->msg   = 'can not disable default flag';
                    }


                    $sql = sprintf(
                        "UPDATE  `Warehouse Flag Dimension`  SET `%s`=%s WHERE `Warehouse Flag Key`=%d ", $field, prepare_mysql($value), $flag_key

                    );
                    $this->db->exec($sql);


                    if ($field == 'Warehouse Flag Active' and $value == 'No') {
                        $sql = sprintf(
                            "SELECT `Location Key` FROM `Location Dimension` WHERE `Location Warehouse Key`=%d AND `Warehouse Flag Key`=%d", $this->id, $row['Warehouse Flag Key']

                        );

                        if ($result2 = $this->db->query($sql)) {
                            foreach ($result2 as $row2) {
                                $location = new Location($row2['Location Key']);
                                $location->update_warehouse_flag_key(
                                    $default_flag_key
                                );
                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            exit;
                        }


                    }


                    $this->updated   = true;
                    $this->new_value = $value;

                    $this->get_flags_data();

                } else {
                    $this->error = true;
                    $this->msg   = 'unknown flag';
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


        } else {
            $this->error = true;
            $this->msg   = 'unknown field';
        }

    }

    function get_default_flag_key() {
        $flag_key = 0;
        $sql      = sprintf(
            "SELECT `Warehouse Flag Key` FROM  `Warehouse Flag Dimension` WHERE `Warehouse Flag Color`=%s AND `Warehouse Key`=%d", prepare_mysql($this->data['Warehouse Default Flag Color']), $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $flag_key = $row['Warehouse Flag Key'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $flag_key;

    }

    function get_flags_data() {
        $this->flags = array();
        $sql         = sprintf(
            "SELECT * FROM `Warehouse Flag Dimension` WHERE `Warehouse Key`=%d", $this->id
        );
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $this->flags[$row['Warehouse Flag Key']] = $row;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }

    function add_area($data) {
        // print_r($data);
        $this->new_area        = false;
        $data['Warehouse Key'] = $this->id;
        $area                  = new WarehouseArea('find', $data, 'create');
        $this->new_area_msg    = $area->msg;
        if ($area->new) {
            $this->new_area     = true;
            $this->new_area_key = $area->id;
        }
    }

    function update_inventory_snapshot($from, $to = false) {

        if (!$to) {
            $to = $from;
        }

        $sql = sprintf(
            "SELECT `Date`  FROM kbase.`Date Dimension` WHERE `Date`>=%s AND `Date` <= %s  ", prepare_mysql($from), prepare_mysql($to)
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                $dormant_1y_open_value_at_day = 0;

                $sql = sprintf(
                    'SELECT ITF.`Part SKU`,`Part Cost`,`Value At Day Cost` FROM `Inventory Spanshot Fact` ITF LEFT JOIN `Part Dimension` P  ON (P.`Part SKU`=ITF.`Part SKU`)WHERE `Warehouse Key`=%d AND `Date`=%s AND `Value At Day Cost`!=0 AND `Part Valid From`>%s',
                    $this->id, prepare_mysql($row['Date']), prepare_mysql(
                        date(
                            "Y-m-d H:i:s", strtotime($row['Date'].' 23:59:59 -1 year')
                        )
                    )
                );


                if ($result2 = $this->db->query($sql)) {
                    foreach ($result2 as $row2) {

                        $sql = sprintf(
                            "SELECT count(*) AS num FROM `Inventory Transaction Fact` WHERE `Part SKU`=%d AND  `Inventory Transaction Type`='Sale' AND `Date`>=%s AND `Date`<=%s ", $row2['Part SKU'],
                            prepare_mysql(
                                date(
                                    "Y-m-d H:i:s", strtotime($row['Date'].' 23:59:59 -1 year')
                                )
                            ), prepare_mysql($row['Date'].' 23:59:59')
                        );


                        if ($result3 = $this->db->query($sql)) {
                            if ($row3 = $result3->fetch()) {
                                if ($row3['num'] == 0) {
                                    $dormant_1y_open_value_at_day += $row2['Value At Day Cost'];
                                }
                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            exit;
                        }


                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


                $sql = sprintf(
                    "SELECT `Date`,
			count(DISTINCT `Part SKU`) AS parts,`Date`, count(DISTINCT `Location Key`) AS locations,
			sum(`Value At Cost Open`) AS open ,sum(`Value At Cost High`) AS high,sum(`Value At Cost Low`) AS low,sum(`Value At Cost`) AS close ,
			sum(`Value At Day Cost Open`) AS open_value_at_day ,sum(`Value At Day Cost High`) AS high_value_at_day,sum(`Value At Day Cost Low`) AS low_value_at_day,sum(`Value At Day Cost`) AS close_value_at_day,
			sum(`Value Commercial Open`) AS open_commercial_value ,sum(`Value Commercial High`) AS high_commercial_value,sum(`Value Commercial Low`) AS low_commercial_value,sum(`Value Commercial`) AS close_commercial_value
			FROM `Inventory Spanshot Fact` WHERE `Warehouse Key`=%d AND `Date`=%s", $this->id, prepare_mysql($row['Date'])

                );


                if ($result2 = $this->db->query($sql)) {
                    if ($row2 = $result2->fetch()) {


                        $sql = sprintf(
                            "INSERT INTO `Inventory Warehouse Spanshot Fact` (`Date`,`Warehouse Key`,`Parts`,`Locations`,
				`Value At Cost`,`Value At Day Cost`,`Value Commercial`,`Value At Cost Open`,`Value At Cost High`,`Value At Cost Low`,`Value At Day Cost Open`,`Value At Day Cost High`,`Value At Day Cost Low`,
				`Value Commercial Open`,`Value Commercial High`,`Value Commercial Low`,`Dormant 1 Year Value At Day Cost`

				) VALUES (%s,%d,%.2f,%.2f,%.2f, %f,%f,%f,%f,%f,%f,%f,%f,%f,%d,%d,%.2f) ON DUPLICATE KEY UPDATE
					`Value At Cost`=%.2f, `Value At Day Cost`=%.2f,`Value Commercial`=%.2f,
			`Value At Cost Open`=%f,`Value At Cost High`=%f,`Value At Cost Low`=%f,
			`Value At Day Cost Open`=%f,`Value At Day Cost High`=%f,`Value At Day Cost Low`=%f,
			`Value Commercial Open`=%f,`Value Commercial High`=%f,`Value Commercial Low`=%f,
			`Parts`=%d,`Locations`=%d,`Dormant 1 Year Value At Day Cost`=%.2f
			", prepare_mysql($row['Date']),

                            $this->id, $row2['parts'], $row2['locations'],

                            $row2['close'], $row2['close_value_at_day'], $row2['close_commercial_value'], $row2['open'], $row2['high'], $row2['low'],

                            $row2['open_value_at_day'], $row2['high_value_at_day'], $row2['low_value_at_day'], $row2['open_commercial_value'], $row2['high_commercial_value'],
                            $row2['low_commercial_value'], $dormant_1y_open_value_at_day,

                            $row2['close'], $row2['close_value_at_day'], $row2['close_commercial_value'], $row2['open'], $row2['high'], $row2['low'],

                            $row2['open_value_at_day'], $row2['high_value_at_day'], $row2['low_value_at_day'], $row2['open_commercial_value'], $row2['high_commercial_value'],
                            $row2['low_commercial_value'], $row2['parts'], $row2['locations'], $dormant_1y_open_value_at_day


                        );
                        $this->db->exec($sql);

                        // print "$sql\n";


                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }

    function update_location_flags_numbers() {


        $sql = sprintf(
            "SELECT * FROM  `Warehouse Flag Dimension` WHERE `Warehouse Key`=%d  ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $this->update_location_flag_number($row['Warehouse Flag Key']);

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }

    function update_location_flag_number($flag_key) {
        $num = 0;
        $sql = sprintf(
            "SELECT count(*) AS num  FROM  `Location Dimension` WHERE `Warehouse Flag Key`=%d ", $flag_key
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $num = $row['num'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "UPDATE  `Warehouse Flag Dimension`  SET `Warehouse Flag Number Locations`=%d WHERE `Warehouse Flag Key`=%d ", $num, $flag_key
        );
        $this->db->exec($sql);


    }

    function get_field_label($field) {

        switch ($field) {

            case 'Warehouse Code':
                $label = _('code');
                break;
            case 'Warehouse Name':
                $label = _('name');
                break;
            case 'Warehouse Address':
                $label = _('address');
                break;

            default:

                if (preg_match(
                    '/Warehouse Flag Label (.+)$/', $field, $match
                )) {
                    $label = '<i class="fa fa-flag '.strtolower($match[1]).'" aria-hidden="true"></i> '._($match[1]);

                    return $label;
                }

                $label = $field;

        }

        return $label;

    }

    function get_kpi($interval) {

        global $account;

        include_once 'utils/date_functions.php';
        list($db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb) = calculate_interval_dates($this->db, $interval);

        // print "$db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb \n";


        $sql = sprintf(
            'SELECT sum(`Timesheet Warehouse Clocked Time`) AS seconds FROM `Timesheet Dimension` WHERE `Timesheet Date`>=%s AND `Timesheet Date`<=%s ', prepare_mysql($from_date),
            prepare_mysql($to_date)
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $hrs = $row['seconds'] / 3600;
            } else {
                $hrs = 0;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if ($to_date == gmdate('Y-m-d 23:59:59')) {
            include_once 'class.Timesheet.php';

            $sql = sprintf(
                "SELECT `Timesheet Key` FROM `Timesheet Dimension` T LEFT JOIN `Staff Role Bridge` B ON (B.`Staff Key`=`Timesheet Staff Key`) WHERE `Role Code` IN ('PICK','WAHSC') AND `Timesheet Date`=%s ",
                prepare_mysql(gmdate('Y-m-d'))
            );
            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $timesheet = new Timesheet($row['Timesheet Key']);
                    $hrs += $timesheet->get_clocked_open_jaw_time() / 3600;
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


        }


        // print $sql;

        $sql = sprintf(
            'SELECT sum(`Delivery Note Invoiced Net DC Amount`) AS amount FROM `Delivery Note Dimension`   WHERE `Delivery Note Date`>=%s AND `Delivery Note Date`<=%s  AND `Delivery Note State`="Dispatched" ',
            prepare_mysql($from_date), prepare_mysql($to_date)
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $amount = $row['amount'];
            } else {
                $amount = 0;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        //    print $sql;
        return array(
            'kpi'              => $amount / $hrs,
            'amount'           => $amount,
            'hrs'              => $hrs,
            'formatted_kpi'    => number($amount / $hrs, 2).' '.currency_symbol($account->get('Account Currency')).'/h',
            'formatted_amount' => money($amount, $account->get('Account Currency')),
            'formatted_hrs'    => sprintf('%d hours', number($hrs, 1)),
        );


    }


    function update_paid_ordered_parts() {

        $paid_ordered_parts                               = 0;
        $to_replenish_picking_location_paid_ordered_parts = 0;


        $production_suppliers = '';
        $sql                  = sprintf('SELECT group_concat(`Supplier Production Supplier Key`) AS  production_suppliers FROM `Supplier Production Dimension`');
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $production_suppliers = $row['production_suppliers'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $sql = sprintf(
            'SELECT count(DISTINCT P.`Part SKU`) AS num FROM 
              `Part Dimension` P LEFT JOIN `Part Location Dimension` PL ON (PL.`Part SKU`=P.`Part SKU`) 
              WHERE  `Part Location Warehouse Key`=%d', $this->id
        );
        //print $sql;
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $paid_ordered_parts = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if ($production_suppliers != '') {

            $sql = sprintf(
                'SELECT count(DISTINCT P.`Part SKU`) AS num FROM 
              `Part Dimension` P LEFT JOIN `Part Location Dimension` PL ON (PL.`Part SKU`=P.`Part SKU`)  LEFT JOIN `Supplier Part Dimension` SP ON (SP.`Supplier Part Part SKU`=P.`Part SKU`) 
              WHERE (`Part Current Stock In Process`+ `Part Current Stock Ordered Paid`)>`Quantity On Hand`   AND (`Part Current Stock In Process`+ `Part Current Stock Ordered Paid`)>0   AND `Part Location Warehouse Key`=%d AND `Can Pick`="Yes"   AND `Supplier Part Supplier Key` NOT IN (%s) ',
                $this->id, $production_suppliers
            );

        } else {
            $sql = sprintf(
                'SELECT count(DISTINCT P.`Part SKU`) AS num FROM 
              `Part Dimension` P LEFT JOIN `Part Location Dimension` PL ON (PL.`Part SKU`=P.`Part SKU`) 
              WHERE (`Part Current Stock In Process`+ `Part Current Stock Ordered Paid`)>`Quantity On Hand`  AND (`Part Current Stock In Process`+ `Part Current Stock Ordered Paid`)>0    AND `Part Location Warehouse Key`=%d AND `Can Pick`="Yes" ',
                $this->id
            );


        }

        //print $sql;
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $to_replenish_picking_location_paid_ordered_parts = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $this->update(
            array(
                'Warehouse Paid Ordered Parts'              => $paid_ordered_parts,
                'Warehouse Paid Ordered Parts To Replenish' => $to_replenish_picking_location_paid_ordered_parts

            ), 'no_history'
        );


    }


}


?>
