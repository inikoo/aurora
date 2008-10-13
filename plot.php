<?
require_once 'common.php';
$tipo='';
if(isset($_REQUEST['tipo']))
  $tipo=$_REQUEST['tipo'];
$title='';
switch($tipo){
 case('sales_week'):
   $ar_address='ar_assets.php?tipo=plot_weeksales';
   $fields='"tip","asales","date"';
   $yfields=array(array('label'=>_('Sales'),'name'=>'asales','axis'=>'formatCurrencyAxisLabel','style'=>''));
   $xfield=array('label'=>_('Date'),'name'=>'date');
   $style='size:10,lineSize:1';
   $tipo_chart='ColumnChart';
   break;
 case('sales_quarter'):
   $ar_address='ar_assets.php?tipo=plot_quartersales';
   $fields='"tip","asales","date"';
   $yfields=array(array('label'=>_('Sales'),'name'=>'asales','axis'=>'formatCurrencyAxisLabel','style'=>''));
   $xfield=array('label'=>_('Date'),'name'=>'date');
   $style='size:20,lineSize:1';
 $tipo_chart='ColumnChart';
   break;
 case('sales_month'):
   $title=_("Sales per Month");
   $ar_address='ar_assets.php?tipo=plot_monthsales';
   $fields='"asales","date","tip_asales"';
   $yfields=array(array('label'=>_('Sales'),'name'=>'asales','axis'=>'formatCurrencyAxisLabel','style'=>'size:10'));
   $xfield=array('label'=>_('Date'),'name'=>'date');
   $style='size:5,lineSize:1';
 $tipo_chart='ColumnChart';
    break;
 case('sales_year'):
   $ar_address='ar_assets.php?tipo=plot_yearsales';
   $fields='"asales","date","tip"';
   $yfields=array(array('label'=>_('Sales'),'name'=>'asales','axis'=>'formatCurrencyAxisLabel','style'=>''));
   $xfield=array('label'=>_('Date'),'name'=>'date');
   $style='size:10,lineSize:1';
 $tipo_chart='LineChart';
    break;
 case('out_week'):
    $title=_("Outers sold per Week");
   $ar_address='ar_assets.php?tipo=plot_weekout';
   $fields='"tip","out","date"';
   $yfields=array(array('label'=>_('Outers'),'name'=>'out','axis'=>'formatNumberAxisLabel','style'=>''));
   $xfield=array('label'=>_('Date'),'name'=>'date');
   $style='size:4,lineSize:1';
   $tipo_chart='LineChart';
   break;
 case('out_quarter'):
    $title=_("Outers sold per Quarter");
   $ar_address='ar_assets.php?tipo=plot_quarterout';
   $fields='"tip","out","date"';
		 $yfields=array(array('label'=>_('Outers'),'name'=>'out','axis'=>'formatNumberAxisLabel','style'=>''));
   $xfield=array('label'=>_('Date'),'name'=>'date');
   $style='size:20,lineSize:1';
 $tipo_chart='ColumnChart';
   break;
 case('out_month'):
   $title=_("Outers sold per Month");
   $ar_address='ar_assets.php?tipo=plot_monthout';
   $fields='"out","date","tip"';
		  $yfields=array(array('label'=>_('Outers'),'name'=>'out','axis'=>'formatNumberAxisLabel'));
   $xfield=array('label'=>_('Date'),'name'=>'date');
   $style='size:10,lineSize:1';
 $tipo_chart='ColumnChart';
    break;
 case('out_year'):
   $ar_address='ar_assets.php?tipo=plot_yearout';
   $fields='"out","date","tip"';
   $yfields=array(array('label'=>_('Outers'),'name'=>'out','axis'=>'formatNumberAxisLabel','style'=>''));
   $xfield=array('label'=>_('Date'),'name'=>'date');
   $style='size:10,lineSize:1';
 $tipo_chart='LineChart';
    break;
 case('stock_day'):
   $ar_address='ar_assets.php?tipo=plot_daystock';
   $fields='"stock","day","tip"';
		  $yfields=array(array('label'=>_('Outers'),'name'=>'stock','axis'=>'formatNumberAxisLabel','style'=>''));
   $xfield=array('label'=>_('Date'),'name'=>'day');
   $style='size:5,lineSize:1';
 $tipo_chart='CartesianChart';
    break;
 case('net_sales_month'):
   $title=_("Total Net Sales per Month");
   $ar_address='ar_orders.php?tipo=plot_monthsales';
   $fields='"sales","tip_sales","date"';
   $yfields=array(array('label'=>_('Month Net Sales'),'name'=>'sales','axis'=>'formatCurrencyAxisLabel','style'=>''));;
   $xfield=array('label'=>_('Date'),'name'=>'date');
   $style='';
   $tipo_chart='LineChart';
   break;
 case('net_sales_gmonth'):
   $title=_("Total Net Sales per Month (Group by month)");
   $ar_address='ar_orders.php?tipo=plot_gmonthsales';
   $fields='"sales2004","sales2005","sales2006","sales2007","sales2008","tip_sales2004","tip_sales2005","tip_sales2006","tip_sales2007","tip_sales2008","date"';
   $yfields=array(
		  
		  array('label'=>_('2004'),'name'=>'sales2004','axis'=>'formatCurrencyAxisLabel','style'=>"size:10,color: 0x62a74b,alpha:.9"),
		  array('label'=>_('2005'),'name'=>'sales2005','axis'=>'formatCurrencyAxisLabel','style'=>"size:10, color: 0xc665a7,alpha:.9"),
		  array('label'=>_('2006'),'name'=>'sales2006','axis'=>'formatCurrencyAxisLabel','style'=>"size:10, color: 0x4dbc9b,alpha:.9"),
		  array('label'=>_('2007'),'name'=>'sales2007','axis'=>'formatCurrencyAxisLabel','style'=>"size:10,color: 0xe2654f,alpha:.9"),
		  array('label'=>_('2008'),'name'=>'sales2008','axis'=>'formatCurrencyAxisLabel','style'=>"size:10,color: 0x4c77d1,alpha:.9")

		  );

   $xfield=array('label'=>_('Date'),'name'=>'date');
   $style='legend:{display: "bottom"}';
   $tipo_chart='ColumnChart';
   break;
 case('plot_month_outofstock'):
   $title="Percentage of products (& Picks) marked as out of stock per month";
   $ar_address='ar_orders.php?tipo=plot_month_outofstock';
   $fields='"per_product_outstock","per_picks_outstock","tip_per_product_outstock","tip_per_picks_outstock","date"';
   $yfields=array(
		  
		  array('label'=>_('Products Out of Stock'),'name'=>'per_product_outstock','axis'=>'formatPercentageAxisLabel'),
		  array('label'=>_('Picks Out of Stock'),'name'=>'per_picks_outstock','axis'=>'formatPercentageAxisLabel'),


		  );

   $xfield=array('label'=>_('Date'),'name'=>'date');
   $style='minorGridLines:{size:5,color: 0x4c77d1},  legend:{display: "bottom"}';
   $tipo_chart='LineChart';
   break;

 default:
   print _("Warning: Unknown $tipo plot reference".'.');
   exit;
   

 }
   



$alt=_('Unable to load Flash content. The YUI Charts Control requires Flash Player 9.0.45 or higher. You can download the latest version of Flash Player from the ').'<a href="http://www.adobe.com/go/getflashplayer">Adobe Flash Player Download Center</a>.';
$out='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3c.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" content="text/html; charset=UTF-8"   >
  <head>






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
}else
return YAHOO.util.Number.format( value,{prefix: "'.$myconf['currency_symbol'].'",thousandsSeparator: ",",decimalPlaces: 2});

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
  $out.=($i>0?',':'').'{ displayName: "'.$yfield['label'].'",  yField: "'.$yfield['name'].'" '.(isset($yfield['style'])?',style:{'.$yfield['style'].'}':'').'}'."\n";
  $i++;
}
$out.='];'."\n".'var yAxis = new YAHOO.widget.NumericAxis();
yAxis.labelFunction = "'.$yfield['axis'].'";
 var mychart = new YAHOO.widget.'.$tipo_chart.'( '.($tipo_chart=='CartesianChart'?"'line',":'').'  "plot", jsonData,
 	{
style:{'.$style.'}          ,
 wmode: "opaque",
          series: seriesDef,
 	 xField: "'.$xfield['name'].'",
 	 yAxis: yAxis,
 	 dataTipFunction: "DataTipText",
 	 expressInstall: "assets/expressinstall.swf"
 	});


 </script>
 </html>';

 print $out;






?>