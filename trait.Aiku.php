<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 16 Oct 2020 15:03:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait Aiku {

    function get_table_name() {
        return $table_name = $this->table_name.' Dimension';
    }

    function get_aiku_params($field, $value) {
        return [
            false,
            false
        ];
    }

    function sync_aiku() {
        $this->update_aiku($this->get_table_name(), 'Object');
    }

    function update_aiku($table_full_name, $field, $value = '') {


        if (!defined('AIKU_TOKEN')) {
            return 0;
        }


        switch ($table_full_name) {
            case 'Staff Dimension':
            case 'User Dimension':
            case 'Store Dimension':
            case 'Customer Dimension':
            case 'Customer Client Dimension':
            case 'Part Dimension':
            case 'Product Dimension':
            case 'Part Location Dimension':

                list($url, $params) = $this->get_aiku_params($field, $value);
                if (!$url) {
                    return 0;
                }
                break;

            default:
                return 0;


        }

        $headers = [
            "Authorization: Bearer ".AIKU_TOKEN,
            "Content-Type:multipart/form-data",
            "Accept: application/json",
        ];

        $curl = curl_init();


        curl_setopt_array(
            $curl, array(
                     CURLOPT_URL            => $url,
                     CURLOPT_RETURNTRANSFER => true,
                     CURLOPT_ENCODING       => "",
                     CURLOPT_MAXREDIRS      => 10,
                     CURLOPT_TIMEOUT        => 0,
                     CURLOPT_FOLLOWLOCATION => true,
                     CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                     CURLOPT_CUSTOMREQUEST  => "POST",
                     CURLOPT_POSTFIELDS     => $params,
                     CURLOPT_HTTPHEADER     => $headers
                 )
        );

        $response = curl_exec($curl);
        //echo "Params:".print_r($params).' <<';
        curl_close($curl);
        //echo "Response:".$response.' <<';


        return 1;
    }

}
