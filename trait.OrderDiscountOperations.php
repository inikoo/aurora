<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 August 2017 at 21:43:14 CEST, Tranava, Slovakia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/

trait OrderDiscountOperations {

     function update_discounts_items() {


        $this->allowance = array(
            'Percentage Off' => array(),
            'Get Free'       => array(),
            'Order Get Free' => array()
        );
        $this->deals     = array(
            'Family' => array(
                'Deal'               => false,
                'Terms'              => false,
                'Deal Multiplicity'  => 0,
                'Terms Multiplicity' => 0
            )
        );

        $sql = sprintf(
            'UPDATE `Order Transaction Fact` SET `Order Transaction Total Discount Amount`=0 , `Order Transaction Amount`=`Order Transaction Gross Amount` WHERE `Order Key`=%d  ', $this->id
        );
        $this->db->exec($sql);
        $sql = sprintf(
            "DELETE FROM `Order Transaction Deal Bridge` WHERE `Order Key` =%d AND `Deal Component Key`!=0  ", $this->id
        );
        $this->db->exec($sql);


        $this->get_allowances_from_order_trigger();
        $this->get_allowances_from_department_trigger();
        $this->get_allowances_from_family_trigger();
        $this->get_allowances_from_product_trigger();
        $this->get_allowances_from_customer_trigger();

        $this->apply_items_discounts();

    }
    
    function get_allowances_from_order_trigger($no_items = false) {


        $deals_component_data = array();

        if ($no_items) {
            $where = sprintf(
                "and `Deal Component Allowance Target Type`='No Items'"
            );
        } else {
            $where = sprintf(
                "and `Deal Component Allowance Target Type`='Items'"
            );

        }

        $sql = sprintf(
            "select * from `Deal Component Dimension` left join `Deal Dimension` D on (D.`Deal Key`=`Deal Component Deal Key`)  where `Deal Component Trigger`='Order'  and `Deal Component Status`='Active'  and `Deal Component Store Key`=%d  and `Deal Component Terms Type` not in ('Voucher AND Order Interval','Voucher AND Order Number','Voucher AND Amount','Voucher')  $where",
            $this->data['Order Store Key']
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $deals_component_data[$row['Deal Component Key']] = $row;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        foreach ($deals_component_data as $deal_component_data) {

            $terms_ok                     = false;
            $this->deals['Order']['Deal'] = true;

            if (isset($this->deals['Order']['Deal Multiplicity'])) {
                $this->deals['Order']['Deal Multiplicity']++;
            } else {
                $this->deals['Order']['Deal Multiplicity'] = 1;
            }

            if (isset($this->deals['Order']['Terms Multiplicity'])) {
                $this->deals['Order']['Terms Multiplicity']++;
            } else {
                $this->deals['Order']['Terms Multiplicity'] = 1;
            }


            $this->test_deal_terms($deal_component_data);


        }

        $deals_component_data = array();
        $sql                  = sprintf(
            "select * from `Deal Component Dimension` DC  left join `Deal Dimension` D on (D.`Deal Key`=`Deal Component Deal Key`)  left join `Voucher Order Bridge` V on (V.`Deal Key`=DC.`Deal Component Deal Key`)   where   `Deal Component Status`='Active'  and `Order Key`=%d    $where",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $deals_component_data[$row['Deal Component Key']] = $row;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        foreach ($deals_component_data as $deal_component_data) {

            $terms_ok                     = false;
            $this->deals['Order']['Deal'] = true;

            if (isset($this->deals['Order']['Deal Multiplicity'])) {
                $this->deals['Order']['Deal Multiplicity']++;
            } else {
                $this->deals['Order']['Deal Multiplicity'] = 1;
            }

            if (isset($this->deals['Order']['Terms Multiplicity'])) {
                $this->deals['Order']['Terms Multiplicity']++;
            } else {
                $this->deals['Order']['Terms Multiplicity'] = 1;
            }


            $this->test_deal_terms($deal_component_data);


        }


    }

    function test_deal_terms($deal_component_data) {


        switch ($deal_component_data['Deal Component Terms Type']) {

            case('Order Number'):

                $order_number_term = $deal_component_data['Deal Component Terms'] - 1;


                $sql = sprintf(
                    "SELECT count(*) AS num FROM `Order Dimension` WHERE `Order Customer Key`=%d AND `Order Key`!=%d AND  `Order Current Dispatch State` NOT IN ('Cancelled','Suspended','Cancelled by Customer') ",
                    $this->data['Order Customer Key'], $this->id
                );


                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        if ($row['num'] == $order_number_term) {
                            $this->deals['Order']['Terms'] = true;
                            $this->get_allowances_from_deal_component_data(
                                $deal_component_data
                            );
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                break;

            case('Voucher AND Order Number'):
                $terms = preg_split(
                    '/;/', $deal_component_data['Deal Component Terms']
                );

                $order_number_term = $terms[1] - 1;


                $sql = sprintf(
                    "SELECT count(*) AS num FROM `Voucher Order Bridge` WHERE `Deal Key`=%d AND `Order Key`=%d ", $deal_component_data['Deal Component Deal Key'], $this->id
                );

                $res2 = mysql_query($sql);
                if ($_row = mysql_fetch_assoc($res2)) {


                    if ($_row['num'] > 0) {

                        $sql = sprintf(
                            "SELECT count(*) AS num FROM `Order Dimension` WHERE `Order Customer Key`=%d AND `Order Key`!=%d AND  `Order Current Dispatch State` NOT IN ('Cancelled','Suspended','Cancelled by Customer') ",
                            $this->data['Order Customer Key'], $this->id
                        );

                        $res3 = mysql_query($sql);
                        if ($__row = mysql_fetch_assoc($res3)) {


                            if ($__row['num'] == $order_number_term) {
                                $this->deals['Order']['Terms'] = true;
                                $this->get_allowances_from_deal_component_data(
                                    $deal_component_data
                                );
                            }
                        }


                    }
                }
                break;


            case('Voucher AND Amount'):

                $sql  = sprintf(
                    "SELECT count(*) AS num FROM `Voucher Order Bridge` WHERE `Deal Key`=%d AND `Order Key`=%d ", $deal_component_data['Deal Component Deal Key'], $this->id
                );
                $res2 = mysql_query($sql);
                if ($_row = mysql_fetch_assoc($res2)) {
                    if ($_row['num'] > 0) {
                        $terms       = preg_split(
                            '/;/', $deal_component_data['Deal Component Terms']
                        );
                        $amount_term = $terms[1];
                        $amount_type = $terms[2];

                        if ($this->data[$amount_type] >= $amount_term) {
                            $this->deals['Order']['Terms'] = true;
                            $this->get_allowances_from_deal_component_data(
                                $deal_component_data
                            );
                        }
                    }
                }
                break;


            case('Voucher'):

                $sql = sprintf(
                    "SELECT count(*) AS num FROM `Voucher Order Bridge` WHERE `Deal Key`=%d AND `Order Key`=%d ", $deal_component_data['Deal Component Deal Key'], $this->id
                );

                $res2 = mysql_query($sql);
                if ($_row = mysql_fetch_assoc($res2)) {


                    if ($_row['num'] > 0) {

                        $this->deals['Order']['Terms'] = true;
                        $this->get_allowances_from_deal_component_data(
                            $deal_component_data
                        );

                    }
                }
                break;


            case('Order Interval'):

                $sql = sprintf(
                    "SELECT count(*) AS num FROM `Order Dimension` WHERE `Order Customer Key`=%d AND `Order Key`!=%d AND `Order Dispatched Date`>=%s AND `Order Current Dispatch State`='Dispatched' AND `Order Invoiced`='Yes'",
                    $this->data['Order Customer Key'], $this->id, prepare_mysql(
                        date(
                            'Y-m-d', strtotime(
                                       $this->data['Order Date']." -".$deal_component_data['Deal Component Terms']
                                   )
                        ).' 00:00:00'
                    )
                );
                //print $sql;
                $res2 = mysql_query($sql);
                if ($_row = mysql_fetch_assoc($res2)) {


                    if ($_row['num'] > 0) {
                        $this->deals['Order']['Terms'] = true;
                        // print_r($deal_component_data);
                        $this->get_allowances_from_deal_component_data(
                            $deal_component_data
                        );
                    }
                }
                break;

            case('Amount'):
                $terms       = preg_split(
                    '/;/', $deal_component_data['Deal Component Terms']
                );
                $amount_term = $terms[0];
                $amount_type = $terms[1];

                if ($this->data[$amount_type] >= $amount_term) {
                    $this->get_allowances_from_deal_component_data(
                        $deal_component_data
                    );
                }


                break;
            case('Amount AND Order Interval'):

                $terms         = preg_split(
                    '/;/', $deal_component_data['Deal Component Terms']
                );
                $amount_term   = $terms[0];
                $amount_type   = $terms[1];
                $interval_term = $terms[2];

                $interval_term_ok = false;
                $amount_term_ok   = false;


                $deal_component_data['Deal Component Terms'];
                //print_r($terms);

                if ($this->data[$amount_type] >= $amount_term) {
                    $amount_term_ok = true;

                }


                if ($amount_term_ok) {

                    $sql = sprintf(
                        "SELECT count(*) AS num FROM `Order Dimension` WHERE `Order Customer Key`=%d AND `Order Key`!=%d AND `Order Dispatched Date`>=%s AND `Order Current Dispatch State`='Dispatched' AND `Order Invoiced`='Yes'",
                        $this->data['Order Customer Key'], $this->id, prepare_mysql(
                            date(
                                'Y-m-d', strtotime(
                                           $this->data['Order Date']." -".$interval_term
                                       )
                            ).' 00:00:00'
                        )
                    );
                    // print $deal_component_data['Deal Component Terms'];
                    $res2 = mysql_query($sql);
                    if ($_row = mysql_fetch_assoc($res2)) {


                        if ($_row['num'] > 0) {
                            $interval_term_ok = true;
                        }
                    }
                }


                if ($amount_term_ok and $interval_term_ok) {


                    $this->get_allowances_from_deal_component_data(
                        $deal_component_data
                    );
                }
            case('Amount AND Order Number'):


                $terms = preg_split(
                    '/;/', $deal_component_data['Deal Component Terms']
                );


                $amount_term = $terms[0];
                $amount_type = $terms[1];

                $order_number_term = $terms[2] - 1;

                $order_number_term_ok = false;
                $amount_term_ok       = false;

                if ($this->data[$amount_type] >= $amount_term) {
                    $amount_term_ok = true;

                }


                if ($amount_term_ok) {


                    $sql = sprintf(
                        "SELECT count(*) AS num FROM `Order Dimension` WHERE `Order Customer Key`=%d AND `Order Key`!=%d AND  `Order Current Dispatch State` NOT IN ('Cancelled','Suspended','Cancelled by Customer') ",
                        $this->data['Order Customer Key'], $this->id
                    );

                    $res2 = mysql_query($sql);
                    if ($_row = mysql_fetch_assoc($res2)) {


                        if ($_row['num'] == $order_number_term) {
                            $order_number_term_ok = true;
                        }
                    }
                }


                if ($amount_term_ok and $order_number_term_ok) {


                    $this->get_allowances_from_deal_component_data(
                        $deal_component_data
                    );
                }


                break;


            case('Department Quantity Ordered'):

                $qty_department = 0;
                $sql            = sprintf(
                    'SELECT sum(`Order Quantity`) AS qty  FROM `Order Transaction Fact` OTF WHERE `Order Key`=%d AND `Product Department Key`=%d ', $this->id,
                    $deal_component_data['Deal Component Allowance Target Key']
                );
                $res2           = mysql_query($sql);
                if ($deal_component_data2 = mysql_fetch_array($res2)) {
                    $qty_department = $deal_component_data2['qty'];
                }
                if ($qty_department >= $deal_component_data['Deal Component Terms']) {
                    $terms_ok                           = true;
                    $this->deals['Department']['Terms'] = true;
                    $this->get_allowances_from_deal_component_data(
                        $deal_component_data
                    );
                }


                break;
            case ('Department For Every Quantity Ordered'):


                $sql = sprintf(
                    'SELECT `Order Quantity`AS qty,`Product ID`   FROM `Order Transaction Fact` OTF WHERE `Order Key`=%d AND `Product Department Key`=%d ', $this->id,
                    $deal_component_data['Deal Component Allowance Target Key']
                );

                $res2 = mysql_query($sql);
                while ($deal_component_data2 = mysql_fetch_array($res2)) {
                    $qty = $deal_component_data2['qty'];

                    if ($qty >= $deal_component_data['Deal Component Terms']) {
                        $terms_ok = true;;
                        $this->deals['Department']['Terms'] = true;

                        $deal_component_product_data = $deal_component_data;
                        if ($deal_component_data['Deal Component Terms'] != 0) {
                            $deal_component_product_data['Deal Component Allowance']            = $deal_component_product_data['Deal Component Allowance'] * floor(
                                    $qty / $deal_component_product_data['Deal Component Terms']
                                );
                            $deal_component_product_data['Deal Component Allowance Type']       = 'Get Free';
                            $deal_component_product_data['Deal Component Allowance Target']     = 'Product';
                            $deal_component_product_data['Deal Component Allowance Target Key'] = $deal_component_data2['Product ID'];
                            $this->get_allowances_from_deal_component_data(
                                $deal_component_product_data
                            );

                        }
                    }

                }


                break;


            case('Family Quantity Ordered'):
                $qty_family = 0;
                $sql        = sprintf(
                    'SELECT sum(`Order Quantity`) AS qty  FROM `Order Transaction Fact` OTF WHERE `Order Key`=%d AND `Product Family Key`=%d ', $this->id,
                    $deal_component_data['Deal Component Allowance Target Key']
                );

                $res2 = mysql_query($sql);
                if ($deal_component_data2 = mysql_fetch_array($res2)) {
                    $qty_family = $deal_component_data2['qty'];
                }
                if ($qty_family >= $deal_component_data['Deal Component Terms']) {
                    $terms_ok                       = true;
                    $this->deals['Family']['Terms'] = true;
                    $this->get_allowances_from_deal_component_data(
                        $deal_component_data
                    );
                }


                break;


            case ('Family For Every Quantity Ordered'):
                $sql = sprintf(
                    'SELECT `Order Quantity`AS qty,`Product ID`   FROM `Order Transaction Fact` OTF WHERE `Order Key`=%d AND `Product Family Key`=%d ', $this->id,
                    $deal_component_data['Deal Component Allowance Target Key']
                );


                $res2 = mysql_query($sql);
                while ($deal_component_data2 = mysql_fetch_array($res2)) {
                    $qty = $deal_component_data2['qty'];

                    if ($qty >= $deal_component_data['Deal Component Terms']) {
                        $terms_ok = true;;
                        $this->deals['Family']['Terms'] = true;

                        $deal_component_product_data = $deal_component_data;
                        if ($deal_component_data['Deal Component Terms'] != 0) {
                            $deal_component_product_data['Deal Component Allowance']            = $deal_component_product_data['Deal Component Allowance'] * floor(
                                    $qty / $deal_component_product_data['Deal Component Terms']
                                );
                            $deal_component_product_data['Deal Component Allowance Type']       = 'Get Free';
                            $deal_component_product_data['Deal Component Allowance Target']     = 'Product';
                            $deal_component_product_data['Deal Component Allowance Target Key'] = $deal_component_data2['Product ID'];
                            $this->get_allowances_from_deal_component_data(
                                $deal_component_product_data
                            );

                        }
                    }

                }
                break;

            case ('Family For Every Quantity Any Product Ordered'):
                $sql = sprintf(
                    'SELECT sum(`Order Quantity`) AS qty FROM `Order Transaction Fact` OTF WHERE `Order Key`=%d AND `Product Family Key`=%d ', $this->id,
                    $deal_component_data['Deal Component Allowance Target Key']
                );


                $res2 = mysql_query($sql);
                while ($deal_component_data2 = mysql_fetch_array($res2)) {

                    $qty = $deal_component_data2['qty'];

                    if ($qty >= $deal_component_data['Deal Component Terms']) {
                        $terms_ok = true;;
                        $this->deals['Family']['Terms'] = true;


                        $deal_component_data['Deal Component Allowance'] = $deal_component_data['Deal Component Allowance'] * floor(
                                $qty / $deal_component_data['Deal Component Terms']
                            );

                        $this->get_allowances_from_deal_component_data(
                            $deal_component_data
                        );

                    }

                }
                break;

            case('Product Quantity Ordered'):
                $qty = 0;
                $sql = sprintf(
                    'SELECT sum(`Order Quantity`) AS qty  FROM `Order Transaction Fact` OTF WHERE `Order Key`=%d AND `Product ID`=%d ', $this->id,
                    $deal_component_data['Deal Component Allowance Target Key']
                );

                $res2 = mysql_query($sql);
                if ($deal_component_data2 = mysql_fetch_assoc($res2)) {


                    $qty = $deal_component_data2['qty'];
                }

                if ($qty >= $deal_component_data['Deal Component Terms']) {
                    $terms_ok = true;
                    // print "xxxx\n";
                    $this->get_allowances_from_deal_component_data(
                        $deal_component_data
                    );
                    //  print "----\n";
                }

                break;

            case('Product For Every Quantity Ordered'):

                $qty_product = 0;
                $sql         = sprintf(
                    'SELECT sum(`Order Quantity`) AS qty  FROM `Order Transaction Fact` OTF WHERE `Order Key`=%d AND `Product ID`=%d ', $this->id,
                    $deal_component_data['Deal Component Allowance Target Key']
                );

                $res2 = mysql_query($sql);
                if ($deal_component_data2 = mysql_fetch_array($res2)) {
                    if ($deal_component_data2['qty'] == '') {
                        $qty_product = 0;
                    } else {
                        $qty_product = $deal_component_data2['qty'];
                    }
                }


                //print_r($deal_component_data);

                //print "** $qty_product  -> ".$deal_component_data['Deal Component Terms']."   **\n";


                if ($qty_product > 0 and $qty_product >= $deal_component_data['Deal Component Terms']) {
                    $terms_ok = true;;
                    $this->deals['Family']['Terms'] = true;

                    // i dont underestad below thing maybe it is wrong
                    if ($deal_component_data['Deal Component Terms'] != 0) {
                        $deal_component_data['Deal Component Allowance'] = $deal_component_data['Deal Component Allowance'] * floor(
                                $qty_product / $deal_component_data['Deal Component Terms']
                            );
                    }

                    $this->get_allowances_from_deal_component_data(
                        $deal_component_data
                    );
                }


                break;

            case('Every Order'):
                $terms_ok                         = true;
                $this->deals['Customer']['Terms'] = true;
                $this->get_allowances_from_deal_component_data(
                    $deal_component_data
                );
                break;


        }

    }

    function get_allowances_from_deal_component_data($deal_component_data) {


        if (isset($deal_component_data['Deal Label'])) {

            $deal_info = sprintf(
                "%s: %s, %s", ($deal_component_data['Deal Label'] == '' ? _('Offer') : $deal_component_data['Deal Label']),
                (isset($deal_component_data['Deal XHTML Terms Description Label']) ? $deal_component_data['Deal XHTML Terms Description Label'] : ''),
                $deal_component_data['Deal Component XHTML Allowance Description Label']

            );
        } else {
            $deal_info = 'Discount';
        }

        switch ($deal_component_data['Deal Component Allowance Type']) {

            case('Amount Off'):


                if (isset($this->allowance['No Item Transaction']['Amount Off'])) {
                    if ($this->allowance['No Item Transaction']['Amount Off']['Amount Off'] < $deal_component_data['Deal Component Allowance']) {

                        $this->allowance['No Item Transaction']['Amount Off'] = array(
                            'Amount Off'         => $deal_component_data['Deal Component Allowance'],
                            'Deal Campaign Key'  => $deal_component_data['Deal Component Campaign Key'],
                            'Deal Component Key' => $deal_component_data['Deal Component Key'],
                            'Deal Key'           => $deal_component_data['Deal Component Deal Key'],
                            'Deal Info'          => $deal_info
                        );

                    }
                } else {

                    $this->allowance['No Item Transaction']['Amount Off'] = array(
                        'Amount Off'         => $deal_component_data['Deal Component Allowance'],
                        'Deal Campaign Key'  => $deal_component_data['Deal Component Campaign Key'],
                        'Deal Component Key' => $deal_component_data['Deal Component Key'],
                        'Deal Key'           => $deal_component_data['Deal Component Deal Key'],
                        'Deal Info'          => $deal_info
                    );
                }
                break;
            case('Percentage Off'):
                switch ($deal_component_data['Deal Component Allowance Target']) {

                    case('Order'):
                        $where = sprintf("where `Order Key`=%d", $this->id);
                        break;
                    case('Department'):
                        $where = sprintf(
                            "where `Order Key`=%d and `Product Department Key`=%d", $this->id, $deal_component_data['Deal Component Allowance Target Key']
                        );
                        break;
                    case('Family'):
                        $where = sprintf(
                            "where `Order Key`=%d and `Product Family Key`=%d", $this->id, $deal_component_data['Deal Component Allowance Target Key']
                        );
                        break;
                    case('Product'):
                        $where = sprintf(
                            "where `Order Key`=%d and `Product ID`=%d", $this->id, $deal_component_data['Deal Component Allowance Target Key']
                        );
                        break;
                    default:
                        $where = ' where false';

                }
                $percentage = $deal_component_data['Deal Component Allowance'];


                $sql = sprintf(
                    "select `Product ID`,OTF.`Product Key`,`Order Transaction Fact Key`,`Product Family Key`,`Order Transaction Gross Amount` from  `Order Transaction Fact` OTF  $where"
                );

                //print $sql;

                $res = mysql_query($sql);
                while ($row = mysql_fetch_array($res)) {
                    $otf_key = $row['Order Transaction Fact Key'];
                    if (isset($this->allowance['Percentage Off'][$otf_key])) {
                        if ($this->allowance['Percentage Off'][$otf_key]['Percentage Off'] <= $percentage) {
                            $this->allowance['Percentage Off'][$otf_key]['Percentage Off']     = $percentage;
                            $this->allowance['Percentage Off'][$otf_key]['Deal Campaign Key']  = $deal_component_data['Deal Component Campaign Key'];
                            $this->allowance['Percentage Off'][$otf_key]['Deal Component Key'] = $deal_component_data['Deal Component Key'];
                            $this->allowance['Percentage Off'][$otf_key]['Deal Key']           = $deal_component_data['Deal Component Deal Key'];
                            $this->allowance['Percentage Off'][$otf_key]['Deal Info']          = $deal_info;
                        }
                    } else {
                        $this->allowance['Percentage Off'][$otf_key] = array(
                            'Percentage Off'                 => $percentage,
                            'Deal Campaign Key'              => $deal_component_data['Deal Component Campaign Key'],
                            'Deal Component Key'             => $deal_component_data['Deal Component Key'],
                            'Deal Key'                       => $deal_component_data['Deal Component Deal Key'],
                            'Deal Info'                      => $deal_info,
                            'Product Key'                    => $row['Product Key'],
                            'Product ID'                     => $row['Product ID'],
                            'Product Family Key'             => $row['Product Family Key'],
                            'Order Transaction Gross Amount' => $row['Order Transaction Gross Amount']

                        );
                    }

                }


                //   print_r($this->allowance['Percentage Off']);


                break;


            case('Get Free'):
                switch ($deal_component_data['Deal Component Allowance Target']) {

                    case('Charge'):
                    case('Shipping'):
                        $this->allowance['No Item Transaction'][$deal_component_data['Deal Component Allowance Target']] = array(
                            'Percentage Off'     => 1,
                            'Deal Campaign Key'  => $deal_component_data['Deal Component Campaign Key'],
                            'Deal Component Key' => $deal_component_data['Deal Component Key'],
                            'Deal Key'           => $deal_component_data['Deal Component Deal Key'],
                            'Deal Info'          => $deal_info
                        );

                        break;


                    case('Family'):


                        $family_key         = $deal_component_data['Deal Component Allowance Target Key'];
                        $get_free_allowance = preg_split(
                            '/;/', $deal_component_data['Deal Component Allowance']
                        );

                        $sql = sprintf(
                            "SELECT `Preference Metadata` FROM `Deal Component Customer Preference Bridge`  WHERE `Deal Component Key`=%d AND `Customer Key`=%d ",
                            $deal_component_data['Deal Component Key'], $this->data['Order Customer Key']
                        );
                        $res = mysql_query($sql);
                        if ($row = mysql_fetch_assoc($res)) {

                            $product_code = $row['Preference Metadata'];

                            $sql  = sprintf(
                                "SELECT `Product ID` FROM `Product Dimension` WHERE `Product Store Key`=%s AND `Product Code`=%s AND `Product Main Type`='Sale'", $this->data['Order Store Key'],
                                prepare_mysql($product_code)
                            );
                            $res2 = mysql_query($sql);
                            if ($row2 = mysql_fetch_assoc($res2)) {
                                $product_pid = $row2['Product ID'];

                            } else {
                                $product_pid = 0;
                            }

                            if ($product_pid) {

                                $sql = sprintf(
                                    "SELECT count(*) AS num FROM `Product Dimension` WHERE `Product Main Type`='Sale' AND `Product Family Key`=%d AND `Product ID`=%d", $family_key, $product_pid
                                );

                                $res2 = mysql_query($sql);
                                if ($row2 = mysql_fetch_assoc($res2)) {
                                    if ($row2['num'] == 0) {
                                        $product_pid = $get_free_allowance[1];
                                        $sql         = sprintf(
                                            "DELETE FROM `Deal Component Customer Preference Bridge`  WHERE `Deal Component Key`=%d AND `Customer Key`=%d ", $deal_component_data['Deal Component Key'],
                                            $this->data['Order Customer Key']
                                        );
                                        mysql_query($sql);
                                    }

                                }
                            }
                        } else {

                            $sql  = sprintf(
                                "SELECT `Product ID` FROM `Product Dimension` WHERE `Product Store Key`=%s AND `Product Code`=%s AND `Product Main Type`='Sale'", $this->data['Order Store Key'],
                                prepare_mysql($get_free_allowance[1])
                            );
                            $res2 = mysql_query($sql);
                            if ($row2 = mysql_fetch_assoc($res2)) {
                                $product_pid = $row2['Product ID'];

                            } else {
                                $product_pid = 0;
                            }


                        }


                        $sql = sprintf(
                            "SELECT count(*) AS num FROM `Product Dimension` WHERE `Product Main Type`='Sale' AND `Product Family Key`=%d AND `Product ID`=%d", $family_key, $product_pid
                        );

                        $res2 = mysql_query($sql);
                        if ($row2 = mysql_fetch_assoc($res2)) {
                            if ($row2['num'] == 0) {
                                $product_pid = 0;
                            }


                        }

                        if ($deal_component_data['Deal Component Trigger'] == 'Order') {
                            $allowance_index = 'Order Get Free';
                        } else {
                            $allowance_index = 'Get Free';
                        }

                        if (!$product_pid) {

                            $this->allowance[$allowance_index][$product_pid] = array(
                                'Product ID'         => 0,
                                'Product Key'        => 0,
                                'Product Family Key' => 0,
                                'Get Free'           => 0,
                                'Deal Campaign Key'  => $deal_component_data['Deal Component Campaign Key'],
                                'Deal Component Key' => $deal_component_data['Deal Component Key'],
                                'Deal Key'           => $deal_component_data['Deal Component Deal Key'],
                                'Deal Info'          => $deal_info
                            );
                        } else {

                            if (isset($this->allowance[$allowance_index][$product_pid])) {
                                $this->allowance[$allowance_index][$product_pid]['Get Free'] += $get_free_allowance[0];
                            } else {

                                $product = new Product('id', $product_pid);


                                $this->allowance[$allowance_index][$product_pid] = array(
                                    'Product ID'         => $product->id,
                                    'Product Key'        => $product->historic_id,
                                    'Product Family Key' => $product->data['Product Family Key'],
                                    'Get Free'           => $get_free_allowance[0],
                                    'Deal Campaign Key'  => $deal_component_data['Deal Component Campaign Key'],
                                    'Deal Component Key' => $deal_component_data['Deal Component Key'],
                                    'Deal Key'           => $deal_component_data['Deal Component Deal Key'],
                                    'Deal Info'          => $deal_info
                                );
                            }
                        }


                        break;

                    case('Product'):
                        $product_pid = $deal_component_data['Deal Component Allowance Target Key'];

                        $product = new Product(
                            'id', $deal_component_data['Deal Component Allowance Target Key']
                        );

                        $get_free_allowance = $deal_component_data['Deal Component Allowance'];

                        //print_r($deal_component_data);

                        if (isset($this->allowance['Order Get Free'][$product_pid])) {
                            $this->allowance['Order Get Free'][$product_pid]['Get Free'] += $get_free_allowance;
                        } else {
                            $this->allowance['Order Get Free'][$product_pid] = array(
                                'Product ID'         => $product->id,
                                'Product Key'        => $product->historic_id,
                                'Product Family Key' => $product->data['Product Family Key'],
                                'Get Free'           => $get_free_allowance,
                                'Deal Campaign Key'  => $deal_component_data['Deal Component Campaign Key'],
                                'Deal Component Key' => $deal_component_data['Deal Component Key'],
                                'Deal Key'           => $deal_component_data['Deal Component Deal Key'],
                                'Deal Info'          => $deal_info
                            );
                        }

                        //print_r($this->allowance);
                        break;
                }

                break;


            case('Get Cheapest Free'):
                //print_r($deal_component_data);

                switch ($deal_component_data['Deal Component Allowance Target']) {
                    case 'Department':
                        $where = sprintf(
                            ' and `Product Department Key`=%d', $deal_component_data['Deal Component Allowance Target Key']
                        );
                        break;
                    case 'Family':
                        $where = sprintf(
                            ' and `Product Family Key`=%d', $deal_component_data['Deal Component Allowance Target Key']
                        );
                        break;
                    default:
                        $where = ' and false';
                        break;
                }

                $number_free_outers = $deal_component_data['Deal Component Allowance'];
                $sql                = sprintf(
                    'SELECT `Order Transaction Fact Key`,`Order Quantity`,OTF.`Product Key`,OTF.`Product ID`,`Product Family Key`,`Order Transaction Gross Amount` FROM `Order Transaction Fact` OTF LEFT JOIN `Product History Dimension` P ON (OTF.`Product Key`=P.`Product Key`) WHERE `Order Key`=%d %s ORDER BY `Product History Price`,`Order Quantity`',
                    $this->id, $where
                );
                // print "$sql\n";
                $res = mysql_query($sql);
                while ($row = mysql_fetch_assoc($res)) {
                    // print_r($row);
                    if ($row['Order Quantity'] <= $number_free_outers) {
                        $percentage = 1;

                    } else {
                        $percentage = $number_free_outers / $row['Order Quantity'];
                    }

                    $number_free_outers -= $row['Order Quantity'];


                    $otf_key = $row['Order Transaction Fact Key'];
                    if (isset($this->allowance['Percentage Off'][$otf_key])) {
                        if ($this->allowance['Percentage Off'][$otf_key]['Percentage Off'] <= $percentage) {
                            $this->allowance['Percentage Off'][$otf_key]['Percentage Off']     = $percentage;
                            $this->allowance['Percentage Off'][$otf_key]['Deal Campaign Key']  = $deal_component_data['Deal Component Campaign Key'];
                            $this->allowance['Percentage Off'][$otf_key]['Deal Component Key'] = $deal_component_data['Deal Component Key'];
                            $this->allowance['Percentage Off'][$otf_key]['Deal Key']           = $deal_component_data['Deal Component Deal Key'];
                            $this->allowance['Percentage Off'][$otf_key]['Deal Info']          = $deal_info;
                        }
                    } else {
                        $this->allowance['Percentage Off'][$otf_key] = array(
                            'Percentage Off'                 => $percentage,
                            'Deal Campaign Key'              => $deal_component_data['Deal Component Campaign Key'],
                            'Deal Component Key'             => $deal_component_data['Deal Component Key'],
                            'Deal Key'                       => $deal_component_data['Deal Component Deal Key'],
                            'Deal Info'                      => $deal_info,
                            'Product Key'                    => $row['Product Key'],
                            'Product ID'                     => $row['Product ID'],
                            'Product Family Key'             => $row['Product Family Key'],
                            'Order Transaction Gross Amount' => $row['Order Transaction Gross Amount']

                        );
                    }

                    if ($number_free_outers <= 0) {
                        break;
                    }

                }


                break;

        }


    }

    function get_allowances_from_department_trigger() {

        $sql = sprintf(
            "SELECT `Product Department Key` FROM `Order Transaction Fact` WHERE `Order Key`=%d GROUP BY `Product Department Key`", $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row_lines) {
                $department_key = $row_lines['Product Department Key'];


                $deals_component_data = array();
                $discounts            = 0;

                $sql = sprintf(
                    "SELECT * FROM `Deal Component Dimension`  LEFT JOIN `Deal Dimension` D ON (D.`Deal Key`=`Deal Component Deal Key`)  WHERE `Deal Component Trigger`='Department' AND `Deal Component Trigger Key` =%d  AND `Deal Component Status`='Active' ",
                    $department_key
                );


                if ($result2 = $this->db->query($sql)) {
                    foreach ($result2 as $row) {
                        $deals_component_data[$row['Deal Component Key']] = $row;
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                foreach ($deals_component_data as $deal_component_data) {

                    $terms_ok                          = false;
                    $this->deals['Department']['Deal'] = true;
                    if (isset($this->deals['Department']['Deal Multiplicity'])) {
                        $this->deals['Department']['Deal Multiplicity']++;
                    } else {
                        $this->deals['Department']['Deal Multiplicity'] = 1;
                    }

                    if (isset($this->deals['Department']['Terms Multiplicity'])) {
                        $this->deals['Department']['Terms Multiplicity']++;
                    } else {
                        $this->deals['Department']['Terms Multiplicity'] = 1;
                    }

                    $this->test_deal_terms($deal_component_data);


                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


    }

    function get_allowances_from_family_trigger() {

        $sql = sprintf(
            "SELECT `Product Family Key` FROM `Order Transaction Fact` WHERE `Order Key`=%d GROUP BY `Product Family Key`", $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row_lines) {

                $family_key = $row_lines['Product Family Key'];


                $deals_component_data = array();
                $discounts            = 0;

                $sql = sprintf(
                    "SELECT * FROM `Deal Component Dimension`  LEFT JOIN `Deal Dimension` D ON (D.`Deal Key`=`Deal Component Deal Key`)  WHERE `Deal Component Trigger`='Family' AND `Deal Component Trigger Key` =%d  AND `Deal Component Status`='Active' ",
                    $family_key
                );


                if ($result2 = $this->db->query($sql)) {
                    foreach ($result2 as $row) {
                        $deals_component_data[$row['Deal Component Key']] = $row;

                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                foreach ($deals_component_data as $deal_component_data) {


                    $terms_ok                      = false;
                    $this->deals['Family']['Deal'] = true;
                    if (isset($this->deals['Family']['Deal Multiplicity'])) {
                        $this->deals['Family']['Deal Multiplicity']++;
                    } else {
                        $this->deals['Family']['Deal Multiplicity'] = 1;
                    }

                    if (isset($this->deals['Family']['Terms Multiplicity'])) {
                        $this->deals['Family']['Terms Multiplicity']++;
                    } else {
                        $this->deals['Family']['Terms Multiplicity'] = 1;
                    }


                    $this->test_deal_terms($deal_component_data);


                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


    }

    function get_allowances_from_product_trigger() {

        $sql = sprintf(
            "SELECT `Product ID` FROM `Order Transaction Fact` WHERE `Order Key`=%d GROUP BY `Product ID`", $this->id
        );


        if ($result2 = $this->db->query($sql)) {
            foreach ($result2 as $row_lines) {


                $deals_component_data = array();
                $discounts            = 0;

                $sql = sprintf(
                    "SELECT * FROM `Deal Component Dimension`  LEFT JOIN `Deal Dimension` D ON (D.`Deal Key`=`Deal Component Deal Key`)  WHERE `Deal Component Trigger`='Product' AND `Deal Component Trigger Key` =%d  AND `Deal Component Status`='Active' ",
                    $row_lines['Product ID']
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $deals_component_data[$row['Deal Component Key']] = $row;
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                foreach ($deals_component_data as $deal_component_data) {

                    $terms_ok                       = false;
                    $this->deals['Product']['Deal'] = true;
                    if (isset($this->deals['Product']['Deal Multiplicity'])) {
                        $this->deals['Product']['Deal Multiplicity']++;
                    } else {
                        $this->deals['Product']['Deal Multiplicity'] = 1;
                    }

                    if (isset($this->deals['Product']['Terms Multiplicity'])) {
                        $this->deals['Product']['Terms Multiplicity']++;
                    } else {
                        $this->deals['Product']['Terms Multiplicity'] = 1;
                    }

                    $this->test_deal_terms($deal_component_data);


                }

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


    }

    function get_allowances_from_customer_trigger($no_items = false) {


        $deals_component_data = array();
        $discounts            = 0;

        if ($no_items) {
            $where = sprintf(
                "and `Deal Component Allowance Target Type`='No Items'"
            );
        } else {
            $where = sprintf(
                "and `Deal Component Allowance Target Type`='Items'"
            );

        }


        $sql = sprintf(
            "select * from `Deal Component Dimension` where `Deal Component Trigger`='Customer' and `Deal Component Trigger Key` =%d  and `Deal Component Status`='Active' $where",
            $this->data['Order Customer Key']
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $deals_component_data[$row['Deal Component Key']] = $row;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf(
            "select * from `Deal Component Dimension`  left join `Deal Dimension` D on (D.`Deal Key`=`Deal Component Deal Key`)  left join  `List Customer Bridge` on (`List Key`=`Deal Component Trigger Key`) where `Deal Component Trigger`='Customer List' and `Customer Key` =%d  and `Deal Component Status`='Active' $where",
            $this->data['Order Customer Key']
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $deals_component_data[$row['Deal Component Key']] = $row;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        foreach ($deals_component_data as $deal_component_data) {

            $terms_ok                        = false;
            $this->deals['Customer']['Deal'] = true;
            if (isset($this->deals['Customer']['Deal Multiplicity'])) {
                $this->deals['Customer']['Deal Multiplicity']++;
            } else {
                $this->deals['Customer']['Deal Multiplicity'] = 1;
            }

            if (isset($this->deals['Customer']['Terms Multiplicity'])) {
                $this->deals['Customer']['Terms Multiplicity']++;
            } else {
                $this->deals['Customer']['Terms Multiplicity'] = 1;
            }


            $this->test_deal_terms($deal_component_data);


        }


    }

    function apply_items_discounts() {

        //print_r($this->allowance);
        foreach (
            $this->allowance['Percentage Off'] as $otf_key => $allowance_data
        ) {


            $sql = sprintf(
                "INSERT INTO `Order Transaction Deal Bridge` (`Order Transaction Fact Key`,`Order Key`,`Product Key`,`Product ID`,`Product Family Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`,`Bunus Quantity`) VALUES
			(%d,%d,%d,%d,%d,%d,%d,%d,%s,%f,%f,0)", $otf_key, $this->id,

                $allowance_data['Product Key'], $allowance_data['Product ID'], $allowance_data['Product Family Key'], $allowance_data['Deal Campaign Key'], $allowance_data['Deal Key'],
                $allowance_data['Deal Component Key'],

                prepare_mysql($allowance_data['Deal Info']), $allowance_data['Order Transaction Gross Amount'] * $allowance_data['Percentage Off'], $allowance_data['Percentage Off']
            );
            $this->db->exec($sql);
            //print "$sql\n";

        }

        foreach ($this->allowance['Order Get Free'] as $allowance_data) {


            //print_r($allowance_data);
            $sql = sprintf(
                'SELECT `Product Family Key`,`Product ID`,OTF.`Product Key`,`Order Transaction Fact Key`,`Order Transaction Gross Amount` FROM  `Order Transaction Fact` OTF  WHERE `Order Key`=%d AND `Product ID`=%d ',
                $this->id, $allowance_data['Product ID']
            );

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $amount_discount   = 0;
                    $fraction_discount = 0;

                    $sql = sprintf(
                        "INSERT INTO `Order Transaction Deal Bridge` (`Order Transaction Fact Key`,`Order Key`,`Product Key`,`Product ID`,`Product Family Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`,`Bunus Quantity`) VALUES (%d,%d,%d,%d,%d,%d,%d,%d,%s,%f,%f,%d)",
                        $row['Order Transaction Fact Key'], $this->id

                        , $row['Product Key'], $row['Product ID'], $row['Product Family Key'], $allowance_data['Deal Campaign Key'], $allowance_data['Deal Key'], $allowance_data['Deal Component Key']

                        , prepare_mysql($allowance_data['Deal Info']), $amount_discount, $fraction_discount, $allowance_data['Get Free']
                    );
                    $this->db->exec($sql);
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            $res = mysql_query($sql);

        }


        $sql = sprintf(
            "SELECT * FROM `Order Transaction Deal Bridge` WHERE `Order Key`=%d  ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Fraction Discount'] > 0) {
                    $sql = sprintf(
                        'UPDATE `Order Transaction Fact` OTF  SET  `Order Transaction Total Discount Amount`=`Order Transaction Gross Amount`*%f WHERE `Order Transaction Fact Key`=%d ',
                        $row['Fraction Discount'], $row['Order Transaction Fact Key']
                    );
                    //print $sql;
                    $this->db->exec($sql);

                    $sql = sprintf(
                        'UPDATE `Order Transaction Fact` OTF  SET  `Order Transaction Amount`=`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount` WHERE `Order Transaction Fact Key`=%d ',
                        $row['Order Transaction Fact Key']
                    );
                    //print $sql;
                    $this->db->exec($sql);

                }

                if ($row['Bunus Quantity'] > 0) {
                    $sql = sprintf(
                        'UPDATE `Order Transaction Fact` OTF  SET  `Order Bonus Quantity`=%f WHERE `Order Transaction Fact Key`=%d ', $row['Bunus Quantity'], $row['Order Transaction Fact Key']
                    );
                    //print $sql;
                    $this->db->exec($sql);
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


    }

    function update_discounts_no_items($dn_key = false) {


        if ($dn_key) {
            return;
        }

        $this->allowance = array(
            'Percentage Off'      => array(),
            'Get Free'            => array(),
            'Order Get Free'      => array(),
            'Get Same Free'       => array(),
            'Credit'              => array(),
            'No Item Transaction' => array()
        );
        $this->deals     = array(
            'Order' => array(
                'Deal'               => false,
                'Terms'              => false,
                'Deal Multiplicity'  => 0,
                'Terms Multiplicity' => 0
            )

        );

        $this->update(array('Order Deal Amount Off' => 0));

        $sql = sprintf(
            'UPDATE `Order No Product Transaction Fact` SET `Transaction Total Discount Amount`=0 , `Transaction Net Amount`=`Transaction Gross Amount` WHERE `Order Key`=%d  ', $this->id
        );
        $this->db->exec($sql);


        $sql = sprintf(
            "DELETE FROM `Order No Product Transaction Deal Bridge` WHERE `Order Key` =%d AND `Deal Component Key`!=0  ", $this->id
        );
        $this->db->exec($sql);


        $sql = sprintf(
            "SELECT `Bonus Order Transaction Fact Key` FROM `Order Meta Transaction Deal Dimension` WHERE `Order Key` =%d AND `Deal Component Key`!=0  ", $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $sql = sprintf(
                    "DELETE FROM `Order Transaction Fact` WHERE `Order Transaction Fact Key`=%d", $row['Bonus Order Transaction Fact Key']
                );
                $this->db->exec($sql);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf(
            "DELETE FROM `Order Meta Transaction Deal Dimension` WHERE `Order Key` =%d AND `Deal Component Key`!=0  ", $this->id
        );
        $this->db->exec($sql);

        $this->get_allowances_from_order_trigger($no_items = true);
        $this->get_allowances_from_customer_trigger($no_items = true);

        $this->apply_no_items_discounts();

    }

    function apply_no_items_discounts() {
        //print "****\n";
        //print_r($this->allowance);
        foreach (
            $this->allowance['Percentage Off'] as $otf_key => $allowance_data
        ) {


            $sql = sprintf(
                "SELECT `Fraction Discount` FROM `Order Transaction Deal Bridge` WHERE `Order Transaction Fact Key`=%d AND `Fraction Discount`>0", $otf_key
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    if ($row['Fraction Discount'] > $allowance_data['Percentage Off']) {
                        continue;
                    } else {
                        $sql = sprintf(
                            "DELETE FROM `Order Transaction Deal Bridge` WHERE `Order Transaction Fact Key`=%d AND `Fraction Discount`>0", $otf_key
                        );
                        //print $sql;
                        $this->db->exec($sql);
                    }
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            $sql = sprintf(
                "INSERT INTO `Order Transaction Deal Bridge` (`Order Transaction Fact Key`,`Order Key`,`Product Key`,`Product ID`,`Product Family Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`,`Bunus Quantity`) VALUES
			(%d,%d,%d,%d,%d,%d,%d,%d,%s,%f,%f,0)", $otf_key, $this->id,

                $allowance_data['Product Key'], $allowance_data['Product ID'], $allowance_data['Product Family Key'], $allowance_data['Deal Campaign Key'], $allowance_data['Deal Key'],
                $allowance_data['Deal Component Key'],

                prepare_mysql($allowance_data['Deal Info']), $allowance_data['Order Transaction Gross Amount'] * $allowance_data['Percentage Off'], $allowance_data['Percentage Off']
            );


            $this->db->exec($sql);


            $sql = sprintf(
                'UPDATE `Order Transaction Fact`   SET  `Order Transaction Total Discount Amount`=`Order Transaction Gross Amount`*%f WHERE `Order Transaction Fact Key`=%d ',
                $allowance_data['Percentage Off'], $otf_key
            );
            // print $sql;
            $this->db->exec($sql);

            $sql = sprintf(
                'UPDATE `Order Transaction Fact` OTF  SET  `Order Transaction Amount`=`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount` WHERE `Order Transaction Fact Key`=%d ',
                $otf_key
            );

            $this->db->exec($sql);


        }

        //print_r($this->allowance);
        foreach (
            $this->allowance['No Item Transaction'] as $type => $allowance_data
        ) {


            switch ($type) {
                case 'Amount Off':


                    $this->update(
                        array('Order Deal Amount Off' => $allowance_data['Amount Off'])
                    );


                    $this->amount_off_allowance_data = $allowance_data;


                    break;
                case 'Charge':
                    //print_r($allowance_data);

                    if ($type == 'Charge') {
                        $_type = 'Charges';
                    } else {
                        $_type = $type;
                    }

                    $sql = sprintf(
                        'SELECT *,`Order No Product Transaction Fact Key`,`Transaction Net Amount` FROM  `Order No Product Transaction Fact` OTF  WHERE `Order Key`=%d AND `Transaction Type`=%s ',
                        $this->id, prepare_mysql($_type)
                    );

                    $res = mysql_query($sql);
                    while ($row = mysql_fetch_assoc($res)) {
                        //print_r($row);
                        $sql = sprintf(
                            "INSERT INTO `Order No Product Transaction Deal Bridge` (`Order No Product Transaction Fact Key`,`Order Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`)
					VALUES (%d,%d,%d,%d,%d,%s,%f,%f)", $row['Order No Product Transaction Fact Key'], $this->id


                            , $allowance_data['Deal Campaign Key'], $allowance_data['Deal Key'], $allowance_data['Deal Component Key']

                            , prepare_mysql($allowance_data['Deal Info']), $row['Transaction Gross Amount'] * $allowance_data['Percentage Off'], $allowance_data['Percentage Off']
                        );
                        $this->db->exec($sql);
                    }
                    break;
                case 'Shipping':
                    //print_r($allowance_data);

                    if ($type == 'Shipping') {
                        $_type = 'Shipping';
                    } else {
                        $_type = $type;
                    }

                    $sql = sprintf(
                        'SELECT *,`Order No Product Transaction Fact Key`,`Transaction Net Amount` FROM  `Order No Product Transaction Fact` OTF  WHERE `Order Key`=%d AND `Transaction Type`=%s ',
                        $this->id, prepare_mysql($_type)
                    );

                    $res = mysql_query($sql);
                    while ($row = mysql_fetch_assoc($res)) {
                        //print_r($row);
                        $sql = sprintf(
                            "INSERT INTO `Order No Product Transaction Deal Bridge` (`Order No Product Transaction Fact Key`,`Order Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`)
					VALUES (%d,%d,%d,%d,%d,%s,%f,%f)", $row['Order No Product Transaction Fact Key'], $this->id


                            , $allowance_data['Deal Campaign Key'], $allowance_data['Deal Key'], $allowance_data['Deal Component Key']

                            , prepare_mysql($allowance_data['Deal Info']), $row['Transaction Gross Amount'] * $allowance_data['Percentage Off'], $allowance_data['Percentage Off']
                        );
                        $this->db->exec($sql);
                    }
                    break;
            }


        }

        foreach ($this->allowance['Get Free'] as $type => $allowance_data) {
            if (in_array(
                $this->data['Order Current Dispatch State'], array(
                                                               'Ready to Pick',
                                                               'Picking & Packing',
                                                               'Packed',
                                                               'Packed Done',
                                                               'Packing'
                                                           )
            )) {
                $dispatching_state = 'Ready to Pick';
            } else {

                $dispatching_state = 'In Process';
            }

            $payment_state = 'Waiting Payment';


            $data = array(
                'date'                      => gmdate('Y-m-d H:i:s'),
                'Product Key'               => $allowance_data['Product Key'],
                'Metadata'                  => '',
                'qty'                       => 0,
                'bonus qty'                 => $allowance_data['Get Free'],
                'Current Dispatching State' => $dispatching_state,
                'Current Payment State'     => $payment_state
            );

            $this->skip_update_after_individual_transaction = true;

            $transaction_data                               = $this->add_order_transaction_to_delete(
                $data
            );
            $this->skip_update_after_individual_transaction = false;

            $sql = sprintf(
                "INSERT INTO `Order Meta Transaction Deal Dimension` (`Order Meta Transaction Deal Type`,`Order Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,
				`Amount Discount`,`Fraction Discount`,`Bonus Quantity`,
				`Bonus Product Key`,`Bonus Product ID`,`Bonus Product Family Key`,`Bonus Order Transaction Fact Key`
				)
			VALUES (%s,%d, %d,%d,%d,%s,%f,%f,%f,%d,%d,%d,%d)  ", prepare_mysql('Order Get Free'), $this->id


                , $allowance_data['Deal Campaign Key'], $allowance_data['Deal Key'], $allowance_data['Deal Component Key'], prepare_mysql($allowance_data['Deal Info']), 0, 0,
                $allowance_data['Get Free'], $allowance_data['Product Key'], $allowance_data['Product ID'], $allowance_data['Product Family Key'], $transaction_data['otf_key']

            );
            $this->db->exec($sql);


        }

        foreach ($this->allowance['Order Get Free'] as $type => $allowance_data) {


            if (in_array(
                $this->data['Order Current Dispatch State'], array(
                                                               'Ready to Pick',
                                                               'Picking & Packing',
                                                               'Packed',
                                                               'Packed Done',
                                                               'Packing'
                                                           )
            )) {
                $dispatching_state = 'Ready to Pick';
            } else {

                $dispatching_state = 'In Process';
            }

            $payment_state = 'Waiting Payment';


            $data = array(
                'date'                      => gmdate('Y-m-d H:i:s'),
                'Product Key'               => $allowance_data['Product Key'],
                'Metadata'                  => '',
                'qty'                       => 0,
                'bonus qty'                 => $allowance_data['Get Free'],
                'Current Dispatching State' => $dispatching_state,
                'Current Payment State'     => $payment_state
            );

            $this->skip_update_after_individual_transaction = true;

            $transaction_data                               = $this->add_order_transaction_to_delete(
                $data
            );
            $this->skip_update_after_individual_transaction = false;

            $sql = sprintf(
                "SELECT `Order Meta Transaction Deal Key` FROM `Order Meta Transaction Deal Dimension`  WHERE `Order Key`=%d AND `Deal Component Key`=%d", $this->id,
                $allowance_data['Deal Component Key']
            );
            $res = mysql_query($sql);
            if ($row = mysql_fetch_assoc($res)) {
                $sql = sprintf(
                    "UPDATE  `Order Meta Transaction Deal Dimension`  SET `Bonus Quantity`=%f,`Bonus Product Key`=%d,`Bonus Product ID`=%d ,`Bonus Product Family Key`=%d ,`Bonus Order Transaction Fact Key`=%d WHERE `Order Meta Transaction Deal Key`=%d",

                    $allowance_data['Get Free'], $allowance_data['Product Key'], $allowance_data['Product ID'], $allowance_data['Product Family Key'], $transaction_data['otf_key'],
                    $row['Order Meta Transaction Deal Key']
                );
                $this->db->exec($sql);
            } else {

                $sql = sprintf(
                    "INSERT INTO `Order Meta Transaction Deal Dimension` (`Order Meta Transaction Deal Type`,`Order Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,
				`Amount Discount`,`Fraction Discount`,`Bonus Quantity`,
				`Bonus Product Key`,`Bonus Product ID`,`Bonus Product Family Key`,`Bonus Order Transaction Fact Key`
				)
			VALUES (%s,%d, %d,%d,%d,%s,%f,%f,%f,%d,%d,%d,%d)  ", prepare_mysql('Order Get Free'), $this->id


                    , $allowance_data['Deal Campaign Key'], $allowance_data['Deal Key'], $allowance_data['Deal Component Key'], prepare_mysql($allowance_data['Deal Info']), 0, 0,
                    $allowance_data['Get Free'], $allowance_data['Product Key'], $allowance_data['Product ID'], $allowance_data['Product Family Key'], $transaction_data['otf_key']

                );
                $this->db->exec($sql);


            }


        }


        $sql = sprintf(
            "SELECT * FROM `Order No Product Transaction Deal Bridge` B LEFT JOIN `Order No Product Transaction Fact`OTF ON (OTF.`Order No Product Transaction Fact Key`=B.`Order No Product Transaction Fact Key`)  WHERE B.`Order Key`=%d  ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Fraction Discount'] > 0) {
                    $sql = sprintf(
                        'UPDATE `Order No Product Transaction Fact` OTF  SET `Transaction Total Discount Amount`=%.2f ,`Transaction Net Amount`=%.2f,`Transaction Tax Amount`=%.2f  WHERE `Order No Product Transaction Fact Key`=%d ',
                        $row['Amount Discount'], $row['Transaction Net Amount'] * (1 - $row['Fraction Discount']), $row['Transaction Tax Amount'] * (1 - $row['Fraction Discount'])

                        , $row['Order No Product Transaction Fact Key']
                    );
                    // print "$sql\n";
                    $this->db->exec($sql);


                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


    }

    function update_deal_bridge() {
        $sql = sprintf(
            "DELETE FROM `Order Deal Bridge` WHERE `Order Key`=%d", $this->id
        );
        $this->db->exec($sql);

        $sql = sprintf(
            "SELECT `Deal Campaign Key`,`Deal Component Key`, `Deal Key` FROM  `Order Transaction Deal Bridge`  WHERE`Order Key`=%d", $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $sql = sprintf(
                    "INSERT INTO `Order Deal Bridge` VALUES(%d,%d,%d,%d,'Yes','Yes') ON DUPLICATE KEY UPDATE `Used`='Yes'", $this->id, $row['Deal Campaign Key'], $row['Deal Key'],
                    $row['Deal Component Key']
                );
                $this->db->exec($sql);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf(
            "SELECT `Deal Campaign Key`,`Deal Component Key`, `Deal Key` FROM  `Order No Product Transaction Deal Bridge`  WHERE`Order Key`=%d", $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $sql = sprintf(
                    "INSERT INTO `Order Deal Bridge` VALUES(%d,%d,%d,%d,'Yes','Yes') ON DUPLICATE KEY UPDATE `Used`='Yes'", $this->id, $row['Deal Campaign Key'], $row['Deal Key'],
                    $row['Deal Component Key']
                );
                $this->db->exec($sql);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if ($this->amount_off_allowance_data) {


            $sql = sprintf(
                "INSERT INTO `Order Deal Bridge` VALUES(%d,%d,%d,%d,'Yes','Yes') ON DUPLICATE KEY UPDATE `Used`='Yes'", $this->id, $this->amount_off_allowance_data['Deal Campaign Key'],
                $this->amount_off_allowance_data['Deal Key'], $this->amount_off_allowance_data['Deal Component Key']
            );
            $this->db->exec($sql);

        }


    }


    function update_transaction_discount_percentage($otf_key, $percentage) {
        $sql = sprintf(
            'SELECT `Product Key`,`Order Transaction Fact Key`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount` FROM  `Order Transaction Fact`  WHERE `Order Transaction Fact Key`=%d ',
            $otf_key
        );

        $res = mysql_query($sql);
        if ($row = mysql_fetch_array($res)) {


            $discount_amount = round(
                ($row['Order Transaction Gross Amount']) * $percentage / 100, 2
            );


            return $this->update_transaction_discount_amount(
                $otf_key, $discount_amount
            );
        } else {
            $this->error = true;
            $this->msg   = 'otf not found';
        }

    }

    function update_transaction_discount_amount($otf_key, $discount_amount, $deal_campaign_key = 0, $deal_key = 0, $deal_component_key = 0) {

        $deal_info = '';

        $sql = sprintf(
            'SELECT `Order Transaction Amount`,OTF.`Product Family Key`,OTF.`Product ID`,`Product XHTML Short Description`,`Order Quantity`,`Product Key`,`Order Transaction Fact Key`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount` FROM  `Order Transaction Fact` OTF LEFT JOIN `Product Dimension` P ON  (P.`Product ID`=OTF.`Product ID`) WHERE `Order Transaction Fact Key`=%d ',
            $otf_key
        );

        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {


            if ($discount_amount == $row['Order Transaction Total Discount Amount'] or $row['Order Transaction Gross Amount'] == 0) {
                $this->msg   = 'Nothing to Change';
                $return_data = array(
                    'updated'             => true,
                    'otf_key'             => $otf_key,
                    'description'         => $row['Product XHTML Short Description'].' <span class="deal_info">'.$deal_info.'</span>',
                    'discount_percentage' => percentage(
                        $discount_amount, $row['Order Transaction Gross Amount'], $fixed = 1, $error_txt = 'NA', $psign = ''
                    ),
                    'to_charge'           => money(
                        $row['Order Transaction Amount'], $this->data['Order Currency']
                    ),
                    'qty'                 => $row['Order Quantity'],
                    'bonus qty'           => 0
                );

                return $return_data;
            }


            $sql = sprintf(
                "DELETE FROM `Order Transaction Deal Bridge` WHERE `Order Transaction Fact Key` =%d", $otf_key
            );
            mysql_query($sql);


            $sql = sprintf(
                'UPDATE `Order Transaction Fact` OTF SET `Order Transaction Amount`=%.2f, `Order Transaction Total Discount Amount`=%f WHERE `Order Transaction Fact Key`=%d ',
                $row['Order Transaction Gross Amount'] - $discount_amount, $discount_amount, $otf_key
            );
            $this->db->exec($sql);
            //print "$sql\n";

            $deal_info = '';
            if ($discount_amount > 0) {

                $deal_info = sprintf(
                    _('%s off'), percentage(
                                   $discount_amount, $row['Order Transaction Gross Amount']
                               )
                );

                $sql = sprintf(
                    "INSERT INTO `Order Transaction Deal Bridge` (`Order Transaction Fact Key`,`Order Key`,`Product Key`,`Product ID`,`Product Family Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`,`Bunus Quantity`) VALUES (%d,%d,%d,%d,%d,%d,%d,%d,%s,%f,%f,0)",
                    $row['Order Transaction Fact Key'], $this->id, $row['Product Key'], $row['Product ID'], $row['Product Family Key'], $deal_campaign_key, $deal_key, $deal_component_key,
                    prepare_mysql($deal_info, false), $discount_amount, ($discount_amount / $row['Order Transaction Gross Amount'])
                );

                mysql_query($sql);
                $this->updated = true;
            }


            $this->update_totals();
           // $this->apply_payment_from_customer_account();

            return array(
                'updated'             => true,
                'otf_key'             => $otf_key,
                'description'         => $row['Product XHTML Short Description'].' <span class="deal_info">'.$deal_info.'</span>',
                'discount_percentage' => percentage(
                    $discount_amount, $row['Order Transaction Gross Amount'], $fixed = 1, $error_txt = 'NA', $psign = ''
                ),
                'to_charge'           => money(
                    $row['Order Transaction Gross Amount'] - $discount_amount, $this->data['Order Currency']
                ),
                'qty'                 => $row['Order Quantity'],
                'bonus qty'           => 0
            );


        } else {
            $this->error = true;
            $this->msg   = 'otf not found';
        }


    }

    function has_deal_with_bonus() {
        $has_deal_with_bonus = false;
        $sql                 = sprintf(
            "SELECT  count(*) AS num  FROM `Order Meta Transaction Deal Dimension` WHERE  `Order Key`=%d AND `Order Meta Transaction Deal Type`='Order Get Free'  ", $this->id
        );
        //print $sql;
        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {
            $has_deal_with_bonus = $row['num'];
        }

        return $has_deal_with_bonus;

    }

    function get_deal_bonus_items() {
        $deal_bonus_items = array();
        $sql              = sprintf(
            "SELECT  `Deal Info`,`Bonus Product ID`,`Deal Component Allowance Target Key`,B.`Deal Component Key`,`Deal Component Allowance`,`Deal Component Allowance Target`,`Deal Component Allowance Target` FROM `Order Meta Transaction Deal Dimension` B LEFT JOIN `Deal Component Dimension` DC  ON (DC.`Deal Component Key`=B.`Deal Component Key`) WHERE  `Order Key`=%d AND `Order Meta Transaction Deal Type`='Order Get Free'   ",
            $this->id
        );
        //print $sql;

        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            $deal_bonus_items[$row['Deal Component Key']] = array();

            if ($row['Deal Component Allowance Target'] == 'Family') {

                $family_key = $row['Deal Component Allowance Target Key'];
                $sql        = sprintf(
                    "SELECT `Product Family Key`,`Product Current Key`,`Product Code`,`Product ID`,`Product Name` FROM `Product Dimension` WHERE `Product Main Type`='Sale' AND `Product Family Key`=%d ORDER BY `Product Code File As`",
                    $family_key

                );

                $res2  = mysql_query($sql);
                $items = array();
                while ($row2 = mysql_fetch_assoc($res2)) {

                    $items[] = array(

                        'pid'         => $row2['Product ID'],
                        'product_key' => $row2['Product Current Key'],
                        'family_key'  => $row2['Product Family Key'],
                        'code'        => $row2['Product Code'],
                        'name'        => $row2['Product Name'],
                        'selected'    => ($row2['Product ID'] == $row['Bonus Product ID'] ? true : false),
                        'deal_info'   => $row['Deal Info'],

                    );

                }

                $deal_bonus_items[$row['Deal Component Key']] = array(
                    'type'  => 'choose_from_family',
                    'items' => $items
                );

            } elseif ($row['Deal Component Allowance Target'] == 'Product') {

                $product_pid = $row['Deal Component Allowance Target Key'];
                $sql         = sprintf(
                    "SELECT `Product Family Key`,`Product Current Key`,`Product Code`,`Product ID`,`Product Name` FROM `Product Dimension` WHERE  `Product ID`=%d ", $product_pid

                );

                $res2 = mysql_query($sql);
                if ($row2 = mysql_fetch_assoc($res2)) {

                    $deal_bonus_items[$row['Deal Component Key']] = array(
                        'type' => 'product',
                        'item' => array(
                            'pid'         => $row2['Product ID'],
                            'product_key' => $row2['Product Current Key'],
                            'family_key'  => $row2['Product Family Key'],
                            'code'        => $row2['Product Code'],
                            'name'        => $row2['Product Name'],
                            'deal_info'   => $row['Deal Info'],
                        )
                    );

                }


            }


        }

        return $deal_bonus_items;
    }

    function get_discounted_products() {
        $sql = sprintf(
            'SELECT  `Product Key` FROM   `Order Transaction Deal Bridge`   WHERE `Order Key`=%d  GROUP BY `Product Key` ', $this->id
        );
        //print "$sql\n";
        $discounted_products = array();


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $discounted_products[$row['Product Key']] = $row['Product Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $discounted_products;

    }

    function update_deal_bridge_from_assets_deals() {


        $sql       = sprintf(
            "SELECT B.`Deal Key` FROM  `Order Deal Bridge` B  LEFT JOIN `Deal Dimension` D ON (D.`Deal Key`=B.`Deal Key`) WHERE `Deal Trigger` IN ('Department','Family','Product') AND `Order Key`=%d",
            $this->id
        );
        $deal_keys = array();


        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            $deal_keys[] = $row['Deal Key'];
        }
        if (count($deal_keys)) {
            $sql = sprintf(
                "DELETE FROM `Order Deal Bridge` WHERE `Order Key`=%d AND `Deal Key` IN (%s)   ", $this->id, join(',', $deal_keys)
            );
            mysql_query($sql);
        }

        $sql = sprintf(
            "SELECT `Deal Campaign Key`,`Deal Component Key`, `Deal Key` FROM  `Order Transaction Deal Bridge`  WHERE`Order Key`=%d AND `Deal Component Key`!=0", $this->id
        );

        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            $sql = sprintf(
                "INSERT INTO `Order Deal Bridge` VALUES(%d,%d,%d,%d,'Yes','Yes') ON DUPLICATE KEY UPDATE `Used`='Yes'", $this->id, $row['Deal Campaign Key'], $row['Deal Key'],
                $row['Deal Component Key']
            );
            $this->db->exec($sql);
        }


    }

}



?>
