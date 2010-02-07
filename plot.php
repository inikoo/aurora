<?php

//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
$colors=array(
	      '0x62a74b',
	      '0xc665a7',
	      '0x4dbc9b',
	      '0xe2654f',
	      '0x4c77d1'
	      );

$color_palette=array(
		     array('value'=>'0x00b8bf','forecast'=>'0x8dd5e7')
		     ,array('value'=>'0xc665a7','forecast'=>'0xe8acd5')
		     ,array('value'=>'0x4dbc9b','forecast'=>'0x99edd4')
		     ,array('value'=>'0xe2654f','forecast'=>'0xef9f91')
		     ,array('value'=>'0x4c77d1','forecast'=>'0x97b3ed')
		     );
  

require_once 'common.php';
//print $_SESSION['state']['store']['plot'];
//exit;
require_once 'class.Product.php';

$tipo='';
if(isset($_REQUEST['tipo']))
  $tipo=$_REQUEST['tipo'];
$title='';

$options='';
$staked=false;

$currency_symbol=$myconf['currency_symbol'];
if(isset($_REQUEST['currency']))
  $currency_symbol=currency_symbol($_REQUEST['currency']);

switch($tipo){

case('sales_by_store');
case('sales_share_by_store');
plot_sales_by_store($tipo);



break;

break;
case('customers');
case('active_customers');

$_SESSION['state']['customers']['plot']=$tipo;


  if(isset($_REQUEST['period'])){
    $period=$_REQUEST['period'];
    $_SESSION['state']['customers']['plot_data'][$tipo]['period']=$period;

    }else
    $period=$_SESSION['state']['customers']['plot_data'][$tipo]['period'];
    
if(isset($_REQUEST['category'])){
  $category=$_REQUEST['category'];
  $_SESSION['state']['customers']['plot_data'][$tipo]['category']=$category;

  }
$category=$_SESSION['state']['customers']['plot_data'][$tipo]['category'];
  
  
 // print_r($tipo);
  
$ar_address='ar_plot.php?tipo='.$tipo.'&period='.$period.'&category='.$category;
//print $ar_address;


// $fields='"tip_lost","lost","date","new","tip_new","active","tip_active"';

$xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category','axis'=>'fdate');
$style='size:1';
if($category=='total'){
$yfields=array(array('label'=>_('Active'),'name'=>'active','axis'=>'formatNumberAxisLabel','style'=>'size:5,lineSize:2'));
$tipo_chart='LineChart';
$fields='"date","active","tip_active"';

}else{
$yfields=array(array('label'=>_('New Customers'),'name'=>'new','axis'=>'formatNumberAxisLabel','style'=>'size:5,lineSize:2'));
$fields='"date","new","tip_new"';

$tipo_chart='ColumnChart';
}
render_flash_plot();


break;
case('customer_month_growth');
$staked=true;
  $ar_address='ar_plot.php?tipo='.$tipo;
$fields='"date","diff","tip_diff","new","tip_new","lost","lost_tip"';
  // $fields='"tip_lost","lost","date","new","tip_new","active","tip_active"';
   $yfields=array(
		  

		  array('label'=>_('New'),'name'=>'new','axis'=>'formatNumberAxisLabel','style'=>'color:0x7076f4,alpha:0.2')
		  ,array('label'=>_('Lost'),'name'=>'lost','axis'=>'formatNumberAxisLabel','style'=>'color:0x7076f4,alpha:0.2')

		  ,array('label'=>_('Change'),'name'=>'diff','type'=>'line','axis'=>'formatNumberAxisLabel','style'=>'size:5,lineSize:3,color:0x3390e7')
		  //  array('label'=>_('New'),'name'=>'new','axis'=>'formatNumberAxisLabel','style'=>'size:5,lineSize:2'),
		  // array('label'=>_('Lost'),'name'=>'lost','axis'=>'formatNumberAxisLabel','style'=>'size:5,lineSize:2')

		  );
   $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category','axis'=>'fdate');
   $style='size:1';
   $tipo_chart='ColumnChart';

break;
 case('product_week_sales'):
 case('product_month_sales'):
 case('product_quarter_sales'):
 case('product_year_sales'):
   
   if($tipo=='product_week_sales'){
     $interval='week';
     $size=5;
   }
   elseif($tipo=='product_month_sales'){
     $interval='month';
      $size=10;
   }
   elseif($tipo=='product_quarter_sales'){
     $interval='quarter';
     $size=20;
   }
   elseif($tipo=='product_year_sales'){
     $interval='year';
     $size=40;
   }

   $_SESSION['state']['product']['plot']=$tipo;
   $mode=$_SESSION['state']['product']['mode'];
   $tag=$_SESSION['state']['product']['tag'];
   $product=new Product($mode,$tag);
   if(isset($_REQUEST['months'])){
     $months=$_REQUEST['months'];
     $_SESSION['state']['product']['plot_data'][$interval]['months']=$months;
     
   }else
     $months=$_SESSION['state']['product']['plot_data'][$interval]['months'];
   
   
   if(isset($_REQUEST['max_sigma'])){
     $max_sigma=$_REQUEST['max_sigma'];
     $_SESSION['state']['product']['plot_data'][$interval]['max_sigma']=$max_sigma;
   }else
     $max_sigma=$_SESSION['state']['product']['plot_data'][$interval]['max_sigma'];
   
   
   if(is_numeric($months) and $months>0)
     $first_day=date("Y-m-d",strtotime("- $months  month"));
   else{
     if($mode=='code')
       $first_day=$product->get('Product Same Code Valid From');
     elseif($mode=='id')
       $first_day=$product->get('Product Same ID Valid From');
     elseif($mode=='key')
       $first_day=$product->get('Product Valid From');
   }
   $_SESSION['state']['product']['plot_data']['week']['first_day']=$first_day;

   if($max_sigma){
      if($mode=='code')
	$max=4*$product->get('Product Same Code 1 Year Acc Invoiced Amount')/52;
      elseif($mode=='id')
	$max=4*$product->get('Product Same ID 1 Year Acc Invoiced Amount')/52;
      elseif($mode=='key')
	$max=4*$product->get('Product 1 Year Acc Invoiced Amount')/52;

   }
   //   $_SESSION['state']['product']['plot_data'][$tipo]['max']=$max;
   
   $ar_address='ar_plot.php?tipo='.$tipo;

   $fields='"tip_asales","asales","date","profit","tip_profit"';
   $yfields=array(
		  array('label'=>_('Sales'),'name'=>'asales','axis'=>'formatCurrencyAxisLabel','style'=>"size:$size,lineSize:2"),
		  array('label'=>_('Profit'),'name'=>'profit','axis'=>'formatCurrencyAxisLabel','style'=>"size:$size,lineSize:2")
		  );
   $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category','axis'=>'fdate');
   $style='size:1';
   $tipo_chart='ColumnChart';
   break;
 case('product_week_outers'):
 case('product_month_outers'):
 case('product_quarter_outers'):
 case('product_year_outers'):
 $_SESSION['state']['product']['plot']=$tipo;
   if($tipo=='product_week_outers')
     $interval='week';
   elseif($tipo=='product_month_outers')
     $interval='month';
   elseif($tipo=='product_quarter_outers')
     $interval='quarter';
   elseif($tipo=='product_year_outers')
     $interval='year';

   
   $mode=$_SESSION['state']['product']['mode'];
   $tag=$_SESSION['state']['product']['tag'];
   $product=new Product($mode,$tag);

   $product_id=$product->id;
   
   if(isset($_REQUEST['months'])){
     $months=$_REQUEST['months'];
     $_SESSION['state']['product']['plot_data'][$interval]['months']=$months;

   }else
     $months=$_SESSION['state']['product']['plot_data'][$interval]['months'];
   
    if(isset($_REQUEST['max_sigma'])){
     $max_sigma=$_REQUEST['max_sigma'];
     $_SESSION['state']['product']['plot_data'][$interval]['max_sigma']=$max_sigma;
   }else
     $max_sigma=$_SESSION['state']['product']['plot_data'][$interval]['max_sigma'];

   if($max_sigma){
      if($mode=='code')
	$max=4*$product->get('Product Same Code 1 Year Acc Quantity Ordered')/52;
      elseif($mode=='id')
	$max=4*$product->get('Product Same ID 1 Year Acc Quantity Ordered')/52;
      elseif($mode=='key')
	$max=4*$product->get('Product 1 Year Acc Quantity Ordered')/52;

   }
   //   $_SESSION['state']['product']['plot_data'][$tipo]['max']=$max;
       
   
   if(is_numeric($months) and $months>0)
     $first_day=date("Y-m-d",strtotime("- $months  month"));
   else
     $first_day=$product->get('mysql_first_date');
   $_SESSION['state']['product']['plot_data'][$interval]['first_day']=$first_day;

   
   $title=_("Outers dispached per Week");
   $ar_address='ar_plot.php?tipo='.$tipo;
   $fields='"tip_out","out","date","bonus","tip_bonus"';
   $yfields=array(
		  array('label'=>_('Sold Outers'),'name'=>'out','style'=>'size:5,lineSize:2','type'=>'line'),
		  array('label'=>_('Bonus Outers'),'name'=>'bonus','style'=>'size:5,lineSize:2')

		  );

   $yfield_label_type='formatCurrencyAxisLabel';
   $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category','axis'=>'fdate');
   $style='size:10';
   $tipo_chart='ColumnChart';
   break;
 case('product_month_year'):
   $ar_address='ar_assets.php?tipo=plot_yearout';
   $fields='"out","date","tip"';
   $yfields=array(array('label'=>_('Outers'),'name'=>'out','axis'=>'formatNumberAxisLabel','style'=>''));
   $xfield=array('label'=>_('Date'),'name'=>'date');
   $style='size:10,lineSize:1';
 $tipo_chart='LineChart';
    break;
 case('product_stock_history'):
   $ar_address='ar_assets.php?tipo=plot_daystock';
   $fields='"stock","day","tip"';
		  $yfields=array(array('label'=>_('Outers'),'name'=>'stock','style'=>''));
   $xfield=array('label'=>_('Date'),'name'=>'day');
   $style='size:5,lineSize:1';
 $tipo_chart='CartesianChart';
    break;

case('top_departments_sales_month'):

  $store_key_array=array();
  $store_keys='';

  if(isset($_REQUEST['store_keys'])){
    if(preg_match('/\(.+\)/',$_REQUEST['store_keys'],$keys)){
      $keys=preg_replace('/\(|\)/','',$keys[0]);
      $keys=preg_split('/\s*,\s*/',$keys);
      $store_keys='(';
      foreach($keys as $key){
	if(is_numeric($key)){
	  $store_keys.=sprintf("%d,",$key);
	  $store_key_array[]=$key;
	}
      }
      $store_keys=preg_replace('/,$/',')',$store_keys);
    }elseif(preg_match('/^\d+$/',$_REQUEST['store_keys'])){
      $store_keys="(".$_REQUEST['store_keys'].")";
      $store_key_array[]=$_REQUEST['store_keys'];
    }
    if(count($store_key_array)==0){
      return;
    }
  }
  if($store_keys=='')
    $where='';
  else
    $where=' where `Product Department Store Key` in '.$store_keys;
  $order='`Product Department 1 Year Acc Invoiced Amount`';
  $sql=sprintf("select `Product Department Code`,`Product Department Key` from `Product Department Dimension` %s order by %s desc limit 3"
	       ,$where
	       ,$order
	       );
  $res=mysql_query($sql);
  $departments_keys='(';
  $deparment_key_array=array();
  while($row=mysql_fetch_array($res)){
    $departments_keys.=$row['Product Department Key'].',';
    $deparment_key_array[$row['Product Department Key']]=$row['Product Department Code'];
  }
  mysql_free_result($res);
  $departments_keys=preg_replace('/,$/',')',$departments_keys);
  //$departments_keys='(1)';
  $title=_("Store Net Sales per Month");
  $ar_address=sprintf('ar_plot.php?tipo=invoiced_department_month_sales&split=yes&department_keys=%s',$departments_keys);
  //print $ar_address;;
  $fields='"date"';

  foreach($deparment_key_array as $key=>$value){
    $fields.=',"value'.$key.'","tip_value'.$key.'","forecast'.$key.'","tip_forecast'.$key.'","tails'.$key.'","tip_tails'.$key.'"';
  }
  $yfields=array();
  $count=0;
  foreach($deparment_key_array as $key=>$value){

    $forecast_color=$color_palette[$count]['forecast'];
    $value_color=$color_palette[$count]['value'];

    $yfields[]=array('label'=>_('Forecast')." ($value)",'name'=>'forecast'.$key,'style'=>'color:'.$forecast_color.',alpha:.7');
    $yfields[]=array('label'=>_('Tails')." ($value)",'name'=>'tails'.$key,'style'=>'color:'.$value_color.',fillColor:0xffffff,alpha:.7');
    $yfields[]=array('label'=>_('Sales')." ($value)",'name'=>'value'.$key,'style'=>'color:'.$value_color.',alpha:.7');
    $count++;
  }		 
		 
  $yfield_label_type='formatCurrencyAxisLabel';
  
  $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category','axis'=>'justyears');
  $style='';
  $tipo_chart='LineChart';
   break;


case('store'):
case('department'):
case('family'):
case('product'):


plot_assets();
render_flash_plot();
    break;

    
    
case('total_sales_month'):
  $title=_("Total Net Sales per Month");
  $ar_address='ar_plot.php?tipo=invoiced_month_sales';
  $fields='"value","tip_value","date","forecast","tip_forecast","tails","tip_tails"';
  $yfields=array(
		 array('label'=>_('Forecast'),'name'=>'forecast','style'=>'color:0x8dd5e7') 
		 , array('label'=>_('Tails'),'name'=>'tails','style'=>'color:0x00b8bf,fillColor:0xffffff') 
		 ,array('label'=>_('Month Net Sales'),'name'=>'value','style'=>'color:0x00b8bf')
        
		 );;
  $yfield_label_type='formatCurrencyAxisLabel';

   $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category','axis'=>'justyears');
   $style='';
   $tipo_chart='LineChart';
   break;
 case('net_diff1y_sales_month'):
   
   $title=_("Monthy net sales change compared with previous year");
   $ar_address='ar_plot.php?tipo=net_diff1y_sales_month';
   $fields='"sales_diff","tip_sales_diff","date"';
   $yfields=array(
		  array('label'=>_('Month Net Sales'),'name'=>'sales_diff','axis'=>'formatCurrencyAxisLabel','style'=>'size:10,color: 0x62a74b')
		  );
   $yfield_label_type='formatCurrencyAxisLabel';
   $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category','axis'=>'justyears');
   $tipo_chart='ColumnChart';$style='';
   break;
 case('net_diff1y_sales_month_per'):
   $title=_("Monthy net sales change compared with previos year");
   $ar_address='ar_plot.php?tipo=net_diff1y_sales_month';
   $fields='"sales_diff_per","tip_sales_diff_per","date"';
   $yfields=array(
		  array('label'=>_('Month Net Sales'),'name'=>'sales_diff_per','style'=>'size:10,color: 0x62a74b')
		  );;
   
   $yfield_label_type='formatPercentageAxisLabel';
   $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category','axis'=>'justyears');
   $tipo_chart='ColumnChart';$style='';
   break;
 case('total_sales_week'):
   $title=_("Total Net Sales per Week");
   $ar_address='ar_plot.php?tipo=invoiced_week_sales';
   $fields='"sales","tip_sales","date"';
   $yfields=array(array('label'=>_('Week Net Sales'),'name'=>'sales','axis'=>'formatCurrencyAxisLabel','style'=>'size:3'));;
   $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category');
   $style='';
   $tipo_chart='LineChart';
   break;

 case('total_sales_groupby_month'):
   

   $title=_("Total Net Sales per Month (Group by month)");
   $ar_address='ar_plot.php?tipo=montly_sales_group_by_month';
   $fields='"date"';
    for($i=date('Y');$i>=date('Y')-5;$i--){
      $fields.=",'sales$i','tip_sales$i'";
    }
   $_year=date('Y');
   $_years=5;
   if($_years>5)
     $_years=5;
   
   

   while($_years>0) {
     $_years--;
     $year=$_year-$_years;
     $yfields[]=array('label'=>$year
		      ,'name'=>'sales'.$year
		      ,'axis'=>'formatCurrencyAxisLabel','style'=>"size:10,color:".$colors[$_years].",alpha:.9");

   }


  //  $yfields=array(
		  
// 		  array('label'=>_('2004'),'name'=>'sales2004','axis'=>'formatCurrencyAxisLabel','style'=>"size:10,color: 0x62a74b,alpha:.9"),
// 		  array('label'=>_('2005'),'name'=>'sales2005','axis'=>'formatCurrencyAxisLabel','style'=>"size:10, color: 0xc665a7,alpha:.9"),
// 		  array('label'=>_('2006'),'name'=>'sales2006','axis'=>'formatCurrencyAxisLabel','style'=>"size:10, color: 0x4dbc9b,alpha:.9"),
// 		  array('label'=>_('2007'),'name'=>'sales2007','axis'=>'formatCurrencyAxisLabel','style'=>"size:10,color: 0xe2654f,alpha:.9"),
// 		  array('label'=>_('2008'),'name'=>'sales2008','axis'=>'formatCurrencyAxisLabel','style'=>"size:10,color: 0x4c77d1,alpha:.9")

// 		  );

   $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category');
   $style='legend:{display: "bottom"}';
   $tipo_chart='ColumnChart';
   break;
 case('total_outofstock_month'):
   $title="Percentage of products (& Picks) marked as out of stock per month";
   $ar_address='ar_orders.php?tipo=plot_month_outofstock';
   $fields='"per_product_outstock","per_picks_outstock","tip_per_product_outstock","tip_per_picks_outstock","date"';
   $yfields=array(
		  
		  array('label'=>_('Products Out of Stock'),'name'=>'per_product_outstock','axis'=>'formatPercentageAxisLabel'),
		  array('label'=>_('Picks Out of Stock'),'name'=>'per_picks_outstock','axis'=>'formatPercentageAxisLabel'),


		  );

   $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category','xaxis'=>'justyears');
   $style='minorGridLines:{size:5,color: 0x4c77d1},  legend:{display: "bottom"}';
   $tipo_chart='LineChart';
   break;
 case('part_stock_history'):
   $title="Part Stock History";
   $ar_address='ar_assets.php?tipo=plot_daily_part_stock_history';
   if(isset($_REQUEST['sku']) and is_numeric($_REQUEST['sku']))
     $ar_address.='&sku='.$_REQUEST['sku'];
   $fields='"stock","tip_stock","tip_sales","sales","date"';
   $yfields=array(
		  
		  array('label'=>_('Stock'),'name'=>'stock','axis'=>'formatNumberAxisLabel','style'=>'size:3,lineSize:2'),



		  );


$options='yAxis.minimum = 0;';

   $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category');
   $style='minorGridLines:{size:5,color: 0x4c77d1}';
   $tipo_chart='LineChart';
   break;
 default:
   exit;
   

 }
   

function render_flash_plot(){
  global $yui_path,$currency_symbol,$title,$fields,$yfields,$xfield,$ar_address,$options,$staked,$tipo_chart,$style,$yfield_label_type;

$alt=_('Unable to load Flash content. The YUI Charts Control requires Flash Player 9.0.45 or higher. You can download the latest version of Flash Player from the ').'<a href="http://www.adobe.com/go/getflashplayer">Adobe Flash Player Download Center</a>.';
$out='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3c.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" content="text/html; charset=UTF-8"   >
  <head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">




 <script type="text/javascript" src="'.$yui_path.'utilities/utilities.js"></script>
       <script type="text/javascript" src="'.$yui_path.'json/json-min.js"></script>
       <script type="text/javascript" src="'.$yui_path.'datasource/datasource-min.js"></script>
       <script type="text/javascript" src="'.$yui_path.'charts/charts-min.js"></script>

</head> <body><div style="font-size:8pt;height:300px" id=plot>'.$alt.'</div><div style="font-family:Verdana, Arial, sans-serif;text-align:center;font-size:10pt;position:relative;bottom:300px;">'.$title.'</div></body>
 <script type="text/javascript">


 function formatCurrencyAxisLabel( value ){
if( value==0)
    return "";
else if ( value>=499){
return YAHOO.util.Number.format( value/1000,{prefix: "'.$currency_symbol.'",thousandsSeparator: ",",decimalPlaces: 1})+"K";
}
else if ( value<=-10000){
return YAHOO.util.Number.format( value/1000,{prefix: "'.$currency_symbol.'",thousandsSeparator: ",",decimalPlaces: 0})+"K";
}
else
return YAHOO.util.Number.format( value,{prefix: "'.$currency_symbol.'",thousandsSeparator: ",",decimalPlaces: 2});
}

 function formatPercentageAxisLabel( value ){
return value+"%";
}
 function formatPercentageAxisLabelx2BUG( value ){
return 2*value+"%";
}




 function formatNumberAxisLabel( value ){return YAHOO.util.Number.format( value,{prefix: "",thousandsSeparator: ",",decimalPlaces: 0});}
 function formatPercentageAxisLabel( value ){return YAHOO.util.Number.format( value,{prefix: "",thousandsSeparator: ",",decimalPlaces: 0})+"%";}

 function DataTipText( item, index, series ){return item["tip_"+series["yField"]]    }

 YAHOO.widget.Chart.SWFURL = "'.$yui_path.'charts/assets/charts.swf";
 	
var jsonData = new YAHOO.util.DataSource( "'.$ar_address.'" );
 	jsonData.connMethodPost = true;
 	jsonData.responseType = YAHOO.util.DataSource.TYPE_JSON;
 	jsonData.responseSchema =
 	{
 			resultsList: "resultset.data",
 			fields: ['.$fields.']
 	};

 var seriesDef = ['."\n";
$i=0;

foreach($yfields as $yfield){

  if(isset($yfield['type']))
    $type='type:"'.$yfield['type'].'",';
  else
    $type='';
  $out.=($i>0?',':'').'{  '.$type.'  displayName: "'.$yfield['label'].'",  yField: "'.$yfield['name'].'" '.(isset($yfield['style'])?',style:{'.$yfield['style'].'}':'').'}'."\n";
  $i++;
}
$out.='];'."\n".'var yAxis = new YAHOO.widget.NumericAxis();';

if(isset($yfield_label_type))
    $out.='yAxis.labelFunction = "'.$yfield_label_type.'";';

else
  $out.='yAxis.labelFunction = "formatNumberAxisLabel";';


if(isset($max))
  $out.='yAxis.maximum = '.$max.';'; 
$out.='

function fdate(value){
return value.replace(/^\d*x/g,"");
}

function justyears(value){
var isjanuary= /^01/;
if(isjanuary.test(value))
value=value.match(/\d{2}$/g)[0]
else
value=""
return value;
}

var xAxis = new YAHOO.widget.'.$xfield['tipo_axis'].'Axis();

'.(isset($xfield['axis'])?'xAxis.labelFunction = "'.$xfield['axis'].'";':'').'

'.$options.'


var styleDef={xAxis:{labelRotation:-90,labelSpacing:0 }};

'.($staked?'yAxis.stackingEnabled = true':'').'

var mychart = new YAHOO.widget.'.($staked?'Stacked':'').$tipo_chart.'( '.($tipo_chart=='CartesianChart'?"'line',":'').'  "plot", jsonData,

 	{
style:{'.$style.'}          ,
 wmode: "transparent",
          series: seriesDef,
 	 xField: "'.$xfield['name'].'",
 	 yAxis: yAxis,
	 xAxis: xAxis,
         dataTipFunction: "DataTipText",
 	 style:styleDef,
         expressInstall: "assets/expressinstall.swf",
         polling: 2000 
 	});


 </script>
 </html>';

 print $out;
}


function plot_sales_by_store($tipo){

$_tipo=$tipo;
$staked=true;

$dtipo='y';
if(isset($_REQUEST['dtipo']))
  $dtipo=$_REQUEST['dtipo'];

$extra='';
if($dtipo=='y'){
  $y=date('Y');
  if(isset($_REQUEST['y']))
  $y=$_REQUEST['y'];
  $extra='&y='.$y;
}elseif($dtipo=='m'){
  $y=date('Y');
  $m=date('m');

  if(isset($_REQUEST['y']))
    $y=$_REQUEST['y'];
  if(isset($_REQUEST['m']))
    $m=$_REQUEST['m'];
  
  $extra='&y='.$y.'&m='.$m;
}



$ar_address='ar_plot.php?tipo='.$tipo.'&dtipo='.$dtipo.$extra;
//print $ar_address;
$tipo=$_REQUEST['dtipo'];
include_once('report_dates.php');
$int=prepare_mysql_dates($from,$to,'`Invoice Date`','date start end');

$sql=sprintf("select CONCAT(`Store Code`,'',`Invoice Category`) as tag from `Invoice Dimension`  left join `Store Dimension` S on (S.`Store Key`=`Invoice Store Key`) where true %s group by `Invoice Store Key`,`Invoice Category`",$int[0]);

$fields='"date"';
 $yfields=array();
 $result=mysql_query($sql);
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    

    if($_tipo=='sales_by_store'){
      $fields.=',"'.$row['tag'].'","tip_'.$row['tag'].'"';
      $yfields[]=array('label'=>$row['tag'],'name'=>$row['tag']);
      $yfield_label_type='formatCurrencyAxisLabel';
    }else{
      $fields.=',"share_bug_'.$row['tag'].'","share_'.$row['tag'].'","tip_share_bug_'.$row['tag'].'"';
      $yfields[]=array('label'=>"share_bug_".$row['tag'],'name'=>"share_bug_".$row['tag']);
      $yfield_label_type='formatPercentageAxisLabelx2BUG';

    }
    
    

  }
mysql_free_result($result);
   $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category');
   $style='size:1';
   $tipo_chart='ColumnChart';
}

function plot_assets(){
  global $color_palette,$yfields,$fields,$yfield_label_type,$tipo_chart,$xfield,$ar_address;
   if(isset($_REQUEST['from']))
    $from=$_REQUEST['from'];
  else
    $from=false;
  if(isset($_REQUEST['to']))
    $to=$_REQUEST['to'];
  else
    $to=false;



  if(isset($_REQUEST['period']))
    $period=$_REQUEST['period'];
  else
    $period='m';

 if(isset($_REQUEST['tipo']))
    $tipo=$_REQUEST['tipo'];
  else
    $tipo='store';

 if(isset($_REQUEST['category']))
    $category=$_REQUEST['category'];
  else
    $category='sales';
 
 
/*    if(preg_match('/^month|m$/',$period)){ */
/*     $period='month'; */
/*    }elseif(preg_match('/year$/',$tipo)){ */
/*     $sub_tipo='year'; */
/*    }elseif(preg_match('/quarter$/',$tipo)){ */
/*     $sub_tipo='quarter'; */
/*    }elseif(preg_match('/week$/',$tipo)){ */
/*     $sub_tipo='week'; */
/*    } */
 $request_keys=$_REQUEST['keys'];
 
  if(preg_match('/store/',$tipo)){
    $tipo='store';
    $plot_name='store';
    $plot_page='store';
    

  }elseif(preg_match('/department/',$tipo)){
    $tipo='department';
    $plot_name='department';
    $plot_page='department';
  }elseif(preg_match('/family/',$tipo)){
    $tipo='family';
    $plot_name='family';
    $plot_page='family';
  }elseif(preg_match('/product/',$tipo)){
    $tipo='product';
    $plot_name='product';
    $plot_page='product';
  }
  $item_keys='';
  $item_key_array=array();
  if($request_keys=='all' and $tipo=='store'){
   
    $sql="select `Store Key` from `Store Dimension` limit 10 ";
    $res=mysql_query($sql);
    while($row=mysql_fetch_array($res)){
      $key=$row['Store Key'];
      $item_keys.=sprintf("%d,",$key);
      $item_key_array[]=$key;
      }
    mysql_free_result($res);
    

  }elseif(preg_match('/\(.+\)/',$request_keys,$keys)){
    $keys=preg_replace('/\(|\)/','',$keys[0]);
    $keys=preg_split('/\s*,\s*/',$keys);
    $item_keys='(';
    foreach($keys as $key){
      if(is_numeric($key)){
	$item_keys.=sprintf("%d,",$key);
	$item_key_array[]=$key;
      }
    }
    $item_keys=preg_replace('/,$/',')',$item_keys);
  }elseif(preg_match('/^\d+$/',$request_keys)){
    $item_keys="(".$request_keys.")";
    $item_key_array[]=$request_keys;
  }
  if(count($item_key_array)==0){
    return;
  }
  

  if(isset($_REQUEST['top_children']) and  is_numeric($_REQUEST['top_children'])){     
    if($tipo=='store'){
      $plot_name='top_departments';
      $tipo='department';
      $where=' where `Product Department Store Key` in '.$item_keys;
      $order='`Product Department 1 Year Acc Invoiced Amount`';
      $sql=sprintf("select `Product Department Code`,`Product Department Key` from `Product Department Dimension` %s order by %s desc limit %d"
		   ,$where
		   ,$order
		   ,$_REQUEST['top_children']
		   );
      $res=mysql_query($sql);
      $item_keys='(';
      $item_key_array=array();
      while($row=mysql_fetch_array($res)){
	$item_keys.=$row['Product Department Key'].',';
	$item_key_array[$row['Product Department Key']]=$row['Product Department Key'];
      }
      $item_keys=preg_replace("/,$/",')',$item_keys);
    }elseif($tipo=='department'){
      $plot_name='top_families';
      $tipo='family';
      $where=' where `Product Family Main Department Key` in '.$item_keys;
      $order='`Product Family 1 Year Acc Invoiced Amount`';
      $sql=sprintf("select `Product Family Code`,`Product Family Key` from `Product Family Dimension` %s order by %s desc limit %d"
		   ,$where
		   ,$order
		   ,$_REQUEST['top_children']
		   );
      $res=mysql_query($sql);
      $item_keys='(';
      $item_key_array=array();
      while($row=mysql_fetch_array($res)){
	$item_keys.=$row['Product Family Key'].',';
	$item_key_array[$row['Product Family Key']]=$row['Product Family Key'];
      }
      $item_keys=preg_replace("/,$/",')',$item_keys);
    }

  }


  $_SESSION['state'][$plot_page]['plot']=$plot_name;
  $_SESSION['state'][$plot_page]['plot_data'][$plot_name]['period']=$period;
  $_SESSION['state'][$plot_page]['plot_data'][$plot_name]['category']=$category;
  // print "$plot_page $plot_name $category";

  $title='';
  $ar_address=sprintf('ar_plot.php?tipo=general&item=%s&category=%s&period=%s&split=yes&item_keys=%s&from=%s&to=%s'
		      ,$tipo
		      ,$category
		      ,$period
		      ,$item_keys
		      ,$from
		      ,$to
		      );
  
  
 // print $ar_address;
  $fields='"date"';
    foreach($item_key_array as $key){
      $fields.=',"value'.$key.'","tip_value'.$key.'","forecast'.$key.'","tip_forecast'.$key.'","tails'.$key.'","tip_tails'.$key.'"';
    }
    $yfields=array();
    $count=0;
    foreach($item_key_array as $key){
      $forecast_color=$color_palette[$count]['forecast'];
    $value_color=$color_palette[$count]['value'];
      $yfields[]=array('label'=>_('Forecast')." ($key)",'name'=>'forecast'.$key,'style'=>'color:'.$forecast_color.',alpha:.7');
      $yfields[]=array('label'=>_('Tails')." ($key)",'name'=>'tails'.$key,'style'=>'color:'.$value_color.',fillColor:0xffffff,alpha:.7');
      $yfields[]=array('label'=>_('Month Net Sales')." ($key)",'name'=>'value'.$key,'style'=>'color:'.$value_color.',alpha:.7');
       $count++;
    }		 
    
    $yfield_label_type='formatCurrencyAxisLabel';
    
    $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category','axis'=>'justyears');
    $style='';
    $tipo_chart='LineChart';



   

}

?>