<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 May 2017 at 09:14:56 GMT-5, CdMx, Mexico
 Copyright (c) 2017, Inikoo

 Version 3

*/


class Public_Website {

    public $editor = array(
        'Author Name'  => false,
        'Author Alias' => false,
        'Author Key'   => 0,
        'User Key'     => 0,
        'Date'         => false
    );

    function Public_Website($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db = $db;

        $this->id            = false;
        $this->table_name    = 'Website';
        $this->ignore_fields = array('Website Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } elseif ($a1 == 'find') {
            $this->find($a2, $a3);

        } else {
            $this->get_data($a1, $a2);
        }
    }


    function get_data($tag, $key) {


        if ($tag == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Website Dimension` WHERE `Website Key`=%d", $key
            );
        } else {
            if ($tag == 'code') {
                $sql = sprintf(
                    "SELECT  * FROM `Website Dimension` WHERE `Website Code`=%s ", prepare_mysql($key)
                );
            } else {
                return;
            }
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id   = $this->data['Website Key'];
            $this->code = $this->data['Website Code'];
        }


    }

    function get_webpage($code) {

        if ($code == '') {
            $code = 'p.home';
        }

        $webpage = new Webpage('website_code', $this->id, $code);

        return $webpage;


    }

    function get_default_template_key($scope, $device = 'Desktop') {

        $template_key = false;

        $sql = sprintf(
            'SELECT `Template Key` FROM `Template Dimension` WHERE `Template Website Key`=%d AND `Template Scope`=%s AND `Template Device`=%s ', $this->id, prepare_mysql($scope),
            prepare_mysql($device)

        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $template_key = $row['Template Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        if (!$template_key) {


            $sql = sprintf(
                'SELECT `Template Key` FROM `Template Dimension` WHERE `Template Website Key`=%d AND `Template Scope`=%s AND `Template Device`="Desktop" ', $this->id, prepare_mysql($scope)

            );
            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $template_key = $row['Template Key'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }

        }

        if (!$template_key) {


            $sql = sprintf(
                'SELECT `Template Key` FROM `Template Dimension` WHERE `Template Website Key`=%d AND `Template Scope`="Blank" AND `Template Device`=%s ', $this->id, prepare_mysql($scope)

            );
            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $template_key = $row['Template Key'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }

        }

        // print $template_key;


        return $template_key;

    }

    function get_system_webpage_key($code) {

        $sql = sprintf(
            'SELECT `Page Key` FROM `Page Store Dimension` WHERE `Webpage Code`=%s AND `Webpage Website Key`=%d  ', prepare_mysql($code), $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                return $row['Page Key'];
            } else {
                return 0;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

    }

    function get_categories($type = 'families', $output = 'data') {

        $categories = array();


        switch ($type) {
            case 'departments':
                $sql = sprintf(
                    'SELECT  `Webpage Code`,`Webpage Name` FROM  `Category Dimension` C   LEFT JOIN `Page Store Dimension` P ON (P.`Webpage Scope Key`=C.`Category Key` AND `Webpage Scope`="Category Categories" ) WHERE   C.`Category Parent Key`=%d ',

                    $this->get('Website Alt Department Category Key')
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {

                        switch ($output) {
                            case 'menu':
                                $categories[] = array(
                                    'url'   => $row['Webpage Code'],
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

                break;
        }


    }

    function get($key = '') {


        if (!$this->id) {
            return '';
        }


        switch ($key) {

            case 'Footer Data':
            case 'Footer Published Data':

                $sql = sprintf('SELECT `Website %s` AS data FROM `Website Footer Dimension` WHERE `Website Footer Key`=%d  ', $key, $this->get('Website Footer Key'));


                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {


                        return json_decode($row['data'], true);
                    } else {
                        return false;
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }
                break;
            case 'Header Data':
            case 'Header Published Data':

                $sql = sprintf('SELECT `Website %s` AS data FROM `Website Header Dimension` WHERE `Website Header Key`=%d  ', $key, $this->get('Website Header Key'));
                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        return json_decode($row['data'], true);
                    } else {
                        return false;
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }
                break;
            case 'Localised Labels':

                if ($this->data['Website '.$key] == '') {
                    $labels = array();
                } else {
                    $labels = json_decode($this->data['Website '.$key], true);
                }

                return $labels;
                break;

            case 'Website Store Key':
            case 'Website Locale':
            case 'Website Footer Key';
            case 'Website Header Key';
            case 'Website Alt Department Category Key':
            case 'Website Alt Family Category Key':
            case 'Website Status';
            case 'Website Theme':
            case 'Website Type':
            case 'Website URL':
            case 'Website Client Analytics Code':
            case 'Website Primary Color':
            case 'Website Secondary Color':
            case 'Website Accent Color':
                return $this->data[$key];
                break;

        }


    }


    function get_payment_accounts() {

        $payments_accounts = array();

        $sql = sprintf(
            'SELECT `Payment Account Store Payment Account Key` FROM `Payment Account Store Bridge` WHERE `Payment Account Store Website Key`=%d AND `Payment Account Store Status`="Active" AND `Payment Account Store Show in Cart`="Yes"  ORDER BY `Payment Account Store Show Cart Order`    ', $this->id
        );

       

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $payment_account = get_object('Payment_Account', $row['Payment Account Store Payment Account Key']);
                switch ($payment_account->get('Payment Account Block')) {
                    case 'BTree':
                        $icon            = 'fa-credit-card';
                        $tab_label_index = '_credit_card_label';
                        $tab_label       = '';
                        break;
                    case 'BTreePaypal':
                        $icon            = 'fa-paypal';
                        $tab_label       = 'Paypal';
                        $tab_label_index = '';
                        break;
                    case 'Paypal':
                        $icon            = 'fa-paypal';
                        $tab_label       = 'Paypal';
                        $tab_label_index = '';
                        break;
                    case 'Bank':
                        $icon            = 'fa-university';
                        $tab_label_index = '_bank_label';
                        $tab_label       = '';
                        break;
                    default:


                }


                $payments_accounts[] = array(
                    'object'          => $payment_account,
                    'icon'            => $icon,
                    'tab_label_index' => $tab_label_index,
                    'tab_label'       => $tab_label
                );

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

      //  print_r($payments_accounts);

        return $payments_accounts;

    }


    function create_user($data) {

        include_once 'class.Public_Website_User.php';

        $this->new = false;

        $data['editor']                   = $this->editor;
        $data['Website User Website Key'] = $this->id;
        $data['Website User Active']      = 'Yes';


        $user = new Public_Website_User('new', $data);

        if ($user->id) {

            if ($user->new) {


                return $user;


            } else {
                $this->error = true;
                $this->msg   = $user->msg;

            }

            return $customer;
        } else {
            $this->error = true;
            $this->msg   = $user->msg;
        }
    }


}


?>
