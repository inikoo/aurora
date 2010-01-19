<?php
require_once 'common.php';



if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('pickers_report'):
  pickers_report();
  break;
case('packers_report'):
   packers_report();
   break;
case('customers'):
  list_customers();
  break;
case('ES_1'):
es_1();
break;


}

function pickers_report(){
   $conf=$_SESSION['state']['report']['pickers'];
 //  if(isset( $_REQUEST['sf']))
//      $start_from=$_REQUEST['sf'];
//    else
//      $start_from=$conf['sf'];
//    if(isset( $_REQUEST['nr']))
//      $number_results=$_REQUEST['nr'];
//    else
//      $number_results=$conf['nr'];
  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$conf['order'];
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$conf['order_dir'];
 //    if(isset( $_REQUEST['f_field']))
//      $f_field=$_REQUEST['f_field'];
//    else
//      $f_field=$conf['f_field'];

//   if(isset( $_REQUEST['f_value']))
//      $f_value=$_REQUEST['f_value'];
//    else
//      $f_value=$conf['f_value'];
// if(isset( $_REQUEST['where']))
//      $where=$_REQUEST['where'];
//    else
//      $where=$conf['where'];
  
 if(isset( $_REQUEST['from']))
    $from=$_REQUEST['from'];
  else
    $from=$conf['from'];
  if(isset( $_REQUEST['to']))
    $to=$_REQUEST['to'];
  else
    $to=$conf['to'];


   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;


   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

   $_SESSION['state']['report']['pickers']=array('order'=>$order,'order_dir'=>$order_direction);

   $date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
   if($date_interval['error']){
      $date_interval=prepare_mysql_dates($conf['from'],$conf['to']);
   }else{
     $_SESSION['state']['report']['pickers']['from']=$date_interval['from'];
     $_SESSION['state']['report']['pickers']['to']=$date_interval['to'];
   }

   
   
   $start_from=0;
  
   $filter_msg='';
   $_order=$order;
   $_dir=$order_direction;


   
   $sql=sprintf("select picker_id,alias, sum(if(feedback_id=1 or feedback_id=3,1,0))/count(distinct orden.id) as epo , sum(weight) as weight,position_id ,sum(share*pick_factor) as units ,count(distinct orden.id) as orders, sum(if(feedback_id=1 or feedback_id=3,1,0)) as errors     from orden left join pick on (order_id=orden.id) left join staff on (picker_id=staff.id)where tipo=2 %s  group by picker_id   order by %s %s ",$date_interval['mysql'],addslashes($order),addslashes($order_direction));
   $result=mysql_query($sql);
   $data=array();
   $hours=40;
   $uph=$row['units']/$hours;
   $total=0;
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){

     if($row['position_id']==1){
       $uph=number($row['units']/$hours);
     }else
       $uph='';

     $total++;
     $data[]=array(
		   'tipo'=>($row['position_id']==1?_('FT'):''),
		   'alias'=>$row['alias'],
		   'orders'=>number($row['orders']),
		   'units'=>number($row['units'],0) ,
		   'weight'=>number($row['weight'],1)." "._('Kg'),
		   'errors'=>number($row['errors']),
		   'epo'=>number(100*$row['epo']+0.00001,1)."%",
		   'hours'=>$hours,
		   'uph'=>$uph
		   );
   }

   $number_results=$total;
   $filtered=0;
   if($total==0){
     $rtext=_('No order has been placed yet').'.';
   }elseif($total<$number_results)
     $rtext=$total.' '.ngettext('record returned','records returned',$total);
   else
     $rtext='';
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$res->numRows(),
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}

function packers_report(){
$conf=$_SESSION['state']['report']['packers'];
 //  if(isset( $_REQUEST['sf']))
//      $start_from=$_REQUEST['sf'];
//    else
//      $start_from=$conf['sf'];
//    if(isset( $_REQUEST['nr']))
//      $number_results=$_REQUEST['nr'];
//    else
//      $number_results=$conf['nr'];
  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$conf['order'];
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$conf['order_dir'];
 //    if(isset( $_REQUEST['f_field']))
//      $f_field=$_REQUEST['f_field'];
//    else
//      $f_field=$conf['f_field'];

//   if(isset( $_REQUEST['f_value']))
//      $f_value=$_REQUEST['f_value'];
//    else
//      $f_value=$conf['f_value'];
// if(isset( $_REQUEST['where']))
//      $where=$_REQUEST['where'];
//    else
//      $where=$conf['where'];
  
 if(isset( $_REQUEST['from']))
    $from=$_REQUEST['from'];
  else
    $from=$conf['from'];
  if(isset( $_REQUEST['to']))
    $to=$_REQUEST['to'];
  else
    $to=$conf['to'];


   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;


   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

   $_SESSION['state']['report']['packers']=array('order'=>$order,'order_dir'=>$order_direction);

   $date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
   if($date_interval['error']){
      $date_interval=prepare_mysql_dates($conf['from'],$conf['to']);
   }else{
     $_SESSION['state']['report']['packers']['from']=$date_interval['from'];
     $_SESSION['state']['report']['packers']['to']=$date_interval['to'];
   }

   
   
   $start_from=0;
  
   $filter_msg='';
   $_order=$order;
   $_dir=$order_direction;


   
   $sql=sprintf("select packer_id,alias, sum(if(feedback_id=2 or feedback_id=3,1,0))/count(distinct orden.id) as epo , sum(weight) as weight,position_id ,sum(share*pack_factor) as units ,count(distinct orden.id) as orders, sum(if(feedback_id=2 or feedback_id=3,1,0)) as errors     from orden left join pack on (order_id=orden.id) left join staff on (packer_id=staff.id)where tipo=2 %s  group by packer_id   order by %s %s ",$date_interval['mysql'],addslashes($order),addslashes($order_direction));

   $result=mysql_query($sql);
   $data=array();
   $hours=40;
   print_r($sql);
   $total=0;
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
$uph=$row['units']/$hours;
     if($row['position_id']==2){
       $uph=number($row['units']/$hours);
     }else
       $uph='';

     $total++;
     $data[]=array(
		   'tipo'=>($row['position_id']==2?_('FT'):''),
		   'alias'=>$row['alias'],
		   'orders'=>number($row['orders']),
		   'units'=>number($row['units'],0) ,
		   'weight'=>number($row['weight'],1)." "._('Kg'),
		   'errors'=>number($row['errors']),
		   'epo'=>number(100*$row['epo']+0.00001,1)."%",
		   'hours'=>$hours,
		   'uph'=>$uph
		   );
   }

   $number_results=$total;
   $filtered=0;
   if($total==0){
     $rtext=_('No order has been placed yet').'.';
   }elseif($total<$number_results)
     $rtext=$total.' '.ngettext('record returned','records returned',$total);
   else
     $rtext='';
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$res->numRows(),
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);

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
   
$where.=sprintf(' and `Customer Main Address Country Code`="ESP"   and Year(`Invoice Date`)=%d',$year );
   

$rtext='';
$filtered=0;
$_order='';
$_dir='';
$total=0;

   $sql="select  GROUP_CONCAT(`Invoice Key`) as invoice_keys,`Customer Main Location`,`Customer Key`,`Customer Name`,`Customer ID`,`Customer Main XHTML Email`,count(DISTINCT `Invoice Key`) as invoices,sum(`Invoice Total Amount`) as total, sum(`Invoice Total Net Amount`) as net from  `Invoice Dimension` I left join  `Customer Dimension` C  on (I.`Invoice Customer Key`=C.`Customer Key`)  $where $wheref  group by `Customer Key` order by total desc";
   $adata=array();
  
  
  
  $result=mysql_query($sql);
  while($data=mysql_fetch_array($result, MYSQL_ASSOC)){

if($data['total']<$umbral)
break;  
$total++;

$tax1=0;
$tax2=0;

$sql2=sprintf("select `Tax Code`,sum(`Tax Amount`) as amount from `Invoice Tax Bridge` where `Invoice Key` in (%s) group by `Tax Code`  ", $data['invoice_keys']);
$res2=mysql_query($sql2);
while($row2=mysql_fetch_array($res2)){
//print_r($row2);
if($row2['Tax Code']=='IVA'){
$tax1=$row2['amount'];
}
if($row2['Tax Code']=='I2'){
$tax2=$row2['amount'];
}

}

    $id="<a href='customer.php?id=".$data['Customer Key']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$data['Customer ID']).'</a>'; 
    $name="<a href='customer.php?id=".$data['Customer Key']."'>".$data['Customer Name'].'</a>'; 

$tax1=0;
$tax2=0;

    $adata[]=array(
		   'id'=>$id,
		   'name'=>$name,
		   'total'=>money($data['total']),
		   'net'=>money($data['net']),
		   'tax1'=>money($tax1),
		   'tax2'=>money($tax2),
		   'invoices'=>number($data['invoices']),
		   'location'=>$data['Customer Main Location']
		  

		   );
  }
mysql_free_result($result);

$rtext=number($total).' '._('Records found'); 


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


function list_customers(){


global $myconf;

  $conf=$_SESSION['state']['report']['customers'];

  $start_from=0;
  
  if(isset( $_REQUEST['nr'])){
     $number_results=$_REQUEST['nr'];
     $_SESSION['state']['report']['customers']['top']=$number_results;
  }else
     $number_results=$conf['top'];

  if(isset( $_REQUEST['o'])){
    $order=$_REQUEST['o'];
    $_SESSION['state']['report']['customers']['criteria']=$order;
  }else
    $order=$conf['criteria'];
  $order_direction='desc';
   $order_dir='desc';
 
 if(isset( $_REQUEST['to'])){
    $to=$_REQUEST['to'];
    $_SESSION['state']['report']['customers']['to']=$to;
  }else
    $to=$conf['to'];



 if(isset( $_REQUEST['from'])){
    $from=$_REQUEST['from'];
    $_SESSION['state']['report']['customers']['from']=$from;
  }else
    $from=$conf['from'];




/*   if(isset( $_REQUEST['f_field'])) */
/*     $f_field=$_REQUEST['f_field']; */
/*   else */
/*     $f_field=$conf['f_field']; */

/*   if(isset( $_REQUEST['f_value'])) */
/*      $f_value=$_REQUEST['f_value']; */
/*    else */
/*      $f_value=$conf['f_value']; */


  
   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;

   if(isset( $_REQUEST['store_id'])    ){
     $store=$_REQUEST['store_id'];
     $_SESSION['state']['report']['customers']['store']=$store;
   }else
     $store=$_SESSION['state']['report']['customers']['store'];


  
   $filter_msg='';
   $wheref='';
   $int=prepare_mysql_dates($from,$to,'`Invoice Date`','only dates');

   $where=sprintf('where true  %s',$int['mysql']);

   
   if(is_numeric($store)){
     $where.=sprintf(' and `Customer Store Key`=%d ',$store);
   }else{
     $where.=sprintf(' and `Customer Store Key` in (%s) ',$store);

   }
   

   $filtered=0;
   $rtext='';
   $total=$number_results;
   
/*   if(($f_field=='customer name'     )  and $f_value!=''){ */
/*     $wheref="  and  `Customer Name` like '%".addslashes($f_value)."%'"; */
/*   }elseif(($f_field=='postcode'     )  and $f_value!=''){ */
/*     $wheref="  and  `Customer Main Address Postal Code` like '%".addslashes($f_value)."%'"; */
    
    
    
/*   }else if($f_field=='id'  ) */
/*      $wheref.=" and  `Customer ID` like '".addslashes(preg_replace('/\s*|\,|\./','',$f_value))."%' "; */
/*   else if($f_field=='maxdesde' and is_numeric($f_value) ) */
/*     $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))<=".$f_value."    "; */
/*   else if($f_field=='mindesde' and is_numeric($f_value) ) */
/*     $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))>=".$f_value."    "; */
/*   else if($f_field=='max' and is_numeric($f_value) ) */
/*     $wheref.=" and  `Customer Orders`<=".$f_value."    "; */
/*   else if($f_field=='min' and is_numeric($f_value) ) */
/*     $wheref.=" and  `Customer Orders`>=".$f_value."    "; */
/*   else if($f_field=='maxvalue' and is_numeric($f_value) ) */
/*     $wheref.=" and  `Customer Net Balance`<=".$f_value."    "; */
/*   else if($f_field=='minvalue' and is_numeric($f_value) ) */
/*     $wheref.=" and  `Customer Net Balance`>=".$f_value."    "; */






/*    $sql="select count(*) as total from `Customer Dimension`  $where $wheref"; */

/*    $res=mysql_query($sql); */
/*      if($row=mysql_fetch_array($res, MYSQL_ASSOC)) { */

/*      $total=$row['total']; */
/*    }if($wheref!=''){ */
/*      $sql="select count(*) as total_without_filters from `Customer Dimension`  $where "; */
/*      $res=mysql_query($sql); */
/*      if($row=mysql_fetch_array($res, MYSQL_ASSOC)) { */
    
/*        $total_records=$row['total_without_filters']; */
/*        $filtered=$row['total_without_filters']-$total; */
/*      } */

/*    }else{ */
/*      $filtered=0; */
/*      $filter_total=0; */
/*      $total_records=$total; */
/*    } */
/*     mysql_free_result($res); */

/*    $rtext=$total_records." ".ngettext('identified customers','identified customers',$total_records); */
/*    if($total_records>$number_results) */
/*      $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp')); */

/*    if($total==0 and $filtered>0){ */
/*      switch($f_field){ */
/*      case('customer name'): */
/*        $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer like")." <b>$f_value</b> "; */
/*        break; */
/*      } */
/*    } */
/*    elseif($filtered>0){ */
/*      switch($f_field){ */
/*      case('customer name'): */
/*        $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('customers with name like')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>"; */
/*        break; */
/*      } */
/*    }else */
/*       $filter_msg=''; */
   




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
    

   if($order=='invoices')
     $order='`Invoices`';

   else   
     $order='`Balance`';

  
   $sql="select  `Customer Type by Activity`,`Customer Last Order Date`,`Customer Main Telephone`,`Customer Key`,`Customer ID`,`Customer Name`,`Customer Main Location`,`Customer Main XHTML Email`,`Customer Main Address Town`,`Customer Main Address Country First Division`,`Customer Main Ship To Postal Code`,count(DISTINCT `Invoice Key`) as Invoices , sum(`Invoice Total Net Amount`) as Balance  from `Customer Dimension` C left join `Invoice Dimension` I on (`Customer Key`=`Invoice Customer Key`)   $where $wheref  group by `Invoice Customer Key` order by $order $order_direction limit $start_from,$number_results";
   // print $sql;
   $adata=array();
  
  
   $position=1;
  $result=mysql_query($sql);
  while($data=mysql_fetch_array($result, MYSQL_ASSOC)){



  


    $id="<a href='customer.php?id=".$data['Customer Key']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$data['Customer ID']).'</a>'; 
    $name="<a href='customer.php?id=".$data['Customer Key']."'>".$data['Customer Name'].'</a>'; 

    $adata[]=array(
		   'position'=>'<b>'.$position++.'</b>',
		   'id'=>$id,
		   'name'=>$name,
		   'location'=>$data['Customer Main Location'],
		   //  'orders'=>number($data['Customer Orders']),
		   'invoices'=>$data['Invoices'],
		   'email'=>$data['Customer Main XHTML Email'],
		   'telephone'=>$data['Customer Main Telephone'],
		   'last_order'=>strftime("%e %b %Y", strtotime($data['Customer Last Order Date'])),
		   // 'total_payments'=>money($data['Customer Net Payments']),
		   'net_balance'=>money($data['Balance']),
		   //'total_refunds'=>money($data['Customer Net Refunds']),
		   //'total_profit'=>money($data['Customer Profit']),
		   //'balance'=>money($data['Customer Outstanding Net Balance']),


		   //'top_orders'=>number($data['Customer Orders Top Percentage']).'%',
		   //'top_invoices'=>number($data['Customer Invoices Top Percentage']).'%',
		   //'top_balance'=>number($data['Customer Balance Top Percentage']).'%',
		   //'top_profits'=>number($data['Customer Profits Top Percentage']).'%',
		   //'contact_name'=>$data['Customer Main Contact Name'],
		   //'address'=>$data['Customer Main Location'],
		   //'town'=>$data['Customer Main Address Town'],
		   //'postcode'=>$data['Customer Main Address Postal Code'],
		   //'region'=>$data['Customer Main Address Country First Division'],
		   //'country'=>$data['Customer Main Address Country'],
		   //		   'ship_address'=>$data['customer main ship to header'],
		   //'ship_town'=>$data['Customer Main Ship To Town'],
		   //'ship_postcode'>$data['Customer Main Ship To Postal Code'],
		   //'ship_region'=>$data['Customer Main Ship To Country Region'],
		   //'ship_country'=>$data['Customer Main Ship To Country'],
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