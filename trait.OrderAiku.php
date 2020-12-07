<?php

/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: 2020-10-23T02:33:49+08:00 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait OrderAiku {

    function get_aiku_params($field, $value = '') {


        switch ($field) {

            case 'refresh_basket':

                $params = [];

                $params['legacy'] = json_encode(
                    [
                        'order_key' => $this->id
                    ]
                );

                if ($this->data['Order Customer Client Key']) {
                    $url = AIKU_URL.'customers/customer_client/'.$this->data['Order Customer Client Key'].'/basket/';

                } else {
                    $url = AIKU_URL.'customers/'.$this->data['Order Customer Key'].'/basket/';

                }


                break;
            case 'refresh':

            case 'refresh_order':

                $params = [];

                $params['legacy'] = json_encode(
                    [
                        'order_key' => $this->id
                    ]
                );

                $url = AIKU_URL.'orders/';


                break;

            default:
                return [
                    false,
                    false
                ];
        }


        return [
            $url,
            $params
        ];
    }
}