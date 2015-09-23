<?php
$where='where true';
$table='`Delivery Note Dimension` D ';
$wheref='';
$group='';

list($db_interval,$from,$to,$from_date_1yb,$to_1yb)=calculate_interval_dates($parameters['period'],$parameters['from'],$parameters['to']);


if (isset($parameters['awhere']) and $parameters['awhere']) {
	$tmp=preg_replace('/\\\"/','"',$parameters['awhere']);
	$tmp=preg_replace('/\\\\\"/','"',$tmp);
	$tmp=preg_replace('/\'/',"\'",$tmp);

	$raw_data=json_decode($tmp, true);
	$raw_data['store_key']=$store;
	list($where,$table)=dn_awhere($raw_data);

	$where_type='';
	$where_interval='';
}
//print $where_interval;exit;
elseif ($parameters['parent']=='list') {
	$sql=sprintf("select * from `List Dimension` where `List Key`=%d",$parameters['parent_key']);

	$res=mysql_query($sql);
	if ($dn_list_data=mysql_fetch_assoc($res)) {
		$awhere=false;
		if ($dn_list_data['List Type']=='Static') {
			$table='`List Delivery Note Bridge` DB left join `Delivery Note Dimension` D  on (DB.`Delivery Note Key`=D.`Delivery Note Key`)';
			$where_type=sprintf(' and `List Key`=%d ',$parameters['parent_key']);

		} else {
			$tmp=preg_replace('/\\\"/','"',$dn_list_data['List Metadata']);
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
elseif ($parameters['parent']=='store') {
	if (is_numeric($parameters['parent_key']) and in_array($parameters['parent_key'],$user->stores)) {
		$where=sprintf(' where  `Delivery Note Store Key`=%d ',$parameters['parent_key']);
		include_once 'class.Store.php';
		$store=new Store($parameters['parent_key']);
		$currency=$store->data['Store Currency Code'];
	}
	else {
		$where=sprintf(' where  false');
	}


}
elseif ($parameters['parent']=='stores') {
	if (is_numeric($parameters['parent_key']) and in_array($parameters['parent_key'],$user->stores)) {

		if (count($user->stores)==0) {
			$where=' where false';
		}
		else {

			$where=sprintf('where  `Delivery Note Store Key` in (%s)  ',join(',',$user->stores));
		}
	}
}
elseif ($parameters['parent']=='part') {
	global $corporate_currency;
	$where=sprintf(' where  `Part SKU`=%d ',$parameters['parent_key']);

	$table='`Inventory Transaction Fact` ITF left join  `Delivery Note Dimension` D  on (ITF.`Delivery Note Key`=D.`Delivery Note Key`) ';
	$group=' group by ITF.`Delivery Note Key`';
	$currency=$corporate_currency;


}
else {
	exit("unknown parent (dn)\n");
}



$where_interval=prepare_mysql_dates($from,$to,'`Delivery Note Date`');
$where.=$where_interval['mysql'];







switch ($parameters['elements_type']) {
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


	$_order=$order;
	$_dir=$order_direction;


	if ($order=='date' )
		$order='`Delivery Note Date Created`';
	elseif ($order=='id')
		$order='`Delivery Note File As`';
	elseif ($order=='customer')
		$order='`Delivery Note Customer Name`';
	elseif ($order=='type')
		$order='`Delivery Note Type`';
	elseif ($order=='weight')
		$order='`Delivery Note Weight`';
	elseif ($order=='parcels')
		$order='`Delivery Note Parcel Type`,`Delivery Note Number Parcels`';
    else
      		$order='`Delivery Note Key`';
  



if ($parameters['f_field']=='customer'       and $f_value!='')
		$wheref=sprintf('  and  `Delivery Note Customer Name`  REGEXP "[[:<:]]%s" ',addslashes($f_value));

elseif ($parameters['f_field']=='number' and $f_value!='')
	$wheref.=" and  `Delivery Note ID` like '".addslashes($f_value)."%'";
elseif ($parameters['f_field']=='country' and  $f_value!='') {
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

$fields='*';
$sql_totals="select count(Distinct D.`Delivery Note Key`) as num from $table   $where $wheref ";





?>
