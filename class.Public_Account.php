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
            case 'Currency Code':
            case 'Account Currency Code':
                return $this->data['Account Currency'];
                break;
            case 'Account Code':
            case 'Account Locale':
            case 'Account Country 2 Alpha Code':
            case 'Account Analytics ID':
            case 'Apply Tax Method':

                return $this->data[$key];

            break;

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


            default:





        }

        return '';
    }


}


?>
