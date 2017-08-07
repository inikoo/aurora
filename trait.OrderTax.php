<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 August 2017 at 08:43:35 CEST, Tranava, Slovakia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/

trait OrderTax {


    function update_tax_number($value) {


        $this->update_field('Order Tax Number', $value);


        if ($this->updated) {

            $this->update_tax_number_validation();


            $this->new_value = $value;

            $this->update_tax();


        }

        $this->other_fields_updated = array(
            'Order_Tax_Number_Valid' => array(
                'field'           => 'Order_Tax_Number_Valid',
                'render'          => ($this->get('Order Tax Number') == '' ? false : true),
                'value'           => $this->get('Order Tax Number Valid'),
                'formatted_value' => $this->get('Tax Number Valid'),


            )
        );


    }

    function update_tax_number_validation() {
        include_once 'utils/validate_tax_number.php';
        $tax_validation_data = validate_tax_number($this->data['Order Tax Number'], $this->data['Order Invoice Address Country 2 Alpha Code']);

        $this->update(
            array(
                'Order Tax Number Valid'              => $tax_validation_data['Tax Number Valid'],
                'Order Tax Number Details Match'      => $tax_validation_data['Tax Number Details Match'],
                'Order Tax Number Validation Date'    => $tax_validation_data['Tax Number Validation Date'],
                'Order Tax Number Validation Source'  => 'Online',
                'Order Tax Number Validation Message' => $tax_validation_data['Tax Number Validation Message'],
            ), 'no_history'
        );


    }

    function update_tax($tax_category_code = false) {


        $old_tax_code = $this->data['Order Tax Code'];

        if ($tax_category_code) {
            $tax_category = new TaxCategory('code', $tax_category_code);
            if (!$tax_category->id) {
                $this->msg   = 'Invalid tax code';
                $this->error = true;

                return;
            } else {

                $this->data['Order Tax Code']           = $tax_category->data['Tax Category Code'];
                $this->data['Order Tax Rate']           = $tax_category->data['Tax Category Rate'];
                $this->data['Order Tax Name']           = $tax_category->data['Tax Category Name'];
                $this->data['Order Tax Operations']     = '';
                $this->data['Order Tax Selection Type'] = 'set';

            }


        } else {

            $tax_data = $this->get_tax_data();

            //print_r($tax_data);
            $this->data['Order Tax Code']           = $tax_data['code'];
            $this->data['Order Tax Rate']           = $tax_data['rate'];
            $this->data['Order Tax Name']           = $tax_data['name'];
            $this->data['Order Tax Operations']     = $tax_data['operations'];
            $this->data['Order Tax Selection Type'] = $tax_data['state'];

        }


        $sql = sprintf(
            "UPDATE `Order Transaction Fact` SET `Transaction Tax Rate`=%f,`Transaction Tax Code`=%s WHERE `Order Key`=%d AND `Consolidated`='No' AND `Transaction Tax Code`=%s  ",
            $this->data['Order Tax Rate'], prepare_mysql($this->data['Order Tax Code']), $this->id, prepare_mysql($old_tax_code)

        );
        $this->db->exec($sql);
        $sql = sprintf(
            "SELECT `Tax Category Code`,`Transaction Type`,`Order No Product Transaction Fact Key`,`Transaction Net Amount` FROM `Order No Product Transaction Fact`  WHERE `Order Key`=%d AND `Consolidated`='No'",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Transaction Type'] == 'Insurance') {
                    // this to be removed!!!!


                    $_transaction_tax_category = new TaxCategory('code', 'EX');
                    $sql                       = sprintf(
                        "UPDATE `Order No Product Transaction Fact` SET `Transaction Tax Amount`=%f,`Tax Category Code`=%s WHERE `Order No Product Transaction Fact Key`=%d",
                        $row['Transaction Net Amount'] * $_transaction_tax_category->data['Tax Category Rate'], prepare_mysql(
                            $_transaction_tax_category->data['Tax Category Code']
                        ), $row['Order No Product Transaction Fact Key']
                    );

                    $this->db->exec($sql);
                } elseif ($row['Tax Category Code'] == $old_tax_code) {

                    $sql = sprintf(
                        "UPDATE `Order No Product Transaction Fact` SET `Transaction Tax Amount`=%f,`Tax Category Code`=%s WHERE `Order No Product Transaction Fact Key`=%d",
                        $row['Transaction Net Amount'] * $this->data['Order Tax Rate'], prepare_mysql($this->data['Order Tax Code']), $row['Order No Product Transaction Fact Key']
                    );
                    $this->db->exec($sql);

                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf(
            "UPDATE `Order Dimension` SET `Order Tax Code`=%s ,`Order Tax Rate`=%f,`Order Tax Name`=%s,`Order Tax Operations`=%s,`Order Tax Selection Type`=%s WHERE `Order Key`=%d",
            prepare_mysql($this->data['Order Tax Code']), $this->data['Order Tax Rate'], prepare_mysql($this->data['Order Tax Name']), prepare_mysql($this->data['Order Tax Operations'], false),
            prepare_mysql($this->data['Order Tax Selection Type']), $this->id
        );

        $this->db->exec($sql);

        $this->update_totals();
       // $this->apply_payment_from_customer_account();

    }

    function get_tax_data() {


        include_once 'utils/geography_functions.php';

        $store    = get_object('store', $this->data['Order Store Key']);
        $customer = get_object('Customer', $this->data['Order Customer Key']);


        switch ($store->data['Store Tax Country Code']) {
            case 'ESP':


                $sql = sprintf(
                    "SELECT `Tax Category Code`,`Tax Category Type`,`Tax Category Name`,`Tax Category Rate` FROM kbase.`Tax Category Dimension`  WHERE `Tax Category Country Code`='ESP' AND `Tax Category Active`='Yes'"
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {

                        switch ($row['Tax Category Name']) {
                            case 'Exento':
                                $tax_category_name = _('Exempt');
                                break;
                            case 'IVA 21%':
                                $tax_category_name = _('VAT').' 21%';
                                break;
                            case 'RE (5,2%)':
                                $tax_category_name = 'RE (5,2%)';
                                break;
                            case 'IVA+RE (26,2%)':
                                $tax_category_name = 'IVA+RE (26,2%)';
                                break;


                            default:
                                $tax_category_name = $row['Tax Category Name'];
                        }


                        $tax_category[$row['Tax Category Type']] = array(
                            'code' => $row['Tax Category Code'],
                            'name' => $tax_category_name,
                            'rate' => $row['Tax Category Rate']
                        );

                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                if ($this->data['Order Delivery Address Country 2 Alpha Code'] == 'ES' and $this->data['Order Invoice Address Country 2 Alpha Code'] == 'ES' and preg_match(
                        '/^(35|38|51|52)/', $this->data['Order Delivery Address Postal Code']
                    ) and preg_match(
                        '/^(35|38|51|52)/', $this->data['Order Invoice Address Postal Code']
                    )) {

                    return array(
                        'code'       => $tax_category['Excluded']['code'],
                        'name'       => $tax_category['Excluded']['name'],
                        'rate'       => $tax_category['Excluded']['rate'],
                        'state'      => 'outside EC',
                        'operations' => '<div>'._('Outside EC fiscal area').'</div>'

                    );
                }

                // new rule seems that is valid to ESP, E.g. billing to Madrid and shipping to canarias
                if ($this->data['Order Delivery Address Country 2 Alpha Code'] == 'ES' and $this->data['Order Invoice Address Country 2 Alpha Code'] == 'ES' and preg_match(
                        '/^(35|38|51|52)/', $this->data['Order Delivery Address Postal Code']
                    )) {

                    return array(
                        'code'       => $tax_category['Excluded']['code'],
                        'name'       => $tax_category['Excluded']['name'],
                        'rate'       => $tax_category['Excluded']['rate'],
                        'state'      => 'outside EC',
                        'operations' => '<div>'._('Outside EC fiscal area').'</div>'

                    );
                }


                if (in_array(
                    $this->data['Order Delivery Address Country 2 Alpha Code'], array(
                                                                                  'ES',
                                                                                  'XX'
                                                                              )
                )) {

                    if ($customer->data['Recargo Equivalencia'] == 'Yes') {

                        return array(
                            'code'       => $tax_category['IVA+RE']['code'],
                            'name'       => $tax_category['IVA+RE']['name'],
                            'rate'       => $tax_category['IVA+RE']['rate'],
                            'state'      => 'delivery to ESP with RE',
                            'operations' => ' <div class="buttons small"><button id="remove_recargo_de_equivalencia" title="Quitar Recargo de equivalencia" style="margin:0px" onClick="update_recargo_de_equivalencia(\'No\')"><img src="/art/icons/delete.png"> RE</button></div>'

                        );

                    } else {

                        return array(
                            'code'       => $tax_category['IVA']['code'],
                            'name'       => $tax_category['IVA']['name'],
                            'rate'       => $tax_category['IVA']['rate'],
                            'state'      => 'delivery to ESP',
                            'operations' => ' <div class="buttons small"><button id="add_recargo_de_equivalencia" title="Añade Recargo de equivalencia" style="margin:0px" onClick="update_recargo_de_equivalencia(\'Yes\')"><img src="/art/icons/add.png"> RE (5,2%)</button></div>'

                        );

                    }


                } elseif (in_array(
                    $this->data['Order Invoice Address Country 2 Alpha Code'], array(
                                                                                 'ES',
                                                                                 'XX'
                                                                             )
                )) {

                    if ($customer->data['Recargo Equivalencia'] == 'Yes') {

                        return array(
                            'code'       => $tax_category['IVA+RE']['code'],
                            'name'       => $tax_category['IVA+RE']['name'],
                            'rate'       => $tax_category['IVA+RE']['rate'],
                            'state'      => 'billing to ESP with RE',
                            'operations' => ' <div class="buttons small"><button id="remove_recargo_de_equivalencia" title="Quitar Recargo de equivalencia" style="margin:0px" onClick="update_recargo_de_equivalencia(\'No\')"><img src="/art/icons/delete.png"> RE</button></div>'

                        );

                    } else {

                        return array(
                            'code'       => $tax_category['IVA']['code'],
                            'name'       => $tax_category['IVA']['name'],
                            'rate'       => $tax_category['IVA']['rate'],
                            'state'      => 'billing to ESP',
                            'operations' => ' <div class="buttons small"><button id="add_recargo_de_equivalencia" title="Añade Recargo de equivalencia" style="margin:0px" onClick="update_recargo_de_equivalencia(\'Yes\')"><img src="/art/icons/add.png"> RE (5,2%)</button></div>'

                        );

                    }


                } elseif (in_array(
                    $this->data['Order Invoice Address Country 2 Alpha Code'], get_countries_EC_Fiscal_VAT_area($this->db)
                )) {


                    if ($this->data['Order Tax Number Valid'] == 'Yes') {


                        $response = array(
                            'code'       => $tax_category['Excluded']['code'],
                            'name'       => $tax_category['Excluded']['name'].'<div>'._('Valid tax number').'<br>'.$this->data['Order Tax Number'].'</div>',
                            'rate'       => $tax_category['Excluded']['rate'],
                            'state'      => 'EC with valid tax number',
                            'operations' => ''

                        );

                    } else {

                        if ($this->data['Order Tax Number'] == '') {


                            $response = array(
                                'code'       => $tax_category['IVA']['code'],
                                'name'       => $tax_category['IVA']['name'],
                                'rate'       => $tax_category['IVA']['rate'],
                                'state'      => 'EC no tax number',
                                'operations' => '<div><img  style="width:12px;position:relative:bottom:2px" src="/art/icons/information.png"/><span style="font-size:90%"> '._(
                                        'VAT might be exempt with a valid tax number'
                                    ).'</span> <div class="buttons small"><button id="set_tax_number" style="margin:0px" onClick="show_set_tax_number_dialog()">'._('Set up tax number')
                                    .'</button></div></div>'

                            );

                        } else {


                            $response = array(
                                'code'  => $tax_category['IVA']['code'],
                                'name'  => $tax_category['IVA']['name'],
                                'rate'  => $tax_category['IVA']['rate'],
                                'state' => 'EC with invalid tax number',

                                'operations' => '<div>
					<img style="width:12px;position:relative;bottom:-1px" src="/art/icons/error.png">
					<span style="font-size:90%;"  >'._('Invalid tax number').'</span>
					<img style="cursor:pointer;position:relative;top:4px"  onClick="check_tax_number_from_tax_info()"  id="check_tax_number" src="/art/validate.png" alt="('._('Validate').')" title="'
                                    ._('Validate').'">
					<br/>
					<img id="set_tax_number" style="width:14px;cursor:pointer;position:relative;top:2px" src="/art/icons/edit.gif"  onClick="show_set_tax_number_dialog()" title="'._('Edit tax number')
                                    .'"/>

					<span id="tax_number">'.$this->data['Order Tax Number'].'</span>
				</div>'

                            );


                        }

                    }

                    return $response;
                } else {


                    if (in_array(
                        $this->data['Order Delivery Address Country 2 Alpha Code'], get_countries_EC_Fiscal_VAT_area($this->db)
                    )) {


                        return array(
                            'code'       => $tax_category['IVA']['code'],
                            'name'       => $tax_category['IVA']['name'],
                            'rate'       => $tax_category['IVA']['rate'],
                            'state'      => 'delivery to EC with no EC billing',
                            'operations' => ''

                        );

                    } else {
                        return array(
                            'code'       => $tax_category['Excluded']['code'],
                            'name'       => $tax_category['Excluded']['name'],
                            'rate'       => $tax_category['Excluded']['rate'],
                            'state'      => 'ouside EC',
                            'operations' => '<div>'._('Outside EC fiscal area').'</div>'

                        );

                    }

                }
                break;
            case 'GBR':

                $tax_category = array();

                $sql = sprintf(
                    "SELECT `Tax Category Code`,`Tax Category Type`,`Tax Category Name`,`Tax Category Rate` FROM kbase.`Tax Category Dimension`  WHERE `Tax Category Country Code`='GBR' AND `Tax Category Active`='Yes'"
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {


                        switch ($row['Tax Category Name']) {
                            case 'Outside the scope of VAT':
                                $tax_category_name = _(
                                    'Outside the scope of VAT'
                                );
                                break;
                            case 'VAT 17.5%':
                                $tax_category_name = _('VAT').' 17.5%';
                                break;
                            case 'VAT 20%':
                                $tax_category_name = _('VAT').' 20%';
                                break;
                            case 'VAT 15%':
                                $tax_category_name = _('VAT').' 15%';
                                break;
                            case 'No Tax':
                                $tax_category_name = _('No Tax');
                                break;
                            case 'Exempt from VAT':
                                $tax_category_name = _('Exempt from VAT');
                                break;


                            default:
                                $tax_category_name = $row['Tax Category Name'];
                        }


                        $tax_category[$row['Tax Category Type']] = array(
                            'code' => $row['Tax Category Code'],
                            'name' => $tax_category_name,
                            'rate' => $row['Tax Category Rate']
                        );


                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


                if (in_array(
                    $this->data['Order Delivery Address Country 2 Alpha Code'], array(
                                                                                  'GB',
                                                                                  'XX',
                                                                                  'IM'
                                                                              )
                )) {

                    return array(
                        'code'       => $tax_category['Standard']['code'],
                        'name'       => $tax_category['Standard']['name'],
                        'rate'       => $tax_category['Standard']['rate'],
                        'state'      => 'delivery to GBR',
                        'operations' => ''

                    );
                } elseif (in_array(
                    $this->data['Order Invoice Address Country 2 Alpha Code'], array(
                                                                                 'GBR',
                                                                                 'UNK',
                                                                                 'IM'
                                                                             )
                )) {

                    return array(
                        'code'       => $tax_category['Standard']['code'],
                        'name'       => $tax_category['Standard']['name'],
                        'rate'       => $tax_category['Standard']['rate'],
                        'state'      => 'billing to GBR',
                        'operations' => ''
                    );
                } elseif (in_array(
                    $this->data['Order Invoice Address Country 2 Alpha Code'], get_countries_EC_Fiscal_VAT_area($this->db)
                )) {


                    if ($this->data['Order Tax Number Valid'] == 'Yes') {


                        $response = array(
                            'code'       => $tax_category['Outside']['code'],
                            'name'       => $tax_category['Outside']['name'].'<div>'._('Valid tax number').'<br>'.$this->data['Order Tax Number'].'</div>',
                            'rate'       => $tax_category['Outside']['rate'],
                            'state'      => 'EC with valid tax number',
                            'operations' => ''

                        );

                    } else {

                        if ($this->data['Order Tax Number'] == '') {


                            $response = array(
                                'code'       => $tax_category['Standard']['code'],
                                'name'       => $tax_category['Standard']['name'],
                                'rate'       => $tax_category['Standard']['rate'],
                                'state'      => 'EC no tax number',
                                'operations' => '<div><img  style="width:12px;position:relative:bottom:2px" src="/art/icons/information.png"/><span style="font-size:90%"> '._(
                                        'VAT might be exempt with a valid tax number'
                                    ).'</span> <div class="buttons small"><button id="set_tax_number" style="margin:0px" onClick="show_set_tax_number_dialog()">'._('Set up tax number')
                                    .'</button></div></div>'

                            );

                        } else {


                            $response = array(
                                'code'  => $tax_category['Standard']['code'],
                                'name'  => $tax_category['Standard']['name'],
                                'rate'  => $tax_category['Standard']['rate'],
                                'state' => 'EC with invalid tax number',

                                'operations' => '<div>
					<img style="width:12px;position:relative;bottom:-1px" src="/art/icons/error.png">
					<span style="font-size:90%;"  >'._('Invalid tax number').'</span>
					<img style="cursor:pointer;position:relative;top:4px"  onClick="check_tax_number_from_tax_info()"  id="check_tax_number" src="/art/validate.png" alt="('._('Validate').')" title="'
                                    ._('Validate').'">
					<br/>
					<img id="set_tax_number" style="width:14px;cursor:pointer;position:relative;top:2px" src="/art/icons/edit.gif"  onClick="show_set_tax_number_dialog()" title="'._('Edit tax number')
                                    .'"/>

					<span id="tax_number">'.$this->data['Order Tax Number'].'</span>
				</div>'

                            );


                        }

                    }


                    return $response;

                } else {


                    if (in_array(
                        $this->data['Order Delivery Address Country 2 Alpha Code'], get_countries_EC_Fiscal_VAT_area($this->db)
                    )) {


                        return array(
                            'code'       => $tax_category['Standard']['code'],
                            'name'       => $tax_category['Standard']['name'],
                            'rate'       => $tax_category['Standard']['rate'],
                            'state'      => 'delivery to EC with no EC billing',
                            'operations' => ''

                        );

                    } else {
                        return array(
                            'code'       => $tax_category['Outside']['code'],
                            'name'       => $tax_category['Outside']['name'],
                            'rate'       => $tax_category['Outside']['rate'],
                            'state'      => 'ouside EC',
                            'operations' => '<div>'._('Outside EC fiscal area').'</div>'

                        );

                    }

                }


                break;
            case 'SVK':

                $tax_category = array();

                $sql = sprintf(
                    "SELECT `Tax Category Code`,`Tax Category Type`,`Tax Category Name`,`Tax Category Rate` FROM kbase.`Tax Category Dimension`  WHERE `Tax Category Country Code`='SVK' AND `Tax Category Active`='Yes'"
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {


                        switch ($row['Tax Category Name']) {
                            case 'Outside the scope of VAT':
                                $tax_category_name = _('Outside the scope of VAT');
                                break;

                            case 'VAT 20%':
                                $tax_category_name = _('VAT').' 20%';
                                break;
                            case 'No Tax':
                                $tax_category_name = _('No Tax');
                                break;
                            case 'Exempt from VAT':
                                $tax_category_name = _('Exempt from VAT');
                                break;


                            default:
                                $tax_category_name = $row['Tax Category Name'];
                        }


                        $tax_category[$row['Tax Category Type']] = array(
                            'code' => $row['Tax Category Code'],
                            'name' => $tax_category_name,
                            'rate' => $row['Tax Category Rate']
                        );


                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


                //   print_r($tax_category);


                if (in_array(
                    $this->data['Order Delivery Address Country 2 Alpha Code'], array(
                                                                                  'SK',
                                                                                  'XX'
                                                                              )
                )) {

                    return array(
                        'code'       => $tax_category['Standard']['code'],
                        'name'       => $tax_category['Standard']['name'],
                        'rate'       => $tax_category['Standard']['rate'],
                        'state'      => 'delivery to SVK',
                        'operations' => ''

                    );
                } elseif (in_array(
                    $this->data['Order Invoice Address Country 2 Alpha Code'], array(
                                                                                 'SK',
                                                                                 'XX'
                                                                             )
                )) {

                    return array(
                        'code'       => $tax_category['Standard']['code'],
                        'name'       => $tax_category['Standard']['name'],
                        'rate'       => $tax_category['Standard']['rate'],
                        'state'      => 'billing to SVK',
                        'operations' => ''
                    );
                } elseif (in_array(
                    $this->data['Order Invoice Address Country 2 Alpha Code'], get_countries_EC_Fiscal_VAT_area($this->db)
                )) {


                    if ($this->data['Order Tax Number Valid'] == 'Yes') {


                        $response = array(
                            'code'       => $tax_category['Outside']['code'],
                            'name'       => $tax_category['Outside']['name'].'<div>'._('Valid tax number').'<br>'.$this->data['Order Tax Number'].'</div>',
                            'rate'       => $tax_category['Outside']['rate'],
                            'state'      => 'EC with valid tax number',
                            'operations' => ''

                        );

                    } else {

                        if ($this->data['Order Tax Number'] == '') {


                            $response = array(
                                'code'       => $tax_category['Standard']['code'],
                                'name'       => $tax_category['Standard']['name'],
                                'rate'       => $tax_category['Standard']['rate'],
                                'state'      => 'EC no tax number',
                                'operations' => '<div><img  style="width:12px;position:relative:bottom:2px" src="/art/icons/information.png"/><span style="font-size:90%"> '._(
                                        'VAT might be exempt with a valid tax number'
                                    ).'</span> <div class="buttons small"><button id="set_tax_number" style="margin:0px" onClick="show_set_tax_number_dialog()">'._('Set up tax number')
                                    .'</button></div></div>'

                            );

                        } else {


                            $response = array(
                                'code'  => $tax_category['Standard']['code'],
                                'name'  => $tax_category['Standard']['name'],
                                'rate'  => $tax_category['Standard']['rate'],
                                'state' => 'EC with invalid tax number',

                                'operations' => '<div>
					<img style="width:12px;position:relative;bottom:-1px" src="/art/icons/error.png">
					<span style="font-size:90%;"  >'._('Invalid tax number').'</span>
					<img style="cursor:pointer;position:relative;top:4px"  onClick="check_tax_number_from_tax_info()"  id="check_tax_number" src="/art/validate.png" alt="('._('Validate').')" title="'
                                    ._('Validate').'">
					<br/>
					<img id="set_tax_number" style="width:14px;cursor:pointer;position:relative;top:2px" src="/art/icons/edit.gif"  onClick="show_set_tax_number_dialog()" title="'._('Edit tax number')
                                    .'"/>

					<span id="tax_number">'.$this->data['Order Tax Number'].'</span>
				</div>'

                            );


                        }

                    }


                    return $response;

                } else {


                    if (in_array(
                        $this->data['Order Delivery Address Country 2 Alpha Code'], get_countries_EC_Fiscal_VAT_area($this->db)
                    )) {


                        return array(
                            'code'       => $tax_category['Standard']['code'],
                            'name'       => $tax_category['Standard']['name'],
                            'rate'       => $tax_category['Standard']['rate'],
                            'state'      => 'delivery to EC with no EC billing',
                            'operations' => ''

                        );

                    } else {
                        return array(
                            'code'       => $tax_category['Outside']['code'],
                            'name'       => $tax_category['Outside']['name'],
                            'rate'       => $tax_category['Outside']['rate'],
                            'state'      => 'ouside EC',
                            'operations' => '<div>'._('Outside EC fiscal area').'</div>'

                        );

                    }

                }


                break;
        }


    }

    function update_tax_number_valid($value) {

        include_once 'utils/validate_tax_number.php';

        if ($value == 'Auto') {

            $tax_validation_data = validate_tax_number(
                $this->data['Order Tax Number'], $this->data['Order Invoice Address Country 2 Alpha Code']
            );

            $this->update(
                array(
                    'Order Tax Number Valid'              => $tax_validation_data['Tax Number Valid'],
                    'Order Tax Number Details Match'      => $tax_validation_data['Tax Number Details Match'],
                    'Order Tax Number Validation Date'    => $tax_validation_data['Tax Number Validation Date'],
                    'Order Tax Number Validation Source'  => 'Online',
                    'Order Tax Number Validation Message' => $tax_validation_data['Tax Number Validation Message'],
                ), 'no_history'
            );

        } else {
            $this->update_field('Order Tax Number Valid', $value);
            $this->update(
                array(
                    'Order Tax Number Details Match'      => 'Unknown',
                    'Order Tax Number Validation Date'    => $this->editor['Date'],
                    'Order Tax Number Validation Source'  => 'Manual',
                    'Order Tax Number Validation Message' => $this->editor['Author Name'],
                ), 'no_history'
            );
        }


        $this->other_fields_updated = array(
            'Order_Tax_Number' => array(
                'field'           => 'Order_Tax_Number',
                'render'          => true,
                'value'           => $this->get('Order Tax Number'),
                'formatted_value' => $this->get('Tax Number'),


            )
        );


    }

}


?>
