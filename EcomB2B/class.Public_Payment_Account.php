<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Created: 28 July 2017 at 17:59:18 CEST, Tranava, Slovakia

 Copyright (c) 2017, Inikoo
 Version 2.0
*/

/**
 * Class Public_Payment_Account
 */
class Public_Payment_Account {

    /**
     * @var \PDO
     */
    public $db;

    function __construct($arg1 = false, $arg2 = false, $_db = false) {

        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }



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


    function create_payment($data) {

        $data['Payment Account Key']          = $this->id;
        $data['Payment Account Code']         = $this->data['Payment Account Code'];
        $data['Payment Service Provider Key'] = $this->data['Payment Account Service Provider Key'];
        $data['editor']                       = $this->editor;


        $account = get_object('Account', 1);
        if ($account->get('Currency Code') != $data['Payment Currency Code']) {
            include_once 'utils/currency_functions.php';
            $data['Payment Currency Exchange Rate'] = currency_conversion($this->db, $data['Payment Currency Code'], $account->get('Currency Code'));
        } else {
            $data['Payment Currency Exchange Rate'] = 1;
        }


        include_once 'class.Public_Payment.php';

        $payment = new Public_Payment('new', $data);
        if ($payment->error) {
            $this->error = true;
            $this->msg   = $payment->msg;

            return $payment;
        }


        require_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'        => 'payment_created',
            'payment_key' => $payment->id,
            'editor'      => $payment->editor
        ), $account->get('Account Code'), $this->db
        );


        return $payment;

    }

    function get($key = '', $arg = '') {


        switch ($key) {

            case 'Valid Delivery Countries':

                $_tmp = array();

                if ($this->data['Payment Account Settings'] != '') {


                    $_tmp = preg_split('/\,/', $this->data['Payment Account Settings']);
                }

                return $_tmp;


            case 'Block Data':


                if ($this->data['Payment Account Block'] == 'Paypal') {


                    /**
                     * @var $arg \Public_Order
                     */


                    $key = 'xx';


                    $paypal_data = base64_url_encode(
                        AESEncryptCtr(
                            json_encode(
                                array(
                                    'braintree_account_key'    => $this->id,
                                    'Payment Account ID'       => $this->get('Payment Account ID'),
                                    'Payment Account Login'    => $this->get('Payment Account Login'),
                                    'Payment Account Password' => $this->get('Payment Account Password'),
                                    'order_key'                => $arg->id,
                                    'amount'                   => $arg->get('Order To Pay Amount'),
                                    'currency'                 => $arg->get('Order Currency'),
                                    'Random'                   => password_hash(time(), PASSWORD_BCRYPT)
                                )
                            ), $key, 256
                        )
                    );


                    return $paypal_data;



                }
                elseif ($this->data['Payment Account Block'] == 'BTree') {


                    $gateway = new Braintree_Gateway(
                        [
                            'environment' => 'production',
                            'merchantId'  => $this->get('Payment Account ID'),
                            'publicKey'   => $this->get('Payment Account Login'),
                            'privateKey'  => $this->get('Payment Account Password')
                        ]
                    );


                    $credit_cards = array();
                    try {
                        $braintree_customer = $gateway->customer()->find($arg);


                        include_once 'utils/aes.php';


                        foreach ($braintree_customer->creditCards as $braintree_credit_card) {


                            $token = AESEncryptCtr(
                                json_encode(
                                    array(
                                        't' => $braintree_credit_card->token,
                                        's' => mt_rand(1, 10000)
                                    )
                                ), md5('CCToken'.CKEY), 256
                            );

                            $credit_cards[] = array(
                                'Masked Number'             => $braintree_credit_card->maskedNumber,
                                'Last 4 Numbers'            => $braintree_credit_card->last4,
                                'Image'                     => $braintree_credit_card->imageUrl,
                                'Formatted Expiration Date' => $braintree_credit_card->expirationDate,
                                'Token'                     => $token
                            );
                        }


                    } catch (Exception $e) {

                    }


                    $_data = array(

                        'client_token'              => $gateway->clientToken()->generate(),
                        'credit_cards'              => $credit_cards,
                        'number_saved_credit_cards' => count($credit_cards)
                    );


                    return $_data;



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
