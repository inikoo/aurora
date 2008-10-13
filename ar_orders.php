<?
require_once 'common.php';
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
case('plot_monthsales'):

  $from='2004-07-00';
  $sql="SELECT count(*) as invoices,month(date_index) as month, UNIX_TIMESTAMP(date_index) as date ,substring(date_index, 1,7) AS dd, COUNT(id)as orders ,sum(net) as sales FROM orden where tipo=2 and date_index>'$from'  GROUP BY dd";
  //  print $sql;  
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

   $data[]=array(
		   'tip_sales'=>$tip,
		   'tip_losses'=>$tip_losses,
		   'sales'=>(float) $row['sales'],
		   'losses'=>$losses,
		   'date'=>strftime("%m/%y", strtotime('@'.$row['date']))
		   );
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

case('plot_gmonthsales'):
  $number_years=4;
  $from='2004-07-00';
  $current_year=date('Y');
  $data=array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'');

  foreach (range( $current_year-$number_years,  $current_year) as $year) {
$sql="SELECT year(date_index)as year, count(*) as invoices,month(date_index) as month, UNIX_TIMESTAMP(date_index) as date , COUNT(id)as orders ,sum(net) as sales FROM orden where tipo=2 and year(date_index)=$year  GROUP BY month  order by month(date_index)";
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
 case('orders'):
   if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$_SESSION['tables']['order_list'][3];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$_SESSION['tables']['order_list'][2];
   if(isset( $_REQUEST['o']))
     $order=$_REQUEST['o'];
   else
     $order=$_SESSION['tables']['order_list'][0];
   if(isset( $_REQUEST['od']))
     $order_dir=$_REQUEST['od'];
   else
     $order_dir=$_SESSION['tables']['order_list'][1];
   
   
    if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;



   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   


   

   if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$_SESSION['tables']['order_list'][4];


  if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$_SESSION['tables']['order_list'][5];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
  else
    $f_value=$_SESSION['tables']['order_list'][6];
  



   $_SESSION['tables']['order_list']=array($order,$order_direction,$number_results,$start_from,$where,$f_field,$f_value);
   //print_r($_SESSION['tables']['order_list']);
   

  $wheref='';

    // if( ($f_field=='public_id'   or  $f_field=='customer_name')  and $f_value=!'' )
  //   $wheref.=" and   $f_field like '".addslashes($f_value)."%'   ";
  if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))>=".$f_value."    ";
   elseif(($f_field=='customer_name' or $f_field=='public_id') and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";
  else if($f_field=='maxvalue' and is_numeric($f_value) )
    $wheref.=" and  total<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  total>=".$f_value."    ";
   


   

   
   $sql="select count(*) as total from orden   $where $wheref ";
   //print "$sql";
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
  
  
 $filter_msg='';

     switch($f_field){
     case('public_id'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order starting with")." <b>$f_value</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('only orders starting with')." <b>$f_value</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Remove Filter')."</span>";
       break;
     }



   
   $_order=$order;
   $_dir=$order_direction;



  $sql="select date_index,public_id,customer_name,id,customer_id,total,titulo,tipo from orden  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
  //print $sql;
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   $data=array();
   while($row=$res->fetchRow()) {
     $data[]=array(
		   'id'=>$row['id'],
		   'public_id'=>$row['public_id'],
		   'customer_name'=>$row['customer_name'],
		   'customer_id'=>$row['customer_id'],
		   'date_index'=>$row['date_index'],
		   'total'=>money($row['total']),
		   'titulo'=>$_order_tipo[$row['tipo']],
		   'tipo'=>$row['tipo']
		   );
   }
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
 case('transactions'):
   
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

   
   $sql="select  p.price as price,concat(100000+p.group_id,p.ncode) as display_order,p.id as id,p.code as code ,product_id,p.description,units,ordered,dispached,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where  and dispached> 0 and (charge!=0 or discount!=1)  order by $order $order_direction  ";
   
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
   $product_id=$_SESSION['state']['product']['id'];

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
   

   $_SESSION['state']['product']['orders']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
   $_order=$order;
   $_dir=$order_direction;
   $filter_msg='';


   $where=sprintf(" where product_id=%d ",$product_id);
   $wheref="";
   if(isset($_REQUEST['f_field']) and isset($_REQUEST['f_value'])){
     if($_REQUEST['f_field']=='public_id' or $_REQUEST['f_field']=='customer'){
       if($_REQUEST['f_value']!='')
	 $wheref=" and  ".$_REQUEST['f_field']." like '".addslashes($_REQUEST['f_value'])."%'";
     }
   }
   
  


   $sql="select count(*) as total from transaction    $where $wheref";
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   if($row=$res->fetchRow()) {
     $total=$row['total'];
   }
   if($wheref==''){
     $filtered=0;
   }else{
     $sql="select count(*) as total from transaction $where      ";
     $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
     if($row=$res->fetchRow()) {
       $filtered=$row['total']-$total;
     }
     
   }
   

   $sql=sprintf("select o.id as id,public_id,dispached,UNIX_TIMESTAMP(o.date_index) as date_index,dispached-ordered as undispached,customer_id,customer_name,o.tipo from transaction as t left join orden as o on (order_id=o.id)  %s %s    order by $order $order_direction  limit $start_from,$number_results"
		,$where
		,$wheref
		);
   // print $sql;

      $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   $data=array();
   while($row=$res->fetchRow()) {
     if($LU->checkRight(CUST_VIEW))
       $customer='<a href="customer.php?id='.$row['customer_id'].'">'.$row['customer_name'].'</a>';
     else
       $customer=$myconf['customer_id_prefix'].sprintf("%05d",$row['customer_id']);
     $data[]=array(
		   'id'=>$row['id'],
		   'public_id'=>$row['public_id'],
		   'customer_name'=>$customer,
		   'date_index'=>$row['date_index'],
		   'date'=> strftime("%A %e %B %Y", strtotime('@'.$row['date_index'])),
		   'dispached'=>number($row['dispached']),
		   'undispached'=>number($row['undispached']),
		   'tipo'=>$_order_tipo[$row['tipo']]
		   );
   }
   if($total==0)
     $rtext="This products has not been ordered yet";
   elseif($total<$number_results)
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

 case('withcustomerproduct'):
   if(!$LU->checkRight(CUST_VIEW))
     exit;

   $conf=$_SESSION['state']['product']['customers'];
   $product_id=$_SESSION['state']['product']['id'];
   
   if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $product_id=$_REQUEST['id'];
   else
     $product_id=$_SESSION['state']['product']['id'];

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
   

  $_SESSION['state']['product']['custumers']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
   $_order=$order;
   $_dir=$order_direction;
   $filter_msg='';

   $where=$where.sprintf(" and product_id=%d ",$product_id);
   $wheref="";
   
  if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))>=".$f_value."    ";
  elseif($f_field=='customer_name'  and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";


   $sql="select count(distinct customer_id) as total from  orden left join transaction on (order_id=orden.id)  $where $wheref";
   //      print "$sql";
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   if($row=$res->fetchRow()) {
     $total=$row['total'];
   }
   if($wheref==''){
     $filtered=0;
   }else{
     $sql="select count(distinct customer_id) as total from orden left join transaction on (order_id=orden.id) $where      ";
     $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
     if($row=$res->fetchRow()) {
       $filtered=$row['total']-$total;
     }
     
   }
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
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('only orders starting with')." <b>$f_value</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Remove Filter')."</span>";
       break;
     }
   }

   
   $_order=$order;
   $_dir=$order_direction;




   $sql=sprintf("select count(distinct o.id) as orders ,customer.name as customer_name,o.tipo,customer_id, sum(if(o.tipo=2,charge,0)) as charged, sum(if(o.tipo=2,dispached,0)) as dispached, sum(if(o.tipo=2,(ordered-dispached),0)) as nodispached , sum(if(o.tipo=1,(ordered-dispached),0))  as todispach from orden as o  left join transaction on (order_id=o.id) left join customer on (customer.id=customer_id) $where $wheref  group by customer_id    order by $order $order_direction  limit $start_from,$number_results "
		);

   //     print "$sql\n";
      $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   $data=array();
   while($row=$res->fetchRow()) {
     $data[]=array(
		   'customer_id'=>$row['customer_id'],
		   'customer_name'=>$row['customer_name'],
		   'charged'=>money($row['charged']),
		   'orders'=>number($row['orders']),
		   'todispach'=>number($row['todispach']),
		   'dispached'=>number($row['dispached']),
		   'nodispached'=>number($row['nodispached'])

		   );
   }
   
   if($total==0)
     $rtext=_('Nobody has ordered this product').'.';
   elseif($total<$number_results)
     $rtext=$total.' '.ngettext('customer','customers',$total);
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