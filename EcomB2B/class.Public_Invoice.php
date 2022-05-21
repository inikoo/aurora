<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
Created: 29 August 2018 at 04:10:36 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 2.0
*/


include_once 'class.DBW_Table.php';


class Public_Invoice extends DBW_Table {

    /**
     * @var array
     */
    public $metadata;

    function __construct($arg1 = false, $arg2 = false, $arg3 = false, $arg4 = false) {

        $this->table_name      = 'Invoice';
        $this->ignore_fields   = array('Invoice Key');

        $this->metadata        = array();

        global $db;
        $this->db = $db;

        if (!$arg1 and !$arg2) {
            $this->error = true;
            $this->msg   = 'No data provided';

            return;
        }
        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }

        $this->get_data($arg1, $arg2);
    }


    function get_data($tipo, $tag) {
        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Invoice Dimension` WHERE  `Invoice Key`=%d", $tag
            );
        } elseif ($tipo == 'public_id') {
            $sql = sprintf(
                "SELECT * FROM `Invoice Dimension` WHERE  `Invoice Public ID`=%s", prepare_mysql($tag)
            );
        } else {
            return;
        }
        //print $sql;


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Invoice Key'];
            $this->metadata = json_decode($this->data['Invoice Metadata'], true);

        }


    }


    function get($key) {

        if (!$this->id) {
            return false;
        }


        switch ($key) {

            case 'Currency Code':

                return $this->data['Invoice Currency'];
                break;

            case('Items Gross Amount'):
            case('Items Discount Amount'):
            case('Items Net Amount'):
            case('Items Tax Amount'):
            case('Refund Net Amount'):
            case('Charges Net Amount'):
            case('Shipping Net Amount'):
            case('Insurance Net Amount'):
            case('Total Net Amount'):
            case('Total Tax Amount'):
            case('Total Amount'):
            case('Total Net Adjust Amount'):
            case('Total Tax Adjust Amount'):
            case('Outstanding Total Amount'):
            case('Credit Net Amount'):
            case('Credit Net Amount'):
            case('Total Profit'):

                return money(
                    $this->data['Invoice '.$key], $this->data['Invoice Currency']
                );

            case('Refund Items Gross Amount'):
            case('Refund Items Discount Amount'):
            case('Refund Items Net Amount'):
            case('Refund Items Tax Amount'):
            case('Refund Charges Net Amount'):
            case('Refund Shipping Net Amount'):
            case('Refund Insurance Net Amount'):
            case('Refund Total Net Amount'):
            case('Refund Total Tax Amount'):
            case('Refund Total Amount'):
            case('Refund Total Net Adjust Amount'):
            case('Refund Total Tax Adjust Amount'):
            case('Refund Payments Amount'):
            case 'Refund To Pay Amount':
                $key = preg_replace('/Refund /', '', $key);


                if ($this->data['Invoice '.$key] == 0) {
                    return money(0, $this->data['Invoice Currency']);

                } else {
                    return money(
                        -1 * $this->data['Invoice '.$key], $this->data['Invoice Currency']
                    );
                }


                break;
            case ('Net Amount Off'):
                return money(
                    -1 * $this->data['Invoice '.$key], $this->data['Invoice Currency']
                );

                break;

            case('Corporate Currency Total Amount'):
                global $corporate_currency;
                $_key = preg_replace('/Corporate Currency /', '', $key);

                return money(
                    $this->data['Invoice '.$_key] * $this->data['Invoice Currency Exchange'], $corporate_currency
                );
                break;
            case('Date'):
                return strftime(
                    "%a %e %b %Y %H:%M %Z", strtotime($this->data['Invoice Date'].' +0:00')
                );
                break;
            case('Payment Method'):

                switch ($this->data['Invoice Main Payment Method']) {
                    case 'Credit Card':
                        return _('Credit Card');
                        break;
                    case 'Cash':
                        return _('Cash');
                        break;
                    case 'Paypal':
                        return _('Paypal');
                        break;
                    case 'Check':
                        return _('Check');
                        break;
                    case 'Bank Transfer':
                        return _('Bank Transfer');
                        break;
                    case 'Other':
                        return _('Other');
                        break;
                    case 'Unknown':
                        return _('Unknown');
                        break;

                    default:
                        return $this->data['Invoice Main Payment Method'];
                        break;
                }
                break;
            case('Payment State'):
                return $this->get_formatted_payment_state();

            case 'State':

                switch ($this->data['Invoice Paid']) {
                    case 'Yes':
                        return _('Paid');
                        break;
                    case 'No':
                        return _('Not paid');
                        break;
                    case 'Partially':
                        return _('Partially paid');
                        break;
                    default:
                        return $this->data['Invoice Paid'];
                        break;
                }
            case 'Margin':
                if($this->data['Invoice Items Net Amount']>0){
                    return percentage($this->data['Invoice Total Profit'] / $this->data['Invoice Items Net Amount'], 1);
                }else{
                    return '-';
                }
                break;

        }


        if (isset($this->data[$key])) {
            return $this->data[$key];
        }


        if (array_key_exists('Invoice '.$key, $this->data)) {
            return $this->data[$this->table_name.' '.$key];
        }


        return false;
    }

    function get_formatted_payment_state() {

        if ($this->data['Invoice Type'] == 'Refund') {
            switch ($this->data['Invoice Paid']) {
                case 'Yes':
                    return _('Paid back in full');
                    break;
                case 'No':
                    return _('Not paid back');
                    break;
                case 'Partially':
                    return _('Partially paid back');
                    break;
                default:
                    return _('Unknown');

            }
        } else {
            switch ($this->data['Invoice Paid']) {
                case 'Yes':
                    return _('Paid in full');
                    break;
                case 'No':
                    return _('Not paid');
                    break;
                case 'Partially':
                    return _('Partially Paid');
                    break;
                default:
                    return _('Unknown');

            }
        }


    }

    function get_date($field) {
        return strftime("%e %b %Y", strtotime($this->data[$field].' +0:00'));
    }

    function get_payments($scope = 'keys', $filter = '') {


        if ($filter == 'Completed') {
            $where = ' and `Payment Transaction Status`="Completed" ';
        }elseif ($filter == 'Pending') {
            $where = ' and `Payment Transaction Status` in ("Pending","Approving") ';
        } else {
            $where = '';
        }


        $payments = array();
        $sql      = sprintf(
            "SELECT B.`Payment Key` FROM `Order Payment Bridge` B LEFT JOIN `Payment Dimension` P  ON (P.`Payment Key`=B.`Payment Key`)  WHERE `Invoice Key`=%d %s ", $this->id, $where
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Payment Key'] == '') {
                    continue;
                }

                if ($scope == 'objects') {
                    $_object = get_object('Payment', $row['Payment Key']);
                    $_object->load_payment_account();
                    $payments[$row['Payment Key']] = $_object;

                } else {
                    $payments[$row['Payment Key']] = $row['Payment Key'];
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $payments;

    }

    function add_payment($payment) {

        $payment->update(array('Payment Invoice Key' => $this->id), 'no_history');

        $sql = sprintf('UPDATE `Order Payment Bridge` SET `Invoice Key`=%d WHERE `Payment Key`=%d ', $this->id, $payment->id);

        $this->db->exec($sql);
        $this->update_payments_totals();


    }

    function metadata($key) {
        return (isset($this->metadata[$key]) ? $this->metadata[$key] : '');
    }


}


