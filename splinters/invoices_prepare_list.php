<?php

$filter_msg='';
$wheref='';

$currency='';
$where='where true';
$table='`Invoice Dimension` I ';
$where_type='';

if ($awhere) {



	$tmp=preg_replace('/\\\"/','"',$awhere);
	$tmp=preg_replace('/\\\\\"/','"',$tmp);
	$tmp=preg_replace('/\'/',"\'",$tmp);

	$raw_data=json_decode($tmp, true);
	//$raw_data['store_key']=$store;
	//print_r( $raw_data);exit;
	list($where,$table)=invoices_awhere($raw_data);


}
elseif ($parent=='category') {
	$category=new Category($parent_key);

	if (!in_array($category->data['Category Store Key'],$user->stores)) {
		return;
	}

	$where=sprintf(" where `Subject`='Invoice' and  `Category Key`=%d",$parent_key);
	$table=' `Category Bridge` left join  `Invoice Dimension` I on (`Subject Key`=`Invoice Key`) ';
	$where_type='';

	$store_key=$category->data['Category Store Key'];

}
elseif ($parent=='list') {
	$sql=sprintf("select * from `List Dimension` where `List Key`=%d",$parent_key);

	$res=mysql_query($sql);
	if ($list_data=mysql_fetch_assoc($res)) {
		$awhere=false;
		$store_key=$list_data['List Parent Key'];
		if ($list_data['List Type']=='Static') {
			$table='`List Invoice Bridge` OB left join `Invoice Dimension` I  on (OB.`Invoice Key`=I.`Invoice Key`)';
			$where_type=sprintf(' and `List Key`=%d ',$parent_key);

		} else {// Dynamic by DEFAULT



			$tmp=preg_replace('/\\\"/','"',$list_data['List Metadata']);
			$tmp=preg_replace('/\\\\\"/','"',$tmp);
			$tmp=preg_replace('/\'/',"\'",$tmp);

			$raw_data=json_decode($tmp, true);

			//$raw_data['store_key']=$store;
			list($where,$table)=invoices_awhere($raw_data);




		}

	} else {
		exit("error");
	}
}
elseif ($parent=='store') {
	if (is_numeric($parent_key) and in_array($parent_key,$user->stores)) {
		$where=sprintf(' where  `Invoice Store Key`=%d ',$parent_key);
		include_once('class.Store.php');
		$store=new Store($parent_key);
		$currency=$store->data['Store Currency Code'];
	}
	else {
		$where.=sprintf(' and  false');
	}


}
elseif ($parent=='stores') {
	if (is_numeric($parent_key) and in_array($parent_key,$user->stores)) {

		if (count($user->stores)==0) {
			$where=' where false';
		}
		else {

			$where=sprintf('where  `Invoice Store Key` in (%s)  ',join(',',$user->stores));
		}
	}
}
else {
	exit("unknown parent\n");
}


if ($from)$from=$from.' 00:00:00';
if ($to)$to=$to.' 23:59:59';




$where_interval=prepare_mysql_dates($from,$to,'`Invoice Date`');
$where.=$where_interval['mysql'];



switch ($elements_type) {

case('type'):
	$_elements='';
	$num_elements_checked=0;
	foreach ($elements['type'] as $_key=>$_value) {
		if ($_value) {
			$num_elements_checked++;

			$_elements.=", '$_key'";
		}
	}

	if ($_elements=='') {
		$where.=' and false' ;
	}elseif ($num_elements_checked==2) {

	}else {
		$_elements=preg_replace('/^,/','',$_elements);
		$where.=' and `Invoice Type` in ('.$_elements.')' ;
	}
	break;
case('payment'):
	$_elements='';
	$num_elements_checked=0;

	foreach ($elements['payment'] as $_key=>$_value) {
		if ($_value) {
			$num_elements_checked++;

			$_elements.=", '$_key'";
		}
	}
	if ($_elements=='') {
		$where.=' and false' ;
	}elseif ($num_elements_checked==3) {

	}else {
		$_elements=preg_replace('/^,/','',$_elements);
		$where.=' and `Invoice Paid` in ('.$_elements.')' ;
	}
	break;
}







if (($f_field=='customer_name'     )  and $f_value!='') {
	$wheref="  and  `Invoice Customer Name` like '%".addslashes($f_value)."%'";
}
elseif ($f_field=='public_id'  and $f_value!='' )
	$wheref.=" and  `Invoice Public ID` like '".addslashes(preg_replace('/\s*|\,|\./','',$f_value))."%' ";
elseif ($f_field=='last_more' and is_numeric($f_value) )
	$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Invoice Date`))>=".$f_value."    ";
elseif ($f_field=='last_less' and is_numeric($f_value) )
	$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Invoice Date`))<=".$f_value."    ";
elseif ($f_field=='maxvalue' and is_numeric($f_value) )
	$wheref.=" and  `Invoice Total Amount`<=".$f_value."    ";
elseif ($f_field=='minvalue' and is_numeric($f_value) )
	$wheref.=" and  `Invoice Total Amount`>=".$f_value."    ";
elseif ($f_field=='country' and  $f_value!='') {
	if ($f_value=='UNK') {
		$wheref.=" and  `Invoice Billing Country Code`='".$f_value."'    ";
		$find_data=' '._('a unknown country');
	} else {

		$f_value=Address::parse_country($f_value);
		if ($f_value!='UNK') {
			$wheref.=" and  `Invoice Billing Country Code`='".$f_value."'    ";
			$country=new Country('code',$f_value);
			$find_data=' '.$country->data['Country Name'].' <img src="art/flags/'.$country->data['Country 2 Alpha Code'].'.png" alt="'.$country->data['Country Code'].'"/>';
		}

	}
}


//print $where_type;

?>
