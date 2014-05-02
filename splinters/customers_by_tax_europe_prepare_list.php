<?php

$stores=join(',',$user->stores);

$where='where true';
$table='`Delivery Note Dimension` D ';
$wheref='';
$group='';

	$date_interval=prepare_mysql_dates($from,$to,'`Invoice Date`','only_dates');
	if ($date_interval['error']) {
		$date_interval=prepare_mysql_dates($_SESSION['state']['report_sales_with_no_tax']['from'],$_SESSION['state']['report_sales_with_no_tax']['to']);
	} else {
		$_SESSION['state']['report_sales_with_no_tax']['from']=$date_interval['from'];
		$_SESSION['state']['report_sales_with_no_tax']['to']=$date_interval['to'];
	}

	$where=sprintf(' where  `Invoice Store Key` in (%s) ',$stores);
	if ($from)$from=$from.' 00:00:00';
	if ($to)$to=$to.' 23:59:59';

	$where_interval=prepare_mysql_dates($from,$to,'`Invoice Date`');
	$where.=$where_interval['mysql'];


	$where_elements_tax_category='';

	$tax_categories=array();
	foreach ($elements_tax_category as $key=>$value) {
		if ($value) {
			$tax_categories[]=prepare_mysql($key);
		}
	}
	if (count($tax_categories)==0) {
		$where.=" and false ";
	}else {
		$where.=" and `Invoice Tax Code` in (".join($tax_categories,',').") ";

	}



	$where_elements_region='';

	if ($country=='GB') {
		if ($elements_region['GBIM']) {
			$where_elements_region.=' or `Invoice Billing Country 2 Alpha Code` in ("GB","IM")  ';
		}
		if ($elements_region['EU']) {
			$where_elements_region.=' or ( `Invoice Billing Country 2 Alpha Code` not in ("GB","IM") and `European Union`="Yes" ) ';
		}
		if ($elements_region['NOEU']) {
			$where_elements_region.=' or (`Invoice Billing Country 2 Alpha Code` not in ("GB","IM") and `European Union`="No")  ';
		}
	}else {

		if ($elements_region[$country]) {
			$where_elements_region.=sprintf(' or `Invoice Billing Country 2 Alpha Code` =%s  ',prepare_mysql($country));
		}
		if ($elements_region['EU']) {
			$where_elements_region.=sprintf(' or ( `Invoice Billing Country 2 Alpha Code`!=%s and `European Union`="Yes" ) ',prepare_mysql($country));
		}
		if ($elements_region['NOEU']) {
			$where_elements_region.=sprintf(' or (`Invoice Billing Country 2 Alpha Code`!=%s and `European Union`="No")  ',prepare_mysql($country));
		}

	}



	$where_elements_region=preg_replace('/^\s*or\s*/','',$where_elements_region);
	if ( $where_elements_region=='')
		$where_elements_region=' false ';
	$where.=" and ($where_elements_region) ";


	$wheref='';


	if ($f_field=='max' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Invoice Date`))<=".$f_value."    ";
	elseif ($f_field=='min' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Invoice Date`))>=".$f_value."    ";
	elseif ($f_field=='customer_name' and $f_value!='')
		$wheref.=" and  `Invoice Customer Name` like   '".addslashes($f_value)."%'";
	elseif ( $f_field=='public_id' and $f_value!='')
		$wheref.=" and  `Invoice Public ID` like '".addslashes($f_value)."%'";

	elseif ($f_field=='maxvalue' and is_numeric($f_value) )
		$wheref.=" and  `Invoice Total Amount`<=".$f_value."    ";
	elseif ($f_field=='minvalue' and is_numeric($f_value) )
		$wheref.=" and  `Invoice Total Amount`>=".$f_value."    ";






?>
