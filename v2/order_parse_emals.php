<?php


            $ip = gethostbyname ( 'imap.gmail.com' );
            $ip = 'imap.gmail.com';
            $mbox = imap_open ( "{" . $ip . ":993/imap/ssl/novalidate-cert}INBOX", $data ['email'], $data ['pwd'] ) or die ( "can't connect: " . imap_last_error () );
            $imap_obj = imap_check ( $mbox );
            $imap_obj->Nmsgs;

            for ($i = 1; $i <= $imap_obj->Nmsgs; $i ++) {
                print "MENSSAGE NUMBER $i\n";
                $email = imap_body ( $mbox, $i );
                $email = mb_convert_encoding ( $email, "UTF-8", "UTF-8, ISO-8859-1" );

                if (preg_match ( '/\nUsername\s*:\s*\d+/', $email, $match ))
                    $edata ['username'] = preg_replace ( '/username\s*:\s*/i', '', _trim ( $match [0] ) );
                if (preg_match ( '/\nDate\s*:\s*[a-zA-Z0-9\-\s]+:\d\d\s*/', $email, $match )) {
                    $date = preg_replace ( '/date\s*:\s*/i', '', _trim ( $match [0] ) );
                    $date = preg_replace ( '/\-/', '', $date );

                    $edata ['date'] = date ( "Y-m-d H:i:s", strtotime ( $date ) );
                }
                if (preg_match ( '/\nShopper Id\s*:\s*\d+/', $email, $match ))
                    $edata ['shopper_id'] = preg_replace ( '/shopper id\s*:\s*/i', '', _trim ( $match [0] ) );
                if (preg_match ( '/\nIP number\s*:\s*\d+\.\d+\.\d+\.\d+/', $email, $match ))
                    $edata ['ip_number'] = preg_replace ( '/ip number\s*:\s*/i', '', _trim ( $match [0] ) );
                if (preg_match ( '/\nFor payment by\s*:\s*.+\n/', $email, $match ))
                    $edata ['for_payment_by'] = preg_replace ( '/for payment by\s*:\s*/i', '', _trim ( $match [0] ) );

                if (! preg_match ( '/\nTel\s*:\s*\n/', $email ) and preg_match ( '/\nTel\s*:\s*.+\n/', $email, $match ))
                    $edata ['tel'] = preg_replace ( '/tel\s*:\s*/i', '', _trim ( $match [0] ) );

                if (! preg_match ( '/\nFax\s*:\s*\n/', $email ) and preg_match ( '/\nFax\s*:\s*.+\n/', $email, $match ))
                    $edata ['fax'] = preg_replace ( '/fax\s*:\s*/i', '', _trim ( $match [0] ) );

                if (! preg_match ( '/\nEmail\s*:\s*\n/', $email ) and preg_match ( '/\nEmail\s*:\s*.+\n/', $email, $match ))
                    $edata ['email'] = preg_replace ( '/email\s*:\s*/i', '', _trim ( $match [0] ) );

                $edata ['voucher'] = '0.00';
                if (preg_match ( '/\nVoucher\s*:\s*[0-9\.`-]+\s*\n/', $email, $match )) {
                    $edata ['voucher'] = preg_replace ( '/voucher\s*:\s*/i', '', _trim ( $match [0] ) );
                    if ($edata ['voucher'] == '-0.00')
                        $edata ['voucher'] = '0.00';
                }
                $edata ['discount'] = '0.00';
                if (preg_match ( '/\nDiscount\s*:\s*[0-9\.`-]+\s*\n/', $email, $match )) {
                    $edata ['discount'] = preg_replace ( '/discount\s*:\s*/i', '', _trim ( $match [0] ) );
                    if ($edata ['discount'] == '-0.00')
                        $edata ['discount'] = '0.00';
                }

                $edata ['subtotal'] = '0.00';
                if (preg_match ( '/\nSubtotal\s*:\s*[0-9\.`-]+\s*\n/', $email, $match )) {
                    $edata ['subtotal'] = preg_replace ( '/subtotal\s*:\s*/i', '', _trim ( $match [0] ) );
                    if ($edata ['subtotal'] == '-0.00')
                        $edata ['subtotal'] = '0.00';
                }
                $edata ['tax'] = '0.00';
                if (preg_match ( '/\nTax\s*:\s*[0-9\.\-]+\s*\n/', $email, $match )) {
                    $edata ['tax'] = preg_replace ( '/tax\s*:\s*/i', '', _trim ( $match [0] ) );
                    if ($edata ['tax'] == '-0.00')
                        $edata ['tax'] = '0.00';
                }

                $edata ['total'] = '0.00';
                if (preg_match ( '/\nTOTAL\s*:\s*[0-9\.`-]+\s*\n/', $email, $match )) {
                    $edata ['total'] = preg_replace ( '/total\s*:\s*/i', '', _trim ( $match [0] ) );
                    if ($edata ['total'] == '-0.00')
                        $edata ['total'] = '0.00';
                }
                $edata ['shipping'] = '0.00';
                if (preg_match ( '/\nShipping\s*:\s*[0-9\.`-]+\s*\n/', $email, $match )) {
                    $edata ['shipping'] = preg_replace ( '/shipping\s*:\s*/i', '', _trim ( $match [0] ) );
                    if ($edata ['shipping'] == '-0.00')
                        $edata ['shipping'] = '0.00';
                }

                $tags = array (' Inv Name', ' Inv Company', ' Inv Address', ' Inv City', ' Inv State', ' Inv Pst Code', ' Inv Country', ' Ship Name', ' Ship Company', ' Ship Address', ' Ship City', ' Ship State', ' Ship Pst Code', ' Ship Country', ' Ship Tel' );
                foreach ( $tags as $tag ) {
                    if (preg_match ( '/\n' . $tag . '\s*:.*\n/', $email, $match ))
                        $edata [strtolower ( _trim ( $tag ) )] = preg_replace ( '/' . _trim ( $tag ) . '\s*:\s*/i', '', _trim ( $match [0] ) );
                }

                $lines = preg_split ( '/\n/', $email );
                $products = false;
                $_products = array ();

                $preline = '';
                foreach ( $lines as $line ) {

                    $line = _trim ( $line );
                    //   print "$products $line\n";
                    if (preg_match ( '/Product : Quantity : Price/', $line ))
                        $products = true;
                    elseif (preg_match ( '/Voucher  :/', $line ))
                    $products = false;
                    elseif ($products and preg_match ( '/:\s*[0-9\.]+\s*:\s*[0-9\.]+$/', $line )) {
                        $_products [] = _trim ( $preline . $line );
                        $preline = '';
                    }
                    elseif ($products)
                    $preline .= $line . ' ';

                }

                global $myconf;
                $cdata ['contact_name'] = $edata ['inv name'];
                $cdata ['type'] = 'Person';
                $__tel = '';
                $__company = '';
                $__name == '';
                if (isset ( $edata ['tel'] ) and $edata ['tel'] != '') {
                    $cdata ['telephone'] = $edata ['tel'];
                    $__tel = $edata ['tel'];
                }
                if (isset ( $edata ['fax'] ) and $edata ['fax'] != '')
                    $cdata ['fax'] = $edata ['fax'];
                if (isset ( $edata ['email'] ) and $edata ['email'] != '')
                    $cdata ['email'] = $edata ['email'];
                if ($edata ['inv company'] != '') {
                    $cdata ['type'] = 'Company';
                    $cdata ['company_name'] = $edata ['inv company'];
                    $__company = $edata ['inv company'];
                }

                $cdata ['address_data'] = array ('type' => '3line', 'address1' => $edata ['inv address'], 'address2' => '', 'address3' => '', 'town' => $edata ['inv city'], 'country' => $edata ['inv country'], 'country_d1' => $edata ['inv state'], 'country_d2' => '', 'default_country_id' => $myconf ['country_id'], 'postcode' => $edata ['inv pst code'] )

                                          ;

                $__name = $edata ['inv name'];
                $cdata ['address_inv_data'] = array ('type' => '3line', 'name' => $__name, 'company' => $edata ['inv company'], 'telephone' => $__tel, 'address1' => $edata ['inv address'], 'address2' => '', 'address3' => '', 'town' => $edata ['inv city'], 'country' => $edata ['inv country'], 'country_d1' => $edata ['inv state'], 'country_d2' => '', 'default_country_id' => $myconf ['country_id'], 'postcode' => $edata ['inv pst code'] )

                                              ;
                $cdata ['address_shipping_data'] = array ('type' => '3line', 'name' => $edata ['ship name'], 'company' => $edata ['ship company'], 'telephone' => $edata ['ship tel'], 'address1' => $edata ['ship address'], 'address2' => '', 'address3' => '', 'town' => $edata ['ship city'], 'country' => $edata ['ship country'], 'country_d1' => $edata ['ship state'], 'country_d2' => '', 'default_country_id' => $myconf ['country_id'], 'postcode' => $edata ['ship pst code'] );

                //check if the addresses are the same:
                $diff_result = array_diff ( $cdata ['address_inv_data'], $cdata ['address_shipping_data'] );

                if (count ( $diff_result ) == 0) {

                    $same_address = true;
                    $same_contact = true;
                    $same_company = true;
                    $same_email = true;
                    $same_telaphone = true;

                } else {

                    $percentage = array ('address1' => 1, 'town' => 1, 'country' => 1, 'country_d1' => 1, 'postcode' => 1 );
                    $percentage_address = array ();
                    $p=0;
                    foreach ( $diff_result as $key => $value ) {
                        similar_text ( $cdata ['address_shipping_data'] [$key], $cdata ['address_inv_data'] [$key], $p );
                        $percentage [$key] = $p / 100;
                        if (preg_match ( '/address1|town|^country$|postcode|country_d1/i', $key ))
                            $percentage_address [$key] = $p / 100;
                    }
                    $avg_percentage = average ( $percentage );
                    $avg_percentage_address = average ( $percentage_address );

                    //print "AVG DIFF $avg_percentage $avg_percentage_address \n";


                    if ($cdata ['address_shipping_data'] ['name'] == '' or ! array_key_exists ( 'name', $diff_result ))
                        $same_contact = true;
                    else {
                        $_max = 1000000;
                        $irand = mt_rand ( 0, 1000000 );
                        $rand = $irand / $_max;
                        if ($rand < $percentage ['name'] and $percentage ['name'] > .90) {
                            $same_contact = true;

                        } else
                            $same_contact = false;
                    }
                    if ($cdata ['address_shipping_data'] ['company'] == '' or ! array_key_exists ( 'company', $diff_result ))
                        $same_company = true;
                    else {
                        $_max = 1000000;
                        $irand = mt_rand ( 0, 1000000 );
                        $rand = $irand / $_max;

                        if ($rand < $percentage ['company'] and $percentage ['company'] > .90) {
                            $same_company = true;
                        } else
                            $same_company = false;
                    }

                    if (array_key_exists ( 'telephone', $diff_result ))
                        $same_telephone = false;
                    else
                        $same_telephone = true;

                    if ($avg_percentage_address == 1)
                        $same_address = true;
                    else
                        $same_address = false;



                }
                $cdata ['has_shipping'] = true;
                $cdata ['shipping_data'] = $cdata ['address_shipping_data'];

                $cdata ['same_address'] = $same_address;
                $cdata ['same_contact'] = $same_contact;
                $cdata ['same_company'] = $same_company;
                $cdata ['same_email'] = $same_email;
                $cdata ['same_telephone'] = $same_email;

                $customer_identification_method = 'email';
                $customer_id = find_customer ( $customer_identification_method, $cdata );
                $customer = new Customer ( $customer_id );
                //$ship_to_key = $customer->data ['Customer Last Ship To Key'];
                //$ship_to = $customer->get ( 'xhtml ship to', $ship_to_key );

                $store = new Store ( 'code', 'AW.web' );
                if (! $store->id)
                    $store = new Store ( 'unknown' );

                $this->data ['Order Date'] = $edata ['date'];
                $this->data ['Order Public Id'] = $edata ['shopper_id'];
                $this->data ['Order Customer Key'] = $customer->id;
                $this->data ['Order Customer Name'] = $customer->data ['customer name'];
                $this->data ['Order Current Dispatch State'] = 'In Process';
                $this->data ['Order Current Payment State'] = 'Waiting Payment';
                $this->data ['Order Current Xhtml State'] = 'In Process';
                $this->data ['Order Customer Message'] = _trim ( $edata ['message'] );
                $this->data ['Order Original Data Mime Type'] = 'text/plain';
                //$this->data ['Order Original Data'] = $email;
                $this->data ['order main store key'] = $store->id;
                $this->data ['order main store code'] = $store->get ( 'code' );
                $this->data ['order main store type'] = $store->get ( 'type' );
                $this->data ['order items gross amount'] = $edata ['subtotal'];
                $this->data ['order items shipping amount'] = $edata ['shipping'];

                $this->data ['order discount ammont'] = $edata ['discount'] + $edata ['voucher'];
                $this->data ['order total tax amount'] = $edata ['tax'];
                $this->data ['order main xhtml ship to'] = $ship_to;
                $this->data ['order ship to addresses'] = 1;
                $this->create_order_header ();

                $pdata = array ();
                foreach ( $_products as $product_line ) {
                    $_data = preg_split ( '/:/', $product_line );
                    if (count ( $_data ) > 3 and count ( $_data ) < 6) {

                        $__code == '';
                        for ($j = 0; $j < count ( $_data ) - 2; $j ++) {
                            $__code .= $_data [$j] . ' ';
                        }
                        $__qty = $_data [count ( $_data ) - 2];
                        $__amount = $_data [count ( $_data ) - 1];
                        $_data = array ($__code, $__qty, $__amount );

                        $this->warnings [] = _ ( 'Warning: Delimiter found in product description. Line:' ) . $product_line;



                    }

                    if (count ( $_data ) == 3) {
                        preg_match ( '/^[a-z0-9\-\&\/]+\s/i', $_data [0], $match_code );
                        $code = _trim ( $match_code [0] );
                        if (in_array ( $code, $data ['product code exceptions'] ))
                            continue;

                        if (array_key_exists ( strtolower ( $code ), $data ['product code replacements'] )) {

                            foreach ( $data ['product code replacements'] [strtolower ( $code )] as $replacement_data ) {
                                if (preg_match ( '/^' . $replacement_data ['line'] . '/i', $_data [0] ))
                                    $code = $replacement_data ['replacement'];
                            }

                        }


                        $product = new Product ( 'code', $code, $this->data ['Order Date'] );
                        if (! $product->id) {
                            $this->errors [] = _ ( 'Error(1): Undentified Product. Line:' ) . $product_line;
                            exit ("Error(1), product undentified Line: $code $product_line\n");
                        } else {
                            $qty = _trim ( $_data [1] );
                            // Get here the discounts
                            if (isset ( $pdata [$product->id] ))
                                $pdata [$product->id] ['qty'] = $pdata [$product->id] ['qty'] + $qty;
                            else
                                $pdata [$product->id] = array ('code' => $product->get ( 'product code' ), 'amount' => $product->data ['Product Price'] * $qty, 'case_price' => $product->get ['Product Price'], 'product_id' => $product->id, 'qty' => $qty, 'family_id' => $product->data ['Product Family Key'] );

                        }
                    } else {

                        $this->errors [] = _ ( 'Error(2): Can not read product line. Line:' ) . $product_line;
                        exit ("Error(2), product undentified Count:" . count ( $_data ) . " Line:$product_line\n");
                    }
                }

                $pdata = $this->get_discounts ( $pdata, $customer->id, $this->data ['Order Date'] );
                $line_number = 1;
                foreach ( $pdata as $product_data ) {
                    $product_data ['date'] = $this->data ['Order Date'];
                    $product_data ['line_number'] = $line_number;
                    $this->add_order_transaction ( $product_data );
                    $line_number ++;
                }



                $customer = new Customer ( $customer_id );

                if ($this->update_customer) {
                    $customer->update_orders();
                    $customer->update_non_nomal_data();
                    $customer->update_activity();
                }



                switch ($_SESSION ['lang']) {
                default :
                    $abstract = sprintf ( 'Internet Order <a href="order.php?id=%d">%s</a>', $this->data['Order Key'], $this->data['Order Public ID'] );
                    $note = sprintf ( '%s (<a href="customer.php?id=%d">%s) place an order by internet using IP:%d at %s', $customer->get ( 'customer name' ), $customer->id, $customer->id, $edata ['ip_number'], strftime ( "%e %b %Y %H:%M", strtotime ( $this->data ['order date'] ) ) );
                }

                $history_data=array(
                                  'Date'=>$this->data ['order date'],
                                  'Subject'=>'Customer',
                                  'Subject Key'=>$customer->id,
                                  'Action'=>'placed',
                                  'Direct Object'=>'Order',
                                  'Direct Object Key'=>$this->data ['Order Key'],
                                  'History Abstract'=>$abstract,
                                  'History Details'=>$note
                              );
                $history_key=$this->add_history($history_data);
                $sql=sprintf("insert into `Customer History Bridge` values (%d,%d,'No','No','Orders')",$customer->id,$history_key);
                mysql_query($sql);

            }












?>