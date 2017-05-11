<?php

/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 8 May 2017 at 22:58:39 GMT-5, CDMX, Mexico

 Copyright (c) 2017, Inikoo

 Version 2.0
*/


class Public_Account  {

    function Public_Account($_db = false) {

        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }


        $this->table_name = 'Account';


        $this->get_data();
    }


    function get_data() {


        $sql = sprintf(
            "SELECT * FROM `Account Dimension` WHERE `Account Key`=1 "
        );


        if ($result = $this->db->query($sql)) {
            if ($this->data = $result->fetch()) {
                $this->id = 1;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }


    function get($key) {

        if (!$this->id) {
            return;
        }


        switch ($key) {

            case ('Currency'):

                if ($this->data['Account Currency'] != '') {


                    $sql = sprintf(
                        "SELECT `Currency Code`,`Currency Name`,`Currency Symbol` FROM kbase.`Currency Dimension` WHERE `Currency Code`=%s", prepare_mysql($this->data['Account Currency'])
                    );


                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            return sprintf("%s (%s)", $row['Currency Name'], $row['Currency Code']);
                        } else {
                            return '';
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }
                } else {
                    return '';
                }

                break;

            case 'Country Code':
                if ($this->get('Account Country Code')) {
                    include_once 'class.Country.php';
                    $country = new Country('code', $this->data['Account Country Code']);

                    return _($country->get('Country Name')).' ('.$country->get('Country Code').')';
                } else {
                    return '';
                }

                break;


            case 'Productions':

                $number = 0;
                $sql    = sprintf(
                    "SELECT count(*) AS num FROM `Supplier Production Dimension`", $this->id
                );
                if ($row = $this->db->query($sql)->fetch()) {
                    $number = $row['num'];
                }

                return $number;
                break;

            case('Locale'):


                include 'utils/available_locales.php';

                if (array_key_exists(
                    $this->data['Account Locale'].'.UTF-8', $available_locales
                )) {
                    $locale = $available_locales[$this->data['Account Locale'].'.UTF-8'];

                    return $locale['Language Name'].($locale['Language Name'] != $locale['Language Original Name'] ? ' ('.$locale['Language Original Name'].')' : '');
                } else {

                    return $this->data['Account Locale'];
                }
                break;


            case 'Setup Metadata':
                return json_decode($this->data['Account Setup Metadata'], true);
                break;
            case 'National Employment Code Label':

                switch ($this->data['Account Country 2 Alpha Code']) {
                    case 'GB':
                        return _('National insurance number');
                        break;
                    case 'ES':
                        return _('DNI');
                        break;
                    default:
                        return '';
                        break;
                }

                break;

            case 'Delta Today Start Orders In Warehouse Number':

                $start = $this->data['Account Today Start Orders In Warehouse Number'];
                $end   = $this->data['Account Orders In Warehouse Number'] + $this->data['Account Orders Packed Number'] + $this->data['Account Orders In Dispatch Area Number'];

                $diff = $end - $start;

                $delta = ($diff > 0 ? '+' : '').number($diff).delta_icon($end, $start, $inverse = true);


                return $delta;

            case 'Today Orders Dispatched':

                $number = 0;

                $sql = sprintf(
                    'SELECT count(*) AS num FROM `Order Dimension` WHERE `Order Current Dispatch State`="Dispatched" AND `Order Dispatched Date`>%s   AND  `Order Dispatched Date`<%s   ',
                    prepare_mysql(date('Y-m-d 00:00:00')), prepare_mysql(date('Y-m-d 23:59:59'))
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $number = $row['num'];
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                return number($number);

            default:


                if (preg_match('/^(DC Orders|Orders|Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Minify$/', $key)) {

                    $field = 'Account '.preg_replace('/ Minify$/', '', $key);
                    $field = preg_replace('/DC Orders/', 'Orders', $field);

                    $suffix          = '';
                    $fraction_digits = 'NO_FRACTION_DIGITS';
                    if ($this->data[$field] >= 1000000) {
                        $suffix          = 'M';
                        $fraction_digits = 'DOUBLE_FRACTION_DIGITS';
                        $_amount         = $this->data[$field] / 1000000;
                    } elseif ($this->data[$field] >= 10000) {
                        $suffix  = 'K';
                        $_amount = $this->data[$field] / 1000;
                    } elseif ($this->data[$field] > 100) {
                        $fraction_digits = 'SINGLE_FRACTION_DIGITS';
                        $suffix          = 'K';
                        $_amount         = $this->data[$field] / 1000;
                    } else {
                        $_amount = $this->data[$field];
                    }

                    $amount = money($_amount, $this->get('Account Currency'), $locale = false, $fraction_digits).$suffix;

                    return $amount;
                }


                if (preg_match('/^(DC Orders|Orders|Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Soft Minify$/', $key)) {

                    $field = 'Account '.preg_replace('/ Soft Minify$/', '', $key);


                    $field = preg_replace('/DC Orders/', 'Orders', $field);

                    $suffix          = '';
                    $fraction_digits = 'NO_FRACTION_DIGITS';
                    $_amount         = $this->data[$field];

                    $amount = money($_amount, $this->get('Account Currency'), $locale = false, $fraction_digits).$suffix;

                    return $amount;
                }
                if (preg_match('/^(Orders|Last|Yesterday|Total|1|10|6|3|2|4|5|Year To|Quarter To|Month To|Today|Week To).*(Quantity Invoiced|Invoices|Number)$/', $key)) {

                    $field = 'Account '.$key;


                    return number($this->data[$field]);
                }
                if (preg_match('/^(Orders|Last|Yesterday|Total|1|10|6|3|2|4|5|Year To|Quarter To|Month To|Today|Week To).*(Quantity Invoiced|Invoices) Minify$/', $key)) {

                    $field = 'Account '.preg_replace('/ Minify$/', '', $key);

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
                if (preg_match('/^(Orders|Last|Yesterday|Total|1|10|6|3|2|4|5|Year To|Quarter To|Month To|Today|Week To).*(Quantity Invoiced|Invoices|Number) Soft Minify$/', $key)) {
                    $field   = 'Account '.preg_replace('/ Soft Minify$/', '', $key);
                    $_number = $this->data[$field];

                    return number($_number, 0);
                }

                if (preg_match('/^(DC Orders|Orders|Total|1).*(Amount|Profit)$/', $key)) {

                    $field = 'Account '.$key;
                    $field = preg_replace('/DC Orders/', 'Orders', $field);


                    return money($this->data[$field], $this->get('Account Currency'));
                }

                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Account '.$key, $this->data)) {
                    return $this->data['Account '.$key];
                }
        }

        return '';
    }


}


?>
