<?php
/*
 File: customer_csv.php 

 Customer CSV data for export

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2010, Inikoo 
 
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
//exit;





header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"".$filename."\"");
//$out = fopen('php://output', 'w');

$outstream = fopen("php://temp", 'r+');
  //      fputcsv($outstream, $data, ',', '"');
   //     rewind($outstream);
    //    $csv = fgets($outstream);
     //   fclose($outstream);
      //  return $csv;


foreach ($adata as $data) {
    fputcsv($outstream, $data,"\t");
}
 rewind($outstream);
$csv='';
  while ( ($line = fgets($outstream)) !== false) {
  $csv.=$line;
}
  
  
fclose($outstream);

$unicode_str_for_Excel = chr(255).chr(254).mb_convert_encoding( $csv, 'UTF-16LE', 'UTF-8');

print $unicode_str_for_Excel;



function get_data($tipo){
$filename='';
$data=array();


switch ($tipo) {
    case 'customers':
        $filename=_('customers').'.csv';

        $f_field=$_SESSION['state']['customers']['table']['f_field'];
        $f_value=$_SESSION['state']['customers']['table']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('customers').'.csv';
	$where=sprintf(' `Customer Store Key`=%d ',$_SESSION['state']['customers']['store']);
        $data=get_customerslist_data($wheref,$where);
        break;
    case 'stores':
        $filename=_('stores').'.csv';
        $f_field=$_SESSION['state']['stores']['table']['f_field'];
        $f_value=$_SESSION['state']['stores']['table']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('stores').'.csv';
        $data=get_stores_data($wheref);break;
    case 'families':
        $filename=_('families').'.csv';
        $f_field=$_SESSION['state']['families']['table']['f_field'];
        $f_value=$_SESSION['state']['families']['table']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('families').'.csv';
        $data=get_families_data($wheref);
        break;
  case 'families_in_department':
    
        $f_field=$_SESSION['state']['family']['table']['f_field'];
        $f_value=$_SESSION['state']['family']['table']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('families').'.csv';
        $where=sprintf(' `Product Main Department Key`=%d ',$_SESSION['state']['department']['id']);
        $data=get_families_data($wheref,$where);
        break;       
    case 'products':
        $filename=_('products').'.csv';
        $f_field=$_SESSION['state']['products']['table']['f_field'];
        $f_value=$_SESSION['state']['products']['table']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('products').'.csv';
        $data=get_products_data($wheref);
        break;
    case 'staff':
        $filename=_('staff').'.csv';
        $f_field=$_SESSION['state']['staff']['table']['f_field'];
        $f_value=$_SESSION['state']['staff']['table']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('staff').'.csv';
        $data=get_staff_data($wheref);
        break;
    case 'warehouses':
        $filename=_('warehouses').'.csv';
        $f_field=$_SESSION['state']['warehouses']['table']['f_field'];
        $f_value=$_SESSION['state']['warehouses']['table']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('warehouses').'.csv';
        $data=get_warehouses_data($wheref);
        break;
    case 'company_areas':
        $filename=_('company_areas').'.csv';
        $f_field=$_SESSION['state']['staff']['company_areas']['f_field'];
        $f_value=$_SESSION['state']['staff']['company_areas']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('company_areas').'.csv';
        $data=get_company_areas_data($wheref);
        break;
   case 'company_departments':
        $filename=_('company_departments').'.csv';
        $f_field=$_SESSION['state']['staff']['company_departments']['f_field'];
        $f_value=$_SESSION['state']['staff']['company_departments']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('company_departments').'.csv';
        $data=get_company_departments_data($wheref);
        break;    
    case 'positions':
        $filename=_('positions').'.csv';
        $f_field=$_SESSION['state']['staff']['positions']['f_field'];
        $f_value=$_SESSION['state']['staff']['positions']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('positions').'.csv';
        $data=get_positions_data($wheref);
        break;
    case 'departments':
        $filename=_('departments').'.csv';
        $f_field=$_SESSION['state']['departments']['table']['f_field'];
        $f_value=$_SESSION['state']['departments']['table']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('departments').'.csv';
        $where=sprintf(' `Product Department Store Key`=%d ',$_SESSION['state']['store']['id']);
        $data=get_departments_data($wheref,$where);
        break;
   case 'products_in_family':
    
        $f_field=$_SESSION['state']['family']['table']['f_field'];
        $f_value=$_SESSION['state']['family']['table']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('products').'.csv';
        $where=sprintf(' `Product Family Key`=%d ',$_SESSION['state']['family']['id']);
        $data=get_products_data($wheref,$where);
        break;

   case 'orders_per_store':
        $filename=_('orders_per_store').'.csv';
        $f_field=$_SESSION['state']['stores']['orders']['f_field'];
        $f_value=$_SESSION['state']['stores']['orders']['f_value'];

        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('orders_per_store').'.csv';
	 $where=sprintf(' `Store Key`=%d ',$_SESSION['state']['store']['id']);
        $data=get_orders_in_store_data($wheref);
        break; 
   case 'invoices_per_store':
        $filename=_('invoices_per_store').'.csv';
        $f_field=$_SESSION['state']['stores']['invoices']['f_field'];
        $f_value=$_SESSION['state']['stores']['invoices']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('invoices_per_store').'.csv';
	 $where=sprintf(' `Store Key`=%d ',$_SESSION['state']['store']['id']);
        $data=get_invoices_per_store_data($wheref);
        break; 
   case 'delivery_notes_per_store':
        $filename=_('delivery_notes_per_store').'.csv';
        $f_field=$_SESSION['state']['stores']['delivery_notes']['f_field'];
        $f_value=$_SESSION['state']['stores']['delivery_notes']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('delivery_notes_per_store').'.csv';
	 $where=sprintf(' `Store Key`=%d ',$_SESSION['state']['orders']['store']);
        $data=get_delivery_notes_data($wheref);
        break;
   case 'orders':
        $filename=_('orders').'.csv';
        $f_field=$_SESSION['state']['orders']['table']['f_field'];
        $f_value=$_SESSION['state']['orders']['table']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('orders').'.csv';
        $date_interval=prepare_mysql_dates($_SESSION['state']['orders']['from'],$_SESSION['state']['orders']['to']);

        $where=sprintf(' `Order Store Key`=%d ',$_SESSION['state']['orders']['store']).$date_interval['mysql'];
        
        
        $data=get_orders_data($wheref,$where);
        break;  
   case 'invoices':
        $filename=_('invoices').'.csv';
        $f_field=$_SESSION['state']['orders']['invoices']['f_field'];
        $f_value=$_SESSION['state']['orders']['invoices']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('invoices').'.csv';
        $where=sprintf(' `Invoice Store Key`=%d ',$_SESSION['state']['store']['id']);
         $type=$_SESSION['state']['orders']['invoices'];
         $to=$_SESSION['state']['orders']['to'];
          $from=$_SESSION['state']['orders']['from'];
           $date_interval=prepare_mysql_dates($from,$to,'`Invoice Date`','only_dates');
    
     if($date_interval['error']){
       $date_interval=prepare_mysql_dates($_SESSION['state']['orders']['from'],$_SESSION['state']['orders']['to']);
     }else{
       $_SESSION['state']['orders']['from']=$date_interval['from'];
       $_SESSION['state']['orders']['to']=$date_interval['to'];
     }
        
        
        $where.=$date_interval['mysql'];
        
        
        switch ($type) {
    case 'paid':
        $where.=' and `Invoice Paid`="Yes"';
        break;
    case 'to_pay':
        $where.=' and `Invoice Paid`!="Yes"';
        break;
    case 'invoices':
        $where.=' and `Invoice Title`="Invoice"';
        break;        
    case 'refunds':
        $where.=' and `Invoice Title`="Refund"';
        break;
    default:
        
        }
        
        $data=get_invoices_data($wheref,$where);
        break;
   case 'ready_to_pick_orders':
        $filename=_('warehouse_orders').'.csv';
        $f_field=$_SESSION['state']['orders']['ready_to_pick_dn']['f_field'];
        $f_value=$_SESSION['state']['orders']['ready_to_pick_dn']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('warehouse_orders').'.csv';
        $where=sprintf(' `Delivery Note State` not in ("Dispatched","Cancelled") ');
        $data=get_orders_ready_to_pick_orders_data($wheref,$where);
        break;    
   case 'dn':
        $filename=_('delivery_notes').'.csv';
        $f_field=$_SESSION['state']['orders']['dn']['f_field'];
        $f_value=$_SESSION['state']['orders']['dn']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('delivery_notes').'.csv';
        $where=sprintf(' `Delivery Note Store Key`=%d ',$_SESSION['state']['store']['id']);
        $data=get_orders_delivery_notes_data($wheref,$where);
        break;  
   case 'customers_per_store':
        $filename=_('customers_per_store').'.csv';
        $f_field=$_SESSION['state']['stores']['customers']['f_field'];
        $f_value=$_SESSION['state']['stores']['customers']['f_value'];

        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('customers_per_store').'.csv';
	 $where=sprintf(' `Store Key`=%d ',$_SESSION['state']['store']['id']);
        $data=get_customers_data($wheref);
        break; 
     case 'parts':
        $filename=_('parts').'.csv';
        $f_field=$_SESSION['state']['parts']['table']['f_field'];
        $f_value=$_SESSION['state']['parts']['table']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('parts').'.csv';
        $data=get_parts_data($wheref);
        break;
    case 'deals':
        $filename=_('deals').'.csv';
        $f_field=$_SESSION['state']['deals']['table']['f_field'];
        $f_value=$_SESSION['state']['deals']['table']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('deals').'.csv';
        $data=get_deals_data($wheref);
        break;
    case 'suppliers':
        $filename=_('suppliers').'.csv';
        $f_field=$_SESSION['state']['suppliers']['table']['f_field'];
        $f_value=$_SESSION['state']['suppliers']['table']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('suppliers').'.csv';
        $data=get_suppliers_data($wheref);break;
    case 'supplier_products':
        $filename=_('supplier_products').'.csv';
        $f_field=$_SESSION['state']['supplier']['products']['f_field'];
        $f_value=$_SESSION['state']['supplier']['products']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('supplier_products').'.csv';
        $data=get_supplier_products_data($wheref);
        break;
    case 'supplier':
        $filename=_('supplier_products').'.csv';
        $f_field=$_SESSION['state']['supplier']['products']['f_field'];
        $f_value=$_SESSION['state']['supplier']['products']['f_value'];
        $wheref=wheref_stores($f_field,$f_value);
        $filename=_('supplier_products').'.csv';
        $where=sprintf(' `Supplier Key`=%d ',$_SESSION['state']['supplier']['id']);
        $data=get_supplier_products_data($wheref,$where);
        break;
   case 'porders':
        $filename=_('porders').'.csv';
        $f_field=$_SESSION['state']['porder']['table']['f_field'];
        $f_value=$_SESSION['state']['porder']['table']['f_value'];
        // $wheref=wheref_stores($f_field,$f_value);
        $filename=_('porders').'.csv';
        // $where=sprintf(' `Order Store Key`=%d ',$_SESSION['state']['store']['id']);
        $data=get_porders_data();
        break; 
   case 'porder_invoices':
        $filename=_('supplier invoices').'.csv';
        $f_field=$_SESSION['state']['porder']['porder_invoices']['f_field'];
        $f_value=$_SESSION['state']['porder']['porder_invoices']['f_value'];
       // $wheref=wheref_stores($f_field,$f_value);
        $filename=_('supplier invoices').'.csv';
       // $where=sprintf(' `Invoice Store Key`=%d ',$_SESSION['state']['store']['id']);
        $data=get_porder_invoices_data();
        break;
   case 'porder_dn':
        $filename=_('delivery notes').'.csv';
        $f_field=$_SESSION['state']['porder']['porder_dn']['f_field'];
        $f_value=$_SESSION['state']['porder']['porder_dn']['f_value'];
       // $wheref=wheref_stores($f_field,$f_value);
        $filename=_('delivery notes').'.csv';
       // $where=sprintf(' `Invoice Store Key`=%d ',$_SESSION['state']['store']['id']);
        $data=get_porder_dn_data();
        break;
                    
    default:
        
        break;
}
return array($filename,$data);
}

function get_stores_data($wheref,$where='true'){

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
$sql="select * from `Store Dimension` where $where $wheref";
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

function get_families_data($wheref){

$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['families']['table']['csv_export'];
}


$fields=array(
'code'=>array('title'=>_('Code'),'db_name'=>'Product Family Code'),
'stores'=>array('title'=>_('Stores'),'db_name'=>'Product Family Store Code'),
'name'=>array('title'=>_('Name'),'db_name'=>'Product Family Name'),
'products'=>array('title'=>_('Products'),'db_name'=>'Product Family For Public Sale Products'),

'surplus'=>array('title'=>_('Surplus'),'db_name'=>'Product Family Surplus Availability Products'),
'ok'=>array('title'=>_('Ok'),'db_name'=>'Product Family Optimal Availability Products'),
'low'=>array('title'=>_('Gone'),'db_name'=>'Product Family Low Availability Products'),
'critical'=>array('title'=>_('Gone'),'db_name'=>'Product Family Critical Availability Products'),
'gone'=>array('title'=>_('Unknown'),'db_name'=>'Product Family Out Of Stock Products'),
'unknown'=>array('title'=>_('Unknown'),'db_name'=>'Product Family Unknown Stock Products'),

'sales_all'=>array('title'=>_('Total Sales'),'db_name'=>'Product Family Total Acc Invoiced Gross Amount'),
'profit_all'=>array('title'=>_('Total Profit'),'db_name'=>'Product Family Total Acc Profit'),
'sales_1y'=>array('title'=>_('Sales 1Y'),'db_name'=>'Product Family 1 Year Acc Invoiced Gross Amount'),
'profit_1y'=>array('title'=>_('Profit 1Y'),'db_name'=>'Product Family 1 Year Acc Profit'),
'sales_1q'=>array('title'=>_('Sales 1Q'),'db_name'=>'Product Family 1 Quarter Acc Invoiced Gross Amount'),
'profit_1q'=>array('title'=>_('Profit 1Q'),'db_name'=>'Product Family 1 Quarter Acc Profit'),
'sales_1m'=>array('title'=>_('Sales 1M'),'db_name'=>'Product Family 1 Month Acc Invoiced Gross Amount'),
'profit_1m'=>array('title'=>_('Profit 1M'),'db_name'=>'Product Family 1 Month Acc Profit'),
'sales_1w'=>array('title'=>_('Sales 1W'),'db_name'=>'Product Family 1 Week Acc Invoiced Gross Amount'),
'profit_1w'=>array('title'=>_('Profit 1W'),'db_name'=>'Product Family 1 Week Acc Profit'),
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
$sql="select * from `Product Family Dimension` where true $wheref";
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



function get_products_data($wheref,$where=' true'){

$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['products']['table']['csv_export'];
}


$fields=array(
'code'=>array('title'=>_('Code'),'db_name'=>'Product Code'),
'name'=>array('title'=>_('Name'),'db_name'=>'Product Short Description'),
'status'=>array('title'=>_('Status'),'db_name'=>'Product Sales Type'),
'web'=>array('title'=>_('Web'),'db_name'=>'Product Web State'),


'sales_all'=>array('title'=>_('Total Sales'),'db_name'=>'Product Total Invoiced Gross Amount'),
'profit_all'=>array('title'=>_('Total Profit'),'db_name'=>'Product Total Profit'),
'sales_1y'=>array('title'=>_('Sales 1Y'),'db_name'=>'Product 1 Year Acc Invoiced Gross Amount'),
'profit_1y'=>array('title'=>_('Profit 1Y'),'db_name'=>'Product 1 Year Acc Profit'),
'sales_1q'=>array('title'=>_('Sales 1Q'),'db_name'=>'Product 1 Quarter Acc Invoiced Gross Amount'),
'profit_1q'=>array('title'=>_('Profit 1Q'),'db_name'=>'Product 1 Quarter Acc Profit'),
'sales_1m'=>array('title'=>_('Sales 1M'),'db_name'=>'Product 1 Month Acc Invoiced Gross Amount'),
'profit_1m'=>array('title'=>_('Profit 1M'),'db_name'=>'Product 1 Month Acc Profit'),
'sales_1w'=>array('title'=>_('Sales 1W'),'db_name'=>'Product 1 Week Acc Invoiced Gross Amount'),
'profit_1w'=>array('title'=>_('Profit 1W'),'db_name'=>'Product 1 Week Acc Profit'),


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
$sql="select * from `Product Dimension` where $where $wheref";
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


function get_staff_data($wheref,$where=' true'){

$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['staff']['table']['csv_export'];
}


$fields=array(
'id'=>array('title'=>_('Td'),'db_name'=>'Staff Key'),
'name'=>array('title'=>_('Name'),'db_name'=>'Staff Name'),
'alias'=>array('title'=>_('Alias'),'db_name'=>'Staff Alias'),
'position'=>array('title'=>_('Position'),'db_name'=>'Company Position Title'),
'description'=>array('title'=>_('Description'),'db_name'=>'Company Position Description'),
'valid_from'=>array('title'=>_('Valid From'),'db_name'=>'Staff Valid from'),
'valid_to'=>array('title'=>_('Valid To'),'db_name'=>'Staff Valid To'),

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
$sql="select * from `Company Position Staff Bridge` PSB left join `Company Position Dimension` P on (`Company Position Key`=`Position Key`) left join `Staff Dimension` SD on PSB.`Staff Key`= SD.`Staff Key` where $where $wheref";

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

function get_warehouses_data($wheref,$where=' true'){

$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['warehouses']['table']['csv_export'];
}


$fields=array(
'id'=>array('title'=>_('Id'),'db_name'=>'Warehouse Key'),
'code'=>array('title'=>_('Code'),'db_name'=>'Warehouse Code'),
'name'=>array('title'=>_('Name'),'db_name'=>'Warehouse Name'),
'locations_no'=>array('title'=>_('Locations'),'db_name'=>'Warehouse Number Locations'),
'areas_no'=>array('title'=>_('Areas'),'db_name'=>'Warehouse Number Areas'),
'shelfs_no'=>array('title'=>_('Shelfs'),'db_name'=>'Warehouse Number Shelfs')

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
$sql="select *  from `Warehouse Dimension` where $where $wheref";

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

function get_company_areas_data($wheref,$where=' true'){
$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['staff']['company_areas']['csv_export'];
}
$fields=array(
'id'=>array('title'=>_('Id'),'db_name'=>'Company Area Key'),
'name'=>array('title'=>_('Name'),'db_name'=>'Company Area Name'),
'code'=>array('title'=>_('Code'),'db_name'=>'Company Area Code'),
'description'=>array('title'=>_('Description'),'db_name'=>'Company Area Description'),
'number_of_department'=>array('title'=>_('No. Of department'),'db_name'=>'Company Area Number Departments'),
'number_of_position'=>array('title'=>_('No. Of Position'),'db_name'=>'Company Area Number Positions'),
'number_of_employee'=>array('title'=>_('No. Of Employee'),'db_name'=>'Company Area Number Employees'),
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
$sql="select * from `Company Area Dimension` where $where $wheref";
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

function get_positions_data($wheref,$where=' true'){
$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['staff']['positions']['csv_export'];
}
$fields=array(
'code'=>array('title'=>_('Code'),'db_name'=>'Company Position Code'),
'name'=>array('title'=>_('Name'),'db_name'=>'Company Position Title'),
'description'=>array('title'=>_('Description'),'db_name'=>'Company Position Description'),
'employees'=>array('title'=>_('Employee'),'db_name'=>'Company Position Employees'),
'department_name'=>array('title'=>_('Department Name'),'db_name'=>'Company Department Name'),
'department_code'=>array('title'=>_('Department Code'),'db_name'=>'Company Department Code'),
'department_description'=>array('title'=>_('Department Description'),'db_name'=>'Company Department Description'),
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

$sql="select * from `Company Position Dimension` CPD left join `Company Department Position Bridge` CDPB on (`Company Position Key`=`Position Key`) left join `Company Department Dimension` CDD on CDD.`Company Department Key`= CDPB.`Department Key` where $where $wheref";

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

function get_departments_data($wheref,$where){
$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['departments']['table']['csv_export'];
}


$fields=array(
'code'=>array('title'=>_('Code'),'db_name'=>'Product Department Code'),
'name'=>array('title'=>_('Name'),'db_name'=>'Product Department Name'),
'families'=>array('title'=>_('Families'),'db_name'=>'Product Department Families'),
'products'=>array('title'=>_('Products'),'db_name'=>'Product Department For Public Sale Products'),
'discontinued'=>array('title'=>_('Discontinued'),'db_name'=>'Product Department Discontinued Products'),


'surplus'=>array('title'=>_('Surplus'),'db_name'=>'Product Department Surplus Availability Products'),
'ok'=>array('title'=>_('Ok'),'db_name'=>'Product Department Optimal Availability Products'),
'gone'=>array('title'=>_('Gone'),'db_name'=>'Product Department Out Of Stock Products'),
'low'=>array('title'=>_('Gone'),'db_name'=>'Product Department Low Availability Products'),
'critical'=>array('title'=>_('Gone'),'db_name'=>'Product Department Critical Availability Products'),
'unknown'=>array('title'=>_('Unknown'),'db_name'=>'Product Department Unknown Sales State Products'),
'sales_all'=>array('title'=>_('Total Sales'),'db_name'=>'Product Department Total Invoiced Gross Amount'),
'profit_all'=>array('title'=>_('Total Profit'),'db_name'=>'Product Department Total Profit'),
'sales_1y'=>array('title'=>_('Sales 1Y'),'db_name'=>'Product Department 1 Year Acc Invoiced Gross Amount'),
'profit_1y'=>array('title'=>_('Profit 1Y'),'db_name'=>'Product Department 1 Year Acc Profit'),
'sales_1q'=>array('title'=>_('Sales 1Q'),'db_name'=>'Product Department 1 Quarter Acc Invoiced Gross Amount'),
'profit_1q'=>array('title'=>_('Profit 1Q'),'db_name'=>'Product Department 1 Quarter Acc Profit'),
'sales_1m'=>array('title'=>_('Sales 1M'),'db_name'=>'Product Department 1 Month Acc Invoiced Gross Amount'),
'profit_1m'=>array('title'=>_('Profit 1M'),'db_name'=>'Product Department 1 Month Acc Profit'),
'sales_1w'=>array('title'=>_('Sales 1W'),'db_name'=>'Product Department 1 Week Acc Invoiced Gross Amount'),
'profit_1w'=>array('title'=>_('Profit 1W'),'db_name'=>'Product Department 1 Week Acc Profit'),
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
$sql="select * from `Product Department Dimension` where $where $wheref";

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






function get_company_departments_data($wheref){

$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['staff']['company_departments']['csv_export'];
}


$fields=array(
'id'=>array('title'=>_('Area'),'db_name'=>'Company Department Key'),
'code'=>array('title'=>_('Code'),'db_name'=>'Company Department Code'),
'name'=>array('title'=>_('Name'),'db_name'=>'Company Department Name'),
'department_description'=>array('title'=>_('Departments Description'),'db_name'=>'Company Department Description'),
'number_of_position'=>array('title'=>_('No. Of Department Employee'),'db_name'=>'Company Department Number Positions'),
'number_of_employee'=>array('title'=>_('Company Area Name'),'db_name'=>'Company Department Number Employees')
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

 
$sql="select * from `Company Department Dimension` where true $wheref";
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


function get_orders_in_store_data($wheref,$where='true'){

$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['stores']['orders']['csv_export'];
}


$fields=array(
'code'=>array('title'=>_('Code'),'db_name'=>'Store Code'),
'name'=>array('title'=>_('Name'),'db_name'=>'Store Name'),
'orders'=>array('title'=>_('Orders'),'db_name'=>'Store Total Orders'),
'cancelled'=>array('title'=>_('Cancelled'),'db_name'=>'Store Cancelled Orders'),
'suspended'=>array('title'=>_('Suspended'),'db_name'=>'Store Suspended Orders'),
'pending'=>array('title'=>_('Pending'),'db_name'=>'Store Orders In Process'),
'dispatched'=>array('title'=>_('Dispatched'),'db_name'=>'Store Dispatched Orders'),

'sales_all'=>array('title'=>_('Total Sales'),'db_name'=>'Store Total Invoiced Gross Amount'),
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
$sql="select * from `Store Dimension` where $where $wheref";
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

function get_invoices_per_store_data($wheref,$where='true'){

$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['stores']['invoices']['csv_export'];
}





$fields=array(
'code'=>array('title'=>_('Code'),'db_name'=>'Store Code'),
'name'=>array('title'=>_('Name'),'db_name'=>'Store Name'),
'invoices'=>array('title'=>_('Invoices'),'db_name'=>'Store Invoices'),
'invpaid'=>array('title'=>_('Inv Paid'),'db_name'=>'Store Paid Invoices'),
'invtopay'=>array('title'=>_('Inv To Pay'),'db_name'=>'Store Partially Paid Invoices'),
'refunds'=>array('title'=>_('Refunds'),'db_name'=>'Store Refunds '),
'refpaid'=>array('title'=>_('Ref Paid'),'db_name'=>'Store Paid Refunds'),
'reftopay'=>array('title'=>_('Ref To Pay'),'db_name'=>'Store Partially Paid Refunds'),

'sales_all'=>array('title'=>_('Total Sales'),'db_name'=>'Store Total Invoiced Gross Amount'),
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
$sql="select * from `Store Dimension` where $where $wheref";
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
function get_delivery_notes_data($wheref,$where='true'){

$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['stores']['delivery_notes']['csv_export'];
}


$fields=array(
'code'=>array('title'=>_('Code'),'db_name'=>'Store Code'),
'name'=>array('title'=>_('Name'),'db_name'=>'Store Name'),
'total'=>array('title'=>_('Total'),'db_name'=>'Store Total Delivery Notes'),
'topick'=>array('title'=>_('To Pick'),'db_name'=>'Store Ready to Pick Delivery Notes'),
'picking'=>array('title'=>_('Picking'),'db_name'=>'Store Picking Delivery Notes'),
'packing'=>array('title'=>_('Packing'),'db_name'=>'Store Packing Delivery Notes'),
'ready'=>array('title'=>_('Ready'),'db_name'=>'Store Ready to Dispatch Delivery Notes'),
'send'=>array('title'=>_('Send'),'db_name'=>'Store Dispatched Delivery Notes'),
'returned'=>array('title'=>_('Returned'),'db_name'=>'Store Returned Delivery Notes'),

'sales_all'=>array('title'=>_('Total Sales'),'db_name'=>'Store Total Invoiced Gross Amount'),
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
$sql="select * from `Store Dimension` where $where $wheref";
$res=mysql_query($sql);

while($row=mysql_fetch_assoc($res)){
//print_r($row);
$_data=array();
foreach($fields as $key=>$options){

$_data[]=$row[$options['db_name']];
}
$data[]=$_data;
}
//print_r($data);exit;

return $data;

}


function get_orders_data($wheref,$where='true'){

$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['orders']['table']['csv_export'];
}


$fields=array(
'code'=>array('title'=>_('Order Id'),'db_name'=>'Order Public ID'),
'last_date'=>array('title'=>_('Last Updated'),'db_name'=>'Order Last Updated Date'),
'customer'=>array('title'=>_('Customer'),'db_name'=>'Order Customer Name'),
'status'=>array('title'=>_('Status'),'db_name'=>'Order Current Dispatch State'),


'totaltax'=>array('title'=>_('Total Tax'),'db_name'=>'Order Total Tax Amount'),
'totalnet'=>array('title'=>_('Total Net'),'db_name'=>'Order Total Net Amount'),
'total'=>array('title'=>_('Total'),'db_name'=>'Order Total Net Amount'),

'balancenet'=>array('title'=>_('Balance Net'),'db_name'=>'Order Balance Net Amount'),
'balancetax'=>array('title'=>_('Balance Tax'),'db_name'=>'Order Balance Tax Amount'),
'balancetotal'=>array('title'=>_('Balance Total'),'db_name'=>'Order Balance Total Amount'),

'outstandingbalancenet'=>array('title'=>_('Outstanding Balance Net'),'db_name'=>'Order Outstanding Balance Net Amount'),
'outstandingbalancetax'=>array('title'=>_('Outstanding Balance Tax'),'db_name'=>'Order Outstanding Balance Tax Amount'),
'outstandingbalancetotal'=>array('title'=>_('Outstanding Balance Total'),'db_name'=>'Order Outstanding Balance Total Amount'),

'contactname'=>array('title'=>_('Customer Contact Name'),'db_name'=>'Order Customer Contact Name'),
'sourcetype'=>array('title'=>_('Source Type'),'db_name'=>'Order Main Source Type'),
'paymentstate'=>array('title'=>_('Payment State'),'db_name'=>'Order Current Payment State'),
'actiontaken'=>array('title'=>_('Actions Taken'),'db_name'=>'Order Actions Taken'),
'ordertype'=>array('title'=>_('Type'),'db_name'=>'Order Type'),
'shippingmethod'=>array('title'=>_('Shipping Method'),'db_name'=>'Order Shipping Method'),

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
$sql="select * from `Order Dimension` where $where $wheref";
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
function get_invoices_data($wheref,$where='true'){

$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['orders']['invoices']['csv_export'];
}


$field=array('code','date','customer','customer_key','net','tax_S1','tax_S2');

//$fields=array(
//'code'=>array('title'=>_('Code'),'db_name'=>'Invoice Public ID'),
//'date'=>array('title'=>_('date'),'db_name'=>'Invoice Date'),
//'customer'=>array('title'=>_('Customer'),'db_name'=>'Invoice Customer Name'),
//'customer_key'=>array('title'=>_('Customer Id'),'db_name'=>'Invoice Customer Key'),

//'paymentmethod'=>array('title'=>_('Payment Method'),'db_name'=>'Invoice Main Payment Method'),
//'invoicefor'=>array('title'=>_('Invoice For'),'db_name'=>'Invoice For'),
//'invoicepaid'=>array('title'=>_('Invoice Paid'),'db_name'=>'Invoice Paid'),

//'invoice_total_amount'=>array('title'=>_('Invoice Total Amount'),'db_name'=>'Invoice Total Amount'),
//'invoice_total_profit'=>array('title'=>_('Invoice Total Profit'),'db_name'=>'Invoice Total Profit'),
//'invoice_total_tax_amount'=>array('title'=>_('Invoice Total Tax Amount'),'db_name'=>'Invoice Total Tax Amount'),
//'invoice_total_tax_adjust_amount'=>array('title'=>_('Invoice Total Tax Adjust Amount'),'db_name'=>'Invoice Total Tax Adjust Amount'),
//'invoice_total_adjust_amount'=>array('title'=>_('Invoice Total Adjust Amount'),'db_name'=>'Invoice Total Adjust Amount')
//);
//foreach($fields as $key=>$value){
//if(!isset($fields_to_export[$key]) or  !$fields_to_export[$key]  )
//unset($fields[$key]);
//}



$data=array();
//$_data=array();
//foreach($fields as $key=>$options){
//$_data[]=$options['title'];
//}
$data[]=array(_('Invoice Number'),_('Date'),_('Customer'),_('Customer ID'),_('Tax Code'),_('Net'),_('Tax'));


$sql="select `Invoice Public ID`,`Invoice Date`,`Invoice Customer Name`,`Invoice Customer Key`,`Invoice Tax Code`,`Invoice Total Net Amount`,`Invoice Total Tax Amount` from `Invoice Dimension` I where $where $wheref";
$res=mysql_query($sql);
//print $sql;
while($row=mysql_fetch_assoc($res)){

$data[]=array($row['Invoice Public ID'],
strftime("%Y-%m-%d", strtotime($row['Invoice Date']." +00:00")),
$row['Invoice Customer Name'],
$row['Invoice Customer Key'],
$row['Invoice Tax Code'],
$row['Invoice Total Net Amount'],
$row['Invoice Total Tax Amount'],


);
}


return $data;

}

function get_orders_ready_to_pick_orders_data($wheref,$where='true'){

$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['orders']['ready_to_pick_dn']['csv_export'];
}


$fields=array(
'id'=>array('title'=>_('Order Id'),'db_name'=>'Delivery Note ID'),
'type'=>array('title'=>_('Type'),'db_name'=>'Delivery Note State'),
'date'=>array('title'=>_('Last Updated'),'db_name'=>'Delivery Note Date Created'),

'weight'=>array('title'=>_('Weight'),'db_name'=>'Delivery Note Estimated Weight'),
'picks'=>array('title'=>_('Picks'),'db_name'=>'Delivery Note Distinct Items'),
'customer_name'=>array('title'=>_('Customer Name'),'db_name'=>'Delivery Note Customer Name'),
'parcel_type'=>array('title'=>_('Parcel Type'),'db_name'=>'Delivery Note Parcel Type')
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
$sql="select * from `Delivery Note Dimension` where $where $wheref";
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


function get_orders_delivery_notes_data($wheref,$where='true'){

$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['orders']['dn']['csv_export'];
}


$fields=array(
'id'=>array('title'=>_('Delivery Note ID'),'db_name'=>'Delivery Note ID'),
'date'=>array('title'=>_('Date'),'db_name'=>'Delivery Note Date'),
'type'=>array('title'=>_('Type'),'db_name'=>'Delivery Note Type'),
'customer_name'=>array('title'=>_('Customer'),'db_name'=>'Delivery Note Customer Name'),
'weight'=>array('title'=>_('Weight(in kilograms)'),'db_name'=>'Delivery Note Weight'),
'parcels_no'=>array('title'=>_('Parcels'),'db_name'=>'Delivery Note Number Parcels'),

'start_picking_date'=>array('title'=>_('Start Picking Date'),'db_name'=>'Delivery Note Date Start Picking'),
'finish_picking_date'=>array('title'=>_('Finish Picking Date'),'db_name'=>'Delivery Note Date Finish Picking'),

'start_packing_date'=>array('title'=>_('Start Packing Date'),'db_name'=>'Delivery Note Date Start Packing'),
'finish_packing_date'=>array('title'=>_('Finish Packing Date'),'db_name'=>'Delivery Note Date Finish Packing'),

'state'=>array('title'=>_('State'),'db_name'=>'Delivery Note State'),
'dispatched_method'=>array('title'=>('Dispatch Method'),'db_name'=>'Delivery Note Dispatch Method'),
'parcel_type'=>array('title'=>_('Parcel Type'),'db_name'=>'Delivery Note Parcel Type'),
'boxes_no'=>array('title'=>_('Number Of Boxes'),'db_name'=>'Delivery Note Number Boxes')
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
$sql="select * from `Delivery Note Dimension` where $where $wheref";
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
function get_customers_data($wheref,$where='true'){

$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['stores']['customers']['csv_export'];
}


$fields=array(
'code'=>array('title'=>_('Code'),'db_name'=>'Store Code'),
'name'=>array('title'=>_('Store Name'),'db_name'=>'Store Name'),
//'total_customer_contacts'=>array('title'=>_('Total Customer Contacts'),'db_name'=>'Store Total Customer Contacts'),
'new_customer_contacts'=>array('title'=>_('New Customer Contacts'),'db_name'=>'Store New Contacts With Orders'),
//'total_customer'=>array('title'=>_('Store Total Customers'),'db_name'=>'Store Total Contacts'),
'active_customer'=>array('title'=>_('Active Customers'),'db_name'=>'Store Active Contacts'),
'new_customer'=>array('title'=>_('New Customers'),'db_name'=>'Store New Contacts'),
'lost_customer'=>array('title'=>_('Lost Customers'),'db_name'=>'Store Lost Contacts'),

'sales_all'=>array('title'=>_('Total Sales'),'db_name'=>'Store Total Invoiced Amount'),
'profit_all'=>array('title'=>_('Total Profit'),'db_name'=>'Store Total Profit'),
'sales_1y'=>array('title'=>_('Sales 1Y'),'db_name'=>'Store 1 Year Acc Invoiced Amount'),
'profit_1y'=>array('title'=>_('Profit 1Y'),'db_name'=>'Store 1 Year Acc Profit'),
'sales_1q'=>array('title'=>_('Sales 1Q'),'db_name'=>'Store 1 Quarter Acc Invoiced Amount'),
'profit_1q'=>array('title'=>_('Profit 1Q'),'db_name'=>'Store 1 Quarter Acc Profit'),
'sales_1m'=>array('title'=>_('Sales 1M'),'db_name'=>'Store 1 Month Acc Invoiced Amount'),
'profit_1m'=>array('title'=>_('Profit 1M'),'db_name'=>'Store 1 Month Acc Profit'),
'sales_1w'=>array('title'=>_('Sales 1W'),'db_name'=>'Store 1 Week Acc Invoiced Amount'),
'profit_1w'=>array('title'=>_('Profit 1W'),'db_name'=>'Store 1 Week Acc Profit')
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
$sql="select * from `Store Dimension` where $where $wheref";
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
function get_customerslist_data($wheref,$where='true'){

$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['customers']['table']['csv_export'];
}


$fields=array(
'id'=>array('title'=>_('Customer Id'),'db_name'=>'Customer Key'),
'name'=>array('title'=>_('Customer Name'),'db_name'=>'Customer Name'),
'location'=>array('title'=>_('Location'),'db_name'=>'Customer Main Delivery Address Town'),
'last_orders'=>array('title'=>_('Last Order'),'db_name'=>'Customer Last Order Date'),
'orders'=>array('title'=>_('Orders'),'db_name'=>'Customer Orders'),
'status'=>array('title'=>_('Status'),'db_name'=>'Customer Type by Activity')
/*'new_customer'=>array('title'=>_('New Customers'),'db_name'=>'Store New Customers'),
'lost_customer'=>array('title'=>_('Lost Customers'),'db_name'=>'Store Lost Customers'),

'sales_all'=>array('title'=>_('Total Sales'),'db_name'=>'Store Total Invoiced Amount'),
'profit_all'=>array('title'=>_('Total Profit'),'db_name'=>'Store Total Profit'),
'sales_1y'=>array('title'=>_('Sales 1Y'),'db_name'=>'Store 1 Year Acc Invoiced Amount'),
'profit_1y'=>array('title'=>_('Profit 1Y'),'db_name'=>'Store 1 Year Acc Profit'),
'sales_1q'=>array('title'=>_('Sales 1Q'),'db_name'=>'Store 1 Quarter Acc Invoiced Amount'),
'profit_1q'=>array('title'=>_('Profit 1Q'),'db_name'=>'Store 1 Quarter Acc Profit'),
'sales_1m'=>array('title'=>_('Sales 1M'),'db_name'=>'Store 1 Month Acc Invoiced Amount'),
'profit_1m'=>array('title'=>_('Profit 1M'),'db_name'=>'Store 1 Month Acc Profit'),
'sales_1w'=>array('title'=>_('Sales 1W'),'db_name'=>'Store 1 Week Acc Invoiced Amount'),
'profit_1w'=>array('title'=>_('Profit 1W'),'db_name'=>'Store 1 Week Acc Profit')*/
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
$sql="select * from `Customer Dimension` where $where $wheref";
$res=mysql_query($sql);
//Customer Type by Activity
while($row=mysql_fetch_assoc($res)){
$_data=array();
foreach($fields as $key=>$options){
//print $options['db_name'];
$_data[]=$row[$options['db_name']];
}
$data[]=$_data;
}
//print_r($data);exit;

return $data;

}
function get_parts_data($wheref){

$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['parts']['table']['csv_export'];
}


$fields=array(
'sku'=>array('title'=>_('SKU'),'db_name'=>'Part SKU'),
'used_in'=>array('title'=>_('Used In'),'db_name'=>'Part Currently Used In'),
'description'=>array('title'=>_('Discription'),'db_name'=>'Part Unit Description'),
'stock'=>array('title'=>_('Stock'),'db_name'=>'Part Current Stock'),
'stock_cost'=>array('title'=>_('Stock Cost'),'db_name'=>'Part Current Stock Cost'),

'unit'=>array('title'=>_('Part Unit'),'db_name'=>'Part Unit'),
'status'=>array('title'=>_('Part Status'),'db_name'=>'Part Status'),
'valid_from'=>array('title'=>_('Valid From'),'db_name'=>'Part Valid From'),
'valid_to'=>array('title'=>_('Valid To'),'db_name'=>'Part Valid To'),

'total_lost'=>array('title'=>_('Total Lost'),'db_name'=>'Part Total Lost'),
'total_broken'=>array('title'=>_('Total Broken'),'db_name'=>'Part Total Broken'),
'total_sold'=>array('title'=>_('Total Sold'),'db_name'=>'Part Total Sold'),
'total_given'=>array('title'=>_('Total Given'),'db_name'=>'Part Total Given'),

'sales_all'=>array('title'=>_('Total Sold Amount'),'db_name'=>'Part Total Sold Amount'),
'profit_all'=>array('title'=>_('Total Profit When Sold'),'db_name'=>'Part Total Profit When Sold'),

'sales_1y'=>array('title'=>_('Sales 1Y'),'db_name'=>'Part 1 Year Acc Sold'),
'profit_1y'=>array('title'=>_('Profit 1Y'),'db_name'=>'Part 1 Year Acc Profit When Sold'),
'sales_1q'=>array('title'=>_('Sales 1Q'),'db_name'=>'Part 1 Quarter Acc Sold'),
'profit_1q'=>array('title'=>_('Profit 1Q'),'db_name'=>'Part 1 Quarter Acc Profit When Sold'),
'sales_1m'=>array('title'=>_('Sales 1M'),'db_name'=>'Part 1 Month Acc Sold'),
'profit_1m'=>array('title'=>_('Profit 1M'),'db_name'=>'Part 1 Month Acc Profit When Sold'),
'sales_1w'=>array('title'=>_('Sales 1W'),'db_name'=>'Part 1 Week Acc Sold Amount'),
'profit_1w'=>array('title'=>_('Profit 1W'),'db_name'=>'Part 1 Week Acc Profit When Sold'),
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
$sql="select * from `Part Dimension` where true $wheref";
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
function get_deals_data($wheref){

$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['deals']['table']['csv_export'];
}


$fields=array(
'name'=>array('title'=>_('Name'),'db_name'=>'Deal Name'),
'trigger'=>array('title'=>_('Trigger'),'db_name'=>'Deal Trigger'),
'target'=>array('title'=>_('Target'),'db_name'=>'Deal Allowance Target'),
'status'=>array('title'=>_('Status'),'db_name'=>'Deal Status'),
'terms_description'=>array('title'=>_('Terms Description'),'db_name'=>'Deal Terms Description'),
'allowance_description'=>array('title'=>_('Allowance Description'),'db_name'=>'Deal Allowance Description'),
'terms_type'=>array('title'=>_('Terms Type'),'db_name'=>'Deal Terms Type'),

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
$sql="select * from `Deal Dimension` where true $wheref";
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
function get_suppliers_data($wheref,$where='true'){

$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['suppliers']['table']['csv_export'];
}


$fields=array(
'id'=>array('title'=>_('Id'),'db_name'=>'Supplier Key'),
'code'=>array('title'=>_('Code'),'db_name'=>'Supplier Code'),
'name'=>array('title'=>_('Name'),'db_name'=>'Supplier Name'),
'opo'=>array('title'=>_('Open Purchase Orders'),'db_name'=>'Supplier Open Purchase Orders'),

'contact_name'=>array('title'=>_('Contact Name'),'db_name'=>'Supplier Main Contact Name'),
'telephone'=>array('title'=>_('Telephone'),'db_name'=>'Supplier Main XHTML Telephone'),
'email'=>array('title'=>_('Email'),'db_name'=>'Supplier Main Plain Email'),
'currency'=>array('title'=>_('Currency'),'db_name'=>'Supplier Default Currency'),

'discontinued'=>array('title'=>_('Discontinued'),'db_name'=>'Supplier Discontinued Supplier Products'),
'surplus'=>array('title'=>_('Surplus'),'db_name'=>'Supplier Surplus Availability Products'),
'ok'=>array('title'=>_('Ok'),'db_name'=>'Supplier Optimal Availability Products'),
'low'=>array('title'=>_('Low'),'db_name'=>'Supplier Low Availability Products'),
'critical'=>array('title'=>_('Critical'),'db_name'=>'Supplier Critical Availability Products'),
'gone'=>array('title'=>_('Gone'),'db_name'=>'Supplier Out Of Stock Products'),

'cost_all'=>array('title'=>_('Total Costs'),'db_name'=>'Supplier Total Cost'),
'profit_all'=>array('title'=>_('Total Profit'),'db_name'=>'Supplier Total Parts Profit'),
'cost_1y'=>array('title'=>_('Costs 1Y'),'db_name'=>'Supplier 1 Year Acc Cost'),
'profit_1y'=>array('title'=>_('Profit 1Y'),'db_name'=>'Supplier 1 Year Acc Parts Profit'),
'cost_1q'=>array('title'=>_('Costs 1Q'),'db_name'=>'Supplier 1 Quarter Acc Cost'),
'profit_1q'=>array('title'=>_('Profit 1Q'),'db_name'=>'Supplier 1 Quarter Acc Parts Profit'),
'cost_1m'=>array('title'=>_('Costs 1M'),'db_name'=>'Supplier 1 Month Acc Cost'),
'profit_1m'=>array('title'=>_('Profit 1M'),'db_name'=>'Supplier 1 Month Acc Parts Profit'),
'cost_1w'=>array('title'=>_('Costs 1W'),'db_name'=>'Supplier 1 Week Acc Cost'),
'profit_1w'=>array('title'=>_('Profit 1W'),'db_name'=>'Supplier 1 Week Acc Parts Profit'),
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
$sql="select * from `Supplier Dimension` where $where $wheref";
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


function get_supplier_products_data($wheref,$where=' true'){

$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['supplier']['products']['csv_export'];
}


$fields=array(
'code'=>array('title'=>_('Code'),'db_name'=>'Supplier Product Code'),
'supplier'=>array('title'=>_('Supplier'),'db_name'=>'Supplier Name'),
'product_name'=>array('title'=>_('Product Name'),'db_name'=>'Supplier Product Name'),
'product_description'=>array('title'=>_('Product Description'),'db_name'=>'Supplier Product Description'),

'unit_type'=>array('title'=>_('Product Unit Type'),'db_name'=>'Supplier Product Unit Type'),
'currency'=>array('title'=>_('Currency'),'db_name'=>'Supplier Product Currency'),
'valid_from'=>array('title'=>_('Product Valid From'),'db_name'=>'Supplier Product Valid From'),
'valid_to'=>array('title'=>_('Product Valid To'),'db_name'=>'Supplier Product Valid To'),
'buy_state'=>array('title'=>_('Buy State'),'db_name'=>'Supplier Product Buy State'),

'cost_all'=>array('title'=>_('Total Cost'),'db_name'=>'Supplier Product Total Cost'),
'profit_all'=>array('title'=>_('Total Profit'),'db_name'=>'Supplier Product Total Parts Profit'),
'cost_1y'=>array('title'=>_('Cost 1Y'),'db_name'=>'Supplier Product 1 Year Acc Cost'),
'profit_1y'=>array('title'=>_('Profit 1Y'),'db_name'=>'Supplier Product 1 Year Acc Parts Profit'),
'cost_1q'=>array('title'=>_('Cost 1Q'),'db_name'=>'Supplier Product 1 Quarter Acc Cost'),
'profit_1q'=>array('title'=>_('Profit 1Q'),'db_name'=>'Supplier Product 1 Quarter Acc Parts Profit'),
'cost_1m'=>array('title'=>_('Cost 1M'),'db_name'=>'Supplier Product 1 Month Acc Cost'),
'profit_1m'=>array('title'=>_('Profit 1M'),'db_name'=>'Supplier Product 1 Month Acc Parts Profit'),
'cost_1w'=>array('title'=>_('Sales 1W'),'db_name'=>'Supplier Product 1 Week Acc Cost'),
'profit_1w'=>array('title'=>_('Cost 1W'),'db_name'=>'Supplier Product 1 Week Acc Parts Profit'),


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
$sql="select * from `Supplier Product Dimension` where $where $wheref";
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
function get_porders_data(){

$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['porder']['table']['csv_export'];
}


$fields=array(
'public_id'=>array('title'=>_('Order Id'),'db_name'=>'Purchase Order Public ID'),
'last_date'=>array('title'=>_('Last Updated'),'db_name'=>'Purchase Order Last Updated Date'),
'supplier'=>array('title'=>_('Supplier'),'db_name'=>'Purchase Order Supplier Name'),
'status'=>array('title'=>_('Status'),'db_name'=>'Purchase Order Current Dispatch State'),
'totaltax'=>array('title'=>_('Total Tax'),'db_name'=>'Purchase Order Total Tax Amount'),
'totalnet'=>array('title'=>_('Total Net'),'db_name'=>'Purchase Order Total Net Amount'),
'shippingmethod'=>array('title'=>_('Total Shipping'),'db_name'=>'Purchase Order Shipping Net Amount'),
'total'=>array('title'=>_('Total'),'db_name'=>'Purchase Order Total Amount'),
'buyername'=>array('title'=>_('Buyer Name'),'db_name'=>'Purchase Order Main Buyer Name'),
'sourcetype'=>array('title'=>_('Source Type'),'db_name'=>'Purchase Order Main Source Type'),
'paymentstate'=>array('title'=>_('Payment State'),'db_name'=>'Purchase Order Current Payment State'),
'actiontaken'=>array('title'=>_('Actions Taken'),'db_name'=>'Purchase Order Actions Taken'),
'items'=>array('title'=>_('Items'),'db_name'=>'Purchase Order Number Items'),
'currency_code'=>array('title'=>_('Currency'),'db_name'=>'Purchase Order Currency Code'),                                                             
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
$sql="select * from `Purchase Order Dimension` where true";
$res=mysql_query($sql);
//echo $sql;
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
function get_porder_invoices_data(){

$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['porder']['porder_invoices']['csv_export'];
}


$fields=array(
'code'=>array('title'=>_('Code'),'db_name'=>'Supplier Invoice Public ID'),
'date'=>array('title'=>_('Last Updated'),'db_name'=>'Supplier Invoice Last Updated Date'),
'name'=>array('title'=>_('Supplier Name'),'db_name'=>'Purchase Order Supplier Name'),
'items'=>array('title'=>_('Items'),'db_name'=>'Supplier Invoice Number Items'),
'currency'=>array('title'=>_('Currency'),'db_name'=>'Purchase Order Currency Code'),
'invoice_total_tax'=>array('title'=>_('Tax'),'db_name'=>'Purchase Order Total Tax Amount'),
'invoice_total_net_amount'=>array('title'=>_('Net Amount'),'db_name'=>'Purchase Order Total Net Amount'),
'invoice_total'=>array('title'=>_('Total Amount'),'db_name'=>'Purchase Order Total Amount')
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
$sql="select  SDND.`Supplier Invoice Last Updated Date`,SDND.`Supplier Invoice Current State`,SDND.`Supplier Invoice Key`,SDND.`Supplier Invoice Public ID`,SDND.`Supplier Invoice Number Items`,POD.`Purchase Order Public ID`,POD.`Purchase Order Supplier Name`,POD.`Purchase Order Total Amount`,POD.`Purchase Order Total Tax Amount`,POD.`Purchase Order Total Net Amount`,POD.`Purchase Order Currency Code` from  `Supplier Invoice Dimension` SDND left join `Purchase Order Transaction Fact` POTF on (SDND.`Supplier Invoice Key`=POTF.`Supplier Invoice Key`) left join `Purchase Order Dimension` POD on (POD.`Purchase Order Key`=POTF.`Purchase Order Key`)";
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

function get_porder_dn_data(){

$data=prepare_values($_REQUEST,array('fields'=>array('type'=>'json array','optional'=>true)));
if(isset($data['fields'])){
$fields_to_export=$data['fields'];
}else{
$fields_to_export=$_SESSION['state']['porder']['porder_dn']['csv_export'];
}


$fields=array(
'code'=>array('title'=>_('Code'),'db_name'=>'Supplier Delivery Note Public ID'),
'date'=>array('title'=>_('Last Updated'),'db_name'=>'Supplier Delivery Note Last Updated Date'),
'name'=>array('title'=>_('Supplier Name'),'db_name'=>'Purchase Order Supplier Name'),
'items'=>array('title'=>_('Items'),'db_name'=>'Supplier Delivery Note Number Items'),
'currency'=>array('title'=>_('Currency'),'db_name'=>'Purchase Order Currency Code'),
'invoice_total_tax'=>array('title'=>_('Tax'),'db_name'=>'Purchase Order Total Tax Amount'),
'invoice_total_net_amount'=>array('title'=>_('Net Amount'),'db_name'=>'Purchase Order Total Net Amount'),
'invoice_total'=>array('title'=>_('Total Amount'),'db_name'=>'Purchase Order Total Amount')
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
$sql="select  SDND.`Supplier Delivery Note Last Updated Date`,SDND.`Supplier Delivery Note Current State`,SDND.`Supplier Delivery Note Number Items`,SDND.`Supplier Delivery Note Key`,SDND.`Supplier Delivery Note Public ID`,POD.`Purchase Order Total Tax Amount`,SDND.`Supplier Delivery Note Number Items`,POD.`Purchase Order Public ID`,POD.`Purchase Order Supplier Name`,POD.`Purchase Order Total Amount`,POD.`Purchase Order Total Net Amount`,POD.`Purchase Order Currency Code` from  `Supplier Delivery Note Dimension` SDND left join `Purchase Order Transaction Fact` POTF on (SDND.`Supplier Delivery Note Key`=POTF.`Supplier Delivery Note Key`) left join `Purchase Order Dimension` POD on (POD.`Purchase Order Key`=POTF.`Purchase Order Key`)";
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
