<?php

/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Created: 28 July 2017 at 17:59:18 CEST, Tranava, Slovakia

 Copyright (c) 2017, Inikoo
 Version 2.0
*/


class Public_Payment_Account  {


    function Public_Payment_Account($arg1 = false, $arg2 = false) {

        global $db;
        $this->db            = $db;
        $this->table_name    = 'Payment Account';
        $this->ignore_fields = array('Payment Account Key');

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }
       
        $this->get_data($arg1, $arg2);

        return;

    }


    function get_data($tipo, $tag) {

        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Payment Account Dimension` WHERE `Payment Account Key`=%d", $tag
            );
        } else {
            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Payment Account Key'];
        }
    }


   

 
    function get($key = '',$arg='') {


        if (isset($this->data[$key])) {
            return $this->data[$key];
        }




        switch ($key) {
            case 'Block Data':


                if($this->data['Payment Account Block']='BTreePaypal'){





                    $paypal_data=base64_url_encode(AESEncryptCtr(
                                                   json_encode(
                                                       array(
                                                           'braintree_account_key'=>$this->id,
                                                           'Payment Account ID'=>$this->get('Payment Account ID'),
                                                           'Payment Account Login'=>$this->get('Payment Account Login'),
                                                           'Payment Account Password'=>$this->get('Payment Account Password'),
                                                           'order_key'=>$arg->id,
                                                           'amount'=>$arg->get('Order To Pay Amount'),
                                                           'currency'=>$arg->get('Order Currency'),
                                                           'Random'=>password_hash(time(), PASSWORD_BCRYPT)
                                                       )
                                                   ),md5('83edh3847203942,'.CKEY),256));


                    return $paypal_data;

                }

            break;

        }

        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        if (array_key_exists('Payment Account '.$key, $this->data)) {
            return $this->data[$this->table_name.' '.$key];
        }

        return false;

    }




    function in_website($website_key) {
        $is_in_website = false;
        $sql           = sprintf(
            "SELECT count(*) AS num FROM `Payment Account Store Bridge` WHERE `Website Key`=%d AND `Payment Account Key`=%d ", $website_key, $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $is_in_website = true;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $is_in_website;
    }


    function is_active_in_website($website_key) {
        $is_active_in_website = false;
        $sql                  = sprintf(
            "SELECT count(*) AS num FROM `Payment Account Store Bridge` WHERE `Website Key`=%d AND `Payment Account Key`=%d AND `Status`='Active'  ", $website_key, $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $is_active_in_website = true;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $is_active_in_website;
    }



    function get_settings() {
        return json_decode($this->data['Payment Account Settings'], true);

    }



    function get_field_label($field) {

        switch ($field) {

            case 'Payment Account Code':
                $label = _('code');
                break;
            case 'Payment Account Name':
                $label = _('name');
                break;
            case 'Payment Account Recipient Holder':
                $label = _('Account beneficiary');
                break;
            case 'Payment Account Recipient Address':
                $label = _('Bank address');
                break;
            case 'Payment Account Recipient Bank Account Number':
                $label = _('account number');
                break;
            case 'Payment Account Recipient Bank Code':
                $label = _('Bank code');
                break;
            case 'Payment Account Recipient Bank Name':
                $label = _('bank name');
                break;
            case 'Payment Account Recipient Bank Swift':
                $label = 'Bank SWIFT/BIC code';
                break;
            case 'Payment Account Recipient Bank IBAN':
                $label = _('Account IBAN');
                break;



            default:
                $label = $field;

        }

        return $label;

    }



}
