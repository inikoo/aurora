<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 23 april 2021, 15:59 Kuala Lumpur Malaysia

 Copyright (c) 2021, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';

class PickingBand extends DB_Table {


    function __construct($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db         = $db;
        $this->error_code = '';

        $this->table_name    = 'Picking Band';
        $this->ignore_fields = array('Picking Band Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } else {
            if (($a1 == 'new' or $a1 == 'create') and is_array($a2)) {
                $this->find($a2, 'create');

            } elseif (preg_match('/find/i', $a1)) {
                $this->find($a2, $a1);
            } else {
                $this->get_data($a1, $a2, $a3);
            }
        }

    }

    function get_data($tipo, $tag, $tag2 = false) {

        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Picking Band Dimension` WHERE `Picking Band Key`=%d", $tag
            );
        }
        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Picking Band Key'];
        }

    }


    function find($raw_data, $options) {

        if (isset($raw_data['editor']) and is_array($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {

                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }

            }
        }

        $this->candidate = array();
        $this->found     = false;
        $this->error     = false;
        $this->found_key = 0;
        $create          = '';

        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        $data = $this->base_data();


        foreach ($raw_data as $key => $value) {

            if (array_key_exists($key, $data)) {
                $data[$key] = $value;
            }

        }


        $sql = "SELECT `Picking Band Key` FROM `Picking Band Dimension` WHERE  `Picking Band Name`=? and `Picking Band Type`=? and `Picking Band Warehouse Key`=? ";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $data['Picking Band Name'],
                $data['Picking Band Type'],
                $data['Picking Band Warehouse Key']
            )
        );
        while ($row = $stmt->fetch()) {
            $this->found            = true;
            $this->found_key        = $row['Picking Band Key'];
            $this->duplicated_field = 'Name';
        }


        if ($this->found) {
            $this->get_data('id', $this->found_key);
        }


        if ($create and !$this->found and !$this->error) {

            $this->create($data);

        }


    }


    function create($data) {

        $data['Picking Band From'] = gmdate('Y-m-d H:i:s');

        $keys   = '';
        $values = '';
        foreach ($data as $key => $value) {
            $keys   .= "`$key`,";
            $values .= prepare_mysql($value).",";

        }


        $keys   = preg_replace('/,$/', '', $keys);
        $values = preg_replace('/,$/', '', $values);


        $sql = sprintf(
            "INSERT INTO `Picking Band Dimension` (%s) VALUES (%s)", $keys, $values
        );


        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();
            $this->get_data('id', $this->id);
            $this->new = true;


            if ($this->data['Picking Band Type'] == 'Packing') {
                $abstract = sprintf(_('Packing band %s created'), $this->data['Picking Band Name']);
            } else {
                $abstract = sprintf(_('Picking band %s created'), $this->data['Picking Band Name']);

            }
            $history_data = array(

                'History Abstract' => $abstract,
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );

        } else {
            $this->error = true;

        }
    }


    function get($key = '') {

        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        switch ($key) {

            case 'Name':
                return $this->data['Picking Band '.$key];
            case 'Amount':
                $account = get_object('Account', 1);

                return money($this->data['Picking Band Amount'], $account->get('Account Currency'));

            case 'Status':
                switch ($this->data['Picking Band Status']) {

                    case 'Active':
                        return _('Active');
                    case 'Inactive':
                        return _('Inactive');

                    default:
                        return $this->data['Picking Band Status'];
                }


            case 'Valid From':

                if ($this->data['Picking Band From'] == '') {
                    return '';
                } else {
                    return gmdate(
                        'd-m-Y', strtotime($this->data['Picking Band From'].' +0:00')
                    );
                }
            case 'Valid To':
                if ($this->data['Picking Band To'] == '') {
                    return '';
                } else {
                    return gmdate(
                        'd-m-Y', strtotime($this->data['Picking Band To'].' +0:00')
                    );
                }


        }

        return false;
    }


    function update_usage() {

        $deliveries = 0;
        $amount_out = 0;
        $qty        = 0;


        $sql =
            "SELECT count(distinct `Delivery Note Key`) as deliveries, sum(`ITF Picking Band Amount`) as amount_out , sum(`Inventory Transaction Quantity`) as qty   from `Inventory Transaction Fact` left join `ITF Picking Band Bridge` on (`ITF Picking Band ITF Key`=`Inventory Transaction Key`) WHERE `ITF Picking Band Picking Band Key`=? ";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        while ($row = $stmt->fetch()) {
            $deliveries = $row['deliveries'];
            $amount_out = $row['amount_out'];
            $qty        = $row['qty'];

        }

        $this->fast_update(
            [
                'Picking Band Number Delivery Notes' => $deliveries,
                'Picking Band Quantity Processed'    => $qty,
                'Picking Band Amount Out'            => $amount_out
            ]
        );


    }

    function get_historic_id(){

        $date=gmdate('Y-m-d H:i:s');
        $sql="insert into `Picking Band Historic Fact` (`Picking Band Historic Band Key`,`Picking Band Historic Type`,`Picking Band Historic Name`,`Picking Band Historic Amount`,`Picking Band Historic Created`,`Picking Band Historic Updated`) values(?,?,?,?,?,?) on duplicate key update `Picking Band Historic Updated`=? ";

        $this->db->prepare($sql)->execute(
            array(
                $this->id,
                $this->data['Picking Band Type'],
                $this->data['Picking Band Name'],
                $this->data['Picking Band Amount'],
                $date,
                $date,
                $date
            )
        );

        return $this->db->lastInsertId();


    }


}


