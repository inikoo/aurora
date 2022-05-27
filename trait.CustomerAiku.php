<?php

/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 16 Oct 2020 13:38:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait CustomerAiku {

    function sync_aiku_basket() {
        $this->update_aiku($this->get_table_name(), 'sync_basket');
    }

    function get_aiku_params($field, $value = '') {


        $url = AIKU_URL.'customers/'.$this->id;


        switch ($field) {

            case 'Object':
                $url = AIKU_URL.'customers/';


                $legacy_data              = [];
                $legacy_data['store_key'] = $this->get('Customer Store Key');
                $legacy_data              += json_decode($this->get_aiku_params('billing_address')[1]['legacy'], true);
                $legacy_data              += json_decode($this->get_aiku_params('delivery_address')[1]['legacy'], true);


                $params['legacy'] = json_encode($legacy_data);


                $params += $this->get_aiku_params('Customer Type by Activity')[1];


                $params['legacy_id'] = $this->id;
                $params['name']      = $this->data['Customer Name'];
                $params['email']     = $this->data['Customer Main Plain Email'];
                $params['mobile']    = $this->data['Customer Main Plain Mobile'];


                $data = [
                    'contact'             => $this->data['Customer Main Contact Name'],
                    'company'             => $this->data['Customer Company Name'],
                    'registration_number' => $this->data['Customer Registration Number'],
                    'tax_number'          => $this->data['Customer Tax Number'],
                    'website'             => $this->data['Customer Website']
                ];

                $data += json_decode($this->get_aiku_params('tax_number_validation')[1]['data'], true);

                $params['data'] = json_encode(
                    array_filter(
                        $data
                    )
                );


                $params['settings'] = json_encode(
                    [
                        'can_send' =>

                            [
                                'newsletter'       => strtolower($this->data['Customer Send Newsletter']),
                                'email_marketing'  => strtolower($this->data['Customer Send Email Marketing']),
                                'basket_engagement'  => strtolower($this->data['Customer Send Basket Emails']),
                                'postal_marketing' => strtolower($this->data['Customer Send Postal Marketing']),


                            ]


                    ]

                );

                //print_r($params);

                break;

            case 'sync_basket':
                $url    = AIKU_URL.'customers/'.$this->id.'/basket';
                $params = ['v' => 1];

                break;
            case 'sync_portfolio':
                $params = false;
                $url    = false;

                $sql =
                    "select `Customer Portfolio Key`,`Customer Portfolio Reference`,`Customer Portfolio Creation Date`,`Customer Portfolio Customers State`,`Customer Portfolio Removed Date` from `Customer Portfolio Fact` where `Customer Portfolio Customer Key`=? and `Customer Portfolio Product ID`=? ";

                $stmt = $this->db->prepare($sql);
                $stmt->execute(
                    array(
                        $this->id,
                        $value
                    )
                );


                if ($row = $stmt->fetch()) {
                    $url              = AIKU_URL.'customers/'.$this->id.'/portfolio/'.$value;
                    $params['legacy'] = json_encode($row);

                }

                break;
            case 'billing_address':
            case 'delivery_address':

                $legacy_object = 'Customer';

                $type = ' Delivery';
                if ($field == 'billing_address') {
                    $type = ' Invoice';
                }


                $address['address_line_1']      = $this->data[$legacy_object.$type.' Address Line 1'];
                $address['address_line_2']      = $this->data[$legacy_object.$type.' Address Line 2'];
                $address['sorting_code']        = $this->data[$legacy_object.$type.' Address Sorting Code'];
                $address['postal_code']         = $this->data[$legacy_object.$type.' Address Postal Code'];
                $address['locality']            = $this->data[$legacy_object.$type.' Address Locality'];
                $address['dependent_locality']  = $this->data[$legacy_object.$type.' Address Dependent Locality'];
                $address['administrative_area'] = $this->data[$legacy_object.$type.' Address Administrative Area'];
                $address['country_code']        = $this->data[$legacy_object.$type.' Address Country 2 Alpha Code'];
                $params['legacy']               = json_encode([$field => $address]);

                break;
            case 'tax_number_validation':


                $data = [];

                if ($this->data['Customer Tax Number'] == '') {
                    $data['tax_number_validation'] = '';
                } else {
                    $data['tax_number_validation'] = json_encode(
                        array_filter(
                            [
                                'valid'              => strtolower($this->data['Customer Tax Number Valid']),
                                'source'             => strtolower($this->data['Customer Tax Number Validation Source']),
                                'date'               => $this->data['Customer Tax Number Validation Date'],
                                'message'            => $this->data['Customer Tax Number Validation Message'],
                                'registered_name'    => $this->data['Customer Tax Number Registered Name'],
                                'registered_address' => $this->data['Customer Tax Number Registered Address'],
                            ]
                        )
                    );
                }
                $params['data'] = json_encode($data);

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