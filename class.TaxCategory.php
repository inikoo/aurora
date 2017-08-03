<?php
/*
 About:
 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';


class TaxCategory extends DB_Table {


    function TaxCategory($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Tax Category';
        $this->ignore_fields = array();

        if ($a1 and !$a2) {
            $this->get_data('code', $a1);
        } elseif ($a1 == 'find') {
            $this->find($a2, $a3);

        } else {
            $this->get_data($a1, $a2);
        }
    }


    function get_data($key, $tag) {

        if ($key == 'code') {
            $sql = sprintf(
                "SELECT *   FROM kbase.`Tax Category Dimension` WHERE `Tax Category Code`=%s ", prepare_mysql($tag)
            );
        } elseif ($key == 'id') {
            $sql = sprintf(
                "SELECT *   FROM kbase.`Tax Category Dimension` WHERE `Tax Category Key`=%d ", $tag
            );
        } else {
            return;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id   = $this->data['Tax Category Key'];
            $this->code = $this->data['Tax Category Code'];
        }



    }

    function find($raw_data, $options) {
        if (isset($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {
                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }
            }
        }

        $this->found     = false;
        $this->found_key = false;



        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = _trim($value);
            }
        }


        //  print_r($raw_data);

        if ($data['Tax Category Code'] == '') {
            $this->error = true;
            $this->msg   = 'Tax Category code empty';

            return;
        }

        if ($data['Tax Category Name'] == '') {
            $data['Tax Category Name'] = $data['Tax Category Code'];
        }

        if (!isset($data['Tax Category Type']) or $data['Tax Category Type'] == '') {
            $data['Tax Category Type'] = $data['Tax Category Code'];
        }
        $sql = sprintf(
            "SELECT * FROM kbase.`Tax Category Dimension` WHERE `Tax Category Code`=%s  ", prepare_mysql($data['Tax Category Code'])
        );


        if ($result=$this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->found     = true;
                $this->found_key = $row['Tax Category Code'];
        	}
        }else {
        	print_r($error_info=$this->db->errorInfo());
        	print "$sql\n";
        	exit;
        }




        if ($this->found) {
            $this->get_data('code', $this->found_key);
        }

    }




    function get($key, $data = false) {
        switch ($key) {

            default:
                if (isset($this->data[$key])) {
                    return $this->data[$key];
                } else {
                    return '';
                }
        }

        return '';
    }


    function calculate_tax($amount) {

        return $amount * $this->data['Tax Category Rate'];

    }

    function set_taxes($country) {

        switch ($country) {
            case('GBR'):
                if ($this->data['Order Ship To Country Code'] == 'GBR' or $this->data['Order Ship To Country Code'] == 'UNK') {
                    $tax_rate = 0.175;
                    $tax_code = 'GBR.S';

                } else {
                    $sql = sprintf(
                        "SELECT `European Union` FROM kbase.`Country Dimension` WHERE `Country Code`=%s      ", prepare_mysql($country)
                    );


                    if ($result=$this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            if ($row['European Union'] == "Yes") {
                                $customer = new Customer(
                                    $this->data['Order Customer Key']
                                );

                                if ($customer->is_tax_number_valid()) {
                                    $tax_rate = 0;
                                    $tax_code = 'GBR.EuroFree';
                                } else {
                                    $tax_rate = 0.175;
                                    $tax_code = 'GBR.S';

                                }
                            }
                            else {
                                $tax_rate = 0;
                                $tax_code = 'GBR.OffEuroFree';

                            }
                    	}else{
                            $tax_rate = 0.175;
                            $tax_code = 'GBR.S';
                        }
                    }else {
                    	print_r($error_info=$this->db->errorInfo());
                    	print "$sql\n";
                    	exit;
                    }




                }

        }


        $this->data['Order Tax Rate'] = $tax_rate;
        $this->data['Order Tax Code'] = $tax_code;


    }


}

?>
