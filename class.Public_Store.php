<?php

/*

  About:
  Author: Raul Perusquia <raul@inikoo.com>
  created: 9 May 2017 at 09:21:33 GMT-5, CdMx Mexico  

  Copyright (c) 2017, Inikoo

  Version 2.0
*/


class Public_Store {

    public $editor = array(
        'Author Name'  => false,
        'Author Alias' => false,
        'Author Key'   => 0,
        'User Key'     => 0,
        'Date'         => false
    );

    function Public_Store($a1, $a2 = false, $a3 = false, $_db = false) {

        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }
        $this->id            = false;
        $this->table_name    = 'Store';
        $this->ignore_fields = array('Store Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } else {
            $this->get_data($a1, $a2);
        }

    }


    function get_data($tipo, $tag) {


        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Store Dimension` WHERE `Store Key`=%d", $tag
            );
        } elseif ($tipo == 'code') {
            $sql = sprintf(
                "SELECT * FROM `Store Dimension` WHERE `Store Code`=%s", prepare_mysql($tag)
            );
        } else {
            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {

            $this->id   = $this->data['Store Key'];
            $this->code = $this->data['Store Code'];
        }


    }

    function get_categories($type = 'families', $pages = '1-10', $output = 'data') {

        $categories = array();


        $limit = preg_split('/-/', $pages);

        // print_r($pages);

        switch ($type) {
            case 'departments':
                $sql = sprintf(
                    'SELECT  `Webpage Code`,`Webpage Name` FROM  `Category Dimension` C   LEFT JOIN `Page Store Dimension` P ON (P.`Webpage Scope Key`=C.`Category Key` AND `Webpage Scope`="Category Categories" ) WHERE   C.`Category Parent Key`=%d  order by `Webpage Name` LIMIT %d,%d ',
                    $this->get('Store Department Category Key'), $limit[0], $limit[1]
                );

                break;
            case 'families':
                $sql = sprintf(
                    'SELECT  `Webpage Code`,`Webpage Name` FROM  `Category Dimension` C   LEFT JOIN `Page Store Dimension` P ON (P.`Webpage Scope Key`=C.`Category Key` AND `Webpage Scope`="Category Products" ) WHERE   C.`Category Parent Key`=%d order by `Webpage Name` LIMIT %d,%d ',
                    $this->get('Store Family Category Key'), $limit[0], $limit[1]
                );



                break;
            case 'web_departments':
                include_once 'class.Public_Website.php';
                $website=new Public_Website($this->get('Store Website Key'));


                $sql = sprintf(
                    'SELECT  `Webpage Code`,`Webpage Name` FROM  `Category Dimension` C   LEFT JOIN `Page Store Dimension` P ON (P.`Webpage Scope Key`=C.`Category Key` AND `Webpage Scope`="Category Categories" ) WHERE   C.`Category Parent Key`=%d order by `Webpage Name` LIMIT %d,%d ',
                    $website->get('Website Alt Department Category Key'), $limit[0], $limit[1]
                );


                break;
            case 'web_families':

                include_once 'class.Public_Website.php';
                $website=new Public_Website($this->get('Store Website Key'));

                $sql = sprintf(
                    'SELECT  `Webpage Code`,`Webpage Name` FROM  `Category Dimension` C   LEFT JOIN `Page Store Dimension` P ON (P.`Webpage Scope Key`=C.`Category Key` AND `Webpage Scope`="Category Products" ) WHERE   C.`Category Parent Key`=%d order by `Webpage Name` LIMIT %d,%d ',
                    $website->get('Website Alt Family Category Key'), $limit[0], $limit[1]
                );

                break;
            default:

                print $type;

        }


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                switch ($output) {
                    case 'menu':
                        $categories[] = array(
                            'url'   => strtolower($row['Webpage Code']),
                            'label' => $row['Webpage Name'],
                            'new'   => false

                        );
                        break;
                }

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $categories;


    }

    function get($key = '') {


        if (!$this->id) {
            return '';
        }


        switch ($key) {


            case 'Telephone':
            case 'Email':
            case 'Address':
                return $this->data['Store '.$key];
                break;


            case 'Store Currency Code':
            case 'Store Show in Warehouse Orders':
            case 'Store Order Public ID Format':
            case 'Store Department Category Key':
            case 'Store Family Category Key':
            case 'Store Timezone':
            case 'Store Key':
            case 'Store Email':
            case 'Store Website Key':
            case 'Store Home Country Code 2 Alpha':
            case 'Store Email':
            case 'Store Email Template Signature':
            case 'Store Google Map URL':
                return $this->data[$key];
                break;

        }


    }


    function create_customer($data,$user_data) {

        include_once 'class.Public_Customer.php';

        $this->new_customer = false;
        $this->new_website_user=false;

        $data['editor']             = $this->editor;
        $data['Customer Store Key'] = $this->id;
        $data['Customer Billing Address Link'] = 'Contact';
        $data['Customer Delivery Address Link'] = 'Billing';

        $address_fields = array(
            'Address Recipient'            => $data['Customer Main Contact Name'],
            'Address Organization'         => $data['Customer Company Name'],
            'Address Line 1'               => '',
            'Address Line 2'               => '',
            'Address Sorting Code'         => '',
            'Address Postal Code'          => '',
            'Address Dependent Locality'   => '',
            'Address Locality'             => '',
            'Address Administrative Area'  => '',
            'Address Country 2 Alpha Code' => $data['Customer Contact Address country'],

        );
        unset($data['Customer Contact Address country']);

        if (isset($data['Customer Contact Address addressLine1'])) {
            $address_fields['Address Line 1'] = $data['Customer Contact Address addressLine1'];
            unset($data['Customer Contact Address addressLine1']);
        }
        if (isset($data['Customer Contact Address addressLine2'])) {
            $address_fields['Address Line 2'] = $data['Customer Contact Address addressLine2'];
            unset($data['Customer Contact Address addressLine2']);
        }
        if (isset($data['Customer Contact Address sortingCode'])) {
            $address_fields['Address Sorting Code'] = $data['Customer Contact Address sortingCode'];
            unset($data['Customer Contact Address sortingCode']);
        }
        if (isset($data['Customer Contact Address postalCode'])) {
            $address_fields['Address Postal Code'] = $data['Customer Contact Address postalCode'];
            unset($data['Customer Contact Address postalCode']);
        }

        if (isset($data['Customer Contact Address dependentLocality'])) {
            $address_fields['Address Dependent Locality'] = $data['Customer Contact Address dependentLocality'];
            unset($data['Customer Contact Address dependentLocality']);
        }

        if (isset($data['Customer Contact Address locality'])) {
            $address_fields['Address Locality'] = $data['Customer Contact Address locality'];
            unset($data['Customer Contact Address locality']);
        }

        if (isset($data['Customer Contact Address administrativeArea'])) {
            $address_fields['Address Administrative Area'] = $data['Customer Contact Address administrativeArea'];
            unset($data['Customer Contact Address administrativeArea']);
        }

        //print_r($address_fields);
        // print_r($data);

        //exit;

        $customer = new Public_Customer('new', $data, $address_fields);
        $website_user='';
        if ($customer->id) {
            $this->new_customer_msg = $customer->msg;

            if ($customer->new) {


                $website=get_object('website',$this->get('Store Website Key'));

                $user_data['Website User Handle']=$customer->get('Customer Main Plain Email');
                $user_data['Website User Customer Key']=$customer->id;
                $website_user=$website->create_user($user_data);

                include_once 'utils/new_fork.php';

                global $account;

                $this->new_customer = true;

                $this->new_website_user = $website_user->new;


                new_housekeeping_fork(
                    'au_housekeeping', array(
                    'type'     => 'customer_created',
                    'customer_key' => $customer->id,
                    'website_user_key' => $website_user->id
                ), $account->get('Account Code')
                );

                //$customer->update_full_search();
                //$customer->update_location_type();
               // $store->update_customers_data();


            } else {
                $this->error = true;
                $this->msg   = $customer->msg;

            }

            return array($customer,$website_user);
        } else {
            $this->error = true;
            $this->msg   = $customer->msg;
        }
    }


}


?>
