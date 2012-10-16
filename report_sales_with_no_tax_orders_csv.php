<?php
/*
 File: customer_csv.php 

 Customer CSV data for export proprces

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2010, Inikoo 
 
 Version 2.0
*/

include_once('common.php');
if(!$user->can_view('orders')){
  exit();
}

 if(isset($_REQUEST['year']) and preg_match('/\d{2,4}/',$_REQUEST['year'])){
$year=$_REQUEST['year'];
$_SESSION['state']['report_data']['ES1']['year']=$year;
}


if(isset($_REQUEST['umbral'])){
list($tmp,$umbral)=parse_money($_REQUEST['umbral']);
$_SESSION['state']['report_data']['ES1']['umbral']=$umbral;
}


$year=$_SESSION['state']['report_data']['ES1']['year'];
$umbral=$_SESSION['state']['report_data']['ES1']['umbral'];


   


//header("Content-type: application/octet-stream");
//header("Content-Disposition: attachment; filename=\"modelo_347-".$year.".csv\"");
$out = fopen('php://output', 'w');

$where=sprintf(' where `Customer Main Country Code`="ESP"   and Year(`Invoice Date`)=%d',$year );

  $sql="select   `Customer Main Location`,`Customer Key`,`Customer Name`,`Customer ID`,`Customer Main XHTML Email`,count(DISTINCT `Invoice Key`) as invoices,sum(`Invoice Total Amount`) as total from  `Invoice Dimension` I left join  `Customer Dimension` C  on (I.`Invoice Customer Key`=C.`Customer Key`)  $where group by `Customer Key` order by total desc";
   $adata=array();
  
  print $sql;
  
  $result=mysql_query($sql);
  while($data=mysql_fetch_array($result, MYSQL_ASSOC)){

if($data['total']<$umbral)
break;  

  
    $adata=array(
		   'id'=>$myconf['customer_id_prefix'].sprintf("%05d",$data['Customer ID']),
		   'name'=>$data['Customer Name'],
		   'total'=>$data['total'],
		   'invoices'=>$data['invoices'],
		  

		   );
		   fputcsv($out, $adata);
  }
mysql_free_result($result);









fclose($out);






?>