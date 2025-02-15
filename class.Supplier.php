<?php
/*
  File: Supplier.php

  This file contains the Supplier Class

  About:
  Author: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0
*/
include_once 'class.SubjectSupplier.php';
include_once 'trait.SupplierAiku.php';


class Supplier extends SubjectSupplier {

    use SupplierAiku;
    /**
     * @var \PDO
     */
    public $db;
    var $new = false;
    public $locale = 'en_GB';

    function __construct($arg1 = false, $arg2 = false, $arg3 = false, $_db = false) {

        global $db;
        $this->db = $db;


        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {


            $this->db = $_db;
        }

        $this->table_name    = 'Supplier';
        $this->ignore_fields = array('Supplier Key');


        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }

        if ($arg1 == 'new') {
            $this->find($arg2, $arg3, 'create');

            return;
        }


        $this->get_data($arg1, $arg2);

    }


    function get_data($tipo, $id) {


        $this->data = $this->base_data();

        if ($tipo == 'id' or $tipo == 'key') {
            $sql = sprintf(
                "SELECT * FROM `Supplier Dimension` WHERE `Supplier Key`=%d", $id
            );
        } elseif ($tipo == 'code') {


            $sql = sprintf(
                "SELECT * FROM `Supplier Dimension` WHERE `Supplier Code`=%s ", prepare_mysql($id)
            );


        } elseif ($tipo == 'deleted') {
            $this->get_deleted_data($id);

            return;
        } else {
            return;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Supplier Key'];


            if (empty($this->data['Supplier Metadata'])) {
                $this->metadata = array();
            } else {
                $this->metadata = json_decode(
                    $this->data['Supplier Metadata'], true
                );
            }
        }

    }


    function get_deleted_data($tag) {

        $this->deleted = true;
        $sql           = sprintf(
            "SELECT * FROM `Supplier Deleted Dimension` WHERE `Supplier Deleted Key`=%d", $tag
        );
        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Supplier Deleted Key'];
            foreach (
                json_decode(
                    gzuncompress($this->data['Supplier Deleted Metadata']), true
                ) as $key => $value
            ) {
                $this->data[$key] = $value;
            }
        }
    }

    function find($raw_data, $address_raw_data, $options) {
        // print "$options\n";
        //print_r($raw_data);

        if (isset($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {

                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }

            }
        }


        $create = '';

        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }

        if (isset($raw_data['name'])) {
            $raw_data['Supplier Name'] = $raw_data['name'];
        }
        if (isset($raw_data['code'])) {
            $raw_data['Supplier Code'] = $raw_data['code'];
        }
        if (isset($raw_data['Supplier Code']) and $raw_data['Supplier Code'] == '') {
            $this->get_data('id', 1);

            return;
        }


        $data = $this->base_data();

        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = _trim($value);
            } elseif (preg_match('/^Supplier Address/', $key)) {
                $data[$key] = _trim($value);
            }
        }

        $data['Supplier Code'] = mb_substr($data['Supplier Code'], 0, 16);


        if ($data['Supplier Code'] != '') {
            $sql = sprintf(
                "SELECT `Supplier Key` FROM `Supplier Dimension` WHERE `Supplier Code`=%s ", prepare_mysql($data['Supplier Code'])
            );

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {

                    $this->found     = true;
                    $this->found_key = $row['Supplier Key'];


                }
            }

        }

        if ($this->found) {
            $this->get_data('id', $this->found_key);
        }


        if ($create) {

            if (!$this->found) {
                $this->create($data, $address_raw_data);
            }
        }


    }

    function create($raw_data, $address_raw_data) {


        $this->data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $this->data)) {
                $this->data[$key] = _trim($value);
            }
        }


        if ($this->data['Supplier Main Plain Mobile'] != '') {
            list($this->data['Supplier Main Plain Mobile'], $this->data['Supplier Main XHTML Mobile']) = $this->get_formatted_number(
                $this->data['Supplier Main Plain Mobile']
            );
        }
        if ($this->data['Supplier Main Plain Telephone'] != '') {
            list($this->data['Supplier Main Plain Telephone'], $this->data['Supplier Main XHTML Telephone']) = $this->get_formatted_number(
                $this->data['Supplier Main Plain Telephone']
            );
        }
        if ($this->data['Supplier Main Plain FAX'] != '') {
            list($this->data['Supplier Main Plain FAX'], $this->data['Supplier Main XHTML FAX']) = $this->get_formatted_number(
                $this->data['Supplier Main Plain FAX']
            );
        }

        $this->data['Supplier Metadata'] = '{}';

        $this->data['Supplier Valid From'] = gmdate('Y-m-d H:i:s');

        $base_data=[];
        foreach ($this->data as $key => $value) {
            $base_data[$key] = _trim($value);

        }


        unset($base_data['Supplier Average Delivery Days']);
        unset($base_data['Supplier Average Production Days']);
        unset($base_data['Supplier Valid To']);
        unset($base_data['Supplier Stock Value']);
        unset($base_data['Supplier Acc To Day Updated']);

        unset($base_data['Supplier Acc Ongoing Intervals Updated']);
        unset($base_data['Supplier Acc Previous Intervals Updated']);
        unset($base_data['Supplier Main Image Key']);


        $sql = sprintf(
            "INSERT INTO `Supplier Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($base_data)).'`', join(',', array_fill(0, count($base_data), '?'))
        );

        $stmt = $this->db->prepare($sql);


        $i = 1;
        foreach ($base_data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {
            $this->id = $this->db->lastInsertId();


            $this->get_data('id', $this->id);


            $sql = "INSERT INTO `Supplier Data` (`Supplier Key`) VALUES(".$this->id.");";
            $this->db->exec($sql);

            if ($this->data['Supplier Company Name'] != '') {
                $supplier_name = $this->data['Supplier Company Name'];
            } else {
                $supplier_name = $this->data['Supplier Main Contact Name'];
            }
            $this->update_field('Supplier Name', $supplier_name, 'no_history');

            $this->update_address('Contact', $address_raw_data, 'no_history');

            $history_data = array(
                'History Abstract' => _('Supplier created'),
                'History Details'  => '',
                'Action'           => 'created'
            );
            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );
            $this->new = true;
            $this->model_updated( 'new', $this->id);

        } else {
            print_r($stmt->errorInfo());
            print "$sql\n";
            // print "Error can not create supplier $sql\n";
        }


    }

    function get_category_data(): array {
        $sql = sprintf(
            "SELECT B.`Category Key`,`Category Root Key`,`Other Note`,`Category Label`,`Category Code`,`Is Category Field Other` FROM `Category Bridge` B LEFT JOIN `Category Dimension` C ON (C.`Category Key`=B.`Category Key`) WHERE  `Category Branch Type`='Head'  AND B.`Subject Key`=%d AND B.`Subject`='Supplier'",
            $this->id
        );

        $category_data = array();


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                $root_label = '';
                $root_code  = '';
                $sql        = sprintf(
                    "SELECT `Category Label`,`Category Code` FROM `Category Dimension` WHERE `Category Key`=%d", $row['Category Root Key']
                );


                if ($result2 = $this->db->query($sql)) {
                    if ($row2 = $result2->fetch()) {
                        $root_label = $row2['Category Label'];
                        $root_code  = $row2['Category Code'];
                    }
                }


                if ($row['Is Category Field Other'] == 'Yes' and $row['Other Note'] != '') {
                    $value = $row['Other Note'];
                } else {
                    $value = $row['Category Label'];
                }
                $category_data[] = array(
                    'root_label'   => $root_label,
                    'root_code'    => $root_code,
                    'label'        => $row['Category Label'],
                    'code'         => $row['Category Code'],
                    'value'        => $value,
                    'category_key' => $row['Category Key']
                );

            }
        }


        return $category_data;
    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        if (is_string($value)) {
            $value = _trim($value);
        }


        if ($this->update_subject_field_switcher(
            $field, $value, $options, $metadata
        )) {
            return;
        }


        switch ($field) {
            case 'Supplier cooling order interval days':
            case 'Supplier minimum order amount':
                $this->fast_update_json_field('Supplier Metadata', preg_replace('/^Supplier_/', '', preg_replace('/\s/', '_', $field)), $value);
                break;
            case 'payment terms':

                $this->fast_update_json_field('Supplier Metadata', preg_replace('/\s/', '_', $field), $value);

                break;
            case 'Supplier Contact Address':


                $this->update_address('Contact', json_decode($value, true), $options);

                break;

            case('Supplier ID'):
            case('Supplier Valid From'):
            case('Supplier Stock Value'):
                break;
            case 'Supplier On Demand':

                if (!in_array(
                    $value, array(
                              'No',
                              'Yes'
                          )
                )) {
                    $this->error = true;
                    $this->msg   = sprintf(
                        _('Invalid value, valid values: %s'), '"Yes", "No"'
                    );

                    return;
                }

                $this->update_field($field, $value, $options);
                if ($this->updated and $value == 'No') {
                    include_once 'utils/new_fork.php';

                    $sql = sprintf(
                        "SELECT `Supplier Part Key` FROM `Supplier Part Dimension` WHERE `Supplier Part Supplier Key`=%d  AND  `Supplier Part On Demand`='Yes' ", $this->id
                    );
                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {



                            new_housekeeping_fork(
                                'au_housekeeping', array(
                                'type'          => 'update_supplier_part_on_demand',
                                'supplier_part_key' => $row['Supplier Part Key'],
                                'value'=>'No',
                                'editor'      => $this->editor,
                            ), DNS_ACCOUNT_CODE,'Low'
                            );


                        }
                    }
                }

                break;
            case('Supplier Sticky Note'):
                $this->update_field_switcher('Sticky Note', $value);
                break;
            case('Sticky Note'):
                $this->update_field('Supplier '.$field, $value, 'no_null');
                $this->new_value = html_entity_decode($this->new_value);
                break;
            case('Note'):
                $this->add_note($value);
                break;
            case('Supplier Average Delivery Days'):
                $this->update_field($field, $value, $options);
                $this->update_metadata = array(
                    'class_html' => array(
                        'Delivery_Time' => $this->get('Delivery Time'),
                    )

                );

                if ($value != '') {

                    include_once 'class.SupplierPart.php';

                    $sql = sprintf(
                        "SELECT `Supplier Part Key` FROM `Supplier Part Dimension` WHERE `Supplier Part Supplier Key`=%d  AND  `Supplier Part Average Delivery Days` IS NULL ", $this->id
                    );
                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {
                            $supplier_part = new SupplierPart(
                                $row['Supplier Part Key']
                            );

                            $supplier_part->update(
                                array(
                                    'Supplier Part Average Delivery Days' => $this->get(
                                        'Supplier Average Delivery Days'
                                    )
                                ), $options
                            );
                        }
                    }

                }

                break;
            case('Supplier Products Origin Country Code'):
                $this->update_field($field, $value, $options);

                include_once 'class.Part.php';

                $sql = sprintf(
                    "SELECT  `Part SKU`  FROM `Supplier Part Dimension` LEFT JOIN `Part Dimension` ON (`Part SKU`=`Supplier Part Part SKU`)  WHERE `Supplier Part Supplier Key`=%d AND  `Part Origin Country Code` IS NULL", $this->id
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $part = new Part($row['Part SKU']);

                        $part->update(
                            array('Part Origin Country Code' => $value)
                        );
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }
                break;

            case 'Supplier Default Currency Code':

                $this->update_field($field, $value, $options);

                include_once 'class.SupplierPart.php';
                $sql = sprintf(
                    'SELECT `Supplier Part Key` FROM `Supplier Part Dimension` WHERE `Supplier Part Supplier Key`=%d ', $this->id
                );

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $supplier_part = new SupplierPart(
                            $row['Supplier Part Key']
                        );

                        $supplier_part->update(
                            array(
                                'Supplier Part Currency Code' => $this->get(
                                    'Supplier Default Currency Code'
                                )
                            ), $options
                        );
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


                break;
            case 'unlink agent':


                $agent = get_object('Agent', $value);

                $sql = "DELETE FROM `Agent Supplier Bridge` WHERE `Agent Supplier Agent Key`=? AND `Agent Supplier Supplier Key`=?";

                $this->db->prepare($sql)->execute(
                    array(
                        $value,
                        $this->id
                    )
                );


                $this->update_type('Free', 'no_history');
                $agent->update_supplier_parts();

                $history_data = array(
                    'History Abstract' => sprintf(
                        _("Supplier %s unlinked from agent %s"), $this->data['Supplier Code'], $agent->get('Code')
                    ),
                    'History Details'  => '',
                    'Action'           => 'edited'
                );

                $this->add_subject_history(
                    $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                );

                break;
            case 'Supplier Purchase Order Type':


                $this->update_field($field, $value, $options);

                if ($value == 'Container') {
                    $this->other_fields_updated = array(
                        'Supplier_Default_Incoterm'       => array(
                            'field'  => 'Supplier_Default_Incoterm',
                            'render' => true,
                        ),
                        'Supplier_Default_Port_of_Export' => array(
                            'field'  => 'Supplier_Default_Port_of_Export',
                            'render' => true,
                        ),
                        'Supplier_Default_Port_of_Import' => array(
                            'field'  => 'Supplier_Default_Port_of_Import',
                            'render' => true,
                        ),
                    );
                    $hide                       = [];
                    $show                       = ['import_settings_tr'];

                } else {
                    $this->other_fields_updated = array(
                        'Supplier_Default_Incoterm'       => array(
                            'field'  => 'Supplier_Default_Incoterm',
                            'render' => false,
                        ),
                        'Supplier_Default_Port_of_Export' => array(
                            'field'  => 'Supplier_Default_Port_of_Export',
                            'render' => false,
                        ),
                        'Supplier_Default_Port_of_Import' => array(
                            'field'  => 'Supplier_Default_Port_of_Import',
                            'render' => false,
                        ),

                    );
                    $hide                       = ['import_settings_tr'];
                    $show                       = [];
                }


                $this->update_metadata = array(
                    'hide' => $hide,
                    'show' => $show
                );

                break;

            default:

                if (array_key_exists(
                    $field, $this->base_data('Supplier Data')
                )) {

                    $this->update_table_field(
                        $field, $value, $options, 'Supplier', 'Supplier Data', $this->id
                    );
                } else {


                    $this->update_field($field, $value, $options);


                }
        }


    }

    function get($key) {


        if (!$this->id) {
            return false;
        }
        list($got, $result) = $this->get_subject_supplier_common($key);

        if ($got) {
            return $result;
        }

        switch ($key) {

            case 'Supplier cooling order interval days':
            case 'Supplier minimum order amount':

                return $this->metadata(preg_replace('/\s/', '_', preg_replace('/^Supplier /', '', $key)));
                break;
            case 'minimum order amount':

                $_value = $this->metadata(preg_replace('/\s/', '_', $key));
                if (is_numeric($_value)) {
                    return money($_value, $this->data['Supplier Default Currency Code']);

                }

                break;
            case 'cooling order interval days':


                $_value = $this->metadata(preg_replace('/\s/', '_', $key));
                if (is_numeric($_value)) {
                    return number($_value, $this->data['Supplier Default Currency Code']);

                }

                break;
            case 'Number System Users':
            case 'Number Attachments':
            case 'Number Purchase Orders':
            case 'Number Number Open Purchase Orders':
            case 'Number Deliveries':
            case 'Number Invoices':
            case 'Number Parts':
            case 'Number Active Parts':
            case 'Number Images':

                return number($this->data['Supplier '.$key]);
            case 'Number Discontinuing Parts':
            case 'Number Discontinued Parts':
            case 'Number In Process Parts':

                $key = preg_replace('/Number /', '', $key);

                return number($this->metadata(preg_replace('/\s/', '_', $key)));


            case ('Purchase Order Type'):
                switch ($this->data['Supplier Purchase Order Type']) {
                    case 'Parcel':
                        return _('Parcels');
                    case 'Container':
                        return _('Containers');
                    case 'Production':
                        return _('Job orders');
                    default:
                        return $this->data['Supplier Purchase Order Type'];
                }

            default;


                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }
                if (array_key_exists('Supplier '.$key, $this->data)) {
                    return $this->data['Supplier '.$key];
                }
        }

        return '';

    }

    function update_type($value, $options = '') {

        $has_agent = 'No';
        $sql       = sprintf(
            'SELECT count(*) AS num FROM `Agent Supplier Bridge` WHERE `Agent Supplier Supplier Key`=%d', $this->id
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                if ($row['num'] > 0) {
                    $has_agent = 'Yes';
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if ($value != 'Archived') {
            if ($has_agent == 'Yes') {
                $value = 'Agent';
            } else {
                $value = 'Free';

            }

        }


        switch ($value) {
            case 'Free':
                $this->update(
                    array(
                        'Supplier Type'      => 'Free',
                        'Supplier Has Agent' => $has_agent,
                        'Supplier Valid To'  => ''

                    ), 'no_history'
                );
                break;
            case 'Agent':
                $this->update(
                    array(
                        'Supplier Type'      => 'Agent',
                        'Supplier Has Agent' => $has_agent,
                        'Supplier Valid To'  => ''
                    ), 'no_history'
                );

                break;
            case 'Archived':


                $this->update(
                    array(
                        'Supplier Type'      => 'Archived',
                        'Supplier Has Agent' => $has_agent,
                        'Supplier Valid To'  => gmdate('Y-m-d H:i:s')

                    ), 'no_history'
                );

                break;
            default:
                $this->error = true;
                $this->msg   = 'Not valid supplier type value '.$value;
                break;
        }

    }

    function create_supplier_part_record($data, $allow_duplicate_part_reference = 'No') {


        $data['editor'] = $this->editor;

        unset($data['Supplier Part Supplier Code']);


        if ($this->get('Supplier Production') == 'Yes') {
            $data['Part Reference'] = $data['Supplier Part Reference'];
        }


        //print_r($data);
        //exit;


        if (isset($data['Supplier Part Package Description']) and !isset($data['Part Package Description'])) {
            $data['Part Package Description'] = $data['Supplier Part Package Description'];
            unset($data['Supplier Part Package Description']);
        }


        if (isset($data['Supplier Part Unit Label']) and !isset($data['Part Unit Label'])) {
            $data['Part Unit Label'] = $data['Supplier Part Unit Label'];
            unset($data['Supplier Part Unit Label']);
        }


        if (!isset($data['Supplier Part Reference']) or $data['Supplier Part Reference'] == '') {
            $this->error      = true;
            $this->msg        = _("Supplier's product reference missing");
            $this->error_code = 'supplier_product_reference_missing';
            $this->metadata   = '';

            return false;
        }

        if (!isset($data['Supplier Part Description']) or $data['Supplier Part Description'] == '') {
            $this->error      = true;
            $this->msg        = _("Supplier's product unit description missing");
            $this->error_code = 'supplier_part_unt_description_missing';
            $this->metadata   = '';

            return false;

        }

        $sql = sprintf(
            'SELECT count(*) AS num FROM `Supplier Part Dimension` WHERE `Supplier Part Reference`=%s AND `Supplier Part Supplier Key`=%d  ', prepare_mysql($data['Supplier Part Reference']), $this->id

        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                if ($row['num'] > 0) {
                    $this->error      = true;
                    $this->msg        = sprintf(
                        _("Duplicated supplier's product reference (%s)"), $data['Supplier Part Reference']
                    );
                    $this->error_code = 'duplicate_supplier_part_reference';
                    $this->metadata   = $data['Supplier Part Reference'];

                    return false;
                }
            }
        }


        if (!isset($data['Part Reference']) or $data['Part Reference'] == '') {
            $this->error      = true;
            $this->msg        = _("Part reference missing");
            $this->error_code = 'part_reference_missing';
            $this->metadata   = '';

            return false;
        }

        if (empty($data['Part Recommended Product Unit Name'])) {
            $this->error      = true;
            $this->msg        = _("Unit recommended description (for website) missing");
            $this->error_code = 'part_recommended_unit_name_missing';
            $this->metadata   = '';

            return false;
        }

        $part_exist = false;

        $sql = sprintf(
            'SELECT `Part SKU` FROM `Part Dimension` WHERE `Part Reference`=%s ', prepare_mysql($data['Part Reference'])
        );


        if ($allow_duplicate_part_reference == 'No') {


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {


                    $this->error      = true;
                    $this->msg        = sprintf(_('Duplicated reference (%s)'), $data['Part Reference']);
                    $this->error_code = 'duplicate_part_reference';
                    $this->metadata   = $data['Part Reference'];

                    return false;


                }
            }


        } else {
            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {


                    $part_exist = true;
                    $part       = get_object('Part', $row['Part SKU']);


                    unset($data['Part Reference']);


                }
            }
        }


        if (!$part_exist) {
            $auto_part_barcode = false;

            if (!empty($data['Part Barcode'])) {


                if (preg_match('/^\s*(auto|automatic)\s*$/i', $data['Part Barcode'])) {

                    $auto_part_barcode = true;
                    unset($data['Part Barcode']);

                } else {


                    $barcode    = $data['Part Barcode'];
                    $error      = '';
                    $_error_mdg = '';

                    if (!is_numeric($barcode)) {
                        $error = 'No Numeric';
                    } elseif (strlen($barcode) != (12 + 1)) {
                        $error = 'Size';
                        if (strlen($barcode) == 12) {
                            $error = 'Checksum_missing';

                            $sql  = "SELECT `Part SKU` FROM `Part Dimension` WHERE `Part Barcode Number` LIKE ? ";
                            $stmt = $this->db->prepare($sql);
                            $stmt->execute(
                                array(
                                    $barcode.'%'
                                )
                            );
                            while ($row = $stmt->fetch()) {
                                $error = 'Short_Duplicated';

                            }


                        }


                    } else {
                        $digits = substr($barcode, 0, 12);

                        $digits         = (string)$digits;
                        $even_sum       = $digits[1] + $digits[3] + $digits[5] + $digits[7] + $digits[9] + $digits[11];
                        $even_sum_three = $even_sum * 3;
                        $odd_sum        = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8] + $digits[10];
                        $total_sum      = $even_sum_three + $odd_sum;
                        $next_ten       = (ceil($total_sum / 10)) * 10;
                        $check_digit    = $next_ten - $total_sum;

                        if ($check_digit != substr($barcode, -1)) {
                            $error = 'Checksum';
                        } else {
                            $sql = sprintf(
                                'SELECT `Part SKU`,`Part Reference` FROM `Part Dimension` WHERE `Part Barcode Number`=%s ', prepare_mysql($barcode)
                            );


                            if ($result = $this->db->query($sql)) {
                                foreach ($result as $row) {
                                    $part = get_object('Part', $row['Part SKU']);
                                    $part->fast_update(array('Part Barcode Number Error' => 'Duplicated'));
                                    $error      = 'Duplicated';
                                    $_error_msg = _('Duplicated').' '.$row['Part Reference'];
                                }

                            } else {
                                print_r($error_info = $this->db->errorInfo());
                                print "$sql\n";
                                exit;
                            }


                        }


                    }

                    if ($error != '') {


                        switch ($error) {

                            case 'Duplicated':
                                $error_msg = $_error_msg;
                                break;
                            case 'Size':
                                $error_msg = _('Barcode should be 13 digits');
                                break;
                            case 'Short_Duplicated':
                                $error_msg = _('Check digit missing, will duplicate');
                                break;
                            case 'Checksum_missing':
                                $error_msg = _('Check digit missing');
                                break;
                            case 'Checksum':
                                $error_msg = _('Invalid check digit');
                                break;
                            default:
                                $error_msg = $error;
                        }


                        $this->error      = true;
                        $this->msg        = $error_msg;
                        $this->error_code = 'Barcode '.$error;

                        return;
                    }


                }

            }
            if (!isset($data['Part Unit Label']) or $data['Part Unit Label'] == '') {


                $this->error      = true;
                $this->msg        = _('Unit label missing');
                $this->error_code = 'part_unit_label_missing';

                return;
            }


            if (!isset($data['Part Package Description']) or $data['Part Package Description'] == '') {


                $this->error      = true;
                $this->msg        = _('Outers (SKO) description missing');
                $this->error_code = 'part_package_description_missing';

                return;
            }


            if (!isset($data['Part Units Per Package']) or $data['Part Units Per Package'] == '') {
                $this->error      = true;
                $this->msg        = _('Units per SKO missing');
                $this->error_code = 'part_unit_per_package_missing';

                return;
            }

            if (!is_numeric($data['Part Units Per Package']) or $data['Part Units Per Package'] < 0) {
                $this->error      = true;
                $this->msg        = sprintf(
                    _('Invalid units per SKO (%s)'), $data['Part Units Per Package']
                );
                $this->error_code = 'invalid_part_unit_per_package';
                $this->metadata   = $data['Part Units Per Package'];

                return;
            }


            if (isset($data['Part Unit Price']) and $data['Part Unit Price'] != '') {
                if (!is_numeric($data['Part Unit Price']) or $data['Part Unit Price'] < 0) {
                    $this->error      = true;
                    $this->msg        = sprintf(
                        _('Invalid unit recommended price (%s)'), $data['Part Unit Price']
                    );
                    $this->error_code = 'invalid_part_unit_price';
                    $this->metadata   = $data['Part Unit Price'];

                    return;
                }
            }
            if (isset($data['Part Unit RRP']) and $data['Part Unit RRP'] != '') {
                if (!is_numeric($data['Part Unit RRP']) or $data['Part Unit RRP'] < 0) {
                    $this->error      = true;
                    $this->msg        = sprintf(
                        _('Invalid unit recommended RRP (%s)'), $data['Part Unit RRP']
                    );
                    $this->error_code = 'invalid_part_unit_rrp';
                    $this->metadata   = $data['Part Unit RRP'];

                    return;
                }
            }

        }


        if (!isset($data['Supplier Part Packages Per Carton']) or $data['Supplier Part Packages Per Carton'] == '') {
            $this->error      = true;
            $this->msg        = _('Outers (SKO) per carton missing');
            $this->error_code = 'supplier_part_packages_per_carton_missing';

            return;
        }

        if (!is_numeric($data['Supplier Part Packages Per Carton']) or $data['Supplier Part Packages Per Carton'] < 0) {
            $this->error      = true;
            $this->msg        = sprintf(
                _('Invalid outers (SKO) per carton (%s)'), $data['Supplier Part Packages Per Carton']
            );
            $this->error_code = 'invalid_supplier_part_packages_per_carton';
            $this->metadata   = $data['Supplier Part Packages Per Carton'];

            return;
        }


        if (!isset($data['Supplier Part Minimum Carton Order']) or $data['Supplier Part Minimum Carton Order'] == '') {
            $data['Supplier Part Minimum Carton Order'] = 1;
        }

        if (!is_numeric($data['Supplier Part Minimum Carton Order']) or $data['Supplier Part Minimum Carton Order'] < 0) {
            $this->error      = true;
            $this->msg        = sprintf(
                _('Invalid minimum order (%s)'), $data['Supplier Part Minimum Carton Order']
            );
            $this->error_code = 'invalid_supplier_part_minimum_carton_order';
            $this->metadata   = $data['Supplier Part Minimum Carton Order'];

            return;
        }

        if ($this->get('Supplier Production') == 'No') {
            if (!isset($data['Supplier Part Unit Cost']) or $data['Supplier Part Unit Cost'] == '') {
                $this->error      = true;
                $this->msg        = _('Cost missing');
                $this->error_code = 'supplier_part_unit_cost_missing';

                return;
            }

            if (!is_numeric($data['Supplier Part Unit Cost']) or $data['Supplier Part Unit Cost'] < 0) {
                $this->error      = true;
                $this->msg        = sprintf(
                    _('Invalid cost (%s)'), $data['Supplier Part Unit Cost']
                );
                $this->error_code = 'invalid_supplier_part_unit_cost';
                $this->metadata   = $data['Supplier Part Unit Cost'];

                return;
            }


            if (!isset($data['Supplier Part Average Delivery Days']) or $data['Supplier Part Average Delivery Days'] == '') {
                $data['Supplier Part Average Delivery Days'] = $this->get('Supplier Average Delivery Days');
            } else {
                if (!is_numeric($data['Supplier Part Average Delivery Days']) or $data['Supplier Part Average Delivery Days'] < 0) {
                    $this->error      = true;
                    $this->msg        = sprintf(
                        _('Invalid delivery time (%s)'), $data['Supplier Part Average Delivery Days']
                    );
                    $this->error_code = 'invalid_supplier_delivery_days';
                    $this->metadata   = $data['Supplier Part Average Delivery Days'];

                    return;
                }

            }

            if (isset($data['Supplier Part Unit Extra Cost Percentage']) and $data['Supplier Part Unit Extra Cost Percentage'] == '') {
                $data['Supplier Part Unit Extra Cost Percentage'] = 0;

            }


            if (preg_match('/\%$/', $data['Supplier Part Unit Extra Cost Percentage'])) {
                $data['Supplier Part Unit Extra Cost Percentage'] = floatval(preg_replace('/\%$/', '', $data['Supplier Part Unit Extra Cost Percentage'])) / 100;
                // $value = $this->data['Supplier Part Unit Cost'] * $value / 100;
            }

            if (isset($data['Supplier Part Unit Extra Cost Percentage']) and (!is_numeric($data['Supplier Part Unit Extra Cost Percentage']) or $data['Supplier Part Unit Extra Cost Percentage'] < 0)) {
                $this->error      = true;
                $this->msg        = sprintf(_('Invalid extra %% cost (%s)'), $data['Supplier Part Unit Extra Cost Percentage']);
                $this->error_code = 'invalid_supplier_part_extra_cost_percentage';
                $this->metadata   = $data['Supplier Part Unit Extra Cost Percentage'];

                return;
            }


        }

        if (isset($data['Supplier Part Carton CBM']) and $data['Supplier Part Carton CBM'] != '') {
            if (!is_numeric($data['Supplier Part Carton CBM']) or $data['Supplier Part Carton CBM'] < 0) {
                $this->error      = true;
                $this->msg        = sprintf(_('Invalid carton CBM (%s)'), $data['Supplier Part Carton CBM']);
                $this->error_code = 'invalid_supplier_part_carton_cbm';
                $this->metadata   = $data['Supplier Part Carton CBM'];

                return;
            }
        }


        $data['Supplier Part Supplier Key'] = $this->id;
        $data['Supplier Part Production']   = $this->get('Supplier Production');

        $data['Supplier Part Minimum Carton Order'] = ceil($data['Supplier Part Minimum Carton Order']);


        $data['Supplier Part Currency Code'] = $this->data['Supplier Default Currency Code'];


        $data['Supplier Part Status'] = 'Available';


        $supplier_part = new SupplierPart('find', $data, 'create');


        if ($supplier_part->id) {
            $this->new_object_msg = $supplier_part->msg;

            if ($supplier_part->new) {
                $this->new_object = true;


                if ($this->get('Supplier Production') == 'Yes') {

                    $sql = "INSERT INTO `Production Part Dimension` (`Production Part Supplier Part Key`) VALUES (?)";
                    $this->db->prepare($sql)->execute([$supplier_part->id]);

                }


                foreach ($data as $key => $value) {
                    $_key        = preg_replace('/^Part Part /', 'Part ', $key);
                    $data[$_key] = $value;

                }

                if (isset($data['Part Materials'])) {
                    $materials = $data['Part Materials'];
                    unset($data['Part Materials']);

                } else {
                    $materials = '';
                }

                if (isset($data['Part Package Dimensions'])) {
                    $package_dimensions = $data['Part Package Dimensions'];
                    unset($data['Part Package Dimensions']);

                } else {
                    $package_dimensions = '';
                }

                if (isset($data['Part Unit Dimensions'])) {
                    $unit_dimensions = $data['Part Unit Dimensions'];
                    unset($data['Part Unit Dimensions']);

                } else {
                    $unit_dimensions = '';
                }


                if (!empty($data['Supplier Part Packages Per Carton'])) {
                    $data['Part SKOs per Carton'] = $data['Supplier Part Packages Per Carton'];

                }

                $data['Part Production'] = $this->get('Supplier Production');


                $data['Part Main Supplier Part Key'] = $supplier_part->id;


                if (!$part_exist) {

                    // print "====\n";
                    // print_r($data);

                    $part = new Part('find', $data, 'create');

                    // print_r($part);
                    if ($part->new) {


                        $part->fast_update(array('Part Carton Barcode' => $supplier_part->get('Supplier Part Carton Barcode')));


                        $part->update(
                            array(
                                'Part Materials'          => $materials,
                                'Part Package Dimensions' => $package_dimensions,
                                'Part Unit Dimensions'    => $unit_dimensions,
                                'Part SKOs per Carton'    => $supplier_part->get('Supplier Part Packages Per Carton')

                            ), 'no_history'
                        );

                        if ($auto_part_barcode) {


                            $barcode_number = '';
                            $sql            = sprintf("SELECT `Barcode Number` FROM `Barcode Dimension` WHERE `Barcode Status`='Available' ORDER BY `Barcode Number`");
                            if ($result = $this->db->query($sql)) {
                                if ($row = $result->fetch()) {
                                    $barcode_number = $row['Barcode Number'];
                                }
                            } else {
                                print_r($error_info = $this->db->errorInfo());
                                exit;
                            }

                            if ($barcode_number != '') {
                                $part->update(
                                    array(
                                        'Part Barcode' => $barcode_number,


                                    ), 'no_history'
                                );
                            }

                        }

                        $supplier_part->update(array('Supplier Part Part SKU' => $part->id));
                        $supplier_part->get_data('id', $supplier_part->id);


                        $supplier_part->update_historic_object();
                        $this->update_supplier_parts();
                        $part->update_cost();


                    } else {

                        $this->error = true;
                        if ($part->found) {

                            $this->error_code     = 'duplicated_field';
                            $this->error_metadata = json_encode(
                                array($part->duplicated_field)
                            );

                            if ($part->duplicated_field == 'Part Reference') {
                                $this->msg = _("Duplicated part reference");
                            } else {
                                $this->msg = 'Duplicated '.$part->duplicated_field;
                            }


                        } else {
                            $this->msg = $part->msg;
                        }

                        $sql = sprintf(
                            'DELETE FROM `Supplier Part Dimension` WHERE `Supplier Part Key`=%d', $supplier_part->id
                        );
                        $this->db->exec($sql);
                        $sql = sprintf(
                            'SELECT `History Key` FROM `Supplier Part History Bridge` WHERE `Supplier Part Key`=%d', $supplier_part->id
                        );
                        if ($result = $this->db->query($sql)) {
                            foreach ($result as $row) {
                                $sql = sprintf(
                                    'DELETE FROM `History Dimension` WHERE `History Key`=%d  ', $row['History Key']
                                );
                                $this->db->exec($sql);
                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            exit;
                        }

                        $sql = sprintf(
                            'DELETE FROM `Supplier Part Dimension` WHERE `Supplier Part Key`=%d', $supplier_part->id
                        );
                        $this->db->exec($sql);
                        $supplier_part = new SupplierPart(0);

                    }
                } else {
                    $supplier_part->update(array('Supplier Part Part SKU' => $part->id));
                    $supplier_part->get_data('id', $supplier_part->id);


                    $supplier_part->update_historic_object();
                    $this->update_supplier_parts();
                    $part->update_cost();
                }


            } else {

                $this->error = true;
                if ($supplier_part->found) {

                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(
                        array($supplier_part->duplicated_field)
                    );

                    if ($supplier_part->duplicated_field == 'Supplier Part Reference') {
                        $this->msg = _("Duplicated supplier's product reference");
                    } else {
                        $this->msg = 'Duplicated '.$supplier_part->duplicated_field;
                    }


                } else {
                    $this->msg = $supplier_part->msg;
                }
            }

            return $supplier_part;
        } else {
            $this->error = true;

            if ($supplier_part->found) {
                $this->error_code     = 'duplicated_field';
                $this->error_metadata = json_encode(
                    array($supplier_part->duplicated_field)
                );

                if ($supplier_part->duplicated_field == 'Part Reference') {
                    $this->msg = _("Duplicated part reference");
                } else {
                    $this->msg = 'Duplicated '.$supplier_part->duplicated_field;
                }

            } else {


                $this->msg = $supplier_part->msg;
            }
        }

    }

    function update_supplier_parts() {


        //  $parts_skos = $this->get_part_skus();


        $supplier_number_parts = 0;

        $supplier_number_active_parts       = 0;
        $supplier_number_surplus_parts      = 0;
        $supplier_number_optimal_parts      = 0;
        $supplier_number_low_parts          = 0;
        $supplier_number_critical_parts     = 0;
        $supplier_number_out_of_stock_parts = 0;

        $supplier_number_in_process_parts    = 0;
        $supplier_number_discontinuing_parts = 0;

        $supplier_number_discontinued_parts = 0;


        $sql = "SELECT count(*) AS num ,`Part Status`

		FROM`Part Dimension` P left join  `Supplier Part Dimension` SP   ON (P.`Part SKU`=SP.`Supplier Part Part SKU`)  WHERE `Supplier Part Supplier Key`=? group by `Part Status` ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id,
            )
        );


        while ($row = $stmt->fetch()) {
            if ($row['Part Status'] == 'In Use') {
                $supplier_number_active_parts = $row['num'];
            } elseif ($row['Part Status'] = 'Discontinuing') {
                $supplier_number_discontinuing_parts = $row['num'];
            } elseif ($row['Part Status'] = 'In Process') {
                $supplier_number_in_process_parts = $row['num'];
            } elseif ($row['Part Status'] = 'Not In Use') {
                $supplier_number_discontinued_parts = $row['num'];
            }


        }

        $supplier_number_parts = $supplier_number_active_parts + $supplier_number_discontinuing_parts + $supplier_number_in_process_parts + $supplier_number_discontinued_parts;


        $sql = "SELECT count(*) AS num ,`Part Stock Status`

		FROM `Supplier Part Dimension` SP  LEFT JOIN `Part Dimension` P ON (P.`Part SKU`=SP.`Supplier Part Part SKU`)  WHERE `Supplier Part Supplier Key`=? and `Part Status`='In Use' group by `Part Stock Status`  ";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id,
            )
        );
        while ($row = $stmt->fetch()) {


            if ($row['Part Stock Status'] == 'Surplus') {
                $supplier_number_surplus_parts = $row['num'];
            } elseif ($row['Part Stock Status'] == 'Optimal') {
                $supplier_number_optimal_parts = $row['num'];
            } elseif ($row['Part Stock Status'] == 'Low') {
                $supplier_number_low_parts = $row['num'];
            } elseif ($row['Part Stock Status'] == 'Critical') {
                $supplier_number_critical_parts = $row['num'];
            } elseif ($row['Part Stock Status'] == 'Out_Of_Stock') {
                $supplier_number_out_of_stock_parts = $row['num'];
            }


        }


        $this->fast_update(
            array(
                'Supplier Number Parts'              => $supplier_number_parts,
                'Supplier Number Active Parts'       => $supplier_number_active_parts,
                'Supplier Number Surplus Parts'      => $supplier_number_surplus_parts,
                'Supplier Number Optimal Parts'      => $supplier_number_optimal_parts,
                'Supplier Number Low Parts'          => $supplier_number_low_parts,
                'Supplier Number Critical Parts'     => $supplier_number_critical_parts,
                'Supplier Number Out Of Stock Parts' => $supplier_number_out_of_stock_parts,

            )
        );

        $this->fast_update_json_field('Supplier Metadata', 'Discontinuing_Parts', $supplier_number_discontinuing_parts);
        $this->fast_update_json_field('Supplier Metadata', 'Discontinued_Parts', $supplier_number_discontinued_parts);
        $this->fast_update_json_field('Supplier Metadata', 'In_Process_Parts', $supplier_number_in_process_parts);


        foreach ($this->get_categories('objects') as $category) {
            $category->update_supplier_category_parts();
        }


    }

    function get_part_skus($type = 'all') {


        $part_skus = '';

        if ($type == 'in_use') {
            $sql =
                "SELECT `Supplier Part Part SKU` FROM `Supplier Part Dimension` SP LEFT JOIN `Part Dimension` P ON (P.`Part SKU`=SP.`Supplier Part Part SKU`) WHERE `Supplier Part Supplier Key`=? AND `Part Status` IN ('In Use','Discontinuing') AND `Supplier Part Status`!='Discontinued' GROUP BY `Supplier Part Part SKU`";


        } else {
            $sql = "SELECT `Supplier Part Part SKU` FROM `Supplier Part Dimension` WHERE `Supplier Part Supplier Key`=?  GROUP BY `Supplier Part Part SKU`";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        while ($row = $stmt->fetch()) {
            if (is_numeric($row['Supplier Part Part SKU']) and $row['Supplier Part Part SKU'] > 0) {
                $part_skus .= $row['Supplier Part Part SKU'].',';

            }
        }


        return preg_replace('/,$/', '', $part_skus);

    }

    function get_categories($scope = 'keys') {

        if ($scope == 'objects') {
            include_once 'class.Category.php';
        }


        $categories = array();


        $sql = sprintf(
            "SELECT B.`Category Key` FROM `Category Dimension` C LEFT JOIN `Category Bridge` B ON (B.`Category Key`=C.`Category Key`) WHERE `Subject`='Supplier' AND `Subject Key`=%d AND `Category Branch Type`!='Root'", $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($scope == 'objects') {
                    $categories[$row['Category Key']] = new Category(
                        $row['Category Key']
                    );
                } else {
                    $categories[$row['Category Key']] = $row['Category Key'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        return $categories;


    }

    function get_field_label($field) {


        switch ($field) {
            case 'Supplier Purchase Order Type':
                $label = _('delivery type');
                break;
            case 'Supplier Code':
                $label = _('code');
                break;
            case 'Supplier Name':
                $label = _('name');
                break;
            case 'Supplier Location':
                $label = _('location');
                break;
            case 'Supplier Company Name':
                $label = _('company name');
                break;
            case 'Supplier Main Contact Name':
                $label = _('contact name');
                break;
            case 'Supplier Main Plain Email':
                $label = _('email');
                break;
            case 'Supplier Main Email':
                $label = _('main email');
                break;
            case 'Supplier Other Email':
                $label = _('other email');
                break;
            case 'Supplier Main Plain Telephone':
            case 'Supplier Main XHTML Telephone':
                $label = _('telephone');
                break;
            case 'Supplier Main Plain Mobile':
            case 'Supplier Main XHTML Mobile':
                $label = _('mobile');
                break;
            case 'Supplier Main Plain FAX':
            case 'Supplier Main XHTML Fax':
                $label = _('fax');
                break;
            case 'Supplier Other Telephone':
                $label = _('other telephone');
                break;
            case 'Supplier Preferred Contact Number':
                $label = _('main contact number');
                break;
            case 'Supplier Fiscal Name':
                $label = _('fiscal name');
                break;

            case 'Supplier Contact Address':
                $label = _('contact address');
                break;
            case 'Supplier Average Delivery Days':
                $label = _('delivery time (days)');
                break;
            case 'Supplier Average Production Days':
                $label = _('production waiting time (days)');
                break;
            case 'Supplier cooling order interval days':
                $label = _('Cooling period between orders (days)');
                break;
            case 'Supplier minimum order amount':
                $label = _('Minimum order');
                break;
            case 'Supplier Default Currency Code':
                $label = _('currency');
                break;
            case 'Part Origin Country Code':
                $label = _('country of origin');
                break;
            case 'Supplier Default Incoterm':
                $label = _('Incoterm');
                break;
            case 'Supplier Default Port of Export':
                $label = _('Port of export');
                break;
            case 'Supplier Default Port of Import':
                $label = _('port of import');
                break;
            case 'Supplier Default PO Terms and Conditions':
                $label = _('T&C');
                break;
            case 'Supplier Show Warehouse TC in PO':
                $label = _('Include general T&C');
                break;
            case 'Supplier User Active':
                $label = _('active');
                break;
            case 'Supplier User Handle':
                $label = _('login');
                break;
            case 'Supplier User Password':
                $label = _('password');
                break;
            case 'Supplier User PIN':
                $label = _('PIN');
                break;
            case 'Supplier On Demand':
                $label = _('Allow on demand');
                break;
            case 'Supplier Account Number':
                $label = _("Account number");
                break;
            case 'Supplier Skip Inputting':
                $label = _("Skip inputting");
                break;
            case 'Supplier Skip Mark as Dispatched':
                $label = _("Skip mark as dispatched");
                break;
            case 'Supplier Skip Mark as Received':
                $label = _("Skip mark as received");
                break;
            case 'Supplier Skip Checking':
                $label = _("Skip checking");
                break;
            case 'Supplier Automatic Placement Location':
                $label = _("Try automatic placement location");
                break;
            case 'Supplier QQ':
                $label = 'QQ';
                break;
            case 'Supplier Order Public ID Format':
                $label = _("order number format");
                break;

            case 'Supplier Order Last Order ID':
                $label = _("last incremental order number");
                break;
            case 'Supplier Nickname':
                $label = _("nickname");
                break;
            case 'Supplier Website':
                $label = _("Website");
                break;
            default:
                $label = $field;

        }

        return $label;

    }

    /**
     * @param string $scope
     *
     * @return array
     */
    public function get_agents($scope = 'keys') {

        $agents_data = array();
        $sql         = sprintf(
            'SELECT `Agent Code`,`Agent Key`,`Agent Name`  FROM `Agent Supplier Bridge` LEFT JOIN `Agent Dimension` ON (`Agent Supplier Agent Key`=`Agent Key`)  WHERE `Agent Supplier Supplier Key`=%d', $this->id
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                if ($scope == 'data') {
                    $agents_data[$row['Agent Key']] = array(
                        'Agent Key'  => $row['Agent Key'],
                        'Agent Code' => $row['Agent Code'],
                        'Agent Name' => $row['Agent Name'],

                    );
                } elseif ($scope == 'objects') {
                    $agents_data[$row['Agent Key']] = get_object('Agent', $row['Agent Key']);
                } else {
                    $agents_data[$row['Agent Key']] = $row['Agent Key'];
                }


            }
        }

        return $agents_data;

    }

    function archive() {

        $this->update_type('Archived', 'no_history');


        $sql  = 'Select `Part SKU`,`Part Reference` from `Part Dimension`   left join `Supplier Part Dimension` on (`Part Main Supplier Part Key`=`Supplier Part Key`)  where `Supplier Part Supplier Key`=?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        while ($row = $stmt->fetch()) {
            $part = get_object('Part', $row['Part SKU']);


            $supplier_parts = $part->get_supplier_parts('objects');

            if (count($supplier_parts) > 1) {
                foreach ($supplier_parts as $supplier_part) {
                    if ($supplier_part->get('Supplier Part Supplier Key') != $this->id) {

                        $_supplier = get_object('Supplier', $supplier_part->get('Supplier Key'));
                        if ($_supplier->get('Supplier Type') != 'Archived') {
                            $part->editor = $this->editor;
                            $part->update(
                                [
                                    'Part Main Supplier Part Key' => $supplier_part->id
                                ]
                            );
                            break;
                        }
                    }
                }
            }
        }


        $history_data = array(
            'History Abstract' => sprintf(
                _("Supplier %s archived"), $this->data['Supplier Code']
            ),
            'History Details'  => '',
            'Action'           => 'edited'
        );

        $this->add_subject_history(
            $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
        );


    }

    function unarchive() {

        $this->update_type('Free', 'no_history');


        $history_data = array(
            'History Abstract' => sprintf(
                _("Supplier %s unarchived"), $this->data['Supplier Code']
            ),
            'History Details'  => '',
            'Action'           => 'edited'
        );

        $this->add_subject_history(
            $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
        );


    }

    function delete($metadata = false) {

        $this->load_acc_data();


        $sql = 'INSERT INTO `Supplier Deleted Dimension`  (`Supplier Deleted Key`,`Supplier Deleted Code`,`Supplier Deleted Name`,`Supplier Deleted Date`,`Supplier Deleted Metadata`) VALUES (?,?,?,?,?) ';

        $this->db->prepare($sql)->execute(
            array(
                $this->id,
                $this->get('Supplier Code'),
                $this->get('Supplier Name'),
                gmdate('Y-m-d H:i:s'),
                gzcompress(json_encode($this->data), 9)
            )
        );


        $sql = sprintf(
            'DELETE FROM `Supplier Dimension`  WHERE `Supplier Key`=%d ', $this->id
        );
        $this->db->exec($sql);


        $history_data = array(
            'History Abstract' => sprintf(
                _("Supplier record %s deleted"), $this->data['Supplier Name']
            ),
            'History Details'  => '',
            'Action'           => 'deleted'
        );

        $this->add_subject_history(
            $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
        );


        $this->deleted = true;


        $sql = sprintf(
            'SELECT `Supplier Part Key` FROM `Supplier Part Dimension` WHERE `Supplier Part Supplier Key`=%d  ', $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $supplier_part = get_object(
                    'Supplier Part', $row['Supplier Part Key']
                );
                $supplier_part->delete();
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }

    function load_acc_data() {
        $sql = sprintf(
            "SELECT * FROM `Supplier Data` WHERE `Supplier Key`=%d", $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                foreach ($row as $key => $value) {
                    $this->data[$key] = $value;
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }

    function create_timeseries($data, $fork_key = 0) {


        include_once 'class.Timeserie.php';

        $data['Timeseries Parent']     = 'Supplier';
        $data['Timeseries Parent Key'] = $this->id;


        $data['editor'] = $this->editor;

        $timeseries = new Timeseries('find', $data, 'create');


        if ($timeseries->id) {
            require_once 'utils/date_functions.php';

            if ($this->data['Supplier Valid From'] != '') {
                $from = date('Y-m-d', strtotime($this->get('Valid From')));

            } else {
                $from = '';
            }

            if ($this->get('Supplier Type') == 'Archived') {
                $to = $this->get('Valid To');
            } else {
                $to = date('Y-m-d');
            }


            $sql = sprintf(
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
                $timeseries->update(
                    array('Timeseries Updated' => gmdate('Y-m-d H:i:s')), 'no_history'
                );
            }

            if ($from and $to) {
                $this->update_timeseries_record($timeseries, $from, $to, $fork_key);
            }


            if ($timeseries->get('Timeseries Number Records') == 0) {
                $timeseries->update(
                    array('Timeseries Updated' => gmdate('Y-m-d H:i:s')), 'no_history'
                );
            }


        }

    }

    /*

    function update_timseries_date($date) {
        include_once 'class.Timeserie.php';
        $sql = sprintf(
            'SELECT `Timeseries Key` FROM `Timeseries Dimension` WHERE `Timeseries Parent`="Supplier" AND `Timeseries Parent Key`=%d ', $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $timeseries = new Timeseries($row['Timeseries Key']);
                $this->update_timeseries_record($timeseries, $date, $date);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }
    */

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


            $sales_data = $this->get_sales_data($date_frequency_period['from'], $date_frequency_period['to']);


            $_date = gmdate('Y-m-d', strtotime($date_frequency_period['from'].' +0:00'));


            if ($sales_data['deliveries'] > 0 or $sales_data['supplier_deliveries'] > 0 or $sales_data['dispatched'] > 0 or $sales_data['invoiced_amount'] != 0 or $sales_data['required'] != 0 or $sales_data['profit'] != 0 or $sales_data['purchased_amount'] != 0) {

                list($timeseries_record_key, $date) = $timeseries->create_record(array('Timeseries Record Date' => $_date));


                $sql = sprintf(
                    'DELETE FROM `Timeseries Record Drill Down` WHERE `Timeseries Record Drill Down Timeseries Record Key`=%d  ', $timeseries_record_key
                );
                //print $sql;
                $this->db->exec($sql);


                $sql = sprintf(
                    'UPDATE `Timeseries Record Dimension` SET 
                              `Timeseries Record Integer A`=%d ,`Timeseries Record Integer B`=%d ,`Timeseries Record Integer C`=%d ,
                              `Timeseries Record Float A`=%.2f ,  `Timeseries Record Float B`=%f ,`Timeseries Record Float C`=%f ,`Timeseries Record Float D`=%f ,
                              `Timeseries Record Type`=%s WHERE `Timeseries Record Key`=%d', $sales_data['dispatched'], $sales_data['deliveries'], $sales_data['supplier_deliveries'], $sales_data['invoiced_amount'], $sales_data['required'], $sales_data['profit'],
                    $sales_data['purchased_amount'], prepare_mysql('Data'), $timeseries_record_key

                );


                $update_sql = $this->db->prepare($sql);
                $update_sql->execute();


                if ($update_sql->rowCount() or $date == date('Y-m-d')) {
                    $timeseries->fast_update(array('Timeseries Updated' => gmdate('Y-m-d H:i:s')));
                }


                if (in_array(
                    $timeseries->get('Timeseries Frequency'), array(
                                                                'Monthly',
                                                                'Quarterly',
                                                                'Yearly'
                                                            )
                )) {

                    foreach (preg_split('/\,/', $this->get_part_family_keys()) as $family_key) {


                        $part_skus = array();


                        $sql = sprintf('SELECT `Part SKU` FROM `Part Dimension`  left join `Supplier Part Dimension` on (`Part SKU`= `Supplier Part Part SKU`)    WHERE  `Supplier Part Supplier Key`=%d and  `Part Family Category Key`=%d ', $this->id, $family_key);
                        if ($result = $this->db->query($sql)) {
                            foreach ($result as $row) {
                                $part_skus[$row['Part SKU']] = $row['Part SKU'];
                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }

                        $part_skus = join(',', $part_skus);


                        // print 'XXX:'.$part_skus;
                        //  exit;

                        $sales_data = $this->get_sales_data($date_frequency_period['from'], $date_frequency_period['to'], $part_skus);
                        $from_1yb   = date('Y-m-d H:i:s', strtotime($date_frequency_period['from'].' -1 year'));
                        $to_1yb     = date('Y-m-d H:i:s', strtotime($date_frequency_period['to'].' -1 year'));


                        $sales_data_1yb = $this->get_sales_data($from_1yb, $to_1yb, $part_skus);

                        if ($sales_data['deliveries'] > 0 or $sales_data['dispatched'] > 0 or $sales_data['invoiced_amount'] != 0 or $sales_data['required'] != 0 or $sales_data['profit'] != 0 or $sales_data_1yb['deliveries'] > 0 or $sales_data_1yb['dispatched'] > 0
                            or $sales_data_1yb['invoiced_amount'] != 0 or $sales_data_1yb['required'] != 0 or $sales_data_1yb['profit'] != 0) {


                            $sql = sprintf(
                                'INSERT INTO `Timeseries Record Drill Down` (`Timeseries Record Drill Down Timeseries Record Key`,`Timeseries Record Drill Down Subject`,`Timeseries Record Drill Down Subject Key`,
`Timeseries Record Drill Down Float A`,`Timeseries Record Drill Down Float B`,`Timeseries Record Drill Down Float C`,`Timeseries Record Drill Down Float D`,
`Timeseries Record Drill Down Integer A`,`Timeseries Record Drill Down Integer B`,`Timeseries Record Drill Down Integer C`,`Timeseries Record Drill Down Integer D`
)
                    VALUES (%d,%s,%d, %f,%f,%f,%f, %d,%d,%d,%d)', $timeseries_record_key, prepare_mysql('Category'), $family_key,

                                $sales_data['invoiced_amount'], $sales_data['profit'], $sales_data_1yb['invoiced_amount'], $sales_data_1yb['profit'], $sales_data['dispatched'], $sales_data['deliveries'], $sales_data_1yb['dispatched'], $sales_data_1yb['deliveries']


                            );

                            //print "$sql\n";
                            $this->db->exec($sql);
                            // exit;
                        }

                    }


                    foreach (preg_split('/\,/', $this->get_part_skus()) as $part_sku) {

                        $sales_data = $this->get_sales_data($date_frequency_period['from'], $date_frequency_period['to'], $part_sku);
                        $from_1yb   = date('Y-m-d H:i:s', strtotime($date_frequency_period['from'].' -1 year'));
                        $to_1yb     = date('Y-m-d H:i:s', strtotime($date_frequency_period['to'].' -1 year'));


                        $sales_data_1yb = $this->get_sales_data($from_1yb, $to_1yb, $part_sku);

                        if ($sales_data['deliveries'] > 0 or $sales_data['dispatched'] > 0 or $sales_data['invoiced_amount'] != 0 or $sales_data['required'] != 0 or $sales_data['profit'] != 0 or $sales_data_1yb['deliveries'] > 0 or $sales_data_1yb['dispatched'] > 0
                            or $sales_data_1yb['invoiced_amount'] != 0 or $sales_data_1yb['required'] != 0 or $sales_data_1yb['profit'] != 0

                        ) {


                            $sql = sprintf(
                                'INSERT INTO `Timeseries Record Drill Down` (`Timeseries Record Drill Down Timeseries Record Key`,`Timeseries Record Drill Down Subject`,`Timeseries Record Drill Down Subject Key`,
`Timeseries Record Drill Down Float A`,`Timeseries Record Drill Down Float B`,`Timeseries Record Drill Down Float C`,`Timeseries Record Drill Down Float D`,
`Timeseries Record Drill Down Integer A`,`Timeseries Record Drill Down Integer B`,`Timeseries Record Drill Down Integer C`,`Timeseries Record Drill Down Integer D`
)
                    VALUES (%d,%s,%d, %f,%f,%f,%f, %d,%d,%d,%d)', $timeseries_record_key, prepare_mysql('Part'), $part_sku,

                                $sales_data['invoiced_amount'], $sales_data['profit'], $sales_data_1yb['invoiced_amount'], $sales_data_1yb['profit'], $sales_data['dispatched'], $sales_data['deliveries'], $sales_data_1yb['dispatched'], $sales_data_1yb['deliveries']


                            );

                            //print "$sql\n";
                            $this->db->exec($sql);
                            // exit;
                        }

                    }
                }


            } else {


                $sql = sprintf(
                    'select `Timeseries Record Key` FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`=%s ', $timeseries->id, prepare_mysql($_date)
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $sql = sprintf(
                            'DELETE FROM `Timeseries Record Drill Down` WHERE `Timeseries Record Drill Down Timeseries Record Key`=%d  ', $row['Timeseries Record Key']
                        );
                        //print $sql;
                        $this->db->exec($sql);

                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
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

        if ($fork_key) {

            $sql = sprintf(
                "UPDATE `Fork Dimension` SET `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=%d,`Fork Result`=%d WHERE `Fork Key`=%d ", $index, $timeseries->id, $fork_key
            );

            $this->db->exec($sql);

        }

    }

    function get_part_family_keys() {


        $part_family_keys = '';


        $sql = sprintf(
            'SELECT `Part Family Category Key` FROM `Supplier Part Dimension` left join `Part Dimension` on (`Part SKU`=`Supplier Part Part SKU`) WHERE `Supplier Part Supplier Key`=%d group by `Part Family Category Key` ', $this->id
        );


        //  print "$sql\n";

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                if (is_numeric($row['Part Family Category Key']) and $row['Part Family Category Key'] > 0) {
                    $part_family_keys .= $row['Part Family Category Key'].',';

                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }
        $part_family_keys = preg_replace('/\,$/', '', $part_family_keys);


        return $part_family_keys;

    }

    function update_supplier_paid_ordered_parts() {

        $paid_ordered_parts                               = 0;
        $to_replenish_picking_location_paid_ordered_parts = 0;


        $sql = sprintf(
            'SELECT count(DISTINCT P.`Part SKU`) AS num FROM 
              `Part Dimension` P  LEFT JOIN `Supplier Part Dimension` SP ON (SP.`Supplier Part Part SKU`=P.`Part SKU`) 
              WHERE  `Supplier Part Supplier Key`=%d', $this->id
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


        $sql = sprintf(
            'SELECT count(DISTINCT P.`Part SKU`) AS num FROM 
              `Part Dimension` P LEFT JOIN `Part Location Dimension` PL ON (PL.`Part SKU`=P.`Part SKU`)  LEFT JOIN `Supplier Part Dimension` SP ON (SP.`Supplier Part Part SKU`=P.`Part SKU`) 
              WHERE (`Part Current Stock In Process`+ `Part Current Stock Ordered Paid`)>`Quantity On Hand`   AND (`Part Current Stock In Process`+ `Part Current Stock Ordered Paid`)>0   AND `Can Pick`="Yes"   AND `Supplier Part Supplier Key`=%d ', $this->id
        );


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
                'Supplier Paid Ordered Parts'              => $paid_ordered_parts,
                'Supplier Paid Ordered Parts To Replenish' => $to_replenish_picking_location_paid_ordered_parts

            ), 'no_history'
        );


    }


    function update_supplier_part_locations_to_replenish() {

        $replenishable_part_locations = 0;
        $part_locations_to_replenish  = 0;


        $sql = sprintf(
            'SELECT count(*) AS num FROM `Part Location Dimension`   PLD  LEFT JOIN 
            `Part Dimension` P ON (PLD.`Part SKU`=P.`Part SKU`) 
            
            LEFT JOIN `Supplier Part Dimension` SP ON (SP.`Supplier Part Part SKU`=P.`Part SKU`)   WHERE   `Supplier Part Supplier Key`=%d     AND  `Minimum Quantity`>=0 AND `Can Pick`="Yes"   ', $this->id
        );
        //print $sql;
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $replenishable_part_locations = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        /*
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

        */
        $sql = sprintf(
            " 
 SELECT count(*) AS num  FROM
 `Part Location Dimension` PL  LEFT JOIN `Part Dimension` P ON (PL.`Part SKU`=P.`Part SKU`)   LEFT JOIN `Supplier Part Dimension` SP ON (SP.`Supplier Part Part SKU`=P.`Part SKU`) 
 
  WHERE `Can Pick`='Yes' AND `Minimum Quantity`>=0 AND   `Minimum Quantity`>=(`Quantity On Hand`- `Part Current Stock In Process`- `Part Current Stock Ordered Paid` )  
   AND `Supplier Part Supplier Key`=%d

", $this->id
        );


        //print $sql;
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $part_locations_to_replenish = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->update(
            array(
                'Supplier Replenishable Part Locations' => $replenishable_part_locations,
                'Supplier Part Locations To Replenish'  => $part_locations_to_replenish

            ), 'no_history'
        );


    }


}



