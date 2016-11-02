<?php
/*
 File: Family.php

 This file contains the Contact Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'class.Product.php';


class Family extends DB_Table {


    var $products = false;
    var $external_DB_link = false;
    var $id = false;
    var $data = array();
    var $locale;
    var $url;
    var $user_id;
    var $method;
    var $match = true;
    var $currency;

    /*
      Constructor: Family
      Initializes the class, trigger  Search/Load/Create for the data set

      Returns:
      void
    */


    function Family($a1 = false, $a2 = false, $a3 = false) {
        //Transfered from LightFamily

        //End Transfer

        global $db;
        $this->db = $db;


        $this->table_name    = 'Product Family';
        $this->page_data     = false;
        $this->ignore_fields = array(
            'Product Family Key',
            'Product Family For Sale Products',
            'Product Family In Process Products',
            'Product Family Not For Sale Products',
            'Product Family Discontinued Products',
            'Product Family Unknown Sales State Products',
            'Product Family Surplus Availability Products',
            'Product Family Optimal Availability Products',
            'Product Family Low Availability Products',
            'Product Family Critical Availability Products',
            'Product Family Out Of Stock Products',
            'Product Family Unknown Stock Products',
            'Product Family Total Invoiced Gross Amount',
            'Product Family Total Invoiced Discount Amount',
            'Product Family Total Invoiced Amount',
            'Product Family Total Profit',
            'Product Family Total Quantity Ordered',
            'Product Family Total Quantity Invoiced',
            'Product Family Total Quantity Delivere',
            'Product Family Total Days On Sale',
            'Product Family Total Days Available',
            'Product Family 1 Year Acc Invoiced Gross Amount',
            'Product Family 1 Year Acc Invoiced Discount Amount',
            'Product Family 1 Year Acc Invoiced Amount',
            'Product Family 1 Year Acc Profit',
            'Product Family 1 Year Acc Quantity Ordered',
            'Product Family 1 Year Acc Quantity Invoiced',
            'Product Family 1 Year Acc Quantity Delivere',
            'Product Family 1 Year Acc Days On Sale',
            'Product Family 1 Year Acc Days Available',
            'Product Family 1 Quarter Acc Invoiced Gross Amount',
            'Product Family 1 Quarter Acc Invoiced Discount Amount',
            'Product Family 1 Quarter Acc Invoiced Amount',
            'Product Family 1 Quarter Acc Profit',
            'Product Family 1 Quarter Acc Quantity Ordered',
            'Product Family 1 Quarter Acc Quantity Invoiced',
            'Product Family 1 Quarter Acc Quantity Delivere',
            'Product Family 1 Quarter Acc Days On Sale',
            'Product Family 1 Quarter Acc Days Available',
            'Product Family 1 Month Acc Invoiced Gross Amount',
            'Product Family 1 Month Acc Invoiced Discount Amount',
            'Product Family 1 Month Acc Invoiced Amount',
            'Product Family 1 Month Acc Profit',
            'Product Family 1 Month Acc Quantity Ordered',
            'Product Family 1 Month Acc Quantity Invoiced',
            'Product Family 1 Month Acc Quantity Delivere',
            'Product Family 1 Month Acc Days On Sale',
            'Product Family 1 Month Acc Days Available',
            'Product Family 1 Week Acc Invoiced Gross Amount',
            'Product Family 1 Week Acc Invoiced Discount Amount',
            'Product Family 1 Week Acc Invoiced Amount',
            'Product Family 1 Week Acc Profit',
            'Product Family 1 Week Acc Quantity Ordered',
            'Product Family 1 Week Acc Quantity Invoiced',
            'Product Family 1 Week Acc Quantity Delivere',
            'Product Family 1 Week Acc Days On Sale',
            'Product Family 1 Week Acc Days Available',
            'Product Family Total Quantity Delivered',
            'Product Family 1 Year Acc Quantity Delivered',
            'Product Family 1 Month Acc Quantity Delivered',
            'Product Family 1 Quarter Acc Quantity Delivered',
            'Product Family 1 Week Acc Quantity Delivered'


        );


        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1, false);
        } else {
            if (preg_match('/new|create/', $a1)) {
                $this->find($a2, 'create');
            } else {
                if (preg_match('/find/', $a1)) {
                    $this->find($a2, $a3);
                } elseif ($a2 != '') {
                    $this->get_data($a1, $a2, $a3);
                }
            }
        }
    }


    /*
        Function: find
        Busca the family
      */

    function get_data($tipo, $tag, $tag2 = false) {
        switch ($tipo) {
            case('id'):
                $sql = sprintf(
                    "SELECT *  FROM `Product Family Dimension` WHERE `Product Family Key`=%d ", $tag
                );
                break;
            case('code'):
            case('code_store'):
                $sql = sprintf(
                    "SELECT *  FROM `Product Family Dimension` WHERE `Product Family Code`=%s AND `Product Family Store Key`=%d ", prepare_mysql($tag), $tag2
                );
                //print $sql;
                break;
        }

        // print $sql;
        $result = mysql_query($sql);
        if ($this->data = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->id = $this->data['Product Family Key'];
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

        $this->found     = false;
        $this->found_key = false;
        $create          = '';
        $update          = '';
        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }
        if (preg_match('/update/i', $options)) {
            $update = 'update';
        }

        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = _trim($value);
            }
        }
        if ($data['Product Family Store Key'] == '') {
            $this->error = true;
            $this->msg   = 'Store Key not provided';

            return;
        }
        if ($data['Product Family Main Department Key'] == '') {
            $this->error = true;
            $this->msg   = 'Department Key code empty';

            return;
        }

        if ($data['Product Family Code'] == '') {
            $this->error = true;
            $this->msg   = 'Family code empty';

            return;
        }

        if ($data['Product Family Name'] == '') {
            $data['Product Family Name'] = $data['Product Family Code'];
        }

        $sql = sprintf(
            "SELECT * FROM `Product Family Dimension` WHERE `Product Family Code`=%s  AND `Product Family Store Key`=%d ", prepare_mysql($data['Product Family Code']),
            $data['Product Family Store Key']
        );

        $result = mysql_query($sql);
        if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->found     = true;
            $this->found_key = $row['Product Family Key'];
        }

        if ($this->found) {
            $this->get_data('id', $this->found_key);
            if ($create) {
                $this->msg = _('Family').' '.$this->data['Product Family Code'].' '._('is already created');
            }
        }

        if (!$this->found and $create) {

            $this->create($data);

        }


    }

    function create($data) {
        $this->new = false;

        $base_data = $this->base_data();
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $base_data)) {
                $base_data[$key] = _trim($value);
            }
        }

        if ($data['Product Family Special Characteristic'] != '') {
            $data['Product Family Special Characteristic']
                = $this->special_characteristic_if_duplicated($data);
        }


        $department                                       = new Department(
            $base_data['Product Family Main Department Key']
        );
        $base_data['Product Family Main Department Code'] = $department->get(
            'Product Department Code'
        );
        $base_data['Product Family Main Department Name'] = $department->get(
            'Product Department Name'
        );

        $store                                  = new Store(
            $base_data['Product Family Store Key']
        );
        $base_data['Product Family Store Code'] = $store->get('Store Code');
        $base_data['Product Family Currency Code']
                                                = $store->data['Store Currency Code'];


        if ($base_data['Product Family Special Characteristic'] == '') {
            $base_data['Product Family Special Characteristic']
                = $base_data['Product Family Name'];
        }

        $keys   = '(';
        $values = 'values(';
        foreach ($base_data as $key => $value) {
            $keys .= "`$key`,";
            if (preg_match('/Product Family Description/', $key)) {
                $values .= "'".addslashes($value)."',";
            } else {
                $values .= prepare_mysql($value).",";
            }
        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);
        $sql    = sprintf(
            "INSERT INTO `Product Family Dimension` %s %s", $keys, $values
        );

        // print_r($data);

        if (mysql_query($sql)) {
            $this->id = mysql_insert_id();
            $this->get_data('id', $this->id, false);
            $this->msg = _("Family created");


            $sql = sprintf(
                "INSERT INTO `Product Family Default Currency` (`Product Family Key`) VALUES (%d)", $this->id
            );
            mysql_query($sql);

            $sql = sprintf(
                "INSERT INTO `Product Family Data Dimension` (`Product Family Key`) VALUES (%d)", $this->id
            );
            mysql_query($sql);

            $data_for_history = array(
                'Action'           => 'created',
                'History Abstract' => _(
                    'Family Created'
                ),
                'History Details'  => _('Family')." ".$this->data['Product Family Name']." (".$this->get(
                        'Product Family Code'
                    ).") "._('Created')
            );
            $this->add_history($data_for_history);

            $this->update_similar_families();

            $department->update_families();
            //	$store->update_families();
            $this->update_full_search();
            $this->new = true;

        } else {
            $this->error = true;
            $this->msg   = "$sql Error can not create the family";
            print "$sql\n";
            exit;
        }
    }

    function special_characteristic_if_duplicated($data) {

        $sql = sprintf(
            "SELECT * FROM `Product Family Dimension` WHERE `Product Family Special Characteristic`=%s  AND `Product Family Store Key`=%d ",
            prepare_mysql($data['Product Family Special Characteristic']), $data['Product Family Store Key']
        );

        $result = mysql_query($sql);
        if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $s_char  = $row['Product Family Special Characteristic'];
            $number  = 1;
            $sql     = sprintf(
                "SELECT * FROM `Product Family Dimension` WHERE `Product Family Special Characteristic` LIKE '%s (%%)'  AND `Product Family Store Key`=%d ",
                addslashes($data['Product Family Special Characteristic']), $data['Product Family Store Key']
            );
            $result2 = mysql_query($sql);

            while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {

                if (preg_match(
                    '/\(\d+\)$/', $row2['Product Family Special Characteristic'], $match
                )) {
                    $_number = preg_replace('/[^\d]/', '', $match[0]);
                }
                if ($_number > $number) {
                    $number = $_number;
                }
            }

            $number++;

            return $data['Product Family Special Characteristic']." ($number)";

        } else {
            return $data['Product Family Special Characteristic'];
        }


    }

    function get($key, $options = false) {

        if (!$this->id) {
            return '';
        }

        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }


        if (preg_match(
            '/^(Yesterday|Today|Last|Week|Year|Month|Total|1|6|3).*(Amount|Profit)$/', $key
        )) {

            $amount = 'Product Family '.$key;

            return money($this->data[$amount]);
        }
        if (preg_match(
            '/^(Yesterday|Today|Last|Week|Year|Month|Total|1|6|3).*(Quantity (Ordered|Invoiced|Delivered|)|Invoices|Pending Orders|Customers)$/', $key
        )) {

            $amount = 'Product Family '.$key;

            return number($this->data[$amount]);
        }


        switch ($key) {
            case("Sticky Note"):
                return nl2br($this->data['Product Family Sticky Note']);
                break;
            case('Similar Families'):
                return $this->get_similar_families();
            case ('Sales Correlated Families'):
                return $this->get_sales_correlated_families();
            case ('Sold in Pages'):
                return $this->get_sold_in_pages();
                break;
            case('Price From Info'):
                $min        = 99999999;
                $product_id = '';
                $changed    = false;
                foreach ($this->products as $key => $value) {
                    if ($value['Product Price'] < $min and $value['Product Price'] > 0) {
                        $min        = $value['Product Price'];
                        $product_id = $value['Product Key'];
                        $changed    = true;
                    }
                }

                if ($changed) {
                    $product = new Product($product_id);

                    return '<div class="prod_info">'.$product->get(
                        'Price Formated', 'from'
                    ).'</div>';
                } else {
                    return '';
                }


                break;

            case('Product Family Description Length'):
                return strlen($this->data['Product Family Description']);
                break;
            case('Product Family Description MD5 Hash'):
                return md5($this->data['Product Family Description']);
                break;


            case('Total Products'):

                return $this->get_number_products();
                break;


            case('weeks'):
                if (is_numeric($this->data['first_date'])) {
                    $date1 = date(
                        'Y-m-d', strtotime('@'.$this->data['first_date'])
                    );
                    $day1  = date('N') - 1;
                    $date2 = date('Y-m-d');
                    $days  = datediff('d', $date1, $date2);
                    $weeks = number_weeks($days, $day1);
                } else {
                    $weeks = 0;
                }

                return $weeks;
        }

    }

    function get_similar_families() {
        $similar_families = '';
        $sql              = sprintf(
            "SELECT `Product Family Code`,`Family B Key`,`Weight` FROM `Product Family Semantic Correlation` LEFT JOIN `Product Family Dimension` ON (`Family B Key`=`Product Family Key`) WHERE `Family A Key`=%d ORDER BY `Weight` DESC LIMIT 5",
            $this->id
        );

        $res = mysql_query($sql);
        while ($row = mysql_fetch_array($res)) {
            $similar_families .= sprintf(
                ', <a href="family.php?id=%d">%s</a> (%s)', $row['Family B Key'], $row['Product Family Code'], number($row['Weight'], 2)
            );
        }
        $similar_families = preg_replace('/^, /', '', $similar_families);

        if ($similar_families == '') {
            $similar_families = "<span style='color:#666;font-style:italic;'>"._('No similar families')."</span>";
        }

        return $similar_families;
    }

    function get_sales_correlated_families() {
        $sales_correlated_families = '';
        $sql                       = sprintf(
            "SELECT `Product Family Code`,`Family B Key`,`Correlation` FROM `Product Family Sales Correlation` LEFT JOIN `Product Family Dimension` ON (`Family B Key`=`Product Family Key`) WHERE `Family A Key`=%d ORDER BY `Correlation` DESC LIMIT 5",
            $this->id
        );

        $res = mysql_query($sql);
        while ($row = mysql_fetch_array($res)) {
            $sales_correlated_families .= sprintf(
                ', <a href="family.php?id=%d">%s</a> (%s)', $row['Family B Key'], $row['Product Family Code'], percentage($row['Correlation'], 1)
            );
        }
        $sales_correlated_families = preg_replace(
            '/^, /', '', $sales_correlated_families
        );

        if ($sales_correlated_families == '') {
            $sales_correlated_families
                = "<span style='color:#666;font-style:italic;'>"._(
                    'No correlated families'
                )."</span>";
        }

        return $sales_correlated_families;
    }

    function get_sold_in_pages() {
        $sold_in_pages = '';
        $sql           = sprintf(
            "SELECT P.`Page Key`,`Page Code` FROM  `Page Product Dimension` B LEFT JOIN `Page Store Dimension` P ON (B.`Page Key`=P.`Page Key`) WHERE `Family Key`=%d GROUP BY B.`Page Key` ", $this->id
        );

        $res = mysql_query($sql);
        while ($row = mysql_fetch_array($res)) {
            $sold_in_pages .= sprintf(
                ', <a href="page.php?id=%d">%s</a>', $row['Page Key'], $row['Page Code']
            );
        }
        $sold_in_pages = preg_replace('/^, /', '', $sold_in_pages);

        if ($sold_in_pages == '') {
            $sold_in_pages = "<span style='color:#666;font-style:italic;'>"._(
                    'No in website'
                )."</span>";
        }

        return $sold_in_pages;
    }

    function get_number_products() {
        $number_products = 0;
        $sql             = sprintf(
            "SELECT count(*) AS num FROM `Product Dimension` WHERE `Product Family Key`=%d ", $this->id
        );
        $res             = mysql_query($sql);
        if ($row = mysql_fetch_array($res)) {
            $number_products = $row['num'];

        }

        return $number_products;
    }

    function update_similar_families() {

        $department_codes = array();
        $department_keys  = array();
        $see_also         = array();


        $this_family_name = $this->data['Product Family Name'];
        $department_key   = $this->data['Product Family Main Department Key'];
        $code             = $this->data['Product Family Code'];

        $finger_print = _trim(
            strtolower(
                $this->data['Product Family Code'].' '.$this->data['Product Family Name'].' '.$this->data['Product Family Description']
            )
        );
        $sql          = sprintf(
            "SELECT `Product Family Main Department Key`,`Product Family Key`,`Product Family Name`, `Product Family Code` FROM `Product Family Dimension` WHERE `Product Family Store Key`=%d ",
            $this->data['Product Family Store Key'], $this->id
        );
        $result       = mysql_query($sql);
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            if ($row['Product Family Key'] == $this->id) {
                continue;
            }

            $other_finger_print = strtolower(
                $row['Product Family Code'].' '.$row['Product Family Name']
            );
            $weight             = sentence_similarity(
                    $finger_print, $other_finger_print
                ) / 100;

            //print $weight."\n";
            if (!$row['Product Family Main Department Key'] == $department_key) {
                $weight = $weight / 1.4;
            }

            if ($weight > 0.000001) {
                $sql = sprintf(
                    "INSERT INTO `Product Family Semantic Correlation` VALUES (%d,%d,%f) ON DUPLICATE KEY UPDATE `Weight`=%f  ", $this->id, $row['Product Family Key'], $weight, $weight

                );
                mysql_query($sql);
            }
        }
    }

    function update_full_search() {

        $first_full_search  = $this->data['Product Family Code'].' '.$this->data['Product Family Name'];
        $second_full_search = '';

        if ($this->data['Product Family Main Image'] != 'art/nopic.png') {
            $img = preg_replace(
                '/small/', 'thumbnails', $this->data['Product Family Main Image']
            );
        } else {
            $img = '';
        }

        $description1 = '<b><a href="family.php?id='.$this->id.'">'.$this->data['Product Family Code'].'</a></b>';
        $description2 = $this->data['Product Family Name'];
        $description  = '<table ><tr style="border:none;"><td  class="col1"'.$description1.'</td><td class="col2">'.$description2.'</td></tr></table>';


        $sql = sprintf(
            "INSERT INTO `Search Full Text Dimension` (`Store Key`,`Subject`,`Subject Key`,`First Search Full Text`,`Second Search Full Text`,`Search Result Name`,`Search Result Description`,`Search Result Image`) VALUES  (%s,'Family',%d,%s,%s,%s,%s,%s) ON DUPLICATE KEY
                     UPDATE `First Search Full Text`=%s ,`Second Search Full Text`=%s ,`Search Result Name`=%s,`Search Result Description`=%s,`Search Result Image`=%s",
            $this->data['Product Family Store Key'], $this->id, prepare_mysql($first_full_search), prepare_mysql($second_full_search, false), prepare_mysql($this->data['Product Family Code'], false),
            prepare_mysql($description, false), prepare_mysql($img, false), prepare_mysql($first_full_search), prepare_mysql($second_full_search, false),
            prepare_mysql($this->data['Product Family Code'], false), prepare_mysql($description, false), prepare_mysql($img, false)
        );
        mysql_query($sql);
        //exit($sql);
    }

    function load_acc_data() {
        if ($this->id) {
            $sql = sprintf(
                "SELECT * FROM `Product Family Data Dimension` WHERE `Product Family Key`=%d", $this->id
            );
            $res = mysql_query($sql);
            if ($row = mysql_fetch_assoc($res)) {
                foreach ($row as $key => $value) {
                    $this->data[$key] = $value;
                }

            }
        }
    }

    function update_department($key) {

        if (!is_numeric($key)) {
            $this->error = true;
            $this->msg   = 'Key is not a number';

            return;
        }

        $old_department       = new Department(
            $this->data['Product Family Main Department Key']
        );
        $old_department_label = sprintf(
            "%s, %s", $this->data['Product Family Main Department Code'], $this->data['Product Family Main Department Name']
        );

        //$old_family=new Department($this->data['Product Family Key']);
        $new_department = new Department($key);
        //print_r($new_department);
        $sql = sprintf(
            "UPDATE `Product Family Dimension` SET `Product Family Main Department Key`=%d, `Product Family Main Department Code`=%s, `Product Family Main Department Name`=%s WHERE `Product Family Key`=%d",
            $key, prepare_mysql($new_department->data['Product Department Code']), prepare_mysql($new_department->data['Product Department Name']), $this->id
        );


        mysql_query($sql);

        $sql = sprintf(
            "UPDATE `Product Dimension` SET `Product Main Department Key`=%d, `Product Main Department Code`=%s, `Product Main Department Name`=%s WHERE `Product Family Key`=%d", $key,
            prepare_mysql($new_department->data['Product Department Code']), prepare_mysql($new_department->data['Product Department Name']), $this->id
        );
        mysql_query($sql);


        //$old_family->update_product_data();
        $new_department->update_product_data();
        $new_department->update_families();
        $old_department->update_product_data();
        $old_department->update_families();
        $this->data['Product Family Key'] = $key;
        $this->new_value                  = $key;
        $this->new_data
                                          = array(
            'code' => $new_department->data['Product Department Code'],
            'name' => $new_department->data['Product Department Name'],
            'key'  => $new_department->id
        );
        $this->updated                    = true;
        $new_department_label             = sprintf(
            "%s, %s", $new_department->data['Product Department Code'], $new_department->data['Product Department Name']
        );


        $this->add_history(
            array(
                'Indirect Object'  => 'Family Department',
                'History Abstract' => _("Family's department changed").' ('.$new_department->data['Product Department Code'].', '.$new_department->data['Product Department Name'].')',
                'History Details'  => _("Family's department changed")."; ".$old_department_label." &rarr; ".$new_department_label
            )
        );


    }

    function update_web_state() {
        $web_state = 'Empty';
        $sql       = sprintf(
            "SELECT count(*) AS num FROM `Product Dimension` WHERE `Product Family Key`=%d AND `Product Web State` IN ('For Sale', 'Out of Stock')  ", $this->id
        );

        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            if ($row['num'] > 0) {
                $web_state = 'With Products For Sale';

            }
        }

        include_once 'class.Page.php';
        foreach ($this->get_pages_keys() as $page_key) {

            if ($web_state == 'With Products For Sale') {
                $_state = 'Online';
            } else {
                $_state = 'Offline';
            }

            $page = new Page($page_key);
            $page->update(array('Page State' => $_state), 'no_history');
        }


        $this->update_field_switcher(
            'Product Family Web Products', $web_state, 'no_history'
        );

    }

    function get_pages_keys() {
        $page_keys = array();
        $sql       = sprintf(
            "SELECT `Page Key` FROM `Page Store Dimension` WHERE `Page Store Section Type`='Family' AND  `Page Parent Key`=%d", $this->id
        );
        $res       = mysql_query($sql);
        while ($row = mysql_fetch_array($res)) {
            $page_keys[] = $row['Page Key'];
        }

        return $page_keys;
    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {
        switch ($field) {

            case('Store Sticky Note'):
                $this->update_field_switcher('Sticky Note', $value);
                break;
            case('Sticky Note'):
                $this->update_field(
                    'Product Family '.$field, $value, 'no_null'
                );
                $this->new_value = html_entity_decode($this->new_value);
                break;
            case('special_char'):
            case('Product Family Special Characteristic'):
                $this->update_field(
                    'Product Family Special Characteristic', $value
                );
                break;
            case('code'):
                $this->update_code($value);
                break;
            case('name'):
                $this->update_name($value);
                break;
            case('sales_type'):
                $this->update_sales_type($value);
                break;
            case('description'):
            case('Product Family Description'):

                $this->update_description($value);
                break;
            default:
                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {
                    if ($value != $this->data[$field]) {
                        $this->update_field($field, $value, $options);
                    }
                }
        }
    }

    function update_code($value) {
        if ($value == $this->data['Product Family Code']) {
            $this->updated   = true;
            $this->new_value = $value;

            return;

        }

        if ($value == '') {
            $this->msg = _('Error: Wrong code (empty)');

            return;
        }
        if (!(strtolower($value) == strtolower(
                $this->data['Product Family Code']
            ) and $value != $this->data['Product Family Code'])
        ) {

            $sql = sprintf(
                "SELECT count(*) AS num FROM `Product Family Dimension` WHERE `Product Family Store Key`=%d AND `Product Family Code`=%s  COLLATE utf8_general_ci ",
                $this->data['Product Family Store Key'], prepare_mysql($value)
            );
            $res = mysql_query($sql);
            $row = mysql_fetch_array($res);
            if ($row['num'] > 0) {
                $this->msg = _("Error: Another family with the same code");

                return;
            }
        }
        $old_value = $this->get('Product Family Code');
        $sql       = sprintf(
            "UPDATE `Product Family Dimension` SET `Product Family Code`=%s WHERE `Product Family Key`=%d ", prepare_mysql($value), $this->id
        );
        if (mysql_query($sql)) {
            $this->msg       = _('Family code updated');
            $this->updated   = true;
            $this->new_value = $value;

            $this->data['Product Family Code'] = $value;
            $this->update_full_search();

            $sql = sprintf(
                "UPDATE `Product Dimension` SET `Product Family Code`=%s WHERE `Product Family Key`=%d ", prepare_mysql($value), $this->id
            );
            mysql_query($sql);


            $data_for_history = array(
                'Indirect Object'  => 'Product Family Code',
                'History Abstract' => _('Product family Code changed').' ('.$this->get('Product Family Code').')',
                'History Details'  => _('Family')." ".$this->data['Product Family Name']." "._(
                        'changed code from'
                    ).' '.$old_value." "._('to').' '.$this->get(
                        'Product Family Code'
                    )
            );
            $this->add_history($data_for_history);

        } else {
            $this->msg = "Error: Family code could not be updated";

            $this->updated = false;

        }
    }

    function update_name($value) {
        if ($value == $this->data['Product Family Name']) {
            $this->updated   = true;
            $this->new_value = $value;

            return;

        }

        if ($value == '') {
            $this->msg = _('Error: Wrong name (empty)');

            return;
        }
        if (!(strtolower($value) == strtolower(
                $this->data['Product Family Name']
            ) and $value != $this->data['Product Family Name'])
        ) {
            $sql = sprintf(
                "SELECT count(*) AS num FROM `Product Family Dimension` WHERE `Product Family Store Key`=%d AND `Product Family Name`=%s  COLLATE utf8_general_ci",
                $this->data['Product Family Store Key'], prepare_mysql($value)
            );
            $res = mysql_query($sql);
            $row = mysql_fetch_array($res);
            if ($row['num'] > 0) {
                $this->msg = _("Error: Another family with the same name");

                return;
            }
        }
        $old_value = $this->get('Product Family Name');
        $sql       = sprintf(
            "UPDATE `Product Family Dimension` SET `Product Family Name`=%s WHERE `Product Family Key`=%d ", prepare_mysql($value), $this->id
        );
        if (mysql_query($sql)) {
            $this->msg       = _('Family name updated');
            $this->updated   = true;
            $this->new_value = $value;

            $this->data['Product Family Name'] = $value;
            $this->update_full_search();

            $sql = sprintf(
                "UPDATE `Product Dimension` SET `Product Family Name`=%s WHERE `Product Family Key`=%d ", prepare_mysql($value), $this->id
            );
            mysql_query($sql);

            $this->add_history(
                array(
                    'Indirect Object'  => 'Product Family Name',
                    'History Abstract' => ('Product Family Name Changed').' ('.$this->get('Product Family Name').')',
                    'History Details'  => _('Product Family')." ("._('Code').":".$this->data['Product Family Code'].") "._(
                            'name changed from'
                        ).' '.$old_value." "._('to').' '.$this->get(
                            'Product Family Name'
                        )
                )
            );


        } else {
            $this->msg = "Error: Family name could not be updated";

            $this->updated = false;

        }
    }

    function update_sales_type($value) {
        if ($value == 'Public Sale' or $value == 'Private Sale' or $value == 'Not For Sale') {
            $sales_state = $value;

            $sql = sprintf(
                "UPDATE `Product Family Dimension` SET `Product Family Sales Type`=%s WHERE `Product Family Key`=%d ", prepare_mysql($sales_state), $this->id
            );
            //print $sql;
            if (mysql_query($sql)) {

                $this->msg     = _('Family Sales Type updated');
                $this->updated = true;

                $this->new_value = $value;

                return;
            } else {
                $this->msg     = _(
                    "Error: Family sales type could not be updated "
                );
                $this->updated = false;

                return;
            }
        } else {
            $this->msg = _("Error: wrong value")." [Sales Type] ($value)";
        }
        $this->updated = false;
    }

    function update_description($description) {

        $old_description = $this->data['Product Family Description'];
        $this->update_field(
            'Product Family Description', $description, 'nohistory'
        );
        if ($this->updated) {

            // TODO: highlight the changes with previos description, similar to git maybe


            $history_data = array(
                'History Abstract' => _('Product Family Description Changed'),
                'History Details'  => ''
                //$rendered_difference

                ,
                'Indirect Object'  => 'Product Family Description'
            );
            // print_r($history_data);
            $this->add_history($history_data);


        }

        foreach ($this->get_pages_keys() as $page_key) {
            $page = new Page($page_key);

            if ($page->data['Page Type'] == 'Store' and $page->data['Page Store Content Display Type'] == 'Template') {
                $page->update_store_search();
            }
        }


    }

    function delete() {
        $this->deleted = false;
        $this->update_product_data();

        if ($this->get('Total Products') == 0) {
            $store           = new Store(
                $this->data['Product Family Store Key']
            );
            $department_keys = $this->get_department_keys();
            $sql             = sprintf(
                "DELETE FROM `Product Family Dimension` WHERE `Product Family Key`=%d", $this->id
            );

            if (mysql_query($sql)) {

                $sql = sprintf(
                    "DELETE FROM `Product Family Department Bridge` WHERE `Product Family Key`=%d", $this->id
                );
                mysql_query($sql);
                foreach ($department_keys as $dept_key) {

                    $department = new Department($dept_key);
                    $department->update_product_data();
                }
                $store->update_product_data();
                $this->deleted = true;

            } else {

                $this->msg = _('Error: can not delete family');

                return;
            }

            $this->deleted = true;
        } else { //Family has associated Products

            $store           = new Store(
                $this->data['Product Family Store Key']
            );
            $department_keys = $this->get_department_keys();
            $sql             = sprintf(
                "SELECT * FROM `Product Dimension` WHERE `Product Family Key` = %d", $this->id
            );
            $res             = mysql_query($sql);
            while ($row = mysql_fetch_assoc($res)) {
                $product = new Product($row['Product ID']);
                $product->update(
                    'Product Family Key', $store->data['Store No Products Family Key']
                );

            }


            $sql = sprintf(
                "DELETE FROM `Product Family Dimension` WHERE `Product Family Key`=%d", $this->id
            );
            mysql_query($sql);


            if (mysql_affected_rows() > 0) {
                $sql = sprintf(
                    "DELETE FROM `Product Family Department Bridge` WHERE `Product Family Key`=%d", $this->id
                );
                mysql_query($sql);

                foreach ($department_keys as $dept_key) {
                    $department = new Department($dept_key);
                    $department->update_product_data();
                }


                $store->update_product_data();
                $this->deleted = true;
            } else {
                $this->deleted_msg = 'Error family can not be deleted';
            }


        }
    }

    function update_product_data() {


        $availability = 'No Applicable';
        $sales_type   = 'No Applicable';
        $in_process   = 0;
        $public_sale  = 0;
        $private_sale = 0;
        $discontinued = 0;
        $not_for_sale = 0;

        $historic                = 0;
        $availability_optimal    = 0;
        $availability_low        = 0;
        $availability_critical   = 0;
        $availability_outofstock = 0;
        $availability_unknown    = 0;
        $availability_surplus    = 0;

        $sql = sprintf(
            "SELECT
		sum(if(`Product Stage`='In process',1,0)) AS in_process,

		 		 sum(if(`Product Main Type`='Historic',1,0)) AS historic,

		 sum(if(`Product Main Type`='Discontinued',1,0)) AS discontinued,
		 sum(if(`Product Main Type`='NoSale',1,0)) AS not_for_sale,
		 sum(if(`Product Main Type`='Sale',1,0)) AS public_sale,
		 sum(if(`Product Main Type`='Private',1,0)) AS private_sale




		FROM
		`Product Dimension` WHERE `Product Family Key`=%d  ", $this->id
        );


        $result = mysql_query($sql);

        //print "$sql\n";
        if ($row = mysql_fetch_assoc($result)) {
            //print_r($row);
            $in_process   = $row['in_process'];
            $public_sale  = $row['public_sale'];
            $private_sale = $row['private_sale'];
            $discontinued = $row['discontinued'];
            $not_for_sale = $row['not_for_sale'];
            $historic     = $row['historic'];

        }

        $sql = sprintf(
            "SELECT




		sum(if(`Product Availability State`='Error',1,0)) AS availability_unknown,
		sum(if(`Product Availability State`='Normal',1,0)) AS availability_optimal,
		sum(if(`Product Availability State`='Low',1,0)) AS availability_low,
		sum(if(`Product Availability State`='Excess',1,0)) AS availability_surplus,sum(if(`Product Availability State`='VeryLow',1,0)) AS availability_critical,sum(if(`Product Availability State`='OutofStock',1,0)) AS availability_outofstock

		FROM
		`Product Dimension` WHERE `Product Family Key`=%d AND `Product Main Type`='Sale' ", $this->id
        );


        $result = mysql_query($sql);
        if ($row = mysql_fetch_assoc($result)) {

            //print_r($row);
            $availability_optimal    = $row['availability_optimal'];
            $availability_low        = $row['availability_low'];
            $availability_critical   = $row['availability_critical'];
            $availability_outofstock = $row['availability_outofstock'];
            $availability_unknown    = $row['availability_unknown'];
            $availability_surplus    = $row['availability_surplus'];
        }


        if ($public_sale > 0) {
            $record_type = 'Normal';


        } elseif ($private_sale > 0) {
            $record_type = 'Private';

        } elseif ($discontinued > 0) {
            $record_type = 'Discontinued';

        } else {

            $record_type = 'Nosale';
        }


        $sql = sprintf(
            "UPDATE `Product Family Dimension` SET `Product Family Record Type`=%s,`Product Family In Process Products`=%d,`Product Family For Public Sale Products`=%d ,`Product Family For Private Sale Products`=%d,`Product Family Discontinued Products`=%d ,`Product Family Not For Sale Products`=%d ,`Product Family Historic Products`=%d, `Product Family Optimal Availability Products`=%d , `Product Family Low Availability Products`=%d ,`Product Family Critical Availability Products`=%d ,`Product Family Out Of Stock Products`=%d,`Product Family Unknown Stock Products`=%d ,`Product Family Surplus Availability Products`=%d  WHERE `Product Family Key`=%d  ",
            prepare_mysql($record_type), $in_process, $public_sale, $private_sale,

            $discontinued, $not_for_sale, $historic,

            $availability_optimal, $availability_low, $availability_critical,

            $availability_outofstock, $availability_unknown, $availability_surplus,

            // prepare_mysql($sales_type),
            // prepare_mysql($availability),
            $this->id
        );

        mysql_query($sql);
        //print "$sql\n\n";


        $this->get_data('id', $this->id);
    }

    function get_department_keys() {
        $department_keys = array();
        $sql             = sprintf(
            "SELECT `Product Department Key` FROM `Product Family Department Bridge` WHERE `Product Family Key`=%d", $this->id
        );
        $res             = mysql_query($sql);
        while ($row = mysql_fetch_array($res)) {
            $department_keys[] = $row['Product Department Key'];
        }

        return $department_keys;
    }

    function add_product($product_id, $args = false) {

        $product = new Product($product_id);
        if ($product->id) {
            $sql = sprintf(
                "UPDATE  `Product Dimension` SET `Product Family Key`=%d ,`Product Family Code`=%s,`Product Family Name`=%s WHERE `Product Key`=%s    ", $this->id,
                prepare_mysql($this->get('Product Family Code')), prepare_mysql($this->get('Product Family Name')), $product->id
            );
            mysql_query($sql);
            $this->update_product_data();
            // print "$sql\n";
        }
    }

    function update_up_today_sales() {

        $this->update_sales_from_invoices('Today');
        $this->update_sales_from_invoices('Week To Day');
        $this->update_sales_from_invoices('Month To Day');
        $this->update_sales_from_invoices('Year To Day');

        $this->update_sales_from_invoices('Total');
    }

    function update_sales_from_invoices($interval) {


        $to_date = '';

        list($db_interval, $from_date, $to_date, $from_date_1yb, $to_1yb)
            = calculate_interval_dates($interval);


        setlocale(LC_ALL, 'en_GB');

        //   print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";

        $this->data["Product Family $db_interval Acc Invoiced Discount Amount"]
            = 0;
        $this->data["Product Family $db_interval Acc Invoiced Gross Amount"]
            = 0;

        $this->data["Product Family $db_interval Acc Invoiced Amount"]    = 0;
        $this->data["Product Family $db_interval Acc Invoices"]           = 0;
        $this->data["Product Family $db_interval Acc Profit"]             = 0;
        $this->data["Product Family $db_interval Acc Customers"]          = 0;
        $this->data["Product Family $db_interval Acc Quantity Ordered"]   = 0;
        $this->data["Product Family $db_interval Acc Quantity Invoiced"]  = 0;
        $this->data["Product Family $db_interval Acc Quantity Delivered"] = 0;
        $this->data["Product Family DC $db_interval Acc Invoiced Amount"] = 0;
        $this->data["Product Family DC $db_interval Acc Invoiced Discount Amount"]
                                                                          = 0;
        $this->data["Product Family DC $db_interval Acc Invoiced Gross Amount"]
                                                                          = 0;

        $this->data["Product Family DC $db_interval Acc Profit"] = 0;

        $sql = sprintf(
            "SELECT  sum(`Shipped Quantity`) AS qty_delivered,sum(`Order Quantity`) AS qty_ordered,sum(`Invoice Quantity`) AS qty_invoiced ,count(DISTINCT `Customer Key`)AS customers,count(DISTINCT `Invoice Key`) AS invoices,IFNULL(sum(`Invoice Transaction Total Discount Amount`),0) AS discounts,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) net ,sum(`Invoice Transaction Gross Amount`) AS gross  ,sum(`Cost Supplier`+`Cost Storing`+`Cost Handing`+`Cost Shipping`) AS total_cost,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`) AS dc_discounts,sum((`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)*`Invoice Currency Exchange Rate`) dc_net,sum((`Invoice Transaction Gross Amount`)*`Invoice Currency Exchange Rate`) dc_gross  ,sum((`Cost Supplier`+`Cost Storing`+`Cost Handing`+`Cost Shipping`)*`Invoice Currency Exchange Rate`) AS dc_total_cost FROM `Order Transaction Fact` WHERE  `Current Dispatching State`='Dispatched' AND `Product Family Key`=%d %s %s",
            $this->id,

            ($from_date ? sprintf(
                'and `Invoice Date`>%s', prepare_mysql($from_date)
            ) : ''),

            ($to_date ? sprintf(
                'and `Invoice Date`<%s', prepare_mysql($to_date)
            ) : '')

        );

        $result = mysql_query($sql);

        //  print $sql."\n\n";
        if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data["Product Family $db_interval Acc Invoiced Discount Amount"]
                = $row["discounts"];
            $this->data["Product Family $db_interval Acc Invoiced Amount"]
                = $row["net"];
            $this->data["Product Family $db_interval Acc Invoiced Gross Amount"]
                = $row["gross"];

            $this->data["Product Family $db_interval Acc Invoices"]
                = $row["invoices"];
            $this->data["Product Family $db_interval Acc Profit"]
                = $row["net"] - $row['total_cost'];
            $this->data["Product Family $db_interval Acc Customers"]
                = $row["customers"];
            $this->data["Product Family $db_interval Acc Quantity Ordered"]
                = $row["qty_ordered"];
            $this->data["Product Family $db_interval Acc Quantity Invoiced"]
                = $row["qty_invoiced"];
            $this->data["Product Family $db_interval Acc Quantity Delivered"]
                = $row["qty_delivered"];

            $this->data["Product Family DC $db_interval Acc Invoiced Amount"]
                = $row["dc_net"];
            $this->data["Product Family DC $db_interval Acc Invoiced Discount Amount"]
                = $row["dc_discounts"];
            $this->data["Product Family DC $db_interval Acc Invoiced Gross Amount"]
                = $row["dc_gross"];

            $this->data["Product Family DC $db_interval Acc Profit"]
                = $row["dc_net"] - $row['dc_total_cost'];
        }


        /*
$sql="select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF   where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')  and  `Product Family Key`=".$this->id;
		$result=mysql_query($sql);
		$pending_orders=0;
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$pending_orders=$row['pending_orders'];
		}
*/

        $sql = sprintf(
            "update `Product Family Dimension` set
                     `Product Family $db_interval Acc Invoiced Discount Amount`=%.2f,
                                          `Product Family $db_interval Acc Invoiced Gross Amount`=%.2f,

                     `Product Family $db_interval Acc Invoiced Amount`=%.2f,
                     `Product Family $db_interval Acc Invoices`=%d,
                     `Product Family $db_interval Acc Profit`=%.2f,
                      `Product Family $db_interval Acc Customers`=%d,
                       `Product Family $db_interval Acc Quantity Ordered`=%d,
                       `Product Family $db_interval Acc Quantity Invoiced`=%d,
                       `Product Family $db_interval Acc Quantity Delivered`=%d
                     where `Product Family Key`=%d ", $this->data["Product Family $db_interval Acc Invoiced Discount Amount"], $this->data["Product Family $db_interval Acc Invoiced Gross Amount"]

            , $this->data["Product Family $db_interval Acc Invoiced Amount"], $this->data["Product Family $db_interval Acc Invoices"], $this->data["Product Family $db_interval Acc Profit"],
            $this->data["Product Family $db_interval Acc Customers"], $this->data["Product Family $db_interval Acc Quantity Ordered"], $this->data["Product Family $db_interval Acc Quantity Invoiced"],
            $this->data["Product Family $db_interval Acc Quantity Delivered"]

            , $this->id
        );

        mysql_query($sql);
        //print $sql."\n\n";

        $sql = sprintf(
            "update `Product Family Default Currency` set
                             `Product Family DC $db_interval Acc Invoiced Discount Amount`=%.2f,
                                                          `Product Family DC $db_interval Acc Invoiced Gross Amount`=%.2f,

                             `Product Family DC $db_interval Acc Invoiced Amount`=%.2f,
                             `Product Family DC $db_interval Acc Profit`=%.2f
                             where `Product Family Key`=%d ", $this->data["Product Family DC $db_interval Acc Invoiced Discount Amount"],
            $this->data["Product Family DC $db_interval Acc Invoiced Gross Amount"]

            , $this->data["Product Family DC $db_interval Acc Invoiced Amount"], $this->data["Product Family DC $db_interval Acc Profit"], $this->id
        );

        mysql_query($sql);

        //print "XX: $interval $from_date_1yb\n\n";

        if ($from_date_1yb) {
            $this->data["Product Family $db_interval Acc 1YB Invoices"] = 0;
            $this->data["Product Family $db_interval Acc 1YB Invoiced Discount Amount"]
                                                                        = 0;
            $this->data["Product Family $db_interval Acc 1YB Invoiced Amount"]
                                                                        = 0;
            $this->data["Product Family $db_interval Acc 1YB Profit"]
                                                                        = 0;
            $this->data["Product Family $db_interval Acc 1YB Invoiced Delta"]
                                                                        = 0;


            $sql = sprintf(
                "SELECT count(DISTINCT `Invoice Key`) AS invoices,IFNULL(sum(`Invoice Transaction Total Discount Amount`),0) AS discounts,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) net  ,sum(`Cost Supplier`+`Cost Storing`+`Cost Handing`+`Cost Shipping`) AS total_cost ,
                         sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`) AS dc_discounts,sum((`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)*`Invoice Currency Exchange Rate`) dc_net  ,sum((`Cost Supplier`+`Cost Storing`+`Cost Handing`+`Cost Shipping`)*`Invoice Currency Exchange Rate`) AS dc_total_cost FROM `Order Transaction Fact` WHERE `Current Dispatching State`='Dispatched' AND `Product Family Key`=%d AND `Invoice Date`>=%s %s",
                $this->id, prepare_mysql($from_date_1yb), ($to_1yb ? sprintf(
                'and `Invoice Date`<%s', prepare_mysql($to_1yb)
            ) : '')

            );


            // print "$sql\n\n";
            $result = mysql_query($sql);
            if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $this->data["Product Family $db_interval Acc 1YB Invoiced Discount Amount"]
                    = $row["discounts"];
                $this->data["Product Family $db_interval Acc 1YB Invoiced Amount"]
                    = $row["net"];
                $this->data["Product Family $db_interval Acc 1YB Invoiced Delta"]
                    = ($row["net"] == 0 ? -1000000 : $this->data["Product Family $db_interval Acc Invoiced Amount"] / $row["net"]);
                $this->data["Product Family $db_interval Acc 1YB Invoices"]
                    = $row["invoices"];
                $this->data["Product Family $db_interval Acc 1YB Profit"]
                    = $row["net"] - $row['total_cost'];

            }

            $sql = sprintf(
                "update `Product Family Dimension` set
                         `Product Family $db_interval Acc 1YB Invoiced Discount Amount`=%.2f,
                         `Product Family $db_interval Acc 1YB Invoiced Amount`=%.2f,
                                                 `Product Family $db_interval Acc 1YB Invoiced Delta`=%f,

                         `Product Family $db_interval Acc 1YB Invoices`=%.2f,
                         `Product Family $db_interval Acc 1YB Profit`=%.2f
                         where `Product Family Key`=%d ", $this->data["Product Family $db_interval Acc 1YB Invoiced Discount Amount"],
                $this->data["Product Family $db_interval Acc 1YB Invoiced Amount"], $this->data["Product Family $db_interval Acc 1YB Invoiced Delta"]

                , $this->data["Product Family $db_interval Acc 1YB Invoices"], $this->data["Product Family $db_interval Acc 1YB Profit"], $this->id
            );

            mysql_query($sql);
            //print "$sql\n";


        }

        return array(
            substr($from_date, -19, -9),
            date("Y-m-d")
        );

    }

    function update_last_period_sales() {

        $this->update_sales_from_invoices('Yesterday');
        $this->update_sales_from_invoices('Last Week');
        $this->update_sales_from_invoices('Last Month');

    }

    function update_interval_sales() {

        $this->update_sales_from_invoices('3 Year');
        $this->update_sales_from_invoices('1 Year');
        $this->update_sales_from_invoices('6 Month');
        $this->update_sales_from_invoices('1 Quarter');
        $this->update_sales_from_invoices('1 Month');
        $this->update_sales_from_invoices('10 Day');
        $this->update_sales_from_invoices('1 Week');

    }

    function update_product_price_data() {
        $from_price         = 0;
        $price_multiplicity = 0;
        $sql                = sprintf(
            "SELECT min(`Product Price`) AS from_price ,count(DISTINCT `Product Price`) AS price_multiplicity FROM `Product Dimension` WHERE `Product Family Key`=%d AND `Product Sales Type`='Public Sale'",
            $this->id
        );

        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {
            $from_price         = $row['from_price'];
            $price_multiplicity = $row['price_multiplicity'];
        }
        $sql = sprintf(
            "UPDATE `Product Family Dimension` SET `Product Family From Price`=%.2f,`Product Family Product Price Multiplicity`=%d WHERE `Product Family Key`=%d", $from_price, $price_multiplicity,
            $this->id
        );
        //print "$sql\n";
        mysql_query($sql);

    }

    function update_customers_data() {
        $number_active_customers              = 0;
        $number_active_customers_more_than_75 = 0;
        $number_active_customers_more_than_50 = 0;
        $number_active_customers_more_than_25 = 0;
        $number_losing_customers              = 0;
        $number_losing_customers_more_than_75 = 0;
        $number_losing_customers_more_than_50 = 0;
        $number_losing_customers_more_than_25 = 0;
        $number_lost_customers                = 0;
        $number_lost_customers_more_than_75   = 0;
        $number_lost_customers_more_than_50   = 0;
        $number_lost_customers_more_than_25   = 0;

        $sql = sprintf(
            " SELECT
		(SELECT sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)  FROM  `Order Transaction Fact`  WHERE  `Order Transaction Fact`.`Customer Key`=OTF.`Customer Key` ) AS total_amount  ,
		 sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) AS amount,
		 OTF.`Customer Key` FROM `Order Transaction Fact`  OTF  LEFT JOIN `Customer Dimension` C ON (C.`Customer Key`=OTF.`Customer Key`)WHERE `Product Family Key`=%d AND `Customer Type by Activity` IN ('Active') AND `Invoice Transaction Gross Amount`>0
		  GROUP BY  OTF.`Customer Key`", $this->id
        );
        // print "$sql\n";
        $result = mysql_query($sql);
        while ($row = mysql_fetch_assoc($result)) {
            $number_active_customers++;
            if ($row['total_amount'] != 0 and ($row['amount'] / $row['total_amount']) > 0.75) {
                $number_active_customers_more_than_75++;
            }
            if ($row['total_amount'] != 0 and ($row['amount'] / $row['total_amount']) > 0.5) {
                $number_active_customers_more_than_50++;
            }
            if ($row['total_amount'] != 0 and ($row['amount'] / $row['total_amount']) > 0.25) {
                $number_active_customers_more_than_25++;
            }

        }

        $this->data['Product Family Active Customers']
            = $number_active_customers;
        $this->data['Product Family Active Customers More 0.75 Share']
            = $number_active_customers_more_than_75;
        $this->data['Product Family Active Customers More 0.5 Share']
            = $number_active_customers_more_than_50;
        $this->data['Product Family Active Customers More 0.25 Share']
            = $number_active_customers_more_than_25;


        $sql = sprintf(
            " SELECT
		(SELECT sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)  FROM  `Order Transaction Fact`  WHERE  `Order Transaction Fact`.`Customer Key`=OTF.`Customer Key` ) AS total_amount  ,
		 sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) AS amount,
		 OTF.`Customer Key` FROM `Order Transaction Fact`  OTF  LEFT JOIN `Customer Dimension` C ON (C.`Customer Key`=OTF.`Customer Key`)WHERE `Product Family Key`=%d AND `Customer Type by Activity` IN ('Losing') AND `Invoice Transaction Gross Amount`>0
		  GROUP BY  OTF.`Customer Key`", $this->id
        );
        // print "$sql\n";
        $result = mysql_query($sql);
        while ($row = mysql_fetch_assoc($result)) {
            $number_losing_customers++;
            if ($row['total_amount'] != 0 and ($row['amount'] / $row['total_amount']) > 0.75) {
                $number_losing_customers_more_than_75++;
            }
            if ($row['total_amount'] != 0 and ($row['amount'] / $row['total_amount']) > 0.5) {
                $number_losing_customers_more_than_50++;
            }
            if ($row['total_amount'] != 0 and ($row['amount'] / $row['total_amount']) > 0.25) {
                $number_losing_customers_more_than_25++;
            }

        }

        $this->data['Product Family Losing Customers']
            = $number_losing_customers;
        $this->data['Product Family Losing Customers More 0.75 Share']
            = $number_losing_customers_more_than_75;
        $this->data['Product Family Losing Customers More 0.5 Share']
            = $number_losing_customers_more_than_50;
        $this->data['Product Family Losing Customers More 0.25 Share']
            = $number_losing_customers_more_than_25;


        $sql = sprintf(
            " SELECT
		(SELECT sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)  FROM  `Order Transaction Fact`  WHERE  `Order Transaction Fact`.`Customer Key`=OTF.`Customer Key` ) AS total_amount  ,
		 sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) AS amount,
		 OTF.`Customer Key` FROM `Order Transaction Fact`  OTF  LEFT JOIN `Customer Dimension` C ON (C.`Customer Key`=OTF.`Customer Key`)WHERE `Product Family Key`=%d AND `Customer Type by Activity` IN ('Active') AND `Invoice Transaction Gross Amount`>0
		  GROUP BY  OTF.`Customer Key`", $this->id
        );
        // print "$sql\n";
        $result = mysql_query($sql);
        while ($row = mysql_fetch_assoc($result)) {
            $number_lost_customers++;
            if ($row['total_amount'] != 0 and ($row['amount'] / $row['total_amount']) > 0.75) {
                $number_lost_customers_more_than_75++;
            }
            if ($row['total_amount'] != 0 and ($row['amount'] / $row['total_amount']) > 0.5) {
                $number_lost_customers_more_than_50++;
            }
            if ($row['total_amount'] != 0 and ($row['amount'] / $row['total_amount']) > 0.25) {
                $number_lost_customers_more_than_25++;
            }

        }

        $this->data['Product Family Lost Customers'] = $number_lost_customers;
        $this->data['Product Family Lost Customers More 0.75 Share']
                                                     = $number_lost_customers_more_than_75;
        $this->data['Product Family Lost Customers More 0.5 Share']
                                                     = $number_lost_customers_more_than_50;
        $this->data['Product Family Lost Customers More 0.25 Share']
                                                     = $number_lost_customers_more_than_25;


        $sql = sprintf(
            "UPDATE `Product Family Dimension` SET 
		`Product Family Active Customers`=%d ,
		`Product Family Active Customers More 0.75 Share`=%d,
		`Product Family Active Customers More 0.5 Share`=%d,
		`Product Family Active Customers More 0.25 Share`=%d,
		
		`Product Family Losing Customers`=%d ,
		`Product Family Losing Customers More 0.75 Share`=%d,
		`Product Family Losing Customers More 0.5 Share`=%d,
		`Product Family Losing Customers More 0.25 Share`=%d,
		
		`Product Family Lost Customers`=%d ,
		`Product Family Lost Customers More 0.75 Share`=%d,
		`Product Family Lost Customers More 0.5 Share`=%d,
		`Product Family Lost Customers More 0.25 Share`=%d

		WHERE `Product Family Key`=%d  ", $this->data['Product Family Active Customers'], $this->data['Product Family Active Customers More 0.75 Share'],
            $this->data['Product Family Active Customers More 0.5 Share'], $this->data['Product Family Active Customers More 0.25 Share'], $this->data['Product Family Losing Customers'],
            $this->data['Product Family Losing Customers More 0.75 Share'], $this->data['Product Family Losing Customers More 0.5 Share'],
            $this->data['Product Family Losing Customers More 0.25 Share'], $this->data['Product Family Lost Customers'], $this->data['Product Family Lost Customers More 0.75 Share'],
            $this->data['Product Family Lost Customers More 0.5 Share'], $this->data['Product Family Lost Customers More 0.25 Share'], $this->id
        );
        //	 print "$sql\n";
        mysql_query($sql);

    }

    function update_product_data_old() {


        $sql = sprintf(
            "SELECT  sum(if(`Product Availability Type`='Discontinued'  AND `Product Availability`>0   ,1,0)) AS to_be_discontinued ,
                     sum(if(`Product Main Type`='Historic',1,0)) AS historic ,
                     sum(if(`Product Main Type`='Discontinued',1,0) ) AS discontinued,
                     sum(if(`Product Main Type`='Private',1,0) ) AS private_sale,
                     sum(if(`Product Main Type`='NoSale',1,0) ) AS not_for_sale,
                     sum(if(`Product Main Type`='Sale' ,1,0)) AS public_sale,
                     sum(if(`Product Stage`='In process',1,0)) AS in_process ,
                     sum(if(`Product Availability State`='Unknown',1,0)) AS availability_unknown,
                     sum(if(`Product Availability State`='Optimal',1,0)) AS availability_optimal,
                     sum(if(`Product Availability State`='Low',1,0)) AS availability_low,
                     sum(if(`Product Availability State`='Surplus',1,0)) AS availability_surplus,
                     sum(if(`Product Availability State`='Critical',1,0)) AS availability_critical,
                     sum(if(`Product Availability State`='Out Of Stock',1,0)) AS availability_outofstock
                     FROM `Product Dimension` WHERE `Product Family Key`=%d", $this->id
        );
        //  print $sql;
        //exit;

        $availability = 'No Applicable';
        $sales_type   = 'No Applicable';
        $historic     = 0;

        $in_process   = 0;
        $public_sale  = 0;
        $private_sale = 0;
        $discontinued = 0;
        $not_for_sale = 0;

        $availability_optimal    = 0;
        $availability_low        = 0;
        $availability_critical   = 0;
        $availability_outofstock = 0;
        $availability_unknown    = 0;
        $availability_surplus    = 0;
        $to_be_discontinued      = 0;

        $result = mysql_query($sql);


        if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            //   print_r($row);
            $to_be_discontinued = $row['to_be_discontinued'];
            $historic           = $row['historic'];

            $in_process   = $row['in_process'];
            $public_sale  = $row['public_sale'];
            $private_sale = $row['private_sale'];
            $discontinued = $row['discontinued'];
            $not_for_sale = $row['not_for_sale'];

            $availability_optimal    = $row['availability_optimal'];
            $availability_low        = $row['availability_low'];
            $availability_critical   = $row['availability_critical'];
            $availability_outofstock = $row['availability_outofstock'];
            $availability_unknown    = $row['availability_unknown'];
            $availability_surplus    = $row['availability_surplus'];


            if ($public_sale == 0 and $private_sale == 0 and $not_for_sale > 0) {
                $sales_type = 'Not for Sale';
            } elseif ($public_sale == 0 and $private_sale > 0) {
                $sales_type = 'Private Sale Only';
            } elseif ($public_sale > 0) {
                $sales_type = 'Public Sale';
            } else {
                $sales_type = 'Unknown';
            }

            $avalilable_products = $availability_optimal + $availability_low + $availability_critical + $availability_surplus + $availability_unknown;
            if ($avalilable_products > 0 and $availability_outofstock > 0) {
                $availability = 'Some Out of Stock';
            } else {
                if ($avalilable_products > 0) {
                    $availability = 'Normal';
                } else {
                    if ($avalilable_products == 0 and $availability_outofstock > 0) {
                        $availability = 'All Out of Stock';
                    }
                }
            }


        }


        $total_products = $discontinued + $public_sale + $private_sale + $not_for_sale;


        if ($public_sale > 0) {
            $record_type = 'Normal';

            if ($public_sale == $to_be_discontinued) {
                $record_type = 'Discontinuing';
            }


        } elseif ($private_sale > 0) {
            $record_type = 'Private';

        } elseif ($discontinued > 0) {
            $record_type = 'Discontinued';

        } else {
            if ($in_process > 0) {
                $record_type = 'InProcess';
            } else {
                $record_type = 'Nosale';
            }
        }

        $sql = sprintf(
            "UPDATE `Product Family Dimension` SET `Product Family Record Type`=%s,`Product Family In Process Products`=%d,`Product Family For Public Sale Products`=%d ,`Product Family For Private Sale Products`=%d,`Product Family Discontinued Products`=%d ,`Product Family Not For Sale Products`=%d , `Product Family Optimal Availability Products`=%d , `Product Family Low Availability Products`=%d ,`Product Family Critical Availability Products`=%d ,`Product Family Out Of Stock Products`=%d,`Product Family Unknown Stock Products`=%d ,`Product Family Surplus Availability Products`=%d ,`Product Family Sales Type`=%s,`Product Family Availability`=%s WHERE `Product Family Key`=%d  ",
            prepare_mysql($record_type), $in_process, $public_sale, $private_sale, $discontinued, $not_for_sale,

            $availability_optimal, $availability_low, $availability_critical, $availability_outofstock, $availability_unknown, $availability_surplus, prepare_mysql($sales_type),
            prepare_mysql($availability), $this->id
        );


        mysql_query($sql);


        $this->get_data('id', $this->id);
    }

    function product_timeline($extra_where = '') {
        //todo: this scheme dont take in count products with 2 sku or sku changing over time
        $min_date = date('Y-m-d H:i:s');
        $max_date = date('Y-m-d H:i:s');

        $sql      = sprintf(
            "SELECT `Product Code`,`Product ID`,`Product Sales Type`,`Product Record Type`,`Product Valid From`,`Product Valid To` FROM `Product Dimension`  WHERE `Product Family Key`=%d  %s  ",
            $this->id, $extra_where
        );
        $res      = mysql_query($sql);
        $products = array();
        $skus     = array();
        while ($row = mysql_fetch_array($res)) {
            if (strtotime($min_date) > strtotime($row['Product Valid From'])) {
                $min_date = $row['Product Valid From'];
            }
            if (strtotime($min_date) > strtotime($row['Product Valid To'])) {
                $min_date = $row['Product Valid To'];
            }

            $_product       = new Product('pid', $row['Product ID']);
            $units_per_case = $_product->data['Product Units Per Case'];
            $sku            = $_product->get('Parts SKU');


            $products[] = array(
                'code'           => $row['Product Code'],
                'units_per_case' => $units_per_case,
                'sku'            => $sku[0],
                'id'             => $row['Product ID'],
                'from'           => $row['Product Valid From'],
                'to'             => ($row['Product Sales Type'] != 'Not for Sale' ? date('Y-m-d H:i:s') : $row['Product Valid To'])
            );
        }
        //print "$min_date $max_date\n";
        //print_r($products);

        $sql        = sprintf(
            "SELECT `Date` FROM kbase.`Date Dimension` WHERE `Date`>=DATE(%s) AND `Date`<=DATE(%s)", prepare_mysql($min_date), prepare_mysql($max_date)
        );
        $res        = mysql_query($sql);
        $dates      = array();
        $dates_skus = array();
        $dates_ppp  = array();
        //print $sql;
        while ($row = mysql_fetch_array($res)) {
            $dates[$row['Date']]      = array();
            $dates_skus[$row['Date']] = array();
            $dates_ppp[$row['Date']]  = array();
            foreach ($products as $product) {
                if (!(strtotime($product['to']) < strtotime(
                        $row['Date'].' OO:00:00'
                    ) or strtotime($product['from']) > strtotime(
                        $row['Date'].' 23:59:59'
                    ))
                ) {
                    $dates[$row['Date']][]      = $product['id'];
                    $dates_skus[$row['Date']][] = $product['sku'];
                    $dates_ppp[$row['Date']][]  = $product['units_per_case'];
                }

            }
            sort($dates[$row['Date']]);

        }

        //  print_r($dates);

        $border_dates = array();
        $pivot        = array();
        foreach ($dates as $key => $date) {
            if ($pivot != $date) {
                //print "$key\n";
                $border_dates[] = $key;
            }
            $pivot = $date;
        }
        $border_dates[] = $key;
        //print_r($border_dates);
        $counter          = 0;
        $product_interval = array();
        foreach ($border_dates as $border) {

            $recent = 'No';
            if ($counter == count($border_dates) - 1) {
                $recent = 'Yes';
            }
            if ($counter > 0) {
                $product_interval[] = array(
                    'Product IDs'       => $dates[$lower_bound],
                    'Product SKUs'      => $dates_skus[$lower_bound],
                    'Products Per Part' => $dates_ppp[$lower_bound],
                    'Valid From'        => $lower_bound.' 00:00:00',
                    'Valid To'          => date(
                        'Y-m-d h:i:s', strtotime($border.' -1 second')
                    ),
                    'Most Recent'       => $recent
                );
            }
            $lower_bound = $border;
            $counter++;
        }

        //print_r($product_interval);

        return $product_interval;


    }

    function get_next_product_code() {
        $next_code = '';
        $sql       = sprintf(
            "SELECT `Product Code File As` FROM `Product Dimension` WHERE `Product Family Key`=%d ORDER BY  `Product Code File As` DESC LIMIT 1 ", $this->id
        );
        $res       = mysql_query($sql);
        if ($row = mysql_fetch_array($res)) {
            $next_code = $row['Product Code File As'];
            if (preg_match('/^[a-z]+\-\d+$/', $row['Product Code File As'])) {
                $last_number = 1;
                if (preg_match(
                    '/\d+$/', $row['Product Code File As'], $match
                )) {
                    $last_number += $match[0];
                }
                $next_code = sprintf(
                    "%s-%02d", $this->data['Product Family Code'], $last_number
                );
            }


            return $next_code;
        } else {
            return '';
        }

    }

    function update_sales_state() {

        $this->update_product_data();

    }

    function get_number_products_by_sales_type($tipo = false) {
        $number_products = array(
            'Public Sale'  => 0,
            'Private Sale' => 0,
            'Not for Sale' => 0,
            'Discontinued' => 0
        );

        $sql = sprintf(
            "SELECT count(*) AS num, `Product Sales Type` FROM `Product Dimension` WHERE `Product Family Key`=%d GROUP BY `Product Sales Type`", $this->id
        );
        $res = mysql_query($sql);
        while ($row = mysql_fetch_array($res)) {
            $number_products[$row['Product Sales Type']] = $row['num'];

        }
        if (!$tipo) {
            return $number_products;
        } else {
            if (array_key_exists($tipo, $number_products)) {
                return $number_products[$tipo];
            } else {
                return 0;
            }
        }

    }

    function has_layout_old_to_delete($type) {


        if (!$this->data['Family Page Key']) {
            return false;
        }
        if (!$this->page_data) {
            $this->page_data = $this->get_page_data();
            if (!$this->page_data) {
                return false;
            }
        }

        switch ($type) {
            case "thumbnails":
                if ($this->page_data['Product Thumbnails Layout'] == 'Yes') {
                    return true;
                }
                break;
            case "list":
            case "lists":
                if ($this->page_data['List Layout'] == 'Yes') {
                    return true;
                }
                break;

            case "slideshow":
                if ($this->page_data['Product Slideshow Layout'] == 'Yes') {
                    return true;
                }
                break;
            case "manual":
                if ($this->page_data['Product Manual Layout'] == 'Yes') {
                    return true;
                }
                break;
            default:
                return false;
                break;
        }

        return false;
    }

    function update_sales_default_currency() {
        $this->data_default_currency = array();
        $this->data_default_currency['Product Family DC Total Invoiced Gross Amount']
                                     = 0;
        $this->data_default_currency['Product Family DC Total Invoiced Discount Amount']
                                     = 0;
        $this->data_default_currency['Product Family DC Total Invoiced Amount']
                                     = 0;
        $this->data_default_currency['Product Family DC Total Profit']
                                     = 0;
        $this->data_default_currency['Product Family DC 1 Year Acc Invoiced Gross Amount']
                                     = 0;
        $this->data_default_currency['Product Family DC 1 Year Acc Invoiced Discount Amount']
                                     = 0;
        $this->data_default_currency['Product Family DC 1 Year Acc Invoiced Amount']
                                     = 0;
        $this->data_default_currency['Product Family DC 1 Year Acc Profit']
                                     = 0;
        $this->data_default_currency['Product Family DC 1 Quarter Acc Invoiced Discount Amount']
                                     = 0;
        $this->data_default_currency['Product Family DC 1 Quarter Acc Invoiced Amount']
                                     = 0;
        $this->data_default_currency['Product Family DC 1 Quarter Acc Profit']
                                     = 0;
        $this->data_default_currency['Product Family DC 1 Month Acc Invoiced Gross Amount']
                                     = 0;
        $this->data_default_currency['Product Family DC 1 Month Acc Invoiced Discount Amount']
                                     = 0;
        $this->data_default_currency['Product Family DC 1 Month Acc Invoiced Amount']
                                     = 0;
        $this->data_default_currency['Product Family DC 1 Month Acc Profit']
                                     = 0;
        $this->data_default_currency['Product Family DC 1 Week Acc Invoiced Gross Amount']
                                     = 0;
        $this->data_default_currency['Product Family DC 1 Week Acc Invoiced Discount Amount']
                                     = 0;
        $this->data_default_currency['Product Family DC 1 Week Acc Invoiced Amount']
                                     = 0;
        $this->data_default_currency['Product Family DC 1 Week Acc Profit']
                                     = 0;


        $sql
            = "SELECT     sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) AS cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) AS gross ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)AS disc  FROM `Order Transaction Fact`  OTF   WHERE `Product Family Key`="
            .$this->id;


        //print "$sql\n\n";
        // exit;
        $result = mysql_query($sql);

        if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data_default_currency['Product Family DC Total Invoiced Gross Amount']
                = $row['gross'];
            $this->data_default_currency['Product Family DC Total Invoiced Discount Amount']
                = $row['disc'];
            $this->data_default_currency['Product Family DC Total Invoiced Amount']
                = $row['gross'] - $row['disc'];
            $this->data_default_currency['Product Family DC Total Profit']
                = $row['gross'] - $row['disc'] - $row['cost_sup'];

        }


        $sql = sprintf(
            "SELECT  sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) AS cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) AS gross
                     ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)AS disc
                     FROM `Order Transaction Fact`  OTF    WHERE `Product Family Key`=%d AND  `Invoice Date`>=%s", $this->id, prepare_mysql(date("Y-m-d", strtotime("- 1 year")))
        );

        $result = mysql_query($sql);

        if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data_default_currency['Product Family DC 1 Year Acc Invoiced Gross Amount']
                = $row['gross'];
            $this->data_default_currency['Product Family DC 1 Year Acc Invoiced Discount Amount']
                = $row['disc'];
            $this->data_default_currency['Product Family DC 1 Year Acc Invoiced Amount']
                = $row['gross'] - $row['disc'];
            $this->data_default_currency['Product Family DC 1 Year Acc Profit']
                = $row['gross'] - $row['disc'] - $row['cost_sup'];

        }

        $sql    = sprintf(
            "SELECT   sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) AS cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) AS gross ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)AS disc  FROM `Order Transaction Fact`  OTF    WHERE `Product Family Key`=%d AND  `Invoice Date`>=%s",
            $this->id, prepare_mysql(date("Y-m-d", strtotime("- 3 month")))
        );
        $result = mysql_query($sql);

        if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data_default_currency['Product Family DC 1 Quarter Acc Invoiced Gross Amount']
                = $row['gross'];
            $this->data_default_currency['Product Family DC 1 Quarter Acc Invoiced Discount Amount']
                = $row['disc'];
            $this->data_default_currency['Product Family DC 1 Quarter Acc Invoiced Amount']
                = $row['gross'] - $row['disc'];
            $this->data_default_currency['Product Family DC 1 Quarter Acc Profit']
                = $row['gross'] - $row['disc'] - $row['cost_sup'];

        }

        $sql = sprintf(
            "SELECT    sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) AS cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) AS gross  ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)AS disc    FROM `Order Transaction Fact`  OTF    WHERE `Product Family Key`=%d AND  `Invoice Date`>=%s",
            $this->id, prepare_mysql(date("Y-m-d", strtotime("- 1 month")))
        );


        $result = mysql_query($sql);

        if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data_default_currency['Product Family DC 1 Month Acc Invoiced Gross Amount']
                = $row['gross'];
            $this->data_default_currency['Product Family DC 1 Month Acc Invoiced Discount Amount']
                = $row['disc'];
            $this->data_default_currency['Product Family DC 1 Month Acc Invoiced Amount']
                = $row['gross'] - $row['disc'];
            $this->data_default_currency['Product Family DC 1 Month Acc Profit']
                = $row['gross'] - $row['disc'] - $row['cost_sup'];

        }
        $sql = sprintf(
            "SELECT  sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) AS cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) AS gross   ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)AS disc    FROM `Order Transaction Fact`  OTF    WHERE `Product Family Key`=%d AND  `Invoice Date`>=%s",
            $this->id, prepare_mysql(date("Y-m-d", strtotime("- 1 week")))
        );
        // print $sql;
        $result = mysql_query($sql);

        if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data_default_currency['Product Family DC 1 Week Acc Invoiced Gross Amount']
                = $row['gross'];
            $this->data_default_currency['Product Family DC 1 Week Acc Invoiced Discount Amount']
                = $row['disc'];
            $this->data_default_currency['Product Family DC 1 Week Acc Invoiced Amount']
                = $row['gross'] - $row['disc'];
            $this->data_default_currency['Product Family DC 1 Week Acc Profit']
                = $row['gross'] - $row['disc'] - $row['cost_sup'];

        }

        $insert_values = '';
        $update_values = '';
        foreach ($this->data_default_currency as $key => $value) {
            $insert_values .= sprintf(',%.2f', $value);
            $update_values .= sprintf(',`%s`=%.2f', addslashes($key), $value);
        }
        $insert_values = preg_replace('/^,/', '', $insert_values);
        $update_values = preg_replace('/^,/', '', $update_values);


        $sql = sprintf(
            'INSERT INTO `Product Family Default Currency` VALUES (%d,%s) ON DUPLICATE KEY UPDATE %s  ', $this->id, $insert_values, $update_values
        );
        mysql_query($sql);
        //print "$sql\n";


    }

    function update_sales_correlations($type = 'All', $limit = 100) {

        $max_correaltions = 50;

        $sql = sprintf(
            "SELECT count(DISTINCT `Customer Key`) AS num FROM  `Order Transaction Fact` WHERE `Product Family Key`=%d AND `Order Transaction Type`='Order' ", $this->id
        );
        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {
            if ($row['num'] < 5) {
                return;
            }
            $a_samples = $row['num'];
            $a_lenght  = sqrt($a_samples);
        }

        switch ($type) {

            case 'Same Department':
                $sql = sprintf(
                    "SELECT F.`Product Family Key` FROM `Product Family Dimension` F  LEFT JOIN `Product Family Data Dimension` D ON (F.`Product Family Key`=D.`Product Family Key`) WHERE  `Product Family Main Department Key`=%d AND  `Product Family Stealth`='No' AND `Product Family 1 Year Acc Customers`>0  ORDER BY `Product Family 1 Year Acc Customers` DESC  LIMIT %s ",
                    $this->data['Product Family Main Department Key'], $limit
                );

                break;

            default:

                $sql = sprintf(
                    "SELECT F.`Product Family Key` FROM `Product Family Dimension` F  LEFT JOIN `Product Family Data Dimension` D ON (F.`Product Family Key`=D.`Product Family Key`) WHERE  `Product Family Store Key`=%d AND  `Product Family Stealth`='No' AND `Product Family 1 Year Acc Customers`>0  ORDER BY `Product Family 1 Year Acc Customers` DESC  LIMIT %s ",
                    $this->data['Product Family Store Key'], $limit
                );

        }
        $res2 = mysql_query($sql);
        while ($row2 = mysql_fetch_assoc($res2)) {
            if ($row2['Product Family Key'] == $this->id) {
                continue;
            }

            $sql = sprintf(
                "SELECT count(DISTINCT `Customer Key`) AS num FROM  `Order Transaction Fact` WHERE `Product Family Key`=%d AND `Order Transaction Type`='Order' ", $row2['Product Family Key']
            );
            $res = mysql_query($sql);
            if ($row = mysql_fetch_assoc($res)) {
                if ($row['num'] < 5) {
                    continue;
                }

                $b_samples = $row['num'];
                $b_lenght  = sqrt($b_samples);
            }


            $sql = sprintf(
                "SELECT `Customer Key` FROM `Order Transaction Fact` OTF  WHERE `Product Family Key`=%d  AND  `Order Transaction Type`='Order'  GROUP BY `Customer Key`",

                $this->id
            );

            $dot_product = 0;
            $res         = mysql_query($sql);
            while ($row = mysql_fetch_assoc($res)) {

                $sql = sprintf(
                    "SELECT `Order Transaction Fact Key` FROM `Order Transaction Fact` OTF2 WHERE OTF2.`Order Transaction Type`='Order' AND OTF2.`Product Family Key`=%d AND OTF2.`Customer Key`=%d",
                    $row2['Product Family Key'], $row['Customer Key']
                );
                //  print "$sql\n";
                $_res = mysql_query($sql);
                if ($_row = mysql_fetch_assoc($_res)) {

                    $dot_product += 1;
                }
            }
            if ($dot_product) {


                $normalization_factor = $a_lenght * $b_lenght;
                $correlation          = $dot_product / $normalization_factor;
                $normalization_factor = ceil($normalization_factor);
                //print $row2['Product Family Key'].' '.$row2['Product Code'].' '.$correlation." $normalization_factor   \n";

                $sql  = sprintf(
                    "SELECT min(`Correlation`) AS corr ,count(*) AS num FROM `Product Family Sales Correlation` WHERE `Family A Key`=%d    ", $this->id
                );
                $res4 = mysql_query($sql);
                if ($row4 = mysql_fetch_assoc($res4)) {
                    if ($row4['num'] < $max_correaltions) {
                        $sql = sprintf(
                            "INSERT INTO  `Product Family Sales Correlation` (`Family A Key`,`Family B Key`,`Correlation`,`Samples`) VALUES (%d,%d,%f,%d) ON DUPLICATE KEY UPDATE `Correlation`=%f, `Samples`=%d ",
                            $this->id, $row2['Product Family Key'], $correlation, $normalization_factor, $correlation, $normalization_factor
                        );


                        mysql_query($sql);
                    } else {
                        if ($row4['corr'] < $correlation) {
                            $sql = sprintf(
                                "DELETE FROM `Product Family Sales Correlation` WHERE `Family A Key`=%d  ORDER BY `Correlation` LIMIT 1  ", $this->id
                            );
                            mysql_query($sql);
                            $sql = sprintf(
                                "INSERT INTO  `Product Family Sales Correlation` (`Family A Key`,`Family B Key`,`Correlation`,`Samples`) VALUES (%d,%d,%f,%d) ON DUPLICATE KEY UPDATE `Correlation`=%f, `Samples`=%d ",
                                $this->id, $row2['Product Family Key'], $correlation, $normalization_factor, $correlation, $normalization_factor
                            );
                            mysql_query($sql);
                        }

                    }

                }


                $sql  = sprintf(
                    "SELECT min(`Correlation`) AS corr ,count(*) AS num FROM `Product Family Sales Correlation` WHERE `Family A Key`=%d    ", $row2['Product Family Key']
                );
                $res4 = mysql_query($sql);
                if ($row4 = mysql_fetch_assoc($res4)) {
                    if ($row4['num'] < $max_correaltions) {
                        $sql = sprintf(
                            "INSERT INTO  `Product Family Sales Correlation` (`Family A Key`,`Family B Key`,`Correlation`,`Samples`) VALUES (%d,%d,%f,%d) ON DUPLICATE KEY UPDATE `Correlation`=%f, `Samples`=%d ",

                            $row2['Product Family Key'], $this->id, $correlation, $normalization_factor, $correlation, $normalization_factor
                        );


                        mysql_query($sql);
                    } else {
                        if ($row4['corr'] < $correlation) {
                            $sql = sprintf(
                                "DELETE FROM `Product Family Sales Correlation` WHERE `Family A Key`=%d  ORDER BY `Correlation` LIMIT 1  ", $row2['Product Family Key']
                            );
                            mysql_query($sql);
                            $sql = sprintf(
                                "INSERT INTO  `Product Family Sales Correlation` (`Family A Key`,`Family B Key`,`Correlation`,`Samples`) VALUES (%d,%d,%f,%d) ON DUPLICATE KEY UPDATE `Correlation`=%f, `Samples`=%d ",

                                $row2['Product Family Key'], $this->id, $correlation, $normalization_factor, $correlation, $normalization_factor
                            );
                            mysql_query($sql);
                        }

                    }

                }


            }


        }

    }

    function update_correlated_sales_families_old() {
        $orders = 0;

        $sql = sprintf(
            "SELECT count(DISTINCT `Order Key`) AS num FROM  `Order Transaction Fact`  WHERE `Product Family Key`=%d  AND `Order Quantity`>0 AND `Order Transaction Type`='Order'", $this->id
        );
        $res = mysql_query($sql);
        //print "$sql\n";
        if ($row = mysql_fetch_assoc($res)) {
            $orders = $row['num'];
        }

        if ($orders) {
            $orders_keys = $this->get_orders_keys();
            $sql         = sprintf(
                "SELECT `Product Family Key` FROM `Product Family Dimension` WHERE `Product Family Key`!=%d AND `Product Family Store Key`=%d AND  `Product Family Stealth`='No'", $this->id,
                $this->data['Product Family Store Key']
            );
            $result      = mysql_query($sql);
            //print "$sql\n";
            while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $family = new Family($row['Product Family Key']);

                //    print $family->id." xxx\n";

                $family_orders_keys = $family->get_orders_keys();

                $number_common_orders = count(
                    array_intersect_key($orders_keys, $family_orders_keys)
                );

                /*

				$sql=sprintf("select count(distinct `Order Key`) as num from `Order Transaction Fact` where `Product Family Key`=%d  and `Order Key` in (select `Order Key` from `Order Transaction Fact` where `Product Family Key`=%d   ) ",
				$this->id,
				$row['Product Family Key']

				);


				$res2=mysql_query($sql);
		//print "$sql\n";
		if ($row2=mysql_fetch_assoc($res2)) {
			$number_common_orders=$row2['num'];
		}else{
		$number_common_orders=0;
		}

		*/


                $probability = $number_common_orders / $orders;
                // print $family->id." $probability\n";

                if ($probability > 0.000001) {
                    $sql = sprintf(
                        "INSERT INTO `Product Family Sales Correlation` VALUES (%d,%d,%f,%d) ON DUPLICATE KEY UPDATE `Correlation`=%f , `Samples`=%d  ", $this->id, $row['Product Family Key'],
                        $probability, $orders, $probability, $orders
                    );
                    mysql_query($sql);
                    //    print "$sql\n";

                } else {

                    $sql = sprintf(
                        "DELETE FROM `Product Family Sales Correlation` WHERE `Family A Key`=%d AND `Family B Key`=%d", $this->id, $row['Product Family Key']

                    );
                    mysql_query($sql);


                }

            }


        }

    }

    function get_orders_keys() {
        $orders_keys = array();
        $sql         = sprintf(
            "SELECT `Order Key` FROM  `Order Transaction Fact`  WHERE  `Product Family Key`=%d AND `Order Quantity`>0 AND `Order Transaction Type`='Order'", $this->id
        );
        $res         = mysql_query($sql);
        //print "$sql\n";
        while ($row = mysql_fetch_assoc($res)) {
            $orders_keys[$row['Order Key']] = $row['Order Key'];

        }

        return $orders_keys;

    }

    function get_period($period, $key) {

        return $this->get($period.' '.$key);
    }


    function get_formated_rrp($data, $options = false) {

        $data = array(
            'Product RRP'            => $data['Product RRP'],
            'Product Units Per Case' => $data['Product Units Per Case'],
            'Product Currency'       => $this->currency,
            'Product Unit Type'      => $data['Product Unit Type'],
            'locale'                 => $this->locale
        );

        return formated_rrp($data, $options);
    }

    function get_formated_price($data, $options = false) {

        $_data = array(
            'Product Price'          => $data['Product Price'],
            'Product Units Per Case' => $data['Product Units Per Case'],
            'Product Currency'       => $this->currency,
            'Product Unit Type'      => $data['Product Unit Type'],
            'Label'                  => $data['Label'],
            'locale'                 => $this->locale,

        );

        if (isset($data['price per unit text'])) {
            $_data['price per unit text'] = $data['price per unit text'];
        }

        return formated_price($_data, $options);
    }


    function strleft1($s1, $s2) {
        return substr($s1, 0, strpos($s1, $s2));
    }


    function remove_image($image_key) {

        $sql = sprintf(
            "SELECT `Image Key`,`Is Principal` FROM `Image Bridge` WHERE `Subject Type`='Family' AND `Subject Key`=%d  AND `Image Key`=%d", $this->id, $image_key
        );
        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {

            $sql = sprintf(
                "DELETE FROM `Image Bridge` WHERE `Subject Type`='Family' AND `Subject Key`=%d  AND `Image Key`=%d", $this->id, $image_key
            );
            mysql_query($sql);
            $this->updated = true;

            $number_images = $this->get_number_of_images();

            if ($number_images == 0) {
                $main_image_src                              = '';
                $main_image_key                              = 0;
                $this->data['Product Family Main Image']     = 'art/nopic.png';
                $this->data['Product Family Main Image Key'] = $main_image_key;
                $sql                                         = sprintf(
                    "UPDATE `Product Family Dimension` SET `Product Family Main Image`=%s ,`Product Family Main Image Key`=%d WHERE `Product Family Key`=%d", prepare_mysql($main_image_src),
                    $main_image_key, $this->id
                );
                mysql_query($sql);

            } elseif ($row['Is Principal'] == 'Yes') {

                $sql  = sprintf(
                    "SELECT `Image Key` FROM `Image Bridge` WHERE `Subject Type`='Family' AND `Subject Key`=%d  ", $this->id
                );
                $res2 = mysql_query($sql);
                if ($row2 = mysql_fetch_assoc($res2)) {
                    $this->update_main_image($row2['Image Key']);
                }
            }


        } else {
            $this->error = true;
            $this->msg   = 'image not associated';

        }


    }

    function update_main_image($image_key) {

        $sql = sprintf(
            "SELECT `Image Key` FROM `Image Bridge` WHERE `Subject Type`='Family' AND `Subject Key`=%d  AND `Image Key`=%d", $this->id, $image_key
        );
        $res = mysql_query($sql);
        if (!mysql_num_rows($res)) {
            $this->error = true;
            $this->msg   = 'image not associated';
            //print $this->msg."\n";
        }

        $sql = sprintf(
            "UPDATE `Image Bridge` SET `Is Principal`='No' WHERE `Subject Type`='Family' AND `Subject Key`=%d  ", $this->id
        );
        mysql_query($sql);
        $sql = sprintf(
            "UPDATE `Image Bridge` SET `Is Principal`='Yes' WHERE `Subject Type`='Family' AND `Subject Key`=%d  AND `Image Key`=%d", $this->id, $image_key
        );
        mysql_query($sql);


        $main_image_src = 'image.php?id='.$image_key.'&size=small';
        $main_image_key = $image_key;

        $this->data['Product Family Main Image']     = $main_image_src;
        $this->data['Product Family Main Image Key'] = $main_image_key;
        $sql                                         = sprintf(
            "UPDATE `Product Family Dimension` SET `Product Family Main Image`=%s ,`Product Family Main Image Key`=%d WHERE `Product Family Key`=%d", prepare_mysql($main_image_src), $main_image_key,
            $this->id
        );
        mysql_query($sql);

        $page_keys = $this->get_pages_keys();
        foreach ($page_keys as $page_key) {
            $page = new Page($page_key);
            $page->update_image_key();
        }


        $this->updated = true;

    }

    function update_image_caption($image_key, $value) {
        $value = _trim($value);


        $sql = sprintf(
            "UPDATE `Image Bridge` SET `Image Caption`=%s WHERE  `Subject Type`='Family' AND `Subject Key`=%d  AND `Image Key`=%d", prepare_mysql($value), $this->id, $image_key
        );
        mysql_query($sql);
        //print $sql;
        if (mysql_affected_rows()) {
            $this->new_value = $value;
            $this->updated   = true;
        } else {
            $this->msg = _('No change');

        }

    }

    function get_main_image_key() {

        return $this->data['Product Family Main Image Key'];
    }

    function post_add_history($history_key, $type = false) {

        if (!$type) {
            $type = 'Changes';
        }

        $sql = sprintf(
            "INSERT INTO  `Product Family History Bridge` (`Family Key`,`History Key`,`Type`) VALUES (%d,%d,%s)", $this->id, $history_key, prepare_mysql($type)
        );
        mysql_query($sql);

    }


    function get_formated_discounts() {
        $formated_discounts = '';

        $sql = sprintf(
            "SELECT `Deal Description`,`Deal Name`,D.`Deal Key`,`Deal XHTML Terms Description Label`,`Deal Component XHTML Allowance Description Label` FROM  `Deal Component Dimension` DC LEFT JOIN `Deal Dimension` D ON (D.`Deal Key`=DC.`Deal Component Deal Key`) WHERE `Deal Component Allowance Target`='Department' AND `Deal Component Allowance Target Key`=%d  AND `Deal Component Status`='Active' ",
            $this->data['Product Family Main Department Key']
        );

        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            $formated_discounts .= '<br> <span title="'.$row['Deal Description'].'"><a href="deal.php?id='.$row['Deal Key'].'">'.$row['Deal Name'].'</a> <span style="font-style:italic;color:#777">'
                .$row['Deal XHTML Terms Description Label'].' &#8658; '.$row['Deal Component XHTML Allowance Description Label'].'</span></span>';
        }

        $sql = sprintf(
            "SELECT `Deal Description`,`Deal Name`,D.`Deal Key`,`Deal XHTML Terms Description Label`,`Deal Component XHTML Allowance Description Label` FROM  `Deal Component Dimension` DC LEFT JOIN `Deal Dimension` D ON (D.`Deal Key`=DC.`Deal Component Deal Key`) WHERE `Deal Component Allowance Target`='Family' AND `Deal Component Allowance Target Key`=%d  AND `Deal Component Status`='Active' ",
            $this->id
        );

        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            $formated_discounts .= '<br> <span title="'.$row['Deal Description'].'"><a href="deal.php?id='.$row['Deal Key'].'">'.$row['Deal Name'].'</a> <span style="font-style:italic;color:#777">'
                .$row['Deal XHTML Terms Description Label'].' &#8658; '.$row['Deal Component XHTML Allowance Description Label'].'</span></span>';
        }
        $formated_discounts = preg_replace(
            '/^\<br\> /', '', $formated_discounts
        );

        return $formated_discounts;
    }

    function get_valid_to() {

        if ($this->data['Product Family Record Type'] == 'Discontinued') {
            return $this->data['Product Family Valid To'];
        } else {
            return gmdate("Y-m-d H:i:s");
        }


    }

    function update_sales_averages() {

        include_once 'common_stat_functions.php';

        $sql = sprintf(
            "SELECT sum(`Sales`) AS sales,sum(`Availability`) AS availability  FROM `Order Spanshot Fact` WHERE `Product Family Key`=%d   GROUP BY `Date`;", $this->id
        );
        $res = mysql_query($sql);

        $counter_available = 0;
        $counter           = 0;
        $sum               = 0;
        while ($row = mysql_fetch_assoc($res)) {

            $sum += $row['sales'];
            $counter++;
            if ($row['sales'] == $row['availability']) {
                $counter_available++;
            }


        }


        if ($counter > 0) {
            $this->data['Product Family Number Days on Sale'] = $counter;
            $this->data['Product Family Avg Day Sales']       = $sum / $counter;
            $this->data['Product Family Number Days Available']
                                                              = $counter_available;

        } else {
            $this->data['Product Family Number Days on Sale']   = 0;
            $this->data['Product Family Avg Day Sales']         = 0;
            $this->data['Product Family Number Days Available'] = 0;


        }

        $sql        = sprintf(
            "SELECT sum(`Sales`) AS sales  FROM `Order Spanshot Fact` WHERE `Product Family Key`=%d AND sales>0  GROUP BY `Date`;", $this->id
        );
        $res        = mysql_query($sql);
        $data_sales = array();
        $max_value  = 0;
        $counter    = 0;
        $sum        = 0;
        while ($row = mysql_fetch_assoc($res)) {
            $data_sales[] = $row['sales'];
            $sum += $row['sales'];
            $counter++;
            if ($row['sales'] > $max_value) {
                $max_value = $row['sales'];
            }
        }


        if ($counter > 0) {


            $this->data['Product Family Number Days with Sales']  = $counter;
            $this->data['Product Family Avg with Sale Day Sales'] = $sum / $counter;
            $this->data['Product Family STD with Sale Day Sales']
                                                                  = standard_deviation(
                $data_sales
            );
            $this->data['Product Family Max Day Sales']           = $max_value;
        } else {
            $this->data['Product Family Number Days with Sales']  = 0;
            $this->data['Product Family Avg with Sale Day Sales'] = 0;
            $this->data['Product Family STD with Sale Day Sales'] = 0;
            $this->data['Product Family Max Day Sales']           = 0;

        }

        $sql = sprintf(
            "UPDATE `Product Family Dimension` SET `Product Family Number Days on Sale`=%d,`Product Family Avg Day Sales`=%d,`Product Family Number Days Available`=%f,`Product Family Number Days with Sales`=%d,`Product Family Avg with Sale Day Sales`=%f,`Product Family STD with Sale Day Sales`=%f,`Product Family Max Day Sales`=%f WHERE `Product Family Key`=%d",
            $this->data['Product Family Number Days on Sale'], $this->data['Product Family Avg Day Sales'], $this->data['Product Family Number Days Available'],
            $this->data['Product Family Number Days with Sales'], $this->data['Product Family Avg with Sale Day Sales'], $this->data['Product Family STD with Sale Day Sales'],
            $this->data['Product Family Max Day Sales'], $this->id
        );
        mysql_query($sql);

    }

    function get_deals_data() {

        $deals = array();

        $sql = sprintf(
            "SELECT `Deal Label`,`Deal Description`,`Deal Name`,`Deal Component Status`,`Deal Component XHTML Allowance Description Label`,`Deal Component Terms Type`,`Deal XHTML Terms Description Label` FROM `Deal Component Dimension` DC  LEFT JOIN `Deal Dimension` D ON (D.`Deal Key`=DC.`Deal Component Deal Key`) WHERE `Deal Component Allowance Target`='Department' AND `Deal Component Allowance Target Key`=%d  AND `Deal Component Status`='Active'  AND `Deal Voucher Key` IS NULL AND `Deal Trigger` NOT IN ('Customer','Customer Category','Customer List')  ",
            $this->data['Product Family Main Department Key']
        );


        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            $deals[] = array(
                'Allowance Label' => $row['Deal Component XHTML Allowance Description Label'],
                'Terms Label'     => $row['Deal XHTML Terms Description Label'],
                'Terms Type'      => $row['Deal Component Terms Type'],
                'Status'          => $row['Deal Component Status'],
                'Name'            => $row['Deal Name'],
                'Label'           => $row['Deal Label'],
                'Description'     => $row['Deal Description']
            );
        }


        $sql = sprintf(
            "SELECT `Deal Label`,`Deal Description`,`Deal Name`,`Deal Component Status`,`Deal Component XHTML Allowance Description Label`,`Deal Component Terms Type`,`Deal XHTML Terms Description Label` FROM `Deal Component Dimension` DC  LEFT JOIN `Deal Dimension` D ON (D.`Deal Key`=DC.`Deal Component Deal Key`) WHERE `Deal Component Allowance Target`='Family' AND `Deal Component Allowance Target Key`=%d  AND `Deal Component Status`='Active'  AND `Deal Voucher Key` IS NULL AND `Deal Voucher Key` IS NULL AND `Deal Trigger` NOT IN ('Customer','Customer Category','Customer List')  ",
            $this->id
        );

        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            $deals[] = array(
                'Allowance Label' => $row['Deal Component XHTML Allowance Description Label'],
                'Terms Label'     => $row['Deal XHTML Terms Description Label'],
                'Terms Type'      => $row['Deal Component Terms Type'],
                'Status'          => $row['Deal Component Status'],
                'Name'            => $row['Deal Name'],
                'Label'           => $row['Deal Label'],
                'Description'     => $row['Deal Description']
            );
        }

        return $deals;
    }

}

?>
