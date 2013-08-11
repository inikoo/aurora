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

 $stores=$_SESSION['state']['report_sales_with_no_tax']['stores'];
$from=$_SESSION['state']['report_sales_with_no_tax']['customers']['from'];
$to=$_SESSION['state']['report_sales_with_no_tax']['customers']['to'];
$date_interval=prepare_mysql_dates($from,$to,'`Invoice Date`','only_dates');

    $corporate_currency='GBP';
   $sql=sprintf("select `Account Currency` from `Account Dimension` ");
   $res=mysql_query($sql);
   if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
     $corporate_currency=$row['Account Currency'];
   }   
     


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"report_sales_with_no_tax_customers.csv\"");
$out = fopen('php://output', 'w');


  $where=sprintf(' where `Invoice Total Tax Amount`=0 and `Invoice Total Amount`!=0  and `Invoice Store Key` in (%s) ',$stores);
   $where.=$date_interval['mysql'];
  

$sql="select  sum( (select `Exchange` from kbase.`HM Revenue and Customs Currency Exchange Dimension` `HM E` where DATE_FORMAT(`HM E`.`Date`,'%%m%%Y')  =DATE_FORMAT(`Invoice Date`,'%%m%%Y') and `Currency Pair`=Concat(`Invoice Currency`,'GBP') limit 1  )*`Invoice Total Amount`) as `Invoice Total Amount Corporate HM Revenue and Customs`  ,  `Invoice Currency`,`Customer Tax Number`,`European Union`,`Invoice Delivery Country 2 Alpha Code`,count(distinct `Invoice Key`) as `Invoices` ,`Country Name`,`Country Code`,`Invoice Customer Key`,`Invoice Customer Name`,`Invoice Date`,sum(`Invoice Total Amount`) as `Invoice Total Amount`,sum(`Invoice Total Amount`*`Invoice Currency Exchange`) as `Invoice Total Amount Corporate`  from `Invoice Dimension` left join kbase.`Country Dimension` on (`Invoice Delivery Country 2 Alpha Code`=`Country 2 Alpha Code`) left join `Customer Dimension` on (`Invoice Customer Key`=`Customer Key`) $where   group by `Invoice Customer Key`  ";


   $data=array();
  
  
  
  $result=mysql_query($sql);
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
 if($row['Invoice Total Amount Corporate HM Revenue and Customs']=='')
	 $total_amount_hmcr=_('No FX Data');
       else
	 $total_amount_hmcr=money($row['Invoice Total Amount Corporate HM Revenue and Customs'],'GBP');
    
    $data=array(

		   'name'=>$row['Invoice Customer Name']
		   ,'tax_number'=>$row['Customer Tax Number']
		   ,'date'=>strftime("%e %b %y", strtotime($row['Invoice Date']))
		   ,'total_amount_original_currency'=>money($row['Invoice Total Amount'],$row['Invoice Currency'])
	//	   ,'total_amount_corporate_currency'=>money($row['Invoice Total Amount Corporate'],$corporate_currency)
		   ,'total_amount_hmcr'=>$total_amount_hmcr
		   ,'send_to'=>$row['Country Name']
		   ,'eu'=>$row['European Union']
		   ,'num_invoices'=>number($row['Invoices'])
		  );
  
    
    fputcsv($out, $data);
  }
mysql_free_result($result);









fclose($out);






?>