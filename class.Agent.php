<?php
/*
  About:
  Author: Raul Perusquia <rulovico@gmail.com>
  Created: 27 April 2016 at 18:00:39 GMT+8, Lovina, Bali , Indonesioa

  Copyright (c) 2009, Inikoo

  Version 2.0
*/
include_once 'class.SubjectSupplier.php';


class Agent extends SubjectSupplier {


    var $new = false;
    public $locale = 'en_GB';

    function __construct($arg1 = false, $arg2 = false, $arg3 = false, $_db = false) {


        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }

        $this->table_name    = 'Agent';
        $this->ignore_fields = array('Agent Key');


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
                "SELECT * FROM `Agent Dimension` WHERE `Agent Key`=%d", $id
            );
        } elseif ($tipo == 'code') {

            $sql = sprintf(
                "SELECT * FROM `Agent Dimension` WHERE `Agent Code`=%s ", prepare_mysql($id)
            );


        } else {
            return;
        }
        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Agent Key'];

            if ($this->data['Agent Metadata'] == '') {
                $this->metadata = array();
            } else {
                $this->metadata = json_decode(
                    $this->data['Agent Metadata'], true
                );
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
            $raw_data['Agent Name'] = $raw_data['name'];
        }
        if (isset($raw_data['code'])) {
            $raw_data['Agent Code'] = $raw_data['code'];
        }
        if (isset($raw_data['Agent Code']) and $raw_data['Agent Code'] == '') {
            $this->get_data('id', 1);

            return;
        }


        $data = $this->base_data();

        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = _trim($value);
            } elseif (preg_match('/^Agent Address/', $key)) {
                $data[$key] = _trim($value);
            }
        }

        $data['Agent Code'] = mb_substr($data['Agent Code'], 0, 16);


        if ($data['Agent Code'] != '') {
            $sql = sprintf(
                "SELECT `Agent Key` FROM `Agent Dimension` WHERE `Agent Code`=%s ", prepare_mysql($data['Agent Code'])
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {

                    $this->found     = true;
                    $this->found_key = $row['Agent Key'];


                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
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


        if ($this->data['Agent Main Plain Mobile'] != '') {
            list(
                $this->data['Agent Main Plain Mobile'], $this->data['Agent Main XHTML Mobile']
                ) = $this->get_formatted_number(
                $this->data['Agent Main Plain Mobile']
            );
        }
        if ($this->data['Agent Main Plain Telephone'] != '') {
            list(
                $this->data['Agent Main Plain Telephone'], $this->data['Agent Main XHTML Telephone']
                ) = $this->get_formatted_number(
                $this->data['Agent Main Plain Telephone']
            );
        }
        if ($this->data['Agent Main Plain FAX'] != '') {
            list(
                $this->data['Agent Main Plain FAX'], $this->data['Agent Main XHTML FAX']
                ) = $this->get_formatted_number(
                $this->data['Agent Main Plain FAX']
            );
        }

        $this->data['Agent Metadata']   = '{}';
        $this->data['Agent Valid From'] = gmdate('Y-m-d H:i:s');


        $keys   = '';
        $values = '';
        foreach ($this->data as $key => $value) {
            $keys .= ",`".$key."`";

            if (in_array(
                $key, array(
                        'Agent Average Delivery Days',
                        'Agent Default Incoterm',
                        'Agent Default Port of Export',
                        'Agent Default Port of Import',
                        'Agent Valid To'
                    )
            )) {
                $values .= ','.prepare_mysql($value, true);

            } else {
                $values .= ','.prepare_mysql($value, false);

            }

        }
        $values = preg_replace('/^,/', '', $values);
        $keys   = preg_replace('/^,/', '', $keys);

        $sql = "insert into `Agent Dimension` ($keys) values ($values)";

        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();


            $this->get_data('id', $this->id);

            $sql = "INSERT INTO `Agent Data` (`Agent Key`) VALUES (?)";

            $this->db->prepare($sql)->execute(
                array(
                    $this->id
                )
            );


            if ($this->data['Agent Company Name'] != '') {
                $agent_name = $this->data['Agent Company Name'];
            } else {
                $agent_name = $this->data['Agent Main Contact Name'];
            }
            $this->update_field('Agent Name', $agent_name, 'no_history');

            $this->update_address('Contact', $address_raw_data);

            $history_data = array(
                'History Abstract' => sprintf(
                    _('Agent %s created'), $this->get('Name')
                ),
                'History Details'  => '',
                'Action'           => 'created'
            );
            $this->add_history($history_data);
            $this->new = true;
            $this->fork_index_elastic_search();


        } else {
            // print "Error can not create agent $sql\n";
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


            case 'Number Images':

                return number($this->data['Agent '.$key]);
                break;

            default;

                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Agent '.$key, $this->data)) {
                    return $this->data['Agent '.$key];
                }

        }

        return '';

    }

    function load_acc_data() {
        $sql = "SELECT * FROM `Agent Data` WHERE `Agent Key`=?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        if ($row = $stmt->fetch()) {
            foreach ($row as $key => $value) {
                $this->data[$key] = $value;
            }
        }


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
            case 'payment terms':
                $this->fast_update_json_field('Agent Metadata', preg_replace('/\s/', '_', $field), $value);

                break;
            case 'Agent Contact Address':

                $old_location = $this->get('Location');
                $this->update_address('Contact', json_decode($value, true), $options);

                if ($old_location != $this->get('Location')) {
                    $this->fork_index_elastic_search();
                }

                break;

            case('Agent Valid From'):
            case('Agent Valid To'):


                break;

            case('Agent Sticky Note'):
                $this->update_field_switcher('Sticky Note', $value);
                break;
            case('Sticky Note'):
                $this->update_field('Agent '.$field, $value, 'no_null');
                $this->new_value = html_entity_decode($this->new_value);
                break;
            case('Note'):
                $this->add_note($value);
                break;
            case('Agent Average Delivery Days'):
                $this->update_field($field, $value, $options);
                $this->update_metadata = array(
                    'class_html' => array(
                        'Delivery_Time' => $this->get('Delivery Time'),
                    )

                );
                break;
            case 'Agent Default Currency Code':

                $this->update_field($field, $value, $options);

                include_once 'class.Supplier.php';
                $sql = sprintf(
                    'SELECT `Agent Supplier Supplier Key` FROM `Agent Supplier Bridge` WHERE `Agent Supplier Agent Key`=%d ', $this->id
                );

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $supplier = new Supplier(
                            $row['Agent Supplier Supplier Key']
                        );
                        $supplier->update_field_switcher(
                            'Supplier Default Currency Code', $this->get('Agent Default Currency Code'), $options
                        );
                    }
                }
                break;
            case 'Agent Code':
            case 'Agent Name':
            case 'Agent Nickname':
            case 'Agent Website':
                $this->update_field($field, $value, $options);
                $this->fork_index_elastic_search();
                break;


            default:

                if (array_key_exists($field, $this->base_data('Agent Data'))) {

                    $this->update_table_field(
                        $field, $value, $options, 'Agent', 'Agent Data', $this->id
                    );
                } else {

                    $this->update_field($field, $value, $options);
                }
        }


    }


    function post_add_history($history_key, $type = false) {

        if (!$type) {
            $type = 'Changes';
        }

        $sql = sprintf(
            "INSERT INTO  `Agent History Bridge` (`Agent Key`,`History Key`,`Type`) VALUES (%d,%d,%s)", $this->id, $history_key, prepare_mysql($type)
        );
        $this->db->exec($sql);

    }


    function get_field_label($field) {


        switch ($field) {

            case 'Agent Code':
                $label = _('code');
                break;
            case 'Agent Name':
                $label = _('name');
                break;
            case 'Agent Location':
                $label = _('location');
                break;
            case 'Agent Company Name':
                $label = _('company name');
                break;
            case 'Agent Main Contact Name':
                $label = _('contact name');
                break;
            case 'Agent Main Plain Email':
                $label = _('email');
                break;
            case 'Agent Main Email':
                $label = _('main email');
                break;
            case 'Agent Other Email':
                $label = _('other email');
                break;
            case 'Agent Main Plain Telephone':
            case 'Agent Main XHTML Telephone':
                $label = _('telephone');
                break;
            case 'Agent Main Plain Mobile':
            case 'Agent Main XHTML Mobile':
                $label = _('mobile');
                break;
            case 'Agent Main Plain FAX':
            case 'Agent Main XHTML Fax':
                $label = _('fax');
                break;
            case 'Agent Other Telephone':
                $label = _('other telephone');
                break;
            case 'Agent Preferred Contact Number':
                $label = _('main contact number');
                break;
            case 'Agent Fiscal Name':
                $label = _('fiscal name');
                break;

            case 'Agent Contact Address':
                $label = _('contact address');
                break;
            case 'Agent Average Delivery Days':
                $label = _('delivery time (days)');
                break;
            case 'Agent Default Currency Code':
                $label = _('currency');
                break;
            case 'Part Origin Country Code':
                $label = _('country of origin');
                break;
            case 'Agent Default Incoterm':
                $label = _('Incoterm');
                break;
            case 'Agent Default Port of Export':
                $label = _('Port of export');
                break;
            case 'Agent Default Port of Import':
                $label = _('port of import');
                break;
            case 'Agent Default PO Terms and Conditions':
                $label = _('T&C');
                break;
            case 'Agent Show Warehouse TC in PO':
                $label = _('Include general T&C');
                break;
            case 'Agent User Active':
                $label = _('active');
                break;
            case 'Agent User Handle':
                $label = _('login');
                break;
            case 'Agent User Password':
                $label = _('password');
                break;
            case 'Agent User PIN':
                $label = _('PIN');
                break;
            case 'Agent Skip Inputting':
                $label = _("Skip inputting");
                break;
            case 'Agent Skip Mark as Dispatched':
                $label = _("Skip mark as dispatched");
                break;
            case 'Agent Skip Mark as Received':
                $label = _("Skip mark as received");
                break;
            case 'Agent Skip Checking':
                $label = _("Skip checking");
                break;
            case 'Agent Automatic Placement Location':
                $label = _("Try automatic placement location");
                break;
            case 'Agent Order Public ID Format':
                $label = _("order number format");
                break;

            case 'Agent Order Last Order ID':
                $label = _("last incremental order number");
                break;


            default:
                $label = $field;

        }

        return $label;

    }

    function create_supplier($data) {

        $data['editor'] = $this->editor;

        $account         = new Account();
        $account->editor = $this->editor;


        $supplier = $account->create_supplier($data);


        if ($supplier->id) {
            $this->associate_subject($supplier->id);
        }

        return $supplier;


    }

    function associate_subject($supplier_key) {

        if (!$supplier_key) {
            return;
        }

        include_once 'class.Supplier.php';

        $supplier = new Supplier($supplier_key);

        if ($supplier->id) {
            $sql = sprintf(
                "INSERT INTO `Agent Supplier Bridge` (`Agent Supplier Agent Key`,`Agent Supplier Supplier Key`) VALUES (%d,%d)", $this->id, $supplier_key

            );

            $this->db->exec($sql);

            $this->update_supplier_parts();


            $supplier->update(
                array(
                    'Supplier Default Currency Code' => $this->get(
                        'Agent Default Currency Code'
                    )
                )
            );

            $supplier->update_type('Agent');
        }

        $this->update_metadata['updated_showcase_fields'] = array(
            'Agent_Number_Suppliers' => $this->get('Number Suppliers'),
            'Agent_Number_Parts'     => $this->get('Number Parts'),

        );


    }

    function update_supplier_parts() {

        $supplier_number_suppliers          = 0;
        $supplier_number_parts              = 0;
        $supplier_number_surplus_parts      = 0;
        $supplier_number_optimal_parts      = 0;
        $supplier_number_low_parts          = 0;
        $supplier_number_critical_parts     = 0;
        $supplier_number_out_of_stock_parts = 0;


        $sql = "SELECT count(*) AS num FROM  `Agent Supplier Bridge`  WHERE `Agent Supplier Agent Key`=?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        if ($row = $stmt->fetch()) {
            $supplier_number_suppliers = $row['num'];
        }


        $sql = "SELECT
		count(*) AS num ,
		sum(if(`Part Stock Status`='Surplus',1,0)) AS surplus,
		sum(if(`Part Stock Status`='Optimal',1,0)) AS optimal,
		sum(if(`Part Stock Status`='Low',1,0)) AS low,
		sum(if(`Part Stock Status`='Critical',1,0)) AS critical,
		sum(if(`Part Stock Status`='Out_Of_Stock',1,0)) AS out_of_stock
		FROM `Supplier Part Dimension` SP  LEFT JOIN `Part Dimension` P ON (P.`Part SKU`=SP.`Supplier Part Part SKU`) 
            LEFT JOIN `Agent Supplier Bridge` B ON (`Agent Supplier Supplier Key`=`Supplier Part Supplier Key`) 
            WHERE `Agent Supplier Agent Key`=?  AND `Part Status`='In Use' AND `Supplier Part Status`!='Discontinued'";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        if ($row = $stmt->fetch()) {
            $supplier_number_parts = $row['num'];
            if ($row['num'] > 0) {

                $supplier_number_surplus_parts      = $row['surplus'];
                $supplier_number_optimal_parts      = $row['optimal'];
                $supplier_number_low_parts          = $row['low'];
                $supplier_number_critical_parts     = $row['critical'];
                $supplier_number_out_of_stock_parts = $row['out_of_stock'];
            }
        }


        $this->fast_update(
            array(
                'Agent Number Suppliers'          => $supplier_number_suppliers,
                'Agent Number Parts'              => $supplier_number_parts,
                'Agent Number Surplus Parts'      => $supplier_number_surplus_parts,
                'Agent Number Optimal Parts'      => $supplier_number_optimal_parts,
                'Agent Number Low Parts'          => $supplier_number_low_parts,
                'Agent Number Critical Parts'     => $supplier_number_critical_parts,
                'Agent Number Out Of Stock Parts' => $supplier_number_out_of_stock_parts,

            )
        );

    }

    function disassociate_subject($supplier_key) {

        if (!$supplier_key) {
            return;
        }

        include_once 'class.Supplier.php';

        $supplier = new Supplier($supplier_key);

        if ($supplier->id) {
            $sql = sprintf(
                "DELETE FROM `Agent Supplier Bridge` WHERE `Agent Supplier Agent Key`=%d AND `Agent Supplier Supplier Key`=%d", $this->id, $supplier_key

            );

            $this->db->exec($sql);

            $this->update_supplier_parts();
            $supplier->update_type('Free');
        }

        $this->update_metadata['updated_showcase_fields'] = array(
            'Agent_Number_Suppliers' => $this->get('Number Suppliers'),
            'Agent_Number_Parts'     => $this->get('Number Parts'),

        );

    }

    function get_part_skus() {

        $part_skus = '';
        $sql       = sprintf(
            'SELECT `Supplier Part Part SKU` FROM `Supplier Part Dimension` LEFT JOIN `Agent Supplier Bridge` ON (`Supplier Part Supplier Key`=`Agent Supplier Supplier Key`)  WHERE `Agent Supplier Agent Key`=%d ', $this->id
        );
        $part_skus = '';
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if (is_numeric($row['Supplier Part Part SKU']) and $row['Supplier Part Part SKU'] > 0) {
                    $part_skus .= $row['Supplier Part Part SKU'].',';

                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }
        $part_skus = preg_replace('/\,$/', '', $part_skus);

        return $part_skus;

    }


    function create_supplier_delivery($data) {

        $account=get_object('Account',1);
        $delivery_data = array(
            'Supplier Delivery Public ID'           => $this->get_next_delivery_public_id(),
            'Supplier Delivery Parent'              => 'Agent',
            'Supplier Delivery Parent Key'          => $this->id,
            'Supplier Delivery Parent Name'         => $this->get('Name'),
            'Supplier Delivery Parent Code'         => $this->get('Code'),
            'Supplier Delivery Parent Contact Name' => $this->get('Main Contact Name'),
            'Supplier Delivery Parent Email'        => $this->get('Main Plain Email'),
            'Supplier Delivery Parent Telephone'    => $this->get('Preferred Contact Number Formatted Number'),
            'Supplier Delivery Parent Address'      => $this->get('Contact Address Formatted'),

            'Supplier Delivery Currency Code'  => $this->get('Default Currency Code'),
            'Supplier Delivery Incoterm'       => $this->get('Default Incoterm'),
            'Supplier Delivery Port of Import' => $this->get('Default Port of Import'),
            'Supplier Delivery Port of Export' => $this->get('Default Port of Export'),
            'Supplier Delivery Warehouse Key'       => $account->get('Account Default Warehouse'),


            'editor' => $this->editor
        );

        //  print_r($delivery_data);


        $delivery = new SupplierDelivery('new', $delivery_data);


        if ($delivery->error) {
            $this->error = true;
            $this->msg   = $delivery->msg;

        } elseif ($delivery->new) {


        }


        return $delivery;

    }

    function get_next_delivery_public_id() {

        $code = $this->get('Code');

        $line_number = 1;
        $sql         =  "SELECT `Supplier Delivery Public ID` FROM `Supplier Delivery Dimension` WHERE `Supplier Delivery Parent`=?  and `Supplier Delivery Parent Key`=? ORDER BY REPLACE(`Supplier Delivery Public ID`,?,'') DESC LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                'Agent',
                $this->id,
                $code

            )
        );
        if ($row = $stmt->fetch()) {
            $line_number = (int)preg_replace('/[^\d]/', '', preg_replace('/^'.$code.'/', '', $row['Supplier Delivery Public ID'])) + 1;

        }

        return sprintf('%s%04d', $code, $line_number);

    }


    function create_timeseries($data, $fork_key = 0) {


        include_once 'class.Timeserie.php';

        $data['Timeseries Parent']     = 'Agent';
        $data['Timeseries Parent Key'] = $this->id;


        $data['editor'] = $this->editor;

        $timeseries = new Timeseries('find', $data, 'create');


        if ($timeseries->id) {
            require_once 'utils/date_functions.php';

            if ($this->data['Agent Valid From'] != '') {
                $from = date('Y-m-d', strtotime($this->get('Valid From')));

            } else {
                $from = '';
            }

            if ($this->get('Agent Valid To')) {
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
            'SELECT `Part Family Category Key` FROM `Supplier Part Dimension` 
                                  left join `Part Dimension` on (`Part SKU`=`Supplier Part Part SKU`)  
                                  left join `Agent Supplier Bridge` B on (`Supplier Part Supplier Key`=B.`Agent Supplier Supplier Key`)  
                                  WHERE `Agent Supplier Agent Key`=%d group by `Part Family Category Key` ', $this->id
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


}



