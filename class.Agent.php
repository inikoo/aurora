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

    function Agent($arg1 = false, $arg2 = false, $arg3 = false,$_db = false) {


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
            list($this->data['Agent Main Plain Mobile'], $this->data['Agent Main XHTML Mobile'])
                = $this->get_formatted_number(
                $this->data['Agent Main Plain Mobile']
            );
        }
        if ($this->data['Agent Main Plain Telephone'] != '') {
            list($this->data['Agent Main Plain Telephone'], $this->data['Agent Main XHTML Telephone'])
                = $this->get_formatted_number(
                $this->data['Agent Main Plain Telephone']
            );
        }
        if ($this->data['Agent Main Plain FAX'] != '') {
            list($this->data['Agent Main Plain FAX'], $this->data['Agent Main XHTML FAX'])
                = $this->get_formatted_number(
                $this->data['Agent Main Plain FAX']
            );
        }


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
        $sql = sprintf(
            "SELECT * FROM `Agent Data` WHERE `Agent Key`=%d", $this->id
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

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        if (is_string($value)) {
            $value = _trim($value);
        }


        if ($this->update_subject_field_switcher(
            $field, $value, $options, $metadata
        )
        ) {
            return;
        }


        switch ($field) {
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
            case('Attach'):
                $this->add_attach($value);
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
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }

            default:

                if (array_key_exists($field, $this->base_data('Agent Data'))) {
                    //print "$field $value \n";
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
        global $account;

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


        $sql = sprintf(
            'SELECT count(*) AS num FROM  `Agent Supplier Bridge`  WHERE `Agent Supplier Agent Key`=%d  ', $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $supplier_number_suppliers = $row['num'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $sql = sprintf(
            'SELECT
		count(*) AS num ,
		sum(if(`Part Stock Status`="Surplus",1,0)) AS surplus,
		sum(if(`Part Stock Status`="Optimal",1,0)) AS optimal,
		sum(if(`Part Stock Status`="Low",1,0)) AS low,
		sum(if(`Part Stock Status`="Critical",1,0)) AS critical,
		sum(if(`Part Stock Status`="Out_Of_Stock",1,0)) AS out_of_stock

		FROM `Supplier Part Dimension` SP  LEFT JOIN `Part Dimension` P ON (P.`Part SKU`=SP.`Supplier Part Part SKU`) LEFT JOIN `Agent Supplier Bridge` B ON (`Agent Supplier Supplier Key`=`Supplier Part Supplier Key`)    WHERE `Agent Supplier Agent Key`=%d  AND `Part Status`="In Use" AND `Supplier Part Status`!="Discontinued" ',
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $supplier_number_parts = $row['num'];
                if ($row['num'] > 0) {

                    $supplier_number_surplus_parts      = $row['surplus'];
                    $supplier_number_optimal_parts      = $row['optimal'];
                    $supplier_number_low_parts          = $row['low'];
                    $supplier_number_critical_parts     = $row['critical'];
                    $supplier_number_out_of_stock_parts = $row['out_of_stock'];
                }

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $this->update(
            array(
                'Agent Number Suppliers'          => $supplier_number_suppliers,
                'Agent Number Parts'              => $supplier_number_parts,
                'Agent Number Surplus Parts'      => $supplier_number_surplus_parts,
                'Agent Number Optimal Parts'      => $supplier_number_optimal_parts,
                'Agent Number Low Parts'          => $supplier_number_low_parts,
                'Agent Number Critical Parts'     => $supplier_number_critical_parts,
                'Agent Number Out Of Stock Parts' => $supplier_number_out_of_stock_parts,

            ), 'no_history'
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
            'SELECT `Supplier Part Part SKU` FROM `Supplier Part Dimension` LEFT JOIN `Agent Supplier Bridge` ON (`Supplier Part Supplier Key`=`Agent Supplier Supplier Key`)  WHERE `Agent Supplier Agent Key`=%d ',
            $this->id
        );
        $part_skus = '';
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if( is_numeric($row['Supplier Part Part SKU']) and $row['Supplier Part Part SKU']>0 ){
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


    function create_delivery($data) {




        $delivery_data = array(
            'Supplier Delivery Public ID'           =>  $this->get_next_delivery_public_id(),
            'Supplier Delivery Parent'              => 'Agent',
            'Supplier Delivery Parent Key'          => $this->id,
            'Supplier Delivery Parent Name'         => $this->get('Name'),
            'Supplier Delivery Parent Code'         => $this->get('Code'),
            'Supplier Delivery Parent Contact Name' => $this->get('Main Contact Name'),
            'Supplier Delivery Parent Email'        => $this->get('Main Plain Email'),
            'Supplier Delivery Parent Telephone'    => $this->get('Preferred Contact Number Formatted Number'),
            'Supplier Delivery Parent Address'      => $this->get('Contact Address Formatted'),

            'Supplier Delivery Currency Code'       => $this->get('Default Currency Code'),
            'Supplier Delivery Incoterm'            => $this->get('Default Incoterm'),
            'Supplier Delivery Port of Import'      => $this->get('Default Port of Import'),
            'Supplier Delivery Port of Export'      => $this->get('Default Port of Export'),
          //  'Supplier Delivery Purchase Order Key'  => $this->id,

            //'Supplier Delivery Warehouse Key'=>$warehouse->id,
            //'Supplier Delivery Warehouse Metadata'=>json_encode($warehouse->data),

            'editor' => $this->editor
        );

        //  print_r($delivery_data);


        $delivery = new SupplierDelivery('new', $delivery_data);


        if ($delivery->error) {
            $this->error = true;
            $this->msg   = $delivery->msg;

        } elseif ($delivery->new ) {





        }


        return $delivery;

    }

    function get_next_delivery_public_id() {

        $code = $this->get('Code');

        $line_number = 1;
        $sql         = sprintf(
            "SELECT `Supplier Delivery Public ID` FROM `Supplier Delivery Dimension` WHERE `Supplier Delivery Parent`=%s  and `Supplier Delivery Parent Key`=%d ORDER BY REPLACE(`Supplier Delivery Public ID`,%s,'') DESC LIMIT 1",
            prepare_mysql('Agent'),
            $this->id,
            prepare_mysql($code)
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $line_number = (int)preg_replace('/[^\d]/', '', preg_replace('/^'.$code.'/', '', $row['Supplier Delivery Public ID'])) + 1;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return sprintf('%s%04d', $code, $line_number);

    }


}


?>
