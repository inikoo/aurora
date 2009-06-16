#<?
/*
 File: Delivery Note.php 

 This file contains the DeliveryNote Class

 Each delivery note has to be associated with a contact if no contac data is provided when the Delivery Note is created an anonimous contact will be created as well. 
 

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('DB_Table.php');

include_once('Order.php');
include_once('Product.php');

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


    if(!$arg1 and !$arg2){
      $this->error=true;
      $this->msg='No data provided';
      return;
    }
    if(is_numeric($arg1)){
      $this->get_data('id',$arg1);
      return;
    }
     if (preg_match('/create|new/i',$arg1)){
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
  function get_data($tipo,$tag){
    if($tipo=='id')
      $sql=sprintf("select * from `Delivery Note Dimension` where  `Delivery Note Key`=%d",$tag);
    elseif($tipo=='public_id' )
      $sql=sprintf("select * from `Delivery Note Dimension` where  `Delivery Note Public ID`=%s",prepare_mysql($tag));
    else
      return;
    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
      $this->id=$this->data['Delivery Note Key'];
  }
 




  /*Method: create
   Creates a new invoice record

  */
  protected function create($dn_data, $transacions_data,$order_key) {
    
    $order=new Order($order_key);
    
    
    $this->data ['Delivery Note Date'] = $dn_data ['Delivery Note Date'];
    $this->data ['Delivery Note ID'] = $dn_data ['Delivery Note ID'];
    $this->data ['Delivery Note File As'] = $dn_data ['Delivery Note File As'];
    $this->data ['Delivery Note Customer Key'] = $order->data ['Order Customer Key'];
    $this->data ['Delivery Note Customer Name'] = $order->data ['Order Customer Name'];
    $this->data ['Delivery Note XHTML Ship Tos'] = $order->data ['Order XHTML Ship Tos'];
    //TODO
    $this->data ['Delivery Note Ship To Key'] = 0;
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
		
    $this->create_header ();
		
    $sql = sprintf ( "select `Ship To Country Key` from  `Ship To Dimension` where `Ship To Key`=%d", $this->data ['Delivery Note Ship To Key'] );
    $res = mysql_query ( $sql );
    if ($row2 = mysql_fetch_array ( $res, MYSQL_ASSOC )) {
      $this->destination_country_key = $row2 ['Ship To Country Key'];
    } else
      $this->destination_country_key = '0';
		
    $sql = sprintf ( "insert into `Order Delivery Note Bridge` values (%d,%d)", $order->data ['Order Key'], $this->data ['Delivery Note Key'] );
    if (! mysql_query ( $sql ))
      exit ( "$sql  Errro can no insert order dn  bridge" );
		
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
      if ($data ['pick_method'] == 'historic') {
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
	  $note = $a . ', ' . $order->data ['Order Current XHTML State'];
					
	  $part = new Part ( 'sku', $part_sku );
	  $location_id = $part->get ( 'Picking Location Key' );
					
	  if ($data ['Shipped Quantity'] == 0)
	    $_typo = "'No Dispached'";
	  else
	    $_typo = "'Sale'";
	  $sql = sprintf ( "insert into `Inventory Transaction Fact`  (`Date`,`Part SKU`,`Location Key`,`Inventory Transaction Quantity`,`Inventory Transaction Type`,`Inventory Transaction Amount`,`Required`,`Given`,`Amount In`,`Metadata`,`Note`,`Supplier Product Key`) values (%s,%s,%d,%s,%s,%.2f,%f,%f,%f,%s,%s,%s) ", prepare_mysql ( $this->data ['Delivery Note Date'] ), prepare_mysql ( $part_sku ), $location_id, prepare_mysql ( - $parts_per_product * $data ['Shipped Quantity'] ), "'Sale'", - $cost, number ( $data ['required'] * $parts_per_product ), $data ['given'] * $parts_per_product, $data ['amount in'], prepare_mysql ( $this->data ['Delivery Note Metadata'] ), prepare_mysql ( $note ), $data ['pick_method_data'] ['supplier product key'] );
	  //  print "$sql\n";
	  if (! mysql_query ( $sql ))
	    exit ( "can not create Warehouse * 888 $sql   Inventory Transaction Fact\n" );
	} else
	  exit ( "error no sku found order php l 792\n" );
			
      }
      // if($data['pick_method']=='historic2'){
			

      //       $cost_supplier=0;
      //       $cost_manu='';
      //       $cost_storing='';
      //       $cost_hand='';
      //       $cost_shipping='';
			

      //       //       print "nre --------------\n";
			

      //       $sql=sprintf("select `Parts Per Product`,`Product Part Key`,`Part SKU` from `Product Part List` where `Product ID`=%s ",prepare_mysql($data['Product ID']));
      //       $result=mysql_query($sql);
      //       $part_sku=array();$qty=array();
			

      //       $index=0;
      //       //  print "$sql\n";
      //       //     $supplier_cost=0;
      //       while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      // 	$part_sku=$row['Part SKU'];
      // 	$parts_per_product=$row['Parts Per Product'];
      // 	//get supplier product id
			

      // 	$sql=sprintf(" select `Supplier Product Code`,`Supplier Product Valid From`,`Supplier Product Valid To`,`Supplier Product Key`,SPD.`Supplier Product ID`,`Supplier Product Units Per Part`,`Supplier Product Cost` from  `Supplier Product Dimension`   SPD left join `Supplier Product Part List` SPPL  on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`) where `Part SKU`=%s  and `Supplier Product Valid From`<=%s and `Supplier Product Valid To`>=%s",prepare_mysql($row['Part SKU'])
      // 		     ,prepare_mysql($this->data['Delivery Note Date'])
      // 		     ,prepare_mysql($this->data['Delivery Note Date'])
      // );
			

      // 	 $result2=mysql_query($sql);
			

      // 	 $num_sp=mysql_num_rows($result2);
      // 	 if($num_sp==0){
      // 	   exit("$sql\n error in order class 0we49qwqeqwe\n");
      // 	 }if($num_sp==1){
      // 	   //print "AQYIIIII";
			

      // 	   $row2=mysql_fetch_array($result2, MYSQL_ASSOC);
      // 	   $supplier_product_id=$row2['Supplier Product ID'];
      // 	   $sp_units_per_part=$row2['Supplier Product Units Per Part'];
      // 	   $cost=$row2['Supplier Product Cost']*$sp_units_per_part*$parts_per_product*$data['Shipped Quantity'];
      // 	   $cost_supplier+=$cost;
      // 	   //print_r($row2);
      // 	   // print "$cost_supplier * $cost * $sp_units_per_part  $parts_per_product  \n";
      // 	   //if($cost=='')
      // 	   //  print_r($data);
			

      // 	   $sql=sprintf("insert into `Inventory Transaction Fact`  (`Date`,`Part SKU`,`Supplier Product ID`,`Location Key`,`Inventory Transaction Quantity`,`Inventory Transaction Type`,`Inventory Transaction Amount`,`Required`,`Given`,`Amount In`,`Metadata`) values (%s,%s,%s,1,%s,'Sale',%.2f,%f,%f,%.2f,%s) "
      // 			,prepare_mysql($this->data['Delivery Note Date'])
      // 			,prepare_mysql($part_sku)
      // 			,prepare_mysql($supplier_product_id)
      // 			,prepare_mysql(-$parts_per_product*$data['Shipped Quantity'])
      // 			,-$cost
      // 			,$data['required']*$parts_per_product
      // 			,$data['given']*$parts_per_product
      // 			,$data['amount in']
      // 			,prepare_mysql($this->data['Delivery Note Metadata'])
      // 		     );
      // 	   //   print "$sql\n";
      // 	   if(!mysql_query($sql))
      // 	     exit("can not create Warehouse * 888 $sql   Inventory Transaction Fact\n");
			

      // 	 }else{// More than one suplier product providing this part get at random (approx)
			

      // 	   print "$sql\n";
      // 	   print "more than 2 prod to choose\n";
      // 	   //   exit;
			

      // 	   $tmp=array();
      // 	   while($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
			

      // 	     $tmp[$row2['Supplier Product Key']]=array(
      // 						      'supplier product id'=>$row2['Supplier Product ID']
      // 						      ,'supplier product units per part'=>$row2['Supplier Product Units Per Part']
      // 						      ,'supplier product cost'=>$row2['Supplier Product Cost']
      // 						      ,'taken'=>0
      // 						      );
      // 	   }
			

      // 	   //print_r($tmp);
      // 	   $total_sps=$sp_units_per_part*$parts_per_product*$data['Shipped Quantity'];
      // 	   //print "$total_sps -------------\n";
      // 	   for($i=1;$i<=floor($total_sps);$i++){
      // 	     $rand_keys = array_rand($tmp,1);
      // 	     $tmp[$rand_keys]['taken']++;
      // 	   }
			

      // 	   if(floor($total_sps)!=$total_sps ){
      // 	     $rand_keys=array_rand($tmp,1);
      // 	     $tmp[$rand_keys]['taken']+=$total_sps-floor($total_sps);
      // 	   }
			

      // 	   //print_r($tmp);
      // 	   foreach($tmp as $key=>$values){
			

      // 	     if($values['taken']>0){
			

      // 	       $cost=$values['taken']*$values['supplier product cost'];
      // 	       $cost_supplier+=$cost;
      // 	       $sql=sprintf("insert into `Inventory Transaction Fact`  (`Date`,`Part SKU`,`Supplier Product ID`,`Location Key`,`Inventory Transaction Quantity`,`Inventory Transaction Type`,`Inventory Transaction Amount`,`Required`,`Given`,`Amount In`,`Metadata`)
      //  values (%s,%s,%s,1,%f,'Sale',%f,%f,%f,%f,%s) "
      // 			    ,prepare_mysql($this->data['Delivery Note Date'])
      // 			    ,prepare_mysql($part_sku)
      // 			    ,prepare_mysql($values['supplier product id'])
			

      // 			    ,-$parts_per_product*$values['taken']
			

      // 			    ,-$cost
			

      // 			    ,($data['required']*$parts_per_product)*($values['taken']/$total_sps)
      //  			    ,$data['given']*$parts_per_product*($values['taken']/$total_sps)
      // 			    ,$data['amount in']*($values['taken']/$total_sps)
			

      // 			    ,prepare_mysql($this->data['Delivery Note Metadata'])
      // 			    );
      // 	       // print "$sql\n\n\n\n";
			

      // 		       if(!mysql_query($sql))
      // 			 exit("can not create Warehouse * 888 $sql   Inventory Transaction Fact\n");
      // 	     }
      // 	   }
      // 	   exit;
			

      // 	 }
			

      //       }
			

      //  //      $sql=sprintf("insert into `Inventory Transaction Fact`  (`Date`,`Part SKU`,`Supplier Product ID`,`Warehouse Key`,`Warehouse Location Key`,`Inventory Transaction Quantity`,`Inventory Transaction Type`,`Inventory Transaction Amount`,`Required`,`Given`,`Amount In`,`Metadata`)
      // //  values (%s,%s,%s,1,1,%s,'Sale'     ,%.2f   ,%f,%f,%.2f,    %s
			

      // // ) "
      // // 			    ,prepare_mysql($this->data['Delivery Note Date'])
      // // 			    ,prepare_mysql($part_sku)
      // // 			    ,prepare_mysql($values['supplier product id'])
			

      // // 			    ,prepare_mysql(-$parts_per_product*$values['taken'])
			

      // // 			    -$cost
			

      // // 			    ,$data['required']*$parts_per_product
      // // 			    ,$data['given']*$parts_per_product
      // // 			    //,$data['amount in']
			

      // // 			    //,prepare_mysql($this->data['Delivery Note Metadata'])
      // // 			    );
			

      //  }
			

      $lag = (strtotime ( $this->data ['Delivery Note Date'] ) - strtotime ( $order->data ['Order Date'] )) / 3600 / 24;
      $sql = sprintf ( "update  `Order Transaction Fact` set `Estimated Weight`=%s,`Actual Shipping Date`=%s,`Order Last Updated Date`=%s, `Delivery Note ID`=%s,`Delivery Note Line`=%d,`Current Autorized to Sell Quantity`=%s ,`Delivery Note Quantity`=%s ,`Shipped Quantity`=%s ,`No Shipped Due Out of Stock`=%s ,`No Shipped Due No Authorized`=%s ,`No Shipped Due Not Found`=%s ,`No Shipped Due Other`=%s ,`Cost Supplier`=%s,`Cost Manufacure`=%s,`Cost Storing`=%s,`Cost Handing`=%s,`Cost Shipping`=%s,`Picking Advance`=100 ,`Packing Advance`=100 ,`Picker Key`=%d,`Packer Key`=%d ,`Delivery Note Key`=%d ,`Destination Country Key`=%s,`Backlog to Shipping Lag`=%f where `Order Key`=%d and  `Order Line`=%d", prepare_mysql ( $data ['Estimated Weight'] ), prepare_mysql ( $this->data ['Delivery Note Date'] ), prepare_mysql ( $this->data ['Delivery Note Date'] ), prepare_mysql ( $this->data ['Delivery Note ID'] ), $line_number, $data ['Current Autorized to Sell Quantity'], $data ['Delivery Note Quantity'], prepare_mysql ( $data ['Shipped Quantity'] ), prepare_mysql ( $data ['No Shipped Due Out of Stock'] ), prepare_mysql ( $data ['No Shipped Due No Authorized'] ), prepare_mysql ( $data ['No Shipped Due Not Found'] ), prepare_mysql ( $data ['No Shipped Due Other'] ), prepare_mysql ( $cost_supplier ), prepare_mysql ( $cost_manu ), prepare_mysql ( $cost_storing ), prepare_mysql ( $cost_hand ), prepare_mysql ( $cost_shipping ), $picking_key, $packing_key, $this->data ['Delivery Note Key'], $this->destination_country_key, $lag, $order->data ['Order Key'], $line_number );
      //    if($cost_supplier==''){
      // 	print "$sql\n $cost_supplier\n";
      // 	print 
      // 	exit;
      //       }
			

      // $prod=new Product($data['product_id']);
			

      //      print "\n ".$prod->data['Product Code']." $cost_supplier \n\n\n\n********************\n\n";
			

      //       if($prod->data['Product Code']=='Joie-01')
      // 	exit;
			

      if (! mysql_query ( $sql ))
	exit ( "$sql\n can not update order transacrion aferter dn 313123" );
		
    }
    $dn_txt = "ready to pick";
		
    if ($order->data ['Order Type'] == 'Sample') {
      $dn_txt = "Send";
		
    }
    if ($order->data ['Order Type'] == 'Donation') {
      $dn_txt = "Send";
		
    }
		
    $xhtml = sprintf ( '%s, %s <a href="dn.php?id=%d">%s</a>', $order->data ['Order Type'], $dn_txt, $this->data ['Delivery Note Key'], $this->data ['Delivery Note ID'] );
		
    $sql = sprintf ( "update `Order Dimension` set `Order Current Dispatch State`='%s' ,`Order Current XHTML State`=%s ,`Order XHTML Delivery Notes`=%s   where `Order Key`=%d", 'Ready to Pick', prepare_mysql ( $xhtml ), prepare_mysql ( $xhtml ), $order->data ['Order Key'] )

      ;
		
    if (! mysql_query ( $sql ))
      exit ( "can not update order dimension after dn\n" );
    $order_txt = 'Order';
    $orders = sprintf ( '%s <a href="order.php?id=%d">%s</a>', $order_txt, $this->id, $order->data ['Order Public ID'] );
    $sql = sprintf ( "update `Delivery Note Dimension` set `Delivery Note XHTML Orders`=%s ,`Delivery Note Distinct Items`=%d    where `Delivery Note Key`=%d", prepare_mysql ( $orders ), $line_number, $this->data ['Delivery Note Key'] );
    if (! mysql_query ( $sql ))
      exit ( "$sql\n can not update order dimension after dn\n" );
   
  }
     
     



  function create_header() {
		
    $sql = sprintf ( "insert into `Delivery Note Dimension` (`Delivery Note XHTML Orders`,`Delivery Note XHTML Invoices`,`Delivery Note Date`,`Delivery Note ID`,`Delivery Note File As`,`Delivery Note Customer Key`,`Delivery Note Customer Name`,`Delivery Note XHTML Ship Tos`,`Delivery Note Ship To Key`,`Delivery Note Metadata`,`Delivery Note Weight`,`Delivery Note XHTML Pickers`,`Delivery Note Number Pickers`,`Delivery Note XHTML Packers`,`Delivery Note Number Packers`,`Delivery Note Type`,`Delivery Note Title`) values ('','',%s,%s,%s,%s,%s,%s,%s,%s,%f,%s,%d,%s,%d,%s,%s)", prepare_mysql ( $this->data ['Delivery Note Date'] ), prepare_mysql ( $this->data ['Delivery Note ID'] ), prepare_mysql ( $this->data ['Delivery Note File As'] ), prepare_mysql ( $this->data ['Delivery Note Customer Key'] ), prepare_mysql ( $this->data ['Delivery Note Customer Name'] ), prepare_mysql ( $this->data ['Delivery Note XHTML Ship Tos'] ), prepare_mysql ( $this->data ['Delivery Note Ship To Key'] ), prepare_mysql ( $this->data ['Delivery Note Metadata'] ), $this->data ['Delivery Note Weight'], prepare_mysql ( $this->data ['Delivery Note XHTML Pickers'] ), $this->data ['Delivery Note Number Pickers'], prepare_mysql ( $this->data ['Delivery Note XHTML Packers'] ), $this->data ['Delivery Note Number Packers'], prepare_mysql ( $this->data ['Delivery Note Type'] ), prepare_mysql ( $this->data ['Delivery Note Title'] ) )

      ;
		
    if (mysql_query ( $sql )) {
			
      $this->data ['Delivery Note Key'] = mysql_insert_id ();
    } else {
      print "$sql \n Error can not create dn header";
      exit ();
    }
	
  }


  function get($key){
  
    switch($key){ 
    case('Items Gross Amount'): 
    case('Items Discount Amount'): 
    case('Items Net Amount'): 
    case('Items Tax Amount'): 
    case('Refund Net Amount'): 
    case('Charges Net Amount'): 
    case('Shipping Net Amount'): 

      return money($this->data['Delivery Note '.$key]);
    } 
  
  
    if(isset($this->data[$key]))
      return $this->data[$key];
   
    return false;
  }



  /*Function: update_field_switcher
   */

  protected function update_field_switcher($field,$value,$options=''){

    switch($field){
    default:
      $this->update_field($field,$value,$options);
    }
  
  }






  function display($tipo='xml'){



    switch($tipo){

    default:
      return 'todo';
    
    }
   
  
  }





}

?>