<?
include_once('common.php');
include_once('report_functions.php');

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 //		 $yui_path.'datatable/assets/skins/sam/datatable.css',
		 'common.css',
		 'button.css',
		 'container.css',
		 'table.css'
		 );
$js_files=array(

		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/calendar_common.js.php',

		'js/report_sales.js.php'
		);



$smarty->assign('parent','reports.php');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$tipo=$_REQUEST['tipo'];
if($tipo=='f'){

  $from=$_REQUEST['from'];
  $to=$_REQUEST['to'];
   $title=_('Sales Report');
 }elseif($tipo=='w'){
   $year=$_REQUEST['y'];
   $week=$_REQUEST['w'];
   
   $sql=sprintf("select UNIX_TIMESTAMP(`First Day`) as date ,`First Day` from `Week Dimension`  where `Year Week`='%04d%02d'",$year,$week);

   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){


     $_time=strtotime('@'.$row['date']);
     $_time_n=strtotime('@'.($row['date']+604800));
     $_time_p=strtotime('@'.($row['date']-604800));

 
     
     $_time_n_3weeks=date("Y-m-d H:i:s",strtotime('@'.($row['date']+1*604800)));
     $_time_p_3weeks=date("Y-m-d H:i:s",strtotime('@'.($row['date']-3*604800)));


   }else
     die('error no year-week found');



   $ffrom=date("d/m", $_time);
   $fto=date("d/m", $_time_n);
   $from=date("d-m-Y", $_time);
   $to=date("d-m-Y", $_time_n);
   $period=_('Week').' '.date("W Y", $_time);
   $title="$period ($ffrom-$fto)"._('Sales Report');

   $smarty->assign('up',array('url'=>'tipo=y&y='.date("Y",$_time),'title'=>date("Y",$_time)));
   $smarty->assign('next',array('url'=>'tipo=w&w='.date("W",$_time_n).'&y='.date("Y",$_time_n),'title'=>_('Week').' '.date("W-Y",$_time_n)));
   $smarty->assign('prev',array('url'=>'tipo=w&m='.date("W",$_time_p).'&y='.date("Y",$_time_p),'title'=>_('Week').' '.date("W-Y",$_time_p)));


   $w=array();
   $sql=sprintf("select * from `Week Dimension` where `First Day`> %s and `First Day`<%s "
		,prepare_mysql($_time_p_3weeks)
		,prepare_mysql($_time_n_3weeks)

		);
   
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $w[]=array(
		"number"=>$row['Week']
		,"mon"=>date("d",strtotime($row['First Day']))
		,"tue"=>date("d",strtotime($row['First Day']." +1 day" ))
		,"wed"=>date("d",strtotime($row['First Day']." +2 day" ))
		,"thu"=>date("d",strtotime($row['First Day']." +3 day" ))
		,"fri"=>date("d",strtotime($row['First Day']." +4 day" ))
		,"sat"=>date("d",strtotime($row['First Day']." +5 day" ))
		,"sun"=>date("d",strtotime($row['First Day']." +6 day" ))
		,"m_mon"=>date("m",strtotime($row['First Day']))
		,"m_tue"=>date("m",strtotime($row['First Day']." +1 day" ))
		,"m_wed"=>date("m",strtotime($row['First Day']." +2 day" ))
		,"m_thu"=>date("m",strtotime($row['First Day']." +3 day" ))
		,"m_fri"=>date("m",strtotime($row['First Day']." +4 day" ))
		,"m_sat"=>date("m",strtotime($row['First Day']." +5 day" ))
		,"m_sun"=>date("m",strtotime($row['First Day']." +6 day" ))
		
		,"year"=>$row['Year']

		);
   }
   

    $smarty->assign('w',$w);






 }elseif($tipo=='m'){
   $year=$_REQUEST['y'];
   $month=$_REQUEST['m'];
   
   $_time=mktime(0, 0, 0,$month ,1 , $year);
   $_time_n=mktime(0, 0, 0,$month+1 ,1 , $year);
   $_time_p=mktime(0, 0, 0,$month-1 ,1 , $year);

   $from=date("d-m-Y", $_time);
   $to=date("d-m-Y", mktime(0, 0, 0, $month+1, 0, $year));
   $period=strftime("%B %Y", $_time);
   $title="$period "._('Sales Report');

   $smarty->assign('up',array('url'=>'tipo=y&y='.date("Y",$_time),'title'=>date("Y",$_time)));
   $smarty->assign('next',array('url'=>'tipo=m&m='.date("m",$_time_n).'&y='.date("Y",$_time_n),'title'=>date("F",$_time_n)));
   $smarty->assign('prev',array('url'=>'tipo=m&m='.date("m",$_time_p).'&y='.date("Y",$_time_p),'title'=>date("F",$_time_p)));



   $w=array();
   $sql=sprintf("select * from `Week Dimension` where (Month(`Last Day`)=%d or  Month(`First Day`)=%d   )  and `Year`=%d "
		,date("m",$_time)
		,date("m",$_time)
		,date("Y",$_time)

		);
   //  print $sql;

   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $w[]=array(
		"number"=>$row['Week']
		,"mon"=>date("d",strtotime($row['First Day']))
		,"tue"=>date("d",strtotime($row['First Day']." +1 day" ))
		,"wed"=>date("d",strtotime($row['First Day']." +2 day" ))
		,"thu"=>date("d",strtotime($row['First Day']." +3 day" ))
		,"fri"=>date("d",strtotime($row['First Day']." +4 day" ))
		,"sat"=>date("d",strtotime($row['First Day']." +5 day" ))
		,"sun"=>date("d",strtotime($row['First Day']." +6 day" ))
		,"m_mon"=>date("m",strtotime($row['First Day']))
		,"m_tue"=>date("m",strtotime($row['First Day']." +1 day" ))
		,"m_wed"=>date("m",strtotime($row['First Day']." +2 day" ))
		,"m_thu"=>date("m",strtotime($row['First Day']." +3 day" ))
		,"m_fri"=>date("m",strtotime($row['First Day']." +4 day" ))
		,"m_sat"=>date("m",strtotime($row['First Day']." +5 day" ))
		,"m_sun"=>date("m",strtotime($row['First Day']." +6 day" ))
		,"year"=>$row['Year']

		);
   }
   

    $smarty->assign('w',$w);







 }elseif($tipo=='y'){
   $year=$_REQUEST['y'];

   
   $_time=mktime(0, 0, 0,1 ,1 , $year);
   $_time_n=mktime(0, 0, 0,1 ,1 , $year+1);
   $_time_p=mktime(0, 0, 0,1 ,1 , $year-1);

   $from=date("d-m-Y", $_time);
   $to=date("d-m-Y", mktime(0, 0, 0, 1, 0, $year+1));
   $period=date("Y", $_time);
   $title="$period "._('Sales Report');

   $smarty->assign('tipo_title',_('Annual Report'));
   $smarty->assign('next',array('url'=>'tipo=y&y='.date("Y",$_time_n),'title'=>date("Y",$_time_n)));
   $smarty->assign('prev',array('url'=>'tipo=y&y='.date("Y",$_time_p),'title'=>date("Y",$_time_p)));
   $m=array();
   foreach( range(1,12) as $_m){
     
     $m[]=substr(strftime("%b", mktime(0, 0, 0, $_m, 1, 2000)),0,1);
     
   }

    $smarty->assign('m',$m);













}elseif($tipo=='d'){
   $year=$_REQUEST['y'];
   $month=$_REQUEST['m'];
   $day=$_REQUEST['d'];

   $_time=mktime(0, 0, 0,$month ,$day , $year);
   $_time_n=mktime(0, 0, 0,$month ,$day+1 , $year);
   $_time_p=mktime(0, 0, 0,$month ,$day-1 , $year);


   $_time_n_3weeks=mktime(0, 0, 0,$month ,$day+14 , $year);
   $_time_p_3weeks=mktime(0, 0, 0,$month ,$day-14 , $year);



   $from=date("d-m-Y", $_time);
   $to=date("d-m-Y", $_time);
    $period=strftime("%A %e %B %Y", $_time);
   $title="$period "._('Sales Report');

   $smarty->assign('up',array('url'=>'tipo=m&m='.date("m",$_time_n).'&y='.date("Y",$_time),'title'=>strftime("%B",$_time)));
   $smarty->assign('next',array('url'=>'tipo=d&m='.date("m",$_time_n).'&y='.date("Y",$_time_n).'&d='.date("d",$_time_n),'title'=>strftime("%e %b %y",$_time_n)));
   $smarty->assign('prev',array('url'=>'tipo=d&m='.date("m",$_time_p).'&y='.date("Y",$_time_p).'&d='.date("d",$_time_n),'title'=>strftime("%e %b %y",$_time_p)));




   $w=array();
   $sql=sprintf("select * from `Week Dimension` where (Month(`Last Day`)=%d or  Month(`First Day`)=%d   )  and `Year`=%d "
		,date("m",$_time)
		,date("m",$_time)
		,date("Y",$_time)

		);
   //  print $sql;

   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $w[]=array(
		"number"=>$row['Week']
		,"mon"=>date("d",strtotime($row['First Day']))
		,"tue"=>date("d",strtotime($row['First Day']." +1 day" ))
		,"wed"=>date("d",strtotime($row['First Day']." +2 day" ))
		,"thu"=>date("d",strtotime($row['First Day']." +3 day" ))
		,"fri"=>date("d",strtotime($row['First Day']." +4 day" ))
		,"sat"=>date("d",strtotime($row['First Day']." +5 day" ))
		,"sun"=>date("d",strtotime($row['First Day']." +6 day" ))
		,"m_mon"=>date("m",strtotime($row['First Day']))
		,"m_tue"=>date("m",strtotime($row['First Day']." +1 day" ))
		,"m_wed"=>date("m",strtotime($row['First Day']." +2 day" ))
		,"m_thu"=>date("m",strtotime($row['First Day']." +3 day" ))
		,"m_fri"=>date("m",strtotime($row['First Day']." +4 day" ))
		,"m_sat"=>date("m",strtotime($row['First Day']." +5 day" ))
		,"m_sun"=>date("m",strtotime($row['First Day']." +6 day" ))
		,"year"=>$row['Year']

		);
   }
   

    $smarty->assign('w',$w);





 }
  













$_SESSION['state']['report']['sales']['to']=$to;
$_SESSION['state']['report']['sales']['from']=$from;
$_SESSION['state']['report']['sales']['period']=$period;

  
$valid_rates=array(
		   array('date'=>'01-01-2000','rate'=>17.5),
		   array('date'=>'01-12-2008','rate'=>15)
		   );

$interval_data=sales_in_interval($from,$to,$valid_rates);
//print_r($interval_data);
$smarty->assign('novalue_invoices',$interval_data['errors']['novalue_invoices']);
$smarty->assign('f_novalue_invoices',number($interval_data['errors']['novalue_invoices']));

$smarty->assign('error_taxable',$interval_data['errors']['taxable']);
$smarty->assign('error_notaxable',$interval_data['errors']['notaxable']);
foreach($interval_data['taxable'] as $key=>$value){
  $interval_data['taxable'][$key]['invoices']=number($value['invoices']);
  $interval_data['taxable'][$key]['sales']=money($value['sales']);
  $interval_data['taxable'][$key]['tax']=money($value['tax']);
}
foreach($interval_data['notaxable'] as $key=>$value){
  $interval_data['notaxable'][$key]['invoices']=number($value['invoices']);
  $interval_data['notaxable'][$key]['sales']=money($value['sales']);
  $interval_data['notaxable'][$key]['tax']=money($value['tax']);
}
$smarty->assign('taxable',$interval_data['taxable']);
$smarty->assign('notaxable',$interval_data['notaxable']);
$smarty->assign('balance',$interval_data['balance']);



$day_interval=get_time_interval(strtotime($from),(strtotime($to)))+1;

   if($day_interval>=7){
     $_from=$from;
     $_to=$to;
     preg_match('/\d{4}$/',$from,$match1);
     $last_year=$match1[0]-1;
     $_from=preg_replace('/\d{4}$/',$last_year,$_from);
     preg_match('/\d{4}$/',$to,$match2);
     $last_year=$match2[0]-1;
     $_to=preg_replace('/\d{4}$/',$last_year,$_to);


     $interval_data_last_year=sales_in_interval($_from,$_to,$valid_rates);

    


     $invoices=$interval_data['invoices']['total_invoices'];
     $invoices_ly=$interval_data_last_year['invoices']['total_invoices'];
     $net=$interval_data['sales']['total_net'];
     $net_ly=$interval_data_last_year['sales']['total_net'];
     $net=$interval_data['sales']['total_net'];
     $net_ly=$interval_data_last_year['sales']['total_net'];
     $orders_received=$interval_data['orders']['orders_total'];
     $orders_received_ly=$interval_data_last_year['orders']['orders_total'];
     
     $diff_sales=$net-$net_ly;
     $diff_sales_change=($diff_sales>0?'+':'');
     $smarty->assign('diff_sales_change',$diff_sales_change);
     $smarty->assign('diff_sales',money($diff_sales));
     $smarty->assign('diff_sales_per',percentage($diff_sales,$net_ly,2));
   
     $diff_invoices=$invoices-$invoices_ly;
     $diff_invoices_change=($diff_invoices>0?'+':'');
     $smarty->assign('diff_invoices_change',$diff_invoices_change);
     $smarty->assign('diff_invoices',$diff_invoices_change.number($diff_invoices));
     $smarty->assign('diff_invoices_per',percentage($diff_invoices,$invoices_ly,2));

     $smarty->assign('orders_state',$interval_data['orders_state']);
	  
     
     $diff_invoices_home=$interval_data['invoices']['invoices_home']-$interval_data_last_year['invoices']['invoices_home'];
     $diff_invoices_home_change=($diff_invoices_home>0?'+':'');
     $smarty->assign('diff_invoices_home_change',$diff_invoices_home_change);
     $smarty->assign('diff_invoices_home',$diff_invoices_home_change.number($diff_invoices_home));
     $smarty->assign('diff_invoices_home_per',percentage($diff_invoices_home,$interval_data_last_year['invoices']['invoices_home'],2));
     $diff_invoices_nohome=$interval_data['invoices']['invoices_nohome']-$interval_data_last_year['invoices']['invoices_nohome'];
     $diff_invoices_nohome_change=($diff_invoices_nohome>0?'+':'');
     $smarty->assign('diff_invoices_nohome_change',$diff_invoices_nohome_change);
     $smarty->assign('diff_invoices_nohome',$diff_invoices_nohome_change.number($diff_invoices_nohome));
     $smarty->assign('diff_invoices_nohome_per',percentage($diff_invoices_nohome,$interval_data_last_year['invoices']['invoices_nohome'],2));
     $diff_invoices_partners=$interval_data['invoices']['invoices_p']-$interval_data_last_year['invoices']['invoices_p'];
     $diff_invoices_partners_change=($diff_invoices_partners>0?'+':'');
     $smarty->assign('diff_invoices_partners_change',$diff_invoices_partners_change);
     $smarty->assign('diff_invoices_partners',$diff_invoices_partners_change.number($diff_invoices_partners));
     $smarty->assign('diff_invoices_partners_per',percentage($diff_invoices_partners,$interval_data_last_year['invoices']['invoices_p'],2));


     $diff_sales_home=$interval_data['sales']['net_home']-$interval_data_last_year['sales']['net_home'];
     $diff_sales_home_change=($diff_sales_home>0?'+':'');
     $smarty->assign('diff_sales_home_change',$diff_sales_home_change);
     $smarty->assign('diff_sales_home',money($diff_sales_home));
     $smarty->assign('diff_sales_home_per',percentage($diff_sales_home,$interval_data_last_year['sales']['net_home'],2));
     $diff_sales_nohome=$interval_data['sales']['net_nohome']-$interval_data_last_year['sales']['net_nohome'];
     $diff_sales_nohome_change=($diff_sales_nohome>0?'+':'');
     $smarty->assign('diff_sales_nohome_change',$diff_sales_nohome_change);
     $smarty->assign('diff_sales_nohome',money($diff_sales_nohome));
     $smarty->assign('diff_sales_nohome_per',percentage($diff_sales_nohome,$interval_data_last_year['sales']['net_nohome'],2));
     $diff_sales_partners=$interval_data['sales']['net_p']-$interval_data_last_year['sales']['net_p'];
     $diff_sales_partners_change=($diff_sales_partners>0?'+':'');
     $smarty->assign('diff_sales_partners_change',$diff_sales_partners_change);
     $smarty->assign('diff_sales_partners',money($diff_sales_partners));
     $smarty->assign('diff_sales_partners_per',percentage($diff_sales_partners,$interval_data_last_year['sales']['net_p'],2));



     $smarty->assign('diff_orders_received',$orders_received-$orders_received_ly);
     $smarty->assign('per_diff_orders_received',percentage($orders_received-$orders_received_ly,$orders_received_ly,2));

      $diff_sales=0;
      if($net_ly>$net){
	$diff_sales=-1;
	$smarty->assign('text_diff_sales',_('a decrese of').' '.percentage(($net_ly-$net),$net_ly));


      }elseif($net_ly < $net){
	$diff_sales=+1;
	$smarty->assign('text_diff_sales',_('an increse of').' '.percentage(($net-$net_ly),$net_ly));

      }else{
	 $smarty->assign('text_diff_sales',_('no change'));

      }
      


      if($invoices_ly>$invoices){
	if($diff_sales==1)
	  $link=_('but');
	else
	  $link=_('and');
	$smarty->assign('text_diff_invoices_link',$link.' ');
	$smarty->assign('text_diff_invoices',($invoices_ly-$invoices).' '._(' less'));

      }elseif($invoices_ly<$invoices){
	
	if($diff_sales==-1)
	  $link=_('but');
	else
	  $link=_('and');
	$smarty->assign('text_diff_invoices_link',$link.' ');
	
	$smarty->assign('text_diff_invoices',$invoices-$invoices_ly.' '._(' more '));

      }else{
	 $smarty->assign('text_diff_invoices','');

      
      }
   }
//end last year


if(isset($interval_data['exports']['top3'][0])){
  $smarty->assign('export_country1',$interval_data['exports']['top3'][0]['country']);
  $smarty->assign('per_export_country1',percentage($interval_data['exports']['top3'][0]['net'],$interval_data['sales']['net_nohome']));
  if(isset($interval_data['exports']['top3'][1])){
    $smarty->assign('export_country2',$interval_data['exports']['top3'][1]['country']);
    $smarty->assign('per_export_country2',percentage($interval_data['exports']['top3'][1]['net'],$interval_data['sales']['net_nohome']));
    if(isset($interval_data['exports']['top3'][2])){
      $smarty->assign('export_country3',$interval_data['exports']['top3'][2]['country']);
      $smarty->assign('per_export_country3',percentage($interval_data['exports']['top3'][2]['net'],$interval_data['sales']['net_nohome']));
    }
  }
 }
$smarty->assign('export_countries',$interval_data['exports']['countries']);
   
   //   print_r($interval_data['exports']['top3']);

   $smarty->assign('home',$myconf['home']);
   $smarty->assign('_home',$myconf['_home']);

   $smarty->assign('extended_home',$myconf['extended_home']);
   $smarty->assign('extended_home_nohome',$myconf['s_extended_home_nohome']);

   $smarty->assign('region',$myconf['region']);
   $smarty->assign('home',$myconf['home']);
   $smarty->assign('region',$myconf['region']);
   $smarty->assign('region2',$myconf['continent']);
   $smarty->assign('outside',$myconf['outside']);
   $smarty->assign('org',$myconf['s_org']);


   $smarty->assign('days',$day_interval.' '.ngettext('day','days',$day_interval));
   
   
   
   //   $smarty->assign('total_net_sales', money($interval_data['sales']['total_net']));
   // $smarty->assign('total_invoices', number($interval_data['invoices']['total_invoices']));

   $smarty->assign('per_partner_sales', percentage($interval_data['sales']['net_p'],$interval_data['sales']['total_net']));
   $smarty->assign('per_export', percentage($interval_data['sales']['total_net_nohome'],$interval_data['sales']['total_net']));
   $smarty->assign('per_export_nop', percentage($interval_data['sales']['net_nohome'],$interval_data['sales']['total_net']));

  foreach($interval_data['refunds'] as $key=>$value){
     $smarty->assign($key, money($value));
   }

   foreach($interval_data['sales'] as $key=>$value){
     $smarty->assign($key, money($value));
   }
 foreach($interval_data['invoices'] as $key=>$value){
     $smarty->assign($key, number($value));
   }

$smarty->assign('dispatch_days', number($interval_data['other_data']['dispatch_days'],2));
$smarty->assign('dispatch_days_home', number($interval_data['other_data']['dispatch_days_home'],2));
$smarty->assign('dispatch_days_nohome', number($interval_data['other_data']['dispatch_days_nohome'],2));

$total_orders=$interval_data['orders']['orders_total'];
foreach($interval_data['orders'] as $key=>$value){
  if(preg_match('/_net$/',$key)){
    $smarty->assign($key, money($value));
  }else{
    $smarty->assign($key, number($value));
    $smarty->assign('per_'.$key, percentage($value,$total_orders));
  }
  
}
$smarty->assign('dn',$interval_data['dn_data']);
$smarty->assign('dn_total',number($interval_data['dn']['dn_total']));
$smarty->assign('dn_total_weight',number($interval_data['dn']['dn_total_weight']).' Kg'  );

$smarty->assign('tipo',$tipo);
$smarty->assign('period',$period);

$smarty->assign('title',$title);
$smarty->assign('year',date('Y'));
$smarty->assign('month',date('m'));
$smarty->assign('month_name',date('M'));


$smarty->assign('week',date('W'));
$smarty->assign('from',date('d-m-Y'));
$smarty->assign('to',date('d-m-Y'));
$smarty->assign('currency',$myconf['currency_symbol']);


$smarty->display('report_sales.tpl');

//  }else{

// $smarty->assign('parent','reports.php');
// $smarty->assign('title', _('Sales Reports'));
// $smarty->assign('css_files',$css_files);
// $smarty->assign('js_files',$js_files);
// $smarty->assign('year',date('Y'));
// $smarty->assign('month',date('m'));
// $smarty->assign('month_name',date('M'));

// $smarty->assign('week',date('W'));
// $smarty->assign('from',date('d-m-Y'));
// $smarty->assign('to',date('d-m-Y'));


// $smarty->display('report_sales.tpl');
//  }
?>

