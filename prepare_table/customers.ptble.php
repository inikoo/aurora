<?php

$currency='';
$where='where true';
$table='`Customer Dimension` C ';
$group_by='';
$where_type='';


if ($awhere) {

	$tmp=preg_replace('/\\\"/','"',$awhere);
	$tmp=preg_replace('/\\\\\"/','"',$tmp);
	$tmp=preg_replace('/\'/',"\'",$tmp);

	$raw_data=json_decode($tmp, true);
	$raw_data['store_key']=$parent_key;
	include_once 'list_functions_customer.php';
	list($where,$table,$group_by)=customers_awhere($raw_data);




}
elseif ($parent=='list') {


	$sql=sprintf("select * from `List Dimension` where `List Key`=%d",$parent_key);

	$res=mysql_query($sql);
	if ($customer_list_data=mysql_fetch_assoc($res)) {
		$awhere=false;
		if ($customer_list_data['List Type']=='Static') {
			$table='`List Customer Bridge` CB left join `Customer Dimension` C  on (CB.`Customer Key`=C.`Customer Key`)';
			$where=sprintf(' where `List Key`=%d ',$parent_key);

		} else {

			$tmp=preg_replace('/\\\"/','"',$customer_list_data['List Metadata']);
			$tmp=preg_replace('/\\\\\"/','"',$tmp);
			$tmp=preg_replace('/\'/',"\'",$tmp);

			$raw_data=json_decode($tmp, true);

			$raw_data['store_key']=$customer_list_data['List Parent Key'];
			include_once 'utils/list_functions_customer.php';

			list($where,$table,$group_by)=customers_awhere($raw_data);


		}

	} else {
		return;
	}



}
elseif ($parent=='category') {

	include_once 'class.Category.php';
	$category=new Category($parent_key);

	if (!in_array($category->data['Category Store Key'],$user->stores)) {
		return;
	}
	$where_type='';
	if ($orders_type=='contacts_with_orders') {
		$where_type=' and `Customer With Orders`="Yes" ';
	}
	$where=sprintf(" where `Subject`='Customer' and  `Category Key`=%d",$parent_key);
	$table=' `Category Bridge` left join  `Customer Dimension` C on (`Subject Key`=`Customer Key`) ';

}
elseif ($parent=='store') {

	if (in_array($parent_key,$user->stores))
		$where_stores=sprintf(' and  `Customer Store Key`=%d ',$parent_key);
	else
		$where_stores=' and false';

	$store=new Store($parent_key);
	$currency=$store->data['Store Currency Code'];
	$where.=$where_stores;
}
else {

	if (count($user->stores)==0)
		$where_stores=sprintf(' and  false');
	else
		$where_stores=sprintf(' and `Customer Store Key` in (%s)  ',join(',',$user->stores));
	$where.=$where_stores;
}






$where_type='';
//if ($orders_type=='contacts_with_orders') {
// $where_type=' and `Customer With Orders`="Yes" ';
//}
switch ($elements_type) {
case 'activity':
	$_elements='';
	$count_elements=0;
	foreach ($elements['activity'] as $_key=>$_value) {
		if ($_value) {
			$count_elements++;
			$_elements.=','.prepare_mysql($_key);

		}
	}
	$_elements=preg_replace('/^\,/','',$_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	} elseif ($count_elements<3) {
		$where.=' and `Customer Type by Activity` in ('.$_elements.')' ;
	}
	break;
case 'level_type':
	$_elements='';
	$count_elements=0;
	foreach ($elements['level_type'] as $_key=>$_value) {
		if ($_value) {
			$count_elements++;
			$_elements.=','.prepare_mysql($_key);

		}
	}
	$_elements=preg_replace('/^\,/','',$_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	} elseif ($count_elements<4) {
		$where.=' and `Customer Level Type` in ('.$_elements.')' ;
	}
	break;
case 'location':
	$_elements='';
	$count_elements=0;
	foreach ($elements['location'] as $_key=>$_value) {
		if ($_value) {
			$count_elements++;
			$_elements.=','.prepare_mysql($_key);

		}
	}
	$_elements=preg_replace('/^\,/','',$_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	} elseif ($count_elements<2) {
		$where.=' and `Customer Location Type` in ('.$_elements.')' ;
	}
	break;



}


$filter_msg='';
$wheref='';


if (($f_field=='name'     )  and $f_value!='') {
	$wheref=sprintf('  and  `Customer Name`  REGEXP "[[:<:]]%s" ',addslashes($f_value));


}
elseif (($f_field=='postcode'     )  and $f_value!='') {
	$wheref="  and  `Customer Main Postal Code` like '%".addslashes($f_value)."%'";
}
elseif ($f_field=='id'  )
	$wheref.=" and  `Customer Key` like '".addslashes(preg_replace('/\s*|\,|\./','',$f_value))."%' ";
elseif ($f_field=='last_more' and is_numeric($f_value) )
	$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))>=".$f_value."    ";
elseif ($f_field=='last_less' and is_numeric($f_value) )
	$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))<=".$f_value."    ";
elseif ($f_field=='max' and is_numeric($f_value) )
	$wheref.=" and  `Customer Orders`<=".$f_value."    ";
elseif ($f_field=='min' and is_numeric($f_value) )
	$wheref.=" and  `Customer Orders`>=".$f_value."    ";
elseif ($f_field=='maxvalue' and is_numeric($f_value) )
	$wheref.=" and  `Customer Net Balance`<=".$f_value."    ";
elseif ($f_field=='minvalue' and is_numeric($f_value) )
	$wheref.=" and  `Customer Net Balance`>=".$f_value."    ";
elseif ($f_field=='country' and  $f_value!='') {
	if ($f_value=='UNK') {
		$wheref.=" and  `Customer Main Country Code`='".$f_value."'    ";
		$find_data=' '._('a unknown country');
	} else {

		$f_value=Address::parse_country($f_value);
		if ($f_value!='UNK') {
			$wheref.=" and  `Customer Main Country Code`='".$f_value."'    ";
			$country=new Country('code',$f_value);
			$find_data=' '.$country->data['Country Name'].' <img src="art/flags/'.$country->data['Country 2 Alpha Code'].'.png" alt="'.$country->data['Country Code'].'"/>';
		}

	}
}

$_order=$order;
$_dir=$order_direction;
if ($order=='name')
	$order='`Customer File As`';


elseif ($order=='formated_id')
	$order='C.`Customer Key`';
elseif ($order=='location')
	$order='`Customer Main Location`';
elseif ($order=='orders')
	$order='`Customer Orders`';
elseif ($order=='email')
	$order='`Customer Main Plain Email`';
elseif ($order=='telephone')
	$order='`Customer Main Plain Telephone`';
elseif ($order=='mobile')
	$order='`Customer Main Plain Mobile`';
elseif ($order=='last_order')
	$order='`Customer Last Order Date`';
elseif ($order=='last_invoice')
	$order='`Customer Last Invoiced Order Date';


elseif ($order=='contact_name')
	$order='`Customer Main Contact Name`';
elseif ($order=='company_name')
	$order='`Customer Company Name`';
elseif ($order=='address')
	$order='`Customer Main Plain Address`';
elseif ($order=='town')
	$order='`Customer Main Town`';
elseif ($order=='postcode')
	$order='`Customer Main Postal Code`';
elseif ($order=='region')
	$order='`Customer Main Country First Division`';
elseif ($order=='country')
	$order='`Customer Main Country`';
//  elseif($order=='ship_address')
//  $order='`customer main ship to header`';
elseif ($order=='ship_town')
	$order='`Customer Main Delivery Address Town`';
elseif ($order=='ship_postcode')
	$order='`Customer Main Delivery Address Postal Code`';
elseif ($order=='ship_region')
	$order='`Customer Main Delivery Address Country Region`';
elseif ($order=='ship_country')
	$order='`Customer Main Delivery Address Country`';
elseif ($order=='net_balance')
	$order='`Customer Net Balance`';
elseif ($order=='balance')
	$order='`Customer Outstanding Net Balance`';
elseif ($order=='total_profit')
	$order='`Customer Profit`';
elseif ($order=='customer_balance')
	$order='`Customer Account Balance`';


elseif ($order=='total_payments')
	$order='`Customer Net Payments`';
elseif ($order=='top_profits')
	$order='`Customer Profits Top Percentage`';
elseif ($order=='top_balance')
	$order='`Customer Balance Top Percentage`';
elseif ($order=='top_orders')
	$order='``Customer Orders Top Percentage`';
elseif ($order=='top_invoices')
	$order='``Customer Invoices Top Percentage`';
elseif ($order=='total_refunds')
	$order='`Customer Total Refunds`';
elseif ($order=='contact_since')
	$order='`Customer First Contacted Date`';
elseif ($order=='activity')
	$order='`Customer Type by Activity`';
elseif ($order=='logins')
	$order='`Customer Number Web Logins`';
elseif ($order=='failed_logins')
	$order='`Customer Number Web Failed Logins`';
elseif ($order=='requests')
	$order='`Customer Number Web Requests`';
elseif ($order=='invoices')
	$order='`Customer Orders Invoiced`';
else
	$order='`Customer File As`';


$sql_totals="select count(Distinct C.`Customer Key`) as num from $table  $where  $where_type";


?>
