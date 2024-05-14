<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 May 2017 at 09:14:56 GMT-5, CdMx, Mexico
 Copyright (c) 2017, Inikoo

 Version 3

*/


class Public_Website
{

    public $editor = array(
        'Author Name'  => false,
        'Author Alias' => false,
        'Author Key'   => 0,
        'User Key'     => 0,
        'Date'         => false
    );

    function __construct($a1, $a2 = false, $a3 = false)
    {
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


    function get_data($tag, $key)
    {
        if ($tag == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Website Dimension` WHERE `Website Key`=%d",
                $key
            );
        } else {
            if ($tag == 'code') {
                $sql = sprintf(
                    "SELECT  * FROM `Website Dimension` WHERE `Website Code`=%s ",
                    prepare_mysql($key)
                );
            } else {
                return;
            }
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id   = $this->data['Website Key'];
            $this->code = $this->data['Website Code'];


            if (empty($this->data['Website Settings'])) {
                $this->settings = array();
            } else {
                //  echo setlocale(LC_NUMERIC, 0);


                $this->settings = json_decode($this->data['Website Settings'], true);

                //  print_r($this->settings);
                //
                // exit;

            }

            if (empty($this->data['Website Style'])) {
                $this->style = array();
            } else {
                $this->style = json_decode($this->data['Website Style'], true);
            }


            if (empty($this->data['Website Mobile Style'])) {
                $this->mobile_style = array();
            } else {
                $this->mobile_style = json_decode($this->data['Website Mobile Style'], true);
            }
        }
    }

    function get_webpage($code)
    {
        if ($code == '') {
            $code = 'home.sys';
        }
        include_once 'class.Public_Webpage.php';

        $webpage = new Public_Webpage('website_code', $this->id, $code);

        return $webpage;
    }

    function get_system_webpage_key($code)
    {
        $sql = sprintf(
            'SELECT `Page Key` FROM `Page Store Dimension` WHERE `Webpage Code`=%s AND `Webpage Website Key`=%d  ',
            prepare_mysql($code),
            $this->id
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

    function get_categories($type = 'families', $output = 'data')
    {
        global $store;

        $categories = array();

        switch ($type) {
            case 'departments':
                $sql = sprintf(
                    'SELECT  `Webpage Code`,`Webpage Name` FROM  `Category Dimension` C   LEFT JOIN `Page Store Dimension` P ON (P.`Webpage Scope Key`=C.`Category Key` AND `Webpage Scope`="Category Categories" ) WHERE   C.`Category Parent Key`=%d ',

                    $store->get('Store Department Category Key')
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

    function get($key = '')
    {
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


            case 'Website Store Key':
            case 'Website Locale':
            case 'Website Name':
            case 'Website Footer Key';
            case 'Website Header Key';
            case 'Website Alt Department Category Key':
            case 'Website Alt Family Category Key':
            case 'Website Status';
            case 'Website Theme':
            case 'Website Type':
            case 'Website URL':
            case 'Website Code':

            case 'Website Client Analytics Code':
            case 'Website Primary Color':
            case 'Website Secondary Color':
            case 'Website Button Color':
            case 'Website Button Text Color':
            case 'Website Active Button Color':
            case 'Website Active Button Text Color':

            case 'Website Accent Color':
            case 'Website Zendesk Chat Code':
            case 'Website Tawk Chat Code':
            case 'Website Sumo Code':
            case 'Website One Signal Code':
            case 'Website Google Tag Manager Code':
            case 'Website Text Font':
            case 'Website Registration Type':
            case 'Website Google Adwords Tag Manager Data':

                return $this->data[$key];
            case 'Store Key':
                return $this->data['Website '.$key];
            case 'Website Settings Browser Title Format':


                if ($this->settings('Browser Title Format') == '') {
                    return '[Webpage]';
                } else {
                    return $this->settings('Browser Title Format');
                }
            case 'header_background_type':

            case 'content_background_type':
            case 'background_type':


                if ($this->settings($key) == '') {
                    return 'no_repeat';
                } else {
                    return $this->settings($key);
                }
            case 'Currency Code':
                $sql  = "select `Store Currency Code` from `Store Dimension` where `Store Key`=?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(
                    array(
                        $this->data['Website Store Key']
                    )
                );
                if ($row = $stmt->fetch()) {
                    return $row['Store Currency Code'];
                } else {
                    return '';
                }
        }
    }

    function settings($key)
    {
        return (isset($this->settings[$key]) ? $this->settings[$key] : '');
    }

    function get_payment_accounts($delivery_2alpha_country = '', $options = '',$customer =false)
    {
        $payments_accounts = array();

        $sql =
            "SELECT `Payment Account Store Payment Account Key`,`hide` FROM `Payment Account Store Bridge` WHERE `Payment Account Store Website Key`=? 
            AND `Payment Account Store Status`='Active' AND `Payment Account Store Show in Cart`='Yes'  ORDER BY `Payment Account Store Show Cart Order`";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id

            )
        );
        $count = 0;



        while ($row = $stmt->fetch()) {


            $payment_account = get_object('Payment_Account', $row['Payment Account Store Payment Account Key']);


            $ok = true;


            switch ($payment_account->get('Payment Account Block')) {
                case 'BTree':
                case 'Checkout':
                    $icon            = 'fa fa-credit-card';
                    $tab_label_index = '_credit_card_label';
                    $tab_label       = '';
                    $short_label     = '<i class="fa fa-credit-card" aria-hidden="true"></i>';
                    $analytics_label = 'Credit card';
                    break;
                case 'Paypal':
                case 'BTreePaypal':
                    $icon            = 'fab fa-paypal';
                    $tab_label       = 'Paypal';
                    $tab_label_index = '';
                    $short_label     = '<i class="fab fa-paypal" aria-hidden="true"></i>';
                    $analytics_label = 'Paypal';

                    break;
                case 'Sofort':
                    $icon            = 'fa fa-hand-peace ';
                    $tab_label       = 'Sofort';
                    $tab_label_index = '';
                    $short_label     = '<i class="fa fa-hand-peace" aria-hidden="true"></i>';
                    $analytics_label = 'Sofort';
                    break;
                case 'Bank':

                    $icon            = 'fa fa-university';
                    $tab_label_index = '_bank_label';
                    $tab_label       = '';
                    $short_label     = '';
                    $analytics_label = 'Bank';
                    break;
                case 'Hokodo':

                    if (in_array($options, $payment_account->get('Valid Delivery Countries'))) {
                        $icon            = 'fa fa-money-check-alt';
                        $tab_label_index = '_pay_later_label';
                        $tab_label       = '';
                        $short_label     = _('Pay later');
                        $analytics_label = 'Hokodo';
                    } else {
                        $ok = false;
                    }
                    break;
                case 'Pastpay':


                    $pass=false;
                    if($options=='GB'){
                        $pass=true;
                    }else{

                        if (

                            $customer and

                            $customer->get('Customer Tax Number Valid')=='Yes' and
                            $customer->get('Customer Tax Number')!=''  and




                            in_array($options, ['HU','PL','SK','CZ','DE','RO','IT',
                                'FR','NL','BE','ES','DK','SE','PT','EE','LV','BG','SI','CH'
                            ])) {
                                $pass=true;
                        }
                    }



                    if ($pass) {
                        $icon            = 'fa fa-money-check-alt';
                        $tab_label_index = '_pastpay_label';
                        $tab_label       = '';
                        $short_label     = _('Pay later');
                        $analytics_label = 'Pastpay';
                    } else {
                        $ok = false;
                   }
                    break;
                case 'ConD':


                    if (in_array($delivery_2alpha_country, $payment_account->get('Valid Delivery Countries'))) {
                        $icon            = 'fa fa-handshake';
                        $tab_label_index = '_cash_on_delivery_label';
                        $tab_label       = '';
                        $short_label     = '';
                        $analytics_label = 'Cash on delivery';
                    } else {
                        $ok = false;
                    }

                    break;
                default:
                    $ok = false;
            }

            if ($options == 'top_up') {
                if (!($payment_account->get('Payment Account Block') == 'BTree' or $payment_account->get('Payment Account Block') == 'BTreePaypal' or $payment_account->get('Payment Account Block') == 'Checkout')) {
                    $ok = false;
                }
            }

            $count++;
            if ($ok) {
                $payments_accounts[] = array(
                    'block'           => $payment_account->get('Payment Account Block'),
                    'count'           => $count,
                    'object'          => $payment_account,
                    'icon'            => $icon,
                    'tab_label_index' => $tab_label_index,
                    'tab_label'       => $tab_label,
                    'short_label'     => $short_label,
                    'analytics_label' => $analytics_label,
                    'hide'            => $row['hide']
                );
            }
        }



        return $payments_accounts;
    }


    function create_user($data)
    {
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

            return $user;
        } else {
            $this->error = true;
            $this->msg   = $user->msg;
        }
    }


    function get_poll_queries($webpage, $customer_key = 0)
    {
        $poll_queries = array();

        switch ($webpage->get('Webpage Code')) {
            case 'profile.sys':
                $where        = ' and `Customer Poll Query In Profile`="Yes" ';
                $customer_key = $customer_key;
                break;
            case 'register.sys':
                $where        = ' and `Customer Poll Query In Registration`="Yes" ';
                $customer_key = 0;
                break;
            default:
                return array();
        }


        $sql = sprintf(
            'SELECT `Customer Poll Query Key`,`Customer Poll Query Label`,`Customer Poll Query Type`,`Customer Poll Query Options`,`Customer Poll Query Registration Required` FROM `Customer Poll Query Dimension` WHERE `Customer Poll Query Store Key`=%d  %s ORDER BY `Customer Poll Query Position`',
            $this->data['Website Store Key'],
            $where

        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Customer Poll Query Type'] == 'Options') {
                    if ($row['Customer Poll Query Options'] < 2) {
                        continue;
                    }
                    $options = array();
                    $sql     = sprintf(
                        'SELECT `Customer Poll Query Option Key`,`Customer Poll Query Option Label` FROM `Customer Poll Query Option Dimension` WHERE `Customer Poll Query Option Query Key`=%d ORDER BY `Customer Poll Query Option Label` ',
                        $row['Customer Poll Query Key']
                    );
                    if ($result2 = $this->db->query($sql)) {
                        foreach ($result2 as $row2) {
                            $options[] = $row2;
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }

                    if (count($options) < 2) {
                        continue;
                    }

                    $row['Options'] = $options;
                    $reply          = 0;
                    if ($customer_key) {
                        $sql = sprintf('SELECT `Customer Poll Query Option Key` FROM `Customer Poll Fact` WHERE `Customer Poll Customer Key`=%d AND `Customer Poll Query Key`=%d ', $customer_key, $row['Customer Poll Query Key']);
                        if ($result2 = $this->db->query($sql)) {
                            if ($row2 = $result2->fetch()) {
                                $reply = $row2['Customer Poll Query Option Key'];
                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }
                    }
                    $row['Reply'] = $reply;
                } else {
                    $reply = '';
                    if ($customer_key) {
                        $sql = sprintf('SELECT `Customer Poll Reply` FROM `Customer Poll Fact` WHERE `Customer Poll Customer Key`=%d AND `Customer Poll Query Key`=%d ', $customer_key, $row['Customer Poll Query Key']);
                        if ($result2 = $this->db->query($sql)) {
                            if ($row2 = $result2->fetch()) {
                                $reply = $row2['Customer Poll Reply'];
                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }
                    }
                    $row['Reply'] = $reply;
                }


                $poll_queries[] = $row;
            }
        }

        return $poll_queries;
    }

    function get_api_key($code)
    {

        if (ENVIRONMENT=='DEVEL' and $code=='Hokodo' ) {
            return HOKODO_DEVEL_KEY;
        }


        $api_key = '';
        $sql     =
            "SELECT login 
FROM `Payment Account Store Bridge` B left join `Payment Account Dimension` PAD on PAD.`Payment Account Key`=B.`Payment Account Store Payment Account Key`  
WHERE `Payment Account Store Website Key`=? AND `Payment Account Store Status`='Active' AND `Payment Account Store Show in Cart`='Yes' and `Payment Account Block`=? ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            [
                $this->id,
                $code
            ]
        );

        while ($row = $stmt->fetch()) {
            $api_key = $row['login'];
        }


        return $api_key;
    }

    function get_public_api_key($code)
    {
        $api_key = '';
        $sql     =
            "SELECT public_key 
FROM `Payment Account Store Bridge` B left join `Payment Account Dimension` PAD on PAD.`Payment Account Key`=B.`Payment Account Store Payment Account Key`  
WHERE `Payment Account Store Website Key`=? AND `Payment Account Store Status`='Active' AND `Payment Account Store Show in Cart`='Yes' and `Payment Account Block`=? ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            [
                $this->id,
                $code
            ]
        );

        while ($row = $stmt->fetch()) {
            $api_key = $row['public_key'];
        }


        return $api_key;
    }

    function get_payment_account__key($code)
    {


        $payment_account__key = '';
        $sql                  =
            "SELECT `Payment Account Store Payment Account Key`
FROM `Payment Account Store Bridge` B left join `Payment Account Dimension` PAD on PAD.`Payment Account Key`=B.`Payment Account Store Payment Account Key`  
WHERE `Payment Account Store Website Key`=? AND `Payment Account Store Status`='Active' AND `Payment Account Store Show in Cart`='Yes' and `Payment Account Block`=? ";
        $stmt                 = $this->db->prepare($sql);
        $stmt->execute(
            [
                $this->id,
                $code
            ]
        );

        while ($row = $stmt->fetch()) {
            $payment_account__key = $row['Payment Account Store Payment Account Key'];
        }


        return $payment_account__key;
    }

    function hokodo_search_company_valid_countries()
    {
        $countries = '';
        if ($payment_account_key = $this->get_payment_account__key('Hokodo')) {
            $payment_account = get_object('payment_account', $payment_account_key);
            if ($payment_account->id and $payment_account->get('Payment Account Settings')) {
                $countries = explode(',', $payment_account->get('Payment Account Settings'));
            }
        }

        return $countries;
    }


}



