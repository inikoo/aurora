<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 December 2018 at 10:26:52 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 2.0

*/


class shipping_for_order {


    public function __construct($db) {

        $this->db = $db;

    }


    function get($_data) {


        include_once 'utils/get_addressing.php';
        include_once 'utils/object_functions.php';

        $shipping_zone_schema_key = $_data['shipping_zone_schema_key'];


        $country_code = $_data['Order Data']['Order Delivery Address Country 2 Alpha Code'];
        $post_code    = $_data['Order Data']['Order Delivery Address Postal Code'];

        if (preg_match('/gb|im|jy|gg/i', $country_code)) {
            include_once 'utils/geography_functions.php';
            $post_code = gbr_pretty_format_post_code($post_code);
        }


        $sql = sprintf(
            'select `Shipping Zone Key`,`Shipping Zone Code`,`Shipping Zone Price`,`Shipping Zone Territories` from `Shipping Zone Dimension` where `Shipping Zone Shipping Zone Schema Key`=%d and `Shipping Zone Type`="Normal"  order by `Shipping Zone Position` desc ',
            $shipping_zone_schema_key
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $zone = get_zone_object(
                    [
                        'id'          => $row['Shipping Zone Key'],
                        'label'       => $row['Shipping Zone Code'],
                        'territories' => json_decode($row['Shipping Zone Territories'], true)
                    ]
                );


                $address = get_address_object();
                $address = $address->withCountryCode($country_code)->withPostalCode($post_code);

                if ($zone->match($address)) {


                    $shipping_price_data = json_decode($row['Shipping Zone Price'], true);


                    $price_data = $this->get_price_from_method($shipping_price_data, $_data);

                    $price_data['shipping_zone_key']        = $row['Shipping Zone Key'];
                    $price_data['shipping_zone_schema_key'] = $shipping_zone_schema_key;

                    return $price_data;

                }


            }
        }


        $sql = sprintf(
            'select `Shipping Zone Key`,`Shipping Zone Code`,`Shipping Zone Price`,`Shipping Zone Territories` from `Shipping Zone Dimension` where `Shipping Zone Shipping Zone Schema Key`=%d and `Shipping Zone Type`="Failover"  ', $shipping_zone_schema_key
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $shipping_price_data = json_decode($row['Shipping Zone Price'], true);


                $price_data = $this->get_price_from_method($shipping_price_data, $_data);

                $price_data['shipping_zone_key']        = $row['Shipping Zone Key'];
                $price_data['shipping_zone_schema_key'] = $shipping_zone_schema_key;

                return $price_data;
            }
        }


    }


    private function get_price_from_method($shipping_price_data, $_data) {
        switch ($shipping_price_data['type']) {
            case 'Step Order Items Net Amount':

                $amount = $_data['Order Data']['Order Items Net Amount'];


                if ($amount <= 0) {
                    $result = array(
                        'price'  => 0,
                        'step'   => 0,
                        'method' => 'Calculated'

                    );

                    return $result;
                }

                $counter = 1;
                foreach ($shipping_price_data['steps'] as $step) {
                    if ($step['to'] == 'INF') {
                        $step['to'] = INF;
                    }

                    if ($amount >= $step['from'] and $amount < $step['to']) {
                        $result = array(
                            'step'   => $counter,
                            'price'  => $step['price'],
                            'method' => 'Calculated'

                        );

                        return $result;

                    }

                    $counter++;
                }


            default:
                $result = array(
                    'price'  => '',
                    'step'   => '',
                    'method' => 'TBC'

                );

                return $result;
        }

    }

}



