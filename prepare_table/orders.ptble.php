<?php



$group_by='';
$wheref='';

$currency='';


$where='where true ';
$table='`Order Dimension` O left join `Payment Account Dimension` P on (P.`Payment Account Key`=O.`Order Payment Account Key`)';


if (isset($parameters['awhere']) and $parameters['awhere']) {
	$tmp=preg_replace('/\\\"/', '"', $awhere);
	$tmp=preg_replace('/\\\\\"/', '"', $tmp);
	$tmp=preg_replace('/\'/', "\'", $tmp);

	$raw_data=json_decode($tmp, true);
	$raw_data['store_key']=$parameters['parent_key'];
	//print_r( $raw_data);exit;
	list($where, $table)=orders_awhere($raw_data);

	$where_interval='';
}


elseif ($parameters['parent']=='list') {
	$where_interval='';

	$sql=sprintf("select * from `List Dimension` where `List Key`=%d", $parameters['parent_key']);
	//print $sql;
	$res=mysql_query($sql);
	if ($list_data=mysql_fetch_assoc($res)) {
		$awhere=false;
		if ($list_data['List Type']=='Static') {
			$table='`List Order Bridge` OB left join `Order Dimension` O  on (OB.`Order Key`=O.`Order Key`)';
			$where=sprintf(' where `List Key`=%d ', $parameters['parent_key']);

		} else {// Dynamic by DEFAULT



			$tmp=preg_replace('/\\\"/', '"', $list_data['List Metadata']);
			$tmp=preg_replace('/\\\\\"/', '"', $tmp);
			$tmp=preg_replace('/\'/', "\'", $tmp);

			$raw_data=json_decode($tmp, true);

			$raw_data['store_key']=$parameters['parent_key'];
			list($where, $table)=orders_awhere($raw_data);




		}

	} else {
		exit("error parent not found: ".$parameters['parent']);
	}
}

elseif ($parameters['parent']=='store') {
	if (is_numeric($parameters['parent_key']) and in_array($parameters['parent_key'], $user->stores)) {
		$where=sprintf(' where  `Order Store Key`=%d ', $parameters['parent_key']);
		if (!isset($store)) {
			include_once 'class.Store.php';
			$store=new Store($parameters['parent_key']);
		}
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

			$where=sprintf('where  `Order Store Key` in (%s)  ', join(',', $user->stores));
		}
	}
}elseif ($parameters['parent']=='customer') {


	include_once 'class.Customer.php';
	$customer=new Customer($parameters['parent_key']);

	if (!$customer->id) {
		$where=' where false';
	}
	else {

		if ( in_array($customer->get('Customer Store Key'), $user->stores)) {
			$where=sprintf('where  `Order Customer Key`=%d  ', $parameters['parent_key']);
		}
		else {
			$where=' where false';
		}

	}
}elseif ($parameters['parent']=='product') {


	$table='`Order Transaction Fact` OTF  left join     `Order Dimension` O   on (OTF.`Order Key`=O.`Order Key`)   left join `Payment Account Dimension` P on (P.`Payment Account Key`=O.`Order Payment Account Key`)';

	$where=sprintf(' where  `Product ID`=%d ', $parameters['parent_key']);

	$group_by=' group by OTF.`Order Key` ';


}elseif ($parameters['parent']=='delivery_note') {


	$table='`Order Delivery Note Bridge` B left join   `Order Dimension` O  on (O.`Order Key`=B.`Order Key`)     left join `Payment Account Dimension`   P on (P.`Payment Account Key`=O.`Order Payment Account Key`)';

	$where=sprintf(' where  `Delivery Note Key`=%d ', $parameters['parent_key']);



}elseif ($parameters['parent']=='invoice') {


	$table='`Order Invoice Bridge` B left join   `Order Dimension` O  on (O.`Order Key`=B.`Order Key`)     left join `Payment Account Dimension`   P on (P.`Payment Account Key`=O.`Order Payment Account Key`)';

	$where=sprintf(' where  `Invoice Key`=%d ', $parameters['parent_key']);



}
else {
	exit("unknown parent\n");
}

if (isset($parameters['period'])) {
	list($db_interval, $from, $to, $from_date_1yb, $to_1yb)=calculate_interval_dates($parameters['period'], $parameters['from'], $parameters['to']);

	$where_interval=prepare_mysql_dates($from, $to, 'O.`Order Date`');
	$where.=$where_interval['mysql'];
}

if (isset($parameters['period'])) {


	switch ($parameters['elements_type']) {
	case('dispatch'):
		$_elements='';
		$num_elements_checked=0;


		foreach ($parameters['elements']['dispatch']['items'] as $_key=>$_value) {
			$_value=$_value['selected'];
			if ($_value) {
				$num_elements_checked++;
				if ($_key=='InProcessCustomer') {
					$_elements.=",'In Process by Customer','Waiting for Payment Confirmation'";

				}elseif ($_key=='InProcess') {
					$_elements.=",'In Process','Submitted by Customer'";
				}elseif ($_key=='Warehouse') {
					$_elements.=",'Ready to Pick','Picking & Packing','Ready to Ship','Packing','Packed','Packed Done'";
				}elseif ($_key=='Dispatched') {
					$_elements.=",'Dispatched'";
				}elseif ($_key=='Cancelled') {
					$_elements.=",'Cancelled'";
				}elseif ($_key=='Suspended') {
					$_elements.=",'Suspended'";
				}
			}
		}

		if ($_elements=='') {
			$where.=' and false' ;
		}elseif ($num_elements_checked==6) {

		}else {


			$_elements=preg_replace('/^,/', '', $_elements);

			$where.=' and `Order Current Dispatch State` in ('.$_elements.')' ;
		}
		break;
	case('source'):
		$_elements='';
		$num_elements_checked=0;
		foreach ($parameters['elements']['source']['items'] as $_key=>$_value) {
			$_value=$_value['selected'];
			if ($_value) {
				$num_elements_checked++;

				$_elements.=", '$_key'";
			}
		}

		if ($_elements=='') {
			$where.=' and false' ;
		}elseif ($num_elements_checked==6) {

		}else {
			$_elements=preg_replace('/^,/', '', $_elements);
			$where.=' and `Order Main Source Type` in ('.$_elements.')' ;
		}
		break;
	case('type'):
		$_elements='';
		$num_elements_checked=0;
		foreach ($parameters['elements']['type']['items'] as $_key=>$_value) {
			$_value=$_value['selected'];
			if ($_value) {
				$num_elements_checked++;

				$_elements.=", '$_key'";
			}
		}

		if ($_elements=='') {
			$where.=' and false' ;
		}elseif ($num_elements_checked==6) {

		}else {
			$_elements=preg_replace('/^,/', '', $_elements);
			$where.=' and `Order Type` in ('.$_elements.')' ;
		}
		break;
	case('payment'):
		$_elements='';
		$num_elements_checked=0;

		//'Waiting Payment','Paid','Partially Paid','Unknown','No Applicable'

		foreach ($parameters['elements']['payment']['items'] as $_key=>$_value) {
			$_value=$_value['selected'];
			if ($_value) {
				$num_elements_checked++;
				if ($_key=='WaitingPayment')$_key='Waiting Payment';
				if ($_key=='PartiallyPaid')$_key='Partially Paid';
				if ($_key=='NA')$_key='No Applicable';


				$_elements.=", '$_key'";
			}
		}

		if ($_elements=='') {
			$where.=' and false' ;
		}elseif ($num_elements_checked==6) {

		}else {
			$_elements=preg_replace('/^,/', '', $_elements);
			$where.=' and `Order Current Payment State` in ('.$_elements.')' ;
		}
		break;
	}
}


if (($parameters['f_field']=='customer')  and $f_value!='') {

	$wheref=sprintf('  and  `Order Customer Name`  REGEXP "[[:<:]]%s" ', addslashes($f_value));


}


elseif (($parameters['f_field']=='postcode')  and $f_value!='') {
	$wheref="  and  `Customer Main Postal Code` like '%".addslashes($f_value)."%'";
}
elseif ($parameters['f_field']=='public_id'  and $f_value!='')
	$wheref.=" and  `Order Public ID`  like '".addslashes($f_value)."%'";

elseif ($parameters['f_field']=='maxvalue' and is_numeric($f_value) )
	$wheref.=" and  `Order Invoiced Balance Total Amount`<=".$f_value."    ";
elseif ($parameters['f_field']=='minvalue' and is_numeric($f_value) )
	$wheref.=" and  `Order Invoiced Balance Total Amount`>=".$f_value."    ";
elseif ($parameters['f_field']=='country' and  $f_value!='') {
	if ($f_value=='UNK') {
		$wheref.=" and  `Order Main Country Code`='".$f_value."'    ";
		$find_data=' '._('a unknown country');
	} else {
		include once('class.Address.php');
		$f_value=Address::parse_country($f_value);
		if ($f_value!='UNK') {
			$wheref.=" and  `Order Main Country Code`='".$f_value."'    ";
			$country=new Country('code', $f_value);
			$find_data=' '.$country->data['Country Name'].' <img style="vertical-align: text-bottom;position:relative;bottom:2px" src="art/flags/'.strtolower($country->data['Country 2 Alpha Code']).'.gif" alt="'.$country->data['Country Code'].'"/>';
		}
	}
}



$_order=$order;
$_dir=$order_direction;


if ($order=='public_id')
	$order='`Order File As`';
elseif ($order=='last_date' or $order=='date')
	$order='O.`Order Date`';

elseif ($order=='customer')
	$order='O.`Order Customer Name`';
elseif ($order=='dispatch_state')
	$order='O.`Order Current Dispatch State`';
elseif ($order=='payment_state')
	$order='O.`Order Current Payment State`';
elseif ($order=='total_amount')
	$order='O.`Order Total Amount`';
else
	$order='O.`Order Key`';

$fields='`Order Store Key`,`Payment Account Name`,`Order Payment Method`,`Order Current XHTML Dispatch State`,`Order Balance Total Amount`,`Order Current Payment State`,`Order Current Dispatch State`,`Order Out of Stock Net Amount`,`Order Invoiced Total Net Adjust Amount`,`Order Invoiced Total Tax Adjust Amount`,FORMAT(`Order Invoiced Total Net Adjust Amount`+`Order Invoiced Total Tax Adjust Amount`,2) as `Order Adjust Amount`,`Order Out of Stock Net Amount`,`Order Out of Stock Tax Amount`,FORMAT(`Order Out of Stock Net Amount`+`Order Out of Stock Tax Amount`,2) as `Order Out of Stock Amount`,`Order Invoiced Balance Total Amount`,`Order Type`,`Order Currency Exchange`,`Order Currency`,O.`Order Key`,O.`Order Public ID`,`Order Customer Key`,`Order Customer Name`,O.`Order Last Updated Date`,O.`Order Date`,`Order Total Amount` ,`Order Current XHTML Payment State`';

$sql_totals="select count(Distinct O.`Order Key`) as num from $table   $where $wheref ";
// $sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
//print $sql;

?>
