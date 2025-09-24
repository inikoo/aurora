<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 August 2017 at 21:43:14 CEST, Tranava, Slovakia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/

use Aurora\Models\Utils\TaxCategory;

trait OrderDiscountOperations
{


    function update_discounts_items()
    {
        $this->deal_components = [];

        $this->allowance = array(
            'Percentage Off'              => [],
            'Get Free'                    => [],
            'Get Free No Ordered Product' => []
        );
        $this->deals     = array(
            'Category' => array(
                'Deal'               => false,
                'Terms'              => false,
                'Deal Multiplicity'  => 0,
                'Terms Multiplicity' => 0
            )

        );
        $this->exclude_category_quantity=[];
        $this->get_allowances_from_products_in_category_trigger(); // family carton deal so it can ignore this product fro voulme discount

        $this->get_allowances_from_order_trigger();


        $this->get_allowances_from_products_in_category_trigger(); // family carton deal so it can ignore this product fro voulme discount


        $this->get_allowances_from_category_trigger();
        $this->get_allowances_from_product_trigger();
        $this->get_allowances_from_customer_trigger();
        $this->get_allowances_from_pinned_deal_components();


        $this->apply_items_discounts();
    }

    function get_allowances_from_order_trigger($no_items = false)
    {
        $deals_component_data = [];

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
        }


        foreach ($deals_component_data as $deal_component_data) {
            $this->test_deal_terms($deal_component_data);
        }

        $deals_component_data = [];
        $sql                  = sprintf(
            "select * from `Deal Component Dimension` DC  left join `Deal Dimension` D on (D.`Deal Key`=`Deal Component Deal Key`)  left join `Voucher Order Bridge` V on (V.`Deal Key`=DC.`Deal Component Deal Key`)   where   `Deal Component Status`='Active'  and `Order Key`=%d    $where",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $deals_component_data[$row['Deal Component Key']] = $row;
            }
        }


        foreach ($deals_component_data as $deal_component_data) {
            $this->test_deal_terms($deal_component_data);
        }
    }



    function test_deal_terms($deal_component_data)
    {

        switch ($deal_component_data['Deal Component Terms Type']) {
            case('Order Number'):

                $order_number_term = $deal_component_data['Deal Component Terms'] - 1;


                $sql = sprintf(
                    "SELECT count(*) AS num FROM `Order Dimension` WHERE `Order Customer Key`=%d AND `Order Key`!=%d AND  `Order State` NOT IN ('Cancelled') ",
                    $this->data['Order Customer Key'],
                    $this->id
                );


                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        if ($row['num'] == $order_number_term) {
                            $this->deals['Order']['Terms'] = true;
                            $this->create_allowances_from_deal_component_data(
                                $deal_component_data
                            );
                        }
                    }
                }


                break;

            case('Voucher AND Order Number'):
                $terms = preg_split(
                    '/;/',
                    $deal_component_data['Deal Component Terms']
                );

                $order_number_term = $terms[1] - 1;


                $sql = sprintf(
                    "SELECT count(*) AS num FROM `Voucher Order Bridge` WHERE `Deal Key`=%d AND `Order Key`=%d ",
                    $deal_component_data['Deal Component Deal Key'],
                    $this->id
                );

                if ($result2 = $this->db->query($sql)) {
                    if ($_row = $result2->fetch()) {
                        if ($_row['num'] > 0) {
                            $sql = sprintf(
                                "SELECT count(*) AS num FROM `Order Dimension` WHERE `Order Customer Key`=%d AND `Order Key`!=%d AND  `Order State` NOT IN ('Cancelled') ",
                                $this->data['Order Customer Key'],
                                $this->id
                            );


                            if ($result3 = $this->db->query($sql)) {
                                if ($__row = $result3->fetch()) {
                                    if ($__row['num'] == $order_number_term) {
                                        $this->deals['Order']['Terms'] = true;
                                        $this->create_allowances_from_deal_component_data(
                                            $deal_component_data
                                        );
                                    }
                                }
                            } else {
                                print_r($error_info = $this->db->errorInfo());
                                print "$sql\n";
                                exit;
                            }
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                break;


            case('Voucher AND Amount'):

                $sql = sprintf(
                    "SELECT count(*) AS num FROM `Voucher Order Bridge` WHERE `Deal Key`=%d AND `Order Key`=%d ",
                    $deal_component_data['Deal Component Deal Key'],
                    $this->id
                );


                if ($result2 = $this->db->query($sql)) {
                    if ($_row = $result2->fetch()) {
                        if ($_row['num'] > 0) {
                            $terms = preg_split(
                                '/;/',
                                $deal_component_data['Deal Component Terms']
                            );


                            $amount_term = $terms[1];
                            $amount_type = $terms[2];


                            if ($this->data[$amount_type] >= $amount_term) {
                                $this->deals['Order']['Terms'] = true;
                                $this->create_allowances_from_deal_component_data(
                                    $deal_component_data
                                );
                            }
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                break;


            case('Voucher'):

                $sql = sprintf(
                    "SELECT count(*) AS num FROM `Voucher Order Bridge` WHERE `Deal Key`=%d AND `Order Key`=%d ",
                    $deal_component_data['Deal Component Deal Key'],
                    $this->id
                );


                if ($result2 = $this->db->query($sql)) {
                    if ($_row = $result2->fetch()) {
                        if ($_row['num'] > 0) {
                            $this->deals['Order']['Terms'] = true;
                            $this->create_allowances_from_deal_component_data(
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


            case('Order Interval'):

                $sql = sprintf(
                    "SELECT count(*) AS num FROM `Order Dimension` WHERE `Order Customer Key`=%d AND `Order Key`!=%d AND `Order Dispatched Date`>=%s AND `Order State`='Dispatched' ",
                    $this->data['Order Customer Key'],
                    $this->id,
                    prepare_mysql(date('Y-m-d', strtotime(gmdate('Y-m-d H:i:s')." -".$deal_component_data['Deal Component Terms'])).' 00:00:00')
                );

//                if(
//                    (!in_array( $this->data['Order Store Key'],[18,22]) and DNS_ACCOUNT_CODE=='AWEU')
//                   or
//                    ($this->data['Order Store Key'] != 3 and DNS_ACCOUNT_CODE=='ES')
//                     ($this->data['Order Store Key']==1 and DNS_ACCOUNT_CODE=='AW')
//                ){
//                    $this->deals['Order']['Terms'] = true;
//                    $this->create_allowances_from_deal_component_data($deal_component_data);
//
//              }else {

                    //print "$sql\n";
                    if ($result = $this->db->query($sql)) {
                        if ($_row = $result->fetch()) {
                            //print_r($_row);
                            if ($_row['num'] > 0) {
                                $this->deals['Order']['Terms'] = true;
                                // print_r($deal_component_data);
                                $this->create_allowances_from_deal_component_data($deal_component_data);
                            }
                        }
                    }
           //    }

                break;

            case('Amount'):


                $terms       = preg_split(
                    '/;/',
                    $deal_component_data['Deal Component Terms']
                );
                $amount_term = $terms[0];
                $amount_type = $terms[1];

                if ($this->data[$amount_type] >= $amount_term) {
                    $this->create_allowances_from_deal_component_data(
                        $deal_component_data
                    );
                }


                break;
            case('Amount AND Order Interval'):


                $terms         = preg_split(
                    '/;/',
                    $deal_component_data['Deal Component Terms']
                );
                $amount_term   = $terms[0];
                $amount_type   = $terms[1];
                $interval_term = $terms[2];

                $interval_term_ok = false;
                $amount_term_ok   = false;


                $deal_component_data['Deal Component Terms'];


                if ($this->data[$amount_type] >= $amount_term) {
                    $amount_term_ok = true;
                }


                if ($amount_term_ok) {
                    $sql = sprintf(
                        "SELECT count(*) AS num FROM `Order Dimension` WHERE `Order Customer Key`=%d AND `Order Key`!=%d AND `Order Dispatched Date`>=%s AND `Order State`='Dispatched' ",
                        $this->data['Order Customer Key'],
                        $this->id,
                        prepare_mysql(
                            date('Y-m-d', strtotime($this->data['Order Date']." -".$interval_term)).' 00:00:00'
                        )
                    );


                    if ($result = $this->db->query($sql)) {
                        if ($_row = $result->fetch()) {
                            if ($_row['num'] > 0) {
                                $interval_term_ok = true;
                            }
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }
                }


                if ($amount_term_ok and $interval_term_ok) {
                    $this->create_allowances_from_deal_component_data($deal_component_data);
                }

                break;
            case('Amount AND Order Number'):


                $terms = preg_split('/;/', $deal_component_data['Deal Component Terms']);

                // print_r($deal_component_data);

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
                        "SELECT count(*) AS num FROM `Order Dimension` WHERE `Order Customer Key`=%d AND `Order Key`!=%d AND  `Order State` NOT IN ('Cancelled') ",
                        $this->data['Order Customer Key'],
                        $this->id
                    );


                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            if ($row['num'] == $order_number_term) {
                                $order_number_term_ok = true;
                            }
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }
                }


                if ($amount_term_ok and $order_number_term_ok) {
                    $this->create_allowances_from_deal_component_data($deal_component_data);
                }


                break;

            case('Category Quantity Ordered'):
                $qty_category = 0;

                $excluded_otfs='';
                if(isset($this->exclude_category_quantity[$deal_component_data['Deal Component Trigger Key']])){
                    $excluded_otfs=' and `Order Transaction Fact Key` not in ('.join($this->exclude_category_quantity[$deal_component_data['Deal Component Trigger Key']],',').') ';
                }


                $sql          = sprintf(
                    'SELECT sum(`Order Quantity`) AS qty  FROM `Order Transaction Fact` OTF   LEFT JOIN `Category Bridge`  ON (`Subject Key`=`Product ID`)    WHERE `Subject`="Product" AND `Order Key`=%d AND `Category Key`=%d '.$excluded_otfs,
                    $this->id,
                    $deal_component_data['Deal Component Trigger Key']
                );


                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $qty_category = $row['qty'];
                    }
                }


                if ($qty_category >= $deal_component_data['Deal Component Terms']) {
                    $terms_ok                         = true;
                    $this->deals['Category']['Terms'] = true;
                    $this->create_allowances_from_deal_component_data($deal_component_data);
                }


                break;
            case('Category Amount Ordered'):

                $terms_data = json_decode($deal_component_data['Deal Component Terms'], true);
                $amount     = 0;
                $sql        = sprintf(
                    'SELECT sum(`Order Transaction Amount`) AS amount  FROM `Order Transaction Fact` OTF   LEFT JOIN `Category Bridge`  ON (`Subject Key`=`Product ID`)    WHERE `Subject`="Product" AND `Order Key`=%d AND `Category Key`=%d ',
                    $this->id,
                    $terms_data['key']
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        if ($row['amount'] == '') {
                            $amount = 0;
                        } else {
                            $amount = $row['amount'];
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                if ($amount >= $terms_data['amount']) {
                    $terms_ok                         = true;
                    $this->deals['Category']['Terms'] = true;
                    $this->create_allowances_from_deal_component_data($deal_component_data);
                }
                break;

            case('Product Amount Ordered'):

                $terms_data = json_decode($deal_component_data['Deal Component Terms'], true);
                $amount     = 0;
                $sql        = sprintf(
                    'SELECT sum(`Order Transaction Amount`) AS amount  FROM `Order Transaction Fact` OTF   where `Order Key`=%d AND `Product ID`=%d ',
                    $this->id,
                    $terms_data['key']
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        if ($row['amount'] == '') {
                            $amount = 0;
                        } else {
                            $amount = $row['amount'];
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                if ($amount >= $terms_data['amount']) {
                    $terms_ok                         = true;
                    $this->deals['Category']['Terms'] = true;
                    $this->create_allowances_from_deal_component_data($deal_component_data);
                }
                break;


            case('Category Quantity Ordered AND Voucher'):
                $qty_category = 0;


                $sql = sprintf(
                    "SELECT count(*) AS num FROM `Voucher Order Bridge` WHERE `Deal Key`=%d AND `Order Key`=%d ",
                    $deal_component_data['Deal Component Deal Key'],
                    $this->id
                );

                if ($result2 = $this->db->query($sql)) {
                    if ($_row = $result2->fetch()) {
                        if ($_row['num'] > 0) {
                            $sql = sprintf(
                                'SELECT sum(`Order Quantity`) AS qty  FROM `Order Transaction Fact` OTF   LEFT JOIN `Category Bridge`  ON (`Subject Key`=`Product ID`)    WHERE `Subject`="Product" AND `Order Key`=%d AND `Category Key`=%d ',
                                $this->id,
                                $deal_component_data['Deal Component Trigger Key']
                            );

                            if ($result = $this->db->query($sql)) {
                                if ($row = $result->fetch()) {
                                    $qty_category = $row['qty'];
                                }
                            } else {
                                print_r($error_info = $this->db->errorInfo());
                                print "$sql\n";
                                exit;
                            }
                        }
                    }
                }


                if ($qty_category >= $deal_component_data['Deal Component Terms']) {
                    $terms_ok                         = true;
                    $this->deals['Category']['Terms'] = true;
                    $this->create_allowances_from_deal_component_data($deal_component_data);
                }


                break;


            case ('Category For Every Quantity Ordered'):


                //  print_r($deal_component_data);

                $sql = sprintf(
                    'SELECT `Order Quantity` AS qty,`Product ID`   FROM `Order Transaction Fact` OTF   LEFT JOIN `Category Bridge`  ON (`Subject Key`=`Product ID`)    WHERE `Subject`="Product" AND `Order Key`=%d AND `Category Key`=%d ',
                    $this->id,
                    $deal_component_data['Deal Component Allowance Target Key']
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $qty = $row['qty'];

                        if ($qty >= $deal_component_data['Deal Component Terms']) {
                            $this->deals['Category']['Terms'] = true;

                            $deal_component_product_data = $deal_component_data;


                            $allowances_data = json_decode($deal_component_product_data['Deal Component Allowance'], true);


                            //  print_r($allowances_data);

                            if ($deal_component_data['Deal Component Terms'] != 0) {
                                $allowances_data['qty'] = $allowances_data['qty'] * (floor(
                                        $qty / $deal_component_product_data['Deal Component Terms']
                                    ));

                                $allowances_data['object'] = 'Product';
                                $allowances_data['key']    = $row['Product ID'];

                                $deal_component_product_data['Deal Component Allowance'] = json_encode($allowances_data);

                                $deal_component_product_data['Deal Component Allowance Type']       = 'Get Free';
                                $deal_component_product_data['Deal Component Allowance Target']     = 'Product';
                                $deal_component_product_data['Deal Component Allowance Target Key'] = $row['Product ID'];


                                $this->create_allowances_from_deal_component_data($deal_component_product_data);
                            }
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                break;

            case ('Category For Every Quantity Any Product Ordered'):


                $sql = sprintf(
                    'SELECT sum(`Order Quantity`) AS qty,`Product ID`   FROM `Order Transaction Fact` OTF   LEFT JOIN `Category Bridge`  ON (`Subject Key`=`Product ID`)    WHERE `Subject`="Product" AND `Order Key`=%d AND `Category Key`=%d ',
                    $this->id,
                    $deal_component_data['Deal Component Allowance Target Key']
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $qty = $row['qty'];

                        if ($qty >= $deal_component_data['Deal Component Terms']) {
                            $terms_ok                         = true;
                            $this->deals['Category']['Terms'] = true;


                            //print $qty.' * '.$deal_component_data['Deal Component Terms'].' **'.floor($qty / $deal_component_data['Deal Component Terms']);


                            $deal_component_data['Deal Component Allowance'] = $deal_component_data['Deal Component Allowance'] * floor($qty / $deal_component_data['Deal Component Terms']);

                            $this->create_allowances_from_deal_component_data(
                                $deal_component_data
                            );
                        }
                    }
                }

                break;

            case('Product Quantity Ordered'):
                $qty = 0;
                $sql = sprintf(
                    'SELECT sum(`Order Quantity`) AS qty  FROM `Order Transaction Fact` OTF WHERE `Order Key`=%d AND `Product ID`=%d ',
                    $this->id,
                    $deal_component_data['Deal Component Allowance Target Key']
                );


                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $qty = $row['qty'];
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                if ($qty >= $deal_component_data['Deal Component Terms']) {
                    $this->create_allowances_from_deal_component_data(
                        $deal_component_data
                    );
                    //  print "----\n";
                }

                break;

            case('Product For Every Quantity Ordered'):

                $qty_product = 0;
                $sql         = sprintf(
                    'SELECT sum(`Order Quantity`) AS qty  FROM `Order Transaction Fact` OTF WHERE `Order Key`=%d AND `Product ID`=%d ',
                    $this->id,
                    $deal_component_data['Deal Component Allowance Target Key']
                );


                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        if ($row['qty'] == '') {
                            $qty_product = 0;
                        } else {
                            $qty_product = $row['qty'];
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                //print_r($deal_component_data);

                //print "** $qty_product  -> ".$deal_component_data['Deal Component Terms']."   **\n";


                if ($qty_product > 0 and $qty_product >= $deal_component_data['Deal Component Terms']) {
                    $terms_ok                         = true;
                    $this->deals['Category']['Terms'] = true;

                    // i dont underestad below thing maybe it is wrong
                    if ($deal_component_data['Deal Component Terms'] != 0) {
                        $deal_component_data['Deal Component Allowance'] = $deal_component_data['Deal Component Allowance'] * floor(
                                $qty_product / $deal_component_data['Deal Component Terms']
                            );
                    }

                    $this->create_allowances_from_deal_component_data(
                        $deal_component_data
                    );
                }


                break;

            case('Every Order'):

                $this->deals['Customer']['Terms'] = true;
                $this->create_allowances_from_deal_component_data(
                    $deal_component_data
                );
                break;
            case 'Product In Category Carton':



                $sql = sprintf(
                    'SELECT  `Order Quantity` AS qty,OTF.`Product ID`   FROM `Order Transaction Fact` OTF   
                                LEFT JOIN `Category Bridge`  ON (`Subject Key`=OTF.`Product ID`)    
                                LEFT JOIN `Product Dimension` P  ON (OTF.`Product ID`=P.`Product ID`)    
                                WHERE `Subject`="Product" AND `Order Key`=%d AND `Category Key`=%d and `Product Outers Per Carton`>1 and  `Order Quantity`>=`Product Outers Per Carton`  ',
                    $this->id,
                    $deal_component_data['Deal Component Allowance Target Key']
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {


                        $deal_component_data['Deal Component Allowance Target']='Product';
                        $deal_component_data['Deal Component Allowance Target Key']=$row['Product ID'];

                        //                            $this->deals['Category']['Terms'] = true;

                        $qty = $row['qty'];

                        if ($qty >= $deal_component_data['Deal Component Terms']) {
                            $terms_ok                         = true;
                            $this->deals['Category']['Terms'] = true;


                            //print $qty.' * '.$deal_component_data['Deal Component Terms'].' **'.floor($qty / $deal_component_data['Deal Component Terms']);


                         //   $deal_component_data['Deal Component Allowance'] = $deal_component_data['Deal Component Allowance'] * floor($qty / $deal_component_data['Deal Component Terms']);

                            //print_r($deal_component_data['Deal Component Allowance']);


                            $this->create_allowances_from_deal_component_data($deal_component_data);
                        }
                    }
                }




                break;


        }
    }

    function create_allowances_from_deal_component_data($deal_component_data, $pinned = false)
    {
        if (isset($deal_component_data['Deal Name Label'])) {
            $deal_info = sprintf(
                "%s: %s, %s",
                ($deal_component_data['Deal Name Label'] == '' ? _('Offer') : $deal_component_data['Deal Name Label']),
                (isset($deal_component_data['Deal Term Label']) ? $deal_component_data['Deal Term Label'] : ''),
                $deal_component_data['Deal Component Allowance Label']

            );
        } else {
            $deal_info = _('Discount');
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
                            'Deal Info'          => $deal_info,
                            'Pinned'             => $pinned
                        );

                        $this->deal_components[$deal_component_data['Deal Component Key']] = $deal_component_data;
                    }
                } else {
                    $this->allowance['No Item Transaction']['Amount Off']              = array(
                        'Amount Off'         => $deal_component_data['Deal Component Allowance'],
                        'Deal Campaign Key'  => $deal_component_data['Deal Component Campaign Key'],
                        'Deal Component Key' => $deal_component_data['Deal Component Key'],
                        'Deal Key'           => $deal_component_data['Deal Component Deal Key'],
                        'Deal Info'          => $deal_info,
                        'Pinned'             => $pinned
                    );
                    $this->deal_components[$deal_component_data['Deal Component Key']] = $deal_component_data;
                }
                break;
            case('Percentage Off'):
                switch ($deal_component_data['Deal Component Allowance Target']) {
                    case('Order'):
                        $where = sprintf("where  (is_variant='No') and  `Order Key`=%d", $this->id);

                        $sql = sprintf(
                            "select OTF.`Product ID`,OTF.`Product Key`,OTF.`Order Transaction Fact Key`,`Order Transaction Gross Amount`,0 as `Category Key` 
                            from  `Order Transaction Fact` OTF 
                            left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`)
                               $where"
                        );

                        break;
                    case('Category'):


                        $sql = sprintf(
                            'SELECT `Order Transaction Fact Key`,OTF.`Product ID` ,OTF.`Product Key`,`Category Key` ,`Order Transaction Gross Amount` 
                            FROM `Order Transaction Fact` OTF   
                                LEFT JOIN `Category Bridge`  ON (`Subject Key`=`Product ID`)   
                             left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`)
                            WHERE (is_variant="No") and  `Subject`="Product" AND `Order Key`=%d AND `Category Key`=%d ',
                            $this->id,
                            $deal_component_data['Deal Component Allowance Target Key']
                        );



                        break;
                    case('Product'):
                        $where = sprintf(
                            "where  (is_variant='No') and  `Order Key`=%d and `Product ID`=%d",
                            $this->id,
                            $deal_component_data['Deal Component Allowance Target Key']
                        );

                        $sql = sprintf(
                            "select OTF.`Product ID`,OTF.`Product Key`,`Order Transaction Fact Key`,`Order Transaction Gross Amount`,0 as `Category Key` 
                            from  `Order Transaction Fact` OTF 
                              left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`)
                             $where"
                        );
                        break;
                    default:
                        $where = sprintf("where (is_variant='No') and  false");

                        $sql = sprintf(
                            "select OTF.`Product ID`,OTF.`Product Key`,`Order Transaction Fact Key`,`Order Transaction Gross Amount` ,0 as `Category Key` 
                            from `Order Transaction Fact` OTF 
                               left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`)
                             $where"
                        );
                }
                $percentage = $deal_component_data['Deal Component Allowance'];

//print $sql;
             //   print_r($deal_component_data);
                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                      //  print_r($row);
                        $otf_key = $row['Order Transaction Fact Key'];
                        $implemented=false;
                        if (isset($this->allowance['Percentage Off'][$otf_key])) {
                            if ($this->allowance['Percentage Off'][$otf_key]['Percentage Off'] <= $percentage) {
                                $this->deal_components[$deal_component_data['Deal Component Key']] = $deal_component_data;


                                $this->allowance['Percentage Off'][$otf_key]['Percentage Off']     = $percentage;
                                $this->allowance['Percentage Off'][$otf_key]['Deal Campaign Key']  = $deal_component_data['Deal Component Campaign Key'];
                                $this->allowance['Percentage Off'][$otf_key]['Deal Component Key'] = $deal_component_data['Deal Component Key'];
                                $this->allowance['Percentage Off'][$otf_key]['Deal Key']           = $deal_component_data['Deal Component Deal Key'];
                                $this->allowance['Percentage Off'][$otf_key]['Deal Info']          = $deal_info;
                                $this->allowance['Percentage Off'][$otf_key]['Pinned']             = $pinned;
                                $implemented=true;
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
                                'Category Key'                   => $row['Category Key'],
                                'Order Transaction Gross Amount' => $row['Order Transaction Gross Amount'],

                                'Pinned' => $pinned

                            );
                            $implemented=true;
                            $this->deal_components[$deal_component_data['Deal Component Key']] = $deal_component_data;
                        }

                        if($implemented and $deal_component_data['Deal Component Terms Type']=='Product In Category Carton'){
                            
                            if(isset($this->exclude_category_quantity[$deal_component_data['Deal Component Trigger Key']])){
                                $this->exclude_category_quantity[$deal_component_data['Deal Component Trigger Key']][]=$otf_key;
                            }else{
                                $this->exclude_category_quantity[$deal_component_data['Deal Component Trigger Key']]=[$otf_key];
                            }
                            
                            
                        }

                    }
                }



                break;


            case('Get Free'):

                switch ($deal_component_data['Deal Component Allowance Target']) {
                    case('Charge'):


                        $_allowance_data = json_decode($deal_component_data['Deal Component Allowance'], true);


                        $this->allowance['No Item Transaction']['Charge'] = array(
                            'Percentage Off'     => 1,
                            'Charge Key'         => $_allowance_data['key'],
                            'Deal Campaign Key'  => $deal_component_data['Deal Component Campaign Key'],
                            'Deal Component Key' => $deal_component_data['Deal Component Key'],
                            'Deal Key'           => $deal_component_data['Deal Component Deal Key'],
                            'Deal Info'          => $deal_info,
                            'Pinned'             => $pinned
                        );


                        $this->deal_components[$deal_component_data['Deal Component Key']] = $deal_component_data;
                        break;

                    case('Shipping'):


                        $this->allowance['No Item Transaction']['Shipping'] = array(
                            'Percentage Off'     => 1,
                            'Deal Campaign Key'  => $deal_component_data['Deal Component Campaign Key'],
                            'Deal Component Key' => $deal_component_data['Deal Component Key'],
                            'Deal Key'           => $deal_component_data['Deal Component Deal Key'],
                            'Deal Info'          => $deal_info,
                            'Pinned'             => $pinned
                        );


                        $this->deal_components[$deal_component_data['Deal Component Key']] = $deal_component_data;
                        break;

                    case('Category'):


                        $allowance_data = $deal_component_data['Deal Component Allowance'];


                        $category_key       = $allowance_data['key'];
                        $get_free_allowance = $allowance_data['qty'];


                        $sql = sprintf(
                            "SELECT `Preference Metadata` FROM `Deal Component Customer Preference Bridge`  WHERE `Deal Component Key`=%d AND `Customer Key`=%d ",
                            $deal_component_data['Deal Component Key'],
                            $this->data['Order Customer Key']
                        );


                        if ($result = $this->db->query($sql)) {
                            if ($row = $result->fetch()) {
                                $product_code = $row['Preference Metadata'];

                                $sql = sprintf(
                                    "SELECT `Product ID` FROM `Product Dimension` WHERE `Product Store Key`=%s AND `Product Code`=%s AND `Product Status` in ('Active','Discontinuing')   ",
                                    $this->data['Order Store Key'],
                                    prepare_mysql($product_code)
                                );


                                if ($result2 = $this->db->query($sql)) {
                                    if ($row2 = $result2->fetch()) {
                                        $product_pid = $row2['Product ID'];
                                    } else {
                                        $product_pid = 0;
                                    }
                                } else {
                                    print_r($error_info = $this->db->errorInfo());
                                    print "$sql\n";
                                    exit;
                                }


                                if ($product_pid) {
                                    $sql = sprintf(
                                        "SELECT count(*) AS num FROM `Product Dimension` WHERE `Product Status` in ('Active','Discontinuing') AND `Product Family Category Key`=%d AND `Product ID`=%d",
                                        $category_key,
                                        $product_pid
                                    );


                                    if ($result2 = $this->db->query($sql)) {
                                        if ($row2 = $result2->fetch()) {
                                            if ($row2['num'] == 0) {
                                                $product_pid = $get_free_allowance[1];
                                                $sql         = sprintf(
                                                    "DELETE FROM `Deal Component Customer Preference Bridge`  WHERE `Deal Component Key`=%d AND `Customer Key`=%d ",
                                                    $deal_component_data['Deal Component Key'],
                                                    $this->data['Order Customer Key']
                                                );
                                                $this->db->exec($sql);
                                            }
                                        }
                                    } else {
                                        print_r($error_info = $this->db->errorInfo());
                                        print "$sql\n";
                                        exit;
                                    }
                                }
                            } else {
                                $sql = sprintf(
                                    "SELECT `Product ID` FROM `Product Dimension` WHERE `Product Store Key`=%s AND `Product Code`=%s AND `Product Status` in ('Active','Discontinuing')",
                                    $this->data['Order Store Key'],
                                    prepare_mysql($get_free_allowance[1])
                                );


                                if ($result2 = $this->db->query($sql)) {
                                    if ($row2 = $result2->fetch()) {
                                        $product_pid = $row2['Product ID'];
                                    } else {
                                        $product_pid = 0;
                                    }
                                } else {
                                    print_r($error_info = $this->db->errorInfo());
                                    print "$sql\n";
                                    exit;
                                }
                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }


                        $sql = sprintf(
                            "SELECT count(*) AS num FROM `Product Dimension` WHERE `Product Status` in ('Active','Discontinuing') AND `Product Family Category Key`=%d AND `Product ID`=%d",
                            $category_key,
                            $product_pid
                        );

                        if ($result_2 = $this->db->query($sql)) {
                            if ($row_2 = $result_2->fetch()) {
                                if ($row_2['num'] == 0) {
                                    $product_pid = 0;
                                }
                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }


                        if ($deal_component_data['Deal Component Trigger'] == 'Order') {
                            $allowance_index = 'Get Free No Ordered Product';
                        } else {
                            $allowance_index = 'Get Free';
                        }

                        if ($product_pid) {
                            if (isset($this->allowance[$allowance_index][$product_pid])) {
                                $this->allowance[$allowance_index][$product_pid]['Get Free'] += $get_free_allowance[0];
                            } else {
                                $product = get_object('Product', $product_pid);

                                $this->allowance[$allowance_index][$product_pid] = array(
                                    'Product ID'           => $product->id,
                                    'Product Key'          => $product->historic_id,
                                    'Product Category Key' => $product->get('Product Family Category Key'),
                                    'Get Free'             => $get_free_allowance[0],
                                    'Deal Campaign Key'    => $deal_component_data['Deal Component Campaign Key'],
                                    'Deal Component Key'   => $deal_component_data['Deal Component Key'],
                                    'Deal Key'             => $deal_component_data['Deal Component Deal Key'],
                                    'Deal Info'            => $deal_info,
                                    'Pinned'               => $pinned
                                );
                            }

                            $this->deal_components[$deal_component_data['Deal Component Key']] = $deal_component_data;
                        }


                        break;

                    case('Product'):


                        $allowance_data = json_decode($deal_component_data['Deal Component Allowance'], true);


                        $product_pid = $allowance_data['key'];

                        $product = get_object($allowance_data['object'], $allowance_data['key']);

                        $get_free_allowance = $allowance_data['qty'];


                        if ($deal_component_data['Deal Component Trigger'] == 'Order') {
                            $allowance_index = 'Get Free No Ordered Product';
                        } else {
                            $allowance_index = 'Get Free';
                        }


                        if (isset($this->allowance[$allowance_index][$product_pid])) {
                            $this->allowance[$allowance_index][$product_pid]['Get Free'] += $get_free_allowance;
                        } else {
                            $this->allowance[$allowance_index][$product_pid] = array(
                                'Product ID'           => $product->id,
                                'Product Key'          => $product->historic_id,
                                'Product Category Key' => $product->get('Product Family Category Key'),
                                'Get Free'             => $get_free_allowance,
                                'Deal Campaign Key'    => $deal_component_data['Deal Component Campaign Key'],
                                'Deal Component Key'   => $deal_component_data['Deal Component Key'],
                                'Deal Key'             => $deal_component_data['Deal Component Deal Key'],
                                'Deal Info'            => $deal_info.' <span class="highlight"><i class="fa fa-plus-square padding_left_10"></i> '.sprintf('%d %s', $get_free_allowance, $product->get('Code')).'</span>',
                                'Pinned'               => $pinned
                            );
                        }


                        break;
                }

                break;


            case('Get Cheapest Free'):
                //print_r($deal_component_data);

                switch ($deal_component_data['Deal Component Allowance Target']) {
                    case 'Category':

                        $sql = sprintf(
                            'SELECT P.`Product Code`,`Order Transaction Fact Key`,`Order Quantity`,OTF.`Product Key`,OTF.`Product ID`,`Product Price`,`Order Transaction Gross Amount`  
                            FROM `Order Transaction Fact` OTF   LEFT JOIN `Category Bridge`  ON (`Subject Key`=`Product ID`)   
                             left join `Product Dimension` P on (OTF.`Product ID`=P.`Product ID`)
                             
                             WHERE `Subject`="Product" AND `Order Key`=%d AND `Category Key`=%d  order by `Product Price`,`Order Quantity`  ',
                            $this->id,
                            $deal_component_data['Deal Component Allowance Target Key']
                        );

                        break;
                    default:


                        break;
                }


                $number_free_outers = $deal_component_data['Deal Component Allowance'];


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        // print_r($row);
                        if ($row['Order Quantity'] <= $number_free_outers) {
                            $percentage = 1;

                            $free_in_this_item = $row['Order Quantity'];
                        } else {
                            $percentage        = $number_free_outers / $row['Order Quantity'];
                            $free_in_this_item = $number_free_outers;
                        }

                        $number_free_outers -= $row['Order Quantity'];


                        $otf_key = $row['Order Transaction Fact Key'];


                        //  $deal_info .= ' <span class="highlight"><i class="fa fa-gift padding_left_10"></i> '.$free_in_this_item.' '.$row['Product Code'].'</span>';

                        //  $deal_info .= sprintf('%s off',percentage($percentage,1));


                        if (isset($this->allowance['Percentage Off'][$otf_key])) {
                            if ($this->allowance['Percentage Off'][$otf_key]['Percentage Off'] <= $percentage) {
                                $this->allowance['Percentage Off'][$otf_key]['Percentage Off']     = $percentage;
                                $this->allowance['Percentage Off'][$otf_key]['Deal Campaign Key']  = $deal_component_data['Deal Component Campaign Key'];
                                $this->allowance['Percentage Off'][$otf_key]['Deal Component Key'] = $deal_component_data['Deal Component Key'];
                                $this->allowance['Percentage Off'][$otf_key]['Deal Key']           = $deal_component_data['Deal Component Deal Key'];
                                $this->allowance['Percentage Off'][$otf_key]['Deal Info']          = $deal_info.' <b>'.sprintf('%s off', percentage($percentage, 1)).'</b>';
                                $this->allowance['Percentage Off'][$otf_key]['Pinned']             = $pinned;
                            }
                        } else {
                            $this->allowance['Percentage Off'][$otf_key] = array(
                                'Percentage Off'       => $percentage,
                                'Deal Campaign Key'    => $deal_component_data['Deal Component Campaign Key'],
                                'Deal Component Key'   => $deal_component_data['Deal Component Key'],
                                'Deal Key'             => $deal_component_data['Deal Component Deal Key'],
                                'Deal Info'            => $deal_info.' <b>'.sprintf('%s off', percentage($percentage, 1)).'</b>',
                                'Product Key'          => $row['Product Key'],
                                'Product ID'           => $row['Product ID'],
                                'Product Category Key' => 0,

                                'Order Transaction Gross Amount' => $row['Order Transaction Gross Amount'],
                                'Pinned'                         => $pinned

                            );
                        }

                        if ($number_free_outers <= 0) {
                            break;
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                break;


            case('Get Free No Ordered Product'):

                switch ($deal_component_data['Deal Component Allowance Target']) {
                    case('Product'):
                        $product_pid = $deal_component_data['Deal Component Allowance Target Key'];

                        $product = get_object('Product', $deal_component_data['Deal Component Allowance Target Key']);


                        $_data_allowances = json_decode($deal_component_data['Deal Component Allowance'], true);


                        $get_free_allowance = $_data_allowances['qty'];


                        if (isset($this->allowance['Get Free No Ordered Product'][$product_pid])) {
                            $this->allowance['Get Free No Ordered Product'][$product_pid]['Get Free'] += $get_free_allowance;
                        } else {
                            $this->allowance['Get Free No Ordered Product'][$product_pid] = array(
                                'Product ID'           => $product->id,
                                'Product Key'          => $product->historic_id,
                                'Product Category Key' => $product->get('Product Family Category Key'),
                                'Get Free'             => $get_free_allowance,
                                'Deal Campaign Key'    => $deal_component_data['Deal Component Campaign Key'],
                                'Deal Component Key'   => $deal_component_data['Deal Component Key'],
                                'Deal Key'             => $deal_component_data['Deal Component Deal Key'],
                                'Deal Info'            => $deal_info.' <span class="highlight"><i class="fa fa-plus-square padding_left_10"></i> '.sprintf('%d %s', $get_free_allowance, $product->get('Code')).'</span>',
                                'Pinned'               => $pinned
                            );
                        }


                        break;
                }

                break;


            case('Get Free Customer Choose'):

                switch ($deal_component_data['Deal Component Allowance Target']) {
                    case('Product'):


                        $allowance_data = json_decode($deal_component_data['Deal Component Allowance'], true);


                        $customer = get_object('Customer', $this->get('Order Customer Key'));


                        $product_pid = $customer->metadata('DC_'.$deal_component_data['Deal Component Key']);


                        // print_r($customer);

                        if (!$product_pid or !array_key_exists($product_pid, $allowance_data['options'])) {
                            $product_pid = $allowance_data['default'];
                        }


                        $product = get_object($allowance_data['object'], $product_pid);

                        $get_free_allowance = $allowance_data['qty'];


                        if ($deal_component_data['Deal Component Trigger'] == 'Order') {
                            $allowance_index = 'Get Free No Ordered Product';
                        } else {
                            $allowance_index = 'Get Free';
                        }


                        if (isset($this->allowance[$allowance_index][$product_pid])) {
                            $this->allowance[$allowance_index][$product_pid]['Get Free'] += $get_free_allowance;
                        } else {
                            $this->allowance[$allowance_index][$product_pid] = array(
                                'Product ID'           => $product->id,
                                'Product Key'          => $product->historic_id,
                                'Product Category Key' => $product->get('Product Family Category Key'),
                                'Get Free'             => $get_free_allowance,
                                'Deal Campaign Key'    => $deal_component_data['Deal Component Campaign Key'],
                                'Deal Component Key'   => $deal_component_data['Deal Component Key'],
                                'Deal Key'             => $deal_component_data['Deal Component Deal Key'],
                                'Deal Info'            => $deal_info.' <span class="highlight"><i class="fa fa-plus-square padding_left_10"></i> '.sprintf('%d %s', $get_free_allowance, $product->get('Code')).'</span>',
                                'Pinned'               => $pinned,
                                'Metadata'             => json_encode(array('selected' => $product->id))
                            );
                        }


                        break;
                }

                break;


            case 'Shipping Off':

                $this->allowance['No Item Transaction']['Shipping Off']            = array(
                    'Shipping Zone Schema Key' => $deal_component_data['Deal Component Allowance Target Key'],
                    'Deal Campaign Key'        => $deal_component_data['Deal Component Campaign Key'],
                    'Deal Component Key'       => $deal_component_data['Deal Component Key'],
                    'Deal Key'                 => $deal_component_data['Deal Component Deal Key'],
                    'Deal Info'                => $deal_info,
                    'Pinned'                   => $pinned
                );
                $this->deal_components[$deal_component_data['Deal Component Key']] = $deal_component_data;


                break;
        }
    }


    function get_allowances_from_products_in_category_trigger()
    {
        $this->exclude_category_quantity=[];
        $sql = sprintf(
            'SELECT `Category Key`  FROM `Order Transaction Fact` OTF   LEFT JOIN `Category Bridge`  ON (`Subject Key`=`Product ID`)    WHERE `Subject`="Product" AND `Order Key`=%d   GROUP BY `Category Key` ',
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row_lines) {



                $category_key = $row_lines['Category Key'];


                $deals_component_data = [];
                //$discounts            = 0;

                $sql = sprintf(
                    "SELECT * FROM `Deal Component Dimension`  LEFT JOIN `Deal Dimension` D ON (D.`Deal Key`=`Deal Component Deal Key`)  
                  WHERE `Deal Component Trigger`='Category' AND `Deal Component Trigger Key` =%d   and `Deal Component Trigger Scope Type`='Products' 
                    AND `Deal Component Status`='Active' ",
                    $category_key
                );


                if ($result2 = $this->db->query($sql)) {
                    foreach ($result2 as $row) {



                        $deals_component_data[$row['Deal Component Key']] = $row;
                    }
                }


                foreach ($deals_component_data as $deal_component_data) {
                    $this->test_deal_terms($deal_component_data);
                }
            }
        }
        
        

    }

    function get_allowances_from_category_trigger()
    {
        $sql = sprintf(
            'SELECT `Category Key`  FROM `Order Transaction Fact` OTF   LEFT JOIN `Category Bridge`  ON (`Subject Key`=`Product ID`)    WHERE `Subject`="Product" AND `Order Key`=%d   GROUP BY `Category Key` ',
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row_lines) {
                $category_key = $row_lines['Category Key'];


                $deals_component_data = [];
                //$discounts            = 0;

                $sql = sprintf(
                    "SELECT * FROM `Deal Component Dimension`  LEFT JOIN `Deal Dimension` D ON (D.`Deal Key`=`Deal Component Deal Key`)  
                  WHERE `Deal Component Trigger`='Category' AND `Deal Component Trigger Key` =%d  and `Deal Component Trigger Scope Type`!='Products' AND `Deal Component Status`='Active' ",
                    $category_key
                );


                if ($result2 = $this->db->query($sql)) {
                    foreach ($result2 as $row) {
                        $deals_component_data[$row['Deal Component Key']] = $row;
                    }
                }


                foreach ($deals_component_data as $deal_component_data) {
                    $this->test_deal_terms($deal_component_data);
                }
            }
        }
    }

    function get_allowances_from_product_trigger()
    {
        $sql = sprintf(
            "SELECT `Product ID` FROM `Order Transaction Fact` WHERE `Order Key`=%d GROUP BY `Product ID`",
            $this->id
        );


        if ($result2 = $this->db->query($sql)) {
            foreach ($result2 as $row_lines) {
                $deals_component_data = [];

                $sql = sprintf(
                    "SELECT * FROM `Deal Component Dimension`  LEFT JOIN `Deal Dimension` D ON (D.`Deal Key`=`Deal Component Deal Key`)  WHERE `Deal Component Trigger`='Product' AND `Deal Component Trigger Key` =%d  AND `Deal Component Status`='Active' ",
                    $row_lines['Product ID']
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $deals_component_data[$row['Deal Component Key']] = $row;
                    }
                }


                foreach ($deals_component_data as $deal_component_data) {
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
        }
    }

    function get_allowances_from_customer_trigger($no_items = false)
    {
        $deals_component_data = [];
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
            "select * from `Deal Component Dimension`  left join `Deal Dimension` D on (D.`Deal Key`=`Deal Component Deal Key`)  where `Deal Component Trigger`='Customer' and `Deal Component Trigger Key` =%d  and `Deal Component Status`='Active' $where",
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
            $this->test_deal_terms($deal_component_data);
        }
    }

    function get_allowances_from_pinned_deal_components($no_items = false)
    {
        $pinned_deal_components = $this->get('Pinned Deal Deal Components');

        if ($no_items) {
            $type = 'No Items';
        } else {
            $type = 'Items';
        }


        foreach ($pinned_deal_components as $pinned_deal_component) {
            if ($pinned_deal_component['Deal Component Allowance Target Type'] == $type) {
                $this->create_allowances_from_deal_component_data(
                    $pinned_deal_component,
                    $pinned = true
                );
            }
        }
    }

    function apply_items_discounts()
    {
        $this->amount_off_allowance_data = '';
        $this->amount_off_value          = 0;


        $current_OTDBs_to_delete        = [];
        $current_OTF_discounts_to_clear = [];
        $current_OTF_bonus_to_clear     = [];

        $current_free_no_ordered_items_to_delete = [];


        $current_ONPTDBs_to_delete = [];
        $sql                       = "select `Transaction Gross Amount`,`Order No Product Transaction Tax Category Key`,`Order No Product Transaction Deal Key`,`Deal Component Key`,ONPTDB.`Order No Product Transaction Fact Key`,
                        `Amount Discount`,ONPTF.`Order No Product Transaction Fact Key` as ONPTF_Key  
                            from `Order No Product Transaction Deal Bridge`  ONPTDB left join 
                                `Order No Product Transaction Fact` ONPTF on (ONPTDB.`Order No Product Transaction Fact Key`=ONPTF.`Order No Product Transaction Fact Key`)  
                            where ONPTDB.`Order Key`=? and `Deal Component Key`>0 and  `Order No Product Transaction Deal Source`='items'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
                           $this->id
                       ));
        while ($row = $stmt->fetch()) {
            if ($row['Order No Product Transaction Fact Key']) {
                $current_ONPTDBs_to_delete[$row['Order No Product Transaction Fact Key'].'_'.$row['Deal Component Key']] = array(
                    'onpt_key'         => $row['Order No Product Transaction Fact Key'],
                    'otdk_key'         => $row['Order No Product Transaction Deal Key'],
                    'tax_category_key' => $row['Order No Product Transaction Tax Category Key'],
                    'gross_amount'     => $row['Transaction Gross Amount'],
                    'ghost'            => ($row['ONPTF_Key'] == '' ? 'Yes' : 'No')
                );
            }
        }


        $sql = sprintf('select `Order Transaction Fact Key`,`Deal Component Key`,`Order Transaction Deal Key`,`Amount Discount`,`Bonus Quantity` from `Order Transaction Deal Bridge` where `Order Key`=%d and `Deal Component Key`>0 ', $this->id);


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $current_OTDBs_to_delete[$row['Order Transaction Fact Key'].'_'.$row['Deal Component Key']] = array(
                    'otfk' => $row['Order Transaction Fact Key'],
                    'otdk' => $row['Order Transaction Deal Key']
                );

                if ($row['Amount Discount'] > 0) {
                    $current_OTF_discounts_to_clear[$row['Order Transaction Fact Key']] = $row['Order Transaction Fact Key'];
                }

                if ($row['Bonus Quantity'] > 0) {
                    $current_OTF_bonus_to_clear[$row['Order Transaction Fact Key']] = $row['Order Transaction Fact Key'];
                }
            }
        }


        $sql = sprintf(
            "SELECT `Order Meta Transaction Deal Key`,`Bonus Order Transaction Fact Key` FROM `Order Meta Transaction Deal Dimension`  WHERE `Order Key`=%d ",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $current_free_no_ordered_items_to_delete[$row['Order Meta Transaction Deal Key']] = $row['Bonus Order Transaction Fact Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if (isset($this->allowance['No Item Transaction'])) {
            foreach ($this->allowance['No Item Transaction'] as $type => $allowance_data) {
                switch ($type) {
                    case 'Amount Off':


                        if ($allowance_data['Amount Off'] > 0 and $this->amount_off_value <= $allowance_data['Amount Off']) {
                            $this->amount_off_value          = $allowance_data['Amount Off'];
                            $this->amount_off_allowance_data = $allowance_data;
                        }


                        break;

                    case 'Shipping Off':

                        //print_r($allowance_data);


                        $_type = 'Shipping';


                        $sql = sprintf(
                            'SELECT `Order No Product Transaction Fact Key`,`Transaction Gross Amount` FROM  `Order No Product Transaction Fact` OTF  WHERE `Order Key`=%d AND `Transaction Type`=%s ',
                            $this->id,
                            prepare_mysql($_type)
                        );


                        if ($result = $this->db->query($sql)) {
                            foreach ($result as $row) {
                                $_data = array(
                                    'shipping_zone_schema_key' => $allowance_data['Shipping Zone Schema Key'],
                                    'Order Data'               => array(
                                        'Order Items Net Amount'                      => $this->data['Order Items Net Amount'],
                                        'Order Estimated Weight'                      => $this->data['Order Estimated Weight'],
                                        'Order Delivery Address Postal Code'          => $this->data['Order Delivery Address Postal Code'],
                                        'Order Delivery Address Country 2 Alpha Code' => $this->data['Order Delivery Address Country 2 Alpha Code'],
                                    )


                                );


                                include_once 'nano_services/shipping_for_order.ns.php';

                                $shipping_data = (new shipping_for_order($this->db))->get($_data);


                                if ($shipping_data['shipping_zone_schema_key'] == $allowance_data['Shipping Zone Schema Key']) {
                                    //print_r($shipping_data);

                                    //  exit;

                                    if (isset($current_ONPTDBs_to_delete[$row['Order No Product Transaction Fact Key'].'_'.$allowance_data['Deal Component Key']])) {
                                        unset($current_ONPTDBs_to_delete[$row['Order No Product Transaction Fact Key'].'_'.$allowance_data['Deal Component Key']]);
                                    }

                                    if (empty($allowance_data['Pinned'])) {
                                        $pinned = 'No';
                                    } else {
                                        $pinned = 'Yes';
                                    }


                                    $amount_discount = $row['Transaction Gross Amount'] - $shipping_data['price'];
                                    if ($row['Transaction Gross Amount'] == 0) {
                                        $percentage_off = 0;
                                    } else {
                                        $percentage_off = $amount_discount / $row['Transaction Gross Amount'];
                                    }


                                    $sql = sprintf(
                                        "INSERT INTO `Order No Product Transaction Deal Bridge` (`Order No Product Transaction Deal Source`,`Order No Product Transaction Fact Key`,`Order Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`,`Order No Product Transaction Deal Pinned`)
					VALUES ('items',%d,%d,%d,%d,%d,%s,%f,%f,%s)  ON DUPLICATE KEY UPDATE `Deal Info`=%s ,`Amount Discount`=%f ,`Fraction Discount`=%f ,`Order No Product Transaction Deal Pinned`=%s",
                                        $row['Order No Product Transaction Fact Key'],
                                        $this->id,
                                        $allowance_data['Deal Campaign Key'],
                                        $allowance_data['Deal Key'],
                                        $allowance_data['Deal Component Key'],
                                        prepare_mysql($allowance_data['Deal Info']),
                                        $amount_discount,
                                        $percentage_off,
                                        prepare_mysql($pinned),
                                        prepare_mysql($allowance_data['Deal Info']),
                                        $amount_discount,
                                        $percentage_off,
                                        prepare_mysql($pinned)
                                    );

                                    //  print $sql;
                                    // exit;
                                    $this->db->exec($sql);
                                }
                            }
                        }


                        break;
                }
            }
        }


        if (isset($this->allowance['Percentage Off'])) {
            foreach ($this->allowance['Percentage Off'] as $otf_key => $allowance_data) {
                //    print $otf_key.'_'.$allowance_data['Deal Component Key']."  <--- \n";

                if (isset($current_OTDBs_to_delete[$otf_key.'_'.$allowance_data['Deal Component Key']])) {
                    unset($current_OTDBs_to_delete[$otf_key.'_'.$allowance_data['Deal Component Key']]);
                }

                if (isset($current_OTF_discounts_to_clear[$otf_key])) {
                    unset($current_OTDBs_to_delete[$otf_key]);
                }

                if (empty($allowance_data['Pinned'])) {
                    $pinned = 'No';
                } else {
                    $pinned = 'Yes';
                }


                $sql = sprintf(
                    "INSERT INTO `Order Transaction Deal Bridge` (`Order Transaction Fact Key`,`Order Key`,`Product Key`,`Product ID`,`Category Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`,`Bonus Quantity`,`Order Transaction Deal Pinned`,`Order Transaction Deal Metadata`) VALUES
			(%d,%d,%d,%d,%d,%d,%d,%d,%s,%f,%f,0,%s,'{}') ON DUPLICATE KEY UPDATE `Deal Info`=%s ,`Amount Discount`=%f ,`Fraction Discount`=%f ,`Order Transaction Deal Pinned`=%s ",
                    $otf_key,
                    $this->id,

                    $allowance_data['Product Key'],
                    $allowance_data['Product ID'],
                    (isset($allowance_data['Category Key']) ? $allowance_data['Category Key'] : 0),
                    $allowance_data['Deal Campaign Key'],
                    $allowance_data['Deal Key'],
                    $allowance_data['Deal Component Key'],

                    prepare_mysql($allowance_data['Deal Info']),
                    $allowance_data['Order Transaction Gross Amount'] * $allowance_data['Percentage Off'],
                    $allowance_data['Percentage Off'],
                    prepare_mysql($pinned),
                    prepare_mysql($allowance_data['Deal Info']),
                    $allowance_data['Order Transaction Gross Amount'] * $allowance_data['Percentage Off'],
                    $allowance_data['Percentage Off'],
                    prepare_mysql($pinned)
                );
                $this->db->exec($sql);
                // print "$sql\n";

            }
        }


        if (isset($this->allowance['Get Free'])) {
            foreach ($this->allowance['Get Free'] as $allowance_data) {
                $sql = sprintf(
                    'SELECT `Product ID`,OTF.`Product Key`,`Order Transaction Fact Key`,`Order Transaction Gross Amount` FROM  `Order Transaction Fact` OTF  WHERE `Order Key`=%d AND `Product ID`=%d ',
                    $this->id,
                    $allowance_data['Product ID']
                );

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $amount_discount   = 0;
                        $fraction_discount = 0;

                        if (isset($current_OTDBs_to_delete[$row['Order Transaction Fact Key'].'_'.$allowance_data['Deal Component Key']])) {
                            unset($current_OTDBs_to_delete[$row['Order Transaction Fact Key'].'_'.$allowance_data['Deal Component Key']]);
                        }

                        if (isset($current_OTF_bonus_to_clear[$row['Order Transaction Fact Key']])) {
                            unset($current_OTF_bonus_to_clear[$row['Order Transaction Fact Key']]);
                        }


                        if (empty($allowance_data['Pinned'])) {
                            $pinned = 'No';
                        } else {
                            $pinned = 'Yes';
                        }


                        $sql = sprintf(
                            "INSERT INTO `Order Transaction Deal Bridge` (`Order Transaction Fact Key`,`Order Key`,`Product Key`,`Product ID`,`Category Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`,`Bonus Quantity`,`Order Transaction Deal Pinned`,`Order Transaction Deal Metadata`) 
                          VALUES (%d,%d,%d,%d,%d,%d,%d,%d,%s,%f,%f,%d,%s,'{}')  ON DUPLICATE KEY UPDATE `Deal Info`=%s ,`Bonus Quantity`=%d ,`Order Transaction Deal Pinned`=%s",
                            $row['Order Transaction Fact Key'],
                            $this->id,
                            $row['Product Key'],
                            $row['Product ID'],
                            $allowance_data['Product Category Key'],
                            $allowance_data['Deal Campaign Key'],
                            $allowance_data['Deal Key'],
                            $allowance_data['Deal Component Key'],
                            prepare_mysql($allowance_data['Deal Info']),
                            $amount_discount,
                            $fraction_discount,
                            $allowance_data['Get Free'],
                            prepare_mysql($pinned),
                            prepare_mysql($allowance_data['Deal Info']),
                            $allowance_data['Get Free'],
                            prepare_mysql($pinned)
                        );

                        //  print $sql."\n";

                        $this->db->exec($sql);
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }
            }
        }


        if (isset($this->allowance['Get Free No Ordered Product'])) {
            foreach ($this->allowance['Get Free No Ordered Product'] as $type => $allowance_data) {
                if (in_array($this->data['Order State'], array(
                    'Ready to Pick',
                    'Packed',
                    'PackedDone',
                    'InWarehouse'
                ))) {
                    $dispatching_state = 'Ready to Pick';
                } else {
                    $dispatching_state = 'In Process';
                }

                $payment_state = 'Waiting Payment';


                $ordered_qty = 0;


                $sql = "SELECT `Order Quantity` FROM `Order Transaction Fact` OTF WHERE `Order Key`=? AND `Product Key`=? ";

                $stmt = $this->db->prepare($sql);
                if ($stmt->execute(array(
                                       $this->id,
                                       $allowance_data['Product Key']
                                   ))) {
                    if ($row = $stmt->fetch()) {
                        $ordered_qty = $row['Order Quantity'];
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit();
                }


                $data = array(
                    'date'                      => gmdate('Y-m-d H:i:s'),
                    'item_historic_key'         => $allowance_data['Product Key'],
                    'Metadata'                  => '',
                    'qty'                       => $ordered_qty,
                    'bonus qty'                 => $allowance_data['Get Free'],
                    'Current Dispatching State' => $dispatching_state,
                    'Current Payment State'     => $payment_state
                );


                $this->skip_update_after_individual_transaction = true;

                $transaction_data = $this->update_item($data);

                $this->skip_update_after_individual_transaction = false;


                if (empty($allowance_data['Pinned'])) {
                    $pinned = 'No';
                } else {
                    $pinned = 'Yes';
                }


                // print_r($allowance_data);


                if (!empty($allowance_data['Metadata'])) {
                    $metadata = $allowance_data['Metadata'];
                } else {
                    $metadata = '{}';
                }


                $sql = sprintf(
                    "INSERT INTO `Order Transaction Deal Bridge` (`Order Transaction Fact Key`,`Order Key`,`Product Key`,`Product ID`,`Category Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`,`Bonus Quantity`,`Order Transaction Deal Pinned`,`Order Transaction Deal Metadata`) 
                          VALUES (%d,%d,%d,%d,%d,%d,%d,%d,%s,%f,%f,%d,%s,%s)  ON DUPLICATE KEY UPDATE 
                          
                          `Order Transaction Deal Key`=LAST_INSERT_ID(`Order Transaction Deal Key`),
                          
                          `Deal Info`=%s ,`Bonus Quantity`=%d ,`Order Transaction Deal Pinned`=%s",
                    $transaction_data['otf_key'],
                    $this->id,
                    $allowance_data['Product Key'],
                    $allowance_data['Product ID'],
                    $allowance_data['Product Category Key'],
                    $allowance_data['Deal Campaign Key'],
                    $allowance_data['Deal Key'],
                    $allowance_data['Deal Component Key'],
                    prepare_mysql($allowance_data['Deal Info']),
                    0,
                    0,
                    $allowance_data['Get Free'],
                    prepare_mysql($pinned),
                    prepare_mysql($metadata),
                    prepare_mysql($allowance_data['Deal Info']),
                    $allowance_data['Get Free'],
                    prepare_mysql($pinned)
                );
                $this->db->exec($sql);


                //print "$sql\n";


                $inserted_otdk = $this->db->lastInsertId();

                $found = false;
                foreach ($current_OTDBs_to_delete as $current_OTDB_to_delete_key => $current_OTDB_to_delete_data) {
                    if ($current_OTDB_to_delete_data['otdk'] == $inserted_otdk) {
                        unset($current_OTF_bonus_to_clear[$current_OTDB_to_delete_data['otfk']]);


                        unset($current_OTDBs_to_delete[$current_OTDB_to_delete_key]);


                        $found = true;
                    }
                }

                if (!$found) {
                    $this->new_otfs[] = $transaction_data;
                    //  print_r($transaction_data);

                }

                $sql = sprintf(
                    "SELECT `Order Meta Transaction Deal Key` FROM `Order Meta Transaction Deal Dimension`  WHERE `Order Key`=%d AND `Deal Component Key`=%d",
                    $this->id,
                    $allowance_data['Deal Component Key']
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        //  print_r($row);


                        if (isset($current_free_no_ordered_items_to_delete[$row['Order Meta Transaction Deal Key']])) {
                            unset($current_free_no_ordered_items_to_delete[$row['Order Meta Transaction Deal Key']]);
                        }

                        $sql = sprintf(
                            "UPDATE  `Order Meta Transaction Deal Dimension`  SET `Bonus Quantity`=%f,`Bonus Product Key`=%d,`Bonus Product ID`=%d ,`Bonus Product Category Key`=%d ,`Bonus Order Transaction Fact Key`=%d WHERE `Order Meta Transaction Deal Key`=%d",

                            $allowance_data['Get Free'],
                            $allowance_data['Product Key'],
                            $allowance_data['Product ID'],
                            $allowance_data['Product Category Key'],
                            $transaction_data['otf_key'],
                            $row['Order Meta Transaction Deal Key']
                        );


                        $this->db->exec($sql);
                    } else {
                        $sql = sprintf(
                            "INSERT INTO `Order Meta Transaction Deal Dimension` (`Order Meta Transaction Deal Type`,`Order Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,
				`Amount Discount`,`Fraction Discount`,`Bonus Quantity`,
				`Bonus Product Key`,`Bonus Product ID`,`Bonus Product Category Key`,`Bonus Order Transaction Fact Key`
				)
			VALUES (%s,%d, %d,%d,%d,%s,%f,%f,%f,%d,%d,%d,%d)  ",
                            prepare_mysql('Get Free No Ordered Product'),
                            $this->id


                            ,
                            $allowance_data['Deal Campaign Key'],
                            $allowance_data['Deal Key'],
                            $allowance_data['Deal Component Key'],
                            prepare_mysql($allowance_data['Deal Info']),
                            0,
                            0,
                            $allowance_data['Get Free'],
                            $allowance_data['Product Key'],
                            $allowance_data['Product ID'],
                            $allowance_data['Product Category Key'],
                            $transaction_data['otf_key']

                        );

                        $this->db->exec($sql);
                    }
                }
            }
        }


        foreach ($current_free_no_ordered_items_to_delete as $current_free_no_ordered_items_to_delete_data) {
        }


        foreach ($current_OTDBs_to_delete as $current_OTDB_to_delete_data) {
            $sql = sprintf(
                'delete from `Order Transaction Deal Bridge` where  `Order Transaction Deal Key`=%d',
                $current_OTDB_to_delete_data['otdk']

            );
            // print $sql;
            $this->db->exec($sql);


            $sql = sprintf(
                "SELECT `Order Transaction Fact Key` FROM `Order Transaction Fact` WHERE   `Order Quantity`=0 and `Order Transaction Fact Key`=%d  ",
                $current_OTDB_to_delete_data['otfk']
            );

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    //print_r($row);


                    $this->delete_transaction($row['Order Transaction Fact Key']);
                }
            }
        }


        foreach ($current_OTF_discounts_to_clear as $otf_key) {
            $sql = sprintf(
                'UPDATE `Order Transaction Fact` SET `Order Transaction Total Discount Amount`=0 , `Order Transaction Amount`=`Order Transaction Gross Amount` WHERE `Order Transaction Fact Key`=%d  ',
                $otf_key
            );
            $this->db->exec($sql);
        }


        foreach ($current_OTF_bonus_to_clear as $otf_key) {
            $sql = sprintf(
                'UPDATE `Order Transaction Fact` OTF  SET  `Order Bonus Quantity`=0 WHERE `Order Transaction Fact Key`=%d ',
                $otf_key
            );

            $this->db->exec($sql);


            $sql = sprintf(
                "SELECT `Order Transaction Fact Key` FROM `Order Transaction Fact` WHERE   `Order Quantity`=0 and `Order Transaction Fact Key`=%d  ",
                $otf_key
            );

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    // print_r($row);


                    $this->delete_transaction($row['Order Transaction Fact Key']);
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }
        }


        $this->fast_update(array('Order Deal Amount Off' => $this->amount_off_value));

        if ($this->amount_off_value > 0) {
            $this->fast_update_json_field('Order Metadata', 'amount_off', json_encode($this->amount_off_allowance_data));
        } else {
            $this->fast_remove_key_from_json_field('Order Metadata', 'amount_off');
        }


        $sql = sprintf(
            "SELECT * FROM `Order Transaction Deal Bridge` WHERE `Order Key`=%d  ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Fraction Discount'] > 0) {
                    $sql = sprintf(
                        'UPDATE `Order Transaction Fact` OTF  SET  `Order Transaction Total Discount Amount`=`Order Transaction Gross Amount`*%f WHERE `Order Transaction Fact Key`=%d ',
                        $row['Fraction Discount'],
                        $row['Order Transaction Fact Key']
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

                if ($row['Bonus Quantity'] > 0) {
                    $sql = sprintf(
                        'UPDATE `Order Transaction Fact` OTF  SET  `Order Bonus Quantity`=%f WHERE `Order Transaction Fact Key`=%d ',
                        $row['Bonus Quantity'],
                        $row['Order Transaction Fact Key']
                    );
                    //print $sql;
                    $this->db->exec($sql);
                }
            }
        }


        foreach ($current_ONPTDBs_to_delete as $current_OTDB_to_delete_data) {
            $sql = sprintf(
                'delete from `Order No Product Transaction Deal Bridge` where  `Order No Product Transaction Deal Key`=%d',
                $current_OTDB_to_delete_data['otdk_key']

            );
            // print $sql;
            $this->db->exec($sql);
        }

        foreach ($current_ONPTDBs_to_delete as $data) {
            $ok = true;

            if (isset($data['ghost']) and $data['ghost'] == 'Yes') {
                $ok = false;
            }

            if ($ok) {
                $tax_category = new TaxCategory($this->db);
                $tax_category->loadWithKey($data['tax_category_key']);

                $sql = sprintf(
                    'UPDATE `Order No Product Transaction Fact` SET `Transaction Total Discount Amount`=0 , `Transaction Net Amount`=`Transaction Gross Amount` ,`Transaction Tax Amount`=%.2f WHERE `Order No Product Transaction Fact Key`=%d  ',
                    $data['onpt_key'],
                    $data['gross_amount'] * $tax_category->get('Tax Category Rate')
                );
                $this->db->exec($sql);
            }
        }
    }

    function update_discounts_no_items($dn_key = false)
    {
        if ($dn_key) {
            return;
        }


        $this->deal_components = [];

        $this->allowance = array(
            'Percentage Off'              => [],
            'Get Free'                    => [],
            'Get Free No Ordered Product' => [],
            'Get Same Free'               => [],
            'Credit'                      => [],
            'No Item Transaction'         => []
        );
        $this->deals     = array(
            'Order' => array(
                'Deal'               => false,
                'Terms'              => false,
                'Deal Multiplicity'  => 0,
                'Terms Multiplicity' => 0
            )

        );


        $this->get_allowances_from_order_trigger($no_items = true);
        $this->get_allowances_from_customer_trigger($no_items = true);
        $this->get_allowances_from_pinned_deal_components($no_items = true);


        $this->apply_no_items_discounts();
        $this->update_totals();
    }

    function apply_no_items_discounts()
    {
        $this->amount_off_value = $this->get('Order Deal Amount Off');


        $current_ONPTDBs_to_delete = [];


        $sql = "select `Transaction Gross Amount`,`Order No Product Transaction Tax Category Key`,`Order No Product Transaction Deal Key`,`Deal Component Key`,ONPTDB.`Order No Product Transaction Fact Key`,`Amount Discount`,ONPTF.`Order No Product Transaction Fact Key` as ONPTF_Key  from `Order No Product Transaction Deal Bridge`  ONPTDB left join `Order No Product Transaction Fact` ONPTF on (ONPTDB.`Order No Product Transaction Fact Key`=ONPTF.`Order No Product Transaction Fact Key`)  
            where ONPTDB.`Order Key`=? and `Deal Component Key`>0 and  `Order No Product Transaction Deal Source`='no_items'";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
                           $this->id
                       ));
        while ($row = $stmt->fetch()) {
            if ($row['Order No Product Transaction Fact Key']) {
                $current_ONPTDBs_to_delete[$row['Order No Product Transaction Fact Key'].'_'.$row['Deal Component Key']] = array(
                    'onpt_key'         => $row['Order No Product Transaction Fact Key'],
                    'otdk_key'         => $row['Order No Product Transaction Deal Key'],
                    'tax_category_key' => $row['Order No Product Transaction Tax Category Key'],
                    'gross_amount'     => $row['Transaction Gross Amount'],
                    'ghost'            => ($row['ONPTF_Key'] == '' ? 'Yes' : 'No')
                );
            }
        }


        // print_r($current_ONPTDBs_to_delete);
        // exit;


        if (isset($this->allowance['Percentage Off'])) {
            foreach ($this->allowance['Percentage Off'] as $otf_key => $allowance_data) {
                if (isset($current_ONPTDBs_to_delete[$otf_key.'_'.$allowance_data['Deal Component Key']])) {
                    unset($current_ONPTDBs_to_delete[$otf_key.'_'.$allowance_data['Deal Component Key']]);
                }
                //todo % discount in charges or shipping


            }
        }
        //print_r($this->allowance);

        if (isset($this->allowance['No Item Transaction'])) {
            foreach ($this->allowance['No Item Transaction'] as $type => $allowance_data) {
                switch ($type) {
                    case 'Amount Off':


                        if ($allowance_data['Amount Off'] > 0 and $this->amount_off_value <= $allowance_data['Amount Off']) {
                            $this->amount_off_value          = $allowance_data['Amount Off'];
                            $this->amount_off_allowance_data = $allowance_data;
                        }


                        break;
                    case 'Charge':
                        //print_r($allowance_data);


                        $sql = sprintf(
                            'SELECT `Order No Product Transaction Fact Key`,`Transaction Gross Amount` FROM  `Order No Product Transaction Fact` OTF  WHERE `Order Key`=%d AND `Transaction Type`=%s  and `Transaction Type Key`=%d ',
                            $this->id,
                            prepare_mysql('Charges'),
                            $allowance_data['Charge Key']
                        );


                        if ($result = $this->db->query($sql)) {
                            foreach ($result as $row) {
                                if (isset($current_ONPTDBs_to_delete[$row['Order No Product Transaction Fact Key'].'_'.$allowance_data['Deal Component Key']])) {
                                    unset($current_ONPTDBs_to_delete[$row['Order No Product Transaction Fact Key'].'_'.$allowance_data['Deal Component Key']]);
                                }

                                if (empty($allowance_data['Pinned'])) {
                                    $pinned = 'No';
                                } else {
                                    $pinned = 'Yes';
                                }


                                $sql = sprintf(
                                    "INSERT INTO `Order No Product Transaction Deal Bridge` (`Order No Product Transaction Fact Key`,`Order Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`,`Order No Product Transaction Deal Pinned`)
					VALUES (%d,%d,%d,%d,%d,%s,%f,%f,%s)  ON DUPLICATE KEY UPDATE `Deal Info`=%s ,`Amount Discount`=%f ,`Fraction Discount`=%f ,`Order No Product Transaction Deal Pinned`=%s",
                                    $row['Order No Product Transaction Fact Key'],
                                    $this->id,
                                    $allowance_data['Deal Campaign Key'],
                                    $allowance_data['Deal Key'],
                                    $allowance_data['Deal Component Key'],
                                    prepare_mysql($allowance_data['Deal Info']),
                                    $row['Transaction Gross Amount'] * $allowance_data['Percentage Off'],
                                    $allowance_data['Percentage Off'],
                                    prepare_mysql($pinned),
                                    prepare_mysql($allowance_data['Deal Info']),
                                    $row['Transaction Gross Amount'] * $allowance_data['Percentage Off'],
                                    $allowance_data['Percentage Off'],
                                    prepare_mysql($pinned)
                                );
                                $this->db->exec($sql);
                                //  print "$sql\n";

                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }


                        break;
                    case 'Shipping':

                        // This one is used for free shipping it maybe never will be used
                        //print_r($allowance_data);

                        if ($type == 'Shipping') {
                            $_type = 'Shipping';
                        } else {
                            $_type = $type;
                        }

                        $sql = sprintf(
                            'SELECT `Order No Product Transaction Fact Key`,`Transaction Gross Amount` FROM  `Order No Product Transaction Fact` OTF  WHERE `Order Key`=%d AND `Transaction Type`=%s ',
                            $this->id,
                            prepare_mysql($_type)
                        );


                        if ($result = $this->db->query($sql)) {
                            foreach ($result as $row) {
                                if (isset($current_ONPTDBs_to_delete[$row['Order No Product Transaction Fact Key'].'_'.$allowance_data['Deal Component Key']])) {
                                    unset($current_ONPTDBs_to_delete[$row['Order No Product Transaction Fact Key'].'_'.$allowance_data['Deal Component Key']]);
                                }

                                if (empty($allowance_data['Pinned'])) {
                                    $pinned = 'No';
                                } else {
                                    $pinned = 'Yes';
                                }


                                $sql = sprintf(
                                    "INSERT INTO `Order No Product Transaction Deal Bridge` (`Order No Product Transaction Fact Key`,`Order Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`,`Order No Product Transaction Deal Pinned`)
					VALUES (%d,%d,%d,%d,%d,%s,%f,%f,%s)  ON DUPLICATE KEY UPDATE `Deal Info`=%s ,`Amount Discount`=%f ,`Fraction Discount`=%f ,`Order No Product Transaction Deal Pinned`=%s",
                                    $row['Order No Product Transaction Fact Key'],
                                    $this->id,
                                    $allowance_data['Deal Campaign Key'],
                                    $allowance_data['Deal Key'],
                                    $allowance_data['Deal Component Key'],
                                    prepare_mysql($allowance_data['Deal Info']),
                                    $row['Transaction Gross Amount'] * $allowance_data['Percentage Off'],
                                    $allowance_data['Percentage Off'],
                                    prepare_mysql($pinned),
                                    prepare_mysql($allowance_data['Deal Info']),
                                    $row['Transaction Gross Amount'] * $allowance_data['Percentage Off'],
                                    $allowance_data['Percentage Off'],
                                    prepare_mysql($pinned)
                                );
                                $this->db->exec($sql);
                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }


                        break;


                    case 'Shipping Off':

                        //print_r($allowance_data);


                        $_type = 'Shipping';


                        $sql = sprintf(
                            'SELECT `Order No Product Transaction Fact Key`,`Transaction Gross Amount` FROM  `Order No Product Transaction Fact` OTF  WHERE `Order Key`=%d AND `Transaction Type`=%s ',
                            $this->id,
                            prepare_mysql($_type)
                        );


                        if ($result = $this->db->query($sql)) {
                            foreach ($result as $row) {
                                $_data = array(
                                    'shipping_zone_schema_key' => $allowance_data['Shipping Zone Schema Key'],
                                    'Order Data'               => array(
                                        'Order Items Net Amount'                      => $this->data['Order Items Net Amount'],
                                        'Order Estimated Weight'                      => $this->data['Order Estimated Weight'],
                                        'Order Delivery Address Postal Code'          => $this->data['Order Delivery Address Postal Code'],
                                        'Order Delivery Address Country 2 Alpha Code' => $this->data['Order Delivery Address Country 2 Alpha Code'],
                                    )


                                );


                                include_once 'nano_services/shipping_for_order.ns.php';

                                $shipping_data = (new shipping_for_order($this->db))->get($_data);


                                if ($shipping_data['shipping_zone_schema_key'] == $allowance_data['Shipping Zone Schema Key']) {
                                    //print_r($shipping_data);

                                    //  exit;

                                    if (isset($current_ONPTDBs_to_delete[$row['Order No Product Transaction Fact Key'].'_'.$allowance_data['Deal Component Key']])) {
                                        unset($current_ONPTDBs_to_delete[$row['Order No Product Transaction Fact Key'].'_'.$allowance_data['Deal Component Key']]);
                                    }

                                    if (empty($allowance_data['Pinned'])) {
                                        $pinned = 'No';
                                    } else {
                                        $pinned = 'Yes';
                                    }


                                    $amount_discount = $row['Transaction Gross Amount'] - ($shipping_data['price'] == '' ? 0 : $shipping_data['price']);
                                    if ($row['Transaction Gross Amount'] == 0) {
                                        $percentage_off = 0;
                                    } else {
                                        $percentage_off = $amount_discount / $row['Transaction Gross Amount'];
                                    }


                                    $sql = sprintf(
                                        "INSERT INTO `Order No Product Transaction Deal Bridge` (`Order No Product Transaction Fact Key`,`Order Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`,`Order No Product Transaction Deal Pinned`)
					VALUES (%d,%d,%d,%d,%d,%s,%f,%f,%s)  ON DUPLICATE KEY UPDATE `Deal Info`=%s ,`Amount Discount`=%f ,`Fraction Discount`=%f ,`Order No Product Transaction Deal Pinned`=%s",
                                        $row['Order No Product Transaction Fact Key'],
                                        $this->id,
                                        $allowance_data['Deal Campaign Key'],
                                        $allowance_data['Deal Key'],
                                        $allowance_data['Deal Component Key'],
                                        prepare_mysql($allowance_data['Deal Info']),
                                        $amount_discount,
                                        $percentage_off,
                                        prepare_mysql($pinned),
                                        prepare_mysql($allowance_data['Deal Info']),
                                        $amount_discount,
                                        $percentage_off,
                                        prepare_mysql($pinned)
                                    );

                                    //  print $sql;
                                    // exit;
                                    $this->db->exec($sql);
                                }
                            }
                        }


                        break;
                }
            }
        }

        $this->fast_update(array('Order Deal Amount Off' => $this->amount_off_value));

        if ($this->amount_off_value == 0) {
            $this->fast_remove_key_from_json_field('Order Metadata', 'amount_off');
        } else {
            if ($this->amount_off_allowance_data != '') {
                $this->fast_update_json_field('Order Metadata', 'amount_off', json_encode($this->amount_off_allowance_data));
            }
        }


        // print_r($current_ONPTDBs_to_delete);


        foreach ($current_ONPTDBs_to_delete as $current_OTDB_to_delete_data) {
            $sql = sprintf(
                'delete from `Order No Product Transaction Deal Bridge` where  `Order No Product Transaction Deal Key`=%d',
                $current_OTDB_to_delete_data['otdk_key']

            );
            // print $sql;
            $this->db->exec($sql);
        }


        foreach ($current_ONPTDBs_to_delete as $data) {
            $ok = true;

            if (isset($data['ghost']) and $data['ghost'] == 'Yes') {
                $ok = false;
            }

            if ($ok) {
                $tax_category = new TaxCategory($this->db);
                $tax_category->loadWithKey($data['tax_category_key']);

                $sql = sprintf(
                    'UPDATE `Order No Product Transaction Fact` SET `Transaction Total Discount Amount`=0 , `Transaction Net Amount`=`Transaction Gross Amount` ,`Transaction Tax Amount`=%.2f WHERE `Order No Product Transaction Fact Key`=%d  ',
                    $data['onpt_key'],
                    $data['gross_amount'] * $tax_category->get('Tax Category Rate')
                );
                $this->db->exec($sql);
            }
        }

        $onptf_discounts_data = [];
        $sql                  = sprintf(
            "SELECT * FROM `Order No Product Transaction Deal Bridge` B LEFT JOIN `Order No Product Transaction Fact`OTF ON (OTF.`Order No Product Transaction Fact Key`=B.`Order No Product Transaction Fact Key`)  WHERE B.`Order Key`=%d  order by `Order No Product Transaction Deal Source` ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                // print_r($row);

                if ($row['Fraction Discount'] > 0) {
                    if (empty($onptf_discounts_data[$row['Order No Product Transaction Fact Key']])) {
                        $onptf_discounts_data[$row['Order No Product Transaction Fact Key']] = $row;
                    } else {
                        if ($onptf_discounts_data[$row['Order No Product Transaction Fact Key']]['Amount Discount'] < $row['Amount Discount']) {
                            $onptf_discounts_data[$row['Order No Product Transaction Fact Key']] = $row;
                        }
                    }
                }
            }
        }


        foreach ($onptf_discounts_data as $row) {
            $sql = sprintf(
                'UPDATE `Order No Product Transaction Fact` OTF  SET `Transaction Total Discount Amount`=%.2f ,`Transaction Net Amount`=%.2f,`Transaction Tax Amount`=%.2f  WHERE `Order No Product Transaction Fact Key`=%d ',
                $row['Amount Discount'],
                $row['Transaction Net Amount'] * (1 - $row['Fraction Discount']),
                $row['Transaction Tax Amount'] * (1 - $row['Fraction Discount'])

                ,
                $row['Order No Product Transaction Fact Key']
            );

            $this->db->exec($sql);
        }
    }

    function update_deal_bridge()
    {
        $sql = "INSERT INTO `Order Deal Bridge` VALUES(?,?,?,?,'Yes','Yes') ON DUPLICATE KEY UPDATE `Used`='Yes'";
        $pst = $this->db->prepare($sql);

        $sql = sprintf(
            "DELETE FROM `Order Deal Bridge` WHERE `Order Key`=%d",
            $this->id
        );
        $this->db->exec($sql);

        $sql = "SELECT `Deal Campaign Key`,`Deal Component Key`, `Deal Key` FROM  `Order Transaction Deal Bridge`  WHERE`Order Key`=?  group by `Deal Component Key`";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
                           $this->id
                       ));

        while ($row = $stmt->fetch()) {
            $pst->execute([
                              $this->id,
                              $row['Deal Campaign Key'],
                              $row['Deal Key'],
                              $row['Deal Component Key']
                          ]);
        }


        if ($this->amount_off_allowance_data) {
            $pst->execute([
                              $this->id,
                              $this->amount_off_allowance_data['Deal Campaign Key'],
                              $this->amount_off_allowance_data['Deal Key'],
                              $this->amount_off_allowance_data['Deal Component Key']
                          ]);
        }
    }

    function get_used_deals(): array
    {
        $campaigns       = [];
        $deals           = [];
        $deal_components = [];

        $stmt = $this->db->prepare("select group_concat(distinct `Deal Campaign Key`) campaigns,group_concat(distinct  `Deal Key`) deals,group_concat(distinct`Deal Component Key`) deal_components FROM `Order Deal Bridge` where `Order Key` = ?");
        if ($stmt->execute(array($this->id))) {
            while ($row = $stmt->fetch()) {
                $campaigns       = preg_split('/\,/', $row['campaigns']);
                $deals           = preg_split('/\,/', $row['deals']);
                $deal_components = preg_split('/\,/', $row['deal_components']);
            }
        }

        return array(
            $campaigns,
            $deals,
            $deal_components
        );
    }

    function update_transaction_discount_percentage($otf_key, $percentage)
    {
        if (!is_numeric($percentage)) {
            $this->error = true;
            $this->msg   = _('Wrong percentage');

            return;
        } elseif ($percentage < 0) {
            $this->error = true;
            $this->msg   = _('Percentage must be a number between 0 and 100');

            return;
        } elseif ($percentage > 100) {
            $this->error = true;
            $this->msg   = _('Percentage must be a number between 0 and 100');

            return;
        }


        $sql = sprintf(
            'SELECT `Product Key`,`Order Transaction Fact Key`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount` FROM  `Order Transaction Fact`  WHERE `Order Transaction Fact Key`=%d ',
            $otf_key
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                if (!is_numeric($row['Order Transaction Gross Amount'])) {
                    $this->error = true;
                    $this->msg   = 'Item amount is not numeric!, submit a ticket';

                    return;
                }

                $discount_amount = round(
                    ($row['Order Transaction Gross Amount']) * $percentage / 100,
                    2
                );


                return $this->update_transaction_discount_amount(
                    $otf_key,
                    $discount_amount
                );
            } else {
                $this->error = true;
                $this->msg   = 'otf not found';
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }
    }

    function update_transaction_discount_amount($otf_key, $discount_amount, $deal_campaign_key = 0, $deal_key = 0, $deal_component_key = 0)
    {
        $deal_info = '';

        $sql = sprintf(
            'SELECT `Order Transaction Amount`,OTF.`Product ID`,`Product Name`,`Order Quantity`,`Product Key`,`Order Transaction Fact Key`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount` FROM  `Order Transaction Fact` OTF LEFT JOIN `Product Dimension` P ON  (P.`Product ID`=OTF.`Product ID`) WHERE `Order Transaction Fact Key`=%d ',
            $otf_key
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                if ($discount_amount == $row['Order Transaction Total Discount Amount'] or $row['Order Transaction Gross Amount'] == 0) {
                    $this->msg   = 'Nothing to Change';
                    $return_data = array(
                        'updated'             => true,
                        'otf_key'             => $otf_key,
                        'description'         => $row['Product Name'].' <span class="deal_info">'.$deal_info.'</span>',
                        'discount_percentage' => percentage(
                            $discount_amount,
                            $row['Order Transaction Gross Amount'],
                            $fixed = 1,
                            $error_txt = 'NA',
                            $psign = ''
                        ),
                        'to_charge'           => money(
                            $row['Order Transaction Amount'],
                            $this->data['Order Currency']
                        ),
                        'qty'                 => $row['Order Quantity'],
                        'bonus qty'           => 0
                    );

                    return $return_data;
                }


                $sql = sprintf(
                    "DELETE FROM `Order Transaction Deal Bridge` WHERE `Order Transaction Fact Key` =%d",
                    $otf_key
                );

                $this->db->exec($sql);

                $old_net_amount = $row['Order Transaction Gross Amount'];
                $net_amount     = $row['Order Transaction Gross Amount'] - $discount_amount;

                $sql = sprintf(
                    'UPDATE `Order Transaction Fact` OTF SET `Order Transaction Amount`=%.2f, `Order Transaction Total Discount Amount`=%f WHERE `Order Transaction Fact Key`=%d ',
                    $net_amount,
                    $discount_amount,
                    $otf_key
                );
                $this->db->exec($sql);
                //print "$sql\n";

                $deal_info = '';
                if ($discount_amount > 0) {
                    $deal_info = sprintf(
                        _('%s off'),
                        percentage(
                            $discount_amount,
                            $row['Order Transaction Gross Amount']
                        )
                    );

                    $sql = sprintf(
                        "INSERT INTO `Order Transaction Deal Bridge` (`Order Transaction Fact Key`,`Order Key`,`Product Key`,`Product ID`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`,`Bonus Quantity`,`Order Transaction Deal Metadata`) VALUES (%d,%d,%d,%d,%d,%d,%d,%s,%f,%f,0,'{}')",
                        $row['Order Transaction Fact Key'],
                        $this->id,
                        $row['Product Key'],
                        $row['Product ID'],
                        $deal_campaign_key,
                        $deal_key,
                        $deal_component_key,
                        prepare_mysql($deal_info, false),
                        $discount_amount,
                        ($discount_amount / $row['Order Transaction Gross Amount'])
                    );

                    $this->db->exec($sql);
                    $this->updated = true;
                }


                $this->update_totals();

                // $this->apply_payment_from_customer_account();


                $operations = [];


                $this->update_metadata = array(

                    'class_html'  => array(
                        'Order_State'      => $this->get('State'),
                        'Items_Net_Amount' => $this->get('Items Net Amount'),

                        'Items_Discount_Amount'         => $this->get('Items Discount Amount'),
                        'Items_Discount_Percentage'     => $this->get('Items Discount Percentage'),
                        'Shipping_Net_Amount'           => $this->get('Shipping Net Amount'),
                        'Charges_Net_Amount'            => $this->get('Charges Net Amount'),
                        'Total_Net_Amount'              => $this->get('Total Net Amount'),
                        'Total_Tax_Amount'              => $this->get('Total Tax Amount'),
                        'Total_Amount'                  => $this->get('Total Amount'),
                        'Total_Amount_Account_Currency' => $this->get('Total Amount Account Currency'),
                        'To_Pay_Amount'                 => $this->get('To Pay Amount'),
                        'Payments_Amount'               => $this->get('Payments Amount'),


                        'Order_Number_items'            => $this->get('Number Items'),
                        'Order_Number_Items_with_Deals' => $this->get('Number Items with Deals')

                    ),
                    'operations'  => $operations,
                    'state_index' => $this->get('State Index'),
                    'to_pay'      => $this->get('Order To Pay Amount'),
                    'total'       => $this->get('Order Total Amount'),
                    'payments'    => $this->get('Order Payments Amount'),
                    'items'       => $this->get('Order Number Items'),
                    'shipping'    => $this->get('Order Shipping Net Amount'),
                    'charges'     => $this->get('Order Charges Net Amount'),

                );


                if (in_array($this->get('Order State'), array(
                    'Cancelled',
                    'Approved',
                    'Dispatched',
                ))) {
                    $discounts_class = '';
                    $discounts_input = '';
                } else {
                    $discounts_class = 'button';
                    $discounts_input = sprintf(
                        '<span class="hide order_item_percentage_discount_form" data-settings=\'{ "field": "Percentage" ,"transaction_key":"%d"  }\'   ><input class="order_item_percentage_discount_input" style="width: 70px" value="%s"> <i class="fa save fa-cloud" aria-hidden="true"></i></span>',
                        $otf_key,
                        percentage($discount_amount, $row['Order Transaction Gross Amount'])
                    );
                }
                $discounts = $discounts_input.'<span class="order_item_percentage_discount   '.$discounts_class.' '.($discount_amount == 0 ? 'super_discreet' : '').'"><span style="padding-right:5px">'.percentage(
                        $discount_amount,
                        $row['Order Transaction Gross Amount']
                    ).'</span> <span class="'.($discount_amount == 0 ? 'hide' : '').'">'.money($discount_amount, $this->data['Order Currency']).'</span></span>';


                return array(
                    'updated'             => true,
                    'otf_key'             => $otf_key,
                    'to_charge'           => money($net_amount, $this->data['Order Currency']),
                    'item_discounts'      => $discounts,
                    'net_amount'          => $net_amount,
                    'delta_net_amount'    => $net_amount - $old_net_amount,
                    'qty'                 => $row['Order Quantity'],
                    'delta_qty'           => 0,
                    'bonus qty'           => 0,
                    'discount_percentage' => percentage($discount_amount, $row['Order Transaction Gross Amount'], $fixed = 1, $error_txt = 'NA', $psign = ''),
                );
            } else {
                $this->error = true;
                $this->msg   = 'otf not found';
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }
    }

    function get_discounted_products()
    {
        $sql = sprintf(
            'SELECT  `Product Key` FROM   `Order Transaction Deal Bridge`   WHERE `Order Key`=%d  GROUP BY `Product Key` ',
            $this->id
        );
        //print "$sql\n";
        $discounted_products = [];


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

    function voucher_formatted_info()
    {
        $voucher_formatted_info = '';

        $vouchers_data = $this->get_vouchers('data');

        foreach ($vouchers_data as $voucher_data) {
            $voucher_formatted_info .= '<div ><span class="voucher_code" >'.$voucher_data['Voucher Code'].'</span> <span class="deal_name">'.$voucher_data['Deal Name Label'].'</span>  <span class="deal_term">'.$voucher_data['Deal Term Label'].'</span>  <span class="deal_allowance">'.$voucher_data['Deal Allowance Label'].'</span>   </div>';
        };

        return $voucher_formatted_info;
    }

    function get_vouchers($scope = 'keys')
    {
        $vouchers = [];


        if ($scope == 'data') {
            $sql = "SELECT V.`Voucher Key`,`Voucher Code`,`Deal Name Label`,`Deal Term Label`,`Deal Allowance Label` FROM `Voucher Order Bridge` B  left join `Voucher Dimension` V on (V.`Voucher Key`=B.`Voucher Key`) left join `Deal Dimension` D on (D.`Deal Key`=B.`Deal Key`)  WHERE `Order Key`=?  ";
        } else {
            $sql = "SELECT `Voucher Key` FROM `Voucher Order Bridge` WHERE `Order Key`=?  ";
        }

        $stmt = $this->db->prepare($sql);
        if ($stmt->execute(array(
                               $this->id
                           ))) {
            while ($row = $stmt->fetch()) {
                if ($scope == 'objects') {
                    $vouchers[$row['Voucher Key']] = get_object('Voucher', $row['Voucher Key']);
                } elseif ($scope == 'keys') {
                    $vouchers[$row['Voucher Key']] = $row['Voucher Key'];
                } else {
                    $vouchers[$row['Voucher Key']] = $row;
                }
            }
        }


        return $vouchers;
    }


}

