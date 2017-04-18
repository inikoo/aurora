<?php
/*
 File: Part.php

 This file contains the Part Class

 About:
 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

include_once 'class.Asset.php';

class Part extends Asset {


    public $sku = false;
    public $warehouse_key = 1;
    public $locale = 'en_GB';

    function __construct($arg1, $arg2 = false, $arg3 = false, $_db = false) {


        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }

        $this->table_name    = 'Part';
        $this->ignore_fields = array(
            'Part Key'
        );

        if (is_numeric($arg1) and !$arg2) {
            $this->get_data('id', $arg1);

            return;
        }


        if (preg_match('/^find/i', $arg1)) {

            $this->find($arg2, $arg3);

            return;
        }

        if (preg_match('/^create/i', $arg1)) {
            $this->create($arg2);

            return;
        }


        $this->get_data($arg1, $arg2);

    }


    function get_data($tipo, $tag) {
        if ($tipo == 'id' or $tipo == 'sku') {
            $sql = sprintf(
                "SELECT * FROM `Part Dimension` WHERE `Part SKU`=%d ", $tag
            );
        } else {
            if ($tipo == 'code' or $tipo == 'reference') {
                $sql = sprintf(
                    "SELECT * FROM `Part Dimension` WHERE `Part Reference`=%s ", prepare_mysql($tag)
                );
            } else {
                return;
            }
        }

        // print $sql;

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id  = $this->data['Part SKU'];
            $this->sku = $this->data['Part SKU'];
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


        $create = '';
        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = _trim($value);
            }
        }


        $sql = sprintf(
            "SELECT `Part SKU` FROM `Part Dimension` WHERE `Part Reference`=%s", prepare_mysql($data['Part Reference'])
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->found     = true;
                $this->found_key = $row['Part SKU'];
                $this->get_data('id', $this->found_key);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if ($create and !$this->found) {


            $this->create($raw_data);

        }


    }

    function create($data) {

        include_once 'class.Account.php';
        include_once 'class.Category.php';

        // print_r($data);
        $account = new Account($this->db);

        if (array_key_exists('Part Family Category Code', $data)) {

            $root_category = new Category(
                $account->get('Account Part Family Category Key')
            );
            if ($root_category->id) {
                $root_category->editor = $this->editor;
                $family                = $root_category->create_category(
                    array('Category Code' => $data['Part Family Category Code'])
                );
                if ($family->id) {
                    $data['Part Family Category Key'] = $family->id;
                }
            }
        }

        if (!isset($data['Part Valid From']) or $data['Part Valid From'] == '') {
            $data['Part Valid From'] = gmdate('Y-m-d H:i:s');
        }
        $base_data = $this->base_data();
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $base_data)) {
                $base_data[$key] = _trim($value);
            }
        }


        //   $base_data['Part Available']='No';

        //  if ($base_data['Part XHTML Description']=='') {
        //   $base_data['Part XHTML Description']=strip_tags($base_data['Part XHTML Description']);
        //  }

        //print_r($base_data);

        $keys   = '(';
        $values = 'values(';
        foreach ($base_data as $key => $value) {
            $keys .= "`$key`,";

            if (in_array(
                $key, array(
                        'Part XHTML Next Supplier Shipment',
                        'Part XHTML Picking Location'
                    )
            )) {
                $values .= prepare_mysql($value, false).",";

            } else {

                $values .= prepare_mysql($value).",";
            }
        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);

        //print_r($base_data);

        $sql = sprintf("INSERT INTO `Part Dimension` %s %s", $keys, $values);


        if ($this->db->exec($sql)) {
            $this->id  = $this->db->lastInsertId();
            $this->sku = $this->id;
            $this->new = true;

            $sql = "INSERT INTO `Part Data` (`Part SKU`) VALUES(".$this->id.");";
            $this->db->exec($sql);


            $this->get_data('id', $this->id);

            $this->update_products_web_status();

            $history_data = array(
                'Action'           => 'created',
                'History Abstract' => _('Part created'),
                'History Details'  => ''
            );
            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );


            //  print 'x'.$this->get('Part Family Category Key')."\n";

            if ($this->get('Part Family Category Key')) {
                $family         = new Category(
                    $this->get('Part Family Category Key')
                );
                $family->editor = $this->editor;


                if ($family->id) {
                    $family->associate_subject($this->id);
                }
            }


        } else {
            print "Error Part can not be created $sql\n";
            $this->msg = 'Error Part can not be created';
            exit;
        }

    }

    function update_products_web_status() {

        $products            = 0;
        $products_web_status = '';
        //'Offline','No Products','Online','Out of Stock'

        foreach ($this->get_products('objects') as $product) {
            if (!($product->get('Product Status') == 'Discontinued' or $product->get('Product Web State') == 'Discontinued')) {

                //'For Sale','Out of Stock','Discontinued','Offline'

                if ($product->get('Product Web State') == 'For Sale') {
                    $products_web_status = 'Online';
                    break;
                } elseif ($product->get('Product Web State') == 'Out of Stock') {
                    $products_web_status = 'Out of Stock';
                } elseif ($product->get('Product Web State') == 'Offline') {

                    if ($products_web_status == '') {
                        $products_web_status = 'Offline';
                    }

                }


                $products++;
            }
        }

        if ($products_web_status == '') {
            $products_web_status = 'No Products';
        }

        //print $this->get('Reference').' '.$products_web_status."\n";

        $this->update(
            array(
                'Part Products Web Status' => $products_web_status
            ), 'no_history'
        );


    }

    function get_products($scope = 'keys') {


        if ($scope == 'objects') {
            include_once 'class.Product.php';
        }

        $sql = sprintf(
            'SELECT `Product Part Product ID` FROM `Product Part Bridge` WHERE `Product Part Part SKU`=%d ', $this->id
        );

        $products = array();

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($scope == 'objects') {
                    $products[$row['Product Part Product ID']] = new Product(
                        $row['Product Part Product ID']
                    );
                } else {
                    $products[$row['Product Part Product ID']] = $row['Product Part Product ID'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        return $products;
    }

    function get($key = '', $args = false) {

        $account = new Account($this->db);

        list($got, $result) = $this->get_asset_common($key, $args);
        if ($got) {
            return $result;
        }

        if (!$this->id) {
            return;
        }


        switch ($key) {

            case 'Products Numbers':

                return number($this->data['Part Number Active Products']).",<span class=' very_discreet'>".number($this->data['Part Number No Active Products']).'</span>';

                break;

            case 'Stock Status Icon':

                if ($this->data['Part Status'] == 'In Process') {
                    return '';
                }

                switch ($this->data[$this->table_name.' Stock Status']) {
                    case 'Surplus':
                        $stock_status = '<i class="fa  fa-plus-circle fa-fw" aria-hidden="true" title="'._('Surplus stock').'"></i>';
                        break;
                    case 'Optimal':
                        $stock_status = '<i class="fa fa-check-circle fa-fw" aria-hidden="true" title="'._('Optimal stock').'"></i>';
                        break;
                    case 'Low':
                        $stock_status = '<i class="fa fa-minus-circle fa-fw" aria-hidden="true" title="'._('Low stock').'"></i>';
                        break;
                    case 'Critical':
                        $stock_status = '<i class="fa error fa-minus-circle fa-fw" aria-hidden="true"  title="'._('Critical stock').'"></i>';
                        break;
                    case 'Out_Of_Stock':
                        $stock_status = '<i class="fa error fa-ban fa-fw" aria-hidden="true"  title="'._('Out of stock').'"></i>';
                        break;
                    case 'Error':
                        $stock_status = '<i class="fa fa-question-circle fa-fw" aria-hidden="true"  title="'._('Error').'"></i>';
                        break;
                    default:
                        $stock_status = $this->data[$this->table_name.' Stock Status'];
                        break;
                }

                return $stock_status;
                break;
            case 'Part Family Category Code':

                if ($this->data['Part Family Category Key'] == '') {
                    return '';
                }

                include_once 'class.Category.php';

                $category = new Category(
                    $this->data['Part Family Category Key']
                );

                if ($category->id) {
                    return $category->get('Code');
                } else {
                    return '';
                }


                break;
            case 'Products Web Status':

                if ($this->data['Part Status'] == 'Not In Use') {

                    if ($this->data['Part Products Web Status'] == 'Online') {
                        return '<i class="fa fa-exclamation-circle error" aria-hidden="true"></i> '._('Online');
                    } elseif ($this->data['Part Products Web Status'] == 'Out of Stock') {
                        return '<i class="fa fa-exclamation-circle warning" aria-hidden="true"></i> '._('Out of stock');
                    }


                } else {


                    if ($this->data['Part Products Web Status'] == 'Offline') {
                        return '<span class="warning"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> '._('Offline').'</span>';
                    } elseif ($this->data['Part Products Web Status'] == 'No Products') {
                        return _('No products associated');
                    } elseif ($this->data['Part Products Web Status'] == 'Online') {

                        if ($this->data['Part Stock Status'] == 'Out_Of_Stock' or $this->data['Part Stock Status'] == 'Error') {
                            return '<span class="error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> '._('Online').'</span>';

                        } else {

                            return _('Online');
                        }
                    } elseif ($this->data['Part Products Web Status'] == 'Out of Stock') {
                        return _('Out of stock');
                    } else {
                        return $this->data['Part Products Web Status'];
                    }
                }

                break;
            case 'Status':
                if ($this->data['Part Status'] == 'In Use') {
                    return _('Active');
                } elseif ($this->data['Part Status'] == 'Discontinuing') {
                    return _('Discontinuing');
                } elseif ($this->data['Part Status'] == 'Not In Use') {
                    return _('Discontinued');
                } elseif ($this->data['Part Status'] == 'In Process') {
                    return _('In process');
                } else {
                    return $this->data['Part Status'];
                }
                break;
            case 'Cost in Warehouse':
                if ($this->data['Part Unit Price'] == '') {
                    return _('Cost price not set up');
                }
                include_once 'utils/natural_language.php';


                $sko_cost = sprintf('<span title="%s">%s/SKO</span>', _('SKO stock value'), money($this->data['Part Cost in Warehouse'], $account->get('Account Currency')));


                $total_value = $this->data['Part Cost in Warehouse'] * $this->get('Part Current On Hand Stock');

                if ($total_value > 0) {
                    $total_value = sprintf('<span title="%s">%s</span>', _('Total stock value'), money($total_value, $account->get('Account Currency')));
                } else {
                    $total_value = '';
                }


                return $sko_cost.' <span class="discreet" style="margin-left:10px">'.$total_value.'</span>';
                break;
            case 'SKO Cost in Warehouse - Price':
                if ($this->data['Part Unit Price'] == '') {
                    return _('Cost price not set up');
                }
                include_once 'utils/natural_language.php';


                $sko_cost = sprintf(
                    _('SKO stock value %s'), money($this->data['Part Cost in Warehouse'], $account->get('Account Currency'))

                );


                $sko_recomended_price = sprintf(
                    _('suggested SKO price: %s'), money($this->data['Part Unit Price'] * $this->data['Part Units Per Package'], $account->get('Account Currency'))

                );


                if ($this->data['Part Units Per Package'] != 0 and is_numeric(
                        $this->data['Part Units Per Package']
                    )
                ) {

                    $unit_margin = $this->data['Part Unit Price'] - ($this->data['Part Cost in Warehouse'] / $this->data['Part Units Per Package']);

                    $sko_recomended_price .= sprintf(
                        ' (<span class="'.($unit_margin < 0 ? 'error' : '').'">%s '._('margin').'</span>)', percentage($unit_margin, $this->data['Part Unit Price'])
                    );
                }


                return $sko_cost.' <span class="discreet" style="margin-left:10px">'.$sko_recomended_price.'</span>';
                break;


            case 'Unit Price':
                if ($this->data['Part Unit Price'] == '') {
                    return '';
                }
                include_once 'utils/natural_language.php';
                $unit_price = money(
                    $this->data['Part Unit Price'], $account->get('Account Currency')
                );

                $price_other_info = '';
                if ($this->data['Part Units Per Package'] != 1 and is_numeric(
                        $this->data['Part Units Per Package']
                    )
                ) {
                    $price_other_info = '('.money(
                            $this->data['Part Unit Price'] * $this->data['Part Units Per Package'], $account->get('Account Currency')
                        ).' '._('per SKO').'), ';
                }


                if ($this->data['Part Units Per Package'] != 0 and is_numeric(
                        $this->data['Part Units Per Package']
                    )
                ) {

                    $unit_margin = $this->data['Part Unit Price'] - ($this->data['Part Cost'] / $this->data['Part Units Per Package']);

                    $price_other_info .= sprintf(
                        '<span class="'.($unit_margin < 0 ? 'error' : '').'">'._('margin %s').'</span>', percentage($unit_margin, $this->data['Part Unit Price'])
                    );
                }

                $price_other_info = preg_replace(
                    '/^, /', '', $price_other_info
                );
                if ($price_other_info != '') {
                    $unit_price .= ' <span class="discreet">'.$price_other_info.'</span>';
                }

                return $unit_price;
                break;
            case 'Unit RRP':
                if ($this->data['Part Unit RRP'] == '') {
                    return '';
                }

                include_once 'utils/natural_language.php';
                $rrp = money(
                    $this->data['Part Unit RRP'], $account->get('Account Currency')
                );


                $unit_margin    = $this->data['Part Unit RRP'] - $this->data['Part Unit Price'];
                $rrp_other_info = sprintf(
                    _('margin %s'), percentage($unit_margin, $this->data['Part Unit RRP'])
                );


                $rrp_other_info = preg_replace('/^, /', '', $rrp_other_info);
                if ($rrp_other_info != '') {
                    $rrp .= ' <span class="'.($unit_margin < 0 ? 'error' : '').'  discreet">'.$rrp_other_info.'</span>';
                }

                return $rrp;
                break;
            case 'Barcode':

                if ($this->get('Part Barcode Number') == '') {
                    return '';
                }


                return '<i '.($this->get('Part Barcode Key') ? 'class="fa fa-barcode button" onClick="change_view(\'inventory/barcode/'.$this->get('Part Barcode Key').'\')"' : 'class="fa fa-barcode"')
                    .' ></i><span class="Part_Barcode_Number ">'.$this->get(
                        'Part Barcode Number'
                    ).'</span>';

                break;

            case 'Available Forecast':

                $available_forecast = '';

                if ($this->data['Part Stock Status'] == 'Out_Of_Stock' or $this->data['Part Stock Status'] == 'Error') {
                    return '';
                }

                if (in_array(
                    $this->data['Part Products Web Status'], array(
                                                               'No Products',
                                                               'Offline',
                                                               'Out of Stock'
                                                           )
                )) {
                    return '';
                }


                include_once 'utils/natural_language.php';

                if ($this->data['Part On Demand'] == 'Yes') {

                    $available_forecast = '<span >'.sprintf(
                            _('%s in stock'), '<span  title="'.sprintf(
                                                "%s %s", number(
                                                $this->data['Part Days Available Forecast'], 1
                                            ), ngettext(
                                                    "day", "days", intval(
                                                             $this->data['Part Days Available Forecast']
                                                         )
                                                )
                                            ).'">'.seconds_to_until(
                                                $this->data['Part Days Available Forecast'] * 86400
                                            ).'</span>'
                        ).'</span>';

                    if ($this->data['Part Fresh'] == 'No') {
                        $available_forecast .= ' <i class="fa fa-fighter-jet padding_left_5" aria-hidden="true" title="'._('On demand').'"></i>';
                    } else {
                        $available_forecast = ' <i class="fa fa-lemon-o padding_left_5" aria-hidden="true" title="'._('On demand').'"></i>';
                    }
                } else {
                    $available_forecast = '<span >'.sprintf(
                            _('%s availability'), '<span  title="'.sprintf(
                                                    "%s %s", number(
                                                    $this->data['Part Days Available Forecast'], 1
                                                ), ngettext(
                                                        "day", "days", intval(
                                                                 $this->data['Part Days Available Forecast']
                                                             )
                                                    )
                                                ).'">'.seconds_to_until(
                                                    $this->data['Part Days Available Forecast'] * 86400
                                                ).'</span>'
                        ).'</span>';


                }


                return $available_forecast;
                break;

            case 'Origin Country Code':
                if ($this->data['Part Origin Country Code']) {
                    include_once 'class.Country.php';
                    $country = new Country(
                        'code', $this->data['Part Origin Country Code']
                    );

                    return '<img src="/art/flags/'.strtolower(
                            $country->get('Country 2 Alpha Code')
                        ).'.gif" title="'.$country->get('Country Code').'"> '._(
                            $country->get('Country Name')
                        );
                } else {
                    return '';
                }

                break;
            case 'Origin Country':
                if ($this->data['Part Origin Country Code']) {
                    include_once 'class.Country.php';
                    $country = new Country(
                        'code', $this->data['Part Origin Country Code']
                    );

                    return $country->get('Country Name');
                } else {
                    return '';
                }

                break;


            case 'Next Supplier Shipment':
                if ($this->data['Part Next Supplier Shipment'] == '') {
                    return '';
                } else {
                    return strftime(
                        "%a, %e %b %y", strtotime(
                                          $this->data['Part Next Supplier Shipment'].' +0:00'
                                      )
                    );
                }
                break;

            case('Current Stock Available'):

                return number(
                    $this->data['Part Current On Hand Stock'] - $this->data['Part Current Stock In Process'] - $this->data['Part Current Stock Ordered Paid'], 6
                );

            case('Cost'):
                global $corporate_currency;

                return money(
                    $this->data['Part Current Stock Cost Per Unit'], $corporate_currency
                );


                break;

            case('Current On Hand Stock'):
            case('Current Stock'):
            case ('Current Stock Picked'):
            case ('Current Stock In Process'):
            case ('Current Stock Ordered Paid'):
                return number($this->data['Part '.$key], 6);


                break;


            case('Valid From'):
            case('Valid From Datetime'):

                return strftime(
                    "%a %e %b %Y %H:%M %Z", strtotime($this->data['Part Valid From'].' +0:00')
                );
                break;
            case('Valid To'):
                return strftime(
                    "%a %e %b %Y %H:%M %Z", strtotime($this->data['Part Valid To'].' +0:00')
                );
                break;
            case 'Package Description Image':

                $image = '';

                $sql = sprintf(
                    'SELECT `Image Subject Image Key`  FROM `Image Subject Bridge` WHERE `Image Subject Object` = "Part" AND `Image Subject Object Key` =%d AND `Image Subject Object Image Scope`="SKO"  ',
                    $this->id
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $image .= sprintf('<img src="/image_root.php?id=%d&size=thumbnail"> ', $row['Image Subject Image Key']);
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                return $image;

                break;

            case 'Acc To Day Updated':
            case 'Acc Ongoing Intervals Updated':
            case 'Acc Previous Intervals Updated':

                if ($this->data['Part '.$key] == '') {
                    $value = '';
                } else {

                    $value = strftime("%a %e %b %Y %H:%M:%S %Z", strtotime($this->data['Part '.$key].' +0:00'));

                }

                return $value;
                break;


            default:

                if (preg_match('/No Supplied$/', $key)) {

                    $_key = preg_replace('/ No Supplied$/', '', $key);
                    if (preg_match('/^Part /', $key)) {
                        return $this->data["$_key Required"] - $this->data["$_key Provided"];

                    } else {
                        return number(
                            $this->data["Part $_key Required"] - $this->data["Part $_key Provided"]
                        );
                    }

                }


                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit)$/', $key
                )) {

                    $amount = 'Part '.$key;

                    return money(
                        $this->data[$amount], $account->get('Account Currency')
                    );
                }
                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Minify$/', $key
                )) {

                    $field = 'Part '.preg_replace('/ Minify$/', '', $key);

                    $suffix          = '';
                    $fraction_digits = 'NO_FRACTION_DIGITS';
                    if ($this->data[$field] >= 10000) {
                        $suffix  = 'K';
                        $_amount = $this->data[$field] / 1000;
                    } elseif ($this->data[$field] > 100) {
                        $fraction_digits = 'SINGLE_FRACTION_DIGITS';
                        $suffix          = 'K';
                        $_amount         = $this->data[$field] / 1000;
                    } else {
                        $_amount = $this->data[$field];
                    }

                    $amount = money(
                            $_amount, $account->get('Account Currency'), $locale = false, $fraction_digits
                        ).$suffix;

                    return $amount;
                }
                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Soft Minify$/', $key
                )) {

                    $field = 'Part '.preg_replace('/ Soft Minify$/', '', $key);


                    $suffix          = '';
                    $fraction_digits = 'NO_FRACTION_DIGITS';
                    $_amount         = $this->data[$field];

                    $amount = money(
                            $_amount, $account->get('Account Currency'), $locale = false, $fraction_digits
                        ).$suffix;

                    return $amount;
                }

                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|Year To|Quarter To|Month To|Today|Week To).*(Margin|GMROI)$/', $key
                )) {

                    $amount = 'Part '.$key;

                    return percentage($this->data[$amount], 1);
                }
                if (preg_match(
                        '/^(Last|Yesterday|Total|1|10|6|3|Year To|Quarter To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Dispatched|Broken|Acquired)$/', $key
                    ) or $key == 'Current Stock'
                ) {

                    $amount = 'Part '.$key;

                    return number($this->data[$amount]);
                }
                if (preg_match(
                        '/^(Last|Yesterday|Total|1|10|6|3|2|4|Year To|Quarter To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Dispatched|Broken|Acquired) Minify$/', $key
                    ) or $key == 'Current Stock'
                ) {

                    $field = 'Part '.preg_replace('/ Minify$/', '', $key);

                    $suffix          = '';
                    $fraction_digits = 0;
                    if ($this->data[$field] >= 10000) {
                        $suffix  = 'K';
                        $_number = $this->data[$field] / 1000;
                    } elseif ($this->data[$field] > 100) {
                        $fraction_digits = 1;
                        $suffix          = 'K';
                        $_number         = $this->data[$field] / 1000;
                    } else {
                        $_number = $this->data[$field];
                    }

                    return number($_number, $fraction_digits).$suffix;
                }
                if (preg_match(
                        '/^(Last|Yesterday|Total|1|10|6|3|2|4|Year To|Quarter To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Dispatched|Broken|Acquired) Soft Minify$/', $key
                    ) or $key == 'Current Stock'
                ) {

                    $field = 'Part '.preg_replace('/ Soft Minify$/', '', $key);

                    $suffix          = '';
                    $fraction_digits = 0;
                    $_number         = $this->data[$field];

                    return number($_number, $fraction_digits).$suffix;
                }


                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Part '.$key, $this->data)) {
                    return $this->data['Part '.$key];
                }

        }

        return false;
    }

    function get_categories($scope = 'keys') {

        if ($scope == 'objects') {
            include_once 'class.Category.php';
        }


        $categories = array();


        $sql = sprintf(
            "SELECT B.`Category Key` FROM `Category Dimension` C LEFT JOIN `Category Bridge` B ON (B.`Category Key`=C.`Category Key`) WHERE `Subject`='Part' AND `Subject Key`=%d AND `Category Branch Type`!='Root'",
            $this->sku
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($scope == 'objects') {
                    $categories[$row['Category Key']] = new Category(
                        $row['Category Key']
                    );
                } else {
                    $categories[$row['Category Key']] = $row['Category Key'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        return $categories;


    }

    function get_production_suppliers($scope = 'keys') {


        if ($scope == 'objects') {
            include_once 'class.Supplier_Production.php';
        }

        $sql = sprintf(
            'SELECT `Supplier Part Supplier Key` FROM `Supplier Part Dimension`LEFT JOIN `Supplier Production Dimension` ON (`Supplier Part Supplier Key`=`Supplier Production Supplier Key`) WHERE `Supplier Production Supplier Key` IS NOT NULL AND `Supplier Part Part SKU`=%d ',
            $this->id
        );

        $suppliers = array();

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($scope == 'objects') {
                    $suppliers[$row['Supplier Part Supplier Key']] = new Supplier_Production(
                        $row['Supplier Part Supplier Key']
                    );
                } else {
                    $suppliers[$row['Supplier Part Supplier Key']] = $row['Supplier Part Supplier Key'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        return $suppliers;
    }

    function update_custom_fields($id, $value) {
        $this->update(array($id => $value));
    }

    function discontinue_trigger() {

        if ($this->get('Part Status') == 'Discontinuing' and ($this->data['Part Current On Hand Stock'] <= 0 and $this->data['Part Current Stock In Process'] == 0)) {
            $this->update_status('Not In Use');

            return;
        }
        if ($this->get('Part Status') == 'Not In Use' and ($this->data['Part Current On Hand Stock'] > 0 or $this->data['Part Current Stock In Process'] > 0)) {
            $this->update_status('Discontinuing');

            return;
        }
        if ($this->get('Part Status') == 'Not In Use' and ($this->data['Part Current On Hand Stock'] < 0)) {


            $this->update_status('Not In Use', '', true);


        }

    }

    function update_status($value, $options = '', $force = false) {


        if ($value == 'Not In Use' and ($this->data['Part Current On Hand Stock'] - $this->data['Part Current Stock In Process']) > 0) {
            $value = 'Discontinuing';
        }


        if ($value == $this->get('Part Status') and !$force) {
            return;
        }

        $this->update_field('Part Status', $value, $options);

        if ($value == 'Discontinuing') {
            $this->discontinue_trigger();

        } elseif ($value == 'Not In Use') {


            foreach (
                $this->get_locations('part_location_object') as $part_location
            ) {
                $part_location->disassociate();
            }

            $this->update_stock();


            $this->update(
                array('Part Valid To' => gmdate("Y-m-d H:i:s")), 'no_history'
            );


            $this->get_data('sku', $this->sku);


        }


        $this->update_stock_status();
        $this->update_available_forecast();


        include_once 'class.Category.php';
        $sql = sprintf(
            "SELECT `Category Key` FROM `Category Bridge` WHERE `Subject`='Part' AND `Subject Key`=%d", $this->sku
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $category = new Category($row['Category Key']);
                $category->update_part_category_status();
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $products = $this->get_products('objects');
        foreach ($products as $product) {

            $product->update_status_from_parts();
        }


    }

    function get_locations($scope = 'keys') {


        if ($scope == 'objects') {
            include_once 'class.Location.php';
        } elseif ($scope == 'part_location_object') {
            include_once 'class.PartLocation.php';
        }

        $sql = sprintf(
            "SELECT PL.`Location Key`,`Location Code`,`Quantity On Hand`,`Location Warehouse Key`,`Location Mainly Used For`,`Part SKU`,`Minimum Quantity`,`Maximum Quantity`,`Moving Quantity`,`Can Pick` FROM `Part Location Dimension` PL LEFT JOIN `Location Dimension` L ON (L.`Location Key`=PL.`Location Key`)  WHERE `Part SKU`=%d",
            $this->sku
        );


        $part_locations = array();


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($scope == 'keys') {
                    $part_locations[$row['Location Key']] = $row['Location Key'];
                } elseif ($scope == 'objects') {
                    $part_locations[$row['Location Key']] = new Location(
                        $row['Location Key']
                    );
                } elseif ($scope == 'part_location_object') {
                    $part_locations[$row['Location Key']] = new  PartLocation(
                        $this->sku.'_'.$row['Location Key']
                    );
                } else {


                    switch ($row['Location Mainly Used For']) {
                        case 'Picking':
                            $used_for = sprintf(
                                '<i class="fa fa-fw fa-shopping-basket" aria-hidden="true" title="%s" ></i>', _('Picking')
                            );
                            break;
                        case 'Storing':
                            $used_for = sprintf(
                                '<i class="fa fa-fw  fa-hdd-o" aria-hidden="true" title="%s"></i>', _('Storing')
                            );
                            break;
                        default:
                            $used_for = sprintf(
                                '<i class="fa fa-fw  fa-map-maker" aria-hidden="true" title="%s"></i>', $row['Location Mainly Used For']
                            );
                    }

                    $part_locations[] = array(
                        'formatted_stock' => number($row['Quantity On Hand'], 3),
                        'stock'           => $row['Quantity On Hand'],
                        'warehouse_key'   => $row['Location Warehouse Key'],

                        'location_key' => $row['Location Key'],
                        'part_sku'     => $row['Part SKU'],

                        'location_code' => $row['Location Code'],


                        'location_used_for_icon' => $used_for,
                        'location_used_for'      => $row['Location Mainly Used For'],
                        'formatted_min_qty'      => ($row['Minimum Quantity'] != '' ? $row['Minimum Quantity'] : '?'),
                        'formatted_max_qty'      => ($row['Maximum Quantity'] != '' ? $row['Maximum Quantity'] : '?'),
                        'formatted_move_qty'     => ($row['Moving Quantity'] != '' ? $row['Moving Quantity'] : '?'),
                        'min_qty'                => $row['Minimum Quantity'],
                        'max_qty'                => $row['Maximum Quantity'],
                        'move_qty'               => $row['Moving Quantity'],

                        'can_pick' => $row['Can Pick'],
                        'label'    => ($row['Can Pick'] == 'Yes' ? _('Picking location') : _('Set as picking location'))


                    );

                }

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $part_locations;
    }

    function update_stock() {


        $picked   = 0;
        $required = 0;


        $sql = sprintf(
            "SELECT sum(`Picked`) AS picked, sum(`Required`) AS required FROM `Inventory Transaction Fact` WHERE `Part SKU`=%d AND `Inventory Transaction Type`='Order In Process'", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $picked   = round($row['picked'], 3);
                $required = round($row['required'], 3);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        //$required+=$this->data['Part Current Stock Ordered Paid'];

        list($stock, $value, $in_process) = $this->get_current_stock();
        //print $stock;

        $this->update(
            array(
                'Part Current Stock'            => $stock + $picked,
                'Part Current Value'            => $value,
                'Part Current Stock In Process' => $required - $picked,
                'Part Current Stock Picked'     => $picked,
                'Part Current On Hand Stock'    => $stock,

            ), 'no_history'
        );


        $this->activate();
        $this->discontinue_trigger();
        $this->update_stock_status();
        $this->update_available_forecast();

        include_once 'utils/new_fork.php';
        global $account;

        $msg = new_housekeeping_fork(
            'au_housekeeping', array(
            'type'     => 'update_part_products_availability',
            'part_sku' => $this->id
        ), $account->get('Account Code')
        );


    }

    function get_current_stock() {
        $stock      = 0;
        $value      = 0;
        $in_process = 0;


        $sql = sprintf(
            "SELECT sum(`Quantity On Hand`) AS stock , sum(`Quantity In Process`) AS in_process , sum(`Stock Value`) AS value FROM `Part Location Dimension` WHERE `Part SKU`=%d ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $stock      = round($row['stock'], 3);
                $in_process = round($row['in_process'], 3);
                $value      = $row['value'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return array(
            $stock,
            $value,
            $in_process
        );

    }

    function activate() {

        if ($this->get('Part Status') == 'In Process') {


            if ($this->get_number_images() > 0 and $this->get(
                    'Part Current On Hand Stock'
                ) > 0
            ) {
                $this->update(
                    array(
                        'Part Status'      => 'In Use',
                        'Part Active From' => gmdate('Y-m-d H:i:s')
                    ), 'no_history'
                );
            }


        }


    }

    function update_stock_status() {

        if ($this->data['Part Current Stock'] < 0) {
            $stock_state = 'Error';
        } elseif ($this->data['Part Current Stock'] == 0) {
            if ($this->data['Part Fresh'] == 'Yes') {
                $stock_state = 'Optimal';
            } else {
                $stock_state = 'Out_of_Stock';
            }
        } elseif ($this->data['Part Days Available Forecast'] <= $this->data['Part Delivery Days']) {

            if ($this->data['Part Fresh'] == 'Yes') {
                $stock_state = 'Surplus';
            } else {
                $stock_state = 'Critical';
            }

        } elseif ($this->data['Part Days Available Forecast'] <= $this->data['Part Delivery Days'] + 7) {

            if ($this->data['Part Fresh'] == 'Yes') {
                $stock_state = 'Surplus';
            } else {
                $stock_state = 'Low';
            }


        } elseif ($this->data['Part Days Available Forecast'] >= $this->data['Part Excess Availability Days Limit']) {

            if ($this->data['Part Fresh'] == 'Yes') {
                $stock_state = 'Surplus';
            } else {
                $stock_state = 'Surplus';
            }


        } else {


            if ($this->data['Part Fresh'] == 'Yes') {
                $stock_state = 'Surplus';
            } else {
                $stock_state = 'Optimal';
            }

        }


        $this->update(
            array(
                'Part Stock Status' => $stock_state
            ), 'no_history'
        );


    }

    function update_available_forecast() {

        $this->load_acc_data();


        // -------------- simple forecast -------------------------

        $sql = sprintf("SELECT `Date` FROM `Inventory Transaction Fact` WHERE `Part SKU`=%d AND `Inventory Transaction Type` LIKE 'Associate' ORDER BY `Date` DESC", $this->id);


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $date     = $row['Date'];
                $interval = (date('U') - strtotime($date)) / 3600 / 24;
            } else {
                $interval = 0;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if ($this->data['Part Current Stock'] == '' or $this->data['Part Current Stock'] < 0) {
            $this->data['Part Days Available Forecast']      = 0;
            $this->data['Part XHTML Available For Forecast'] = '?';
        } elseif ($this->data['Part Current Stock'] == 0) {
            $this->data['Part Days Available Forecast']      = 0;
            $this->data['Part XHTML Available For Forecast'] = 0;
        } else {


            //print $this->data['Part 1 Quarter Acc Dispatched'];

            //   print $this->data['Part 1 Quarter Acc Dispatched']/(52/4)/7;


            if ($this->data['Part 1 Quarter Acc Dispatched'] > 0) {

                $days_on_sale = 91.25;

                $from_since = (date('U') - strtotime(
                            $this->data['Part Valid From']
                        )) / 3600 / 24;
                if ($from_since < 1) {
                    $from_since = 1;
                }


                if ($days_on_sale > $from_since) {
                    $days_on_sale = $from_since;
                }


                $this->data['Part Days Available Forecast']      = $this->data['Part Current Stock'] / ($this->data['Part 1 Quarter Acc Dispatched'] / $days_on_sale);
                $this->data['Part XHTML Available For Forecast'] = number(
                        $this->data['Part Days Available Forecast'], 0
                    ).' '._('d');

            } else {

                $from_since = (date('U') - strtotime(
                        $this->data['Part Valid From']
                    ) / 86400);
                if ($from_since < ($this->data['Part Excess Availability Days Limit'] / 2)) {
                    $forecast = $this->data['Part Excess Availability Days Limit'] - 1;
                } else {
                    $forecast = $this->data['Part Excess Availability Days Limit'] + $from_since;
                }


                $this->data['Part Days Available Forecast']      = $forecast;
                $this->data['Part XHTML Available For Forecast'] = number(
                        $this->data['Part Days Available Forecast'], 0
                    ).' '._('d');


            }


        }


        $this->update(
            array(
                'Part Days Available Forecast'      => $this->data['Part Days Available Forecast'],
                'Part XHTML Available for Forecast' => $this->data['Part XHTML Available For Forecast']
            ), 'no_history'
        );


    }

    function load_acc_data() {
        $sql = sprintf(
            "SELECT * FROM `Part Data` WHERE `Part SKU`=%d", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                foreach ($row as $key => $value) {
                    $this->data[$key] = $value;
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {

        global $account;

        if ($this->update_asset_field_switcher(
            $field, $value, $options, $metadata
        )
        ) {
            return;
        }

        switch ($field) {


            case 'Part Barcode Number':


                $this->update_field($field, $value, $options);


                $updated = $this->updated;


                foreach ($this->get_products('objects') as $product) {

                    if (count($product->get_parts()) == 1) {
                        $product->editor = $this->editor;
                        $product->update(
                            array('Product Barcode Number' => $value), $options.' from_part'
                        );
                    }

                }


                $this->updated = $updated;


                break;
            case 'Part Barcode Key':


                $this->update_field($field, $value, $options);


                $updated = $this->updated;


                foreach ($this->get_products('objects') as $product) {

                    if (count($product->get_parts()) == 1) {
                        $product->editor = $this->editor;
                        $product->update(
                            array('Product Barcode Key' => $value), $options.' from_part'
                        );
                    }

                }


                $this->updated = $updated;


                break;


            case 'Part Unit Label':


                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Unit label missing');

                    return;
                }

                $this->update_field($field, $value, $options);

                break;

            case 'Part Unit Description':


                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Unit description missing');

                    return;
                }

                $this->update_field($field, $value, $options);


                break;
            case 'Part Package Description':
                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Outers (SKO) description');

                    return;
                }

                $this->update_field($field, $value, $options);

                break;

            case 'Part Reference':

                if ($value == '') {
                    $this->error = true;
                    $this->msg   = sprintf(_('Reference missing'));

                    return;
                }

                $sql = sprintf(
                    'SELECT count(*) AS num FROM `Part Dimension` WHERE `Part Reference`=%s AND `Part SKU`!=%d ', prepare_mysql($value), $this->id
                );


                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        if ($row['num'] > 0) {
                            $this->error = true;
                            $this->msg   = sprintf(
                                _('Duplicated reference (%s)'), $value
                            );

                            return;
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }
                $this->update_field($field, $value, $options);
                break;
            case 'Part Unit Price':

                /*
			if ($value==''   ) {
				$this->error=true;
				$this->msg=_('Unit recommended price missing');
				return;
			}
*/
                if ($value != '' and (!is_numeric($value) or $value < 0)) {
                    $this->error = true;
                    $this->msg   = sprintf(
                        _('Invalid unit recommended price (%s)'), $value
                    );

                    return;
                }


                $this->update_field('Part Unit Price', $value, $options);

                $this->other_fields_updated = array(

                    'Part_Unit_RRP' => array(
                        'field'           => 'Part_Unit_RRP',
                        'render'          => true,
                        'value'           => $this->get('Part Unit RRP'),
                        'formatted_value' => $this->get('Unit RRP'),
                    ),

                );

                break;


            case 'Part Unit RRP':

                /*
			if ($value==''   ) {
				$this->error=true;
				$this->msg=_('Unit recommended price missing');
				return;
			}
*/
                if ($value != '' and (!is_numeric($value) or $value < 0)) {
                    $this->error = true;
                    $this->msg   = sprintf(
                        _('Invalid unit recommended RRP (%s)'), $value
                    );

                    return;
                }


                $this->update_field('Part Unit RRP', $value, $options);


                break;

            case 'Part Units Per Package':
                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Units per SKO missing');

                    return;
                }

                if (!is_numeric($value) or $value < 0) {
                    $this->error = true;
                    $this->msg   = sprintf(
                        _('Invalid units per SKO (%s)'), $value
                    );

                    return;
                }

                $this->update_field('Part Units Per Package', $value, $options);

                if (!preg_match('/skip_update_historic_object/', $options)) {
                    foreach (
                        $this->get_supplier_parts('objects') as $supplier_part
                    ) {
                        $supplier_part->update_historic_object();
                    }
                }


                $this->other_fields_updated = array(
                    'Part_Unit_Price' => array(
                        'field'           => 'Part_Unit_Price',
                        'render'          => true,
                        'value'           => $this->get('Part Unit Price'),
                        'formatted_value' => $this->get('Unit Price'),
                    ),
                    'Part_Unit_RRP'   => array(
                        'field'           => 'Part_Unit_RRP',
                        'render'          => true,
                        'value'           => $this->get('Part Unit RRP'),
                        'formatted_value' => $this->get('Unit RRP'),
                    ),

                );

                break;

            case 'Part Family Code':
            case 'Part Family Category Code':
                $account = new Account($this->db);
                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _("Family's code missing");

                    return;
                }

                include_once 'class.Category.php';


                $root_category = new Category(
                    $account->get('Account Part Family Category Key')
                );
                if ($root_category->id) {
                    $root_category->editor = $this->editor;
                    $family                = $root_category->create_category(
                        array('Category Code' => $value)
                    );
                    if ($family->id) {

                        $this->update_field_switcher(
                            'Part Family Category Key', $family->id, $options
                        );


                    } else {
                        $this->error = true;
                        $this->msg   = _("Can't create family");

                        return;
                    }
                } else {
                    $this->error = true;
                    $this->msg   = _("Part's families not configured");

                    return;
                }


                break;
            case 'Part Family Category Key';

                $account = new Account($this->db);
                include_once 'class.Category.php';


                if ($value != '') {


                    $category = new Category($value);
                    if ($category->id and $category->get('Category Root Key') == $account->get('Account Part Family Category Key')) {
                        $category->associate_subject($this->id);
                    } else {
                        $this->error = true;
                        $this->msg   = 'wrong category';

                        return;

                    }

                } else {

                    if ($this->data['Part Family Category Key'] != '') {


                        $category = new Category(
                            $this->data['Part Family Category Key']
                        );

                        if ($category->id) {
                            $category->disassociate_subject($this->id);
                        }

                    }


                }
                $this->update_field(
                    'Part Family Category Key', $value, 'no_history'
                );


                $categories = '';
                foreach ($this->get_category_data() as $item) {
                    $categories .= sprintf(
                        '<li><span class="button" onclick="change_view(\'category/%d\')" title="%s">%s</span></li>', $item['category_key'], $item['label'], $item['code']

                    );

                }
                $this->update_metadata = array(
                    'class_html' => array(
                        'Categories' => $categories,

                    )
                );


                break;
            case 'Part Materials':
                include_once 'utils/parse_materials.php';


                $materials_to_update = array();
                $sql                 = sprintf(
                    'SELECT `Material Key` FROM `Part Material Bridge` WHERE `Part SKU`=%d', $this->id
                );
                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $materials_to_update[$row['Material Key']] = true;
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


                if ($value == '') {
                    $materials = '';


                    $sql = sprintf(
                        "DELETE FROM `Part Material Bridge` WHERE `Part SKU`=%d ", $this->sku
                    );
                    $this->db->exec($sql);

                } else {

                    $materials_data = parse_materials($value, $this->editor);

                    // print_r($materials_data);

                    $sql = sprintf(
                        "DELETE FROM `Part Material Bridge` WHERE `Part SKU`=%d ", $this->sku
                    );

                    $this->db->exec($sql);

                    foreach ($materials_data as $material_data) {

                        if ($material_data['id'] > 0) {
                            $sql = sprintf(
                                "INSERT INTO `Part Material Bridge` (`Part SKU`, `Material Key`, `Ratio`, `May Contain`) VALUES (%d, %d, %s, %s) ", $this->sku, $material_data['id'],
                                prepare_mysql($material_data['ratio']), prepare_mysql($material_data['may_contain'])

                            );
                            $this->db->exec($sql);

                            if (isset($materials_to_update[$material_data['id']])) {
                                $materials_to_update[$material_data['id']] = false;
                            } else {
                                $materials_to_update[$material_data['id']] = true;
                            }

                        }


                    }


                    $materials = json_encode($materials_data);
                }


                foreach ($materials_to_update as $material_key => $update) {
                    if ($update) {
                        $material = new Material($material_key);
                        $material->update_stats();

                    }
                }


                $this->update_field('Part Materials', $materials, $options);
                $updated = $this->updated;


                foreach ($this->get_products('objects') as $product) {

                    if (count($product->get_parts()) == 1) {
                        $product->editor = $this->editor;
                        $product->update(
                            array('Product Materials' => $value), $options.' from_part'
                        );
                    }

                }


                $this->updated = $updated;
                break;


            case '':


                foreach ($this->get_products('objects') as $product) {

                    if (count($product->get_parts()) == 1) {
                        $product->editor = $this->editor;
                        $product->update(
                            array('Product Materials' => $value), $options.' from_part'
                        );
                    }

                }


                break;

            case 'Part Package Dimensions':
            case 'Part Unit Dimensions':


                include_once 'utils/parse_natural_language.php';

                $tag = preg_replace('/ Dimensions$/', '', $field);

                if ($value == '') {
                    $dim = '';
                    $vol = '';
                } else {
                    $dim = parse_dimensions($value);
                    if ($dim == '') {
                        $this->error = true;
                        $this->msg   = sprintf(
                            _("Dimensions can't be parsed (%s)"), $value
                        );

                        return;
                    }
                    $_tmp = json_decode($dim, true);
                    $vol  = $_tmp['vol'];
                }

                $this->update_field($tag.' Dimensions', $dim, $options);
                $updated = $this->updated;
                $this->update_field($tag.' Volume', $vol, 'no_history');
                //$this->update_linked_products($field, $value, $options, $metadata);

                if ($field == 'Part Unit Dimensions') {
                    foreach ($this->get_products('objects') as $product) {

                        if (count($product->get_parts()) == 1) {
                            $product->editor = $this->editor;
                            $product->update(
                                array('Product Unit Dimensions' => $value), $options.' from_part'
                            );
                        }

                    }
                }
                $this->updated = $updated;

                break;
            case 'Part Package Weight':
            case 'Part Unit Weight':

                if ($value != '' and (!is_numeric($value) or $value < 0)) {
                    $this->error = true;
                    $this->msg   = sprintf(_('Invalid weight (%s)'), $value);

                    return;
                }


                $tag  = preg_replace('/ Weight$/', '', $field);
                $tag2 = preg_replace('/^Part /', '', $tag);
                $tag3 = preg_replace('/ /', '_', $tag);

                $this->update_field($field, $value, $options);
                $updated                    = $this->updated;
                $this->other_fields_updated = array(
                    $tag3.'_Dimensions' => array(
                        'field'           => $tag3.'_Dimensions',
                        'render'          => true,
                        'value'           => $this->get($tag.' Dimensions'),
                        'formatted_value' => $this->get($tag2.' Dimensions'),


                    )
                );
                //$this->update_linked_products($field, $value, $options, $metadata);


                if ($field == 'Part Package Weight') {

                    if ($value != '') {
                        $purchase_order_keys = array();
                        $sql                 = sprintf(
                            "SELECT `Purchase Order Transaction Fact Key`,`Purchase Order Key`,`Purchase Order Quantity`,`Supplier Part Packages Per Carton` FROM `Purchase Order Transaction Fact` POTF LEFT JOIN `Supplier Part Dimension` S ON (POTF.`Supplier Part Key`=S.`Supplier Part Key`)  WHERE `Supplier Part Part SKU`=%d  AND `Purchase Order Weight` IS NULL AND `Purchase Order Transaction State` IN ('InProcess','Submitted')  ",
                            $this->id
                        );
                        //print $sql;
                        if ($result = $this->db->query($sql)) {
                            foreach ($result as $row) {
                                $purchase_order_keys[$row['Purchase Order Key']] = $row['Purchase Order Key'];
                                $sql                                             = sprintf(
                                    'UPDATE `Purchase Order Transaction Fact` SET  `Purchase Order Weight`=%f WHERE `Purchase Order Transaction Fact Key`=%d',
                                    $this->get('Part Package Weight') * $row['Supplier Part Packages Per Carton'] * $row['Purchase Order Quantity'], $row['Purchase Order Transaction Fact Key']
                                );
                                $this->db->exec($sql);
                            }
                            include_once 'class.PurchaseOrder.php';
                            foreach (
                                $purchase_order_keys as $purchase_order_key
                            ) {
                                $purchase_order = new PurchaseOrder(
                                    $purchase_order_key
                                );
                                $purchase_order->update_totals();
                            }

                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            exit;
                        }
                    }

                }

                if ($field == 'Part Unit Weight') {

                    foreach ($this->get_products('objects') as $product) {

                        if (count($product->get_parts()) == 1) {
                            $product->editor = $this->editor;
                            $product->update(
                                array(
                                    'Product Unit Weight' => $this->get(
                                        'Part Unit Weight'
                                    )
                                ), $options.' from_part'
                            );
                        }

                    }
                }

                $this->updated = $updated;
                break;
            case('Part Tariff Code'):

                if ($value == '') {
                    $tariff_code_valid = '';
                } else {
                    include_once 'utils/validate_tariff_code.php';
                    $tariff_code_valid = validate_tariff_code(
                        $value, $this->db
                    );
                }


                $this->update_field($field, $value, $options);
                $updated = $this->updated;
                $this->update_field(
                    'Part Tariff Code Valid', $tariff_code_valid, 'no_history'
                );


                foreach ($this->get_products('objects') as $product) {

                    if (count($product->get_parts()) == 1) {
                        $product->editor = $this->editor;
                        $product->update(
                            array(
                                'Product Tariff Code' => $this->get(
                                    'Part Tariff Code'
                                )
                            ), $options.' from_part'
                        );
                    }

                }

                //$this->update_linked_products($field, $value, $options, $metadata);
                $this->updated = $updated;

                break;
            case 'Part SKO Barcode':


                $sql = sprintf(
                    'SELECT count(*) AS num FROM `Part Dimension` WHERE `Part SKO Barcode`=%s AND `Part SKU`!=%d ', prepare_mysql($value), $this->id
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        if ($row['num'] > 0) {
                            $this->error = true;
                            $this->msg   = sprintf(
                                _('Duplicated SKO barcode (%s)'), $value
                            );

                            return;
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


                $this->update_field($field, $value, $options);

                $account->update_parts_data();

                if (file_exists('widgets/inventory_alerts.wget.php')) {
                    include_once('widgets/inventory_alerts.wget.php');
                    global $smarty;

                    if (is_object($smarty)) {


                        $_data = get_widget_data(

                            $account->get('Account Active Parts Number') - $account->get('Account Active Parts with SKO Barcode Number'), $account->get('Account Active Parts Number'), 0, 0
                        );


                        $smarty->assign('data', $_data);


                        $this->update_metadata = array('parts_with_no_sko_barcode' => $smarty->fetch('dashboard/inventory.parts_with_no_sko_barcode.dbard.tpl'));
                    }
                }
                break;
            case 'Part UN Number':
            case 'Part UN Class':
            case 'Part Packing Group':
            case 'Part Proper Shipping Name':
            case 'Part Hazard Indentification Number':
            case('Part Duty Rate'):
            case('Part CPNP Number'):

                $this->update_field($field, $value, $options);
                $updated = $this->updated;
                //$this->update_linked_products($field, $value, $options, $metadata);


                foreach ($this->get_products('objects') as $product) {

                    if (count($product->get_parts()) == 1) {
                        $product->editor = $this->editor;

                        $product_field = preg_replace(
                            '/^Part /', 'Product ', $field
                        );

                        $product->update(
                            array($product_field => $this->get($field)), $options.' from_part'
                        );
                    }

                }


                $this->updated = $updated;
                break;

            case 'Part Origin Country Code':


                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _("Country of origin missing");

                    return;
                }

                include_once 'class.Country.php';
                $country = new Country('find', $value);
                if ($country->get('Country Code') == 'UNK') {
                    $this->error = true;
                    $this->msg   = sprintf(_("Country not found (%s)"), $value);

                    return;

                }


                $this->update_field(
                    $field, $country->get('Country Code'), $options
                );
                $updated = $this->updated;

                foreach ($this->get_products('objects') as $product) {

                    if (count($product->get_parts()) == 1) {
                        $product->editor = $this->editor;

                        $product_field = preg_replace(
                            '/^Part /', 'Product ', $field
                        );

                        $product->update(
                            array($product_field => $this->get($field)), $options.' from_part'
                        );
                    }

                }


                $this->updated = $updated;
                break;

            case('Part Status'):


                if (!in_array(
                    $value, array(
                              'In Use',
                              'Not In Use',
                              'Discontinuing',
                              'In Process'
                          )
                )
                ) {
                    $this->error = true;
                    $this->msg   = _('Invalid part status').' ('.$value.')';

                    return;
                }

                /*
                                if ($this->get('Part Status') == 'In Process' and $value = 'In Use' and !($this->get('Part Current On Hand Stock') > 0)
                                ) {

                                    $this->error = true;
                                    $this->msg   = _("Part status can't be set to active until stock is set up");

                                    return;

                                }
                */

                if ($value == 'Not In Use') {
                    if ($this->get('Part Current On Hand Stock') > 0) {
                        $value = 'Discontinuing';
                    } elseif ($this->get('Part Current On Hand Stock') < 0) {

                    }
                }


                $this->update_status($value, $options);
                break;
            case('Part Available for Products Configuration'):
                $this->update_availability_for_products_configuration(
                    $value, $options
                );
                break;


            case 'Part Next Set Supplier Shipment':
                $this->update_set_next_supplier_shipment($value, $options);
                break;
            default:
                $base_data = $this->base_data();


                if (array_key_exists($field, $base_data)) {
                    //print "$field $value  ".$this->data[$field]." \n";
                    if ($value != $this->data[$field]) {

                        if ($field == 'Part General Description' or $field == 'Part Health And Safety') {
                            $options .= ' nohistory';
                        }
                        $this->update_field($field, $value, $options);


                    }
                } elseif (array_key_exists($field, $this->base_data('Part Data'))) {
                    $this->update_table_field($field, $value, $options, 'Part Data', 'Part Data', $this->id);
                } elseif (preg_match('/^custom_field_part/i', $field)) {
                    $this->update_field($field, $value, $options);
                }

        }


    }

    function get_supplier_parts($scope = 'keys') {


        if ($scope == 'objects') {
            include_once 'class.SupplierPart.php';
        }

        $sql = sprintf(
            'SELECT `Supplier Part Key` FROM `Supplier Part Dimension` WHERE `Supplier Part Part SKU`=%d ', $this->id
        );

        $supplier_parts = array();

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($scope == 'objects') {
                    $supplier_parts[$row['Supplier Part Key']] = new SupplierPart('id', $row['Supplier Part Key'], false, $this->db);
                } else {
                    $supplier_parts[$row['Supplier Part Key']] = $row['Supplier Part Key'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        return $supplier_parts;
    }

    function get_category_data() {


        $type = 'Part';

        $sql = sprintf(
            "SELECT B.`Category Key`,`Category Root Key`,`Other Note`,`Category Label`,`Category Code`,`Is Category Field Other` FROM `Category Bridge` B LEFT JOIN `Category Dimension` C ON (C.`Category Key`=B.`Category Key`) WHERE  `Category Branch Type`='Head'  AND B.`Subject Key`=%d AND B.`Subject`=%s",
            $this->id, prepare_mysql($type)
        );

        $category_data = array();


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $sql = sprintf(
                    "SELECT `Category Label`,`Category Code` FROM `Category Dimension` WHERE `Category Key`=%d", $row['Category Root Key']
                );


                if ($result2 = $this->db->query($sql)) {
                    if ($row2 = $result2->fetch()) {
                        $root_label = $row2['Category Label'];
                        $root_code  = $row2['Category Code'];
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


                if ($row['Is Category Field Other'] == 'Yes' and $row['Other Note'] != '') {
                    $value = $row['Other Note'];
                } else {
                    $value = $row['Category Label'];
                }
                $category_data[] = array(
                    'root_label'   => $root_label,
                    'root_code'    => $root_code,
                    'root_key'     => $row['Category Root Key'],
                    'label'        => $row['Category Label'],
                    'code'         => $row['Category Code'],
                    'value'        => $value,
                    'category_key' => $row['Category Key']
                );

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $category_data;
    }

    function update_availability_for_products_configuration($value, $options) {

        $this->update_field(
            'Part Available for Products Configuration', $value, $options
        );
        $new_value = $this->new_value;
        $updated   = $this->updated;

        if (preg_match('/dont_update_pages/', $options)) {
            $update_products = false;
        } else {
            $update_products = true;
        }

        $this->update_availability_for_products($update_products);
        $this->new_value = $new_value;
        $this->updated   = $updated;

    }

    function update_availability_for_products($update_pages = true) {

        switch ($this->data['Part Available for Products Configuration']) {
            case 'Yes':
            case 'No':
                $this->update_field(
                    'Part Available for Products', $this->data['Part Available for Products Configuration']
                );
                break;
            case 'Automatic':
                if ($this->data['Part Current Stock'] > 0 and $this->data['Part Status'] == 'In Use') {
                    $this->update_field('Part Available for Products', 'Yes');
                } else {
                    $this->update_field('Part Available for Products', 'No');
                }

        }


        if ($this->updated) {


            if (isset($this->editor['User Key']) and is_numeric(
                    $this->editor['User Key']
                )
            ) {
                $user_key = $this->editor['User Key'];
            } else {
                $user_key = 0;
            }

            $sql = sprintf(
                "SELECT UNIX_TIMESTAMP(`Date`) AS date,`Part Availability for Products Key` FROM `Part Availability for Products Timeline` WHERE `Part SKU`=%d AND `Warehouse Key`=%d  ORDER BY `Date` DESC ,`Part Availability for Products Key` DESC LIMIT 1",
                $this->sku, $this->warehouse_key
            );

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $last_record_key  = $row['Part Availability for Products Key'];
                    $last_record_date = $row['date'];
                } else {
                    $last_record_key  = false;
                    $last_record_date = false;
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            $new_date_formatted = gmdate('Y-m-d H:i:s');
            $new_date           = gmdate('U');

            $sql = sprintf(
                "INSERT INTO `Part Availability for Products Timeline`  (`Part SKU`,`User Key`,`Warehouse Key`,`Date`,`Availability for Products`) VALUES (%d,%d,%d,%s,%s) ", $this->sku, $user_key,
                $this->warehouse_key, prepare_mysql($new_date_formatted), prepare_mysql($this->data['Part Available for Products'])

            );
            $this->db->exec($sql);

            if ($last_record_key) {
                $sql = sprintf(
                    "UPDATE `Part Availability for Products Timeline` SET `Duration`=%d WHERE `Part Availability for Products Key`=%d", $new_date - $last_record_date, $last_record_key

                );
                $this->db->exec($sql);

            }


            foreach ($this->get_products('objects') as $product) {
                $product->editor = $this->editor;
                //$product->update_web_state($update_pages);

            }

        }

    }

    function get_stock_supplier_data() {

        // todo create a way to know what is the supplier based in stock

        $supplier_key               = 0;
        $supplier_part_key          = 0;
        $supplier_part_historic_key = 0;


        foreach ($this->get_supplier_parts('objects') as $supplier_part) {
            $supplier_key               = $supplier_part->get('Supplier Part Supplier Key');
            $supplier_part_key          = $supplier_part->id;
            $supplier_part_historic_key = $supplier_part->get('Supplier Part Historic Key');
            break;
        }


        return array(
            $supplier_key,
            $supplier_part_key,
            $supplier_part_historic_key
        );
    }

    function update_on_demand() {

        $on_demand_available = 'No';
        foreach ($this->get_supplier_parts('objects') as $supplier_part) {
            if ($supplier_part->get('Supplier Part On Demand') == 'Yes' and $supplier_part->get('Supplier Part Status') == 'Available') {
                $on_demand_available = 'Yes';
                break;
            }
        }
        $this->update_field(
            'Part On Demand', $on_demand_available, 'no_history'
        );


        foreach ($this->get_products('objects') as $product) {
            $product->update_availability();
        }
    }

    function update_fresh() {

        $fresh_available = 'No';
        foreach ($this->get_supplier_parts('objects') as $supplier_part) {
            if ($supplier_part->get('Supplier Part Fresh') == 'Yes' and $supplier_part->get('Supplier Part On Demand') == 'Yes' and $supplier_part->get('Supplier Part Status') == 'Available') {
                $fresh_available = 'Yes';
                break;
            }
        }
        $this->update_field('Part Fresh', $fresh_available, 'no_history');

        $this->update_stock_status();


        foreach ($this->get_suppliers('objects') as $supplier) {
            $supplier->update_supplier_parts();
        }

    }

    function get_suppliers($scope = 'keys') {


        if ($scope == 'objects') {
            include_once 'class.Supplier.php';
        }

        $sql = sprintf(
            'SELECT `Supplier Part Supplier Key` FROM `Supplier Part Dimension` WHERE `Supplier Part Part SKU`=%d ', $this->id
        );

        $suppliers = array();

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($scope == 'objects') {


                    $suppliers[$row['Supplier Part Supplier Key']] = new Supplier('id', $row['Supplier Part Supplier Key'], false, $this->db);
                } else {
                    $suppliers[$row['Supplier Part Supplier Key']] = $row['Supplier Part Supplier Key'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        return $suppliers;
    }

    function update_weight_dimensions_data($field, $value, $type) {

        include_once 'utils/units_functions.php';

        //print "$field $value |";

        $this->update_field($field, $value);
        $_new_value = $this->new_value;
        $_updated   = $this->updated;

        $this->updated   = true;
        $this->new_value = $value;
        if ($this->updated) {

            if (preg_match('/Package/i', $field)) {
                $tag = 'Package';
            } else {
                $tag = 'Unit';
            }
            if ($field != 'Part '.$tag.' '.$type.' Display Units') {
                $value_in_standard_units = convert_units(
                    $value, $this->data['Part '.$tag.' '.$type.' Display Units'], ($type == 'Dimensions' ? 'm' : 'Kg')
                );


                $this->update_field(
                    preg_replace('/\sDisplay$/', '', $field), $value_in_standard_units, 'nohistory'
                );
            } elseif ($field == 'Part '.$tag.' Dimensions Display Units') {

                $width_in_standard_units    = convert_units(
                    $this->data['Part '.$tag.' Dimensions Width Display'], $value, 'm'
                );
                $depth_in_standard_units    = convert_units(
                    $this->data['Part '.$tag.' Dimensions Depth Display'], $value, 'm'
                );
                $length_in_standard_units   = convert_units(
                    $this->data['Part '.$tag.' Dimensions Length Display'], $value, 'm'
                );
                $diameter_in_standard_units = convert_units(
                    $this->data['Part '.$tag.' Dimensions Diameter Display'], $value, 'm'
                );


                $this->update_field(
                    'Part '.$tag.' Dimensions Width', $width_in_standard_units, 'nohistory'
                );
                $this->update_field(
                    'Part '.$tag.' Dimensions Depth', $depth_in_standard_units, 'nohistory'
                );
                $this->update_field(
                    'Part '.$tag.' Dimensions Length', $length_in_standard_units, 'nohistory'
                );
                $this->update_field(
                    'Part '.$tag.' Dimensions Diameter', $diameter_in_standard_units, 'nohistory'
                );


            }

            //print "x".$this->updated."<<";


            //print "x".$this->updated."< $type <";
            if ($type == 'Dimensions') {
                include_once 'utils/geometry_functions.php';
                $volume = get_volume(
                    $this->data["Part $tag Dimensions Type"], $this->data["Part $tag Dimensions Width"], $this->data["Part $tag Dimensions Depth"], $this->data["Part $tag Dimensions Length"],
                    $this->data["Part $tag Dimensions Diameter"]
                );

                //print "*** $volume $volume";
                if (is_numeric($volume) and $volume > 0) {

                    $this->update_field(
                        'Part '.$tag.' Dimensions Volume', $volume, 'nohistory'
                    );
                }
                $this->update_field(
                    'Part '.$tag.' XHTML Dimensions', $this->get_xhtml_dimensions($tag), 'nohistory'
                );

            } else {
                $this->update_field(
                    'Part '.$tag.' Weight', convert_units(
                    $this->data['Part '.$tag.' Weight Display'], $this->data['Part '.$tag.' '.$type.' Display Units'], 'Kg'
                ), 'nohistory'
                );

            }


            $this->updated   = $_updated;
            $this->new_value = $_new_value;
        }
    }

    function get_period($period, $key) {
        return $this->get($period.' '.$key);
    }

    function get_unit($number) {
        //'10','25','100','200','bag','ball','box','doz','dwt','ea','foot','gram','gross','hank','kilo','ib','m','oz','ozt','pair','pkg','set','skein','spool','strand','ten','tube','vial','yd'
        switch ($this->data['Part Unit']) {
            case 'bag':
                $unit = ngettext('bag', 'bags', $number);
                break;
            case 'box':
                $unit = ngettext('box', 'boxes', $number);

                break;
            case 'doz':
                $unit = ngettext('dozen', 'dozens', $number);

                break;
            case 'ea':
                $unit = ngettext('unit', 'units', $number);

                break;
            default:
                $unit = $this->data['Part Unit'];
                break;
        }

        return $unit;
    }


    function get_stock($date) {
        $stock = 0;
        $value = 0;
        $sql   = sprintf(
            "SELECT ifnull(sum(`Quantity On Hand`), 0) AS stock, ifnull(sum(`Value At Cost`), 0) AS value FROM `Inventory Spanshot Fact` WHERE `Part SKU`=%d AND `Date`=%s", $this->id,
            prepare_mysql($date)
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $stock = $row['stock'];
                $value = $row['value'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return array(
            $stock,
            $value
        );
    }

    function update_stock_in_paid_orders() {

        $stock_in_paid_orders = 0;


        $sql = sprintf(
            'SELECT sum((`Order Quantity`+`Order Bonus Quantity`)*`Product Part Ratio`) AS required FROM `Order Transaction Fact` OTF LEFT JOIN `Product Part Bridge` PPB ON (OTF.`Product ID`=PPB.`Product Part Product ID`)    WHERE OTF.`Current Dispatching State` IN ("Submitted by Customer","In Process") AND  `Current Payment State` IN ("Paid","No Applicable") AND `Product Part Part SKU`=%d    ',

            $this->id
        );
        //print $sql;
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                //  print_r($row);
                $stock_in_paid_orders = $row['required'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        // print "$sql\n";
        $this->update(
            array(
                'Part Current Stock Ordered Paid' => $stock_in_paid_orders

            ), 'no_history'
        );

        if ($this->updated) {
            $this->update_stock();
        }
    }


    function get_barcode_data() {

        switch ($this->data['Part Barcode Data Source']) {
            case 'SKU':
                return $this->sku;
            case 'Reference':
                return $this->data['Part Reference'];
            default:
                return $this->data['Part Barcode Data'];


        }

    }

    function get_current_formatted_value_at_cost() {
        //return number($this->data['Part Current Value'],2);
        return money($this->data['Part Current Value']);
    }

    function get_current_formatted_value_at_current_cost() {

        $a = floatval(3.000 * 3.575);
        $a = round(3.575 + 3.575 + 3.575, 3);

        return money(
            $this->data['Part Current On Hand Stock'] * $this->data['Part Cost']
        );
    }

    function fix_stock_transactions() {

        include_once 'class.PartLocation.php';

        $sql = sprintf(
            "SELECT `Location Key`  FROM `Inventory Transaction Fact` WHERE `Part SKU`=%d GROUP BY `Location Key`", $this->sku
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $part_location = new PartLocation(
                    $this->sku.'_'.$row['Location Key']
                );
                $part_location->redo_adjusts();
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "SELECT `Inventory Transaction Key`,`Date`,`Inventory Transaction Record Type`,`Inventory Transaction Section`,`Location Key`,`Note`,`Inventory Transaction Quantity`,`Required`  FROM `Inventory Transaction Fact` WHERE `Part SKU`=%d AND `Inventory Transaction Section` IN ('Out','OIP') ORDER BY `Date`",
            $this->sku
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                if ($row['Inventory Transaction Section'] == 'OIP') {
                    $qty = $row['Required'];
                } else {
                    $qty = -1 * $row['Inventory Transaction Quantity'];
                }
                $picking_locations = $this->get_picking_location_historic(
                    $row['Date'], $qty
                );

                if (count($picking_locations == 1) and $picking_locations[0]['location_key'] != $row['Location Key']) {

                    $_location = new Location(
                        $picking_locations[0]['location_key']
                    );
                    $note      = $row['Note'];

                    if (preg_match('/(<.*a> )(.*)/', $note, $matches)) {

                        if ($_location->id == 1) {
                            $location_note .= ' '._('Taken from an')." ".sprintf(
                                    "<a href='location.php?id=1'>%s</a>", _('Unknown Location')
                                );
                        } else {
                            $location_note = ' '._('Taken from').": ".sprintf(
                                    "<a href='location.php?id=%d'>%s</a>", $_location->id, $_location->data['Location Code']
                                );
                        }


                        $note = $matches[1].$location_note;
                    } else {

                        $note .= ' (WL)';
                    }


                    $sql = sprintf(
                        'UPDATE `Inventory Transaction Fact` SET `Location Key`=%d ,`Note`=%s WHERE `Inventory Transaction Key`=%d', $_location->id, prepare_mysql($note),
                        $row['Inventory Transaction Key']
                    );
                    print $sql;
                    $this->db->exec($sql);
                    print_r($row);
                    print_r($picking_locations);
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $this->update_stock();

    }

    function update_stock_history() {


        $sql = sprintf(
            "SELECT `Location Key`  FROM `Inventory Transaction Fact` WHERE `Part SKU`=%d GROUP BY `Location Key`", $this->sku
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $part_location = new PartLocation(
                    $this->sku.'_'.$row['Location Key']
                );
                $part_location->update_stock_history();
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }

    function update_stock_in_transactions() {

        $locations_data = array();
        $stock          = 0;
        $sql            = sprintf(
            "SELECT `Inventory Transaction Quantity` ,`Inventory Transaction Key`,`Location Key` FROM `Inventory Transaction Fact` WHERE `Part SKU`=%d ORDER BY `Date`,`Event Order`", $this->sku
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if (array_key_exists($row['Location Key'], $locations_data)) {
                    $locations_data[$row['Location Key']] += $row['Inventory Transaction Quantity'];
                } else {
                    $locations_data[$row['Location Key']] = $row['Inventory Transaction Quantity'];
                }

                $stock += $row['Inventory Transaction Quantity'];
                $sql   = sprintf(
                    "UPDATE `Inventory Transaction Fact` SET `Part Stock`=%f,`Part Location Stock`=%f WHERE `Inventory Transaction Key`=%d", $stock, $locations_data[$row['Location Key']],
                    $row['Inventory Transaction Key']
                );
                $this->db->exec($sql);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


    }

    function update_sales_from_invoices($interval, $this_year = true, $last_year = true) {

        include_once 'utils/date_functions.php';
        list(
            $db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb
            ) = calculate_interval_dates($this->db, $interval);


        if ($this_year) {

            $sales_data = $this->get_sales_data($from_date, $to_date);


            $data_to_update = array(
                "Part $db_interval Acc Customers"        => $sales_data['customers'],
                "Part $db_interval Acc Repeat Customers" => $sales_data['repeat_customers'],
                "Part $db_interval Acc Deliveries"       => $sales_data['deliveries'],
                "Part $db_interval Acc Profit"           => $sales_data['profit'],
                "Part $db_interval Acc Invoiced Amount"  => $sales_data['invoiced_amount'],
                "Part $db_interval Acc Required"         => $sales_data['required'],
                "Part $db_interval Acc Dispatched"       => $sales_data['dispatched'],
                "Part $db_interval Acc Keeping Days"     => $sales_data['keep_days'],
                "Part $db_interval Acc With Stock Days"  => $sales_data['with_stock_days'],
            );


            $this->update($data_to_update, 'no_history');
        }
        if ($from_date_1yb and $last_year) {


            $sales_data = $this->get_sales_data($from_date_1yb, $to_date_1yb);


            $data_to_update = array(

                "Part $db_interval Acc 1YB Customers"        => $sales_data['customers'],
                "Part $db_interval Acc 1YB Repeat Customers" => $sales_data['repeat_customers'],
                "Part $db_interval Acc 1YB Deliveries"       => $sales_data['deliveries'],
                "Part $db_interval Acc 1YB Profit"           => $sales_data['profit'],
                "Part $db_interval Acc 1YB Invoiced Amount"  => $sales_data['invoiced_amount'],
                "Part $db_interval Acc 1YB Required"         => $sales_data['required'],
                "Part $db_interval Acc 1YB Dispatched"       => $sales_data['dispatched'],
                "Part $db_interval Acc 1YB Keeping Day"      => $sales_data['keep_days'],
                "Part $db_interval Acc 1YB With Stock Days"  => $sales_data['with_stock_days'],

            );
            $this->update($data_to_update, 'no_history');


        }

        if (in_array(
            $db_interval, [
                            'Total',
                            'Year To Date',
                            'Quarter To Date',
                            'Week To Date',
                            'Month To Date',
                            'Today'
                        ]
        )) {

            $this->update(['Part Acc To Day Updated' => gmdate('Y-m-d H:i:s')], 'no_history');

        } elseif (in_array(
            $db_interval, [
                            '1 Year',
                            '1 Month',
                            '1 Week',
                            '1 Quarter'
                        ]
        )) {

            $this->update(['Part Acc Ongoing Intervals Updated' => gmdate('Y-m-d H:i:s')], 'no_history');
        } elseif (in_array(
            $db_interval, [
                            'Last Month',
                            'Last Week',
                            'Yesterday',
                            'Last Year'
                        ]
        )) {

            $this->update(['Part Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')], 'no_history');
        }


    }

    function get_sales_data($from_date, $to_date) {

        $sales_data = array(
            'invoiced_amount'  => 0,
            'profit'           => 0,
            'required'         => 0,
            'dispatched'       => 0,
            'deliveries'       => 0,
            'customers'        => 0,
            'repeat_customers' => 0,
            'keep_days'        => 0,
            'with_stock_days'  => 0,

        );


        if ($from_date == '' and $to_date == '') {
            $sales_data['repeat_customers'] = $this->get_customers_total_data();
        }


        $sql = sprintf(
            "SELECT count(DISTINCT `Delivery Note Customer Key`) AS customers, count( DISTINCT ITF.`Delivery Note Key`) AS deliveries, round(ifnull(sum(`Amount In`),0),2) AS invoiced_amount,round(ifnull(sum(`Amount In`+`Inventory Transaction Amount`),0),2) AS profit,round(ifnull(sum(`Inventory Transaction Quantity`),0),1) AS dispatched,round(ifnull(sum(`Required`),0),1) AS required FROM `Inventory Transaction Fact` ITF  LEFT JOIN `Delivery Note Dimension` DN ON (DN.`Delivery Note Key`=ITF.`Delivery Note Key`) WHERE `Inventory Transaction Type` LIKE 'Sale' AND `Part SKU`=%d %s %s",
            $this->id, ($from_date ? sprintf('and  `Date`>=%s', prepare_mysql($from_date)) : ''), ($to_date ? sprintf('and `Date`<%s', prepare_mysql($to_date)) : '')
        );

        //print "$sql\n";

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['customers']       = $row['customers'];
                $sales_data['invoiced_amount'] = $row['invoiced_amount'];
                $sales_data['profit']          = $row['profit'];
                $sales_data['dispatched']      = -1.0 * $row['dispatched'];
                $sales_data['required']        = $row['required'];
                $sales_data['deliveries']      = $row['deliveries'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $sales_data;

    }

    function get_customers_total_data() {

        $repeat_customers = 0;


        $sql = sprintf(
            'SELECT count(`Customer Part Customer Key`) AS num  FROM `Customer Part Bridge` WHERE `Customer Part Delivery Notes`>1 AND `Customer Part Part SKU`=%d    ', $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $repeat_customers = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $repeat_customers;

    }

    function update_previous_years_data() {

        foreach (range(1, 5) as $i) {
            $data_iy_ago    = $this->get_sales_data(
                date('Y-01-01 00:00:00', strtotime('-'.$i.' year')), date('Y-01-01 00:00:00', strtotime('-'.($i - 1).' year'))
            );
            $data_to_update = array(
                "Part $i Year Ago Customers"        => $data_iy_ago['customers'],
                "Part $i Year Ago Repeat Customers" => $data_iy_ago['repeat_customers'],
                "Part $i Year Ago Deliveries"       => $data_iy_ago['deliveries'],
                "Part $i Year Ago Profit"           => $data_iy_ago['profit'],
                "Part $i Year Ago Invoiced Amount"  => $data_iy_ago['invoiced_amount'],
                "Part $i Year Ago Required"         => $data_iy_ago['required'],
                "Part $i Year Ago Dispatched"       => $data_iy_ago['dispatched'],
                "Part $i Year Ago Keeping Day"      => $data_iy_ago['keep_days'],
                "Part $i Year Ago With Stock Days"  => $data_iy_ago['with_stock_days'],
            );

            $this->update($data_to_update, 'no_history');
        }

    }

    function update_previous_quarters_data() {


        include_once 'utils/date_functions.php';

        foreach (range(1, 4) as $i) {
            $dates     = get_previous_quarters_dates($i);
            $dates_1yb = get_previous_quarters_dates($i + 4);


            $sales_data     = $this->get_sales_data(
                $dates['start'], $dates['end']
            );
            $sales_data_1yb = $this->get_sales_data(
                $dates_1yb['start'], $dates_1yb['end']
            );

            $data_to_update = array(
                "Part $i Quarter Ago Customers"        => $sales_data['customers'],
                "Part $i Quarter Ago Repeat Customers" => $sales_data['repeat_customers'],
                "Part $i Quarter Ago Deliveries"       => $sales_data['deliveries'],
                "Part $i Quarter Ago Profit"           => $sales_data['profit'],
                "Part $i Quarter Ago Invoiced Amount"  => $sales_data['invoiced_amount'],
                "Part $i Quarter Ago Required"         => $sales_data['required'],
                "Part $i Quarter Ago Dispatched"       => $sales_data['dispatched'],
                "Part $i Quarter Ago Keeping Day"      => $sales_data['keep_days'],
                "Part $i Quarter Ago With Stock Days"  => $sales_data['with_stock_days'],

                "Part $i Quarter Ago 1YB Customers"        => $sales_data_1yb['customers'],
                "Part $i Quarter Ago 1YB Repeat Customers" => $sales_data_1yb['repeat_customers'],
                "Part $i Quarter Ago 1YB Deliveries"       => $sales_data_1yb['deliveries'],
                "Part $i Quarter Ago 1YB Profit"           => $sales_data_1yb['profit'],
                "Part $i Quarter Ago 1YB Invoiced Amount"  => $sales_data_1yb['invoiced_amount'],
                "Part $i Quarter Ago 1YB Required"         => $sales_data_1yb['required'],
                "Part $i Quarter Ago 1YB Dispatched"       => $sales_data_1yb['dispatched'],
                "Part $i Quarter Ago 1YB Keeping Day"      => $sales_data_1yb['keep_days'],
                "Part $i Quarter Ago 1YB With Stock Days"  => $sales_data_1yb['with_stock_days'],
            );
            $this->update($data_to_update, 'no_history');
        }

    }


    function delete($metadata = false) {


        $sql = sprintf(
            'INSERT INTO `Part Deleted Dimension`  (`Part Deleted Key`,`Part Deleted Reference`,`Part Deleted Date`,`Part Deleted Metadata`) VALUES (%d,%s,%s,%s) ', $this->id,
            prepare_mysql($this->get('Part Reference')), prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql(gzcompress(json_encode($this->data), 9))

        );
        $this->db->exec($sql);


        $sql = sprintf(
            'DELETE FROM `Part Dimension`  WHERE `Part SKU`=%d ', $this->id
        );
        $this->db->exec($sql);


        $history_data = array(
            'History Abstract' => sprintf(
                _("Part record %s deleted"), $this->data['Part Reference']
            ),
            'History Details'  => '',
            'Action'           => 'deleted'
        );

        $this->add_subject_history(
            $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
        );


        $this->deleted = true;


        $sql = sprintf(
            'SELECT `Supplier Part Key` FROM `Supplier Part Dimension` WHERE `Supplier Part Part SKU`=%d  ', $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $supplier_part = get_object(
                    'Supplier Part', $row['Supplier Part Key']
                );
                $supplier_part->delete();
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }

    function get_field_label($field) {
        global $account;

        switch ($field) {

            case 'Part SKU':
                $label = _('SKU');
                break;
            case 'Part Status':
                $label = _('Status');
                break;
            case 'Part Reference':
                $label = _('reference');
                break;
            case 'Part Unit Description':
                $label = _('unit description');
                break;
            case 'Part Unit Label':
                $label = _('unit label');
                break;
            case 'Part Package Description':
                $label = _('SKO description');
                break;
            case 'Part Package Description Note':
                $label = _('SKO description note');
                break;
            case 'Part Package Image':
                $label = _('SKO image');
                break;

            case 'Part Unit Price':
                $label = _('unit recommended price');
                break;
            case 'Part Unit RRP':
                $label = _('unit recommended RRP');
                break;

            case 'Part Package Weight':
                $label = _('SKO weight');
                break;
            case 'Part Package Dimensions':
                $label = _('SKO dimensions');
                break;
            case 'Part Unit Weight':
                $label = _('unit weight');
                break;
            case 'Part Unit Dimensions':
                $label = _('unit dimensions');
                break;
            case 'Part Tariff Code':
                $label = _('tariff code');
                break;

            case 'Part Duty Rate':
                $label = _('duty rate');
                break;

            case 'Part UN Number':
                $label = _('UN number');
                break;

            case 'Part UN Class':
                $label = _('UN class');
                break;
            case 'Part Packing Group':
                $label = _('packing group');
                break;
            case 'Part Proper Shipping Name':
                $label = _('proper shipping name');
                break;
            case 'Part Hazard Indentification Number':
                $label = _('hazard identification number');
                break;
            case 'Part Materials':
                $label = _('Materials/Ingredients');
                break;
            case 'Part Origin Country Code':
                $label = _('country of origin');
                break;
            case 'Part Units Per Package':
                $label = _('units per SKO');
                break;
            case 'Part Barcode Number':
                $label = _('barcode');
                break;
            case 'Part CPNP Number':
                $label = _('CPNP number');
                break;
            case 'Part Cost in Warehouse':
                $label = _('Stock value (per SKO)');
                break;
            default:
                $label = $field;

        }

        return $label;

    }

    function get_products_data($with_objects = false) {

        include_once 'class.Product.php';

        $sql           = sprintf(
            "SELECT `Linked Fields`,`Store Product Key`,`Parts Per Product`,`Note` FROM `Store Product Part Bridge` WHERE `Part SKU`=%d ", $this->id
        );
        $products_data = array();
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $product_data = $row;
                if ($product_data['Linked Fields'] == '') {
                    $product_data['Linked Fields']        = array();
                    $product_data['Number Linked Fields'] = 0;
                } else {
                    $product_data['Linked Fields']        = json_decode(
                        $row['Linked Fields'], true
                    );
                    $product_data['Number Linked Fields'] = count(
                        $product_data['Linked Fields']
                    );
                }
                if ($with_objects) {
                    $product_data['Product'] = new Product(
                        'id', $row['Store Product Key']
                    );
                }
                $products_data[] = $product_data;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        return $products_data;
    }

    function create_supplier_part_record($data) {


        include_once 'class.Supplier.php';

        $data['editor'] = $this->editor;


        $supplier = new Supplier($data['Supplier Part Supplier Key']);
        if (!$supplier->id) {
            $this->error      = true;
            $this->error_code = 'supplier_not_found';
            $this->msg        = _('Supplier not found');
        }

        if ($data['Supplier Part Minimum Carton Order'] == '') {
            $data['Supplier Part Minimum Carton Order'] = 1;
        } else {
            $data['Supplier Part Minimum Carton Order'] = ceil(
                $data['Supplier Part Minimum Carton Order']
            );
        }


        $data['Supplier Part Currency Code'] = $supplier->get('Supplier Default Currency Code');





        $supplier_part = new SupplierPart('find', $data, 'create');



        if ($supplier_part->id) {
            $this->new_object_msg = $supplier_part->msg;

            if ($supplier_part->new) {
                $this->new_object = true;








                $supplier_part->update(array('Supplier Part Part SKU' => $this->sku));
                $supplier_part->get_data('id', $supplier_part->id);

                $supplier->update_supplier_parts();

                $this->update_cost();
                $supplier_part->update_historic_object();


            } else {

                $this->error = true;
                if ($supplier_part->found) {

                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(
                        array($supplier_part->duplicated_field)
                    );

                    if ($supplier_part->duplicated_field == 'Supplier Part Reference') {
                        $this->msg = _("Duplicated supplier's part reference");
                    } else {
                        $this->msg = 'Duplicated '.$supplier_part->duplicated_field;
                    }


                } else {
                    $this->msg = $supplier_part->msg;
                }
            }

            return $supplier_part;
        } else {
            $this->error = true;

            if ($supplier_part->found) {
                $this->error_code     = 'duplicated_field';
                $this->error_metadata = json_encode(
                    array($supplier_part->duplicated_field)
                );

                if ($supplier_part->duplicated_field == 'Part Reference') {
                    $this->msg = _("Duplicated part reference");
                } else {
                    $this->msg = 'Duplicated '.$supplier_part->duplicated_field;
                }

            } else {


                $this->msg = $supplier_part->msg;
            }
        }

    }

    function update_cost() {

        $account = new Account($this->db);

        $supplier_parts = $this->get_supplier_parts('objects');

        $cost_available    = array();
        $cost_no_available = array();
        $cost_discontinued = array();


        foreach ($supplier_parts as $supplier_part) {


            if ($supplier_part->get('Supplier Part Currency Code') != $account->get('Account Currency')) {
                include_once 'utils/currency_functions.php';
                $exchange = currency_conversion(
                    $this->db, $supplier_part->get('Supplier Part Currency Code'), $account->get('Account Currency'), '- 15 minutes'
                );

            } else {
                $exchange = 1;
            }

            $_cost = $exchange * ($supplier_part->get('Supplier Part Unit Cost') + $supplier_part->get('Supplier Part Unit Extra Cost'));


            if ($supplier_part->get('Supplier Part Status') == 'Available') {
                $cost_available[] = $_cost;

            } elseif ($supplier_part->get('Supplier Part Status') == 'NoAvailable') {
                $cost_no_available[] = $_cost;

            } elseif ($supplier_part->get('Supplier Part Status') == 'Discontinued') {
                $cost_discontinued[] = $_cost;

            }


        }


        $cost     = 0;
        $cost_set = false;


        if (count($cost_available) > 0) {

            $cost     = array_sum($cost_available) / count($cost_available);
            $cost_set = true;
        }

        if (!$cost_set and count($cost_no_available) > 0) {
            $cost     = array_sum($cost_no_available) / count(
                    $cost_no_available
                );
            $cost_set = true;
        }

        if (!$cost_set and count($cost_discontinued) > 0) {
            $cost     = array_sum($cost_discontinued) / count(
                    $cost_discontinued
                );
            $cost_set = true;
        }


        if ($cost_set) {
            $cost = $cost * $this->data['Part Units Per Package'];
        }

        $this->update_field('Part Cost', $cost, 'no_history');
        $this->update_field('Part Number Supplier Parts', count($supplier_parts), 'no_history');


        foreach ($this->get_products('objects') as $product) {
            $product->update_cost();
        }


    }

    function updated_linked_products() {
        include_once 'class.Image.php';
        foreach ($this->get_products('objects') as $product) {

            if (count($product->get_parts()) == 1) {
                $product->editor = $this->editor;

                $product->update(
                    array(
                        'Product Tariff Code' => $this->get(
                            'Part Tariff Code'
                        )
                    ), 'no_history from_part'
                );
                $product->update(
                    array('Product Duty Rate' => $this->get('Part Duty Rate')), 'no_history from_part'
                );
                $product->update(
                    array(
                        'Product Origin Country Code' => $this->get(
                            'Part Origin Country Code'
                        )
                    ), 'no_history from_part'
                );


                $product->update(
                    array('Product UN Number' => $this->get('Part UN Number')), 'no_history from_part'
                );
                $product->update(
                    array('Product UN Class' => $this->get('Part UN Class')), 'no_history from_part'
                );
                $product->update(
                    array(
                        'Product Packing Group' => $this->get(
                            'Part Packing Group'
                        )
                    ), 'no_history from_part'
                );
                $product->update(
                    array(
                        'Product Proper Shipping Name' => $this->get(
                            'Part Proper Shipping Name'
                        )
                    ), 'no_history from_part'
                );
                $product->update(
                    array(
                        'Product Hazard Indentification Number' => $this->get(
                            'Part Hazard Indentification Number'
                        )
                    ), 'no_history from_part'
                );


                $product->update(
                    array(
                        'Product Unit Weight' => $this->get(
                            'Part Unit Weight'
                        )
                    ), 'no_history from_part'
                );


                $product->update(
                    array(
                        'Product Unit Dimensions' => $this->get(
                            'Part Unit Dimensions'
                        )
                    ), 'no_history from_part'
                );
                $product->update(
                    array(
                        'Product Materials' => strip_tags(
                            $this->get('Materials')
                        )
                    ), 'no_history from_part'
                );

                $sql = sprintf(
                    'SELECT `Image Subject Image Key` FROM `Image Subject Bridge` WHERE `Image Subject Object`="Part" AND `Image Subject Object Key`=%d  ', $this->id
                );

                //   print "$sql\n";

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        //print_r($row);
                        $product->link_image($row['Image Subject Image Key']);
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


            }

        }


    }

    function get_picking_locations($qty = 1) {


        include_once 'class.PartLocation.php';

        $this->unknown_location_associated = false;
        $locations                         = array();
        $locations_data                    = array();
        $sql                               = sprintf("SELECT `Location Key` FROM `Part Location Dimension` WHERE `Part SKU` IN (%s) ORDER BY `Can Pick` ;", $this->sku);

        // print $sql;


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $part_location = new PartLocation($this->sku.'_'.$row['Location Key']);
                //list($stock,$value,$in_process)=$part_location->get_stock();
                $stock = $part_location->data['Quantity On Hand'];

                $locations_data[] = array(
                    'location_key' => $row['Location Key'],
                    'stock'        => $stock
                );
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $number_associated_locations = count($locations_data);

        if ($number_associated_locations == 0) {
            $this->unknown_location_associated = true;
            $locations[]                       = array(
                'location_key' => 1,
                'qty'          => $qty
            );
            //  $qty=0;
        } else {

            foreach ($locations_data as $location_data) {

                $locations[] = array(
                    'location_key' => $location_data['location_key'],
                    'qty'          => $qty
                );
                break;


            }
            //print_r($locations);
            //print "--- $qty\n";


        }

        //print_r($locations);
        return $locations;

    }


    function update_products_data() {


        //'InProcess','Active','Suspended',,'Discontinued'

        $active_products =0;
        $no_active_products =0;


        $sql = sprintf(
            "SELECT count(*) AS num FROM `Product Part Bridge`  LEFT JOIN `Product Dimension` P ON (P.`Product ID`=`Product Part Product ID`)  WHERE `Product Part Part SKU`=%d  AND `Product Status` IN ('InProcess','Active','Discontinuing') ",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $active_products = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $sql = sprintf(
            "SELECT count(*) AS num FROM `Product Part Bridge`  LEFT JOIN `Product Dimension` P ON (P.`Product ID`=`Product Part Product ID`)  WHERE `Product Part Part SKU`=%d  AND `Product Status` IN ('Suspended','Discontinued') ",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $no_active_products = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $this->update(
            array(
                'Part Number Active Products' => $active_products,
                'Part Number No Active Products' => $no_active_products,

            ), 'no_history'

        );


    }

}
