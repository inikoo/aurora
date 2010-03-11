<?php
/*
 File: ar_edit_porders.php 

 Ajax Server Anchor for the Order Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyrigh (c) 2010, Kaktus 
 
 Version 2.0
*/
require_once 'common.php';
require_once 'class.PurchaseOrder.php';
include_once('class.SupplierDeliveryNote.php');

if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }


$tipo=$_REQUEST['tipo'];


switch($tipo){
case('delete'):
  delete_purchase_order();
  break;
  case('cancel'):
  cancel_purchase_order();
  break;
  case('submit'):
    require_once 'class.Staff.php';

  submit_purchase_order();
  break;
case('take_values_from_pos'):
  take_values_from_pos();
  break;
case('po_transactions_to_process'):
 po_transactions_to_process();
   break;
case('dn_transactions_to_process'):
 dn_transactions_to_process();
   break;
case('edit_new_porder'):
  edit_new_porder();
  break;

default:
  $response=array('state'=>404,'resp'=>_('Operation not found'));
  echo json_encode($response);
}

function take_values_from_pos(){
 

 if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id'])){
   $supplier_dn_key=$_REQUEST['supplier_dn_key'];
   $_SESSION['state']['supplier_dn']['id']=$supplier_dn_key;
 }else
   $supplier_dn_key=$_SESSION['state']['supplier_dn']['id'];
 

 $supplier_dn=New SupplierDeleveryNote($supplier_dn_key);
 $supplier_dn->take_values_from_pos();
 
 





  
}

function delete_purchase_order(){
  if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id'])){
     $purchase_order_key=$_REQUEST['id'];
     $_SESSION['state']['porder']['id']=$purchase_order_key;
  }else
     $purchase_order_key=$_SESSION['state']['porder']['id'];

  $po=new PurchaseOrder($purchase_order_key);
  $supplier_key=$po->data['Purchase Order Supplier Key'];
  $po->delete();
  if(!$po->error){
    $response= array('state'=>200,'supplier_key'=>$supplier_key);

  }else{
    $response= array('state'=>400,'msg'=>$po->msg);

  }
   echo json_encode($response);  
}
function submit_purchase_order() {
    global $user;
    if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id'])) {
        $purchase_order_key=$_REQUEST['id'];
        $_SESSION['state']['porder']['id']=$purchase_order_key;
    } else
        $purchase_order_key=$_SESSION['state']['porder']['id'];

    $po=new PurchaseOrder($purchase_order_key);

    $data=array(
              'Purchase Order Submitted Date'=>date('Y-m-d H:i:s'),
              'Purchase Order Main Buyer Key'=>$user->data['User Parent Key'],
              'Purchase Order Main Buyer Name'=>$user->data['User Alias'],
              'Purchase Order Main Source Type'=>'Unknown',
              'Purchase Order Estimated Receiving Date'=>''
		);
    
    if (isset($_REQUEST['date_type']) and $_REQUEST['date_type']=='manual' ) {
        if (isset($_REQUEST['submit_date']) and  isset($_REQUEST['submit_time']) ) {
            $_date=$_REQUEST['submit_date'].' '.$_REQUEST['submit_time'];
            $date_data=prepare_mysql_datetime($_date);
            if (!$date_data['ok']) {
                $response= array('state'=>400,'msg'=>_('Wrong date/time'));
                echo json_encode($response);
                return;
            }
            $data['Purchase Order Submitted Date']=$date_data['mysql_date'];
        }
    }


    if (isset($_REQUEST['estimated_date']) and $_REQUEST['estimated_date']==''    ) {
        $date_data=prepare_mysql_datetime($_REQUEST['estimated_date'],'midday');
        if ($date_data['ok']) {
            $data['Purchase Order Estimated Receiving Date']=$date_data['mysql_date'];
        }

    }
    if (isset($_REQUEST['submit_method'])  ) {
            $data['Purchase Order Main Source Type']=$_REQUEST['submit_method'];
         }
    if (isset($_REQUEST['staff_key'])  ) {
    
    $staff=new Staff($_REQUEST['staff_key']);
    if(!$staff->id){
      $response= array('state'=>400,'msg'=>'Wrong Sumitter');
                echo json_encode($response);
                return;
    }
    
            $data['Purchase Order Main Buyer Key']=$staff->id;
                        $data['Purchase Order Main Buyer Name']=$staff->data['Staff Alias'];

         }


    $po->submit($data);
    if (!$po->error) {
        $response= array('state'=>200);

    } else {
        $response= array('state'=>400,'msg'=>$po->msg);

    }
    echo json_encode($response);
}
function cancel_purchase_order() {
    global $user;
    if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id'])) {
        $purchase_order_key=$_REQUEST['id'];
        $_SESSION['state']['porder']['id']=$purchase_order_key;
    } else
        $purchase_order_key=$_SESSION['state']['porder']['id'];

    $po=new PurchaseOrder($purchase_order_key);


    $data=array(
              'Purchase Order Cancelled Date'=>date('Y-m-d H:i:s'),
              'Purchase Order Cancel Note'=>'',
		);
    
 

    if (isset($_REQUEST['cancelled_date']) and $_REQUEST['cancelled_date']==''    ) {
        $date_data=prepare_mysql_datetime($_REQUEST['cancelled_date'],'datetime');
        if ($date_data['ok']) {
            $data['Purchase Order Cancelled Date']=$date_data['mysql_date'];
        }

    }
   if(isset($_REQUEST['note'])){
$data['Purchase Order Cancel Notes']=$_REQUEST['note'];
}




    $po->cancel($data);
    if (!$po->error) {
        $response= array('state'=>200);

    } else {
        $response= array('state'=>400,'msg'=>$po->msg);

    }
    echo json_encode($response);
}

function dn_transactions_to_process(){

 if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id'])){
   $supplier_dn_key=$_REQUEST['supplier_dn_key'];
   $_SESSION['state']['supplier_dn']['id']=$supplier_dn_key;
 }else
   $supplier_dn_key=$_SESSION['state']['supplier_dn']['id'];
 

 $supplier_dn=New SupplierDeliveryNote($supplier_dn_key);
 $supplier_key=$supplier_dn->data['Supplier Delivery Note Supplier Key'];
 




 $pos='';
 if(isset( $_REQUEST['pos'])){
   $pos=preg_replace('/[^\d\,]/','',$_REQUEST['pos']);
   
   $_SESSION['state']['supplier_dn']['pos']=$pos;
 }else
   $pos=$_SESSION['state']['supplier_dn']['pos'];

 


 $conf=$_SESSION['state']['supplier_dn']['products'];
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
      $_SESSION['state']['supplier_dn']['show_all']=$show_all;
    }else
      $show_all=$_SESSION['state']['supplier_dn']['show_all'];




    //    print_r($_SESSION['state']['supplier_dn']);


$_SESSION['state']['supplier_dn']['products']=array(
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


   if($show_all){
      $table=' `Supplier Product Dimension` PD ';
     $where=sprintf('where `Supplier Key`=%d   ',$supplier_key);
     $sql_qty=sprintf(',IFNULL((select sum(`Purchase Order Quantity`) from `Purchase Order Transaction Fact` where `Supplier Product Key`=`Supplier Product Current Key` and `Purchase Order Key`in (%s)),0) as `Purchase Order Quantity`, IFNULL((select sum(`Purchase Order Net Amount`) from `Purchase Order Transaction Fact` where `Supplier Product Key`=`Supplier Product Current Key` and `Purchase Order Key` in (%s)),0) as `Purchase Order Net Amount` ',$pos,$pos); 





   }else{
     $table='  `Purchase Order Transaction Fact` OTF  left join `Supplier Product History Dimension` PHD on (`SPH Key`=`Supplier Product Key`) left join `Supplier Product Dimension` PD on (PD.`Supplier Product Code`=PHD.`Supplier Product Code` and PD.`Supplier Key`=PHD.`Supplier Key`) ';
     $where=sprintf(' where  `Purchase Order Key` in (%s)',$pos);
     $sql_qty=', `Purchase Order Quantity`,`Purchase Order Net Amount`';

  
     
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
         print $sql;
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
    $order='`Supplier Product Code`';
 
    if ($order=='code')
      $order='`Supplier Product Code`';
    else if ($order=='name')
      $order='`Supplier Product Name`';
   
    elseif($order=='parts') {
      $order='`Supplier Product XHTML Parts`';
    }
    elseif($order=='supplied') {
      $order='`Supplier Product XHTML Supplied By`';
    }
 

    

 $sql="select  `Supplier Product XHTML Used In` ,`Supplier Product Unit Type`,`Supplier Product Tax Code`,`Supplier Product Current Key`,PD.`Supplier Product Code`,`Supplier Product Name`,`Supplier Product Cost`,`Supplier Product Units Per Case`,`Supplier Product Unit Type`  $sql_qty from $table   $where $wheref order by $order $order_direction limit $start_from,$number_results    ";
 
    $res = mysql_query($sql);

    $adata=array();
    // print $sql;
 while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

if($row['Purchase Order Quantity']==0)
  $amount='';
else
$amount=money($row['Purchase Order Net Amount']);

$unit_type=$row['Supplier Product Unit Type'];
if($unit_type=='ea'){
$unit_type='piece';
}
   $adata[]=array(
		  'id'=>$row['Supplier Product Current Key'],
		  'code'=>$row['Supplier Product Code'],
		  'description'=>'<span style="font-size:95%">'.number($row['Supplier Product Units Per Case']).'x '.$row['Supplier Product Name'].' @'.money($row['Supplier Product Cost']/$row['Supplier Product Units Per Case']).' '.$row['Supplier Product Unit Type'].'</span>',
		'used_in'=>$row['Supplier Product XHTML Used In'],
		  'quantity'=>$row['Purchase Order Quantity'],
		  'quantity_static'=>number($row['Purchase Order Quantity']),
		  'amount'=>$amount,
		  'unit_type'=>$unit_type,

		  'tax_code'=>$row['Supplier Product Tax Code'],
		  'add'=>'+',
		  'remove'=>'-',

		     
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



function po_transactions_to_process(){
 if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id'])){
     $purchase_order_key=$_REQUEST['id'];
     $_SESSION['state']['porder']['id']=$purchase_order_key;
  }else
     $purchase_order_key=$_SESSION['state']['porder']['id'];

 if(isset( $_REQUEST['supplier_key']) and is_numeric( $_REQUEST['supplier_key'])){
   $supplier_key=$_REQUEST['supplier_key'];
   $_SESSION['state']['porder']['supplier_key']=$supplier_key;
 }else
   $supplier_key=$_SESSION['state']['porder']['supplier_key'];
 

 $conf=$_SESSION['state']['porder']['products'];
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
      $_SESSION['state']['porder']['show_all']=$show_all;
    }else
      $show_all=$_SESSION['state']['porder']['show_all'];




    //    print_r($_SESSION['state']['porder']);


$_SESSION['state']['porder']['products']=array(
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


   if($show_all){
      $table=' `Supplier Product Dimension` PD ';
     $where=sprintf('where `Supplier Key`=%d   ',$supplier_key);
     $sql_qty=sprintf(',IFNULL((select sum(`Purchase Order Quantity`) from `Purchase Order Transaction Fact` where `Supplier Product Key`=`Supplier Product Current Key` and `Purchase Order Key`=%d),0) as `Purchase Order Quantity`, IFNULL((select sum(`Purchase Order Net Amount`) from `Purchase Order Transaction Fact` where `Supplier Product Key`=`Supplier Product Current Key` and `Purchase Order Key`=%d),0) as `Purchase Order Net Amount` ',$purchase_order_key,$purchase_order_key); 





   }else{
     $table='  `Purchase Order Transaction Fact` OTF  left join `Supplier Product History Dimension` PHD on (`SPH Key`=`Supplier Product Key`) left join `Supplier Product Dimension` PD on (PD.`Supplier Product Code`=PHD.`Supplier Product Code` and PD.`Supplier Key`=PHD.`Supplier Key`) ';
     $where=sprintf(' where  `Purchase Order Key`=%d',$purchase_order_key);
     $sql_qty=', `Purchase Order Quantity`,`Purchase Order Net Amount`';

  
     
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
    $order='`Supplier Product Code`';
 
    if ($order=='code')
      $order='`Supplier Product Code`';
    else if ($order=='name')
      $order='`Supplier Product Name`';
   
    elseif($order=='parts') {
      $order='`Supplier Product XHTML Parts`';
    }
    elseif($order=='supplied') {
      $order='`Supplier Product XHTML Supplied By`';
    }
 

    

 $sql="select  `Supplier Product XHTML Used In` ,`Supplier Product Unit Type`,`Supplier Product Tax Code`,`Supplier Product Current Key`,PD.`Supplier Product Code`,`Supplier Product Name`,`Supplier Product Cost`,`Supplier Product Units Per Case`,`Supplier Product Unit Type`  $sql_qty from $table   $where $wheref order by $order $order_direction limit $start_from,$number_results    ";
 
    $res = mysql_query($sql);

    $adata=array();
    // print $sql;
 while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

if($row['Purchase Order Quantity']==0)
  $amount='';
else
$amount=money($row['Purchase Order Net Amount']);

$unit_type=$row['Supplier Product Unit Type'];
if($unit_type=='ea'){
$unit_type='piece';
}
   $adata[]=array(
		  'id'=>$row['Supplier Product Current Key'],
		  'code'=>$row['Supplier Product Code'],
		  'description'=>'<span style="font-size:95%">'.number($row['Supplier Product Units Per Case']).'x '.$row['Supplier Product Name'].' @'.money($row['Supplier Product Cost']/$row['Supplier Product Units Per Case']).' '.$row['Supplier Product Unit Type'].'</span>',
		'used_in'=>$row['Supplier Product XHTML Used In'],
		  'quantity'=>$row['Purchase Order Quantity'],
		  'quantity_static'=>number($row['Purchase Order Quantity']),
		  'amount'=>$amount,
		  'unit_type'=>$unit_type,

		  'tax_code'=>$row['Supplier Product Tax Code'],
		  'add'=>'+',
		  'remove'=>'-',

		     
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



function edit_new_porder(){
  
  $purchase_order_key=$_SESSION['state']['porder']['id'];
  $supplier_product_key=$_REQUEST['id'];
  $quantity=$_REQUEST['newvalue'];

  if(!isset($_REQUEST['qty_type']))
    $quantity_type='Piece';
  else
  $quantity_type=$_REQUEST['qty_type'];
  if(is_numeric($quantity) and $quantity>=0){

  $order=new PurchaseOrder($purchase_order_key);
  

  $product=new SupplierProduct('key',$supplier_product_key);

  $gross=$quantity*$product->data['Supplier Product Cost'];


  $data=array(
	    
	      'date'=>date('Y-m-d H:i:s')
	      ,'Supplier Product Key'=>$product->data['Supplier Product Current Key']
	      ,'line_number'=>$order->get_next_line_number()
	      ,'amount'=>$gross
	      ,'qty'=>$quantity
	      ,'qty_type'=>$quantity_type
	      ,'tax_code'=>$product->data['Supplier Product Tax Code']
	      ,'Current Dispatching State'=>'In Process'
	      ,'Current Payment State'=>'Waiting Payment'
	     
	      );
  

  $transaction_data=$order->add_order_transaction($data);
 

  $adata=array();
  
  $order->update_item_totals_from_order_transactions();
  $order->update_totals_from_order_transactions();
 
  //$order->update_charges();
  //$order->get_original_totals();
  // $order->update_totals();
  //$order->update_totals_from_order_transactions();
  

  

  $updated_data=array(
		      'goods'=>$order->get('Items Net Amount')
		      //,'order_net'=>$order->get('Total Net Amount')
		      ,'vat'=>$order->get('Total Tax Amount')
		      //,'order_charges'=>$order->get('Charges Net Amount')
		      // ,'order_credits'=>$order->get('Net Credited Amount')
		      ,'shipping'=>$order->get('Shipping Net Amount')
		      ,'total'=>$order->get('Total Amount')
		      ,'distinct_products'=>$order->get('Number Items')
		      );
  






  $response= array('state'=>200,'quantity'=>$transaction_data['qty'],'key'=>$_REQUEST['key'],'data'=>$updated_data,'to_charge'=>$transaction_data['to_charge']);
  }else
    $response= array('state'=>200,'quantity'=>$_REQUEST['oldvalue'],'key'=>$_REQUEST['key']);
 echo json_encode($response);  
  
}
