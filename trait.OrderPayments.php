<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 August 2017 at 21:43:14 CEST, Tranava, Slovakia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/

trait OrderPayments {


    function add_payment($payment) {

        $payment->update(array('Payment Order Key' => $this->id), 'no_history');

        $sql = sprintf('INSERT INTO `Order Payment Bridge` (`Order Key`,`Payment Key`) VALUES (%d,%d) ', $this->id, $payment->id);


        $this->db->exec($sql);
        $this->update_totals();

        include_once 'utils/new_fork.php';


        $account = get_object('Account', 1);


        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'        => 'payment_added_order',
            'order_key'   => $this->id,
            'store_key'   => $this->data['Order Store Key'],
            'payment_key' => $payment->id,
        ), $account->get('Account Code')
        );



    }

    function get_payments($scope = 'keys', $filter = ''): array {

        //'Pending','Completed','Cancelled','Error','Declined'

        if ($filter == 'Completed') {
            $where = ' and `Payment Transaction Status`="Completed" ';
        } elseif ($filter == 'Completed_or_Pending') {
            $where = ' and (`Payment Transaction Status`="Completed" or `Payment Transaction Status`="Pending" ) ';
        } else {
            $where = '';
        }


        $payments = array();
        $sql      = sprintf(
            "SELECT B.`Payment Key` FROM `Order Payment Bridge` B LEFT JOIN `Payment Dimension` P  ON (P.`Payment Key`=B.`Payment Key`)  WHERE `Order Key`=%d %s ", $this->id, $where
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Payment Key'] == '') {
                    continue;
                }

                if ($scope == 'objects') {
                    $_object = get_object('Payment', $row['Payment Key']);
                    $_object->load_payment_account();
                    $payments[$row['Payment Key']] = $_object;

                } else {
                    $payments[$row['Payment Key']] = $row['Payment Key'];
                }
            }
        }


        return $payments;

    }

    function update_payment_state() {

        if ($this->data['Order Total Amount'] == 0) {
            $payment_state = 'NA';
        } else {
            if ($this->data['Order To Pay Amount'] > 0) {
                $payment_state = 'ToPay';
            } elseif ($this->data['Order To Pay Amount'] == 0) {
                $payment_state = 'Paid';
            } else {
                $payment_state = 'OverPaid';
            }


        }

        $this->fast_update(
            array('Order Payment State' => $payment_state)
        );


    }

}


?>
