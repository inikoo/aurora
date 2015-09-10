<?php
/*
 File: customer_csv.php

 Customer CSV data for export proprces

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Inikoo

 Version 2.0
*/

include_once 'common.php';
if (!$user->can_view('orders')) {
	exit();
}

if (isset($_REQUEST['year']) and preg_match('/\d{2,4}/',$_REQUEST['year'])) {
	$year=$_REQUEST['year'];
	$_SESSION['state']['report_data']['ES1']['year']=$year;
}


if (isset($_REQUEST['umbral'])) {
	list($tmp,$umbral)=parse_money($_REQUEST['umbral']);
	$_SESSION['state']['report_data']['ES1']['umbral']=$umbral;
}


$year=$_SESSION['state']['report_data']['ES1']['year'];
$umbral=$_SESSION['state']['report_data']['ES1']['umbral'];





header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"modelo_347-".$year.".csv\"");
$out = fopen('php://output', 'w');
$wheref='';
$where=' where true ';
$where.=sprintf(' and `Customer Main Country Code`="ESP"   and Year(`Invoice Date`)=%d',$year );
$sql="select  GROUP_CONCAT(`Invoice Key`) as invoice_keys,sum(`Invoice Total Tax Adjust Amount`) as adjust_tax,`Customer Main Location`,`Customer Key`,`Customer Name`,`Customer Main XHTML Email`,count(DISTINCT `Invoice Key`) as invoices,sum(`Invoice Total Amount`) as total, sum(`Invoice Total Net Amount`) as net from  `Invoice Dimension` I left join  `Customer Dimension` C  on (I.`Invoice Customer Key`=C.`Customer Key`)  $where $wheref  group by `Customer Key` order by total desc";
//   print $sql;
$adata=array();
$adata=array(
	'name'=>'Customer Name',
	'total'=>'total',
	'net'=>'net',
	'tax1'=>'tax1',
	'tax2'=>'tax2',
	'invoices'=>'invoices'




);

fputcsv($out, $adata);
$total=0;
$result=mysql_query($sql);
while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

	if ($data['total']<$umbral)
		break;
	$total++;

	$tax1=0;
	$tax2=0;

	$sql2=sprintf("select `Tax Code`,sum(`Tax Amount`) as amount from `Invoice Tax Bridge` where `Invoice Key` in (%s) group by `Tax Code`  ", $data['invoice_keys']);
	$res2=mysql_query($sql2);
	//print "$sql2<br>";
	$tax1=0;
	$tax2=0;

	while ($row2=mysql_fetch_array($res2)) {
			if ($row2['Tax Code']=='S1') {
				$tax1+=$row2['amount'];
			}
			elseif ($row2['Tax Code']=='S2') {
				$tax2+=$row2['amount'];
			}
			elseif ($row2['Tax Code']=='S3') {
				$tax1+=0.8*$row2['amount'];
				$tax2+=0.2*$row2['amount'];
			}elseif ($row2['Tax Code']=='S4') {
				$tax1+=$row2['amount'];
			}elseif ($row2['Tax Code']=='S5') {
				$tax1+=0.81818181*$row2['amount'];
				$tax2+=0.18181818*$row2['amount'];
			}elseif ($row2['Tax Code']=='UNK') {
				$tax1+=$row2['amount'];
			}
			
		
		
	}

	if ($tax2>0 and $tax1==0) {
		$tax2+=$data['adjust_tax'];

	}else {

		$tax1+=$data['adjust_tax'];
	}


	$adata=array(
		//'id'=>$myconf['customer_id_prefix'].sprintf("%05d",$data['Customer ID']),
		'name'=>$data['Customer Name'],
		'total'=>$data['total'],
		'net'=>$data['net'],
		'tax1'=>$tax1,
		'tax2'=>$tax2,
		'invoices'=>$data['invoices'],




	);
	fputcsv($out, $adata);
}
mysql_free_result($result);









fclose($out);






?>
