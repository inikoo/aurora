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
				$where.=sprintf(' and `List Key`=%d ',$parent_key);

			} else {

				$tmp=preg_replace('/\\\"/','"',$customer_list_data['List Metadata']);
				$tmp=preg_replace('/\\\\\"/','"',$tmp);
				$tmp=preg_replace('/\'/',"\'",$tmp);

				$raw_data=json_decode($tmp, true);

				$raw_data['store_key']=$customer_list_data['List Parent Key'];
				include_once 'list_functions_customer.php';

				list($where,$table,$group_by)=customers_awhere($raw_data);


			}

		} else {
			return;
		}



	}
	elseif ($parent=='category') {

include_once('class.Category.php');
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
		$where_stores=sprintf(' and  `Customer Store Key`=%d ',$parent_key);
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
	if ($orders_type=='contacts_with_orders') {
		$where_type=' and `Customer With Orders`="Yes" ';
	}
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




	}


	$filter_msg='';
	$wheref='';


	if (($f_field=='customer name'     )  and $f_value!='') {
		$wheref="  and  `Customer Name` like '%".addslashes($f_value)."%'";
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

?>