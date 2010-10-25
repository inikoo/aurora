<?php
/*
 File: customer_csv.php 

 Customer CSV data for export

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2010, Kaktus 
 
 Version 2.0
*/

include_once('common.php');
include_once('ar_common.php');

if(!isset($_REQUEST['tipo']))
exit("unknown operation");

$tipo=$_REQUEST['tipo'];

list($filename,$adata)=get_data($tipo);

if(!$filename){
exit("unknown operation 2");
}

//print_r($adata);



header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"".$filename."\"");
$out = fopen('php://output', 'w');
foreach ($adata as $data) {
    fputcsv($out, $data);
}

fclose($out);

function get_data($tipo){
$filename='';
$data=array();


switch ($tipo) {
    case 'customers':
        $filename=_('customers').'.csv';
        $data=get_customers_data();
        break;
    case 'stores':
        $filename=_('stores').'.csv';
        $f_field=$_SESSION['state']['stores']['table']['f_field'];
        $f_value=$_SESSION['state']['stores']['table']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('stores').'.csv';
        $data=get_stores_data($wheref);
        break;        
    default:
        
        break;
}
return array($filename,$data);
}

function get_stores_data($wheref){

$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['stores']['table']['csv_export'];
}




$fields=array(
'code'=>array('title'=>_('Code'),'db_name'=>'Store Code'),
'name'=>array('title'=>_('Name'),'db_name'=>'Store Name'),
'departments'=>array('title'=>_('Departments'),'db_name'=>'Store Departments'),
'families'=>array('title'=>_('Departments'),'db_name'=>'Store Families'),
'products'=>array('title'=>_('Products'),'db_name'=>'Store For Public Sale Products'),
'discontinued'=>array('title'=>_('Discontinued'),'db_name'=>'Store Discontinued Products'),
'new'=>array('title'=>_('New'),'db_name'=>'Store New Products'),
'surplus'=>array('title'=>_('Surplus'),'db_name'=>'Store Surplus Availability Products'),
'ok'=>array('title'=>_('Ok'),'db_name'=>'Store Optimal Availability Products'),
'gone'=>array('title'=>_('Gone'),'db_name'=>'Store Out Of Stock Products'),
'low'=>array('title'=>_('Gone'),'db_name'=>'Store Low Availability Products'),
'critical'=>array('title'=>_('Gone'),'db_name'=>'Store Critical Availability Products'),
'unknown'=>array('title'=>_('Unknown'),'db_name'=>'Store Unknown Stock Products'),
'sales_all'=>array('title'=>_('Total Sales'),'db_name'=>'Store Total Invoiced Amount'),
'profit_all'=>array('title'=>_('Total Profit'),'db_name'=>'Store Total Profit'),
'sales_1y'=>array('title'=>_('Sales 1Y'),'db_name'=>'Store 1 Year Acc Invoiced Amount'),
'profit_1y'=>array('title'=>_('Profit 1Y'),'db_name'=>'Store 1 Year Acc Profit'),
'sales_1q'=>array('title'=>_('Sales 1Q'),'db_name'=>'Store 1 Quarter Acc Invoiced Amount'),
'profit_1q'=>array('title'=>_('Profit 1Q'),'db_name'=>'Store 1 Quarter Acc Profit'),
'sales_1m'=>array('title'=>_('Sales 1M'),'db_name'=>'Store 1 Month Acc Invoiced Amount'),
'profit_1m'=>array('title'=>_('Profit 1M'),'db_name'=>'Store 1 Month Acc Profit'),
'sales_1w'=>array('title'=>_('Sales 1W'),'db_name'=>'Store 1 Week Acc Invoiced Amount'),
'profit_1w'=>array('title'=>_('Profit 1W'),'db_name'=>'Store 1 Week Acc Profit'),
);


foreach($fields as $key=>$value){
if(!isset($fields_to_export[$key]) or  !$fields_to_export[$key]  )
unset($fields[$key]);
}



$data=array();
$_data=array();
foreach($fields as $key=>$options){
$_data[]=$options['title'];
}
$data[]=$_data;
$sql="select * from `Store Dimension` where true $wheref";
$res=mysql_query($sql);

while($row=mysql_fetch_assoc($res)){
$_data=array();
foreach($fields as $key=>$options){

$_data[]=$row[$options['db_name']];
}
$data[]=$_data;
}
//print_r($data);exit;

return $data;

}
 
?>