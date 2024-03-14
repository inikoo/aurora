<?php
/*
 File: Warehouse.php

 This file contains the Warehouse Class

 About:
 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

use Elasticsearch\ClientBuilder;

include_once 'class.DB_Table.php';

class Warehouse extends DB_Table {

    /**
     * @var \PDO
     */
    public $db;


    function __construct($a1, $a2 = false, $a3 = false) {

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
            $sql = "SELECT * FROM `Warehouse Dimension` WHERE `Warehouse Key`=?";
        } elseif ($key == 'code') {
            $sql = "SELECT * FROM `Warehouse Dimension` WHERE `Warehouse Code`=?";
        } elseif ($key == 'name') {
            $sql = "SELECT * FROM `Warehouse Dimension` WHERE `Warehouse Name`=?";
        } else {
            return;
        }


        $stmt = $this->db->prepare($sql);
        if ($stmt->execute(
            array(
                $tag
            )
        )) {
            if ($row = $stmt->fetch()) {
                $this->data = $row;
                $this->id   = $this->data['Warehouse Key'];
                $this->code = $this->data['Warehouse Code'];

                $this->properties = json_decode($this->data['Warehouse Properties'], true);
                $this->settings   = json_decode($this->data['Warehouse Settings'], true);


            }
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

        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        $data = $raw_data;
        unset($data['editor']);


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
        }


        if ($create and !$this->found) {
            $this->create($data);

        }


    }

    function create($data) {


        $this->new = false;


        $sql = sprintf(
            "INSERT INTO `Warehouse Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($data)).'`', join(',', array_fill(0, count($data), '?'))
        );

        $stmt = $this->db->prepare($sql);


        $i = 1;
        foreach ($data as $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {


            $this->id  = $this->db->lastInsertId();
            $this->msg = _("Warehouse added");
            $this->get_data('id', $this->id);
            $this->new = true;


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
                    "INSERT INTO `Warehouse Flag Dimension` ( `Warehouse Flag Warehouse Key`, `Warehouse Flag Color`, `Warehouse Flag Label`, `Warehouse Flag Number Locations`, `Warehouse Flag Active`) VALUES ( %d, %s,%s, '0', 'Yes')", $this->id, prepare_mysql($flag),
                    prepare_mysql($flag_label)
                );

                $this->db->exec($sql);

            }


            $unknown_location = $this->create_location(
                array(
                    'Location Code' => 'Unknown',
                    'editor'        => $this->editor
                )
            );


            $this->fast_update(
                array(
                    'Warehouse Unknown Location Key' => $unknown_location->id,
                    'Warehouse Properties'           => '{}',
                    'Warehouse Settings'             => '{}',
                )

            );


        } else {
            print_r($stmt->errorInfo());

            $this->msg = _(" Error can not create warehouse");
            print $sql;
            exit;
        }
    }

    function create_location($data) {

        include_once 'class.Location.php';


        $this->new_product = false;

        $data['editor'] = $this->editor;


        //print_r($data);

        if (!isset($data['Location Code']) or $data['Location Code'] == '') {
            $this->error      = true;
            $this->msg        = _("Location missing");
            $this->error_code = 'location_code_missing';
            $this->metadata   = '';

            return false;
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

                    return false;
                }
            }
        }


        if (!empty($data['Location Flag Color'])) {


            $sql = sprintf(
                "SELECT `Warehouse Flag Key` FROM  `Warehouse Flag Dimension` WHERE `Warehouse Flag Color`=%s", prepare_mysql(ucfirst($data['Location Flag Color']))
            );

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {

                    $data['Location Warehouse Flag Key'] = $row['Warehouse Flag Key'];

                } else {
                    $this->error      = true;
                    $this->msg        = _('Flag invalid colour');
                    $this->error_code = 'location_flag_color_not_valid';

                }
            }
        }

        if (!empty($data['Warehouse Area Code'])) {


            include_once 'class.WarehouseArea.php';
            $warehouse_area = new WarehouseArea('warehouse_code', $this->id, $data['Warehouse Area Code']);


            if ($warehouse_area->id) {
                $data['Location Warehouse Area Key'] = $warehouse_area->id;
            }

            unset($data['Warehouse Area Code']);


        }


        $data['Location Warehouse Key'] = $this->id;


        $location = new Location('find', $data, 'create');


        if ($location->id) {
            $this->new_object_msg = $location->msg;

            if ($location->new) {
                $this->new_object    = true;
                $this->new_locationt = true;

                if ($location->get('Location Warehouse Flag Key')) {
                    $this->update_location_flag_number($location->get('Location Warehouse Flag Key'));
                }

                $this->update_warehouse_aggregations();


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

            return false;
        }

    }

    function update_location_flag_number($flag_key) {
        $num = 0;
        $sql = sprintf(
            "SELECT count(*) AS num  FROM  `Location Dimension` WHERE `Location Warehouse Flag Key`=%d ", $flag_key
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $num = $row['num'];

            }
        }


        $sql = sprintf(
            "UPDATE  `Warehouse Flag Dimension`  SET `Warehouse Flag Number Locations`=%d WHERE `Warehouse Flag Key`=%d ", $num, $flag_key
        );
        $this->db->exec($sql);


    }

    function update_warehouse_aggregations() {


        $number_locations                  = 0;
        $number_parts                      = 0;
        $number_part_locations             = 0;
        $number_part_locations_with_errors = 0;
        $number_part_locations_unknown     = 0;
        $number_picking_pipelines          = 0;

        $sql = sprintf('SELECT count(*) AS number FROM `Location Dimension` WHERE `Location Warehouse Key`=%d', $this->id);


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_locations = $row['number'];

            }
        }

        $sql = sprintf(
            'SELECT count(*) AS number  , sum(if(`Quantity On Hand`<0,1,0) ) AS errors , count(DISTINCT `Part SKU`) as parts  FROM `Part Location Dimension` WHERE `Part Location Warehouse Key`=%d  and `Location Key`!=%d  ', $this->id,
            $this->get('Warehouse Unknown Location Key')
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_part_locations             = $row['number'];
                $number_part_locations_with_errors = $row['errors'];
                $number_parts                      = $row['parts'];

            }
        }
        $sql  = "SELECT count(*) AS number  , sum(if(`Quantity On Hand`<0,1,0) ) AS errors,sum(`Stock Value`) as amount FROM `Part Location Dimension` WHERE `Part Location Warehouse Key`=?  and `Location Key`=? ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id,
                $this->get('Warehouse Unknown Location Key')
            )
        );
        while ($row = $stmt->fetch()) {
            $number_part_locations_unknown = $row['number'];
        }


        $this->fast_update(
            array(
                'Warehouse Number Locations'                => $number_locations,
                'Warehouse Part Locations'                  => $number_part_locations,
                'Warehouse Part Locations Errors'           => $number_part_locations_with_errors,
                'Warehouse Part Location Unknown Locations' => $number_part_locations_unknown,
                'Warehouse Number Parts'                    => $number_parts
            )
        );

        $sql  = "SELECT count(*) AS number  FROM `Picking Pipeline Dimension` WHERE `Picking Pipeline Warehouse Key`=?   ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        while ($row = $stmt->fetch()) {
            $number_picking_pipelines = $row['number'];
        }

        $this->fast_update_json_field('Warehouse Properties', 'pipelines', $number_picking_pipelines);


    }

    function get($key) {

        if (!$this->id) {
            return '';
        }


        switch ($key) {

            case 'Warehouse Paid Ordered Parts To Replenish External Warehouse':
                $num = $this->properties('to_replenish_from_external');
                if ($num == '') {
                    $num = 0;
                }

                return $num;
            case 'Paid Ordered Parts To Replenish External Warehouse':
                return number($this->get('Warehouse Paid Ordered Parts To Replenish External Warehouse'));
            case('Leakage Timeseries From'):
                if ($this->data['Warehouse Leakage Timeseries From'] == '') {
                    return '';
                } else {
                    return strftime("%a %e %b %Y", strtotime($this->data['Warehouse Leakage Timeseries From'].' +0:00'));
                }


            case 'Stock Amount':
                $account = get_object('Account', 1);

                return money($this->data['Warehouse '.$key], $account->get('Account Currency Code'));


            case 'Address':


                return '<div style="line-height: 150%">'.nl2br($this->data['Warehouse Address']).'</div>';


            case 'formatted_ready_to_pick_number':
            case 'formatted_assigned_number':
            case 'formatted_waiting_for_customer_number':
            case 'formatted_waiting_for_restock_number':
            case 'formatted_waiting_for_production_number':
            case 'formatted_picking_number':
            case 'formatted_packing_number':
            case 'formatted_packed_done_number':
            case 'formatted_approved_number':

                return number($this->properties(preg_replace('/^formatted_/', '', $key)));


            case 'formatted_ready_to_pick_weight':
            case 'formatted_assigned_weight':


                $weight = $this->properties(preg_replace('/^formatted_/', '', $key));

                if ($weight > 1000) {
                    return weight($weight / 1000, 'T', 1, true);

                } else {
                    return weight($weight, 'Kg', 0, true);

                }


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

    function properties($key) {
        return ($this->properties[$key] ?? '');
    }

    function create_shipper($data) {

        include_once 'class.Shipper.php';

        $this->new_shipper = false;

        $data['editor'] = $this->editor;


        //print_r($data);

        if (empty($data['Shipper Code'])) {
            $this->error      = true;
            $this->msg        = _("Code missing");
            $this->error_code = 'shipper_code_missing';
            $this->metadata   = '';

            return false;
        }

        if (empty($data['Shipper Name'])) {
            $this->error      = true;
            $this->msg        = _("Name missing");
            $this->error_code = 'shipper_name_missing';
            $this->metadata   = '';

            return false;
        }

        $sql = sprintf(
            'SELECT count(*) AS num FROM `Shipper Dimension` WHERE `Shipper Code`=%s AND `Shipper Warehouse Key`=%d  ', prepare_mysql($data['Shipper Code']), $this->id

        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                if ($row['num'] > 0) {
                    $this->error      = true;
                    $this->msg        = sprintf(_('Duplicated code (%s)'), $data['Shipper Code']);
                    $this->error_code = 'duplicate_shipper_code';
                    $this->metadata   = $data['Shipper Code'];

                    return false;
                }
            }
        }


        $data['Shipper Warehouse Key'] = $this->id;


        $shipper = new Shipper('find', $data, 'create');


        if ($shipper->id) {
            $this->new_object_msg = $shipper->msg;

            if ($shipper->new) {
                $this->new_object  = true;
                $this->new_shipper = true;


                $this->update_warehouse_aggregations();


            } else {

                $this->error = true;
                if ($shipper->found) {

                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(
                        array($shipper->duplicated_field)
                    );

                    if ($shipper->duplicated_field == 'Shipper Code') {
                        $this->msg = _("Duplicated code");
                    } else {
                        $this->msg = 'Duplicated '.$shipper->duplicated_field;
                    }


                } else {
                    $this->msg = $shipper->msg;
                }
            }

            return $shipper;
        } else {

            $this->error = true;
            $this->msg   = $shipper->msg;

        }

        return false;
    }

    function update_stock_amount() {


        $account = get_object('Account', 1);

        $stock_amount = 0;


        if ($account->get('Account Add Stock Value Type') == 'Blockchain') {
            $sql = sprintf('SELECT sum(`Stock Value` ) AS amount FROM `Part Location Dimension` WHERE `Part Location Warehouse Key`=%d', $this->id);

        } else {
            $sql = sprintf('SELECT sum(`Quantity On Hand`*`Part Cost in Warehouse` ) AS amount FROM `Part Location Dimension`  PL left join `Part Dimension` P on (P.`Part SKU`=PL.`Part SKU`)  WHERE `Part Location Warehouse Key`=%d', $this->id);

        }


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $stock_amount = $row['amount'];
            }
        }


        $this->fast_update(
            array(
                'Warehouse Stock Amount' => $stock_amount
            )
        );


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

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        if ($this->id and !$this->deleted) {


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

    }

    function update_flag($flag_key, $field, $value) {

        if (in_array(
            $field, array(
                      'Warehouse Flag Label',
                      'Warehouse Flag Active'
                  )
        )) {


            $sql = sprintf(
                "SELECT * FROM  `Warehouse Flag Dimension` WHERE  `Warehouse Flag Key`=%d AND `Warehouse Flag Warehouse Key`=%d", $flag_key, $this->id
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {


                    $default_flag_key = $this->get_default_flag_key();
                    if ($default_flag_key == $value and $field == 'Warehouse Flag Active' and $value == 'No') {
                        $this->error = true;
                        $this->msg   = 'can not disable default flag';
                    }


                    if ($field == 'Warehouse Flag Label') {
                        $sql = "UPDATE  `Warehouse Flag Dimension`  SET `Warehouse Flag Label`=? WHERE `Warehouse Flag Key`=? ";
                    } elseif ($field == 'Warehouse Flag Active') {
                        $sql = "UPDATE  `Warehouse Flag Dimension`  SET `Warehouse Flag Active`=? WHERE `Warehouse Flag Key`=? ";

                    }


                    $this->db->prepare($sql)->execute(
                        array(
                            $value,
                            $flag_key
                        )
                    );


                    if ($field == 'Warehouse Flag Active' and $value == 'No') {
                        $sql = sprintf(
                            "SELECT `Location Key` FROM `Location Dimension` WHERE `Location Warehouse Key`=%d AND `Location Warehouse Flag Key`=%d", $this->id, $row['Warehouse Flag Key']

                        );

                        if ($result2 = $this->db->query($sql)) {
                            foreach ($result2 as $row2) {
                                $location         = get_object('Location', $row2['Location Key']);
                                $location->editor = $this->editor;
                                $location->update(
                                    array(
                                        'Location Warehouse Flag Key' => $default_flag_key
                                    )
                                );


                            }
                        }


                    }


                    $this->updated   = true;
                    $this->new_value = $value;

                    $this->get_flags_data();

                } else {
                    $this->error = true;
                    $this->msg   = 'unknown flag';
                }
            }


        } else {
            $this->error = true;
            $this->msg   = 'unknown field';
        }

    }

    function get_default_flag_key() {
        $flag_key = 0;
        $sql      = sprintf(
            "SELECT `Warehouse Flag Key` FROM  `Warehouse Flag Dimension` WHERE `Warehouse Flag Color`=%s AND `Warehouse Flag Warehouse Key`=%d", prepare_mysql($this->data['Warehouse Default Flag Color']), $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $flag_key = $row['Warehouse Flag Key'];

            }
        }


        return $flag_key;

    }

    function get_flags_data() {
        $this->flags = array();
        $sql         = sprintf(
            "SELECT * FROM `Warehouse Flag Dimension` WHERE `Warehouse Flag Warehouse Key`=%d", $this->id
        );
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $this->flags[$row['Warehouse Flag Key']] = $row;
            }
        }


    }

    function create_warehouse_area($data) {

        include_once 'class.WarehouseArea.php';


        $this->error_metadata = array();

        // print_r($data);
        $this->new_warehouse_area             = false;
        $data['Warehouse Area Warehouse Key'] = $this->id;
        $data['editor']                       = $this->editor;


        if ($data['Warehouse Area Code'] == '' and $data['Warehouse Area Name'] == '') {
            $this->error_code       = 'missing_required_fields';
            $this->error            = true;
            $this->msg              = _('Missing field');
            $this->error_metadata[] = _('Code');
            $this->error_metadata[] = _('Name');
            $this->error_metadata   = json_encode($this->error_metadata);


            // $this->duplicated_field = 'Warehouse Area Code';
            return false;
        }


        if ($data['Warehouse Area Code'] == '') {
            $this->error_code       = 'missing_required_fields';
            $this->error            = true;
            $this->msg              = _('Missing field');
            $this->error_metadata[] = _('Code');
            $this->error_metadata   = json_encode($this->error_metadata);

            return false;
        }

        if ($data['Warehouse Area Name'] == '') {
            $this->error            = true;
            $this->error_code       = 'missing_required_fields';
            $this->msg              = _('Missing field');
            $this->error_metadata[] = _('Name');
            $this->error_metadata   = json_encode($this->error_metadata);

            return false;
        }


        $warehouse_area = new WarehouseArea('find', $data, 'create');


        if ($warehouse_area->id) {

            $this->new_area_msg = $warehouse_area->msg;
            if ($warehouse_area->new) {
                $this->new_warehouse_area     = true;
                $this->new_warehouse_area_key = $warehouse_area->id;


            } else {
                $this->error          = true;
                $this->error_code     = 'duplicated_field';
                $this->error_metadata = json_encode(array($warehouse_area->duplicated_field));
                $this->msg            = $warehouse_area->msg;

            }

            return $warehouse_area;
        } else {
            $this->error      = true;
            $this->msg        = $warehouse_area->msg;
            $this->error_code = $warehouse_area->error_code;

            return false;
        }
    }

    function update_inventory_snapshot($from = '', $to = false) {

        $client = ClientBuilder::create()->setHosts(get_elasticsearch_hosts())
        ->setApiKey(ES_KEY1,ES_KEY2)
        ->setSSLVerification(ES_SSL)
        ->build();


        include_once 'utils/warehouse_isf_functions.php';

        if ($from == '') {
            $from = gmdate('Y-m-d', strtotime($this->data['Warehouse Valid From'].' +0:00'));
        }

        if (!$to) {
            $to = $from;
        }

        $sql = sprintf(
            "SELECT `Date`  FROM kbase.`Date Dimension` WHERE `Date`>=%s AND `Date` <= %s  ", prepare_mysql($from), prepare_mysql($to)
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $params = [
                    'index' => strtolower('au_part_isf_'.strtolower(DNS_ACCOUNT_CODE)),

                    'body' => [
                        'query'        => [

                            'bool' => [


                                'filter' => [
                                    [
                                        "term" => [
                                            'date' => $row['Date'],
                                        ],


                                    ],
                                    //todo set warehouses when creating au_part_isf_
                                    /*
                                    "term" => [
                                        'warehouses' => $this->id,
                                    ],
                                    */
                                    [
                                        "term" => [
                                            'no_sales_1_year' => true,
                                        ],


                                    ],

                                ]


                            ]
                        ],
                        "aggregations" => [
                            "parts" => [
                                "cardinality" => [
                                    "field" => "sku"
                                ],
                            ],
                        ],
                        'size'         => 0

                    ],


                ];

                $response = $client->search($params);

                //                print_r($params);
                //              print_r($response);

                $parts_no_sales_1_year = $response['aggregations']['parts']['value'];


                $params   = [
                    'index' => strtolower('au_part_isf_'.strtolower(DNS_ACCOUNT_CODE)),

                    'body' => [
                        'query'        => [

                            'bool' => [


                                'filter' => [
                                    [
                                        "term" => [
                                            'date' => $row['Date'],
                                        ],


                                    ],
                                    //todo set warehouses when creating au_part_isf_
                                    /*
                                    "term" => [
                                        'warehouses' => $this->id,
                                    ],
                                    */
                                    [
                                        'range' => [
                                            'stock_left_1_year_ago' => [
                                                'gt' => 0
                                            ]
                                        ],

                                    ]

                                ]


                            ]
                        ],
                        "aggregations" => [
                            "parts" => [
                                "cardinality" => [
                                    "field" => "sku"
                                ],
                            ],
                        ],
                        'size'         => 10

                    ],


                ];
                $response = $client->search($params);


                $parts_with_stock_left_1_year = $response['aggregations']['parts']['value'];


                $params = [
                    'index' => strtolower('au_part_isf_'.strtolower(DNS_ACCOUNT_CODE)),

                    'body' => [
                        'query' => [

                            'bool' => [


                                'filter' => [
                                    [
                                        "term" => [
                                            'date' => $row['Date'],
                                        ],


                                    ],
                                    //todo set warehouses when creating au_part_isf_
                                    /*
                                    "term" => [
                                        'warehouses' => $this->id,
                                    ],
                                    */
                                    [
                                        'range' => [
                                            'stock_value_at_day_cost' => [
                                                'gt' => 0
                                            ]
                                        ],

                                    ]

                                ]


                            ]
                        ]


                    ],

                    'size'    => 1000,
                    '_source' => [
                        'stock_value_at_day_cost',
                        'sku',
                        'date'
                    ],
                    'scroll'  => '5s'
                ];


                $response = $client->search($params);


                $dormant_1y_open_value_at_day = 0;
                while (isset($response['hits']['hits']) && count($response['hits']['hits']) > 0) {


                    foreach ($response['hits']['hits'] as $hit) {

                        if (get_if_part_hsa_no_sales_1y($this->db, $hit['_source']['sku'], $row['Date'])) {
                            $dormant_1y_open_value_at_day += $hit['_source']['stock_value_at_day_cost'];
                        }


                    }


                    $scroll_id = $response['_scroll_id'];


                    $response = $client->scroll(
                        [
                            'scroll_id' => $scroll_id,
                            'scroll'    => '5s'
                        ]
                    );


                }


                $data = array(
                    'locations'        => 0,
                    'parts'            => 0,
                    'stock_cost'       => 0,
                    'value_at_day'     => 0,
                    'commercial_value' => 0,

                    'amount_in_po'     => 0,
                    'amount_in_other'  => 0,
                    'amount_out_sales' => 0,
                    'amount_out_other' => 0,
                );

                $params = [
                    'index' => strtolower('au_part_isf_'.strtolower(DNS_ACCOUNT_CODE)),

                    'body' => [
                        'query'        => [

                            'bool' => [
                                'filter' => [
                                    [
                                        "term" => [
                                            'date' => $row['Date'],
                                        ],
                                    ]

                                    //todo set warehouses when creating au_part_isf_
                                    /*
                                    "term" => [
                                        'warehouses' => $this->id,
                                    ],
                                    */


                                ]


                            ]
                        ],
                        "aggregations" => [
                            "stock_cost"              => [
                                "sum" => [
                                    "field" => "stock_cost"
                                ],
                            ],
                            "stock_value_at_day_cost" => [
                                "sum" => [
                                    "field" => "stock_value_at_day_cost"
                                ],
                            ],
                            "stock_commercial_value"  => [
                                "sum" => [
                                    "field" => "stock_commercial_value"
                                ],
                            ],


                            "parts" => [
                                "value_count" => [
                                    "field" => "sku"
                                ],
                            ],

                            "stock_value_in_purchase_order" => [
                                "sum" => [
                                    "field" => "stock_value_in_purchase_order"
                                ],
                            ],
                            "stock_value_in_other"          => [
                                "sum" => [
                                    "field" => "stock_value_in_other"
                                ],
                            ],
                            "stock_value_out_sales"         => [
                                "sum" => [
                                    "field" => "stock_value_out_sales"
                                ],
                            ],
                            "stock_value_out_other"         => [
                                "sum" => [
                                    "field" => "stock_value_out_other"
                                ],
                            ],

                        ],
                        'size'         => 3

                    ],


                ];

                $response = $client->search($params);

                //print_r($params);
                // print_r($response);

                $data['parts'] = $response['aggregations']['parts']['value'];

                $data['stock_cost']              = $response['aggregations']['stock_cost']['value'];
                $data['stock_value_at_day_cost'] = $response['aggregations']['stock_value_at_day_cost']['value'];
                $data['stock_commercial_value']  = $response['aggregations']['stock_commercial_value']['value'];

                $data['stock_value_in_purchase_order'] = $response['aggregations']['stock_value_in_purchase_order']['value'];
                $data['stock_value_in_other']          = $response['aggregations']['stock_value_in_other']['value'];
                $data['stock_value_out_sales']         = $response['aggregations']['stock_value_out_sales']['value'];
                $data['stock_value_out_other']         = $response['aggregations']['stock_value_out_other']['value'];


                $params   = [
                    'index' => strtolower('au_part_location_isf_'.strtolower(DNS_ACCOUNT_CODE)),

                    'body' => [
                        'query'        => [

                            'bool' => [
                                'filter' => [
                                    [
                                        "term" => [
                                            'date' => $row['Date'],
                                        ],
                                    ],
                                    [
                                        "term" => [
                                            'warehouse' => $this->id,
                                        ],
                                    ],


                                ]


                            ]
                        ],
                        "aggregations" => [


                            "locations" => [
                                "cardinality" => [
                                    "field"               => "location_key",
                                    "precision_threshold" => 20000
                                ],
                            ],


                        ],
                        'size'         => 0

                    ],


                ];
                $response = $client->search($params);

                $data['locations'] = $response['aggregations']['locations']['value'];


                $client = ClientBuilder::create()->setHosts(get_elasticsearch_hosts())
        ->setApiKey(ES_KEY1,ES_KEY2)
        ->setSSLVerification(ES_SSL)
        ->build();


                $params = ['body' => []];


                $params['body'][] = [
                    'index' => [
                        '_index' => 'au_warehouse_isf_'.strtolower(DNS_ACCOUNT_CODE),
                        '_id'    => DNS_ACCOUNT_CODE.'.'.$row['Date'],

                    ]
                ];
                $params['body'][] = [
                    'tenant'          => strtolower(DNS_ACCOUNT_CODE),
                    'date'            => $row['Date'],
                    '1st_day_year'    => (bool)preg_match('/\d{4}-01-01/ ', $row['Date']),
                    '1st_day_month'   => (bool)preg_match('/\d{4}-\d{2}-01/', $row['Date']),
                    '1st_day_quarter' => (bool)preg_match('/\d{4}-(01|04|07|10)-01/', $row['Date']),
                    '1st_day_week'    => gmdate('w', strtotime($row['Date'])) == 0,


                    'parts'     => $data['parts'],
                    'locations' => $data['locations'],

                    'stock_cost'              => $data['stock_cost'],
                    'stock_value_at_day_cost' => $data['stock_value_at_day_cost'],
                    'stock_commercial_value'  => $data['stock_commercial_value'],

                    'stock_value_in_purchase_order' => $data['stock_value_in_purchase_order'],
                    'stock_value_in_other'          => $data['stock_value_in_other'],
                    'stock_value_out_sales'         => $data['stock_value_out_sales'],
                    'stock_value_out_other'         => $data['stock_value_out_other'],


                    'stock_value_dormant_1y'   => $dormant_1y_open_value_at_day,
                    'parts_with_no_sales_1y'   => $parts_no_sales_1_year,
                    'parts_with_stock_left_1y' => $parts_with_stock_left_1_year,


                ];

                $client->bulk($params);


            }
        }

        $client=null;

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
            case 'Warehouse Email Template Signature':
                $label = '[Signature]';
                break;
            case 'Warehouse Leakage Timeseries From':
                $label = _('Calculate leakage from');
                break;


            default:

                if (preg_match(
                    '/Warehouse Flag Label (.+)$/', $field, $match
                )) {
                    return '<i class="fa fa-flag '.strtolower($match[1]).'" aria-hidden="true"></i> '._($match[1]);
                }

                $label = $field;

        }

        return $label;

    }

    function get_kpi($interval): array {

        global $account;


        include_once 'utils/date_functions.php';
        list($db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb) = calculate_interval_dates($this->db, $interval);

        // print "$db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb \n";

        $hrs = 0;
        $sql = sprintf(
            'SELECT sum(`Timesheet Warehouse Clocked Time`) AS seconds FROM `Timesheet Dimension` WHERE `Timesheet Date`>=%s AND `Timesheet Date`<=%s ', prepare_mysql($from_date), prepare_mysql($to_date)
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $hrs = $row['seconds'] / 3600;
            }
        }


        if ($to_date == gmdate('Y-m-d 23:59:59')) {
            include_once 'class.Timesheet.php';

            $sql = sprintf(
                "SELECT `Timesheet Key` FROM `Timesheet Dimension` T LEFT JOIN `Staff Role Bridge` B ON (B.`Staff Key`=`Timesheet Staff Key`) WHERE `Role Code` IN ('PICK','WAHSC') AND `Timesheet Date`=%s ", prepare_mysql(gmdate('Y-m-d'))
            );
            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $timesheet = new Timesheet($row['Timesheet Key']);
                    $hrs       += $timesheet->get_clocked_open_jaw_time() / 3600;
                }
            }


        }


        $amount = 0;

        $sql = "SELECT sum(`Delivery Note Invoiced Net DC Amount`) AS amount FROM `Delivery Note Dimension`   WHERE `Delivery Note Date`>=? AND `Delivery Note Date`<=?  AND `Delivery Note State`='Dispatched'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $from_date,
                $to_date
            )
        );
        if ($row = $stmt->fetch()) {
            $amount = $row['amount'];
        }


        if ($hrs == 0) {
            $kpi           = '';
            $formatted_kpi = '-';
        } else {
            $kpi           = $amount / $hrs;
            $formatted_kpi = number($amount / $hrs, 2).' '.currency_symbol($account->get('Account Currency')).'/h';
        }


        $wpm = array(
            'wpm_kpi'                    => $kpi,
            'wpm_amount'                 => $amount,
            'wpm_hrs'                    => $hrs,
            'wpm_formatted_kpi'          => $formatted_kpi,
            'wpm_formatted_amount'       => money($amount, $account->get('Account Currency')),
            'wpm_formatted_hrs'          => sprintf('%s hours', number($hrs, 1)),
            'wpm_formatted_aux_kpi_data' => sprintf('%s hours', number($hrs, 1)),

        );


        $where = sprintf(
            " where `Inventory Transaction Type` = 'Adjust' and `Inventory Transaction Section`='Audit' and  `Inventory Transaction Quantity`<0   AND `Warehouse Key`=%d %s %s  ", $this->id, ($from_date ? sprintf('and  `Date`>=%s', prepare_mysql($from_date)) : ''),
            ($to_date ? sprintf('and `Date`<%s', prepare_mysql($to_date)) : '')
        );


        $_stock_leakage              = 0;
        $_stock_leakage_transactions = 0;
        $sql                         = sprintf(
            "SELECT sum(`Inventory Transaction Amount`) AS amount, count(*) AS num FROM `Inventory Transaction Fact` %s    AND `Inventory Transaction Quantity`<0  ", $where
        );
        foreach ($this->db->query($sql) as $row) {

            $_stock_leakage              = $row['amount'];
            $_stock_leakage_transactions = $row['num'];

        }

        if ($_stock_leakage == 0) {
            $stock_leakage = '<span class="success"><i class="fa fa-thumbs-up" aria-hidden="true"></i> '.money(0, $account->get('Currency Code')).'</span>';

        } else {
            $stock_leakage = '<span >'.money($_stock_leakage, $account->get('Currency Code')).'</span>';
        }


        $stock_leakage = array(
            'stock_leakage_down_amount'       => $stock_leakage,
            'stock_leakage_down_transactions' => number($_stock_leakage_transactions)
        );


        $where = sprintf(
            " where `Inventory Transaction Type` = 'Adjust' and  `Inventory Transaction Quantity`>0  and `Inventory Transaction Section`='Audit'  AND `Warehouse Key`=%d %s %s  ", $this->id, ($from_date ? sprintf('and  `Date`>=%s', prepare_mysql($from_date)) : ''),
            ($to_date ? sprintf('and `Date`<%s', prepare_mysql($to_date)) : '')
        );


        $_stock_found = 0;

        $sql = sprintf(
            "SELECT sum(`Inventory Transaction Amount`) AS amount, count(*) AS num FROM `Inventory Transaction Fact` %s    AND `Inventory Transaction Quantity`<0  ", $where
        );
        foreach ($this->db->query($sql) as $row) {

            $_stock_found              = $row['amount'];
            $_stock_found_transactions = $row['num'];

        }

        if ($_stock_found == 0) {
            $stock_found = '<span class="success"><i class="fa fa-thumbs-up" aria-hidden="true"></i> '.money(0, $account->get('Currency Code')).'</span>';

        } else {
            $stock_found = '<span >'.money($_stock_found, $account->get('Currency Code')).'</span>';
        }


        $stock_found = array(
            'stock_found_amount'       => $stock_found,
            'stock_found_transactions' => number($_stock_found_transactions)
        );


        $stock = array(
            'stock_amount' => money($this->get('Warehouse Stock Amount'), $account->get('Account Currency'), false, 'NO_FRACTION_DIGITS'),
            //'stock_leakage_down_transactions' => number($_stock_leakage_transactions)
        );


        return array(

            'stock_leakage' => $stock_leakage,
            'stock_found'   => $stock_found,
            'wpm'           => $wpm,
            'stock'         => $stock,

        );


    }

    function update_warehouse_paid_ordered_parts() {

        $paid_ordered_parts                               = 0;
        $to_replenish_picking_location_paid_ordered_parts = 0;


        $production_suppliers = '';

        $sql  = "SELECT group_concat(`Supplier Production Supplier Key`) AS  production_suppliers FROM `Supplier Production Dimension`";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        if ($row = $stmt->fetch()) {
            $production_suppliers = $row['production_suppliers'];
        }


        $sql  = "SELECT count(DISTINCT P.`Part SKU`) AS num FROM 
              `Part Dimension` P LEFT JOIN `Part Location Dimension` PL ON (PL.`Part SKU`=P.`Part SKU`) 
              WHERE  `Part Location Warehouse Key`=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        if ($row = $stmt->fetch()) {
            $paid_ordered_parts = $row['num'];
        }


        if ($production_suppliers != '') {

            $sql  = "SELECT count(DISTINCT P.`Part SKU`) AS num FROM 
              `Part Dimension` P LEFT JOIN `Part Location Dimension` PL ON (PL.`Part SKU`=P.`Part SKU`)  LEFT JOIN `Supplier Part Dimension` SP ON (SP.`Supplier Part Part SKU`=P.`Part SKU`) 
              WHERE (`Part Current Stock In Process`+ `Part Current Stock Ordered Paid`)>`Quantity On Hand`   AND (`Part Current Stock In Process`+ `Part Current Stock Ordered Paid`)>0   AND `Part Location Warehouse Key`=? AND `Can Pick`='Yes'   AND `Supplier Part Supplier Key` NOT IN (?) ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(
                array(
                    $this->id,
                    $production_suppliers
                )
            );
            if ($row = $stmt->fetch()) {
                $to_replenish_picking_location_paid_ordered_parts = $row['num'];
            }


        } else {
            $sql = "SELECT count(DISTINCT P.`Part SKU`) AS num FROM 
              `Part Dimension` P LEFT JOIN `Part Location Dimension` PL ON (PL.`Part SKU`=P.`Part SKU`) 
              WHERE (`Part Current Stock In Process`+ `Part Current Stock Ordered Paid`)>`Quantity On Hand`  AND (`Part Current Stock In Process`+ `Part Current Stock Ordered Paid`)>0    AND `Part Location Warehouse Key`=? AND `Can Pick`='Yes'";


            $stmt = $this->db->prepare($sql);
            $stmt->execute(
                array(
                    $this->id
                )
            );
            if ($row = $stmt->fetch()) {
                $to_replenish_picking_location_paid_ordered_parts = $row['num'];
            }


        }


        $this->fast_update(
            array(
                'Warehouse Paid Ordered Parts'              => $paid_ordered_parts,
                'Warehouse Paid Ordered Parts To Replenish' => $to_replenish_picking_location_paid_ordered_parts

            )
        );

        $to_replenish_from_external = 0;

        $sql  = "SELECT count(DISTINCT P.`Part SKU`) AS num FROM 
              `Part Dimension` P
              WHERE (`Part Current Stock In Process`+ `Part Current Stock Ordered Paid`)>( `Part Current On Hand Stock`-`Part Current On Hand Stock External` ) AND (`Part Current Stock In Process`+ `Part Current Stock Ordered Paid`)>0  and `Part Current On Hand Stock External`>0 ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        if ($row = $stmt->fetch()) {
            $to_replenish_from_external = $row['num'];
        }


        $this->fast_update_json_field('Warehouse Properties', 'to_replenish_from_external', $to_replenish_from_external);


    }

    function update_warehouse_part_locations_to_replenish() {

        $replenishable_part_locations = 0;
        $part_locations_to_replenish  = 0;


        $sql = "SELECT count(*) AS num FROM `Part Location Dimension` PL left join `Location Dimension` L on (L.`Location Key`=PL.`Location Key`)    WHERE  `Part Location Warehouse Key`=?  AND  `Minimum Quantity`>=0 AND `Can Pick`='Yes'and `Location Pipeline`='No'   ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        if ($row = $stmt->fetch()) {
            $replenishable_part_locations = $row['num'];
        }


        $sql = "SELECT count(*) AS num  FROM `Part Location Dimension` PL  LEFT JOIN `Part Dimension` P ON (PL.`Part SKU`=P.`Part SKU`)  left join `Location Dimension` L on (L.`Location Key`=PL.`Location Key`)  
                    WHERE `Can Pick`='Yes' and `Location Pipeline`='No' AND `Minimum Quantity`>=0 AND   `Minimum Quantity`>=(`Quantity On Hand`- `Part Current Stock In Process`- `Part Current Stock Ordered Paid` ) AND 
                            (P.`Part Current On Hand Stock`-`Quantity On Hand`)>=0  AND `Part Location Warehouse Key`=? and (`Part Distinct Locations`-`Part Pipeline Locations`)>1 ";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        if ($row = $stmt->fetch()) {
            $part_locations_to_replenish = $row['num'];
        }





        $this->fast_update(
            array(
                'Warehouse Replenishable Part Locations' => $replenishable_part_locations,
                'Warehouse Part Locations To Replenish'  => $part_locations_to_replenish

            )
        );


    }

    function create_timeseries($data, $fork_key = 0) {


        include_once 'class.Timeserie.php';

        $data['Timeseries Parent']     = 'Warehouse';
        $data['Timeseries Parent Key'] = $this->id;


        $data['editor'] = $this->editor;

        $timeseries = new Timeseries('find', $data, 'create');

        if ($timeseries->id) {
            require_once 'utils/date_functions.php';


            //Warehouse Leakage Timeseries From


            if ($timeseries->get('Timeseries Type') == 'WarehouseStockLeakages' and $this->get('Warehouse Leakage Timeseries From') != '') {
                $from = $this->get('Warehouse Leakage Timeseries From');
            } else {
                if ($this->data['Warehouse Valid From'] != '') {
                    $from = date('Y-m-d', strtotime($this->get('Valid From')));

                } else {
                    $from = '';
                }
            }


            $to = gmdate('Y-m-d');


            $sql        = sprintf(
                'DELETE FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`<%s ', $timeseries->id, prepare_mysql($from)
            );
            $update_sql = $this->db->prepare($sql);
            $update_sql->execute();
            if ($update_sql->rowCount()) {
                $timeseries->update(
                    array('Timeseries Updated' => gmdate('Y-m-d H:i:s')), 'no_history'
                );
            }

            $sql        = sprintf(
                'DELETE FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`>%s ', $timeseries->id, prepare_mysql($to)
            );
            $update_sql = $this->db->prepare($sql);
            $update_sql->execute();
            if ($update_sql->rowCount()) {
                $timeseries->fast_update(
                    array('Timeseries Updated' => gmdate('Y-m-d H:i:s'))
                );
            }

            if ($from and $to) {
                $this->update_timeseries_record($timeseries, $from, $to, $fork_key);
            }


            if ($timeseries->get('Timeseries Number Records') == 0) {
                $timeseries->fast_update(
                    array('Timeseries Updated' => gmdate('Y-m-d H:i:s'))
                );
            }


        }

    }

    function update_timeseries_record($timeseries, $from, $to, $fork_key = false) {


        include_once 'utils/date_functions.php';

        $dates = date_frequency_range($this->db, $timeseries->get('Timeseries Frequency'), $from, $to);


        if ($fork_key) {

            $sql = sprintf(
                "UPDATE `Fork Dimension` SET `Fork State`='In Process' ,`Fork Operations Total Operations`=%d,`Fork Start Date`=NOW(),`Fork Result`=%d  WHERE `Fork Key`=%d ", count($dates), $timeseries->id, $fork_key
            );

            $this->db->exec($sql);
        }
        $index = 0;


        foreach ($dates as $date_frequency_period) {
            $index++;


            $sales_data = $this->get_leakages_data($date_frequency_period['from'], $date_frequency_period['to']);

            //print_r($date_frequency_period);
            //print_r($sales_data);
            //exit;
            $_date = gmdate('Y-m-d', strtotime($date_frequency_period['from'].' +0:00'));


            if ($sales_data['up_transactions'] > 0 or $sales_data['down_transactions'] > 0) {

                list($timeseries_record_key, $date) = $timeseries->create_record(array('Timeseries Record Date' => $_date));


                $sql = sprintf(
                    'DELETE FROM `Timeseries Record Drill Down` WHERE `Timeseries Record Drill Down Timeseries Record Key`=%d  ', $timeseries_record_key
                );
                //print $sql;
                $this->db->exec($sql);


                $sql = sprintf(
                    'UPDATE `Timeseries Record Dimension` SET 
                              `Timeseries Record Integer A`=%d ,`Timeseries Record Integer B`=%d ,
                              `Timeseries Record Float A`=%.2f ,  `Timeseries Record Float B`=%f ,`Timeseries Record Float C`=%f ,`Timeseries Record Float D`=%f ,
                              `Timeseries Record Type`=%s,`Timeseries Record Metadata`=%s WHERE `Timeseries Record Key`=%d', $sales_data['up_transactions'], $sales_data['down_transactions'], $sales_data['up_amount'], $sales_data['up_commercial_amount'],
                    $sales_data['down_amount'], $sales_data['down_commercial_amount'], prepare_mysql('Data'), prepare_mysql(
                        json_encode(
                            array(
                                'f' => $date_frequency_period['from'],
                                't' => $date_frequency_period['to']
                            )
                        )
                    ), $timeseries_record_key

                );


                //  print "$sql\n";

                $update_sql = $this->db->prepare($sql);
                $update_sql->execute();
                if ($update_sql->rowCount() or $date == date('Y-m-d')) {
                    $timeseries->fast_update(array('Timeseries Updated' => gmdate('Y-m-d H:i:s')));
                }


            } else {


                $sql = sprintf(
                    'SELECT `Timeseries Record Key` FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`=%s ', $timeseries->id, prepare_mysql($_date)
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $sql = sprintf(
                            'DELETE FROM `Timeseries Record Drill Down` WHERE `Timeseries Record Drill Down Timeseries Record Key`=%d  ', $row['Timeseries Record Key']
                        );
                        //print $sql;
                        $this->db->exec($sql);

                    }
                }


                $sql = sprintf(
                    'DELETE FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`=%s ', $timeseries->id, prepare_mysql($_date)
                );


                $update_sql = $this->db->prepare($sql);
                $update_sql->execute();
                if ($update_sql->rowCount()) {
                    $timeseries->fast_update(
                        array('Timeseries Updated' => gmdate('Y-m-d H:i:s'))
                    );

                }

            }
            if ($fork_key) {
                $skip_every = 1;
                if ($index % $skip_every == 0) {
                    $sql = sprintf(
                        "UPDATE `Fork Dimension` SET `Fork Operations Done`=%d  WHERE `Fork Key`=%d ", $index, $fork_key
                    );
                    $this->db->exec($sql);

                }

            }

            $date = gmdate('Y-m-d H:i:s');
            $sql  = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';
            $this->db->prepare($sql)->execute(
                [
                    $date,
                    $date,
                    'timeseries_stats',
                    $timeseries->id,
                    $date,

                ]
            );


        }

        //  exit("x--------------------z\n");

        if ($fork_key) {

            $sql = sprintf(
                "UPDATE `Fork Dimension` SET `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=%d,`Fork Result`=%d WHERE `Fork Key`=%d ", $index, $timeseries->id, $fork_key
            );

            $this->db->exec($sql);

        }

    }

    function get_leakages_data($from_date, $to_date) {

        $sales_data = array(
            'up_amount'              => 0,
            'up_commercial_amount'   => 0,
            'up_transactions'        => 0,
            'down_amount'            => 0,
            'down_transactions'      => 0,
            'down_commercial_amount' => 0,


        );


        $sql = sprintf(
            "SELECT count(*) AS transactions, round(ifnull(sum(`Inventory Transaction Amount`),0),2) AS amount,round(ifnull(sum(`Inventory Transaction Quantity`*`Part Commercial Value`),0),2) AS commercial_amount FROM `Inventory Transaction Fact` ITF LEFT JOIN `Part Dimension` P ON (ITF.`Part SKU`=P.`Part SKU`)  WHERE `Inventory Transaction Type` = 'Adjust' AND `Inventory Transaction Section`='Audit' AND `Inventory Transaction Quantity`<0  AND `Warehouse Key`=%d %s %s",
            $this->id, ($from_date ? sprintf('and  `Date`>=%s', prepare_mysql($from_date)) : ''), ($to_date ? sprintf('and `Date`<%s', prepare_mysql($to_date)) : '')
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['down_amount']            = $row['amount'];
                $sales_data['down_commercial_amount'] = $row['commercial_amount'];
                $sales_data['down_transactions']      = $row['transactions'];


            }
        }


        $sql = sprintf(
            "SELECT count(*) AS transactions, round(ifnull(sum(`Inventory Transaction Amount`),0),2) AS amount,round(ifnull(sum(`Inventory Transaction Quantity`*`Part Commercial Value`),0),2) AS commercial_amount FROM `Inventory Transaction Fact` ITF LEFT JOIN `Part Dimension` P ON (ITF.`Part SKU`=P.`Part SKU`)  WHERE `Inventory Transaction Type` = 'Adjust' AND `Inventory Transaction Section`='Audit' AND `Inventory Transaction Quantity`>0  AND `Warehouse Key`=%d %s %s",
            $this->id, ($from_date ? sprintf('and  `Date`>=%s', prepare_mysql($from_date)) : ''), ($to_date ? sprintf('and `Date`<%s', prepare_mysql($to_date)) : '')
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['up_amount']            = $row['amount'];
                $sales_data['up_commercial_amount'] = $row['commercial_amount'];
                $sales_data['up_transactions']      = $row['transactions'];


            }
        }


        return $sales_data;

    }

    function update_current_timeseries_record($type) {

        $sql = "SELECT `Timeseries Key` FROM `Timeseries Dimension` WHERE `Timeseries Type`=? AND `Timeseries Parent`='Warehouse' AND `Timeseries Parent key`=?";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $type,
                $this->id
            )
        );
        while ($row = $stmt->fetch()) {
            $timeseries = get_object('timeseries', $row['Timeseries Key']);
            $this->update_timeseries_record($timeseries, gmdate('Y-m-d'), gmdate('Y-m-d'));
        }


    }

    function get_shippers($scope = 'keys', $options = ''): array {


        if ($options == 'Active') {
            $where = sprintf(' where `Shipper Warehouse Key`=%s and `Shipper Status`="Active"', $this->id);

        } else {
            $where = sprintf(' where `Shipper Warehouse Key`=%d', $this->id);
        }

        $sql = sprintf(
            "SELECT `Shipper Key`,`Shipper Code`,`Shipper Name`,`Shipper API Key` from `Shipper Dimension` %s ORDER BY `Shipper Code` ", $where
        );


        $shippers = array();


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($scope == 'keys') {
                    $shippers[$row['Shipper Key']] = $row['Shipper Key'];
                } elseif ($scope == 'objects') {
                    $shippers[$row['Shipper Key']] = get_object('Shipper', $row['Shipper Key']);
                } else {


                    $shippers[$row['Shipper Key']] = array(
                        'key'     => $row['Shipper Key'],
                        'code'    => $row['Shipper Code'],
                        'name'    => $row['Shipper Name'],
                        'api_key' => $row['Shipper API Key']

                    );

                }

            }
        }


        return $shippers;
    }

    function update_delivery_notes() {

        $ready_to_pick_number = 0;
        $assigned_number      = 0;


        $ready_to_pick_weight = 0;
        $assigned_weight      = 0;


        $sql = sprintf(
            'select count(*) as num, sum( if(`Delivery Note Weight Source`="Estimated",`Delivery Note Estimated Weight` ,`Delivery Note Weight`)  ) as weight,  `Delivery Note State` from `Delivery Note Dimension` 
                where `Delivery Note Warehouse Key`=%d  group by `Delivery Note State`', $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                switch ($row['Delivery Note State']) {
                    case 'Ready to be Picked':
                        $ready_to_pick_number = $row['num'];
                        $ready_to_pick_weight = $row['weight'];
                        break;
                    case 'Picker Assigned':
                        $assigned_number = $row['num'];
                        $assigned_weight = $row['weight'];
                        break;

                }


            }
        }


        $this->fast_update_json_field('Warehouse Properties', 'ready_to_pick_number', $ready_to_pick_number);
        $this->fast_update_json_field('Warehouse Properties', 'ready_to_pick_weight', $ready_to_pick_weight);
        $this->fast_update_json_field('Warehouse Properties', 'assigned_number', $assigned_number);
        $this->fast_update_json_field('Warehouse Properties', 'assigned_weight', $assigned_weight);


    }

    function settings($key) {
        return ($this->settings[$key] ?? '');
    }

    function create_picking_band($data) {

        include_once 'class.PickingBand.php';


        $this->error_metadata = array();

        // print_r($data);
        $this->new_warehouse_area           = false;
        $data['Picking Band Warehouse Key'] = $this->id;
        $data['editor']                     = $this->editor;


        if ($data['Picking Band Name'] == '') {
            $this->error_code       = 'missing_required_fields';
            $this->error            = true;
            $this->msg              = _('Missing field');
            $this->error_metadata[] = _('Name');
            $this->error_metadata   = json_encode($this->error_metadata);

            return false;
        }


        $picking_band = new PickingBand('find create', $data);


        if ($picking_band->id) {

            $this->new_area_msg = $picking_band->msg;
            if ($picking_band->new) {
                $this->new_warehouse_area     = true;
                $this->new_warehouse_area_key = $picking_band->id;


            } else {
                $this->error          = true;
                $this->error_code     = 'duplicated_field';
                $this->error_metadata = json_encode(array($picking_band->duplicated_field));
                $this->msg            = $picking_band->msg;

            }

            return $picking_band;
        } else {
            $this->error      = true;
            $this->msg        = $picking_band->msg;
            $this->error_code = $picking_band->error_code;

            return false;
        }
    }

}



