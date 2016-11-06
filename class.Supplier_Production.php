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


    function Supplier_Production($a1, $a2 = false, $a3 = false) {

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
        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Supplier Key'];

            $sql = sprintf(
                'SELECT * FROM `Supplier Production Dimension` WHERE `Supplier Production Supplier Key`=%d ', $this->id
            );
            if ($row = $this->db->query($sql)->fetch()) {

                foreach ($row as $key => $value) {
                    $this->data[$key] = $value;
                }
            }


        }

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

        $paid_ordered_parts      = 0;
        $todo_paid_ordered_parts = 0;
        /*
        $sql=sprintf('select count(distinct P.`Part SKU`) as num  from `Order Transaction Fact` OTF left join `Product Part Bridge` PPB on (OTF.`Product ID`=PPB.`Product Part Product ID`)    left join `Part Dimension` P on (PPB.`Product Part Part SKU`=P.`Part SKU`) left join `Supplier Part Dimension` SP on (SP.`Supplier Part Part SKU`=P.`Part SKU`) where OTF.`Current Dispatching State` in ("Submitted by Customer","In Process") and  `Current Payment State`="Paid" and  `Supplier Part Supplier Key`=%d ',

            $this->id
        );


        if ($result=$this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $paid_ordered_parts=$row['num'];
            }
        }else {
            print_r($error_info=$this->db->errorInfo());
            print "$sql\n";
            exit;
        }

*/


        $sql = sprintf(
            'SELECT count(DISTINCT P.`Part SKU`) AS num FROM `Part Dimension` P LEFT JOIN `Supplier Part Dimension` SP ON (SP.`Supplier Part Part SKU`=P.`Part SKU`) LEFT JOIN `Part Data` PD ON (PD.`Part SKU`=P.`Part SKU`) WHERE (`Part Current On Hand Stock`- `Part Current Stock In Process`- `Part Current Stock Ordered Paid` )<0 AND (`Part Current Stock In Process`+ `Part Current Stock Ordered Paid`)>0 AND `Supplier Part Supplier Key`=%d',
            $this->id
        );
        //print $sql;
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $todo_paid_ordered_parts = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $this->update(
            array(
                // 'Supplier Production Paid Ordered Parts'=>$paid_ordered_parts,
                'Supplier Production Paid Ordered Parts Todo' => $todo_paid_ordered_parts

            ), 'no_history'
        );


    }


}


?>
