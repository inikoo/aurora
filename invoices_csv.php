<?php
/*
 File: invoices_csv.php 

 Customer CSV data for export proprces

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2010, Inikoo 
 
 Version 2.0
*/


/**
**
 * MS-Excel stream handler
 * Excel export example
 * @author      Ignatius Teo            <ignatius@act28.com>
 * @copyright   (C)2004 act28.com       <http://act28.com>
 * @date        21 Oct 2004
 */

include_once('common.php');

if(!$user->can_view('orders')){
  exit();
}

 
      $conf=$_SESSION['state']['orders']['invoices'];
 
    if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
if(isset( $_REQUEST['where']))
     $where=$_REQUEST['where'];
   else
     $where=$conf['where'];
  
 if(isset( $_REQUEST['from']))
    $from=$_REQUEST['from'];
 else{
  
     $from=$_SESSION['state']['orders']['from'];
 }

  if(isset( $_REQUEST['to']))
    $to=$_REQUEST['to'];
  else{
 
      $to=$_SESSION['state']['orders']['to'];
  }


     if(isset( $_REQUEST['store_id'])    ){
       $store=$_REQUEST['store_id'];
       $_SESSION['state']['orders']['store']=$store;
     }else
       $store=$_SESSION['state']['orders']['store'];
     
     
  
 
 $date_interval=prepare_mysql_dates($from,$to,'`Invoice Date`','only_dates');
     if($date_interval['error']){
       $date_interval=prepare_mysql_dates($_SESSION['state']['orders']['from'],$_SESSION['state']['orders']['to']);
     }else{
       $_SESSION['state']['orders']['from']=$date_interval['from'];
       $_SESSION['state']['orders']['to']=$date_interval['to'];
 }



 if(is_numeric($store)){
     $where.=sprintf(' and `Invoice Store Key`=%d ',$store);
   }

   $where.=$date_interval['mysql'];
   

  

   $wheref='';
   
    if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Invoice Date`))<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Invoice Date`))>=".$f_value."    ";
   elseif($f_field=='customer_name' and $f_value!='')
    $wheref.=" and  `Invoice Customer Name` like   '".addslashes($f_value)."%'";
  elseif( $f_field=='public_id' and $f_value!='')
    $wheref.=" and  `Invoice Public ID` like '".addslashes($f_value)."%'";
   
else if($f_field=='maxvalue' and is_numeric($f_value) )
    $wheref.=" and  `Invoice Total Amount`<=".$f_value."    ";
  else if($f_field=='minvalue' and is_numeric($f_value) )
    $wheref.=" and  `Invoice Total Amount`>=".$f_value."    ";
   

   
 

header('Content-type: text/html; charset=utf16le');
header("Content-Disposition: attachment; filename=\"invoices.csv\"");

//$out = fopen('php://output', 'w');
$csv='';

$tax_data=array();

  $sql="select  I.`Invoice Key`,(select GROUP_CONCAT(`Tax Code`,',',`Tax Amount` SEPARATOR ':')  from `Invoice Tax Bridge` where `Invoice Key`=I.`Invoice Key`) as `Tax Spread`, `Invoice Total Net Amount`,`Invoice Has Been Paid In Full`,`Invoice Key`,`Invoice XHTML Orders`,`Invoice XHTML Delivery Notes`,`Invoice Public ID`,`Invoice Customer Key`,`Invoice Customer Name`,`Invoice Date`,`Invoice Total Amount`  from `Invoice Dimension` I  $where $wheref  ";
  // print $sql;

   $data=array();

   
   $res=mysql_query($sql);
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

     $order_id=sprintf('<a href="invoice.php?id=%d">%s</a>',$row['Invoice Key'],$row['Invoice Public ID']);
     $customer=sprintf('<a href="customer.php?id=%d">%s</a>',$row['Invoice Customer Key'],$row['Invoice Customer Name']);
     if($row['Invoice Has Been Paid In Full'])
       $state=_('Paid');
     else
       $state=_('No Paid');
//print $row['Tax Spread'];
$_tax_data=preg_split('/:/',$row['Tax Spread']);
foreach($_tax_data as $tax_pair){
$__tax_data=preg_split('/,/',$tax_pair);
$tax_codes[$__tax_data[0]]=$__tax_data[0];
$tax_data[$row['Invoice Key']][$__tax_data[0]]=$__tax_data[1];
}


     $data[]=array(
     'key'=>$row['Invoice Key']
		   ,'id'=>$row['Invoice Public ID']
		   ,'customer'=>$row['Invoice Customer Name']
		   ,'date'=>strftime("%e/%m/%Y", strtotime($row['Invoice Date']))
		   
		   ,'state'=>$state
		   //,'orders'=>$row['Invoice XHTML Orders']
		  // ,'dns'=>$row['Invoice XHTML Delivery Notes']
		   		   ,'total_amount'=>money($row['Invoice Total Amount'])

		   ,'net'=>money($row['Invoice Total Net Amount'])
		   
        //   ,'tax'=>$row['Tax Spread']

		   
		   );
		   
		   
		   //  print_r($data);
		//   fputcsv($out, $data);
   }
   
  
   
   foreach($data as $_data){
   
   foreach($tax_codes as $tax_code){
  
   if(array_key_exists($tax_code,$tax_data[$_data['key']])){
   $_data[$tax_code]=money($tax_data[$_data['key']][$tax_code]);
   }else{
   $_data[$tax_code]=money(0);
   }
   }
$_csv='';     
foreach($_data as $key=>$value){
$_csv.="\t".$value;
}

$csv.=preg_replace('/^\t/','',$_csv)."\n";

     // fputcsv($out, $_data,"\t");

//print_r($_data);

   }
   
mysql_free_result($res);


print chr(255).chr(254).mb_convert_encoding( $csv, 'UTF-16LE', 'UTF-8'); 







//fclose($out);






?>