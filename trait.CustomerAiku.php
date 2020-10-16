<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 16 Oct 2020 13:38:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */
trait CustomerAiku {

    function get_aiku_params($field, $value = '') {

        $params = [
            'legacy_id' => $this->id,
            'legacy'=>json_encode(['store_key'=>$this->get('Customer Store Key')])
        ];

        $url = AIKU_URL.'customer/';


        switch ($field) {

            case 'Object':

                $params           += $this->get_aiku_params('tax_number_validation')[1];
                $params           += $this->get_aiku_params('Customer Type by Activity')[1];
                $params['name']   = $this->data['Customer Name'];
                $params['email']  = $this->data['Customer Main Plain Email'];
                $params['mobile'] = $this->data['Customer Main Plain Mobile'];


                $params['data']   = json_encode(array_filter([
                                                                 'contact'             => $this->data['Customer Main Contact Name'],
                                                                 'company'             => $this->data['Customer Company Name'],
                                                                 'registration_number' => $this->data['Customer Registration Number'],
                                                                 'tax_number'          => $this->data['Customer Tax Number'],
                                                                 'website'             => $this->data['Customer Website']


                                                             ]));

                break;
            case 'tax_number_validation':

                if ($this->data['Customer Tax Number'] == '') {
                    $params['tax_number_validation'] = '';
                } else {
                    $params['tax_number_validation'] = json_encode(
                        array_filter([
                                         'valid'              => strtolower($this->data['Customer Tax Number Valid']),
                                         'source'             => strtolower($this->data['Customer Tax Number Validation Source']),
                                         'date'               => $this->data['Customer Tax Number Validation Date'],
                                         'message'            => $this->data['Customer Tax Number Validation Message'],
                                         'registered_name'    => $this->data['Customer Tax Number Registered Name'],
                                         'registered_address' => $this->data['Customer Tax Number Registered Address'],
                                     ])
                    );
                }


                break;
            case 'Customer Type by Activity':


                $status = 'approved';
                $state  = 'active';
                if ($this->data['Customer Type by Activity'] == 'Rejected') {
                    $status = 'rejected';
                } elseif ($this->data['Customer Type by Activity'] == 'ToApprove') {
                    $state  = 'registered';
                    $status = 'pending-approval';
                } elseif ($this->data['Customer Type by Activity'] == 'Losing') {
                    $state = 'losing';
                } elseif ($this->data['Customer Type by Activity'] == 'Lost') {
                    $state = 'lost';
                }


                $params['state']  = $state;
                $params['status'] = $status;


                break;
            case 'Customer Name':
                $params['name'] = $value;
                break;
            case 'Customer Main Plain Email':
                $params['email'] = $value;
                break;
            case 'Customer Main Plain Mobile':
                $params['mobile'] = $value;
                break;
            case 'Customer Main Contact Name':
                $params['data'] = json_encode(['contact' => $value]);
                break;
            case 'Customer Company Name':
                $params['data'] = json_encode(['company' => $value]);
                break;
            case 'Customer Registration Number':
                $params['data'] = json_encode(['registration_number' => $value]);
                break;
            case 'Customer Tax Number':
                $params['data'] = json_encode(['tax_number' => $value]);
                break;
            case 'Customer Website':
                $params['data'] = json_encode(['website' => $value]);
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