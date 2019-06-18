<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 May 2016 at 10:56:11 GMT+7, Bandung, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'class.Subject.php';


class SubjectSupplier extends Subject {

    function create_order($_data) {


        $account = get_object('Account', 1);


        $staff     = get_object('Staff', $_data['user']->get('User Parent Key'));
        $warehouse = get_object('Warehouse', $_data['warehouse_key']);

        $order_data = array(
            'Purchase Order Parent'              => $this->table_name,
            'Purchase Order Parent Key'          => $this->id,
            'Purchase Order Parent Name'         => $this->get('Name'),
            'Purchase Order Parent Code'         => $this->get('Code'),
            'Purchase Order Parent Contact Name' => $this->get('Main Contact Name'),
            'Purchase Order Parent Email'        => $this->get('Main Plain Email'),
            'Purchase Order Parent Telephone'    => $this->get('Preferred Contact Number Formatted Number'),
            'Purchase Order Parent Address'      => $this->get('Contact Address Formatted'),

            'Purchase Order Currency Code'  => $this->get('Default Currency Code'),
            'Purchase Order Incoterm'       => $this->get($this->table_name.' Default Incoterm'),
            'Purchase Order Port of Import' => $this->get('Default Port of Import'),
            'Purchase Order Port of Export' => $this->get('Default Port of Export'),


            'Purchase Order Warehouse Key'            => $warehouse->data['Warehouse Key'],
            'Purchase Order Warehouse Code'           => $warehouse->data['Warehouse Code'],
            'Purchase Order Warehouse Name'           => $warehouse->data['Warehouse Name'],
            'Purchase Order Warehouse Address'        => $warehouse->data['Warehouse Address'],
            'Purchase Order Warehouse Company Name'   => $warehouse->data['Warehouse Company Name'],
            'Purchase Order Warehouse Company Number' => $warehouse->data['Warehouse Company Number'],
            'Purchase Order Warehouse VAT Number'     => $warehouse->data['Warehouse VAT Number'],
            'Purchase Order Warehouse Telephone'      => $warehouse->data['Warehouse Telephone'],
            'Purchase Order Warehouse Email'          => $warehouse->data['Warehouse Email'],
            'Purchase Order Account Number'           => $this->get('Account Number'),

            'Purchase Order Terms and Conditions' => $this->get('Default PO Terms and Conditions'),
            'Purchase Order Main Buyer Key'       => $staff->id,
            'Purchase Order Main Buyer Name'      => $staff->get('Staff Name'),

            'Purchase Order Metadata' => json_encode(
                array(
                    'payment_terms' => $this->metadata('payment_terms')
                )
            ),
            'editor'                  => $this->editor,


        );


        if ($this->get('Show Warehouse TC in PO') == 'Yes') {

            if ($order_data['Purchase Order Terms and Conditions'] != '') {
                $order_data['Purchase Order Terms and Conditions'] .= '<br><br>';
            }
            $order_data['Purchase Order Terms and Conditions'] .= $account->get('Account Suppliers Terms and Conditions');
        }


        //   print_r($order_data);

        $order = new PurchaseOrder('new', $order_data);


        if ($order->error) {
            $this->error = true;
            $this->msg   = $order->msg;

        }


        return $order;

    }


    function update_purchase_orders() {
        $number_purchase_orders      = 0;
        $number_open_purchase_orders = 0;
        $number_delivery_notes       = 0;
        $number_invoices             = 0;

        $sql = sprintf(
            "SELECT count(*) AS num FROM `Purchase Order Dimension` WHERE `Purchase Order Parent`=%s AND `Purchase Order Parent Key`=%d", prepare_mysql($this->table_name), $this->id
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_purchase_orders = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "SELECT count(*) AS num FROM `Purchase Order Dimension` WHERE `Purchase Order Parent`=%s AND  `Purchase Order Parent Key`=%d AND `Purchase Order State` NOT IN ('Done','Cancelled')", prepare_mysql($this->table_name), $this->id
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_open_purchase_orders = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "SELECT count(*) AS num FROM `Supplier Delivery Dimension` WHERE `Supplier Delivery Parent`=%s AND  `Supplier Delivery Parent Key`=%d", prepare_mysql($this->table_name), $this->id
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_delivery_notes = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "UPDATE `%s Dimension` SET `%s Number Purchase Orders`=%d,`%s Number Open Purchase Orders`=%d ,`%s Number Deliveries`=%d,`%s Number Invoices`=%d  WHERE `%s Key`=%d", $this->table_name, $this->table_name, $number_purchase_orders, $this->table_name,
            $number_open_purchase_orders, $this->table_name, $number_delivery_notes, $this->table_name, $number_invoices,

            $this->table_name, $this->id
        );


        $this->db->exec($sql);

    }

    function get_users($scope = 'keys') {

        if ($scope == 'objects') {
            include_once 'class.User.php';
        }


        $users = array();
        $sql   = sprintf(
            "SELECT `User Key` FROM `User Dimension` whereUser Type`=%s and `USER Parent KEY`=%d  ", prepare_mysql($this->table_name), $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($scope == 'objects') {

                    $users[$row['User Key']] = new User($row['User Key']);

                } else {
                    $users[$row['User Key']] = $row['User Key'];
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $users;

    }

    function create_user($data) {


        if (isset($this->data[$this->table_name.' User Key']) and $this->data[$this->table_name.' User Key']) {
            $this->create_user_error = true;
            if ($this->table_name == 'Supplier') {
                $this->create_user_msg = _(
                    'Supplier has already a system user'
                );
            } else {
                $this->create_user_msg = _('Agent has already a system user');
            }

            $this->user = false;

            return false;
        }


        $data['editor'] = $this->editor;

        if (!array_key_exists('User Handle', $data) or $data['User Handle'] == '') {
            $this->create_user_error = true;
            $this->create_user_msg   = _('User login must be provided');
            $this->user              = false;

            return false;

        }

        if (!array_key_exists('User Password', $data) or $data['User Password'] == '') {
            include_once 'utils/password_functions.php';
            $data['User Password'] = hash('sha256', generatePassword(8, 3));
        }

        $data['User Type'] = $this->table_name;


        $data['User Parent Key'] = $this->id;
        $data['User Alias']      = $this->get('Name');
        $user                    = new User('find', $data, 'create');
        $this->get_user_data();
        $this->create_user_error = $user->error;
        $this->create_user_msg   = $user->msg;
        $this->user              = $user;

        return $user;


    }

    function get_user_data() {

        $sql = sprintf(
            'SELECT * FROM `User Dimension` WHERE `User Type`=%s AND `User Parent Key`=%d ', prepare_mysql($this->table_name), $this->id
        );
        if ($row = $this->db->query($sql)->fetch()) {

            foreach ($row as $key => $value) {
                $this->data['Supplier '.$key] = $value;
            }
        }


    }

    function get_subject_supplier_common($key) {

        global $account;

        if (!$this->id) {
            return array(
                false,
                false
            );
        };

        list($got, $result) = $this->get_subject_common($key);
        if ($got) {
            return array(
                true,
                $result
            );
        }


        switch ($key) {


            case 'Default Incoterm':

                if ($this->get($this->table_name.' '.$key) == '' or $this->get($this->table_name.' '.$key) == 'No') {
                    return array(
                        true,
                        '<span class="discreet italic">'._('Not set').'</span>'
                    );
                } else {
                    return array(
                        true,
                        $this->get($this->table_name.' '.$key)
                    );
                }

                brea;

            case 'payment terms':

                return array(
                    true,
                    $this->metadata(preg_replace('/\s/', '_', $key))
                );

            case 'Supplier Number Todo Parts':
            case 'Agent Number Todo Parts':

                if ($this->table_name == 'Supplier Production') {
                    $table_name = 'Supplier';
                } else {
                    $table_name = $this->table_name;
                }

                return array(
                    true,
                    $this->data[$table_name.' Number Critical Parts'] + $this->data[$table_name.' Number Out Of Stock Parts']
                );
                breaak;
            case('Valid From'):
            case('Valid To'):
                if ($this->get($this->table_name.' '.$key) == '') {
                    return array(
                        true,
                        ''
                    );
                } else {
                    return array(
                        true,
                        strftime(
                            "%a, %e %b %y", strtotime(
                                              $this->get($this->table_name.' '.$key).' +0:00'
                                          )
                        )
                    );
                }
                break;
            case ('Default Currency'):

                if ($this->data[$this->table_name.' Default Currency Code'] != '') {


                    $options_currencies = array();
                    $sql                = sprintf(
                        "SELECT `Currency Code`,`Currency Name`,`Currency Symbol` FROM kbase.`Currency Dimension` WHERE `Currency Code`=%s", prepare_mysql(
                                                                                                                                               $this->data[$this->table_name.' Default Currency Code']
                                                                                                                                           )
                    );


                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            return array(
                                true,
                                sprintf(
                                    "%s (%s)", $row['Currency Name'], $row['Currency Code']
                                )
                            );
                        } else {
                            return array(
                                true,
                                $this->data[$this->table_name.' Default Currency Code']
                            );
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }
                } else {
                    return array(
                        true,
                        ''
                    );
                }

                break;
            case 'Average Delivery Days':
                if ($this->data[$this->table_name.' Average Delivery Days'] == '') {
                    return array(
                        true,
                        ''
                    );
                }

                return array(
                    true,
                    number(
                        $this->data[$this->table_name.' Average Delivery Days']
                    )
                );
                break;
            case 'Delivery Time':


                include_once 'utils/natural_language.php';
                if ($this->get($this->table_name.' Average Delivery Days') == '') {
                    return array(
                        true,
                        '<span class="italic very_discreet">'._('Unknown').'</span>'
                    );
                } else {
                    return array(
                        true,
                        seconds_to_natural_string(
                            24 * 3600 * $this->get(
                                $this->table_name.' Average Delivery Days'
                            )
                        )
                    );
                }
                break;
            case 'Average Production Days':
                if ($this->data[$this->table_name.' Average Production Days'] == '') {
                    return array(
                        true,
                        ''
                    );
                }

                return array(
                    true,
                    number(
                        $this->data[$this->table_name.' Average Production Days']
                    )
                );
                break;
            case 'Production Time':


                include_once 'utils/natural_language.php';
                if ($this->get($this->table_name.' Average Production Days') == '') {
                    return array(
                        true,
                        '<span class="italic very_discreet">'._('Unknown').'</span>'
                    );
                } else {
                    return array(
                        true,
                        seconds_to_natural_string(
                            24 * 3600 * $this->get(
                                $this->table_name.' Average Production Days'
                            )
                        )
                    );
                }
                break;

            case 'Products Origin Country Code':
                if ($this->get(
                    $this->table_name.' Products Origin Country Code'
                )) {
                    include_once 'class.Country.php';
                    $country = new Country(
                        'code', $this->data[$this->table_name.' Products Origin Country Code']
                    );

                    return array(
                        true,
                        _($country->get('Country Name')).' ('.$country->get('Country Code').')'
                    );
                } else {
                    return array(
                        true,
                        ''
                    );
                }

                break;


            case('Formatted ID'):
            case("ID"):
                return array(
                    true,
                    $this->get_formatted_id()
                );
            case('Stock Value'):

                if (!is_numeric(
                    $this->data[$this->table_name.' Stock Value']
                )) {
                    return array(
                        true,
                        _('Unknown')
                    );
                } else {
                    return array(
                        true,
                        money(
                            $this->data[$this->table_name.' Stock Value']
                        )
                    );
                }
                break;

            case('Parent Skip Inputting'):
            case('Parent Skip Mark as Dispatched'):
            case('Parent Skip Mark as Received'):
            case('Parent Skip Checking'):
            case('Parent Automatic Placement Location'):

                $field = preg_replace('/^Parent/', $this->table_name, $key);

                return array(
                    true,
                    $this->data[$field]
                );
                break;
            case 'Acc To Day Updated':
            case 'Acc Ongoing Intervals Updated':
            case 'Acc Previous Intervals Updated':

                if ($this->data[$this->table_name.' '.$key] == '') {
                    $value = '';
                } else {

                    $value = strftime("%a %e %b %Y %H:%M:%S %Z", strtotime($this->data[$this->table_name.' '.$key].' +0:00'));

                }

                return array(
                    true,
                    $value
                );
                break;


            default;

                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit)$/', $key
                )) {

                    $field = $this->table_name.' '.$key;

                    return array(
                        true,
                        money(
                            $this->data[$field], $account->get('Account Currency')
                        )
                    );
                }
                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Minify$/', $key
                )) {

                    $field = $this->table_name.' '.preg_replace(
                            '/ Minify$/', '', $key
                        );

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

                    $amount = money(
                            $_amount, $account->get('Account Currency'), $locale = false, $fraction_digits
                        ).$suffix;

                    return array(
                        true,
                        $amount
                    );
                }
                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Soft Minify$/', $key
                )) {

                    $field = $this->table_name.' '.preg_replace(
                            '/ Soft Minify$/', '', $key
                        );


                    $suffix          = '';
                    $fraction_digits = 'NO_FRACTION_DIGITS';
                    $_amount         = $this->data[$field];

                    $amount = money(
                            $_amount, $account->get('Account Currency'), $locale = false, $fraction_digits
                        ).$suffix;

                    return array(
                        true,
                        $amount
                    );
                }

                if (preg_match(
                        '/^(Last|Yesterday|Total|1|10|6|3|Year To|Quarter To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Dispatched|Broken|Acquired)$/', $key
                    ) or $key == 'Current Stock') {

                    $field = $this->table_name.' '.$key;

                    return array(
                        true,
                        number($this->data[$field])
                    );
                }


                if (preg_match(
                        '/^(Last|Yesterday|Total|1|10|6|3|2|4|Year To|Quarter To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Dispatched|Broken|Acquired) Minify$/', $key
                    ) or $key == 'Current Stock') {

                    $field = $this->table_name.' '.preg_replace(
                            '/ Minify$/', '', $key
                        );

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

                    return array(
                        true,
                        number($_number, $fraction_digits).$suffix
                    );
                }
                if (preg_match(
                        '/^(Last|Yesterday|Total|1|10|6|3|2|4|Year To|Quarter To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Dispatched|Broken|Acquired) Soft Minify$/', $key
                    ) or $key == 'Current Stock') {

                    $field = $this->table_name.' '.preg_replace(
                            '/ Soft Minify$/', '', $key
                        );

                    $suffix          = '';
                    $fraction_digits = 0;
                    $_number         = $this->data[$field];

                    return array(
                        true,
                        number($_number, $fraction_digits).$suffix
                    );
                }


        }


        return array(
            false,
            false
        );

    }


    function update_sales_from_invoices($interval, $this_year = true, $last_year = true) {

        include_once 'utils/date_functions.php';
        list($db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb) = calculate_interval_dates($this->db, $interval);

        if ($this_year) {

            $sales_data = $this->get_sales_data($from_date, $to_date);


            $data_to_update = array(
                $this->table_name." $db_interval Acc Customers"        => $sales_data['customers'],
                $this->table_name." $db_interval Acc Repeat Customers" => $sales_data['repeat_customers'],
                $this->table_name." $db_interval Acc Deliveries"       => $sales_data['deliveries'],
                $this->table_name." $db_interval Acc Profit"           => $sales_data['profit'],
                $this->table_name." $db_interval Acc Invoiced Amount"  => $sales_data['invoiced_amount'],
                $this->table_name." $db_interval Acc Required"         => $sales_data['required'],
                $this->table_name." $db_interval Acc Dispatched"       => $sales_data['dispatched'],
                $this->table_name." $db_interval Acc Keeping Days"     => $sales_data['keep_days'],
                $this->table_name." $db_interval Acc With Stock Days"  => $sales_data['with_stock_days'],
            );

            // print_r($data_to_update);

            $this->fast_update($data_to_update, $this->table_name.' Data');
        }

        if ($from_date_1yb and $last_year) {


            $sales_data = $this->get_sales_data($from_date_1yb, $to_date_1yb);


            $data_to_update = array(

                $this->table_name." $db_interval Acc 1YB Customers"        => $sales_data['customers'],
                $this->table_name." $db_interval Acc 1YB Repeat Customers" => $sales_data['repeat_customers'],
                $this->table_name." $db_interval Acc 1YB Deliveries"       => $sales_data['deliveries'],
                $this->table_name." $db_interval Acc 1YB Profit"           => $sales_data['profit'],
                $this->table_name." $db_interval Acc 1YB Invoiced Amount"  => $sales_data['invoiced_amount'],
                $this->table_name." $db_interval Acc 1YB Required"         => $sales_data['required'],
                $this->table_name." $db_interval Acc 1YB Dispatched"       => $sales_data['dispatched'],
                $this->table_name." $db_interval Acc 1YB Keeping Days"     => $sales_data['keep_days'],
                $this->table_name." $db_interval Acc 1YB With Stock Days"  => $sales_data['with_stock_days'],

            );
            $this->fast_update($data_to_update, $this->table_name.' Data');


        }


        if (in_array(
            $db_interval, [
                            'Total',
                            'Year To Date',
                            'Quarter To Date',
                            'Week To Date',
                            'Month To Date',
                            'Today'
                        ]
        )) {

            $this->fast_update([$this->table_name.' Acc To Day Updated' => gmdate('Y-m-d H:i:s')]);

        } elseif (in_array(
            $db_interval, [
                            '1 Year',
                            '1 Month',
                            '1 Week',
                            '1 Quarter'
                        ]
        )) {

            $this->fast_update([$this->table_name.' Acc Ongoing Intervals Updated' => gmdate('Y-m-d H:i:s')]);
        } elseif (in_array(
            $db_interval, [
                            'Last Month',
                            'Last Week',
                            'Yesterday',
                            'Last Year'
                        ]
        )) {

            $this->update([$this->table_name.' Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')], 'no_history');
        }


    }

    function get_sales_data($from_date, $to_date, $part_skos = false) {

        $sales_data = array(
            'invoiced_amount'     => 0,
            'purchased_amount'    => 0,
            'profit'              => 0,
            'required'            => 0,
            'dispatched'          => 0,
            'deliveries'          => 0,
            'supplier_deliveries' => 0,
            'customers'           => 0,
            'repeat_customers'    => 0,
            'keep_days'           => 0,
            'with_stock_days'     => 0,

        );

        if (!$part_skos) {
            $part_skos = $this->get_part_skus();
            $method    = 'like_a_supplier';
        } else {
            $method = 'like_a_part';
        }


        if ($part_skos != '') {

            if ($from_date == '' and $to_date == '') {
                $sales_data['repeat_customers'] = $this->get_customers_total_data($part_skos);
            }


            $sql = sprintf(
                "SELECT count(DISTINCT `Delivery Note Customer Key`) AS customers, count( DISTINCT ITF.`Delivery Note Key`) AS deliveries, round(ifnull(sum(`Amount In`),0),2) AS invoiced_amount,round(ifnull(sum(`Amount In`+`Inventory Transaction Amount`),0),2) AS profit,round(ifnull(sum(`Inventory Transaction Quantity`),0),1) AS dispatched,round(ifnull(sum(`Required`),0),1) AS required 
                FROM `Inventory Transaction Fact` ITF  LEFT JOIN `Delivery Note Dimension` DN ON (DN.`Delivery Note Key`=ITF.`Delivery Note Key`) WHERE `Inventory Transaction Type` LIKE 'Sale' AND `Part SKU` IN (%s) %s %s", $part_skos,
                ($from_date ? sprintf('and  `Date`>=%s', prepare_mysql($from_date)) : ''), ($to_date ? sprintf('and `Date`<%s', prepare_mysql($to_date)) : '')
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $sales_data['customers']       = $row['customers'];
                    $sales_data['invoiced_amount'] = $row['invoiced_amount'];
                    $sales_data['profit']          = $row['profit'];
                    $sales_data['dispatched']      = -1.0 * $row['dispatched'];
                    $sales_data['required']        = $row['required'];
                    $sales_data['deliveries']      = $row['deliveries'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


        }


        if ($method == 'like_a_supplier') {

            $sql = sprintf(
                'SELECT ifnull(sum(`Supplier Delivery Total Amount`*`Supplier Delivery Currency Exchange`),0) AS invoiced_amount, count(*) AS supplier_deliveries FROM `Supplier Delivery Dimension` WHERE `Supplier Delivery State`="Placed"  AND `Supplier Delivery Parent`=%s AND `Supplier Delivery Parent Key`=%d  %s %s ',
                prepare_mysql($this->table_name), $this->id, ($from_date ? sprintf('and  `Supplier Delivery Placed Date`>=%s', prepare_mysql($from_date)) : ''), ($to_date ? sprintf('and `Supplier Delivery Placed Date`<%s', prepare_mysql($to_date)) : '')

            );

            // print "$sql\n";


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {


                    $sales_data['supplier_deliveries'] = $row['supplier_deliveries'];
                    $sales_data['purchased_amount']    = $row['invoiced_amount'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }
        } else {


        }

        return $sales_data;

    }

    function get_customers_total_data($part_skos) {

        $repeat_customers = 0;


        $sql = sprintf(
            'SELECT count(`Customer Part Customer Key`) AS num  FROM `Customer Part Bridge` WHERE `Customer Part Delivery Notes`>1 AND `Customer Part Part SKU` IN (%s)    ', $part_skos
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $repeat_customers = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $repeat_customers;

    }

    function update_previous_years_data() {


        foreach (range(1, 5) as $i) {
            $data_iy_ago = $this->get_sales_data(
                date('Y-01-01 00:00:00', strtotime('-'.$i.' year')), date('Y-01-01 00:00:00', strtotime('-'.($i - 1).' year'))
            );


            $data_to_update = array(
                $this->table_name." $i Year Ago Customers"        => $data_iy_ago['customers'],
                $this->table_name." $i Year Ago Repeat Customers" => $data_iy_ago['repeat_customers'],
                $this->table_name." $i Year Ago Deliveries"       => $data_iy_ago['deliveries'],
                $this->table_name." $i Year Ago Profit"           => $data_iy_ago['profit'],
                $this->table_name." $i Year Ago Invoiced Amount"  => $data_iy_ago['invoiced_amount'],
                $this->table_name." $i Year Ago Required"         => $data_iy_ago['required'],
                $this->table_name." $i Year Ago Dispatched"       => $data_iy_ago['dispatched'],
                $this->table_name." $i Year Ago Keeping Day"      => $data_iy_ago['keep_days'],
                $this->table_name." $i Year Ago With Stock Days"  => $data_iy_ago['with_stock_days'],
            );


            $this->update($data_to_update, 'no_history');
        }

        $this->update([$this->table_name.' Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')], 'no_history');


    }

    function update_previous_quarters_data() {


        include_once 'utils/date_functions.php';


        foreach (range(1, 4) as $i) {
            $dates     = get_previous_quarters_dates($i);
            $dates_1yb = get_previous_quarters_dates($i + 4);


            $sales_data     = $this->get_sales_data($dates['start'], $dates['end']);
            $sales_data_1yb = $this->get_sales_data($dates_1yb['start'], $dates_1yb['end']);

            $data_to_update = array(
                $this->table_name." $i Quarter Ago Customers"        => $sales_data['customers'],
                $this->table_name." $i Quarter Ago Repeat Customers" => $sales_data['repeat_customers'],
                $this->table_name." $i Quarter Ago Deliveries"       => $sales_data['deliveries'],
                $this->table_name." $i Quarter Ago Profit"           => $sales_data['profit'],
                $this->table_name." $i Quarter Ago Invoiced Amount"  => $sales_data['invoiced_amount'],
                $this->table_name." $i Quarter Ago Required"         => $sales_data['required'],
                $this->table_name." $i Quarter Ago Dispatched"       => $sales_data['dispatched'],
                $this->table_name." $i Quarter Ago Keeping Day"      => $sales_data['keep_days'],
                $this->table_name." $i Quarter Ago With Stock Days"  => $sales_data['with_stock_days'],

                $this->table_name." $i Quarter Ago 1YB Customers"        => $sales_data_1yb['customers'],
                $this->table_name." $i Quarter Ago 1YB Repeat Customers" => $sales_data_1yb['repeat_customers'],
                $this->table_name." $i Quarter Ago 1YB Deliveries"       => $sales_data_1yb['deliveries'],
                $this->table_name." $i Quarter Ago 1YB Profit"           => $sales_data_1yb['profit'],
                $this->table_name." $i Quarter Ago 1YB Invoiced Amount"  => $sales_data_1yb['invoiced_amount'],
                $this->table_name." $i Quarter Ago 1YB Required"         => $sales_data_1yb['required'],
                $this->table_name." $i Quarter Ago 1YB Dispatched"       => $sales_data_1yb['dispatched'],
                $this->table_name." $i Quarter Ago 1YB Keeping Day"      => $sales_data_1yb['keep_days'],
                $this->table_name." $i Quarter Ago 1YB With Stock Days"  => $sales_data_1yb['with_stock_days'],
            );
            $this->update($data_to_update, 'no_history');
        }

        $this->update([$this->table_name.' Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')], 'no_history');


    }

    function metadata($key) {
        return (isset($this->metadata[$key]) ? $this->metadata[$key] : '');
    }


}



