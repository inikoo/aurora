<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6:29 pm Thursday, 25 June 2020 (MYT MYT Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/

class Operative extends Staff {


    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {
        parent::__construct($arg1, $arg2, $arg3);
        $this->table_name = 'Staff Operative';


    }

    function get_data($key, $id) {
        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Staff Dimension` left join `Staff Operative Data`  on (`Staff Key`=`Staff Operative Key`) WHERE `Staff Key`=%d", $id
            );
        } elseif ($key == 'deleted') {
            $this->get_deleted_data($id);

            return;
        } else {
            return;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id         = $this->data['Staff Key'];
            $this->properties = json_decode($this->data['Staff Properties'], true);

        }

    }

    public function update_operative_status() {

        if ($this->data['Staff Currently Working'] != 'Yes') {
            $this->fast_update(['Staff Operative Status' => 'NoWorking'], 'Staff Operative Data');

        } else {

            $sql  = "SELECT `Staff Key` FROM `Staff Role Bridge` where `Role Code`='PRODO' and `Staff Key`=? ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(
                array(
                    $this->id
                )
            );
            if ($row = $stmt->fetch()) {
                $this->fast_update(['Staff Operative Status' => 'Worker'], 'Staff Operative Data');

            } else {
                $this->fast_update(['Staff Operative Status' => 'Other'], 'Staff Operative Data');

            }


        }

    }

    public function update_operative_purchase_order_stats() {

        $purchase_order_stats = array(


            'Planning'      => 0,
            'Queued'        => 0,
            'Manufacturing' => 0,
            'Manufactured'  => 0,
            'QC_Pass'       => 0,
            'Delivered'     => 0,
            'Placed'        => 0,
            'Cancelled'     => 0

        );

        $sql  = "select count(*) as number , ANY_VALUE(`Purchase Order State`) as element from `Purchase Order Dimension` where `Purchase Order Type`='Production'  and  `Purchase Order Operator Key`=? group by `Purchase Order State` ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );


        while ($row = $stmt->fetch()) {


            if ($row['element'] == 'InProcess') {
                $element = 'Planning';
            } elseif ($row['element'] == 'Submitted') {
                $element = 'Queued';
            } elseif ($row['element'] == 'Confirmed') {
                $element = 'Manufacturing';
            } elseif ($row['element'] == 'Manufactured') {
                $element = 'Manufactured';
            } elseif ($row['element'] == 'QC_Pass') {
                $element = 'QC_Pass';
            } elseif ($row['element'] == 'Received' or $row['element'] == 'Checked' or $row['element'] == 'Inputted' or $row['element'] == 'Dispatched') {
                $element = 'Delivered';
            } elseif ($row['element'] == 'Placed' or $row['element'] == 'Costing' or $row['element'] == 'InvoiceChecked') {
                $element = 'Placed';
            } elseif ($row['element'] == 'Cancelled' or $row['element'] == 'NoReceived') {
                $element = 'Cancelled';
            }


            if (isset($purchase_order_stats[$element])) {
                $purchase_order_stats[$element] += $row['number'];
            }

        }




        $this->fast_update(
            [
                'Staff Operative Purchase Orders Planning' => $purchase_order_stats['Planning'],
                'Staff Operative Purchase Orders Queued' => $purchase_order_stats['Queued'],
                'Staff Operative Purchase Orders Manufacturing' => $purchase_order_stats['Manufacturing'],
                'Staff Operative Purchase Orders Waiting QC' => $purchase_order_stats['Manufactured'],
                'Staff Operative Purchase Orders QC Pass' => $purchase_order_stats['QC_Pass'],
                'Staff Operative Purchase Orders Waiting Placing' => $purchase_order_stats['Delivered'],
                'Staff Operative Purchase Orders' => $purchase_order_stats['Placed'],


            ], 'Staff Operative Data'
        );


    }

    public function update_operative_transaction_stats() {

        $transactions_stats = array(


            'Planning'      => 0,
            'Queued'        => 0,
            'Manufacturing' => 0,
            'Manufactured'  => 0,
            'QC_Pass'       => 0,
            'Delivered'     => 0,
            'Placed'        => 0,
            'Cancelled'     => 0

        );
        $products_stats = array(


            'Planning'      => 0,
            'Queued'        => 0,
            'Manufacturing' => 0,
            'Manufactured'  => 0,
            'QC_Pass'       => 0,
            'Delivered'     => 0,
            'Placed'        => 0,
            'Cancelled'     => 0

        );

        $sql  = "select count(*) as number , count(DISTINCT `Supplier Part Key`) as products , ANY_VALUE(`Purchase Order Transaction State`) as element from `Purchase Order Transaction Fact` where `Purchase Order Transaction Type`='Production'  and  `Purchase Order Transaction Operator Key`=? group by `Purchase Order Transaction State` ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );

        while ($row = $stmt->fetch()) {

            if ($row['element'] == 'InProcess') {
                $element = 'Planning';
            } elseif ($row['element'] == 'Submitted') {
                $element = 'Queued';
            } elseif ($row['element'] == 'Confirmed' or $row['element'] == 'ProblemSupplier' ) {
                $element = 'Manufacturing';
            } elseif ($row['element'] == 'Manufactured'  or $row['element'] == 'ReceivedAgent'   ) {
                $element = 'Manufactured';
            } elseif ($row['element'] == 'QC_Pass') {
                $element = 'QC_Pass';
            } elseif ($row['element'] == 'Received' or $row['element'] == 'Checked' or $row['element'] == 'Inputted' or $row['element'] == 'Dispatched' or  $row['element'] == 'InDelivery' ) {
                $element = 'Delivered';
            } elseif ($row['element'] == 'Placed' or $row['element'] == 'InvoiceChecked') {
                $element = 'Placed';
            } elseif ($row['element'] == 'Cancelled' or $row['element'] == 'NoReceived') {
                $element = 'Cancelled';
            }


            if (isset($transactions_stats[$element])) {
                $transactions_stats[$element] += $row['number'];
                $products_stats[$element] += $row['number'];

            }

        }



        $this->fast_update(
            [
                'Staff Operative Transactions Planning' => $transactions_stats['Planning'],
                'Staff Operative Transactions Queued' => $transactions_stats['Queued'],
                'Staff Operative Transactions Manufacturing' => $transactions_stats['Manufacturing'],
                'Staff Operative Transactions Waiting QC' => $transactions_stats['Manufactured'],
                'Staff Operative Transactions QC Pass' => $transactions_stats['QC_Pass'],
                'Staff Operative Transactions Waiting Placing' => $transactions_stats['Delivered'],
                'Staff Operative Transactions' => $transactions_stats['Placed']+$transactions_stats['Delivered'],

                'Staff Operative Products Planning' => $products_stats['Planning'],
                'Staff Operative Products Queued' => $products_stats['Queued'],
                'Staff Operative Products Manufacturing' => $products_stats['Manufacturing'],
                'Staff Operative Products Waiting QC' => $products_stats['Manufactured'],
                'Staff Operative Products QC Pass' => $products_stats['QC_Pass'],
                'Staff Operative Products Waiting Placing' => $products_stats['Delivered'],
                'Staff Operative Products Orders' => $products_stats['Placed'],

            ], 'Staff Operative Data'
        );


    }

}
