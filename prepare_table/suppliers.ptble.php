<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2015 15:16:52 GMT+7, MIA-MAnchester (Train), UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$currency='';
$where='where true';
$table='`Supplier Dimension` S ';
$group_by='';
$where_type='';


if (isset($parameters['awhere']) and $parameters['awhere']) {

	$tmp=preg_replace('/\\\"/','"',$parameters['awhere']);
	$tmp=preg_replace('/\\\\\"/','"',$tmp);
	$tmp=preg_replace('/\'/',"\'",$tmp);

	$raw_data=json_decode($tmp, true);
	$raw_data['store_key']=$parameters['parent_key'];
	include_once 'list_functions_supplier.php';
	list($where,$table,$group_by)=suppliers_awhere($raw_data);




}
elseif ($parameters['parent']=='list') {


	$sql=sprintf("select * from `List Dimension` where `List Key`=%d",$parameters['parent_key']);

	$res=mysql_query($sql);
	if ($supplier_list_data=mysql_fetch_assoc($res)) {
		$parameters['awhere']=false;
		if ($supplier_list_data['List Type']=='Static') {
			$table='`List Supplier Bridge` CB left join `Supplier Dimension` C  on (CB.`Supplier Key`=S.`Supplier Key`)';
			$where=sprintf(' where `List Key`=%d ',$parameters['parent_key']);

		} else {

			$tmp=preg_replace('/\\\"/','"',$supplier_list_data['List Metadata']);
			$tmp=preg_replace('/\\\\\"/','"',$tmp);
			$tmp=preg_replace('/\'/',"\'",$tmp);

			$raw_data=json_decode($tmp, true);

			$raw_data['store_key']=$supplier_list_data['List Parent Key'];
			include_once 'utils/list_functions_supplier.php';

			list($where,$table,$group_by)=suppliers_awhere($raw_data);


		}

	} else {
		return;
	}



}
elseif ($parameters['parent']=='category') {


	$where=sprintf(" where `Subject`='Supplier' and  `Category Key`=%d",$parameters['parent_key']);
	$table=' `Category Bridge` left join  `Supplier Dimension` C on (`Subject Key`=`Supplier Key`) ';

}
elseif ($parameters['parent']=='store') {



	$where.=$where_stores;
}
else {


}




/*

$where_type='';

switch ($parameters['elements_type']) {
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
		$where.=' and `Supplier Type by Activity` in ('.$_elements.')' ;
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
		$where.=' and `Supplier Level Type` in ('.$_elements.')' ;
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
		$where.=' and `Supplier Location Type` in ('.$_elements.')' ;
	}
	break;



}
*/


$filter_msg='';
$wheref='';
if ($parameters['f_field']=='code'  and $f_value!='')
	$wheref.=" and `Supplier Code` like '".addslashes($f_value)."%'";
if ($parameters['f_field']=='name' and $f_value!='')
	$wheref.=" and  `Supplier Name` like '".addslashes($f_value)."%'";
elseif ($parameters['f_field']=='low' and is_numeric($f_value))
	$wheref.=" and lowstock>=$f_value  ";
elseif ($parameters['f_field']=='outofstock' and is_numeric($f_value))
	$wheref.=" and outofstock>=$f_value  ";


$_order=$order;
$_dir=$order_direction;


$db_period=get_interval_db_name($parameters['f_period']);

if ($order=='code')
	$order='`Supplier Code`';
elseif ($order=='name')
	$order='`Supplier Name`';
elseif ($order=='formated_id')
	$order='`Supplier Key`';
elseif ($order=='id')
	$order='`Supplier Key`';
elseif ($order=='location')
	$order='`Supplier Main Location`';
elseif ($order=='email')
	$order='`Supplier Main XHTML Email`';
elseif ($order=='products')
	$order='`Supplier Active Supplier Products`';
elseif ($order=='sales') {
	$order="`Supplier $db_period Acc Parts Sold Amount`";
}
elseif ($order=='sold') {
	$order="`Supplier $db_period Acc Parts Sold`";
}
elseif ($order=='required') {
	$order="`Supplier $db_period Acc Parts Required`";
}



elseif ($order=='delta_sales') {

	if (in_array($parameters['f_period'],array('all','3y','three_year'))) {

		$order="`Supplier $db_period Acc Parts Sold Amount`";

	}else {
		$order="((`Supplier $db_period Acc Parts Sold Amount`-`Supplier $db_period Acc 1Yb Parts Sold Amount`)/`Supplier $db_period Acc 1Yb Parts Sold Amount`)";
	}
}



elseif ($order=='pending_pos') {
	$order='`Supplier Open Purchase Orders`';

}
elseif ($order=='margin') {
	$order="`Supplier $db_period Acc Parts Margin`";

}
elseif ($order=='cost') {
	$order="`Supplier $db_period Acc Parts Cost`";


}elseif ($order=='origin') {
	$order="`Supplier Products Origin Country Code`";
}elseif ($order=='active_sp') {
	$order="`Supplier Active Supplier Products`";
}elseif ($order=='no_active_sp') {
	$order="`Supplier Discontinued Supplier Products`";
}elseif ($order=='delivery_time') {
	$order="`Supplier Average Delivery Days`";
}elseif ($order=='low') {
	$order="`Supplier Low Availability Products`";
}elseif ($order=='high') {
	$order="`Supplier Surplus Availability Products`";
}elseif ($order=='normal') {
	$order="`Supplier Optimal Availability Products`";
}elseif ($order=='critical') {
	$order="`Supplier Critical Availability Products`";
}elseif ($order=='outofstock') {
	$order="`Supplier Out Of Stock Products`";
}elseif ($order=='contact') {
	$order="`Supplier Main Contact Name`";
}elseif ($order=='tel') {
	$order="`Supplier Main Plain Telephone`";
}elseif ($order=='profit_after_storing') {
	$order="`Supplier $db_period Acc Parts Profit After Storing`";

}

elseif ($order=='profit') {
	$order="`Supplier $db_period Acc Parts Profit`";
}
elseif ($order=='delta_sales_year0') {$order="(-1*(`Supplier Year To Day Acc Parts Sold Amount`-`Supplier Year To Day Acc 1YB Parts Sold Amount`)/`Supplier Year To Day Acc 1YB Parts Sold Amount`)";}
elseif ($order=='delta_sales_year1') {$order="(-1*(`Supplier 2 Year Ago Sales Amount`-`Supplier 1 Year Ago Sales Amount`)/`Supplier 2 Year Ago Sales Amount`)";}
elseif ($order=='delta_sales_year2') {$order="(-1*(`Supplier 3 Year Ago Sales Amount`-`Supplier 2 Year Ago Sales Amount`)/`Supplier 3 Year Ago Sales Amount`)";}
elseif ($order=='delta_sales_year3') {$order="(-1*(`Supplier 4 Year Ago Sales Amount`-`Supplier 3 Year Ago Sales Amount`)/`Supplier 4 Year Ago Sales Amount`)";}
elseif ($order=='sales_year1') {$order="`Supplier 1 Year Ago Sales Amount`";}
elseif ($order=='sales_year2') {$order="`Supplier 2 Year Ago Sales Amount`";}
elseif ($order=='sales_year3') {$order="`Supplier 3 Year Ago Sales Amount`";}
elseif ($order=='sales_year4') {$order="`Supplier 4 Year Ago Sales Amount`";}
elseif ($order=='sales_year0') {$order="`Supplier Year To Day Acc Parts Sold Amount`";}


$sql_totals="select count(Distinct S.`Supplier Key`) as num from $table  $where  $where_type";

$fields='*';

?>
