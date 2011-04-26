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


if(!$user->can_view('customers')){
  exit();
}

 


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"customer_list_export_".date("ymd_Hi").".csv\"");
$out = fopen('php://output', 'w');

$conf=$_SESSION['state']['customers']['list'];
   $awhere=$conf['where'];

  $awhere=preg_replace('/\\\"/','"',$awhere);
    //    print "$awhere";
    $awhere=json_decode($awhere,TRUE);
    // print_r($awhere);
    $where='where ';

    if ($awhere['product_ordered1']!='') {
        if ($awhere['product_ordered1']!='ANY') {
            $where_product_ordered1=extract_product_groups($awhere['product_ordered1']);
        } else
            $where_product_ordered1='true';
    } else
        $where_product_ordered1='false';

    if ($awhere['product_not_ordered1']!='') {
        if ($awhere['product_not_ordered1']!='ALL') {
            $where_product_not_ordered1=extract_product_groups($awhere['product_ordered1'],'P.`Product Code` not like','transaction.product_id not like','F.`Product Family Code` not like','P.`Product Family Key` like');
        } else
            $where_product_not_ordered1='false';
    } else
        $where_product_not_ordered1='true';

    if ($awhere['product_not_received1']!='') {
        if ($awhere['product_not_received1']!='ANY') {
            $where_product_not_received1=extract_product_groups($awhere['product_ordered1'],'(ordered-dispatched)>0 and    product.code  like','(ordered-dispatched)>0 and  transaction.product_id not like','(ordered-dispatched)>0 and  product_group.name not like','(ordered-dispatched)>0 and  product_group.id like');
        } else
            $where_product_not_received1=' ((ordered-dispatched)>0)  ';
    } else
        $where_product_not_received1='true';



    $date_interval1=prepare_mysql_dates($awhere['from1'],$awhere['to1'],'`Invoice Date`','only_dates');
    $where='where ('.$where_product_ordered1.' and '.$where_product_not_ordered1.' and '.$where_product_not_received1.$date_interval1['mysql'].") ";



    $sql="select `Customer Main Contact Name`,`Customer Main Plain Email`,`Customer Type`,`Customer Main XHTML Address`,`Customer Last Order Date`,`Customer Name`,`Customer Orders`,C.`Customer Key`,`Customer Main Location`,`Customer Main XHTML Email`,`Customer Main XHTML Telephone` from `Order Transaction Fact` OTF left join `Customer Dimension` C on (C.`Customer Key`=OTF.`Customer Key`) left join `Product History Dimension` PHD on (OTF.`Product Key`=PHD.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PHD.`Product ID`)   $where  group by C.`Customer Key`   ";


  
  $adata=array(
	     'id'=>_('ID')
	     ,'type'=>_('Type')
	     ,'name'=>_('Name')
	     ,'contact_name'=>_('Conatact')
	     ,'email'=>_('Email')
	     ,'telephone'=>_('Telephone')
	     ,'address'=>_('Address')

	     ,'orders'=>_('Orders')
	     ,'last_order'=>_('Last Order')
	       
	       
	       );
 fputcsv($out, $adata);

  
  $result=mysql_query($sql);
  while($data=mysql_fetch_array($result, MYSQL_ASSOC)){


  
$type=$data['Customer Type'];
  if($data['Customer Orders']==0)
      $last_order_date='';
    else
      $last_order_date=strftime("%d-%m-%Y", strtotime($data['Customer Last Order Date']));
  $adata=array(
	       'id'=>sprintf("%05d",$data['Customer Key'])
	       ,'type'=>$type
	       ,'name'=>$data['Customer Name']
	       ,'contact_name'=>$data['Customer Main Contact Name']
	       ,'email'=>$data['Customer Main Plain Email']
	       ,'telephone'=>$data['Customer Main XHTML Telephone']
	       ,'address'=>preg_replace('/\<br\/\>/',"\n",$data['Customer Main XHTML Address'])

	       ,'orders'=>number($data['Customer Orders'])
	       ,'last_order'=>$last_order_date
	       
	       
	       );
  fputcsv($out, $adata);
  }
mysql_free_result($result);









fclose($out);






?>