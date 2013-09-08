<?php





	$where='where true ';
	$table='`Order Dimension` O ';
	$where_type='';

	if ($from)$from=$from.' 00:00:00';
	if ($to)$to=$to.' 23:59:59';

	$where_interval=prepare_mysql_dates($from,$to,'`Order Last Updated Date`');
	$where_interval=$where_interval['mysql'];

	switch ($elements_type) {
	case('dispatch'):
		$_elements='';
		$num_elements_checked=0;
		foreach ($elements['dispatch'] as $_key=>$_value) {
			if ($_value) {
				$num_elements_checked++;
				if ($_key=='InProcessCustomer') {
					$_elements.=",'In Process Customer'";

				}elseif ($_key=='InProcess') {
					$_elements.=",'In Process'";
				}elseif ($_key=='Warehouse') {
					$_elements.=",'Ready to Pick','Picking & Packing','Ready to Ship'";
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
			$_elements=preg_replace('/^,/','',$_elements);
			$where.=' and `Order Current Dispatch State` in ('.$_elements.')' ;
		}
		break;
	case('source'):
		$_elements='';
		$num_elements_checked=0;
		foreach ($elements['source'] as $_key=>$_value) {
			if ($_value) {
				$num_elements_checked++;

				$_elements.=", '$_key'";
			}
		}

		if ($_elements=='') {
			$where.=' and false' ;
		}elseif ($num_elements_checked==6) {

		}else {
			$_elements=preg_replace('/^,/','',$_elements);
			$where.=' and `Order Main Source Type` in ('.$_elements.')' ;
		}
		break;
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
		}elseif ($num_elements_checked==6) {

		}else {
			$_elements=preg_replace('/^,/','',$_elements);
			$where.=' and `Order Type` in ('.$_elements.')' ;
		}
		break;
	case('payment'):
		$_elements='';
		$num_elements_checked=0;

		//'Waiting Payment','Paid','Partially Paid','Unknown','No Applicable'

		foreach ($elements['payment'] as $_key=>$_value) {
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
			$_elements=preg_replace('/^,/','',$_elements);
			$where.=' and `Order Current Payment State` in ('.$_elements.')' ;
		}
		break;
	}



	if ($awhere) {
		$tmp=preg_replace('/\\\"/','"',$awhere);
		$tmp=preg_replace('/\\\\\"/','"',$tmp);
		$tmp=preg_replace('/\'/',"\'",$tmp);

		$raw_data=json_decode($tmp, true);
		$raw_data['store_key']=$parent_key;
		//print_r( $raw_data);exit;
		list($where,$table)=orders_awhere($raw_data);

		$where_type='';
		$where_interval='';
	}


	if ($list_key) {
		$where_type='';
		$where_interval='';

		$sql=sprintf("select * from `List Dimension` where `List Key`=%d",$_REQUEST['list_key']);
		//print $sql;
		$res=mysql_query($sql);
		if ($customer_list_data=mysql_fetch_assoc($res)) {
			$awhere=false;
			if ($customer_list_data['List Type']=='Static') {
				$table='`List Order Bridge` OB left join `Order Dimension` O  on (OB.`Order Key`=O.`Order Key`)';
				//$where_type=sprintf(' and `List Key`=%d ',$_REQUEST['list_key']);

			} else {// Dynamic by DEFAULT



				$tmp=preg_replace('/\\\"/','"',$customer_list_data['List Metadata']);
				$tmp=preg_replace('/\\\\\"/','"',$tmp);
				$tmp=preg_replace('/\'/',"\'",$tmp);

				$raw_data=json_decode($tmp, true);

				$raw_data['store_key']=$parent_key;
				list($where,$table)=orders_awhere($raw_data);




			}

		} else {
			exit("error");
		}
	}





	$filter_msg='';
	$wheref='';

	$currency='';

	$where_stores=sprintf(' and  false');

	if (is_numeric($parent_key) and in_array($parent_key,$user->stores)) {
		$where_stores=sprintf(' and  `Order Store Key`=%d ',$parent_key);
		$store=new Store($parent_key);
		$currency=$store->data['Store Currency Code'];
	} else {

		$currency='';
	}


	if (isset( $_REQUEST['all_stores']) and  $_REQUEST['all_stores']  ) {
		$where_stores=sprintf('and `Order Store Key` in (%s)  ',join(',',$user->stores));
	}

	$where.=$where_stores;
	$where.=$where_type;
	$where.=$where_interval;



	if (($f_field=='customer_name')  and $f_value!='') {
		$wheref="  and  `Order Customer Name` like '%".addslashes($f_value)."%'";
	}
	elseif (($f_field=='postcode')  and $f_value!='') {
		$wheref="  and  `Customer Main Postal Code` like '%".addslashes($f_value)."%'";
	}
	elseif ($f_field=='public_id'  and $f_value!='')
		$wheref.=" and  `Order Public ID`  like '".addslashes($f_value)."%'";

	elseif ($f_field=='maxvalue' and is_numeric($f_value) )
		$wheref.=" and  `Order Invoiced Balance Total Amount`<=".$f_value."    ";
	elseif ($f_field=='minvalue' and is_numeric($f_value) )
		$wheref.=" and  `Order Invoiced Balance Total Amount`>=".$f_value."    ";
	elseif ($f_field=='country' and  $f_value!='') {
		if ($f_value=='UNK') {
			$wheref.=" and  `Order Main Country Code`='".$f_value."'    ";
			$find_data=' '._('a unknown country');
		} else {
			include once('class.Address.php');
			$f_value=Address::parse_country($f_value);
			if ($f_value!='UNK') {
				$wheref.=" and  `Order Main Country Code`='".$f_value."'    ";
				$country=new Country('code',$f_value);
				$find_data=' '.$country->data['Country Name'].' <img style="vertical-align: text-bottom;position:relative;bottom:2px" src="art/flags/'.strtolower($country->data['Country 2 Alpha Code']).'.gif" alt="'.$country->data['Country Code'].'"/>';
			}
		}
	}



?>