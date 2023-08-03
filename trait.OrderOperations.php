<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 August 2017 at 08:29:30 CEST, Tranava, Slovakia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/
use Aurora\Models\Utils\TaxCategory;

trait OrderOperations
{


    /**
     * @throws \Exception
     */
    function create($data)
    {
        $account = get_object('Account', 1);


        $this->editor           = $data['editor'];
        $this->public_id_format = $data['public_id_format'];
        unset($data['editor']);
        unset($data['public_id_format']);


        if (isset($data['Recargo Equivalencia'])) {
            if ($data['Recargo Equivalencia'] == 'Yes') {
                $recargo_equivalencia = $data['Recargo Equivalencia'];
            }
            unset($data['Recargo Equivalencia']);
        }


        $this->data = $this->base_data();
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $this->data[$key] = _trim($value);
            }
        }


        $this->data['Order Type'] = $data['Order Type'];
        if (!empty($data['Order Date'])) {
            $this->data['Order Date'] = $data['Order Date'];
        } else {
            $this->data['Order Date'] = gmdate('Y-m-d H:i:s');
        }
        $this->data['Order Created Date'] = $this->data['Order Date'];

        $this->data['Order Last Updated by Customer'] = $this->data['Order Date'];


        $this->data['Order State']                       = 'InBasket';
        $this->data['Order Current XHTML Payment State'] = _('Waiting for payment');


        if (isset($data['Order Payment Method'])) {
            $this->data['Order Payment Method'] = $data['Order Payment Method'];
        } else {
            $this->data['Order Payment Method'] = 'Unknown';
        }


        $this->data['Order Customer Message'] = '';


        if (isset($data['Order Original Data MIME Type'])) {
            $this->data['Order Original Data MIME Type'] = $data['Order Original Data MIME Type'];
        } else {
            $this->data['Order Original Data MIME Type'] = 'none';
        }

        if (isset($data['Order Original Metadata'])) {
            $this->data['Order Original Metadata'] = $data['Order Original Metadata'];
        } else {
            $this->data['Order Original Metadata'] = '';
        }

        if (isset($data['Order Original Data Source'])) {
            $this->data['Order Original Data Source'] = $data['Order Original Data Source'];
        } else {
            $this->data['Order Original Data Source'] = 'Other';
        }


        if (isset($data['Order Original Data Filename'])) {
            $this->data['Order Original Data Filename'] = $data['Order Original Data Filename'];
        } else {
            $this->data['Order Original Data Filename'] = 'Other';
        }


        $this->data['Order Currency Exchange'] = 1;
        $this->data['Order Delivery Data']     = '{}';
        $this->data['Order Pastpay Data']     = '{}';


        if ($this->data['Order Currency'] != $account->get('Currency Code')) {
            include_once 'utils/currency_functions.php';
            $exchange                              = currency_conversion($this->db, $this->data['Order Currency'], $account->get('Currency Code'), '1 hour');
            $this->data['Order Currency Exchange'] = $exchange;
        }

        $this->data['Order Main Source Type'] = 'Call';
        if (isset($data['Order Main Source Type']) and preg_match('/^(Internet|Call|Store|Unknown|Email|Fax)$/i', $data['Order Main Source Type'])) {
            $this->data['Order Main Source Type'] = $data['Order Main Source Type'];
        }


        $sql = sprintf(
            "UPDATE `Store Dimension` SET `Store Order Last Order ID` = LAST_INSERT_ID(`Store Order Last Order ID` + 1) WHERE `Store Key`=%d",
            $this->data['Order Store Key']
        );

        $this->db->exec($sql);

        $public_id = $this->db->lastInsertId();

        $this->data['Order Public ID'] = sprintf($this->public_id_format, $public_id);

        $number = strtolower($this->data['Order Public ID']);
        if (preg_match("/^\d+/", $number, $match)) {
            $part_number                 = $match[0];
            $this->data['Order File As'] = preg_replace('/^\d+/', sprintf("%012d", $part_number), $number);
        } elseif (preg_match("/\d+$/", $number, $match)) {
            $part_number                 = $match[0];
            $this->data['Order File As'] = preg_replace('/\d+$/', sprintf("%012d", $part_number), $number);
        } else {
            $this->data['Order File As'] = $number;
        }


        $this->data['Order Items Gross Amount']    = 0;
        $this->data['Order Items Discount Amount'] = 0;


        $this->data['Order Metadata'] = '{}';


        $sql = sprintf(
            "INSERT INTO `Order Dimension` (%s) values (%s)",
            '`'.join('`,`', array_keys($this->data)).'`',
            join(',', array_fill(0, count($this->data), '?'))
        );

        $stmt = $this->db->prepare($sql);

        $i = 1;
        foreach ($this->data as $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {
            $this->id = $this->db->lastInsertId();

            if (!$this->id) {
                throw new Exception('Error inserting '.$this->table_name);
            }

            //$this->fast_update_json_field('Order Metadata', 'tax_name', $tax_name);
            //$this->fast_update_json_field('Order Metadata', 'why_tax', $reason_tax_code_selected);
            $this->get_data('id', $this->id);

            if (isset($recargo_equivalencia)) {
                $this->fast_update_json_field('Order Metadata', 'RE', 'Yes');

            }
            $this->update_tax();

            $this->update_charges();

            if ($this->data['Order Shipping Method'] == 'Calculated') {
                $this->update_shipping();
            }

            $this->update_totals();


            $sql = sprintf(
                "UPDATE `Deal Component Dimension` SET `Deal Component Allowance Target Key`=%d WHERE `Deal Component Terms Type`='Next Order' AND  `Deal Component Trigger`='Customer' AND `Deal Component Trigger Key`=%d AND `Deal Component Allowance Target Key`=0 AND `Deal Component Status`='Active' ",
                $this->id,
                $this->data['Order Customer Key']
            );

            $this->db->exec($sql);


            $history_data = array(
                'History Abstract' => _('Order created'),
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

            $this->fork_index_elastic_search();
        } else {
            $arr = $stmt->errorInfo();
            print_r($arr);

            print_r($data);

            exit ("Error, can't  create order ");
        }
    }


    function add_basket_history($data)
    {
        $sql = 'INSERT INTO `Order Basket History Dimension`  (`Order Basket History Date`,
                                               `Order Basket History Order Transaction Key`,
                                               `Order Basket History Website Key`,
                                               `Order Basket History Store Key`,
                                               `Order Basket History Customer Key`,
                                               `Order Basket History Order Key`,
                                               
                                               `Order Basket History Webpage Key`,
                                               `Order Basket History Product ID`,
                                               `Order Basket History Quantity Delta`,
                                               
                                               `Order Basket History Quantity`,
                                               `Order Basket History Net Amount Delta`,
                                               `Order Basket History Net Amount`,`Order Basket History Source`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?) ';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
                           gmdate('Y-m-d H:i:s'),
                           $data['otf_key'],
                           $this->data['Order Website Key'],

                           $this->data['Order Store Key'],
                           $this->data['Order Customer Key'],
                           $this->id,

                           $data['webpage_key'],
                           $data['product_id'],
                           $data['quantity_delta'],

                           $data['quantity'],
                           $data['net_amount_delta'],
                           $data['net_amount'],

                           $data['page_section_type']
                       ));
    }

    function get_field_label($field)
    {
        switch ($field) {
            default:
                $label = $field;
        }

        return $label;
    }

    function update_for_collection($value, $options = false)
    {
        if ($this->get('State Index') >= 90 or $this->get('State Index') <= 0) {
            return;
        }


        if ($value != 'Yes') {
            $value = 'No';
        }

        if ($value == 'Yes') {
            $store = get_object('Store', $this->data['Order Store Key']);
            if ($store->get('Store Collect Address Country 2 Alpha Code') == '') {
                $this->error = true;
                $this->msg   = _('Collection address not configured');

                return;
            }
        }


        if ($value == 'Yes') {
            $address_data = array(
                'Address Recipient'            => '',
                'Address Organization'         => $store->get('Store Name'),
                'Address Line 1'               => $store->get('Store Collect Address Line 1'),
                'Address Line 2'               => $store->get('Store Collect Address Line 2'),
                'Address Sorting Code'         => $store->get('Store Collect Address Sorting Code'),
                'Address Postal Code'          => $store->get('Store Collect Address Postal Code'),
                'Address Dependent Locality'   => $store->get('Store Collect Address Dependent Locality'),
                'Address Locality'             => $store->get('Store Collect Address Locality'),
                'Address Administrative Area'  => $store->get('Store Collect Address Administrative Area'),
                'Address Country 2 Alpha Code' => $store->get('Store Collect Address Country 2 Alpha Code'),

            );
        } else {
            $customer = get_object('Customer', $this->get('Order Customer Key'));

            $address_data = array(
                'Address Recipient'            => $customer->get('Customer Main Contact Name'),
                'Address Organization'         => $customer->get('Customer Company Name'),
                'Address Line 1'               => $customer->get('Customer Delivery Address Line 1'),
                'Address Line 2'               => $customer->get('Customer Delivery Address Line 2'),
                'Address Sorting Code'         => $customer->get('Customer Delivery Address Sorting Code'),
                'Address Postal Code'          => $customer->get('Customer Delivery Address Postal Code'),
                'Address Dependent Locality'   => $customer->get('Customer Delivery Address Dependent Locality'),
                'Address Locality'             => $customer->get('Customer Delivery Address Locality'),
                'Address Administrative Area'  => $customer->get('Customer Delivery Address Administrative Area'),
                'Address Country 2 Alpha Code' => $customer->get('Customer Delivery Address Country 2 Alpha Code'),

            );
        }
        $this->update_address('Delivery', $address_data, $options);


        $this->update_field('Order For Collection', $value, $options);

        $this->update_shipping();
        $this->update_tax();
        $this->update_totals();
    }


    function cancel($note = '', $fork = true, $process_stats = true): bool
    {
        if ($this->data['Order State'] == 'Dispatched') {
            $this->error = true;
            $this->msg   = _('Order can not be cancelled, because has already been dispatched');

            return false;
        }

        if ($this->data['Order State'] == 'Cancelled') {
            $this->error = true;
            $this->msg   = _('Order is already cancelled');

            return false;
        }


        if ($this->data['Order Payments Amount'] != 0) {
            $this->error = true;
            $this->msg   = _('Payments must be refunded or voided before cancel the order');

            return false;
        }

        $date = gmdate('Y-m-d H:i:s');

        $this->data['Order Cancelled Date'] = $date;
        $this->data['Order Cancel Note']    = $note;
        $this->data['Order Payment State']  = 'NA';
        $this->data['Order State']          = 'Cancelled';


        $this->data['Order Invoiced Balance Net Amount']               = 0;
        $this->data['Order Invoiced Balance Tax Amount']               = 0;
        $this->data['Order Invoiced Outstanding Balance Total Amount'] = 0;
        $this->data['Order Invoiced Outstanding Balance Net Amount']   = 0;
        $this->data['Order Invoiced Outstanding Balance Tax Amount']   = 0;
        $this->data['Order Balance Net Amount']                        = 0;
        $this->data['Order Balance Tax Amount']                        = 0;
        $this->data['Order Balance Total Amount']                      = 0;


        $this->data['Order To Pay Amount'] = round(
            $this->data['Order Balance Total Amount'] - $this->data['Order Payments Amount'],
            2
        );


        if($this->data['hokodo_order_id']){
            $this->cancel_hokodo_payment();
        }


        $sql = "UPDATE `Order Dimension` SET  `Order Cancelled Date`=?, `Order State`=?,`Order Payment State`='NA',`Order To Pay Amount`=?,`Order Cancel Note`=?,
				`Order Balance Net Amount`=0,`Order Balance tax Amount`=0,`Order Balance Total Amount`=0,`Order Items Cost`=0,
				`Order Invoiced Balance Net Amount`=0,`Order Invoiced Balance Tax Amount`=0,`Order Invoiced Balance Total Amount`=0 ,`Order Invoiced Outstanding Balance Net Amount`=0,`Order Invoiced Outstanding Balance Tax Amount`=0,`Order Invoiced Outstanding Balance Total Amount`=0,`Order Invoiced Profit Amount`=0
				WHERE `Order Key`=?";


        $this->db->prepare($sql)->execute(array(
                                              $this->data['Order Cancelled Date'],
                                              $this->data['Order State'],
                                              round($this->data['Order To Pay Amount'], 2),
                                              $this->data['Order Cancel Note'],

                                              $this->id
                                          ));


        $sql = sprintf(
            "UPDATE `Order Transaction Fact` SET  `Delivery Note Key`=NULL,`Invoice Key`=NULL, `Consolidated`='Yes',`Current Dispatching State`=%s ,`Cost Supplier`=0  WHERE `Order Key`=%d ",
            prepare_mysql('Cancelled'),
            $this->id
        );
        $this->db->exec($sql);

        $sql = sprintf(

            "UPDATE `Order Transaction Fact` SET   `Delivery Note Quantity`=0, `No Shipped Due Out of Stock`=0,`Order Transaction Out of Stock Amount`=0 WHERE `Order Key`=%d ",

            $this->id
        );
        $this->db->exec($sql);


        $sql = sprintf(
            "UPDATE `Order No Product Transaction Fact` SET `Delivery Note Date`=NULL,`Delivery Note Key`=NULL,`State`=%s ,`Consolidated`='Yes' WHERE `Order Key`=%d ",
            prepare_mysql('Cancelled'),
            $this->id
        );
        $this->db->exec($sql);


        $history_data = array(
            'History Abstract' => _('Order cancelled').($note != '' ? ', '.$note : ''),
            'History Details'  => '',
        );
        $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);


        $account = get_object('Account', '');





        if ($fork) {
            require_once 'utils/new_fork.php';
            new_housekeeping_fork(
                'au_housekeeping',
                array(
                    'type'      => 'order_cancelled',
                    'order_key' => $this->id,


                    'editor' => $this->editor
                ),
                $account->get('Account Code'),
                $this->db
            );
        } else {
            $customer = get_object('Customer', $this->get('Order Customer Key'));
            $customer->update_orders();


            if ($process_stats) {
                $store = get_object('Store', $this->get('Order Store Key'));

                $sql = sprintf("SELECT `Transaction Type Key` FROM `Order No Product Transaction Fact` WHERE `Transaction Type`='Charges' AND   `Order Key`=%d  ", $this->id);

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        /**
                         * @var $charge \Charge
                         */
                        $charge = get_object('Charge', $row['Transaction Type Key']);
                        $charge->update_charge_usage();
                    }
                }


                $store->update_orders();
                $account->update_orders();

                $deals     = array();
                $campaigns = array();
                $sql       = sprintf(
                    "SELECT `Deal Component Key`,`Deal Key`,`Deal Campaign Key` FROM  `Order Deal Bridge` WHERE `Order Key`=%d",
                    $this->id
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        /**
                         * @var $component \DealComponent
                         */
                        $component = get_object('DealComponent', $row['Deal Component Key']);
                        $component->update_usage();
                        $deals[$row['Deal Key']]              = $row['Deal Key'];
                        $campaigns[$row['Deal Campaign Key']] = $row['Deal Campaign Key'];
                    }
                }


                foreach ($deals as $deal_key) {
                    /**
                     * @var $deal \Deal
                     */
                    $deal = get_object('Deal', $deal_key);
                    $deal->update_usage();
                }

                foreach ($campaigns as $campaign_key) {
                    $campaign = get_object('DealCampaign', $campaign_key);
                    $campaign->update_usage();
                }
            }
        }

        $this->fork_index_elastic_search();


        if($this->data['Order Type']=='FulfilmentRent'){
            $customer         = get_object('Customer_Fulfilment', $this->data['Order Customer Key']);
            $customer->editor = $this->editor;

            $customer->update_rent_order();
        }

        return true;
    }

    function cancel_hokodo_payment(){

        $db=$this->db;
        $store   = get_object('Store', $this->get('Order Store Key'));
        $website = get_object('Website', $store->get('Store Website Key'));
        $api_key = $website->get_api_key('Hokodo');

        $order=$this;

        $items=[];
        $sql  = "select * from `Order Transaction Fact` OTF  left join `Product Dimension` P  on (OTF.`Product ID`=P.`Product Id`) where `Order Key`=? 
                
                                                                                                                    and `Order Quantity`>0
                                                                                                                    and `Order Transaction Amount`!=0 ";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            [
                $order->id
            ]
        );
        while ($row = $stmt->fetch()) {
            $item_total  = floor(100 * ($row['Order Transaction Amount'] + ($row['Order Transaction Amount'] * $row['Transaction Tax Rate'])));
            $item_tax    = floor(100 * $row['Order Transaction Amount'] * $row['Transaction Tax Rate']);


            $items[] = [
                "item_id"            => $row['Order Transaction Fact Key'],
                "quantity"           => $row['Order Quantity'],
                "unit_price"         => floor($item_total / $row['Order Quantity']),
                "total_amount"       => $item_total,
                "tax_amount"         => $item_tax,
            ];
        }

        $sql  = "select * from `Order No Product Transaction Fact` OTF  where `Order Key`=? and (`Transaction Net Amount`+`Transaction Tax Amount`)>0
                                                                                                                   ";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            [
                $order->id
            ]
        );
        while ($row = $stmt->fetch()) {



            $item_total  = floor(100 * ($row['Transaction Net Amount'] + $row['Transaction Tax Amount']));
            $item_tax    = floor(100 * $row['Transaction Tax Amount']);



            $items[] = [
                "item_id"            => 'np-'.$row['Order No Product Transaction Fact Key'],
                "quantity"           => 1,
                "total_amount"       => $item_total,
                "tax_amount"         => $item_tax,

            ];
        }
        // add discounts

        if ($this->get('Order Deal Amount Off') != '' and $this->get('Order Deal Amount Off') > 0) {


            $discount_net = -$this->get('Order Deal Amount Off');

            $tax_category = new TaxCategory($db);
            $tax_category->loadWithKey($this->data['Order Tax Category Key']);

            if ($tax_category->id) {
                $tax_rate = $tax_category->get('Tax Category Rate');
            } else {
                $tax_rate = 0;
            }


            $discount_tax = $discount_net * $tax_rate;
            $discount_total = $discount_net + $discount_tax;
            $sql = "SELECT  `Order Transaction Tax Category Key`  FROM `Order Transaction Fact` WHERE `Order Key`=?  AND `Order Transaction Type`='Order' GROUP BY  `Order Transaction Tax Category Key`";


            $stmt = $db->prepare($sql);
            $stmt->execute(
                array(
                    $this->id
                )
            );

            $amount_off_processed = false;


            while ($row = $stmt->fetch()) {


                if ($this->data['Order Tax Category Key'] == $row['Order Transaction Tax Category Key']) {
                    $amount_off_processed = true;
                }
            }



            if ($amount_off_processed) {
                $items[] = [
                    "item_id"            => 'discount-'.$this->id,
                    "quantity"           => 1,
                    "total_amount"       => round($discount_total * 100),
                    "tax_amount"         => round($discount_tax * 100),
                ];
            }
        }



        include_once 'EcomB2B/hokodo/api_call.php';

        $res=api_post_call('payment/orders/'.$this->data['hokodo_order_id'].'/cancel',
                           ['items'=>$items],$api_key,'PUT',$this->db
        );

        // print_r($res);


         $payment=get_object('Payment',$order->data['pending_hokodo_payment_id']);
         $payment->fast_update(
             [
                 'Payment Transaction Status Info'=>''
             ]
         );
         $payment->delete();


    }


}


