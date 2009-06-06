<?
require_once 'common.php';
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
 case('product_week_outers'): 
 case('product_week_sales'):
   
  $mode=$_SESSION['state']['product']['mode'];
  $tag=$_SESSION['state']['product']['tag'];
  $first_day=$_SESSION['state']['product']['plot_data']['week']['first_day'];
  // print $first_day;
  
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
	$data[$_index]['tip_out']=_('Outer Dispached')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".number($row['_out']).' '._('Outers');
	$data[$_index]['tip_asales']=_('Sales')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".money($row['asales']);
	$data[$_index]['tip_profit']=_('Profit')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".money($row['profit']);
	$data[$_index]['bonus']=0;
	$data[$_index]['tip_bonus']='';
	//	$data[$_index]['tip_bonus']=_('Free Bonus')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".number($row['bono']).' '._('Outers');
      }
    }
    
   
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 )
		   );

   echo json_encode($response);
   break;
 case('product_month_outers'): 
 case('product_month_sales'):
   
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
	$data[$_index]['tip_out']=_('Outers Dispached')."\n".strftime("%b %y",strtotime($data[$_index]['_date']))."\n".number($row['_out']).' '._('Outers');
	$data[$_index]['tip_asales']=_('Sales')."\n".strftime("%b %y",strtotime($data[$_index]['_date']))."\n".money($row['asales']);
	$data[$_index]['tip_profit']=_('Profit')."\n".strftime("%b %y",strtotime($data[$_index]['_date']))."\n".money($row['profit']);
	$data[$_index]['bonus']=0;
	$data[$_index]['tip_bonus']='';
	//	$data[$_index]['tip_bonus']=_('Free Bonus')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".number($row['bono']).' '._('Outers');
      }
    }
   //  print_r($data);
   
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 )
		   );

      echo json_encode($response);
   break;
 case('product_quarter_outers'): 
 case('product_quarter_sales'):
   
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
	$data[$_index]['tip_out']=_('Outers Dispached')."\n".strftime("%b %y",strtotime($data[$_index]['_date']))."\n".number($row['_out']).' '._('Outers');
	$data[$_index]['tip_asales']=_('Sales')."\n".strftime("%b %y",strtotime($data[$_index]['_date']))."\n".money($row['asales']);
	$data[$_index]['tip_profit']=_('Profit')."\n".strftime("%b %y",strtotime($data[$_index]['_date']))."\n".money($row['profit']);
	$data[$_index]['bonus']=0;
	$data[$_index]['tip_bonus']='';
	//	$data[$_index]['tip_bonus']=_('Free Bonus')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".number($row['bono']).' '._('Outers');
      }
    }
   //  print_r($data);
   
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 )
		   );

      echo json_encode($response);
   break;

 case('product_year_outers'): 
 case('product_year_sales'):
   
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
	$data[$_index]['tip_out']=$row['year'].' '._('Outers Dispached')."\n".number($row['_out']).' '._('Outers');
	$data[$_index]['tip_asales']=$row['year'].' '._('Sales')."\n".money($row['asales']);
	$data[$_index]['tip_profit']=$row['year'].' '._('Profits')."\n".money($row['profit']);
	$data[$_index]['bonus']=0;
	$data[$_index]['tip_bonus']='';
	//	$data[$_index]['tip_bonus']=_('Free Bonus')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".number($row['bono']).' '._('Outers');
      }
    }
   //  print_r($data);
   
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 )
		   );

      echo json_encode($response);
   break;


 default:
   
   $response=array('state'=>404,'resp'=>_('Operation not found'));
   echo json_encode($response);
   
 }




?>
