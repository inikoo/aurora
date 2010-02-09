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
	      'Author Name'=>$user->data['User Alias'],
	      'Author Type'=>$user->data['User Type'],
	      'Author Key'=>$user->data['User Parent Key'],
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
case('edit_new_order'):
  edit_new_order();
  break;
 case('transactions_to_process'):
   products_to_sell();
   return;
   if(isset( $_REQUEST['show_all']) and preg_match('/^(yes|no)$/',$_REQUEST['show_all'])  ){
    
     if($_REQUEST['show_all']=='yes')
       $show_all=true;
     else
       $show_all=false;
     $_SESSION['state']['order']['show_all']=$show_all;
   }else
     $show_all=$_SESSION['state']['order']['show_all'];
  
   if($show_all)
     products_to_sell();
   else
     transactions_to_process();
  
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


function edit_new_order(){
  
  $order_key=$_SESSION['state']['order']['id'];
  $product_pid=$_REQUEST['pid'];
  $quantity=$_REQUEST['newvalue'];
  
  if(is_numeric($quantity) and $quantity>=0){

  $order=new Order($order_key);
  

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
	      ,'metadata'=>''
	      ,'qty'=>$quantity
	      ,'units_per_case'=>$product->data['Product Units Per Case']
	      ,'Current Dispatching State'=>'In Process'
	      ,'Current Payment State'=>'Waiting Payment'
	     
	      );
  

  $order->add_order_transaction($data);

  $order->update_discounts();
  $order->update_item_totals_from_order_transactions();
  $order->update_charges();
  $order->get_original_totals();
  $order->update_totals('save');

  $order->update_totals_from_order_transactions();
  
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

  $response= array('state'=>200,'newvalue'=>$quantity,'key'=>$_REQUEST['key'],'data'=>$updated_data);
  }else
    $response= array('state'=>200,'newvalue'=>$_REQUEST['oldvalue'],'key'=>$_REQUEST['key']);
 echo json_encode($response);  
  
}


function products_to_sell(){
  if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id'])){
     $order_id=$_REQUEST['id'];
     $_SESSION['state']['order']['id']=$order_id;
  }else
     $order_id=$_SESSION['state']['order']['id'];

   if(isset( $_REQUEST['store_key']) and is_numeric( $_REQUEST['store_key'])){
     $store_key=$_REQUEST['store_key'];
     $_SESSION['state']['order']['store_key']=$store_key;
   }else
     $store_key=$_SESSION['state']['order']['store_key'];
$conf=$_SESSION['state']['products']['table'];
  if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
    if (!is_numeric($start_from))
        $start_from=0;

    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];

        if ($start_from>0) {
            $page=floor($start_from/$number_results);
            $start_from=$start_from-$page;
        }

    }      else
        $number_results=$conf['nr'];
   
  if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];

    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



   /*  if (isset( $_REQUEST['where'])) */
/*         $where=addslashes($_REQUEST['where']); */
/*     else */
/*         $where=$conf['where']; */


    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;

    
if(isset( $_REQUEST['show_all']) and preg_match('/^(yes|no)$/',$_REQUEST['show_all'])  ){
      
      if($_REQUEST['show_all']=='yes')
	$show_all=true;
      else
       $show_all=false;
      $_SESSION['state']['order']['show_all']=$show_all;
    }else
      $show_all=$_SESSION['state']['order']['show_all'];




    //    print_r($_SESSION['state']['order']);


   $_SESSION['state']['products']['table']=array(
						 'order'=>$order
						 ,'order_dir'=>$order_direction
						 ,'nr'=>$number_results
						 ,'sf'=>$start_from
						 //						 ,'where'=>$where
						 ,'f_field'=>$f_field
						 ,'f_value'=>$f_value
                                                 );
   

if(!$show_all){
  $start_from=0;
  $number_results=1000;
  
}






   if(!$show_all){
     
     $table='  `Order Transaction Fact` OTF  left join `Product History Dimension` PHD on (PHD.`Product Key`=OTF.`Product Key`) left join `Product Dimension` P on (PHD.`Product ID`=P.`Product ID`)  ';
     $where=sprintf(' where `Order Quantity`>0 and `Order Key`=%d',$order_id);
     $sql_qty='';
   }else{
    $table=' `Product Dimension` ';
     $where=sprintf('where `Product Store Key`=%d   ',$store_key);
     $sql_qty=sprintf(',IFNULL((select sum(`Order Quantity`) from `Order Transaction Fact` where `Product Key`=`Product Current Key` and `Order Key`=%d),0) as `Order Quantity`, IFNULL((select sum(`Order Transaction Total Discount Amount`) from `Order Transaction Fact` where `Product Key`=`Product Current Key` and `Order Key`=1165),0) as `Order Transaction Total Discount Amount`, IFNULL((select sum(`Order Transaction Gross Amount`) from `Order Transaction Fact` where `Product Key`=`Product Current Key` and `Order Key`=1165),0) as `Order Transaction Gross Amount` ',$order_id); 

     
   }


  

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
        $sql="select count(*) as total from `Product Dimension`   $where   ";
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
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with code like')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with name like')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
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


    

 $sql="select  `Product Availability`,`Product Sales State`,`Product ID`,`Product Code`,`Product XHTML Short Description`,`Product Price`,`Product Units Per Case`,`Product Record Type`,`Product Web State`,`Product Family Name`,`Product Main Department Name`,`Product Tariff Code`,`Product XHTML Parts`,`Product GMROI`,`Product XHTML Parts`,`Product XHTML Supplied By`,`Product Stock Value`,`Product Main Image` $sql_qty from $table   $where $wheref order by $order $order_direction limit $start_from,$number_results    ";
 
    $res = mysql_query($sql);

    $adata=array();
    //   print $sql;
 while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

   if (is_numeric($row['Product Availability']))
     $stock=number($row['Product Availability']);
   else
     $stock='?';
   $type=$row['Product Sales State'];
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
   

   $adata[]=array(
		  'pid'=>$row['Product ID'],
		  'code'=>$row['Product Code'],
		'description'=>$row['Product XHTML Short Description'],
		'shortname'=>number($row['Product Units Per Case']).'x @'.money($row['Product Price']/$row['Product Units Per Case']).' '._('ea'),
		'family'=>$row['Product Family Name'],
		'dept'=>$row['Product Main Department Name'],
		'expcode'=>$row['Product Tariff Code'],
                     'parts'=>$row['Product XHTML Parts'],
		  'supplied'=>$row['Product XHTML Supplied By'],
		  'gmroi'=>$row['Product GMROI'],
		  'stock_value'=>money($row['Product Stock Value']),
                     'stock'=>$stock,
		  'quantity'=>$row['Order Quantity'],
		  'state'=>$type,
		  'web'=>$web_state,
		  'image'=>$row['Product Main Image'],
		  'type'=>'item',
		  'change'=>'+ -',
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
                                      'total_records'=>$total_records,
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
   

 



  
   

  
   $where.=' and `Delivery Note State`="Ready to be Picked" ';
   

   $wheref='';

  if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Delivery Note Last Updated Date`))<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Delivery Note Last Updated Date`))>=".$f_value."    ";
   elseif($f_field=='customer_name' and $f_value!='')
    $wheref.=" and  `Delivery Note Customer Name` like '".addslashes($f_value)."%'";
   elseif($f_field=='public_id' and $f_value!='')
    $wheref.=" and  `Delivery Note Public ID` like '".addslashes($f_value)."%'";


   


   

   
  $sql="select count(*) as total from `Delivery Note Dimension`   $where $wheref ";
  // print $sql ;
   $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $total=$row['total'];
  }
  if($where==''){
    $filtered=0;
     $total_records=$total;
  }else{
    
      $sql="select count(*) as total from `Delivery Note Dimension`  $where";
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
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders starting with')." <b>$f_value</b>) <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;
     case('customer_name'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with customer")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with customer')." <b>".$f_value."*</b>) <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;  
     case('minvalue'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order minimum value of")." <b>".money($f_value)."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with min value of')." <b>".money($f_value)."*</b>) <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;  
  
 case('max'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order older than")." <b>".number($f_value)."</b> "._('days');
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('last')." <b>".number($f_value)."</b> "._('days orders').") <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;  
     }



   
   $_order=$order;
   $_dir=$order_direction;
   
   $order='`Delivery Note Date Created`';
   if($order=='id')
     $order='`Delivery Note File As`';
   else if($order=='customer')
     $order='`Delivery Note Customer Name`';
   
   
   

  $sql="select `Delivery Note Key`,`Delivery Note ID`,`Delivery Note Customer Key`,`Delivery Note Customer Name`,`Delivery Note Date Created`,`Delivery Note Estimated Weight` from `Delivery Note Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
  // print $sql;
  global $myconf;

   $data=array();

   $res = mysql_query($sql);
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
    
     if($row['Delivery Note Date Created']=='')
       $lap='';
     else
       $lap=RelativeTime(date('U',strtotime($row['Delivery Note Date Created'])));
    
     $w=weight($row['Delivery Note Estimated Weight']);

     $data[]=array(
		   'id'=>$row['Delivery Note ID']
		   ,'public_id'=>sprintf("%d05",$row['Delivery Note ID'])
		   ,'customer'=>$row['Delivery Note Customer Name']
		   ,'wating_lap'=>$lap
		   ,'e_weight'=>$w
		   ,'date'=>$row['Delivery Note Date Created']
		   ,'pick_it'=>_('Pick it')
		   );
   }
mysql_free_result($res);
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'rtext'=>$rtext,
			 'rtext_rpp'=>$rtext_rpp,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$total,
			 'records_perpage'=>$number_results,

			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}