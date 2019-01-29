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


    function update_bill_of_materials($data) {
        $part = get_object('Part', $data['item_key']);


        if ($part->sku == $this->get('Supplier Part Part SKU')) {
            $this->error = true;
            $this->msg   = _("You can't add same part as a material");

            return false;
        }

        if ($data['field'] == 'Units') {
            $qty = $data['qty'] / $part->get('Part Units Per Package');

        }else{
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


        return true;
    }


    function update_number_components() {

        $number_components = 0;
        $sql               = 'select count(*) as num from `Bill of Materials Bridge` where  `Bill of Materials Supplier Part Key`=? ';
        $stmt              = $this->db->prepare($sql);
        if ($stmt->execute(
            array(
                $this->id,
            )
        )) {
            if ($row = $stmt->fetch()) {
                $number_components = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit();
        }

        $this->part->fast_update(
            array(
                'Part Number Components' => $number_components,
            )
        );


    }


}

