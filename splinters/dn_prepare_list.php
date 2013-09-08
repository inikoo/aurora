<?php
	$where='where true';
	$table='`Delivery Note Dimension` D ';
$wheref='';





	if ($awhere) {
		$tmp=preg_replace('/\\\"/','"',$awhere);
		$tmp=preg_replace('/\\\\\"/','"',$tmp);
		$tmp=preg_replace('/\'/',"\'",$tmp);

		$raw_data=json_decode($tmp, true);
		$raw_data['store_key']=$store;
		list($where,$table)=dn_awhere($raw_data);

		$where_type='';
		$where_interval='';
	}
	//print $where_interval;exit;
	elseif ($parent=='list') {
		$sql=sprintf("select * from `List Dimension` where `List Key`=%d",$_REQUEST['list_key']);

		$res=mysql_query($sql);
		if ($customer_list_data=mysql_fetch_assoc($res)) {
			$awhere=false;
			if ($customer_list_data['List Type']=='Static') {
				$table='`List Delivery Note Bridge` DB left join `Delivery Note Dimension` D  on (DB.`Delivery Note Key`=D.`Delivery Note Key`)';
				$where_type=sprintf(' and `List Key`=%d ',$_REQUEST['list_key']);

			} else {// Dynamic by DEFAULT



				$tmp=preg_replace('/\\\"/','"',$customer_list_data['List Metadata']);
				$tmp=preg_replace('/\\\\\"/','"',$tmp);
				$tmp=preg_replace('/\'/',"\'",$tmp);

				$raw_data=json_decode($tmp, true);

				$raw_data['store_key']=$store;
				list($where,$table)=dn_awhere($raw_data);




			}

		} else {
			exit("error");
		}
	} 
elseif ($parent=='store') {
	if (is_numeric($parent_key) and in_array($parent_key,$user->stores)) {
		$where=sprintf(' where  `Delivery Note Store Key`=%d ',$parent_key);
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

			$where=sprintf('where  `Delivery Note Store Key` in (%s)  ',join(',',$user->stores));
		}
	}
}
else {
	exit("unknown parent\n");
}




	if ($from)$from=$from.' 00:00:00';
	if ($to)$to=$to.' 23:59:59';

	$where_interval=prepare_mysql_dates($from,$to,'`Delivery Note Date`');
	$where.=$where_interval['mysql'];







	switch ($elements_type) {
	case('dispatch'):
		$_elements='';
		$num_elements_checked=0;
		foreach ($elements['dispatch'] as $_key=>$_value) {
			if ($_value) {
				$num_elements_checked++;
				if ($_key=='Ready') {
					$_elements.=",'Ready to be Picked'";

				}elseif ($_key=='Picking') {
					$_elements.=",'Picking','Picking & Packing','Picked','Picker Assigned','Picker & Packer Assigned'";
				}elseif ($_key=='Packing') {
					$_elements.=",'Packing','Packed','Packer Assigned','Packed Done'";
				}elseif ($_key=='Done') {
					$_elements.=",'Approved'";
				}elseif ($_key=='Send') {
					$_elements.=",'Dispatched'";
				}elseif ($_key=='Returned') {
					$_elements.=",'Cancelled','Cancelled to Restock'";
				}
			}
		}

		if ($_elements=='') {
			$where.=' and false' ;
		}elseif ($num_elements_checked==6) {

		}else {
			$_elements=preg_replace('/^,/','',$_elements);
			$where.=' and `Delivery Note State` in ('.$_elements.')' ;
		}
		break;

	case('type'):



		$_elements='';
		$num_elements_checked=0;
		foreach ($elements['type'] as $_key=>$_value) {
			if ($_value) {
				$num_elements_checked++;

				if ($_key=='Replacements') {
					$_elements.=", 'Replacement','Replacement & Shortages'  ";

				}else {

					$_elements.=", '$_key'";
				}
			}
		}

		if ($_elements=='') {
			$where.=' and false' ;
		}elseif ($num_elements_checked==5) {

		}else {
			$_elements=preg_replace('/^,/','',$_elements);
			$where.=' and `Delivery Note Type` in ('.$_elements.')' ;
		}
		break;

	}



	

	if ($f_field=='customer_name'       and $f_value!='')
		$wheref="  and  `Delivery Note Customer Name` like '%".addslashes($f_value)."%'";
	elseif ($f_field=='public_id' and $f_value!='')
		$wheref.=" and  `Delivery Note ID` like '".addslashes($f_value)."%'";
	elseif ($f_field=='country' and  $f_value!='') {
		if ($f_value=='UNK') {
			$wheref.=" and  `Delivery Note Country Code`='".$f_value."'    ";
			$find_data=' '._('a unknown country');
		} else {
			$f_value=Address::parse_country($f_value);
			if ($f_value!='UNK') {
				$wheref.=" and  `Delivery Note Country Code`='".$f_value."'    ";
				$country=new Country('code',$f_value);
				$find_data=' '.$country->data['Country Name'].' <img style="vertical-align: text-bottom;position:relative;bottom:2px" src="art/flags/'.strtolower($country->data['Country 2 Alpha Code']).'.gif" alt="'.$country->data['Country Code'].'"/>';
			}
		}
	}

	//}elseif ($f_field=='max' and is_numeric($f_value) )
	// $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Delivery Note Date Created`))<=".$f_value."    ";
	//elseif ($f_field=='min' and is_numeric($f_value) )
	// $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Delivery Note Date Created`))>=".$f_value."    ";
	// elseif ($f_field=='invoice' and $f_value!='')
	//  $wheref.=" and  `Delivery Note Invoices` like '".addslashes($f_value)."%'";
	// elseif ($f_field=='order' and $f_value!='')
	//  $wheref.=" and  `Delivery Note Order` like '".addslashes($f_value)."%'";
	// elseif ($f_field=='maxvalue' and is_numeric($f_value) )
	//  $wheref.=" and  total<=".$f_value."    ";
	// elseif ($f_field=='minvalue' and is_numeric($f_value) )
	//  $wheref.=" and  total>=".$f_value."    ";





?>