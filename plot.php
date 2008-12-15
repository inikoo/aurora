<?

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
switch($tipo){
 case('product_week_sales'):

 $product=new Product($_SESSION['state']['product']['id']);
   $product_id=$product->id;

   if(isset($_REQUEST['months'])){
     $months=$_REQUEST['months'];
     $_SESSION['state']['product']['plot_data']['months']=$months;

   }else
     $months=$_SESSION['state']['product']['plot_data']['months'];
   

   if(isset($_REQUEST['max_sigma'])){
     $max_sigma=$_REQUEST['max_sigma'];
     $_SESSION['state']['product']['plot_data']['max_sigma']=$max_sigma;
   }else
     $max_sigma=$_SESSION['state']['product']['plot_data']['max_sigma'];
       
   
   if(is_numeric($months) and $months>0)
     $first_day=date("Y-m-d",strtotime("- $months  month"));
   else
     $first_day=$product->get('mysql_first_date');
   

   if($max_sigma)
     $max=4*$product->get('awtsoall');


   $ar_address='ar_assets.php?tipo=plot_product_week_sales&product_id='.$product_id.'&first_day='.$first_day;

   $fields='"tip_asales","asales","date"';
   $yfields=array(array('label'=>_('Sales'),'name'=>'asales','axis'=>'formatCurrencyAxisLabel','style'=>'size:5,lineSize:2'));
   $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category','axis'=>'fdate');
   $style='size:1';
   $tipo_chart='LineChart';
   break;
 case('product_week_outers'):
   
   $product=new Product($_SESSION['state']['product']['id']);
   $product_id=$product->id;

   if(isset($_REQUEST['months'])){
     $months=$_REQUEST['months'];
     $_SESSION['state']['product']['plot_data']['months']=$months;

   }else
     $months=$_SESSION['state']['product']['plot_data']['months'];
   

   if(isset($_REQUEST['max_sigma'])){
     $max_sigma=$_REQUEST['max_sigma'];
     $_SESSION['state']['product']['plot_data']['max_sigma']=$max_sigma;
   }else
     $max_sigma=$_SESSION['state']['product']['plot_data']['max_sigma'];
       
   
   if(is_numeric($months) and $months>0)
     $first_day=date("Y-m-d",strtotime("- $months  month"));
   else
     $first_day=$product->get('mysql_first_date');
   

   if($max_sigma)
     $max=4*$product->get('awtsoall');

   
   $title=_("Outers dispached per Week");
   $ar_address='ar_assets.php?tipo=plot_product_week_outers&product_id='.$product_id.'&first_day='.$first_day;
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

 case('product_quarter_sales'):
   $ar_address='ar_assets.php?tipo=plot_quartersales';
   $fields='"tip","asales","date"';
   $yfields=array(array('label'=>_('Sales'),'name'=>'asales','axis'=>'formatCurrencyAxisLabel','style'=>''));
   $xfield=array('label'=>_('Date'),'name'=>'date');
   $style='size:20,lineSize:1';
 $tipo_chart='ColumnChart';
   break;
 case('product_month_sales'):
   $title=_("Sales per Month");
   $ar_address='ar_assets.php?tipo=plot_monthsales';
   $fields='"asales","date","tip_asales"';
   $yfields=array(array('label'=>_('Sales'),'name'=>'asales','axis'=>'formatCurrencyAxisLabel','style'=>'size:10'));
   $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category','axis'=>'justyears');
   $style='size:5,lineSize:1';
 $tipo_chart='ColumnChart';
    break;
 case('department_month_sales'):
   $title=_("Sales per Month");
   $ar_address='ar_assets.php?tipo=plot_department_monthsales';
   $fields='"asales","date","tip_asales"';
   $yfields=array(array('label'=>_('Sales'),'name'=>'asales','axis'=>'formatCurrencyAxisLabel','style'=>'size:10'));
   $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category','axis'=>'justyears');
   $style='size:5,lineSize:1';
 $tipo_chart='ColumnChart';
    break;


 case('product_year_sales'):
   $ar_address='ar_assets.php?tipo=plot_yearsales';
   $fields='"asales","date","tip"';
   $yfields=array(array('label'=>_('Sales'),'name'=>'asales','axis'=>'formatCurrencyAxisLabel','style'=>''));
   $xfield=array('label'=>_('Date'),'name'=>'date');
   $style='size:10,lineSize:1';
 $tipo_chart='LineChart';
    break;
 case('product_week_outers'):
    $title=_("Outers sold per Week");
   $ar_address='ar_assets.php?tipo=plot_product_week_outers';
   $fields='"tip","out","date"';
   $yfields=array(array('label'=>_('Outers'),'name'=>'out','axis'=>'formatNumberAxisLabel','style'=>''));
   $xfield=array('label'=>_('Date'),'name'=>'date');
   $style='size:4,lineSize:1';
   $tipo_chart='LineChart';
   break;
 case('product_quarter_outers'):
    $title=_("Outers sold per Quarter");
   $ar_address='ar_assets.php?tipo=plot_quarterout';
   $fields='"tip_out","out","date"';
   $yfields=array(array('label'=>_('Outers'),'name'=>'out','axis'=>'formatNumberAxisLabel','style'=>''));
   $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category','axis'=>'fdate');
   $style='size:20,lineSize:1';
 $tipo_chart='ColumnChart';
   break;
 case('product_month_outers'):
   $title=_("Outers sold per Month");
   $ar_address='ar_assets.php?tipo=plot_monthout';
   $fields='"out","date","tip"';
   $yfields=array(array('label'=>_('Outers'),'name'=>'out','axis'=>'formatNumberAxisLabel'));
   $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category','axis'=>'fdate');
   $style='size:10,lineSize:1';
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
   $ar_address='ar_orders.php?tipo=plot_net_diff1y_sales_month';
   $fields='"sales_diff","tip_sales_diff","date"';
   $yfields=array(
		  array('label'=>_('Month Net Sales'),'name'=>'sales_diff','axis'=>'formatCurrencyAxisLabel','style'=>'size:10,color: 0x62a74b')
		  );;
   $xfield=array('label'=>_('Date'),'name'=>'date','tipo_axis'=>'Category','axis'=>'justyears');
   $tipo_chart='ColumnChart';$style='';
   break;
 case('net_diff1y_sales_month_per'):
   $title=_("Monthy net sales change compared with previos year");
   $ar_address='ar_orders.php?tipo=plot_net_diff1y_sales_month';
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
   $ar_address='ar_orders.php?tipo=plot_gmonthsales';
   $fields='"sales2004","sales2005","sales2006","sales2007","sales2008","tip_sales2004","tip_sales2005","tip_sales2006","tip_sales2007","tip_sales2008","date"';
   
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

 default:
   exit;
   

 }
   



$alt=_('Unable to load Flash content. The YUI Charts Control requires Flash Player 9.0.45 or higher. You can download the latest version of Flash Player from the ').'<a href="http://www.adobe.com/go/getflashplayer">Adobe Flash Player Download Center</a>.';
$out='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3c.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" content="text/html; charset=UTF-8"   >
  <head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">





       <script type="text/javascript" src="'.$yui_path.'yahoo-dom-event/yahoo-dom-event.js"></script>
       <script type="text/javascript" src="'.$yui_path.'json/json-min.js"></script>
       <script type="text/javascript" src="'.$yui_path.'element/element-beta-min.js"></script>
       <script type="text/javascript" src="'.$yui_path.'connection/connection-min.js"></script>
       <script type="text/javascript" src="'.$yui_path.'datasource/datasource-min.js"></script>
       <script type="text/javascript" src="'.$yui_path.'charts/charts-experimental-min.js"></script>

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


var mychart = new YAHOO.widget.'.$tipo_chart.'( '.($tipo_chart=='CartesianChart'?"'line',":'').'  "plot", jsonData,

 	{
style:{'.$style.'}          ,
 wmode: "opaque",
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