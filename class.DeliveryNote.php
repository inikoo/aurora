<?php
/*
  File: Delivery Note.php

  This file contains the DeliveryNote Class

  Each delivery note has to be associated with a contact if no contac data is provided when the Delivery Note is created an anonimous contact will be created as well.


  About:
  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Kaktus

  Version 2.0
*/
include_once('class.DB_Table.php');

include_once('class.Order.php');

include_once('class.Product.php');

/* class: DeliveryNote
   Class to manage the *Delivery Note Dimension* table
*/



class DeliveryNote extends DB_Table {

    /*
      Constructor: DeliveryNote
      Initializes the class, trigger  Search/Load/Create for the data set

      If first argument is find it will try to match the data or create if not found

      Parameters:
      arg1 -    Tag for the Search/Load/Create Options *or* the Contact Key for a simple object key search
      arg2 -    (optional) Data used to search or create the object

      Returns:
      void

      Example:
      (start example)
      // Load data from `Delivery Note Dimension` table where  `Delivery Note Key`=3
      $key=3;
      $dn = New DeliveryNote($key);




    */
    function DeliveryNote($arg1=false,$arg2=false,$arg3=false,$arg4=false) {

        $this->table_name='Delivery Note';
        $this->ignore_fields=array('Delivery Note Key');


        if (!$arg1 and !$arg2) {
            $this->error=true;
            $this->msg='No data provided';
            return;
        }
        if (is_numeric($arg1)) {
            $this->get_data('id',$arg1);
            return;
        }
        if (preg_match('/(create|new).*(replacements?|shortages?)/i',$arg1)) {
            $this->create_replacement($arg2,$arg3,$arg4);
            return;
        }
        if (preg_match('/create|new/i',$arg1)) {
            $this->create($arg2,$arg3,$arg4);
            return;
        }
        //    if(preg_match('/find/i',$arg1)){
        //  $this->find($arg2,$arg1);
        //  return;
        // }
        $this->get_data($arg1,$arg2);
    }
    /*
      Method: get_data
      Load the data from the database

      See Also:
      <find>
    */
    function get_data($tipo,$tag) {
        if ($tipo=='id')
            $sql=sprintf("select * from `Delivery Note Dimension` where  `Delivery Note Key`=%d",$tag);
        elseif($tipo=='public_id' )
        $sql=sprintf("select * from `Delivery Note Dimension` where  `Delivery Note Public ID`=%s",prepare_mysql($tag));
        else
            return;
        $result=mysql_query($sql);
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
            $this->id=$this->data['Delivery Note Key'];
    }



    protected function create($dn_data, $transacions_data,$order) {
        global $myconf;
        // print_r($transacions_data);
        // print_r($dn_data);
        //exit;

        $this->data ['Delivery Note Date'] = $dn_data ['Delivery Note Date'];
        $this->data ['Delivery Note ID'] = $dn_data ['Delivery Note ID'];
        $this->data ['Delivery Note File As'] = $dn_data ['Delivery Note File As'];
        $this->data ['Delivery Note Customer Key'] = $order->data ['Order Customer Key'];
        $this->data ['Delivery Note Customer Name'] = $order->data ['Order Customer Name'];

        $this->data ['Delivery Note Store Key'] = $order->data ['Order Store Key'];

        $this->data ['Delivery Note Metadata'] = $order->data ['Order Original Metadata'];
        $this->data ['Delivery Note Weight'] = $dn_data ['Delivery Note Weight'];
        $this->data ['Delivery Note XHTML Pickers'] = $dn_data ['Delivery Note XHTML Pickers'];
        $this->data ['Delivery Note Number Pickers'] = $dn_data ['Delivery Note Number Pickers'];
        $this->data ['Delivery Note Pickers IDs'] = $dn_data ['Delivery Note Pickers IDs'];
        $this->data ['Delivery Note XHTML Packers'] = $dn_data ['Delivery Note XHTML Packers'];
        $this->data ['Delivery Note Number Packers'] = $dn_data ['Delivery Note Number Packers'];
        $this->data ['Delivery Note Packers IDs'] = $dn_data ['Delivery Note Packers IDs'];
        $this->data ['Delivery Note Type'] = $dn_data ['Delivery Note Type'];
        $this->data ['Delivery Note Title'] = $dn_data ['Delivery Note Title'];
        if (isset($dn_data ['Delivery Note Dispatch Method']))
            $this->data ['Delivery Note Dispatch Method'] = $dn_data ['Delivery Note Dispatch Method'];
        else
            $this->data ['Delivery Note Dispatch Method'] = 'Unknown';

        $this->data ['Delivery Note Shipper Code']='';

        if (isset($dn_data ['Delivery Note Shipper Code']))
            $this->data ['Delivery Note Shipper Code']=$dn_data ['Delivery Note Shipper Code'];

        //get tyhe customer mos rtecent ship to
        //    $this->data ['Delivery Note Country 2 Alpha Code'] = 'XX';
        $this->data ['Delivery Note XHTML Ship To'] = '';
        $this->data ['Delivery Note Ship To Key'] = 0;
        //    print_r($this->data);
        if ($dn_data['Delivery Note Has Shipping']) {

            $customer=new Customer($this->data ['Delivery Note Customer Key']);
            // print_r($customer->data);
            $this->data ['Delivery Note Ship To Key'] =$customer->data['Customer Main Ship To Key'];

            if ($this->data ['Delivery Note Ship To Key']) {
                $ship_to=new Ship_To($this->data ['Delivery Note Ship To Key']);
                $this->data ['Delivery Note XHTML Ship To'] = $ship_to->data['Ship To XHTML Address'];
                $this->data ['Delivery Note Country 2 Alpha Code'] = $ship_to->data['Ship To Country 2 Alpha Code'];

            }
        } else {
            $this->data ['Delivery Note XHTML Ship To'] = _('Collected');
            $this->data ['Delivery Note Country 2 Alpha Code'] = $myconf['country_2acode'];
            $this->data ['Delivery Note Ship To Key'] =0;
        }

        $this->create_header ();
        $this->destination_country_key = '0';
        if ($this->data ['Delivery Note Ship To Key']) {
            $sql = sprintf ( "select `Ship To Country Key` from  `Ship To Dimension` where `Ship To Key`=%d", $this->data ['Delivery Note Ship To Key'] );
            $res = mysql_query ( $sql );
            if ($row2 = mysql_fetch_array ( $res, MYSQL_ASSOC )) {
                $this->destination_country_key = $row2 ['Ship To Country Key'];
            } else
                $this->destination_country_key = '0';
        }



        // $sql = sprintf ( "insert into `Order Delivery Note Bridge` values (%d,%d)", $order->data ['Order Key'], $this->data ['Delivery Note Key'] );
        //if (! mysql_query ( $sql ))
        // exit ( "$sql  Errro can no insert order dn  bridge" );

        $line_number = 0;
        $amount = 0;
        $discounts = 0;
        foreach ( $transacions_data as $data ) {


            $line_number ++;



            $sql = sprintf ( "update  `Order Transaction Fact` set `Estimated Weight`=%s,`Order Last Updated Date`=%s, `Delivery Note ID`=%s,`Delivery Note Line`=%d,`Current Autorized to Sell Quantity`=%.f ,`Delivery Note Key`=%d ,`Destination Country 2 Alpha Code`=%s where `Order Key`=%d and  `Order Line`=%d"
                             , prepare_mysql ( $data ['Estimated Weight'] )
                             , prepare_mysql ( $this->data ['Delivery Note Date'] )
                             , prepare_mysql ( $this->data ['Delivery Note ID'] )
                             , $line_number
                             , $data ['Current Autorized to Sell Quantity']
                             , $this->data ['Delivery Note Key']
                             , prepare_mysql($this->data ['Delivery Note Country 2 Alpha Code'])
                             , $order->data ['Order Key']
                             , $line_number
                           );
            //    print $sql;exit;
            if (! mysql_query ( $sql ))
                exit ( "$sql\n can not update order transacrion aferter dn 313123" );

        }
        // $dn_txt = "ready to pick";

        // if ($order->data ['Order Type'] == 'Sample') {
        //   $dn_txt = "Send";

        // }
        // if ($order->data ['Order Type'] == 'Donation') {
        //   $dn_txt = "Send";

        // }

        /*   $xhtml = sprintf ( '%s, %s <a href="dn.php?id=%d">%s</a>', $order->data ['Order Type'], $dn_txt, $this->data ['Delivery Note Key'], $this->data ['Delivery Note ID'] ); */

        /*     $sql = sprintf ( "update `Order Dimension` set `Order Current Dispatch State`='%s' ,`Order Current XHTML State`=%s ,`Order XHTML Delivery Notes`=%s   where `Order Key`=%d", 'Ready to Pick', prepare_mysql ( $xhtml ), prepare_mysql ( $xhtml ), $order->data ['Order Key'] ); */
        /*     mysql_query ( $sql ); */

        // exit ( "$sql\n  can not update order dimension after dn\n" );
        $this->load('orders');
        $sql = sprintf ( "delete from  `Order Delivery Note Bridge` where `Delivery Note Key`=%d",$this->id);
        mysql_query ( $sql );
        foreach($this->orders as $key=>$ord) {
            $sql = sprintf ( "insert into `Order Delivery Note Bridge` values (%d,%d)", $this->id, $key );
            mysql_query ( $sql );
        }





        $sql = sprintf ( "update `Delivery Note Dimension` set `Delivery Note XHTML Orders`=%s ,`Delivery Note Distinct Items`=%d    where `Delivery Note Key`=%d", prepare_mysql ( $this->data ['Delivery Note XHTML Orders'] ), $line_number, $this->data ['Delivery Note Key'] );
        if (! mysql_query ( $sql ))
            exit ( "$sql\n can not update dn\n" );

    }





    function create_header() {

        $sql = sprintf ( "insert into `Delivery Note Dimension` (`Delivery Note Dispatch Method`,`Delivery Note Store Key`,`Delivery Note XHTML Orders`,`Delivery Note XHTML Invoices`,`Delivery Note Date`,`Delivery Note ID`,`Delivery Note File As`,`Delivery Note Customer Key`,`Delivery Note Customer Name`,`Delivery Note XHTML Ship To`,`Delivery Note Ship To Key`,`Delivery Note Metadata`,`Delivery Note Weight`,`Delivery Note XHTML Pickers`,`Delivery Note Number Pickers`,`Delivery Note XHTML Packers`,`Delivery Note Number Packers`,`Delivery Note Type`,`Delivery Note Title`,`Delivery Note Country 2 Alpha Code`,`Delivery Note Shipper Code`) values (%s,%s,'','',%s,%s,%s,%s,%s,%s,%s,%s,%f,%s,%d,%s,%d,%s,%s,%s,%s)"

                         , prepare_mysql ( $this->data ['Delivery Note Dispatch Method'] )
                         , prepare_mysql ( $this->data ['Delivery Note Store Key'] )
                         , prepare_mysql ( $this->data ['Delivery Note Date'] )
                         , prepare_mysql ( $this->data ['Delivery Note ID'] ), prepare_mysql ( $this->data ['Delivery Note File As'] ), prepare_mysql ( $this->data ['Delivery Note Customer Key'] ), prepare_mysql ( $this->data ['Delivery Note Customer Name'] ), prepare_mysql ( $this->data ['Delivery Note XHTML Ship To'] ), prepare_mysql ( $this->data ['Delivery Note Ship To Key'] ), prepare_mysql ( $this->data ['Delivery Note Metadata'] ), $this->data ['Delivery Note Weight'], prepare_mysql ( $this->data ['Delivery Note XHTML Pickers'] ), $this->data ['Delivery Note Number Pickers'], prepare_mysql ( $this->data ['Delivery Note XHTML Packers'] ), $this->data ['Delivery Note Number Packers'], prepare_mysql ( $this->data ['Delivery Note Type'] )
                         , prepare_mysql ( $this->data ['Delivery Note Title'] )
                         , prepare_mysql ($this->data ['Delivery Note Country 2 Alpha Code'])
                         , prepare_mysql ($this->data ['Delivery Note Shipper Code'])

                       )

               ;

        if (mysql_query ( $sql )) {

            $this->data ['Delivery Note Key'] = mysql_insert_id ();
            $this->id=$this->data ['Delivery Note Key'];
        } else {
            print "$sql \n Error can not create dn header";
            exit ();
        }

    }


    function get($key) {

        switch ($key) {
        case('Items Gross Amount'):
        case('Items Discount Amount'):
        case('Items Net Amount'):
        case('Items Tax Amount'):
        case('Refund Net Amount'):
        case('Charges Net Amount'):
        case('Shipping Net Amount'):

            return money($this->data['Delivery Note '.$key]);
        }


        if (isset($this->data[$key]))
            return $this->data[$key];

        return false;
    }



    /*Function: update_field_switcher
     */

    protected function update_field_switcher($field,$value,$options='') {

        switch ($field) {
        default:
            $this->update_field($field,$value,$options);
        }

    }






    function display($tipo='xml') {



        switch ($tipo) {

        default:
            return 'todo';

        }


    }




    function load($key,$args=false) {
        global $myconf;
        switch ($key) {
        case('orders'):

            $sql=sprintf("select `Order Key` from `Order Transaction Fact` where `Delivery Note Key`=%d group by `Order Key`",$this->id);
            //print $sql;
            $res = mysql_query ( $sql );
            $this->orders=array();
            while ($row = mysql_fetch_array ( $res, MYSQL_ASSOC )) {
                if ($row['Order Key']) {
                    $this->orders[$row['Order Key']]=new Order($row['Order Key']);
                }

            }
            $this->data ['Delivery Note XHTML Orders'] ='';
            foreach($this->orders as $order) {
                $this->data ['Delivery Note XHTML Orders'] .= sprintf ( '%s <a href="order.php?id=%d">%s</a>, ', $myconf['order_id_prefix'], $order->data ['Order Key'], $order->data ['Order Public ID'] );
            }
            $this->data ['Delivery Note XHTML Orders'] =_trim(preg_replace('/\, $/','',$this->data ['Delivery Note XHTML Orders']));
            break;
        case('invoices'):

            $sql=sprintf("select `Invoice Key` from `Order Transaction Fact` where `Delivery Note Key`=%d group by `Invoice Key`",$this->id);
            //print $sql;

            $res = mysql_query ( $sql );
            $this->invoices=array();
            while ($row = mysql_fetch_array ( $res, MYSQL_ASSOC )) {
                if ($row['Invoice Key']) {
                    $this->invoices[$row['Invoice Key']]=new Invoice($row['Invoice Key']);
                }

            }
            $this->data ['Delivery Note XHTML Invoices'] ='';
            foreach($this->invoices as $invoice) {
                $this->data ['Delivery Note XHTML Invoices'] .= sprintf ( '%s <a href="invoice.php?id=%d">%s</a>, ', $myconf['invoice_id_prefix'], $invoice->data ['Invoice Key'], $invoice->data ['Invoice Public ID'] );
            }
            $this->data ['Delivery Note XHTML Invoices'] =_trim(preg_replace('/\, $/','',$this->data ['Delivery Note XHTML Invoices']));
            break;


        }
    }

    function update($key,$data=false,$args=false) {
        switch ($key) {
        case('Delivery Note XHTML Invoices'):
            $this->load('invoices');
            $sql=sprintf("update `Delivery Note Dimension` set `Delivery Note XHTML Invoices`=%s where `Delivery Note Key`=%d "
                         ,prepare_mysql($this->data['Delivery Note XHTML Invoices'])
                         ,$this->id
                        );
            mysql_query($sql);
            break;
        }

    }




    /*
      function: pick
      Mark as picked
    */

    function pick_simple($transacions_data) {
        $line_number = 0;
        $amount = 0;
        $discounts = 0;
        foreach ( $transacions_data as $data ) {
            if ($this->data ['Delivery Note Number Pickers'] == 1)
                $picking_key = $this->data ['Delivery Note Pickers IDs'] [0];
            else {
                $rand_keys = array_rand ( $this->data ['Delivery Note Pickers IDs'], 1 );
                $picking_key = $this->data ['Delivery Note Pickers IDs'] [$rand_keys];
            }
            if ($this->data ['Delivery Note Number Packers'] == 1)
                $packing_key = $this->data ['Delivery Note Packers IDs'] [0];
            else {
                $rand_keys = array_rand ( $this->data ['Delivery Note Packers IDs'], 1 );
                $packing_key = $this->data ['Delivery Note Packers IDs'] [$rand_keys];
            }

            $line_number ++;
            $cost_supplier = 0;
            $cost_manu = '';
            $cost_storing = '';
            $cost_hand = '';
            $cost_shipping = '';




            foreach( $data['pick_method_data']['parts_sku'] as $part_sku=>$part_data) {

                $parts_per_product = $part_data['parts_per_product'];
                $part_unit_cost=$part_data['unit_cost'];
                $cost = $part_unit_cost * $parts_per_product * $data ['Shipped Quantity'];

                $cost_supplier += $cost;

                $product = new product ($data ['product_id'] );
                $a = sprintf ( '<a href="product.php?id=%d">%s</a>', $product->id, $product->code );
                unset ( $product );
                //$note = $a . ', ' . $order->data ['Order Current XHTML State'];
                $note = $a;
                $part = new Part ( 'sku', $part_sku );
                $location_id = $part->get ( 'Picking Location Key' );

                if ($data ['Shipped Quantity'] == 0)
                    $_typo = "'No Dispached'";
                else
                    $_typo = "'Sale'";
                $sql = sprintf ( "insert into `Inventory Transaction Fact`  (`Date`,`Part SKU`,`Location Key`,`Inventory Transaction Quantity`,`Inventory Transaction Type`,`Inventory Transaction Amount`,`Required`,`Given`,`Amount In`,`Metadata`,`Note`) values (%s,%s,%d,%s,%s,%.2f,%f,%f,%f,%s,%s) "
                                 ,prepare_mysql ( $this->data ['Delivery Note Date'] ),
                                 prepare_mysql ( $part_sku ),
                                 $location_id,
                                 prepare_mysql ( - $parts_per_product * $data ['Shipped Quantity'] ),
                                 "'Sale'",
                                 - $cost,
                                 number ( $data ['required'] * $parts_per_product ),
                                 $data ['given'] * $parts_per_product,
                                 $data ['amount in'],
                                 prepare_mysql ( $this->data ['Delivery Note Metadata'] ),
                                 prepare_mysql ( $note )
                               );
                //  print "$sql\n";
                if (! mysql_query ( $sql ))
                    exit ( "can not create Warehouse * 888 $sql   Inventory Transaction Fact\n" );

            }
            $sql = sprintf ( "select `No Shipped Due Other`,`Order Quantity`,`No Shipped Due No Authorized` from  `Order Transaction Fact`   where `Delivery Note Key`=%d and   `Order Line`=%d"
                             , $this->id
                             , $line_number
                           );
            $order_qty=0;
            $no_auth=0;
            $no_other=0;
            $resultx=mysql_query($sql);
            if ($rowx=mysql_fetch_array($resultx, MYSQL_ASSOC)) {
                $order_qty=$rowx['Order Quantity'];
                $no_auth=$rowx['No Shipped Due No Authorized'];
                $no_other=$rowx['No Shipped Due Other'];
            }


            $dn_qty=$order_qty-$no_auth-$data ['No Shipped Due Out of Stock']-$data ['No Shipped Due Not Found']-$data ['No Shipped Due Other'];
            if ($dn_qty==0)
                $picking_state='Cancelled';
            else
                $picking_state='Ready to Pack';



            //$lag = (strtotime ( $this->data ['Delivery Note Date'] ) - strtotime ( $order->data ['Order Date'] )) / 3600 / 24;
            $sql = sprintf ( "update  `Order Transaction Fact` set `Estimated Weight`=%s,`Order Last Updated Date`=%s,`Cost Supplier`=%s,`Cost Manufacure`=%s,`Cost Storing`=%s,`Cost Handing`=%s,`Cost Shipping`=%s,`Picking Factor`=%f ,`Picker Key`=%d,`No Shipped Due Out of Stock`=%f,`No Shipped Due Not Found`=%f,`No Shipped Due Other`=%f,`Delivery Note Quantity`=%f ,`Current Dispatching State`=%s where `Delivery Note Key`=%d and   `Order Line`=%d"
                             , prepare_mysql ( $data ['Estimated Weight'] )
                             , prepare_mysql ( $this->data ['Delivery Note Date'] )

                             , prepare_mysql ( $cost_supplier )
                             , prepare_mysql ( $cost_manu )
                             , prepare_mysql ( $cost_storing )
                             , prepare_mysql ( $cost_hand )
                             , prepare_mysql ( $cost_shipping )
                             ,1
                             , $picking_key

                             ,$data ['No Shipped Due Out of Stock']
                             ,$data ['No Shipped Due Not Found']
                             ,$data ['No Shipped Due Other']+$no_other
                             ,$dn_qty
                             ,prepare_mysql ( $picking_state)
                             , $this->id
                             , $line_number
                           );
            if (! mysql_query ( $sql ))
                exit ( "$sql\n can not update order transacrion aferter dn 313123 zxzxzx" );
            //print "$sql\n";
        }


    }

    /*
      function: pick
      Mark as picked
    */

    function pick_historic($transacions_data) {


        $line_number = 0;
        $amount = 0;
        $discounts = 0;
        foreach ( $transacions_data as $data ) {
            if ($this->data ['Delivery Note Number Pickers'] == 1)
                $picking_key = $this->data ['Delivery Note Pickers IDs'] [0];
            else {
                $rand_keys = array_rand ( $this->data ['Delivery Note Pickers IDs'], 1 );
                $picking_key = $this->data ['Delivery Note Pickers IDs'] [$rand_keys];
            }
            if ($this->data ['Delivery Note Number Packers'] == 1)
                $packing_key = $this->data ['Delivery Note Packers IDs'] [0];
            else {
                $rand_keys = array_rand ( $this->data ['Delivery Note Packers IDs'], 1 );
                $packing_key = $this->data ['Delivery Note Packers IDs'] [$rand_keys];
            }

            $line_number ++;
            $cost_supplier = 0;
            $cost_manu = '';
            $cost_storing = '';
            $cost_hand = '';
            $cost_shipping = '';
            $sql = sprintf ( "select `Parts Per Product`,`Product Part Key`,`Part SKU` from `Product Part List` where `Product Part ID`=%d and `Part SKU`=%d", $data ['pick_method_data'] ['product part id'], $data ['pick_method_data'] ['part sku'] );
            $result = mysql_query ( $sql );
            $part_sku = array ();
            $qty = array ();
            if ($row = mysql_fetch_array ( $result, MYSQL_ASSOC )) {
                $parts_per_product = $row ['Parts Per Product'];
                $part_sku = $row ['Part SKU'];

                $sql = sprintf ( " select `Supplier Product Code`,`Supplier Product Valid From`,`Supplier Product Valid To`,`Supplier Product Key`,SPD.`Supplier Product ID`,`Supplier Product Units Per Part`,`Supplier Product Cost` from  `Supplier Product Dimension`   SPD left join `Supplier Product Part List` SPPL  on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`) where `Part SKU`=%s  and `Supplier Product Valid From`<=%s and `Supplier Product Valid To`>=%s  and `Supplier Product Key`=%s", prepare_mysql ( $row ['Part SKU'] ), prepare_mysql ( $this->data ['Delivery Note Date'] ), prepare_mysql ( $this->data ['Delivery Note Date'] ), $data ['pick_method_data'] ['supplier product key'] );

                $result2 = mysql_query ( $sql );

                $num_sp = mysql_num_rows ( $result2 );
                if ($num_sp != 1)
                    exit ( "$sql\n error in order class 0we49qwqeqwe history 1\n" );

                $row2 = mysql_fetch_array ( $result2, MYSQL_ASSOC );
                $supplier_product_id = $row2 ['Supplier Product ID'];
                $sp_units_per_part = $row2 ['Supplier Product Units Per Part'];
                $cost = $row2 ['Supplier Product Cost'] * $sp_units_per_part * $parts_per_product * $data ['Shipped Quantity'];

                $cost_supplier += $cost;

                $product = new product ( $data ['product_id'] );
                $a = sprintf ( '<a href="product.php?id=%d">%s</a>', $product->id, $product->data ['Product Code'] );
                unset ( $product );
                //$note = $a . ', ' . $order->data ['Order Current XHTML State'];
                $note = $a;
                $part = new Part ( 'sku', $part_sku );
                $location_id = $part->get ( 'Picking Location Key' );

                if ($data ['Shipped Quantity'] == 0)
                    $_typo = "'No Dispached'";
                else
                    $_typo = "'Sale'";
                $sql = sprintf ( "insert into `Inventory Transaction Fact`  (`Date`,`Part SKU`,`Location Key`,`Inventory Transaction Quantity`,`Inventory Transaction Type`,`Inventory Transaction Amount`,`Required`,`Given`,`Amount In`,`Metadata`,`Note`,`Supplier Product Key`) values (%s,%s,%d,%s,%s,%.2f,%f,%f,%f,%s,%s,%s) "
                                 , prepare_mysql ( $this->data ['Delivery Note Date'] )
                                 , prepare_mysql ( $part_sku )
                                 , $location_id
                                 , prepare_mysql ( - $parts_per_product * $data ['Shipped Quantity'] )
                                 , "'Sale'"
                                 , - $cost
                                 , number ( $data ['required'] * $parts_per_product )
                                 , $data ['given'] * $parts_per_product
                                 , $data ['amount in']
                                 , prepare_mysql ( $this->data ['Delivery Note Metadata'] )
                                 , prepare_mysql ( $note )
                                 , $data ['pick_method_data'] ['supplier product key']
                               );
                //  print "$sql\n";
                if (! mysql_query ( $sql ))
                    exit ( "can not create Warehouse * 888 $sql   Inventory Transaction Fact\n" );
            } else
                exit ( "error no sku found order php l 792\n" );

            $sql = sprintf ( "select `No Shipped Due Other`,`Order Quantity`,`No Shipped Due No Authorized` from  `Order Transaction Fact`   where `Delivery Note Key`=%d and   `Order Line`=%d"
                             , $this->id
                             , $line_number
                           );
            $order_qty=0;
            $no_auth=0;
            $no_other=0;
            $resultx=mysql_query($sql);
            if ($rowx=mysql_fetch_array($resultx, MYSQL_ASSOC)) {
                $order_qty=$rowx['Order Quantity'];
                $no_auth=$rowx['No Shipped Due No Authorized'];
                $no_other=$rowx['No Shipped Due Other'];
            }


            $dn_qty=$order_qty-$no_auth-$data ['No Shipped Due Out of Stock']-$data ['No Shipped Due Not Found']-$data ['No Shipped Due Other'];
            if ($dn_qty==0)
                $picking_state='Cancelled';
            else
                $picking_state='Ready to Pack';



            //$lag = (strtotime ( $this->data ['Delivery Note Date'] ) - strtotime ( $order->data ['Order Date'] )) / 3600 / 24;
            $sql = sprintf ( "update  `Order Transaction Fact` set `Estimated Weight`=%s,`Order Last Updated Date`=%s,`Cost Supplier`=%s,`Cost Manufacure`=%s,`Cost Storing`=%s,`Cost Handing`=%s,`Cost Shipping`=%s,`Picking Factor`=%f ,`Picker Key`=%d,`No Shipped Due Out of Stock`=%f,`No Shipped Due Not Found`=%f,`No Shipped Due Other`=%f,`Delivery Note Quantity`=%f ,`Current Dispatching State`=%s where `Delivery Note Key`=%d and   `Order Line`=%d"
                             , prepare_mysql ( $data ['Estimated Weight'] )
                             , prepare_mysql ( $this->data ['Delivery Note Date'] )

                             , prepare_mysql ( $cost_supplier )
                             , prepare_mysql ( $cost_manu )
                             , prepare_mysql ( $cost_storing )
                             , prepare_mysql ( $cost_hand )
                             , prepare_mysql ( $cost_shipping )
                             ,1
                             , $picking_key

                             ,$data ['No Shipped Due Out of Stock']
                             ,$data ['No Shipped Due Not Found']
                             ,$data ['No Shipped Due Other']+$no_other
                             ,$dn_qty
                             ,prepare_mysql ( $picking_state)
                             , $this->id
                             , $line_number
                           );
            if (! mysql_query ( $sql ))
                exit ( "$sql\n can not update order transacrion aferter dn 313123 zxzxzx" );
            //print "$sql\n";
        }


    }



    /*
      function: pick
      Mark as picked
    */

    function pack($tipo='all') {

        if ($tipo=='all') {
            $sql=sprintf("select * from `Order Transaction Fact` where `Delivery Note Key`=%s  and `Packing Factor`<1  ",$this->id);
            $result=mysql_query($sql);
            $_data=array();
            while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

                if ($this->data ['Delivery Note Number Packers'] == 1)
                    $packing_key = $this->data ['Delivery Note Packers IDs'] [0];
                else {
                    $rand_keys = array_rand ( $this->data ['Delivery Note Packers IDs'], 1 );
                    $packing_key = $this->data ['Delivery Note Packers IDs'] [$rand_keys];
                }
                $sql = sprintf ( "update  `Order Transaction Fact` set `Packing Factor`=%f ,`Packer Key`=%d ,`Current Dispatching State`=%s where `Current Dispatching State`='Ready to Pack'  and `Delivery Note Key`=%d and   `Order Line`=%d"
                                 ,1
                                 ,$packing_key
                                 ,prepare_mysql('Ready to Ship')
                                 ,$this->id
                                 ,$row['Order Line']);
                mysql_query ( $sql );

                $sql = sprintf ( "update  `Order Transaction Fact` set `Packing Factor`=%f ,`Packer Key`=%d  where `Current Dispatching State`='Cancelled'  and `Delivery Note Key`=%d and   `Order Line`=%d"
                                 ,1
                                 ,0
                                 ,$this->id
                                 ,$row['Order Line']);
                mysql_query ( $sql );


            }


        }


    }


    /*
      function: dispatch
      Mark as Dispatched
    */

    function dispatch($tipo='all',$transacions_data) {


        if ($tipo=='all') {
            $sql=sprintf("select * from `Order Transaction Fact` where `Delivery Note Key`=%s  and `Current Dispatching State`='Ready to Ship'  ",$this->id);
            //    print $sql;
            $result=mysql_query($sql);
            $_data=array();
            while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

                $sql = sprintf ( "update  `Order Transaction Fact` set `Shipped Quantity`=%f,  `Current Dispatching State`=%s where  `Delivery Note Key`=%d and   `Order Line`=%d"
                                 ,$row['Delivery Note Quantity']
                                 ,prepare_mysql('Dispached')
                                 ,$this->id
                                 ,$row['Order Line']);
                mysql_query ( $sql );
                // print "$sql\n";



            }


        }


        /*  //$lag = (strtotime ( $this->data ['Delivery Note Date'] ) - strtotime ( $order->data ['Order Date'] )) / 3600 / 24; */
        /*    $sql = sprintf ( "update  set `Shipped Quantity`=(`Delivery Note Quantity`-`No Shipped Due Out of Stock`-`No Shipped Due No Authorized`-`No Shipped Due Not Found`-`No Shipped Due Other`)   where `Delivery Note Key`=%d and `Current Dispatching State`='Ready to Ship' ",$this->id); */
        /*    // print $sql; */
        /*    mysql_query ( $sql ); */






    }

}

?>