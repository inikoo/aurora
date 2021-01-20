<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 August 2017 at 08:43:35 CEST, Tranava, Slovakia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/

trait OrderTax {

    /**
     * @var PDO
     */
    public $db;

    function update_tax_number($value, $options = '', $updated_from_invoice = false) {


        if ($this->get('State Index') <= 0) {
            return;
        }


        $this->update_field('Order Tax Number', $value, $options);


        if ($this->updated and !$updated_from_invoice) {

            $this->validate_order_tax_number();


            $this->new_value = $value;

            $this->update_tax();
            $this->other_fields_updated = array(
                'Order_Tax_Number_Valid' => array(
                    'field'           => 'Order_Tax_Number_Valid',
                    'render'          => ($this->get('Order Tax Number') == '' ? false : true),
                    'value'           => $this->get('Order Tax Number Valid'),
                    'formatted_value' => $this->get('Tax Number Valid'),


                )
            );

        }


    }

    function validate_order_tax_number() {

        if (!empty($this->skip_validate_tax_number)) {
            return;
        }


        if ($this->get('State Index') <= 0) {
            return;
        }

        if ($this->data['Order Tax Number'] == '') {
            $this->fast_update(
                array(
                    'Order Tax Number Valid'              => 'Unknown',
                    'Order Tax Number Details Match'      => '',
                    'Order Tax Number Validation Date'    => '',
                    'Order Tax Number Validation Source'  => '',
                    'Order Tax Number Validation Message' => ''
                )
            );
        } else {
            include_once 'utils/validate_tax_number.php';
            $tax_validation_data = validate_tax_number($this->data['Order Tax Number'], $this->data['Order Invoice Address Country 2 Alpha Code']);

            if ($tax_validation_data['Tax Number Valid'] == 'API_Down') {
                if (!($this->data['Order Tax Number Validation Source'] == '' and $this->data['Order Tax Number Valid'] == 'No')) {
                    return;
                }
            }

            $this->fast_update(
                array(
                    'Order Tax Number Valid'              => $tax_validation_data['Tax Number Valid'],
                    'Order Tax Number Details Match'      => $tax_validation_data['Tax Number Details Match'],
                    'Order Tax Number Validation Date'    => $tax_validation_data['Tax Number Validation Date'],
                    'Order Tax Number Validation Source'  => $tax_validation_data['Tax Number Validation Source'],
                    'Order Tax Number Validation Message' => $tax_validation_data['Tax Number Validation Message'],
                )
            );
        }

        $this->update_tax();

    }


    function update_tax_number_valid($value) {

        include_once 'utils/validate_tax_number.php';

        if ($value == 'Auto') {

            $tax_validation_data = validate_tax_number($this->data['Order Tax Number'], $this->data['Order Invoice Address Country 2 Alpha Code']);

            if ($tax_validation_data['Tax Number Valid'] == 'API_Down') {
                if (!($this->data['Order Tax Number Validation Source'] == '' and $this->data['Order Tax Number Valid'] == 'No')) {
                    $this->error = true;
                    $this->msg   = '<span class="error"><i class="fa fa-exclamation-circle"></i> '.$tax_validation_data['Tax Number Validation Message'].'</span>';

                    return;
                }
            }

            $this->update(
                array(
                    'Order Tax Number Valid'              => $tax_validation_data['Tax Number Valid'],
                    'Order Tax Number Details Match'      => $tax_validation_data['Tax Number Details Match'],
                    'Order Tax Number Validation Date'    => $tax_validation_data['Tax Number Validation Date'],
                    'Order Tax Number Validation Source'  => 'Online',
                    'Order Tax Number Validation Message' => 'B'.$tax_validation_data['Tax Number Validation Message'],
                ), 'no_history'
            );

        } else {
            $this->update_field('Order Tax Number Valid', $value);
            $this->update(
                array(
                    'Order Tax Number Details Match'      => 'Unknown',
                    'Order Tax Number Validation Date'    => $this->editor['Date'],
                    'Order Tax Number Validation Source'  => 'Staff',
                    'Order Tax Number Validation Message' => $this->editor['Author Name'],
                ), 'no_history'
            );
        }

        $this->update_tax();


        $this->other_fields_updated = array(
            'Order_Tax_Number' => array(
                'field'           => 'Order_Tax_Number',
                'render'          => true,
                'value'           => $this->get('Order Tax Number'),
                'formatted_value' => $this->get('Tax Number'),


            )
        );


    }


    function update_tax($tax_category_code = false, $update_from_invoice_key = false) {


        $old_tax_code = $this->data['Order Tax Code'];


        if ($this->get('State Index') > 90 and $this->get('Order Invoice Key')) {
            $edit_otf = false;

        } else {
            $edit_otf = true;
        }

        if ($update_from_invoice_key) {
            $edit_otf = true;

        }


        if (!$edit_otf) {


            if ($this->metadata('original_tax_code') == '') {
                $this->fast_update_json_field('Order Metadata', 'original_tax_code', $old_tax_code);
                $this->fast_update_json_field('Order Metadata', 'original_tax_description', $this->get('Tax Description'));


            }
        }

        if ($tax_category_code) {
            $tax_category = new TaxCategory('code', $tax_category_code);
            if (!$tax_category->id) {
                $this->msg   = 'Invalid tax code';
                $this->error = true;

                return;
            } else {

                $new_tax_code             = $tax_category->data['Tax Category Code'];
                $tax_rate                 = $tax_category->data['Tax Category Rate'];
                $tax_name                 = $tax_category->data['Tax Category Name'];
                $reason_tax_code_selected = 'set';

            }


        } else {

            $tax_data = $this->get_tax_data();

            $new_tax_code = $tax_data['code'];
            $tax_rate     = $tax_data['rate'];

            $tax_name                 = $tax_data['name'];
            $reason_tax_code_selected = $tax_data['reason_tax_code_selected'];


        }

        if ($update_from_invoice_key > 0) {
            $sql = sprintf(
                "UPDATE `Order Transaction Fact` SET `Transaction Tax Rate`=%f,`Transaction Tax Code`=%s WHERE `Order Key`=%d AND `Invoice Key`=%d  ", $tax_rate, prepare_mysql($new_tax_code), $this->id, $update_from_invoice_key

            );
        } else {
            $sql = sprintf(
                "UPDATE `Order Transaction Fact` SET `Transaction Tax Rate`=%f,`Transaction Tax Code`=%s WHERE `Order Key`=%d AND `Consolidated`='No'  ", $tax_rate, prepare_mysql($new_tax_code), $this->id

            );
        }

        if ($edit_otf) {
            $this->db->exec($sql);

        }


        if ($update_from_invoice_key > 0) {
            $sql = sprintf(
                "SELECT `Tax Category Code`,`Transaction Type`,`Order No Product Transaction Fact Key`,`Transaction Net Amount` FROM `Order No Product Transaction Fact`  WHERE `Order Key`=%d AND `Invoice Key`=%d  ", $this->id, $update_from_invoice_key
            );
        } else {
            $sql = sprintf(
                "SELECT `Tax Category Code`,`Transaction Type`,`Order No Product Transaction Fact Key`,`Transaction Net Amount` FROM `Order No Product Transaction Fact`  WHERE `Order Key`=%d AND `Consolidated`='No'", $this->id
            );
        }

        if ($edit_otf) {
            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {


                    $sql = sprintf(
                        "UPDATE `Order No Product Transaction Fact` SET `Transaction Tax Amount`=%f,`Tax Category Code`=%s WHERE `Order No Product Transaction Fact Key`=%d", $row['Transaction Net Amount'] * $tax_rate, prepare_mysql($new_tax_code),
                        $row['Order No Product Transaction Fact Key']
                    );
                    $this->db->exec($sql);


                }
            }
        }

        $this->fast_update(
            array(
                'Order Tax Code' => $new_tax_code,
                'Order Tax Rate' => $tax_rate
            )
        );


        $this->fast_update_json_field('Order Metadata', 'tax_name', $tax_name);
        $this->fast_update_json_field('Order Metadata', 'why_tax', $reason_tax_code_selected);

        if ($edit_otf) {
            $this->update_totals();
        } else {
            $this->fast_update_json_field('Order Metadata', 'post_invoice_tax_code', $new_tax_code);

        }
    }

    function get_tax_data() {

        $account=get_object('Account',1);

        include_once 'utils/geography_functions.php';

        $store = get_object('store', $this->data['Order Store Key']);


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
                            case 'Outside the scope of VAT':
                                $tax_category_name = _('Outside the scope of VAT');
                                break;
                            case 'EU with valid tax code':
                                $tax_category_name = _('EU with valid tax code');
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
                        'code'                     => $tax_category['Exempt']['code'],
                        'name'                     => $tax_category['Exempt']['name'],
                        'rate'                     => $tax_category['Exempt']['rate'],
                        'reason_tax_code_selected' => 'exempt VAT',
                        'operations'               => '<div>'._('Exempt VAT').'</div>'

                    );
                }

                // new rule seems that is valid to ESP, E.g. billing to Madrid and shipping to canarias
                if ($this->data['Order Delivery Address Country 2 Alpha Code'] == 'ES' and $this->data['Order Invoice Address Country 2 Alpha Code'] == 'ES' and preg_match(
                        '/^(35|38|51|52)/', $this->data['Order Delivery Address Postal Code']
                    )) {

                    return array(
                        'code'                     => $tax_category['Exempt']['code'],
                        'name'                     => $tax_category['Exempt']['name'],
                        'rate'                     => $tax_category['Exempt']['rate'],
                        'reason_tax_code_selected' => 'exempt VAT',
                        'operations'               => '<div>'._('Exempt VAT').'</div>'

                    );
                }


                if (in_array(
                    $this->data['Order Delivery Address Country 2 Alpha Code'], array(
                                                                                  'ES',
                                                                                  'XX'
                                                                              )
                )) {


                    if ($this->metadata('RE') == 'Yes') {

                        return array(
                            'code'                     => $tax_category['IVA+RE']['code'],
                            'name'                     => $tax_category['IVA+RE']['name'],
                            'rate'                     => $tax_category['IVA+RE']['rate'],
                            'reason_tax_code_selected' => 'delivery to ESP with RE',
                            'operations'               => ' <div class="buttons small"><button id="remove_recargo_de_equivalencia" title="Quitar Recargo de equivalencia" style="margin:0px" onClick="update_recargo_de_equivalencia(\'No\')"><img src="/art/icons/delete.png"> RE</button></div>'

                        );

                    } else {

                        return array(
                            'code'                     => $tax_category['IVA']['code'],
                            'name'                     => $tax_category['IVA']['name'],
                            'rate'                     => $tax_category['IVA']['rate'],
                            'reason_tax_code_selected' => 'delivery to ESP',
                            'operations'               => ' <div class="buttons small"><button id="add_recargo_de_equivalencia" title="Añade Recargo de equivalencia" style="margin:0px" onClick="update_recargo_de_equivalencia(\'Yes\')"><img src="/art/icons/add.png"> RE (5,2%)</button></div>'

                        );

                    }


                } elseif (in_array(
                    $this->data['Order Invoice Address Country 2 Alpha Code'], array(
                                                                                 'ES',
                                                                                 'XX'
                                                                             )
                )) {

                    if ($this->metadata('RE') == 'Yes') {

                        return array(
                            'code'                     => $tax_category['IVA+RE']['code'],
                            'name'                     => $tax_category['IVA+RE']['name'],
                            'rate'                     => $tax_category['IVA+RE']['rate'],
                            'reason_tax_code_selected' => 'billing to ESP with RE',
                            'operations'               => ' <div class="buttons small"><button id="remove_recargo_de_equivalencia" title="Quitar Recargo de equivalencia" style="margin:0px" onClick="update_recargo_de_equivalencia(\'No\')"><img src="/art/icons/delete.png"> RE</button></div>'

                        );

                    } else {

                        return array(
                            'code'                     => $tax_category['IVA']['code'],
                            'name'                     => $tax_category['IVA']['name'],
                            'rate'                     => $tax_category['IVA']['rate'],
                            'reason_tax_code_selected' => 'billing to ESP',
                            'operations'               => ' <div class="buttons small"><button id="add_recargo_de_equivalencia" title="Añade Recargo de equivalencia" style="margin:0px" onClick="update_recargo_de_equivalencia(\'Yes\')"><img src="/art/icons/add.png"> RE (5,2%)</button></div>'

                        );

                    }


                } elseif (in_array(
                    $this->data['Order Invoice Address Country 2 Alpha Code'], get_countries_EC_Fiscal_VAT_area($this->db)
                )) {


                    if ($this->data['Order Tax Number Valid'] == 'Yes') {


                        $response = array(
                            'code'                     => $tax_category['EU_VTC']['code'],
                            'name'                     => _('EC with valid tax number').'<span>'.$this->data['Order Tax Number'].'</span>',
                            'rate'                     => $tax_category['EU_VTC']['rate'],
                            'reason_tax_code_selected' => 'EC with valid tax number',
                            'operations'               => ''

                        );

                    } else {

                        if ($this->data['Order Tax Number'] == '') {


                            $response = array(
                                'code'                     => $tax_category['IVA']['code'],
                                'name'                     => $tax_category['IVA']['name'],
                                'rate'                     => $tax_category['IVA']['rate'],
                                'reason_tax_code_selected' => 'EC no tax number',
                                'operations'               => '<div><img  style="width:12px;position:relative:bottom:2px" src="/art/icons/information.png"/><span style="font-size:90%"> '._(
                                        'VAT might be exempt with a valid tax number'
                                    ).'</span> <div class="buttons small"><button id="set_tax_number" style="margin:0px" onClick="show_set_tax_number_dialog()">'._('Set up tax number').'</button></div></div>'

                            );

                        } else {


                            $response = array(
                                'code'                     => $tax_category['IVA']['code'],
                                'name'                     => $tax_category['IVA']['name'],
                                'rate'                     => $tax_category['IVA']['rate'],
                                'reason_tax_code_selected' => 'EC with invalid tax number',

                                'operations' => '<div>
					<img style="width:12px;position:relative;bottom:-1px" src="/art/icons/error.png">
					<span style="font-size:90%;"  >'._('Invalid tax number').'</span>
					<img style="cursor:pointer;position:relative;top:4px"  onClick="check_tax_number_from_tax_info()"  id="check_tax_number" src="/art/validate.png" alt="('._('Validate').')" title="'._('Validate').'">
					<br/>
					<img id="set_tax_number" style="width:14px;cursor:pointer;position:relative;top:2px" src="/art/icons/edit.gif"  onClick="show_set_tax_number_dialog()" title="'._('Edit tax number').'"/>

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
                            'code'                     => $tax_category['IVA']['code'],
                            'name'                     => $tax_category['IVA']['name'],
                            'rate'                     => $tax_category['IVA']['rate'],
                            'reason_tax_code_selected' => 'delivery to EC with no EC billing',
                            'operations'               => ''

                        );

                    } else {
                        return array(
                            'code'                     => $tax_category['Outside']['code'],
                            'name'                     => $tax_category['Outside']['name'],
                            'rate'                     => $tax_category['Outside']['rate'],
                            'reason_tax_code_selected' => 'outside EC',
                            'operations'               => '<div>'._('Outside EC fiscal area').'</div>'

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
                                $tax_category_name = _('Outside the scope of VAT');
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
                            case 'EU with valid tax code':
                                $tax_category_name = _('EU with valid tax code');
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

                /*

                                if ($this->data['Order Delivery Address Country 2 Alpha Code'] == 'ES' and $this->data['Order Invoice Address Country 2 Alpha Code'] == 'ES' and preg_match(
                                        '/^(35|38|51|52)/', $this->data['Order Delivery Address Postal Code']
                                    ) and preg_match(
                                        '/^(35|38|51|52)/', $this->data['Order Invoice Address Postal Code']
                                    )) {

                                    return array(
                                        'code'                     => $tax_category['Outside']['code'],
                                        'name'                     => $tax_category['Outside']['name'],
                                        'rate'                     => $tax_category['Outside']['rate'],
                                        'reason_tax_code_selected' => 'outside EC',
                                        'operations'               => '<div>'._('Outside EC fiscal area').'</div>'

                                    );
                                }

                                // new rule seems that is valid to ESP, E.g. billing to Madrid and shipping to canarias
                                if ($this->data['Order Delivery Address Country 2 Alpha Code'] == 'ES' and $this->data['Order Invoice Address Country 2 Alpha Code'] == 'ES' and preg_match(
                                        '/^(35|38|51|52)/', $this->data['Order Delivery Address Postal Code']
                                    )) {

                                    return array(
                                        'code'                     => $tax_category['Outside']['code'],
                                        'name'                     => $tax_category['Outside']['name'],
                                        'rate'                     => $tax_category['Outside']['rate'],
                                        'reason_tax_code_selected' => 'outside EC',
                                        'operations'               => '<div>'._('Outside EC fiscal area').'</div>'

                                    );
                                }

                */

                if (in_array(
                    $this->data['Order Delivery Address Country 2 Alpha Code'], array(
                                                                                  'GB',
                                                                                  'XX',
                                                                                  'IM'
                                                                              )
                )) {

                    return array(
                        'code'                     => $tax_category['Standard']['code'],
                        'name'                     => $tax_category['Standard']['name'],
                        'rate'                     => $tax_category['Standard']['rate'],
                        'reason_tax_code_selected' => 'delivery to GBR',
                        'operations'               => ''

                    );
                } elseif (in_array(
                    $this->data['Order Invoice Address Country 2 Alpha Code'], array(
                                                                                 'GB',
                                                                                 'XX',
                                                                                 'IM'
                                                                             )
                )) {

                    return array(
                        'code'                     => $tax_category['Standard']['code'],
                        'name'                     => $tax_category['Standard']['name'],
                        'rate'                     => $tax_category['Standard']['rate'],
                        'reason_tax_code_selected' => 'billing to GBR',
                        'operations'               => ''
                    );
                } else {
                    return array(
                        'code'                     => $tax_category['Outside']['code'],
                        'name'                     => $tax_category['Outside']['name'],
                        'rate'                     => $tax_category['Outside']['rate'],
                        'reason_tax_code_selected' => 'outside EC',
                        'operations'               => '<div>'._('Outside EC fiscal area').'</div>'

                    );
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
                            case 'EU with valid tax code':
                                $tax_category_name = _('EU with valid tax code');
                                break;

                            default:
                                $tax_category_name = $row['Tax Category Name'];
                        }

                        $_tax_code= $row['Tax Category Code'];

                        if($account->get('Account Country Code')!=$store->data['Store Tax Country Code']){

                            switch ($_tax_code){
                                case 'S1':
                                    $_tax_code='XS1';
                                    break;
                                case 'OUT':
                                    $_tax_code='XOU';
                                    break;
                                case 'EX':
                                    $_tax_code='XEX';
                                    break;
                                case 'EU':
                                    $_tax_code='XEU';
                                    break;

                            }

                        }

                        $tax_category[$row['Tax Category Type']] = array(
                            'code' => $_tax_code,
                            'name' => $tax_category_name,
                            'rate' => $row['Tax Category Rate']
                        );


                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


                if ($this->data['Order Delivery Address Country 2 Alpha Code'] == 'ES' and $this->data['Order Invoice Address Country 2 Alpha Code'] == 'ES' and preg_match(
                        '/^(35|38|51|52)/', $this->data['Order Delivery Address Postal Code']
                    ) and preg_match(
                        '/^(35|38|51|52)/', $this->data['Order Invoice Address Postal Code']
                    )) {

                    return array(
                        'code'                     => $tax_category['Outside']['code'],
                        'name'                     => $tax_category['Outside']['name'],
                        'rate'                     => $tax_category['Outside']['rate'],
                        'reason_tax_code_selected' => 'outside EC',
                        'operations'               => '<div>'._('Outside EC fiscal area').'</div>'

                    );
                }

                // new rule seems that is valid to ESP, E.g. billing to Madrid and shipping to canarias
                if ($this->data['Order Delivery Address Country 2 Alpha Code'] == 'ES' and $this->data['Order Invoice Address Country 2 Alpha Code'] == 'ES' and preg_match(
                        '/^(35|38|51|52)/', $this->data['Order Delivery Address Postal Code']
                    )) {

                    return array(
                        'code'                     => $tax_category['Outside']['code'],
                        'name'                     => $tax_category['Outside']['name'],
                        'rate'                     => $tax_category['Outside']['rate'],
                        'reason_tax_code_selected' => 'outside EC',
                        'operations'               => '<div>'._('Outside EC fiscal area').'</div>'

                    );
                }

                //   print_r($tax_category);


                if (in_array(
                    $this->data['Order Delivery Address Country 2 Alpha Code'], array(
                                                                                  'SK',
                                                                                  'XX'
                                                                              )
                )) {

                    return array(
                        'code'                     => $tax_category['Standard']['code'],
                        'name'                     => $tax_category['Standard']['name'],
                        'rate'                     => $tax_category['Standard']['rate'],
                        'reason_tax_code_selected' => 'delivery to SVK',
                        'operations'               => ''

                    );
                } elseif (in_array(
                    $this->data['Order Invoice Address Country 2 Alpha Code'], array(
                                                                                 'SK',
                                                                                 'XX'
                                                                             )
                )) {

                    return array(
                        'code'                     => $tax_category['Standard']['code'],
                        'name'                     => $tax_category['Standard']['name'],
                        'rate'                     => $tax_category['Standard']['rate'],
                        'reason_tax_code_selected' => 'billing to SVK',
                        'operations'               => ''
                    );
                } elseif (in_array(
                    $this->data['Order Invoice Address Country 2 Alpha Code'], get_countries_EC_Fiscal_VAT_area($this->db)
                )) {


                    if ($this->data['Order Tax Number Valid'] == 'Yes') {


                        $response = array(
                            'code'                     => $tax_category['EU_VTC']['code'],
                            'name'                     => _('EC with valid tax number').'<span>'.$this->data['Order Tax Number'].'</span>',
                            'rate'                     => $tax_category['EU_VTC']['rate'],
                            'reason_tax_code_selected' => 'EC with valid tax number',
                            'operations'               => ''

                        );

                    } else {

                        if ($this->data['Order Tax Number'] == '') {


                            $response = array(
                                'code'                     => $tax_category['Standard']['code'],
                                'name'                     => $tax_category['Standard']['name'],
                                'rate'                     => $tax_category['Standard']['rate'],
                                'reason_tax_code_selected' => 'EC no tax number',
                                'operations'               => '<div><img  style="width:12px;position:relative:bottom:2px" src="/art/icons/information.png"/><span style="font-size:90%"> '._(
                                        'VAT might be exempt with a valid tax number'
                                    ).'</span> <div class="buttons small"><button id="set_tax_number" style="margin:0px" onClick="show_set_tax_number_dialog()">'._('Set up tax number').'</button></div></div>'

                            );

                        } else {


                            $response = array(
                                'code'                     => $tax_category['Standard']['code'],
                                'name'                     => $tax_category['Standard']['name'],
                                'rate'                     => $tax_category['Standard']['rate'],
                                'reason_tax_code_selected' => 'EC with invalid tax number',

                                'operations' => '<div>
					<img style="width:12px;position:relative;bottom:-1px" src="/art/icons/error.png">
					<span style="font-size:90%;"  >'._('Invalid tax number').'</span>
					<img style="cursor:pointer;position:relative;top:4px"  onClick="check_tax_number_from_tax_info()"  id="check_tax_number" src="/art/validate.png" alt="('._('Validate').')" title="'._('Validate').'">
					<br/>
					<img id="set_tax_number" style="width:14px;cursor:pointer;position:relative;top:2px" src="/art/icons/edit.gif"  onClick="show_set_tax_number_dialog()" title="'._('Edit tax number').'"/>

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
                            'code'                     => $tax_category['Standard']['code'],
                            'name'                     => $tax_category['Standard']['name'],
                            'rate'                     => $tax_category['Standard']['rate'],
                            'reason_tax_code_selected' => 'delivery to EC with no EC billing',
                            'operations'               => ''

                        );

                    } else {
                        return array(
                            'code'                     => $tax_category['Outside']['code'],
                            'name'                     => $tax_category['Outside']['name'],
                            'rate'                     => $tax_category['Outside']['rate'],
                            'reason_tax_code_selected' => 'outside EC',
                            'operations'               => '<div>'._('Outside EC fiscal area').'</div>'

                        );

                    }

                }


                break;
        }


    }

}

