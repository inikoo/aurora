<?php
require_once 'common.php';

require_once 'class.Order.php';

if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>407,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }


$editor=array(
	      'User Key'=>$user->id
	      );

$tipo=$_REQUEST['tipo'];

switch($tipo){
case('ready_to_pick_orders'):
   ready_to_pick_orders();
   break;
  break;
case('cancel'):
  cancel_order();
  break;
case('send_to_warehouse'):
  if(isset($_REQUEST['order_key']) and is_numeric($_REQUEST['order_key']) )
    $order_key=$_REQUEST['order_key'];
  else
    $order_key=$_SESSION['state']['order']['id'];
    send_to_warehouse($order_key);
  break;
case('edit_new_order'):
  edit_new_order();
  break;
 case('transactions_to_process'):
    transactions_to_process();
  break;
  case('edit_new_order_shipping_type'):
  edit_new_order_shipping_type();
   break;
   case('set_order_shipping'):
  set_order_shipping();
   break;
    case('transactions_to_process'):
    transactions_to_process();
  break;
     case('post_transactions_to_process'):
    post_transactions_to_process();
  break;  
default:
  $response=array('state'=>404,'resp'=>_('Operation not found'));
  echo json_encode($response);
  
}



function cancel_order(){
  $order_key=$_SESSION['state']['order']['id'];

  $order=new Order($order_key);
  if(isset($_REQUEST['note']))
    $note=stripslashes(urldecode($_REQUEST['note']));
  else
    $note='';
	
  $order->cancel($note);
  if($order->cancelled){
    $response=array('state'=>200,'order_key'=>$order->id);
  echo json_encode($response);
  }else{
    $response=array('state'=>400,'msg'=>$this->msg);
    echo json_encode($response);

  }
  
}



function send_to_warehouse($order_key){

  $order=new Order($order_key);
	


  


  $order->send_to_warehouse();
  if(!$order->error){
    $response=array('state'=>200,'order_key'=>$order->id);
    echo json_encode($response);
  }else{
    $response=array('state'=>400,'msg'=>$order->msg);
    echo json_encode($response);

  }
  
}


function edit_new_order_shipping_type(){

  $order_key=$_REQUEST['id'];
  
  $value=$_REQUEST['newvalue'];

  $order=new Order($order_key);
  if($order->id){
    $order->update_shipping_type($value);
    if($order->updated){
              $response=array('state'=>200,'result'=>'updated','new_value'=>$order->new_value);

    }else{
          $response=array('state'=>200,'result'=>'no_change');

    }

  }else{
      $response=array('state'=>400,'msg'=>$order->msg);

  }
      echo json_encode($response);

  
  
}

function set_order_shipping(){

 $order_key=$_REQUEST['order_key'];
  
  $value=$_REQUEST['value'];

  $order=new Order($order_key);
  if($order->id){
    $order->update_shipping_amount($value);
    if($order->updated){
    
    
    $updated_data=array(
		      'order_items_gross'=>$order->get('Items Gross Amount')
		      ,'order_items_discount'=>$order->get('Items Discount Amount')
		      ,'order_items_net'=>$order->get('Items Net Amount')
		      ,'order_net'=>$order->get('Total Net Amount')
		      ,'order_tax'=>$order->get('Total Tax Amount')
		      ,'order_charges'=>$order->get('Charges Net Amount')
		      ,'order_credits'=>$order->get('Net Credited Amount')
		      ,'order_shipping'=>$order->get('Shipping Net Amount')
		      ,'order_total'=>$order->get('Total Amount')
		      
		      );
  

    
              $response=array('state'=>200,'result'=>'updated','new_value'=>$order->new_value,'data'=>$updated_data,'shipping'=>money($order->new_value));
              
              
              
              
              

    }else{
          $response=array('state'=>200,'result'=>'no_change');

    }

  }else{
      $response=array('state'=>400,'msg'=>$order->msg);

  }
      echo json_encode($response);

}

function edit_new_order(){
global $user;

 if(!isset($_SESSION['order_key']) or !$_SESSION['order_key']){
 $order_key=0;
 }else{
  $order_key=$_SESSION['order_key'];
  }
  
  if(!$order_key){
    $customer=new Customer($_SESSION['customer_key']);
   if(!$customer->id)
   return;
  $editor=array(
		'Author Name'=>$user->data['User Alias'],
		'Author Type'=>$user->data['User Type'],
		'Author Key'=>$user->data['User Parent Key'],
		'User Key'=>$user->id
		);
  
  $order_data=array('type'=>'system'
		    ,'Customer Key'=>$customer->id
		    ,'Order Type'=>'Order'
		    ,'editor'=>$editor
		   
		    );
  $order=new Order('new',$order_data);
  $_SESSION['order_key']=$order->id;
  }else{
    $order=new Order($order_key);

  }

if(!$order->id){
print $order->msg;
exit;
}

  $product_pid=$_REQUEST['pid'];
  $quantity=$_REQUEST['newvalue'];
  
  
  
  if(is_numeric($quantity) and $quantity>=0){

  

  $product=new Product('pid',$product_pid);

  $gross=$quantity*$product->data['Product Price'];
  $estimated_weight=$quantity*$product->data['Product Gross Weight'];

  $data=array(
	      'Estimated Weight'=>$estimated_weight
	      ,'date'=>date('Y-m-d H:i:s')
	      ,'Product Key'=>$product->data['Product Current Key']
	      ,'line_number'=>$order->get_next_line_number()
	      ,'gross_amount'=>$gross
	      ,'discount_amount'=>0
	      ,'Metadata'=>''
	      ,'qty'=>$quantity
	      ,'units_per_case'=>$product->data['Product Units Per Case']
	      ,'Current Dispatching State'=>'In Process'
	      ,'Current Payment State'=>'Waiting Payment'
	     
	      );
  
  
  
  $disconted_products=$order->get_discounted_products();
$order->skip_update_after_individual_transaction=false;
  $transaction_data=$order->add_order_transaction($data);



  $new_disconted_products=$order->get_discounted_products();
  foreach($new_disconted_products as $key=>$value){
    $disconted_products[$key]=$value;
  }
  //print_r($disconted_products);

  $adata=array();
  
  if(count($disconted_products)>0){

  $product_keys=join(',',$disconted_products);
  $sql=sprintf("select (select `Deal Info` from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key` and OTDB.`Order Line`=OTF.`Order Line`) as `Deal Info`,P.`Product ID`,`Product XHTML Short Description`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount` from `Order Transaction Fact` OTF  left join `Product History Dimension` PHD on (PHD.`Product Key`=OTF.`Product Key`) left join `Product Dimension` P on (PHD.`Product ID`=P.`Product ID`) where OTF.`Order Key`=%d and OTF.`Product Key` in (%s)",$order->id,$product_keys);
  
  
  // print $sql;
  $res = mysql_query($sql);
  $adata=array();
  
  while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
    $deal_info='';
    if($row['Deal Info']!=''){
      $deal_info=' <span class="deal_info">'.$row['Deal Info'].'</span>';
    }
  
 
  
    $adata[$row['Product ID']]=array(
		   'pid'=>$row['Product ID'],
		   'description'=>$row['Product XHTML Short Description'].$deal_info,
		   'to_charge'=>money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'],$order->data['Order Currency'])
		   );		   
      };
  }

  
  

  

 
 $_SESSION['order_data']=array(
   'items'=>$order->get('Order Items Gross Amount'),
	       'shipping'=>$order->get('Order Shipping Net Amount'),
	       	       'shipping_and_handing'=>$order->get('Order Shipping Net Amount')+$order->get('Order Charges Net Amount'),

	       'charges'=>$order->get('Order Charges Net Amount'),
	       'discounts'=>$order->get('Order Items Discount Amount'),
	       'total_net'=>$order->get('Order Total Net Amount'),
	       'tax'=>$order->get('Order Total Tax Amount'),
	       'total'=>$order->get('Order Total Amount'),
  'amount_items'=>$order->get('Items Gross Amount'),
  'amount_discounts'=>$order->get('Items Discount Amount'),
  'amount_shipping'=>$order->get('Shipping Net Amount'),
   'amount_shipping_and_handing'=>$order->get('Shipping And Handing Net Amount'),
  'amount_charges'=>$order->get('Charges Net Amount'),
  'amount_total_net'=>$order->get('Total Net Amount'),
  'amount_tax'=>$order->get('Total Tax Amount'),
  'amount_total'=>$order->get('Total Amount')
  );

  $response= array(
    'state'=>200
    ,'quantity'=>$transaction_data['qty']
    ,'key'=>$_REQUEST['key'],'data'=>$_SESSION['order_data']
    ,'to_charge'=>$transaction_data['to_charge'],'discount_data'=>$adata
    ,'discounts'=>($order->data['Order Items Discount Amount']!=0?true:false)
    ,'charges'=>($order->data['Order Charges Net Amount']!=0?true:false)
  );
  }else
    $response= array('state'=>200,'newvalue'=>$_REQUEST['oldvalue'],'key'=>$_REQUEST['key']);
 echo json_encode($response);  
  
}


function transactions_to_process(){
 
 global $store_key;
 
     $order_id=$_SESSION['order_key'];
$start_from=0;
 
$nr=250;
$order='';
$order_direction='';
$tableid=0;
$f_field='';
$order_dir='';
    $number_results=250;










  
     
     $table='  `Order Transaction Fact` OTF  left join `Product History Dimension` PHD on (PHD.`Product Key`=OTF.`Product Key`) left join `Product Dimension` P on (PHD.`Product ID`=P.`Product ID`)  ';
     $where=sprintf(' where `Order Quantity`>0 and `Order Key`=%d',$order_id);
   //  $sql_qty=', `Order Quantity`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,(select GROUP_CONCAT(`Deal Info`) from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key` and OTDB.`Order Line`=OTF.`Order Line`) as `Deal Info`';
  
$sql_qty=', `Order Quantity`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,(select GROUP_CONCAT(`Deal Info`) from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key`) as `Deal Info`';  

     $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';
    $wheref='';
    if ($f_field=='code' and $f_value!='')
        $wheref.=" and  `Product Code` like '".addslashes($f_value)."%'";
    elseif($f_field=='name' and $f_value!='')
    $wheref.=" and  `Product Name` like '%".addslashes($f_value)."%'";
   
      $sql="select count(*) as total from $table   $where $wheref   ";
 
    // print_r($conf);exit;
      //    print $sql;
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from $table  $where   ";

        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }

    }


    $rtext=$total_records." ".ngettext('product','products',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' '._('(Showing all)');

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code like ")." <b>".$f_value."*</b> ";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with name like ")." <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with code like')." <b>".$f_value."*</b>";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with name like')." <b>".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_order=$order;
    $_order_dir=$order_dir;
    $order='`Product Code File As`';
    if ($order=='stock')
      $order='`Product Availability`';
    if ($order=='code')
      $order='`Product Code File As`';
    else if ($order=='name')
      $order='`Product Name`';
    else if ($order=='available_for')
      $order='`Product Available Days Forecast`';
    elseif($order=='family') {
      $order='`Product Family`Code';
    }
    elseif($order=='dept') {
      $order='`Product Main Department Code`';
    }
    elseif($order=='expcode') {
      $order='`Product Tariff Code`';
    }
    elseif($order=='parts') {
      $order='`Product XHTML Parts`';
    }
    elseif($order=='supplied') {
      $order='`Product XHTML Supplied By`';
    }
    elseif($order=='gmroi') {
      $order='`Product GMROI`';
    }
    elseif($order=='state') {
      $order='`Product Sales State`';
    }
    elseif($order=='web') {
      $order='`Product Web State`';
    }


    
 $sql="select  `Product Availability`,`Product Record Type`,P.`Product ID`,`Product Code`,`Product XHTML Short Description`,`Product Price`,`Product Units Per Case`,`Product Record Type`,`Product Web State`,`Product Family Name`,`Product Main Department Name`,`Product Tariff Code`,`Product XHTML Parts`,`Product GMROI`,`Product XHTML Parts`,`Product XHTML Supplied By`,`Product Stock Value`  $sql_qty from $table   $where $wheref order by $order $order_direction limit $start_from,$number_results   ";
 
    $res = mysql_query($sql);

    $adata=array();
//print $sql;
 while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

   if (is_numeric($row['Product Availability']))
     $stock=number($row['Product Availability']);
   else
     $stock='?';
   $type=$row['Product Record Type'];
   if ($row['Product Record Type']=='In Process')
            $type.='<span style="color:red">*</span>';
   switch ($row['Product Web State']) {
   case('Online Force Out of Stock'):
     $web_state=_('Out of Stock');
     break;
        case('Online Auto'):
	  $web_state=_('Auto');
	  break;
   case('Unknown'):
     $web_state=_('Unknown');
        case('Offline'):
	  $web_state=_('Offline');
	  break;
   case('Online Force Hide'):
     $web_state=_('Hide');
            break;
   case('Online Force For Sale'):
     $web_state=_('Sale');
     break;
        default:
	  $web_state=$row['Product Web State'];
   }
   

   $deal_info='';
   if($row['Deal Info']!=''){
     $deal_info=' <span class="deal_info">'.$row['Deal Info'].'</span>';
   }

   $adata[]=array(
		  'pid'=>$row['Product ID'],
		  'code'=>$row['Product Code'],
		'description'=>$row['Product XHTML Short Description'].$deal_info,
		'shortname'=>number($row['Product Units Per Case']).'x @'.money($row['Product Price']/$row['Product Units Per Case']).' '._('ea'),
		'family'=>$row['Product Family Name'],
		'dept'=>$row['Product Main Department Name'],
		'expcode'=>$row['Product Tariff Code'],
                     'parts'=>$row['Product XHTML Parts'],
		  'supplied'=>$row['Product XHTML Supplied By'],
		  'gmroi'=>$row['Product GMROI'],
		  //		  'stock_value'=>money($row['Product Stock Value']),
                     'stock'=>$stock,
		  'quantity'=>$row['Order Quantity'],
		  'state'=>$type,
		  'web'=>$web_state,
		  //		  'image'=>$row['Product Main Image'],
		  'type'=>'item',
		  'add'=>'+',
		  'remove'=>'-',
		  //'change'=>'<span onClick="quick_change("+",'.$row['Product ID'].')" class="quick_add">+</span> <span class="quick_add" onClick="quick_change("-",'.$row['Product ID'].')" >-</span>',
		  'to_charge'=>money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'])
		     
                 );


 }


 $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'total_records'=>$total_records-$filtered,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                     )
                   );
    echo json_encode($response);


}
function post_transactions_to_process(){
 
 global $store_key;
 
     $order_id=$_SESSION['order_key'];
$start_from=0;
 
$nr=250;
$order='';
$order_direction='';
$tableid=0;
$f_field='';
$order_dir='';
    $number_results=250;






  
     
     $table='  `Order Transaction Fact` OTF  left join `Product History Dimension` PHD on (PHD.`Product Key`=OTF.`Product Key`) left join `Product Dimension` P on (PHD.`Product ID`=P.`Product ID`)  ';
     $where=sprintf(' where `Order Quantity`>0 and `Order Key`=%d',$order_id);
   //  $sql_qty=', `Order Quantity`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,(select GROUP_CONCAT(`Deal Info`) from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key` and OTDB.`Order Line`=OTF.`Order Line`) as `Deal Info`';
  
$sql_qty=', `Order Quantity`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,(select GROUP_CONCAT(`Deal Info`) from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key` and OTDB.`Order Line`=OTF.`Order Line`) as `Deal Info`';
  
  

     $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';
    $wheref='';
    if ($f_field=='code' and $f_value!='')
        $wheref.=" and  `Product Code` like '".addslashes($f_value)."%'";
    elseif($f_field=='name' and $f_value!='')
    $wheref.=" and  `Product Name` like '%".addslashes($f_value)."%'";
   
      $sql="select count(*) as total from $table   $where $wheref   ";
 
    // print_r($conf);exit;
      //    print $sql;
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from $table  $where   ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }

    }


    $rtext=$total_records." ".ngettext('product','products',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' '._('(Showing all)');

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code like ")." <b>".$f_value."*</b> ";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with name like ")." <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with code like')." <b>".$f_value."*</b>";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with name like')." <b>".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_order=$order;
    $_order_dir=$order_dir;
    $order='`Product Code File As`';
    if ($order=='stock')
      $order='`Product Availability`';
    if ($order=='code')
      $order='`Product Code File As`';
    else if ($order=='name')
      $order='`Product Name`';
    else if ($order=='available_for')
      $order='`Product Available Days Forecast`';
    elseif($order=='family') {
      $order='`Product Family`Code';
    }
    elseif($order=='dept') {
      $order='`Product Main Department Code`';
    }
    elseif($order=='expcode') {
      $order='`Product Tariff Code`';
    }
    elseif($order=='parts') {
      $order='`Product XHTML Parts`';
    }
    elseif($order=='supplied') {
      $order='`Product XHTML Supplied By`';
    }
    elseif($order=='gmroi') {
      $order='`Product GMROI`';
    }
    elseif($order=='state') {
      $order='`Product Sales State`';
    }
    elseif($order=='web') {
      $order='`Product Web State`';
    }


    
 $sql="select  `Product Availability`,`Product Record Type`,P.`Product ID`,`Product Code`,`Product XHTML Short Description`,`Product Price`,`Product Units Per Case`,`Product Record Type`,`Product Web State`,`Product Family Name`,`Product Main Department Name`,`Product Tariff Code`,`Product XHTML Parts`,`Product GMROI`,`Product XHTML Parts`,`Product XHTML Supplied By`,`Product Stock Value`  $sql_qty from $table   $where $wheref order by $order $order_direction limit $start_from,$number_results    ";
 
    $res = mysql_query($sql);

    $adata=array();
print $sql;
 while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

   if (is_numeric($row['Product Availability']))
     $stock=number($row['Product Availability']);
   else
     $stock='?';
   $type=$row['Product Record Type'];
   if ($row['Product Record Type']=='In Process')
            $type.='<span style="color:red">*</span>';
   switch ($row['Product Web State']) {
   case('Online Force Out of Stock'):
     $web_state=_('Out of Stock');
     break;
        case('Online Auto'):
	  $web_state=_('Auto');
	  break;
   case('Unknown'):
     $web_state=_('Unknown');
        case('Offline'):
	  $web_state=_('Offline');
	  break;
   case('Online Force Hide'):
     $web_state=_('Hide');
            break;
   case('Online Force For Sale'):
     $web_state=_('Sale');
     break;
        default:
	  $web_state=$row['Product Web State'];
   }
   

   $deal_info='';
   if($row['Deal Info']!=''){
     $deal_info=' <span class="deal_info">'.$row['Deal Info'].'</span>';
   }

   $adata[]=array(
		  'pid'=>$row['Product ID'],
		  'code'=>$row['Product Code'],
		'description'=>$row['Product XHTML Short Description'].$deal_info,
		'shortname'=>number($row['Product Units Per Case']).'x @'.money($row['Product Price']/$row['Product Units Per Case']).' '._('ea'),
		'family'=>$row['Product Family Name'],
		'dept'=>$row['Product Main Department Name'],
		'expcode'=>$row['Product Tariff Code'],
                     'parts'=>$row['Product XHTML Parts'],
		  'supplied'=>$row['Product XHTML Supplied By'],
		  'gmroi'=>$row['Product GMROI'],
		  //		  'stock_value'=>money($row['Product Stock Value']),
                     'stock'=>$stock,
		  'quantity'=>$row['Order Quantity'],
		  'state'=>$type,
		  'web'=>$web_state,
		  //		  'image'=>$row['Product Main Image'],
		  'type'=>'item',
		  'add'=>'+',
		  'remove'=>'-',
		  //'change'=>'<span onClick="quick_change("+",'.$row['Product ID'].')" class="quick_add">+</span> <span class="quick_add" onClick="quick_change("-",'.$row['Product ID'].')" >-</span>',
		  'to_charge'=>money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'])
		     
                 );


 }

 $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'total_records'=>$total_records-$filtered,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                     )
                   );
    echo json_encode($response);


}

function ready_to_pick_orders(){
 
    $conf=$_SESSION['state']['orders']['ready_to_pick_dn'];
    if(isset( $_REQUEST['sf']))
      $start_from=$_REQUEST['sf'];
    else
      $start_from=$conf['sf'];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
   if(isset( $_REQUEST['o']))
     $order=$_REQUEST['o'];
  else
    $order=$conf['order'];
   if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
   else
     $order_dir=$conf['order_dir'];
    if(isset( $_REQUEST['f_field']))
      $f_field=$_REQUEST['f_field'];
    else
      $f_field=$conf['f_field'];
    
    if(isset( $_REQUEST['f_value']))
      $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
    if(isset( $_REQUEST['where']))
     $where=$_REQUEST['where'];
    else
      $where=$conf['where'];
    
   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;

 

   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

  
     
     
     $_SESSION['state']['orders']['ready_to_pick_dn']=array(
						 'order'=>$order,
						 'order_dir'=>$order_direction,
						 'nr'=>$number_results,
						 'sf'=>$start_from,
						 'where'=>$where,
						 'f_field'=>$f_field,
						 'f_value'=>$f_value,


						 );
   

 



  
   

  
   $where.=' and `Order Current Dispatch State`="Ready to Pick" ';
   

   $wheref='';

  if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Order Last Updated Date`))<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Order Last Updated Date`))>=".$f_value."    ";
   elseif($f_field=='customer_name' and $f_value!='')
    $wheref.=" and  `Order Customer Name` like '".addslashes($f_value)."%'";
   elseif($f_field=='public_id' and $f_value!='')
    $wheref.=" and  `Order Public ID` like '".addslashes($f_value)."%'";


   


   

   
  $sql="select count(*) as total from `Order Dimension`   $where $wheref ";
  //print $sql ;
   $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $total=$row['total'];
  }
  if($where==''){
    $filtered=0;
     $total_records=$total;
  }else{
    
      $sql="select count(*) as total from `Order Dimension`  $where";
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	$total_records=$row['total'];
	$filtered=$total_records-$total;
      }
      
  }
  mysql_free_result($result);

  $rtext=$total_records." ".ngettext('order','orders',$total_records);

  if($total_records>$number_results)
    $rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
  else
    $rtext_rpp=sprintf("Showing all orders");

  $filter_msg='';

     switch($f_field){
     case('public_id'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with number")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders starting with')." <b>$f_value</b>)";
       break;
     case('customer_name'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with customer")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with customer')." <b>".$f_value."*</b>)";
       break;  
     case('minvalue'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order minimum value of")." <b>".money($f_value)."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with min value of')." <b>".money($f_value)."*</b>)";
       break;  
  
 case('max'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order older than")." <b>".number($f_value)."</b> "._('days');
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('last')." <b>".number($f_value)."</b> "._('days orders').")";
       break;  
     }



   
   $_order=$order;
   $_dir=$order_direction;
   
   $order='`Order Last Updated Date`';
   if($order=='id')
     $order='`Order Public ID`';
   else if($order=='customer')
     $order='`Order Customer Name`';
   
   
   

  $sql="select `Order Key`,`Order Public ID`,`Order Customer Key`,`Order Customer Name`,`Order Last Updated Date`,`Order Estimated Weight` from `Order Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
 // print $sql;
  global $myconf;

   $data=array();

   $res = mysql_query($sql);
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
    
     if($row['Order Last Updated Date']=='')
       $lap='';
     else
       $lap=RelativeTime(date('U',strtotime($row['Order Last Updated Date'])));
    
     $w=weight($row['Order Estimated Weight']);

     $data[]=array(
		   'id'=>$row['Order Key']
		   ,'public_id'=>sprintf("%d05",$row['Order Public ID'])
		   ,'customer'=>$row['Order Customer Name']
		   ,'wating_lap'=>$lap
		   ,'e_weight'=>$w
		   ,'date'=>$row['Order Last Updated Date']
		   ,'pick_it'=>_('Pick it')
		   );
   }
mysql_free_result($res);

 $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'total_records'=>$total_records-$filtered,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                     )
                   );
    echo json_encode($response);


 
}



