<?php

/*
 File: Location.php

 This file contains the Location Class

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Refurbished: 28 April 2016 at 15:40:46 GMT+8, Lovina, Bali, Indonesia

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

class Location extends DB_Table {


    var $parts = false;
    var $warehouse = false;
    var $warehouse_area = false;
    var $shelf = false;

    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Location';
        $this->ignore_fields = array('Location Key');

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

        $this->data['Location File As'] = $this->get_file_as(
            $this->data['Location Code']
        );

        //exit;
        $warehouse = get_object('Warehouse', $this->data['Location Warehouse Key']);
        if (!$warehouse->id) {
            $this->error = true;
            $this->msg   = 'Warehouse not found';

            return false;
        }


        if ($this->data['Location Code'] == '') {
            $this->error = true;
            $this->msg   = _('Wrong location code');

            return false;
        }


        if (!$this->data['Location Max Volume']) {
            if ($this->data['Location Shape Type'] == 'Box' and is_numeric($this->data['Location Width']) and $this->data['Location Width'] > 0 and is_numeric($this->data['Location Deep']) and $this->data['Location Deep'] > 0 and is_numeric(
                    $this->data['Location Height']
                ) and $this->data['Location Height'] > 0) {
                $this->data['Location Max Volume'] = $this->data['Location Width'] * $this->data['Location Deep'] * $this->data['Location Height'] * 0.001;
            }
            if ($this->data['Location Shape Type'] == 'Cylinder' and is_numeric($this->data['Location Radius']) and $this->data['Location Radius'] > 0 and is_numeric($this->data['Location Height']) and $this->data['Location Height'] > 0) {
                $this->data['Location Max Volume'] = 3.151592 * $this->data['Location Radius'] * $this->data['Location Radius'] * $this->data['Location Height'] * 0.001;
            }
        }


        $sql = sprintf(
            "INSERT INTO `Location Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($this->data)).'`', join(',', array_fill(0, count($this->data), '?'))
        );

        $stmt = $this->db->prepare($sql);

        $i = 1;
        foreach ($this->data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {
            $this->id = $this->db->lastInsertId();


            $this->get_data('id', $this->id);

            if ($this->data['Location Code'] != 'Unknown') {

                $history_data = array(
                    'History Abstract' => sprintf(_('%s location created'), $this->data['Location Code']),
                    'History Details'  => '',
                    'Action'           => 'created'
                );

                $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id);


            }


            $this->new = true;


            if ($this->data['Location Warehouse Area Key']) {
                $warehouse_area = get_object('WarehouseArea', $this->data['Location Warehouse Area Key']);
                if ($warehouse_area->id) {
                    $warehouse_area->update_warehouse_area_locations();
                }
            }


            return $this;


        } else {
            exit($sql);
        }


    }

    function get_file_as($StartCode) {

        $PaddingAmount = 4;
        $s             = preg_replace("/[^0-9]/", "-", $StartCode);

        for ($qq = 0; $qq < 10; $qq++) {
            $s = preg_replace("/--/", "-", $s);
        }


        $pieces = explode("-", $s);

        for ($qq = 0; $qq < count($pieces); $qq++) {
            $ss = str_pad($pieces[$qq], $PaddingAmount, '0', STR_PAD_LEFT);
            if (strlen($pieces[$qq]) > 0) {
                $StartCode      = preg_replace(
                    '/'.$pieces[$qq].'/', ';xyz;', $StartCode, 1
                );
                $arr_parts[$qq] = $ss;
            }

        }


        for ($qq = 0; $qq < count($pieces); $qq++) {

            if (strlen($pieces[$qq]) > 0) {
                $ss        = $arr_parts[$qq];
                $StartCode = preg_replace('/;xyz;/', $ss, $StartCode, 1);
            }


        }


        return $StartCode;


    }

    function get_data($key, $tag, $tag2 = '') {


        if ($key == 'warehouse_code') {

            $sql = "SELECT * FROM `Location Dimension` where  `Location Warehouse Key`=? and  `Location Code`=? ";

            $arguments = array(
                $tag,
                $tag2
            );

        } else {
            $arguments = array($tag);
            if ($key == 'id') {
                $sql = "SELECT * FROM `Location Dimension` where  `Location Key`=? ";
            } elseif ($key == 'deleted') {
                $this->get_deleted_data($tag);

                return;
            } elseif ($key == 'code') {
                $sql = "SELECT * FROM `Location Dimension` where  `Location Code`=? ";

            } else {
                return false;
            }
        }


        $stmt = $this->db->prepare($sql);
        if ($stmt->execute($arguments)) {
            if ($row = $stmt->fetch()) {
                $this->data = $row;
                $this->id   = $this->data['Location Key'];
            } else {
                $this->msg = _('Location do not exist');
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit();
        }


    }


    function get_deleted_data($tag) {

        $this->deleted = true;


        $sql = sprintf("SELECT * FROM `Location Deleted Dimension` WHERE `Location Deleted Key`=%d", $tag);

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id     = $this->data['Location Deleted Key'];
            $deleted_data = json_decode(gzuncompress($this->data['Location Deleted Metadata']), true);


            foreach ($deleted_data as $key => $value) {
                $this->data[$key] = $value;
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


        $this->found = false;
        $create      = '';
        $update      = '';
        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        $data = $this->base_data();
        foreach ($raw_data as $key => $val) {
            /*       if(preg_match('/from supplier/',$options)) */ /* 	$_key=preg_replace('/^Location /i','',$key); */
            /*       else */
            $_key        = $key;
            $data[$_key] = $val;
        }


        //look for areas with the same code in the same warehouse
        $sql = sprintf(
            "SELECT `Location Key` FROM `Location Dimension` WHERE `Location Warehouse Key`=%d AND `Location Code`=%s", $data['Location Warehouse Key'], prepare_mysql($data['Location Code'])
        );

        // print $sql;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $this->found     = true;
                $this->found_key = $row['Location Key'];


                $this->get_data('id', $this->found_key);
                $this->duplicated_field = 'Location Code';

                return;


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if ($create) {

            $this->create($data, $options);


        }
    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        $warehouse = get_object('Warehouse', $this->get('Location Warehouse Key'));
        if ($this->id == $warehouse->get('Warehouse Unknown Location Key')) {
            $this->deleted_msg = 'Error location unknown can not be edited';
            $this->error       = true;

            return;
        }


        switch ($field) {
            case('Location Code'):


                $code = _trim($value);

                if ($code == '') {
                    $this->msg     = _('Wrong location code');
                    $this->updated = false;

                    return;
                }


                $sql = sprintf(
                    'SELECT `Location Key` FROM `Location Dimension` WHERE `Location Key`!=%d AND `Location Code`=%s', $this->id, prepare_mysql($value)
                );
                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $this->msg     = _('Other location has this code');
                        $this->updated = false;

                        return;
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


                $this->update_field('Location File As', $this->get_file_as($code), 'no_history');

                $this->update_field('Location Code', $code, $options);
                break;

            case 'Warehouse Area Code':


                if ($value == '') {
                    $this->update_field_switcher('Location Warehouse Area Key', 0, $options);
                } else {
                    include_once 'class.WarehouseArea.php';
                    $warehouse_area = new WarehouseArea('warehouse_code', $this->data['Location Warehouse Key'], $value);


                    if ($warehouse_area->id) {
                        $this->update_field_switcher('Location Warehouse Area Key', $warehouse_area->id, $options);
                    } else {
                        $this->error = true;
                        $this->msg   = _('Warehouse area not found').' ('.$value.')';
                    }
                }


                break;
            case('Location Area Key'):
            case('Location Warehouse Area Key'):
                $this->update_area_key($value);
                break;
            //  case('Location Mainly Used For'):
            //     $this->update_used_for($value);
            //     break;
            case('Location Max Weight'):

                include_once 'utils/parse_natural_language.php';

                if ($value != '') {


                    list($value, $original_units) = parse_weight($value);
                    if (!is_numeric($value)) {
                        $this->msg     = _(
                            'The maximum weight for this location show be numeric'
                        );
                        $this->updated = false;

                        return;
                    }
                    if ($value < 0) {
                        $this->msg     = _('The maximum weight can not be negative');
                        $this->updated = false;

                        return;
                    }
                    if ($value == 0) {
                        $this->msg     = _('The maximum weight can not be zero');
                        $this->updated = false;

                        return;
                    }
                    if ($value == $this->data['Location Max Weight']) {
                        $this->msg     = _('Nothing to change');
                        $this->updated = false;

                        return;
                    }

                }

                $this->update_field($field, $value, $options);


                break;
            case('Location Max Volume'):

                if ($value != '') {

                    list($value, $original_units) = parse_cbm($value);
                    if (!is_numeric($value)) {
                        $this->msg     = _(
                            'The maximum volume for this location show be numeric'
                        );
                        $this->updated = false;

                        return;
                    }
                    if ($value < 0) {
                        $this->msg     = _('The maximum volume can not be negative');
                        $this->updated = false;

                        return;
                    }
                    if ($value == 0) {
                        $this->msg     = _('The maximum volume can not be zero');
                        $this->updated = false;

                        return;
                    }
                    if ($value == $this->data['Location Max Volume']) {
                        $this->msg     = _('Nothing to change');
                        $this->updated = false;

                        return;
                    }

                }
                $this->update_field($field, $value, $options);

                break;

            case 'Location Flag Color':


                if ($value == '') {
                    $this->update_field('Location Warehouse Flag Key', $value, $options);

                    return;
                }

                $sql = sprintf(
                    "SELECT `Warehouse Flag Key` FROM  `Warehouse Flag Dimension` WHERE `Warehouse Flag Color`=%s", prepare_mysql(ucfirst($value))
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $this->update_field_switcher('Location Warehouse Flag Key', $row['Warehouse Flag Key'], $options);
                    } else {
                        $this->error = true;
                        $this->msg   = _('Flag invalid color');
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                break;
            case 'Location Warehouse Flag Key':

                include_once 'class.Warehouse.php';
                $warehouse = new Warehouse($this->data['Location Warehouse Key']);


                if ($value == '') {

                    $old_key = $this->data['Location Warehouse Flag Key'];
                    $this->update_field('Location Warehouse Flag Key', $value, $options);

                    if ($old_key) {
                        $warehouse->update_location_flag_number($old_key);
                    }

                    return;
                }

                $sql = sprintf(
                    "SELECT `Warehouse Flag Warehouse Key`,`Warehouse Flag Color` FROM  `Warehouse Flag Dimension` WHERE `Warehouse Flag Key`=%d", $value
                );


                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {

                        if ($row['Warehouse Flag Warehouse Key'] != $this->data['Location Warehouse Key']) {
                            $this->error = true;
                            $this->msg   = 'flag key not in this warehouse';

                            return;
                        }

                        $old_key = $this->data['Location Warehouse Flag Key'];
                        $this->update_field('Location Warehouse Flag Key', $value, $options);

                        $warehouse->update_location_flag_number($this->data['Location Warehouse Flag Key']);

                        if ($old_key) {
                            $warehouse->update_location_flag_number($old_key);
                        }


                    } else {
                        $this->error = true;
                        $this->msg   = 'flag key not found';
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit('xx');
                }


                break;

            default:
                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {

                    if ($value != $this->data[$field]) {

                        $this->update_field($field, $value, $options);
                    }
                }
        }

    }

    function get($key) {


        if (!$this->id) {
            return '';
        }


        switch ($key) {
            case 'Stock Value':
                $account = get_object('Account', 1);

                return money($this->data['Location Stock Value'], $account->get('Account Currency'));

                break;
            case 'Max Weight':

                return weight($this->data['Location Max Weight']);

                break;
            case 'Max Volume':

                if ($this->data['Location Max Volume'] == '') {
                    return '';
                }

                return number($this->data['Location Max Volume']).' '.ngettext('cubic meter', 'cubic meters', floor($this->data['Location Max Volume']));

                break;

            case 'Location Flag Color':
                $sql = sprintf(
                    'SELECT `Warehouse Flag Color`,`Warehouse Flag Label` FROM `Warehouse Flag Dimension` WHERE `Warehouse Flag Key`=%d  ', $this->data['Location Warehouse Flag Key']
                );


                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        return $row['Warehouse Flag Color'];
                    } else {
                        return '';
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                break;
            case 'Warehouse Flag Key':

                $sql = sprintf(
                    'SELECT `Warehouse Flag Color`,`Warehouse Flag Label` FROM `Warehouse Flag Dimension` WHERE `Warehouse Flag Key`=%d  ', $this->data['Location Warehouse Flag Key']
                );


                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        return sprintf('<i class="fa fa-flag %s padding_right_10 "  aria-hidden="true"></i> %s', strtolower($row['Warehouse Flag Color']), $row['Warehouse Flag Label']);
                    } else {
                        return '';
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                break;
            case 'Warehouse Area Key':
                if (!$this->warehouse_area) {
                    $warehouse_area = get_object(
                        'WarehouseArea', $this->data['Location Warehouse Area Key']
                    );
                }

                return $warehouse_area->get('Code');
                break;

            /*
             *
             *

        case 'Mainly Used For':
            switch ($this->data['Location Mainly Used For']) {
                case 'Picking':
                    return _('Picking');
                    break;
                case 'Storing':
                    return _('Storing');
                    break;
                case 'Loading':
                    return _('Loading');
                    break;
                case 'Displaying':
                    return _('Displaying');
                    break;
                case 'Other':
                    return _('Other');
                    break;
                case 'Default':
                    return _('Default');
                    break;
                default:
                    return $this->data['Lcoation Mainly Used For'];
                    break;
            }

            break;
            */ default:

            if (array_key_exists($key, $this->data)) {
                return $this->data[$key];
            }

            if (array_key_exists('Location '.$key, $this->data)) {
                return $this->data['Location '.$key];
            }


            if (preg_match('/^warehouse area/i', $key)) {
                if (!$this->warehouse_area) {
                    $warehouse_area = get_object(
                        'WarehouseArea', $this->data['Location Warehouse Area Key']
                    );
                }

                return $warehouse_area->get($key);
            }
            if (preg_match('/^warehouse/i', $key)) {
                if (!$this->warehouse) {
                    $warehouse = new Warehouse(
                        $this->data['Location Warehouse Key']
                    );
                }

                return $warehouse->get($key);
            }
            if (preg_match('/^shelf/i', $key)) {
                if (!$this->data['Location Shelf Key']) {
                    return false;
                }
                if (!$this->shelf) {
                    $shelf = new Shelf($this->data['Location Shelf Key']);
                }

                return $shelf->get($key);
            }

            return '';

        }


    }

    /*
    function update_used_for($value, $options = '') {

        if (!preg_match(
            '/^(Picking|Storing|Displaying|Loading|Other)$/', $value
        )
        ) {
            $this->msg     = _('Wrong location type');
            $this->updated = false;

            return;
        }

        $this->update_field('Location Mainly Used For', $value, $options);


    }

*/

    function update_area_key($warehouse_area_key) {


        if ($this->get('Location Type') == 'Unknown') {
            $this->deleted_msg = 'Error location unknown can not be edited';
            $this->error       = true;

            return;
        }


        if ($warehouse_area_key > 0) {
            if ($warehouse_area_key == $this->data['Location Warehouse Area Key']) {
                $this->msg = 'no_change';

                return;
            }
        } else {
            if (!$this->data['Location Warehouse Area Key']) {
                $this->msg = 'no_change';

                return;
            }
        }


        if ($this->data['Location Warehouse Area Key']) {
            $old_area = get_object('WarehouseArea', $this->data['Location Warehouse Area Key']);
        }


        $this->updated = true;


        $this->fast_update(
            array(
                'Location Warehouse Area Key' => ($warehouse_area_key > 0 ? $warehouse_area_key : '')
            )
        );

        if ($warehouse_area_key > 0) {
            $new_area = get_object('warehouse_area', $warehouse_area_key);


            $new_area->update_warehouse_area_locations();


            if (isset($old_area) and $old_area->id) {
                $history_data = array(
                    'History Abstract' => sprintf(
                        _('Location moved from to warehouse area %s to %s'),
                        '<span title="'.$old_area->get('Name').'" class="link discreet" onclick="change_view(\'warehouse/'.$old_area->get('Warehouse Key').'/areas/'.$old_area->id.'\')" >'.$old_area->get('Code').'</span>',
                        '<span title="'.$new_area->get('Name').'" class="link strong" onclick="change_view(\'warehouse/'.$new_area->get('Warehouse Key').'/areas/'.$new_area->id.'\')" >'.$new_area->get('Code').'</span>'

                    ),
                    'History Details'  => '',
                    'Action'           => 'edited'
                );
            } else {
                $history_data = array(
                    'History Abstract' => sprintf(
                        _('Location associated to warehouse area %s'), '<span title="'.$new_area->get('Name').'" class="link" onclick="change_view(\'warehouse/'.$new_area->get('Warehouse Key').'/areas/'.$new_area->id.'\')" >'.$new_area->get('Code').'</span>'
                    ),
                    'History Details'  => '',
                    'Action'           => 'edited'
                );
            }


            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );

            $history_data = array(
                'History Abstract' => '<i class="fa fa-link"></i>'.sprintf(_('Location %s associated'), '<span class="link" onclick="change_view(\'locations/'.$new_area->get('Warehouse Key').'/areas/'.$this->id.'\')" >'.$this->get('Code').'</span>'),
                'History Details'  => '',
                'Action'           => 'edited'
            );

            $new_area->add_subject_history(
                $history_data, true, 'No', 'Changes', $new_area->get_object_name(), $new_area->id
            );


        } else {


        }

        if (isset($old_area) and $old_area->id) {
            $old_area->update_warehouse_area_locations();

        }


    }

    function update_shape($value) {


        $warehouse = get_object('Warehouse', $this->get('Location Warehouse Key'));
        if ($this->id == $warehouse->get('Warehouse Unknown Location Key')) {
            $this->deleted_msg = 'Error location unknown can not be edited';
            $this->error       = true;

            return;
        }


        $value = _trim($value);
        if ($value == $this->data['Location Shape Type']) {
            $this->msg     = _('Nothing to change');
            $this->updated = false;

            return;
        }
        if (!preg_match('/^(Box|Cylinder|Unknown)$/', $value)) {
            $this->msg     = _('Wrong location shape');
            $this->updated = false;

            return;
        }

        $old_value = $this->data['Location Shape Type'];
        $sql       = sprintf(
            "UPDATE `Location Dimension` SET `Location Shape Type`=%s WHERE `Location Key`=%d", prepare_mysql($value), $this->id
        );
        //print $sql; exit;
        $this->db->exec($sql);
        $this->data['Location Shape Type'] = $value;
        $this->new_value                   = $value;
        $this->new_data                    = array(
            'old_value' => $old_value,
            'type'      => 'shape'
        );
        $this->msg                         = _('Location shape changed');
        $this->updated                     = true;


    }

    function update_stock_value() {

        $stock_value = 0;

        $sql = sprintf('SELECT sum(`Stock Value`) AS value FROM `Part Location Dimension` WHERE `Location Key`=%d ', $this->id);

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $stock_value = $row['value'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $this->fast_update(
            array(
                'Location Stock Value',
                $stock_value
            )
        );


    }

    function update_parts() {
        $this->parts = array();

        $sql = sprintf(
            "SELECT `Part SKU`,`Quantity On Hand` AS qty FROM `Part Location Dimension`  WHERE `Location Key`=%d  ", $this->id

        );


        $has_stock  = 'No';
        $has_errors = 'No';

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if (is_numeric($row['qty']) and $row['qty'] > 0) {
                    $has_stock = 'Yes';

                }
                if (is_numeric($row['qty']) and $row['qty'] < 0) {
                    $has_errors = 'Yes';

                }

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $this->fast_update(
            array(
                'Location Distinct Parts' => count($this->get_parts()),
                'Location has Stock'      => $has_stock,
                'Location has Errors'     => $has_errors
            )

        );


    }

    function get_parts($scope = 'keys') {


        if ($scope == 'objects') {
            include_once 'class.Part.php';
        } elseif ($scope == 'part_location_object') {
            include_once 'class.PartLocation.php';
        } elseif ($scope == 'data') {
            include_once 'class.PartLocation.php';

        }
        $sql = sprintf(
            "SELECT PL.`Part SKU`,`Quantity On Hand`,`Minimum Quantity`,`Maximum Quantity`,`Moving Quantity`,`Can Pick` 
            FROM `Part Location Dimension` PL LEFT JOIN `Part Dimension` P ON (P.`Part SKU`=PL.`Part SKU`)  WHERE `Location Key`=%d", $this->id
        );


        $part_locations = array();


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($scope == 'keys') {
                    $part_locations[$row['Part SKU']] = $row['Part SKU'];
                } elseif ($scope == 'objects') {
                    $part_locations[$row['Part SKU']] = new Part($row['Part SKU']);
                } elseif ($scope == 'part_location_object') {
                    $part_locations[$row['Part SKU']] = new  PartLocation($row['Part SKU'].'_'.$this->id);
                } else {

                    $part_location = new  PartLocation($row['Part SKU'].'_'.$this->id);

                    $part_locations[$row['Part SKU']]          = $part_location->data;
                    $part_locations[$row['Part SKU']] ['Part'] = $part_location->part->data;
                }

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $part_locations;
    }

    function delete() {

        include_once 'class.Warehouse.php';
        $warehouse = new Warehouse($this->data['Location Warehouse Key']);

        $this->deleted     = false;
        $this->deleted_msg = '';


        if ($this->id == $warehouse->get('Warehouse Unknown Location Key')) {
            $this->deleted_msg = 'Error location unknown can not be deleted';

            return;
        }


        if (count($this->get_parts()) > 0) {
            $this->deleted_msg = _("Can't delete because location has parts associated");

            return;
        }


        $sql = sprintf(
            'INSERT INTO `Location Deleted Dimension`  (`Location Deleted Key`,`Location Deleted Code`,`Location Deleted Date`,`Location Deleted Metadata`) VALUES (%d,%s,%s,%s) ', $this->id, prepare_mysql($this->get('Location Code')),
            prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql(gzcompress(json_encode($this->data), 9))

        );
        $this->db->exec($sql);

        $sql = sprintf(
            "DELETE FROM `Location Dimension` WHERE `Location Key`=%d", $this->id
        );
        $this->db->exec($sql);
        $this->deleted = true;

        $history_data = array(
            'History Abstract' => sprintf(
                _("Location %s deleted"), $this->data['Location Code']
            ),
            'History Details'  => '',
            'Action'           => 'deleted'
        );

        $this->add_subject_history(
            $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
        );


    }

    function get_field_label($field) {

        switch ($field) {

            case 'Location Code':
                $label = _('code');
                break;
            /*
                        case 'Location Mainly Used For':
                            $label = _('used for');
                            break;
            */ case 'Location Warehouse Flag Key':
            $label = _('flag');
            break;
            case 'Location Max Weight':
                $label = _('max weight').' (Kg)';
                break;
            case 'Location Max Volume':
                $label = _('max volume').' (m³)';
                break;


            default:
                $label = $field;

        }

        return $label;

    }

}


?>
