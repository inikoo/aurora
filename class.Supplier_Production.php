<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 October 2016 at 22:36:41 GMT+8, Kuala Lumpur , Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

include_once 'class.DB_Table.php';
include_once 'class.Supplier.php';

class Supplier_Production extends Supplier {


    function __construct($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Supplier Production';
        $this->ignore_fields = array('Supplier Production Supplier Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } elseif ($a1 == 'find') {
            $this->find($a2, $a3);

        } else {
            $this->get_data($a1, $a2);
        }
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

        $this->metadata = array();

        $this->production_metadata = array();

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Supplier Key'];

            if (!empty($this->data['Supplier Metadata'])) {

                $this->metadata = json_decode(
                    $this->data['Supplier Metadata'], true
                );
            }

            $sql = sprintf(
                'SELECT * FROM `Supplier Production Dimension` WHERE `Supplier Production Supplier Key`=%d ', $this->id
            );
            if ($row = $this->db->query($sql)->fetch()) {

                foreach ($row as $key => $value) {

                    if($key=='Supplier Production Metadata'){

                        if ($row[$key]!='') {
                            $this->production_metadata = json_decode($row[$key], true);
                        }
                    }else{
                        $this->data[$key] = $value;
                    }





                }
            }


        }

    }

    function production_metadata($key) {
        return (isset($this->production_metadata[$key]) ? $this->production_metadata[$key] : '');
    }

    function metadata($key) {
        return (isset($this->metadata[$key]) ? $this->metadata[$key] : '');
    }


    function update_locations_with_errors() {

        $part_locations             = 0;
        $part_locations_with_errors = 0;

        $sql = sprintf(
            'SELECT count(*) AS num  FROM `Part Location Dimension` PLD  LEFT JOIN `Part Dimension` P ON (PLD.`Part SKU`=P.`Part SKU`) LEFT JOIN `Supplier Part Dimension` SP ON (SP.`Supplier Part Part SKU`=P.`Part SKU`) WHERE `Supplier Part Supplier Key`=%d ',

            $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $part_locations = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf(
            'SELECT count(*) AS num  FROM `Part Location Dimension` PLD  LEFT JOIN `Part Dimension` P ON (PLD.`Part SKU`=P.`Part SKU`) LEFT JOIN `Supplier Part Dimension` SP ON (SP.`Supplier Part Part SKU`=P.`Part SKU`) WHERE `Supplier Part Supplier Key`=%d AND `Quantity On Hand`<0 ',

            $this->id
        );
        //print $sql;
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $part_locations_with_errors = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $this->update(
            array(
                'Supplier Production Part Locations'        => $part_locations,
                'Supplier Production Part Locations Errors' => $part_locations_with_errors

            ), 'no_history'
        );


    }


    function update_paid_ordered_parts() {


        $todo_paid_ordered_parts = 0;


        $sql = sprintf(
            'SELECT count(DISTINCT P.`Part SKU`) AS num FROM `Part Dimension` P LEFT JOIN `Supplier Part Dimension` SP ON (SP.`Supplier Part Part SKU`=P.`Part SKU`) LEFT JOIN `Part Data` PD ON (PD.`Part SKU`=P.`Part SKU`) 
WHERE (`Part Current On Hand Stock`- `Part Current Stock In Process`- `Part Current Stock Ordered Paid` )<0 AND (`Part Current Stock In Process`+ `Part Current Stock Ordered Paid`)>=0 AND `Supplier Part Supplier Key`=%d',
            $this->id
        );
        //print $sql;
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $todo_paid_ordered_parts = $row['num'];
            }
        }

        $this->update(
            array(
                'Supplier Production Paid Ordered Parts Todo' => $todo_paid_ordered_parts

            ), 'no_history'
        );


    }


    function get_kpi($interval) {

        global $account;

        include_once 'utils/date_functions.php';
        list($db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb) = calculate_interval_dates($this->db, $interval);

        // print "$db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb \n";


        $sql = sprintf(
            'SELECT sum(`Timesheet Production Clocked Time`) AS seconds FROM `Timesheet Dimension` WHERE `Timesheet Date`>=%s AND `Timesheet Date`<=%s ', prepare_mysql($from_date), prepare_mysql($to_date)
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
                "SELECT `Timesheet Key` FROM `Timesheet Dimension` T LEFT JOIN `Staff Role Bridge` B ON (B.`Staff Key`=`Timesheet Staff Key`) WHERE `Role Code` IN ('PRODM','PRODO') AND `Timesheet Date`=%s ", prepare_mysql(gmdate('Y-m-d'))
            );
            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $timesheet = new Timesheet($row['Timesheet Key']);
                    $hrs       += $timesheet->get_clocked_open_jaw_time() / 3600;
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


        }


        // print $sql;


        $db_interval = get_interval_db_name($interval);

        $supplier = get_object('supplier', $this->id);
        $supplier->load_acc_data();
        $amount = $supplier->get('Supplier '.$db_interval.' Acc Invoiced Amount');


        /*

                $sql=sprintf('select sum(`Amount In`) as amount from `Inventory Transaction Fact` ITF LEFT JOIN `Supplier Part Dimension` SP ON (SP.`Supplier Part Part SKU`=ITF.`Part SKU`)   where `Date`>=%s and `Date`<=%s  and `Inventory Transaction Type`="Sale" and `Supplier Part Supplier Key`=%d   ',
                             prepare_mysql($from_date),
                             prepare_mysql($to_date),
                             $this->id
                );
                if ($result=$this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $amount=$row['amount'];
                    }else{
                        $amount=0;
                    }
                }else {
                    print_r($error_info=$this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

        */


        if ($hrs == 0) {
            $kpi           = '';
            $kpi_formatted = '';
        } else {
            $kpi           = $amount / $hrs;
            $kpi_formatted = number($amount / $hrs, 2).' '.currency_symbol($account->get('Account Currency')).'/h';
        }


        return array(
            'ppm' => array(

                'ppm_kpi'                    => $kpi,
                'ppm_amount'                 => $amount,
                'ppm_hrs'                    => $hrs,
                'ppm_formatted_kpi'          => $kpi_formatted,
                'ppm_formatted_amount'       => money($amount, $account->get('Account Currency')),
                'ppm_formatted_hrs'          => sprintf('%d hours', number($hrs, 1)),
                'ppm_formatted_aux_kpi_data' => sprintf('%d hours', number($hrs, 1)),
            )
        );


    }

}

