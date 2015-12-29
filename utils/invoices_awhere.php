<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 28 December 2015 at 18:02:30 GMT+8, Kuala Lumour, Malaysia

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function invoices_awhere($awhere) {
	// $awhere=preg_replace('/\\\"/','"',$awhere);



	$where_data=array(
		//'product_ordered1'=>'∀',
		'invoice_date_from'=>'',
		'invoice_date_to'=>'',
		'invoice_paid_date_from'=>'',
		'invoice_paid_date_to'=>'',
		'billing_geo_constraints'=>'',
		'delivery_geo_constraints'=>'',
		'total_net_amount_lower'=>'',
		'total_net_amount_upper'=>'',
		'total_tax_amount_lower'=>'',
		'total_tax_amount_upper'=>'',
		'total_profit_lower'=>'',
		'total_profit_upper'=>'',
		'total_amount_lower'=>'',
		'total_amount_upper'=>'',
		'tax_code'=>'',
		'paid_status'=>array(),
		'not_paid_status'=>array(),
		'total_net_amount'=>array(),
		'total_tax_amount'=>array(),
		'total_profit'=>array(),
		'total_amount'=>array(),
		'category'=>array(),
		'store_key'=>false
	);

	//  $awhere=json_decode($awhere,TRUE);


	foreach ($awhere as $key=>$item) {
		$where_data[$key]=$item;
	}

	$where='where true';
	$table='`Invoice Dimension` I ';

	$use_product=false;
	//$use_categories =false;
	$use_otf =false;



	$where_billing_geo_constraints='';
	if ($where_data['billing_geo_constraints']!='') {
		$where_billing_geo_constraints=sprintf(" and `Order Billing To Country 2 Alpha Code`='%s'", $where_data['billing_geo_constraints']);
	}

	$where_delivery_geo_constraints='';
	if ($where_data['delivery_geo_constraints']!='') {
		$where_delivery_geo_constraints=sprintf(" and `Order Ship To Country Code`='%s'", $where_data['delivery_geo_constraints']);
	}

	$where_tax_code='';
	if ($where_data['tax_code']!='') {
		$where_delivery_geo_constraints=sprintf(" and `Invoice Tax Code`='%s'", $where_data['tax_code']);
	}


	$date_interval_invoice_created=prepare_mysql_dates($where_data['invoice_date_from'], $where_data['invoice_date_to'], '`Invoice Date`', 'only_dates');
	$date_interval_invoice_paid=prepare_mysql_dates($where_data['invoice_paid_date_from'], $where_data['invoice_paid_date_to'], '`Invoice Paid Date`', 'only_dates');


	$where='where ( true '.$date_interval_invoice_created['mysql'].$date_interval_invoice_paid['mysql'].") $where_billing_geo_constraints $where_delivery_geo_constraints $where_tax_code";
	//print $where;exit;


	$paid_status_where='';
	foreach ($where_data['paid_status'] as $paid_status) {
		switch ($paid_status) {
		case 'partially':
			$paid_status_where.=sprintf(" or `Invoice Paid`='Partially' ");
			break;
		case 'yes':
			$paid_status_where.=sprintf(" or `Invoice Paid`='Yes'  ");
			break;
		case 'no':
			$paid_status_where.=sprintf(" or  `Invoice Paid`='No'  ");
			break;

		}



	}
	$paid_status_where=preg_replace('/^\s*or/', '', $paid_status_where);
	if ($paid_status_where!='') {
		$where.="and ($paid_status_where)";
	}

	$not_paid_status_where='';
	foreach ($where_data['not_paid_status'] as $not_paid_status) {
		switch ($not_paid_status) {
		case 'partially':
			$not_paid_status_where.=sprintf(" or `Invoice Paid`!='Partially' ");
			break;
		case 'yes':
			$not_paid_status_where.=sprintf(" or `Invoice Paid`!='Yes'  ");
			break;
		case 'no':
			$not_paid_status_where.=sprintf(" or  `Invoice Paid`!='No'  ");
			break;
		}



	}
	$not_paid_status_where=preg_replace('/^\s*or/', '', $not_paid_status_where);
	if ($not_paid_status_where!='') {
		$where.="and ($not_paid_status_where)";
	}


	$total_net_amount_where='';
	foreach ($where_data['total_net_amount'] as $total_net_amount) {
		switch ($total_net_amount) {
		case 'less':
			$total_net_amount_where.=sprintf(" and `Invoice Total Net Amount`<'%s' ", $where_data['total_net_amount_lower']);
			break;
		case 'equal':
			$total_net_amount_where.=sprintf(" and `Invoice Total Net Amount`='%s'  ", $where_data['total_net_amount_lower']);
			break;
		case 'more':
			$total_net_amount_where.=sprintf(" and `Invoice Total Net Amount`>'%s'  ", $where_data['total_net_amount_upper']);
			break;
		case 'between':
			$total_net_amount_where.=sprintf(" and  `Invoice Total Net Amount`>'%s'  and `Invoice Total Net Amount`<'%s'", $where_data['total_net_amount_lower'], $where_data['total_net_amount_upper']);
			break;
		}
	}
	$total_net_amount_where=preg_replace('/^\s*and/', '', $total_net_amount_where);

	if ($total_net_amount_where!='') {
		$where.="and ($total_net_amount_where)";
	}


	$total_tax_amount_where='';
	foreach ($where_data['total_tax_amount'] as $total_tax_amount) {
		switch ($total_tax_amount) {
		case 'less':
			$total_tax_amount_where.=sprintf(" and `Invoice Total Tax Amount`<'%s' ", $where_data['total_tax_amount_lower']);
			break;
		case 'equal':
			$total_tax_amount_where.=sprintf(" and `Invoice Total Tax Amount`='%s'  ", $where_data['total_tax_amount_lower']);
			break;
		case 'more':
			$total_tax_amount_where.=sprintf(" and `Invoice Total Tax Amount`>'%s'  ", $where_data['total_tax_amount_upper']);
			break;
		case 'between':
			$total_tax_amount_where.=sprintf(" and  `Invoice Total Tax Amount`>'%s'  and `Invoice Total Tax Amount`<'%s'", $where_data['total_tax_amount_lower'], $where_data['total_tax_amount_upper']);
			break;
		}
	}
	$total_tax_amount_where=preg_replace('/^\s*and/', '', $total_tax_amount_where);

	if ($total_tax_amount_where!='') {
		$where.="and ($total_tax_amount_where)";
	}

	$total_profit_where='';
	foreach ($where_data['total_profit'] as $total_profit) {
		switch ($total_profit) {
		case 'less':
			$total_profit_where.=sprintf(" and `Invoice Total Profit`<'%s' ", $where_data['total_profit_lower']);
			break;
		case 'equal':
			$total_profit_where.=sprintf(" and `Invoice Total Profit`='%s'  ", $where_data['total_profit_lower']);
			break;
		case 'more':
			$total_profit_where.=sprintf(" and `Invoice Total Profit`>'%s'  ", $where_data['total_profit_upper']);
			break;
		case 'between':
			$total_profit_where.=sprintf(" and  `Invoice Total Profit`>'%s'  and `Invoice Total Profit`<'%s'", $where_data['total_profit_lower'], $where_data['total_profit_upper']);
			break;
		}
	}
	$total_profit_where=preg_replace('/^\s*and/', '', $total_profit_where);

	if ($total_profit_where!='') {
		$where.="and ($total_profit_where)";
	}

	$total_amount_where='';
	foreach ($where_data['total_amount'] as $total_amount) {
		switch ($total_amount) {
		case 'less':
			$total_amount_where.=sprintf(" and `Invoice Total Amount`<'%s' ", $where_data['total_amount_lower']);
			break;
		case 'equal':
			$total_amount_where.=sprintf(" and `Invoice Total Amount`='%s'  ", $where_data['total_amount_lower']);
			break;
		case 'more':
			$total_amount_where.=sprintf(" and `Invoice Total Amount`>'%s'  ", $where_data['total_amount_upper']);
			break;
		case 'between':
			$total_amount_where.=sprintf(" and  `Invoice Total Amount`>'%s'  and `Invoice Total Amount`<'%s'", $where_data['total_amount_lower'], $where_data['total_amount_upper']);
			break;
		}
	}
	$total_amount_where=preg_replace('/^\s*and/', '', $total_amount_where);

	if ($total_amount_where!='') {
		$where.="and ($total_amount_where)";
	}


	/*
    	   $total_net_amount_where='';
    foreach($where_data['total_net_amount'] as $total_net_amount) {
        switch ($total_net_amount) {
        case 'less':
            $total_net_amount_where.=sprintf(" and `Invoice Total Net Amount`<'%s' ",$where_data['total_net_amount_lower']);
            break;
        case 'equal':
            $total_net_amount_where.=sprintf(" and `Invoice Total Net Amount`='%s'  ",$where_data['total_net_amount_lower']);
            break;
        case 'more':
            $total_net_amount_where.=sprintf(" and `Invoice Total Net Amount`>'%s'  ",$where_data['total_net_amount_upper']);
            break;
    	case 'between':
    		$total_net_amount_where.=sprintf(" and  `Invoice Total Net Amount`>'%s'  and `Invoice Total Net Amount`<'%s'", $where_data['total_net_amount_lower'], $where_data['total_net_amount_upper']);
    		break;
    	}
    }
    $total_net_amount_where=preg_replace('/^\s*and/','',$total_net_amount_where);

    if($total_net_amount_where!=''){
    	$where.="and ($total_net_amount_where)";
    }
    */

	$category_where='';
	foreach ($where_data['category'] as $category) {
		$sql=sprintf("select `Subject Key` from `Category Bridge` where `Category Key`=%d", $category);
		$result=mysql_query($sql);
		$subject_keys=array();
		while ($row=mysql_fetch_array($result)) {
			$subject_keys[]=$row['Subject Key'];
		}
		$subject_keys=join(",", $subject_keys);
		//print_r($subject_keys);exit;
		$category_where.=sprintf(" and `Invoice Key` in ($subject_keys)");
	}
	$category_where=preg_replace('/^\s*and/', '', $category_where);

	if ($category_where!='') {
		$where.="and ($category_where)";
	}



	return array($where, $table);


}


?>