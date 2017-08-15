<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 August 2017 at 19:16:12 CEST, Lake Balaton, Hungary

 Copyright (c) 2016, Inikoo

 Version 3.0

*/

trait OrderInsuranceOperations {

   
   
   
    function remove_insurance($onptf_key) {

        $sql = sprintf(
            "DELETE FROM `Order No Product Transaction Fact` WHERE `Order No Product Transaction Fact Key`=%d AND `Order Key`=%d", $onptf_key, $this->id
        );
        mysql_query($sql);

        $this->update_totals();
        // $this->apply_payment_from_customer_account();
    }

    function add_insurance($insurance_key, $dn_key = false) {

        $valid_insurances = $this->get_insurances($dn_key);

        if (array_key_exists($insurance_key, $valid_insurances)) {

            if (!$valid_insurances[$insurance_key]['Order No Product Transaction Fact Key']) {


                $sql = sprintf(
                    "INSERT INTO `Order No Product Transaction Fact` (`Order Key`,`Order Date`,`Transaction Type`,`Transaction Type Key`,`Transaction Description`
					,`Transaction Gross Amount`,`Transaction Net Amount`,`Tax Category Code`,`Transaction Tax Amount`,`Currency Code`,`Currency Exchange`,`Metadata`,`Delivery Note Key`)
				VALUES (%d,%s,%s,%d,%s,%.2f,%.2f,%s,%.2f,%s,%.2f,%s,%s)  ", $this->id, prepare_mysql(gmdate("Y-m-d H:i:s")), prepare_mysql('Insurance'), $insurance_key, prepare_mysql(
                    $valid_insurances[$insurance_key]['Insurance Description']
                ), $valid_insurances[$insurance_key]['Insurance Net Amount'], $valid_insurances[$insurance_key]['Insurance Net Amount'], prepare_mysql(
                        $valid_insurances[$insurance_key]['Insurance Tax Code']
                    ), $valid_insurances[$insurance_key]['Insurance Tax Amount'],

                    prepare_mysql($this->data['Order Currency']), $this->data['Order Currency Exchange'], prepare_mysql($this->data['Order Original Metadata']), prepare_mysql($dn_key)

                );
                mysql_query($sql);

                $onptf_key = mysql_insert_id();

                $this->update_totals();

                //   $this->apply_payment_from_customer_account();
            } else {
                $onptf_key = $valid_insurances[$insurance_key]['Order No Product Transaction Fact Key'];
            }

        } else {
            $onptf_key = 0;
        }

        return $onptf_key;
    }

   
   

}





?>
