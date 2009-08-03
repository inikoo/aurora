<?php
include_once('common.php');
?>


function formatCurrencyAxisLabel( value )
{
     if( value==0)
	 return ''
    if( value>=10000){
	value=value/1000;
	var str=YAHOO.util.Number.format( value,{prefix: "<?php echo$myconf['currency_symbol']?>",thousandsSeparator: ",",decimalPlaces: 0});
	return str+'K';

    }else
     return YAHOO.util.Number.format( value,{prefix: "<?php echo$myconf['currency_symbol']?>",thousandsSeparator: ",",decimalPlaces: 2});
} 

function init(){
YAHOO.widget.Chart.SWFURL = "http://yui.yahooapis.com/2.5.2/build//charts/assets/charts.swf";
	
//--- data

var plot_monthsalesData = new YAHOO.util.DataSource( "ar_contacts.php?tipo=plot_order_interval"  );


plot_monthsalesData.connMethodPost = true;
plot_monthsalesData.responseType = YAHOO.util.DataSource.TYPE_JSON;
plot_monthsalesData.responseSchema =
    {
	resultsList: "resultset.data",
	fields: ["x","y"]
    };





var seriesDef = [
		 //		 { displayName: "<?php echo _('Losses')?>", yField: "losses", style:{color: 0xff0000,lineSize: 2,size:8}},
		 { displayName: "<?php echo _('Customers')?>", yField: "y",  style:{connectPoints:false,size:8} }

 
];

//var plot_monthsales_currencyAxis = new YAHOO.widget.NumericAxis();
//plot_monthsales_currencyAxis.labelFunction = "formatCurrencyAxisLabel";

getDataTipText = function( item, index, series )
{
    //	var toolTipText = series.displayName + " for " + item.month;
    //	toolTipText += "\n" + YAHOO.example.formatCurrencyAxisLabel( item[series.yField] );
    //	return toolTipText;
    if(series.yField=='sales')
	return item.tip;
    else
	return item.tip_losses;
}


var mychart = new YAHOO.widget.LineChart( "orden_interval_distribution",plot_monthsalesData ,
					  {
					      
					      series: seriesDef,
					      xField: "x",
					      wmode: "opaque",
					      //polling: 1000, 
					      // yAxis: plot_monthsales_currencyAxis,
					      //yAxis: currencyAxis,
					      // dataTipFunction: getDataTipText,
					      //only needed for flash player express install
					      expressInstall: "assets/expressinstall.swf"
					  });

}

YAHOO.util.Event.onDOMReady(init);