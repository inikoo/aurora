<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 January 2019 at 14:06:33 MYT+0800, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3.1

*/


include_once 'class.SupplierPart.php';

class ProductionPart extends SupplierPart {


    function get_data($key, $tag) {

        if ($key == 'id') {
            $sql = sprintf("SELECT * FROM `Supplier Part Dimension` WHERE `Supplier Part Key`=%d", $tag);
        } else {
            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Supplier Part Key'];

            $this->part = get_object('Part', $this->data['Supplier Part Part SKU'], false, $this->db);
        }


        $sql = sprintf("SELECT * FROM `Production Part Dimension`  WHERE `Production Part Supplier Part Key`=%d", $this->id);


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

    function update_bill_of_materials($data) {
        $part = get_object('Part', $data['item_key']);


        if ($part->sku == $this->get('Supplier Part Part SKU')) {
            $this->error = true;
            $this->msg   = _("You can't add same part as a material");

            return false;
        }

        if ($data['field'] == 'Units') {
            $qty = $data['qty'] / $part->get('Part Units Per Package');

        } else {
            $qty = $data['qty'];

        }

        $sql = 'insert into  `Bill of Materials Bridge` (`Bill of Materials Supplier Part Key`,`Bill of Materials Supplier Part Component Key`,`Bill of Materials Quantity`) values (?,?,?) ON DUPLICATE KEY UPDATE `Bill of Materials Quantity`=?';


        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            print_r($this->db->errorInfo());
        }


        if (!$stmt->execute(
            [
                $this->id,
                $part->id,
                $qty,
                $qty
            ]
        )) {
            print_r($stmt->errorInfo());
        }

        $this->update_number_components();
        $part->update_production_supply_data();
        $this->update_available_to_make_up();


        return true;
    }

    function update_number_components() {

        $number_components = 0;

        $sql = "select count(*) as num from `Bill of Materials Bridge` where  `Bill of Materials Supplier Part Key`=? ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id,
            )
        );
        if ($row = $stmt->fetch()) {
            $number_components = $row['num'];
        }


        $this->fast_update(array('Production Part Components Number' => $number_components), 'Production Part Dimension');


    }

    function update_available_to_make_up() {


        $available_to_make_up = '';
        $counter              = 0;

        $sql  = 'select `Part Current On Hand Stock`,`Bill of Materials Quantity`   from   `Bill of Materials Bridge`   left join `Part Dimension` P on (P.`Part SKU`=`Bill of Materials Supplier Part Component Key`) where  `Bill of Materials Supplier Part Key`=?';
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute(
            array(
                $this->id


            )
        )) {
            while ($row = $stmt->fetch()) {


                if (is_numeric($row['Part Current On Hand Stock']) and $row['Part Current On Hand Stock'] >= 0 and $row['Bill of Materials Quantity'] > 0) {
                    $stock_ok = true;
                } else {
                    $stock_ok = false;
                }


                if ($stock_ok) {
                    if ($counter == 0) {
                        $available_to_make_up = $row['Part Current On Hand Stock'] / $row['Bill of Materials Quantity'];
                    } else {
                        if ($row['can_make'] < $available_to_make_up) {
                            $available_to_make_up = $row['can_make'];

                        }

                    }


                    $counter++;
                } else {
                    $available_to_make_up = '';
                    break;
                }


            }
        } else {
            print $sql;
            print_r($error_info = $this->db->errorInfo());
            exit();
        }


        $this->fast_update(array('Production Part Available to Make up' => $available_to_make_up), 'Production Part Dimension');


        return $available_to_make_up;

    }


    //todo
    function update_production_supply_data() {


        $number_of_parts_using_part = 0;
        
        $sql  = 'select count(*) as num from `Bill of Materials Bridge` where  `Bill of Materials Supplier Part Component Key`=? ';
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute(
            array(
                $this->id,
            )
        )) {
            if ($row = $stmt->fetch()) {
                $number_of_parts_using_part = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit();
        }

        $this->fast_update(
            array(
                'Part Number Production Links' => $number_of_parts_using_part,
            )
        );


    }

}

