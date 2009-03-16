<?
require_once 'common.php';
require_once 'classes/Order.php';

//require_once 'ar_common.php';


if (!$LU or !$LU->isLoggedIn()) {
  $response=array('state'=>402,'resp'=>_('Forbidden'));
  echo json_encode($response);
  exit;
 }


if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }

$tipo=$_REQUEST['tipo'];
switch($tipo){

 case('create_po'):
   $po=new Order('po',array('supplier_id'=>$_SESSION['state']['supplier']['id']));
   if(is_numeric($po->id)){
     $response= array('state'=>200,'id'=>$po->id);

   }else
     $response= array('state'=>400,'id'=>_("Error: Purchase order could 't be created"));
     echo json_encode($response);  
   break;
 case('plot_month_outofstock_money'):
 case('plot_month_outofstock'):

   if(isset($_REQUEST['from']))
     $from=$_REQUEST['from'];
   else
     $from=date("d-m-Y",strtotime('-1 year') );
   if(isset($_REQUEST['to']))
     $to=$_REQUEST['to'];
   else
     $to=date("d-m-Y",strtotime('now') );
   $_from=$from;
   $_to=$to;

   $int=prepare_mysql_dates($_from,$_to,'date_index','date only,complete months');
   // make the structure of the months
   $data=date_base($_from,$_to,'m','complete months');
   if($tipo=='plot_month_outofstock'){

    $sql=sprintf("select count(DISTINCT  product_id) as products_total ,sum(dispached) as dispached, substring(date_index, 1,7) AS dd from transaction left join orden on (order_id=orden.id) where partner=0  %s group by dd;",$int[0]);

    $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
    while($row=$res->fetchRow()) {
      $data_all[$row['dd']]=array('d_products'=>$row['products_total'],'picks'=>$row['dispached']);
      
    }
   }
    $sql=sprintf("select count(DISTINCT  product_id) as products,sum(qty) as qty, substring(date_index, 1,7) AS dd,sum(qty*price) as e_cost from outofstock left join orden on (order_id=orden.id) left join product on (product_id=product.id) where  partner=0  %s  group by dd   ",$int[0]);
    $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
    while($row=$res->fetchRow()) {
      $data_outstock[$row['dd']]=array('d_products'=>$row['products'],'picks'=>$row['qty'],'e_cost'=>$row['e_cost']);
    }
    
    foreach($data as $key=>$value){
      $total_products=0;
      $outstock_products=0;
      $total_picks=0;
      $outstock_picks=0;
      $e_cost=0;
      if(isset($data_all[$key])){
	 $total_products=$data_all[$key]['d_products'];
	 $total_picks=$data_all[$key]['picks'];
      }
      if(isset($data_outstock[$key])){
	$outstock_products=$data_outstock[$key]['d_products'];
	$outstock_picks=$data_outstock[$key]['picks'];
	$e_cost=$data_outstock[$key]['e_cost'];
      }

      $per_prods=percentage($outstock_products,$total_products,2,'0','' );
      $per_picks=percentage($outstock_picks,$total_picks,2,'0','' );

      $_data[]=array(
		     'per_product_outstock'=>(float) $per_prods,
			'per_picks_outstock'=>(float) $per_picks,
			'e_cost'=>money($e_cost),
			'date'=>$key,
			'tip_per_product_outstock'=>_('Out of Stock Products')."\n".$per_prods.'% ('.number($outstock_products).' '._('of').' '.number($total_products).')',
			'tip_per_picks_outstock'=>_('Out of Stock Picks')."\n".$per_picks."%\n(".number($outstock_picks).' '._('of').' '.number($total_picks).")\n"._('Estimated Value')."\n@"._('Current Sale Price')."\n".money($e_cost)

		     );
    }
$response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$_data,
			 )
		   );

   echo json_encode($response);
   
   
   break;

case('plot_net_diff1y_sales_month'):

  $time=strtotime($myconf['data_since']);
  if(date("d",$time)==1)
    $from=date("Y-m-d",$time);
  else{
    $from=date("Y-",$time).(date("m",$time)+1).'-01';
  }
  $sql="SELECT count(*) as invoices,month(date_index) as month, UNIX_TIMESTAMP(date_index) as date ,substring(date_index, 1,7) AS dd, COUNT(id)as orders ,sum(net) as sales FROM orden where tipo=2 and date_index>'$from'  GROUP BY dd";
  //    print $sql;  
 $data=array();
  $prev_month='';
 $prev_year=array();
  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}

   while($row=$res->fetchRow()) {
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
  

 $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 )
		   );



    echo json_encode($response);
// echo '{"resultset":{"state":200,"data":{"tip":"Sales October 2008\n\u00a329,085.85\n-87.4% change (last month)\n-89.5% change (last year)\n(240 Orders)","tip_losses":"Lost Sales October 2008\n\u00a30.00 (0.0%)","sales":"34429","losses":0,"date":"10-2008"}}}';

 break;
case('plot_monthsales'):
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

  $sql="SELECT count(*) as invoices,month(`Invoice Date`) as month, UNIX_TIMESTAMP(`Invoice Date`) as date ,substring(`Invoice Date`, 1,7) AS dd ,sum(`Invoice Gross Amount`-`Invoice Discount Amount`) as sales FROM `Invoice Dimension` where `Invoice Date`>'$from'  GROUP BY dd";
  

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
// echo '{"resultset":{"state":200,"data":{"tip":"Sales October 2008\n\u00a329,085.85\n-87.4% change (last month)\n-89.5% change (last year)\n(240 Orders)","tip_losses":"Lost Sales October 2008\n\u00a30.00 (0.0%)","sales":"34429","losses":0,"date":"10-2008"}}}';

 break;
case('plot_weeksales'):

  $from='2004-07-01';
  $sql="select  yearweek,first_day from list_week where  first_day>'$from' and first_day<NOW()";
  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   while($row=$res->fetchRow()) {
     $_data[$row['yearweek']]=array('sales'=>0,'tip_sales'=>'','date'=>$row['yearweek']);
   }

  $sql="SELECT yearweek(date_index) AS dd, COUNT(id) as orders ,sum(net) as sales FROM orden where tipo=2 and date_index>'$from'  GROUP BY dd";
  //  print $sql;  
 $data=array();
 $prev_week='';
 $prev_year=array();
  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   while($row=$res->fetchRow()) {
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
   $data=array();
   $i=0;
   foreach($_data as $__data){
     $data[]=$__data;
     print $i++." ".$__data['sales']."\n";
   }



 $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 )
		   );



 // echo json_encode($response);
// echo '{"resultset":{"state":200,"data":{"tip":"Sales October 2008\n\u00a329,085.85\n-87.4% change (last month)\n-89.5% change (last year)\n(240 Orders)","tip_losses":"Lost Sales October 2008\n\u00a30.00 (0.0%)","sales":"34429","losses":0,"date":"10-2008"}}}';

 break;



case('plot_gmonthsales'):
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
$sql="SELECT year(`Invoice Date`)as year, count(*) as invoices,month(`Invoice Date`) as month, UNIX_TIMESTAMP(`Invoice Date`) as date ,sum(`Invoice Gross Amount`-`Invoice Discount Amount`) as sales FROM `Invoice Dimension` where year(`Invoice Date`)=$year  GROUP BY month  order by month(`Invoice Date`)";
//print "$sql<br>";

 $prev_month='';
 $prev_year=array();
  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   while($row=$res->fetchRow()) {
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
   break;
case('changesalesplot'):
   if(isset($_REQUEST['value'])){
     $value=$_REQUEST['value'];
     $_SESSION['views']['sales_plot']="$value";

   }
 case('proinvoice'):
   if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$_SESSION['tables']['proinvoice_list'][3];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$_SESSION['tables']['proinvoice_list'][2];
   if(isset( $_REQUEST['o']))
     $order=$_REQUEST['o'];
   else
     $order=$_SESSION['tables']['proinvoice_list'][0];
   if(isset( $_REQUEST['od']))
     $order_dir=$_REQUEST['od'];
   else
     $order_dir=$_SESSION['tables']['proinvoice_list'][1];
   
   
   


   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   


   

    if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$_SESSION['tables']['proinvoice_list'][4];

    
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$_SESSION['tables']['proinvoice_list'][5];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$_SESSION['tables']['proinvoice_list'][6];



  $_SESSION['tables']['proinvoice_list']=array($order,$order_direction,$number_results,$start_from,$where,$f_field,$f_value);

  $wheref='';

  if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))>=".$f_value."    ";
  elseif(($f_field=='customer_name' or $f_field=='public_id') and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";


   

   
   $sql="select count(*) as total from orden   $where $wheref ";
   // print "$sql";
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  if($row=$res->fetchRow()) {
    $total=$row['total'];
  }
  if($where==''){
    $filtered=0;
  }else{
    
      $sql="select count(*) as total from orden  $where";
      $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
      if($row=$res->fetchRow()) {
	$filtered=$row['total']-$total;
      }
      
  }
  
  

  $sql="select UNIX_TIMESTAMP(date_index) as date_index ,public_id,customer_name,id,customer_id,total,titulo,tipo,TO_DAYS(NOW())-TO_DAYS(date_index) as desde from orden  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
  //  print $sql;
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   $data=array();
   while($row=$res->fetchRow()) {
     $data[]=array(
		   'id'=>$row['id'],
		   'public_id'=>$row['public_id'],
		   'customer_name'=>$row['customer_name'],
		   'customer_id'=>$row['customer_id'],
		   //		   'date_index'=>$row['date_index'],
		   'date_index'=> strftime("%A %e %B %Y", strtotime('@'.$row['date_index'])),
		   'total'=>money($row['total']),
		   'titulo'=>$_order_tipo[$row['tipo']],
		   'tipo'=>$row['tipo'],
		   'desde'=>$row['desde']
		   //		   'file'=>$row['original_file']
		   );
   }

   
   if($total==0){
     $rtext=_('No orders are outstanding').'.';
   }else if($total<$number_results){
     $rtext=$total.' '.ngettext('record returned','records returned',$total);
   }else
     $rtext='';
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
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
   break;
 case('orders_report'):
   $_REQUEST['saveto']='report_sales';
 case('orders'):
    if(!$LU->checkRight(ORDER_VIEW))
    exit;
    if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
      $conf=$_SESSION['state']['report']['sales'];
    else
      $conf=$_SESSION['state']['orders']['table'];
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
  
 if(isset( $_REQUEST['from']))
    $from=$_REQUEST['from'];
 else{
   if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
     $from=$conf['from'];
   else
     $from=$_SESSION['state']['orders']['from'];
 }

  if(isset( $_REQUEST['to']))
    $to=$_REQUEST['to'];
  else{
    if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
      $to=$conf['to'];
    else
      $to=$_SESSION['state']['orders']['to'];
  }

   if(isset( $_REQUEST['view']))
    $view=$_REQUEST['view'];
   else{
     if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
       $view=$conf['view'];
     else
       $view=$_SESSION['state']['orders']['view'];

   }
   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;


   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

        
   if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales'){
     
     $_SESSION['state']['report']['sales']['order']=$order;
     $_SESSION['state']['report']['sales']['order_dir']=$order_direction;
     $_SESSION['state']['report']['sales']['nr']=$number_results;
     $_SESSION['state']['report']['sales']['sf']=$start_from;
     $_SESSION['state']['report']['sales']['where']=$where;
     $_SESSION['state']['report']['sales']['f_field']=$f_field;
     $_SESSION['state']['report']['sales']['f_value']=$f_value;
     $_SESSION['state']['report']['sales']['to']=$to;
     $_SESSION['state']['report']['sales']['from']=$from;
     $date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
     
   }else{

     $_SESSION['state']['orders']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
     $_SESSION['state']['orders']['view']=$view;
     $date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
     if($date_interval['error']){
       $date_interval=prepare_mysql_dates($_SESSION['state']['orders']['from'],$_SESSION['state']['orders']['to']);
     }else{
       $_SESSION['state']['orders']['from']=$date_interval['from'];
       $_SESSION['state']['orders']['to']=$date_interval['to'];
     }
   }
 //   switch($view){
//    case('all'):
//      break;
//    case('invoices'):
//      $where.=' and orden.tipo=2 ';
//      break;
//    case('in_process'):
//      $where.=' and orden.tipo=1 ';
//      break;
//    case('cancelled'):
//      $where.=' and orden.tipo=3 ';
//      break;
//    default:
     
     
//    }
   $where.=$date_interval['mysql'];
   
   $wheref='';

  if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Order Last Updated Date`))<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Order Last Updated Date`))>=".$f_value."    ";
   elseif($f_field=='customer_name' and $f_value!='')
    $wheref.=" and  `Order Customer Name` like '".addslashes($f_value)."%'";
   elseif($f_field=='public_id' and $f_value!='')
    $wheref.=" and  `Order Public ID` like '".addslashes($f_value)."%'";


  else if($f_field=='maxvalue' and is_numeric($f_value) )
    $wheref.=" and  `Order Total Amount`<=".$f_value."    ";
  else if($f_field=='minvalue' and is_numeric($f_value) )
    $wheref.=" and  `Order Total Amount`>=".$f_value."    ";
   


   

   
  $sql="select count(*) as total from `Order Dimension`   $where $wheref ";
  //     print $sql ;
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  if($row=$res->fetchRow()) {
    $total=$row['total'];
  }
  if($where==''){
    $filtered=0;
     $total_records=$total;
  }else{
    
      $sql="select count(*) as total from `Order Dimension`  $where";
      $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
      if($row=$res->fetchRow()) {
	$total_records=$row['total'];
	$filtered=$total_records-$total;
      }
      
  }
  $rtext=$total_records." ".ngettext('order','orders',$total_records);
  if($total_records>$number_results)
    $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
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
   case('maxvalue'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order maximum value of")." <b>".money($f_value)."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with max value of')." <b>".money($f_value)."*</b>) <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
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

   
   if($order=='date')
     $order='`Order Date`';
   else if($order=='last_date')
     $order='`Order Last Updated Date`';
   else if($order=='id')
     $order='`Order File As`';
   else if($order=='state')
     $order='`Order Current Dispatch State`,`Order Current Payment State`';
   else if($order=='total_amount')
     $order='`Order Total Amount`';
else if($order=='customer')
     $order='`Order Customer Name`';

  $sql="select `Order Key`,`Order Public ID`,`Order Customer Key`,`Order Customer Name`,`Order Last Updated Date`,`Order Date`,`Order Total Amount` ,`Order Current XHTML State` from `Order Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
  //  print $sql;

   $data=array();

   $res = mysql_query($sql);
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
     $order_id=sprintf('<a href="order.php?id=%d">%s</a>',$row['Order Key'],$row['Order Public ID']);
     $customer=sprintf('<a href="order.php?id=%d">%s</a>',$row['Order Customer Key'],$row['Order Customer Name']);
     $data[]=array(
		   'id'=>$order_id,
		   'customer'=>$customer,
		   'date'=>strftime("%e %b %y %H:%M", strtotime($row['Order Date'])),
		   'last_date'=>strftime("%e %b %y %H:%M", strtotime($row['Order Last Updated Date'])),
		   'total_amount'=>money($row['Order Total Amount']),
		   'state'=>$row['Order Current XHTML State']
		   );
   }

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'rtext'=>$rtext,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$total,
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
   break;


 case('invoices'):
    if(!$LU->checkRight(ORDER_VIEW))
    exit;
    
    $conf=$_SESSION['state']['orders']['invoices'];
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
  
 if(isset( $_REQUEST['from']))
    $from=$_REQUEST['from'];
 else{
   if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
     $from=$conf['from'];
   else
     $from=$_SESSION['state']['orders']['from'];
 }

  if(isset( $_REQUEST['to']))
    $to=$_REQUEST['to'];
  else{
    if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
      $to=$conf['to'];
    else
      $to=$_SESSION['state']['orders']['to'];
  }

   if(isset( $_REQUEST['view']))
    $view=$_REQUEST['view'];
   else{
     if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
       $view=$conf['view'];
     else
       $view=$_SESSION['state']['orders']['view'];

   }
   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;


   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

        
   if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales'){
     
     $_SESSION['state']['report']['sales']['order']=$order;
     $_SESSION['state']['report']['sales']['order_dir']=$order_direction;
     $_SESSION['state']['report']['sales']['nr']=$number_results;
     $_SESSION['state']['report']['sales']['sf']=$start_from;
     $_SESSION['state']['report']['sales']['where']=$where;
     $_SESSION['state']['report']['sales']['f_field']=$f_field;
     $_SESSION['state']['report']['sales']['f_value']=$f_value;
     $_SESSION['state']['report']['sales']['to']=$to;
     $_SESSION['state']['report']['sales']['from']=$from;
     $date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
     
   }else{

     $_SESSION['state']['orders']['invoices']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
     $_SESSION['state']['orders']['view']=$view;
     $date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
     if($date_interval['error']){
       $date_interval=prepare_mysql_dates($_SESSION['state']['orders']['from'],$_SESSION['state']['orders']['to']);
     }else{
       $_SESSION['state']['orders']['from']=$date_interval['from'];
       $_SESSION['state']['orders']['to']=$date_interval['to'];
     }
   }

   $where.=$date_interval['mysql'];
   
   $wheref='';

  if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))>=".$f_value."    ";
   elseif(($f_field=='customer_name' or $f_field=='public_id') and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";
  else if($f_field=='maxvalue' and is_numeric($f_value) )
    $wheref.=" and  total<=".$f_value."    ";
  else if($f_field=='minvalue' and is_numeric($f_value) )
    $wheref.=" and  total>=".$f_value."    ";
   


   

   
  $sql="select count(*) as total from `Invoice Dimension`   $where $wheref ";
  // print $sql ;
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  if($row=$res->fetchRow()) {
    $total=$row['total'];
  }
  if($where==''){
    $filtered=0;
     $total_records=$total;
  }else{
    
      $sql="select count(*) as total from `Order Dimension`  $where";
      $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
      if($row=$res->fetchRow()) {
	$total_records=$row['total'];
	$filtered=$total_records-$total;
      }
      
  }
  $rtext=$total_records." ".ngettext('order','orders',$total_records);
  if($total_records>$number_results)
    $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
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
   case('maxvalue'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order maximum value of")." <b>".money($f_value)."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with max value of')." <b>".money($f_value)."*</b>) <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
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

   
   if($order=='date')
     $order='`Invoice Date`';
   else if($order=='last_date')
     $order='`Invoice Last Updated Date`';
   else if($order=='id')
     $order='`Invoice File As`';
   else if($order=='state')
     $order='`Invoice Current Dispatch State`,`Invoice Current Payment State`';
   else if($order=='total_amount')
     $order='`Invoice Total Amount`';
else if($order=='customer')
     $order='`Invoice Customer Name`';

  $sql="select `Invoice Has Been Paid In Full`,`Invoice Key`,`Invoice XHTML Orders`,`Invoice XHTML Delivery Notes`,`Invoice Public ID`,`Invoice Customer Key`,`Invoice Customer Name`,`Invoice Date`,`Invoice Total Amount`  from `Invoice Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
  // print $sql;

   $data=array();

   $res = mysql_query($sql);
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
     $order_id=sprintf('<a href="invoice.php?id=%d">%s</a>',$row['Invoice Key'],$row['Invoice Public ID']);
     $customer=sprintf('<a href="customer.php?id=%d">%s</a>',$row['Invoice Customer Key'],$row['Invoice Customer Name']);
     if($row['Invoice Has Been Paid In Full'])
       $state=_('Paid');
     else
       $state=_('No Paid');

     $data[]=array(
		   'id'=>$order_id
		   ,'customer'=>$customer
		   ,'date'=>strftime("%e %b %y", strtotime($row['Invoice Date']))
		   ,'total_amount'=>money($row['Invoice Total Amount'])
		   ,'state'=>$state
		   ,'orders'=>$row['Invoice XHTML Orders']
		   ,'dns'=>$row['Invoice XHTML Delivery Notes']
		   );
   }

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'rtext'=>$rtext,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$total,
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
   break;

 case('po_supplier'):
    if(!$LU->checkRight(ORDER_VIEW))
    exit;


    $supplier_id=$_SESSION['state']['supplier']['id'];

    $conf=$_SESSION['state']['supplier']['po'];
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
  
 if(isset( $_REQUEST['from']))
    $from=$_REQUEST['from'];
  else
    $from=$_SESSION['state']['supplier']['po']['from'];
  if(isset( $_REQUEST['to']))
    $to=$_REQUEST['to'];
  else
    $to=$_SESSION['state']['supplier']['po']['to'];


   if(isset( $_REQUEST['view']))
    $view=$_REQUEST['view'];
  else
    $view=$_SESSION['state']['supplier']['po']['view'];


   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;


   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   $_SESSION['state']['supplier']['po']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
   $_SESSION['state']['supplier']['po']['view']=$view;
   $date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
   if($date_interval['error']){
      $date_interval=prepare_mysql_dates($_SESSION['state']['supplier']['po']['from'],$_SESSION['state']['supplier']['po']['to']);
   }else{
     $_SESSION['state']['supplier']['po']['from']=$date_interval['from'];
     $_SESSION['state']['supplier']['po']['to']=$date_interval['to'];
   }


    $where.=sprintf(' and `Purchase Order Supplier Key`=%d',$supplier_id);

//    switch($view){
//    case('all'):
//      break;
//    case('submited'):
//      $where.=' and porden.status_id==10 ';
//      break;
//    case('new'):
//      $where.=' and porden.status_id<10 ';
//      break;
//    case('received'):
//      $where.=' and porden.status_id>80 ';
//      break;
//    default:
     
     
//    }
   $where.=$date_interval['mysql'];
   
   $wheref='';

  if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))>=".$f_value."    ";
   elseif(($f_field=='customer_name' or $f_field=='public_id') and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";
  else if($f_field=='maxvalue' and is_numeric($f_value) )
    $wheref.=" and  total<=".$f_value."    ";
  else if($f_field=='minvalue' and is_numeric($f_value) )
    $wheref.=" and  total>=".$f_value."    ";
   




   
   $sql="select count(*) as total from `Purchase Order Dimension`   $where $wheref ";
  
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   if($row=$res->fetchRow()) {
     $total=$row['total'];
  }
  if($where==''){
    $filtered=0;
     $total_records=$total;
  }else{
    
      $sql="select count(*) as total from `Purchase Order Dimension`   $where";
      $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
      if($row=$res->fetchRow()) {
	$total_records=$row['total'];
	$filtered=$row['total']-$total;
      }
      
  }
  $rtext=$total_records." ".ngettext('order','orders',$total_records);
  if($total_records>$number_results)
    $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
  $filter_msg='';

     switch($f_field){
     case('public_id'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with number")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders starting with')." <b>$f_value</b>) <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;
     case('minvalue'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order minimum value of")." <b>".money($f_value)."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with min value of')." <b>".money($f_value)."*</b>) <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;  
   case('maxvalue'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order maximum value of")." <b>".money($f_value)."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with max value of')." <b>".money($f_value)."*</b>) <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
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

   if($order=='date_index')
     $order='`Purchase Order Date`';

  $sql="select  * from  `Purchase Order Dimension`   $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
  //print $sql;
  //  print $sql;
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   $data=array();
   while($row=$res->fetchRow()) {
     $data[]=array(
		   'id'=>'<a href="porder.php?id='.$row['id'].'">'.$row['id']."</a>",
		   'date_index'=>strftime("%e %b %Y %H:%M", strtotime('@'.$row['date_index'])),
		   'total'=>money($row['total']),
		   'items'=>number($row['items']),
		   'tipo'=>$_order_tipo[$row['tipo']]." (".$_order_status[$row['status_id']].")"
		   );
   }

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'rtext'=>$rtext,
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
   break;
 case('pickers_report'):
   
   
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
   $res = $db->query($sql); 
   $data=array();
   $hours=40;
   $uph=$row['units']/$hours;
   $total=0;
   while($row=$res->fetchRow()) {

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
   break;

 case('packers_report'):
   
   
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

   $res = $db->query($sql); 
   $data=array();
   $hours=40;
   $uph=$row['units']/$hours;
   $total=0;
   while($row=$res->fetchRow()) {

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
   break;

 case('outofstock'):
   
   if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $order_id=$_REQUEST['id'];
   else
     $order_id=$_SESSION['order_id'];

   if(isset( $_REQUEST['o']))
     $order=$_REQUEST['o'];
   else
     $order=$_SESSION['tables']['transaction_list'][0];
   if(isset( $_REQUEST['od']))
     $order_dir=$_REQUEST['od'];
   else
     $order_dir=$_SESSION['tables']['transaction_list'][1];
   
   
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   

   $_SESSION['tables']['transaction_list']=array($order,$order_direction);
   
   $where=' where order_id='.$order_id;

   $total_charged=0;
   $total_discounts=0;
   $total_picks=0;

   
   $sql="select * from outofstock left join product on (product.id=product_id)  $where order by code ";
   
   //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispached,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
   //      print $sql;
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   $data=array();
   while($row=$res->fetchRow()) {
     



     $data[]=array(
		   'id'=>$row['id']
		   ,'product_id'=>$row['product_id']
		   ,'code'=>$row['code']
		   ,'description'=>number($row['units']).'x '.$row['description']
		   //'ordered'=>$row['ordered'],
		   ,'qty'=>number($row['qty'],2)


		   );
   }


    

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data

			 )
		   );
   echo json_encode($response);
   break;
 case('transactions_to_process'):
   
   if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $order_id=$_REQUEST['id'];
   else
     $order_id=$_SESSION['state']['order']['id'];
   



   $where=' where `Order Key`='.$order_id;

   $total_charged=0;
   $total_discounts=0;
   $total_picks=0;

   $data=array();
   $sql="select * from `Order Transaction Fact` O left join `Product Dimension` P on (O.`Product key`=P.`Product Key`) $where   ";
   
   //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispached,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
   //   print $sql;
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
   //   $total_charged+=$row['charge'];
//      $total_discounts+=$ndiscount;
//      $total_picks+=$row['dispached'];
     $data[]=array(

		   'code'=>$row['Product Code']
		   ,'description'=>$row['Product XHTML Short Description']
		   ,'tariff_code'=>$row['Product Tariff Code']
		   ,'quantity'=>number($row['Order Quantity'])
		   ,'gross'=>money($row['Order Transaction Gross Amount'])
		   ,'discount'=>money($row['Order Transaction Total Discount Amount'])
		   ,'to_charge'=>money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'])
		   );
   }


 
   

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data
// 			 'total_records'=>$total,
// 			 'records_offset'=>$start_from,
// 			 'records_returned'=>$start_from+$res->numRows(),
// 			 'records_perpage'=>$number_results,
// 			 'records_text'=>$rtext,
// 			 'records_order'=>$order,
// 			 'records_order_dir'=>$order_dir,
// 			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
   break;
 case('transactions'):
   
   if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $order_id=$_REQUEST['id'];
   else
     $order_id=$_SESSION['state']['order']['id'];

 //   if(isset( $_REQUEST['o']))
//      $order=$_REQUEST['o'];
//    else
//      $order=$_SESSION['tables']['transaction_list'][0];
//    if(isset( $_REQUEST['od']))
//      $order_dir=$_REQUEST['od'];
//    else
//      $order_dir=$_SESSION['tables']['transaction_list'][1];
   
   
//    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   

//    $_SESSION['tables']['transaction_list']=array($order,$order_direction);
   
   $where=' where order_id='.$order_id;

   $total_charged=0;
   $total_discounts=0;
   $total_picks=0;

   
   $sql="select weight, p.price as price,concat(100000+p.group_id,p.ncode) as display_order,p.id as id,p.code as code ,product_id,p.description,units,ordered,dispached,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where  and dispached> 0 and (charge!=0 or discount!=1)    ";
   
   //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispached,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
   //     print $sql;
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   $data=array();
   while($row=$res->fetchRow()) {
     $discount='';
     $ndiscount=0;
     $cost='';
     $ncost=0;
     if($row['charge']==0 or $row['discount']==1)
       $outer_price=$row['price'];
     else{
       $outer_price=$row['charge']/((1-$row['discount'])*$row['dispached'] );
       $ncost=$outer_price;///$row['dispached'];
       //       $$row['dispached']/$row['o']

       $cost= money($ncost);
       if($row['discount']>0){
	 $ndiscount=$row['discount']* $row['charge'] ;
	 $discount='('.number(100*$row['discount'],0).'%) '.money(  $ndiscount);
       }
     }


     



     $total_charged+=$row['charge'];
     $total_discounts+=$ndiscount;
     $total_picks+=$row['dispached'];
     $data[]=array(
		   'id'=>$row['id']
		   ,'product_id'=>$row['product_id']
		   ,'code'=>$row['code']
		   ,'description'=>number($row['units']).'x '.$row['description'].' @ '.$cost
		   ,  'cost'=>$cost
		   //'ordered'=>$row['ordered'],
		   ,'dispached'=>number($row['dispached'],2)
		   ,'charge'=>money($row['charge'])
		   ,'discount'=>$discount

		   );
   }




   // todo transactions
 $sql="select * from todo_transaction  $where and (bonus=0 and  discount!=1)";

   //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispached,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
 // print $sql;
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}

   while($row=$res->fetchRow()) {

     $charged=($row['ordered']-$row['reorder']) * $row['price']*(1-$row['discount']);
     $total_charged+=$charged;
     $total_discounts+=$ndiscount;
     $pick=number($row['ordered']-$row['reorder']);
     $total_picks+=$pick;

     $discount='';
     $ndiscount=0;
     $cost='';
     $ncost=0;
     if($charged==0 or $row['discount']==1)
       $outer_price=$row['price'];
     else{
       $outer_price=$charged/((1-$row['discount'])*$pick );
       $ncost=$outer_price*$pick;
       $cost= money($ncost);
       if($row['discount']>0){
	 $ndiscount=$row['discount']* $charged;
	 $discount='('.number(100*$row['discount'],0).'%'._('off').') '.$myconf['currency_symbol'].money($ndiscount);
       }
     }











       $data[]=array(
		   'id'=>$row['id']
		   ,'product_id'=>-1
		   ,'code'=>$row['code']
		   ,'description'=>$row['description'].' @ '.money($row['price'])
		   ,  'cost'=>money($row['price'] )
		   //'ordered'=>$row['ordered'],
		   ,'dispached'=>number($pick)
		   ,'charge'=>money($charged)
		   ,'discount'=>$discount
		   //'promotion_id'=>$row['promotion_id']
		   );

   }


   // todo transactions
 $sql="select * from todo_transaction  $where and (bonus!=0 or  discount=1)";

   //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispached,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
 //  print $sql;
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}

   while($row=$res->fetchRow()) {
     
     $pick=$row['bonus']+$row['ordered']-$row['reorder'];

     $data[]=array(
		   'id'=>$row['id']
		   ,'product_id'=>-1
		   ,'code'=>$row['code']
		   ,'description'=>$row['description'].' ('._('Free Bonus').')'
		   ,  'cost'=>''
		   //'ordered'=>$row['ordered'],
		   ,'dispached'=>number($pick)
		   ,'charge'=>''
		   ,'discount'=>''
		   //'promotion_id'=>$row['promotion_id']
		   );

   }










  $sql="select promotion,bonus.id as id,product_id,p.units as units,concat(100000+p.group_id,p.ncode) as display_order,p.code as code,p.description as description, qty from bonus   left join product as p on (product_id=p.id) $where";

   //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispached,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
  //  print $sql;
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}

   while($row=$res->fetchRow()) {

     $tipo_discount=_('Free Bonus');
     if($row['promotion'])
       $tipo_discount=_('First Order Bonus');
     $data[]=array(
		   'id'=>$row['id']
		   ,'product_id'=>$row['product_id']
		   ,'code'=>$row['code']
		   ,'description'=>number($row['units']).'x '.$row['description'].' ('.$tipo_discount.')'
		   ,  'cost'=>''
		   //'ordered'=>$row['ordered'],
		   ,'dispached'=>number($row['qty'])
		   ,'charge'=>''
		   ,'discount'=>''
		   //'promotion_id'=>$row['promotion_id']
		   );

   }
   $data[]=array(
		 'id'=>0
		 ,'product_id'=>''
		 ,'code'=>_('Subtotal')
		 ,'description'=>''
		 ,  'cost'=>''
		 //'ordered'=>$row['ordered'],
		 ,'dispached'=>number($total_picks)
		 ,'charge'=>money($total_charged)
		 ,'discount'=>money($total_discounts)
		 //'promotion_id'=>$row['promotion_id']
		 );
   

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data
// 			 'total_records'=>$total,
// 			 'records_offset'=>$start_from,
// 			 'records_returned'=>$start_from+$res->numRows(),
// 			 'records_perpage'=>$number_results,
// 			 'records_text'=>$rtext,
// 			 'records_order'=>$order,
// 			 'records_order_dir'=>$order_dir,
// 			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
   break;
 case('withproduct'):

   $conf=$_SESSION['state']['product']['orders'];


 if(isset( $_REQUEST['code'])){
     $tag=$_REQUEST['code'];
     $mode='code';
   }else if(isset( $_REQUEST['id'])){
     $tag=$_REQUEST['id'];
     $mode='id';
   }else if(isset( $_REQUEST['key'])){
     $tag=$_REQUEST['key'];
     $mode='key';
   }else{
     $tag=$_SESSION['state']['product']['tag'];
     $mode=$_SESSION['state']['product']['mode'];
   }



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
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$conf['where'];
   
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;



   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   

   $_SESSION['state']['product']['orders']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value,'tag'=>$tag,'mode'=>$mode);
   $_order=$order;
   $_dir=$order_direction;
   $filter_msg='';


  if($mode=='code')
     $where=$where.sprintf(" and PD.`Product Code`=%s ",prepare_mysql($tag));
   elseif($mode=='id')
     $where=$where.sprintf(" and PD.`Product ID`=%d ",$tag);
   elseif($mode=='key')
     $where=$where.sprintf(" and PD.`Product Key`=%d ",$tag);



   $wheref="";
   if(isset($_REQUEST['f_field']) and isset($_REQUEST['f_value'])){
     if($_REQUEST['f_field']=='public_id' or $_REQUEST['f_field']=='customer'){
       if($_REQUEST['f_value']!='')
	 $wheref=" and  ".$_REQUEST['f_field']." like '".addslashes($_REQUEST['f_value'])."%'";
     }
   }
   
  


   $sql="select count(*) as total from `Order Transaction Fact` OTF  left join `Product Dimension` PD on (PD.`Product Key`=OTF.`Product Key`) $where $wheref";
   //print $sql;   
$res = mysql_query($sql);
   if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
  $total=$row['total'];
   }
   if($wheref==''){
     $filtered=0;  $total_records=$total;
   }else{
     $sql="select count(*) as total from `Order Transaction Fact` OTF left join `Product Dimension` PD on (PD.`Product Key`=OTF.`Product Key`) $where      ";
       $res = mysql_query($sql);
       if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	$total_records=$row['total'];
	$filtered=$total_records-$total;
       }
       
   }

 $rtext=$total_records." ".ngettext('order','orders',$total_records);
  if($total_records>$number_results)
    $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
  $filter_msg='';


   if($order=='date'){
      $order='`Actual Shipping Date`';

   }elseif($order=='dispached')
      $order='`Shipped Quantity`';
   elseif($order=='order'){
     $order='';
     $order_direction ='';

  }
   $sql=sprintf("select `Delivery Note XHTML Orders`,`Customer Name`,CD.`Customer Key`,`Delivery Note Date`,sum(`Shipped Quantity`) as dispached,sum(`No Shipped Due Out of Stock`+`No Shipped Due No Authorized`+`No Shipped Due Not Found`+`No Shipped Due Other`) as undispached  from     `Order Transaction Fact` OTF  left join   `Delivery Note Dimension` DND on (OTF.`Delivery Note Key`=DND.`Delivery Note Key`) left join `Customer Dimension` CD on (OTF.`Customer Key`=CD.`Customer Key`)   left join `Product Dimension` PD on (PD.`Product Key`=OTF.`Product Key`)     %s %s  and OTF.`Delivery Note Key`>0  group by OTF.`Delivery Note Key`  order by  $order $order_direction  limit $start_from,$number_results"
		,$where
		,$wheref
		);

   //    print "$sql";
   $res=mysql_query($sql);
   $data=array();
   while($row= mysql_fetch_array($res, MYSQL_ASSOC) ) {
     if($LU->checkRight(CUST_VIEW))
       $customer='<a href="customer.php?id='.$row['Customer Key'].'">'.$row['Customer Name'].'</a>';
     else
       $customer=$myconf['customer_id_prefix'].sprintf("%05d",$row['Customer Key']);
     


     $data[]=array(
		   'order'=>$row['Delivery Note XHTML Orders'],
		   'customer_name'=>$customer,
		   'date'=> strftime("%e %b %y", strtotime($row['Delivery Note Date'])),
		   'dispached'=>number($row['dispached']),
		   'undispached'=>number($row['undispached'])

		   );
   }

   $response=array('resultset'=>
		   array('state'=>200,
			 
			 'data'=>$data,
			 'rtext'=>$rtext,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$total,
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
   break;

 case('withcustomerproduct'):
   if(!$LU->checkRight(CUST_VIEW))
     exit;
   $conf=$_SESSION['state']['product']['customers'];

   if(isset( $_REQUEST['code'])){
     $tag=$_REQUEST['code'];
     $mode='code';
   }else if(isset( $_REQUEST['id'])){
     $tag=$_REQUEST['id'];
     $mode='id';
   }else if(isset( $_REQUEST['key'])){
     $tag=$_REQUEST['key'];
     $mode='key';
   }else{
     $tag=$_SESSION['state']['product']['tag'];
     $mode=$_SESSION['state']['product']['mode'];
   }

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
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$conf['where'];
   
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   $_SESSION['state']['product']['custumers']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value,'tag'=>$tag,'mode'=>$mode);
   $_order=$order;
   $_dir=$order_direction;
   $filter_msg='';

   if($mode=='code')
     $where=$where.sprintf(" and PD.`Product Code`=%s ",prepare_mysql($tag));
   elseif($mode=='id')
     $where=$where.sprintf(" and PD.`Product ID`=%d ",$tag);
   elseif($mode=='key')
     $where=$where.sprintf(" and PD.`Product Key`=%d ",$tag);


   $wheref="";
   
  if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))>=".$f_value."    ";
  elseif($f_field=='customer_name'  and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";


   $sql="select count(distinct `Customer Key`) as total from  `Order Transaction Fact` OTF left join `Product Dimension` PD on (PD.`Product Key`=OTF.`Product Key`)  $where $wheref";
   //print $sql;
   $res = mysql_query($sql);
   if($row=mysql_fetch_array($res)) {
       $total=$row['total'];
   }
   if($wheref==''){
     $filtered=0;
      $total_records=$total;
   }else{
     $sql="select count(distinct `Customer Key`) as total from  `Order Transaction Fact` OTF left join `Product Dimension` PD on (PD.`Product Key`=OTF.`Product Key`)  $where      ";
     $res = mysql_query($sql);
     if($row=mysql_fetch_array($res)) {
	$total_records=$row['total'];
	$filtered=$total_records-$total;
     }
     
   }
   $rtext=$total_records." ".ngettext('customer','customers',$total_records);
   if($total_records>$number_results)
     $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
   

   $filter_msg='';
   if($total==0 and $filtered>0){
     switch($f_field){
     case('public_id'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order starting with")." <b>$f_value</b> ";
       break;
     }
   }
   elseif($filtered>0){
     switch($f_field){
     case('publuc_id'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('only orders starting with')." <b>$f_value</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;
     }
   }

   
   $_order=$order;
   $_dir=$order_direction;

   if($order=='customer')
     $order='`Customer Name`';


   $sql=sprintf("select   CD.`Customer Key` as customer_id,`Customer Name`,`Customer Main Location`,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`-`Invoice Transaction Net Refund Amount`) as charged ,count(distinct `Order Key`) as orders ,sum(`Shipped Quantity`) as dispached,sum(`Current Manufacturing Quantity`+`Current On Shelf Quantity`+`Current On Box Quantity`) as todispach,sum(`No Shipped Due Out of Stock`+`No Shipped Due No Authorized`+`No Shipped Due Not Found`) as nodispached from     `Order Transaction Fact` OTF left join `Customer Dimension` CD on (OTF.`Customer Key`=CD.`Customer Key`)  left join `Product Dimension` PD on (PD.`Product Key`=OTF.`Product Key`)      $where $wheref  group by CD.`Customer Key`    order by $order $order_direction  limit $start_from,$number_results "
		);

   //      print "$sql\n";
   $data=array();
   
  $res = mysql_query($sql);
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


     $data[]=array(
		   'customer'=>sprintf('<a href="customer.php?id=%d"><b>%s</b></a>, %s',$row['customer_id'],$row['Customer Name'],$row['Customer Main Location']),
		   'charged'=>money($row['charged']),
		   'orders'=>number($row['orders']),
		   'todispach'=>number($row['todispach']),
		   'dispached'=>number($row['dispached']),
		   'nodispached'=>number($row['nodispached'])

		   );
   }
   

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'sort_key'=>$_order,
			 'rtext'=>$rtext,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$total,
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
   break;

 case('withcustomer'):
 if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$_SESSION['tables']['order_withcust'][3];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$_SESSION['tables']['order_withcust'][2];
   if(isset( $_REQUEST['o']))
     $order=$_REQUEST['o'];
   else
     $order=$_SESSION['tables']['order_withcust'][0];
   if(isset( $_REQUEST['od']))
     $order_dir=$_REQUEST['od'];
   else
     $order_dir=$_SESSION['tables']['order_withcust'][1];
   

   if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $customer_id=$_REQUEST['id'];
   else
     $customer_id=$_SESSION['tables']['order_withcust'][4];


   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   

   $_SESSION['tables']['order_withcust']=array($order,$order_direction,$number_results,$start_from,$customer_id);

   $where=sprintf(" where customer_id=%d ",$customer_id);
   $wheref="";
   if(isset($_REQUEST['f_field']) and isset($_REQUEST['f_value'])){
     if($_REQUEST['f_field']=='public_id' or $_REQUEST['f_field']=='customer'){
       if($_REQUEST['f_value']!='')
	 $wheref=" and  ".$_REQUEST['f_field']." like '".addslashes($_REQUEST['f_value'])."%'";
     }
   }
   
  


   $sql="select count(*) as total from orden    $where $wheref";

   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   if($row=$res->fetchRow()) {
     $total=$row['total'];
   }
   if($wheref==''){
     $filtered=0;
   }else{
     $sql="select count(*) as total from orden $where      ";
     $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
     if($row=$res->fetchRow()) {
       $filtered=$row['total']-$total;
     }
     
   }
   

   $sql=sprintf("select tipo,id,public_id,total ,UNIX_TIMESTAMP(date_index) as date_index from orden  $where $wheref     order by $order $order_direction  limit $start_from,$number_results "
		);

   //print "$sql\n";
      $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   $data=array();
   while($row=$res->fetchRow()) {
     $data[]=array(
		   'id'=>$row['id'],
		   'public_id'=>$row['public_id'],
		   'date_index'=>$row['date_index'],
		   'date'=> strftime("%A %e %B %Y %H:%I", strtotime('@'.$row['date_index'])),
		   'total'=>money($row['total']),
		   // 'undispached'=>number($row['undispached']),
		   'tipo'=>$_order_tipo[$row['tipo']]
		   );
   }
   
   if($total<$number_results)
     $rtext=$total.' '.ngettext('record returned','records returned',$total);
   else
     $rtext='';
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
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
   break;

 default:


   $response=array('state'=>404,'resp'=>_('Operation not found'));
   echo json_encode($response);
   
 }




?>