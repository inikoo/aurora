<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 August 2017 at 08:43:35 CEST, Tranava, Slovakia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/

use Aurora\Interfaces\TaxCategory\TaxCategoryProviderFactory;
use Aurora\Models\Utils\TaxCategory;

trait OrderTax
{


    /**
     * @throws Exception
     */
    function update_tax_number($value, $options = '', $updated_from_invoice = false)
    {
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
                    'render'          => !($this->get('Order Tax Number') == ''),
                    'value'           => $this->get('Order Tax Number Valid'),
                    'formatted_value' => $this->get('Tax Number Valid'),


                )
            );
        }
    }

    /**
     * @throws Exception
     */
    function validate_order_tax_number()
    {
        if (!empty($this->skip_validate_tax_number)) {
            return;
        }


        if ($this->get('State Index') <= 0) {
            return;
        }

        if ($this->data['Order Tax Number'] == '') {
            $this->fast_update(array(
                                   'Order Tax Number Valid'              => 'Unknown',
                                   'Order Tax Number Details Match'      => '',
                                   'Order Tax Number Validation Date'    => '',
                                   'Order Tax Number Validation Source'  => '',
                                   'Order Tax Number Validation Message' => ''
                               ));
        } else {
            include_once 'utils/validate_tax_number.php';
            $tax_validation_data = validate_tax_number($this->data['Order Tax Number'], $this->data['Order Invoice Address Country 2 Alpha Code']);

            if ($tax_validation_data['Tax Number Valid'] == 'API_Down') {
                if (!($this->data['Order Tax Number Validation Source'] == '' and $this->data['Order Tax Number Valid'] == 'No')) {
                    return;
                }
            }

            $this->fast_update(array(
                                   'Order Tax Number Valid'              => $tax_validation_data['Tax Number Valid'],
                                   'Order Tax Number Details Match'      => $tax_validation_data['Tax Number Details Match'],
                                   'Order Tax Number Validation Date'    => $tax_validation_data['Tax Number Validation Date'],
                                   'Order Tax Number Validation Source'  => $tax_validation_data['Tax Number Validation Source'],
                                   'Order Tax Number Validation Message' => $tax_validation_data['Tax Number Validation Message'],
                               ));
        }

        $this->update_tax();
    }


    /**
     * @throws Exception
     */
    function update_tax_number_valid($value)
    {
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
                ),
                'no_history'
            );
        } else {
            $this->update_field('Order Tax Number Valid', $value);
            $this->update(
                array(
                    'Order Tax Number Details Match'      => 'Unknown',
                    'Order Tax Number Validation Date'    => $this->editor['Date'],
                    'Order Tax Number Validation Source'  => 'Staff',
                    'Order Tax Number Validation Message' => $this->editor['Author Name'],
                ),
                'no_history'
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


    /**
     * @throws Exception
     */
    function update_tax($tax_category_key = false, $update_from_invoice_key = false)
    {
        $account = get_object('Account', 1);
        $account->load_properties();

        if ($account->properties('tax_per_item')) {
            $this->update_tax_per_item($tax_category_key, $update_from_invoice_key);
        } else {
            $this->update_tax_all_order_same($tax_category_key, $update_from_invoice_key);
        }
    }


    function get_tax_category(): TaxCategory
    {
        $address = new \Aurora\Utilities\Address();

        $store = get_object('Store', $this->data['Order Store Key']);

        $provider = TaxCategoryProviderFactory::createProvider($this->db, $store->settings('tax_authority'), ['RE' => ($this->metadata('RE') == 'Yes'), 'base_country' => $store->settings('tax_country_code')]);


        return $provider->getTaxCategory(
            $address->setCountryCode($this->data['Order Invoice Address Country 2 Alpha Code'])->setPostalCode($this->data['Order Invoice Address Postal Code']),
            $address->setCountryCode($this->data['Order Delivery Address Country 2 Alpha Code'])->setPostalCode($this->data['Order Delivery Address Postal Code']),
            $this->getTaxNumber('Order')
        );
    }


    /**
     * @throws Exception
     */
    function update_tax_per_item($tax_category_key, $update_from_invoice_key)
    {
        // $account_has_re_tax = false;
        // if ($account->properties('has_re_tax')) {
        //     $account_has_re_tax = true;
        // }
        // $order_has_re = false;

        $edit_otf = $this->prepare_update_tax($update_from_invoice_key);


        if ($tax_category_key) {
            $tax_category = new TaxCategory($this->db);
            $tax_category->loadWithKey($tax_category_key);

            if (!$tax_category->id) {
                $this->msg   = 'Invalid tax code';
                $this->error = true;

                return;
            }
        } else {
            $tax_category = $this->get_tax_category();
        }
        $products_tax_categories     = [];
        $products_with_different_tax = [];
        if ($tax_category->get('Tax Category Rate') > 0) {
            if ($update_from_invoice_key > 0) {
                $sql  = "select `Product Tax Category Data`,`Order Transaction Fact Key`
                                from `Order Transaction Fact` OTF left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`)
                                where `Order Key`=? AND `Invoice Key`=? and `Product Tax Category Data` is not null  ";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(array(
                                   $this->id,
                                   $update_from_invoice_key
                               ));
            } else {
                $sql  = "select `Product Tax Category Data`,`Order Transaction Fact Key`
                        from `Order Transaction Fact` OTF left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`) 
                        where `Order Key`=? and `Product Tax Category Data` is not null ";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(array(
                                   $this->id
                               ));
            }


            while ($row = $stmt->fetch()) {
                if ($row['Product Tax Category Data'] != '{}') {
                    $product_tax_category_data = json_decode($row['Product Tax Category Data'], true);


                    if (isset($product_tax_category_data[$tax_category->id])) {
                        if (!isset($products_tax_categories[$product_tax_category_data[$tax_category->id]])) {
                            $_tax_category = new TaxCategory($this->db);
                            $_tax_category->loadWithKey($product_tax_category_data[$tax_category->id]);
                            $products_tax_categories[$product_tax_category_data[$tax_category->id]] = $_tax_category;
                        }

                        $products_with_different_tax[$row['Order Transaction Fact Key']] = $product_tax_category_data[$tax_category->id];
                    }
                }
            }
        }


        if ($edit_otf) {
            if ($update_from_invoice_key > 0) {
                $sql = "UPDATE `Order Transaction Fact` SET `Order Transaction Tax Category Key`=?,`Transaction Tax Rate`=?,`Transaction Tax Code`=? WHERE `Order Key`=? AND `Invoice Key`=?  ";
                $this->db->prepare($sql)->execute(array(
                                                      $tax_category->id,
                                                      $tax_category->get('Tax Category Rate'),
                                                      $tax_category->get('Tax Category Code'),
                                                      $this->id,
                                                      $update_from_invoice_key
                                                  ));
            } else {
                $sql = "UPDATE `Order Transaction Fact` SET `Order Transaction Tax Category Key`=?,`Transaction Tax Rate`=?,`Transaction Tax Code`=? WHERE `Order Key`=?";

                $this->db->prepare($sql)->execute(array(
                                                      $tax_category->id,
                                                      $tax_category->get('Tax Category Rate'),
                                                      $tax_category->get('Tax Category Code'),
                                                      $this->id
                                                  ));
            }
        }


        foreach ($products_with_different_tax as $key => $product_with_different_tax_category_key) {
            $sql = "UPDATE `Order Transaction Fact` SET  `Order Transaction Tax Category Key`=?,`Transaction Tax Rate`=?,`Transaction Tax Code`=? WHERE `Order Transaction Fact Key`=?";

            $this->db->prepare($sql)->execute(array(
                                                  $products_tax_categories[$product_with_different_tax_category_key]->id,
                                                  $products_tax_categories[$product_with_different_tax_category_key]->get('Tax Category Rate'),
                                                  $products_tax_categories[$product_with_different_tax_category_key]->get('Tax Category Code'),
                                                  $key
                                              ));
        }


        if ($update_from_invoice_key > 0) {
            $sql = sprintf(
                "SELECT `Tax Category Code`,`Transaction Type`,`Order No Product Transaction Fact Key`,`Transaction Net Amount` FROM `Order No Product Transaction Fact`  WHERE `Order Key`=%d AND `Invoice Key`=%d  ",
                $this->id,
                $update_from_invoice_key
            );
        } else {
            $sql = sprintf(
                "SELECT `Tax Category Code`,`Transaction Type`,`Order No Product Transaction Fact Key`,`Transaction Net Amount` FROM `Order No Product Transaction Fact`  WHERE `Order Key`=%d AND `Consolidated`='No'",
                $this->id
            );
        }

        if ($edit_otf) {
            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $sql = "UPDATE `Order No Product Transaction Fact` SET `Order No Product Transaction Tax Category Key`=?,`Transaction Tax Amount`=?,`Tax Category Code`=? WHERE `Order No Product Transaction Fact Key`=?";


                    $this->db->prepare($sql)->execute([
                                                          $tax_category->id,
                                                          $row['Transaction Net Amount'] * $tax_category->get('Tax Category Rate'),
                                                          $tax_category->get('Tax Category Code'),
                                                          $row['Order No Product Transaction Fact Key']
                                                      ]);
                }
            }
        }

        $this->fast_update(array(
                               'Order Tax Code'         => $tax_category->get('Tax Category Code'),
                               'Order Tax Rate'         => $tax_category->get('Tax Category Rate'),
                               'Order Tax Category Key' => $tax_category->id
                           ));


        $this->fast_update_json_field('Order Metadata', 'tax_name', $tax_category->get('Tax Category Name'));
        //$this->fast_update_json_field('Order Metadata', 'why_tax', $reason_tax_code_selected);

        if ($edit_otf) {
            $this->update_totals();
        } else {
            $this->fast_update_json_field('Order Metadata', 'post_invoice_tax_code', $tax_category->get('Tax Category Code'));
        }
    }


    /**
     * @throws Exception
     */
    function update_tax_all_order_same($tax_category_key = false, $update_from_invoice_key = false)
    {
        $edit_otf = $this->prepare_update_tax($update_from_invoice_key);

        if ($tax_category_key) {
            $tax_category = new TaxCategory($this->db);
            $tax_category->loadWithKey($tax_category_key);


            if (!$tax_category->id) {
                $this->msg   = 'Invalid tax code';
                $this->error = true;

                return;
            }
            //                $reason_tax_code_selected = 'set';


        } else {
            $address = new \Aurora\Utilities\Address();

            $store = get_object('Store', $this->data['Order Store Key']);

            $provider = TaxCategoryProviderFactory::createProvider($this->db, $store->settings('tax_authority'), ['RE' => ($this->metadata('RE') == 'Yes'), 'base_country' => $store->settings('tax_country_code')]);

            $tax_category = $provider->getTaxCategory(
                $address->setCountryCode($this->data['Order Invoice Address Country 2 Alpha Code'])->setPostalCode($this->data['Order Invoice Address Postal Code']),
                $address->setCountryCode($this->data['Order Delivery Address Country 2 Alpha Code'])->setPostalCode($this->data['Order Delivery Address Postal Code']),
                $this->getTaxNumber('Order')
            );


            //$tax_data = $this->get_tax_data();
            //$new_tax_code = $tax_data['code'];
            //$tax_rate     = $tax_data['rate'];
            //$tax_name                 = $tax_data['name'];
            //$reason_tax_code_selected = $tax_data['reason_tax_code_selected'];


        }

        if ($edit_otf) {
            if ($update_from_invoice_key > 0) {
                $sql = "UPDATE `Order Transaction Fact` SET `Transaction Tax Rate`=?,`Transaction Tax Code`=?  ,`Order Transaction Tax Category Key`=?  WHERE `Order Key`=? AND `Invoice Key`=? ";
                $this->db->prepare($sql)->execute(array(
                                                      $tax_category->get('Tax Category Rate'),
                                                      $tax_category->get('Tax Category Code'),
                                                      $tax_category->id,
                                                      $this->id,
                                                      $update_from_invoice_key
                                                  ));

                $sql = "SELECT `Tax Category Code`,`Transaction Type`,`Order No Product Transaction Fact Key`,`Transaction Net Amount` FROM `Order No Product Transaction Fact`  WHERE `Order Key`=? AND `Invoice Key`=?  ";


                $stmt = $this->db->prepare($sql);
                $stmt->execute(array(
                                   $this->id,
                                   $update_from_invoice_key
                               ));
            } else {
                $sql = "UPDATE `Order Transaction Fact` SET `Transaction Tax Rate`=?,`Transaction Tax Code`=?  ,`Order Transaction Tax Category Key`=?  WHERE `Order Key`=? AND `Consolidated`='No' ";
                $this->db->prepare($sql)->execute(array(
                                                      $tax_category->get('Tax Category Rate'),
                                                      $tax_category->get('Tax Category Code'),
                                                      $tax_category->id,
                                                      $this->id,
                                                  ));

                $sql  = "SELECT `Tax Category Code`,`Transaction Type`,`Order No Product Transaction Fact Key`,`Transaction Net Amount` FROM `Order No Product Transaction Fact`  WHERE `Order Key`=? AND `Consolidated`='No'  ";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(array(
                                   $this->id
                               ));
            }
            while ($row = $stmt->fetch()) {
                $sql = "UPDATE `Order No Product Transaction Fact` SET `Transaction Tax Amount`=?,`Tax Category Code`=? ,`Order No Product Transaction Tax Category Key`=? WHERE `Order No Product Transaction Fact Key`=?";
                $this->db->prepare($sql)->execute(array(
                                                      round($row['Transaction Net Amount'] * $tax_category->get('Tax Category Rate'), 2),
                                                      $tax_category->get('Tax Category Code'),
                                                      $tax_category->id,
                                                      $row['Order No Product Transaction Fact Key']
                                                  ));
            }
        }


        $this->fast_update(array(
                               'Order Tax Code'         => $tax_category->get('Tax Category Code'),
                               'Order Tax Rate'         => $tax_category->get('Tax Category Rate'),
                               'Order Tax Category Key' => $tax_category->id
                           ));


        $this->fast_update_json_field('Order Metadata', 'tax_name', $tax_category->get('Tax Category Name'));
        // $this->fast_update_json_field('Order Metadata', 'why_tax', $reason_tax_code_selected);

        if ($edit_otf) {
            $this->update_totals();
        } else {
            $this->fast_update_json_field('Order Metadata', 'post_invoice_tax_code', $tax_category->get('Tax Category Code'));
        }
    }

    /**
     * @param $update_from_invoice_key
     * @return bool
     */
    private function prepare_update_tax($update_from_invoice_key): bool
    {
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
        return $edit_otf;
    }


}

