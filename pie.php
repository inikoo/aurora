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
require_once 'class.Product.php';

$tipo='';
if(isset($_REQUEST['tipo']))
  $tipo=$_REQUEST['tipo'];
$title='';

$options='';
$staked=false;

switch($tipo){
 case('pie_department_share'):
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
   $value_tipo='sales';
   $ar_address='ar_pie.php?store_keys='.$store_keys.'&category=department&value='.$value_tipo;
   
   //print $ar_address;
   $fields='"value","label"';
   
  
   $style='legend:
			{
				display: "right",
				padding: 10,
				spacing: 5,
				font:
				{
					family: "Arial",
					size: 13
				}
			}';
   $tipo_chart='PieChart';
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


 



 YAHOO.widget.Chart.SWFURL = "'.$yui_path.'charts/assets/charts.swf";
 	
var jsonData = new YAHOO.util.DataSource( "'.$ar_address.'" );
 	jsonData.connMethodPost = true;
 	jsonData.responseType = YAHOO.util.DataSource.TYPE_JSON;
 	jsonData.responseSchema =
 	{
 			resultsList: "resultset.data",
 			fields: ['.$fields.']
 	};

 

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




var mychart = new YAHOO.widget.'.($staked?'Stacked':'').$tipo_chart.'( '.($tipo_chart=='CartesianChart'?"'line',":'').'  "plot", jsonData,

 	{
style:{'.$style.'}          ,
 wmode: "transparent",
          
 	 categoryField:"label",
	 dataField:"value",
        
       
         Expressinstall: "assets/expressinstall.swf"
 	});


 </script>
 </html>';

 print $out;






?>