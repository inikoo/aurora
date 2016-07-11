<?php

$fields='';
$filter_msg='';
$wheref='';
$group_by='';

$currency='';


if (isset($parameters['excluded_stores']) and is_array($parameters['excluded_stores']) and count($parameters['excluded_stores'])>0)
	$where=sprintf(' where `Invoice Store Key` not in (%s)  ', join($parameters['excluded_stores'], ','));
else
	$where=' where true';


$table='`Invoice Dimension` I left join `Payment Account Dimension` P on (P.`Payment Account Key`=I.`Invoice Payment Account Key`)  ';
$where_type='';


if (isset($parameters['awhere']) and $parameters['awhere']) {

	include_once 'invoices_awhere.php';

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
elseif ($parameters['parent']=='account') {

	if ($parameters['tab']=='billingregion_taxcategory.invoices') {

		$fields='`Store Code`,`Store Name`,`Country Name`,';
		$table='`Invoice Dimension` I left join `Store Dimension` S on (S.`Store Key`=I.`Invoice Store Key`)  left join kbase.`Country Dimension` C on (I.`Invoice Billing Country 2 Alpha Code`=C.`Country 2 Alpha Code`)  left join `Payment Account Dimension` P on (P.`Payment Account Key`=I.`Invoice Payment Account Key`)'   ;

		$parents=preg_split('/_/', $parameters['parent_key']);
		$where=sprintf('where  `Invoice Type`="Invoice" and  `Invoice Billing Region`=%s and `Invoice Tax Code`=%s  ',
			prepare_mysql($parents[0]),
			prepare_mysql($parents[1])
		);


	}
	elseif ($parameters['tab']=='billingregion_taxcategory.refunds') {

		$fields='`Store Code`,`Store Name`,`Country Name`,';
		$table='`Invoice Dimension` I left join `Store Dimension` S on (S.`Store Key`=I.`Invoice Store Key`)  left join kbase.`Country Dimension` C on (I.`Invoice Billing Country 2 Alpha Code`=C.`Country 2 Alpha Code`)  left join `Payment Account Dimension` P on (P.`Payment Account Key`=I.`Invoice Payment Account Key`)'   ;

		$parents=preg_split('/_/', $parameters['parent_key']);
		$where=sprintf('where  `Invoice Type`!="Invoice"  and  `Invoice Billing Region`=%s and `Invoice Tax Code`=%s  ',
			prepare_mysql($parents[0]),
			prepare_mysql($parents[1])
		);


	}else {



		if (is_numeric($parameters['parent_key']) and in_array($parameters['parent_key'], $user->stores)) {

			if (count($user->stores)==0) {
				$where=' where false';
			}
			else {

				$where=sprintf('where  `Invoice Store Key` in (%s)  ', join(',', $user->stores));

			}
		}
	}
}
elseif ($parameters['parent']=='order') {

	$table='`Order Invoice Bridge` B left join   `Invoice Dimension` I  on (I.`Invoice Key`=B.`Invoice Key`)     left join `Payment Account Dimension` P on (P.`Payment Account Key`=I.`Invoice Payment Account Key`)';
	$where=sprintf('where  B.`Order Key`=%d  ', $parameters['parent_key']);

}
elseif ($parameters['parent']=='delivery_note') {

	$table='`Invoice Delivery Note Bridge` B left join   `Invoice Dimension` I  on (I.`Invoice Key`=B.`Invoice Key`)     left join `Payment Account Dimension` P on (P.`Payment Account Key`=I.`Invoice Payment Account Key`)';
	$where=sprintf('where  B.`Delivery Note Key`=%d  ', $parameters['parent_key']);

}
else {

	exit("unknown parent ".$parameters['parent']." \n");
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






?>
