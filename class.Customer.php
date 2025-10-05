<?php

/*
  File: Customer.php

  This file contains the Customer Class

  About:
  Author: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0


*/
include_once 'class.Subject.php';
include_once 'class.Order.php';
include_once 'trait.CustomerAiku.php';

class Customer extends Subject
{

    use CustomerAiku;

    /**
     * @var \PDO
     */
    public $db;
    /**
     * @var mixed|string
     */
    private $label;


    function __construct($arg1 = false, $arg2 = false, $arg3 = false, $_db = false)
    {
        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }

        $this->label         = _('Customer');
        $this->table_name    = 'Customer';
        $this->ignore_fields = array(
            'Customer Key',

            'Customer Order Interval',
            'Customer Order Interval STD',
            'Customer Orders Top Percentage',
            'Customer Invoices Top Percentage',
            'Customer Balance Top Percentage',
            'Customer Profits Top Percentage',
            'Customer First Order Date',
            'Customer Last Order Date'
        );


        if (is_numeric($arg1) and !$arg2) {
            $this->get_data('id', $arg1);

            return;
        }


        if ($arg1 == 'new') {
            $this->find($arg2, $arg3, 'create');

            return;
        }


        $this->get_data($arg1, $arg2);
    }

    function get_data($tag, $id)
    {
        if ($tag == 'email') {
            $sql = sprintf(
                "SELECT * FROM `Customer Dimension` WHERE `Customer Main Plain Email`=%s",
                prepare_mysql($id)
            );
        } elseif ($tag == 'deleted') {
            $this->get_deleted_data($id);

            return;
        } else {
            $sql = sprintf(
                "SELECT * FROM `Customer Dimension` WHERE `Customer Key`=%s",
                prepare_mysql($id)
            );
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id       = $this->data['Customer Key'];
            $this->metadata = json_decode($this->data['Customer Metadata'], true);

            if ($this->data['Customer Fulfilment'] == 'Yes') {
                $sql   = 'select * from  `Customer Fulfilment Dimension` where `Customer Fulfilment Customer Key`=?';
                $stmt2 = $this->db->prepare($sql);
                $stmt2->execute(array(
                                    $this->id
                                ));
                if ($row2 = $stmt2->fetch()) {
                    foreach ($row2 as $key => $value) {
                        $this->data[$key] = $value;
                    }
                }
            }
        }
    }

    function get_deleted_data($id)
    {
        $this->deleted = true;
        $sql           = sprintf("SELECT * FROM `Customer Deleted Dimension` WHERE `Customer Key`=%d", $id);

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Customer Key'];
        }
    }

    function find($raw_data, $address_raw_data, $options = '')
    {
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


        if (!isset($raw_data['Customer Store Key']) or !preg_match('/^\d+$/i', $raw_data['Customer Store Key'])) {
            $this->error = true;
            $this->msg   = 'missing store key';
        }


        $sql = sprintf(
            'SELECT `Customer Key` FROM `Customer Dimension` WHERE `Customer Store Key`=%d AND `Customer Main Plain Email`=%s ',
            $raw_data['Customer Store Key'],
            prepare_mysql($raw_data['Customer Main Plain Email'])
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->error = true;
                $this->found = true;
                $this->msg   = _('Another customer with same email has been found');

                return;
            }
        }


        if ($create) {
            $this->create($raw_data, $address_raw_data);
        }
    }

    function create($raw_data, $address_raw_data)
    {
        $this->editor = $raw_data['editor'];
        unset($raw_data['editor']);


        if ($raw_data['Customer Company Name'] == '' and $raw_data['Customer Main Contact Name'] == '') {
            $this->error = true;
            $this->msg   = _('Customer company name or contact name required');

            return false;
        }


        $eori = '';
        if (isset($raw_data['Customer EORI'])) {
            $eori = $raw_data['Customer EORI'];
            unset($raw_data['Customer EORI']);
        }


        $raw_data['Customer First Contacted Date'] = gmdate('Y-m-d H:i:s');
        $raw_data['Customer Sticky Note']          = '';
        $raw_data['Customer Metadata']             = '{}';


        unset($raw_data['Customer Lost Date']);
        unset($raw_data['First Invoiced Order Date']);
        unset($raw_data['Customer Last Invoiced Order Date']);
        unset($raw_data['Customer Tax Number Validation Date']);
        unset($raw_data['Customer Last Order Date']);
        unset($raw_data['Customer First Order Date']);


        $sql = sprintf(
            "INSERT INTO `Customer Dimension` (%s) values (%s)",
            '`'.join('`,`', array_keys($raw_data)).'`',
            join(',', array_fill(0, count($raw_data), '?'))
        );

        $stmt = $this->db->prepare($sql);
        $i    = 1;
        foreach ($raw_data as $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {
            $this->id = $this->db->query("SELECT LAST_INSERT_ID()")->fetchColumn();
            if (!$this->id) {
                throw new Exception('Error inserting '.$this->table_name);
            }


            $this->get_data('id', $this->id);


            $this->fast_update_json_field('Customer Metadata', 'eori', $eori);


            if ($this->data['Customer Company Name'] != '') {
                $customer_name = $this->data['Customer Company Name'];
            } else {
                $customer_name = $this->data['Customer Main Contact Name'];
            }
            $this->update_field('Customer Name', $customer_name, 'no_history');


            $this->update_address('Contact', $address_raw_data, 'no_history');
            $this->update_address('Invoice', $address_raw_data, 'no_history');
            $this->update_address('Delivery', $address_raw_data, 'no_history');


            $this->update(
                array(
                    'Customer Main Plain Mobile'    => $this->get('Customer Main Plain Mobile'),
                    'Customer Main Plain Telephone' => $this->get('Customer Main Plain Telephone'),
                    'Customer Main Plain FAX'       => $this->get('Customer Main Plain FAX'),
                ),
                'no_history'

            );


            $history_data = array(
                'History Abstract' => sprintf(_('%s customer record created'), $this->get('Name')),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data,
                true,
                'No',
                'Changes',
                $this->get_object_name(),
                $this->id
            );

            $this->new = true;
            $this->fork_index_elastic_search();
            $this->model_updated( 'new', $this->id);

            return $this;
        } else {
            $this->error = true;
            $errorInfo = $stmt->errorInfo();
            $errorDetail = '';
            if (is_array($errorInfo)) {
                if (!empty($errorInfo[2])) {
                    $errorDetail = $errorInfo[2];
                } else {
                    $errorDetail = implode(' | ', array_filter($errorInfo));
                }
            }
            $this->msg = 'Error inserting customer record' . ($errorDetail ? ': ' . $errorDetail : '');
        }

        return false;
    }

    function get($key, $arg1 = false)
    {
        if (!$this->id) {
            return false;
        }

        list($got, $result) = $this->get_subject_common($key, $arg1);
        if ($got) {
            return $result;
        }

        switch ($key) {
            case 'Customer EORI':
            case 'EORI':
                return $this->metadata('eori');
            case 'Last Website Visit':

                if ($this->data['Customer Last Website Visit'] != '') {
                    $_tmp = gmdate("U") - gmdate("U", strtotime($this->data['Customer Last Website Visit'].' +0:00'));
                    if ($_tmp < 3600) {
                        $date = strftime("%H:%M:%S %Z", strtotime($this->data['Customer Last Website Visit'].' +0:00'));
                    } elseif ($_tmp < 86400) {
                        $date = strftime(
                            "%e %b %Y %H:%M %Z",
                            strtotime(
                                $this->data['Customer Last Website Visit'].' +0:00'
                            )
                        );
                    } else {
                        $date = strftime(
                            "%e %b %Y",
                            strtotime(
                                $this->data['Customer Last Website Visit'].' +0:00'
                            )
                        );
                    }

                    return $date;
                } else {
                    return '';
                }


            case 'Delivery Address Link':

                if ($this->data['Customer Delivery Address Link'] == 'Billing') {
                    return _('Same as invoice address');
                } elseif ($this->data['Customer Delivery Address Link'] == 'None') {
                    return _('Unrelated to invoice address');
                } else {
                    return _('Unrelated to contact address');
                }

            case 'Fiscal Name':
            case 'Invoice Name':

                if ($this->data['Customer Invoice Address Organization'] != '') {
                    return $this->data['Customer Invoice Address Organization'];
                }
                if ($this->data['Customer Invoice Address Recipient'] != '') {
                    return $this->data['Customer Invoice Address Recipient'];
                }

                return $this->data['Customer Name'];

            case 'Tax Number Formatted':

                switch ($this->data['Customer Tax Number Validation Source']) {
                    case 'Online':
                        $source = ' <i class="fal fa-globe"></i>';
                        break;
                    case 'Staff':
                        $source = ' <i class="fal fa-thumbtack"></i>';
                        break;
                    default:
                        $source = '';
                }

                if ($this->data['Customer Tax Number Validation Date'] != '') {
                    $_tmp = gmdate("U") - gmdate(
                            "U",
                            strtotime(
                                $this->data['Customer Tax Number Validation Date'].' +0:00'
                            )
                        );
                    if ($_tmp < 3600) {
                        $date = strftime(
                            "%e %b %Y %H:%M:%S %Z",
                            strtotime(
                                $this->data['Customer Tax Number Validation Date'].' +0:00'
                            )
                        );
                    } elseif ($_tmp < 86400) {
                        $date = strftime(
                            "%e %b %Y %H:%M %Z",
                            strtotime(
                                $this->data['Customer Tax Number Validation Date'].' +0:00'
                            )
                        );
                    } else {
                        $date = strftime(
                            "%e %b %Y",
                            strtotime(
                                $this->data['Customer Tax Number Validation Date'].' +0:00'
                            )
                        );
                    }
                } else {
                    $date = '';
                }

                $msg = $this->data['Customer Tax Number Validation Message'];

                $title = htmlspecialchars(trim($date.' '.$msg));

                if ($this->data['Customer Tax Number'] != '') {
                    if ($this->data['Customer Tax Number Valid'] == 'Yes') {
                        return sprintf(
                            '<i style="margin-right: 0" class="fa fa-check success" title="'._('Valid').'"></i> <span title="'.$title.'" >%s</span>',
                            $this->data['Customer Tax Number'].$source
                        );
                    } elseif ($this->data['Customer Tax Number Valid'] == 'Unknown') {
                        return sprintf(
                            '<i style="margin-right: 0" class="fal fa-question-circle discreet" title="'._('Unknown if is valid').'"></i> <span class="discreet" title="'.$title.'">%s</span>',
                            $this->data['Customer Tax Number'].$source
                        );
                    } elseif ($this->data['Customer Tax Number Valid'] == 'API_Down') {
                        return sprintf(
                            '<i style="margin-right: 0"  class="fal fa-question-circle discreet" title="'._('Validity is unknown').'"> </i> <span class="discreet" title="'.$title.'">%s</span> %s',
                            $this->data['Customer Tax Number'],
                            ' <i  title="'._('Online validation service down').'" class="fa fa-wifi-slash error"></i>'
                        );
                    } else {
                        return sprintf(
                            '<i style="margin-right: 0" class="fa fa-ban error" title="'._('Invalid').'"></i> <span class="discreet" title="'.$title.'">%s</span>',
                            $this->data['Customer Tax Number'].$source
                        );
                    }
                }
                break;
            case('Tax Number Valid'):
                if ($this->data['Customer Tax Number'] != '') {
                    if ($this->data['Customer Tax Number Validation Date'] != '') {
                        $_tmp = gmdate("U") - gmdate(
                                "U",
                                strtotime(
                                    $this->data['Customer Tax Number Validation Date'].' +0:00'
                                )
                            );
                        if ($_tmp < 3600) {
                            $date = strftime(
                                "%e %b %Y %H:%M:%S %Z",
                                strtotime(
                                    $this->data['Customer Tax Number Validation Date'].' +0:00'
                                )
                            );
                        } elseif ($_tmp < 86400) {
                            $date = strftime(
                                "%e %b %Y %H:%M %Z",
                                strtotime(
                                    $this->data['Customer Tax Number Validation Date'].' +0:00'
                                )
                            );
                        } else {
                            $date = strftime(
                                "%e %b %Y",
                                strtotime(
                                    $this->data['Customer Tax Number Validation Date'].' +0:00'
                                )
                            );
                        }
                    } else {
                        $date = '';
                    }

                    $msg = $this->data['Customer Tax Number Validation Message'];

                    if ($this->data['Customer Tax Number Validation Source'] == 'Online') {
                        $source = '<i title=\''._('Validated online').'\' class=\'far fa-globe\'></i>';
                    } elseif ($this->data['Customer Tax Number Validation Source'] == 'Staff') {
                        $source = '<i title=\''._('Set up manually').'\' class=\'far fa-thumbtack\'></i>';
                    } else {
                        $source = '';
                    }

                    $validation_data = trim($date.' '.$source.' '.$msg);
                    if ($validation_data != '') {
                        $validation_data = ' <span class=\'discreet\'>('.$validation_data.')</span>';
                    }

                    switch ($this->data['Customer Tax Number Valid']) {
                        case 'Unknown':
                        case 'API_Down':
                            return _('Not validated').$validation_data;
                        case 'Yes':
                            return _('Validated').$validation_data;
                        case 'No':
                            return _('Not valid').$validation_data;
                        default:
                            return $this->data['Customer Tax Number Valid'].$validation_data;
                    }
                } else {
                    return '';
                }

            case('Tax Number Details Match'):
                switch ($this->data['Customer '.$key]) {
                    case 'Unknown':
                        return _('Unknown');

                    case 'Yes':
                        return _('Yes');

                    case 'No':
                        return _('No');

                    default:
                        return $this->data['Customer '.$key];
                }

            case('Recargo Equivalencia'):

                if (($this->data['Customer '.$key]) == 'Yes') {
                    return _('Yes');
                } else {
                    return _('No');
                }


            case('Lost Date'):
            case('Last Order Date'):
            case('First Order Date'):
            case('First Contacted Date'):
            case('Tax Number Validation Date'):
                if ($this->data['Customer '.$key] == '') {
                    return '';
                } else {
                    return '<span title="'.strftime(
                            "%a %e %b %Y %H:%M:%S %Z",
                            strtotime($this->data['Customer '.$key]." +00:00")
                        ).'">'.strftime(
                            "%a %e %b %Y",
                            strtotime($this->data['Customer '.$key]." +00:00")
                        ).'</span>';
                }

            case('Orders'):
                return number($this->data['Customer Orders']);

            case('Notes'):
                $sql   = sprintf(
                    "SELECT count(*) AS total FROM  `Customer History Bridge` WHERE `Customer Key`=%d AND `Type`='Notes'  ",
                    $this->id
                );
                $notes = 0;

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $notes = $row['total'];
                    }
                }


                return number($notes);

            case('Send Newsletter'):
            case('Send Email Marketing'):
            case('Send Postal Marketing'):

                return $this->data['Customer '.$key] == 'Yes' ? _('Yes') : _('No');


            case("ID"):
            case("Formatted ID"):
                return $this->get_formatted_id();
            case("Sticky Note"):
                return nl2br($this->data['Customer Sticky Note']);
            case 'Account Balance':
            case 'Invoiced Net Amount':
            case 'Credit Limit':


                if (!isset($this->store)) {
                    $store       = get_object('Store', $this->data['Customer Store Key']);
                    $this->store = $store;
                }


                return money(
                    $this->data['Customer '.$key],
                    $this->store->get('Store Currency Code')
                );


            case 'Invoiced Balance Amount':

                if (!isset($this->store)) {
                    $store       = get_object('Store', $this->data['Customer Store Key']);
                    $this->store = $store;
                }

                return money(
                    $this->data['Customer Invoiced Net Amount'] + $this->data['Customer Refunded Net Amount'],
                    $this->store->get('Store Currency Code')
                );

            case('Total Net Per Order'):
                if ($this->data['Customer Number Invoices'] > 0) {
                    if (!isset($this->store)) {
                        $store       = get_object('Store', $this->data['Customer Store Key']);
                        $this->store = $store;
                    }


                    return money($this->data['Customer Invoiced Net Amount'] / $this->data['Customer Number Invoices'], $this->store->data['Store Currency Code']);
                } else {
                    return _('ND');
                }


            case('Order Interval'):
                $order_interval = $this->get('Customer Order Interval') / 24 / 3600;

                if ($order_interval > 10) {
                    $order_interval = round($order_interval / 7);
                    if ($order_interval == 1) {
                        $order_interval = _('week');
                    } else {
                        $order_interval = $order_interval.' '._('weeks');
                    }
                } else {
                    if ($order_interval == '') {
                        $order_interval = '';
                    } else {
                        $order_interval = round($order_interval).' '._('days');
                    }
                }

                return $order_interval;


            case 'Web Login Password':
                return '<i class="fa fa-asterisk" aria-hidden="true"></i><i class="fa fa-asterisk" aria-hidden="true"></i><i class="fa fa-asterisk" aria-hidden="true"></i><i class="fa fa-asterisk" aria-hidden="true"></i><i class="fa fa-asterisk" aria-hidden="true"></i>
';

            case('Sales Representative'):
                if ($this->data['Customer Sales Representative Key'] != '') {
                    $sales_representative = get_object('Sales_Representative', $this->data['Customer Sales Representative Key']);
                    if ($sales_representative->id) {
                        return $sales_representative->user->get('Alias');
                    }
                } else {
                    return '<span class="very_discreet italic">'._('No account manager').'</span>';
                }
                break;

            case 'Level Type Icon':
                switch ($this->get('Customer Level Type')) {
                    case 'Partner':
                        return '<i class="fa fa-dove blue margin_right_5" title="'._('Partner').'" ></i>';
                    case 'VIP':
                        return '<i class="fa fa-badge-check success margin_right_5" title="'._('VIP customer').'" ></i>';
                    default:
                        return '';
                }
            case 'Fulfilment Icon':
                switch ($this->get('Customer Fulfilment')) {
                    case 'Yes':
                        return '<i class="fa fa-shopping-basket purple padding_left_10" title="'._('Full product procurement').'" ></i>';

                    default:
                        return '';
                }

            case 'Absolute Refunded Net Amount':
                if (!isset($this->store)) {
                    $store       = get_object('Store', $this->data['Customer Store Key']);
                    $this->store = $store;
                }


                return money(
                    -1 * $this->data['Customer Refunded Net Amount'],
                    $this->store->get('Store Currency Code')
                );

            case 'Name Truncated':
                return (strlen($this->get('Customer Name')) > 50 ? substrwords($this->get('Customer Name'), 55) : $this->get('Customer Name'));
            case 'First Contacted Date With Time':
                return strftime(
                    "%e %b %Y %H:%M %Z",
                    strtotime(
                        $this->data['Customer First Contacted Date'].' +0:00'
                    )
                );
            case 'Poll Overview':
                $poll_overview = '';

                $sql  =
                    "select `Customer Poll Reply`,`Customer Poll Query Option Name`,CPF.`Customer Poll Query Key`,CPF.`Customer Poll Query Option Key` from `Customer Poll Fact` CPF left join `Customer Poll Query Option Dimension` CPQOD on (CPQOD.`Customer Poll Query Option Key`=CPF.`Customer Poll Query Option Key`) where `Customer Poll Customer Key`=?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(array(
                                   $this->id
                               ));
                while ($row = $stmt->fetch()) {
                    if ($row['Customer Poll Query Option Key']) {
                        $poll_overview .= sprintf(
                            ' <span>&#183;</span> <span class="link" onClick="change_view(\'customers/%d/poll_query/%d/option/%d\')">%s</span>',
                            $this->get('Store Key'),
                            $row['Customer Poll Query Key'],
                            $row['Customer Poll Query Option Key'],
                            trim($row['Customer Poll Query Option Name'].' '.$row['Customer Poll Reply'])
                        );
                    } else {
                        $poll_overview .= sprintf(' <span>&#183;</span> <span class=" italic" >%s</span>', $row['Customer Poll Reply']);
                    }
                }

                return preg_replace('/^ <span>&#183;<\/span>/', '', $poll_overview);
            case 'Categories Overview':
                $categories_overview = '';

                $sql  =
                    "select `Category Code`,`Category Label`,`Category Parent Key`,B.`Category Key` from `Category Bridge` B left join `Category Dimension` C on (C.`Category Key`=B.`Category Key`) where `Subject Key`=? and `Category Scope`='Customer' and `Category Branch Type`='Head' ";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(array(
                                   $this->id
                               ));
                while ($row = $stmt->fetch()) {
                    $categories_overview .= sprintf(
                        ' <span>&#183;</span> <span class="link" onClick="change_view(\'customers/%d/category/%d/%d\')">%s</span>',
                        $this->get('Store Key'),
                        $row['Category Parent Key'],
                        $row['Category Key'],
                        $row['Category Code']
                    );
                }

                return preg_replace('/^ <span>&#183;<\/span>/', '', $categories_overview);

            default:


                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }


                if (preg_match(
                        '/^(Last|Yesterday|Total|1|10|6|3|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit)$/',
                        $key
                    ) or in_array($key, array(
                        'Net Amount',
                        'Refunded Net Amount'
                    ))) {
                    if (!isset($this->store)) {
                        $store       = get_object('Store', $this->data['Customer Store Key']);
                        $this->store = $store;
                    }

                    $amount = 'Customer '.$key;

                    return money(
                        $this->data[$amount],
                        $this->store->get('Store Currency Code')
                    );
                }

                if (array_key_exists('Customer '.$key, $this->data)) {
                    return $this->data[$this->table_name.' '.$key];
                }


                if (preg_match(
                    '/^Customer Other Delivery Address (\d+)/i',
                    $key,
                    $matches
                )) {
                    $address_fields = $this->get_other_delivery_address_fields(
                        $matches[1]
                    );


                    return json_encode($address_fields);
                }

                if (preg_match(
                    '/^Other Delivery Address (\d+)/i',
                    $key,
                    $matches
                )) {
                    $customer_delivery_key = $matches[1];
                    $sql                   = sprintf(
                        "SELECT `Customer Other Delivery Address Formatted` FROM `Customer Other Delivery Address Dimension` WHERE `Customer Other Delivery Address Key`=%d ",
                        $customer_delivery_key
                    );
                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            return $row['Customer Other Delivery Address Formatted'];
                        }
                    }
                }

                if (preg_match('/^Poll Query (\d+)/i', $key, $matches)) {
                    $poll_key = $matches[1];
                    /** @var $poll_query \Customer_Poll_Query */
                    $poll_query = get_object('Customer_Poll_Query', $poll_key);

                    $tmp = $poll_query->get_answer($this->id);

                    return $tmp[1];
                }

                if (preg_match('/^Customer Poll Query (\d+)/i', $key, $matches)) {
                    $poll_key = $matches[1];
                    /** @var $poll_query \Customer_Poll_Query */
                    $poll_query = get_object('Customer_Poll_Query', $poll_key);

                    $tmp = $poll_query->get_answer($this->id);

                    return $tmp[0];
                }
                if (preg_match('/^Category (\d+)/i', $key, $matches)) {
                    $category_key  = $matches[1];
                    $category_code = '';
                    $category      = get_object('Category', $category_key);
                    if ($category->id) {
                        foreach ($category->get_children_with_subject($this->id) as $children_data) {
                            $category_code .= ', '.$children_data[1];
                        }
                    }
                    if ($category_code == '') {
                        $category_code = _('Not assigned');
                    }

                    return preg_replace('/^, /', '', $category_code);
                }


                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Soft Minify$/',
                    $key
                )) {
                    if (!isset($this->store)) {
                        $store       = get_object('Store', $this->data['Customer Store Key']);
                        $this->store = $store;
                    }

                    $field = 'Customer '.preg_replace('/ Soft Minify$/', '', $key);


                    $suffix          = '';
                    $fraction_digits = 'NO_FRACTION_DIGITS';
                    $_amount         = $this->data[$field];

                    return money(
                            $_amount,
                            $this->store->get('Store Currency Code'),
                            false,
                            $fraction_digits
                        ).$suffix;
                }

                if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Minify$/', $key)) {
                    if (!isset($this->store)) {
                        $store       = get_object('Store', $this->data['Customer Store Key']);
                        $this->store = $store;
                    }

                    $field = 'Customer '.preg_replace('/ Minify$/', '', $key);

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

                    return money(
                            $_amount,
                            $this->store->get('Store Currency Code'),
                            false,
                            $fraction_digits
                        ).$suffix;
                }


                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|2|4|Year To|Quarter To|Month To|Today|Week To).*(Invoices|Refunds) Minify$/',
                    $key
                )) {
                    $field = 'Customer '.preg_replace('/ Minify$/', '', $key);

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
                    '/^(Last|Yesterday|Total|1|10|6|3|2|4|Year To|Quarter To|Month To|Today|Week To|Number).*(Invoices|Refunds) Soft Minify$/',
                    $key
                )) {
                    $field = 'Customer '.preg_replace('/ Soft Minify$/', '', $key);

                    $suffix          = '';
                    $fraction_digits = 0;
                    $_number         = $this->data[$field];

                    return number($_number, $fraction_digits).$suffix;
                }
        }


        return '';
    }

    function get_other_delivery_address_fields($other_delivery_address_key)
    {
        $sql = sprintf(
            "SELECT * FROM `Customer Other Delivery Address Dimension` WHERE `Customer Other Delivery Address Key`=%d ",
            $other_delivery_address_key
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                return array(

                    'Address Recipient'            => $row['Customer Other Delivery Address Recipient'],
                    'Address Organization'         => $row['Customer Other Delivery Address Organization'],
                    'Address Line 1'               => $row['Customer Other Delivery Address Line 1'],
                    'Address Line 2'               => $row['Customer Other Delivery Address Line 2'],
                    'Address Sorting Code'         => $row['Customer Other Delivery Address Sorting Code'],
                    'Address Postal Code'          => $row['Customer Other Delivery Address Postal Code'],
                    'Address Dependent Locality'   => $row['Customer Other Delivery Address Dependent Locality'],
                    'Address Locality'             => $row['Customer Other Delivery Address Locality'],
                    'Address Administrative Area'  => $row['Customer Other Delivery Address Administrative Area'],
                    'Address Country 2 Alpha Code' => $row['Customer Other Delivery Address Country 2 Alpha Code'],


                );
            }
        }

        return false;
    }

    function update_location_type()
    {
        $store = get_object('Store', $this->data['Customer Store Key']);

        if ($this->data['Customer Contact Address Country 2 Alpha Code'] == $store->data['Store Home Country Code 2 Alpha'] or $this->data['Customer Contact Address Country 2 Alpha Code'] == 'XX') {
            $location_type = 'Domestic';
        } else {
            $location_type = 'Export';
        }

        $this->fast_update(array('Customer Location Type' => $location_type));
    }


    function get_telephone()
    {
        $phone = $this->get('Customer Main Plain Mobile');

        if ($phone == '') {
            $phone = $this->get('Customer Main Plain Telephone');
        }

        return $phone;
    }

    function create_order($options = '{}'): Order
    {
        $account = get_object('Account', 1);


        $order_data = array(

            'Order Original Data MIME Type' => 'application/aurora',
            'Order Type'                    => 'Order',
            'editor'                        => $this->editor,


        );


        $options = json_decode($options, true);

        if (!empty($options['date'])) {
            $order_data['Order Date'] = $options['date'];
        }

        $order_data['Order Customer Key']          = $this->id;
        $order_data['Order Customer Name']         = $this->data['Customer Name'];
        $order_data['Order Customer Contact Name'] = $this->data['Customer Main Contact Name'];
        $order_data['Order Registration Number']   = $this->data['Customer Registration Number'];
        $order_data['Order Customer Level Type']   = $this->data['Customer Level Type'];

        $order_data['Order Tax Number']                    = $this->data['Customer Tax Number'];
        $order_data['Order Tax Number Valid']              = $this->data['Customer Tax Number Valid'];
        $order_data['Order Tax Number Validation Date']    = $this->data['Customer Tax Number Validation Date'];
        $order_data['Order Tax Number Validation Source']  = $this->data['Customer Tax Number Validation Source'];
        $order_data['Order Tax Number Validation Message'] = $this->data['Customer Tax Number Validation Message'];


        $order_data['Order Tax Number Details Match']      = $this->data['Customer Tax Number Details Match'];
        $order_data['Order Tax Number Registered Name']    = $this->data['Customer Tax Number Registered Name'];
        $order_data['Order Tax Number Registered Address'] = $this->data['Customer Tax Number Registered Address'];
        $order_data['Order Available Credit Amount']       = $this->data['Customer Account Balance'];
        $order_data['Order Sales Representative Key']      = $this->data['Customer Sales Representative Key'];


        $order_data['Order Customer Fiscal Name'] = $this->get('Fiscal Name');
        $order_data['Order Email']                = $this->data['Customer Main Plain Email'];
        $order_data['Order Telephone']            = $this->get_telephone();


        $order_data['Order Invoice Address Recipient']            = $this->data['Customer Invoice Address Recipient'];
        $order_data['Order Invoice Address Organization']         = $this->data['Customer Invoice Address Organization'];
        $order_data['Order Invoice Address Line 1']               = $this->data['Customer Invoice Address Line 1'];
        $order_data['Order Invoice Address Line 2']               = $this->data['Customer Invoice Address Line 2'];
        $order_data['Order Invoice Address Sorting Code']         = $this->data['Customer Invoice Address Sorting Code'];
        $order_data['Order Invoice Address Postal Code']          = $this->data['Customer Invoice Address Postal Code'];
        $order_data['Order Invoice Address Dependent Locality']   = $this->data['Customer Invoice Address Dependent Locality'];
        $order_data['Order Invoice Address Locality']             = $this->data['Customer Invoice Address Locality'];
        $order_data['Order Invoice Address Administrative Area']  = $this->data['Customer Invoice Address Administrative Area'];
        $order_data['Order Invoice Address Country 2 Alpha Code'] = $this->data['Customer Invoice Address Country 2 Alpha Code'];
        $order_data['Order Invoice Address Checksum']             = $this->data['Customer Invoice Address Checksum'];
        $order_data['Order Invoice Address Formatted']            = $this->data['Customer Invoice Address Formatted'];
        $order_data['Order Invoice Address Postal Label']         = $this->data['Customer Invoice Address Postal Label'];


        $order_data['Order Delivery Address Recipient']            = $this->data['Customer Delivery Address Recipient'];
        $order_data['Order Delivery Address Organization']         = $this->data['Customer Delivery Address Organization'];
        $order_data['Order Delivery Address Line 1']               = $this->data['Customer Delivery Address Line 1'];
        $order_data['Order Delivery Address Line 2']               = $this->data['Customer Delivery Address Line 2'];
        $order_data['Order Delivery Address Sorting Code']         = $this->data['Customer Delivery Address Sorting Code'];
        $order_data['Order Delivery Address Postal Code']          = $this->data['Customer Delivery Address Postal Code'];
        $order_data['Order Delivery Address Dependent Locality']   = $this->data['Customer Delivery Address Dependent Locality'];
        $order_data['Order Delivery Address Locality']             = $this->data['Customer Delivery Address Locality'];
        $order_data['Order Delivery Address Administrative Area']  = $this->data['Customer Delivery Address Administrative Area'];
        $order_data['Order Delivery Address Country 2 Alpha Code'] = $this->data['Customer Delivery Address Country 2 Alpha Code'];
        $order_data['Order Delivery Address Checksum']             = $this->data['Customer Delivery Address Checksum'];
        $order_data['Order Delivery Address Formatted']            = $this->data['Customer Delivery Address Formatted'];
        $order_data['Order Delivery Address Postal Label']         = $this->data['Customer Delivery Address Postal Label'];

        $order_data['Order Sticky Note']          = $this->data['Customer Order Sticky Note'];
        $order_data['Order Delivery Sticky Note'] = $this->data['Customer Delivery Sticky Note'];

        $order_data['Order Customer Order Number'] = $this->get_number_of_orders() + 1;

        $store = get_object('Store', $this->get('Customer Store Key'));

        $order_data['Order Store Key']                = $store->id;
        $order_data['Order Currency']                 = $store->get('Store Currency Code');
        $order_data['Order Show in Warehouse Orders'] = $store->get('Store Show in Warehouse Orders');
        $order_data['public_id_format']               = $store->get('Store Order Public ID Format');

        $order_data['Recargo Equivalencia'] = $this->get('Customer Recargo Equivalencia');

        $order_data['Order External Invoicer Key'] = $store->get('Store External Invoicer Key');


        $order_data['Order Source Key'] = 2;


        $sql  = "select `Order Source Key`,`Order Source Option Key` from `Order Source Dimension`  where `Order Source Option Key`>0 and   `Order Source Type`='marketplace' and  `Order Source Store Key`=? ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            [
                $this->get('Customer Store Key')
            ]
        );
        while ($row = $stmt->fetch()) {
            $sql   = "select `Customer Poll Key` from `Customer Poll Fact` where `Customer Poll Customer Key`=? and `Customer Poll Query Option Key`=? ";
            $stmt2 = $this->db->prepare($sql);
            $stmt2->execute(
                [
                    $this->id,
                    $row['Order Source Option Key']
                ]
            );
            while ($row2 = $stmt2->fetch()) {
                $order_data['Order Source Key'] = $row['Order Source Key'];
            }
        }


        $order = new Order('new', $order_data);


        if ($order->error) {
            $this->error = true;
            $this->msg   = $order->msg;

            return $order;
        }


        require_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_housekeeping',
            array(
                'type'        => 'order_created',
                'subject_key' => $order->id,
                'editor'      => $order->editor
            ),
            DNS_ACCOUNT_CODE,
            $this->db
        );

        return $order;
    }

    function get_number_of_orders()
    {
        $sql    = sprintf(
            "SELECT count(*) AS number FROM `Order Dimension` WHERE `Order Customer Key`=%d ",
            $this->id
        );
        $number = 0;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number = $row['number'];
            }
        }


        return $number;
    }

    function update_field_switcher($field, $value, $options = '', $metadata = '')
    {
        if (is_string($value)) {
            $value = _trim($value);
        }

        if ($this->update_subject_field_switcher($field, $value, $options, $metadata)) {
            return;
        }

        switch ($field) {
            case 'Customer EORI':

                $this->fast_update_json_field('Customer Metadata', 'eori', $value);

                break;

            case 'Customer Order Sticky Note':

                $this->update_field($field, $value, 'no_history');

                $sql = "update `Order Dimension` set `Order Sticky Note`=?   WHERE `Order State` in  ('InBasket','InProcess')  and `Order Customer Key`=?";
                $this->db->prepare($sql)->execute(array(
                                                      $value,
                                                      $this->id
                                                  ));


                if ($this->updated) {
                    $abstract = '<i class="fa fa-sticky-note pink"></i> ';
                    if ($value == '') {
                        $abstract .= _('Order sticky note deleted');
                    } else {
                        $abstract .= _('Order sticky note').' &rArr; <small>'.$value.'</small>';
                    }

                    $history_data = array(
                        'History Abstract' => $abstract,
                        'History Details'  => '',
                        'Action'           => 'edited'
                    );

                    $this->add_subject_history(
                        $history_data,
                        true,
                        'No',
                        'ChangesT2',
                        $this->get_object_name(),
                        $this->id
                    );
                }
                break;
            case 'Customer Delivery Sticky Note':
                $this->update_field($field, $value, 'no_history');
                $sql = "update `Order Dimension` set `Order Delivery Sticky Note`=?   WHERE `Order State` in  ('InBasket','InProcess','InWarehouse')  and `Order Customer Key`=?";
                $this->db->prepare($sql)->execute(array(
                                                      $value,
                                                      $this->id
                                                  ));
                if ($this->updated) {
                    $abstract = '<i class="fa fa-sticky-note " style="color:#94db84"></i> ';


                    if ($value == '') {
                        $abstract .= _('Delivery sticky note deleted');
                    } else {
                        $abstract .= _('Delivery sticky note').' &rArr; <small>'.$value.'</small>';
                    }

                    $history_data = array(
                        'History Abstract' => $abstract,
                        'History Details'  => '',
                        'Action'           => 'edited'
                    );


                    $this->add_subject_history(
                        $history_data,
                        true,
                        'No',
                        'ChangesT2',
                        $this->get_object_name(),
                        $this->id
                    );
                }

                break;
            case('Customer Sticky Note'):
                $this->update_field($field, $value, 'no_history');

                if ($this->updated) {
                    $abstract = '<i class="fa fa-sticky-note " style="color:#7da7f4"></i> ';

                    if ($value == '') {
                        $abstract .= _('Sticky note deleted');
                    } else {
                        $abstract .= _('Sticky note').' &rArr; <small>'.$value.'</small>';
                    }

                    $history_data = array(
                        'History Abstract' => $abstract,
                        'History Details'  => '',
                        'Action'           => 'edited'
                    );


                    $this->add_subject_history(
                        $history_data,
                        true,
                        'No',
                        'ChangesT2',
                        $this->get_object_name(),
                        $this->id
                    );
                }


                break;

            case 'Customer Web Login Password':

                /** @var Website_User $website_user */ $website_user = get_object('Website_User', $this->get('Customer Website User Key'));


                if ($website_user->id) {
                    $website_user->editor = $this->editor;


                    $website_user->fast_update(array(
                                                   'Website User Password'      => hash('sha256', $value),
                                                   'Website User Password Hash' => password_hash(hash('sha256', $value), PASSWORD_DEFAULT, array('cost' => 12))
                                               ));
                }


                break;
            case 'Customer Contact Address':
                $this->update_address('Contact', json_decode($value, true), $options);
                $this->fork_index_elastic_search();

                break;


            case 'Customer Invoice Address':

                $old_country = $this->data['Customer Invoice Address Country 2 Alpha Code'];

                $this->update_address('Invoice', json_decode($value, true), $options);

                if ($old_country != $this->data['Customer Invoice Address Country 2 Alpha Code']) {
                    $this->validate_customer_tax_number();
                }

                $this->update_metadata = array(

                    'class_html' => array(
                        'Contact_Address'               => $this->get('Contact Address'),
                        'Customer_Tax_Number_Formatted' => $this->get('Tax Number Formatted')


                    )
                );

                $this->other_fields_updated = array(
                    'Customer_Tax_Number' => array(
                        'field'           => 'Customer_Tax_Number_Valid',
                        'render'          => true,
                        'value'           => $this->get('Customer Tax Number Valid'),
                        'formatted_value' => $this->get('Tax Number Valid'),


                    )
                );

                break;
            case 'Customer Delivery Address':


                $this->update_address('Delivery', json_decode($value, true), $options);


                break;
            case 'new delivery address':
                $this->add_other_delivery_address(json_decode($value, true));

                break;

            case('Customer Registration Number'):
                $this->update_field($field, $value, $options);


                $sql  = "SELECT `Order Key` FROM `Order Dimension` WHERE  `Order State` IN ('InBasket') AND `Order Customer Key`=? ";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(array(
                                   $this->id
                               ));
                while ($row = $stmt->fetch()) {
                    $order         = get_object('Order', $row['Order Key']);
                    $order->editor = $this->editor;
                    $order->update(array('Order Registration Number' => $value), $options);
                }


                if ($value == '') {
                    $this->update_metadata['hide'] = array('Customer_Registration_Number_display');
                } else {
                    $this->update_metadata['show'] = array('Customer_Registration_Number_display');
                }

                break;
            case('Customer Tax Number'):
                $this->update_tax_number($value);


                $sql = sprintf("SELECT `Order Key` FROM `Order Dimension` WHERE  `Order State` IN ('InBasket')  AND `Order Customer Key`=%d ", $this->id);
                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $order         = get_object('Order', $row['Order Key']);
                        $order->editor = $this->editor;
                        $order->update_tax_number($value);
                    }
                }

                $this->update_metadata = array(
                    'class_html' => array(
                        'Customer_Tax_Number_Formatted' => $this->get('Tax Number Formatted'),
                    ),

                );

                if ($value == '') {
                    $this->update_metadata['hide'] = array('Customer_Tax_Number_display');
                } else {
                    $this->update_metadata['show'] = array('Customer_Tax_Number_display');
                }

                $this->fork_index_elastic_search();
                break;

            case('Customer Tax Number Valid'):
                $this->update_tax_number_valid($value);


                $sql = sprintf("SELECT `Order Key` FROM `Order Dimension` WHERE  `Order State` IN ('InBasket')  AND `Order Customer Key`=%d ", $this->id);
                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $order = get_object('Order', $row['Order Key']);
                        $order->update_tax_number_valid($value);
                    }
                }
                $this->update_metadata = array(
                    'class_html' => array(
                        'Customer_Tax_Number_Formatted' => $this->get('Tax Number Formatted'),
                    )
                );


                break;

            case('Customer Delivery Address Link'):
                $this->update_field($field, $value, $options);


                $this->other_fields_updated = array(
                    'Customer_Delivery_Address' => array(
                        'field'           => 'Customer_Delivery_Address',
                        'id'              => 'Customer_Delivery_Address',
                        'edit'            => 'address',
                        'render'          => !($this->get('Customer Delivery Address Link') != 'None'),
                        'value'           => htmlspecialchars($this->get('Customer Delivery Address')),
                        'formatted_value' => $this->get('Delivery Address'),
                        'label'           => ucfirst($this->get_field_label('Customer Delivery Address')),

                    ),


                );


                break;


            case('Note'):
                $this->add_note($value);
                break;
            case 'Customer Level Type Partner':

                $old_value = $this->data['Customer Level Type'];
                if ($value == 'Yes') {
                    $value = 'Partner';
                } else {
                    $value = 'Normal';
                }
                $this->fast_update(array(
                                       'Customer Level Type' => $value
                                   ));

                if ($value == 'Normal') {
                    $this->fast_update(array(
                                           'Customer Sales Representative Key' => ''
                                       ));
                }


                $this->update_level_type($old_value);


                $this->update_metadata = array(

                    'class_html' => array(
                        'Customer_Level_Type_Icon' => $this->get('Level Type Icon'),
                        'Sales_Representative'     => $this->get('Sales Representative')


                    )
                );

                if ($this->get('Customer Sales Representative Key')) {
                    $this->update_metadata['show'] = array('Customer_Sales_Representative_tr');
                } else {
                    $this->update_metadata['hide'] = array('Customer_Sales_Representative_tr');
                }


                $options_sales_representative = [];

                $sql = "SELECT `Staff Name`,S.`Staff Key`,`Staff Alias` 
                        FROM `Staff Dimension` S LEFT JOIN `User Dimension` U ON (S.`Staff Key`=U.`User Parent Key`) LEFT JOIN 
                                `User Group User Bridge` B ON (U.`User Key`=B.`User Key`) 
                        WHERE  `User Type` in  ('Staff','Contractor')  and `User Group Key`=2     and `Staff Currently Working`='Yes'  group by S.`Staff Key`";

                $stmt = $this->db->prepare($sql);
                $stmt->execute([]);
                while ($row = $stmt->fetch()) {
                    $options_sales_representative[$row['Staff Key']] = array(
                        'label'    => $row['Staff Alias'],
                        'label2'   => $row['Staff Name'].' ('.sprintf('%03d', $row['Staff Key']).')',
                        'selected' => false
                    );
                }


                $_options_sales_representative = '';
                foreach ($options_sales_representative as $_key => $_option) {
                    $_options_sales_representative .= sprintf(
                        '  <li id="Customer_Sales_Representative_option_%d" label="%s"
                                                    value="%d" is_selected="%s"
                                                    onclick="select_option_multiple_choices(\'Customer_Sales_Representative\',\'%d\',\'%s\' )">
                                                    <i class="far fa-fw checkbox %s"></i> %s
                                                    <i class="fa fa-circle fw current_mark %s"></i>
                                                </li>',
                        $_key,
                        $_option['label'],
                        $_key,
                        $_option['selected'],
                        $_key,
                        $_option['label'],
                        ($_option['selected'] ? 'fa-check-square' : 'fa-square'),
                        $_option['label'],
                        ($_option['selected'] ? 'current' : '')

                    );
                }

                $this->other_fields_updated = array(
                    'Part_Unit_Price' => array(
                        'field'           => 'Customer_Sales_Representative',
                        'render'          => !($this->get('Customer Level Type') == 'Partner'),
                        'value'           => $this->get('Customer Sales Representative'),
                        'formatted_value' => $this->get('Sales Representative'),
                        'options'         => $_options_sales_representative

                    )
                );


                break;

            case 'Customer Sales Representative':

                if ($value == 0) {
                    $this->fast_update(array('Customer Sales Representative Key' => ''));
                } else {
                    include_once('class.Sales_Representative.php');
                    $sales_representative = new Sales_Representative('find', array(
                        'Sales Representative User Key' => $value,
                        'editor'                        => $this->editor
                    ));
                    $sales_representative->fast_update(array('Sales Representative Customer Agent' => 'Yes'));


                    $this->fast_update(array(
                                           'Customer Sales Representative Key' => $sales_representative->id
                                       ));
                }


                $this->update_level_type();

                $this->update_metadata = array(

                    'class_html' => array(
                        'Customer_Level_Type_Icon' => $this->get('Level Type Icon'),
                        'Sales_Representative'     => $this->get('Sales Representative')


                    )
                );

                if ($this->get('Customer Sales Representative Key')) {
                    $this->update_metadata['show'] = array('Customer_Sales_Representative_tr');
                } else {
                    $this->update_metadata['hide'] = array('Customer_Sales_Representative_tr');
                }


                $options_sales_representative = [];

                if ($this->get('Customer Sales Representative Key')) {
                    $options_sales_representative[0] = array(
                        'label'    => _('Remove account manager'),
                        'label2'   => _('Remove account manager'),
                        'selected' => false
                    );
                }

                $sql =
                    "SELECT `Staff Name`,S.`Staff Key`,`Staff Alias` FROM `Staff Dimension` S LEFT JOIN `User Dimension` U ON (S.`Staff Key`=U.`User Parent Key`) LEFT JOIN `User Group User Bridge` B ON (U.`User Key`=B.`User Key`) WHERE  `User Type` in  ('Staff','Contractor')  and `User Group Key`=2     and `Staff Currently Working`='Yes'  group by S.`Staff Key`  ";


                foreach ($this->db->query($sql) as $row) {
                    $options_sales_representative[$row['Staff Key']] = array(
                        'label'    => $row['Staff Alias'],
                        'label2'   => $row['Staff Name'].' ('.sprintf('%03d', $row['Staff Key']).')',
                        'selected' => false
                    );
                }

                $_options_sales_representative = '';
                foreach ($options_sales_representative as $_key => $_option) {
                    $_options_sales_representative .= sprintf(
                        '  <li id="Customer_Sales_Representative_option_%d" label="%s"
                                                    value="%d" is_selected="%s"
                                                    onclick="select_option_multiple_choices(\'Customer_Sales_Representative\',\'%d\',\'%s\' )">
                                                    <i class="far fa-fw checkbox %s"></i> %s
                                                    <i class="fa fa-circle fw current_mark %s"></i>
                                                </li>',
                        $_key,
                        $_option['label'],
                        $_key,
                        $_option['selected'],
                        $_key,
                        $_option['label'],
                        ($_option['selected'] ? 'fa-check-square' : 'fa-square'),
                        $_option['label'],
                        ($_option['selected'] ? 'current' : '')

                    );
                }


                $this->other_fields_updated = array(
                    'Part_Unit_Price' => array(
                        'field'           => 'Customer_Sales_Representative',
                        'render'          => !($this->get('Customer Level Type') == 'Partner'),
                        'value'           => $this->get('Customer Sales Representative'),
                        'formatted_value' => $this->get('Sales Representative'),
                        'options'         => $_options_sales_representative,

                    )
                );


                break;


            case('Customer Send Newsletter'):
            case('Customer Send Email Marketing'):
            case('Customer Send Basket Emails'):

                $this->update_field($field, $value, $options);
                /** @var \Store $store */
                $store = get_object('Store', $this->data['Customer Store Key']);
                $store->update_customers_email_marketing_data();

                break;
            case('Customer Recargo Equivalencia'):

                $this->update_field($field, $value, $options);

                $sql  = "SELECT `Order Key` FROM `Order Dimension` WHERE  `Order State` IN ('InBasket') AND `Order Customer Key`=? ";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(array(
                                   $this->id
                               ));
                while ($row = $stmt->fetch()) {
                    $order         = get_object('Order', $row['Order Key']);
                    $order->editor = $this->editor;
                    $order->update(array('Order Recargo Equivalencia' => $value), $options);
                }

                $this->update_metadata = array(
                    ($this->data['Customer Recargo Equivalencia'] == 'Yes' ? 'show' : 'hide') => array(
                        'recargo_equivalencia_tag'

                    )
                );


                break;
            case('Customer Fulfilment'):

                $store = get_object('Store', $this->data['Customer Store Key']);
                if ($store->get('Store Type') == 'Dropshipping') {
                    if ($value == 'Yes') {
                        $this->update_field($field, $value, $options);

                        if ($this->updated) {
                            $account = get_object('Account', 1);
                            $account->load_acc_data();
                            $sql = "insert into `Customer Fulfilment Dimension` (`Customer Fulfilment Customer Key`,`Customer Fulfilment Metadata`,`Customer Fulfilment Warehouse Key`,`Customer Fulfilment Type`) values (?,'{}',?,'Dropshipping')";
                            $this->db->prepare($sql)->execute(array(
                                                                  $this->id,
                                                                  $account->properties('fulfilment_warehouse_key')

                                                              ));
                        }
                    } elseif ($value == 'No') {
                        $customer_fulfilment = get_object('customer_fulfilment', $this->id);
                        if ($customer_fulfilment->get('Customer Fulfilment Stored Parts') > 0) {
                            $this->error = true;
                            $this->msg   = _('Customer still have stored parts');

                            return;
                        }
                        $this->update_field($field, $value, $options);
                        $customer_fulfilment->fast_update(['Customer Fulfilment Status' => 'StoringEnd']);
                    }
                }


                $customer_fulfilment         = get_object('customer_fulfilment', $this->id);
                $customer_fulfilment->editor = $this->editor;


                $customer_fulfilment->update_field($field, $value, $options);
                $this->updated = $customer_fulfilment->updated;

                $this->update_metadata = array(
                    'class_html' => array(
                        'Customer_Fulfilment_Icon' => $this->get('Fulfilment Icon'),
                    )
                );


                break;
            default:


                if (preg_match('/^custom_field_/i', $field)) {
                    //$field=preg_replace('/^custom_field_/','',$field);
                    $this->update_field($field, $value, $options);


                    return;
                }


                if (preg_match('/^Customer Other Delivery Address (\d+)/i', $field, $matches)) {
                    $customer_delivery_address_key = $matches[1];
                    $this->update_other_delivery_address(
                        $customer_delivery_address_key,
                        json_decode($value, true)
                    );

                    return;
                }

                if (preg_match('/^Customer Poll Query (\d+)/i', $field, $matches)) {
                    $poll_key = $matches[1];
                    $this->update_poll_answer($poll_key, $value, $options);

                    $poll_overview = $this->get('Poll Overview');

                    $this->update_metadata = array(
                        'class_html' => array(
                            'poll_overview' => $poll_overview,
                        )
                    );

                    if ($poll_overview == '') {
                        $this->update_metadata['hide'] = array('poll_overview_display');
                    } else {
                        $this->update_metadata['show'] = array('poll_overview_display');
                    }

                    return;
                }

                if (preg_match('/^Customer Category (\d+)/i', $field, $matches)) {
                    if ($value > 0) {
                        $category         = get_object('Category', $value);
                        $category->editor = $this->editor;
                        $category->associate_subject($this->id);
                    } else {
                        $category = get_object('Category', $matches[1]);
                        foreach ($category->get_children_with_subject($this->id) as $child_key => $children_data) {
                            $category         = get_object('Category', $child_key);
                            $category->editor = $this->editor;
                            $category->disassociate_subject($this->id);
                        }
                    }


                    $categories_overview = $this->get('Categories Overview');

                    $this->update_metadata = array(
                        'class_html' => array(
                            'categories_overview' => $categories_overview,
                        )
                    );

                    if ($categories_overview == '') {
                        $this->update_metadata['hide'] = array('categories_overview_display');
                    } else {
                        $this->update_metadata['show'] = array('categories_overview_display');
                    }

                    return;
                }


                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {
                    if ($value != $this->data[$field]) {
                        $this->update_field($field, $value, $options);
                    }
                }
        }
    }

    function validate_customer_tax_number(): bool
    {
        if (!empty($this->skip_validate_tax_number)) {
            return false;
        }


        if ($this->data['Customer Tax Number'] == '') {
            $this->fast_update(array(
                                   'Customer Tax Number Valid'              => 'Unknown',
                                   'Customer Tax Number Details Match'      => '',
                                   'Customer Tax Number Validation Date'    => '',
                                   'Customer Tax Number Validation Source'  => '',
                                   'Customer Tax Number Validation Message' => ''
                               ));
        } else {
            include_once 'utils/validate_tax_number.php';

            $tax_validation_data = validate_tax_number($this->data['Customer Tax Number'], $this->data['Customer Invoice Address Country 2 Alpha Code']);

            if ($tax_validation_data['Tax Number Valid'] == 'API_Down') {
                if (!($this->data['Customer Tax Number Validation Source'] == '' and $this->data['Customer Tax Number Valid'] == 'No')) {
                    return false;
                }
            }

            $this->fast_update(array(
                                   'Customer Tax Number Valid'              => $tax_validation_data['Tax Number Valid'],
                                   'Customer Tax Number Details Match'      => $tax_validation_data['Tax Number Details Match'],
                                   'Customer Tax Number Validation Date'    => $tax_validation_data['Tax Number Validation Date'],
                                   'Customer Tax Number Validation Source'  => $tax_validation_data['Tax Number Validation Source'],
                                   'Customer Tax Number Validation Message' => $tax_validation_data['Tax Number Validation Message'],
                               ));
        }


        $this->process_aiku_fetch('Customer', $this->id, 'tax_number_validation');

        return true;
    }

    function add_other_delivery_address($fields)
    {
        include_once 'utils/get_addressing.php';

        $checksum = md5(json_encode($fields));
        if ($checksum == $this->data['Customer Delivery Address Checksum']) {
            $this->error = true;
            $this->msg   = _('Duplicated address');

            return;
        }

        $sql = sprintf(
            'SELECT `Customer Other Delivery Address Checksum` FROM `Customer Other Delivery Address Dimension` WHERE `Customer Other Delivery Address Customer Key`=%d',
            $this->id
        );
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($checksum == $row['Customer Other Delivery Address Checksum']) {
                    $this->error = true;
                    $this->msg   = _('Duplicated address');

                    return;
                }
            }
        }


        $store = get_object('Store', $this->get('Store Key'));


        list($address, $formatter, $postal_label_formatter) = get_address_formatter($store->get('Store Home Country Code 2 Alpha'), $store->get('Store Locale'));


        if (preg_match('/gb|im|jy|gg/i', $fields['Address Country 2 Alpha Code'])) {
            include_once 'utils/geography_functions.php';
            $fields['Address Postal Code'] = gbr_pretty_format_post_code($fields['Address Postal Code']);
        }


        $address = $address->withFamilyName($fields['Address Recipient'])->withOrganization($fields['Address Organization'])->withAddressLine1($fields['Address Line 1'])->withAddressLine2(
            $fields['Address Line 2']
        )->withSortingCode($fields['Address Sorting Code'])->withPostalCode($fields['Address Postal Code'])->withDependentLocality($fields['Address Dependent Locality'])->withLocality(
            $fields['Address Locality']
        )->withAdministrativeArea($fields['Address Administrative Area'])->withCountryCode($fields['Address Country 2 Alpha Code']);

        $xhtml_address = $formatter->format($address);
        $xhtml_address = preg_replace('/<br>\s/', "\n", $xhtml_address);
        $xhtml_address = preg_replace(
            '/class="recipient"/',
            'class="recipient fn"',
            $xhtml_address
        );
        $xhtml_address = preg_replace(
            '/class="organization"/',
            'class="organization org"',
            $xhtml_address
        );
        $xhtml_address = preg_replace(
            '/class="address-line1"/',
            'class="address-line1 street-address"',
            $xhtml_address
        );
        $xhtml_address = preg_replace(
            '/class="address-line2"/',
            'class="address-line2 extended-address"',
            $xhtml_address
        );
        $xhtml_address = preg_replace(
            '/class="sort-code"/',
            'class="sort-code postal-code"',
            $xhtml_address
        );
        $xhtml_address = preg_replace(
            '/class="country"/',
            'class="country country-name"',
            $xhtml_address
        );


        $sql = sprintf(
            'INSERT INTO `Customer Other Delivery Address Dimension` (
        `Customer Other Delivery Address Store Key`,
        `Customer Other Delivery Address Customer Key`,
        `Customer Other Delivery Address Recipient`,
        `Customer Other Delivery Address Organization`,
        `Customer Other Delivery Address Line 1`,
        `Customer Other Delivery Address Line 2`,
        `Customer Other Delivery Address Sorting Code`,
        `Customer Other Delivery Address Postal Code`,
        `Customer Other Delivery Address Dependent Locality`,
        `Customer Other Delivery Address Locality`,
        `Customer Other Delivery Address Administrative Area`,
        `Customer Other Delivery Address Country 2 Alpha Code`,
         `Customer Other Delivery Address Checksum`,
        `Customer Other Delivery Address Formatted`,
        `Customer Other Delivery Address Postal Label`

        ) VALUES (%d,%d,
        %s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s
        ) ',
            $this->get('Store Key'),
            $this->id,
            prepare_mysql($fields['Address Recipient'], false),
            prepare_mysql($fields['Address Organization'], false),
            prepare_mysql($fields['Address Line 1'], false),
            prepare_mysql($fields['Address Line 2'], false),
            prepare_mysql($fields['Address Sorting Code'], false),
            prepare_mysql($fields['Address Postal Code'], false),
            prepare_mysql($fields['Address Dependent Locality'], false),
            prepare_mysql($fields['Address Locality'], false),
            prepare_mysql($fields['Address Administrative Area'], false),
            prepare_mysql($fields['Address Country 2 Alpha Code'], false),
            prepare_mysql($checksum),
            prepare_mysql($xhtml_address),
            prepare_mysql($postal_label_formatter->format($address))
        );

        $prep = $this->db->prepare($sql);


        try {
            $prep->execute();

            $inserted_key = $this->db->lastInsertId();

            //print $sql;
            if ($inserted_key) {
                $this->field_created = true;


                $this->add_changelog_record(
                    _("delivery address"),
                    '',
                    $this->get("Other Delivery Address $inserted_key"),
                    '',
                    $this->table_name,
                    $this->id,
                    'added'
                );


                $this->new_fields_info = array(
                    array(
                        'clone_from'      => 'Customer_Other_Delivery_Address',
                        'field'           => 'Customer_Other_Delivery_Address_'.$inserted_key,
                        'render'          => true,
                        'edit'            => 'address',
                        'value'           => $this->get(
                            'Customer Other Delivery Address '.$inserted_key
                        ),
                        'formatted_value' => $this->get(
                            'Other Delivery Address '.$inserted_key
                        ),
                        'label'           => '',


                    )
                );
            } else {
                $this->error = true;

                $this->msg = _('Duplicated address').' (1)';
            }
        } catch (PDOException $e) {
            $this->error = true;

            if ($e->errorInfo[0] == '23000' && $e->errorInfo[1] == '1062') {
                $this->msg = _('Duplicated address').' (2)';
            } else {
                $this->msg = $e->getMessage();
            }
        }
    }

    function update_tax_number($value)
    {
        $this->update_field('Customer Tax Number', $value);

        if ($this->updated) {
            $this->validate_customer_tax_number();
        }

        $this->other_fields_updated = array(
            'Customer_Tax_Number_Valid' => array(
                'field'           => 'Customer_Tax_Number_Valid',
                'render'          => !($this->get('Customer Tax Number') == ''),
                'value'           => $this->get('Customer Tax Number Valid'),
                'formatted_value' => $this->get('Tax Number Valid'),


            )
        );
    }

    function update_tax_number_valid($value)
    {
        include_once 'utils/validate_tax_number.php';


        if ($value == 'Auto') {
            $tax_validation_data = validate_tax_number($this->data['Customer Tax Number'], $this->data['Customer Invoice Address Country 2 Alpha Code']);

            if ($tax_validation_data['Tax Number Valid'] == 'API_Down') {
                if (!($this->data['Customer Tax Number Validation Source'] == '' and $this->data['Customer Tax Number Valid'] == 'No')) {
                    $this->error = true;
                    $this->msg   = '<span class="error"><i class="fa fa-exclamation-circle"></i> '.$tax_validation_data['Tax Number Validation Message'].'</span>';

                    return;
                }
            }


            $this->update(
                array(
                    'Customer Tax Number Valid' => $tax_validation_data['Tax Number Valid'],

                    'Customer Tax Number Details Match'      => $tax_validation_data['Tax Number Details Match'],
                    'Customer Tax Number Validation Date'    => ($tax_validation_data['Tax Number Validation Date'] == '' ? gmdate('Y-m-d H:i:s') : $tax_validation_data['Tax Number Validation Date']),
                    'Customer Tax Number Validation Source'  => 'Online',
                    'Customer Tax Number Validation Message' => $tax_validation_data['Tax Number Validation Message'],
                ),
                'no_history'
            );
        } else {
            $this->update(
                array(
                    'Customer Tax Number Details Match'      => 'Unknown',
                    'Customer Tax Number Validation Date'    => $this->editor['Date'],
                    'Customer Tax Number Validation Source'  => 'Staff',
                    'Customer Tax Number Validation Message' => $this->editor['Author Name'],
                ),
                'no_history'
            );
            $this->update_field('Customer Tax Number Valid', $value);
        }


        // print_r($this->data);

        $this->other_fields_updated = array(
            'Customer_Tax_Number' => array(
                'field'           => 'Customer_Tax_Number',
                'render'          => true,
                'value'           => $this->get('Customer Tax Number'),
                'formatted_value' => $this->get('Tax Number'),


            )
        );
    }

    function get_field_label($field)
    {
        switch ($field) {
            case 'Customer Delivery Address Link':
                $label = _('delivery address link');
                break;
            case 'Customer Billing Address Link':
                $label = _('invoice address link');
                break;
            case 'Customer Registration Number':
                $label = _('registration number');
                break;
            case 'Customer Tax Number':
                $label = _('tax number');
                break;
            case 'Customer Tax Number Valid':
                $label = _('tax number validity');
                break;
            case 'Customer Company Name':
                $label = _('company name');
                break;
            case 'Customer Main Contact Name':
                $label = _('contact name');
                break;
            case 'Customer Main Plain Email':
                $label = _('email');
                break;
            case 'Customer Main Email':
                $label = _('main email');
                break;
            case 'Customer Other Email':
                $label = _('other email');
                break;
            case 'Customer Main Plain Telephone':
            case 'Customer Main XHTML Telephone':
                $label = _('telephone');
                break;
            case 'Customer Main Plain Mobile':
            case 'Customer Main XHTML Mobile':
                $label = _('mobile');
                break;
            case 'Customer Main Plain FAX':
            case 'Customer Main XHTML Fax':
                $label = _('fax');
                break;
            case 'Customer Other Telephone':
                $label = _('other telephone');
                break;
            case 'Customer Preferred Contact Number':
                $label = _('main contact number');
                break;
            case 'Customer Fiscal Name':
                $label = _('fiscal name');
                break;

            case 'Customer Contact Address':
                $label = _('contact address');
                break;

            case 'Customer Invoice Address':
                $label = _('invoice address');
                break;
            case 'Customer Delivery Address':
                $label = _('delivery address');
                break;
            case 'Customer Other Delivery Address':
                $label = _('other delivery address');
                break;
            case 'Customer Send Email Marketing':
                $label = _('subscription to email marketing');
                break;
            case 'Customer Send Basket Emails':
                $label = _('subscription to basket engagement emails');
                break;
            case 'Customer Send Postal Marketing':
                $label = _('subscription postal marketing');
                break;
            case 'Customer Send Newsletter':
                $label = _('subscription to newsletter');
                break;
            case 'Customer Website':
                $label = _('website');
                break;
            case 'Customer Credit Limit':
                $label = _('credit limit');
                break;
            default:
                $label = $field;
        }

        return $label;
    }

    function update_level_type($old_value = '')
    {
        if (!$old_value) {
            $old_value = $this->data['Customer Level Type'];
        }


        if ($this->data['Customer Level Type'] != 'Partner') {
            if ($this->data['Customer Sales Representative Key'] != '') {
                $value = 'VIP';
            } else {
                $value = 'Normal';
            }

            $this->fast_update(
                array(
                    'Customer Level Type' => $value
                )
            );
            $this->data['Customer Level Type'] = $value;
        }

        if ($old_value != $this->data['Customer Level Type']) {
            switch ($this->data['Customer Level Type']) {
                case 'Partner':
                    $history_data = array(
                        'History Abstract' => _('Customer set up as partner'),
                        'History Details'  => '',
                        'Action'           => 'edited'
                    );
                    break;
                case 'VIP':

                    $sales_representative = get_object('Sales_Representative', $this->data['Customer Sales Representative Key']);

                    $history_data = array(
                        'History Abstract' => sprintf(_('Customer set up as VIP with %s as account manager'), $sales_representative->user->get('Alias')),
                        'History Details'  => '',
                        'Action'           => 'edited'
                    );
                    break;
                case 'Normal':

                    if ($old_value == 'Partner') {
                        $history_data = array(
                            'History Abstract' => _('Customer is not longer set up as partner'),
                            'History Details'  => '',
                            'Action'           => 'edited'
                        );
                    } elseif ($old_value == 'VIP') {
                        $history_data = array(
                            'History Abstract' => _('Customer is not longer a VIP'),
                            'History Details'  => '',
                            'Action'           => 'edited'
                        );
                    } else {
                        $history_data = array(
                            'History Abstract' => _('Set up as normal customer'),
                            'History Details'  => '',
                            'Action'           => 'edited'
                        );
                    }
                    break;
                default:
                    $history_data = array(
                        'History Abstract' => sprintf(_('Set up as %s'), $this->data['Customer Level Type']),
                        'History Details'  => '',
                        'Action'           => 'edited'
                    );
            }


            if ($order_key = $this->get_order_in_process_key()) {
                $order = get_object('Order', $order_key);
                if ($order->id) {
                    $order->fast_update(
                        ['Order Customer Level Type' => $this->get('Customer Level Type')]
                    );
                }
            }

            $this->add_subject_history(
                $history_data,
                true,
                'No',
                'Changes',
                $this->get_object_name(),
                $this->id
            );

            $this->fork_index_elastic_search();
        }
    }

    function update_other_delivery_address($customer_delivery_address_key, $fields)
    {
        $updated_fields_number  = 0;
        $updated_address_fields = false;

        foreach ($fields as $field => $value) {
            $sql = sprintf(
                'UPDATE `Customer Other Delivery Address Dimension` SET %s=%s WHERE `Customer Other Delivery Address Key`=%d ',
                '`'.addslashes('Customer Other Delivery '.$field).'`',
                prepare_mysql($value),
                $customer_delivery_address_key
            );

            $update_op = $this->db->prepare($sql);
            $update_op->execute();
            $affected = $update_op->rowCount();


            if ($affected > 0) {
                $updated_fields_number++;
            }
        }


        if ($updated_fields_number > 0) {
            $this->updated = true;
        }


        if ($this->updated) {
            include_once 'utils/get_addressing.php';


            $address_fields = $this->get_other_delivery_address_fields($customer_delivery_address_key);

            // replace null to empty string do not remove

            array_walk_recursive($address_fields, function (&$item) {
                $item = strval($item);
            });


            $new_checksum = md5(json_encode($address_fields));


            $sql = sprintf(
                'UPDATE `Customer Other Delivery Address Dimension` SET `Customer Other Delivery Address Checksum`=%s WHERE `Customer Other Delivery Address Key`=%d ',
                prepare_mysql($new_checksum),
                $customer_delivery_address_key
            );
            $this->db->exec($sql);

            //print $sql;


            $store = get_object('Store', $this->get('Store Key'));


            list(
                $address, $formatter, $postal_label_formatter
                ) = get_address_formatter(
                $store->get('Store Home Country Code 2 Alpha'),
                $store->get('Store Locale')
            );

            if (preg_match('/gb|im|jy|gg/i', $address_fields['Address Country 2 Alpha Code'])) {
                include_once 'utils/geography_functions.php';
                $address_fields['Address Postal Code'] = gbr_pretty_format_post_code($address_fields['Address Postal Code']);
            }


            $address = $address->withFamilyName($address_fields['Address Recipient'])->withOrganization($address_fields['Address Organization'])->withAddressLine1($address_fields['Address Line 1'])->withAddressLine2($address_fields['Address Line 2'])->withSortingCode(
                $address_fields['Address Sorting Code']
            )->withPostalCode($address_fields['Address Postal Code'])->withDependentLocality(
                $address_fields['Address Dependent Locality']
            )->withLocality($address_fields['Address Locality'])->withAdministrativeArea(
                $address_fields['Address Administrative Area']
            )->withCountryCode(
                $address_fields['Address Country 2 Alpha Code']
            );

            $xhtml_address = $formatter->format($address);
            $xhtml_address = preg_replace('/<br>\s/', "\n", $xhtml_address);
            $xhtml_address = preg_replace(
                '/class="recipient"/',
                'class="recipient fn"',
                $xhtml_address
            );
            $xhtml_address = preg_replace(
                '/class="organization"/',
                'class="organization org"',
                $xhtml_address
            );
            $xhtml_address = preg_replace(
                '/class="address-line1"/',
                'class="address-line1 street-address"',
                $xhtml_address
            );
            $xhtml_address = preg_replace(
                '/class="address-line2"/',
                'class="address-line2 extended-address"',
                $xhtml_address
            );
            $xhtml_address = preg_replace(
                '/class="sort-code"/',
                'class="sort-code postal-code"',
                $xhtml_address
            );
            $xhtml_address = preg_replace(
                '/class="country"/',
                'class="country country-name"',
                $xhtml_address
            );


            $sql = sprintf(
                'UPDATE `Customer Other Delivery Address Dimension` SET `Customer Other Delivery Address Formatted`=%s WHERE `Customer Other Delivery Address Key`=%d ',
                prepare_mysql($xhtml_address),
                $customer_delivery_address_key
            );
            $this->db->exec($sql);

            $sql = sprintf(
                'UPDATE `Customer Other Delivery Address Dimension` SET `Customer Other Delivery Address Postal Label`=%s WHERE `Customer Other Delivery Address Key`=%d ',
                prepare_mysql($postal_label_formatter->format($address)),
                $customer_delivery_address_key
            );
            $this->db->exec($sql);
        }
    }

    function update_poll_answer($poll_key, $value, $options)
    {
        /**
         * @var $poll \Customer_Poll_Query
         */
        $poll = get_object('Customer_Poll_Query', $poll_key);
        $poll->add_customer($this, $value);
    }


    function get_addresses_data(): array
    {
        $address_data = [];


        $address_data[$this->data['Customer Invoice Address Checksum']] = array(
            'type'                       => 'invoice',
            'formatted_value'            => preg_replace('/<br>/', '', $this->get('Customer Invoice Address Formatted')),
            'label'                      => _('Current invoice address'),
            'other_delivery_address_key' => ''


        );

        if ($this->data['Customer Invoice Address Checksum'] != $this->data['Customer Delivery Address Checksum']) {
            $address_data[$this->data['Customer Delivery Address Checksum']] = array(
                'type'                       => 'delivery',
                'formatted_value'            => preg_replace('/<br>/', '', $this->get('Customer Invoice Address Formatted')),
                'label'                      => _('Current delivery address'),
                'other_delivery_address_key' => ''

            );
        }


        $sql = sprintf(
            "SELECT `Customer Other Delivery Address Checksum`,`Customer Other Delivery Address Key`,`Customer Other Delivery Address Formatted`,`Customer Other Delivery Address Label` FROM `Customer Other Delivery Address Dimension` WHERE `Customer Other Delivery Address Customer Key`=%d ORDER BY `Customer Other Delivery Address Key`",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if (!array_key_exists($row['Customer Other Delivery Address Checksum'], $address_data)) {
                    $address_data[$row['Customer Other Delivery Address Checksum']] = array(
                        'type'                       => 'other_delivery',
                        'formatted_value'            => $row['Customer Other Delivery Address Formatted'],
                        'label'                      => $row['Customer Other Delivery Address Label'],
                        'other_delivery_address_key' => $row['Customer Other Delivery Address Key']

                    );
                }
            }
        }


        return $address_data;
    }

    function get_other_delivery_addresses_data(): array
    {
        $sql = sprintf(
            "SELECT `Customer Other Delivery Address Checksum`,`Customer Other Delivery Address Key`,`Customer Other Delivery Address Formatted`,`Customer Other Delivery Address Label` FROM `Customer Other Delivery Address Dimension` WHERE `Customer Other Delivery Address Customer Key`=%d ORDER BY `Customer Other Delivery Address Key`",
            $this->id
        );

        $delivery_address_keys = [];

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $delivery_address_keys[$row['Customer Other Delivery Address Key']] = array(
                    'value'           => $this->get('Customer Other Delivery Address '.$row['Customer Other Delivery Address Key']),
                    'formatted_value' => $row['Customer Other Delivery Address Formatted'],
                    'label'           => $row['Customer Other Delivery Address Label'],
                    'checksum'        => $row['Customer Other Delivery Address Checksum'],

                );
            }
        }


        return $delivery_address_keys;
    }


    function get_order_in_process_key()
    {
        $order_key = false;
        $sql       = sprintf(
            "SELECT `Order Key` FROM `Order Dimension` WHERE `Order Customer Key`=%d AND `Order State`='InBasket' ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $order_key = $row['Order Key'];
            }
        }


        return $order_key;
    }


    public function update_orders()
    {
        $customer_orders = 0;


        $orders_cancelled = 0;

        $order_interval     = '';
        $order_interval_std = '';

        $customer_with_orders = 'No';
        $first_order          = '';
        $last_order           = '';

        $payments = 0;


        $sql = sprintf(
            "SELECT sum(`Payment Transaction Amount`) AS payments FROM `Payment Dimension` WHERE   `Payment Customer Key`=%d  AND `Payment Transaction Status`='Completed' ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $payments = $row['payments'];
            }
        }


        $sql = sprintf(
            "SELECT
		min(`Order Date`) AS first_order_date ,
		max(`Order Date`) AS last_order_date,
		count(*) AS orders
		FROM `Order Dimension` WHERE `Order Customer Key`=%d  AND `Order State` NOT IN ('Cancelled','InBasket') ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $customer_orders = $row['orders'];


                if ($customer_orders > 0) {
                    $first_order          = $row['first_order_date'];
                    $last_order           = $row['last_order_date'];
                    $customer_with_orders = 'Yes';
                }
            }
        }


        $sql = sprintf(
            "SELECT
	
		count(*) AS orders
		FROM `Order Dimension` WHERE `Order Customer Key`=%d  AND `Order State`  IN ('Cancelled') ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $orders_cancelled = $row['orders'];
            }
        }


        if (($orders_cancelled + $customer_orders) > 1) {
            $_last_order = false;
            $_last_date  = false;
            $intervals   = [];
            $sql         = "SELECT `Order Date` AS date FROM `Order Dimension` WHERE  `Order State`  NOT IN ('InBasket')  AND `Order Customer Key`=? ORDER BY `Order Date`";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(array(
                               $this->id
                           ));
            while ($row = $stmt->fetch()) {
                $this_date = gmdate('U', strtotime($row['date']));
                if ($_last_order) {
                    $intervals[] = ($this_date - $_last_date);
                }

                $_last_date  = $this_date;
                $_last_order = true;
            }


            $order_interval     = average($intervals);
            $order_interval_std = deviation($intervals);
        }


        $update_data = array(
            'Customer Orders'           => $customer_orders,
            'Customer Orders Cancelled' => $orders_cancelled,

            'Customer First Order Date' => $first_order,
            'Customer Last Order Date'  => $last_order,

            'Customer Order Interval'     => $order_interval,
            'Customer Order Interval STD' => $order_interval_std,
            'Customer With Orders'        => $customer_with_orders,
            'Customer Payments Amount'    => $payments,
            //'Customer Sales Amount'              => $sales_amount,
            //'Customer Total Sales Amount'        => $invoiced_net_amount,


        );

        $this->fast_update($update_data);
    }


    public function update_invoices()
    {
        $first_invoiced_date = '';
        $last_invoiced_date  = '';


        $sales_amount        = 0;
        $sales_dc_amount     = 0;
        $invoiced_net_amount = 0;
        $refunded_net_amount = 0;

        $customer_invoices = 0;
        $customer_refunds  = 0;


        $sql = "SELECT count(*) AS num ,
		min(`Invoice Date`) AS first_invoiced_date ,
		max(`Invoice Date`) AS last_invoiced_date,
        sum(`Invoice Total Net Amount`) AS net 
		FROM `Invoice Dimension` WHERE `Invoice Type`='Invoice'  AND `Invoice Customer Key`=?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
                           $this->id
                       ));
        while ($row = $stmt->fetch()) {
            $customer_invoices   = $row['num'];
            $invoiced_net_amount = $row['net'];
            $first_invoiced_date = $row['first_invoiced_date'];
            $last_invoiced_date  = $row['last_invoiced_date'];
        }


        $sql = sprintf(
            "SELECT count(*) AS num ,sum(`Invoice Total Net Amount`) AS net FROM `Invoice Dimension` WHERE `Invoice Type`='Refund' and  `Invoice Customer Key`=%d  ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $customer_refunds    = $row['num'];
                $refunded_net_amount = $row['net'];
            }
        }


        $sql = sprintf(
            "SELECT sum(`Invoice Total Net Amount`) AS amount , sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) AS dc_amount
 FROM `Invoice Dimension` WHERE  `Invoice Customer Key`=%d  ",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_amount    = $row['amount'];
                $sales_dc_amount = $row['dc_amount'];
            }
        }


        $update_data = array(
            'Customer First Invoiced Order Date' => $first_invoiced_date,
            'Customer Last Invoiced Order Date'  => $last_invoiced_date,
            'Customer Sales Amount'              => $sales_amount,
            'Customer Sales DC Amount'           => $sales_dc_amount,
            'Customer Invoiced Net Amount'       => $invoiced_net_amount,
            'Customer Refunded Net Amount'       => $refunded_net_amount,
            'Customer Number Invoices'           => $customer_invoices,
            'Customer Number Refunds'            => $customer_refunds,

        );

        $this->fast_update($update_data);


        $sql = sprintf('select `Prospect Key` from `Prospect Dimension` where `Prospect Customer Key`=%d ', $this->id);
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                /** @var \Prospect $prospect */
                $prospect = get_object('Prospect', $row['Prospect Key']);
                $prospect->fast_update(array('Prospect Invoiced' => ($customer_invoices > 0 ? 'Yes' : 'No')));

                if ($customer_invoices > 0 and $prospect->get('Prospect Status') == 'Registered') {
                    $sql = sprintf('select `Invoice Key` from `Invoice Dimension` where `Invoice Customer Key`=%d order by `Invoice Date` limit 1 ', $this->id);
                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $first_invoice = get_object('Invoice', $row['Invoice Key']);
                            $prospect->update_status('Invoiced', $first_invoice);
                        }
                    }
                }
            }
        }
    }

    public function update_clients_data()
    {
        $clients = 0;
        $sql     = "SELECT count(*) AS num FROM `Customer Client Dimension` WHERE `Customer Client Customer Key`=?  ";
        $stmt    = $this->db->prepare($sql);
        $stmt->execute(array($this->id));
        while ($row = $stmt->fetch()) {
            $clients = $row['num'];
        }


        $this->fast_update(array(
                               'Customer Number Clients' => $clients,
                           ));
    }

    public function update_portfolio()
    {
        $products = 0;
        $sql      = "SELECT count(*) AS num FROM `Customer Portfolio Fact` WHERE `Customer Portfolio Customers State`='Active' and  `Customer Portfolio Customer Key`=?  ";
        $stmt     = $this->db->prepare($sql);
        $stmt->execute(array($this->id));
        while ($row = $stmt->fetch()) {
            $products = $row['num'];
        }


        $this->fast_update(array(
                               'Customer Number Products in Portfolio' => $products,
                           ));
    }

    public function update_associated_products()
    {
        $associated_products = 0;


        $sql = sprintf(
            "SELECT count(*) AS num FROM `Product Dimension` WHERE   `Product Customer Key`=%d  AND `Product Type`='Product' and  `Product Status` not in ( 'Suspended','Discontinued') ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $associated_products = $row['num'];
            }
        }


        $update_data = array(
            'Customer Number Products' => $associated_products,


        );


        $this->fast_update($update_data);
    }


    public function update_payments()
    {
        $payments = 0;


        $sql = sprintf(
            "SELECT sum(`Payment Transaction Amount`) AS payments FROM `Payment Dimension` WHERE   `Payment Customer Key`=%d  AND `Payment Transaction Status`='Completed' ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $payments = $row['payments'];
            }
        }


        $update_data = array(
            'Customer Payments Amount' => $payments,


        );


        $this->fast_update($update_data);
    }

    public function update_activity()
    {
        if ($this->data['Customer Type by Activity'] == 'ToApprove' or $this->data['Customer Type by Activity'] == 'Rejected') {
            return;
        }


        $orders = $this->data['Customer Orders'];


        $store = get_object('store', $this->data['Customer Store Key']);


        if ($orders == 0) {
            $type_by_activity   = 'Active';
            $is_customer_active = 'Yes';
            if (strtotime('now') - strtotime($this->data['Customer First Contacted Date']) > $store->data['Store Losing Customer Interval']) {
                $type_by_activity = 'Losing';
            }
            if (strtotime('now') - strtotime($this->data['Customer First Contacted Date']) > $store->data['Store Lost Customer Interval']) {
                $type_by_activity   = 'NeverOrder';
                $is_customer_active = 'No';
            }

            $customer_lost_date = gmdate('Y-m-d H:i:s', strtotime($this->data['Customer First Contacted Date']." +".$store->data['Store Lost Customer Interval']." seconds"));
        } else {
            $losing_interval = $store->data['Store Losing Customer Interval'];
            $lost_interval   = $store->data['Store Lost Customer Interval'];

            if ($orders > 20) {
                $sigma_factor = 3.2906;//99.9% value assuming normal distribution

                $losing_interval = (3 * $this->data['Customer Order Interval'] + $sigma_factor * $this->data['Customer Order Interval STD']) + 30;
                $lost_interval   = $losing_interval * 4.0;
            }

            $lost_interval   = ceil($lost_interval);
            $losing_interval = ceil($losing_interval);


            $type_by_activity   = 'Active';
            $is_customer_active = 'Yes';
            if (strtotime('now') - strtotime($this->data['Customer Last Order Date']) > $losing_interval) {
                $type_by_activity = 'Losing';
            }
            if (strtotime('now') - strtotime($this->data['Customer Last Order Date']) > $lost_interval) {
                $type_by_activity   = 'Lost';
                $is_customer_active = 'No';
            }
            $customer_lost_date = gmdate('Y-m-d H:i:s', strtotime($this->data['Customer Last Order Date']." +$lost_interval seconds"));
        }


        $this->fast_update(array(
                               'Customer Active'           => $is_customer_active,
                               'Customer Type by Activity' => $type_by_activity,
                               'Customer Lost Date'        => $customer_lost_date,
                           ));
    }

    function delete($note = '')
    {
        $this->deleted = false;


        $sql  = "SELECT `Order Key`  FROM `Order Dimension` WHERE `Order State` in  ('InBasket','InProcess') and  `Order Customer Key`=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
                           $this->id
                       ));
        while ($row = $stmt->fetch()) {
            /** @var \Order $order */
            $order         = get_object('Order', $row['Order Key']);
            $order->editor = $this->editor;
            $order->cancel(_('Cancelled because customer was deleted'));
        }


        $number_orders = 0;

        $sql  = "SELECT count(*) AS num  FROM `Order Dimension` WHERE `Order State`!='Cancelled' and  `Order Customer Key`=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
                           $this->id
                       ));
        if ($row = $stmt->fetch()) {
            $number_orders = $row['num'];
        }


        if ($number_orders > 0) {
            $this->msg = sprintf(
                ngettext(
                    "Customer can't be deleted because it has %s order",
                    "Customer can't be deleted because it has %s orders",
                    $number_orders
                ),
                number($number_orders)
            );


            $this->error = true;

            return;
        }


        $history_data = array(
            'History Abstract' => _('Customer deleted'),
            'History Details'  => '',
            'Action'           => 'deleted'
        );

        $this->add_subject_history(
            $history_data,
            true,
            'No',
            'Changes',
            $this->get_object_name(),
            $this->id
        );


        $sql = sprintf(
            "DELETE FROM `Customer Dimension` WHERE `Customer Key`=%d",
            $this->id
        );
        $this->db->exec($sql);


        $sql = sprintf(
            "INSERT INTO `Customer Deleted Dimension` (`Customer Key`,`Customer Store Key`,`Customer Deleted Name`,`Customer Deleted Contact Name`,`Customer Deleted Email`,`Customer Deleted Metadata`,`Customer Deleted Date`,`Customer Deleted Note`) VALUE (%d,%d,%s,%s,%s,%s,%s,%s) ",
            $this->id,
            $this->data['Customer Store Key'],
            prepare_mysql($this->data['Customer Name']),
            prepare_mysql($this->data['Customer Main Contact Name']),
            prepare_mysql($this->data['Customer Main Plain Email']),
            prepare_mysql(gzcompress(json_encode($this->data), 9)),
            prepare_mysql($this->editor['Date']),
            prepare_mysql($note, false)
        );


        $this->db->exec($sql);


        require_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_housekeeping',
            array(
                'type'         => 'customer_deleted',
                'store_key'    => $this->data['Customer Store Key'],
                'customer_key' => $this->id,
                'website_user' => $this->get('Customer Website User Key'),
                'editor'       => $this->editor
            ),
            DNS_ACCOUNT_CODE,
            $this->db
        );

        $this->fork_index_elastic_search('delete_elastic_index_object');
        $this->model_updated('deleted',$this->id);
        $this->deleted = true;
    }

    function get_category_data(): array
    {
        $category_data = [];

        $sql = sprintf(
            "SELECT `Category Root Key`,`Other Note`,`Category Label`,`Category Code`,`Is Category Field Other` FROM `Category Bridge` B LEFT JOIN `Category Dimension` C ON (C.`Category Key`=B.`Category Key`) WHERE  `Category Branch Type`='Head'  AND B.`Subject Key`=%d AND B.`Subject`='Customer'",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $sql = sprintf(
                    "SELECT `Category Label`,`Category Code` FROM `Category Dimension` WHERE `Category Key`=%d",
                    $row['Category Root Key']
                );


                if ($result2 = $this->db->query($sql)) {
                    if ($row2 = $result2->fetch()) {
                        $root_label = $row2['Category Label'];
                        $root_code  = $row2['Category Code'];
                    }
                }
                if ($row['Is Category Field Other'] == 'Yes' and $row['Other Note'] != '') {
                    $value = $row['Other Note'];
                } else {
                    $value = $row['Category Label'];
                }
                $category_data[] = array(
                    'root_label' => $root_label,
                    'root_code'  => $root_code,
                    'label'      => $row['Category Label'],
                    'code'       => $row['Category Code'],
                    'value'      => $value
                );
            }
        }


        return $category_data;
    }


    function get_number_saved_credit_cards($delivery_address_checksum, $invoice_address_checksum)
    {
        $number_saved_credit_cards = 0;
        $sql                       = sprintf(
            "SELECT count(*) AS number FROM `Customer Credit Card Dimension` WHERE `Customer Credit Card Customer Key`=%d AND `Customer Credit Card Invoice Address Checksum`=%s AND `Customer Credit Card Delivery Address Checksum`=%s AND   `Customer Credit Card Valid Until`>NOW()  ",
            $this->id,
            prepare_mysql($invoice_address_checksum),
            prepare_mysql($delivery_address_checksum)
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_saved_credit_cards = $row['number'];
            }
        }


        return $number_saved_credit_cards;
    }

    function get_saved_credit_cards($delivery_address_checksum, $invoice_address_checksum)
    {
        $key = md5($this->id.','.$delivery_address_checksum.','.$invoice_address_checksum.','.CKEY);

        $card_data = [];
        $sql       = sprintf(
            "SELECT * FROM `Customer Credit Card Dimension` WHERE `Customer Credit Card Customer Key`=%d AND `Customer Credit Card Invoice Address Checksum`=%s AND `Customer Credit Card Delivery Address Checksum`=%s AND   `Customer Credit Card Valid Until`>NOW()  ",
            $this->id,
            prepare_mysql($invoice_address_checksum),
            prepare_mysql($delivery_address_checksum)


        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $_card_data       = json_decode(AESDecryptCtr($row['Customer Credit Card Metadata'], $key, 256), true);
                $_card_data['id'] = $row['Customer Credit Card Key'];

                $card_data[] = $_card_data;
            }
        }


        return $card_data;
    }

    function delete_credit_card($card_key)
    {
        $tokens = [];
        $sql    = sprintf(
            "SELECT `Customer Credit Card CCUI` FROM `Customer Credit Card Dimension` WHERE `Customer Credit Card Customer Key`=%d  AND `Customer Credit Card Key`=%d ",
            $this->id,

            $card_key
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sql = sprintf(
                    'SELECT `Customer Credit Card Key`,`Customer Credit Card Invoice Address Checksum`,`Customer Credit Card Delivery Address Checksum` FROM `Customer Credit Card Dimension`  WHERE `Customer Credit Card Customer Key`=%d AND `Customer Credit Card CCUI`=%s',
                    $this->id,
                    prepare_mysql($row['Customer Credit Card CCUI'])
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row2) {
                        $tokens[] = $this->get_credit_card_token(
                            $row2['Customer Credit Card Key'],
                            $row2['Customer Credit Card Invoice Address Checksum'],
                            $row2['Customer Credit Card Delivery Address Checksum']
                        );

                        $sql = sprintf(
                            'DELETE FROM `Customer Credit Card Dimension`  WHERE `Customer Credit Card Key`=%d',
                            $row2['Customer Credit Card Key']
                        );

                        $this->db->exec($sql);
                    }
                }
            }
        }


        return $tokens;
    }

    function get_credit_card_token($card_key, $delivery_address_checksum, $invoice_address_checksum)
    {
        $key = md5($this->id.','.$delivery_address_checksum.','.$invoice_address_checksum.','.CKEY);

        $token = false;
        $sql   = sprintf(
            "SELECT `Customer Credit Card Metadata` FROM `Customer Credit Card Dimension` WHERE `Customer Credit Card Customer Key`=%d AND `Customer Credit Card Invoice Address Checksum`=%s AND `Customer Credit Card Delivery Address Checksum`=%s AND   `Customer Credit Card Valid Until`>NOW() AND  `Customer Credit Card Key`=%d ",
            $this->id,
            prepare_mysql($invoice_address_checksum),
            prepare_mysql($delivery_address_checksum),
            $card_key
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $_card_data = json_decode(AESDecryptCtr($row['Customer Credit Card Metadata'], $key, 256), true);
                $token      = $_card_data['Token'];
            }
        }


        return $token;
    }

    function update_product_bridge()
    {
        $sql = sprintf(
            "DELETE FROM `Customer Product Bridge` WHERE `Customer Product Customer Key`=%d ",
            $this->id
        );
        $this->db->exec($sql);


        $sql = sprintf(
            "SELECT `Product ID`, count(DISTINCT `Invoice Key`) invoices ,max(`Invoice Date`) AS date FROM `Order Transaction Fact`  WHERE     `Invoice Key`>0 AND (`Delivery Note Quantity`)>0  AND  `Customer Key`=%d  GROUP BY `Product ID` ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $sql = sprintf(
                    "INSERT INTO `Customer Product Bridge` (`Customer Product Customer Key`,`Customer Product Product ID`,`Customer Product Invoices`,`Customer Product Last Invoice Date`) VALUES (%d,%d,%s,%s) ",
                    $this->id,
                    $row['Product ID'],
                    $row['invoices'],
                    prepare_mysql($row['date']),

                );


                $this->db->exec($sql);
            }
        }
    }

    function update_part_bridge()
    {
        $sql = sprintf(
            "DELETE FROM `Customer Part Bridge` WHERE `Customer Part Customer Key`=%d ",
            $this->id
        );
        $this->db->exec($sql);


        $sql = sprintf(
            "SELECT `Part SKU`, count(DISTINCT ITF.`Delivery Note Key`) delivery_notes ,max(`Delivery Note Date`) AS date FROM `Inventory Transaction Fact` ITF LEFT JOIN `Delivery Note Dimension` DN ON (DN.`Delivery Note Key`=ITF.`Delivery Note Key`) WHERE   `Inventory Transaction Type`='Sale'  AND  `Delivery Note Customer Key`=%d  GROUP BY `Part SKU` ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $sql = sprintf(
                    "INSERT INTO `Customer Part Bridge` (`Customer Part Customer Key`,`Customer Part Part SKU`,`Customer Part Delivery Notes`,`Customer Part Last Delivery Note Date`) VALUES (%d,%d,%s,%s) ",
                    $this->id,
                    $row['Part SKU'],
                    $row['delivery_notes'],
                    prepare_mysql($row['date'])

                );

                //print "$sql\n";
                $this->db->exec($sql);
            }
        }
    }


    /**
     * @throws \ErrorException
     */
    function set_account_balance_adjust($amount, $note)
    {
        include_once "utils/currency_functions.php";

        $amount = round($amount, 2);
        if ($amount == 0) {
            return;
        }


        $account = get_object('Account', $this->db);
        $store   = get_object('Store', $this->data['Customer Store Key']);
        $date    = gmdate('Y-m-d H:i:s');

        $exchange = currency_conversion(
            $this->db,
            $store->get('Store Currency Code'),
            $account->get('Account Currency'),
            '- 180 minutes'
        );


        $sql = "INSERT INTO `Credit Transaction Fact` 
                    (`Credit Transaction Date`,`Credit Transaction Amount`,`Credit Transaction Currency Code`,`Credit Transaction Currency Exchange Rate`,`Credit Transaction Customer Key`,`Credit Transaction Type`) 
                    VALUES (?,?,?,?,?,'Adjust')";

        $this->db->prepare($sql)->execute(array(
                                              $date,
                                              $amount,
                                              $store->get('Store Currency Code'),
                                              $exchange,
                                              $this->id
                                          ));


        $this->db->exec($sql);
        $credit_key = $this->db->lastInsertId();

        if ($credit_key == 0) {
            print $sql;
            exit;
        }


        $history_data = array(
            'History Abstract' => sprintf(
                _('Customer account balance adjusted %s'),
                ($amount > 0 ? '+' : ':').money($amount, $store->get('Store Currency Code')).($note != '' ? ' ('.$note.')' : '')
            ),
            'History Details'  => '',
            'Action'           => 'edited'
        );

        $history_key = $this->add_subject_history(
            $history_data,
            true,
            'No',
            'Changes',
            $this->get_object_name(),
            $this->id
        );

        $sql = sprintf(
            'INSERT INTO `Credit Transaction History Bridge` 
                    (`Credit Transaction History Credit Transaction Key`,`Credit Transaction History History Key`) 
                    VALUES (%d,%d) ',
            $credit_key,
            $history_key


        );
        $this->db->exec($sql);


        $this->update_account_balance();
        $this->update_credit_account_running_balances();
    }

    function update_account_balance()
    {
        $balance = 0;


        $sql = sprintf(
            'SELECT sum(`Credit Transaction Amount`) AS balance FROM `Credit Transaction Fact`  WHERE `Credit Transaction Customer Key`=%d  order by `Credit Transaction Date`,`Credit Transaction Key` ',
            $this->id
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $balance = $row['balance'];
            }
        }

        $this->fast_update(array('Customer Account Balance' => $balance));


        $sql = 'update `Order Dimension`  set `Order Available Credit Amount`=:credit where `Order Customer Key`=:key  ';

        $stmt = $this->db->prepare($sql);


        $credit = $this->get('Customer Account Balance');

        $stmt->bindParam(':credit', $credit);

        $stmt->bindParam(':key', $this->id, PDO::PARAM_INT);

        $stmt->execute();
    }

    function update_credit_account_running_balances()
    {
        $running_balance     = 0;
        $credit_transactions = 0;

        $sql  = 'SELECT `Credit Transaction Amount`,`Credit Transaction Key`  FROM `Credit Transaction Fact`  WHERE `Credit Transaction Customer Key`=? order by `Credit Transaction Date`,`Credit Transaction Key`    ';
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute(array(
                               $this->id
                           ))) {
            while ($row = $stmt->fetch()) {
                $running_balance += $row['Credit Transaction Amount'];
                $sql             = 'update `Credit Transaction Fact` set `Credit Transaction Running Amount`=? where `Credit Transaction Key`=?  ';
                $this->db->prepare($sql)->execute(array(
                                                      $running_balance,
                                                      $row['Credit Transaction Key']
                                                  ));
                $credit_transactions++;
            }
        }

        $this->fast_update(array('Customer Number Credit Transactions' => $credit_transactions));
    }

    function approve(): string
    {
        include_once 'utils/new_fork.php';

        $this->update(array('Customer Type by Activity' => 'Active'));

        new_housekeeping_fork(
            'au_housekeeping',
            array(
                'type'                => 'customer_approval_done',
                'email_template_code' => 'Registration Approved',
                'customer_key'        => $this->id,
            ),
            DNS_ACCOUNT_CODE
        );

        $sql  = "select `Customer Key` from `Customer Dimension` where `Customer Type by Activity`='ToApprove' and `Customer Store Key`=? order by `Customer First Contacted Date`   limit 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
                           $this->data['Customer Store Key']
                       ));
        if ($row = $stmt->fetch()) {
            return 'customers/'.$this->data['Customer Store Key'].'/'.$row['Customer Key'];
        } else {
            return 'customers/'.$this->data['Customer Store Key'];
        }
    }

    function reject(): string
    {
        include_once 'utils/new_fork.php';

        $this->update(array('Customer Type by Activity' => 'Rejected'));


        new_housekeeping_fork(
            'au_housekeeping',
            array(
                'type'                => 'customer_approval_done',
                'email_template_code' => 'Registration Rejected',
                'customer_key'        => $this->id,
            ),
            DNS_ACCOUNT_CODE
        );

        $sql  = "select `Customer Key` from `Customer Dimension` where `Customer Type by Activity`='ToApprove' and `Customer Store Key`=? order by `Customer First Contacted Date`   limit 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
                           $this->data['Customer Store Key']
                       ));
        if ($row = $stmt->fetch()) {
            return 'customers/'.$this->data['Customer Store Key'].'/'.$row['Customer Key'];
        } else {
            return 'customers/'.$this->data['Customer Store Key'];
        }
    }

    function update_last_dispatched_order_key()
    {
        $order_key = '';
        $date      = '';
        $sql       = sprintf(
            "SELECT `Order Key`,Date(`Order Dispatched Date`) as dispatched_date from `Order Dimension` WHERE `Order Customer Key`=%d  AND `Order State`='Dispatched' order by `Order Dispatched Date` desc limit 1 ",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $order_key = $row['Order Key'];
                $date      = $row['dispatched_date'];
            }
        }

        $this->fast_update(array(
                               'Customer Last Dispatched Order Key'  => $order_key,
                               'Customer Last Dispatched Order Date' => $date
                           ));
    }

    function load_previous_years_data()
    {
        foreach (range(1, 5) as $i) {
            $data_iy_ago = $this->get_sales_data(
                date('Y-01-01 00:00:00', strtotime('-'.$i.' year')),
                date('Y-01-01 00:00:00', strtotime('-'.($i - 1).' year'))
            );


            $data_to_update = array(
                $this->table_name." $i Year Ago Invoices"            => $data_iy_ago['invoices'],
                $this->table_name." $i Year Ago Refunds"             => $data_iy_ago['refunds'],
                $this->table_name." $i Year Ago Invoiced Net Amount" => $data_iy_ago['invoiced_amount'],
                $this->table_name." $i Year Ago Refunded Net Amount" => $data_iy_ago['refunded_amount'],
                $this->table_name." $i Year Ago Net Amount"          => $data_iy_ago['balance_amount'],

            );


            foreach ($data_to_update as $key => $value) {
                $this->data[$key] = $value;
            }
        }
    }

    function get_sales_data($from_date, $to_date): array
    {
        $sales_data = array(
            'balance_amount'  => 0,
            'invoiced_amount' => 0,
            'refunded_amount' => 0,
            'profit'          => 0,
            'invoices'        => 0,
            'refunds'         => 0,


        );


        $sql = sprintf(
            "select count(*) as num , sum(`Invoice Total Net Amount`) as amount from `Invoice Dimension` where `Invoice Type`='Invoice' and  `Invoice Customer Key`=%d  %s %s",
            $this->id,
            ($from_date ? sprintf('and  `Invoice Date`>=%s', prepare_mysql($from_date)) : ''),
            ($to_date ? sprintf('and `Invoice Date`<%s', prepare_mysql($to_date)) : '')
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['invoiced_amount'] = $row['amount'];
                $sales_data['invoices']        = $row['num'];
            }
        }

        $sql = sprintf(
            "select count(*) as num , sum(`Invoice Total Net Amount`) as amount from `Invoice Dimension` where `Invoice Type`='Refund' and  `Invoice Customer Key`=%d  %s %s",
            $this->id,
            ($from_date ? sprintf('and  `Invoice Date`>=%s', prepare_mysql($from_date)) : ''),
            ($to_date ? sprintf('and `Invoice Date`<%s', prepare_mysql($to_date)) : '')
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['refunded_amount'] = $row['amount'];
                $sales_data['refunds']         = $row['num'];
            }
        }

        $sales_data['balance_amount'] = $sales_data['invoiced_amount'] - $sales_data['refunded_amount'];

        return $sales_data;
    }

    function load_previous_quarters_data()
    {
        include_once 'utils/date_functions.php';


        foreach (range(1, 4) as $i) {
            $dates     = get_previous_quarters_dates($i);
            $dates_1yb = get_previous_quarters_dates($i + 4);


            $sales_data     = $this->get_sales_data($dates['start'], $dates['end']);
            $sales_data_1yb = $this->get_sales_data($dates_1yb['start'], $dates_1yb['end']);

            $data_to_update = array(
                $this->table_name." $i Quarter Ago Invoices"            => $sales_data['invoices'],
                $this->table_name." $i Quarter Ago Refunds"             => $sales_data['refunds'],
                $this->table_name." $i Quarter Ago Invoiced Net Amount" => $sales_data['invoiced_amount'],
                $this->table_name." $i Quarter Ago Refunded Net Amount" => $sales_data['refunded_amount'],
                $this->table_name." $i Quarter Ago Net Amount"          => $sales_data['balance_amount'],

                $this->table_name." $i Quarter Ago 1YB Invoices"            => $sales_data_1yb['invoices'],
                $this->table_name." $i Quarter Ago 1YB Refunds"             => $sales_data_1yb['refunds'],
                $this->table_name." $i Quarter Ago 1YB Invoiced Net Amount" => $sales_data_1yb['invoiced_amount'],
                $this->table_name." $i Quarter Ago 1YB Refunded Net Amount" => $sales_data_1yb['refunded_amount'],
                $this->table_name." $i Quarter Ago 1YB Net Amount"          => $sales_data_1yb['balance_amount'],


            );
            foreach ($data_to_update as $key => $value) {
                $this->data[$key] = $value;
            }
        }
    }

    function load_sales_data($interval, $this_year = true, $last_year = true)
    {
        include_once 'utils/date_functions.php';
        list($db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb) = calculate_interval_dates($this->db, $interval);

        if ($this_year) {
            $sales_data = $this->get_sales_data($from_date, $to_date);


            $data_to_update = array(
                $this->table_name." $db_interval Acc Invoices"            => $sales_data['invoices'],
                $this->table_name." $db_interval Acc Refunds"             => $sales_data['refunds'],
                $this->table_name." $db_interval Acc Invoiced Net Amount" => $sales_data['invoiced_amount'],
                $this->table_name." $db_interval Acc Refunded Net Amount" => $sales_data['refunded_amount'],
                $this->table_name." $db_interval Acc Net Amount"          => $sales_data['balance_amount'],

            );
            foreach ($data_to_update as $key => $value) {
                $this->data[$key] = $value;
            }
        }

        if ($from_date_1yb and $last_year) {
            $sales_data = $this->get_sales_data($from_date_1yb, $to_date_1yb);


            $data_to_update = array(
                $this->table_name." $db_interval Acc 1YB Invoices"            => $sales_data['invoices'],
                $this->table_name." $db_interval Acc 1YB Refunds"             => $sales_data['refunds'],
                $this->table_name." $db_interval Acc 1YB Invoiced Net Amount" => $sales_data['invoiced_amount'],
                $this->table_name." $db_interval Acc 1YB Refunded Net Amount" => $sales_data['refunded_amount'],
                $this->table_name." $db_interval Acc 1YB Net Amount"          => $sales_data['balance_amount'],


            );
            foreach ($data_to_update as $key => $value) {
                $this->data[$key] = $value;
            }
        }
    }

    function create_timeseries($data, $fork_key = 0)
    {
        if (($this->data['Customer Number Invoices'] + $this->data['Customer Number Invoices']) == 0) {
            return;
        }

        include_once 'class.Timeserie.php';

        $data['Timeseries Parent']     = 'Customer';
        $data['Timeseries Parent Key'] = $this->id;
        $data['editor']                = $this->editor;

        $timeseries = new Timeseries('find', $data, 'create');

        // print_r($timeseries);
        if ($timeseries->id) {
            require_once 'utils/date_functions.php';


            $from = date('Y-m-d', strtotime($this->data['Customer First Invoiced Order Date']));

            $to = date('Y-m-d', strtotime($this->data['Customer Last Invoiced Order Date']));


            $sql = sprintf(
                'DELETE FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`<%s ',
                $timeseries->id,
                prepare_mysql($from)
            );


            $update_sql = $this->db->prepare($sql);
            $update_sql->execute();
            if ($update_sql->rowCount()) {
                $timeseries->update(
                    array('Timeseries Updated' => gmdate('Y-m-d H:i:s')),
                    'no_history'
                );
            }

            $sql        = sprintf(
                'DELETE FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`>%s ',
                $timeseries->id,
                prepare_mysql($to)
            );
            $update_sql = $this->db->prepare($sql);
            $update_sql->execute();
            if ($update_sql->rowCount()) {
                $timeseries->update(
                    array('Timeseries Updated' => gmdate('Y-m-d H:i:s')),
                    'no_history'
                );
            }


            if ($from and $to) {
                $this->update_timeseries_record($timeseries, $from, $to, $fork_key);
            }


            if ($timeseries->get('Timeseries Number Records') == 0) {
                $timeseries->update(
                    array('Timeseries Updated' => gmdate('Y-m-d H:i:s')),
                    'no_history'
                );
            }
        }
    }

    function update_timeseries_record($timeseries, $from, $to, $fork_key = false)
    {
        $dates = date_frequency_range($this->db, $timeseries->get('Timeseries Frequency'), $from, $to);
        // print $timeseries->id."\n";
        // print $timeseries->get('Timeseries Frequency')."\n";
        //print_r($dates);

        if ($fork_key) {
            $sql = sprintf(
                "UPDATE `Fork Dimension` SET `Fork State`='In Process' ,`Fork Operations Total Operations`=%d,`Fork Start Date`=NOW(),`Fork Result`=%d  WHERE `Fork Key`=%d ",
                count($dates),
                $timeseries->id,
                $fork_key
            );

            $this->db->exec($sql);
        }
        $index = 0;


        foreach ($dates as $date_frequency_period) {
            $index++;


            $sales_data = $this->get_sales_data($date_frequency_period['from'], $date_frequency_period['to']);
            // print_r($sales_data);


            $_date = gmdate('Y-m-d', strtotime($date_frequency_period['from'].' +0:00'));


            if ($sales_data['invoices'] > 0 or $sales_data['refunds'] > 0) {
                list($timeseries_record_key, $date) = $timeseries->create_record(array('Timeseries Record Date' => $_date));


                $sql = "UPDATE `Timeseries Record Dimension` SET 
                              `Timeseries Record Integer A`=? ,`Timeseries Record Integer B`=? ,
                              `Timeseries Record Float A`=? ,  `Timeseries Record Float B`=? ,`Timeseries Record Float C`=? ,
                              `Timeseries Record Type`=? WHERE `Timeseries Record Key`=?";


                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                                   $sales_data['invoices'],
                                   $sales_data['refunds'],
                                   $sales_data['invoiced_amount'],
                                   $sales_data['refunded_amount'],
                                   $sales_data['profit'],
                                   'Data',
                                   $timeseries_record_key
                               ]);

                $total_records = $stmt->rowCount();


                if ($total_records or $date == date('Y-m-d')) {
                    $timeseries->fast_update(array('Timeseries Updated' => gmdate('Y-m-d H:i:s')));
                }
            } else {
                $sql = sprintf(
                    'select `Timeseries Record Key` FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`=%s ',
                    $timeseries->id,
                    prepare_mysql($_date)
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $sql = sprintf(
                            'DELETE FROM `Timeseries Record Drill Down` WHERE `Timeseries Record Drill Down Timeseries Record Key`=%d  ',
                            $row['Timeseries Record Key']
                        );
                        //print $sql;
                        $this->db->exec($sql);
                    }
                }


                $sql = sprintf(
                    'DELETE FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`=%s ',
                    $timeseries->id,
                    prepare_mysql($_date)
                );


                $update_sql = $this->db->prepare($sql);
                $update_sql->execute();
                if ($update_sql->rowCount()) {
                    $timeseries->fast_update(array('Timeseries Updated' => gmdate('Y-m-d H:i:s')));
                }
            }
            if ($fork_key) {
                $skip_every = 1;
                if ($index % $skip_every == 0) {
                    $sql = sprintf(
                        "UPDATE `Fork Dimension` SET `Fork Operations Done`=%d  WHERE `Fork Key`=%d ",
                        $index,
                        $fork_key
                    );
                    $this->db->exec($sql);
                }
            }

            $date = gmdate('Y-m-d H:i:s');
            $sql  = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';
            $this->db->prepare($sql)->execute([
                                                  $date,
                                                  $date,
                                                  'timeseries_stats',
                                                  $timeseries->id,
                                                  $date,

                                              ]);
        }

        if ($fork_key) {
            $sql = sprintf(
                "UPDATE `Fork Dimension` SET `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=%d,`Fork Result`=%d WHERE `Fork Key`=%d ",
                $index,
                $timeseries->id,
                $fork_key
            );

            $this->db->exec($sql);
        }
    }

    function unsubscribe($note)
    {
        $this->fast_update(array(
                               'Customer Send Newsletter'      => 'No',
                               'Customer Send Email Marketing' => 'No',
                               'Customer Send Basket Emails'   => 'No'
                           ));

        /** @var $store \Store */
        $store = get_object('Store', $this->data['Customer Store Key']);
        $store->update_customers_email_marketing_data();

        $history_data = array(
            'History Abstract' => $note,
            'History Details'  => '',
            'Action'           => 'edited'
        );

        $this->add_subject_history(
            $history_data,
            true,
            'No',
            'Changes',
            $this->get_object_name(),
            $this->id
        );
    }

    /**
     * @param $data
     *
     * @return bool|\Customer_Client
     */
    public function create_client($data, $can_insert_with_no_code = false)
    {
        global $account;

        include_once 'class.Customer_Client.php';

        $this->new_client = false;

        $data['editor'] = $this->editor;


        if (!$can_insert_with_no_code and empty($data['Customer Client Code'])) {
            $this->error      = true;
            $this->msg        = _("Code missing");
            $this->error_code = 'client_code_missing';
            $this->metadata   = '';

            return false;
        }


        $data['Customer Client Store Key']     = $this->data['Customer Store Key'];
        $data['Customer Client Customer Key']  = $this->id;
        $data['Customer Client Currency Code'] = $this->metadata('cur');


        $address_fields = array(
            'Address Recipient'            => $data['Customer Client Main Contact Name'],
            'Address Organization'         => $data['Customer Client Company Name'],
            'Address Line 1'               => '',
            'Address Line 2'               => '',
            'Address Sorting Code'         => '',
            'Address Postal Code'          => '',
            'Address Dependent Locality'   => '',
            'Address Locality'             => '',
            'Address Administrative Area'  => '',
            'Address Country 2 Alpha Code' => $data['Customer Client Contact Address country'],

        );
        unset($data['Customer Client Contact Address country']);

        if (isset($data['Customer Client Contact Address addressLine1'])) {
            $address_fields['Address Line 1'] = $data['Customer Client Contact Address addressLine1'];
            unset($data['Customer Client Contact Address addressLine1']);
        }
        if (isset($data['Customer Client Contact Address addressLine2'])) {
            $address_fields['Address Line 2'] = $data['Customer Client Contact Address addressLine2'];
            unset($data['Customer Client Contact Address addressLine2']);
        }
        if (isset($data['Customer Client Contact Address sortingCode'])) {
            $address_fields['Address Sorting Code'] = $data['Customer Client Contact Address sortingCode'];
            unset($data['Customer Client Contact Address sortingCode']);
        }
        if (isset($data['Customer Client Contact Address postalCode'])) {
            $address_fields['Address Postal Code'] = $data['Customer Client Contact Address postalCode'];
            unset($data['Customer Client Contact Address postalCode']);
        }

        if (isset($data['Customer Client Contact Address dependentLocality'])) {
            $address_fields['Address Dependent Locality'] = $data['Customer Client Contact Address dependentLocality'];
            unset($data['Customer Client Contact Address dependentLocality']);
        }

        if (isset($data['Customer Client Contact Address locality'])) {
            $address_fields['Address Locality'] = $data['Customer Client Contact Address locality'];
            unset($data['Customer Client Contact Address locality']);
        }

        if (isset($data['Customer Client Contact Address administrativeArea'])) {
            $address_fields['Address Administrative Area'] = $data['Customer Client Contact Address administrativeArea'];
            unset($data['Customer Client Contact Address administrativeArea']);
        }


        $client = new Customer_Client('new', $data, $address_fields);


        if ($client->id) {
            $this->new_client_msg = $client->msg;

            if ($client->new) {
                $this->new_client = true;

                include_once 'utils/new_fork.php';


                new_housekeeping_fork(
                    'au_housekeeping',
                    array(
                        'type'                => 'customer_client_created',
                        'customer_key'        => $this->id,
                        'customer_client_key' => $client->id,

                        'editor' => $this->editor
                    ),
                    $account->get('Account Code')
                );
            } else {
                $this->error = true;
                $this->msg   = $client->msg;
            }

            return $client;
        } else {
            $this->error = true;
            $this->msg   = $client->msg;
        }

        return false;
    }

    function metadata($key)
    {
        return ($this->metadata[$key] ?? '');
    }


}





