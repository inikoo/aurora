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
		 'css/calendar.css',
		 'css/dropdown.css'

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
		'common.js.php',
		'table_common.js.php',
		'calendar_common.js.php',
		
		//		'report_sales.js.php',
		'report_sales_main.js.php',
		'reports_calendar.js.php',
		'js/dropdown.js'

		);

$root_title=_('Sales Report');

include_once('reports_list.php');

$smarty->assign('parent','reports');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



if(isset($_REQUEST['tipo'])){
  $tipo=$_REQUEST['tipo'];
  $_SESSION['state']['report_sales']['tipo']=$tipo;
}else
  $tipo=$_SESSION['state']['report_sales']['tipo'];

$sql=sprintf("select count(*) as num_stores,GROUP_CONCAT(Distinct `Currency Symbol`) as store_currencies from  `Store Dimension` left join kbase.`Currency Dimension` CD on (CD.`Currency Code`=`Store Currency Code`) ");
$res=mysql_query($sql);

if($row=mysql_fetch_array($res)){
  $num_stores=$row['num_stores'];
  $store_currencies=$row['store_currencies'];
}else{
  exit("no stores");
}

if($_SESSION['state']['report_sales']['store_keys']=='all'){
  $store_keys=join(',',$user->stores);
  $formated_store_keys='all';
}
else{
  $store_keys=$_SESSION['state']['report_sales']['store_keys'];
  $formated_store_keys=$store_keys;

}

if($store_keys=='all'){
  global $user;
  $store_keys=join(',',$user->stores);

}

$sql=sprintf("select `Corporation Currency`,`Currency Symbol` from  `Corporation Dimension` left join kbase.`Currency Dimension` CD on (CD.`Currency Code`=`Corporation Currency`) ");
$res=mysql_query($sql);

if($row=mysql_fetch_array($res)){
$corporate_currency=$row['Corporation Currency'];
$corporate_symbol=$row['Currency Symbol'];
}

$smarty->assign('store_currencies',$store_currencies);
$smarty->assign('corporate_symbol',$corporate_symbol);

$store_key=$store_keys;

$sql=sprintf("select `Invoice Category Key` from  `Invoice Category Dimension` where `Store Key` in (%s)",$store_keys);
///print $sql;
$res=mysql_query($sql);
$invoice_category_key=array();
while($row=mysql_fetch_array($res)){
  $invoice_category_key[]=$row['Invoice Category Key'];
}
$smarty->assign('view',$_SESSION['state']['report_sales']['view']);

//print_r($_SESSION['state']['report_sales']['currency']);

$smarty->assign('currencies',$_SESSION['state']['report_sales']['currency']);

$smarty->assign('store_keys',$store_keys);
$smarty->assign('formated_store_keys',$formated_store_keys);
$smarty->assign('invoice_category_keys','('.join(',',$invoice_category_key).')');
$report_name='report_sales';

include_once('report_dates.php');

$smarty->assign('report_url','report_sales_main.php');

$_SESSION['state']['report_sales']['to']=$to;
$_SESSION['state']['report_sales']['from']=$from;
$_SESSION['state']['report_sales']['period']=$period;


global $myconf;
  
/* $valid_rates=array( */
/* 		   array('date'=>'01-01-2000','rate'=>17.5), */
/* 		   array('date'=>'01-12-2008','rate'=>15) */
/* 		   ); */


$int=prepare_mysql_dates($from,$to,'`Invoice Date`','date start end');


$store_data=array();
$store_data_profit=array();

$sql="select `Store Name`,`Store Key`,`Store Currency Code` from `Store Dimension`";
$result=mysql_query($sql);
$mixed_currencies=false;
while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
  if($row['Store Currency Code']!=$corporate_currency)
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
  
 $store_data_profit[$row['Store Key']]=array(
				       'store'=>sprintf('<a href="report_sales.php?store_key=%d%s">%s</a>',$row['Store Key'],$link,$row['Store Name'])
				       ,'currency_code'=>$row['Store Currency Code']
				       ,'net'=>'<b>'.money(0,$row['Store Currency Code']).'</b>'
				       ,'profit'=>'<b>'.money(0,$row['Store Currency Code']).'</b>'
				       ,'eq_net'=>'<b>'.money(0,$corporate_currency).'</b>'
				       ,'eq_profit'=>'<b>'.money(0,$corporate_currency).'</b>'
				       ,'margin'=>'<b>NA</b>'
				       );
  


  $sql=sprintf("select `Invoice Category`,`Store Name`,`Store Key`,`Store Currency Code`,sum(if(`Invoice Title`='Invoice',1,0)) as invoices,sum(`Invoice Total Profit`) as profit,sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Tax Amount`) as tax ,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as eq_net,sum(`Invoice Total Tax Amount`*`Invoice Currency Exchange`) as eq_tax from `Invoice Dimension` I left join `Store Dimension` S on (S.`Store Key`=`Invoice Store Key`) where `Invoice Store Key`=%d %s group by `Invoice Category`",$row['Store Key'],$int[0]);
// print $sql."<br><br>";
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
      
        $store_data_profit[$row['Store Key'].'.'.$row2['Invoice Category']]=array(
									 'store'=>''
									 ,'substore'=>sprintf("%s",$row2['Invoice Category'])
									
									 ,'net'=>money($row2['net'],$row['Store Currency Code'])
									 ,'profit'=>money($row2['profit'],$row['Store Currency Code'])
									 ,'margin'=>percentage($row2['profit'],$row2['net'])

									 );

    
    
    }

  }


 



}








$sql="select `Invoice Store Key`,sum(if(`Invoice Title`='Invoice',1,0)) as invoices,sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Profit`*`Invoice Currency Exchange`) as eq_profit,sum(`Invoice Total Profit`) as profit,sum(`Invoice Total Tax Amount`) as tax ,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as eq_net,sum(`Invoice Total Tax Amount`*`Invoice Currency Exchange`) as eq_tax from `Invoice Dimension` where true ".$int[0]." group by `Invoice Store Key`";
//print $sql;
$result=mysql_query($sql);
$sum_net_eq=0;
$sum_tax_eq=0;
$sum_inv=0;
 $sum_profit_eq=0;
$mixed_currencies=false;
while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
  $sum_net_eq+=$row['eq_net'];
  $sum_profit_eq+=$row['eq_profit'];
  $sum_tax_eq+=$row['eq_tax'];
  $sum_inv+=$row['invoices'];

  $store_data[$row['Invoice Store Key']]['invoices']='<b>'.number($row['invoices']).'</b>';
  $store_data[$row['Invoice Store Key']]['net']='<b>'.money($row['net'],$store_data[$row['Invoice Store Key']]['currency_code']).'</b>';
  $store_data[$row['Invoice Store Key']]['tax']='<b>'.money($row['tax'],$store_data[$row['Invoice Store Key']]['currency_code']).'</b>';
  $store_data[$row['Invoice Store Key']]['eq_net']=money($row['eq_net'],$corporate_currency);
  $store_data[$row['Invoice Store Key']]['eq_tax']=money($row['eq_tax'],$corporate_currency);
  $store_data[$row['Invoice Store Key']]['_eq_net']=$row['eq_net'];
  $store_data[$row['Invoice Store Key']]['_eq_tax']=$row['eq_tax'];
  $store_data_profit[$row['Invoice Store Key']]['net']='<b>'.money($row['net'],$store_data[$row['Invoice Store Key']]['currency_code']).'</b>';
    $store_data_profit[$row['Invoice Store Key']]['eq_net']='<b>'.money($row['eq_net'],$corporate_currency).'</b>';

  $store_data_profit[$row['Invoice Store Key']]['profit']='<b>'.money($row['profit'],$store_data[$row['Invoice Store Key']]['currency_code']).'</b>';
    $store_data_profit[$row['Invoice Store Key']]['eq_profit']='<b>'.money($row['eq_profit'],$corporate_currency).'</b>';

  $store_data_profit[$row['Invoice Store Key']]['margin']='<b>'.percentage($row['profit'],$row['net']).'</b>';
  
 
}
mysql_free_result($result);
foreach($store_data as $key=>$val){
  if($val['store']!=''){
    if($val['currency_code']!=$corporate_currency)
      $store_data[$key]['per_eq_net']='<span class="mix_currency">'.percentage($val['_eq_net'],$sum_net_eq).'</span>';
    else
      $store_data[$key]['per_eq_net']=percentage($val['_eq_net'],$sum_net_eq);
  }else{
    if($val['currency_code']!=$corporate_currency)
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
		   ,'eq_net'=>'<span class="mix_currency">'.money($sum_net_eq).'</span>'
		      ,'eq_tax'=>'<span class="mix_currency">'.money($sum_tax_eq).'</span>'
		      );
	$store_data_profit[]=array(
		      'store'=>_('Total')
		      ,'invoices'=>number($sum_inv)
		      ,'net'=>'<span class="mix_currency">'.money($sum_net_eq).'</span>'
		      ,'tax'=>'<span class="mix_currency">'.money($sum_tax_eq).'</span>'
		   ,'eq_net'=>'<span >'.money($sum_net_eq).'</span>'
		      ,'eq_tax'=>'<span >'.money($sum_tax_eq).'</span>'
		      );	      
		      
		      
}else{
  $store_data[]=array(
		      'store'=>_('Total')
		      ,'invoices'=>number($sum_inv)
		      ,'net'=>money($sum_net_eq)
		      ,'tax'=>money($sum_tax_eq)
		    ,'eq_net'=>money($sum_net_eq)
		      ,'eq_tax'=>money($sum_tax_eq)
		      );
	$store_data_profit[]=array(
		      'store'=>_('Total')
		      
		      ,'net'=>'<span class="mix_currency">'.money($sum_net_eq).'</span>'
		      ,'profit'=>'<span class="mix_currency">'.money($sum_profit_eq).'</span>'
		     ,'eq_profit'=>'<span ><b>'.money($sum_profit_eq).'</b></span>'

		   ,'eq_net'=>'<span ><b>'.money($sum_net_eq).'</b></span>'
		     
		      );	      
}
  











//print_r($store_data);
$smarty->assign('store_data',$store_data);
$smarty->assign('store_data_profit',$store_data_profit);

$plot_tipo=$_SESSION['state']['report_sales']['plot'];
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
$smarty->assign('from',$from);
$smarty->assign('to',$to);
$smarty->assign('currency',$myconf['currency_symbol']);

//if($period!=''){
  $smarty->assign('display_plot',true);
  
  $plot_tipo=$_SESSION['state']['report_sales']['plot'];
  $plot_data=$_SESSION['state']['report_sales']['plot_data'][$plot_tipo];
  $plot_category=$plot_data['category'];
  if($tipo=='y'){
    $plot_period='m';
  }elseif($tipo=='m'){
    $plot_period='w';
  }elseif($tipo=='w'){
    $plot_period='d';
  }elseif($tipo=='d'){
    $plot_period='h';
  }else{
     $plot_period='m';

  }





  $plot_args='tipo=store&category='.$plot_category.'&period='.$plot_period.'&keys='.$store_keys.'&from='.$from.'&to='.$to;


  $smarty->assign('plot_tipo',$plot_tipo);
  $smarty->assign('plot_args',$plot_args);
  $smarty->assign('plot_page',$plot_data['page']);
  $smarty->assign('plot_period',$plot_period);
  $smarty->assign('plot_category',$plot_period);
  $smarty->assign('plot_data',$_SESSION['state']['report_sales']['plot_data']);
//print_r($_SESSION['state']['report_sales']['plot_data']);

  if($plot_period=='m')
    $plot_formated_period='Monthly';
  elseif($plot_period=='y')
    $plot_formated_period='Yearly';
  elseif($plot_period=='q')
    $plot_formated_period='Quarterly';
  elseif($plot_period=='w')
    $plot_formated_period='Weekly';
  elseif($plot_period=='d')
    $plot_formated_period='Daily';
  elseif($plot_period=='h')
    $plot_formated_period='Every Hour';
      
  if($plot_category=='profit')
    $plot_formated_category=_('Profits');
  else
    $plot_formated_category=_('Net Sales');
  $smarty->assign('plot_formated_category',$plot_formated_category);
  $smarty->assign('plot_formated_period',$plot_formated_period);
//}






$plot_category_menu=array(
		     array("category"=>'sales','label'=>_('Net Item Sales'))
		     ,array("category"=>'profit','label'=>_('Profit'))
		     );
$smarty->assign('plot_category_menu',$plot_category_menu);




$smarty->display('report_sales_main.tpl');
?>

