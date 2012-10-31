<?php
require_once 'common.php';
require_once 'class.TimeSeries.php';

if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }




$plot_type=$_REQUEST['tipo'];
switch($plot_type){
case('part_stock_history'):
  list_part_stock_history();
  break;
case('invoiced_month_sales'):
list_invoices_per_month();
break;
case("general");


if(!isset($_REQUEST['period']) or !isset($_REQUEST['item'])  or !isset($_REQUEST['category']) or !isset($_REQUEST['item_keys']) )
  return;

$period=$_REQUEST['period'];
$tipo=$_REQUEST['item'];
if($tipo=='product')
  $tipo='product id';
if($tipo=='part')
  $tipo='part sku';


$from=false;
if(isset($_REQUEST['from']))
   $from=$_REQUEST['from'];

$to=false;
if(isset($_REQUEST['to']))
   $to=$_REQUEST['to'];

$category=$_REQUEST['category'];
$split=(isset($_REQUEST['split']) and preg_match('/yes/i',$_REQUEST['split']) ?true:false);
$item_keys=$_REQUEST['item_keys'];

if(isset($_REQUEST['stack']) and preg_match('/yes/i',$_REQUEST['stack'])){

list_stack_item($tipo,$category,$period,$item_keys,$from,$to);

}else{
list_item($tipo,$category,$period,$item_keys,$split,$from,$to);
}
break;
case('invoiced_store_month_sales'):
list_store_invoices_per_month();
break;
case('invoiced_department_month_sales'):
list_department_invoices_per_month();
break;
case('invoiced_week_sales'):
list_invoices_per_week();
break;
case('sales_by_store'):
case('sales_share_by_store'):
 $tipo=$_REQUEST['dtipo'];
 include_once('report_dates.php');
 $int=prepare_mysql_dates($from,$to,'`Invoice Date`','date start end');
 if($_REQUEST['dtipo']=='y'){
   list_invoices_per_store_per_month($int);
 }elseif($_REQUEST['dtipo']=='m'){
    list_invoices_per_store_per_day($int);
 }
 break;
 case('product_week_outers'): 
 case('product_week_sales'):
   
   list_product_sales_per_week();
   

   break;
 case('product_month_outers'): 
 case('product_month_sales'):
   list_product_sales_per_month();
 
   break;
 case('product_quarter_outers'): 
 case('product_quarter_sales'):
   list_product_sales_per_quarter();
 
   break;

 case('product_year_outers'): 
 case('product_year_sales'):
   list_product_sales_per_year();
 
   break;
case('montly_sales_group_by_month'):
list_invoices_grouped_per_month();

   break;
case('net_diff1y_sales_month'):
list_invoices_1y_change_per_month();
 break;
case('active_customers'): 
  if(isset($_REQUEST['store_keys']) )
    $store_keys=$_REQUEST['store_keys'];
  else
    $store_keys=$_SESSION['state']['customers']['store'];

  

  if(preg_match('/[^\d\,\s]/',$store_keys))
    return;

  $data=array('Store Keys'=>$store_keys);
  if($_REQUEST['period']=='m')
    list_active_customer_population_per_month($data);
  
  break;
case('customers'): 

  if(isset($_REQUEST['store_keys']) )
    $store_keys=$_REQUEST['store_keys'];
  else
    $store_keys=$_SESSION['state']['customers']['store'];

  if(preg_match('/[^\d\,\s]/',$store_keys))
    return;
  $data=array('Store Keys'=>$store_keys);
  if($_REQUEST['period']=='m')
    list_customer_population_per_month($data);
  elseif($_REQUEST['period']=='y')
    list_customer_population_per_year($data);
  break;
default:
   $response=array('state'=>405,'resp'=>_('Operation not found'));
   echo json_encode($response);
   
 }



function list_product_sales_per_week(){
  $mode=$_SESSION['state']['product']['mode'];
  $tag=$_SESSION['state']['product']['tag'];
  $first_day=$_SESSION['state']['product']['plot_data']['week']['first_day'];

  
   $sql="select date_format(`First Day`,'%c') as month, `First Day` as date, `Year Week` as yearweek,date_format(`First Day`,'%v %x') as week,  UNIX_TIMESTAMP(`First Day`)+36000 as utime  from `Week Dimension` where `First Day`>'$first_day' and `First Day` < NOW(); ";

   $data=array();
   $res = mysql_query($sql);
   $i=0;
   $last_month='';

   while($row=mysql_fetch_array($res)) {
     $index[$row['yearweek']]=$i;
     $date=$row['utime'].'x  '.strftime("%b%y",$row['utime']);
     $data[]=array(
		   'tip_asales'=>_('No sales this week'),
		   'tip_profit'=>_('No sales this week'),

		   'tip_out'=>_('No sales this week'),
		   'tip_bonus'=>_('No bonus this week'),
		   'date'=>$date,
		   'week'=>$row['week'],
		   'utime'=>$row['utime'],
		   'asales'=>0,
		   'profit'=>0,
		   'out'=>0,
		   'bonus'=>0,
		   'outofstock'=>0,
		   );

     $i++;
    }
mysql_free_result($res);
   if($mode=='code')
     $where=sprintf(" where  PD.`Product Code`=%s and `Order Last Updated Date`>%s    ",prepare_mysql($tag),prepare_mysql($first_day));
   elseif($mode=='id')
     $where=sprintf(" where  PD.`Product ID`=%d and `Order Last Updated Date`>%s ",$tag,prepare_mysql($first_day));
   elseif($mode=='key')
     $where=sprintf(" where PD.`Product Key`=%d and `Order Last Updated Date`>%s ",$tag,prepare_mysql($first_day));
   

   $sql=sprintf("select YEARWEEK(`Order Last Updated Date`) as yearweek,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)as asales,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`-`Cost Supplier`)as profit,sum(`Shipped Quantity`)as _out from `Order Transaction Fact` OTF left join `Product Dimension` PD on (PD.`Product Key`=OTF.`Product Key`)   %s   group by YEARWEEK(`Order Last Updated Date`)",$where);
   // print $sql;
   $res=mysql_query($sql);
   while($row=mysql_fetch_array($res)){
      if(isset($index[$row['yearweek']])){
	$_index=$index[$row['yearweek']];
	$data[$_index]['asales']=(float)$row['asales'];
	$data[$_index]['profit']=(float)$row['profit'];

	$data[$_index]['out']=(int)$row['_out'];
	$fday=strftime("%d %b", strtotime('@'.$data[$_index]['utime']));
	$data[$_index]['tip_out']=_('Outer Dispatched')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".number($row['_out']).' '._('Outers');
	$data[$_index]['tip_asales']=_('Sales')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".money($row['asales']);
	$data[$_index]['tip_profit']=_('Profit')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".money($row['profit']);
	$data[$_index]['bonus']=0;
	$data[$_index]['tip_bonus']='';
	//	$data[$_index]['tip_bonus']=_('Free Bonus')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".number($row['bono']).' '._('Outers');
      }
    }
    mysql_free_result($res);
   
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 )
		   );

   echo json_encode($response);
}
function list_product_sales_per_month(){ 
$mode=$_SESSION['state']['product']['mode'];
  $tag=$_SESSION['state']['product']['tag'];
  $first_day=$_SESSION['state']['product']['plot_data']['month']['first_day'];
  // print $first_day;
  
   $sql="select date_format(`First Day`,'%c') as month, `First Day` as date, `Year Month` as yearmonth  from `Month Dimension` where `First Day`>'$first_day' and `First Day` < NOW(); ";

   $data=array();
   $res = mysql_query($sql);
   $i=0;
   $last_month='';

   while($row=mysql_fetch_array($res)) {
     $index[$row['yearmonth']]=$i;


     $data[]=array(
		   'tip_asales'=>_('No sales this month'),
		   'tip_profit'=>_('No sales this month'),
		   'tip_out'=>_('No sales this month'),
		   'tip_bonus'=>_('No bonus this month'),
		   'month'=>$row['month'],
		   '_date'=>$row['date'],
		   'date'=>strftime("%b %y",strtotime($row['date'])),

		   'asales'=>0,
		   'profit'=>0,
		   'out'=>0,
		   'bonus'=>0,
		   'outofstock'=>0,
		   );

     $i++;
    }
mysql_free_result($res);
   if($mode=='code')
     $where=sprintf(" where  PD.`Product Code`=%s and `Order Last Updated Date`>%s    ",prepare_mysql($tag),prepare_mysql($first_day));
   elseif($mode=='id')
     $where=sprintf(" where  PD.`Product ID`=%d and `Order Last Updated Date`>%s ",$tag,prepare_mysql($first_day));
   elseif($mode=='key')
     $where=sprintf(" where PD.`Product Key`=%d and `Order Last Updated Date`>%s ",$tag,prepare_mysql($first_day));


   $sql=sprintf("select  date_format(`Order Last Updated Date`,'%%Y%%m')  as yearmonth,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)as asales,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`-`Cost Supplier`)as profit,sum(`Shipped Quantity`)as _out from `Order Transaction Fact` OTF left join `Product Dimension` PD on (PD.`Product Key`=OTF.`Product Key`)   %s   group by  date_format(`Order Last Updated Date`,'%%Y%%m') ",$where);
   // print $sql;
   $res=mysql_query($sql);
   while($row=mysql_fetch_array($res)){
     //     print $row['yearmonth']."\n";
      if(isset($index[$row['yearmonth']])){
	$_index=$index[$row['yearmonth']];
	$data[$_index]['asales']=(float)$row['asales'];
	$data[$_index]['profit']=(float)$row['profit'];

	$data[$_index]['out']=(int)$row['_out'];
	$data[$_index]['tip_out']=_('Outers Dispatched')."\n".strftime("%b %y",strtotime($data[$_index]['_date']))."\n".number($row['_out']).' '._('Outers');
	$data[$_index]['tip_asales']=_('Sales')."\n".strftime("%b %y",strtotime($data[$_index]['_date']))."\n".money($row['asales']);
	$data[$_index]['tip_profit']=_('Profit')."\n".strftime("%b %y",strtotime($data[$_index]['_date']))."\n".money($row['profit']);
	$data[$_index]['bonus']=0;
	$data[$_index]['tip_bonus']='';
	//	$data[$_index]['tip_bonus']=_('Free Bonus')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".number($row['bono']).' '._('Outers');
      }
    }
   //  print_r($data);
   mysql_free_result($res);
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 )
		   );

     echo json_encode($response);
      }
function list_product_sales_per_quarter(){
       $mode=$_SESSION['state']['product']['mode'];
  $tag=$_SESSION['state']['product']['tag'];
  $first_day=$_SESSION['state']['product']['plot_data']['quarter']['first_day'];
  // print $first_day;
  
   $sql="select Quarter(`First Day`) as quarter, `First Day` as date, `Year Quarter` as yearquarter  from `Quarter Dimension` where `First Day`>'$first_day' and `First Day` < NOW(); ";
   // print $sql;
   $data=array();
   $res = mysql_query($sql);
   $i=0;
   $last_quarter='';

   while($row=mysql_fetch_array($res)) {
     $index[$row['yearquarter']]=$i;


     $data[]=array(
		   'tip_asales'=>_('No sales this quarter'),
		   'tip_profit'=>_('No sales this quarter'),
		   'tip_out'=>_('No sales this quarter'),
		   'tip_bonus'=>_('No bonus this quarter'),
		   'quarter'=>$row['quarter'],
		   '_date'=>$row['date'],
		   'date'=>strftime("%b %y",strtotime($row['date'])),

		   'asales'=>0,
		   'profit'=>0,
		   'out'=>0,
		   'bonus'=>0,
		   'outofstock'=>0,
		   );

     $i++;
    }
    mysql_free_result($res);

   if($mode=='code')
     $where=sprintf(" where  PD.`Product Code`=%s and `Order Last Updated Date`>%s    ",prepare_mysql($tag),prepare_mysql($first_day));
   elseif($mode=='id')
     $where=sprintf(" where  PD.`Product ID`=%d and `Order Last Updated Date`>%s ",$tag,prepare_mysql($first_day));
   elseif($mode=='key')
     $where=sprintf(" where PD.`Product Key`=%d and `Order Last Updated Date`>%s ",$tag,prepare_mysql($first_day));


   $sql=sprintf("select  CONCAT(Year(`Order Last Updated Date`),Quarter(`Order Last Updated Date`))  as yearquarter,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)as asales,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`-`Cost Supplier`)as profit,sum(`Shipped Quantity`)as _out from `Order Transaction Fact` OTF left join `Product Dimension` PD on (PD.`Product Key`=OTF.`Product Key`)   %s   group by  CONCAT(Year(`Order Last Updated Date`),Quarter(`Order Last Updated Date`))  ",$where);
   //     print $sql;
   $res=mysql_query($sql);
   while($row=mysql_fetch_array($res)){
     //     print $row['yearquarter']."\n";
      if(isset($index[$row['yearquarter']])){
	$_index=$index[$row['yearquarter']];
	$data[$_index]['asales']=(float)$row['asales'];
	$data[$_index]['profit']=(float)$row['profit'];

	$data[$_index]['out']=(int)$row['_out'];
	$data[$_index]['tip_out']=_('Outers Dispatched')."\n".strftime("%b %y",strtotime($data[$_index]['_date']))."\n".number($row['_out']).' '._('Outers');
	$data[$_index]['tip_asales']=_('Sales')."\n".strftime("%b %y",strtotime($data[$_index]['_date']))."\n".money($row['asales']);
	$data[$_index]['tip_profit']=_('Profit')."\n".strftime("%b %y",strtotime($data[$_index]['_date']))."\n".money($row['profit']);
	$data[$_index]['bonus']=0;
	$data[$_index]['tip_bonus']='';
	//	$data[$_index]['tip_bonus']=_('Free Bonus')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".number($row['bono']).' '._('Outers');
      }
    }
    mysql_free_result($res);
   //  print_r($data);
   
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 )
		   );

      echo json_encode($response);
      }
function list_product_sales_per_year(){
 $mode=$_SESSION['state']['product']['mode'];
  $tag=$_SESSION['state']['product']['tag'];
  $first_day=$_SESSION['state']['product']['plot_data']['year']['first_day'];
  // print $first_day;
  
  $i=0;
  $year=date('Y',strtotime( $first_day));
  while( $year    <= date('Y')  ) {
     $index[$year]=$i;


     $data[]=array(
		   'tip_asales'=>_('No sales this year'),
		   'tip_profit'=>_('No sales this year'),
		   'tip_out'=>_('No sales this year'),
		   'tip_bonus'=>_('No bonus this year'),
		   'date'=>$year,
		   'asales'=>0,
		   'profit'=>0,
		   'out'=>0,
		   'bonus'=>0,
		   'outofstock'=>0,
		   );
     $year++;
     $i++;
     if($i>10)
       exit;
    }

   if($mode=='code')
     $where=sprintf(" where  PD.`Product Code`=%s and `Order Last Updated Date`>%s    ",prepare_mysql($tag),prepare_mysql($first_day));
   elseif($mode=='id')
     $where=sprintf(" where  PD.`Product ID`=%d and `Order Last Updated Date`>%s ",$tag,prepare_mysql($first_day));
   elseif($mode=='key')
     $where=sprintf(" where PD.`Product Key`=%d and `Order Last Updated Date`>%s ",$tag,prepare_mysql($first_day));


   $sql=sprintf("select  Year(`Order Last Updated Date`)  as year,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)as asales,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`-`Cost Supplier`)as profit,sum(`Shipped Quantity`)as _out from `Order Transaction Fact` OTF left join `Product Dimension` PD on (PD.`Product Key`=OTF.`Product Key`)   %s   group by  Year(`Order Last Updated Date`)  ",$where);
   //     print $sql;
   $res=mysql_query($sql);
   while($row=mysql_fetch_array($res)){
     //     print $row['yearquarter']."\n";
      if(isset($index[$row['year']])){
	$_index=$index[$row['year']];
	$data[$_index]['asales']=(float)$row['asales'];
	$data[$_index]['profit']=(float)$row['profit'];

	$data[$_index]['out']=(int)$row['_out'];
	$data[$_index]['tip_out']=$row['year'].' '._('Outers Dispatched')."\n".number($row['_out']).' '._('Outers');
	$data[$_index]['tip_asales']=$row['year'].' '._('Sales')."\n".money($row['asales']);
	$data[$_index]['tip_profit']=$row['year'].' '._('Profits')."\n".money($row['profit']);
	$data[$_index]['bonus']=0;
	$data[$_index]['tip_bonus']='';
	//	$data[$_index]['tip_bonus']=_('Free Bonus')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".number($row['bono']).' '._('Outers');
      }
    }
    mysql_free_result($res);
   //  print_r($data);
   
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 )
		   );

      echo json_encode($response);
}
function list_invoices_per_month(){
  $tm=new TimeSeries(array('m','invoices'));
  $_data=$tm->plot_data();
   foreach($_data as $__data)
    $data[]=$__data;
  $response=array('resultset'=>
		  array('state'=>200,
			'data'=>$data,
			)
		  );

  echo json_encode($response);
}

function list_store_invoices_per_month(){
  if(!isset($_REQUEST['store_keys']))
    return;
  $store_keys=$_REQUEST['store_keys'];
  $tm=new TimeSeries(array('m','store '.$store_keys.' sales'));
  $_data=$tm->plot_data();
  $data=array();
  foreach($_data as $__data)
    $data[]=$__data;
  $response=array('resultset'=>
		  array('state'=>200,
			'data'=>$data,
			)
		  );

  echo json_encode($response);
}


function list_stack_item($tipo,$category,$period,$item_keys,$split=true,$from=false,$to=false){
 $_data=array();

 $item_keys=preg_replace('/\(|\)/','',$item_keys);

 foreach(preg_split('/\s*,\s*/',$item_keys) as $key){

      if(!is_numeric($key))
	continue;
      $tm=new TimeSeries(array($period,"$tipo ($key) $category"));
      $tmp_data=$tm->plot_data($from,$to);
      //  print_r($tmp_data);
      foreach($tmp_data as $date=>$values){
	if(isset($values['tail'])){
	  $value=$values['tail'];
	  $value_tip=$values['tip_tail'];

	}elseif(isset($values['value'])){
	  $value=$values['value'];
	  $value_tip=$values['tip_value'];

	}else
	  continue;
	
	
	
	$_values=array('key_'.$tm->name_key=>$value,'tip_key_'.$tm->name_key=>$value_tip);

	if(isset($_data[$date])){
	  $_data[$date]=array_merge($_data[$date],$_values);
	}else
	  $_data[$date]=$_values;
      }



    }

 ksort($_data);

 foreach($_data as $date=>$values){
   $_date=array('date'=>$date);
   $data[]=array_merge($_date,$values);
 }


 //$data=array();
 //$data[]= array("date"=>"2003-09","key_2"=>64081,"key_4"=>164081);
 //$data[]=array("date"=>"2003-10","key_2"=>67081,"key_4"=>264081) ;

 
 $response=array('resultset'=>
		  array(
			'state'=>200,
			'data'=>$data
			)
		  );
  
  echo json_encode($response);

}


function list_item($tipo,$category,$period,$item_keys,$split=false,$from=false,$to=false){
  $_data=array();

  if($split){
    $item_keys=preg_replace('/\(|\)/','',$item_keys);
    foreach(preg_split('/\s*,\s*/',$item_keys) as $key){
	    
      if(!is_numeric($key))
	continue;
  //print "$tipo ($key) $category $from $to $period";
      
      $tm=new TimeSeries(array($period,"$tipo ($key) $category"));
      //print_r($tm);
      //exit;
     
     
      $tmp_data=$tm->plot_data($from,$to);
      // print_r($tmp_data);

      unset($tm);
      foreach($tmp_data as $key=>$values){
	if(isset($_data[$key])){
	  $_data[$key]=array_merge($_data[$key],$values);
	}else
	  $_data[$key]=$values;
      }
     
    }
    

  }else{
     //print "$period $tipo $item_keys $category";


    $tm=new TimeSeries(array($period,"$tipo $item_keys $category"));
    $_data=$tm->plot_data($from,$to);
  }
  //$_data=asort($_data);
  ksort($_data);

  $data=array();
  foreach($_data as $__data)
    $data[]=$__data;
  $response=array('resultset'=>
		  array(
			'state'=>200,
			'data'=>$data
			)
		  );
  
  echo json_encode($response);


}


function list_department_invoices_per_month(){
  $_data=array();
  if(!isset($_REQUEST['department_keys']))
    return;
  $department_keys=$_REQUEST['department_keys'];
  if(isset($_REQUEST['split']) and $_REQUEST['split']=='yes'){
    $department_keys=preg_replace('/\(|\)/','',$department_keys);
    foreach(preg_split('/\s*,\s*/',$department_keys) as $key){
      if(!is_numeric($key))
	continue;

      //print "product department ($key) sales\n";
      $tm=new TimeSeries(array('m',"product department ($key) sales"));
      
    
      
      $tmp_data=$tm->plot_data();
      unset($tm);
      foreach($tmp_data as $key=>$values){
	if(isset($_data[$key])){
	  $_data[$key]=array_merge($_data[$key],$values);
	}else
	  $_data[$key]=$values;
      }
 
      
    }
    

  }else{
  $tm=new TimeSeries(array('m','product department '.$department_keys.' sales'));
  $_data=$tm->plot_data();
 

  }

  foreach($_data as $__data)
    $data[]=$__data;
  $response=array('resultset'=>
		  array(
			'state'=>200,
			'data'=>$data
			)
		  );

  echo json_encode($response);
}


function list_invoices_per_month_live_data(){
global $myconf;

 $time=strtotime($myconf['data_since']);
 

 
 $pivot=strtotime($myconf['data_since']);
 $today=strtotime('today');
 while($pivot<$today){
   
   $tip=_('Sales')." ".strftime("%B %Y",$pivot)."\n".money(0)."\n(0 "._('Invoices').")";
   $tip_losses='';

   $data[date('Y-m',$pivot)]=array(
				   'tip_sales'=>$tip,
				   'tip_losses'=>$tip_losses,
				   'sales'=>0,
				   'losses'=>0,
				   'date'=>strftime("%m/%y",$pivot)
				   );
   $pivot=strtotime(date('Y-m-d',$pivot).' +1 month');
 }




 

  if(date("d",$time)==1)
     $from=date("Y-m-d",$time);
   else{
     $from=date("Y-",$time).(date("m",$time)+1).'-01';
   }

  $sql="SELECT count(*) as invoices,month(`Invoice Date`) as month, UNIX_TIMESTAMP(`Invoice Date`) as date ,substring(`Invoice Date`, 1,7) AS dd ,sum(`Invoice Total Net Amount`) as sales FROM `Invoice Dimension` where `Invoice Date`>'$from'  GROUP BY dd";
  // print $sql; 

 $prev_month='';
 $prev_year=array();
 $res = mysql_query($sql); 
 while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
 if(is_numeric($prev_month)){
   $diff=$row['sales']-$prev_month;
   $diff_prev_month=percentage($diff,$prev_month,1,'NA','%',true)." "._('change (last month)')."\n";
   //       print $row['sales']."---------  $prev_month ----------    $diff_prev_month   <br >";
 }else
   $diff_prev_month='';
 
 if(isset($prev_year[$row['month']])){
   $diff=$row['sales']-$prev_year[$row['month']];
   $diff_prev_year=percentage($diff,$prev_year[$row['month']],1,'NA','%',true)." "._('change (last year)')."\n";
   //	 print $row['sales']."------ ---  ".$prev_year[$row['month']]." ----- $diff  -----    $diff_prev_year   <br >";
 }else{
   //	print $row['sales']."  <br >";
   $diff_prev_year='';
 }
 
 $credits=0;//$row['credits'];
 $outstoke_value=0;//=$row['outstock'];
 $losses=$credits+$outstoke_value;
 $percentage_losses=percentage($losses,$row['sales']);
 
 $tip=_('Sales')." ".strftime("%B %Y", strtotime('@'.$row['date']))."\n".money($row['sales'])."\n".$diff_prev_month.$diff_prev_year."(".$row['invoices']." "._('Invoices').")";
 $tip_losses=_('Lost Sales')." ".strftime("%B %Y", strtotime('@'.$row['date']))."\n".money($losses)." ($percentage_losses)".($credits>0?"\n".money($credits)." "._('due to refund/credits'):"").($outstoke_value>0?"\n".money($outstoke_value)." "._('due to out of stock'):"");
 
 $data[$row['dd']]=array(
		   'tip_sales'=>$tip,
		   'tip_losses'=>$tip_losses,
		   'sales'=>(float) $row['sales'],
		   'losses'=>$losses,
		   'date'=>strftime("%m/%y", strtotime('@'.$row['date']))
		   );
     $prev_month=$row['sales'];
     $prev_year[$row['month']]=$row['sales'];
   }
   mysql_free_result($res);
   $_data=array();
   $i=0;
   foreach($data as $__data){
     $_data[]=$__data;

   }



 $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$_data,
			 )
		   );



    echo json_encode($response);
}

function list_invoices_per_week(){
 $first_day='2004-07-01';
 
 $sql="select date_format(`First Day`,'%c') as month, `First Day` as date, `Year Week` as yearweek,date_format(`First Day`,'%v %x') as week,  UNIX_TIMESTAMP(`First Day`)+36000 as utime  from `Week Dimension` where `First Day`>'$first_day' and `First Day` < NOW(); ";

 $result=mysql_query($sql);
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $_data[$row['yearweek']]=array('sales'=>0,'tip_sales'=>'','date'=>$row['yearweek']);
   }
 mysql_free_result($result);
  $sql=sprintf("SELECT yearweek(`Invoice Date`) AS dd, COUNT(`Invoice Key`) as orders ,sum(`Invoice Total Net Amount`) as sales FROM `Invoice Dimension` where  `Invoice Date`>%s  GROUP BY dd"
    ,prepare_mysql($first_day));
  //  print $sql;  
 $data=array();
 $prev_week='';
 $prev_year=array();
  $result=mysql_query($sql);
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     if(is_numeric($prev_week)){
       $diff=$row['sales']-$prev_week;
       $diff_prev_week=percentage($diff,$prev_week,1,'NA','%',true)." "._('change (previous week)')."\n";
       //       print $row['sales']."---------  $prev_month ----------    $diff_prev_month   <br >";
     }else
       $diff_prev_week='';
     $diff_prev_year='';
 //      if(isset($prev_year[$row['month']])){
// 	$diff=$row['sales']-$prev_year[$row['month']];
// 	$diff_prev_year=percentage($diff,$prev_year[$row['month']],1,'NA','%',true)." "._('change (last year)')."\n";
// 	//	 print $row['sales']."------ ---  ".$prev_year[$row['month']]." ----- $diff  -----    $diff_prev_year   <br >";
//       }else{
// 	//	print $row['sales']."  <br >";
// 	$diff_prev_year='';
//       }

   //    $credits=0;//$row['credits'];
//       $outstoke_value=0;//=$row['outstock'];
//       $losses=$credits+$outstoke_value;
//       $percentage_losses=percentage($losses,$row['sales']);

     //    $tip=_('Sales')." ".strftime("%B %Y", strtotime('@'.$row['date']))."\n".money($row['sales'])."\n".$diff_prev_month.$diff_prev_year."(".$row['invoices']." "._('Orders').")";
//       $tip_losses=_('Lost Sales')." ".strftime("%B %Y", strtotime('@'.$row['date']))."\n".money($losses)." ($percentage_losses)".($credits>0?"\n".money($credits)." "._('due to refund/credits'):"").($outstoke_value>0?"\n".money($outstoke_value)." "._('due to out of stock'):"");
     $tip=$row['dd'];
     $_data[$row['dd']]=array(
			     'tip_sales'=>$tip,
			     //'tip_losses'=>$tip_losses,
			     'sales'=>(float) $row['sales'],
			     //'losses'=>$losses,
			     'date'=>$row['dd']//strftime("%m/%y", strtotime('@'.$row['date']))
			     );
   // $prev_month=$row['sales'];
   //  $prev_year[$row['month']]=$row['sales'];
   }
   mysql_free_result($result);
   $data=array();
   $i=0;
   foreach($_data as $__data){
     $data[]=$__data;
    // print $i++." ".$__data['sales']."\n";
   }



 $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 )
		   );



 echo json_encode($response);
// echo '{"resultset":{"state":200,"data":{"tip":"Sales October 2008\n\u00a329,085.85\n-87.4% change (last month)\n-89.5% change (last year)\n(240 Orders)","tip_losses":"Lost Sales October 2008\n\u00a30.00 (0.0%)","sales":"34429","losses":0,"date":"10-2008"}}}';

}
function list_active_customer_population_per_month($data){
  $store_keys=$data['Store Keys'];
  global $myconf;

  $sql=sprintf("select min(`Customer First Order Date`) first_day from `Customer Dimension`   where `Customer Store Key` in (%s) ; ",$store_keys);;
  $res = mysql_query($sql); 
  if($row=mysql_fetch_array($res)) {
     $first_day=$row['first_day'];
     $first_yearmonth=date("Ym",strtotime($first_day));
  }else
    return;

   $sql="select date_format(`First Day`,'%c') as month, `First Day` as date, `Year Month` as yearmonth  from kbase.`Month Dimension` where `Last Day`>'$first_day' and `First Day` < NOW(); ";
   // print $first_day;
   $data=array();
   $res = mysql_query($sql);
   $i=0;
   $last_month='';
   $res = mysql_query($sql); 
   while($row=mysql_fetch_array($res)) {
     $index[$row['yearmonth']]=$i;


     $data[]=array(
		   'tip_lost'=>_('No customer lost'),
		   'tip_new'=>_('No new customers'),
		   'tip_active'=>_('No active customers'),
		   'tip_diff'=>_('No change in the number of customers'),
		   'month'=>$row['month'],
		   '_date'=>$row['date'],
		   'date'=>strftime("%b %y",strtotime($row['date'])),
		   'yearmonth'=>$row['yearmonth'],
		   'new'=>0,
		   'lost'=>0,
		   'active'=>0,
		   
		   );

     $i++;
    }

   mysql_free_result($res);



   $sql="select count(*) as new, DATE_FORMAT(`Customer First Order Date`,'%Y%m') yearmonth from `Customer Dimension`  where `Actual Customer`='Yes' and `Customer Store Key` in (".$store_keys.") group by DATE_FORMAT(`Customer First Order Date`,'%m%Y')";
   // print $sql;
   $res=mysql_query($sql);
   while($row=mysql_fetch_array($res)){
     //     print $row['yearmonth']."\n";
      if(isset($index[$row['yearmonth']])){
	$_index=$index[$row['yearmonth']];
	$data[$_index]['new']=(float)$row['new'];
	$data[$_index]['tip_new']=_('New Customers')."\n".strftime("%b %y",strtotime($data[$_index]['_date']))."\n".number($row['new']);
      }
    }

mysql_free_result($res);
   
   $sql="select count(*) as  lost  , DATE_FORMAT(`Customer Lost Date`,'%Y%m') yearmonth  from `Customer Dimension` where `Active Customer`='No'  and `Actual Customer`='Yes' and  `Customer Store Key` in (".$store_keys.") group by DATE_FORMAT(`Customer Lost Date`,'%m%Y');";
   //print $sql;
   $res=mysql_query($sql);
   while($row=mysql_fetch_array($res)){
     //     print $row['yearmonth']."\n";
     if(isset($index[$row['yearmonth']])){
	$_index=$index[$row['yearmonth']];
	$data[$_index]['lost']=(float)$row['lost'];
	$data[$_index]['tip_lost']=_('Lost Customers')."\n".strftime("%b %y",strtotime($data[$_index]['_date']))."\n".number($row['lost']);
      }
    }
    mysql_free_result($res);
   $_data[]=0;
   $active=0;
   foreach($data as $_index=>$value){
     $active+=($data[$_index]['new']-$data[$_index]['lost']);
     //$active+=($data[$_index]['new']);
     $data[$_index]['active']=$active;
     $data[$_index]['tip_active']=_('Active Customers')."\n".strftime("%b %y",strtotime($data[$_index]['_date']))."\n".number($active);

     $data[$_index]['tip_diff']=strftime("%b %y",strtotime($data[$_index]['_date']))." "._('Customer Growth')."\n".number($data[$_index]['new']-$data[$_index]['lost']);

   }
   $_data=array();


  

    foreach($data as $_index=>$value){
   
      
       if($first_yearmonth<=$data[$_index]['yearmonth']){
       
	$_data[]=array(
		   'tip_lost'=>$value['tip_lost'],
		   'tip_new'=>$value['tip_new'],
		   'tip_active'=>$value['tip_active'],
		   'tip_diff'=>$value['tip_diff'],
		   'date'=>$value['date'],
		   'new'=>$value['new'],
		   'lost'=>-$value['lost'],
		   'active'=>$value['active'],
		   'diff'=>$value['new']-$value['lost']
		   );

	
	   }

   }




   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$_data,
			 )
		   );

      echo json_encode($response);
}


function list_customer_population_per_month($data){
 
  $store_keys=$data['Store Keys'];
  
  $sql=sprintf("select min(`Customer First Contacted Date`) first_day, count(*) as number from `Customer Dimension` where `Customer Store Key` in (%s) ; ",$store_keys);;

  $res = mysql_query($sql); 
  if($row=mysql_fetch_array($res)) {
    if($row['number']==0)
      return;
    $first_day=$row['first_day'];
    $first_yearmonth=date("Ym",strtotime($first_day));
  }
   

  

  $sql="select date_format(`First Day`,'%c') as month, `First Day` as date, `Year Month` as yearmonth  from kbase.`Month Dimension` where `First Day`>'$first_day' and `First Day` < NOW(); ";

  $data=array();
  $res = mysql_query($sql);
  $i=0;
  $last_month='';
  $res = mysql_query($sql); 
  while($row=mysql_fetch_array($res)) {
    $index[$row['yearmonth']]=$i;


    $data[]=array(
		  'tip_lost'=>_('No customer lost'),
		  'tip_new'=>_('No new customers'),
		  'tip_active'=>_('No active customers'),
		  'tip_diff'=>_('No change in the number of customers'),
		  'month'=>$row['month'],
		  '_date'=>$row['date'],
		  'date'=>strftime("%b %y",strtotime($row['date'])),
		  'yearmonth'=>$row['yearmonth'],
		  'new'=>0,
		  'lost'=>0,
		  'active'=>0,
		   
		  );

    $i++;
  }

  mysql_free_result($res);



  $sql="select count(*) as new, DATE_FORMAT(`Customer First Contacted Date`,'%Y%m') yearmonth from `Customer Dimension` where `Customer Store Key` in ($store_keys)   group by DATE_FORMAT(`Customer First Contacted Date`,'%m%Y')";
  //print $sql;
  $res=mysql_query($sql);
  while($row=mysql_fetch_array($res)){
    //     print $row['yearmonth']."\n";
    if(isset($index[$row['yearmonth']])){
      $_index=$index[$row['yearmonth']];
      $data[$_index]['new']=(float)$row['new'];
      $data[$_index]['tip_new']=_('New Customers')."\n".strftime("%b %y",strtotime($data[$_index]['_date']))."\n".number($row['new']);
    }
  }

  mysql_free_result($res);
   
 
  $_data[]=0;
  $active=0;
  foreach($data as $_index=>$value){
    $last_month_customers=0;
    $active+=($data[$_index]['new']);
    $data[$_index]['active']=$active;

    $data[$_index]['tip_active']=strftime("%b %y",strtotime($data[$_index]['_date']))."\n"._('Total customers contacts').": ".number($active)."\n"._('New this month').": ".number($data[$_index]['new']);

    $data[$_index]['tip_diff']=strftime("%b %y",strtotime($data[$_index]['_date']))." "._('Customer Growth')."\n".number($data[$_index]['new']-$last_month_customers);

  }
  $_data=array();

  foreach($data as $_index=>$value){
   
      
    if($first_yearmonth<$data[$_index]['yearmonth']){
       
      $_data[]=array(
		     'tip_lost'=>$value['tip_lost'],
		     'tip_new'=>$value['tip_new'],
		     'tip_active'=>$value['tip_active'],
		     'tip_diff'=>$value['tip_diff'],
		     'date'=>$value['date'],
		     'new'=>$value['new'],
		     'lost'=>-$value['lost'],
		     'active'=>$value['active'],
		     'diff'=>$value['new']-$last_month_customers
		     );

	
    }

  }

//print_r($_data);
//     exit;

  //   exit;

  $response=array('resultset'=>
		  array('state'=>200,
			'data'=>$_data,
			)
		  );

  echo json_encode($response);
}


function list_customer_population_per_year(){
$store_keys=$data['Store Keys'];
$sql=sprintf("select YEAR(MIN(`Customer First Contacted Date`)) as data_since from `Customer Dimension` where `Customer Store Key` in (%s) ;",$store_keys);
  $res=mysql_query($sql);
  if($row=mysql_fetch_array($res)){
    $first_year=$row['data_since'];

  }else{
    global $myconf;
    $first_day=$myconf['data_since'];
    $first_year=date("Y",strtotime($first_day));
  }    

  

  $current_year=date('Y');
  if($first_year>=$current_year)
    return;
  $last_year=$current_year;
  


  $year=$first_year;

    $i=0;
  while($year<=$last_year) {
    $index[$year]=$i;

    $date=$year.'-01-01';
    $data[]=array(
		  'tip_lost'=>_('No customer lost'),
		  'tip_new'=>_('No new customers'),
		  'tip_active'=>_('No active customers'),
		  'tip_diff'=>_('No change in the number of customers'),
		  '_date'=>$date,
		  'year'=>$year,
		  'date'=>strftime("%Y",strtotime($date)),
		  'yearmonth'=>$date,
		  'new'=>0,
		  'lost'=>0,
		  'active'=>0
		  
		  );
    
    $i++;
    $year++;
  }




  $sql="select count(*) as new, YEAR(`Customer First Contacted Date`) year from `Customer Dimension`  where `Customer Store Key` in (".$store_keys.")  group by YEAR(`Customer First Contacted Date`)";
 
 $res=mysql_query($sql);
  while($row=mysql_fetch_array($res)){
    //     print $row['yearmonth']."\n";
    if(isset($index[$row['year']])){
      $_index=$index[$row['year']];
      $data[$_index]['new']=(float)$row['new'];
      $data[$_index]['tip_new']=$row['year'].' '._('New Customers')."\n".number($row['new']);
    }
  }

  mysql_free_result($res);
   
 
  $_data[]=0;
  $active=0;
  foreach($data as $_index=>$value){
    $last_month_customers=0;
    $active+=($data[$_index]['new']);
    $data[$_index]['active']=$active;
    $data[$_index]['tip_active']=strftime("%Y",strtotime($data[$_index]['_date']))."\n"._('Total customers contacts').": ".number($active)."\n"._('New this year').": ".number($data[$_index]['new']);

    $data[$_index]['tip_diff']=strftime("%Y",strtotime($data[$_index]['_date']))." "._('Customer Growth')."\n".number($data[$_index]['new']-$last_month_customers);

  }
  $_data=array();

  foreach($data as $_index=>$value){
   
      
    if($first_year<$data[$_index]['year']){
       
      $_data[]=array(
		     'tip_lost'=>$value['tip_lost'],
		     'tip_new'=>$value['tip_new'],
		     'tip_active'=>$value['tip_active'],
		     'tip_diff'=>$value['tip_diff'],
		     'date'=>$value['date'],
		     'new'=>$value['new'],
		     'lost'=>-$value['lost'],
		     'active'=>$value['active'],
		     'diff'=>$value['new']-$last_month_customers
		     );

	
    }

  }



  //   exit;

  $response=array('resultset'=>
		  array('state'=>200,
			'data'=>$_data,
			)
		  );

  echo json_encode($response);
}


function list_invoices_grouped_per_month(){
$number_years=4;
  $from='2004-07-00';
  $current_year=date('Y');
  $data=array(
	      1=>''
	      ,2=>''
	      ,3=>''
	      ,4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'');


  foreach($data as $key=>$value){
    $data[$key]['date']=strftime("%b", strtotime('2000-01-01 + '.($key-1).' month'));
    for($i=date('Y');$i>=date('Y')-5;$i--){
      $data[$key]['sales'.$i]=0;
      $data[$key]['tip_sales'.$i]=_('No Sales');
    }
  }

  

  foreach (range( $current_year-$number_years,  $current_year) as $year) {
$sql="SELECT year(`Invoice Date`)as year, count(*) as invoices,month(`Invoice Date`) as month, UNIX_TIMESTAMP(`Invoice Date`) as date ,sum(`Invoice Total Net Amount`) as sales FROM `Invoice Dimension` where year(`Invoice Date`)=$year  GROUP BY month  order by month(`Invoice Date`)";
//print "$sql<br>";

 $prev_month='';
 $prev_year=array();
 $res = mysql_query($sql); 
  while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
     if(is_numeric($prev_month)){
       $diff=$row['sales']-$prev_month;
       $diff_prev_month=percentage($diff,$prev_month,1,'NA','%',true)." "._('change (last month)')."\n";
       //       print $row['sales']."---------  $prev_month ----------    $diff_prev_month   <br >";
     }else
       $diff_prev_month='';
     
      if(isset($data['sales'.$year-1][$row['month']])){
	$prev_year=$data['sales'.$year-1][$row['month']];
	$diff=$row['sales']-$prev_year;
	$diff_prev_year=percentage($diff,$prev_year,1,'NA','%',true)." "._('change (last year)')."\n";
	//	 print $row['sales']."------ ---  ".$prev_year[$row['month']]." ----- $diff  -----    $diff_prev_year   <br >";
      }else{
	//	print $row['sales']."  <br >";
	$diff_prev_year='';
      }

      $credits=0;//$row['credits'];
      $outstoke_value=0;//=$row['outstock'];
      $losses=$credits+$outstoke_value;
      $percentage_losses=percentage($losses,$row['sales']);

      $tip=_('Sales')." ".strftime("%B %Y", strtotime('@'.$row['date']))."\n".money($row['sales'])."\n".$diff_prev_month.$diff_prev_year."(".$row['invoices']." "._('Orders').")";
      $tip_losses=_('Lost Sales')." ".strftime("%B %Y", strtotime('@'.$row['date']))."\n".money($losses)." ($percentage_losses)".($credits>0?"\n".money($credits)." "._('due to refund/credits'):"").($outstoke_value>0?"\n".money($outstoke_value)." "._('due to out of stock'):"");
      
      $data[$row['month']]['sales'.$row['year']]=(float)$row['sales'];
      $data[$row['month']]['tip_sales'.$row['year']]=$tip;
      $data[$row['month']]['date']=strftime("%b", strtotime('@'.$row['date']));
      

     $prev_month=$row['sales'];
     $prev_year[$row['month']]=$row['sales'];
   }
   mysql_free_result($res);
   
 }
  $_data=array();


  foreach($data as $key=>$dt){
    
    $_data[]=$dt;
  }

  //print_r($_data);
  $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$_data,
			 )
		   );

   echo json_encode($response);
}
function list_invoices_1y_change_per_month(){
global $myconf;
 $time=strtotime($myconf['data_since']);
  if(date("d",$time)==1)
    $from=date("Y-m-d",$time);
  else{
    $from=date("Y-",$time).(date("m",$time)+1).'-01';
  }
  $sql="SELECT count(*) as invoices,month(`Invoice Date`) as month, UNIX_TIMESTAMP(`Invoice Date`) as date ,substring(`Invoice Date`, 1,7) AS dd, COUNT(`Invoice Key`)as orders ,sum(`Invoice Total Net Amount`) as sales FROM `Invoice Dimension` where `Invoice Date`>'$from'  GROUP BY dd";
  //print $sql;  
 $data=array();
  $prev_month='';
 $prev_year=array();
 $res = mysql_query($sql); 
while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
  
     if(is_numeric($prev_month)){
       $diff=$row['sales']-$prev_month;
       $diff_prev_month=percentage($diff,$prev_month,1,'NA','%',true)." "._('change (last month)')."\n";
       //       print $row['sales']."---------  $prev_month ----------    $diff_prev_month   <br >";
     }else
       $diff_prev_month='';
     
      if(isset($prev_year[$row['month']])){
	$diff=$row['sales']-$prev_year[$row['month']];
	$diff_prev_year=percentage($diff,$prev_year[$row['month']],1,'NA','%',true)." "._('change (last year)')."\n";
	//	 print $row['sales']."------ ---  ".$prev_year[$row['month']]." ----- $diff  -----    $diff_prev_year   <br >";
      }else{
	//	print $row['sales']."  <br >";
	$diff_prev_year='';
      }

      $credits=0;//$row['credits'];
      $outstoke_value=0;//=$row['outstock'];
      $losses=$credits+$outstoke_value;
      $percentage_losses=percentage($losses,$row['sales']);
      
      $tip=_('Sales')." ".strftime("%B %Y", strtotime('@'.$row['date']))."\n".money($row['sales'])."\n".$diff_prev_month.$diff_prev_year."(".$row['invoices']." "._('Orders').")";
      $tip_losses=_('Lost Sales')." ".strftime("%B %Y", strtotime('@'.$row['date']))."\n".money($losses)." ($percentage_losses)".($credits>0?"\n".money($credits)." "._('due to refund/credits'):"").($outstoke_value>0?"\n".money($outstoke_value)." "._('due to out of stock'):"");
      
      if(isset($prev_year[$row['month']])){
	$data[]=array(
		      'tip_sales_diff'=>$tip,
		      'tip_sales_diff_per'=>$tip,
		      'sales_diff'=>(float) $row['sales']-$prev_year[$row['month']],
		      'sales_diff_per'=>(float) 100*($row['sales']-$prev_year[$row['month']])/$prev_year[$row['month']],
		      'losses'=>$losses,
		      'date'=>strftime("%m/%y", strtotime('@'.$row['date']))
		      );
      }
      $prev_month=$row['sales'];
     $prev_year[$row['month']]=$row['sales'];
   }
   mysql_free_result($res);
  

 $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 )
		   );



    echo json_encode($response);

}
function list_invoices_per_store_per_month($int){
   $_int=preg_replace('/Invoice Date/i','First Day',$int[0]);;
    $sql=sprintf("select date_format(`First Day`,'%%c') as month, `First Day` as date, `Year Month` as yearmonth  from `Month Dimension` where true  %s ; ",$_int);
    // print $sql;
    
    $data=array();
    $res = mysql_query($sql);
    $i=0;
    $last_month='';


    $sql=sprintf("select CONCAT(`Store Code`,'',`Invoice Category`) as tag from `Invoice Dimension`  left join `Store Dimension` S on (S.`Store Key`=`Invoice Store Key`) where true %s group by `Invoice Store Key`,`Invoice Category`",$int[0]);
    $result=mysql_query($sql);
    $yfields=array();
    while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $yfields[]=$row['tag'];
    }
    mysql_free_result($result);
   while($row=mysql_fetch_array($res)) {
     $index[$row['yearmonth']]=$i;
     $total[$row['yearmonth']]=0;

     $tmp_data=array(
		       'month'=>$row['month'],
		       '_date'=>$row['date'],
		       'date'=>strftime("%b %y",strtotime($row['date'])),
		       );

     foreach($yfields as $field){
       $tmp_data[$field]=0;
       $tmp_data['tip_'.$field]=$field.': '._('no sales this month').".";

     }

     

     $data[]=$tmp_data;

     $i++;
    }
    mysql_free_result($res);

   $sql=sprintf("select date_format(`Invoice Date`,'%%Y%%m')  as yearmonth,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`)as asales from `Invoice Dimension` left join `Store Dimension` S on (S.`Store Key`=`Invoice Store Key`) where true %s   group by  yearmonth ",$int[0]);
   //print $sql;
   $res=mysql_query($sql);
   while($row=mysql_fetch_array($res)){
     $total[$row['yearmonth']]=$row['asales'];
   }
mysql_free_result($res);

   // print_r($total);

   //   print_r($data);
   $sql=sprintf("select  CONCAT(`Store Code`,'-',`Invoice Category`) as tag2,CONCAT(`Store Code`,'',`Invoice Category`) as tag,date_format(`Invoice Date`,'%%Y%%m')  as yearmonth,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`)as asales from `Invoice Dimension` left join `Store Dimension` S on (S.`Store Key`=`Invoice Store Key`) where true %s   group by  yearmonth,CONCAT(`Store Code`,'-',`Invoice Category`) ",$int[0]);
   //print $sql;
   $res=mysql_query($sql);
   while($row=mysql_fetch_array($res)){
     //   print $row['yearmonth']."\n";
      if(isset($index[$row['yearmonth']])){
	$_index=$index[$row['yearmonth']];
	//print $_index."\n";
	$data[$_index][$row['tag']]=(float)$row['asales'];
	$data[$_index]['tip_'.$row['tag']]=$row['tag2'].': '._('Net Sales')."\n".strftime("%b %y",strtotime($data[$_index]['_date']))."\n".money($row['asales'])."\n".percentage($row['asales'],$total[$row['yearmonth']]).' '._('of the total sales').".";
	$data[$_index]['tip_'.$row['tag']]=_('Net Sales')." ".strftime("%b %y",strtotime($data[$_index]['_date']))."\n".money($total[$row['yearmonth']])."\n".$row['tag2'].":\n".money($row['asales'])."\n".percentage($row['asales'],$total[$row['yearmonth']]).' '._('of the total sales').".";
	if($total[$row['yearmonth']]>0){
	  $data[$_index]['share_'.$row['tag']]=(float)100*$row['asales']/$total[$row['yearmonth']];
	  $data[$_index]['share_bug_'.$row['tag']]=(float)50*$row['asales']/$total[$row['yearmonth']];
	  
	  $data[$_index]['tip_share_bug_'.$row['tag']]=$row['tag2'].': '._('Net Sales')."\n".strftime("%b %y",strtotime($data[$_index]['_date']))."\n".money($row['asales'])."\n".percentage($row['asales'],$total[$row['yearmonth']]).' '._('of the total sales').".";
	  }
	

      }
   }
   mysql_free_result($res);
   //  print_r($data);
   
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 )
		   );

      echo json_encode($response);
}
function list_invoices_per_store_per_day($int){
 $_int=preg_replace('/Invoice Date/i','Date',$int[0]);;
    $sql=sprintf("select  `Date` as date  from `Date Dimension` where true  %s ; ",$_int);
    // print $sql;
    
    $data=array();
    $res = mysql_query($sql);
    $i=0;
    $last_month='';


    $sql=sprintf("select CONCAT(`Store Code`,'',`Invoice Category`) as tag from `Invoice Dimension`  left join `Store Dimension` S on (S.`Store Key`=`Invoice Store Key`) where true %s group by `Invoice Store Key`,`Invoice Category`",$int[0]);
    $result=mysql_query($sql);
    $yfields=array();
    while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $yfields[]=$row['tag'];
    }
   while($row=mysql_fetch_array($res)) {
     $index[$row['date']]=$i;
     $total[$row['date']]=0;
     
     if($_REQUEST['dtipo']=='m'){
     $tmp_data=array(
		       '_date'=>$row['date'],
		       'date'=>strftime("%a %d",strtotime($row['date'])),
		       );
     }else{
       $tmp_data=array(
		       '_date'=>$row['date'],
		       'date'=>strftime("%a %d %b %y",strtotime($row['date'])),
		       );

     }
     


     foreach($yfields as $field){
       $tmp_data[$field]=0;
       $tmp_data['tip_'.$field]=$field.': '._('no sales this day').".";

     }

     

     $data[]=$tmp_data;

     $i++;
    }
    mysql_free_result($res);

   $sql=sprintf("select Date(`Invoice Date`)  as date,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`)as asales from `Invoice Dimension` left join `Store Dimension` S on (S.`Store Key`=`Invoice Store Key`) where true %s   group by  date ",$int[0]);
   //print $sql;
   $res=mysql_query($sql);
   while($row=mysql_fetch_array($res)){
     $total[$row['date']]=$row['asales'];
   }
mysql_free_result($res);

   // print_r($total);

   //   print_r($data);
   $sql=sprintf("select  CONCAT(`Store Code`,'-',`Invoice Category`) as tag2,CONCAT(`Store Code`,'',`Invoice Category`) as tag,Date(`Invoice Date`) as date,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`)as asales from `Invoice Dimension` left join `Store Dimension` S on (S.`Store Key`=`Invoice Store Key`) where true %s   group by  date,CONCAT(`Store Code`,'-',`Invoice Category`) ",$int[0]);
   //print $sql;
   $res=mysql_query($sql);
   while($row=mysql_fetch_array($res)){
     //   print $row['date']."\n";
      if(isset($index[$row['date']])){
	$_index=$index[$row['date']];
	//print $_index."\n";
	$data[$_index][$row['tag']]=(float)$row['asales'];

	$data[$_index]['tip_'.$row['tag']]=$row['tag2'].': '._('Net Sales')."\n".strftime("%b %y",strtotime($data[$_index]['_date']))."\n".money($row['asales'])."\n".percentage($row['asales'],$total[$row['date']]).' '._('of the total sales').".";
	if($total[$row['date']]>0){
	  $data[$_index]['share_'.$row['tag']]=(float)100*$row['asales']/$total[$row['date']];
	  $data[$_index]['tip_share_'.$row['tag']]=$row['tag2'].': '._('Net Sales')."\n".strftime("%b %y",strtotime($data[$_index]['_date']))."\n".money($row['asales'])."\n".percentage($row['asales'],$total[$row['date']]).' '._('of the total sales').".";
	  }
	

      }
   }
   mysql_free_result($res);
   // print_r($index);
   
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 )
		   );

      echo json_encode($response);
}


function list_part_stock_history(){
  
  $sku=$_REQUEST['keys'];
  $from=$_REQUEST['from'];
  $to=$_REQUEST['to'];
  
  $period=$_REQUEST['period'];
  $period_group='';$period_group_args='';


  $part=new Part($sku);
  $part_units=$part->data['Part Unit'];
  if($part_units=='ea'){
    $part_units='psc';
  }

//  $from= date('Y-m-d',strtotime($part->data['Part Valid From'].' -1 month'));
 // $to=date('Y-m-d');
  
  
  
// $res=mysql_query($sql);

 
   if($period=='w'){
    $period_group='YearWeek';
   $sql=sprintf("select  (`Date`) as `Date`,`Quantity On Hand` ,(select sum(`Quantity In`) from `Inventory Spanshot Fact`  where `Part SKU`=%s and YearWeek(`Date`)=YearWEEK(ISF.`Date`)  ) as `Quantity In` ,(select sum(`Quantity Sold`) from `Inventory Spanshot Fact`  where `Part SKU`=%s and YEARWEEK(`Date`)=YEARWEEK(ISF.`Date`)  ) as `Quantity Sold` from  kbase.`Week Dimension` left join  `Inventory Spanshot Fact` ISF on (`Last Day`=`Date`)  where `Part SKU`=%d  and `First Day`>Date(%s) and  `Last Day`<%s order by `Date` ",$sku,$sku,$sku,prepare_mysql($from),prepare_mysql($to));

 
 }elseif($period=='m'){
    $period_group='Date_Format';
    $period_group_args="'%Y %m'";
      $sql=sprintf("select  (`Date`) as `Date`,`Quantity On Hand` ,(select sum(`Quantity In`) from `Inventory Spanshot Fact`  where `Part SKU`=%s and Date_Format(`Date`,'%%Y %%m')=Date_Format(ISF.`Date`,'%%Y %%m')  ) as `Quantity In` ,(select sum(`Quantity Sold`) from `Inventory Spanshot Fact`  where `Part SKU`=%s and Date_Format(`Date`,'%%Y %%m')=Date_Format(ISF.`Date`,'%%Y %%m')  ) as `Quantity Sold` from  kbase.`Month Dimension` left join  `Inventory Spanshot Fact` ISF on (`Last Day`=`Date`)  where `Part SKU`=%d  and `First Day`>Date(%s) and  `Last Day`<%s order by `Date` ",$sku,$sku,$sku,prepare_mysql($from),prepare_mysql($to));

  }elseif($period=='d'){
 
      $sql=sprintf("select  (ISF.`Date`) as `Date`,`Quantity On Hand` ,(select sum(`Quantity In`) from `Inventory Spanshot Fact`  where `Part SKU`=%s and `Date`=ISF.`Date`  ) as `Quantity In` ,(select sum(`Quantity Sold`) from `Inventory Spanshot Fact`  where `Part SKU`=%s and `Date`=ISF.`Date`  ) as `Quantity Sold` from  kbase.`Date Dimension`  DD left join  `Inventory Spanshot Fact` ISF on (ISF.`Date`=DD.`Date`)  where `Part SKU`=%d  and DD.`Date`>Date(%s) and  DD.`Date`<%s order by DD.`Date` ",
      $sku,
      $sku,
      $sku,
      prepare_mysql($from),
      prepare_mysql($to)
      );

  }
 
//print "$period_group $period_group_args x $period  $sql\n";

    // $sql=sprintf("select  (`Date`) as `Date`,`Quantity On Hand`,sum(`Value At Cost`) as `Value At Cost`,sum(`Sold Amount`) as `Sold Amount`,sum(`Value Commercial`) as `Value Commercial`,sum(`Storing Cost`) as `Storing Cost`,sum(`Quantity Sold`) as `Quantity Sold`,sum(`Quantity In`) as `Quantity In`,sum(`Quantity Lost`) as `Quantity Lost`  from `Inventory Spanshot Fact` ISF  where `Part SKU`=%d   group by %s(`Date`,%s) order by `Date` ",$sku,$period_group,$period_group_args);
    $res=mysql_query($sql);
    $data=array();
     //print $sql;
    while($row=mysql_fetch_array($res)){
      $data[]=array(
		    'Date'=>$row['Date']
		    ,'Stock'=>$row['Quantity On Hand']
		    ,'tip_Stock'=>strftime("%B %Y",strtotime($row['Date']))."\n"._('Stock end of month').":\n".number($row['Quantity On Hand']).$part_units
		    ,'In'=>$row['Quantity In']
		    ,'tip_In'=>strftime("%B %Y",strtotime($row['Date']))."\n"._('Stock in').":\n".number($row['Quantity In']).$part_units
		    ,'Sales'=>-$row['Quantity Sold']
		    ,'tip_Sales'=>strftime("%B %Y",strtotime($row['Date']))."\n"._('Stock Out (Sales)').":\n".number($row['Quantity Sold']).$part_units
		    );
    }
     $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 )
		   );

      echo json_encode($response);
  

}



?>
