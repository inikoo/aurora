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


    }
    
    function get_payments($scope = 'keys',$filter='') {


         if($filter=='Completed'){
             $where=' and `Payment Transaction Status`="Completed" ';
         }else{
             $where='';
         }
     

        $payments = array();
        $sql      = sprintf(
            "SELECT B.`Payment Key` FROM `Order Payment Bridge` B left join `Payment Dimension` P  on (P.`Payment Key`=B.`Payment Key`)  WHERE `Order Key`=%d %s ", $this->id,$where
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Payment Key'] == '') {
                    continue;
                }

                if ($scope == 'objects') {
                    $payments[$row['Payment Key']] =  get_object('Payment',$row['Payment Key']);  

                } else {
                    $payments[$row['Payment Key']] = $row['Payment Key'];
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $payments;

    }

}


?>
