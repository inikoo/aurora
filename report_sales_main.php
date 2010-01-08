<?php
include_once('common.php');
include_once('report_functions.php');
include_once('class.Store.php');

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 //		 $yui_path.'datatable/assets/skins/sam/datatable.css',
		 'common.css',
		 'button.css',
		 'container.css',
		 'table.css',
		 		 'css/dropdown.css'

		 );
$js_files=array(

		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'common.js.php',
		'table_common.js.php',
		'calendar_common.js.php',

		'report_sales.js.php',
				 'js/dropdown.js'

		);



$smarty->assign('parent','reports');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
if(isset($_REQUEST['tipo'])){
$tipo=$_REQUEST['tipo'];
$_SESSION['state']['report']['tipo']=$tipo;
}else
$tipo=$_SESSION['state']['report']['tipo'];

include_once('report_dates.php');








$_SESSION['state']['report']['sales']['to']=$to;
$_SESSION['state']['report']['sales']['from']=$from;
$_SESSION['state']['report']['sales']['period']=$period;
global $myconf;
  
/* $valid_rates=array( */
/* 		   array('date'=>'01-01-2000','rate'=>17.5), */
/* 		   array('date'=>'01-12-2008','rate'=>15) */
/* 		   ); */


$int=prepare_mysql_dates($from,$to,'`Invoice Date`','date start end');


$store_data=array();
$sql="select `Store Name`,`Store Key`,`Store Currency Code` from `Store Dimension`";
$result=mysql_query($sql);
$mixed_currencies=false;
while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
  if($row['Store Currency Code']!=$myconf['currency_code'])
    $mixed_currencies=true;
  $store_data[$row['Store Key']]=array(
				       'store'=>sprintf('<a href="report_sales.php?store_key=%d%s">%s</a>',$row['Store Key'],$link,$row['Store Name'])
				       ,'currency_code'=>$row['Store Currency Code']
				       ,'net'=>'<b>'.money(0,$row['Store Currency Code']).'</b>'
				       ,'tax'=>'<b>'.money(0,$row['Store Currency Code']).'</b>'
				       ,'eq_tax'=>money(0)
				       ,'eq_net'=>money(0)
				       ,'_eq_tax'=>0
				       ,'_eq_net'=>0
				       ,'invoices'=>0
				       );
  
   $sql=sprintf("select `Invoice Category`,`Store Name`,`Store Key`,`Store Currency Code`,sum(if(`Invoice Title`='Invoice',1,0)) as invoices,sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Tax Amount`) as tax ,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as eq_net,sum(`Invoice Total Tax Amount`*`Invoice Currency Exchange`) as eq_tax from `Invoice Dimension` I left join `Store Dimension` S on (S.`Store Key`=`Invoice Store Key`) where `Invoice Store Key`=%d %s group by `Invoice Category`",$row['Store Key'],$int[0]);
  $result2=mysql_query($sql);
  if(mysql_num_rows($result2) >1 ){
  while($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
    $store_data[$row['Store Key'].'.'.$row2['Invoice Category']]=array(
								       'store'=>''
								       ,'substore'=>sprintf("%s",$row2['Invoice Category'])
								       ,'invoices'=>number($row2['invoices'])
								       ,'net'=>money($row2['net'],$row['Store Currency Code'])
								       ,'tax'=>money($row2['tax'],$row['Store Currency Code'])
								       ,'eq_net'=>money($row2['eq_net'])
								       ,'eq_tax'=>money($row2['eq_tax'])
								       ,'_eq_net'=>$row2['eq_net']
								       ,'_eq_tax'=>$row2['eq_tax']
								       ,'currency_code'=>$row['Store Currency Code']
								       );
  }

  }
}




$sql="select `Invoice Store Key`,sum(if(`Invoice Title`='Invoice',1,0)) as invoices,sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Tax Amount`) as tax ,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as eq_net,sum(`Invoice Total Tax Amount`*`Invoice Currency Exchange`) as eq_tax from `Invoice Dimension` where true ".$int[0]." group by `Invoice Store Key`";
//print $sql;
$result=mysql_query($sql);
$sum_net_eq=0;
$sum_tax_eq=0;
$sum_inv=0;
$mixed_currencies=false;
while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
  $sum_net_eq+=$row['eq_net'];
  $sum_tax_eq+=$row['eq_tax'];
  $sum_inv+=$row['invoices'];

  $store_data[$row['Invoice Store Key']]['invoices']='<b>'.number($row['invoices']).'</b>';
  $store_data[$row['Invoice Store Key']]['net']='<b>'.money($row['net'],$store_data[$row['Invoice Store Key']]['currency_code']).'</b>';
  $store_data[$row['Invoice Store Key']]['tax']='<b>'.money($row['tax'],$store_data[$row['Invoice Store Key']]['currency_code']).'</b>';
  $store_data[$row['Invoice Store Key']]['eq_net']=money($row['eq_net']);
  $store_data[$row['Invoice Store Key']]['eq_tax']=money($row['eq_tax']);
  $store_data[$row['Invoice Store Key']]['_eq_net']=$row['eq_net'];
  $store_data[$row['Invoice Store Key']]['_eq_ta']=$row['eq_tax'];

  
 /*  $sql=sprintf("select `Invoice Category`,`Store Name`,`Store Key`,`Store Currency Code`,sum(if(`Invoice Title`='Invoice',1,0)) as invoices,sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Tax Amount`) as tax ,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as eq_net,sum(`Invoice Total Tax Amount`*`Invoice Currency Exchange`) as eq_tax from `Invoice Dimension` I left join `Store Dimension` S on (S.`Store Key`=`Invoice Store Key`) where `Invoice Store Key`=%d %s group by `Invoice Category`",$row['Invoice Store Key'],$int[0]); */
/*   $result2=mysql_query($sql); */
/*   if(mysql_num_rows($result2) >1 ){ */
/*   while($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){ */
/*     $store_data[]=array( */
/* 			'store'=>'' */
/* 			,'substore'=>sprintf("%s",$row2['Invoice Category']) */
/* 			,'invoices'=>number($row2['invoices']) */
/* 			,'net'=>money($row2['net'],$row['Store Currency Code']) */
/* 			,'tax'=>money($row2['tax'],$row['Store Currency Code']) */
/* 			,'eq_net'=>money($row2['eq_net']) */
/* 			,'eq_tax'=>money($row2['eq_tax']) */
/* 			,'_eq_net'=>$row2['eq_net'] */
/* 			,'_eq_tax'=>$row2['eq_tax'] */
/* 			,'currency_code'=>$row['Store Currency Code'] */
/* 			); */
/*   }       */
}
//mysql_free_result($result2);

mysql_free_result($result);
foreach($store_data as $key=>$val){
  if($val['store']!=''){
  if($val['currency_code']!=$myconf['currency_code'])
    $store_data[$key]['per_eq_net']='<span class="mix_currency">'.percentage($val['_eq_net'],$sum_net_eq).'</span>';
  else
    $store_data[$key]['per_eq_net']=percentage($val['_eq_net'],$sum_net_eq);
  }else{
     if($val['currency_code']!=$myconf['currency_code'])
    $store_data[$key]['sub_per_eq_net']='<span class="mix_currency">'.percentage($val['_eq_net'],$sum_net_eq).'</span>';
  else
    $store_data[$key]['sub_per_eq_net']=percentage($val['_eq_net'],$sum_net_eq);
    
  }

}


if($mixed_currencies){
  $store_data[]=array(
		   'store'=>_('Total')
		   ,'invoices'=>number($sum_inv)
		   ,'net'=>'<span class="mix_currency">'.money($sum_net_eq).'</span>'
		   ,'tax'=>'<span class="mix_currency">'.money($sum_tax_eq).'</span>'
		   
		      );
}else{
   $store_data[]=array(
		   'store'=>_('Total')
		   ,'invoices'=>number($sum_inv)
		   ,'net'=>money($sum_net_eq)
		   ,'tax'=>money($sum_tax_eq)
		   
		      );

}
  



//print_r($store_data);
$smarty->assign('store_data',$store_data);

$plot_tipo=$_SESSION['state']['report']['sales']['plot'];
//print $plot_tipo.preg_replace('/tipo/i','dtipo',$link);
$smarty->assign('plot_tipo',$plot_tipo.preg_replace('/tipo/i','dtipo',$link));


$day_interval=get_time_interval(strtotime($from),(strtotime($to)))+1;
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

$plot_tipo=$_SESSION['state']['report']['sales']['plot'];
$plot_data=$_SESSION['state']['report']['sales']['plot_data'][$plot_tipo];
$plot_category=$plot_data['category'];
if($tipo=='y'){
  $plot_period='m';
}elseif($tipo=='m'){
  $plot_period='w';
}

$plot_args='tipo=store&category='.$plot_category.'&period='.$plot_period.'&keys=all&from='.$from.'&to='.$to;
$smarty->assign('plot_tipo',$plot_tipo);
$smarty->assign('plot_args',$plot_args);
$smarty->assign('plot_page',$plot_data['page']);
$smarty->assign('plot_period',$plot_period);
$smarty->assign('plot_category',$plot_period);
$smarty->assign('plot_data',$_SESSION['state']['store']['plot_data']);


 if($plot_period=='m')
      $plot_formated_period='Monthly';
    elseif($plot_period=='y')
      $plot_formated_period='Yearly';
    elseif($plot_period=='q')
      $plot_formated_period='Quarterly';
    elseif($plot_period=='w')
      $plot_formated_period='Weekly';

if($plot_category=='profit')
  $plot_formated_category=_('Profits');
else
  $plot_formated_category=_('Net Sales');
$smarty->assign('plot_formated_category',$plot_formated_category);
$smarty->assign('plot_formated_period',$plot_formated_period);

$smarty->display('report_sales_main.tpl');
?>

