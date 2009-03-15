<?

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
   
   $product_id=$_REQUEST['product_id'];
   $first_day=$_REQUEST['first_day'];
   // print $first_day;
  
   $sql="select date_format(`First Day`,'%c') as month, `First Day` as date, yearweek,date_format(`First Day`,'%v %x') as week,  UNIX_TIMESTAMP(`First Day`)+36000 as utime  from list_week where `First Day`>'$first_day' and `First Day` < NOW(); ";

   $data=array();
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   $i=0;
   $last_month='';
   while($row=$res->fetchRow()) {
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


   $tipo_orders=' (orden.tipo!=0 or orden.tipo!=3 or  orden.tipo!=9 or orden.tipo!=10) ';
   $sql=sprintf("select YEARWEEK(date_index) as yearweek,sum(charge)as asales,sum(profit)as profit,sum(dispached)as _out from transaction left join orden on (order_id=orden.id) where %s and product_id=%d  group by YEARWEEK(date_index)",$tipo_orders,$product_id);

   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}

    while($row=$res->fetchRow()) {

      if(isset($index[$row['yearweek']])){
	$_index=$index[$row['yearweek']];
	$data[$_index]['asales']=(float)$row['asales'];
	$data[$_index]['profit']=(float)$row['profit'];

	$data[$_index]['out']=(int)$row['_out'];
	$fday=strftime("%d %b", strtotime('@'.$data[$_index]['utime']));
	$data[$_index]['tip_out']=_('Outer Dispached')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".number($row['_out']).' '._('Outers');
	$data[$_index]['tip_asales']=_('Sales')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".money($row['asales']);
	$data[$_index]['tip_profit']=_('Profit')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".money($row['profit']);

      }
    }
    
    $tipo_orders=' (orden.tipo!=0 or orden.tipo!=3 or  orden.tipo!=9 or orden.tipo!=10) ';
   $sql=sprintf("select YEARWEEK(date_index) as yearweek,sum(qty)as bono from bonus left join orden on (order_id=orden.id) where %s and product_id=%d  group by YEARWEEK(date_index)",$tipo_orders,$product_id);

   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}

    while($row=$res->fetchRow()) {

      if(isset($index[$row['yearweek']])){
	$_index=$index[$row['yearweek']];
	$data[$_index]['bonus']=(float)$row['bono'];
	$fday=strftime("%d %b", strtotime('@'.$data[$_index]['utime']));
	$data[$_index]['tip_bonus']=_('Free Bonus')."\n"._('Week').' '.$data[$_index]['week']."\n"._('Starting')." ".$fday."\n".number($row['bono']).' '._('Outers');

      }
    }

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
