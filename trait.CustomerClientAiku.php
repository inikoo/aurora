<?php

/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 16 Oct 2020 14:36:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait CustomerClientAiku {

    function get_aiku_params($field, $value = '') {

        $params = [
            'legacy_id' => $this->id,
            'legacy'    => json_encode(['customer_key' => $this->get('Customer Client Customer Key')])
        ];

        $url = AIKU_URL.'customer_client/';


        switch ($field) {

            case 'Object':

                $params['name'] = $this->data['Customer Client Name'];
                $params['code'] = $this->data['Customer Client Code'];
                $deleted_at     = null;
                if ($this->data['Customer Client Status'] == 'Inactive') {
                    $deleted_at = $this->metadata('deactivated_date');
                }
                $params['deleted_at'] = $deleted_at;
                $params['data']       = json_encode(
                    array_filter(
                        [
                            'contact' => $this->data['Customer Client Main Contact Name'],
                            'company' => $this->data['Customer Client Company Name'],
                            'mobile'  => $this->data['Customer Main Plain Mobile'],
                            'phone'   => $this->data['Customer Main Plain Telephone'],
                            'email'   => $this->data['Customer Main Plain Email'],


                        ]
                    )
                );

                break;

            case 'deactivate':


                $params['code'] = $this->data['Customer Client Code'];
                $deleted_at     = null;
                if ($this->data['Customer Client Status'] == 'Inactive') {
                    $deleted_at = $this->metadata('deactivated_date');
                }


                $params['deleted_at'] = $deleted_at;


                break;
            case 'Customer Client Name':
                $params['name'] = $value;
                break;
            case 'Customer Client Code':
                $params['code'] = $value;
                break;
            case 'Customer Client Main Contact Name':
                $params['data'] = json_encode(['contact' => $value]);
                break;
            case 'Customer Client Company Name':
                $params['data'] = json_encode(['company' => $value]);
                break;
            case 'Customer Client Main Plain Mobile':
                $params['data'] = json_encode(['mobile' => $value]);
                break;
            case 'Customer Client Main Plain Telephone':
                $params['data'] = json_encode(['phone' => $value]);
                break;
            case 'Customer Client Main Plain Email':
                $params['data'] = json_encode(['email' => $value]);
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