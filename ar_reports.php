<?php
require_once 'common.php';



if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('ES_1'):
es_1();
}

function es_1(){


global $myconf;

  $conf=$_SESSION['state']['customers']['table'];
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


if(isset( $_REQUEST['y']))
     $year=$_REQUEST['y'];
   else
     $year=date('Y',strtotime('today -1 year'));

if(isset( $_REQUEST['umbral']))
     $umbral=$_REQUEST['umbral'];
   else
     $umbral=3000;


  
   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;

   if(isset( $_REQUEST['store_id'])    ){
     $store=$_REQUEST['store_id'];
     $_SESSION['state']['customers']['store']=$store;
   }else
     $store=$_SESSION['state']['customers']['store'];


   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   $_SESSION['state']['customers']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
   $filter_msg='';
   $wheref='';
   
   
   if(is_numeric($store)){
     $where.=sprintf(' and `Customer Store Key`=%d ',$store);
   }
   
$where.=sprintf(' and total>%f and Year(`Invoice Date`)=%d',$umbral,$year );
   
   
  if(($f_field=='customer name'     )  and $f_value!=''){
    $wheref="  and  `Customer Name` like '%".addslashes($f_value)."%'";
  }elseif(($f_field=='postcode'     )  and $f_value!=''){
    $wheref="  and  `Customer Main Address Postal Code` like '%".addslashes($f_value)."%'";
    
    
    
  }else if($f_field=='id'  )
     $wheref.=" and  `Customer ID` like '".addslashes(preg_replace('/\s*|\,|\./','',$f_value))."%' ";
  else if($f_field=='maxdesde' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))<=".$f_value."    ";
  else if($f_field=='mindesde' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))>=".$f_value."    ";
  else if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  `Customer Orders`<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  `Customer Orders`>=".$f_value."    ";
  else if($f_field=='maxvalue' and is_numeric($f_value) )
    $wheref.=" and  `Customer Net Balance`<=".$f_value."    ";
  else if($f_field=='minvalue' and is_numeric($f_value) )
    $wheref.=" and  `Customer Net Balance`>=".$f_value."    ";






   $sql="select count(*) as total from `Customer Dimension`  $where $wheref";

   $res=mysql_query($sql);
     if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

     $total=$row['total'];
   }if($wheref!=''){
     $sql="select count(*) as total_without_filters from `Customer Dimension`  $where ";
     $res=mysql_query($sql);
     if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
    
       $total_records=$row['total_without_filters'];
       $filtered=$row['total_without_filters']-$total;
     }

   }else{
     $filtered=0;
     $filter_total=0;
     $total_records=$total;
   }
    mysql_free_result($res);

   $rtext=$total_records." ".ngettext('identified customers','identified customers',$total_records);
   if($total_records>$number_results)
     $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));

   if($total==0 and $filtered>0){
     switch($f_field){
     case('customer name'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer like")." <b>$f_value</b> ";
       break;
     }
   }
   elseif($filtered>0){
     switch($f_field){
     case('customer name'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('customers with name like')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;
     }
   }else
      $filter_msg='';
   




   $_order=$order;
   $_dir=$order_direction;
   // if($order=='location'){
//      if($order_direction=='desc')
//        $order='country_code desc ,town desc';
//      else
//        $order='country_code,town';
//      $order_direction='';
//    }

//     if($order=='total'){
//       $order='supertotal';
//    }
    

   if($order=='name')
     $order='`Customer File As`';
   elseif($order=='id')
     $order='`Customer ID`';
   elseif($order=='location')
     $order='`Customer Main Location`';
   elseif($order=='orders')
     $order='`Customer Orders`';
   elseif($order=='email')
     $order='`Customer Email`';
   elseif($order=='telephone')
     $order='`Customer Main Telehone`';
   elseif($order=='last_order')
     $order='`Customer Last Order Date`';
   elseif($order=='contact_name')
     $order='`Customer Main Contact Name`';
   elseif($order=='address')
     $order='`Customer Main Location`';
   elseif($order=='town')
     $order='`Customer Main Address Town`';
   elseif($order=='postcode')
     $order='`Customer Main Address Postal Code`';
   elseif($order=='region')
     $order='`Customer Main Address Country First Division`';
   elseif($order=='country')
     $order='`Customer Main Address Country`';
   //  elseif($order=='ship_address')
   //  $order='`customer main ship to header`';
   elseif($order=='ship_town')
     $order='`Customer Main Ship To Town`';
   elseif($order=='ship_postcode')
     $order='`Customer Main Ship To Postal Code`';
   elseif($order=='ship_region')
     $order='`Customer Main Ship To Country Region`';
   elseif($order=='ship_country')
     $order='`Customer Main Ship To Country`';
   elseif($order=='net_balance')
     $order='`Customer Net Balance`';
   elseif($order=='balance')
     $order='`Customer Outstanding Net Balance`';
   elseif($order=='total_profit')
     $order='`Customer Profit`';
   elseif($order=='total_payments')
     $order='`Customer Net Payments`';
   elseif($order=='top_profits')
     $order='`Customer Profits Top Percentage`';
   elseif($order=='top_balance')
     $order='`Customer Balance Top Percentage`';
   elseif($order=='top_orders')
     $order='``Customer Orders Top Percentage`';
   elseif($order=='top_invoices')
     $order='``Customer Invoices Top Percentage`';
    elseif($order=='total_refunds')
     $order='`Customer Total Refunds`';
    
  elseif($order=='activity')
     $order='`Customer Type by Activity`';
  else
  $order='`Customer File As`';
   $sql="select   *,sum(`Invoice Total Amount`) as total from  `Invoice Dimension` I left join  `Customer Dimension` C  on (I.`Invoice Customer Key`=C.`Customer Key`)  $where $wheref  group by `Customer Key`  order by $order $order_direction limit $start_from,$number_results";
     print $sql;
   $adata=array();
  
  
  
  $result=mysql_query($sql);
  while($data=mysql_fetch_array($result, MYSQL_ASSOC)){



  


    $id="<a href='customer.php?id=".$data['Customer Key']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$data['Customer ID']).'</a>'; 
    $name="<a href='customer.php?id=".$data['Customer Key']."'>".$data['Customer Name'].'</a>'; 

    $adata[]=array(
		   'id'=>$id,
		   'name'=>$name,
		   'location'=>$data['Customer Main Location'],
		   'orders'=>number($data['Customer Orders']),
		   'invoices'=>$data['Customer Orders Invoiced'],
		   'email'=>$data['Customer Main XHTML Email'],
		   'telephone'=>$data['Customer Main Telephone'],
		   'last_order'=>strftime("%e %b %Y", strtotime($data['Customer Last Order Date'])),
		   'total_payments'=>money($data['Customer Net Payments']),
		   'net_balance'=>money($data['Customer Net Balance']),
		   'total_refunds'=>money($data['Customer Net Refunds']),
		   'total_profit'=>money($data['Customer Profit']),
		   'balance'=>money($data['Customer Outstanding Net Balance']),


		   'top_orders'=>number($data['Customer Orders Top Percentage']).'%',
		   'top_invoices'=>number($data['Customer Invoices Top Percentage']).'%',
		   'top_balance'=>number($data['Customer Balance Top Percentage']).'%',
		   'top_profits'=>number($data['Customer Profits Top Percentage']).'%',
		   'contact_name'=>$data['Customer Main Contact Name'],
		   'address'=>$data['Customer Main Location'],
		   'town'=>$data['Customer Main Address Town'],
		   'postcode'=>$data['Customer Main Address Postal Code'],
		   'region'=>$data['Customer Main Address Country First Division'],
		   'country'=>$data['Customer Main Address Country'],
		   //		   'ship_address'=>$data['customer main ship to header'],
		   'ship_town'=>$data['Customer Main Ship To Town'],
		   'ship_postcode'>$data['Customer Main Ship To Postal Code'],
		   'ship_region'=>$data['Customer Main Ship To Country Region'],
		   'ship_country'=>$data['Customer Main Ship To Country'],
		   'activity'=>$data['Customer Type by Activity']

		   );
  }
mysql_free_result($result);




  $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
			 'rtext'=>$rtext,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,

			 'records_perpage'=>$number_results,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}


?>