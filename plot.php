<?
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
$colors=array(
	      '0x62a74b',
	      '0xc665a7',
	      '0x4dbc9b',
	      '0xe2654f',
	      '0x4c77d1'
	      );

require_once 'common.php';
require_once 'classes/Product.php';

$tipo='';
if(isset($_REQUEST['tipo']))
  $tipo=$_REQUEST['tipo'];
$title='';

$options='';


switch($tipo){
case('customer_month_population');

  $ar_address='ar_plot.php?tipo='.$tipo;

   $fields='"tip_lost","lost","date","new","tip_new","active","tip_active"';
   $yfields=array(
		  array('label'=>_('Active'),'name'=>'active','axis'=>'formatNumberAxisLabel','style'=>'size:5,lineSize:2'),
		  array('label'=>_('New'),'name'=>'new','axis'=>'formatNumberAxisLabel','style'=>'size:5,lineSize:2'),
		  array('label'=>_('Lost'),'name'=>'lost','axis'=>'formatNumberAxisLabel','style'=>'size:5,lineSize:2')

		  );
   $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category','axis'=>'fdate');
   $style='size:1';
   $tipo_chart='LineChart';
break;
 case('product_week_sales'):
 case('product_month_sales'):
 case('product_quarter_sales'):
 case('product_year_sales'):
   
   if($tipo=='product_week_sales')
     $interval='week';
   elseif($tipo=='product_month_sales')
     $interval='month';
   elseif($tipo=='product_quarter_sales')
     $interval='quarter';
   elseif($tipo=='product_year_sales')
     $interval='year';

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
		  array('label'=>_('Sales'),'name'=>'asales','axis'=>'formatCurrencyAxisLabel','style'=>'size:5,lineSize:2'),
		  array('label'=>_('Profit'),'name'=>'profit','axis'=>'formatCurrencyAxisLabel','style'=>'size:5,lineSize:2')
		  );
   $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category','axis'=>'fdate');
   $style='size:1';
   $tipo_chart='LineChart';
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
   // print_r($_REQUEST);
   // print $ar_address;
   $fields='"tip_out","out","date","bonus","tip_bonus"';
   $yfields=array(
		  array('label'=>_('Sold Outers'),'name'=>'out','axis'=>'formatNumberAxisLabel','style'=>'size:5,lineSize:2','type'=>'line'),
		  array('label'=>_('Bonus Outers'),'name'=>'bonus','axis'=>'formatNumberAxisLabel','style'=>'size:5,lineSize:2')

		  );
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
		  $yfields=array(array('label'=>_('Outers'),'name'=>'stock','axis'=>'formatNumberAxisLabel','style'=>''));
   $xfield=array('label'=>_('Date'),'name'=>'day');
   $style='size:5,lineSize:1';
 $tipo_chart='CartesianChart';
    break;
 case('total_sales_month'):
   $title=_("Total Net Sales per Month");
   $ar_address='ar_orders.php?tipo=plot_monthsales';
   $fields='"sales","tip_sales","date"';
   $yfields=array(array('label'=>_('Month Net Sales'),'name'=>'sales','axis'=>'formatCurrencyAxisLabel','style'=>''));;
   $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category','axis'=>'justyears');
   $style='';
   $tipo_chart='LineChart';
   break;
 case('net_diff1y_sales_month'):
   
   $title=_("Monthy net sales change compared with previos year");
   $ar_address='ar_plot.php?tipo=net_diff1y_sales_month';
   $fields='"sales_diff","tip_sales_diff","date"';
   $yfields=array(
		  array('label'=>_('Month Net Sales'),'name'=>'sales_diff','axis'=>'formatCurrencyAxisLabel','style'=>'size:10,color: 0x62a74b')
		  );;
   $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category','axis'=>'justyears');
   $tipo_chart='ColumnChart';$style='';
   break;
 case('net_diff1y_sales_month_per'):
   $title=_("Monthy net sales change compared with previos year");
   $ar_address='ar_plot.php?tipo=net_diff1y_sales_month';
   $fields='"sales_diff_per","tip_sales_diff_per","date"';
   $yfields=array(
		  array('label'=>_('Month Net Sales'),'name'=>'sales_diff_per','axis'=>'formatPercentageAxisLabel','style'=>'size:10,color: 0x62a74b')
		  );;
   $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category','axis'=>'justyears');
   $tipo_chart='ColumnChart';$style='';
   break;
 case('total_sales_week'):
   $title=_("Total Net Sales per Week");
   $ar_address='ar_orders.php?tipo=plot_weeksales';
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
else if ( value>=10000){
return YAHOO.util.Number.format( value/1000,{prefix: "'.$myconf['currency_symbol'].'",thousandsSeparator: ",",decimalPlaces: 0})+"K";
}
else if ( value<=-10000){
return YAHOO.util.Number.format( value/1000,{prefix: "'.$myconf['currency_symbol'].'",thousandsSeparator: ",",decimalPlaces: 0})+"K";
}
else
return YAHOO.util.Number.format( value,{prefix: "'.$myconf['currency_symbol'].'",thousandsSeparator: ",",decimalPlaces: 2});
}

 function formatPercentageAxisLabel( value ){
return value+"%";
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
$out.='];'."\n".'var yAxis = new YAHOO.widget.NumericAxis();
yAxis.labelFunction = "'.$yfield['axis'].'";

';
if(isset($max))
  $out.='yAxis.maximum = '.$max.';'; 
$out.='

function fdate(value){
return value.replace(/^\d*x/g,"");
}

function justyears(value){
var isjune= /^06/;
if(isjune.test(value))
value=value.match(/\d{2}$/g)[0]
else
value=""
return value;
}

var xAxis = new YAHOO.widget.'.$xfield['tipo_axis'].'Axis();

'.(isset($xfield['axis'])?'xAxis.labelFunction = "'.$xfield['axis'].'";':'').'

'.$options.'

var mychart = new YAHOO.widget.'.$tipo_chart.'( '.($tipo_chart=='CartesianChart'?"'line',":'').'  "plot", jsonData,

 	{
style:{'.$style.'}          ,
 wmode: "transparent",
          series: seriesDef,
 	 xField: "'.$xfield['name'].'",
 	 yAxis: yAxis,
	 xAxis: xAxis,
dataTipFunction: "DataTipText",
 	 expressInstall: "assets/expressinstall.swf"
 	});


 </script>
 </html>';

 print $out;






?>