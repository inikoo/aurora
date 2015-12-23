<?php

$fields='';
$filter_msg='';
$wheref='';

$currency='';
$where='where true';
$table='`Invoice Dimension` I left join `Payment Account Dimension` P on (P.`Payment Account Key`=I.`Invoice Payment Account Key`)';
$where_type='';


if (isset($parameters['awhere']) and $parameters['awhere']) {



	$tmp=preg_replace('/\\\"/', '"', $parameters['awhere']);
	$tmp=preg_replace('/\\\\\"/', '"', $tmp);
	$tmp=preg_replace('/\'/', "\'", $tmp);

	$raw_data=json_decode($tmp, true);
	//$raw_data['store_key']=$store;
	//print_r( $raw_data);exit;
	list($where, $table)=invoices_awhere($raw_data);


}
elseif ($parameters['parent']=='category') {
	$category=new Category($parameters['parent_key']);

	if (!in_array($category->data['Category Store Key'], $user->stores)) {
		return;
	}

	$where=sprintf(" where `Subject`='Invoice' and  `Category Key`=%d", $parameters['parent_key']);
	$table=' `Category Bridge` left join  `Invoice Dimension` I on (`Subject Key`=`Invoice Key`) ';
	$where_type='';

	$store_key=$category->data['Category Store Key'];

}
elseif ($parameters['parent']=='list') {
	$sql=sprintf("select * from `List Dimension` where `List Key`=%d", $parameters['parent_key']);

	$res=mysql_query($sql);
	if ($list_data=mysql_fetch_assoc($res)) {
		$parameters['awhere']=false;
		$store_key=$list_data['List Parent Key'];
		if ($list_data['List Type']=='Static') {
			$table='`List Invoice Bridge` OB left join `Invoice Dimension` I  on (OB.`Invoice Key`=I.`Invoice Key`)';
			$where_type=sprintf(' and `List Key`=%d ', $parameters['parent_key']);

		} else {// Dynamic by DEFAULT



			$tmp=preg_replace('/\\\"/', '"', $list_data['List Metadata']);
			$tmp=preg_replace('/\\\\\"/', '"', $tmp);
			$tmp=preg_replace('/\'/', "\'", $tmp);

			$raw_data=json_decode($tmp, true);

			//$raw_data['store_key']=$store;
			list($where, $table)=invoices_awhere($raw_data);




		}

	} else {
		exit("error");
	}
}
elseif ($parameters['parent']=='store') {
	if (is_numeric($parameters['parent_key']) and in_array($parameters['parent_key'], $user->stores)) {
		$where=sprintf(' where  `Invoice Store Key`=%d ', $parameters['parent_key']);
		include_once 'class.Store.php';
		$store=new Store($parameters['parent_key']);
		$currency=$store->data['Store Currency Code'];
	}
	else {
		$where.=sprintf(' and  false');
	}


}
elseif ($parameters['parent']=='stores') {
	if (is_numeric($parameters['parent_key']) and in_array($parameters['parent_key'], $user->stores)) {

		if (count($user->stores)==0) {
			$where=' where false';
		}
		else {

			$where=sprintf('where  `Invoice Store Key` in (%s)  ', join(',', $user->stores));

		}
	}
}elseif ($parameters['parent']=='order') {

	$table='`Order Invoice Bridge` B left join   `Invoice Dimension` I  on (I.`Invoice Key`=B.`Invoice Key`)     left join `Payment Account Dimension` P on (P.`Payment Account Key`=I.`Invoice Payment Account Key`)';
	$where=sprintf('where  B.`Order Key`=%d  ', $parameters['parent_key']);

}elseif ($parameters['parent']=='delivery_note') {

	$table='`Invoice Delivery Note Bridge` B left join   `Invoice Dimension` I  on (I.`Invoice Key`=B.`Invoice Key`)     left join `Payment Account Dimension` P on (P.`Payment Account Key`=I.`Invoice Payment Account Key`)';
	$where=sprintf('where  B.`Delivery Note Key`=%d  ', $parameters['parent_key']);

}elseif ($parameters['parent']=='billingregion_taxcategory.invoices') {

	$fields='`Store Code`,`Store Name`,`Country Name`,';
	$table='`Invoice Dimension` I left join `Store Dimension` S on (S.`Store Key`=I.`Invoice Store Key`)  left join kbase.`Country Dimension` C on (I.`Invoice Billing Country 2 Alpha Code`=C.`Country 2 Alpha Code`) '   ;

	$parents=preg_split('/_/', $parameters['parent_key']);
	$where=sprintf('where  `Invoice Type`="Invoice" and  `Invoice Billing Region`=%s and `Invoice Tax Code`=%s  ',
		prepare_mysql($parents[0]),
		prepare_mysql($parents[1])
	);


}elseif ($parameters['parent']=='billingregion_taxcategory.refunds') {

	$fields='`Store Code`,`Store Name`,`Country Name`,';
	$table='`Invoice Dimension` I left join `Store Dimension` S on (S.`Store Key`=I.`Invoice Store Key`)  left join kbase.`Country Dimension` C on (I.`Invoice Billing Country 2 Alpha Code`=C.`Country 2 Alpha Code`) '   ;

	$parents=preg_split('/_/', $parameters['parent_key']);
	$where=sprintf('where  `Invoice Type`!="Invoice"  and  `Invoice Billing Region`=%s and `Invoice Tax Code`=%s  ',
		prepare_mysql($parents[0]),
		prepare_mysql($parents[1])
	);


}else {
	exit("unknown parent\n");
}



if (isset($parameters['period'])) {
	include_once 'utils/date_functions.php';
	list($db_interval, $from, $to, $from_date_1yb, $to_1yb)=calculate_interval_dates($parameters['period'], $parameters['from'], $parameters['to']);
	$where_interval=prepare_mysql_dates($from, $to, '`Invoice Date`');
	$where.=$where_interval['mysql'];

}




if (isset($parameters['elements'])) {
	$elements=$parameters['elements'];


	switch ($parameters['elements_type']) {

	case('type'):
		$_elements='';
		$num_elements_checked=0;
		foreach ($elements['type']['items'] as $_key=>$_value) {
			if ($_value['selected']) {
				$num_elements_checked++;

				$_elements.=", '$_key'";
			}
		}

		if ($_elements=='') {
			$where.=' and false' ;
		}elseif ($num_elements_checked==2) {

		}else {
			$_elements=preg_replace('/^,/', '', $_elements);
			$where.=' and `Invoice Type` in ('.$_elements.')' ;
		}
		break;
	case('payment'):
		$_elements='';
		$num_elements_checked=0;

		foreach ($elements['payment']['items'] as $_key=>$_value) {
			if ($_value['selected']) {
				$num_elements_checked++;

				$_elements.=", '$_key'";
			}
		}
		if ($_elements=='') {
			$where.=' and false' ;
		}elseif ($num_elements_checked==3) {

		}else {
			$_elements=preg_replace('/^,/', '', $_elements);
			$where.=' and `Invoice Paid` in ('.$_elements.')' ;
		}
		break;
	}

}





if (($parameters['f_field']=='customer'     )  and $f_value!='') {
	$wheref=sprintf('  and  `Invoice Customer Name`  REGEXP "[[:<:]]%s" ', addslashes($f_value));
}
elseif ($parameters['f_field']=='number'  and $f_value!='' )
	$wheref.=" and  `Invoice Public ID` like '".addslashes(preg_replace('/\s*|\,|\./', '', $f_value))."%' ";
elseif ($parameters['f_field']=='last_more' and is_numeric($f_value) )
	$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Invoice Date`))>=".$f_value."    ";
elseif ($parameters['f_field']=='last_less' and is_numeric($f_value) )
	$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Invoice Date`))<=".$f_value."    ";
elseif ($parameters['f_field']=='maxvalue' and is_numeric($f_value) )
	$wheref.=" and  `Invoice Total Amount`<=".$f_value."    ";
elseif ($parameters['f_field']=='minvalue' and is_numeric($f_value) )
	$wheref.=" and  `Invoice Total Amount`>=".$f_value."    ";
elseif ($parameters['f_field']=='country' and  $f_value!='') {
	if ($f_value=='UNK') {
		$wheref.=" and  `Invoice Billing Country Code`='".$f_value."'    ";
		$find_data=' '._('a unknown country');
	} else {

		$f_value=Address::parse_country($f_value);
		if ($f_value!='UNK') {
			$wheref.=" and  `Invoice Billing Country Code`='".$f_value."'    ";
			$country=new Country('code', $f_value);
			$find_data=' '.$country->data['Country Name'].' <img src="/art/flags/'.$country->data['Country 2 Alpha Code'].'.png" alt="'.$country->data['Country Code'].'"/>';
		}

	}
}

$_order=$order;
$_dir=$order_direction;


if ($order=='date')
	$order='`Invoice Date`';
elseif ($order=='last_date')
	$order='`Invoice Last Updated Date`';
elseif ($order=='number')
	$order='`Invoice File As`';

elseif ($order=='total_amount')
	$order='`Invoice Total Amount`';

elseif ($order=='items')
	$order='`Invoice Items Net Amount`';
elseif ($order=='shipping')
	$order='`Invoice Shipping Net Amount`';

elseif ($order=='customer')
	$order='`Invoice Customer Name`';
elseif ($order=='method')
	$order='`Invoice Main Payment Method`';
elseif ($order=='type')
	$order='`Invoice Type`';
elseif ($order=='state')
	$order='`Invoice Paid`';
elseif ($order=='net')
	$order='`Invoice Total Net Amount`';
elseif ($order=='tax')
	$order='`Invoice Total Tax Amount`';
elseif ($order=='store_code')
	$order='`Store Code`';
else
	$order='I.`Invoice Key`';


$fields.='`Invoice Key`,`Invoice Paid`,`Invoice Type`,`Invoice Main Payment Method`,`Invoice Store Key`,`Invoice Customer Key`,`Invoice Public ID`,`Invoice Customer Name`,`Invoice Date`,`Invoice Total Amount`,`Invoice Currency`,
`Invoice Total Net Amount`,`Invoice Total Tax Amount`,`Invoice Shipping Net Amount`,`Invoice Items Net Amount`,`Invoice Total Net Amount`,`Invoice Shipping Net Amount`,
`Invoice Billing Country 2 Alpha Code`,`Invoice Delivery Country 2 Alpha Code`
';
$sql_totals="select count(Distinct I.`Invoice Key`) as num from $table   $where $wheref ";


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



	//print $table. $where; exit;
	return array($where, $table);


}


?>
